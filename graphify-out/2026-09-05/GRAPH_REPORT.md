# Graph Report - WALK-IN-RESTO  (2026-09-05)

## Corpus Check
- 634 files · ~304,813 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 8570 nodes · 26393 edges · 351 communities (310 shown, 41 thin omitted)
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
- Illuminate\Database\Eloquent\Relations\BelongsTo
- y
- TestCase
- constructor
- Illuminate\Http\Request
- GuestContext
- r
- find
- User
- Restaurant
- _update
- Visit
- SubscriptionAccess
- Order
- get
- BackedEnum
- slice
- Oc
- RestaurantCategory
- AppServiceProvider.php
- parse
- support.js
- n
- nodesBetween
- create
- columns/select.js
- .slice
- eq
- echo.js
- resolve
- OrderResource
- fromObject
- Filament\Resources\Pages\ListRecords
- Filament\Schemas\Schema
- W
- e
- create
- draw
- Ye
- Illuminate\Foundation\Http\FormRequest
- Filament\Resources\Pages\ManageRecords
- notifications.js
- pd
- PlatformSetting
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
- Filament\Tables\Table
- t
- Si
- RendersAnalyticsDashboard.php
- getContext
- selectOption
- sliceDoc
- CreateCashierOrder
- ir
- ce
- slider.js
- measure
- closeDropdown
- fn
- of
- InvoicePaymentTest
- ExportFileResource
- getDatasetMeta
- file-upload.js
- updateElements
- RestaurantDirectory
- FonnteErrorMessage
- k
- AdminPanelProvider.php
- _r
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
- ot
- composer.json
- parse
- order-today-stats-widget.blade.php
- FilamentProfilePlugin.php
- date-time-picker.js
- 🚀 3. Langkah-Langkah Menambahkan Template Baru (*Workflow*)
- DiningTable
- En
- Login
- Illuminate\Database\Schema\Blueprint
- _update
- actions/actions.js
- getProps
- schemas.js
- Landasan Produksi — Tahap 1 (Walk-In Operasional)
- Guest
- E
- 5. Keuntungan dari fitur — bahasa owner, bukan bahasa sistem
- P
- require-dev
- isHorizontal
- 6. Katalog fitur
- filament-shield.php
- config
- 6. Katalog fitur
- EditProfile
- register-restaurant.blade.php
- add-to-cart-modal.blade.php
- KdsStationResource
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
- Ka
- closeSimpleModeModal
- Illuminate\Database\Migrations\Migration
- OrderReceiptPrintTest
- MenuCategoryResource
- markdown-editor.js
- Illuminate\Support\Facades\Schema
- ExportFile
- kpi.blade.php
- createResolver
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
- AuthGlass
- rules/graphify.md
- workflows/graphify.md
- S
- MenuItem
- TenantContext
- configure
- constructor
- glassmorphism/show.blade.php
- CmsMedia
- CashierFilamentActionsTest
- jn
- fn
- SubscriptionInvoice
- glassmorphism-background.blade.php
- Filament/Pages/Dashboard.php
- ReservedSlugs
- be
- constructor
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
- `createGuestRestaurant()` --calls--> `MenuCategory`  [EXTRACTED]
  tests/Concerns/CreatesGuestRestaurant.php → app/Models/MenuCategory.php
- `createGuestRestaurant()` --calls--> `MenuItem`  [EXTRACTED]
  tests/Concerns/CreatesGuestRestaurant.php → app/Models/MenuItem.php
- `extraMenuItem()` --calls--> `MenuItem`  [EXTRACTED]
  tests/Concerns/CreatesGuestRestaurant.php → app/Models/MenuItem.php

## Import Cycles
- None detected.

## Communities (351 total, 41 thin omitted)

### Community 0 - "stat/chart.js"
Cohesion: 0.02
Nodes (76): Ro(), addControllers(), addPlugins(), addScales(), Ao(), bl(), bs(), ci() (+68 more)

### Community 1 - "components/chart.js"
Cohesion: 0.01
Nodes (156): abutsStart(), addControllers(), addPlugins(), addScales(), alpha(), apply(), Bc(), bd() (+148 more)

### Community 2 - "code-editor.js"
Cohesion: 0.01
Nodes (136): Ac(), addCompletion(), addCompletions(), addNamespace(), addNamespaceObject(), Ag(), attrs(), AX() (+128 more)

### Community 3 - "rich-editor.js"
Cohesion: 0.01
Nodes (175): Rd(), $a(), aa(), addExtensions(), addHackNode(), addNodeMark(), addTextblockHacks(), applyAspectRatio() (+167 more)

