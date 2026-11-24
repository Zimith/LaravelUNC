@php($homeRoute = session()->has('user_id') ? route('main') : route('home'))
<div class="header">
    <div class="container">
        <div class="row d_flex">
            <div class=" col-md-2 col-sm-3 col logo_section">
                <div class="full">
                    <div class="center-desk">
                        <div class="logo">
                            <a href="{{ $homeRoute }}"><img src="{{ asset('images/simep2.png') }}" alt="#"></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8 col-sm-12">
                <nav class="navigation navbar navbar-expand-md navbar-dark ">
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample04" aria-controls="navbarsExample04" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarsExample04">
                        <ul class="navbar-nav mr-auto">
                            <li class="nav-item active">
                                <a class="nav-link" href="{{ $homeRoute }}">Inicio</a>
                            </li>
                            @if (session()->has('user_id'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('main') }}">Proyectos</a>
                                </li>
                            @endif
                            <li class="nav-item">
                                <a class="nav-link" href="#">Documentación</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="https://agro.unc.edu.ar/planificacion-institucional/pep-2030/">PEP2030</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="https://agro.unc.edu.ar">FCA</a>
                            </li>
                            @if (session()->has('user_id'))
                                <li class="nav-item">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button class="nav-link btn btn-link" type="submit">Salir</button>
                                    </form>
                                </li>
                            @else
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">Ingresar</a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</div>
