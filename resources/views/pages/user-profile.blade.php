@extends('layouts.master')

@section('content')
    <!-- MAIN PANEL -->
    <div id="main" role="main">
        <!-- MAIN CONTENT -->
        <div id="content">

            @component('components.breadcrumbs', ['breadcrumbs' => $breadcrumbs]) @endcomponent
            <div class="block text-center">
                <h1>
                    <span class="semi-bold">{{ auth()->user()->name }}</span>
                    <br>
                    {{ auth()->user()->email }}
                    <br>
                    <small>{{ auth()->user()->roles[0]->display_name }}</small>
                </h1>
            </div>

        </div>
    </div>
@endsection