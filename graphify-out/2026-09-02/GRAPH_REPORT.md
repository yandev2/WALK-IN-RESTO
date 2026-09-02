# Graph Report - WALK-IN-RESTO  (2026-09-02)

## Corpus Check
- 620 files · ~287,411 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 8462 nodes · 26137 edges · 340 communities (306 shown, 34 thin omitted)
- Extraction: 91% EXTRACTED · 9% INFERRED · 0% AMBIGUOUS · INFERRED: 2462 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `91504d36`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- stat/chart.js
- components/chart.js
- code-editor.js
- rich-editor.js
- MenuItem
- draw
- TestCase
- constructor
- nodeAt
- GuestContext
- r
- i
- User
- Restaurant
- _update
- Visit
- Illuminate\Http\Request
- DiningTable
- get
- Filament\Tables\Table
- slice
- Order
- Dashboard
- markdown-editor.js
- PlatformSetting
- support.js
- n
- marks
- facet
- columns/select.js
- .slice
- AppServiceProvider.php
- echo.js
- constructor
- ExportFile
- resolve
- i
- Filament\Schemas\Schema
- W
- toString
- SubscriptionInvoice
- SubscriptionAccess
- Ye
- Filament\Resources\Pages\ListRecords
- SoftDeleteTrashPage
- notifications.js
- updateElements
- getContext
- te
- Cn
- y
- components/select.js
- constructor
- tables.js
- ce
- r
- Illuminate\Database\Eloquent\Builder
- SubscriptionStatus
- Xt
- TenantContext
- filament-right-click.js
- Activity
- t
- Si
- RendersAnalyticsDashboard.php
- dx
- selectOption
- fo
- CreateCashierOrder
- ir
- RegisterRestaurant.php
- slider.js
- ae
- selectOption
- fn
- next
- eq
- Illuminate\Foundation\Http\FormRequest
- constructor
- file-upload.js
- Pe
- addElementByRule
- E
- RestaurantDirectory
- FonnteErrorMessage
- be
- g$
- fn
- _notify
- AdminPanelProvider.php
- _generate
- devDependencies
- filament/app.js
- cc
- fn
- selectRecords
- require
- scripts
- Illuminate\Database\Eloquent\Model
- color-picker.js
- js/app.js
- EditProfile
- CmsMedia
- composer.json
- getDatasetMeta
- OrderReceiptPrintTest
- .panel
- date-time-picker.js
- 🚀 3. Langkah-Langkah Menambahkan Template Baru (*Workflow*)
- N
- Mt
- Login
- Illuminate\Support\Facades\Schema
- actions/actions.js
- create
- schemas.js
- Landasan Produksi — Tahap 1 (Walk-In Operasional)
- Guest
- N
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
- GeoDistance
- MenuItemResource
- fd
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
- o
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
- parse
- _update
- ir
- get
- addEventListener
- glassmorphism/show.blade.php
- S
- l
- _resolveAnimations
- LandingTemplate

## God Nodes (most connected - your core abstractions)
1. `Restaurant` - 260 edges
2. `User` - 245 edges
3. `constructor()` - 152 edges
4. `update()` - 148 edges
5. `TestCase` - 139 edges
6. `Order` - 105 edges
7. `resolve()` - 94 edges
8. `y()` - 93 edges
9. `MenuItem` - 92 edges
10. `_update()` - 87 edges

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

## Communities (340 total, 34 thin omitted)

### Community 0 - "stat/chart.js"
Cohesion: 0.03
Nodes (80): themeClasses(), addControllers(), addPlugins(), addScales(), applyStack(), ar(), beforeDatasetsDraw(), beforeDraw() (+72 more)

### Community 1 - "components/chart.js"
Cohesion: 0.01
Nodes (142): abutsStart(), addControllers(), addPlugins(), addScales(), Be(), beforeDraw(), bm(), Bn() (+134 more)

### Community 2 - "code-editor.js"
Cohesion: 0.01
Nodes (136): Ac(), addCompletion(), addCompletions(), addNamespace(), addNamespaceObject(), Ag(), AX(), b0() (+128 more)

