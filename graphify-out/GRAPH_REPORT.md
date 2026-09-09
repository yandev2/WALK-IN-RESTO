# Graph Report - WALK-IN-RESTO  (2026-09-09)

## Corpus Check
- 646 files · ~313,697 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 8654 nodes · 26668 edges · 338 communities (298 shown, 40 thin omitted)
- Extraction: 91% EXTRACTED · 9% INFERRED · 0% AMBIGUOUS · INFERRED: 2461 edges (avg confidence: 0.85)
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
- Illuminate\Http\Request
- TenantContext
- r
- slice
- User
- Filament\Resources\Pages\ListRecords
- _update
- Order
- Filament\Resources\Pages\CreateRecord
- GuestPay
- get
- Filament\Support\Icons\Heroicon
- advance
- Oc
- RestaurantCategory
- AppServiceProvider.php
- ExportFile
- support.js
- n
- Je
- of
- columns/select.js
- .slice
- reduce
- echo.js
- resolve
- OrderResource
- ActivityPresenter
- SoftDeleteTrashPage
- constructor
- prop
- DiningTable
- Filament\Tables\Table
- Illuminate\Database\Eloquent\Builder
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
- GuestContext
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
- from
- Filament\Schemas\Schema
- RestaurantDirectory
- FonnteErrorMessage
- Im
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
- Pe
- actions/actions.js
- VisitCartItem
- schemas.js
- Landasan Produksi — Tahap 1 (Walk-In Operasional)
- Guest
- ExportFileResource
- 2. Masalah di lapangan — dan apa yang sistem selesaikan
- nearestDesc
- require-dev
- 6. Katalog fitur
- filament-shield.php
- config
- 6. Katalog fitur
- EditProfile
- register-restaurant.blade.php
- add-to-cart-modal.blade.php
- ReviewController.php
- components/actions.js
- psr-4
- extra
- logging.php
- RegisterRestaurant
- Illuminate\Http\JsonResponse
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
- FounderStatsWidget.php
- SubscriptionWriteGuard
- SitemapController.php
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
- CashierFilamentActionsTest
- addSingleBadge
- SubscriptionInvoice
- glassmorphism-background.blade.php
- st
- dropdown.blade.php
- ut
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
10. `PlatformSetting` - 90 edges

## Surprising Connections (you probably didn't know these)
- `up()` --calls--> `DiningTable`  [EXTRACTED]
  database/migrations/2026_08_20_010000_add_floor_layout_to_tables_table.php → app/Models/DiningTable.php
- `createGuestRestaurant()` --calls--> `DiningTable`  [EXTRACTED]
  tests/Concerns/CreatesGuestRestaurant.php → app/Models/DiningTable.php
- `createGuestRestaurant()` --calls--> `MenuCategory`  [EXTRACTED]
  tests/Concerns/CreatesGuestRestaurant.php → app/Models/MenuCategory.php
- `createGuestRestaurant()` --calls--> `MenuItem`  [EXTRACTED]
  tests/Concerns/CreatesGuestRestaurant.php → app/Models/MenuItem.php
- `extraMenuItem()` --calls--> `MenuItem`  [EXTRACTED]
  tests/Concerns/CreatesGuestRestaurant.php → app/Models/MenuItem.php

## Import Cycles
- None detected.

## Communities (338 total, 40 thin omitted)

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
Nodes (178): _0(), aa(), addAttributes(), addHackNode(), addTextblockHacks(), applyAspectRatio(), applyConstraints(), atEnd() (+170 more)

### Community 4 - "Illuminate\Database\Eloquent\Relations\BelongsTo"
Cohesion: 0.02
Nodes (44): CmsBanner, LogOptions, CmsFaq, LogOptions, CmsGalleryImage, CmsProfile, LogOptions, restaurant() (+36 more)

### Community 5 - "y"
Cohesion: 0.16
Nodes (71): at(), b(), Be(), $c(), X(), me(), Cr(), Ct() (+63 more)

