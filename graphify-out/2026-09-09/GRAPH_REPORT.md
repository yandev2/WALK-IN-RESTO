# Graph Report - WALK-IN-RESTO  (2026-09-09)

## Corpus Check
- 652 files · ~318,204 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 8680 nodes · 26737 edges · 358 communities (313 shown, 45 thin omitted)
- Extraction: 91% EXTRACTED · 9% INFERRED · 0% AMBIGUOUS · INFERRED: 2462 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `0baaead6`
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
- Outlet
- find
- r
- i
- User
- Dashboard
- _update
- Order
- static
- Restaurant
- get
- ir
- slice
- eq
- AdminPanelProvider.php
- GuestCheckoutService
- ExportFile
- support.js
- n
- addCommands
- of
- columns/select.js
- .slice
- advance
- echo.js
- resolve
- CashierMenuCatalog
- Activity
- Filament\Schemas\Schema
- SubscriptionGate
- W
- DiningTable
- .panel
- Illuminate\Database\Eloquent\Builder
- Ye
- EditProfile
- fn
- notifications.js
- markdown-editor.js
- PlatformSetting
- te
- Cn
- components/select.js
- O
- tables.js
- Illuminate\Console\Command
- r
- o
- SubscriptionStatus
- Xt
- 2026_08_20_010000_add_floor_layout_to_tables_table.php
- filament-right-click.js
- t
- Si
- SubscriptionAccess
- ae
- selectOption
- ManagePlatformPages
- CreateCashierOrder
- ir
- ce
- slider.js
- RestaurantAnalyticsPeriod
- selectOption
- fn
- facet
- InvoicePaymentTest
- SubscriptionInvoiceService
- file-upload.js
- create
- ManageHomeLanding
- RestaurantDirectory
- FonnteErrorMessage
- child
- devDependencies
- filament/app.js
- sliceDoc
- fn
- selectRecords
- require
- scripts
- RestaurantCategory
- color-picker.js
- js/app.js
- create
- composer.json
- order-today-stats-widget.blade.php
- RestaurantDirectoryTest
- date-time-picker.js
- 🚀 3. Langkah-Langkah Menambahkan Template Baru (*Workflow*)
- P
- Mt
- Illuminate\Http\Request
- OrderReceiptPrintTest
- actions/actions.js
- GuestContext
- schemas.js
- Landasan Produksi — Tahap 1 (Walk-In Operasional)
- Guest
- RefreshesAnalyticsChart.php
- 2. Masalah di lapangan — dan apa yang sistem selesaikan
- CashierOrderPreview
- require-dev
- Payment
- 6. Katalog fitur
- ExcelExporter
- config
- 6. Katalog fitur
- Login
- register-restaurant.blade.php
- add-to-cart-modal.blade.php
- nodeAt
- components/actions.js
- psr-4
- extra
- logging.php
- RegisterRestaurant
- MenuCategory
- cart.blade.php
- 2026_08_19_040000_add_soft_deletes_to_core_tables.php
- customer.blade.php
- landing/show.blade.php
- database-notifications.blade.php
- guest-order.blade.php
- landing.blade.php
- sidebar.blade.php
- guest/menu.blade.php
- search-bar.blade.php
- activitylog.php
- SoftDeleteTrashTest
- closeSimpleModeModal
- Illuminate\Support\Facades\Schema
- Illuminate\Database\Schema\Blueprint
- cc
- Illuminate\Database\Migrations\Migration
- ProfilePageTest
- CashierMenuCatalog.php
- SubscriptionWriteGuard
- CmsProfile
- Filament\Panel
- toString
- filament-shield.php
- 🎨 Spesifikasi Token & Class CSS yang Tersedia di `theme.css`
- dx
- le
- SubscriptionPlan
- pay.blade.php
- order-panel.blade.php
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
- N
- classic/show.blade.php
- GuestMenuTest
- rt
- rules/graphify.md
- workflows/graphify.md
- sl
- FounderStatsWidget.php
- yl
- clickPercent
- et
- glassmorphism/show.blade.php
- c
- CashierFilamentActionsTest
- addSingleBadge
- FilamentTenantTheme.php
- SubscriptionInvoice
- glassmorphism-background.blade.php
- st
- dropdown.blade.php
- Ae

## God Nodes (most connected - your core abstractions)
1. `Restaurant` - 290 edges
2. `User` - 257 edges
3. `TestCase` - 155 edges
4. `constructor()` - 152 edges
5. `update()` - 148 edges
6. `Order` - 119 edges
7. `MenuItem` - 107 edges
8. `resolve()` - 94 edges
9. `y()` - 93 edges
10. `DiningTable` - 90 edges

## Surprising Connections (you probably didn't know these)
- `up()` --calls--> `DiningTable`  [EXTRACTED]
  database/migrations/2026_08_20_010000_add_floor_layout_to_tables_table.php → app/Models/DiningTable.php
- `createGuestRestaurant()` --calls--> `DiningTable`  [EXTRACTED]
  tests/Concerns/CreatesGuestRestaurant.php → app/Models/DiningTable.php
