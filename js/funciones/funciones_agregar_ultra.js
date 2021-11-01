$(document).ready(function(){

	$('#form_ultra').validate(
	{		
	    rules:
	    {
            ultra:
            {  
                required: true,           
            },

        },
        messages:
        {
			ultra: "Debe de ingresar la imagen de la ultrasonografia del paciente",

		},
		highlight: function(element)
		{
			$(element).closest('.form-group').removeClass('has-success').addClass('has-error');
		},
		success: function(element)
		{
			$(element).closest('.form-group').removeClass('has-error').addClass('has-success');
		},
		submitHandler: function (form)
		{ 

    		sendUltra();
		}
    });


    function sendUltra(){
    	var form=$("#form_ultra");

    	var formdata=false;
    	if(window.FormData){
    		formdata= new FormData(form[0]);
    	}
    	
    	$.ajax({
    		url:'agregar_ultra.php',
    		type:'POST',
    		cache: false,
    		data: formdata ? formdata : form.serialize(),
    		contentType:false,
    		processData:false,
    		dataType:'json',
    		success:function(data){
                //alert(data.typeinfo);
    			display_notify(data.typeinfo, data.msg, data.process);
    			if(data.typeinfo=="Success"){
    				setTimeout(function(){
    					location.href='cola.php';
    				}, 1500);
    			}
    		}

    	});
    }
});