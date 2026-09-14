# Graph Report - WALK-IN-RESTO  (2026-09-14)

## Corpus Check
- 687 files · ~344,506 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 8927 nodes · 27552 edges · 368 communities (322 shown, 46 thin omitted)
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
- le
- CashierMenuCatalog
- CmsMedia
- slice
- User
- DiningTable
- _update
- Order
- advance
- r
- get
- updateElements
- Visit
- Illuminate\Foundation\Http\FormRequest
- AdminPanelProvider.php
- OrderResource
- ExportFile
- support.js
- vd
- toString
- of
- columns/select.js
- .slice
- reduce
- echo.js
- resolve
- g$
- iy
- BackedEnum
- nodesBetween
- prop
- GuestContext
- Customer
- e
- Ye
- EditProfile
- Dashboard
- notifications.js
- markdown-editor.js
- LandingTemplate
- te
- Cn
- components/select.js
- o
- tables.js
- sameMarkup
- r
- parse
- SubscriptionStatus
- Xt
- 2026_08_20_010000_add_floor_layout_to_tables_table.php
- filament-right-click.js
- fn
- t
- Si
- PlatformSetting
- ae
- selectOption
- Filament\Schemas\Schema
- TenantContext
- ir
- toString
- slider.js
- Restaurant
- selectOption
- fn
- facet
- InvoicePaymentTest
- static
- file-upload.js
- Sl
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
- CashierOrderPreview
- composer.json
- order-today-stats-widget.blade.php
- GeoDistance
- date-time-picker.js
- 🚀 3. Langkah-Langkah Menambahkan Template Baru (*Workflow*)
- from
- Mt
- ManageLandingLayout
- RestaurantDirectory
- SubscriptionAccess
- Filament\Tables\Table
- actions/actions.js
- .count
- schemas.js
- Landasan Produksi — Tahap 1 (Walk-In Operasional)
- Guest
- .panel
- 2. Masalah di lapangan — dan apa yang sistem selesaikan
- SoftDeleteTrashTest
- require-dev
- SoftDeleteTrashPage
- 6. Katalog fitur
- config
- 6. Katalog fitur
- CashierShiftResource
- register-restaurant.blade.php
- add-to-cart-modal.blade.php
- rt
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
- Illuminate\Support\Facades\DB
- closeSimpleModeModal
- Illuminate\Support\Facades\Schema
- sl
- cc
- Illuminate\Database\Schema\Blueprint
- CashierFilamentActionsTest
- RendersAnalyticsDashboard.php
- SubscriptionWriteGuard
- addElementByRule
- ProfileInformationForm.php
- filament-shield.php
- 🎨 Spesifikasi Token & Class CSS yang Tersedia di `theme.css`
- n
- Illuminate\Database\Eloquent\Builder
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
- AnalyticsSidebarWidget
- classic/show.blade.php
- WelcomeBannerWidget
- FounderStatsWidget.php
- yl
- clickPercent
- rules/graphify.md
- workflows/graphify.md
- Illuminate\Console\Command
- et
- c
- glassmorphism/show.blade.php
- CashierShift
- addSingleBadge
- SubscriptionInvoice
- glassmorphism-background.blade.php
- N
- st
- RefreshesAnalyticsChart.php
- dropdown.blade.php
- GraceReadOnlyTest
- Filament\Panel
- Ae
- ExcelExporter

## God Nodes (most connected - your core abstractions)
1. `Restaurant` - 317 edges
2. `User` - 296 edges
3. `TestCase` - 167 edges
4. `constructor()` - 152 edges
5. `Order` - 151 edges
6. `update()` - 148 edges
7. `MenuItem` - 108 edges
8. `PlatformSetting` - 105 edges
9. `resolve()` - 94 edges
10. `y()` - 93 edges

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

## Communities (368 total, 46 thin omitted)

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
Nodes (212): aa(), add(), addExtensions(), addHackNode(), addNode(), addTextblockHacks(), an(), applyAspectRatio() (+204 more)

### Community 4 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.02
Nodes (48): CashierShiftMovement, CmsBanner, LogOptions, CmsFaq, LogOptions, CmsGalleryImage, CmsProfile, LogOptions (+40 more)

### Community 5 - "y"
Cohesion: 0.13
Nodes (83): at(), b(), Be(), $c(), X(), ca(), me(), Cr() (+75 more)

