# Graph Report - WALK-IN-RESTO  (2026-09-02)

## Corpus Check
- 622 files · ~291,131 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 8474 nodes · 26149 edges · 343 communities (305 shown, 38 thin omitted)
- Extraction: 91% EXTRACTED · 9% INFERRED · 0% AMBIGUOUS · INFERRED: 2462 edges (avg confidence: 0.85)
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
- MenuItem
- updateElements
- TestCase
- constructor
- constructor
- Illuminate\Http\Request
- r
- find
- User
- s
- _update
- Order
- E
- DiningTable
- get
- BackedEnum
- advance
- OrderReceiptPrintTest
- TenantContext
- markdown-editor.js
- Restaurant
- support.js
- n
- Je
- facet
- columns/select.js
- .slice
- CreateCashierOrder.php
- echo.js
- resolve
- ExportFile
- create
- o
- Filament\Schemas\Schema
- prop
- toString
- SubscriptionInvoice
- Filament\Resources\Pages\ListRecords
- at
- Dashboard
- Filament\Resources\Pages\ManageRecords
- notifications.js
- fromObject
- PlatformSetting
- te
- Cn
- y
- components/select.js
- reduce
- tables.js
- ce
- r
- Illuminate\Database\Eloquent\Builder
- SubscriptionStatus
- Xt
- Illuminate\Support\Collection
- filament-right-click.js
- Filament\Tables\Table
- t
- Si
- RendersAnalyticsDashboard.php
- dx
- selectOption
- Im
- CreateCashierOrder
- ir
- configure
- slider.js
- ae
- selectOption
- fn
- slice
- getContext
- ExportFileResource
- sliceDoc
- file-upload.js
- st
- CashierFilamentActionsTest
- AppServiceProvider.php
- RestaurantDirectory
- FonnteErrorMessage
- lo
- fd
- Ts
- _update
- AdminPanelProvider.php
- O
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
- RegisterRestaurant.php
- composer.json
- fo
- A
- .panel
- date-time-picker.js
- 🚀 3. Langkah-Langkah Menambahkan Template Baru (*Workflow*)
- N
- Mt
- Login
- Illuminate\Support\Facades\Schema
- actions/actions.js
- c
- schemas.js
- Landasan Produksi — Tahap 1 (Walk-In Operasional)
- Guest
- createResolver
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
- Illuminate\Foundation\Http\FormRequest
- replace
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
- addElementByRule
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
- getDatasetMeta
- Y
- S
- constructor
- LandingLayout
- glassmorphism/show.blade.php
- _notify
- ViewOrder
- addEventListener
- LandingMenuCatalogTest
- Vf
- glassmorphism-background.blade.php

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

## Communities (343 total, 38 thin omitted)

### Community 0 - "stat/chart.js"
Cohesion: 0.02
Nodes (91): aa(), addControllers(), addEventListener(), addPlugins(), addScales(), applyStack(), ba(), beforeDatasetsDraw() (+83 more)

### Community 1 - "components/chart.js"
Cohesion: 0.01
Nodes (140): abutsStart(), addControllers(), addPlugins(), addScales(), alpha(), bd(), Be(), bm() (+132 more)

### Community 2 - "code-editor.js"
Cohesion: 0.01
Nodes (137): aa(), Ac(), addActive(), addChanges(), addCompletion(), addCompletions(), addNamespace(), addNamespaceObject() (+129 more)

### Community 3 - "rich-editor.js"
Cohesion: 0.01
Nodes (228): aa(), add(), addExtensions(), addHackNode(), addNode(), addTextblockHacks(), an(), ao() (+220 more)

### Community 4 - "MenuItem"
Cohesion: 0.02
Nodes (51): RestaurantMenuController, MenuCategoryResource, MenuItemResource, CmsBanner, LogOptions, CmsFaq, LogOptions, CmsGalleryImage (+43 more)

### Community 5 - "updateElements"
Cohesion: 0.03
Nodes (113): aa(), acquireContext(), afterAutoSkip(), Ao(), aspectRatio(), bh(), bu(), buildLookupTable() (+105 more)

