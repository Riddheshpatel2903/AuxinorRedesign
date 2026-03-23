# ⚙️ PHASE 4: MODELS & RELATIONSHIPS — COMPLETE

Updated all models to work with the new schema-driven architecture.

---

## ✅ What Was Updated

### 1. PageSection Model
**File:** `app/Models/PageSection.php`

**New relationships:**
```php
$section->page();               // Belongs to Page
$section->sectionContents();    // Has many SectionContent
$section->elements();           // Has many PageElement (deprecated)
```

**New schema methods:**
```php
$section->schema();             // Get SectionSchema for this section
$section->trySchema();          // Get schema or null if invalid
$section->sectionTypeEnum();    // Get SectionType enum
$section->getContent();         // All content as key-value map
$section->getFieldValue('heading', 'default'); // Get specific field
```

**Example usage:**
```php
$section = PageSection::find(1);

// Get schema
$schema = $section->schema();
echo $schema->label();  // 'Hero Banner'

// Get all content
$content = $section->getContent();
echo $content['heading'];  // 'My Title'

// Get specific field
$heading = $section->getFieldValue('heading');
$image = $section->getFieldValue('background_image');

// Backward compat
$oldValue = $section->val('heading');  // From old content JSON
```

**Backwards compatibility:**
- Keeps old methods: `val()`, `element()`
- Falls back to PageElement system if SectionContent not found
- `section_key` → `section_type` migration (uses section_key as fallback)

**Style methods (unchanged):**
```php
$section->getStyleString();     // Inline CSS style attribute
$section->getAnimationClass();  // Animation class if defined
```

---

### 2. Page Model
**File:** `app/Models/Page.php`

**Updated relationships:**
```php
$page->sections();              // Has many visible sections
$page->allSections();           // All sections including hidden
```

**New query methods:**
```php
$page = Page::bySlug('about')->first();
$page = Page::active()->first();

$section = $page->findSection('hero');
$heroSections = $page->getSectionsByType('hero');
```

**Content methods:**
```php
$page->hasSections();           // Check if page has sections
$page->countSections();         // Count sections
$page->publish();               // Mark page as published
$page->isPublished();           // Check if published
```

**Accessors:**
```php
$page->url;                     // Get page URL
$page->display_name;            // Get display name for admin
```

**Example:**
```php
$page = Page::bySlug('about')->active()->first();

foreach ($page->sections() as $section) {
    echo $section->schema()->label();
    echo $section->getContent()['heading'];
}
```

---

### 3. PageElement Model
**File:** `app/Models/PageElement.php`

**Status:** ⚠️ **DEPRECATED**

Marked with deprecation notice in docblock:
```php
/**
 * @deprecated Use SectionContent model instead
 * 
 * This model is kept for backwards compatibility during migration.
 * All new code should use SectionContent.
 */
```

**Why kept:**
- Existing views still reference it
- Data migration happens in phases
- Backwards compatibility during transition

**Timeline to removal:**
1. ✅ PHASE 2: Created section_contents table
2. ✅ PHASE 3: Created SectionContent model
3. ✅ PHASE 4: Marked PageElement deprecated
4. FUTURE: Drop page_elements table

---

## 🏗️ Model Relationships Diagram

### Before (PHASE 1)
```
Page (title, slug) 
  ↓
PageSection (page_id, page_slug, section_key, content JSON)
  ↓
PageElement (element_key, element_type, content)
```

**Problems:**
- Double foreign keys (page_id + page_slug)
- Arbitrary element_key strings
- Content in wrong places

### After (PHASE 4)
```
Page (title, slug)
  ↓
PageSection (page_id, section_type) ← points to config/sections.php
  ↓
SectionContent (section_id, key, type, value, meta)
             ↓
         config/sections.php (schema)
```

**Benefits:**
- ✅ Clean hierarchy
- ✅ Schema-driven
- ✅ Normalized content
- ✅ Type safe

---

## 💡 Usage in Views

### Example 1: Render section with new system

```blade
@foreach($page->sections() as $section)
    @php
        $schema = $section->schema();
        $content = $section->getContent();
        $styles = $section->getStyleString();
    @endphp

    <section style="{{ $styles }}" data-section-type="{{ $section->section_type }}">
        @if($section->section_type === 'hero')
            <h1>{{ $content['heading'] ?? 'Untitled' }}</h1>
            <p>{{ $content['subheading'] ?? '' }}</p>
            @if($content['background_image'])
                <img src="{{ $content['background_image']['url'] }}" alt="{{ $content['background_image']['alt'] }}">
            @endif
        @elseif($section->section_type === 'cta')
            <h2>{{ $content['title'] }}</h2>
            <a href="{{ $content['button_link'] }}">{{ $content['button_text'] }}</a>
        @endif
    </section>
@endforeach
```

