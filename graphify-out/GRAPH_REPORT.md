# Graph Report - WALK-IN-RESTO  (2026-09-03)

## Corpus Check
- 625 files · ~292,847 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 8493 nodes · 26206 edges · 327 communities (290 shown, 37 thin omitted)
- Extraction: 91% EXTRACTED · 9% INFERRED · 0% AMBIGUOUS · INFERRED: 2464 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `96c8948d`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- stat/chart.js
- components/chart.js
- code-editor.js
- rich-editor.js
- Illuminate\Database\Eloquent\Model
- updateElements
- TestCase
- constructor
- Illuminate\Support\Collection
- Illuminate\Http\Request
- r
- facet
- User
- by
- _update
- Order
- SubscriptionAccess
- DiningTable
- get
- Filament\Tables\Table
- advance
- CmsProfile
- Illuminate\Database\Eloquent\Builder
- markdown-editor.js
- Restaurant
- support.js
- vd
- marks
- of
- columns/select.js
- .slice
- CreateCashierOrder.php
- echo.js
- resolve
- ExportFile
- copy
- o
- Filament\Schemas\Schema
- prop
- Im
- InvoicePaymentTest
- E
- Ye
- Dashboard
- SoftDeleteTrashPage
- notifications.js
- Y
- PlatformSetting
- te
- Cn
- Pe
- components/select.js
- reduce
- tables.js
- ne
- r
- AppServiceProvider.php
- SubscriptionStatus
- Xt
- RestaurantCategory
- filament-right-click.js
- Activity
- t
- Si
- RendersAnalyticsDashboard.php
- n
- selectOption
- cc
- CreateCashierOrder
- ir
- addElementByRule
- slider.js
- ae
- closeDropdown
- fn
- slice
- Facility
- ExportFileResource
- SubscriptionInvoiceResource
- file-upload.js
- renderOptions
- CashierFilamentActionsTest
- RestaurantDirectory
- FonnteErrorMessage
- eq
- toString
- constructor
- AdminPanelProvider.php
- y
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
- EditProfile
- RegisterRestaurant.php
- composer.json
- order-today-stats-widget.blade.php
- .panel
- date-time-picker.js
- 🚀 3. Langkah-Langkah Menambahkan Template Baru (*Workflow*)
- N
- Mt
- Login
- Illuminate\Support\Facades\Schema
- actions/actions.js
- schemas.js
- Landasan Produksi — Tahap 1 (Walk-In Operasional)
- Guest
- 5. Keuntungan dari fitur — bahasa owner, bukan bahasa sistem
- AuthGlass
- require-dev
- st
- 6. Katalog fitur
- filament-shield.php
- config
- 6. Katalog fitur
- ProfileInformationForm.php
- register-restaurant.blade.php
- add-to-cart-modal.blade.php
- selectOption
- components/actions.js
- psr-4
- extra
- logging.php
- GeoDistance
- Illuminate\Foundation\Http\FormRequest
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
- Illuminate\Database\Schema\Blueprint
- Illuminate\Database\Migrations\Migration
- kpi.blade.php
- Ae
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
- SubscriptionWriteGuard
- classic/show.blade.php
- rules/graphify.md
- workflows/graphify.md
- glassmorphism/show.blade.php
- MenuItem
- glassmorphism-background.blade.php

## God Nodes (most connected - your core abstractions)
1. `Restaurant` - 263 edges
2. `User` - 247 edges
3. `constructor()` - 152 edges
4. `update()` - 148 edges
5. `TestCase` - 141 edges
6. `Order` - 108 edges
7. `resolve()` - 94 edges
8. `y()` - 93 edges
9. `MenuItem` - 92 edges
10. `_update()` - 87 edges

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

## Communities (327 total, 37 thin omitted)

### Community 0 - "stat/chart.js"
Cohesion: 0.01
Nodes (488): Nn(), Ot(), A(), aa(), acquireContext(), active(), add(), addControllers() (+480 more)

### Community 1 - "components/chart.js"
Cohesion: 0.01
Nodes (352): abutsStart(), ac(), ad(), addControllers(), addPlugins(), addScales(), ae(), after() (+344 more)