### Community 4 - "Illuminate\Database\Eloquent\Relations\BelongsTo"
Cohesion: 0.02
Nodes (49): CmsBanner, LogOptions, CmsFaq, LogOptions, CmsGalleryImage, CmsProfile, LogOptions, restaurant() (+41 more)

### Community 5 - "y"
Cohesion: 0.14
Nodes (81): at(), b(), Be(), $c(), X(), ca(), me(), Cr() (+73 more)

### Community 6 - "TestCase"
Cohesion: 0.03
Nodes (58): CashierOrderService, ImageOptimizer, BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles, Bityukov\CommandCenter\Filament\Pages\Commands, Bityukov\CommandCenter\Filament\Pages\History, DatabaseSeeder, RolePermissionSeeder, Filament\Facades\Filament (+50 more)

### Community 7 - "constructor"
Cohesion: 0.02
Nodes (171): accept(), active(), add(), addChunk(), addEventListener(), addInfoPane(), addInner(), addToSet() (+163 more)

### Community 8 - "Illuminate\Http\Request"
Cohesion: 0.05
Nodes (30): ApplyPlatformBrandTheme, ApplyRestaurantPanelTheme, EnsureApiGuestVisit, EnsureGuestVisit, EnsureRestaurantOperations, EnsureTenantSubscription, IdentifyApiGuestDevice, IdentifyGuestDevice (+22 more)

### Community 9 - "GuestContext"
Cohesion: 0.04
Nodes (37): CartController, CheckoutController, MenuController, OrderController, ReviewController, SessionController, TableController, VisitController (+29 more)

### Community 10 - "r"
Cohesion: 0.04
Nodes (153): xQ(), Ad(), addNodeView(), addProseMirrorPlugins(), af(), au(), ay(), B0() (+145 more)

### Community 11 - "find"
Cohesion: 0.06
Nodes (49): activateHover(), baseDirAt(), bd(), Bh(), bidiIn(), bidiSpans(), bidiSpansAt(), bP() (+41 more)

### Community 12 - "User"
Cohesion: 0.03
Nodes (28): AnalyticsKpiWidget, Role, User, RolePolicy, UserPolicy, PermissionCheck, Filament\Models\Contracts\FilamentUser, Filament\Models\Contracts\HasTenants (+20 more)

### Community 13 - "Restaurant"
Cohesion: 0.03
Nodes (35): ExpireStaleOperationsCommand, GenerateUpcomingInvoicesCommand, ProcessSubscriptionLifecycleCommand, analyticsDateFrom(), analyticsDateTo(), analyticsDayCount(), analyticsRangeLabel(), analyticsSnapshot() (+27 more)

### Community 14 - "_update"
Cohesion: 0.04
Nodes (87): addElements(), addEventListener(), afterBuildTicks(), afterCalculateLabelRotation(), afterDataLimits(), afterFit(), afterSetDimensions(), afterTickToLabelConversion() (+79 more)

### Community 15 - "Visit"
Cohesion: 0.04
Nodes (18): Visit, VisitDevice, GuestCheckoutService, MenuModifierService, OrderPaymentService, StaleOperationsService, TableOpsService, TableScanService (+10 more)

### Community 16 - "SubscriptionAccess"
Cohesion: 0.07
Nodes (12): canCreate(), canDelete(), canDeleteAny(), canEdit(), canViewAny(), ManageCmsProfile, BackedEnum, UnitEnum (+4 more)

### Community 17 - "Order"
Cohesion: 0.04
Nodes (15): OrderReceiptDownloadController, OrderReceiptPrintController, SendWhatsappReceiptJob, Order, OrderItem, OrderReceipt, WhatsappMessage, KdsItemService (+7 more)

### Community 18 - "get"
Cohesion: 0.04
Nodes (95): addBlock(), addBlockWidget(), addBreak(), addComposition(), addDelimiter(), addGaps(), addInlineWidget(), addLine() (+87 more)

### Community 19 - "BackedEnum"
Cohesion: 0.12
Nodes (48): ViewSubscriptionInvoice, BackedEnum, Filament\Actions\Action, Filament\Actions\DeleteAction, Filament\Actions\EditAction, Filament\Actions\ViewAction, Filament\Forms\Components\CheckboxList, Filament\Forms\Components\ColorPicker (+40 more)

### Community 20 - "slice"
Cohesion: 0.03
Nodes (99): addChild(), addLeafElement(), addNode(), advance(), ATXHeading(), blank(), break(), BulletList() (+91 more)

### Community 21 - "Oc"
Cohesion: 0.19
Nodes (14): [g](), getExtension(), _getTestState(), co(), Ee(), Ft(), Kt(), lx() (+6 more)