### Community 6 - "TestCase"
Cohesion: 0.03
Nodes (54): CashierOrderService, ImageOptimizer, BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles, Bityukov\CommandCenter\Filament\Pages\Commands, Bityukov\CommandCenter\Filament\Pages\History, RolePermissionSeeder, Filament\Facades\Filament, Illuminate\Database\QueryException (+46 more)

### Community 7 - "constructor"
Cohesion: 0.03
Nodes (144): add(), addChunk(), addEventListener(), addInfoPane(), addInner(), addWindowListeners(), adjust(), al() (+136 more)

### Community 8 - "Illuminate\Http\Request"
Cohesion: 0.04
Nodes (33): OrderReceiptPrintController, ApplyPlatformBrandTheme, ApplyRestaurantPanelTheme, EnsureApiGuestVisit, EnsureGuestVisit, EnsureRestaurantOperations, EnsureTenantSubscription, IdentifyApiGuestDevice (+25 more)

### Community 9 - "TenantContext"
Cohesion: 0.09
Nodes (7): ManageDiningTables, Action, Closure, bootBelongsToRestaurantAndOutlet(), BelongsToRestaurantScope, TenantContext, Illuminate\Database\Eloquent\Scope

### Community 10 - "r"
Cohesion: 0.04
Nodes (127): add(), addNodeView(), addOptions(), addProseMirrorPlugins(), af(), au(), Cc(), cf() (+119 more)

### Community 11 - "slice"
Cohesion: 0.05
Nodes (128): addElement(), Ah(), balanced(), baseIndentFor(), be(), Bg(), a(), blockAt() (+120 more)

### Community 12 - "User"
Cohesion: 0.02
Nodes (30): AnalyticsKpiWidget, Role, LogOptions, User, ExportFilePolicy, RolePolicy, UserPolicy, Filament\Models\Contracts\FilamentUser (+22 more)

### Community 13 - "Filament\Resources\Pages\ListRecords"
Cohesion: 0.07
Nodes (13): ListFacilities, ListLandingTemplates, ListSubscriptionInvoices, EditSubscriptionPlan, ListSubscriptionPlans, SubscriptionPlanResource, ListTenants, ListMenuItems (+5 more)

### Community 14 - "_update"
Cohesion: 0.03
Nodes (122): active(), addBox(), addElements(), afterBuildTicks(), afterCalculateLabelRotation(), afterDataLimits(), afterDatasetsUpdate(), afterDraw() (+114 more)

### Community 15 - "Order"
Cohesion: 0.03
Nodes (27): Order, OrderItem, Payment, Visit, VisitDevice, GuestCheckoutService, KdsItemService, MenuModifierService (+19 more)

### Community 16 - "Filament\Resources\Pages\CreateRecord"
Cohesion: 0.07
Nodes (14): CreateFacility, EditFacility, CreateLandingTemplate, EditLandingTemplate, CreateTenant, EditTenant, CreateMenuItem, EditMenuItem (+6 more)

### Community 17 - "GuestPay"
Cohesion: 0.20
Nodes (5): OrderController, GuestPay, PaymentProofService, Livewire\Features\SupportFileUploads\TemporaryUploadedFile, Livewire\WithFileUploads

### Community 18 - "get"
Cohesion: 0.03
Nodes (108): addBlockWidget(), addBreak(), addComposition(), addDelimiter(), addInlineWidget(), addLine(), addLineStart(), addLineStartIfNotCovered() (+100 more)

### Community 19 - "Filament\Support\Icons\Heroicon"
Cohesion: 0.10
Nodes (35): IdrAmount, Filament\Actions\Action, Filament\Forms\Components\ColorPicker, Filament\Forms\Components\Component, Filament\Forms\Components\FileUpload, Filament\Forms\Components\Hidden, Filament\Forms\Components\Repeater, Filament\Forms\Components\RichEditor (+27 more)

### Community 20 - "advance"
Cohesion: 0.05
Nodes (65): addChild(), addGaps(), addLeafElement(), addNode(), advance(), ATXHeading(), blank(), break() (+57 more)

