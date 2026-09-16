# Graph Report - WALK-IN-RESTO  (2026-09-16)

## Corpus Check
- 850 files · ~394,751 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 9682 nodes · 30058 edges · 403 communities (359 shown, 44 thin omitted)
- Extraction: 91% EXTRACTED · 9% INFERRED · 0% AMBIGUOUS · INFERRED: 2589 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `32a33f54`
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
- fromObject
- Filament\Schemas\Schema
- lo
- find
- SoftDeleteTrashPage
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
- Outlet
- facet
- support.js
- Ae
- AppServiceProvider.php
- GuestContext
- columns/select.js
- child
- draw
- echo.js
- resolve
- fn
- of
- BlogAnalytics.php
- SubscriptionInvoiceResource
- W
- from
- Customer
- find
- .append
- EditProfile
- CashierShift
- notifications.js
- markdown-editor.js
- CommissionReconciliation
- te
- Cn
- OrderResource
- components/select.js
- Activity
- tables.js
- ye
- r
- ae
- SubscriptionStatus
- Xt
- Illuminate\Database\Migrations\Migration
- filament-right-click.js
- ir
- t
- Si
- ImageOptimizer
- parse
- selectOption
- CashierCommissionBillingService
- MenuVariant
- ir
- Filament\Tables\Table
- slider.js
- RestaurantAnalyticsPeriod
- closeDropdown
- ManageBillingAccount
- slice
- RefreshesAnalyticsChart.php
- configure
- join
- file-upload.js
- Login
- GraceReadOnlyTest
- DailyOmzetServiceTest
- RestaurantDirectory
- FonnteErrorMessage
- Visit
- devDependencies
- filament/app.js
- fn
- FilamentTranslatable
- require
- scripts
- .slice
- color-picker.js
- resources/js/app.js
- sliceDoc
- eq
- composer.json
- order-today-stats-widget.blade.php
- RegisterRestaurant
- date-time-picker.js
- 🚀 3. Langkah-Langkah Menambahkan Template Baru (*Workflow*)
- RestaurantDirectoryTest
- Mt
- Illuminate\Database\Eloquent\Builder
- ManageSoundNotifications
- Filament\Resources\Pages\ListRecords
- actions/actions.js
- schemas.js
- Landasan Produksi — Tahap 1 (Walk-In Operasional)
- Guest
- RestaurantDirectory
- 2. Masalah di lapangan — dan apa yang sistem selesaikan
- Role
- require-dev
- 6. Katalog fitur
- config
- 6. Katalog fitur
- register-restaurant.blade.php
- add-to-cart-modal.blade.php
- components/actions.js
- psr-4
- extra
- logging.php
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
- closeSimpleModeModal
- Illuminate\Database\Eloquent\Model
- renderOptions
- Illuminate\Database\Schema\Blueprint
- CashierFilamentActionsTest
- Filament\Widgets\Widget
- SubscriptionWriteGuard
- DailyOmzetService
- InteractsWithRestaurantAnalytics.php
- 🎨 Spesifikasi Token & Class CSS yang Tersedia di `theme.css`
- AdSetting
- MenuCategory
- n
- User
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
- CreateCashierOrder
- classic/show.blade.php
- DiningTable
- rules/graphify.md
- workflows/graphify.md
- HasSingletonForm.php
- Dashboard
- glassmorphism/show.blade.php
- Filament\Resources\Pages\CreateRecord
- selectOption
- Restaurant
- glassmorphism-background.blade.php
- Illuminate\Http\Request
- dropdown.blade.php
- CommissionReconciliationService
- BlogPostObserver
- O
- InvoicePaymentTest
- replace
- ExcelExporter
- 2026_08_20_010000_add_floor_layout_to_tables_table.php
- Illuminate\Support\Facades\Schema
- Astrotomic\Translatable\Validation\RuleFactory
- layouts/blog.blade.php

## God Nodes (most connected - your core abstractions)
1. `User` - 394 edges
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

## Communities (403 total, 44 thin omitted)

### Community 0 - "stat/chart.js"
Cohesion: 0.01
Nodes (486): themeClasses(), Ot(), A(), aa(), acquireContext(), active(), add(), addControllers() (+478 more)

### Community 1 - "components/chart.js"
Cohesion: 0.01
Nodes (337): $a(), abutsStart(), ad(), add(), addControllers(), addElements(), addPlugins(), addScales() (+329 more)

### Community 2 - "code-editor.js"
Cohesion: 0.01
Nodes (136): aa(), Ac(), addActive(), addCompletion(), addCompletions(), addEventListener(), addNamespace(), addNamespaceObject() (+128 more)

### Community 3 - "rich-editor.js"
Cohesion: 0.01
Nodes (257): $a(), aa(), Ad(), addExtensions(), addHackNode(), addNode(), addNodeMark(), addTextblockHacks() (+249 more)

### Community 4 - "ExportFile"
Cohesion: 0.04
Nodes (21): TenantForceDeleteCommand, PdfExporter, CleanupOldExportFilesJob, ExportReportJob, ForceDeleteTenantJob, SendWhatsappReceiptJob, ExportFile, ExportFileObserver (+13 more)

