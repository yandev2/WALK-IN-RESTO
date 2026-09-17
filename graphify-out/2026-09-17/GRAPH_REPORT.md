# Graph Report - WALK-IN-RESTO  (2026-09-17)

## Corpus Check
- 853 files · ~399,071 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 9729 nodes · 30134 edges · 402 communities (358 shown, 44 thin omitted)
- Extraction: 92% EXTRACTED · 8% INFERRED · 0% AMBIGUOUS · INFERRED: 2514 edges (avg confidence: 0.85)
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
- Role
- OrderResource
- Je
- next
- Filament\Support\Icons\Heroicon
- PlatformSetting
- _update
- Order
- slice
- r
- get
- lo
- Illuminate\Database\Eloquent\Model
- Illuminate\Foundation\Http\FormRequest
- AdminPanelProvider.php
- TenantContext
- Filament\Resources\Pages\ListRecords
- support.js
- n
- LandingLayout
- Illuminate\Http\Request
- columns/select.js
- nodeAt
- o
- echo.js
- resolve
- fn
- facet
- LandingMenuCatalogTest
- O
- W
- ae
- Customer
- BlogPost
- Ye
- EditProfile
- UserResource
- notifications.js
- markdown-editor.js
- SubscriptionAccess
- te
- Cn
- Payment
- components/select.js
- ot
- tables.js
- eq
- r
- Filament\Tables\Table
- SubscriptionStatus
- Xt
- Illuminate\Database\Migrations\Migration
- filament-right-click.js
- fromObject
- t
- Si
- Filament\Widgets\Concerns\InteractsWithPageFilters
- vd
- selectOption
- BloggerResourceLivewireTest
- dx
- ir
- SubscriptionInvoiceResource
- slider.js
- Restaurant
- closeDropdown
- Filament\Schemas\Schema
- i
- RefreshesAnalyticsChart.php
- BlogAnalyticsService
- StoreBlogCommentRequest
- file-upload.js
- SubscriptionInvoice
- ManageLandingLayout
- BlogVisitStatsWidget
- RestaurantDirectory
- FonnteErrorMessage
- Illuminate\Database\Schema\Blueprint
- .parent
- BlogCategory
- BlogPostObserver
- Im
- devDependencies
- filament/app.js
- Dashboard
- fn
- Illuminate\View\View
- require
- scripts
- .slice
- color-picker.js
- resources/js/app.js
- g$
- create
- composer.json
- order-today-stats-widget.blade.php
- RegisterRestaurant
- date-time-picker.js
- 🚀 3. Langkah-Langkah Menambahkan Template Baru (*Workflow*)
- Dashboard
- Mt
- Filament\Resources\Pages\ViewRecord
- SitemapController.php
- GuestMenuTest
- actions/actions.js
- fd
- schemas.js
- Landasan Produksi — Tahap 1 (Walk-In Operasional)
- Guest
- GeoDistance
- 2. Masalah di lapangan — dan apa yang sistem selesaikan
- Facility
- require-dev
- BlogComment
- 6. Katalog fitur
- config
- 6. Katalog fitur
- toString
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
- CashierFilamentActionsTest
- Illuminate\Database\Eloquent\Builder
- GraceReadOnlyTest
- getDatasetMeta
- 🎨 Spesifikasi Token & Class CSS yang Tersedia di `theme.css`
- AdSetting
- CmsMedia
- User
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
- fn
- rules/graphify.md
- workflows/graphify.md
- HasSingletonForm.php
- glassmorphism/show.blade.php
- cc
- selectOption
- glassmorphism-background.blade.php
- renderOptions
- N
- dropdown.blade.php
- st
- Ae
- InvoicePaymentTest
- 2026_08_20_010000_add_floor_layout_to_tables_table.php
- Illuminate\Support\Facades\Schema
- Astrotomic\Translatable\Validation\RuleFactory
- layouts/blog.blade.php

## God Nodes (most connected - your core abstractions)
1. `User` - 407 edges
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

## Communities (402 total, 44 thin omitted)

### Community 0 - "stat/chart.js"
Cohesion: 0.01
Nodes (495): themeClasses(), Ot(), A(), aa(), acquireContext(), active(), add(), addControllers() (+487 more)

### Community 1 - "components/chart.js"
Cohesion: 0.01
Nodes (358): $a(), abutsStart(), ac(), acquireContext(), ad(), add(), addControllers(), addElements() (+350 more)

### Community 2 - "code-editor.js"
Cohesion: 0.01
Nodes (136): Ac(), addCompletion(), addCompletions(), addNamespace(), addNamespaceObject(), Ag(), AX(), b0() (+128 more)

