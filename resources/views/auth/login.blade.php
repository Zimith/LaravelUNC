@extends('layouts.app')

@section('html-lang', 'en')
@section('title', 'SiMEP')

@section('head-meta')
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1">
@endsection

@section('head-description')
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="author" content="">
@endsection

@section('content')
    <!-- contact -->
    <div class="contact">
        <div class="container">
            <div class="row ">
                <div class="col-md-12">
                    <div class="titlepage text_align_center">
                        <h2>Ingrese sus datos de <span class="blue_light">Acceso</span></h2>
                    </div>
                </div>
                <div class="col-md-10 offset-md-1">
                    <form id="request" class="main_form" action="{{ route('login.store') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 ">
                                <input class="login" placeholder="Usuario" type="text" name="user" id="user"><br><br>
                            </div>
                            <div class="col-md-12">
                                <input class="login" placeholder="Contraseña" type="password" name="pass" id="pass">
                            </div>
                            <div class="col-md-12">
                                <button class="send_btn">Ingresar</button><br><br>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- contact -->
@endsection
