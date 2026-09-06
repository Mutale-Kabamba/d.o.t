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
     */
    public function getPoints(string $field): array
    {
        $text = trim($this->{$field} ?? '');
        if (empty($text)) {
            return [];
        }

        $lines = preg_split('/\r\n|\r|\n/', $text);
        $points = [];

        foreach ($lines as $line) {
            $cleaned = trim(preg_replace('/^[\s\-\*\•\d+\.\)]+/', '', $line));
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
        $text = preg_replace('/^[\s\-\*\•\d+\.\)]+/', '', $text);

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

        $text = preg_replace('/^[\s\-\*\•\d+\.\)]+/', '', $text);
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
}
