# Graph Report - WALK-IN-RESTO  (2026-09-16)

## Corpus Check
- 851 files · ~395,488 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 9686 nodes · 30069 edges · 417 communities (375 shown, 42 thin omitted)
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
- next
- SoftDeleteTrashPage
- PlatformSetting
- _update
- Order
- slice
- r
- get
- o
- Illuminate\Database\Eloquent\Relations\BelongsTo
- Illuminate\Foundation\Http\FormRequest
- AdminPanelProvider.php
- KitchenDisplay
- create
- support.js
- n
- AppServiceProvider.php
- GuestContext
- columns/select.js
- nodeAt
- draw
- echo.js
- resolve
- fn
- facet
- BlogVisitStatsWidget
- SubscriptionInvoiceResource
- W
- constructor
- RestaurantReview
- Je
- Ye
- EditProfile
- S
- notifications.js
- markdown-editor.js
- CommissionReconciliation
- te
- Cn
- _update
- components/select.js
- Activity
- tables.js
- Pe
- r
- updateElements
- SubscriptionStatus
- Xt
- Illuminate\Database\Migrations\Migration
- filament-right-click.js
- getDatasetMeta
- t
- Si
- eq
- Im
- selectOption
- draw
- parse
- ir
- Filament\Tables\Table
- slider.js
- Restaurant
- closeDropdown
- Section
- i
- RefreshesAnalyticsChart.php
- E
- fn
- file-upload.js
- isHorizontal
- parse
- Ka
- RestaurantDirectory
- FonnteErrorMessage
- Illuminate\Database\Schema\Blueprint
- Visit
- BlogPost
- getContext
- FilamentTranslatable
- devDependencies
- filament/app.js
- BlogAnalyticsService
- fn
- BackedEnum
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
- Illuminate\Support\Collection
- En
- Illuminate\Database\Eloquent\Builder
- e
- LandingTemplate
- Filament\Resources\Pages\ListRecords
- actions/actions.js
- fd
- schemas.js
- Landasan Produksi — Tahap 1 (Walk-In Operasional)
- Guest
- GeoDistance
- 2. Masalah di lapangan — dan apa yang sistem selesaikan
- Vf
- require-dev
- pl
- 6. Katalog fitur
- config
- 6. Katalog fitur
- toString
- register-restaurant.blade.php
- add-to-cart-modal.blade.php
- Illuminate\View\View
- components/actions.js
- psr-4
- extra
- logging.php
- BloggerPanelTest
- selectRecords
- getDataset
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
- BlogLike
- closeSimpleModeModal
- st
- Illuminate\Database\Eloquent\Model
- renderOptions
- CashierFilamentActionsTest
- RestaurantTheme
- GraceReadOnlyTest
- N
- pt
- BlogAnalytics
- getProps
- SitemapController.php
- BlogHeroSetting
- 🎨 Spesifikasi Token & Class CSS yang Tersedia di `theme.css`
- AdSetting
- MenuItem
- dx
- User
- BlogPostTranslation
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
- cc
- PlatformPagesTest
- rules/graphify.md
- workflows/graphify.md
- HasSingletonForm.php
- glassmorphism/show.blade.php
- Filament\Resources\Pages\EditRecord
- selectOption
- SubscriptionInvoice
- glassmorphism-background.blade.php
- Ae
- Illuminate\Http\Request
- dropdown.blade.php
- BlogPostObserver
- ce
- InvoicePaymentTest
- 2026_08_20_010000_add_floor_layout_to_tables_table.php
- Illuminate\Support\Facades\Schema
- Astrotomic\Translatable\Validation\RuleFactory
- layouts/blog.blade.php

## God Nodes (most connected - your core abstractions)
1. `User` - 394 edges
2. `Restaurant` - 350 edges
3. `TestCase` - 193 edges
4. `Order` - 161 edges
5. `constructor()` - 152 edges
6. `update()` - 148 edges
7. `MenuItem` - 114 edges
8. `PlatformSetting` - 113 edges
9. `Visit` - 104 edges
10. `DiningTable` - 100 edges

## Surprising Connections (you probably didn't know these)
- `up()` --calls--> `DiningTable`  [EXTRACTED]
  database/migrations/2026_08_20_010000_add_floor_layout_to_tables_table.php → app/Models/DiningTable.php
- `BlogAnalyticsTest` --references--> `BlogCategory`  [EXTRACTED]
  tests/Feature/BlogAnalyticsTest.php → app/Models/BlogCategory.php
- `createGuestRestaurant()` --calls--> `DiningTable`  [EXTRACTED]
  tests/Concerns/CreatesGuestRestaurant.php → app/Models/DiningTable.php
- `extraMenuItem()` --calls--> `MenuItem`  [EXTRACTED]
  tests/Concerns/CreatesGuestRestaurant.php → app/Models/MenuItem.php
