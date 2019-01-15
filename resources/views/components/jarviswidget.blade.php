<!-- Widget ID (each widget will need unique ID)-->
<div class="jarviswidget jarviswidget-color-darken" id="wid-id-{{ $id }}"
     data-widget-editbutton="false" data-widget-deletebutton="false"
     data-widget-fullscreenbutton="false" data-widget-sortable="false">
    <!-- widget options:
    usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">

    data-widget-colorbutton="false"
    data-widget-editbutton="false"
    data-widget-togglebutton="false"
    data-widget-deletebutton="false"
    data-widget-fullscreenbutton="false"
    data-widget-custombutton="false"
    data-widget-collapsed="true"
    data-widget-sortable="false"

    -->
    <header>
        <span class="widget-icon"> <i class="fa {{ $icon }}"></i> </span>
        {{-- 2018-04-17 [HEL-9] LamVT add dropdown for C3/L8 chart --}}
        <h2 id="{{ $id }}"> {{ $title }} </h2>
        @if (isset($dropdown) && $dropdown == "true")
            <div class="widget-toolbar" role="menu">
                <div class="btn-group open">
                    <button id="dropdown" class="btn dropdown-toggle btn-xs btn-warning" data-toggle="dropdown">
                        Dropdown <i class="fa fa-caret-down"></i>
                    </button>
                    <ul class="dropdown-menu pull-right" style="z-index: 999;">
                        @for ($i = 1; $i <= 12; $i++)
                            <li id="month" value="{{$i}}">
                                <a href="javascript:void(0);">{{date("F - Y", strtotime(date("Y") ."-". $i ."-01"))}}</a>
                            </li>
                        @endfor
                    </ul>
                </div>
            </div>
        @endif

        @if (isset($dropdownY) && $dropdownY == "true")
            <div class="widget-toolbar" role="menu">
                <div class="btn-group open">
                    <button id="dropdownY" class="btn dropdown-toggle btn-xs btn-warning" data-toggle="dropdown">
                        Dropdown <i class="fa fa-caret-down"></i>
                    </button>
                    <ul class="dropdown-menu pull-right" style="z-index: 999;">
                        <li id="lastMonth" value="6"><a href="javascript:void(0);">Last 6 months</a></li>
                        <li id="lastMonth" value="12"><a href="javascript:void(0);">Last 12 months</a></li>
                        <li id="lastMonth" value="18"><a href="javascript:void(0);">Last 18 months</a></li>
                        <li id="lastMonth" value="24"><a href="javascript:void(0);">Last 24 months</a></li>
                    </ul>
                </div>
            </div>
        @endif
        {{-- end 2018-04-17 [HEL-9] LamVT add dropdown for C3/L8 chart --}}
    </header>

    <!-- widget div-->
    <div>

        <!-- widget edit box -->
        <div class="jarviswidget-editbox">
            <!-- This area used as dropdown edit box -->

        </div>
        <!-- end widget edit box -->

        <!-- widget content -->
            {{ $slot }}
        <!-- end widget content -->

    </div>
    <!-- end widget div -->

</div>
<!-- end widget -->