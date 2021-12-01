    <!DOCTYPE>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Dashboard</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="css/mystyle.css">
    </head>

    <body>
        <div class="container-lg text-center border border-black">
            <h4>Make an appointment</h4>

            <!-- CALENDAR HEADER -->
            <div class="d-flex justify-content-center align-items-center">
                <h1><a href="/{{ $calendar['monthDigit']-1 }}-{{ $calendar['year'] }}">&larr;</a></h1>
                <h2>{{ $calendar['month']}} {{ $calendar['year'] }}</h2>
                <h1><a href="/{{ $calendar['monthDigit']+1 }}-{{ $calendar['year'] }}">&rarr;</a></h1>
            </div>


            <!-- TABLE -->
            <div class="calendar d-flex justify-content-center">
                <table>
                    <tr>
                        <th>Sunday</th>
                        <th>Monday</th>
                        <th>Tuesday</th>
                        <th>Wednesday</th>
                        <th>Thursday</th>
                        <th>Friday</th>
                        <th>Saturday</th>
                    </tr>
                    @for ($day=1-$calendar['skipDays'], $dayTile=1; $day<=$calendar['daysInMonth']; $day++, $dayTile++)
                        @if (($dayTile % 7 == 1) && ($day >= $calendar['skipDays']))
                            <tr><td value="not-allowed" id="{{ $day }}">{{ $day }}</td>
                        @elseif ($day < 1)
                            @if ($dayTile == 1)
                                <tr><td value="not-allowed"></td>
                            @else
                                <td value="not-allowed"></td>
                            @endif
                        @elseif ($dayTile % 7 == 0)
                            <td value="not-allowed" id="{{ $day }}">{{ $day }}</td></tr>
                        @else
                            <td value="allowed" id="{{ $day }}">{{ $day }}</td>
                        @endif
                    @endfor
                </table>
            </div>
        </div>

        <!-- FORM -->
        <div class="container mt-5 border border-black">
            <h1 class="text-center">Form</h1>
            <form method="POST" action="/appointment"> 
                @csrf
                <input type="text" name="name" placeholder="Your name">
                @error('name') <div style="color:red">{{ $message }}</div> @enderror
                <input type="tel" name="phone" placeholder="Your phone">
                @error('phone') <div style="color:red">{{ $message }}</div> @enderror
                <select name="contractor">
                    <option value="0" selected disabled>Choose the contractor</option>
                    <option value="1">Contractor 1</option>
                    <option value="2">Contractor 2</option>
                    <option value="3">Contractor 3</option>
                </select>
                @error('contractor') <div style="color:red">{{ $message }}</div> @enderror
                <input type="text" name="date" id="date" placeholder="Choose the date" readonly>
                @error('date') <div style="color:red">{{ $message }}</div> @enderror
                <select name="hour">
                    <option value="0" selected disabled>Choose hour</option>
                    <option value="1">09</option>
                    <option value="2">10</option>
                    <option value="3">11</option>
                    <option value="4">12</option>
                    <option value="5">13</option>
                    <option value="6">14</option>
                    <option value="7">15</option>
                    <option value="8">16</option>
                    <option value="9">17</option>
                </select>
                @error('hour') <div style="color:red">{{ $message }}</div> @enderror
                <select name="minute">
                    <option value="0" selected disabled>Choose minute</option>
                    <option value="1">00</option>
                    <option value="2">05</option>
                    <option value="3">10</option>
                    <option value="4">15</option>
                    <option value="5">20</option>
                    <option value="6">25</option>
                    <option value="7">30</option>
                    <option value="8">35</option>
                    <option value="9">40</option>
                    <option value="10">45</option>
                    <option value="11">50</option>
                    <option value="12">55</option>
                </select>
                @error('minute') <div style="color:red">{{ $message }}</div> @enderror
                
                <div class="container d-flex flex-wrap justify-content-around">
                    <p id="service-text-1"><input type="checkbox" id="service-1" name="services"> Service 1</p>
                    <p id="service-text-2"><input type="checkbox" id="service-2" name="services"> Service 2</p>
                    <p id="service-text-3"><input type="checkbox" id="service-3" name="services"> Service 3</p>
                    <p id="service-text-4"><input type="checkbox" id="service-4" name="services"> Service 4</p>
                    <p id="service-text-5"><input type="checkbox" id="service-5" name="services"> Service 5</p>
                    <p id="service-text-6"><input type="checkbox" id="service-6" name="services"> Service 6</p>            
                </div>
                @error('services') <div style="color:red">{{ $message }}</div> @enderror
                <button type="submit" class="btn btn-primary">Make Appointment</button>
            </form>
        </div>




        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="js/myscript.js"></script>
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
                        else{
                            $("#"+day).css({'cursor':'pointer'});
                            $("#"+day).attr("value","allowed");
                        }
                    }
                    else{
                        $("#"+day).css({'cursor':'pointer'});
                        $("#"+day).attr("value","allowed");
                    }
                }



                // CLICK AND HOVER TILES
                $("td").click(function(){
                    if ($(this).attr("value") == "allowed")
                        $("#date").val(($(this).attr("id")+" "+calendar['month']+" "+calendar['year']));
                });

                $("td").mouseenter(function(){
                    if ($(this).attr("value") == "allowed")
                        $("#"+$(this).attr("id")).css({'background':'#3366ff', 'color':'white', 'font-weight':'bold'});
                });

                $("td").mouseleave(function(){
                    if ($(this).attr("value") == "allowed"){
                        if ((($(this).attr("id") == calendar['today']) && (current_month == calendar['monthDigit'])) && (current_year == calendar['year']))
                            $("#"+$(this).attr("id")).css({'background':'cornflowerblue', 'color':'white', 'font-weight':'bold'});
                        else
                            $("#"+$(this).attr("id")).css({'background':'whitesmoke', 'color':'black', 'font-weight':'normal'});
                    }
                });


                
                // GROUPING CHECKBOXES TO SEND A SINGLE VALUE TO THE DATABASE
                $('input[type=checkbox]').click(function(){
                    var service_value = '';
                    
                    for (let i=1; i<=$('input[type=checkbox]').length; i++)
                        if ($("#service-"+i).is(':checked'))
                            service_value = service_value + $("#service-text-"+i).text()+"\n";
                    
                    $("input[type=checkbox]").attr("value", service_value);
                });
            });
        </script>
    </body>
</html>
    