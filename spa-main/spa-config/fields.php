<?php
/**
 * SPA – Gravity Forms Field Mapping (PHP)
 * Tento súbor mapuje logické názvy polí na konkrétne GF input_ID.
 * Upravuje sa PO IMPORTE GF formulára na novom webe.
 */

return [

    /* ==========================
       VÝBER PROGRAMU
       ========================== */
    'program' => 'input_12',
    'variant' => 'input_13',

    /* ==========================
       ÚČASTNÍK – KONTAKT
       ========================== */
    'client_email' => 'input_22',
    'client_phone' => 'input_23',

    /* ==========================
       ÚČASTNÍK – ADRESA
       (GF Address – array)
       ========================== */
    'client_address' => 'input_30',

    /* ==========================
       SÚHLASY
       ========================== */
    'consent_gdpr'      => 'input_40',
    'consent_health'    => 'input_41',
    'consent_statutes'  => 'input_42',
    'consent_terms'     => 'input_43',
    'consent_guardian'  => 'input_44',
    'consent_marketing' => 'input_45',

];
