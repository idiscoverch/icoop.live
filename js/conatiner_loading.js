
var blanc = "";
var my_x = "";
var my_y = "";


function readURL(input,val) {  
 if (input.files && input.files[0]) {
    var reader = new FileReader(); reader.onload = function (e) {$('#img'+val).attr('src', e.target.result);}
    reader.readAsDataURL(input.files[0]);
 }
}


function cloading_longin(){
	var username = document.getElementById('cl_username').value;
	var password = document.getElementById('cl_password').value;

	if (username == '' || password == ''  ){
		document.getElementById("cl_login-alert12").style.display = "none";
	    document.getElementById("cl_login-alert11").style.display = "block";
	    document.getElementById('cl_login-alert11').innerHTML = '<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>Vous n\'avez pas entré votre mail ou votre mot de passe!';

	} else {
		var resurl='listeslies.php?elemid=connexion&username='+username+'&password='+password;  
        var xhr = getXhr();
        xhr.onreadystatechange = function(){
            if(xhr.readyState == 4 ){
                leselect = xhr.responseText;   
                var val=leselect;
                var val1=val.split('##');

                if (val1[0] == 1) {
					window.open("containerloading.php",'_self');
				} else {
					document.getElementById("cl_login-alert12").style.display = "none";
				    document.getElementById("cl_login-alert11").style.display = "block";
	                document.getElementById('cl_login-alert11').innerHTML = '<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'+val1[1];
				}

				leselect = xhr.responseText;
            }
        };

        xhr.open("GET",resurl,true);
        xhr.send(null);
	}
}


function loadingForm(ord_loading_id,id_ord_loading_item,booking_nr,ord_schedule_id,id_con_list,container_nr){   
	$("#containerForm").modal("show");
	
	var resurl='loading_listeslies.php?elemid=loading_container&ord_loading_id='+ord_loading_id+'&id_ord_loading_item='+id_ord_loading_item+'&booking_nr='+booking_nr+'&ord_schedule_id='+ord_schedule_id+'&id_con_list='+id_con_list+'&container_nr='+container_nr;  
    var xhr = getXhr();
    xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
			leselect = xhr.responseText;   
            
			document.getElementById("loadingFormContent").innerHTML = leselect;
			document.getElementById("ord_loading_id").value = ord_loading_id;

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
	xhr.send(null);
}


function Startloading() {
	if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition2);
    } else { 
        alert("Geolocation is not supported by this browser.");
    }
}


function showPosition2(position) {  

	coord_x = position.coords.latitude;
	coord_y = position.coords.longitude;
	
	var ord_schedule_id = document.getElementById("ord_schedule_id").value;
	var id_con_list = document.getElementById("id_con_list").value;
	var container_nr = document.getElementById("container_nr").value;
	
	
	var resurl='loading_listeslies.php?elemid=start_loading&coord_x='+coord_x+'&coord_y='+coord_y+'&ord_schedule_id='+ord_schedule_id+'&id_con_list='+id_con_list+'&container_nr='+container_nr;   
	var xhr = getXhr();   
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;      
			var val = leselect.split('#');
			
			if(val[0] == 1){
				$("#startLBtn").addClass("hide");
				document.getElementById("ord_loading_id").value = val[1];
				
				$("#start_ctn").removeClass("hide");
			} 

			leselect = xhr.responseText;
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
	
}


function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
    } else { 
        alert("Geolocation is not supported by this browser.");
    }
}

function showPosition(position) {

	my_x = position.coords.latitude;
	my_y = position.coords.longitude;
	
	var quality = '';

	if($("input[type='radio'].qualityRadio").is(':checked')) {
		quality = $("input[type='radio'].qualityRadio:checked").val();
	}
	
	var ord_loading_id = document.getElementById("ord_loading_id").value;
	
	if(quality){
		var resurl='loading_listeslies.php?elemid=booking_quality&quality='+quality+'&coord_x='+my_x+'&coord_y='+my_y+'&ord_loading_id='+ord_loading_id; 
		var xhr = getXhr(); 
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;   
				
				if(leselect == 1){
					$("#bookingQuality").addClass("hide");
				} 

				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
	}	
}


function cleanliness() {
	var cleanliness = '';

	if($("input[type='radio'].cleanlinessRadio").is(':checked')) {
		cleanliness = $("input[type='radio'].cleanlinessRadio:checked").val();
	}
	
	var ord_loading_id = document.getElementById("ord_loading_id").value; 
	
	if(cleanliness){
		var resurl='loading_listeslies.php?elemid=booking_cleanliness&cleanliness='+cleanliness+'&ord_loading_id='+ord_loading_id;   
		var xhr = getXhr(); 
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;   
				
				if(leselect == 1){
					$("#bookingCleanliness").addClass("hide");
				} 

				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
	}
}


function smell(){
	var smell = '';

	if($("input[type='radio'].smellRadio").is(':checked')) {
		smell = $("input[type='radio'].smellRadio:checked").val();
	}
	
	var ord_loading_id = document.getElementById("ord_loading_id").value; 
	
	if(smell){
		var resurl='loading_listeslies.php?elemid=booking_smell&smell='+smell+'&ord_loading_id='+ord_loading_id;   
		var xhr = getXhr(); 
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;   
				
				if(leselect == 1){
					$("#bookingSmell").addClass("hide");
				} 

				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
	}
}


function locks(){
	var locks = '';

	if($("input[type='radio'].locksRadio").is(':checked')) {
		locks = $("input[type='radio'].locksRadio:checked").val();
	}
	
	var ord_loading_id = document.getElementById("ord_loading_id").value; 
	
	if(locks){
		var resurl='loading_listeslies.php?elemid=booking_locks&locks='+locks+'&ord_loading_id='+ord_loading_id;   
		var xhr = getXhr(); 
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;   
				
				if(leselect == 1){
					$("#bookingLocks").addClass("hide");
				} 

				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
	}
}


function approve_use(){
	var approve_use = '';

	if($("input[type='radio'].approve_useRadio").is(':checked')) {
		approve_use = $("input[type='radio'].approve_useRadio:checked").val();
	}
	
	var ord_loading_id = document.getElementById("ord_loading_id").value; 
	
	if(approve_use){
		var resurl='loading_listeslies.php?elemid=booking_approve_use&approve_use='+approve_use+'&ord_loading_id='+ord_loading_id;   
		var xhr = getXhr(); 
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;   
				
				if(leselect == 1){
					$("#bookingApprove_use").addClass("hide");
				} 

				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
	}
}


