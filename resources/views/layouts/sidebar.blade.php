<div class="border-right custom-sidebar" id="sidebar-wrapper">
          <div class="sidebar-heading custom-sidebar-heading text-center">
            <img src="{{ asset('assets/images/logo2-3.png') }}" width="170" />
          </div>
          <div class="list-group list-group-flush">
            @foreach ($userMenu as $menu)
              <a
                class="nav-link {{$menu->submenu == 0 ? '' : 'nav-link-cs'}} collapsed text-truncate list-group-item custom-sidebar list-group-item-action custom-active-color {{ (request()->is($menu->url.'*')) ? 'active custom-active-color' : '' }}"
                href="{{$menu->submenu == null ? route($menu->route) : "#admin$menu->menu_id"}}"
                data-toggle="{{$menu->submenu == 0 ? '' : 'collapse'}}"
                data-target="{{$menu->submenu == 0 ? $menu->route : "#admin$menu->menu_id"}}"
                >
                <span class="d-none d-sm-inline"></span>{{ $menu->menu}}</a
              >
              <div class="collapse" id="admin{{$menu->menu_id}}" aria-expanded="false">
                <ul class="flex-column pl-2 nav">
                  <li class="nav-item">
                    {{-- menmapilkan submenu berasarkan menu_id --}}
                    @php
                    $menu_id  = $menu->menu_id;
                    $submenus = $gF->userSubmenus($menu_id);
                    @endphp
                    @foreach($submenus as $submenu)
                       <a
                        href="{{ route($submenu->route)}}"
                        class="list-group-item  custom-sidebar list-group-item-action custom-active-color {{ (request()->is($submenu->url.'*')) ? 'active custom-active-color' : '' }}"
                      >
                        {{$submenu->name}}
                      </a>
                    @endforeach
                  </li>
                </ul>
              </div>
            @endforeach
           
            </a>
             <a class="list-group-item d-lg-none list-group-item-action" href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                  </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                    </form>
            </a>
          </div>
        </div>