- `createGuestRestaurant()` --calls--> `MenuCategory`  [EXTRACTED]
  tests/Concerns/CreatesGuestRestaurant.php → app/Models/MenuCategory.php
- `extraMenuItem()` --calls--> `MenuItem`  [EXTRACTED]
  tests/Concerns/CreatesGuestRestaurant.php → app/Models/MenuItem.php
- `paidGuestOrder()` --calls--> `Order`  [EXTRACTED]
  tests/Concerns/CreatesGuestRestaurant.php → app/Models/Order.php

## Import Cycles
- None detected.

## Communities (358 total, 45 thin omitted)

### Community 0 - "stat/chart.js"
Cohesion: 0.01
Nodes (475): A(), aa(), acquireContext(), active(), add(), addControllers(), addElements(), addEventListener() (+467 more)

### Community 1 - "components/chart.js"
Cohesion: 0.01
Nodes (309): $a(), active(), ad(), add(), addControllers(), addEventListener(), addPlugins(), addScales() (+301 more)

### Community 2 - "code-editor.js"
Cohesion: 0.01
Nodes (128): Ac(), addActive(), addCompletion(), addCompletions(), addNamespace(), addNamespaceObject(), Ag(), Ar() (+120 more)

### Community 3 - "rich-editor.js"
Cohesion: 0.01
Nodes (201): aa(), add(), addExtensions(), addGlobalAttributes(), addHackNode(), addTextblockHacks(), an(), applyAspectRatio() (+193 more)

### Community 4 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.03
Nodes (39): CmsBanner, LogOptions, CmsFaq, LogOptions, CmsGalleryImage, LogOptions, restaurant(), bootPurgesPublicDiskFiles() (+31 more)

### Community 5 - "y"
Cohesion: 0.18
Nodes (49): al(), at(), Be(), Cr(), de(), dt(), Ee(), ef() (+41 more)

### Community 6 - "TestCase"
Cohesion: 0.03
Nodes (62): SetPermissionsTeamId, CashierOrderService, BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles, Bityukov\CommandCenter\Filament\Pages\Commands, Bityukov\CommandCenter\Filament\Pages\History, DatabaseSeeder, RolePermissionSeeder, Filament\Facades\Filament (+54 more)

### Community 7 - "constructor"
Cohesion: 0.03
Nodes (129): add(), addChunk(), addEventListener(), addInfoPane(), addInner(), addSelection(), addToSet(), addWindowListeners() (+121 more)

### Community 8 - "Outlet"
Cohesion: 0.08
Nodes (5): Outlet, RestaurantProvisioner, CheckoutTotals, LandingMenuHiddenPriceTest, MenuItemBestSellerTest

### Community 9 - "find"
Cohesion: 0.10
Nodes (30): baseDirAt(), bidiIn(), bidiSpans(), bidiSpansAt(), checkHover(), coordsAtPos(), Df(), dirAt() (+22 more)

### Community 10 - "r"
Cohesion: 0.05
Nodes (127): _0(), addNodeView(), addOptions(), addProseMirrorPlugins(), af(), au(), buildProps(), Cc() (+119 more)

### Community 11 - "i"
Cohesion: 0.04
Nodes (135): aa(), addElement(), applyChanges(), balance(), balanced(), baseIndent(), baseIndentFor(), Bg() (+127 more)

### Community 12 - "User"
Cohesion: 0.03
Nodes (25): Role, User, RolePolicy, UserPolicy, PermissionCheck, Filament\Models\Contracts\FilamentUser, Filament\Models\Contracts\HasTenants, Illuminate\Database\Eloquent\Factories\HasFactory (+17 more)

### Community 13 - "Dashboard"
Cohesion: 0.08
Nodes (6): Dashboard, GenerateReport, BackedEnum, UnitEnum, PermissionTeam, TenantIsolationTest

### Community 14 - "_update"
Cohesion: 0.03
Nodes (119): addBox(), addElements(), afterBuildTicks(), afterCalculateLabelRotation(), afterDataLimits(), afterDatasetsUpdate(), afterFit(), afterSetDimensions() (+111 more)

### Community 15 - "Order"
Cohesion: 0.04
Nodes (17): OrderReceiptDownloadController, OrderReceiptPrintController, SendWhatsappReceiptJob, Order, OrderItem, OrderReceipt, WhatsappMessage, KdsItemService (+9 more)

### Community 16 - "static"
Cohesion: 0.02
Nodes (46): canDelete(), canDeleteAny(), canForceDelete(), canRestore(), FacilityResource, CreateFacility, EditFacility, ListFacilities (+38 more)

### Community 17 - "Restaurant"
Cohesion: 0.03
Nodes (16): CreateSubscriptionInvoice, SitemapController, DateTimeInterface, Restaurant, ExportStoragePath, ReceiptLogo, DemoCommissionInvoicesSeeder, Filament\Models\Contracts\HasName (+8 more)

### Community 18 - "get"
Cohesion: 0.03
Nodes (104): addBlock(), addBlockWidget(), addBreak(), addComposition(), addDelimiter(), addInlineWidget(), addLine(), addLineDeco() (+96 more)

