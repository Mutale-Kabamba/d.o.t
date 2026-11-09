<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProjectSubmission extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected static function booted(): void
    {
        static::creating(function ($submission) {
            if (empty($submission->token)) {
                $submission->token = (string) Str::uuid();
            }
        });
    }

    /**
     * Helper to get bullet points array from multiline text field.
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

        // Check for custom styled badge: ( [Icon] Name - Type ) URL or [ [Icon] Name - Type ](URL)
        $badgePattern = '/(?:\(\s*([📁📄📊📑🎥📦🔗🖼️📸📕🎬📎][^()\n]+?)\s*\)|\[\s*([📁📄📊📑🎥📦🔗🖼️📸📕🎬📎][^\[\]\n]+?)\s*\])\s*\(?\s*(https?:\/\/[^\s<>"\'\)]+)\s*\)?/u';
        if (preg_match($badgePattern, $text)) {
            return preg_replace_callback($badgePattern, function ($m) use ($isPdf) {
                $label = trim(!empty($m[1]) ? $m[1] : $m[2]);
                $url = $m[3];

                if ($isPdf) {
                    return '<a href="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '" target="_blank" style="color: #1d4ed8; text-decoration: underline; font-weight: 700; font-size: 8.5px; word-break: break-all;">[' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . ']</a>';
                }

                return '<a href="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '" target="_blank" rel="noopener noreferrer" style="display: inline-flex; align-items: center; gap: 4px; padding: 2px 8px; border-radius: 6px; background-color: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; font-size: 11.5px; font-weight: 700; text-decoration: none; word-break: break-all; margin: 2px 4px; vertical-align: middle;"><span>' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '</span> <span style="font-size: 10px; opacity: 0.8;">↗</span></a>';
            }, htmlspecialchars($text, ENT_QUOTES, 'UTF-8'));
        }

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

        $badgePattern = '/(?:\(\s*([📁📄📊📑🎥📦🔗🖼️📸📕🎬📎][^()\n]+?)\s*\)|\[\s*([📁📄📊📑🎥📦🔗🖼️📸📕🎬📎][^\[\]\n]+?)\s*\])\s*\(?\s*(https?:\/\/[^\s<>"\'\)]+)\s*\)?/u';
        if (preg_match($badgePattern, $text)) {
            return preg_replace_callback($badgePattern, function ($m) {
                $label = trim(!empty($m[1]) ? $m[1] : $m[2]);
                return "[{$label}]";
            }, $text);
        }

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
        $uLower = strtolower($url);

        // 1. Photos & Images (Drive images, Google Photos, direct image links)
        if (
            str_contains($uLower, 'photos.google.com') ||
            str_contains($uLower, 'googleusercontent.com') ||
            preg_match('/\.(jpg|jpeg|png|gif|webp|svg|bmp|heic|tiff)(\?|$)/i', $url) ||
            preg_match('/(?:photo|image|picture|screenshot|img_)/i', $url)
        ) {
            return '🖼️ Image';
        }

        // 2. Google Workspace
        if (str_contains($url, 'docs.google.com/document')) {
            return '📄 Google Doc';
        }
        if (str_contains($url, 'docs.google.com/spreadsheets')) {
            return '📊 Google Sheets';
        }
        if (str_contains($url, 'docs.google.com/presentation')) {
            return '📑 Google Slides';
        }

        // 3. Drive Folders & Files
        if (str_contains($url, 'drive.google.com/drive/folders')) {
            return '📁 Drive Folder';
        }
        if (str_contains($url, 'drive.google.com/file') || str_contains($url, 'drive.google.com/open')) {
            return '📁 Drive File';
        }
        if (str_contains($url, 'drive.google.com')) {
            return '📁 Google Drive';
        }

        // 4. Media & Docs
        if (str_contains($url, 'youtube.com') || str_contains($url, 'youtu.be')) {
            return '🎥 Video Link';
        }
        if (preg_match('/\.pdf(\?|$)/i', $url)) {
            return '📕 PDF Document';
        }
        if (str_contains($url, 'dropbox.com')) {
            return '📦 Dropbox';
        }
        if (str_contains($url, 'onedrive') || str_contains($url, 'sharepoint.com')) {
            return '📁 OneDrive';
        }

        $host = parse_url($url, PHP_URL_HOST) ?? '';
        $cleanHost = preg_replace('/^www\./i', '', $host);
        return !empty($cleanHost) ? '🔗 ' . $cleanHost : '🔗 View Attachment';
    }
}
