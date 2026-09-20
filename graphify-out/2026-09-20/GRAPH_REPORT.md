# Graph Report - WALK-IN-RESTO  (2026-09-20)

## Corpus Check
- 860 files · ~405,603 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 9773 nodes · 30277 edges · 419 communities (373 shown, 46 thin omitted)
- Extraction: 92% EXTRACTED · 8% INFERRED · 0% AMBIGUOUS · INFERRED: 2515 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `edcdb5cf`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- stat/chart.js
- components/chart.js
- code-editor.js
- rich-editor.js
- ExportFile
- y
- TestCase
- constructor
- Illuminate\Http\Request
- Filament\Tables\Table
- addCommands
- i
- CashierShift
- PlatformSetting
- _update
- Order
- advance
- create
- get
- addElementByRule
- Illuminate\Database\Eloquent\Model
- updateElements
- AdminPanelProvider.php
- TenantContext
- Filament\Resources\Pages\ListRecords
- support.js
- n
- fromObject
- GuestContext
- columns/select.js
- r
- o
- echo.js
- resolve
- fn
- facet
- Role
- constructor
- W
- ae
- Visit
- OrderResource
- Ye
- parse
- cc
- notifications.js
- markdown-editor.js
- SubscriptionAccess
- te
- Cn
- draw
- components/select.js
- fn
- tables.js
- Illuminate\Foundation\Http\FormRequest
- r
- Filament\Resources\Pages\ManageRecords
- SubscriptionStatus
- Xt
- Illuminate\Database\Migrations\Migration
- filament-right-click.js
- addEventListener
- next
- Si
- BlogVisitStatsWidget
- ce
- selectOption
- ProfilePageTest
- dx
- ir
- SubscriptionInvoiceResource
- slider.js
- Restaurant
- closeDropdown
- Filament\Schemas\Schema
- slice
- RefreshesAnalyticsChart.php
- BlogAnalyticsService
- O
- file-upload.js
- st
- E
- Filament\Support\Icons\Heroicon
- RestaurantDirectory
- FonnteErrorMessage
- Illuminate\Database\Schema\Blueprint
- Activity
- BlogComment
- BlogPostObserver
- _update
- devDependencies
- filament/app.js
- Illuminate\Http\Resources\Json\JsonResource
- fn
- eq
- require
- scripts
- .slice
- color-picker.js
- resources/js/app.js
- sliceDoc
- constructor
- composer.json
- order-today-stats-widget.blade.php
- RegisterRestaurant
- date-time-picker.js
- 🚀 3. Langkah-Langkah Menambahkan Template Baru (*Workflow*)
- Illuminate\Support\Collection
- Mt
- getContext
- RestaurantReviewService
- LandingLayout
- actions/actions.js
- g$
- schemas.js
- Landasan Produksi — Tahap 1 (Walk-In Operasional)
- Guest
- GeoDistance
- 2. Masalah di lapangan — dan apa yang sistem selesaikan
- Customer
- require-dev
- BloggerResource.php
- 6. Katalog fitur
- config
- 6. Katalog fitur
- Illuminate\View\View
- register-restaurant.blade.php
- add-to-cart-modal.blade.php
- _each
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
- closeSimpleModeModal
- reduce
- GuestMenuTest
- CashierFilamentActionsTest
- Blogger/Concerns/HandlesTranslatableForm.php
- SubscriptionWriteGuard
- Dashboard
- Filament\Widgets\Concerns\InteractsWithPageFilters
- renderOptions
- s
- configure
- 🎨 Spesifikasi Token & Class CSS yang Tersedia di `theme.css`
- AdSetting
- Filament\Widgets\Widget
- getDatasetMeta
- User
- UserFactory
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
- CreateCashierOrder
- classic/show.blade.php
- Illuminate\Database\Eloquent\Builder
- Y
- S
- A
- rules/graphify.md
- workflows/graphify.md
- HasSingletonForm.php
- glassmorphism/show.blade.php
- Locales
- DiningTable
- selectOption
- _notify
- glassmorphism-background.blade.php
- replace
- dropdown.blade.php
- BlogContentProgressWidget
- FounderRevenueGrowthApexChartWidget
- FounderSubscriptionHealthApexChartWidget
- GraceReadOnlyTest
- Vf
- dismissLoyaltyAlert({{ $loyaltyPoint->order_id }})
- InvoicePaymentTest
- 2026_08_20_010000_add_floor_layout_to_tables_table.php
- Illuminate\Support\Facades\Schema
- Astrotomic\Translatable\Validation\RuleFactory
- layouts/blog.blade.php

## God Nodes (most connected - your core abstractions)
1. `User` - 411 edges
2. `Restaurant` - 354 edges
3. `TestCase` - 199 edges
4. `Order` - 170 edges
5. `constructor()` - 152 edges
6. `update()` - 148 edges
7. `MenuItem` - 114 edges
8. `PlatformSetting` - 114 edges
9. `Visit` - 114 edges
10. `BlogPost` - 102 edges

## Surprising Connections (you probably didn't know these)
- `up()` --calls--> `DiningTable`  [EXTRACTED]
  database/migrations/2026_08_20_010000_add_floor_layout_to_tables_table.php → app/Models/DiningTable.php
- `createGuestRestaurant()` --calls--> `DiningTable`  [EXTRACTED]
  tests/Concerns/CreatesGuestRestaurant.php → app/Models/DiningTable.php
- `extraMenuItem()` --calls--> `MenuItem`  [EXTRACTED]
  tests/Concerns/CreatesGuestRestaurant.php → app/Models/MenuItem.php
