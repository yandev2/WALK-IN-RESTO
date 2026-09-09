# Graph Report - WALK-IN-RESTO  (2026-09-09)

## Corpus Check
- 646 files · ~313,697 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 8654 nodes · 26667 edges · 332 communities (292 shown, 40 thin omitted)
- Extraction: 91% EXTRACTED · 9% INFERRED · 0% AMBIGUOUS · INFERRED: 2463 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `0b593c82`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- stat/chart.js
- components/chart.js
- code-editor.js
- rich-editor.js
- Illuminate\Database\Eloquent\Relations\BelongsTo
- y
- TestCase
- constructor
- CmsMedia
- TenantContext
- r
- slice
- User
- Filament\Resources\Pages\ListRecords
- _update
- DiningTable
- Filament/Pages/Dashboard.php
- Order
- get
- Filament\Tables\Table
- advance
- nodeAt
- RestaurantCategory
- AppServiceProvider.php
- ExportFile
- support.js
- n
- find
- of
- columns/select.js
- .slice
- reduce
- echo.js
- resolve
- OrderResource
- ActivityPresenter
- Filament\Resources\Pages\ManageRecords
- LandingLayout
- prop
- ModifierGroupResource
- CmsGalleryImageResource
- ExcelExporter
- Ye
- Illuminate\Foundation\Http\FormRequest
- fn
- notifications.js
- markdown-editor.js
- PlatformSetting
- te
- Cn
- components/select.js
- tables.js
- MenuCategoryResource
- r
- o
- SubscriptionStatus
- Xt
- 2026_08_20_010000_add_floor_layout_to_tables_table.php
- filament-right-click.js
- t
- Si
- RendersAnalyticsDashboard.php
- ae
- selectOption
- g$
- CreateCashierOrder
- ir
- ce
- slider.js
- Restaurant
- selectOption
- fn
- facet
- InvoicePaymentTest
- SubscriptionAccess
- file-upload.js
- create
- Filament\Schemas\Schema
- RestaurantDirectory
- FonnteErrorMessage
- toString
- AdminPanelProvider.php
- devDependencies
- filament/app.js
- toString
- fn
- selectRecords
- require
- scripts
- Illuminate\Database\Eloquent\Model
- color-picker.js
- js/app.js
- buildTicks
- composer.json
- order-today-stats-widget.blade.php
- GeoDistance
- date-time-picker.js
- 🚀 3. Langkah-Langkah Menambahkan Template Baru (*Workflow*)
- Mt
- Login
- actions/actions.js
- schemas.js
- Landasan Produksi — Tahap 1 (Walk-In Operasional)
- Guest
- O
- 2. Masalah di lapangan — dan apa yang sistem selesaikan
- require-dev
- 6. Katalog fitur
- filament-shield.php
- config
- 6. Katalog fitur
- EditProfile
- register-restaurant.blade.php
- add-to-cart-modal.blade.php
- components/actions.js
- psr-4
- extra
- logging.php
- RegisterRestaurant
- GuestContext
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
- draw
- closeSimpleModeModal
- Illuminate\Support\Facades\Schema
- Illuminate\Database\Schema\Blueprint
- cc
- Illuminate\Database\Migrations\Migration
- GraceReadOnlyTest
- dx
- kpi.blade.php
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
- classic/show.blade.php
- rules/graphify.md
- workflows/graphify.md
- glassmorphism/show.blade.php
- CashierOrderPreview
- CashierFilamentActionsTest
- addSingleBadge
- SubscriptionInvoice
- glassmorphism-background.blade.php
- st
- N
- dropdown.blade.php
- Ae
- MenuItem

## God Nodes (most connected - your core abstractions)
1. `Restaurant` - 288 edges
2. `User` - 255 edges
3. `TestCase` - 153 edges
4. `constructor()` - 152 edges
5. `update()` - 148 edges
6. `Order` - 116 edges
7. `MenuItem` - 105 edges
8. `resolve()` - 94 edges
9. `y()` - 93 edges
10. `PlatformSetting` - 89 edges

## Surprising Connections (you probably didn't know these)
- `up()` --calls--> `DiningTable`  [EXTRACTED]
  database/migrations/2026_08_20_010000_add_floor_layout_to_tables_table.php → app/Models/DiningTable.php
