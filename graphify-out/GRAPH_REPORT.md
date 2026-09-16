# Graph Report - WALK-IN-RESTO  (2026-09-16)

## Corpus Check
- 850 files · ~394,587 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 9680 nodes · 30055 edges · 439 communities (388 shown, 51 thin omitted)
- Extraction: 91% EXTRACTED · 9% INFERRED · 0% AMBIGUOUS · INFERRED: 2589 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `92db1d2f`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- stat/chart.js
- components/chart.js
- code-editor.js
- rich-editor.js
- ExportFile
- Pe
- Restaurant
- constructor
- fromObject
- ManageAdSettings
- r
- find
- constructor
- PlatformSetting
- _update
- Order
- advance
- .forEach
- get
- o
- Illuminate\Database\Eloquent\Model
- Illuminate\Foundation\Http\FormRequest
- AdminPanelProvider.php
- .parent
- s
- support.js
- vd
- Filament/Concerns/HandlesTranslatableForm.php
- GuestContext
- columns/select.js
- e
- updateElements
- echo.js
- resolve
- fn
- facet
- BlogAnalytics
- E
- prop
- configure
- Customer
- nodesBetween
- apply
- EditProfile
- Illuminate\Http\Request
- notifications.js
- markdown-editor.js
- ExportFileResource
- te
- Cn
- getContext
- components/select.js
- Illuminate\View\View
- tables.js
- l
- r
- ae
- SubscriptionStatus
- Xt
- Illuminate\Database\Migrations\Migration
- filament-right-click.js
- reduce
- t
- Si
- LandingLayout
- Illuminate\Http\JsonResponse
- selectOption
- CashierCommissionBillingService
- CreateCashierOrder
- ir
- Filament\Schemas\Schema
- slider.js
- RestaurantAnalyticsPeriod
- closeDropdown
- _update
- slice
- fo
- g$
- fn
- file-upload.js
- A
- c
- createResolver
- RestaurantDirectory
- FonnteErrorMessage
- getDatasetMeta
- VisitDevice
- S
- constructor
- FilamentTenantTheme
- devDependencies
- filament/app.js
- RestaurantReview
- fn
- Locales
- require
- scripts
- .slice
- color-picker.js
- resources/js/app.js
- fd
- addElementByRule
- composer.json
- order-today-stats-widget.blade.php
- RegisterRestaurant
- date-time-picker.js
- 🚀 3. Langkah-Langkah Menambahkan Template Baru (*Workflow*)
- RestaurantDirectoryTest
- Mt
- KitchenDisplay
- CommissionReconciliation
- ManageSoundNotifications
- static
- actions/actions.js
- sliceDoc
- schemas.js
- Landasan Produksi — Tahap 1 (Walk-In Operasional)
- Guest
- GeoDistance
- 2. Masalah di lapangan — dan apa yang sistem selesaikan
- User
- require-dev
- st
- 6. Katalog fitur
- config
- 6. Katalog fitur
- StoreBlogCommentRequest
- register-restaurant.blade.php
- add-to-cart-modal.blade.php
- GuestCheckoutService
- components/actions.js
- psr-4
- extra
- logging.php
- _notify
- selectRecords
- ReportExportBuilder
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
- le
- closeSimpleModeModal
- RestaurantCategory
- renderOptions
- CashierFilamentActionsTest
- CmsMedia
- GraceReadOnlyTest
- N
- .count
- DailyOmzetService
- InteractsWithRestaurantAnalytics.php
- UserPolicy
- TenantIsolationTest
- 🎨 Spesifikasi Token & Class CSS yang Tersedia di `theme.css`
- AdSetting
- LandingMenuCatalogTest
- n
- BlogPost
- RestaurantAnalyticsService
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
- ViewOrder
- classic/show.blade.php
- rt
- RestaurantReviewApiTest
- Visit
- SubscriptionLifecycleService
- rules/graphify.md
- workflows/graphify.md
- HasSingletonForm.php
- Dashboard
- sl
- glassmorphism/show.blade.php
- Dashboard
- addEventListener
- selectOption
- SubscriptionInvoice
- glassmorphism-background.blade.php
- Vf
- Illuminate\Http\Resources\Json\JsonResource
- dropdown.blade.php
- Carbon\CarbonInterface
- GuestMenuTest
- WelcomeBannerWidget
- xc
- CashierOrderSoundAlertTest
- yl
- ManageLandingLayout
- BlogPostObserver
- clickPercent
- Illuminate\Database\Schema\Blueprint
- c
- Y
- TableQrToken
- OrderReceiptPrintTest
- ManageHomeLanding
- y
- InvoicePaymentTest
- st
- replace
- Ae
- Illuminate\Contracts\View\View
- 2026_08_20_010000_add_floor_layout_to_tables_table.php
- Illuminate\Support\Facades\Schema
- Astrotomic\Translatable\Validation\RuleFactory
- layouts/blog.blade.php

## God Nodes (most connected - your core abstractions)
1. `User` - 393 edges
2. `Restaurant` - 348 edges
3. `TestCase` - 191 edges
4. `Order` - 161 edges
5. `constructor()` - 152 edges
6. `update()` - 148 edges
7. `MenuItem` - 114 edges
8. `PlatformSetting` - 111 edges
9. `Visit` - 104 edges
10. `DiningTable` - 100 edges

## Surprising Connections (you probably didn't know these)
- `up()` --calls--> `DiningTable`  [EXTRACTED]
  database/migrations/2026_08_20_010000_add_floor_layout_to_tables_table.php → app/Models/DiningTable.php
- `makeOwner()` --calls--> `Role`  [INFERRED]
  tests/Concerns/CreatesSubscribedRestaurant.php → app/Models/Role.php
- `makeStaff()` --calls--> `Role`  [INFERRED]
  tests/Concerns/CreatesSubscribedRestaurant.php → app/Models/Role.php
- `createGuestRestaurant()` --calls--> `DiningTable`  [EXTRACTED]
  tests/Concerns/CreatesGuestRestaurant.php → app/Models/DiningTable.php
- `createGuestRestaurant()` --calls--> `KdsStation`  [EXTRACTED]
  tests/Concerns/CreatesGuestRestaurant.php → app/Models/KdsStation.php

