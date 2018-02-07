@extends('layouts.master')

@section('content')
    <!-- MAIN PANEL -->
    <div id="main" role="main">

        <!-- MAIN CONTENT -->
        <div id="content">

        @component('components.breadcrumbs', ['breadcrumbs' => $breadcrumbs])
        @endcomponent

            <div class="row">

                <div class="col-sm-12 col-md-12 col-lg-3">
                    <!-- new widget -->
                    <div class="jarviswidget jarviswidget-color-blueDark">
                        <header>
                            <h2> Add Kpis </h2>
                        </header>

                        <!-- widget div-->
                        <div>

                            <section class="widget-body">
                                <!-- content goes here -->

                                <form id="add-event-form" class="smart-form">
                                    <fieldset>

                                        <section>
                                            <label>Month</label>
                                            <label class="select">
                                                <select name="kpi_month" id="kpi-month" class="form-control">
                                                    <option value="1">January</option>
                                                    <option value="2">Febrary</option>
                                                    <option value="3">March</option>
                                                    <option value="4">April</option>
                                                    <option value="5">May</option>
                                                    <option value="6">June</option>
                                                    <option value="7">July</option>
                                                    <option value="8">August</option>
                                                    <option value="9">September</option>
                                                    <option value="10">October</option>
                                                    <option value="11">November</option>
                                                    <option value="12">December</option>
                                                </select>
                                                <i></i>
                                            </label>
                                        </section>
                                        <section>
                                            <label class="label">KPI File</label>
                                            <div class="input input-file">
                                                <span class="button"><input type="file" id="file" onchange="this.parentNode.nextSibling.value = this.value">Browse</span><input type="text" readonly="">
                                            </div>
                                            <div class="note">This is a required field.</div>
                                        </section>

                                    </fieldset>
                                    <div class="form-actions text-center">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <button class="btn btn-primary btn-sm" type="button" id="add-event" >
                                                    Import KPI
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <!-- end content -->
                            </section>
                        </div>
                    </div>
                    <!-- end widget -->

                    {{--<div class="well well-sm" id="event-container">
                        <form>
                            <fieldset>
                                <legend>
                                    Draggable KPIs
                                </legend>
                                <ul id='external-events' class="list-unstyled">
                                    <li>
                                        <span class="bg-color-darken txt-color-white" data-description="Currently busy" data-icon="fa-time">Office Meeting</span>
                                    </li>
                                    <li>
                                        <span class="bg-color-blue txt-color-white" data-description="No Description" data-icon="fa-pie">Lunch Break</span>
                                    </li>
                                    <li>
                                        <span class="bg-color-red txt-color-white" data-description="Urgent Tasks" data-icon="fa-alert">URGENT</span>
                                    </li>
                                </ul>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" id="drop-remove" class="checkbox style-0" checked="checked">
                                        <span>remove after drop</span> </label>

                                </div>
                            </fieldset>
                        </form>

                    </div>--}}
                </div>
                <div class="col-sm-12 col-md-12 col-lg-9">

                    <!-- new widget -->
                    <div class="jarviswidget jarviswidget-color-blueDark">

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
                            <span class="widget-icon"> <i class="fa fa-calendar"></i> </span>
                            <h2> KPIs This Month </h2>
                            <div class="widget-toolbar">
                                <!-- add: non-hidden - to disable auto hide -->
                                <div class="btn-group">
                                    <button class="btn dropdown-toggle btn-xs btn-default" data-toggle="dropdown">
                                        Showing <i class="fa fa-caret-down"></i>
                                    </button>
                                    <ul class="dropdown-menu js-status-update pull-right">
                                        <li>
                                            <a href="javascript:void(0);" id="mt">Month</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" id="ag">Agenda</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" id="td">Today</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </header>

                        <!-- widget div-->
                        <div>

                            <div class="widget-body no-padding">
                                <!-- content goes here -->
                                <div class="widget-body-toolbar">

                                    <div id="calendar-buttons">

                                        <div class="btn-group">
                                            <a href="javascript:void(0)" class="btn btn-default btn-xs" id="btn-prev"><i class="fa fa-chevron-left"></i></a>
                                            <a href="javascript:void(0)" class="btn btn-default btn-xs" id="btn-next"><i class="fa fa-chevron-right"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div id="calendar"></div>

                                <!-- end content -->
                            </div>

                        </div>
                        <!-- end widget div -->
                    </div>
                    <!-- end widget -->

                </div>

            </div>

        </div>
        <!-- END MAIN CONTENT -->

    </div>
    <!-- END MAIN PANEL -->

@endsection

