<?php

/**
 * Simplified CMS Section Schema Configuration
 */

return [
    'hero' => [
        'label'  => 'Hero Banner',
        'fields' => [
            'label'            => 'text',
            'heading'          => 'text',
            'subheading'       => 'text',
            'button_text'      => 'text',
            'button_link'      => 'url',
            'background_image' => 'image',
            'breadcrumb'       => 'text',
        ],
    ],
    'mission' => [
        'label'  => 'Mission Statement',
        'fields' => [
            'title'       => 'text',
            'description' => 'html',
        ],
    ],
    'content' => [
        'label'  => 'Content Block',
        'fields' => [
            'label'          => 'text',
            'title'          => 'text',
            'description'    => 'html',
            'image'          => 'image',
            'image_position' => 'select',
        ],
    ],
    'grid' => [
        'label'  => 'Grid Layout',
        'fields' => [
            'label'   => 'text',
            'title'   => 'text',
            'columns' => 'number',
            'items'   => 'repeater', // Refactored to use repeater for better scaling
        ],
    ],
    'features' => [
        'label'  => 'Feature Cards',
        'fields' => [
            'title'       => 'text',
            'description' => 'text',
            'items'       => 'repeater',
        ],
    ],
    'stats' => [
        'label'  => 'Statistics',
        'fields' => [
            'title' => 'text',
            'items' => 'repeater',
        ],
    ],
    'cta' => [
        'label'  => 'Call to Action',
        'fields' => [
            'title'       => 'text',
            'subtitle'    => 'text',
            'button_text' => 'text',
            'button_link' => 'url',
        ],
    ],
    'contact' => [
        'label'  => 'Contact Form',
        'fields' => [
            'title'              => 'text',
            'description'        => 'text',
            'submit_button_text' => 'text',
        ],
    ],
    'catalogue_grid' => [
        'label'  => 'Catalogue Grid',
        'fields' => [
            'title'          => 'text',
            'catalogue_type' => 'select',
            'layout'         => 'select',
        ],
    ],
    'home_about' => [
        'label'  => 'Home About',
        'fields' => [
            'title'       => 'text',
            'subtitle'    => 'html',
            'button_text' => 'text',
            'image'       => 'image',
        ],
    ],
    'services_strip' => [
        'label'  => 'Services Strip',
        'fields' => [
            'title' => 'text',
            'items' => 'repeater',
        ],
    ],
];