function seal(){

	var container_nr = document.getElementById("container_nr").value; 
	
	$('#loading_overlay').removeClass('hide');
	$('#loading_popup').removeClass('hide');
	
	$('#seal_photos').on('submit', function(e) {

        e.preventDefault();
 
        var $form = $(this);
        var formdata = (window.FormData) ? new FormData($form[0]) : null;
        var data = (formdata !== null) ? formdata : $form.serialize();
 
		var seal1=0;
		var seal2=0;
		var seal3=0;
		var seal4=0;
		
        $.ajax({
            url: $form.attr('action'),
            type: $form.attr('method'),
            contentType: false, // obligatoire pour de l'upload
            processData: false, // obligatoire pour de l'upload
            dataType: 'text', // selon le retour attendu
            data: data,
            success: function (response) { 
				var val = response.split('#');  
		
				if(val[0]==1){ $("#bookingSeal1").addClass("hide"); } else { toastr.error('Image 1 not aploaded',{timeOut:15000}) }
				if(val[1]==1){ $("#bookingSeal2").addClass("hide"); } else { toastr.error('Image 2 not aploaded',{timeOut:15000}) }
				if(val[2]==1){ $("#bookingSeal3").addClass("hide"); } else { toastr.error('Image 3 not aploaded',{timeOut:15000}) }
				if(val[3]==1){ $("#bookingSeal4").addClass("hide"); } else { toastr.error('Image 4 not aploaded',{timeOut:15000}) }
				
				var seal1_photo = document.getElementById("file1").value.replace(/C:\\fakepath\\/i, '');   
				var seal2_photo = document.getElementById("file2").value.replace(/C:\\fakepath\\/i, ''); 
				var seal3_photo = document.getElementById("file3").value.replace(/C:\\fakepath\\/i, ''); 
				var seal4_photo = document.getElementById("file4").value.replace(/C:\\fakepath\\/i, ''); 
				
				var seal_1_nr = document.getElementById("seal_1_nr").value; 
				var seal_2_nr = document.getElementById("seal_2_nr").value; 
				var seal_3_nr = document.getElementById("seal_3_nr").value; 
				var seal_4_nr = document.getElementById("seal_4_nr").value; 
				
				var resurl='loading_listeslies.php?elemid=seal_photos&seal1_photo='+seal1_photo+'&seal2_photo='+seal2_photo+'&seal3_photo='+seal3_photo+'&seal4_photo='+seal4_photo+'&container_nr='+container_nr+'&seal_1_nr='+seal_1_nr+'&seal_2_nr='+seal_2_nr+'&seal_3_nr='+seal_3_nr+'&seal_4_nr='+seal_4_nr;   
				var xhr = getXhr();  
				xhr.onreadystatechange = function(){
					if(xhr.readyState == 4 ){
						leselect = xhr.responseText;   
							
						if(leselect == 1){
							$("#bookingAllSeals").addClass("hide");
							
							$('#loading_overlay').addClass('hide');
							$('#loading_popup').addClass('hide');
						} 

						leselect = xhr.responseText;
						}
				};

				xhr.open("GET",resurl,true);
				xhr.send(null);
            }
        });
    });
	
}


function seal_1() {
	
	var container_nr = document.getElementById("container_nr").value;
	
	$('#loading_overlay').removeClass('hide');
	$('#loading_popup').removeClass('hide');
	
	$('#seal_photo_1').on('submit', function(e) {

        e.preventDefault();
 
        var $form = $(this);
        var formdata = (window.FormData) ? new FormData($form[0]) : null;
        var data = (formdata !== null) ? formdata : $form.serialize();
 
		var img=0;
		
        $.ajax({
            url: $form.attr('action'),
            type: $form.attr('method'),
            contentType: false, // obligatoire pour de l'upload
            processData: false, // obligatoire pour de l'upload
            dataType: 'text', // selon le retour attendu
            data: data,
            success: function (response) { 
				if(response==1){ 
					var seal1_photo = document.getElementById("file1").value.replace(/C:\\fakepath\\/i, ''); 
					var seal_1_nr = document.getElementById("seal_1_nr").value;
					
					var resurl='loading_listeslies.php?elemid=seal1_photo&seal1_photo='+seal1_photo+'&seal_1_nr='+seal_1_nr+'&container_nr='+container_nr;   
					var xhr = getXhr(); 
					xhr.onreadystatechange = function(){
						if(xhr.readyState == 4 ){
							leselect = xhr.responseText;   
						
							if(leselect == 1){
								$("#bookingSeal1").addClass("hide"); 
								
								$('#loading_overlay').addClass('hide');
								$('#loading_popup').addClass('hide');
							} 

							leselect = xhr.responseText;
						}
					};

					xhr.open("GET",resurl,true);
					xhr.send(null);
					
				} else { toastr.error('Image not uploaded.',{timeOut:15000}) }
            }
        });
    });
}


function seal_2() { 
	
	var container_nr = document.getElementById("container_nr").value;
	
	$('#loading_overlay').removeClass('hide');
	$('#loading_popup').removeClass('hide');
	
	$('#seal_photo_2').on('submit', function(e) { 

        e.preventDefault();
 
        var $form = $(this);
        var formdata = (window.FormData) ? new FormData($form[0]) : null;
        var data = (formdata !== null) ? formdata : $form.serialize();
 
		var img=0;
		
        $.ajax({
            url: $form.attr('action'),
            type: $form.attr('method'),
            contentType: false, // obligatoire pour de l'upload
            processData: false, // obligatoire pour de l'upload
            dataType: 'text', // selon le retour attendu
            data: data,
            success: function (response) { 
				if(response==1){ 
					var seal2_photo = document.getElementById("file2").value.replace(/C:\\fakepath\\/i, ''); 
					var seal_2_nr = document.getElementById("seal_2_nr").value;
					
					var resurl='loading_listeslies.php?elemid=seal2_photo&seal2_photo='+seal2_photo+'&seal_2_nr='+seal_2_nr+'&container_nr='+container_nr;   
					var xhr = getXhr();  
					xhr.onreadystatechange = function(){
						if(xhr.readyState == 4 ){
							leselect = xhr.responseText;     
						
							if(leselect == 1){
								$("#bookingSeal2").addClass("hide"); 
								
								$('#loading_overlay').addClass('hide');
								$('#loading_popup').addClass('hide');
							} 

							leselect = xhr.responseText;
						}
					};

					xhr.open("GET",resurl,true);
					xhr.send(null);
					
				} else { toastr.error('Image not uploaded.',{timeOut:15000}) }
            }
        });
    });
}


