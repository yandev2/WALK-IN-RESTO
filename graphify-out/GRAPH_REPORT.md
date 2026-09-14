# Graph Report - WALK-IN-RESTO  (2026-09-14)

## Corpus Check
- 687 files · ~345,270 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 8930 nodes · 27561 edges · 370 communities (328 shown, 42 thin omitted)
- Extraction: 91% EXTRACTED · 9% INFERRED · 0% AMBIGUOUS · INFERRED: 2476 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `138ef553`
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
- fromObject
- MenuItem
- constructor
- slice
- User
- constructor
- _update
- Order
- advance
- r
- get
- updateElements
- DiningTable
- Illuminate\Foundation\Http\FormRequest
- AdminPanelProvider.php
- OrderResource
- ExportFile
- support.js
- n
- g$
- facet
- columns/select.js
- .slice
- reduce
- echo.js
- resolve
- sliceDoc
- _update
- BackedEnum
- s
- W
- Illuminate\Http\Request
- Illuminate\Database\Eloquent\Builder
- .forEach
- Ye
- EditProfile
- Filament\Resources\Pages\EditRecord
- notifications.js
- markdown-editor.js
- parse
- te
- Cn
- components/select.js
- o
- tables.js
- ff
- r
- nodeAt
- SubscriptionStatus
- Xt
- 2026_08_20_010000_add_floor_layout_to_tables_table.php
- filament-right-click.js
- getContext
- next
- Si
- PlatformSetting
- ae
- selectOption
- Filament\Schemas\Schema
- CreateCashierOrder
- ir
- st
- slider.js
- Restaurant
- selectOption
- fn
- i
- InvoicePaymentTest
- static
- file-upload.js
- configure
- ce
- RestaurantDirectory
- FonnteErrorMessage
- LandingLayout
- Y
- Illuminate\Database\Migrations\Migration
- devDependencies
- filament/app.js
- SubscriptionInvoiceService
- fn
- E
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
- create
- Mt
- RestaurantCategory
- A
- SubscriptionAccess
- Filament\Tables\Table
- actions/actions.js
- replace
- schemas.js
- Landasan Produksi — Tahap 1 (Walk-In Operasional)
- Guest
- draw
- 2. Masalah di lapangan — dan apa yang sistem selesaikan
- Pe
- require-dev
- Filament\Resources\Pages\ManageRecords
- 6. Katalog fitur
- config
- 6. Katalog fitur
- CashierShiftResource
- register-restaurant.blade.php
- add-to-cart-modal.blade.php
- S
- components/actions.js
- psr-4
- extra
- logging.php
- selectRecords
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
- RegisterRestaurant
- closeSimpleModeModal
- Illuminate\Support\Facades\Schema
- Dashboard
- cc
- Illuminate\Database\Schema\Blueprint
- CashierFilamentActionsTest
- CmsMedia
- SubscriptionWriteGuard
- getDatasetMeta
- KitchenDisplay
- filament-shield.php
- 🎨 Spesifikasi Token & Class CSS yang Tersedia di `theme.css`
- dx
- AppServiceProvider.php
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
- Facility
- classic/show.blade.php
- SubscriptionPlan
- FounderStatsWidget.php
- _each
- ManageSoundNotifications
- rules/graphify.md
- workflows/graphify.md
- _notify
- ModifierGroupResource
- ManageBillingAccount
- glassmorphism/show.blade.php
- CashierShift
- addSingleBadge
- SubscriptionInvoice
- glassmorphism-background.blade.php
- CmsFaqResource
- st
- RefreshesAnalyticsChart.php
- dropdown.blade.php
- GraceReadOnlyTest
- Filament\Panel
- ut
- OrderReceiptPrintTest
- addEventListener
- Vf

## God Nodes (most connected - your core abstractions)
1. `Restaurant` - 317 edges
2. `User` - 296 edges
3. `TestCase` - 167 edges
4. `Order` - 153 edges
5. `constructor()` - 152 edges
6. `update()` - 148 edges
7. `MenuItem` - 108 edges
8. `PlatformSetting` - 105 edges
9. `resolve()` - 94 edges
10. `y()` - 93 edges

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

## Communities (370 total, 42 thin omitted)

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
Nodes (181): themeClasses(), aa(), Ad(), addHackNode(), addNode(), addTextblockHacks(), applyAspectRatio(), applyConstraints() (+173 more)

### Community 4 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.02
Nodes (44): CashierShiftMovement, CmsBanner, LogOptions, CmsFaq, LogOptions, CmsGalleryImage, CmsProfile, LogOptions (+36 more)

### Community 5 - "y"
Cohesion: 0.16
Nodes (71): at(), b(), Be(), $c(), X(), me(), Cr(), Ct() (+63 more)

### Community 6 - "TestCase"
Cohesion: 0.03
Nodes (59): CashierOrderService, OrderPaymentService, ImageOptimizer, BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles, Bityukov\CommandCenter\Filament\Pages\Commands, Bityukov\CommandCenter\Filament\Pages\History, RolePermissionSeeder, Filament\Facades\Filament (+51 more)

