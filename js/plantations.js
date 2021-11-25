/*!
 
=========================================================
* iCoop Dashboard - v1.2.2
=========================================================

* Product Page: https://www.icoop.com/ic/
* Copyright 2020 dev4impact (https://www.dev4impact.com)
* Licensed under MIT (https://icoop.live/ic/LICENSE.md)

* Coded by www.dev4impact.com

=========================================================

*/


var blanc = "";
var tt_weight = 0;
var edit_schedule=0;
var current_plantation_id;
var quill;

var saveBtn, cancelBtn, expdBtn;

/*
* 
* Users 
* Role, Acces and permissions
* 
*/

// Map edit System Admin
var mapEdit_read = 0; 
var mapEdit_update = 0; 
var mapEdit_create = 0; 
var mapEdit_delete = 0; 

if(user_roles.length != 0){
	var role_permissions = JSON.stringify(user_roles);   
	$.each(JSON.parse(role_permissions), function(idx, obj) {

		// Edit Map
		if(obj.id_object == 230){ 
			if(obj._read == 1){ mapEdit_read=1; } 
			if(obj._create == 1){ mapEdit_create = 1; }
			if(obj._update == 1){ mapEdit_update = 1; }
			if(obj._delete == 1){ mapEdit_delete = 1; }
		}

	});
}

var poly="", t_line="", fm_coordx="", fm_coordy="", seeArea;
var polygon, col_point, polyline;
var saveTraceBtn, cancelTraceBtn;

/*
* 
* Maps loader
* Google road map and sattelite
* 
*/

var ggl = new L.Google('HYBRID');
var googlemap = new L.Google('ROADMAP');
var map = new L.Map('farmers_map', {layers: [googlemap], maxZoom: 22, minZoom: 1});

var baseMaps = {
	"Google Map": googlemap,
	"Google Satellite": ggl
};

var polylineMeasure = L.control.polylineMeasure ({
	position:'topleft', 
	unit:'metres', 
	showBearings:true, 
	clearMeasurementsOnStop: false, 
	showClearControl: true, 
	showUnitControl: true
});

var regions = L.geoJson(null, { onEachFeature : onEachFeature_region });
var label_towns = L.geoJson('', {});
var towns_mrker_couche = L.geoJson('', {});
var infrastructure_couche = L.geoJson('', {});
var plantation_points = L.geoJson('', {});
var circle_layer = L.geoJson(null, { });

var plantation_couche = L.geoJson(null, {
	style: function (feature) {

		return {
			color: 'red',
			weight: 1,
			fill: true,
			fillOpacity: 0.2
		};
	}, onEachFeature : onEachFeature_plantation
});


var LeafIcon2 = L.Icon.extend({
    options: {
        iconSize:     [25, 30],
		iconAnchor:   [16, 32],
        popupAnchor:  [0, -33]
    }
});

var pointIcon = new LeafIcon2({iconUrl: 'img/icon_point.png'});

var LeafIcon3 = L.Icon.extend({
    options: {
        iconSize:     [30, 30],
		iconAnchor:   [16, 30],
        popupAnchor:  [0, -30]
    }
});

var townIcon = new LeafIcon3({iconUrl: 'img/icon_town.png'}) ;
var infrastructureIcon = new LeafIcon3({iconUrl: 'img/icon_infrastructure.png'}) ;
var drawnPolygone = L.featureGroup();

var polygon_drawControl = new L.Control.Draw({
	position: 'topright',
    edit: {
        featureGroup: drawnPolygone,
		remove: false,
        poly: {
            allowIntersection: false
        }
    },
    draw: {
        polygon: true,
		polyline: false,
        circle: false,
		rectangle: false,
		circlemarker: false,
        marker: false
    }
});


function onEachFeature_plantation(feature, layer) {
	
	if(mapEdit_update == 1){  
		var editBtn = '<i class="fa fa-pen-square fa-fw pull-right" onclick="editFarmerPlant(\''+layer.feature.properties.gid_plantation+'\',\''+layer.feature.properties.id_contact+'\');" style="cursor:pointer; color:green;"></i>';
	} else { var editBtn = ""; }
	
	if(layer.feature.properties.name_farmer === null){ var name_farmer=""; } else { var name_farmer=layer.feature.properties.name_farmer; }
	if(layer.feature.properties.name_farmergroup === null){ var name_farmergroup=""; } else { var name_farmergroup=layer.feature.properties.name_farmergroup; }
	if(layer.feature.properties.name_town === null){ var name_town=""; } else { var name_town=layer.feature.properties.name_town; }
	if(layer.feature.properties.code_farmer === null){ var code_farmer=""; } else { var code_farmer=layer.feature.properties.code_farmer; }
	if(layer.feature.properties.culture === null){ var culture="---"; } else { var culture=layer.feature.properties.culture; }
	if(layer.feature.properties.area === null){ var area=""; } else { var area=layer.feature.properties.area; }
	if(layer.feature.properties.name_buyer === null){ var name_buyer=""; } else { var name_buyer=layer.feature.properties.name_buyer; }
	if(layer.feature.properties.gid_plantation === null){ var gid_plantation=""; } else { var gid_plantation=layer.feature.properties.gid_plantation; }
	if(layer.feature.properties.gid_town === null){ var gid_town="-"; } else { var gid_town=layer.feature.properties.gid_town; }
	if(layer.feature.properties.id_contact === null){ var id_contact="-"; } else { var id_contact=layer.feature.properties.id_contact; }
	if(layer.feature.properties.plantation_town === null){ var plantation_town=""; } else { var plantation_town=layer.feature.properties.plantation_town; }
	if(layer.feature.properties.code_parcelle === null){ var code_plantation=""; } else { var code_plantation=layer.feature.properties.code_parcelle; }
	if(layer.feature.properties.surface_ha === null){ var surface_ha=""; } else { var surface_ha=layer.feature.properties.surface_ha; }
	if(layer.feature.properties.area_acres === null){ var area_acres=""; } else { var area_acres=layer.feature.properties.area_acres; }

	var popupContent = "<div style=\"max-width:400px;\"><h5 style=\"border-bottom: 1px solid #eee;\">"+blanc
	+"<i class=\"fa fa-check-square fa-fw\" style=\"color:#ed1b2c\"></i><strong style=\"color:#ed1b2c\">&nbsp;&nbsp;Plantation</strong>"+editBtn+"</h5>"+blanc
		+"<div class=\"icon_desc\" style=\"margin-left:0px;display:block\"><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Farmer name : </strong>"+name_farmer
		+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Farmer groups : </strong>"+name_farmergroup
		+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Farmer residence : </strong>"+name_town
		+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> ID town : </strong>"+gid_town
		+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Code Farmer : </strong>"+code_farmer
		+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Code Plantation : </strong>"+code_plantation
		+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Culture : </strong>"+culture
		+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Area (m2) : </strong>"+area
		+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Surface Ha: </strong>"+surface_ha
		+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Acres: </strong>"+area_acres
		+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Buyer : </strong>"+name_buyer
		+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> ID plantation : </strong>"+gid_plantation
		+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Plantation town : </strong>"+plantation_town
		+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> ID Town Plantation : </strong>"+gid_town
		+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> ID Contact: </strong>"+id_contact
	+" </span></div></div>";


	if (feature.properties) {
		layer.bindPopup(popupContent);
		layer.on('click', function(e) { 
			$("#rangeSliderBox").addClass("hide"); 
		});
	}
}

var project_zone = L.geoJson(null, { onEachFeature : onEachFeature_zone });

function onEachFeature_zone(feature, layer) {
	if(layer.feature.properties.zone_color != null) {    
		layer.setStyle({ 
			fill: false, 
			color: layer.feature.properties.zone_color,
			weight: 4
		}); 
	} 
	
	var popupContent = layer.feature.properties.project_name;

	if (feature.properties) {
		layer.bindPopup(popupContent);
	}
}

function onEachFeature_traces(feature, layer) {
	
	if(mapEdit_update == 1){  
		var editBtn = '<i class="fa fa-pen-square fa-fw pull-right" onclick="editTraceLine(\''+layer.feature.properties.plant_line_id+'\');" style="cursor:pointer; color:green;"></i>';
	} else { var editBtn = ""; }
	
	if(layer.feature.properties.plant_line_id === null){ var plant_line_id=""; } else { var plant_line_id=layer.feature.properties.plant_line_id; }
	if(layer.feature.properties.id_plantation === null){ var id_plantation=""; } else { var id_plantation=layer.feature.properties.id_plantation; }
	if(layer.feature.properties.id_region === null){ var id_region="-"; } else { var id_region=layer.feature.properties.id_region; }
	// if(layer.feature.properties.name_town === null){ var name_town="-"; } else { var name_town=layer.feature.properties.name_town; }
	var name_town = '';
	
	var popupContent = "<div style=\"max-width:400px; max-height: 200px\"><h5 style=\"border-bottom: 1px solid #eee;\">"+blanc
	+"<i class=\"fa fa-check-square fa-fw\" style=\"color:#ed1b2c\"></i><strong style=\"color:#ed1b2c\">&nbsp;&nbsp;Trace</strong>"+editBtn+"</h5>"+blanc
		+"<div class=\"icon_desc\" style=\"margin-left:0px;display:block\">"+blanc
		+" <span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Town name : </strong>"+name_town
		+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> ID Plantation : </strong>"+id_plantation
		+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> ID Region : </strong>"+id_region
		+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> ID Trace : </strong>"+plant_line_id
	+" </span></div></div>";

	if (feature.properties) {
		layer.bindPopup(popupContent);
	}
}