- `paidGuestOrder()` --calls--> `Order`  [EXTRACTED]
  tests/Concerns/CreatesGuestRestaurant.php → app/Models/Order.php
- `createGuestRestaurant()` --calls--> `Outlet`  [EXTRACTED]
  tests/Concerns/CreatesGuestRestaurant.php → app/Models/Outlet.php

## Import Cycles
- None detected.

## Communities (419 total, 46 thin omitted)

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
Nodes (171): aa(), addExtensions(), addHackNode(), addTextblockHacks(), an(), applyAspectRatio(), applyConstraints(), atEnd() (+163 more)

### Community 4 - "ExportFile"
Cohesion: 0.03
Nodes (31): ExpireStaleOperationsCommand, FinalizeCashierCommissionCommand, ProcessSubscriptionLifecycleCommand, SendCashierCommissionReminderCommand, TenantForceDeleteCommand, PdfExporter, CleanupOldExportFilesJob, ExportReportJob (+23 more)

### Community 5 - "y"
Cohesion: 0.18
Nodes (49): al(), at(), Be(), Cr(), de(), dt(), Ee(), ef() (+41 more)

### Community 6 - "TestCase"
Cohesion: 0.03
Nodes (61): CashierOrderService, SubscriptionPlanSync, ImageOptimizer, BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles, Bityukov\CommandCenter\Filament\Pages\Commands, Bityukov\CommandCenter\Filament\Pages\History, Carbon\CarbonPeriod, OverdueTenantDemoSeeder (+53 more)

### Community 7 - "constructor"
Cohesion: 0.02
Nodes (142): add(), addChunk(), addEventListener(), addInfoPane(), addInner(), addWindowListeners(), adjust(), al() (+134 more)

### Community 8 - "Illuminate\Http\Request"
Cohesion: 0.10
Nodes (18): CashierShiftPrintController, ApplyRestaurantPanelTheme, EnsureApiGuestVisit, EnsureGuestVisit, EnsureRestaurantOperations, EnsureTenantSubscription, IdentifyApiGuestDevice, IdentifyGuestDevice (+10 more)

### Community 9 - "Filament\Tables\Table"
Cohesion: 0.06
Nodes (28): BlogPostsRelationManager, BloggersTable, CommentsRelationManager, Action, trashPageAction(), ActivitiesTable, CmsFaqResource, ManageCmsFaqs (+20 more)

### Community 10 - "addCommands"
Cohesion: 0.06
Nodes (62): addCommands(), addGlobalAttributes(), addInputRules(), addMark(), addStoredMark(), Ah(), Ax(), blockRange() (+54 more)

### Community 11 - "i"
Cohesion: 0.05
Nodes (80): aa(), applyChanges(), balanced(), baseIndent(), baseIndentFor(), Bg(), bidiSpans(), blockAt() (+72 more)

### Community 12 - "CashierShift"
Cohesion: 0.07
Nodes (6): PendingPaymentsWidget, RestaurantReadinessWidget, CashierShift, CashierShiftService, Illuminate\Database\Eloquent\Collection, DashboardAnalyticsWidgetsTest

### Community 13 - "PlatformSetting"
Cohesion: 0.02
Nodes (22): paymentMixSummary(), WelcomeBannerWidget, PlatformPageController, RestaurantBrandResource, self, PlatformSetting, CashierMenuCatalog, CashierOrderPreview (+14 more)

### Community 14 - "_update"
Cohesion: 0.04
Nodes (106): addBox(), addElements(), adjustHitBoxes(), afterBuildTicks(), afterCalculateLabelRotation(), afterDataLimits(), afterDraw(), afterFit() (+98 more)

### Community 15 - "Order"
Cohesion: 0.03
Nodes (39): analyticsDateFrom(), analyticsDateTo(), analyticsDayCount(), analyticsRangeLabel(), analyticsSnapshot(), canViewAnalytics(), normalizedAnalyticsDateRange(), Carbon (+31 more)

### Community 16 - "advance"
Cohesion: 0.05
Nodes (62): addChild(), addGaps(), addLeafElement(), addNode(), advance(), ATXHeading(), blank(), break() (+54 more)

### Community 17 - "create"
Cohesion: 0.06
Nodes (74): Ac(), append(), bu(), _c(), close(), closeFrontierNode(), cn(), compatibleContent() (+66 more)

### Community 18 - "get"
Cohesion: 0.04
Nodes (87): addBlockWidget(), addBreak(), addComposition(), addDelimiter(), addInlineWidget(), addLine(), addLineStart(), addLineStartIfNotCovered() (+79 more)

### Community 19 - "addElementByRule"
Cohesion: 0.11
Nodes (31): addAll(), addDOM(), addElement(), addElementByRule(), addTextNode(), addToSet(), allowsMarks(), allowsMarkType() (+23 more)

### Community 20 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.01
Nodes (57): canDelete(), canForceDelete(), canRestore(), RestaurantMenuController, BlogCategoryTranslation, BlogHeroSettingTranslation, BlogTagTranslation, CashierShiftMovement (+49 more)

### Community 21 - "updateElements"
Cohesion: 0.03
Nodes (113): aa(), acquireContext(), afterAutoSkip(), Ao(), aspectRatio(), bh(), bu(), buildLookupTable() (+105 more)

### Community 22 - "AdminPanelProvider.php"
Cohesion: 0.05
Nodes (42): Login, Dashboard, EditProfile, FilamentProfilePlugin, ProfileInformationForm, ApplyPlatformBrandTheme, SetPermissionsTeamId, AdminPanelProvider (+34 more)