### Community 5 - "y"
Cohesion: 0.19
Nodes (50): at(), Be(), Cr(), Ct(), de(), dr(), dt(), Ee() (+42 more)

### Community 6 - "TestCase"
Cohesion: 0.03
Nodes (58): MenuItem, CashierOrderService, OrderPaymentService, SubscriptionPlanSync, BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles, Bityukov\CommandCenter\Filament\Pages\Commands, Bityukov\CommandCenter\Filament\Pages\History, Carbon\CarbonPeriod (+50 more)

### Community 7 - "constructor"
Cohesion: 0.03
Nodes (132): add(), addChunk(), addInfoPane(), addInner(), adjust(), al(), annotation(), applyEdits() (+124 more)

### Community 8 - "fromObject"
Cohesion: 0.03
Nodes (117): mm(), Oe(), ac(), ae(), after(), Al(), Am(), before() (+109 more)

### Community 9 - "Filament\Schemas\Schema"
Cohesion: 0.04
Nodes (65): TranslationTabs, BlogHeroFormSchema, BlogCategoryForm, BlogCategoryInfolist, BlogTagForm, ManageAdSettings, BackedEnum, UnitEnum (+57 more)

### Community 10 - "lo"
Cohesion: 0.08
Nodes (31): _0(), addOptions(), buildProps(), can(), createCan(), createChain(), ff(), findDiffEnd() (+23 more)

### Community 11 - "find"
Cohesion: 0.09
Nodes (35): activateHover(), baseDirAt(), bidiIn(), bidiSpans(), bidiSpansAt(), bP(), cd(), checkHover() (+27 more)

### Community 12 - "SoftDeleteTrashPage"
Cohesion: 0.04
Nodes (23): SoftDeleteTrashPage, CmsBannerResource, ManageCmsBanners, TrashCmsBanners, ManageCmsFaqs, TrashCmsFaqs, ManageCmsGalleryImages, TrashCmsGalleryImages (+15 more)

### Community 13 - "PlatformSetting"
Cohesion: 0.06
Nodes (5): self, PlatformSetting, PlatformSettingSeeder, AuthGlassTest, PlatformSettingTest

### Community 14 - "_update"
Cohesion: 0.03
Nodes (103): active(), afterBuildTicks(), afterCalculateLabelRotation(), afterDataLimits(), afterDatasetsUpdate(), afterDraw(), afterFit(), afterSetDimensions() (+95 more)

### Community 15 - "Order"
Cohesion: 0.03
Nodes (23): OrderReceiptDownloadController, OrderReceiptPrintController, CashierOrderSoundAlert, Order, OrderItem, KdsItemService, OrderReceiptService, OrderVoidService (+15 more)

### Community 16 - "advance"
Cohesion: 0.03
Nodes (125): acceptToken(), addActions(), addChild(), addGaps(), addLeafElement(), addNode(), advance(), advanceFully() (+117 more)

### Community 17 - "r"
Cohesion: 0.04
Nodes (137): xQ(), addNodeView(), af(), ao(), append(), au(), ay(), bl() (+129 more)

### Community 18 - "get"
Cohesion: 0.03
Nodes (102): addBlockWidget(), addBreak(), addComposition(), addDelimiter(), addInlineWidget(), addLine(), addLineStart(), addLineStartIfNotCovered() (+94 more)

### Community 19 - "o"
Cohesion: 0.03
Nodes (194): addBox(), addEventListener(), af(), afterAutoSkip(), apply(), ar(), As(), at() (+186 more)

### Community 20 - "Illuminate\Database\Eloquent\Relations\BelongsTo"
Cohesion: 0.02
Nodes (44): CashierShiftMovement, CmsBanner, LogOptions, CmsFaq, LogOptions, CmsGalleryImage, CmsProfile, LogOptions (+36 more)

### Community 21 - "Illuminate\Foundation\Http\FormRequest"
Cohesion: 0.06
Nodes (13): BlogCommentController, AddCartItemRequest, CheckoutRequest, ClaimTableRequest, JoinTableRequest, StorePaymentProofRequest, StoreRestaurantReviewRequest, UpdateCartItemRequest (+5 more)

### Community 22 - "AdminPanelProvider.php"
Cohesion: 0.12
Nodes (26): ApplyPlatformBrandTheme, AdminPanelProvider, BloggerPanelProvider, FounderPanelProvider, AuthGlass, BezhanSalleh\FilamentShield\FilamentShieldPlugin, Bityukov\CommandCenter\Filament\CommandCenterPlugin, Filament\Http\Middleware\Authenticate (+18 more)

### Community 23 - "Outlet"
Cohesion: 0.05
Nodes (9): KdsStation, Outlet, OutletOperatingHour, RestaurantProvisioner, DirectoryDemoRestaurantsSeeder, LandingMenuHiddenPriceTest, OutletHoursTest, RestaurantRegistrationStepperTest (+1 more)

