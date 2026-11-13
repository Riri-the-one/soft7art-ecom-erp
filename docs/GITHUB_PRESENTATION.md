# Portfolio presentation

Suggested description: **Laravel e-commerce ERP internship project (2026): inventory, order lifecycle, CRM, role authorization and automated tests.**

Suggested topics: laravel, php, blade, tailwindcss, alpinejs, erp, inventory-management, phpunit, portfolio.

Present the project as a 2026 internship project for PFE 2027 applications. Do not claim production customers, commercial outcomes, performance measurements or an employer that is not documented.

## Interview walkthrough

1. Explain the MVC request path, Form Requests and role/policy matrix.
2. Create a pending order and explain why it does not reserve inventory yet.
3. Confirm it, repeat the confirmation and show the regression test proving no double decrement.
4. Explain the transaction, row locks and rollback when any product lacks stock.
5. Explain price/cost snapshots and the limits of historical margin estimates.
6. Show real responsive screenshots and discuss remaining MySQL/scale validation.

Discuss tradeoffs candidly: shared sales workspace, advisory duplicate warning, one-line creation UI, CDN assets and no full accounting ledger. Preserve repository authorship and existing contributor attribution. The finalization branch is reviewed through a PR; do not merge automatically or deploy production.