### Community 6 - "TestCase"
Cohesion: 0.03
Nodes (57): CashierOrderService, OrderPaymentService, BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles, Bityukov\CommandCenter\Filament\Pages\Commands, Bityukov\CommandCenter\Filament\Pages\History, RolePermissionSeeder, Filament\Facades\Filament, Illuminate\Database\QueryException (+49 more)

### Community 7 - "constructor"
Cohesion: 0.03
Nodes (144): add(), addChunk(), addEventListener(), addInfoPane(), addInner(), addWindowListeners(), adjust(), al() (+136 more)

### Community 8 - "le"
Cohesion: 0.23
Nodes (13): De(), Ee(), Fl(), le(), mm(), pe(), q(), qe() (+5 more)

### Community 10 - "CmsMedia"
Cohesion: 0.03
Nodes (39): paymentMixSummary(), RestaurantMenuController, ApplyPlatformBrandTheme, ApplyRestaurantPanelTheme, EnsureApiGuestVisit, EnsureGuestVisit, EnsureRestaurantOperations, EnsureTenantSubscription (+31 more)

### Community 11 - "slice"
Cohesion: 0.05
Nodes (128): addElement(), Ah(), balanced(), baseIndentFor(), be(), Bg(), a(), blockAt() (+120 more)

### Community 12 - "User"
Cohesion: 0.03
Nodes (24): Role, LogOptions, User, RolePolicy, UserPolicy, PermissionCheck, Filament\Models\Contracts\FilamentUser, Filament\Models\Contracts\HasTenants (+16 more)

### Community 13 - "DiningTable"
Cohesion: 0.05
Nodes (8): DiningTable, LogOptions, TableFloorPlan, TableQrToken, KitchenDisplayPageTest, StaleOperationsServiceTest, TableFloorPlanTest, TableOpsServiceTest

### Community 14 - "_update"
Cohesion: 0.03
Nodes (123): addBox(), addElements(), afterBuildTicks(), afterCalculateLabelRotation(), afterDataLimits(), afterDatasetsUpdate(), afterDraw(), afterFit() (+115 more)

### Community 15 - "Order"
Cohesion: 0.03
Nodes (18): OrderReceiptDownloadController, OrderReceiptPrintController, SendWhatsappReceiptJob, Order, OrderItem, OrderReceipt, WhatsappMessage, KdsItemService (+10 more)

### Community 16 - "advance"
Cohesion: 0.05
Nodes (65): addChild(), addGaps(), addLeafElement(), addNode(), advance(), ATXHeading(), blank(), break() (+57 more)

### Community 17 - "r"
Cohesion: 0.05
Nodes (129): addNodeView(), addProseMirrorPlugins(), af(), au(), bl(), buildProps(), by(), c1() (+121 more)

### Community 18 - "get"
Cohesion: 0.03
Nodes (108): addBlockWidget(), addBreak(), addComposition(), addDelimiter(), addInlineWidget(), addLine(), addLineStart(), addLineStartIfNotCovered() (+100 more)

### Community 19 - "updateElements"
Cohesion: 0.06
Nodes (53): addEventListener(), applyStack(), bindResponsiveEvents(), _calculateBarIndexPixels(), _calculateBarValuePixels(), calculateCircumference(), _circumference(), _computeAngle() (+45 more)

### Community 20 - "Visit"
Cohesion: 0.03
Nodes (19): Payment, Visit, VisitDevice, GuestCheckoutService, MenuModifierService, PaymentProofService, StaleOperationsService, TableOpsService (+11 more)

### Community 21 - "Illuminate\Foundation\Http\FormRequest"
Cohesion: 0.08
Nodes (8): AddCartItemRequest, CheckoutRequest, ClaimTableRequest, JoinTableRequest, StorePaymentProofRequest, StoreRestaurantReviewRequest, UpdateCartItemRequest, Illuminate\Foundation\Http\FormRequest

### Community 22 - "AdminPanelProvider.php"
Cohesion: 0.16
Nodes (18): BezhanSalleh\FilamentShield\FilamentShieldPlugin, Bityukov\CommandCenter\Filament\CommandCenterPlugin, Filament\Http\Middleware\Authenticate, Filament\Http\Middleware\AuthenticateSession, Filament\Http\Middleware\DisableBladeIconComponents, Filament\Http\Middleware\DispatchServingFilamentEvent, Filament\Navigation\NavigationGroup, Filament\Support\Colors\Color (+10 more)

