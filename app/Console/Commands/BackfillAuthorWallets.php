<?php

namespace App\Console\Commands;

use App\Services\WalletService;
use Illuminate\Console\Command;

class BackfillAuthorWallets extends Command
{
    protected $signature = 'wallets:backfill-sales {--author= : Limiter à un auteur (user id)}';

    protected $description = 'Crédite les portefeuilles auteurs pour les ventes déjà confirmées';

    public function handle(WalletService $walletService): int
    {
        $authorId = $this->option('author') ? (int) $this->option('author') : null;

        $this->info('Synchronisation des ventes vers les portefeuilles…');

        $credited = $walletService->backfillSales($authorId);

        $this->info("{$credited} transaction(s) créditée(s).");

        return self::SUCCESS;
    }
}
