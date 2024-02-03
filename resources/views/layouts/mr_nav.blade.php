<nav class="navbar navbar-expand-md shadow mr-nav">
  <div class="container">
    <a class="navbar-brand text-italic mr-nav-link-logo" href="{{ url('/') }}">
      My Climbing
    </a>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
          <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
             data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
            <span class="mr-color-white">{{ mb_strtoupper(app()->getLocale()) }}</span> <span class="caret"></span>
          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
            @foreach(\App\Models\System\Language::all() as $item)
              @if($item->getCode() == mb_strtoupper(app()->getLocale()))
                @continue
              @endif
              <a href="{{ url('/locale/'.mb_strtolower($item->getCode())) }}" class="dropdown-item">
                <i class="nav-item mr-color-green-dark"></i> {{ $item->getCode() . ' ' . $item->getName() }}</a>
            @endforeach
          </div>
        </li>
        @guest
          <li class="nav-item">
            <a class="nav-link mr-nav-link-color" href="{{ route('faq.page') }}"><span class="">FAQ</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link mr-nav-link-color" href="{{ route('login') }}"><span
                class="">{{ __('Login') }}</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link mr-nav-link-color" href="{{ route('register') }}"><span
                class="">{{ __('Register') }}</span></a>
          </li>
        @else
          <li class="nav-item">
            <a class="nav-link mr-nav-link-color" href="/admin">{{__('mr-t.Account')}}</a>
          </li>
          <li class="nav-item dropdown">
            <div class="dropdown-menu dropdown-menu-right mr-nav-link-submenu-background"
                 aria-labelledby="navbarDropdown">
              <a class="nav-link mr-nav-link-submenu-color" href="{{route('faq.page')}}"><span class="">FAQ</span></a>
            </div>
          </li>
          @include('layouts.Elements.logout')
        @endguest
      </ul>
    </div>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false"
            aria-label="{{ __('Toggle navigation') }}">
      <span class="navbar-toggler-icon" style="color: #00A000"></span>
    </button>

  </div>
</nav>
