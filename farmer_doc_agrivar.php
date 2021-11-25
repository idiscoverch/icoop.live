<?php
	include('fcts.php');
	
	$id_plantation = $_GET['id'];
	
	$conn=connect();
	
	// Plantation
	$sqlP="SELECT id_farmer, code_farmer, code_parcelle, name_town, coordx, coordy, year_creation, surface_ha, geom_json FROM v_plantation WHERE gid_plantation = $id_plantation";
	$rstP = pg_query($conn, $sqlP);
	$arrP = pg_fetch_assoc($rstP);
	
	$id_contact = $arrP['id_farmer'];
	
	if(!empty($arrP['coordx']) AND !empty($arrP['coordy'])) {
		$lonlat = $arrP['coordx'].','.$arrP['coordy'];
	} else {
		$lonlat = '';
	}
	
	// Contact
	$sqlC="SELECT lastname, firstname, name_town, name_cooperative FROM v_icw_contacts WHERE id_contact = $id_contact";
	$rstC = pg_query($conn, $sqlC);
	$arrC = pg_fetch_assoc($rstC);
	
	// Avatar
	$sqlA="SELECT doc_link FROM contact_docs WHERE contact_id = $id_contact AND doc_type = 154";
	$rstA = pg_query($conn, $sqlA);
	$arrA = pg_fetch_assoc($rstA);
	
	if($arrA['doc_link']) {
		$pieces = explode("upload", $arrA['doc_link']);
		$avatar_link = $pieces[0].'upload/w_500,h_500,c_crop,g_auto/'.$pieces[1];
	} else {
		$avatar_link = 'img/user.jpg';
	}
?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>iCoop - Farmer document</title>

	<link href="favicon.ico" rel="shortcut icon">
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="js/plugins/leaflet/leaflet.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
	<link href='https://fonts.googleapis.com/css?family=Lato:900,300' rel='stylesheet' type='text/css'>
	
	<style>
		.bordure {
			border: 1px solid #999;
		}
		
		.bordure_dwn {
			border-bottom: 1px solid #999;
		}
		
		.map_canvas {
			width: 100%;
			height: 310px;
			border: 1px solid #ccc;
		}
		
		.col-print-1 {width:8%;  float:left;}
		.col-print-2 {width:16%; float:left;}
		.col-print-3 {width:25%; float:left;}
		.col-print-4 {width:33%; float:left;}
		.col-print-5 {width:42%; float:left;}
		.col-print-6 {width:50%; float:left;}
		.col-print-7 {width:58%; float:left;}
		.col-print-8 {width:66%; float:left;}
		.col-print-9 {width:75%; float:left;}
		.col-print-10{width:83%; float:left;}
		.col-print-11{width:92%; float:left;}
		.col-print-12{width:100%; float:left;}
	</style>
