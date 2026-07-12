<div class="aiz-user-sidenav-wrap position-relative z-1 rounded-0">
    <div class="aiz-user-sidenav overflow-auto c-scrollbar-light px-4 pb-4">
        <!-- Close button -->
        <div class="d-xl-none">
            <button class="btn btn-sm p-2 " data-toggle="class-toggle" data-backdrop="static"
            data-target=".aiz-mobile-side-nav" data-same=".mobile-side-nav-thumb">
            <i class="las la-times la-2x"></i>
        </button>
    </div>
    <!-- Customer info -->
    <div class="p-4 text-center mb-4 border-bottom position-relative">
        <!-- Image -->
        <span class="avatar avatar-md mb-3">
            @if ($user->avatar_original != null)
            <img src="{{ $user->info->image ? env('USER_PROFILE_IMAGE_URL').$user->info->image : '/assets/images/svg/user-tie.svg' }}"
            onerror="this.onerror=null;this.src='{{ asset('website-assets/img/avatar-place.png') }}';">
            @else
            <img src="{{ asset('website-assets/img/avatar-place.png') }}" class="image rounded-circle"
            onerror="this.onerror=null;this.src='{{ asset('website-assets/img/avatar-place.png') }}';">
            @endif
        </span>
        <!-- Name -->
        <h4 class="h5 fs-14 mb-1 fw-700 text-dark">{{ $user->name }}</h4>
        <!-- Phone -->
        @if ($user->phone != null)
        <div class="text-truncate opacity-60 fs-12">{{ $user->phone }}</div>
        <!-- Email -->
        @else
        <div class="text-truncate opacity-60 fs-12">{{ $user->email }}</div>
        @endif
    </div>

    <!-- Menus -->
    <div class="sidemnenu">
        <ul class="aiz-side-nav-list mb-3 pb-3 border-bottom" data-toggle="aiz-side-menu">

            <!-- Dashboard -->
            <li class="aiz-side-nav-item">
                <a href="{{ route('website.home') }}" class="aiz-side-nav-link {{ \App\Library\Helper::areActiveRoutes(['dashboard']) }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                        <g id="Group_24768" data-name="Group 24768" transform="translate(3495.144 -602)">
                          <path id="Path_2916" data-name="Path 2916" d="M15.3,5.4,9.561.481A2,2,0,0,0,8.26,0H7.74a2,2,0,0,0-1.3.481L.7,5.4A2,2,0,0,0,0,6.92V14a2,2,0,0,0,2,2H14a2,2,0,0,0,2-2V6.92A2,2,0,0,0,15.3,5.4M10,15H6V9A1,1,0,0,1,7,8H9a1,1,0,0,1,1,1Zm5-1a1,1,0,0,1-1,1H11V9A2,2,0,0,0,9,7H7A2,2,0,0,0,5,9v6H2a1,1,0,0,1-1-1V6.92a1,1,0,0,1,.349-.76l5.74-4.92A1,1,0,0,1,7.74,1h.52a1,1,0,0,1,.651.24l5.74,4.92A1,1,0,0,1,15,6.92Z" transform="translate(-3495.144 602)" fill="#b5b5bf"/>
                      </g>
                  </svg>
                  <span class="aiz-side-nav-text ml-3">@lang(('Dashboard'))</span>
              </a>
          </li>

          <!-- Wishlist -->
          <li class="aiz-side-nav-item">
            <a href="{{-- {{ route('wishlists.index') }} --}}"
            class="aiz-side-nav-link {{-- {{ \App\Library\Helper::areActiveRoutes(['wishlists.index']) }} --}}">
            <svg id="Group_8116" data-name="Group 8116" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16" height="14" viewBox="0 0 16 14">
                <defs>
                    <clipPath id="clip-path">
                        <rect id="Rectangle_1391" data-name="Rectangle 1391" width="16" height="14" fill="#b5b5bf"/>
                    </clipPath>
                </defs>
                <g id="Group_8115" data-name="Group 8115" clip-path="url(#clip-path)">
                    <path id="Path_2981" data-name="Path 2981" d="M14.682,1.318a4.5,4.5,0,0,0-6.364,0L8,1.636l-.318-.318A4.5,4.5,0,0,0,1.318,7.682l6.046,6.054a.9.9,0,0,0,1.273,0l6.045-6.054a4.5,4.5,0,0,0,0-6.364m-.707,5.657L8,12.959,2.025,6.975a3.5,3.5,0,0,1,4.95-4.95l.389.389a.9.9,0,0,0,1.273,0l.388-.389a3.5,3.5,0,0,1,4.95,4.95" transform="translate(0 0)" fill="#b5b5bf"/>
                </g>
            </svg>
            <span class="aiz-side-nav-text ml-3">@lang('Wishlist')</span>
        </a>
    </li>

    <!-- Compare -->
    <li class="aiz-side-nav-item">
        <a href="{{-- {{ route('compare') }} --}}" class="aiz-side-nav-link {{-- {{ \App\Library\Helper::areActiveRoutes(['compare']) }} --}}">
            <svg id="Group_22071" data-name="Group 22071" xmlns="http://www.w3.org/2000/svg" width="14.6" height="16" viewBox="0 0 14.6 16">
                <g id="LWPOLYLINE" transform="translate(0.158)">
                    <path id="Path_25677" data-name="Path 25677" d="M304.755,426.408v-2.032a.5.5,0,1,1,.993,0v3.239a.5.5,0,0,1-.5.5h-3.216a.5.5,0,0,1,0-1h2.006a6.924,6.924,0,0,0-11.8,1,.5.5,0,0,1-.666.221.5.5,0,0,1-.219-.672,7.913,7.913,0,0,1,13.4-1.256Z" transform="translate(-291.306 -423.268)" fill="#b5b5bf"/>
                </g>
                <g id="LWPOLYLINE-2" data-name="LWPOLYLINE" transform="translate(0 10.879)">
                    <path id="Path_25678" data-name="Path 25678" d="M292.141,414.371V416.4a.5.5,0,1,1-.993,0v-3.238a.5.5,0,0,1,.5-.5h3.216a.5.5,0,0,1,0,1h-2.006a6.924,6.924,0,0,0,11.8-1,.493.493,0,0,1,.666-.221.5.5,0,0,1,.219.671,7.913,7.913,0,0,1-13.4,1.256Z" transform="translate(-291.148 -412.39)" fill="#b5b5bf"/>
                </g>
            </svg>
            <span class="aiz-side-nav-text ml-3">@lang('Compare')</span>
        </a>
    </li>

    <!-- My Wallet -->
    {{-- @if (get_setting('wallet_system') == 1) --}}
    <li class="aiz-side-nav-item">
        <a href="{{-- {{ route('wallet.index') }} --}}"
        class="aiz-side-nav-link {{-- {{ \App\Library\Helper::areActiveRoutes(['wallet.index']) }} --}}">
        <svg id="Group_8103" data-name="Group 8103" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16" height="16" viewBox="0 0 16 16">
            <defs>
                <clipPath id="clip-path">
                    <rect id="Rectangle_1386" data-name="Rectangle 1386" width="16" height="16" fill="#b5b5bf"/>
                </clipPath>
            </defs>
            <g id="Group_8102" data-name="Group 8102" clip-path="url(#clip-path)">
                <path id="Path_2936" data-name="Path 2936" d="M13.5,4H13V2.5A2.5,2.5,0,0,0,10.5,0h-8A2.5,2.5,0,0,0,0,2.5v11A2.5,2.5,0,0,0,2.5,16h11A2.5,2.5,0,0,0,16,13.5v-7A2.5,2.5,0,0,0,13.5,4M2.5,1h8A1.5,1.5,0,0,1,12,2.5V4H2.5a1.5,1.5,0,0,1,0-3M15,11H10a1,1,0,0,1,0-2h5Zm0-3H10a2,2,0,0,0,0,4h5v1.5A1.5,1.5,0,0,1,13.5,15H2.5A1.5,1.5,0,0,1,1,13.5v-9A2.5,2.5,0,0,0,2.5,5h11A1.5,1.5,0,0,1,15,6.5Z" fill="#b5b5bf"/>
            </g>
        </svg>
        <span class="aiz-side-nav-text ml-3">@lang('My Wallet')</span>
    </a>
