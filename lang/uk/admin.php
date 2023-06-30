<?php

use App\Enums\User\UserRoleEnum;

return [
    'fields' => [
        'name' => 'Імʼя',
        'animal_type' => 'Тип тварини',
        'is_visible' => 'Видно',
        'event' => 'Подія',
        'note' => 'Примітка',
        'parent' => 'Родитель',
        'model' => 'Модель',
        'breed' => 'Порода',
        'category' => 'Категорія',
        'pin' => 'Шпилька',
        'pin_type' => 'Тип шпильки',
        'user' => 'Користувач',
        'coordinates' => 'Координати',
        'message' => 'Повідомлення',
        'reviewable' => 'Підлягає перегляду',
        'reviewer' => 'Рецензент',
        'rating' => 'Рейтинг',
        'verified' => 'Перевірений',
        'radius' => 'Радіус',
        'latitude' => 'Широта',
        'longitude' => 'Довгота',
        'first_name' => 'Імʼя',
        'last_name' => 'Прізвище',
        'phone' => 'Телефон',
        'provider' => 'Провайдер',
        'provider_id' => 'Провайдер ID',
        'email_verified_at' => 'Email підтверджено о',
        'role' => 'Роль',
        'created_at' => 'Дата створення',
        'created_from' => 'Створено з',
        'created_until' => 'Створено до',
        'animal' => 'Тварина',
        'owner' => 'Власник',
    ],

    'placeholders' => [
        'any_type' => 'Будь-який тип',
        'registrations' => [
            'weekly' => 'Цьоготижневі реєстрації',
        ],
        'animals' => [
            'weekly' => 'Цього тижня додано тварин'
        ],
    ],

    'nav' => [
        'animal_types' => 'Типи тварин',
        'breeds' => 'Породи',
        'categories' => 'Категорії',
        'users' => 'Користувачі',
        'pins' => 'Шпильки',
        'pin_types' => 'Типи шпільок',
        'reviews' => 'Відгуки',
        'animals' => 'Тварини',
        'events' => 'Події',
        'notes' => 'Примітки'
    ],

    'nav_groups' => [
        'settings' => 'Налаштування',
        'users' => 'Користувацьке',
    ],

    'tips' => [
        'category' => 'Доступний для будь-якої моделі',
        'parent' => 'Використовувати як батьків',
    ],

    'roles' => [
        UserRoleEnum::Admin->value => 'Адміністратор',
        UserRoleEnum::Regular->value => 'Користувач',
    ],
];
