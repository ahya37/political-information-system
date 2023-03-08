<div class="border-right custom-sidebar" id="sidebar-wrapper">
    <div class="sidebar-heading custom-sidebar-heading text-center">
        <img src="{{ asset('assets/images/logo2-3.png') }}" width="170" />
    </div>
    <div class="list-group list-group-flush">
        <a href="{{ route('admin-dashboard') }}"
            class="list-group-item custom-sidebar list-group-item-action custom-active-color {{ request()->is('admin/dashboard*') ? 'active custom-active-color' : '' }}">
            Dashboard
        </a>
        {{-- <a href="{{ route('admin-intelegency-index') }}"
            class="list-group-item custom-sidebar list-group-item-action custom-active-color {{ request()->is('admin/intelegency*') ? 'active custom-active-color' : '' }}">
            Intelegensi Politik
        </a> --}}
        <a class="nav-link nav-link-cs collapsed text-truncate list-group-item custom-sidebar list-group-item-action custom-active-color {{ request()->is('admin/struktur*') ? 'active custom-active-color ' : '' }}"
            href="#org" data-toggle="collapse" data-target="#org">
            <span class="d-none d-sm-inline"></span>Struktur Organisasi</a>
        <div class="collapse" id="org" aria-expanded="false">
            <ul class="flex-column pl-2 nav">
                <li class="nav-item">
                    <a href="{{ route('admin-struktur-organisasi-pusat') }}"
                        class="list-group-item custom-sidebar  list-group-item-action custom-active-color {{ request()->is('admin/struktur/create') ? 'active custom-active-color ' : '' }}">
                        Kor. Pusat
                    </a>
                    <a href="{{ route('admin-struktur-organisasi-dapil-index') }}"
                        class="list-group-item custom-sidebar  list-group-item-action custom-active-color {{ request()->is('admin/struktur/create') ? 'active custom-active-color ' : '' }}">
                        Kor. Dapil
                    </a>
                    <a href="{{ route('admin-struktur-organisasi-district-index') }}"
                        class="list-group-item custom-sidebar  list-group-item-action custom-active-color {{ request()->is('admin/struktur/create') ? 'active custom-active-color ' : '' }}">
                        Kor. Kecamatan
                    </a>
                    <a href="{{ route('admin-struktur-organisasi-create') }}"
                        class="list-group-item custom-sidebar  list-group-item-action custom-active-color {{ request()->is('admin/struktur/create') ? 'active custom-active-color ' : '' }}">
                        Kor. Desa
                    </a>
                    <a href="{{ route('admin-struktur-organisasi-rt') }}"
                        class="list-group-item custom-sidebar  list-group-item-action custom-active-color {{ request()->is('admin/struktur/create') ? 'active custom-active-color ' : '' }}">
                        Kor. RT
                    </a>

                    <a href="{{ route('admin-struktur-organisasi-test') }}" target="_blank"
                        class="list-group-item custom-sidebar list-group-item-action custom-active-color {{ request()->is('admin/struktur/dashboard') ? 'active custom-active-color' : '' }}">
                        Bagan Struktur
                    </a>
                </li>
            </ul>
        </div>

        <a class="nav-link nav-link-cs collapsed text-truncate list-group-item custom-sidebar list-group-item-action custom-active-color {{ request()->is('admin/member*') ? 'active custom-active-color ' : '' }}"
            href="#Anggota" data-toggle="collapse" data-target="#Anggota">
            <span class="d-none d-sm-inline"></span>Anggota</a>
        <div class="collapse" id="Anggota" aria-expanded="false">
            <ul class="flex-column pl-2 nav">
                <li class="nav-item">
                    <a href="{{ route('admin-member') }}"
                        class="list-group-item custom-sidebar  list-group-item-action custom-active-color {{ request()->is('admin/member') ? 'active custom-active-color ' : '' }}">
                        Anggota Terdaftar
                    </a>
                    <a href="{{ route('admin-member-potensial') }}"
                        class="list-group-item custom-sidebar  list-group-item-action custom-active-color {{ request()->is('admin/member/potensial') ? 'active custom-active-color ' : '' }}">
                        Anggota Potensial
                    </a>
                    </a>
                    <a href="{{ route('admin-member-create') }}"
                        class="list-group-item custom-sidebar list-group-item-action custom-active-color {{ request()->is('admin/member/create') ? 'active custom-active-color' : '' }}">
                        Buat Anggota Baru
                    </a>
                    <a href="{{ route('admin-reward') }}"
                        class="list-group-item custom-sidebar list-group-item-action custom-active-color {{ request()->is('admin/reward') ? 'active custom-active-color' : '' }}">
                        Reward
                    </a>
                    <a href="{{ route('admin-reward-special-referal') }}"
                        class="list-group-item custom-sidebar list-group-item-action custom-active-color {{ request()->is('admin/listrewardreferal') ? 'active custom-active-color' : '' }}">
                        Reward Khusus
                    </a>
                    <a href="{{ route('admin-listrewardreferal') }}"
                        class="list-group-item custom-sidebar list-group-item-action custom-active-color {{ request()->is('admin/listrewardreferal') ? 'active custom-active-color' : '' }}">
                        Daftar Reward
                    </a>

                </li>
            </ul>
        </div>

        <a class="nav-link nav-link-cs collapsed text-truncate list-group-item custom-sidebar list-group-item-action custom-active-color {{ request()->is('admin/admincontrol/district*') ? 'active custom-active-color' : '' }}"
            href="#admin" data-toggle="collapse" data-target="#admin">
            <span class="d-none d-sm-inline"></span>Admin</a>
        <div class="collapse" id="admin" aria-expanded="false">
            <ul class="flex-column pl-2 nav">
                <li class="nav-item">
                    <a href="{{ route('admin-admincontroll') }}"
                        class="list-group-item  custom-sidebar list-group-item-action custom-active-color {{ request()->is('admin/admincontrol/district') ? 'active custom-active-color' : '' }}">
                        Daftar Admin
                    </a>
                    <a href="{{ route('admin-admincontroll-create') }}"
                        class="list-group-item custom-sidebar list-group-item-action custom-active-color {{ request()->is('admin/admincontrol/district/createadmin') ? 'active custom-active-color' : '' }}">
                        Tambah Admin
                    </a>
                    {{-- <a
                      href="{{ route('admin-showadminsubmission') }}"
                      class="list-group-item custom-sidebar list-group-item-action custom-active-color {{ (request()->is('admin/admincontrol/showadminsubmission')) ? 'active custom-active-color' : '' }}"
                    >
                      Pengajuan Admin
                    </a> --}}
                    <a href="{{ route('admin-rewardadmin') }}"
                        class="list-group-item custom-sidebar list-group-item-action custom-active-color {{ request()->is('admin/rewardadmin') ? 'active custom-active-color' : '' }}">
                        Reward
                    </a>
                    <a href="{{ route('admin-reward-special-admin') }}"
                        class="list-group-item custom-sidebar list-group-item-action custom-active-color {{ request()->is('admin/listrewardadmin') ? 'active custom-active-color' : '' }}">
                        Reward Khusus
                    </a>
                    <a href="{{ route('admin-listrewardadmin') }}"
                        class="list-group-item custom-sidebar list-group-item-action custom-active-color {{ request()->is('admin/listrewardadmin') ? 'active custom-active-color' : '' }}">
                        Daftar Reward
                    </a>

                </li>
            </ul>
        </div>
        <a class="nav-link nav-link-cs collapsed text-truncate list-group-item custom-sidebar list-group-item-action custom-active-color {{ request()->is('admin/dapil*') ? 'active custom-active-color' : '' }}"
            href="#dapil" data-toggle="collapse" data-target="#dapil">
            <span class="d-none d-sm-inline"></span>Dapil</a>
        <div class="collapse" id="dapil" aria-expanded="false">
            <ul class="flex-column pl-2 nav">
                <li class="nav-item">
                    <a href="{{ route('admin-dapil') }}"
                        class="list-group-item  custom-sidebar list-group-item-action custom-active-color {{ request()->is('admin/admincontrol/district') ? 'active custom-active-color' : '' }}">
                        Daftar Dapil
                    </a>
                    <a href="{{ route('admin-dapil-create') }}"
                        class="list-group-item custom-sidebar list-group-item-action custom-active-color {{ request()->is('admin/dapil/create') ? 'active custom-active-color' : '' }}">
                        Tambah Dapil
                    </a>
                    <a href="{{ route('admin-koordinator-create') }}"
                        class="list-group-item custom-sidebar list-group-item-action custom-active-color {{ request()->is('admin/koordinator/create') ? 'active custom-active-color' : '' }}">
                        Tambah Koordinator
                    </a>
                    <a href="{{ route('admin-koordinator-pusat-index') }}"
                        class="list-group-item custom-sidebar list-group-item-action custom-active-color {{ request()->is('admin/koordinator/pusat/create') ? 'active custom-active-color' : '' }}">
                        Koordinator
                    </a>
                </li>
            </ul>
        </div>
        <a class="nav-link nav-link-cs collapsed text-truncate list-group-item custom-sidebar list-group-item-action custom-active-color {{ request()->is('admin/event*') ? 'active custom-active-color' : '' }}"
            href="#event" data-toggle="collapse" data-target="#event">
            <span class="d-none d-sm-inline"></span>Event</a>
        <div class="collapse" id="event" aria-expanded="false">
            <ul class="flex-column pl-2 nav">
                <li class="nav-item">
                    <a href="{{ route('admin-event') }}"
                        class="list-group-item  custom-sidebar list-group-item-action custom-active-color {{ request()->is('admin/event') ? 'active custom-active-color' : '' }}"><span>Daftar
                            Event</span></a>
                    <a href="{{ route('admin-event-create') }}"
                        class="list-group-item custom-sidebar list-group-item-action custom-active-color {{ request()->is('admin/event/*') ? 'active custom-active-color' : '' }}"><span>Buat
                            Event Baru</span>
                    </a>
                </li>
            </ul>
        </div>

        <a class="nav-link nav-link-cs collapsed text-truncate list-group-item custom-sidebar list-group-item-action custom-active-color {{ request()->is('admin/setting*') ? 'active custom-active-color' : '' }}"
            href="#target" data-toggle="collapse" data-target="#target">
            <span class="d-none d-sm-inline"></span>Target</a>
        <div class="collapse" id="target" aria-expanded="false">
            <ul class="flex-column pl-2 nav">
                <li class="nav-item">
                    <a href="{{ route('admin-setting-targetmember') }}"
                        class="list-group-item  custom-sidebar list-group-item-action custom-active-color {{ request()->is('setting/targetmember') ? 'active custom-active-color' : '' }}"><span>Atur
                            Target Anggota</span></a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin-list-target') }}"
                        class="list-group-item  custom-sidebar list-group-item-action custom-active-color {{ request()->is('setting/targetmember') ? 'active custom-active-color' : '' }}"><span>Daftar
                            Target Anggota</span></a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin-rightchoose') }}"
                        class="list-group-item  custom-sidebar list-group-item-action custom-active-color {{ request()->is('setting/rightchoose') ? 'active custom-active-color' : '' }}"><span>Atur
                            Hak Pilih</span></a>
                </li>

            </ul>
        </div>

        <a class="nav-link nav-link-cs collapsed text-truncate list-group-item custom-sidebar list-group-item-action custom-active-color {{ request()->is('admin/info/*') ? 'active custom-active-color' : '' }}"
            href="#info" data-toggle="collapse" data-target="#info">
            <span class="d-none d-sm-inline"></span>Informasi</a>
        <div class="collapse" id="info" aria-expanded="false">
            <ul class="flex-column pl-2 nav">
                <li class="nav-item">
                    <a href="{{ route('admin-intelegency') }}"
                        class="list-group-item custom-sidebar list-group-item-action custom-active-color {{ request()->is('admin/info/intelegency') ? 'active custom-active-color' : '' }}"><span>Form
                            Intelegensi Politik</span>
                    </a>
                    {{-- <a
                    href="{{ route('admin-listintelegency') }}"
                    class="list-group-item custom-sidebar list-group-item-action custom-active-color {{ (request()->is('admin/info/listintelegency')) ? 'active custom-active-color' : '' }}"
                    ><span>Daftar Intelegensi Politik</span>
                  </a> --}}
                    <a href="{{ route('admin-intelegency-index') }}"
                        class="list-group-item custom-sidebar list-group-item-action custom-active-color {{ request()->is('admin/intelegency*') ? 'active custom-active-color' : '' }}">
                        Intelegensi Politik
                    </a>
                </li>
            </ul>
        </div>

        <a class="nav-link nav-link-cs collapsed text-truncate list-group-item custom-sidebar list-group-item-action custom-active-color {{ request()->is('admin/cost/*') ? 'active custom-active-color' : '' }}"
            href="#cas" data-toggle="collapse" data-target="#cas">
            <span class="d-none d-sm-inline"></span>Cost Politik</a>
        <div class="collapse" id="cas" aria-expanded="false">
            <ul class="flex-column pl-2 nav">
                <li class="nav-item">
                    <a href="{{ route('admin-cost-cost') }}"
                        class="list-group-item  custom-sidebar list-group-item-action custom-active-color {{ request()->is('cost/create') ? 'active custom-active-color' : '' }}"><span>Tambah
                            Pengeluaran</span></a>
                    <a href="{{ route('admin-cost-index') }}"
                        class="list-group-item  custom-sidebar list-group-item-action custom-active-color {{ request()->is('cost/index') ? 'active custom-active-color' : '' }}"><span>Daftar
                            Pengeluaran</span></a>
                </li>
            </ul>
        </div>


        {{-- <a
              class="nav-link nav-link-cs collapsed text-truncate list-group-item custom-sidebar list-group-item-action custom-active-color {{ (request()->is('admin/voucher*')) ? 'active custom-active-color' : '' }}"
              href="#report"
              data-toggle="collapse"
              data-target="#report"
              >
              <span class="d-none d-sm-inline"></span>Voucher</a
            >
             <div class="collapse" id="report" aria-expanded="false">
              <ul class="flex-column pl-2 nav">
                <li class="nav-item">
                  <a
                    href="{{ route('admin-voucher-report') }}"
                    class="list-group-item  custom-sidebar list-group-item-action custom-active-color {{ (request()->is('setting/targetmember')) ? 'active custom-active-color' : '' }}"
                    ><span>Pengeluaran Voucher</span></a
                  >
                </li>
              </ul>
            </div> --}}

        <a class="list-group-item d-lg-none custom-sidebar list-group-item-action custom-active-color"
            href="{{ route('admin-logout') }}"
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
