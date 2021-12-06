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
                    if ($(this).attr("value") == "allowed")
                        $("#date").val(($(this).attr("id")+" "+calendar['month']+" "+calendar['year']));
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
            });
        </script>
    </body>
</html>
    