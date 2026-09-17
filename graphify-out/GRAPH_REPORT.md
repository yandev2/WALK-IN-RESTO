# Graph Report - WALK-IN-RESTO  (2026-09-18)

## Corpus Check
- 860 files · ~404,482 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 9770 nodes · 30269 edges · 430 communities (386 shown, 44 thin omitted)
- Extraction: 92% EXTRACTED · 8% INFERRED · 0% AMBIGUOUS · INFERRED: 2515 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `6d291338`
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
- BlogPostResource
- nodesBetween
- find
- ExportReportJob.php
- PlatformSetting
- _update
- Order
- advance
- r
- get
- addElementByRule
- Illuminate\Database\Eloquent\Model
- updateElements
- AdminPanelProvider.php
- Illuminate\Database\Eloquent\Builder
- Filament\Resources\Pages\ListRecords
- support.js
- n
- fromObject
- GuestContext
- columns/select.js
- .forEach
- o
- echo.js
- resolve
- fn
- facet
- MenuItem
- constructor
- prop
- ae
- Customer
- OrderResource
- apply
- s
- g$
- notifications.js
- markdown-editor.js
- CommissionReconciliation
- te
- Cn
- configure
- components/select.js
- fn
- tables.js
- Illuminate\Foundation\Http\FormRequest
- r
- AppServiceProvider.php
- SubscriptionStatus
- Xt
- Illuminate\Database\Migrations\Migration
- filament-right-click.js
- addEventListener
- t
- Si
- BlogVisitStatsWidget
- ce
- selectOption
- EditProfile
- dx
- ir
- TenantResource
- slider.js
- Restaurant
- closeDropdown
- Filament\Schemas\Schema
- slice
- RefreshesAnalyticsChart.php
- BlogAnalyticsService
- getContext
- file-upload.js
- st
- E
- Filament\Tables\Table
- RestaurantDirectory
- FonnteErrorMessage
- Illuminate\Database\Schema\Blueprint
- Activity
- BlogPost
- BlogPostObserver
- _update
- devDependencies
- filament/app.js
- SubscriptionAccess
- fn
- fo
- require
- scripts
- .slice
- color-picker.js
- resources/js/app.js
- sliceDoc
- e
- composer.json
- order-today-stats-widget.blade.php
- RegisterRestaurant
- date-time-picker.js
- 🚀 3. Langkah-Langkah Menambahkan Template Baru (*Workflow*)
- RestaurantCategory
- Mt
- A
- BlogTag
- OrderReceiptPrintTest
- actions/actions.js
- fd
- schemas.js
- Landasan Produksi — Tahap 1 (Walk-In Operasional)
- Guest
- GeoDistance
- 2. Masalah di lapangan — dan apa yang sistem selesaikan
- BlogCommentStatus.php
- require-dev
- BlogComment
- 6. Katalog fitur
- config
- 6. Katalog fitur
- Illuminate\View\View
- register-restaurant.blade.php
- add-to-cart-modal.blade.php
- BloggerPanelTest
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
- BlogDemoSeeder.php
- CashierFilamentActionsTest
- .parent
- SubscriptionWriteGuard
- Dashboard
- Pe
- renderOptions
- c
- createResolver
- 🎨 Spesifikasi Token & Class CSS yang Tersedia di `theme.css`
- AdSetting
- CmsMedia
- getDatasetMeta
- User
- xc
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
- KitchenDisplay
- Y
- S
- constructor
- rules/graphify.md
- workflows/graphify.md
- AuthGlass
- HasSingletonForm.php
- Locales
- glassmorphism/show.blade.php
- Illuminate\Console\Command
- Visit
- selectOption
- _notify
- glassmorphism-background.blade.php
- replace
- ut
- ForceDeleteTenantJob
- dropdown.blade.php
- StoreBlogCommentRequest
- st
- ImageOptimizer
- UserFactory
- ViewOrder
- FonnteClient
- Dashboard
- GraceReadOnlyTest
- SubscriptionGateTest
- SitemapController.php
- Vf
- ExcelExporter
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
- `BlogAnalyticsTest` --references--> `BlogCategory`  [EXTRACTED]
  tests/Feature/BlogAnalyticsTest.php → app/Models/BlogCategory.php
- `createGuestRestaurant()` --calls--> `KdsStation`  [EXTRACTED]
  tests/Concerns/CreatesGuestRestaurant.php → app/Models/KdsStation.php
- `createGuestRestaurant()` --calls--> `MenuCategory`  [EXTRACTED]
  tests/Concerns/CreatesGuestRestaurant.php → app/Models/MenuCategory.php
- `createGuestRestaurant()` --calls--> `MenuItem`  [EXTRACTED]
  tests/Concerns/CreatesGuestRestaurant.php → app/Models/MenuItem.php

## Import Cycles
- None detected.

## Communities (430 total, 44 thin omitted)

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
Cohesion: 0.13
Nodes (4): ExportFile, ExportFileObserver, ExportFilePolicy, ExportService

