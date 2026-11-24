@extends('layouts.app')

@section('title', 'SiMEP')

@section('content')
    <div class="guarantee">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="titlepage text_align_center">
                        <h2>Bienvenido <span class="blue_light">{{ $usuario->nombre_completo }}</span></h2>
                        <br>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-warning">Salir</button>
                        </form>
                        <br><br>
                        <p>Eres responsable de los siguientes proyectos</p>
                    </div>
                </div>
            </div>
            <div class="row">
                @forelse ($proyectos as $proyecto)
                    <div class="col-lg-3 col-md-6">
                        <div id="ho_co" class="guarantee-box_main">
                            <div class="guarantee-box text_align_center">
                                <i>
                                    <a href="{{ route('projects.show', $proyecto) }}">
                                        <img src="{{ asset('images/proyectos.png') }}" width="150px" alt="Proyecto">
                                    </a>
                                </i>
                                <h3>
                                    <a href="{{ route('projects.show', $proyecto) }}">{{ $proyecto->titulo }}</a>
                                </h3>
                            </div>
                            <a class="read_more" href="{{ route('projects.show', $proyecto) }}">Ver más</a>
                        </div>
                    </div>
                @empty
                    <div class="col-md-12 text_align_center">
                        <p>Actualmente no tienes proyectos asignados.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