### Community 3 - "rich-editor.js"
Cohesion: 0.01
Nodes (200): aa(), add(), addExtensions(), addHackNode(), addNodeMark(), addTextblockHacks(), an(), applyAspectRatio() (+192 more)

### Community 4 - "ExportFile"
Cohesion: 0.04
Nodes (21): TenantForceDeleteCommand, PdfExporter, CleanupOldExportFilesJob, ExportReportJob, ForceDeleteTenantJob, BlogPostTranslation, ExportFile, BlogPostTranslationObserver (+13 more)

### Community 5 - "y"
Cohesion: 0.17
Nodes (54): al(), at(), Be(), Cr(), Ct(), de(), dr(), dt() (+46 more)

### Community 6 - "TestCase"
Cohesion: 0.03
Nodes (60): CashierOrderService, OrderPaymentService, ImageOptimizer, BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles, Bityukov\CommandCenter\Filament\Pages\Commands, Bityukov\CommandCenter\Filament\Pages\History, Carbon\CarbonPeriod, RolePermissionSeeder (+52 more)

### Community 7 - "constructor"
Cohesion: 0.02
Nodes (180): active(), add(), addChunk(), addEventListener(), addInfoPane(), addInner(), addSelection(), addWindowListeners() (+172 more)

### Community 8 - "Role"
Cohesion: 0.05
Nodes (9): Role, Spatie\Permission\DefaultTeamResolver, makeOwner(), makeStaff(), KitchenDisplayPageTest, OrderReceiptPrintTest, OrderTodayStatsWidgetTest, OwnerRoleProtectionTest (+1 more)

### Community 9 - "OrderResource"
Cohesion: 0.07
Nodes (6): MenuItemResource, EditMenuItem, ListMenuItems, TrashMenuItems, OrderResource, ListOrders

### Community 10 - "Je"
Cohesion: 0.05
Nodes (62): addGlobalAttributes(), addInputRules(), addMark(), addPasteRules(), addStoredMark(), Ah(), Ax(), _c() (+54 more)

### Community 11 - "next"
Cohesion: 0.06
Nodes (44): addActive(), addChanges(), Ar(), as(), be(), bidiSpans(), checkHover(), chunkEnd() (+36 more)

### Community 12 - "Filament\Support\Icons\Heroicon"
Cohesion: 0.09
Nodes (41): TranslationTabs, Action, trashPageAction(), ViewSubscriptionInvoice, EditRestaurant, Filament\Actions\Action, Filament\Forms\Components\CheckboxList, Filament\Forms\Components\ColorPicker (+33 more)

### Community 13 - "PlatformSetting"
Cohesion: 0.03
Nodes (11): PlatformPageController, self, PlatformSetting, ReceiptLogo, PlatformSettingSeeder, AuthGlassTest, FilamentTenantThemeTest, ManageSoundNotificationsTest (+3 more)

### Community 14 - "_update"
Cohesion: 0.03
Nodes (105): active(), afterBuildTicks(), afterCalculateLabelRotation(), afterDataLimits(), afterFit(), afterSetDimensions(), afterTickToLabelConversion(), afterUpdate() (+97 more)

### Community 15 - "Order"
Cohesion: 0.03
Nodes (23): OrderReceiptDownloadController, OrderReceiptPrintController, SendWhatsappReceiptJob, CashierOrderSoundAlert, Order, OrderItem, OrderReceipt, WhatsappMessage (+15 more)

### Community 16 - "slice"
Cohesion: 0.03
Nodes (125): addActions(), addChild(), addGaps(), addLeafElement(), addNode(), advance(), advanceFully(), advanceStack() (+117 more)

### Community 17 - "r"
Cohesion: 0.04
Nodes (154): Ad(), addAttributes(), addNodeView(), addProseMirrorPlugins(), af(), au(), ay(), B0() (+146 more)

### Community 18 - "get"
Cohesion: 0.04
Nodes (100): addBlockWidget(), addBreak(), addComposition(), addDelimiter(), addInlineWidget(), addLine(), addLineStart(), addLineStartIfNotCovered() (+92 more)

### Community 19 - "lo"
Cohesion: 0.08
Nodes (31): _0(), addOptions(), buildProps(), can(), createCan(), createChain(), ff(), findDiffEnd() (+23 more)

### Community 20 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.01
Nodes (76): canDelete(), getRecord(), canForceDelete(), canRestore(), RestaurantMenuController, MenuItemResource, BlogCategoryTranslation, BlogHeroSettingTranslation (+68 more)

