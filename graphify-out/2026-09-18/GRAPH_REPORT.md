# Graph Report - WALK-IN-RESTO  (2026-09-18)

## Corpus Check
- 856 files · ~402,303 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 9752 nodes · 30211 edges · 407 communities (362 shown, 45 thin omitted)
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
- Symfony\Component\HttpFoundation\Response
- AppServiceProvider.php
- nodesBetween
- find
- CashierShift
- PlatformSetting
- _update
- Order
- advance
- r
- get
- addElementByRule
- Illuminate\Database\Eloquent\Relations\BelongsTo
- Controller
- AdminPanelProvider.php
- TenantContext
- Filament\Resources\Pages\ListRecords
- support.js
- n
- O
- VisitCartItem
- columns/select.js
- .forEach
- o
- echo.js
- resolve
- fn
- facet
- LandingMenuCatalogTest
- create
- prop
- ae
- Customer
- Illuminate\Database\Eloquent\Model
- apply
- LandingLayout
- GuestContext
- notifications.js
- markdown-editor.js
- CommissionReconciliation
- te
- Cn
- Visit
- components/select.js
- fn
- tables.js
- Illuminate\Foundation\Http\FormRequest
- r
- Filament\Tables\Table
- SubscriptionStatus
- Xt
- Illuminate\Database\Migrations\Migration
- filament-right-click.js
- getDataset
- t
- Si
- BlogVisitStatsWidget
- ce
- selectOption
- EditProfile
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
- BlogPostTranslation
- file-upload.js
- SubscriptionInvoice
- E
- Filament\Support\Icons\Heroicon
- RestaurantDirectory
- FonnteErrorMessage
- Illuminate\Database\Schema\Blueprint
- Filament\Resources\Pages\ViewRecord
- BlogPost
- BlogPostObserver
- LandingTemplate
- devDependencies
- filament/app.js
- OutletResource
- fn
- Illuminate\Http\Request
- require
- scripts
- .slice
- color-picker.js
- resources/js/app.js
- g$
- e
- composer.json
- order-today-stats-widget.blade.php
- RegisterRestaurant
- date-time-picker.js
- 🚀 3. Langkah-Langkah Menambahkan Template Baru (*Workflow*)
- Illuminate\Support\Collection
- Mt
- ManageSoundNotifications
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
- Illuminate\Database\Eloquent\Builder
- GraceReadOnlyTest
- Dashboard
- CashierOrderSoundAlert
- renderOptions
- GuestCheckout.php
- ManageBillingAccount
- 🎨 Spesifikasi Token & Class CSS yang Tersedia di `theme.css`
- AdSetting
- CmsMedia
- SoftDeleteTrashTest
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
- rules/graphify.md
- workflows/graphify.md
- HasSingletonForm.php
- glassmorphism/show.blade.php
- ActivityLogger
- selectOption
- determineDataLimits
- glassmorphism-background.blade.php
- dropdown.blade.php
- UserFactory
- InvoicePaymentTest
- 2026_08_20_010000_add_floor_layout_to_tables_table.php
- Illuminate\Support\Facades\Schema
- Astrotomic\Translatable\Validation\RuleFactory
- layouts/blog.blade.php

## God Nodes (most connected - your core abstractions)
1. `User` - 411 edges
2. `Restaurant` - 354 edges
3. `TestCase` - 197 edges
4. `Order` - 167 edges
5. `constructor()` - 152 edges
6. `update()` - 148 edges
7. `MenuItem` - 114 edges
8. `PlatformSetting` - 114 edges
9. `Visit` - 110 edges
10. `BlogPost` - 102 edges

## Surprising Connections (you probably didn't know these)
- `up()` --calls--> `DiningTable`  [EXTRACTED]
  database/migrations/2026_08_20_010000_add_floor_layout_to_tables_table.php → app/Models/DiningTable.php
- `BlogAnalyticsTest` --references--> `BlogCategory`  [EXTRACTED]
  tests/Feature/BlogAnalyticsTest.php → app/Models/BlogCategory.php
- `extraMenuItem()` --calls--> `MenuItem`  [EXTRACTED]
  tests/Concerns/CreatesGuestRestaurant.php → app/Models/MenuItem.php
- `paidGuestOrder()` --calls--> `Order`  [EXTRACTED]
  tests/Concerns/CreatesGuestRestaurant.php → app/Models/Order.php
- `createGuestRestaurant()` --calls--> `Restaurant`  [EXTRACTED]
  tests/Concerns/CreatesGuestRestaurant.php → app/Models/Restaurant.php

## Import Cycles
- None detected.

