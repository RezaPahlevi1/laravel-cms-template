<?php

return [
    'cache' => [
        'nav_ttl' => 3600,
        'settings_ttl' => 3600,
    ],

    'pagination' => [
        'related_posts_limit' => 4,
    ],

    'search' => [
        'min_query_length' => 2,
    ],

    'seo' => [
        'meta_title_max_length' => 255,
        'meta_description_max_length' => 160,
    ],

    'uploads' => [
        'image_max_size_kb' => 2048,
        'allowed_image_types' => ['jpg', 'jpeg', 'png', 'webp'],
    ],
];