### Community 5 - "y"
Cohesion: 0.16
Nodes (71): at(), b(), Be(), $c(), X(), me(), Cr(), Ct() (+63 more)

### Community 6 - "TestCase"
Cohesion: 0.03
Nodes (60): CashierOrderService, OrderPaymentService, BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles, Bityukov\CommandCenter\Filament\Pages\Commands, Bityukov\CommandCenter\Filament\Pages\History, Carbon\CarbonPeriod, DatabaseSeeder, OverdueTenantDemoSeeder (+52 more)

### Community 7 - "constructor"
Cohesion: 0.02
Nodes (171): active(), add(), addChunk(), addEventListener(), addInfoPane(), addInner(), addWindowListeners(), adjust() (+163 more)

### Community 8 - "Illuminate\Http\Request"
Cohesion: 0.04
Nodes (34): CashierShiftPrintController, ApplyRestaurantPanelTheme, EnsureApiGuestVisit, EnsureGuestVisit, EnsureRestaurantOperations, EnsureTenantSubscription, IdentifyApiGuestDevice, IdentifyGuestDevice (+26 more)

### Community 9 - "BlogPostResource"
Cohesion: 0.08
Nodes (12): BlogPostsRelationManager, BlogPostResource, CreateBlogPost, EditBlogPost, ListBlogPosts, ViewBlogPost, CommentsRelationManager, BlogPostForm (+4 more)

### Community 10 - "nodesBetween"
Cohesion: 0.05
Nodes (68): addGlobalAttributes(), addInputRules(), addMark(), addPasteRules(), addStoredMark(), Ah(), Ax(), childAfter() (+60 more)

### Community 11 - "find"
Cohesion: 0.13
Nodes (22): baseDirAt(), bidiIn(), bidiSpans(), bidiSpansAt(), checkHover(), coordsAtPos(), Df(), dirAt() (+14 more)

### Community 12 - "ExportReportJob.php"
Cohesion: 0.14
Nodes (9): PdfExporter, CleanupOldExportFilesJob, ExportReportJob, SendWhatsappReceiptJob, Illuminate\Contracts\Queue\ShouldQueue, Illuminate\Foundation\Queue\Queueable, Illuminate\Support\Facades\Log, Maatwebsite\Excel\Facades\Excel (+1 more)

### Community 13 - "PlatformSetting"
Cohesion: 0.02
Nodes (27): analyticsTheme(), PlatformPageController, RestaurantBrandResource, RestaurantResource, RestaurantSummaryResource, RestaurantMenuCatalog, Facility, self (+19 more)

### Community 14 - "_update"
Cohesion: 0.04
Nodes (106): addBox(), addElements(), adjustHitBoxes(), afterBuildTicks(), afterCalculateLabelRotation(), afterDataLimits(), afterDraw(), afterFit() (+98 more)

### Community 15 - "Order"
Cohesion: 0.03
Nodes (23): OrderReceiptDownloadController, OrderReceiptPrintController, PaymentProofViewController, CashierOrderSoundAlert, Order, OrderItem, OrderReceipt, WhatsappMessage (+15 more)

### Community 16 - "advance"
Cohesion: 0.05
Nodes (65): addChild(), addGaps(), addLeafElement(), addNode(), advance(), ATXHeading(), blank(), break() (+57 more)

### Community 17 - "r"
Cohesion: 0.07
Nodes (49): ao(), append(), Cc(), co(), descendants(), domAtPos(), element(), findDiffEnd() (+41 more)

### Community 18 - "get"
Cohesion: 0.03
Nodes (101): addBlockWidget(), addBreak(), addComposition(), addDelimiter(), addInlineWidget(), addLine(), addLineStart(), addLineStartIfNotCovered() (+93 more)

### Community 19 - "addElementByRule"
Cohesion: 0.13
Nodes (27): addAll(), addDOM(), addElement(), addElementByRule(), addTextNode(), addToSet(), allowedMarks(), allowsMarkType() (+19 more)

### Community 20 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.01
Nodes (51): canDelete(), getRecord(), canForceDelete(), canRestore(), BlogCategoryTranslation, BlogHeroSettingTranslation, BlogTagTranslation, CashierShiftMovement (+43 more)

### Community 21 - "updateElements"
Cohesion: 0.03
Nodes (113): aa(), acquireContext(), afterAutoSkip(), Ao(), aspectRatio(), bh(), bu(), buildLookupTable() (+105 more)

### Community 22 - "AdminPanelProvider.php"
Cohesion: 0.20
Nodes (20): ApplyPlatformBrandTheme, SetPermissionsTeamId, BezhanSalleh\FilamentShield\FilamentShieldPlugin, Bityukov\CommandCenter\Filament\CommandCenterPlugin, Filament\Http\Middleware\Authenticate, Filament\Http\Middleware\AuthenticateSession, Filament\Http\Middleware\DisableBladeIconComponents, Filament\Http\Middleware\DispatchServingFilamentEvent (+12 more)

