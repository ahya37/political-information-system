<div class="border-right custom-sidebar" id="sidebar-wrapper">
          <div class="sidebar-heading custom-sidebar-heading text-center">
            <img src="{{ asset('assets/images/logo2-3.png') }}" width="170" />
          </div>
          <div class="list-group list-group-flush">
            <a
              href="{{ route('admin-dashboard') }}"
              class="list-group-item custom-sidebar list-group-item-action custom-active-color {{ (request()->is('admin/dashboard*')) ? 'active custom-active-color' : '' }}"
            >
              Dashboard
            </a>
            <a
              class="nav-link collapsed text-truncate list-group-item custom-sidebar list-group-item-action custom-active-color {{ (request()->is('admin/member*')) ? 'active custom-active-color ' : '' }}"
              href="#Anggota"
              data-toggle="collapse"
              data-target="#Anggota"
              >
              <span class="d-none d-sm-inline"></span>Anggota</a
            >
            <div class="collapse" id="Anggota" aria-expanded="false">
              <ul class="flex-column pl-2 nav">
                <li class="nav-item">
                  <a
                    href="{{ route('admin-member') }}"
                    class="list-group-item custom-sidebar  list-group-item-action custom-active-color {{ (request()->is('admin/member')) ? 'active custom-active-color ' : '' }}"
                  >
                    Anggota Terdaftar
                  </a>
                  </a>
                   <a
                    href="{{ route('admin-member-create') }}"
                    class="list-group-item custom-sidebar list-group-item-action custom-active-color {{ (request()->is('admin/member/create')) ? 'active custom-active-color' : '' }}"
                   >
                    Buat Anggota Baru
                  </a>
                </li>
              </ul>
            </div>
            
            <a
              class="nav-link collapsed text-truncate list-group-item custom-sidebar list-group-item-action custom-active-color {{ (request()->is('admin/admincontrol/district*')) ? 'active custom-active-color' : '' }}"
              href="#admin"
              data-toggle="collapse"
              data-target="#admin"
              >
              <span class="d-none d-sm-inline"></span>Admin</a
            >
            <div class="collapse" id="admin" aria-expanded="false">
              <ul class="flex-column pl-2 nav">
                <li class="nav-item">
                  <a
                    href="{{ route('admin-admincontroll-district') }}"
                    class="list-group-item  custom-sidebar list-group-item-action custom-active-color {{ (request()->is('admin/admincontrol/district')) ? 'active custom-active-color' : '' }}"
                  >
                    Daftar Admin
                  </a>
                   <a
                      href="{{ route('admin-admincontroll-district-create') }}"
                      class="list-group-item custom-sidebar list-group-item-action custom-active-color {{ (request()->is('admin/admincontrol/createadmin/district')) ? 'active custom-active-color' : '' }}"
                    >
                      Tambah Admin
                    </a>
                </li>
              </ul>
            </div>
            <a
              class="nav-link collapsed text-truncate list-group-item custom-sidebar list-group-item-action custom-active-color {{ (request()->is('admin/event*')) ? 'active custom-active-color' : '' }}"
              href="#submenu1"
              data-toggle="collapse"
              data-target="#submenu1"
              >
              <span class="d-none d-sm-inline"></span>Event</a
            >
            <div class="collapse" id="submenu1" aria-expanded="false">
              <ul class="flex-column pl-2 nav">
                <li class="nav-item">
                  <a
                    href="{{ route('admin-event') }}"
                    class="list-group-item  custom-sidebar list-group-item-action custom-active-color {{ (request()->is('admin/event')) ? 'active custom-active-color' : '' }}"
                    ><span>Daftar Event</span></a
                  >
                  <a
                    href="{{ route('admin-event-create') }}"
                    class="list-group-item custom-sidebar list-group-item-action custom-active-color {{ (request()->is('admin/event/create')) ? 'active custom-active-color' : '' }}"
                    ><span>Daftar Event</span></a>
                </li>
              </ul>
            </div>
            <a class="list-group-item d-lg-none custom-sidebar list-group-item-action custom-active-color" href="{{ route('admin-logout') }}"
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