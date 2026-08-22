<?php

return [
    'trial_days' => 30,
    'grace_days' => 7,
    'invoice_lead_days' => 7,

    'contact_email' => env('FOUNDER_EMAIL', 'founder@restoterdekat.id'),
    'contact_whatsapp' => env('FOUNDER_WHATSAPP'),

    'bank_name' => env('FOUNDER_BANK_NAME', 'BCA'),
    'bank_account' => env('FOUNDER_BANK_ACCOUNT', '0000000000'),
    'bank_holder' => env('FOUNDER_BANK_HOLDER', 'RestoTerdekat'),
];