### Community 23 - "OrderResource"
Cohesion: 0.08
Nodes (9): OrderResource, ListOrders, ViewOrder, OrderTodayStatsWidget, Carbon\Carbon, Filament\Infolists\Components\ImageEntry, Filament\Infolists\Components\RepeatableEntry, Filament\Tables\Filters\Filter (+1 more)

### Community 24 - "ExportFile"
Cohesion: 0.05
Nodes (16): PdfExporter, CleanupOldExportFilesJob, ExportReportJob, ExportFile, ExportFileObserver, ExportFilePolicy, ExportFinishedNotifier, ReportExportDispatcher (+8 more)

### Community 25 - "support.js"
Cohesion: 0.05
Nodes (75): acquireScrollLock(), ai(), e(), Bi(), br(), Bt(), ca(), close() (+67 more)

### Community 26 - "vd"
Cohesion: 0.09
Nodes (71): _a(), Ae(), ar(), as(), ue(), u(), ci(), r() (+63 more)

### Community 27 - "toString"
Cohesion: 0.07
Nodes (38): addToSet(), bd(), between(), Bh(), childString(), clearDelayedAndroidKey(), d0(), De() (+30 more)

### Community 28 - "of"
Cohesion: 0.04
Nodes (70): active(), apply(), B(), baseTheme(), blur(), bu(), checkAsyncSchedule(), define() (+62 more)

### Community 29 - "columns/select.js"
Cohesion: 0.06
Nodes (57): A(), An(), applyDisabledState(), b(), Bt(), Ce(), D(), disable() (+49 more)

### Community 30 - ".slice"
Cohesion: 0.05
Nodes (67): accepts(), addInner(), addMaps(), addStep(), addTransform(), appendMap(), appendMapping(), appendMappingInverted() (+59 more)

### Community 31 - "reduce"
Cohesion: 0.08
Nodes (46): addActions(), advanceFully(), advanceStack(), allActions(), c0(), canShift(), close(), deadEnd() (+38 more)

### Community 32 - "echo.js"
Cohesion: 0.05
Nodes (49): a(), ar(), b(), Be(), Ce(), cr(), d(), De() (+41 more)

### Community 33 - "resolve"
Cohesion: 0.05
Nodes (136): Ad(), addKeyboardShortcuts(), after(), al(), allowsMarks(), AS(), ay(), before() (+128 more)

### Community 34 - "g$"
Cohesion: 0.03
Nodes (95): acceptToken(), allows(), aO(), ch(), charCategorizer(), childAfter(), childBefore(), cO() (+87 more)

### Community 35 - "iy"
Cohesion: 0.27
Nodes (10): gr(), gu(), iy(), Ln(), oy(), pasteHTML(), pasteText(), replaceSelection() (+2 more)

### Community 36 - "BackedEnum"
Cohesion: 0.12
Nodes (48): canForceDelete(), canRestore(), getRecordRouteBindingEloquentQuery(), CustomerAnalytics, BackedEnum, Filament\Actions\Action, Filament\Actions\DeleteAction, Filament\Actions\EditAction (+40 more)

### Community 37 - "nodesBetween"
Cohesion: 0.08
Nodes (37): _0(), addGlobalAttributes(), childAfter(), childBefore(), dn(), Er(), excludes(), extendNodeSchema() (+29 more)

### Community 38 - "prop"
Cohesion: 0.06
Nodes (58): AQ(), atLastNode(), au(), child(), cursor(), cursorAt(), dX(), enter() (+50 more)

### Community 39 - "GuestContext"
Cohesion: 0.04
Nodes (29): CartController, CheckoutController, MenuController, OrderController, ReviewController, SessionController, TableController, VisitController (+21 more)

### Community 40 - "Customer"
Cohesion: 0.09
Nodes (3): Customer, CustomerCrmService, CustomerCrmLoyaltyTest

### Community 41 - "e"
Cohesion: 0.05
Nodes (74): addCommands(), addInputRules(), addMark(), addNodeMark(), addPasteRules(), addStoredMark(), Ah(), Ax() (+66 more)

### Community 42 - "Ye"
Cohesion: 0.07
Nodes (53): Rd(), $a(), ak(), at(), bk(), c(), bp(), bt() (+45 more)

### Community 44 - "Dashboard"
Cohesion: 0.06
Nodes (10): EditFacility, EditSubscriptionPlan, ListSubscriptionPlans, SubscriptionPlanResource, EditTenant, Dashboard, EditMenuItem, EditRestaurantCategory (+2 more)

