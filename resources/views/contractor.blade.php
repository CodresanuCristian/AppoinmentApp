<!DOCTYPE>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Dashboard</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="css/mystyle.css">
    </head>

    <body>
        <h1>Welcome {{ session('username') }}</h1>
        <a href="/logout">Log out</a>

        <div class="container">
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
    </body>
</html>