$(document).ready(function(){    

    // GROUPING CHECKBOXES TO SEND A SINGLE VALUE TO THE DATABASE
    $('#appointmentform').on('click','.pservice', function(){
        var service_value = '';

        for (let i=1; i<=$('input[type=checkbox').length; i++)
            if ($('#service-'+i).is(':checked'))
                service_value = service_value + $('#service-text-'+i).text()+',';
                
        $("input[type=checkbox]").attr("value", service_value);
    });
    
});