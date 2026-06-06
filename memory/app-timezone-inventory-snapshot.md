---
name: app-timezone-inventory-snapshot
description: App must run in Asia/Dhaka, not UTC; getInventoryStock hides future-dated batches so a wrong timezone empties the sale-page product list
metadata:
  type: project
---

The business is in Bangladesh (Bengali UI, ৳ BDT). `config/app.php` defaults `timezone` to `Asia/Dhaka`, but `.env` had `APP_TIMEZONE=UTC` (6h behind). Lifts/products are dated with the user's local "today" (e.g. 2026-06-06), while a UTC `now()` was still 2026-06-05.

`ProductPurchaseRepository::getInventoryStock(?$snapshotDate = null)` defaults `$snapshotDate = now()->toDateString()` and **filters out any batch whose `products.date` is after the snapshot** (`if ($purchaseDate > $snapshotDate) return false;`). With the UTC clock one day behind, every same-day lift was treated as "future" and excluded → the Sales page (`/api/inventory/search` → `searchInventoryProducts` → `getInventoryStock`) returned **0 products**.

**Fix:** set `APP_TIMEZONE=Asia/Dhaka` in `.env`, then `php artisan config:clear`. Verified: `now()` → 2026-06-06, `getInventoryStock()` count went 0 → 1 (lemonade, variants 1000ml/2000ml).

Watch for this same off-by-one in any date-snapshot/reporting filter: always compare against the business-local date, never raw UTC. Related: [[stock-value-correction-procedure]].

**Sequel (2026-06-06): date columns must use a date-only cast.** `Lift.lift_date` and `Product.date` were cast `'datetime'`. A `datetime` cast serializes to a UTC ISO string (Dhaka midnight 2026-06-06 → `2026-06-05T18:00:00Z`), so the **date part shifts back a day**. The Lift Report (`LiftReport.vue` `filteredLifts`) filters `new Date(lift.lift_date)` against a default range of [today, today], so a shifted lift **vanishes from the report** (it's still in the DB — not deleted). It also pre-fills the edit form with the shifted date, so each save compounds the drift. Fix: cast `lift_date` / `Product.date` as `'date:Y-m-d'` (serializes as plain `'2026-06-06'`, no time/zone) and repair already-drifted rows (re-assign the intended `Y-m-d` and save). The write itself was fine (`2026-06-06 00:00:00` stored); only serialization drifted. **`Sale.sale_date` likely has the same latent bug** (Sales Report filters server-side `whereBetween` on today) — not yet changed; revisit if sales vanish from today's report.
