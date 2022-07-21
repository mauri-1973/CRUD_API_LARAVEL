<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
      <li class="nav-item">
        <router-link to="/dashboard" class="nav-link">
          <i class="nav-icon fas fa-tachometer-alt blue"></i>
          <p>
          {{ __('lang.adm9') }}
          </p>
        </router-link>
      </li>

      <li class="nav-item">
        <router-link to="/products" class="nav-link">
          <i class="nav-icon fas fa-list orange"></i>
          <p>
          {{ __('lang.adm8') }}
          </p>
        </router-link>
      </li>

      @can('isAdmin')
        <li class="nav-item">
          <router-link to="/users" class="nav-link">
            <i class="fa fa-users nav-icon blue"></i>
            <p>{{ __('lang.adm7') }}</p>
          </router-link>
        </li>
      @endcan

      

      @can('isAdmin')
      <li class="nav-item has-treeview">
        <a href="#" class="nav-link">
          <i class="nav-icon fas fa-cog green"></i>
          <p>
            {{ __('lang.adm6') }}
            <i class="right fas fa-angle-left"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">

          <li class="nav-item">
            <router-link to="/product/category" class="nav-link">
              <i class="nav-icon fas fa-list-ol green"></i>
              <p>
              {{ __('lang.adm5') }}
              </p>
            </router-link>
          </li>
          <li class="nav-item">
            <router-link to="/product/tag" class="nav-link">
              <i class="nav-icon fas fa-tags green"></i>
              <p>
              {{ __('lang.adm4') }}
              </p>
            </router-link>
          </li>
          
        </ul>
      </li>

      @endcan
      
      
      <li class="nav-item has-treeview">
        <a href="#" class="nav-link">
          <i class="nav-icon fas fa-language green"></i>
          <p>
            {{ __('lang.adm2') }}
            <i class="right fas fa-angle-left"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
          @if (config('locale.status') && count(config('locale.languages')) > 1)
            @foreach (array_keys(config('locale.languages')) as $lang)
                @if ($lang != App::getLocale())
                  <li class="nav-item">
                    <a href="#" class="nav-link" onclick="event.preventDefault();
                    document.getElementById('lang-form{{ $lang }}').submit();">
                        <i class="nav-icon fas fa-upload green"></i>
                        <p>
                           @if($lang == "es")
                           {{ __('lang.adm0') }}
                           @else
                           {{ __('lang.adm1') }}
                           @endif
                           <small>{!! $lang !!}</small>
                        </p>
                    </a>
                    <form id="lang-form{{ $lang }}" action="{{ route('lang') }}" method="POST" style="display: none;">
                      @csrf
                      <input type="hidden" id="lang" name="lang" value="{{ $lang }}" />
                    </form>
                  </li>
                  
                @endif
            @endforeach
          @endif
        </ul>
      </li>
      <li class="nav-item">
        <a href="#" class="nav-link" onclick="event.preventDefault();
          document.getElementById('logout-form').submit();">
          <i class="nav-icon fas fa-power-off red"></i>
          <p>
            {{ __('Logout') }}
          </p>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
          @csrf
        </form>
      </li>
    </ul>
  </nav>