function seal_3() {
	
	var container_nr = document.getElementById("container_nr").value;
	
	$('#loading_overlay').removeClass('hide');
	$('#loading_popup').removeClass('hide');
	
	$('#seal_photo_3').on('submit', function(e) {

        e.preventDefault();
 
        var $form = $(this);
        var formdata = (window.FormData) ? new FormData($form[0]) : null;
        var data = (formdata !== null) ? formdata : $form.serialize();
 
		var img=0;
		
        $.ajax({
            url: $form.attr('action'),
            type: $form.attr('method'),
            contentType: false, // obligatoire pour de l'upload
            processData: false, // obligatoire pour de l'upload
            dataType: 'text', // selon le retour attendu
            data: data,
            success: function (response) { 
				if(response==1){ 
					var seal3_photo = document.getElementById("file3").value.replace(/C:\\fakepath\\/i, ''); 
					var seal_3_nr = document.getElementById("seal_3_nr").value;
					
					var resurl='loading_listeslies.php?elemid=seal3_photo&seal3_photo='+seal3_photo+'&seal_3_nr='+seal_3_nr+'&container_nr='+container_nr;   
					var xhr = getXhr(); 
					xhr.onreadystatechange = function(){
						if(xhr.readyState == 4 ){
							leselect = xhr.responseText;   
						
							if(leselect == 1){
								$("#bookingSeal3").addClass("hide"); 
								
								$('#loading_overlay').addClass('hide');
								$('#loading_popup').addClass('hide');
							} 

							leselect = xhr.responseText;
						}
					};

					xhr.open("GET",resurl,true);
					xhr.send(null);
					
				} else { toastr.error('Image not uploaded.',{timeOut:15000}) }
            }
        });
    });
}


function seal_4() {
	
	var container_nr = document.getElementById("container_nr").value;
	
	$('#loading_overlay').removeClass('hide');
	$('#loading_popup').removeClass('hide');
	
	$('#seal_photo_4').on('submit', function(e) {

        e.preventDefault();
 
        var $form = $(this);
        var formdata = (window.FormData) ? new FormData($form[0]) : null;
        var data = (formdata !== null) ? formdata : $form.serialize();
 
		var img=0;
		
        $.ajax({
            url: $form.attr('action'),
            type: $form.attr('method'),
            contentType: false, // obligatoire pour de l'upload
            processData: false, // obligatoire pour de l'upload
            dataType: 'text', // selon le retour attendu
            data: data,
            success: function (response) { 
				if(response==1){ 
					var seal4_photo = document.getElementById("file4").value.replace(/C:\\fakepath\\/i, ''); 
					var seal_4_nr = document.getElementById("seal_4_nr").value;
					
					var resurl='loading_listeslies.php?elemid=seal4_photo&seal4_photo='+seal4_photo+'&seal_4_nr='+seal_4_nr+'&container_nr='+container_nr;   
					var xhr = getXhr(); 
					xhr.onreadystatechange = function(){
						if(xhr.readyState == 4 ){
							leselect = xhr.responseText;   
						
							if(leselect == 1){
								$("#bookingSeal4").addClass("hide"); 
								
								$('#loading_overlay').addClass('hide');
								$('#loading_popup').addClass('hide');
							} 

							leselect = xhr.responseText;
						}
					};

					xhr.open("GET",resurl,true);
					xhr.send(null);
					
				} else { toastr.error('Image not uploaded.',{timeOut:15000}) }
            }
        });
    });
}


function seal_5() {
	
	var container_nr = document.getElementById("container_nr").value;
	
	$('#loading_overlay').removeClass('hide');
	$('#loading_popup').removeClass('hide');
	
	$('#seal_photo_5').on('submit', function(e) {

        e.preventDefault();
 
        var $form = $(this);
        var formdata = (window.FormData) ? new FormData($form[0]) : null;
        var data = (formdata !== null) ? formdata : $form.serialize();
 
		var img=0;
		
        $.ajax({
            url: $form.attr('action'),
            type: $form.attr('method'),
            contentType: false, // obligatoire pour de l'upload
            processData: false, // obligatoire pour de l'upload
            dataType: 'text', // selon le retour attendu
            data: data,
            success: function (response) {  
				if(response==1){ 
					var seal5_photo = document.getElementById("file23").value.replace(/C:\\fakepath\\/i, '');   
					var seal_5_nr = document.getElementById("seal_5_nr").value; 
					
					var resurl='loading_listeslies.php?elemid=seal5_photo&seal5_photo='+seal5_photo+'&seal_5_nr='+seal_5_nr+'&container_nr='+container_nr;   
					var xhr = getXhr(); 
					xhr.onreadystatechange = function(){
						if(xhr.readyState == 4 ){
							leselect = xhr.responseText;   
						
							if(leselect == 1){
								$("#bookingSeal5").addClass("hide"); 
								
								$('#loading_overlay').addClass('hide');
								$('#loading_popup').addClass('hide');
							} 

							leselect = xhr.responseText;
						}
					};

					xhr.open("GET",resurl,true);
					xhr.send(null);
					
				} else { toastr.error('Image not uploaded.',{timeOut:15000}) }
            }
        });
    });
}


function HeadTruck() {
	
	var ord_loading_id = document.getElementById("ord_loading_id").value; 
	
	$('#loading_overlay').removeClass('hide');
	$('#loading_popup').removeClass('hide');
	
	$('#truck_nr_plate').on('submit', function(e) {

        e.preventDefault();
 
        var $form = $(this);
        var formdata = (window.FormData) ? new FormData($form[0]) : null;
        var data = (formdata !== null) ? formdata : $form.serialize();
 
		var img=0;
		
        $.ajax({
            url: $form.attr('action'),
            type: $form.attr('method'),
            contentType: false, // obligatoire pour de l'upload
            processData: false, // obligatoire pour de l'upload
            dataType: 'text', // selon le retour attendu
            data: data,
            success: function (response) { 
				if(response==1){ 
					var truck_number = document.getElementById("truck_number").value; 
					var truck_nr_plate = document.getElementById("file5").value.replace(/C:\\fakepath\\/i, ''); 
					var container_nr = document.getElementById("container_nr").value;
			
					var resurl='loading_listeslies.php?elemid=truck_nr&truck_nr_plate='+truck_nr_plate+'&truck_number='+truck_number+'&container_nr='+container_nr+'&ord_loading_id='+ord_loading_id;   
					var xhr = getXhr(); 
					xhr.onreadystatechange = function(){
						if(xhr.readyState == 4 ){
							leselect = xhr.responseText;   
						
							if(leselect == 1){
								$("#loadingTruck_nr_plate").addClass("hide"); 
								
								$('#loading_overlay').addClass('hide');
								$('#loading_popup').addClass('hide');
							} 

							leselect = xhr.responseText;
						}
					};

					xhr.open("GET",resurl,true);
					xhr.send(null);
					
				} else { toastr.error('Image not uploaded.',{timeOut:15000}) }
            }
        });
    });
}