### Community 23 - "TenantContext"
Cohesion: 0.04
Nodes (24): ExcelExporter, ManageAdSettings, BackedEnum, UnitEnum, ManageHomeLanding, BackedEnum, UnitEnum, ListCustomerReviews (+16 more)

### Community 24 - "Filament\Resources\Pages\ListRecords"
Cohesion: 0.03
Nodes (34): ListBlogPosts, FacilityResource, CreateFacility, EditFacility, ListFacilities, LandingTemplateResource, CreateLandingTemplate, EditLandingTemplate (+26 more)

### Community 25 - "support.js"
Cohesion: 0.05
Nodes (75): acquireScrollLock(), ai(), e(), Bi(), br(), Bt(), ca(), close() (+67 more)

### Community 26 - "n"
Cohesion: 0.09
Nodes (79): _a(), Ae(), ar(), as(), bc(), ee(), ue(), u() (+71 more)

### Community 27 - "fromObject"
Cohesion: 0.03
Nodes (109): El(), ac(), ae(), after(), Al(), Am(), before(), bl() (+101 more)

### Community 28 - "GuestContext"
Cohesion: 0.07
Nodes (13): CartController, OrderController, TableController, VisitController, VisitResource, GuestCart, GuestCheckout, GuestMenu (+5 more)

### Community 29 - "columns/select.js"
Cohesion: 0.06
Nodes (57): A(), An(), applyDisabledState(), b(), Bt(), Ce(), D(), disable() (+49 more)

### Community 30 - "r"
Cohesion: 0.04
Nodes (139): _0(), addNodeView(), addOptions(), addPasteRules(), addProseMirrorPlugins(), af(), allowedMarks(), au() (+131 more)

### Community 31 - "o"
Cohesion: 0.04
Nodes (121): ag(), ah(), apply(), ar(), au(), average(), Ba(), beforeDatasetDraw() (+113 more)

### Community 32 - "echo.js"
Cohesion: 0.05
Nodes (49): a(), ar(), b(), Be(), Ce(), cr(), d(), De() (+41 more)

### Community 33 - "resolve"
Cohesion: 0.05
Nodes (128): addKeyboardShortcuts(), addNodeMark(), after(), ag(), al(), AS(), before(), Bs() (+120 more)

### Community 34 - "fn"
Cohesion: 0.13
Nodes (33): aa(), At(), ba(), cr(), da(), de(), dt(), ei() (+25 more)

### Community 35 - "facet"
Cohesion: 0.04
Nodes (88): accept(), active(), applyTransaction(), asSingle(), B(), baseTheme(), between(), blur() (+80 more)

### Community 36 - "Role"
Cohesion: 0.04
Nodes (11): Role, Spatie\Permission\DefaultTeamResolver, makeOwner(), makeStaff(), AnalyticsChartWidgetRenderTest, DashboardHttpSmokeTest, KitchenDisplayPageTest, OrderReceiptPrintTest (+3 more)

### Community 37 - "constructor"
Cohesion: 0.03
Nodes (85): Bc(), bg(), chartOptionScopes(), Cl(), clone(), constructor(), create(), Ct() (+77 more)

### Community 38 - "W"
Cohesion: 0.05
Nodes (79): AQ(), atLastNode(), au(), child(), childAfter(), childBefore(), continue(), cursor() (+71 more)

### Community 39 - "ae"
Cohesion: 0.09
Nodes (34): ae(), Ao(), as(), B(), Kt(), cs(), Ee(), Ge() (+26 more)

### Community 40 - "Visit"
Cohesion: 0.02
Nodes (36): ExportFileDownloadController, CashierOrderSoundAlert, getUnclaimedLoyaltyPoint(), GuestPay, GuestStatus, ScanTable, RestaurantMenuCatalog, Payment (+28 more)

### Community 41 - "OrderResource"
Cohesion: 0.12
Nodes (3): OrderResource, ListOrders, OrderTodayStatsWidget

### Community 42 - "Ye"
Cohesion: 0.10
Nodes (43): Rd(), $a(), ak(), at(), bk(), c(), bp(), Dk() (+35 more)

### Community 43 - "parse"
Cohesion: 0.06
Nodes (54): buildOrUpdateElements(), Cn(), determineDataLimits(), diff(), dn(), el(), En(), endOf() (+46 more)

### Community 44 - "cc"
Cohesion: 0.22
Nodes (11): attrs(), bi(), cc(), cO(), JQ(), m$(), Ow(), rc() (+3 more)

### Community 45 - "notifications.js"
Cohesion: 0.06
Nodes (31): actions(), button(), c(), close(), configureAnimations(), configureTransitions(), constructor(), danger() (+23 more)

### Community 46 - "markdown-editor.js"
Cohesion: 0.05
Nodes (83): ad(), af(), An(), bf(), bo(), Bt(), cd(), Ct() (+75 more)

### Community 47 - "SubscriptionAccess"
Cohesion: 0.05
Nodes (18): canCreate(), canEdit(), canViewAny(), CustomerAnalytics, CustomerSatisfactionAnalytics, GenerateReport, BackedEnum, UnitEnum (+10 more)

### Community 48 - "te"
Cohesion: 0.05
Nodes (9): Bn(), br(), ji(), on(), qd(), Ri(), te(), Vi() (+1 more)

### Community 49 - "Cn"
Cohesion: 0.13
Nodes (46): Cn(), b(), Be(), Ce(), De(), dn(), _e(), Fe() (+38 more)

### Community 50 - "draw"
Cohesion: 0.09
Nodes (34): addElements(), bi(), bindEvents(), bindUserEvents(), buildOrUpdateScales(), _checkEventBindings(), clear(), _dataCheck() (+26 more)