### Example 2: Render with helper function

```blade
@foreach($page->sections() as $section)
    @include("sections.{$section->section_type}", [
        'section' => $section,
        'content' => $section->getContent(),
        'schema' => $section->schema(),
    ])
@endforeach
```

---

## 🔄 Migration from PageElement to SectionContent

### Data Flow

```
OLD (page_elements)
────────────────────────────
id: 1
section_id: 5
element_key: "about_hero_title"
element_type: "text"
content: "My Title"
styles: {...}

        ↓ (Migration command)
        
NEW (section_contents)
────────────────────────────
id: 1
section_id: 5
key: "heading"
type: "text"
value: "My Title"
meta: null
```

### Migration Commands

```bash
# Populate page_id from page_slug
php artisan cms:populate-page-ids

# Migrate elements → contents
php artisan cms:migrate-elements

# Verify migration
php artisan cms:verify-migration
```

---

## 🚨 Breaking Changes

### ✅ Backwards Compatible
- Old methods still work: `$section->val()`, `$section->element()`
- Old relationships still exist: `$section->elements()`
- Old columns stay: `section_key`, `content` JSON
- Existing views continue to work

### ⚠️ New Code Should Use
```php
// OLD - still works but deprecated
$value = $section->val('heading');
$element = $section->element('heading');

// NEW - use this
$value = $section->getFieldValue('heading');
$content = $section->getContent();
$schema = $section->schema();
```

---

## 🧪 Testing Relationships

```php
// Create test page with section
$page = Page::create([
    'slug' => 'test',
    'title' => 'Test Page',
    'is_active' => true,
]);

$section = $page->sections()->create([
    'section_type' => 'hero',
    'sort_order' => 0,
    'is_visible' => true,
]);

// Add content
SectionContent::create([
    'section_id' => $section->id,
    'key' => 'heading',
    'type' => 'text',
    'value' => 'My Title',
]);

// Query
$schema = $section->schema();
$content = $section->getContent();
$heading = $section->getFieldValue('heading');

// Assertions
$this->assertEquals('My Title', $heading);
$this->assertEquals('hero', $section->section_type);
$this->assertTrue($section->schema()->isValid($content));
```

---

## 📊 Database State After PHASE 4

### pages
```
✅ Unchanged
id, title, slug (unique), meta_title, meta_description, is_active
created_at, updated_at
```

### page_sections
```
✅ ENHANCED
id
page_id ← Foreign key (NOT NULL)
page_slug ← Deprecated but still populated
section_type ← NEW: 'hero', 'cta', 'content', etc.
section_key ← Deprecated (kept for backwards compat)
section_label ← Deprecated
sort_order
is_visible
content ← Deprecated (moved to section_contents)
styles ← KEEP
published_at
bg_image, bg_overlay, bg_color
created_at, updated_at
```

### section_contents (✨ NEW)
```
id
section_id ← Foreign key
key ← field name: 'heading', 'description', etc.
type ← 'text', 'html', 'image', 'url', etc.
value ← TEXT
meta ← JSON: {image_url, image_path, image_alt, ...}
sort_order
created_at, updated_at

UNIQUE(section_id, key)
INDEX(section_id, type)
```

### page_elements (DEPRECATED)
```
⚠️  Still exists for backwards compat
Data being migrated to section_contents
Will be dropped in future cleanup phase
```

---

## 🎯 What's Next (PHASE 5)

**PHASE 5: CONTROLLERS — CLEAN SEPARATION**
- Create AdminPageController (CRUD pages)
- Create SectionController (manage sections)
- Create SectionContentController (edit content)
- Remove mixed logic from PageEditorController
- Remove catalogue editing from CMS

---

## # ✅ PHASE 4 COMPLETE

Models are now schema-aware and properly structured.

Key achievements:
- ✅ PageSection knows its schema
- ✅ Clean relationships (Page → Section → Content)
- ✅ Backwards compatible with old code
- ✅ Type-safe with enums
- ✅ Content properly normalized

Next: PHASE 5 — Build clean controller layer
