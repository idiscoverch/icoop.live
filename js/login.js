
/* Login form action */

function longin_connexion(){
	var username = document.getElementById('username').value;
	var password = document.getElementById('password').value;

	if (username == '' || password == ''){
		document.getElementById("login-alert12").style.display = "none";
	    document.getElementById("login-alert11").style.display = "block";
	    document.getElementById('login-alert11').innerHTML = '<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>Vous n\'avez pas entré votre mail ou votre mot de passe!';

	} else {
		var resurl='listeslies.php?elemid=connexion&username='+username+'&password='+password;     
        var xhr = getXhr();
        xhr.onreadystatechange = function(){
            if(xhr.readyState == 4 ){
                leselect = xhr.responseText;     console.log(leselect);
                var val=leselect;
                var val1=val.split('##');

                if (val1[0] == 1) {
					window.open("index.php",'_self');
				} else {
					document.getElementById("login-alert12").style.display = "none";
				    document.getElementById("login-alert11").style.display = "block";
	                document.getElementById('login-alert11').innerHTML = '<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'+val1[1];
				}

				leselect = xhr.responseText;
            }
        };

        xhr.open("GET",resurl,true);
        xhr.send(null);
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


var check_session;
function CheckForSession() {
    var str="chksession=true";
    jQuery.ajax({
        type: "POST",
        url: "chk_session.php",
        data: str,
        cache: false,
        success: function(res){
			if(res == "1") {
				window.open("logout.php",'_self');
			}
        }
    });
} 
