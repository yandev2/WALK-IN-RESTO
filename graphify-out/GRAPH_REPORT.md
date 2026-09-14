# Graph Report - WALK-IN-RESTO  (2026-09-14)

## Corpus Check
- 666 files · ~327,957 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 8778 nodes · 27111 edges · 370 communities (328 shown, 42 thin omitted)
- Extraction: 91% EXTRACTED · 9% INFERRED · 0% AMBIGUOUS · INFERRED: 2471 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `6c100810`
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
- _update
- sameMarkup
- getContext
- slice
- User
- Visit
- _update
- Order
- constructor
- SubscriptionAccess
- get
- o
- finish
- Illuminate\Foundation\Http\FormRequest
- AdminPanelProvider.php
- OrderResource
- ExportFile
- support.js
- n
- SoftDeleteTrashPage
- of
- Y
- .slice
- advance
- echo.js
- resolve
- DiningTable
- s
- BackedEnum
- e
- g$
- GuestContext
- Dashboard
- ExcelExporter
- Ye
- EditProfile
- KitchenDisplay
- notifications.js
- markdown-editor.js
- PlatformSetting
- te
- Cn
- components/select.js
- updateElements
- tables.js
- Filament\Actions\Action
- r
- ir
- SubscriptionStatus
- Xt
- 2026_08_20_010000_add_floor_layout_to_tables_table.php
- filament-right-click.js
- E
- t
- Si
- CmsMedia
- ae
- columns/select.js
- Filament\Schemas\Schema
- CreateCashierOrder
- ir
- ce
- slider.js
- Restaurant
- selectOption
- fn
- find
- InvoicePaymentTest
- static
- file-upload.js
- from
- nodesBetween
- RestaurantDirectory
- FonnteErrorMessage
- A
- getContext
- devDependencies
- filament/app.js
- sliceDoc
- fn
- Sl
- require
- scripts
- ExportFileResource
- color-picker.js
- js/app.js
- TenantContext
- composer.json
- order-today-stats-widget.blade.php
- GeoDistance
- date-time-picker.js
- 🚀 3. Langkah-Langkah Menambahkan Template Baru (*Workflow*)
- r
- Mt
- getDatasetMeta
- S
- fn
- Filament\Tables\Table
- actions/actions.js
- MenuItemResource
- schemas.js
- Landasan Produksi — Tahap 1 (Walk-In Operasional)
- Guest
- O
- 2. Masalah di lapangan — dan apa yang sistem selesaikan
- Illuminate\Database\Eloquent\Builder
- require-dev
- SubscriptionPlan
- 6. Katalog fitur
- _generate
- config
- 6. Katalog fitur
- Login
- register-restaurant.blade.php
- add-to-cart-modal.blade.php
- getProps
- components/actions.js
- psr-4
- extra
- logging.php
- fromDateTimes
- AppServiceProvider.php
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
- fo
- closeSimpleModeModal
- Illuminate\Support\Facades\Schema
- Illuminate\Database\Schema\Blueprint
- cc
- Illuminate\Database\Migrations\Migration
- FilamentTenantTheme
- addElementByRule
- SubscriptionWriteGuard
- RegisterRestaurant
- draw
- fromObject
- filament-shield.php
- 🎨 Spesifikasi Token & Class CSS yang Tersedia di `theme.css`
- dx
- fn
- RestaurantCategory
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
- toString
- classic/show.blade.php
- P
- rules/graphify.md
- workflows/graphify.md
- parse
- ManageSoundNotifications
- SubscriptionInvoiceService
- glassmorphism/show.blade.php
- CommissionReconciliation
- _each
- addSingleBadge
- SubscriptionInvoice
- glassmorphism-background.blade.php
- ManageBillingAccount
- N
- st
- RefreshesAnalyticsChart.php
- dropdown.blade.php
- iy
- OrderReceiptPrintTest
- Ae
- SoftDeleteTrashTest
- UserPolicy
- CashierOrderSoundAlertTest
- FounderStatsWidget.php
- GraceReadOnlyTest
- GuestMenuTest

## God Nodes (most connected - your core abstractions)
1. `Restaurant` - 306 edges
2. `User` - 269 edges
3. `TestCase` - 163 edges
4. `constructor()` - 152 edges
5. `update()` - 148 edges
6. `Order` - 141 edges
7. `MenuItem` - 107 edges
8. `PlatformSetting` - 105 edges
9. `resolve()` - 94 edges
10. `DiningTable` - 93 edges

## Surprising Connections (you probably didn't know these)
- `up()` --calls--> `DiningTable`  [EXTRACTED]
  database/migrations/2026_08_20_010000_add_floor_layout_to_tables_table.php → app/Models/DiningTable.php
- `createGuestRestaurant()` --calls--> `DiningTable`  [EXTRACTED]
  tests/Concerns/CreatesGuestRestaurant.php → app/Models/DiningTable.php
- `extraMenuItem()` --calls--> `MenuItem`  [EXTRACTED]
  tests/Concerns/CreatesGuestRestaurant.php → app/Models/MenuItem.php
- `paidGuestOrder()` --calls--> `Order`  [EXTRACTED]
  tests/Concerns/CreatesGuestRestaurant.php → app/Models/Order.php