### Community 2 - "code-editor.js"
Cohesion: 0.01
Nodes (133): aa(), Ac(), addActive(), addChanges(), addCompletion(), addCompletions(), addNamespace(), addNamespaceObject() (+125 more)

### Community 3 - "rich-editor.js"
Cohesion: 0.01
Nodes (185): themeClasses(), aa(), addHackNode(), addNodeMark(), addTextblockHacks(), an(), applyAspectRatio(), applyConstraints() (+177 more)

### Community 4 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.02
Nodes (40): CmsBanner, LogOptions, CmsFaq, LogOptions, CmsGalleryImage, restaurant(), bootPurgesPublicDiskFiles(), storedFileAttributes() (+32 more)

### Community 5 - "updateElements"
Cohesion: 0.06
Nodes (53): addEventListener(), applyStack(), bindResponsiveEvents(), _calculateBarIndexPixels(), _calculateBarValuePixels(), calculateCircumference(), _circumference(), _computeAngle() (+45 more)

### Community 6 - "TestCase"
Cohesion: 0.03
Nodes (52): CashierOrderService, BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles, Bityukov\CommandCenter\Filament\Pages\Commands, Bityukov\CommandCenter\Filament\Pages\History, DatabaseSeeder, RolePermissionSeeder, Filament\Facades\Filament, Illuminate\Database\QueryException (+44 more)

### Community 7 - "constructor"
Cohesion: 0.03
Nodes (144): add(), addChunk(), addEventListener(), addInfoPane(), addInner(), addWindowListeners(), adjust(), al() (+136 more)

### Community 8 - "Illuminate\Support\Collection"
Cohesion: 0.06
Nodes (10): periodSummary(), WelcomeBannerWidget, GuestCheckoutService, MenuModifierService, CashTender, IdrAmount, RestaurantDirectory, Illuminate\Contracts\Pagination\LengthAwarePaginator (+2 more)

### Community 9 - "Illuminate\Http\Request"
Cohesion: 0.02
Nodes (67): CartController, CheckoutController, MenuController, OrderController, ReviewController, SessionController, TableController, VisitController (+59 more)

### Community 10 - "r"
Cohesion: 0.05
Nodes (141): _0(), addNodeView(), addProseMirrorPlugins(), af(), au(), blockRange(), buildProps(), c1() (+133 more)

### Community 11 - "facet"
Cohesion: 0.04
Nodes (64): accept(), activateHover(), applyTransaction(), asSingle(), baseDirAt(), bidiIn(), bidiSpans(), bidiSpansAt() (+56 more)

### Community 12 - "User"
Cohesion: 0.02
Nodes (31): AnalyticsKpiWidget, Role, LogOptions, User, ExportFilePolicy, RolePolicy, UserPolicy, Filament\Models\Contracts\FilamentUser (+23 more)

### Community 13 - "by"
Cohesion: 0.04
Nodes (85): Ad(), add(), ay(), Bd(), Bg(), bt(), bw(), by() (+77 more)

### Community 14 - "_update"
Cohesion: 0.03
Nodes (123): addBox(), addElements(), afterBuildTicks(), afterCalculateLabelRotation(), afterDataLimits(), afterDatasetsUpdate(), afterDraw(), afterFit() (+115 more)

### Community 15 - "Order"
Cohesion: 0.04
Nodes (17): OrderReceiptDownloadController, OrderReceiptPrintController, SendWhatsappReceiptJob, Order, OrderItem, OrderReceipt, WhatsappMessage, DailyOmzetService (+9 more)

### Community 16 - "SubscriptionAccess"
Cohesion: 0.05
Nodes (12): canCreate(), canEdit(), canViewAny(), OrderResource, ListOrders, OrderTodayStatsWidget, OutletResource, Action (+4 more)

### Community 17 - "DiningTable"
Cohesion: 0.03
Nodes (20): ScanTable, DiningTable, LogOptions, Visit, OrderPaymentService, StaleOperationsService, TableOpsService, TableScanService (+12 more)

### Community 18 - "get"
Cohesion: 0.03
Nodes (108): addBlockWidget(), addBreak(), addComposition(), addDelimiter(), addInlineWidget(), addLine(), addLineStart(), addLineStartIfNotCovered() (+100 more)

