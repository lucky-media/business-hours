<?php

namespace LuckyMedia\BusinessHours\Blueprints;

use Statamic\Facades\Blueprint;
use Statamic\Facades\YAML;

class BusinessHours
{
    public static function augmentedValues()
    {
        return static::blueprint()
            ->fields()
            ->addValues(static::values())
            ->augment()
            ->values();
    }

    public static function values()
    {
        return collect(YAML::file(config('statamic.business_hours.path'))->parse())
            ->merge(YAML::file(config('statamic.business_hours.path'))->parse())
            ->all();
    }

    public static function blueprint()
    {
        return Blueprint::make()->setContents([
            'sections' => [
                'main' => [
                    'display' => __('business-hours::fieldsets/defaults.general'),
                    'fields' => [
                        [
                            'handle' => 'hours',
                            'field' => [
                                'fields' => [
                                    [
                                        'handle' => 'enabled',
                                        'field' => [
                                            'default' => true,
                                            'display' => __('business-hours::fieldsets/defaults.enabled'),
                                            'type' => 'toggle',
                                            'icon' => 'toggle',
                                            'width' => 25,
                                        ],
                                    ],
                                    [
                                        'handle' => 'weekday',
                                        'field' => [
                                            'display' => __('business-hours::fieldsets/defaults.weekday'),
                                            'type' => 'text',
                                            'input_type' => 'text',
                                            'icon' => 'text',
                                            'width' => 25,
                                            'unless' => [
                                                'enabled' => 'equals false'
                                            ],
                                        ],
                                    ],
                                    [
                                        'handle' => 'start_time',
                                        'field' => [
                                            'display' => __('business-hours::fieldsets/defaults.start_time'),
                                            'type' => 'text',
                                            'input_type' => 'text',
                                            'icon' => 'text',
                                            'width' => 25,
                                            'unless_any' => [
                                                'enabled' => 'equals false',
                                                'closed' => 'equals true',
                                                '24_hours' => 'equals true',
                                            ],
                                            'validate' => [
                                                'date_format:H:i',
                                             ],
                                        ],
                                    ],
                                    [
                                        'handle' => 'end_time',
                                        'input_type' => 'text',
                                        'field' => [
                                            'display' => __('business-hours::fieldsets/defaults.end_time'),
                                            'type' => 'text',
                                            'input_type' => 'text',
                                            'icon' => 'text',
                                            'width' => 25,
                                            'unless_any' => [
                                                'enabled' => 'equals false',
                                                'closed' => 'equals true',
                                                '24_hours' => 'equals true',
                                            ],
                                            'validate' => [
                                                'date_format:H:i',
                                            ],
                                        ],
                                    ],
                                    [
                                        'handle' => '24_hours',
                                        'field' => [
                                            'default' => false,
                                            'display' => __('business-hours::fieldsets/defaults.24_hours'),
                                            'type' => 'toggle',
                                            'icon' => 'toggle',
                                            'width' => 25,
                                            'unless_any' => [
                                                'enabled' => 'equals false',
                                                'closed' => 'equals true',
                                            ],
                                        ],
                                    ],
                                    [
                                        'handle' => 'closed',
                                        'field' => [
                                            'default' => false,
                                            'display' => __('business-hours::fieldsets/defaults.closed'),
                                            'type' => 'toggle',
                                            'icon' => 'toggle',
                                            'unless_any' => [
                                                'enabled' => 'equals false',
                                                '24_hours' => 'equals true',
                                            ]
                                        ],
                                    ],
                                ],
                                'mode' => 'table',
                                'min_rows' => 7,
                                'max_rows' => 7,
                                'reorderable' => false,
                                'display' => __('business-hours::fieldsets/defaults.hours'),
                                'type' => 'grid',
                                'icon' => 'grid',
                            ],
                        ],
                    ],
                ],
                'exceptions' => [
                    'display' => __('business-hours::fieldsets/defaults.exceptions'),
                    'fields' => [
                        [
                            'handle' => 'exceptions',
                            'field' => [
                                'fields' => [
                                    [
                                        'handle' => 'enable_date',
                                        'field' => [
                                            'default' => true,
                                            'display' => __('business-hours::fieldsets/defaults.enable_date'),
                                            'instructions' => __('business-hours::fieldsets/defaults.enable_date_instr'),
                                            'type' => 'toggle',
                                            'icon' => 'toggle',
                                            'width' => 100,
                                        ],
                                    ],
                                    [
                                        'handle' => 'start_date',
                                        'field' => [
                                            'mode' => 'single',
                                            'time_enabled' => false,
                                            'time_required' => false,
                                            'full_width' => false,
                                            'inline' => false,
                                            'columns' => 1,
                                            'rows' => 1,
                                            'display' => __('business-hours::fieldsets/defaults.start_date'),
                                            'instructions' => __('business-hours::fieldsets/defaults.start_date_instr'),
                                            'type' => 'date',
                                            'icon' => 'date',
                                            'width' => 50,
                                            'validate' => [
                                                'required',
                                            ],
                                        ],
                                    ],
                                    [
                                        'handle' => 'end_date',
                                        'field' => [
                                            'mode' => 'single',
                                            'time_enabled' => false,
                                            'time_required' => false,
                                            'full_width' => false,
                                            'inline' => false,
                                            'columns' => 1,
                                            'rows' => 1,
                                            'display' => __('business-hours::fieldsets/defaults.end_date'),
                                            'instructions' => __('business-hours::fieldsets/defaults.end_date_instr'),
                                            'type' => 'date',
                                            'icon' => 'date',
                                            'width' => 50,
                                            'validate' => [
                                                'required',
                                            ],
                                        ],
                                    ],
                                    [
                                        'handle' => 'reason',
                                        'field' => [
                                            'always_show_set_button' => false,
                                            'buttons' => [
                                                0 => 'bold',
                                                1 => 'italic',
                                                2 => 'removeformat',
                                            ],
                                            'save_html' => false,
                                            'toolbar_mode' => 'fixed',
                                            'link_noopener' => false,
                                            'link_noreferrer' => false,
                                            'target_blank' => false,
                                            'reading_time' => false,
                                            'fullscreen' => false,
                                            'allow_source' => false,
                                            'enable_input_rules' => false,
                                            'enable_paste_rules' => true,
                                            'antlers' => false,
                                            'display' => __('business-hours::fieldsets/defaults.reason'),
                                            'type' => 'bard',
                                            'icon' => 'bard',
                                        ],
                                    ],
                                ],
                                'mode' => 'stacked',
                                'add_row' => __('business-hours::fieldsets/defaults.add_row'),
                                'reorderable' => true,
                                'display' => __('business-hours::fieldsets/defaults.exceptions'),
                                'type' => 'grid',
                                'icon' => 'grid',
                                'listable' => 'hidden',
                                'instructions_position' => 'above',
                            ],
                        ]
                    ]
                ]
            ],
        ]);
    }
}