- `createGuestRestaurant()` --calls--> `KdsStation`  [EXTRACTED]
  tests/Concerns/CreatesGuestRestaurant.php → app/Models/KdsStation.php
- `createGuestRestaurant()` --calls--> `MenuCategory`  [EXTRACTED]
  tests/Concerns/CreatesGuestRestaurant.php → app/Models/MenuCategory.php
- `createGuestRestaurant()` --calls--> `MenuItem`  [EXTRACTED]
  tests/Concerns/CreatesGuestRestaurant.php → app/Models/MenuItem.php
- `extraMenuItem()` --calls--> `MenuItem`  [EXTRACTED]
  tests/Concerns/CreatesGuestRestaurant.php → app/Models/MenuItem.php

## Import Cycles
- None detected.

## Communities (332 total, 40 thin omitted)

### Community 0 - "stat/chart.js"
Cohesion: 0.01
Nodes (491): themeClasses(), A(), aa(), acquireContext(), active(), add(), addControllers(), addElements() (+483 more)

### Community 1 - "components/chart.js"
Cohesion: 0.01
Nodes (392): aa(), abutsStart(), ac(), ad(), add(), addControllers(), addPlugins(), addScales() (+384 more)

### Community 2 - "code-editor.js"
Cohesion: 0.01
Nodes (133): aa(), Ac(), addActive(), addChanges(), addCompletion(), addCompletions(), addNamespace(), addNamespaceObject() (+125 more)

### Community 3 - "rich-editor.js"
Cohesion: 0.01
Nodes (284): aa(), Ad(), add(), addExtensions(), addHackNode(), addNode(), addTextblockHacks(), an() (+276 more)

### Community 4 - "Illuminate\Database\Eloquent\Relations\BelongsTo"
Cohesion: 0.03
Nodes (35): CmsBanner, LogOptions, CmsFaq, LogOptions, CmsGalleryImage, CmsProfile, LogOptions, restaurant() (+27 more)

### Community 5 - "y"
Cohesion: 0.18
Nodes (49): al(), at(), Be(), Cr(), de(), dt(), Ee(), ef() (+41 more)

### Community 6 - "TestCase"
Cohesion: 0.03
Nodes (54): ImageOptimizer, BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles, Bityukov\CommandCenter\Filament\Pages\Commands, Bityukov\CommandCenter\Filament\Pages\History, DatabaseSeeder, RolePermissionSeeder, Filament\Facades\Filament, Illuminate\Database\QueryException (+46 more)

### Community 7 - "constructor"
Cohesion: 0.03
Nodes (144): add(), addChunk(), addEventListener(), addInfoPane(), addInner(), addWindowListeners(), adjust(), al() (+136 more)

### Community 8 - "CmsMedia"
Cohesion: 0.03
Nodes (38): ApplyPlatformBrandTheme, ApplyRestaurantPanelTheme, EnsureApiGuestVisit, EnsureGuestVisit, EnsureRestaurantOperations, EnsureTenantSubscription, IdentifyApiGuestDevice, IdentifyGuestDevice (+30 more)

### Community 9 - "TenantContext"
Cohesion: 0.05
Nodes (15): DiningTableResource, ManageDiningTables, Action, Closure, TrashDiningTables, MenuItemResource, EditMenuItem, ListMenuItems (+7 more)

### Community 10 - "r"
Cohesion: 0.04
Nodes (155): _0(), addAttributes(), addNodeView(), addOptions(), addProseMirrorPlugins(), af(), au(), bl() (+147 more)

### Community 11 - "slice"
Cohesion: 0.05
Nodes (128): addElement(), Ah(), balanced(), baseIndentFor(), be(), Bg(), a(), blockAt() (+120 more)

### Community 12 - "User"
Cohesion: 0.02
Nodes (37): getRecordRouteBindingEloquentQuery(), AnalyticsKpiWidget, Activity, bootScopedToRestaurant(), scopeForRestaurant(), scopeWithoutRestaurantScope(), Role, LogOptions (+29 more)

### Community 13 - "Filament\Resources\Pages\ListRecords"
Cohesion: 0.05
Nodes (21): CreateFacility, ListFacilities, CreateLandingTemplate, ListLandingTemplates, CreateSubscriptionInvoice, ListSubscriptionInvoices, ViewSubscriptionInvoice, SubscriptionInvoiceResource (+13 more)

