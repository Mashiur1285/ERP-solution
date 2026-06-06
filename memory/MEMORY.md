# Memory Index

- [Stock/value correction procedure](stock-value-correction-procedure.md) — how stock cases & total value are computed from products.metadata->variants; which fields drive the card vs sale form vs lifting; preview→apply tinker pattern.
- [Lift/deposit/stock invariant](lift-deposit-stock-invariant.md) — lift total_amount ↔ supplier deposit balance_used must stay in sync; the "already been sold" edit-block red herring; why stock adjustments reconcile deposits.
- [App timezone / inventory snapshot](app-timezone-inventory-snapshot.md) — APP_TIMEZONE must be Asia/Dhaka not UTC; getInventoryStock hides future-dated batches, so a UTC clock one day behind empties the sale-page product list.