## Import Cycles
- None detected.

## Communities (439 total, 51 thin omitted)

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
Nodes (254): aa(), Ad(), add(), addExtensions(), addHackNode(), addNode(), addNodeMark(), addTextblockHacks() (+246 more)

### Community 4 - "ExportFile"
Cohesion: 0.05
Nodes (19): TenantForceDeleteCommand, PdfExporter, CleanupOldExportFilesJob, ExportReportJob, ForceDeleteTenantJob, ExportFile, ExportFileObserver, ExportFilePolicy (+11 more)

### Community 5 - "Pe"
Cohesion: 0.16
Nodes (28): Ba(), ca(), de(), dt(), Ee(), ei(), Ft(), Hr() (+20 more)

### Community 6 - "Restaurant"
Cohesion: 0.02
Nodes (95): GenerateUpcomingInvoicesCommand, RestaurantMenuController, RestaurantLandingController, SetPermissionsTeamId, MenuCategory, MenuItem, Outlet, Restaurant (+87 more)

### Community 7 - "constructor"
Cohesion: 0.02
Nodes (171): active(), add(), addChunk(), addEventListener(), addInfoPane(), addInner(), addWindowListeners(), adjust() (+163 more)

### Community 8 - "fromObject"
Cohesion: 0.03
Nodes (109): El(), ac(), ae(), after(), Al(), Am(), before(), bl() (+101 more)

### Community 9 - "ManageAdSettings"
Cohesion: 0.19
Nodes (7): ManageAdSettings, BackedEnum, UnitEnum, Filament\Schemas\Components\Tabs, Filament\Schemas\Components\Tabs\Tab, Tab, Tabs

### Community 10 - "r"
Cohesion: 0.07
Nodes (49): ao(), append(), Cc(), co(), descendants(), domAtPos(), element(), findDiffEnd() (+41 more)

### Community 11 - "find"
Cohesion: 0.13
Nodes (22): baseDirAt(), bidiIn(), bidiSpans(), bidiSpansAt(), checkHover(), coordsAtPos(), Df(), dirAt() (+14 more)

### Community 12 - "constructor"
Cohesion: 0.03
Nodes (85): Bc(), bg(), chartOptionScopes(), Cl(), clone(), constructor(), create(), Ct() (+77 more)

### Community 13 - "PlatformSetting"
Cohesion: 0.04
Nodes (15): ManageBillingAccount, BackedEnum, UnitEnum, ManagePlatformPages, BackedEnum, UnitEnum, analyticsTheme(), PlatformPageController (+7 more)

### Community 14 - "_update"
Cohesion: 0.04
Nodes (106): addBox(), addElements(), adjustHitBoxes(), afterBuildTicks(), afterCalculateLabelRotation(), afterDataLimits(), afterDraw(), afterFit() (+98 more)

### Community 15 - "Order"
Cohesion: 0.03
Nodes (24): ExportFileDownloadController, OrderReceiptDownloadController, SendWhatsappReceiptJob, Order, OrderItem, OrderReceipt, WhatsappMessage, CommissionReconciliationService (+16 more)

### Community 16 - "advance"
Cohesion: 0.05
Nodes (65): addChild(), addGaps(), addLeafElement(), addNode(), advance(), ATXHeading(), blank(), break() (+57 more)

### Community 17 - ".forEach"
Cohesion: 0.03
Nodes (135): _0(), addAttributes(), addNodeView(), addOptions(), addProseMirrorPlugins(), af(), au(), B0() (+127 more)

### Community 18 - "get"
Cohesion: 0.03
Nodes (101): addBlockWidget(), addBreak(), addComposition(), addDelimiter(), addInlineWidget(), addLine(), addLineStart(), addLineStartIfNotCovered() (+93 more)

### Community 19 - "o"
Cohesion: 0.04
Nodes (121): ag(), ah(), apply(), ar(), au(), average(), Ba(), beforeDatasetDraw() (+113 more)

### Community 20 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.02
Nodes (44): BlogCategoryTranslation, BlogHeroSettingTranslation, BlogTagTranslation, CashierShift, CashierShiftMovement, CmsBanner, LogOptions, CmsFaq (+36 more)

### Community 21 - "Illuminate\Foundation\Http\FormRequest"
Cohesion: 0.06
Nodes (10): TableController, AddCartItemRequest, CheckoutRequest, ClaimTableRequest, JoinTableRequest, StorePaymentProofRequest, StoreRestaurantReviewRequest, UpdateCartItemRequest (+2 more)

### Community 22 - "AdminPanelProvider.php"
Cohesion: 0.11
Nodes (29): Login, ApplyPlatformBrandTheme, AdminPanelProvider, BloggerPanelProvider, FounderPanelProvider, AuthGlass, BezhanSalleh\FilamentShield\FilamentShieldPlugin, Bityukov\CommandCenter\Filament\CommandCenterPlugin (+21 more)

### Community 23 - ".parent"
Cohesion: 0.05
Nodes (10): getRecordTitle(), EditBlogPost, getRecordRouteBindingEloquentQuery(), ViewCashierShift, Closure, Closure, Closure, ManageOutlet (+2 more)

### Community 24 - "s"
Cohesion: 0.05
Nodes (71): add(), afterAutoSkip(), Bt(), buildLookupTable(), buildOrUpdateElements(), cl(), Cn(), cr() (+63 more)

### Community 25 - "support.js"
Cohesion: 0.05
Nodes (75): acquireScrollLock(), ai(), e(), Bi(), br(), Bt(), ca(), close() (+67 more)

### Community 26 - "vd"
Cohesion: 0.06
Nodes (95): _a(), Ae(), as(), bc(), cd(), ee(), ue(), u() (+87 more)

### Community 27 - "Filament/Concerns/HandlesTranslatableForm.php"
Cohesion: 0.44
Nodes (8): afterCreate(), afterSave(), ensureAtLeastOneTranslation(), extractTranslations(), hasTranslatableContent(), mutateFormDataBeforeCreate(), mutateFormDataBeforeSave(), persistTranslations()