- `paidGuestOrder()` --calls--> `Order`  [EXTRACTED]
  tests/Concerns/CreatesGuestRestaurant.php → app/Models/Order.php

## Import Cycles
- None detected.

## Communities (417 total, 42 thin omitted)

### Community 0 - "stat/chart.js"
Cohesion: 0.02
Nodes (105): themeClasses(), qc(), addControllers(), addPlugins(), addScales(), alpha(), applyStack(), ar() (+97 more)

### Community 1 - "components/chart.js"
Cohesion: 0.01
Nodes (110): abutsStart(), active(), addControllers(), addPlugins(), addScales(), afterDraw(), ag(), _animateOptions() (+102 more)

### Community 2 - "code-editor.js"
Cohesion: 0.01
Nodes (136): Ac(), addCompletion(), addCompletions(), addNamespace(), addNamespaceObject(), Ag(), AX(), b0() (+128 more)

### Community 3 - "rich-editor.js"
Cohesion: 0.01
Nodes (200): aa(), add(), addExtensions(), addHackNode(), addNodeMark(), addTextblockHacks(), an(), applyAspectRatio() (+192 more)

### Community 4 - "ExportFile"
Cohesion: 0.06
Nodes (18): TenantForceDeleteCommand, PdfExporter, CleanupOldExportFilesJob, ExportReportJob, ForceDeleteTenantJob, ExportFile, ExportFileObserver, ExportFinishedNotifier (+10 more)

### Community 5 - "y"
Cohesion: 0.16
Nodes (70): at(), b(), Be(), $c(), X(), me(), Cr(), Ct() (+62 more)

### Community 6 - "TestCase"
Cohesion: 0.03
Nodes (59): CashierOrderService, OrderPaymentService, SubscriptionPlanSync, ImageOptimizer, BezhanSalleh\FilamentShield\Resources\Roles\Pages\ListRoles, Bityukov\CommandCenter\Filament\Pages\Commands, Bityukov\CommandCenter\Filament\Pages\History, Carbon\CarbonPeriod (+51 more)

### Community 7 - "constructor"
Cohesion: 0.02
Nodes (180): active(), add(), addChunk(), addEventListener(), addInfoPane(), addInner(), addSelection(), addWindowListeners() (+172 more)

### Community 8 - "fromObject"
Cohesion: 0.03
Nodes (112): mm(), Oe(), ac(), ae(), after(), Al(), Am(), before() (+104 more)

### Community 9 - "Filament\Schemas\Schema"
Cohesion: 0.08
Nodes (48): SeoFields, TranslationTabs, BlogHeroFormSchema, BlogCategoryForm, BloggerForm, BlogTagForm, Filament\Actions\Action, Filament\Actions\ActionGroup (+40 more)

### Community 10 - "lo"
Cohesion: 0.08
Nodes (31): _0(), addOptions(), buildProps(), can(), createCan(), createChain(), ff(), findDiffEnd() (+23 more)

### Community 11 - "next"
Cohesion: 0.06
Nodes (44): addActive(), addChanges(), Ar(), as(), be(), bidiSpans(), checkHover(), chunkEnd() (+36 more)

### Community 12 - "SoftDeleteTrashPage"
Cohesion: 0.04
Nodes (20): SoftDeleteTrashPage, ManageCmsBanners, TrashCmsBanners, ManageCmsFaqs, TrashCmsFaqs, CmsGalleryImageResource, ManageCmsGalleryImages, TrashCmsGalleryImages (+12 more)

### Community 13 - "PlatformSetting"
Cohesion: 0.05
Nodes (9): ManageSoundNotifications, BackedEnum, UnitEnum, self, PlatformSetting, PlatformSettingSeeder, AuthGlassTest, ManageSoundNotificationsTest (+1 more)

### Community 14 - "_update"
Cohesion: 0.04
Nodes (86): addBox(), addElements(), afterBuildTicks(), afterCalculateLabelRotation(), afterDataLimits(), afterFit(), afterSetDimensions(), afterTickToLabelConversion() (+78 more)

### Community 15 - "Order"
Cohesion: 0.03
Nodes (29): OrderReceiptDownloadController, OrderReceiptPrintController, SendWhatsappReceiptJob, CashierOrderSoundAlert, Order, OrderItem, OrderReceipt, WhatsappMessage (+21 more)

### Community 16 - "slice"
Cohesion: 0.03
Nodes (125): addActions(), addChild(), addGaps(), addLeafElement(), addNode(), advance(), advanceFully(), advanceStack() (+117 more)

### Community 17 - "r"
Cohesion: 0.04
Nodes (154): Ad(), addAttributes(), addNodeView(), addProseMirrorPlugins(), af(), au(), ay(), B0() (+146 more)

### Community 18 - "get"
Cohesion: 0.04
Nodes (100): addBlockWidget(), addBreak(), addComposition(), addDelimiter(), addInlineWidget(), addLine(), addLineStart(), addLineStartIfNotCovered() (+92 more)