### Community 14 - "_update"
Cohesion: 0.03
Nodes (122): active(), addBox(), addElements(), afterBuildTicks(), afterCalculateLabelRotation(), afterDataLimits(), afterDatasetsUpdate(), afterDraw() (+114 more)

### Community 15 - "DiningTable"
Cohesion: 0.03
Nodes (21): DiningTable, LogOptions, Visit, CashierOrderService, GuestCheckoutService, MenuModifierService, OrderPaymentService, StaleOperationsService (+13 more)

### Community 16 - "Filament/Pages/Dashboard.php"
Cohesion: 0.06
Nodes (13): EditFacility, EditLandingTemplate, EditSubscriptionPlan, SubscriptionPlanResource, EditTenant, Dashboard, EditRestaurantCategory, EditRestaurant (+5 more)

### Community 17 - "Order"
Cohesion: 0.03
Nodes (23): OrderController, OrderReceiptDownloadController, OrderReceiptPrintController, SendWhatsappReceiptJob, Order, OrderItem, OrderReceipt, Payment (+15 more)

### Community 18 - "get"
Cohesion: 0.03
Nodes (108): addBlockWidget(), addBreak(), addComposition(), addDelimiter(), addInlineWidget(), addLine(), addLineStart(), addLineStartIfNotCovered() (+100 more)

### Community 19 - "Filament\Tables\Table"
Cohesion: 0.12
Nodes (47): TableRightClick, BackedEnum, Filament\Actions\Action, Filament\Actions\DeleteAction, Filament\Actions\EditAction, Filament\Forms\Components\CheckboxList, Filament\Forms\Components\ColorPicker, Filament\Forms\Components\Component (+39 more)

### Community 20 - "advance"
Cohesion: 0.05
Nodes (65): addChild(), addGaps(), addLeafElement(), addNode(), advance(), ATXHeading(), blank(), break() (+57 more)

### Community 21 - "nodeAt"
Cohesion: 0.05
Nodes (87): Ac(), addCommands(), addStoredMark(), append(), AS(), Cc(), cellsInRect(), checkContent() (+79 more)

### Community 22 - "RestaurantCategory"
Cohesion: 0.05
Nodes (9): periodSummary(), RestaurantCategory, RestaurantDirectory, RestaurantCategorySeeder, Illuminate\Contracts\Pagination\LengthAwarePaginator, Illuminate\Support\Collection, RestaurantDirectoryTest, RestaurantRegistrationStepperTest (+1 more)

### Community 23 - "AppServiceProvider.php"
Cohesion: 0.05
Nodes (26): KitchenDisplay, BackedEnum, UnitEnum, Width, SoftDeleteTrashPage, TrashUsers, AppServiceProvider, Filament\Actions\DeleteBulkAction (+18 more)

### Community 24 - "ExportFile"
Cohesion: 0.05
Nodes (16): PdfExporter, CleanupOldExportFilesJob, ExportReportJob, ExportFile, ExportFileObserver, ExportFilePolicy, ExportFinishedNotifier, ReportExportDispatcher (+8 more)

### Community 25 - "support.js"
Cohesion: 0.05
Nodes (75): acquireScrollLock(), ai(), e(), Bi(), br(), Bt(), ca(), close() (+67 more)

### Community 26 - "n"
Cohesion: 0.09
Nodes (79): _a(), Ae(), ar(), as(), bc(), ee(), ue(), u() (+71 more)

### Community 27 - "find"
Cohesion: 0.06
Nodes (51): addGlobalAttributes(), addInputRules(), addMark(), addPasteRules(), Ah(), Ax(), ay(), dispatchTransaction() (+43 more)

### Community 28 - "of"
Cohesion: 0.04
Nodes (70): active(), apply(), B(), baseTheme(), blur(), bu(), checkAsyncSchedule(), define() (+62 more)

### Community 29 - "columns/select.js"
Cohesion: 0.06
Nodes (57): A(), An(), applyDisabledState(), b(), Bt(), Ce(), D(), disable() (+49 more)