### Community 51 - "components/select.js"
Cohesion: 0.08
Nodes (34): b(), Bt(), D(), E(), en(), Et(), getLabelsForMultipleSelection(), getSelectedOptionLabels() (+26 more)

### Community 52 - "fn"
Cohesion: 0.15
Nodes (22): themeClasses(), Ck(), De(), fn(), Gh(), ip(), Ja(), Jh() (+14 more)

### Community 53 - "tables.js"
Cohesion: 0.13
Nodes (44): A(), ae(), B(), be(), C(), ce(), E(), ee() (+36 more)

### Community 54 - "Illuminate\Foundation\Http\FormRequest"
Cohesion: 0.06
Nodes (12): BlogCommentController, AddCartItemRequest, CheckoutRequest, ClaimTableRequest, JoinTableRequest, StorePaymentProofRequest, UpdateCartItemRequest, StoreBlogCommentRequest (+4 more)

### Community 55 - "r"
Cohesion: 0.15
Nodes (43): _a(), ar(), c(), f(), d(), di(), g(), Hi() (+35 more)

### Community 56 - "Filament\Resources\Pages\ManageRecords"
Cohesion: 0.05
Nodes (18): CmsBannerResource, ManageCmsBanners, TrashCmsBanners, CmsGalleryImageResource, ManageCmsGalleryImages, TrashCmsGalleryImages, KdsStationResource, ManageKdsStations (+10 more)

### Community 57 - "SubscriptionStatus"
Cohesion: 0.12
Nodes (3): BackedEnum, UnitEnum, SubscriptionStatus

### Community 58 - "Xt"
Cohesion: 0.12
Nodes (44): ae(), At(), bi(), bn(), ci(), cn(), ct(), de() (+36 more)

### Community 60 - "filament-right-click.js"
Cohesion: 0.11
Nodes (42): a(), ae(), b(), be(), C(), ce(), D(), de() (+34 more)

### Community 61 - "addEventListener"
Cohesion: 0.33
Nodes (7): addEventListener(), bindResponsiveEvents(), fu(), isAttached(), nr(), removeEventListener(), Ua()

### Community 62 - "next"
Cohesion: 0.08
Nodes (33): activeForPoint(), addActive(), addBlock(), addLineDeco(), Ar(), as(), blankContent(), boundChange() (+25 more)

### Community 63 - "Si"
Cohesion: 0.14
Nodes (40): ae(), At(), bi(), bn(), ci(), cn(), ct(), de() (+32 more)

### Community 64 - "BlogVisitStatsWidget"
Cohesion: 0.24
Nodes (3): Dashboard, BlogVisitStatsWidget, Filament\Pages\Dashboard\Concerns\HasFiltersForm

### Community 65 - "ce"
Cohesion: 0.08
Nodes (46): Ac(), ao(), bl(), Cc(), ce(), cl(), Cn(), Dc() (+38 more)

### Community 66 - "selectOption"
Cohesion: 0.12
Nodes (39): addBadgesForSelectedOptions(), addSingleBadge(), addSingleSelectionDisplay(), closeDropdown(), constructor(), createBadgeElement(), createOptionElement(), createRemoveButton() (+31 more)

### Community 68 - "dx"
Cohesion: 0.09
Nodes (38): Ei(), Aa(), ai(), Ba(), Bi(), cf(), da(), fa() (+30 more)

### Community 69 - "ir"
Cohesion: 0.11
Nodes (49): Ft(), ir(), ae(), A(), E(), at(), be(), ce() (+41 more)

### Community 70 - "SubscriptionInvoiceResource"
Cohesion: 0.05
Nodes (14): BlogReferrersWidget, TopBloggersWidget, TopBlogPostsWidget, CreateSubscriptionInvoice, ViewSubscriptionInvoice, SubscriptionInvoiceResource, CreateTenant, EditTenant (+6 more)

### Community 71 - "slider.js"
Cohesion: 0.09
Nodes (38): Ae(), ar(), Be(), Bt(), Ce(), De(), _e(), Ee() (+30 more)

### Community 72 - "Restaurant"
Cohesion: 0.02
Nodes (38): GenerateUpcomingInvoicesCommand, CheckoutController, SessionController, RestaurantController, RestaurantReviewController, Controller, RestaurantLandingController, RestaurantResource (+30 more)

### Community 73 - "closeDropdown"
Cohesion: 0.23
Nodes (17): applyDisabledState(), closeDropdown(), constructor(), destroy(), disable(), enable(), focusNextOption(), focusPreviousOption() (+9 more)

### Community 74 - "Filament\Schemas\Schema"
Cohesion: 0.04
Nodes (22): BlogAnalytics, BlogCategoryInfolist, BlogCommentForm, BlogCommentInfolist, BlogPostForm, BlogPostInfolist, BlogTagInfolist, ManageBillingAccount (+14 more)

### Community 75 - "slice"
Cohesion: 0.04
Nodes (133): a$(), activateHover(), addChanges(), addElement(), Ah(), AX(), b1(), balance() (+125 more)

### Community 76 - "RefreshesAnalyticsChart.php"
Cohesion: 0.42
Nodes (7): generateChartDataChecksum(), getCachedChartData(), getChartData(), mountRefreshesAnalyticsChart(), refreshAnalyticsChartData(), rendering(), updateChartData()

### Community 77 - "BlogAnalyticsService"
Cohesion: 0.19
Nodes (4): VisitLog, BlogAnalyticsService, Carbon\Carbon, Illuminate\Database\Eloquent\Relations\MorphTo

