{{-- For submenu --}}
<ul class="menu-content">
    @foreach($menu as $submenu)
    <?php
            $submenuTranslation = "";
            if(isset($menu->i18n)){
                $submenuTranslation = $menu->i18n;
            }
        ?>
    <li class="{{ in_array(Route::current()->uri, $submenu->activeUrls ?? []) ? 'active' : '' }}">
        <a href="{{ $submenu->url ? route($submenu->url) : '' }}">
            <i class="{{ isset($submenu->icon) ? $submenu->icon : "" }}"></i>
            <span class="menu-title" data-i18n="{{ $submenuTranslation }}">{{ __('locale.'.$submenu->name) }}</span>
        </a>
        @if (isset($submenu->submenu))
        @include('panels/submenu', ['menu' => $submenu->submenu])
        @endif
    </li>
    @endforeach
</ul>