### Community 19 - "o"
Cohesion: 0.05
Nodes (95): ar(), Ba(), beforeLayout(), bi(), ca(), cg(), Ci(), createResolver() (+87 more)

### Community 20 - "Illuminate\Database\Eloquent\Relations\BelongsTo"
Cohesion: 0.02
Nodes (45): CashierShiftPrintController, CashierShift, CashierShiftMovement, CmsBanner, LogOptions, CmsFaq, LogOptions, CmsGalleryImage (+37 more)

### Community 21 - "Illuminate\Foundation\Http\FormRequest"
Cohesion: 0.06
Nodes (11): BlogCommentController, AddCartItemRequest, CheckoutRequest, ClaimTableRequest, JoinTableRequest, StorePaymentProofRequest, StoreRestaurantReviewRequest, UpdateCartItemRequest (+3 more)

### Community 22 - "AdminPanelProvider.php"
Cohesion: 0.12
Nodes (26): ApplyPlatformBrandTheme, AdminPanelProvider, BloggerPanelProvider, FounderPanelProvider, AuthGlass, BezhanSalleh\FilamentShield\FilamentShieldPlugin, Bityukov\CommandCenter\Filament\CommandCenterPlugin, Filament\Http\Middleware\Authenticate (+18 more)

### Community 23 - "KitchenDisplay"
Cohesion: 0.13
Nodes (5): KitchenDisplay, BackedEnum, UnitEnum, Width, Filament\Resources\Concerns\HasTabs

### Community 24 - "create"
Cohesion: 0.04
Nodes (75): Cl(), clone(), create(), Ct(), Dl(), dtFormatter(), Ea(), Ec() (+67 more)

### Community 25 - "support.js"
Cohesion: 0.05
Nodes (65): acquireScrollLock(), ae(), ai(), Ao(), as(), close(), closeQuietly(), commit() (+57 more)

### Community 26 - "n"
Cohesion: 0.09
Nodes (80): _a(), Ae(), ar(), as(), Ba(), bc(), bf(), ee() (+72 more)

### Community 27 - "AppServiceProvider.php"
Cohesion: 0.07
Nodes (21): BlogComment, BlogCommentObserver, AppServiceProvider, ValidBlogCommentParent, Carbon\Carbon, Filament\Actions\ForceDeleteAction, Filament\Actions\ForceDeleteBulkAction, Filament\Actions\RestoreAction (+13 more)

### Community 28 - "GuestContext"
Cohesion: 0.04
Nodes (34): CartController, CheckoutController, MenuController, OrderController, ReviewController, SessionController, TableController, VisitController (+26 more)

### Community 29 - "columns/select.js"
Cohesion: 0.06
Nodes (57): A(), An(), applyDisabledState(), b(), Bt(), Ce(), D(), disable() (+49 more)

### Community 30 - "nodeAt"
Cohesion: 0.08
Nodes (64): Ac(), addCommands(), allowedMarks(), AS(), cellsInRect(), checkContent(), clearIncompatible(), colCount() (+56 more)

### Community 31 - "draw"
Cohesion: 0.04
Nodes (114): acquireContext(), adjustHitBoxes(), Ao(), aspectRatio(), B(), bh(), calculateLabelRotation(), _calculatePadding() (+106 more)

### Community 32 - "echo.js"
Cohesion: 0.05
Nodes (49): a(), ar(), b(), Be(), Ce(), cr(), d(), De() (+41 more)

### Community 33 - "resolve"
Cohesion: 0.06
Nodes (114): addKeyboardShortcuts(), after(), ag(), al(), allowsMarks(), before(), bl(), blockRange() (+106 more)

### Community 34 - "fn"
Cohesion: 0.12
Nodes (35): aa(), ba(), cr(), da(), de(), dt(), ei(), Fi() (+27 more)

### Community 35 - "facet"
Cohesion: 0.04
Nodes (83): accept(), activateHover(), Ah(), applyTransaction(), asSingle(), baseTheme(), between(), bi() (+75 more)

### Community 36 - "BlogVisitStatsWidget"
Cohesion: 0.09
Nodes (10): Dashboard, BlogContentProgressWidget, BlogTrafficApexChartWidget, BlogVisitStatsWidget, Dashboard, BezhanSalleh\FilamentShield\Resources\Roles\RoleResource, Filament\Pages\Dashboard, Filament\Pages\Dashboard\Concerns\HasFiltersForm (+2 more)

### Community 37 - "SubscriptionInvoiceResource"
Cohesion: 0.12
Nodes (4): CreateSubscriptionInvoice, ViewSubscriptionInvoice, SubscriptionInvoiceResource, FounderStatsWidget

### Community 38 - "W"
Cohesion: 0.05
Nodes (70): AQ(), atLastNode(), au(), child(), childAfter(), childBefore(), continue(), cursor() (+62 more)