var plantation_lines_couche = L.geoJson(null, {
	style: function (feature) {
		return {
			color: '#EE82EE',
			weight: 2
		};
	}, onEachFeature : onEachFeature_traces
});

var towns = L.layerGroup([label_towns, towns_mrker_couche]);

function onEachFeature_region(feature, layer) {
	if(layer.feature.properties.map_color != null) {    
		layer.setStyle({ 
			fill: true, 
			color: '#FF8000',
			fillColor : layer.feature.properties.map_color, 
			weight: 1,
			opacity: 1
		}); 
		
	} else {
		layer.setStyle({ 
			fill: false, 
			color : '#FF8000', 
			weight: 1 
		}); 
	}
	
	var popupContent = layer.feature.properties.name_region;

	if (feature.properties) {
		layer.bindPopup(popupContent);
	}
}


var departements = L.geoJson(null, { onEachFeature : onEachFeature_departement });

function onEachFeature_departement(feature, layer) {
	if(layer.feature.properties.map_color != null) {    
		layer.setStyle({ 
			fill: true, 
			color: '#265CFF',
			fillColor : layer.feature.properties.map_color, 
			weight: 1,
			opacity: 1
		}); 
		
	} else {
		layer.setStyle({ 
			fill: false, 
			color : '#265CFF', 
			weight: 1 
		}); 
	}
  
	var popupContent = layer.feature.properties.name_dpt;

	if (feature.properties) {
		layer.bindPopup(popupContent);
	}
}


var sous_prefectures = L.geoJson(null, { onEachFeature : onEachFeature_sous_prefecture });

function onEachFeature_sous_prefecture(feature, layer) {
	if(layer.feature.properties.map_color != null) {    
		layer.setStyle({ 
			fill: true, 
			color: '#663300',
			fillColor : layer.feature.properties.map_color, 
			weight: 1,
			opacity: 1
		}); 
		
	} else {
		layer.setStyle({ 
			fill: false, 
			color : '#663300', 
			weight: 1 
		}); 
	}
	
	var popupContent = layer.feature.properties.name_spf;

	if (feature.properties) {
		layer.bindPopup(popupContent);
	}
}

var water = L.geoJson(null, {
	style: function (feature) {
		return {
			color: '#2BA5FF',
			fillColor: '#2BA5FF',
			weight: 1,
			fill: true,
			opacity: 1
		};
	}, onEachFeature : onEachFeature_water
});

var water_ways = L.geoJson(null, {
	style: function (feature) {
		return {
			color: '#AADAFE',
			fillColor: '#AADAFE',
			weight: 1,
			// fill: true,
			opacity: 1
		};
	}, onEachFeature : onEachFeature_water
});

function onEachFeature_water(feature, layer) {
	var name, type;
	if(layer.feature.properties.name == null) { name = ""; } else { name = layer.feature.properties.name; }
	if(layer.feature.properties.fclass == null) { type = ""; } else { type = layer.feature.properties.fclass; }
	
	var popupContent = "<strong>Name:</strong> "+name
	    +"<br><strong>Type:</strong> "+type;
		
	if (feature.properties) {
		layer.bindPopup(popupContent);
	}
}


var parc_national = L.geoJson(null, {
	style: function (feature) {
		return {
			color: 'red',
			fillColor: 'green',
			dashArray: '3',
			weight: 1,
			fill: true,
			opacity: 1
		};
	}, onEachFeature : onEachFeature_area
});

var reserve = L.geoJson(null, {
	style: function (feature) {
		return {
			color: 'blue',
			fillColor: 'green',
			dashArray: '3',
			weight: 1,
			fill: true,
			opacity: 1
		};
	}, onEachFeature : onEachFeature_area
});

var foret_classee = L.geoJson(null, {
	style: function (feature) {

		return {
			color: 'green',
			fillColor: 'green',
			dashArray: '3',
			weight: 1,
			fill: true,
			opacity: 1
		};
	}, onEachFeature : onEachFeature_area
});


function onEachFeature_area(feature, layer) {
	var popupContent = "<strong>Name:</strong> "+layer.feature.properties.NOM_FORET
	    +"<br><strong>Type:</strong> "+layer.feature.properties.TYPE
	    +"<br><strong>Creation:</strong> "+layer.feature.properties.date_creation
	    +"<br><strong>Area(ha):</strong> "+layer.feature.properties.superficie;
		
	if (feature.properties) {
		layer.bindPopup(popupContent);
	}
}

var drawnPolygone = L.featureGroup();

var drawLineControl = new L.Control.Draw({
	position: 'topright',
    edit: {
        featureGroup: drawnPolyline,
        poly: {
            allowIntersection: false
        }
    },
    draw: {
		marker: false,
        polygon: false,
        circle: false,
		rectangle: false,
		circlemarker: false
    }
});


var drawControl = new L.Control.Draw({
	position: 'topright',
    edit: {
        featureGroup: drawnPolygone,
        poly: {
            allowIntersection: false
        }
    },
    draw: {
		marker: false,
        polyline: false,
        circle: false,
		rectangle: false,
		circlemarker: false
    }
});

var drawnPolyline = L.featureGroup();


function expandGeoMap() {
	$("#geoFarmers").addClass("hide");
	
	$("#geoMap").removeClass("col-md-8");
	$("#geoMap").removeClass("col-sm-8");
	$("#geoMap").removeClass("col-xs-8");
	
	$("#geoMap").addClass("col-md-12");
	$("#geoMap").addClass("col-sm-12");
	$("#geoMap").addClass("col-xs-12");

	map.removeControl(expdBtn);
	expdBtn = L.easyButton('fa fa-compress fa-lg', function(btn, map){ compressGeoMap(); });
	expdBtn.addTo(map);
	
	setTimeout(function() {
		map.invalidateSize();
	}, 100);
}

function compressGeoMap() {
	$("#geoFarmers").removeClass("hide");
	
	$("#geoMap").removeClass("col-md-12");
	$("#geoMap").removeClass("col-sm-12");
	$("#geoMap").removeClass("col-xs-12");
	
	$("#geoMap").addClass("col-md-8");
	$("#geoMap").addClass("col-sm-8");
	$("#geoMap").addClass("col-xs-8");
	
	map.removeControl(expdBtn);
	expdBtn = L.easyButton('fa fa-expand fa-lg', function(btn, map){ expandGeoMap(); });
	expdBtn.addTo(map);
	
	setTimeout(function() {
		map.invalidateSize();
	}, 100);
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

function removeDrawCtl() {

	map.removeControl(drawLineControl);
	drawnPolyline.clearLayers();
	if(polyline){ map.removeLayer(polyline); }
	
	if(newLineBtn){ 
		map.removeControl(newLineBtn); 
		newLineBtn = L.easyButton('fa fa-route fa-lg', function(btn, map){ addTLineModule(); }, { position: 'topright' });  
		newLineBtn.addTo(map);
	}

	map.removeControl(drawControl);
	drawnPolygone.clearLayers();
	if(polygon){ map.removeLayer(polygon); }
}

function editFarmerPlant(id_plantation,id_farmer) { 

	if(saveBtn){ map.removeControl(saveBtn); }
	if(cancelBtn){ map.removeControl(cancelBtn); }
	
	removeDrawCtl();
	// map.removeControl(drawControl);
	// drawnPolygone.clearLayers();
	
	saveBtn = L.easyButton('fa fa-save fa-lg', function(btn, map){ saveFPModule(id_plantation,id_farmer,'geolocation'); });
	cancelBtn = L.easyButton('fa fa-ban fa-lg', function(btn, map){ closeFPModule(id_farmer,'geolocation'); });
	
	cancelBtn.button.style.color = 'white';
	cancelBtn.button.style.backgroundColor = 'red';
	saveBtn.button.style.color = 'white';
	saveBtn.button.style.backgroundColor = 'green';
	
	saveBtn.addTo(map);
	cancelBtn.addTo(map);
	
	map.addControl(drawControl);
	
	var resurl='include/geolocation.php?elemid=show_plantations_geom_json&id_plantation='+id_plantation;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;  
			
			var bounds = [];
			var plantations = JSON.parse(leselect);  
			var long_ = plantations.coordinates[0][0].length;
			for(var i=0; i<long_; i++){ 
				bounds.push([plantations.coordinates[0][0][i][1],plantations.coordinates[0][0][i][0]]);
			}
	
			polygon = new L.Polygon(bounds);
			polygon.editing.enable();
	
			var polygon2 = turf.polygon([bounds]);   
			seeArea = turf.area(polygon2); 
		
			map.addLayer(polygon);
			
			polygon.on('edit', function(e) {
				var json = e.target.toGeoJSON();

				var new_json = {
					"type": "MultiPolygon",
					"coordinates": [
						json.geometry.coordinates
					] 
				};
			
				poly = JSON.stringify(new_json);
	
				var polygon3 = turf.polygon(json.geometry.coordinates);  
				seeArea = turf.area(polygon3);  
			});
			
			map.fitBounds(polygon.getBounds());
		}
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);

	map.on(L.Draw.Event.CREATED, function (event) {
		var layer = event.layer;

		drawnPolygone.addLayer(layer);
		map.addLayer(drawnPolygone);

		var type = event.layerType;
		if (type === 'polygon') {
			// seeArea = L.GeometryUtil.geodesicArea(layer.getLatLngs());
			
			var polygon4 = turf.polygon(layer.getLatLngs()); 
			seeArea = turf.area(polygon4); 
		}
		  
		var json = drawnPolygone.toGeoJSON();
		
		var new_json = {
			"type": "MultiPolygon",
			"coordinates": [
				json.geometry.coordinates
			]
		};
		
	// ,"crs":{"type":"name","properties":{"name":"EPSG:4326"}} 
		poly = JSON.stringify(new_json);
	});
	
	map.on(L.Draw.Event.EDITED, function(event) {
		var layers = event.layers;
		
		drawnPolygone.addLayer(layers);
		map.addLayer(drawnPolygone);
		
		var type = event.layerType;
		if (type === 'polygon') {
			var polygon5 = turf.polygon(layer.getLatLngs()); 
			seeArea = turf.area(polygon5);  
		}
		
		var json = drawnPolygone.toGeoJSON();
		
		var new_json = {
			"type": "MultiPolygon",
			"coordinates": [
				json.geometry.coordinates
			]
		};
		
		poly = JSON.stringify(new_json);
	});
}

