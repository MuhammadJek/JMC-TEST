<!-- partial:./assets/partials/_sidebar.html -->
<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">

        @if (Auth::user()->role == 'operator')
            <li class="nav-item {{ Route::is('barang*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('barang.index') }}">
                    <i class="mdi mdi-format-vertical-align-bottom menu-icon"></i>
                    <span class="menu-title">Barang Masuk</span>
                </a>
            </li>
        @endif
        {{-- {{ Auth::user()->role }} --}}
        @if (Auth::user()->role == 'admin')
            <li class="nav-item {{ Route::is('barang*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('barang.index') }}">
                    <i class="mdi mdi-format-vertical-align-bottom menu-icon"></i>
                    <span class="menu-title">Barang Masuk</span>
                </a>
            </li>
            <li class="nav-item {{ Route::is('category*') || Route::is('subcategory*') ? 'active' : '' }}">
                <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false"
                    aria-controls="ui-basic">
                    <i class="mdi mdi-bookmark-outline menu-icon"></i>
                    <span class="menu-title">Master Data</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="ui-basic">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item {{ Route::is('category*') ? 'active' : '' }}"> <a class="nav-link"
                                href="{{ Route('category.index') }}">Kategori</a></li>
                        <li class="nav-item  {{ Route::is('subcategory*') ? 'active' : '' }}"> <a class="nav-link"
                                href="{{ route('subcategory.index') }}">SubKategori</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::is('users*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                    <i class="mdi mdi-account-multiple-outline menu-icon"></i>
                    <span class="menu-title">Manejemen User</span>
                </a>
            </li>
        @endif
    </ul>
</nav>
