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
            <h3 class="appList-header text-center m-4" id="close-appList-window"></h3>
            <div class="appList"></div>
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
                    // if ($('.contractordetailsform').is(':visible'))
                        // ShowContractorDetails();
                });

                $('td').click(function(){
                    if ($('.newappform').is(':visible') || $('.contractordetailsform').is(':visible')){    
                        $("#date").val(($(this).attr("id")+" "+calendar['month']+" "+calendar['year']));
                        $("#adddaysoff").val(($(this).attr("id")+" "+calendar['month']+" "+calendar['year']));
                    }
                    else
                        $(".appList-window").show();
                });

                $('#close-appList-window').click(function(){
                    $('.appList-window').hide();
                });





                function ShowContractorDetails()
                {
                    $.ajax({
                        type: 'GET',
                        url: '/showcontractordetails',
                        dataType: 'json',
                        success: function(data){
                            for (let i=0; i<data.contractor.length; i++)
                                createOption('deletecontractor', data.contractor[i]['username'], false);
                            for (let i=0; i<data.contractor_details.length; i++){
                                createOption('deletedaysoff', data.contractor_details[i]['days_off'], false);
                                createOption('deleteholiday',data.contractor_details[i]['start_holiday']+' / '+data.contractor_details[i]['finish_holiday'], true);
                            }
                        }
                    });
                }


                function createOption(father, value, holiday)
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

                









// =================================================


                // // APPOINTMENT LIST - SHOW LIST
                // $('td').click(function(){
                //     $.ajax({
                //         type:'get',
                //         url: '/showlist',
                //         data: {date_filter: $(this).attr('id')+' '+calendar['month']+' '+calendar['year']},
                //         success: function(response){
                //             ShowList(client, response.newDate);
                //         },
                //     });
                // });


                // function ShowList(client, date)
                // {
                //     $('#close-appList-window').text(date+' (close)');
                //     // $(".appList-window").css({'display':'inherit'});

                //     var clock, title, phone, userBox, editBtn, delBtn;

                //     for (let i=0; i < client.length; i++){
                //         if (date == client[i]['date']){
                //             userBox = document.createElement('div');
                //             userBox.setAttribute('class', 'userbox');
                //             userBox.setAttribute('id', 'userbox'+client[i]['id']);
                //             $('.appList').append(userBox);

                //             clock = $(document.createElement('p'));
                //             clock.attr('class','data-hour m-0');
                //             clock.attr('id', 'clock'+client[i]['id']);
                //             clock.text(client[i]['start_hour'] + ':' + client[i]['start_minute']);
                //             $('#userbox'+client[i]['id']).append(clock);

                //             title = $(document.createElement('p'));
                //             title.attr('class','data-title m-0');
                //             title.attr('id', 'title'+client[i]['id']);
                //             title.text(client[i]['name'] + ' - ' + client[i]['services']);
                //             $('#userbox'+client[i]['id']).append(title);

                //             phone = $(document.createElement('p'));
                //             phone.attr('class','data-phone m-0');
                //             phone.attr('id', 'phone'+client[i]['id']);
                //             phone.text(client[i]['phone']);
                //             $('#userbox'+client[i]['id']).append(phone);

                //             editBtn = $(document.createElement('button'));
                //             editBtn.attr('class','btn btn-warning m-2 editBtn');
                //             editBtn.attr('id','editBtn'+client[i]['id']);
                //             editBtn.text('Edit');
                //             $('#userbox'+client[i]['id']).append(editBtn);

                //             delBtn = document.createElement('button');
                //             delBtn.setAttribute('class','pula');
                //             delBtn.setAttribute('id','delBtn'+client[i]['id']);
                //             // delBtn.('Delete');
                //             $('#userbox'+client[i]['id']).appendChild(delBtn);
                //         }
                //     }
                // }

            });
        </script>
    </body>
</html>