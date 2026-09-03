# Graph Report - WALK-IN-RESTO  (2026-09-03)

## Corpus Check
- 627 files · ~295,534 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 8508 nodes · 26253 edges · 345 communities (306 shown, 39 thin omitted)
- Extraction: 91% EXTRACTED · 9% INFERRED · 0% AMBIGUOUS · INFERRED: 2464 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `10472a10`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- stat/chart.js
- components/chart.js
- code-editor.js
- rich-editor.js
- Illuminate\Database\Eloquent\Relations\BelongsTo
- ce
- TestCase
- constructor
- DiningTable
- Illuminate\Http\Request
- r
- slice
- User
- eq
- _update
- toString
- OrderResource
- Order
- get
- BackedEnum
- advance
- fromObject
- Filament\Resources\Pages\ListRecords
- markdown-editor.js
- Restaurant
- support.js
- n
- P
- facet
- columns/select.js
- .slice
- AppServiceProvider.php
- echo.js
- resolve
- ExportFile
- constructor
- updateElements
- Filament\Schemas\Schema
- W
- nodeAt
- InvoicePaymentTest
- o
- Ye
- Dashboard
- SoftDeleteTrashPage
- notifications.js
- parse
- PlatformSetting
- te
- Cn
- components/select.js
- tables.js
- reduce
- r
- E
- SubscriptionStatus
- Xt
- 2026_08_20_010000_add_floor_layout_to_tables_table.php
- filament-right-click.js
- Filament\Tables\Table
- next
- Si
- RendersAnalyticsDashboard.php
- dx
- selectOption
- cc
- CreateCashierOrder
- ir
- create
- slider.js
- ae
- selectOption
- fn
- i
- draw
- ExportFileResource
- Illuminate\Database\Eloquent\Builder
- file-upload.js
- MenuItem
- RestaurantDirectory
- FonnteErrorMessage
- child
- replace
- fn
- AdminPanelProvider.php
- y
- devDependencies
- filament/app.js
- g$
- fn
- selectRecords
- require
- scripts
- Illuminate\Database\Eloquent\Model
- color-picker.js
- js/app.js
- EditProfile
- SubscriptionInvoice
- composer.json
- st
- order-today-stats-widget.blade.php
- .panel
- date-time-picker.js
- 🚀 3. Langkah-Langkah Menambahkan Template Baru (*Workflow*)
- N
- Mt
- Login
- Illuminate\Database\Schema\Blueprint
- _update
- actions/actions.js
- O
- schemas.js
- Landasan Produksi — Tahap 1 (Walk-In Operasional)
- Guest
- KitchenDisplay
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
- addSingleBadge
- components/actions.js
- psr-4
- extra
- logging.php
- SubscriptionAccess
- GuestContext
- getContext
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
- s
- Illuminate\Database\Migrations\Migration
- configure
- Illuminate\Foundation\Http\FormRequest
- getDatasetMeta
- Illuminate\Support\Facades\Schema
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
- Y
- classic/show.blade.php
- S
- rules/graphify.md
- workflows/graphify.md
- A
- sliceDoc
- TenantContext
- addCommands
- _each
- glassmorphism/show.blade.php
- _notify
- CashierFilamentActionsTest
- GeoDistance
- addEventListener
- Vf
- glassmorphism-background.blade.php
- OrderReceiptPrintTest
- SubscriptionWriteGuard

## God Nodes (most connected - your core abstractions)
1. `Restaurant` - 265 edges
2. `User` - 247 edges
3. `constructor()` - 152 edges
4. `update()` - 148 edges
5. `TestCase` - 143 edges
6. `Order` - 108 edges
7. `MenuItem` - 101 edges
8. `resolve()` - 94 edges
9. `y()` - 93 edges
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

## Communities (345 total, 39 thin omitted)

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

### Community 4 - "Illuminate\Database\Eloquent\Relations\BelongsTo"
Cohesion: 0.01
Nodes (52): CmsBanner, LogOptions, CmsFaq, LogOptions, CmsGalleryImage, CmsProfile, LogOptions, restaurant() (+44 more)

### Community 5 - "ce"
Cohesion: 0.08
Nodes (46): Ac(), ao(), bl(), Cc(), ce(), cl(), Cn(), Dc() (+38 more)