### Community 39 - "constructor"
Cohesion: 0.04
Nodes (70): alpha(), apply(), Bc(), Be(), bg(), $c(), chartOptionScopes(), co() (+62 more)

### Community 40 - "RestaurantReview"
Cohesion: 0.04
Nodes (8): Customer, CustomerLoyaltyPoint, RestaurantReview, CustomerCrmService, PublicRestaurantApiTest, RestaurantReviewApiTest, CustomerCrmLoyaltyTest, CustomerSatisfactionAnalyticsTest

### Community 41 - "Je"
Cohesion: 0.05
Nodes (62): addGlobalAttributes(), addInputRules(), addMark(), addPasteRules(), addStoredMark(), Ah(), Ax(), _c() (+54 more)

### Community 42 - "Ye"
Cohesion: 0.08
Nodes (48): Rd(), $a(), ak(), at(), bk(), c(), bp(), Dk() (+40 more)

### Community 43 - "EditProfile"
Cohesion: 0.10
Nodes (7): EditProfile, FilamentProfilePlugin, ProfileInformationForm, Ipatco\FilamentProfile\FilamentProfilePlugin, Ipatco\FilamentProfile\Forms\ProfileInformationForm, Ipatco\FilamentProfile\Pages\EditProfile, ProfilePageTest

### Community 44 - "S"
Cohesion: 0.05
Nodes (66): A(), add(), apply(), As(), bo(), _cachedScopes(), chartOptionScopes(), _computeLabelSizes() (+58 more)

### Community 45 - "notifications.js"
Cohesion: 0.06
Nodes (31): actions(), button(), c(), close(), configureAnimations(), configureTransitions(), constructor(), danger() (+23 more)

### Community 46 - "markdown-editor.js"
Cohesion: 0.05
Nodes (85): ad(), af(), ai(), al(), An(), ao(), bo(), br() (+77 more)

### Community 47 - "CommissionReconciliation"
Cohesion: 0.06
Nodes (15): CommissionReconciliation, BackedEnum, UnitEnum, Width, CustomerAnalytics, CustomerSatisfactionAnalytics, GenerateReport, BackedEnum (+7 more)

### Community 48 - "te"
Cohesion: 0.05
Nodes (11): Bn(), Id(), ji(), on(), qd(), qi(), Ri(), te() (+3 more)

### Community 49 - "Cn"
Cohesion: 0.12
Nodes (51): B(), Cn(), b(), Be(), Ce(), De(), dn(), _e() (+43 more)

### Community 50 - "_update"
Cohesion: 0.05
Nodes (64): afterBuildTicks(), afterCalculateLabelRotation(), afterDataLimits(), afterDatasetsUpdate(), afterFit(), afterSetDimensions(), afterTickToLabelConversion(), afterUpdate() (+56 more)

### Community 51 - "components/select.js"
Cohesion: 0.08
Nodes (34): b(), Bt(), D(), E(), en(), Et(), getLabelsForMultipleSelection(), getSelectedOptionLabels() (+26 more)

### Community 52 - "Activity"
Cohesion: 0.09
Nodes (6): ActivityResource, ViewActivity, ActivityInfolist, Activity, ActivityPresenter, Spatie\Activitylog\Models\Activity

### Community 53 - "tables.js"
Cohesion: 0.13
Nodes (44): A(), ae(), B(), be(), C(), ce(), E(), ee() (+36 more)

### Community 54 - "Pe"
Cohesion: 0.12
Nodes (32): cd(), dd(), dt(), Ft(), gl(), _i(), Ie(), it() (+24 more)

### Community 55 - "r"
Cohesion: 0.15
Nodes (43): _a(), ar(), c(), f(), d(), di(), g(), Hi() (+35 more)

### Community 56 - "updateElements"
Cohesion: 0.05
Nodes (60): addEventListener(), af(), afterAutoSkip(), applyStack(), au(), bindResponsiveEvents(), buildLookupTable(), _calculateBarIndexPixels() (+52 more)

### Community 57 - "SubscriptionStatus"
Cohesion: 0.12
Nodes (3): BackedEnum, UnitEnum, SubscriptionStatus

### Community 58 - "Xt"
Cohesion: 0.12
Nodes (44): ae(), At(), bi(), bn(), ci(), cn(), ct(), de() (+36 more)

### Community 60 - "filament-right-click.js"
Cohesion: 0.11
Nodes (42): a(), ae(), b(), be(), C(), ce(), D(), de() (+34 more)

### Community 61 - "getDatasetMeta"
Cohesion: 0.07
Nodes (41): afterDatasetsUpdate(), An(), beforeDatasetsDraw(), bu(), dataset(), ef(), generateLabels(), getDatasetMeta() (+33 more)

### Community 62 - "t"
Cohesion: 0.06
Nodes (49): a$(), activeForPoint(), addBlock(), addLineDeco(), baseDirAt(), bidiIn(), bidiSpansAt(), blankContent() (+41 more)

