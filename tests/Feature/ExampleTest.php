<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_home_page_renders_with_multiple_sections()
    {
        // Create home page
        $page = \App\Models\Page::create([
            'title' => 'Home',
            'slug' => 'home',
            'is_active' => true,
        ]);

        // Create hero section
        $heroSection = \App\Models\PageSection::create([
            'page_id' => $page->id,
            'page_slug' => 'home',
            'section_key' => 'hero',
            'section_label' => 'Hero Banner',
            'sort_order' => 1,
            'is_visible' => true,
        ]);
        \App\Models\SectionContent::create([
            'section_id' => $heroSection->id,
            'key' => 'hero_headline_line1',
            'type' => 'text',
            'value' => 'Welcome to Our Site',
        ]);

        // Create content section
        $contentSection = \App\Models\PageSection::create([
            'page_id' => $page->id,
            'page_slug' => 'home',
            'section_key' => 'content',
            'section_label' => 'About Section',
            'sort_order' => 2,
            'is_visible' => true,
        ]);
        \App\Models\SectionContent::create([
            'section_id' => $contentSection->id,
            'key' => 'title',
            'type' => 'text',
            'value' => 'About Us',
        ]);

        // Create features section
        $featuresSection = \App\Models\PageSection::create([
            'page_id' => $page->id,
            'page_slug' => 'home',
            'section_key' => 'features',
            'section_label' => 'Features',
            'sort_order' => 3,
            'is_visible' => true,
        ]);
        \App\Models\SectionContent::create([
            'section_id' => $featuresSection->id,
            'key' => 'feature_1_title',
            'type' => 'text',
            'value' => 'Quality Service',
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);

        // Check that sections are loaded
        $this->assertDatabaseHas('pages', ['slug' => 'home', 'is_active' => true]);
        $this->assertDatabaseHas('page_sections', ['page_slug' => 'home', 'section_key' => 'hero']);
        $this->assertDatabaseHas('page_sections', ['page_slug' => 'home', 'section_key' => 'content']);
        $this->assertDatabaseHas('page_sections', ['page_slug' => 'home', 'section_key' => 'features']);

        // Check that section content is rendered
        $response->assertSee('Welcome to Our Site')
                 ->assertSee('About Us')
                 ->assertSee('Quality Service');
    }
}