### Community 6 - "TestCase"
Cohesion: 0.03
Nodes (55): CashierOrderService, BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles, Bityukov\CommandCenter\Filament\Pages\Commands, Bityukov\CommandCenter\Filament\Pages\History, RolePermissionSeeder, Filament\Facades\Filament, Illuminate\Database\QueryException, Illuminate\Foundation\Testing\RefreshDatabase (+47 more)

### Community 7 - "constructor"
Cohesion: 0.02
Nodes (171): active(), add(), addChunk(), addEventListener(), addInfoPane(), addInner(), addWindowListeners(), adjust() (+163 more)

### Community 8 - "constructor"
Cohesion: 0.03
Nodes (85): Bc(), bg(), chartOptionScopes(), Cl(), clone(), constructor(), create(), Ct() (+77 more)

### Community 9 - "Illuminate\Http\Request"
Cohesion: 0.02
Nodes (64): CartController, CheckoutController, MenuController, OrderController, ReviewController, SessionController, TableController, VisitController (+56 more)

### Community 10 - "r"
Cohesion: 0.04
Nodes (126): Ad(), addNodeView(), addProseMirrorPlugins(), af(), au(), ay(), B0(), Bf() (+118 more)

### Community 11 - "find"
Cohesion: 0.13
Nodes (22): baseDirAt(), bidiIn(), bidiSpans(), bidiSpansAt(), checkHover(), coordsAtPos(), Df(), dirAt() (+14 more)

### Community 12 - "User"
Cohesion: 0.03
Nodes (23): AnalyticsKpiWidget, Role, LogOptions, User, ExportFilePolicy, RolePolicy, UserPolicy, PermissionCheck (+15 more)

### Community 13 - "s"
Cohesion: 0.05
Nodes (71): add(), afterAutoSkip(), Bt(), buildLookupTable(), buildOrUpdateElements(), cl(), Cn(), cr() (+63 more)

### Community 14 - "_update"
Cohesion: 0.04
Nodes (106): addBox(), addElements(), adjustHitBoxes(), afterBuildTicks(), afterCalculateLabelRotation(), afterDataLimits(), afterDraw(), afterFit() (+98 more)

### Community 15 - "Order"
Cohesion: 0.03
Nodes (30): OrderReceiptPrintController, GuestPay, Order, OrderItem, Payment, Visit, GuestCheckoutService, KdsItemService (+22 more)

### Community 16 - "E"
Cohesion: 0.05
Nodes (61): $a(), add(), af(), B(), bo(), bs(), ca(), _cachedScopes() (+53 more)

### Community 17 - "DiningTable"
Cohesion: 0.06
Nodes (6): DiningTable, LogOptions, TableFloorPlan, SoftDeleteTrashTest, StaleOperationsServiceTest, TableFloorPlanTest

### Community 18 - "get"
Cohesion: 0.03
Nodes (101): addBlockWidget(), addBreak(), addComposition(), addDelimiter(), addInlineWidget(), addLine(), addLineStart(), addLineStartIfNotCovered() (+93 more)

### Community 19 - "BackedEnum"
Cohesion: 0.16
Nodes (32): CmsFaqResource, TrashCmsFaqs, KdsStationResource, ModifierGroupResource, BackedEnum, Filament\Actions\DeleteAction, Filament\Actions\EditAction, Filament\Actions\ViewAction (+24 more)

### Community 20 - "advance"
Cohesion: 0.05
Nodes (65): addChild(), addGaps(), addLeafElement(), addNode(), advance(), ATXHeading(), blank(), break() (+57 more)

### Community 22 - "TenantContext"
Cohesion: 0.06
Nodes (11): DiningTableResource, ManageDiningTables, Action, Closure, TrashDiningTables, MenuItemResource, EditMenuItem, ListMenuItems (+3 more)

### Community 23 - "markdown-editor.js"
Cohesion: 0.05
Nodes (83): ad(), af(), An(), bf(), bo(), Bt(), cd(), Ct() (+75 more)

### Community 24 - "Restaurant"
Cohesion: 0.03
Nodes (36): ExpireStaleOperationsCommand, GenerateUpcomingInvoicesCommand, ProcessSubscriptionLifecycleCommand, analyticsDateFrom(), analyticsDateTo(), analyticsDayCount(), analyticsRangeLabel(), analyticsSnapshot() (+28 more)