function SideISO() { 
	
	var ord_loading_id = document.getElementById("ord_loading_id").value; 
	
	$('#loading_overlay').removeClass('hide');
	$('#loading_popup').removeClass('hide');
	
	$('#con_side_view').on('submit', function(e) {

        e.preventDefault();
 
        var $form = $(this);
        var formdata = (window.FormData) ? new FormData($form[0]) : null;
        var data = (formdata !== null) ? formdata : $form.serialize();
 
		var img=0;
		
        $.ajax({
            url: $form.attr('action'),
            type: $form.attr('method'),
            contentType: false, // obligatoire pour de l'upload
            processData: false, // obligatoire pour de l'upload
            dataType: 'text', // selon le retour attendu
            data: data,
            success: function (response) {
				if(response==1){ 
					var con_side_view = document.getElementById("file6").value.replace(/C:\\fakepath\\/i, ''); 
					var container_nr = document.getElementById("container_nr").value;  
			
					var resurl='loading_listeslies.php?elemid=side_view&con_side_view='+con_side_view+'&container_nr='+container_nr+'&ord_loading_id='+ord_loading_id;   
					var xhr = getXhr();  
					xhr.onreadystatechange = function(){
						if(xhr.readyState == 4 ){
							leselect = xhr.responseText;   
					
							if(leselect == 1){
								$("#loadingCon_side_view").addClass("hide"); 
								
								$('#loading_overlay').addClass('hide');
								$('#loading_popup').addClass('hide');
							} 

							leselect = xhr.responseText;
						}
					};

					xhr.open("GET",resurl,true);
					xhr.send(null);
					
				} else { toastr.error('Image not uploaded.',{timeOut:15000}) } 
            }
			
        });
    });
}


function plate_cert() {
	
	var ord_loading_id = document.getElementById("ord_loading_id").value; 
	
	$('#loading_overlay').removeClass('hide');
	$('#loading_popup').removeClass('hide');
	
	$('#plate_cert').on('submit', function(e) {

        e.preventDefault();
 
        var $form = $(this);
        var formdata = (window.FormData) ? new FormData($form[0]) : null;
        var data = (formdata !== null) ? formdata : $form.serialize();
		
		var img=0;
		
        $.ajax({
            url: $form.attr('action'),
            type: $form.attr('method'),
            contentType: false, // obligatoire pour de l'upload
            processData: false, // obligatoire pour de l'upload
            dataType: 'text', // selon le retour attendu
            data: data,
            success: function (response) {
				if(response==1){ 
					var plate_cert = document.getElementById("file7").value.replace(/C:\\fakepath\\/i, '');
					var container_nr = document.getElementById("container_nr").value;
					
					var resurl='loading_listeslies.php?elemid=plate_cert&plate_cert='+plate_cert+'&container_nr='+container_nr+'&ord_loading_id='+ord_loading_id;   
					var xhr = getXhr(); 
					xhr.onreadystatechange = function(){
						if(xhr.readyState == 4 ){
							leselect = xhr.responseText;   
							
							if(leselect == 1){
								$("#loadingPlate_cert").addClass("hide"); 
								
								$('#loading_overlay').addClass('hide');
								$('#loading_popup').addClass('hide');
							} 

							leselect = xhr.responseText;
						}
					};

					xhr.open("GET",resurl,true);
					xhr.send(null);
					
				} else { toastr.error('Image not uploaded.',{timeOut:15000}) }
            }
        });
    });
}


function RearView() {
	
	var ord_loading_id = document.getElementById("ord_loading_id").value; 
	
	$('#loading_overlay').removeClass('hide');
	$('#loading_popup').removeClass('hide');
	
	$('#rear_view').on('submit', function(e) {

        e.preventDefault();
 
        var $form = $(this);
        var formdata = (window.FormData) ? new FormData($form[0]) : null;
        var data = (formdata !== null) ? formdata : $form.serialize();
		
		var img=0;
		
        $.ajax({
            url: $form.attr('action'),
            type: $form.attr('method'),
            contentType: false, // obligatoire pour de l'upload
            processData: false, // obligatoire pour de l'upload
            dataType: 'text', // selon le retour attendu
            data: data,
            success: function (response) {
				if(response==1){ 
					var rear_view = document.getElementById("file8").value.replace(/C:\\fakepath\\/i, '');
					var container_nr = document.getElementById("container_nr").value;
					
					var resurl='loading_listeslies.php?elemid=rear_view&rear_view='+rear_view+'&container_nr='+container_nr+'&ord_loading_id='+ord_loading_id;   
					var xhr = getXhr(); 
					xhr.onreadystatechange = function(){
						if(xhr.readyState == 4 ){
							leselect = xhr.responseText;   
							
							if(leselect == 1){
								$("#loadingRear_view").addClass("hide"); 
								
								$('#loading_overlay').addClass('hide');
								$('#loading_popup').addClass('hide');
							} 

							leselect = xhr.responseText;
						}
					};

					xhr.open("GET",resurl,true);
					xhr.send(null);
					
				} else { toastr.error('Image not uploaded.',{timeOut:15000}) }
            }
        });
    });
}


function Rearoutlet() {
	
	var ord_loading_id = document.getElementById("ord_loading_id").value; 
	
	$('#loading_overlay').removeClass('hide');
	$('#loading_popup').removeClass('hide');
	
	$('#rear_outlet').on('submit', function(e) {

        e.preventDefault();
 
        var $form = $(this);
        var formdata = (window.FormData) ? new FormData($form[0]) : null;
        var data = (formdata !== null) ? formdata : $form.serialize();
 
		var img=0;
		
        $.ajax({
            url: $form.attr('action'),
            type: $form.attr('method'),
            contentType: false, // obligatoire pour de l'upload
            processData: false, // obligatoire pour de l'upload
            dataType: 'text', // selon le retour attendu
            data: data,
            success: function (response) {
				if(response==1){ 
					var rear_outlet = document.getElementById("file9").value.replace(/C:\\fakepath\\/i, '');
					var container_nr = document.getElementById("container_nr").value;
					
					var resurl='loading_listeslies.php?elemid=rear_outlet&rear_outlet='+rear_outlet+'&container_nr='+container_nr+'&ord_loading_id='+ord_loading_id;   
					var xhr = getXhr(); 
					xhr.onreadystatechange = function(){
						if(xhr.readyState == 4 ){
							leselect = xhr.responseText;   
							
							if(leselect == 1){
								$("#loadingRear_outlet").addClass("hide"); 
								
								$('#loading_overlay').addClass('hide');
								$('#loading_popup').addClass('hide');
							} 

							leselect = xhr.responseText;
						}
					};

					xhr.open("GET",resurl,true);
					xhr.send(null);
					
				} else { toastr.error('Image not uploaded.',{timeOut:15000}) }
            }
        });
    });
}