### Community 30 - ".slice"
Cohesion: 0.05
Nodes (60): accepts(), addInner(), addMaps(), addStep(), addTransform(), appendMap(), appendMapping(), appendMappingInverted() (+52 more)

### Community 31 - "reduce"
Cohesion: 0.08
Nodes (46): addActions(), advanceFully(), advanceStack(), allActions(), c0(), canShift(), close(), deadEnd() (+38 more)

### Community 32 - "echo.js"
Cohesion: 0.05
Nodes (49): a(), ar(), b(), Be(), Ce(), cr(), d(), De() (+41 more)

### Community 33 - "resolve"
Cohesion: 0.07
Nodes (97): addKeyboardShortcuts(), after(), al(), before(), blockRange(), Bs(), canReplace(), canReplaceWith() (+89 more)

### Community 34 - "OrderResource"
Cohesion: 0.06
Nodes (13): OrderResource, ListOrders, ViewOrder, OrderTodayStatsWidget, PendingPaymentsWidget, IdrAmount, Carbon\Carbon, Filament\Actions\ViewAction (+5 more)

### Community 35 - "ActivityPresenter"
Cohesion: 0.10
Nodes (6): ActivityResource, ListActivities, ViewActivity, ActivityInfolist, ActivitiesTable, ActivityPresenter

### Community 36 - "Filament\Resources\Pages\ManageRecords"
Cohesion: 0.05
Nodes (15): CmsBannerResource, ManageCmsBanners, TrashCmsBanners, CmsFaqResource, ManageCmsFaqs, TrashCmsFaqs, KdsStationResource, ManageKdsStations (+7 more)

### Community 38 - "prop"
Cohesion: 0.06
Nodes (58): AQ(), atLastNode(), au(), child(), cursor(), cursorAt(), dX(), enter() (+50 more)

### Community 39 - "ModifierGroupResource"
Cohesion: 0.24
Nodes (4): ModifierGroupResource, ManageModifierGroups, Closure, TrashModifierGroups

### Community 40 - "CmsGalleryImageResource"
Cohesion: 0.27
Nodes (3): CmsGalleryImageResource, ManageCmsGalleryImages, TrashCmsGalleryImages

### Community 41 - "ExcelExporter"
Cohesion: 0.33
Nodes (4): ExcelExporter, Illuminate\Contracts\View\View, Maatwebsite\Excel\Concerns\FromView, Maatwebsite\Excel\Concerns\ShouldAutoSize

### Community 42 - "Ye"
Cohesion: 0.10
Nodes (43): Rd(), $a(), ak(), at(), bk(), c(), bp(), Dk() (+35 more)

### Community 43 - "Illuminate\Foundation\Http\FormRequest"
Cohesion: 0.09
Nodes (7): AddCartItemRequest, CheckoutRequest, ClaimTableRequest, JoinTableRequest, StorePaymentProofRequest, StoreRestaurantReviewRequest, Illuminate\Foundation\Http\FormRequest

### Community 44 - "fn"
Cohesion: 0.16
Nodes (21): Ck(), De(), fn(), Gh(), ip(), Ja(), Jh(), Ji() (+13 more)

### Community 45 - "notifications.js"
Cohesion: 0.06
Nodes (31): actions(), button(), c(), close(), configureAnimations(), configureTransitions(), constructor(), danger() (+23 more)

### Community 46 - "markdown-editor.js"
Cohesion: 0.05
Nodes (83): ad(), af(), An(), bf(), bo(), Bt(), cd(), Ct() (+75 more)

### Community 47 - "PlatformSetting"
Cohesion: 0.03
Nodes (21): WelcomeBannerWidget, PlatformPageController, RestaurantLandingController, RestaurantMenuCatalog, self, PlatformSetting, LandingPageDataService, FilamentTenantTheme (+13 more)

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

### Community 54 - "MenuCategoryResource"
Cohesion: 0.31
Nodes (3): MenuCategoryResource, ManageMenuCategories, TrashMenuCategories

### Community 55 - "r"
Cohesion: 0.15
Nodes (43): _a(), ar(), c(), f(), d(), di(), g(), Hi() (+35 more)

### Community 56 - "o"
Cohesion: 0.03
Nodes (168): $a(), addEventListener(), ag(), apply(), ar(), at(), au(), B() (+160 more)

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
Cohesion: 0.07
Nodes (42): a$(), activeForPoint(), addBlock(), addLineDeco(), b1(), blankContent(), boundChange(), commit() (+34 more)