### Community 7 - "constructor"
Cohesion: 0.02
Nodes (142): add(), addChunk(), addEventListener(), addInfoPane(), addInner(), addWindowListeners(), adjust(), al() (+134 more)

### Community 8 - "fromObject"
Cohesion: 0.03
Nodes (109): El(), ac(), ae(), after(), Al(), Am(), before(), bl() (+101 more)

### Community 9 - "MenuItem"
Cohesion: 0.03
Nodes (15): TemplateRadioPicker, MenuItem, LogOptions, MenuItemPhoto, CashierMenuCatalog, Filament\Forms\Components\Field, Illuminate\Database\Eloquent\Collection, extraMenuItem() (+7 more)

### Community 10 - "constructor"
Cohesion: 0.03
Nodes (107): add(), addExtensions(), an(), applyInitialSize(), $b(), Bd(), Bg(), Bo() (+99 more)

### Community 11 - "slice"
Cohesion: 0.04
Nodes (133): a$(), activateHover(), addChanges(), addElement(), Ah(), AX(), b1(), balance() (+125 more)

### Community 12 - "User"
Cohesion: 0.03
Nodes (28): Activity, Role, LogOptions, User, ExportFilePolicy, RolePolicy, UserPolicy, PermissionCheck (+20 more)

### Community 13 - "constructor"
Cohesion: 0.03
Nodes (85): Bc(), bg(), chartOptionScopes(), Cl(), clone(), constructor(), create(), Ct() (+77 more)

### Community 14 - "_update"
Cohesion: 0.04
Nodes (106): addBox(), addElements(), adjustHitBoxes(), afterBuildTicks(), afterCalculateLabelRotation(), afterDataLimits(), afterDraw(), afterFit() (+98 more)

### Community 15 - "Order"
Cohesion: 0.02
Nodes (28): ExportFileDownloadController, OrderReceiptDownloadController, OrderReceiptPrintController, SendWhatsappReceiptJob, GuestPay, Order, OrderItem, OrderReceipt (+20 more)

### Community 16 - "advance"
Cohesion: 0.05
Nodes (62): addChild(), addGaps(), addLeafElement(), addNode(), advance(), ATXHeading(), blank(), break() (+54 more)

### Community 17 - "r"
Cohesion: 0.04
Nodes (128): addNodeView(), addOptions(), addProseMirrorPlugins(), af(), au(), ay(), bl(), cf() (+120 more)

### Community 18 - "get"
Cohesion: 0.04
Nodes (87): addBlockWidget(), addBreak(), addComposition(), addDelimiter(), addInlineWidget(), addLine(), addLineStart(), addLineStartIfNotCovered() (+79 more)

### Community 19 - "updateElements"
Cohesion: 0.03
Nodes (113): aa(), acquireContext(), afterAutoSkip(), Ao(), aspectRatio(), bh(), bu(), buildLookupTable() (+105 more)

### Community 20 - "DiningTable"
Cohesion: 0.02
Nodes (27): ScanTable, DiningTable, LogOptions, Visit, VisitDevice, GuestCheckoutService, MenuModifierService, StaleOperationsService (+19 more)

### Community 21 - "Illuminate\Foundation\Http\FormRequest"
Cohesion: 0.08
Nodes (8): AddCartItemRequest, CheckoutRequest, ClaimTableRequest, JoinTableRequest, StorePaymentProofRequest, StoreRestaurantReviewRequest, UpdateCartItemRequest, Illuminate\Foundation\Http\FormRequest

### Community 22 - "AdminPanelProvider.php"
Cohesion: 0.11
Nodes (23): AdminPanelProvider, FounderPanelProvider, AuthGlass, BezhanSalleh\FilamentShield\FilamentShieldPlugin, Bityukov\CommandCenter\Filament\CommandCenterPlugin, Filament\Http\Middleware\Authenticate, Filament\Http\Middleware\AuthenticateSession, Filament\Http\Middleware\DisableBladeIconComponents (+15 more)

### Community 23 - "OrderResource"
Cohesion: 0.05
Nodes (14): ExcelExporter, OrderResource, ListOrders, ViewOrder, OrderTodayStatsWidget, CashierOrderSoundAlert, Carbon\Carbon, Filament\Infolists\Components\ImageEntry (+6 more)

### Community 24 - "ExportFile"
Cohesion: 0.05
Nodes (16): PdfExporter, CleanupOldExportFilesJob, ExportReportJob, ExportFile, ExportFileObserver, ExportFinishedNotifier, ReportExportDispatcher, ExportService (+8 more)

### Community 25 - "support.js"
Cohesion: 0.05
Nodes (75): acquireScrollLock(), ai(), e(), Bi(), br(), Bt(), ca(), close() (+67 more)

### Community 26 - "n"
Cohesion: 0.09
Nodes (80): _a(), Ae(), ar(), as(), Ba(), bc(), bf(), ee() (+72 more)

### Community 27 - "g$"
Cohesion: 0.07
Nodes (45): acceptToken(), allows(), bd(), Bh(), clearDelayedAndroidKey(), d0(), De(), delayAndroidKey() (+37 more)