### Community 21 - "Illuminate\Foundation\Http\FormRequest"
Cohesion: 0.08
Nodes (8): AddCartItemRequest, CheckoutRequest, ClaimTableRequest, JoinTableRequest, StorePaymentProofRequest, StoreRestaurantReviewRequest, UpdateCartItemRequest, Illuminate\Foundation\Http\FormRequest

### Community 22 - "AdminPanelProvider.php"
Cohesion: 0.10
Nodes (30): FilamentProfilePlugin, ApplyPlatformBrandTheme, SetPermissionsTeamId, AdminPanelProvider, BloggerPanelProvider, FounderPanelProvider, AuthGlass, BezhanSalleh\FilamentShield\FilamentShieldPlugin (+22 more)

### Community 23 - "TenantContext"
Cohesion: 0.08
Nodes (8): ListCustomerReviews, ManageDiningTables, Action, TrashDiningTables, BelongsToRestaurantScope, TenantContext, Illuminate\Database\Eloquent\Scope, Livewire\Attributes\Url

### Community 24 - "Filament\Resources\Pages\ListRecords"
Cohesion: 0.03
Nodes (46): afterCreate(), afterSave(), ensureAtLeastOneTranslation(), extractTranslations(), hasTranslatableContent(), mutateFormDataBeforeCreate(), mutateFormDataBeforeSave(), persistTranslations() (+38 more)

### Community 25 - "support.js"
Cohesion: 0.05
Nodes (75): acquireScrollLock(), ai(), e(), Bi(), br(), Bt(), ca(), close() (+67 more)

### Community 26 - "n"
Cohesion: 0.08
Nodes (83): _a(), bc(), bl(), ee(), ue(), ce(), u(), cl() (+75 more)

### Community 27 - "LandingLayout"
Cohesion: 0.11
Nodes (3): LandingLayout, self, Illuminate\Support\Arr

### Community 28 - "Illuminate\Http\Request"
Cohesion: 0.02
Nodes (83): CartController, CheckoutController, MenuController, OrderController, ReviewController, SessionController, TableController, VisitController (+75 more)

### Community 29 - "columns/select.js"
Cohesion: 0.06
Nodes (57): A(), An(), applyDisabledState(), b(), Bt(), Ce(), D(), disable() (+49 more)

### Community 30 - "nodeAt"
Cohesion: 0.08
Nodes (64): Ac(), addCommands(), allowedMarks(), AS(), cellsInRect(), checkContent(), clearIncompatible(), colCount() (+56 more)

### Community 31 - "o"
Cohesion: 0.02
Nodes (257): aa(), addBox(), addEventListener(), adjustHitBoxes(), af(), ah(), Ao(), apply() (+249 more)

### Community 32 - "echo.js"
Cohesion: 0.05
Nodes (49): a(), ar(), b(), Be(), Ce(), cr(), d(), De() (+41 more)

### Community 33 - "resolve"
Cohesion: 0.06
Nodes (114): addKeyboardShortcuts(), after(), ag(), al(), allowsMarks(), before(), bl(), blockRange() (+106 more)

### Community 34 - "fn"
Cohesion: 0.13
Nodes (33): aa(), At(), ba(), cr(), da(), de(), dt(), ei() (+25 more)

### Community 35 - "facet"
Cohesion: 0.04
Nodes (83): accept(), activateHover(), Ah(), applyTransaction(), asSingle(), baseTheme(), between(), bi() (+75 more)

### Community 37 - "O"
Cohesion: 0.20
Nodes (35): b(), $c(), X(), ca(), me(), D(), _e(), f() (+27 more)

### Community 38 - "W"
Cohesion: 0.05
Nodes (70): AQ(), atLastNode(), au(), child(), childAfter(), childBefore(), continue(), cursor() (+62 more)

### Community 39 - "ae"
Cohesion: 0.09
Nodes (34): ae(), Ao(), as(), B(), Kt(), cs(), Ee(), Ge() (+26 more)

### Community 40 - "Customer"
Cohesion: 0.06
Nodes (5): Customer, CustomerLoyaltyPoint, CustomerCrmService, WhatsAppNumber, CustomerCrmLoyaltyTest

### Community 42 - "Ye"
Cohesion: 0.08
Nodes (48): Rd(), $a(), ak(), at(), bk(), c(), bp(), Dk() (+40 more)

### Community 43 - "EditProfile"
Cohesion: 0.12
Nodes (5): EditProfile, ProfileInformationForm, Ipatco\FilamentProfile\Forms\ProfileInformationForm, Ipatco\FilamentProfile\Pages\EditProfile, ProfilePageTest