## Communities (407 total, 45 thin omitted)

### Community 0 - "stat/chart.js"
Cohesion: 0.01
Nodes (465): themeClasses(), jo(), Ot(), qc(), A(), aa(), acquireContext(), active() (+457 more)

### Community 1 - "components/chart.js"
Cohesion: 0.01
Nodes (362): El(), abutsStart(), ac(), add(), addControllers(), addPlugins(), addScales(), ae() (+354 more)

### Community 2 - "code-editor.js"
Cohesion: 0.01
Nodes (124): aa(), Ac(), addActive(), addCompletion(), addCompletions(), addNamespace(), addNamespaceObject(), Ag() (+116 more)

### Community 3 - "rich-editor.js"
Cohesion: 0.01
Nodes (254): aa(), Ad(), add(), addExtensions(), addHackNode(), addNode(), addNodeMark(), addTextblockHacks() (+246 more)

### Community 4 - "ExportFile"
Cohesion: 0.04
Nodes (21): TenantForceDeleteCommand, CleanupOldExportFilesJob, ExportReportJob, ForceDeleteTenantJob, SendWhatsappReceiptJob, ExportFile, WhatsappMessage, ExportFileObserver (+13 more)

### Community 5 - "y"
Cohesion: 0.18
Nodes (49): al(), at(), Be(), Cr(), de(), dt(), Ee(), ef() (+41 more)

### Community 6 - "TestCase"
Cohesion: 0.03
Nodes (58): CashierOrderService, OrderPaymentService, ImageOptimizer, BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles, Bityukov\CommandCenter\Filament\Pages\Commands, Bityukov\CommandCenter\Filament\Pages\History, RolePermissionSeeder, Filament\Facades\Filament (+50 more)

### Community 7 - "constructor"
Cohesion: 0.02
Nodes (164): add(), addChunk(), addEventListener(), addInfoPane(), addInner(), addWindowListeners(), adjust(), al() (+156 more)

### Community 8 - "Symfony\Component\HttpFoundation\Response"
Cohesion: 0.08
Nodes (16): ApplyRestaurantPanelTheme, EnsureGuestVisit, EnsureRestaurantOperations, EnsureTenantSubscription, IdentifyApiGuestDevice, IdentifyGuestDevice, RequireApiGuestDevice, SecurityHeaders (+8 more)

### Community 9 - "AppServiceProvider.php"
Cohesion: 0.04
Nodes (34): afterCreate(), afterSave(), ensureAtLeastOneTranslation(), extractTranslations(), hasTranslatableContent(), mutateFormDataBeforeCreate(), mutateFormDataBeforeSave(), persistTranslations() (+26 more)

### Community 10 - "nodesBetween"
Cohesion: 0.05
Nodes (68): addGlobalAttributes(), addInputRules(), addMark(), addPasteRules(), addStoredMark(), Ah(), Ax(), childAfter() (+60 more)

### Community 11 - "find"
Cohesion: 0.09
Nodes (32): baseDirAt(), bidiIn(), bidiSpans(), bidiSpansAt(), bP(), checkHover(), coordsAt(), coordsAtPos() (+24 more)

### Community 12 - "CashierShift"
Cohesion: 0.06
Nodes (8): PdfExporter, CashierShiftPrintController, CashierShift, CashierShiftMovement, CashierShiftService, ReceiptLogo, Barryvdh\DomPDF\Facade\Pdf, ReceiptLogoTest

### Community 13 - "PlatformSetting"
Cohesion: 0.05
Nodes (7): PlatformPageController, self, PlatformSetting, PlatformSettingSeeder, AuthGlassTest, FilamentTenantThemeTest, PlatformSettingTest

### Community 14 - "_update"
Cohesion: 0.02
Nodes (245): acquireContext(), addBox(), adjustHitBoxes(), af(), afterBuildTicks(), afterCalculateLabelRotation(), afterDataLimits(), afterDatasetsUpdate() (+237 more)

### Community 15 - "Order"
Cohesion: 0.03
Nodes (20): OrderReceiptDownloadController, OrderReceiptPrintController, PaymentProofViewController, Order, OrderItem, OrderReceipt, Payment, KdsItemService (+12 more)

### Community 16 - "advance"
Cohesion: 0.05
Nodes (65): addChild(), addGaps(), addLeafElement(), addNode(), advance(), ATXHeading(), blank(), break() (+57 more)

### Community 17 - "r"
Cohesion: 0.07
Nodes (49): ao(), append(), Cc(), co(), descendants(), domAtPos(), element(), findDiffEnd() (+41 more)

