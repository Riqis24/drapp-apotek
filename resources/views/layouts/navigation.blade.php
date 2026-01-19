<div class="sidebar-menu">
    <ul class="menu">
        @role(['Super Admin', 'Admin', 'Owner'])
            <li class="sidebar-item {{ Route::is('dashboard.index') ? 'active' : '' }}">
                <a href="{{ route('dashboard.index') }}" class="sidebar-link">
                    <i class="bi bi-grid-fill"></i>
                    <span>Dashboard</span>
                </a>
            </li>
        @endrole
        <li class="sidebar-item {{ Route::is('SalesMstr.cashier') ? 'active' : '' }}">
            <a href="{{ route('SalesMstr.cashier') }}" class="sidebar-link">
                <i class="bi bi-cash"></i>
                <span>POS KASIR</span>
            </a>
        </li>

        <li
            class="sidebar-item has-sub {{ Request::is('SalesMstr*', 'SrMstr*', 'ExpenseTransaction*', 'ArMstr*') ? 'active' : '' }}">
            <a href="#" class="sidebar-link">
                <i class="bi bi-coin"></i>
                <span>Penjualan</span>
            </a>
            <ul
                class="submenu {{ Request::is('SalesMstr*', 'SrMstr*', 'ExpenseTransaction*', 'ArMstr*') ? 'active' : '' }}">
                <li class="submenu-item {{ Route::is('SalesMstr.index') ? 'active' : '' }}">
                    <a href="{{ route('SalesMstr.index') }}" class="submenu-link">Invoice Penjualan</a>
                </li>
                <li class="submenu-item {{ Route::is('SrMstr.index') ? 'active' : '' }}">
                    <a href="{{ route('SrMstr.index') }}" class="submenu-link">Retur Penjualan</a>
                </li>
                <li class="submenu-item {{ Route::is('ExpenseTransaction.*') ? 'active' : '' }}">
                    <a href="{{ route('ExpenseTransaction.index') }}" class="submenu-link">Pengeluaran</a>
                </li>
                <li class="submenu-item {{ Route::is('ArMstr.*') ? 'active' : '' }}">
                    <a href="{{ route('ArMstr.index') }}" class="submenu-link">Piutang Usaha</a>
                </li>
            </ul>
        </li>
        @role(['Super Admin', 'Admin', 'Owner'])
            <li
                class="sidebar-item has-sub {{ Request::is('PurchaseOrder*', 'PrMstr*', 'BpbMstr*', 'ApMstr*') ? 'active' : '' }}">
                <a href="#" class="sidebar-link">
                    <i class="bi bi-cart"></i>
                    <span>Pembelian</span>
                </a>
                <ul class="submenu {{ Request::is('PurchaseOrder*', 'PrMstr*', 'BpbMstr*', 'ApMstr*') ? 'active' : '' }}">
                    <li class="submenu-item {{ Route::is('PurchaseOrder.*') ? 'active' : '' }}">
                        <a href="{{ route('PurchaseOrder.index') }}" class="submenu-link">Pesanan Pembelian</a>
                    </li>
                    <li class="submenu-item {{ Route::is('PrMstr.index') ? 'active' : '' }}">
                        <a href="{{ route('PrMstr.index') }}" class="submenu-link">Retur Pembelian</a>
                    </li>
                    <li class="submenu-item {{ Route::is('BpbMstr.*') ? 'active' : '' }}">
                        <a href="{{ route('BpbMstr.index') }}" class="submenu-link">Penerimaan Barang</a>
                    </li>
                    <li class="submenu-item {{ Route::is('ApMstr.index') ? 'active' : '' }}">
                        <a href="{{ route('ApMstr.index') }}" class="submenu-link">Utang Usaha</a>
                    </li>
                </ul>
            </li>
            <li class="sidebar-item has-sub {{ Request::is('TsMstr*', 'SaMstr*', 'SoMstr*') ? 'active' : '' }}">
                <a href="#" class="sidebar-link">
                    <i class="bi bi-box-seam"></i>
                    <span>Persediaan</span>
                </a>
                <ul class="submenu {{ Request::is('TsMstr*', 'SaMstr*', 'SoMstr*') ? 'active' : '' }}">
                    <li class="submenu-item {{ Route::is('TsMstr.*') ? 'active' : '' }}">
                        <a href="{{ route('TsMstr.index') }}" class="submenu-link">Pemindahan Barang</a>
                    </li>
                    <li class="submenu-item {{ Route::is('SaMstr.*') ? 'active' : '' }}">
                        <a href="{{ route('SaMstr.index') }}" class="submenu-link">Penyesuaian Persediaan</a>
                    </li>
                    <li class="submenu-item {{ Route::is('SoMstr.*') ? 'active' : '' }}">
                        <a href="{{ route('SoMstr.index') }}" class="submenu-link">Stock Opname</a>
                    </li>
                </ul>
            </li>
        @endrole
        <li
            class="sidebar-item has-sub {{ Request::is('StockTransaction*', 'Stock/*', 'FinancialRecord*', 'ApMstr/SuppStatement*', 'ApMstr/Aging*') ? 'active' : '' }}">
            <a href="#" class="sidebar-link">
                <i class="bi bi-journals"></i>
                <span>Report</span>
            </a>
            <ul
                class="submenu {{ Request::is('StockTransaction*', 'Stock/*', 'FinancialRecord*', 'ApMstr/SuppStatement*', 'ApMstr/Aging*') ? 'active' : '' }}">
                @role(['Super Admin', 'Admin', 'Owner'])
                    <li class="submenu-item {{ Route::is('ApMstr.SuppStatement') ? 'active' : '' }}">
                        <a href="{{ route('ApMstr.SuppStatement') }}" class="submenu-link">Lap. Rekening Pemasok</a>
                    </li>
                    <li class="submenu-item {{ Route::is('ApMstr.AgingHutang') ? 'active' : '' }}">
                        <a href="{{ route('ApMstr.AgingHutang') }}" class="submenu-link">Umur Hutang</a>
                    </li>
                @endrole
                <li class="submenu-item {{ Route::is('StockTransaction.*') ? 'active' : '' }}">
                    <a href="{{ route('StockTransaction.index') }}" class="submenu-link">Transaksi histori</a>
                </li>
                <li class="submenu-item {{ Route::is('Stock.index') ? 'active' : '' }}">
                    <a href="{{ route('Stock.index') }}" class="submenu-link">Stok Obat</a>
                </li>
                <li class="submenu-item {{ Route::is('StockTransaction.StockCard') ? 'active' : '' }}">
                    <a href="{{ route('StockTransaction.StockCard') }}" class="submenu-link">Kartu Stok</a>
                </li>
                <li class="submenu-item {{ Route::is('SummaryStockCard') ? 'active' : '' }}">
                    <a href="{{ route('SummaryStockCard') }}" class="submenu-link">kartu Stok (Rekap)</a>
                </li>
                @role(['Super Admin', 'Admin', 'Owner'])
                    <li class="submenu-item {{ Route::is('FinancialRecord.*') ? 'active' : '' }}">
                        <a href="{{ route('FinancialRecord.index') }}" class="submenu-link">Catatan Keuangan</a>
                    </li>
                @endrole

            </ul>
        </li>

        @role(['Super Admin', 'Owner'])
            <li
                class="sidebar-item has-sub {{ Request::is('Product*', 'Measurement*', 'CustMstr*', 'Supplier*', 'LocMstr*', 'Price*', 'Store*') ? 'active' : '' }}">
                <a href="#" class="sidebar-link">
                    <i class="bi bi-boxes"></i>
                    <span>Master Data</span>
                </a>
                <ul
                    class="submenu {{ Request::is('Product*', 'Measurement*', 'CustMstr*', 'Supplier*', 'LocMstr*', 'Price*', 'Store*') ? 'active' : '' }}">
                    <li class="submenu-item {{ Route::is('ProductMstr.*') ? 'active' : '' }}">
                        <a href="{{ route('ProductMstr.index') }}" class="submenu-link">Master Obat</a>
                    </li>
                    <li class="submenu-item {{ Route::is('ProductCat.*') ? 'active' : '' }}">
                        <a href="{{ route('ProductCat.index') }}" class="submenu-link">Master Category</a>
                    </li>
                    <li class="submenu-item {{ Route::is('MeasurementMstr.*') ? 'active' : '' }}">
                        <a href="{{ route('MeasurementMstr.index') }}" class="submenu-link">Master Satuan</a>
                    </li>
                    <li class="submenu-item {{ Route::is('CustMstr.*') ? 'active' : '' }}">
                        <a href="{{ route('CustMstr.index') }}" class="submenu-link">Master Pelanggan</a>
                    </li>
                    <li class="submenu-item {{ Route::is('SupplierMstr.*') ? 'active' : '' }}">
                        <a href="{{ route('SupplierMstr.index') }}" class="submenu-link">Master Pemasok</a>
                    </li>
                    <li class="submenu-item {{ Route::is('LocMstr.*') ? 'active' : '' }}">
                        <a href="{{ route('LocMstr.index') }}" class="submenu-link">Master Gudang</a>
                    </li>
                    <li class="submenu-item {{ Route::is('ProductPlacement.*') ? 'active' : '' }}">
                        <a href="{{ route('ProductPlacement.index') }}" class="submenu-link">Master Lokasi</a>
                    </li>
                    <li class="submenu-item {{ Route::is('PriceMstr.*') ? 'active' : '' }}">
                        <a href="{{ route('PriceMstr.index') }}" class="submenu-link">Master Harga</a>
                    </li>
                    <li class="submenu-item {{ Route::is('StoreProfile.*') ? 'active' : '' }}">
                        <a href="{{ route('StoreProfile.index') }}" class="submenu-link">Profile Apotek</a>
                    </li>
                </ul>
            </li>
        @endrole

        @role('Super Admin')
            <li
                class="sidebar-item has-sub {{ Request::is('UserMstr*', 'RoleMstr*', 'PermissionMstr*', 'settings*') ? 'active' : '' }}">
                <a href="#" class="sidebar-link">
                    <i class="bi bi-gear"></i>
                    <span>Config</span>
                </a>
                <ul
                    class="submenu {{ Request::is('UserMstr*', 'RoleMstr*', 'PermissionMstr*', 'settings*') ? 'active' : '' }}">
                    <li class="submenu-item {{ Route::is('UserMstr.*') ? 'active' : '' }}">
                        <a href="{{ route('UserMstr.index') }}" class="submenu-link">User Master</a>
                    </li>
                    <li class="submenu-item {{ Route::is('RoleMstr.*') ? 'active' : '' }}">
                        <a href="{{ route('RoleMstr.index') }}" class="submenu-link">Role Master</a>
                    </li>
                    <li class="submenu-item {{ Route::is('PermissionMstr.*') ? 'active' : '' }}">
                        <a href="{{ route('PermissionMstr.index') }}" class="submenu-link">Permissions</a>
                    </li>
                    <li class="submenu-item {{ Route::is('settings.index') ? 'active' : '' }}">
                        <a href="{{ route('settings.index') }}" class="submenu-link">Setting</a>
                    </li>
                </ul>
            </li>
        @endrole

    </ul>

    <div class="p-3 border-top w-100 mt-auto" style="text-align:center">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-danger w-100">
                <i class="bi bi-box-arrow-right"></i> Logout
            </button>
        </form>
    </div>
</div>