### Community 3 - "rich-editor.js"
Cohesion: 0.01
Nodes (172): Rd(), $a(), aa(), addGlobalAttributes(), addHackNode(), addNodeMark(), addTextblockHacks(), an() (+164 more)

### Community 4 - "MenuItem"
Cohesion: 0.02
Nodes (46): CmsBanner, LogOptions, CmsFaq, LogOptions, CmsGalleryImage, CmsProfile, LogOptions, restaurant() (+38 more)

### Community 5 - "draw"
Cohesion: 0.05
Nodes (95): acquireContext(), adjustHitBoxes(), afterDraw(), bh(), bu(), buildTicks(), calculateLabelRotation(), _calculatePadding() (+87 more)

### Community 6 - "TestCase"
Cohesion: 0.03
Nodes (55): BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles, Bityukov\CommandCenter\Filament\Pages\Commands, Bityukov\CommandCenter\Filament\Pages\History, DatabaseSeeder, RolePermissionSeeder, Filament\Facades\Filament, Illuminate\Database\QueryException, Illuminate\Foundation\Testing\RefreshDatabase (+47 more)

### Community 7 - "constructor"
Cohesion: 0.02
Nodes (180): active(), add(), addChunk(), addEventListener(), addInfoPane(), addInner(), addSelection(), addWindowListeners() (+172 more)

### Community 8 - "nodeAt"
Cohesion: 0.06
Nodes (79): Ac(), addCommands(), AS(), Bm(), cellsInRect(), closest(), colCount(), colSelection() (+71 more)

### Community 9 - "GuestContext"
Cohesion: 0.04
Nodes (30): CartController, CheckoutController, MenuController, OrderController, ReviewController, SessionController, TableController, VisitController (+22 more)

### Community 10 - "r"
Cohesion: 0.05
Nodes (128): _0(), addNodeView(), addOptions(), addProseMirrorPlugins(), af(), allowedMarks(), au(), bl() (+120 more)

### Community 11 - "i"
Cohesion: 0.04
Nodes (145): aa(), addElement(), b1(), balance(), balanced(), baseIndent(), baseIndentFor(), Bg() (+137 more)

### Community 12 - "User"
Cohesion: 0.03
Nodes (25): Role, LogOptions, User, RolePolicy, UserPolicy, PermissionCheck, Filament\Models\Contracts\FilamentUser, Filament\Models\Contracts\HasTenants (+17 more)

### Community 13 - "Restaurant"
Cohesion: 0.03
Nodes (36): ExpireStaleOperationsCommand, GenerateUpcomingInvoicesCommand, ProcessSubscriptionLifecycleCommand, analyticsDateFrom(), analyticsDateTo(), analyticsDayCount(), analyticsRangeLabel(), analyticsSnapshot() (+28 more)

### Community 14 - "_update"
Cohesion: 0.03
Nodes (117): addBox(), addElements(), afterBuildTicks(), afterCalculateLabelRotation(), afterDataLimits(), afterDatasetsUpdate(), afterFit(), afterSetDimensions() (+109 more)

### Community 15 - "Visit"
Cohesion: 0.04
Nodes (18): Visit, VisitDevice, CashierOrderService, GuestCheckoutService, MenuModifierService, OrderPaymentService, StaleOperationsService, TableOpsService (+10 more)

### Community 16 - "Illuminate\Http\Request"
Cohesion: 0.05
Nodes (34): RestaurantMenuController, ApplyRestaurantPanelTheme, EnsureApiGuestVisit, EnsureGuestVisit, EnsureRestaurantOperations, EnsureTenantSubscription, IdentifyApiGuestDevice, IdentifyGuestDevice (+26 more)

### Community 17 - "DiningTable"
Cohesion: 0.03
Nodes (14): periodSummary(), DiningTable, LogOptions, RestaurantCategory, RestaurantDirectory, TableFloorPlan, TableQrToken, RestaurantCategorySeeder (+6 more)

### Community 18 - "get"
Cohesion: 0.04
Nodes (100): addBlockWidget(), addBreak(), addComposition(), addDelimiter(), addInlineWidget(), addLine(), addLineStart(), addLineStartIfNotCovered() (+92 more)