### Community 18 - "get"
Cohesion: 0.04
Nodes (96): addBlockWidget(), addBreak(), addComposition(), addDelimiter(), addInlineWidget(), addLine(), addLineStart(), addLineStartIfNotCovered() (+88 more)

### Community 19 - "addElementByRule"
Cohesion: 0.13
Nodes (27): addAll(), addDOM(), addElement(), addElementByRule(), addTextNode(), addToSet(), allowedMarks(), allowsMarkType() (+19 more)

### Community 20 - "Illuminate\Database\Eloquent\Relations\BelongsTo"
Cohesion: 0.02
Nodes (56): RestaurantMenuController, CmsBanner, LogOptions, CmsFaq, LogOptions, CmsGalleryImage, CmsProfile, LogOptions (+48 more)

### Community 21 - "Controller"
Cohesion: 0.09
Nodes (15): CartController, CheckoutController, MenuController, OrderController, ReviewController, SessionController, VisitController, RestaurantController (+7 more)

### Community 22 - "AdminPanelProvider.php"
Cohesion: 0.12
Nodes (27): ApplyPlatformBrandTheme, SetPermissionsTeamId, AdminPanelProvider, BloggerPanelProvider, FounderPanelProvider, AuthGlass, BezhanSalleh\FilamentShield\FilamentShieldPlugin, Bityukov\CommandCenter\Filament\CommandCenterPlugin (+19 more)

### Community 23 - "TenantContext"
Cohesion: 0.08
Nodes (8): ListCustomerReviews, ManageDiningTables, Action, Closure, bootBelongsToRestaurantAndOutlet(), BelongsToRestaurantScope, TenantContext, Illuminate\Database\Eloquent\Scope

### Community 24 - "Filament\Resources\Pages\ListRecords"
Cohesion: 0.03
Nodes (33): ListBlogCategories, CreateBlogComment, ListBlogComments, BloggerResource, CreateBlogger, EditBlogger, ListBloggers, ViewBlogger (+25 more)

### Community 25 - "support.js"
Cohesion: 0.05
Nodes (75): acquireScrollLock(), ai(), e(), Bi(), br(), Bt(), ca(), close() (+67 more)

### Community 26 - "n"
Cohesion: 0.09
Nodes (79): _a(), Ae(), ar(), as(), bc(), ee(), ue(), u() (+71 more)

### Community 27 - "O"
Cohesion: 0.19
Nodes (38): b(), $c(), X(), ca(), me(), D(), _e(), Ea() (+30 more)

### Community 28 - "VisitCartItem"
Cohesion: 0.08
Nodes (10): ExportFileDownloadController, GuestCart, GuestMenu, GuestReview, GuestStatus, VisitCartItem, GuestCartService, RestaurantReviewService (+2 more)

### Community 29 - "columns/select.js"
Cohesion: 0.06
Nodes (57): A(), An(), applyDisabledState(), b(), Bt(), Ce(), D(), disable() (+49 more)

### Community 30 - ".forEach"
Cohesion: 0.03
Nodes (135): _0(), addAttributes(), addNodeView(), addOptions(), addProseMirrorPlugins(), af(), au(), B0() (+127 more)

### Community 31 - "o"
Cohesion: 0.03
Nodes (174): ag(), ah(), apply(), ar(), au(), average(), Ba(), beforeDatasetDraw() (+166 more)

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
Cohesion: 0.03
Nodes (101): accept(), active(), apply(), B(), b0(), baseTheme(), between(), blur() (+93 more)

### Community 37 - "create"
Cohesion: 0.09
Nodes (29): addChanges(), addSelection(), Ah(), applyTransaction(), asSingle(), composeDesc(), create(), dl() (+21 more)

### Community 38 - "prop"
Cohesion: 0.06
Nodes (64): AQ(), atLastNode(), au(), child(), childAfter(), childBefore(), cursor(), cursorAt() (+56 more)

### Community 39 - "ae"
Cohesion: 0.09
Nodes (34): ae(), Ao(), as(), B(), Kt(), cs(), Ee(), Ge() (+26 more)

### Community 40 - "Customer"
Cohesion: 0.05
Nodes (7): scopeForRestaurant(), scopeWithoutRestaurantScope(), Customer, CustomerLoyaltyPoint, CustomerCrmService, CustomerCrmLoyaltyTest, GuestScanTableTest

### Community 41 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.04
Nodes (18): BlogPostResource, BlogPostForm, BlogPostsTable, canDelete(), canForceDelete(), canRestore(), OrderResource, ListOrders (+10 more)

### Community 42 - "apply"
Cohesion: 0.07
Nodes (52): ak(), apply(), applyInner(), applyTransaction(), at(), bk(), c(), bp() (+44 more)

