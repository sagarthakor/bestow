<!DOCTYPE html>
{{--@if(\App\Models\Language::where('code', Session::get('locale', Config::get('app.locale')))->first()->rtl == 1)
<html dir="rtl" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@else--}}
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
{{--
@endif
--}}
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport"
          content="width=device-width, height=device-height, initial-scale=1.0, maximum-scale=1.0"/>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="title"
          content="{{ App\Library\Helper::get_setting('meta_title', 'seo_setting') ?? config('project.company') }}">
    <meta name="description"
          content="{{ App\Library\Helper::get_setting('meta_description', 'seo_setting') ?? config('project.company') }}">
    <meta name="keywords"
          content="{{ App\Library\Helper::get_setting('keywords', 'seo_setting') ?? config('project.company') }}">
    <meta name="author" content="{{ config('project.company') }}"/>
    <meta name="copyright" content="{{ config('project.company') }}"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <link rel="canonical" href="{{ env('APP_URL') }}"/>
    <meta name="robots" content="index, follow">
    <meta name=" theme-color" content="#fff">

    <link rel="icon" type="image/png" href="/logo/favicon.ico">
    <link rel="apple-touch-icon" href="/logo/favicon.ico">
    <title>@yield('title') | Welcome To {{ config('project.company') }}</title>

    @php
        $meta_image = asset((env('WEBSITE_CONTENT_IMAGE_URL').App\Library\Helper::get_setting('meta_image', 'seo_setting')));
    @endphp

        <!-- Schema.org markup for Google+ -->
    <meta itemprop="name"
          content="{{ App\Library\Helper::get_setting('meta_title', 'seo_setting') ?? config('project.company') }}">
    <meta itemprop="description"
          content="{{ App\Library\Helper::get_setting('meta_description', 'seo_setting') ?? config('project.company') }}">
    <meta itemprop="image" content="{{ $meta_image }}">

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="product">
    <meta name="twitter:site" content="@publisher_handle">
    <meta name="twitter:title"
          content="{{ App\Library\Helper::get_setting('meta_title', 'seo_setting') ?? config('project.company') }}">
    <meta name="twitter:description"
          content="{{ App\Library\Helper::get_setting('meta_description', 'seo_setting') ?? config('project.company') }}">
    <meta name="twitter:creator" content="@author_handle">
    <meta name="twitter:image" content="{{ $meta_image }}">

    <!-- Open Graph data -->
    <meta property="og:title"
          content="{{ App\Library\Helper::get_setting('meta_title', 'seo_setting') ?? config('project.company') }}"/>
    <meta property="og:type" content="website"/>
    <meta property="og:url" content="{{ route('website.home') }}"/>
    <meta property="og:image" content="{{ $meta_image }}"/>
    <meta property="og:description"
          content="{{ App\Library\Helper::get_setting('meta_description', 'seo_setting') ?? config('project.company') }}"/>
    <meta property="og:site_name" content="{{ env('APP_NAME') }}"/>
    <meta property="fb:app_id" content="{{-- {{ env('FACEBOOK_PIXEL_ID') }} --}}">

    <!-- CSS Files -->
    <script src="{{ asset('website-assets/js/jquery.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('website-assets/css/vendors.css') }}">
    <link rel="stylesheet" href="{{ asset('website-assets/css/aiz-core.css') }}">
    <link rel="stylesheet" href="{{ asset('website-assets/css/custom-style.css') }}">
    <link rel="stylesheet" href="{{ asset('/website-assets/css/style.css') }}">
    <!-- lightbox -->
    <link rel="stylesheet" href="{{ asset('website-assets/css/lightbox.min.css') }}">



    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    @if(env('PWA_ENABLE') == 1)
        @laravelPWA
    @endif

    <script>
        var AIZ = AIZ || {};
        AIZ.local = {
            nothing_selected: '@lang('Nothing selected')',
            nothing_found: '@lang('Nothing found')',
            choose_file: '@lang('Choose file')',
            file_selected: '@lang('File selected')',
            files_selected: '@lang('Files selected')',
            add_more_files: '@lang('Add more files')',
            adding_more_files: '@lang('Adding more files')',
            drop_files_here_paste_or: '@lang('Drop files here, paste or')',
            browse: '@lang('Browse')',
            upload_complete: '@lang('Upload complete')',
            upload_paused: '@lang('Upload paused')',
            resume_upload: '@lang('Resume upload')',
            pause_upload: '@lang('Pause upload')',
            retry_upload: '@lang('Retry upload')',
            cancel_upload: '@lang('Cancel upload')',
            uploading: '@lang('Uploading')',
            processing: '@lang('Processing')',
            complete: '@lang('Complete')',
            file: '@lang('File')',
            files: '@lang('Files')',
        }
    </script>

    <style>
        :root {
            --blue: #3490f3;
            --hov-blue: #2e7fd6;
            --soft-blue: rgba(0, 123, 255, 0.15);
            --secondary-base: #ffc519;
            --hov-secondary-base: #dbaa17;
            --soft-secondary-base: #ffc519;
            --gray: #9d9da6;
            --gray-dark: #8d8d8d;
            --secondary: #919199;
            --soft-secondary: rgba(145, 145, 153, 0.15);
            --success: #85b567;
            --soft-success: rgba(133, 181, 103, 0.15);
            --warning: #f3af3d;
            --soft-warning: rgba(243, 175, 61, 0.15);
            --light: #f5f5f5;
            --soft-light: #dfdfe6;
            --soft-white: #b5b5bf;
            --dark: #292933;
            --soft-dark: #1b1b28;
            --primary: #d43533;
            --hov-primary: #9d1b1a;
            --soft-primary: #9d1b1a
            ;
        }

        body {
            /*font-family: 'Open Sans', sans-serif;*/
            font-family: 'Public Sans', sans-serif;
            font-weight: 400;
        }

        #map {
            width: 100%;
            height: 250px;
        }

        #edit_map {
            width: 100%;
            height: 250px;
        }

        .pac-container {
            z-index: 100000;
        }
    </style>

    {{-- @if (get_setting('google_analytics') == 1) --}}
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ env('TRACKING_ID') }}"></script>

    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());
        gtag('config', '{{ env('TRACKING_ID') }}');
    </script>
    {{--  @endif --}}

    {{-- @if (get_setting('facebook_pixel') == 1) --}}
    <!-- Facebook Pixel Code -->
    {{-- <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
                'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '{{ env('FACEBOOK_PIXEL_ID') }}');
            fbq('track', 'PageView');
        </script>
        <noscript>
            <img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id={{ env('FACEBOOK_PIXEL_ID') }}&ev=PageView&noscript=1"/>
        </noscript> --}}
    <!-- End Facebook Pixel Code -->
    {{-- @endif --}}

    @yield('page-css')
    @yield('import-css')
