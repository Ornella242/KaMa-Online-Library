# Utilisation PawaPay — Mobile Money (KaMa)

PawaPay complète **Stripe** : le client choisit **carte** ou **Mobile Money**.

## Flux

```
Panier → Infos commande (pays) → Page paiement
                                    ├─ Carte      → Stripe (EUR)
                                    └─ Mobile Money → PawaPay (devise du pays)
```

Le **pays de la commande** détermine automatiquement la devise MoMo (ex. Ghana → GHS, Bénin → XOF).

### Marchés couverts (20)

BJ, BF, CI, SN (XOF) · CM, CG, GA (XAF) · GH (GHS) · NG (NGN) · KE (KES) · UG (UGX) · TZ (TZS) · RW (RWF) · ZM (ZMW) · MW (MWK) · MZ (MZN) · CD (CDF) · ET (ETB) · LS (LSL) · SL (SLE)

Webhook :

```
POST /webhooks/pawapay
```

## Configuration

```env
PAWAPAY_API_TOKEN=ton_token
PAWAPAY_SANDBOX=true
```

Callback deposits : `https://TON-TUNNEL/webhooks/pawapay`

Taux de change : **Admin → Réglages → Taux Mobile Money** (stockés en BDD, sans redeploy).  
Défauts dans `config/pawapay.php`.

## Test Ghana

1. Commande avec pays = Ghana  
2. Choisir Mobile Money → montant affiché en **GHS**  
3. Page PawaPay sandbox  

Docs : [Providers](https://docs.pawapay.io/v2/docs/providers) · [Payment Page](https://docs.pawapay.io/v2/docs/payment_page)