### Community 19 - "Filament\Tables\Table"
Cohesion: 0.13
Nodes (35): CmsBannerResource, DiningTableResource, MenuItemResource, ModifierGroupResource, WhatsappMessageResource, TableRightClick, BackedEnum, Carbon\Carbon (+27 more)

### Community 20 - "advance"
Cohesion: 0.05
Nodes (65): addChild(), addGaps(), addLeafElement(), addNode(), advance(), ATXHeading(), blank(), break() (+57 more)

### Community 21 - "CmsProfile"
Cohesion: 0.04
Nodes (13): CmsProfile, LogOptions, FilamentTenantTheme, LandingLayout, self, Filament\Support\Colors\ColorManager, Filament\Support\Facades\FilamentColor, Illuminate\Support\Arr (+5 more)

### Community 22 - "Illuminate\Database\Eloquent\Builder"
Cohesion: 0.06
Nodes (15): getRecordRouteBindingEloquentQuery(), ManageDiningTables, Action, Closure, ListMenuItems, bootBelongsToRestaurantAndOutlet(), bootScopedToRestaurant(), scopeForRestaurant() (+7 more)

### Community 23 - "markdown-editor.js"
Cohesion: 0.05
Nodes (88): ad(), af(), ai(), al(), An(), ao(), bf(), bo() (+80 more)

### Community 24 - "Restaurant"
Cohesion: 0.03
Nodes (38): ExpireStaleOperationsCommand, GenerateUpcomingInvoicesCommand, ProcessSubscriptionLifecycleCommand, FounderStatsWidget, analyticsDateFrom(), analyticsDateTo(), analyticsDayCount(), analyticsRangeLabel() (+30 more)

### Community 25 - "support.js"
Cohesion: 0.05
Nodes (75): acquireScrollLock(), ai(), e(), Bi(), br(), Bt(), ca(), close() (+67 more)

### Community 26 - "vd"
Cohesion: 0.08
Nodes (77): _a(), Ac(), Ae(), ar(), as(), bc(), bl(), ce() (+69 more)

### Community 27 - "marks"
Cohesion: 0.06
Nodes (58): addGlobalAttributes(), addInputRules(), addMark(), addPasteRules(), addStoredMark(), Ah(), Ax(), dn() (+50 more)

### Community 28 - "of"
Cohesion: 0.04
Nodes (70): active(), apply(), B(), baseTheme(), blur(), bu(), checkAsyncSchedule(), define() (+62 more)

### Community 29 - "columns/select.js"
Cohesion: 0.06
Nodes (57): A(), An(), applyDisabledState(), b(), Bt(), Ce(), D(), disable() (+49 more)

### Community 30 - ".slice"
Cohesion: 0.05
Nodes (67): accepts(), addInner(), addMaps(), addStep(), addTransform(), appendMap(), appendMapping(), appendMappingInverted() (+59 more)

### Community 31 - "CreateCashierOrder.php"
Cohesion: 0.13
Nodes (26): Filament\Actions\Action, Filament\Forms\Components\ColorPicker, Filament\Forms\Components\Component, Filament\Forms\Components\DatePicker, Filament\Forms\Components\Hidden, Filament\Forms\Components\Repeater, Filament\Forms\Components\RichEditor, Filament\Forms\Components\Select (+18 more)

### Community 32 - "echo.js"
Cohesion: 0.05
Nodes (48): a(), ar(), b(), Be(), Ce(), cr(), d(), De() (+40 more)

### Community 33 - "resolve"
Cohesion: 0.06
Nodes (139): Ac(), addCommands(), addKeyboardShortcuts(), after(), ag(), al(), AS(), before() (+131 more)

### Community 34 - "ExportFile"
Cohesion: 0.06
Nodes (19): ExcelExporter, PdfExporter, CleanupOldExportFilesJob, ExportReportJob, ExportFile, ExportFileObserver, ExportFinishedNotifier, ReportExportDispatcher (+11 more)

### Community 35 - "copy"
Cohesion: 0.14
Nodes (32): bu(), close(), closeFrontierNode(), computeWrapping(), copy(), defaultType(), dl(), dropNode() (+24 more)

### Community 36 - "o"
Cohesion: 0.02
Nodes (219): acquireContext(), adjustHitBoxes(), ah(), Ao(), apply(), ar(), aspectRatio(), au() (+211 more)