### Community 63 - "Si"
Cohesion: 0.13
Nodes (41): ae(), At(), bi(), bn(), ci(), cn(), ct(), de() (+33 more)

### Community 64 - "RendersAnalyticsDashboard.php"
Cohesion: 0.09
Nodes (19): AnalyticsPeriodSummaryWidget, AnalyticsRevenueBarWidget, AnalyticsSidebarWidget, AnalyticsTopMenuWidget, generateChartDataChecksum(), getCachedChartData(), getChartData(), mountRefreshesAnalyticsChart() (+11 more)

### Community 65 - "ae"
Cohesion: 0.09
Nodes (34): ae(), Ao(), as(), B(), Kt(), cs(), Ee(), Ge() (+26 more)

### Community 66 - "selectOption"
Cohesion: 0.12
Nodes (39): addBadgesForSelectedOptions(), addSingleBadge(), addSingleSelectionDisplay(), closeDropdown(), constructor(), createBadgeElement(), createOptionElement(), createRemoveButton() (+31 more)

### Community 67 - "g$"
Cohesion: 0.03
Nodes (95): acceptToken(), allows(), aO(), ch(), charCategorizer(), childAfter(), childBefore(), cO() (+87 more)

### Community 68 - "CreateCashierOrder"
Cohesion: 0.07
Nodes (9): TemplateRadioPicker, CreateCashierOrder, BackedEnum, UnitEnum, Width, CashierMenuCatalog, Filament\Forms\Components\Field, Illuminate\Database\Eloquent\Collection (+1 more)

### Community 69 - "ir"
Cohesion: 0.13
Nodes (34): Ft(), ir(), ce(), de(), Dt(), ee(), Et(), fe() (+26 more)

### Community 70 - "ce"
Cohesion: 0.08
Nodes (46): Ac(), ao(), bl(), Cc(), ce(), cl(), Cn(), Dc() (+38 more)

### Community 71 - "slider.js"
Cohesion: 0.11
Nodes (33): ar(), Be(), Ce(), De(), _e(), Ee(), er(), et() (+25 more)

### Community 72 - "Restaurant"
Cohesion: 0.02
Nodes (52): ExpireStaleOperationsCommand, FinalizeCashierCommissionCommand, GenerateUpcomingInvoicesCommand, ProcessSubscriptionLifecycleCommand, SendCashierCommissionReminderCommand, analyticsDateFrom(), analyticsDateTo(), analyticsDayCount() (+44 more)

### Community 73 - "selectOption"
Cohesion: 0.15
Nodes (33): addSingleSelectionDisplay(), closeDropdown(), constructor(), createOptionElement(), deferPositionDropdown(), destroy(), filterOptions(), focusNextOption() (+25 more)

### Community 74 - "fn"
Cohesion: 0.13
Nodes (33): aa(), At(), ba(), cr(), da(), de(), dt(), ei() (+25 more)

### Community 75 - "facet"
Cohesion: 0.04
Nodes (64): accept(), activateHover(), applyTransaction(), asSingle(), baseDirAt(), bidiIn(), bidiSpans(), bidiSpansAt() (+56 more)

### Community 78 - "SubscriptionAccess"
Cohesion: 0.07
Nodes (10): canCreate(), canEdit(), canViewAny(), ManageCmsProfile, BackedEnum, UnitEnum, OutletResource, Action (+2 more)

### Community 79 - "file-upload.js"
Cohesion: 0.05
Nodes (53): hc(), Bp(), c(), ca(), clickPercent(), constructor(), Cp(), da() (+45 more)

### Community 81 - "create"
Cohesion: 0.06
Nodes (82): addAll(), addDOM(), addElement(), addElementByRule(), addNodeMark(), addTextNode(), addToSet(), ag() (+74 more)

### Community 82 - "Filament\Schemas\Schema"
Cohesion: 0.04
Nodes (23): ManageBillingAccount, BackedEnum, UnitEnum, ManageHomeLanding, BackedEnum, UnitEnum, ManagePlatformPages, BackedEnum (+15 more)