### Community 44 - "UserResource"
Cohesion: 0.10
Nodes (5): CreateUser, EditUser, ListUsers, TrashUsers, UserResource

### Community 45 - "notifications.js"
Cohesion: 0.06
Nodes (31): actions(), button(), c(), close(), configureAnimations(), configureTransitions(), constructor(), danger() (+23 more)

### Community 46 - "markdown-editor.js"
Cohesion: 0.04
Nodes (95): Ac(), ad(), af(), ai(), An(), ao(), bo(), Bt() (+87 more)

### Community 47 - "SubscriptionAccess"
Cohesion: 0.04
Nodes (19): canCreate(), canEdit(), canViewAny(), CustomerAnalytics, CustomerSatisfactionAnalytics, GenerateReport, BackedEnum, UnitEnum (+11 more)

### Community 48 - "te"
Cohesion: 0.05
Nodes (8): Bn(), br(), ji(), qd(), Ri(), te(), Vi(), Xc()

### Community 49 - "Cn"
Cohesion: 0.13
Nodes (46): Cn(), b(), Be(), Ce(), De(), dn(), _e(), Fe() (+38 more)

### Community 50 - "Payment"
Cohesion: 0.10
Nodes (5): Payment, GuestCheckoutService, MenuModifierService, PaymentProofService, CashTender

### Community 51 - "components/select.js"
Cohesion: 0.08
Nodes (34): b(), Bt(), D(), E(), en(), Et(), getLabelsForMultipleSelection(), getSelectedOptionLabels() (+26 more)

### Community 52 - "ot"
Cohesion: 0.17
Nodes (17): cd(), dd(), _i(), jl(), kn(), ld(), lr(), od() (+9 more)

### Community 53 - "tables.js"
Cohesion: 0.13
Nodes (44): A(), ae(), B(), be(), C(), ce(), E(), ee() (+36 more)

### Community 54 - "eq"
Cohesion: 0.05
Nodes (57): addNode(), ao(), append(), bt(), by(), closest(), Cr(), cy() (+49 more)

### Community 55 - "r"
Cohesion: 0.15
Nodes (43): _a(), ar(), c(), f(), d(), di(), g(), Hi() (+35 more)

### Community 56 - "Filament\Tables\Table"
Cohesion: 0.04
Nodes (52): BloggersTable, TenantResource, FounderOverdueRestaurantsWidget, FounderRecentTenantsWidget, ActivityResource, ActivitiesTable, CmsBannerResource, ManageCmsBanners (+44 more)

### Community 57 - "SubscriptionStatus"
Cohesion: 0.12
Nodes (3): BackedEnum, UnitEnum, SubscriptionStatus

### Community 58 - "Xt"
Cohesion: 0.12
Nodes (44): ae(), At(), bi(), bn(), ci(), cn(), ct(), de() (+36 more)

### Community 60 - "filament-right-click.js"
Cohesion: 0.11
Nodes (42): a(), ae(), b(), be(), C(), ce(), D(), de() (+34 more)

### Community 61 - "fromObject"
Cohesion: 0.03
Nodes (107): Oe(), ae(), after(), Al(), Am(), at(), before(), Bf() (+99 more)

### Community 62 - "t"
Cohesion: 0.06
Nodes (49): a$(), activeForPoint(), addBlock(), addLineDeco(), baseDirAt(), bidiIn(), bidiSpansAt(), blankContent() (+41 more)

### Community 63 - "Si"
Cohesion: 0.14
Nodes (40): ae(), At(), bi(), bn(), ci(), cn(), ct(), de() (+32 more)

### Community 64 - "Filament\Widgets\Concerns\InteractsWithPageFilters"
Cohesion: 0.19
Nodes (4): BlogAudienceWidget, BlogCategoryDistributionWidget, BlogTrafficApexChartWidget, Filament\Widgets\Concerns\InteractsWithPageFilters

### Community 65 - "vd"
Cohesion: 0.16
Nodes (23): Ae(), ar(), as(), bf(), ci(), Cn(), co(), da() (+15 more)

### Community 66 - "selectOption"
Cohesion: 0.12
Nodes (39): addBadgesForSelectedOptions(), addSingleBadge(), addSingleSelectionDisplay(), closeDropdown(), constructor(), createBadgeElement(), createOptionElement(), createRemoveButton() (+31 more)

### Community 68 - "dx"
Cohesion: 0.13
Nodes (29): Ei(), Aa(), Ba(), Bi(), Gr(), Jr(), Kr(), ls() (+21 more)