### Community 6 - "TestCase"
Cohesion: 0.03
Nodes (52): CashierOrderService, BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles, Bityukov\CommandCenter\Filament\Pages\Commands, Bityukov\CommandCenter\Filament\Pages\History, RolePermissionSeeder, Filament\Facades\Filament, Illuminate\Database\QueryException, Illuminate\Foundation\Testing\RefreshDatabase (+44 more)

### Community 7 - "constructor"
Cohesion: 0.02
Nodes (142): add(), addChunk(), addEventListener(), addInfoPane(), addInner(), addWindowListeners(), adjust(), al() (+134 more)

### Community 8 - "DiningTable"
Cohesion: 0.03
Nodes (26): TableController, DiningTable, Payment, Visit, GuestCheckoutService, MenuModifierService, OrderPaymentService, PaymentProofService (+18 more)

### Community 9 - "Illuminate\Http\Request"
Cohesion: 0.03
Nodes (39): ApplyRestaurantPanelTheme, EnsureApiGuestVisit, EnsureGuestVisit, EnsureRestaurantOperations, EnsureTenantSubscription, IdentifyApiGuestDevice, IdentifyGuestDevice, RequireApiGuestDevice (+31 more)

### Community 10 - "r"
Cohesion: 0.05
Nodes (127): _0(), addNodeView(), addOptions(), addProseMirrorPlugins(), af(), au(), buildProps(), Cc() (+119 more)

### Community 11 - "slice"
Cohesion: 0.04
Nodes (133): a$(), activateHover(), addChanges(), addElement(), Ah(), AX(), b1(), balance() (+125 more)

### Community 12 - "User"
Cohesion: 0.03
Nodes (25): AnalyticsKpiWidget, Role, User, ExportFilePolicy, RolePolicy, UserPolicy, Filament\Models\Contracts\FilamentUser, Filament\Models\Contracts\HasTenants (+17 more)

### Community 13 - "eq"
Cohesion: 0.07
Nodes (40): addNode(), ao(), append(), destroyBetween(), destroyRest(), dragend(), dragleave(), dragover() (+32 more)

### Community 14 - "_update"
Cohesion: 0.04
Nodes (106): addBox(), addElements(), adjustHitBoxes(), afterBuildTicks(), afterCalculateLabelRotation(), afterDataLimits(), afterDraw(), afterFit() (+98 more)

### Community 15 - "toString"
Cohesion: 0.13
Nodes (20): Bc(), check(), checkAttrs(), checkContent(), cn(), endIndex(), getObj(), hasProtocol() (+12 more)

### Community 16 - "OrderResource"
Cohesion: 0.08
Nodes (9): OrderResource, ListOrders, ViewOrder, OrderTodayStatsWidget, Carbon\Carbon, Filament\Infolists\Components\ImageEntry, Filament\Infolists\Components\RepeatableEntry, Filament\Tables\Filters\Filter (+1 more)

### Community 17 - "Order"
Cohesion: 0.03
Nodes (21): OrderReceiptDownloadController, OrderReceiptPrintController, SendWhatsappReceiptJob, Order, OrderItem, OrderReceipt, WhatsappMessage, KdsItemService (+13 more)

### Community 18 - "get"
Cohesion: 0.04
Nodes (87): addBlockWidget(), addBreak(), addComposition(), addDelimiter(), addInlineWidget(), addLine(), addLineStart(), addLineStartIfNotCovered() (+79 more)

### Community 19 - "BackedEnum"
Cohesion: 0.18
Nodes (31): ActivityResource, CmsBannerResource, CmsFaqResource, DiningTableResource, KdsStationResource, ModifierGroupResource, WhatsappMessageResource, BackedEnum (+23 more)

### Community 20 - "advance"
Cohesion: 0.05
Nodes (62): addChild(), addGaps(), addLeafElement(), addNode(), advance(), ATXHeading(), blank(), break() (+54 more)

### Community 21 - "fromObject"
Cohesion: 0.03
Nodes (109): El(), ac(), ae(), after(), Al(), Am(), before(), bl() (+101 more)

### Community 22 - "Filament\Resources\Pages\ListRecords"
Cohesion: 0.06
Nodes (16): ListFacilities, ListLandingTemplates, ListSubscriptionInvoices, ListSubscriptionPlans, ListTenants, ListActivities, MenuItemResource, CreateMenuItem (+8 more)

### Community 23 - "markdown-editor.js"
Cohesion: 0.05
Nodes (83): ad(), af(), An(), bf(), bo(), Bt(), cd(), Ct() (+75 more)

