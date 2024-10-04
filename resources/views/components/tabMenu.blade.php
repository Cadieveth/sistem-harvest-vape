<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link {{ request()->is('admin/asset') ? 'active' : '' }}" href="{{ route('admin.asset') }}" style="{{ request()->is('admin/asset') ? 'font-weight: bold;' : '' }}">
            Peralatan
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->is('admin/assetPerlengkapan') ? 'active' : '' }}" href="{{ route('admin.assetPerlengkapan') }}" style="{{ request()->is('admin/assetPerlengkapan') ? 'font-weight: bold;' : '' }}">
            Perlengkapan
        </a>
    </li>
</ul>
