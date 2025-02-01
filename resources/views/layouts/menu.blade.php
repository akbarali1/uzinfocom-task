<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('welcome') }}" target="_blank">
           <span class="app-brand-text demo menu-text fw-bold ms-2">
                {{ auth()->user()->name }}
            </span>
            <span class="app-brand-text menu-text fw-bold ms-2 d-block mt-2">
               {{ auth()->user()->getRoleNames()->first() }}
            </span>
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>
    <div class="menu-inner-shadow"></div>
    <li class="menu-header small text-uppercase"><span class="menu-header-text">@lang('menu.documents')</span></li>
    <li class="menu-item">
        @can('document.create')
            <a href="{{ route('document.create') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-collection"></i>
                @lang('menu.document.create')
            </a>
        @endcan
        @can('document.upload')
            <a href="{{ route('document.upload') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-collection"></i>
                @lang('menu.document.upload')
            </a>
        @endcan
        @can('document.view.own')
            <a href="{{ route('document.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-collection"></i>
                @lang('menu.document.list')
            </a>
        @endcan
    </li>
    <ul class="menu-inner py-1">
        @role('admin')
        <li class="menu-header small text-uppercase"><span class="menu-header-text">@lang('menu.settings')</span></li>
        @if (Route::has('user.index'))
            <li class="menu-item">
                <a href="{{ route('user.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-collection"></i>
                    @lang('form.users')
                </a>
            </li>
        @endif
        @endrole
    </ul>
</aside>