### Community 43 - "LandingLayout"
Cohesion: 0.11
Nodes (3): LandingLayout, self, Illuminate\Support\Arr

### Community 44 - "GuestContext"
Cohesion: 0.09
Nodes (9): TableController, EnsureApiGuestVisit, ScanTable, VisitDevice, StaleOperationsService, TableScanService, VisitClaimService, GuestContext (+1 more)

### Community 45 - "notifications.js"
Cohesion: 0.06
Nodes (31): actions(), button(), c(), close(), configureAnimations(), configureTransitions(), constructor(), danger() (+23 more)

### Community 46 - "markdown-editor.js"
Cohesion: 0.05
Nodes (83): ad(), af(), An(), bf(), bo(), Bt(), cd(), Ct() (+75 more)

### Community 47 - "CommissionReconciliation"
Cohesion: 0.05
Nodes (17): CommissionReconciliation, BackedEnum, UnitEnum, Width, CustomerAnalytics, CustomerSatisfactionAnalytics, GenerateReport, BackedEnum (+9 more)

### Community 48 - "te"
Cohesion: 0.05
Nodes (9): Bn(), br(), ji(), on(), qd(), Ri(), te(), Vi() (+1 more)

### Community 49 - "Cn"
Cohesion: 0.13
Nodes (46): Cn(), b(), Be(), Ce(), De(), dn(), _e(), Fe() (+38 more)

### Community 50 - "Visit"
Cohesion: 0.04
Nodes (13): Visit, GuestCheckoutService, MenuModifierService, VisitLifecycleService, Filament\Models\Contracts\HasAvatar, Filament\Models\Contracts\HasName, Illuminate\Database\Eloquent\Relations\HasMany, Illuminate\Database\Eloquent\Relations\HasOne (+5 more)

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
Cohesion: 0.07
Nodes (8): AddCartItemRequest, CheckoutRequest, ClaimTableRequest, JoinTableRequest, StorePaymentProofRequest, StoreRestaurantReviewRequest, UpdateCartItemRequest, Illuminate\Foundation\Http\FormRequest

### Community 55 - "r"
Cohesion: 0.15
Nodes (43): _a(), ar(), c(), f(), d(), di(), g(), Hi() (+35 more)

### Community 56 - "Filament\Tables\Table"
Cohesion: 0.04
Nodes (57): BlogPostsRelationManager, BloggersTable, CommentsRelationManager, SoftDeleteTrashPage, ActivitiesTable, CmsBannerResource, ManageCmsBanners, TrashCmsBanners (+49 more)

### Community 57 - "SubscriptionStatus"
Cohesion: 0.12
Nodes (3): BackedEnum, UnitEnum, SubscriptionStatus

### Community 58 - "Xt"
Cohesion: 0.12
Nodes (44): ae(), At(), bi(), bn(), ci(), cn(), ct(), de() (+36 more)

### Community 60 - "filament-right-click.js"
Cohesion: 0.11
Nodes (42): a(), ae(), b(), be(), C(), ce(), D(), de() (+34 more)

### Community 61 - "getDataset"
Cohesion: 0.09
Nodes (30): addElements(), addEventListener(), As(), bindEvents(), bindResponsiveEvents(), bindUserEvents(), buildOrUpdateControllers(), buildOrUpdateElements() (+22 more)

### Community 62 - "t"
Cohesion: 0.07
Nodes (42): a$(), activeForPoint(), addBlock(), addLineDeco(), blankContent(), boundChange(), commit(), comparePoint() (+34 more)

### Community 63 - "Si"
Cohesion: 0.14
Nodes (40): ae(), At(), bi(), bn(), ci(), cn(), ct(), de() (+32 more)

### Community 64 - "BlogVisitStatsWidget"
Cohesion: 0.10
Nodes (9): Dashboard, BlogContentProgressWidget, BlogTrafficApexChartWidget, BlogVisitStatsWidget, Dashboard, BezhanSalleh\FilamentShield\Resources\Roles\RoleResource, Filament\Pages\Dashboard, Filament\Pages\Dashboard\Concerns\HasFiltersForm (+1 more)

### Community 65 - "ce"
Cohesion: 0.08
Nodes (46): Ac(), ao(), bl(), Cc(), ce(), cl(), Cn(), Dc() (+38 more)

### Community 66 - "selectOption"
Cohesion: 0.12
Nodes (39): addBadgesForSelectedOptions(), addSingleBadge(), addSingleSelectionDisplay(), closeDropdown(), constructor(), createBadgeElement(), createOptionElement(), createRemoveButton() (+31 more)