- `createGuestRestaurant()` --calls--> `Restaurant`  [EXTRACTED]
  tests/Concerns/CreatesGuestRestaurant.php → app/Models/Restaurant.php

## Import Cycles
- None detected.

## Communities (370 total, 42 thin omitted)

### Community 0 - "stat/chart.js"
Cohesion: 0.02
Nodes (99): qc(), aa(), addControllers(), addPlugins(), addScales(), applyStack(), ar(), ba() (+91 more)

### Community 1 - "components/chart.js"
Cohesion: 0.01
Nodes (146): $a(), ag(), Be(), beforeDatasetDraw(), bm(), Bn(), Bt(), $c() (+138 more)

### Community 2 - "code-editor.js"
Cohesion: 0.01
Nodes (158): aa(), Ac(), addActive(), addChanges(), addCompletion(), addCompletions(), addNamespace(), addNamespaceObject() (+150 more)

### Community 3 - "rich-editor.js"
Cohesion: 0.01
Nodes (212): aa(), add(), addExtensions(), addHackNode(), addNode(), addTextblockHacks(), an(), applyAspectRatio() (+204 more)

### Community 4 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.02
Nodes (49): CmsBanner, LogOptions, CmsFaq, LogOptions, CmsGalleryImage, CmsProfile, LogOptions, restaurant() (+41 more)

### Community 5 - "y"
Cohesion: 0.18
Nodes (49): al(), at(), Be(), Cr(), de(), dt(), Ee(), ef() (+41 more)

### Community 6 - "TestCase"
Cohesion: 0.03
Nodes (62): CashierOrderService, ImageOptimizer, BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles, Bityukov\CommandCenter\Filament\Pages\Commands, Bityukov\CommandCenter\Filament\Pages\History, DatabaseSeeder, RolePermissionSeeder, Filament\Facades\Filament (+54 more)

### Community 7 - "constructor"
Cohesion: 0.02
Nodes (176): accept(), add(), addChunk(), addEventListener(), addInfoPane(), addInner(), addWindowListeners(), adjust() (+168 more)

### Community 8 - "_update"
Cohesion: 0.05
Nodes (70): afterBuildTicks(), afterCalculateLabelRotation(), afterDataLimits(), afterDatasetsUpdate(), afterFit(), afterSetDimensions(), afterTickToLabelConversion(), afterUpdate() (+62 more)

### Community 9 - "sameMarkup"
Cohesion: 0.19
Nodes (14): ao(), append(), Cc(), findDiffEnd(), findDiffStart(), fromArray(), hasMarkup(), Mc() (+6 more)

### Community 10 - "getContext"
Cohesion: 0.06
Nodes (57): acquireContext(), add(), addElements(), As(), beforeUpdate(), bo(), buildOrUpdateElements(), ca() (+49 more)

### Community 11 - "slice"
Cohesion: 0.05
Nodes (135): addElement(), b1(), baseIndentFor(), be(), Bg(), a(), blockAt(), bS() (+127 more)

### Community 12 - "User"
Cohesion: 0.03
Nodes (25): Role, LogOptions, User, ExportFilePolicy, RolePolicy, PermissionCheck, Filament\Models\Contracts\FilamentUser, Filament\Models\Contracts\HasTenants (+17 more)

### Community 13 - "Visit"
Cohesion: 0.04
Nodes (18): ScanTable, Visit, VisitDevice, GuestCheckoutService, OrderPaymentService, StaleOperationsService, TableOpsService, TableScanService (+10 more)

### Community 14 - "_update"
Cohesion: 0.05
Nodes (61): addBox(), afterBuildTicks(), afterCalculateLabelRotation(), afterDataLimits(), afterFit(), afterSetDimensions(), afterTickToLabelConversion(), afterUpdate() (+53 more)

### Community 15 - "Order"
Cohesion: 0.02
Nodes (46): ExportFileDownloadController, OrderReceiptDownloadController, OrderReceiptPrintController, ApplyPlatformBrandTheme, ApplyRestaurantPanelTheme, EnsureApiGuestVisit, EnsureGuestVisit, EnsureRestaurantOperations (+38 more)

### Community 16 - "constructor"
Cohesion: 0.04
Nodes (58): alpha(), apply(), Bc(), bg(), chartOptionScopes(), co(), constructor(), darken() (+50 more)

### Community 17 - "SubscriptionAccess"
Cohesion: 0.05
Nodes (11): canCreate(), canEdit(), canViewAny(), OutletResource, Action, ManageOutlet, EditUser, ListUsers (+3 more)

### Community 18 - "get"
Cohesion: 0.04
Nodes (101): addBlockWidget(), addBreak(), addComposition(), addDelimiter(), addInlineWidget(), addLine(), addLineStart(), addLineStartIfNotCovered() (+93 more)

### Community 19 - "o"
Cohesion: 0.07
Nodes (71): addEventListener(), ar(), au(), Ba(), beforeLayout(), bi(), bindEvents(), bindResponsiveEvents() (+63 more)

### Community 20 - "finish"
Cohesion: 0.06
Nodes (44): addChild(), addGaps(), addLeafElement(), addNode(), ATXHeading(), balanced(), _c(), char() (+36 more)