function editFarmerCPoint(id_plantation,id_farmer) { 

	if(saveBtn){ map.removeControl(saveBtn); }
	if(cancelBtn){ map.removeControl(cancelBtn); }
	
	removeDrawCtl();
	
	plantation_points.clearLayers();
	plantation_couche.clearLayers();

	saveBtn = L.easyButton('fa fa-save fa-lg', function(btn, map){ saveFPModulePt(id_plantation,id_farmer); });
	cancelBtn = L.easyButton('fa fa-ban fa-lg', function(btn, map){ closeFPModulePt(id_farmer); });
	
	cancelBtn.button.style.color = 'white';
	cancelBtn.button.style.backgroundColor = 'red';
	saveBtn.button.style.color = 'white';
	saveBtn.button.style.backgroundColor = 'green';
	
	saveBtn.addTo(map);
	cancelBtn.addTo(map);
	
	var resurl='include/geolocation.php?elemid=show_plantations_collection_point&id_plantation='+id_plantation;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;  
			
			var val = leselect.split('##');
			
			var marker = new L.marker([val[0],val[1]], {draggable:'true'}).addTo(plantation_points);
			map.addLayer(plantation_points);
			map.setView([val[0],val[1]], 15);
			
			marker.on('dragend', function (e) {
				fm_coordx=marker.getLatLng().lat; 
				fm_coordy=marker.getLatLng().lng;
			});
	
            leselect = xhr.responseText;
		}
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function editTraceLine(plant_line_id) {

	if(saveBtn){ map.removeControl(saveBtn); }
	if(cancelBtn){ map.removeControl(cancelBtn); }

	removeDrawCtl();
	// map.removeControl(drawLineControl);
	// drawnPolyline.clearLayers();
	
	saveBtn = L.easyButton('fa fa-save fa-lg', function(btn, map){ saveTLineModule(plant_line_id); });
	cancelBtn = L.easyButton('fa fa-ban fa-lg', function(btn, map){ closeTLineModule(plant_line_id); });
	
	cancelBtn.button.style.color = 'white';
	cancelBtn.button.style.backgroundColor = 'red';
	saveBtn.button.style.color = 'white';
	saveBtn.button.style.backgroundColor = 'green';
	
	saveBtn.addTo(map);
	cancelBtn.addTo(map);
	
	map.addControl(drawLineControl);
	
	document.getElementById("traceRegion_plant_line_id").value = plant_line_id;

	var resurl='include/geolocation.php?elemid=selected_plantation_lines&plant_line_id='+plant_line_id;  
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;   
			var val = leselect.split('##');
			
			if((val[0]=="")||(val[2]=="")){ 
				$("#traceRegionModal").modal("show"); 
				
				document.getElementById("traceRegion_id").value = val[0];
				document.getElementById("tracePlantation_id").value = val[1];
			} 
			
			var bounds = [];
			var lines = JSON.parse(val[1]);  
			var long_ = lines.coordinates.length;
			for(var i=0; i<long_; i++){ 
				bounds.push([lines.coordinates[i][1],lines.coordinates[i][0]]);
			}
	
			polyline = new L.Polyline(bounds);
			polyline.editing.enable();
	
			map.addLayer(polyline);
			
			polyline.on('edit', function(e) {
				var json = e.target.toGeoJSON();

				var new_json = {
					"type": "LineString",
					"coordinates":json.geometry.coordinates
				};
			
				t_line = JSON.stringify(new_json);  
			});
			
			map.fitBounds(polyline.getBounds());
		}
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}

function addTLineModule() { 

	if(newLineBtn){ map.removeControl(newLineBtn); }
	
	newLineBtn = L.easyButton('fa fa-route fa-lg', function(btn, map){ addTLineModule(); }, { position: 'topright' });  
	newLineBtn.button.style.backgroundColor = '#dedede';
	newLineBtn.addTo(map);
	
	map.removeControl(drawLineControl);
    map.addControl(drawLineControl);
	drawnPolyline.clearLayers();
	
	
	if(cancelBtn){ map.removeControl(cancelBtn); }
	if(saveBtn){ map.removeControl(saveBtn); }
	
	cancelBtn = L.easyButton('fa fa-ban fa-lg', function(btn, map){ cancelTLineModule(); }, { position: 'topleft' }); 
	saveBtn = L.easyButton('fa fa-save fa-lg', function(btn, map){ newTLineModule(); }, { position: 'topleft' });
	
	cancelBtn.button.style.color = 'white';
	cancelBtn.button.style.backgroundColor = 'red';
	saveBtn.button.style.color = 'white';
	saveBtn.button.style.backgroundColor = 'green';
	
	cancelBtn.addTo(map);
	saveBtn.addTo(map);
	
	map.on(L.Draw.Event.CREATED, function (event) {
		var layer = event.layer;

		drawnPolyline.addLayer(layer);
		map.addLayer(drawnPolyline);

		var type = event.layerType;
		var json = drawnPolyline.toGeoJSON();    
		
		var new_json = {
			"type": "LineString",
			"coordinates": json.features[0].geometry.coordinates
		};
	
		t_line = JSON.stringify(new_json);  
	});
	
	map.on(L.Draw.Event.EDITED, function(event) {
		var layers = event.layers;
		
		drawnPolyline.addLayer(layers);
		map.addLayer(drawnPolyline);
		
		var json = drawnPolyline.toGeoJSON();
		
		var new_json = {
			"type": "LineString",
			"coordinates": json.features[0].geometry.coordinates
		};
		
		t_line = JSON.stringify(new_json);
	});
}


function cancelTLineModule() {
	
	drawnPolyline.clearLayers();  
	map.removeControl(drawLineControl);
	if(polyline){ map.removeLayer(polyline); }
	
	map.removeControl(cancelBtn); 
	map.removeControl(saveBtn); 
	
	if(newLineBtn){ map.removeControl(newLineBtn); }
	
	newLineBtn = L.easyButton('fa fa-route fa-lg', function(btn, map){ addTLineModule(); }, { position: 'topright' });  
	newLineBtn.addTo(map);
}

function newTLineModule() {
	if(t_line!=""){   
		var resurl='include/geolocation.php?elemid=add_plantation_line&geom_json='+t_line;    
		var xhr = getXhr();    
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;      			
				if(leselect == 1){
					toastr.success('New line saved successfully.',{timeOut:15000})
					cancelTLineModule();
					
				} else {
					toastr.error('Line not saved.',{timeOut:15000})
				}
			} 
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
		
	} else {
		toastr.info('Draw a line first.',{timeOut:15000})
	}
}

function clearTraceRegionForm() {
	$("#traceRegionModal").modal("hide");
	$('#traceRegionModalForm').find("input, textarea, select").val(""); 
}


function editTraceRegion() {
	
	var id_region = document.getElementById("traceRegion_id").value;
	var id_plantation = document.getElementById("tracePlantation_id").value;
	var plant_line_id = document.getElementById("traceRegion_plant_line_id").value;
	
	var resurl='include/geolocation.php?elemid=update_plantation_lines_id_region&plant_line_id='+plant_line_id+'&id_region='+id_region+'&id_plantation='+id_plantation;
    var xhr = getXhr(); 
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText; 

			if(leselect == 1){
				toastr.success('Editing saved successfully.',{timeOut:15000})
				clearTraceRegionForm();
			
			} else {
				toastr.error('Editing not saved.',{timeOut:15000})
			}
			
		}
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function showHidePlanFilter() {  
	if ( $("#geoMap_Filter").hasClass("hide") ) {   
		$("#geoMap_Filter").removeClass("hide");
		$(".dashPlantRightFilterBtn").removeClass("dashPlantRightFilterBtn_width_0");
		$(".dashPlantRightFilterBtn").addClass("dashPlantRightFilterBtn_width_210");
		
		$(".dashPlantRightFilterBtn").removeClass("fadeInLeft");
		$("#geoMap_Filter").removeClass("fadeInLeftBig");
		
		$(".dashPlantRightFilterBtn").addClass("fadeInRight");
		$("#geoMap_Filter").addClass("fadeInRightBig");
	
	} else {
		$("#geoMap_Filter").addClass("hide");
		$(".dashPlantRightFilterBtn").removeClass("dashPlantRightFilterBtn_width_210");
		$(".dashPlantRightFilterBtn").addClass("dashPlantRightFilterBtn_width_0");
		
		$(".dashPlantRightFilterBtn").removeClass("fadeInRight");
		$("#geoMap_Filter").removeClass("fadeInRightBig");
		
		$(".dashPlantRightFilterBtn").addClass("fadeInLeft");
		$("#geoMap_Filter").addClass("fadeInLeftBig");
	}
}