### Community 84 - "FonnteErrorMessage"
Cohesion: 0.11
Nodes (8): FonnteClient, FonnteErrorMessage, Throwable, Illuminate\Http\Client\ConnectionException, Illuminate\Http\Client\Response, PHPUnit\Framework\Attributes\DataProvider, RuntimeException, FonnteErrorMessageTest

### Community 85 - "toString"
Cohesion: 0.09
Nodes (31): Bc(), Bm(), check(), checkAttrs(), cn(), eat(), endIndex(), err() (+23 more)

### Community 89 - "AdminPanelProvider.php"
Cohesion: 0.11
Nodes (24): AdminPanelProvider, FounderPanelProvider, AuthGlass, BezhanSalleh\FilamentShield\FilamentShieldPlugin, Bityukov\CommandCenter\Filament\CommandCenterPlugin, Filament\Http\Middleware\Authenticate, Filament\Http\Middleware\AuthenticateSession, Filament\Http\Middleware\DisableBladeIconComponents (+16 more)

### Community 91 - "devDependencies"
Cohesion: 0.11
Nodes (18): axios, concurrently, laravel-vite-plugin, devDependencies, axios, concurrently, laravel-vite-plugin, tailwindcss (+10 more)

### Community 92 - "filament/app.js"
Cohesion: 0.13
Nodes (8): B(), close(), G(), init(), P(), setUpResizeObserver(), x(), Y()

### Community 93 - "toString"
Cohesion: 0.07
Nodes (38): addToSet(), bd(), between(), Bh(), childString(), clearDelayedAndroidKey(), d0(), De() (+30 more)

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

### Community 98 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.04
Nodes (19): canDelete(), canDeleteAny(), canForceDelete(), canRestore(), Action, trashPageAction(), FacilityResource, LandingTemplateResource (+11 more)

### Community 99 - "color-picker.js"
Cohesion: 0.14
Nodes (3): style(), update(), [x]()

### Community 100 - "js/app.js"
Cohesion: 0.22
Nodes (6): applyTheme(), bindThemeToggle(), initReveal(), initTheme(), syncThemeToggleIcons(), toggleTheme()

### Community 101 - "buildTicks"
Cohesion: 0.13
Nodes (20): afterAutoSkip(), Bf(), buildLookupTable(), buildTicks(), Fa(), _generate(), getDataTimestamps(), getDecimalForPixel() (+12 more)

### Community 103 - "composer.json"
Cohesion: 0.14
Nodes (13): autoload-dev, psr-4, description, keywords, license, minimum-stability, name, prefer-stable (+5 more)

### Community 106 - "GeoDistance"
Cohesion: 0.20
Nodes (4): GeoDistance, PHPUnit\Framework\TestCase, ExampleTest, GeoDistanceTest

### Community 107 - "date-time-picker.js"
Cohesion: 0.26
Nodes (8): d(), e(), i(), m(), r(), s(), t(), rr()

### Community 108 - "🚀 3. Langkah-Langkah Menambahkan Template Baru (*Workflow*)"
Cohesion: 0.12
Nodes (15): 📌 1. Prinsip Utama & Aturan Wajib (Golden Rules), 🏗️ 2. Arsitektur 10 Core Section CMS (1:1 Mapping), 🚀 3. Langkah-Langkah Menambahkan Template Baru (*Workflow*), 📊 4. Variabel yang Disediakan oleh `LandingPageDataService`, 💎 5. Pelajaran Desain & Best Practices UI/UX (*Key Learnings*), A. Larangan Keras Menggunakan Emoji Unicode (Gunakan SVG Heroicons), B. Arsitektur Layering 3D & Background Tembus Pandang (*Stacking Context*), C. Resep Glassmorphism iOS yang Nyata (*True Frosted Glass*) (+7 more)

### Community 110 - "Mt"
Cohesion: 0.24
Nodes (11): apply(), fs(), go(), Hr(), T(), ir(), it(), Mt() (+3 more)

### Community 111 - "Login"
Cohesion: 0.24
Nodes (4): Login, Filament\Auth\Pages\Login, Filament\Schemas\Components\Component, Illuminate\Contracts\Support\Htmlable