### Community 21 - "Illuminate\Foundation\Http\FormRequest"
Cohesion: 0.08
Nodes (8): AddCartItemRequest, CheckoutRequest, ClaimTableRequest, JoinTableRequest, StorePaymentProofRequest, StoreRestaurantReviewRequest, UpdateCartItemRequest, Illuminate\Foundation\Http\FormRequest

### Community 22 - "AdminPanelProvider.php"
Cohesion: 0.09
Nodes (27): FilamentProfilePlugin, AdminPanelProvider, FounderPanelProvider, AuthGlass, BezhanSalleh\FilamentShield\FilamentShieldPlugin, Bityukov\CommandCenter\Filament\CommandCenterPlugin, Filament\Http\Middleware\Authenticate, Filament\Http\Middleware\AuthenticateSession (+19 more)

### Community 23 - "OrderResource"
Cohesion: 0.07
Nodes (10): OrderResource, ListOrders, ViewOrder, OrderTodayStatsWidget, Carbon\Carbon, Filament\Forms\Components\DatePicker, Filament\Infolists\Components\ImageEntry, Filament\Infolists\Components\RepeatableEntry (+2 more)

### Community 24 - "ExportFile"
Cohesion: 0.04
Nodes (19): CleanupOldExportFilesJob, ExportReportJob, SendWhatsappReceiptJob, ExportFile, WhatsappMessage, ExportFileObserver, ExportFinishedNotifier, ReportExportDispatcher (+11 more)

### Community 25 - "support.js"
Cohesion: 0.05
Nodes (75): acquireScrollLock(), ai(), e(), Bi(), br(), Bt(), ca(), close() (+67 more)

### Community 26 - "n"
Cohesion: 0.09
Nodes (79): _a(), Ae(), ar(), as(), bc(), ee(), ue(), u() (+71 more)

### Community 27 - "SoftDeleteTrashPage"
Cohesion: 0.04
Nodes (21): SoftDeleteTrashPage, ManageCmsBanners, TrashCmsBanners, ManageCmsFaqs, TrashCmsFaqs, ManageCmsGalleryImages, TrashCmsGalleryImages, TrashDiningTables (+13 more)

### Community 28 - "of"
Cohesion: 0.04
Nodes (81): active(), apply(), B(), b0(), baseTheme(), between(), blur(), bu() (+73 more)

### Community 29 - "Y"
Cohesion: 0.10
Nodes (28): Z(), A(), b(), Bt(), D(), E(), F(), gt() (+20 more)

### Community 30 - ".slice"
Cohesion: 0.05
Nodes (67): accepts(), addInner(), addMaps(), addStep(), addTransform(), appendMap(), appendMapping(), appendMappingInverted() (+59 more)

### Community 31 - "advance"
Cohesion: 0.06
Nodes (57): addActions(), advance(), advanceFully(), advanceStack(), allActions(), break(), c0(), canShift() (+49 more)

### Community 32 - "echo.js"
Cohesion: 0.05
Nodes (49): a(), ar(), b(), Be(), Ce(), cr(), d(), De() (+41 more)

### Community 33 - "resolve"
Cohesion: 0.05
Nodes (136): Ad(), addKeyboardShortcuts(), after(), al(), allowsMarks(), AS(), ay(), before() (+128 more)

### Community 34 - "DiningTable"
Cohesion: 0.05
Nodes (11): PdfExporter, DiningTable, LogOptions, TableFloorPlan, TableQrToken, Barryvdh\DomPDF\Facade\Pdf, Endroid\QrCode\QrCode, Endroid\QrCode\Writer\PngWriter (+3 more)

### Community 35 - "s"
Cohesion: 0.08
Nodes (37): Nn(), addEventListener(), afterAutoSkip(), _animateOptions(), bindResponsiveEvents(), Bt(), buildLookupTable(), buildOrUpdateElements() (+29 more)

### Community 36 - "BackedEnum"
Cohesion: 0.19
Nodes (30): ActivityResource, CmsBannerResource, CmsFaqResource, CmsGalleryImageResource, DiningTableResource, KdsStationResource, MenuCategoryResource, WhatsappMessageResource (+22 more)

### Community 37 - "e"
Cohesion: 0.05
Nodes (74): addCommands(), addInputRules(), addMark(), addNodeMark(), addPasteRules(), addStoredMark(), Ah(), Ax() (+66 more)

### Community 38 - "g$"
Cohesion: 0.04
Nodes (87): acceptToken(), allows(), AQ(), atLastNode(), au(), child(), childAfter(), childBefore() (+79 more)

### Community 39 - "GuestContext"
Cohesion: 0.03
Nodes (35): CartController, CheckoutController, MenuController, OrderController, ReviewController, SessionController, TableController, VisitController (+27 more)

### Community 40 - "Dashboard"
Cohesion: 0.18
Nodes (3): Dashboard, EditRestaurant, Filament\Pages\Dashboard\Concerns\HasFiltersForm

### Community 41 - "ExcelExporter"
Cohesion: 0.24
Nodes (5): ExcelExporter, CashierOrderSoundAlert, Illuminate\Contracts\View\View, Maatwebsite\Excel\Concerns\FromView, Maatwebsite\Excel\Concerns\ShouldAutoSize

### Community 42 - "Ye"
Cohesion: 0.07
Nodes (53): Rd(), $a(), ak(), at(), bk(), c(), bp(), bt() (+45 more)