### Community 25 - "support.js"
Cohesion: 0.05
Nodes (75): acquireScrollLock(), ai(), e(), Bi(), br(), Bt(), ca(), close() (+67 more)

### Community 26 - "n"
Cohesion: 0.09
Nodes (79): _a(), Ae(), ar(), as(), bc(), ee(), ue(), u() (+71 more)

### Community 27 - "Je"
Cohesion: 0.04
Nodes (78): addGlobalAttributes(), addInputRules(), addMark(), addPasteRules(), addStoredMark(), Ah(), Ax(), childAfter() (+70 more)

### Community 28 - "facet"
Cohesion: 0.04
Nodes (68): accept(), baseTheme(), blur(), bu(), build(), dispatch(), dr(), facet() (+60 more)

### Community 29 - "columns/select.js"
Cohesion: 0.06
Nodes (57): A(), An(), applyDisabledState(), b(), Bt(), Ce(), D(), disable() (+49 more)

### Community 30 - ".slice"
Cohesion: 0.04
Nodes (75): accepts(), addAttributes(), addInner(), addMaps(), addStep(), addTransform(), appendMap(), appendMapping() (+67 more)

### Community 31 - "CreateCashierOrder.php"
Cohesion: 0.08
Nodes (36): Action, trashPageAction(), ViewSubscriptionInvoice, Width, Action, Filament\Actions\Action, Filament\Forms\Components\ColorPicker, Filament\Forms\Components\Component (+28 more)

### Community 32 - "echo.js"
Cohesion: 0.05
Nodes (49): a(), ar(), b(), Be(), Ce(), cr(), d(), De() (+41 more)

### Community 33 - "resolve"
Cohesion: 0.06
Nodes (126): Ac(), addCommands(), addKeyboardShortcuts(), after(), al(), AS(), before(), blockRange() (+118 more)

### Community 34 - "ExportFile"
Cohesion: 0.03
Nodes (30): PdfExporter, OrderReceiptDownloadController, CleanupOldExportFilesJob, ExportReportJob, SendWhatsappReceiptJob, ExportFile, OrderReceipt, WhatsappMessage (+22 more)

### Community 35 - "create"
Cohesion: 0.05
Nodes (91): addNodeMark(), ag(), allowsMarks(), bu(), _c(), canReplaceWith(), checkContent(), close() (+83 more)

### Community 36 - "o"
Cohesion: 0.04
Nodes (121): ag(), ah(), apply(), ar(), au(), average(), Ba(), beforeDatasetDraw() (+113 more)

### Community 37 - "Filament\Schemas\Schema"
Cohesion: 0.04
Nodes (18): ManageBillingAccount, BackedEnum, UnitEnum, ManageHomeLanding, BackedEnum, UnitEnum, ManagePlatformPages, BackedEnum (+10 more)

### Community 38 - "prop"
Cohesion: 0.05
Nodes (69): acceptToken(), allows(), AQ(), atLastNode(), au(), child(), childAfter(), childBefore() (+61 more)

### Community 39 - "toString"
Cohesion: 0.14
Nodes (20): Bc(), check(), checkAttrs(), endIndex(), getObj(), hasProtocol(), $i(), Ra() (+12 more)

### Community 40 - "SubscriptionInvoice"
Cohesion: 0.06
Nodes (6): FounderStatsWidget, SubscriptionInvoice, SubscriptionInvoiceService, Filament\Widgets\StatsOverviewWidget, Filament\Widgets\StatsOverviewWidget\Stat, InvoicePaymentTest

### Community 41 - "Filament\Resources\Pages\ListRecords"
Cohesion: 0.08
Nodes (13): ListFacilities, ListLandingTemplates, ListSubscriptionInvoices, EditSubscriptionPlan, ListSubscriptionPlans, SubscriptionPlanResource, ListTenants, ListOrders (+5 more)

### Community 42 - "at"
Cohesion: 0.08
Nodes (48): Rd(), $a(), ak(), at(), bk(), c(), bp(), Dk() (+40 more)

