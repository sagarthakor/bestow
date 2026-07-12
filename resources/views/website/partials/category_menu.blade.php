@php $categories = $categories ?? []; @endphp

@if(count($categories) > 0)
    <div class="category-menu">
        <ul>
            @foreach($categories as $category)
                @include('website.partials.category_item', ['item' => $category])
            @endforeach
        </ul>
    </div>
@endif