### Community 22 - "RestaurantCategory"
Cohesion: 0.05
Nodes (9): RestaurantCategory, FacilitySeeder, PlatformSettingSeeder, RestaurantCategorySeeder, SubscriptionPlanSeeder, Illuminate\Database\Seeder, RestaurantDirectoryTest, RestaurantRegistrationStepperTest (+1 more)

### Community 23 - "AppServiceProvider.php"
Cohesion: 0.05
Nodes (29): KitchenDisplay, BackedEnum, UnitEnum, Width, SoftDeleteTrashPage, TrashUsers, AppServiceProvider, Filament\Actions\DeleteBulkAction (+21 more)

### Community 24 - "parse"
Cohesion: 0.08
Nodes (43): addAll(), addDOM(), addElement(), addElementByRule(), addTextNode(), addToSet(), allowsMarkType(), append() (+35 more)

### Community 25 - "support.js"
Cohesion: 0.05
Nodes (62): acquireScrollLock(), ae(), ai(), Ao(), as(), close(), closeQuietly(), commit() (+54 more)

### Community 26 - "n"
Cohesion: 0.10
Nodes (73): _a(), Ae(), ar(), as(), bc(), ee(), ue(), u() (+65 more)

### Community 27 - "nodesBetween"
Cohesion: 0.04
Nodes (81): _0(), addGlobalAttributes(), addInputRules(), addMark(), addPasteRules(), addStoredMark(), Ah(), Ax() (+73 more)

### Community 28 - "create"
Cohesion: 0.10
Nodes (28): addChanges(), addSelection(), Ah(), applyTransaction(), asSingle(), composeDesc(), create(), dl() (+20 more)

### Community 29 - "columns/select.js"
Cohesion: 0.06
Nodes (57): A(), An(), applyDisabledState(), b(), Bt(), Ce(), D(), disable() (+49 more)

### Community 30 - ".slice"
Cohesion: 0.05
Nodes (71): accepts(), addInner(), addMaps(), addStep(), addTransform(), ak(), appendMap(), appendMapping() (+63 more)

### Community 31 - "eq"
Cohesion: 0.07
Nodes (44): addNode(), ao(), Cc(), dd(), deleteNode(), deleteRange(), destroyBetween(), destroyRest() (+36 more)

### Community 32 - "echo.js"
Cohesion: 0.05
Nodes (49): a(), ar(), b(), Be(), Ce(), cr(), d(), De() (+41 more)

### Community 33 - "resolve"
Cohesion: 0.05
Nodes (168): Ac(), addCommands(), addKeyboardShortcuts(), after(), ag(), al(), allowedMarks(), allowsMarks() (+160 more)

### Community 34 - "OrderResource"
Cohesion: 0.08
Nodes (9): OrderResource, ListOrders, ViewOrder, OrderTodayStatsWidget, Carbon\Carbon, Filament\Infolists\Components\ImageEntry, Filament\Infolists\Components\RepeatableEntry, Filament\Tables\Filters\Filter (+1 more)

### Community 35 - "fromObject"
Cohesion: 0.03
Nodes (111): El(), ac(), ae(), after(), Al(), Am(), before(), bl() (+103 more)

### Community 36 - "Filament\Resources\Pages\ListRecords"
Cohesion: 0.06
Nodes (14): ListFacilities, ListLandingTemplates, ListSubscriptionInvoices, SubscriptionInvoiceResource, EditSubscriptionPlan, ListSubscriptionPlans, SubscriptionPlanResource, ListTenants (+6 more)

### Community 37 - "Filament\Schemas\Schema"
Cohesion: 0.04
Nodes (19): ManageBillingAccount, BackedEnum, UnitEnum, ManageHomeLanding, BackedEnum, UnitEnum, ManagePlatformPages, BackedEnum (+11 more)

### Community 38 - "W"
Cohesion: 0.04
Nodes (87): AQ(), au(), b1(), child(), childAfter(), childBefore(), cursor(), cursorAt() (+79 more)

### Community 39 - "e"
Cohesion: 0.13
Nodes (37): _a(), e(), Bi(), br(), Bt(), ca(), ct(), Dn() (+29 more)

### Community 40 - "create"
Cohesion: 0.04
Nodes (67): Cl(), clone(), create(), Ct(), Dl(), dtFormatter(), Ea(), Ec() (+59 more)

### Community 41 - "draw"
Cohesion: 0.04
Nodes (106): acquireContext(), adjustHitBoxes(), afterDraw(), Ao(), aspectRatio(), bh(), buildTicks(), calculateLabelRotation() (+98 more)

### Community 42 - "Ye"
Cohesion: 0.10
Nodes (42): at(), bk(), c(), bp(), bt(), Cr(), Dk(), dp() (+34 more)

