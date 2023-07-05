<?php

use App\Enums\AppealStatusEnum;
use App\Enums\UserRoleEnum;

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
        'original_event' => 'Відлік від події',
        'title' => 'Заголовок',
        'description' => 'Опис',
        'starts_at' => 'Починається о',
        'ends_at' => 'Закінчується о',
        'repeat_scheme' => 'Схема повторів',
        'whole_day' => 'Цілий день',
        'address' => 'Адреса',
        'contact' => 'Контактна інформація',
        'type' => 'Тип',
        'custom_type_name' => 'Користувацький тип',
        'custom_breed_name' => 'Користувацька порода',
        'breed_name' => 'Назва породи',
        'metis' => 'Метис',
        'sterilised' => 'Стерилізована',
        'sex' => 'Стать',
        'weight' => 'Вага',
        'weight_unit' => 'Одиниця ваги',
        'birth_date' => 'Дата народження',
        'status' => 'Статус',
        'appeal' => 'Звернення',
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
        'notes' => 'Примітки',
        'appeals' => 'Звернення',
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

    'rating_statuses' => [
        1 => 'Дуже погано',
        2 => 'Погано',
        3 => 'Нормально',
        4 => 'Добре',
        5 => 'Відмінно'
    ],

    'appeals' => [
        'statuses' => [
            AppealStatusEnum::Pending->value => 'В очікуванні',
            AppealStatusEnum::InProgress->value => 'В процесі',
            AppealStatusEnum::Processed->value => 'Оброблено',
        ]
    ],
];