### Community 28 - "GuestContext"
Cohesion: 0.06
Nodes (16): GuestCart, GuestCheckout, GuestMenu, GuestPay, GuestReview, GuestStatus, RestaurantMenuCatalog, VisitCartItem (+8 more)

### Community 29 - "columns/select.js"
Cohesion: 0.06
Nodes (57): A(), An(), applyDisabledState(), b(), Bt(), Ce(), D(), disable() (+49 more)

### Community 30 - "e"
Cohesion: 0.06
Nodes (86): addCommands(), AS(), Bc(), Bm(), cellsInRect(), check(), checkAttrs(), checkContent() (+78 more)

### Community 31 - "updateElements"
Cohesion: 0.03
Nodes (113): aa(), acquireContext(), afterAutoSkip(), Ao(), aspectRatio(), bh(), bu(), buildLookupTable() (+105 more)

### Community 32 - "echo.js"
Cohesion: 0.05
Nodes (49): a(), ar(), b(), Be(), Ce(), cr(), d(), De() (+41 more)

### Community 33 - "resolve"
Cohesion: 0.05
Nodes (147): Ac(), addKeyboardShortcuts(), after(), ag(), al(), allowsMarks(), before(), blockRange() (+139 more)

### Community 34 - "fn"
Cohesion: 0.13
Nodes (33): aa(), At(), ba(), cr(), da(), de(), dt(), ei() (+25 more)

### Community 35 - "facet"
Cohesion: 0.04
Nodes (68): accept(), baseTheme(), blur(), bu(), build(), dispatch(), dr(), facet() (+60 more)

### Community 36 - "BlogAnalytics"
Cohesion: 0.08
Nodes (7): BlogAnalytics, Dashboard, BlogContentProgressWidget, BlogTrafficApexChartWidget, BlogVisitStatsWidget, TopBloggersWidget, Filament\Pages\Dashboard\Concerns\HasFiltersForm

### Community 37 - "E"
Cohesion: 0.05
Nodes (61): $a(), add(), af(), B(), bo(), bs(), ca(), _cachedScopes() (+53 more)

### Community 38 - "prop"
Cohesion: 0.05
Nodes (69): acceptToken(), allows(), AQ(), atLastNode(), au(), child(), childAfter(), childBefore() (+61 more)

### Community 39 - "configure"
Cohesion: 0.06
Nodes (55): addElements(), afterDatasetsUpdate(), bi(), bindEvents(), bindUserEvents(), buildOrUpdateControllers(), buildOrUpdateScales(), _checkEventBindings() (+47 more)

### Community 40 - "Customer"
Cohesion: 0.08
Nodes (4): Customer, CustomerLoyaltyPoint, CustomerCrmService, CustomerCrmLoyaltyTest

### Community 41 - "nodesBetween"
Cohesion: 0.05
Nodes (68): addGlobalAttributes(), addInputRules(), addMark(), addPasteRules(), addStoredMark(), Ah(), Ax(), childAfter() (+60 more)

### Community 42 - "apply"
Cohesion: 0.07
Nodes (52): ak(), apply(), applyInner(), applyTransaction(), at(), bk(), c(), bp() (+44 more)

### Community 43 - "EditProfile"
Cohesion: 0.08
Nodes (9): EditProfile, FilamentProfilePlugin, ProfileInformationForm, Filament\Schemas\Components\Component, Ipatco\FilamentProfile\FilamentProfilePlugin, Ipatco\FilamentProfile\Forms\ProfileInformationForm, Ipatco\FilamentProfile\Pages\EditProfile, Ipatco\FilamentProfile\Widgets\AccountWidget (+1 more)

### Community 44 - "Illuminate\Http\Request"
Cohesion: 0.12
Nodes (15): CashierShiftPrintController, EnsureApiGuestVisit, EnsureGuestVisit, EnsureRestaurantOperations, EnsureTenantSubscription, IdentifyApiGuestDevice, IdentifyGuestDevice, RequireApiGuestDevice (+7 more)

### Community 45 - "notifications.js"
Cohesion: 0.06
Nodes (31): actions(), button(), c(), close(), configureAnimations(), configureTransitions(), constructor(), danger() (+23 more)

### Community 46 - "markdown-editor.js"
Cohesion: 0.05
Nodes (86): Ac(), af(), al(), An(), ao(), ar(), bf(), bl() (+78 more)

### Community 47 - "ExportFileResource"
Cohesion: 0.07
Nodes (12): CustomerAnalytics, CustomerSatisfactionAnalytics, GenerateReport, BackedEnum, UnitEnum, CustomerReviewResource, CustomerResource, ListCustomers (+4 more)

### Community 48 - "te"
Cohesion: 0.05
Nodes (11): Bn(), Id(), ji(), on(), qd(), qi(), Ri(), te() (+3 more)

### Community 49 - "Cn"
Cohesion: 0.13
Nodes (46): Cn(), b(), Be(), Ce(), De(), dn(), _e(), Fe() (+38 more)

### Community 50 - "getContext"
Cohesion: 0.07
Nodes (51): acquireContext(), Ae(), Ao(), bl(), Ca(), ci(), _computeGridLineItems(), _computeLabelArea() (+43 more)

### Community 51 - "components/select.js"
Cohesion: 0.08
Nodes (34): b(), Bt(), D(), E(), en(), Et(), getLabelsForMultipleSelection(), getSelectedOptionLabels() (+26 more)

### Community 52 - "Illuminate\View\View"
Cohesion: 0.16
Nodes (7): BlogController, BlogLikeController, BlogLike, BlogLikeService, RecordVisitService, VisitorHash, Illuminate\View\View

### Community 53 - "tables.js"
Cohesion: 0.13
Nodes (44): A(), ae(), B(), be(), C(), ce(), E(), ee() (+36 more)

### Community 54 - "l"
Cohesion: 0.14
Nodes (22): ad(), Ct(), dd(), df(), dr(), gl(), ir(), jl() (+14 more)

### Community 55 - "r"
Cohesion: 0.15
Nodes (43): _a(), ar(), c(), f(), d(), di(), g(), Hi() (+35 more)

### Community 56 - "ae"
Cohesion: 0.09
Nodes (34): ae(), Ao(), as(), B(), Kt(), cs(), Ee(), Ge() (+26 more)

