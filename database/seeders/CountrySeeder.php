<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Country;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      $countries = [

            // AFRIQUE DU NORD
            [
                'name'=>'Algérie',
                'code'=>'DZ',
                'flag'=>'🇩🇿',
                'region'=>'Afrique du Nord'
            ],

            [
                'name'=>'Égypte',
                'code'=>'EG',
                'flag'=>'🇪🇬',
                'region'=>'Afrique du Nord'
            ],

            [
                'name'=>'Libye',
                'code'=>'LY',
                'flag'=>'🇱🇾',
                'region'=>'Afrique du Nord'
            ],

            [
                'name'=>'Maroc',
                'code'=>'MA',
                'flag'=>'🇲🇦',
                'region'=>'Afrique du Nord'
            ],

            [
                'name'=>'Mauritanie',
                'code'=>'MR',
                'flag'=>'🇲🇷',
                'region'=>'Afrique du Nord'
            ],

            [
                'name'=>'Soudan',
                'code'=>'SD',
                'flag'=>'🇸🇩',
                'region'=>'Afrique du Nord'
            ],

            [
                'name'=>'Tunisie',
                'code'=>'TN',
                'flag'=>'🇹🇳',
                'region'=>'Afrique du Nord'
            ],


            // AFRIQUE DE L'OUEST

            [
                'name'=>'Bénin',
                'code'=>'BJ',
                'flag'=>'🇧🇯',
                'region'=>'Afrique de l’Ouest'
            ],

            [
                'name'=>'Burkina Faso',
                'code'=>'BF',
                'flag'=>'🇧🇫',
                'region'=>'Afrique de l’Ouest'
            ],

            [
                'name'=>'Cap-Vert',
                'code'=>'CV',
                'flag'=>'🇨🇻',
                'region'=>'Afrique de l’Ouest'
            ],

            [
                'name'=>'Côte d’Ivoire',
                'code'=>'CI',
                'flag'=>'🇨🇮',
                'region'=>'Afrique de l’Ouest'
            ],

            [
                'name'=>'Gambie',
                'code'=>'GM',
                'flag'=>'🇬🇲',
                'region'=>'Afrique de l’Ouest'
            ],

            [
                'name'=>'Ghana',
                'code'=>'GH',
                'flag'=>'🇬🇭',
                'region'=>'Afrique de l’Ouest'
            ],

            [
                'name'=>'Guinée',
                'code'=>'GN',
                'flag'=>'🇬🇳',
                'region'=>'Afrique de l’Ouest'
            ],

            [
                'name'=>'Guinée-Bissau',
                'code'=>'GW',
                'flag'=>'🇬🇼',
                'region'=>'Afrique de l’Ouest'
            ],

            [
                'name'=>'Libéria',
                'code'=>'LR',
                'flag'=>'🇱🇷',
                'region'=>'Afrique de l’Ouest'
            ],

            [
                'name'=>'Mali',
                'code'=>'ML',
                'flag'=>'🇲🇱',
                'region'=>'Afrique de l’Ouest'
            ],

            [
                'name'=>'Niger',
                'code'=>'NE',
                'flag'=>'🇳🇪',
                'region'=>'Afrique de l’Ouest'
            ],

            [
                'name'=>'Nigeria',
                'code'=>'NG',
                'flag'=>'🇳🇬',
                'region'=>'Afrique de l’Ouest'
            ],

            [
                'name'=>'Sénégal',
                'code'=>'SN',
                'flag'=>'🇸🇳',
                'region'=>'Afrique de l’Ouest'
            ],

            [
                'name'=>'Sierra Leone',
                'code'=>'SL',
                'flag'=>'🇸🇱',
                'region'=>'Afrique de l’Ouest'
            ],

            [
                'name'=>'Togo',
                'code'=>'TG',
                'flag'=>'🇹🇬',
                'region'=>'Afrique de l’Ouest'
            ],


            // AFRIQUE CENTRALE

            [
                'name'=>'Cameroun',
                'code'=>'CM',
                'flag'=>'🇨🇲',
                'region'=>'Afrique centrale'
            ],

            [
                'name'=>'République centrafricaine',
                'code'=>'CF',
                'flag'=>'🇨🇫',
                'region'=>'Afrique centrale'
            ],

            [
                'name'=>'Tchad',
                'code'=>'TD',
                'flag'=>'🇹🇩',
                'region'=>'Afrique centrale'
            ],

            [
                'name'=>'République du Congo',
                'code'=>'CG',
                'flag'=>'🇨🇬',
                'region'=>'Afrique centrale'
            ],

            [
                'name'=>'République démocratique du Congo',
                'code'=>'CD',
                'flag'=>'🇨🇩',
                'region'=>'Afrique centrale'
            ],

            [
                'name'=>'Guinée équatoriale',
                'code'=>'GQ',
                'flag'=>'🇬🇶',
                'region'=>'Afrique centrale'
            ],

            [
                'name'=>'Gabon',
                'code'=>'GA',
                'flag'=>'🇬🇦',
                'region'=>'Afrique centrale'
            ],

            [
                'name'=>'São Tomé-et-Príncipe',
                'code'=>'ST',
                'flag'=>'🇸🇹',
                'region'=>'Afrique centrale'
            ],


            // AFRIQUE DE L'EST

            [
                'name'=>'Burundi',
                'code'=>'BI',
                'flag'=>'🇧🇮',
                'region'=>'Afrique de l’Est'
            ],

            [
                'name'=>'Comores',
                'code'=>'KM',
                'flag'=>'🇰🇲',
                'region'=>'Afrique de l’Est'
            ],

            [
                'name'=>'Djibouti',
                'code'=>'DJ',
                'flag'=>'🇩🇯',
                'region'=>'Afrique de l’Est'
            ],

            [
                'name'=>'Érythrée',
                'code'=>'ER',
                'flag'=>'🇪🇷',
                'region'=>'Afrique de l’Est'
            ],

            [
                'name'=>'Éthiopie',
                'code'=>'ET',
                'flag'=>'🇪🇹',
                'region'=>'Afrique de l’Est'
            ],

            [
                'name'=>'Kenya',
                'code'=>'KE',
                'flag'=>'🇰🇪',
                'region'=>'Afrique de l’Est'
            ],

            [
                'name'=>'Madagascar',
                'code'=>'MG',
                'flag'=>'🇲🇬',
                'region'=>'Afrique de l’Est'
            ],

            [
                'name'=>'Malawi',
                'code'=>'MW',
                'flag'=>'🇲🇼',
                'region'=>'Afrique de l’Est'
            ],

            [
                'name'=>'Maurice',
                'code'=>'MU',
                'flag'=>'🇲🇺',
                'region'=>'Afrique de l’Est'
            ],

            [
                'name'=>'Mozambique',
                'code'=>'MZ',
                'flag'=>'🇲🇿',
                'region'=>'Afrique de l’Est'
            ],

            [
                'name'=>'Rwanda',
                'code'=>'RW',
                'flag'=>'🇷🇼',
                'region'=>'Afrique de l’Est'
            ],

            [
                'name'=>'Seychelles',
                'code'=>'SC',
                'flag'=>'🇸🇨',
                'region'=>'Afrique de l’Est'
            ],

            [
                'name'=>'Somalie',
                'code'=>'SO',
                'flag'=>'🇸🇴',
                'region'=>'Afrique de l’Est'
            ],

            [
                'name'=>'Soudan du Sud',
                'code'=>'SS',
                'flag'=>'🇸🇸',
                'region'=>'Afrique de l’Est'
            ],

            [
                'name'=>'Tanzanie',
                'code'=>'TZ',
                'flag'=>'🇹🇿',
                'region'=>'Afrique de l’Est'
            ],

            [
                'name'=>'Ouganda',
                'code'=>'UG',
                'flag'=>'🇺🇬',
                'region'=>'Afrique de l’Est'
            ],


            // AFRIQUE AUSTRALE

            [
                'name'=>'Afrique du Sud',
                'code'=>'ZA',
                'flag'=>'🇿🇦',
                'region'=>'Afrique australe'
            ],

            [
                'name'=>'Angola',
                'code'=>'AO',
                'flag'=>'🇦🇴',
                'region'=>'Afrique australe'
            ],

            [
                'name'=>'Botswana',
                'code'=>'BW',
                'flag'=>'🇧🇼',
                'region'=>'Afrique australe'
            ],

            [
                'name'=>'Eswatini',
                'code'=>'SZ',
                'flag'=>'🇸🇿',
                'region'=>'Afrique australe'
            ],

            [
                'name'=>'Lesotho',
                'code'=>'LS',
                'flag'=>'🇱🇸',
                'region'=>'Afrique australe'
            ],

            [
                'name'=>'Namibie',
                'code'=>'NA',
                'flag'=>'🇳🇦',
                'region'=>'Afrique australe'
            ],

            [
                'name'=>'Zambie',
                'code'=>'ZM',
                'flag'=>'🇿🇲',
                'region'=>'Afrique australe'
            ],

            [
                'name'=>'Zimbabwe',
                'code'=>'ZW',
                'flag'=>'🇿🇼',
                'region'=>'Afrique australe'
            ],

        ];



        foreach($countries as $country){

            Country::updateOrCreate(
                [
                    'code'=>$country['code']
                ],
                $country
            );

        }

    
    }
}