### Community 37 - "Filament\Schemas\Schema"
Cohesion: 0.05
Nodes (18): TemplateRadioPicker, ManageBillingAccount, BackedEnum, UnitEnum, ManageHomeLanding, BackedEnum, UnitEnum, ManagePlatformPages (+10 more)

### Community 38 - "prop"
Cohesion: 0.06
Nodes (58): AQ(), atLastNode(), au(), child(), cursor(), cursorAt(), dX(), enter() (+50 more)

### Community 39 - "Im"
Cohesion: 0.31
Nodes (10): Bm(), eat(), err(), Im(), isInGroup(), Lm(), o1(), pc() (+2 more)

### Community 41 - "E"
Cohesion: 0.05
Nodes (64): $a(), aa(), add(), B(), bo(), bs(), ca(), _cachedScopes() (+56 more)

### Community 42 - "Ye"
Cohesion: 0.07
Nodes (52): Rd(), $a(), ak(), at(), bk(), c(), bp(), closest() (+44 more)

### Community 43 - "Dashboard"
Cohesion: 0.05
Nodes (12): EditFacility, EditLandingTemplate, EditSubscriptionPlan, ListSubscriptionPlans, SubscriptionPlanResource, EditTenant, Dashboard, EditMenuItem (+4 more)

### Community 44 - "SoftDeleteTrashPage"
Cohesion: 0.03
Nodes (26): SoftDeleteTrashPage, ManageCmsBanners, TrashCmsBanners, CmsFaqResource, ManageCmsFaqs, TrashCmsFaqs, CmsGalleryImageResource, ManageCmsGalleryImages (+18 more)

### Community 45 - "notifications.js"
Cohesion: 0.06
Nodes (31): actions(), button(), c(), close(), configureAnimations(), configureTransitions(), constructor(), danger() (+23 more)

### Community 46 - "Y"
Cohesion: 0.05
Nodes (54): active(), af(), afterAutoSkip(), _animateOptions(), at(), Bf(), br(), buildLookupTable() (+46 more)

### Community 47 - "PlatformSetting"
Cohesion: 0.04
Nodes (16): PlatformPageController, RestaurantLandingController, RestaurantMenuCatalog, self, PlatformSetting, LandingPageDataService, ReceiptLogo, RestaurantTheme (+8 more)

### Community 48 - "te"
Cohesion: 0.04
Nodes (12): Pr(), Bn(), br(), Id(), ji(), qd(), qi(), Ri() (+4 more)

### Community 49 - "Cn"
Cohesion: 0.13
Nodes (46): Cn(), b(), Be(), Ce(), De(), dn(), _e(), Fe() (+38 more)

### Community 50 - "Pe"
Cohesion: 0.23
Nodes (22): ca(), de(), dt(), Ee(), ei(), Ft(), Hr(), ht() (+14 more)

### Community 51 - "components/select.js"
Cohesion: 0.08
Nodes (34): b(), Bt(), D(), E(), en(), Et(), getLabelsForMultipleSelection(), getSelectedOptionLabels() (+26 more)

### Community 52 - "reduce"
Cohesion: 0.08
Nodes (46): addActions(), advanceFully(), advanceStack(), allActions(), c0(), canShift(), close(), deadEnd() (+38 more)

### Community 53 - "tables.js"
Cohesion: 0.13
Nodes (44): A(), ae(), B(), be(), C(), ce(), E(), ee() (+36 more)

### Community 54 - "ne"
Cohesion: 0.10
Nodes (42): cd(), ee(), ue(), cl(), dd(), Do(), Et(), fd() (+34 more)

### Community 55 - "r"
Cohesion: 0.15
Nodes (43): _a(), ar(), c(), f(), d(), di(), g(), Hi() (+35 more)

### Community 56 - "AppServiceProvider.php"
Cohesion: 0.06
Nodes (25): KitchenDisplay, BackedEnum, UnitEnum, Width, AppServiceProvider, Filament\Actions\DeleteBulkAction, Filament\Actions\ForceDeleteAction, Filament\Actions\ForceDeleteBulkAction (+17 more)

### Community 57 - "SubscriptionStatus"
Cohesion: 0.10
Nodes (4): BackedEnum, UnitEnum, SubscriptionStatus, SubscriptionPlan