### Community 63 - "Si"
Cohesion: 0.14
Nodes (40): ae(), At(), bi(), bn(), ci(), cn(), ct(), de() (+32 more)

### Community 64 - "eq"
Cohesion: 0.05
Nodes (57): addNode(), ao(), append(), bt(), by(), closest(), Cr(), cy() (+49 more)

### Community 65 - "Im"
Cohesion: 0.31
Nodes (10): Bm(), eat(), err(), Im(), isInGroup(), Lm(), o1(), pc() (+2 more)

### Community 66 - "selectOption"
Cohesion: 0.12
Nodes (39): addBadgesForSelectedOptions(), addSingleBadge(), addSingleSelectionDisplay(), closeDropdown(), constructor(), createBadgeElement(), createOptionElement(), createRemoveButton() (+31 more)

### Community 67 - "draw"
Cohesion: 0.06
Nodes (48): aa(), addEventListener(), Ae(), ai(), bi(), bindEvents(), bindResponsiveEvents(), bindUserEvents() (+40 more)

### Community 68 - "parse"
Cohesion: 0.07
Nodes (44): Bt(), cl(), Cn(), determineDataLimits(), el(), En(), endOf(), formats() (+36 more)

### Community 69 - "ir"
Cohesion: 0.14
Nodes (33): Ft(), ir(), ce(), de(), Dt(), ee(), Et(), fe() (+25 more)

### Community 70 - "Filament\Tables\Table"
Cohesion: 0.05
Nodes (33): BlogCategoriesTable, BlogCommentsTable, BlogPostsRelationManager, BloggersTable, CommentsRelationManager, BlogPostsTable, BlogTagsTable, BlogReferrersWidget (+25 more)

### Community 71 - "slider.js"
Cohesion: 0.11
Nodes (33): ar(), Be(), Ce(), De(), _e(), Ee(), er(), et() (+25 more)

### Community 72 - "Restaurant"
Cohesion: 0.03
Nodes (24): analyticsDateFrom(), analyticsDateTo(), analyticsDayCount(), analyticsRangeLabel(), analyticsSnapshot(), canViewAnalytics(), normalizedAnalyticsDateRange(), Carbon (+16 more)

### Community 73 - "closeDropdown"
Cohesion: 0.23
Nodes (17): applyDisabledState(), closeDropdown(), constructor(), destroy(), disable(), enable(), focusNextOption(), focusPreviousOption() (+9 more)

### Community 74 - "Section"
Cohesion: 0.05
Nodes (20): ExcelExporter, ManageAdSettings, BackedEnum, UnitEnum, ManageBillingAccount, BackedEnum, UnitEnum, ManageHomeLanding (+12 more)

### Community 75 - "i"
Cohesion: 0.04
Nodes (145): aa(), addElement(), b1(), balance(), balanced(), baseIndent(), baseIndentFor(), Bg() (+137 more)

### Community 76 - "RefreshesAnalyticsChart.php"
Cohesion: 0.36
Nodes (8): generateChartDataChecksum(), getCachedChartData(), getChartData(), mountRefreshesAnalyticsChart(), refreshAnalyticsChartData(), rendering(), updateChartData(), Livewire\Attributes\Locked

### Community 77 - "E"
Cohesion: 0.07
Nodes (43): $a(), aa(), add(), bo(), bs(), _cachedScopes(), cd(), Ch() (+35 more)

### Community 78 - "fn"
Cohesion: 0.16
Nodes (20): Ck(), De(), fn(), Gh(), ip(), Ja(), Jh(), Ji() (+12 more)

### Community 79 - "file-upload.js"
Cohesion: 0.05
Nodes (52): hc(), Bp(), c(), ca(), clickPercent(), constructor(), Cp(), da() (+44 more)

### Community 80 - "isHorizontal"
Cohesion: 0.08
Nodes (39): afterAutoSkip(), buildLookupTable(), buildTicks(), calculateLabelRotation(), _calculatePadding(), _computeLabelItems(), computeTickLimit(), dn() (+31 more)

### Community 81 - "parse"
Cohesion: 0.07
Nodes (38): at(), beforeDraw(), Bf(), br(), determineDataLimits(), Eh(), formats(), Gd() (+30 more)

### Community 82 - "Ka"
Cohesion: 0.07
Nodes (35): ad(), cf(), Do(), Dt(), first(), _getLegendItemAt(), ig(), inXRange() (+27 more)

### Community 83 - "RestaurantDirectory"
Cohesion: 0.11
Nodes (3): RestaurantDirectory, Livewire\Attributes\Computed, Livewire\WithPagination

### Community 84 - "FonnteErrorMessage"
Cohesion: 0.11
Nodes (8): FonnteClient, FonnteErrorMessage, Throwable, Illuminate\Http\Client\ConnectionException, Illuminate\Http\Client\Response, PHPUnit\Framework\Attributes\DataProvider, RuntimeException, FonnteErrorMessageTest