### Community 78 - "O"
Cohesion: 0.19
Nodes (38): b(), $c(), X(), ca(), me(), D(), _e(), Ea() (+30 more)

### Community 79 - "file-upload.js"
Cohesion: 0.05
Nodes (53): hc(), Bp(), c(), ca(), clickPercent(), constructor(), Cp(), da() (+45 more)

### Community 80 - "st"
Cohesion: 0.05
Nodes (48): ad(), applyStack(), br(), Di(), drawCaret(), _f(), first(), getCaretPosition() (+40 more)

### Community 81 - "E"
Cohesion: 0.05
Nodes (61): $a(), add(), af(), B(), bo(), bs(), ca(), _cachedScopes() (+53 more)

### Community 82 - "Filament\Support\Icons\Heroicon"
Cohesion: 0.09
Nodes (50): SeoFields, TranslationTabs, ManageBlogHero, BlogHeroFormSchema, BackedEnum, Filament\Actions\Action, Filament\Forms\Components\CheckboxList, Filament\Forms\Components\ColorPicker (+42 more)

### Community 84 - "FonnteErrorMessage"
Cohesion: 0.11
Nodes (8): FonnteClient, FonnteErrorMessage, Throwable, Illuminate\Http\Client\ConnectionException, Illuminate\Http\Client\Response, PHPUnit\Framework\Attributes\DataProvider, RuntimeException, FonnteErrorMessageTest

### Community 87 - "Activity"
Cohesion: 0.10
Nodes (7): ActivityResource, ListActivities, ViewActivity, ActivityInfolist, Activity, ActivityPresenter, Spatie\Activitylog\Models\Activity

### Community 88 - "BlogComment"
Cohesion: 0.07
Nodes (10): BlogCommentResource, CreateBlogComment, EditBlogComment, ListBlogComments, ViewBlogComment, BlogCommentsTable, BlogComment, BlogCommentObserver (+2 more)

### Community 90 - "_update"
Cohesion: 0.05
Nodes (63): active(), afterBuildTicks(), afterCalculateLabelRotation(), afterDataLimits(), afterDatasetsUpdate(), afterFit(), afterSetDimensions(), afterTickToLabelConversion() (+55 more)

### Community 91 - "devDependencies"
Cohesion: 0.09
Nodes (21): apexcharts, axios, concurrently, laravel-vite-plugin, dependencies, apexcharts, devDependencies, axios (+13 more)

### Community 92 - "filament/app.js"
Cohesion: 0.13
Nodes (8): B(), close(), G(), init(), P(), setUpResizeObserver(), x(), Y()

### Community 93 - "Illuminate\Http\Resources\Json\JsonResource"
Cohesion: 0.08
Nodes (13): MenuController, CartItemResource, CartResource, MenuCategoryResource, MenuItemResource, MenuVariantResource, ModifierGroupResource, ModifierResource (+5 more)

### Community 94 - "fn"
Cohesion: 0.22
Nodes (18): An(), Ce(), ei(), fn(), Ft(), Ie(), Le(), ni() (+10 more)

### Community 95 - "eq"
Cohesion: 0.09
Nodes (35): addNode(), ao(), dd(), destroyBetween(), destroyRest(), eq(), ey(), findIndexWithChild() (+27 more)

### Community 96 - "require"
Cohesion: 0.12
Nodes (17): require, astrotomic/laravel-translatable, barryvdh/laravel-dompdf, bezhansalleh/filament-shield, endroid/qr-code, filament/filament, hammadzafar05/filament-mobile-preset, ipatco/filament-profile (+9 more)

### Community 97 - "scripts"
Cohesion: 0.12
Nodes (16): scripts, dev, post-autoload-dump, post-create-project-cmd, post-root-package-install, post-update-cmd, Composer\\Config::disableProcessTimeout, Illuminate\\Foundation\\ComposerScripts::postAutoloadDump (+8 more)

### Community 98 - ".slice"
Cohesion: 0.05
Nodes (68): accepts(), addAttributes(), addInner(), addMaps(), addStep(), addTransform(), appendMap(), appendMapping() (+60 more)

### Community 99 - "color-picker.js"
Cohesion: 0.11
Nodes (8): [g](), style(), update(), [x](), _freeze(), getAllExtensions(), st(), zt()

### Community 100 - "resources/js/app.js"
Cohesion: 0.12
Nodes (6): applyTheme(), bindThemeToggle(), initReveal(), initTheme(), syncThemeToggleIcons(), toggleTheme()

### Community 101 - "sliceDoc"
Cohesion: 0.15
Nodes (19): aO(), charCategorizer(), Fc(), flatten(), getCursor(), getDeco(), gT(), highlight() (+11 more)

### Community 102 - "constructor"
Cohesion: 0.03
Nodes (141): Ad(), add(), applyInitialSize(), ay(), Bd(), Bg(), bl(), bt() (+133 more)

### Community 103 - "composer.json"
Cohesion: 0.14
Nodes (13): autoload-dev, psr-4, description, keywords, license, minimum-stability, name, prefer-stable (+5 more)

### Community 107 - "date-time-picker.js"
Cohesion: 0.29
Nodes (7): d(), e(), i(), m(), r(), s(), t()

