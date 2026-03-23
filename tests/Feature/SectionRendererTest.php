<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\PageSection;
use App\View\Components\SectionRenderer;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SectionRendererTest extends TestCase
{
    use RefreshDatabase;

    public function test_renders_default_view_when_specific_section_view_missing()
    {
        $page = \App\Models\Page::create(['title' => 'Home', 'slug' => 'home', 'is_active' => true]);

        $section = PageSection::create([
            'page_id' => $page->id,
            'page_slug' => 'home',
            'section_key' => 'this_section_does_not_exist',
            'section_type' => 'content',
            'section_label' => 'Fallback Section',
            'sort_order' => 10,
            'is_visible' => true,
        ]);

        $renderer = new SectionRenderer($section, [], []);

        $this->assertSame('components.sections.default', $renderer->render());
    }

    public function test_renders_hero_view_when_component_exists()
    {
        $page = \App\Models\Page::create(['title' => 'Home', 'slug' => 'home', 'is_active' => true]);

        $section = PageSection::create([
            'page_id' => $page->id,
            'page_slug' => 'home',
            'section_key' => 'hero',
            'section_type' => 'hero',
            'section_label' => 'Hero Section',
            'sort_order' => 1,
            'is_visible' => true,
        ]);

        $renderer = new SectionRenderer($section, ['hero_headline_line1' => 'Test'], []);

        $this->assertSame('components.sections.hero', $renderer->render());

        $rendered = view($renderer->render(), ['section' => $section, 'globalSettings' => ['hero_headline_line1' => 'Test'], 'stats' => []])->render();

        $this->assertStringContainsString('Trusted Chemical Trading', $rendered);
        $this->assertStringContainsString('Explore Products', $rendered);
    }

    public function test_services_strip_section_renders_service_cards()
    {
        $page = \App\Models\Page::create(['title' => 'Home', 'slug' => 'home', 'is_active' => true]);
        $section = PageSection::create([
            'page_id' => $page->id,
            'page_slug' => 'home',
            'section_key' => 'services_strip',
            'section_type' => 'services_strip',
            'section_label' => 'Services Strip',
            'sort_order' => 2,
            'is_visible' => true,
        ]);

        $renderer = new \App\View\Components\SectionRenderer($section, ['service_1_title' => 'Trading'], []);

        $this->assertSame('components.sections.services_strip', $renderer->render());

        $rendered = view($renderer->render(), ['section' => $section, 'globalSettings' => ['service_1_title' => 'Trading'], 'stats' => []])->render();

        $this->assertStringContainsString('Service 1', $rendered);
    }

    public function test_section_uses_section_content_over_legacy_elements()
    {
        $page = \App\Models\Page::create(['title' => 'Home', 'slug' => 'home', 'is_active' => true]);
        $section = PageSection::create([
            'page_id' => $page->id,
            'page_slug' => 'home',
            'section_key' => 'hero',
            'section_type' => 'hero',
            'section_label' => 'Hero Section',
            'sort_order' => 1,
            'is_visible' => true,
        ]);

        \App\Models\SectionContent::create([
            'section_id' => $section->id,
            'key' => 'hero_headline_line1',
            'type' => 'text',
            'value' => 'SectionContent headline',
        ]);

        $content = $section->getFieldValue('hero_headline_line1');

        $this->assertSame('SectionContent headline', $content);
    }

    public function test_stats_section_template_renders_metrics()
    {
        $page = \App\Models\Page::create(['title' => 'Home', 'slug' => 'home', 'is_active' => true]);
        $section = PageSection::create([
            'page_id' => $page->id,
            'page_slug' => 'home',
            'section_key' => 'stats',
            'section_type' => 'stats',
            'section_label' => 'Statistics',
            'sort_order' => 3,
            'is_visible' => true,
        ]);

        $renderer = new \App\View\Components\SectionRenderer($section, ['stat_1_number' => '42', 'stat_1_label' => 'Projects'], []);

        $this->assertSame('components.sections.stats', $renderer->render());

        \App\Models\SectionContent::create(['section_id' => $section->id, 'key' => 'stat_1_number', 'type' => 'text', 'value' => '42']);
        \App\Models\SectionContent::create(['section_id' => $section->id, 'key' => 'stat_1_label', 'type' => 'text', 'value' => 'Projects']);

        $this->assertSame('components.sections.stats', $renderer->render());

        $rendered = view($renderer->render(), ['section' => $section, 'globalSettings' => [], 'stats' => []])->render();

        $this->assertStringContainsString('42', $rendered);
        $this->assertStringContainsString('Projects', $rendered);
    }

    public function test_cta_section_template_renders_cta_button()
    {
        $page = \App\Models\Page::create(['title' => 'Home', 'slug' => 'home', 'is_active' => true]);
        $section = PageSection::create([
            'page_id' => $page->id,
            'page_slug' => 'home',
            'section_key' => 'cta',
            'section_type' => 'cta',
            'section_label' => 'CTA',
            'sort_order' => 4,
            'is_visible' => true,
        ]);

        $renderer = new \App\View\Components\SectionRenderer($section, [], []);

        \App\Models\SectionContent::create(['section_id' => $section->id, 'key' => 'button_text', 'type' => 'text', 'value' => 'Start Now']);
        \App\Models\SectionContent::create(['section_id' => $section->id, 'key' => 'button_link', 'type' => 'url', 'value' => '/start']);

        $this->assertSame('components.sections.cta', $renderer->render());

        $rendered = view($renderer->render(), ['section' => $section, 'globalSettings' => [], 'stats' => []])->render();

        $this->assertStringContainsString('Start Now', $rendered);
        $this->assertStringContainsString('/start', $rendered);
    }

    public function test_features_section_template_renders_feature_cards()
    {
        $page = \App\Models\Page::create(['title' => 'Home', 'slug' => 'home', 'is_active' => true]);
        $section = PageSection::create([
            'page_id' => $page->id,
            'page_slug' => 'home',
            'section_key' => 'features',
            'section_type' => 'features',
            'section_label' => 'Features',
            'sort_order' => 5,
            'is_visible' => true,
        ]);

        \App\Models\SectionContent::create(['section_id' => $section->id, 'key' => 'feature_1_title', 'type' => 'text', 'value' => 'Fast Delivery']);
        \App\Models\SectionContent::create(['section_id' => $section->id, 'key' => 'feature_1_description', 'type' => 'text', 'value' => 'Get products quickly.']);

        $renderer = new \App\View\Components\SectionRenderer($section, [], []);

        $this->assertSame('components.sections.features', $renderer->render());

        $rendered = view($renderer->render(), ['section' => $section, 'globalSettings' => [], 'stats' => []])->render();

        $this->assertStringContainsString('Fast Delivery', $rendered);
        $this->assertStringContainsString('Get products quickly.', $rendered);
    }

    public function test_grid_section_template_renders_grid_layout()
    {
        $page = \App\Models\Page::create(['title' => 'Home', 'slug' => 'home', 'is_active' => true]);
        $section = PageSection::create([
            'page_id' => $page->id,
            'page_slug' => 'home',
            'section_key' => 'grid',
            'section_type' => 'grid',
            'section_label' => 'Grid Section',
            'sort_order' => 6,
            'is_visible' => true,
        ]);

        \App\Models\SectionContent::create(['section_id' => $section->id, 'key' => 'title', 'type' => 'text', 'value' => 'Our Services']);
        \App\Models\SectionContent::create(['section_id' => $section->id, 'key' => 'columns', 'type' => 'select', 'value' => '3']);
        \App\Models\SectionContent::create(['section_id' => $section->id, 'key' => 'grid_item_1_title', 'type' => 'text', 'value' => 'Service One']);

        $renderer = new \App\View\Components\SectionRenderer($section, [], []);

        $this->assertSame('components.sections.grid', $renderer->render());

        $rendered = view($renderer->render(), ['section' => $section, 'globalSettings' => [], 'stats' => []])->render();

        $this->assertStringContainsString('Our Services', $rendered);
        $this->assertStringContainsString('Service One', $rendered);
        $this->assertStringContainsString('grid-cols-1 md:grid-cols-2 lg:grid-cols-3', $rendered);
    }

    public function test_content_section_template_renders_content_block()
    {
        $page = \App\Models\Page::create(['title' => 'Home', 'slug' => 'home', 'is_active' => true]);
        $section = PageSection::create([
            'page_id' => $page->id,
            'page_slug' => 'home',
            'section_key' => 'content',
            'section_type' => 'content',
            'section_label' => 'Content Section',
            'sort_order' => 7,
            'is_visible' => true,
        ]);

        \App\Models\SectionContent::create(['section_id' => $section->id, 'key' => 'title', 'type' => 'text', 'value' => 'About Us']);
        \App\Models\SectionContent::create(['section_id' => $section->id, 'key' => 'description', 'type' => 'html', 'value' => '<p>We are a great company.</p>']);
        \App\Models\SectionContent::create(['section_id' => $section->id, 'key' => 'image', 'type' => 'image', 'value' => '/images/about.jpg']);

        $renderer = new \App\View\Components\SectionRenderer($section, [], []);

        $this->assertSame('components.sections.content', $renderer->render());

        $rendered = view($renderer->render(), ['section' => $section, 'globalSettings' => [], 'stats' => []])->render();

        $this->assertStringContainsString('About Us', $rendered);
        $this->assertStringContainsString('We are a great company.', $rendered);
        $this->assertStringContainsString('/images/about.jpg', $rendered);
    }

    public function test_mission_section_template_renders_quote_styling()
    {
        $page = \App\Models\Page::create(['title' => 'Home', 'slug' => 'home', 'is_active' => true]);
        $section = PageSection::create([
            'page_id' => $page->id,
            'page_slug' => 'home',
            'section_key' => 'mission',
            'section_type' => 'mission',
            'section_label' => 'Mission Section',
            'sort_order' => 8,
            'is_visible' => true,
        ]);

        \App\Models\SectionContent::create(['section_id' => $section->id, 'key' => 'title', 'type' => 'text', 'value' => 'Our Mission']);
        \App\Models\SectionContent::create(['section_id' => $section->id, 'key' => 'description', 'type' => 'html', 'value' => '<p>To provide excellent service.</p>']);

        $renderer = new \App\View\Components\SectionRenderer($section, [], []);

        $this->assertSame('components.sections.mission', $renderer->render());

        $rendered = view($renderer->render(), ['section' => $section, 'globalSettings' => [], 'stats' => []])->render();

        $this->assertStringContainsString('Our Mission', $rendered);
        $this->assertStringContainsString('To provide excellent service.', $rendered);
    }

    public function test_contact_section_template_renders_contact_form()
    {
        $page = \App\Models\Page::create(['title' => 'Home', 'slug' => 'home', 'is_active' => true]);
        $section = PageSection::create([
            'page_id' => $page->id,
            'page_slug' => 'home',
            'section_key' => 'contact',
            'section_type' => 'contact',
            'section_label' => 'Contact Section',
            'sort_order' => 9,
            'is_visible' => true,
        ]);

        \App\Models\SectionContent::create(['section_id' => $section->id, 'key' => 'title', 'type' => 'text', 'value' => 'Get In Touch']);
        \App\Models\SectionContent::create(['section_id' => $section->id, 'key' => 'submit_button_text', 'type' => 'text', 'value' => 'Send Message']);

        $renderer = new \App\View\Components\SectionRenderer($section, [], []);

        $this->assertSame('components.sections.contact', $renderer->render());

        $rendered = view($renderer->render(), ['section' => $section, 'globalSettings' => [], 'stats' => []])->render();

        $this->assertStringContainsString('Get In Touch', $rendered);
        $this->assertStringContainsString('Send Message', $rendered);
        $this->assertStringContainsString('<form', $rendered);
    }

    public function test_catalogue_grid_section_template_renders_catalogue_items()
    {
        $page = \App\Models\Page::create(['title' => 'Home', 'slug' => 'home', 'is_active' => true]);
        $section = PageSection::create([
            'page_id' => $page->id,
            'page_slug' => 'home',
            'section_key' => 'catalogue_grid',
            'section_type' => 'catalogue_grid',
            'section_label' => 'Catalogue Section',
            'sort_order' => 10,
            'is_visible' => true,
        ]);

        \App\Models\SectionContent::create(['section_id' => $section->id, 'key' => 'title', 'type' => 'text', 'value' => 'Our Industries']);
        \App\Models\SectionContent::create(['section_id' => $section->id, 'key' => 'catalogue_type', 'type' => 'select', 'value' => 'industries']);

        $renderer = new \App\View\Components\SectionRenderer($section, [], []);

        $this->assertSame('components.sections.catalogue_grid', $renderer->render());

        $rendered = view($renderer->render(), ['section' => $section, 'globalSettings' => [], 'stats' => []])->render();

        $this->assertStringContainsString('Our Industries', $rendered);
    }
}