### Community 19 - "ir"
Cohesion: 0.10
Nodes (26): beforeDatasetsDraw(), beforeDraw(), bu(), dataset(), Do(), getSortedVisibleDatasetMetas(), getVisibleDatasetCount(), index() (+18 more)

### Community 20 - "slice"
Cohesion: 0.05
Nodes (58): addGaps(), addLeafElement(), addNode(), ATXHeading(), _c(), char(), complete(), computeBlockGapDeco() (+50 more)

### Community 21 - "eq"
Cohesion: 0.07
Nodes (40): addNode(), ao(), append(), destroyBetween(), destroyRest(), dragend(), dragleave(), dragover() (+32 more)

### Community 22 - "AdminPanelProvider.php"
Cohesion: 0.16
Nodes (18): ApplyPlatformBrandTheme, BezhanSalleh\FilamentShield\FilamentShieldPlugin, Bityukov\CommandCenter\Filament\CommandCenterPlugin, Filament\Http\Middleware\Authenticate, Filament\Http\Middleware\AuthenticateSession, Filament\Http\Middleware\DisableBladeIconComponents, Filament\Http\Middleware\DispatchServingFilamentEvent, Filament\Navigation\NavigationGroup (+10 more)

### Community 24 - "ExportFile"
Cohesion: 0.05
Nodes (17): PdfExporter, CleanupOldExportFilesJob, ExportReportJob, ExportFile, ExportFileObserver, ExportFilePolicy, ExportFinishedNotifier, ReportExportDispatcher (+9 more)

### Community 25 - "support.js"
Cohesion: 0.05
Nodes (75): acquireScrollLock(), ai(), e(), Bi(), br(), Bt(), ca(), close() (+67 more)

### Community 26 - "n"
Cohesion: 0.09
Nodes (79): _a(), Ae(), ar(), as(), bc(), ee(), ue(), u() (+71 more)

### Community 27 - "addCommands"
Cohesion: 0.13
Nodes (26): addCommands(), addStoredMark(), computeAttrs(), createChecked(), ensureMarks(), handleExit(), i1(), insertText() (+18 more)

### Community 28 - "of"
Cohesion: 0.04
Nodes (78): active(), apply(), B(), baseTheme(), between(), blur(), bu(), checkAsyncSchedule() (+70 more)

### Community 29 - "columns/select.js"
Cohesion: 0.06
Nodes (57): A(), An(), applyDisabledState(), b(), Bt(), Ce(), D(), disable() (+49 more)

### Community 30 - ".slice"
Cohesion: 0.06
Nodes (54): accepts(), addMaps(), addStep(), addTransform(), appendMap(), appendMapping(), appendMappingInverted(), apply() (+46 more)

### Community 31 - "advance"
Cohesion: 0.04
Nodes (84): acceptToken(), addActions(), addChild(), advance(), advanceFully(), advanceStack(), allActions(), allows() (+76 more)

### Community 32 - "echo.js"
Cohesion: 0.05
Nodes (49): a(), ar(), b(), Be(), Ce(), cr(), d(), De() (+41 more)

### Community 33 - "resolve"
Cohesion: 0.04
Nodes (140): Ad(), addKeyboardShortcuts(), after(), al(), ay(), Bd(), before(), Bg() (+132 more)

### Community 35 - "Activity"
Cohesion: 0.09
Nodes (7): ViewActivity, Activity, ActivityPresenter, Filament\Resources\Pages\ViewRecord, Spatie\Activitylog\Models\Activity, ActivityLogTenantTest, TableOpsServiceTest

### Community 36 - "Filament\Schemas\Schema"
Cohesion: 0.03
Nodes (96): Action, trashPageAction(), SoftDeleteTrashPage, ActivityResource, ActivityInfolist, ActivitiesTable, CmsBannerResource, ManageCmsBanners (+88 more)

### Community 37 - "SubscriptionGate"
Cohesion: 0.21
Nodes (4): Carbon, DateTimeInterface, SubscriptionLifecycleService, SubscriptionGate

### Community 38 - "W"
Cohesion: 0.05
Nodes (74): AQ(), atLastNode(), au(), b1(), child(), childAfter(), childBefore(), cursor() (+66 more)

### Community 39 - "DiningTable"
Cohesion: 0.03
Nodes (22): ManageDiningTables, Action, Closure, bootBelongsToRestaurantAndOutlet(), DiningTable, Visit, OrderPaymentService, StaleOperationsService (+14 more)

### Community 40 - ".panel"
Cohesion: 0.20
Nodes (5): AdminPanelProvider, FounderPanelProvider, AuthGlass, Filament\PanelProvider, Illuminate\Support\HtmlString

### Community 41 - "Illuminate\Database\Eloquent\Builder"
Cohesion: 0.04
Nodes (32): getRecordRouteBindingEloquentQuery(), KitchenDisplay, BackedEnum, UnitEnum, Width, bootScopedToRestaurant(), scopeForRestaurant(), scopeWithoutRestaurantScope() (+24 more)

