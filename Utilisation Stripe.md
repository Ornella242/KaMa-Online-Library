# Utilisation Stripe — KaMa Online Library

Guide de configuration et d’utilisation de **Stripe Checkout** pour les paiements KaMa (USD), en remplacement de Lemon Squeezy.

## Flux couverts

| Type | Parcours | Passerelle |
|------|----------|------------|
| Achat livre(s) | Panier → Commande → Paiement | Stripe Checkout (USD) |
| Frais de publication | Dépôt écrivain / admin | Stripe Checkout (USD) |
| Sponsoring | Paiement sponsoring | Stripe Checkout (USD) |

Webhook :

```
POST /webhooks/stripe
```

## Prérequis

- Compte [Stripe](https://dashboard.stripe.com/)
- URL publique (tunnel type ngrok en local) pour le webhook
- Montants KaMa en **USD**

## 1. Compte & mode test

1. Créer / se connecter au [Dashboard Stripe](https://dashboard.stripe.com/)
2. Activer le **Test mode** (toggle en haut à droite)
3. Docs : [Test mode](https://docs.stripe.com/test-mode)

## 2. Clés API

Dashboard → **Developers → API keys**

| Clé | Variable `.env` |
|-----|-----------------|
| Publishable key `pk_test_…` | `STRIPE_KEY` (optionnel, réservé usage futur) |
| Secret key `sk_test_…` | `STRIPE_SECRET` (**obligatoire**) |

## 3. Webhook

Dashboard → **Developers → Webhooks → Add endpoint**

| Champ | Valeur |
|-------|--------|
| Endpoint URL | `https://TON-TUNNEL/webhooks/stripe` |
| Events | `checkout.session.completed`, `checkout.session.async_payment_succeeded` |

Exemple local :

```
https://abc123.ngrok-free.app/webhooks/stripe
```

Copier le **Signing secret** (`whsec_…`) → `.env` : `STRIPE_WEBHOOK_SECRET`

Docs : [Webhooks](https://docs.stripe.com/webhooks)

## 4. Variables `.env`

```env
STRIPE_KEY=pk_test_xxx
STRIPE_SECRET=sk_test_xxx
STRIPE_WEBHOOK_SECRET=whsec_xxx
STRIPE_TEST_MODE=true
```

Puis :

```bash
php artisan config:clear
```

## 5. Devise

Tous les montants facturés via Stripe sont en **USD** (Checkout Session `currency=usd`).

## 6. Test bout en bout

1. Lancer l’app + tunnel HTTPS vers le webhook
2. Panier → commande → **Payer avec Stripe**
3. Carte test : `4242 4242 4242 4242`, date future, CVC quelconque
4. Retour sur KaMa → commande `paid` après webhook
5. Vérifier les logs webhook dans le Dashboard Stripe

Même flux pour **frais de publication** et **sponsoring** (metadata `type` différente).

## Dépannage

| Symptôme | Cause probable | Action |
|----------|----------------|--------|
| Bouton « Stripe non configuré » | `STRIPE_SECRET` manquant | Remplir `.env` + `config:clear` |
| Webhook `401` | Mauvais signing secret | Recopier `STRIPE_WEBHOOK_SECRET` |
| Paiement OK mais commande pending | Webhook non livré | Tunnel / URL / events |
| Erreur montant &lt; 0,50 USD | Minimum Stripe | Augmenter le montant |

## Checklist

1. Compte Stripe + Test mode  
2. `STRIPE_SECRET`  
3. Webhook → `https://…/webhooks/stripe`  
4. `STRIPE_WEBHOOK_SECRET`  
5. Paiement test carte `4242…` validé  