function PhotoOfSeal1() {
	
	var ord_loading_id = document.getElementById("ord_loading_id").value; 
	
	$('#loading_overlay').removeClass('hide');
	$('#loading_popup').removeClass('hide');
	
	$('#seal1').on('submit', function(e) {

        e.preventDefault();
 
        var $form = $(this);
        var formdata = (window.FormData) ? new FormData($form[0]) : null;
        var data = (formdata !== null) ? formdata : $form.serialize();
		
		var img=0;
		
        $.ajax({
            url: $form.attr('action'),
            type: $form.attr('method'),
            contentType: false, // obligatoire pour de l'upload
            processData: false, // obligatoire pour de l'upload
            dataType: 'text', // selon le retour attendu
            data: data,
            success: function (response) {
				if(response==1){
					var seal1 = document.getElementById("file10").value.replace(/C:\\fakepath\\/i, '');
					var container_nr = document.getElementById("container_nr").value;
					
					var resurl='loading_listeslies.php?elemid=seal1&seal1='+seal1+'&container_nr='+container_nr+'&ord_loading_id='+ord_loading_id;   
					var xhr = getXhr(); 
					xhr.onreadystatechange = function(){
						if(xhr.readyState == 4 ){
							leselect = xhr.responseText;   
							
							if(leselect == 1){
								$("#loadingSeal1").addClass("hide"); 
								
								$('#loading_overlay').addClass('hide');
								$('#loading_popup').addClass('hide');
							} 

							leselect = xhr.responseText;
						}
					};

					xhr.open("GET",resurl,true);
					xhr.send(null);
					
				} else { toastr.error('Image not uploaded.',{timeOut:15000}) }
            }
        });
    });
}


function Compartment1() {
	
	var ord_loading_id = document.getElementById("ord_loading_id").value; 
	
	$('#loading_overlay').removeClass('hide');
	$('#loading_popup').removeClass('hide');
	
	$('#comp1_interior').on('submit', function(e) {

        e.preventDefault();
 
        var $form = $(this);
        var formdata = (window.FormData) ? new FormData($form[0]) : null;
        var data = (formdata !== null) ? formdata : $form.serialize();
 
		var img=0;
		
        $.ajax({
            url: $form.attr('action'),
            type: $form.attr('method'),
            contentType: false, // obligatoire pour de l'upload
            processData: false, // obligatoire pour de l'upload
            dataType: 'text', // selon le retour attendu
            data: data,
            success: function (response) {
				if(response==1){ 
					var comp1_interior = document.getElementById("file11").value.replace(/C:\\fakepath\\/i, ''); 
					var container_nr = document.getElementById("container_nr").value;
					
					var resurl='loading_listeslies.php?elemid=comp1_interior&comp1_interior='+comp1_interior+'&container_nr='+container_nr+'&ord_loading_id='+ord_loading_id;   
					var xhr = getXhr(); 
					xhr.onreadystatechange = function(){
						if(xhr.readyState == 4 ){
							leselect = xhr.responseText;   
							
							if(leselect == 1){
								$("#loadingComp1_interior").addClass("hide");

								$('#loading_overlay').addClass('hide');
								$('#loading_popup').addClass('hide');								
							} 

							leselect = xhr.responseText;
						}
					};

					xhr.open("GET",resurl,true);
					xhr.send(null);
					
				} else { toastr.error('Image not uploaded.',{timeOut:15000}) }
            }
        });
    });
}


function Compartment2() {
	
	var ord_loading_id = document.getElementById("ord_loading_id").value; 
	
	$('#loading_overlay').removeClass('hide');
	$('#loading_popup').removeClass('hide');
	
	$('#comp2_interior').on('submit', function(e) {

        e.preventDefault();
 
        var $form = $(this);
        var formdata = (window.FormData) ? new FormData($form[0]) : null;
        var data = (formdata !== null) ? formdata : $form.serialize();
 
		var img=0;
		
        $.ajax({
            url: $form.attr('action'),
            type: $form.attr('method'),
            contentType: false, // obligatoire pour de l'upload
            processData: false, // obligatoire pour de l'upload
            dataType: 'text', // selon le retour attendu
            data: data,
            success: function (response) {
				if(response==1){ 
					var comp2_interior = document.getElementById("file12").value.replace(/C:\\fakepath\\/i, ''); 
					var container_nr = document.getElementById("container_nr").value;
					
					var resurl='loading_listeslies.php?elemid=comp2_interior&comp2_interior='+comp2_interior+'&container_nr='+container_nr+'&ord_loading_id='+ord_loading_id;   
					var xhr = getXhr(); 
					xhr.onreadystatechange = function(){
						if(xhr.readyState == 4 ){
							leselect = xhr.responseText;   
							
							if(leselect == 1){
								$("#loadingComp2_interior").addClass("hide"); 
								
								$('#loading_overlay').addClass('hide');
								$('#loading_popup').addClass('hide');
							} 

							leselect = xhr.responseText;
						}
					};

					xhr.open("GET",resurl,true);
					xhr.send(null);
					
				} else { toastr.error('Image not uploaded.',{timeOut:15000}) }
            }
        });
    });
}


function Compartment3() {
	
	var ord_loading_id = document.getElementById("ord_loading_id").value; 
	
	$('#loading_overlay').removeClass('hide');
	$('#loading_popup').removeClass('hide');
	
	$('#comp3_interior').on('submit', function(e) {

        e.preventDefault();
 
        var $form = $(this);
        var formdata = (window.FormData) ? new FormData($form[0]) : null;
        var data = (formdata !== null) ? formdata : $form.serialize();
 
		var img=0;
		
        $.ajax({
            url: $form.attr('action'),
            type: $form.attr('method'),
            contentType: false, // obligatoire pour de l'upload
            processData: false, // obligatoire pour de l'upload
            dataType: 'text', // selon le retour attendu
            data: data,
            success: function (response) {
				if(response==1){ 
					var comp3_interior = document.getElementById("file13").value.replace(/C:\\fakepath\\/i, ''); 
					var container_nr = document.getElementById("container_nr").value;
					
					var resurl='loading_listeslies.php?elemid=comp3_interior&comp3_interior='+comp3_interior+'&container_nr='+container_nr+'&ord_loading_id='+ord_loading_id;   
					var xhr = getXhr(); 
					xhr.onreadystatechange = function(){
						if(xhr.readyState == 4 ){
							leselect = xhr.responseText;   
							
							if(leselect == 1){
								$("#loadingComp3_interior").addClass("hide"); 
								
								$('#loading_overlay').addClass('hide');
								$('#loading_popup').addClass('hide');
							} 

							leselect = xhr.responseText;
						}
					};

					xhr.open("GET",resurl,true);
					xhr.send(null);
					
				} else { toastr.error('Image not uploaded.',{timeOut:15000}) }
            }
        });
    });
}


