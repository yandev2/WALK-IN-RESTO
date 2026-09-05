# Graph Report - WALK-IN-RESTO  (2026-09-05)

## Corpus Check
- 635 files · ~305,924 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 8576 nodes · 26403 edges · 357 communities (310 shown, 47 thin omitted)
- Extraction: 91% EXTRACTED · 9% INFERRED · 0% AMBIGUOUS · INFERRED: 2456 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `d60082ac`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- stat/chart.js
- components/chart.js
- code-editor.js
- rich-editor.js
- Illuminate\Database\Eloquent\Model
- y
- TestCase
- constructor
- Illuminate\Http\Request
- Illuminate\Http\JsonResponse
- r
- slice
- User
- console.php
- _update
- DiningTable
- ManageOutlet
- Order
- get
- Filament\Schemas\Schema
- advance
- nodeAt
- RestaurantDirectoryTest
- KitchenDisplay
- child
- support.js
- n
- P
- facet
- columns/select.js
- .slice
- eq
- echo.js
- create
- OrderResource
- fromObject
- GuestContext
- ManagePlatformPages
- W
- O
- constructor
- updateElements
- Ye
- Illuminate\Foundation\Http\FormRequest
- Illuminate\Http\Resources\Json\JsonResource
- notifications.js
- markdown-editor.js
- Restaurant
- te
- Cn
- components/select.js
- tables.js
- reduce
- r
- o
- SubscriptionStatus
- Xt
- 2026_08_20_010000_add_floor_layout_to_tables_table.php
- filament-right-click.js
- replace
- next
- Si
- RendersAnalyticsDashboard.php
- ae
- selectOption
- sliceDoc
- CreateCashierOrder
- ir
- ce
- slider.js
- addCommands
- selectOption
- fn
- i
- InvoicePaymentTest
- Dashboard
- SubscriptionPlan
- file-upload.js
- addEventListener
- ManageLandingLayout
- RestaurantDirectory
- FonnteErrorMessage
- toString
- AdminPanelProvider.php
- CashierMenuCatalog
- devDependencies
- filament/app.js
- g$
- fn
- selectRecords
- require
- scripts
- static
- color-picker.js
- js/app.js
- LandingLayout
- composer.json
- parse
- order-today-stats-widget.blade.php
- GeoDistance
- date-time-picker.js
- 🚀 3. Langkah-Langkah Menambahkan Template Baru (*Workflow*)
- RestaurantDirectory
- Mt
- Login
- _update
- actions/actions.js
- Vf
- schemas.js
- Landasan Produksi — Tahap 1 (Walk-In Operasional)
- Guest
- E
- 5. Keuntungan dari fitur — bahasa owner, bukan bahasa sistem
- _each
- require-dev
- getContext
- 6. Katalog fitur
- filament-shield.php
- config
- 6. Katalog fitur
- EditProfile
- register-restaurant.blade.php
- add-to-cart-modal.blade.php
- _notify
- components/actions.js
- psr-4
- extra
- logging.php
- RegisterRestaurant
- getDatasetMeta
- cart.blade.php
- 2026_08_19_040000_add_soft_deletes_to_core_tables.php
- customer.blade.php
- landing/show.blade.php
- database-notifications.blade.php
- guest-order.blade.php
- landing.blade.php
- revenue-bar.blade.php
- sidebar.blade.php
- guest/menu.blade.php
- search-bar.blade.php
- activitylog.php
- st
- closeSimpleModeModal
- Illuminate\Database\Schema\Blueprint
- cc
- Illuminate\Database\Migrations\Migration
- RefreshesAnalyticsChart.php
- SubscriptionWriteGuard
- ExcelExporter
- dx
- Illuminate\Support\Facades\Schema
- ExportFile
- kpi.blade.php
- configure
- pay.blade.php
- order-panel.blade.php
- period-summary.blade.php
- top-menu.blade.php
- need-scan.blade.php
- directory.blade.php
- review.blade.php
- restaurant-menu-catalog.blade.php
- Bityukov\CommandCenter\Sources\ConfigSource
- create
- filament.pages.partials.subscription-transfer-widget
- filament.widgets.partials.sales-summary-icon
- mountTableAction(
- openPicker({{ $item->id }})
- partials.customer.guest-nav
- resetFilters
- map-panel.blade.php
- subscription-plan-picker.blade.php
- checkout.blade.php
- status.blade.php
- restaurant-directory.blade.php
- 3. Detail Implementasi Perbaikan Keamanan
- foodie/show.blade.php
- Y
- classic/show.blade.php
- WelcomeBannerWidget
- rules/graphify.md
- workflows/graphify.md
- S
- MenuItem
- Illuminate\Database\Eloquent\Builder
- draw
- A
- glassmorphism/show.blade.php
- CashierOrderPreview
- CashierFilamentActionsTest
- addSingleBadge
- fn
- SubscriptionInvoice
- glassmorphism-background.blade.php
- OrderReceiptPrintController
- s
- resolve
- SitemapController.php
- st
- N
- dropdown.blade.php
- Ae

## God Nodes (most connected - your core abstractions)
1. `Restaurant` - 270 edges
2. `User` - 252 edges
3. `constructor()` - 152 edges
4. `TestCase` - 149 edges
5. `update()` - 148 edges
6. `Order` - 110 edges
7. `MenuItem` - 101 edges
8. `resolve()` - 94 edges
9. `y()` - 93 edges
10. `_update()` - 87 edges

## Surprising Connections (you probably didn't know these)
- `up()` --calls--> `DiningTable`  [EXTRACTED]
  database/migrations/2026_08_20_010000_add_floor_layout_to_tables_table.php → app/Models/DiningTable.php
- `createGuestRestaurant()` --calls--> `DiningTable`  [EXTRACTED]
  tests/Concerns/CreatesGuestRestaurant.php → app/Models/DiningTable.php
- `createGuestRestaurant()` --calls--> `MenuItem`  [EXTRACTED]
  tests/Concerns/CreatesGuestRestaurant.php → app/Models/MenuItem.php
- `extraMenuItem()` --calls--> `MenuItem`  [EXTRACTED]
  tests/Concerns/CreatesGuestRestaurant.php → app/Models/MenuItem.php
- `paidGuestOrder()` --calls--> `Order`  [EXTRACTED]
  tests/Concerns/CreatesGuestRestaurant.php → app/Models/Order.php

## Import Cycles
- None detected.

## Communities (357 total, 47 thin omitted)

### Community 0 - "stat/chart.js"
Cohesion: 0.02
Nodes (103): alpha(), applyStack(), ar(), ba(), be(), beforeDatasetsDraw(), beforeDraw(), Bn() (+95 more)

### Community 1 - "components/chart.js"
Cohesion: 0.01
Nodes (140): abutsStart(), addControllers(), addPlugins(), addScales(), alpha(), bd(), Be(), bm() (+132 more)

### Community 2 - "code-editor.js"
Cohesion: 0.01
Nodes (131): Ac(), addCompletion(), addCompletions(), addNamespace(), addNamespaceObject(), addSelection(), Ag(), b0() (+123 more)

### Community 3 - "rich-editor.js"
Cohesion: 0.01
Nodes (201): aa(), add(), addExtensions(), addGlobalAttributes(), addHackNode(), addTextblockHacks(), an(), applyAspectRatio() (+193 more)

### Community 4 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.02
Nodes (48): CmsBanner, LogOptions, CmsFaq, LogOptions, CmsGalleryImage, CmsProfile, LogOptions, restaurant() (+40 more)

### Community 5 - "y"
Cohesion: 0.18
Nodes (49): al(), at(), Be(), Cr(), de(), dt(), Ee(), ef() (+41 more)

### Community 6 - "TestCase"
Cohesion: 0.03
Nodes (62): CashierOrderService, BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles, Bityukov\CommandCenter\Filament\Pages\Commands, Bityukov\CommandCenter\Filament\Pages\History, DatabaseSeeder, RolePermissionSeeder, Filament\Facades\Filament, Illuminate\Database\QueryException (+54 more)

### Community 7 - "constructor"
Cohesion: 0.02
Nodes (142): add(), addChunk(), addEventListener(), addInfoPane(), addInner(), addWindowListeners(), adjust(), al() (+134 more)

### Community 8 - "Illuminate\Http\Request"
Cohesion: 0.12
Nodes (16): ApplyPlatformBrandTheme, ApplyRestaurantPanelTheme, EnsureApiGuestVisit, EnsureGuestVisit, EnsureRestaurantOperations, EnsureTenantSubscription, IdentifyApiGuestDevice, IdentifyGuestDevice (+8 more)

### Community 9 - "Illuminate\Http\JsonResponse"
Cohesion: 0.08
Nodes (17): CartController, CheckoutController, MenuController, OrderController, ReviewController, SessionController, VisitController, RestaurantController (+9 more)

### Community 10 - "r"
Cohesion: 0.05
Nodes (127): _0(), addNodeView(), addOptions(), addProseMirrorPlugins(), af(), au(), buildProps(), Cc() (+119 more)

### Community 11 - "slice"
Cohesion: 0.04
Nodes (133): a$(), activateHover(), addChanges(), addElement(), Ah(), AX(), b1(), balance() (+125 more)

### Community 12 - "User"
Cohesion: 0.02
Nodes (31): ViewActivity, AnalyticsKpiWidget, SetPermissionsTeamId, Activity, Role, LogOptions, User, RolePolicy (+23 more)

### Community 13 - "console.php"
Cohesion: 0.24
Nodes (6): GenerateUpcomingInvoicesCommand, ProcessSubscriptionLifecycleCommand, Illuminate\Console\Command, Illuminate\Foundation\Inspiring, Illuminate\Support\Facades\Artisan, Illuminate\Support\Facades\Schedule

### Community 14 - "_update"
Cohesion: 0.04
Nodes (106): addBox(), addElements(), adjustHitBoxes(), afterBuildTicks(), afterCalculateLabelRotation(), afterDataLimits(), afterDraw(), afterFit() (+98 more)

### Community 15 - "DiningTable"
Cohesion: 0.03
Nodes (25): ExpireStaleOperationsCommand, TableController, DiningTable, LogOptions, Payment, Visit, GuestCheckoutService, OrderPaymentService (+17 more)

### Community 17 - "Order"
Cohesion: 0.03
Nodes (31): analyticsDateFrom(), analyticsDateTo(), analyticsDayCount(), analyticsRangeLabel(), analyticsSnapshot(), normalizedAnalyticsDateRange(), Carbon, OrderReceiptDownloadController (+23 more)

### Community 18 - "get"
Cohesion: 0.04
Nodes (87): addBlockWidget(), addBreak(), addComposition(), addDelimiter(), addInlineWidget(), addLine(), addLineStart(), addLineStartIfNotCovered() (+79 more)

### Community 19 - "Filament\Schemas\Schema"
Cohesion: 0.02
Nodes (118): canCreate(), canEdit(), canViewAny(), Action, trashPageAction(), ViewSubscriptionInvoice, SoftDeleteTrashPage, ActivityResource (+110 more)

### Community 20 - "advance"
Cohesion: 0.05
Nodes (62): addChild(), addGaps(), addLeafElement(), addNode(), advance(), ATXHeading(), blank(), break() (+54 more)

### Community 21 - "nodeAt"
Cohesion: 0.11
Nodes (50): AS(), cellsInRect(), co(), colCount(), content(), createAndFill(), ct(), dS() (+42 more)

### Community 23 - "KitchenDisplay"
Cohesion: 0.13
Nodes (5): KitchenDisplay, BackedEnum, UnitEnum, Width, Filament\Resources\Concerns\HasTabs

### Community 24 - "child"
Cohesion: 0.10
Nodes (33): addInner(), Bm(), child(), dg(), eat(), err(), ew(), findIndex() (+25 more)

### Community 25 - "support.js"
Cohesion: 0.05
Nodes (75): acquireScrollLock(), ai(), e(), Bi(), br(), Bt(), ca(), close() (+67 more)

### Community 26 - "n"
Cohesion: 0.09
Nodes (79): _a(), Ae(), ar(), as(), bc(), ee(), ue(), u() (+71 more)

### Community 27 - "P"
Cohesion: 0.12
Nodes (26): addInputRules(), addMark(), addPasteRules(), Ah(), Ax(), dispatchTransaction(), ea(), Eh() (+18 more)

### Community 28 - "facet"
Cohesion: 0.04
Nodes (88): accept(), active(), applyTransaction(), asSingle(), B(), baseTheme(), between(), blur() (+80 more)

### Community 29 - "columns/select.js"
Cohesion: 0.06
Nodes (57): A(), An(), applyDisabledState(), b(), Bt(), Ce(), D(), disable() (+49 more)

### Community 30 - ".slice"
Cohesion: 0.06
Nodes (54): accepts(), addMaps(), addStep(), addTransform(), appendMap(), appendMapping(), appendMappingInverted(), apply() (+46 more)

### Community 31 - "eq"
Cohesion: 0.07
Nodes (40): addNode(), ao(), append(), destroyBetween(), destroyRest(), dragend(), dragleave(), dragover() (+32 more)

### Community 32 - "echo.js"
Cohesion: 0.05
Nodes (49): a(), ar(), b(), Be(), Ce(), cr(), d(), De() (+41 more)

### Community 33 - "create"
Cohesion: 0.04
Nodes (118): Ac(), addAll(), addDOM(), addElement(), addElementByRule(), addNodeMark(), addTextNode(), addToSet() (+110 more)

### Community 34 - "OrderResource"
Cohesion: 0.05
Nodes (8): OrderResource, ListOrders, ViewOrder, OrderTodayStatsWidget, CashTender, IdrAmount, SoftDeleteTrashTest, IdrAmountTest

### Community 35 - "fromObject"
Cohesion: 0.03
Nodes (109): El(), ac(), ae(), after(), Al(), Am(), before(), bl() (+101 more)

### Community 36 - "GuestContext"
Cohesion: 0.07
Nodes (15): ExportFileDownloadController, GuestCart, GuestCheckout, GuestPay, GuestReview, GuestStatus, ScanTable, VisitDevice (+7 more)

### Community 37 - "ManagePlatformPages"
Cohesion: 0.11
Nodes (6): ManageHomeLanding, BackedEnum, UnitEnum, ManagePlatformPages, BackedEnum, UnitEnum

### Community 38 - "W"
Cohesion: 0.05
Nodes (79): AQ(), atLastNode(), au(), child(), childAfter(), childBefore(), continue(), cursor() (+71 more)

### Community 39 - "O"
Cohesion: 0.19
Nodes (38): b(), $c(), X(), ca(), me(), D(), _e(), Ea() (+30 more)

### Community 40 - "constructor"
Cohesion: 0.03
Nodes (85): Bc(), bg(), chartOptionScopes(), Cl(), clone(), constructor(), create(), Ct() (+77 more)

### Community 41 - "updateElements"
Cohesion: 0.03
Nodes (113): aa(), acquireContext(), afterAutoSkip(), Ao(), aspectRatio(), bh(), bu(), buildLookupTable() (+105 more)

### Community 42 - "Ye"
Cohesion: 0.10
Nodes (41): Rd(), $a(), ak(), at(), bk(), c(), bp(), Dk() (+33 more)

### Community 43 - "Illuminate\Foundation\Http\FormRequest"
Cohesion: 0.08
Nodes (8): AddCartItemRequest, CheckoutRequest, ClaimTableRequest, JoinTableRequest, StorePaymentProofRequest, StoreRestaurantReviewRequest, UpdateCartItemRequest, Illuminate\Foundation\Http\FormRequest

### Community 44 - "Illuminate\Http\Resources\Json\JsonResource"
Cohesion: 0.08
Nodes (12): CartItemResource, CartResource, MenuCategoryResource, MenuVariantResource, ModifierGroupResource, ModifierResource, OrderItemResource, OrderResource (+4 more)

### Community 45 - "notifications.js"
Cohesion: 0.06
Nodes (31): actions(), button(), c(), close(), configureAnimations(), configureTransitions(), constructor(), danger() (+23 more)

### Community 46 - "markdown-editor.js"
Cohesion: 0.05
Nodes (83): ad(), af(), An(), bf(), bo(), Bt(), cd(), Ct() (+75 more)

### Community 47 - "Restaurant"
Cohesion: 0.02
Nodes (38): ManageBillingAccount, BackedEnum, UnitEnum, RestaurantMenuController, PlatformPageController, RestaurantLandingController, MenuItemResource, PlatformSetting (+30 more)

### Community 48 - "te"
Cohesion: 0.05
Nodes (9): Bn(), br(), ji(), on(), qd(), Ri(), te(), Vi() (+1 more)

### Community 49 - "Cn"
Cohesion: 0.13
Nodes (46): Cn(), b(), Be(), Ce(), De(), dn(), _e(), Fe() (+38 more)

### Community 51 - "components/select.js"
Cohesion: 0.08
Nodes (38): A(), applyDisabledState(), b(), Bt(), D(), disable(), E(), en() (+30 more)

### Community 53 - "tables.js"
Cohesion: 0.13
Nodes (44): A(), ae(), B(), be(), C(), ce(), E(), ee() (+36 more)

### Community 54 - "reduce"
Cohesion: 0.06
Nodes (62): addActions(), advanceFully(), advanceStack(), allActions(), apply(), c0(), canShift(), checkAsyncSchedule() (+54 more)

### Community 55 - "r"
Cohesion: 0.15
Nodes (43): _a(), ar(), c(), f(), d(), di(), g(), Hi() (+35 more)

### Community 56 - "o"
Cohesion: 0.04
Nodes (121): ag(), ah(), apply(), ar(), au(), average(), Ba(), beforeDatasetDraw() (+113 more)

### Community 57 - "SubscriptionStatus"
Cohesion: 0.15
Nodes (3): BackedEnum, UnitEnum, SubscriptionStatus

### Community 58 - "Xt"
Cohesion: 0.12
Nodes (44): ae(), At(), bi(), bn(), ci(), cn(), ct(), de() (+36 more)

### Community 60 - "filament-right-click.js"
Cohesion: 0.11
Nodes (42): a(), ae(), b(), be(), C(), ce(), D(), de() (+34 more)

### Community 61 - "replace"
Cohesion: 0.07
Nodes (36): addToSet(), childString(), decompose(), decomposeLeft(), decomposeRight(), flushIOSKey(), FO(), getReplacement() (+28 more)

### Community 62 - "next"
Cohesion: 0.08
Nodes (33): activeForPoint(), addActive(), addBlock(), addLineDeco(), Ar(), as(), blankContent(), boundChange() (+25 more)

### Community 63 - "Si"
Cohesion: 0.13
Nodes (41): ae(), At(), bi(), bn(), ci(), cn(), ct(), de() (+33 more)

### Community 64 - "RendersAnalyticsDashboard.php"
Cohesion: 0.12
Nodes (11): AnalyticsPeriodSummaryWidget, AnalyticsRevenueBarWidget, AnalyticsSidebarWidget, AnalyticsTopMenuWidget, analyticsTheme(), formatKpiDelta(), makeKpiCard(), paymentMixSummary() (+3 more)

### Community 65 - "ae"
Cohesion: 0.09
Nodes (34): ae(), Ao(), as(), B(), Kt(), cs(), Ee(), Ge() (+26 more)

### Community 66 - "selectOption"
Cohesion: 0.12
Nodes (39): addBadgesForSelectedOptions(), addSingleBadge(), addSingleSelectionDisplay(), closeDropdown(), constructor(), createBadgeElement(), createOptionElement(), createRemoveButton() (+31 more)

### Community 67 - "sliceDoc"
Cohesion: 0.15
Nodes (19): aO(), charCategorizer(), Fc(), flatten(), getCursor(), getDeco(), gT(), highlight() (+11 more)

### Community 68 - "CreateCashierOrder"
Cohesion: 0.17
Nodes (4): CreateCashierOrder, BackedEnum, UnitEnum, Width

### Community 69 - "ir"
Cohesion: 0.13
Nodes (34): Ft(), ir(), ce(), de(), Dt(), ee(), Et(), fe() (+26 more)

### Community 70 - "ce"
Cohesion: 0.08
Nodes (46): Ac(), ao(), bl(), Cc(), ce(), cl(), Cn(), Dc() (+38 more)

### Community 71 - "slider.js"
Cohesion: 0.11
Nodes (33): ar(), Be(), Ce(), De(), _e(), Ee(), er(), et() (+25 more)

### Community 72 - "addCommands"
Cohesion: 0.13
Nodes (26): addCommands(), addStoredMark(), computeAttrs(), createChecked(), ensureMarks(), handleExit(), i1(), insertText() (+18 more)

### Community 73 - "selectOption"
Cohesion: 0.15
Nodes (33): addSingleSelectionDisplay(), closeDropdown(), constructor(), createOptionElement(), deferPositionDropdown(), destroy(), filterOptions(), focusNextOption() (+25 more)

### Community 74 - "fn"
Cohesion: 0.13
Nodes (33): aa(), At(), ba(), cr(), da(), de(), dt(), ei() (+25 more)

### Community 75 - "i"
Cohesion: 0.05
Nodes (80): aa(), applyChanges(), balanced(), baseIndent(), baseIndentFor(), Bg(), bidiSpans(), blockAt() (+72 more)

### Community 77 - "Dashboard"
Cohesion: 0.09
Nodes (5): Dashboard, GenerateReport, BackedEnum, UnitEnum, TenantIsolationTest

### Community 79 - "file-upload.js"
Cohesion: 0.05
Nodes (53): hc(), Bp(), c(), ca(), clickPercent(), constructor(), Cp(), da() (+45 more)

### Community 81 - "addEventListener"
Cohesion: 0.33
Nodes (7): addEventListener(), bindResponsiveEvents(), fu(), isAttached(), nr(), removeEventListener(), Ua()

### Community 82 - "ManageLandingLayout"
Cohesion: 0.14
Nodes (7): TemplateRadioPicker, ManageLandingLayout, BackedEnum, Closure, UnitEnum, Filament\Forms\Components\Field, Illuminate\Database\Eloquent\Collection

### Community 83 - "RestaurantDirectory"
Cohesion: 0.08
Nodes (4): RestaurantDirectory, RestaurantMenuCatalog, Livewire\Attributes\Computed, Livewire\WithPagination

### Community 84 - "FonnteErrorMessage"
Cohesion: 0.11
Nodes (8): FonnteClient, FonnteErrorMessage, Throwable, Illuminate\Http\Client\ConnectionException, Illuminate\Http\Client\Response, PHPUnit\Framework\Attributes\DataProvider, RuntimeException, FonnteErrorMessageTest

### Community 85 - "toString"
Cohesion: 0.13
Nodes (20): Bc(), check(), checkAttrs(), checkContent(), cn(), endIndex(), getObj(), hasProtocol() (+12 more)

### Community 89 - "AdminPanelProvider.php"
Cohesion: 0.11
Nodes (24): AdminPanelProvider, FounderPanelProvider, AuthGlass, BezhanSalleh\FilamentShield\FilamentShieldPlugin, Bityukov\CommandCenter\Filament\CommandCenterPlugin, Filament\Http\Middleware\Authenticate, Filament\Http\Middleware\AuthenticateSession, Filament\Http\Middleware\DisableBladeIconComponents (+16 more)

### Community 91 - "devDependencies"
Cohesion: 0.11
Nodes (18): axios, concurrently, laravel-vite-plugin, devDependencies, axios, concurrently, laravel-vite-plugin, tailwindcss (+10 more)

### Community 92 - "filament/app.js"
Cohesion: 0.13
Nodes (8): B(), close(), G(), init(), P(), setUpResizeObserver(), x(), Y()

### Community 93 - "g$"
Cohesion: 0.07
Nodes (45): acceptToken(), allows(), bd(), Bh(), clearDelayedAndroidKey(), d0(), De(), delayAndroidKey() (+37 more)

### Community 94 - "fn"
Cohesion: 0.22
Nodes (18): An(), Ce(), ei(), fn(), Ft(), Ie(), Le(), ni() (+10 more)

### Community 95 - "selectRecords"
Cohesion: 0.20
Nodes (18): areRecordsPartiallySelected(), areRecordsSelected(), areRecordsToggleable(), canSelectAllRecords(), deselectAllRecords(), deselectRecords(), getRecordsOnPage(), getSelectedRecordsCount() (+10 more)

### Community 96 - "require"
Cohesion: 0.12
Nodes (16): require, barryvdh/laravel-dompdf, bezhansalleh/filament-shield, endroid/qr-code, filament/filament, hammadzafar05/filament-mobile-preset, ipatco/filament-profile, laravel/framework (+8 more)

### Community 97 - "scripts"
Cohesion: 0.12
Nodes (16): scripts, dev, post-autoload-dump, post-create-project-cmd, post-root-package-install, post-update-cmd, Composer\\Config::disableProcessTimeout, Illuminate\\Foundation\\ComposerScripts::postAutoloadDump (+8 more)

### Community 98 - "static"
Cohesion: 0.02
Nodes (46): canDelete(), canDeleteAny(), canForceDelete(), canRestore(), FacilityResource, CreateFacility, EditFacility, ListFacilities (+38 more)

### Community 99 - "color-picker.js"
Cohesion: 0.14
Nodes (3): style(), update(), [x]()

### Community 100 - "js/app.js"
Cohesion: 0.22
Nodes (6): applyTheme(), bindThemeToggle(), initReveal(), initTheme(), syncThemeToggleIcons(), toggleTheme()

### Community 103 - "composer.json"
Cohesion: 0.14
Nodes (13): autoload-dev, psr-4, description, keywords, license, minimum-stability, name, prefer-stable (+5 more)

### Community 104 - "parse"
Cohesion: 0.06
Nodes (54): buildOrUpdateElements(), Cn(), determineDataLimits(), diff(), dn(), el(), En(), endOf() (+46 more)

### Community 106 - "GeoDistance"
Cohesion: 0.20
Nodes (4): GeoDistance, PHPUnit\Framework\TestCase, ExampleTest, GeoDistanceTest

### Community 107 - "date-time-picker.js"
Cohesion: 0.26
Nodes (8): d(), e(), i(), m(), r(), s(), t(), rr()

### Community 108 - "🚀 3. Langkah-Langkah Menambahkan Template Baru (*Workflow*)"
Cohesion: 0.12
Nodes (15): 📌 1. Prinsip Utama & Aturan Wajib (Golden Rules), 🏗️ 2. Arsitektur 10 Core Section CMS (1:1 Mapping), 🚀 3. Langkah-Langkah Menambahkan Template Baru (*Workflow*), 📊 4. Variabel yang Disediakan oleh `LandingPageDataService`, 💎 5. Pelajaran Desain & Best Practices UI/UX (*Key Learnings*), A. Larangan Keras Menggunakan Emoji Unicode (Gunakan SVG Heroicons), B. Arsitektur Layering 3D & Background Tembus Pandang (*Stacking Context*), C. Resep Glassmorphism iOS yang Nyata (*True Frosted Glass*) (+7 more)

### Community 109 - "RestaurantDirectory"
Cohesion: 0.22
Nodes (3): periodSummary(), RestaurantDirectory, Illuminate\Contracts\Pagination\LengthAwarePaginator

### Community 110 - "Mt"
Cohesion: 0.24
Nodes (11): apply(), fs(), go(), Hr(), T(), ir(), it(), Mt() (+3 more)

### Community 111 - "Login"
Cohesion: 0.25
Nodes (4): Login, Filament\Auth\Pages\Login, Filament\Schemas\Components\Component, Illuminate\Contracts\Support\Htmlable

### Community 113 - "_update"
Cohesion: 0.05
Nodes (63): active(), afterBuildTicks(), afterCalculateLabelRotation(), afterDataLimits(), afterDatasetsUpdate(), afterFit(), afterSetDimensions(), afterTickToLabelConversion() (+55 more)

### Community 115 - "actions/actions.js"
Cohesion: 0.44
Nodes (8): closeModal(), generateModalId(), getActionNestingIndexFromModalId(), init(), openModal(), rememberPreviouslyFocusedElement(), restorePreviouslyFocusedElement(), syncActionModals()

### Community 116 - "Vf"
Cohesion: 0.33
Nodes (7): contains(), gi(), splitAt(), toISOTime(), toMillis(), Vf(), ye()

### Community 118 - "Landasan Produksi — Tahap 1 (Walk-In Operasional)"
Cohesion: 0.05
Nodes (37): 10. Aturan yang tidak boleh dilanggar, 11. Matriks skenario lapangan, 12. Yang sengaja tidak didukung (bukan gap, non-goal), 13. Tahap 2 (bukan sekarang), 14. Urutan bangun (agar cepat produksi), 1. Keputusan terkunci, 2. Modul tahap 1 vs ditunda, 3. Model domain (+29 more)

### Community 119 - "Guest"
Cohesion: 0.05
Nodes (34): Bentuk objek, CartItemResource, DELETE `/guest/cart/items/{cartItem}`, GET `/guest/cart`, GET `/guest/menu`, GET `/guest/orders`, GET `/guest/orders/{public_id}`, GET `/guest/review` (+26 more)

### Community 120 - "E"
Cohesion: 0.05
Nodes (61): $a(), add(), af(), B(), bo(), bs(), ca(), _cachedScopes() (+53 more)

### Community 121 - "5. Keuntungan dari fitur — bahasa owner, bukan bahasa sistem"
Cohesion: 0.06
Nodes (34): 1. Cerita yang mungkin terasa familiar, 2.10 Owner tidak punya angka yang bisa dipercaya, 2.11 Tamu tanpa HP, atau minta tambah pesanan, 2.1 Pesanan salah, kurang, atau telat sampai dapur, 2.2 Kasir jadi bottleneck, 2.3 Transfer QRIS yang “mirip-mirip”, 2.4 Tamu “pesan tunai dari luar resto”, 2.5 Dapur dan bar rebutan kertas, tidak tahu mana yang lama (+26 more)

### Community 122 - "_each"
Cohesion: 0.12
Nodes (17): addControllers(), addPlugins(), addScales(), _each(), _exec(), _getRegistryForType(), invalidate(), isForType() (+9 more)

### Community 123 - "require-dev"
Cohesion: 0.25
Nodes (8): require-dev, fakerphp/faker, laravel/pail, laravel/pint, laravel/sail, mockery/mockery, nunomaduro/collision, phpunit/phpunit

### Community 124 - "getContext"
Cohesion: 0.07
Nodes (52): acquireContext(), Ao(), bl(), buildTicks(), Ca(), calculateLabelRotation(), _calculatePadding(), ci() (+44 more)

### Community 125 - "6. Katalog fitur"
Cohesion: 0.06
Nodes (33): 1. Latar belakang, 2. Rumusan masalah, 3.1 Tujuan umum, 3.2 Tujuan khusus, 3. Tujuan, 4.1 Bagi restoran (owner, kasir, dapur), 4.2 Bagi tamu, 4.3 Bagi pengelola platform (+25 more)

### Community 126 - "filament-shield.php"
Cohesion: 0.29
Nodes (5): Dashboard, BezhanSalleh\FilamentShield\Resources\Roles\RoleResource, Filament\Pages\Dashboard, Filament\Widgets\AccountWidget, Filament\Widgets\FilamentInfoWidget

### Community 127 - "config"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

### Community 128 - "6. Katalog fitur"
Cohesion: 0.06
Nodes (32): 1. Latar belakang, 2. Rumusan masalah, 3.1 Tujuan umum, 3.2 Tujuan khusus, 3. Tujuan, 4.1 Bagi restoran (owner, kasir, dapur), 4.2 Bagi tamu, 4.3 Bagi pengelola platform (+24 more)

### Community 129 - "EditProfile"
Cohesion: 0.09
Nodes (8): EditProfile, FilamentProfilePlugin, ProfileInformationForm, Ipatco\FilamentProfile\FilamentProfilePlugin, Ipatco\FilamentProfile\Forms\ProfileInformationForm, Ipatco\FilamentProfile\Pages\EditProfile, Ipatco\FilamentProfile\Widgets\AccountWidget, ProfilePageTest

### Community 130 - "register-restaurant.blade.php"
Cohesion: 0.15
Nodes (12): applyColorPreset(, back, nextFromAccount, nextFromPlan, nextFromRestaurant, nextFromVisual, register, $set( (+4 more)

### Community 131 - "add-to-cart-modal.blade.php"
Cohesion: 0.29
Nodes (6): cancelPicking, confirmAdd, decrementPickingQty, incrementPickingQty, setVariant({{ $variant->id }}), toggleModifier({{ $modifier->id }})

### Community 132 - "_notify"
Cohesion: 0.20
Nodes (14): active(), _animateOptions(), cancel(), _createAnimations(), _createDescriptors(), _descriptors(), _notify(), _notifyStateChanges() (+6 more)

### Community 134 - "psr-4"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 135 - "extra"
Cohesion: 0.40
Nodes (5): dev-master, extra, branch-alias, laravel, dont-discover

### Community 136 - "logging.php"
Cohesion: 0.40
Nodes (4): Monolog\Handler\NullHandler, Monolog\Handler\StreamHandler, Monolog\Handler\SyslogUdpHandler, Monolog\Processor\PsrLogMessageProcessor

### Community 139 - "getDatasetMeta"
Cohesion: 0.11
Nodes (26): afterDatasetsUpdate(), An(), generateLabels(), getDatasetMeta(), getDataVisibility(), _getLegendItemAt(), getMaxBorderWidth(), _getSortedDatasetMetas() (+18 more)

### Community 140 - "cart.blade.php"
Cohesion: 0.40
Nodes (4): remove({{ $item->id }}), guest.partials.nav, minus({{ $item->id }}), plus({{ $item->id }})

### Community 141 - "2026_08_19_040000_add_soft_deletes_to_core_tables.php"
Cohesion: 0.83
Nodes (3): down(), tables(), up()

### Community 142 - "customer.blade.php"
Cohesion: 0.50
Nodes (3): gotoPage({{ $page }}, , nextPage(, previousPage(

### Community 143 - "landing/show.blade.php"
Cohesion: 0.50
Nodes (3): landing.sections., partials.customer.landing-footer, partials.customer.landing-header

### Community 144 - "database-notifications.blade.php"
Cohesion: 0.50
Nodes (3): mountAction(, setTab(, toggleNotificationReadStatus(

### Community 145 - "guest-order.blade.php"
Cohesion: 0.50
Nodes (3): partials.customer.guest-header, partials.customer.theme-init, partials.customer.theme-vars

### Community 146 - "landing.blade.php"
Cohesion: 0.50
Nodes (3): partials.customer.image-preview-alpine, partials.customer.theme-init, partials.customer.theme-vars

### Community 148 - "revenue-bar.blade.php"
Cohesion: 0.50
Nodes (3): filament.widgets.analytics._chart-canvas, filament.widgets.analytics._section-header, filament.widgets.analytics._styles

### Community 149 - "sidebar.blade.php"
Cohesion: 0.50
Nodes (3): filament.widgets.analytics._chart-canvas, filament.widgets.analytics._section-header, filament.widgets.analytics._styles

### Community 150 - "guest/menu.blade.php"
Cohesion: 0.50
Nodes (3): guest.partials.nav, setCategory({{ $category->id }}), setCategory(null)

### Community 153 - "st"
Cohesion: 0.05
Nodes (48): ad(), applyStack(), br(), Di(), drawCaret(), _f(), first(), getCaretPosition() (+40 more)

### Community 158 - "cc"
Cohesion: 0.22
Nodes (11): attrs(), bi(), cc(), cO(), JQ(), m$(), Ow(), rc() (+3 more)

### Community 163 - "RefreshesAnalyticsChart.php"
Cohesion: 0.36
Nodes (8): generateChartDataChecksum(), getCachedChartData(), getChartData(), mountRefreshesAnalyticsChart(), refreshAnalyticsChartData(), rendering(), updateChartData(), Livewire\Attributes\Locked

### Community 164 - "SubscriptionWriteGuard"
Cohesion: 0.28
Nodes (3): BlockGraceMutations, SubscriptionWriteGuard, Livewire\ComponentHook

### Community 176 - "ExcelExporter"
Cohesion: 0.39
Nodes (4): ExcelExporter, Illuminate\Contracts\View\View, Maatwebsite\Excel\Concerns\FromView, Maatwebsite\Excel\Concerns\ShouldAutoSize

### Community 180 - "dx"
Cohesion: 0.09
Nodes (38): Ei(), Aa(), ai(), Ba(), Bi(), cf(), da(), fa() (+30 more)

### Community 183 - "ExportFile"
Cohesion: 0.03
Nodes (37): PdfExporter, CleanupOldExportFilesJob, ExportReportJob, ExportFile, ExportFileObserver, ExportFilePolicy, AppServiceProvider, ExportFinishedNotifier (+29 more)

### Community 185 - "configure"
Cohesion: 0.06
Nodes (45): add(), _cachedScopes(), configure(), createResolver(), datasetAnimationScopeKeys(), datasetElementScopeKeys(), datasetScopeKeys(), get() (+37 more)

### Community 193 - "restaurant-menu-catalog.blade.php"
Cohesion: 0.25
Nodes (7): landing.templates.foodie.sections.footer, landing.templates.foodie.sections.header, landing.templates.glassmorphism.partials.background, landing.templates.glassmorphism.sections.footer, landing.templates.glassmorphism.sections.header, partials.customer.landing-footer, partials.customer.landing-header

### Community 295 - "3. Detail Implementasi Perbaikan Keamanan"
Cohesion: 0.17
Nodes (11): 1. Ringkasan Eksekutif (Executive Summary), 2. Matriks Temuan & Status Perbaikan (Findings & Remediation Matrix), 3. Detail Implementasi Perbaikan Keamanan, 4. Hasil Verifikasi Pengujian Otomatis, A. Proteksi `qr_secret` pada Model (`SEC-01`), B. Middleware HTTP Security Headers (`SEC-02`), C. Pengetatan CORS & Session Cookie (`SEC-03` & `SEC-05`), D. Sanitasi File Upload (`SEC-06`) (+3 more)

### Community 296 - "foodie/show.blade.php"
Cohesion: 0.33
Nodes (5): landing.templates.foodie.sections., landing.templates.foodie.sections.hero, landing.sections., landing.templates.foodie.sections.footer, landing.templates.foodie.sections.header

### Community 297 - "Y"
Cohesion: 0.11
Nodes (22): at(), Bf(), determineDataLimits(), ef(), getMatchingVisibleMetas(), getMinMax(), _getOtherScale(), getUserBounds() (+14 more)

### Community 301 - "classic/show.blade.php"
Cohesion: 0.50
Nodes (3): landing.sections., partials.customer.landing-footer, partials.customer.landing-header

### Community 313 - "S"
Cohesion: 0.10
Nodes (28): afterAutoSkip(), Bt(), buildLookupTable(), da(), drawTitle(), Ds(), Fs(), getBasePixel() (+20 more)

### Community 317 - "MenuItem"
Cohesion: 0.04
Nodes (12): GuestMenu, MenuItem, LogOptions, VisitCartItem, GuestCartService, MenuModifierService, extraMenuItem(), GuestMenuTest (+4 more)

### Community 318 - "Illuminate\Database\Eloquent\Builder"
Cohesion: 0.06
Nodes (12): getRecordRouteBindingEloquentQuery(), ManageDiningTables, Action, Closure, bootBelongsToRestaurantAndOutlet(), bootScopedToRestaurant(), scopeForRestaurant(), scopeWithoutRestaurantScope() (+4 more)

### Community 320 - "draw"
Cohesion: 0.09
Nodes (34): addElements(), bi(), bindEvents(), bindUserEvents(), buildOrUpdateScales(), _checkEventBindings(), clear(), _dataCheck() (+26 more)

### Community 321 - "A"
Cohesion: 0.07
Nodes (38): Ot(), A(), apply(), As(), chartOptionScopes(), _computeLabelSizes(), constructor(), cr() (+30 more)

### Community 322 - "glassmorphism/show.blade.php"
Cohesion: 0.29
Nodes (6): landing.templates.glassmorphism.sections., landing.templates.glassmorphism.sections.hero, landing.sections., landing.templates.glassmorphism.partials.background, landing.templates.glassmorphism.sections.footer, landing.templates.glassmorphism.sections.header

### Community 327 - "addSingleBadge"
Cohesion: 0.33
Nodes (6): addBadgesForSelectedOptions(), addSingleBadge(), createBadgeElement(), createRemoveButton(), getLabelForSingleSelection(), getSelectedOptionLabel()

### Community 328 - "fn"
Cohesion: 0.08
Nodes (35): themeClasses(), addAttributes(), b1(), Ck(), coordsAtPos(), De(), fn(), Gh() (+27 more)

### Community 340 - "SubscriptionInvoice"
Cohesion: 0.12
Nodes (5): FounderStatsWidget, SubscriptionInvoice, SubscriptionInvoiceService, Filament\Widgets\StatsOverviewWidget, Filament\Widgets\StatsOverviewWidget\Stat

### Community 345 - "s"
Cohesion: 0.05
Nodes (62): aa(), addEventListener(), Ae(), ai(), al(), an(), _animateOptions(), bindResponsiveEvents() (+54 more)

### Community 346 - "resolve"
Cohesion: 0.04
Nodes (140): Ad(), addKeyboardShortcuts(), after(), al(), ay(), Bd(), before(), Bg() (+132 more)

### Community 348 - "st"
Cohesion: 0.24
Nodes (11): [g](), _freeze(), getAllExtensions(), Ct(), lt(), ot(), se(), st() (+3 more)

### Community 349 - "N"
Cohesion: 0.33
Nodes (11): ae(), A(), E(), at(), be(), Gt(), i(), Jt() (+3 more)

### Community 356 - "Ae"
Cohesion: 0.67
Nodes (3): Ae(), Bt(), ne()

## Knowledge Gaps
- **333 isolated node(s):** `$schema`, `name`, `type`, `description`, `laravel` (+328 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **47 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `_s()` connect `components/chart.js` to `o`, `rich-editor.js`?**
  _High betweenness centrality (0.027) - this node is a cross-community bridge._
- **Why does `Wi()` connect `Ye` to `code-editor.js`, `rich-editor.js`, `constructor`, `components/select.js`, `facet`, `columns/select.js`?**
  _High betweenness centrality (0.026) - this node is a cross-community bridge._
- **Why does `update()` connect `constructor` to `stat/chart.js`, `code-editor.js`, `rich-editor.js`, `slice`, `get`, `advance`, `n`, `facet`, `echo.js`, `fromObject`, `W`, `O`, `Ye`, `markdown-editor.js`, `te`, `dx`, `reduce`, `replace`, `next`, `sliceDoc`, `fn`, `i`, `resolve`, `g$`?**
  _High betweenness centrality (0.021) - this node is a cross-community bridge._
- **Are the 18 inferred relationships involving `constructor()` (e.g. with `a()` and `h()`) actually correct?**
  _`constructor()` has 18 INFERRED edges - model-reasoned connections that need verification._
- **Are the 26 inferred relationships involving `update()` (e.g. with `Pr()` and `a()`) actually correct?**
  _`update()` has 26 INFERRED edges - model-reasoned connections that need verification._
- **What connects `$schema`, `name`, `type` to the rest of the system?**
  _333 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `stat/chart.js` be split into smaller, more focused modules?**
  _Cohesion score 0.024135681669928244 - nodes in this community are weakly interconnected._