# Graph Report - WALK-IN-RESTO  (2026-09-17)

## Corpus Check
- 852 files · ~397,007 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 9700 nodes · 30131 edges · 416 communities (368 shown, 48 thin omitted)
- Extraction: 91% EXTRACTED · 9% INFERRED · 0% AMBIGUOUS · INFERRED: 2589 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `853cc765`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- stat/chart.js
- components/chart.js
- code-editor.js
- rich-editor.js
- ExportFile
- Pe
- TestCase
- constructor
- MenuItem
- fromObject
- lo
- find
- Filament\Tables\Table
- PlatformSetting
- _update
- Order
- advance
- r
- get
- o
- Illuminate\Database\Eloquent\Relations\BelongsTo
- Illuminate\Foundation\Http\FormRequest
- AdminPanelProvider.php
- Illuminate\Database\Eloquent\Builder
- Filament\Resources\Pages\ListRecords
- support.js
- n
- AppServiceProvider.php
- GuestContext
- columns/select.js
- e
- updateElements
- echo.js
- resolve
- fn
- facet
- Illuminate\Http\Request
- y
- prop
- ae
- Customer
- eq
- Ye
- EditProfile
- Activity
- notifications.js
- markdown-editor.js
- CommissionReconciliation
- te
- Cn
- Filament\Widgets\Concerns\InteractsWithPageFilters
- components/select.js
- ManageSoundNotifications
- tables.js
- constructor
- r
- SoftDeleteTrashPage
- SubscriptionStatus
- Xt
- Illuminate\Database\Migrations\Migration
- filament-right-click.js
- constructor
- t
- Si
- s
- configure
- selectOption
- reduce
- getContext
- ir
- BlogReferrersWidget
- slider.js
- Restaurant
- selectOption
- Filament\Schemas\Schema
- slice
- RefreshesAnalyticsChart.php
- E
- st
- file-upload.js
- _update
- fo
- Illuminate\Http\JsonResponse
- RestaurantDirectory
- FonnteErrorMessage
- Illuminate\Database\Schema\Blueprint
- CashierOrderSoundAlertTest
- BlogPost
- A
- c
- devDependencies
- filament/app.js
- createResolver
- fn
- TenantContext
- require
- scripts
- .slice
- color-picker.js
- resources/js/app.js
- sliceDoc
- create
- composer.json
- order-today-stats-widget.blade.php
- RegisterRestaurant
- date-time-picker.js
- 🚀 3. Langkah-Langkah Menambahkan Template Baru (*Workflow*)
- Dashboard
- Mt
- TableQrToken
- getDatasetMeta
- Y
- .parent
- actions/actions.js
- fd
- schemas.js
- Landasan Produksi — Tahap 1 (Walk-In Operasional)
- Guest
- GeoDistance
- 2. Masalah di lapangan — dan apa yang sistem selesaikan
- S
- require-dev
- LandingLayout
- 6. Katalog fitur
- config
- 6. Katalog fitur
- toString
- register-restaurant.blade.php
- add-to-cart-modal.blade.php
- web.php
- components/actions.js
- psr-4
- extra
- logging.php
- constructor
- selectRecords
- replace
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
- _notify
- closeSimpleModeModal
- GuestMenu
- Illuminate\Database\Eloquent\Model
- LandingMenuCatalogTest
- CashierFilamentActionsTest
- Filament\Widgets\Widget
- GraceReadOnlyTest
- CashierOrderPreview
- ViewOrder
- StaleOperationsServiceTest
- ManageDiningTables
- CheckoutTotals
- CashTender
- 🎨 Spesifikasi Token & Class CSS yang Tersedia di `theme.css`
- AdSetting
- CmsMedia
- Dashboard
- User
- ScanTable
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
- g$
- addEventListener
- rules/graphify.md
- workflows/graphify.md
- Vf
- glassmorphism/show.blade.php
- SubscriptionAccess
- addSingleBadge
- glassmorphism-background.blade.php
- CashierOrderSoundAlert
- GuestReviewWebTest
- dropdown.blade.php
- InvoicePaymentTest
- 2026_08_20_010000_add_floor_layout_to_tables_table.php
- Illuminate\Support\Facades\Schema
- Astrotomic\Translatable\Validation\RuleFactory
- layouts/blog.blade.php

## God Nodes (most connected - your core abstractions)
1. `User` - 397 edges
2. `Restaurant` - 353 edges
3. `TestCase` - 193 edges
4. `Order` - 167 edges
5. `constructor()` - 152 edges
6. `update()` - 148 edges
7. `MenuItem` - 114 edges
8. `PlatformSetting` - 114 edges
9. `Visit` - 107 edges
10. `DiningTable` - 102 edges

## Surprising Connections (you probably didn't know these)
- `up()` --calls--> `DiningTable`  [EXTRACTED]
  database/migrations/2026_08_20_010000_add_floor_layout_to_tables_table.php → app/Models/DiningTable.php
- `extraMenuItem()` --calls--> `MenuItem`  [EXTRACTED]
  tests/Concerns/CreatesGuestRestaurant.php → app/Models/MenuItem.php
- `paidGuestOrder()` --calls--> `Order`  [EXTRACTED]
  tests/Concerns/CreatesGuestRestaurant.php → app/Models/Order.php
- `createGuestRestaurant()` --calls--> `Restaurant`  [EXTRACTED]
  tests/Concerns/CreatesGuestRestaurant.php → app/Models/Restaurant.php
