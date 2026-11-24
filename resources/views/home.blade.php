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
    <!-- loader -->
    <!-- end loader -->
    <!-- top -->
    <!-- end banner -->
    <!-- domain -->
    <!-- end domain -->
    <!-- guarantee -->
    @include('home.indicadores')
    <!-- end guarantee -->
    <!-- about -->
    <div class="about">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="titlepage text_align_center">
                        <h2>Sobre el <span class="blue_light">SiMEP</span></h2>
                    </div>
                </div>
                <div class="col-md-10 offset-md-1">
                    <div class="about_img text_align_center">
                        <figure><img src="{{ asset('images/pep.webp') }}" alt="#"></figure>
                        <!--<p>
                           Texto informativo sobre el sistema
                        </p>-->
                        <p></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end about -->
@endsection
