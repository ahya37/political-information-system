 <nav
        class="navbar navbar-expand-lg navbar-light navbar-store fixed-top"
        data-aos="fade-down"
      >
        <div class="container-fluid">
          <button
            class="button btn btn-secondary d-md-none mr-auto mr2"
            id="menu-toggle"
          >
            &laquo; Menu
          </button>
          <button
            class="navbar-toggler"
            type="button"
            data-toggle="collapse"
            data-target="#navbarSupportedContent"
          >
            <span class="navbar-toggler-icon"> </span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- desktop menu -->
            <ul class="navbar-nav d-none d-lg-flex ml-auto">
              <li class="nav-item dropdown">
                <a
                  href="#"
                  class="nav-link"
                  id="navbarDropdown"
                  role="button"
                  data-toggle="dropdown"
                >
                  <img
                    src="{{ asset('storage/'.Auth::user()->photo ?? '') }}"
                    alt=""
                    class="rounded-circle mr-2 profile-picture"
                  />
                  {{ Auth::user()->name }}
                </a>
                <div class="dropdown-menu">
                    
                  {{-- <div class="dropdown-divider"></div> --}}
                  {{-- <a  class="dropdown-item" href="{{ route('logout') }}">
                    {{ __('Ganti Password') }}
                  </a> --}}

                  <a class="dropdown-item" href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                  </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                    </form>
                </div>
              </li>
            </ul>

            <ul class="navbar-nav d-block d-lg-none">
              <li class="nav-item">
                  <img
                    src="{{ asset('assets/images/profile.svg') }}"
                    alt=""
                    class="rounded-circle mr-2 profile-picture mt-3"
                  />
                <a href="#" class="nav-link"> {{ Auth::user()->name }} </a>
              </li>
            </ul>
          </div>
        </div>
      </nav>