### Community 19 - "Filament\Tables\Table"
Cohesion: 0.14
Nodes (32): ActivitiesTable, CmsBannerResource, CmsGalleryImageResource, KdsStationResource, TableRightClick, BackedEnum, Filament\Actions\DeleteAction, Filament\Actions\EditAction (+24 more)

### Community 20 - "slice"
Cohesion: 0.03
Nodes (125): addActions(), addChild(), addGaps(), addLeafElement(), addNode(), advance(), advanceFully(), advanceStack() (+117 more)

### Community 21 - "Order"
Cohesion: 0.03
Nodes (22): PdfExporter, ExportFileDownloadController, OrderReceiptDownloadController, OrderReceiptPrintController, Order, OrderItem, OrderReceipt, WhatsappMessage (+14 more)

### Community 22 - "Dashboard"
Cohesion: 0.06
Nodes (10): EditFacility, EditLandingTemplate, EditSubscriptionPlan, SubscriptionPlanResource, EditTenant, Dashboard, EditRestaurantCategory, EditRestaurant (+2 more)

### Community 23 - "markdown-editor.js"
Cohesion: 0.05
Nodes (85): ad(), af(), ai(), al(), An(), ao(), bo(), br() (+77 more)

### Community 24 - "PlatformSetting"
Cohesion: 0.02
Nodes (26): PlatformPageController, RestaurantLandingController, RestaurantResource, RestaurantMenuCatalog, self, PlatformSetting, LandingPageDataService, FilamentTenantTheme (+18 more)

### Community 25 - "support.js"
Cohesion: 0.05
Nodes (75): acquireScrollLock(), ai(), e(), Bi(), br(), Bt(), ca(), close() (+67 more)

### Community 26 - "n"
Cohesion: 0.09
Nodes (80): _a(), Ae(), ar(), as(), Ba(), bc(), bf(), ee() (+72 more)

### Community 27 - "marks"
Cohesion: 0.09
Nodes (35): addInputRules(), addMark(), addPasteRules(), addStoredMark(), Ah(), Ax(), dispatchTransaction(), ea() (+27 more)

### Community 28 - "facet"
Cohesion: 0.04
Nodes (83): accept(), activateHover(), Ah(), applyTransaction(), asSingle(), baseTheme(), between(), bi() (+75 more)

### Community 29 - "columns/select.js"
Cohesion: 0.06
Nodes (57): A(), An(), applyDisabledState(), b(), Bt(), Ce(), D(), disable() (+49 more)

### Community 30 - ".slice"
Cohesion: 0.05
Nodes (71): accepts(), addAttributes(), addInner(), addMaps(), addStep(), addTransform(), appendMap(), appendMapping() (+63 more)

### Community 31 - "AppServiceProvider.php"
Cohesion: 0.06
Nodes (48): Width, AppServiceProvider, Filament\Actions\Action, Filament\Actions\DeleteBulkAction, Filament\Actions\ForceDeleteAction, Filament\Actions\ForceDeleteBulkAction, Filament\Actions\RestoreAction, Filament\Actions\RestoreBulkAction (+40 more)

### Community 32 - "echo.js"
Cohesion: 0.05
Nodes (49): a(), ar(), b(), Be(), Ce(), cr(), d(), De() (+41 more)

### Community 33 - "constructor"
Cohesion: 0.03
Nodes (140): Ad(), add(), addExtensions(), applyInitialSize(), ay(), Bd(), Bg(), Bo() (+132 more)

### Community 34 - "ExportFile"
Cohesion: 0.05
Nodes (17): CleanupOldExportFilesJob, ExportReportJob, SendWhatsappReceiptJob, ExportFile, ExportFileObserver, ExportFilePolicy, ExportFinishedNotifier, ReportExportDispatcher (+9 more)

### Community 35 - "resolve"
Cohesion: 0.06
Nodes (132): addKeyboardShortcuts(), after(), ag(), al(), allowsMarks(), before(), blockRange(), Bs() (+124 more)

### Community 36 - "i"
Cohesion: 0.05
Nodes (63): $a(), ah(), alpha(), apply(), average(), Bt(), ca(), Ci() (+55 more)