### Community 57 - "SubscriptionStatus"
Cohesion: 0.12
Nodes (3): BackedEnum, UnitEnum, SubscriptionStatus

### Community 58 - "Xt"
Cohesion: 0.12
Nodes (44): ae(), At(), bi(), bn(), ci(), cn(), ct(), de() (+36 more)

### Community 60 - "filament-right-click.js"
Cohesion: 0.11
Nodes (42): a(), ae(), b(), be(), C(), ce(), D(), de() (+34 more)

### Community 61 - "reduce"
Cohesion: 0.07
Nodes (52): addActions(), advanceFully(), advanceStack(), allActions(), c0(), canShift(), close(), deadEnd() (+44 more)

### Community 62 - "t"
Cohesion: 0.06
Nodes (50): a$(), activeForPoint(), addBlock(), addLineDeco(), b1(), blankContent(), boundChange(), commit() (+42 more)

### Community 63 - "Si"
Cohesion: 0.14
Nodes (40): ae(), At(), bi(), bn(), ci(), cn(), ct(), de() (+32 more)

### Community 65 - "Illuminate\Http\JsonResponse"
Cohesion: 0.09
Nodes (15): CartController, CheckoutController, MenuController, OrderController, ReviewController, SessionController, VisitController, RestaurantController (+7 more)

### Community 66 - "selectOption"
Cohesion: 0.12
Nodes (39): addBadgesForSelectedOptions(), addSingleBadge(), addSingleSelectionDisplay(), closeDropdown(), constructor(), createBadgeElement(), createOptionElement(), createRemoveButton() (+31 more)

### Community 67 - "CashierCommissionBillingService"
Cohesion: 0.15
Nodes (9): ExpireStaleOperationsCommand, FinalizeCashierCommissionCommand, ProcessSubscriptionLifecycleCommand, SendCashierCommissionReminderCommand, CashierCommissionBillingService, Illuminate\Console\Command, Illuminate\Foundation\Inspiring, Illuminate\Support\Facades\Artisan (+1 more)

### Community 68 - "CreateCashierOrder"
Cohesion: 0.05
Nodes (9): CreateCashierOrder, BackedEnum, UnitEnum, Width, CashierMenuCatalog, CashierOrderPreview, CheckoutTotals, CashierMenuCatalogTest (+1 more)

### Community 69 - "ir"
Cohesion: 0.13
Nodes (34): et(), Ft(), ir(), ce(), de(), Dt(), ee(), Et() (+26 more)

### Community 70 - "Filament\Schemas\Schema"
Cohesion: 0.02
Nodes (163): SeoFields, TranslationTabs, ManageBlogHero, BlogHeroFormSchema, BlogCategoryResource, EditBlogCategory, ViewBlogCategory, BlogCategoryForm (+155 more)

### Community 71 - "slider.js"
Cohesion: 0.11
Nodes (33): ar(), Be(), Ce(), De(), _e(), Ee(), er(), Fe() (+25 more)

### Community 72 - "RestaurantAnalyticsPeriod"
Cohesion: 0.23
Nodes (3): Carbon, RestaurantAnalyticsPeriod, RestaurantAnalyticsServiceTest

### Community 73 - "closeDropdown"
Cohesion: 0.23
Nodes (17): applyDisabledState(), closeDropdown(), constructor(), destroy(), disable(), enable(), focusNextOption(), focusPreviousOption() (+9 more)

### Community 74 - "_update"
Cohesion: 0.07
Nodes (43): themeClasses(), active(), afterBuildTicks(), afterCalculateLabelRotation(), afterDataLimits(), afterFit(), afterSetDimensions(), afterTickToLabelConversion() (+35 more)

### Community 75 - "slice"
Cohesion: 0.05
Nodes (123): addElement(), Ah(), baseIndentFor(), be(), Bg(), a(), blockAt(), bS() (+115 more)

### Community 76 - "fo"
Cohesion: 0.07
Nodes (41): alpha(), be(), bo(), co(), darken(), desaturate(), Ea(), es() (+33 more)

### Community 77 - "g$"
Cohesion: 0.05
Nodes (59): attrs(), bi(), cc(), ch(), cO(), _d(), eh(), Ex() (+51 more)

### Community 78 - "fn"
Cohesion: 0.09
Nodes (35): Rd(), $a(), Ck(), closest(), De(), fn(), p(), Fx() (+27 more)

### Community 79 - "file-upload.js"
Cohesion: 0.08
Nodes (12): hc(), constructor(), define(), dm(), getExtension(), _getTestState(), gm(), Il() (+4 more)

### Community 80 - "A"
Cohesion: 0.10
Nodes (36): A(), As(), buildTicks(), calculateLabelRotation(), _calculatePadding(), _computeLabelItems(), _computeLabelSizes(), computeTickLimit() (+28 more)

### Community 81 - "c"
Cohesion: 0.09
Nodes (32): ai(), al(), bs(), dl(), c(), er(), first(), getCenterPoint() (+24 more)

### Community 82 - "createResolver"
Cohesion: 0.09
Nodes (32): _cachedScopes(), createResolver(), datasetAnimationScopeKeys(), datasetElementScopeKeys(), get(), getMaxOverflow(), getOptionScopes(), getSharedOptions() (+24 more)

### Community 83 - "RestaurantDirectory"
Cohesion: 0.11
Nodes (3): RestaurantDirectory, Livewire\Attributes\Computed, Livewire\WithPagination

### Community 84 - "FonnteErrorMessage"
Cohesion: 0.11
Nodes (8): FonnteClient, FonnteErrorMessage, Throwable, Illuminate\Http\Client\ConnectionException, Illuminate\Http\Client\Response, PHPUnit\Framework\Attributes\DataProvider, RuntimeException, FonnteErrorMessageTest

### Community 85 - "getDatasetMeta"
Cohesion: 0.11
Nodes (26): afterDatasetsUpdate(), An(), generateLabels(), getDatasetMeta(), getDataVisibility(), _getLegendItemAt(), getMaxBorderWidth(), _getSortedDatasetMetas() (+18 more)