### Community 87 - "Visit"
Cohesion: 0.02
Nodes (23): DiningTable, LogOptions, Visit, VisitDevice, GuestCheckoutService, StaleOperationsService, TableOpsService, TableScanService (+15 more)

### Community 88 - "BlogPost"
Cohesion: 0.10
Nodes (4): BlogCategory, BlogPost, BlogSeoAndMediaTest, PublicBlogTest

### Community 89 - "getContext"
Cohesion: 0.08
Nodes (34): acquireContext(), Ca(), _computeGridLineItems(), datasetAnimationScopeKeys(), datasetElementScopeKeys(), dr(), drawGrid(), ft() (+26 more)

### Community 90 - "FilamentTranslatable"
Cohesion: 0.09
Nodes (12): BlogCategoryInfolist, BlogCommentForm, BlogCommentInfolist, BlogPostInfolist, BlogTagInfolist, FilamentTranslatable, Filament\Infolists\Components\IconEntry, Filament\Infolists\Components\ImageEntry (+4 more)

### Community 91 - "devDependencies"
Cohesion: 0.09
Nodes (21): apexcharts, axios, concurrently, laravel-vite-plugin, dependencies, apexcharts, devDependencies, axios (+13 more)

### Community 92 - "filament/app.js"
Cohesion: 0.13
Nodes (8): B(), close(), G(), init(), P(), setUpResizeObserver(), x(), Y()

### Community 93 - "BlogAnalyticsService"
Cohesion: 0.11
Nodes (4): VisitLog, BlogAnalyticsService, Illuminate\Database\Eloquent\Relations\MorphTo, BlogAnalyticsTest

### Community 94 - "fn"
Cohesion: 0.22
Nodes (18): An(), Ce(), ei(), fn(), Ft(), Ie(), Le(), ni() (+10 more)

### Community 95 - "BackedEnum"
Cohesion: 0.04
Nodes (31): getRecordTitle(), BlogCategoryResource, ViewBlogCategory, BlogCommentResource, EditBlogComment, ViewBlogComment, BloggerResource, ViewBlogger (+23 more)

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

### Community 109 - "Illuminate\Support\Collection"
Cohesion: 0.03
Nodes (17): periodSummary(), RestaurantCategory, MenuModifierService, RestaurantDirectory, BlogDemoSeeder, CustomerSatisfactionDemoSeeder, DatabaseSeeder, FacilitySeeder (+9 more)

### Community 110 - "En"
Cohesion: 0.15
Nodes (16): apply(), At(), En(), fs(), go(), Hr(), T(), ir() (+8 more)

### Community 111 - "Illuminate\Database\Eloquent\Builder"
Cohesion: 0.03
Nodes (23): Login, getRecordRouteBindingEloquentQuery(), ListCustomerReviews, ManageDiningTables, Action, Closure, Closure, MenuItemResource (+15 more)

### Community 112 - "e"
Cohesion: 0.17
Nodes (31): e(), Bi(), br(), Bt(), ca(), ct(), Dn(), Ea() (+23 more)

### Community 113 - "LandingTemplate"
Cohesion: 0.10
Nodes (8): TemplateRadioPicker, ManageLandingLayout, BackedEnum, Closure, UnitEnum, LandingTemplate, LogOptions, Filament\Forms\Components\Field

### Community 114 - "Filament\Resources\Pages\ListRecords"
Cohesion: 0.03
Nodes (41): afterCreate(), afterSave(), ensureAtLeastOneTranslation(), extractTranslations(), hasTranslatableContent(), mutateFormDataBeforeCreate(), mutateFormDataBeforeSave(), persistTranslations() (+33 more)

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

### Community 122 - "Vf"
Cohesion: 0.11
Nodes (21): ah(), average(), contains(), getCenterPoint(), gi(), Gr(), hasValue(), Jg() (+13 more)

### Community 123 - "require-dev"
Cohesion: 0.25
Nodes (8): require-dev, fakerphp/faker, laravel/pail, laravel/pint, laravel/sail, mockery/mockery, nunomaduro/collision, phpunit/phpunit

### Community 124 - "pl"
Cohesion: 0.13
Nodes (21): Ao(), bl(), ci(), getBasePosition(), getBaseValue(), getDistanceFromCenterForValue(), getIndexAngle(), getPointPosition() (+13 more)

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

### Community 132 - "Illuminate\View\View"
Cohesion: 0.26
Nodes (4): BlogController, PlatformPageController, RecordVisitService, Illuminate\View\View

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

### Community 139 - "getDataset"
Cohesion: 0.15
Nodes (15): addElements(), buildOrUpdateElements(), _dataCheck(), di(), getDataset(), getPadding(), getScaleForId(), initialize() (+7 more)

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