### Community 115 - "actions/actions.js"
Cohesion: 0.44
Nodes (8): closeModal(), generateModalId(), getActionNestingIndexFromModalId(), init(), openModal(), rememberPreviouslyFocusedElement(), restorePreviouslyFocusedElement(), syncActionModals()

### Community 118 - "Landasan Produksi — Tahap 1 (Walk-In Operasional)"
Cohesion: 0.05
Nodes (37): 10. Aturan yang tidak boleh dilanggar, 11. Matriks skenario lapangan, 12. Yang sengaja tidak didukung (bukan gap, non-goal), 13. Tahap 2 (bukan sekarang), 14. Urutan bangun (agar cepat produksi), 1. Keputusan terkunci, 2. Modul tahap 1 vs ditunda, 3. Model domain (+29 more)

### Community 119 - "Guest"
Cohesion: 0.05
Nodes (34): Bentuk objek, CartItemResource, DELETE `/guest/cart/items/{cartItem}`, GET `/guest/cart`, GET `/guest/menu`, GET `/guest/orders`, GET `/guest/orders/{public_id}`, GET `/guest/review` (+26 more)

### Community 120 - "O"
Cohesion: 0.19
Nodes (38): b(), $c(), X(), ca(), me(), D(), _e(), Ea() (+30 more)

### Community 121 - "2. Masalah di lapangan — dan apa yang sistem selesaikan"
Cohesion: 0.05
Nodes (41): 1. Cerita yang mungkin terasa familiar, 2.10 Struk kertas hilang, tamu minta dikirim WhatsApp, 2.11 Tampilan website restoran kaku atau tidak sesuai konsep resto, 2.12 Calon tamu ingin lihat menu lengkap sebelum datang ke resto, 2.13 Foto menu yang diupload staf ukurannya raksasa bikin web lemot, 2.14 Owner dan kasir ingin tahu performa hari ini secara instan, 2.15 Tak sengaja hapus menu atau meja saat jam sibuk, 2.16 Sulit ditemukan calon tamu baru di internet (+33 more)

### Community 123 - "require-dev"
Cohesion: 0.25
Nodes (8): require-dev, fakerphp/faker, laravel/pail, laravel/pint, laravel/sail, mockery/mockery, nunomaduro/collision, phpunit/phpunit

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

### Community 134 - "psr-4"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 135 - "extra"
Cohesion: 0.40
Nodes (5): dev-master, extra, branch-alias, laravel, dont-discover

### Community 136 - "logging.php"
Cohesion: 0.40
Nodes (4): Monolog\Handler\NullHandler, Monolog\Handler\StreamHandler, Monolog\Handler\SyslogUdpHandler, Monolog\Processor\PsrLogMessageProcessor

### Community 139 - "GuestContext"
Cohesion: 0.03
Nodes (34): CartController, CheckoutController, MenuController, ReviewController, SessionController, TableController, VisitController, RestaurantController (+26 more)

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

### Community 153 - "draw"
Cohesion: 0.03
Nodes (161): acquireContext(), adjustHitBoxes(), af(), Ao(), applyStack(), aspectRatio(), bh(), br() (+153 more)

### Community 158 - "cc"
Cohesion: 0.12
Nodes (18): attrs(), AX(), bi(), cc(), combine(), configure(), extend(), gQ() (+10 more)

### Community 164 - "GraceReadOnlyTest"
Cohesion: 0.16
Nodes (4): BlockGraceMutations, SubscriptionWriteGuard, Livewire\ComponentHook, GraceReadOnlyTest

### Community 180 - "dx"
Cohesion: 0.09
Nodes (38): Ei(), Aa(), ai(), Ba(), Bi(), cf(), da(), fa() (+30 more)

### Community 193 - "restaurant-menu-catalog.blade.php"
Cohesion: 0.25
Nodes (7): landing.templates.foodie.sections.footer, landing.templates.foodie.sections.header, landing.templates.glassmorphism.partials.background, landing.templates.glassmorphism.sections.footer, landing.templates.glassmorphism.sections.header, partials.customer.landing-footer, partials.customer.landing-header

