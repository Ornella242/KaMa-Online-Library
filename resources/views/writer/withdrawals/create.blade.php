@extends('layouts.writer')

@section('writer-content')
<main class="writer-page">
    <div class="container">
        <header class="writer-page-header">
            <div>
                <span class="writer-page-eyebrow">Finances</span>
                <h1>Demande de retrait</h1>
                <p>Choisissez comment recevoir vos fonds, puis indiquez le montant à retirer.</p>
            </div>
            <a href="{{ route('writer.wallet') }}" class="writer-primary-action">
                <i class="bi bi-wallet2"></i> Retour au portefeuille
            </a>
        </header>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if($openWithdrawal)
            <div class="writer-panel">
                <div class="alert alert-warning mb-0">
                    Vous avez déjà une demande <strong>#{{ $openWithdrawal->id }}</strong>
                    ({{ $openWithdrawal->statusLabel() }}) en cours.
                    Attendez son traitement avant d’en créer une nouvelle.
                </div>
            </div>
        @else
            <div class="writer-metric-grid mb-4">
                <article class="writer-metric">
                    <span class="writer-metric-icon red"><i class="bi bi-wallet2"></i></span>
                    <div>
                        <small>Solde disponible</small>
                        <strong>${{ number_format($wallet->balance, 2, '.', ',') }}</strong>
                        <span class="neutral">Maximum retirable</span>
                    </div>
                </article>
                <article class="writer-metric">
                    <span class="writer-metric-icon black"><i class="bi bi-percent"></i></span>
                    <div>
                        <small>Commission KaMa</small>
                        <strong>{{ number_format($commissionPercent, 2, '.', ',') }} %</strong>
                        <span class="neutral">Prélevée sur le montant</span>
                    </div>
                </article>
                <article class="writer-metric">
                    <span class="writer-metric-icon blue"><i class="bi bi-cash-coin"></i></span>
                    <div>
                        <small>Minimum</small>
                        <strong>${{ number_format($minimumAmount, 2, '.', ',') }}</strong>
                        <span class="neutral">Par demande</span>
                    </div>
                </article>
            </div>

            <form method="POST" action="{{ route('writer.withdrawals.store') }}" id="withdrawalForm" class="writer-panel">
                @csrf

                <header class="writer-panel-header">
                    <div>
                        <span>Étape 1</span>
                        <h2>Mode de versement</h2>
                        <p>Sélectionnez le canal sur lequel l’argent vous sera envoyé.</p>
                    </div>
                </header>

                <div class="withdrawal-methods">
                    @foreach($methods as $key => $method)
                        <label class="withdrawal-method-card">
                            <input type="radio"
                                   name="payment_method"
                                   value="{{ $key }}"
                                   @checked(old('payment_method', 'mobile_money') === $key)
                                   required>
                            <span class="method-body">
                                <i class="bi {{ $method['icon'] }}"></i>
                                <strong>{{ $method['label'] }}</strong>
                                <small>{{ $method['hint'] }}</small>
                            </span>
                        </label>
                    @endforeach
                </div>

                <header class="writer-panel-header mt-5">
                    <div>
                        <span>Étape 2</span>
                        <h2>Coordonnées de réception</h2>
                        <p>Renseignez les informations du compte choisi.</p>
                    </div>
                </header>

                <div id="payoutFields" class="withdrawal-fields"></div>

                <header class="writer-panel-header mt-5">
                    <div>
                        <span>Étape 3</span>
                        <h2>Montant</h2>
                        <p>Le montant demandé est débité immédiatement de votre portefeuille.</p>
                    </div>
                </header>

                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Montant à retirer (USD)</label>
                        <input type="number"
                               name="amount"
                               id="withdrawalAmount"
                               class="form-control"
                               min="{{ $minimumAmount }}"
                               max="{{ $wallet->balance }}"
                               step="0.01"
                               value="{{ old('amount') }}"
                               required>
                    </div>
                    <div class="col-md-8">
                        <div class="withdrawal-summary">
                            <div><span>Commission ({{ number_format($commissionPercent, 2, '.', ',') }} %)</span><strong id="commissionPreview">$0.00</strong></div>
                            <div><span>Vous recevrez</span><strong id="netPreview" class="text-success">$0.00</strong></div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-end gap-2">
                    <a href="{{ route('writer.wallet') }}" class="btn btn-outline-secondary">Annuler</a>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-send-check me-1"></i> Lancer la demande
                    </button>
                </div>
            </form>
        @endif
    </div>
</main>
@endsection