### Community 43 - "Dashboard"
Cohesion: 0.05
Nodes (17): CreateFacility, EditFacility, CreateLandingTemplate, EditLandingTemplate, CreateSubscriptionInvoice, CreateTenant, EditTenant, Dashboard (+9 more)

### Community 44 - "Filament\Resources\Pages\ManageRecords"
Cohesion: 0.06
Nodes (15): ManageCmsFaqs, CmsGalleryImageResource, ManageCmsGalleryImages, TrashCmsGalleryImages, ManageKdsStations, Closure, TrashKdsStations, ManageModifierGroups (+7 more)

### Community 45 - "notifications.js"
Cohesion: 0.06
Nodes (31): actions(), button(), c(), close(), configureAnimations(), configureTransitions(), constructor(), danger() (+23 more)

### Community 46 - "fromObject"
Cohesion: 0.03
Nodes (109): El(), ac(), ae(), after(), Al(), Am(), before(), bl() (+101 more)

### Community 47 - "PlatformSetting"
Cohesion: 0.03
Nodes (25): WelcomeBannerWidget, PlatformPageController, RestaurantLandingController, RestaurantBrandResource, RestaurantResource, RestaurantSummaryResource, self, PlatformSetting (+17 more)

### Community 48 - "te"
Cohesion: 0.05
Nodes (9): Bn(), br(), ji(), on(), qd(), Ri(), te(), Vi() (+1 more)

### Community 49 - "Cn"
Cohesion: 0.13
Nodes (46): Cn(), b(), Be(), Ce(), De(), dn(), _e(), Fe() (+38 more)

### Community 50 - "y"
Cohesion: 0.18
Nodes (49): al(), at(), Be(), Cr(), de(), dt(), Ee(), ef() (+41 more)

### Community 51 - "components/select.js"
Cohesion: 0.08
Nodes (38): A(), applyDisabledState(), b(), Bt(), D(), disable(), E(), en() (+30 more)

### Community 52 - "reduce"
Cohesion: 0.07
Nodes (52): addActions(), advanceFully(), advanceStack(), allActions(), c0(), canShift(), close(), deadEnd() (+44 more)

### Community 53 - "tables.js"
Cohesion: 0.13
Nodes (44): A(), ae(), B(), be(), C(), ce(), E(), ee() (+36 more)

### Community 54 - "ce"
Cohesion: 0.08
Nodes (46): Ac(), ao(), bl(), Cc(), ce(), cl(), Cn(), Dc() (+38 more)

### Community 55 - "r"
Cohesion: 0.15
Nodes (43): _a(), ar(), c(), f(), d(), di(), g(), Hi() (+35 more)

### Community 56 - "Illuminate\Database\Eloquent\Builder"
Cohesion: 0.06
Nodes (15): getRecordRouteBindingEloquentQuery(), TemplateRadioPicker, KitchenDisplay, BackedEnum, UnitEnum, bootScopedToRestaurant(), scopeForRestaurant(), scopeWithoutRestaurantScope() (+7 more)

### Community 57 - "SubscriptionStatus"
Cohesion: 0.11
Nodes (7): ExcelExporter, BackedEnum, UnitEnum, SubscriptionStatus, Illuminate\Contracts\View\View, Maatwebsite\Excel\Concerns\FromView, Maatwebsite\Excel\Concerns\ShouldAutoSize

### Community 58 - "Xt"
Cohesion: 0.12
Nodes (44): ae(), At(), bi(), bn(), ci(), cn(), ct(), de() (+36 more)

### Community 59 - "Illuminate\Support\Collection"
Cohesion: 0.06
Nodes (8): periodSummary(), RestaurantCategory, RestaurantDirectory, RestaurantCategorySeeder, Illuminate\Contracts\Pagination\LengthAwarePaginator, Illuminate\Support\Collection, RestaurantDirectoryTest, TenantIsolationTest

### Community 60 - "filament-right-click.js"
Cohesion: 0.11
Nodes (42): a(), ae(), b(), be(), C(), ce(), D(), de() (+34 more)

