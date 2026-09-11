<style>
    #sidebar-menu > ul > li > a {
        border-left: 3px solid transparent;
    }
    #sidebar-menu > ul > li > a:hover,
    #sidebar-menu > ul > li > a.subdrop,
    #sidebar-menu > ul > li > a.active {
        border-left-color: #26a69a;
    }
    #sidebar-menu ul ul li.menu-section-label {
        padding: 12px 20px 4px;
        font-size: 10.5px;
        letter-spacing: .09em;
        text-transform: uppercase;
        color: #8a97a8;
        font-weight: 600;
        pointer-events: none;
    }
    #sidebar-menu ul ul a:hover,
    #sidebar-menu ul ul li.active > a {
        border-left-color: #26a69a;
    }
    @media (max-width: 768px) {
        #sidebar-menu > ul > li > a,
        #sidebar-menu ul ul a {
            padding-top: 14px;
            padding-bottom: 14px;
        }
    }
</style>

<!-- Loader -->
<div id="preloader">
    <div id="status">
        <div class="spinner">
            <div class="spinner-wrapper">
                <div class="rotator">
                    <div class="inner-spin"></div>
                    <div class="inner-spin"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Begin page -->
<div id="wrapper">

    <!-- Top Bar Start -->
    <div class="topbar">

        <!-- LOGO -->
        <div class="topbar-left">
            <a href="{{ url('admin/index') }}" class="logo"><span>{{Session::get('software_title')}}<span></span></span>
                <i class="mdi mdi-cube"></i>
            </a>

        </div>

        <!-- Button mobile view to collapse sidebar menu -->
        <div class="navbar navbar-default" role="navigation">
            <div class="container">

                <!-- Navbar-left -->
                <ul class="nav navbar-nav navbar-left">
                    <li>
                        <button class="button-menu-mobile open-left waves-effect waves-light">
                            <i class="mdi mdi-menu"></i>
                        </button>
                    </li>

                </ul>

                <!-- Right(Notification) -->
                <ul class="nav navbar-nav navbar-right">
                    {{--  <li>
                        <a href="#" class="right-menu-item dropdown-toggle" data-toggle="dropdown">
                            <i class="mdi mdi-bell"></i>
                            <span class="badge up bg-primary">4</span>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-right arrow-dropdown-menu arrow-menu-right dropdown-lg user-list notify-list">
                            <li>
                                <h5>Notifications</h5>
                            </li>
                            <li>
                                <a href="#" class="user-list-item">
                                    <div class="icon bg-info">
                                        <i class="mdi mdi-account"></i>
                                    </div>
                                    <div class="user-desc">
                                        <span class="name">New Signup</span>
                                        <span class="time">5 hours ago</span>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="user-list-item">
                                    <div class="icon bg-danger">
                                        <i class="mdi mdi-comment"></i>
                                    </div>
                                    <div class="user-desc">
                                        <span class="name">New Message received</span>
                                        <span class="time">1 day ago</span>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="user-list-item">
                                    <div class="icon bg-warning">
                                        <i class="mdi mdi-settings"></i>
                                    </div>
                                    <div class="user-desc">
                                        <span class="name">Settings</span>
                                        <span class="time">1 day ago</span>
                                    </div>
                                </a>
                            </li>
                            <li class="all-msgs text-center">
                                <p class="m-0"><a href="#">See all Notification</a></p>
                            </li>
                        </ul>
                    </li>  --}}

                    {{--  <li>
                        <a href="#" class="right-menu-item dropdown-toggle" data-toggle="dropdown">
                            <i class="mdi mdi-email"></i>
                            <span class="badge up bg-danger">8</span>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-right arrow-dropdown-menu arrow-menu-right dropdown-lg user-list notify-list">
                            <li>
                                <h5>Messages</h5>
                            </li>
                            <li>
                                <a href="#" class="user-list-item">
                                    <div class="avatar">
                                        <img src="assets/images/users/avatar-2.jpg" alt="">
                                    </div>
                                    <div class="user-desc">
                                        <span class="name">Patricia Beach</span>
                                        <span class="desc">There are new settings available</span>
                                        <span class="time">2 hours ago</span>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="user-list-item">
                                    <div class="avatar">
                                        <img src="assets/images/users/avatar-3.jpg" alt="">
                                    </div>
                                    <div class="user-desc">
                                        <span class="name">Connie Lucas</span>
                                        <span class="desc">There are new settings available</span>
                                        <span class="time">2 hours ago</span>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="user-list-item">
                                    <div class="avatar">
                                        <img src="assets/images/users/avatar-4.jpg" alt="">
                                    </div>
                                    <div class="user-desc">
                                        <span class="name">Margaret Becker</span>
                                        <span class="desc">There are new settings available</span>
                                        <span class="time">2 hours ago</span>
                                    </div>
                                </a>
                            </li>
                            <li class="all-msgs text-center">
                                <p class="m-0"><a href="#">See all Messages</a></p>
                            </li>
                        </ul>
                    </li>  --}}

                    {{--  <li>
                        <a href="javascript:void(0);" class="right-bar-toggle right-menu-item">
                            <i class="mdi mdi-settings"></i>
                        </a>
                    </li>  --}}

                    <li class="dropdown user-box">
                        <a href="" class="dropdown-toggle waves-effect waves-light user-link" data-toggle="dropdown" aria-expanded="true">
                            <img src="/img_avatar.png" alt="user-img" class="img-circle user-img">
                        </a>

                        <ul class="dropdown-menu dropdown-menu-right arrow-dropdown-menu arrow-menu-right user-list notify-list">
                            <li>
                                <h5>Hi, Admin</h5>
                            </li>

                            <li><a href="{{route('admin.logout')}}"><i class="ti-power-off m-r-5"></i> Logout</a></li>
                        </ul>
                    </li>

                </ul> <!-- end navbar-right -->

            </div><!-- end container -->
        </div><!-- end navbar -->
    </div>
    <!-- Top Bar End -->
    <!-- ========== Left Sidebar Start ========== -->
    <div class="left side-menu">
        <div class="sidebar-inner slimscrollleft">

            <div id="sidebar-menu">
                <div class="user-details">
                    <div class="overlay"></div>
                    <div class="text-center">
                        <img src="/img_avatar.png" alt="" class="thumb-md img-circle">
                    </div>
                    <div class="user-info">
                        <div>
                            <a href="#setting-dropdown" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Admin <span class="mdi mdi-menu-down"></span></a>
                        </div>
                    </div>
                </div>

                <div class="dropdown" id="setting-dropdown">
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('logout') }}"><i class="mdi mdi-logout m-r-5"></i> Logout</a></li>
                    </ul>
                </div>
                <ul>
                    <li class="menu-title">Navigation</li>

                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="waves-effect"><i class="mdi mdi-view-dashboard">
                            </i><span class="badge badge-success pull-right"></span> <span> Dashboard </span>
                        </a>
                    </li>
                    @php
                        $user = \Illuminate\Support\Facades\Auth::user();
                        $organizationHasPermission = $user->hasAnyPermission(['customer_view', 'vendor_view', 'customer_contact_view', 'vendor_contact_view']);
                    @endphp

                    {{--  <li class="has_sub">
                          <a href="{{ url('client/search/product/master') }}" class="waves-effect">
                              <i class="mdi mdi-file-find"></i>
                              <span>Master Search </span></a></li><li class="has_sub">
                          <a href="{{ url('client/search/purchase/master') }}" class="waves-effect">
                              <i class="mdi mdi-file-find"></i>
                              <span>Purchase Master Search </span></a>
                      </li>--}}


                    @if($organizationHasPermission)
                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect">
                                <i class="mdi mdi-account-box"></i>
                                <span> Organizations </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul class="list-unstyled">
                                @can('customer_view')
                                    <li><a href="{{route('admin.customers.list')}}"><i class="mdi mdi-account-box"></i>Customer</a></li>
                                @endcan
                                @can('vendor_view')
                                    <li><a href="{{route('admin.vendor.list')}}"><i class="mdi mdi-domain"></i>Vendor</a></li>
                                @endcan
                                @can('customer_contact_view')
                                    <li><a href="{{route('admin.customer.contact.list')}}"><i class="mdi mdi-phone"></i>Customer Contact</a></li>
                                @endcan
                                @can('vendor_contact_view')
                                    <li><a href="{{route('admin.vendor.contact.list')}}"><i class="mdi mdi-account-switch"></i>Vendor Contact</a></li>
                                @endcan
                                @can('vendor_contact_view')
                                    <li><a href="{{route('admin.salesman.list')}}"><i class="mdi mdi-account-multiple"></i>Salesman</a></li>
                                @endcan

                                {{-- <li><a href="http://127.0.0.1:8001/item/manufacturer/list">Manufacturer</a></li>

                                 <li><a href="http://127.0.0.1:8001/item/importer/list">Importer</a></li>

                                 <li><a href="http://127.0.0.1:8001/item/packer/list">Packer</a></li>--}}
                            </ul>
                        </li>
                    @endif
                    @if($user->hasAnyPermission(['website_order_view','quotation_view', 'sales_view', 'delivery_challan_view', 'invoice_view', 'purchase_view']))
                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect">
                                <i class="mdi mdi-cart"></i>
                                <span> Sales | Purchase </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul class="list-unstyled">
                                @can('website_order_view')
                                    <li><a href="{{ route('order_list') }}"><i class="mdi mdi-web"></i>Website Orders</a></li>
                                @endcan
                                @can('quotation_view')
                                    <li><a href="{{ route('admin.quotation.list') }}"><i class="mdi mdi-file-document-box"></i>Quotation</a></li>
                                @endcan
                                @can('sales_view')
                                    <li><a href="{{ route('admin.sales.list') }}"><i class="mdi mdi-cash-multiple"></i>Sales</a></li>
                                @endcan
                                @can('delivery_challan_view')
                                    <li><a href="{{route('admin.challan.list')}}"><i class="mdi mdi-truck-delivery"></i>Delivery</a></li>
                                @endcan
                                @can('invoice_view')
                                    <li><a href="{{route('admin.invoice.list')}}"><i class="mdi mdi-receipt"></i>Invoice</a></li>
                                @endcan
                                @can('purchase_view')
                                    <li><a href="{{route('admin.purchase.list')}}"><i class="mdi mdi-basket"></i>Purchase</a></li>
                                    <li><a href="{{route('admin.purchase.requirement.list')}}"><i class="mdi mdi-cart-plus"></i>Purchase Request</a></li>
                                @endcan
                                    <li><a href="{{route('admin.orders.index')}}"><i class="mdi mdi-web"></i>Website Orders</a></li>
                            </ul>
                        </li>
                    @endif
                    @if($user->hasAnyPermission(['quot_report_view', 'sales_report_view', 'invoice_report_view', 'sales_summary_report_view', 'product_wise_sales_report_view', 'salesman_wise_sales_report_view', 'stock_available_report_view', 'raw_material_pending_report_view', 'production_pending_report_view', 'stitching_pending_report_view', 'pressing_pending_report_view', 'packaging_pending_report_view', 'belt_production_report_view', 'roll_production_report_view', 'roll_material_consumption_report_view', 'roll_stock_report_view', 'belt_cutting_report_view', 'socks_missing_formula_report_view']))
                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect">
                                <i class="mdi mdi-chart-bar"></i>
                                <span> Reports </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul class="list-unstyled">
                                @can('quot_report_view')
                                    <li><a href="{{ route('admin.reports.quotation') }}"><i class="mdi mdi-file-document-box"></i>Quotation</a></li>
                                @endcan
                                @can('sales_report_view')
                                    <li><a href="{{ route('admin.reports.sales') }}"><i class="mdi mdi-chart-line"></i>Sales</a></li>
                                    <li><a href="{{ route('admin.reports.sales.out_of_stock_items') }}"><i class="mdi mdi-alert-circle"></i>Sales Out of stock Items</a></li>
                                @endcan
                                @can('invoice_report_view')
                                    <li><a href="{{ route('admin.reports.invoice') }}"><i class="mdi mdi-receipt"></i>Invoice</a></li>
                                @endcan
                                @can('sales_summary_report_view')
                                    <li><a href="{{ route('admin.reports.sales_summary') }}"><i class="mdi mdi-calendar-clock"></i>Sales Report - Daily/Monthly</a></li>
                                @endcan
                                @can('product_wise_sales_report_view')
                                    <li><a href="{{ route('admin.reports.product_wise_sales') }}"><i class="mdi mdi-chart-bar"></i>Product Wise Sales Report</a></li>
                                @endcan
                                @can('salesman_wise_sales_report_view')
                                    <li><a href="{{ route('admin.reports.salesman_wise_sales') }}"><i class="mdi mdi-account-multiple"></i>Sales-MAN Wise Sales Report</a></li>
                                @endcan
                                @can('stock_available_report_view')
                                    <li><a href="{{ route('admin.reports.stock_available') }}"><i class="mdi mdi-package-variant"></i>Product Wise Available Stock Report</a></li>
                                @endcan
                                @can('raw_material_pending_report_view')
                                    <li><a href="{{ route('admin.reports.raw_material_pending') }}"><i class="mdi mdi-alert"></i>RAW Material Required Pending Report</a></li>
                                @endcan
                                @can('production_pending_report_view')
                                    <li><a href="{{ route('admin.reports.production_pending') }}"><i class="mdi mdi-clock-alert"></i>Production Pending</a></li>
                                @endcan
                                @can('stitching_pending_report_view')
                                    <li><a href="{{ route('admin.reports.stitching_pending') }}"><i class="mdi mdi-needle"></i>Stitching Pending</a></li>
                                @endcan
                                @can('pressing_pending_report_view')
                                    <li><a href="{{ route('admin.reports.pressing_pending') }}"><i class="mdi mdi-hanger"></i>Press Pending</a></li>
                                @endcan
                                @can('packaging_pending_report_view')
                                    <li><a href="{{ route('admin.reports.packaging_pending') }}"><i class="mdi mdi-package"></i>Packing Pending</a></li>
                                @endcan
                                @can('roll_production_report_view')
                                    <li><a href="{{ route('admin.reports.roll_production') }}"><i class="mdi mdi-buffer"></i>Roll Production</a></li>
                                @endcan
                                @can('roll_material_consumption_report_view')
                                    <li><a href="{{ route('admin.reports.roll_material_consumption') }}"><i class="mdi mdi-scale-balance"></i>Roll Material Consumption</a></li>
                                @endcan
                                @can('roll_stock_report_view')
                                    <li><a href="{{ route('admin.belt_roll_production.register') }}"><i class="mdi mdi-barcode-scan"></i>Roll Stock</a></li>
                                @endcan
                                @can('belt_cutting_report_view')
                                    <li><a href="{{ route('admin.reports.belt_cutting') }}"><i class="mdi mdi-content-cut"></i>Belt Cutting</a></li>
                                @endcan
                                @can('belt_production_report_view')
                                    <li><a href="{{ route('admin.reports.belt_production') }}"><i class="mdi mdi-buffer"></i>Belt Production (Old)</a></li>
                                @endcan
                                @can('socks_missing_formula_report_view')
                                    <li><a href="{{ route('admin.reports.socks_missing_formula') }}"><i class="mdi mdi-alert-circle"></i>Socks Products Without Formula</a></li>
                                @endcan
                            </ul>
                        </li>
                    @endif
                    @if($user->hasAnyPermission(['product_view']))
                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect">
                                <i class="mdi mdi-dropbox"></i>
                                <span> Inventory </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul class="list-unstyled" style="display: none;">

                                <li><a href="{{route('admin.stock.status')}}"><i class="mdi mdi-clipboard-check"></i>Stock Status</a></li>
                                <li><a href="{{route('admin.stock.book')}}"><i class="mdi mdi-book-open-variant"></i>Stock Book</a></li>
                                @can('outward_stock_view')
                                    <li><a href="{{route('admin.outward.list')}}"><i class="mdi mdi-package-up"></i>Outward Stock</a></li>
                                @endcan
                                @can('product_view')
                                    <li><a href="{{ route('admin.product.list') }}"><i class="mdi mdi-shopping"></i>Product</a></li>
                                    {{--<li><a href="{{route('admin.bom.list')}}">Custom Product (BOM)</a></li>--}}
                                    <li><a href="{{ route('admin.category.list') }}"><i class="mdi mdi-folder"></i>Category</a></li>
                                    <li><a href="{{ route('admin.subcategory.list') }}"><i class="mdi mdi-folder-multiple"></i>Subcategory</a></li>
                                    <li><a href="{{ route('admin.uom.list') }}"><i class="mdi mdi-ruler"></i>Usage Unit</a></li>
                                    <li><a href="{{ route('admin.brand.list') }}"><i class="mdi mdi-tag"></i>Brand</a></li>
                                    <li><a href="{{ route('admin.material.list') }}"><i class="mdi mdi-texture"></i>Material</a></li>
                                    <li><a href="{{ route('admin.attribute.list') }}"><i class="mdi mdi-format-list-bulleted"></i>Attribute</a></li>
                                    <li><a href="{{ route('admin.variation.list') }}"><i class="mdi mdi-palette"></i>Variation</a></li>
                                    <li><a href="{{ route('admin.raw.material.list') }}"><i class="mdi mdi-cube-outline"></i>Raw Material</a></li>
                                @endcan

                            </ul>
                        </li>
                    @endif
                    @if($user->hasAnyPermission(['production_master_view','production_view', 'stitching_view', 'pressing_view', 'washing_view', 'packaging_view', 'formula_view']))
                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect">
                                <i class="mdi mdi-factory"></i>
                                <span> Production </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul class="list-unstyled" style="display: none;">
                                @can('production_master_view')
                                    <li><a href="{{ route('admin.production.dashboard') }}"><i class="mdi mdi-apps"></i>All Production Dashboard</a></li>
                                @endcan
                                @can('production_view')
                                    <li><a href="{{ route('admin.production.process') }}"><i class="mdi mdi-speedometer"></i>Production Dashboard</a></li>
                                @endcan
                                @can('stitching_view')
                                    <li><a href="{{ route('admin.production.stitching.dashboard') }}"><i class="mdi mdi-needle"></i>Stitching Dashboard</a></li>
                                @endcan
                                @can('pressing_view')
                                    <li><a href="{{ route('admin.production.pressing.dashboard') }}"><i class="mdi mdi-hanger"></i>Pressing Dashboard</a></li>
                                @endcan
                                @can('washing_view')
                                    <li><a href="{{ route('admin.production.washing.dashboard') }}"><i class="mdi mdi-water"></i>Washing Dashboard</a></li>
                                @endcan
                                @can('packaging_view')
                                    <li><a href="{{ route('admin.production.packaging.dashboard') }}"><i class="mdi mdi-package-variant-closed"></i>Packaging Dashboard</a></li>
                                @endcan
                                @can('formula_view')
                                    <li><a href="{{ route('admin.production.formula_list') }}"><i class="mdi mdi-flask"></i>Formula Master</a></li>
                                @endcan
                            </ul>
                        </li>
                    @endif
                    @if($user->hasAnyPermission(['belt_production_view','belt_roll_production_view','belt_cutting_view','roll_formula_view']))
                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect">
                                <i class="mdi mdi-buffer"></i>
                                <span> Belt Production </span>
                                <span class="menu-arrow"></span>
                            </a>
                            {{--
                                Ordered the way the floor works: set the masters up once,
                                then weave, then cut. The "Add ..." entries are gone - every
                                list screen already has its own Add New button - and the
                                superseded single-stage archive only appears while it still
                                has an unfinished batch in it.
                            --}}
                            <ul class="list-unstyled" style="display: none;">
                                <li class="menu-section-label">Masters</li>
                                <li><a href="{{ route('admin.niwar.list') }}"><i class="mdi mdi-tag-outline"></i>Niwar Code</a></li>
                                <li><a href="{{ route('admin.bukkal.list') }}"><i class="mdi mdi-barcode"></i>Bukkal Code</a></li>
                                <li><a href="{{ route('admin.belt.list') }}"><i class="mdi mdi-calculator"></i>Belt Costing</a></li>
                                @can('roll_formula_view')
                                    <li><a href="{{ route('admin.roll_formula.list') }}"><i class="mdi mdi-flask-empty-outline"></i>Roll Formula</a></li>
                                @endcan

                                <li class="menu-section-label">Production</li>
                                @can('belt_roll_production_view')
                                    <li><a href="{{ route('admin.belt_roll_production.list') }}"><i class="mdi mdi-format-list-bulleted"></i>Roll Production</a></li>
                                    <li><a href="{{ route('admin.belt_roll_production.register') }}"><i class="mdi mdi-barcode-scan"></i>Roll Stock Register</a></li>
                                @endcan
                                @can('belt_cutting_view')
                                    <li><a href="{{ route('admin.belt_cutting.list') }}"><i class="mdi mdi-content-cut"></i>Belt Cutting</a></li>
                                @endcan

                                @can('belt_production_view')
                                    @if(\App\Http\Controllers\BeltProductionController::hasOpenBatches())
                                        <li class="menu-section-label">Archive</li>
                                        <li><a href="{{ route('admin.belt_production.list') }}"><i class="mdi mdi-archive"></i>Belt Production (Old)</a></li>
                                    @endif
                                @endcan
                            </ul>
                        </li>
                    @endif
                    @if($user->hasAnyPermission(['term_view','gst_view', 'payment_terms_view', 'industry_view', 'customer_type_view', 'country_view', 'state_view', 'city_view']))
                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect">
                                <i class="mdi mdi-dots-horizontal"></i>
                                <span> Others </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul class="list-unstyled">
                                @can('term_view')
                                    <li><a href="{{route('admin.term.list')}}"><i class="mdi mdi-file"></i>Terms &amp; Condition</a></li>
                                @endcan
                                @can('gst_view')
                                    <li><a href="{{route('admin.gst.list')}}"><i class="mdi mdi-percent"></i>GST</a></li>
                                @endcan
                                @can('payment_terms_view')
                                    <li><a href="{{route('admin.payment_terms.list')}}"><i class="mdi mdi-file-find"></i>Payment Terms</a></li>
                                @endcan
                                @can('industry_view')
                                    <li><a href="{{ route('admin.industry.list') }}"><i class="mdi mdi-hospital-building"></i>Industry</a></li>
                                @endcan
                                @can('customer_type_view')
                                    <li><a href="{{ route('admin.type.list') }}"><i class="mdi mdi-account-box"></i>Customer Type</a></li>
                                @endcan
                                @can('country_view')
                                    <li><a href="{{route('admin.country.list')}}"><i class="mdi mdi-flag"></i>Country</a></li>
                                @endcan
                                @can('state_view')
                                    <li><a href="{{ route('admin.state.list') }}"><i class="mdi mdi-home"></i>State</a></li>
                                @endcan
                                @can('city_view')
                                    <li><a href="{{ route('admin.city.list') }}"><i class="mdi mdi-city"></i>City</a></li>
                                @endcan
                                    <li><a href="{{ route('admin.banners.index')}}"><i class="mdi mdi-image"></i>Banner</a></li>
                                    <li><a href="{{ route('admin.shipping.index')}}"><i class="mdi mdi-truck"></i>Shipping charge</a></li>
                            </ul>
                        </li>
                    @endcan
                    @can('machine_master')
                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect">
                                <i class="mdi mdi-engine"></i>
                                <span> Machine </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul class="list-unstyled" style="">
                                <li><a href="{{ url('machine/list') }}"><i class="mdi mdi-settings"></i>Machine Master</a></li>

                                <li><a href="{{ url('stitching-machine/list') }}"><i class="mdi mdi-needle"></i>Stitching Machine</a></li>

                                <li><a href="{{ url('pressing-machine/list') }}"><i class="mdi mdi-hanger"></i>Pressing Machine</a></li>

                                <li><a href="{{ url('washing-machine/list') }}"><i class="mdi mdi-water"></i>Washing Machine</a></li>

                                <li><a href="{{ url('packaging-machine/list') }}"><i class="mdi mdi-package-variant"></i>Packaging Machine</a></li>
                            </ul>
                        </li>
                    @endcan
                    @if($user->hasAnyPermission(['role_view', 'user_view']))
                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect">
                                <i class="mdi mdi-account-network"></i>
                                <span> User Management </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul class="list-unstyled">
                                @can('role_view')
                                    <li><a href="{{route('admin.roles.list')}}"><i class="mdi mdi-sitemap"></i>Roles</a></li>
                                @endcan
                                @can('user_view')
                                    <li><a href="{{route('admin.user.list')}}"><i class="mdi mdi-account"></i>User</a></li>
                                @endcan
                            </ul>
                        </li>
                    @endcan
                    @can('company_view')
                        <li>
                            <a href="{{ route('admin.company.list') }}" class="waves-effect">
                                <i class="mdi mdi-hospital-building"></i>
                                <span>Company Details </span></a></li>
                    @endcan
                </ul>
            </div>
            <div class="clearfix"></div>
            <div class="help-box">
                <h5 class="text-muted m-t-0">For Help ?</h5>
                <p class=""><span class="text-custom">Email:</span> <br/> saggyt19@gmail.com</p>
                <p class="m-b-0"><span class="text-custom">Call:</span> <br/> +91-8866368568</p>
            </div>
        </div>
        <!-- Sidebar -left -->

    </div>
    <!-- Left Sidebar End -->

    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