### Community 24 - "facet"
Cohesion: 0.06
Nodes (53): accept(), Ah(), applyTransaction(), asSingle(), build(), composeDesc(), create(), dl() (+45 more)

### Community 25 - "support.js"
Cohesion: 0.05
Nodes (75): acquireScrollLock(), ai(), e(), Bi(), br(), Bt(), ca(), close() (+67 more)

### Community 26 - "Ae"
Cohesion: 0.08
Nodes (85): _a(), Ae(), bc(), bl(), ue(), ce(), u(), cl() (+77 more)

### Community 27 - "AppServiceProvider.php"
Cohesion: 0.06
Nodes (24): BlogCommentResource, CreateBlogComment, EditBlogComment, ViewBlogComment, BlogCommentForm, BlogCommentInfolist, BlogCommentsTable, BlogComment (+16 more)

### Community 28 - "GuestContext"
Cohesion: 0.03
Nodes (38): CartController, CheckoutController, MenuController, OrderController, ReviewController, SessionController, TableController, VisitController (+30 more)

### Community 29 - "columns/select.js"
Cohesion: 0.06
Nodes (57): A(), An(), applyDisabledState(), b(), Bt(), Ce(), D(), disable() (+49 more)

### Community 30 - "child"
Cohesion: 0.06
Nodes (79): Ac(), add(), addCommands(), allowsMarks(), AS(), cellsInRect(), checkContent(), child() (+71 more)

### Community 31 - "draw"
Cohesion: 0.04
Nodes (99): aa(), acquireContext(), adjustHitBoxes(), Ao(), aspectRatio(), bh(), buildTicks(), cd() (+91 more)

### Community 32 - "echo.js"
Cohesion: 0.05
Nodes (49): a(), ar(), b(), Be(), Ce(), cr(), d(), De() (+41 more)

### Community 33 - "resolve"
Cohesion: 0.07
Nodes (110): addAttributes(), addKeyboardShortcuts(), after(), ag(), al(), before(), blockRange(), Bs() (+102 more)

### Community 34 - "fn"
Cohesion: 0.13
Nodes (33): aa(), At(), ba(), cr(), da(), de(), dt(), ei() (+25 more)

### Community 35 - "of"
Cohesion: 0.05
Nodes (70): active(), apply(), B(), baseTheme(), between(), bi(), blur(), bu() (+62 more)

### Community 36 - "BlogAnalytics.php"
Cohesion: 0.05
Nodes (12): BlogAnalytics, Dashboard, BlogAudienceWidget, BlogCategoryDistributionWidget, BlogContentProgressWidget, BlogReferrersWidget, BlogTrafficApexChartWidget, BlogVisitStatsWidget (+4 more)

### Community 37 - "SubscriptionInvoiceResource"
Cohesion: 0.07
Nodes (9): SubscriptionInvoiceResource, CreateTenant, EditTenant, TenantResource, FounderOverdueRestaurantsWidget, FounderPendingInvoicesWidget, FounderRecentTenantsWidget, FounderStatsWidget (+1 more)

### Community 38 - "W"
Cohesion: 0.05
Nodes (74): AQ(), atLastNode(), au(), ch(), child(), childAfter(), childBefore(), cursor() (+66 more)

### Community 39 - "from"
Cohesion: 0.11
Nodes (31): allowedMarks(), bu(), _c(), close(), closeFrontierNode(), computeWrapping(), cutByIndex(), defaultType() (+23 more)

### Community 40 - "Customer"
Cohesion: 0.08
Nodes (4): Customer, CustomerLoyaltyPoint, CustomerCrmService, CustomerCrmLoyaltyTest

### Community 41 - "find"
Cohesion: 0.08
Nodes (40): addGlobalAttributes(), addInputRules(), addPasteRules(), Ah(), Ax(), dispatchTransaction(), dn(), Eh() (+32 more)

### Community 42 - ".append"
Cohesion: 0.09
Nodes (43): ak(), at(), bk(), c(), bp(), bt(), Cr(), cy() (+35 more)

### Community 43 - "EditProfile"
Cohesion: 0.10
Nodes (7): EditProfile, FilamentProfilePlugin, ProfileInformationForm, Ipatco\FilamentProfile\FilamentProfilePlugin, Ipatco\FilamentProfile\Forms\ProfileInformationForm, Ipatco\FilamentProfile\Pages\EditProfile, ProfilePageTest

### Community 44 - "CashierShift"
Cohesion: 0.11
Nodes (4): CashierShiftPrintController, CashierShift, CashierShiftService, PermissionCheck

### Community 45 - "notifications.js"
Cohesion: 0.06
Nodes (31): actions(), button(), c(), close(), configureAnimations(), configureTransitions(), constructor(), danger() (+23 more)

### Community 46 - "markdown-editor.js"
Cohesion: 0.04
Nodes (111): Ac(), ad(), af(), ai(), al(), An(), ao(), ar() (+103 more)

