<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use App\Models\BookSponsorship;
use Illuminate\Console\Command;

#[Signature('app:expire-book-sponsorships')]
#[Description('Command description')]
class ExpireBookSponsorships extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        BookSponsorship::query()->where('status','paid')
        ->where('ends_at','<',now())
        ->update([
            'status'=>'expired'
        ]);


        $this->info('Sponsoring expirés mis à jour.');
    }
}