- `makeOwner()` --references--> `Restaurant`  [EXTRACTED]
  tests/Concerns/CreatesSubscribedRestaurant.php → app/Models/Restaurant.php

## Import Cycles
- None detected.

## Communities (416 total, 48 thin omitted)

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
Nodes (189): aa(), addHackNode(), addNode(), addOptions(), addTextblockHacks(), an(), ao(), applyAspectRatio() (+181 more)

### Community 4 - "ExportFile"
Cohesion: 0.03
Nodes (25): TenantForceDeleteCommand, PdfExporter, CleanupOldExportFilesJob, ExportReportJob, ForceDeleteTenantJob, SendWhatsappReceiptJob, ExportFile, WhatsappMessage (+17 more)

### Community 5 - "Pe"
Cohesion: 0.11
Nodes (33): Ba(), cd(), me(), dd(), dt(), Ft(), gl(), ht() (+25 more)

### Community 6 - "TestCase"
Cohesion: 0.03
Nodes (60): SetPermissionsTeamId, CashierOrderService, OrderPaymentService, ImageOptimizer, BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles, Bityukov\CommandCenter\Filament\Pages\Commands, Bityukov\CommandCenter\Filament\Pages\History, DatabaseSeeder (+52 more)

### Community 7 - "constructor"
Cohesion: 0.02
Nodes (171): active(), add(), addChunk(), addEventListener(), addInfoPane(), addInner(), addWindowListeners(), adjust() (+163 more)

### Community 8 - "MenuItem"
Cohesion: 0.02
Nodes (42): RestaurantMenuController, DiningTable, KdsStation, MenuCategory, MenuItem, MenuVariant, Modifier, ModifierGroup (+34 more)

### Community 9 - "fromObject"
Cohesion: 0.03
Nodes (109): El(), ac(), ae(), after(), Al(), Am(), before(), bl() (+101 more)

### Community 10 - "lo"
Cohesion: 0.29
Nodes (10): Km(), lo(), qm(), n(), renderSpec(), serializeFragment(), serializeMark(), serializeNode() (+2 more)

### Community 11 - "find"
Cohesion: 0.13
Nodes (22): baseDirAt(), bidiIn(), bidiSpans(), bidiSpansAt(), checkHover(), coordsAtPos(), Df(), dirAt() (+14 more)

### Community 12 - "Filament\Tables\Table"
Cohesion: 0.09
Nodes (45): BloggersTable, CmsBannerResource, DiningTableResource, ModifierGroupResource, TableRightClick, BackedEnum, Filament\Actions\BulkAction, Filament\Actions\BulkActionGroup (+37 more)

### Community 13 - "PlatformSetting"
Cohesion: 0.06
Nodes (6): self, PlatformSetting, PlatformSettingSeeder, AuthGlassTest, FilamentTenantThemeTest, PlatformSettingTest

### Community 14 - "_update"
Cohesion: 0.04
Nodes (106): addBox(), addElements(), adjustHitBoxes(), afterBuildTicks(), afterCalculateLabelRotation(), afterDataLimits(), afterDraw(), afterFit() (+98 more)

### Community 15 - "Order"
Cohesion: 0.03
Nodes (25): OrderReceiptDownloadController, OrderReceiptPrintController, PaymentProofViewController, Order, OrderItem, OrderReceipt, DailyOmzetService, KdsItemService (+17 more)

### Community 16 - "advance"
Cohesion: 0.05
Nodes (65): addChild(), addGaps(), addLeafElement(), addNode(), advance(), ATXHeading(), blank(), break() (+57 more)

### Community 17 - "r"
Cohesion: 0.04
Nodes (119): _0(), addNodeView(), addProseMirrorPlugins(), af(), append(), au(), Bf(), c() (+111 more)

### Community 18 - "get"
Cohesion: 0.03
Nodes (101): addBlockWidget(), addBreak(), addComposition(), addDelimiter(), addInlineWidget(), addLine(), addLineStart(), addLineStartIfNotCovered() (+93 more)

### Community 19 - "o"
Cohesion: 0.04
Nodes (121): ag(), ah(), apply(), ar(), au(), average(), Ba(), beforeDatasetDraw() (+113 more)

### Community 20 - "Illuminate\Database\Eloquent\Relations\BelongsTo"
Cohesion: 0.02
Nodes (33): CashierShift, CashierShiftMovement, CmsBanner, LogOptions, CmsFaq, LogOptions, CmsGalleryImage, CmsProfile (+25 more)

### Community 21 - "Illuminate\Foundation\Http\FormRequest"
Cohesion: 0.07
Nodes (10): AddCartItemRequest, CheckoutRequest, ClaimTableRequest, JoinTableRequest, StorePaymentProofRequest, UpdateCartItemRequest, StoreBlogCommentRequest, ValidBlogCommentParent (+2 more)

### Community 22 - "AdminPanelProvider.php"
Cohesion: 0.12
Nodes (26): ApplyPlatformBrandTheme, AdminPanelProvider, BloggerPanelProvider, FounderPanelProvider, AuthGlass, BezhanSalleh\FilamentShield\FilamentShieldPlugin, Bityukov\CommandCenter\Filament\CommandCenterPlugin, Filament\Http\Middleware\Authenticate (+18 more)