### Community 67 - "EditProfile"
Cohesion: 0.09
Nodes (8): EditProfile, FilamentProfilePlugin, ProfileInformationForm, Ipatco\FilamentProfile\FilamentProfilePlugin, Ipatco\FilamentProfile\Forms\ProfileInformationForm, Ipatco\FilamentProfile\Pages\EditProfile, Ipatco\FilamentProfile\Widgets\AccountWidget, ProfilePageTest

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
Nodes (41): ExpireStaleOperationsCommand, FinalizeCashierCommissionCommand, GenerateUpcomingInvoicesCommand, ProcessSubscriptionLifecycleCommand, SendCashierCommissionReminderCommand, analyticsDateFrom(), analyticsDateTo(), analyticsDayCount() (+33 more)

### Community 73 - "closeDropdown"
Cohesion: 0.23
Nodes (17): applyDisabledState(), closeDropdown(), constructor(), destroy(), disable(), enable(), focusNextOption(), focusPreviousOption() (+9 more)

### Community 74 - "Filament\Schemas\Schema"
Cohesion: 0.02
Nodes (40): getRecordTitle(), BlogAnalytics, BlogCategoryResource, BlogCategoryForm, BlogCategoryInfolist, BlogCategoriesTable, BlogCommentForm, BlogCommentInfolist (+32 more)

### Community 75 - "slice"
Cohesion: 0.04
Nodes (141): addElement(), b1(), balanced(), baseIndentFor(), be(), Bg(), a(), blockAt() (+133 more)

### Community 76 - "RefreshesAnalyticsChart.php"
Cohesion: 0.36
Nodes (8): generateChartDataChecksum(), getCachedChartData(), getChartData(), mountRefreshesAnalyticsChart(), refreshAnalyticsChartData(), rendering(), updateChartData(), Livewire\Attributes\Locked

### Community 77 - "BlogAnalyticsService"
Cohesion: 0.11
Nodes (5): VisitLog, BlogAnalyticsService, Carbon\Carbon, Illuminate\Database\Eloquent\Relations\MorphTo, BlogAnalyticsTest

### Community 78 - "BlogPostTranslation"
Cohesion: 0.21
Nodes (4): BlogPostTranslation, BlogPostTranslationObserver, AppServiceProvider, Illuminate\Support\ServiceProvider

### Community 79 - "file-upload.js"
Cohesion: 0.05
Nodes (53): hc(), Bp(), c(), ca(), clickPercent(), constructor(), Cp(), da() (+45 more)

### Community 80 - "SubscriptionInvoice"
Cohesion: 0.04
Nodes (10): SubscriptionInvoice, SubscriptionPlan, FounderAnalyticsService, SubscriptionInvoiceService, Carbon\CarbonPeriod, FacilitySeeder, SubscriptionPlanSeeder, CashierCommissionBillingTest (+2 more)

### Community 81 - "E"
Cohesion: 0.06
Nodes (44): $a(), aa(), ad(), bd(), bs(), cd(), describe(), E() (+36 more)

### Community 82 - "Filament\Support\Icons\Heroicon"
Cohesion: 0.07
Nodes (52): SeoFields, TranslationTabs, canCreate(), canEdit(), canViewAny(), Action, trashPageAction(), SubscriptionAccess (+44 more)

### Community 83 - "RestaurantDirectory"
Cohesion: 0.11
Nodes (3): RestaurantDirectory, Livewire\Attributes\Computed, Livewire\WithPagination

### Community 84 - "FonnteErrorMessage"
Cohesion: 0.11
Nodes (8): FonnteClient, FonnteErrorMessage, Throwable, Illuminate\Http\Client\ConnectionException, Illuminate\Http\Client\Response, PHPUnit\Framework\Attributes\DataProvider, RuntimeException, FonnteErrorMessageTest

### Community 87 - "Filament\Resources\Pages\ViewRecord"
Cohesion: 0.07
Nodes (11): ViewBlogCategory, ViewBlogComment, ViewBlogPost, ViewBlogTag, ActivityResource, ListActivities, ViewActivity, CashierShiftResource (+3 more)

### Community 88 - "BlogPost"
Cohesion: 0.10
Nodes (4): BlogCategory, BlogPost, BlogSeoAndMediaTest, PublicBlogTest

### Community 90 - "LandingTemplate"
Cohesion: 0.11
Nodes (8): TemplateRadioPicker, ManageLandingLayout, BackedEnum, Closure, UnitEnum, LandingTemplate, LogOptions, Filament\Forms\Components\Field