</head>

<body>

<!-- aiz-main-wrapper -->
<div class="aiz-main-wrapper d-flex flex-column {{-- bg-white --}}">
    @php
        $user = auth()->user();
    @endphp

    @include('website.template.header')

    @yield('content')

    @include('website.template.footer')


    <!-- Floating Buttons -->
    @include('website.template.floating_buttons')

    <div class="aiz-refresh">
        <div class="aiz-refresh-content">
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>
    @yield('modal')


    {{--  @if (get_setting('facebook_chat') == 1) --}}
    {{-- <script type="text/javascript">
        window.fbAsyncInit = function() {
            FB.init({
              xfbml            : true,
              version          : 'v3.3'
          });
        };

        (function(d, s, id) {
          var js, fjs = d.getElementsByTagName(s)[0];
          if (d.getElementById(id)) return;
          js = d.createElement(s); js.id = id;
          js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js';
          fjs.parentNode.insertBefore(js, fjs);
      }(document, 'script', 'facebook-jssdk'));
  </script>
  <div id="fb-root"></div> --}}
    <!-- Your customer chat code -->
    {{-- <div class="fb-customerchat" attribution=setup_tool page_id="{{ env('FACEBOOK_PAGE_ID') }}"></div> --}}
    {{-- @endif --}}

    <!-- website popup -->
    @php
     //   $dynamic_popups = App\Models\Banner::whereType(App\Models\Banner::POPUP)->active()->orderBy('id', 'asc')->get();
    @endphp
    @if(Route::currentRouteName() == 'website.home')
      {{--  @foreach ($dynamic_popups as $key => $dynamic_popup)
            <div class="modal website-popup removable-session d-none"
                 data-key="website-popup-{{ $dynamic_popup->id }}" data-value="removed">
                <div class="absolute-full bg-black opacity-60"></div>
                <div class="modal-dialog modal-dialog-centered modal-dialog-zoom modal-md mx-4 mx-md-auto">
                    <div class="modal-content position-relative border-0 rounded-0">
                        <div class="aiz-editor-data">
                            <div class="d-block">
                                <img class="w-100" src="{{ env('POPUP_IMAGE_URL') . $dynamic_popup->image }}"
                                     alt="dynamic_popup">
                            </div>
                        </div>
                        --}}{{-- <div class="pb-5 pt-4 px-3 px-md-2rem">
                            <h1 class="fs-30 fw-700 text-dark">{{ $dynamic_popup->name }}</h1>
                            <p class="fs-14 fw-400 mt-3 mb-4">{{ $dynamic_popup->summary }}</p>
                            <a href="{{ $dynamic_popup->btn_link }}" class="btn btn-block mt-3 rounded-0 text-{{ $dynamic_popup->btn_text_color }}" style="background: {{ App\Library\Helper::get_setting('primary', 'base_color') ?? '#d43533' }};">
                                {{ $dynamic_popup->btn_text }}
                            </a>
                        </div> --}}{{--
                        <button
                            class="absolute-top-right bg-white shadow-lg btn btn-circle btn-icon mr-n3 mt-n3 set-session"
                            data-key="website-popup-{{ $dynamic_popup->id }}" data-value="removed"
                            data-toggle="remove-parent" data-parent=".website-popup">
                            <i class="la la-close fs-20"></i>
                        </button>
                    </div>
                </div>
            </div>
        @endforeach--}}
    @endif
    <script>
        $(document).ready(function () {
            AIZ.extra.showSessionPopup();
        });

        $('#search').on('keyup', function () {
            search();
        });

        $('#search').on('focus', function () {
            search();
        });

        function search() {
            var searchKey = $('#search').val();
            if (searchKey.length > 0) {
                $('body').addClass("typed-search-box-shown");

                $('.typed-search-box').removeClass('d-none');
                $('.search-preloader').removeClass('d-none');
                $.post('{{ route('website.search.ajax') }}', {
                    _token: AIZ.data.csrf,
                    search: searchKey
                }, function (data) {
                    if (data == '0') {
                        // $('.typed-search-box').addClass('d-none');
                        $('#search-content').html(null);
                        $('.typed-search-box .search-nothing').removeClass('d-none').html('Sorry, nothing found for <strong>"' + searchKey + '"</strong>');
                        $('.search-preloader').addClass('d-none');

                    } else {
                        $('.typed-search-box .search-nothing').addClass('d-none').html(null);
                        $('#search-content').html(data);
                        $('.search-preloader').addClass('d-none');
                    }
                });
            } else {
                $('.typed-search-box').addClass('d-none');
                $('body').removeClass("typed-search-box-shown");
            }
        }
    </script>

    <script>
        function showFloatingButtons() {
            document.querySelector('.floating-buttons-section').classList.toggle('show');
            ;
        }

    </script>

    <!-- SCRIPTS -->
    <script src="{{ asset('website-assets/js/vendors.js') }}"></script>
    <script src="{{ asset('website-assets/js/jquery.select2.min.js') }}"></script>

    <script src="{{ asset('website-assets/js/aiz-core.js') }}"></script>
    <script src="{{ asset('/website-assets/js/script.js') }}"></script>

    <!-- lightbox -->
    <script src="{{ asset('website-assets/js/lightbox-plus-jquery.min.js') }}"></script>

    <script src="{{ asset('/plugins/sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('/website-assets/plugins/vue/blockUI.js') }}"></script>
    <script src="{{ asset('/website-assets/plugins/lodash/lodash.js') }}"></script>
    <script src="{{ asset('/website-assets/plugins/axios/axios.min.js') }}"></script>
    <script
        src="/website-assets/plugins/vue/{{ env('APP_ENV') == 'local' ? 'vue.js' : 'vue.min.js' }}"></script>
    <script src="{{ asset('/website-assets/plugins/vue/component.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/js-cookie@3.0.0/dist/js.cookie.min.js"
            integrity="sha256-pUYbeWfQ0TisH2PabhAZLCzI8qGOJop0mEWjbJBcZLQ=" crossorigin="anonymous"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>


    <script type="text/javascript">
        var path = "{{ route('website.auto.complete') }}";
        $('input.typeahead').typeahead({
            source: function (query, process) {
                return $.get(path, {query: query}, function (data) {
                    return process(data);
                });
            }
        });
    </script>
    <script>
        @if(session('errors'))
        swal('Oops', '{{ session('errors')->first() }}', 'error')
        @elseif(session('error'))
        swal('Oops', '{{ session('error') }}', 'error')
        @elseif(session('success'))
        swal('Success', '{{ session('success') }}', 'success')
        @endif
    </script>
@yield('import-javascript')
@yield('page-javascript')
</body>
</html>