</li>
{{-- @endif --}}

{{-- @php
$support_ticket = DB::table('tickets')
->where('client_viewed', 0)
->where('user_id', Auth::user()->id)
->count();
@endphp --}}

<!-- Support Ticket -->
<li class="aiz-side-nav-item">
    <a href="{{-- {{ route('support_ticket.index') }} --}}"
    class="aiz-side-nav-link {{-- {{ \App\Library\Helper::areActiveRoutes(['support_ticket.index', 'support_ticket.show']) }} --}}">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16.001" viewBox="0 0 16 16.001">
        <g id="Group_24764" data-name="Group 24764" transform="translate(-316 -1066)">
            <path id="Subtraction_184" data-name="Subtraction 184" d="M16427.109,902H16420a8.015,8.015,0,1,1,8-8,8.278,8.278,0,0,1-1.422,4.535l1.244,2.132a.81.81,0,0,1,0,.891A.791.791,0,0,1,16427.109,902ZM16420,887a7,7,0,1,0,0,14h6.283c.275,0,.414,0,.549-.111s-.209-.574-.34-.748l0,0-.018-.022-1.064-1.6A6.829,6.829,0,0,0,16427,894a6.964,6.964,0,0,0-7-7Z" transform="translate(-16096 180)" fill="#b5b5bf"/>
            <path id="Union_12" data-name="Union 12" d="M16414,895a1,1,0,1,1,1,1A1,1,0,0,1,16414,895Zm.5-2.5V891h.5a2,2,0,1,0-2-2h-1a3,3,0,1,1,3.5,2.958v.54a.5.5,0,1,1-1,0Zm-2.5-3.5h1a.5.5,0,1,1-1,0Z" transform="translate(-16090.998 183.001)" fill="#b5b5bf"/>
        </g>
    </svg>
    <span class="aiz-side-nav-text ml-3">@lang('Support Ticket')</span>
    {{-- @if ($support_ticket > 0)
    <span class="badge badge-inline badge-success">{{ $support_ticket }}</span>
    @endif --}}
