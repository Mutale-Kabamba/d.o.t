<?php

namespace App\Services;

use Exception;
use ZipArchive;

class PptxParserService
{
    /**
     * Map slide item headers / keywords to database columns
     */
    protected static array $fieldKeywords = [
        'achievements_milestones' => ['key milestone', 'milestone', 'major milestone'],
        'achievements_impact' => ['impact evidence', 'impact', 'evidence of change'],
        'achievements_stories' => ['success stor', 'story', 'positive success'],

        'challenges_operational' => ['operational challenge', 'operational', 'implementation challenge'],
        'challenges_resources' => ['resource gap', 'resource', 'critical gap'],
        'challenges_risks' => ['risk monitoring', 'risk', 'identified risk', 'mitigation'],

        'learning_lessons' => ['lessons learned', 'lesson learned', 'key insight'],
        'learning_feedback' => ['community feedback', 'feedback', 'stakeholder feedback'],
        'learning_innovation' => ['innovation', 'innovative approach'],

        'mne_performance' => ['performance', 'against plan', 'activity milestone'],
        'mne_data_quality' => ['data quality', 'data check', 'verification'],
        'mne_evaluation_plans' => ['evaluation plan', 'evaluation', 'outcome and impact'],

        'collab_projects' => ['project collab', 'internal collab', 'inter-project'],
        'collab_partnerships' => ['strategic partnership', 'partnership', 'external partner'],
        'collab_cross_learning' => ['cross-learning', 'cross learning', 'knowledge sharing'],
    ];

    /**
     * Parse a PPTX file and return extracted project submissions array.
     *
     * @param string $filePath Absolute path to .pptx file
     * @return array Array of parsed projects
     */
    public function parse(string $filePath): array
    {
        if (!file_exists($filePath)) {
            throw new Exception("PowerPoint file not found at: {$filePath}");
        }

        $zip = new ZipArchive();
        if ($zip->open($filePath) !== true) {
            throw new Exception("Unable to open PowerPoint (.pptx) archive.");
        }

        $slidesXml = $this->extractSlidesXml($zip);
        $zip->close();

        if (empty($slidesXml)) {
            throw new Exception("No slides found in the uploaded PowerPoint file.");
        }

        // 1. Try parsing as Consolidated Multi-Project Presentation (Table or multi-row based)
        $consolidatedProjects = $this->parseConsolidatedSlides($slidesXml);
        if (!empty($consolidatedProjects)) {
            return $consolidatedProjects;
        }

        // 2. Parse as Single Project Presentation
        $singleProject = $this->parseSingleProjectSlides($slidesXml);
        return !empty($singleProject) ? [$singleProject] : [];
    }

    /**
     * Extract ordered slide XML strings from ZIP archive.
     */
    protected function extractSlidesXml(ZipArchive $zip): array
    {
        $slides = [];

        // Try reading relationship map to preserve exact slide order
        $relsContent = $zip->getFromName('ppt/_rels/presentation.xml.rels');
        $slideOrder = [];

        if ($relsContent !== false) {
            if (preg_match_all('/Target="(slides\/slide\d+\.xml)"/i', $relsContent, $matches)) {
                foreach ($matches[1] as $slideRelPath) {
                    $fullPath = 'ppt/' . ltrim($slideRelPath, '/');
                    if (!in_array($fullPath, $slideOrder)) {
                        $slideOrder[] = $fullPath;
                    }
                }
            }
        }

        // If rels ordering found, load in that order
        if (!empty($slideOrder)) {
            foreach ($slideOrder as $slidePath) {
                $xml = $zip->getFromName($slidePath);
                if ($xml !== false) {
                    $slides[] = $xml;
                }
            }
        }

        // Fallback: search all slide*.xml files in zip
        if (empty($slides)) {
            $slideFiles = [];
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $name = $zip->getNameIndex($i);
                if (preg_match('/^ppt\/slides\/slide\d+\.xml$/i', $name)) {
                    $slideFiles[] = $name;
                }
            }

            natsort($slideFiles);

            foreach ($slideFiles as $file) {
                $xml = $zip->getFromName($file);
                if ($xml !== false) {
                    $slides[] = $xml;
                }
            }
        }