### Community 108 - "🚀 3. Langkah-Langkah Menambahkan Template Baru (*Workflow*)"
Cohesion: 0.12
Nodes (15): 📌 1. Prinsip Utama & Aturan Wajib (Golden Rules), 🏗️ 2. Arsitektur 10 Core Section CMS (1:1 Mapping), 🚀 3. Langkah-Langkah Menambahkan Template Baru (*Workflow*), 📊 4. Variabel yang Disediakan oleh `LandingPageDataService`, 💎 5. Pelajaran Desain & Best Practices UI/UX (*Key Learnings*), A. Larangan Keras Menggunakan Emoji Unicode (Gunakan SVG Heroicons), B. Arsitektur Layering 3D & Background Tembus Pandang (*Stacking Context*), C. Resep Glassmorphism iOS yang Nyata (*True Frosted Glass*) (+7 more)

### Community 109 - "Illuminate\Support\Collection"
Cohesion: 0.04
Nodes (10): periodSummary(), Facility, RestaurantCategory, RestaurantDirectory, FacilitySeeder, RestaurantCategorySeeder, Illuminate\Contracts\Pagination\LengthAwarePaginator, Illuminate\Support\Collection (+2 more)

### Community 110 - "Mt"
Cohesion: 0.24
Nodes (11): apply(), fs(), go(), Hr(), T(), ir(), it(), Mt() (+3 more)

### Community 111 - "getContext"
Cohesion: 0.07
Nodes (52): acquireContext(), Ao(), bl(), buildTicks(), Ca(), calculateLabelRotation(), _calculatePadding(), ci() (+44 more)

### Community 112 - "RestaurantReviewService"
Cohesion: 0.10
Nodes (6): ReviewController, StoreRestaurantReviewRequest, RestaurantReviewResource, GuestReview, RestaurantReviewService, VisitReviewStatus

### Community 113 - "LandingLayout"
Cohesion: 0.11
Nodes (3): LandingLayout, self, Illuminate\Support\Arr

### Community 115 - "actions/actions.js"
Cohesion: 0.44
Nodes (8): closeModal(), generateModalId(), getActionNestingIndexFromModalId(), init(), openModal(), rememberPreviouslyFocusedElement(), restorePreviouslyFocusedElement(), syncActionModals()

### Community 116 - "g$"
Cohesion: 0.07
Nodes (45): acceptToken(), allows(), bd(), Bh(), clearDelayedAndroidKey(), d0(), De(), delayAndroidKey() (+37 more)

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

### Community 122 - "Customer"
Cohesion: 0.05
Nodes (7): Customer, CustomerLoyaltyPoint, CustomerCrmService, CustomerSatisfactionDemoSeeder, CustomerCrmLoyaltyTest, GuestLoyaltyAndMemberCardTest, GuestScanTableTest

### Community 123 - "require-dev"
Cohesion: 0.25
Nodes (8): require-dev, fakerphp/faker, laravel/pail, laravel/pint, laravel/sail, mockery/mockery, nunomaduro/collision, phpunit/phpunit

### Community 124 - "BloggerResource.php"
Cohesion: 0.11
Nodes (7): BloggerResource, CreateBlogger, EditBlogger, ListBloggers, ViewBlogger, BloggerForm, BloggerInfolist

### Community 125 - "6. Katalog fitur"
Cohesion: 0.06
Nodes (33): 1. Latar belakang, 2. Rumusan masalah, 3.1 Tujuan umum, 3.2 Tujuan khusus, 3. Tujuan, 4.1 Bagi restoran (owner, kasir, dapur), 4.2 Bagi tamu, 4.3 Bagi pengelola platform (+25 more)

### Community 127 - "config"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

### Community 128 - "6. Katalog fitur"
Cohesion: 0.06
Nodes (32): 1. Latar belakang, 2. Rumusan masalah, 3.1 Tujuan umum, 3.2 Tujuan khusus, 3. Tujuan, 4.1 Bagi restoran (owner, kasir, dapur), 4.2 Bagi tamu, 4.3 Bagi pengelola platform (+24 more)

### Community 129 - "Illuminate\View\View"
Cohesion: 0.16
Nodes (7): BlogController, BlogLikeController, BlogLike, BlogLikeService, RecordVisitService, VisitorHash, Illuminate\View\View