### Community 43 - "Illuminate\Foundation\Http\FormRequest"
Cohesion: 0.08
Nodes (8): AddCartItemRequest, CheckoutRequest, ClaimTableRequest, JoinTableRequest, StorePaymentProofRequest, StoreRestaurantReviewRequest, UpdateCartItemRequest, Illuminate\Foundation\Http\FormRequest

### Community 44 - "Filament\Resources\Pages\ManageRecords"
Cohesion: 0.05
Nodes (18): CmsBannerResource, ManageCmsBanners, TrashCmsBanners, CmsFaqResource, ManageCmsFaqs, TrashCmsFaqs, CmsGalleryImageResource, ManageCmsGalleryImages (+10 more)

### Community 45 - "notifications.js"
Cohesion: 0.06
Nodes (31): actions(), button(), c(), close(), configureAnimations(), configureTransitions(), constructor(), danger() (+23 more)

### Community 46 - "pd"
Cohesion: 0.10
Nodes (35): af(), al(), An(), bo(), co(), Dn(), ef(), En() (+27 more)

### Community 47 - "PlatformSetting"
Cohesion: 0.04
Nodes (11): PlatformPageController, RestaurantLandingController, self, PlatformSetting, ReceiptLogo, RestaurantTheme, Illuminate\View\View, AuthGlassTest (+3 more)

### Community 48 - "te"
Cohesion: 0.05
Nodes (13): Bn(), br(), Id(), ji(), on(), qd(), qi(), Ri() (+5 more)

### Community 49 - "Cn"
Cohesion: 0.11
Nodes (52): B(), Cn(), b(), Be(), Ce(), De(), dn(), _e() (+44 more)

### Community 51 - "components/select.js"
Cohesion: 0.09
Nodes (27): addBadgesForSelectedOptions(), addSingleBadge(), addSingleSelectionDisplay(), applyDisabledState(), createBadgeElement(), createOptionElement(), createRemoveButton(), disable() (+19 more)

### Community 53 - "tables.js"
Cohesion: 0.13
Nodes (44): A(), ae(), B(), be(), C(), ce(), E(), ee() (+36 more)

### Community 54 - "reduce"
Cohesion: 0.07
Nodes (50): addActions(), advanceFully(), advanceStack(), allActions(), c0(), canShift(), close(), deadEnd() (+42 more)

### Community 55 - "r"
Cohesion: 0.15
Nodes (42): ar(), c(), f(), d(), di(), g(), Hi(), I() (+34 more)

### Community 56 - "o"
Cohesion: 0.04
Nodes (143): ag(), ar(), au(), Ba(), beforeDatasetDraw(), beforeLayout(), bi(), Bn() (+135 more)

### Community 57 - "SubscriptionStatus"
Cohesion: 0.08
Nodes (7): BackedEnum, UnitEnum, SubscriptionStatus, BlockGraceMutations, SubscriptionPlan, SubscriptionWriteGuard, Livewire\ComponentHook

### Community 58 - "Xt"
Cohesion: 0.12
Nodes (44): ae(), At(), bi(), bn(), ci(), cn(), ct(), de() (+36 more)

### Community 60 - "filament-right-click.js"
Cohesion: 0.11
Nodes (42): a(), ae(), b(), be(), C(), ce(), D(), de() (+34 more)

### Community 61 - "Filament\Tables\Table"
Cohesion: 0.06
Nodes (12): ActivityResource, ListActivities, ViewActivity, ActivityInfolist, ActivitiesTable, TableRightClick, PendingPaymentsWidget, Activity (+4 more)

### Community 62 - "t"
Cohesion: 0.04
Nodes (75): a$(), activeForPoint(), addActive(), Ar(), as(), atLastNode(), boundChange(), chunkEnd() (+67 more)

### Community 63 - "Si"
Cohesion: 0.13
Nodes (41): ae(), At(), bi(), bn(), ci(), cn(), ct(), de() (+33 more)

### Community 64 - "RendersAnalyticsDashboard.php"
Cohesion: 0.06
Nodes (23): AnalyticsPeriodSummaryWidget, AnalyticsRevenueBarWidget, AnalyticsSidebarWidget, AnalyticsTopMenuWidget, generateChartDataChecksum(), getCachedChartData(), getChartData(), mountRefreshesAnalyticsChart() (+15 more)

### Community 65 - "getContext"
Cohesion: 0.08
Nodes (34): acquireContext(), Ae(), applyStack(), beforeDatasetsDraw(), beforeDraw(), bi(), Bn(), Ca() (+26 more)

### Community 66 - "selectOption"
Cohesion: 0.12
Nodes (39): addBadgesForSelectedOptions(), addSingleBadge(), addSingleSelectionDisplay(), closeDropdown(), constructor(), createBadgeElement(), createOptionElement(), createRemoveButton() (+31 more)