### Community 28 - "facet"
Cohesion: 0.04
Nodes (88): accept(), active(), applyTransaction(), asSingle(), B(), baseTheme(), between(), blur() (+80 more)

### Community 29 - "columns/select.js"
Cohesion: 0.06
Nodes (57): A(), An(), applyDisabledState(), b(), Bt(), Ce(), D(), disable() (+49 more)

### Community 30 - ".slice"
Cohesion: 0.05
Nodes (70): accepts(), addAttributes(), addInner(), addMaps(), addStep(), addTransform(), appendMap(), appendMapping() (+62 more)

### Community 31 - "reduce"
Cohesion: 0.06
Nodes (62): addActions(), advanceFully(), advanceStack(), allActions(), apply(), c0(), canShift(), checkAsyncSchedule() (+54 more)

### Community 32 - "echo.js"
Cohesion: 0.05
Nodes (49): a(), ar(), b(), Be(), Ce(), cr(), d(), De() (+41 more)

### Community 33 - "resolve"
Cohesion: 0.07
Nodes (105): Ac(), addCommands(), addKeyboardShortcuts(), after(), al(), before(), blockRange(), Bs() (+97 more)

### Community 34 - "sliceDoc"
Cohesion: 0.15
Nodes (19): aO(), charCategorizer(), Fc(), flatten(), getCursor(), getDeco(), gT(), highlight() (+11 more)

### Community 35 - "_update"
Cohesion: 0.05
Nodes (63): active(), afterBuildTicks(), afterCalculateLabelRotation(), afterDataLimits(), afterDatasetsUpdate(), afterFit(), afterSetDimensions(), afterTickToLabelConversion() (+55 more)

### Community 36 - "BackedEnum"
Cohesion: 0.12
Nodes (48): CustomerAnalytics, TableRightClick, BackedEnum, Filament\Actions\Action, Filament\Actions\DeleteAction, Filament\Actions\EditAction, Filament\Actions\ViewAction, Filament\Forms\Components\CheckboxList (+40 more)

### Community 37 - "s"
Cohesion: 0.05
Nodes (62): aa(), addEventListener(), Ae(), ai(), al(), an(), _animateOptions(), bindResponsiveEvents() (+54 more)

### Community 38 - "W"
Cohesion: 0.05
Nodes (79): AQ(), atLastNode(), au(), child(), childAfter(), childBefore(), continue(), cursor() (+71 more)

### Community 39 - "Illuminate\Http\Request"
Cohesion: 0.02
Nodes (61): CartController, CheckoutController, MenuController, OrderController, ReviewController, SessionController, TableController, VisitController (+53 more)

### Community 40 - "Illuminate\Database\Eloquent\Builder"
Cohesion: 0.05
Nodes (11): getRecordRouteBindingEloquentQuery(), bootScopedToRestaurant(), scopeForRestaurant(), scopeWithoutRestaurantScope(), Customer, CustomerLoyaltyPoint, BelongsToRestaurantScope, CustomerCrmService (+3 more)

### Community 41 - ".forEach"
Cohesion: 0.04
Nodes (104): _0(), addGlobalAttributes(), addInputRules(), addMark(), addPasteRules(), addStoredMark(), addToSet(), Ah() (+96 more)

### Community 42 - "Ye"
Cohesion: 0.09
Nodes (47): Rd(), $a(), at(), bk(), c(), bp(), bt(), Cr() (+39 more)

### Community 43 - "EditProfile"
Cohesion: 0.12
Nodes (5): EditProfile, ProfileInformationForm, Ipatco\FilamentProfile\Forms\ProfileInformationForm, Ipatco\FilamentProfile\Pages\EditProfile, ProfilePageTest

### Community 44 - "Filament\Resources\Pages\EditRecord"
Cohesion: 0.10
Nodes (7): EditFacility, EditLandingTemplate, EditSubscriptionPlan, SubscriptionPlanResource, EditTenant, EditMenuItem, Filament\Resources\Pages\EditRecord

### Community 45 - "notifications.js"
Cohesion: 0.06
Nodes (31): actions(), button(), c(), close(), configureAnimations(), configureTransitions(), constructor(), danger() (+23 more)

### Community 46 - "markdown-editor.js"
Cohesion: 0.05
Nodes (85): ad(), af(), ai(), al(), An(), ao(), bo(), br() (+77 more)

### Community 47 - "parse"
Cohesion: 0.06
Nodes (54): buildOrUpdateElements(), Cn(), determineDataLimits(), diff(), dn(), el(), En(), endOf() (+46 more)

### Community 48 - "te"
Cohesion: 0.05
Nodes (11): Bn(), Id(), ji(), on(), qd(), qi(), Ri(), te() (+3 more)

### Community 49 - "Cn"
Cohesion: 0.13
Nodes (46): Cn(), b(), Be(), Ce(), De(), dn(), _e(), Fe() (+38 more)

### Community 51 - "components/select.js"
Cohesion: 0.08
Nodes (38): A(), applyDisabledState(), b(), Bt(), D(), disable(), E(), en() (+30 more)

