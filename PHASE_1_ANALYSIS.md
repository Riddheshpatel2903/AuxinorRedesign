# PHASE 1: CMS / VISUAL EDITOR ANALYSIS

## Status
Complete

This phase is analysis only. No architecture should be considered complete until the implementation matches the requirements in this document.

## Existing System Reviewed

### Database / Migrations
- `database/migrations/2026_03_19_170927_create_page_sections_table.php`
- `database/migrations/2026_03_19_170928_create_page_elements_table.php`
- `database/migrations/2026_03_21_104843_create_pages_table.php`
- `database/migrations/2026_03_21_104910_add_page_id_to_page_sections_table.php`
- `database/migrations/2026_03_22_100000_add_section_type_to_page_sections.php`
- `database/migrations/2026_03_22_100001_create_section_contents_table.php`
- `database/migrations/2026_03_22_100002_require_page_id_in_sections.php`

### Models
- `app/Models/Page.php`
- `app/Models/PageSection.php`
- `app/Models/SectionContent.php`

### Controllers
- `app/Http/Controllers/Admin/PageEditorController.php`
- `app/Http/Controllers/Admin/AdminPageController.php`
- `app/Http/Controllers/Admin/SectionController.php`
- `app/Http/Controllers/Admin/SectionContentController.php`
- `app/Http/Controllers/Admin/SectionStyleController.php`
- `app/Http/Controllers/PageController.php`

### Routes
- `routes/web.php`

### Blade Views
- `resources/views/admin/editor/index.blade.php`
- `resources/views/admin/editor/editor.blade.php`
- `resources/views/admin/editor/preview.blade.php`
- `resources/views/components/sections/*.blade.php`
- Legacy page views such as:
  - `resources/views/about.blade.php`
  - `resources/views/contact.blade.php`
  - `resources/views/industries.blade.php`
  - `resources/views/infrastructure.blade.php`
  - `resources/views/products/index.blade.php`
  - `resources/views/partials/navbar.blade.php`
  - `resources/views/partials/footer.blade.php`

### Editor JavaScript
- `public/js/editor.js`
- `public/js/editor-receiver.js`

## Problems

### 1. Database architecture is still transitional, not clean
- `pages` does not match the requested shape.
- It still uses `meta_title`, `meta_description`, and `is_active` instead of a clean `status` field.
- `page_sections` still contains legacy columns:
  - `page_slug`
  - `section_key`
  - `section_label`
  - `content`
  - `published_at`
- `page_elements` still exists, so the old element system has not actually been removed.

### 2. Legacy compatibility is still driving core runtime behavior
- `PageSection` still exposes old-style helpers and deprecated fallback behavior.
- `SectionRenderer` still contains legacy element fallback paths.
- Public rendering still builds `pageSections` keyed by `section_key`.

### 3. Public page rendering is still slug-view based
- `PageController` returns `view($slug, $data)`.
- This keeps the system tied to page-specific Blade templates instead of generic section rendering.
- The requested architecture was:
  - load page by slug
  - load sections
  - loop sections
  - include section template by section type

### 4. CMS is still mixed with non-CMS concerns
- Admin preview still loads global settings and stats.
- Section components still read from global settings in multiple places.
- Public pages still inject catalogue and settings-driven behavior inside page rendering.
- This violates the rule to keep products, industries, and settings out of CMS editing logic.

### 5. Section schema exists, but section components do not consistently follow it
- `config/sections.php` defines a schema, but several section components still use legacy keys.
- Example issues:
  - hero component expects keys like `hero_label`, `hero_headline_line1`
  - grid component expects keys like `grid_item_1_title`
- Those keys are not aligned with the current schema definition and make editor generation unreliable.

### 6. Style system is still split between structured and ad hoc storage
- Structured `styles` JSON exists, but dedicated fields like `bg_image`, `bg_overlay`, and `bg_color` are still on `page_sections`.
- Styling is still partly routed through legacy editor assumptions.
- There is not yet a single clean style contract centered on one normalized style payload.

### 7. Visual editor UI is still wired to the wrong API contract
- `resources/views/admin/editor/editor.blade.php` references old route names that do not exist in the new route layer.
- The editor JS still assumes old payload shapes such as element-level editing and legacy route endpoints.
- The page editor view also expects variables that are not passed consistently.

### 8. Some code claims refactor completion before the architecture is actually finished
- `REFACTORING_PROGRESS.md` marked phases as complete even though the codebase still contains major gaps.
- This creates implementation risk because later work would be built on false assumptions.

## Reusable Parts

### 1. New normalized content table
- `section_contents` is a good direction.
- The `SectionContent` model is reusable.
- Key/value content plus `meta` is suitable for schema-driven sections.

### 2. Schema registry direction
- `config/sections.php` is reusable as the schema source of truth.
- `SectionType`, `SectionSchema`, `Field`, and validation classes are worth keeping and refining.

### 3. Controller separation direction
- Splitting responsibilities into:
  - `AdminPageController`
  - `SectionController`
  - `SectionContentController`
  - `SectionStyleController`
  is the right architectural direction.

### 4. Component-based section rendering
- `SectionRenderer` and `resources/views/components/sections/*` are reusable as a concept.
- They need cleanup, but the approach is correct.

### 5. Page ownership via `page_id`
- Moving sections to a real page relationship is correct and should be kept.

## Things To Remove

### Remove from schema / runtime
- `page_elements` table
- old PageElement-based editing assumptions
- legacy key patterns like:
  - `industry:1:name`
  - `product:2:image`
  - `el_setting:company_name`

### Remove from `page_sections`
- `page_slug`
- `section_key`
- `section_label`
- `content`
- `published_at`
- dedicated background fields if styles are normalized fully into the style payload

### Remove from CMS/editor responsibilities
- product editing logic
- industry editing logic
- global settings editing logic
- stats/settings coupling in preview rendering

### Remove from rendering architecture
- slug-specific page Blade templates as the primary rendering path
- legacy `pageSections` compatibility map
- old editor route names and payload shapes in the admin editor UI

## Phase 1 Output Summary

### Problems identified
- mixed responsibilities remain
- database is still transitional
- old element system still exists
- section schema is only partially applied
- rendering is still slug-coupled
- editor UI is still using old contracts

### Reusable parts identified
- `section_contents`
- schema config and helper classes
- separated controller direction
- component-based section rendering approach
- real page relationship via `page_id`

### Things to remove identified
- old element system
- legacy `page_sections` columns
- non-CMS business logic inside CMS/editor
- old rendering/editor compatibility layers

## Gate Before Phase 2

Phase 2 should begin with a clean redesign migration plan that:
- creates the final target page schema
- removes dependency on legacy page/section columns
- defines a migration path from old data into the final structure
- does not preserve legacy runtime behavior as a permanent architecture