### Community 87 - "VisitDevice"
Cohesion: 0.11
Nodes (5): ScanTable, VisitDevice, StaleOperationsService, TableScanService, VisitClaimService

### Community 88 - "S"
Cohesion: 0.11
Nodes (23): ar(), da(), getPadding(), gn(), gs(), It(), ji(), ke() (+15 more)

### Community 89 - "constructor"
Cohesion: 0.11
Nodes (22): Ot(), apply(), chartOptionScopes(), constructor(), describe(), ei(), getDevicePixelRatio(), getMeta() (+14 more)

### Community 90 - "FilamentTenantTheme"
Cohesion: 0.12
Nodes (6): ApplyRestaurantPanelTheme, FilamentTenantTheme, Filament\Support\Colors\ColorManager, Filament\Support\Facades\FilamentColor, ReflectionClass, FilamentTenantThemeTest

### Community 91 - "devDependencies"
Cohesion: 0.09
Nodes (21): apexcharts, axios, concurrently, laravel-vite-plugin, dependencies, apexcharts, devDependencies, axios (+13 more)

### Community 92 - "filament/app.js"
Cohesion: 0.13
Nodes (8): B(), close(), G(), init(), P(), setUpResizeObserver(), x(), Y()

### Community 93 - "RestaurantReview"
Cohesion: 0.10
Nodes (4): ListCustomerReviews, RestaurantReview, PublicRestaurantApiTest, CustomerSatisfactionAnalyticsTest

### Community 94 - "fn"
Cohesion: 0.22
Nodes (18): An(), Ce(), ei(), fn(), Ft(), Ie(), Le(), ni() (+10 more)

### Community 95 - "Locales"
Cohesion: 0.15
Nodes (11): afterCreate(), afterSave(), ensureAtLeastOneTranslation(), extractTranslations(), hasTranslatableContent(), mutateFormDataBeforeCreate(), mutateFormDataBeforeFill(), mutateFormDataBeforeSave() (+3 more)

### Community 96 - "require"
Cohesion: 0.12
Nodes (17): require, astrotomic/laravel-translatable, barryvdh/laravel-dompdf, bezhansalleh/filament-shield, endroid/qr-code, filament/filament, hammadzafar05/filament-mobile-preset, ipatco/filament-profile (+9 more)

### Community 97 - "scripts"
Cohesion: 0.12
Nodes (16): scripts, dev, post-autoload-dump, post-create-project-cmd, post-root-package-install, post-update-cmd, Composer\\Config::disableProcessTimeout, Illuminate\\Foundation\\ComposerScripts::postAutoloadDump (+8 more)

### Community 98 - ".slice"
Cohesion: 0.06
Nodes (54): accepts(), addInner(), addMaps(), addStep(), addTransform(), appendMap(), appendMapping(), appendMappingInverted() (+46 more)

### Community 99 - "color-picker.js"
Cohesion: 0.14
Nodes (3): style(), update(), [x]()

### Community 100 - "resources/js/app.js"
Cohesion: 0.12
Nodes (6): applyTheme(), bindThemeToggle(), initReveal(), initTheme(), syncThemeToggleIcons(), toggleTheme()

### Community 101 - "fd"
Cohesion: 0.06
Nodes (48): activateHover(), addToSet(), bd(), between(), Bh(), cd(), childString(), clearDelayedAndroidKey() (+40 more)

### Community 102 - "addElementByRule"
Cohesion: 0.13
Nodes (27): addAll(), addDOM(), addElement(), addElementByRule(), addTextNode(), addToSet(), allowedMarks(), allowsMarkType() (+19 more)

### Community 103 - "composer.json"
Cohesion: 0.14
Nodes (13): autoload-dev, psr-4, description, keywords, license, minimum-stability, name, prefer-stable (+5 more)

### Community 107 - "date-time-picker.js"
Cohesion: 0.26
Nodes (8): d(), e(), i(), m(), r(), s(), t(), rr()

### Community 108 - "🚀 3. Langkah-Langkah Menambahkan Template Baru (*Workflow*)"
Cohesion: 0.12
Nodes (15): 📌 1. Prinsip Utama & Aturan Wajib (Golden Rules), 🏗️ 2. Arsitektur 10 Core Section CMS (1:1 Mapping), 🚀 3. Langkah-Langkah Menambahkan Template Baru (*Workflow*), 📊 4. Variabel yang Disediakan oleh `LandingPageDataService`, 💎 5. Pelajaran Desain & Best Practices UI/UX (*Key Learnings*), A. Larangan Keras Menggunakan Emoji Unicode (Gunakan SVG Heroicons), B. Arsitektur Layering 3D & Background Tembus Pandang (*Stacking Context*), C. Resep Glassmorphism iOS yang Nyata (*True Frosted Glass*) (+7 more)

### Community 110 - "Mt"
Cohesion: 0.24
Nodes (11): apply(), fs(), go(), Hr(), T(), ir(), it(), Mt() (+3 more)

### Community 111 - "KitchenDisplay"
Cohesion: 0.13
Nodes (5): KitchenDisplay, BackedEnum, UnitEnum, Width, Filament\Resources\Concerns\HasTabs

### Community 112 - "CommissionReconciliation"
Cohesion: 0.24
Nodes (4): CommissionReconciliation, BackedEnum, UnitEnum, Width

### Community 113 - "ManageSoundNotifications"
Cohesion: 0.16
Nodes (4): ManageSoundNotifications, BackedEnum, UnitEnum, ManageSoundNotificationsTest

### Community 114 - "static"
Cohesion: 0.02
Nodes (54): CreateBlogCategory, ListBlogCategories, CreateBlogComment, ListBlogComments, CreateBlogger, EditBlogger, ListBloggers, CreateBlogPost (+46 more)

### Community 115 - "actions/actions.js"
Cohesion: 0.44
Nodes (8): closeModal(), generateModalId(), getActionNestingIndexFromModalId(), init(), openModal(), rememberPreviouslyFocusedElement(), restorePreviouslyFocusedElement(), syncActionModals()

### Community 116 - "sliceDoc"
Cohesion: 0.10
Nodes (27): aO(), charCategorizer(), Fc(), flatten(), getCursor(), getDeco(), gT(), highlight() (+19 more)

