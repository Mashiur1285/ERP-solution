# Graph Report - ERP-solution  (2026-06-06)

## Corpus Check
- 267 files · ~140,147 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 1115 nodes · 1334 edges · 232 communities (201 shown, 31 thin omitted)
- Extraction: 100% EXTRACTED · 0% INFERRED · 0% AMBIGUOUS · INFERRED: 4 edges (avg confidence: 0.8)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `462c4078`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- [[_COMMUNITY_Community 0|Community 0]]
- [[_COMMUNITY_Community 1|Community 1]]
- [[_COMMUNITY_Community 2|Community 2]]
- [[_COMMUNITY_Community 3|Community 3]]
- [[_COMMUNITY_Community 4|Community 4]]
- [[_COMMUNITY_Community 5|Community 5]]
- [[_COMMUNITY_Community 6|Community 6]]
- [[_COMMUNITY_Community 7|Community 7]]
- [[_COMMUNITY_Community 8|Community 8]]
- [[_COMMUNITY_Community 9|Community 9]]
- [[_COMMUNITY_Community 10|Community 10]]
- [[_COMMUNITY_Community 11|Community 11]]
- [[_COMMUNITY_Community 12|Community 12]]
- [[_COMMUNITY_Community 13|Community 13]]
- [[_COMMUNITY_Community 14|Community 14]]
- [[_COMMUNITY_Community 15|Community 15]]
- [[_COMMUNITY_Community 16|Community 16]]
- [[_COMMUNITY_Community 17|Community 17]]
- [[_COMMUNITY_Community 18|Community 18]]
- [[_COMMUNITY_Community 19|Community 19]]
- [[_COMMUNITY_Community 20|Community 20]]
- [[_COMMUNITY_Community 21|Community 21]]
- [[_COMMUNITY_Community 22|Community 22]]
- [[_COMMUNITY_Community 23|Community 23]]
- [[_COMMUNITY_Community 24|Community 24]]
- [[_COMMUNITY_Community 25|Community 25]]
- [[_COMMUNITY_Community 26|Community 26]]
- [[_COMMUNITY_Community 27|Community 27]]
- [[_COMMUNITY_Community 28|Community 28]]
- [[_COMMUNITY_Community 29|Community 29]]
- [[_COMMUNITY_Community 30|Community 30]]
- [[_COMMUNITY_Community 31|Community 31]]
- [[_COMMUNITY_Community 32|Community 32]]
- [[_COMMUNITY_Community 33|Community 33]]
- [[_COMMUNITY_Community 34|Community 34]]
- [[_COMMUNITY_Community 35|Community 35]]
- [[_COMMUNITY_Community 36|Community 36]]
- [[_COMMUNITY_Community 37|Community 37]]
- [[_COMMUNITY_Community 38|Community 38]]
- [[_COMMUNITY_Community 39|Community 39]]
- [[_COMMUNITY_Community 40|Community 40]]
- [[_COMMUNITY_Community 41|Community 41]]
- [[_COMMUNITY_Community 42|Community 42]]
- [[_COMMUNITY_Community 43|Community 43]]
- [[_COMMUNITY_Community 44|Community 44]]
- [[_COMMUNITY_Community 45|Community 45]]
- [[_COMMUNITY_Community 46|Community 46]]
- [[_COMMUNITY_Community 47|Community 47]]
- [[_COMMUNITY_Community 48|Community 48]]
- [[_COMMUNITY_Community 49|Community 49]]
- [[_COMMUNITY_Community 50|Community 50]]
- [[_COMMUNITY_Community 51|Community 51]]
- [[_COMMUNITY_Community 52|Community 52]]
- [[_COMMUNITY_Community 53|Community 53]]
- [[_COMMUNITY_Community 54|Community 54]]
- [[_COMMUNITY_Community 55|Community 55]]
- [[_COMMUNITY_Community 56|Community 56]]
- [[_COMMUNITY_Community 57|Community 57]]
- [[_COMMUNITY_Community 58|Community 58]]
- [[_COMMUNITY_Community 59|Community 59]]
- [[_COMMUNITY_Community 60|Community 60]]
- [[_COMMUNITY_Community 61|Community 61]]
- [[_COMMUNITY_Community 62|Community 62]]
- [[_COMMUNITY_Community 63|Community 63]]
- [[_COMMUNITY_Community 65|Community 65]]
- [[_COMMUNITY_Community 66|Community 66]]
- [[_COMMUNITY_Community 67|Community 67]]
- [[_COMMUNITY_Community 68|Community 68]]
- [[_COMMUNITY_Community 69|Community 69]]
- [[_COMMUNITY_Community 70|Community 70]]
- [[_COMMUNITY_Community 71|Community 71]]
- [[_COMMUNITY_Community 72|Community 72]]
- [[_COMMUNITY_Community 73|Community 73]]
- [[_COMMUNITY_Community 74|Community 74]]
- [[_COMMUNITY_Community 75|Community 75]]
- [[_COMMUNITY_Community 76|Community 76]]
- [[_COMMUNITY_Community 77|Community 77]]
- [[_COMMUNITY_Community 78|Community 78]]
- [[_COMMUNITY_Community 79|Community 79]]
- [[_COMMUNITY_Community 80|Community 80]]
- [[_COMMUNITY_Community 81|Community 81]]
- [[_COMMUNITY_Community 82|Community 82]]
- [[_COMMUNITY_Community 83|Community 83]]
- [[_COMMUNITY_Community 84|Community 84]]
- [[_COMMUNITY_Community 85|Community 85]]
- [[_COMMUNITY_Community 86|Community 86]]
- [[_COMMUNITY_Community 87|Community 87]]
- [[_COMMUNITY_Community 88|Community 88]]
- [[_COMMUNITY_Community 89|Community 89]]
- [[_COMMUNITY_Community 90|Community 90]]
- [[_COMMUNITY_Community 91|Community 91]]
- [[_COMMUNITY_Community 92|Community 92]]
- [[_COMMUNITY_Community 93|Community 93]]
- [[_COMMUNITY_Community 94|Community 94]]
- [[_COMMUNITY_Community 126|Community 126]]
- [[_COMMUNITY_Community 127|Community 127]]
- [[_COMMUNITY_Community 130|Community 130]]
- [[_COMMUNITY_Community 131|Community 131]]
- [[_COMMUNITY_Community 132|Community 132]]
- [[_COMMUNITY_Community 134|Community 134]]
- [[_COMMUNITY_Community 135|Community 135]]
- [[_COMMUNITY_Community 136|Community 136]]
- [[_COMMUNITY_Community 138|Community 138]]