### Community 23 - "Illuminate\Database\Eloquent\Builder"
Cohesion: 0.07
Nodes (8): KitchenDisplay, BackedEnum, UnitEnum, Width, BelongsToRestaurantScope, Filament\Resources\Concerns\HasTabs, Illuminate\Database\Eloquent\Builder, Illuminate\Database\Eloquent\Scope

### Community 24 - "Filament\Resources\Pages\ListRecords"
Cohesion: 0.02
Nodes (42): BlogCommentResource, CreateBlogComment, EditBlogComment, ListBlogComments, ViewBlogComment, BlogCommentForm, BlogCommentInfolist, BlogCommentsTable (+34 more)

### Community 25 - "support.js"
Cohesion: 0.05
Nodes (75): acquireScrollLock(), ai(), e(), Bi(), br(), Bt(), ca(), close() (+67 more)

### Community 26 - "n"
Cohesion: 0.09
Nodes (78): _a(), Ae(), ar(), as(), bc(), ee(), ue(), u() (+70 more)

### Community 27 - "AppServiceProvider.php"
Cohesion: 0.10
Nodes (16): BlogPostTranslation, BlogPostTranslationObserver, AppServiceProvider, Filament\Actions\ForceDeleteAction, Filament\Actions\ForceDeleteBulkAction, Filament\Actions\RestoreAction, Filament\Actions\RestoreBulkAction, Filament\Resources\Pages\Page (+8 more)

### Community 28 - "GuestContext"
Cohesion: 0.08
Nodes (15): CartController, GuestCart, GuestCheckout, GuestPay, GuestReview, GuestStatus, VisitCartItem, GuestCartService (+7 more)

### Community 29 - "columns/select.js"
Cohesion: 0.06
Nodes (57): A(), An(), applyDisabledState(), b(), Bt(), Ce(), D(), disable() (+49 more)

### Community 30 - "e"
Cohesion: 0.07
Nodes (73): addCommands(), AS(), Bm(), cellsInRect(), check(), colCount(), colSelection(), computeAttrs() (+65 more)

### Community 31 - "updateElements"
Cohesion: 0.03
Nodes (113): aa(), acquireContext(), afterAutoSkip(), Ao(), aspectRatio(), bh(), bu(), buildLookupTable() (+105 more)

### Community 32 - "echo.js"
Cohesion: 0.05
Nodes (49): a(), ar(), b(), Be(), Ce(), cr(), d(), De() (+41 more)

### Community 33 - "resolve"
Cohesion: 0.07
Nodes (85): addKeyboardShortcuts(), after(), al(), before(), blockRange(), Bs(), canAppend(), canReplace() (+77 more)

### Community 34 - "fn"
Cohesion: 0.13
Nodes (33): aa(), At(), ba(), cr(), da(), de(), dt(), ei() (+25 more)

### Community 35 - "facet"
Cohesion: 0.04
Nodes (68): accept(), baseTheme(), blur(), bu(), build(), dispatch(), dr(), facet() (+60 more)

### Community 36 - "Illuminate\Http\Request"
Cohesion: 0.08
Nodes (19): CashierShiftPrintController, ApplyRestaurantPanelTheme, EnsureApiGuestVisit, EnsureGuestVisit, EnsureRestaurantOperations, EnsureTenantSubscription, IdentifyApiGuestDevice, IdentifyGuestDevice (+11 more)

### Community 37 - "y"
Cohesion: 0.13
Nodes (79): at(), b(), Be(), $c(), X(), ca(), Cr(), Ct() (+71 more)

### Community 38 - "prop"
Cohesion: 0.05
Nodes (69): acceptToken(), allows(), AQ(), atLastNode(), au(), child(), childAfter(), childBefore() (+61 more)

### Community 39 - "ae"
Cohesion: 0.09
Nodes (34): ae(), Ao(), as(), B(), Kt(), cs(), Ee(), Ge() (+26 more)

### Community 40 - "Customer"
Cohesion: 0.06
Nodes (8): bootScopedToRestaurant(), scopeForRestaurant(), scopeWithoutRestaurantScope(), Customer, CustomerLoyaltyPoint, CustomerCrmService, WhatsAppNumber, CustomerCrmLoyaltyTest

### Community 41 - "eq"
Cohesion: 0.05
Nodes (72): addGlobalAttributes(), addInputRules(), addMark(), addPasteRules(), addStoredMark(), Ah(), Ax(), childAfter() (+64 more)

### Community 42 - "Ye"
Cohesion: 0.08
Nodes (49): Rd(), $a(), at(), bk(), bp(), bt(), Cr(), Dk() (+41 more)

### Community 43 - "EditProfile"
Cohesion: 0.10
Nodes (7): EditProfile, FilamentProfilePlugin, ProfileInformationForm, Ipatco\FilamentProfile\FilamentProfilePlugin, Ipatco\FilamentProfile\Forms\ProfileInformationForm, Ipatco\FilamentProfile\Pages\EditProfile, ProfilePageTest

### Community 44 - "Activity"
Cohesion: 0.05
Nodes (14): ViewBlogger, ViewBlogTag, ActivityResource, ListActivities, ViewActivity, ActivityInfolist, ActivitiesTable, CashierShiftResource (+6 more)

### Community 45 - "notifications.js"
Cohesion: 0.06
Nodes (31): actions(), button(), c(), close(), configureAnimations(), configureTransitions(), constructor(), danger() (+23 more)

