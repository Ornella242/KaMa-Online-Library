<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Faq;
use App\Models\FaqCategory;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $lecteurs = FaqCategory::query()->where('slug','lecteurs')->first();
        $ecrivains = FaqCategory::query()->where('slug','ecrivains')->first();


        Faq::insert([


            [
                'faq_category_id'=>$lecteurs->id,
                'question'=>'Comment acheter un livre sur KaMa ?',
                'answer'=>'Choisissez un livre dans le catalogue, ajoutez-le à votre panier puis procédez au paiement.',
                'order'=>1
            ],

            [
                'faq_category_id'=>$lecteurs->id,
                'question'=>'Quels sont les moyens de paiement sur KaMa ?',
                'answer'=>'Uniquement les paiements par Visa card ou Mastercard sont autorisés sur KaMa',
                'order'=>2
            ],



            [
                'faq_category_id'=>$lecteurs->id,
                'question'=>'Puis-je lire un aperçu avant achat ?',
                'answer'=>'Oui, tous les livres proposent un extrait afin de découvrir l’œuvre avant de l’acheter.',
                'order'=>3
            ],


            [
                'faq_category_id'=>$lecteurs->id,
                'question'=>'Où retrouver mes livres achetés ?',
                'answer'=>'Vos livres sont envoyés par mail après achat et sont aussi accessibles depuis votre espace personnel.',
                'order'=>4
            ],


            [
                'faq_category_id'=>$ecrivains->id,
                'question'=>'Comment publier un livre sur KaMa ?',
                'answer'=>'Créez votre compte auteur, ajoutez votre livre, payez les frais de dépôt et soumettez-le pour validation.',
                'order'=>1
            ],

            [
                'faq_category_id'=>$ecrivains->id,
                'question'=>'Puis-je modifier mon livre après paiement des frais?',
                'answer'=>'Oui , mais en partie. Le titre, le type (ebook/audio) et le fichier du livre ne sont pas modifiables après paiement parce qu\'elles sont utilisées pour la vérification éditoriale',
                'order'=>2
            ],

            [
                'faq_category_id'=>$ecrivains->id,
                'question'=>'Comment savoir si mon livre est publié après soumission pour validation?',
                'answer'=>'Vous recevrez une notification dans votre espace personnel ainsi qu\'un mail sur toutes les mises mise à jour concernant votre livre',
                'order'=>3
            ],


            [
                'faq_category_id'=>$ecrivains->id,
                'question'=>'Comment suivre mes ventes ?',
                'answer'=>'Votre tableau de bord auteur vous permet de suivre vos ventes et revenus.',
                'order'=>4
            ]


        ]);


    }


    
}
