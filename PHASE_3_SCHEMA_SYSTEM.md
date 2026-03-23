# 🧠 PHASE 3: SECTION SCHEMA SYSTEM — COMPLETE

The schema layer provides validation, type safety, and a clean API for schema-driven sections.

---

## ✅ What Was Created

### 1. Schema Configuration
**File:** `config/sections.php`

Defines all section types and their fields:
```php
'hero' => [
    'label' => 'Hero Banner',
    'fields' => [
        ['key' => 'heading', 'type' => 'text', 'label' => 'Main Heading', 'maxLength' => 200],
        ['key' => 'image', 'type' => 'image', 'label' => 'Background Image'],
        // ... more fields
    ],
    'styles' => ['padding_top', 'background_color', ...],
]
```

**Purpose:**
- Single source of truth for section structure
- No database lookups (cached by Laravel)
- Easy to extend with new section types

---

### 2. SectionType Enum
**File:** `app/Enums/SectionType.php`

Type-safe enum for section types:
```php
use App\Enums\SectionType;

SectionType::HERO;           // 'hero'
SectionType::CTA;            // 'cta'
SectionType::CONTACT;        // 'contact'

SectionType::HERO->label();  // 'Hero Banner'
SectionType::options();      // ['hero' => 'Hero Banner', 'cta' => '...']
```

**Benefits:**
- ✅ No string typos
- ✅ IDE autocomplete
- ✅ Easy to find all usages

---

### 3. Field Class
**File:** `app/CMS/Field.php`

Represents a single editable field:
```php
$field = new Field([
    'key' => 'heading',
    'type' => 'text',
    'label' => 'Main Heading',
    'maxLength' => 200,
    'required' => true,
]);

$field->key();            // 'heading'
$field->type();           // 'text'
$field->label();          // 'Main Heading'
$field->isRequired();     // true
$field->maxLength();      // 200
$field->validate('...');  // Validate value
```

**Features:**
- ✅ Encapsulates field metadata
- ✅ Validation support
- ✅ Type inference
- ✅ Smart accessors

**Field Types Supported:**
- `text` — single-line text (max length)
- `html` — rich text content
- `image` — image upload + metadata
- `url` — hyperlink with validation
- `select` — dropdown from options
- `number` — numeric value
- `boolean` — true/false toggle
- `json` — structured data

---

### 4. SectionSchema Class
**File:** `app/CMS/SectionSchema.php`

Load and query section schema:
```php
use App\CMS\SectionSchema;

// Load schema
$schema = SectionSchema::for('hero');

// Query fields
$schema->label();           // 'Hero Banner'
$schema->description();     // '...'
$schema->fields();          // Collection<Field>
$schema->field('heading');  // Field object
$schema->fieldKeys();       // ['heading', 'subheading', 'button_text', ...]

// Validation
$schema->hasField('heading');      // true
$schema->validate($contentData);   // [] or [errors]
$schema->isValid($contentData);    // true/false

// Metadata
$schema->styles();          // ['padding_top', 'background_color', ...]
$schema->icon();            // 'hero'
$schema->isCatalogue();     // false
$schema->hasRequiredFields(); // true
$schema->template();        // Default values
```

**Methods:**
- `for()` — Load schema by type
- `tryFor()` — Load schema or return null
- `fields()` — Get all fields as Collection
- `field()` — Get specific field (or null)
- `fieldKeys()` — Get just the keys
- `styles()` — Allowed CSS properties
- `validate()` — Validate content
- `isValid()` — Boolean validation result
- `template()` — New content template with defaults

---

### 5. SectionValidator Class
**File:** `app/CMS/SectionValidator.php`

Validate section content against schema:
```php
use App\CMS\SectionValidator;

$validator = new SectionValidator('hero');

// Schema-based validation
$validator->validate($requestData);           // Array of errors
$validator->isValid($requestData);            // true/false
$validator->validateOrFail($requestData);     // Throw if invalid

// Get Laravel validation rules
$rules = SectionValidator::rules('hero');
// Returns: ['heading' => 'required|string|max:200', ...]

// Validate with Laravel
$validated = Validator::make($data, SectionValidator::rules('hero'))->validate();
```

**Validation Features:**
- ✅ Required/optional fields
- ✅ Type-specific rules (text, image, URL, etc.)
- ✅ Length constraints
- ✅ Select options validation
- ✅ File size/type for images
- ✅ URL format validation
- ✅ Unknown field detection

---

### 6. SectionContent Model
**File:** `app/Models/SectionContent.php`

New model for section_contents table:
```php
use App\Models\SectionContent;

// Get content
$content = SectionContent::where('section_id', 1)
    ->where('key', 'heading')
    ->first();

// Create/update
SectionContent::upsert(
    sectionId: 1,
    key: 'heading',
    value: 'My Title',
    type: 'text',
    meta: null,
);

// Bulk get as map
$contentMap = SectionContent::mapForSection(1);
// Returns: ['heading' => 'My Title', 'description' => '...', ...]

// Get all content for section
$fullContent = SectionContent::getForSection(1);

// Get specific field with fallback
$heading = SectionContent::getField(1, 'heading', 'Default Title');

// Relationships
$content = SectionContent::find(1);
$content->section;  // The PageSection

// Scopes
SectionContent::forSection(1)->get();     // All content for section 1
SectionContent::forKey('heading')->get(); // All 'heading' fields
```

**Accessors & Helpers:**
- `getValue()` — Get value (resolve image URLs)
- `getMeta()` — Get metadata
- `getImageMeta()` — Get image info (url, path, alt)
- `getLinkMeta()` — Get link info (href, text, style)
- `isImage()` — Check if image type
- `isHtml()` — Check if HTML type
- `isLink()` — Check if URL type

