<div class="border-right custom-sidebar" id="sidebar-wrapper">
          <div class="sidebar-heading custom-sidebar-heading text-center">
            <img src="{{ asset('assets/images/logo2-3.png') }}" width="170" />
          </div>
          <div class="list-group list-group-flush">
            {{-- <a
              class="nav-link nav-link-cs collapsed text-truncate list-group-item custom-sidebar list-group-item-action custom-active-color {{ (request()->is('admin/admincontrol/district*')) ? 'active custom-active-color' : '' }}"
              href="#admin"
              data-toggle="collapse"
              data-target="#admin"
              >
              <span class="d-none d-sm-inline"></span>Anggota</a
            >
            <div class="collapse" id="admin" aria-expanded="false">
              <ul class="flex-column pl-2 nav">
                <li class="nav-item">
                  <a
                    href="{{ route('admin-admincontroll-district') }}"
                    class="list-group-item  custom-sidebar list-group-item-action custom-active-color {{ (request()->is('admin/admincontrol/district')) ? 'active custom-active-color' : '' }}"
                  >
                    Buat Anggota Baru
                  </a>
                   <a
                      href="{{ route('admin-admincontroll-district-create') }}"
                      class="list-group-item custom-sidebar list-group-item-action custom-active-color {{ (request()->is('admin/admincontrol/createadmin/district')) ? 'active custom-active-color' : '' }}"
                    >
                      Daftar Anggota
                    </a>
                </li>
              </ul>
            </div> --}}
            @foreach ($userMenu as $menu)
            <a
              href="{{ route($menu->route) }}"
              class="list-group-item custom-sidebar custom-active-color list-group-item-action {{ (request()->is($menu->url.'*')) ? 'active' : '' }}"
            >
              {{ $menu->menu }}
            </a>
            @endforeach
             <a
              href="{{ route('member-event') }}"
              class="list-group-item custom-sidebar custom-active-color list-group-item-action {{ (request()->is('user/member/event*')) ? 'active' : '' }}"
            >
              Event
            </a>
            <a
              href="{{ route('member-registered-user') }}"
              class="list-group-item custom-sidebar custom-active-color list-group-item-action {{ (request()->is('user/registered*')) ? 'active' : '' }}"
            >
              Anggota Terdaftar
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