### Community 23 - "Illuminate\Database\Eloquent\Builder"
Cohesion: 0.05
Nodes (15): ListCustomerReviews, DiningTableResource, ManageDiningTables, Action, TrashDiningTables, MenuItemResource, ListMenuItems, TrashMenuItems (+7 more)

### Community 24 - "Filament\Resources\Pages\ListRecords"
Cohesion: 0.03
Nodes (38): CreateBlogCategory, EditBlogCategory, ListBlogCategories, BloggerResource, CreateBlogger, EditBlogger, ListBloggers, ViewBlogger (+30 more)

### Community 25 - "support.js"
Cohesion: 0.05
Nodes (75): acquireScrollLock(), ai(), e(), Bi(), br(), Bt(), ca(), close() (+67 more)

### Community 26 - "n"
Cohesion: 0.09
Nodes (80): _a(), Ae(), ar(), as(), Ba(), bc(), bf(), ee() (+72 more)

### Community 27 - "fromObject"
Cohesion: 0.03
Nodes (109): El(), ac(), ae(), after(), Al(), Am(), before(), bl() (+101 more)

### Community 28 - "GuestContext"
Cohesion: 0.03
Nodes (38): CartController, CheckoutController, MenuController, OrderController, ReviewController, SessionController, TableController, VisitController (+30 more)

### Community 29 - "columns/select.js"
Cohesion: 0.06
Nodes (57): A(), An(), applyDisabledState(), b(), Bt(), Ce(), D(), disable() (+49 more)

### Community 30 - ".forEach"
Cohesion: 0.03
Nodes (135): _0(), addAttributes(), addNodeView(), addOptions(), addProseMirrorPlugins(), af(), au(), B0() (+127 more)

### Community 31 - "o"
Cohesion: 0.04
Nodes (121): ag(), ah(), apply(), ar(), au(), average(), Ba(), beforeDatasetDraw() (+113 more)

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

### Community 36 - "MenuItem"
Cohesion: 0.03
Nodes (16): MenuCategory, MenuItem, LogOptions, MenuItemPhoto, MenuModifierService, CashierMenuCatalog, Illuminate\Database\Eloquent\Collection, extraMenuItem() (+8 more)

### Community 37 - "constructor"
Cohesion: 0.03
Nodes (85): Bc(), bg(), chartOptionScopes(), Cl(), clone(), constructor(), create(), Ct() (+77 more)

### Community 38 - "prop"
Cohesion: 0.05
Nodes (69): acceptToken(), allows(), AQ(), atLastNode(), au(), child(), childAfter(), childBefore() (+61 more)

### Community 39 - "ae"
Cohesion: 0.09
Nodes (34): ae(), Ao(), as(), B(), Kt(), cs(), Ee(), Ge() (+26 more)

### Community 40 - "Customer"
Cohesion: 0.04
Nodes (10): Customer, CustomerLoyaltyPoint, RestaurantReview, CustomerCrmService, CustomerSatisfactionDemoSeeder, PublicRestaurantApiTest, CustomerCrmLoyaltyTest, CustomerSatisfactionAnalyticsTest (+2 more)

### Community 41 - "OrderResource"
Cohesion: 0.07
Nodes (9): ManageCmsProfile, BackedEnum, UnitEnum, OrderResource, ListOrders, OrderTodayStatsWidget, Filament\Infolists\Components\RepeatableEntry, Filament\Tables\Filters\Filter (+1 more)

### Community 42 - "apply"
Cohesion: 0.07
Nodes (52): ak(), apply(), applyInner(), applyTransaction(), at(), bk(), c(), bp() (+44 more)

### Community 43 - "s"
Cohesion: 0.05
Nodes (71): add(), afterAutoSkip(), Bt(), buildLookupTable(), buildOrUpdateElements(), cl(), Cn(), cr() (+63 more)

### Community 44 - "g$"
Cohesion: 0.05
Nodes (59): attrs(), bi(), cc(), ch(), cO(), _d(), eh(), Ex() (+51 more)

### Community 45 - "notifications.js"
Cohesion: 0.06
Nodes (31): actions(), button(), c(), close(), configureAnimations(), configureTransitions(), constructor(), danger() (+23 more)

### Community 46 - "markdown-editor.js"
Cohesion: 0.05
Nodes (85): ad(), af(), ai(), al(), An(), ao(), bo(), br() (+77 more)

### Community 47 - "CommissionReconciliation"
Cohesion: 0.06
Nodes (14): CommissionReconciliation, BackedEnum, UnitEnum, CustomerAnalytics, CustomerSatisfactionAnalytics, GenerateReport, BackedEnum, UnitEnum (+6 more)

### Community 48 - "te"
Cohesion: 0.05
Nodes (11): Bn(), Id(), ji(), on(), qd(), qi(), Ri(), te() (+3 more)

### Community 49 - "Cn"
Cohesion: 0.13
Nodes (46): Cn(), b(), Be(), Ce(), De(), dn(), _e(), Fe() (+38 more)

