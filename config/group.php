<?php

return [
    'administrator' => [
        'name' => 'Administrator',
        'resources' => ['Group', 'User', 'Product'],
        'permissions' => ['Read', 'Write'],
    ],
    'user' => [
        'name' => 'User',
        'resources' => ['Group', 'User', 'Product'],
        'permissions' => ['Read'],
    ],
];
