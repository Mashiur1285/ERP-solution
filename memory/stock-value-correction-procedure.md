---
name: stock-value-correction-procedure
description: How to manually correct a product's stock cases and total value via products.metadata->variants so the inventory card AND sale form agree
metadata:
  type: project
---

Stock cases and total value are **computed**, not stored, from `products.metadata->variants` (a catalog has many `products` rows = purchase "batches"; each metadata has a `variants` array). There is no single editable field — corrections edit the JSON.

**Three sections read DIFFERENT fields — update all that matter:**
- **Inventory/Product card** ("Stock Cases"): `FLOOR( ((cases_without_free_bottles*bpc - sold_purchased) + (total_free_bottles - sold_free)) / bpc )`, summed across batches; `sold_*` = non-draft `sale_items` where `product_id` = that batch AND matching `variant`. Per-batch, floored at 0.
- **Sale form available stock**: uses `current_purchased_quantity` / `current_free_quantity` (live running balance, decremented per sale in [ProductPurchaseRepository](app/Repositories/ProductPurchaseRepository.php)). Falls back to `cases_without_free_bottles*bpc` only if unset. So to change sale-form stock you MUST set `current_purchased_quantity`.
- **Product List "Purchase Amount"** (per-variant chip AND product-level sum): both read each batch variant's `total_cost`. As of 2026-06-06, `ProductPurchaseRepository::updateInventory` recomputes `total_cost = max(0,current_purchased_quantity) * actual_rate_per_bottle` on every sale (and on sale-edit restore, since restore calls it with negative qty) — so Purchase Amount now drops as stock is sold, mirroring `adjustVariantStock`. Free bottles carry no cost. Rate fallback: `total_cost/initial` then `case_buying_price/bpc`. Pre-fix desynced batches were backfilled once. A manual tinker edit of `current_purchased_quantity` should also recompute `total_cost` to stay consistent.
- **Lifting section**: separate `lift_items` table (`case_buying_price`, `number_of_cases`). Historical; feeds supplier totals. NOTE: the Product List "Adjust Inventory" UI (`adjustVariantStock` in [ProductPurchaseController](app/Http/Controllers/ProductPurchaseController.php)) now ALSO rewrites the matching `lift_items` row (by `product_id`+`variant`) and recomputes the parent `Lift.total_amount`, so the Lift Report tracks adjustments. A manual tinker correction that edits only `products.metadata` will leave the Lift Report stale — update `lift_items` too if you go that route.
- **Card "Stock Cases / Stock Bottles / Purchase Amount" are catalog-wide SUMS across ALL variants**, not per-variant. Per-variant value shows only as the chip (`250ml • ৳<total_cost>`).

**Per-case price fields in a variant:** `case_buying_price`, `actual_rate_per_case` (= per case), `actual_rate_per_bottle` (= price/bpc). Total value = `SUM(total_cost)`.

**Procedure:** match catalog by name (e.g. `LOWER(name) LIKE '%uro%cola%'`; names may contain emoji), inspect per-batch net + value first, then run a tinker heredoc with an `$apply` toggle (false=preview, true=write). ALWAYS confirm the run printed `APPLIED.` — a silent paste that prints nothing did NOT execute (verify by re-reading). To hit an exact net target on a keeper batch while covering its recorded sales: `need = targetCases*bpc + sold_purchased + sold_free; cw = intdiv(need,bpc); free = need%bpc;` and set `current_purchased_quantity = targetCases*bpc`. Zero other batches' variant. Variant labels have no space (`250ml`, not `250 ml`).

Done 2026-06-03 for Fizup (250ml=510/৳189040, 1000ml=0, 2000ml=68@558.46) and Uro Cola (0, value 36,431.61) / Uro Orange (56@403.20, value 22,579.20). Past sales and lifting were left untouched per user. See [[fizup-uro-stock-corrections-2026-06]].
