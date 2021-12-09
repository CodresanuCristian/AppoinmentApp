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
                                    CreateList(date_tile.list[i]['id'], date_tile.list[i]['start_hour']+':'+date_tile.list[i]['start_minute'], date_tile.list[i]['name']+' - '+date_tile.list[i]['services'], date_tile.list[i]['phone']);
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