### Community 153 - "BlogLike"
Cohesion: 0.20
Nodes (4): BlogLikeController, BlogLike, BlogLikeService, VisitorHash

### Community 155 - "st"
Cohesion: 0.24
Nodes (11): [g](), _freeze(), getAllExtensions(), Ct(), lt(), ot(), se(), st() (+3 more)

### Community 157 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.02
Nodes (34): canCreate(), canDelete(), canDeleteAny(), canEdit(), canViewAny(), canForceDelete(), canRestore(), Action (+26 more)

### Community 158 - "renderOptions"
Cohesion: 0.37
Nodes (13): createOptionElement(), deferPositionDropdown(), filterOptions(), handleSearch(), hideLoadingState(), openDropdown(), populateLabelRepositoryFromOptions(), positionDropdown() (+5 more)

### Community 163 - "RestaurantTheme"
Cohesion: 0.04
Nodes (21): BlogAudienceWidget, BlogCategoryDistributionWidget, FounderRevenueGrowthApexChartWidget, FounderSubscriptionHealthApexChartWidget, OrderTodayStatsWidget, AnalyticsKpiWidget, AnalyticsPeriodSummaryWidget, AnalyticsRevenueBarWidget (+13 more)

### Community 164 - "GraceReadOnlyTest"
Cohesion: 0.16
Nodes (4): BlockGraceMutations, SubscriptionWriteGuard, Livewire\ComponentHook, GraceReadOnlyTest

### Community 168 - "N"
Cohesion: 0.33
Nodes (11): ae(), A(), E(), at(), be(), Gt(), i(), Jt() (+3 more)

### Community 170 - "pt"
Cohesion: 0.24
Nodes (10): dataset(), first(), nearest(), pathSegment(), Pn(), point(), pt(), sn() (+2 more)

### Community 172 - "getProps"
Cohesion: 0.25
Nodes (8): Nn(), active(), _animateOptions(), _createAnimations(), getCenterPoint(), getProps(), tooltipPosition(), ya()

### Community 173 - "SitemapController.php"
Cohesion: 0.38
Nodes (3): SitemapController, MediaUrl, Illuminate\Http\Response

### Community 174 - "BlogHeroSetting"
Cohesion: 0.29
Nodes (3): BlogHeroSetting, self, Astrotomic\Translatable\Contracts\Translatable

### Community 176 - "🎨 Spesifikasi Token & Class CSS yang Tersedia di `theme.css`"
Cohesion: 0.15
Nodes (12): 1. Kartu Kontainer Utama (`.vision-card`), 2. Kotak Ikon Gradien Neon (`.vision-icon-box`), 3. Kapsul Metrik / Sub-Kartu (`.vision-pill-card` atau `.vision-pill-dark`), 4. Kartu Filter (`.vision-filter-card`), 5. Tabel Data Kaca (`.vision-table-card`), 6. Form Section & Skema Input Kaca (`.vision-card` pada `Section::make`), 7. Kartu Statistik Global (`StatsOverviewWidget` / `Stat::make`), ⚙️ Aturan Wajib untuk AI Developer (+4 more)

### Community 177 - "AdSetting"
Cohesion: 0.08
Nodes (7): AdSetting, self, AdPlacementService, Head, Slot, Illuminate\View\Component, AdIntegrationTest

### Community 179 - "MenuItem"
Cohesion: 0.02
Nodes (24): RestaurantReadinessWidget, RestaurantMenuCatalog, KdsStation, MenuCategory, MenuItem, LogOptions, LandingPageDataService, CashierMenuCatalog (+16 more)

### Community 180 - "dx"
Cohesion: 0.14
Nodes (24): Ei(), Aa(), Bi(), ca(), da(), fa(), Gr(), ki() (+16 more)

### Community 183 - "User"
Cohesion: 0.02
Nodes (32): Role, HasMany, LogOptions, User, ExportFilePolicy, RolePolicy, UserPolicy, Filament\Models\Contracts\FilamentUser (+24 more)

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

### Community 302 - "cc"
Cohesion: 0.38
Nodes (7): attrs(), cc(), JQ(), m$(), Ow(), rc(), read()

### Community 317 - "HasSingletonForm.php"
Cohesion: 0.07
Nodes (27): mutateFormDataBeforeFill(), ManageBlogHero, afterCreate(), afterSave(), ensureAtLeastOneTranslation(), extractTranslations(), hasTranslatableContent(), mutateFormDataBeforeCreate() (+19 more)

### Community 322 - "glassmorphism/show.blade.php"
Cohesion: 0.29
Nodes (6): landing.templates.glassmorphism.sections., landing.templates.glassmorphism.sections.hero, landing.sections., landing.templates.glassmorphism.partials.background, landing.templates.glassmorphism.sections.footer, landing.templates.glassmorphism.sections.header

