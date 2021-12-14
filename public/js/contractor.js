$(document).ready(function(){
    
    $('#redirectpage').attr('value','contractor');


    // GROUPING CHECKBOXES TO SEND A SINGLE VALUE TO THE DATABASE
    $('#appointmentform').on('click','.pservice', function(){
        var service_value = '';

        for (let i=1; i<=$('input[type=checkbox').length; i++)
            if ($('#service-'+i).is(':checked'))
                service_value = service_value + $('#service-text-'+i).text()+',';
                
        $("input[type=checkbox]").attr("value", service_value);
    });

    $('.edit-input').click(function(){
        var edit_service_value = '';

        for (let i=1; i<=$('.edit-input').length; i++){
            if ($("#edit-service-"+i).is(':checked'))
                edit_service_value = edit_service_value + $("#edit-service-text-"+i).text()+"\n";
        }
        
        $(".edit-input").attr("value", edit_service_value);
    });

});