### Community 46 - "markdown-editor.js"
Cohesion: 0.03
Nodes (143): Ei(), Aa(), Ac(), ad(), af(), ai(), al(), An() (+135 more)

### Community 47 - "CommissionReconciliation"
Cohesion: 0.06
Nodes (15): CommissionReconciliation, BackedEnum, UnitEnum, Width, CustomerAnalytics, CustomerSatisfactionAnalytics, GenerateReport, BackedEnum (+7 more)

### Community 48 - "te"
Cohesion: 0.05
Nodes (8): Bn(), br(), ji(), qd(), Ri(), te(), Vi(), Xc()

### Community 49 - "Cn"
Cohesion: 0.13
Nodes (46): Cn(), b(), Be(), Ce(), De(), dn(), _e(), Fe() (+38 more)

### Community 50 - "Filament\Widgets\Concerns\InteractsWithPageFilters"
Cohesion: 0.10
Nodes (8): Dashboard, BlogAudienceWidget, BlogCategoryDistributionWidget, BlogContentProgressWidget, BlogTrafficApexChartWidget, BlogVisitStatsWidget, Filament\Pages\Dashboard\Concerns\HasFiltersForm, Filament\Widgets\Concerns\InteractsWithPageFilters

### Community 51 - "components/select.js"
Cohesion: 0.08
Nodes (38): A(), applyDisabledState(), b(), Bt(), D(), disable(), E(), en() (+30 more)

### Community 52 - "ManageSoundNotifications"
Cohesion: 0.16
Nodes (4): ManageSoundNotifications, BackedEnum, UnitEnum, ManageSoundNotificationsTest

### Community 53 - "tables.js"
Cohesion: 0.13
Nodes (44): A(), ae(), B(), be(), C(), ce(), E(), ee() (+36 more)

### Community 54 - "constructor"
Cohesion: 0.03
Nodes (99): Ad(), add(), addExtensions(), applyInitialSize(), ay(), Bd(), Bo(), bw() (+91 more)

### Community 55 - "r"
Cohesion: 0.15
Nodes (43): _a(), ar(), c(), f(), d(), di(), g(), Hi() (+35 more)

### Community 56 - "SoftDeleteTrashPage"
Cohesion: 0.03
Nodes (26): SoftDeleteTrashPage, ManageCmsBanners, TrashCmsBanners, CmsFaqResource, ManageCmsFaqs, TrashCmsFaqs, CmsGalleryImageResource, ManageCmsGalleryImages (+18 more)

### Community 57 - "SubscriptionStatus"
Cohesion: 0.12
Nodes (3): BackedEnum, UnitEnum, SubscriptionStatus

### Community 58 - "Xt"
Cohesion: 0.12
Nodes (44): ae(), At(), bi(), bn(), ci(), cn(), ct(), de() (+36 more)

### Community 60 - "filament-right-click.js"
Cohesion: 0.11
Nodes (42): a(), ae(), b(), be(), C(), ce(), D(), de() (+34 more)

### Community 61 - "constructor"
Cohesion: 0.03
Nodes (85): Bc(), bg(), chartOptionScopes(), Cl(), clone(), constructor(), create(), Ct() (+77 more)

### Community 62 - "t"
Cohesion: 0.06
Nodes (50): a$(), activeForPoint(), addBlock(), addLineDeco(), b1(), blankContent(), boundChange(), commit() (+42 more)

### Community 63 - "Si"
Cohesion: 0.13
Nodes (41): ae(), At(), bi(), bn(), ci(), cn(), ct(), de() (+33 more)

### Community 64 - "s"
Cohesion: 0.05
Nodes (71): add(), afterAutoSkip(), Bt(), buildLookupTable(), buildOrUpdateElements(), cl(), Cn(), cr() (+63 more)

### Community 65 - "configure"
Cohesion: 0.06
Nodes (55): addElements(), afterDatasetsUpdate(), bi(), bindEvents(), bindUserEvents(), buildOrUpdateControllers(), buildOrUpdateScales(), _checkEventBindings() (+47 more)

### Community 66 - "selectOption"
Cohesion: 0.12
Nodes (39): addBadgesForSelectedOptions(), addSingleBadge(), addSingleSelectionDisplay(), closeDropdown(), constructor(), createBadgeElement(), createOptionElement(), createRemoveButton() (+31 more)

### Community 67 - "reduce"
Cohesion: 0.07
Nodes (52): addActions(), advanceFully(), advanceStack(), allActions(), c0(), canShift(), close(), deadEnd() (+44 more)

### Community 68 - "getContext"
Cohesion: 0.07
Nodes (51): acquireContext(), Ae(), Ao(), bl(), Ca(), ci(), _computeGridLineItems(), _computeLabelArea() (+43 more)

### Community 69 - "ir"
Cohesion: 0.11
Nodes (49): Ft(), ir(), ae(), A(), E(), at(), be(), ce() (+41 more)

### Community 70 - "BlogReferrersWidget"
Cohesion: 0.10
Nodes (7): BlogReferrersWidget, TopBloggersWidget, TopBlogPostsWidget, FounderOverdueRestaurantsWidget, FounderPendingInvoicesWidget, FounderRecentTenantsWidget, Filament\Widgets\TableWidget

### Community 71 - "slider.js"
Cohesion: 0.09
Nodes (39): Ae(), ar(), Be(), Bt(), Ce(), De(), _e(), Ee() (+31 more)