### Community 324 - "Filament\Resources\Pages\EditRecord"
Cohesion: 0.06
Nodes (11): EditFacility, EditLandingTemplate, EditSubscriptionPlan, ListSubscriptionPlans, SubscriptionPlanResource, Dashboard, EditMenuItem, EditRestaurantCategory (+3 more)

### Community 327 - "selectOption"
Cohesion: 0.24
Nodes (12): addBadgesForSelectedOptions(), addSingleBadge(), addSingleSelectionDisplay(), createBadgeElement(), createRemoveButton(), getLabelForSingleSelection(), getSelectedOptionLabel(), hideMaxItemsMessage() (+4 more)

### Community 340 - "SubscriptionInvoice"
Cohesion: 0.03
Nodes (20): ExpireStaleOperationsCommand, FinalizeCashierCommissionCommand, GenerateUpcomingInvoicesCommand, ProcessSubscriptionLifecycleCommand, SendCashierCommissionReminderCommand, SubscriptionInvoice, SubscriptionPlan, HandleNotification (+12 more)

### Community 347 - "Ae"
Cohesion: 0.67
Nodes (3): Ae(), Bt(), ne()

### Community 348 - "Illuminate\Http\Request"
Cohesion: 0.03
Nodes (40): paymentMixSummary(), RestaurantMenuController, ApplyRestaurantPanelTheme, EnsureApiGuestVisit, EnsureGuestVisit, EnsureRestaurantOperations, EnsureTenantSubscription, IdentifyApiGuestDevice (+32 more)

### Community 387 - "ce"
Cohesion: 0.09
Nodes (41): Ac(), bl(), Cc(), ce(), cl(), Dc(), Do(), Ec() (+33 more)

## Knowledge Gaps
- **345 isolated node(s):** `$schema`, `name`, `type`, `description`, `laravel` (+340 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **42 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `update()` connect `constructor` to `stat/chart.js`, `code-editor.js`, `ce`, `rich-editor.js`, `y`, `fromObject`, `next`, `slice`, `r`, `get`, `n`, `echo.js`, `resolve`, `facet`, `W`, `Ye`, `markdown-editor.js`, `te`, `dx`, `t`, `i`, `g$`, `fd`?**
  _High betweenness centrality (0.022) - this node is a cross-community bridge._
- **Why does `User` connect `User` to `ExportFile`, `TestCase`, `Filament\Schemas\Schema`, `BloggerPanelTest`, `PlatformSetting`, `Order`, `Illuminate\Database\Eloquent\Relations\BelongsTo`, `AdminPanelProvider.php`, `AppServiceProvider.php`, `GuestContext`, `Illuminate\Database\Eloquent\Model`, `CashierFilamentActionsTest`, `RestaurantTheme`, `GraceReadOnlyTest`, `RestaurantReview`, `CommissionReconciliation`, `AdSetting`, `MenuItem`, `Filament\Tables\Table`, `Restaurant`, `SubscriptionInvoice`, `Visit`, `BlogPost`, `FilamentTranslatable`, `Illuminate\Http\Request`, `BlogAnalyticsService`, `BackedEnum`, `RegisterRestaurant`, `Illuminate\Support\Collection`, `Illuminate\Database\Eloquent\Builder`, `Filament\Resources\Pages\ListRecords`?**
  _High betweenness centrality (0.021) - this node is a cross-community bridge._
- **Why does `Restaurant` connect `Restaurant` to `ExportFile`, `TestCase`, `Filament\Schemas\Schema`, `PlatformSetting`, `Order`, `Illuminate\Database\Eloquent\Relations\BelongsTo`, `AdminPanelProvider.php`, `GuestContext`, `Illuminate\Database\Eloquent\Model`, `CashierFilamentActionsTest`, `RestaurantTheme`, `GraceReadOnlyTest`, `SubscriptionInvoiceResource`, `RestaurantReview`, `SitemapController.php`, `CommissionReconciliation`, `MenuItem`, `User`, `SubscriptionStatus`, `Filament\Resources\Pages\EditRecord`, `Filament\Tables\Table`, `SubscriptionInvoice`, `Visit`, `Illuminate\Http\Request`, `BackedEnum`, `RegisterRestaurant`, `Illuminate\Support\Collection`, `Illuminate\Database\Eloquent\Builder`, `Filament\Resources\Pages\ListRecords`?**
  _High betweenness centrality (0.021) - this node is a cross-community bridge._
- **What connects `$schema`, `name`, `type` to the rest of the system?**
  _345 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `stat/chart.js` be split into smaller, more focused modules?**
  _Cohesion score 0.023399238164338837 - nodes in this community are weakly interconnected._
- **Should `components/chart.js` be split into smaller, more focused modules?**
  _Cohesion score 0.011720158672917418 - nodes in this community are weakly interconnected._
- **Should `code-editor.js` be split into smaller, more focused modules?**
  _Cohesion score 0.008718773188226564 - nodes in this community are weakly interconnected._