### Community 47 - "CommissionReconciliation"
Cohesion: 0.06
Nodes (15): CommissionReconciliation, BackedEnum, UnitEnum, Width, CustomerAnalytics, CustomerSatisfactionAnalytics, GenerateReport, BackedEnum (+7 more)

### Community 48 - "te"
Cohesion: 0.05
Nodes (9): Rd(), Bn(), br(), ji(), qd(), Ri(), te(), Vi() (+1 more)

### Community 49 - "Cn"
Cohesion: 0.13
Nodes (46): Cn(), b(), Be(), Ce(), De(), dn(), _e(), Fe() (+38 more)

### Community 50 - "OrderResource"
Cohesion: 0.08
Nodes (5): MenuItemResource, EditMenuItem, TrashMenuItems, OrderResource, ListOrders

### Community 51 - "components/select.js"
Cohesion: 0.08
Nodes (34): b(), Bt(), D(), E(), en(), Et(), getLabelsForMultipleSelection(), getSelectedOptionLabels() (+26 more)

### Community 52 - "Activity"
Cohesion: 0.10
Nodes (6): ActivityResource, ListActivities, ViewActivity, Activity, ActivityPresenter, Spatie\Activitylog\Models\Activity

### Community 53 - "tables.js"
Cohesion: 0.13
Nodes (44): A(), ae(), B(), be(), C(), ce(), E(), ee() (+36 more)

### Community 54 - "ye"
Cohesion: 0.17
Nodes (20): cd(), ee(), Et(), Fc(), _i(), ii(), ld(), od() (+12 more)

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

### Community 61 - "ir"
Cohesion: 0.16
Nodes (17): bu(), dataset(), Do(), index(), ir(), isPointInArea(), jd(), Ni() (+9 more)

### Community 62 - "t"
Cohesion: 0.07
Nodes (45): a$(), activeForPoint(), addBlock(), addLineDeco(), attrs(), blankContent(), boundChange(), cc() (+37 more)

### Community 63 - "Si"
Cohesion: 0.14
Nodes (40): ae(), At(), bi(), bn(), ci(), cn(), ct(), de() (+32 more)

### Community 64 - "ImageOptimizer"
Cohesion: 0.20
Nodes (3): ImageOptimizer, ImageUploadAuditTest, ImageOptimizerTest

### Community 65 - "parse"
Cohesion: 0.26
Nodes (12): Bm(), eat(), err(), Im(), isInGroup(), Lm(), $m(), o1() (+4 more)

### Community 66 - "selectOption"
Cohesion: 0.12
Nodes (39): addBadgesForSelectedOptions(), addSingleBadge(), addSingleSelectionDisplay(), closeDropdown(), constructor(), createBadgeElement(), createOptionElement(), createRemoveButton() (+31 more)

### Community 67 - "CashierCommissionBillingService"
Cohesion: 0.14
Nodes (9): ExpireStaleOperationsCommand, FinalizeCashierCommissionCommand, ProcessSubscriptionLifecycleCommand, SendCashierCommissionReminderCommand, CashierCommissionBillingService, Illuminate\Console\Command, Illuminate\Foundation\Inspiring, Illuminate\Support\Facades\Artisan (+1 more)

### Community 68 - "MenuVariant"
Cohesion: 0.06
Nodes (8): MenuVariant, Modifier, CashierMenuCatalog, CashierOrderPreview, CheckoutTotals, Illuminate\Support\Facades\Cache, CashierMenuCatalogTest, CashierOrderPreviewTest

### Community 69 - "ir"
Cohesion: 0.11
Nodes (49): Ft(), ir(), ae(), A(), E(), at(), be(), ce() (+41 more)

### Community 70 - "Filament\Tables\Table"
Cohesion: 0.09
Nodes (37): BlogCategoriesTable, ActivitiesTable, CmsFaqResource, CmsGalleryImageResource, CustomerReviewResource, DiningTableResource, KdsStationResource, ModifierGroupResource (+29 more)

### Community 71 - "slider.js"
Cohesion: 0.09
Nodes (38): Ae(), ar(), Be(), Bt(), Ce(), De(), _e(), Ee() (+30 more)

### Community 72 - "RestaurantAnalyticsPeriod"
Cohesion: 0.22
Nodes (4): Carbon, RestaurantAnalyticsPeriod, Carbon\CarbonInterface, RestaurantAnalyticsServiceTest

### Community 73 - "closeDropdown"
Cohesion: 0.23
Nodes (17): applyDisabledState(), closeDropdown(), constructor(), destroy(), disable(), enable(), focusNextOption(), focusPreviousOption() (+9 more)

### Community 74 - "ManageBillingAccount"
Cohesion: 0.24
Nodes (3): ManageBillingAccount, BackedEnum, UnitEnum

### Community 75 - "slice"
Cohesion: 0.04
Nodes (152): addChanges(), addElement(), b1(), baseIndentFor(), be(), Bg(), a(), blockAt() (+144 more)

### Community 76 - "RefreshesAnalyticsChart.php"
Cohesion: 0.36
Nodes (8): generateChartDataChecksum(), getCachedChartData(), getChartData(), mountRefreshesAnalyticsChart(), refreshAnalyticsChartData(), rendering(), updateChartData(), Livewire\Attributes\Locked