### Community 45 - "notifications.js"
Cohesion: 0.06
Nodes (31): actions(), button(), c(), close(), configureAnimations(), configureTransitions(), constructor(), danger() (+23 more)

### Community 46 - "markdown-editor.js"
Cohesion: 0.05
Nodes (78): ad(), af(), ai(), al(), Ba(), bc(), bf(), bo() (+70 more)

### Community 47 - "LandingTemplate"
Cohesion: 0.18
Nodes (5): TemplateRadioPicker, LandingTemplate, LogOptions, LandingTemplateSeeder, Filament\Forms\Components\Field

### Community 48 - "te"
Cohesion: 0.04
Nodes (11): Bn(), br(), Id(), ji(), qd(), qi(), Ri(), te() (+3 more)

### Community 49 - "Cn"
Cohesion: 0.13
Nodes (46): Cn(), b(), Be(), Ce(), De(), dn(), _e(), Fe() (+38 more)

### Community 51 - "components/select.js"
Cohesion: 0.08
Nodes (38): A(), applyDisabledState(), b(), Bt(), D(), disable(), E(), en() (+30 more)

### Community 52 - "o"
Cohesion: 0.02
Nodes (219): acquireContext(), adjustHitBoxes(), ah(), Ao(), apply(), ar(), aspectRatio(), au() (+211 more)

### Community 53 - "tables.js"
Cohesion: 0.13
Nodes (44): A(), ae(), B(), be(), C(), ce(), E(), ee() (+36 more)

### Community 54 - "sameMarkup"
Cohesion: 0.19
Nodes (14): ao(), append(), Cc(), findDiffEnd(), findDiffStart(), fromArray(), hasMarkup(), Mc() (+6 more)

### Community 55 - "r"
Cohesion: 0.15
Nodes (43): _a(), ar(), c(), f(), d(), di(), g(), Hi() (+35 more)

### Community 56 - "parse"
Cohesion: 0.12
Nodes (23): Bm(), Dc(), defaultType(), Dm(), eat(), err(), getJSON(), hasRequiredAttrs() (+15 more)

### Community 57 - "SubscriptionStatus"
Cohesion: 0.12
Nodes (3): BackedEnum, UnitEnum, SubscriptionStatus

### Community 58 - "Xt"
Cohesion: 0.12
Nodes (44): ae(), At(), bi(), bn(), ci(), cn(), ct(), de() (+36 more)

### Community 60 - "filament-right-click.js"
Cohesion: 0.11
Nodes (42): a(), ae(), b(), be(), C(), ce(), D(), de() (+34 more)

### Community 61 - "fn"
Cohesion: 0.14
Nodes (21): themeClasses(), Ck(), fn(), Gh(), ip(), Ja(), Jh(), Ji() (+13 more)

### Community 62 - "t"
Cohesion: 0.07
Nodes (42): a$(), activeForPoint(), addBlock(), addLineDeco(), b1(), blankContent(), boundChange(), commit() (+34 more)

### Community 63 - "Si"
Cohesion: 0.13
Nodes (41): ae(), At(), bi(), bn(), ci(), cn(), ct(), de() (+33 more)

### Community 64 - "PlatformSetting"
Cohesion: 0.03
Nodes (22): ManageSoundNotifications, BackedEnum, UnitEnum, PlatformPageController, RestaurantLandingController, RestaurantMenuCatalog, self, PlatformSetting (+14 more)

### Community 65 - "ae"
Cohesion: 0.09
Nodes (34): ae(), Ao(), as(), B(), Kt(), cs(), Ee(), Ge() (+26 more)

### Community 66 - "selectOption"
Cohesion: 0.12
Nodes (39): addBadgesForSelectedOptions(), addSingleBadge(), addSingleSelectionDisplay(), closeDropdown(), constructor(), createBadgeElement(), createOptionElement(), createRemoveButton() (+31 more)

### Community 67 - "Filament\Schemas\Schema"
Cohesion: 0.04
Nodes (19): ManageBillingAccount, BackedEnum, UnitEnum, ManageHomeLanding, BackedEnum, UnitEnum, ManagePlatformPages, BackedEnum (+11 more)

### Community 68 - "TenantContext"
Cohesion: 0.05
Nodes (14): CreateCashierOrder, BackedEnum, UnitEnum, DiningTableResource, ManageDiningTables, Action, Closure, TrashDiningTables (+6 more)