### Community 69 - "ir"
Cohesion: 0.14
Nodes (33): Ft(), ir(), ce(), de(), Dt(), ee(), Et(), fe() (+25 more)

### Community 70 - "SubscriptionInvoiceResource"
Cohesion: 0.08
Nodes (8): BlogReferrersWidget, TopBloggersWidget, TopBlogPostsWidget, CreateSubscriptionInvoice, SubscriptionInvoiceResource, FounderPendingInvoicesWidget, FounderStatsWidget, Filament\Widgets\TableWidget

### Community 71 - "slider.js"
Cohesion: 0.11
Nodes (33): ar(), Be(), Ce(), De(), _e(), Ee(), er(), et() (+25 more)

### Community 72 - "Restaurant"
Cohesion: 0.02
Nodes (48): ExpireStaleOperationsCommand, FinalizeCashierCommissionCommand, GenerateUpcomingInvoicesCommand, ProcessSubscriptionLifecycleCommand, SendCashierCommissionReminderCommand, analyticsDateFrom(), analyticsDateTo(), analyticsDayCount() (+40 more)

### Community 73 - "closeDropdown"
Cohesion: 0.23
Nodes (17): applyDisabledState(), closeDropdown(), constructor(), destroy(), disable(), enable(), focusNextOption(), focusPreviousOption() (+9 more)

### Community 74 - "Filament\Schemas\Schema"
Cohesion: 0.02
Nodes (43): SeoFields, BlogAnalytics, BlogCategoryResource, BlogCategoryForm, BlogCategoryInfolist, BlogCategoriesTable, BlogCommentForm, BlogCommentInfolist (+35 more)

### Community 75 - "i"
Cohesion: 0.04
Nodes (145): aa(), addElement(), b1(), balance(), balanced(), baseIndent(), baseIndentFor(), Bg() (+137 more)

### Community 76 - "RefreshesAnalyticsChart.php"
Cohesion: 0.36
Nodes (8): generateChartDataChecksum(), getCachedChartData(), getChartData(), mountRefreshesAnalyticsChart(), refreshAnalyticsChartData(), rendering(), updateChartData(), Livewire\Attributes\Locked

### Community 77 - "BlogAnalyticsService"
Cohesion: 0.14
Nodes (4): VisitLog, BlogAnalyticsService, Carbon\Carbon, Illuminate\Database\Eloquent\Relations\MorphTo

### Community 78 - "StoreBlogCommentRequest"
Cohesion: 0.19
Nodes (5): BlogCommentController, StoreBlogCommentRequest, ValidBlogCommentParent, Illuminate\Contracts\Validation\ValidationRule, Illuminate\Http\RedirectResponse

### Community 79 - "file-upload.js"
Cohesion: 0.05
Nodes (52): hc(), Bp(), c(), ca(), clickPercent(), constructor(), Cp(), da() (+44 more)

### Community 80 - "SubscriptionInvoice"
Cohesion: 0.04
Nodes (8): SubscriptionInvoice, SubscriptionPlan, FounderAnalyticsService, SubscriptionInvoiceService, SubscriptionPlanSeeder, CashierCommissionBillingTest, FounderDashboardAnalyticsTest, KdsSubscriptionLockTest

### Community 81 - "ManageLandingLayout"
Cohesion: 0.24
Nodes (4): ManageLandingLayout, BackedEnum, Closure, UnitEnum

### Community 82 - "BlogVisitStatsWidget"
Cohesion: 0.19
Nodes (4): Dashboard, BlogContentProgressWidget, BlogVisitStatsWidget, Filament\Pages\Dashboard\Concerns\HasFiltersForm

### Community 84 - "FonnteErrorMessage"
Cohesion: 0.11
Nodes (8): FonnteClient, FonnteErrorMessage, Throwable, Illuminate\Http\Client\ConnectionException, Illuminate\Http\Client\Response, PHPUnit\Framework\Attributes\DataProvider, RuntimeException, FonnteErrorMessageTest

### Community 87 - ".parent"
Cohesion: 0.04
Nodes (19): Login, getRecordTitle(), BlogPostsRelationManager, BlogPostResource, CreateBlogPost, EditBlogPost, ViewBlogPost, CommentsRelationManager (+11 more)

### Community 88 - "BlogCategory"
Cohesion: 0.07
Nodes (8): BlogCategory, BlogHeroSetting, self, BlogTag, Astrotomic\Translatable\Contracts\Translatable, Astrotomic\Translatable\Translatable, BlogAnalyticsTest, BlogSeoAndMediaTest