### Community 50 - "configure"
Cohesion: 0.06
Nodes (55): addElements(), afterDatasetsUpdate(), bi(), bindEvents(), bindUserEvents(), buildOrUpdateControllers(), buildOrUpdateScales(), _checkEventBindings() (+47 more)

### Community 51 - "components/select.js"
Cohesion: 0.08
Nodes (34): b(), Bt(), D(), E(), en(), Et(), getLabelsForMultipleSelection(), getSelectedOptionLabels() (+26 more)

### Community 52 - "fn"
Cohesion: 0.09
Nodes (35): Rd(), $a(), Ck(), closest(), De(), fn(), p(), Fx() (+27 more)

### Community 53 - "tables.js"
Cohesion: 0.13
Nodes (44): A(), ae(), B(), be(), C(), ce(), E(), ee() (+36 more)

### Community 54 - "Illuminate\Foundation\Http\FormRequest"
Cohesion: 0.08
Nodes (8): AddCartItemRequest, CheckoutRequest, ClaimTableRequest, JoinTableRequest, StorePaymentProofRequest, StoreRestaurantReviewRequest, UpdateCartItemRequest, Illuminate\Foundation\Http\FormRequest

### Community 55 - "r"
Cohesion: 0.15
Nodes (43): _a(), ar(), c(), f(), d(), di(), g(), Hi() (+35 more)

### Community 56 - "AppServiceProvider.php"
Cohesion: 0.03
Nodes (49): Width, Width, SoftDeleteTrashPage, CmsBannerResource, ManageCmsBanners, TrashCmsBanners, ManageCmsFaqs, TrashCmsFaqs (+41 more)

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

### Community 62 - "t"
Cohesion: 0.06
Nodes (50): a$(), activeForPoint(), addBlock(), addLineDeco(), b1(), blankContent(), boundChange(), commit() (+42 more)

### Community 63 - "Si"
Cohesion: 0.14
Nodes (40): ae(), At(), bi(), bn(), ci(), cn(), ct(), de() (+32 more)

### Community 64 - "BlogVisitStatsWidget"
Cohesion: 0.14
Nodes (5): Dashboard, BlogContentProgressWidget, BlogTrafficApexChartWidget, BlogVisitStatsWidget, Filament\Pages\Dashboard\Concerns\HasFiltersForm

### Community 65 - "ce"
Cohesion: 0.09
Nodes (41): Ac(), bl(), Cc(), ce(), cl(), Dc(), Do(), Ec() (+33 more)

### Community 66 - "selectOption"
Cohesion: 0.12
Nodes (39): addBadgesForSelectedOptions(), addSingleBadge(), addSingleSelectionDisplay(), closeDropdown(), constructor(), createBadgeElement(), createOptionElement(), createRemoveButton() (+31 more)

### Community 67 - "EditProfile"
Cohesion: 0.12
Nodes (5): EditProfile, ProfileInformationForm, Ipatco\FilamentProfile\Forms\ProfileInformationForm, Ipatco\FilamentProfile\Pages\EditProfile, ProfilePageTest

### Community 68 - "dx"
Cohesion: 0.14
Nodes (23): Ei(), Aa(), Bi(), ca(), da(), fa(), Gr(), ki() (+15 more)

### Community 69 - "ir"
Cohesion: 0.14
Nodes (30): ir(), at(), be(), ce(), Ct(), de(), Dt(), ee() (+22 more)

### Community 70 - "TenantResource"
Cohesion: 0.07
Nodes (11): BlogReferrersWidget, TopBloggersWidget, TopBlogPostsWidget, CreateTenant, EditTenant, TenantResource, FounderOverdueRestaurantsWidget, FounderPendingInvoicesWidget (+3 more)

### Community 71 - "slider.js"
Cohesion: 0.11
Nodes (32): ar(), Be(), Ce(), De(), _e(), Ee(), er(), Fe() (+24 more)

### Community 72 - "Restaurant"
Cohesion: 0.02
Nodes (51): GenerateUpcomingInvoicesCommand, analyticsDateFrom(), analyticsDateTo(), analyticsDayCount(), analyticsRangeLabel(), analyticsSnapshot(), canViewAnalytics(), normalizedAnalyticsDateRange() (+43 more)

### Community 73 - "closeDropdown"
Cohesion: 0.23
Nodes (17): applyDisabledState(), closeDropdown(), constructor(), destroy(), disable(), enable(), focusNextOption(), focusPreviousOption() (+9 more)

### Community 74 - "Filament\Schemas\Schema"
Cohesion: 0.02
Nodes (48): getRecordTitle(), SeoFields, TranslationTabs, BlogAnalytics, BlogHeroFormSchema, BlogCategoryResource, BlogCategoryForm, BlogCategoryInfolist (+40 more)

### Community 75 - "slice"
Cohesion: 0.05
Nodes (123): addElement(), Ah(), baseIndentFor(), be(), Bg(), a(), blockAt(), bS() (+115 more)