var scalefactor, scale;

function geolocation() { 

	// $("#db_geolocation").removeClass("hide");
	$("#geolocationSpanner").removeClass("hide");
	drawnPolygone.clearLayers();
	map.addControl(polygon_drawControl);
	
	// document.getElementById('pageTitle').innerHTML = "Geolocation/fields";
	
	$(".div_overlay").remove();
	var spinner = '<div class="sk-spinner sk-spinner-double-bounce div_ov_spanner">'+
		'<div class="sk-double-bounce1"></div>'+
		'<div class="sk-double-bounce2"></div>'+
	'</div>';

	$("#geoMap").append("<div class='div_overlay'>"+spinner+"</div>");
	
	if(drawControl){ map.removeControl(drawControl); }
	if(scalefactor){ map.removeControl(scalefactor); }
	if(scale){ map.removeControl(scale); }
	
	var resurl='include/geolocation.php?elemid=geolocation_contacts';
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;   
			var val = leselect.split('##');   
			
			map.fitWorld().zoomIn();
			
			document.getElementById('d4_content').innerHTML = val[0];

			var options = {
				valueNames: ['geo_contact_name']
			};

			var userList = new List('geo_contacts', options);

			$('#d4_content li a').click(function() {
				$('ul li.on').removeClass('on');
				$(this).closest('li').addClass('on');
			});

			document.getElementById('list_towns').innerHTML = val[1];
			document.getElementById('list_projects').innerHTML = val[2];


			load_regions();
			load_departements();
			load_sousprefectures();
			load_towns();
			load_infrastructures(); 
			load_zones(0);
			
			showAllLines();
			showAllPlantations(0);
	
			scale = L.control.scale({maxWidth:240, metric:true, imperial:false, position: 'bottomright'});
			scale.addTo(map);
            
            polylineMeasure.addTo(map);
			
			scalefactor = L.control.scalefactor();
			scalefactor.addTo(map);
			
            
			$('input#plt_filter_bio').on('ifChecked', function (event){ showAllPlantations(1); });
			$('input#plt_filter_bio').on('ifUnchecked', function (event) { showAllPlantations(1); });
			
			$('input#plt_filter_bio_suisse').on('ifChecked', function (event){ showAllPlantations(1); });
			$('input#plt_filter_bio_suisse').on('ifUnchecked', function (event) { showAllPlantations(1); });
			
			$('input#plt_filter_rspo').on('ifChecked', function (event){ showAllPlantations(1); });
			$('input#plt_filter_rspo').on('ifUnchecked', function (event) { showAllPlantations(1); });
			
			$('input#plt_filter_fair_trade').on('ifChecked', function (event){ showAllPlantations(1); });
			$('input#plt_filter_fair_trade').on('ifUnchecked', function (event) { showAllPlantations(1); });
			
			$('input#plt_filter_global_gap').on('ifChecked', function (event){ showAllPlantations(1); });
			$('input#plt_filter_global_gap').on('ifUnchecked', function (event) { showAllPlantations(1); });
			
			$('input#plt_filter_utz').on('ifChecked', function (event){ showAllPlantations(1); });
			$('input#plt_filter_utz').on('ifUnchecked', function (event) { showAllPlantations(1); });
			
			$('input#plt_filter_perimeter').on('ifChecked', function (event){ showAllPlantations(1); });
			$('input#plt_filter_perimeter').on('ifUnchecked', function (event) { showAllPlantations(1); });
			
			$('input#plt_filter_eco_river').on('ifChecked', function (event){ showAllPlantations(1); });
			$('input#plt_filter_eco_river').on('ifUnchecked', function (event) { showAllPlantations(1); });
			
			$('input#plt_filter_eco_shallows').on('ifChecked', function (event){ showAllPlantations(1); });
			$('input#plt_filter_eco_shallows').on('ifUnchecked', function (event) { showAllPlantations(1); });
			
			$('input#plt_filter_eco_wells').on('ifChecked', function (event){ showAllPlantations(1); });
			$('input#plt_filter_eco_wells').on('ifUnchecked', function (event) { showAllPlantations(1); });
			
			$('input#plt_filter_synthetic_fertilizer').on('ifChecked', function (event){ showAllPlantations(1); });
			$('input#plt_filter_synthetic_fertilizer').on('ifUnchecked', function (event) { showAllPlantations(1); });
			
			$('input#plt_filter_synthetic_herbicides').on('ifChecked', function (event){ showAllPlantations(1); });
			$('input#plt_filter_synthetic_herbicides').on('ifUnchecked', function (event) { showAllPlantations(1); });
			
			$('input#plt_filter_synthetic_pesticide').on('ifChecked', function (event){ showAllPlantations(1); });
			$('input#plt_filter_synthetic_pesticide').on('ifUnchecked', function (event) { showAllPlantations(1); });
			
			$('input#plt_filter_intercropping').on('ifChecked', function (event){ showAllPlantations(1); });
			$('input#plt_filter_intercropping').on('ifUnchecked', function (event) { showAllPlantations(1); });
			
			$('input#plt_filter_forest').on('ifChecked', function (event){ showAllPlantations(1); });
			$('input#plt_filter_forest').on('ifUnchecked', function (event) { showAllPlantations(1); });
			
			$('input#plt_filter_sewage').on('ifChecked', function (event){ showAllPlantations(1); });
			$('input#plt_filter_sewage').on('ifUnchecked', function (event) { showAllPlantations(1); });
			
			$('input#plt_filter_waste').on('ifChecked', function (event){ showAllPlantations(1); });
			$('input#plt_filter_waste').on('ifUnchecked', function (event) { showAllPlantations(1); });
			
			$('input#plt_filter_fire').on('ifChecked', function (event){ showAllPlantations(1); });
			$('input#plt_filter_fire').on('ifUnchecked', function (event) { showAllPlantations(1); });
			
			$('input#plt_filter_irrigation').on('ifChecked', function (event){ showAllPlantations(1); });
			$('input#plt_filter_irrigation').on('ifUnchecked', function (event) { showAllPlantations(1); });
			
			$('input#plt_filter_drainage').on('ifChecked', function (event){ showAllPlantations(1); });
			$('input#plt_filter_drainage').on('ifUnchecked', function (event) { showAllPlantations(1); });
			
			$('input#plt_filter_slope').on('ifChecked', function (event){ showAllPlantations(1); });
			$('input#plt_filter_slope').on('ifUnchecked', function (event) { showAllPlantations(1); });
			
			$('input#plt_filter_pest').on('ifChecked', function (event){ showAllPlantations(1); });
			$('input#plt_filter_pest').on('ifUnchecked', function (event) { showAllPlantations(1); });
			
			$('input#plt_filter_extension').on('ifChecked', function (event){ showAllPlantations(1); });
			$('input#plt_filter_extension').on('ifUnchecked', function (event) { showAllPlantations(1); });
			
			$('input#plt_filter_replanting').on('ifChecked', function (event){ showAllPlantations(1); });
			$('input#plt_filter_replanting').on('ifUnchecked', function (event) { showAllPlantations(1); });
			
			$('input#plt_filter_road_access').on('ifChecked', function (event){ showAllPlantations(1); });
			$('input#plt_filter_road_access').on('ifUnchecked', function (event) { showAllPlantations(1); });
			
			$("#geolocationSpanner").addClass("hide");
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
	
	
	map.on(L.Draw.Event.CREATED, function (event) {
		var layer = event.layer;

		drawnPolygone.addLayer(layer);
		map.addLayer(drawnPolygone);

		var json = drawnPolygone.toGeoJSON();

		var polygon2 = turf.polygon(json.features[0].geometry.coordinates);
		var box_area = turf.area(polygon2);	
		var poly_ha = box_area * 0.0001;
		document.getElementById('polyBox').innerHTML = poly_ha.toFixed(2)+' Ha';
		$("#polyBox").removeClass("hide");
	});
	
	map.on(L.Draw.Event.EDITED, function(event) {
		var layers = event.layers;
		
		drawnPolygone.addLayer(layers);
		map.addLayer(drawnPolygone);

		var polygon2 = turf.polygon(json.features[0].geometry.coordinates);
		var box_area = turf.area(polygon2);	
		var poly_ha = box_area * 0.0001;
		document.getElementById('polyBox').innerHTML = poly_ha.toFixed(2)+' Ha';
		$("#polyBox").removeClass("hide");
	});
}

var newLineBtn;

