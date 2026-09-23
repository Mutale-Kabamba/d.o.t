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

    protected static function booted(): void
    {
        static::creating(function ($entry) {
            if (empty($entry->token)) {
                $entry->token = (string) Str::uuid();
            }
            if (empty($entry->reporting_period) && !empty($entry->activity_date)) {
                $date = Carbon::parse($entry->activity_date);
                $quarter = ceil($date->month / 3);
                $entry->reporting_period = "Quarter {$quarter} " . $date->format('F Y');
            }
        });

        static::saving(function ($entry) {
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
                    // Sync combined sub-fields into points column if not manually overridden or to keep in sync
                    $entry->{$pointsCol} = implode("\n", $subContent);
                }
            }
        });
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
        if ($user->isSuperAdmin()) {
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