### Community 295 - "3. Detail Implementasi Perbaikan Keamanan"
Cohesion: 0.17
Nodes (11): 1. Ringkasan Eksekutif (Executive Summary), 2. Matriks Temuan & Status Perbaikan (Findings & Remediation Matrix), 3. Detail Implementasi Perbaikan Keamanan, 4. Hasil Verifikasi Pengujian Otomatis, A. Proteksi `qr_secret` pada Model (`SEC-01`), B. Middleware HTTP Security Headers (`SEC-02`), C. Pengetatan CORS & Session Cookie (`SEC-03` & `SEC-05`), D. Sanitasi File Upload (`SEC-06`) (+3 more)

### Community 296 - "foodie/show.blade.php"
Cohesion: 0.33
Nodes (5): landing.templates.foodie.sections., landing.templates.foodie.sections.hero, landing.sections., landing.templates.foodie.sections.footer, landing.templates.foodie.sections.header

### Community 301 - "classic/show.blade.php"
Cohesion: 0.50
Nodes (3): landing.sections., partials.customer.landing-footer, partials.customer.landing-header

### Community 322 - "glassmorphism/show.blade.php"
Cohesion: 0.29
Nodes (6): landing.templates.glassmorphism.sections., landing.templates.glassmorphism.sections.hero, landing.sections., landing.templates.glassmorphism.partials.background, landing.templates.glassmorphism.sections.footer, landing.templates.glassmorphism.sections.header

### Community 324 - "CashierOrderPreview"
Cohesion: 0.19
Nodes (3): CashierOrderPreview, CheckoutTotals, CashierOrderPreviewTest

### Community 327 - "addSingleBadge"
Cohesion: 0.33
Nodes (6): addBadgesForSelectedOptions(), addSingleBadge(), createBadgeElement(), createRemoveButton(), getLabelForSingleSelection(), getSelectedOptionLabel()

### Community 340 - "SubscriptionInvoice"
Cohesion: 0.08
Nodes (6): FounderStatsWidget, SubscriptionInvoice, SubscriptionInvoiceService, Filament\Widgets\StatsOverviewWidget, Filament\Widgets\StatsOverviewWidget\Stat, CashierCommissionBillingTest

### Community 348 - "st"
Cohesion: 0.24
Nodes (11): [g](), _freeze(), getAllExtensions(), Ct(), lt(), ot(), se(), st() (+3 more)

### Community 349 - "N"
Cohesion: 0.33
Nodes (11): ae(), A(), E(), at(), be(), Gt(), i(), Jt() (+3 more)

### Community 356 - "Ae"
Cohesion: 0.67
Nodes (3): Ae(), Bt(), ne()

### Community 358 - "MenuItem"
Cohesion: 0.04
Nodes (15): RestaurantMenuController, MenuCategoryResource, MenuCategory, MenuItem, LogOptions, MenuItemPhoto, MenuSearch, extraMenuItem() (+7 more)

## Knowledge Gaps
- **340 isolated node(s):** `$schema`, `name`, `type`, `description`, `laravel` (+335 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **40 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `_s()` connect `components/chart.js` to `rich-editor.js`?**
  _High betweenness centrality (0.029) - this node is a cross-community bridge._
- **Why does `Wi()` connect `Ye` to `code-editor.js`, `rich-editor.js`, `constructor`, `components/select.js`, `of`, `columns/select.js`?**
  _High betweenness centrality (0.023) - this node is a cross-community bridge._
- **Why does `update()` connect `constructor` to `stat/chart.js`, `components/chart.js`, `code-editor.js`, `rich-editor.js`, `r`, `slice`, `get`, `advance`, `n`, `of`, `reduce`, `echo.js`, `resolve`, `prop`, `Ye`, `markdown-editor.js`, `te`, `dx`, `t`, `g$`, `facet`, `toString`, `O`?**
  _High betweenness centrality (0.022) - this node is a cross-community bridge._
- **Are the 18 inferred relationships involving `constructor()` (e.g. with `a()` and `h()`) actually correct?**
  _`constructor()` has 18 INFERRED edges - model-reasoned connections that need verification._
- **Are the 26 inferred relationships involving `update()` (e.g. with `Pr()` and `a()`) actually correct?**
  _`update()` has 26 INFERRED edges - model-reasoned connections that need verification._
- **What connects `$schema`, `name`, `type` to the rest of the system?**
  _340 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `stat/chart.js` be split into smaller, more focused modules?**
  _Cohesion score 0.010864923518499072 - nodes in this community are weakly interconnected._