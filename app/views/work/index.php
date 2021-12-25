
<div class="container">
    <div class="row">
        <div class="col-12 text-center">
            <h1>WORK BOARD</h1>
        </div>

    </div>

    <div class="row">
        <div class="col-12">
            <div id="calendar-work"></div>
        </div>
    </div>
    <!-- BEGIN MODAL -->
    <div class="modal fade" id="event-modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header text-center border-bottom-0 d-block">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Add New Work</h4>
                </div>
                <div class="modal-body"></div>
                <div id="alert-msg" class="d-none"><div class="alert alert-danger" role="alert"></div></div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light waves-effect" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success save-event waves-effect waves-light">Save</button>
                    <button type="button" class="btn btn-danger delete-event waves-effect waves-light" data-dismiss="modal">Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    commands.push(function () {
        var element = document.getElementById('calendar-work');
        $.ajax({
            method: "GET",
            url: "/work/getList",
            dataType: 'json',
            success: function (res) {
                var list_work = res.data;
                var status_option = res.html_option_status;
                var calendar = new FullCalendar.Calendar(element,{
                    events: list_work,
                    selectable: true,
                    editable: true,
                    initialView: 'dayGridMonth',
                    headerToolbar: {
                        left: 'prev,next',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    select:function (info) {
                        $('#event-modal').modal({
                            backdrop: 'static'
                        });
                        $('#event-modal').find('.delete-event').hide().end().find('.create-event').show();
                        var form = $("<form></form>");
                        form.append("<div class='row'></div>");
                        form.find(".row")
                            .append("<div class='col-md-6'><div class='form-group'><label class='control-label'>Work Name</label><input class='form-control' placeholder='Insert Work Name' type='text' name='work_name'/></div></div>")
                            .append("<div class='col-md-6'><div class='form-group'><label class='control-label'>Status</label><select class='form-control' name='status'></select></div></div>")
                            .find("select[name=status]")
                            .append(status_option)
                            .append("</div></div>");

                        $('#event-modal').find('.modal-body').empty().prepend(form);

                        $('.save-event').unbind('click').on('click', function () {
                            var work_name = $('input[name=work_name]').val();
                            $('#alert-msg .alert').text("");
                            $('#alert-msg').addClass("d-none");
                            if(work_name.length === 0) {
                                $('#alert-msg .alert').text("Work name is required");
                                $('#alert-msg').removeClass("d-none");
                                return;
                            }

                            var data = {
                                title: work_name,
                                start: info.startStr,
                                end: info.endStr,
                                status: $("select[name='status'] option:selected").val()
                            };
                            $.ajax({
                                method: "POST",
                                url: "/work/insert",
                                dataType: 'json',
                                data:data,
                                success: function (res) {
                                    if(res.status === true){
                                        var new_work = calendar.addEvent({
                                            title: work_name,
                                            start: info.startStr,
                                            end: info.endStr,
                                            color: $("select[name='status'] option:selected").data("color")
                                        });
                                        new_work.setProp('id',res.new_id);
                                        new_work.setExtendedProp('status',data.status);
                                    }

                                }
                            });
                        });
                    },
                    eventDrop: function(info){
                        var this_event = info.event;
                        var work_status = this_event.extendedProps.status;
                        var data = {
                            id: this_event.id,
                            title: this_event.title,
                            start: this_event.startStr,
                            end: this_event.endStr,
                            status: work_status
                        };
                        $.ajax({
                            method: "POST",
                            url: "/work/update",
                            dataType: 'json',
                            data:data,
                            success: function (res) {
                                console.log(res);
                            }
                        });

                    },
                    eventResize:function (info) {
                        var this_event = info.event;
                        var work_status = this_event.extendedProps.status;
                        var data = {
                            id: this_event.id,
                            title: this_event.title,
                            start: this_event.startStr,
                            end:this_event.endStr,
                            status: work_status
                        };

                        $.ajax({
                            method: "POST",
                            url: "/work/update",
                            dataType: 'json',
                            data:data,
                            success: function (res) {
                                console.log(res);
                            }
                        });
                    },
                    eventClick: function (info) {
                        var this_event = info.event;
                        var work_status = this_event.extendedProps.status;
                        $('#event-modal').modal({
                            backdrop: 'static'
                        });
                        $('#event-modal').find('.delete-event').show();
                        $('#event-modal .modal-title').text("Update Work");
                        var form = $("<form></form>");
                        form.append("<div class='row'></div>");
                        form.find(".row")
                            .append("<div class='col-md-6'><div class='form-group'><label class='control-label'>Work Name</label><input class='form-control' value='"+this_event.title+"' type='text' name='work_name'/></div></div>")
                            .append("<div class='col-md-6'><div class='form-group'><label class='control-label'>Status</label><select class='form-control' name='status'></select></div></div>")
                            .find("select[name=status]")
                            .append(status_option)
                            .append("</div></div>");

                        $('#event-modal').find('.modal-body').empty().prepend(form);
                        $('select[name=status]').val(work_status).change();
                        console.log(work_status);
                        var work_color = $('select[name=status] option:selected').data("color");

                        /*UPDATE WORK*/
                        $('.save-event').unbind('click').on('click', function () {
                            var work_name = $('input[name=work_name]').val();
                            work_status = $('select[name=status] option:selected').val();
                            work_color = $('select[name=status] option:selected').data("color");
                            $('#alert-msg .alert').text("");
                            $('#alert-msg').addClass("d-none");
                            if(work_name.length === 0) {
                                $('#alert-msg .alert').text("Work name is required");
                                $('#alert-msg').removeClass("d-none");
                                return;
                            }

                            var data = {
                                id: this_event.id,
                                title: work_name,
                                start: this_event.startStr,
                                end:this_event.endStr,
                                status: work_status
                            };

                            $.ajax({
                                method: "POST",
                                url: "/work/update",
                                dataType: 'json',
                                data:data,
                                success: function (res) {
                                    if(res.status === true){
                                        console.log(data);
                                        this_event.setProp('title',work_name);
                                        this_event.setProp('color',work_color);
                                        this_event.setExtendedProp('status',work_status);

                                    }
                                }
                            });
                        });

                        /*DELETE WORK*/
                        $('.delete-event').unbind('click').on('click', function () {
                            $.ajax({
                                method: "POST",
                                url: "/work/delete",
                                dataType: 'json',
                                data:{id: this_event.id},
                                success: function (res) {
                                    if(res.status === true){
                                        this_event.remove();
                                    }
                                }
                            });
                        });
                    }
                });

                calendar.render();
            }
        });


    });
</script>