### Community 76 - "RefreshesAnalyticsChart.php"
Cohesion: 0.36
Nodes (8): generateChartDataChecksum(), getCachedChartData(), getChartData(), mountRefreshesAnalyticsChart(), refreshAnalyticsChartData(), rendering(), updateChartData(), Livewire\Attributes\Locked

### Community 77 - "BlogAnalyticsService"
Cohesion: 0.11
Nodes (4): VisitLog, BlogAnalyticsService, Illuminate\Database\Eloquent\Relations\MorphTo, BlogAnalyticsTest

### Community 78 - "getContext"
Cohesion: 0.07
Nodes (51): acquireContext(), Ae(), Ao(), bl(), Ca(), ci(), _computeGridLineItems(), _computeLabelArea() (+43 more)

### Community 79 - "file-upload.js"
Cohesion: 0.05
Nodes (53): hc(), Bp(), c(), ca(), clickPercent(), constructor(), Cp(), da() (+45 more)

### Community 80 - "st"
Cohesion: 0.05
Nodes (48): ad(), applyStack(), br(), Di(), drawCaret(), _f(), first(), getCaretPosition() (+40 more)

### Community 81 - "E"
Cohesion: 0.05
Nodes (61): $a(), add(), af(), B(), bo(), bs(), ca(), _cachedScopes() (+53 more)

### Community 82 - "Filament\Tables\Table"
Cohesion: 0.08
Nodes (53): Action, trashPageAction(), CmsFaqResource, TableRightClick, BackedEnum, Filament\Actions\Action, Filament\Actions\BulkAction, Filament\Actions\BulkActionGroup (+45 more)

### Community 84 - "FonnteErrorMessage"
Cohesion: 0.16
Nodes (4): FonnteErrorMessage, Throwable, PHPUnit\Framework\Attributes\DataProvider, FonnteErrorMessageTest

### Community 87 - "Activity"
Cohesion: 0.11
Nodes (7): ActivityResource, ListActivities, ViewActivity, ActivitiesTable, Activity, ActivityPresenter, Spatie\Activitylog\Models\Activity

### Community 88 - "BlogPost"
Cohesion: 0.11
Nodes (4): BlogCategory, BlogPost, BlogSeoAndMediaTest, PublicBlogTest

### Community 89 - "BlogPostObserver"
Cohesion: 0.16
Nodes (3): BlogPostTranslation, BlogPostObserver, BlogPostTranslationObserver

### Community 90 - "_update"
Cohesion: 0.07
Nodes (43): themeClasses(), active(), afterBuildTicks(), afterCalculateLabelRotation(), afterDataLimits(), afterFit(), afterSetDimensions(), afterTickToLabelConversion() (+35 more)

### Community 91 - "devDependencies"
Cohesion: 0.09
Nodes (21): apexcharts, axios, concurrently, laravel-vite-plugin, dependencies, apexcharts, devDependencies, axios (+13 more)

### Community 92 - "filament/app.js"
Cohesion: 0.13
Nodes (8): B(), close(), G(), init(), P(), setUpResizeObserver(), x(), Y()

### Community 93 - "SubscriptionAccess"
Cohesion: 0.05
Nodes (11): canCreate(), canEdit(), canViewAny(), OutletResource, Action, ManageOutlet, EditUser, ListUsers (+3 more)

### Community 94 - "fn"
Cohesion: 0.22
Nodes (18): An(), Ce(), ei(), fn(), Ft(), Ie(), Le(), ni() (+10 more)

### Community 95 - "fo"
Cohesion: 0.07
Nodes (41): alpha(), be(), bo(), co(), darken(), desaturate(), Ea(), es() (+33 more)

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

### Community 101 - "sliceDoc"
Cohesion: 0.10
Nodes (27): aO(), charCategorizer(), Fc(), flatten(), getCursor(), getDeco(), gT(), highlight() (+19 more)

### Community 102 - "e"
Cohesion: 0.06
Nodes (86): addCommands(), AS(), Bc(), Bm(), cellsInRect(), check(), checkAttrs(), checkContent() (+78 more)

### Community 103 - "composer.json"
Cohesion: 0.14
Nodes (13): autoload-dev, psr-4, description, keywords, license, minimum-stability, name, prefer-stable (+5 more)

### Community 107 - "date-time-picker.js"
Cohesion: 0.26
Nodes (8): d(), e(), i(), m(), r(), s(), t(), rr()

### Community 108 - "🚀 3. Langkah-Langkah Menambahkan Template Baru (*Workflow*)"
Cohesion: 0.12
Nodes (15): 📌 1. Prinsip Utama & Aturan Wajib (Golden Rules), 🏗️ 2. Arsitektur 10 Core Section CMS (1:1 Mapping), 🚀 3. Langkah-Langkah Menambahkan Template Baru (*Workflow*), 📊 4. Variabel yang Disediakan oleh `LandingPageDataService`, 💎 5. Pelajaran Desain & Best Practices UI/UX (*Key Learnings*), A. Larangan Keras Menggunakan Emoji Unicode (Gunakan SVG Heroicons), B. Arsitektur Layering 3D & Background Tembus Pandang (*Stacking Context*), C. Resep Glassmorphism iOS yang Nyata (*True Frosted Glass*) (+7 more)