### Community 61 - "Filament\Tables\Table"
Cohesion: 0.06
Nodes (12): ActivityResource, ListActivities, ViewActivity, ActivityInfolist, ActivitiesTable, TableRightClick, PendingPaymentsWidget, Activity (+4 more)

### Community 62 - "t"
Cohesion: 0.06
Nodes (50): a$(), activeForPoint(), addBlock(), addLineDeco(), b1(), blankContent(), boundChange(), commit() (+42 more)

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

### Community 67 - "Im"
Cohesion: 0.18
Nodes (15): Bm(), eat(), err(), Im(), isInGroup(), Lm(), $m(), matchesContext() (+7 more)

### Community 68 - "CreateCashierOrder"
Cohesion: 0.13
Nodes (4): CreateCashierOrder, BackedEnum, UnitEnum, Width

### Community 69 - "ir"
Cohesion: 0.13
Nodes (34): Ft(), ir(), ce(), de(), Dt(), ee(), Et(), fe() (+26 more)

### Community 70 - "configure"
Cohesion: 0.06
Nodes (55): addElements(), afterDatasetsUpdate(), bi(), bindEvents(), bindUserEvents(), buildOrUpdateControllers(), buildOrUpdateScales(), _checkEventBindings() (+47 more)

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

### Community 75 - "slice"
Cohesion: 0.05
Nodes (123): addElement(), Ah(), baseIndentFor(), be(), Bg(), a(), blockAt(), bS() (+115 more)

### Community 76 - "getContext"
Cohesion: 0.07
Nodes (51): acquireContext(), Ae(), Ao(), bl(), Ca(), ci(), _computeGridLineItems(), _computeLabelArea() (+43 more)

### Community 77 - "ExportFileResource"
Cohesion: 0.13
Nodes (6): GenerateReport, BackedEnum, UnitEnum, ExportFileResource, ListExportFiles, TrashExportFiles

### Community 78 - "sliceDoc"
Cohesion: 0.10
Nodes (27): aO(), charCategorizer(), Fc(), flatten(), getCursor(), getDeco(), gT(), highlight() (+19 more)

### Community 79 - "file-upload.js"
Cohesion: 0.05
Nodes (53): hc(), Bp(), c(), ca(), clickPercent(), constructor(), Cp(), da() (+45 more)

### Community 80 - "st"
Cohesion: 0.05
Nodes (48): ad(), applyStack(), br(), Di(), drawCaret(), _f(), first(), getCaretPosition() (+40 more)

### Community 82 - "AppServiceProvider.php"
Cohesion: 0.06
Nodes (24): SoftDeleteTrashPage, CmsBannerResource, ManageCmsBanners, TrashCmsBanners, MenuCategoryResource, ManageMenuCategories, TrashMenuCategories, TrashUsers (+16 more)

### Community 84 - "FonnteErrorMessage"
Cohesion: 0.11
Nodes (8): FonnteClient, FonnteErrorMessage, Throwable, Illuminate\Http\Client\ConnectionException, Illuminate\Http\Client\Response, PHPUnit\Framework\Attributes\DataProvider, RuntimeException, FonnteErrorMessageTest

### Community 85 - "lo"
Cohesion: 0.06
Nodes (46): _0(), addOptions(), buildProps(), can(), Cc(), createCan(), createChain(), deleteNode() (+38 more)

### Community 86 - "fd"
Cohesion: 0.06
Nodes (48): activateHover(), addToSet(), bd(), between(), Bh(), cd(), childString(), clearDelayedAndroidKey() (+40 more)

### Community 87 - "Ts"
Cohesion: 0.13
Nodes (30): Ck(), clearIncompatible(), Cp(), De(), dS(), fg(), k(), fn() (+22 more)

### Community 88 - "_update"
Cohesion: 0.07
Nodes (43): themeClasses(), active(), afterBuildTicks(), afterCalculateLabelRotation(), afterDataLimits(), afterFit(), afterSetDimensions(), afterTickToLabelConversion() (+35 more)

