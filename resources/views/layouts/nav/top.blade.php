<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            {{ config('app.name', 'Laravel') }}
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">
                @if(Auth::user())
                    <li class="nav-item">
                        <a class="nav-link" href="/shares">Shares</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/companies">Companies</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/exchanges">Exchanges</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/owners">Owners</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/banks">Bank</A>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/desktop">Desktop</a>
                    </li>
                @endif
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif
                @else
                    @role('super-admin')
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                Admin <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="/admin">Admin</a></li>
                                <li><a class="dropdown-item" href="/admin/game">
                                    Manage Game
                                </a></li>
                                <li><a class="dropdown-item" href="/admin/users">
                                    Users
                                </a></li>
                            </ul>
                        </li>
                    @endrole
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
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
                @endguest
            </ul>
        </div>
    </div>
</nav>