### Community 37 - "Filament\Schemas\Schema"
Cohesion: 0.04
Nodes (18): ManageBillingAccount, BackedEnum, UnitEnum, ManageHomeLanding, BackedEnum, UnitEnum, ManagePlatformPages, BackedEnum (+10 more)

### Community 38 - "W"
Cohesion: 0.05
Nodes (70): AQ(), atLastNode(), au(), child(), childAfter(), childBefore(), continue(), cursor() (+62 more)

### Community 39 - "toString"
Cohesion: 0.13
Nodes (21): Bc(), check(), checkAttrs(), checkContent(), endIndex(), getObj(), hasProtocol(), $i() (+13 more)

### Community 40 - "SubscriptionInvoice"
Cohesion: 0.05
Nodes (7): FounderStatsWidget, SubscriptionInvoice, SubscriptionInvoiceService, Filament\Widgets\StatsOverviewWidget, Filament\Widgets\StatsOverviewWidget\Stat, Illuminate\Support\Facades\DB, InvoicePaymentTest

### Community 41 - "SubscriptionAccess"
Cohesion: 0.05
Nodes (14): canCreate(), canEdit(), canViewAny(), ManageCmsProfile, BackedEnum, UnitEnum, OrderResource, ListOrders (+6 more)

### Community 42 - "Ye"
Cohesion: 0.12
Nodes (35): ak(), at(), bk(), c(), bp(), Dk(), dp(), Ek() (+27 more)

### Community 43 - "Filament\Resources\Pages\ListRecords"
Cohesion: 0.05
Nodes (20): CreateFacility, ListFacilities, CreateLandingTemplate, ListLandingTemplates, CreateSubscriptionInvoice, ListSubscriptionInvoices, ViewSubscriptionInvoice, SubscriptionInvoiceResource (+12 more)

### Community 44 - "SoftDeleteTrashPage"
Cohesion: 0.04
Nodes (22): SoftDeleteTrashPage, ManageCmsBanners, TrashCmsBanners, CmsFaqResource, ManageCmsFaqs, TrashCmsFaqs, ManageCmsGalleryImages, TrashCmsGalleryImages (+14 more)

### Community 45 - "notifications.js"
Cohesion: 0.06
Nodes (31): actions(), button(), c(), close(), configureAnimations(), configureTransitions(), constructor(), danger() (+23 more)

### Community 46 - "updateElements"
Cohesion: 0.05
Nodes (63): addEventListener(), afterAutoSkip(), Ao(), aspectRatio(), au(), bindResponsiveEvents(), buildLookupTable(), _calculateBarIndexPixels() (+55 more)

### Community 47 - "getContext"
Cohesion: 0.07
Nodes (47): acquireContext(), Ae(), buildTicks(), Ca(), calculateLabelRotation(), _calculatePadding(), _computeGridLineItems(), _computeLabelArea() (+39 more)

### Community 48 - "te"
Cohesion: 0.05
Nodes (11): Bn(), Id(), ji(), on(), qd(), qi(), Ri(), te() (+3 more)

### Community 49 - "Cn"
Cohesion: 0.13
Nodes (46): Cn(), b(), Be(), Ce(), De(), dn(), _e(), Fe() (+38 more)

### Community 50 - "y"
Cohesion: 0.16
Nodes (70): at(), b(), Be(), $c(), X(), me(), Cr(), Ct() (+62 more)

### Community 51 - "components/select.js"
Cohesion: 0.08
Nodes (38): A(), applyDisabledState(), b(), Bt(), D(), disable(), E(), en() (+30 more)

### Community 52 - "constructor"
Cohesion: 0.03
Nodes (106): Oe(), ac(), ae(), after(), ag(), Al(), Am(), Bc() (+98 more)

### Community 53 - "tables.js"
Cohesion: 0.13
Nodes (44): A(), ae(), B(), be(), C(), ce(), E(), ee() (+36 more)

### Community 54 - "ce"
Cohesion: 0.09
Nodes (41): Ac(), bl(), Cc(), ce(), cl(), Dc(), Do(), Ec() (+33 more)

### Community 55 - "r"
Cohesion: 0.15
Nodes (43): _a(), ar(), c(), f(), d(), di(), g(), Hi() (+35 more)

