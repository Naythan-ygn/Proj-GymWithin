<?php

return [
    'bank_name' => env('PAYMENT_BANK_NAME', 'KBZ Bank'),
    'account_name' => env('PAYMENT_ACCOUNT_NAME', 'GymWithin Co., Ltd.'),
    'account_number' => env('PAYMENT_ACCOUNT_NUMBER', '09123456789'),
    'instructions' => env('PAYMENT_INSTRUCTIONS', 'Transfer the exact order total, then upload a clear screenshot of the transaction receipt.'),
];