### Community 43 - "EditProfile"
Cohesion: 0.12
Nodes (5): EditProfile, ProfileInformationForm, Ipatco\FilamentProfile\Forms\ProfileInformationForm, Ipatco\FilamentProfile\Pages\EditProfile, ProfilePageTest

### Community 44 - "KitchenDisplay"
Cohesion: 0.13
Nodes (5): KitchenDisplay, BackedEnum, UnitEnum, Width, Filament\Resources\Concerns\HasTabs

### Community 45 - "notifications.js"
Cohesion: 0.06
Nodes (31): actions(), button(), c(), close(), configureAnimations(), configureTransitions(), constructor(), danger() (+23 more)

### Community 46 - "markdown-editor.js"
Cohesion: 0.05
Nodes (83): ad(), af(), An(), bf(), bo(), Bt(), cd(), Ct() (+75 more)

### Community 47 - "PlatformSetting"
Cohesion: 0.07
Nodes (5): self, PlatformSetting, PlatformSettingSeeder, AuthGlassTest, PlatformSettingTest

### Community 48 - "te"
Cohesion: 0.05
Nodes (9): Bn(), br(), ji(), on(), qd(), Ri(), te(), Vi() (+1 more)

### Community 49 - "Cn"
Cohesion: 0.13
Nodes (46): Cn(), b(), Be(), Ce(), De(), dn(), _e(), Fe() (+38 more)

### Community 51 - "components/select.js"
Cohesion: 0.08
Nodes (37): A(), applyDisabledState(), b(), Bt(), D(), disable(), E(), en() (+29 more)

### Community 52 - "updateElements"
Cohesion: 0.04
Nodes (86): ad(), af(), afterAutoSkip(), Ao(), applyStack(), aspectRatio(), at(), Bf() (+78 more)

### Community 53 - "tables.js"
Cohesion: 0.09
Nodes (66): A(), ae(), areRecordsPartiallySelected(), areRecordsSelected(), areRecordsToggleable(), B(), be(), C() (+58 more)

### Community 54 - "Filament\Actions\Action"
Cohesion: 0.13
Nodes (29): Filament\Actions\Action, Filament\Actions\ActionGroup, Filament\Forms\Components\ColorPicker, Filament\Forms\Components\Component, Filament\Forms\Components\FileUpload, Filament\Forms\Components\Hidden, Filament\Forms\Components\Placeholder, Filament\Forms\Components\RichEditor (+21 more)

### Community 55 - "r"
Cohesion: 0.15
Nodes (43): _a(), ar(), c(), f(), d(), di(), g(), Hi() (+35 more)

### Community 56 - "ir"
Cohesion: 0.08
Nodes (33): beforeDatasetsDraw(), beforeDraw(), bu(), dataset(), Do(), getSortedVisibleDatasetMetas(), getVisibleDatasetCount(), ig() (+25 more)

### Community 57 - "SubscriptionStatus"
Cohesion: 0.12
Nodes (3): BackedEnum, UnitEnum, SubscriptionStatus

### Community 58 - "Xt"
Cohesion: 0.13
Nodes (40): ae(), At(), bi(), bn(), ci(), cn(), ct(), de() (+32 more)

### Community 60 - "filament-right-click.js"
Cohesion: 0.11
Nodes (42): a(), ae(), b(), be(), C(), ce(), D(), de() (+34 more)

### Community 61 - "E"
Cohesion: 0.07
Nodes (40): B(), bd(), bs(), Ci(), _computeLabelSizes(), describe(), df(), E() (+32 more)

### Community 62 - "t"
Cohesion: 0.07
Nodes (40): a$(), activeForPoint(), addBlock(), addLineDeco(), blankContent(), boundChange(), commit(), comparePoint() (+32 more)

### Community 63 - "Si"
Cohesion: 0.13
Nodes (41): ae(), At(), bi(), bn(), ci(), cn(), ct(), de() (+33 more)

### Community 64 - "CmsMedia"
Cohesion: 0.02
Nodes (40): TemplateRadioPicker, AnalyticsKpiWidget, AnalyticsPeriodSummaryWidget, AnalyticsRevenueBarWidget, AnalyticsSidebarWidget, AnalyticsTopMenuWidget, analyticsTheme(), formatKpiDelta() (+32 more)

### Community 65 - "ae"
Cohesion: 0.09
Nodes (34): ae(), Ao(), as(), B(), Kt(), cs(), Ee(), Ge() (+26 more)

### Community 66 - "columns/select.js"
Cohesion: 0.08
Nodes (53): addBadgesForSelectedOptions(), addSingleBadge(), addSingleSelectionDisplay(), An(), applyDisabledState(), closeDropdown(), constructor(), createBadgeElement() (+45 more)

### Community 67 - "Filament\Schemas\Schema"
Cohesion: 0.05
Nodes (16): ManageHomeLanding, BackedEnum, UnitEnum, ManagePlatformPages, BackedEnum, UnitEnum, ManageCmsProfile, BackedEnum (+8 more)

