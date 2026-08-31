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
}