### Community 91 - "devDependencies"
Cohesion: 0.09
Nodes (21): apexcharts, axios, concurrently, laravel-vite-plugin, dependencies, apexcharts, devDependencies, axios (+13 more)

### Community 92 - "filament/app.js"
Cohesion: 0.13
Nodes (8): B(), close(), G(), init(), P(), setUpResizeObserver(), x(), Y()

### Community 93 - "OutletResource"
Cohesion: 0.12
Nodes (3): OutletResource, Action, ManageOutlet

### Community 94 - "fn"
Cohesion: 0.22
Nodes (18): An(), Ce(), ei(), fn(), Ft(), Ie(), Le(), ni() (+10 more)

### Community 95 - "Illuminate\Http\Request"
Cohesion: 0.08
Nodes (16): CartItemResource, CartResource, MenuCategoryResource, MenuItemResource, MenuVariantResource, ModifierGroupResource, ModifierResource, OrderItemResource (+8 more)

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
Cohesion: 0.11
Nodes (8): [g](), style(), update(), [x](), _freeze(), getAllExtensions(), st(), zt()

### Community 100 - "resources/js/app.js"
Cohesion: 0.12
Nodes (6): applyTheme(), bindThemeToggle(), initReveal(), initTheme(), syncThemeToggleIcons(), toggleTheme()

### Community 101 - "g$"
Cohesion: 0.04
Nodes (68): acceptToken(), allows(), aO(), charCategorizer(), d0(), De(), Dg(), E$() (+60 more)

### Community 102 - "e"
Cohesion: 0.06
Nodes (86): addCommands(), AS(), Bc(), Bm(), cellsInRect(), check(), checkAttrs(), checkContent() (+78 more)

### Community 103 - "composer.json"
Cohesion: 0.14
Nodes (13): autoload-dev, psr-4, description, keywords, license, minimum-stability, name, prefer-stable (+5 more)

### Community 106 - "RegisterRestaurant"
Cohesion: 0.08
Nodes (6): RegisterRestaurant, GuestPay, PaymentProofService, ReservedSlugs, Livewire\Features\SupportFileUploads\TemporaryUploadedFile, Livewire\WithFileUploads

### Community 107 - "date-time-picker.js"
Cohesion: 0.29
Nodes (7): d(), e(), i(), m(), r(), s(), t()

### Community 108 - "🚀 3. Langkah-Langkah Menambahkan Template Baru (*Workflow*)"
Cohesion: 0.12
Nodes (15): 📌 1. Prinsip Utama & Aturan Wajib (Golden Rules), 🏗️ 2. Arsitektur 10 Core Section CMS (1:1 Mapping), 🚀 3. Langkah-Langkah Menambahkan Template Baru (*Workflow*), 📊 4. Variabel yang Disediakan oleh `LandingPageDataService`, 💎 5. Pelajaran Desain & Best Practices UI/UX (*Key Learnings*), A. Larangan Keras Menggunakan Emoji Unicode (Gunakan SVG Heroicons), B. Arsitektur Layering 3D & Background Tembus Pandang (*Stacking Context*), C. Resep Glassmorphism iOS yang Nyata (*True Frosted Glass*) (+7 more)

### Community 109 - "Illuminate\Support\Collection"
Cohesion: 0.03
Nodes (11): periodSummary(), RestaurantCategory, RestaurantDirectory, TableFloorPlan, RestaurantCategorySeeder, Illuminate\Contracts\Pagination\LengthAwarePaginator, Illuminate\Support\Collection, RestaurantDirectoryTest (+3 more)

### Community 110 - "Mt"
Cohesion: 0.24
Nodes (11): apply(), fs(), go(), Hr(), T(), ir(), it(), Mt() (+3 more)

### Community 111 - "ManageSoundNotifications"
Cohesion: 0.16
Nodes (4): ManageSoundNotifications, BackedEnum, UnitEnum, ManageSoundNotificationsTest

### Community 112 - "BlogTag"
Cohesion: 0.09
Nodes (9): SitemapController, BlogHeroSetting, self, BlogTag, MediaUrl, Astrotomic\Translatable\Contracts\Translatable, Astrotomic\Translatable\Translatable, Illuminate\Http\Response (+1 more)

### Community 115 - "actions/actions.js"
Cohesion: 0.44
Nodes (8): closeModal(), generateModalId(), getActionNestingIndexFromModalId(), init(), openModal(), rememberPreviouslyFocusedElement(), restorePreviouslyFocusedElement(), syncActionModals()