### Community 68 - "CreateCashierOrder"
Cohesion: 0.07
Nodes (7): CreateCashierOrder, BackedEnum, UnitEnum, Width, CashierOrderPreview, CashierFilamentActionsTest, CashierOrderPreviewTest

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
Nodes (47): ExpireStaleOperationsCommand, FinalizeCashierCommissionCommand, GenerateUpcomingInvoicesCommand, ProcessSubscriptionLifecycleCommand, SendCashierCommissionReminderCommand, analyticsDateFrom(), analyticsDateTo(), analyticsDayCount() (+39 more)

### Community 73 - "selectOption"
Cohesion: 0.15
Nodes (33): addSingleSelectionDisplay(), closeDropdown(), constructor(), createOptionElement(), deferPositionDropdown(), destroy(), filterOptions(), focusNextOption() (+25 more)

### Community 74 - "fn"
Cohesion: 0.13
Nodes (33): aa(), At(), ba(), cr(), da(), de(), dt(), ei() (+25 more)

### Community 75 - "find"
Cohesion: 0.07
Nodes (36): activateHover(), baseDirAt(), bidiIn(), bidiSpans(), bidiSpansAt(), cd(), checkHover(), coordsAtPos() (+28 more)

### Community 78 - "static"
Cohesion: 0.03
Nodes (23): canDelete(), canDeleteAny(), canForceDelete(), canRestore(), Action, trashPageAction(), FacilityResource, CreateFacility (+15 more)

### Community 79 - "file-upload.js"
Cohesion: 0.05
Nodes (53): hc(), Bp(), c(), ca(), clickPercent(), constructor(), Cp(), da() (+45 more)

### Community 81 - "from"
Cohesion: 0.09
Nodes (56): Ac(), ag(), bu(), _c(), canAppend(), canReplace(), canReplaceWith(), close() (+48 more)

### Community 82 - "nodesBetween"
Cohesion: 0.08
Nodes (37): _0(), addGlobalAttributes(), childAfter(), childBefore(), dn(), Er(), excludes(), extendNodeSchema() (+29 more)

### Community 83 - "RestaurantDirectory"
Cohesion: 0.11
Nodes (3): RestaurantDirectory, Livewire\Attributes\Computed, Livewire\WithPagination

### Community 84 - "FonnteErrorMessage"
Cohesion: 0.11
Nodes (8): FonnteClient, FonnteErrorMessage, Throwable, Illuminate\Http\Client\ConnectionException, Illuminate\Http\Client\Response, PHPUnit\Framework\Attributes\DataProvider, RuntimeException, FonnteErrorMessageTest

### Community 85 - "A"
Cohesion: 0.07
Nodes (42): A(), As(), buildTicks(), calculateLabelRotation(), _calculatePadding(), Cn(), _computeLabelItems(), _computeLabelSizes() (+34 more)

### Community 89 - "getContext"
Cohesion: 0.05
Nodes (75): g(), interpolate(), jo(), acquireContext(), Ae(), ai(), al(), bi() (+67 more)

### Community 91 - "devDependencies"
Cohesion: 0.09
Nodes (21): apexcharts, axios, concurrently, laravel-vite-plugin, dependencies, apexcharts, devDependencies, axios (+13 more)

### Community 92 - "filament/app.js"
Cohesion: 0.13
Nodes (8): B(), close(), G(), init(), P(), setUpResizeObserver(), x(), Y()

### Community 93 - "sliceDoc"
Cohesion: 0.04
Nodes (74): addToSet(), aO(), bd(), Bh(), charCategorizer(), childString(), clearDelayedAndroidKey(), d0() (+66 more)

### Community 94 - "fn"
Cohesion: 0.22
Nodes (18): An(), Ce(), ei(), fn(), Ft(), Ie(), Le(), ni() (+10 more)

### Community 95 - "Sl"
Cohesion: 0.08
Nodes (32): addAttributes(), addOptions(), domAtPos(), element(), Gg(), iw(), jd(), kl() (+24 more)

### Community 96 - "require"
Cohesion: 0.12
Nodes (16): require, barryvdh/laravel-dompdf, bezhansalleh/filament-shield, endroid/qr-code, filament/filament, hammadzafar05/filament-mobile-preset, ipatco/filament-profile, laravel/framework (+8 more)

### Community 97 - "scripts"
Cohesion: 0.12
Nodes (16): scripts, dev, post-autoload-dump, post-create-project-cmd, post-root-package-install, post-update-cmd, Composer\\Config::disableProcessTimeout, Illuminate\\Foundation\\ComposerScripts::postAutoloadDump (+8 more)

### Community 98 - "ExportFileResource"
Cohesion: 0.13
Nodes (6): GenerateReport, BackedEnum, UnitEnum, ExportFileResource, ListExportFiles, TrashExportFiles

### Community 99 - "color-picker.js"
Cohesion: 0.14
Nodes (3): style(), update(), [x]()

### Community 100 - "js/app.js"
Cohesion: 0.16
Nodes (6): applyTheme(), bindThemeToggle(), initReveal(), initTheme(), syncThemeToggleIcons(), toggleTheme()

### Community 101 - "TenantContext"
Cohesion: 0.11
Nodes (5): ManageDiningTables, Action, Closure, bootBelongsToRestaurantAndOutlet(), TenantContext

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

### Community 109 - "r"
Cohesion: 0.05
Nodes (129): addNodeView(), addProseMirrorPlugins(), af(), au(), bl(), buildProps(), by(), c1() (+121 more)

