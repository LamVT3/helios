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
                    <ul class="dropdown-menu pull-right">
                        @for ($i = 1; $i <= 12; $i++)
                            <li id="month" value="{{$i}}">
                                <a href="javascript:void(0);">{{date('F - Y', strtotime('2018-'.$i))}}</a>
                            </li>
                        @endfor
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