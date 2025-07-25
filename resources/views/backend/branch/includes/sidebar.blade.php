<!-- Sidemenu -->
<div class="main-sidebar main-sidebar-sticky side-menu">
    <div class="sidemenu-logo">
        <a class="main-logo" href="{{ route('branch.dashboard') }}">
            <img src="{{ asset('backend/assets/img/logo.png') }}" class="header-brand-img desktop-logo" alt="logo">
        </a>
    </div>
    <div class="main-sidebar-body">
        <ul class="nav">
            <li class="nav-label">Dashboard</li>

            <li class="nav-item {{ Request::path() === 'branch/dashboard' ? 'active show' : '' }}">
                <a class="nav-link" href="{{ route('branch.dashboard') }}"><i class="fas fa-desktop"></i><span
                        class="sidemenu-label">Dashboard</span></a>
            </li>


            @can('create purchase')

                <li class="nav-label ">Purchase</li>
                <li
                    class="nav-item {{ Request::path() === 'branch/branch-purchase/branch-purchase' ? 'active show' : '' }}">
                    <a class="nav-link" href="{{ route('branch-purchase.index') }}"><i class="fe fe-file-text"></i><span
                            class="sidemenu-label">Purchase</span></a>
                </li>
                <li
                    class="nav-item {{ Request::path() === 'branch/branch-purchase/branch-last-purchase' ? 'active show' : '' }}">
                    <a class="nav-link" href="{{ route('branch-last-purchase.index') }}"><i class="fe fe-clock"></i><span
                            class="sidemenu-label">Last Purchase</span></a>
                </li>

                <li
                    class="nav-item {{ Request::path() === 'branch/branch-purchase/branch-all-purchase' ? 'active show' : '' }}">
                    <a class="nav-link" href="{{ route('branch.purchase.getBranchAllPurchaseList.index') }}">
                        <i class="fe fe-star"></i>
                        <span class="sidemenu-label">All Purchase</span>
                    </a>
                </li>
            @endcan




            @can('create product')
                <li class="nav-label">Create Product</li>
                <li class="nav-item {{ Request::path() === 'branch/product/branch-brand' ? 'active show' : '' }}">
                    <a class="nav-link" href="{{ route('branch-brand.index') }}"><i class="fe fe-tag"></i><span
                            class="sidemenu-label">Brand</span></a>
                </li>
                <li class="nav-item {{ Request::path() === 'branch/product/branch-category' ? 'active show' : '' }}">
                    <a class="nav-link" href="{{ route('branch-category.index') }}"><i class="fe fe-grid"></i><span
                            class="sidemenu-label">Category</span></a>
                </li>
                <li class="nav-item {{ Request::path() === 'branch/product/branch-product' ? 'active show' : '' }}">
                    <a class="nav-link" href="{{ route('branch-product.index') }}"><i class="fe fe-box"></i><span
                            class="sidemenu-label">Product</span></a>
                </li>
            @endcan



            <li class="nav-label ">Estimate</li>
            <li class="nav-item {{ Request::path() === 'branch/estimate/estimate-list' ? 'active show' : '' }}">
                <a class="nav-link" href="{{ route('estimate-list.index') }}"><i class="fe fe-file-text"></i><span
                        class="sidemenu-label">Create/View Estimate</span></a>
            </li>

            <li class="nav-item {{ Request::path() === 'branch/estimate/dues-estimate-list' ? 'active show' : '' }}">
                <a class="nav-link" href="{{ route('dues-estimate-list.index') }}"><i class="fe fe-clock"></i><span
                        class="sidemenu-label">Due Payments</span></a>
            </li>

            <li
                class="nav-item {{ Request::path() === 'branch/estimate/estimate-list-cancelled' ? 'active show' : '' }}">
                <a class="nav-link" href="{{ route('estimate-list-cancelled.index') }}"><i
                        class="fe fe-clock"></i><span class="sidemenu-label">Cancelled Estimate</span></a>
            </li>


            <li class="nav-label">Delivery</li>


            <li
                class="nav-item {{ Request::path() === 'branch/delivery/dues-delivery-list-new' ? 'active show' : '' }}">
                <a class="nav-link" href="{{ route('dues-delivery-list-new.index') }}"><i
                        class="fe fe-clock"></i><span class="sidemenu-label">Delivery & Challan</span></a>
            </li>


            {{-- <li class="nav-item {{ Request::path() === 'branch/delivery/dues-delivery-list' ? 'active show' : '' }}">
                <a class="nav-link" href="{{ route('dues-delivery-list.index') }}"><i class="fe fe-clock"></i><span
                        class="sidemenu-label">Dues Delivery List</span></a>
            </li>

            <li
                class="nav-item {{ Request::path() === 'branch/delivery/dues-delivery-list-otm' ? 'active show' : '' }}">
                <a class="nav-link" href="{{ route('dues-delivery-list-otm.index') }}"><i class="fe fe-truck"></i><span
                        class="sidemenu-label">Dues Order To Make List </span></a>
            </li> --}}

            <li class="nav-item {{ Request::path() === 'branch/delivery/delivered-list' ? 'active show' : '' }}">
                <a class="nav-link" href="{{ route('delivered-list.index') }}"><i class="fe fe-truck"></i><span
                        class="sidemenu-label">Delivered/Sale Return</span></a>
            </li>


            <li class="nav-label">Sale</li>
            <li class="nav-item {{ Request::path() === 'branch/sale/sale-list' ? 'active show' : '' }}">
                <a class="nav-link" href="{{ route('sale-list.index') }}"><i class="fe fe-database "></i>
                    <span class="sidemenu-label">DSR</span>
                </a>
            </li>
            <li class="nav-item {{ Request::path() === 'branch/sale/sale-returned-list' ? 'active show' : '' }}">
                <a class="nav-link" href="{{ route('sale-returned-list.index') }}">
                    <i class="fe fe-activity"></i><span class="sidemenu-label">Sale Returned</span>
                </a>
            </li>

            {{-- <li class="nav-item {{Request::path() === 'branch/sale/sale-list-daily' ? 'active show' : '' }}"> --}}
            {{-- <a class="nav-link" href="{{route('sale-list-daily.index')}}"> --}}
            {{-- <i class="fe fe-git-commit"></i> --}}
            {{-- <span class="sidemenu-label">Daily Sale</span> --}}
            {{-- </a> --}}
            {{-- </li> --}}


            <li class="nav-label">Branch Transfer</li>
            <li
                class="nav-item {{ Request::path() === 'branch/branch-transfer/branch-transfer' ? 'active show' : '' }}">
                <a class="nav-link" href="{{ route('branch-transfer.index') }}"><i class="fe fe-repeat"></i><span
                        class="sidemenu-label">Branch Transfer</span></a>
            </li>


            <li class="nav-item {{ Request::path() === 'branch/branch-transfer/in-transit' ? 'active show' : '' }}">
                <a class="nav-link" href="{{ route('in-transit.index') }}"><i class="fe fe-truck"></i><span
                        class="sidemenu-label">In Transit</span></a>
            </li>


            <li
                class="nav-item {{ Request::path() === 'branch/branch-transfer/transfer-record' ? 'active show' : '' }}">
                <a class="nav-link" href="{{ route('transfer-record.index') }}"><i class="fe fe-repeat"></i><span
                        class="sidemenu-label">Transfer record</span></a>
            </li>





            <li class="nav-label">Reports</li>
            <li class="nav-item {{ Request::path() === 'branch/reports/branch-stock' ? 'active show' : '' }}">
                <a class="nav-link" href="{{ route('branch-stock.index') }}"><i class="fe fe-box"></i><span
                        class="sidemenu-label">Branch Stock</span></a>
            </li>
            <li class="nav-item {{ Request::path() === 'branch/reports/consolidate' ? 'active show' : '' }}">
                <a class="nav-link" href="{{ route('consolidate.index') }}"><i class="fe fe-git-commit"></i><span
                        class="sidemenu-label">Consolidated</span></a>
            </li>
            <li class="nav-item {{ Request::path() === 'branch/reports/product-report' ? 'active show' : '' }}">
                <a class="nav-link" href="{{ route('product-report.index') }}"><i class="fe fe-file"></i><span
                        class="sidemenu-label">Product Report</span></a>
            </li>


        </ul>
    </div>
</div>
<!-- End Sidemenu -->
