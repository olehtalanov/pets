<?php

use App\Enums\User\UserRoleEnum;

return [
    'fields' => [
        'name' => 'Name',
        'animal_type' => 'Animal type',
        'is_visible' => 'Is visible',
        'event' => 'Event',
        'note' => 'Note',
        'parent' => 'Parent',
        'model' => 'Model',
        'breed' => 'Breed',
        'category' => 'Category',
        'pin' => 'Pin',
        'pin_type' => 'Pin type',
        'user' => 'User',
        'coordinates' => 'Coordinates',
        'message' => 'Message',
        'reviewable' => 'Reviewable',
        'reviewer' => 'Reviewer',
        'rating' => 'Rating',
    ],

    'nav' => [
        'animal_types' => 'Animal types',
        'breeds' => 'Breeds',
        'categories' => 'Categories',
        'users' => 'Users',
        'pins' => 'Pins',
        'pin_types' => 'Pin types',
        'reviews' => 'Reviews',
    ],

    'nav_groups' => [
        'settings' => 'Settings',
        'users' => 'User\'s',
    ],

    'tips' => [
        'category' => 'Available for any model',
        'parent' => 'Use as parent',
    ],

    'roles' => [
        UserRoleEnum::Admin->value => 'Administrator',
        UserRoleEnum::Regular->value => 'Regular',
    ],
];