### Community 77 - "configure"
Cohesion: 0.17
Nodes (12): AX(), combine(), configure(), extend(), gQ(), kr(), parseDialect(), tQ() (+4 more)

### Community 78 - "join"
Cohesion: 0.07
Nodes (44): B0(), c1(), Ck(), Cp(), d1(), De(), f1(), Fk() (+36 more)

### Community 79 - "file-upload.js"
Cohesion: 0.05
Nodes (51): hc(), Bp(), c(), ca(), clickPercent(), constructor(), Cp(), da() (+43 more)

### Community 80 - "Login"
Cohesion: 0.25
Nodes (3): Login, Filament\Auth\Pages\Login, Filament\Schemas\Components\Component

### Community 84 - "FonnteErrorMessage"
Cohesion: 0.11
Nodes (8): FonnteClient, FonnteErrorMessage, Throwable, Illuminate\Http\Client\ConnectionException, Illuminate\Http\Client\Response, PHPUnit\Framework\Attributes\DataProvider, RuntimeException, FonnteErrorMessageTest

### Community 87 - "Visit"
Cohesion: 0.06
Nodes (10): Visit, VisitDevice, GuestCheckoutService, StaleOperationsService, TableScanService, VisitClaimService, VisitLifecycleService, CashierOrderSoundAlertTest (+2 more)

### Community 91 - "devDependencies"
Cohesion: 0.09
Nodes (21): apexcharts, axios, concurrently, laravel-vite-plugin, dependencies, apexcharts, devDependencies, axios (+13 more)

### Community 92 - "filament/app.js"
Cohesion: 0.13
Nodes (8): B(), close(), G(), init(), P(), setUpResizeObserver(), x(), Y()

### Community 94 - "fn"
Cohesion: 0.22
Nodes (18): An(), Ce(), ei(), fn(), Ft(), Ie(), Le(), ni() (+10 more)

### Community 95 - "FilamentTranslatable"
Cohesion: 0.04
Nodes (38): afterCreate(), afterSave(), ensureAtLeastOneTranslation(), extractTranslations(), hasTranslatableContent(), mutateFormDataBeforeCreate(), mutateFormDataBeforeFill(), mutateFormDataBeforeSave() (+30 more)

### Community 96 - "require"
Cohesion: 0.12
Nodes (17): require, astrotomic/laravel-translatable, barryvdh/laravel-dompdf, bezhansalleh/filament-shield, endroid/qr-code, filament/filament, hammadzafar05/filament-mobile-preset, ipatco/filament-profile (+9 more)

### Community 97 - "scripts"
Cohesion: 0.12
Nodes (16): scripts, dev, post-autoload-dump, post-create-project-cmd, post-root-package-install, post-update-cmd, Composer\\Config::disableProcessTimeout, Illuminate\\Foundation\\ComposerScripts::postAutoloadDump (+8 more)

### Community 98 - ".slice"
Cohesion: 0.04
Nodes (73): accepts(), addInner(), addMaps(), addStep(), addTransform(), appendMap(), appendMapping(), appendMappingInverted() (+65 more)

### Community 99 - "color-picker.js"
Cohesion: 0.11
Nodes (8): [g](), style(), update(), [x](), _freeze(), getAllExtensions(), st(), zt()

### Community 100 - "resources/js/app.js"
Cohesion: 0.12
Nodes (6): applyTheme(), bindThemeToggle(), initReveal(), initTheme(), syncThemeToggleIcons(), toggleTheme()

### Community 101 - "sliceDoc"
Cohesion: 0.05
Nodes (57): addToSet(), aO(), bd(), Bh(), childString(), clearDelayedAndroidKey(), d0(), De() (+49 more)

### Community 102 - "eq"
Cohesion: 0.07
Nodes (56): addAll(), addDOM(), addElement(), addElementByRule(), addMark(), addProseMirrorPlugins(), addStoredMark(), addTextNode() (+48 more)

### Community 103 - "composer.json"
Cohesion: 0.14
Nodes (13): autoload-dev, psr-4, description, keywords, license, minimum-stability, name, prefer-stable (+5 more)

### Community 107 - "date-time-picker.js"
Cohesion: 0.29
Nodes (7): d(), e(), i(), m(), r(), s(), t()

### Community 108 - "🚀 3. Langkah-Langkah Menambahkan Template Baru (*Workflow*)"
Cohesion: 0.12
Nodes (15): 📌 1. Prinsip Utama & Aturan Wajib (Golden Rules), 🏗️ 2. Arsitektur 10 Core Section CMS (1:1 Mapping), 🚀 3. Langkah-Langkah Menambahkan Template Baru (*Workflow*), 📊 4. Variabel yang Disediakan oleh `LandingPageDataService`, 💎 5. Pelajaran Desain & Best Practices UI/UX (*Key Learnings*), A. Larangan Keras Menggunakan Emoji Unicode (Gunakan SVG Heroicons), B. Arsitektur Layering 3D & Background Tembus Pandang (*Stacking Context*), C. Resep Glassmorphism iOS yang Nyata (*True Frosted Glass*) (+7 more)