### Community 58 - "Xt"
Cohesion: 0.12
Nodes (44): ae(), At(), bi(), bn(), ci(), cn(), ct(), de() (+36 more)

### Community 59 - "RestaurantCategory"
Cohesion: 0.06
Nodes (9): RestaurantCategory, FacilitySeeder, LandingTemplateSeeder, PlatformSettingSeeder, RestaurantCategorySeeder, SubscriptionPlanSeeder, Illuminate\Database\Seeder, RestaurantDirectoryTest (+1 more)

### Community 60 - "filament-right-click.js"
Cohesion: 0.11
Nodes (42): a(), ae(), b(), be(), C(), ce(), D(), de() (+34 more)

### Community 61 - "Activity"
Cohesion: 0.07
Nodes (11): ActivityResource, ListActivities, ViewActivity, ActivityInfolist, ActivitiesTable, ViewOrder, Activity, ActivityPresenter (+3 more)

### Community 62 - "t"
Cohesion: 0.07
Nodes (42): a$(), activeForPoint(), addBlock(), addLineDeco(), b1(), blankContent(), boundChange(), commit() (+34 more)

### Community 63 - "Si"
Cohesion: 0.14
Nodes (40): ae(), At(), bi(), bn(), ci(), cn(), ct(), de() (+32 more)

### Community 64 - "RendersAnalyticsDashboard.php"
Cohesion: 0.09
Nodes (19): AnalyticsPeriodSummaryWidget, AnalyticsRevenueBarWidget, AnalyticsSidebarWidget, AnalyticsTopMenuWidget, generateChartDataChecksum(), getCachedChartData(), getChartData(), mountRefreshesAnalyticsChart() (+11 more)

### Community 65 - "n"
Cohesion: 0.09
Nodes (40): Ei(), Aa(), Ba(), Bi(), cf(), gl(), Gr(), If() (+32 more)

### Community 66 - "selectOption"
Cohesion: 0.12
Nodes (39): addBadgesForSelectedOptions(), addSingleBadge(), addSingleSelectionDisplay(), closeDropdown(), constructor(), createBadgeElement(), createOptionElement(), createRemoveButton() (+31 more)

### Community 67 - "cc"
Cohesion: 0.12
Nodes (18): attrs(), AX(), bi(), cc(), combine(), configure(), extend(), gQ() (+10 more)

### Community 68 - "CreateCashierOrder"
Cohesion: 0.13
Nodes (4): CreateCashierOrder, BackedEnum, UnitEnum, Width

### Community 69 - "ir"
Cohesion: 0.13
Nodes (34): Ft(), ir(), ce(), de(), Dt(), ee(), Et(), fe() (+26 more)

### Community 70 - "addElementByRule"
Cohesion: 0.14
Nodes (25): addAll(), addDOM(), addElement(), addElementByRule(), addTextNode(), addToSet(), allowedMarks(), allowsMarkType() (+17 more)

### Community 71 - "slider.js"
Cohesion: 0.11
Nodes (33): ar(), Be(), Ce(), De(), _e(), Ee(), er(), et() (+25 more)

### Community 72 - "ae"
Cohesion: 0.09
Nodes (34): ae(), Ao(), as(), B(), Kt(), cs(), Ee(), Ge() (+26 more)

### Community 73 - "closeDropdown"
Cohesion: 0.23
Nodes (17): applyDisabledState(), closeDropdown(), constructor(), destroy(), disable(), enable(), focusNextOption(), focusPreviousOption() (+9 more)

### Community 74 - "fn"
Cohesion: 0.13
Nodes (33): aa(), At(), ba(), cr(), da(), de(), dt(), ei() (+25 more)

### Community 75 - "slice"
Cohesion: 0.05
Nodes (128): addElement(), Ah(), balanced(), baseIndentFor(), be(), Bg(), a(), blockAt() (+120 more)

### Community 76 - "Facility"
Cohesion: 0.12
Nodes (4): ManageCmsProfile, BackedEnum, UnitEnum, Facility

### Community 77 - "ExportFileResource"
Cohesion: 0.12
Nodes (6): GenerateReport, BackedEnum, UnitEnum, ExportFileResource, ListExportFiles, TrashExportFiles