### Community 21 - "Oc"
Cohesion: 0.15
Nodes (20): ao(), append(), checkContent(), co(), computeAttrs(), createChecked(), fromArray(), hasMarkup() (+12 more)

### Community 22 - "RestaurantCategory"
Cohesion: 0.06
Nodes (5): RestaurantCategory, RestaurantCategorySeeder, RestaurantDirectoryTest, RestaurantRegistrationStepperTest, TenantIsolationTest

### Community 23 - "AppServiceProvider.php"
Cohesion: 0.06
Nodes (23): Dashboard, Width, AppServiceProvider, Filament\Actions\DeleteBulkAction, Filament\Actions\ForceDeleteAction, Filament\Actions\ForceDeleteBulkAction, Filament\Actions\RestoreAction, Filament\Actions\RestoreBulkAction (+15 more)

### Community 24 - "ExportFile"
Cohesion: 0.04
Nodes (19): PdfExporter, CleanupOldExportFilesJob, ExportReportJob, SendWhatsappReceiptJob, ExportFile, OrderReceipt, WhatsappMessage, ExportFileObserver (+11 more)

### Community 25 - "support.js"
Cohesion: 0.05
Nodes (75): acquireScrollLock(), ai(), e(), Bi(), br(), Bt(), ca(), close() (+67 more)

### Community 26 - "n"
Cohesion: 0.09
Nodes (80): _a(), Ae(), ar(), as(), Ba(), bc(), bf(), ee() (+72 more)

### Community 27 - "Je"
Cohesion: 0.07
Nodes (46): addGlobalAttributes(), addInputRules(), addMark(), addPasteRules(), addStoredMark(), Ah(), Ax(), _c() (+38 more)

### Community 28 - "of"
Cohesion: 0.04
Nodes (70): active(), apply(), B(), baseTheme(), blur(), bu(), checkAsyncSchedule(), define() (+62 more)

### Community 29 - "columns/select.js"
Cohesion: 0.06
Nodes (57): A(), An(), applyDisabledState(), b(), Bt(), Ce(), D(), disable() (+49 more)

### Community 30 - ".slice"
Cohesion: 0.03
Nodes (90): accepts(), addInner(), addMaps(), addStep(), addTransform(), ak(), appendMap(), appendMapping() (+82 more)

### Community 31 - "reduce"
Cohesion: 0.08
Nodes (46): addActions(), advanceFully(), advanceStack(), allActions(), c0(), canShift(), close(), deadEnd() (+38 more)

### Community 32 - "echo.js"
Cohesion: 0.05
Nodes (49): a(), ar(), b(), Be(), Ce(), cr(), d(), De() (+41 more)

### Community 33 - "resolve"
Cohesion: 0.05
Nodes (154): Ac(), addCommands(), addKeyboardShortcuts(), after(), al(), AS(), before(), blockRange() (+146 more)

### Community 34 - "OrderResource"
Cohesion: 0.07
Nodes (12): OrderResource, ListOrders, ViewOrder, OrderTodayStatsWidget, PendingPaymentsWidget, Carbon\Carbon, Filament\Actions\ViewAction, Filament\Infolists\Components\ImageEntry (+4 more)

### Community 35 - "ActivityPresenter"
Cohesion: 0.11
Nodes (5): ActivityResource, ListActivities, ViewActivity, ActivityPresenter, Filament\Resources\Pages\ViewRecord

### Community 36 - "SoftDeleteTrashPage"
Cohesion: 0.03
Nodes (23): SoftDeleteTrashPage, ManageCmsBanners, TrashCmsBanners, ManageCmsFaqs, TrashCmsFaqs, ManageCmsGalleryImages, TrashCmsGalleryImages, TrashDiningTables (+15 more)

### Community 37 - "constructor"
Cohesion: 0.03
Nodes (120): Ad(), addExtensions(), addNode(), applyInitialSize(), ay(), Bd(), Bg(), Bo() (+112 more)