### Community 116 - "fd"
Cohesion: 0.09
Nodes (29): activateHover(), addToSet(), bd(), Bh(), cd(), childString(), clearDelayedAndroidKey(), delayAndroidKey() (+21 more)

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
Cohesion: 0.10
Nodes (7): BlogCommentController, StoreBlogCommentRequest, BlogComment, BlogCommentObserver, ValidBlogCommentParent, Illuminate\Contracts\Validation\ValidationRule, Illuminate\Http\RedirectResponse

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
Cohesion: 0.06
Nodes (55): addActions(), advanceFully(), advanceStack(), allActions(), attrs(), bi(), c0(), canShift() (+47 more)

### Community 158 - "BlogDemoSeeder.php"
Cohesion: 0.20
Nodes (4): BlogLike, BlogLikeService, VisitorHash, BlogDemoSeeder

### Community 163 - "Illuminate\Database\Eloquent\Builder"
Cohesion: 0.04
Nodes (13): Login, BlogCommentResource, getRecordRouteBindingEloquentQuery(), KitchenDisplay, BackedEnum, UnitEnum, Width, Closure (+5 more)

### Community 164 - "GraceReadOnlyTest"
Cohesion: 0.16
Nodes (4): BlockGraceMutations, SubscriptionWriteGuard, Livewire\ComponentHook, GraceReadOnlyTest

### Community 171 - "renderOptions"
Cohesion: 0.37
Nodes (13): createOptionElement(), deferPositionDropdown(), filterOptions(), handleSearch(), hideLoadingState(), openDropdown(), populateLabelRepositoryFromOptions(), positionDropdown() (+5 more)

### Community 174 - "ManageBillingAccount"
Cohesion: 0.24
Nodes (3): ManageBillingAccount, BackedEnum, UnitEnum

### Community 176 - "🎨 Spesifikasi Token & Class CSS yang Tersedia di `theme.css`"
Cohesion: 0.15
Nodes (12): 1. Kartu Kontainer Utama (`.vision-card`), 2. Kotak Ikon Gradien Neon (`.vision-icon-box`), 3. Kapsul Metrik / Sub-Kartu (`.vision-pill-card` atau `.vision-pill-dark`), 4. Kartu Filter (`.vision-filter-card`), 5. Tabel Data Kaca (`.vision-table-card`), 6. Form Section & Skema Input Kaca (`.vision-card` pada `Section::make`), 7. Kartu Statistik Global (`StatsOverviewWidget` / `Stat::make`), ⚙️ Aturan Wajib untuk AI Developer (+4 more)

### Community 177 - "AdSetting"
Cohesion: 0.07
Nodes (7): AdSetting, self, AdPlacementService, Head, Slot, Illuminate\View\Component, AdIntegrationTest

### Community 179 - "CmsMedia"
Cohesion: 0.02
Nodes (32): BlogAudienceWidget, BlogCategoryDistributionWidget, FounderRevenueGrowthApexChartWidget, FounderSubscriptionHealthApexChartWidget, OrderTodayStatsWidget, AnalyticsKpiWidget, AnalyticsPeriodSummaryWidget, AnalyticsRevenueBarWidget (+24 more)

### Community 183 - "User"
Cohesion: 0.02
Nodes (33): Role, HasMany, LogOptions, static, User, RolePolicy, UserPolicy, Filament\Models\Contracts\FilamentUser (+25 more)

### Community 185 - "xc"
Cohesion: 0.40
Nodes (5): fromSchema(), marksFromSchema(), nodesFromSchema(), schemaRules(), xc()

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
Cohesion: 0.06
Nodes (8): CreateCashierOrder, BackedEnum, UnitEnum, Width, ViewOrder, CashierOrderPreview, IdrAmount, CashierOrderPreviewTest

### Community 301 - "classic/show.blade.php"
Cohesion: 0.50
Nodes (3): landing.sections., partials.customer.landing-footer, partials.customer.landing-header

### Community 320 - "HasSingletonForm.php"
Cohesion: 0.07
Nodes (27): mutateFormDataBeforeFill(), ManageBlogHero, BlogHeroFormSchema, afterCreate(), afterSave(), ensureAtLeastOneTranslation(), extractTranslations(), hasTranslatableContent() (+19 more)

### Community 322 - "glassmorphism/show.blade.php"
Cohesion: 0.29
Nodes (6): landing.templates.glassmorphism.sections., landing.templates.glassmorphism.sections.hero, landing.sections., landing.templates.glassmorphism.partials.background, landing.templates.glassmorphism.sections.footer, landing.templates.glassmorphism.sections.header