function showAllLines() {
	
	plantation_lines_couche.clearLayers();
	if(newLineBtn) { map.removeControl(newLineBtn); }
	
	var resurl='include/geolocation.php?elemid=show_all_plantation_lines';
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;    
			
			var plantation_lines = JSON.parse(leselect);    
			plantation_lines_couche.addData(plantation_lines);	 
			map.addLayer(plantation_lines_couche);  
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
	
	newLineBtn = L.easyButton('fa fa-route fa-lg', function(btn, map){ addTLineModule(); }, { position: 'topright' });  
	newLineBtn.addTo(map);
}


function showAllPlantations(conf) {
	
	var centre_x=0, centre_y=0;
	$("#rangeSliderBox").addClass("hide");
	
	plantation_points.clearLayers();
	plantation_couche.clearLayers();
	circle_layer.clearLayers();
	
	if(expdBtn){ map.removeControl(expdBtn); }

	if(conf == 1) {
		$(".div_overlay").remove();
		var spinner = '<div class="sk-spinner sk-spinner-double-bounce div_ov_spanner">'+
			'<div class="sk-double-bounce1"></div>'+
			'<div class="sk-double-bounce2"></div>'+
		'</div>';

		$("#geoMap").append("<div class='div_overlay'>"+spinner+"</div>");
	}
	
	var req='';
	if ($('#plt_filter_bio').is(':checked')) { req=req+'&bio=1'; }
	if ($('#plt_filter_bio_suisse').is(':checked')) { req=req+'&bio_suisse=1'; }
	if ($('#plt_filter_rspo').is(':checked')) { req=req+'&rspo=1'; }
	if ($('#plt_filter_fair_trade').is(':checked')) { req=req+'&fair_trade=1'; }
	if ($('#plt_filter_global_gap').is(':checked')) { req=req+'&globalgap=1'; }
	if ($('#plt_filter_utz').is(':checked')) { req=req+'&utz_rainforest=1'; }
	if ($('#plt_filter_perimeter').is(':checked')) { req=req+'&perimeter=1'; }
	if ($('#plt_filter_eco_river').is(':checked')) { req=req+'&eco_river=1'; }
	if ($('#plt_filter_eco_shallows').is(':checked')) { req=req+'&eco_shallows=1'; }
	if ($('#plt_filter_eco_wells').is(':checked')) { req=req+'&eco_wells=1'; }
	if ($('#plt_filter_synthetic_fertilizer').is(':checked')) { req=req+'&synthetic_fertilizer=1'; }
	if ($('#plt_filter_synthetic_herbicides').is(':checked')) { req=req+'&synthetic_herbicides=1'; }
	if ($('#plt_filter_synthetic_pesticide').is(':checked')) { req=req+'&synthetic_pesticide=1'; }
	if ($('#plt_filter_intercropping').is(':checked')) { req=req+'&intercropping=1'; }
	if ($('#plt_filter_forest').is(':checked')) { req=req+'&forest=1'; }
	if ($('#plt_filter_sewage').is(':checked')) { req=req+'&sewage=1'; }
	if ($('#plt_filter_waste').is(':checked')) { req=req+'&waste=1'; }
	if ($('#plt_filter_fire').is(':checked')) { req=req+'&fire=1'; }
	if ($('#plt_filter_irrigation').is(':checked')) { req=req+'&irrigation=1'; }
	if ($('#plt_filter_drainage').is(':checked')) { req=req+'&drainage=1'; }
	if ($('#plt_filter_slope').is(':checked')) { req=req+'&slope=1'; }
	if ($('#plt_filter_pest').is(':checked')) { req=req+'&pest=1'; }
	if ($('#plt_filter_rating').val() != "") { req=req+'&rating='+$('#plt_filter_rating').val(); }
	if ($('#plt_filter_surface_ha').val() != "") { req=req+'&surface_ha='+$('#plt_filter_surface_ha').val(); }
	if ($('#plt_filter_year_creation').val() != "") { req=req+'&year_creation='+$('#plt_filter_year_creation').val(); }
	if ($('#plt_filter_extension').is(':checked')) { req=req+'&extension=1'; }
	if ($('#plt_filter_year_extension').val() != "") { req=req+'&year_extension='+$('#plt_filter_year_extension').val(); }
	if ($('#plt_filter_replanting').is(':checked')) { req=req+'&replanting=1'; }
	if ($('#plt_filter_year_to_replant').val() != "") { req=req+'&year_to_replant='+$('#plt_filter_year_to_replant').val(); }
	if ($('#plt_filter_road_access').is(':checked')) { req=req+'&road_access=1'; }

	
	var resurl='include/geolocation.php?elemid=show_all_plantations'+req;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;    //console.log(leselect);
			
			map.invalidateSize();
			
			var plantations = JSON.parse(leselect); 
			var n = parseInt(JSON.stringify(plantations.length));  
			
			var poly=0, coords=0;
			
			i=0;
			while(i<n){ 
				
				if(mapEdit_update == 1){  
					var editBtn = '<i class="fa fa-pen-square fa-fw pull-right" onclick="editFarmerCPoint(\''+plantations[i].properties.gid_plantation+'\',\''+plantations[i].properties.id_contact+'\');" style="cursor:pointer; color:green;"></i>';
				} else { var editBtn = ""; }

				var geom_json = JSON.stringify(plantations[i].properties.geom_json);    
				var coordx = JSON.stringify(plantations[i].properties.coordx);  
				var coordy = JSON.stringify(plantations[i].properties.coordy);  
				
				if((geom_json!=null)&&(geom_json!='null')) { poly += 1;
					plantation_couche.addData(plantations[i]);	 
					map.addLayer(plantation_couche); 
				} 
				
				if(((coordx!='null')&&(coordy!='null')) &&
					((coordx!=null)&&(coordy!=null))){  coords += 1;
					if(plantations[i].properties.name_farmer === null){ var name_farmer=""; } else { var name_farmer=plantations[i].properties.name_farmer; }
						if(plantations[i].properties.name_farmergroup === null){ var name_farmergroup=""; } else { var name_farmergroup=plantations[i].properties.name_farmergroup; }
						if(plantations[i].properties.name_town === null){ var name_town=""; } else { var name_town=plantations[i].properties.name_town; }
						if(plantations[i].properties.code_farmer === null){ var code_farmer=""; } else { var code_farmer=plantations[i].properties.code_farmer; }
						if(plantations[i].properties.culture === null){ var culture=""; } else { var culture=plantations[i].properties.culture; }
						if(plantations[i].properties.surface_ha === null){ var surface_ha=""; } else { var surface_ha=plantations[i].properties.surface_ha; }
						if(plantations[i].properties.name_buyer === null){ var name_buyer=""; } else { var name_buyer=plantations[i].properties.name_buyer; }
						if(plantations[i].properties.gid_plantation === null){ var gid_plantation=""; } else { var gid_plantation=plantations[i].properties.gid_plantation; }
						if(plantations[i].properties.gid_town === null){ var gid_town=""; } else { var gid_town=plantations[i].properties.gid_town; }
						if(plantations[i].properties.id_contact === null){ var id_contact=""; } else { var id_contact=plantations[i].properties.id_contact; }
	
						var popupContent = "<div style=\"max-width:400px; max-height: 200px\"><h5 style=\"border-bottom: 1px solid #eee;\">"+blanc
						+"<i class=\"fa fa-check-square fa-fw\" style=\"color:#ed1b2c\"></i><strong style=\"color:#ed1b2c\">&nbsp;&nbsp;Collection Point</strong>"+editBtn+"</h5>"+blanc
							+"<div class=\"icon_desc\" style=\"margin-left:0px;display:block\"><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Farmer name : </strong>"+name_farmer
							+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Farmer group : </strong>"+name_farmergroup
							+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Farmer residence : </strong>"+name_town
							+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Code Farmer : </strong>"+code_farmer
							+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Culture : </strong>"+culture
							+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Area (ha) : </strong>"+surface_ha
							+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Buyer : </strong>"+name_buyer
							+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> GID plantation : </strong>"+gid_plantation
							+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> GID town : </strong>"+gid_town
							+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> ID Contact: </strong>"+id_contact
						+" </span></div></div>";
					
					mark = L.marker([plantations[i].properties.coordx, plantations[i].properties.coordy], {icon: pointIcon,riseOnHover:true})
						.bindPopup(popupContent)
						.on('click', function(e) { $("#rangeSliderBox").removeClass("hide");
							centre_x=e.latlng.lat; centre_y=e.latlng.lng;
							create_circle(centre_x,centre_y,0); 
						})
						.addTo(plantation_points);
					map.addLayer(plantation_points);
				}  
				
				i += 1;
			}
			
			if((coords == 0) && (poly != 0)) {
				map.fitBounds(plantation_couche.getBounds());
			} else
			if((poly == 0) && (coords != 0)) {
				map.fitBounds(plantation_points.getBounds());
			} else 
			if((poly != 0) && (coords != 0)) {
				map.fitBounds(plantation_points.getBounds());
			} else {
				map.fitWorld().zoomIn(); 
			}
			
			
			// if(coords == 0){ console.log(1);
				// map.fitBounds(plantation_couche.getBounds());
			// } else
			// if(poly == 0){ console.log(2);
				// map.fitBounds(plantation_points.getBounds());
			// } else { console.log(3);
				// map.fitBounds(plantation_points.getBounds());
			// }
			
			
			$(".div_overlay").remove();
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
	
	expdBtn = L.easyButton('fa fa-expand fa-lg', function(btn, map){ expandGeoMap(); });
	expdBtn.addTo(map);

	$("#ionrange_equipement").ionRangeSlider({
		min: 0,
		max: 100,
		postfix: " Km",
		onFinish: function (data) {
			create_circle(centre_x,centre_y,data.fromNumber);
        }
    });

}