### Community 67 - "sliceDoc"
Cohesion: 0.15
Nodes (19): aO(), charCategorizer(), Fc(), flatten(), getCursor(), getDeco(), gT(), highlight() (+11 more)

### Community 68 - "CreateCashierOrder"
Cohesion: 0.07
Nodes (9): TemplateRadioPicker, CreateCashierOrder, BackedEnum, UnitEnum, Width, CashierMenuCatalog, Filament\Forms\Components\Field, Illuminate\Database\Eloquent\Collection (+1 more)

### Community 69 - "ir"
Cohesion: 0.13
Nodes (33): De(), Ft(), ir(), ce(), de(), Dt(), ee(), Et() (+25 more)

### Community 70 - "ce"
Cohesion: 0.14
Nodes (25): Ac(), bl(), ce(), cl(), Dc(), dl(), Do(), el() (+17 more)

### Community 71 - "slider.js"
Cohesion: 0.12
Nodes (31): ar(), Be(), Ce(), _e(), Ee(), er(), Fe(), G() (+23 more)

### Community 72 - "measure"
Cohesion: 0.11
Nodes (24): applyChanges(), balanced(), Cf(), docViewUpdate(), ensureLineGaps(), gapSize(), getScrollOffset(), getViewport() (+16 more)

### Community 73 - "closeDropdown"
Cohesion: 0.20
Nodes (25): closeDropdown(), constructor(), deferPositionDropdown(), destroy(), filterOptions(), focusNextOption(), focusPreviousOption(), getVisibleOptions() (+17 more)

### Community 74 - "fn"
Cohesion: 0.14
Nodes (31): aa(), ba(), cr(), da(), de(), dt(), ei(), Fi() (+23 more)

### Community 75 - "of"
Cohesion: 0.04
Nodes (132): aa(), addElement(), balance(), baseIndent(), baseIndentFor(), baseTheme(), be(), Bg() (+124 more)

### Community 77 - "ExportFileResource"
Cohesion: 0.12
Nodes (6): GenerateReport, BackedEnum, UnitEnum, ExportFileResource, ListExportFiles, TrashExportFiles

### Community 78 - "getDatasetMeta"
Cohesion: 0.11
Nodes (23): afterDatasetsUpdate(), dataset(), fi(), getDatasetMeta(), _handleEvent(), hide(), il(), index() (+15 more)

### Community 79 - "file-upload.js"
Cohesion: 0.05
Nodes (53): hc(), Bp(), c(), ca(), clickPercent(), constructor(), Cp(), da() (+45 more)

### Community 81 - "updateElements"
Cohesion: 0.05
Nodes (62): aa(), addBox(), afterAutoSkip(), buildLookupTable(), _calculateBarIndexPixels(), _calculateBarValuePixels(), calculateCircumference(), cd() (+54 more)

### Community 84 - "FonnteErrorMessage"
Cohesion: 0.11
Nodes (8): FonnteClient, FonnteErrorMessage, Throwable, Illuminate\Http\Client\ConnectionException, Illuminate\Http\Client\Response, PHPUnit\Framework\Attributes\DataProvider, RuntimeException, FonnteErrorMessageTest

### Community 85 - "k"
Cohesion: 0.18
Nodes (17): A(), b(), Bt(), D(), ht(), $i(), je(), k() (+9 more)

### Community 89 - "AdminPanelProvider.php"
Cohesion: 0.15
Nodes (20): AdminPanelProvider, BezhanSalleh\FilamentShield\FilamentShieldPlugin, Bityukov\CommandCenter\Filament\CommandCenterPlugin, Filament\Http\Middleware\Authenticate, Filament\Http\Middleware\AuthenticateSession, Filament\Http\Middleware\DisableBladeIconComponents, Filament\Http\Middleware\DispatchServingFilamentEvent, Filament\Navigation\NavigationGroup (+12 more)

### Community 90 - "_r"
Cohesion: 0.15
Nodes (16): ar(), first(), gn(), ji(), ms(), path(), pathSegment(), Pn() (+8 more)

### Community 91 - "devDependencies"
Cohesion: 0.11
Nodes (18): axios, concurrently, laravel-vite-plugin, devDependencies, axios, concurrently, laravel-vite-plugin, tailwindcss (+10 more)

### Community 92 - "filament/app.js"
Cohesion: 0.13
Nodes (8): B(), close(), G(), init(), P(), setUpResizeObserver(), x(), Y()

### Community 93 - "g$"
Cohesion: 0.07
Nodes (43): acceptToken(), allows(), between(), bu(), d0(), De(), Dg(), E$() (+35 more)