### Community 109 - "RestaurantCategory"
Cohesion: 0.07
Nodes (4): RestaurantCategory, RestaurantCategorySeeder, RestaurantDirectoryTest, TenantIsolationTest

### Community 110 - "Mt"
Cohesion: 0.24
Nodes (11): apply(), fs(), go(), Hr(), T(), ir(), it(), Mt() (+3 more)

### Community 111 - "A"
Cohesion: 0.10
Nodes (36): A(), As(), buildTicks(), calculateLabelRotation(), _calculatePadding(), _computeLabelItems(), _computeLabelSizes(), computeTickLimit() (+28 more)

### Community 112 - "BlogTag"
Cohesion: 0.09
Nodes (6): BlogHeroSetting, self, BlogTag, Astrotomic\Translatable\Contracts\Translatable, Astrotomic\Translatable\Translatable, BlogLocaleRoutingTest

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

### Community 123 - "require-dev"
Cohesion: 0.25
Nodes (8): require-dev, fakerphp/faker, laravel/pail, laravel/pint, laravel/sail, mockery/mockery, nunomaduro/collision, phpunit/phpunit

### Community 124 - "BlogComment"
Cohesion: 0.11
Nodes (3): BlogComment, BlogCommentObserver, BloggerResourceLivewireTest

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
Cohesion: 0.37
Nodes (3): BlogController, RecordVisitService, Illuminate\View\View

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
Cohesion: 0.07
Nodes (52): addActions(), advanceFully(), advanceStack(), allActions(), c0(), canShift(), close(), deadEnd() (+44 more)

### Community 158 - "BlogDemoSeeder.php"
Cohesion: 0.22
Nodes (4): BlogLike, BlogLikeService, VisitorHash, BlogDemoSeeder

### Community 163 - ".parent"
Cohesion: 0.04
Nodes (18): Login, ViewBlogCategory, BlogCommentResource, CreateBlogComment, EditBlogComment, ListBlogComments, ViewBlogComment, BlogCommentsTable (+10 more)

### Community 164 - "SubscriptionWriteGuard"
Cohesion: 0.28
Nodes (3): BlockGraceMutations, SubscriptionWriteGuard, Livewire\ComponentHook

### Community 168 - "Dashboard"
Cohesion: 0.11
Nodes (4): Dashboard, CreateRestaurant, EditRestaurant, RestaurantResource

### Community 170 - "Pe"
Cohesion: 0.12
Nodes (32): cd(), dd(), dt(), Ft(), gl(), _i(), Ie(), it() (+24 more)

### Community 171 - "renderOptions"
Cohesion: 0.37
Nodes (13): createOptionElement(), deferPositionDropdown(), filterOptions(), handleSearch(), hideLoadingState(), openDropdown(), populateLabelRepositoryFromOptions(), positionDropdown() (+5 more)

### Community 173 - "c"
Cohesion: 0.09
Nodes (32): ai(), al(), bs(), dl(), c(), er(), first(), getCenterPoint() (+24 more)

### Community 174 - "createResolver"
Cohesion: 0.09
Nodes (32): _cachedScopes(), createResolver(), datasetAnimationScopeKeys(), datasetElementScopeKeys(), get(), getMaxOverflow(), getOptionScopes(), getSharedOptions() (+24 more)

### Community 176 - "🎨 Spesifikasi Token & Class CSS yang Tersedia di `theme.css`"
Cohesion: 0.15
Nodes (12): 1. Kartu Kontainer Utama (`.vision-card`), 2. Kotak Ikon Gradien Neon (`.vision-icon-box`), 3. Kapsul Metrik / Sub-Kartu (`.vision-pill-card` atau `.vision-pill-dark`), 4. Kartu Filter (`.vision-filter-card`), 5. Tabel Data Kaca (`.vision-table-card`), 6. Form Section & Skema Input Kaca (`.vision-card` pada `Section::make`), 7. Kartu Statistik Global (`StatsOverviewWidget` / `Stat::make`), ⚙️ Aturan Wajib untuk AI Developer (+4 more)

### Community 177 - "AdSetting"
Cohesion: 0.06
Nodes (7): AdSetting, self, AdPlacementService, Head, Slot, Illuminate\View\Component, AdIntegrationTest

### Community 179 - "CmsMedia"
Cohesion: 0.03
Nodes (24): BlogAudienceWidget, BlogCategoryDistributionWidget, FounderRevenueGrowthApexChartWidget, FounderSubscriptionHealthApexChartWidget, AnalyticsKpiWidget, AnalyticsPeriodSummaryWidget, AnalyticsRevenueBarWidget, AnalyticsSidebarWidget (+16 more)

### Community 180 - "getDatasetMeta"
Cohesion: 0.11
Nodes (26): afterDatasetsUpdate(), An(), generateLabels(), getDatasetMeta(), getDataVisibility(), _getLegendItemAt(), getMaxBorderWidth(), _getSortedDatasetMetas() (+18 more)