### Community 72 - "Restaurant"
Cohesion: 0.02
Nodes (50): ExpireStaleOperationsCommand, FinalizeCashierCommissionCommand, GenerateUpcomingInvoicesCommand, ProcessSubscriptionLifecycleCommand, SendCashierCommissionReminderCommand, analyticsDateFrom(), analyticsDateTo(), analyticsDayCount() (+42 more)

### Community 73 - "selectOption"
Cohesion: 0.15
Nodes (33): addSingleSelectionDisplay(), closeDropdown(), constructor(), createOptionElement(), deferPositionDropdown(), destroy(), filterOptions(), focusNextOption() (+25 more)

### Community 74 - "Filament\Schemas\Schema"
Cohesion: 0.03
Nodes (67): BlogAnalytics, ManageBlogHero, BlogHeroFormSchema, content(), defaultForm(), fillForm(), getFormActions(), getFormContentComponent() (+59 more)

### Community 75 - "slice"
Cohesion: 0.05
Nodes (123): addElement(), Ah(), baseIndentFor(), be(), Bg(), a(), blockAt(), bS() (+115 more)

### Community 76 - "RefreshesAnalyticsChart.php"
Cohesion: 0.36
Nodes (8): generateChartDataChecksum(), getCachedChartData(), getChartData(), mountRefreshesAnalyticsChart(), refreshAnalyticsChartData(), rendering(), updateChartData(), Livewire\Attributes\Locked

### Community 77 - "E"
Cohesion: 0.05
Nodes (61): $a(), add(), af(), B(), bo(), bs(), ca(), _cachedScopes() (+53 more)

### Community 78 - "st"
Cohesion: 0.05
Nodes (48): ad(), applyStack(), br(), Di(), drawCaret(), _f(), first(), getCaretPosition() (+40 more)

### Community 79 - "file-upload.js"
Cohesion: 0.05
Nodes (53): hc(), Bp(), c(), ca(), clickPercent(), constructor(), Cp(), da() (+45 more)

### Community 80 - "_update"
Cohesion: 0.07
Nodes (43): themeClasses(), active(), afterBuildTicks(), afterCalculateLabelRotation(), afterDataLimits(), afterFit(), afterSetDimensions(), afterTickToLabelConversion() (+35 more)

### Community 81 - "fo"
Cohesion: 0.07
Nodes (41): alpha(), be(), bo(), co(), darken(), desaturate(), Ea(), es() (+33 more)

### Community 82 - "Illuminate\Http\JsonResponse"
Cohesion: 0.08
Nodes (11): OrderController, ReviewController, TableController, VisitController, RestaurantReviewController, StoreRestaurantReviewRequest, RestaurantReviewResource, TableScanResource (+3 more)

### Community 83 - "RestaurantDirectory"
Cohesion: 0.11
Nodes (3): RestaurantDirectory, Livewire\Attributes\Computed, Livewire\WithPagination

### Community 84 - "FonnteErrorMessage"
Cohesion: 0.11
Nodes (8): FonnteClient, FonnteErrorMessage, Throwable, Illuminate\Http\Client\ConnectionException, Illuminate\Http\Client\Response, PHPUnit\Framework\Attributes\DataProvider, RuntimeException, FonnteErrorMessageTest

### Community 88 - "BlogPost"
Cohesion: 0.03
Nodes (21): BlogCommentController, SitemapController, BlogCategory, BlogComment, BlogPost, BlogTag, VisitLog, BlogCommentObserver (+13 more)

### Community 89 - "A"
Cohesion: 0.10
Nodes (36): A(), As(), buildTicks(), calculateLabelRotation(), _calculatePadding(), _computeLabelItems(), _computeLabelSizes(), computeTickLimit() (+28 more)

### Community 90 - "c"
Cohesion: 0.09
Nodes (32): ai(), al(), bs(), dl(), c(), er(), first(), getCenterPoint() (+24 more)

### Community 91 - "devDependencies"
Cohesion: 0.09
Nodes (21): apexcharts, axios, concurrently, laravel-vite-plugin, dependencies, apexcharts, devDependencies, axios (+13 more)

### Community 92 - "filament/app.js"
Cohesion: 0.13
Nodes (8): B(), close(), G(), init(), P(), setUpResizeObserver(), x(), Y()

### Community 93 - "createResolver"
Cohesion: 0.09
Nodes (32): _cachedScopes(), createResolver(), datasetAnimationScopeKeys(), datasetElementScopeKeys(), get(), getMaxOverflow(), getOptionScopes(), getSharedOptions() (+24 more)

### Community 94 - "fn"
Cohesion: 0.22
Nodes (18): An(), Ce(), ei(), fn(), Ft(), Ie(), Le(), ni() (+10 more)

### Community 95 - "TenantContext"
Cohesion: 0.13
Nodes (3): bootBelongsToRestaurantAndOutlet(), IdrAmount, TenantContext

### Community 96 - "require"
Cohesion: 0.12
Nodes (17): require, astrotomic/laravel-translatable, barryvdh/laravel-dompdf, bezhansalleh/filament-shield, endroid/qr-code, filament/filament, hammadzafar05/filament-mobile-preset, ipatco/filament-profile (+9 more)

