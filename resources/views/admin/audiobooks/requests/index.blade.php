@extends('layouts.admin')

@section('title', 'Demandes audiobooks')
@section('page-title', 'Demandes audiobooks')

@section('admin-content')

<div class="audiobook-requests-page">
    {{-- Statistiques --}}
    <div class="audiobook-requests-stats">

        <article>
            <span class="pending">
                <i class="bi bi-hourglass-split"></i>
            </span>

            <div>
                <small>En attente de paiement</small>
                <strong>
                    {{ number_format(
                        $requests->where('status', 'pending_payment')->count()
                    ) }}
                </strong>
            </div>
        </article>


        <article>
            <span class="paid">
                <i class="bi bi-credit-card"></i>
            </span>

            <div>
                <small>À traiter</small>
                <strong>
                    {{ number_format(
                        $requests->where('status', 'paid')->count()
                    ) }}
                </strong>
            </div>
        </article>


        <article>
            <span class="generating">
                <i class="bi bi-soundwave"></i>
            </span>

            <div>
                <small>En production</small>
                <strong>
                    {{ number_format(
                        $requests->whereIn('status', [
                            'queued',
                            'generating',
                            'assembling'
                        ])->count()
                    ) }}
                </strong>
            </div>
        </article>


        <article>
            <span class="completed">
                <i class="bi bi-check2-circle"></i>
            </span>

            <div>
                <small>Terminés</small>
                <strong>
                    {{ number_format(
                        $requests->where('status', 'completed')->count()
                    ) }}
                </strong>
            </div>
        </article>

    </div>


    {{-- Tableau --}}
    <div class="audiobook-requests-panel">

        <header>

            <div>

                <strong>
                    {{ number_format($requests->total()) }} demande(s)
                </strong>

                <span>
                    Les demandes payées peuvent être lancées en production.
                </span>

            </div>

        </header>


        @if($requests->isEmpty())

            <div class="audiobook-requests-empty">

                <span>
                    <i class="bi bi-headphones"></i>
                </span>

                <h3>
                    Aucune demande d’audiobook
                </h3>

                <p>
                    Les demandes d’audiobooks apparaîtront ici après leur création.
                </p>

            </div>

        @else

            <div class="table-responsive">

                <table class="table audiobook-requests-table align-middle">

                    <thead>

                        <tr>

                            <th>
                                Livre
                            </th>

                            <th>
                                Auteur
                            </th>

                            <th>
                                Voix
                            </th>

                            <th>
                                Montant
                            </th>

                            <th>
                                Statut
                            </th>

                            <th>
                                Demandé le
                            </th>

                            <th class="text-end">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach($requests as $request)

                            @php

                                $authorName = trim(
                                    ($request->author?->firstname ?? '') .
                                    ' ' .
                                    ($request->author?->lastname ?? '')
                                ) ?: 'Auteur inconnu';

                                $statusLabel = match ($request->status) {

                                    'pending_payment' => 'En attente de paiement',

                                    'paid' => 'À traiter',

                                    'queued' => 'En file d’attente',

                                    'generating' => 'Génération en cours',

                                    'assembling' => 'Assemblage',

                                    'completed' => 'Terminé',

                                    'failed' => 'Échec',

                                    'cancelled' => 'Annulée',

                                    default => ucfirst(
                                        str_replace('_', ' ', $request->status)
                                    ),

                                };

                                $statusClass = match ($request->status) {

                                    'pending_payment' => 'pending',

                                    'paid' => 'paid',

                                    'queued',
                                    'generating',
                                    'assembling' => 'generating',

                                    'completed' => 'completed',

                                    'failed' => 'failed',

                                    'cancelled' => 'cancelled',

                                    default => 'default',

                                };

                            @endphp


                            <tr>

                                {{-- Livre --}}
                                <td>

                                    <div class="audiobook-book-cell">

                                        <img
                                            src="{{ $request->book?->cover_image
                                                ? asset('storage/' . $request->book->cover_image)
                                                : asset('assets/images/book/01.jpg') }}"
                                            alt=""
                                        >

                                        <div>

                                            <a
                                                href="{{ route(
                                                    'admin.audiobooks.requests.show',
                                                    $request
                                                ) }}"
                                                class="audiobook-book-title"
                                            >
                                                {{ $request->book?->title ?? 'Livre supprimé' }}
                                            </a>

                                            <small>
                                                Demande #{{ str_pad(
                                                    (string) $request->id,
                                                    4,
                                                    '0',
                                                    STR_PAD_LEFT
                                                ) }}
                                            </small>

                                        </div>

                                    </div>

                                </td>


                                {{-- Auteur --}}
                                <td>

                                    <div class="audiobook-author-cell">

                                        <strong>
                                            {{ $authorName }}
                                        </strong>

                                        <small>
                                            {{ $request->author?->email ?? '—' }}
                                        </small>

                                    </div>

                                </td>


                                {{-- Voix --}}
                               
                                <td>
                                    <div class="audiobook-voice">
                                        <i class="bi bi-mic"></i>
                                        {{ $voiceNames[$request->voice_id] ?? 'Voix inconnue' }}
                                    </div>
                                </td>


                                {{-- Montant --}}
                                <td>

                                    <div class="audiobook-amount">

                                        <strong>
                                            {{ number_format(
                                                (float) $request->total_amount,
                                                2,
                                                ',',
                                                ' '
                                            ) }}
                                            €
                                        </strong>

                                        <small>
                                            Paiement
                                        </small>

                                    </div>

                                </td>


                                {{-- Statut --}}
                                <td>

                                    <span class="audiobook-status {{ $statusClass }}">

                                        @switch($request->status)

                                            @case('pending_payment')
                                                <i class="bi bi-hourglass-split"></i>
                                                @break

                                            @case('paid')
                                                <i class="bi bi-credit-card"></i>
                                                @break

                                            @case('queued')
                                                <i class="bi bi-clock"></i>
                                                @break

                                            @case('generating')
                                                <i class="bi bi-soundwave"></i>
                                                @break

                                            @case('assembling')
                                                <i class="bi bi-gear"></i>
                                                @break

                                            @case('completed')
                                                <i class="bi bi-check2-circle"></i>
                                                @break

                                            @case('failed')
                                                <i class="bi bi-exclamation-circle"></i>
                                                @break

                                            @default
                                                <i class="bi bi-circle"></i>

                                        @endswitch

                                        {{ $statusLabel }}

                                    </span>

                                </td>


                                {{-- Date --}}
                                <td>

                                    <div class="audiobook-date">

                                        <strong>
                                            {{ $request->created_at?->format('d/m/Y') }}
                                        </strong>

                                        <small>
                                            {{ $request->created_at?->diffForHumans() }}
                                        </small>

                                    </div>

                                </td>


                                {{-- Actions --}}
                                <td>

                                   @if(auth()->user()->hasAdminPermission('audiobook_requests.show'))
                                        <div class="audiobook-row-actions">
                                            <a
                                                href="{{ route('admin.audiobooks.requests.show', $request) }}"
                                                class="view"
                                                title="Voir la demande"
                                            >
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </div>
                                    @endif

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @endif


        {{-- Pagination --}}
        @if($requests->hasPages())

            <footer class="audiobook-requests-pagination">

                <span>
                    Page {{ $requests->currentPage() }}
                    sur {{ $requests->lastPage() }}
                </span>

                {{ $requests->onEachSide(1)->links() }}

            </footer>

        @endif

    </div>

