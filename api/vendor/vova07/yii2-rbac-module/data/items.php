<?php
return [
    'user' => [
        'type' => 1,
        'description' => 'User',
        'ruleName' => 'group',
    ],
    'moderator' => [
        'type' => 1,
        'description' => 'Moderator',
        'ruleName' => 'group',
        'children' => [
            'user',
        ],
    ],
    'admin' => [
        'type' => 1,
        'ruleName' => 'group',
        'children' => [
            'moderator',
            'user',
        ],
    ],
];