function LoadingStart() {
	
	var ord_loading_id = document.getElementById("ord_loading_id").value; 
	
	$('#loading_overlay').removeClass('hide');
	$('#loading_popup').removeClass('hide');
	
	$('#loading_start').on('submit', function(e) {

        e.preventDefault();
 
        var $form = $(this);
        var formdata = (window.FormData) ? new FormData($form[0]) : null;
        var data = (formdata !== null) ? formdata : $form.serialize();
		
		var img=0;
		
        $.ajax({
            url: $form.attr('action'),
            type: $form.attr('method'),
            contentType: false, // obligatoire pour de l'upload
            processData: false, // obligatoire pour de l'upload
            dataType: 'text', // selon le retour attendu
            data: data,
            success: function (response) {
				if(response==1){ 		
					var loading_start = document.getElementById("file14").value.replace(/C:\\fakepath\\/i, ''); 
					var container_nr = document.getElementById("container_nr").value;
					
					var resurl='loading_listeslies.php?elemid=loading_start&loading_start='+loading_start+'&container_nr='+container_nr+'&ord_loading_id='+ord_loading_id;   
					var xhr = getXhr(); 
					xhr.onreadystatechange = function(){
						if(xhr.readyState == 4 ){
							leselect = xhr.responseText;   
							
							if(leselect == 1){
								$("#loadingLoadingStart").addClass("hide"); 
								
								$('#loading_overlay').addClass('hide');
								$('#loading_popup').addClass('hide');
							} 

							leselect = xhr.responseText;
						}
					};

					xhr.open("GET",resurl,true);
					xhr.send(null);
					
				} else { toastr.error('Image not uploaded.',{timeOut:15000}) }
            }
        });
    });
}


function LoadingLevel() {
	
	var ord_loading_id = document.getElementById("ord_loading_id").value; 
	
	$('#loading_overlay').removeClass('hide');
	$('#loading_popup').removeClass('hide');
	
	$('#loading_level').on('submit', function(e) {

        e.preventDefault();
 
        var $form = $(this);
        var formdata = (window.FormData) ? new FormData($form[0]) : null;
        var data = (formdata !== null) ? formdata : $form.serialize();
 
		var img=0;
		
        $.ajax({
            url: $form.attr('action'),
            type: $form.attr('method'),
            contentType: false, // obligatoire pour de l'upload
            processData: false, // obligatoire pour de l'upload
            dataType: 'text', // selon le retour attendu
            data: data,
            success: function (response) {
				if(response==1){ 
					var loading_level = document.getElementById("file15").value.replace(/C:\\fakepath\\/i, ''); 
					var container_nr = document.getElementById("container_nr").value;
					
					var resurl='loading_listeslies.php?elemid=loading_level&loading_level='+loading_level+'&container_nr='+container_nr+'&ord_loading_id='+ord_loading_id;   
					var xhr = getXhr(); 
					xhr.onreadystatechange = function(){
						if(xhr.readyState == 4 ){
							leselect = xhr.responseText;   
							
							if(leselect == 1){
								$("#loadingLoadinglevel").addClass("hide"); 
								
								$('#loading_overlay').addClass('hide');
								$('#loading_popup').addClass('hide');
							} 

							leselect = xhr.responseText;
						}
					};

					xhr.open("GET",resurl,true);
					xhr.send(null);
					
				} else { toastr.error('Image not uploaded.',{timeOut:15000}) }
            }
        });
    });
}


function Fillinglevel() {
	
	var ord_loading_id = document.getElementById("ord_loading_id").value; 
	
	$('#loading_overlay').removeClass('hide');
	$('#loading_popup').removeClass('hide');
	
	$('#filling_level').on('submit', function(e) {

        e.preventDefault();
 
        var $form = $(this);
        var formdata = (window.FormData) ? new FormData($form[0]) : null;
        var data = (formdata !== null) ? formdata : $form.serialize();
 
		var img=0;
		
        $.ajax({
            url: $form.attr('action'),
            type: $form.attr('method'),
            contentType: false, // obligatoire pour de l'upload
            processData: false, // obligatoire pour de l'upload
            dataType: 'text', // selon le retour attendu
            data: data,
            success: function (response) {
				if(response==1){ 
					var filling_level = document.getElementById("file16").value.replace(/C:\\fakepath\\/i, ''); 
					var container_nr = document.getElementById("container_nr").value;
					
					var resurl='loading_listeslies.php?elemid=filling_level&filling_level='+filling_level+'&container_nr='+container_nr+'&ord_loading_id='+ord_loading_id;   
					var xhr = getXhr(); 
					xhr.onreadystatechange = function(){
						if(xhr.readyState == 4 ){
							leselect = xhr.responseText;   
							
							if(leselect == 1){
								$("#loadingFilling_level").addClass("hide"); 
								
								$('#loading_overlay').addClass('hide');
								$('#loading_popup').addClass('hide');
							} 

							leselect = xhr.responseText;
						}
					};

					xhr.open("GET",resurl,true);
					xhr.send(null);
					
				} else { toastr.error('Image not uploaded.',{timeOut:15000}) }
            }
        });
    });
}


function SamplesTin() {
	
	var ord_loading_id = document.getElementById("ord_loading_id").value; 
	
	$('#loading_overlay').removeClass('hide');
	$('#loading_popup').removeClass('hide');
	
	$('#photo_sample').on('submit', function(e) {

        e.preventDefault();
 
        var $form = $(this);
        var formdata = (window.FormData) ? new FormData($form[0]) : null;
        var data = (formdata !== null) ? formdata : $form.serialize();
 
		var img=0;
		
        $.ajax({
            url: $form.attr('action'),
            type: $form.attr('method'),
            contentType: false, // obligatoire pour de l'upload
            processData: false, // obligatoire pour de l'upload
            dataType: 'text', // selon le retour attendu
            data: data,
            success: function (response) {
				if(response==1){ 
					var photo_sample = document.getElementById("file17").value.replace(/C:\\fakepath\\/i, '');
					var container_nr = document.getElementById("container_nr").value;
					
					var resurl='loading_listeslies.php?elemid=photo_sample&photo_sample='+photo_sample+'&container_nr='+container_nr+'&ord_loading_id='+ord_loading_id;   
					var xhr = getXhr(); 
					xhr.onreadystatechange = function(){
						if(xhr.readyState == 4 ){
							leselect = xhr.responseText;   
							
							if(leselect == 1){
								$("#loadingPhoto_sample").addClass("hide"); 
								
								$('#loading_overlay').addClass('hide');
								$('#loading_popup').addClass('hide');
							} 

							leselect = xhr.responseText;
						}
					};

					xhr.open("GET",resurl,true);
					xhr.send(null);
					
				} else { toastr.error('Image not uploaded.',{timeOut:15000}) }
            }
        });
    });
}


