{{--
    Reusable breadcrumb partial (v2).

    Usage from a page that extends master_v2 / table_master_v2:

        @section('breadcrumb')
            @include('admin.layout.partials.breadcrumb_v2', [
                'title' => 'Sales Orders',
                'items' => [
                    ['label' => 'Sales | Purchase', 'url' => null],
                    ['label' => 'Sales Orders', 'url' => route('admin.sales.list')],
                    ['label' => 'View', 'url' => null],
                ],
                'actions' => true, // optional slot below, see @section('breadcrumb-actions')
            ])
        @endsection

    Not consumed by any live page yet — module pages are migrated in Phase 3.
--}}
<div class="pagehead-v2">
    <div>
        <h1 class="pagehead-title">{{ $title ?? '' }}</h1>
        @if(!empty($items))
            <nav class="breadcrumb-v2" aria-label="breadcrumb">
                <a href="{{ url('admin') }}" class="breadcrumb-v2-item">
                    <i class="mdi mdi-home-outline"></i>
                </a>
                @foreach($items as $item)
                    <span class="breadcrumb-v2-sep">/</span>
                    @if(!empty($item['url']) && !$loop->last)
                        <a href="{{ $item['url'] }}" class="breadcrumb-v2-item">{{ $item['label'] }}</a>
                    @else
                        <span class="breadcrumb-v2-item breadcrumb-v2-current">{{ $item['label'] }}</span>
                    @endif
                @endforeach
            </nav>
        @endif
    </div>
    @hasSection('breadcrumb-actions')
        <div class="pagehead-actions">
            @yield('breadcrumb-actions')
        </div>
    @endif
</div>