### Community 118 - "Landasan Produksi — Tahap 1 (Walk-In Operasional)"
Cohesion: 0.05
Nodes (37): 10. Aturan yang tidak boleh dilanggar, 11. Matriks skenario lapangan, 12. Yang sengaja tidak didukung (bukan gap, non-goal), 13. Tahap 2 (bukan sekarang), 14. Urutan bangun (agar cepat produksi), 1. Keputusan terkunci, 2. Modul tahap 1 vs ditunda, 3. Model domain (+29 more)

### Community 119 - "Guest"
Cohesion: 0.05
Nodes (34): Bentuk objek, CartItemResource, DELETE `/guest/cart/items/{cartItem}`, GET `/guest/cart`, GET `/guest/menu`, GET `/guest/orders`, GET `/guest/orders/{public_id}`, GET `/guest/review` (+26 more)

### Community 120 - "GeoDistance"
Cohesion: 0.20
Nodes (4): GeoDistance, PHPUnit\Framework\TestCase, ExampleTest, GeoDistanceTest

### Community 121 - "2. Masalah di lapangan — dan apa yang sistem selesaikan"
Cohesion: 0.05
Nodes (41): 1. Cerita yang mungkin terasa familiar, 2.10 Struk kertas hilang, tamu minta dikirim WhatsApp, 2.11 Tampilan website restoran kaku atau tidak sesuai konsep resto, 2.12 Calon tamu ingin lihat menu lengkap sebelum datang ke resto, 2.13 Foto menu yang diupload staf ukurannya raksasa bikin web lemot, 2.14 Owner dan kasir ingin tahu performa hari ini secara instan, 2.15 Tak sengaja hapus menu atau meja saat jam sibuk, 2.16 Sulit ditemukan calon tamu baru di internet (+33 more)

### Community 122 - "User"
Cohesion: 0.03
Nodes (24): Role, HasMany, LogOptions, User, RolePolicy, Filament\Models\Contracts\FilamentUser, Filament\Models\Contracts\HasTenants, Illuminate\Database\Eloquent\Factories\HasFactory (+16 more)

### Community 123 - "require-dev"
Cohesion: 0.25
Nodes (8): require-dev, fakerphp/faker, laravel/pail, laravel/pint, laravel/sail, mockery/mockery, nunomaduro/collision, phpunit/phpunit

### Community 124 - "st"
Cohesion: 0.05
Nodes (48): ad(), applyStack(), br(), Di(), drawCaret(), _f(), first(), getCaretPosition() (+40 more)

### Community 125 - "6. Katalog fitur"
Cohesion: 0.06
Nodes (33): 1. Latar belakang, 2. Rumusan masalah, 3.1 Tujuan umum, 3.2 Tujuan khusus, 3. Tujuan, 4.1 Bagi restoran (owner, kasir, dapur), 4.2 Bagi tamu, 4.3 Bagi pengelola platform (+25 more)

### Community 127 - "config"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

### Community 128 - "6. Katalog fitur"
Cohesion: 0.06
Nodes (32): 1. Latar belakang, 2. Rumusan masalah, 3.1 Tujuan umum, 3.2 Tujuan khusus, 3. Tujuan, 4.1 Bagi restoran (owner, kasir, dapur), 4.2 Bagi tamu, 4.3 Bagi pengelola platform (+24 more)

### Community 129 - "StoreBlogCommentRequest"
Cohesion: 0.19
Nodes (5): BlogCommentController, StoreBlogCommentRequest, ValidBlogCommentParent, Illuminate\Contracts\Validation\ValidationRule, Illuminate\Http\RedirectResponse

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

### Community 137 - "_notify"
Cohesion: 0.20
Nodes (14): active(), _animateOptions(), cancel(), _createAnimations(), _createDescriptors(), _descriptors(), _notify(), _notifyStateChanges() (+6 more)

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

### Community 153 - "le"
Cohesion: 0.23
Nodes (13): De(), Ee(), Fl(), le(), mm(), pe(), q(), qe() (+5 more)

### Community 157 - "RestaurantCategory"
Cohesion: 0.07
Nodes (8): ManageCmsProfile, BackedEnum, UnitEnum, Facility, RestaurantCategory, FacilitySeeder, RestaurantCategorySeeder, RestaurantRegistrationStepperTest

### Community 158 - "renderOptions"
Cohesion: 0.37
Nodes (13): createOptionElement(), deferPositionDropdown(), filterOptions(), handleSearch(), hideLoadingState(), openDropdown(), populateLabelRepositoryFromOptions(), positionDropdown() (+5 more)

### Community 163 - "CmsMedia"
Cohesion: 0.02
Nodes (31): BlogAudienceWidget, BlogCategoryDistributionWidget, BlogReferrersWidget, TopBlogPostsWidget, FounderOverdueRestaurantsWidget, FounderPendingInvoicesWidget, FounderRecentTenantsWidget, FounderRevenueGrowthApexChartWidget (+23 more)

### Community 164 - "GraceReadOnlyTest"
Cohesion: 0.16
Nodes (4): BlockGraceMutations, SubscriptionWriteGuard, Livewire\ComponentHook, GraceReadOnlyTest

### Community 168 - "N"
Cohesion: 0.33
Nodes (11): ae(), A(), E(), at(), be(), Gt(), i(), Jt() (+3 more)

### Community 170 - ".count"
Cohesion: 0.24
Nodes (3): periodSummary(), RestaurantDirectory, Illuminate\Contracts\Pagination\LengthAwarePaginator

### Community 172 - "InteractsWithRestaurantAnalytics.php"
Cohesion: 0.36
Nodes (8): analyticsDateFrom(), analyticsDateTo(), analyticsDayCount(), analyticsRangeLabel(), analyticsSnapshot(), canViewAnalytics(), normalizedAnalyticsDateRange(), Carbon