### Community 56 - "Illuminate\Database\Eloquent\Builder"
Cohesion: 0.09
Nodes (10): getRecordRouteBindingEloquentQuery(), KitchenDisplay, BackedEnum, UnitEnum, bootScopedToRestaurant(), scopeForRestaurant(), scopeWithoutRestaurantScope(), BelongsToRestaurantScope (+2 more)

### Community 57 - "SubscriptionStatus"
Cohesion: 0.10
Nodes (4): BackedEnum, UnitEnum, SubscriptionStatus, SubscriptionPlan

### Community 58 - "Xt"
Cohesion: 0.12
Nodes (44): ae(), At(), bi(), bn(), ci(), cn(), ct(), de() (+36 more)

### Community 59 - "TenantContext"
Cohesion: 0.08
Nodes (11): ExcelExporter, DiningTableResource, ManageDiningTables, Action, Closure, TrashDiningTables, bootBelongsToRestaurantAndOutlet(), TenantContext (+3 more)

### Community 60 - "filament-right-click.js"
Cohesion: 0.11
Nodes (42): a(), ae(), b(), be(), C(), ce(), D(), de() (+34 more)

### Community 61 - "Activity"
Cohesion: 0.07
Nodes (10): ActivityResource, ListActivities, ViewActivity, ActivityInfolist, ViewOrder, Activity, ActivityPresenter, Filament\Infolists\Components\KeyValueEntry (+2 more)

### Community 62 - "t"
Cohesion: 0.06
Nodes (49): a$(), activeForPoint(), addBlock(), addLineDeco(), baseDirAt(), bidiIn(), bidiSpansAt(), blankContent() (+41 more)

### Community 63 - "Si"
Cohesion: 0.13
Nodes (41): ae(), At(), bi(), bn(), ci(), cn(), ct(), de() (+33 more)

### Community 64 - "RendersAnalyticsDashboard.php"
Cohesion: 0.09
Nodes (19): AnalyticsKpiWidget, AnalyticsPeriodSummaryWidget, AnalyticsRevenueBarWidget, AnalyticsSidebarWidget, AnalyticsTopMenuWidget, generateChartDataChecksum(), getCachedChartData(), getChartData() (+11 more)

### Community 65 - "dx"
Cohesion: 0.14
Nodes (24): Ei(), Aa(), Bi(), ca(), da(), fa(), Gr(), ki() (+16 more)

### Community 66 - "selectOption"
Cohesion: 0.12
Nodes (39): addBadgesForSelectedOptions(), addSingleBadge(), addSingleSelectionDisplay(), closeDropdown(), constructor(), createBadgeElement(), createOptionElement(), createRemoveButton() (+31 more)

### Community 67 - "fo"
Cohesion: 0.11
Nodes (25): alpha(), es(), et(), fo(), go(), greyscale(), ho(), hslString() (+17 more)

### Community 68 - "CreateCashierOrder"
Cohesion: 0.13
Nodes (4): CreateCashierOrder, BackedEnum, UnitEnum, Width

### Community 69 - "ir"
Cohesion: 0.13
Nodes (34): Ft(), ir(), ce(), de(), Dt(), ee(), Et(), fe() (+26 more)

### Community 70 - "RegisterRestaurant.php"
Cohesion: 0.10
Nodes (7): RegisterRestaurant, RestaurantProvisioner, SubscriptionPlanSync, ReservedSlugs, Illuminate\Support\Facades\Auth, Illuminate\Validation\Rule, Illuminate\Validation\Rules\Password

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

### Community 75 - "next"
Cohesion: 0.06
Nodes (44): addActive(), addChanges(), Ar(), as(), be(), bidiSpans(), checkHover(), chunkEnd() (+36 more)

### Community 76 - "eq"
Cohesion: 0.06
Nodes (48): addNode(), ao(), append(), bt(), Cc(), Cr(), cy(), dd() (+40 more)

### Community 77 - "Illuminate\Foundation\Http\FormRequest"
Cohesion: 0.08
Nodes (8): AddCartItemRequest, CheckoutRequest, ClaimTableRequest, JoinTableRequest, StorePaymentProofRequest, StoreRestaurantReviewRequest, UpdateCartItemRequest, Illuminate\Foundation\Http\FormRequest