### Community 97 - "scripts"
Cohesion: 0.12
Nodes (16): scripts, dev, post-autoload-dump, post-create-project-cmd, post-root-package-install, post-update-cmd, Composer\\Config::disableProcessTimeout, Illuminate\\Foundation\\ComposerScripts::postAutoloadDump (+8 more)

### Community 98 - ".slice"
Cohesion: 0.04
Nodes (95): accepts(), addAttributes(), addInner(), addMaps(), addStep(), addTransform(), ak(), appendMap() (+87 more)

### Community 99 - "color-picker.js"
Cohesion: 0.11
Nodes (8): [g](), style(), update(), [x](), _freeze(), getAllExtensions(), st(), zt()

### Community 100 - "resources/js/app.js"
Cohesion: 0.12
Nodes (6): applyTheme(), bindThemeToggle(), initReveal(), initTheme(), syncThemeToggleIcons(), toggleTheme()

### Community 101 - "sliceDoc"
Cohesion: 0.10
Nodes (27): aO(), charCategorizer(), Fc(), flatten(), getCursor(), getDeco(), gT(), highlight() (+19 more)

### Community 102 - "create"
Cohesion: 0.05
Nodes (100): Ac(), addAll(), addDOM(), addElement(), addElementByRule(), addNodeMark(), addTextNode(), addToSet() (+92 more)

### Community 103 - "composer.json"
Cohesion: 0.14
Nodes (13): autoload-dev, psr-4, description, keywords, license, minimum-stability, name, prefer-stable (+5 more)

### Community 107 - "date-time-picker.js"
Cohesion: 0.29
Nodes (7): d(), e(), i(), m(), r(), s(), t()

### Community 108 - "🚀 3. Langkah-Langkah Menambahkan Template Baru (*Workflow*)"
Cohesion: 0.12
Nodes (15): 📌 1. Prinsip Utama & Aturan Wajib (Golden Rules), 🏗️ 2. Arsitektur 10 Core Section CMS (1:1 Mapping), 🚀 3. Langkah-Langkah Menambahkan Template Baru (*Workflow*), 📊 4. Variabel yang Disediakan oleh `LandingPageDataService`, 💎 5. Pelajaran Desain & Best Practices UI/UX (*Key Learnings*), A. Larangan Keras Menggunakan Emoji Unicode (Gunakan SVG Heroicons), B. Arsitektur Layering 3D & Background Tembus Pandang (*Stacking Context*), C. Resep Glassmorphism iOS yang Nyata (*True Frosted Glass*) (+7 more)

### Community 109 - "Dashboard"
Cohesion: 0.04
Nodes (10): Dashboard, periodSummary(), RestaurantCategory, RestaurantDirectory, FacilitySeeder, RestaurantCategorySeeder, Illuminate\Contracts\Pagination\LengthAwarePaginator, RestaurantDirectoryTest (+2 more)

### Community 110 - "Mt"
Cohesion: 0.24
Nodes (11): apply(), fs(), go(), Hr(), T(), ir(), it(), Mt() (+3 more)

### Community 111 - "TableQrToken"
Cohesion: 0.09
Nodes (4): TableQrToken, GuestApiTest, KitchenDisplayPageTest, TableOpsServiceTest

### Community 112 - "getDatasetMeta"
Cohesion: 0.11
Nodes (26): afterDatasetsUpdate(), An(), generateLabels(), getDatasetMeta(), getDataVisibility(), _getLegendItemAt(), getMaxBorderWidth(), _getSortedDatasetMetas() (+18 more)

### Community 113 - "Y"
Cohesion: 0.11
Nodes (22): at(), Bf(), determineDataLimits(), ef(), getMatchingVisibleMetas(), getMinMax(), _getOtherScale(), getUserBounds() (+14 more)

### Community 114 - ".parent"
Cohesion: 0.02
Nodes (58): Login, afterCreate(), afterSave(), ensureAtLeastOneTranslation(), extractTranslations(), hasTranslatableContent(), mutateFormDataBeforeCreate(), mutateFormDataBeforeFill() (+50 more)

### Community 115 - "actions/actions.js"
Cohesion: 0.44
Nodes (8): closeModal(), generateModalId(), getActionNestingIndexFromModalId(), init(), openModal(), rememberPreviouslyFocusedElement(), restorePreviouslyFocusedElement(), syncActionModals()

### Community 116 - "fd"
Cohesion: 0.06
Nodes (48): activateHover(), addToSet(), bd(), between(), Bh(), cd(), childString(), clearDelayedAndroidKey() (+40 more)

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

### Community 122 - "S"
Cohesion: 0.11
Nodes (23): ar(), da(), getPadding(), gn(), gs(), It(), ji(), ke() (+15 more)

### Community 123 - "require-dev"
Cohesion: 0.25
Nodes (8): require-dev, fakerphp/faker, laravel/pail, laravel/pint, laravel/sail, mockery/mockery, nunomaduro/collision, phpunit/phpunit

### Community 124 - "LandingLayout"
Cohesion: 0.11
Nodes (3): LandingLayout, self, Illuminate\Support\Arr

### Community 125 - "6. Katalog fitur"
Cohesion: 0.06
Nodes (33): 1. Latar belakang, 2. Rumusan masalah, 3.1 Tujuan umum, 3.2 Tujuan khusus, 3. Tujuan, 4.1 Bagi restoran (owner, kasir, dapur), 4.2 Bagi tamu, 4.3 Bagi pengelola platform (+25 more)