### Community 94 - "fn"
Cohesion: 0.24
Nodes (17): An(), Ce(), ei(), fn(), Ft(), Ie(), Le(), ni() (+9 more)

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
Nodes (30): canForceDelete(), canRestore(), Action, trashPageAction(), FacilityResource, CreateFacility, EditFacility, LandingTemplateResource (+22 more)

### Community 99 - "color-picker.js"
Cohesion: 0.14
Nodes (3): style(), update(), [x]()

### Community 100 - "js/app.js"
Cohesion: 0.22
Nodes (6): applyTheme(), bindThemeToggle(), initReveal(), initTheme(), syncThemeToggleIcons(), toggleTheme()

### Community 101 - "ot"
Cohesion: 0.13
Nodes (21): ad(), cd(), dd(), gl(), Ie(), jl(), ld(), lr() (+13 more)

### Community 103 - "composer.json"
Cohesion: 0.14
Nodes (13): autoload-dev, psr-4, description, keywords, license, minimum-stability, name, prefer-stable (+5 more)

### Community 104 - "parse"
Cohesion: 0.07
Nodes (42): afterAutoSkip(), buildLookupTable(), buildOrUpdateElements(), cl(), Cn(), determineDataLimits(), el(), En() (+34 more)

### Community 106 - "FilamentProfilePlugin.php"
Cohesion: 0.33
Nodes (3): FilamentProfilePlugin, Ipatco\FilamentProfile\FilamentProfilePlugin, Ipatco\FilamentProfile\Widgets\AccountWidget

### Community 107 - "date-time-picker.js"
Cohesion: 0.29
Nodes (7): d(), e(), i(), m(), r(), s(), t()

### Community 108 - "🚀 3. Langkah-Langkah Menambahkan Template Baru (*Workflow*)"
Cohesion: 0.12
Nodes (15): 📌 1. Prinsip Utama & Aturan Wajib (Golden Rules), 🏗️ 2. Arsitektur 10 Core Section CMS (1:1 Mapping), 🚀 3. Langkah-Langkah Menambahkan Template Baru (*Workflow*), 📊 4. Variabel yang Disediakan oleh `LandingPageDataService`, 💎 5. Pelajaran Desain & Best Practices UI/UX (*Key Learnings*), A. Larangan Keras Menggunakan Emoji Unicode (Gunakan SVG Heroicons), B. Arsitektur Layering 3D & Background Tembus Pandang (*Stacking Context*), C. Resep Glassmorphism iOS yang Nyata (*True Frosted Glass*) (+7 more)

### Community 109 - "DiningTable"
Cohesion: 0.04
Nodes (17): getRecordRouteBindingEloquentQuery(), periodSummary(), bootScopedToRestaurant(), scopeForRestaurant(), scopeWithoutRestaurantScope(), DiningTable, RestaurantDirectory, TableFloorPlan (+9 more)

### Community 110 - "En"
Cohesion: 0.14
Nodes (17): apply(), At(), En(), fs(), go(), Hr(), T(), ir() (+9 more)

### Community 111 - "Login"
Cohesion: 0.25
Nodes (4): Login, Filament\Auth\Pages\Login, Filament\Schemas\Components\Component, Illuminate\Contracts\Support\Htmlable

### Community 113 - "_update"
Cohesion: 0.09
Nodes (33): themeClasses(), afterBuildTicks(), afterCalculateLabelRotation(), afterDataLimits(), afterFit(), afterSetDimensions(), afterTickToLabelConversion(), afterUpdate() (+25 more)

### Community 115 - "actions/actions.js"
Cohesion: 0.44
Nodes (8): closeModal(), generateModalId(), getActionNestingIndexFromModalId(), init(), openModal(), rememberPreviouslyFocusedElement(), restorePreviouslyFocusedElement(), syncActionModals()

### Community 116 - "getProps"
Cohesion: 0.12
Nodes (21): active(), ah(), _animateOptions(), average(), contains(), _createAnimations(), getCenterPoint(), getProps() (+13 more)

### Community 118 - "Landasan Produksi — Tahap 1 (Walk-In Operasional)"
Cohesion: 0.05
Nodes (37): 10. Aturan yang tidak boleh dilanggar, 11. Matriks skenario lapangan, 12. Yang sengaja tidak didukung (bukan gap, non-goal), 13. Tahap 2 (bukan sekarang), 14. Urutan bangun (agar cepat produksi), 1. Keputusan terkunci, 2. Modul tahap 1 vs ditunda, 3. Model domain (+29 more)

### Community 119 - "Guest"
Cohesion: 0.05
Nodes (34): Bentuk objek, CartItemResource, DELETE `/guest/cart/items/{cartItem}`, GET `/guest/cart`, GET `/guest/menu`, GET `/guest/orders`, GET `/guest/orders/{public_id}`, GET `/guest/review` (+26 more)

