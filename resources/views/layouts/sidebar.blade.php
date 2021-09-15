<div class="border-right" id="sidebar-wrapper">
          <div class="sidebar-heading text-center">
            <img src="{{ asset('assets/images/logo2-3.png') }}" width="170" />
          </div>
          <div class="list-group list-group-flush">
            @foreach ($userMenu as $menu)
            <a
              href="{{ route($menu->route) }}"
              class="list-group-item list-group-item-action {{ (request()->is($menu->url.'*')) ? 'active' : '' }}"
            >
              {{ $menu->menu }}
            </a>
            @endforeach
             <a
              href="{{ route('member-event') }}"
              class="list-group-item list-group-item-action {{ (request()->is('user/member/event*')) ? 'active' : '' }}"
            >
              Event
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