### Community 38 - "prop"
Cohesion: 0.06
Nodes (58): AQ(), atLastNode(), au(), child(), cursor(), cursorAt(), dX(), enter() (+50 more)

### Community 39 - "DiningTable"
Cohesion: 0.04
Nodes (19): periodSummary(), Activity, bootScopedToRestaurant(), scopeForRestaurant(), scopeWithoutRestaurantScope(), DiningTable, LogOptions, RestaurantDirectory (+11 more)

### Community 40 - "Filament\Tables\Table"
Cohesion: 0.15
Nodes (27): ActivitiesTable, CmsBannerResource, CmsFaqResource, CmsGalleryImageResource, DiningTableResource, KdsStationResource, MenuCategoryResource, MenuItemResource (+19 more)

### Community 41 - "Illuminate\Database\Eloquent\Builder"
Cohesion: 0.08
Nodes (9): getRecordRouteBindingEloquentQuery(), ExcelExporter, KitchenDisplay, BackedEnum, UnitEnum, Illuminate\Contracts\View\View, Illuminate\Database\Eloquent\Builder, Maatwebsite\Excel\Concerns\FromView (+1 more)

### Community 42 - "Ye"
Cohesion: 0.10
Nodes (42): Rd(), $a(), at(), bk(), c(), bp(), Cp(), Dk() (+34 more)

### Community 43 - "Illuminate\Foundation\Http\FormRequest"
Cohesion: 0.08
Nodes (9): TableController, AddCartItemRequest, CheckoutRequest, ClaimTableRequest, JoinTableRequest, StorePaymentProofRequest, UpdateCartItemRequest, TableScanService (+1 more)

### Community 44 - "fn"
Cohesion: 0.17
Nodes (21): Ck(), De(), fn(), p(), Gh(), ip(), Ja(), Jh() (+13 more)

### Community 45 - "notifications.js"
Cohesion: 0.06
Nodes (31): actions(), button(), c(), close(), configureAnimations(), configureTransitions(), constructor(), danger() (+23 more)

### Community 46 - "markdown-editor.js"
Cohesion: 0.05
Nodes (85): ad(), af(), ai(), al(), An(), ao(), bo(), br() (+77 more)

### Community 47 - "PlatformSetting"
Cohesion: 0.02
Nodes (29): WelcomeBannerWidget, PlatformPageController, RestaurantLandingController, RestaurantResource, RestaurantSummaryResource, RestaurantMenuCatalog, self, PlatformSetting (+21 more)

### Community 48 - "te"
Cohesion: 0.05
Nodes (11): Bn(), Id(), ji(), on(), qd(), qi(), Ri(), te() (+3 more)

### Community 49 - "Cn"
Cohesion: 0.13
Nodes (46): Cn(), b(), Be(), Ce(), De(), dn(), _e(), Fe() (+38 more)

### Community 51 - "components/select.js"
Cohesion: 0.08
Nodes (38): A(), applyDisabledState(), b(), Bt(), D(), disable(), E(), en() (+30 more)

### Community 53 - "tables.js"
Cohesion: 0.13
Nodes (44): A(), ae(), B(), be(), C(), ce(), E(), ee() (+36 more)

### Community 54 - "GuestContext"
Cohesion: 0.12
Nodes (9): ExportFileDownloadController, OrderReceiptDownloadController, GuestCheckout, GuestReview, GuestStatus, ScanTable, RestaurantReviewService, GuestContext (+1 more)

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
Cohesion: 0.14
Nodes (30): ir(), at(), be(), ce(), Ct(), de(), Dt(), ee() (+22 more)

### Community 70 - "ce"
Cohesion: 0.09
Nodes (41): Ac(), bl(), Cc(), ce(), cl(), Dc(), Do(), Ec() (+33 more)

### Community 71 - "slider.js"
Cohesion: 0.11
Nodes (32): ar(), Be(), Ce(), De(), _e(), Ee(), er(), Fe() (+24 more)

