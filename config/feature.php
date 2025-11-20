<?php

return [
    // Temporarily disable cart and payments
    'payments_enabled' => env('FEATURE_PAYMENTS', false),
    'cart_enabled' => env('FEATURE_CART', false),
];


