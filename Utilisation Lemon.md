# Utilisation Lemon Squeezy — KaMa Online Library

Guide de configuration et d’utilisation de **Lemon Squeezy** pour les paiements KaMa (USD), en remplacement de KKiaPay.

Ce document décrit le setup Test mode, la création du produit/variant, le webhook, le `.env`, et la checklist de test.

---

## Vue d’ensemble

KaMa utilise **un seul produit Lemon Squeezy** (variant générique).  
Le montant réel (panier, frais de dépôt, sponsoring) est envoyé dynamiquement via `custom_price`.

| Flux KaMa | Route / écran | Paiement |
|---|---|---|
| Achat livre(s) | Panier → Commande → Paiement | Lemon Squeezy (USD) |
| Frais de publication | Dépôt écrivain / admin | Lemon Squeezy (USD) |
| Sponsoring | Paiement sponsoring | Lemon Squeezy (USD) |

Webhook KaMa :

```text
POST /webhooks/lemonsqueezy
```

---

## Prérequis

- Compte [Lemon Squeezy](https://www.lemonsqueezy.com/)
- Projet KaMa lancé en local (`php artisan serve`)
- Tunnel public pour les webhooks en local (ngrok, Cloudflare Tunnel, etc.)

```bash
cd /chemin/vers/KaMa-Online-Library
php artisan serve
```

App locale : `http://127.0.0.1:8000`

---

## 1. Créer le compte / store

Sur [lemonsqueezy.com](https://www.lemonsqueezy.com/) → **Get started / Sign up**

### Infos à renseigner (même sans site déployé)

| Champ | Valeur recommandée |
|---|---|
| Nom de l’entreprise | `KaMa Online Library` |
| URL du site | `http://localhost` (temporaire) |
| Devise | **USD** |

Notes :

- Rester en **Test mode** (activé par défaut)
- L’URL pourra être mise à jour plus tard
- Ne passer en **Live** qu’avec un vrai domaine et les infos de payout

Docs : [Test Mode](https://docs.lemonsqueezy.com/help/getting-started/test-mode)

---

## 2. Récupérer le Store ID

1. Dashboard Lemon Squeezy  
2. **Settings → Stores**  
3. Copier le **Store ID** (ex. `12345`)

→ Variable `.env` : `LEMON_SQUEEZY_STORE_ID`

---

## 3. Créer le produit + variant

KaMa n’a **pas** besoin d’un produit Lemon Squeezy par livre.  
Créer **un seul produit générique**.

### 3.1 Produit

**Products → New product**

| Champ | Valeur |
|---|---|
| Name | `KaMa Purchase` |
| Description | `Paiement KaMa Online Library (ebooks, dépôt, sponsoring)` |
| Pricing | **Single payment** (one-time) |
| Price | `1.00 USD` (prix placeholder) |
| Currency | **USD** |

Le prix `1.00` est volontaire : KaMa écrase ce montant à chaque paiement via `custom_price`.

### 3.2 Variant

Lemon Squeezy crée souvent un variant **Default** automatiquement.

Vérifier :

- Nom : `Default` (ou `Standard`)
- Prix : `1.00 USD`
- Disponible en test
   
### 3.3 Variant ID

Sur le produit / variant → menu `...` → **Copy ID**

→ Variable `.env` : `LEMON_SQUEEZY_VARIANT_ID`

Docs : [Taking Payments](https://docs.lemonsqueezy.com/guides/developer-guide/taking-payments)

---

## 4. Créer l’API Key

1. **Settings → API**  
2. **Create API key**  
3. Nom suggéré : `KaMa Local Test`  
4. Copier la clé immédiatement (affichée une seule fois)

→ Variable `.env` : `LEMON_SQUEEZY_API_KEY`

Important : créer la clé **en Test mode**. Les clés test ≠ live.

---

## 5. Tunnel local (webhooks)

`localhost` n’est pas accessible depuis Internet. Il faut un tunnel.

### Option A — ngrok

```bash
ngrok http 8000
```

Exemple d’URL : `https://abc123.ngrok-free.app`

### Option B — Cloudflare Tunnel

```bash
cloudflared tunnel --url http://127.0.0.1:8000
```

Garder le tunnel ouvert pendant les tests.

---

## 6. Créer le webhook

Dans Lemon Squeezy : **Settings → Webhooks → Create**

| Champ | Valeur |
|---|---|
| Callback URL | `https://TON-TUNNEL/webhooks/lemonsqueezy` |
| Events | `order_created` |
| Signing secret | générer / copier |

Exemple :

```text
https://abc123.ngrok-free.app/webhooks/lemonsqueezy
```

→ Variable `.env` : `LEMON_SQUEEZY_WEBHOOK_SECRET`

Docs : [Webhooks](https://docs.lemonsqueezy.com/guides/developer-guide/webhooks)

---

## 7. Configuration `.env` KaMa

```env
LEMON_SQUEEZY_API_KEY=xxx_ta_cle_api_test
LEMON_SQUEEZY_STORE_ID=12345
LEMON_SQUEEZY_VARIANT_ID=67890
LEMON_SQUEEZY_WEBHOOK_SECRET=whsec_xxxxx
LEMON_SQUEEZY_TEST_MODE=true
```

Puis :

```bash
php artisan config:clear
```

---

## 8. Alignement des prix KaMa (USD)

Tous les montants facturés via Lemon Squeezy doivent être en **USD** :

- prix des livres
- frais de publication
- plans de sponsoring

Exemple : livre à `$9.99` → valeur en base `9.99`

---

## 9. Checklist de test

### A. Achat client

1. Catalogue → Ajouter un livre au panier  
2. `/panier` → **Commander**  
3. Remplir les infos acheteur  
4. Page paiement → **Payer avec Lemon Squeezy**  
5. Carte test : `4242 4242 4242 4242`  
   - Date future  
   - CVC quelconque  
   - ZIP : '31905'
6. Valider le paiement  
7. Vérifier :
   - commande en statut `paid`
   - page succès / email
   - badge panier mis à jour

### B. Webhook

Dans Lemon Squeezy → Webhooks → logs de livraison :

- requête `order_created`
- réponse HTTP `200`

Si échec :

- tunnel coupé
- mauvaise URL
- mauvais secret
- serveur Laravel arrêté

### C. Dépôt / Sponsoring

Même bouton Lemon Squeezy, montants différents, même produit/variant.

---

## Cartes de test (Test mode)

| Cas | Numéro |
|---|---|
| Succès Visa | `4242 4242 4242 4242` |

Ne pas utiliser de vraie carte en test.

---

## Récap des variables

| Où le trouver | Variable `.env` |
|---|---|
| Settings → Stores | `LEMON_SQUEEZY_STORE_ID` |
| Products → variant → Copy ID | `LEMON_SQUEEZY_VARIANT_ID` |
| Settings → API | `LEMON_SQUEEZY_API_KEY` |
| Settings → Webhooks → secret | `LEMON_SQUEEZY_WEBHOOK_SECRET` |
| Mode test / live | `LEMON_SQUEEZY_TEST_MODE` |

---

## Erreurs fréquentes

| Problème | Cause probable | Correction |
|---|---|---|
| Bouton « Lemon Squeezy non configuré » | `.env` incomplet ou cache config | Remplir `.env` + `php artisan config:clear` |
| Checkout OK mais commande reste `pending` | Webhook non reçu | Vérifier tunnel + URL webhook |
| Webhook `401` | Mauvais secret | Recopier `LEMON_SQUEEZY_WEBHOOK_SECRET` |
| Montant incorrect | Prix KaMa pas en USD | Corriger les prix en `$` |
| URL webhook invalide après redémarrage ngrok | Nouvelle URL générée | Mettre à jour le webhook dans LS |

---

## Ordre ultra-court

1. Compte LS (`KaMa Online Library` + `http://localhost`)  
2. Produit `KaMa Purchase` / `$1` USD / variant Default  
3. Copier Store ID + Variant ID + API key  
4. Lancer `ngrok http 8000`  
5. Webhook → `https://tunnel/webhooks/lemonsqueezy` (`order_created`)  
6. Remplir `.env` + `php artisan config:clear`  
7. Tester un achat avec `4242…`

---


## Statut

Configuration Lemon Squeezy opérationnelle en Test mode : création de compte, produit/variant, `.env`, webhook et paiement de bout en bout validés.
