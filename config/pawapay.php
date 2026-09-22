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
    | Taux par défaut : 1 EUR = X unités de devise locale
    |--------------------------------------------------------------------------
    |
    | Surchargeables en admin (Setting pawapay_fx_rates) sans redéployer.
    | XOF/XAF = parité officielle BCEAO/BEAC.
    | Les autres devises sont indicatives — à ajuster régulièrement.
    |
    */

    'rates' => [
        'XOF' => 655.957,
        'XAF' => 655.957,
        'GHS' => 16.50,
        'NGN' => 1600.0,
        'KES' => 140.0,
        'UGX' => 4000.0,
        'TZS' => 2800.0,
        'RWF' => 1400.0,
        'ZMW' => 27.0,
        'MWK' => 1800.0,
        'MZN' => 70.0,
        'CDF' => 2900.0,
        'ETB' => 130.0,
        'LSL' => 20.0,
        'SLE' => 25.0,
    ],

];