### Community 69 - "ir"
Cohesion: 0.13
Nodes (33): De(), Ft(), ir(), ce(), de(), Dt(), Et(), fe() (+25 more)

### Community 70 - "toString"
Cohesion: 0.14
Nodes (20): Bc(), check(), checkAttrs(), endIndex(), getObj(), hasProtocol(), $i(), Ra() (+12 more)

### Community 71 - "slider.js"
Cohesion: 0.12
Nodes (31): ar(), Be(), Ce(), _e(), Ee(), er(), Fe(), G() (+23 more)

### Community 72 - "Restaurant"
Cohesion: 0.02
Nodes (46): analyticsDateFrom(), analyticsDateTo(), analyticsDayCount(), analyticsRangeLabel(), analyticsSnapshot(), canViewAnalytics(), normalizedAnalyticsDateRange(), Carbon (+38 more)

### Community 73 - "selectOption"
Cohesion: 0.15
Nodes (33): addSingleSelectionDisplay(), closeDropdown(), constructor(), createOptionElement(), deferPositionDropdown(), destroy(), filterOptions(), focusNextOption() (+25 more)

### Community 74 - "fn"
Cohesion: 0.13
Nodes (33): aa(), At(), ba(), cr(), da(), de(), dt(), ei() (+25 more)

### Community 75 - "facet"
Cohesion: 0.04
Nodes (64): accept(), activateHover(), applyTransaction(), asSingle(), baseDirAt(), bidiIn(), bidiSpans(), bidiSpansAt() (+56 more)

### Community 78 - "static"
Cohesion: 0.02
Nodes (33): Action, trashPageAction(), FacilityResource, CreateFacility, ListFacilities, LandingTemplateResource, CreateLandingTemplate, EditLandingTemplate (+25 more)

### Community 79 - "file-upload.js"
Cohesion: 0.07
Nodes (14): hc(), constructor(), define(), dm(), _freeze(), getAllExtensions(), getExtension(), _getTestState() (+6 more)

### Community 81 - "Sl"
Cohesion: 0.08
Nodes (32): addAttributes(), addOptions(), domAtPos(), element(), Gg(), iw(), jd(), kl() (+24 more)

### Community 82 - "ce"
Cohesion: 0.06
Nodes (55): Ac(), An(), ao(), bl(), Cc(), cd(), ee(), ce() (+47 more)

### Community 84 - "FonnteErrorMessage"
Cohesion: 0.11
Nodes (8): FonnteClient, FonnteErrorMessage, Throwable, Illuminate\Http\Client\ConnectionException, Illuminate\Http\Client\Response, PHPUnit\Framework\Attributes\DataProvider, RuntimeException, FonnteErrorMessageTest

### Community 89 - "Y"
Cohesion: 0.05
Nodes (54): active(), af(), afterAutoSkip(), _animateOptions(), at(), Bf(), br(), buildLookupTable() (+46 more)

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
Nodes (64): $a(), aa(), add(), B(), bo(), bs(), ca(), _cachedScopes() (+56 more)

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

### Community 101 - "CashierOrderPreview"
Cohesion: 0.24
Nodes (3): CashierOrderPreview, CheckoutTotals, CashierOrderPreviewTest

### Community 103 - "composer.json"
Cohesion: 0.14
Nodes (13): autoload-dev, psr-4, description, keywords, license, minimum-stability, name, prefer-stable (+5 more)

### Community 106 - "GeoDistance"
Cohesion: 0.20
Nodes (4): GeoDistance, PHPUnit\Framework\TestCase, ExampleTest, GeoDistanceTest

### Community 107 - "date-time-picker.js"
Cohesion: 0.29
Nodes (7): d(), e(), i(), m(), r(), s(), t()

### Community 108 - "🚀 3. Langkah-Langkah Menambahkan Template Baru (*Workflow*)"
Cohesion: 0.12
Nodes (15): 📌 1. Prinsip Utama & Aturan Wajib (Golden Rules), 🏗️ 2. Arsitektur 10 Core Section CMS (1:1 Mapping), 🚀 3. Langkah-Langkah Menambahkan Template Baru (*Workflow*), 📊 4. Variabel yang Disediakan oleh `LandingPageDataService`, 💎 5. Pelajaran Desain & Best Practices UI/UX (*Key Learnings*), A. Larangan Keras Menggunakan Emoji Unicode (Gunakan SVG Heroicons), B. Arsitektur Layering 3D & Background Tembus Pandang (*Stacking Context*), C. Resep Glassmorphism iOS yang Nyata (*True Frosted Glass*) (+7 more)

