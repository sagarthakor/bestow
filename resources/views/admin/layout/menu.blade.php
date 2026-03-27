<style>
    .topbar .topbar-left {
        background: #496ebf !important;
        float: left;
        text-align: center;
        height: 60px;
        position: relative;
        width: 250px;
        z-index: 1;
    }
    .navbar-default {
        background-color: #496ebf !important;
        border-radius: 0;
        border: none;
        margin-bottom: 0;
        padding: 0 20px;
        box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12);
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

                    <li class="has_sub">
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
                                    <li><a href="{{route('admin.vendor.list')}}"><i class="mdi mdi-account-box"></i>Vendor</a></li>
                                @endcan
                                @can('customer_contact_view')
                                    <li><a href="{{route('admin.customer.contact.list')}}"><i class="mdi mdi-content-save-all"></i>Customer Contact</a></li>
                                @endcan
                                @can('vendor_contact_view')
                                    <li><a href="{{route('admin.vendor.contact.list')}}"><i class="mdi mdi-content-save-all"></i>Vendor Contact</a></li>
                                @endcan
                                @can('vendor_contact_view')
                                    <li><a href="{{route('admin.salesman.list')}}"><i class="mdi mdi-content-save-all"></i>Salesman</a></li>
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
                                    <li><a href="{{ route('order_list') }}">Website Orders</a></li>
                                @endcan
                                @can('quotation_view')
                                    <li><a href="{{ route('admin.quotation.list') }}">Quotation</a></li>
                                @endcan
                                @can('sales_view')
                                    <li><a href="{{ route('admin.sales.list') }}">Sales</a></li>
                                @endcan
                                @can('delivery_challan_view')
                                    <li><a href="{{route('admin.challan.list')}}">Delivery</a></li>
                                @endcan
                                @can('invoice_view')
                                    <li><a href="{{route('admin.invoice.list')}}">Invoice</a></li>
                                @endcan
                                @can('purchase_view')
                                    <li><a href="{{route('admin.purchase.list')}}">Purchase</a></li>
                                    <li><a href="{{route('admin.purchase.requirement.list')}}">Purchase Request</a></li>
                                @endcan
                                    <li><a href="{{route('admin.orders.index')}}">Website Orders</a></li>
                            </ul>
                        </li>
                    @endif
                    @if($user->hasAnyPermission(['quot_report_view', 'sales_report_view', 'invoice_report_view']))
                        <li class="has_sub">
                            <a href="javascript:void(0);" class="waves-effect">
                                <i class="mdi mdi-cart"></i>
                                <span> Reports </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <ul class="list-unstyled">
                                @can('quot_report_view')
                                    <li><a href="{{ route('admin.reports.quotation') }}">Quotation</a></li>
                                @endcan
                                @can('sales_report_view')
                                    <li><a href="{{ route('admin.reports.sales') }}">Sales</a></li>
                                    <li><a href="{{ route('admin.reports.sales.out_of_stock_items') }}">Sales Out of stock Items</a></li>
                                @endcan
                                @can('invoice_report_view')
                                    <li><a href="{{ route('admin.reports.invoice') }}">Invoice</a></li>
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

                                <li><a href="{{route('admin.stock.status')}}">Stock Status</a></li>
                                <li><a href="{{route('admin.stock.book')}}">Stock Book</a></li>
                                @can('product_view')
                                    <li><a href="{{ route('admin.product.list') }}">Product</a></li>
                                    {{--<li><a href="{{route('admin.bom.list')}}">Custom Product (BOM)</a></li>--}}
                                    <li><a href="{{ route('admin.category.list') }}">Category</a></li>
                                    <li><a href="{{ route('admin.subcategory.list') }}">Subcategory</a></li>
                                    <li><a href="{{ route('admin.uom.list') }}">Usage Unit</a></li>
                                    <li><a href="{{ route('admin.brand.list') }}">Brand</a></li>
                                    <li><a href="{{ route('admin.material.list') }}">Material</a></li>
                                    <li><a href="{{ route('admin.attribute.list') }}">Attribute</a></li>
                                    <li><a href="{{ route('admin.variation.list') }}">Variation</a></li>
                                    <li><a href="{{ route('admin.raw.material.list') }}">Raw Material</a></li>
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
                                    <li><a href="{{ route('admin.production.dashboard') }}">All Production Dashboard</a></li>
                                @endcan
                                @can('production_view')
                                    <li><a href="{{ route('admin.production.process') }}">Production Dashboard</a></li>
                                @endcan
                                @can('stitching_view')
                                    <li><a href="{{ route('admin.production.stitching.dashboard') }}">Stitching Dashboard</a></li>
                                @endcan
                                @can('pressing_view')
                                    <li><a href="{{ route('admin.production.pressing.dashboard') }}">Pressing Dashboard</a></li>
                                @endcan
                                @can('washing_view')
                                    <li><a href="{{ route('admin.production.washing.dashboard') }}">Washing Dashboard</a></li>
                                @endcan
                                @can('packaging_view')
                                    <li><a href="{{ route('admin.production.packaging.dashboard') }}">Packaging Dashboard</a></li>
                                @endcan
                                @can('formula_view')
                                    <li><a href="{{ route('admin.production.formula_list') }}">Formula Master</a></li>
                                @endcan
                                <li>
                                    <a href="{{ route('admin.bukkal.list') }}">Bukkal Code</a>
                                </li>
                                    <li>
                                        <a href="{{ route('admin.niwar.list') }}">Niwar Code</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.belt.list') }}">Bukkal Costing</a>
                                    </li>
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
                                    <li><a href="{{ route('admin.banners.index')}}"><i class="mdi mdi-city"></i>Banner</a></li>
                                    <li><a href="{{ route('admin.shipping.index')}}"><i class="mdi mdi-city"></i>Shipping charge</a></li>
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
                                <li><a href="{{ url('machine/list') }}">Machine Master</a></li>

                                <li><a href="{{ url('stitching-machine/list') }}">Stitching Machine</a></li>

                                <li><a href="{{ url('pressing-machine/list') }}">Pressing Machine</a></li>

                                <li><a href="{{ url('washing-machine/list') }}">Washing Machine</a></li>

                                <li><a href="{{ url('packaging-machine/list') }}">Packaging Machine</a></li>
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
                        <li class="has_sub">
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