### Community 78 - "constructor"
Cohesion: 0.22
Nodes (10): apply(), bo(), chartOptionScopes(), constructor(), getDevicePixelRatio(), getMeta(), Le(), so() (+2 more)

### Community 79 - "file-upload.js"
Cohesion: 0.05
Nodes (53): hc(), Bp(), c(), ca(), clickPercent(), constructor(), Cp(), da() (+45 more)

### Community 80 - "Pe"
Cohesion: 0.12
Nodes (32): cd(), dd(), dt(), Ft(), gl(), _i(), Ie(), it() (+24 more)

### Community 81 - "addElementByRule"
Cohesion: 0.12
Nodes (29): addAll(), addDOM(), addElement(), addElementByRule(), addTextNode(), addToSet(), allowsMarkType(), closeExtra() (+21 more)

### Community 82 - "E"
Cohesion: 0.08
Nodes (31): aa(), ad(), bd(), br(), cd(), Di(), E(), _getUniformDataChanges() (+23 more)

### Community 84 - "FonnteErrorMessage"
Cohesion: 0.11
Nodes (8): FonnteClient, FonnteErrorMessage, Throwable, Illuminate\Http\Client\ConnectionException, Illuminate\Http\Client\Response, PHPUnit\Framework\Attributes\DataProvider, RuntimeException, FonnteErrorMessageTest

### Community 85 - "be"
Cohesion: 0.08
Nodes (27): aa(), ai(), ba(), be(), co(), color(), darken(), desaturate() (+19 more)

### Community 86 - "g$"
Cohesion: 0.06
Nodes (50): acceptToken(), aO(), charCategorizer(), d0(), De(), Dg(), E$(), eh() (+42 more)

### Community 87 - "fn"
Cohesion: 0.16
Nodes (20): Ck(), De(), fn(), Gh(), ip(), Ja(), Jh(), Ji() (+12 more)

### Community 88 - "_notify"
Cohesion: 0.20
Nodes (14): active(), _animateOptions(), cancel(), _createAnimations(), _createDescriptors(), _descriptors(), _notify(), _notifyStateChanges() (+6 more)

### Community 89 - "AdminPanelProvider.php"
Cohesion: 0.16
Nodes (19): ApplyPlatformBrandTheme, BezhanSalleh\FilamentShield\FilamentShieldPlugin, Bityukov\CommandCenter\Filament\CommandCenterPlugin, Filament\Http\Middleware\Authenticate, Filament\Http\Middleware\AuthenticateSession, Filament\Http\Middleware\DisableBladeIconComponents, Filament\Http\Middleware\DispatchServingFilamentEvent, Filament\Navigation\NavigationGroup (+11 more)

### Community 90 - "_generate"
Cohesion: 0.11
Nodes (25): af(), Fa(), _generate(), getAllParsedValues(), getDataTimestamps(), _getLabelBounds(), getLabelTimestamps(), getMatchingVisibleMetas() (+17 more)

### Community 91 - "devDependencies"
Cohesion: 0.11
Nodes (18): axios, concurrently, laravel-vite-plugin, devDependencies, axios, concurrently, laravel-vite-plugin, tailwindcss (+10 more)

### Community 92 - "filament/app.js"
Cohesion: 0.13
Nodes (8): B(), close(), G(), init(), P(), setUpResizeObserver(), x(), Y()

### Community 93 - "cc"
Cohesion: 0.38
Nodes (7): attrs(), cc(), JQ(), m$(), Ow(), rc(), read()

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
Nodes (20): canDelete(), canDeleteAny(), canForceDelete(), canRestore(), Action, trashPageAction(), FacilityResource, LandingTemplateResource (+12 more)

### Community 99 - "color-picker.js"
Cohesion: 0.14
Nodes (3): style(), update(), [x]()

### Community 100 - "js/app.js"
Cohesion: 0.22
Nodes (6): applyTheme(), bindThemeToggle(), initReveal(), initTheme(), syncThemeToggleIcons(), toggleTheme()

### Community 102 - "CmsMedia"
Cohesion: 0.03
Nodes (12): paymentMixSummary(), WelcomeBannerWidget, Payment, PaymentProofService, CashierMenuCatalog, CashierOrderPreview, CheckoutTotals, CmsMedia (+4 more)