### Community 109 - "from"
Cohesion: 0.09
Nodes (56): Ac(), ag(), bu(), _c(), canAppend(), canReplace(), canReplaceWith(), close() (+48 more)

### Community 110 - "Mt"
Cohesion: 0.24
Nodes (11): apply(), fs(), go(), Hr(), T(), ir(), it(), Mt() (+3 more)

### Community 111 - "ManageLandingLayout"
Cohesion: 0.20
Nodes (4): ManageLandingLayout, BackedEnum, Closure, UnitEnum

### Community 113 - "SubscriptionAccess"
Cohesion: 0.04
Nodes (20): canCreate(), canDelete(), canDeleteAny(), canEdit(), canViewAny(), MenuItemResource, CreateMenuItem, ListMenuItems (+12 more)

### Community 114 - "Filament\Tables\Table"
Cohesion: 0.07
Nodes (11): ActivityResource, ListActivities, ViewActivity, ActivityInfolist, ActivitiesTable, TableRightClick, Activity, ActivityPresenter (+3 more)

### Community 115 - "actions/actions.js"
Cohesion: 0.44
Nodes (8): closeModal(), generateModalId(), getActionNestingIndexFromModalId(), init(), openModal(), rememberPreviouslyFocusedElement(), restorePreviouslyFocusedElement(), syncActionModals()

### Community 118 - "Landasan Produksi — Tahap 1 (Walk-In Operasional)"
Cohesion: 0.05
Nodes (37): 10. Aturan yang tidak boleh dilanggar, 11. Matriks skenario lapangan, 12. Yang sengaja tidak didukung (bukan gap, non-goal), 13. Tahap 2 (bukan sekarang), 14. Urutan bangun (agar cepat produksi), 1. Keputusan terkunci, 2. Modul tahap 1 vs ditunda, 3. Model domain (+29 more)

### Community 119 - "Guest"
Cohesion: 0.05
Nodes (34): Bentuk objek, CartItemResource, DELETE `/guest/cart/items/{cartItem}`, GET `/guest/cart`, GET `/guest/menu`, GET `/guest/orders`, GET `/guest/orders/{public_id}`, GET `/guest/review` (+26 more)

### Community 120 - ".panel"
Cohesion: 0.22
Nodes (5): AdminPanelProvider, FounderPanelProvider, AuthGlass, Filament\PanelProvider, Illuminate\Support\HtmlString

### Community 121 - "2. Masalah di lapangan — dan apa yang sistem selesaikan"
Cohesion: 0.05
Nodes (41): 1. Cerita yang mungkin terasa familiar, 2.10 Struk kertas hilang, tamu minta dikirim WhatsApp, 2.11 Tampilan website restoran kaku atau tidak sesuai konsep resto, 2.12 Calon tamu ingin lihat menu lengkap sebelum datang ke resto, 2.13 Foto menu yang diupload staf ukurannya raksasa bikin web lemot, 2.14 Owner dan kasir ingin tahu performa hari ini secara instan, 2.15 Tak sengaja hapus menu atau meja saat jam sibuk, 2.16 Sulit ditemukan calon tamu baru di internet (+33 more)

### Community 123 - "require-dev"
Cohesion: 0.25
Nodes (8): require-dev, fakerphp/faker, laravel/pail, laravel/pint, laravel/sail, mockery/mockery, nunomaduro/collision, phpunit/phpunit

### Community 124 - "SoftDeleteTrashPage"
Cohesion: 0.04
Nodes (26): SoftDeleteTrashPage, CmsBannerResource, ManageCmsBanners, TrashCmsBanners, CmsFaqResource, ManageCmsFaqs, TrashCmsFaqs, CmsGalleryImageResource (+18 more)

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
Cohesion: 0.12
Nodes (6): Login, CashierShiftResource, ViewCashierShift, Filament\Auth\Pages\Login, Filament\Schemas\Components\Component, Illuminate\Contracts\Support\Htmlable

