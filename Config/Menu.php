<?php

namespace Config;

class Menu extends Config
{
    public $config = [
        [
            'name' => '教务',
            'sub_button' => [
                [
                    'type' => 'click',
                    'name' => '今日课表',
                    'key' => 'Course_today'
                ],
                [
                    'type' => 'click',
                    'name' => '本周课表',
                    'key' => 'Course'
                ],
            ],
        ],
        [
            'name' => '我',
            'sub_button' => [
                [
                    'type' => 'click',
                    'name' => '设置',
                    'key' => 'Setting'
                ],
            ],
        ],
    ];
}