@section('script')
    <!-- PAGE RELATED PLUGIN(S) -->
    <script src="{{ asset('js/plugin/moment/moment.min.js') }}"></script>
    <script src="{{ asset('js/plugin/fullcalendar/jquery.fullcalendar.min.js') }}"></script>



    <script type="text/javascript">

        // DO NOT REMOVE : GLOBAL FUNCTIONS!

        $(document).ready(function() {

            pageSetUp();


            "use strict";

            var date = new Date();
            var d = date.getDate();
            var m = date.getMonth();
            var y = date.getFullYear();

            var hdr = {
                left: 'title',
                center: 'month,agendaWeek,agendaDay',
                right: 'prev,today,next'
            };

            var initDrag = function (e) {
                // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
                // it doesn't need to have a start or end

                var eventObject = {
                    title: $.trim(e.children().text()), // use the element's text as the event title
                    description: $.trim(e.children('span').attr('data-description')),
                    icon: $.trim(e.children('span').attr('data-icon')),
                    className: $.trim(e.children('span').attr('class')) // use the element's children as the event class
                };
                // store the Event Object in the DOM element so we can get to it later
                e.data('eventObject', eventObject);

                // make the event draggable using jQuery UI
                e.draggable({
                    zIndex: 999,
                    revert: true, // will cause the event to go back to its
                    revertDuration: 0 //  original position after the drag
                });
            };

            var addEvent = function (title, priority, description, icon) {
                title = title.length === 0 ? "Untitled Event" : title;
                description = description.length === 0 ? "No Description" : description;
                icon = icon.length === 0 ? " " : icon;
                priority = priority.length === 0 ? "label label-default" : priority;

                var html = $('<li><span class="' + priority + '" data-description="' + description + '" data-icon="' +
                    icon + '">' + title + '</span></li>').prependTo('ul#external-events').hide().fadeIn();

                $("#event-container").effect("highlight", 800);

                initDrag(html);
            };

            /* initialize the external events
             -----------------------------------------------------------------*/

            $('#external-events > li').each(function () {
                initDrag($(this));
            });

            $('#add-event').click(function () {
                var title = $('#title').val(),
                    priority = $('input:radio[name=priority]:checked').val(),
                    description = $('#description').val(),
                    icon = $('input:radio[name=iconselect]:checked').val();

                addEvent(title, priority, description, icon);
            });

            /* initialize the calendar
             -----------------------------------------------------------------*/

            $('#calendar').fullCalendar({

                header: hdr,
                editable: true,
                droppable: true, // this allows things to be dropped onto the calendar !!!

                drop: function (date, allDay) { // this function is called when something is dropped

                    // retrieve the dropped element's stored Event Object
                    var originalEventObject = $(this).data('eventObject');

                    // we need to copy it, so that multiple events don't have a reference to the same object
                    var copiedEventObject = $.extend({}, originalEventObject);

                    // assign it the date that was reported
                    copiedEventObject.start = date;
                    copiedEventObject.allDay = allDay;

                    // render the event on the calendar
                    // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
                    $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);

                    // is the "remove after drop" checkbox checked?
                    if ($('#drop-remove').is(':checked')) {
                        // if so, remove the element from the "Draggable Events" list
                        $(this).remove();
                    }

                },

                select: function (start, end, allDay) {
                    var title = prompt('Event Title:');
                    if (title) {
                        calendar.fullCalendar('renderEvent', {
                                title: title,
                                start: start,
                                end: end,
                                allDay: allDay
                            }, true // make the event "stick"
                        );
                    }
                    calendar.fullCalendar('unselect');
                },

                events: [{
                    title: 'C3',
                    start: new Date(y, m, 1),
                    allDay: true,
                    description: '2345',
                    className: ["event", "bg-color-blue"],
                }, {
                    title: 'Spent',
                    start: new Date(y, m, 1),
                    allDay: true,
                    description: '2345',
                    className: ["event", "bg-color-greenLight"],
                }, {
                    title: 'Revenue',
                    start: new Date(y, m, 1),
                    allDay: true,
                    description: '2345',
                    className: ["event", "bg-color-redLight"],
                }, {
                    title: 'C3',
                    start: new Date(y, m, 2),
                    allDay: true,
                    description: '2345',
                    className: ["event", "bg-color-blue"],
                }, {
                    title: 'Spent',
                    start: new Date(y, m, 2),
                    allDay: true,
                    description: '2345',
                    className: ["event", "bg-color-greenLight"],
                }, {
                    title: 'Revenue',
                    start: new Date(y, m, 2),
                    allDay: true,
                    description: '2345',
                    className: ["event", "bg-color-redLight"],
                }, {
                    title: 'C3',
                    start: new Date(y, m, 3),
                    allDay: true,
                    description: '2345',
                    className: ["event", "bg-color-blue"],
                }, {
                    title: 'Spent',
                    start: new Date(y, m, 3),
                    allDay: true,
                    description: '2345',
                    className: ["event", "bg-color-greenLight"],
                }, {
                    title: 'Revenue',
                    start: new Date(y, m, 3),
                    allDay: true,
                    description: '2345',
                    className: ["event", "bg-color-redLight"],
                }],

                eventRender: function (event, element, icon) {
                    if (!event.description == "") {
                        element.find('.fc-title').append("<br/><span class='ultra-light'>" + event.description +
                            "</span>");
                    }
                    if (!event.icon == "") {
                        element.find('.fc-title').append("<i class='air air-top-right fa " + event.icon +
                            " '></i>");
                    }
                },

                windowResize: function (event, ui) {
                    $('#calendar').fullCalendar('render');
                }
            });

            /* hide default buttons */
            $('.fc-right, .fc-center').hide();


            $('#calendar-buttons #btn-prev').click(function () {
                $('.fc-prev-button').click();
                return false;
            });

            $('#calendar-buttons #btn-next').click(function () {
                $('.fc-next-button').click();
                return false;
            });

            $('#calendar-buttons #btn-today').click(function () {
                $('.fc-today-button').click();
                return false;
            });

            $('#mt').click(function () {
                $('#calendar').fullCalendar('changeView', 'month');
            });

            $('#ag').click(function () {
                $('#calendar').fullCalendar('changeView', 'agendaWeek');
            });

            $('#td').click(function () {
                $('#calendar').fullCalendar('changeView', 'agendaDay');
            });

        })

    </script>

@endsection