### Community 90 - "Im"
Cohesion: 0.31
Nodes (10): Bm(), eat(), err(), Im(), isInGroup(), Lm(), o1(), pc() (+2 more)

### Community 91 - "devDependencies"
Cohesion: 0.09
Nodes (21): apexcharts, axios, concurrently, laravel-vite-plugin, dependencies, apexcharts, devDependencies, axios (+13 more)

### Community 92 - "filament/app.js"
Cohesion: 0.13
Nodes (8): B(), close(), G(), init(), P(), setUpResizeObserver(), x(), Y()

### Community 93 - "Dashboard"
Cohesion: 0.25
Nodes (5): Dashboard, BezhanSalleh\FilamentShield\Resources\Roles\RoleResource, Filament\Pages\Dashboard, Filament\Widgets\AccountWidget, Filament\Widgets\FilamentInfoWidget

### Community 94 - "fn"
Cohesion: 0.22
Nodes (18): An(), Ce(), ei(), fn(), Ft(), Ie(), Le(), ni() (+10 more)

### Community 95 - "Illuminate\View\View"
Cohesion: 0.37
Nodes (3): BlogController, RecordVisitService, Illuminate\View\View

### Community 96 - "require"
Cohesion: 0.12
Nodes (17): require, astrotomic/laravel-translatable, barryvdh/laravel-dompdf, bezhansalleh/filament-shield, endroid/qr-code, filament/filament, hammadzafar05/filament-mobile-preset, ipatco/filament-profile (+9 more)

### Community 97 - "scripts"
Cohesion: 0.12
Nodes (16): scripts, dev, post-autoload-dump, post-create-project-cmd, post-root-package-install, post-update-cmd, Composer\\Config::disableProcessTimeout, Illuminate\\Foundation\\ComposerScripts::postAutoloadDump (+8 more)

### Community 98 - ".slice"
Cohesion: 0.05
Nodes (64): accepts(), addInner(), addMaps(), addStep(), addTransform(), appendMap(), appendMapping(), appendMappingInverted() (+56 more)

### Community 99 - "color-picker.js"
Cohesion: 0.14
Nodes (3): style(), update(), [x]()

### Community 100 - "resources/js/app.js"
Cohesion: 0.12
Nodes (6): applyTheme(), bindThemeToggle(), initReveal(), initTheme(), syncThemeToggleIcons(), toggleTheme()

### Community 101 - "g$"
Cohesion: 0.06
Nodes (50): acceptToken(), aO(), charCategorizer(), d0(), De(), Dg(), E$(), eh() (+42 more)

### Community 102 - "create"
Cohesion: 0.07
Nodes (70): addAll(), addDOM(), addElement(), addElementByRule(), addTextNode(), addToSet(), allowsMarkType(), bu() (+62 more)

### Community 103 - "composer.json"
Cohesion: 0.14
Nodes (13): autoload-dev, psr-4, description, keywords, license, minimum-stability, name, prefer-stable (+5 more)

### Community 107 - "date-time-picker.js"
Cohesion: 0.26
Nodes (8): d(), e(), i(), m(), r(), s(), t(), rr()

### Community 108 - "🚀 3. Langkah-Langkah Menambahkan Template Baru (*Workflow*)"
Cohesion: 0.12
Nodes (15): 📌 1. Prinsip Utama & Aturan Wajib (Golden Rules), 🏗️ 2. Arsitektur 10 Core Section CMS (1:1 Mapping), 🚀 3. Langkah-Langkah Menambahkan Template Baru (*Workflow*), 📊 4. Variabel yang Disediakan oleh `LandingPageDataService`, 💎 5. Pelajaran Desain & Best Practices UI/UX (*Key Learnings*), A. Larangan Keras Menggunakan Emoji Unicode (Gunakan SVG Heroicons), B. Arsitektur Layering 3D & Background Tembus Pandang (*Stacking Context*), C. Resep Glassmorphism iOS yang Nyata (*True Frosted Glass*) (+7 more)

### Community 109 - "Dashboard"
Cohesion: 0.05
Nodes (7): Dashboard, RestaurantCategory, FacilitySeeder, RestaurantCategorySeeder, RestaurantDirectoryTest, RestaurantRegistrationStepperTest, TenantIsolationTest

### Community 110 - "Mt"
Cohesion: 0.24
Nodes (11): apply(), fs(), go(), Hr(), T(), ir(), it(), Mt() (+3 more)

### Community 111 - "Filament\Resources\Pages\ViewRecord"
Cohesion: 0.08
Nodes (8): ViewBlogCategory, ViewBlogger, ViewActivity, CashierShiftResource, ListCashierShifts, ViewCashierShift, ActivityPresenter, Filament\Resources\Pages\ViewRecord