</a>
</li>

<!-- Manage Profile -->
<li class="aiz-side-nav-item">
    <a href="{{-- {{ route('profile') }} --}}" class="aiz-side-nav-link {{-- {{ \App\Library\Helper::areActiveRoutes(['profile']) }} --}}">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
            <g id="Group_8094" data-name="Group 8094" transform="translate(3176 -602)">
              <path id="Path_2924" data-name="Path 2924" d="M331.144,0a4,4,0,1,0,4,4,4,4,0,0,0-4-4m0,7a3,3,0,1,1,3-3,3,3,0,0,1-3,3" transform="translate(-3499.144 602)" fill="#b5b5bf"/>
              <path id="Path_2925" data-name="Path 2925" d="M332.144,20h-10a3,3,0,0,0,0,6h10a3,3,0,0,0,0-6m0,5h-10a2,2,0,0,1,0-4h10a2,2,0,0,1,0,4" transform="translate(-3495.144 592)" fill="#b5b5bf"/>
          </g>
      </svg>
      <span class="aiz-side-nav-text ml-3">@lang('Manage Profile')</span>
  </a>
</li>

</ul>

<!-- logout -->
<a href="{{ route('user.logout') }}" class="btn btn-primary btn-block fs-14 fw-700 mb-5 mb-md-0" style="border-radius: 25px;">@lang('Sign Out')</a>
</div>

</div>
</div>
