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
                <h1><a href="/{{ $calendar['monthNow']-1 }}">&larr;</a></h1>
                <h2>{{ $calendar['month']}} {{ $calendar['year'] }}</h2>
                <h1><a href="/{{ $calendar['monthNow']+1 }}">&rarr;</a></h1>
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
                    @for ($day=1-$calendar['skipDays'], $dayBox=1; $day<=$calendar['daysInMonth']; $day++, $dayBox++)
                        @if (($dayBox % 7 == 1) && ($day >= $calendar['skipDays']))
                            <tr><td id="{{ $day }}">{{ $day }}</td>
                        @elseif ($day < 1)
                            @if ($dayBox == 1)
                                <tr><td></td>
                            @else
                                <td></td>
                            @endif
                        @elseif ($dayBox % 7 == 0)
                            <td id="{{ $day }}">{{ $day }}</td></tr>
                        @else
                            <td id="{{ $day }}">{{ $day }}</td>
                        @endif
                    @endfor
                </table>
            </div>
        </div>

        <!-- FORM -->
        <div class="container mt-5">
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
                <div class="container d-flex border border-black flex-wrap justify-content-around">
                    <p><input type="checkbox" name="services1"> Services 1</p>
                    <p><input type="checkbox" name="services2"> Services 2</p>
                    <p><input type="checkbox" name="services3"> Services 3</p>
                    <p><input type="checkbox" name="services4"> Services 4</p>
                    <p><input type="checkbox" name="services5"> Services 5</p>
                    <p><input type="checkbox" name="services6"> Services 6</p>            
                </div>
                <input type="text" name="date" placeholder="Choose the date">
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
                @error('date') <div style="color:red">{{ $message }}</div> @enderror
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
                <button type="submit" class="btn btn-primary">Make Appointment</button>
            </form>
        </div>




        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="js/myscript.js"></script>
        <script>
            $(document).ready(function(){
                var calendar = {!! json_encode($calendar) !!};
                var date = new Date();
                var month = date.getMonth()+1;


                // SHOW CURRENT DAY IN CALENDAR
                if (calendar['monthNow'] == month)
                    $("#"+calendar['today']).css({'background':'cornflowerblue', 'color':'white', 'font-weight':'bold'});


                // CHANGE THE CURSOR ACCORDING TO THE VALIDITY OF DAYS
                for (let day=1; day<=calendar['daysInMonth']; day++)
                    if (calendar['monthNow'] == month)
                    {
                        if (day < calendar['today']) 
                            $("#"+day).css({'cursor':'not-allowed'});
                        else                      
                            $("#"+day).css({'cursor':'pointer'});
                    }
                    else if (calendar['monthNow'] < month)
                        $("#"+day).css({'cursor':'not-allowed'});
                    else if (calendar['monthNow'] > month)
                        $("#"+day).css({'cursor':'pointer'});

            });
        </script>
    </body>
</html>
    