### Community 130 - "register-restaurant.blade.php"
Cohesion: 0.15
Nodes (12): applyColorPreset(, back, nextFromAccount, nextFromPlan, nextFromRestaurant, nextFromVisual, register, $set( (+4 more)

### Community 131 - "add-to-cart-modal.blade.php"
Cohesion: 0.29
Nodes (6): cancelPicking, confirmAdd, decrementPickingQty, incrementPickingQty, setVariant({{ $variant->id }}), toggleModifier({{ $modifier->id }})

### Community 132 - "_each"
Cohesion: 0.12
Nodes (17): addControllers(), addPlugins(), addScales(), _each(), _exec(), _getRegistryForType(), invalidate(), isForType() (+9 more)

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

### Community 157 - "reduce"
Cohesion: 0.06
Nodes (62): addActions(), advanceFully(), advanceStack(), allActions(), apply(), c0(), canShift(), checkAsyncSchedule() (+54 more)

### Community 163 - "Blogger/Concerns/HandlesTranslatableForm.php"
Cohesion: 0.04
Nodes (36): afterCreate(), afterSave(), ensureAtLeastOneTranslation(), extractTranslations(), hasTranslatableContent(), mutateFormDataBeforeCreate(), mutateFormDataBeforeSave(), persistTranslations() (+28 more)

### Community 164 - "SubscriptionWriteGuard"
Cohesion: 0.28
Nodes (3): BlockGraceMutations, SubscriptionWriteGuard, Livewire\ComponentHook

### Community 170 - "Filament\Widgets\Concerns\InteractsWithPageFilters"
Cohesion: 0.17
Nodes (4): BlogAudienceWidget, BlogCategoryDistributionWidget, BlogTrafficApexChartWidget, Filament\Widgets\Concerns\InteractsWithPageFilters

### Community 171 - "renderOptions"
Cohesion: 0.37
Nodes (13): createOptionElement(), deferPositionDropdown(), filterOptions(), handleSearch(), hideLoadingState(), openDropdown(), populateLabelRepositoryFromOptions(), positionDropdown() (+5 more)

### Community 173 - "s"
Cohesion: 0.05
Nodes (62): aa(), addEventListener(), Ae(), ai(), al(), an(), _animateOptions(), bindResponsiveEvents() (+54 more)

### Community 174 - "configure"
Cohesion: 0.06
Nodes (45): add(), _cachedScopes(), configure(), createResolver(), datasetAnimationScopeKeys(), datasetElementScopeKeys(), datasetScopeKeys(), get() (+37 more)

### Community 176 - "🎨 Spesifikasi Token & Class CSS yang Tersedia di `theme.css`"
Cohesion: 0.15
Nodes (12): 1. Kartu Kontainer Utama (`.vision-card`), 2. Kotak Ikon Gradien Neon (`.vision-icon-box`), 3. Kapsul Metrik / Sub-Kartu (`.vision-pill-card` atau `.vision-pill-dark`), 4. Kartu Filter (`.vision-filter-card`), 5. Tabel Data Kaca (`.vision-table-card`), 6. Form Section & Skema Input Kaca (`.vision-card` pada `Section::make`), 7. Kartu Statistik Global (`StatsOverviewWidget` / `Stat::make`), ⚙️ Aturan Wajib untuk AI Developer (+4 more)

### Community 177 - "AdSetting"
Cohesion: 0.06
Nodes (7): AdSetting, self, AdPlacementService, Head, Slot, Illuminate\View\Component, AdIntegrationTest

### Community 179 - "Filament\Widgets\Widget"
Cohesion: 0.11
Nodes (11): AnalyticsKpiWidget, AnalyticsPeriodSummaryWidget, AnalyticsRevenueBarWidget, AnalyticsSidebarWidget, AnalyticsTopMenuWidget, analyticsTheme(), formatKpiDelta(), makeKpiCard() (+3 more)

### Community 180 - "getDatasetMeta"
Cohesion: 0.11
Nodes (26): afterDatasetsUpdate(), An(), generateLabels(), getDatasetMeta(), getDataVisibility(), _getLegendItemAt(), getMaxBorderWidth(), _getSortedDatasetMetas() (+18 more)

### Community 183 - "User"
Cohesion: 0.01
Nodes (46): SitemapController, BlogCategory, BlogHeroSetting, self, BlogPost, BlogPostTranslation, BlogTag, HasMany (+38 more)

### Community 185 - "UserFactory"
Cohesion: 0.47
Nodes (3): static, UserFactory, Illuminate\Database\Eloquent\Factories\Factory

### Community 186 - "pay.blade.php"
Cohesion: 0.50
Nodes (3): removeProof, guest.partials.loyalty-earned-modal, guest.partials.nav

### Community 193 - "restaurant-menu-catalog.blade.php"
Cohesion: 0.25
Nodes (7): landing.templates.foodie.sections.footer, landing.templates.foodie.sections.header, landing.templates.glassmorphism.partials.background, landing.templates.glassmorphism.sections.footer, landing.templates.glassmorphism.sections.header, partials.customer.landing-footer, partials.customer.landing-header

### Community 295 - "3. Detail Implementasi Perbaikan Keamanan"
Cohesion: 0.17
Nodes (11): 1. Ringkasan Eksekutif (Executive Summary), 2. Matriks Temuan & Status Perbaikan (Findings & Remediation Matrix), 3. Detail Implementasi Perbaikan Keamanan, 4. Hasil Verifikasi Pengujian Otomatis, A. Proteksi `qr_secret` pada Model (`SEC-01`), B. Middleware HTTP Security Headers (`SEC-02`), C. Pengetatan CORS & Session Cookie (`SEC-03` & `SEC-05`), D. Sanitasi File Upload (`SEC-06`) (+3 more)

### Community 296 - "foodie/show.blade.php"
Cohesion: 0.33
Nodes (5): landing.templates.foodie.sections., landing.templates.foodie.sections.hero, landing.sections., landing.templates.foodie.sections.footer, landing.templates.foodie.sections.header

### Community 297 - "CreateCashierOrder"
Cohesion: 0.07
Nodes (7): CreateCashierOrder, BackedEnum, UnitEnum, Width, ViewOrder, IdrAmount, IdrAmountTest

### Community 301 - "classic/show.blade.php"
Cohesion: 0.50
Nodes (3): landing.sections., partials.customer.landing-footer, partials.customer.landing-header

### Community 302 - "Illuminate\Database\Eloquent\Builder"
Cohesion: 0.04
Nodes (33): getRecordRouteBindingEloquentQuery(), CommissionReconciliation, BackedEnum, UnitEnum, Width, KitchenDisplay, BackedEnum, UnitEnum (+25 more)

### Community 304 - "Y"
Cohesion: 0.11
Nodes (22): at(), Bf(), determineDataLimits(), ef(), getMatchingVisibleMetas(), getMinMax(), _getOtherScale(), getUserBounds() (+14 more)

### Community 306 - "S"
Cohesion: 0.10
Nodes (28): afterAutoSkip(), Bt(), buildLookupTable(), da(), drawTitle(), Ds(), Fs(), getBasePixel() (+20 more)

### Community 307 - "A"
Cohesion: 0.07
Nodes (38): Ot(), A(), apply(), As(), chartOptionScopes(), _computeLabelSizes(), constructor(), cr() (+30 more)

### Community 318 - "HasSingletonForm.php"
Cohesion: 0.18
Nodes (15): content(), defaultForm(), fillForm(), getFormActions(), getFormContentComponent(), getRecord(), getRedirectUrl(), getSavedNotificationTitle() (+7 more)

### Community 322 - "glassmorphism/show.blade.php"
Cohesion: 0.29
Nodes (6): landing.templates.glassmorphism.sections., landing.templates.glassmorphism.sections.hero, landing.sections., landing.templates.glassmorphism.partials.background, landing.templates.glassmorphism.sections.footer, landing.templates.glassmorphism.sections.header

### Community 324 - "Locales"
Cohesion: 0.08
Nodes (17): mutateFormDataBeforeFill(), afterCreate(), afterSave(), ensureAtLeastOneTranslation(), extractTranslations(), hasTranslatableContent(), mutateFormDataBeforeCreate(), mutateFormDataBeforeFill() (+9 more)

### Community 325 - "DiningTable"
Cohesion: 0.05
Nodes (7): DiningTable, LogOptions, TableFloorPlan, TableQrToken, SecurityHardeningTest, StaleOperationsServiceTest, TableFloorPlanTest

### Community 327 - "selectOption"
Cohesion: 0.24
Nodes (12): addBadgesForSelectedOptions(), addSingleBadge(), addSingleSelectionDisplay(), createBadgeElement(), createRemoveButton(), getLabelForSingleSelection(), getSelectedOptionLabel(), hideMaxItemsMessage() (+4 more)

### Community 340 - "_notify"
Cohesion: 0.20
Nodes (14): active(), _animateOptions(), cancel(), _createAnimations(), _createDescriptors(), _descriptors(), _notify(), _notifyStateChanges() (+6 more)

### Community 347 - "replace"
Cohesion: 0.07
Nodes (36): addToSet(), childString(), decompose(), decomposeLeft(), decomposeRight(), flushIOSKey(), FO(), getReplacement() (+28 more)

### Community 368 - "Vf"
Cohesion: 0.33
Nodes (7): contains(), gi(), splitAt(), toISOTime(), toMillis(), Vf(), ye()

## Knowledge Gaps
- **349 isolated node(s):** `$schema`, `name`, `type`, `description`, `laravel` (+344 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **46 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `update()` connect `constructor` to `stat/chart.js`, `code-editor.js`, `i`, `advance`, `get`, `n`, `fromObject`, `reduce`, `echo.js`, `resolve`, `facet`, `W`, `Ye`, `markdown-editor.js`, `te`, `next`, `dx`, `slice`, `O`, `replace`, `.slice`, `sliceDoc`, `constructor`, `g$`?**
  _High betweenness centrality (0.028) - this node is a cross-community bridge._
- **Why does `Restaurant` connect `Restaurant` to `ExportFile`, `TestCase`, `Illuminate\Http\Request`, `Filament\Tables\Table`, `CashierShift`, `PlatformSetting`, `Order`, `Illuminate\Database\Eloquent\Model`, `AdminPanelProvider.php`, `TenantContext`, `Filament\Resources\Pages\ListRecords`, `CashierFilamentActionsTest`, `Role`, `Dashboard`, `Visit`, `Illuminate\Database\Eloquent\Builder`, `SubscriptionAccess`, `Filament\Widgets\Widget`, `User`, `SubscriptionStatus`, `DiningTable`, `SubscriptionInvoiceResource`, `Filament\Schemas\Schema`, `Filament\Support\Icons\Heroicon`, `GraceReadOnlyTest`, `RegisterRestaurant`, `Illuminate\Support\Collection`, `LandingLayout`, `Customer`?**
  _High betweenness centrality (0.025) - this node is a cross-community bridge._
- **Why does `User` connect `User` to `ExportFile`, `TestCase`, `Illuminate\Http\Request`, `Filament\Tables\Table`, `CashierShift`, `PlatformSetting`, `Order`, `Illuminate\Database\Eloquent\Model`, `AdminPanelProvider.php`, `Filament\Resources\Pages\ListRecords`, `CashierFilamentActionsTest`, `Blogger/Concerns/HandlesTranslatableForm.php`, `Role`, `Visit`, `Dashboard`, `Filament\Widgets\Concerns\InteractsWithPageFilters`, `Illuminate\Database\Eloquent\Builder`, `SubscriptionAccess`, `AdSetting`, `UserFactory`, `BlogVisitStatsWidget`, `DiningTable`, `SubscriptionInvoiceResource`, `Restaurant`, `Filament\Schemas\Schema`, `Filament\Support\Icons\Heroicon`, `BlogComment`, `BlogContentProgressWidget`, `GraceReadOnlyTest`, `RegisterRestaurant`, `Illuminate\Support\Collection`, `Customer`, `BloggerResource.php`?**
  _High betweenness centrality (0.022) - this node is a cross-community bridge._
- **What connects `$schema`, `name`, `type` to the rest of the system?**
  _349 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `stat/chart.js` be split into smaller, more focused modules?**
  _Cohesion score 0.024135681669928244 - nodes in this community are weakly interconnected._
- **Should `components/chart.js` be split into smaller, more focused modules?**
  _Cohesion score 0.01160448290537665 - nodes in this community are weakly interconnected._
- **Should `code-editor.js` be split into smaller, more focused modules?**
  _Cohesion score 0.008813949032381682 - nodes in this community are weakly interconnected._