### Community 78 - "SubscriptionInvoiceResource"
Cohesion: 0.15
Nodes (3): CreateSubscriptionInvoice, ViewSubscriptionInvoice, SubscriptionInvoiceResource

### Community 79 - "file-upload.js"
Cohesion: 0.05
Nodes (53): hc(), Bp(), c(), ca(), clickPercent(), constructor(), Cp(), da() (+45 more)

### Community 80 - "renderOptions"
Cohesion: 0.37
Nodes (13): createOptionElement(), deferPositionDropdown(), filterOptions(), handleSearch(), hideLoadingState(), openDropdown(), populateLabelRepositoryFromOptions(), positionDropdown() (+5 more)

### Community 84 - "FonnteErrorMessage"
Cohesion: 0.11
Nodes (8): FonnteClient, FonnteErrorMessage, Throwable, Illuminate\Http\Client\ConnectionException, Illuminate\Http\Client\Response, PHPUnit\Framework\Attributes\DataProvider, RuntimeException, FonnteErrorMessageTest

### Community 85 - "eq"
Cohesion: 0.06
Nodes (52): addNode(), allowsMarks(), ao(), append(), Cc(), co(), compatibleContent(), dd() (+44 more)

### Community 86 - "toString"
Cohesion: 0.07
Nodes (38): addToSet(), bd(), between(), Bh(), childString(), clearDelayedAndroidKey(), d0(), De() (+30 more)

### Community 87 - "constructor"
Cohesion: 0.04
Nodes (68): addAttributes(), addExtensions(), addOptions(), applyInitialSize(), Bo(), cn(), compile(), configure() (+60 more)

### Community 89 - "AdminPanelProvider.php"
Cohesion: 0.16
Nodes (18): BezhanSalleh\FilamentShield\FilamentShieldPlugin, Bityukov\CommandCenter\Filament\CommandCenterPlugin, Filament\Http\Middleware\Authenticate, Filament\Http\Middleware\AuthenticateSession, Filament\Http\Middleware\DisableBladeIconComponents, Filament\Http\Middleware\DispatchServingFilamentEvent, Filament\Navigation\NavigationGroup, Filament\Support\Colors\Color (+10 more)

### Community 90 - "y"
Cohesion: 0.18
Nodes (62): at(), b(), Be(), $c(), X(), me(), Cr(), Ct() (+54 more)

### Community 91 - "devDependencies"
Cohesion: 0.11
Nodes (18): axios, concurrently, laravel-vite-plugin, devDependencies, axios, concurrently, laravel-vite-plugin, tailwindcss (+10 more)

### Community 92 - "filament/app.js"
Cohesion: 0.13
Nodes (8): B(), close(), G(), init(), P(), setUpResizeObserver(), x(), Y()

### Community 93 - "g$"
Cohesion: 0.03
Nodes (95): acceptToken(), allows(), aO(), ch(), charCategorizer(), childAfter(), childBefore(), cO() (+87 more)

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
Cohesion: 0.03
Nodes (32): canDelete(), canDeleteAny(), canForceDelete(), canRestore(), Action, trashPageAction(), FacilityResource, CreateFacility (+24 more)

### Community 99 - "color-picker.js"
Cohesion: 0.14
Nodes (3): style(), update(), [x]()

### Community 100 - "js/app.js"
Cohesion: 0.22
Nodes (6): applyTheme(), bindThemeToggle(), initReveal(), initTheme(), syncThemeToggleIcons(), toggleTheme()

### Community 102 - "RegisterRestaurant.php"
Cohesion: 0.10
Nodes (7): RegisterRestaurant, RestaurantProvisioner, SubscriptionPlanSync, ReservedSlugs, Illuminate\Support\Facades\Auth, Illuminate\Validation\Rule, Illuminate\Validation\Rules\Password

### Community 103 - "composer.json"
Cohesion: 0.14
Nodes (13): autoload-dev, psr-4, description, keywords, license, minimum-stability, name, prefer-stable (+5 more)

### Community 106 - ".panel"
Cohesion: 0.20
Nodes (7): FilamentProfilePlugin, AdminPanelProvider, FounderPanelProvider, Filament\Panel, Filament\PanelProvider, Ipatco\FilamentProfile\FilamentProfilePlugin, Ipatco\FilamentProfile\Widgets\AccountWidget