### Community 52 - "o"
Cohesion: 0.04
Nodes (121): ag(), ah(), apply(), ar(), au(), average(), Ba(), beforeDatasetDraw() (+113 more)

### Community 53 - "tables.js"
Cohesion: 0.13
Nodes (44): A(), ae(), B(), be(), C(), ce(), E(), ee() (+36 more)

### Community 54 - "ff"
Cohesion: 0.13
Nodes (20): ao(), append(), buildProps(), can(), Cc(), createCan(), createChain(), ff() (+12 more)

### Community 55 - "r"
Cohesion: 0.15
Nodes (43): _a(), ar(), c(), f(), d(), di(), g(), Hi() (+35 more)

### Community 56 - "nodeAt"
Cohesion: 0.10
Nodes (51): AS(), Bm(), cellsInRect(), colCount(), content(), createAndFill(), ct(), dS() (+43 more)

### Community 57 - "SubscriptionStatus"
Cohesion: 0.12
Nodes (3): BackedEnum, UnitEnum, SubscriptionStatus

### Community 58 - "Xt"
Cohesion: 0.12
Nodes (44): ae(), At(), bi(), bn(), ci(), cn(), ct(), de() (+36 more)

### Community 60 - "filament-right-click.js"
Cohesion: 0.11
Nodes (42): a(), ae(), b(), be(), C(), ce(), D(), de() (+34 more)

### Community 61 - "getContext"
Cohesion: 0.07
Nodes (52): acquireContext(), Ao(), bl(), buildTicks(), Ca(), calculateLabelRotation(), _calculatePadding(), ci() (+44 more)

### Community 62 - "next"
Cohesion: 0.08
Nodes (33): activeForPoint(), addActive(), addBlock(), addLineDeco(), Ar(), as(), blankContent(), boundChange() (+25 more)

### Community 63 - "Si"
Cohesion: 0.13
Nodes (41): ae(), At(), bi(), bn(), ci(), cn(), ct(), de() (+33 more)

### Community 64 - "PlatformSetting"
Cohesion: 0.07
Nodes (5): self, PlatformSetting, PlatformSettingSeeder, AuthGlassTest, PlatformSettingTest

### Community 65 - "ae"
Cohesion: 0.09
Nodes (34): ae(), Ao(), as(), B(), Kt(), cs(), Ee(), Ge() (+26 more)

### Community 66 - "selectOption"
Cohesion: 0.12
Nodes (39): addBadgesForSelectedOptions(), addSingleBadge(), addSingleSelectionDisplay(), closeDropdown(), constructor(), createBadgeElement(), createOptionElement(), createRemoveButton() (+31 more)

### Community 67 - "Filament\Schemas\Schema"
Cohesion: 0.05
Nodes (16): ManageHomeLanding, BackedEnum, UnitEnum, ManagePlatformPages, BackedEnum, UnitEnum, ManageLandingLayout, BackedEnum (+8 more)

### Community 68 - "CreateCashierOrder"
Cohesion: 0.11
Nodes (4): CreateCashierOrder, BackedEnum, UnitEnum, Width

### Community 69 - "ir"
Cohesion: 0.14
Nodes (30): ir(), at(), be(), ce(), Ct(), de(), Dt(), ee() (+22 more)

### Community 70 - "st"
Cohesion: 0.05
Nodes (48): ad(), applyStack(), br(), Di(), drawCaret(), _f(), first(), getCaretPosition() (+40 more)

### Community 71 - "slider.js"
Cohesion: 0.11
Nodes (32): ar(), Be(), Ce(), De(), _e(), Ee(), er(), Fe() (+24 more)

### Community 72 - "Restaurant"
Cohesion: 0.02
Nodes (71): ExpireStaleOperationsCommand, FinalizeCashierCommissionCommand, GenerateUpcomingInvoicesCommand, ProcessSubscriptionLifecycleCommand, SendCashierCommissionReminderCommand, analyticsDateFrom(), analyticsDateTo(), analyticsDayCount() (+63 more)

### Community 73 - "selectOption"
Cohesion: 0.15
Nodes (33): addSingleSelectionDisplay(), closeDropdown(), constructor(), createOptionElement(), deferPositionDropdown(), destroy(), filterOptions(), focusNextOption() (+25 more)

### Community 74 - "fn"
Cohesion: 0.13
Nodes (33): aa(), At(), ba(), cr(), da(), de(), dt(), ei() (+25 more)

### Community 75 - "i"
Cohesion: 0.05
Nodes (80): aa(), applyChanges(), balanced(), baseIndent(), baseIndentFor(), Bg(), bidiSpans(), blockAt() (+72 more)

### Community 78 - "static"
Cohesion: 0.03
Nodes (30): canForceDelete(), canRestore(), Action, trashPageAction(), FacilityResource, CreateFacility, ListFacilities, LandingTemplateResource (+22 more)

### Community 79 - "file-upload.js"
Cohesion: 0.05
Nodes (53): hc(), Bp(), c(), ca(), clickPercent(), constructor(), Cp(), da() (+45 more)

