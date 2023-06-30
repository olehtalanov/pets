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
        'verified' => 'Verified',
        'radius' => 'Radius',
        'latitude' => 'Latitude',
        'longitude' => 'Longitude',
        'first_name' => 'First name',
        'last_name' => 'Last name',
        'phone' => 'Phone number',
        'provider' => 'Provider',
        'provider_id' => 'Provider ID',
        'email_verified_at' => 'Email verified at',
        'role' => 'Role',
        'created_at' => 'Creation date',
        'created_from' => 'Created from',
        'created_until' => 'Created until',
        'animal' => 'Animal',
        'owner' => 'Owner',
    ],

    'placeholders' => [
        'any_type' => 'Any type'
    ],

    'nav' => [
        'animal_types' => 'Animal types',
        'breeds' => 'Breeds',
        'categories' => 'Categories',
        'users' => 'Users',
        'pins' => 'Pins',
        'pin_types' => 'Pin types',
        'reviews' => 'Reviews',
        'animals' => 'Animals',
        'events' => 'Events',
        'notes' => 'Notes',
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