function create_circle(centre_x,centre_y,rayon) {
	circle_layer.clearLayers();

	var circle = L.circle([centre_x, centre_y], rayon*1000, {
		color: 'red',
		fillColor: '#f03',
		fillOpacity: 0.2
	}).addTo(circle_layer);
	circle_layer.addTo(map);

	map.fitBounds(circle_layer.getBounds());
}

// Load Administrative Data

var overlayMaps_map = { 
	"Plantation Data": {
		"&nbsp;&nbsp;<img src='img/collection_point.png' width='20' height='20'>&nbsp;Collection Point": plantation_points,
		"&nbsp;&nbsp;<img src='img/plantation.png' width='20' height='20'>&nbsp;Plantation": plantation_couche,  
		"&nbsp;&nbsp;<img src='img/trace.png' width='20' height='20'>&nbsp;Traces": plantation_lines_couche
	},
	
	"Administrative Data": {
		"&nbsp;&nbsp;<img src='img/region.png' width='20' height='20'>&nbsp;Regions": regions,
		"&nbsp;&nbsp;<img src='img/departement.png' width='20' height='20'>&nbsp;Departments": departements,
		"&nbsp;&nbsp;<img src='img/sous_prefecture.png' width='20' height='20'>&nbsp;Sub prefectures": sous_prefectures,
		"&nbsp;&nbsp;<img src='img/town.png' width='20' height='20'>&nbsp;Towns": towns
	},
	
	"Project Data": {
		"&nbsp;&nbsp;<img src='img/icon_infrastructure.png' width='20' height='20'>&nbsp;Infrastructure": infrastructure_couche,
		"&nbsp;&nbsp;<img src='img/icon_zone.png' width='20' height='20'>&nbsp;Zone": project_zone
	},

	"Protected area": {
		"&nbsp;&nbsp;<img src='img/parc.png' width='20' height='20'>&nbsp;National Parc": parc_national,
		"&nbsp;&nbsp;<img src='img/reserve.png' width='20' height='20'>&nbsp;National Reserve": reserve,
		"&nbsp;&nbsp;<img src='img/classified.png' width='20' height='20'>&nbsp;Classified Area": foret_classee
    },
	
	"Waters": {
		"&nbsp;&nbsp;<img src='img/water.png' width='20' height='20'>&nbsp;Eaux": water,
		"&nbsp;&nbsp;<img src='img/water_ways.png' width='20' height='20'>&nbsp;Cours d'eaux": water_ways
    }
};


L.control.groupedLayers(baseMaps, overlayMaps_map).addTo(map);


$.getJSON("data/parc_national.json", function (data) {
    parc_national.addData(data);
});

$.getJSON("data/reserve.json", function (data) {
    reserve.addData(data);
});

$.getJSON("data/foret_classee.json", function (data) {
    foret_classee.addData(data);
});



$.getJSON("data/water.json", function (data) {
    water.addData(data);
});

$.getJSON("data/water_ways.json", function (data) {
    water_ways.addData(data);
});


function load_infrastructures() {
	infrastructure_couche.clearLayers();
	
	var resurl='administrative_data.php?elemid=infrastructures';
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;   
			var data = leselect.split('@@');
			
			i = 0;
			while(data[i] != 'end'){
				
				var elt=data[i].split('##');
			
				if((elt[1]!="")&&(elt[2]!="")) {
					var popupContent = "<div style=\"max-width:400px; max-height: 200px\"><h5 style=\"border-bottom: 1px solid #eee;\">"+blanc
					+"<i class=\"fa fa-check-square fa-fw\" style=\"color:#ed1b2c\"></i><strong style=\"color:#ed1b2c\">&nbsp;&nbsp;"+elt[0]+"</strong></h5>"+blanc
						+"<div class=\"icon_desc\" style=\"margin-left:0px;display:block\">"+blanc
						+" <span>"+elt[3]
					+" </span></div></div>";
	
	
					var mark = L.marker([elt[1], elt[2]],{icon: infrastructureIcon,riseOnHover:true}).bindPopup(popupContent).addTo(infrastructure_couche); 
				} 
				
				i += 1;
			}
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}

function load_regions() {
	regions.clearLayers();
	var resurl='administrative_data.php?elemid=regions';
    var xhr = getXhr();
    xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;  
			
			var region_layer = JSON.parse(leselect);    
			regions.addData(region_layer);	
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null)
}

function load_departements() {
	departements.clearLayers();
	var resurl='administrative_data.php?elemid=departements';
    var xhr = getXhr();
    xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;  
			
			var departements_layer = JSON.parse(leselect);    
			departements.addData(departements_layer);	
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null)
}

function load_sousprefectures() {
	sous_prefectures.clearLayers();
	var resurl='administrative_data.php?elemid=sous_prefectures';
    var xhr = getXhr();
    xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText; 
			
			var sous_prefectures_layer = JSON.parse(leselect);    
			sous_prefectures.addData(sous_prefectures_layer);
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null)
}

function load_towns() {
	label_towns.clearLayers();
	towns_mrker_couche.clearLayers();
	
	var resurl='administrative_data.php?elemid=towns';
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;   //console.log(leselect);
			var data = leselect.split('@@');
			
			i = 0; var x, y;
			while(data[i] != 'end'){
				
				var elt=data[i].split('##');
				var popupContent;
			
				if((elt[1]!="")&&(elt[2]!="")) {
					popupContent = elt[0];
					var mark = L.marker([elt[1], elt[2]],{icon: townIcon,riseOnHover:true}).bindPopup(popupContent).addTo(towns_mrker_couche); 
					
					var divIcon = L.divIcon({ 
						className: "labelClass",
						iconAnchor:[-15,25],
						html: elt[0]
					});

					var mark2 = L.marker([elt[1], elt[2]], {icon: divIcon }).addTo(label_towns); 
					// map.addLayer(towns_mrker_couche);
				} 
				
				x= elt[1]; y= elt[2];
				i += 1;
			}
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}

function load_zones(id_project) {
	project_zone.clearLayers();
	var resurl='administrative_data.php?elemid=zones&id_project='+id_project;
    var xhr = getXhr();
    xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;  // console.log(leselect);
			
			var project_zone_layer = JSON.parse(leselect);    
			project_zone.addData(project_zone_layer);
			
			// var data = leselect.split('@@');
			
			// i = 0;
			// while(data[i] != 'end'){
				// var elt=data[i].split('##');
				
				// var project_zone_layer = JSON.parse(elt[0]);     console.log(project_zone_layer);
				// project_zone.addData(project_zone_layer);
				// project_zone.bindPopup(elt[1]);
				
				// if(elt[2] != null) {    
					// project_zone.setStyle({ 
						// fill: true, 
						// color: '#999999',
						// fillColor : elt[2], 
						// weight: 2,
						// opacity: 1
					// }); 
		
				// } else {
					// layer.setStyle({ 
						// fill: false, 
						// color : '#999999', 
						// weight: 2 
					// }); 
				// }
				
				// i += 1;
			// }
	
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null)
}