</head>
<body>
	<div style="width:80%; margin:45px auto;" id="capture">
		<div class="row">
			<div class="col-print-2">
				<img src="img/Agrivar-Logo.jpg" class="img-responsive" style="height:70px;" />
			</div>
			<div class="col-print-10">
				<h3 class="bordure_dwn" style="padding-top:14px;">AGRIVAR (Agro Industrie Variée) SA</h3>
			</div>
		</div>
		
		<div class="row" style="margin-top:25px;">
			<div class="bordure col-print-12">
				<div class="col-print-2 text-center" style="padding: 15px;">
					<img src="<?php echo $avatar_link; ?>" height="128" />
				</div>
				<div class="col-print-10" style="float:right;">
					<table style="margin-top:15px;">
						<tr>
							<td style="width:190px;"><label>Code Planteur</label></td>
							<td><?php echo $arrP['code_farmer']; ?></td>
						</tr>
						<tr>
							<td style="width:190px;"><label>Nom de famille</label></td>
							<td><?php echo $arrC['lastname']; ?></td>
						</tr>
						<tr>
							<td style="width:190px;"><label>Prénom</label></td>
							<td><?php echo $arrC['firstname']; ?></td>
						</tr>
						<tr>
							<td style="width:190px;"><label>Résidence</label></td>
							<td><?php echo $arrC['name_town']; ?></td>
						</tr>
						<tr>
							<td style="width:190px;"><label>Cooperative</label></td>
							<td><?php echo $arrC['name_cooperative']; ?></td>
						</tr>
					</table>
				</div>
			</div>
		</div>
		
		<div class="row" style="margin-top:25px;">
			<div class="bordure col-print-12">
				<div class="row">
					<div class="col-print-12">
						<table style="margin-top:15px; width:100%;" class="text-center">
							<tr>
								<td style="width:20%"><label>Code Plantation</label></td>
								<td style="width:20%"><label>Localité</label></td>
								<td style="width:20%"><label>Longitude/Latitude</label></td>
								<td style="width:20%"><label>Année de creation</label></td>
								<td style="width:20%"><label>Superficie (ha)</label></td>
							</tr>
							<tr>
								<td><?php echo $arrP['code_parcelle']; ?></td>
								<td><?php echo $arrP['name_town']; ?></td>
								<td><?php echo $lonlat; ?></td>
								<td><?php echo $arrP['year_creation']; ?></td>
								<td><?php echo number_format($arrP['surface_ha'],4); ?></td>
							</tr>
						</table>
					</div>
				</div>
				<div class="row" style="margin:0;">
					<div class="col-print-6" style="padding:15px;">
						<div id="map1" class="map_canvas"></div>
					</div>
					<div class="col-print-6" style="padding:15px;">
						<div id="map2" class="map_canvas"></div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="row" style="margin-top:25px;">
			Edité le (<?php echo gmdate('d-m-Y'); ?>)
			<strong class="pull-right">Cette carte n’est pas un titre foncier</strong>
		</div>
	</div>

	<script src="js/jquery-2.1.1.js"></script>
    <script src="js/bootstrap.min.js"></script>
	<script src="js/plugins/leaflet/leaflet.js"></script>
	<script src="https://maps.google.com/maps/api/js?libraries=geometry,places&key=AIzaSyBcOXamzcMVv4w0sCQBnXFaFjVwrL4k73E"></script>
	<script src="js/plugins/leaflet-plugins-master/layer/tile/Google1.js"></script>
	<script src="js/html2canvas.js"></script>

	<script>
		// Map 1
		var ggl = new L.Google('HYBRID');
		var map = new L.Map('map1', {layers: [ggl], zoomControl: false});

		// Map 2
		var ggl2 = new L.Google('HYBRID');
		var map2 = new L.Map('map2', {layers: [ggl2], zoomControl: false});
		
		var plantation_point = L.geoJson('', {});
		var plantation_point2 = L.geoJson('', {});
		
		var plantation_couche = L.geoJson(null, {
			style: function (feature) {

				return {
					color: 'red',
					weight: 1,
					fill: true,
					fillOpacity: 0.3
				};
			}
		});
		
		var plantation_couche2 = L.geoJson(null, {
			style: function (feature) {

				return {
					color: 'red',
					weight: 1,
					fill: true,
					fillOpacity: 0.3
				};
			}
		});
		
		var LeafIcon2 = L.Icon.extend({
			options: {
				iconSize:     [25, 30],
				iconAnchor:   [16, 32],
				popupAnchor:  [0, -33]
			}
		});

		var pointIcon = new LeafIcon2({iconUrl: 'img/icon_point.png'});
		
		<?php if(!empty($arrP['coordx']) AND !empty($arrP['coordy'])){ ?>
			var coordx = <?php echo $arrP['coordx']; ?>; 
			var coordy = <?php echo $arrP['coordy']; ?>; 
			
			if((coordx!="")&&(coordy!="")) {
				L.marker([coordx, coordy], {icon: pointIcon, riseOnHover:true}).addTo(plantation_point);
				map.addLayer(plantation_point);
				
				L.marker([coordx, coordy], {icon: pointIcon, riseOnHover:true}).addTo(plantation_point2);
				map2.addLayer(plantation_point2);
			}
		<?php } ?>
		
		var plantation_data = <?php echo $arrP['geom_json']; ?>; 
		
		plantation_couche.addData(plantation_data);	 
		map.addLayer(plantation_couche);
		map.fitBounds(plantation_couche.getBounds());
		

		var plantation_data2 = <?php echo $arrP['geom_json']; ?>; 
		
		plantation_couche2.addData(plantation_data2);	 
		map2.addLayer(plantation_couche2);
		map2.fitBounds(plantation_couche2.getBounds());
		map2.setZoom(14);
		
		function myFunction() {
			html2canvas(document.querySelector("#capture")).then(canvas => {
				document.body.appendChild(canvas)
			});
		}
	</script>
</body>

</html>