var blanc = '';

var change_lien1 = 0;	 

var change_lien2 = 0;	 

var img_a_supp = '';	 

  function is_int(value){

		  if((parseFloat(value) == parseInt(value)) && !isNaN(value)){ 

			  return true;

		  } else { 

			  return false;

		  }

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



     function enregistrer(param,action,id){

	

	    // alert(param+"/"+action);

	  

	  if ( param == "story"){

		  

		  

		  

		   if (action == "delete"){

				 

						  if(confirm("Do you want to delete ?")){

			   	

							} else {

							

							   return;

							 

						   }

				 var country_id = document.getElementById('story_country0').value;

		    	  var resurl='listeslies_stories.php?elemid=enregistrer&param='+param+'&action='+action+'&id='+id+'&img_a_supp='+img_a_supp+'&country_id='+country_id; //alert(resurl);

		    } else 

			{

					var story_country = document.getElementById('story_country').value; 

					var story_exporter = document.getElementById('story_exporter').value; 

					var story_culture = document.getElementById('story_culture').value; 

					var story_titleen = document.getElementById('story_titleen').value; 

					var story_titlede = document.getElementById('story_titlede').value; 

					var story_titlefr = document.getElementById('story_titlefr').value; 

					var story_titlept = document.getElementById('story_titlept').value; 

					var story_titlees = document.getElementById('story_titlees').value; 

					var story_media_type = document.getElementById('story_media_type').value; 

					var country_id = document.getElementById('story_country0').value;

					

				   

					if (story_country == ''){

					

					   document.getElementById("update-alert2").style.display = "none";

					   document.getElementById("update-alert").style.display = "inline";

					   document.getElementById('update-alert').innerHTML = 'You didn\'t select the country !';	  

					   return;

					} else 

						

					if (story_exporter == ''){

					

					   document.getElementById("update-alert2").style.display = "none";

					   document.getElementById("update-alert").style.display = "inline";

					   document.getElementById('update-alert').innerHTML = 'You didn\'t select the exporter !';	  

					   return;

					} else

					 if (story_culture == ''){

					

					   document.getElementById("update-alert2").style.display = "none";

					   document.getElementById("update-alert").style.display = "inline";

					   document.getElementById('update-alert').innerHTML = 'You didn\'t select the culture !';	  

					   return;

					} else 

					if (story_media_type == ''){

					

					   document.getElementById("update-alert2").style.display = "none";

					   document.getElementById("update-alert").style.display = "inline";

					   document.getElementById('update-alert').innerHTML = 'You didn\'t select the media type !';	



					   return;

					} else

					{  

				         var story_media_link = '';

				         if (story_media_type == 1)

						 { 

					        var story_media_link = document.getElementById('story_media_link').value; 

						 } else 

						  if (story_media_type == 2) {

							  

							  // if(change_lien1 == 0){

	   

								   // var story_media_link = "0" ;

							   

							   // } else {

							 

							       var story_media_link = document.getElementById("upload-file-info").innerHTML ;

							  // } 

						 }

					

						

						  var resurl='listeslies_stories.php?elemid=enregistrer&param='+param+'&action='+action+'&id='+id+'&story_country='+story_country+'&story_exporter='+story_exporter

									 +'&story_culture='+story_culture+'&story_titleen='+story_titleen+'&story_titlede='+story_titlede

									 +'&story_titlefr='+story_titlefr+'&story_titlept='+story_titlept+'&story_titlees='+story_titlees

									 +'&story_media_type='+story_media_type+'&story_media_link='+story_media_link+'&change_lien1='+change_lien1+'&img_a_supp='+img_a_supp+'&country_id='+country_id; //alert(resurl);

					  

				    }	

		  }  

			var xhr = getXhr();

                

                xhr.onreadystatechange = function(){     

                    // On ne fait quelque chose que si on a tout reçu et que le serveur est ok

                    if(xhr.readyState == 4 ){

                        //alert("ko");

                        leselect = xhr.responseText;    //alert(leselect);

                         var val=leselect;  

                         var val1=val.split('##');	



                           if (val1[0] == 1) {

						 

						     

								 if (action == "delete"){

									 

									 

								 } else {

									  document.getElementById("updateform").reset();

							

						           document.getElementById("update-alert").style.display = "none";

				                   document.getElementById("update-alert2").style.display = "inline";

								   toastr.success(val1[1],{timeOut:15000})	

	                               document.getElementById('update-alert2').innerHTML = val1[1];

									 

									 

								 }

								  

							

							document.getElementById('list_stories').innerHTML = val1[2];

							

							 var options2 = {

									 valueNames: [ 'title','country']

								};



							 var userList = new List('users1', options2);

							

							

	                        

						 }	else {

						 

						    document.getElementById("update-alert2").style.display = "none";

				            document.getElementById("update-alert").style.display = "inline";

							toastr.error(val1[1],{timeOut:15000})	

	                        document.getElementById('update-alert').innerHTML = val1[1];

						 

						 

						 }					 



				         

						

						

						

		                 leselect = xhr.responseText;

                  }    

                };

                xhr.open("GET",resurl,true);

                xhr.send(null);  

		  

		  



	  } else 

	   if ( param == "step"){

		  

		  

		  

		   if (action == "delete"){

				 

						  if(confirm("Do you want to delete ?")){

			   	

							} else {

							

							   return;

							 

						   }

						   

						   var country_id = document.getElementById('step_country0').value;

					var story_id0 = document.getElementById('step_story0').value;

				 

		    	  var resurl='listeslies_stories.php?elemid=enregistrer&param='+param+'&action='+action+'&id='+id+'&img_a_supp='+img_a_supp+'&country_id='+country_id+'&story_id0='+story_id0; //alert(resurl);

		    } else 

			{ 

					var story_id = document.getElementById('story_id').value; 

					var seq_number = document.getElementById('seq_number').value; 

					var seq_texten = document.getElementById('seq_texten').value; 

					var seq_textde = document.getElementById('seq_textde').value; 

					var seq_textfr = document.getElementById('seq_textfr').value; 

					var seq_textpt = document.getElementById('seq_textpt').value; 

					var seq_textes = document.getElementById('seq_textes').value;

					var step_media_type = document.getElementById('step_media_type').value; 

					var coordx = document.getElementById('coordx').value; 

					var coordy = document.getElementById('coordy').value; 

					var country_id = document.getElementById('step_country0').value;

					var story_id0 = document.getElementById('step_story0').value;

					

			

					if (story_id == ''){

					

					   document.getElementById("update-alert2").style.display = "none";

					   document.getElementById("update-alert").style.display = "inline";

					    toastr.error('You didn\'t select the story !',{timeOut:15000})	

					   document.getElementById('update-alert').innerHTML = 'You didn\'t select the story !';	  

					   return;

					} else 

					 if (seq_number == ''){

					

					   document.getElementById("update-alert2").style.display = "none";

					   document.getElementById("update-alert").style.display = "inline";

					    toastr.error('You didn\'t enter the sequence number !',{timeOut:15000})	

					   document.getElementById('update-alert').innerHTML = 'You didn\'t enter the sequence number !';	  

					   return;

					} else 

						if (!is_int(seq_number)){ 

		

					   document.getElementById("update-alert2").style.display = "none"; 

					   document.getElementById("update-alert").style.display = "inline";

					    toastr.error('Sequence number is not correct !',{timeOut:15000})	

					   document.getElementById('update-alert').innerHTML = 'Sequence number is not correct !';	  

					   return;

					  } else

					if (step_media_type == ''){

					

					   document.getElementById("update-alert2").style.display = "none";

					   document.getElementById("update-alert").style.display = "inline";

					   toastr.error('You didn\'t select the media type !',{timeOut:15000})	

					   document.getElementById('update-alert').innerHTML = 'You didn\'t select the media type !';	  

					   return;

					} else 

						

					

			          if (isNaN(coordx)){ 

		

					   document.getElementById("update-alert2").style.display = "none"; 

					   document.getElementById("update-alert").style.display = "inline";

					   toastr.error('coord X is not correct !',{timeOut:15000})			

					   document.getElementById('update-alert').innerHTML = 'coord X is not correct !';	

                      														   

					   return;

					  } else

					  if (isNaN(coordy)){ 

		

					   document.getElementById("update-alert2").style.display = "none"; 

					   document.getElementById("update-alert").style.display = "inline";

					   toastr.error('coord Y is not correct !',{timeOut:15000})	

					   document.getElementById('update-alert').innerHTML = 'coord Y is not correct !';	  

					   return;

					  } else

					 if (coordx == ''){

					

					   document.getElementById("update-alert2").style.display = "none";

					   document.getElementById("update-alert").style.display = "inline";

					    toastr.error('You didn\'t enter the coordinate X !',{timeOut:15000})	

					   document.getElementById('update-alert').innerHTML = 'You didn\'t enter the coordinate X !';	  

					   return;

					} else 

					if (coordy == ''){

					

					   document.getElementById("update-alert2").style.display = "none";

					   document.getElementById("update-alert").style.display = "inline";

					    toastr.error('You didn\'t enter the coordinate Y !',{timeOut:15000})	

					   document.getElementById('update-alert').innerHTML = 'You didn\'t enter the coordinate Y !';	  

					   return;

					}else

					{



                         var step_media_link = '';

				         if (step_media_type == 1)

						 { 

					        var step_media_link = document.getElementById('step_media_link').value; 

						 } else 

						  if (step_media_type == 2) {

							  

							  // if(change_lien1 == 0){

	   

								   // var story_media_link = "0" ;

							   

							   // } else {

							 

							       var step_media_link = document.getElementById("upload-file-info").innerHTML ;

							  // } 

						 }

					

					

						

						  var resurl='listeslies_stories.php?elemid=enregistrer&param='+param+'&action='+action+'&id='+id+'&story_id='+story_id

									 +'&seq_number='+seq_number+'&seq_texten='+seq_texten+'&seq_textde='+seq_textde

									 +'&seq_textfr='+seq_textfr+'&seq_textpt='+seq_textpt+'&seq_textes='+seq_textes

									 +'&step_media_type='+step_media_type+'&coordx='+coordx+'&coordy='+coordy

									 +'&step_media_link='+step_media_link+'&change_lien2='+change_lien2+'&img_a_supp='+img_a_supp+'&country_id='+country_id+'&story_id0='+story_id0; //alert(resurl);

					  

					 

					  

					  

						   }	

		  }  

			var xhr = getXhr();

                

                xhr.onreadystatechange = function(){     

                    // On ne fait quelque chose que si on a tout reçu et que le serveur est ok

                    if(xhr.readyState == 4 ){

                        //alert("ko");

                        leselect = xhr.responseText;   // alert(leselect);

                         var val=leselect;  

                         var val1=val.split('##');	



                           if (val1[0] == 1) {

						 

						     

								 if (action == "delete"){

									 

									 

								 } else {

									  document.getElementById("updateform").reset();

							

						           document.getElementById("update-alert").style.display = "none";

				                   document.getElementById("update-alert2").style.display = "inline";

								    toastr.success(val1[1],{timeOut:15000})	

	                               document.getElementById('update-alert2').innerHTML = val1[1];

								   

								    

									 

									 

								 }

								  

							

							document.getElementById('list_steps').innerHTML = val1[2];

							

							// map.removeLayer(drawnItems);

							drawnItems.clearLayers(); 

							

							 var options2 = {

									 valueNames: [ 'story_id','content']

								};



							 var userList2 = new List('users2', options2);

							

							

	                        

						 }	else {

						 

						    document.getElementById("update-alert2").style.display = "none";

				            document.getElementById("update-alert").style.display = "inline";

							toastr.error(val1[1],{timeOut:15000})	

	                        document.getElementById('update-alert').innerHTML = val1[1];

						 

						 

						 }					 



				         

						

						

						

		                 leselect = xhr.responseText;

                  }    

                };

                xhr.open("GET",resurl,true);

                xhr.send(null);  

		  

		  



	  }



     }	 

	 

function escapeTags( str ) {

  return String( str )

           .replace( /&/g, '&amp;' )

           .replace( /"/g, '&quot;' )

           .replace( /'/g, '&#39;' )

           .replace( /</g, '&lt;' )

           .replace( />/g, '&gt;' );

}

function delete_file(nom){

	

	 if(nom == 'story'){

		  img_a_supp = document.getElementById('upload-file-info').innerHTML;	 

		  document.getElementById('upload-file-info').innerHTML="";

		  document.getElementById('delete_justif').style.display="none";

		  msgBox.innerHTML = '';

		   change_lien1 = 1;

	  } else 

	if(nom == 'step'){

		  img_a_supp = document.getElementById('upload-file-info').innerHTML;	 

		  document.getElementById('upload-file-info').innerHTML="";

		  document.getElementById('delete_justif').style.display="none";

		  msgBox.innerHTML = '';

		   change_lien2 = 1;

	  }

	  

	  

}

     

function select(param){ 
	if (param == 'story_country0'){
		var country_id = document.getElementById('story_country0').value;

		var resurl='listeslies_stories.php?elemid=select&param='+param+'&country_id='+country_id;  //alert(resurl);
		var xhr = getXhr();
		
		xhr.onreadystatechange = function(){     
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;    
				var val=leselect;  
				
				document.getElementById('list_stories').innerHTML = val;

				var options2 = {
					valueNames: [ 'title','country']
				};

				var userList = new List('users1', options2);

				leselect = xhr.responseText;
			}    
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);  

	} else

		if (param == 'step_country0'){

		  

		 var country_id = document.getElementById('step_country0').value;

		 

		 document.getElementById('step_story0').options[0].selected=true;  

		 

		var resurl='listeslies_stories.php?elemid=select&param='+param+'&country_id='+country_id; //alert(resurl);

		 

						var xhr = getXhr();

							

							xhr.onreadystatechange = function(){     

								// On ne fait quelque chose que si on a tout reçu et que le serveur est ok

								if(xhr.readyState == 4 ){

									//alert("ko");

									leselect = xhr.responseText;   // alert(leselect);

									 var val=leselect;  

									  var val1=val.split('##');	



							           document.getElementById('list_steps').innerHTML = val1[0];

							           document.getElementById('step_story0').innerHTML = val1[1];

										

										 var options2 = {

												 valueNames: [ 'story_id','content']

											};



										 var userList2 = new List('users2', options2);

									

									 leselect = xhr.responseText;

							  }    

							};

							xhr.open("GET",resurl,true);

							xhr.send(null);  

	   } else

		if (param == 'route_country'){

		  

		 var country_id = document.getElementById('route_country').value;

		 

		 document.getElementById('route_story').options[0].selected=true;  

		 

		var resurl='listeslies_stories.php?elemid=select&param='+param+'&country_id='+country_id; //alert(resurl);

		 

						var xhr = getXhr();

							

							xhr.onreadystatechange = function(){     

								// On ne fait quelque chose que si on a tout reçu et que le serveur est ok

								if(xhr.readyState == 4 ){

									//alert("ko");

									leselect = xhr.responseText;   // alert(leselect);

									 var val=leselect;  

									  // var val1=val.split('##');	



							           document.getElementById('route_story').innerHTML = val;

							          

										

										

									 leselect = xhr.responseText;

							  }    

							};

							xhr.open("GET",resurl,true);

							xhr.send(null);  

	   } else

		 if (param == 'step_story0'){

		  

		 var story_id = document.getElementById('step_story0').value;

		 

		  

		 

		var resurl='listeslies_stories.php?elemid=select&param='+param+'&story_id='+story_id; //alert(resurl);

		 

						var xhr = getXhr();

							

							xhr.onreadystatechange = function(){     

								// On ne fait quelque chose que si on a tout reçu et que le serveur est ok

								if(xhr.readyState == 4 ){

									//alert("ko");

									leselect = xhr.responseText;   // alert(leselect);

									 var val=leselect;  

									  // var val1=val.split('##');	



							           document.getElementById('list_steps').innerHTML = val;

							          

										

										 var options2 = {

												 valueNames: [ 'story_id','content']

											};



										 var userList2 = new List('users2', options2);

									

									 leselect = xhr.responseText;

							  }    

							};

							xhr.open("GET",resurl,true);

							xhr.send(null); 

	   } else

		 if (param == 'story_country'){

		  

		 var country_id = document.getElementById('story_country').value;

		 

		 var dom_step = '<option value="">-----</option>'; 

		 

		   // for (j in json_exporter) {

              // if (json_exporter[j].properties. == country_id){

		        // dom_step += '<option value="'+json_exporter[j].properties.id_contact+'">'+json_exporter[j].properties.firstname+'</option>';

			  // }

		   // }

		   

		  // document.getElementById('story_exporter').innerHTML =  dom_step; 

	   } else 

	   if (param == 'story_mediatype')  { 

		   var story_media_type = document.getElementById('story_media_type').value;

		   

		    if (story_media_type == 1){

				document.getElementById('media').innerHTML =  ' <label for="first-name">Youtube video ID (Exemple: vjcddSIfLcg): </label>'+blanc

															  +'<input type="text" class="form-control" id="story_media_link" value="" > '; 

				

			} else

			if (story_media_type == 2){

				document.getElementById('media').innerHTML =  '<label for="last-name">Join image (Format JPG ; Filesize < 5 Mo):</label>'+blanc

																	+' <div style="position:relative;">'+blanc

																				

																	+'			  <div class="row" style="padding-top:10px;">'+blanc

																	+'				<div class="col-xs-2">'+blanc

																+'					  <button id="uploadBtn" class="btn btn-large btn-primary">Load File</button>'+blanc

																	+'				</div>'+blanc

																	+'				<div class="col-xs-10">'+blanc

																	+'			  <div id="progressOuter" class="progress progress-striped active" style="display:none;">'+blanc

																	+'				<div id="progressBar" class="progress-bar progress-bar-success"  role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 0%">'+blanc

																	+'				</div>'+blanc

																	+'			  </div>'+blanc

																	+'				</div>'+blanc

																	+'			  </div>'+blanc

																	+'			  <div class="row" style="padding-top:10px;">'+blanc

																	+'				<div class="col-xs-10">'+blanc

																	+'				  <div id="msgBox">'+blanc

																	+'				  </div>'+blanc

																	+'				</div>'+blanc

																	+'			  </div>'+blanc

																			 

																	+'		  <a href="#" style="display:none;" id="delete_justif"  onclick="delete_file(\'story\')">'+blanc

																	+'		  <i class="fa fa-trash white" style="color:red"></i>'+blanc

																	+'		  </a>&nbsp;<span class=\'label label-info\' id="upload-file-info"></span>'+blanc

																			

																	+'</div>';

					

					

					

					

					 var btn = document.getElementById('uploadBtn'),

						  progressBar = document.getElementById('progressBar'),

						  progressOuter = document.getElementById('progressOuter'),

						  msgBox = document.getElementById('msgBox');

					

					 

					  var uploader = new ss.SimpleUpload({

							button: btn,

							url: 'file_upload.php',

							name: 'uploadfile',

							hoverClass: 'hover',

							focusClass: 'focus',

							allowedExtensions: ['jpg','jpeg','png'],

							responseType: 'json',

							startXHR: function() {

								progressOuter.style.display = 'block'; // make progress bar visible

								this.setProgressBar( progressBar );

							},

							onSubmit: function() {

								msgBox.innerHTML = ''; // empty the message box

								btn.innerHTML = 'Chargement...'; // change button text to "Uploading..."

							  },

						onComplete: function( filename, response ) {

							btn.innerHTML = 'Choisir un fichier';

							progressOuter.style.display = 'none'; // hide progress bar when upload is completed



							if ( !response ) {

								msgBox.innerHTML = 'Unable to upload file';

								return;

							}



            if ( response.success === true ) {

                msgBox.innerHTML = 'Image loaded successfull.';

				 // alert(1);

				 // alert(escapeTags( filename ));

				document.getElementById('upload-file-info').innerHTML = escapeTags(filename);

				document.getElementById('delete_justif').style.display="inline";

				// alert(escapeTags(filename));

				

				change_lien1 = 1;



            } else {

                if ( response.msg )  {

                    msgBox.innerHTML = escapeTags( response.msg );



                } else {

                    msgBox.innerHTML = 'An error occurred and the upload failed.';

                }

            }

          },

        onError: function() {

            progressOuter.style.display = 'none';

            msgBox.innerHTML = 'Unable to upload file';

          }

	});

			

					

					

			} else 

			{

				document.getElementById('media').innerHTML =  ''; 

				

			}

		   

	   } else 

	if (param == 'step_mediatype')  { 

		   var step_media_type = document.getElementById('step_media_type').value;

		   

		    if (step_media_type == 1){

				document.getElementById('media').innerHTML =  ' <label for="first-name">Youtube video ID (Exemple: vjcddSIfLcg): </label>'+blanc

															  +'<input type="text" class="form-control" id="step_media_link" value="" > '; 

			} else

			if (step_media_type == 2){

				document.getElementById('media').innerHTML =  '<label for="last-name">Join image (Format JPG ; Filesize < 5 Mo):</label>'+blanc

																	+' <div style="position:relative;">'+blanc

																				

																	+'			  <div class="row" style="padding-top:10px;">'+blanc

																	+'				<div class="col-xs-2">'+blanc

																+'					  <button id="uploadBtn" class="btn btn-large btn-primary">Load File</button>'+blanc

																	+'				</div>'+blanc

																	+'				<div class="col-xs-10">'+blanc

																	+'			  <div id="progressOuter" class="progress progress-striped active" style="display:none;">'+blanc

																	+'				<div id="progressBar" class="progress-bar progress-bar-success"  role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 0%">'+blanc

																	+'				</div>'+blanc

																	+'			  </div>'+blanc

																	+'				</div>'+blanc

																	+'			  </div>'+blanc

																	+'			  <div class="row" style="padding-top:10px;">'+blanc

																	+'				<div class="col-xs-10">'+blanc

																	+'				  <div id="msgBox">'+blanc

																	+'				  </div>'+blanc

																	+'				</div>'+blanc

																	+'			  </div>'+blanc

																			 

																	+'		  <a href="#" style="display:none;" id="delete_justif"  onclick="delete_file(\'step\')">'+blanc

																	+'		  <i class="fa fa-trash white" style="color:red"></i>'+blanc

																	+'		  </a>&nbsp;<span class=\'label label-info\' id="upload-file-info"></span>'+blanc

																			

																	+'</div>';

					

					

					

					

					 var btn = document.getElementById('uploadBtn'),

						  progressBar = document.getElementById('progressBar'),

						  progressOuter = document.getElementById('progressOuter'),

						  msgBox = document.getElementById('msgBox');

					

					 

					  var uploader = new ss.SimpleUpload({

							button: btn,

							url: 'file_upload.php',

							name: 'uploadfile',

							hoverClass: 'hover',

							focusClass: 'focus',

							allowedExtensions: ['jpg','jpeg','png'],

							responseType: 'json',

							startXHR: function() {

								progressOuter.style.display = 'block'; // make progress bar visible

								this.setProgressBar( progressBar );

							},

							onSubmit: function() {

								msgBox.innerHTML = ''; // empty the message box

								btn.innerHTML = 'Chargement...'; // change button text to "Uploading..."

							  },

						onComplete: function( filename, response ) {

							btn.innerHTML = 'Choisir un fichier';

							progressOuter.style.display = 'none'; // hide progress bar when upload is completed



							if ( !response ) {

								msgBox.innerHTML = 'Unable to upload file';

								return;

							}



            if ( response.success === true ) {

                msgBox.innerHTML = 'Image loaded successfull.';

				 // alert(1);

				 // alert(escapeTags( filename ));

				document.getElementById('upload-file-info').innerHTML = escapeTags(filename);

				document.getElementById('delete_justif').style.display="inline";

				// alert(escapeTags(filename));

				

				change_lien2 = 1;



            } else {

                if ( response.msg )  {

                    msgBox.innerHTML = escapeTags( response.msg );



                } else {

                    msgBox.innerHTML = 'An error occurred and the upload failed.';

                }

            }

          },

        onError: function() {

            progressOuter.style.display = 'none';

            msgBox.innerHTML = 'Unable to upload file';

          }

	});

			

					

				

					

			} else 

			{

				document.getElementById('media').innerHTML =  ''; 

				

			}

		   

	   }

	   

	   

	 

	 }

	 
function fenetre(param,action,id){ 
	change_lien1 = 0;
	change_lien2 = 0;
	img_a_supp = '';

	$('#modal').modal();
	document.getElementById("update-alert").style.display = "none";
	document.getElementById("update-alert2").style.display = "none";
	document.getElementById('contenu_modal').innerHTML =  "";

	if (param == 'story') {
		if (action == 'add') {
			document.getElementById('myModalLabel').innerHTML =  '<i class="fa fa-plus-square" style="color:#1ab394"></i>&nbsp;&nbsp;Add Story Form'; 
			
			var resurl='listeslies_stories.php?elemid=fenetre&param='+param+'&action='+action+'&id='+id; //alert(resurl);
			var xhr = getXhr();
			
			xhr.onreadystatechange = function(){     
				if(xhr.readyState == 4 ){
					leselect = xhr.responseText;    //alert(leselect);
					var val=leselect;  
					
					document.getElementById('contenu_modal').innerHTML = val;

					leselect = xhr.responseText;
				}    
			};

			xhr.open("GET",resurl,true);
			xhr.send(null); 
			
		} else
		if (action == 'modif') {
			document.getElementById('myModalLabel').innerHTML =  '<i class="fa fa-pencil-square-o" style="color:#1ab394"></i>&nbsp;&nbsp;Modify Story Form'; 
			
			var resurl='listeslies_stories.php?elemid=fenetre&param='+param+'&action='+action+'&id='+id; //alert(resurl);
			var xhr = getXhr();

			xhr.onreadystatechange = function(){     
				if(xhr.readyState == 4 ){
					leselect = xhr.responseText;    //alert(leselect);
					var val=leselect;  
					
					document.getElementById('contenu_modal').innerHTML = val;

					var btn = document.getElementById('uploadBtn'),
					progressBar = document.getElementById('progressBar'),
					progressOuter = document.getElementById('progressOuter'),
					msgBox = document.getElementById('msgBox');

					var uploader = new ss.SimpleUpload({
						button: btn,
						url: 'file_upload.php',
						name: 'uploadfile',
						hoverClass: 'hover',
						focusClass: 'focus',
						allowedExtensions: ['jpg','jpeg','png'],
						responseType: 'json',
						startXHR: function() {
							progressOuter.style.display = 'block'; // make progress bar visible
							this.setProgressBar( progressBar );
						},
						
						onSubmit: function() {
							msgBox.innerHTML = ''; // empty the message box
							btn.innerHTML = 'Chargement...'; // change button text to "Uploading..."
						},
						
						onComplete: function( filename, response ) {
							btn.innerHTML = 'Choisir un fichier';
							progressOuter.style.display = 'none'; // hide progress bar when upload is completed

							if ( !response ) {
								msgBox.innerHTML = 'Unable to upload file';
								return;
							}

							if ( response.success === true ) {
								msgBox.innerHTML = 'Image loaded successfull.';
								document.getElementById('upload-file-info').innerHTML = escapeTags(filename);
								document.getElementById('delete_justif').style.display="inline";

								change_lien1 = 1;
							} else {
								if ( response.msg )  {
									msgBox.innerHTML = escapeTags( response.msg );
								} else {
									msgBox.innerHTML = 'An error occurred and the upload failed.';
								}
							}
						},

						onError: function() {
							progressOuter.style.display = 'none';
							msgBox.innerHTML = 'Unable to upload file';
						}
					});
					
					leselect = xhr.responseText;
				}    
			};

			xhr.open("GET",resurl,true);
			xhr.send(null);  

		}
		
	} else 
	if (param == 'step') {
		if (action == 'add') { 
			document.getElementById('myModalLabel').innerHTML =  '<i class="fa fa-plus-square" style="color:#1ab394"></i>&nbsp;&nbsp;Add Sequence Form'; 

			var resurl='listeslies_stories.php?elemid=fenetre&param='+param+'&action='+action+'&id='+id; //alert(resurl);
			var xhr = getXhr();

			xhr.onreadystatechange = function(){     
				if(xhr.readyState == 4 ){
					leselect = xhr.responseText;    //alert(leselect);
					var val=leselect;  
					
					document.getElementById('contenu_modal').innerHTML = val;
					var osmUrl = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
					osmAttrib = '&copy; <a href="https://openstreetmap.org/copyright">OpenStreetMap</a> contributors',
					osm = L.tileLayer(osmUrl, {maxZoom: 18, attribution: osmAttrib});
					
					map = new L.Map('storymap', {center: new L.LatLng(0, 0), zoom: 2}),
					drawnItems = L.featureGroup().addTo(map);
					
					L.control.layers({
						'osm':osm.addTo(map),
						"google": L.tileLayer('https://www.google.cn/maps/vt?lyrs=s@189&gl=cn&x={x}&y={y}&z={z}', {
							attribution: 'google'
						})
					}, {'Route':drawnItems}, { position: 'topleft', collapsed: false }).addTo(map);
					
					map.addControl(new L.Control.Draw({
						edit: {
							featureGroup: drawnItems,
							poly : {
								allowIntersection : false
							}
						},
						
						draw: {
							polygon: false,
							circle: false, 
							rectangle: false,
							polyline: false
						}
					}));
					
					map.on('draw:created', function(event) {
						if (document.getElementById('coordx').value != ''){
							toastr.error('Delete the point first !!',{timeOut:15000})
							return;
						}
						
						var layer = event.layer;
						drawnItems.addLayer(layer);
						
						var data = drawnItems.toGeoJSON();
						var coordy = JSON.stringify(data.features[0].geometry.coordinates[0]);
						var coordx = JSON.stringify(data.features[0].geometry.coordinates[1]);
						
						document.getElementById('coordx').value = coordx;
						document.getElementById('coordy').value = coordy;
					});
					
					map.on('draw:edited', function (e) {
						var layers = e.layers;
						var data = drawnItems.toGeoJSON();
						
						var coordy = JSON.stringify(data.features[0].geometry.coordinates[0]);
						var coordx = JSON.stringify(data.features[0].geometry.coordinates[1]);
						
						document.getElementById('coordx').value = coordx;
						document.getElementById('coordy').value = coordy;
					});

					map.on('draw:deleted', function (e) {
						var layers = e.layers;
						
						document.getElementById('coordx').value = '';
						document.getElementById('coordy').value = '';
					});
					
					leselect = xhr.responseText;
				}    
			};
			
			xhr.open("GET",resurl,true);
			xhr.send(null); 
			
		} else
		if (action == 'modif') {
			document.getElementById('myModalLabel').innerHTML =  '<i class="fa fa-pencil-square-o" style="color:#1ab394"></i>&nbsp;&nbsp;Modify Sequence Form'; 
			
			var resurl='listeslies_stories.php?elemid=fenetre&param='+param+'&action='+action+'&id='+id;
			var xhr = getXhr();
			
			xhr.onreadystatechange = function(){   
				if(xhr.readyState == 4 ){
					leselect = xhr.responseText;
					var val=leselect; 
					var val1=val.split('##');	
					
					document.getElementById('contenu_modal').innerHTML = val1[0];
					
					var osmUrl = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
					osmAttrib = '&copy; <a href="https://openstreetmap.org/copyright">OpenStreetMap</a> contributors',
					osm = L.tileLayer(osmUrl, {maxZoom: 18, attribution: osmAttrib});
					
					map = new L.Map('storymap', {center: new L.LatLng(0, 0), zoom: 2}),
					drawnItems = L.featureGroup().addTo(map);
					
					L.control.layers({
						'osm':osm.addTo(map),
						"google": L.tileLayer('https://www.google.cn/maps/vt?lyrs=s@189&gl=cn&x={x}&y={y}&z={z}', {
							attribution: 'google'
						})
					}, {'Route':drawnItems}, { position: 'topleft', collapsed: false }).addTo(map);
					
					map.addControl(new L.Control.Draw({
						edit: {
							featureGroup: drawnItems,
							poly : {
								allowIntersection : false
							}
						},
						
						draw: {
							polygon: false,
							circle: false, 
							rectangle: false,
							polyline: false
						}
					}));

					map.on('draw:created', function(event) {

						if (document.getElementById('coordx').value != ''){
							toastr.error('Delete the point first !!',{timeOut:15000})
							return;
						}

						var layer = event.layer;

						drawnItems.addLayer(layer);

						var data = drawnItems.toGeoJSON();
						var coordy = JSON.stringify(data.features[0].geometry.coordinates[0]);
						var coordx = JSON.stringify(data.features[0].geometry.coordinates[1]);

						document.getElementById('coordx').value = coordx;
						document.getElementById('coordy').value = coordy;
					
					});

					map.on('draw:edited', function (e) {
						var layers = e.layers;
						var data = drawnItems.toGeoJSON();
						var coordy = JSON.stringify(data.features[0].geometry.coordinates[0]);
						var coordx = JSON.stringify(data.features[0].geometry.coordinates[1]);
						document.getElementById('coordx').value = coordx;
						document.getElementById('coordy').value = coordy;
					});

					map.on('draw:deleted', function (e) {
						var layers = e.layers;
						document.getElementById('coordx').value = '';
						document.getElementById('coordy').value = '';
					});

					var marker = L.marker([val1[1], val1[2]]).addTo(drawnItems);
					
					var btn = document.getElementById('uploadBtn'),
					progressBar = document.getElementById('progressBar'),
					progressOuter = document.getElementById('progressOuter'),
					msgBox = document.getElementById('msgBox');

					var uploader = new ss.SimpleUpload({
						button: btn,
						url: 'file_upload.php',
						name: 'uploadfile',
						hoverClass: 'hover',
						focusClass: 'focus',
						allowedExtensions: ['jpg','jpeg','png'],
						responseType: 'json',
						startXHR: function() {
							progressOuter.style.display = 'block'; 
							this.setProgressBar( progressBar );
						},
					
						onSubmit: function() {
							msgBox.innerHTML = ''; // empty the message box
							btn.innerHTML = 'Chargement...'; // change button text to "Uploading..."
						},
						
						onComplete: function( filename, response ) {
							btn.innerHTML = 'Choisir un fichier';
							progressOuter.style.display = 'none'; // hide progress bar when upload is completed

							if ( !response ) {
								msgBox.innerHTML = 'Unable to upload file';
								return;
							}

							if ( response.success === true ) {
								msgBox.innerHTML = 'Image loaded successfull.';
								document.getElementById('upload-file-info').innerHTML = escapeTags(filename);
								document.getElementById('delete_justif').style.display="inline";
								change_lien2 = 1;
							} else {
								if ( response.msg )  {
									msgBox.innerHTML = escapeTags( response.msg );
								} else {
									msgBox.innerHTML = 'An error occurred and the upload failed.';
								}
							}
						},
						
						onError: function() {
							progressOuter.style.display = 'none';
							msgBox.innerHTML = 'Unable to upload file';
						}
					});

					leselect = xhr.responseText;
				}    
			};

			xhr.open("GET",resurl,true);
			xhr.send(null);  
		}

	} else
	if (param == 'route') {
		if (action == 'add') {
			document.getElementById('myModalLabel').innerHTML =  '<i class="fa fa-map-marker" style="color:#1ab394"></i>&nbsp;&nbsp;Add Route Map'; 
			var route_country = document.getElementById('route_country').value; 
			var route_story = document.getElementById('route_story').value;  

			if (route_story == ''){
				document.getElementById("update-alert2").style.display = "none";
				document.getElementById("update-alert").style.display = "inline";
				toastr.error('You didn\'t select the story !',{timeOut:15000})	
				document.getElementById('update-alert').innerHTML = 'You didn\'t select the story !';	  
				return;
			} 

			var resurl='listeslies_stories.php?elemid=fenetre&param='+param+'&action='+action+'&id='+id+'&route_country='+route_country+'&route_story='+route_story; //alert(resurl);
			var xhr = getXhr();
			
			xhr.onreadystatechange = function(){     
				if(xhr.readyState == 4 ){
					leselect = xhr.responseText;    //alert(leselect);
					var val=leselect;  
					var val1=val.split('##');	
					if (val1[0] == 1) {
						document.getElementById('contenu_modal').innerHTML = val1[1];
						var osmUrl = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
						osmAttrib = '&copy; <a href="https://openstreetmap.org/copyright">OpenStreetMap</a> contributors',
						osm = L.tileLayer(osmUrl, {maxZoom: 18, attribution: osmAttrib});
						map = new L.Map('storymap', {center: new L.LatLng(0, 0), zoom: 2}),
						drawnItems = L.featureGroup().addTo(map);

						L.control.layers({
							'osm':osm.addTo(map),
							"google": L.tileLayer('https://www.google.cn/maps/vt?lyrs=s@189&gl=cn&x={x}&y={y}&z={z}', {
								attribution: 'google'
							})
						}, {'Route':drawnItems}, { position: 'topleft', collapsed: false }).addTo(map);
						
						map.addControl(new L.Control.Draw({
							edit: {
								featureGroup: drawnItems,
								poly : {
									allowIntersection : false
								}
							},
							draw: {
								polygon: false,
								circle: false, 
								rectangle: false,
								marker: false
							}
						}));

						map.on('draw:created', function(event) {
							if (document.getElementById('coordx').value != ''){
								toastr.error('Delete the point first !!',{timeOut:15000})
								return;
							}

							var layer = event.layer;
							drawnItems.addLayer(layer);
							var data = drawnItems.toGeoJSON();
							var coordy = JSON.stringify(data.features[0].geometry.coordinates[0]);
							var coordx = JSON.stringify(data.features[0].geometry.coordinates[1]);

							document.getElementById('coordx').value = coordx;
							document.getElementById('coordy').value = coordy;
						});

						map.on('draw:edited', function (e) {
							var layers = e.layers;
							var data = drawnItems.toGeoJSON();
							var coordy = JSON.stringify(data.features[0].geometry.coordinates[0]);
							var coordx = JSON.stringify(data.features[0].geometry.coordinates[1]);
							document.getElementById('coordx').value = coordx;
							document.getElementById('coordy').value = coordy;
						});

						map.on('draw:deleted', function (e) {
							var layers = e.layers;
							document.getElementById('coordx').value = '';
							document.getElementById('coordy').value = '';
						});
						
					} else {
						document.getElementById("update-alert2").style.display = "none";
						document.getElementById("update-alert").style.display = "inline";
						toastr.error(val1[1],{timeOut:15000})	
						document.getElementById('update-alert').innerHTML = val1[1];
					}

					leselect = xhr.responseText;
				}    
			};
			
			xhr.open("GET",resurl,true);
			xhr.send(null); 
			
		} else
		if (action == 'modif') {
			document.getElementById('myModalLabel').innerHTML =  '<i class="fa fa-pencil-square-o" style="color:#1ab394"></i>&nbsp;&nbsp;Modify Sequence Form'; 
			var resurl='listeslies_stories.php?elemid=fenetre&param='+param+'&action='+action+'&id='+id; //alert(resurl);
			var xhr = getXhr();
			xhr.onreadystatechange = function(){     
				if(xhr.readyState == 4 ){
					leselect = xhr.responseText;    //alert(leselect);
					var val=leselect;  
					var val1=val.split('##');	
					
					document.getElementById('contenu_modal').innerHTML = val1[0];
					var osmUrl = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
					osmAttrib = '&copy; <a href="https://openstreetmap.org/copyright">OpenStreetMap</a> contributors',
					osm = L.tileLayer(osmUrl, {maxZoom: 18, attribution: osmAttrib});
					map = new L.Map('storymap', {center: new L.LatLng(0, 0), zoom: 2}),
					drawnItems = L.featureGroup().addTo(map);
					
					L.control.layers({
						'osm':osm.addTo(map),
						"google": L.tileLayer('https://www.google.cn/maps/vt?lyrs=s@189&gl=cn&x={x}&y={y}&z={z}', {
							attribution: 'google'
						})
					}, {'Route':drawnItems}, { position: 'topleft', collapsed: false }).addTo(map);
					
					map.addControl(new L.Control.Draw({
						edit: {
							atureGroup: drawnItems,
							poly : {
								allowIntersection : false
							}
						},
						
						draw: {
							polygon: false,
							circle: false, 
							rectangle: false,
							polyline: false
						}
					}));
					
					map.on('draw:created', function(event) {
						if (document.getElementById('coordx').value != ''){
							toastr.error('Delete the point first !!',{timeOut:15000})
							return;
						}

						var layer = event.layer;
						drawnItems.addLayer(layer);
						var data = drawnItems.toGeoJSON();
						var coordy = JSON.stringify(data.features[0].geometry.coordinates[0]);
						var coordx = JSON.stringify(data.features[0].geometry.coordinates[1]);
						document.getElementById('coordx').value = coordx;
						document.getElementById('coordy').value = coordy;
					});
					
					map.on('draw:edited', function (e) {
						var layers = e.layers;
						var data = drawnItems.toGeoJSON();
						var coordy = JSON.stringify(data.features[0].geometry.coordinates[0]);
						var coordx = JSON.stringify(data.features[0].geometry.coordinates[1]);
						document.getElementById('coordx').value = coordx;
						document.getElementById('coordy').value = coordy;
					});
					
					map.on('draw:deleted', function (e) {
						var layers = e.layers;
						document.getElementById('coordx').value = '';
						document.getElementById('coordy').value = '';
					});
					
					var marker = L.marker([val1[1], val1[2]]).addTo(drawnItems);
					
					var btn = document.getElementById('uploadBtn'),
					progressBar = document.getElementById('progressBar'),
					progressOuter = document.getElementById('progressOuter'),
					msgBox = document.getElementById('msgBox');
					
					var uploader = new ss.SimpleUpload({
						button: btn,
						url: 'file_upload.php',
						name: 'uploadfile',
						hoverClass: 'hover',
						focusClass: 'focus',
						allowedExtensions: ['jpg','jpeg','png'],
						responseType: 'json',
						startXHR: function() {
							progressOuter.style.display = 'block'; // make progress bar visible
							this.setProgressBar( progressBar );
						},

						onSubmit: function() {
							msgBox.innerHTML = ''; // empty the message box
							btn.innerHTML = 'Chargement...'; // change button text to "Uploading..."
						},

						onComplete: function( filename, response ) {
							btn.innerHTML = 'Choisir un fichier';
							progressOuter.style.display = 'none'; // hide progress bar when upload is completed

							if ( !response ) {
								msgBox.innerHTML = 'Unable to upload file';
								return;
							}

							if ( response.success === true ) {
								msgBox.innerHTML = 'Image loaded successfull.';
								document.getElementById('upload-file-info').innerHTML = escapeTags(filename);
								document.getElementById('delete_justif').style.display="inline";
								change_lien2 = 1;
								
							} else {
								if ( response.msg )  {
									msgBox.innerHTML = escapeTags( response.msg );
								} else {
									msgBox.innerHTML = 'An error occurred and the upload failed.';
								}
							}
						},

						onError: function() {
							progressOuter.style.display = 'none';
							msgBox.innerHTML = 'Unable to upload file';
						}
					});

					leselect = xhr.responseText;
				}    
			};
			xhr.open("GET",resurl,true);
			xhr.send(null);	  
		}
	}

	document.getElementById('update-footer').innerHTML = "<button type=\"button\" class=\"btn btn-info\" onclick=\"enregistrer('"+param+"','"+action+"',"+id+")\"><i class=\"fa fa-plus-square\"></i>&nbsp;&nbsp;<span class=\"bold\">Add</span></button>"+blanc
	+"<button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Fermer</button>"; 
}


$(document).ready(function () {
	$('body').scrollspy({
	 target: '.navbar-fixed-top',
		offset: 80
	});

	var options1 = {
		valueNames: [ 'title','country']
	};

	var userList1 = new List('users1', options1);
	var options2 = {
		valueNames: [ 'story_id','content']
	};

	var userList2 = new List('users2', options2);
	$('a.page-scroll').bind('click', function(event) {
		var link = $(this);
		$('html, body').stop().animate({
			scrollTop: $(link.attr('href')).offset().top - 50
		}, 500);
		
		event.preventDefault();
		$("#navbar").collapse('hide');
	});
});

var cbpAnimatedHeader = (function() {
	var docElem = document.documentElement,
	header = document.querySelector( '.navbar-default' ),
	didScroll = false,
	changeHeaderOn = 200;

	function init() {
		window.addEventListener( 'scroll', function( event ) {
			if( !didScroll ) {
				didScroll = true;
				setTimeout( scrollPage, 250 );
			}
		}, false );
	}

	function scrollPage() {
		var sy = scrollY();
		if ( sy >= changeHeaderOn ) {
			$(header).addClass('navbar-scroll')
		} else {
			$(header).removeClass('navbar-scroll')
		}
		didScroll = false;
	}

	function scrollY() {
		return window.pageYOffset || docElem.scrollTop;
	}

	init();
})();