### Community 176 - "🎨 Spesifikasi Token & Class CSS yang Tersedia di `theme.css`"
Cohesion: 0.15
Nodes (12): 1. Kartu Kontainer Utama (`.vision-card`), 2. Kotak Ikon Gradien Neon (`.vision-icon-box`), 3. Kapsul Metrik / Sub-Kartu (`.vision-pill-card` atau `.vision-pill-dark`), 4. Kartu Filter (`.vision-filter-card`), 5. Tabel Data Kaca (`.vision-table-card`), 6. Form Section & Skema Input Kaca (`.vision-card` pada `Section::make`), 7. Kartu Statistik Global (`StatsOverviewWidget` / `Stat::make`), ⚙️ Aturan Wajib untuk AI Developer (+4 more)

### Community 177 - "AdSetting"
Cohesion: 0.12
Nodes (4): AdSetting, self, AdPlacementService, AdIntegrationTest

### Community 180 - "n"
Cohesion: 0.11
Nodes (38): Ei(), Aa(), ai(), at(), Bi(), cf(), da(), fa() (+30 more)

### Community 183 - "BlogPost"
Cohesion: 0.03
Nodes (29): SitemapController, BlogCategory, BlogComment, BlogHeroSetting, BlogPost, BlogPostTranslation, BlogTag, scopeForRestaurant() (+21 more)

### Community 193 - "restaurant-menu-catalog.blade.php"
Cohesion: 0.25
Nodes (7): landing.templates.foodie.sections.footer, landing.templates.foodie.sections.header, landing.templates.glassmorphism.partials.background, landing.templates.glassmorphism.sections.footer, landing.templates.glassmorphism.sections.header, partials.customer.landing-footer, partials.customer.landing-header

### Community 295 - "3. Detail Implementasi Perbaikan Keamanan"
Cohesion: 0.17
Nodes (11): 1. Ringkasan Eksekutif (Executive Summary), 2. Matriks Temuan & Status Perbaikan (Findings & Remediation Matrix), 3. Detail Implementasi Perbaikan Keamanan, 4. Hasil Verifikasi Pengujian Otomatis, A. Proteksi `qr_secret` pada Model (`SEC-01`), B. Middleware HTTP Security Headers (`SEC-02`), C. Pengetatan CORS & Session Cookie (`SEC-03` & `SEC-05`), D. Sanitasi File Upload (`SEC-06`) (+3 more)

### Community 296 - "foodie/show.blade.php"
Cohesion: 0.33
Nodes (5): landing.templates.foodie.sections., landing.templates.foodie.sections.hero, landing.sections., landing.templates.foodie.sections.footer, landing.templates.foodie.sections.header

### Community 297 - "ViewOrder"
Cohesion: 0.19
Nodes (3): ViewOrder, OrderReceiptPrintController, PermissionCheck

### Community 301 - "classic/show.blade.php"
Cohesion: 0.50
Nodes (3): landing.sections., partials.customer.landing-footer, partials.customer.landing-header

### Community 302 - "rt"
Cohesion: 0.29
Nodes (8): ca(), Dp(), _e(), Ea(), nm(), rt(), xt(), ya()

### Community 306 - "Visit"
Cohesion: 0.02
Nodes (25): ViewActivity, ManageDiningTables, Action, Activity, bootBelongsToRestaurantAndOutlet(), DiningTable, LogOptions, Visit (+17 more)

### Community 307 - "SubscriptionLifecycleService"
Cohesion: 0.33
Nodes (3): Carbon, DateTimeInterface, SubscriptionLifecycleService

### Community 317 - "HasSingletonForm.php"
Cohesion: 0.12
Nodes (23): content(), defaultForm(), fillForm(), getFormActions(), getFormContentComponent(), getRecord(), getRedirectUrl(), getSavedNotificationTitle() (+15 more)

### Community 318 - "Dashboard"
Cohesion: 0.29
Nodes (4): Dashboard, BezhanSalleh\FilamentShield\Resources\Roles\RoleResource, Filament\Pages\Dashboard, Filament\Widgets\FilamentInfoWidget

### Community 320 - "sl"
Cohesion: 0.33
Nodes (7): Cp(), da(), Gp(), kp(), Np(), sl(), Vp()

### Community 322 - "glassmorphism/show.blade.php"
Cohesion: 0.29
Nodes (6): landing.templates.glassmorphism.sections., landing.templates.glassmorphism.sections.hero, landing.sections., landing.templates.glassmorphism.partials.background, landing.templates.glassmorphism.sections.footer, landing.templates.glassmorphism.sections.header

### Community 324 - "Dashboard"
Cohesion: 0.09
Nodes (5): Dashboard, ImageOptimizer, PermissionTeam, ImageUploadAuditTest, ImageOptimizerTest

### Community 325 - "addEventListener"
Cohesion: 0.33
Nodes (7): addEventListener(), bindResponsiveEvents(), fu(), isAttached(), nr(), removeEventListener(), Ua()

### Community 327 - "selectOption"
Cohesion: 0.24
Nodes (12): addBadgesForSelectedOptions(), addSingleBadge(), addSingleSelectionDisplay(), createBadgeElement(), createRemoveButton(), getLabelForSingleSelection(), getSelectedOptionLabel(), hideMaxItemsMessage() (+4 more)

### Community 340 - "SubscriptionInvoice"
Cohesion: 0.04
Nodes (9): DateTimeInterface, SubscriptionInvoice, SubscriptionPlan, FounderAnalyticsService, SubscriptionInvoiceService, CashierCommissionBillingTest, FounderDashboardAnalyticsTest, GenerateUpcomingInvoicesTest (+1 more)

### Community 347 - "Vf"
Cohesion: 0.33
Nodes (7): contains(), gi(), splitAt(), toISOTime(), toMillis(), Vf(), ye()

### Community 348 - "Illuminate\Http\Resources\Json\JsonResource"
Cohesion: 0.06
Nodes (15): CartItemResource, CartResource, MenuCategoryResource, MenuItemResource, MenuVariantResource, ModifierGroupResource, ModifierResource, OrderItemResource (+7 more)

### Community 354 - "Carbon\CarbonInterface"
Cohesion: 0.31
Nodes (4): CommissionReconciliationExport, Carbon\CarbonInterface, Maatwebsite\Excel\Facades\Excel, Symfony\Component\HttpFoundation\BinaryFileResponse