</div>

@endsection


@push('styles')

<style>

    .audiobook-requests-page {
        display: grid;
        gap: 22px;
    }


    /* ---------------------------------------------------------
       HEADER
    --------------------------------------------------------- */

    .audiobook-requests-heading {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 20px;
        background: transparent;
    }

    .audiobook-requests-heading > div:first-child > span {
        display: block;
        margin-bottom: 4px;
        color: #b30000;
        font-size: .8rem;
        font-weight: 800;
        letter-spacing: .08em;
        text-transform: uppercase;
    }

    .audiobook-requests-heading h2 {
        margin: 0;
        font-size: 1.75rem;
    }

    .audiobook-requests-heading p {
        margin: 5px 0 0;
        color: #777b83;
        font-size: .95rem;
    }

    .audiobook-requests-heading-icon {
        display: grid;
        width: 48px;
        height: 48px;
        place-items: center;
        border-radius: 13px;
        background: #fff0f0;
        color: #b30000;
        font-size: 1.3rem;
    }


    /* ---------------------------------------------------------
       STATS
    --------------------------------------------------------- */

    .audiobook-requests-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 13px;
    }

    .audiobook-requests-stats article {
        display: flex;
        align-items: center;
        gap: 13px;
        padding: 16px;
        border: 1px solid #e6e7ea;
        border-radius: 14px;
        background: #fff;
        box-shadow: 0 8px 24px rgba(15, 23, 42, .035);
    }

    .audiobook-requests-stats article > span {
        display: grid;
        width: 46px;
        height: 46px;
        flex: 0 0 46px;
        place-items: center;
        border-radius: 12px;
        font-size: 1.25rem;
    }

    .audiobook-requests-stats .pending {
        background: #fff5d9;
        color: #9a6500;
    }

    .audiobook-requests-stats .paid {
        background: #e8f5ff;
        color: #157bb5;
    }

    .audiobook-requests-stats .generating {
        background: #f1eaff;
        color: #7040a8;
    }

    .audiobook-requests-stats .completed {
        background: #eaf8ef;
        color: #138443;
    }

    .audiobook-requests-stats small,
    .audiobook-requests-stats strong {
        display: block;
    }

    .audiobook-requests-stats small {
        color: #858991;
        font-size: .78rem;
        font-weight: 700;
    }

    .audiobook-requests-stats strong {
        margin-top: 2px;
        font-size: 1.4rem;
    }


    /* ---------------------------------------------------------
       PANEL
    --------------------------------------------------------- */

    .audiobook-requests-panel {
        overflow: hidden;
        border: 1px solid #e5e7ea;
        border-radius: 17px;
        background: #fff;
        box-shadow: 0 8px 24px rgba(15, 23, 42, .035);
    }

    .audiobook-requests-panel > header {
        padding: 16px 19px 9px;
    }

    .audiobook-requests-panel > header strong,
    .audiobook-requests-panel > header span {
        display: block;
    }

    .audiobook-requests-panel > header strong {
        font-size: .95rem;
    }

    .audiobook-requests-panel > header span {
        margin-top: 2px;
        color: #989ba2;
        font-size: .78rem;
    }


    /* ---------------------------------------------------------
       TABLE
    --------------------------------------------------------- */

    .audiobook-requests-table {
        min-width: 1100px;
        margin: 0;
    }

    .audiobook-requests-table thead th {
        padding: 11px 14px;
        border-color: #eceef0;
        color: #8e9299;
        font-size: .74rem;
        font-weight: 700;
        letter-spacing: .05em;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .audiobook-requests-table tbody td {
        padding: 12px 14px;
        border-color: #eff0f2;
        color: #45484e;
        font-size: .86rem;
    }

    .audiobook-requests-table tbody tr:hover {
        background: #fcfcfd;
    }


    /* ---------------------------------------------------------
       BOOK
    --------------------------------------------------------- */

    .audiobook-book-cell {
        display: flex;
        min-width: 220px;
        align-items: center;
        gap: 11px;
    }

    .audiobook-book-cell img {
        width: 39px;
        height: 51px;
        flex: 0 0 39px;
        border-radius: 6px;
        background: #f0f1f2;
        object-fit: cover;
    }

    .audiobook-book-title {
        display: block;
        max-width: 220px;
        overflow: hidden;
        color: #23252a;
        font-size: .9rem;
        font-weight: 700;
        text-decoration: none;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .audiobook-book-title:hover {
        color: #b30000;
    }

    .audiobook-book-cell small {
        display: block;
        margin-top: 3px;
        color: #989ba2;
        font-size: .73rem;
    }


    /* ---------------------------------------------------------
       AUTHOR
    --------------------------------------------------------- */

    .audiobook-author-cell,
    .audiobook-date,
    .audiobook-amount {
        min-width: 130px;
    }

    .audiobook-author-cell strong,
    .audiobook-amount strong,
    .audiobook-date strong {
        display: block;
        color: #3a3d42;
        font-size: .85rem;
    }

    .audiobook-author-cell small,
    .audiobook-amount small,
    .audiobook-date small {
        display: block;
        margin-top: 3px;
        color: #989ba2;
        font-size: .73rem;
    }


    /* ---------------------------------------------------------
       VOICE
    --------------------------------------------------------- */

    .audiobook-voice {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        max-width: 150px;
        padding: 6px 9px;
        overflow: hidden;
        border-radius: 999px;
        background: #f0f1f3;
        color: #4d5056;
        font-size: .74rem;
        font-weight: 700;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .audiobook-voice i {
        color: #b30000;
    }


    /* ---------------------------------------------------------
       STATUS
    --------------------------------------------------------- */

    .audiobook-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: .74rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .audiobook-status.pending {
        background: #fff5d9;
        color: #9a6500;
    }

    .audiobook-status.paid {
        background: #e8f5ff;
        color: #157bb5;
    }

    .audiobook-status.generating {
        background: #f1eaff;
        color: #7040a8;
    }

    .audiobook-status.completed {
        background: #eaf8ef;
        color: #138443;
    }

    .audiobook-status.failed {
        background: #fff0f0;
        color: #b30000;
    }

    .audiobook-status.cancelled,
    .audiobook-status.default {
        background: #f0f1f3;
        color: #62666d;
    }


    /* ---------------------------------------------------------
       ACTIONS
    --------------------------------------------------------- */

    .audiobook-row-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 6px;
    }

    .audiobook-row-actions form {
        margin: 0;
    }

    .audiobook-row-actions .view,
    .audiobook-row-actions .generate {
        display: inline-flex;
        min-height: 32px;
        align-items: center;
        justify-content: center;
        gap: 5px;
        padding: 6px 10px;
        border-radius: 8px;
        font-size: .76rem;
        font-weight: 700;
        text-decoration: none;
    }

    .audiobook-row-actions .view {
        width: 32px;
        padding: 0;
        border: 1px solid #e2e4e7;
        background: #fff;
        color: #62666d;
    }

    .audiobook-row-actions .view:hover {
        border-color: #b30000;
        color: #b30000;
    }

    .audiobook-row-actions .generate {
        border: 0;
        background: #b30000;
        color: #fff;
    }

    .audiobook-row-actions .generate:hover {
        background: #8f0000;
    }

    .audiobook-row-actions .processing,
    .audiobook-row-actions .completed-action {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 9px;
        border-radius: 8px;
        font-size: .72rem;
        font-weight: 700;
    }

    .audiobook-row-actions .processing {
        background: #f1eaff;
        color: #7040a8;
    }

    .audiobook-row-actions .completed-action {
        background: #eaf8ef;
        color: #138443;
    }


    /* ---------------------------------------------------------
       EMPTY
    --------------------------------------------------------- */

    .audiobook-requests-empty {
        display: grid;
        width: 100%;
        min-height: 310px;
        place-items: center;
        align-content: center;
        justify-items: center;
        padding: 40px 24px;
        text-align: center;
    }

    .audiobook-requests-empty > span {
        display: grid;
        width: 60px;
        height: 60px;
        place-items: center;
        border-radius: 17px;
        background: #fff0f0;
        color: #b30000;
        font-size: 1.5rem;
    }

    .audiobook-requests-empty h3 {
        margin: 14px 0 4px;
        font-size: 1.12rem;
    }

    .audiobook-requests-empty p {
        margin: 0;
        color: #8c9097;
        font-size: .88rem;
    }


    /* ---------------------------------------------------------
       PAGINATION
    --------------------------------------------------------- */

    .audiobook-requests-pagination {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 15px 19px;
        border-top: 1px solid #eceef0;
    }

    .audiobook-requests-pagination > span {
        color: #8b8f96;
        font-size: .8rem;
    }

    .audiobook-requests-pagination .pagination {
        margin: 0;
    }


    /* ---------------------------------------------------------
       RESPONSIVE
    --------------------------------------------------------- */

    @media(max-width:1050px) {

        .audiobook-requests-stats {
            grid-template-columns: repeat(2, 1fr);
        }

    }


    @media(max-width:767px) {

        .audiobook-requests-heading {
            align-items: flex-start;
        }

        .audiobook-requests-heading-icon {
            display: none;
        }

        .audiobook-requests-pagination {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }

    }


    @media(max-width:520px) {

        .audiobook-requests-stats {
            grid-template-columns: 1fr;
        }

        .audiobook-row-actions .generate {
            font-size: 0;
        }

        .audiobook-row-actions .generate i {
            font-size: .9rem;
        }

    }

</style>

@endpush