### Community 24 - "Restaurant"
Cohesion: 0.04
Nodes (34): ExpireStaleOperationsCommand, GenerateUpcomingInvoicesCommand, ProcessSubscriptionLifecycleCommand, analyticsDateFrom(), analyticsDateTo(), analyticsDayCount(), analyticsRangeLabel(), analyticsSnapshot() (+26 more)

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

### Community 31 - "AppServiceProvider.php"
Cohesion: 0.06
Nodes (48): Width, AppServiceProvider, Filament\Actions\Action, Filament\Actions\DeleteBulkAction, Filament\Actions\ForceDeleteAction, Filament\Actions\ForceDeleteBulkAction, Filament\Actions\RestoreAction, Filament\Actions\RestoreBulkAction (+40 more)

### Community 32 - "echo.js"
Cohesion: 0.05
Nodes (49): a(), ar(), b(), Be(), Ce(), cr(), d(), De() (+41 more)

### Community 33 - "resolve"
Cohesion: 0.04
Nodes (140): Ad(), addKeyboardShortcuts(), after(), al(), ay(), Bd(), before(), Bg() (+132 more)

### Community 34 - "ExportFile"
Cohesion: 0.05
Nodes (18): PdfExporter, ExportFileDownloadController, CleanupOldExportFilesJob, ExportReportJob, ExportFile, ExportFileObserver, ExportFinishedNotifier, ReportExportDispatcher (+10 more)

### Community 35 - "constructor"
Cohesion: 0.03
Nodes (85): Bc(), bg(), chartOptionScopes(), Cl(), clone(), constructor(), create(), Ct() (+77 more)

### Community 36 - "updateElements"
Cohesion: 0.03
Nodes (113): aa(), acquireContext(), afterAutoSkip(), Ao(), aspectRatio(), bh(), bu(), buildLookupTable() (+105 more)

### Community 37 - "Filament\Schemas\Schema"
Cohesion: 0.05
Nodes (17): ManageBillingAccount, BackedEnum, UnitEnum, ManageHomeLanding, BackedEnum, UnitEnum, ManagePlatformPages, BackedEnum (+9 more)

### Community 38 - "W"
Cohesion: 0.05
Nodes (79): AQ(), atLastNode(), au(), child(), childAfter(), childBefore(), continue(), cursor() (+71 more)

### Community 39 - "nodeAt"
Cohesion: 0.11
Nodes (50): AS(), cellsInRect(), co(), colCount(), content(), createAndFill(), ct(), dS() (+42 more)

### Community 41 - "o"
Cohesion: 0.04
Nodes (121): ag(), ah(), apply(), ar(), au(), average(), Ba(), beforeDatasetDraw() (+113 more)

### Community 42 - "Ye"
Cohesion: 0.10
Nodes (41): Rd(), $a(), ak(), at(), bk(), c(), bp(), Dk() (+33 more)

### Community 43 - "Dashboard"
Cohesion: 0.05
Nodes (13): EditFacility, EditLandingTemplate, EditSubscriptionPlan, SubscriptionPlanResource, EditTenant, Dashboard, EditRestaurantCategory, EditRestaurant (+5 more)

### Community 44 - "SoftDeleteTrashPage"
Cohesion: 0.04
Nodes (22): SoftDeleteTrashPage, ManageCmsBanners, TrashCmsBanners, ManageCmsFaqs, TrashCmsFaqs, CmsGalleryImageResource, ManageCmsGalleryImages, TrashCmsGalleryImages (+14 more)

### Community 45 - "notifications.js"
Cohesion: 0.06
Nodes (31): actions(), button(), c(), close(), configureAnimations(), configureTransitions(), constructor(), danger() (+23 more)

### Community 46 - "parse"
Cohesion: 0.06
Nodes (54): buildOrUpdateElements(), Cn(), determineDataLimits(), diff(), dn(), el(), En(), endOf() (+46 more)

### Community 47 - "PlatformSetting"
Cohesion: 0.02
Nodes (25): WelcomeBannerWidget, PlatformPageController, RestaurantLandingController, RegisterRestaurant, RestaurantMenuCatalog, self, PlatformSetting, LandingPageDataService (+17 more)

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

### Community 56 - "E"
Cohesion: 0.05
Nodes (61): $a(), add(), af(), B(), bo(), bs(), ca(), _cachedScopes() (+53 more)

