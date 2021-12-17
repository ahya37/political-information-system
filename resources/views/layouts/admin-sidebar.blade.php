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
              class="nav-link nav-link-cs collapsed text-truncate list-group-item custom-sidebar list-group-item-action custom-active-color {{ (request()->is('admin/member*')) ? 'active custom-active-color ' : '' }}"
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
                  <a
                    href="{{ route('admin-member-potensial') }}"
                    class="list-group-item custom-sidebar  list-group-item-action custom-active-color {{ (request()->is('admin/member/potensial')) ? 'active custom-active-color ' : '' }}"
                  >
                    Anggota Potensial
                  </a>
                  </a>
                   <a
                    href="{{ route('admin-member-create') }}"
                    class="list-group-item custom-sidebar list-group-item-action custom-active-color {{ (request()->is('admin/member/create')) ? 'active custom-active-color' : '' }}"
                   >
                    Buat Anggota Baru
                  </a>
                   <a
                    href="{{ route('admin-reward') }}"
                    class="list-group-item custom-sidebar list-group-item-action custom-active-color {{ (request()->is('admin/reward')) ? 'active custom-active-color' : '' }}"
                   >
                    Reward
                  </a>
                </li>
              </ul>
            </div>
            
            <a
              class="nav-link nav-link-cs collapsed text-truncate list-group-item custom-sidebar list-group-item-action custom-active-color {{ (request()->is('admin/admincontrol/district*')) ? 'active custom-active-color' : '' }}"
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
                    href="{{ route('admin-admincontroll') }}"
                    class="list-group-item  custom-sidebar list-group-item-action custom-active-color {{ (request()->is('admin/admincontrol/district')) ? 'active custom-active-color' : '' }}"
                  >
                    Daftar Admin
                  </a>
                   <a
                      href="{{ route('admin-admincontroll-create') }}"
                      class="list-group-item custom-sidebar list-group-item-action custom-active-color {{ (request()->is('admin/admincontrol/district/createadmin')) ? 'active custom-active-color' : '' }}"
                    >
                      Tambah Admin
                    </a>
                   <a
                      href="{{ route('admin-showadminsubmission') }}"
                      class="list-group-item custom-sidebar list-group-item-action custom-active-color {{ (request()->is('admin/admincontrol/showadminsubmission')) ? 'active custom-active-color' : '' }}"
                    >
                      Pengajuan Admin
                    </a>
                     <a
                    href="{{ route('admin-rewardadmin') }}"
                    class="list-group-item custom-sidebar list-group-item-action custom-active-color {{ (request()->is('admin/rewardadmin')) ? 'active custom-active-color' : '' }}"
                   >
                    Reward
                  </a>
                </li>
              </ul>
            </div>
            <a
              class="nav-link nav-link-cs collapsed text-truncate list-group-item custom-sidebar list-group-item-action custom-active-color {{ (request()->is('admin/dapil*')) ? 'active custom-active-color' : '' }}"
              href="#dapil"
              data-toggle="collapse"
              data-target="#dapil"
              >
              <span class="d-none d-sm-inline"></span>Dapil</a
            >
            <div class="collapse" id="dapil" aria-expanded="false">
              <ul class="flex-column pl-2 nav">
                <li class="nav-item">
                  <a
                    href="{{ route('admin-dapil') }}"
                    class="list-group-item  custom-sidebar list-group-item-action custom-active-color {{ (request()->is('admin/admincontrol/district')) ? 'active custom-active-color' : '' }}"
                  >
                    Daftar Dapil
                  </a>
                   <a
                      href="{{ route('admin-dapil-create') }}"
                      class="list-group-item custom-sidebar list-group-item-action custom-active-color {{ (request()->is('admin/dapil/create')) ? 'active custom-active-color' : '' }}"
                    >
                      Tambah Dapil
                    </a>
                </li>
              </ul>
            </div>
            <a
              class="nav-link nav-link-cs collapsed text-truncate list-group-item custom-sidebar list-group-item-action custom-active-color {{ (request()->is('admin/event*')) ? 'active custom-active-color' : '' }}"
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
                    ><span>Buat Event Baru</span></a>
                </li>
              </ul>
            </div>

            <a
              class="nav-link nav-link-cs collapsed text-truncate list-group-item custom-sidebar list-group-item-action custom-active-color {{ (request()->is('admin/event*')) ? 'active custom-active-color' : '' }}"
              href="#setting"
              data-toggle="collapse"
              data-target="#setting"
              >
              <span class="d-none d-sm-inline"></span>Pengaturan</a
            >
            <div class="collapse" id="setting" aria-expanded="false">
              <ul class="flex-column pl-2 nav">
                <li class="nav-item">
                  <a
                    href="{{ route('admin-setting-targetmember') }}"
                    class="list-group-item  custom-sidebar list-group-item-action custom-active-color {{ (request()->is('setting/targetmember')) ? 'active custom-active-color' : '' }}"
                    ><span>Target Anggota</span></a
                  >
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