### Community 103 - "composer.json"
Cohesion: 0.14
Nodes (13): autoload-dev, psr-4, description, keywords, license, minimum-stability, name, prefer-stable (+5 more)

### Community 104 - "getDatasetMeta"
Cohesion: 0.12
Nodes (23): afterDatasetsUpdate(), buildOrUpdateControllers(), _destroyDatasetMeta(), getController(), getDatasetMeta(), getElement(), _handleEvent(), hide() (+15 more)

### Community 106 - ".panel"
Cohesion: 0.20
Nodes (7): FilamentProfilePlugin, AdminPanelProvider, FounderPanelProvider, Filament\Panel, Filament\PanelProvider, Ipatco\FilamentProfile\FilamentProfilePlugin, Ipatco\FilamentProfile\Widgets\AccountWidget

### Community 107 - "date-time-picker.js"
Cohesion: 0.26
Nodes (8): d(), e(), i(), m(), r(), s(), t(), rr()

### Community 108 - "🚀 3. Langkah-Langkah Menambahkan Template Baru (*Workflow*)"
Cohesion: 0.18
Nodes (10): 📌 1. Prinsip Utama & Aturan Wajib (Golden Rules), 🏗️ 2. Arsitektur 10 Core Section CMS (1:1 Mapping), 🚀 3. Langkah-Langkah Menambahkan Template Baru (*Workflow*), 📊 4. Variabel yang Disediakan oleh `LandingPageDataService`, Langkah 1: Buat Direktori Template, Langkah 2: Buat File Utama `show.blade.php`, Langkah 3: Tambahkan Dukungan Varian di `dish-card.blade.php`, Langkah 4: Registrasi Template ke Database (+2 more)

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

### Community 116 - "create"
Cohesion: 0.04
Nodes (76): ar(), beforeDatasetDraw(), cg(), Cl(), clone(), create(), Dl(), dtFormatter() (+68 more)

### Community 118 - "Landasan Produksi — Tahap 1 (Walk-In Operasional)"
Cohesion: 0.05
Nodes (37): 10. Aturan yang tidak boleh dilanggar, 11. Matriks skenario lapangan, 12. Yang sengaja tidak didukung (bukan gap, non-goal), 13. Tahap 2 (bukan sekarang), 14. Urutan bangun (agar cepat produksi), 1. Keputusan terkunci, 2. Modul tahap 1 vs ditunda, 3. Model domain (+29 more)

### Community 119 - "Guest"
Cohesion: 0.05
Nodes (34): Bentuk objek, CartItemResource, DELETE `/guest/cart/items/{cartItem}`, GET `/guest/cart`, GET `/guest/menu`, GET `/guest/orders`, GET `/guest/orders/{public_id}`, GET `/guest/review` (+26 more)

### Community 120 - "N"
Cohesion: 0.11
Nodes (22): Ao(), bl(), ci(), Fs(), getBasePosition(), getBaseValue(), getDistanceFromCenterForValue(), getIndexAngle() (+14 more)

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

### Community 137 - "GeoDistance"
Cohesion: 0.20
Nodes (4): GeoDistance, PHPUnit\Framework\TestCase, ExampleTest, GeoDistanceTest

### Community 138 - "MenuItemResource"
Cohesion: 0.12
Nodes (5): MenuItemResource, CreateMenuItem, EditMenuItem, ListMenuItems, TrashMenuItems

### Community 139 - "fd"
Cohesion: 0.13
Nodes (22): addToSet(), bd(), Bh(), childString(), clearDelayedAndroidKey(), delayAndroidKey(), fd(), flush() (+14 more)

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

### Community 176 - "o"
Cohesion: 0.06
Nodes (76): beforeLayout(), bi(), _d(), g(), gu(), inRange(), jm(), xe() (+68 more)

### Community 185 - "Ae"
Cohesion: 0.67
Nodes (3): Ae(), Bt(), ne()

