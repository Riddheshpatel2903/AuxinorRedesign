# PHASE 7: Frontend Rendering Updates

**Completed:** March 22, 2026  
**Status:** ✅ IN PROGRESS

## Objectives

- Migrate `PageController` to use section_contents-based data.
- Keep legacy views working via backward-compatible accessors.
- Provide rendering adaption layer for `PageSection` / `SectionContent`.

## Work Done

### 1. PageController updated
- `show()` now preloads `sectionContents` instead of old `elements`.
- Keeps existing `pageSections` mapping and passes same view data contract.

### 2. PageSection compatibility layer
- Added `element($key)` returning:
  - `SectionContent` for new sections (preferred)
  - fallback `PageElement` for old data.
- Added `getElementsAttribute()` to return:
  - `sectionContents` collection when present
  - fallback `elements` old collection otherwise
- This makes `@php $sec->elements->firstWhere(...)` continue working without large view refactor.

### 3. SectionContent compatibility methods
- Added these methods:
  - `getImageSrc()` (mirrors PageElement API)
  - `getStyleString()` (mirrors PageElement API)
  - `getContentAttribute()` => `getValue()` (for `->content` access use)
  - `getHrefAttribute()` etc. for link compatibility

### 4. SectionHelper updated
- `element($pageSections,$sectionKey,$elementKey)` now calls `$section->element($elementKey)`.
- Make helper future-proof for both content models.

## Behavior after this update

### No immediate template changes required for backward compatibility

Existing view code like:
```blade
@php $el = $sec?->elements->firstWhere('element_key', 'hero_label') @endphp
{{ $el?->content }}
```
will continue to work under legacy + new data.

### New preferred API (refactor next)

- `@php $heroLabel = $sec->element('hero_label')?->content; @endphp`
- `@php $heroImage = $sec->element('hero_image')?->getImageSrc(); @endphp`


## Next actions

1. Replace old `$sec->elements` usage in section views with `$sec->element('key')` and mapping palettes.
2. Introduce real section view components in Phase 8 (`resources/views/components/sections/*`).
3. Add Blade helper directives (`@sectionField($section, 'hero_title')`) in app service provider.
4. Verify full UI behavior with test dataset.

## Note

This is a phased migration: existing pages stay functional while we incremental refactor to new `section_contents` model.