### Community 42 - "Ye"
Cohesion: 0.10
Nodes (41): Rd(), $a(), ak(), at(), bk(), c(), bp(), Dk() (+33 more)

### Community 43 - "EditProfile"
Cohesion: 0.24
Nodes (4): EditProfile, ProfileInformationForm, Ipatco\FilamentProfile\Forms\ProfileInformationForm, Ipatco\FilamentProfile\Pages\EditProfile

### Community 44 - "fn"
Cohesion: 0.08
Nodes (35): themeClasses(), addAttributes(), b1(), Ck(), coordsAtPos(), De(), fn(), Gh() (+27 more)

### Community 45 - "notifications.js"
Cohesion: 0.06
Nodes (31): actions(), button(), c(), close(), configureAnimations(), configureTransitions(), constructor(), danger() (+23 more)

### Community 46 - "markdown-editor.js"
Cohesion: 0.05
Nodes (83): ad(), af(), An(), bf(), bo(), Bt(), cd(), Ct() (+75 more)

### Community 47 - "PlatformSetting"
Cohesion: 0.03
Nodes (22): ManageBillingAccount, BackedEnum, UnitEnum, paymentMixSummary(), RestaurantController, PlatformPageController, RestaurantLandingController, RestaurantResource (+14 more)

### Community 48 - "te"
Cohesion: 0.05
Nodes (9): Bn(), br(), ji(), on(), qd(), Ri(), te(), Vi() (+1 more)

### Community 49 - "Cn"
Cohesion: 0.13
Nodes (46): Cn(), b(), Be(), Ce(), De(), dn(), _e(), Fe() (+38 more)

### Community 51 - "components/select.js"
Cohesion: 0.08
Nodes (38): A(), applyDisabledState(), b(), Bt(), D(), disable(), E(), en() (+30 more)

### Community 52 - "O"
Cohesion: 0.19
Nodes (38): b(), $c(), X(), ca(), me(), D(), _e(), Ea() (+30 more)

### Community 53 - "tables.js"
Cohesion: 0.13
Nodes (44): A(), ae(), B(), be(), C(), ce(), E(), ee() (+36 more)

### Community 54 - "Illuminate\Console\Command"
Cohesion: 0.15
Nodes (9): ExpireStaleOperationsCommand, FinalizeCashierCommissionCommand, GenerateUpcomingInvoicesCommand, ProcessSubscriptionLifecycleCommand, SendCashierCommissionReminderCommand, Illuminate\Console\Command, Illuminate\Foundation\Inspiring, Illuminate\Support\Facades\Artisan (+1 more)

### Community 55 - "r"
Cohesion: 0.15
Nodes (43): _a(), ar(), c(), f(), d(), di(), g(), Hi() (+35 more)

### Community 56 - "o"
Cohesion: 0.02
Nodes (284): aa(), acquireContext(), adjustHitBoxes(), af(), afterDraw(), ag(), ah(), Ao() (+276 more)

### Community 57 - "SubscriptionStatus"
Cohesion: 0.12
Nodes (3): BackedEnum, UnitEnum, SubscriptionStatus

### Community 58 - "Xt"
Cohesion: 0.12
Nodes (44): ae(), At(), bi(), bn(), ci(), cn(), ct(), de() (+36 more)

### Community 60 - "filament-right-click.js"
Cohesion: 0.11
Nodes (42): a(), ae(), b(), be(), C(), ce(), D(), de() (+34 more)

### Community 62 - "t"
Cohesion: 0.05
Nodes (56): a$(), activeForPoint(), as(), be(), boundChange(), chunkEnd(), commit(), comparePoint() (+48 more)

### Community 63 - "Si"
Cohesion: 0.13
Nodes (41): ae(), At(), bi(), bn(), ci(), cn(), ct(), de() (+33 more)

### Community 64 - "SubscriptionAccess"
Cohesion: 0.03
Nodes (29): canCreate(), canEdit(), canViewAny(), ManageLandingLayout, BackedEnum, Closure, UnitEnum, OrderResource (+21 more)

### Community 65 - "ae"
Cohesion: 0.09
Nodes (34): ae(), Ao(), as(), B(), Kt(), cs(), Ee(), Ge() (+26 more)

### Community 66 - "selectOption"
Cohesion: 0.12
Nodes (39): addBadgesForSelectedOptions(), addSingleBadge(), addSingleSelectionDisplay(), closeDropdown(), constructor(), createBadgeElement(), createOptionElement(), createRemoveButton() (+31 more)

### Community 67 - "ManagePlatformPages"
Cohesion: 0.22
Nodes (3): ManagePlatformPages, BackedEnum, UnitEnum

### Community 68 - "CreateCashierOrder"
Cohesion: 0.17
Nodes (4): CreateCashierOrder, BackedEnum, UnitEnum, Width

### Community 69 - "ir"
Cohesion: 0.13
Nodes (33): De(), Ft(), ir(), ce(), de(), Dt(), Et(), fe() (+25 more)

### Community 70 - "ce"
Cohesion: 0.08
Nodes (46): Ac(), ao(), bl(), Cc(), ce(), cl(), Cn(), Dc() (+38 more)