### Community 193 - "restaurant-menu-catalog.blade.php"
Cohesion: 0.29
Nodes (6): landing.templates.foodie.sections.footer, landing.templates.foodie.sections.header, landing.templates.glassmorphism.sections.footer, landing.templates.glassmorphism.sections.header, partials.customer.landing-footer, partials.customer.landing-header

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

### Community 313 - "parse"
Cohesion: 0.06
Nodes (49): add(), afterAutoSkip(), Bt(), buildLookupTable(), cl(), Cn(), determineDataLimits(), diff() (+41 more)

### Community 317 - "_update"
Cohesion: 0.05
Nodes (72): addElements(), afterBuildTicks(), afterCalculateLabelRotation(), afterDataLimits(), afterFit(), afterSetDimensions(), afterTickToLabelConversion(), afterUpdate() (+64 more)

### Community 318 - "ir"
Cohesion: 0.06
Nodes (40): applyStack(), beforeDatasetsDraw(), dataset(), Do(), first(), generateLabels(), getDataVisibility(), _getSortedDatasetMetas() (+32 more)

### Community 320 - "get"
Cohesion: 0.10
Nodes (28): add(), bo(), bs(), _cachedScopes(), Ch(), Ct(), datasetElementScopeKeys(), describe() (+20 more)

### Community 321 - "addEventListener"
Cohesion: 0.22
Nodes (11): Nn(), addEventListener(), bindResponsiveEvents(), ga(), Hn(), isAttached(), Ja(), li() (+3 more)

### Community 322 - "glassmorphism/show.blade.php"
Cohesion: 0.33
Nodes (5): landing.templates.glassmorphism.sections., landing.templates.glassmorphism.sections.hero, landing.sections., landing.templates.glassmorphism.sections.footer, landing.templates.glassmorphism.sections.header

### Community 324 - "S"
Cohesion: 0.07
Nodes (42): A(), As(), _computeLabelSizes(), cr(), createResolver(), da(), describe(), ei() (+34 more)

### Community 325 - "l"
Cohesion: 0.08
Nodes (44): at(), Ba(), Bf(), cf(), determineDataLimits(), l(), ef(), formats() (+36 more)

### Community 327 - "_resolveAnimations"
Cohesion: 0.10
Nodes (24): active(), _animateOptions(), _cachedScopes(), _createAnimations(), datasetAnimationScopeKeys(), datasetElementScopeKeys(), get(), getMaxOverflow() (+16 more)

### Community 328 - "LandingTemplate"
Cohesion: 0.11
Nodes (9): TemplateRadioPicker, ManageLandingLayout, BackedEnum, Closure, UnitEnum, LandingTemplate, LogOptions, Filament\Forms\Components\Field (+1 more)

## Knowledge Gaps
- **315 isolated node(s):** `$schema`, `name`, `type`, `description`, `laravel` (+310 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **34 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `_s()` connect `components/chart.js` to `rich-editor.js`, `i`?**
  _High betweenness centrality (0.030) - this node is a cross-community bridge._
- **Why does `update()` connect `constructor` to `stat/chart.js`, `code-editor.js`, `rich-editor.js`, `r`, `i`, `fd`, `get`, `slice`, `markdown-editor.js`, `n`, `facet`, `.slice`, `echo.js`, `constructor`, `resolve`, `W`, `te`, `y`, `constructor`, `ce`, `t`, `dx`, `next`, `g$`?**
  _High betweenness centrality (0.029) - this node is a cross-community bridge._
- **Why does `Wi()` connect `rich-editor.js` to `code-editor.js`, `components/select.js`, `columns/select.js`, `constructor`?**
  _High betweenness centrality (0.027) - this node is a cross-community bridge._
- **Are the 18 inferred relationships involving `constructor()` (e.g. with `a()` and `h()`) actually correct?**
  _`constructor()` has 18 INFERRED edges - model-reasoned connections that need verification._
- **Are the 26 inferred relationships involving `update()` (e.g. with `Pr()` and `a()`) actually correct?**
  _`update()` has 26 INFERRED edges - model-reasoned connections that need verification._
- **What connects `$schema`, `name`, `type` to the rest of the system?**
  _315 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `stat/chart.js` be split into smaller, more focused modules?**
  _Cohesion score 0.026094938368738527 - nodes in this community are weakly interconnected._