### Community 183 - "User"
Cohesion: 0.03
Nodes (29): Role, HasMany, LogOptions, static, User, RolePolicy, UserPolicy, Filament\Models\Contracts\FilamentUser (+21 more)

### Community 185 - "xc"
Cohesion: 0.40
Nodes (5): fromSchema(), marksFromSchema(), nodesFromSchema(), schemaRules(), xc()

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
Cohesion: 0.08
Nodes (6): CreateCashierOrder, BackedEnum, UnitEnum, Width, CashierOrderPreview, CashierOrderPreviewTest

### Community 301 - "classic/show.blade.php"
Cohesion: 0.50
Nodes (3): landing.sections., partials.customer.landing-footer, partials.customer.landing-header

### Community 302 - "KitchenDisplay"
Cohesion: 0.15
Nodes (4): KitchenDisplay, BackedEnum, UnitEnum, Filament\Resources\Concerns\HasTabs

### Community 304 - "Y"
Cohesion: 0.11
Nodes (22): at(), Bf(), determineDataLimits(), ef(), getMatchingVisibleMetas(), getMinMax(), _getOtherScale(), getUserBounds() (+14 more)

### Community 306 - "S"
Cohesion: 0.11
Nodes (23): ar(), da(), getPadding(), gn(), gs(), It(), ji(), ke() (+15 more)

### Community 307 - "constructor"
Cohesion: 0.11
Nodes (22): Ot(), apply(), chartOptionScopes(), constructor(), describe(), ei(), getDevicePixelRatio(), getMeta() (+14 more)

### Community 317 - "AuthGlass"
Cohesion: 0.13
Nodes (10): FilamentProfilePlugin, AdminPanelProvider, BloggerPanelProvider, FounderPanelProvider, AuthGlass, Filament\Panel, Filament\PanelProvider, Illuminate\Support\HtmlString (+2 more)

### Community 318 - "HasSingletonForm.php"
Cohesion: 0.16
Nodes (15): ManageBlogHero, content(), defaultForm(), fillForm(), getFormActions(), getFormContentComponent(), getRedirectUrl(), getSavedNotificationTitle() (+7 more)

### Community 320 - "Locales"
Cohesion: 0.07
Nodes (25): afterCreate(), afterSave(), ensureAtLeastOneTranslation(), extractTranslations(), hasTranslatableContent(), mutateFormDataBeforeCreate(), mutateFormDataBeforeFill(), mutateFormDataBeforeSave() (+17 more)

### Community 322 - "glassmorphism/show.blade.php"
Cohesion: 0.29
Nodes (6): landing.templates.glassmorphism.sections., landing.templates.glassmorphism.sections.hero, landing.sections., landing.templates.glassmorphism.partials.background, landing.templates.glassmorphism.sections.footer, landing.templates.glassmorphism.sections.header

### Community 324 - "Illuminate\Console\Command"
Cohesion: 0.17
Nodes (8): ExpireStaleOperationsCommand, FinalizeCashierCommissionCommand, ProcessSubscriptionLifecycleCommand, SendCashierCommissionReminderCommand, Illuminate\Console\Command, Illuminate\Foundation\Inspiring, Illuminate\Support\Facades\Artisan, Illuminate\Support\Facades\Schedule

### Community 325 - "Visit"
Cohesion: 0.02
Nodes (27): ScanTable, DiningTable, LogOptions, Visit, VisitDevice, GuestCheckoutService, StaleOperationsService, TableOpsService (+19 more)

### Community 327 - "selectOption"
Cohesion: 0.24
Nodes (12): addBadgesForSelectedOptions(), addSingleBadge(), addSingleSelectionDisplay(), createBadgeElement(), createRemoveButton(), getLabelForSingleSelection(), getSelectedOptionLabel(), hideMaxItemsMessage() (+4 more)

### Community 340 - "_notify"
Cohesion: 0.20
Nodes (14): active(), _animateOptions(), cancel(), _createAnimations(), _createDescriptors(), _descriptors(), _notify(), _notifyStateChanges() (+6 more)

### Community 347 - "replace"
Cohesion: 0.15
Nodes (17): applyChanges(), balanced(), decompose(), decomposeLeft(), decomposeRight(), getReplacement(), heightForGap(), heightForLine() (+9 more)

### Community 348 - "ut"
Cohesion: 0.17
Nodes (16): Ae(), Bt(), et(), Ft(), fe(), ft(), Jt(), le() (+8 more)

### Community 350 - "ForceDeleteTenantJob"
Cohesion: 0.22
Nodes (3): TenantForceDeleteCommand, ForceDeleteTenantJob, TenantPurgeService

### Community 354 - "StoreBlogCommentRequest"
Cohesion: 0.19
Nodes (5): BlogCommentController, StoreBlogCommentRequest, ValidBlogCommentParent, Illuminate\Contracts\Validation\ValidationRule, Illuminate\Http\RedirectResponse

