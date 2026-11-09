<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add Department and hierarchy columns to projects table
        Schema::table('projects', function (Blueprint $table) {
            if (!Schema::hasColumn('projects', 'parent_id')) {
                $table->foreignId('parent_id')->nullable()->after('id')->constrained('projects')->nullOnDelete();
            }
            if (!Schema::hasColumn('projects', 'is_department')) {
                $table->boolean('is_department')->default(false)->after('parent_id');
            }
        });

        // 2. Add Activity Type and Period/Schedule columns to activity_entries table
        Schema::table('activity_entries', function (Blueprint $table) {
            if (!Schema::hasColumn('activity_entries', 'activity_type')) {
                $table->string('activity_type')->default('activity')->after('activity_title');
            }
            if (!Schema::hasColumn('activity_entries', 'period_type')) {
                $table->string('period_type')->default('single_day')->after('activity_type');
            }
            if (!Schema::hasColumn('activity_entries', 'start_date')) {
                $table->date('start_date')->nullable()->after('activity_date');
            }
            if (!Schema::hasColumn('activity_entries', 'end_date')) {
                $table->date('end_date')->nullable()->after('start_date');
            }
            if (!Schema::hasColumn('activity_entries', 'period_cadence')) {
                $table->string('period_cadence')->nullable()->after('end_date');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activity_entries', function (Blueprint $table) {
            $columns = ['activity_type', 'period_type', 'start_date', 'end_date', 'period_cadence'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('activity_entries', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('projects', function (Blueprint $table) {
            if (Schema::hasColumn('projects', 'parent_id')) {
                $table->dropForeign(['parent_id']);
                $table->dropColumn('parent_id');
            }
            if (Schema::hasColumn('projects', 'is_department')) {
                $table->dropColumn('is_department');
            }
        });
    }
};