### Community 72 - "Restaurant"
Cohesion: 0.03
Nodes (44): ExpireStaleOperationsCommand, FinalizeCashierCommissionCommand, GenerateUpcomingInvoicesCommand, ProcessSubscriptionLifecycleCommand, SendCashierCommissionReminderCommand, analyticsDateFrom(), analyticsDateTo(), analyticsDayCount() (+36 more)

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

### Community 81 - "from"
Cohesion: 0.06
Nodes (85): addAll(), addDOM(), addElement(), addElementByRule(), addNodeMark(), addTextNode(), addToSet(), ag() (+77 more)

### Community 82 - "Filament\Schemas\Schema"
Cohesion: 0.04
Nodes (18): ManageBillingAccount, BackedEnum, UnitEnum, ManageHomeLanding, BackedEnum, UnitEnum, ManagePlatformPages, BackedEnum (+10 more)

### Community 83 - "RestaurantDirectory"
Cohesion: 0.11
Nodes (3): RestaurantDirectory, Livewire\Attributes\Computed, Livewire\WithPagination

### Community 84 - "FonnteErrorMessage"
Cohesion: 0.11
Nodes (8): FonnteClient, FonnteErrorMessage, Throwable, Illuminate\Http\Client\ConnectionException, Illuminate\Http\Client\Response, PHPUnit\Framework\Attributes\DataProvider, RuntimeException, FonnteErrorMessageTest

### Community 85 - "Im"
Cohesion: 0.36
Nodes (9): Bm(), eat(), err(), Im(), Lm(), o1(), pc(), Pm() (+1 more)

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
Cohesion: 0.03
Nodes (22): canDelete(), canDeleteAny(), canForceDelete(), canRestore(), Action, trashPageAction(), FacilityResource, LandingTemplateResource (+14 more)

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

### Community 113 - "Pe"
Cohesion: 0.12
Nodes (32): cd(), dd(), dt(), Ft(), gl(), _i(), Ie(), it() (+24 more)

### Community 115 - "actions/actions.js"
Cohesion: 0.44
Nodes (8): closeModal(), generateModalId(), getActionNestingIndexFromModalId(), init(), openModal(), rememberPreviouslyFocusedElement(), restorePreviouslyFocusedElement(), syncActionModals()

### Community 116 - "VisitCartItem"
Cohesion: 0.12
Nodes (5): GuestCart, GuestMenu, VisitCartItem, GuestCartService, Livewire\Attributes\Layout

### Community 118 - "Landasan Produksi — Tahap 1 (Walk-In Operasional)"
Cohesion: 0.05
Nodes (37): 10. Aturan yang tidak boleh dilanggar, 11. Matriks skenario lapangan, 12. Yang sengaja tidak didukung (bukan gap, non-goal), 13. Tahap 2 (bukan sekarang), 14. Urutan bangun (agar cepat produksi), 1. Keputusan terkunci, 2. Modul tahap 1 vs ditunda, 3. Model domain (+29 more)

### Community 119 - "Guest"
Cohesion: 0.05
Nodes (34): Bentuk objek, CartItemResource, DELETE `/guest/cart/items/{cartItem}`, GET `/guest/cart`, GET `/guest/menu`, GET `/guest/orders`, GET `/guest/orders/{public_id}`, GET `/guest/review` (+26 more)

### Community 120 - "ExportFileResource"
Cohesion: 0.12
Nodes (6): GenerateReport, BackedEnum, UnitEnum, ExportFileResource, ListExportFiles, TrashExportFiles

### Community 121 - "2. Masalah di lapangan — dan apa yang sistem selesaikan"
Cohesion: 0.05
Nodes (41): 1. Cerita yang mungkin terasa familiar, 2.10 Struk kertas hilang, tamu minta dikirim WhatsApp, 2.11 Tampilan website restoran kaku atau tidak sesuai konsep resto, 2.12 Calon tamu ingin lihat menu lengkap sebelum datang ke resto, 2.13 Foto menu yang diupload staf ukurannya raksasa bikin web lemot, 2.14 Owner dan kasir ingin tahu performa hari ini secara instan, 2.15 Tak sengaja hapus menu atau meja saat jam sibuk, 2.16 Sulit ditemukan calon tamu baru di internet (+33 more)