@push('styles')
<style>
#withdrawalForm.writer-panel{
    padding:0 0 32px;
}
#withdrawalForm .writer-panel-header{
    margin:0 0 8px!important;
    padding:22px 28px 18px!important;
}
#withdrawalForm .withdrawal-methods,
#withdrawalForm .withdrawal-fields,
#withdrawalForm .row.g-3,
#withdrawalForm .d-flex.justify-content-end{
    padding-left:28px;
    padding-right:28px;
}
.withdrawal-methods{
    display:grid;
    grid-template-columns:repeat(3,minmax(0,1fr));
    gap:18px;
    margin:12px 0 28px;
}
.withdrawal-method-card{cursor:pointer;position:relative}
.withdrawal-method-card input{position:absolute;opacity:0;pointer-events:none}
.withdrawal-method-card .method-body{
    display:grid;
    gap:10px;
    min-height:132px;
    padding:22px 20px;
    border:1px solid #e7e8eb;
    border-radius:14px;
    background:#fff;
    transition:.15s ease;
}
.withdrawal-method-card .method-body i{font-size:1.45rem;color:#b30000}
.withdrawal-method-card .method-body strong{font-size:.95rem}
.withdrawal-method-card .method-body small{color:#888;font-size:.78rem;line-height:1.45}
.withdrawal-method-card input:checked + .method-body{border-color:#b30000;box-shadow:0 0 0 3px rgba(179,0,0,.12)}
.withdrawal-fields{
    display:grid;
    grid-template-columns:repeat(2,minmax(0,1fr));
    gap:24px 22px;
    margin:12px 0 28px;
}
.withdrawal-fields > div{
    display:flex;
    flex-direction:column;
    gap:10px;
}
.withdrawal-fields .form-label{
    display:block;
    margin:0;
    font-weight:600;
    font-size:.88rem;
    color:#333;
}
.withdrawal-fields .form-control,
.withdrawal-fields .form-select{
    min-height:50px;
    padding:12px 16px;
    border-radius:10px;
}
.withdrawal-fields > div:last-child:nth-child(odd){grid-column:1 / -1}
#withdrawalForm .row.g-3{
    margin-top:12px!important;
    margin-bottom:12px;
    --bs-gutter-x:1.5rem;
    --bs-gutter-y:1.25rem;
}
#withdrawalForm .row.g-3 .form-label{
    display:block;
    margin-bottom:10px;
    font-weight:600;
}
#withdrawalForm .row.g-3 .form-control{
    min-height:50px;
    padding:12px 16px;
    border-radius:10px;
}
.withdrawal-summary{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:16px;
    padding:20px 22px;
    border:1px solid #eceef0;
    border-radius:12px;
    background:#fafafa;
}
.withdrawal-summary span{
    display:block;
    margin-bottom:8px;
    color:#888;
    font-size:.72rem;
    font-weight:700;
    text-transform:uppercase;
}
.withdrawal-summary strong{font-size:1.1rem}
#withdrawalForm .d-flex.justify-content-end{
    margin-top:28px!important;
    padding-top:8px;
}
@media(max-width:900px){
    #withdrawalForm .writer-panel-header,
    #withdrawalForm .withdrawal-methods,
    #withdrawalForm .withdrawal-fields,
    #withdrawalForm .row.g-3,
    #withdrawalForm .d-flex.justify-content-end{padding-left:18px;padding-right:18px}
    .withdrawal-methods{grid-template-columns:1fr}
    .withdrawal-fields{grid-template-columns:1fr;gap:20px}
}
@media(max-width:560px){.withdrawal-summary{grid-template-columns:1fr}}
</style>
@endpush

@push('scripts')
<script>
const methods = @json($methods);
const commissionPercent = {{ (float) $commissionPercent }};
const fieldsBox = document.getElementById('payoutFields');
const amountInput = document.getElementById('withdrawalAmount');
const commissionPreview = document.getElementById('commissionPreview');
const netPreview = document.getElementById('netPreview');
const oldDetails = @json(old('payout_details', []));

function renderFields(methodKey) {
    const method = methods[methodKey];
    if (!method || !fieldsBox) return;

    fieldsBox.innerHTML = Object.entries(method.fields).map(([key, field]) => {
        const value = oldDetails[key] ?? '';
        const required = field.required === false ? '' : 'required';

        if (field.type === 'select') {
            const options = (field.options || []).map(opt =>
                `<option value="${opt}" ${value === opt ? 'selected' : ''}>${opt}</option>`
            ).join('');
            return `<div>
                <label class="form-label">${field.label}</label>
                <select class="form-select" name="payout_details[${key}]" ${required}>
                    <option value="">Choisir…</option>
                    ${options}
                </select>
            </div>`;
        }

        return `<div>
            <label class="form-label">${field.label}</label>
            <input class="form-control"
                   type="${field.type || 'text'}"
                   name="payout_details[${key}]"
                   placeholder="${field.placeholder || ''}"
                   value="${value}"
                   ${required}>
        </div>`;
    }).join('');
}

function updatePreview() {
    const amount = parseFloat(amountInput?.value || '0') || 0;
    const commission = Math.round((amount * commissionPercent / 100) * 100) / 100;
    const net = Math.round((amount - commission) * 100) / 100;
    if (commissionPreview) commissionPreview.textContent = `$${commission.toFixed(2)}`;
    if (netPreview) netPreview.textContent = `$${Math.max(net, 0).toFixed(2)}`;
}

document.querySelectorAll('input[name="payment_method"]').forEach(input => {
    input.addEventListener('change', () => renderFields(input.value));
});

amountInput?.addEventListener('input', updatePreview);

const selected = document.querySelector('input[name="payment_method"]:checked')?.value || 'mobile_money';
renderFields(selected);
updatePreview();
</script>
@endpush
