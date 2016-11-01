<?php
 use yii\helpers\Url;
 
?>
<body>
    <br>
    <div class="shadow"></div>
    <div class="hideSkipLink"></div>
    <div class="main">

        <div style="width:160px; float:left;">
            <div id="nav"></div>
        </div>

        <div style="margin-left: 160px;">
            <div class="form-group">

                <div class="col-md-12">
                    <div class="form-group">
                        <label for="filter" class="col-md-2 control-label">Show Rooms</label>
                        <div class="col-md-2">
                            <select id="filter" class="form-control input-sm">
                                <option value="0">All</option>
                                <option value="1">Single</option> 
                                <option value="2">Double</option>
                                <option value="4">Family</option>
                            </select>

                        </div>
                        <label for="timerange" class="col-md-2 control-label">Time Range</label>
                        <div class="col-md-2">
                            <select id="timerange" class="form-control input-sm">
                                <option value="week">Week</option>
                                <option value="month" selected>Month</option>
                            </select>
                        </div>

                        <label for="autocellwidth" class="col-md-2 control-label">Auto Cell Width</label>
                        <div class="col-md-1">
                            <input type="checkbox" id="autocellwidth" class="">
                        </div>
                    </div>
                </div>
            </div>

            <br /><br />

            <div id="dp"></div>

            <div class="space">
                <a href="#" id="add-room">Add Room</a>
            </div>
            <div class="space">
                <a href="#" id="test_1">Load Resources (test)</a>
            </div>

        </div>

        <script type="text/javascript">

            $('#test_1').click(function(event) {
                event.preventDefault();
                loadEvents();

            });

        </script>

        <script type="text/javascript">
            var nav = new DayPilot.Navigator("nav");
            nav.selectMode = "month";
            nav.showMonths = 3;
            nav.skipMonths = 3;
            nav.onTimeRangeSelected = function(args) {
                loadTimeline(args.start);
                loadEvents();
            };
            nav.init();

            $("#timerange").change(function() {
                switch (this.value) {
                    case "week":
                        dp.days = 7;
                        nav.selectMode = "Week";
                        nav.select(nav.selectionDay);
                        break;
                    case "month":
                        dp.days = dp.startDate.daysInMonth();
                        nav.selectMode = "Month";
                        nav.select(nav.selectionDay);
                        break;
                }
            });

            $("#autocellwidth").click(function() {
                dp.cellWidth = 40;  // reset for "Fixed" mode
                dp.cellWidthSpec = $(this).is(":checked") ? "Auto" : "Fixed";
                dp.update();
            });

            $("#add-room").click(function(ev) {
                ev.preventDefault();
                var modal = new DayPilot.Modal();
                modal.className = 'modal_window new_room_modal';
                modal.onClosed = function(args) {
                    loadResources();
                };
                modal.showUrl('<?= Url::to(['/dashboard/new-room']) ?>');
            });
        </script>

        <script>
            var dp = new DayPilot.Scheduler("dp");


            dp.allowEventOverlap = false;

            //dp.scale = "Day";
            //dp.startDate = new DayPilot.Date().firstDayOfMonth();
            dp.days = dp.startDate.daysInMonth();
            loadTimeline(DayPilot.Date.today().firstDayOfMonth());

            dp.eventDeleteHandling = "Update";

            dp.timeHeaders = [
                {groupBy: "Month", format: "MMMM yyyy"},
                {groupBy: "Day", format: "d"}
            ];

            dp.eventHeight = 50;
            dp.bubble = new DayPilot.Bubble({});

            dp.rowHeaderColumns = [
                {title: "Room", width: 80},
                {title: "Capacity", width: 80},
                {title: "Status", width: 80}
            ];

            dp.onBeforeResHeaderRender = function(args) {
                var beds = function(count) {
                    return count + " bed" + (count > 1 ? "s" : "");
                };

                //console.log(args);

                args.resource.columns[0].html = beds(args.resource.capacity);
                args.resource.columns[1].html = args.resource.status;
                switch (args.resource.status) {
                    case "Dirty":
                        args.resource.cssClass = "status_dirty";
                        break;
                    case "Cleanup":
                        args.resource.cssClass = "status_cleanup";
                        break;
                }

                args.resource.areas = [{
                        top: 3,
                        right: 4,
                        height: 14,
                        width: 14,
                        action: "JavaScript",
                        js: function(r) {
                            var modal = new DayPilot.Modal();
                            modal.className = 'modal_window edit_room_modal';
                            modal.onClosed = function(args) {
                                loadResources();
                            };
                            modal.showUrl('<?= Url::to(['/dashboard/edit-room']) ?>' + "?id=" + r.id);
                        },
                        v: "Hover",
                        css: "icon icon-edit",
                    }];
            };

            // http://api.daypilot.org/daypilot-scheduler-oneventmoved/
            dp.onEventMoved = function(args) {
                $.post('<?= Url::to(['/dashboard/move-rsv']) ?>',
                        {
                            id: args.e.id(),
                            newStart: args.newStart.toString(),
                            newEnd: args.newEnd.toString(),
                            newResource: args.newResource
                        },
                function(data) {
                    dp.message(data.message);
                });
            };

            // http://api.daypilot.org/daypilot-scheduler-oneventresized/
            dp.onEventResized = function(args) {
                $.post('<?= Url::to(['/dashboard/resize-rsv']) ?>',
                        {
                            id: args.e.id(),
                            newStart: args.newStart.toString(),
                            newEnd: args.newEnd.toString()
                        },
                function() {
                    dp.message("Resized.");
                });
            };

            dp.onEventDeleted = function(args) {
                $.post('<?= Url::to(['/dashboard/destroy-rsv']) ?>',
                        {
                            id: args.e.id()
                        },
                function() {
                    dp.message("Deleted.");
                });
            };

            // event creating
            // http://api.daypilot.org/daypilot-scheduler-ontimerangeselected/
            dp.onTimeRangeSelected = function(args) {
                //var name = prompt("New event name:", "Event");
                //if (!name) return;

                //alert(args.start);
                //$("#myModal").modal('show');


                var modal = new DayPilot.Modal();
                modal.className = 'modal_window new_rsv_modal';
                modal.closed = function() {
                    dp.clearSelection();

                    // reload all events
                    var data = this.result;
                    if (data && data.result === "OK") {
                        loadEvents();
                    }
                };

                modal.showUrl('<?= Url::to(['/dashboard/newrsv']) ?>' + "?start=" + args.start + "&end=" + args.end + "&resource=" + args.resource);

            };

            dp.onEventClick = function(args) {
                var modal = new DayPilot.Modal();
                modal.className = 'modal_window edit_rsv_modal';
                modal.closed = function() {
                    // reload all events
                    var data = this.result;
                    if (data && data.result === "OK") {
                        loadEvents();
                    }
                };
                modal.showUrl('<?= Url::to(['/dashboard/editrsv']) ?>' + "?id=" + args.e.id());
            };

            dp.onBeforeCellRender = function(args) {
                var dayOfWeek = args.cell.start.getDayOfWeek();
                if (dayOfWeek === 6 || dayOfWeek === 0) {
                    args.cell.backColor = "#f8f8f8";
                }
            };

            dp.onBeforeEventRender = function(args) {
                var start = new DayPilot.Date(args.e.start);
                var end = new DayPilot.Date(args.e.end);

                var today = new DayPilot.Date().getDatePart();
                var now = new DayPilot.Date();

                args.e.html = args.e.text + " (" + start.toString("M/d/yyyy") + " - " + end.toString("M/d/yyyy") + ")";

                switch (args.e.status) {
                    case "New":
                        var in2days = today.addDays(1);

                        if (start.getTime() < in2days.getTime()) {
                            args.e.barColor = 'red';
                            args.e.toolTip = 'Expired (not confirmed in time)';
                        }
                        else {
                            args.e.barColor = 'orange';
                            args.e.toolTip = 'New';
                        }
                        break;
                    case "Confirmed":
                        var arrivalDeadline = today.addHours(18);

                        if (start.getTime() < today.getTime() || (start.getTime() === today.getTime() && now.getTime() > arrivalDeadline.getTime())) { // must arrive before 6 pm
                            args.e.barColor = "#f41616";  // red
                            args.e.toolTip = 'Late arrival';
                        }
                        else {
                            args.e.barColor = "green";
                            args.e.toolTip = "Confirmed";
                        }
                        break;
                    case 'Arrived': // arrived
                        var checkoutDeadline = today.addHours(10);

                        if (end.getTime() < today.getTime() || (end.getTime() === today.getTime() && now.getTime() > checkoutDeadline.getTime())) { // must checkout before 10 am
                            args.e.barColor = "#f41616";  // red
                            args.e.toolTip = "Late checkout";
                        }
                        else
                        {
                            args.e.barColor = "#1691f4";  // blue
                            args.e.toolTip = "Arrived";
                        }
                        break;
                    case 'CheckedOut': // checked out
                        args.e.barColor = "gray";
                        args.e.toolTip = "Checked out";
                        break;
                    default:
                        args.e.toolTip = "Unexpected state";
                        break;
                }

                args.e.html = args.e.html + "<br /><span style='color:gray'>" + args.e.toolTip + "</span>";

                var paid = args.e.paid;
                var paidColor = "#aaaaaa";

                args.e.areas = [
                    {bottom: 10, right: 4, html: "<div style='color:" + paidColor + "; font-size: 8pt;'>Paid: " + paid + "%</div>", v: "Visible"},
                    {left: 4, bottom: 8, right: 4, height: 2, html: "<div style='background-color:" + paidColor + "; height: 100%; width:" + paid + "%'></div>", v: "Visible"}
                ];

            };


            dp.init();

            loadResources();
            loadEvents();

            function loadTimeline(date) {
                dp.scale = "Manual";
                dp.timeline = [];
                var start = date.getDatePart().addHours(12);

                for (var i = 0; i < dp.days; i++) {
                    dp.timeline.push({start: start.addDays(i), end: start.addDays(i + 1)});
                }
                dp.update();
            }

            function loadEvents() {
                var start = dp.visibleStart();
                var end = dp.visibleEnd();

                var csrfToken = $('meta[name="csrf-token"]').attr("content");

                $.post('<?= Url::to(['/dashboard/events']) ?>',
                        {
                            start: start.toString(),
                            end: end.toString(),
                            _csrf: csrfToken
                        },
                function(data) {
                    dp.events.list = data;
                    dp.update();
                }
                );
            }

            function loadResources() {
                var csrfToken = $('meta[name="csrf-token"]').attr("content");
                $.post('<?= Url::to(['/dashboard/rooms']) ?>',
                        {capacity: $("#filter").val(), _csrf: csrfToken},
                function(data) {
                    dp.resources = data;
                    console.log(dp.resources[0]);
                    dp.update();
                });
            }

            $(document).ready(function() {
                $("#filter").change(function() {
                    loadResources();
                });
            });

        </script>

    </div>
    <div class="clear">
    </div>