### Community 110 - "Mt"
Cohesion: 0.24
Nodes (11): apply(), fs(), go(), Hr(), T(), ir(), it(), Mt() (+3 more)

### Community 111 - "Illuminate\Database\Eloquent\Builder"
Cohesion: 0.03
Nodes (18): getRecordRouteBindingEloquentQuery(), KitchenDisplay, BackedEnum, UnitEnum, Width, ListCustomerReviews, ManageDiningTables, Action (+10 more)

### Community 113 - "ManageSoundNotifications"
Cohesion: 0.16
Nodes (4): ManageSoundNotifications, BackedEnum, UnitEnum, ManageSoundNotificationsTest

### Community 114 - "Filament\Resources\Pages\ListRecords"
Cohesion: 0.03
Nodes (33): BlogCategoryResource, CreateBlogCategory, ListBlogCategories, ViewBlogCategory, ListBlogComments, BloggerResource, CreateBlogger, EditBlogger (+25 more)

### Community 115 - "actions/actions.js"
Cohesion: 0.44
Nodes (8): closeModal(), generateModalId(), getActionNestingIndexFromModalId(), init(), openModal(), rememberPreviouslyFocusedElement(), restorePreviouslyFocusedElement(), syncActionModals()

### Community 118 - "Landasan Produksi — Tahap 1 (Walk-In Operasional)"
Cohesion: 0.05
Nodes (37): 10. Aturan yang tidak boleh dilanggar, 11. Matriks skenario lapangan, 12. Yang sengaja tidak didukung (bukan gap, non-goal), 13. Tahap 2 (bukan sekarang), 14. Urutan bangun (agar cepat produksi), 1. Keputusan terkunci, 2. Modul tahap 1 vs ditunda, 3. Model domain (+29 more)

### Community 119 - "Guest"
Cohesion: 0.05
Nodes (34): Bentuk objek, CartItemResource, DELETE `/guest/cart/items/{cartItem}`, GET `/guest/cart`, GET `/guest/menu`, GET `/guest/orders`, GET `/guest/orders/{public_id}`, GET `/guest/review` (+26 more)

### Community 120 - "RestaurantDirectory"
Cohesion: 0.11
Nodes (6): GeoDistance, RestaurantDirectory, Illuminate\Contracts\Pagination\LengthAwarePaginator, PHPUnit\Framework\TestCase, ExampleTest, GeoDistanceTest

### Community 121 - "2. Masalah di lapangan — dan apa yang sistem selesaikan"
Cohesion: 0.05
Nodes (41): 1. Cerita yang mungkin terasa familiar, 2.10 Struk kertas hilang, tamu minta dikirim WhatsApp, 2.11 Tampilan website restoran kaku atau tidak sesuai konsep resto, 2.12 Calon tamu ingin lihat menu lengkap sebelum datang ke resto, 2.13 Foto menu yang diupload staf ukurannya raksasa bikin web lemot, 2.14 Owner dan kasir ingin tahu performa hari ini secara instan, 2.15 Tak sengaja hapus menu atau meja saat jam sibuk, 2.16 Sulit ditemukan calon tamu baru di internet (+33 more)

### Community 122 - "Role"
Cohesion: 0.04
Nodes (13): Role, RolePolicy, Spatie\Permission\DefaultTeamResolver, makeOwner(), makeStaff(), AnalyticsChartWidgetRenderTest, DashboardHttpSmokeTest, KitchenDisplayPageTest (+5 more)

### Community 123 - "require-dev"
Cohesion: 0.25
Nodes (8): require-dev, fakerphp/faker, laravel/pail, laravel/pint, laravel/sail, mockery/mockery, nunomaduro/collision, phpunit/phpunit

### Community 125 - "6. Katalog fitur"
Cohesion: 0.06
Nodes (33): 1. Latar belakang, 2. Rumusan masalah, 3.1 Tujuan umum, 3.2 Tujuan khusus, 3. Tujuan, 4.1 Bagi restoran (owner, kasir, dapur), 4.2 Bagi tamu, 4.3 Bagi pengelola platform (+25 more)

### Community 127 - "config"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

### Community 128 - "6. Katalog fitur"
Cohesion: 0.06
Nodes (32): 1. Latar belakang, 2. Rumusan masalah, 3.1 Tujuan umum, 3.2 Tujuan khusus, 3. Tujuan, 4.1 Bagi restoran (owner, kasir, dapur), 4.2 Bagi tamu, 4.3 Bagi pengelola platform (+24 more)

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

### Community 157 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.02
Nodes (33): canCreate(), canDelete(), canDeleteAny(), canEdit(), canViewAny(), canForceDelete(), canRestore(), Action (+25 more)

