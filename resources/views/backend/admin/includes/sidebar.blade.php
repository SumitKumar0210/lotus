<!-- Sidemenu -->
<div class="main-sidebar main-sidebar-sticky side-menu">
    <div class="sidemenu-logo">
        <a class="main-logo" href="{{ route('admin.dashboard') }}">
            <img src="{{ asset('backend/assets/img/brand/logo.png') }}" class="header-brand-img desktop-logo"
                alt="logo">
            <img src="{{ asset('backend/assets/img/brand/icon.png') }}" class="header-brand-img icon-logo" alt="logo">
            <img src="{{ asset('backend/assets/img/brand/logo-light.png') }}"
                class="header-brand-img desktop-logo theme-logo" alt="logo">
            <img src="{{ asset('backend/assets/img/brand/icon-light.png') }}"
                class="header-brand-img icon-logo theme-logo" alt="logo">
        </a>
    </div>
    <div class="main-sidebar-body">
        <ul class="nav">
            <li class="nav-label">Dashboard</li>


            <li class="nav-item {{ Request::path() === 'admin/dashboard' ? 'active show' : '' }}">
                <a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="fe fe-airplay"></i><span
                        class="sidemenu-label">Dashboard</span></a>
            </li>
            <li class="nav-label">Create Product</li>
            <li class="nav-item {{ Request::path() === 'admin/product/brand' ? 'active show' : '' }}">
                <a class="nav-link" href="{{ route('brand.index') }}"><i class="fe fe-tag"></i><span
                        class="sidemenu-label">Brand</span></a>
            </li>
            <li class="nav-item {{ Request::path() === 'admin/product/category' ? 'active show' : '' }}">
                <a class="nav-link" href="{{ route('category.index') }}"><i class="fe fe-grid"></i><span
                        class="sidemenu-label">Category</span></a>
            </li>
            <li class="nav-item {{ Request::path() === 'admin/product/product' ? 'active show' : '' }}">
                <a class="nav-link" href="{{ route('product.index') }}"><i class="fe fe-box"></i><span
                        class="sidemenu-label">Product</span></a>
            </li>


            <li class="nav-label">Branches</li>
            <li class="nav-item {{ Request::path() === 'admin/branch/branch' ? 'active show' : '' }}">
                <a class="nav-link" href="{{ route('branch.index') }}"><i class="fe fe-database"></i><span
                        class="sidemenu-label">Branch</span></a>
            </li>


            <li class="nav-label">Users</li>
            <li class="nav-item {{ Request::path() === 'admin/user/user' ? 'active show' : '' }}">
                <a class="nav-link" href="{{ route('user.index') }}"><i class="fe fe-user"></i><span
                        class="sidemenu-label">User</span></a>
            </li>


            <li class="nav-label">Purchase</li>
            <li class="nav-item {{ Request::path() === 'admin/purchase/purchase' ? 'active show' : '' }}">
                <a class="nav-link" href="{{ route('purchase.index') }}"><i class="fe fe-credit-card"></i><span
                        class="sidemenu-label">Purchase</span></a>
            </li>


            <li class="nav-label">Branch Transfer</li>
            <li
                class="nav-item {{ Request::path() === 'admin/branch-transfer/branch-transfer' ? 'active show' : '' }}">
                <a class="nav-link" href="{{ route('branch-transfer-admin.index') }}"><i class="fe fe-send"></i><span
                        class="sidemenu-label">Transfer Record</span></a>
            </li>


            <li
                class="nav-item {{ Request::path() === 'admin/branch-transfer/in-transit-admin' ? 'active show' : '' }}">
                <a class="nav-link" href="{{ route('in-transit-admin.index') }}"><i class="fe fe-truck"></i><span
                        class="sidemenu-label">In Transit</span></a>
            </li>



            <li class="nav-label">Estimate</li>
            <li class="nav-item {{ Request::path() === 'admin/estimate/estimate' ? 'active show' : '' }}">
                <a class="nav-link" href="{{ route('estimate.index') }}"><i class="fe fe-clipboard"></i><span
                        class="sidemenu-label">Estimate List</span></a>
            </li>


            <li
                class="nav-item {{ Request::path() === 'admin/estimate/getEstimateListOrderToMakeIndex' ? 'active show' : '' }}">
                <a class="nav-link" href="{{ route('estimate.getEstimateListOrderToMakeIndex') }}"><i
                        class="fe fe-clipboard"></i><span class="sidemenu-label">Orders</span></a>
            </li>


            <li class="nav-item {{ Request::path() === 'admin/estimate/due-payment' ? 'active show' : '' }}">
                <a class="nav-link" href="{{ route('due-payment.index') }}"><i class="fe fe-clock"></i><span
                        class="sidemenu-label">Due payments</span></a>
            </li>

            <li class="nav-label">Delivery</li>


            <li
                class="nav-item {{ Request::path() === 'admin/delivery/due-payment-due-delivery' ? 'active show' : '' }}">
                <a class="nav-link" href="{{ route('due-payment-due-delivery.index') }}"><i
                        class="fe fe-star"></i><span class="sidemenu-label">Pending Approvals</span></a>
            </li>



            <li class="nav-item {{ Request::path() === 'admin/delivery/due-delivery' ? 'active show' : '' }}">
                <a class="nav-link" href="{{ route('due-delivery.index') }}"><i class="fe fe-clock"></i><span
                        class="sidemenu-label">Pending Delivery</span></a>
            </li>

            {{-- <li class="nav-item {{Request::path() === 'admin/delivery/dues-delivery-list-otm-admin' ? 'active show' : '' }}">
                <a class="nav-link" href="{{route('dues-delivery-list-otm-admin.index')}}"><i
                        class="fe fe-truck"></i><span class="sidemenu-label">Dues Order To Make List</span></a>
            </li> --}}

            <li class="nav-item {{ Request::path() === 'admin/delivery/delivered' ? 'active show' : '' }}">
                <a class="nav-link" href="{{ route('delivered.index') }}"><i class="fe fe-truck"></i><span
                        class="sidemenu-label">Delivered</span></a>
            </li>

            <li class="nav-label">Sale</li>
            {{-- <li class="nav-item {{Request::path() === 'admin/sale/sale-list-admin-today' ? 'active show' : '' }}"> --}}
            {{-- <a class="nav-link" href="{{route('sale-list-admin-today.index')}}"><i class="fe fe-layout "></i><span --}}
            {{-- class="sidemenu-label">All Sale List Today's</span></a> --}}
            {{-- </li> --}}



            <li class="nav-item {{ Request::path() === 'admin/sale/sale-list-admin' ? 'active show' : '' }}">
                <a class="nav-link" href="{{ route('sale-list-admin.index') }}"><i class="fe fe-database "></i><span
                        class="sidemenu-label">DSR</span></a>
            </li>



            <li
                class="nav-item {{ Request::path() === 'admin/delivery/sale-returned-list-admin' ? 'active show' : '' }}">
                <a class="nav-link" href="{{ route('sale-returned-list-admin.index') }}"><i
                        class="fe fe-activity"></i>
                    <span class="sidemenu-label">Sale Returned List</span>
                </a>
            </li>

            {{-- <li class="nav-item {{Request::path() === 'admin/sale/sale-list-daily-admin' ? 'active show' : '' }}"> --}}
            {{-- <a class="nav-link" href="{{route('sale-list-daily-admin.index')}}"> --}}
            {{-- <i class="fe fe-git-commit"></i> --}}
            {{-- <span class="sidemenu-label">Daily Sale</span> --}}
            {{-- </a> --}}
            {{-- </li> --}}


            <li class="nav-label">Cashbook</li>
            <li class="nav-item {{Request::path() === 'admin/cashbook/cashbookList' ? 'active show' : '' }}">
                {{-- <a class="nav-link" href="javascript:void(0)" data-target="#modaldemo1" data-toggle="modal"><i
                        class="fe fe-book-open"></i>
                    <span class="sidemenu-label">Cashbook</span>
                </a> --}}
                <a class="nav-link" 
                href="{{route('admin.cashbook.cashbookList')}}"><i class="fe fe-book-open"></i>
                    <span class="sidemenu-label">Branch Report</span>
                </a>
            </li>
            <li class="nav-item {{Request::path() === 'admin/cashbook/admin-cashbookList' ? 'active show' : '' }}">
                <a class="nav-link" href="{{route('admin.cashbook.adminCashbook')}}"><i class="fe fe-book-open"></i>
                    <span class="sidemenu-label">Admin Report</span>
                </a>
            </li>
            <li class="nav-item {{Request::path() === 'admin/cashbook/admin-cashbook' ? 'active show' : '' }}">
                <a class="nav-link" href="{{route('admin.cashbook.cashbook')}}"><i class="fe fe-book-open"></i>
                    <span class="sidemenu-label">Cashbook</span>
                </a>
            </li>
            <li class="nav-item {{Request::path() === 'admin/cashbook/receiveCashList' ? 'active show' : '' }}">
                <a class="nav-link" href="{{route('admin.cashbook.receiveCashList')}}"><i class="fe fe-book-open"></i>
                    <span class="sidemenu-label">Receive Cash</span>
                </a>
            </li>
            <li class="nav-item {{Request::path() === 'admin/cashbook/creditNote' ? 'active show' : '' }}">
                <a class="nav-link" href="{{route('admin.cashbook.creditNote')}}"><i class="fe fe-file-text"></i>
                    <span class="sidemenu-label">Credit Note</span>
                </a>
            </li>
            <li class="nav-item {{Request::path() === 'admin/cashbook/expense' ? 'active show' : '' }}">
                <a class="nav-link" href="{{route('admin.cashbook.expense')}}"><i class="fe fe-file-text"></i>
                    <span class="sidemenu-label">Expense</span>
                </a>
            </li>


            <li class="nav-label">Reports</li>
            <li class="nav-item {{ Request::path() === 'admin/reports/customer-report' ? 'active show' : '' }}">
                <a class="nav-link" href="{{ route('customer-report.index') }}"><i class="fe fe-clipboard"></i><span
                        class="sidemenu-label">Customer Report</span></a>
            </li>


            {{-- <li class="nav-item {{Request::path() === 'admin/reports/customer-history-report' ? 'active show' : '' }}"> --}}
            {{-- <a class="nav-link" href="{{route('customer-history-report.index')}}"><i class="fe fe-clipboard"></i><span --}}
            {{-- class="sidemenu-label">Customer History Report</span></a> --}}
            {{-- </li> --}}

            <li class="nav-item {{ Request::path() === 'admin/reports/branch-stock-report' ? 'active show' : '' }}">
                <a class="nav-link" href="{{ route('branch-stock-report.index') }}"><i
                        class="fe fe-clipboard"></i><span class="sidemenu-label">Branch Stock Report</span></a>
            </li>


            {{-- <li class="nav-item {{Request::path() === 'admin/reports/estimate-report' ? 'active show' : '' }}"> --}}
            {{-- <a class="nav-link" href="{{route('estimate-report.index')}}"><i class="fe fe-clipboard"></i><span --}}
            {{-- class="sidemenu-label">Estimate Report</span></a> --}}
            {{-- </li> --}}

            {{-- <li class="nav-item {{Request::path() === 'admin/reports/warehouse-report' ? 'active show' : '' }}"> --}}
            {{-- <a class="nav-link" href="{{route('warehouse-report.index')}}"><i class="fe fe-clipboard"></i><span --}}
            {{-- class="sidemenu-label">Ware House Report</span></a> --}}
            {{-- </li> --}}



            <li class="nav-item {{ Request::path() === 'admin/reports/product-wise-report' ? 'active show' : '' }}">
                <a class="nav-link" href="{{ route('product-wise-report.index') }}"><i
                        class="fe fe-clipboard"></i><span class="sidemenu-label">Product History Report</span></a>
            </li>


            {{-- <li class="nav-item {{Request::path() === 'admin/reports/low-inventory-report' ? 'active show' : '' }}"> --}}
            {{-- <a class="nav-link" href="{{route('low-inventory-report.index')}}"><i class="fe fe-clipboard"></i><span --}}
            {{-- class="sidemenu-label">Low inventory Report</span></a> --}}
            {{-- </li> --}}

            <li class="nav-item {{ Request::path() === 'admin/reports/consolidate-report' ? 'active show' : '' }}">
                <a class="nav-link" href="{{ route('consolidate-report.index') }}"><i
                        class="fe fe-clipboard"></i><span class="sidemenu-label">Consolidated Report</span></a>
            </li>


        </ul>
    </div>
</div>
<!-- End Sidemenu -->