### Community 71 - "slider.js"
Cohesion: 0.12
Nodes (31): ar(), Be(), Ce(), _e(), Ee(), er(), Fe(), G() (+23 more)

### Community 72 - "RestaurantAnalyticsPeriod"
Cohesion: 0.07
Nodes (17): analyticsDateFrom(), analyticsDateTo(), analyticsDayCount(), analyticsRangeLabel(), analyticsSnapshot(), canViewAnalytics(), normalizedAnalyticsDateRange(), Carbon (+9 more)

### Community 73 - "selectOption"
Cohesion: 0.15
Nodes (33): addSingleSelectionDisplay(), closeDropdown(), constructor(), createOptionElement(), deferPositionDropdown(), destroy(), filterOptions(), focusNextOption() (+25 more)

### Community 74 - "fn"
Cohesion: 0.13
Nodes (33): aa(), At(), ba(), cr(), da(), de(), dt(), ei() (+25 more)

### Community 75 - "facet"
Cohesion: 0.05
Nodes (57): accept(), activateHover(), addChanges(), Ah(), applyTransaction(), asSingle(), build(), cd() (+49 more)

### Community 79 - "file-upload.js"
Cohesion: 0.07
Nodes (13): hc(), constructor(), define(), dm(), _freeze(), getAllExtensions(), getExtension(), _getTestState() (+5 more)

### Community 81 - "create"
Cohesion: 0.04
Nodes (118): Ac(), addAll(), addDOM(), addElement(), addElementByRule(), addNodeMark(), addTextNode(), addToSet() (+110 more)

### Community 82 - "ManageHomeLanding"
Cohesion: 0.22
Nodes (3): ManageHomeLanding, BackedEnum, UnitEnum

### Community 84 - "FonnteErrorMessage"
Cohesion: 0.11
Nodes (8): FonnteClient, FonnteErrorMessage, Throwable, Illuminate\Http\Client\ConnectionException, Illuminate\Http\Client\Response, PHPUnit\Framework\Attributes\DataProvider, RuntimeException, FonnteErrorMessageTest

### Community 85 - "child"
Cohesion: 0.10
Nodes (33): addInner(), Bm(), child(), dg(), eat(), err(), ew(), findIndex() (+25 more)

### Community 91 - "devDependencies"
Cohesion: 0.09
Nodes (21): apexcharts, axios, concurrently, laravel-vite-plugin, dependencies, apexcharts, devDependencies, axios (+13 more)

### Community 92 - "filament/app.js"
Cohesion: 0.13
Nodes (8): B(), close(), G(), init(), P(), setUpResizeObserver(), x(), Y()

### Community 93 - "sliceDoc"
Cohesion: 0.06
Nodes (50): aO(), bd(), Bh(), charCategorizer(), clearDelayedAndroidKey(), d0(), De(), delayAndroidKey() (+42 more)

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

### Community 98 - "RestaurantCategory"
Cohesion: 0.10
Nodes (7): ManageCmsProfile, BackedEnum, UnitEnum, Facility, RestaurantCategory, FacilitySeeder, RestaurantCategorySeeder

### Community 99 - "color-picker.js"
Cohesion: 0.14
Nodes (3): style(), update(), [x]()

### Community 100 - "js/app.js"
Cohesion: 0.16
Nodes (6): applyTheme(), bindThemeToggle(), initReveal(), initTheme(), syncThemeToggleIcons(), toggleTheme()

### Community 101 - "create"
Cohesion: 0.03
Nodes (139): El(), abutsStart(), ac(), ae(), after(), Al(), Am(), before() (+131 more)

### Community 103 - "composer.json"
Cohesion: 0.14
Nodes (13): autoload-dev, psr-4, description, keywords, license, minimum-stability, name, prefer-stable (+5 more)

### Community 106 - "RestaurantDirectoryTest"
Cohesion: 0.05
Nodes (9): periodSummary(), WelcomeBannerWidget, GeoDistance, RestaurantDirectory, Illuminate\Contracts\Pagination\LengthAwarePaginator, PHPUnit\Framework\TestCase, RestaurantDirectoryTest, ExampleTest (+1 more)

### Community 107 - "date-time-picker.js"
Cohesion: 0.29
Nodes (7): d(), e(), i(), m(), r(), s(), t()

### Community 108 - "🚀 3. Langkah-Langkah Menambahkan Template Baru (*Workflow*)"
Cohesion: 0.12
Nodes (15): 📌 1. Prinsip Utama & Aturan Wajib (Golden Rules), 🏗️ 2. Arsitektur 10 Core Section CMS (1:1 Mapping), 🚀 3. Langkah-Langkah Menambahkan Template Baru (*Workflow*), 📊 4. Variabel yang Disediakan oleh `LandingPageDataService`, 💎 5. Pelajaran Desain & Best Practices UI/UX (*Key Learnings*), A. Larangan Keras Menggunakan Emoji Unicode (Gunakan SVG Heroicons), B. Arsitektur Layering 3D & Background Tembus Pandang (*Stacking Context*), C. Resep Glassmorphism iOS yang Nyata (*True Frosted Glass*) (+7 more)