### Community 110 - "Mt"
Cohesion: 0.24
Nodes (11): apply(), fs(), go(), Hr(), T(), ir(), it(), Mt() (+3 more)

### Community 111 - "getDatasetMeta"
Cohesion: 0.09
Nodes (33): afterDatasetsUpdate(), An(), buildOrUpdateControllers(), _destroyDatasetMeta(), generateLabels(), getDatasetMeta(), getDataVisibility(), _getLegendItemAt() (+25 more)

### Community 112 - "S"
Cohesion: 0.05
Nodes (62): addElements(), apply(), _cachedScopes(), chartOptionScopes(), configure(), constructor(), createResolver(), da() (+54 more)

### Community 113 - "fn"
Cohesion: 0.14
Nodes (21): themeClasses(), Ck(), fn(), Gh(), ip(), Ja(), Jh(), Ji() (+13 more)

### Community 114 - "Filament\Tables\Table"
Cohesion: 0.08
Nodes (8): ViewActivity, ActivitiesTable, TableRightClick, Activity, ActivityPresenter, Filament\Resources\Pages\ViewRecord, Filament\Tables\Table, Spatie\Activitylog\Models\Activity

### Community 115 - "actions/actions.js"
Cohesion: 0.44
Nodes (8): closeModal(), generateModalId(), getActionNestingIndexFromModalId(), init(), openModal(), rememberPreviouslyFocusedElement(), restorePreviouslyFocusedElement(), syncActionModals()

### Community 116 - "MenuItemResource"
Cohesion: 0.04
Nodes (22): EditFacility, ListFacilities, EditLandingTemplate, ListLandingTemplates, ListSubscriptionInvoices, EditSubscriptionPlan, ListSubscriptionPlans, SubscriptionPlanResource (+14 more)

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

### Community 122 - "Illuminate\Database\Eloquent\Builder"
Cohesion: 0.11
Nodes (7): getRecordRouteBindingEloquentQuery(), bootScopedToRestaurant(), scopeForRestaurant(), scopeWithoutRestaurantScope(), BelongsToRestaurantScope, Illuminate\Database\Eloquent\Builder, Illuminate\Database\Eloquent\Scope

### Community 123 - "require-dev"
Cohesion: 0.25
Nodes (8): require-dev, fakerphp/faker, laravel/pail, laravel/pint, laravel/sail, mockery/mockery, nunomaduro/collision, phpunit/phpunit

### Community 124 - "SubscriptionPlan"
Cohesion: 0.10
Nodes (4): CreateSubscriptionInvoice, ViewSubscriptionInvoice, SubscriptionInvoiceResource, SubscriptionPlan

### Community 125 - "6. Katalog fitur"
Cohesion: 0.06
Nodes (33): 1. Latar belakang, 2. Rumusan masalah, 3.1 Tujuan umum, 3.2 Tujuan khusus, 3. Tujuan, 4.1 Bagi restoran (owner, kasir, dapur), 4.2 Bagi tamu, 4.3 Bagi pengelola platform (+25 more)

### Community 126 - "_generate"
Cohesion: 0.06
Nodes (45): add(), determineDataLimits(), diff(), el(), endOf(), formats(), Fs(), _generate() (+37 more)

### Community 127 - "config"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

### Community 128 - "6. Katalog fitur"
Cohesion: 0.06
Nodes (32): 1. Latar belakang, 2. Rumusan masalah, 3.1 Tujuan umum, 3.2 Tujuan khusus, 3. Tujuan, 4.1 Bagi restoran (owner, kasir, dapur), 4.2 Bagi tamu, 4.3 Bagi pengelola platform (+24 more)

### Community 129 - "Login"
Cohesion: 0.25
Nodes (4): Login, Filament\Auth\Pages\Login, Filament\Schemas\Components\Component, Illuminate\Contracts\Support\Htmlable