## God Nodes (most connected - your core abstractions)
1. `Controller` - 37 edges
2. `BaseRepository` - 20 edges
3. `TestCase` - 20 edges
4. `SalesController` - 17 edges
5. `LiftController` - 14 edges
6. `ProductPurchaseController` - 12 edges
7. `Request` - 12 edges
8. `BrandController` - 11 edges
9. `CategoryController` - 11 edges
10. `DepositController` - 11 edges

## Surprising Connections (you probably didn't know these)
- `RoleController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/Acl/RoleController.php → app/Http/Controllers/Controller.php
- `UserController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/Acl/UserController.php → app/Http/Controllers/Controller.php
- `AuthenticatedSessionController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/Auth/AuthenticatedSessionController.php → app/Http/Controllers/Controller.php
- `ConfirmablePasswordController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/Auth/ConfirmablePasswordController.php → app/Http/Controllers/Controller.php
- `EmailVerificationNotificationController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/Auth/EmailVerificationNotificationController.php → app/Http/Controllers/Controller.php

## Import Cycles
- None detected.

## Communities (232 total, 31 thin omitted)

### Community 0 - "Community 0"
Cohesion: 0.06
Nodes (12): AuthenticationTest, EmailVerificationTest, PasswordConfirmationTest, PasswordResetTest, PasswordUpdateTest, RegistrationTest, BaseTestCase, ExampleTest (+4 more)

### Community 1 - "Community 1"
Cohesion: 0.05
Nodes (31): isSidebarOpen, page, pageTitle, pageTitleMap, calculateDue(), dueAmount, formatCurrency(), isSubmitting (+23 more)

### Community 2 - "Community 2"
Cohesion: 0.08
Nodes (15): RoleController, UserController, User, Role, Role, RoleStoreRequest, RoleUpdateRequest, Seeder (+7 more)

### Community 3 - "Community 3"
Cohesion: 0.07
Nodes (28): dependencies, chart.js, flowbite, @fortawesome/fontawesome-svg-core, @fortawesome/free-solid-svg-icons, @fortawesome/vue-fontawesome, @inertiajs/inertia, @inertiajs/vue3 (+20 more)

### Community 4 - "Community 4"
Cohesion: 0.11
Nodes (10): Request, BelongsTo, Collection, Model, Product, ProductPurchaseController, Lift, LiftContract (+2 more)

### Community 5 - "Community 5"
Cohesion: 0.08
Nodes (25): For /graphify add and --watch, For /graphify query, For the commit hook and native CLAUDE.md integration, For --update and --cluster-only, /graphify, Honesty Rules, Interpreter guard for subcommands, Part A - Structural extraction for code files (+17 more)

### Community 6 - "Community 6"
Cohesion: 0.09
Nodes (13): Request, Request, Builder, Model, ExpenseController, ProfitLossController, Expense, ExpenseContract (+5 more)

### Community 7 - "Community 7"
Cohesion: 0.23
Nodes (18): addItem(), calculateFromCases(), emit, formatNumber(), getCalculatedPricePerBottle(), getCalculatedTotalBottles(), getEffectiveBottlesPerCase(), getEffectiveSellingPricePerCase() (+10 more)

### Community 8 - "Community 8"
Cohesion: 0.24
Nodes (3): Request, SupplierController, StoreSupplierRequest

### Community 10 - "Community 10"
Cohesion: 0.23
Nodes (15): getProduct(), getVariant(), liftPayload(), makeCatalog(), makeShop(), makeSupplier(), makeUser(), salePayload() (+7 more)

### Community 11 - "Community 11"
Cohesion: 0.15
Nodes (13): activePreset, computeRange(), displayRange, endLabel, label, lang, localEnd, localStart (+5 more)

### Community 12 - "Community 12"
Cohesion: 0.21
Nodes (5): Collection, Deposit, Model, DepositContract, DepositRepository

### Community 13 - "Community 13"
Cohesion: 0.22
Nodes (4): Request, Shop, ShopController, StoreShopRequest

### Community 14 - "Community 14"
Cohesion: 0.26
Nodes (5): Builder, Collection, Model, BaseContract, BaseRepository

### Community 15 - "Community 15"
Cohesion: 0.27
Nodes (4): Collection, Model, Product, ProductPurchaseRepository

### Community 16 - "Community 16"
Cohesion: 0.12
Nodes (4): Request, Request, DepositController, LiftController

### Community 17 - "Community 17"
Cohesion: 0.32
Nodes (3): AppServiceProvider, RepositoryServiceProvider, ServiceProvider

### Community 18 - "Community 18"
Cohesion: 0.26
Nodes (3): Brand, BrandController, StoreBrandRequest

### Community 20 - "Community 20"
Cohesion: 0.17
Nodes (7): filteredLifts, printedAtLabel, totalAmount, totalBottles, totalCases, totalItems, totalLifts

### Community 21 - "Community 21"
Cohesion: 0.24
Nodes (6): RedirectResponse, VerifyEmailController, Controller, PaymentController, SaleItemController, EmailVerificationRequest

### Community 22 - "Community 22"
Cohesion: 0.18
Nodes (11): require-dev, fakerphp/faker, laravel/breeze, laravel/pail, laravel/pint, laravel/sail, mockery/mockery, nunomaduro/collision (+3 more)

### Community 23 - "Community 23"
Cohesion: 0.25
Nodes (5): Model, Brand, Category, Shop, Supplier

### Community 24 - "Community 24"
Cohesion: 0.29
Nodes (7): Collection, Deposit, Model, depositHistory(), findTheLatestDepositForTheSupplier(), totalRemainingDepositsBySupplier(), updateDepositTable()

### Community 25 - "Community 25"
Cohesion: 0.33
Nodes (8): Collection, Model, getInventoryStock(), getLowStockProducts(), getProductsBySupplier(), getTopSellingProducts(), purchaseHistory(), updateInventory()

### Community 26 - "Community 26"
Cohesion: 0.20
Nodes (8): aclMenu, expenseMenu, inventoryMenu, purchaseMenu, reportsMenu, salesMenu, sidebarClasses, suppliersMenu

### Community 27 - "Community 27"
Cohesion: 0.36
Nodes (5): RedirectResponse, Request, Response, AuthenticatedSessionController, LoginRequest

### Community 28 - "Community 28"
Cohesion: 0.39
Nodes (4): Collection, ProductCatalog, ProductCatalogContract, ProductCatalogRepository

### Community 29 - "Community 29"
Cohesion: 0.39
Nodes (5): RedirectResponse, Request, Response, ProfileController, ProfileUpdateRequest

### Community 30 - "Community 30"
Cohesion: 0.36
Nodes (3): BelongsTo, HasMany, ProductCatalog

### Community 31 - "Community 31"
Cohesion: 0.22
Nodes (9): require, inertiajs/inertia-laravel, laravel/framework, laravel/sanctum, laravel/tinker, php, spatie/laravel-package-tools, spatie/laravel-permission (+1 more)

### Community 32 - "Community 32"
Cohesion: 0.22
Nodes (7): colorMap, iconClasses, isActive, linkClasses, page, props, scheme

### Community 33 - "Community 33"
Cohesion: 0.39
Nodes (6): Collection, Model, all(), create(), find(), update()

### Community 34 - "Community 34"
Cohesion: 0.43
Nodes (3): BelongsTo, Product, SoftDeletes

### Community 35 - "Community 35"
Cohesion: 0.33
Nodes (3): Request, DashboardController, ProductPurchaseContract

### Community 36 - "Community 36"
Cohesion: 0.38
Nodes (3): Shop, ShopRepository, ShopContract

### Community 37 - "Community 37"
Cohesion: 0.39
Nodes (6): Authenticatable, HasFactory, HasRoles, Expense, User, Notifiable

### Community 38 - "Community 38"
Cohesion: 0.25
Nodes (7): description, keywords, license, name, prefer-stable, $schema, type

### Community 39 - "Community 39"
Cohesion: 0.32
Nodes (3): FormRequest, ProfileUpdateRequest, StoreBrandRequest

### Community 40 - "Community 40"
Cohesion: 0.25
Nodes (3): form, form, props

### Community 41 - "Community 41"
Cohesion: 0.47
Nodes (3): SalesItemRepository, SaleItem, SalesItemContract

### Community 42 - "Community 42"
Cohesion: 0.25
Nodes (7): graphify reference: extra exports and benchmark, Step 6b - Wiki (only if --wiki flag), Step 7 - Neo4j export (only if --neo4j or --neo4j-push flag), Step 7b - SVG export (only if --svg flag), Step 7c - GraphML export (only if --graphml flag), Step 7d - MCP server (only if --mcp flag), Step 8 - Token reduction benchmark (only if total_words > 5000)

### Community 43 - "Community 43"
Cohesion: 0.43
Nodes (4): RedirectResponse, Request, Response, ConfirmablePasswordController

### Community 44 - "Community 44"
Cohesion: 0.48
Nodes (4): RedirectResponse, Request, Response, NewPasswordController

### Community 45 - "Community 45"
Cohesion: 0.43
Nodes (4): RedirectResponse, Request, Response, PasswordResetLinkController

### Community 46 - "Community 46"
Cohesion: 0.43
Nodes (4): RedirectResponse, Request, Response, RegisteredUserController

### Community 47 - "Community 47"
Cohesion: 0.43
Nodes (3): Request, Middleware, HandleInertiaRequests

### Community 48 - "Community 48"
Cohesion: 0.43
Nodes (3): BelongsTo, HasMany, Sale

### Community 49 - "Community 49"
Cohesion: 0.60
Nodes (3): RedirectResponse, Request, PasswordController

### Community 51 - "Community 51"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

### Community 52 - "Community 52"
Cohesion: 0.29
Nodes (6): compilerOptions, baseUrl, paths, exclude, @/*, ziggy-js

### Community 53 - "Community 53"
Cohesion: 0.53
Nodes (4): RedirectResponse, Request, Response, EmailVerificationPromptController

### Community 54 - "Community 54"
Cohesion: 0.47
Nodes (3): BelongsTo, HasMany, Lift

### Community 56 - "Community 56"
Cohesion: 0.33
Nodes (6): scripts, dev, post-autoload-dump, post-create-project-cmd, post-root-package-install, post-update-cmd

### Community 57 - "Community 57"
Cohesion: 0.47
Nodes (3): UserFactory, Factory, static

### Community 58 - "Community 58"
Cohesion: 0.60
Nodes (4): Collection, Model, liftHistory(), saveLiftWithItems()

### Community 59 - "Community 59"
Cohesion: 0.60
Nodes (3): RedirectResponse, Request, EmailVerificationNotificationController

### Community 60 - "Community 60"
Cohesion: 0.60
Nodes (3): Brand, BrandContract, BrandRepository

### Community 62 - "Community 62"
Cohesion: 0.60
Nodes (3): Supplier, SupplierRepository, SupplierContract

### Community 63 - "Community 63"
Cohesion: 0.60
Nodes (3): Category, CategoryContract, CategoryRepository

### Community 65 - "Community 65"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 69 - "Community 69"
Cohesion: 0.50
Nodes (4): emit, form, isSubmitting, submitShop()

### Community 74 - "Community 74"
Cohesion: 0.83
Nodes (3): Collection, getBySupplier(), searchBySupplier()

### Community 76 - "Community 76"
Cohesion: 0.50
Nodes (3): alignmentClasses, open, widthClass

### Community 77 - "Community 77"
Cohesion: 0.50
Nodes (3): alignmentClasses, open, widthClass

### Community 78 - "Community 78"
Cohesion: 0.50
Nodes (3): fs, glob, path

### Community 79 - "Community 79"
Cohesion: 0.50
Nodes (3): ERP-solution, Features, Shop Management:

### Community 80 - "Community 80"
Cohesion: 0.50
Nodes (3): For /graphify add, For --watch, graphify reference: add a URL and watch a folder

### Community 81 - "Community 81"
Cohesion: 0.50
Nodes (3): For git commit hook, For native CLAUDE.md integration, graphify reference: commit hook and native CLAUDE.md integration

### Community 82 - "Community 82"
Cohesion: 0.50
Nodes (3): For /graphify explain, For /graphify path, graphify reference: query, path, explain

### Community 83 - "Community 83"
Cohesion: 0.50
Nodes (3): For --cluster-only, For --update (incremental re-extraction), graphify reference: incremental update and cluster-only

### Community 93 - "Community 93"
Cohesion: 0.67
Nodes (3): autoload-dev, psr-4, Tests\\

### Community 94 - "Community 94"
Cohesion: 0.67
Nodes (3): extra, laravel, dont-discover

## Knowledge Gaps
- **191 isolated node(s):** `PreToolUse`, `$schema`, `name`, `type`, `description` (+186 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **31 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Controller` connect `Community 21` to `Community 2`, `Community 4`, `Community 6`, `Community 8`, `Community 9`, `Community 13`, `Community 16`, `Community 18`, `Community 19`, `Community 27`, `Community 29`, `Community 35`, `Community 43`, `Community 44`, `Community 45`, `Community 46`, `Community 49`, `Community 53`, `Community 59`?**
  _High betweenness centrality (0.090) - this node is a cross-community bridge._
- **Why does `LiftItem` connect `Community 4` to `Community 23`?**
  _High betweenness centrality (0.035) - this node is a cross-community bridge._
- **Why does `ProductPurchaseController` connect `Community 4` to `Community 21`?**
  _High betweenness centrality (0.034) - this node is a cross-community bridge._
- **What connects `PreToolUse`, `$schema`, `name` to the rest of the system?**
  _191 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `Community 0` be split into smaller, more focused modules?**
  _Cohesion score 0.06290471785383904 - nodes in this community are weakly interconnected._
- **Should `Community 1` be split into smaller, more focused modules?**
  _Cohesion score 0.04625346901017576 - nodes in this community are weakly interconnected._
- **Should `Community 2` be split into smaller, more focused modules?**
  _Cohesion score 0.07957957957957958 - nodes in this community are weakly interconnected._