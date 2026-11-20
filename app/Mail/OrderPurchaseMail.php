<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\URL;

class OrderPurchaseMail extends Mailable
{
    use Queueable, SerializesModels;

    /** @var Collection<int, array{title: string, type: string, url: string}> */
    public Collection $downloads;

    public function __construct(public Order $order)
    {
        $this->order->loadMissing('items.book');

        $this->downloads = $this->order->items
            ->filter(fn ($item) => $item->book && filled($item->book->file_path))
            ->map(fn ($item) => [
                'title' => $item->title,
                'type' => $item->book_type === 'audio' ? 'Livre audio' : 'Ebook',
                'url' => URL::temporarySignedRoute(
                    'checkout.download',
                    now()->addDays(14),
                    [
                        'order' => $this->order->reference,
                        'book' => $item->book_id,
                    ]
                ),
            ])
            ->values();
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Vos livres KaMa — commande ' . $this->order->reference,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.orders.order-purchase',
            with: [
                'order' => $this->order,
                'downloads' => $this->downloads,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
