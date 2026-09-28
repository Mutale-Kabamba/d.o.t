<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ActivityEntry extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'pillar_1_achievements' => 'array',
        'pillar_2_challenges' => 'array',
        'pillar_3_learning' => 'array',
        'pillar_4_monitoring' => 'array',
        'pillar_5_collaboration' => 'array',
        'activity_date' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    protected static function booted(): void
    {
        static::creating(function ($entry) {
            if (empty($entry->token)) {
                $entry->token = (string) Str::uuid();
            }
            if (empty($entry->activity_type)) {
                $entry->activity_type = 'activity';
            }
            if (empty($entry->period_type)) {
                $entry->period_type = in_array($entry->activity_type, ['session', 'class', 'training']) && !empty($entry->end_date)
                    ? 'date_range'
                    : 'single_day';
            }
            if (empty($entry->start_date) && !empty($entry->activity_date)) {
                $entry->start_date = $entry->activity_date;
            }
            if (empty($entry->activity_date) && !empty($entry->start_date)) {
                $entry->activity_date = $entry->start_date;
            }
            if (empty($entry->period_granularity)) {
                $entry->period_granularity = 'quarter';
            }
            if (empty($entry->reporting_period) && !empty($entry->activity_date)) {
                $date = Carbon::parse($entry->activity_date);
                $quarter = ceil($date->month / 3);
                $entry->reporting_period = "Quarter {$quarter} " . $date->format('F Y');
            }
        });

        static::saving(function ($entry) {
            // Helper to extract points and narrative from sub-pillar input
            $parseSubPillar = function ($data, $fallbackCol = null) use ($entry) {
                $points = [];
                $narrative = '';

                if (is_array($data)) {
                    if (isset($data['points'])) {
                        $points = is_array($data['points']) ? $data['points'] : self::extractBulletPoints($data['points']);
                    } else {
                        // Check if it's a numeric array of bullet strings
                        $isList = true;
                        foreach (array_keys($data) as $k) {
                            if (!is_int($k)) { $isList = false; break; }
                        }
                        if ($isList) {
                            $points = $data;
                        }
                    }
                    if (isset($data['narrative'])) {
                        $narrative = trim((string) $data['narrative']);
                    }
                } elseif (!empty($data) && is_string($data)) {
                    $points = self::extractBulletPoints($data);
                } elseif (!empty($fallbackCol) && !empty($entry->{$fallbackCol})) {
                    $points = self::extractBulletPoints($entry->{$fallbackCol});
                }

                // Clean bullet list
                $cleanedPoints = [];
                foreach ((array)$points as $pt) {
                    $trimmed = trim((string)$pt);
                    if (!empty($trimmed)) {
                        $cleanedPoints[] = $trimmed;
                    }
                }

                return [
                    'points' => array_values($cleanedPoints),
                    'narrative' => $narrative,
                ];
            };

            // 1. Pillar 1: Achievements
            $p1 = is_array($entry->pillar_1_achievements) ? $entry->pillar_1_achievements : [];
            $m = $parseSubPillar($p1['milestones'] ?? null, 'achievements_milestones');
            $i = $parseSubPillar($p1['impact'] ?? null, 'achievements_impact');
            $s = $parseSubPillar($p1['stories'] ?? null, 'achievements_stories');

            if (!empty($p1['milestones_narrative']) && empty($m['narrative'])) $m['narrative'] = trim($p1['milestones_narrative']);
            if (!empty($p1['impact_narrative']) && empty($i['narrative'])) $i['narrative'] = trim($p1['impact_narrative']);
            if (!empty($p1['stories_narrative']) && empty($s['narrative'])) $s['narrative'] = trim($p1['stories_narrative']);

            $entry->pillar_1_achievements = [
                'milestones' => $m,
                'impact' => $i,
                'stories' => $s,
            ];
            $entry->achievements_milestones = implode("\n", $m['points']);
            $entry->achievements_impact = implode("\n", $i['points']);
            $entry->achievements_stories = implode("\n", $s['points']);

            // Pillar 1 synthesis
            $subNarratives1 = array_filter([$m['narrative'], $i['narrative'], $s['narrative']]);
            if (empty($entry->pillar_1_narrative) && !empty($subNarratives1)) {
                $entry->pillar_1_narrative = implode("\n\n", $subNarratives1);
            }
            if (!empty($entry->pillar_1_narrative)) {
                $entry->achievements_narrative = $entry->pillar_1_narrative;
            } elseif (!empty($entry->achievements_narrative)) {
                $entry->pillar_1_narrative = $entry->achievements_narrative;
            }

            // 2. Pillar 2: Challenges
            $p2 = is_array($entry->pillar_2_challenges) ? $entry->pillar_2_challenges : [];
            $op = $parseSubPillar($p2['operational'] ?? null, 'challenges_operational');
            $res = $parseSubPillar($p2['resources'] ?? null, 'challenges_resources');
            $risk = $parseSubPillar($p2['risks'] ?? null, 'challenges_risks');

            if (!empty($p2['operational_narrative']) && empty($op['narrative'])) $op['narrative'] = trim($p2['operational_narrative']);
            if (!empty($p2['resources_narrative']) && empty($res['narrative'])) $res['narrative'] = trim($p2['resources_narrative']);
            if (!empty($p2['risks_narrative']) && empty($risk['narrative'])) $risk['narrative'] = trim($p2['risks_narrative']);

            $entry->pillar_2_challenges = [
                'operational' => $op,
                'resources' => $res,
                'risks' => $risk,
            ];
            $entry->challenges_operational = implode("\n", $op['points']);
            $entry->challenges_resources = implode("\n", $res['points']);
            $entry->challenges_risks = implode("\n", $risk['points']);

            // Pillar 2 synthesis
            $subNarratives2 = array_filter([$op['narrative'], $res['narrative'], $risk['narrative']]);
            if (empty($entry->pillar_2_narrative) && !empty($subNarratives2)) {
                $entry->pillar_2_narrative = implode("\n\n", $subNarratives2);
            }
            if (!empty($entry->pillar_2_narrative)) {
                $entry->challenges_narrative = $entry->pillar_2_narrative;
            } elseif (!empty($entry->challenges_narrative)) {
                $entry->pillar_2_narrative = $entry->challenges_narrative;
            }

            // 3. Pillar 3: Learning
            $p3 = is_array($entry->pillar_3_learning) ? $entry->pillar_3_learning : [];
            $les = $parseSubPillar($p3['lessons'] ?? null, 'learning_lessons');
            $feed = $parseSubPillar($p3['feedback'] ?? null, 'learning_feedback');
            $inno = $parseSubPillar($p3['innovation'] ?? null, 'learning_innovation');

            if (!empty($p3['lessons_narrative']) && empty($les['narrative'])) $les['narrative'] = trim($p3['lessons_narrative']);
            if (!empty($p3['feedback_narrative']) && empty($feed['narrative'])) $feed['narrative'] = trim($p3['feedback_narrative']);
            if (!empty($p3['innovation_narrative']) && empty($inno['narrative'])) $inno['narrative'] = trim($p3['innovation_narrative']);

            $entry->pillar_3_learning = [
                'lessons' => $les,
                'feedback' => $feed,
                'innovation' => $inno,
            ];
            $entry->learning_lessons = implode("\n", $les['points']);
            $entry->learning_feedback = implode("\n", $feed['points']);
            $entry->learning_innovation = implode("\n", $inno['points']);

            // Pillar 3 synthesis
            $subNarratives3 = array_filter([$les['narrative'], $feed['narrative'], $inno['narrative']]);
            if (empty($entry->pillar_3_narrative) && !empty($subNarratives3)) {
                $entry->pillar_3_narrative = implode("\n\n", $subNarratives3);
            }
            if (!empty($entry->pillar_3_narrative)) {
                $entry->learning_narrative = $entry->pillar_3_narrative;
            } elseif (!empty($entry->learning_narrative)) {
                $entry->pillar_3_narrative = $entry->learning_narrative;
            }

            // 4. Pillar 4: M&E
            $p4 = is_array($entry->pillar_4_monitoring) ? $entry->pillar_4_monitoring : [];
            $perf = $parseSubPillar($p4['performance'] ?? null, 'mne_performance');
            $dq = $parseSubPillar($p4['data_quality'] ?? null, 'mne_data_quality');
            $eval = $parseSubPillar($p4['evaluation'] ?? null, 'mne_evaluation_plans');

            if (!empty($p4['performance_narrative']) && empty($perf['narrative'])) $perf['narrative'] = trim($p4['performance_narrative']);
            if (!empty($p4['data_quality_narrative']) && empty($dq['narrative'])) $dq['narrative'] = trim($p4['data_quality_narrative']);
            if (!empty($p4['evaluation_narrative']) && empty($eval['narrative'])) $eval['narrative'] = trim($p4['evaluation_narrative']);

            $entry->pillar_4_monitoring = [
                'performance' => $perf,
                'data_quality' => $dq,
                'evaluation' => $eval,
            ];
            $entry->mne_performance = implode("\n", $perf['points']);
            $entry->mne_data_quality = implode("\n", $dq['points']);
            $entry->mne_evaluation_plans = implode("\n", $eval['points']);

            // Pillar 4 synthesis
            $subNarratives4 = array_filter([$perf['narrative'], $dq['narrative'], $eval['narrative']]);
            if (empty($entry->pillar_4_narrative) && !empty($subNarratives4)) {
                $entry->pillar_4_narrative = implode("\n\n", $subNarratives4);
            }
            if (!empty($entry->pillar_4_narrative)) {
                $entry->mne_narrative = $entry->pillar_4_narrative;
            } elseif (!empty($entry->mne_narrative)) {
                $entry->pillar_4_narrative = $entry->mne_narrative;
            }

            // 5. Pillar 5: Collaboration
            $p5 = is_array($entry->pillar_5_collaboration) ? $entry->pillar_5_collaboration : [];
            $proj = $parseSubPillar($p5['project_collab'] ?? $p5['projects'] ?? null, 'collab_projects');
            $part = $parseSubPillar($p5['partnerships'] ?? null, 'collab_partnerships');
            $cross = $parseSubPillar($p5['cross_learning'] ?? null, 'collab_cross_learning');

            if (!empty($p5['project_collab_narrative']) && empty($proj['narrative'])) $proj['narrative'] = trim($p5['project_collab_narrative']);
            if (!empty($p5['partnerships_narrative']) && empty($part['narrative'])) $part['narrative'] = trim($p5['partnerships_narrative']);
            if (!empty($p5['cross_learning_narrative']) && empty($cross['narrative'])) $cross['narrative'] = trim($p5['cross_learning_narrative']);

            $entry->pillar_5_collaboration = [
                'project_collab' => $proj,
                'partnerships' => $part,
                'cross_learning' => $cross,
            ];
            $entry->collab_projects = implode("\n", $proj['points']);
            $entry->collab_partnerships = implode("\n", $part['points']);
            $entry->collab_cross_learning = implode("\n", $cross['points']);

            // Pillar 5 synthesis
            $subNarratives5 = array_filter([$proj['narrative'], $part['narrative'], $cross['narrative']]);
            if (empty($entry->pillar_5_narrative) && !empty($subNarratives5)) {
                $entry->pillar_5_narrative = implode("\n\n", $subNarratives5);
            }
            if (!empty($entry->pillar_5_narrative)) {
                $entry->collab_narrative = $entry->pillar_5_narrative;
            } elseif (!empty($entry->collab_narrative)) {
                $entry->pillar_5_narrative = $entry->collab_narrative;
            }

            // Sync legacy points columns
            $pillarMapping = [
                'achievements_points' => ['achievements_milestones', 'achievements_impact', 'achievements_stories'],
                'challenges_points' => ['challenges_operational', 'challenges_resources', 'challenges_risks'],
                'learning_points' => ['learning_lessons', 'learning_feedback', 'learning_innovation'],
                'mne_points' => ['mne_performance', 'mne_data_quality', 'mne_evaluation_plans'],
                'collab_points' => ['collab_projects', 'collab_partnerships', 'collab_cross_learning'],
            ];

            foreach ($pillarMapping as $pointsCol => $subFields) {
                $subContent = [];
                foreach ($subFields as $sf) {
                    $val = trim($entry->{$sf} ?? '');
                    if (!empty($val)) {
                        $subContent[] = $val;
                    }
                }

                if (!empty($subContent)) {
                    $entry->{$pointsCol} = implode("\n", $subContent);
                }
            }
        });
    }

    /**
     * Check if activity is an ongoing type (e.g. Training, Class, Session).
     */
    public function isOngoing(): bool
    {
        $type = strtolower($this->activity_type ?? 'activity');
        return in_array($type, ['training', 'class', 'session', 'ongoing', 'session_training_class'])
            || !empty($this->end_date);
    }

    /**
     * Get human-friendly label for activity type.
     */
    public function getTypeLabelAttribute(): string
    {
        return match (strtolower($this->activity_type ?? 'activity')) {
            'training' => 'Training',
            'class' => 'Class',
            'session' => 'Session',
            'ongoing', 'session_training_class' => 'Session / Class / Training',
            default => 'Activity',
        };
    }

    /**
     * Get appropriate label: "Location" for ongoing sessions/trainings/classes, "Venue" for activities.
     */
    public function getVenueOrLocationLabelAttribute(): string
    {
        return $this->isOngoing() ? 'Location' : 'Venue';
    }

    /**
     * Accessor for venue, alias for location.
     */
    public function getVenueAttribute(): ?string
    {
        return $this->location;
    }

    /**
     * Mutator for venue, alias for location.
     */
    public function setVenueAttribute($value): void
    {
        $this->attributes['location'] = $value;
    }

    /**
     * Formatted date or period range with duration.
     */
    public function getFormattedPeriodAttribute(): string
    {
        $start = $this->start_date ? Carbon::parse($this->start_date) : ($this->activity_date ? Carbon::parse($this->activity_date) : null);
        $end = $this->end_date ? Carbon::parse($this->end_date) : null;

        if (!$start) {
            return 'No date set';
        }

        if (!$end || $start->toDateString() === $end->toDateString()) {
            return $start->format('d M Y');
        }

        $diffDays = $start->diffInDays($end) + 1;
        $duration = $diffDays >= 7
            ? round($diffDays / 7, 1) . ' weeks (' . $diffDays . ' days)'
            : $diffDays . ' days';

        return $start->format('d M') . ' – ' . $end->format('d M Y') . ' (' . $duration . ')';
    }

    /**
     * Get bullet points array for a specific pillar and sub-category.
     */
    public function getPillarBullets(int $pillarNumber, string $subField): array
    {
        $pillarColumn = match ($pillarNumber) {
            1 => 'pillar_1_achievements',
            2 => 'pillar_2_challenges',
            3 => 'pillar_3_learning',
            4 => 'pillar_4_monitoring',
            5 => 'pillar_5_collaboration',
            default => null,
        };

        $pillarData = $pillarColumn ? $this->{$pillarColumn} : null;

        if (is_array($pillarData)) {
            // 1. Check exact subField key
            if (!empty($pillarData[$subField])) {
                $data = $pillarData[$subField];
                if (is_array($data)) {
                    if (isset($data['points'])) {
                        $pts = is_array($data['points']) ? $data['points'] : self::extractBulletPoints($data['points']);
                        $cleaned = array_values(array_filter(array_map('trim', $pts), fn($p) => $p !== ''));
                        if (!empty($cleaned)) {
                            return $cleaned;
                        }
                    } else {
                        $cleaned = array_values(array_filter(array_map('trim', $data), fn($p) => $p !== ''));
                        if (!empty($cleaned)) {
                            return $cleaned;
                        }
                    }
                } elseif (is_string($data)) {
                    $pts = self::extractBulletPoints($data);
                    if (!empty($pts)) {
                        return $pts;
                    }
                }
            }

            // 2. Check alias for pillar 5 (project_collab vs projects)
            if ($pillarNumber === 5 && ($subField === 'project_collab' || $subField === 'projects')) {
                $alt = $subField === 'project_collab' ? 'projects' : 'project_collab';
                if (!empty($pillarData[$alt])) {
                    $data = $pillarData[$alt];
                    if (is_array($data)) {
                        if (isset($data['points'])) {
                            $pts = is_array($data['points']) ? $data['points'] : self::extractBulletPoints($data['points']);
                            $cleaned = array_values(array_filter(array_map('trim', $pts), fn($p) => $p !== ''));
                            if (!empty($cleaned)) {
                                return $cleaned;
                            }
                        } else {
                            $cleaned = array_values(array_filter(array_map('trim', $data), fn($p) => $p !== ''));
                            if (!empty($cleaned)) {
                                return $cleaned;
                            }
                        }
                    } elseif (is_string($data)) {
                        $pts = self::extractBulletPoints($data);
                        if (!empty($pts)) {
                            return $pts;
                        }
                    }
                }
            }
        }

        // 3. Fallback to direct dedicated database columns
        $subFieldToColumn = [
            1 => [
                'milestones' => 'achievements_milestones',
                'impact' => 'achievements_impact',
                'stories' => 'achievements_stories',
            ],
            2 => [
                'operational' => 'challenges_operational',
                'resources' => 'challenges_resources',
                'risks' => 'challenges_risks',
            ],
            3 => [
                'lessons' => 'learning_lessons',
                'feedback' => 'learning_feedback',
                'innovation' => 'learning_innovation',
            ],
            4 => [
                'performance' => 'mne_performance',
                'data_quality' => 'mne_data_quality',
                'evaluation' => 'mne_evaluation_plans',
            ],
            5 => [
                'project_collab' => 'collab_projects',
                'projects' => 'collab_projects',
                'partnerships' => 'collab_partnerships',
                'cross_learning' => 'collab_cross_learning',
            ],
        ];

        $fallbackCol = $subFieldToColumn[$pillarNumber][$subField] ?? $subField;
        if (!empty($this->{$fallbackCol})) {
            return $this->getPoints($fallbackCol);
        }

        return [];
    }

    /**
     * Get qualitative narrative for a specific sub-pillar section.
     */
    public function getSubPillarNarrative(int $pillarNumber, string $subField): string
    {
        $pillarColumn = match ($pillarNumber) {
            1 => 'pillar_1_achievements',
            2 => 'pillar_2_challenges',
            3 => 'pillar_3_learning',
            4 => 'pillar_4_monitoring',
            5 => 'pillar_5_collaboration',
            default => null,
        };

        $pillarData = $pillarColumn ? $this->{$pillarColumn} : null;

        if (is_array($pillarData)) {
            if (!empty($pillarData[$subField])) {
                $data = $pillarData[$subField];
                if (is_array($data) && !empty($data['narrative'])) {
                    return trim((string) $data['narrative']);
                }
            }

            if ($pillarNumber === 5 && ($subField === 'project_collab' || $subField === 'projects')) {
                $alt = $subField === 'project_collab' ? 'projects' : 'project_collab';
                if (!empty($pillarData[$alt]) && is_array($pillarData[$alt]) && !empty($pillarData[$alt]['narrative'])) {
                    return trim((string) $pillarData[$alt]['narrative']);
                }
            }

            if (!empty($pillarData[$subField . '_narrative'])) {
                return trim((string) $pillarData[$subField . '_narrative']);
            }
        }

        return '';
    }

    /**
     * Get qualitative narrative for a specific pillar.
     */
    public function getPillarNarrative(int $pillarNumber): ?string
    {
        $narrativeColumn = match ($pillarNumber) {
            1 => 'pillar_1_narrative',
            2 => 'pillar_2_narrative',
            3 => 'pillar_3_narrative',
            4 => 'pillar_4_narrative',
            5 => 'pillar_5_narrative',
            default => null,
        };

        return $narrativeColumn ? ($this->{$narrativeColumn} ?: null) : null;
    }

    /**
     * Project this activity entry belongs to.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * User who logged this activity entry.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    /**
     * Helper to get bullet points array from a thematic presentation points or sub-section field.
     * Preserves leading numbers, percentages, and metrics (e.g. "98% of girls passed", "100+ participants", "1 in 4").
     */
    public function getPoints(string $field): array
    {
        $text = trim($this->{$field} ?? '');
        return self::extractBulletPoints($text);
    }

    /**
     * Static helper to extract bullet points from text without destroying metrics.
     */
    public static function extractBulletPoints(?string $text): array
    {
        $text = trim($text ?? '');
        if (empty($text)) {
            return [];
        }

        $lines = preg_split('/\r\n|\r|\n/', $text);
        $points = [];

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) {
                continue;
            }

            // 1. Strip true bullet symbols, dashes, and list markers (•, -, *, –, —, >)
            $cleaned = preg_replace('/^[\s\-\*\•\–\—\>]+/u', '', $line);

            // 2. Strip standard ordered list numbering (e.g., "1. ", "1) ", "(1) ", "1: ", "1 - ")
            // Crucially: only when a number is followed by a punctuation separator (. ) : - ) and whitespace,
            // NOT when followed by % or letters (e.g. "98%", "100+", "1 in 4" remain untouched).
            $cleaned = preg_replace('/^\s*\(?\d+\)?[.:\-\)]\s+/u', '', $cleaned);

            $cleaned = trim($cleaned);

            if (!empty($cleaned)) {
                $points[] = $cleaned;
            }
        }

        return !empty($points) ? $points : [$text];
    }

    /**
     * Convert URLs in a bullet point into neat, compact, clickable link badges/anchors.
     */
    public static function formatPointHtml(string $text, bool $isPdf = false): string
    {
        $text = trim($text);
        if (empty($text)) {
            return '';
        }

        // Clean up common bullet artifacts at the beginning if any remained
        $text = preg_replace('/^[\s\-\*\•\–\—\>]+/u', '', $text);
        $text = preg_replace('/^\s*\(?\d+\)?[.:\-\)]\s+/u', '', $text);
        $text = trim($text);

        // Regex pattern for URLs
        $pattern = '/https?:\/\/[^\s<>"\'\)]+/i';

        // Check if the point is strictly a standalone URL
        if (preg_match('/^https?:\/\/[^\s<>"\'\)]+$/i', $text, $matches)) {
            $url = $matches[0];
            $label = self::getSmartUrlLabel($url);

            if ($isPdf) {
                return '<a href="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '" target="_blank" style="color: #1d4ed8; text-decoration: underline; font-weight: 700; font-size: 8.5px; word-break: break-all; display: inline-block;">' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '</a>';
            }

            return '<a href="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '" target="_blank" rel="noopener noreferrer" style="display: inline-flex; align-items: center; gap: 4px; padding: 2px 7px; border-radius: 6px; background-color: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; font-size: 11.5px; font-weight: 700; text-decoration: none; word-break: break-all; margin: 1px 0; max-width: 100%;"><span>' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '</span> <span style="font-size: 10px; opacity: 0.8;">↗</span></a>';
        }

        // If URL is embedded inside text, escape non-URL text and hyperlink the URL cleanly
        $escaped = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
        return preg_replace_callback($pattern, function ($matches) use ($isPdf) {
            $url = $matches[0];
            $label = self::getSmartUrlLabel($url);

            if ($isPdf) {
                return '<a href="' . $url . '" target="_blank" style="color: #1d4ed8; text-decoration: underline; font-weight: 700; font-size: 8.5px; word-break: break-all;">[' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . ']</a>';
            }

            return '<a href="' . $url . '" target="_blank" rel="noopener noreferrer" style="color: #1d4ed8; text-decoration: underline; font-weight: 700; word-break: break-all; display: inline-block;">[' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . ' ↗]</a>';
        }, $escaped);
    }

    /**
     * Format a point for plain text (e.g. PPTX) by simplifying raw long URLs into clean labeled placeholders.
     */
    public static function formatPointText(string $text): string
    {
        $text = trim($text);
        if (empty($text)) {
            return '';
        }

        $text = preg_replace('/^[\s\-\*\•\–\—\>]+/u', '', $text);
        $text = preg_replace('/^\s*\(?\d+\)?[.:\-\)]\s+/u', '', $text);
        $text = trim($text);

        $pattern = '/https?:\/\/[^\s<>"\'\)]+/i';

        return preg_replace_callback($pattern, function ($matches) {
            $url = $matches[0];
            $label = self::getSmartUrlLabel($url);
            return "[{$label}]";
        }, $text);
    }

    /**
     * Get a clean, human-readable friendly label for URLs.
     */
    public static function getSmartUrlLabel(string $url): string
    {
        $host = parse_url($url, PHP_URL_HOST) ?? '';

        if (str_contains($url, 'docs.google.com/document')) {
            return '📄 Google Document';
        }
        if (str_contains($url, 'docs.google.com/spreadsheets')) {
            return '📊 Google Spreadsheet';
        }
        if (str_contains($url, 'docs.google.com/presentation')) {
            return '📑 Google Presentation';
        }
        if (str_contains($url, 'drive.google.com/drive/folders') || str_contains($url, 'drive.google.com/drive/u/') || str_contains($url, 'drive.google.com/file')) {
            return '📁 Google Drive Folder';
        }
        if (str_contains($url, 'youtube.com') || str_contains($url, 'youtu.be')) {
            return '🎥 Video Link';
        }
        if (str_contains($url, 'dropbox.com')) {
            return '📦 Dropbox Attachment';
        }
        if (str_contains($url, 'onedrive') || str_contains($url, 'sharepoint.com')) {
            return '📁 OneDrive Attachment';
        }

        $cleanHost = preg_replace('/^www\./i', '', $host);
        return !empty($cleanHost) ? '🔗 ' . $cleanHost : '🔗 View Attachment';
    }

    /**
     * Scope for User access (scoped to user's assigned projects unless super admin).
     */
    public function scopeForUser(Builder $query, User $user): Builder
    {
        if ($user->hasAdminAccess()) {
            return $query;
        }

        return $query->whereHas('project.users', function ($q) use ($user) {
            $q->where('users.id', $user->id);
        });
    }

    /**
     * Filter by interval (day, month, quarter, year, or custom range).
     */
    public function scopeFilterInterval(Builder $query, string $interval, array $params = []): Builder
    {
        return match ($interval) {
            'day' => $query->whereDate('activity_date', $params['date'] ?? now()->toDateString()),
            'month' => $query->whereMonth('activity_date', $params['month'] ?? now()->month)
                             ->whereYear('activity_date', $params['year'] ?? now()->year),
            'quarter' => (function () use ($query, $params) {
                $qNum = (int) ($params['quarter'] ?? ceil(now()->month / 3));
                $year = (int) ($params['year'] ?? now()->year);
                $startMonth = (($qNum - 1) * 3) + 1;
                $endMonth = $startMonth + 2;
                $startDate = Carbon::create($year, $startMonth, 1)->startOfMonth();
                $endDate = Carbon::create($year, $endMonth, 1)->endOfMonth();
                return $query->whereBetween('activity_date', [$startDate->toDateString(), $endDate->toDateString()]);
            })(),
            'year' => $query->whereYear('activity_date', $params['year'] ?? now()->year),
            'custom' => !empty($params['from_date']) && !empty($params['to_date'])
                ? $query->whereBetween('activity_date', [$params['from_date'], $params['to_date']])
                : $query,
            default => $query,
        };
    }
}