### Community 127 - "config"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

### Community 128 - "6. Katalog fitur"
Cohesion: 0.06
Nodes (32): 1. Latar belakang, 2. Rumusan masalah, 3.1 Tujuan umum, 3.2 Tujuan khusus, 3. Tujuan, 4.1 Bagi restoran (owner, kasir, dapur), 4.2 Bagi tamu, 4.3 Bagi pengelola platform (+24 more)

### Community 129 - "toString"
Cohesion: 0.13
Nodes (19): Bc(), checkAttrs(), checkContent(), endIndex(), getObj(), hasProtocol(), Rc(), render() (+11 more)

### Community 130 - "register-restaurant.blade.php"
Cohesion: 0.15
Nodes (12): applyColorPreset(, back, nextFromAccount, nextFromPlan, nextFromRestaurant, nextFromVisual, register, $set( (+4 more)

### Community 131 - "add-to-cart-modal.blade.php"
Cohesion: 0.29
Nodes (6): cancelPicking, confirmAdd, decrementPickingQty, incrementPickingQty, setVariant({{ $variant->id }}), toggleModifier({{ $modifier->id }})

### Community 132 - "web.php"
Cohesion: 0.12
Nodes (8): BlogController, BlogLikeController, PlatformPageController, BlogLike, BlogLikeService, RecordVisitService, VisitorHash, Illuminate\View\View

### Community 134 - "psr-4"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 135 - "extra"
Cohesion: 0.40
Nodes (5): dev-master, extra, branch-alias, laravel, dont-discover

### Community 136 - "logging.php"
Cohesion: 0.40
Nodes (4): Monolog\Handler\NullHandler, Monolog\Handler\StreamHandler, Monolog\Handler\SyslogUdpHandler, Monolog\Processor\PsrLogMessageProcessor

### Community 137 - "constructor"
Cohesion: 0.11
Nodes (22): Ot(), apply(), chartOptionScopes(), constructor(), describe(), ei(), getDevicePixelRatio(), getMeta() (+14 more)

### Community 138 - "selectRecords"
Cohesion: 0.20
Nodes (18): areRecordsPartiallySelected(), areRecordsSelected(), areRecordsToggleable(), canSelectAllRecords(), deselectAllRecords(), deselectRecords(), getRecordsOnPage(), getSelectedRecordsCount() (+10 more)

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

### Community 149 - "sidebar.blade.php"
Cohesion: 0.50
Nodes (3): filament.widgets.analytics._chart-canvas, filament.widgets.analytics._section-header, filament.widgets.analytics._styles

### Community 150 - "guest/menu.blade.php"
Cohesion: 0.50
Nodes (3): guest.partials.nav, setCategory({{ $category->id }}), setCategory(null)

### Community 153 - "_notify"
Cohesion: 0.20
Nodes (14): active(), _animateOptions(), cancel(), _createAnimations(), _createDescriptors(), _descriptors(), _notify(), _notifyStateChanges() (+6 more)

### Community 157 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.02
Nodes (31): canDelete(), canDeleteAny(), canForceDelete(), canRestore(), Action, trashPageAction(), FacilityResource, LandingTemplateResource (+23 more)

### Community 163 - "Filament\Widgets\Widget"
Cohesion: 0.04
Nodes (24): TemplateRadioPicker, FounderRevenueGrowthApexChartWidget, FounderSubscriptionHealthApexChartWidget, OrderTodayStatsWidget, AnalyticsKpiWidget, AnalyticsPeriodSummaryWidget, AnalyticsRevenueBarWidget, AnalyticsSidebarWidget (+16 more)

### Community 164 - "GraceReadOnlyTest"
Cohesion: 0.16
Nodes (4): BlockGraceMutations, SubscriptionWriteGuard, Livewire\ComponentHook, GraceReadOnlyTest

### Community 173 - "CheckoutTotals"
Cohesion: 0.25
Nodes (3): CartItemResource, CartResource, CheckoutTotals

### Community 176 - "🎨 Spesifikasi Token & Class CSS yang Tersedia di `theme.css`"
Cohesion: 0.15
Nodes (12): 1. Kartu Kontainer Utama (`.vision-card`), 2. Kotak Ikon Gradien Neon (`.vision-icon-box`), 3. Kapsul Metrik / Sub-Kartu (`.vision-pill-card` atau `.vision-pill-dark`), 4. Kartu Filter (`.vision-filter-card`), 5. Tabel Data Kaca (`.vision-table-card`), 6. Form Section & Skema Input Kaca (`.vision-card` pada `Section::make`), 7. Kartu Statistik Global (`StatsOverviewWidget` / `Stat::make`), ⚙️ Aturan Wajib untuk AI Developer (+4 more)

### Community 177 - "AdSetting"
Cohesion: 0.07
Nodes (7): AdSetting, self, AdPlacementService, Head, Slot, Illuminate\View\Component, AdIntegrationTest

### Community 179 - "CmsMedia"
Cohesion: 0.03
Nodes (27): WelcomeBannerWidget, CheckoutController, MenuController, SessionController, RestaurantController, Controller, ExportFileDownloadController, RestaurantLandingController (+19 more)

### Community 180 - "Dashboard"
Cohesion: 0.25
Nodes (5): Dashboard, BezhanSalleh\FilamentShield\Resources\Roles\RoleResource, Filament\Pages\Dashboard, Filament\Widgets\AccountWidget, Filament\Widgets\FilamentInfoWidget

### Community 183 - "User"
Cohesion: 0.02
Nodes (35): Role, HasMany, LogOptions, User, ExportFilePolicy, RolePolicy, UserPolicy, Filament\Models\Contracts\FilamentUser (+27 more)

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
Cohesion: 0.16
Nodes (4): CreateCashierOrder, BackedEnum, UnitEnum, Width

### Community 301 - "classic/show.blade.php"
Cohesion: 0.50
Nodes (3): landing.sections., partials.customer.landing-footer, partials.customer.landing-header

### Community 302 - "g$"
Cohesion: 0.05
Nodes (59): attrs(), bi(), cc(), ch(), cO(), _d(), eh(), Ex() (+51 more)

### Community 304 - "addEventListener"
Cohesion: 0.33
Nodes (7): addEventListener(), bindResponsiveEvents(), fu(), isAttached(), nr(), removeEventListener(), Ua()

### Community 317 - "Vf"
Cohesion: 0.33
Nodes (7): contains(), gi(), splitAt(), toISOTime(), toMillis(), Vf(), ye()

### Community 322 - "glassmorphism/show.blade.php"
Cohesion: 0.29
Nodes (6): landing.templates.glassmorphism.sections., landing.templates.glassmorphism.sections.hero, landing.sections., landing.templates.glassmorphism.partials.background, landing.templates.glassmorphism.sections.footer, landing.templates.glassmorphism.sections.header

### Community 324 - "SubscriptionAccess"
Cohesion: 0.04
Nodes (15): canCreate(), canEdit(), canViewAny(), ManageCmsProfile, BackedEnum, UnitEnum, MenuItemResource, ListMenuItems (+7 more)

### Community 327 - "addSingleBadge"
Cohesion: 0.33
Nodes (6): addBadgesForSelectedOptions(), addSingleBadge(), createBadgeElement(), createRemoveButton(), getLabelForSingleSelection(), getSelectedOptionLabel()

## Knowledge Gaps
- **345 isolated node(s):** `$schema`, `name`, `type`, `description`, `laravel` (+340 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **48 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `User` connect `User` to `ExportFile`, `TestCase`, `MenuItem`, `Filament\Tables\Table`, `Order`, `Illuminate\Database\Eloquent\Relations\BelongsTo`, `AdminPanelProvider.php`, `Illuminate\Database\Eloquent\Builder`, `AppServiceProvider.php`, `Illuminate\Database\Eloquent\Model`, `CashierFilamentActionsTest`, `Filament\Widgets\Widget`, `Illuminate\Http\Request`, `GraceReadOnlyTest`, `Customer`, `StaleOperationsServiceTest`, `CashTender`, `CommissionReconciliation`, `AdSetting`, `Filament\Widgets\Concerns\InteractsWithPageFilters`, `CmsMedia`, `ManageSoundNotifications`, `BlogReferrersWidget`, `Restaurant`, `Filament\Schemas\Schema`, `CashierOrderSoundAlertTest`, `BlogPost`, `GuestReviewWebTest`, `RegisterRestaurant`, `Dashboard`, `TableQrToken`, `.parent`?**
  _High betweenness centrality (0.026) - this node is a cross-community bridge._
- **Why does `update()` connect `constructor` to `code-editor.js`, `fromObject`, `replace`, `find`, `advance`, `r`, `get`, `n`, `echo.js`, `resolve`, `facet`, `y`, `Ye`, `g$`, `markdown-editor.js`, `te`, `constructor`, `t`, `reduce`, `slice`, `_update`, `.slice`, `sliceDoc`, `fd`?**
  _High betweenness centrality (0.020) - this node is a cross-community bridge._
- **Why does `Restaurant` connect `Restaurant` to `ExportFile`, `TestCase`, `MenuItem`, `Filament\Tables\Table`, `PlatformSetting`, `Order`, `Illuminate\Database\Eloquent\Relations\BelongsTo`, `AdminPanelProvider.php`, `Illuminate\Database\Eloquent\Builder`, `Filament\Resources\Pages\ListRecords`, `Illuminate\Database\Eloquent\Model`, `LandingMenuCatalogTest`, `CashierFilamentActionsTest`, `Filament\Widgets\Widget`, `Illuminate\Http\Request`, `GraceReadOnlyTest`, `Customer`, `CommissionReconciliation`, `CmsMedia`, `User`, `SubscriptionStatus`, `SubscriptionAccess`, `BlogReferrersWidget`, `Filament\Schemas\Schema`, `Illuminate\Http\JsonResponse`, `BlogPost`, `TenantContext`, `RegisterRestaurant`, `Dashboard`, `LandingLayout`?**
  _High betweenness centrality (0.019) - this node is a cross-community bridge._
- **What connects `$schema`, `name`, `type` to the rest of the system?**
  _345 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `stat/chart.js` be split into smaller, more focused modules?**
  _Cohesion score 0.02443338861249309 - nodes in this community are weakly interconnected._
- **Should `components/chart.js` be split into smaller, more focused modules?**
  _Cohesion score 0.01160448290537665 - nodes in this community are weakly interconnected._
- **Should `code-editor.js` be split into smaller, more focused modules?**
  _Cohesion score 0.008478741705578767 - nodes in this community are weakly interconnected._