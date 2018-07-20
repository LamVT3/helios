@if(isset($currency))
    <div class="row">
        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
            <h1 class="page-title txt-color-blueDark">

                <!-- PAGE HEADER -->
                {!! $breadcrumbs !!}
            </h1>
        </div>
        <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
            <!-- Button trigger modal -->
            {{ $slot }}
        </div>
    </div>
@else
    <div class="row">
        <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
            <h1 class="page-title txt-color-blueDark">
                <!-- PAGE HEADER -->
                {!! $breadcrumbs !!}
            </h1>
        </div>
        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
            <!-- Button trigger modal -->
            {{ $slot }}
        </div>
    </div>
@endif