### Community 358 - "xc"
Cohesion: 0.40
Nodes (5): fromSchema(), marksFromSchema(), nodesFromSchema(), schemaRules(), xc()

### Community 360 - "yl"
Cohesion: 0.40
Nodes (5): Bp(), om(), Op(), rl(), yl()

### Community 361 - "ManageLandingLayout"
Cohesion: 0.15
Nodes (6): TemplateRadioPicker, ManageLandingLayout, BackedEnum, Closure, UnitEnum, Filament\Forms\Components\Field

### Community 365 - "clickPercent"
Cohesion: 0.60
Nodes (5): clickPercent(), getPosition(), mouseUp(), movePlayhead(), timelineClicked()

### Community 369 - "c"
Cohesion: 0.67
Nodes (4): c(), o(), p(), s()

### Community 372 - "Y"
Cohesion: 0.11
Nodes (22): at(), Bf(), determineDataLimits(), ef(), getMatchingVisibleMetas(), getMinMax(), _getOtherScale(), getUserBounds() (+14 more)

### Community 379 - "TableQrToken"
Cohesion: 0.09
Nodes (4): TableQrToken, GuestApiTest, KitchenDisplayPageTest, TableOpsServiceTest

### Community 385 - "ManageHomeLanding"
Cohesion: 0.22
Nodes (3): ManageHomeLanding, BackedEnum, UnitEnum

### Community 387 - "y"
Cohesion: 0.18
Nodes (60): b(), Be(), $c(), X(), me(), Cr(), D(), _e() (+52 more)

### Community 388 - "InvoicePaymentTest"
Cohesion: 0.05
Nodes (4): CommandCenterTest, InvoicePaymentTest, TenantResetOwnerPasswordTest, SubscriptionGateTest

### Community 390 - "st"
Cohesion: 0.24
Nodes (11): [g](), _freeze(), getAllExtensions(), Ct(), lt(), ot(), se(), st() (+3 more)

### Community 392 - "replace"
Cohesion: 0.15
Nodes (17): applyChanges(), balanced(), decompose(), decomposeLeft(), decomposeRight(), getReplacement(), heightForGap(), heightForLine() (+9 more)

### Community 394 - "Ae"
Cohesion: 0.67
Nodes (3): Ae(), Bt(), ne()

### Community 397 - "Illuminate\Contracts\View\View"
Cohesion: 0.12
Nodes (8): ExcelExporter, CashierOrderSoundAlert, Head, Slot, Illuminate\Contracts\View\View, Illuminate\View\Component, Maatwebsite\Excel\Concerns\FromView, Maatwebsite\Excel\Concerns\ShouldAutoSize

## Knowledge Gaps
- **345 isolated node(s):** `$schema`, `name`, `type`, `description`, `laravel` (+340 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **51 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `User` connect `User` to `ExportFile`, `InvoicePaymentTest`, `Restaurant`, `Order`, `Illuminate\Database\Eloquent\Model`, `AdminPanelProvider.php`, `.parent`, `RestaurantCategory`, `CashierFilamentActionsTest`, `CmsMedia`, `GraceReadOnlyTest`, `Customer`, `ViewOrder`, `InteractsWithRestaurantAnalytics.php`, `Illuminate\Http\Request`, `UserPolicy`, `ExportFileResource`, `RestaurantReviewApiTest`, `AdSetting`, `Visit`, `TenantIsolationTest`, `BlogPost`, `Dashboard`, `Filament\Schemas\Schema`, `RestaurantAnalyticsPeriod`, `SubscriptionInvoice`, `FilamentTenantTheme`, `RestaurantReview`, `CashierOrderSoundAlertTest`, `RegisterRestaurant`, `ManageSoundNotifications`, `static`, `TableQrToken`, `OrderReceiptPrintTest`?**
  _High betweenness centrality (0.021) - this node is a cross-community bridge._
- **Why does `Restaurant` connect `Restaurant` to `ExportFile`, `InvoicePaymentTest`, `ReportExportBuilder`, `PlatformSetting`, `Order`, `Illuminate\Database\Eloquent\Model`, `AdminPanelProvider.php`, `.parent`, `GuestContext`, `RestaurantCategory`, `CashierFilamentActionsTest`, `CmsMedia`, `GraceReadOnlyTest`, `Customer`, `.count`, `DailyOmzetService`, `InteractsWithRestaurantAnalytics.php`, `Illuminate\Http\Request`, `TenantIsolationTest`, `ExportFileResource`, `Visit`, `SubscriptionLifecycleService`, `LandingMenuCatalogTest`, `BlogPost`, `SubscriptionStatus`, `RestaurantAnalyticsService`, `Illuminate\Http\JsonResponse`, `CashierCommissionBillingService`, `Dashboard`, `Filament\Schemas\Schema`, `RestaurantAnalyticsPeriod`, `SubscriptionInvoice`, `FilamentTenantTheme`, `Illuminate\Http\Resources\Json\JsonResource`, `Carbon\CarbonInterface`, `ManageLandingLayout`, `RegisterRestaurant`, `RestaurantDirectoryTest`, `CommissionReconciliation`, `static`, `User`, `OrderReceiptPrintTest`?**
  _High betweenness centrality (0.020) - this node is a cross-community bridge._
- **Why does `update()` connect `constructor` to `code-editor.js`, `y`, `rich-editor.js`, `replace`, `fromObject`, `find`, `advance`, `.forEach`, `get`, `vd`, `echo.js`, `facet`, `markdown-editor.js`, `te`, `n`, `reduce`, `t`, `_update`, `slice`, `g$`, `fn`, `fd`, `sliceDoc`?**
  _High betweenness centrality (0.019) - this node is a cross-community bridge._
- **What connects `$schema`, `name`, `type` to the rest of the system?**
  _345 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `stat/chart.js` be split into smaller, more focused modules?**
  _Cohesion score 0.02443338861249309 - nodes in this community are weakly interconnected._
- **Should `components/chart.js` be split into smaller, more focused modules?**
  _Cohesion score 0.01160448290537665 - nodes in this community are weakly interconnected._
- **Should `code-editor.js` be split into smaller, more focused modules?**
  _Cohesion score 0.008478741705578767 - nodes in this community are weakly interconnected._