### Community 112 - "SitemapController.php"
Cohesion: 0.38
Nodes (3): SitemapController, MediaUrl, Illuminate\Http\Response

### Community 115 - "actions/actions.js"
Cohesion: 0.44
Nodes (8): closeModal(), generateModalId(), getActionNestingIndexFromModalId(), init(), openModal(), rememberPreviouslyFocusedElement(), restorePreviouslyFocusedElement(), syncActionModals()

### Community 116 - "fd"
Cohesion: 0.13
Nodes (22): addToSet(), bd(), Bh(), childString(), clearDelayedAndroidKey(), delayAndroidKey(), fd(), flush() (+14 more)

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

### Community 122 - "Facility"
Cohesion: 0.13
Nodes (4): ManageCmsProfile, BackedEnum, UnitEnum, Facility

### Community 123 - "require-dev"
Cohesion: 0.25
Nodes (8): require-dev, fakerphp/faker, laravel/pail, laravel/pint, laravel/sail, mockery/mockery, nunomaduro/collision, phpunit/phpunit

### Community 124 - "BlogComment"
Cohesion: 0.07
Nodes (10): BlogCommentResource, CreateBlogComment, EditBlogComment, ListBlogComments, ViewBlogComment, BlogCommentsTable, BlogComment, BlogCommentObserver (+2 more)

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
Cohesion: 0.14
Nodes (19): Bc(), check(), checkAttrs(), cn(), endIndex(), getObj(), hasProtocol(), Rc() (+11 more)

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

### Community 163 - "Illuminate\Database\Eloquent\Builder"
Cohesion: 0.04
Nodes (35): CommissionReconciliation, BackedEnum, UnitEnum, Width, KitchenDisplay, BackedEnum, UnitEnum, Width (+27 more)

### Community 164 - "GraceReadOnlyTest"
Cohesion: 0.16
Nodes (4): BlockGraceMutations, SubscriptionWriteGuard, Livewire\ComponentHook, GraceReadOnlyTest

### Community 173 - "getDatasetMeta"
Cohesion: 0.09
Nodes (30): afterDatasetsUpdate(), An(), ef(), generateLabels(), getDatasetMeta(), getDataVisibility(), _getLegendItemAt(), getMaxBorderWidth() (+22 more)

### Community 176 - "🎨 Spesifikasi Token & Class CSS yang Tersedia di `theme.css`"
Cohesion: 0.15
Nodes (12): 1. Kartu Kontainer Utama (`.vision-card`), 2. Kotak Ikon Gradien Neon (`.vision-icon-box`), 3. Kapsul Metrik / Sub-Kartu (`.vision-pill-card` atau `.vision-pill-dark`), 4. Kartu Filter (`.vision-filter-card`), 5. Tabel Data Kaca (`.vision-table-card`), 6. Form Section & Skema Input Kaca (`.vision-card` pada `Section::make`), 7. Kartu Statistik Global (`StatsOverviewWidget` / `Stat::make`), ⚙️ Aturan Wajib untuk AI Developer (+4 more)

### Community 177 - "AdSetting"
Cohesion: 0.07
Nodes (7): AdSetting, self, AdPlacementService, Head, Slot, Illuminate\View\Component, AdIntegrationTest

### Community 179 - "CmsMedia"
Cohesion: 0.02
Nodes (34): TemplateRadioPicker, FounderRevenueGrowthApexChartWidget, FounderSubscriptionHealthApexChartWidget, OrderTodayStatsWidget, AnalyticsKpiWidget, AnalyticsPeriodSummaryWidget, AnalyticsRevenueBarWidget, AnalyticsSidebarWidget (+26 more)

### Community 183 - "User"
Cohesion: 0.02
Nodes (35): CashierShiftPrintController, Activity, CashierShift, HasMany, LogOptions, static, User, RolePolicy (+27 more)

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

### Community 306 - "fn"
Cohesion: 0.16
Nodes (20): Ck(), De(), fn(), Gh(), ip(), Ja(), Jh(), Ji() (+12 more)

### Community 320 - "HasSingletonForm.php"
Cohesion: 0.08
Nodes (26): mutateFormDataBeforeFill(), ManageBlogHero, BlogHeroFormSchema, afterCreate(), afterSave(), ensureAtLeastOneTranslation(), extractTranslations(), hasTranslatableContent() (+18 more)

### Community 322 - "glassmorphism/show.blade.php"
Cohesion: 0.29
Nodes (6): landing.templates.glassmorphism.sections., landing.templates.glassmorphism.sections.hero, landing.sections., landing.templates.glassmorphism.partials.background, landing.templates.glassmorphism.sections.footer, landing.templates.glassmorphism.sections.header

