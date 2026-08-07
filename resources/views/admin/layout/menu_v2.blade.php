{{-- ==========================================================================
     SIDEBAR + TOPBAR (v2)
     Parallel redesign of admin/layout/menu.blade.php using the v2 design
     system (public/admin-v2). Every @can / hasAnyPermission / route() call
     below is copied verbatim from the live menu.blade.php — only the HTML
     structure and CSS classes changed. Not included by any live page yet;
     wired up by master_v2.blade.php / table_master_v2.blade.php only.
     ========================================================================== --}}
@php
    $user = \Illuminate\Support\Facades\Auth::user();
    $organizationHasPermission = $user->hasAnyPermission(['customer_view', 'vendor_view', 'customer_contact_view', 'vendor_contact_view']);
@endphp
<div class="app-shell-v2" id="appShellV2">
    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

    <!-- ================= Sidebar ================= -->
    <aside class="sidebar-v2" id="sidebarV2">
        <div class="sidebar-brand">
            <a href="{{ url('admin/index') }}" class="sidebar-brand-link">
                <span class="sidebar-brand-icon"><i class="mdi mdi-cube"></i></span>
                <span class="sidebar-brand-text">{{ Session::get('software_title') ?? 'Bestow' }}</span>
            </a>
            <button type="button" class="sidebar-collapse-btn d-none d-lg-inline-flex" id="sidebarCollapseBtn" title="Collapse sidebar">
                <i class="mdi mdi-chevron-left"></i>
            </button>
        </div>

        <nav class="sidebar-nav">
            <div class="sidebar-nav-title">Navigation</div>

            <div class="sidebar-item">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="mdi mdi-view-dashboard sidebar-link-icon"></i>
                    <span class="sidebar-link-text">Dashboard</span>
                </a>
            </div>

            @if($organizationHasPermission)
                @php $grpActive = request()->routeIs(['admin.customers.*','admin.vendor.*','admin.customer.contact.*','admin.vendor.contact.*','admin.salesman.*']); @endphp
                <div class="sidebar-group {{ $grpActive ? 'open' : '' }}">
                    <button type="button" class="sidebar-link sidebar-group-toggle {{ $grpActive ? 'active' : '' }}">
                        <i class="mdi mdi-account-box sidebar-link-icon"></i>
                        <span class="sidebar-link-text">Organizations</span>
                        <i class="mdi mdi-chevron-down sidebar-caret"></i>
                    </button>
                    <div class="sidebar-submenu" {{ $grpActive ? '' : 'style=display:none' }}>
                        @can('customer_view')
                            <a href="{{ route('admin.customers.list') }}" class="sidebar-sublink {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">Customer</a>
                        @endcan
                        @can('vendor_view')
                            <a href="{{ route('admin.vendor.list') }}" class="sidebar-sublink {{ request()->routeIs('admin.vendor.*') ? 'active' : '' }}">Vendor</a>
                        @endcan
                        @can('customer_contact_view')
                            <a href="{{ route('admin.customer.contact.list') }}" class="sidebar-sublink {{ request()->routeIs('admin.customer.contact.*') ? 'active' : '' }}">Customer Contact</a>
                        @endcan
                        @can('vendor_contact_view')
                            <a href="{{ route('admin.vendor.contact.list') }}" class="sidebar-sublink {{ request()->routeIs('admin.vendor.contact.*') ? 'active' : '' }}">Vendor Contact</a>
                        @endcan
                        @can('vendor_contact_view')
                            <a href="{{ route('admin.salesman.list') }}" class="sidebar-sublink {{ request()->routeIs('admin.salesman.*') ? 'active' : '' }}">Salesman</a>
                        @endcan
                    </div>
                </div>
            @endif

            @if($user->hasAnyPermission(['website_order_view','quotation_view', 'sales_view', 'delivery_challan_view', 'invoice_view', 'purchase_view']))
                @php $grpActive = request()->routeIs(['order_list','admin.quotation.*','admin.sales.*','admin.challan.*','admin.invoice.*','admin.purchase.*','admin.orders.*']); @endphp
                <div class="sidebar-group {{ $grpActive ? 'open' : '' }}">
                    <button type="button" class="sidebar-link sidebar-group-toggle {{ $grpActive ? 'active' : '' }}">
                        <i class="mdi mdi-cart sidebar-link-icon"></i>
                        <span class="sidebar-link-text">Sales | Purchase</span>
                        <i class="mdi mdi-chevron-down sidebar-caret"></i>
                    </button>
                    <div class="sidebar-submenu" {{ $grpActive ? '' : 'style=display:none' }}>
                        @can('website_order_view')
                            <a href="{{ route('order_list') }}" class="sidebar-sublink {{ request()->routeIs('order_list') ? 'active' : '' }}">Website Orders</a>
                        @endcan
                        @can('quotation_view')
                            <a href="{{ route('admin.quotation.list') }}" class="sidebar-sublink {{ request()->routeIs('admin.quotation.*') ? 'active' : '' }}">Quotation</a>
                        @endcan
                        @can('sales_view')
                            <a href="{{ route('admin.sales.list') }}" class="sidebar-sublink {{ request()->routeIs('admin.sales.*') ? 'active' : '' }}">Sales</a>
                        @endcan
                        @can('delivery_challan_view')
                            <a href="{{route('admin.challan.list')}}" class="sidebar-sublink {{ request()->routeIs('admin.challan.*') ? 'active' : '' }}">Delivery</a>
                        @endcan
                        @can('invoice_view')
                            <a href="{{route('admin.invoice.list')}}" class="sidebar-sublink {{ request()->routeIs('admin.invoice.*') ? 'active' : '' }}">Invoice</a>
                        @endcan
                        @can('purchase_view')
                            <a href="{{route('admin.purchase.list')}}" class="sidebar-sublink {{ request()->routeIs('admin.purchase.list') ? 'active' : '' }}">Purchase</a>
                            <a href="{{route('admin.purchase.requirement.list')}}" class="sidebar-sublink {{ request()->routeIs('admin.purchase.requirement.*') ? 'active' : '' }}">Purchase Request</a>
                        @endcan
                        <a href="{{route('admin.orders.index')}}" class="sidebar-sublink {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">Website Orders</a>
                    </div>
                </div>
            @endif

            @if($user->hasAnyPermission(['quot_report_view', 'sales_report_view', 'invoice_report_view', 'sales_summary_report_view', 'product_wise_sales_report_view', 'salesman_wise_sales_report_view', 'stock_available_report_view', 'raw_material_pending_report_view', 'production_pending_report_view', 'stitching_pending_report_view', 'pressing_pending_report_view', 'packaging_pending_report_view', 'belt_production_report_view', 'socks_missing_formula_report_view', 'belt_missing_formula_report_view']))
                @php $grpActive = request()->routeIs('admin.reports.*'); @endphp
                <div class="sidebar-group {{ $grpActive ? 'open' : '' }}">
                    <button type="button" class="sidebar-link sidebar-group-toggle {{ $grpActive ? 'active' : '' }}">
                        <i class="mdi mdi-chart-box sidebar-link-icon"></i>
                        <span class="sidebar-link-text">Reports</span>
                        <i class="mdi mdi-chevron-down sidebar-caret"></i>
                    </button>
                    <div class="sidebar-submenu" {{ $grpActive ? '' : 'style=display:none' }}>
                        @can('quot_report_view')
                            <a href="{{ route('admin.reports.quotation') }}" class="sidebar-sublink {{ request()->routeIs('admin.reports.quotation') ? 'active' : '' }}">Quotation</a>
                        @endcan
                        @can('sales_report_view')
                            <a href="{{ route('admin.reports.sales') }}" class="sidebar-sublink {{ request()->routeIs('admin.reports.sales') ? 'active' : '' }}">Sales</a>
                            <a href="{{ route('admin.reports.sales.out_of_stock_items') }}" class="sidebar-sublink {{ request()->routeIs('admin.reports.sales.out_of_stock_items') ? 'active' : '' }}">Sales Out of stock Items</a>
                        @endcan
                        @can('invoice_report_view')
                            <a href="{{ route('admin.reports.invoice') }}" class="sidebar-sublink {{ request()->routeIs('admin.reports.invoice') ? 'active' : '' }}">Invoice</a>
                        @endcan
                        @can('sales_summary_report_view')
                            <a href="{{ route('admin.reports.sales_summary') }}" class="sidebar-sublink {{ request()->routeIs('admin.reports.sales_summary') ? 'active' : '' }}">Sales Report - Daily/Monthly</a>
                        @endcan
                        @can('product_wise_sales_report_view')
                            <a href="{{ route('admin.reports.product_wise_sales') }}" class="sidebar-sublink {{ request()->routeIs('admin.reports.product_wise_sales') ? 'active' : '' }}">Product Wise Sales Report</a>
                        @endcan
                        @can('salesman_wise_sales_report_view')
                            <a href="{{ route('admin.reports.salesman_wise_sales') }}" class="sidebar-sublink {{ request()->routeIs('admin.reports.salesman_wise_sales') ? 'active' : '' }}">Sales-MAN Wise Sales Report</a>
                        @endcan
                        @can('stock_available_report_view')
                            <a href="{{ route('admin.reports.stock_available') }}" class="sidebar-sublink {{ request()->routeIs('admin.reports.stock_available') ? 'active' : '' }}">Product Wise Available Stock Report</a>
                        @endcan
                        @can('raw_material_pending_report_view')
                            <a href="{{ route('admin.reports.raw_material_pending') }}" class="sidebar-sublink {{ request()->routeIs('admin.reports.raw_material_pending') ? 'active' : '' }}">RAW Material Required Pending Report</a>
                        @endcan
                        @can('production_pending_report_view')
                            <a href="{{ route('admin.reports.production_pending') }}" class="sidebar-sublink {{ request()->routeIs('admin.reports.production_pending') ? 'active' : '' }}">Production Pending</a>
                        @endcan
                        @can('stitching_pending_report_view')
                            <a href="{{ route('admin.reports.stitching_pending') }}" class="sidebar-sublink {{ request()->routeIs('admin.reports.stitching_pending') ? 'active' : '' }}">Stitching Pending</a>
                        @endcan
                        @can('pressing_pending_report_view')
                            <a href="{{ route('admin.reports.pressing_pending') }}" class="sidebar-sublink {{ request()->routeIs('admin.reports.pressing_pending') ? 'active' : '' }}">Press Pending</a>
                        @endcan
                        @can('packaging_pending_report_view')
                            <a href="{{ route('admin.reports.packaging_pending') }}" class="sidebar-sublink {{ request()->routeIs('admin.reports.packaging_pending') ? 'active' : '' }}">Packing Pending</a>
                        @endcan
                        @can('belt_production_report_view')
                            <a href="{{ route('admin.reports.belt_production') }}" class="sidebar-sublink {{ request()->routeIs('admin.reports.belt_production') ? 'active' : '' }}">Belt Production</a>
                        @endcan
                        @can('socks_missing_formula_report_view')
                            <a href="{{ route('admin.reports.socks_missing_formula') }}" class="sidebar-sublink {{ request()->routeIs('admin.reports.socks_missing_formula') ? 'active' : '' }}">Socks Products Without Formula</a>
                        @endcan
                        @can('belt_missing_formula_report_view')
                            <a href="{{ route('admin.reports.belt_missing_formula') }}" class="sidebar-sublink {{ request()->routeIs('admin.reports.belt_missing_formula') ? 'active' : '' }}">Belt Products Without Formula</a>
                        @endcan
                    </div>
                </div>
            @endif

            @if($user->hasAnyPermission(['product_view']))
                @php $grpActive = request()->routeIs(['admin.stock.*','admin.product.*','admin.category.*','admin.subcategory.*','admin.uom.*','admin.brand.*','admin.material.*','admin.attribute.*','admin.variation.*','admin.raw.material.*']); @endphp
                <div class="sidebar-group {{ $grpActive ? 'open' : '' }}">
                    <button type="button" class="sidebar-link sidebar-group-toggle {{ $grpActive ? 'active' : '' }}">
                        <i class="mdi mdi-dropbox sidebar-link-icon"></i>
                        <span class="sidebar-link-text">Inventory</span>
                        <i class="mdi mdi-chevron-down sidebar-caret"></i>
                    </button>
                    <div class="sidebar-submenu" {{ $grpActive ? '' : 'style=display:none' }}>
                        <a href="{{route('admin.stock.status')}}" class="sidebar-sublink {{ request()->routeIs('admin.stock.status') ? 'active' : '' }}">Stock Status</a>
                        <a href="{{route('admin.stock.book')}}" class="sidebar-sublink {{ request()->routeIs('admin.stock.book') ? 'active' : '' }}">Stock Book</a>
                        @can('product_view')
                            <a href="{{ route('admin.product.list') }}" class="sidebar-sublink {{ request()->routeIs('admin.product.*') ? 'active' : '' }}">Product</a>
                            <a href="{{ route('admin.category.list') }}" class="sidebar-sublink {{ request()->routeIs('admin.category.*') ? 'active' : '' }}">Category</a>
                            <a href="{{ route('admin.subcategory.list') }}" class="sidebar-sublink {{ request()->routeIs('admin.subcategory.*') ? 'active' : '' }}">Subcategory</a>
                            <a href="{{ route('admin.uom.list') }}" class="sidebar-sublink {{ request()->routeIs('admin.uom.*') ? 'active' : '' }}">Usage Unit</a>
                            <a href="{{ route('admin.brand.list') }}" class="sidebar-sublink {{ request()->routeIs('admin.brand.*') ? 'active' : '' }}">Brand</a>
                            <a href="{{ route('admin.material.list') }}" class="sidebar-sublink {{ request()->routeIs('admin.material.*') ? 'active' : '' }}">Material</a>
                            <a href="{{ route('admin.attribute.list') }}" class="sidebar-sublink {{ request()->routeIs('admin.attribute.*') ? 'active' : '' }}">Attribute</a>
                            <a href="{{ route('admin.variation.list') }}" class="sidebar-sublink {{ request()->routeIs('admin.variation.*') ? 'active' : '' }}">Variation</a>
                            <a href="{{ route('admin.raw.material.list') }}" class="sidebar-sublink {{ request()->routeIs('admin.raw.material.*') ? 'active' : '' }}">Raw Material</a>
                        @endcan
                    </div>
                </div>
            @endif

            @if($user->hasAnyPermission(['production_master_view','production_view', 'stitching_view', 'pressing_view', 'washing_view', 'packaging_view', 'formula_view']))
                @php $grpActive = request()->routeIs('admin.production.*'); @endphp
                <div class="sidebar-group {{ $grpActive ? 'open' : '' }}">
                    <button type="button" class="sidebar-link sidebar-group-toggle {{ $grpActive ? 'active' : '' }}">
                        <i class="mdi mdi-factory sidebar-link-icon"></i>
                        <span class="sidebar-link-text">Production</span>
                        <i class="mdi mdi-chevron-down sidebar-caret"></i>
                    </button>
                    <div class="sidebar-submenu" {{ $grpActive ? '' : 'style=display:none' }}>
                        @can('production_master_view')
                            <a href="{{ route('admin.production.dashboard') }}" class="sidebar-sublink {{ request()->routeIs('admin.production.dashboard') ? 'active' : '' }}">All Production Dashboard</a>
                        @endcan
                        @can('production_view')
                            <a href="{{ route('admin.production.process') }}" class="sidebar-sublink {{ request()->routeIs('admin.production.process') ? 'active' : '' }}">Production Dashboard</a>
                        @endcan
                        @can('stitching_view')
                            <a href="{{ route('admin.production.stitching.dashboard') }}" class="sidebar-sublink {{ request()->routeIs('admin.production.stitching.dashboard') ? 'active' : '' }}">Stitching Dashboard</a>
                        @endcan
                        @can('pressing_view')
                            <a href="{{ route('admin.production.pressing.dashboard') }}" class="sidebar-sublink {{ request()->routeIs('admin.production.pressing.dashboard') ? 'active' : '' }}">Pressing Dashboard</a>
                        @endcan
                        @can('washing_view')
                            <a href="{{ route('admin.production.washing.dashboard') }}" class="sidebar-sublink {{ request()->routeIs('admin.production.washing.dashboard') ? 'active' : '' }}">Washing Dashboard</a>
                        @endcan
                        @can('packaging_view')
                            <a href="{{ route('admin.production.packaging.dashboard') }}" class="sidebar-sublink {{ request()->routeIs('admin.production.packaging.dashboard') ? 'active' : '' }}">Packaging Dashboard</a>
                        @endcan
                        @can('formula_view')
                            <a href="{{ route('admin.production.formula_list') }}" class="sidebar-sublink {{ request()->routeIs('admin.production.formula_list') ? 'active' : '' }}">Formula Master</a>
                        @endcan
                    </div>
                </div>
            @endif

            @if($user->hasAnyPermission(['buckle_formula_view','belt_production_view']))
                @php $grpActive = request()->routeIs(['admin.belt_production.*','admin.production.buckle_formula_*','admin.bukkal.*','admin.niwar.*','admin.belt.*']); @endphp
                <div class="sidebar-group {{ $grpActive ? 'open' : '' }}">
                    <button type="button" class="sidebar-link sidebar-group-toggle {{ $grpActive ? 'active' : '' }}">
                        <i class="mdi mdi-buffer sidebar-link-icon"></i>
                        <span class="sidebar-link-text">Belt Production</span>
                        <i class="mdi mdi-chevron-down sidebar-caret"></i>
                    </button>
                    <div class="sidebar-submenu" {{ $grpActive ? '' : 'style=display:none' }}>
                        @can('belt_production_view')
                            <a href="{{ route('admin.belt_production.list') }}" class="sidebar-sublink {{ request()->routeIs('admin.belt_production.list') ? 'active' : '' }}">Belt Production List</a>
                            <a href="{{ route('admin.belt_production.add') }}" class="sidebar-sublink {{ request()->routeIs('admin.belt_production.add') ? 'active' : '' }}">Add Belt Production</a>
                        @endcan
                        @can('buckle_formula_view')
                            <a href="{{ route('admin.production.buckle_formula_list') }}" class="sidebar-sublink {{ request()->routeIs('admin.production.buckle_formula_list') ? 'active' : '' }}">Belt Formula Master</a>
                        @endcan
                        <a href="{{ route('admin.bukkal.list') }}" class="sidebar-sublink {{ request()->routeIs('admin.bukkal.*') ? 'active' : '' }}">Bukkal Code</a>
                        <a href="{{ route('admin.niwar.list') }}" class="sidebar-sublink {{ request()->routeIs('admin.niwar.*') ? 'active' : '' }}">Niwar Code</a>
                        <a href="{{ route('admin.belt.list') }}" class="sidebar-sublink {{ request()->routeIs('admin.belt.*') ? 'active' : '' }}">Bukkal Costing</a>
                    </div>
                </div>
            @endif

            @if($user->hasAnyPermission(['term_view','gst_view', 'payment_terms_view', 'industry_view', 'customer_type_view', 'country_view', 'state_view', 'city_view']))
                @php $grpActive = request()->routeIs(['admin.term.*','admin.gst.*','admin.payment_terms.*','admin.industry.*','admin.type.*','admin.country.*','admin.state.*','admin.city.*','admin.banners.*','admin.shipping.*']); @endphp
                <div class="sidebar-group {{ $grpActive ? 'open' : '' }}">
                    <button type="button" class="sidebar-link sidebar-group-toggle {{ $grpActive ? 'active' : '' }}">
                        <i class="mdi mdi-dots-horizontal sidebar-link-icon"></i>
                        <span class="sidebar-link-text">Others</span>
                        <i class="mdi mdi-chevron-down sidebar-caret"></i>
                    </button>
                    <div class="sidebar-submenu" {{ $grpActive ? '' : 'style=display:none' }}>
                        @can('term_view')
                            <a href="{{route('admin.term.list')}}" class="sidebar-sublink {{ request()->routeIs('admin.term.*') ? 'active' : '' }}"><i class="mdi mdi-file"></i> Terms &amp; Condition</a>
                        @endcan
                        @can('gst_view')
                            <a href="{{route('admin.gst.list')}}" class="sidebar-sublink {{ request()->routeIs('admin.gst.*') ? 'active' : '' }}"><i class="mdi mdi-percent"></i> GST</a>
                        @endcan
                        @can('payment_terms_view')
                            <a href="{{route('admin.payment_terms.list')}}" class="sidebar-sublink {{ request()->routeIs('admin.payment_terms.*') ? 'active' : '' }}"><i class="mdi mdi-file-find"></i> Payment Terms</a>
                        @endcan
                        @can('industry_view')
                            <a href="{{ route('admin.industry.list') }}" class="sidebar-sublink {{ request()->routeIs('admin.industry.*') ? 'active' : '' }}"><i class="mdi mdi-hospital-building"></i> Industry</a>
                        @endcan
                        @can('customer_type_view')
                            <a href="{{ route('admin.type.list') }}" class="sidebar-sublink {{ request()->routeIs('admin.type.*') ? 'active' : '' }}"><i class="mdi mdi-account-box"></i> Customer Type</a>
                        @endcan
                        @can('country_view')
                            <a href="{{route('admin.country.list')}}" class="sidebar-sublink {{ request()->routeIs('admin.country.*') ? 'active' : '' }}"><i class="mdi mdi-flag"></i> Country</a>
                        @endcan
                        @can('state_view')
                            <a href="{{ route('admin.state.list') }}" class="sidebar-sublink {{ request()->routeIs('admin.state.*') ? 'active' : '' }}"><i class="mdi mdi-home"></i> State</a>
                        @endcan
                        @can('city_view')
                            <a href="{{ route('admin.city.list') }}" class="sidebar-sublink {{ request()->routeIs('admin.city.*') ? 'active' : '' }}"><i class="mdi mdi-city"></i> City</a>
                        @endcan
                        <a href="{{ route('admin.banners.index')}}" class="sidebar-sublink {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}"><i class="mdi mdi-image"></i> Banner</a>
                        <a href="{{ route('admin.shipping.index')}}" class="sidebar-sublink {{ request()->routeIs('admin.shipping.*') ? 'active' : '' }}"><i class="mdi mdi-truck"></i> Shipping charge</a>
                    </div>
                </div>
            @endif

            @can('machine_master')
                @php $grpActive = request()->is('machine/*', 'stitching-machine/*', 'pressing-machine/*', 'washing-machine/*', 'packaging-machine/*'); @endphp
                <div class="sidebar-group {{ $grpActive ? 'open' : '' }}">
                    <button type="button" class="sidebar-link sidebar-group-toggle {{ $grpActive ? 'active' : '' }}">
                        <i class="mdi mdi-engine sidebar-link-icon"></i>
                        <span class="sidebar-link-text">Machine</span>
                        <i class="mdi mdi-chevron-down sidebar-caret"></i>
                    </button>
                    <div class="sidebar-submenu" {{ $grpActive ? '' : 'style=display:none' }}>
                        <a href="{{ url('machine/list') }}" class="sidebar-sublink {{ request()->is('machine/list') ? 'active' : '' }}">Machine Master</a>
                        <a href="{{ url('stitching-machine/list') }}" class="sidebar-sublink {{ request()->is('stitching-machine/list') ? 'active' : '' }}">Stitching Machine</a>
                        <a href="{{ url('pressing-machine/list') }}" class="sidebar-sublink {{ request()->is('pressing-machine/list') ? 'active' : '' }}">Pressing Machine</a>
                        <a href="{{ url('washing-machine/list') }}" class="sidebar-sublink {{ request()->is('washing-machine/list') ? 'active' : '' }}">Washing Machine</a>
                        <a href="{{ url('packaging-machine/list') }}" class="sidebar-sublink {{ request()->is('packaging-machine/list') ? 'active' : '' }}">Packaging Machine</a>
                    </div>
                </div>
            @endcan

            @if($user->hasAnyPermission(['role_view', 'user_view']))
                @php $grpActive = request()->routeIs(['admin.roles.*','admin.user.*']); @endphp
                <div class="sidebar-group {{ $grpActive ? 'open' : '' }}">
                    <button type="button" class="sidebar-link sidebar-group-toggle {{ $grpActive ? 'active' : '' }}">
                        <i class="mdi mdi-account-network sidebar-link-icon"></i>
                        <span class="sidebar-link-text">User Management</span>
                        <i class="mdi mdi-chevron-down sidebar-caret"></i>
                    </button>
                    <div class="sidebar-submenu" {{ $grpActive ? '' : 'style=display:none' }}>
                        @can('role_view')
                            <a href="{{route('admin.roles.list')}}" class="sidebar-sublink {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}"><i class="mdi mdi-sitemap"></i> Roles</a>
                        @endcan
                        @can('user_view')
                            <a href="{{route('admin.user.list')}}" class="sidebar-sublink {{ request()->routeIs('admin.user.*') ? 'active' : '' }}"><i class="mdi mdi-account"></i> User</a>
                        @endcan
                    </div>
                </div>
            @endif

            @can('company_view')
                <div class="sidebar-item">
                    <a href="{{ route('admin.company.list') }}" class="sidebar-link {{ request()->routeIs('admin.company.*') ? 'active' : '' }}">
                        <i class="mdi mdi-hospital-building sidebar-link-icon"></i>
                        <span class="sidebar-link-text">Company Details</span>
                    </a>
                </div>
            @endcan
        </nav>

        <div class="sidebar-help">
            <div class="sidebar-help-title">For Help?</div>
            <div class="sidebar-help-row"><i class="mdi mdi-email-outline"></i> saggyt19@gmail.com</div>
            <div class="sidebar-help-row"><i class="mdi mdi-phone-outline"></i> +91-8866368568</div>
        </div>
    </aside>

    <div class="main-wrapper-v2">

        <!-- ================= Topbar ================= -->
        <header class="topbar-v2">
            <button type="button" class="topbar-icon-btn d-lg-none" id="sidebarMobileToggle" title="Menu">
                <i class="mdi mdi-menu"></i>
            </button>

            <div class="topbar-search">
                <i class="mdi mdi-magnify"></i>
                <input type="text" class="topbar-search-input" placeholder="Search customers, orders, invoices..." data-shortcut-focus>
                <kbd class="topbar-search-kbd">/</kbd>
            </div>

            <div class="topbar-spacer"></div>

            <div class="topbar-actions">
                <button type="button" class="topbar-icon-btn" data-theme-toggle title="Toggle theme">
                    <i class="mdi mdi-weather-night"></i>
                </button>

                <div class="dropdown">
                    <button type="button" class="topbar-icon-btn" data-bs-toggle="dropdown" data-bs-auto-close="outside" title="Notifications">
                        <i class="mdi mdi-bell-outline"></i>
                        <span class="topbar-badge-dot"></span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end notif-panel">
                        <div class="notif-panel-header">Notifications</div>
                        <div class="notif-item">
                            <span class="notif-icon bg-primary-soft"><i class="mdi mdi-file-document-outline"></i></span>
                            <div>
                                <div class="notif-text"><strong>7 quotations</strong> awaiting conversion</div>
                                <div class="notif-time">Today</div>
                            </div>
                        </div>
                        <div class="notif-item">
                            <span class="notif-icon bg-success-soft"><i class="mdi mdi-truck-check-outline"></i></span>
                            <div>
                                <div class="notif-text">Delivery challan <strong>DC-1042</strong> completed</div>
                                <div class="notif-time">Yesterday</div>
                            </div>
                        </div>
                        <div class="notif-item">
                            <span class="notif-icon bg-warning-soft"><i class="mdi mdi-alert-outline"></i></span>
                            <div>
                                <div class="notif-text">Raw material stock running low</div>
                                <div class="notif-time">2 days ago</div>
                            </div>
                        </div>
                        <div class="notif-panel-footer text-muted">Sample preview data — no notification backend wired yet.</div>
                    </div>
                </div>

                <div class="dropdown">
                    <button type="button" class="topbar-user-btn" data-bs-toggle="dropdown">
                        <img src="{{ asset('img_avatar.png') }}" alt="" class="topbar-user-avatar">
                        <span class="topbar-user-name d-none d-md-inline">{{ $user->name ?? 'Admin' }}</span>
                        <i class="mdi mdi-chevron-down d-none d-md-inline"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end user-menu-panel">
                        <div class="user-menu-header">
                            <div class="fw-semibold">{{ $user->name ?? 'Admin' }}</div>
                            <div class="text-muted text-sm">{{ $user->email ?? '' }}</div>
                        </div>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ url('client/user/profile') }}"><i class="mdi mdi-account-outline"></i> Profile</a>
                        <a class="dropdown-item text-danger" href="{{ route('admin.logout') }}"><i class="mdi mdi-logout"></i> Logout</a>
                    </div>
                </div>
            </div>
        </header>