---

## 🏗️ Architecture

### Schema System Flow

```
config/sections.php
        ↓
   (Laravel cache)
        ↓
   SectionSchema::for('hero')
        ↓
    [Field, Field, Field, ...]
        ↓
   Validation checks
        ↓
   [errors] or ok → Save to section_contents table
        ↓
   SectionContent model
        ↓
   PageSection relationship
```

### Data Flow

```
User enters content in editor
           ↓
AJAX POST /editor/content
           ↓
PageEditorController
           ↓
SectionValidator::validate()  ← Check against config/sections.php
           ↓
SectionContent::upsert()      ← Save to database
           ↓
View reads SectionContent
           ↓
Render on page
```

---

## 💡 Usage Examples

### Example 1: Load & Display Section Content

```php
// Controller
$section = PageSection::find(1);
$schema = SectionSchema::for($section->section_type);
$content = SectionContent::getForSection($section->id);

return view('sections.hero', [
    'section' => $section,
    'schema' => $schema,
    'content' => $content,
]);
```

```blade
{{-- Blade --}}
<section style="{{ $section->getStyleString() }}">
    <h1>{{ $content['heading'] ?? 'Default' }}</h1>
    <p>{{ $content['description'] ?? '' }}</p>
    <img src="{{ $content['image']['url'] }}" alt="{{ $content['image']['alt'] }}">
</section>
```

### Example 2: Validate & Save Content

```php
// Controller
$validator = new SectionValidator($request->section_type);

if (!$validator->isValid($request->content)) {
    return response()->json([
        'ok' => false,
        'errors' => $validator->validate($request->content),
    ]);
}

// Save all fields
foreach ($request->content as $key => $value) {
    SectionContent::upsert(
        sectionId: $section->id,
        key: $key,
        value: is_array($value) ? json_encode($value) : $value,
        type: $validator->schema()->field($key)?->type() ?? 'text',
    );
}

return response()->json(['ok' => true]);
```

### Example 3: Generate Editor Form

```php
$schema = SectionSchema::for('hero');

// Get fields for form generation
foreach ($schema->fields() as $field) {
    echo "
    <div class='form-group'>
        <label>{$field->label()}</label>
        <input type='{$field->type()}' 
               name='{$field->key()}'
               placeholder='{$field->placeholder()}'
               maxlength='{$field->maxLength()}'
               {$field->isRequired() ? 'required' : ''}>
        <small>{$field->description()}</small>
    </div>";
}
```

### Example 4: Type Safety with Enum

```php
// Use enum instead of strings
switch ($section->section_type) {
    case SectionType::HERO->value:
        return view('sections.hero', ...);
    case SectionType::CTA->value:
        return view('sections.cta', ...);
    // ...
}

// Or with enum directly
if ($section->section_type === SectionType::HERO->value) {
    // ...
}

// Generate dropdown
<select name="section_type">
    @foreach(SectionType::options() as $value => $label)
        <option value="{{ $value }}">{{ $label }}</option>
    @endforeach
</select>
```

---

## 📋 Adding a New Section Type

### Step 1: Add to config/sections.php
```php
'testimonial' => [
    'label' => 'Testimonial',
    'description' => 'Customer testimonial with avatar',
    'icon' => 'quote',
    'fields' => [
        ['key' => 'quote', 'type' => 'html', 'label' => 'Quote', 'required' => true],
        ['key' => 'author', 'type' => 'text', 'label' => 'Author Name', 'maxLength' => 100],
        ['key' => 'role', 'type' => 'text', 'label' => 'Author Role', 'maxLength' => 100],
        ['key' => 'avatar', 'type' => 'image', 'label' => 'Avatar Image'],
        ['key' => 'rating', 'type' => 'select', 'label' => 'Rating', 'options' => ['5', '4', '3', '2', '1']],
    ],
    'styles' => ['padding_top', 'padding_bottom', 'background_color'],
],
```

### Step 2: Add enum case (optional)
```php
// app/Enums/SectionType.php
case TESTIMONIAL = 'testimonial';

public function label(): string {
    return match ($this) {
        // ...
        self::TESTIMONIAL => 'Testimonial',
    };
}
```

### Step 3: Create blade component
```blade
{{-- resources/views/sections/testimonial.blade.php --}}
<blockquote style="{{ $section->getStyleString() }}">
    <p>{{ $content['quote'] }}</p>
    <footer>
        <img src="{{ $content['avatar']['url'] }}" alt="{{ $content['avatar']['alt'] }}">
        <strong>{{ $content['author'] }}</strong>
        <small>{{ $content['role'] }}</small>
    </footer>
</blockquote>
```

### That's it! Schema is live.

---

## 🔒 Type Safety

### Before (PHASE 1)
```php
// No validation, arbitrary strings
$elLabel = $sec->elements->firstWhere('element_key', 'about_hero_label');
// What if 'about_hero_label' doesn't exist? Silent fail.
```

### After (PHASE 3)
```php
$schema = SectionSchema::for($section->section_type);
$field = $schema->field('heading');

if (!$field) {
    throw new \InvalidArgumentException("Field 'heading' not found in schema");
}

// Type-safe, validated
```

---

## 🚀 What's Next (PHASE 4)

**PHASE 4: MODELS & RELATIONSHIPS**
- Update PageSection model to use section_type
- Create clean relationship between Page → Section → Content
- Add caching for schema lookups
- Add PageSection helper methods
- Document model relationships

---

# ✅ PHASE 3 COMPLETE

Schema system is production-ready. Validation, type safety, and clean API in place.

Next: PHASE 4 — Update models to use new schema system.
