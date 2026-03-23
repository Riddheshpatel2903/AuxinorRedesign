<?php

/**
 * Design Tokens Configuration
 * 
 * PHASE 6: Style System
 * 
 * Defines the design system tokens (typography, colors, spacing, effects)
 * that constrain allowed CSS values throughout the CMS.
 * 
 * This ensures visual consistency and prevents arbitrary styling.
 */

return [
    'colors' => [
        'primary' => [
            'label' => 'Primary',
            'value' => '#2563eb',
            'hex' => '#2563eb',
            'rgb' => 'rgb(37, 99, 235)',
        ],
        'primary-dark' => [
            'label' => 'Primary Dark',
            'value' => '#1e40af',
            'hex' => '#1e40af',
            'rgb' => 'rgb(30, 64, 175)',
        ],
        'primary-light' => [
            'label' => 'Primary Light',
            'value' => '#3b82f6',
            'hex' => '#3b82f6',
            'rgb' => 'rgb(59, 130, 246)',
        ],
        'secondary' => [
            'label' => 'Secondary',
            'value' => '#06b6d4',
            'hex' => '#06b6d4',
            'rgb' => 'rgb(6, 182, 212)',
        ],
        'accent' => [
            'label' => 'Accent',
            'value' => '#f59e0b',
            'hex' => '#f59e0b',
            'rgb' => 'rgb(245, 158, 11)',
        ],
        'success' => [
            'label' => 'Success',
            'value' => '#10b981',
            'hex' => '#10b981',
            'rgb' => 'rgb(16, 185, 129)',
        ],
        'warning' => [
            'label' => 'Warning',
            'value' => '#f59e0b',
            'hex' => '#f59e0b',
            'rgb' => 'rgb(245, 158, 11)',
        ],
        'danger' => [
            'label' => 'Danger',
            'value' => '#ef4444',
            'hex' => '#ef4444',
            'rgb' => 'rgb(239, 68, 68)',
        ],
        'dark' => [
            'label' => 'Dark',
            'value' => '#1f2937',
            'hex' => '#1f2937',
            'rgb' => 'rgb(31, 41, 55)',
        ],
        'light' => [
            'label' => 'Light',
            'value' => '#f3f4f6',
            'hex' => '#f3f4f6',
            'rgb' => 'rgb(243, 244, 246)',
        ],
        'white' => [
            'label' => 'White',
            'value' => '#ffffff',
            'hex' => '#ffffff',
            'rgb' => 'rgb(255, 255, 255)',
        ],
        'gray-50' => [
            'label' => 'Gray 50',
            'value' => '#f9fafb',
            'hex' => '#f9fafb',
            'rgb' => 'rgb(249, 250, 251)',
        ],
        'gray-100' => [
            'label' => 'Gray 100',
            'value' => '#f3f4f6',
            'hex' => '#f3f4f6',
            'rgb' => 'rgb(243, 244, 246)',
        ],
        'gray-200' => [
            'label' => 'Gray 200',
            'value' => '#e5e7eb',
            'hex' => '#e5e7eb',
            'rgb' => 'rgb(229, 231, 235)',
        ],
        'gray-300' => [
            'label' => 'Gray 300',
            'value' => '#d1d5db',
            'hex' => '#d1d5db',
            'rgb' => 'rgb(209, 213, 219)',
        ],
        'gray-400' => [
            'label' => 'Gray 400',
            'value' => '#9ca3af',
            'hex' => '#9ca3af',
            'rgb' => 'rgb(156, 163, 175)',
        ],
        'gray-500' => [
            'label' => 'Gray 500',
            'value' => '#6b7280',
            'hex' => '#6b7280',
            'rgb' => 'rgb(107, 114, 128)',
        ],
        'gray-600' => [
            'label' => 'Gray 600',
            'value' => '#4b5563',
            'hex' => '#4b5563',
            'rgb' => 'rgb(75, 85, 99)',
        ],
        'gray-700' => [
            'label' => 'Gray 700',
            'value' => '#374151',
            'hex' => '#374151',
            'rgb' => 'rgb(55, 65, 81)',
        ],
        'gray-800' => [
            'label' => 'Gray 800',
            'value' => '#1f2937',
            'hex' => '#1f2937',
            'rgb' => 'rgb(31, 41, 55)',
        ],
        'gray-900' => [
            'label' => 'Gray 900',
            'value' => '#111827',
            'hex' => '#111827',
            'rgb' => 'rgb(17, 24, 39)',
        ],
    ],

    'typography' => [
        'h1' => [
            'label' => 'Heading 1',
            'fontSize' => '48px',
            'fontWeight' => '700',
            'lineHeight' => '1.2',
            'letterSpacing' => '-0.02em',
        ],
        'h2' => [
            'label' => 'Heading 2',
            'fontSize' => '36px',
            'fontWeight' => '700',
            'lineHeight' => '1.3',
            'letterSpacing' => '-0.01em',
        ],
        'h3' => [
            'label' => 'Heading 3',
            'fontSize' => '28px',
            'fontWeight' => '600',
            'lineHeight' => '1.4',
            'letterSpacing' => '0',
        ],
        'h4' => [
            'label' => 'Heading 4',
            'fontSize' => '24px',
            'fontWeight' => '600',
            'lineHeight' => '1.4',
            'letterSpacing' => '0',
        ],
        'h5' => [
            'label' => 'Heading 5',
            'fontSize' => '20px',
            'fontWeight' => '600',
            'lineHeight' => '1.5',
            'letterSpacing' => '0',
        ],
        'h6' => [
            'label' => 'Heading 6',
            'fontSize' => '16px',
            'fontWeight' => '600',
            'lineHeight' => '1.5',
            'letterSpacing' => '0.01em',
        ],
        'body-lg' => [
            'label' => 'Body Large',
            'fontSize' => '18px',
            'fontWeight' => '400',
            'lineHeight' => '1.6',
            'letterSpacing' => '0',
        ],
        'body' => [
            'label' => 'Body',
            'fontSize' => '16px',
            'fontWeight' => '400',
            'lineHeight' => '1.6',
            'letterSpacing' => '0',
        ],
        'body-sm' => [
            'label' => 'Body Small',
            'fontSize' => '14px',
            'fontWeight' => '400',
            'lineHeight' => '1.5',
            'letterSpacing' => '0.01em',
        ],
        'caption' => [
            'label' => 'Caption',
            'fontSize' => '12px',
            'fontWeight' => '400',
            'lineHeight' => '1.4',
            'letterSpacing' => '0.02em',
        ],
        'button' => [
            'label' => 'Button',
            'fontSize' => '14px',
            'fontWeight' => '600',
            'lineHeight' => '1.5',
            'letterSpacing' => '0.01em',
        ],
    ],

    'spacing' => [
        '0' => '0',
        '1' => '4px',
        '2' => '8px',
        '3' => '12px',
        '4' => '16px',
        '5' => '20px',
        '6' => '24px',
        '8' => '32px',
        '10' => '40px',
        '12' => '48px',
        '16' => '64px',
        '20' => '80px',
        '24' => '96px',
        '32' => '128px',
    ],

    'borderRadius' => [
        'none' => '0',
        'sm' => '4px',
        'md' => '8px',
        'lg' => '12px',
        'xl' => '16px',
        'full' => '9999px',
    ],

    'shadows' => [
        'none' => 'none',
        'sm' => '0 1px 2px 0 rgba(0, 0, 0, 0.05)',
        'md' => '0 4px 6px -1px rgba(0, 0, 0, 0.1)',
        'lg' => '0 10px 15px -3px rgba(0, 0, 0, 0.1)',
        'xl' => '0 20px 25px -5px rgba(0, 0, 0, 0.1)',
        '2xl' => '0 25px 50px -12px rgba(0, 0, 0, 0.25)',
    ],

    'transitions' => [
        'fast' => '150ms ease-in-out',
        'normal' => '300ms ease-in-out',
        'slow' => '500ms ease-in-out',
    ],

    'zIndex' => [
        'auto' => 'auto',
        '0' => '0',
        '10' => '10',
        '20' => '20',
        '30' => '30',
        '40' => '40',
        '50' => '50',
        '100' => '100',
        '1000' => '1000',
        '9999' => '9999',
    ],
];