function PhotoOfSeal2() {
	
	var ord_loading_id = document.getElementById("ord_loading_id").value; 
	
	$('#loading_overlay').removeClass('hide');
	$('#loading_popup').removeClass('hide');
	
	$('#seal2').on('submit', function(e) {

        e.preventDefault();
 
        var $form = $(this);
        var formdata = (window.FormData) ? new FormData($form[0]) : null;
        var data = (formdata !== null) ? formdata : $form.serialize();
		
		var img=0;
		
        $.ajax({
            url: $form.attr('action'),
            type: $form.attr('method'),
            contentType: false, // obligatoire pour de l'upload
            processData: false, // obligatoire pour de l'upload
            dataType: 'text', // selon le retour attendu
            data: data,
            success: function (response) {
				if(response==1){
					var seal2 = document.getElementById("file20").value.replace(/C:\\fakepath\\/i, '');
					var seal_2_nr = document.getElementById("seal_2_nr").value; 
					var container_nr = document.getElementById("container_nr").value; 
					
					var resurl='loading_listeslies.php?elemid=seal2&seal2='+seal2+'&seal_2_nr='+seal_2_nr+'&container_nr='+container_nr+'&ord_loading_id='+ord_loading_id;   
					var xhr = getXhr(); 
					xhr.onreadystatechange = function(){
						if(xhr.readyState == 4 ){
							leselect = xhr.responseText;   
							
							if(leselect == 1){
								$("#loadingSeal2").addClass("hide");

								$('#loading_overlay').addClass('hide');
								$('#loading_popup').addClass('hide');								
							} 

							leselect = xhr.responseText;
						}
					};

					xhr.open("GET",resurl,true);
					xhr.send(null);
					
				} else { toastr.error('Image not uploaded.',{timeOut:15000}) }
            }
        });
    });
}


function PhotoOfSeal3() {
	
	var ord_loading_id = document.getElementById("ord_loading_id").value; 
	
	$('#loading_overlay').removeClass('hide');
	$('#loading_popup').removeClass('hide');
	
	$('#seal3').on('submit', function(e) {

        e.preventDefault();
 
        var $form = $(this);
        var formdata = (window.FormData) ? new FormData($form[0]) : null;
        var data = (formdata !== null) ? formdata : $form.serialize();
		
		var img=0;
		
        $.ajax({
            url: $form.attr('action'),
            type: $form.attr('method'),
            contentType: false, // obligatoire pour de l'upload
            processData: false, // obligatoire pour de l'upload
            dataType: 'text', // selon le retour attendu
            data: data,
            success: function (response) {
				if(response==1){
					var seal3 = document.getElementById("file21").value.replace(/C:\\fakepath\\/i, '');
					var seal_3_nr = document.getElementById("seal_3_nr").value; 
					var container_nr = document.getElementById("container_nr").value; 
					
					var resurl='loading_listeslies.php?elemid=seal3&seal3='+seal3+'&ord_loading_id='+ord_loading_id+'&container_nr='+container_nr+'&seal_3_nr='+seal_3_nr;   
					var xhr = getXhr(); 
					xhr.onreadystatechange = function(){
						if(xhr.readyState == 4 ){
							leselect = xhr.responseText;   
							
							if(leselect == 1){
								$("#loadingSeal3").addClass("hide"); 
								
								$('#loading_overlay').addClass('hide');
								$('#loading_popup').addClass('hide');
							} 

							leselect = xhr.responseText;
						}
					};

					xhr.open("GET",resurl,true);
					xhr.send(null);
					
				} else { toastr.error('Image not uploaded.',{timeOut:15000}) }
            }
        });
    });
}


function PhotoOfSeal4() {
	
	var ord_loading_id = document.getElementById("ord_loading_id").value; 
	
	$('#loading_overlay').removeClass('hide');
	$('#loading_popup').removeClass('hide');
	
	$('#seal4').on('submit', function(e) {

        e.preventDefault();
 
        var $form = $(this);
        var formdata = (window.FormData) ? new FormData($form[0]) : null;
        var data = (formdata !== null) ? formdata : $form.serialize();
		
		var img=0;
		
        $.ajax({
            url: $form.attr('action'),
            type: $form.attr('method'),
            contentType: false, // obligatoire pour de l'upload
            processData: false, // obligatoire pour de l'upload
            dataType: 'text', // selon le retour attendu
            data: data,
            success: function (response) {
				if(response==1){
					var seal4 = document.getElementById("file22").value.replace(/C:\\fakepath\\/i, '');
					var container_nr = document.getElementById("container_nr").value; 
					var seal_4_nr = document.getElementById("seal_4_nr").value; 
					
					var resurl='loading_listeslies.php?elemid=seal4&seal4='+seal4+'&container_nr='+container_nr+'&seal_4_nr='+seal_4_nr+'&ord_loading_id='+ord_loading_id;   
					var xhr = getXhr(); 
					xhr.onreadystatechange = function(){
						if(xhr.readyState == 4 ){
							leselect = xhr.responseText;   
							
							if(leselect == 1){
								$("#loadingSeal4").addClass("hide"); 
								
								$('#loading_overlay').addClass('hide');
								$('#loading_popup').addClass('hide');
							} 

							leselect = xhr.responseText;
						}
					};

					xhr.open("GET",resurl,true);
					xhr.send(null);
					
				} else { toastr.error('Image not uploaded.',{timeOut:15000}) }
            }
        });
    });
}


