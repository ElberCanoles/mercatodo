<?php

const DAYS_IN_A_WEEK = 7;

const HOURS_IN_A_DAY = 24;

const MINUTES_IN_A_HOUR = 60;

const TIME_IN_MINUTES = DAYS_IN_A_WEEK * HOURS_IN_A_DAY * MINUTES_IN_A_HOUR;

return [
    'cookie' => [
        'name' => env(key: 'CART_COOKIE_NAME', default: 'cart_cookie'),
        'expiration' => TIME_IN_MINUTES
    ],
];
