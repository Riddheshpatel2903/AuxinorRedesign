# PHASE 2: DATABASE REDESIGN

## Status
In progress

Phase 2 is focused on the database contract and migration path only.

## Target Database Shape

### pages
- `id`
- `title`
- `slug`
- `status`
- `created_at`

### page_sections
- `id`
- `page_id`
- `type`
- `sort_order`
- `is_visible`
- `styles`

### section_contents
- `id`
- `section_id`
- `key`
- `type`
- `value`
- `meta`

## What Already Exists

- `pages` table exists, but still includes legacy metadata columns and `is_active`
- `page_sections` table exists, but still includes legacy CMS columns
- `section_contents` exists and is the correct long-term direction
- `page_elements` still exists and must be migrated away from

## What This Phase Implements

### Final target columns introduced
- `pages.status`
- `page_sections.type`

### Backfill path introduced
- backfill `pages.status` from existing page state
- backfill `page_sections.type` from `section_type` or `section_key`
- backfill missing `page_id` from `page_slug`
- migrate useful legacy `page_elements` rows into `section_contents`

## Files Added / Updated

### Migration
- `database/migrations/2026_03_23_090000_finalize_cms_phase_two_schema.php`

Purpose:
- add final target columns required for the clean CMS contract
- backfill them from legacy columns so later phases can move to the new contract safely

### Commands
- `app/Console/Commands/BackfillCmsStructure.php`
- `app/Console/Commands/MigrateElementsToSectionContents.php`
- `app/Console/Commands/PopulatePageIdsInSections.php`

Purpose:
- make the legacy-to-final migration path executable
- remove dependency on the deleted `PageElement` model
- keep migration reviewable with `--dry-run`

## Important Notes

- This phase does not yet remove all legacy columns from runtime code.
- That removal should happen only after later phases switch rendering, models, and controllers to the final contract.
- This is an intentional bridge so the database can move toward the target architecture without breaking the app in one step.

## Commands To Run

```bash
php artisan migrate
php artisan cms:backfill-structure --dry-run
php artisan cms:backfill-structure
php artisan cms:populate-page-ids --dry-run
php artisan cms:populate-page-ids
php artisan cms:migrate-elements --dry-run
php artisan cms:migrate-elements
```

## Cleanup Deferred To Later Phases

These are still scheduled for removal after the app fully switches to the new contract:

- `page_sections.page_slug`
- `page_sections.section_key`
- `page_sections.section_label`
- `page_sections.content`
- `page_sections.published_at`
- `page_elements` table
- compatibility logic that depends on legacy keys

## Exit Criteria For Phase 2

Phase 2 is considered complete when:
- final target columns exist
- legacy data can be backfilled into them
- section content can be migrated out of `page_elements`
- later phases can safely target `pages.status`, `page_sections.type`, and `section_contents`