### Community 122 - "nearestDesc"
Cohesion: 0.12
Nodes (20): an(), $b(), bl(), $g(), getDesc(), has(), ignoreMutation(), ignoreSelectionChange() (+12 more)

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

### Community 132 - "ReviewController.php"
Cohesion: 0.18
Nodes (4): ReviewController, StoreRestaurantReviewRequest, RestaurantReviewResource, VisitReviewStatus

### Community 134 - "psr-4"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 135 - "extra"
Cohesion: 0.40
Nodes (5): dev-master, extra, branch-alias, laravel, dont-discover

### Community 136 - "logging.php"
Cohesion: 0.40
Nodes (4): Monolog\Handler\NullHandler, Monolog\Handler\StreamHandler, Monolog\Handler\SyslogUdpHandler, Monolog\Processor\PsrLogMessageProcessor

### Community 139 - "Illuminate\Http\JsonResponse"
Cohesion: 0.11
Nodes (13): CartController, CheckoutController, MenuController, SessionController, VisitController, RestaurantController, RestaurantMenuController, RestaurantReviewController (+5 more)

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

### Community 163 - "FounderStatsWidget.php"
Cohesion: 0.50
Nodes (3): FounderStatsWidget, Filament\Widgets\StatsOverviewWidget, Filament\Widgets\StatsOverviewWidget\Stat

### Community 164 - "SubscriptionWriteGuard"
Cohesion: 0.28
Nodes (3): BlockGraceMutations, SubscriptionWriteGuard, Livewire\ComponentHook

### Community 180 - "dx"
Cohesion: 0.14
Nodes (23): Ei(), Aa(), Bi(), ca(), da(), fa(), Gr(), ki() (+15 more)

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

### Community 327 - "addSingleBadge"
Cohesion: 0.33
Nodes (6): addBadgesForSelectedOptions(), addSingleBadge(), createBadgeElement(), createRemoveButton(), getLabelForSingleSelection(), getSelectedOptionLabel()

### Community 340 - "SubscriptionInvoice"
Cohesion: 0.09
Nodes (3): SubscriptionInvoice, SubscriptionInvoiceService, CashierCommissionBillingTest

### Community 348 - "st"
Cohesion: 0.21
Nodes (12): [g](), _freeze(), getAllExtensions(), ae(), A(), E(), lt(), ot() (+4 more)

### Community 356 - "ut"
Cohesion: 0.17
Nodes (16): Ae(), Bt(), et(), Ft(), fe(), ft(), Jt(), le() (+8 more)

### Community 358 - "MenuItem"
Cohesion: 0.04
Nodes (12): MenuCategory, MenuItem, LogOptions, extraMenuItem(), ExportReportTest, GuestMenuTest, LandingMenuCatalogTest, LandingMenuHiddenPriceTest (+4 more)

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
- **Why does `update()` connect `constructor` to `stat/chart.js`, `components/chart.js`, `code-editor.js`, `rich-editor.js`, `y`, `r`, `slice`, `get`, `advance`, `n`, `of`, `.slice`, `reduce`, `echo.js`, `constructor`, `prop`, `Ye`, `markdown-editor.js`, `te`, `dx`, `t`, `g$`, `ce`, `facet`, `toString`?**
  _High betweenness centrality (0.022) - this node is a cross-community bridge._
- **Are the 18 inferred relationships involving `constructor()` (e.g. with `a()` and `h()`) actually correct?**
  _`constructor()` has 18 INFERRED edges - model-reasoned connections that need verification._
- **Are the 26 inferred relationships involving `update()` (e.g. with `Pr()` and `a()`) actually correct?**
  _`update()` has 26 INFERRED edges - model-reasoned connections that need verification._
- **What connects `$schema`, `name`, `type` to the rest of the system?**
  _340 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `stat/chart.js` be split into smaller, more focused modules?**
  _Cohesion score 0.010864923518499072 - nodes in this community are weakly interconnected._