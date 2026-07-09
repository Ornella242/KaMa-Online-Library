@extends('layouts.writer')

@section('writer-content')
    <div class="container">
        <!-- PAGE HEADER START -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                 <div class="row">
                <div class="col-12">
                    <h1 class="fs-4 mb-0"><i class="bi bi-wallet2 fa-fw me-1"></i>Revenus</h1>
                </div>
            </div>	

                <p class="text-black mb-0">
                    Suivez les performances financières de vos ouvrages KaMa.
                </p>

            </div>
        </div>
        <!-- PAGE HEADER END -->


        <!-- STATISTICS START -->
        <div class="row g-4 mb-4">
            <!-- TOTAL REVENUE -->
            <div class="col-sm-6 col-xl-3">
                <div class="revenue-card">
                    <div class="revenue-icon bg-success-soft">
                        <i class="bi bi-currency-dollar"></i>
                    </div>

                    <div>

                        <h3>
                            ${{ number_format($totalRevenue,2) }}
                        </h3>


                        <p>
                            Revenus totaux
                        </p>

                        <span class="text-success small">
                            <i class="bi bi-arrow-up"></i>
                            Revenus générés
                        </span>
                    </div>
                </div>
            </div>

            <!-- MONTH REVENUE -->
            <div class="col-sm-6 col-xl-3">
                <div class="revenue-card">
                    <div class="revenue-icon bg-primary-soft">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <div>
                        <h3>
                            ${{ number_format($monthlyRevenue,2) }}
                        </h3>
                        <p>
                            Ce mois
                        </p>
                        <span class="text-primary small">
                            <i class="bi bi-graph-up"></i>
                            Performance 
                        </span>
                    </div>
                </div>
            </div>


            <!-- SALES -->
            <div class="col-sm-6 col-xl-3">
                <div class="revenue-card">
                    <div class="revenue-icon bg-warning-soft">
                        <i class="bi bi-book"></i>
                    </div>

                    <div>
                        <h3>
                            {{ $totalSales }}
                        </h3>
                        <p>
                            Livres vendus
                        </p>
                        <span class="text-warning small">
                            <i class="bi bi-cart-check"></i>
                            Achats confirmés
                        </span>
                    </div>
                </div>
            </div>

            <!-- READERS -->
            <div class="col-sm-6 col-xl-3">
                <div class="revenue-card">
                    <div class="revenue-icon bg-danger-soft">
                        <i class="bi bi-people"></i>
                    </div>
                    <div>
                        <h3>
                            {{ $totalReaders }}
                        </h3>
                        <p>
                            Lecteurs
                        </p>
                        <span class="text-danger small">
                            <i class="bi bi-person-check"></i>
                            Clients uniques
                        </span>
                    </div>
                </div>

            </div>
        </div>
        <!-- STATISTICS END -->

        <!-- SALES HISTORY START -->

            <div class="card border-0 shadow-sm rounded-4">
                <!-- HEADER -->
                <div class="card-header sales-header">

                    <div class="d-flex justify-content-between align-items-center">

                        <div class="sales-header-content">

                            <h5 class="fw-bold mb-1">
                                <span class="sales-icon">
                                    <i class="bi bi-receipt-cutoff"></i>
                                </span>

                                Historique des ventes
                            </h5>

                            <p class="mb-0">
                                Retrouvez tous les achats effectués par vos lecteurs.
                            </p>

                        </div>


                        <span class="sales-count">
                            {{ $purchasePayments->total() }} ventes
                        </span>


                    </div>

                </div>
                <!-- BODY -->

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>
                                        Livre
                                    </th>

                                    <th>
                                        Lecteur
                                    </th>

                                    <th>
                                        Montant
                                    </th>
                                    <th>
                                       Méthode de  Paiement
                                    </th>

                                    <th>
                                        Statut
                                    </th>

                                    <th>
                                        Date
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                            @forelse($purchasePayments as $payment)
                                <tr>
                                    <!-- BOOK -->
                                    <td>
                                        <div class="d-flex align-items-center">


                                            <img 
                                            src="{{ asset('storage/'.$payment->book->cover_image) }}"
                                            width="45"
                                            height="60"
                                            class="rounded-3 me-3"
                                            style="object-fit:cover;">



                                            <div>


                                                <h6 class="mb-1 fw-bold">

                                                    {{ $payment->book->title }}

                                                </h6>


                                                <small class="text-muted">

                                                    {{ ucfirst($payment->book->type) }}

                                                </small>


                                            </div>


                                        </div>
                                    </td>

                                    <!-- READER -->

                                    <td>
                                        <div>
                                            <strong>
                                                {{ $payment->user->firstname }}
                                                {{ $payment->user->lastname }}
                                            </strong>
                                            <br>
                                            <small class="text-muted">
                                                {{ $payment->user->email }}
                                            </small>
                                        </div>
                                    </td>

                                    <!-- AMOUNT -->
                                    <td>
                                        <span class="fw-bold text-success">
                                            {{ $payment->currency }}
                                            {{ number_format($payment->amount,2) }}
                                        </span>
                                    </td>

                                    <!-- METHOD -->

                                    <td>
                                        @if($purchasePayments->payment_method)
                                            <span class="payment-method">
                                                <i class="bi bi-credit-card me-1"></i>
                                                {{ ucfirst($payment->payment_method) }}
                                            </span>
                                        @else
                                            <span class="text-muted">
                                                -
                                            </span>


                                        @endif


                                    </td>

                                    <!-- STATUS -->
                                    <td>
                                        @if($payment->status == 'success')
                                            <span class="status-success">
                                                <i class="bi bi-check-circle-fill"></i>
                                                Payé
                                            </span>
                                        @elseif($payment->status == 'pending')
                                            <span class="status-pending">
                                                <i class="bi bi-hourglass-split"></i>
                                                En attente
                                            </span>
                                        @else
                                            <span class="status-failed">
                                                <i class="bi bi-x-circle-fill"></i>
                                                Échec
                                            </span>
                                        @endif
                                    </td>
                                    <!-- DATE -->

                                    <td>
                                        {{ $payment->created_at->format('d M Y') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <i class="bi bi-cart-x fs-1 icon-red"></i>
                                        <h6 class="mt-3">
                                            Aucune vente enregistrée
                                        </h6>
                                        <p class="text-muted">
                                            Vos ventes apparaîtront ici.
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    <!-- PAGINATION -->

                            <div class="card-footer bg-white border-top">
                                {{ $purchasePayments->links() }}
                            </div>
                        </div>
                    <!-- SALES HISTORY END -->
            </div>

        <!-- PUBLICATION HISTORY START -->
            <div class="card border-0 shadow-sm rounded-4 mt-4">


                <!-- HEADER -->

                <div class="card-header publication-header">


                    <div class="d-flex justify-content-between align-items-center">


                        <div class="publication-header-content">


                            <h5 class="fw-bold mb-1">

                                <span class="publication-icon">

                                    <i class="bi bi-cloud-upload"></i>

                                </span>


                                Historique des publications

                            </h5>


                            <p class="mb-0">

                                Suivez les frais de dépôt liés à vos ouvrages.

                            </p>


                        </div>



                        <div class="publication-count">

                            <strong>
                                {{ $publicationPayments->total() }}
                            </strong>

                            <span>
                                dépôts
                            </span>

                        </div>



                    </div>


                </div>




                <!-- BODY -->


                <div class="card-body p-0">


                    <div class="table-responsive">


                        <table class="table align-middle mb-0">


                            <thead class="table-light">


                                <tr>

                                    <th>
                                        Livre
                                    </th>


                                    <th>
                                        Type
                                    </th>


                                    <th>
                                        Montant
                                    </th>


                                    <th>
                                        Référence
                                    </th>


                                    <th>
                                        Statut
                                    </th>


                                    <th>
                                        Date
                                    </th>


                                </tr>


                            </thead>



                            <tbody>


                            @forelse($publicationPayments as $payment)


                                <tr>


                                    <!-- BOOK -->

                                    <td>


                                        <div class="d-flex align-items-center">


                                            <img
                                            src="{{ asset('storage/'.$payment->book->cover_image) }}"
                                            width="45"
                                            height="60"
                                            class="rounded-3 me-3"
                                            style="object-fit:cover;">



                                            <div>


                                                <h6 class="mb-1 fw-bold">

                                                    {{ $payment->book->title }}

                                                </h6>


                                                <small class="text-muted">

                                                    {{ ucfirst($payment->book->type) }}

                                                </small>


                                            </div>


                                        </div>


                                    </td>




                                    <!-- TYPE -->

                                    <td>


                                        <span class="publication-type">


                                            <i class="bi bi-journal-text me-1"></i>


                                            Dépôt publication


                                        </span>


                                    </td>




                                    <!-- AMOUNT -->


                                    <td>


                                        <strong class="text-danger">


                                            {{ $payment->currency }}

                                            {{ number_format($payment->amount,2) }}


                                        </strong>


                                    </td>




                                    <!-- REFERENCE -->


                                    <td>


                                        <span class="reference-code">


                                            {{ $payment->reference }}


                                        </span>


                                    </td>





                                    <!-- STATUS -->


                                    <td>


                                        @if($payment->status == 'success')


                                            <span class="status-success">

                                                <i class="bi bi-check-circle-fill"></i>

                                                Payé

                                            </span>


                                        @elseif($payment->status == 'pending')


                                            <span class="status-pending">

                                                <i class="bi bi-hourglass-split"></i>

                                                En attente

                                            </span>


                                        @else


                                            <span class="status-failed">

                                                <i class="bi bi-x-circle-fill"></i>

                                                Échec

                                            </span>


                                        @endif


                                    </td>





                                    <!-- DATE -->

                                    <td>


                                        {{ $payment->created_at->format('d M Y') }}


                                    </td>



                                </tr>



                            @empty


                                <tr>

                                    <td colspan="6" class="text-center py-5">


                                        <i class="bi bi-cloud-upload fs-1 text-muted"></i>


                                        <h6 class="mt-3">

                                            Aucun dépôt effectué

                                        </h6>


                                        <p class="text-muted">

                                            Vos paiements de publication apparaîtront ici.

                                        </p>


                                    </td>


                                </tr>


                            @endforelse


                            </tbody>


                        </table>


                    </div>


                </div>




                <!-- FOOTER -->


                <div class="card-footer bg-white border-top">


                    {{ $publicationPayments->links() }}


                </div>


            </div>
        <!-- PUBLICATION HISTORY END -->
    </div>
@endsection