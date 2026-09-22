<?php

return [

    /*
    |--------------------------------------------------------------------------
    | PawaPay markets (ISO2 KaMa → ISO3 PawaPay + devise locale)
    |--------------------------------------------------------------------------
    |
    | decimals: false = montant entier (ex. XOF), 2 = deux décimales.
    |
    */

    'markets' => [
        'BJ' => ['iso3' => 'BEN', 'currency' => 'XOF', 'decimals' => false, 'name' => 'Bénin'],
        'BF' => ['iso3' => 'BFA', 'currency' => 'XOF', 'decimals' => false, 'name' => 'Burkina Faso'],
        'CI' => ['iso3' => 'CIV', 'currency' => 'XOF', 'decimals' => false, 'name' => 'Côte d’Ivoire'],
        'SN' => ['iso3' => 'SEN', 'currency' => 'XOF', 'decimals' => false, 'name' => 'Sénégal'],
        'CM' => ['iso3' => 'CMR', 'currency' => 'XAF', 'decimals' => false, 'name' => 'Cameroun'],
        'CG' => ['iso3' => 'COG', 'currency' => 'XAF', 'decimals' => false, 'name' => 'République du Congo'],
        'GA' => ['iso3' => 'GAB', 'currency' => 'XAF', 'decimals' => 2, 'name' => 'Gabon'],
        'CD' => ['iso3' => 'COD', 'currency' => 'CDF', 'decimals' => 2, 'name' => 'RDC'],
        'ET' => ['iso3' => 'ETH', 'currency' => 'ETB', 'decimals' => 2, 'name' => 'Éthiopie'],
        'GH' => ['iso3' => 'GHA', 'currency' => 'GHS', 'decimals' => 2, 'name' => 'Ghana'],
        'KE' => ['iso3' => 'KEN', 'currency' => 'KES', 'decimals' => false, 'name' => 'Kenya'],
        'LS' => ['iso3' => 'LSO', 'currency' => 'LSL', 'decimals' => 2, 'name' => 'Lesotho'],
        'MW' => ['iso3' => 'MWI', 'currency' => 'MWK', 'decimals' => 2, 'name' => 'Malawi'],
        'MZ' => ['iso3' => 'MOZ', 'currency' => 'MZN', 'decimals' => 2, 'name' => 'Mozambique'],
        'NG' => ['iso3' => 'NGA', 'currency' => 'NGN', 'decimals' => 2, 'name' => 'Nigeria'],
        'RW' => ['iso3' => 'RWA', 'currency' => 'RWF', 'decimals' => false, 'name' => 'Rwanda'],
        'SL' => ['iso3' => 'SLE', 'currency' => 'SLE', 'decimals' => 2, 'name' => 'Sierra Leone'],
        'TZ' => ['iso3' => 'TZA', 'currency' => 'TZS', 'decimals' => 2, 'name' => 'Tanzanie'],
        'UG' => ['iso3' => 'UGA', 'currency' => 'UGX', 'decimals' => 2, 'name' => 'Ouganda'],
        'ZM' => ['iso3' => 'ZMB', 'currency' => 'ZMW', 'decimals' => 2, 'name' => 'Zambie'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Taux de change
    |--------------------------------------------------------------------------
    |
    | Les taux 1 EUR → devise locale viennent de CurrencyFreaks
    | (CURRENCYFREAKS_API_KEY). Plus de référentiel manuel.
    |
    */

];