### Community 120 - "E"
Cohesion: 0.05
Nodes (60): $a(), add(), B(), bo(), bs(), ca(), _cachedScopes(), Ch() (+52 more)

### Community 121 - "5. Keuntungan dari fitur — bahasa owner, bukan bahasa sistem"
Cohesion: 0.06
Nodes (34): 1. Cerita yang mungkin terasa familiar, 2.10 Owner tidak punya angka yang bisa dipercaya, 2.11 Tamu tanpa HP, atau minta tambah pesanan, 2.1 Pesanan salah, kurang, atau telat sampai dapur, 2.2 Kasir jadi bottleneck, 2.3 Transfer QRIS yang “mirip-mirip”, 2.4 Tamu “pesan tunai dari luar resto”, 2.5 Dapur dan bar rebutan kertas, tidak tahu mana yang lama (+26 more)

### Community 122 - "P"
Cohesion: 0.09
Nodes (29): br(), buildOrUpdateControllers(), cancel(), _createDescriptors(), _descriptors(), _destroyDatasetMeta(), getController(), getElement() (+21 more)

### Community 123 - "require-dev"
Cohesion: 0.25
Nodes (8): require-dev, fakerphp/faker, laravel/pail, laravel/pint, laravel/sail, mockery/mockery, nunomaduro/collision, phpunit/phpunit

### Community 124 - "isHorizontal"
Cohesion: 0.08
Nodes (36): Bt(), buildTicks(), _calculatePadding(), _computeGridLineItems(), _computeLabelItems(), computeTickLimit(), fl(), fn() (+28 more)

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
Cohesion: 0.12
Nodes (5): EditProfile, ProfileInformationForm, Ipatco\FilamentProfile\Forms\ProfileInformationForm, Ipatco\FilamentProfile\Pages\EditProfile, ProfilePageTest