### Community 89 - "AdminPanelProvider.php"
Cohesion: 0.16
Nodes (19): ApplyPlatformBrandTheme, BezhanSalleh\FilamentShield\FilamentShieldPlugin, Bityukov\CommandCenter\Filament\CommandCenterPlugin, Filament\Http\Middleware\Authenticate, Filament\Http\Middleware\AuthenticateSession, Filament\Http\Middleware\DisableBladeIconComponents, Filament\Http\Middleware\DispatchServingFilamentEvent, Filament\Navigation\NavigationGroup (+11 more)

### Community 90 - "O"
Cohesion: 0.19
Nodes (38): b(), $c(), X(), ca(), me(), D(), _e(), Ea() (+30 more)

### Community 91 - "devDependencies"
Cohesion: 0.11
Nodes (18): axios, concurrently, laravel-vite-plugin, devDependencies, axios, concurrently, laravel-vite-plugin, tailwindcss (+10 more)

### Community 92 - "filament/app.js"
Cohesion: 0.13
Nodes (8): B(), close(), G(), init(), P(), setUpResizeObserver(), x(), Y()

### Community 93 - "g$"
Cohesion: 0.05
Nodes (59): attrs(), bi(), cc(), ch(), cO(), _d(), eh(), Ex() (+51 more)

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
Nodes (26): canCreate(), canDelete(), canDeleteAny(), canEdit(), canViewAny(), canForceDelete(), canRestore(), FacilityResource (+18 more)

### Community 99 - "color-picker.js"
Cohesion: 0.14
Nodes (3): style(), update(), [x]()

### Community 100 - "js/app.js"
Cohesion: 0.22
Nodes (6): applyTheme(), bindThemeToggle(), initReveal(), initTheme(), syncThemeToggleIcons(), toggleTheme()

### Community 102 - "RegisterRestaurant.php"
Cohesion: 0.07
Nodes (10): RegisterRestaurant, RestaurantProvisioner, SubscriptionPlanSync, CashierOrderPreview, CheckoutTotals, ReservedSlugs, Illuminate\Support\Facades\Auth, Illuminate\Validation\Rule (+2 more)

### Community 103 - "composer.json"
Cohesion: 0.14
Nodes (13): autoload-dev, psr-4, description, keywords, license, minimum-stability, name, prefer-stable (+5 more)

### Community 104 - "fo"
Cohesion: 0.07
Nodes (41): alpha(), be(), bo(), co(), darken(), desaturate(), Ea(), es() (+33 more)

### Community 105 - "A"
Cohesion: 0.10
Nodes (36): A(), As(), buildTicks(), calculateLabelRotation(), _calculatePadding(), _computeLabelItems(), _computeLabelSizes(), computeTickLimit() (+28 more)

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

### Community 116 - "c"
Cohesion: 0.09
Nodes (32): ai(), al(), bs(), dl(), c(), er(), first(), getCenterPoint() (+24 more)

### Community 118 - "Landasan Produksi — Tahap 1 (Walk-In Operasional)"
Cohesion: 0.05
Nodes (37): 10. Aturan yang tidak boleh dilanggar, 11. Matriks skenario lapangan, 12. Yang sengaja tidak didukung (bukan gap, non-goal), 13. Tahap 2 (bukan sekarang), 14. Urutan bangun (agar cepat produksi), 1. Keputusan terkunci, 2. Modul tahap 1 vs ditunda, 3. Model domain (+29 more)

### Community 119 - "Guest"
Cohesion: 0.05
Nodes (34): Bentuk objek, CartItemResource, DELETE `/guest/cart/items/{cartItem}`, GET `/guest/cart`, GET `/guest/menu`, GET `/guest/orders`, GET `/guest/orders/{public_id}`, GET `/guest/review` (+26 more)

### Community 120 - "createResolver"
Cohesion: 0.09
Nodes (32): _cachedScopes(), createResolver(), datasetAnimationScopeKeys(), datasetElementScopeKeys(), get(), getMaxOverflow(), getOptionScopes(), getSharedOptions() (+24 more)

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

### Community 138 - "Illuminate\Foundation\Http\FormRequest"
Cohesion: 0.08
Nodes (8): AddCartItemRequest, CheckoutRequest, ClaimTableRequest, JoinTableRequest, StorePaymentProofRequest, StoreRestaurantReviewRequest, UpdateCartItemRequest, Illuminate\Foundation\Http\FormRequest