### Community 324 - "cc"
Cohesion: 0.38
Nodes (7): attrs(), cc(), JQ(), m$(), Ow(), rc(), read()

### Community 327 - "selectOption"
Cohesion: 0.24
Nodes (12): addBadgesForSelectedOptions(), addSingleBadge(), addSingleSelectionDisplay(), createBadgeElement(), createRemoveButton(), getLabelForSingleSelection(), getSelectedOptionLabel(), hideMaxItemsMessage() (+4 more)

### Community 347 - "renderOptions"
Cohesion: 0.37
Nodes (13): createOptionElement(), deferPositionDropdown(), filterOptions(), handleSearch(), hideLoadingState(), openDropdown(), populateLabelRepositoryFromOptions(), positionDropdown() (+5 more)

### Community 350 - "N"
Cohesion: 0.33
Nodes (11): ae(), A(), E(), at(), be(), Gt(), i(), Jt() (+3 more)

### Community 355 - "st"
Cohesion: 0.24
Nodes (11): [g](), _freeze(), getAllExtensions(), Ct(), lt(), ot(), se(), st() (+3 more)

### Community 358 - "Ae"
Cohesion: 0.67
Nodes (3): Ae(), Bt(), ne()

## Knowledge Gaps
- **345 isolated node(s):** `$schema`, `name`, `type`, `description`, `laravel` (+340 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **44 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `User` connect `User` to `ExportFile`, `BloggerPanelTest`, `TestCase`, `Role`, `Filament\Support\Icons\Heroicon`, `PlatformSetting`, `Order`, `Illuminate\Database\Eloquent\Model`, `AdminPanelProvider.php`, `Filament\Resources\Pages\ListRecords`, `Illuminate\Http\Request`, `CashierFilamentActionsTest`, `Illuminate\Database\Eloquent\Builder`, `GraceReadOnlyTest`, `Customer`, `BlogPost`, `UserResource`, `SubscriptionAccess`, `AdSetting`, `Payment`, `CmsMedia`, `Filament\Tables\Table`, `Filament\Widgets\Concerns\InteractsWithPageFilters`, `BloggerResourceLivewireTest`, `SubscriptionInvoiceResource`, `Restaurant`, `Filament\Schemas\Schema`, `SubscriptionInvoice`, `.parent`, `BlogCategory`, `RegisterRestaurant`, `Dashboard`, `BlogComment`?**
  _High betweenness centrality (0.030) - this node is a cross-community bridge._
- **Why does `Restaurant` connect `Restaurant` to `ExportFile`, `TestCase`, `Role`, `Filament\Support\Icons\Heroicon`, `PlatformSetting`, `Order`, `Illuminate\Database\Eloquent\Model`, `AdminPanelProvider.php`, `TenantContext`, `Filament\Resources\Pages\ListRecords`, `LandingLayout`, `Illuminate\Http\Request`, `CashierFilamentActionsTest`, `Illuminate\Database\Eloquent\Builder`, `GraceReadOnlyTest`, `LandingMenuCatalogTest`, `Customer`, `UserResource`, `SubscriptionAccess`, `CmsMedia`, `User`, `Filament\Tables\Table`, `SubscriptionStatus`, `SubscriptionInvoiceResource`, `SubscriptionInvoice`, `RegisterRestaurant`, `Dashboard`, `SitemapController.php`, `Facility`?**
  _High betweenness centrality (0.025) - this node is a cross-community bridge._
- **Why does `update()` connect `constructor` to `stat/chart.js`, `components/chart.js`, `code-editor.js`, `rich-editor.js`, `next`, `slice`, `r`, `get`, `n`, `echo.js`, `resolve`, `facet`, `O`, `W`, `Ye`, `markdown-editor.js`, `te`, `t`, `dx`, `i`, `g$`, `fd`?**
  _High betweenness centrality (0.017) - this node is a cross-community bridge._
- **What connects `$schema`, `name`, `type` to the rest of the system?**
  _345 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `stat/chart.js` be split into smaller, more focused modules?**
  _Cohesion score 0.010697215689249678 - nodes in this community are weakly interconnected._
- **Should `components/chart.js` be split into smaller, more focused modules?**
  _Cohesion score 0.007446536593678112 - nodes in this community are weakly interconnected._
- **Should `code-editor.js` be split into smaller, more focused modules?**
  _Cohesion score 0.008718773188226564 - nodes in this community are weakly interconnected._