<?php

function connect()
{       
	$conn = pg_connect("host=185.142.213.227 port=5433 dbname=icollect_dev user=icollect password=icollect");

	return $conn;	
} 

function authentification(){
    $login = $_GET['username'];
    $_SESSION['pseudo'] = $_GET['username'];
    $motdepass= $_GET['password'];
	
    if(empty($login) or empty($motdepass)){
		return FALSE;
     
    }else{
        if(verification($login, $motdepass)){        
			$conn=connect();
            $dc=gmdate ("Y-m-d H:i:s",time());            
            $role = $_SESSION['role'] ;
            if($role==''){$role='';}
            $sql="INSERT INTO connections_user(pseudo,date_debut_connect,id_roles_user) VALUES ('$login', '$dc', $role) RETURNING id_connection";
            $res = pg_query($conn,$sql);
            $row= pg_fetch_assoc($res);
            $id_connection=$row["id_connection"];
            $_SESSION['id_connection'] = $id_connection;
       
            pg_Close ($conn);
            return TRUE;                    
           
        } else{         
            pg_Close ($conn);
            return FALSE;
            
        }        
    }
}


function verifUsrename($username,$conn){
    $sql="Select * from v_security_new where username='$username' ";
	$result = pg_query ($conn, $sql);
    
	if ($result && pg_num_rows($result) > 0) {
        return TRUE;
    }else{
		return FALSE;		
	}
}


function verifExporter($username){
	$conn=connect();
    $sql="SELECT * FROM v_security_new WHERE username='$username'";
	$result = pg_query ($conn, $sql);
	$row = pg_fetch_assoc($result);
	
	if ($row['id_exporter'] != '') {
		return TRUE;
    } else {
		return FALSE;		
	}
}


function getIP() {
	foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
		if (array_key_exists($key, $_SERVER) === true) {
			foreach (explode(',', $_SERVER[$key]) as $ip) {
				if (filter_var($ip, FILTER_VALIDATE_IP) !== false) {
					return $ip;
				}
			}
		}
	}
}
	
	
function getRegvalues($id_regvalue, $lang) {
	$conn=connect();
	
	if($lang=='en') { $field = "cvalue"; } else { $field = "cvalue".$lang; }

	$sql = "SELECT cvalue, $field As lvalue FROM v_regvalues WHERE id_regvalue=$id_regvalue";
	$rs = pg_query($conn, $sql);
	$row = pg_fetch_assoc($rs);
	
	if($lang=='en') { $value = $row['cvalue']; } else { $value = $row['lvalue']; }
	
	return $value;
}

	
function verification($login, $pass){ 

	$conn=connect();
		
	if (!$conn) {
		error_log ("Impossible d'acceder à la base de donn&eacute;es");
		error_log ("PG Connection error: " . pg_last_error($connection));
		exit();
	
	} else {
	
		$login_ok = false;
		
		$sql = "SELECT * FROM v_security_new WHERE username = '$login' " ;
		$resultat = pg_query($conn,$sql);
		$nbresultats=pg_num_rows($resultat);
		
		if ($nbresultats >0){ 
			$row= pg_fetch_assoc($resultat);
		
			if($row["pwd_reset"]==1){
				$check_password = hash('sha256', $pass . $row['salt']);
				for($round = 0; $round < 65536; $round++) { 
					$check_password = hash('sha256', $check_password . $row['salt']); 
				} 
			
				if($check_password === $row['password']) { $login_ok = true; } 
			
			} else {
				if($pass === $row['password']) { $login_ok = true; } 
			}
		}	

		// $monfichier = fopen('test.txt', 'r+');
		// ftruncate($monfichier,0);
		// fputs($monfichier,$sql);

		if($login_ok) {		
			$id_user=$row["id_user"];
			$downline=$row["downline"];
			$name=$row["name"];
			$id_buyer=$row["id_buyer"];
			$idview=$row["idview"];
			$id_company=$row["id_company"];
			$company_name=$row["company_name"];
			$id_supchain_type=$row["id_supchain_type"];
			$id_user_supchain_type=$row["id_user_supchain_type"];
			$id_primary_company=$row["id_primary_company"];
			$id_management_company=$row["id_management_company"];
			$username=$row["username"];
			$id_cooperative=$row["id_cooperative"];
			$date=gmdate("Y-m-d H:i:s");
		
			$id_exporter=$row["id_exporter"];
			$code_country=$row["code"];
			$id_culture=$row["id_culture"];
			$id_contact=$row["id_contact"];
			$pwd_reset=$row["pwd_reset"];
			$p_email3=$row["p_email3"];
			$p_email=$row["p_email"];
			$p_email3_pwd=$row["p_email3_pwd"];
			$p_phone=$row["p_phone"];
			$id_country=$row["id_country"];
		
			$_SESSION['id_contact'] = $id_contact;
			$_SESSION['id_buyer'] = $id_buyer;
			$_SESSION['idview'] = $idview;
			$_SESSION['id_user'] = $id_user;
			$_SESSION['downline'] = $downline;
			$_SESSION['name'] = $name;
			$_SESSION['id_company'] = $id_company;
			$_SESSION['company_name'] = $company_name;
			$_SESSION['id_supchain_type'] = $id_supchain_type;
			$_SESSION['id_user_supchain_type'] = $id_user_supchain_type;
			$_SESSION['id_primary_company'] = $id_primary_company;
			$_SESSION['id_management_company'] = $id_management_company;
			$_SESSION['id_exporter'] = $id_exporter;	
			$_SESSION['username'] = $login;
			$_SESSION['code_country'] = $code_country;
			$_SESSION['id_culture'] = $id_culture;
			$_SESSION['pwd_reset'] = $pwd_reset;
			$_SESSION['p_email3'] = $p_email3;
			$_SESSION['p_email'] = $p_email;
			$_SESSION['p_email3_pwd'] = $p_email3_pwd;
			$_SESSION['p_phone'] = $p_phone;
			$_SESSION['id_cooperative'] = $id_cooperative;
			$_SESSION['id_country'] = $id_country;


			// $json = file_get_contents('http://getcitydetails.geobytes.com/GetCityDetails?fqcn='. getIP()); 
			// $data = json_decode($json, true);
		
			// $session_ip = $data['geobytesipaddress'];
			// $session_country = str_replace("'", " ", $data['geobytesfqcn']);
			
			
			$sql="INSERT INTO sessions(id_user, name, created, interface) VALUES ('$id_user', '$username', '$date', 'iCoop')";
			$res = pg_query($conn,$sql);
			
			
			// $monfichier = fopen('test.txt', 'r+');
			// ftruncate($monfichier,0);
			// fputs($monfichier,$sql);

			return TRUE;				
		} else {
			return FALSE;
		}
	}
}



?>