### Community 109 - "P"
Cohesion: 0.12
Nodes (26): addInputRules(), addMark(), addPasteRules(), Ah(), Ax(), dispatchTransaction(), ea(), Eh() (+18 more)

### Community 110 - "Mt"
Cohesion: 0.24
Nodes (11): apply(), fs(), go(), Hr(), T(), ir(), it(), Mt() (+3 more)

### Community 111 - "Illuminate\Http\Request"
Cohesion: 0.05
Nodes (27): ApplyRestaurantPanelTheme, EnsureApiGuestVisit, EnsureGuestVisit, EnsureRestaurantOperations, EnsureTenantSubscription, IdentifyApiGuestDevice, IdentifyGuestDevice, RequireApiGuestDevice (+19 more)

### Community 115 - "actions/actions.js"
Cohesion: 0.44
Nodes (8): closeModal(), generateModalId(), getActionNestingIndexFromModalId(), init(), openModal(), rememberPreviouslyFocusedElement(), restorePreviouslyFocusedElement(), syncActionModals()

### Community 116 - "GuestContext"
Cohesion: 0.03
Nodes (34): CartController, CheckoutController, MenuController, OrderController, ReviewController, SessionController, TableController, VisitController (+26 more)

### Community 118 - "Landasan Produksi — Tahap 1 (Walk-In Operasional)"
Cohesion: 0.05
Nodes (37): 10. Aturan yang tidak boleh dilanggar, 11. Matriks skenario lapangan, 12. Yang sengaja tidak didukung (bukan gap, non-goal), 13. Tahap 2 (bukan sekarang), 14. Urutan bangun (agar cepat produksi), 1. Keputusan terkunci, 2. Modul tahap 1 vs ditunda, 3. Model domain (+29 more)

### Community 119 - "Guest"
Cohesion: 0.05
Nodes (34): Bentuk objek, CartItemResource, DELETE `/guest/cart/items/{cartItem}`, GET `/guest/cart`, GET `/guest/menu`, GET `/guest/orders`, GET `/guest/orders/{public_id}`, GET `/guest/review` (+26 more)

### Community 120 - "RefreshesAnalyticsChart.php"
Cohesion: 0.36
Nodes (8): generateChartDataChecksum(), getCachedChartData(), getChartData(), mountRefreshesAnalyticsChart(), refreshAnalyticsChartData(), rendering(), updateChartData(), Livewire\Attributes\Locked

### Community 121 - "2. Masalah di lapangan — dan apa yang sistem selesaikan"
Cohesion: 0.05
Nodes (41): 1. Cerita yang mungkin terasa familiar, 2.10 Struk kertas hilang, tamu minta dikirim WhatsApp, 2.11 Tampilan website restoran kaku atau tidak sesuai konsep resto, 2.12 Calon tamu ingin lihat menu lengkap sebelum datang ke resto, 2.13 Foto menu yang diupload staf ukurannya raksasa bikin web lemot, 2.14 Owner dan kasir ingin tahu performa hari ini secara instan, 2.15 Tak sengaja hapus menu atau meja saat jam sibuk, 2.16 Sulit ditemukan calon tamu baru di internet (+33 more)

### Community 123 - "require-dev"
Cohesion: 0.25
Nodes (8): require-dev, fakerphp/faker, laravel/pail, laravel/pint, laravel/sail, mockery/mockery, nunomaduro/collision, phpunit/phpunit

### Community 124 - "Payment"
Cohesion: 0.05
Nodes (18): ViewOrder, GuestPay, GuestStatus, Payment, PaymentProofService, CashTender, IdrAmount, ImageOptimizer (+10 more)

### Community 125 - "6. Katalog fitur"
Cohesion: 0.06
Nodes (33): 1. Latar belakang, 2. Rumusan masalah, 3.1 Tujuan umum, 3.2 Tujuan khusus, 3. Tujuan, 4.1 Bagi restoran (owner, kasir, dapur), 4.2 Bagi tamu, 4.3 Bagi pengelola platform (+25 more)

### Community 126 - "ExcelExporter"
Cohesion: 0.39
Nodes (4): ExcelExporter, Illuminate\Contracts\View\View, Maatwebsite\Excel\Concerns\FromView, Maatwebsite\Excel\Concerns\ShouldAutoSize

### Community 127 - "config"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

### Community 128 - "6. Katalog fitur"
Cohesion: 0.06
Nodes (32): 1. Latar belakang, 2. Rumusan masalah, 3.1 Tujuan umum, 3.2 Tujuan khusus, 3. Tujuan, 4.1 Bagi restoran (owner, kasir, dapur), 4.2 Bagi tamu, 4.3 Bagi pengelola platform (+24 more)

### Community 129 - "Login"
Cohesion: 0.24
Nodes (4): Login, Filament\Auth\Pages\Login, Filament\Schemas\Components\Component, Illuminate\Contracts\Support\Htmlable

