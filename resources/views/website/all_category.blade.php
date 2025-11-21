@extends('website.template.layout')
@section('title', 'All Categories')

@section('content')

    <section class="pt-4 mb-4">
        <div class="container text-center">
            <div class="row">
                <div class="col-lg-6 text-center text-lg-left">
                    <h1 class="fw-600 h4">@lang('All Categories')</h1>
                </div>
                <div class="col-lg-6">
                    <ul class="breadcrumb bg-transparent p-0 justify-content-center justify-content-lg-end">
                        <li class="breadcrumb-item opacity-50">
                            <a class="text-reset" href="{{ route('website.home') }}">@lang('Home')</a>
                        </li>
                        <li class="text-dark fw-600 breadcrumb-item">@lang('All Categories')</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- ✅ Category Tree View -->
    <section class="mb-4">
        <div class="container">

            @foreach ($categories as $category)
                <div class="category-item border rounded mb-2">

                    <!-- ✅ Main Category -->
                    <div class="category-header d-flex justify-content-between align-items-center p-3 bg-light"
                         data-toggle="cat-{{ $category->id }}" style="cursor: pointer;">
                        <a href="{{ route('website.product.view', ['slugs' => $category->slug]) }}" class="text-reset">
                            📁 {{ $category->category_name }}
                        </a>
                        <i class="la la-angle-down rotate-icon" style="cursor: pointer;"></i>
                    </div>

                    <!-- ✅ Subcategory List -->
                    <div class="subcategory-container" id="cat-{{ $category->id }}" style="display: none; overflow: hidden;">

                        @foreach($category->subcategories as $sub)
                            <div class="subcategory-item py-2 pl-4 d-flex align-items-center justify-content-between"
                                 data-target="sub-{{ $sub->id }}" style="cursor: pointer;">

                                <a href="{{ route('website.product.view', ['slugs' => $category->slug, 'child_category' => $sub->slug]) }}" class="text-reset">
                                    📄 {{ $sub->subcategory_name }}
                                </a>

                                @if($sub->children && $sub->children->count())
                                    <i class="la la-angle-right small rotate-child" style="cursor: pointer;"></i>
                                @endif
                            </div>

                            <!-- ✅ 3rd Level Subcategories -->
                            @if($sub->children && $sub->children->count())
                                <div class="child-category pl-5" id="sub-{{ $sub->id }}" style="display: none;">
                                    @foreach($sub->children as $child3)
                                        <div class="py-1">
                                            └ <a href="{{ route('website.product.view', ['slugs' => $category->slug, 'child_category' => $child3->slug]) }}" class="text-muted">
                                                📌 {{ $child3->subcategory_name }}
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                        @endforeach
                    </div>
                </div>
            @endforeach

        </div>
    </section>

@endsection

<!-- ✅ CSS -->
@section('page-css')
    <style>
        .rotate-icon, .rotate-child { transition: 0.3s; }
        .rotated { transform: rotate(180deg); }
        .subcategory-item:hover, .category-header:hover { background: #f8f9fa; }
    </style>
@endsection

<!-- ✅ JavaScript -->
@section('page-javascript')
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            // ✅ 1. Expand ALL Main Categories on page load
            document.querySelectorAll('.subcategory-container').forEach(container => {
                slideDown(container);
                let header = document.querySelector(`[data-toggle="${container.id}"]`);
                if (header) {
                    header.querySelector('.rotate-icon')?.classList.add('rotated');
                }
            });

            // ✅ 2. Expand ALL Subcategories (Level 3) by default
            document.querySelectorAll('.child-category').forEach(child => {
                slideDown(child);
                let icon = document.querySelector(`[data-target="${child.id}"] .rotate-child`);
                icon?.classList.add('rotated');
            });

            // ✅ 3. Toggle only when arrow icon is clicked (Main Category)
            document.querySelectorAll('.category-header .rotate-icon').forEach(icon => {
                icon.addEventListener('click', function(e){
                    e.preventDefault();
                    e.stopPropagation();
                    let header = this.closest('.category-header');
                    let target = document.getElementById(header.dataset.toggle);

                    if (target.style.display === "none" || target.style.display === "") {
                        slideDown(target);
                        this.classList.add('rotated');
                    } else {
                        slideUp(target);
                        this.classList.remove('rotated');
                    }
                });
            });

            // ✅ 4. Toggle only when arrow icon is clicked (Subcategory Level)
            document.querySelectorAll('.subcategory-item .rotate-child').forEach(icon => {
                icon.addEventListener('click', function(e){
                    e.preventDefault();
                    e.stopPropagation();
                    let row = this.closest('.subcategory-item');
                    let target = document.getElementById(row.dataset.target);

                    if (target.style.display === "none" || target.style.display === "") {
                        slideDown(target);
                        this.classList.add('rotated');
                    } else {
                        slideUp(target);
                        this.classList.remove('rotated');
                    }
                });
            });
        });

        // ✅ Animation - Slide Down Effect
        function slideDown(element) {
            element.style.display = 'block';
            let height = element.scrollHeight + 'px';
            element.style.height = '0px';
            requestAnimationFrame(() => {
                element.style.transition = 'height 0.3s ease';
                element.style.height = height;
            });
            element.addEventListener('transitionend', function end() {
                element.style.height = 'auto';
                element.style.transition = '';
                element.removeEventListener('transitionend', end);
            });
        }

        // ✅ Animation - Slide Up Effect
        function slideUp(element) {
            let height = element.scrollHeight + 'px';
            element.style.height = height;
            requestAnimationFrame(() => {
                element.style.transition = 'height 0.3s ease';
                element.style.height = '0px';
            });
            element.addEventListener('transitionend', function end() {
                element.style.display = 'none';
                element.style.transition = '';
                element.removeEventListener('transitionend', end);
            });
        }
    </script>
@endsection