### Community 107 - "date-time-picker.js"
Cohesion: 0.26
Nodes (8): d(), e(), i(), m(), r(), s(), t(), rr()

### Community 108 - "🚀 3. Langkah-Langkah Menambahkan Template Baru (*Workflow*)"
Cohesion: 0.12
Nodes (15): 📌 1. Prinsip Utama & Aturan Wajib (Golden Rules), 🏗️ 2. Arsitektur 10 Core Section CMS (1:1 Mapping), 🚀 3. Langkah-Langkah Menambahkan Template Baru (*Workflow*), 📊 4. Variabel yang Disediakan oleh `LandingPageDataService`, 💎 5. Pelajaran Desain & Best Practices UI/UX (*Key Learnings*), A. Larangan Keras Menggunakan Emoji Unicode (Gunakan SVG Heroicons), B. Arsitektur Layering 3D & Background Tembus Pandang (*Stacking Context*), C. Resep Glassmorphism iOS yang Nyata (*True Frosted Glass*) (+7 more)

### Community 109 - "N"
Cohesion: 0.33
Nodes (11): ae(), A(), E(), at(), be(), Gt(), i(), Jt() (+3 more)

### Community 110 - "Mt"
Cohesion: 0.24
Nodes (11): apply(), fs(), go(), Hr(), T(), ir(), it(), Mt() (+3 more)

### Community 111 - "Login"
Cohesion: 0.25
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

### Community 121 - "5. Keuntungan dari fitur — bahasa owner, bukan bahasa sistem"
Cohesion: 0.06
Nodes (34): 1. Cerita yang mungkin terasa familiar, 2.10 Owner tidak punya angka yang bisa dipercaya, 2.11 Tamu tanpa HP, atau minta tambah pesanan, 2.1 Pesanan salah, kurang, atau telat sampai dapur, 2.2 Kasir jadi bottleneck, 2.3 Transfer QRIS yang “mirip-mirip”, 2.4 Tamu “pesan tunai dari luar resto”, 2.5 Dapur dan bar rebutan kertas, tidak tahu mana yang lama (+26 more)

### Community 123 - "require-dev"
Cohesion: 0.25
Nodes (8): require-dev, fakerphp/faker, laravel/pail, laravel/pint, laravel/sail, mockery/mockery, nunomaduro/collision, phpunit/phpunit

### Community 124 - "st"
Cohesion: 0.24
Nodes (11): [g](), _freeze(), getAllExtensions(), Ct(), lt(), ot(), se(), st() (+3 more)

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

### Community 129 - "ProfileInformationForm.php"
Cohesion: 0.40
Nodes (3): ProfileInformationForm, Ipatco\FilamentProfile\Forms\ProfileInformationForm, Ipatco\FilamentProfile\Pages\EditProfile

