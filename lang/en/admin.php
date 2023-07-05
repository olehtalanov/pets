<?php

use App\Enums\AppealStatusEnum;
use App\Enums\UserRoleEnum;

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
        'original_event' => 'Countdown from the event',
        'title' => 'Title',
        'description' => 'Description',
        'starts_at' => 'Starts at',
        'ends_at' => 'Ends at',
        'repeat_scheme' => 'Repeat scheme',
        'whole_day' => 'Whole day',
        'address' => 'Address',
        'contact' => 'Contact',
        'type' => 'Type',
        'custom_type_name' => 'Custom type name',
        'custom_breed_name' => 'Custom breed name',
        'breed_name' => 'Breed name',
        'metis' => 'Metis',
        'sterilised' => 'Sterilised',
        'sex' => 'Sex',
        'weight' => 'Weight',
        'weight_unit' => 'Weight unit',
        'birth_date' => 'Birth date',
        'status' => 'Status',
        'appeal' => 'Appeal',
    ],

    'placeholders' => [
        'any_type' => 'Any type',
        'registrations' => [
            'weekly' => 'This week\'s registrations',
        ],
        'animals' => [
            'weekly' => 'Added animals this week'
        ],
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
        'appeals' => 'Appeals',
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

    'rating_statuses' => [
        1 => 'Very bad',
        2 => 'Bad',
        3 => 'Normal',
        4 => 'Good',
        5 => 'Excellent'
    ],

    'appeals' => [
        'statuses' => [
            AppealStatusEnum::Pending->value => 'Pending',
            AppealStatusEnum::InProgress->value => 'In progress',
            AppealStatusEnum::Processed->value => 'Processed',
        ]
    ],
];
