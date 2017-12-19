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
                [
                    'type' => 'click',
                    'name' => '学期成绩',
                    'key' => 'Score_currentsemester'
                ],
                [
                    'type' => 'click',
                    'name' => '历年成绩',
                    'key' => 'Score'
                ],
                [
                    'type' => 'click',
                    'name' => '考试安排',
                    'key' => 'Exam'
                ],
            ],
        ],
        [
            'name' => '学习|生活',
            'sub_button' => [
                [
                    'type' => 'click',
                    'name' => '饭卡消费',
                    'key' => 'Ecard'
                ],
                [
                    'type' => 'click',
                    'name' => '本月消费',
                    'key' => 'Ecardthismonth'
                ],
                [
                    'type' => 'click',
                    'name' => '图书借阅',
                    'key' => 'Borrow'
                ],
                [
                    'type' => 'click',
                    'name' => '寝室网络',
                    'key' => 'Net'
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