### Community 325 - "ActivityLogger"
Cohesion: 0.03
Nodes (13): Activity, TableOpsService, ActivityLogger, ActivityPresenter, CashTender, TableQrToken, WhatsAppNumber, Spatie\Activitylog\Models\Activity (+5 more)

### Community 327 - "selectOption"
Cohesion: 0.24
Nodes (12): addBadgesForSelectedOptions(), addSingleBadge(), addSingleSelectionDisplay(), createBadgeElement(), createRemoveButton(), getLabelForSingleSelection(), getSelectedOptionLabel(), hideMaxItemsMessage() (+4 more)

### Community 340 - "determineDataLimits"
Cohesion: 0.07
Nodes (35): active(), _animateOptions(), at(), Bf(), br(), cancel(), _createAnimations(), _createDescriptors() (+27 more)

### Community 357 - "UserFactory"
Cohesion: 0.47
Nodes (3): static, UserFactory, Illuminate\Database\Eloquent\Factories\Factory

## Knowledge Gaps
- **345 isolated node(s):** `$schema`, `name`, `type`, `description`, `laravel` (+340 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **45 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `User` connect `User` to `ExportFile`, `BloggerPanelTest`, `TestCase`, `Symfony\Component\HttpFoundation\Response`, `AppServiceProvider.php`, `CashierShift`, `Order`, `Illuminate\Database\Eloquent\Relations\BelongsTo`, `AdminPanelProvider.php`, `Filament\Resources\Pages\ListRecords`, `BlogDemoSeeder.php`, `CashierFilamentActionsTest`, `Illuminate\Database\Eloquent\Builder`, `GraceReadOnlyTest`, `Customer`, `Illuminate\Database\Eloquent\Model`, `CommissionReconciliation`, `AdSetting`, `Visit`, `CmsMedia`, `SoftDeleteTrashTest`, `Filament\Tables\Table`, `ActivityLogger`, `SubscriptionInvoiceResource`, `Restaurant`, `Filament\Schemas\Schema`, `BlogAnalyticsService`, `SubscriptionInvoice`, `Filament\Support\Icons\Heroicon`, `BlogPost`, `UserFactory`, `RegisterRestaurant`, `Illuminate\Support\Collection`, `ManageSoundNotifications`, `BlogTag`, `OrderReceiptPrintTest`, `BlogCommentStatus.php`, `BlogComment`?**
  _High betweenness centrality (0.030) - this node is a cross-community bridge._
- **Why does `Restaurant` connect `Restaurant` to `ExportFile`, `TestCase`, `Symfony\Component\HttpFoundation\Response`, `AppServiceProvider.php`, `CashierShift`, `PlatformSetting`, `Order`, `Illuminate\Database\Eloquent\Relations\BelongsTo`, `Controller`, `AdminPanelProvider.php`, `TenantContext`, `Filament\Resources\Pages\ListRecords`, `CashierFilamentActionsTest`, `Illuminate\Database\Eloquent\Builder`, `GraceReadOnlyTest`, `LandingMenuCatalogTest`, `Dashboard`, `Illuminate\Database\Eloquent\Model`, `Customer`, `LandingLayout`, `CommissionReconciliation`, `Visit`, `CmsMedia`, `SoftDeleteTrashTest`, `User`, `Filament\Tables\Table`, `SubscriptionStatus`, `ActivityLogger`, `SubscriptionInvoiceResource`, `Filament\Schemas\Schema`, `SubscriptionInvoice`, `Filament\Support\Icons\Heroicon`, `OutletResource`, `RegisterRestaurant`, `Illuminate\Support\Collection`, `BlogTag`, `OrderReceiptPrintTest`?**
  _High betweenness centrality (0.027) - this node is a cross-community bridge._
- **Why does `update()` connect `constructor` to `stat/chart.js`, `components/chart.js`, `code-editor.js`, `rich-editor.js`, `find`, `advance`, `get`, `n`, `O`, `reduce`, `.forEach`, `echo.js`, `facet`, `create`, `prop`, `markdown-editor.js`, `te`, `fn`, `t`, `dx`, `slice`, `g$`, `fd`?**
  _High betweenness centrality (0.021) - this node is a cross-community bridge._
- **What connects `$schema`, `name`, `type` to the rest of the system?**
  _345 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `stat/chart.js` be split into smaller, more focused modules?**
  _Cohesion score 0.010441965874108566 - nodes in this community are weakly interconnected._
- **Should `components/chart.js` be split into smaller, more focused modules?**
  _Cohesion score 0.008615776593248182 - nodes in this community are weakly interconnected._
- **Should `code-editor.js` be split into smaller, more focused modules?**
  _Cohesion score 0.008717948717948718 - nodes in this community are weakly interconnected._