### Community 57 - "SubscriptionStatus"
Cohesion: 0.10
Nodes (4): BackedEnum, UnitEnum, SubscriptionStatus, SubscriptionPlan

### Community 58 - "Xt"
Cohesion: 0.12
Nodes (44): ae(), At(), bi(), bn(), ci(), cn(), ct(), de() (+36 more)

### Community 60 - "filament-right-click.js"
Cohesion: 0.11
Nodes (42): a(), ae(), b(), be(), C(), ce(), D(), de() (+34 more)

### Community 61 - "Filament\Tables\Table"
Cohesion: 0.07
Nodes (11): ViewActivity, ActivitiesTable, TableRightClick, PendingPaymentsWidget, Activity, ActivityPresenter, Filament\Actions\ViewAction, Filament\Resources\Pages\ViewRecord (+3 more)

### Community 62 - "next"
Cohesion: 0.08
Nodes (33): activeForPoint(), addActive(), addBlock(), addLineDeco(), Ar(), as(), blankContent(), boundChange() (+25 more)

### Community 63 - "Si"
Cohesion: 0.13
Nodes (41): ae(), At(), bi(), bn(), ci(), cn(), ct(), de() (+33 more)

### Community 64 - "RendersAnalyticsDashboard.php"
Cohesion: 0.09
Nodes (19): AnalyticsPeriodSummaryWidget, AnalyticsRevenueBarWidget, AnalyticsSidebarWidget, AnalyticsTopMenuWidget, generateChartDataChecksum(), getCachedChartData(), getChartData(), mountRefreshesAnalyticsChart() (+11 more)

### Community 65 - "dx"
Cohesion: 0.09
Nodes (38): Ei(), Aa(), ai(), Ba(), Bi(), cf(), da(), fa() (+30 more)

### Community 66 - "selectOption"
Cohesion: 0.12
Nodes (39): addBadgesForSelectedOptions(), addSingleBadge(), addSingleSelectionDisplay(), closeDropdown(), constructor(), createBadgeElement(), createOptionElement(), createRemoveButton() (+31 more)

### Community 67 - "cc"
Cohesion: 0.22
Nodes (11): attrs(), bi(), cc(), cO(), JQ(), m$(), Ow(), rc() (+3 more)

### Community 68 - "CreateCashierOrder"
Cohesion: 0.09
Nodes (6): CreateCashierOrder, BackedEnum, UnitEnum, Width, CashierMenuCatalog, CashierMenuCatalogTest

### Community 69 - "ir"
Cohesion: 0.13
Nodes (34): Ft(), ir(), ce(), de(), Dt(), ee(), Et(), fe() (+26 more)

### Community 70 - "create"
Cohesion: 0.04
Nodes (118): Ac(), addAll(), addDOM(), addElement(), addElementByRule(), addNodeMark(), addTextNode(), addToSet() (+110 more)

### Community 71 - "slider.js"
Cohesion: 0.11
Nodes (33): ar(), Be(), Ce(), De(), _e(), Ee(), er(), et() (+25 more)

### Community 72 - "ae"
Cohesion: 0.09
Nodes (34): ae(), Ao(), as(), B(), Kt(), cs(), Ee(), Ge() (+26 more)

### Community 73 - "selectOption"
Cohesion: 0.15
Nodes (33): addSingleSelectionDisplay(), closeDropdown(), constructor(), createOptionElement(), deferPositionDropdown(), destroy(), filterOptions(), focusNextOption() (+25 more)

### Community 74 - "fn"
Cohesion: 0.13
Nodes (33): aa(), At(), ba(), cr(), da(), de(), dt(), ei() (+25 more)

### Community 75 - "i"
Cohesion: 0.05
Nodes (80): aa(), applyChanges(), balanced(), baseIndent(), baseIndentFor(), Bg(), bidiSpans(), blockAt() (+72 more)

### Community 76 - "draw"
Cohesion: 0.09
Nodes (34): addElements(), bi(), bindEvents(), bindUserEvents(), buildOrUpdateScales(), _checkEventBindings(), clear(), _dataCheck() (+26 more)

### Community 77 - "ExportFileResource"
Cohesion: 0.12
Nodes (6): GenerateReport, BackedEnum, UnitEnum, ExportFileResource, ListExportFiles, TrashExportFiles