### Community 158 - "renderOptions"
Cohesion: 0.37
Nodes (13): createOptionElement(), deferPositionDropdown(), filterOptions(), handleSearch(), hideLoadingState(), openDropdown(), populateLabelRepositoryFromOptions(), positionDropdown() (+5 more)

### Community 163 - "Filament\Widgets\Widget"
Cohesion: 0.04
Nodes (22): TemplateRadioPicker, FounderRevenueGrowthApexChartWidget, FounderSubscriptionHealthApexChartWidget, OrderTodayStatsWidget, AnalyticsKpiWidget, AnalyticsPeriodSummaryWidget, AnalyticsRevenueBarWidget, AnalyticsSidebarWidget (+14 more)

### Community 164 - "SubscriptionWriteGuard"
Cohesion: 0.28
Nodes (3): BlockGraceMutations, SubscriptionWriteGuard, Livewire\ComponentHook

### Community 172 - "InteractsWithRestaurantAnalytics.php"
Cohesion: 0.36
Nodes (8): analyticsDateFrom(), analyticsDateTo(), analyticsDayCount(), analyticsRangeLabel(), analyticsSnapshot(), canViewAnalytics(), normalizedAnalyticsDateRange(), Carbon

### Community 176 - "🎨 Spesifikasi Token & Class CSS yang Tersedia di `theme.css`"
Cohesion: 0.15
Nodes (12): 1. Kartu Kontainer Utama (`.vision-card`), 2. Kotak Ikon Gradien Neon (`.vision-icon-box`), 3. Kapsul Metrik / Sub-Kartu (`.vision-pill-card` atau `.vision-pill-dark`), 4. Kartu Filter (`.vision-filter-card`), 5. Tabel Data Kaca (`.vision-table-card`), 6. Form Section & Skema Input Kaca (`.vision-card` pada `Section::make`), 7. Kartu Statistik Global (`StatsOverviewWidget` / `Stat::make`), ⚙️ Aturan Wajib untuk AI Developer (+4 more)

### Community 177 - "AdSetting"
Cohesion: 0.07
Nodes (7): AdSetting, self, AdPlacementService, Head, Slot, Illuminate\View\Component, AdIntegrationTest

### Community 179 - "MenuCategory"
Cohesion: 0.08
Nodes (5): RestaurantReadinessWidget, MenuCategory, ExportReportTest, LandingMenuCatalogTest, SoftDeleteTrashTest

### Community 180 - "n"
Cohesion: 0.11
Nodes (36): Ei(), Aa(), Bi(), cf(), da(), fa(), fi(), Gr() (+28 more)

### Community 183 - "User"
Cohesion: 0.02
Nodes (36): SitemapController, BlogCategory, BlogHeroSetting, BlogPost, BlogTag, HasMany, User, VisitLog (+28 more)

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
Nodes (8): CreateCashierOrder, BackedEnum, UnitEnum, Width, ViewOrder, CashTender, IdrAmount, IdrAmountTest

### Community 301 - "classic/show.blade.php"
Cohesion: 0.50
Nodes (3): landing.sections., partials.customer.landing-footer, partials.customer.landing-header

### Community 306 - "DiningTable"
Cohesion: 0.04
Nodes (10): DiningTable, MenuModifierService, TableFloorPlan, TableQrToken, Illuminate\Support\Collection, createGuestRestaurant(), GuestApiTest, StaleOperationsServiceTest (+2 more)

### Community 317 - "HasSingletonForm.php"
Cohesion: 0.14
Nodes (16): ManageBlogHero, content(), defaultForm(), fillForm(), getFormActions(), getFormContentComponent(), getRecord(), getRedirectUrl() (+8 more)

### Community 318 - "Dashboard"
Cohesion: 0.25
Nodes (5): Dashboard, BezhanSalleh\FilamentShield\Resources\Roles\RoleResource, Filament\Pages\Dashboard, Filament\Widgets\AccountWidget, Filament\Widgets\FilamentInfoWidget

### Community 322 - "glassmorphism/show.blade.php"
Cohesion: 0.29
Nodes (6): landing.templates.glassmorphism.sections., landing.templates.glassmorphism.sections.hero, landing.sections., landing.templates.glassmorphism.partials.background, landing.templates.glassmorphism.sections.footer, landing.templates.glassmorphism.sections.header

### Community 324 - "Filament\Resources\Pages\CreateRecord"
Cohesion: 0.04
Nodes (18): EditBlogCategory, CreateFacility, EditFacility, CreateLandingTemplate, EditLandingTemplate, CreateSubscriptionInvoice, EditSubscriptionPlan, SubscriptionPlanResource (+10 more)

### Community 327 - "selectOption"
Cohesion: 0.24
Nodes (12): addBadgesForSelectedOptions(), addSingleBadge(), addSingleSelectionDisplay(), createBadgeElement(), createRemoveButton(), getLabelForSingleSelection(), getSelectedOptionLabel(), hideMaxItemsMessage() (+4 more)

### Community 340 - "Restaurant"
Cohesion: 0.02
Nodes (25): GenerateUpcomingInvoicesCommand, DateTimeInterface, Restaurant, SubscriptionInvoice, SubscriptionPlan, FounderAnalyticsService, SubscriptionInvoiceService, Carbon (+17 more)