### Community 81 - "configure"
Cohesion: 0.06
Nodes (45): add(), _cachedScopes(), configure(), createResolver(), datasetAnimationScopeKeys(), datasetElementScopeKeys(), datasetScopeKeys(), get() (+37 more)

### Community 82 - "ce"
Cohesion: 0.09
Nodes (41): Ac(), bl(), Cc(), ce(), cl(), Dc(), Do(), Ec() (+33 more)

### Community 84 - "FonnteErrorMessage"
Cohesion: 0.11
Nodes (8): FonnteClient, FonnteErrorMessage, Throwable, Illuminate\Http\Client\ConnectionException, Illuminate\Http\Client\Response, PHPUnit\Framework\Attributes\DataProvider, RuntimeException, FonnteErrorMessageTest

### Community 89 - "Y"
Cohesion: 0.11
Nodes (22): at(), Bf(), determineDataLimits(), ef(), getMatchingVisibleMetas(), getMinMax(), _getOtherScale(), getUserBounds() (+14 more)

### Community 91 - "devDependencies"
Cohesion: 0.09
Nodes (21): apexcharts, axios, concurrently, laravel-vite-plugin, dependencies, apexcharts, devDependencies, axios (+13 more)

### Community 92 - "filament/app.js"
Cohesion: 0.13
Nodes (8): B(), close(), G(), init(), P(), setUpResizeObserver(), x(), Y()

### Community 94 - "fn"
Cohesion: 0.22
Nodes (18): An(), Ce(), ei(), fn(), Ft(), Ie(), Le(), ni() (+10 more)

### Community 95 - "E"
Cohesion: 0.05
Nodes (61): $a(), add(), af(), B(), bo(), bs(), ca(), _cachedScopes() (+53 more)

### Community 96 - "require"
Cohesion: 0.12
Nodes (16): require, barryvdh/laravel-dompdf, bezhansalleh/filament-shield, endroid/qr-code, filament/filament, hammadzafar05/filament-mobile-preset, ipatco/filament-profile, laravel/framework (+8 more)

### Community 97 - "scripts"
Cohesion: 0.12
Nodes (16): scripts, dev, post-autoload-dump, post-create-project-cmd, post-root-package-install, post-update-cmd, Composer\\Config::disableProcessTimeout, Illuminate\\Foundation\\ComposerScripts::postAutoloadDump (+8 more)

### Community 98 - "ExportFileResource"
Cohesion: 0.12
Nodes (6): GenerateReport, BackedEnum, UnitEnum, ExportFileResource, ListExportFiles, TrashExportFiles

### Community 99 - "color-picker.js"
Cohesion: 0.14
Nodes (3): style(), update(), [x]()

### Community 100 - "js/app.js"
Cohesion: 0.16
Nodes (6): applyTheme(), bindThemeToggle(), initReveal(), initTheme(), syncThemeToggleIcons(), toggleTheme()

### Community 101 - "TenantContext"
Cohesion: 0.07
Nodes (11): DiningTableResource, ManageDiningTables, Action, Closure, TrashDiningTables, MenuItemResource, ListMenuItems, TrashMenuItems (+3 more)

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

### Community 109 - "create"
Cohesion: 0.05
Nodes (99): addAll(), addDOM(), addElement(), addElementByRule(), addNodeMark(), addTextNode(), ag(), allowedMarks() (+91 more)

### Community 110 - "Mt"
Cohesion: 0.24
Nodes (11): apply(), fs(), go(), Hr(), T(), ir(), it(), Mt() (+3 more)

### Community 111 - "RestaurantCategory"
Cohesion: 0.06
Nodes (5): RestaurantCategory, RestaurantCategorySeeder, RestaurantDirectoryTest, RestaurantRegistrationStepperTest, TenantIsolationTest

### Community 112 - "A"
Cohesion: 0.07
Nodes (38): Ot(), A(), apply(), As(), chartOptionScopes(), _computeLabelSizes(), constructor(), cr() (+30 more)

### Community 113 - "SubscriptionAccess"
Cohesion: 0.06
Nodes (12): canCreate(), canDelete(), canDeleteAny(), canEdit(), canViewAny(), CmsBannerResource, ManageCmsBanners, TrashCmsBanners (+4 more)

### Community 114 - "Filament\Tables\Table"
Cohesion: 0.06
Nodes (11): CreateSubscriptionInvoice, ViewSubscriptionInvoice, SubscriptionInvoiceResource, ActivityResource, ListActivities, ViewActivity, ActivityInfolist, ActivitiesTable (+3 more)

### Community 115 - "actions/actions.js"
Cohesion: 0.44
Nodes (8): closeModal(), generateModalId(), getActionNestingIndexFromModalId(), init(), openModal(), rememberPreviouslyFocusedElement(), restorePreviouslyFocusedElement(), syncActionModals()

### Community 116 - "replace"
Cohesion: 0.07
Nodes (36): addToSet(), childString(), decompose(), decomposeLeft(), decomposeRight(), flushIOSKey(), FO(), getReplacement() (+28 more)