function byTowns() {
	
	var centre_x=0, centre_y=0;
	$("#rangeSliderBox").addClass("hide");

	plantation_couche.clearLayers();
	plantation_points.clearLayers();
	
	map.removeControl(drawControl);
	
	var code_town = document.getElementById('list_towns').value;
	
	if(code_town == ""){
		geolocation();
		
	} else {
		$("#geolocationSpanner").removeClass("hide");
	
		$(".div_overlay").remove();
		var spinner = '<div class="sk-spinner sk-spinner-double-bounce div_ov_spanner">'+
			'<div class="sk-double-bounce1"></div>'+
			'<div class="sk-double-bounce2"></div>'+
		'</div>';

		$("#geoMap").append("<div class='div_overlay'>"+spinner+"</div>");
		
		$("#list_projects").val($("#list_projects option:first").val());
		
		var resurl='include/geolocation.php?elemid=show_by_towns&code_town='+code_town;
		var xhr = getXhr();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;
				var val = leselect.split('**');  

				document.getElementById('d4_content').innerHTML = val[0];

				var options = {
					valueNames: ['geo_contact_name']
				};

				var userList = new List('geo_contacts', options);

				$('#d4_content li a').click(function() {
					$('ul li.on').removeClass('on');
					$(this).closest('li').addClass('on');
				});
				
			
				var plantations = JSON.parse(val[1]); 
				var n = parseInt(JSON.stringify(plantations.length)); 
			
				i=0;
				var b=0;
				while(i<n){ 
					
					if(mapEdit_update == 1){  
						var editBtn = '<i class="fa fa-pen-square fa-fw pull-right" onclick="editFarmerCPoint(\''+plantations[i].properties.gid_plantation+'\',\''+plantations[i].properties.id_contact+'\');" style="cursor:pointer; color:green;"></i>';
					} else { var editBtn = ""; }

					var geom_json = JSON.stringify(plantations[i].properties.geom_json);   
					var coordx = JSON.stringify(plantations[i].properties.coordx);    
					var coordy = JSON.stringify(plantations[i].properties.coordy);    
					
					if(geom_json!="null"){  
						plantation_couche.addData(plantations);	
						
						map.addLayer(plantation_couche);  
						map.fitBounds(plantation_couche.getBounds());  
					} 
					
					if((coordx!="null")&&(coordy!="null")) {
						
						if(plantations[i].properties.name_farmer === null){ var name_farmer=""; } else { var name_farmer=plantations[i].properties.name_farmer; }
						if(plantations[i].properties.name_farmergroup === null){ var name_farmergroup=""; } else { var name_farmergroup=plantations[i].properties.name_farmergroup; }
						if(plantations[i].properties.name_town === null){ var name_town=""; } else { var name_town=plantations[i].properties.name_town; }
						if(plantations[i].properties.code_farmer === null){ var code_farmer=""; } else { var code_farmer=plantations[i].properties.code_farmer; }
						if(plantations[i].properties.culture === null){ var culture=""; } else { var culture=plantations[i].properties.culture; }
						if(plantations[i].properties.surface_ha === null){ var surface_ha=""; } else { var surface_ha=plantations[i].properties.surface_ha; }
						if(plantations[i].properties.name_buyer === null){ var name_buyer=""; } else { var name_buyer=plantations[i].properties.name_buyer; }
						if(plantations[i].properties.gid_plantation === null){ var gid_plantation=""; } else { var gid_plantation=plantations[i].properties.gid_plantation; }
						if(plantations[i].properties.gid_town === null){ var gid_town=""; } else { var gid_town=plantations[i].properties.gid_town; }
						if(plantations[i].properties.id_contact === null){ var id_contact=""; } else { var id_contact=plantations[i].properties.id_contact; }
	
						var popupContent = "<div style=\"max-width:400px; max-height: 200px\"><h5 style=\"border-bottom: 1px solid #eee;\">"+blanc
						+"<i class=\"fa fa-check-square fa-fw\" style=\"color:#ed1b2c\"></i><strong style=\"color:#ed1b2c\">&nbsp;&nbsp;Collection Point</strong>"+editBtn+"</h5>"+blanc
							+"<div class=\"icon_desc\" style=\"margin-left:0px;display:block\"><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Farmer name : </strong>"+name_farmer
							+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Farmer group : </strong>"+name_farmergroup
							+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Farmer residence : </strong>"+name_town
							+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Code Farmer : </strong>"+code_farmer
							+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Culture : </strong>"+culture
							+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Area (ha) : </strong>"+surface_ha
							+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Buyer : </strong>"+name_buyer
							+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> GID plantation : </strong>"+gid_plantation
							+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> GID town : </strong>"+gid_town
							+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> ID Contact: </strong>"+id_contact
						+" </span></div></div>";
						
						mark = L.marker([plantations[i].properties.coordx, plantations[i].properties.coordy], {icon: pointIcon,riseOnHover:true})
							.bindPopup(popupContent)
							.on('click', function(e) { $("#rangeSliderBox").removeClass("hide");
								centre_x=e.latlng.lat; centre_y=e.latlng.lng;
								create_circle(centre_x,centre_y,0); 
							})
							.addTo(plantation_points);
							
						map.addLayer(plantation_points);
					} 
					
					if((JSON.stringify(geom_json)=="null")&&((coordx=="null")&&(coordy=="null"))){
						map.fitWorld().zoomIn(); 
					} else { b=1; }
					
					i += 1;
				}
				
				$("#geolocationSpanner").addClass("hide");
				$(".div_overlay").remove();
				
				if(b == 1){
					map.fitBounds(plantation_couche.getBounds().extend(plantation_points.getBounds()));
				}
			}
		};
		
		xhr.open("GET",resurl,true);
		xhr.send(null);
	}
	
	$("#ionrange_equipement").ionRangeSlider({
		min: 0,
		max: 100,
		postfix: " Km",
		onFinish: function (data) {
			create_circle(centre_x,centre_y,data.fromNumber);
        }
    });
	
	plantation_points.on('clusterclick', function (a) {
		a.layer.zoomToBounds();
	});
}


function byProjects() {
	
	var centre_x=0, centre_y=0;
	$("#rangeSliderBox").addClass("hide");
	
	plantation_couche.clearLayers();
	plantation_points.clearLayers();
	
	map.removeControl(drawControl);
	
	var id_project = document.getElementById('list_projects').value;
	
	if(id_project == ""){
		geolocation();
		
	} else {
		
		$("#geolocationSpanner").removeClass("hide");
	
		$(".div_overlay").remove();
		var spinner = '<div class="sk-spinner sk-spinner-double-bounce div_ov_spanner">'+
			'<div class="sk-double-bounce1"></div>'+
			'<div class="sk-double-bounce2"></div>'+
		'</div>';

		$("#geoMap").append("<div class='div_overlay'>"+spinner+"</div>");
	
		$("#list_towns").val($("#list_towns option:first").val());
		
		var resurl='include/geolocation.php?elemid=show_by_project&id_project='+id_project; 
		var xhr = getXhr();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;  
				var val = leselect.split('**');  

				document.getElementById('d4_content').innerHTML = val[0];
				
				load_zones(id_project);

				var options = {
					valueNames: ['geo_contact_name']
				};

				var userList = new List('geo_contacts', options);

				$('#d4_content li a').click(function() {
					$('ul li.on').removeClass('on');
					$(this).closest('li').addClass('on');
				});
				
				map.invalidateSize();
			
				var plantations = JSON.parse(val[1]); 
				var n = parseInt(JSON.stringify(plantations.length));  
				
				i=0;
				while(i<n){ 
					
					if(mapEdit_update == 1){  
						var editBtn = '<i class="fa fa-pen-square fa-fw pull-right" onclick="editFarmerCPoint(\''+plantations[i].properties.gid_plantation+'\',\''+plantations[i].properties.id_contact+'\');" style="cursor:pointer; color:green;"></i>';
					} else { var editBtn = ""; }

					var geom_json = JSON.stringify(plantations[i].properties.geom_json);   
					var coordx = JSON.stringify(plantations[i].properties.coordx);  
					var coordy = JSON.stringify(plantations[i].properties.coordy);  
					
					if(geom_json!="null"){   
						plantation_couche.addData(plantations[i]);	 
						
						map.addLayer(plantation_couche);     
						// map.fitBounds(plantation_couche.getBounds());
					} 
					
					if((coordx!="null")&&(coordy!="null")) { 
						if(plantations[i].properties.name_farmer === null){ var name_farmer=""; } else { var name_farmer=plantations[i].properties.name_farmer; }
							if(plantations[i].properties.name_farmergroup === null){ var name_farmergroup=""; } else { var name_farmergroup=plantations[i].properties.name_farmergroup; }
							if(plantations[i].properties.name_town === null){ var name_town=""; } else { var name_town=plantations[i].properties.name_town; }
							if(plantations[i].properties.code_farmer === null){ var code_farmer=""; } else { var code_farmer=plantations[i].properties.code_farmer; }
							if(plantations[i].properties.culture === null){ var culture=""; } else { var culture=plantations[i].properties.culture; }
							if(plantations[i].properties.surface_ha === null){ var surface_ha=""; } else { var surface_ha=plantations[i].properties.surface_ha; }
							if(plantations[i].properties.name_buyer === null){ var name_buyer=""; } else { var name_buyer=plantations[i].properties.name_buyer; }
							if(plantations[i].properties.gid_plantation === null){ var gid_plantation=""; } else { var gid_plantation=plantations[i].properties.gid_plantation; }
							if(plantations[i].properties.gid_town === null){ var gid_town=""; } else { var gid_town=plantations[i].properties.gid_town; }
							if(plantations[i].properties.id_contact === null){ var id_contact=""; } else { var id_contact=plantations[i].properties.id_contact; }
		
							var popupContent = "<div style=\"max-width:400px; max-height: 200px\"><h5 style=\"border-bottom: 1px solid #eee;\">"+blanc
							+"<i class=\"fa fa-check-square fa-fw\" style=\"color:#ed1b2c\"></i><strong style=\"color:#ed1b2c\">&nbsp;&nbsp;Collection Point</strong>"+editBtn+"</h5>"+blanc
								+"<div class=\"icon_desc\" style=\"margin-left:0px;display:block\"><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Farmer name : </strong>"+name_farmer
								+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Farmer group : </strong>"+name_farmergroup
								+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Farmer residence : </strong>"+name_town
								+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Code Farmer : </strong>"+code_farmer
								+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Culture : </strong>"+culture
								+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Area (ha) : </strong>"+surface_ha
								+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Buyer : </strong>"+name_buyer
								+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> GID plantation : </strong>"+gid_plantation
								+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> GID town : </strong>"+gid_town
								+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> ID Contact: </strong>"+id_contact
							+" </span></div></div>";
						
						mark = L.marker([plantations[i].properties.coordx, plantations[i].properties.coordy], {icon: pointIcon,riseOnHover:true})
							.bindPopup(popupContent)
							.on('click', function(e) { $("#rangeSliderBox").removeClass("hide");
								centre_x=e.latlng.lat; centre_y=e.latlng.lng;
								create_circle(centre_x,centre_y,0); 
							})
							.addTo(plantation_points);
						map.addLayer(plantation_points);
						
						map.fitBounds(plantation_points.getBounds());
					}  

					i += 1;
				}
				
				$("#geolocationSpanner").addClass("hide");
				$(".div_overlay").remove();
			}
		};
		
		xhr.open("GET",resurl,true);
		xhr.send(null);
	}

	$("#ionrange_equipement").ionRangeSlider({
		min: 0,
		max: 100,
		postfix: " Km",
		onFinish: function (data) {
			create_circle(centre_x,centre_y,data.fromNumber);
        }
    });

	plantation_points.on('clusterclick', function (a) {
		a.layer.zoomToBounds();
	});
}

