<!DOCTYPE>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Client</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="/css/calendar.css">
        <link rel="stylesheet" type="text/css" href="/css/newappform.css">
    </head>

    <body>
        <div class="container-lg text-center border border-black">
            <h4>Make an appointment</h4>
            <!-- CALENDAR HEADER -->
            <div class="d-flex justify-content-center align-items-center">
                <h1><a class="arrow" href="/{{ $calendar['monthDigit']-1 }}-{{ $calendar['year'] }}">&larr;</a></h1>
                <h2 class="calendar-header">{{ $calendar['month']}} {{ $calendar['year'] }}</h2>
                <h1><a class="arrow" href="/{{ $calendar['monthDigit']+1 }}-{{ $calendar['year'] }}">&rarr;</a></h1>
            </div>
            <!-- CREATE CALENDAR -->
            @include('calendar')
            <!-- CREATE NEW APPOINTMENT FORM -->
            @include('newappointment')
        </div>



        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="/js/client.js"></script>
        <script type="text/javascript">
            $(document).ready(function(){
                var calendar = {!! json_encode($calendar) !!};
                var current_month = new Date().getMonth()+1;
                var current_year = new Date().getFullYear();
                


                // MARK TODAY'S TILE
                for(let day=1; day<=calendar['daysInMonth']; day++)
                    if (((day == calendar['today']) && (current_month == calendar['monthDigit'])) && (current_year == calendar['year']))
                        $("#"+day).css({'background':'cornflowerblue', 'color':'white', 'font-weight':'bold'});


                // CHANGE THE CURSOR ACCORDING TO THE VALIDITY OF DAYS
                for (let day=1; day<=calendar['daysInMonth']; day++)
                {   
                    if (calendar['year'] < current_year){
                        $("#"+day).css({'cursor':'not-allowed'});
                        $("#"+day).attr("value","not-allowed");
                    }
                    else if (calendar['year'] == current_year){
                        if (calendar['monthDigit'] < current_month){
                            $("#"+day).css({'cursor':'not-allowed'});
                            $("#"+day).attr("value","not-allowed");
                        }
                        else if ((calendar['monthDigit'] == current_month) && (day < calendar['today'])){
                            $("#"+day).css({'cursor':'not-allowed'});
                            $("#"+day).attr("value","not-allowed");
                        }
                    }
                }


                // CLICK AND HOVER TILES
                $('table').on('click','td', function(){
                    if ($(this).attr("value") == "allowed"){
                        $("#date").val(($(this).attr("id")+" "+calendar['month']+" "+calendar['year']));
                        if ($('#serviceTime').val() != 0)
                            GetSchedule();
                    }
                });

                $("td").mouseenter(function(){
                    if ($(this).attr("value") == "allowed")
                        $("#"+$(this).attr("id")).css({'background':'#3366ff', 'color':'white', 'font-weight':'bold', 'cursor':'pointer'});
                    else
                    $("#"+$(this).attr("id")).css({'cursor':'not-allowed'});
                });

                $("td").mouseleave(function(){
                    if ($(this).attr("value") == "allowed"){
                        if ((($(this).attr("id") == calendar['today']) && (current_month == calendar['monthDigit'])) && (current_year == calendar['year']))
                            $("#"+$(this).attr("id")).css({'background':'cornflowerblue', 'color':'white', 'font-weight':'bold'});
                        else
                            $("#"+$(this).attr("id")).css({'background':'whitesmoke', 'color':'black', 'font-weight':'normal'});
                    }
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
            });
        </script>
    </body>
</html>
    