### Community 118 - "Landasan Produksi — Tahap 1 (Walk-In Operasional)"
Cohesion: 0.05
Nodes (37): 10. Aturan yang tidak boleh dilanggar, 11. Matriks skenario lapangan, 12. Yang sengaja tidak didukung (bukan gap, non-goal), 13. Tahap 2 (bukan sekarang), 14. Urutan bangun (agar cepat produksi), 1. Keputusan terkunci, 2. Modul tahap 1 vs ditunda, 3. Model domain (+29 more)

### Community 119 - "Guest"
Cohesion: 0.05
Nodes (34): Bentuk objek, CartItemResource, DELETE `/guest/cart/items/{cartItem}`, GET `/guest/cart`, GET `/guest/menu`, GET `/guest/orders`, GET `/guest/orders/{public_id}`, GET `/guest/review` (+26 more)

### Community 120 - "draw"
Cohesion: 0.09
Nodes (34): addElements(), bi(), bindEvents(), bindUserEvents(), buildOrUpdateScales(), _checkEventBindings(), clear(), _dataCheck() (+26 more)

### Community 121 - "2. Masalah di lapangan — dan apa yang sistem selesaikan"
Cohesion: 0.05
Nodes (41): 1. Cerita yang mungkin terasa familiar, 2.10 Struk kertas hilang, tamu minta dikirim WhatsApp, 2.11 Tampilan website restoran kaku atau tidak sesuai konsep resto, 2.12 Calon tamu ingin lihat menu lengkap sebelum datang ke resto, 2.13 Foto menu yang diupload staf ukurannya raksasa bikin web lemot, 2.14 Owner dan kasir ingin tahu performa hari ini secara instan, 2.15 Tak sengaja hapus menu atau meja saat jam sibuk, 2.16 Sulit ditemukan calon tamu baru di internet (+33 more)

### Community 122 - "Pe"
Cohesion: 0.12
Nodes (32): cd(), dd(), dt(), Ft(), gl(), _i(), Ie(), it() (+24 more)

### Community 123 - "require-dev"
Cohesion: 0.25
Nodes (8): require-dev, fakerphp/faker, laravel/pail, laravel/pint, laravel/sail, mockery/mockery, nunomaduro/collision, phpunit/phpunit

### Community 124 - "Filament\Resources\Pages\ManageRecords"
Cohesion: 0.06
Nodes (15): CmsGalleryImageResource, ManageCmsGalleryImages, TrashCmsGalleryImages, KdsStationResource, ManageKdsStations, Closure, TrashKdsStations, MenuCategoryResource (+7 more)

### Community 125 - "6. Katalog fitur"
Cohesion: 0.06
Nodes (33): 1. Latar belakang, 2. Rumusan masalah, 3.1 Tujuan umum, 3.2 Tujuan khusus, 3. Tujuan, 4.1 Bagi restoran (owner, kasir, dapur), 4.2 Bagi tamu, 4.3 Bagi pengelola platform (+25 more)

### Community 127 - "config"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

### Community 128 - "6. Katalog fitur"
Cohesion: 0.06
Nodes (32): 1. Latar belakang, 2. Rumusan masalah, 3.1 Tujuan umum, 3.2 Tujuan khusus, 3. Tujuan, 4.1 Bagi restoran (owner, kasir, dapur), 4.2 Bagi tamu, 4.3 Bagi pengelola platform (+24 more)

### Community 129 - "CashierShiftResource"
Cohesion: 0.11
Nodes (7): Login, CashierShiftResource, ListCashierShifts, ViewCashierShift, Filament\Auth\Pages\Login, Filament\Schemas\Components\Component, Illuminate\Contracts\Support\Htmlable