### Community 130 - "register-restaurant.blade.php"
Cohesion: 0.15
Nodes (12): applyColorPreset(, back, nextFromAccount, nextFromPlan, nextFromRestaurant, nextFromVisual, register, $set( (+4 more)

### Community 131 - "add-to-cart-modal.blade.php"
Cohesion: 0.29
Nodes (6): cancelPicking, confirmAdd, decrementPickingQty, incrementPickingQty, setVariant({{ $variant->id }}), toggleModifier({{ $modifier->id }})

### Community 132 - "rt"
Cohesion: 0.29
Nodes (8): ca(), Dp(), _e(), Ea(), nm(), rt(), xt(), ya()

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

### Community 153 - "Illuminate\Support\Facades\DB"
Cohesion: 0.04
Nodes (12): RegisterRestaurant, SubscriptionPlan, ImageOptimizer, ReservedSlugs, SubscriptionPlanSeeder, Illuminate\Support\Facades\Auth, Illuminate\Support\Facades\DB, Illuminate\Validation\Rule (+4 more)

### Community 157 - "sl"
Cohesion: 0.33
Nodes (7): Cp(), da(), Gp(), kp(), Np(), sl(), Vp()

### Community 158 - "cc"
Cohesion: 0.12
Nodes (18): attrs(), AX(), bi(), cc(), combine(), configure(), extend(), gQ() (+10 more)

### Community 163 - "RendersAnalyticsDashboard.php"
Cohesion: 0.14
Nodes (10): AnalyticsKpiWidget, AnalyticsPeriodSummaryWidget, AnalyticsRevenueBarWidget, AnalyticsTopMenuWidget, analyticsTheme(), formatKpiDelta(), makeKpiCard(), todayKpiCards() (+2 more)

### Community 164 - "SubscriptionWriteGuard"
Cohesion: 0.28
Nodes (3): BlockGraceMutations, SubscriptionWriteGuard, Livewire\ComponentHook

### Community 170 - "addElementByRule"
Cohesion: 0.12
Nodes (28): addAll(), addDOM(), addElement(), addElementByRule(), addTextNode(), addToSet(), allowedMarks(), allowsMarkType() (+20 more)

### Community 172 - "ProfileInformationForm.php"
Cohesion: 0.40
Nodes (3): ProfileInformationForm, Ipatco\FilamentProfile\Forms\ProfileInformationForm, Ipatco\FilamentProfile\Pages\EditProfile

### Community 173 - "filament-shield.php"
Cohesion: 0.29
Nodes (5): Dashboard, BezhanSalleh\FilamentShield\Resources\Roles\RoleResource, Filament\Pages\Dashboard, Filament\Widgets\AccountWidget, Filament\Widgets\FilamentInfoWidget

### Community 176 - "🎨 Spesifikasi Token & Class CSS yang Tersedia di `theme.css`"
Cohesion: 0.15
Nodes (12): 1. Kartu Kontainer Utama (`.vision-card`), 2. Kotak Ikon Gradien Neon (`.vision-icon-box`), 3. Kapsul Metrik / Sub-Kartu (`.vision-pill-card` atau `.vision-pill-dark`), 4. Kartu Filter (`.vision-filter-card`), 5. Tabel Data Kaca (`.vision-table-card`), 6. Form Section & Skema Input Kaca (`.vision-card` pada `Section::make`), 7. Kartu Statistik Global (`StatsOverviewWidget` / `Stat::make`), ⚙️ Aturan Wajib untuk AI Developer (+4 more)

### Community 180 - "n"
Cohesion: 0.10
Nodes (43): Ei(), Aa(), Bi(), cf(), co(), da(), Dn(), Eo() (+35 more)

### Community 185 - "Illuminate\Database\Eloquent\Builder"
Cohesion: 0.04
Nodes (37): CommissionReconciliation, BackedEnum, UnitEnum, Width, Width, KitchenDisplay, BackedEnum, UnitEnum (+29 more)

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

### Community 304 - "FounderStatsWidget.php"
Cohesion: 0.50
Nodes (3): FounderStatsWidget, Filament\Widgets\StatsOverviewWidget, Filament\Widgets\StatsOverviewWidget\Stat

### Community 306 - "yl"
Cohesion: 0.40
Nodes (5): Bp(), om(), Op(), rl(), yl()

### Community 307 - "clickPercent"
Cohesion: 0.60
Nodes (5): clickPercent(), getPosition(), mouseUp(), movePlayhead(), timelineClicked()

### Community 318 - "Illuminate\Console\Command"
Cohesion: 0.11
Nodes (12): ExpireStaleOperationsCommand, FinalizeCashierCommissionCommand, GenerateUpcomingInvoicesCommand, ProcessSubscriptionLifecycleCommand, SendCashierCommissionReminderCommand, Carbon, DateTimeInterface, SubscriptionLifecycleService (+4 more)

### Community 320 - "et"
Cohesion: 0.40
Nodes (5): et(), ee(), he(), me(), Y()

### Community 321 - "c"
Cohesion: 0.67
Nodes (4): c(), o(), p(), s()

### Community 322 - "glassmorphism/show.blade.php"
Cohesion: 0.29
Nodes (6): landing.templates.glassmorphism.sections., landing.templates.glassmorphism.sections.hero, landing.sections., landing.templates.glassmorphism.partials.background, landing.templates.glassmorphism.sections.footer, landing.templates.glassmorphism.sections.header

### Community 324 - "CashierShift"
Cohesion: 0.06
Nodes (10): CashierShiftPrintController, CashierShift, CashierShiftService, ReceiptLogo, Barryvdh\DomPDF\Facade\Pdf, Endroid\QrCode\QrCode, Endroid\QrCode\Writer\PngWriter, Illuminate\Database\Eloquent\Collection (+2 more)

### Community 327 - "addSingleBadge"
Cohesion: 0.33
Nodes (6): addBadgesForSelectedOptions(), addSingleBadge(), createBadgeElement(), createRemoveButton(), getLabelForSingleSelection(), getSelectedOptionLabel()

### Community 347 - "N"
Cohesion: 0.33
Nodes (11): ae(), A(), E(), at(), be(), Gt(), i(), Jt() (+3 more)

### Community 348 - "st"
Cohesion: 0.36
Nodes (8): [g](), Ct(), lt(), ot(), se(), st(), Zt(), zt()

### Community 349 - "RefreshesAnalyticsChart.php"
Cohesion: 0.36
Nodes (8): generateChartDataChecksum(), getCachedChartData(), getChartData(), mountRefreshesAnalyticsChart(), refreshAnalyticsChartData(), rendering(), updateChartData(), Livewire\Attributes\Locked

### Community 355 - "Filament\Panel"
Cohesion: 0.33
Nodes (4): FilamentProfilePlugin, Filament\Panel, Ipatco\FilamentProfile\FilamentProfilePlugin, Ipatco\FilamentProfile\Widgets\AccountWidget

### Community 356 - "Ae"
Cohesion: 0.67
Nodes (3): Ae(), Bt(), ne()

### Community 361 - "ExcelExporter"
Cohesion: 0.36
Nodes (4): ExcelExporter, Illuminate\Contracts\View\View, Maatwebsite\Excel\Concerns\FromView, Maatwebsite\Excel\Concerns\ShouldAutoSize

## Knowledge Gaps
- **342 isolated node(s):** `$schema`, `name`, `type`, `description`, `laravel` (+337 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **46 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Wi()` connect `Ye` to `code-editor.js`, `rich-editor.js`, `constructor`, `components/select.js`, `of`, `columns/select.js`?**
  _High betweenness centrality (0.029) - this node is a cross-community bridge._
- **Why does `_s()` connect `components/chart.js` to `rich-editor.js`, `o`?**
  _High betweenness centrality (0.025) - this node is a cross-community bridge._
- **Why does `update()` connect `constructor` to `stat/chart.js`, `components/chart.js`, `code-editor.js`, `rich-editor.js`, `y`, `slice`, `advance`, `r`, `get`, `vd`, `toString`, `of`, `.slice`, `reduce`, `echo.js`, `resolve`, `g$`, `prop`, `Ye`, `markdown-editor.js`, `te`, `n`, `t`, `facet`, `Sl`?**
  _High betweenness centrality (0.021) - this node is a cross-community bridge._
- **Are the 18 inferred relationships involving `constructor()` (e.g. with `a()` and `h()`) actually correct?**
  _`constructor()` has 18 INFERRED edges - model-reasoned connections that need verification._
- **What connects `$schema`, `name`, `type` to the rest of the system?**
  _342 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `stat/chart.js` be split into smaller, more focused modules?**
  _Cohesion score 0.010747207000552228 - nodes in this community are weakly interconnected._
- **Should `components/chart.js` be split into smaller, more focused modules?**
  _Cohesion score 0.008473021724128895 - nodes in this community are weakly interconnected._