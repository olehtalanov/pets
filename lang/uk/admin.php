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
    ],

    'nav' => [
        'animal_types' => 'Типи тварин',
        'breeds' => 'Породи',
        'categories' => 'Категорії',
        'users' => 'Користувачі',
        'pins' => 'Шпильки',
        'pin_types' => 'Типи шпільок',
        'reviews' => 'Відгуки',
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
