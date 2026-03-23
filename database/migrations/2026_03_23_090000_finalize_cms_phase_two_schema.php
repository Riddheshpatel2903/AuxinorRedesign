<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('pages')) {
            Schema::table('pages', function (Blueprint $table) {
                if (!Schema::hasColumn('pages', 'status')) {
                    $table->string('status', 20)->default('draft')->after('slug');
                }
            });

            DB::table('pages')
                ->whereNull('status')
                ->orWhere('status', '')
                ->update(['status' => 'draft']);

            if (Schema::hasColumn('pages', 'is_active')) {
                DB::table('pages')
                    ->where('is_active', true)
                    ->update(['status' => 'published']);
            }
        }

        if (Schema::hasTable('page_sections')) {
            Schema::table('page_sections', function (Blueprint $table) {
                if (!Schema::hasColumn('page_sections', 'type')) {
                    $table->string('type')->nullable()->after('page_id');
                }
            });

            $sections = DB::table('page_sections')
                ->select('id', 'type', 'section_type', 'section_key', 'page_slug', 'page_id')
                ->get();

            foreach ($sections as $section) {
                $updates = [];

                if (empty($section->type)) {
                    $updates['type'] = $section->section_type ?: ($section->section_key ?: 'content');
                }

                if (empty($section->page_id) && !empty($section->page_slug) && Schema::hasTable('pages')) {
                    $pageId = DB::table('pages')
                        ->where('slug', $section->page_slug)
                        ->value('id');

                    if ($pageId) {
                        $updates['page_id'] = $pageId;
                    }
                }

                if (!empty($updates)) {
                    DB::table('page_sections')
                        ->where('id', $section->id)
                        ->update($updates);
                }
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('page_sections') && Schema::hasColumn('page_sections', 'type')) {
            Schema::table('page_sections', function (Blueprint $table) {
                $table->dropColumn('type');
            });
        }

        if (Schema::hasTable('pages') && Schema::hasColumn('pages', 'status')) {
            Schema::table('pages', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};