### Community 78 - "Illuminate\Database\Eloquent\Builder"
Cohesion: 0.08
Nodes (8): getRecordRouteBindingEloquentQuery(), periodSummary(), bootScopedToRestaurant(), scopeForRestaurant(), scopeWithoutRestaurantScope(), RestaurantDirectory, Illuminate\Contracts\Pagination\LengthAwarePaginator, Illuminate\Database\Eloquent\Builder

### Community 79 - "file-upload.js"
Cohesion: 0.05
Nodes (53): hc(), Bp(), c(), ca(), clickPercent(), constructor(), Cp(), da() (+45 more)

### Community 81 - "MenuItem"
Cohesion: 0.05
Nodes (15): TemplateRadioPicker, MenuItem, LogOptions, Modifier, CashierOrderPreview, CheckoutTotals, Filament\Forms\Components\Field, Illuminate\Database\Eloquent\Collection (+7 more)

### Community 84 - "FonnteErrorMessage"
Cohesion: 0.11
Nodes (8): FonnteClient, FonnteErrorMessage, Throwable, Illuminate\Http\Client\ConnectionException, Illuminate\Http\Client\Response, PHPUnit\Framework\Attributes\DataProvider, RuntimeException, FonnteErrorMessageTest

### Community 85 - "child"
Cohesion: 0.10
Nodes (33): addInner(), Bm(), child(), dg(), eat(), err(), ew(), findIndex() (+25 more)

### Community 86 - "replace"
Cohesion: 0.07
Nodes (36): addToSet(), childString(), decompose(), decomposeLeft(), decomposeRight(), flushIOSKey(), FO(), getReplacement() (+28 more)

### Community 87 - "fn"
Cohesion: 0.08
Nodes (35): themeClasses(), addAttributes(), b1(), Ck(), coordsAtPos(), De(), fn(), Gh() (+27 more)

### Community 89 - "AdminPanelProvider.php"
Cohesion: 0.16
Nodes (19): ApplyPlatformBrandTheme, BezhanSalleh\FilamentShield\FilamentShieldPlugin, Bityukov\CommandCenter\Filament\CommandCenterPlugin, Filament\Http\Middleware\Authenticate, Filament\Http\Middleware\AuthenticateSession, Filament\Http\Middleware\DisableBladeIconComponents, Filament\Http\Middleware\DispatchServingFilamentEvent, Filament\Navigation\NavigationGroup (+11 more)

### Community 90 - "y"
Cohesion: 0.18
Nodes (49): al(), at(), Be(), Cr(), de(), dt(), Ee(), ef() (+41 more)

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

### Community 98 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.03
Nodes (27): canDelete(), canDeleteAny(), canForceDelete(), canRestore(), Action, trashPageAction(), FacilityResource, CreateFacility (+19 more)

### Community 99 - "color-picker.js"
Cohesion: 0.14
Nodes (3): style(), update(), [x]()

### Community 100 - "js/app.js"
Cohesion: 0.22
Nodes (6): applyTheme(), bindThemeToggle(), initReveal(), initTheme(), syncThemeToggleIcons(), toggleTheme()

### Community 102 - "SubscriptionInvoice"
Cohesion: 0.10
Nodes (6): FounderStatsWidget, SubscriptionInvoice, SubscriptionInvoiceService, Filament\Widgets\StatsOverviewWidget, Filament\Widgets\StatsOverviewWidget\Stat, GenerateUpcomingInvoicesTest

### Community 103 - "composer.json"
Cohesion: 0.14
Nodes (13): autoload-dev, psr-4, description, keywords, license, minimum-stability, name, prefer-stable (+5 more)

### Community 104 - "st"
Cohesion: 0.05
Nodes (48): ad(), applyStack(), br(), Di(), drawCaret(), _f(), first(), getCaretPosition() (+40 more)

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

### Community 113 - "_update"
Cohesion: 0.05
Nodes (63): active(), afterBuildTicks(), afterCalculateLabelRotation(), afterDataLimits(), afterDatasetsUpdate(), afterFit(), afterSetDimensions(), afterTickToLabelConversion() (+55 more)

### Community 115 - "actions/actions.js"
Cohesion: 0.44
Nodes (8): closeModal(), generateModalId(), getActionNestingIndexFromModalId(), init(), openModal(), rememberPreviouslyFocusedElement(), restorePreviouslyFocusedElement(), syncActionModals()