### Community 130 - "register-restaurant.blade.php"
Cohesion: 0.15
Nodes (12): applyColorPreset(, back, nextFromAccount, nextFromPlan, nextFromRestaurant, nextFromVisual, register, $set( (+4 more)

### Community 131 - "add-to-cart-modal.blade.php"
Cohesion: 0.29
Nodes (6): cancelPicking, confirmAdd, decrementPickingQty, incrementPickingQty, setVariant({{ $variant->id }}), toggleModifier({{ $modifier->id }})

### Community 132 - "KdsStationResource"
Cohesion: 0.24
Nodes (4): KdsStationResource, ManageKdsStations, Closure, TrashKdsStations

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
Cohesion: 0.06
Nodes (44): afterDatasetsUpdate(), An(), beforeDatasetsDraw(), bu(), dataset(), ef(), generateLabels(), getDatasetMeta() (+36 more)

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

### Community 153 - "Ka"
Cohesion: 0.11
Nodes (23): ad(), applyStack(), Do(), first(), ig(), jd(), Ka(), lr() (+15 more)

### Community 176 - "MenuCategoryResource"
Cohesion: 0.31
Nodes (3): MenuCategoryResource, ManageMenuCategories, TrashMenuCategories

### Community 180 - "markdown-editor.js"
Cohesion: 0.04
Nodes (99): Ei(), Aa(), ai(), ao(), Ba(), bf(), Bi(), Bt() (+91 more)

### Community 183 - "ExportFile"
Cohesion: 0.05
Nodes (20): ExcelExporter, PdfExporter, CleanupOldExportFilesJob, ExportReportJob, ExportFile, ExportFileObserver, ExportFilePolicy, ExportFinishedNotifier (+12 more)

### Community 185 - "createResolver"
Cohesion: 0.10
Nodes (25): active(), _animateOptions(), _cachedScopes(), _createAnimations(), createResolver(), datasetAnimationScopeKeys(), datasetElementScopeKeys(), get() (+17 more)

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
Cohesion: 0.06
Nodes (48): af(), at(), Bf(), br(), determineDataLimits(), df(), Di(), Fa() (+40 more)

### Community 301 - "classic/show.blade.php"
Cohesion: 0.50
Nodes (3): landing.sections., partials.customer.landing-footer, partials.customer.landing-header

### Community 302 - "AuthGlass"
Cohesion: 0.25
Nodes (3): FounderPanelProvider, AuthGlass, Illuminate\Support\HtmlString

### Community 313 - "S"
Cohesion: 0.20
Nodes (14): da(), getPadding(), gs(), isAttached(), ke(), ko(), on(), Re() (+6 more)

### Community 317 - "MenuItem"
Cohesion: 0.03
Nodes (20): RestaurantResource, RestaurantMenuCatalog, MenuCategory, MenuItem, LandingPageDataService, LandingLayout, self, MenuSearch (+12 more)

### Community 318 - "TenantContext"
Cohesion: 0.09
Nodes (9): DiningTableResource, ManageDiningTables, Action, Closure, TrashDiningTables, bootBelongsToRestaurantAndOutlet(), BelongsToRestaurantScope, TenantContext (+1 more)

### Community 320 - "configure"
Cohesion: 0.10
Nodes (33): addElements(), addEventListener(), bindEvents(), bindResponsiveEvents(), bindUserEvents(), buildOrUpdateScales(), _checkEventBindings(), configure() (+25 more)

### Community 321 - "constructor"
Cohesion: 0.06
Nodes (42): Ot(), alpha(), apply(), bo(), chartOptionScopes(), co(), constructor(), describe() (+34 more)

### Community 322 - "glassmorphism/show.blade.php"
Cohesion: 0.29
Nodes (6): landing.templates.glassmorphism.sections., landing.templates.glassmorphism.sections.hero, landing.sections., landing.templates.glassmorphism.partials.background, landing.templates.glassmorphism.sections.footer, landing.templates.glassmorphism.sections.header

### Community 324 - "CmsMedia"
Cohesion: 0.05
Nodes (8): WelcomeBannerWidget, PaymentResource, Payment, PaymentProofService, CashierOrderPreview, CheckoutTotals, CmsMedia, CashierOrderPreviewTest

### Community 327 - "jn"
Cohesion: 0.29
Nodes (8): E(), gt(), jn(), i(), St(), v(), ve(), x()

### Community 328 - "fn"
Cohesion: 0.16
Nodes (21): Ck(), De(), fn(), Gh(), ip(), Ja(), Jh(), Ji() (+13 more)

### Community 340 - "SubscriptionInvoice"
Cohesion: 0.07
Nodes (6): FounderStatsWidget, SubscriptionInvoice, SubscriptionInvoiceService, SubscriptionPlanSync, Filament\Widgets\StatsOverviewWidget, Filament\Widgets\StatsOverviewWidget\Stat

### Community 343 - "Filament/Pages/Dashboard.php"
Cohesion: 0.14
Nodes (6): Dashboard, EditRestaurant, Filament\Forms\Components\DatePicker, Filament\Pages\Dashboard\Concerns\HasFiltersForm, Filament\Schemas\Components\Flex, Filament\Support\Enums\Size

### Community 345 - "be"
Cohesion: 0.09
Nodes (25): aa(), ai(), ba(), be(), color(), darken(), desaturate(), _e() (+17 more)

### Community 346 - "constructor"
Cohesion: 0.03
Nodes (99): add(), addAttributes(), addOptions(), an(), applyInitialSize(), $b(), Bd(), Bg() (+91 more)

### Community 348 - "st"
Cohesion: 0.24
Nodes (11): et(), Ct(), he(), lt(), me(), ot(), se(), st() (+3 more)

### Community 349 - "N"
Cohesion: 0.33
Nodes (11): ae(), A(), E(), at(), be(), Gt(), i(), Jt() (+3 more)

### Community 356 - "Ae"
Cohesion: 0.67
Nodes (3): Ae(), Bt(), ne()

## Knowledge Gaps
- **333 isolated node(s):** `$schema`, `name`, `type`, `description`, `laravel` (+328 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **41 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `_s()` connect `components/chart.js` to `rich-editor.js`, `getProps`?**
  _High betweenness centrality (0.029) - this node is a cross-community bridge._
- **Why does `Wi()` connect `rich-editor.js` to `code-editor.js`, `components/select.js`, `columns/select.js`, `constructor`?**
  _High betweenness centrality (0.027) - this node is a cross-community bridge._
- **Why does `update()` connect `constructor` to `code-editor.js`, `rich-editor.js`, `y`, `r`, `find`, `get`, `slice`, `n`, `create`, `echo.js`, `fromObject`, `W`, `pd`, `te`, `markdown-editor.js`, `reduce`, `t`, `sliceDoc`, `measure`, `of`, `constructor`, `g$`, `_update`?**
  _High betweenness centrality (0.025) - this node is a cross-community bridge._
- **Are the 18 inferred relationships involving `constructor()` (e.g. with `a()` and `h()`) actually correct?**
  _`constructor()` has 18 INFERRED edges - model-reasoned connections that need verification._
- **Are the 26 inferred relationships involving `update()` (e.g. with `Pr()` and `a()`) actually correct?**
  _`update()` has 26 INFERRED edges - model-reasoned connections that need verification._
- **What connects `$schema`, `name`, `type` to the rest of the system?**
  _333 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `stat/chart.js` be split into smaller, more focused modules?**
  _Cohesion score 0.0242296918767507 - nodes in this community are weakly interconnected._