### Community 130 - "register-restaurant.blade.php"
Cohesion: 0.15
Nodes (12): applyColorPreset(, back, nextFromAccount, nextFromPlan, nextFromRestaurant, nextFromVisual, register, $set( (+4 more)

### Community 131 - "add-to-cart-modal.blade.php"
Cohesion: 0.29
Nodes (6): cancelPicking, confirmAdd, decrementPickingQty, incrementPickingQty, setVariant({{ $variant->id }}), toggleModifier({{ $modifier->id }})

### Community 132 - "nodeAt"
Cohesion: 0.11
Nodes (50): AS(), cellsInRect(), co(), colCount(), content(), createAndFill(), ct(), dS() (+42 more)

### Community 134 - "psr-4"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 135 - "extra"
Cohesion: 0.40
Nodes (5): dev-master, extra, branch-alias, laravel, dont-discover

### Community 136 - "logging.php"
Cohesion: 0.40
Nodes (4): Monolog\Handler\NullHandler, Monolog\Handler\StreamHandler, Monolog\Handler\SyslogUdpHandler, Monolog\Processor\PsrLogMessageProcessor

### Community 139 - "MenuCategory"
Cohesion: 0.09
Nodes (6): RestaurantMenuController, MenuCategoryResource, MenuCategory, MenuSearch, PublicRestaurantApiTest, LandingMenuCatalogTest

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

### Community 149 - "sidebar.blade.php"
Cohesion: 0.50
Nodes (3): filament.widgets.analytics._chart-canvas, filament.widgets.analytics._section-header, filament.widgets.analytics._styles

### Community 150 - "guest/menu.blade.php"
Cohesion: 0.50
Nodes (3): guest.partials.nav, setCategory({{ $category->id }}), setCategory(null)

### Community 158 - "cc"
Cohesion: 0.08
Nodes (28): attrs(), AX(), bi(), cc(), cO(), combine(), configure(), extend() (+20 more)

### Community 163 - "CashierMenuCatalog.php"
Cohesion: 0.32
Nodes (3): TemplateRadioPicker, Filament\Forms\Components\Field, Illuminate\Database\Eloquent\Collection

### Community 164 - "SubscriptionWriteGuard"
Cohesion: 0.28
Nodes (3): BlockGraceMutations, SubscriptionWriteGuard, Livewire\ComponentHook

### Community 168 - "CmsProfile"
Cohesion: 0.05
Nodes (7): CmsProfile, FilamentTenantTheme, LandingLayout, self, FilamentTenantThemeTest, LandingLayoutTest, LandingMultiTemplateTest

### Community 170 - "Filament\Panel"
Cohesion: 0.29
Nodes (4): FilamentProfilePlugin, Filament\Panel, Ipatco\FilamentProfile\FilamentProfilePlugin, Ipatco\FilamentProfile\Widgets\AccountWidget

### Community 172 - "toString"
Cohesion: 0.13
Nodes (20): Bc(), check(), checkAttrs(), checkContent(), cn(), endIndex(), getObj(), hasProtocol() (+12 more)

### Community 173 - "filament-shield.php"
Cohesion: 0.29
Nodes (5): Dashboard, BezhanSalleh\FilamentShield\Resources\Roles\RoleResource, Filament\Pages\Dashboard, Filament\Widgets\AccountWidget, Filament\Widgets\FilamentInfoWidget

### Community 176 - "🎨 Spesifikasi Token & Class CSS yang Tersedia di `theme.css`"
Cohesion: 0.15
Nodes (12): 1. Kartu Kontainer Utama (`.vision-card`), 2. Kotak Ikon Gradien Neon (`.vision-icon-box`), 3. Kapsul Metrik / Sub-Kartu (`.vision-pill-card` atau `.vision-pill-dark`), 4. Kartu Filter (`.vision-filter-card`), 5. Tabel Data Kaca (`.vision-table-card`), 6. Form Section & Skema Input Kaca (`.vision-card` pada `Section::make`), 7. Kartu Statistik Global (`StatsOverviewWidget` / `Stat::make`), ⚙️ Aturan Wajib untuk AI Developer (+4 more)

### Community 180 - "dx"
Cohesion: 0.09
Nodes (38): Ei(), Aa(), ai(), Ba(), Bi(), cf(), da(), fa() (+30 more)

### Community 183 - "le"
Cohesion: 0.23
Nodes (13): De(), Ee(), Fl(), le(), mm(), pe(), q(), qe() (+5 more)

### Community 185 - "SubscriptionPlan"
Cohesion: 0.06
Nodes (6): ViewSubscriptionInvoice, SubscriptionInvoiceResource, SubscriptionPlan, SubscriptionPlanSeeder, Illuminate\Support\Carbon, Illuminate\Support\Facades\DB

### Community 193 - "restaurant-menu-catalog.blade.php"
Cohesion: 0.25
Nodes (7): landing.templates.foodie.sections.footer, landing.templates.foodie.sections.header, landing.templates.glassmorphism.partials.background, landing.templates.glassmorphism.sections.footer, landing.templates.glassmorphism.sections.header, partials.customer.landing-footer, partials.customer.landing-header

### Community 295 - "3. Detail Implementasi Perbaikan Keamanan"
Cohesion: 0.17
Nodes (11): 1. Ringkasan Eksekutif (Executive Summary), 2. Matriks Temuan & Status Perbaikan (Findings & Remediation Matrix), 3. Detail Implementasi Perbaikan Keamanan, 4. Hasil Verifikasi Pengujian Otomatis, A. Proteksi `qr_secret` pada Model (`SEC-01`), B. Middleware HTTP Security Headers (`SEC-02`), C. Pengetatan CORS & Session Cookie (`SEC-03` & `SEC-05`), D. Sanitasi File Upload (`SEC-06`) (+3 more)

### Community 296 - "foodie/show.blade.php"
Cohesion: 0.33
Nodes (5): landing.templates.foodie.sections., landing.templates.foodie.sections.hero, landing.sections., landing.templates.foodie.sections.footer, landing.templates.foodie.sections.header

### Community 297 - "N"
Cohesion: 0.33
Nodes (11): ae(), A(), E(), at(), be(), Gt(), i(), Jt() (+3 more)

### Community 301 - "classic/show.blade.php"
Cohesion: 0.50
Nodes (3): landing.sections., partials.customer.landing-footer, partials.customer.landing-header

### Community 306 - "rt"
Cohesion: 0.29
Nodes (8): ca(), Dp(), _e(), Ea(), nm(), rt(), xt(), ya()

### Community 313 - "sl"
Cohesion: 0.33
Nodes (7): Cp(), da(), Gp(), kp(), Np(), sl(), Vp()

### Community 317 - "FounderStatsWidget.php"
Cohesion: 0.50
Nodes (3): FounderStatsWidget, Filament\Widgets\StatsOverviewWidget, Filament\Widgets\StatsOverviewWidget\Stat

### Community 318 - "yl"
Cohesion: 0.40
Nodes (5): Bp(), om(), Op(), rl(), yl()

### Community 320 - "clickPercent"
Cohesion: 0.60
Nodes (5): clickPercent(), getPosition(), mouseUp(), movePlayhead(), timelineClicked()

### Community 321 - "et"
Cohesion: 0.40
Nodes (5): et(), ee(), he(), me(), Y()

### Community 322 - "glassmorphism/show.blade.php"
Cohesion: 0.29
Nodes (6): landing.templates.glassmorphism.sections., landing.templates.glassmorphism.sections.hero, landing.sections., landing.templates.glassmorphism.partials.background, landing.templates.glassmorphism.sections.footer, landing.templates.glassmorphism.sections.header

### Community 324 - "c"
Cohesion: 0.67
Nodes (4): c(), o(), p(), s()

### Community 327 - "addSingleBadge"
Cohesion: 0.33
Nodes (6): addBadgesForSelectedOptions(), addSingleBadge(), createBadgeElement(), createRemoveButton(), getLabelForSingleSelection(), getSelectedOptionLabel()

### Community 328 - "FilamentTenantTheme.php"
Cohesion: 0.40
Nodes (4): Filament\Support\Colors\Color, Filament\Support\Colors\ColorManager, Filament\Support\Facades\FilamentColor, ReflectionClass

### Community 348 - "st"
Cohesion: 0.36
Nodes (8): [g](), Ct(), lt(), ot(), se(), st(), Zt(), zt()

### Community 356 - "Ae"
Cohesion: 0.67
Nodes (3): Ae(), Bt(), ne()

## Knowledge Gaps
- **342 isolated node(s):** `$schema`, `name`, `type`, `description`, `laravel` (+337 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **45 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `_s()` connect `components/chart.js` to `rich-editor.js`?**
  _High betweenness centrality (0.028) - this node is a cross-community bridge._
- **Why does `Wi()` connect `Ye` to `code-editor.js`, `rich-editor.js`, `constructor`, `components/select.js`, `of`, `columns/select.js`?**
  _High betweenness centrality (0.022) - this node is a cross-community bridge._
- **Why does `update()` connect `constructor` to `stat/chart.js`, `code-editor.js`, `rich-editor.js`, `find`, `i`, `get`, `slice`, `n`, `of`, `advance`, `echo.js`, `resolve`, `W`, `Ye`, `fn`, `markdown-editor.js`, `te`, `dx`, `O`, `t`, `facet`, `sliceDoc`, `create`?**
  _High betweenness centrality (0.018) - this node is a cross-community bridge._
- **Are the 18 inferred relationships involving `constructor()` (e.g. with `a()` and `h()`) actually correct?**
  _`constructor()` has 18 INFERRED edges - model-reasoned connections that need verification._
- **Are the 26 inferred relationships involving `update()` (e.g. with `Pr()` and `a()`) actually correct?**
  _`update()` has 26 INFERRED edges - model-reasoned connections that need verification._
- **What connects `$schema`, `name`, `type` to the rest of the system?**
  _342 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `stat/chart.js` be split into smaller, more focused modules?**
  _Cohesion score 0.01090603402742131 - nodes in this community are weakly interconnected._