function WeightTicket() {
	
	var ord_loading_id = document.getElementById("ord_loading_id").value; 
	
	$('#loading_overlay').removeClass('hide');
	$('#loading_popup').removeClass('hide');
	
	$('#vgm').on('submit', function(e) {

        e.preventDefault();
 
        var $form = $(this);
        var formdata = (window.FormData) ? new FormData($form[0]) : null;
        var data = (formdata !== null) ? formdata : $form.serialize();
		
		var img=0;
		
        $.ajax({
            url: $form.attr('action'),
            type: $form.attr('method'),
            contentType: false, // obligatoire pour de l'upload
            processData: false, // obligatoire pour de l'upload
            dataType: 'text', // selon le retour attendu
            data: data,
            success: function (response) {
				if(response==1){ 
					var vgm = document.getElementById("file18").value.replace(/C:\\fakepath\\/i, '');
					var container_nr = document.getElementById("container_nr").value;
					var vgm_weight = document.getElementById("vgm_weight").value;
					
					var resurl='loading_listeslies.php?elemid=vgm&vgm='+vgm+'&container_nr='+container_nr+'&vgm_weight='+vgm_weight+'&ord_loading_id='+ord_loading_id;   
					var xhr = getXhr(); 
					xhr.onreadystatechange = function(){
						if(xhr.readyState == 4 ){
							leselect = xhr.responseText;   
							
							if(leselect == 1){
								$("#loadingVgm").addClass("hide");

								$('#loading_overlay').addClass('hide');
								$('#loading_popup').addClass('hide');								
							} 

							leselect = xhr.responseText;
						}
					};

					xhr.open("GET",resurl,true);
					xhr.send(null);
					
				} else { toastr.error('Image not uploaded.',{timeOut:15000}) }
            }
        });
    });
}


function RearoutletSeal1(){
	var rear_valve = '';

	if($("input[type='radio'].rear_valveRadio").is(':checked')) {
		rear_valve = $("input[type='radio'].rear_valveRadio:checked").val();
	}
	
	var ord_loading_id = document.getElementById("ord_loading_id").value; 
	var container_nr = document.getElementById("container_nr").value;
	var seal_1_nr = document.getElementById("seal_1_nr").value;
	
	if(rear_valve){
		var resurl='loading_listeslies.php?elemid=loading_rear_valve&rear_valve='+rear_valve+'&seal_1_nr='+seal_1_nr+'&container_nr='+container_nr+'&ord_loading_id='+ord_loading_id;   
		var xhr = getXhr(); 
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;   
				
				if(leselect == 1){
					$("#RearoutletSeal1").addClass("hide");
				} 

				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
	}
}


function LoadingEnd(){

	var ord_loading_id = document.getElementById("ord_loading_id").value; 
	var measure_level = document.getElementById("measure_level").value; 
	
	if(measure_level){
		var resurl='loading_listeslies.php?elemid=loading_measure_level&measure_level='+measure_level+'&ord_loading_id='+ord_loading_id;   
		var xhr = getXhr(); 
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;   
				
				if(leselect == 1){
					$("#LoadingEnd").addClass("hide");
				} 

				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
	}
}


function TakeSamples(){
	var take_samples = '';

	if($("input[type='radio'].take_samplesRadio").is(':checked')) {
		take_samples = $("input[type='radio'].take_samplesRadio:checked").val();
	}
	
	var ord_loading_id = document.getElementById("ord_loading_id").value; 
	
	if(take_samples){
		var resurl='loading_listeslies.php?elemid=loading_take_samples&take_samples='+take_samples+'&ord_loading_id='+ord_loading_id;   
		var xhr = getXhr(); 
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;   
				
				if(leselect == 1){
					$("#TakeSamples").addClass("hide");
				} 

				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
	}
}


function VisuelContainer(){
	var interior_inspection = '';

	if($("input[type='radio'].interior_inspectionRadio").is(':checked')) {
		interior_inspection = $("input[type='radio'].interior_inspectionRadio:checked").val();
	}
	
	var ord_loading_id = document.getElementById("ord_loading_id").value; 
	
	if(interior_inspection){
		var resurl='loading_listeslies.php?elemid=loading_interior_inspection&interior_inspection='+interior_inspection+'&ord_loading_id='+ord_loading_id;   
		var xhr = getXhr(); 
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;   
				
				if(leselect == 1){
					$("#VisuelContainer").addClass("hide");
				} 

				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
	}
}


function ContainerLocks1(){
	var container_lock = '';

	if($("input[type='radio'].container_lockRadio").is(':checked')) {
		container_lock = $("input[type='radio'].container_lockRadio:checked").val();
	}
	
	var ord_loading_id = document.getElementById("ord_loading_id").value; 
	
	if(container_lock){
		var resurl='loading_listeslies.php?elemid=loading_container_lock&container_lock='+container_lock+'&ord_loading_id='+ord_loading_id;   
		var xhr = getXhr(); 
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;   
				
				if(leselect == 1){
					$("#ContainerLocks1").addClass("hide");
				} 

				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
	}
}


function ContainerLocks2() {
	
	var ord_loading_id = document.getElementById("ord_loading_id").value; 
	
	$('#loading_overlay').removeClass('hide');
	$('#loading_popup').removeClass('hide');
	
	$('#container_lock_photo').on('submit', function(e) {

        e.preventDefault();
 
        var $form = $(this);
        var formdata = (window.FormData) ? new FormData($form[0]) : null;
        var data = (formdata !== null) ? formdata : $form.serialize();
		
		var img=0;
		
        $.ajax({
            url: $form.attr('action'),
            type: $form.attr('method'),
            contentType: false, // obligatoire pour de l'upload
            processData: false, // obligatoire pour de l'upload
            dataType: 'text', // selon le retour attendu
            data: data,
            success: function (response) {
				if(response==1){ 
					var container_lock_photo = document.getElementById("file19").value.replace(/C:\\fakepath\\/i, '');
					var container_nr = document.getElementById("container_nr").value;
					
					var resurl='loading_listeslies.php?elemid=container_lock_photo&container_lock_photo='+container_lock_photo+'&container_nr='+container_nr+'&ord_loading_id='+ord_loading_id;   
					var xhr = getXhr(); 
					xhr.onreadystatechange = function(){
						if(xhr.readyState == 4 ){
							leselect = xhr.responseText;   
							
							if(leselect == 1){
								$("#ContainerLocks2").addClass("hide"); 
								
								$('#loading_overlay').addClass('hide');
								$('#loading_popup').addClass('hide');
							} 

							leselect = xhr.responseText;
						}
					};

					xhr.open("GET",resurl,true);
					xhr.send(null);
					
				} else { toastr.error('Image not uploaded.',{timeOut:15000}) }
            }
        });
    });
}


function Endloading() {
	var ord_loading_id = document.getElementById("ord_loading_id").value; 
	
	var resurl='loading_listeslies.php?elemid=end_loading&ord_loading_id='+ord_loading_id;   
	var xhr = getXhr(); 
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;   
		
			if(leselect == 1){
				$("#endLBtn").addClass("hide"); 
			} 

			leselect = xhr.responseText;
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}


function getXhr(){
	var xhr = null;
    if(window.XMLHttpRequest) {
		xhr = new XMLHttpRequest();

	} else if(window.ActiveXObject) {
		try {
			xhr = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			xhr = new ActiveXObject("Microsoft.XMLHTTP");
		}

    } else {
        alert("Votre navigateur ne supporte pas les objets XMLHTT");
        xhr = false;
    }

    return xhr;
}