        return $slides;
    }

    /**
     * Parse multi-project consolidated presentations.
     */
    protected function parseConsolidatedSlides(array $slidesXml): array
    {
        $projects = [];
        $hasTables = false;

        foreach ($slidesXml as $slideXml) {
            $tables = $this->extractTablesFromSlideXml($slideXml);
            if (empty($tables)) {
                continue;
            }

            $hasTables = true;

            foreach ($tables as $table) {
                if (count($table) < 2) {
                    continue;
                }

                // Row 0 is column headers
                $headerRow = $table[0];
                $colFieldMap = [];

                foreach ($headerRow as $cIdx => $headerText) {
                    $matchedField = $this->matchFieldKey($headerText);
                    if ($matchedField) {
                        $colFieldMap[$cIdx] = $matchedField;
                    }
                }

                if (empty($colFieldMap)) {
                    continue;
                }

                // Subsequent rows are projects
                for ($rIdx = 1; $rIdx < count($table); $rIdx++) {
                    $row = $table[$rIdx];
                    $firstCellText = trim($row[0] ?? '');
                    if (empty($firstCellText)) {
                        continue;
                    }

                    // Extract project name & officer name from cell header
                    $parsedMeta = $this->extractProjectAndOfficerFromCell($row);
                    $projName = $parsedMeta['project_name'];
                    $officerName = $parsedMeta['officer_name'];

                    if (empty($projName)) {
                        continue;
                    }

                    $projKey = strtolower($projName);
                    if (!isset($projects[$projKey])) {
                        $projects[$projKey] = [
                            'project_name' => $projName,
                            'officer_name' => $officerName ?: 'Project Officer',
                            'reporting_period' => 'Quarter 2 April, May, June 2026',
                        ];
                    } elseif (empty($projects[$projKey]['officer_name']) && !empty($officerName)) {
                        $projects[$projKey]['officer_name'] = $officerName;
                    }

                    // Extract content for each column
                    foreach ($colFieldMap as $cIdx => $fieldKey) {
                        if (!isset($row[$cIdx])) {
                            continue;
                        }

                        $cellContent = $this->cleanCellContent($row[$cIdx]);
                        if (!empty($cellContent)) {
                            if (empty($projects[$projKey][$fieldKey])) {
                                $projects[$projKey][$fieldKey] = $cellContent;
                            } else {
                                $projects[$projKey][$fieldKey] .= "\n" . $cellContent;
                            }
                        }
                    }
                }
            }
        }

        return array_values($projects);
    }

    /**
     * Parse single project slide deck into single project data dictionary.
     */
    protected function parseSingleProjectSlides(array $slidesXml): ?array
    {
        $data = [
            'project_name' => '',
            'officer_name' => '',
            'reporting_period' => 'Quarter 2 April, May, June 2026',
        ];

        // 1. Scan Cover slide for Project & Officer Info
        if (isset($slidesXml[0])) {
            $coverTexts = $this->extractTextParagraphsFromXml($slidesXml[0]);
            $this->extractCoverMetadata($coverTexts, $data);
        }

        // 2. Scan all slides for field sections & bullet points
        foreach ($slidesXml as $slideXml) {
            $shapes = $this->extractShapesFromSlideXml($slideXml);
            $count = count($shapes);

            for ($i = 0; $i < $count; $i++) {
                $shape = $shapes[$i];
                $shapeTitle = $shape['title'] ?? '';
                $shapeText = $shape['text'] ?? '';

                $matchedField = $this->matchFieldKey($shapeTitle);

                // If this shape title matched a field and had no body text, take text from adjacent shape
                if ($matchedField && empty(trim($shapeText)) && isset($shapes[$i + 1])) {
                    $nextShape = $shapes[$i + 1];
                    $shapeText = $nextShape['all_text'];
                }

                if (!$matchedField) {
                    $lines = preg_split('/\r\n|\r|\n/', trim($shape['all_text']));
                    if (!empty($lines[0])) {
                        $matchedField = $this->matchFieldKey($lines[0]);
                        if ($matchedField) {
                            array_shift($lines);
                            $shapeText = implode("\n", $lines);
                        }
                    }
                }

                if ($matchedField) {
                    $cleaned = $this->cleanBulletText($shapeText);
                    if (!empty($cleaned)) {
                        if (empty($data[$matchedField])) {
                            $data[$matchedField] = $cleaned;
                        } else {
                            $data[$matchedField] .= "\n" . $cleaned;
                        }
                    }
                }
            }
        }

        // If project name still empty, provide sensible default
        if (empty($data['project_name'])) {
            $data['project_name'] = 'Imported Project';
        }
        if (empty($data['officer_name'])) {
            $data['officer_name'] = 'Project Officer';
        }

        return $data;
    }

    /**
     * Extract tables from Slide XML.
     */
    protected function extractTablesFromSlideXml(string $xml): array
    {
        $tables = [];

        if (preg_match_all('/<a:tbl\b[^>]*>(.*?)<\/a:tbl>/is', $xml, $tblMatches)) {
            foreach ($tblMatches[1] as $tblXml) {
                $tableRows = [];
                if (preg_match_all('/<a:tr\b[^>]*>(.*?)<\/a:tr>/is', $tblXml, $trMatches)) {
                    foreach ($trMatches[1] as $trXml) {
                        $rowCells = [];
                        if (preg_match_all('/<a:tc\b[^>]*>(.*?)<\/a:tc>/is', $trXml, $tcMatches)) {
                            foreach ($tcMatches[1] as $tcXml) {
                                $cellParagraphs = [];
                                if (preg_match_all('/<a:p\b[^>]*>(.*?)<\/a:p>/is', $tcXml, $pMatches)) {
                                    foreach ($pMatches[1] as $pXml) {
                                        $tStrings = [];
                                        if (preg_match_all('/<a:t\b[^>]*>(.*?)<\/a:t>/is', $pXml, $tMatches)) {
                                            foreach ($tMatches[1] as $tStr) {
                                                $tStrings[] = $this->cleanXmlText($tStr);
                                            }
                                        }
                                        $pText = trim(implode('', $tStrings));
                                        if (!empty($pText)) {
                                            $cellParagraphs[] = $pText;
                                        }
                                    }
                                }
                                $rowCells[] = implode("\n", $cellParagraphs);
                            }
                        }
                        if (!empty($rowCells)) {
                            $tableRows[] = $rowCells;
                        }
                    }
                }
                if (!empty($tableRows)) {
                    $tables[] = $tableRows;
                }
            }
        }

        return $tables;
    }

    /**
     * Extract shapes / text boxes from Slide XML.
     */
    protected function extractShapesFromSlideXml(string $xml): array
    {
        $shapes = [];

        if (preg_match_all('/<p:sp\b[^>]*>(.*?)<\/p:sp>/is', $xml, $spMatches)) {
            foreach ($spMatches[1] as $spXml) {
                $paragraphs = [];
                if (preg_match_all('/<a:p\b[^>]*>(.*?)<\/a:p>/is', $spXml, $pMatches)) {
                    foreach ($pMatches[1] as $pXml) {
                        $tStrings = [];
                        if (preg_match_all('/<a:t\b[^>]*>(.*?)<\/a:t>/is', $pXml, $tMatches)) {
                            foreach ($tMatches[1] as $tStr) {
                                $tStrings[] = $this->cleanXmlText($tStr);
                            }
                        }
                        $pText = trim(implode('', $tStrings));
                        if (!empty($pText)) {
                            $paragraphs[] = $pText;
                        }
                    }
                }

                if (!empty($paragraphs)) {
                    $title = $paragraphs[0];
                    $bodyLines = array_slice($paragraphs, 1);
                    $shapes[] = [
                        'title' => $title,
                        'text' => implode("\n", $bodyLines),
                        'all_text' => implode("\n", $paragraphs),
                    ];
                }
            }
        }

        return $shapes;
    }

    /**
     * Extract raw text paragraph lines from XML.
     */
    protected function extractTextParagraphsFromXml(string $xml): array
    {
        $paragraphs = [];

        if (preg_match_all('/<a:p\b[^>]*>(.*?)<\/a:p>/is', $xml, $pMatches)) {
            foreach ($pMatches[1] as $pXml) {
                $tStrings = [];
                if (preg_match_all('/<a:t\b[^>]*>(.*?)<\/a:t>/is', $pXml, $tMatches)) {
                    foreach ($tMatches[1] as $tStr) {
                        $tStrings[] = $this->cleanXmlText($tStr);
                    }
                }
                $pText = trim(implode('', $tStrings));
                if (!empty($pText)) {
                    $paragraphs[] = $pText;
                }
            }
        }

        return $paragraphs;
    }

    /**
     * Clean and unwrap XML text node.
     */
    protected function cleanXmlText(string $raw): string
    {
        $cleaned = html_entity_decode($raw, ENT_QUOTES | ENT_XML1, 'UTF-8');
        $cleaned = preg_replace('/^<!\[CDATA\[(.*)\]\]>$/s', '$1', trim($cleaned));
        return $cleaned;
    }

    /**
     * Extract project name & officer name from cover slide paragraphs.
     */
    protected function extractCoverMetadata(array $coverLines, array &$data): void
    {
        foreach ($coverLines as $idx => $line) {
            $lower = strtolower($line);

            if (str_contains($lower, 'play it forward') || str_contains($lower, 'programmes meeting')) {
                continue;
            }

            if (str_contains($lower, 'quarter') || str_contains($lower, 'q2') || str_contains($lower, 'q1') || str_contains($lower, 'q3') || str_contains($lower, 'q4')) {
                $data['reporting_period'] = $line;
                continue;
            }

            if (preg_match('/project\s*:\s*(.+)$/i', $line, $m)) {
                $data['project_name'] = trim($m[1]);
                continue;
            }

            if (preg_match('/(officer|lead|submitted by)\s*:\s*(.+)$/i', $line, $m)) {
                $data['officer_name'] = trim($m[2]);
                continue;
            }

            // If project_name is still empty and it's a prominent line
            if (empty($data['project_name']) && strlen($line) < 60) {
                if (preg_match('/^([^\(•]+)\s*\(([^)]+)\)$/', $line, $m)) {
                    $data['project_name'] = trim($m[1]);
                    $data['officer_name'] = trim($m[2]);
                } else {
                    $data['project_name'] = $line;
                }
            }
        }
    }

    /**
     * Extract project name and officer name from table cell row.
     */
    protected function extractProjectAndOfficerFromCell(array $row): array
    {
        $projectName = '';
        $officerName = '';

        foreach ($row as $cell) {
            $lines = preg_split('/\r\n|\r|\n/', trim($cell));
            if (empty($lines)) {
                continue;
            }

            $firstLine = trim($lines[0]);

            // Match patterns like "Education • Caristo Maambo" or "Education (Caristo Maambo)"
            if (preg_match('/^([^\•\(\)]+)\s*[\•\-]\s*([^\•\(\)]+)$/u', $firstLine, $m)) {
                $projectName = trim($m[1]);
                $officerName = trim($m[2]);
                break;
            }

            if (preg_match('/^([^\(•]+)\s*\(([^)]+)\)$/', $firstLine, $m)) {
                $projectName = trim($m[1]);
                $officerName = trim($m[2]);
                break;
            }

            if (strlen($firstLine) < 40 && !preg_match('/^[\•\-\*\d]/', $firstLine)) {
                $projectName = $firstLine;
            }
        }

        return [
            'project_name' => $projectName,
            'officer_name' => $officerName,
        ];
    }

    /**
     * Clean cell content by stripping project header badge line if present.
     */
    protected function cleanCellContent(string $text): string
    {
        $lines = preg_split('/\r\n|\r|\n/', trim($text));
        if (empty($lines)) {
            return '';
        }

        // If line 0 is project badge (e.g. "Education • Caristo Maambo" or "No points entered"), strip it
        $first = trim($lines[0]);
        if (str_contains($first, '•') && !preg_match('/^[\•\-\*]/', $first)) {
            array_shift($lines);
        } elseif (preg_match('/^([^\(•]+)\s*\(([^)]+)\)$/', $first)) {
            array_shift($lines);
        }

        $filtered = [];
        foreach ($lines as $l) {
            $t = trim($l);
            if (!empty($t) && !str_starts_with(strtolower($t), 'no points') && !str_starts_with(strtolower($t), 'no key points')) {
                $filtered[] = $t;
            }
        }

        return implode("\n", $filtered);
    }

    /**
     * Clean bullet point text.
     */
    protected function cleanBulletText(string $text): string
    {
        $lines = preg_split('/\r\n|\r|\n/', trim($text));
        $cleaned = [];

        foreach ($lines as $line) {
            $t = trim($line);
            if (!empty($t) && !str_starts_with(strtolower($t), 'no points') && !str_starts_with(strtolower($t), 'no key points')) {
                $cleaned[] = $t;
            }
        }

        return implode("\n", $cleaned);
    }

    /**
     * Match a header / title string to one of our 15 schema keys.
     */
    protected function matchFieldKey(string $text): ?string
    {
        $lower = strtolower(trim($text));
        if (empty($lower)) {
            return null;
        }

        foreach (self::$fieldKeywords as $fieldKey => $keywords) {
            foreach ($keywords as $kw) {
                if (str_contains($lower, $kw)) {
                    return $fieldKey;
                }
            }
        }

        return null;
    }
}
