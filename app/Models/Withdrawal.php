<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Withdrawal extends Model
{
    public const STATUS_INITIATED = 'initiated';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_REJECTED = 'rejected';

    public const METHOD_MOBILE_MONEY = 'mobile_money';
    public const METHOD_BANK = 'bank_transfer';
    public const METHOD_WESTERN_UNION = 'western_union';
    public const METHOD_PAYPAL = 'paypal';

    protected $fillable = [
        'user_id',
        'amount',
        'commission_percent',
        'commission_amount',
        'net_amount',
        'status',
        'payment_method',
        'account_number',
        'payout_details',
        'admin_note',
        'payout_reference',
        'processing_at',
        'completed_at',
        'rejected_at',
        'processed_by',
    ];

    protected $casts = [
        'amount' => 'float',
        'commission_percent' => 'float',
        'commission_amount' => 'float',
        'net_amount' => 'float',
        'payout_details' => 'array',
        'processing_at' => 'datetime',
        'completed_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    /**
     * Canaux proposés pour une nouvelle demande.
     */
    public static function paymentMethods(): array
    {
        return [
            self::METHOD_BANK => [
                'label' => 'Virement bancaire',
                'icon' => 'bi-bank',
                'hint' => 'Compte bancaire national ou international',
                'fields' => [
                    'bank_name' => ['label' => 'Nom de la banque', 'type' => 'text', 'placeholder' => 'Ex. Ecobank'],
                    'account_name' => ['label' => 'Titulaire du compte', 'type' => 'text', 'placeholder' => 'Nom complet'],
                    'iban_or_account' => ['label' => 'IBAN / N° de compte', 'type' => 'text', 'placeholder' => 'IBAN ou numéro'],
                    'swift' => ['label' => 'Code SWIFT / BIC (optionnel)', 'type' => 'text', 'placeholder' => 'Optionnel', 'required' => false],
                    'country' => ['label' => 'Pays de la banque', 'type' => 'text', 'placeholder' => 'Ex. Bénin'],
                ],
            ],
            self::METHOD_WESTERN_UNION => [
                'label' => 'Western Union',
                'icon' => 'bi-globe2',
                'hint' => 'Nom, adresse, date de naissance et téléphone du bénéficiaire',
                'fields' => [
                    'full_name' => ['label' => 'Nom', 'type' => 'text', 'placeholder' => 'Nom complet du bénéficiaire'],
                    'address' => ['label' => 'Adresse', 'type' => 'text', 'placeholder' => 'Rue, code postal, ville, pays'],
                    'birth_date' => ['label' => 'Date de naissance', 'type' => 'date'],
                    'phone' => ['label' => 'Téléphone', 'type' => 'tel', 'placeholder' => '+33 …'],
                ],
            ],
            self::METHOD_PAYPAL => [
                'label' => 'PayPal',
                'icon' => 'bi-paypal',
                'hint' => 'Versement sur une adresse e-mail PayPal',
                'fields' => [
                    'email' => ['label' => 'E-mail PayPal', 'type' => 'email', 'placeholder' => 'vous@email.com'],
                    'account_name' => ['label' => 'Nom du compte', 'type' => 'text', 'placeholder' => 'Nom affiché'],
                ],
            ],
        ];
    }

    /**
     * Anciens canaux, conservés pour l’affichage des demandes déjà créées.
     *
     * @return array<string, array<string, mixed>>
     */
    public static function methodCatalog(): array
    {
        return self::paymentMethods() + [
            self::METHOD_MOBILE_MONEY => [
                'label' => 'Mobile Money',
                'icon' => 'bi-phone',
                'hint' => 'Ancien canal',
                'fields' => [
                    'operator' => ['label' => 'Opérateur', 'type' => 'text'],
                    'phone' => ['label' => 'Numéro Mobile Money', 'type' => 'tel'],
                    'account_name' => ['label' => 'Nom du titulaire', 'type' => 'text'],
                ],
            ],
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_INITIATED => 'Initié',
            self::STATUS_PROCESSING => 'En cours',
            self::STATUS_COMPLETED => 'Terminé',
            self::STATUS_REJECTED => 'Refusé',
            default => ucfirst((string) $this->status),
        };
    }

    public function methodLabel(): string
    {
        return self::methodCatalog()[$this->payment_method]['label'] ?? (string) $this->payment_method;
    }

    public function isOpen(): bool
    {
        return in_array($this->status, [self::STATUS_INITIATED, self::STATUS_PROCESSING], true);
    }
}
