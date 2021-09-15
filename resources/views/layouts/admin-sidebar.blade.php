<div class="border-right" id="sidebar-wrapper">
          <div class="sidebar-heading text-center">
            <img src="{{ asset('assets/images/logo2-3.png') }}" width="170" />
          </div>
          <div class="list-group list-group-flush">
            <a
              href="{{ route('admin-dashboard') }}"
              class="list-group-item list-group-item-action {{ (request()->is('admin/dashboard*')) ? 'active' : '' }}"
            >
              Dashboard
            </a>
            <a
              href="{{ route('admin-member') }}"
              class="list-group-item list-group-item-action {{ (request()->is('admin/member')) ? 'active' : '' }}"
            >
              Anggota Terdaftar
            </a>
            <a
              href="{{ route('admin-member-create') }}"
              class="list-group-item list-group-item-action {{ (request()->is('admin/member/create')) ? 'active' : '' }}"
            >
              Buat Anggota Baru
            </a>
            <a
              href="{{ route('admin-admincontroll-district-create') }}"
              class="list-group-item list-group-item-action {{ (request()->is('admin/admincontrol/createadmin/district')) ? 'active' : '' }}"
            >
              Tambah Admin
            </a>
            <a
              href="{{ route('admin-admincontroll-district') }}"
              class="list-group-item list-group-item-action {{ (request()->is('admin/admincontrol/district')) ? 'active' : '' }}"
            >
              Daftar Admin
            </a>
             <a
              href="{{ route('admin-event-create') }}"
              class="list-group-item list-group-item-action {{ (request()->is('admin/event/create')) ? 'active' : '' }}"
            >
              Buat Event Baru
            </a>
            <a
              href="{{ route('admin-event') }}"
              class="list-group-item list-group-item-action {{ (request()->is('admin/event')) ? 'active' : '' }}"
            >
              Daftar Event
            </a>
            <a class="list-group-item d-lg-none list-group-item-action" href="{{ route('admin-logout') }}"
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