### Community 130 - "register-restaurant.blade.php"
Cohesion: 0.33
Nodes (5): back, nextFromAccount, nextFromRestaurant, register, selectPlan(

### Community 131 - "add-to-cart-modal.blade.php"
Cohesion: 0.33
Nodes (5): confirmAdd, decrementPickingQty, incrementPickingQty, setVariant({{ $variant->id }}), toggleModifier({{ $modifier->id }})

### Community 132 - "selectOption"
Cohesion: 0.24
Nodes (12): addBadgesForSelectedOptions(), addSingleBadge(), addSingleSelectionDisplay(), createBadgeElement(), createRemoveButton(), getLabelForSingleSelection(), getSelectedOptionLabel(), hideMaxItemsMessage() (+4 more)

### Community 134 - "psr-4"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 135 - "extra"
Cohesion: 0.40
Nodes (5): dev-master, extra, branch-alias, laravel, dont-discover

### Community 136 - "logging.php"
Cohesion: 0.40
Nodes (4): Monolog\Handler\NullHandler, Monolog\Handler\StreamHandler, Monolog\Handler\SyslogUdpHandler, Monolog\Processor\PsrLogMessageProcessor

### Community 137 - "GeoDistance"
Cohesion: 0.20
Nodes (4): GeoDistance, PHPUnit\Framework\TestCase, ExampleTest, GeoDistanceTest

### Community 138 - "Illuminate\Foundation\Http\FormRequest"
Cohesion: 0.08
Nodes (8): AddCartItemRequest, CheckoutRequest, ClaimTableRequest, JoinTableRequest, StorePaymentProofRequest, StoreRestaurantReviewRequest, UpdateCartItemRequest, Illuminate\Foundation\Http\FormRequest

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

### Community 185 - "Ae"
Cohesion: 0.67
Nodes (3): Ae(), Bt(), ne()

### Community 193 - "restaurant-menu-catalog.blade.php"
Cohesion: 0.25
Nodes (7): landing.templates.foodie.sections.footer, landing.templates.foodie.sections.header, landing.templates.glassmorphism.partials.background, landing.templates.glassmorphism.sections.footer, landing.templates.glassmorphism.sections.header, partials.customer.landing-footer, partials.customer.landing-header

### Community 295 - "3. Detail Implementasi Perbaikan Keamanan"
Cohesion: 0.17
Nodes (11): 1. Ringkasan Eksekutif (Executive Summary), 2. Matriks Temuan & Status Perbaikan (Findings & Remediation Matrix), 3. Detail Implementasi Perbaikan Keamanan, 4. Hasil Verifikasi Pengujian Otomatis, A. Proteksi `qr_secret` pada Model (`SEC-01`), B. Middleware HTTP Security Headers (`SEC-02`), C. Pengetatan CORS & Session Cookie (`SEC-03` & `SEC-05`), D. Sanitasi File Upload (`SEC-06`) (+3 more)

### Community 296 - "foodie/show.blade.php"
Cohesion: 0.33
Nodes (5): landing.templates.foodie.sections., landing.templates.foodie.sections.hero, landing.sections., landing.templates.foodie.sections.footer, landing.templates.foodie.sections.header

### Community 297 - "SubscriptionWriteGuard"
Cohesion: 0.28
Nodes (3): BlockGraceMutations, SubscriptionWriteGuard, Livewire\ComponentHook

### Community 301 - "classic/show.blade.php"
Cohesion: 0.50
Nodes (3): landing.sections., partials.customer.landing-footer, partials.customer.landing-header

### Community 322 - "glassmorphism/show.blade.php"
Cohesion: 0.29
Nodes (6): landing.templates.glassmorphism.sections., landing.templates.glassmorphism.sections.hero, landing.sections., landing.templates.glassmorphism.partials.background, landing.templates.glassmorphism.sections.footer, landing.templates.glassmorphism.sections.header

### Community 328 - "MenuItem"
Cohesion: 0.03
Nodes (19): GuestCart, GuestMenu, MenuCategory, MenuItem, LogOptions, VisitCartItem, GuestCartService, CashierMenuCatalog (+11 more)

## Knowledge Gaps
- **323 isolated node(s):** `$schema`, `name`, `type`, `description`, `laravel` (+318 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **37 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `_s()` connect `components/chart.js` to `rich-editor.js`, `o`?**
  _High betweenness centrality (0.030) - this node is a cross-community bridge._
- **Why does `update()` connect `constructor` to `stat/chart.js`, `components/chart.js`, `code-editor.js`, `rich-editor.js`, `facet`, `by`, `get`, `advance`, `markdown-editor.js`, `vd`, `of`, `prop`, `Ye`, `te`, `reduce`, `ne`, `t`, `n`, `slice`, `toString`, `constructor`, `y`, `g$`?**
  _High betweenness centrality (0.027) - this node is a cross-community bridge._
- **Why does `Wi()` connect `Ye` to `code-editor.js`, `rich-editor.js`, `constructor`, `components/select.js`, `of`, `columns/select.js`?**
  _High betweenness centrality (0.026) - this node is a cross-community bridge._
- **Are the 18 inferred relationships involving `constructor()` (e.g. with `a()` and `h()`) actually correct?**
  _`constructor()` has 18 INFERRED edges - model-reasoned connections that need verification._
- **Are the 26 inferred relationships involving `update()` (e.g. with `Pr()` and `a()`) actually correct?**
  _`update()` has 26 INFERRED edges - model-reasoned connections that need verification._
- **What connects `$schema`, `name`, `type` to the rest of the system?**
  _323 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `stat/chart.js` be split into smaller, more focused modules?**
  _Cohesion score 0.010747207000552228 - nodes in this community are weakly interconnected._