### Community 116 - "O"
Cohesion: 0.19
Nodes (38): b(), $c(), X(), ca(), me(), D(), _e(), Ea() (+30 more)

### Community 118 - "Landasan Produksi — Tahap 1 (Walk-In Operasional)"
Cohesion: 0.05
Nodes (37): 10. Aturan yang tidak boleh dilanggar, 11. Matriks skenario lapangan, 12. Yang sengaja tidak didukung (bukan gap, non-goal), 13. Tahap 2 (bukan sekarang), 14. Urutan bangun (agar cepat produksi), 1. Keputusan terkunci, 2. Modul tahap 1 vs ditunda, 3. Model domain (+29 more)

### Community 119 - "Guest"
Cohesion: 0.05
Nodes (34): Bentuk objek, CartItemResource, DELETE `/guest/cart/items/{cartItem}`, GET `/guest/cart`, GET `/guest/menu`, GET `/guest/orders`, GET `/guest/orders/{public_id}`, GET `/guest/review` (+26 more)

### Community 120 - "KitchenDisplay"
Cohesion: 0.11
Nodes (7): ExcelExporter, KitchenDisplay, BackedEnum, UnitEnum, Illuminate\Contracts\View\View, Maatwebsite\Excel\Concerns\FromView, Maatwebsite\Excel\Concerns\ShouldAutoSize

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
Cohesion: 0.29
Nodes (6): cancelPicking, confirmAdd, decrementPickingQty, incrementPickingQty, setVariant({{ $variant->id }}), toggleModifier({{ $modifier->id }})

### Community 132 - "addSingleBadge"
Cohesion: 0.33
Nodes (6): addBadgesForSelectedOptions(), addSingleBadge(), createBadgeElement(), createRemoveButton(), getLabelForSingleSelection(), getSelectedOptionLabel()

### Community 134 - "psr-4"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 135 - "extra"
Cohesion: 0.40
Nodes (5): dev-master, extra, branch-alias, laravel, dont-discover

### Community 136 - "logging.php"
Cohesion: 0.40
Nodes (4): Monolog\Handler\NullHandler, Monolog\Handler\StreamHandler, Monolog\Handler\SyslogUdpHandler, Monolog\Processor\PsrLogMessageProcessor

### Community 137 - "SubscriptionAccess"
Cohesion: 0.07
Nodes (10): canCreate(), canEdit(), canViewAny(), ManageCmsProfile, BackedEnum, UnitEnum, OutletResource, Action (+2 more)

### Community 138 - "GuestContext"
Cohesion: 0.03
Nodes (35): CartController, CheckoutController, MenuController, OrderController, ReviewController, SessionController, VisitController, RestaurantController (+27 more)

### Community 139 - "getContext"
Cohesion: 0.07
Nodes (52): acquireContext(), Ao(), bl(), buildTicks(), Ca(), calculateLabelRotation(), _calculatePadding(), ci() (+44 more)

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

### Community 153 - "s"
Cohesion: 0.05
Nodes (62): aa(), addEventListener(), Ae(), ai(), al(), an(), _animateOptions(), bindResponsiveEvents() (+54 more)

### Community 163 - "configure"
Cohesion: 0.06
Nodes (45): add(), _cachedScopes(), configure(), createResolver(), datasetAnimationScopeKeys(), datasetElementScopeKeys(), datasetScopeKeys(), get() (+37 more)

### Community 176 - "Illuminate\Foundation\Http\FormRequest"
Cohesion: 0.08
Nodes (8): AddCartItemRequest, CheckoutRequest, ClaimTableRequest, JoinTableRequest, StorePaymentProofRequest, StoreRestaurantReviewRequest, UpdateCartItemRequest, Illuminate\Foundation\Http\FormRequest

### Community 180 - "getDatasetMeta"
Cohesion: 0.11
Nodes (26): afterDatasetsUpdate(), An(), generateLabels(), getDatasetMeta(), getDataVisibility(), _getLegendItemAt(), getMaxBorderWidth(), _getSortedDatasetMetas() (+18 more)

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

### Community 297 - "Y"
Cohesion: 0.11
Nodes (22): at(), Bf(), determineDataLimits(), ef(), getMatchingVisibleMetas(), getMinMax(), _getOtherScale(), getUserBounds() (+14 more)

### Community 301 - "classic/show.blade.php"
Cohesion: 0.50
Nodes (3): landing.sections., partials.customer.landing-footer, partials.customer.landing-header