### Community 130 - "register-restaurant.blade.php"
Cohesion: 0.15
Nodes (12): applyColorPreset(, back, nextFromAccount, nextFromPlan, nextFromRestaurant, nextFromVisual, register, $set( (+4 more)

### Community 131 - "add-to-cart-modal.blade.php"
Cohesion: 0.29
Nodes (6): cancelPicking, confirmAdd, decrementPickingQty, incrementPickingQty, setVariant({{ $variant->id }}), toggleModifier({{ $modifier->id }})

### Community 132 - "getProps"
Cohesion: 0.10
Nodes (25): active(), ah(), _animateOptions(), average(), contains(), _createAnimations(), getCenterPoint(), getProps() (+17 more)

### Community 134 - "psr-4"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 135 - "extra"
Cohesion: 0.40
Nodes (5): dev-master, extra, branch-alias, laravel, dont-discover

### Community 136 - "logging.php"
Cohesion: 0.40
Nodes (4): Monolog\Handler\NullHandler, Monolog\Handler\StreamHandler, Monolog\Handler\SyslogUdpHandler, Monolog\Processor\PsrLogMessageProcessor

### Community 138 - "fromDateTimes"
Cohesion: 0.10
Nodes (22): abutsStart(), Cm(), difference(), divideEqually(), fc(), fromDateTimes(), Gn(), intersection() (+14 more)

### Community 139 - "AppServiceProvider.php"
Cohesion: 0.11
Nodes (17): AppServiceProvider, Filament\Actions\DeleteBulkAction, Filament\Actions\ForceDeleteAction, Filament\Actions\ForceDeleteBulkAction, Filament\Actions\RestoreAction, Filament\Actions\RestoreBulkAction, Filament\Actions\ViewAction, Filament\Resources\Pages\Page (+9 more)

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

### Community 153 - "fo"
Cohesion: 0.10
Nodes (28): alpha(), bo(), es(), et(), fo(), go(), greyscale(), ho() (+20 more)

### Community 158 - "cc"
Cohesion: 0.10
Nodes (21): attrs(), AX(), bi(), cc(), cO(), combine(), configure(), extend() (+13 more)

### Community 162 - "FilamentTenantTheme"
Cohesion: 0.13
Nodes (5): FilamentTenantTheme, Filament\Support\Colors\ColorManager, Filament\Support\Facades\FilamentColor, ReflectionClass, FilamentTenantThemeTest

### Community 163 - "addElementByRule"
Cohesion: 0.12
Nodes (28): addAll(), addDOM(), addElement(), addElementByRule(), addTextNode(), addToSet(), allowedMarks(), allowsMarkType() (+20 more)

### Community 164 - "SubscriptionWriteGuard"
Cohesion: 0.28
Nodes (3): BlockGraceMutations, SubscriptionWriteGuard, Livewire\ComponentHook

### Community 170 - "draw"
Cohesion: 0.04
Nodes (101): aa(), adjustHitBoxes(), afterDraw(), bh(), br(), buildTicks(), calculateCircumference(), calculateLabelRotation() (+93 more)

### Community 172 - "fromObject"
Cohesion: 0.03
Nodes (113): Oe(), ac(), ae(), after(), Al(), Am(), before(), bl() (+105 more)

### Community 173 - "filament-shield.php"
Cohesion: 0.29
Nodes (5): Dashboard, BezhanSalleh\FilamentShield\Resources\Roles\RoleResource, Filament\Pages\Dashboard, Filament\Widgets\AccountWidget, Filament\Widgets\FilamentInfoWidget

### Community 176 - "🎨 Spesifikasi Token & Class CSS yang Tersedia di `theme.css`"
Cohesion: 0.15
Nodes (12): 1. Kartu Kontainer Utama (`.vision-card`), 2. Kotak Ikon Gradien Neon (`.vision-icon-box`), 3. Kapsul Metrik / Sub-Kartu (`.vision-pill-card` atau `.vision-pill-dark`), 4. Kartu Filter (`.vision-filter-card`), 5. Tabel Data Kaca (`.vision-table-card`), 6. Form Section & Skema Input Kaca (`.vision-card` pada `Section::make`), 7. Kartu Statistik Global (`StatsOverviewWidget` / `Stat::make`), ⚙️ Aturan Wajib untuk AI Developer (+4 more)

### Community 180 - "dx"
Cohesion: 0.09
Nodes (38): Ei(), Aa(), ai(), Ba(), Bi(), cf(), da(), fa() (+30 more)

### Community 183 - "fn"
Cohesion: 0.24
Nodes (17): Ce(), ei(), fn(), Ft(), Ie(), Le(), ni(), oe() (+9 more)

### Community 185 - "RestaurantCategory"
Cohesion: 0.05
Nodes (11): RestaurantCategory, RestaurantDirectory, FacilitySeeder, RestaurantCategorySeeder, SubscriptionPlanSeeder, Illuminate\Contracts\Pagination\LengthAwarePaginator, Illuminate\Database\Seeder, Illuminate\Support\Collection (+3 more)

### Community 193 - "restaurant-menu-catalog.blade.php"
Cohesion: 0.25
Nodes (7): landing.templates.foodie.sections.footer, landing.templates.foodie.sections.header, landing.templates.glassmorphism.partials.background, landing.templates.glassmorphism.sections.footer, landing.templates.glassmorphism.sections.header, partials.customer.landing-footer, partials.customer.landing-header

### Community 295 - "3. Detail Implementasi Perbaikan Keamanan"
Cohesion: 0.17
Nodes (11): 1. Ringkasan Eksekutif (Executive Summary), 2. Matriks Temuan & Status Perbaikan (Findings & Remediation Matrix), 3. Detail Implementasi Perbaikan Keamanan, 4. Hasil Verifikasi Pengujian Otomatis, A. Proteksi `qr_secret` pada Model (`SEC-01`), B. Middleware HTTP Security Headers (`SEC-02`), C. Pengetatan CORS & Session Cookie (`SEC-03` & `SEC-05`), D. Sanitasi File Upload (`SEC-06`) (+3 more)

### Community 296 - "foodie/show.blade.php"
Cohesion: 0.33
Nodes (5): landing.templates.foodie.sections., landing.templates.foodie.sections.hero, landing.sections., landing.templates.foodie.sections.footer, landing.templates.foodie.sections.header

### Community 297 - "toString"
Cohesion: 0.14
Nodes (20): Bc(), check(), checkAttrs(), endIndex(), getObj(), hasProtocol(), $i(), Ra() (+12 more)

### Community 301 - "classic/show.blade.php"
Cohesion: 0.50
Nodes (3): landing.sections., partials.customer.landing-footer, partials.customer.landing-header

### Community 302 - "P"
Cohesion: 0.07
Nodes (35): active(), Ao(), bl(), br(), cancel(), ci(), _createAnimations(), _createDescriptors() (+27 more)

### Community 318 - "parse"
Cohesion: 0.12
Nodes (23): Bm(), Dc(), defaultType(), Dm(), eat(), err(), getJSON(), hasRequiredAttrs() (+15 more)

### Community 320 - "ManageSoundNotifications"
Cohesion: 0.15
Nodes (4): ManageSoundNotifications, BackedEnum, UnitEnum, ManageSoundNotificationsTest

### Community 322 - "glassmorphism/show.blade.php"
Cohesion: 0.29
Nodes (6): landing.templates.glassmorphism.sections., landing.templates.glassmorphism.sections.hero, landing.sections., landing.templates.glassmorphism.partials.background, landing.templates.glassmorphism.sections.footer, landing.templates.glassmorphism.sections.header

### Community 324 - "CommissionReconciliation"
Cohesion: 0.26
Nodes (4): CommissionReconciliation, BackedEnum, UnitEnum, Width

### Community 325 - "_each"
Cohesion: 0.18
Nodes (12): addControllers(), addPlugins(), addScales(), _each(), _exec(), _getRegistryForType(), isForType(), remove() (+4 more)

### Community 327 - "addSingleBadge"
Cohesion: 0.33
Nodes (6): addBadgesForSelectedOptions(), addSingleBadge(), createBadgeElement(), createRemoveButton(), getLabelForSingleSelection(), getSelectedOptionLabel()

### Community 344 - "ManageBillingAccount"
Cohesion: 0.24
Nodes (3): ManageBillingAccount, BackedEnum, UnitEnum

### Community 347 - "N"
Cohesion: 0.33
Nodes (11): ae(), A(), E(), at(), be(), Gt(), i(), Jt() (+3 more)

### Community 348 - "st"
Cohesion: 0.24
Nodes (11): [g](), _freeze(), getAllExtensions(), Ct(), lt(), ot(), se(), st() (+3 more)

### Community 349 - "RefreshesAnalyticsChart.php"
Cohesion: 0.36
Nodes (8): generateChartDataChecksum(), getCachedChartData(), getChartData(), mountRefreshesAnalyticsChart(), refreshAnalyticsChartData(), rendering(), updateChartData(), Livewire\Attributes\Locked

### Community 354 - "iy"
Cohesion: 0.27
Nodes (10): gr(), gu(), iy(), Ln(), oy(), pasteHTML(), pasteText(), replaceSelection() (+2 more)

### Community 356 - "Ae"
Cohesion: 0.67
Nodes (3): Ae(), Bt(), ne()

### Community 360 - "FounderStatsWidget.php"
Cohesion: 0.50
Nodes (3): FounderStatsWidget, Filament\Widgets\StatsOverviewWidget, Filament\Widgets\StatsOverviewWidget\Stat

## Knowledge Gaps
- **342 isolated node(s):** `$schema`, `name`, `type`, `description`, `laravel` (+337 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **42 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `update()` connect `constructor` to `stat/chart.js`, `code-editor.js`, `rich-editor.js`, `slice`, `get`, `n`, `of`, `.slice`, `advance`, `echo.js`, `resolve`, `g$`, `Ye`, `fromObject`, `markdown-editor.js`, `te`, `dx`, `t`, `find`, `sliceDoc`, `Sl`, `r`, `O`?**
  _High betweenness centrality (0.032) - this node is a cross-community bridge._
- **Why does `_s()` connect `components/chart.js` to `rich-editor.js`, `getProps`?**
  _High betweenness centrality (0.027) - this node is a cross-community bridge._
- **Why does `Restaurant` connect `Restaurant` to `Illuminate\Database\Eloquent\Model`, `TestCase`, `User`, `Visit`, `Order`, `SubscriptionAccess`, `AdminPanelProvider.php`, `ExportFile`, `FilamentTenantTheme`, `DiningTable`, `BackedEnum`, `GuestContext`, `Dashboard`, `RegisterRestaurant`, `PlatformSetting`, `Filament\Actions\Action`, `SubscriptionStatus`, `RestaurantCategory`, `CmsMedia`, `SubscriptionInvoiceService`, `Filament\Schemas\Schema`, `CommissionReconciliation`, `CreateCashierOrder`, `static`, `ExportFileResource`, `OrderReceiptPrintTest`, `TenantContext`, `SoftDeleteTrashTest`, `FounderStatsWidget.php`, `GraceReadOnlyTest`, `Illuminate\Database\Eloquent\Builder`, `SubscriptionPlan`?**
  _High betweenness centrality (0.023) - this node is a cross-community bridge._
- **Are the 18 inferred relationships involving `constructor()` (e.g. with `a()` and `h()`) actually correct?**
  _`constructor()` has 18 INFERRED edges - model-reasoned connections that need verification._
- **What connects `$schema`, `name`, `type` to the rest of the system?**
  _342 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `stat/chart.js` be split into smaller, more focused modules?**
  _Cohesion score 0.022850389047572146 - nodes in this community are weakly interconnected._
- **Should `components/chart.js` be split into smaller, more focused modules?**
  _Cohesion score 0.010825917082700238 - nodes in this community are weakly interconnected._