### Community 348 - "Illuminate\Http\Request"
Cohesion: 0.02
Nodes (56): RestaurantMenuController, BlogController, BlogLikeController, PlatformPageController, RestaurantLandingController, ApplyRestaurantPanelTheme, EnsureApiGuestVisit, EnsureGuestVisit (+48 more)

### Community 354 - "CommissionReconciliationService"
Cohesion: 0.29
Nodes (4): CommissionReconciliationService, CommissionReconciliationExport, Maatwebsite\Excel\Facades\Excel, Symfony\Component\HttpFoundation\BinaryFileResponse

### Community 363 - "BlogPostObserver"
Cohesion: 0.16
Nodes (3): BlogPostTranslation, BlogPostObserver, BlogPostTranslationObserver

### Community 387 - "O"
Cohesion: 0.18
Nodes (34): b(), $c(), X(), ca(), me(), D(), _e(), se() (+26 more)

### Community 392 - "replace"
Cohesion: 0.15
Nodes (17): applyChanges(), balanced(), decompose(), decomposeLeft(), decomposeRight(), getReplacement(), heightForGap(), heightForLine() (+9 more)

### Community 397 - "ExcelExporter"
Cohesion: 0.60
Nodes (3): ExcelExporter, Maatwebsite\Excel\Concerns\FromView, Maatwebsite\Excel\Concerns\ShouldAutoSize

## Knowledge Gaps
- **345 isolated node(s):** `$schema`, `name`, `type`, `description`, `laravel` (+340 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **44 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `update()` connect `constructor` to `stat/chart.js`, `code-editor.js`, `O`, `rich-editor.js`, `replace`, `fromObject`, `find`, `advance`, `r`, `get`, `facet`, `Ae`, `echo.js`, `of`, `W`, `markdown-editor.js`, `te`, `n`, `t`, `slice`, `join`, `sliceDoc`?**
  _High betweenness centrality (0.022) - this node is a cross-community bridge._
- **Why does `User` connect `User` to `ExportFile`, `TestCase`, `Filament\Schemas\Schema`, `Order`, `Illuminate\Database\Eloquent\Relations\BelongsTo`, `AdminPanelProvider.php`, `Outlet`, `AppServiceProvider.php`, `GuestContext`, `Illuminate\Database\Eloquent\Model`, `CashierFilamentActionsTest`, `Filament\Widgets\Widget`, `BlogAnalytics.php`, `Customer`, `InteractsWithRestaurantAnalytics.php`, `CashierShift`, `CommissionReconciliation`, `AdSetting`, `DiningTable`, `MenuCategory`, `Filament\Tables\Table`, `RestaurantAnalyticsPeriod`, `GraceReadOnlyTest`, `DailyOmzetServiceTest`, `Restaurant`, `Visit`, `Illuminate\Http\Request`, `FilamentTranslatable`, `RegisterRestaurant`, `Illuminate\Database\Eloquent\Builder`, `ManageSoundNotifications`, `Filament\Resources\Pages\ListRecords`, `Role`?**
  _High betweenness centrality (0.021) - this node is a cross-community bridge._
- **Why does `Restaurant` connect `Restaurant` to `ExportFile`, `TestCase`, `Filament\Schemas\Schema`, `ReportExportBuilder`, `PlatformSetting`, `Order`, `Illuminate\Database\Eloquent\Relations\BelongsTo`, `AdminPanelProvider.php`, `Outlet`, `GuestContext`, `Illuminate\Database\Eloquent\Model`, `CashierFilamentActionsTest`, `Filament\Widgets\Widget`, `SubscriptionInvoiceResource`, `Customer`, `DailyOmzetService`, `InteractsWithRestaurantAnalytics.php`, `CommissionReconciliation`, `DiningTable`, `MenuCategory`, `User`, `SubscriptionStatus`, `RestaurantAnalyticsService`, `CashierCommissionBillingService`, `Filament\Resources\Pages\CreateRecord`, `MenuVariant`, `Filament\Tables\Table`, `RestaurantAnalyticsPeriod`, `GraceReadOnlyTest`, `Illuminate\Http\Request`, `CommissionReconciliationService`, `RegisterRestaurant`, `RestaurantDirectoryTest`, `Illuminate\Database\Eloquent\Builder`, `RestaurantDirectory`, `Role`?**
  _High betweenness centrality (0.020) - this node is a cross-community bridge._
- **What connects `$schema`, `name`, `type` to the rest of the system?**
  _345 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `stat/chart.js` be split into smaller, more focused modules?**
  _Cohesion score 0.010785747405214537 - nodes in this community are weakly interconnected._
- **Should `components/chart.js` be split into smaller, more focused modules?**
  _Cohesion score 0.00782006405004841 - nodes in this community are weakly interconnected._
- **Should `code-editor.js` be split into smaller, more focused modules?**
  _Cohesion score 0.008458261653149833 - nodes in this community are weakly interconnected._