### Community 302 - "S"
Cohesion: 0.10
Nodes (28): afterAutoSkip(), Bt(), buildLookupTable(), da(), drawTitle(), Ds(), Fs(), getBasePixel() (+20 more)

### Community 313 - "A"
Cohesion: 0.07
Nodes (38): Ot(), A(), apply(), As(), chartOptionScopes(), _computeLabelSizes(), constructor(), cr() (+30 more)

### Community 317 - "sliceDoc"
Cohesion: 0.15
Nodes (19): aO(), charCategorizer(), Fc(), flatten(), getCursor(), getDeco(), gT(), highlight() (+11 more)

### Community 318 - "TenantContext"
Cohesion: 0.09
Nodes (7): ManageDiningTables, Action, Closure, bootBelongsToRestaurantAndOutlet(), BelongsToRestaurantScope, TenantContext, Illuminate\Database\Eloquent\Scope

### Community 320 - "addCommands"
Cohesion: 0.13
Nodes (26): addCommands(), addStoredMark(), computeAttrs(), createChecked(), ensureMarks(), handleExit(), i1(), insertText() (+18 more)

### Community 321 - "_each"
Cohesion: 0.12
Nodes (17): addControllers(), addPlugins(), addScales(), _each(), _exec(), _getRegistryForType(), invalidate(), isForType() (+9 more)

### Community 322 - "glassmorphism/show.blade.php"
Cohesion: 0.29
Nodes (6): landing.templates.glassmorphism.sections., landing.templates.glassmorphism.sections.hero, landing.sections., landing.templates.glassmorphism.partials.background, landing.templates.glassmorphism.sections.footer, landing.templates.glassmorphism.sections.header

### Community 324 - "_notify"
Cohesion: 0.20
Nodes (14): active(), _animateOptions(), cancel(), _createAnimations(), _createDescriptors(), _descriptors(), _notify(), _notifyStateChanges() (+6 more)

### Community 327 - "GeoDistance"
Cohesion: 0.20
Nodes (4): GeoDistance, PHPUnit\Framework\TestCase, ExampleTest, GeoDistanceTest

### Community 328 - "addEventListener"
Cohesion: 0.33
Nodes (7): addEventListener(), bindResponsiveEvents(), fu(), isAttached(), nr(), removeEventListener(), Ua()

### Community 340 - "Vf"
Cohesion: 0.33
Nodes (7): contains(), gi(), splitAt(), toISOTime(), toMillis(), Vf(), ye()

### Community 344 - "SubscriptionWriteGuard"
Cohesion: 0.28
Nodes (3): BlockGraceMutations, SubscriptionWriteGuard, Livewire\ComponentHook

## Knowledge Gaps
- **324 isolated node(s):** `$schema`, `name`, `type`, `description`, `laravel` (+319 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **39 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `_s()` connect `components/chart.js` to `o`, `rich-editor.js`?**
  _High betweenness centrality (0.031) - this node is a cross-community bridge._
- **Why does `Wi()` connect `Ye` to `code-editor.js`, `rich-editor.js`, `constructor`, `components/select.js`, `facet`, `columns/select.js`?**
  _High betweenness centrality (0.026) - this node is a cross-community bridge._
- **Why does `update()` connect `constructor` to `stat/chart.js`, `code-editor.js`, `rich-editor.js`, `slice`, `get`, `advance`, `fromObject`, `markdown-editor.js`, `n`, `facet`, `echo.js`, `resolve`, `W`, `Ye`, `te`, `reduce`, `sliceDoc`, `next`, `dx`, `i`, `replace`, `fn`, `g$`, `O`?**
  _High betweenness centrality (0.019) - this node is a cross-community bridge._
- **Are the 18 inferred relationships involving `constructor()` (e.g. with `a()` and `h()`) actually correct?**
  _`constructor()` has 18 INFERRED edges - model-reasoned connections that need verification._
- **Are the 26 inferred relationships involving `update()` (e.g. with `Pr()` and `a()`) actually correct?**
  _`update()` has 26 INFERRED edges - model-reasoned connections that need verification._
- **What connects `$schema`, `name`, `type` to the rest of the system?**
  _324 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `stat/chart.js` be split into smaller, more focused modules?**
  _Cohesion score 0.024135681669928244 - nodes in this community are weakly interconnected._