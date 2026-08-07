<li class="has-submenu">

    {{-- MOBILE = direct; DESKTOP = submenu on hover --}}
    <a href="{{ route('website.product.view', ['category'=>$item->slug]) }}" class="menu-link">
        <span>{{ $item->category_name ?? $item->subcategory_name ?? $item->name }}</span>

        @if(isset($item->subcategories) && $item->subcategories->count() > 0)
            <i class="las la-angle-right"></i>
        @endif
    </a>

    {{-- DESKTOP SUBCATEGORY --}}
    @if(isset($item->subcategories) && $item->subcategories->count() > 0)
        <ul class="submenu">
            @foreach($item->subcategories as $sub)
                <li>
                    <a href="{{ route('website.product.view', ['child_category'=>$sub->slug]) }}">
                        {{ $sub->subcategory_name ?? $sub->category_name ?? $sub->name }}
                    </a>
                </li>
            @endforeach
        </ul>
    @endif

</li>
