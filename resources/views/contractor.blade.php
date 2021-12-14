<!DOCTYPE>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <title>Contractor</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="/css/calendar.css">
        <link rel="stylesheet" type="text/css" href="/css/contractor.css">
        <link rel="stylesheet" type="text/css" href="/css/newappform.css">
    </head>

    <body>
        <h4>Welcome {{ session('username') }}</h4>
        <a href="/logout" class="text-white"><button type="button" class="btn btn-dark">Log out</button></a>

        <div class="container-lg text-center border border-black">
            <!-- CALENDAR HEADER -->
            <div class="d-flex justify-content-center">
                <h1><a class="arrow" href="/contractor/{{ $calendar['monthDigit']-1 }}-{{ $calendar['year'] }}">&larr;</a></h1>
                <h2 class="calendar-header">{{ $calendar['month']}} {{ $calendar['year'] }}</h2>
                <h1><a class="arrow" href="/contractor/{{ $calendar['monthDigit']+1 }}-{{ $calendar['year'] }}">&rarr;</a></h1>
            </div>
            @include('calendar')
            <!-- OPTIONS BUTTON -->
            <div class="mt-5 pt-5">
                <button type="button" class="btn btn-primary m-3" id="addNewApp">Add New Appointment</button>
                <button type="button" class="btn btn-primary m-3" id="contractorDetails">Contractor Details</button>
            </div>
            <!-- NEW APPOINTMENT FORM -->
            <div class="newappform">
                @include('newappointment')
            </div>
            <!-- CONTRACTOR DETAILS FORM -->
            <div class="contractordetailsform">
                @include('contractordetails')
            </div>
        </div>


        <!-- APPOINTMENT LIST -->
        <div class="appList-window">
            <h5 class="text-right m-1" id="close-appwindow" style="cursor:pointer;">close</h5>
            <h3 class="appList-header text-center m-4"></h3>
            <div class="appList" id="appList"></div>
        </div>
        
        
        <!-- APPOINMENT LIST -> EDIT FORM -->
        <div class="appList-editForm">
            <h5 class="text-right m-1" id="close-editForm" style="cursor:pointer;">close</h5>
            <form method="post" action="/editappointment" id="editForm" class="text-center">
                @csrf
                <input type="text" name="start_hour" placeholder="Start hour">
                <input type="text" name="start_minute" placeholder="Start minute">
                <input type="text" name="finish_hour" placeholder="Finish hour">
                <input type="text" name="finish_minute" placeholder="finish minute">
                <input type="text" name="name" placeholder="Name">
                <input type="tel" name="phone" placeholder="Phone">
                <div class="container d-flex flex-wrap justify-content-around">
                    <p id="edit-service-text-1"><input type="checkbox" id="edit-service-1" class="edit-input" name="services"> Service 1</p>
                    <p id="edit-service-text-2"><input type="checkbox" id="edit-service-2" class="edit-input" name="services"> Service 2</p>
                    <p id="edit-service-text-3"><input type="checkbox" id="edit-service-3" class="edit-input" name="services"> Service 3</p>
                    <p id="edit-service-text-4"><input type="checkbox" id="edit-service-4" class="edit-input" name="services"> Service 4</p>
                    <p id="edit-service-text-5"><input type="checkbox" id="edit-service-5" class="edit-input" name="services"> Service 5</p>
                    <p id="edit-service-text-6"><input type="checkbox" id="edit-service-6" class="edit-input" name="services"> Service 6</p>            
                </div>
                <input type="text" name="id" id="input_id" hidden>
                <button type="submit" class="btn btn-warning" id="updateAppBtn">Apply</button>
            </form>
        </div>



        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="/js/contractor.js"></script>
        <script type="text/javascript">
            $(document).ready(function(){
                var calendar = {!! json_encode($calendar) !!};
                var client = {!! json_encode($db_appointment) !!};
                var current_month = new Date().getMonth()+1;
                var current_year = new Date().getFullYear();

                ShowContractorDetails();
            
                // MARK TODAY'S TILE
                for(let day=1; day<=calendar['daysInMonth']; day++)
                    if (((day == calendar['today']) && (current_month == calendar['monthDigit'])) && (current_year == calendar['year']))
                        $("#"+day).css({'background':'cornflowerblue', 'color':'white', 'font-weight':'bold'});



                // HOVER TILES
                $("td").mouseenter(function(){
                    $("#"+$(this).attr("id")).css({'background':'#3366ff', 'color':'white', 'font-weight':'bold'});
                });

                $("td").mouseleave(function(){
                    if ((($(this).attr("id") == calendar['today']) && (current_month == calendar['monthDigit'])) && (current_year == calendar['year']))
                        $("#"+$(this).attr("id")).css({'background':'cornflowerblue', 'color':'white', 'font-weight':'bold'});
                    else
                        $("#"+$(this).attr("id")).css({'background':'whitesmoke', 'color':'black', 'font-weight':'normal'});
                });



                // OPEN AND CLOSE APPOINTMENT LIST WINDOW  AND NEW APPOINTMENT FORM
                $('#addNewApp').click(function(){
                    $('.newappform').toggle();
                });

                $('#contractorDetails').click(function(){
                    $('.contractordetailsform').toggle();
                });

                $('td').click(function(){
                    if ($('.newappform').is(':visible') || $('.contractordetailsform').is(':visible')){    
                        $("#date").val(($(this).attr("id")+" "+calendar['month']+" "+calendar['year']));
                        $("#adddaysoff").val(($(this).attr("id")+" "+calendar['month']+" "+calendar['year']));
                    }
                });




                // APPOINTMENT LIST - SHOW/HIDE LIST
                $('td').click(function(){
                    if (($('.newappform').is(':visible') == false) && ($('.contractordetailsform').is(':visible')) == false){    
                        var day = $(this).attr('id');
                        var month = calendar['month'];
                        var year = calendar['year'];

                        $.ajax({
                            type:'GET',
                            url: '/showapplist',
                            data: {date_tile: day+' '+month+' '+year},
                            success: function(date_tile){
                                for (let i=0; i<date_tile.list.length; i++)
                                    CreateList(date_tile.list[i]['id'], date_tile.list[i]['start_hour']+':'+date_tile.list[i]['start_minute']+' - '+date_tile.list[i]['finish_hour']+':'+date_tile.list[i]['finish_minute'], date_tile.list[i]['name']+' - '+date_tile.list[i]['services'], date_tile.list[i]['phone']);
                                $('.appList-header').text(day+' '+month);
                                $('.appList-window').show();
                            },
                        });
                    }
                });

                $('.appList-window').on('click','#close-appwindow', function(){
                    $('.appList-window').hide();
                    $('.appList').empty();
                });



                // APPOINTMENT LIST - BUTTONS
                $('.appList').on('click','.deletebtn', function(){
                    $.ajax({
                        type:'GET',
                        url:'/deleteappointment',
                        data: {id:$(this).attr('value')},
                        success: function(){
                            $('.appList-window').hide();
                            $('.appList').empty();
                            alert('Appointment removed');
                        }
                    });
                });
                
                $('.appList').on('click','.editbtn', function(){
                    $('.appList-editForm').show();
                    $('#input_id').attr('value', $(this).attr('value'));
                });

                $('.appList-editForm').on('click','#close-editForm', function(){
                    $('.appList-editForm').hide();
                });

                $('.pservice').click(function(){
                    if ($('#'+$('#'+$(this).attr('id')+' input[type=checkbox]').attr('id')).is(':checked'))
                        $('#'+$('#'+$(this).attr('id')+' input[type=checkbox]').attr('id')).prop("checked", false);
                    else
                        $('#'+$('#'+$(this).attr('id')+' input[type=checkbox]').attr('id')).prop("checked", true);

                    var clock = GetServicesTime();
                    var hour = Math.floor(clock / 60);
                    if (hour != 0){
                        var minute = clock - hour * 60;
                        $('#serviceTime').text('Estimated time: ' + hour +'h '+ minute + 'min');
                    }else{ 
                        minute = clock;
                        $('#serviceTime').text('Estimated time: ' + clock + ' min');
                    }
                    $('#serviceTime').val(clock);

                    if (($('#serviceTime').val() != 0) && ($('#date').val() != ''))
                        GetSchedule();
                    else{
                        $('#hour').prop('disabled', true);
                        $('#minute').prop('disabled', true);
                    }

                });
                


                function GetServicesTime()
                {
                    var servicesTime = 0;

                    if ($('#service-1').is(':checked')) servicesTime = servicesTime + 45;
                    if ($('#service-2').is(':checked')) servicesTime = servicesTime + 35;
                    if ($('#service-3').is(':checked')) servicesTime = servicesTime + 30;
                    if ($('#service-4').is(':checked')) servicesTime = servicesTime + 60;
                    if ($('#service-5').is(':checked')) servicesTime = servicesTime + 20;
                    if ($('#service-6').is(':checked')) servicesTime = servicesTime + 45;                    

                    return servicesTime;
                }


                function GetSchedule()
                {   
                    $.ajax({
                        type: 'GET',
                        url: '/getSchedule',
                        data: {date: $("#date").val(), servicesTime: GetServicesTime(), contractor: $('#contractor option:selected').attr('value')},
                        success: function(schedule){
                            ShowClock(schedule.db);
                        }
                    });

                    $('#hour').prop('disabled', false);
                    $('#hour').empty();
                    $('#minute').prop('disabled', true);
                    $('#minute').empty();



                    function ShowClock(db)
                    {
                        const openingHour = 9;
                        const closingHour = 18;
                        const minStep = 5;
                        var rangeTimeByServices = (parseInt($('#serviceTime').val()) / minStep);

                        // READ APPOINTMENT CLOCK FROM DATABASE
                        var sh=[], sm=[], fh=[], fm=[];
                        for (let i=0; i<db.length; i++){
                            sh[i] = parseInt(db[i]['start_hour']);
                            sm[i] = parseInt(db[i]['start_minute']);
                            fh[i] = parseInt(db[i]['finish_hour']);
                            fm[i] = parseInt(db[i]['finish_minute']);
                        }


                        // CREATE CLOCK ARRAY
                        var clock = [];
                        var m = 0;

                        for (let i=0; i<=((closingHour-openingHour)*(60/minStep)); i++){
                            clock[i] = m;
                            if (m < 55)  m = m + minStep;
                            else         m = 0;
                        }
                        


                        // THE RANGE WICH APPOINTMENTS ARE MADE WILL MARK WITH -1
                        var mark = false;
                        for (let i=0; i<sh.length; i++)
                            for (let j=(sh[i]-openingHour)*12; j<=((fh[i]-openingHour)+1)*12; j++){
                                if (clock[j] > sm[i])
                                    mark = true;

                                if ((clock[j] >= fm[i]) && (j >= (fh[i]-openingHour)*12))
                                    mark = false;

                                if (mark == true)
                                    clock[j] = -1;
                            }

                        
                        // CREATE OPTIONS FOR HOUR AND MINUTES SELECTOR
                        var opHour = document.createElement('option');
                        opHour.value = '0';
                        opHour.innerHTML = 'Choose hour';
                        opHour.selected = 'select';
                        opHour.disabled = 'disable';
                        document.getElementById("hour").appendChild(opHour);

                        for (let i=0; i<clock.length; i++){
                            var acceptRange = true;
                            if (i+rangeTimeByServices < clock.length){
                                for (let j=i; j<=i+rangeTimeByServices; j++){
                                    if (clock[j] == -1){
                                        acceptRange = false;
                                        break;
                                    }
                                }

                                if (acceptRange == true){
                                    opHour = document.createElement('option');
                                    if (openingHour+Math.floor(i/12) == 9){
                                        opHour.value = '09';
                                        opHour.innerHTML = '09';
                                    }
                                    else{ 
                                        opHour.innerHTML = openingHour+Math.floor(i/12);
                                        opHour.value = openingHour+Math.floor(i/12);
                                    }
                                    document.getElementById("hour").appendChild(opHour);
                                    i = ((Math.floor(i/12)+1)*12)-1;
                                }
                            }
                        }

                        $('#hour').on('change',function(){
                            $('#minute').empty();
                            var hour_selected = (parseInt($('#hour option:selected').val()) - openingHour) * 12;

                            
                            var opMin = document.createElement('option');
                            opMin.value = '0';
                            opMin.innerHTML = 'Choose minute';
                            opMin.selected = 'select';
                            opMin.disabled = 'disable';
                            document.getElementById("minute").appendChild(opMin);


                            for (let i=hour_selected; i<=hour_selected+11; i++){
                                var acceptRange = true;
                                    for (let j=i; j<=i+rangeTimeByServices; j++){
                                        if (clock[j] == -1){
                                            acceptRange = false;
                                            break;
                                        }
                                    }

                                    if (acceptRange == true){
                                        opMin = document.createElement('option');
                                        opMin.value = clock[i];
                                        if (i % 12 == 0){
                                            opMin.value = '00';
                                            opMin.innerHTML = '00';
                                        }
                                        else if (i % 12 == 1){
                                            opMin.value = '05';
                                            opMin.innerHTML = '05';
                                        }
                                        else{
                                            opMin.value = clock[i];
                                            opMin.innerHTML = clock[i];
                                        }
                                        document.getElementById("minute").appendChild(opMin);
                                    }
                            }
                            $('#minute').prop('disabled', false);
                            $('#minute option')[0].selected='selected';
                        });
                    }
                }





                // MY FUNCTIONS 
                function ShowContractorDetails()
                {
                    $.ajax({
                        type: 'GET',
                        url: '/showcontractordetails',
                        dataType: 'json',
                        success: function(data){
                            for (let i=0; i<data.contractor.length; i++)
                                CreateOption('deletecontractor', data.contractor[i]['username'], false);
                            for (let i=0; i<data.contractor_details.length; i++){
                                CreateOption('deletedaysoff', data.contractor_details[i]['days_off'], false);
                                CreateOption('deleteholiday',data.contractor_details[i]['start_holiday']+' / '+data.contractor_details[i]['finish_holiday'], true);
                            }
                        }
                    });
                }


                function CreateOption(father, value, holiday)
                {
                    if ((value != '') && (value != ' / ')){
                        var option = document.createElement('option');
                        if (holiday == true)
                            option.value = value.slice(0, value.indexOf(" "));
                        else
                            option.value = value;
                        option.innerHTML = value ;
                        document.getElementById(father).appendChild(option);
                    }
                }


                function CreateList(index, clockVal, titleVal, phoneVal)
                {
                    var newLine = document.createElement('div');
                    newLine.setAttribute('class', 'new-line');
                    newLine.setAttribute('id', 'new-line'+index);
                    document.getElementById('appList').appendChild(newLine);

                    var clock = document.createElement('p');
                    clock.setAttribute('class', 'new-line-data');
                    clock.setAttribute('id', 'clock'+index);
                    clock.innerHTML = clockVal;
                    document.getElementById('new-line'+index).appendChild(clock);

                    var title = document.createElement('p');
                    title.setAttribute('class', 'new-line-data');
                    title.setAttribute('id', 'title'+index);
                    title.innerHTML = titleVal;
                    document.getElementById('new-line'+index).appendChild(title);

                    var phone = document.createElement('p');
                    phone.setAttribute('class', 'new-line-data');
                    phone.setAttribute('id', 'phone'+index);
                    phone.innerHTML = phoneVal;
                    document.getElementById('new-line'+index).appendChild(phone);                    

                    var editBtn = document.createElement('button');
                    editBtn.setAttribute('class', 'btn btn-success editbtn');
                    editBtn.setAttribute('id', 'editbtn'+index);
                    editBtn.setAttribute('value',index);
                    editBtn.innerHTML = "Edit";
                    document.getElementById('new-line'+index).appendChild(editBtn);

                    var deleteBtn = document.createElement('button');
                    deleteBtn.setAttribute('class', 'btn btn-danger deletebtn');
                    deleteBtn.setAttribute('id', 'deletebtn'+index);
                    deleteBtn.setAttribute('value',index);
                    deleteBtn.innerHTML = "Delete";
                    document.getElementById('new-line'+index).appendChild(deleteBtn);
                }
            });
        </script>
    </body>
</html>