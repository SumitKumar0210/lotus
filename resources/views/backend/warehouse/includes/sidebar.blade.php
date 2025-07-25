<!-- Sidemenu -->
<div class="main-sidebar main-sidebar-sticky side-menu">
    <div class="sidemenu-logo">
        <a class="main-logo" href="{{ route('warehouse.dashboard') }}">
            <img src="{{ asset('backend/assets/img/logo.png') }}" class="header-brand-img desktop-logo" alt="logo">
        </a>
    </div>
    <div class="main-sidebar-body">
        <ul class="nav">
            <li class="nav-label">Dashboard</li>

            <li class="nav-item {{ Request::path() === 'warehouse/dashboard' ? 'active show' : '' }}">
                <a class="nav-link" href="{{ route('warehouse.dashboard') }}"><i class="fas fa-desktop"></i><span
                        class="sidemenu-label">Dashboard</span></a>
            </li>



            <li class="nav-label ">Purchase</li>
            <li
                class="nav-item {{ Request::path() === 'warehouse/purchase/purchase-warehouse' ? 'active show' : '' }}">
                <a class="nav-link" href="{{ route('purchase-warehouse.index') }}"><i
                        class="fe fe-file-text"></i><span class="sidemenu-label">Purchase</span></a>
            </li>
            <li
                class="nav-item {{ Request::path() === 'warehouse/purchase/warehouse-last-purchase-list' ? 'active show' : '' }}">
                <a class="nav-link" href="{{ route('warehouse-last-purchase.index') }}"><i
                        class="fe fe-clock"></i><span class="sidemenu-label">Last Purchase</span></a>
            </li>

            <li
                class="nav-item {{ Request::path() === 'warehouse/purchase/warehouse-all-purchase' ? 'active show' : '' }}">
                <a class="nav-link" href="{{ route('warehouse.purchase.getWarehouseAllPurchaseList.index') }}">
                    <i class="fe fe-star"></i>
                    <span class="sidemenu-label">All Purchase</span>
                </a>
            </li>



            <li class="nav-label">Create Product</li>
            <li class="nav-item {{ Request::path() === 'warehouse/product/warehouse-brand' ? 'active show' : '' }}">
                <a class="nav-link" href="{{ route('warehouse-brand.index') }}"><i
                        class="fe fe-tag"></i><span class="sidemenu-label">Brand</span></a>
            </li>
            <li
                class="nav-item {{ Request::path() === 'warehouse/product/warehouse-category' ? 'active show' : '' }}">
                <a class="nav-link" href="{{ route('warehouse-category.index') }}"><i
                        class="fe fe-grid"></i><span class="sidemenu-label">Category</span></a>
            </li>
            <li
                class="nav-item {{ Request::path() === 'warehouse/product/warehouse-product' ? 'active show' : '' }}">
                <a class="nav-link" href="{{ route('warehouse-product.index') }}"><i
                        class="fe fe-box"></i><span class="sidemenu-label">Product</span></a>
            </li>




            <li class="nav-label">Branch Transfer</li>
            <li
                class="nav-item {{ Request::path() === 'warehouse/branch-transfer/branch-transfer-warehouse' ? 'active show' : '' }}">
                <a class="nav-link" href="{{ route('branch-transfer-warehouse.index') }}"><i
                        class="fe fe-repeat"></i><span class="sidemenu-label">Branch Transfer</span></a>
            </li>


            <li
                class="nav-item {{ Request::path() === 'warehouse/branch-transfer/in-transit-warehouse' ? 'active show' : '' }}">
                <a class="nav-link" href="{{ route('in-transit-warehouse.index') }}"><i
                        class="fe fe-truck"></i><span class="sidemenu-label">In Transit</span></a>
            </li>

            <li
                class="nav-item {{ Request::path() === 'warehouse/branch-transfer/transfer-record-warehouse' ? 'active show' : '' }}">
                <a class="nav-link" href="{{ route('transfer-record-warehouse.index') }}"><i
                        class="fe fe-repeat"></i><span class="sidemenu-label">Transfer record</span></a>
            </li>



            <li class="nav-label">Reports</li>
            <li class="nav-item {{ Request::path() === 'warehouse/reports/stock-warehouse' ? 'active show' : '' }}">
                <a class="nav-link" href="{{ route('stock-warehouse.index') }}"><i
                        class="fe fe-box"></i><span class="sidemenu-label">Stock</span></a>
            </li>
            <li
                class="nav-item {{ Request::path() === 'warehouse/reports/consolidate-warehouse' ? 'active show' : '' }}">
                <a class="nav-link" href="{{ route('consolidate-warehouse.index') }}"><i
                        class="fe fe-git-commit"></i><span class="sidemenu-label">Consolidated</span></a>
            </li>

        </ul>
    </div>
</div>
<!-- End Sidemenu -->
