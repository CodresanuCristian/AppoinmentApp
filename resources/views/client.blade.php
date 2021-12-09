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
                $("td").click(function(){
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


                $('input[type=checkbox').click(function(){
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
                        data: {date: $("#date").val(), servicesTime: GetServicesTime()},
                        success: function(schedule){
                            ShowClock(schedule.db);
                        }
                    });

                    $('#hour').prop('disabled', false);
                    $('#hour').empty();



                    function ShowClock(db)
                    {

                        // READ APPOINTMENT CLOCK FROM DATABASE
                        var sh=[], sm=[], fh=[], fm=[];
                        for (let i=0; i<db.length; i++){
                            sh[i] = parseInt(db[i]['start_hour']);
                            sm[i] = parseInt(db[i]['start_minute']);
                            fh[i] = parseInt(db[i]['finish_hour']);
                            fm[i] = parseInt(db[i]['finish_minute']);
                        }



                        // CREATE A CLOCK MATRIX   9, 10, ... , 17 | -> hour
                        // -----------------------------------------
                        //                         0,  0, ... ,  0 |
                        //                         5,  5, ... ,  5 | -> minutes
                        //                        ................ |
                        //                        50, 50, ...., 50 |
                        //                        55, 55, ... , 55 | 
                        // ------------------------------------------------------  
                        // Ex: clock[9][1] = 5      -> it means 09:05
                        //     clock[17][10] = 50   -> it means 17:50
                        var clock = new Array(18);
                        const hour = 9;
                        var minuteStep = 5;
                        for (let i=hour; i<clock.length; i++)
                            clock[i] = new Array(12);

                        for (let i=hour; i<clock.length; i++){
                            var minute = 0;
                            for (let j=0; j<clock[i].length; j++){
                                clock[i][j] = minute;
                                minute = minute + minuteStep;
                            }
                        }


                        
                        // THE RANGE WICH APPOINTMENTS ARE MADE WILL MARK WITH -1
                        for (var i=0; i<db.length; i++){
                            if (sh[i] == fh[i]){
                                var aux_min = sm[i]/minuteStep;
                                while (aux_min <= fm[i]/minuteStep){
                                    clock[sh[i]][aux_min] = -1;
                                    aux_min++;
                                }
                            }else if (sh[i] != fh[i]){
                                var aux_hour = sh[i];
                                var aux_min = sm[i]/minuteStep;
                                while (aux_hour <= fh[i]){
                                    clock[aux_hour][aux_min] = -1;
                                    if (aux_min == 11){
                                        aux_min = 0;
                                        aux_hour++;
                                    }
                                    else if (aux_hour == fh[i])
                                        if (aux_min < fm[i]/minuteStep)
                                            aux_min++;
                                        else
                                            aux_hour++;
                                    else
                                        aux_min++;
                                }
                            }
                        }


                        // CREATE OPTIONS FOR HOUR AND MINUTES SELECTOR
                        var opHour = document.createElement('option');
                            opHour.value = '0';
                            opHour.innerHTML = 'Choose hour';
                            opHour.selected = 'select';
                            opHour.disabled = 'disable';
                            document.getElementById("hour").appendChild(opHour);

                        var rangeTimeByServices = parseInt($('#serviceTime').val()) / minuteStep;
                        var rangeTimeStep = 0;
                        for (let i=clock.length-1; i>=hour; i--)
                            for (let j=clock[i].length-1; j>=0; j--){
                                if (clock[i][j] == -1) rangeTimeStep = 0;
                                else                   rangeTimeStep++;

                                
                                if (rangeTimeStep >= rangeTimeByServices){
                                    opHour = document.createElement('option');
                                    if (i == 9){
                                        opHour.value = '09';
                                        opHour.innerHTML = '09';
                                    }
                                    else{
                                        opHour.value = i;
                                        opHour.innerHTML = i;
                                    }
                                    document.getElementById("hour").appendChild(opHour);
                                    break;
                                }
                            }
                            
                        

                        $('#hour').on('change',function(){
                            $('#minute').empty();
                            var hour_selected = parseInt($('#hour option:selected').val());
                            
                            var opMin = document.createElement('option');
                            opMin.value = '0';
                            opMin.innerHTML = 'Choose minute';
                            opMin.selected = 'select';
                            opMin.disabled = 'disable';
                            document.getElementById("minute").appendChild(opMin);

                                for (let j=clock[hour_selected].length-1; j>=0; j--){
                                    if (clock[hour_selected][j] == -1) rangeTimeStep = 0;
                                    else                               rangeTimeStep++;

                                
                                    if (rangeTimeStep >= rangeTimeByServices){
                                        opMin = document.createElement('option');
                                        if (j == 0){
                                            opMin.value = '00';
                                            opMin.innerHTML = '00';
                                        }else if (j == 1){
                                            opMin.value = '05';
                                            opMin.innerHTML = '05';
                                        }else{
                                            opMin.value = clock[hour_selected][j];
                                            opMin.innerHTML = clock[hour_selected][j];
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
    