### Community 130 - "register-restaurant.blade.php"
Cohesion: 0.15
Nodes (12): applyColorPreset(, back, nextFromAccount, nextFromPlan, nextFromRestaurant, nextFromVisual, register, $set( (+4 more)

### Community 131 - "add-to-cart-modal.blade.php"
Cohesion: 0.29
Nodes (6): cancelPicking, confirmAdd, decrementPickingQty, incrementPickingQty, setVariant({{ $variant->id }}), toggleModifier({{ $modifier->id }})

### Community 132 - "S"
Cohesion: 0.10
Nodes (28): afterAutoSkip(), Bt(), buildLookupTable(), da(), drawTitle(), Ds(), Fs(), getBasePixel() (+20 more)

### Community 134 - "psr-4"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 135 - "extra"
Cohesion: 0.40
Nodes (5): dev-master, extra, branch-alias, laravel, dont-discover

### Community 136 - "logging.php"
Cohesion: 0.40
Nodes (4): Monolog\Handler\NullHandler, Monolog\Handler\StreamHandler, Monolog\Handler\SyslogUdpHandler, Monolog\Processor\PsrLogMessageProcessor

### Community 138 - "selectRecords"
Cohesion: 0.20
Nodes (18): areRecordsPartiallySelected(), areRecordsSelected(), areRecordsToggleable(), canSelectAllRecords(), deselectAllRecords(), deselectRecords(), getRecordsOnPage(), getSelectedRecordsCount() (+10 more)

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

### Community 157 - "Dashboard"
Cohesion: 0.10
Nodes (5): Dashboard, CreateRestaurant, EditRestaurant, RestaurantResource, Filament\Pages\Dashboard\Concerns\HasFiltersForm

### Community 158 - "cc"
Cohesion: 0.22
Nodes (11): attrs(), bi(), cc(), cO(), JQ(), m$(), Ow(), rc() (+3 more)

### Community 163 - "CmsMedia"
Cohesion: 0.04
Nodes (20): AnalyticsKpiWidget, AnalyticsPeriodSummaryWidget, AnalyticsRevenueBarWidget, AnalyticsSidebarWidget, AnalyticsTopMenuWidget, formatKpiDelta(), makeKpiCard(), paymentMixSummary() (+12 more)

### Community 164 - "SubscriptionWriteGuard"
Cohesion: 0.28
Nodes (3): BlockGraceMutations, SubscriptionWriteGuard, Livewire\ComponentHook

### Community 170 - "getDatasetMeta"
Cohesion: 0.11
Nodes (26): afterDatasetsUpdate(), An(), generateLabels(), getDatasetMeta(), getDataVisibility(), _getLegendItemAt(), getMaxBorderWidth(), _getSortedDatasetMetas() (+18 more)

### Community 172 - "KitchenDisplay"
Cohesion: 0.14
Nodes (4): KitchenDisplay, BackedEnum, UnitEnum, Width

### Community 173 - "filament-shield.php"
Cohesion: 0.29
Nodes (5): Dashboard, BezhanSalleh\FilamentShield\Resources\Roles\RoleResource, Filament\Pages\Dashboard, Filament\Widgets\AccountWidget, Filament\Widgets\FilamentInfoWidget

### Community 176 - "🎨 Spesifikasi Token & Class CSS yang Tersedia di `theme.css`"
Cohesion: 0.15
Nodes (12): 1. Kartu Kontainer Utama (`.vision-card`), 2. Kotak Ikon Gradien Neon (`.vision-icon-box`), 3. Kapsul Metrik / Sub-Kartu (`.vision-pill-card` atau `.vision-pill-dark`), 4. Kartu Filter (`.vision-filter-card`), 5. Tabel Data Kaca (`.vision-table-card`), 6. Form Section & Skema Input Kaca (`.vision-card` pada `Section::make`), 7. Kartu Statistik Global (`StatsOverviewWidget` / `Stat::make`), ⚙️ Aturan Wajib untuk AI Developer (+4 more)

### Community 180 - "dx"
Cohesion: 0.14
Nodes (23): Ei(), Aa(), Bi(), ca(), da(), fa(), Gr(), ki() (+15 more)

### Community 185 - "AppServiceProvider.php"
Cohesion: 0.07
Nodes (28): CommissionReconciliation, BackedEnum, UnitEnum, Width, SoftDeleteTrashPage, AppServiceProvider, Filament\Actions\ActionGroup, Filament\Actions\DeleteBulkAction (+20 more)

### Community 193 - "restaurant-menu-catalog.blade.php"
Cohesion: 0.25
Nodes (7): landing.templates.foodie.sections.footer, landing.templates.foodie.sections.header, landing.templates.glassmorphism.partials.background, landing.templates.glassmorphism.sections.footer, landing.templates.glassmorphism.sections.header, partials.customer.landing-footer, partials.customer.landing-header

### Community 295 - "3. Detail Implementasi Perbaikan Keamanan"
Cohesion: 0.17
Nodes (11): 1. Ringkasan Eksekutif (Executive Summary), 2. Matriks Temuan & Status Perbaikan (Findings & Remediation Matrix), 3. Detail Implementasi Perbaikan Keamanan, 4. Hasil Verifikasi Pengujian Otomatis, A. Proteksi `qr_secret` pada Model (`SEC-01`), B. Middleware HTTP Security Headers (`SEC-02`), C. Pengetatan CORS & Session Cookie (`SEC-03` & `SEC-05`), D. Sanitasi File Upload (`SEC-06`) (+3 more)

### Community 296 - "foodie/show.blade.php"
Cohesion: 0.33
Nodes (5): landing.templates.foodie.sections., landing.templates.foodie.sections.hero, landing.sections., landing.templates.foodie.sections.footer, landing.templates.foodie.sections.header

### Community 297 - "Facility"
Cohesion: 0.12
Nodes (5): ManageCmsProfile, BackedEnum, UnitEnum, Facility, FacilitySeeder

### Community 301 - "classic/show.blade.php"
Cohesion: 0.50
Nodes (3): landing.sections., partials.customer.landing-footer, partials.customer.landing-header

### Community 304 - "FounderStatsWidget.php"
Cohesion: 0.50
Nodes (3): FounderStatsWidget, Filament\Widgets\StatsOverviewWidget, Filament\Widgets\StatsOverviewWidget\Stat

### Community 306 - "_each"
Cohesion: 0.12
Nodes (17): addControllers(), addPlugins(), addScales(), _each(), _exec(), _getRegistryForType(), invalidate(), isForType() (+9 more)

### Community 307 - "ManageSoundNotifications"
Cohesion: 0.15
Nodes (4): ManageSoundNotifications, BackedEnum, UnitEnum, ManageSoundNotificationsTest

### Community 318 - "_notify"
Cohesion: 0.20
Nodes (14): active(), _animateOptions(), cancel(), _createAnimations(), _createDescriptors(), _descriptors(), _notify(), _notifyStateChanges() (+6 more)

### Community 320 - "ModifierGroupResource"
Cohesion: 0.21
Nodes (4): ModifierGroupResource, ManageModifierGroups, Closure, TrashModifierGroups

### Community 321 - "ManageBillingAccount"
Cohesion: 0.24
Nodes (3): ManageBillingAccount, BackedEnum, UnitEnum

### Community 322 - "glassmorphism/show.blade.php"
Cohesion: 0.29
Nodes (6): landing.templates.glassmorphism.sections., landing.templates.glassmorphism.sections.hero, landing.sections., landing.templates.glassmorphism.partials.background, landing.templates.glassmorphism.sections.footer, landing.templates.glassmorphism.sections.header

### Community 324 - "CashierShift"
Cohesion: 0.08
Nodes (5): CashierShiftPrintController, CashierShift, CashierShiftService, ReceiptLogo, ReceiptLogoTest

### Community 327 - "addSingleBadge"
Cohesion: 0.33
Nodes (6): addBadgesForSelectedOptions(), addSingleBadge(), createBadgeElement(), createRemoveButton(), getLabelForSingleSelection(), getSelectedOptionLabel()

### Community 347 - "CmsFaqResource"
Cohesion: 0.27
Nodes (3): CmsFaqResource, ManageCmsFaqs, TrashCmsFaqs

### Community 348 - "st"
Cohesion: 0.21
Nodes (12): [g](), _freeze(), getAllExtensions(), ae(), A(), E(), lt(), ot() (+4 more)

### Community 349 - "RefreshesAnalyticsChart.php"
Cohesion: 0.36
Nodes (8): generateChartDataChecksum(), getCachedChartData(), getChartData(), mountRefreshesAnalyticsChart(), refreshAnalyticsChartData(), rendering(), updateChartData(), Livewire\Attributes\Locked

### Community 355 - "Filament\Panel"
Cohesion: 0.33
Nodes (4): FilamentProfilePlugin, Filament\Panel, Ipatco\FilamentProfile\FilamentProfilePlugin, Ipatco\FilamentProfile\Widgets\AccountWidget

### Community 356 - "ut"
Cohesion: 0.17
Nodes (16): Ae(), Bt(), et(), Ft(), fe(), ft(), Jt(), le() (+8 more)

### Community 361 - "addEventListener"
Cohesion: 0.33
Nodes (7): addEventListener(), bindResponsiveEvents(), fu(), isAttached(), nr(), removeEventListener(), Ua()

### Community 362 - "Vf"
Cohesion: 0.33
Nodes (7): contains(), gi(), splitAt(), toISOTime(), toMillis(), Vf(), ye()

## Knowledge Gaps
- **342 isolated node(s):** `$schema`, `name`, `type`, `description`, `laravel` (+337 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **42 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Wi()` connect `Ye` to `code-editor.js`, `rich-editor.js`, `constructor`, `components/select.js`, `facet`, `columns/select.js`?**
  _High betweenness centrality (0.028) - this node is a cross-community bridge._
- **Why does `_s()` connect `components/chart.js` to `rich-editor.js`, `o`?**
  _High betweenness centrality (0.025) - this node is a cross-community bridge._
- **Why does `Restaurant` connect `Restaurant` to `Illuminate\Database\Eloquent\Model`, `TestCase`, `MenuItem`, `User`, `Order`, `DiningTable`, `AdminPanelProvider.php`, `OrderResource`, `ExportFile`, `RegisterRestaurant`, `Dashboard`, `CashierFilamentActionsTest`, `CmsMedia`, `BackedEnum`, `Illuminate\Http\Request`, `Illuminate\Database\Eloquent\Builder`, `Facility`, `Filament\Resources\Pages\EditRecord`, `FounderStatsWidget.php`, `AppServiceProvider.php`, `SubscriptionStatus`, `PlatformSetting`, `Filament\Schemas\Schema`, `CashierShift`, `static`, `SubscriptionInvoice`, `LandingLayout`, `SubscriptionInvoiceService`, `ExportFileResource`, `GraceReadOnlyTest`, `TenantContext`, `OrderReceiptPrintTest`, `RestaurantCategory`, `SubscriptionAccess`, `Filament\Tables\Table`?**
  _High betweenness centrality (0.020) - this node is a cross-community bridge._
- **What connects `$schema`, `name`, `type` to the rest of the system?**
  _342 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `stat/chart.js` be split into smaller, more focused modules?**
  _Cohesion score 0.024135681669928244 - nodes in this community are weakly interconnected._
- **Should `components/chart.js` be split into smaller, more focused modules?**
  _Cohesion score 0.01160448290537665 - nodes in this community are weakly interconnected._
- **Should `code-editor.js` be split into smaller, more focused modules?**
  _Cohesion score 0.008813949032381682 - nodes in this community are weakly interconnected._