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
                @if (($dayTile % 7 == 1))
                    <td value="not-allowed" id="{{ $day }}">{{ $day }}</td>
                @else
                    <td value="allowed" id="{{ $day }}">{{ $day }}</td>
                @endif
            @endif
        @endfor
    </table>
</div>
    