function deleteTraceRegion() {
	
	var plant_line_id = document.getElementById("traceRegion_plant_line_id").value;
	var r = confirm("Are you sure you want to delete ID Trace : "+plant_line_id);
	
	if(r == true){
		var resurl='include/geolocation.php?elemid=delete_plantation_lines&plant_line_id='+plant_line_id;
		var xhr = getXhr(); 
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;  

				if(leselect == 1){
					toastr.success('Trace deleted successfully.',{timeOut:15000})
					geolocation();
				
				} else {
					toastr.error('Trace not deleted.',{timeOut:15000})
				}
				
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
	}
}

// Edit - Save Polygon
function closeFPModule(id_farmer,conf) {

	drawnPolygone.clearLayers();

	// showPlantations(id_farmer);
	if(conf == 'farmer') {
		map2.removeControl(drawControl);
	
		map2.removeControl(saveBtn);
		map2.removeControl(cancelBtn);
		
		map2.removeLayer(polygon);
	
	} else {
		map.removeControl(drawControl);
	
		map.removeControl(saveBtn);
		map.removeControl(cancelBtn);
		
		map.removeLayer(polygon);
	
		showAllPlantations(0);
		// geolocation();
	}
}

function saveFPModulePt(id_plantation,id_farmer) {  
	if((fm_coordx!="")&&(fm_coordy!="")){   
		var resurl='include/geolocation.php?elemid=save_farmer_collection_point&id_plantation='+id_plantation+'&coordx='+fm_coordx+'&coordy='+fm_coordy; 
		var xhr = getXhr();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText; 
				
				if(leselect == 1){
					toastr.success('Editing saved successfully.',{timeOut:15000})
					showPlantations(id_farmer);
					closeFPModulePt(id_farmer);
					
				} else {
					toastr.error('Editing not saved.',{timeOut:15000})
				}

				leselect = xhr.responseText;
			} 
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
		
	} else {
		toastr.info('Add a marker.',{timeOut:15000})
	}
}


// Edit - Save Marker

function saveFPModule(id_plantation,id_farmer,conf) {  
	if(poly!=""){   
		var resurl='include/geolocation.php?elemid=save_farmer_plantation&id_plantation='+id_plantation+'&area_acres='+seeArea+'&geom_json='+poly; 
		var xhr = getXhr();  
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;    
				
				if(leselect == 1){
					toastr.success('Editing saved successfully.',{timeOut:15000})
					if(conf == 'farmer') {
						showPlantation(id_plantation);
					} else { showPlantations(id_farmer); }
					
					closeFPModule(id_farmer,conf);
					
				} else {
					toastr.error('Editing not saved.',{timeOut:15000})
				}
			} 
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
		
	} else {
		toastr.info('Draw a polygone or add a marker.',{timeOut:15000})
	}
}

function closeFPModulePt(id_farmer) {

	map.removeControl(saveBtn);
	map.removeControl(cancelBtn);
	
	geolocation();
}

// Edit - Save Polyline
function closeTLineModule(plant_line_id) {
	
	drawnPolyline.clearLayers();  
	map.removeControl(drawLineControl);
	
	map.removeControl(saveBtn);
	map.removeControl(cancelBtn);
	
	map.removeLayer(polyline);
	
	geolocation();
}

function showPlantations(id_farmer) {

	plantation_points.clearLayers();
	plantation_couche.clearLayers();

	var resurl='include/geolocation.php?elemid=show_plantations&id_farmer='+id_farmer;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;    
			
			var plantations = JSON.parse(leselect); 
			var n = parseInt(JSON.stringify(plantations.length));   
			
			i=0;
			while(i<n){ 
				
				if(mapEdit_update == 1){  
					var editBtn = '<i class="fa fa-pen-square fa-fw pull-right" onclick="editFarmerCPoint(\''+plantations[i].properties.gid_plantation+'\',\''+plantations[i].properties.id_contact+'\');" style="cursor:pointer; color:green;"></i>';
				} else { var editBtn = ""; }

				var bounds = [];
				var geom_json = JSON.stringify(plantations[i].properties.geom_json);   
				var coordx = JSON.stringify(plantations[i].properties.coordx);  
				var coordy = JSON.stringify(plantations[i].properties.coordy);  
				
				if(geom_json!=null){    
					plantation_couche.addData(plantations[i]);	 
					
					map.addLayer(plantation_couche);   
				
					if(coordx=='null'){
						map.fitBounds(plantation_couche.getBounds());   
					}
					
					
					if(id_farmer==0) {
						var long_ = geom_json.coordinates[0][0].length;
						for(var i=0; i<long_; i++){ 
							bounds.push([geom_json.coordinates[0][0][i][1],geom_json.coordinates[0][0][i][0]]);
						}
						
						var polygon = new L.Polygon(bounds);
						polygon.editing.enable();
				
						var polygon2 = turf.polygon([bounds]);   
						seeArea = turf.area(polygon2);
						
						map.addLayer(polygon);
				
						polygon.on('edit', function(e) {
							var json = e.target.toGeoJSON();

							var new_json = {
								"type": "MultiPolygon",
								"coordinates": [
									json.geometry.coordinates
								]
							};
						
							poly = JSON.stringify(new_json);
				
							var polygon3 = turf.polygon(json.geometry.coordinates);  
							seeArea = turf.area(polygon3);   
						});
						
						map.fitBounds(polygon.getBounds());
					}
				} 
				
				if((coordx!=null)&&(coordy!=null)) { 
					if(plantations[i].properties.name_farmer === null){ var name_farmer=""; } else { var name_farmer=plantations[i].properties.name_farmer; }
						if(plantations[i].properties.name_farmergroup === null){ var name_farmergroup=""; } else { var name_farmergroup=plantations[i].properties.name_farmergroup; }
						if(plantations[i].properties.name_town === null){ var name_town=""; } else { var name_town=plantations[i].properties.name_town; }
						if(plantations[i].properties.code_farmer === null){ var code_farmer=""; } else { var code_farmer=plantations[i].properties.code_farmer; }
						if(plantations[i].properties.culture === null){ var culture=""; } else { var culture=plantations[i].properties.culture; }
						if(plantations[i].properties.surface_ha === null){ var surface_ha=""; } else { var surface_ha=plantations[i].properties.surface_ha; }
						if(plantations[i].properties.name_buyer === null){ var name_buyer=""; } else { var name_buyer=plantations[i].properties.name_buyer; }
						if(plantations[i].properties.gid_plantation === null){ var gid_plantation=""; } else { var gid_plantation=plantations[i].properties.gid_plantation; }
						if(plantations[i].properties.gid_town === null){ var gid_town=""; } else { var gid_town=plantations[i].properties.gid_town; }
						if(plantations[i].properties.id_contact === null){ var id_contact=""; } else { var id_contact=plantations[i].properties.id_contact; }
	
						var popupContent = "<div style=\"max-width:400px; max-height: 200px\"><h5 style=\"border-bottom: 1px solid #eee;\">"+blanc
						+"<i class=\"fa fa-check-square fa-fw\" style=\"color:#ed1b2c\"></i><strong style=\"color:#ed1b2c\">&nbsp;&nbsp;Collection Point</strong>"+editBtn+"</h5>"+blanc
							+"<div class=\"icon_desc\" style=\"margin-left:0px;display:block\"><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Farmer name : </strong>"+name_farmer
							+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Farmer group : </strong>"+name_farmergroup
							+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Farmer residence : </strong>"+name_town
							+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Code Farmer : </strong>"+code_farmer
							+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Culture : </strong>"+culture
							+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Area (ha) : </strong>"+surface_ha
							+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Buyer : </strong>"+name_buyer
							+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> GID plantation : </strong>"+gid_plantation
							+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> GID town : </strong>"+gid_town
							+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> ID Contact: </strong>"+id_contact
						+" </span></div></div>";
					
					mark = L.marker([plantations[i].properties.coordx, plantations[i].properties.coordy], {icon: pointIcon,riseOnHover:true})
						.bindPopup(popupContent)
						.addTo(plantation_points);
					map.addLayer(plantation_points);
					
					map.setView([plantations[i].properties.coordx, plantations[i].properties.coordy], 15);
				} 
				
				if((geom_json==null)&&((coordx==null)&&(coordy==null))){
					map.fitWorld().zoomIn();
				}
				
				i += 1;
			}
			
		
			$('#d4_content li a').click(function() {
				$('ul li.on').removeClass('on');
				$(this).closest('li').addClass('on');
			});
			
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


geolocation();