### Community 139 - "replace"
Cohesion: 0.15
Nodes (17): applyChanges(), balanced(), decompose(), decomposeLeft(), decomposeRight(), getReplacement(), heightForGap(), heightForLine() (+9 more)

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

### Community 176 - "addElementByRule"
Cohesion: 0.14
Nodes (26): addAll(), addDOM(), addElement(), addElementByRule(), addTextNode(), addToSet(), allowedMarks(), allowsMarkType() (+18 more)

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

### Community 313 - "getDatasetMeta"
Cohesion: 0.11
Nodes (26): afterDatasetsUpdate(), An(), generateLabels(), getDatasetMeta(), getDataVisibility(), _getLegendItemAt(), getMaxBorderWidth(), _getSortedDatasetMetas() (+18 more)

### Community 317 - "Y"
Cohesion: 0.11
Nodes (22): at(), Bf(), determineDataLimits(), ef(), getMatchingVisibleMetas(), getMinMax(), _getOtherScale(), getUserBounds() (+14 more)

### Community 318 - "S"
Cohesion: 0.11
Nodes (23): ar(), da(), getPadding(), gn(), gs(), It(), ji(), ke() (+15 more)

### Community 320 - "constructor"
Cohesion: 0.11
Nodes (22): Ot(), apply(), chartOptionScopes(), constructor(), describe(), ei(), getDevicePixelRatio(), getMeta() (+14 more)

### Community 321 - "LandingLayout"
Cohesion: 0.11
Nodes (3): LandingLayout, self, Illuminate\Support\Arr

### Community 322 - "glassmorphism/show.blade.php"
Cohesion: 0.29
Nodes (6): landing.templates.glassmorphism.sections., landing.templates.glassmorphism.sections.hero, landing.sections., landing.templates.glassmorphism.partials.background, landing.templates.glassmorphism.sections.footer, landing.templates.glassmorphism.sections.header

### Community 324 - "_notify"
Cohesion: 0.20
Nodes (14): active(), _animateOptions(), cancel(), _createAnimations(), _createDescriptors(), _descriptors(), _notify(), _notifyStateChanges() (+6 more)

### Community 327 - "addEventListener"
Cohesion: 0.33
Nodes (7): addEventListener(), bindResponsiveEvents(), fu(), isAttached(), nr(), removeEventListener(), Ua()

### Community 340 - "Vf"
Cohesion: 0.33
Nodes (7): contains(), gi(), splitAt(), toISOTime(), toMillis(), Vf(), ye()

## Knowledge Gaps
- **322 isolated node(s):** `$schema`, `name`, `type`, `description`, `laravel` (+317 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **38 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `_s()` connect `components/chart.js` to `rich-editor.js`, `o`?**
  _High betweenness centrality (0.030) - this node is a cross-community bridge._
- **Why does `update()` connect `constructor` to `code-editor.js`, `rich-editor.js`, `r`, `replace`, `find`, `get`, `advance`, `markdown-editor.js`, `n`, `facet`, `.slice`, `echo.js`, `at`, `fromObject`, `te`, `reduce`, `t`, `dx`, `slice`, `sliceDoc`, `fd`, `_update`, `O`, `g$`?**
  _High betweenness centrality (0.029) - this node is a cross-community bridge._
- **Why does `Wi()` connect `at` to `code-editor.js`, `rich-editor.js`, `constructor`, `components/select.js`, `columns/select.js`?**
  _High betweenness centrality (0.027) - this node is a cross-community bridge._
- **Are the 18 inferred relationships involving `constructor()` (e.g. with `a()` and `h()`) actually correct?**
  _`constructor()` has 18 INFERRED edges - model-reasoned connections that need verification._
- **Are the 26 inferred relationships involving `update()` (e.g. with `Pr()` and `a()`) actually correct?**
  _`update()` has 26 INFERRED edges - model-reasoned connections that need verification._
- **What connects `$schema`, `name`, `type` to the rest of the system?**
  _322 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `stat/chart.js` be split into smaller, more focused modules?**
  _Cohesion score 0.02443338861249309 - nodes in this community are weakly interconnected._