### Community 355 - "st"
Cohesion: 0.21
Nodes (12): [g](), _freeze(), getAllExtensions(), ae(), A(), E(), lt(), ot() (+4 more)

### Community 356 - "ImageOptimizer"
Cohesion: 0.22
Nodes (3): ImageOptimizer, ImageUploadAuditTest, ImageOptimizerTest

### Community 357 - "UserFactory"
Cohesion: 0.47
Nodes (3): static, UserFactory, Illuminate\Database\Eloquent\Factories\Factory

### Community 359 - "FonnteClient"
Cohesion: 0.33
Nodes (4): FonnteClient, Illuminate\Http\Client\ConnectionException, Illuminate\Http\Client\Response, RuntimeException

### Community 360 - "Dashboard"
Cohesion: 0.25
Nodes (5): Dashboard, BezhanSalleh\FilamentShield\Resources\Roles\RoleResource, Filament\Pages\Dashboard, Filament\Widgets\AccountWidget, Filament\Widgets\FilamentInfoWidget

### Community 365 - "SitemapController.php"
Cohesion: 0.38
Nodes (3): SitemapController, MediaUrl, Illuminate\Http\Response

### Community 368 - "Vf"
Cohesion: 0.33
Nodes (7): contains(), gi(), splitAt(), toISOTime(), toMillis(), Vf(), ye()

### Community 369 - "ExcelExporter"
Cohesion: 0.60
Nodes (3): ExcelExporter, Maatwebsite\Excel\Concerns\FromView, Maatwebsite\Excel\Concerns\ShouldAutoSize

## Knowledge Gaps
- **349 isolated node(s):** `$schema`, `name`, `type`, `description`, `laravel` (+344 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **44 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Restaurant` connect `Restaurant` to `TestCase`, `Illuminate\Http\Request`, `ExportReportJob.php`, `PlatformSetting`, `Order`, `Illuminate\Database\Eloquent\Model`, `AdminPanelProvider.php`, `Illuminate\Database\Eloquent\Builder`, `Filament\Resources\Pages\ListRecords`, `GuestContext`, `CashierFilamentActionsTest`, `MenuItem`, `Dashboard`, `OrderResource`, `Customer`, `CommissionReconciliation`, `CmsMedia`, `User`, `AppServiceProvider.php`, `SubscriptionStatus`, `Visit`, `Filament\Tables\Table`, `SubscriptionAccess`, `ForceDeleteTenantJob`, `GraceReadOnlyTest`, `RegisterRestaurant`, `SitemapController.php`, `RestaurantCategory`, `BlogTag`, `OrderReceiptPrintTest`?**
  _High betweenness centrality (0.027) - this node is a cross-community bridge._
- **Why does `update()` connect `constructor` to `code-editor.js`, `rich-editor.js`, `y`, `find`, `advance`, `get`, `n`, `fromObject`, `reduce`, `.forEach`, `echo.js`, `facet`, `g$`, `markdown-editor.js`, `te`, `fn`, `t`, `ce`, `dx`, `slice`, `_update`, `replace`, `sliceDoc`, `fd`?**
  _High betweenness centrality (0.024) - this node is a cross-community bridge._
- **Why does `User` connect `User` to `ExportFile`, `BloggerPanelTest`, `TestCase`, `Illuminate\Http\Request`, `BlogPostResource`, `ExportReportJob.php`, `PlatformSetting`, `Order`, `Illuminate\Database\Eloquent\Model`, `AdminPanelProvider.php`, `Illuminate\Database\Eloquent\Builder`, `Filament\Resources\Pages\ListRecords`, `GuestContext`, `BlogDemoSeeder.php`, `CashierFilamentActionsTest`, `.parent`, `MenuItem`, `Customer`, `OrderResource`, `CommissionReconciliation`, `AdSetting`, `CmsMedia`, `AppServiceProvider.php`, `Visit`, `TenantResource`, `Restaurant`, `Filament\Schemas\Schema`, `BlogAnalyticsService`, `Filament\Tables\Table`, `BlogPost`, `SubscriptionAccess`, `ForceDeleteTenantJob`, `UserFactory`, `GraceReadOnlyTest`, `RegisterRestaurant`, `RestaurantCategory`, `BlogTag`, `OrderReceiptPrintTest`, `BlogCommentStatus.php`, `BlogComment`?**
  _High betweenness centrality (0.019) - this node is a cross-community bridge._
- **What connects `$schema`, `name`, `type` to the rest of the system?**
  _349 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `stat/chart.js` be split into smaller, more focused modules?**
  _Cohesion score 0.02443338861249309 - nodes in this community are weakly interconnected._
- **Should `components/chart.js` be split into smaller, more focused modules?**
  _Cohesion score 0.01160448290537665 - nodes in this community are weakly interconnected._
- **Should `code-editor.js` be split into smaller, more focused modules?**
  _Cohesion score 0.008478741705578767 - nodes in this community are weakly interconnected._