/*!

=========================================================
* iCoop Dashboard - v1.5.175
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

var poly="", t_line="", fm_coordx="", fm_coordy="", seeArea;
var saveBtn, cancelBtn, expdBtn;
var polygon, col_point, polyline;
var saveTraceBtn, cancelTraceBtn;


// check_session = setInterval(CheckForSession, 5000);


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

/* Popup Reset pasword form */

setInterval(function(){
    refreshSn() // this will run after every 10 min
}, 600000);

function refreshSn() {
	var resurl='session_renew.php';  
    var xhr = getXhr(); 
    xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;  
			
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


if(pwd_reset == 0){ 
	$("#passwordRestModal").modal("show");
}


$(".open-button").on("click", function() {
  $(this).closest('.collapse-group').find('.collapse').collapse('show');
});

$(".close-button").on("click", function() {
  $(this).closest('.collapse-group').find('.collapse').collapse('hide');
});
 
	
function ttWeightCal() {
	var product_quantity = document.getElementById("product_quantity").value;
	var weight_unit = document.getElementById("weight_unit").value;
	
	tt_weight = weight_unit*product_quantity;
	document.getElementById("Ttweight").innerHTML = tt_weight;
	document.getElementById("weight_total").value = tt_weight;
}


function getWeek() {
	var kk = $("#delivery_date").val();  
	document.getElementById("delivery_week").value = moment(kk, "YYYY/MM/DD").week();
	
	if(kk!=""){
		$("#data_5").addClass("hide");
		document.getElementById("delivery_date2").value = "";
		document.getElementById("delivery_week2").value = "";
	}
}

function getWeek2() {
	var kk = $("#delivery_date2").val();  
	document.getElementById("delivery_week2").value = moment(kk, "YYYY/MM/DD").week();
	
	if(kk!=""){
		$("#data_4").addClass("hide");
		document.getElementById("delivery_date").value = "";
		document.getElementById("delivery_week").value = "";
	}
}

function getWeekShowQuote_etd() {
	var kk = $("#req_quote_month_etd").val(); 
	document.getElementById("req_quote_week_etd").value = moment(kk, "YYYY/MM/DD").week();
}


function readURL1(input) {
 if (input.files && input.files[0]) {
    var reader = new FileReader(); reader.onload = function (e) {$('#img1').attr('src', e.target.result);}
    reader.readAsDataURL(input.files[0]);
	$("#upload").prop("disabled", false);
 }
}


function protverif() {
	var order_incoterms_id = document.getElementById('order_incoterms_id').value;
	
	if((order_incoterms_id == 263)||(order_incoterms_id == 264)){
		$("#selport").addClass("hide");
		$("#data_4").removeClass("hide");
		$("#data_5").removeClass("hide");
	} else {
		$("#selport").removeClass("hide");
		$("#data_4").removeClass("hide");
		$("#data_5").addClass("hide");
	}
}


function destory_editor(selector){
    if($(selector)[0])
    {
        var content = $(selector).find('.ql-editor').html();
        $(selector).html(content);

        $(selector).siblings('.ql-toolbar').remove();
        $(selector + " *[class*='ql-']").removeClass (function (index, css) {
           return (css.match (/(^|\s)ql-\S+/g) || []).join(' ');
        });

        $(selector + "[class*='ql-']").removeClass (function (index, css) {
           return (css.match (/(^|\s)ql-\S+/g) || []).join(' ');
        });
    }
    else
    {
        console.error('editor not exists');
    }
}


function notifications(message) {
	var resurl='notification.php?message='+message;  
    var xhr = getXhr(); 
    xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText; 

        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


/*
* Notifications
*/

function clearAllNotifications(username) {
	var resurl='include/notification.php?elemid=clear_all_notifications&username='+username;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;
			var val = leselect.split('##');

			if(val[0]==1){
				toastr.success('Notifications clear',{timeOut:15000})
				uploadCounter();
			} else 
			if(val[0]==0){
				toastr.error('Notifications not clear',{timeOut:15000})
			} else {
				internal_error();
			}
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function uploadCounter() { 

	$("#alertBell").addClass("wiggle");
	var resurl='include/notification.php?elemid=upload_notification_counter';  
    var xhr = getXhr(); 
    xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;  
			var val = leselect.split('##');

			document.getElementById('nt_counter').innerHTML = val[1];
			document.getElementById('nt_list').innerHTML = val[0];   
			$("#alertBell").removeClass("wiggle");
		
			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


/*
* 
* User roles and acces at login
* 
*/

// var freight_right = 0;
// var calculation_right = 0;
// var proposal_right = 0;
// var ord_confirmation_right = 0;
 

// Contacts rights
/* Peoples */
var contppl_create = 0; 
var contppl_update = 0;
var contppl_delete = 0; 

/* Organisations */
var contorg_create = 0; 
var contorg_update = 0;
var contorg_delete = 0; 

/* Peoples Biography */
var contpplbio_read = 0; 
var contpplbio_create = 0; 
var contpplbio_update = 0;
var contpplbio_delete = 0; 

/* Organisations Biography */
var contorgbio_read = 0; 
var contorgbio_create = 0; 
var contorgbio_update = 0;
var contorgbio_delete = 0; 

/* Peoples Links */
var contppllinks_read = 0; 
var contppllinks_create = 0; 
var contppllinks_update = 0;
var contppllinks_delete = 0; 

/* Organisations Links */
var contorglinks_read = 0; 
var contorglinks_create = 0; 
var contorglinks_update = 0;
var contorglinks_delete = 0; 

/* Peoples Demography */
var contppldemog_read = 0; 
var contppldemog_create = 0; 
var contppldemog_update = 0;
var contppldemog_delete = 0;   

/* Peoples Plantation */
var contpplplant_read = 0; 
var contpplplant_create = 0; 
var contpplplant_update = 0;
var contpplplant_delete = 0;

/* System */

// System Ports Costs rights
var sysPortCost_create = 0; 
var sysPortCost_update = 0;
var sysPortCost_delete = 0;

// System Ports Costs Assignments rights
var sysPortCostAssign_read = 0; 
var sysPortCostAssign_create = 0; 
var sysPortCostAssign_update = 0;
var sysPortCostAssign_delete = 0;

// System Ports rights
var sysPort_create = 0; 
var sysPort_update = 0; 
var sysPort_delete = 0; 

// System Products rights
var sysProduct_create = 0; 
var sysProduct_update = 0; 
var sysProduct_delete = 0; 

// System Contracts rights
var sysContract_create = 0; 
var sysContract_update = 0; 
var sysContract_delete = 0; 

// System Cultures rights
var sysCulture_create = 0; 
var sysCulture_update = 0; 
var sysCulture_delete = 0; 

// System Registers rights
var sysReg_create = 0; 
var sysReg_update = 0; 
var sysReg_delete = 0; 

// System Registers Values rights
var sysRegValue_create = 0; 
var sysRegValue_update = 0; 
var sysRegValue_delete = 0; 

// System CRM Freight rights
var sysFreight_create = 0; 
var sysFreight_update = 0; 
var sysFreight_delete = 0;

// System CRM Ship rights
var sysShip_create = 0; 
var sysShip_update = 0; 
var sysShip_delete = 0; 

// System User Management rights
var sysUserManag_create = 0; 
var sysUserManag_update = 0; 
var sysUserManag_delete = 0; 

// System Role Definition rights
var sysRoleDef_create = 0; 
var sysRoleDef_update = 0; 
var sysRoleDef_delete = 0; 

// System Role Definition rights
var sysRoleAssign_create = 0; 
var sysRoleAssign_update = 0; 
var sysRoleAssign_delete = 0; 

	
/* CRM */

// CRM Summary rights
var sum_create = 0;
var sum_update = 0;
var sum_delete = 0;

// CRM 2 Summary rights
var sum_create_2 = 0;
var sum_update_2 = 0;
var sum_delete_2 = 0;

// CRM Schedule rights
var sched_create = 0;
var sched_update = 0;
var sched_delete = 0;

// CRM Order Confirmation rights
var ordcon_read = 0;
var ordcon_create = 0; 
var ordcon_update = 0; 
var ordcon_delete = 0;

// CRM 2 Order Confirmation rights
var ordcon_read_2 = 0;
var ordcon_create_2 = 0; 
var ordcon_update_2 = 0; 
var ordcon_delete_2 = 0;

// CRM Freight rights
var freight_read = 0;
var freight_create = 0; 
var freight_update = 0; 
var freight_delete = 0; 

// CRM 2 Freight rights
var freight_read_2 = 0;
var freight_create_2 = 0; 
var freight_update_2 = 0; 
var freight_delete_2 = 0; 

// CRM Proposal rights
var proposal_read = 0; 
var proposal_create = 0; 
var proposal_update = 0; 
var proposal_delete = 0; 

// CRM 2 Proposal rights
var proposal_read_2 = 0; 
var proposal_create_2 = 0; 
var proposal_update_2 = 0; 
var proposal_delete_2 = 0; 

// CRM Contract rights
var contract_create = 0; 
var contract_update = 0;
var contract_delete = 0;  

// CRM 2 Contract rights
var contract_read_2 = 0; 
var contract_create_2 = 0; 
var contract_update_2 = 0;
var contract_delete_2 = 0;

// CRM Calculation
var calc_read = 0; 
var calc_create = 0; 
var calc_update = 0; 
var calc_delete = 0;

// CRM 2 Calculation
var calc_read_2 = 0; 
var calc_create_2 = 0; 
var calc_update_2 = 0; 
var calc_delete_2 = 0;

// CRM Exporter rights
var exporter_update = 0;

// CRM 2 Exporter rights
var exporter_update_2 = 0;

// CRM Documents rights
var docManager_read = 0; 
var docManager_create = 0; 

// CRM Summary Note rights
var sumNote_update = 0;


/* Logistics */ 
 
 
// Shipping Documents rights
var logShipDoc_read = 0;
var logShipDoc_create = 0;
var logShipDoc_update = 0;

// Ocean Booking rights
var logOceanBooking_read = 0; 
var logOceanBooking_create = 0; 
var logOceanBooking_update = 0; 

// Ocean Booking Container rights
var logOceanContainer_read = 0; 
var logOceanContainer_update = 0; 
var logOceanContainer_create = 0; 

// Ocean Booking add rights
var logOceanBookingAdd_read = 0; 
var logOceanBookingAdd_create = 0; 
var logOceanBookingAdd_update = 0; 

// Onward Booking rights
var logOnwardBooking_read = 0; 
var logOnwardBooking_create = 0; 
var logOnwardBooking_update = 0; 

// Onward Booking Add rights
var logOnwardBookingAdd_read = 0; 
var logOnwardBookingAdd_create = 0; 
var logOnwardBookingAdd_update = 0; 

// Ocean Booking Add Container rights
var logOceanAddContainer_read = 0; 
var logOceanAddContainer_update = 0; 
var logOceanAddContainer_create = 0; 

// Onward Booking Container rights
var logOnwardContainer_read = 0; 
var logOnwardContainer_update = 0; 
var logOnwardContainer_create = 0; 

// Onward Booking Add Container rights
var logOnwardAddContainer_read = 0; 
var logOnwardAddContainer_update = 0; 
var logOnwardAddContainer_create = 0; 


// Booking Documents rights
var bookingDocManager_read = 0; 
var bookingDocManager_create = 0; 

// Logistic Onward Invoice rights
var onwardInvoice_read = 0; 
var onwardInvoice_create = 0; 
var onwardInvoice_update = 0; 
var onwardInvoice_delete = 0; 

// Logistic Onward Add Invoice rights
var onwardInvoiceAdd_read = 0; 
var onwardInvoiceAdd_create = 0; 
var onwardInvoiceAdd_update = 0; 
var onwardInvoiceAdd_delete = 0; 

// Traceability rights
var traceability_read = 0; 
var traceability_create = 0; 
var traceability_update = 0; 
var traceability_delete = 0; 

// Traceability Admin rights
var traceabilityAdmin_read = 0; 
var traceabilityAdmin_create = 0; 
var traceabilityAdmin_update = 0; 
var traceabilityAdmin_delete = 0; 

// Traceability Admin rights
var logLabAnalysis_read = 0; 
var logLabAnalysis_create = 0; 
var logLabAnalysis_update = 0; 
var logLabAnalysis_delete = 0; 


// Contacts rights
var contact_create = 0;
	
// Logistics Move Container rights
var LogContMove = 0;

// Geolocation / Field
var geoField_update = 0;

// Workflow Process
var wfProcess_update = 1;
var wfProcess_delete = 1; 
var wfProcess_create = 1; 
var wfProcess_read = 1; 

// Workflow Trigger
var wfTrigger_update = 1;
var wfTrigger_delete = 1; 
var wfTrigger_create = 1; 
var wfTrigger_read = 1; 

// Workflow Trigger
var wfGroup_update = 1;
var wfGroup_delete = 1; 
var wfGroup_create = 1; 
var wfGroup_read = 1; 

// Project
var project_create = 0; 
var project_update = 0; 
var project_delete = 0; 
var project_read = 0;

// Story Management
var storyManag_create = 0; 
var storyManag_update = 0; 
var storyManag_delete = 0; 

// DASHBOARD 
var dashboard_0 = 0;
var dashboard_apex = 0;
var dashboard_crt_status = 0;
var dashboard_ic_analysis = 0;

//Survey
var survey_create = 0; 
var survey_update = 0; 
var survey_delete = 0; 

// Map edit System Admin
var mapEdit_read = 0; 
var mapEdit_update = 0; 
var mapEdit_create = 0; 
var mapEdit_delete = 0; 



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

// Map 2
var ggl2 = new L.Google('HYBRID');
var googlemap2 = new L.Google('ROADMAP');
var map2 = new L.Map('plantationMap', {layers: [googlemap2], maxZoom: 22, fullscreenControl: {
        pseudoFullscreen: false
    }});
var baseMaps2 = {
	"Google Map": googlemap2,
	"Google Satellite": ggl2
};

// Map env
var ggl_env = new L.Google('HYBRID');
var googlemap_env = new L.Google('ROADMAP');
var map_env = new L.Map('plantEnvMap', {layers: [googlemap_env], maxZoom: 22});
var baseMaps_env = {
	"Google Map": googlemap_env,
	"Google Satellite": ggl_env
};

// Map 3
var ggl3 = new L.Google('HYBRID');
var googlemap3 = new L.Google('ROADMAP');
var map3 = new L.Map('agent_map', {layers: [googlemap3]}); 
var baseMaps3 = {
	"Google Map": googlemap3,
	"Google Satellite": ggl3
};

// db Map
var ggl_db = new L.Google('HYBRID');
var googlemap_db = new L.Google('ROADMAP');
var db_map = new L.Map('db_map', {layers: [googlemap_db]});  
var baseMaps_db = {
	"Google Map": googlemap_db,
	"Google Satellite": ggl_db
};

// Contact Map
var ggl_ct = new L.Google('HYBRID');
var googlemap_ct = new L.Google('ROADMAP');
var ct_map = new L.Map('ct_map', {layers: [googlemap_ct]});  
var baseMaps_ct = {
	"Google Map": googlemap_ct,
	"Google Satellite": ggl_ct
};

// Home Contact Map
var ggl_home_ct = new L.Google('HYBRID');
var googlemap_home_ct = new L.Google('ROADMAP');
var home_ct_map = new L.Map('contact_map', {layers: [googlemap_home_ct]}); 
var baseMaps_home_ct = {
	"Google Map": googlemap_home_ct,
	"Google Satellite": ggl_home_ct
};

// Country Modal Map
var ggl_country_modal = new L.Google('HYBRID');
var googlemap_country_modal = new L.Google('ROADMAP'); 
var country_modal_map = new L.Map('countryModalMap', {layers: [googlemap_country_modal]});  
var baseMaps_country_modal = {
	"Google Map": googlemap_country_modal,
	"Google Satellite": ggl_country_modal
};

// Town Modal Map
var ggl_town_modal = new L.Google('HYBRID');
var googlemap_town_modal = new L.Google('ROADMAP'); 
var town_modal_map = new L.Map('townModalMap', {layers: [googlemap_town_modal]});  
var baseMaps_town_modal = {
	"Google Map": googlemap_town_modal,
	"Google Satellite": ggl_town_modal
};

// Town Coords Modal Map
var ggl_townCoords_modal = new L.Google('HYBRID');
var googlemap_townCoords_modal = new L.Google('ROADMAP'); 
var townCoords_modal_map = new L.Map('townsCoordsMap', {layers: [ggl_townCoords_modal]});  
var baseMaps_townCoords_modal = {
	"Google Map": googlemap_townCoords_modal,
	"Google Satellite": ggl_townCoords_modal
};

// Farmer Visit Map
var ggl_fmrVisit = new L.Google('HYBRID');
var googlemap_fmrVisit = new L.Google('ROADMAP');
var fmrVisit_map = new L.Map('fmrVisit_map', {layers: [googlemap_fmrVisit], maxZoom: 22});    
var baseMaps_fmrVisit = {
	"Google Map": googlemap_fmrVisit,
	"Google Satellite": ggl_fmrVisit
};  


// Offline Map
var ggl_offline = new L.Google('HYBRID');
var googlemap_offline = new L.Google('ROADMAP');
var offline_map = new L.Map('offline_map', {layers: [googlemap_offline], maxZoom: 22, fullscreenControl: {
        pseudoFullscreen: false
    }});    
var baseMaps_offline = {
	"Google Map": googlemap_offline,
	"Google Satellite": ggl_offline
};  


// var org_map = new L.Map('org_map', {layers: [googlemap]});   

var drawnPolygone = L.featureGroup();
var drawnRectangle = L.featureGroup();
var drawnPolyline = L.featureGroup();


var agent_couche = L.geoJson('', {}).addTo(map3);
var loadingPoint_couche = L.geoJson('', {}).addTo(ct_map);
// var position_couche = L.geoJson('', {}).addTo(home_ct_map);

var vessel_couche = L.geoJson(null, {
	pointToLayer: function (feature, latlng) {  
		return mark = L.marker(latlng,{icon: vesselIcon, riseOnHover:false}).addTo(vessel_couche);
	}, onEachFeature : onEachFeature_vessel
}).addTo(db_map);


function onEachFeature_vessel(feature, layer) {
	
	var fiche_img = "<img style='border:1px solid #ccc' width='100%' src='"+layer.feature.properties.photo+"'>";
	var mmsi = layer.feature.properties.mmsi.replace(/\s/g, '');
	
	var popupContent = "<div><h5 style=\"border-bottom: 1px solid #eee; \"><strong>"+layer.feature.properties.shipname+"</strong></h5>"+blanc
	+"<div style=\"margin-left:0px;display:block;\">"+blanc
	+" <span style=\"margin-bottom:10px;\"><strong>MMSI : </strong>"+mmsi+"</span><br>"+blanc
	+" <span><strong>SPEED : </strong>"+layer.feature.properties.speed+"</span><br>"+blanc
	+" <span><strong>DATE TIME : </strong>"+layer.feature.properties.timest+"</span><br>"+blanc
	+" <span><strong>SN : </strong>"+layer.feature.properties.shipment_number+"</span><br>"+blanc
	+"</div>"+fiche_img+"</div>";
	
	if (feature.properties) {
		var sn = layer.feature.properties.shipment_number;
		var con_booking_id = layer.feature.properties.con_booking_id;
		layer.on("click", function (e) { toggle_vessel(mmsi,sn,con_booking_id); }).bindPopup(popupContent);
	}
}


var overlayMaps = { };


// Geolocation 

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


var regions = L.geoJson(null, { onEachFeature : onEachFeature_region });

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


var plantation_project_couche = L.geoJson(null, {
	style: function (feature) {

		return {
			color: 'red',
			weight: 1,
			fill: true,
			fillOpacity: 0.3
		};
	}, onEachFeature : onEachFeature_plantation_project
});


function onEachFeature_plantation_project(feature, layer) {

	if(layer.feature.properties.name_farmer=="null"){ var name_farmer="---"; } else { var name_farmer=layer.feature.properties.name_farmer; }
	if(layer.feature.properties.name_farmergroup=="null"){ var name_farmergroup="---"; } else { var name_farmergroup=layer.feature.properties.name_farmergroup; }
	if(layer.feature.properties.name_town=="null"){ var name_town="---"; } else { var name_town=layer.feature.properties.name_town; }
	if(layer.feature.properties.code_farmer=="null"){ var code_farmer="---"; } else { var code_farmer=layer.feature.properties.code_farmer; }
	if(layer.feature.properties.culture=="null"){ var culture="---"; } else { var culture=layer.feature.properties.culture; }
	if(layer.feature.properties.area=="null"){ var area="---"; } else { var area=layer.feature.properties.area; }
	if(layer.feature.properties.name_buyer=="null"){ var name_buyer="---"; } else { var name_buyer=layer.feature.properties.name_buyer; }

	var popupContent = "<div style=\"max-width:400px; max-height: 200px\"><h5 style=\"border-bottom: 1px solid #eee;\">"+blanc
	+"<i class=\"fa fa-check-square fa-fw\" style=\"color:#ed1b2c\"></i><strong style=\"color:#ed1b2c\">&nbsp;&nbsp;Plantation</strong></h5>"+blanc
		+"<div class=\"icon_desc\" style=\"margin-left:0px;display:block\"><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Farmer name : </strong>"+name_farmer
		+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Farmer_groups : </strong>"+name_farmergroup
		+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Village : </strong>"+name_town
		+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Code Farmer : </strong>"+code_farmer
		+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Culture : </strong>"+culture
		+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Area (ha) : </strong>"+area
		+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Buyer : </strong>"+name_buyer
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


function onEachFeature_traces(feature, layer) {
	
	if(geoField_update == 1){  
		var editBtn = '<i class="fa fa-pen-square fa-fw pull-right" onclick="editTraceLine(\''+layer.feature.properties.plant_line_id+'\');" style="cursor:pointer; color:green;"></i>';
	} else { var editBtn = ""; }
	
	if(layer.feature.properties.plant_line_id === null){ var plant_line_id=""; } else { var plant_line_id=layer.feature.properties.plant_line_id; }
	if(layer.feature.properties.id_plantation === null){ var id_plantation=""; } else { var id_plantation=layer.feature.properties.id_plantation; }
	if(layer.feature.properties.id_region === null){ var id_region="-"; } else { var id_region=layer.feature.properties.id_region; }
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


var plantation_couche = L.geoJson(null, {
	style: function (feature) {

		return {
			color: 'red',
			weight: 1,
			fill: true,
			fillOpacity: 0.3
		};
	}, onEachFeature : onEachFeature_plantation
});

var plantationEnv_couche = L.geoJson(null, {
	style: function (feature) {

		return {
			color: 'red',
			weight: 1,
			fill: true,
			fillOpacity: 0.3
		};
	}, onEachFeature : onEachFeature_plantation
});


function onEachFeature_plantation(feature, layer) {
	
	if(geoField_update == 1){  
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


var plantation_couche_farmer = L.geoJson(null, {
	style: function (feature) {

		return {
			color: 'red',
			weight: 1,
			fill: true,
			fillOpacity: 0.2
		};
	}, onEachFeature : onEachFeature_plantation_farmer
});


function onEachFeature_plantation_farmer(feature, layer) {
	
	if(mapEdit_update == 1){  
		var editBtn = '<i class="fa fa-pen-square fa-fw pull-right" onclick="editFarmerPlant_farmer(\''+layer.feature.properties.gid_plantation+'\',\''+layer.feature.properties.id_contact+'\');" style="cursor:pointer; color:green;"></i>';
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
	}
}

var label_towns = L.geoJson('', {});
var towns_mrker_couche = L.geoJson('', {});

var towns = L.layerGroup([label_towns, towns_mrker_couche]);

var infrastructure_couche = L.geoJson('', {});

var plantation_project_points = L.geoJson('', {});
var plantationEnv_points = L.geoJson('', {});
var plantation_points = L.geoJson('', {});
// var circle_layer = L.geoJson(null, { });


var markers = L.markerClusterGroup({spiderfyOnMaxZoom: true, showCoverageOnHover: true, zoomToBoundsOnClick: true});


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


// Load Administrative Data

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


// function load_towns() {
	// label_towns.clearLayers();
	// towns_mrker_couche.clearLayers();
	
	// var resurl='administrative_data.php?elemid=towns';
    // var xhr = getXhr();
	// xhr.onreadystatechange = function(){
        // if(xhr.readyState == 4 ){
            // leselect = xhr.responseText;   
			// var data = leselect.split('@@');
			
			// i = 0; var x, y;
			// while(data[i] != 'end'){
				
				// var elt=data[i].split('##');
				// var popupContent;
			
				// if((elt[1]!="")&&(elt[2]!="")) {
					// popupContent = elt[0];
					// var mark = L.marker([elt[1], elt[2]],{icon: townIcon,riseOnHover:true}).bindPopup(popupContent).addTo(towns_mrker_couche); 
					
					// var divIcon = L.divIcon({ 
						// className: "labelClass",
						// iconAnchor:[-15,25],
						// html: elt[0]
					// });

					// var mark2 = L.marker([elt[1], elt[2]], {icon: divIcon }).addTo(label_towns); 
					// map.addLayer(towns_mrker_couche);
				// } 
				
				// x= elt[1]; y= elt[2];
				// i += 1;
			// }
        // }
    // };

    // xhr.open("GET",resurl,true);
    // xhr.send(null);
// }


// function load_zones(id_project) {
	// project_zone.clearLayers();
	// var resurl='administrative_data.php?elemid=zones&id_project='+id_project;
    // var xhr = getXhr();
    // xhr.onreadystatechange = function(){
        // if(xhr.readyState == 4 ){
            // leselect = xhr.responseText;  // console.log(leselect);
			
			// var project_zone_layer = JSON.parse(leselect);    
			// project_zone.addData(project_zone_layer);
	
        // }
    // };

    // xhr.open("GET",resurl,true);
    // xhr.send(null)
// }


// function load_regions() {
	// regions.clearLayers();
	// var resurl='administrative_data.php?elemid=regions';
    // var xhr = getXhr();
    // xhr.onreadystatechange = function(){
        // if(xhr.readyState == 4 ){
            // leselect = xhr.responseText; 
			
			// var region_layer = JSON.parse(leselect);    
			// regions.addData(region_layer);	
        // }
    // };

    // xhr.open("GET",resurl,true);
    // xhr.send(null)
// }

// function load_departements() {
	// departements.clearLayers();
	// var resurl='administrative_data.php?elemid=departements';
    // var xhr = getXhr();
    // xhr.onreadystatechange = function(){
        // if(xhr.readyState == 4 ){
            // leselect = xhr.responseText;  
			
			// var departements_layer = JSON.parse(leselect);    
			// departements.addData(departements_layer);	
        // }
    // };

    // xhr.open("GET",resurl,true);
    // xhr.send(null)
// }

// function load_sousprefectures() {
	// sous_prefectures.clearLayers();
	// var resurl='administrative_data.php?elemid=sous_prefectures';
    // var xhr = getXhr();
    // xhr.onreadystatechange = function(){
        // if(xhr.readyState == 4 ){
            // leselect = xhr.responseText; 
			
			// var sous_prefectures_layer = JSON.parse(leselect);    
			// sous_prefectures.addData(sous_prefectures_layer);
        // }
    // };

    // xhr.open("GET",resurl,true);
    // xhr.send(null)
// }


var LeafIcon2 = L.Icon.extend({
    options: {
        iconSize:     [25, 30],
		iconAnchor:   [16, 32],
        popupAnchor:  [0, -33]
    }
});

var pointIcon = new LeafIcon2({iconUrl: 'img/icon_point.png'});


var mobileIcon = L.Icon.extend({
    options: {
        iconSize:     [40, 40],
		iconAnchor:   [16, 32],
        popupAnchor:  [0, -33]
    }
});

var homeMarker = new mobileIcon({iconUrl: 'img/home_point.png'});
var warehouseMarker = new mobileIcon({iconUrl: 'img/warehouse_point.png'});

var photoIcon = L.Icon.extend({
    options: {
        iconSize:     [30, 30],
		iconAnchor:   [16, 32],
        popupAnchor:  [0, -23]
    }
});

var plantPicture = new photoIcon({iconUrl: 'img/plantation_photo.png'});

var homeMap_couche = L.geoJson('', {});
var wareHouse_couche = L.geoJson('', {});
var farmerTown_couche = L.geoJson('', {});
var plantationTown_couche = L.geoJson('', {});
var plantationTown_label = L.geoJson('', {});


$('#myMap').on('show.bs.modal', function(){
  setTimeout(function() {
    map.invalidateSize();
  }, 10);
});


var LeafIcon = L.Icon.extend({
    options: {
        iconSize:     [35, 40],
		iconAnchor:   [16, 32],
        popupAnchor:  [0, -33]
    }
});

var agentIcon = new LeafIcon({iconUrl: 'img/icon_agent.gif'}) ;
var vesselIcon = new LeafIcon({iconUrl: 'img/vessel.png'}) ;

var LeafIcon3 = L.Icon.extend({
    options: {
        iconSize:     [30, 30],
		iconAnchor:   [16, 30],
        popupAnchor:  [0, -30]
    }
});

var LeafIcon4 = L.Icon.extend({
    options: {
        iconSize:     [20, 20],
		iconAnchor:   [16, 30],
        popupAnchor:  [0, -20]
    }
});

var townIcon = new LeafIcon3({iconUrl: 'img/icon_town.png'}) ;
var townNotInIcon = new LeafIcon4({iconUrl: 'img/icon_town_not_in.png'}) ;
var infrastructureIcon = new LeafIcon3({iconUrl: 'img/icon_infrastructure.png'}) ;

L.control.layers(baseMaps_ct, overlayMaps).addTo(ct_map);


/*
* 
* Users 
* Role, Acces and permissions
* 
*/

if(user_roles.length == 0){
	$("#no_data").removeClass("hide");
	document.getElementById('no_data').innerHTML = '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> No data';

} else {
	$("#no_data").addClass("hide");
	var role_permissions = JSON.stringify(user_roles);   
	$.each(JSON.parse(role_permissions), function(idx, obj) {

		// Dashboard In-transit
		if(obj.id_object == 1){ if(obj._read == 1){ $("#btn_db").removeClass("hide"); dashboard(1); dashboard_0=1; } }
		if(obj.id_object == 2){ if(obj._read == 1){ $("#btn_idiscover").removeClass("hide"); } }
		if(obj.id_object == 4){ if(obj._read == 1){ $("#btn_crm").removeClass("hide"); } }
		
		// CRM Summary permissions
		if(obj.id_object == 5){
			if(obj._read == 1){ $("#crm_summary").removeClass("hide"); }
			if(obj._create == 1){ sum_create = 1; 
				$("#startWizard").removeClass("hide"); 
			}
			if(obj._update == 1){ sum_update = 1; }
			if(obj._delete == 1){ sum_delete = 1; }
		}
		
		if(obj.id_object == 6){ if(obj._read == 1){ $("#crm_request").removeClass("hide"); } }
		
		// CRM Schedule permissions
		if(obj.id_object == 7){ 
			if(obj._read == 1){ 
				$("#crm_request_sched_tab").removeClass("hide"); $("#crm_request_sched_ct").removeClass("hide"); 
			}
			if(obj._create == 1){ sched_create = 1; }
			if(obj._update == 1){ sched_update = 1; 
				$("#edit_schedule_btn").removeClass("hide");  
			}
			if(obj._delete == 1){ sched_delete = 1; }
		}
		
		// CRM Exporter permissions
		if(obj.id_object == 8){ 
			if(obj._read == 1){
				$("#crm_request_export_tab").removeClass("hide"); $("#crm_request_export_ct").removeClass("hide");    
				if(obj._update == 1){ exporter_update = 1; }
			}
		}
		
		// CRM Order Confirmation permissions
		if(obj.id_object == 9){ 
			if(obj._read == 1){ ordcon_read = 1; }
			if(obj._create == 1){ ordcon_create = 1; }
			if(obj._update == 1){ ordcon_update = 1; }
			if(obj._delete == 1){ ordcon_delete = 1; }
		}
		
		// CRM Calculation permissions
		if(obj.id_object == 10){
			if(obj._read == 1){ calc_read = 1; }
			if(obj._create == 1){ calc_create = 1; }
			if(obj._update == 1){ calc_update = 1; }
			if(obj._delete == 1){ calc_delete = 1; }
		}
		
		// Logistics permissions
		if(obj.id_object == 11){ if(obj._read == 1){ $("#btn_logistic").removeClass("hide"); } }
		
		// Logistic shipping documents
		if(obj.id_object == 12){ 
			if(obj._read == 1){ logShipDoc_read=1; } 
			if(obj._create == 1){ logShipDoc_create=1; } 
			if(obj._update == 1){ logShipDoc_update=1; } 
		}

		// Logistic Ocean Booking
		if(obj.id_object == 13){ 
			if(obj._read == 1){ logOceanBooking_read=1; } 
			if(obj._create == 1){ logOceanBooking_create=1; } 
			if(obj._update == 1){ logOceanBooking_update=1; } 
		}
		
		// Contacts permissions
		if(obj.id_object == 20){ 
			if(obj._read == 1){ $("#btn_ct").removeClass("hide"); } 
			if(obj._create == 1){ contact_create = 1; }
		}
		
		// Contacts Peoples permissions
		if(obj.id_object == 21){ 
			if(obj._read == 1){ $("#btn_ctp").removeClass("hide"); } 
			if(obj._create == 1){ contppl_create = 1; }
			if(obj._update == 1){ contppl_update = 1; }
			if(obj._delete == 1){ contppl_delete = 1; } 
		}
		
		// Contacts Organisations permissions
		if(obj.id_object == 22){ 
			if(obj._read == 1){ $("#btn_cto").removeClass("hide"); } 
			if(obj._create == 1){ contorg_create = 1; }
			if(obj._update == 1){ contorg_update = 1; }
			if(obj._delete == 1){ contorg_delete = 1; }
		}
		
		// Contacts Peoples Biography permissions
		if(obj.id_object == 23){ 
			if(obj._read == 1){ contpplbio_read = 1; } 
			if(obj._create == 1){ contpplbio_create = 1; }
			if(obj._update == 1){ contpplbio_update = 1; }
			if(obj._delete == 1){ contpplbio_delete = 1; }
		}
		
		// Contacts Peoples Biography permissions
		if(obj.id_object == 24){ 
			if(obj._read == 1){ contppllinks_read = 1; } 
			if(obj._create == 1){ contppllinks_create = 1; }
			if(obj._update == 1){ contppllinks_update = 1; }
			if(obj._delete == 1){ contppllinks_delete = 1; }
		}
		
		// Contacts Organisations Links permissions
		if(obj.id_object == 25){ 
			if(obj._read == 1){ contorglinks_read = 1; } 
			if(obj._create == 1){ contorglinks_create = 1; }
			if(obj._update == 1){ contorglinks_update = 1; }
			if(obj._delete == 1){ contorglinks_delete = 1; }
		}

		// Contacts Peoples Demography permissions
		if(obj.id_object == 26){ 
			if(obj._read == 1){ contppldemog_read = 1; } 
			if(obj._create == 1){ contppldemog_create = 1; }
			if(obj._update == 1){ contppldemog_update = 1; }
			if(obj._delete == 1){ contppldemog_delete = 1; }
		}
		
		// Contacts Peoples Plantation permissions
		if(obj.id_object == 27){ 
			if(obj._read == 1){ contpplplant_read = 1; } 
			if(obj._create == 1){ contpplplant_create = 1; }
			if(obj._update == 1){ contpplplant_update = 1; }
			if(obj._delete == 1){ contpplplant_delete = 1; }
		}
		
		// Contacts Organisations Biography permissions
		if(obj.id_object == 28){ 
			if(obj._read == 1){ contorgbio_read = 1; } 
			if(obj._create == 1){ contorgbio_create = 1; }
			if(obj._update == 1){ contorgbio_update = 1; }
			if(obj._delete == 1){ contorgbio_delete = 1; }
		}
		
		if(obj.id_object == 30){ 
			if(obj._read == 1){ $("#btn_projects").removeClass("hide"); project_read = 1; } 
			if(obj._create == 1){ project_create = 1; }
			if(obj._update == 1){ project_update = 1; }
			if(obj._delete == 1){ project_delete = 1; }
		}
		
		if(obj.id_object == 31){ if(obj._read == 1){ $("#btn_tasks").removeClass("hide"); } }
		if(obj.id_object == 32){ if(obj._read == 1){ $("#btn_notes").removeClass("hide"); } }
		if(obj.id_object == 33){ if(obj._read == 1){ $("#btn_email").removeClass("hide"); } }
		if(obj.id_object == 34){ if(obj._read == 1){ $("#btn_cal").removeClass("hide"); } }
		
		
		if(obj.id_object == 40){ 
			if(obj._read == 1){ $("#btn_story").removeClass("hide"); } 
			if(obj._create == 1){ storyManag_create = 1; }
			if(obj._update == 1){ storyManag_update = 1; }
			if(obj._delete == 1){ storyManag_delete = 1; }
		}
		if(obj.id_object == 48){ if(obj._read == 1){ $("#btn_pref").removeClass("hide"); } }
		
		
		if(obj.id_object == 50){ 
			if(obj._update == 1){ geoField_update = 1; } 
		}
		if(obj.id_object == 54){ if(obj._read == 1){ $("#btn_syst").removeClass("hide"); } }
		if(obj.id_object == 55){ if(obj._read == 1){ $("#btn_syst_cunt").removeClass("hide"); } }
		if(obj.id_object == 56){ if(obj._read == 1){ $("#btn_syst_town").removeClass("hide"); } }
		
		// System Role Assignments permissions
		if(obj.id_object == 66){ 
			if(obj._read == 1){ $("#btn_role_ass").removeClass("hide"); } 
			if(obj._create == 1){ sysRoleAssign_create = 1; }
			if(obj._update == 1){ sysRoleAssign_update = 1; }
			if(obj._delete == 1){ sysRoleAssign_delete = 1; }
		}
		
		// System Role permissions
		if(obj.id_object == 68){ 
			if(obj._read == 1){ $("#btn_role_perm").removeClass("hide"); } 
			if(obj._update == 1){ $(".check_RolePerm").prop("disabled", false); } else { $(".check_RolePerm").prop("disabled", true); }
		}
		
		// System User Management permissions
		if(obj.id_object == 69){ 
			if(obj._read == 1){ $("#btn_user_manag").removeClass("hide"); } 
			if(obj._create == 1){ sysUserManag_create = 1; }
			if(obj._update == 1){ sysUserManag_update = 1; }
			if(obj._delete == 1){ sysUserManag_delete = 1; }
		}
		
		if(obj.id_object == 90){ if(obj._read == 1){ $("#btn_amgt").removeClass("hide"); } }
		
		// CRM Summary Note
		if(obj.id_object == 91){ if(obj._update == 1){ sumNote_update = 1; } }
		
		// System CRM permissions
		if(obj.id_object == 92){ if(obj._read == 1){ $("#btn_syst_crm").removeClass("hide"); } }
		
		// System Ports Costs permissions
		if(obj.id_object == 93){ 
			if(obj._read == 1){ $("#btn_crm_pcosts").removeClass("hide"); $("#btn_crm_pcosts_tbl").removeClass("hide"); } 
			if(obj._create == 1){ sysPortCost_create = 1; }
			if(obj._update == 1){ sysPortCost_update = 1; }
			if(obj._delete == 1){ sysPortCost_delete = 1; }
		}
		
		// System Ports permissions
		if(obj.id_object == 94){ 
			if(obj._read == 1){ $("#btn_crm_port").removeClass("hide"); } 
			if(obj._create == 1){ sysPort_create = 1; }
			if(obj._update == 1){ sysPort_update = 1; }
			if(obj._delete == 1){ sysPort_delete = 1; }
		}
		
		// System Users permissions
		if(obj.id_object == 95){ if(obj._read == 1){ $("#btn_user").removeClass("hide"); } }
		
		// System Products permissions
		if(obj.id_object == 96){ 
			if(obj._read == 1){ $("#btn_crm_pdct").removeClass("hide"); } 
			if(obj._create == 1){ sysProduct_create = 1; }
			if(obj._update == 1){ sysProduct_update = 1; }
			if(obj._delete == 1){ sysProduct_delete = 1; }
		}
		
		// System Contracts permissions
		if(obj.id_object == 97){ 
			if(obj._read == 1){ $("#btn_crm_contract").removeClass("hide"); } 
			if(obj._create == 1){ sysContract_create = 1; }
			if(obj._update == 1){ sysContract_update = 1; }
			if(obj._delete == 1){ sysContract_delete = 1; }
		}
		
		if(obj.id_object == 98){ if(obj._read == 1){ $("#btn_crm_relship").removeClass("hide"); } }
		
		// System Cultures permissions
		if(obj.id_object == 99){ 
			if(obj._read == 1){ $("#btn_crm_cult").removeClass("hide"); } 
			if(obj._create == 1){ sysCulture_create = 1; }
			if(obj._update == 1){ sysCulture_update = 1; }
			if(obj._delete == 1){ sysCulture_delete = 1; }
		}
		
		// System Registers permissions
		if(obj.id_object == 100){ 
			if(obj._read == 1){ $("#btn_register").removeClass("hide"); } 
			if(obj._create == 1){ sysReg_create = 1; }
			if(obj._update == 1){ sysReg_update = 1; }
			if(obj._delete == 1){ sysReg_delete = 1; }
		}
		
		// System Registers Values permissions
		if(obj.id_object == 101){ 
			if(obj._read == 1){ $("#btn_syst_values").removeClass("hide"); } 
			if(obj._create == 1){ sysRegValue_create = 1; }
			if(obj._update == 1){ sysRegValue_update = 1; }
			if(obj._delete == 1){ sysRegValue_delete = 1; }
		}
		
		// System Role Definition permissions
		if(obj.id_object == 102){ 
			if(obj._read == 1){ $("#btn_role_def").removeClass("hide"); } 
			if(obj._create == 1){ sysRoleDef_create = 1; }
			if(obj._update == 1){ sysRoleDef_update = 1; }
			if(obj._delete == 1){ sysRoleDef_delete = 1; }
		}
		
		// CRM Freight permissions
		if(obj.id_object == 103){ 
			if(obj._read == 1){ freight_read = 1; }
			if(obj._create == 1){ freight_create = 1; }
			if(obj._update == 1){ freight_update = 1; }
			if(obj._delete == 1){ freight_delete = 1; }
		}
		
		// System CRM Freight permissions
		if(obj.id_object == 115){ 
			if(obj._read == 1){ $("#btn_crm_freights").removeClass("hide"); } 
			if(obj._create == 1){ sysFreight_create = 1; }
			if(obj._update == 1){ sysFreight_update = 1; }
			if(obj._delete == 1){ sysFreight_delete = 1; }
		}
		
		// CRM Proposal permissions
		if(obj.id_object == 117){ 
			if(obj._read == 1){ proposal_read = 1; }
			if(obj._create == 1){ proposal_create = 1; }
			if(obj._update == 1){ proposal_update = 1; }
			if(obj._delete == 1){ proposal_delete = 1; }
		}
		
		// Logistic Ocean Booking Add ermissions
		if(obj.id_object == 118){ 
			if(obj._read == 1){ logOceanBookingAdd_read = 1; }
			if(obj._create == 1){ logOceanBookingAdd_create = 1; }
			if(obj._update == 1){ logOceanBookingAdd_update = 1; }
		}
		
		// Logistic Onward Booking permissions
		if(obj.id_object == 119){ 
			if(obj._read == 1){ logOnwardBooking_read = 1; }
			if(obj._create == 1){ logOnwardBooking_create = 1; }
			if(obj._update == 1){ logOnwardBooking_update = 1; }
		}
		
		// Logistic Onward Booking permissions
		if(obj.id_object == 120){ 
			if(obj._read == 1){ logOnwardBookingAdd_read = 1; }
			if(obj._create == 1){ logOnwardBookingAdd_create = 1; }
			if(obj._update == 1){ logOnwardBookingAdd_update = 1; }
		}
		
		// Logistic Container permissions
		if(obj.id_object == 121){ 
			if(obj._read == 1){ logOceanContainer_read = 1; } 
			if(obj._update == 1){ logOceanContainer_update = 1; } 
			if(obj._create == 1){ logOceanContainer_create = 1; } 
		}
	
		// Logistic Add Container permissions
		if(obj.id_object == 122){ 
			if(obj._read == 1){ logOceanAddContainer_read = 1; } 
			if(obj._update == 1){ logOceanAddContainer_update = 1; } 
			if(obj._create == 1){ logOceanAddContainer_create = 1; } 
		} 
		
		// Logistic Add Container permissions
		if(obj.id_object == 123){ 
			if(obj._read == 1){ logOnwardContainer_read = 1; } 
			if(obj._update == 1){ logOnwardContainer_update = 1; } 
			if(obj._create == 1){ logOnwardContainer_create = 1; } 
		} 
		
		// Logistic Add Container permissions
		if(obj.id_object == 124){ 
			if(obj._read == 1){ logOnwardAddContainer_read = 1; } 
			if(obj._update == 1){ logOnwardAddContainer_update = 1; } 
			if(obj._create == 1){ logOnwardAddContainer_create = 1; } 
		} 
		
		// Dashboard Analytics permissions
		if(obj.id_object == 126){ if(obj._read == 1){  $("#btn_db_3").removeClass("hide"); } }
		
		// Dashboard Field
		if(obj.id_object == 127){ if(obj._read == 1){ $("#btn_db").removeClass("hide"); work_progress(0); dashboard_0=1; } }
		
		// Dashboard Gantt permissions
		if(obj.id_object == 128){ if(obj._read == 1){  $("#btn_db_2").removeClass("hide"); } }
		
		// CRM Contract permissions
		if(obj.id_object == 142){ 
			if(obj._read == 1){ 
				$('#crm_contract_tab').removeClass("hide"); $('#crm_contract').removeClass("hide"); 
			} 
			if(obj._create == 1){ contract_create = 1; }
			if(obj._update == 1){ contract_update = 1; }
			if(obj._delete == 1){ contract_delete = 1; }
		}
		
		// CRM Doc Manager permissions
		if(obj.id_object == 143){ 
			if(obj._read == 1){ docManager_read=1; } 
			if(obj._create == 1){ docManager_create=1; } 
		}
		
		// System CRM Port Cost Assignments permissions
		if(obj.id_object == 148){ 
			if(obj._read == 1){ sysPortCostAssign_read=1; } 
			if(obj._update == 1){ sysPortCostAssign_update=1; } 
		}
		
		// CRM Bookingin Doc permissions
		if(obj.id_object == 149){ 
			if(obj._read == 1){ bookingDocManager_read=1; } 
			if(obj._create == 1){ bookingDocManager_create=1; } 
		}
		
		// System CRM Ship permissions
		if(obj.id_object == 178){ 
			if(obj._read == 1){ $("#btn_crm_ship").removeClass("hide"); } 
			if(obj._create == 1){ sysShip_create = 1; }
			if(obj._update == 1){ sysShip_update = 1; }
			if(obj._delete == 1){ sysShip_delete = 1; }
		}
		
		// Logistic Onward Invoice permissions
		if(obj.id_object == 181){ 
			if(obj._read == 1){ onwardInvoice_read = 1; } 
			if(obj._create == 1){ onwardInvoice_create = 1; }
			if(obj._update == 1){ onwardInvoice_update = 1; }
			if(obj._delete == 1){ onwardInvoice_delete = 1; }
		}
		
		// Logistic Onward Add Invoice permissions
		if(obj.id_object == 182){ 
			if(obj._read == 1){ onwardInvoiceAdd_read = 1; } 
			if(obj._create == 1){ onwardInvoiceAdd_create = 1; }
			if(obj._update == 1){ onwardInvoiceAdd_update = 1; }
			if(obj._delete == 1){ onwardInvoiceAdd_delete = 1; }
		}
	
		// CRM 2
		if(obj.id_object == 187){ if(obj._read == 1){ $("#btn_crm2").removeClass("hide"); } }

		// CRM 2 Schedule permissions
		if(obj.id_object == 188){ 
			if(obj._read == 1){ 
				$("#crm_request_sched_tab2").removeClass("hide"); $("#crm_request_sched_ct2").removeClass("hide");
			}
			// if(obj._create == 1){ sched_create = 1; }
			if(obj._update == 1){ sched_update = 1; 
				$("#edit_schedule_btn2").removeClass("hide"); 
			}
			// if(obj._delete == 1){ sched_delete = 1; }
		}
		
		// CRM 2 Exporter permissions
		if(obj.id_object == 189){ 
			if(obj._read == 1){  
				$("#crm_request_export_tab2").removeClass("hide"); $("#crm_request_export_ct2").removeClass("hide");   
				if(obj._update == 1){ exporter_update_2 = 1; }
			}
		}
		
		// CRM 2 Freight permissions
		if(obj.id_object == 190){ 
			if(obj._read == 1){ freight_read_2 = 1; }
			if(obj._create == 1){ freight_create_2 = 1; }
			if(obj._update == 1){ freight_update_2 = 1; }
			if(obj._delete == 1){ freight_delete_2 = 1; }
		}
		
		// CRM 2 Calculation permissions
		if(obj.id_object == 191){
			if(obj._read == 1){ calc_read_2 = 1; }
			if(obj._create == 1){ calc_create_2 = 1; }
			if(obj._update == 1){ calc_update_2 = 1; }
			if(obj._delete == 1){ calc_delete_2 = 1; }
		}
		
		// CRM 2 Proposal permissions
		if(obj.id_object == 192){ 
			if(obj._read == 1){ proposal_read_2 = 1; }
			if(obj._create == 1){ proposal_create_2 = 1; }
			if(obj._update == 1){ proposal_update_2 = 1; }
			if(obj._delete == 1){ proposal_delete_2 = 1; }
		}
		
		// CRM 2 Summary permissions
		if(obj.id_object == 194){
			if(obj._read == 1){ $("#crm_summary2").removeClass("hide"); }
			if(obj._create == 1){ //sum_create = 1; 
				$("#startWizard2").removeClass("hide"); 
			}
			if(obj._update == 1){ sum_update_2 = 1; }
			if(obj._delete == 1){ sum_delete_2 = 1; }
		}
		
		// CRM 2 Summary Note permissions  
		if(obj.id_object == 195){ if(obj._read == 1){ $("#crm_notes_tab2").removeClass("hide"); $("#crm_notes2").removeClass("hide"); } }
		
		// CRM Contract permissions
		if(obj.id_object == 196){ 
			if(obj._read == 1){ contract_read_2 = 1;
				$('#crm_contract_tab2').removeClass("hide"); $('#crm_contract2').removeClass("hide");
			} 
			if(obj._create == 1){ contract_create_2 = 1; }
			if(obj._update == 1){ contract_update_2 = 1; }
			if(obj._delete == 1){ contract_delete_2 = 1; }
		}
		
		if(obj.id_object == 199){ if(obj._read == 1){ $("#crm_request2").removeClass("hide"); } }
		
		// CRM 2 Order Confirmation permissions  
		if(obj.id_object == 9){ 
			if(obj._read == 1){ ordcon_read_2 = 1; }
			if(obj._create == 1){ ordcon_create_2 = 1; }
			if(obj._update == 1){ ordcon_update_2 = 1; }
			if(obj._delete == 1){ ordcon_delete_2 = 1; }
		}

		// CRM 2 Customer Request permissions  
		if(obj.id_object == 201){ if(obj._read == 1){ $("#crm_customer_request_tab2").removeClass("hide"); $("#crm_customer_request2").removeClass("hide"); } }
		
		// Traceability permissions  
		if(obj.id_object == 212){ 
			if(obj._read == 1){ traceability_read = 1; }
			if(obj._create == 1){ traceability_create = 1; }
			if(obj._update == 1){ traceability_update = 1; }
			if(obj._delete == 1){ traceability_delete = 1; }
		}
		
		// Traceability Admin permissions  
		if(obj.id_object == 215){ 
			if(obj._read == 1){ traceabilityAdmin_read = 1; }
			if(obj._create == 1){ traceabilityAdmin_create = 1; }
			if(obj._update == 1){ traceabilityAdmin_update = 1; }
			if(obj._delete == 1){ traceabilityAdmin_delete = 1; }
		}
		
		// Lab Analysis permissions  
		if(obj.id_object == 218){ 
			if(obj._read == 1){ logLabAnalysis_read = 1; }
			if(obj._create == 1){logLabAnalysis_create = 1; }
			if(obj._update == 1){ logLabAnalysis_update = 1; }
			if(obj._delete == 1){ logLabAnalysis_delete = 1; }
		}
		
		// Dashboard Accounts Receivable permissions
		if(obj.id_object == 220){ if(obj._read == 1){  $("#btn_db_4").removeClass("hide"); } }
		
		// Logistics Move Container permissions  
		if(obj.id_object == 221){
			if(obj._update == 1){ LogContMove = 1; }
		}  
		
		// Workflow
		if(obj.id_object == 224){ if(obj._read == 1){ $("#btn_workflow").removeClass("hide"); } }
		
		// Projects
		if(obj.id_object == 225){ if(obj._read == 1){ $("#btn_project_mgnt").removeClass("hide"); } }
		
		// TimeLine
		if(obj.id_object == 226){ if(obj._read == 1){ //$("#btn_time").removeClass("hide"); 
		} }
		
		// Edit Map
		if(obj.id_object == 230){ 
			if(obj._read == 1){ mapEdit_read=1; } 
			if(obj._create == 1){ mapEdit_create = 1; }
			if(obj._update == 1){ mapEdit_update = 1; }
			if(obj._delete == 1){ mapEdit_delete = 1; }
		}
		
		// Fields-Plantations
		if(obj.id_object == 232){ if(obj._read == 1){ dashboard_fields = 1; $("#btn_fields_plantation").removeClass("hide"); } }
		
		// Apex
		if(obj.id_object == 242){ if(obj._read == 1){ dashboard_apex = 1; $("#btn_apex").removeClass("hide"); } }
		
		// Certification Status
		if(obj.id_object == 243){ if(obj._read == 1){ dashboard_crt_status = 1; $("#btn_crt_status").removeClass("hide"); } }
		
		// iC.Analysis
		if(obj.id_object == 245){ if(obj._read == 1){ dashboard_ic_analysis = 1; $("#btn_syst_analysis").removeClass("hide"); } }
	
		
		// Survey
		if(obj.id_object == 246){ 
			if(obj._read == 1){ 
				$("#btn_survey").removeClass("hide"); 
				$("#btn_suv_camp").removeClass("hide"); 
				$("#btn_suv_camp_result").removeClass("hide"); 
			} 
			
			if(obj._create == 1){ survey_create = 1; }
			if(obj._update == 1){ survey_update = 1; }
			if(obj._delete == 1){ survey_delete = 1; }
		}
	});
}


if(dashboard_0 == 0){
	noDashboard();
}


function showLoadingPoint(x,y) {
	loadingPoint_couche.clearLayers();
	setTimeout(function() {
		ct_map.invalidateSize();
	}, 1000);

	ct_map.addLayer(googlemap);
	var mark = L.marker([x, y], {icon: agentIcon,riseOnHover:true}).addTo(loadingPoint_couche);
	ct_map.addLayer(loadingPoint_couche);
	
	ct_map.setView([x, y], 9); 
	
	$("#modalLoadingPoint").modal("show");
}


function showLoadingImage(img_link) {  
	document.getElementById("loadingImgContent").innerHTML = '<img src="'+img_link+'" class="img-responsive" />';
	$("#modalLoadingImages").modal("show");
}


/*
* No DASHBOARD 
*/

function noDashboard() { 
	$("#no_data").removeClass("hide");
	document.getElementById('pageTitle').innerHTML = '';
	document.getElementById("no_data").innerHTML = '<div class="text-center col-sm-12">'
		+'<i class="fa fa-home" style="font-size:20em; color:#aaa;" aria-hidden="true"></i><br/>'
		+'<h3>Welcome to iCoop</h3>'
	+'</div>';
}


/*
* AVATAR 
*/

function default_avatar() {
	$('#img1').attr('src', 'img/user.jpg');
	$("#upload").prop("disabled", true);
}


function upload_avatar() {
	var spinner = '<div class="sk-spinner sk-spinner-double-bounce div_ov_spanner">'+
		'<div class="sk-double-bounce1"></div>'+
		'<div class="sk-double-bounce2"></div>'+
	'</div>';
	
	var progressbar = '<div id="progress-div"><div id="progress-bar"></div></div>';

	$("#modalAvatarContent").append("<div class='div_overlay'>"+spinner+progressbar+"</div>");
	
	$('#avatar_upload').one('submit', function(e) {

		e.preventDefault();
		$('#progress-div').show();
		$(this).ajaxSubmit({  
			beforeSubmit: function() {
				$("#progress-bar").width('0%');
			},
			uploadProgress: function (event, position, total, percentComplete){	
				$("#progress-bar").width(percentComplete + '%');
				$("#progress-bar").html('<div id="progress-status">' + percentComplete +' %</div>')
			},
			success:function (response){
				var val = response.split('##');
				if(val[0] == 1){
					$(".div_overlay").remove();
					$('#progress-div').hide();
					$("#modalAvatar").modal("hide");
					document.getElementById("avatar_prev").src="img/avatar/"+val[1]+".jpg";
					// $('#avatar_prev').attr('src', 'img/avatar/'+val[1]+'.jpg'); 
	
				} else { 
					toastr.error(response,{timeOut:15000}) 
					$(".div_overlay").remove();
					$('#progress-div').hide();
					default_avatar();
				}
				
				$("#upload").prop("disabled", true);
			},
			resetForm: true 
		}); 
		return false; 
	});
}

/*
* CRM - Summary
* Edit Customer Ref-number
*/

// Show input
function editCusRefNumb(id_ord_order) {
	$('#cusRefNumbShow').addClass("hide");
	$('#cusRefNumbInput').removeClass("hide");
	
	document.getElementById("cusRefNumbManagBtn").innerHTML = '<a href="#" class="btn btn-white btn-sm" onclick="saveEditCusRefNumb('+id_ord_order+');"><i class="fa fa-check" style="color:green;"></i></a>'+
		' <a href="#" class="btn btn-white btn-sm" onclick="cancelEditCusRefNumb('+id_ord_order+');"><i class="fa fa-times" style="color:red;"></i></a>';
}

// Save Edited
function saveEditCusRefNumb(id_ord_order) {
	var customer_reference_nr = document.getElementById("edit_customer_reference_nr").value;
	
	var resurl='listeslies.php?elemid=save_edited_cus_ref_number&id_ord_order='+id_ord_order+'&customer_reference_nr='+customer_reference_nr;
    var xhr = getXhr();
    xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;
            var val=leselect.split('##');

			if(val[0]==1){
				toastr.success('Customer Ref-Number saved successfully.',{timeOut:15000})
				document.getElementById("cusRefNumbShow").innerHTML = val[1];
				cancelEditCusRefNumb(id_ord_order);
				
			} else 
			if(val[0]==0){
				toastr.error('Customer Ref-Number not saved.',{timeOut:15000})
				
			} else {
				internal_error();
			}

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null)
}


// Cancel Edition
function cancelEditCusRefNumb(id_ord_order) {
	$('#cusRefNumbShow').removeClass("hide");
	$('#cusRefNumbInput').addClass("hide");
	
	document.getElementById("cusRefNumbManagBtn").innerHTML = '<a href="#" onclick="editCusRefNumb('+id_ord_order+');" class="btn btn-white btn-sm"><i class="fa fa-edit"></i></a>';
}


/*
* CRM - Summary
* Edit Customer Notes
*/

function showEditCusNotes() {
	$("#cusNotesManagBtn").removeClass("hide");

	document.getElementById('sumCusRequestToggler').innerHTML = '<button class="btn btn-danger pull-right" style="margin-top:10px; margin-right:20px;" onclick="closeEditCusNotes();" type="button"><i class="fa fa-ban"></i></button>'+
		' &nbsp;<button class="btn btn-success pull-right" style="margin-top:10px; margin-right:10px; " onclick="save_customer_req_notes();" type="button"><i class="fa fa-save"></i></button>';
}
	
	
function closeEditCusNotes() {
	$("#cusNotesManagBtn").addClass("hide");
	
	document.getElementById('sumCusRequestToggler').innerHTML = '<button class="btn btn-success pull-right" onclick="showEditCusNotes();" style="margin-top:10px; margin-right:20px;" type="button">'
		+'<i class="fa fa-edit"></i></button>';
}

function save_customer_req_notes(){
	closeEditCusNotes();
}

// Show input
function editCusNotes(id_ord_order) {
	$('#cusNotesShow').addClass("hide");
	$('#cusNotesInput').removeClass("hide");
	
	document.getElementById("cusNotesManagBtn").innerHTML = '<a href="#" onclick="saveEditCusNotes('+id_ord_order+');" class="btn btn-white btn-sm"><i class="fa fa-check" style="color:green;"></i></a>'+
		' <a href="#"onclick="cancelEditCusNotes('+id_ord_order+');" class="btn btn-white btn-sm"><i class="fa fa-times" style="cursor:pointer; color:red;" onclick="cancelEditCusNotes('+id_ord_order+');"></i></a>';
}

// Save Edited
function saveEditCusNotes(id_ord_order) {
	var notes_customer = document.getElementById("edit_notes_customer").value;
	
	var resurl='listeslies.php?elemid=save_edited_cus_notes&id_ord_order='+id_ord_order+'&notes_customer='+notes_customer;
    var xhr = getXhr();
    xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;
            var val=leselect.split('##');

			if(val[0]==1){
				toastr.success('Customer Note saved successfully.',{timeOut:15000})
				document.getElementById("cusNotesShow").innerHTML = val[1];
				cancelEditCusNotes(id_ord_order);
				
			} else 
			if(val[0]==0){
				toastr.error('Customer Note not saved.',{timeOut:15000})
				
			} else {
				internal_error();
			}

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null)
}


// Cancel Edition
function cancelEditCusNotes(id_ord_order) {
	$('#cusNotesShow').removeClass("hide");
	$('#cusNotesInput').addClass("hide");
	
	document.getElementById("cusNotesManagBtn").innerHTML = '<a href="#" onclick="editCusNotes('+id_ord_order+');" class="btn btn-white btn-sm"><i class="fa fa-edit"></i></a>';
}

/*
* Carrier
* Management
*/

function newCarrier() {
	$("#modalCreateCarrier").modal("show");
}

function saveCarrier() {
	var lastname = document.getElementById("carr_lastname").value;
	var fname = document.getElementById("carr_fname").value;
	var contact_code = document.getElementById("carr_code").value;
	var town_name = document.getElementById("carr_townname").value;
	
	if(lastname == ""){
		toastr.info('Enter carrier name.',{timeOut:15000})
	} else
	if(fname == ""){
		toastr.info('Enter designation.',{timeOut:15000})
	} else
	if(contact_code == ""){
		toastr.info('Enter carrier code.',{timeOut:15000})
	} else
	if(town_name == ""){
		toastr.info('Enter the place.',{timeOut:15000})
	} else {
		
		var resurl='listeslies.php?elemid=save_new_carrier&lastname='+lastname+'&fname='+fname+'contact_code='+contact_code+'&town_name='+town_name;
		var xhr = getXhr();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;
				var val=leselect.split('##');

				if(val[0]==1){
					toastr.success('Carrier saved successfully.',{timeOut:15000})
					document.getElementById("carrier_name_list").innerHTML = val[1];
					document.getElementById("carrierForm").reset();
					$("#modalCreateCarrier").modal("hide");
					
				} else 
				if(val[0]==0){
					toastr.error('Carrier not saved.',{timeOut:15000})
					
				} else {
					internal_error();
				}

				leselect = xhr.responseText;
			} 
		};

		xhr.open("GET",resurl,true);
		xhr.send(null)
	}
}

/*
* Forwarder
* Management
*/

function newForwarder() {
	$("#modalCreateForwarder").modal("show");
}

function saveForwarder() {
	var lastname = document.getElementById("forw_lastname").value;
	var fname = document.getElementById("forw_fname").value;
	var contact_code = document.getElementById("forw_code").value;
	var town_name = document.getElementById("forw_townname").value;
	
	if(lastname == ""){
		toastr.info('Enter forwarder name.',{timeOut:15000})
	} else
	if(fname == ""){
		toastr.info('Enter designation.',{timeOut:15000})
	} else
	if(contact_code == ""){
		toastr.info('Enter forwarder code.',{timeOut:15000})
	} else
	if(town_name == ""){
		toastr.info('Enter the place.',{timeOut:15000})
	} else {
		
		var resurl='listeslies.php?elemid=save_new_forwarder&lastname='+lastname+'&fname='+fname+'&contact_code='+contact_code+'&town_name='+town_name;
		var xhr = getXhr();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;
				var val=leselect.split('##');

				if(val[0]==1){
					toastr.success('Forwarder saved successfully.',{timeOut:15000})
					document.getElementById("forwarder_name_list").innerHTML = val[1];
					document.getElementById("forwarderForm").reset();
					$("#modalCreateForwarder").modal("hide");
					
				} else 
				if(val[0]==0){
					toastr.error('Forwarder not saved.',{timeOut:15000})
					
				} else {
					internal_error();
				}

				leselect = xhr.responseText;
			} 
		};

		xhr.open("GET",resurl,true);
		xhr.send(null)
	}
}

/*
* CRM - Summary
* Edit Pipeline/Status
*/

function editSumPipeline(id_ord_order) {
	document.getElementById("sumPipeline_id_ord_order").value = id_ord_order;
	$("#modalEditSumPipeline").modal("show");
}

function saveEditedSumPipeline() {
	
	var id_ord_order = document.getElementById("sumPipeline_id_ord_order").value;
	var pipeline_id = document.getElementById("sumStatus_pipeline_id").value;
	
	var resurl='listeslies.php?elemid=save_edited_sum_pipeline&id_ord_order='+id_ord_order+'&pipeline_id='+pipeline_id;
    var xhr = getXhr();
    xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;
            var val=leselect.split('##');

			if(val[0]==1){
				toastr.success('Pipeline saved successfully.',{timeOut:15000})
				document.getElementById("sumPipelineName").innerHTML = val[1];
				document.getElementById("sumPipelineForm").reset();
				$("#modalEditSumPipeline").modal("hide");
				crm_manag(0,0);
				
			} else 
			if(val[0]==0){
				toastr.error('Pipeline not saved.',{timeOut:15000})
			} else {
				internal_error();
			}

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null)
}

/*
* CRM - Summary
* Edit Notes/Status
*/

function editSumStatus(id_ord_order,pipeline_id) {
	document.getElementById("sumStatus_id_ord_order").value = id_ord_order;
	if(pipeline_id<296){ var show_delete=1; } else { var show_delete=0; }
	
	var resurl='listeslies.php?elemid=summary_status_liste&id_ord_order='+id_ord_order+'&show_delete='+show_delete; 
    var xhr = getXhr();
    xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;

			document.getElementById("sumStatus_status_id").innerHTML = leselect;
			$("#modalEditSumStatus").modal("show");

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null)
}

function saveEditedSumStatus() {
	
	var action = 1;
	var id_ord_order = document.getElementById("sumStatus_id_ord_order").value;
	var status_id = document.getElementById("sumStatus_status_id").value;
	
	if(status_id == 393){
		var r = confirm("Are you sure to delete this order?");
		if (r == true) { action = 1; } else { action = 0; }
	} else { action = 1; }
	
	if(action == 1){
		EditedSumStatusAction(id_ord_order,status_id);
	}
}

function EditedSumStatusAction(id_ord_order,status_id) {
	var resurl='listeslies.php?elemid=save_edited_sum_status&id_ord_order='+id_ord_order+'&status_id='+status_id;
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;
			var val=leselect.split('##');

			if(val[0]==1){
				
				if(status_id!=393){
					toastr.success('Status saved successfully.',{timeOut:15000})
					document.getElementById("sumStatusName").innerHTML = val[1]; 
				} else {
					toastr.success('Order successfully deleted',{timeOut:15000})
				}
				
				document.getElementById("sumStatusForm").reset();
				$("#modalEditSumStatus").modal("hide");
				
			} else 
			if(val[0]==0){
				toastr.error('Status not saved.',{timeOut:15000})
			} else {
				internal_error();
			}

			leselect = xhr.responseText;
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null)
}

/*
* CRM - Summary
* Edit Customer Notes
*/

// Show input
function editIntNotes(id_ord_order) {
	$('#intNotesShow').addClass("hide");
	$('#intNotesInput').removeClass("hide");
	
	document.getElementById("intNotesManagBtn").innerHTML = '<a href="#" class="btn btn-white btn-sm" onclick="saveEditIntNotes('+id_ord_order+');"><i class="fa fa-check" style="color:green;"></i></a>'+
		' <a href="#" class="btn btn-white btn-sm" onclick="cancelEditIntNotes('+id_ord_order+');"><i class="fa fa-times" style="color:red;"></i></a>';
}

// Save Edited
function saveEditIntNotes(id_ord_order) {
	var notes_internal = document.getElementById("edit_notes_internal").value;
	
	var resurl='listeslies.php?elemid=save_edited_int_notes&id_ord_order='+id_ord_order+'&notes_internal='+notes_internal;
    var xhr = getXhr();
    xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;
            var val=leselect.split('##');

			if(val[0]==1){
				toastr.success('Note saved successfully.',{timeOut:15000})
				document.getElementById("intNotesShow").innerHTML = val[1];
				cancelEditIntNotes(id_ord_order);
				
			} else 
			if(val[0]==0){
				toastr.error('Note not saved.',{timeOut:15000})
				
			} else {
				internal_error();
			}

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null)
}


// Cancel Edition
function cancelEditIntNotes(id_ord_order) {
	$('#intNotesShow').removeClass("hide");
	$('#intNotesInput').addClass("hide");
	
	document.getElementById("intNotesManagBtn").innerHTML = '<a herf="#" onclick="editIntNotes('+id_ord_order+');" class="btn btn-white btn-sm"><i class="fa fa-edit"></i></a>';
}


// Show input
function editorderNrOld(id_ord_order) {
	$('#orderNrOldShow').addClass("hide");
	$('#orderNrOldInput').removeClass("hide");
	
	document.getElementById("orderNrOldManagBtn").innerHTML = '<a href="#" class="btn btn-white btn-sm" onclick="saveEditOrderNrOld('+id_ord_order+');"><i class="fa fa-check" style="color:green;"></i></a>'+
		' <a href="#" class="btn btn-white btn-sm" onclick="cancelEditOrderNrOld('+id_ord_order+');"><i class="fa fa-times" style="color:red;"></i></a>';
}

// Save Edited
function saveEditOrderNrOld(id_ord_order) {
	var order_nr_old = document.getElementById("edit_order_nr_old").value;
	
	var resurl='listeslies.php?elemid=save_edited_order_nr_old&id_ord_order='+id_ord_order+'&order_nr_old='+order_nr_old;
    var xhr = getXhr();
    xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;
            var val=leselect.split('##');

			if(val[0]==1){
				toastr.success('Note saved successfully.',{timeOut:15000})
				document.getElementById("orderNrOldShow").innerHTML = val[1];
				cancelEditOrderNrOld(id_ord_order);
				
			} else 
			if(val[0]==0){
				toastr.error('Note not saved.',{timeOut:15000})
				
			} else {
				internal_error();
			}

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null)
}


// Cancel Edition
function cancelEditOrderNrOld(id_ord_order) {
	$('#orderNrOldShow').removeClass("hide");
	$('#orderNrOldInput').addClass("hide");
	
	document.getElementById("orderNrOldManagBtn").innerHTML = '<a herf="#" onclick="editorderNrOld('+id_ord_order+');" class="btn btn-white btn-sm"><i class="fa fa-edit"></i></a>';
}


/*
* 
* Menu management and controls
* 
*/


/* Edit user profile */

function saveProfileData() {

	var name = document.getElementById("prfname").value;
	var username = document.getElementById("prfusername").value;
	var company_name = document.getElementById("prfCompany").value;
	var id_supchain_type = document.getElementById("prfSupchain").value;
	var name_country = document.getElementById("prfCountry").value;
	var name_town = document.getElementById("prfTown").value;

	var resurl='listeslies.php?elemid=save_profile_data&name='+name+'&username='+username+'&company_name='+company_name+'&id_supchain_type='+id_supchain_type+'&name_country='+name_country+'&name_town='+name_town;
    var xhr = getXhr();
    xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;
            var val=leselect;
            var val1=val.split('##');

            if (val1[0] == 1) {
				window.open("index.php",'_self');
			} else 
			if (val1[0] == 0) {
				document.getElementById("login-alert22").style.display = "none";
			    document.getElementById("login-alert21").style.display = "block";
	            document.getElementById('login-alert21').innerHTML = '<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'+val1[1];
			} else {
				internal_error();
			}

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


/* Reset password */

function resetPassword(user_id) {
	var pass = document.getElementById('password1').value;
	
	var resurl='listeslies.php?elemid=reset_password&password='+pass+'&user_id='+user_id;  
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;  

			if(leselect==1){
				toastr.success('Password reset successfully.',{timeOut:15000})
			} else 
			if(leselect==0){
				toastr.error('Password not reset please retry.',{timeOut:15000})
			} else {
				internal_error();
			}

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


/* Link to reset password */

function reset_PassLink() { 
	var username = document.getElementById('login').value;
	
	if(username!=""){ 
		var resurl='listeslies.php?elemid=reset_pass_link&username='+username; 
		var xhr = getXhr();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;   

				if(leselect==1){
					document.getElementById("reset_link_alert_err").style.display = "none";
					document.getElementById("reset_link_alert_succ").style.display = "block";
					document.getElementById("reset_link_alert_succ").innerHTML = '<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button> Mail has been sent to check to reset your password';
				} else 
				if(leselect==0){
					document.getElementById("reset_link_alert_succ").style.display = "none";
					document.getElementById("reset_link_alert_err").style.display = "block";
					document.getElementById("reset_link_alert_err").innerHTML = '<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button> Error sendind mail, check your email address for error and retry!';
				} else 
				if(leselect==2){
					document.getElementById("reset_link_alert_succ").style.display = "none";
					document.getElementById("reset_link_alert_err").style.display = "block"; 
					document.getElementById("reset_link_alert_err").innerHTML = '<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button> Internal error, please retry!';
				} else {
					internal_error();
				}

				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
		
	} else {
		alert('Enter your login');
	}
}


function showProposalDocBtn(ord_order_id,order_ship_nr,pipeline_id) {
	if(pipeline_id == 296){
		$("#create_proposal_doc_btn").prop("disabled", true);
		$("#sales_pipeline_btn").prop("disabled", true);
	} else {
		$("#create_proposal_doc_btn").prop("disabled", false);
		$("#sales_pipeline_btn").prop("disabled", false);
	}

	document.getElementById('proposal_doc_toggle').innerHTML = '<button class="btn btn-danger pull-right" onclick="cancelProposalDocBtn('+ord_order_id+','+order_ship_nr+','+pipeline_id+');" style="margin-top:10px;" type="button"><i class="fa fa-ban"></i></button>'
		+'<button class="btn btn-success pull-right" onclick="save_proposal('+ord_order_id+','+order_ship_nr+');" style="margin-top:10px; margin-right:10px;" type="button"><i class="fa fa-save"></i></button>';
}


function cancelProposalDocBtn(ord_order_id,order_ship_nr,pipeline_id) {
	if(pipeline_id == 296){
		$("#create_proposal_doc_btn").prop("disabled", true);
		$("#sales_pipeline_btn").prop("disabled", true);
	} else {
		$("#create_proposal_doc_btn").prop("disabled", false);
		$("#sales_pipeline_btn").prop("disabled", false);
	}
	
	document.getElementById('proposal_doc_toggle').innerHTML = '<button class="btn btn-success" onclick="showProposalDocBtn('+ord_order_id+','+order_ship_nr+','+pipeline_id+');" style="margin-top:10px;" type="button"><i class="fa fa-edit"></i></button>';
}


function save_proposal(ord_order_id,order_ship_nr) {
	cancelProposalDocBtn(ord_order_id,order_ship_nr);
}


/*
*
* CRM : upload documents
*
*/

function openDocUploader() {
	$('#docUploaderCt').removeClass('hide');
	document.getElementById('document_toggler').innerHTML = '<a href="#" onclick="closeDocUploader();"><i class="fa fa-times"></i></a>';
}


function closeDocUploader() {
	$('#docUploaderCt').addClass('hide');
	document.getElementById('document_toggler').innerHTML = '<a href="#" onclick="openDocUploader();"><i class="fa fa-file-text-o"></i></a>';
}

function docFile(value) {

	if(value!=""){
		var ext = $('#po_document').val().split('.').pop().toLowerCase();  
		if(ext!='pdf'){
			$('#upload_document').on('submit', function(e) {
				e.preventDefault();
			});
			
			toastr.error('invalid file type!',{timeOut:15000})
			return;
		}
	
		var _size = $('#po_document')[0].files[0].size;
		var fSExt = new Array('Bytes', 'KB', 'MB', 'GB'),
		i=0;while(_size>900){_size/=1024;i++;}
		var exactSize = (Math.round(_size*100)/100)+' '+fSExt[i];
	
		$('.file-dummy').removeClass('po_bg_default');
		$('.file-dummy').addClass('po_bg_success');
		
		$('#po_success').removeClass('hide');
		$('#po_default').addClass('hide');
		
		var doc_filesize = '<br/>'+exactSize;
		var doc_filename = value.replace(/C:\\fakepath\\/i, '');
		document.getElementById('po_success').innerHTML = doc_filename+doc_filesize;
	} else {
		$('.file-dummy').removeClass('po_bg_success');
		$('.file-dummy').addClass('po_bg_default');
		
		$('#po_default').removeClass('hide');
		$('#po_success').addClass('hide');
		
		document.getElementById('po_default').innerHTML = '<i class="fa fa-file-pdf-o"></i> Drop files here or click to upload.';
	}
}

function showDocList(id_ord_order,ord_schedule_id,type,position) { 

	document.getElementById('sideBarBtnToggle').innerHTML = '<i class="fa fa-caret-left"></i>';
	document.getElementById('documentCurrentType').value = type;
	document.getElementById('documentCurrentPosition').value = position;
	$('#right-sidebar').addClass('hide');

	$('#sideBarBtnToggle').removeClass("toggleOpen");
	$('#sideBarBtnToggle').removeClass('hide');

	volet_droit_animated();

	documentForm(id_ord_order,ord_schedule_id,type,position);  
}


function documentForm(id_ord_order,id_ord_schedule,type,position) { 
	
	if(type=='crm'){
		var doc_right=docManager_create;
	} else {
		var doc_right=bookingDocManager_create;
	}

	var resurl='listeslies.php?elemid=document_loading&id_ord_order='+id_ord_order+'&id_ord_schedule='+id_ord_schedule+'&type='+type+'&position='+position+'&doc_edit='+doc_right;
    var xhr = getXhr();   
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;  

			document.getElementById('rightInfos').innerHTML = leselect;
			documentList(id_ord_order,id_ord_schedule,type,position);

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function documentList(id_ord_order,ord_schedule_id,type,position) { 
	var resurl='listeslies.php?elemid=document_list&id_ord_order='+id_ord_order+'&ord_schedule_id='+ord_schedule_id+'&type='+type+'&position='+position;
    var xhr = getXhr(); 
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;  
			var list = leselect.split('##');

			if(list[0]!='') { var docList=list[0]; } else {  var docList='<li><i class="fa fa-warning"></i> No documents</li>'; }
			if(list[1]!='') { var mailList=list[1]; } else {  var mailList='<li><i class="fa fa-warning"></i> No mails</li>'; }
			
			document.getElementById('po_doc_list').innerHTML = docList;  
			document.getElementById('po_mail_list').innerHTML = mailList;  

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function documents() { 

	var conf = document.getElementById("po_type").value; 
	
	var ord_order_id = document.getElementById("po_doc_order_id").value; 
	var ord_schedule_id = document.getElementById("po_doc_schedule_id").value; 
	var doc_type_id = document.getElementById("po_doc_type_id").value; 
	var document_desc = document.getElementById("po_document_desc").value;   
	
	var ext = $('#po_document').val().split('.').pop().toLowerCase();  
	if(ext!='pdf'){
		$('#upload_document').on('submit', function(e) {
			e.preventDefault();
		});
		
		return;
	}

	if(doc_type_id == ""){
		
		$('#upload_document').on('submit', function(e) {
			e.preventDefault();
		});
		
		alert('Chose a document type.');
		
	} else {
		var spinner = '<div class="sk-spinner sk-spinner-double-bounce div_ov_spanner">'+
			'<div class="sk-double-bounce1"></div>'+
			'<div class="sk-double-bounce2"></div>'+
		'</div>';
		
		var progressbar = '<div id="progress-div"><div id="progress-bar"></div></div>';

		$("#docUploaderCt").append("<div class='div_overlay'>"+spinner+progressbar+"</div>");
	
		$('#upload_document').one('submit', function(e) {

			e.preventDefault();
			$('#progress-div').show();
			$(this).ajaxSubmit({  
				beforeSubmit: function() {
					$("#progress-bar").width('0%');
				},
				uploadProgress: function (event, position, total, percentComplete){	
					$("#progress-bar").width(percentComplete + '%');
					$("#progress-bar").html('<div id="progress-status">' + percentComplete +' %</div>')
				},
				success:function (response){
					$(".div_overlay").remove();
					$('#progress-div').hide();
					$("#uploadDocBtn").prop("disabled", true);
				
					if(response == 1){
						var doc_filename = document.getElementById("po_doc_newName").value;  
						saveUploadedDocument(ord_order_id,ord_schedule_id,doc_type_id,doc_filename,document_desc,conf);
						
						if((doc_type_id == 13)||(doc_type_id == 14)){
							sysAdminMAil(ord_order_id,ord_schedule_id,doc_type_id);
						}
						
					} else { toastr.error(response,{timeOut:15000}) }
				},
				resetForm: true 
			}); 
			return false; 
		});
	}

}

function sysAdminMAil(ord_order_id,ord_schedule_id,doc_type_id) {
	var resurl='listeslies.php?elemid=system_admin_mail&ord_order_id='+ord_order_id+'&ord_schedule_id='+ord_schedule_id+'&doc_type_id='+doc_type_id; 
    var xhr = getXhr(); 
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;    

		
			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function docTypeSelect(doc_type_id) {  
	if(doc_type_id!=""){
		var ord_order_id = document.getElementById("po_doc_order_id").value; 
		var ord_schedule_id = document.getElementById("po_doc_schedule_id").value; 
		fileName(ord_order_id,ord_schedule_id,doc_type_id,'email');
	} else {
		document.getElementById("po_doc_newName").value = '';
	}
}


function fileName(ord_order_id,ord_schedule_id,doc_type_id,conf) {
	
	document.getElementById("generatedFileName").value="";
	
	if((ord_schedule_id!="")&&(ord_order_id!="")){
		var type = 'logistics';
		var id = ord_schedule_id;
	} else
	if((ord_schedule_id!="")&&(ord_order_id=="")){
		var type = 'logistics';
		var id = ord_schedule_id;
	} else {
		var type = 'crm';
		var id = ord_order_id;
	}
	
	var resurl='listeslies.php?elemid=naming_file&id='+id+'&type='+type+'&doc_type_id='+doc_type_id; 
    var xhr = getXhr(); 
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;    

			if(conf!='invoice'){
				document.getElementById("po_doc_newName").value = leselect;
				$("#uploadDocBtn").prop("disabled", false);
				
			} else {
				document.getElementById("generatedFileName").value = leselect; 
			}

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function saveUploadedDocument(ord_order_id,ord_schedule_id,doc_type_id,doc_filename,document_desc,conf) {

	var resurl='listeslies.php?elemid=cus_po_document&ord_order_id='+ord_order_id+'&ord_schedule_id='+ord_schedule_id+'&doc_type_id='+doc_type_id+'&doc_filename='+doc_filename+'&document_desc='+document_desc;   
	var xhr = getXhr();  
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;    
		
				if(leselect == 1){
					toastr.success('Document uploaded successfully',{timeOut:15000})
					documentList(ord_order_id,ord_schedule_id,conf);  
					document.getElementById("upload_document").reset();
					docFile('');
					
					notifications("document");
			
				} else 
				if(leselect == 0){
					toastr.error('Document not uploaded.',{timeOut:15000})
				} else {
					internal_error();
				}
				
			leselect = xhr.responseText;
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}


function getWizardFrom1_id() {
	var resurl='listeslies.php?elemid=chain_list';
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;

			document.getElementById('prfSupchain').innerHTML = leselect;

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function transitDaysCal(id_townport,ord_order_id,id_ord_schedule,order_incoterms_id) {

	// var incoterms_id = document.getElementById('req_quote_incoterms_id').value;  
	// if(incoterms_id==263){ process=0; }
	// else if(incoterms_id==264){ process=0; }
	// else{ process=1; }	
	
	var resurl='listeslies.php?elemid=transit_days_calcul&ord_order_id='+ord_order_id+'&id_townport='+id_townport+'&id_ord_schedule='+id_ord_schedule+'&order_incoterms_id='+order_incoterms_id;
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;   
			var val = leselect.split('##');
			
			if((val[2]=="")&&(val[3]=="")){ 
				document.getElementById('transDays').innerHTML = 'Transit days : '+val[1];
				document.getElementById('req_quote_month_etd').value = val[0];
		
				$('#req_quote_month_etd').datepicker({
					format: "yyyy/mm/dd",
					calendarWeeks:true,
					autoclose: true
				}).datepicker('setDate', val[0]);
				
				document.getElementById("req_quote_week_etd").value = moment(val[0], "YYYY/MM/DD").week();
				
			} else { 
				document.getElementById('transDays').innerHTML = '';
				document.getElementById("req_quote_week_eta").value = moment(val[2], "YYYY/MM/DD").week();
				document.getElementById("req_quote_month_eta").value = val[2];
			}

			leselect = xhr.responseText;
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}


function selectClient() {
	var company_id = document.getElementById('ord_cus_contact_id2').value;
	if(company_id == ""){
		$('#order_client').addClass("hide");
	} else {
		var resurl='listeslies.php?elemid=select_client&company_id='+company_id;
		var xhr = getXhr();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;

				document.getElementById('ord_cus_person_id2').innerHTML = leselect;  
				$('#order_client').removeClass("hide");

				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
	}
}

function chainList() {
	var resurl='listeslies.php?elemid=chain_list';
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;

			document.getElementById('prfSupchain').innerHTML = leselect;

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


var plantation_pictures = L.geoJson('', {});

var overlayMaps_map2 = { 
	"Collection Point": plantation_points,
	"Plantation": plantation_couche_farmer,  
	"Pictures": plantation_pictures,
	"Traces": plantation_lines_couche,
	"&nbsp;&nbsp;<img src='img/water.png' width='20' height='20'>&nbsp;Eaux": water,
	"&nbsp;&nbsp;<img src='img/water_ways.png' width='20' height='20'>&nbsp;Cours d'eaux": water_ways,
	"&nbsp;&nbsp;<img src='img/parc.png' width='20' height='20'>&nbsp;National Parc": parc_national,
	"&nbsp;&nbsp;<img src='img/reserve.png' width='20' height='20'>&nbsp;National Reserve": reserve,
	"&nbsp;&nbsp;<img src='img/classified.png' width='20' height='20'>&nbsp;Classified Area": foret_classee,
	"&nbsp;&nbsp;<img src='img/icon_infrastructure.png' width='20' height='20'>&nbsp;Infrastructure": infrastructure_couche
};

L.control.layers(baseMaps2, overlayMaps_map2).addTo(map2);

function refreshPlantMap() {
	showContactPlantationDetails(current_plantation_id,'');
}

function showPlantation(gid_plantation) {

	plantation_couche_farmer.clearLayers();
	plantation_points.clearLayers();
	
	var resurl='include/plantation.php?elemid=show_plantation_and_collection&id='+gid_plantation;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){  
        if(xhr.readyState == 4 ){
			leselect = xhr.responseText;    console.log(leselect);
	
			map2.invalidateSize();
			
			var plantations = JSON.parse(leselect);  

			if(plantations[0]) {
				if(geoField_update == 1){   
						var editBtn = '<i class="fa fa-pen-square fa-fw pull-right" onclick="editFarmerCPoint(\''+plantations[0].properties.gid_plantation+'\',\''+plantations[0].properties.id_contact+'\');" style="cursor:pointer; color:green;"></i>';
				} else { var editBtn = ""; }
			
				if((plantations[0].properties.coordx!=null)&&(plantations[0].properties.coordy!=null)){
					if(plantations[0].properties.name_farmer === null){ var name_farmer=""; } else { var name_farmer=plantations[0].properties.name_farmer; }
					if(plantations[0].properties.name_farmergroup === null){ var name_farmergroup=""; } else { var name_farmergroup=plantations[0].properties.name_farmergroup; }
					if(plantations[0].properties.name_town === null){ var name_town=""; } else { var name_town=plantations[0].properties.name_town; }
					if(plantations[0].properties.code_farmer === null){ var code_farmer=""; } else { var code_farmer=plantations[0].properties.code_farmer; }
					if(plantations[0].properties.culture === null){ var culture=""; } else { var culture=plantations[0].properties.culture; }
					if(plantations[0].properties.area === null){ var area=""; } else { var area=plantations[0].properties.area; }
					if(plantations[0].properties.name_buyer === null){ var name_buyer=""; } else { var name_buyer=plantations[0].properties.name_buyer; }
					if(plantations[0].properties.gid_plantation === null){ var gid_plantation=""; } else { var gid_plantation=plantations[0].properties.gid_plantation; }
					if(plantations[0].properties.gid_town === null){ var gid_town=""; } else { var gid_town=plantations[0].properties.gid_town; }
					if(plantations[0].properties.id_contact === null){ var id_contact=""; } else { var id_contact=plantations[0].properties.id_contact; }
					if(plantations[0].properties.plantation_town === null){ var plantation_town=""; } else { var plantation_town=plantations[0].properties.plantation_town; }
					if(plantations[0].properties.code_parcelle === null){ var code_plantation=""; } else { var code_plantation=plantations[0].properties.code_parcelle; }
					
					var popupContent = "<div style=\"max-width:400px; max-height: 200px\"><h5 style=\"border-bottom: 1px solid #eee;\">"+blanc
						+"<i class=\"fa fa-check-square fa-fw\" style=\"color:#ed1b2c\"></i><strong style=\"color:#ed1b2c\">&nbsp;&nbsp;Collection Point</strong>"+editBtn+"</h5>"+blanc
							+"<div class=\"icon_desc\" style=\"margin-left:0px;display:block\"><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Farmer name : </strong>"+name_farmer
							+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Farmer group : </strong>"+name_farmergroup
							+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Farmer residence : </strong>"+name_town
							+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Code Farmer : </strong>"+code_farmer
							+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Code Plantation : </strong>"+code_plantation
							+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Culture : </strong>"+culture
							+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Area (ha) : </strong>"+area
							+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Buyer : </strong>"+name_buyer
							+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> GID plantation : </strong>"+gid_plantation
							+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Plantation town : </strong>"+plantation_town
							+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> ID Town Plantation : </strong>"+gid_town
							+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> ID Contact: </strong>"+id_contact
						+" </span></div></div>";
						
						
					L.marker([plantations[0].properties.coordx, plantations[0].properties.coordy], {icon: pointIcon,riseOnHover:true})
						.bindPopup(popupContent)
						.addTo(plantation_points);
					map2.addLayer(plantation_points);
				}
				
				if(plantations[0].properties.geom_json!=null) {
					plantation_couche_farmer.addData(plantations[0]);	 
					map2.addLayer(plantation_couche_farmer);   
				}
				
				if((plantations[0].properties.coordx!=null)&&(plantations[0].properties.geom_json==null)){ 
					map2.setView([plantations[0].properties.coordx, plantations[0].properties.coordy], 15);
				} else 
				if((plantations[0].properties.coordx!=null)&&(plantations[0].properties.geom_json!=null)){  
					map2.fitBounds(plantation_couche_farmer.getBounds().extend(plantation_points.getBounds()));
				} else
				if((plantations[0].properties.coordx==null)&&(plantations[0].properties.geom_json!=null)){  
					map2.fitBounds(plantation_couche_farmer.getBounds());
				} else { 
					map2.fitWorld().zoomIn();
				}
			}
	
			load_infrastructures();
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function showPlantationTraceLine(id_plantation) {

	plantation_lines_couche.clearLayers();

	var resurl='include/plantation.php?elemid=show_plantation_lines&id_plantation='+id_plantation;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;    
			
			if(leselect!=[]){
				var plantation_lines = JSON.parse(leselect);    
				plantation_lines_couche.addData(plantation_lines);	 
				map2.addLayer(plantation_lines_couche);  
			}
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}

function editFarmerPlant_farmer(id_plantation,id_farmer) {
	if(saveBtn){ map2.removeControl(saveBtn); }
	if(cancelBtn){ map2.removeControl(cancelBtn); }
	
	removeDrawCtl();
	// map.removeControl(drawControl);
	// drawnPolygone.clearLayers();
	
	saveBtn = L.easyButton('fa fa-save fa-lg', function(btn, map){ saveFPModule(id_plantation,id_farmer,'farmer'); });
	cancelBtn = L.easyButton('fa fa-ban fa-lg', function(btn, map){ closeFPModule(id_farmer,'farmer'); });
	
	cancelBtn.button.style.color = 'white';
	cancelBtn.button.style.backgroundColor = 'red';
	saveBtn.button.style.color = 'white';
	saveBtn.button.style.backgroundColor = 'green';
	
	saveBtn.addTo(map2);
	cancelBtn.addTo(map2);
	
	map2.addControl(drawControl);
	
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
		
			map2.addLayer(polygon);
			
			polygon.on('edit', function(e) {
				var json = e.target.toGeoJSON();

				var new_json = {
					"type": "MultiPolygon",
					"coordinates": [
						json.geometry.coordinates
					],"crs":{"type":"name","properties":{"name":"EPSG:4326"}} 
				};
			
				poly = JSON.stringify(new_json);
	
				var polygon3 = turf.polygon(json.geometry.coordinates);  
				seeArea = turf.area(polygon3);   
			});
			
			map2.fitBounds(polygon.getBounds());
		}
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);

	map2.on(L.Draw.Event.CREATED, function (event) {
		var layer = event.layer;

		drawnPolygone.addLayer(layer);
		map2.addLayer(drawnPolygone);

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
			],"crs":{"type":"name","properties":{"name":"EPSG:4326"}} 
		};
	
		poly = JSON.stringify(new_json);
	});
	
	map2.on(L.Draw.Event.EDITED, function(event) {
		var layers = event.layers;
		
		drawnPolygone.addLayer(layers);
		map2.addLayer(drawnPolygone);
		
		var json = drawnPolygone.toGeoJSON();
		
		var new_json = {
			"type": "MultiPolygon",
			"coordinates": [
				json.geometry.coordinates
			],"crs":{"type":"name","properties":{"name":"EPSG:4326"}} 
		};
		
		poly = JSON.stringify(new_json);
	});
}


/* To be removed */
function editPlantationSpecial(id_plantation) {
	$("#deleteConfirmation").modal("show");	
	
	var resurl='include/plantation.php?elemid=show_plantation_special&id_plantation='+id_plantation;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;
			var val = leselect.split('##');
			
			document.getElementById('deleteConfirmationHeader').innerHTML = 'Edit Plantation - ('+val[0]+')';  
			document.getElementById('deleteConfirmationContent').innerHTML = '<div class="form-group">'
				+'<label for="special_code_plantation">Plantation Code</label>'
				+'<input type="text" value="'+val[0]+'" id="special_code_plantation" class="form-control"></div>'
				+'<div class="form-group">'
				+'<label for="special_notes_plantation">Notes</label>'
				+'<textarea id="special_notes_plantation" class="form-control">'+val[1]+'</textarea></div>'
				+'<div class="form-group">'
				+'<label for="special_year_creation">Année de mise en culture</label>'
				+'<input type="number" value="'+val[2]+'" id="special_year_creation" class="form-control"></div>'
			document.getElementById('deleteConfirmationFooter').innerHTML = '<button type="button" class="btn btn-primary pull-left" onclick="save_special_plantation(\''+id_plantation+'\');" data-dismiss="modal">Save</button> '
				+'<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>'; 
		}
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function save_special_plantation(id_plantation) {  
	
	var req='';
	
	var code_plantation=document.getElementById('special_code_plantation').value;
	if(code_plantation){ req=req+'&code_plantation='+code_plantation; }
	
	var notes=document.getElementById('special_notes_plantation').value;
	if(notes){ req=req+'&notes='+notes; }

	var year_creation=document.getElementById('special_year_creation').value;
	if(year_creation){ req=req+'&year_creation='+year_creation; }
	
	var resurl='include/plantation.php?elemid=update_plantation_special&id_plantation='+id_plantation+req;  
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;  
			
			if(leselect==1){
				toastr.success('Successfully saved',{timeOut:15000})
				showContactPlantationDetails(id_plantation,'');
			} else 
			if(leselect==0){
				toastr.error('Not saved',{timeOut:15000})
			} else {
				internal_error();
			}
		}
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}

/* To be removed */

function showContactPlantationDetails(gid_plantation,type) {
	var resurl='include/plantation.php?elemid=selected_plantation_details&gid_plantation='+gid_plantation;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;  
			var val = leselect.split('##');
			
			plantationTabTown(val[2]);
			showPlantPic(val[3],0);
			
			showPlantation(gid_plantation);
			showPlantationTraceLine(gid_plantation);
			plantationPicturesMap(gid_plantation,'plantation');
			
			map2.addLayer(water);
			map2.addLayer(water_ways);
			
			document.getElementById('ct_plant_content').innerHTML = val[0];
			document.getElementById('PlantationImages').innerHTML = val[1];
			
			$('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });
			
			$('input[name="check_out_plant"]').on('ifClicked', function (event) {
				updateCheckOut('planntation',this.value,gid_plantation,'');
			});
			
			$('input[name="check_out_cont"]').on('ifClicked', function (event) {
				updateCheckOut('contact',this.value,val[4],type);
			});
			
			var $carousel = $('.main-carousel').flickity({
				prevNextButtons: false,
				imagesLoaded: true,
				wrapAround: true,
				freeScroll: true,
				percentPosition: false
			});

			// previous
			$('.--prev').on( 'click', function() { 
				$carousel.flickity('previous');
				var id_prev = $(".flky.is-selected").attr("id"); 
				showPlantPic(id_prev,1);
			});
			
			// next
			$('.--next').on( 'click', function() { 
				$carousel.flickity('next');	
				var id_next = $(".flky.is-selected").attr("id");
				showPlantPic(id_next,1);
			});
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function plantationTabTown(id_town) { 

	plantationTown_couche.clearLayers();
	plantationTown_label.clearLayers();
	
	var resurl='include/plantation.php?elemid=plantation_town_marker&id_town='+id_town;  
    var xhr = getXhr();
	xhr.onreadystatechange = function(){  
        if(xhr.readyState == 4 ){
			leselect = xhr.responseText;   
			var elt = leselect.split('##');
			
			if((elt[1]!="")&&(elt[2]!="")) {
				var mark = L.marker([elt[1], elt[2]],{icon: townIcon,riseOnHover:true}).bindPopup(elt[0]).addTo(plantationTown_couche); 
		
				var divIcon = L.divIcon({ 
					className: "labelClass",
					iconAnchor:[-15,25],
					html: elt[0]
				});

				var mark2 = L.marker([elt[1], elt[2]], {icon: divIcon }).addTo(plantationTown_label);
				
				map2.addLayer(plantationTown_couche);
				map2.addLayer(plantationTown_label);
			} 

		}
	}
	
	xhr.open("GET",resurl,true);
	xhr.send(null);
}


function showContactPlantationManagerDetails(id_plantation) {
	var resurl='include/plantation.php?elemid=selected_plantation_manager_details&id_plantation='+id_plantation;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;  
			var val = leselect.split('##');
	
			document.getElementById('plantManager_show').innerHTML = val[0];
			document.getElementById('plantManager_Household_tabs').innerHTML = val[1];
			document.getElementById('plantManager_Demography_tabs').innerHTML = val[2];
			
			showContactHouseholdDetails(val[3], 'plantation');
			
			$('#manager_household_list_content li').click(function() { 
				$('ul li.on9').removeClass('on9'); 
				$(this).closest('li').addClass('on9'); 
			});	
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function deletePlantDoc(id_plantation,id_plantdoc) {
	var resurl='include/plantation.php?elemid=delete_plantation_document&id_plantdoc='+id_plantdoc;  
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;   

			if(leselect==1){
				toastr.success('Document successfully deleted',{timeOut:15000})
				showContactPlantationDetails(id_plantation,'');
			} else 
			if(leselect==0){
				toastr.error('Document not deleted',{timeOut:15000})
			} else {
				internal_error();
			}
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function showFarmerDocPDF(file_path,conf,id_plantation,type) {
	$("#bookingDocModal").modal("show");
	
	var spinner = '<div class="sk-spinner sk-spinner-double-bounce div_ov_spanner">'+
			'<div class="sk-double-bounce1"></div>'+
			'<div class="sk-double-bounce2"></div>'+
		'</div>';
		
	document.getElementById('booking_document_show').innerHTML = spinner;

	var link="img/farmer_document/";
	
	if(type == 14167) {
		var ficheurl='pdf/farmer_document_agrivar.php?link='+file_path+'&id_plantation='+id_plantation; 
	} else {
		var ficheurl='pdf/farmer_document.php?link='+file_path+'&id_plantation='+id_plantation; 
	}
	
	if(conf == 0) {
		document.getElementById('booking_document_show').innerHTML = '<div><iframe src="'+link+file_path+'" style="width:100%; height:500px;"></iframe></div>';
		document.getElementById('booking_document_footer').innerHTML = '<a href="'+link+file_path+'" target="_blank" class="btn btn-info pull-left"><i class="fa fa-file-pdf-o"></i>&nbsp;Preview/Print</a>'
		+'<button type="button" class="btn btn-danger pull-right" data-dismiss="modal"><i class="fa fa-ban"></i> Close</button>';
	} else {
		document.getElementById('booking_document_show').innerHTML = '<div><iframe src="'+ficheurl+'" style="width:100%; height:500px;"></iframe></div>';
		document.getElementById('booking_document_footer').innerHTML = '<a href="https://icoop.live/ic/'+ficheurl+'" target="_blank" class="btn btn-info pull-left"><i class="fa fa-file-pdf-o"></i>&nbsp;Preview/Print</a>'
		+'<button type="button" class="btn btn-danger pull-right" data-dismiss="modal"><i class="fa fa-ban"></i> Close</button>';
	}
}


function createFarmerDocCapture(id_plantation) {
	
	var spinner = '<div class="sk-spinner sk-spinner-double-bounce div_ov_spanner">'+
		'<div class="sk-double-bounce1"></div>'+
		'<div class="sk-double-bounce2"></div>'+
	'</div>';

	$("#plantation_tab_content").append("<div class='div_overlay'>"+spinner+"</div>");
	
	var resurl='upload_cloudinary?id='+id_plantation;  
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText; 
			var val = leselect.split('##');
			$(".div_overlay").remove();
			
			if(val[0] == 1) {
				showFarmerDocPDF(val[1],1,id_plantation,'');
				showContactPlantationDetails(id_plantation,'');
			}
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function createFarmerDocCaptureAgv(id_plantation) {
	
	var spinner = '<div class="sk-spinner sk-spinner-double-bounce div_ov_spanner">'+
		'<div class="sk-double-bounce1"></div>'+
		'<div class="sk-double-bounce2"></div>'+
	'</div>';

	$("#plantation_tab_content").append("<div class='div_overlay'>"+spinner+"</div>");
	
	var resurl='upload_cloudinary_agrivar?id='+id_plantation;  
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;  
			var val = leselect.split('##');
			$(".div_overlay").remove();
			
			if(val[0] == 1) {
				showFarmerDocPDF(val[1],1,id_plantation,14167);
				showContactPlantationDetails(id_plantation,'');
			}
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}

function showContactCertificationDetails(gid_plantation) {
	var resurl='include/plantation.php?elemid=selected_plantation_certification_details&gid_plantation='+gid_plantation;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;
			var val	= leselect.split('##');
		
			document.getElementById('ct_certification_content').innerHTML = val[0];
			document.getElementById('cert_documents').innerHTML = val[1];
			document.getElementById('certificationTabDocs').innerHTML = val[2];
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function selectCertDocVersion(id_contact,id_plantation) {
	$("#verCertDocModal").modal("show");
	
	document.getElementById('verCertDocModal_show').innerHTML = '<div class="form-group">'
		+'<label for="selCertDoc_type">Selectionner la verion du document *</label>'
		+'<select id="selCertDoc_type" class="form-control">'
		+'<option value="">-- Version --</option>'
		+'<option value="654">Ancienne version</option>'
		+'<option value="809">Nouvelle version</option>'
	+'</select></div>';
	
	document.getElementById('verCertDocModal_footer').innerHTML = '<a href="#" onclick="createCertDocPDF(\''+id_contact+'\',\''+id_plantation+'\');" data-dismiss="modal" class="btn btn-info pull-left"><i class="fa fa-save"></i>&nbsp;Enregistrer</a>'
		+'<button type="button" class="btn btn-danger pull-right" data-dismiss="modal"><i class="fa fa-ban"></i> Fermer</button>';
}


function createCertDocPDF(id_contact,id_plantation) {
	var doc_type = document.getElementById('selCertDoc_type').value;
	$("#bookingDocModal").modal("show");

	var ficheurl, next;
	
	if(doc_type == 654){
		var ficheurl='pdf/certification_document.php?id_contact='+id_contact; 
		next = true;
	} else
	if(doc_type == 809){
		ficheurl='pdf/globalgap_agreement_2.php?id_contact='+id_contact; 
		next = true;
	} else {
		selectCertDocVersion(id_contact,id_plantation);
		next = false;
	}
	
	if(next == true) {
		showContactCertificationDetails(id_plantation);
		
		document.getElementById('booking_document_show').innerHTML = '<div><iframe src="'+ficheurl+'" style="width:100%; height:500px;"></iframe></div>';
		document.getElementById('booking_document_footer').innerHTML = '<a href="https://icoop.live/ic/'+ficheurl+'" target="_blank" class="btn btn-info pull-left"><i class="fa fa-file-pdf-o"></i>&nbsp;Preview/Print</a>'
			+'<button type="button" class="btn btn-danger pull-right" data-dismiss="modal"><i class="fa fa-ban"></i> Close</button>';
	}
}


function showCertDocPDF(doc_link,doc_type) {
	$("#bookingDocModal").modal("show");
	
	var link;
	if(doc_type == 654){
		link="https://icoop.live/ic/img/certification_document/";
	} else {
		link="https://icoop.live/ic/img/certification_document_2/";
	}
	
	document.getElementById('booking_document_show').innerHTML = '<div><iframe src="'+link+doc_link+'" style="width:100%; height:500px;"></iframe></div>';
	document.getElementById('booking_document_footer').innerHTML = '<a href="'+link+doc_link+'" target="_blank" class="btn btn-info pull-left"><i class="fa fa-file-pdf-o"></i>&nbsp;Preview/Print</a>'
		+'<button type="button" class="btn btn-danger pull-right" data-dismiss="modal"><i class="fa fa-ban"></i> Close</button>';
}


function deleteCertDoc(id_plantation,id_condoc) {
	var resurl='include/plantation.php?elemid=delete_contact_document&id_condoc='+id_condoc;  
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;   

			if(leselect==1){
				toastr.success('Document successfully deleted',{timeOut:15000})
				showContactCertificationDetails(id_plantation);
			} else 
			if(leselect==0){
				toastr.error('Document not deleted',{timeOut:15000})
			} else {
				internal_error();
			}
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function editPlantCertification(field_name,field_value,id_plantation) {
	
	var resurl='include/plantation.php?elemid=get_certification_list&field_value='+field_value;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;
			
			$("#editPlantCertification").modal("show");
	
			document.getElementById('editPlantCertificationContent').innerHTML = '<div class="form-group" style="padding-right:10px;">'
				+'<label>'+field_name+'</label><br/>'
				+'<select class="form-control" id="farmerCertification_'+field_name+'">'+leselect+'</select>'
			+'</div>';
			
			document.getElementById('editPlantCertificationFooter').innerHTML = '<button type="button" class="btn btn-primary pull-left" onclick="save_edited_farmerCertification(\''+field_name+'\',\''+id_plantation+'\');" data-dismiss="modal">Save</button> '
				+'<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>'; 
		}
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function save_edited_farmerCertification(field_name,id_plantation) {
	var new_value = document.getElementById('farmerCertification_'+field_name).value;
	
	var resurl='include/plantation.php?elemid=edit_farmer_certification&id_plantation='+id_plantation+'&field_name='+field_name+'&new_value='+new_value;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;  
			
			if(leselect==1){
				toastr.success('Saved successfully',{timeOut:15000})
				showContactCertificationDetails(id_plantation);
			} else 
			if(leselect==0){
				toastr.error('Not saved',{timeOut:15000})
			} else {
				internal_error();
			}
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function showContactEnvironmentDetails(gid_plantation) {
	var resurl='include/plantation.php?elemid=selected_plantation_environment_details&gid_plantation='+gid_plantation;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;  
			var val = leselect.split('##');
			
			showPlantEnvPic(val[2]);
		
			document.getElementById('ct_environment_content').innerHTML = val[0];
			document.getElementById('environment_media').innerHTML = val[1];
			
			// $('.main-carousel').flickity({
				// imagesLoaded: true,
				// percentPosition: false
			// });
			
			var $carousel = $('.main-carousel').flickity({
				prevNextButtons: false,
				imagesLoaded: true,
				wrapAround: true,
				freeScroll: true,
				percentPosition: false
			});

			// previous
			$('.--prev').on( 'click', function() { 
				$carousel.flickity('previous');
				var id_prev = $(".flky_env.is-selected").attr("id");
				showPlantEnvPic(id_prev);
			});
			
			// next
			$('.--next').on( 'click', function() { 
				$carousel.flickity('next');
				var id_next = $(".flky_env.is-selected").attr("id");
				showPlantEnvPic(id_next);
			});
			
			environmentMap(gid_plantation);
			plantationPicturesMap(gid_plantation,'environment');
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function showPlantEnvPic(id_plantdoc) {
	var resurl='include/plantation.php?elemid=show_plantation_picture&id_plantdoc='+id_plantdoc;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
			leselect = xhr.responseText; 
			var val = leselect.split('##');
			
			if((val[0]!="")&&(val[1]!="")) {
				map_env.setView([val[0],val[1]], 20); 
			}
			
			document.getElementById('EnvironmentImagesDetails').innerHTML = '<b>'+val[3]+'</b><p>'+val[2]+'</p>';
		}
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
} 


function createEnvDocPDF(id_plantation) {
	$("#bookingDocModal").modal("show");

	var link="img/environment_document/";
	var ficheurl='pdf/environment_document.php?id_plantation='+id_plantation; 
	showContactEnvironmentDetails(id_plantation);
	
	document.getElementById('booking_document_show').innerHTML = '<div><iframe src="'+ficheurl+'" style="width:100%; height:500px;"></iframe></div>';
	document.getElementById('booking_document_footer').innerHTML = '<a href="https://icoop.live/ic/'+ficheurl+'" target="_blank" class="btn btn-info pull-left"><i class="fa fa-file-pdf-o"></i>&nbsp;Preview/Print</a>'
		+'<button type="button" class="btn btn-danger pull-right" data-dismiss="modal"><i class="fa fa-ban"></i> Close</button>';
}


function showEnvDocPDF(doc_link) {
	$("#bookingDocModal").modal("show");
	
	var link="https://icoop.live/ic/img/environment_document/";
	
	document.getElementById('booking_document_show').innerHTML = '<div><iframe src="'+link+doc_link+'" style="width:100%; height:500px;"></iframe></div>';
	document.getElementById('booking_document_footer').innerHTML = '<a href="'+link+doc_link+'" target="_blank" class="btn btn-info pull-left"><i class="fa fa-file-pdf-o"></i>&nbsp;Preview/Print</a>'
		+'<button type="button" class="btn btn-danger pull-right" data-dismiss="modal"><i class="fa fa-ban"></i> Close</button>';
}


function deleteEnvDoc(id_plantation,id_plantdoc) {
	var resurl='include/plantation.php?elemid=delete_plantation_document&id_plantdoc='+id_plantdoc;  
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;   

			if(leselect==1){
				toastr.success('Document successfully deleted',{timeOut:15000})
				showContactEnvironmentDetails(id_plantation);
			} else 
			if(leselect==0){
				toastr.error('Document not deleted',{timeOut:15000})
			} else {
				internal_error();
			}
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}

var plantationEnv_pictures = L.geoJson('', {});

var overlayMaps_map_env = { 
	"Collection Point": plantationEnv_points,
	"Plantation": plantationEnv_couche,  
	"Pictures": plantationEnv_pictures
};

function refreshEnvMap() {
	showContactEnvironmentDetails(current_plantation_id);
}

L.control.layers(baseMaps_env, overlayMaps_map_env).addTo(map_env);

function environmentMap(id_plantation) {
	plantationEnv_couche.clearLayers();
	plantationEnv_points.clearLayers();
	
	var resurl='include/plantation.php?elemid=show_plantation_and_collection&id='+id_plantation;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){  
        if(xhr.readyState == 4 ){
			leselect = xhr.responseText;   
	
			if(leselect) {
				var plantations = JSON.parse(leselect); 
				
				map_env.invalidateSize();

				if(plantations[0]) {
					if(geoField_update == 1){   
						var editBtn = '<i class="fa fa-pen-square fa-fw pull-right" onclick="editFarmerCPoint(\''+plantations[0].properties.gid_plantation+'\',\''+plantations[0].properties.id_contact+'\');" style="cursor:pointer; color:green;"></i>';
					} else { var editBtn = ""; }
				
					if((plantations[0].properties.coordx!=null)&&(plantations[0].properties.coordy!=null)){
						if(plantations[0].properties.name_farmer === null){ var name_farmer=""; } else { var name_farmer=plantations[0].properties.name_farmer; }
						if(plantations[0].properties.name_farmergroup === null){ var name_farmergroup=""; } else { var name_farmergroup=plantations[0].properties.name_farmergroup; }
						if(plantations[0].properties.name_town === null){ var name_town=""; } else { var name_town=plantations[0].properties.name_town; }
						if(plantations[0].properties.code_farmer === null){ var code_farmer=""; } else { var code_farmer=plantations[0].properties.code_farmer; }
						if(plantations[0].properties.culture === null){ var culture=""; } else { var culture=plantations[0].properties.culture; }
						if(plantations[0].properties.area === null){ var area=""; } else { var area=plantations[0].properties.area; }
						if(plantations[0].properties.name_buyer === null){ var name_buyer=""; } else { var name_buyer=plantations[0].properties.name_buyer; }
						if(plantations[0].properties.gid_plantation === null){ var gid_plantation=""; } else { var gid_plantation=plantations[0].properties.gid_plantation; }
						if(plantations[0].properties.gid_town === null){ var gid_town=""; } else { var gid_town=plantations[0].properties.gid_town; }
						if(plantations[0].properties.id_contact === null){ var id_contact=""; } else { var id_contact=plantations[0].properties.id_contact; }
						
						var popupContent = "<div style=\"max-width:400px; max-height: 200px\"><h5 style=\"border-bottom: 1px solid #eee;\">"+blanc
							+"<i class=\"fa fa-check-square fa-fw\" style=\"color:#ed1b2c\"></i><strong style=\"color:#ed1b2c\">&nbsp;&nbsp;Collection Point</strong>"+editBtn+"</h5>"+blanc
								+"<div class=\"icon_desc\" style=\"margin-left:0px;display:block\"><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Farmer name : </strong>"+name_farmer
								+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Farmer group : </strong>"+name_farmergroup
								+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Farmer residence : </strong>"+name_town
								+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Code Farmer : </strong>"+code_farmer
								+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Culture : </strong>"+culture
								+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Area (ha) : </strong>"+area
								+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Buyer : </strong>"+name_buyer
								+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> GID plantation : </strong>"+gid_plantation
								+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> GID town : </strong>"+gid_town
								+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> ID Contact: </strong>"+id_contact
							+" </span></div></div>";
							
							
						L.marker([plantations[0].properties.coordx, plantations[0].properties.coordy], {icon: pointIcon,riseOnHover:true})
							.bindPopup(popupContent)
							.addTo(plantationEnv_points);
						map_env.addLayer(plantationEnv_points);
					}
					
					if(plantations[0].properties.geom_json!=null) {
						plantationEnv_couche.addData(plantations[0]);	 
						map_env.addLayer(plantationEnv_couche);   
					}
					
					if((plantations[0].properties.coordx!=null)&&(plantations[0].properties.geom_json==null)){ 
						map_env.setView([plantations[0].properties.coordx, plantations[0].properties.coordy], 15);
					} else 
					if((plantations[0].properties.coordx!=null)&&(plantations[0].properties.geom_json!=null)){  
						map_env.fitBounds(plantationEnv_couche.getBounds().extend(plantationEnv_points.getBounds()));
					} else
					if((plantations[0].properties.coordx==null)&&(plantations[0].properties.geom_json!=null)){  
						map_env.fitBounds(plantationEnv_couche.getBounds());
					} else { 
						map_env.fitWorld().zoomIn();
					}
				}

			}
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function showContactbusiFinaceDetails(gid_plantation) {
	var resurl='include/plantation.php?elemid=selected_plantation_busiFinace_details&gid_plantation='+gid_plantation;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText; 
			var val = leselect.split('##');
		
			document.getElementById('ct_busiFinace_content').innerHTML = val[0];
			document.getElementById('busiFinace_doc').innerHTML = val[1];
			
			loadGallery(true, 'a.thumbnail'); 
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function plantImgPreview(value,title) { 
	$('#image-gallery-image').attr('src', value);
	$('#image-gallery-title').text(title);
	$("#image-gallery").modal("show");
}

function plantationPicturesMap(id_plantation,conf) {
	plantation_pictures.clearLayers(); 
	plantationEnv_pictures.clearLayers();
	
	var resurl='include/plantation.php?elemid=plantation_pictures_on_map&id_plantation='+id_plantation+'&conf='+conf;
    var xhr = getXhr(); 
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;   
			var data = leselect.split('@@');
			
			i = 0; 
			while(data[i] != 'end'){
				
				var elt=data[i].split('##');
		
				var popupContent = "<div style=\"max-width:400px; max-height:auto;\"><h5 style=\"border-bottom: 1px solid #eee;\">"+blanc
					+"<i class=\"fa fa-check-square fa-fw\" style=\"color:#ed1b2c\"></i><strong style=\"color:#ed1b2c\">&nbsp;&nbsp;"+elt[2]+"</strong></h5>"+blanc
						+"<div class=\"icon_desc\" style=\"margin-left:0px;display:block\"><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Date : </strong>"+elt[4]
						+"</span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Description : </strong>"+elt[5]
						+"</span><br><img src='"+elt[3]+"' class='img-responsive'/>"
					+" </span></div></div>";
					
				if(conf == 'plantation') {
					L.marker([elt[0], elt[1]], {rotationAngle:elt[6],icon:plantPicture,riseOnHover:true})
						.bindPopup(popupContent)
						.addTo(plantation_pictures);
					map2.addLayer(plantation_pictures);
					
				} else {
					L.marker([elt[0], elt[1]], {rotationAngle:elt[6],icon:plantPicture,riseOnHover:true})
						.bindPopup(popupContent)
						.addTo(plantationEnv_pictures);
					map_env.addLayer(plantationEnv_pictures);
				}
			
				i += 1;
			}
			
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function showPlantPic(id_plantdoc,conf) {
	var resurl='include/plantation.php?elemid=show_plantation_picture&id_plantdoc='+id_plantdoc;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
			leselect = xhr.responseText; 
			var val = leselect.split('##');
			
			if(conf == 1) {
				if((val[0]!="")&&(val[1]!="")) {
					map2.setView([val[0],val[1]], 20); 
				}
			}
			
			document.getElementById('PlantationImagesDetails').innerHTML = '<b>'+val[3]+'</b><p>'+val[2]+'</p>';
		}
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
} 


function showContactHouseholdDetails(id_household,conf) {
	var resurl='include/contact.php?elemid=selected_household_details&id_household='+id_household+'&conf='+conf;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;  

			if(conf == 'contact'){
				document.getElementById('ct_household_content').innerHTML = leselect; 
			} else {
				document.getElementById('pt_household_content').innerHTML = leselect; 
			}
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function bySupplierContactId(id_company) {
	var resurl='listeslies.php?elemid=by_supplier_id_company&id_company='+id_company;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;
			var val = leselect.split('##');

			document.getElementById('req_quote_supplier_person_id').innerHTML = val[0];
			document.getElementById('req_quote_pol_id').innerHTML = val[1];

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function updatePlantationCertificate(id_plantation) {
	
	var req="";
	
	var globalgap = document.getElementById('cert_globalgap').value;
	if(globalgap){ req=req+'&globalgap='+globalgap; }
	
	var cert_approved_date = document.getElementById('cert_approved_date').value;
	if(cert_approved_date){ req=req+'&cert_approved_date='+cert_approved_date; }
	
	var cert_notes = document.getElementById('cert_notes').value;
	if(cert_notes){ req=req+'&cert_notes='+cert_notes; }
	

	var resurl='include/plantation.php?elemid=update_plantation_certificate&id_plantation='+id_plantation+req;  
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;   
			var val = leselect.split('##');

			if(val[0]==1){
				toastr.success(val[1],{timeOut:15000})
				showContactCertificationDetails(id_plantation);
			} else 
			if(val[0]==2){
				toastr.error(val[1],{timeOut:15000})
			} else {
				internal_error();
			}
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function updatePlantation(id_plantation,id_farmer,type) {
	
	var req="";
	
	var dc_completed = document.getElementById('dc_completed').value;
	if(dc_completed){ req=req+'&dc_completed='+dc_completed; }
	
	var bio = document.getElementById('plant_bio').value;
	if(bio){ req=req+'&bio='+bio; }
	
	var bio_suisse = document.getElementById('bio_suisse').value;
	if(bio_suisse){ req=req+'&bio_suisse='+bio_suisse; }
	
	var name_town = document.getElementById('name_town').value;
	if(name_town){ req=req+'&name_town='+name_town; }
	
	var area = document.getElementById('area').value;
	if(area){ req=req+'&area='+area; }
	
	var area_acres = document.getElementById('area_acres').value;
	if(area_acres){ req=req+'&area_acres='+area_acres; }
	
	var surface_ha = document.getElementById('surface_ha').value;
	if(surface_ha){ req=req+'&surface_ha='+surface_ha; }
	
	var year_creation = document.getElementById('year_creation').value;
	if(year_creation){ req=req+'&year_creation='+year_creation; }
	
	var variety = document.getElementById('variety').value;
	if(variety){ req=req+'&variety='+variety; }
	
	// var statut = document.getElementById('statut').value;
	// req=req+'&statut='+statut;
	
	var property = document.getElementById('property').value;
	if(property){ req=req+'&property='+property; }
	
	var title_deed = document.getElementById('title_deed').value;
	if(title_deed){ req=req+'&title_deed='+title_deed; }
	
	var perimeter = document.getElementById('perimeter').value;
	if(perimeter){ req=req+'&perimeter='+perimeter; }
	
	var eco_river = document.getElementById('eco_river').value;
	if(eco_river){ req=req+'&eco_river='+eco_river; }
	
	var eco_shallows = document.getElementById('eco_shallows').value;
	if(eco_shallows){ req=req+'&eco_shallows='+eco_shallows; }
	
	var eco_wells = document.getElementById('eco_wells').value;
	if(eco_wells){ req=req+'&eco_wells='+eco_wells; }
	
	var seed_type = document.getElementById('seed_type').value;
	if(seed_type){ req=req+'&seed_type='+seed_type; }
	
	var name_manager = document.getElementById('name_manager').value;
	if(name_manager){ req=req+'&name_manager='+name_manager; }
	
	var manager_phone = document.getElementById('manager_phone').value;
	if(manager_phone){ req=req+'&manager_phone='+manager_phone; }
	
	var inactive = document.getElementById('inactive').value;
	if(inactive){ req=req+'&inactive='+inactive; }
	
	var inactive_date = document.getElementById('inactive_date').value;
	if(inactive_date){ req=req+'&inactive_date='+inactive_date; }
	
	var notes = document.getElementById('notes').value;
	if(notes){ req=req+'&notes='+notes; }
	

	var resurl='include/contact.php?elemid=update_plantation&id_plantation='+id_plantation+req;  
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;  
			var val = leselect.split('##');

			if(val[0]==1){
				toastr.success(val[1],{timeOut:15000})
				showContact(id_farmer,type);
			} else 
			if(val[0]==2){
				toastr.error(val[1],{timeOut:15000})
			} else {
				internal_error();
			}
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function updateHousehold(id_household) {
	var req="";
	
	var firstname = document.getElementById('hh_firstname').value;
	if(firstname){ req=req+'&firstname='+firstname; }
	
	var lastname = document.getElementById('hh_lastname').value;
	if(lastname){ req=req+'&lastname='+lastname; }
	
	var birth_year = document.getElementById('hh_birth_year').value;
	if(birth_year){ req=req+'&birth_year='+birth_year; }
	
	var relation = document.getElementById('hh_relation').value;
	if(relation){ req=req+'&relation='+relation; }
	
	var graduate_primary = document.getElementById('hh_graduate_primary').value;
	if(graduate_primary){ req=req+'&graduate_primary='+graduate_primary; }
	
	var graduate_secondary = document.getElementById('hh_graduate_secondary').value;
	if(graduate_secondary){ req=req+'&graduate_secondary='+graduate_secondary; }
	
	var graduate_tertiary = document.getElementById('hh_graduate_tertiary').value;
	if(graduate_tertiary){ req=req+'&graduate_tertiary='+graduate_tertiary; }
	
	var working_on_farm = document.getElementById('hh_working_on_farm').value;
	if(working_on_farm){ req=req+'&working_on_farm='+working_on_farm; }
	
	var working_off_farm = document.getElementById('hh_working_off_farm').value;
	if(working_off_farm){ req=req+'&working_off_farm='+working_off_farm; }
	
	var gender = document.getElementById('hh_gender').value;
	if(gender){ req=req+'&gender='+gender; }
	
	var read_write = document.getElementById('hh_read_write').value;
	if(read_write){ req=req+'&read_write='+read_write; }
	
	var schooling = document.getElementById('hh_schooling').value;
	if(schooling){ req=req+'&schooling='+schooling; }
	
	var resurl='include/contact.php?elemid=update_household&id_household='+id_household+req;  
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;   
			var val = leselect.split('##');

			if(val[0]==1){
				toastr.success(val[1],{timeOut:15000})
				showContactHouseholdDetails(id_household,'contact');
			} else 
			if(val[0]==2){
				toastr.error(val[1],{timeOut:15000})
			} else {
				internal_error();
			}
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function top_canban() { 

	if ($('#toggle_top_canban').hasClass("toggleOpen")) { 
		document.getElementById('toggle_top_canban').innerHTML = 'open <i class="fa fa-chevron-circle-down"></i>';
		$('#canban_box').removeClass('fadeInDownBig');
		$('#canban_box').addClass('fadeOutUptBig');
		$('#canban_box').addClass('hide');

		$('#toggle_top_canban').removeClass("toggleOpen");
		$('#toggle_top_canban').addClass("fadeOutUptBig");
		$('#toggle_top_canban').removeClass("fadeInDownBig");

	} else { 
		document.getElementById('toggle_top_canban').innerHTML = 'close <i class="fa fa-chevron-circle-up"></i>';
	    $('#canban_box').removeClass('hide');
	    $('#canban_box').addClass('fadeInDownBig');
		$('#canban_box').removeClass('fadeOutUptBig');

		$('#toggle_top_canban').addClass("toggleOpen");
		$('#toggle_top_canban').removeClass("fadeOutUptBig");
		$('#toggle_top_canban').addClass("fadeInDownBig");
	}
}


function updateLinks(id) {
	var id_supchain_type = document.getElementById('id_supchain_type').value;

	var resurl='include/contact.php?elemid=update_links&id='+id+'&id_supchain_type='+id_supchain_type;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;
			var val = leselect.split('##');

			if(val[0]==1){
				toastr.success(val[1],{timeOut:15000})
			} else 
			if(val[0]==2){
				toastr.error(val[1],{timeOut:15000})
			} else {
				internal_error();
			}

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}

// L.control.layers(baseMaps, overlayMaps).addTo(org_map);

// function loadCtMap() { 

	// setTimeout(function() {
		// org_map.invalidateSize();
	// }, 1000); 
	
	// org_map.fitWorld().zoomIn();
// }

// function zoomMyLocation(x,y){
	// position_couche.clearLayers();
	// var mark = L.marker([x,y],{icon: agentIcon,riseOnHover:true}).addTo(position_couche);
	// org_map.addLayer(position_couche);
	// org_map.setView([x,y], 17);
// }


// L.control.layers(baseMaps, overlayMaps).addTo(home_ct_map);

// function loadContactMap(conf,id,x,y) {  

	// if conf = 0 contact creat form
	// if conf = 1 contact edit form
	
	// position_couche.clearLayers();
	
	// setTimeout(function() {
		// home_ct_map.invalidateSize();
	// }, 1000); 

	// home_ct_map.fitWorld().zoomIn();

	// home_ct_map.addLayer(googlemap);
	
	// home_ct_map.on('click', function(e) {   
		// position_couche.clearLayers();
		// var popLocation= e.latlng;
		// var popup = L.popup()
		// .setLatLng(popLocation)
		// .setContent('<p><b>x = </b>'+popLocation.lat+'<br /><b>y = </b>'+popLocation.lng+'</p>')
		// .openOn(home_ct_map);   

		// var mark = L.marker(e.latlng,{icon: agentIcon,riseOnHover:true}).addTo(position_couche);
		// home_ct_map.addLayer(position_couche);
		
		// if(conf == 0){
			// document.getElementById("cT_coordx").value = popLocation.lat;
			// document.getElementById("cT_coordy").value = popLocation.lng;
		// } else {
			// var x = popLocation.lat;
			// var y = popLocation.lng;
			
			// document.getElementById("modalContactMapFooter").innerHTML = '<button type="button" class="btn btn-primary" saveContactLocation(\''+x+'\',\''+y+'\',\''+id+'\');><i class="fa fa-save"></i></button>'
			// +'<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i></button>'; 
		// }
	// });
	
	// if(conf == 1){
		// if((x!=0)&&(y!=0)){ 
			// var mark = L.marker([x,y],{icon: agentIcon,riseOnHover:true}).addTo(position_couche);
			// home_ct_map.addLayer(position_couche);
			// home_ct_map.setView([x,y], 17); 
			
			// document.getElementById("modalContactMapFooter").innerHTML = '<button type="button" class="btn btn-primary" saveContactLocation(\''+x+'\',\''+y+'\',\''+id+'\');><i class="fa fa-save"></i></button>'
			// +'<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i></button>'; 
		// }
	// }
// }


// function saveContactLocation(x,y,id) {
	// alert(x);
	// alert(y);
	// alert(id);
// }


// function getMyLocation() {
    // if (navigator.geolocation) {
        // navigator.geolocation.getCurrentPosition(showMyPosition);
    // } else { 
        // alert("Geolocation is not supported by this browser.");
    // }
// }

// function showMyPosition(position) {
	// var my_x = position.coords.latitude;
	// var my_y = position.coords.longitude;

	// document.getElementById("cT_coordx").value = my_x;
	// document.getElementById("cT_coordy").value = my_y;
		
	// position_couche.clearLayers();
	// var popLocation= new L.LatLng(my_x,my_y);
	// var popup = L.popup()
	// .setLatLng(popLocation)
	// .setContent('<p>Your Location<br /><b>x = </b>'+my_x+'<br /><b>y = </b>'+my_y+'</p>')
	// .openOn(home_ct_map);   

	// var mark = L.marker(popLocation,{icon: agentIcon,riseOnHover:true}).addTo(position_couche);
	// home_ct_map.addLayer(position_couche);

	// home_ct_map.setView(popLocation, 17);
// }


function addContactForm() { 
	$("#modalAddContact").modal("show");
	
	$('.edit_delivery_date').datepicker({
		format: "yyyy/mm/dd",
		calendarWeeks:true,
		autoclose: true
	});
}

function addCoopForm() { 
	$("#modalAddCoop").modal("show");
	
	$('.edit_delivery_date').datepicker({
		format: "yyyy/mm/dd",
		calendarWeeks:true,
		autoclose: true
	});
}


function saveCoop() {
	var req = "";
	
	var firstname = document.getElementById('coop_firstname').value;  
	if(firstname){ req=req+'&firstname='+firstname.toUpperCase(); }
	
	var lastname = document.getElementById('coop_lastname').value;  
	if(lastname){ req=req+'&lastname='+lastname; }
	
	var national_lang = document.getElementById('coop_national_lang').value;  
	if(national_lang){ req=req+'&national_lang='+national_lang; }
	
	var p_phone = document.getElementById('coop_p_phone').value;  
	if(p_phone){ req=req+'&p_phone='+p_phone; }
	
	var p_phone2 = document.getElementById('coop_p_phone2').value;  
	if(p_phone2){ req=req+'&p_phone2='+p_phone2; }
	
	var p_phone3 = document.getElementById('coop_p_phone3').value;  
	if(p_phone3){ req=req+'&p_phone3='+p_phone3; }
	
	var p_phone4 = document.getElementById('coop_p_phone4').value;  
	if(p_phone4){ req=req+'&p_phone4='+p_phone4; }
	
	var p_phone5 = document.getElementById('coop_p_phone5').value;  
	if(p_phone5){ req=req+'&p_phone5='+p_phone5; }
	
	var bankname = document.getElementById('coop_bankname').value;  
	if(bankname){ req=req+'&bankname='+bankname; }
	
	var p_email = document.getElementById('coop_p_email').value;  
	if(p_email){ req=req+'&p_email='+p_email; }
	
	var postalcode = document.getElementById('coop_postalcode').value;  
	if(postalcode){ req=req+'&postalcode='+postalcode; }
	
	var p_street = document.getElementById('coop_p_street').value;  
	if(p_street){ req=req+'&p_street='+p_street; }
	
	var town_name = document.getElementById('coop_town_name').value;  
	if(town_name){ req=req+'&town_name='+town_name; }
	
	var notes = document.getElementById('coop_notes').value;  
	if(notes){ req=req+'&notes='+notes; }
	
	var resurl='listeslies.php?elemid=register_new_cooperative'+req;    //console.log(resurl);
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;     console.log(leselect);

			if(leselect==1){
				toastr.success('Cooperative added successfully',{timeOut:15000})
				document.getElementById("addCooperativeForm").reset();
				$("#modalAddCoop").modal("hide");
				contactList(10,0,0,0,0);
			} else 
			if(leselect==0){
				toastr.error('Cooperative not added',{timeOut:15000})
			} else {
				internal_error();
			}
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function saveContact() {
	
	var req = "";
	
	var lastname = document.getElementById('cT_lastname').value;  
	if(lastname){ req=req+'&lastname='+lastname.toUpperCase(); }
	
	var firstname = document.getElementById('cT_firstname').value; 
	if(firstname){ req=req+'&firstname='+firstname+'&name='+lastname.toUpperCase()+' '+firstname; }

	var middlename = document.getElementById('cT_middlename').value;  
	if(middlename){ req=req+'&middlename='+middlename; }
	
	// var nickname = document.getElementById('nickname').value;
	// if(nickname){ req=req+'&nickname='+nickname; }
	
	var gender = document.getElementById('cT_gender').value; 
	if(gender){ req=req+'&gender='+gender; }
	
	var birthday = document.getElementById('cT_birthday').value; 
	if(birthday){ var bdate = birthday.split('/');
		var birth_year = bdate[0];
		var birth_date = birthday;
	req=req+'&birth_date='+birth_date+'&birth_year='+birth_year; }
	
	var national_lang = document.getElementById('cT_national_lang').value; 
	if(national_lang){ req=req+'&national_lang='+national_lang; }
	
	var primary_company = document.getElementById('cT_contact_primary_company').value;  
	if(primary_company){ req=req+'&primary_company='+primary_company; }
	
	// var street = document.getElementById('street').value;
	// if(street){ req=req+'&street='+street; }
	
	var p_street = document.getElementById('cT_p_street').value; 
	if(p_street){ req=req+'&p_street='+p_street; }
	
	// var p_street1 = document.getElementById('p_street1').value;
	// if(p_street1){ req=req+'&p_street1='+p_street1; }
	
	var town_name_id = document.getElementById('cT_town_name').value;  
	if(town_name_id){ 
		var data = town_name_id.split("@");
		req=req+'&id_town='+data[0]; 
		req=req+'&town_name='+data[1]; 
	}
	
	// var name_country = document.getElementById('name_country').value;
	// if(name_country){ req=req+'&name_country='+name_country; }
	
	// var p_postalcode = document.getElementById('cT_postalcode').value;  
	// if(p_postalcode){ req=req+'&p_postalcode='+p_postalcode; }
	
	// var coordx = document.getElementById('cT_coordx').value;
	// if(coordx){ req=req+'&coordx='+coordx; }
	
	// var coordy = document.getElementById('cT_coordy').value;
	// if(coordy){ req=req+'&coordy='+coordy; }
	
	var notes = document.getElementById('cT_notes').value;  
	if(notes){ req=req+'&notes='+notes; }
	
	var p_phone = document.getElementById('cT_p_phone').value; 
	if(p_phone){ req=req+'&p_phone='+p_phone; }
	
	var p_phone2 = document.getElementById('cT_p_phone2').value;  
	if(p_phone2){ req=req+'&p_phone2='+p_phone2; }
	
	var p_phone3 = document.getElementById('cT_p_phone3').value; 
	if(p_phone3){ req=req+'&p_phone3='+p_phone3; }
	
	var p_phone4 = document.getElementById('cT_p_phone4').value;  
	if(p_phone4){ req=req+'&p_phone4='+p_phone4; }
	
	var p_phone5 = document.getElementById('cT_p_phone5').value;  
	if(p_phone5){ req=req+'&p_phone5='+p_phone5; }
	
	var p_email = document.getElementById('cT_p_email').value; 
	if(p_email){ req=req+'&p_email='+p_email; }
	
	var p_email2 = document.getElementById('cT_p_email2').value; 
	if(p_email2){ req=req+'&p_email2='+p_email2; }
	
	// var p_email3 = document.getElementById('cT_p_email3').value;
	// if(p_email3){ req=req+'&p_email3='+p_email3; }
	
	var skype_id = document.getElementById('cT_skype_id').value; 
	if(skype_id){ req=req+'&skype_id='+skype_id; }
	
	var id_supchain_type = document.getElementById('cT_id_supchain_type').value;  
	if(id_supchain_type){ req=req+'&id_supchain_type='+id_supchain_type; }
	
	var agent_type = document.getElementById('cT_agent_type').value;  
	if(agent_type){ req=req+'&agent_type='+agent_type; }
	

	var resurl='listeslies.php?elemid=register_new_contact'+req;    
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;    

			if(leselect==1){
				toastr.success('User added successfully',{timeOut:15000})
				document.getElementById("addContactForm").reset();
				$("#modalAddContact").modal("hide");
				refreshContactList(primary_company);
			} else 
			if(leselect==0){
				toastr.error('User not added',{timeOut:15000})
			} else {
				internal_error();
			}
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function refreshContactList(id_primary_company){

	var resurl='include/contact.php?elemid=refresh_contact_list&id_primary_company='+id_primary_company+'&update_right='+contppl_update;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;

			document.getElementById('comp_contactList').innerHTML = leselect;

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function updateProfil(id,type) {
	var req='';
	
	var contact_code=document.getElementById('ctE_contact_code').value;
	if(contact_code){ req=req+'&contact_code='+contact_code; }
	
	var mobile_created=document.getElementById('ctE_mobile_created').value;
	if(mobile_created){ req=req+'&mobile_created='+mobile_created; }
	
	var firstname=document.getElementById('ctE_firstname').value;
	if(firstname){ req=req+'&firstname='+firstname; }
	
	var lastname=document.getElementById('ctE_lastname').value;
	if(lastname){ req=req+'&lastname='+lastname.toUpperCase()+'&name='+lastname.toUpperCase()+' '+firstname; }
	
	var middlename=document.getElementById('ctE_middlename').value;
	if(middlename){ req=req+'&middlename='+middlename; }
	
	var p_phone=document.getElementById('ctE_p_phone').value;
	if(p_phone){ req=req+'&p_phone='+p_phone; }
	
	var p_phone2=document.getElementById('ctE_p_phone2').value;
	if(p_phone2){ req=req+'&p_phone2='+p_phone2; }
	
	var p_phone3=document.getElementById('ctE_p_phone3').value;
	if(p_phone3){ req=req+'&p_phone3='+p_phone3; }
	
	var p_phone4=document.getElementById('ctE_p_phone4').value;
	if(p_phone4){ req=req+'&p_phone4='+p_phone4; }
	
	var p_phone5=document.getElementById('ctE_p_phone5').value;
	if(p_phone5){ req=req+'&p_phone5='+p_phone5; }
	
	var bankname=document.getElementById('ctE_bankname').value;
	if(bankname){ req=req+'&bankname='+bankname; }
	
	var p_email=document.getElementById('ctE_p_email').value;
	if(p_email){ req=req+'&p_email='+p_email; }
	
	var p_email2=document.getElementById('ctE_p_email2').value;
	if(p_email2){ req=req+'&p_email2='+p_email2; }
	
	var p_email3=document.getElementById('ctE_p_email3').value;
	if(p_email3){ req=req+'&p_email3='+p_email3; }
	
	var skype_id=document.getElementById('ctE_skype_id').value;
	if(skype_id){ req=req+'&skype_id='+skype_id; }
	
	var p_street=document.getElementById('ctE_p_street').value;
	if(p_street){ req=req+'&p_street='+p_street; }
	
	var town_name_id=document.getElementById('ctE_town_name').value;
	if(town_name_id){ 
		var data = town_name_id.split("@");
		req=req+'&id_town='+data[0]; 
		req=req+'&town_name='+data[1]; 
	}
	
	var postalcode=document.getElementById('ctE_postalcode').value;
	if(postalcode){ req=req+'&postalcode='+postalcode; }
	
	var notes=document.getElementById('ctE_notes').value;
	if(notes){ req=req+'&notes='+notes; }
	
	var gender=document.getElementById('ctE_gender').value;
	if(gender){ req=req+'&gender='+gender; }
	
	var birthday=document.getElementById('ctE_birthday').value;
	if(birthday){ var bdate = birthday.split('/');
		var birth_year = bdate[0];
		var birth_date = birthday;
	req=req+'&birth_date='+birth_date+'&birth_year='+birth_year; }

	var national_lang=document.getElementById('ctE_national_lang').value;
	if(national_lang){ req=req+'&national_lang='+national_lang; }
	
	var agent_type=document.getElementById('ctE_agent_type').value;
	if(agent_type){ req=req+'&agent_type='+agent_type; }
	
	
	var resurl='include/contact.php?elemid=update_profil&id='+id+req;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;  
			var val = leselect.split('##');

			if(val[0]==1){
				toastr.success(val[1],{timeOut:15000})
				CancelEditBio();
				showContact(id,type);
			} else 
			if(val[0]==0){
				toastr.error(val[1],{timeOut:15000})
			} else {
				internal_error();
			}
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function updateProfil2(id) {
	var req='';
	
	var firstname=document.getElementById('ctE2_firstname').value;
	if(firstname){ req=req+'&firstname='+firstname; }
	
	var lastname=document.getElementById('ctE2_lastname').value;
	if(lastname){ req=req+'&lastname='+lastname.toUpperCase()+'&name='+lastname.toUpperCase()+' '+firstname; }
	
	var middlename=document.getElementById('ctE2_middlename').value;
	if(middlename){ req=req+'&middlename='+middlename; }
	
	var p_phone=document.getElementById('ctE2_p_phone').value;
	if(p_phone){ req=req+'&p_phone='+p_phone; }
	
	var p_phone2=document.getElementById('ctE2_p_phone2').value;
	if(p_phone2){ req=req+'&p_phone2='+p_phone2; }
	
	var p_phone3=document.getElementById('ctE2_p_phone3').value;
	if(p_phone3){ req=req+'&p_phone3='+p_phone3; }
	
	var p_phone4=document.getElementById('ctE2_p_phone4').value;
	if(p_phone4){ req=req+'&p_phone4='+p_phone4; }
	
	var p_phone5=document.getElementById('ctE2_p_phone5').value;
	if(p_phone5){ req=req+'&p_phone5='+p_phone5; }
	
	var p_email=document.getElementById('ctE2_p_email').value;
	if(p_email){ req=req+'&p_email='+p_email; }
	
	var p_email2=document.getElementById('ctE2_p_email2').value;
	if(p_email2){ req=req+'&p_email2='+p_email2; }
	
	var skype_id=document.getElementById('ctE2_skype_id').value;
	if(skype_id){ req=req+'&skype_id='+skype_id; }
	
	var p_street=document.getElementById('ctE2_p_street').value;
	if(p_street){ req=req+'&p_street='+p_street; }
	
	var town_name_id=document.getElementById('ctE2_town_name').value;
	if(town_name_id){ 
		var data = town_name_id.split("@");
		req=req+'&id_town='+data[0]; 
		req=req+'&town_name='+data[1]; 
	}
	
	var postalcode=document.getElementById('ctE2_postalcode').value;
	if(postalcode){ req=req+'&postalcode='+postalcode; }
	
	var notes=document.getElementById('ctE2_notes').value;
	if(notes){ req=req+'&notes='+notes; }
	
	var gender=document.getElementById('ctE2_gender').value;
	if(gender){ req=req+'&gender='+gender; }
	
	var birthday=document.getElementById('ctE2_birthday').value;
	if(birthday){ var bdate = birthday.split('/');
		var birth_year = bdate[0];
		var birth_date = birthday;
	req=req+'&birth_date='+birth_date+'&birth_year='+birth_year; }
	
	var national_lang=document.getElementById('ctE2_national_lang').value;
	if(national_lang){ req=req+'&national_lang='+national_lang; }
	
	
	var resurl='include/contact.php?elemid=update_profil&id='+id+req;   
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText; 
			var val = leselect.split('##');

			if(val[0]==1){
				toastr.success(val[1],{timeOut:15000})
			} else 
			if(val[0]==0){
				toastr.error(val[1],{timeOut:15000})
			} else {
				internal_error();
			}

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function getAFSContract(id_plantation,id_contact) {
	$("#afsContractDateModal").modal("show");
	
	$('.edit_delivery_date').datepicker({
					format: "yyyy/mm/dd",
					calendarWeeks:true,
					autoclose: true
				});
				
	document.getElementById('afsContractDateFooter').innerHTML = '<button type="button" class="btn btn-primary pull-left" onclick="afs_contract_pdf(\''+id_plantation+'\',\''+id_contact+'\');" data-dismiss="modal"> Use</button>'
	+'<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Close</button>';
}


function afs_contract_pdf(id_plantation,id_contact) {
	$("#bookingDocModal").modal("show");

	var doc_date = document.getElementById('afsContract_date').value;
	var ficheurl='pdf/engagement_pmci.php?id_plantation='+id_plantation+'&id_contact='+id_contact+'&doc_date='+doc_date; 
	
	document.getElementById('booking_document_show').innerHTML = '<div><iframe src="'+ficheurl+'&save=0" style="width:100%; height:500px;"></iframe></div>';
	document.getElementById('booking_document_footer').innerHTML = '<a href="https://icoop.live/ic/'+ficheurl+'&save=1" onclick="showContactCertificationDetails('+id_plantation+');" target="_blank" class="btn btn-info pull-left"><i class="fa fa-file-pdf-o"></i>&nbsp;Save</a>'
		+'<button type="button" class="btn btn-danger pull-right" data-dismiss="modal"><i class="fa fa-ban"></i> Close</button>';
}


function deleteEngagementDoc(id_plantation,id_plantdoc) {
	var resurl='include/plantation.php?elemid=delete_plantation_document&id_plantdoc='+id_plantdoc;  
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;   

			if(leselect==1){
				toastr.success('Document successfully deleted',{timeOut:15000})
				showContactCertificationDetails(id_plantation);
			} else 
			if(leselect==0){
				toastr.error('Document not deleted',{timeOut:15000})
			} else {
				internal_error();
			}
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function organisationDetails(id) {
	
	var resurl='include/contact.php?elemid=organisation_details&id_contact='+id;
    var xhr = getXhr();   
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;  

			document.getElementById('rightInfos').innerHTML = leselect;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function showContact(id,type) {
	
	var req="";
	
	if(type == 9){
		// Bio
		if(contpplbio_read == 1) { $("#bio_tab").removeClass("hide"); $("#bio_tab_content").removeClass("hide"); 
			req=req+'&bio_create='+contpplbio_create+'&bio_update='+contpplbio_update+'&bio_delete='+contpplbio_delete;
		}
		// Demography
		if(contppldemog_read == 1) { $("#demog_tab").removeClass("hide"); $("#demog_tab_content").removeClass("hide"); 
			req=req+'&demog_create='+contppldemog_create+'&demog_update='+contppldemog_update+'&demog_delete='+contppldemog_delete;
		}
		// Links
		if(contppllinks_read == 1) { $("#links_tab").removeClass("hide"); $("#links_tab_content").removeClass("hide"); 
			req=req+'&links_create='+contppllinks_create+'&links_update='+contppllinks_update+'&links_delete='+contppllinks_delete;
		}
		// Plantation
		if(contpplplant_read == 1) { $("#plantation_tab").removeClass("hide"); $("#plantation_tab_content").removeClass("hide");
			req=req+'&plant_create='+contpplplant_create+'&plant_update='+contpplplant_update+'&plant_delete='+contpplplant_delete;
		}
		
		// Household
		$("#household_tab").addClass("hide"); 
		$("#household_tab_content").addClass("hide");
		
		// Manager
		$("#plantManager_tab").addClass("hide"); 
		$("#plantManager_tab_content").addClass("hide");
		
		// Certification
		$("#certification_tab").addClass("hide"); 
		$("#certification_tab_content").addClass("hide");
		
		// Environment
		$("#environment_tab").addClass("hide"); 
		$("#environment_tab_content").addClass("hide");
		
		// Business & Financials
		$("#busiFinace_tab").addClass("hide"); 
		$("#busiFinace_tab_content").addClass("hide");
		
		// Activity Log
		$("#activityLog_tab").addClass("hide"); 
		$("#activityLog_tab_content").addClass("hide");
		
	} else
	if(type == 10) {
		// Bio
		if(contorgbio_read == 1) { $("#bio_tab").removeClass("hide"); $("#bio_tab_content").removeClass("hide"); 
			req=req+'&bio_create='+contorgbio_create+'&bio_update='+contorgbio_update+'&bio_delete='+contorgbio_delete;
		}
		// Links
		if(contorglinks_read == 1) { $("#links_tab").removeClass("hide"); $("#links_tab_content").removeClass("hide"); 
			req=req+'&links_create='+contorglinks_create+'&links_update='+contorglinks_update+'&links_delete='+contorglinks_delete;
		}
		
		// Plantation
		$("#plantation_tab").addClass("hide"); 
		$("#plantation_tab_content").addClass("hide");
		
		// Household
		$("#household_tab").addClass("hide"); 
		$("#household_tab_content").addClass("hide");
		
		// Manager
		$("#plantManager_tab").addClass("hide"); 
		$("#plantManager_tab_content").addClass("hide");
		
		// Certification
		$("#certification_tab").addClass("hide"); 
		$("#certification_tab_content").addClass("hide");
		
		// Environment
		$("#environment_tab").addClass("hide"); 
		$("#environment_tab_content").addClass("hide");
		
		// Business & Financials
		$("#busiFinace_tab").addClass("hide"); 
		$("#busiFinace_tab_content").addClass("hide");
		
		// Activity Log
		$("#activityLog_tab").addClass("hide"); 
		$("#activityLog_tab_content").addClass("hide");
		
		
		$('#right-sidebar').addClass('hide');

		$('#sideBarBtnToggle').removeClass("toggleOpen");
		$('#sideBarBtnToggle').removeClass('hide');
	
		if(user_culture == 496) {
			volet_droit_animated();
			organisationDetails(id);
			$('#sideBarBtnToggle').removeClass('hide');
		} else {
			$('#sideBarBtnToggle').addClass('hide');
		}
	
	} else {
		
		// Bio
		if(contpplbio_read == 1) { $("#bio_tab").removeClass("hide"); $("#bio_tab_content").removeClass("hide"); 
			req=req+'&bio_create='+contpplbio_create+'&bio_update='+contpplbio_update+'&bio_delete='+contpplbio_delete;
		}
		// Demography
		if(contppldemog_read == 1) { $("#demog_tab").removeClass("hide"); $("#demog_tab_content").removeClass("hide"); 
			req=req+'&demog_create='+contppldemog_create+'&demog_update='+contppldemog_update+'&demog_delete='+contppldemog_delete;
		}
		// Links
		if(contppllinks_read == 1) { $("#links_tab").removeClass("hide"); $("#links_tab_content").removeClass("hide"); 
			req=req+'&links_create='+contppllinks_create+'&links_update='+contppllinks_update+'&links_delete='+contppllinks_delete;
		}
		
		if(contpplplant_read == 1) { 
			req=req+'&plant_create='+contpplplant_create+'&plant_update='+contpplplant_update+'&plant_delete='+contpplplant_delete;
		}
		
		// Plantation
		$("#plantation_tab").removeClass("hide"); 
		$("#plantation_tab_content").removeClass("hide");
		
		// Household
		$("#household_tab").removeClass("hide"); 
		$("#household_tab_content").removeClass("hide");
		
		// Manager
		$("#plantManager_tab").removeClass("hide"); 
		$("#plantManager_tab_content").removeClass("hide");
		
		// Certification
		$("#certification_tab").removeClass("hide"); 
		$("#certification_tab_content").removeClass("hide");
		
		// Environment
		$("#environment_tab").removeClass("hide"); 
		$("#environment_tab_content").removeClass("hide");
		
		// Business & Financials
		$("#busiFinace_tab").removeClass("hide"); 
		$("#busiFinace_tab_content").removeClass("hide");
		
		// Activity Log
		$("#activityLog_tab").removeClass("hide"); 
		$("#activityLog_tab_content").removeClass("hide");
	}
	
	document.getElementById('allPlantBtn').innerHTML = "";
	
	var resurl='include/contact.php?elemid=show_contact&id='+id+'&type='+type+req+'&update_right='+contppl_update;   
    var xhr = getXhr();
	xhr.onreadystatechange = function(){  
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;  
			var val = leselect.split('@@');

			contactTabMap(id);
			contactWharehouse(id);
			contactTabTown(id);
			
			document.getElementById('bio').innerHTML = val[0];
			document.getElementById('contactTabDocs').innerHTML = val[11];
			
			document.getElementById('demog').innerHTML = val[1];  
			document.getElementById('links').innerHTML = val[2];
			document.getElementById('plantation').innerHTML = val[3];
			
			// Plantation Manager
			document.getElementById('plantManager_ct').innerHTML = val[16];
			
			$('#plantManager_content li').click(function() {
				$('ul li.on8').removeClass('on8');
				$(this).closest('li').addClass('on8');
			});
			
			if(type == 115){
				contactPlantationTab(val[10],type);
				showContactPlantationManagerDetails(val[10]);
				showContactCertificationDetails(val[10]);
				showContactEnvironmentDetails(val[10]);
				showContactbusiFinaceDetails(val[10]);
				
				document.getElementById('allPlantBtn').innerHTML = '<button class="btn btn-primary btn-sm" onclick="showAllFarmerPlant(\''+id+'\');"> Show All plantations</button>';
			}
			
			loadGallery(true, 'a.thumbnail'); 

			if(contppldemog_read == 1) {
				if(val[1]!=''){
					$("#demog_tab").removeClass("hide"); $("#demog_tab_content").removeClass("hide");
				} else {
					$("#demog_tab").addClass("hide"); $("#demog_tab_content").addClass("hide");
				}
			}

			if(contpplplant_read == 1) {
				if(val[3]!=''){
					$("#platation_tab").removeClass("hide"); $("#platation_tab_content").removeClass("hide");
				} else {
					$("#platation_tab").addClass("hide"); $("#platation_tab_content").addClass("hide");
				}
			} 

			if(type == 10){ 
				document.getElementById('cT_selected_company').value = val[4]; 
				document.getElementById('cT_id_supchain_type').value = val[7]; 
				document.getElementById('cT_contact_primary_company').value = id;  
			}
			
			var options = {
				valueNames: ['contracting_party']
			};

			var contractingList = new List('c_party', options);
			
			$('#contracting_content li').click(function() {
				$('ul li.on2').removeClass('on2');
				$(this).closest('li').addClass('on2');
			});
			
			$('#plantation_content li').click(function() {
				$('ul li.on3').removeClass('on3');
				$(this).closest('li').addClass('on3');
			});

			// Household
			
			document.getElementById('household').innerHTML = val[8];
			showContactHouseholdDetails(val[9],'contact');
			
			// document.getElementById('household_details').innerHTML = val[9];
	
			$('#household_list_content li').click(function() {
				$('ul li.on4').removeClass('on4');
				$(this).closest('li').addClass('on4');
			});
			
			
			$('.rotate-btn').on('click', function () {
				var cardId = $(this).attr('data-card');
				$('#' + cardId).toggleClass('flipped');
			});
			
			// Certification
			document.getElementById('certification_ct').innerHTML = val[12];
			
			$('#certification_content li').click(function() {
				$('ul li.on5').removeClass('on5');
				$(this).closest('li').addClass('on5');
			});
			
			// Environement
			document.getElementById('environment_ct').innerHTML = val[13];
			
			$('#environment_content li').click(function() {
				$('ul li.on6').removeClass('on6');
				$(this).closest('li').addClass('on6');
			});
			
			// Business Finance
			document.getElementById('busiFinace_ct').innerHTML = val[14];
			
			$('#busiFinace_content li').click(function() {
				$('ul li.on7').removeClass('on7');
				$(this).closest('li').addClass('on7');
			});
			
			// Activity Log
			document.getElementById('activityLog_ct').innerHTML = val[15];
			document.getElementById('list_activityLog').innerHTML = val[17]; 
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function showAllFarmerPlant(id_farmer) {
	plantation_couche_farmer.clearLayers();
	plantation_points.clearLayers();
	
	var resurl='include/plantation.php?elemid=show_all_farmer_plantations&id_farmer='+id_farmer;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){  
        if(xhr.readyState == 4 ){
			leselect = xhr.responseText;    //console.log(leselect);  
	
			map2.invalidateSize();
			
			var plantations = JSON.parse(leselect);  console.log(plantations);

			plantation_couche_farmer.addData(plantations);	 
			map2.addLayer(plantation_couche_farmer);   
			map2.fitBounds(plantation_couche_farmer.getBounds());

        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function updateCheckOut(conf,value,id,type) {
	
	if(conf == 'contact'){
		var resurl='include/contact.php?elemid=check_out&id_contact='+id+'&check_out='+value; 
	} else {
		var resurl='include/plantation.php?elemid=check_out&id_plantation='+id+'&check_out='+value; 
	} 
	
    var xhr = getXhr();
	xhr.onreadystatechange = function(){  
        if(xhr.readyState == 4 ){
			leselect = xhr.responseText;    //console.log(leselect);
			if(leselect == 1) {
				toastr.success('Data updated successfully',{timeOut:15000})
				if(conf == 'contact'){
					showContact(id,type);
				} else {
					showContactPlantationDetails(id,'');
				}
				
			} else {
				toastr.error('Error updating data.',{timeOut:15000}) 
			}
		}
	}
	
	xhr.open("GET",resurl,true);
	xhr.send(null);
}


L.control.layers(baseMaps_home_ct, overlayMaps).addTo(home_ct_map);

function contactTabMap(id_contact) { 

	homeMap_couche.clearLayers();
	
	var resurl='include/contact.php?elemid=location_markers&id_contact='+id_contact;  
    var xhr = getXhr();
	xhr.onreadystatechange = function(){  
        if(xhr.readyState == 4 ){
			leselect = xhr.responseText;  
			var val = leselect.split('@@');
			
			if(val[0]!="") {
				mark = L.marker([val[0],val[1]], {icon: homeMarker,riseOnHover:true})
					.bindPopup('Home')
					.addTo(homeMap_couche);
					
				home_ct_map.addLayer(homeMap_couche);
				
				home_ct_map.setView([val[0],val[1]], 16); 
				// home_ct_map.fitBounds(homeMap_couche.getBounds().extend(wareHouse_couche.getBounds()));
				
			} else {
				home_ct_map.fitWorld().zoomIn();
			}
		}
	}
	
	xhr.open("GET",resurl,true);
	xhr.send(null);
}


function contactWharehouse(id_contact) { 

	wareHouse_couche.clearLayers();
	
	var resurl='include/plantation.php?elemid=wharehouse&id_contact='+id_contact;  
    var xhr = getXhr();
	xhr.onreadystatechange = function(){  
        if(xhr.readyState == 4 ){
			leselect = xhr.responseText;   
			var val = leselect.split('@@');
			
			if(leselect) {
				i=0;
				while (val[i] !='end') {
					
					var data = val[i].split('##');
					
					if((data[0]!="")&&(data[1]!="")){
						mark = L.marker([data[0], data[1]], {icon: warehouseMarker,riseOnHover:true})
							.bindPopup('Wharehouse')
							.addTo(wareHouse_couche);
						home_ct_map.addLayer(wareHouse_couche);
					}
					
					i += 1;
				}
			}
		}
	}
	
	xhr.open("GET",resurl,true);
	xhr.send(null);
}


function contactTabTown(id_contact) { 

	farmerTown_couche.clearLayers();
	label_towns.clearLayers();
	
	var resurl='include/contact.php?elemid=farmer_town_marker&id_contact='+id_contact;  
    var xhr = getXhr();
	xhr.onreadystatechange = function(){  
        if(xhr.readyState == 4 ){
			leselect = xhr.responseText;   //console.log(leselect);
			var elt = leselect.split('##');
			
			if((elt[1]!="")&&(elt[2]!="")) {
				var mark = L.marker([elt[1], elt[2]],{icon: townIcon,riseOnHover:true}).bindPopup(elt[0]).addTo(farmerTown_couche); 
		
				var divIcon = L.divIcon({ 
					className: "labelClass",
					iconAnchor:[-15,25],
					html: elt[0]
				});

				var mark2 = L.marker([elt[1], elt[2]], {icon: divIcon }).addTo(label_towns);
				
				home_ct_map.addLayer(farmerTown_couche);
				home_ct_map.addLayer(label_towns);
				
				home_ct_map.setView([elt[1], elt[2]], 14);
			} 

		}
	}
	
	xhr.open("GET",resurl,true);
	xhr.send(null);
}


function contactPlantationTab(id_plantation,type) { 
	current_plantation_id = id_plantation;
	showContactPlantationDetails(id_plantation,type);
}


// Contract CARD
// ** Start Contract ** 

function newContractForm(conf,id_contact) {
	
	clearContractForm();
	
	$("#contractModal").modal("show");
	if(conf == "new"){
		document.getElementById('contractModalLabel').innerHTML = lg_contract_modal_title_create;
	} else {
		document.getElementById('contractModalLabel').innerHTML = lg_contract_modal_title_edit;
	}
	
	var resurl='include/contact.php?elemid=new_contract&id_contact='+id_contact;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;  
			var val = leselect.split('##');
			
			document.getElementById('contractModal_id_contracting_party').innerHTML = val[0];
			document.getElementById('contractModal_id_contract_type').innerHTML = val[1];
			document.getElementById('contractModal_contractor_box').innerHTML = val[2];
			
			if(conf=="show"){
				document.getElementById('contractModalFooter').innerHTML = '';
			} else {
				document.getElementById('contractModalFooter').innerHTML = '<button type="button" class="btn btn-primary" onclick="saveContract(\''+id_contact+'\');"><i class="fa fa-save"></i></button>'
				+'<button type="button" class="btn btn-danger" data-dismiss="modal" onclick="clearContractForm();"><i class="fa fa-ban"></i></button>';
			}
			
			$('.chosen-select').chosen({width: "100%"});
			
			$('.edit_delivery_date').datepicker({
				format: "yyyy/mm/dd",
				calendarWeeks:true,
				autoclose: true
			}).datepicker('setDate', new Date());
	
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function saveContract(id_contact) {
	
	var req="";
	var id_contractor=document.getElementById('contractModal_id_contractor').value;
	if(id_contractor){ req=req+'&id_contractor='+id_contractor; }
	
	var id_contracting_party=document.getElementById('contractModal_id_contracting_party').value;
	if(id_contracting_party){ req=req+'&id_contracting_party='+id_contracting_party; }
	
	var contract_code=document.getElementById('contractModal_contract_code').value;
	if(contract_code){ req=req+'&contract_code='+contract_code; }
	
	var id_contract_type=document.getElementById('contractModal_id_contract_type').value;
	if(id_contract_type){ req=req+'&id_contract_type='+id_contract_type; }
	
	var contract_date=document.getElementById('contractModal_contract_date').value;
	if(contract_date){ req=req+'&contract_date='+contract_date; }
	
	var start_date=document.getElementById('contractModal_start_date').value;
	if(start_date){ req=req+'&start_date='+start_date; }
	
	var end_date=document.getElementById('contractModal_end_date').value;
	if(end_date){ req=req+'&end_date='+end_date; }
	
	var contract_desc=document.getElementById('contractModal_contract_desc').value;
	if(contract_desc){ req=req+'&contract_desc='+contract_desc; }
	
	if(id_contracting_party==""){
		toastr.info('Select contracting party.',{timeOut:15000})
	} else 
	if(start_date==""){
		toastr.info('Enter a start date.',{timeOut:15000})
	} else 
	if(end_date==""){
		toastr.info('Enter a end date.',{timeOut:15000})
		
	} else {
		var resurl='include/contact.php?elemid=contract_management&conf=add'+req;    
		var xhr = getXhr();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;      
				
				if(leselect == 1){
					toastr.success('Contract successfully saved',{timeOut:15000})
					$("#contractModal").modal("hide");
					contractList(id_contact);
				
				} else 
				if(leselect == 0){
					toastr.error('Contract not saved, please retry!',{timeOut:15000})
				} else {
					internal_error();
				}
				
				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
	} 
}


function clearContractForm() {
	$('#contractModalForm').find("input, textarea, select").val(""); 
	$('#contractModal_id_contracting_party').val("");
	$('#contractModal_id_contracting_party').trigger("chosen:updated");
}

function contractList(id_contact) {
	var resurl='include/contact.php?elemid=has_relation_list&id_contact='+id_contact;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;

			document.getElementById('contracting_content').innerHTML = leselect;
			
			var options = {
				valueNames: ['contracting_party']
			};

			var contractingList = new List('c_party', options);
			
			$('#contracting_content li').click(function() {
				$('ul li.on2').removeClass('on2');
				$(this).closest('li').addClass('on2');
			});
			
			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}

function editContract(id_contract,id_contact) {
	newContractForm('show',id_contact);  
	
	setTimeout(function(){
        var resurl='include/contact.php?elemid=show_contract_details&id_contract='+id_contract;  
		var xhr = getXhr();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;   
				var elt = leselect.split('##');

				document.getElementById('contractModal_id_contractor').value = elt[0];
				$('#contractModal_id_contracting_party').val(elt[1]);
				$('#contractModal_id_contracting_party').trigger("chosen:updated");
		
				document.getElementById('contractModal_contract_code').value = elt[2];
				document.getElementById('contractModal_id_contract_type').value = elt[3];
				document.getElementById('contractModal_contract_date').value = elt[4];
				document.getElementById('contractModal_start_date').value = elt[5];
				document.getElementById('contractModal_end_date').value = elt[6];
				document.getElementById('contractModal_contract_desc').value = elt[7];

				document.getElementById('contractModalFooter').innerHTML = '<button type="button" class="btn btn-primary" onclick="saveEditedContract(\''+id_contract+'\',\''+id_contact+'\');"><i class="fa fa-save"></i></button>'
					+'<button type="button" class="btn btn-danger" data-dismiss="modal" onclick="clearContractForm();"><i class="fa fa-ban"></i></button>';
					
				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
    },2000);
}


function saveEditedContract(id_contract,id_contact) {
	var req="";
	var id_contractor=document.getElementById('contractModal_id_contractor').value;
	if(id_contractor){ req=req+'&id_contractor='+id_contractor; }
	
	var id_contracting_party=document.getElementById('contractModal_id_contracting_party').value;
	if(id_contracting_party){ req=req+'&id_contracting_party='+id_contracting_party; }
	
	var contract_code=document.getElementById('contractModal_contract_code').value;
	if(contract_code){ req=req+'&contract_code='+contract_code; } 
	
	var id_contract_type=document.getElementById('contractModal_id_contract_type').value;
	if(id_contract_type){ req=req+'&id_contract_type='+id_contract_type; }
	
	var contract_date=document.getElementById('contractModal_contract_date').value;
	if(contract_date){ req=req+'&contract_date='+contract_date; }
	
	var start_date=document.getElementById('contractModal_start_date').value;
	if(start_date){ req=req+'&start_date='+start_date; }
	
	var end_date=document.getElementById('contractModal_end_date').value;
	if(end_date){ req=req+'&end_date='+end_date; }
	
	var contract_desc=document.getElementById('contractModal_contract_desc').value;
	if(contract_desc){ req=req+'&contract_desc='+contract_desc; }
	
	if(id_contracting_party==""){
		toastr.info('Select contracting party.',{timeOut:15000})
	} else 
	if(start_date==""){
		toastr.info('Enter a start date.',{timeOut:15000})
	} else 
	if(end_date==""){
		toastr.info('Enter a end date.',{timeOut:15000})
		
	} else {
		var resurl='include/contact.php?elemid=contract_management&conf=edit'+req+'&id_contract='+id_contract;    
		var xhr = getXhr();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;      
				
				if(leselect == 1){
					toastr.success('Contract successfully saved',{timeOut:15000})
					$("#contractModal").modal("hide");
					contractList(id_contact);
				
				} else 
				if(leselect == 0){
					toastr.error('Contract not saved, please retry!',{timeOut:15000})
				} else {
					internal_error();
				}
				
				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
	} 
}

function deleteContract(id_contract,id_contact) {
	var resurl='include/contact.php?elemid=delete_contract&id_contract='+id_contract;    
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;       
			
			if(leselect == 1){
				toastr.success('Contract successfully deleted',{timeOut:15000})
				contractList(id_contact);
			} else 
			if(leselect == 0){
				toastr.error('Contract not deleted, please retry!',{timeOut:15000})
			} else {
				internal_error();
			}
			
			leselect = xhr.responseText;
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}

// ** End Contract ** 

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


// function byTowns() {
	
	// var centre_x=0, centre_y=0;
	// $("#rangeSliderBox").addClass("hide");

	// plantation_couche.clearLayers();
	// plantation_points.clearLayers();
	
	// map.removeControl(drawControl);
	
	// var code_town = document.getElementById('list_towns').value;
	
	// if(code_town == ""){
		// geolocation();
		
	// } else {
		// $("#geolocationSpanner").removeClass("hide");
	
		// $(".div_overlay").remove();
		// var spinner = '<div class="sk-spinner sk-spinner-double-bounce div_ov_spanner">'+
			// '<div class="sk-double-bounce1"></div>'+
			// '<div class="sk-double-bounce2"></div>'+
		// '</div>';

		// $("#geoMap").append("<div class='div_overlay'>"+spinner+"</div>");
		
		// $("#list_projects").val($("#list_projects option:first").val());
		
		// var resurl='include/geolocation.php?elemid=show_by_towns&code_town='+code_town;
		// var xhr = getXhr();
		// xhr.onreadystatechange = function(){
			// if(xhr.readyState == 4 ){
				// leselect = xhr.responseText;
				// var val = leselect.split('**');  

				// document.getElementById('d4_content').innerHTML = val[0];

				// var options = {
					// valueNames: ['geo_contact_name']
				// };

				// var userList = new List('geo_contacts', options);

				// $('#d4_content li a').click(function() {
					// $('ul li.on').removeClass('on');
					// $(this).closest('li').addClass('on');
				// });
				
			
				// var plantations = JSON.parse(val[1]); 
				// var n = parseInt(JSON.stringify(plantations.length)); 
			
				// i=0;
				// var b=0;
				// while(i<n){ 
					
					// if(geoField_update == 1){  
						// var editBtn = '<i class="fa fa-pen-square fa-fw pull-right" onclick="editFarmerCPoint(\''+plantations[i].properties.gid_plantation+'\',\''+plantations[i].properties.id_contact+'\');" style="cursor:pointer; color:green;"></i>';
					// } else { var editBtn = ""; }

					// var geom_json = JSON.stringify(plantations[i].properties.geom_json);   
					// var coordx = JSON.stringify(plantations[i].properties.coordx);    
					// var coordy = JSON.stringify(plantations[i].properties.coordy);    
					
					// if(geom_json!="null"){  
						// plantation_couche.addData(plantations);	
						
						// map.addLayer(plantation_couche);  
						// map.fitBounds(plantation_couche.getBounds());  
					// } 
					
					// if((coordx!="null")&&(coordy!="null")) {
						
						// if(plantations[i].properties.name_farmer === null){ var name_farmer=""; } else { var name_farmer=plantations[i].properties.name_farmer; }
						// if(plantations[i].properties.name_farmergroup === null){ var name_farmergroup=""; } else { var name_farmergroup=plantations[i].properties.name_farmergroup; }
						// if(plantations[i].properties.name_town === null){ var name_town=""; } else { var name_town=plantations[i].properties.name_town; }
						// if(plantations[i].properties.code_farmer === null){ var code_farmer=""; } else { var code_farmer=plantations[i].properties.code_farmer; }
						// if(plantations[i].properties.culture === null){ var culture=""; } else { var culture=plantations[i].properties.culture; }
						// if(plantations[i].properties.area === null){ var area=""; } else { var area=plantations[i].properties.area; }
						// if(plantations[i].properties.name_buyer === null){ var name_buyer=""; } else { var name_buyer=plantations[i].properties.name_buyer; }
						// if(plantations[i].properties.gid_plantation === null){ var gid_plantation=""; } else { var gid_plantation=plantations[i].properties.gid_plantation; }
						// if(plantations[i].properties.gid_town === null){ var gid_town=""; } else { var gid_town=plantations[i].properties.gid_town; }
						// if(plantations[i].properties.id_contact === null){ var id_contact=""; } else { var id_contact=plantations[i].properties.id_contact; }
	
						// var popupContent = "<div style=\"max-width:400px; max-height: 200px\"><h5 style=\"border-bottom: 1px solid #eee;\">"+blanc
						// +"<i class=\"fa fa-check-square fa-fw\" style=\"color:#ed1b2c\"></i><strong style=\"color:#ed1b2c\">&nbsp;&nbsp;Collection Point</strong>"+editBtn+"</h5>"+blanc
							// +"<div class=\"icon_desc\" style=\"margin-left:0px;display:block\"><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Farmer name : </strong>"+name_farmer
							// +" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Farmer group : </strong>"+name_farmergroup
							// +" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Farmer residence : </strong>"+name_town
							// +" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Code Farmer : </strong>"+code_farmer
							// +" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Culture : </strong>"+culture
							// +" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Area (ha) : </strong>"+area
							// +" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Buyer : </strong>"+name_buyer
							// +" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> GID plantation : </strong>"+gid_plantation
							// +" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> GID town : </strong>"+gid_town
							// +" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> ID Contact: </strong>"+id_contact
						// +" </span></div></div>";
						
						// mark = L.marker([plantations[i].properties.coordx, plantations[i].properties.coordy], {icon: pointIcon,riseOnHover:true})
							// .bindPopup(popupContent)
							// .on('click', function(e) { $("#rangeSliderBox").removeClass("hide");
								// centre_x=e.latlng.lat; centre_y=e.latlng.lng;
								// create_circle(centre_x,centre_y,0); 
							// })
							// .addTo(plantation_points);
							
						// map.addLayer(plantation_points);
					// } 
					
					// if((JSON.stringify(geom_json)=="null")&&((coordx=="null")&&(coordy=="null"))){
						// map.fitWorld().zoomIn(); 
					// } else { b=1; }
					
					// i += 1;
				// }
				
				// $("#geolocationSpanner").addClass("hide");
				// $(".div_overlay").remove();
				
				// if(b == 1){
					// map.fitBounds(plantation_couche.getBounds().extend(plantation_points.getBounds()));
				// }
			// }
		// };
		
		// xhr.open("GET",resurl,true);
		// xhr.send(null);
	// }
	
	// $("#ionrange_equipement").ionRangeSlider({
		// min: 0,
		// max: 100,
		// postfix: " Km",
		// onFinish: function (data) {
			// create_circle(centre_x,centre_y,data.fromNumber);
        // }
    // });
	
	// plantation_points.on('clusterclick', function (a) {
		// a.layer.zoomToBounds();
	// });
// }


// function byProjects() {
	
	// var centre_x=0, centre_y=0;
	// $("#rangeSliderBox").addClass("hide");
	
	// plantation_couche.clearLayers();
	// plantation_points.clearLayers();
	
	// map.removeControl(drawControl);
	
	// var id_project = document.getElementById('list_projects').value;
	
	// if(id_project == ""){
		// geolocation();
		
	// } else {
		
		// $("#geolocationSpanner").removeClass("hide");
	
		// $(".div_overlay").remove();
		// var spinner = '<div class="sk-spinner sk-spinner-double-bounce div_ov_spanner">'+
			// '<div class="sk-double-bounce1"></div>'+
			// '<div class="sk-double-bounce2"></div>'+
		// '</div>';

		// $("#geoMap").append("<div class='div_overlay'>"+spinner+"</div>");
	
		// $("#list_towns").val($("#list_towns option:first").val());
		
		// var resurl='include/geolocation.php?elemid=show_by_project&id_project='+id_project; 
		// var xhr = getXhr();
		// xhr.onreadystatechange = function(){
			// if(xhr.readyState == 4 ){
				// leselect = xhr.responseText;  
				// var val = leselect.split('**');  

				// document.getElementById('d4_content').innerHTML = val[0];
				
				// load_zones(id_project);

				// var options = {
					// valueNames: ['geo_contact_name']
				// };

				// var userList = new List('geo_contacts', options);

				// $('#d4_content li a').click(function() {
					// $('ul li.on').removeClass('on');
					// $(this).closest('li').addClass('on');
				// });
				
				// map.invalidateSize();
			
				// var plantations = JSON.parse(val[1]); 
				// var n = parseInt(JSON.stringify(plantations.length));  
				
				// i=0;
				// while(i<n){ 
					
					// if(geoField_update == 1){  
						// var editBtn = '<i class="fa fa-pen-square fa-fw pull-right" onclick="editFarmerCPoint(\''+plantations[i].properties.gid_plantation+'\',\''+plantations[i].properties.id_contact+'\');" style="cursor:pointer; color:green;"></i>';
					// } else { var editBtn = ""; }

					// var geom_json = JSON.stringify(plantations[i].properties.geom_json);   
					// var coordx = JSON.stringify(plantations[i].properties.coordx);  
					// var coordy = JSON.stringify(plantations[i].properties.coordy);  
					
					// if(geom_json!="null"){   
						// plantation_couche.addData(plantations[i]);	 
						
						// map.addLayer(plantation_couche);     
						// map.fitBounds(plantation_couche.getBounds());
					// } 
					
					// if((coordx!="null")&&(coordy!="null")) { 
						// if(plantations[i].properties.name_farmer === null){ var name_farmer=""; } else { var name_farmer=plantations[i].properties.name_farmer; }
							// if(plantations[i].properties.name_farmergroup === null){ var name_farmergroup=""; } else { var name_farmergroup=plantations[i].properties.name_farmergroup; }
							// if(plantations[i].properties.name_town === null){ var name_town=""; } else { var name_town=plantations[i].properties.name_town; }
							// if(plantations[i].properties.code_farmer === null){ var code_farmer=""; } else { var code_farmer=plantations[i].properties.code_farmer; }
							// if(plantations[i].properties.culture === null){ var culture=""; } else { var culture=plantations[i].properties.culture; }
							// if(plantations[i].properties.area === null){ var area=""; } else { var area=plantations[i].properties.area; }
							// if(plantations[i].properties.name_buyer === null){ var name_buyer=""; } else { var name_buyer=plantations[i].properties.name_buyer; }
							// if(plantations[i].properties.gid_plantation === null){ var gid_plantation=""; } else { var gid_plantation=plantations[i].properties.gid_plantation; }
							// if(plantations[i].properties.gid_town === null){ var gid_town=""; } else { var gid_town=plantations[i].properties.gid_town; }
							// if(plantations[i].properties.id_contact === null){ var id_contact=""; } else { var id_contact=plantations[i].properties.id_contact; }
		
							// var popupContent = "<div style=\"max-width:400px; max-height: 200px\"><h5 style=\"border-bottom: 1px solid #eee;\">"+blanc
							// +"<i class=\"fa fa-check-square fa-fw\" style=\"color:#ed1b2c\"></i><strong style=\"color:#ed1b2c\">&nbsp;&nbsp;Collection Point</strong>"+editBtn+"</h5>"+blanc
								// +"<div class=\"icon_desc\" style=\"margin-left:0px;display:block\"><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Farmer name : </strong>"+name_farmer
								// +" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Farmer group : </strong>"+name_farmergroup
								// +" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Farmer residence : </strong>"+name_town
								// +" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Code Farmer : </strong>"+code_farmer
								// +" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Culture : </strong>"+culture
								// +" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Area (ha) : </strong>"+area
								// +" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Buyer : </strong>"+name_buyer
								// +" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> GID plantation : </strong>"+gid_plantation
								// +" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> GID town : </strong>"+gid_town
								// +" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> ID Contact: </strong>"+id_contact
							// +" </span></div></div>";
						
						// mark = L.marker([plantations[i].properties.coordx, plantations[i].properties.coordy], {icon: pointIcon,riseOnHover:true})
							// .bindPopup(popupContent)
							// .on('click', function(e) { $("#rangeSliderBox").removeClass("hide");
								// centre_x=e.latlng.lat; centre_y=e.latlng.lng;
								// create_circle(centre_x,centre_y,0); 
							// })
							// .addTo(plantation_points);
						// map.addLayer(plantation_points);
						
						// map.fitBounds(plantation_points.getBounds());
					// }  

					// i += 1;
				// }
				
				// $("#geolocationSpanner").addClass("hide");
				// $(".div_overlay").remove();
			// }
		// };
		
		// xhr.open("GET",resurl,true);
		// xhr.send(null);
	// }

	// $("#ionrange_equipement").ionRangeSlider({
		// min: 0,
		// max: 100,
		// postfix: " Km",
		// onFinish: function (data) {
			// create_circle(centre_x,centre_y,data.fromNumber);
        // }
    // });

	// plantation_points.on('clusterclick', function (a) {
		// a.layer.zoomToBounds();
	// });
// }


var newLineBtn;

// function showAllLines() {
	
	// plantation_lines_couche.clearLayers();
	// if(newLineBtn) { map.removeControl(newLineBtn); }
	
	// var resurl='include/geolocation.php?elemid=show_all_plantation_lines';
    // var xhr = getXhr();
	// xhr.onreadystatechange = function(){
        // if(xhr.readyState == 4 ){
            // leselect = xhr.responseText;    
			
			// var plantation_lines = JSON.parse(leselect);    
			// plantation_lines_couche.addData(plantation_lines);	 
			// map.addLayer(plantation_lines_couche);  
        // }
    // };

    // xhr.open("GET",resurl,true);
    // xhr.send(null);
	
	// newLineBtn = L.easyButton('fa fa-route fa-lg', function(btn, map){ addTLineModule(); }, { position: 'topright' });  
	// newLineBtn.addTo(map);
// }


function showTraceLine(plant_line_id) {

	plantation_points.clearLayers();
	plantation_couche.clearLayers();
	plantation_lines_couche.clearLayers();

	var resurl='include/geolocation.php?elemid=show_plantation_lines&plant_line_id='+plant_line_id;
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
}


// function showAllPlantations(conf) {
	
	// var centre_x=0, centre_y=0;
	// $("#rangeSliderBox").addClass("hide");
	
	// plantation_points.clearLayers();
	// plantation_couche.clearLayers();
	// circle_layer.clearLayers();
	
	// if(expdBtn){ map.removeControl(expdBtn); }

	// if(conf == 1) {
		// $(".div_overlay").remove();
		// var spinner = '<div class="sk-spinner sk-spinner-double-bounce div_ov_spanner">'+
			// '<div class="sk-double-bounce1"></div>'+
			// '<div class="sk-double-bounce2"></div>'+
		// '</div>';

		// $("#geoMap").append("<div class='div_overlay'>"+spinner+"</div>");
	// }
	
	// var req='';
	// if ($('#plt_filter_bio').is(':checked')) { req=req+'&bio=1'; }
	// if ($('#plt_filter_bio_suisse').is(':checked')) { req=req+'&bio_suisse=1'; }
	// if ($('#plt_filter_rspo').is(':checked')) { req=req+'&rspo=1'; }
	// if ($('#plt_filter_fair_trade').is(':checked')) { req=req+'&fair_trade=1'; }
	// if ($('#plt_filter_global_gap').is(':checked')) { req=req+'&globalgap=1'; }
	// if ($('#plt_filter_utz').is(':checked')) { req=req+'&utz_rainforest=1'; }
	// if ($('#plt_filter_perimeter').is(':checked')) { req=req+'&perimeter=1'; }
	// if ($('#plt_filter_eco_river').is(':checked')) { req=req+'&eco_river=1'; }
	// if ($('#plt_filter_eco_shallows').is(':checked')) { req=req+'&eco_shallows=1'; }
	// if ($('#plt_filter_eco_wells').is(':checked')) { req=req+'&eco_wells=1'; }
	// if ($('#plt_filter_synthetic_fertilizer').is(':checked')) { req=req+'&synthetic_fertilizer=1'; }
	// if ($('#plt_filter_synthetic_herbicides').is(':checked')) { req=req+'&synthetic_herbicides=1'; }
	// if ($('#plt_filter_synthetic_pesticide').is(':checked')) { req=req+'&synthetic_pesticide=1'; }
	// if ($('#plt_filter_intercropping').is(':checked')) { req=req+'&intercropping=1'; }
	// if ($('#plt_filter_forest').is(':checked')) { req=req+'&forest=1'; }
	// if ($('#plt_filter_sewage').is(':checked')) { req=req+'&sewage=1'; }
	// if ($('#plt_filter_waste').is(':checked')) { req=req+'&waste=1'; }
	// if ($('#plt_filter_fire').is(':checked')) { req=req+'&fire=1'; }
	// if ($('#plt_filter_irrigation').is(':checked')) { req=req+'&irrigation=1'; }
	// if ($('#plt_filter_drainage').is(':checked')) { req=req+'&drainage=1'; }
	// if ($('#plt_filter_slope').is(':checked')) { req=req+'&slope=1'; }
	// if ($('#plt_filter_pest').is(':checked')) { req=req+'&pest=1'; }
	// if ($('#plt_filter_rating').val() != "") { req=req+'&rating='+$('#plt_filter_rating').val(); }
	// if ($('#plt_filter_surface_ha').val() != "") { req=req+'&surface_ha='+$('#plt_filter_surface_ha').val(); }
	// if ($('#plt_filter_year_creation').val() != "") { req=req+'&year_creation='+$('#plt_filter_year_creation').val(); }
	// if ($('#plt_filter_extension').is(':checked')) { req=req+'&extension=1'; }
	// if ($('#plt_filter_year_extension').val() != "") { req=req+'&year_extension='+$('#plt_filter_year_extension').val(); }
	// if ($('#plt_filter_replanting').is(':checked')) { req=req+'&replanting=1'; }
	// if ($('#plt_filter_year_to_replant').val() != "") { req=req+'&year_to_replant='+$('#plt_filter_year_to_replant').val(); }
	// if ($('#plt_filter_road_access').is(':checked')) { req=req+'&road_access=1'; }

	
	// var resurl='include/geolocation.php?elemid=show_all_plantations'+req;
    // var xhr = getXhr();
	// xhr.onreadystatechange = function(){
        // if(xhr.readyState == 4 ){
            // leselect = xhr.responseText;   
			
			// map.invalidateSize();
			
			// var plantations = JSON.parse(leselect); 
			// var n = parseInt(JSON.stringify(plantations.length));  
			
			// var poly=0, coords=0;
			
			// i=0;
			// while(i<n){ 
				
				// if(geoField_update == 1){  
					// var editBtn = '<i class="fa fa-pen-square fa-fw pull-right" onclick="editFarmerCPoint(\''+plantations[i].properties.gid_plantation+'\',\''+plantations[i].properties.id_contact+'\');" style="cursor:pointer; color:green;"></i>';
				// } else { var editBtn = ""; }

				// var geom_json = JSON.stringify(plantations[i].properties.geom_json);   
				// var coordx = JSON.stringify(plantations[i].properties.coordx);  
				// var coordy = JSON.stringify(plantations[i].properties.coordy);  
				
				// if(geom_json!="null"){ poly += 1;
					// plantation_couche.addData(plantations[i]);	 
					// map.addLayer(plantation_couche); 
				// } 
				
				// if((coordx!="null")&&(coordy!="null")) {  coords += 1;
					// if(plantations[i].properties.name_farmer === null){ var name_farmer=""; } else { var name_farmer=plantations[i].properties.name_farmer; }
						// if(plantations[i].properties.name_farmergroup === null){ var name_farmergroup=""; } else { var name_farmergroup=plantations[i].properties.name_farmergroup; }
						// if(plantations[i].properties.name_town === null){ var name_town=""; } else { var name_town=plantations[i].properties.name_town; }
						// if(plantations[i].properties.code_farmer === null){ var code_farmer=""; } else { var code_farmer=plantations[i].properties.code_farmer; }
						// if(plantations[i].properties.culture === null){ var culture=""; } else { var culture=plantations[i].properties.culture; }
						// if(plantations[i].properties.area === null){ var area=""; } else { var area=plantations[i].properties.area; }
						// if(plantations[i].properties.name_buyer === null){ var name_buyer=""; } else { var name_buyer=plantations[i].properties.name_buyer; }
						// if(plantations[i].properties.gid_plantation === null){ var gid_plantation=""; } else { var gid_plantation=plantations[i].properties.gid_plantation; }
						// if(plantations[i].properties.gid_town === null){ var gid_town=""; } else { var gid_town=plantations[i].properties.gid_town; }
						// if(plantations[i].properties.id_contact === null){ var id_contact=""; } else { var id_contact=plantations[i].properties.id_contact; }
	
						// var popupContent = "<div style=\"max-width:400px; max-height: 200px\"><h5 style=\"border-bottom: 1px solid #eee;\">"+blanc
						// +"<i class=\"fa fa-check-square fa-fw\" style=\"color:#ed1b2c\"></i><strong style=\"color:#ed1b2c\">&nbsp;&nbsp;Collection Point</strong>"+editBtn+"</h5>"+blanc
							// +"<div class=\"icon_desc\" style=\"margin-left:0px;display:block\"><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Farmer name : </strong>"+name_farmer
							// +" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Farmer group : </strong>"+name_farmergroup
							// +" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Farmer residence : </strong>"+name_town
							// +" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Code Farmer : </strong>"+code_farmer
							// +" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Culture : </strong>"+culture
							// +" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Area (ha) : </strong>"+area
							// +" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Buyer : </strong>"+name_buyer
							// +" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> GID plantation : </strong>"+gid_plantation
							// +" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> GID town : </strong>"+gid_town
							// +" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> ID Contact: </strong>"+id_contact
						// +" </span></div></div>";
					
					// mark = L.marker([plantations[i].properties.coordx, plantations[i].properties.coordy], {icon: pointIcon,riseOnHover:true})
						// .bindPopup(popupContent)
						// .on('click', function(e) { $("#rangeSliderBox").removeClass("hide");
							// centre_x=e.latlng.lat; centre_y=e.latlng.lng;
							// create_circle(centre_x,centre_y,0); 
						// })
						// .addTo(plantation_points);
					// map.addLayer(plantation_points);
				// }  
				
				// i += 1;
			// }
			
			// if(coords == 0){
				// map.fitBounds(plantation_couche.getBounds());
			// } else
			// if(poly == 0){
				// map.fitBounds(plantation_points.getBounds());
			// } else { 
				// map.fitBounds(plantation_points.getBounds());
			// }
			
			// $(".div_overlay").remove();
        // }
    // };

    // xhr.open("GET",resurl,true);
    // xhr.send(null);
	
	// expdBtn = L.easyButton('fa fa-expand fa-lg', function(btn, map){ expandGeoMap(); });
	// expdBtn.addTo(map);

	// $("#ionrange_equipement").ionRangeSlider({
		// min: 0,
		// max: 100,
		// postfix: " Km",
		// onFinish: function (data) {
			// create_circle(centre_x,centre_y,data.fromNumber);
        // }
    // });

// }


// function create_circle(centre_x,centre_y,rayon) {
	// circle_layer.clearLayers();

	// var circle = L.circle([centre_x, centre_y], rayon*1000, {
		// color: 'red',
		// fillColor: '#f03',
		// fillOpacity: 0.2
	// }).addTo(circle_layer);
	// circle_layer.addTo(map);

	// map.fitBounds(circle_layer.getBounds());
// }


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
				
				if(geoField_update == 1){  
					var editBtn = '<i class="fa fa-pen-square fa-fw pull-right" onclick="editFarmerCPoint(\''+plantations[i].properties.gid_plantation+'\',\''+plantations[i].properties.id_contact+'\');" style="cursor:pointer; color:green;"></i>';
				} else { var editBtn = ""; }

				var geom_json = JSON.stringify(plantations[i].properties.geom_json);   
				var coordx = JSON.stringify(plantations[i].properties.coordx);  
				var coordy = JSON.stringify(plantations[i].properties.coordy);  
				
				if(geom_json!=null){    
					plantation_couche.addData(plantations[i]);	 
					
					map.addLayer(plantation_couche);   
				
					if(coordx=='null'){
						map.fitBounds(plantation_couche.getBounds());   
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
			
			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
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
					],"crs":{"type":"name","properties":{"name":"EPSG:4326"}} 
				};
			
				poly = JSON.stringify(new_json);
	
				var polygon3 = turf.polygon(json.geometry.coordinates);  
				seeArea = turf.area(polygon3);   
			});
			
			map.fitBounds(polygon.getBounds());

            leselect = xhr.responseText;
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
			],"crs":{"type":"name","properties":{"name":"EPSG:4326"}} 
		};
	
		poly = JSON.stringify(new_json);
	});
	
	map.on(L.Draw.Event.EDITED, function(event) {
		var layers = event.layers;
		
		drawnPolygone.addLayer(layers);
		map.addLayer(drawnPolygone);
		
		var json = drawnPolygone.toGeoJSON();
		
		var new_json = {
			"type": "MultiPolygon",
			"coordinates": [
				json.geometry.coordinates
			],"crs":{"type":"name","properties":{"name":"EPSG:4326"}} 
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
					"coordinates":json.geometry.coordinates,"crs":{"type":"name","properties":{"name":"EPSG:4326"}} 
				};
			
				t_line = JSON.stringify(new_json);  
			});
			
			map.fitBounds(polyline.getBounds());
		}
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
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


// function deleteTraceRegion() {
	
	// var plant_line_id = document.getElementById("traceRegion_plant_line_id").value;
	// var r = confirm("Are you sure you want to delete ID Trace : "+plant_line_id);
	
	// if(r == true){
		// var resurl='include/geolocation.php?elemid=delete_plantation_lines&plant_line_id='+plant_line_id;
		// var xhr = getXhr(); 
		// xhr.onreadystatechange = function(){
			// if(xhr.readyState == 4 ){
				// leselect = xhr.responseText;  

				// if(leselect == 1){
					// toastr.success('Trace deleted successfully.',{timeOut:15000})
					// geolocation();
				
				// } else {
					// toastr.error('Trace not deleted.',{timeOut:15000})
				// }
				
			// }
		// };

		// xhr.open("GET",resurl,true);
		// xhr.send(null);
	// }
// }

// Edit - Save Polygon
function closeFPModule(id_farmer,conf) {

	drawnPolygone.clearLayers();

	showPlantations(id_farmer);
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
	
		geolocation();
	}
}

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


// Edit - Save Marker
function closeFPModulePt(id_farmer) {

	map.removeControl(saveBtn);
	map.removeControl(cancelBtn);
	
	map.removeLayer(polygon);
	
	showPlantations(id_farmer);
	geolocation();
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


// Edit - Save Polyline
// function closeTLineModule(plant_line_id) {
	
	// drawnPolyline.clearLayers();  
	// map.removeControl(drawLineControl);
	
	// map.removeControl(saveBtn);
	// map.removeControl(cancelBtn);
	
	// map.removeLayer(polyline);
	
	// geolocation();
// }

function saveTLineModule(plant_line_id) {  
	if(t_line!=""){   
		var resurl='include/geolocation.php?elemid=save_plantation_line&plant_line_id='+plant_line_id+'&geom_json='+t_line;  
		var xhr = getXhr();  
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;     
				
				if(leselect == 1){
					toastr.success('Editing saved successfully.',{timeOut:15000})
					closeTLineModule(plant_line_id);
					showTraceLine(plant_line_id);
					
				} else {
					toastr.error('Editing not saved.',{timeOut:15000})
				}

				leselect = xhr.responseText;
			} 
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
		
	} else {
		toastr.info('Draw a line first.',{timeOut:15000})
	}
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
			"coordinates": json.features[0].geometry.coordinates,"crs":{"type":"name","properties":{"name":"EPSG:4326"}} 
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
			"coordinates": json.features[0].geometry.coordinates,"crs":{"type":"name","properties":{"name":"EPSG:4326"}} 
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


/* Hide all pages */

function hideAll() {
	$("#no_data").addClass("hide");
	$("#db_content").addClass("hide");
	$("#db_story").addClass("hide");
	$("#db_geolocation").addClass("hide");
	$("#db_data_collection").addClass("hide");
	$("#db_dashboard_1").addClass("hide");
	$("#db_dashboard_2").addClass("hide");
	$("#db_dashboard_3").addClass("hide");
	$("#db_dashboard_4").addClass("hide");
	$("#db_calendar").addClass("hide");
	$("#db_notes").addClass("hide");
	$("#db_projects").addClass("hide");
	$("#db_tasks").addClass("hide");
	$("#db_agentm").addClass("hide");
	$("#db_regvalues").addClass("hide");
	$("#db_users").addClass("hide");
	$("#db_roles_ass").addClass("hide");
	$("#db_roles_def").addClass("hide");
	$("#db_permissions").addClass("hide");
	$("#db_crm_manag").addClass("hide");
	$("#db_crm_manag2").addClass("hide");
	$("#db_port_costs").addClass("hide");
	$("#db_product_exp").addClass("hide");
	$("#db_logistiques").addClass("hide");
	$("#db_crm_freight").addClass("hide");
	$("#db_crm_ship").addClass("hide");
	$("#db_port_costs_table").addClass("hide");
	$("#db_country").addClass("hide");
	$("#db_town").addClass("hide");
	$("#db_port").addClass("hide");
	$("#db_crm_cult").addClass("hide");
	$("#db_email").addClass("hide");
	$("#db_wf_process").addClass("hide");
	$("#db_wf_trigger").addClass("hide");
	$("#db_wf_group").addClass("hide");
	$("#db_timeLine").addClass("hide");
	$("#db_workflow_progress").addClass("hide");
	$("#db_cert_status").addClass("hide");
	$("#db_apex").addClass("hide");
	$("#db_survey").addClass("hide");
	$("#db_survey_campaign").addClass("hide");
	$("#db_survey_campaign_results").addClass("hide");
	
	$('#right-sidebar').removeClass('sidebar-open');
	$('#sideBarBtnToggle').addClass('hide');
}


function volet_droit_animated() {

	if ($('#sideBarBtnToggle').hasClass("toggleOpen")) {
		document.getElementById('sideBarBtnToggle').innerHTML = '<i class="fa fa-caret-left"></i>';
		$('#right-sidebar').removeClass('fadeInRightBig');
		$('#right-sidebar').addClass('fadeOutRightBig');
		$('#right-sidebar').addClass('hide');

		$('#sideBarBtnToggle').removeClass("toggleOpen");
		$('#sideBarBtnToggle').removeClass("fadeInRightBig");
		// $('#sideBarBtnToggle').addClass("fadeOutLeftBig");
		$('#sideBarBtnToggle').addClass("hide");

	} else {
		$('#sideBarBtnToggle').removeClass("hide");
		document.getElementById('sideBarBtnToggle').innerHTML = '<i class="fa fa-caret-right"></i>';
		$('#right-sidebar').addClass('sidebar-open');
	    $('#right-sidebar').removeClass('hide');
	    $('#right-sidebar').removeClass('fadeOutRightBig');
		$('#right-sidebar').addClass('fadeInRightBig');

		$('#sideBarBtnToggle').addClass("toggleOpen");
		$('#sideBarBtnToggle').removeClass("fadeOutRightBig");
		$('#sideBarBtnToggle').addClass("fadeInRightBig");
	}
}


function loadingForm2(id_con_list,id_ord_loading_item){   

	$('#right-sidebar').addClass('sidebar-open');
	$('#sideBarBtnToggle').removeClass('hide');
	volet_droit_animated();
	
	var resurl='container_loading.php?elemid=loading_container&id_con_list='+id_con_list+'&id_ord_loading_item='+id_ord_loading_item;  
    var xhr = getXhr();  
    xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
			leselect = xhr.responseText;     
            
			document.getElementById("rightInfos").innerHTML = leselect;

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
	xhr.send(null);
}			


function refreshLoading(id_con_list,id_ord_loading_item) {
	
	$('#refresh_loading').addClass('fa-spin');
	var resurl='container_loading.php?elemid=loading_container&id_con_list='+id_con_list+'&id_ord_loading_item='+id_ord_loading_item;  
    var xhr = getXhr();  
    xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
			leselect = xhr.responseText;     
            
			document.getElementById("rightInfos").innerHTML = leselect;
			$('#refresh_loading').removeClass('fa-spin');

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
	xhr.send(null);
}


/*
*
* Logistiques
*
*/


/* Loading place */

function newLaodingPlcae() {
	$("#modalCreateLoadingPlace").modal("show");
}


/* menu management */
	
function logistique(cond,schedule_id) {
	hideAll();
	
	$("#db_logistiques").removeClass("hide");
	$("#logistiqueSpanner").removeClass("hide");
	
	titleMenuManag("Logistic","btn_logistic");
	
	var thumb = '<i class="fa fa-hand-o-left"></i> Select a freight in your list';
	
	document.getElementById('freight_ocean').innerHTML = thumb;
	document.getElementById('booking_addendum').innerHTML = thumb;
	document.getElementById('onward_carriage').innerHTML = thumb;
	document.getElementById('carriage_addendum').innerHTML = thumb;
	
	
	var resurl='include/logistic.php?elemid=logistiques&doc_right='+bookingDocManager_read+'&cond='+cond+'&schedule_id='+schedule_id;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;  

			document.getElementById('logis_refn_list').innerHTML = leselect;
			
			var options = {
				valueNames: ['reference_nr']
			};

			var userList = new List('logistiques_reference_nembers', options);

			$("#logistiqueSpanner").addClass("hide");
			
			$('#logis_refn_list li a').click(function() {
				$('ul li.on').removeClass('on');
				$(this).closest('li').addClass('on');
			});
			
			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);	
}

function logPipelineFilter(value) {
	logistique(value,0);
}

function logCustomSearch(value) {

	var thumb = '<i class="fa fa-hand-o-left"></i> Select a freight in your list';
	
	document.getElementById('freight_ocean').innerHTML = thumb;
	document.getElementById('booking_addendum').innerHTML = thumb;
	document.getElementById('onward_carriage').innerHTML = thumb;
	document.getElementById('carriage_addendum').innerHTML = thumb;
	
	
	if(value == "default") {
		$('#log_default_search').removeClass('hide');
		$('#log_bl_search').addClass('hide');
		$('#log_container_search').addClass('hide');
	
		logistique(0,0);
		
	} else {
		$('#log_default_search').addClass('hide');
		var resurl='include/logistic.php?elemid=logistiques_custom';   
		var xhr = getXhr();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText; 
				var val = leselect.split('##');

				if(value == "bl"){
					$('#log_container_search').addClass('hide');
					$('#log_bl_search').removeClass('hide');
					
				} else {
					$('#log_bl_search').addClass('hide');
					$('#log_container_search').removeClass('hide');
				}
				
				document.getElementById('logBLSearchId').innerHTML = val[0];
				document.getElementById('logContainerSearchId').innerHTML = val[1];
				
				$('.chosen-select').chosen({width: "100%"});
				
				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
	}
}


/* booking Form for a container */

function activateLogisticTab(flag_book_add,flag_onw,flag_onw_add) { 
	
	if(flag_book_add==1){
		if(logOceanBookingAdd_read == 1){
			$('#crm_containers_tab').removeClass('hide'); $('#crm_addendum_ct').removeClass('hide');
		} else {
			$('#crm_containers_tab').addClass('hide'); $('#crm_addendum_ct').addClass('hide');
		}
	} else { $('#crm_containers_tab').addClass('hide'); $('#crm_addendum_ct').addClass('hide'); }

	if(flag_onw==1){
		if(logOnwardBooking_read == 1){
			$('#crm_onward_carriage_tab').removeClass('hide'); $('#crm_onward_carriage_ct').removeClass('hide');
		} else {
			$('#crm_onward_carriage_tab').addClass('hide'); $('#crm_onward_carriage_ct').addClass('hide');
		}

	} else {
		$('#crm_onward_carriage_tab').addClass('hide'); $('#crm_onward_carriage_ct').addClass('hide');
	}
	
	if(flag_onw_add==1){
		if(logOnwardBookingAdd_read == 1){
			$('#crm_carriage_addendum_tab').removeClass('hide'); $('#crm_carriage_addendum_ct').removeClass('hide');
		} else {
			$('#crm_carriage_addendum_tab').addClass('hide'); $('#crm_carriage_addendum_ct').addClass('hide');
		}
	} else { $('#crm_carriage_addendum_tab').addClass('hide'); $('#crm_carriage_addendum_ct').addClass('hide'); }
	
}

function ocean_containers_loading(ord_schedule_id,ref_num,supplier_name,pol_name,pod_name,cus_incoterms_id,id_con_booking,shipment_number,pol_id,pod_id,doc_right,flag_book_add,flag_onw,flag_onw_add,ord_fa_contact_id,pipeline_sched_id) {  
	
	var rights='';
	
	document.getElementById('sideBarBtnToggle').innerHTML = '<i class="fa fa-caret-left"></i>';
	$('#right-sidebar').removeClass('fadeInRightBig');
	$('#right-sidebar').addClass('fadeOutRightBig');
	$('#right-sidebar').addClass('hide');

	$('#sideBarBtnToggle').removeClass("toggleOpen");
	$('#sideBarBtnToggle').removeClass("fadeInRightBig");
	$('#sideBarBtnToggle').addClass('hide');
	
	var spanner = '<div class="h1 m-t-xs text-navy"><span class="loading"></span></div>';
	
	document.getElementById('freight_ocean').innerHTML = spanner;
	document.getElementById('booking_addendum').innerHTML = spanner;
	document.getElementById('onward_carriage').innerHTML = spanner;
	document.getElementById('carriage_addendum').innerHTML = spanner;
	document.getElementById('traceability').innerHTML = spanner;
	
	
	// Logistics Documents rights
	rights=rights+'&doc_right='+doc_right+'&shipDoc_read='+logShipDoc_read+'&shipDoc_create='+logShipDoc_create+'&shipDoc_update='+logShipDoc_update; 
	
	// Logistic Ocean Booking
	rights=rights+'&ocean_read='+logOceanBooking_read+'&ocean_create='+logOceanBooking_create+'&ocean_update='+logOceanBooking_update+'&labAna_update='+logLabAnalysis_update+'&labAna_read='+logLabAnalysis_read+'&labAna_create='+logLabAnalysis_create+'&labAna_delete='+logLabAnalysis_delete; 

	// Logistic Ocean Booking Container
	rights=rights+'&oceanCont_read='+logOceanContainer_read+'&oceanCont_create='+logOceanContainer_create+'&oceanCont_update='+logOceanContainer_update; 
	
	// Logistic Ocean Booking Add
	rights=rights+'&oceanAdd_read='+logOceanBookingAdd_read+'&oceanAdd_create='+logOceanBookingAdd_create+'&oceanAdd_update='+logOceanBookingAdd_update; 
	
	// Logistic Ocean Booking Add Container
	rights=rights+'&oceanAddCont_read='+logOceanAddContainer_read+'&oceanAddCont_create='+logOceanAddContainer_create+'&oceanAddCont_update='+logOceanAddContainer_update; 
	
	// Logistic Onward Booking 
	rights=rights+'&onward_read='+logOnwardBooking_read+'&onward_create='+logOnwardBooking_create+'&onward_update='+logOnwardBooking_update; 
	
	// Logistic Onward Booking Container
	rights=rights+'&onwardCont_read='+logOnwardContainer_read+'&onwardCont_create='+logOnwardContainer_create+'&onwardCont_update='+logOnwardContainer_update; 
	
	// Logistic Onward Booking Add
	rights=rights+'&onwardAdd_read='+logOnwardBookingAdd_read+'&onwardAdd_create='+logOnwardBookingAdd_create+'&onwardAdd_update='+logOnwardBookingAdd_update; 
	
	// Logistic Onward Booking Add Container
	rights=rights+'&onwardAddCont_read='+logOnwardAddContainer_read+'&onwardAddCont_create='+logOnwardAddContainer_create+'&onwardAddCont_update='+logOnwardAddContainer_update; 
	
	// Logistic Onward Invoice rights
	rights=rights+'&onwardInvoice_read='+onwardInvoice_read+'&onwardInvoice_create='+onwardInvoice_create+'&onwardInvoice_update='+onwardInvoice_update;

	// Logistic Onward Add Invoice rights
	rights=rights+'&onwardInvoiceAdd_read='+onwardInvoiceAdd_read+'&onwardInvoiceAdd_create='+onwardInvoiceAdd_create+'&onwardInvoiceAdd_update='+onwardInvoiceAdd_update;
	
	// Logistic Traceability rights
	rights=rights+'&traceability_read='+traceability_read+'&traceability_create='+traceability_create+'&traceability_update='+traceability_update+'&traceability_delete='+traceability_delete;

	// Logistic Traceability Admin rights
	rights=rights+'&traceabilityAdmin_read='+traceabilityAdmin_read+'&traceabilityAdmin_create='+traceabilityAdmin_create+'&traceabilityAdmin_update='+traceabilityAdmin_update+'&traceabilityAdmin_delete='+traceabilityAdmin_delete;

	// Logistics Move Container rights
	rights=rights+'&LogContMove='+LogContMove;

	// Card view
	var cards='&flag_book_add='+flag_book_add+'&flag_onw='+flag_onw+'&flag_onw_add='+flag_onw_add+'&pipeline_sched_id='+pipeline_sched_id;

	var resurl='include/logistic.php?elemid=ocean_containers_loading&ord_schedule_id='+ord_schedule_id+'&ref_num='+ref_num+'&supplier_name='+supplier_name+'&pol_name='+pol_name+'&pod_name='+pod_name+'&shipment_number='+shipment_number+'&pol_id='+pol_id+'&pod_id='+pod_id+rights+'&ord_fa_contact_id='+ord_fa_contact_id+'&id_con_booking='+id_con_booking+cards;
    var xhr = getXhr();  
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;
			var val = leselect.split('##');

			activateLogisticTab(flag_book_add,flag_onw,flag_onw_add);
			
			if(pipeline_sched_id >= 298){
				if(logOnwardBooking_read == 1){
					$('#crm_onward_carriage_tab').removeClass('hide'); $('#crm_onward_carriage_ct').removeClass('hide');
				}
				
				if(traceability_read == 1){
					$('#traceability_tab').removeClass('hide'); $('#traceability_ct').removeClass('hide');
				} else {
					$('#traceability_tab').addClass('hide'); $('#traceability_ct').addClass('hide');
				}
				
			} else {
				$('#traceability_tab').addClass('hide'); $('#traceability_ct').addClass('hide');
			}

			document.getElementById('freight_ocean').innerHTML = val[0];
			document.getElementById('booking_addendum').innerHTML = val[1];
			document.getElementById('onward_carriage').innerHTML = val[2];
			document.getElementById('carriage_addendum').innerHTML = val[3];
			document.getElementById('traceability').innerHTML = val[8];
			
			if(val[4]=='update'){ $('#containerEditBTN').prop("disabled", false); } else { $('#containerEditBTN').prop("disabled", true); }
			if(val[5]=='update'){ $('#containerEditBTN_add').prop("disabled", false); } else { $('#containerEditBTN_add').prop("disabled", true); }
			if(val[6]=='update'){ 
				$('#containerEditBTN_onward').prop("disabled", false); 
				$('#containerDispoEditBTN_onward').prop("disabled", false); 
			} else { 
				if((cus_incoterms_id == 263)||(cus_incoterms_id == 264)){
					$('#containerEditBTN_onward').prop("disabled", false); 
					$('#containerDispoEditBTN_onward').prop("disabled", false); 
				} else {
					$('#containerEditBTN_onward').prop("disabled", true); 
					$('#containerDispoEditBTN_onward').prop("disabled", true); 
				}
			}
			if(val[7]=='update'){ 
				$('#containerEditBTN_onward_add').prop("disabled", false); 
				$('#containerDispoEditBTN_onward_add').prop("disabled", false); 
			} else { 
				$('#containerEditBTN_onward_add').prop("disabled", true); 
				$('#containerDispoEditBTN_onward_add').prop("disabled", true); 
			}
			
			$('#bookingTabID').find('input, textarea, select').prop("disabled", true);
			$('#bookingTabID_add').find('input, textarea, select').prop("disabled", true);
			$('#onwardTabID').find('input, textarea, select').prop("disabled", true);
			$('#onwardTabID_add').find('input, textarea, select').prop("disabled", true);
			$('#traceabilityDocID').find('input, textarea, select').prop("disabled", true);
		
			$('.i-checks').iCheck({
				checkboxClass: 'icheckbox_square-green',
				radioClass: 'iradio_square-green'
			});
			
			$('.iso_available').on('ifChanged', function(event){ contPositionningHdr($(event.target).val()); });
			$('.ids_agent_radio').on('ifChanged', function(event){ LoadingAgent($(event.target).val()); });
			
			
			$('#collapseContainer').find('input, select, textarea').prop("disabled", true);
			$(".containers_action_tb").addClass("hide");
			
			$('#collapseContainer_add').find('input, select, textarea').prop("disabled", true);
			$(".containers_action_tb_add").addClass("hide");
			
			$('#collapseCarriageContainer').find('input, select, textarea').prop("disabled", true);
			$(".containers_action_tb_onward").addClass("hide");
			
			$('#collapseConPositioning_add1').find('input, select, textarea').prop("disabled", true);
			$(".containers_dispo_action_tb_onward").addClass("hide");
			
			$('#collapseCarriageContainer_add').find('input, select, textarea').prop("disabled", true);
			$(".containers_action_tb_onward_add").addClass("hide");
			
			$('#collapseConPositioning_add2').find('input, select, textarea').prop("disabled", true);
			$(".containers_dispo_action_tb_onward_add").addClass("hide");
			
			// if(oceanBooking_read == 1){ $("#ocean_booking").removeClass("hide"); }
			// if(logOceanContainer_update == 1){ $(".editContainer").removeClass("hide"); } 
			
			// if(logOceanBooking_create == 1){ $("#EditOcenLoadingBtnID2").removeClass("hide"); }
			// if(logOceanBooking_update == 1){ $("#EditOcenLoadingBtnID2").removeClass("hide"); }
			
			
			$('.edit_delivery_date').datepicker({
				format: "yyyy/mm/dd",
				calendarWeeks:true,
				autoclose: true
			}); 
			
			$('.clockpicker').clockpicker();
			
			$('[data-toggle="tooltip"]').tooltip();
			
			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);	
}

// Move container Liste Modal
function moveContainerModal(id_con_list,ord_order_id,ord_schedule_id){
	
	$("#shipmentListMove").modal("show");
	
	var resurl='include/logistic.php?elemid=move_container_to&id_con_list='+id_con_list+'&ord_order_id='+ord_order_id;
    var xhr = getXhr();    
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;    
			
			document.getElementById('actualShipmentScheduleID').value = ord_schedule_id;
			document.getElementById('shipmentListMoveContent').innerHTML = leselect;
			
			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}

// Shipment Selected
function newShipmentSelected(id_con_list,i_schedule_id) {	
	document.getElementById('shipmentListMoveFooter').innerHTML = '<a href="javascript:moveContainer(\''+id_con_list+'\',\''+i_schedule_id+'\');" onclick="return confirm(\'Are you sure you want to move the container ?\')" class="btn btn-success"><i class="fa fa-save"></i></a>'
	+'<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i></button>';
}

// Move container 
function moveContainer(id_con_list,i_schedule_id) {
	
	$("#shipmentListMove").modal("hide");
	var ord_schedule_id = document.getElementById('actualShipmentScheduleID').value;
	
	var resurl='include/logistic.php?elemid=move_container&id_con_list='+id_con_list+'&i_schedule_id='+i_schedule_id;
    var xhr = getXhr();    
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;   
			
			if(leselect == 1){   
				toastr.success('Container successfully moved',{timeOut:15000})  
				container_list(ord_schedule_id);
			} else 
			if(leselect == 0){   
				toastr.error('Container not moved, please retry',{timeOut:15000})
			} else {
				internal_error();
			}
			
			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


/* 
* Booking
* Edit, Save and Cancel management */

function show_ocean_loading_editBtn(id_con_booking,ord_schedule_id,ref_num,req) {
	
	$('#bookingTabID').find('input, textarea, select').prop("disabled", false);

	if(req=='update'){
		document.getElementById('EditOcenLoadingBtnID2').innerHTML = '<button class="btn btn-danger pull-right" onclick="hide_ocean_loading_editBtn(\''+id_con_booking+'\',\''+ord_schedule_id+'\',\''+ref_num+'\',\''+req+'\');" style="margin-top:10px;" type="button"><i class="fa fa-ban"></i></button>'
			+'&nbsp;<button class="btn btn-success pull-right" id="booking_btn" onclick="save_booking(\''+ord_schedule_id+'\',\''+ref_num+'\',\''+req+'\',\''+id_con_booking+'\');" style="margin-top:10px; margin-right:10px;" type="button"><i class="fa fa-spinner fa-spin hide" id="booking_btn_spnr"></i> <i class="fa fa-save"></i></button>'; 
			
		document.getElementById('EditOcenLoadingBtnID').innerHTML = '<a href="#" style="color:#FFF;" onclick="save_booking(\''+ord_schedule_id+'\',\''+ref_num+'\',\''+req+'\',\''+id_con_booking+'\');"> <i class="fa fa-check"></i> </a>'
			+'&nbsp;<a href="#" onclick="hide_ocean_loading_editBtn(\''+id_con_booking+'\',\''+ord_schedule_id+'\',\''+ref_num+'\',\''+req+'\');" style="color:#FFF;"> <i class="fa fa-times"></i> </a>';
	} else {
		document.getElementById('EditOcenLoadingBtnID2').innerHTML = '<button class="btn btn-danger pull-right" onclick="hide_ocean_loading_editBtn(\''+id_con_booking+'\',\''+ord_schedule_id+'\',\''+ref_num+'\',\''+req+'\');" style="margin-top:10px;" type="button"><i class="fa fa-ban"></i></button>'
			+'&nbsp;<button class="btn btn-success pull-right" id="booking_btn" onclick="save_booking(\''+ord_schedule_id+'\',\''+ref_num+'\',\'add\',\''+id_con_booking+'\');" style="margin-top:10px; margin-right:10px;" type="button"><i class="fa fa-spinner fa-spin hide" id="booking_btn_spnr"></i> <i class="fa fa-plus"></i></button>'; 
			
		document.getElementById('EditOcenLoadingBtnID').innerHTML = '<a href="#" style="color:#FFF;" onclick="save_booking(\''+ord_schedule_id+'\',\''+ref_num+'\',\'add\',\''+id_con_booking+'\');"> <i class="fa fa-check"></i> </a>'
			+'&nbsp;<a href="#" onclick="hide_ocean_loading_editBtn(\''+id_con_booking+'\',\''+ord_schedule_id+'\',\''+ref_num+'\',\''+req+'\');" style="color:#FFF;"> <i class="fa fa-times"></i> </a>';
	}	
	
	jQuery('#vessel_feeder_name').keyup(function() {
		$(this).val($(this).val().toUpperCase());
	});
	
	jQuery('#vessel_name').keyup(function() {
		$(this).val($(this).val().toUpperCase());
	});
	
	jQuery('#vessel_feeder_name1').keyup(function() {
		$(this).val($(this).val().toUpperCase());
	});
}


function hide_ocean_loading_editBtn(id_con_booking,ord_schedule_id,ref_num,req) {
	
	$('#bookingTabID').find('input, textarea, select').prop("disabled", true);
	document.getElementById('EditOcenLoadingBtnID2').innerHTML = '<button class="btn btn-success pull-right" onclick="show_ocean_loading_editBtn(\''+id_con_booking+'\',\''+ord_schedule_id+'\',\''+ref_num+'\',\''+req+'\');" style="margin-top:10px;" type="button"><i class="fa fa-edit"></i></button>';
	
	document.getElementById('EditOcenLoadingBtnID').innerHTML = '<a href="#" style="color:#FFF;" onclick="show_ocean_loading_editBtn(\''+id_con_booking+'\',\''+ord_schedule_id+'\',\''+ref_num+'\',\''+req+'\');"> <i class="fa fa-edit"></i> </a>';
}


function edit_ocean_loading(id_con_booking,ref_num,ord_schedule_id,conf) {

	$('#bookingTabID').find('input, textarea, select').prop("disabled", false);

	document.getElementById('EditOcenLoadingBtnID').innerHTML = '<a href="#" style="color:#FFF;" onclick="save_booking(\''+ord_schedule_id+'\',\''+ref_num+'\',\''+conf+'\',\''+id_con_booking+'\');"> <i class="fa fa-check"></i> </a>'
		+'&nbsp;<a href="#" onclick="cancel_ocean_loading(\''+id_con_booking+'\',\''+ref_num+'\',\''+conf+'\',\''+ord_schedule_id+'\');" style="color:#FFF;"> <i class="fa fa-times"></i> </a>';

	document.getElementById('EditOcenLoadingBtnID2').innerHTML = '<button class="btn btn-danger pull-right" onclick="cancel_ocean_loading(\''+id_con_booking+'\',\''+ref_num+'\',\''+conf+'\',\''+ord_schedule_id+'\');" style="margin-top:10px;" type="button"><i class="fa fa-ban"></i></button>'
		+'&nbsp;<button class="btn btn-success pull-right" id="booking_btn" onclick="save_booking(\''+ord_schedule_id+'\',\''+ref_num+'\',\''+conf+'\',\''+id_con_booking+'\');" style="margin-top:10px; margin-right:15px;" type="button"><i class="fa fa-spinner fa-spin hide" id="booking_btn_spnr"></i> <i class="fa fa-save"></i></button>';  
}


function cancel_ocean_loading(id_con_booking,ref_num,conf,ord_schedule_id) {
	
	$('#bookingTabID').find('input, textarea, select').prop("disabled", true);
	
	document.getElementById('EditOcenLoadingBtnID').innerHTML = '<a href="#" style="color:#FFF;" onclick="edit_ocean_loading(\''+id_con_booking+'\',\''+ref_num+'\',\''+ord_schedule_id+'\',\''+conf+'\');"> <i class="fa fa-edit"></i> </a>';
	
	document.getElementById('EditOcenLoadingBtnID2').innerHTML = '<button class="btn btn-success pull-right" onclick="edit_ocean_loading(\''+id_con_booking+'\',\''+ref_num+'\',\''+ord_schedule_id+'\',\''+conf+'\');" style="margin-top:10px;" type="button"><i class="fa fa-edit"></i></button>';
}

// Final document

function show_final_doc_editBtn_onward(ord_schedule_id,pipeline_sched_id) {
	if(pipeline_sched_id == 300){
		$('#onw_archive').prop("disabled", true);   
		$('#onw_accounting').prop("disabled", true);   
	} else {
		$('#onw_archive').prop("disabled", false);   
		$('#onw_accounting').prop("disabled", false);   
	}
	
	document.getElementById('editFinalDocBtn_onward').innerHTML = '<button onclick="finalDoc_cancelBtn_onward(\''+ord_schedule_id+'\',\''+pipeline_sched_id+'\');" class="btn btn-danger pull-right"><i class="fa fa-ban"></i></button>'
		+'<button onclick="hide_finalDoc_editBtn(\''+ord_schedule_id+'\',\''+pipeline_sched_id+'\');" style="margin-right:10px;" class="btn btn-success pull-right"><i class="fa fa-save"></i></button>';
}

function finalDoc_cancelBtn_onward(ord_schedule_id,pipeline_sched_id) {
	$('#onw_archive').prop("disabled", true);   
	$('#onw_accounting').prop("disabled", true);
	
	document.getElementById('editFinalDocBtn_onward').innerHTML = '<button class="btn btn-success pull-right" onclick="show_final_doc_editBtn_onward(\''+ord_schedule_id+'\',\''+pipeline_sched_id+'\');" style="margin-top:10px;" type="button"><i class="fa fa-edit"></i></button>';
}

function hide_finalDoc_editBtn(ord_schedule_id,pipeline_sched_id) {
	toastr.success('Final document editing saved successfully',{timeOut:15000})
	finalDoc_cancelBtn_onward(ord_schedule_id,pipeline_sched_id);
}

// Traceability

function show_traceability_editBtn(ord_schedule_id,id_con_booking) {
	
	$('#traceabilityDocID').find('input, textarea, select').prop("disabled", false);

	document.getElementById('editTraceDocBtn').innerHTML = '<button class="btn btn-danger pull-right" onclick="hide_traceability_editBtn(\''+ord_schedule_id+'\',\''+id_con_booking+'\');" style="margin-top:10px;" type="button"><i class="fa fa-ban"></i></button>'
		+'&nbsp;<button class="btn btn-success pull-right" id="traceDoc_btn" onclick="save_traceability_doc(\''+ord_schedule_id+'\',\''+id_con_booking+'\');" style="margin-top:10px; margin-right:10px;" type="button"><i class="fa fa-spinner fa-spin hide" id="traceDoc_btn_spnr"></i> <i class="fa fa-save"></i></button>'; 
}


function hide_traceability_editBtn(ord_schedule_id,id_con_booking) {
	
	$('#traceabilityDocID').find('input, textarea, select').prop("disabled", true);
	
	document.getElementById('editTraceDocBtn').innerHTML = '<button class="btn btn-success pull-right" id="traceabilityEditBTN" onclick="show_traceability_editBtn(\''+ord_schedule_id+'\',\''+id_con_booking+'\');" style="margin-top:10px;" type="button"><i class="fa fa-edit"></i></button>';
}


function save_traceability_doc(ord_schedule_id,id_con_booking) {
	var req='';
	var trace_doc_nr = document.getElementById('trace_doc_nr').value;  
	if(trace_doc_nr){ req=req+'&trace_doc_nr='+trace_doc_nr; }
	
	var trace_doc_date = document.getElementById('trace_doc_date').value;  
	if(trace_doc_date){ req=req+'&trace_doc_date='+trace_doc_date; }
	
	var trace_doc_publish = document.getElementById('trace_doc_publish').value;  
	if(trace_doc_publish){ req=req+'&trace_doc_publish='+trace_doc_publish; }

	var trace_buyer_days = document.getElementById('days_copra_purchases').value;  
	if(trace_buyer_days){ req=req+'&trace_buyer_days='+trace_buyer_days; }

	var resurl='listeslies.php?elemid=update_traceability_document&ord_schedule_id='+ord_schedule_id+'&id_con_booking='+id_con_booking+req;
    var xhr = getXhr();    
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;    
			
			if(leselect == 1){   
				toastr.success('Booking saved successfully',{timeOut:15000})  
				hide_traceability_editBtn(ord_schedule_id,id_con_booking);
				if(trace_doc_publish == 1){
					$("#traceability_panel").removeClass("hide");
				} else {
					$("#traceability_panel").addClass("hide");
				}
				
			
			} else 
			if(leselect == 0){   
				toastr.error('Error saving booking, please retry',{timeOut:15000})
			} else {
				internal_error();
			}

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function sendOrderTracePdf(ord_schedule_id,id_con_booking) {
	var resurl='listeslies.php?elemid=send_order_trace_pdf&ord_schedule_id='+ord_schedule_id+'&id_con_booking='+id_con_booking;
    var xhr = getXhr();    
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText; 
			var val = leselect.split('##');
			
			if(val[0] == 1){   
				toastr.success('Order Traceability PDF successfully send',{timeOut:15000})  
				saveOrderTracePdf(ord_schedule_id,id_con_booking,val[1],'trace');
			} else 
			if(val[0] == 0){   
				toastr.error('Order Traceability PDF not sent, please retry',{timeOut:15000})
			} else {
				internal_error();
			}

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function saveOrderTracePdf(ord_schedule_id,id_con_booking,filename,conf) {
	var resurl='pdf/trace_mail.php?filename='+filename+'&ord_schedule_id='+ord_schedule_id+'&id_con_booking='+id_con_booking+'&conf='+conf;
    var xhr = getXhr();    
    xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;  

			notifications("mail"); 
			
			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function show_traceStory_editBtn(ord_schedule_id,id_con_booking,supplier_contact_id) {
	
	$('#traceabilityStoryID').find('input, textarea, select').prop("disabled", false); 
	$('.traceStoryList').removeClass('hide'); 
	$('.trace_story_check').addClass('hide'); 

	document.getElementById('EditTraceStory').innerHTML = '<button class="btn btn-danger pull-right" onclick="hide_trace_story_editBtn(\''+ord_schedule_id+'\',\''+id_con_booking+'\',\''+supplier_contact_id+'\');" style="margin-top:10px;" type="button"><i class="fa fa-ban"></i></button>'
		+'&nbsp;<button class="btn btn-success pull-right" id="traceDoc_btn" onclick="save_traceability_story(\''+ord_schedule_id+'\',\''+id_con_booking+'\',\''+supplier_contact_id+'\');" style="margin-top:10px; margin-right:10px;" type="button"><i class="fa fa-spinner fa-spin hide" id="traceDoc_btn_spnr"></i> <i class="fa fa-save"></i></button>'; 

	$('.i-checks').iCheck({
		checkboxClass: 'icheckbox_square-green',
		radioClass: 'iradio_square-green'
	});
	
	$('input').on('ifChanged', function(event){ updateTraceStoryID($(event.target).val(),id_con_booking,ord_schedule_id,supplier_contact_id); });	
}


function updateTraceStoryID(story_id,id_con_booking,ord_schedule_id,supplier_contact_id){
	var resurl='include/logistic.php?elemid=update_traceability_story&story_id='+story_id+'&id_con_booking='+id_con_booking;
    var xhr = getXhr();    
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;    
			
			if(leselect == 1){   
				toastr.success('Story saved successfully',{timeOut:15000})
				refreshStoryList(supplier_contact_id,story_id);
				hide_trace_story_editBtn(ord_schedule_id,id_con_booking,supplier_contact_id);
	
			} else 
			if(leselect == 0){   
				toastr.error('Error saving story, please retry',{timeOut:15000})
			} else {
				internal_error();
			}

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);	
}


function refreshStoryList(supplier_contact_id,story_id){
	var resurl='include/logistic.php?elemid=refresh_traceability_story&supplier_contact_id='+supplier_contact_id+'&trace_story_id='+story_id;
    var xhr = getXhr();    
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;    

			document.getElementById('trace_story').innerHTML = leselect;
			
			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);	
}


function hide_trace_story_editBtn(ord_schedule_id,id_con_booking,supplier_contact_id) {
	
	$('#traceabilityStoryID').find('input, textarea, select').prop("disabled", true);
	$('.trace_story_check').removeClass('hide'); 
	$('.traceStoryList').addClass('hide'); 
	
	document.getElementById('EditTraceStory').innerHTML = '<button class="btn btn-success pull-right" id="EditTraceStoryBtn" onclick="show_traceStory_editBtn(\''+ord_schedule_id+'\');" style="margin-top:10px;" type="button"><i class="fa fa-edit"></i></button>';
}


function save_traceability_story(ord_schedule_id,id_con_booking,supplier_contact_id) {
	hide_trace_story_editBtn(ord_schedule_id,id_con_booking,supplier_contact_id);
}


// Container

function show_container_editBtn(id_con_booking,ord_schedule_id) {
	$('#collapseContainer').find('input, select, textarea').prop("disabled", false);   
	$(".containers_action_tb").removeClass("hide");
	
	document.getElementById('editContBtn').innerHTML = '<button onclick="container_cancelBtn(\''+id_con_booking+'\',\''+ord_schedule_id+'\');" class="btn btn-danger pull-right"><i class="fa fa-ban"></i></button>'
		+'<button onclick="save_container(\''+id_con_booking+'\',\''+ord_schedule_id+'\');" style="margin-right:10px;" class="btn btn-success pull-right"><i class="fa fa-save"></i></button>';

}

function container_cancelBtn(id_con_booking,ord_schedule_id) {
	$('#collapseContainer').find('input, select, textarea').prop("disabled", true);
	$(".containers_action_tb").addClass("hide");
	
	document.getElementById('editContBtn').innerHTML = '<button class="btn btn-success pull-right" onclick="show_container_editBtn(\''+id_con_booking+'\',\''+ord_schedule_id+'\');" style="margin-top:10px;" type="button"><i class="fa fa-edit"></i></button>';
}

/* 
* Booking add
* Edit, Save and Cancel management */

function show_ocean_loading_editBtn_add(id_con_booking,ord_schedule_id,ref_num,req) {
	
	$('#bookingTabID_add').find('input, textarea, select').prop("disabled", false);
	
	if(req=='update'){
		document.getElementById('EditOcenLoadingBtnID2_add').innerHTML = '<button class="btn btn-success pull-right" id="booking_btn_add" onclick="save_booking(\''+ord_schedule_id+'\',\''+ref_num+'\',\''+req+'\',\''+id_con_booking+'\');" style="margin-top:10px;" type="button"><i class="fa fa-spinner fa-spin hide" id="booking_btn_spnr_add"></i> <i class="fa fa-save"></i></button>'
			+'&nbsp;<button class="btn btn-danger pull-right" onclick="hide_ocean_loading_editBtn_add(\''+id_con_booking+'\',\''+ord_schedule_id+'\',\''+ref_num+'\',\''+req+'\');" style="margin-top:10px; margin-right:10px;" type="button"><i class="fa fa-ban"></i></button>'; 
			
		document.getElementById('EditOcenLoadingBtnID_add').innerHTML = '<a href="#" style="color:#FFF;" onclick="save_booking(\''+ord_schedule_id+'\',\''+ref_num+'\',\''+req+'\',\''+id_con_booking+'\');"> <i class="fa fa-check"></i> </a>'
			+'&nbsp;<a href="#" onclick="hide_ocean_loading_editBtn_add(\''+id_con_booking+'\',\''+ord_schedule_id+'\',\''+ref_num+'\',\''+req+'\');" style="color:#FFF;"> <i class="fa fa-times"></i> </a>';
	} else {
		document.getElementById('EditOcenLoadingBtnID2_add').innerHTML = '<button class="btn btn-primary pull-right" id="booking_btn_add" onclick="save_booking_add(\''+ord_schedule_id+'\',\''+ref_num+'\',\'add\',\''+id_con_booking+'\');" style="margin-top:10px;" type="button"><i class="fa fa-spinner fa-spin hide" id="booking_btn_spnr_add"></i> <i class="fa fa-plus"></i></button>'
			+'&nbsp;<button class="btn btn-danger pull-right" onclick="hide_ocean_loading_editBtn_add(\''+id_con_booking+'\',\''+ord_schedule_id+'\',\''+ref_num+'\',\''+req+'\');" style="margin-top:10px; margin-right:10px;" type="button"><i class="fa fa-ban"></i></button>'; 
			
		document.getElementById('EditOcenLoadingBtnID_add').innerHTML = '<a href="#" style="color:#FFF;" onclick="save_booking_add(\''+ord_schedule_id+'\',\''+ref_num+'\',\'add\',\''+id_con_booking+'\');"> <i class="fa fa-check"></i> </a>'
			+'&nbsp;<a href="#" onclick="hide_ocean_loading_editBtn_add(\''+id_con_booking+'\',\''+ord_schedule_id+'\',\''+ref_num+'\',\''+req+'\');" style="color:#FFF;"> <i class="fa fa-times"></i> </a>';
	}	
}


function hide_ocean_loading_editBtn_add(id_con_booking,ord_schedule_id,ref_num,req) {
	
	$('#bookingTabID_add').find('input, textarea, select').prop("disabled", true);
	document.getElementById('EditOcenLoadingBtnID2_add').innerHTML = '<button class="btn btn-success pull-right" onclick="show_ocean_loading_editBtn_add(\''+id_con_booking+'\',\''+ord_schedule_id+'\',\''+ref_num+'\',\''+req+'\');" style="margin-top:10px;" type="button"><i class="fa fa-edit"></i></button>';
	
	document.getElementById('EditOcenLoadingBtnID_add').innerHTML = '<a href="#" style="color:#FFF;" onclick="show_ocean_loading_editBtn_add(\''+id_con_booking+'\',\''+ord_schedule_id+'\',\''+ref_num+'\',\''+req+'\');"> <i class="fa fa-edit"></i> </a>';
}

function edit_ocean_loading_add(id_con_booking,ref_num,ord_schedule_id,conf) {

	$('#bookingTabID_add').find('input, textarea, select').prop("disabled", false);

	document.getElementById('EditOcenLoadingBtnID_add').innerHTML = '<a href="#" style="color:#FFF;" onclick="save_booking(\''+ord_schedule_id+'\',\''+ref_num+'\',\''+conf+'\',\''+id_con_booking+'\');"> <i class="fa fa-check"></i> </a>'
		+'&nbsp;<a href="#" onclick="cancel_ocean_loading_add(\''+id_con_booking+'\',\''+ref_num+'\',\''+conf+'\',\''+ord_schedule_id+'\');" style="color:#FFF;"> <i class="fa fa-times"></i> </a>';

	document.getElementById('EditOcenLoadingBtnID2_add').innerHTML = '<button class="btn btn-success pull-right" id="booking_btn_add" onclick="save_booking(\''+ord_schedule_id+'\',\''+ref_num+'\',\''+conf+'\',\''+id_con_booking+'\');" style="margin-top:10px; margin-right:15px;" type="button"><i class="fa fa-spinner fa-spin hide" id="booking_btn_spnr_add"></i> <i class="fa fa-save"></i></button>'
		+'&nbsp;<button class="btn btn-danger pull-right" onclick="cancel_ocean_loading_add(\''+id_con_booking+'\',\''+ref_num+'\',\''+conf+'\',\''+ord_schedule_id+'\');" style="margin-top:10px;" type="button"><i class="fa fa-ban"></i></button>';  
}


function cancel_ocean_loading_add(id_con_booking,ref_num,conf,ord_schedule_id) {
	
	$('#bookingTabID_add').find('input, textarea, select').prop("disabled", true);
	
	document.getElementById('EditOcenLoadingBtnID_add').innerHTML = '<a href="#" style="color:#FFF;" onclick="edit_ocean_loading_add(\''+id_con_booking+'\',\''+ref_num+'\',\''+ord_schedule_id+'\',\''+conf+'\');"> <i class="fa fa-edit"></i> </a>';
	
	document.getElementById('EditOcenLoadingBtnID2_add').innerHTML = '<button class="btn btn-success pull-right" onclick="edit_ocean_loading_add(\''+id_con_booking+'\',\''+ref_num+'\',\''+ord_schedule_id+'\',\''+conf+'\');" style="margin-top:10px;" type="button"><i class="fa fa-edit"></i></button>';
}

// Container

function show_container_editBtn_add(id_con_booking,ord_schedule_id) {
	$('#collapseContainer_add').find('input, select, textarea').prop("disabled", false);   
	$(".containers_action_tb_add").removeClass("hide");
	
	document.getElementById('editContBtn_add').innerHTML = '<button onclick="container_cancelBtn_add(\''+id_con_booking+'\',\''+ord_schedule_id+'\');" class="btn btn-danger pull-right"><i class="fa fa-ban"></i></button>'
		+'<button onclick="save_container_add(\''+id_con_booking+'\',\''+ord_schedule_id+'\');" style="margin-right:10px;" class="btn btn-success pull-right"><i class="fa fa-save"></i></button>';

}

function container_cancelBtn_add(id_con_booking,ord_schedule_id) {
	$('#collapseContainer_add').find('input, select, textarea').prop("disabled", true);
	$(".containers_action_tb_add").addClass("hide");
	
	document.getElementById('editContBtn_add').innerHTML = '<button class="btn btn-success pull-right" onclick="show_container_editBtn_add(\''+id_con_booking+'\',\''+ord_schedule_id+'\');" style="margin-top:10px;" type="button"><i class="fa fa-edit"></i></button>';
}

/* 
* Onward Carriage
* Edit, Save and Cancel management */

function show_onward_editBtn(id_con_booking,ord_schedule_id,ref_num,req) {
	
	$('#onwardTabID').find('input, textarea, select').prop("disabled", false);

	if(req=='update'){ 
		document.getElementById('EditOnwardBtnID').innerHTML = '<a href="#" style="color:#FFF;" onclick="save_onward(\''+ord_schedule_id+'\',\''+ref_num+'\',\'edit\',\''+id_con_booking+'\');"> <i class="fa fa-check"></i> </a>'
		+'&nbsp;<a href="#" onclick="cancel_onward_carriage(\''+id_con_booking+'\',\''+ref_num+'\',\'edit\',\''+ord_schedule_id+'\');" style="color:#FFF;"> <i class="fa fa-times"></i> </a>';

		document.getElementById('EditOnwardBtnID2').innerHTML = '<button class="btn btn-danger pull-right" onclick="cancel_onward_carriage(\''+id_con_booking+'\',\''+ref_num+'\',\'edit\',\''+ord_schedule_id+'\');" style="margin-top:10px;" type="button"><i class="fa fa-ban"></i></button>'
		+'&nbsp;<button class="btn btn-success pull-right" id="onward_btn" onclick="save_onward(\''+ord_schedule_id+'\',\''+ref_num+'\',\'edit\',\''+id_con_booking+'\');" style="margin-top:10px; margin-right:15px;" type="button"><i class="fa fa-spinner fa-spin hide" id="onward_btn_spnr"></i> <i class="fa fa-save"></i></button>';  

	} else { 
		document.getElementById('EditOnwardBtnID2').innerHTML = '<button class="btn btn-danger pull-right" onclick="hide_onward_editBtn(\''+id_con_booking+'\',\''+ord_schedule_id+'\',\''+ref_num+'\',\''+req+'\');" style="margin-top:10px;" type="button"><i class="fa fa-ban"></i></button>'
			+'&nbsp;<button class="btn btn-success pull-right" id="onward_btn" onclick="save_onward(\''+ord_schedule_id+'\',\''+ref_num+'\',\'add\',\''+id_con_booking+'\');" style="margin-top:10px; margin-right:10px;" type="button"><i class="fa fa-spinner fa-spin hide" id="onward_btn_spnr"></i> <i class="fa fa-plus"></i></button>'; 
		
		document.getElementById('EditOnwardBtnID').innerHTML = '<a href="#" style="color:#FFF;" onclick="save_onward(\''+ord_schedule_id+'\',\''+ref_num+'\',\'add\',\''+id_con_booking+'\');"> <i class="fa fa-check"></i> </a>'
			+'&nbsp;<a href="#" onclick="hide_onward_editBtn(\''+id_con_booking+'\',\''+ord_schedule_id+'\',\''+ref_num+'\',\''+req+'\');" style="color:#FFF;"> <i class="fa fa-times"></i> </a>';
	}	
}


function hide_onward_editBtn(id_con_booking,ord_schedule_id,ref_num,req) {
	
	$('#onwardTabID').find('input, textarea, select').prop("disabled", true);
	document.getElementById('EditOnwardBtnID2').innerHTML = '<button class="btn btn-success pull-right" onclick="show_onward_editBtn(\''+id_con_booking+'\',\''+ord_schedule_id+'\',\''+ref_num+'\',\''+req+'\');" style="margin-top:10px;" type="button"><i class="fa fa-edit"></i></button>';
	
	document.getElementById('EditOnwardBtnID').innerHTML = '<a href="#" style="color:#FFF;" onclick="show_onward_editBtn(\''+id_con_booking+'\',\''+ord_schedule_id+'\',\''+ref_num+'\',\''+req+'\');"> <i class="fa fa-edit"></i> </a>';
}


function edit_onward_carriage(id_con_booking,ref_num,ord_schedule_id,conf) {

	$('#onwardTabID').find('input, textarea, select').prop("disabled", false);

	document.getElementById('EditOnwardBtnID').innerHTML = '<a href="#" style="color:#FFF;" onclick="save_onward(\''+ord_schedule_id+'\',\''+ref_num+'\',\''+conf+'\',\''+id_con_booking+'\');"> <i class="fa fa-check"></i> </a>'
		+'&nbsp;<a href="#" onclick="cancel_onward_carriage(\''+id_con_booking+'\',\''+ref_num+'\',\''+conf+'\',\''+ord_schedule_id+'\');" style="color:#FFF;"> <i class="fa fa-times"></i> </a>';

	document.getElementById('EditOnwardBtnID2').innerHTML = '<button class="btn btn-danger pull-right" onclick="cancel_onward_carriage(\''+id_con_booking+'\',\''+ref_num+'\',\''+conf+'\',\''+ord_schedule_id+'\');" style="margin-top:10px;" type="button"><i class="fa fa-ban"></i></button>'
		+'&nbsp;<button class="btn btn-success pull-right" id="onward_btn" onclick="save_onward(\''+ord_schedule_id+'\',\''+ref_num+'\',\''+conf+'\',\''+id_con_booking+'\');" style="margin-top:10px; margin-right:15px;" type="button"><i class="fa fa-spinner fa-spin hide" id="onward_btn_spnr"></i> <i class="fa fa-save"></i></button>';  
}


function cancel_onward_carriage(id_con_booking,ref_num,conf,ord_schedule_id) {
	
	$('#onwardTabID').find('input, textarea, select').prop("disabled", true);
	
	document.getElementById('EditOnwardBtnID').innerHTML = '<a href="#" style="color:#FFF;" onclick="edit_onward_carriage(\''+id_con_booking+'\',\''+ref_num+'\',\''+ord_schedule_id+'\',\''+conf+'\');"> <i class="fa fa-edit"></i> </a>';
	
	document.getElementById('EditOnwardBtnID2').innerHTML = '<button class="btn btn-success pull-right" onclick="edit_onward_carriage(\''+id_con_booking+'\',\''+ref_num+'\',\''+ord_schedule_id+'\',\''+conf+'\');" style="margin-top:10px;" type="button"><i class="fa fa-edit"></i></button>';
}

// Container

function show_container_editBtn_onward(id_con_booking,ord_schedule_id) {
	$('#collapseCarriageContainer').find('input, select, textarea').prop("disabled", false);   
	$(".containers_action_tb_onward").removeClass("hide");
	
	document.getElementById('editContBtn_onward').innerHTML = '<button onclick="container_cancelBtn_onward(\''+id_con_booking+'\',\''+ord_schedule_id+'\');" class="btn btn-danger pull-right"><i class="fa fa-ban"></i></button>'
		+'<button onclick="hide_container_editBtn(\''+id_con_booking+'\',\''+ord_schedule_id+'\');" style="margin-right:10px;" class="btn btn-success pull-right"><i class="fa fa-save"></i></button>';

}

function container_cancelBtn_onward(id_con_booking,ord_schedule_id) {
	$('#collapseCarriageContainer').find('input, select, textarea').prop("disabled", true);
	$(".containers_action_tb_onward").addClass("hide");
	
	document.getElementById('editContBtn_onward').innerHTML = '<button class="btn btn-success pull-right" onclick="show_container_editBtn_onward(\''+id_con_booking+'\',\''+ord_schedule_id+'\');" style="margin-top:10px;" type="button"><i class="fa fa-edit"></i></button>';
}

function hide_container_editBtn(id_con_booking,ord_schedule_id) {
	toastr.success('Container editing saved successfully',{timeOut:15000})
	container_cancelBtn_onward(id_con_booking,ord_schedule_id);
}

// Container Disposition

function show_container_dispo_editBtn_onward(id_con_booking,ord_schedule_id) {
	$('#collapseConPositioning_add1').find('input, select, textarea').prop("disabled", false);   
	$(".containers_dispo_action_tb_onward").removeClass("hide");
	
	document.getElementById('editContDispoBtn_onward').innerHTML = '<button onclick="container_dispo_cancelBtn_onward(\''+id_con_booking+'\',\''+ord_schedule_id+'\');" class="btn btn-danger pull-right"><i class="fa fa-ban"></i></button>'
		+'<button onclick="hide_container_dispo_editBtn(\''+id_con_booking+'\',\''+ord_schedule_id+'\');" style="margin-right:10px;" class="btn btn-success pull-right"><i class="fa fa-save"></i></button>';

}

function container_dispo_cancelBtn_onward(id_con_booking,ord_schedule_id) {
	$('#collapseConPositioning_add1').find('input, select, textarea').prop("disabled", true);
	$(".containers_dispo_action_tb_onward").addClass("hide");
	
	document.getElementById('editContDispoBtn_onward').innerHTML = '<button class="btn btn-success pull-right" onclick="show_container_dispo_editBtn_onward(\''+id_con_booking+'\',\''+ord_schedule_id+'\');" style="margin-top:10px;" type="button"><i class="fa fa-edit"></i></button>';
}

function hide_container_dispo_editBtn(id_con_booking,ord_schedule_id) {
	toastr.success('Container disposition editing saved successfully',{timeOut:15000})
	container_dispo_cancelBtn_onward(id_con_booking,ord_schedule_id);
}

/* 
* Onward Carriage Add
* Edit, Save and Cancel management */

function show_onward_editBtn_add(id_con_booking,ord_schedule_id,ref_num,req) {
	
	$('#onwardTabID_add').find('input, textarea, select').prop("disabled", false);

	if(req=='update'){ 
		document.getElementById('EditOnwardBtnID2_add').innerHTML = '<button class="btn btn-danger pull-right" onclick="hide_onward_editBtn_add(\''+id_con_booking+'\',\''+ord_schedule_id+'\',\''+ref_num+'\',\''+req+'\');" style="margin-top:10px;" type="button"><i class="fa fa-ban"></i></button>'
			+'&nbsp;<button class="btn btn-success pull-right" id="onward_btn_add" onclick="edit_onward_carriage_add(\''+id_con_booking+'\',\''+ref_num+'\',\''+ord_schedule_id+'\',\'edit\');" style="margin-top:10px; margin-right:10px;" type="button"><i class="fa fa-spinner fa-spin hide" id="onward_btn_spnr_add"></i> <i class="fa fa-save"></i></button>'; 
		
		document.getElementById('EditOnwardBtnID_add').innerHTML = '<a href="#" style="color:#FFF;" onclick="edit_onward_carriage_add(\''+id_con_booking+'\',\''+ref_num+'\',\''+ord_schedule_id+'\',\'edit\');"> <i class="fa fa-check"></i> </a>'
			+'&nbsp;<a href="#" onclick="hide_onward_editBtn_add(\''+id_con_booking+'\',\''+ord_schedule_id+'\',\''+ref_num+'\',\''+req+'\');" style="color:#FFF;"> <i class="fa fa-times"></i> </a>';
	} else { 
		document.getElementById('EditOnwardBtnID2_add').innerHTML = '<button class="btn btn-danger pull-right" onclick="hide_onward_editBtn_add(\''+id_con_booking+'\',\''+ord_schedule_id+'\',\''+ref_num+'\',\''+req+'\');" style="margin-top:10px;" type="button"><i class="fa fa-ban"></i></button>'
			+'&nbsp;<button class="btn btn-success pull-right" id="onward_btn_add" onclick="save_onward(\''+ord_schedule_id+'\',\''+ref_num+'\',\'add\',\''+id_con_booking+'\');" style="margin-top:10px; margin-right:10px;" type="button"><i class="fa fa-spinner fa-spin hide" id="onward_btn_spnr_add"></i> <i class="fa fa-plus"></i></button>'; 
		
		document.getElementById('EditOnwardBtnID_add').innerHTML = '<a href="#" style="color:#FFF;" onclick="save_onward(\''+ord_schedule_id+'\',\''+ref_num+'\',\'add\',\''+id_con_booking+'\');"> <i class="fa fa-check"></i> </a>'
			+'&nbsp;<a href="#" onclick="hide_onward_editBtn_add(\''+id_con_booking+'\',\''+ord_schedule_id+'\',\''+ref_num+'\',\''+req+'\');" style="color:#FFF;"> <i class="fa fa-times"></i> </a>';
	}	
}


function hide_onward_editBtn_add(id_con_booking,ord_schedule_id,ref_num,req) {
	
	$('#onwardTabID_add').find('input, textarea, select').prop("disabled", true);
	document.getElementById('EditOnwardBtnID2_add').innerHTML = '<button class="btn btn-success pull-right" onclick="show_onward_editBtn_add(\''+id_con_booking+'\',\''+ord_schedule_id+'\',\''+ref_num+'\',\''+req+'\');" style="margin-top:10px;" type="button"><i class="fa fa-edit"></i></button>';
	
	document.getElementById('EditOnwardBtnID_add').innerHTML = '<a href="#" style="color:#FFF;" onclick="show_onward_editBtn_add(\''+id_con_booking+'\',\''+ord_schedule_id+'\',\''+ref_num+'\',\''+req+'\');"> <i class="fa fa-edit"></i> </a>';
}


function edit_onward_carriage_add(id_con_booking,ref_num,ord_schedule_id,conf) {

	$('#onwardTabID_add').find('input, textarea, select').prop("disabled", false);

	document.getElementById('EditOnwardBtnID_add').innerHTML = '<a href="#" style="color:#FFF;" onclick="save_onward(\''+ord_schedule_id+'\',\''+ref_num+'\',\''+conf+'\',\''+id_con_booking+'\');"> <i class="fa fa-check"></i> </a>'
		+'&nbsp;<a href="#" onclick="cancel_onward_carriage_add(\''+id_con_booking+'\',\''+ref_num+'\',\''+conf+'\',\''+ord_schedule_id+'\');" style="color:#FFF;"> <i class="fa fa-times"></i> </a>';

	document.getElementById('EditOnwardBtnID2_add').innerHTML = '<button class="btn btn-danger pull-right" onclick="cancel_onward_carriage_add(\''+id_con_booking+'\',\''+ref_num+'\',\''+conf+'\',\''+ord_schedule_id+'\');" style="margin-top:10px;" type="button"><i class="fa fa-ban"></i></button>'
		+'&nbsp;<button class="btn btn-success pull-right" id="onward_btn_add" onclick="save_onward(\''+ord_schedule_id+'\',\''+ref_num+'\',\''+conf+'\',\''+id_con_booking+'\');" style="margin-top:10px; margin-right:15px;" type="button"><i class="fa fa-spinner fa-spin hide" id="onward_btn_spnr_add"></i> <i class="fa fa-save"></i></button>';  
}


function cancel_onward_carriage_add(id_con_booking,ref_num,conf,ord_schedule_id) {
	
	$('#onwardTabID_add').find('input, textarea, select').prop("disabled", true);
	
	document.getElementById('EditOnwardBtnID_add').innerHTML = '<a href="#" style="color:#FFF;" onclick="edit_onward_carriage_add(\''+id_con_booking+'\',\''+ref_num+'\',\''+ord_schedule_id+'\',\''+conf+'\');"> <i class="fa fa-edit"></i> </a>';
	
	document.getElementById('EditOnwardBtnID2_add').innerHTML = '<button class="btn btn-success pull-right" onclick="edit_onward_carriage_add(\''+id_con_booking+'\',\''+ref_num+'\',\''+ord_schedule_id+'\',\''+conf+'\');" style="margin-top:10px;" type="button"><i class="fa fa-edit"></i></button>';
}

// Container 

function show_container_editBtn_onward_add(id_con_booking,ord_schedule_id) {
	$('#collapseCarriageContainer_add').find('input, select, textarea').prop("disabled", false);   
	$(".containers_action_tb_onward_add").removeClass("hide");
	
	document.getElementById('editContBtn_onward_add').innerHTML = '<button onclick="container_cancelBtn_onward_add(\''+id_con_booking+'\',\''+ord_schedule_id+'\');" class="btn btn-danger pull-right"><i class="fa fa-ban"></i></button>'
		+'<button onclick="hide_container_editBtn_add(\''+id_con_booking+'\',\''+ord_schedule_id+'\');" style="margin-right:10px;" class="btn btn-success pull-right"><i class="fa fa-save"></i></button>';

}

function container_cancelBtn_onward_add(id_con_booking,ord_schedule_id) {
	$('#collapseCarriageContainer_add').find('input, select, textarea').prop("disabled", true);
	$(".containers_action_tb_onward_add").addClass("hide");
	
	document.getElementById('editContBtn_onward_add').innerHTML = '<button class="btn btn-success pull-right" onclick="show_container_editBtn_onward_add(\''+id_con_booking+'\',\''+ord_schedule_id+'\');" style="margin-top:10px;" type="button"><i class="fa fa-edit"></i></button>';
}

function hide_container_editBtn_add(id_con_booking,ord_schedule_id) {
	toastr.success('Container editing saved successfully',{timeOut:15000})
	container_cancelBtn_onward(id_con_booking,ord_schedule_id);
}

// Container Disposition

function show_container_dispo_editBtn_onward_add(id_con_booking,ord_schedule_id) {
	$('#collapseConPositioning_add2').find('input, select, textarea').prop("disabled", false);   
	$(".containers_dispo_action_tb_onward_add").removeClass("hide");
	
	document.getElementById('editContDispoBtn_onward_add').innerHTML = '<button onclick="container_dispo_cancelBtn_onward_add(\''+id_con_booking+'\',\''+ord_schedule_id+'\');" class="btn btn-danger pull-right"><i class="fa fa-ban"></i></button>'
		+'<button onclick="hide_container_dispo_editBtn_add(\''+id_con_booking+'\',\''+ord_schedule_id+'\');" style="margin-right:10px;" class="btn btn-success pull-right"><i class="fa fa-save"></i></button>';

}

function container_dispo_cancelBtn_onward_add(id_con_booking,ord_schedule_id) {
	$('#collapseConPositioning_add2').find('input, select, textarea').prop("disabled", true);
	$(".containers_dispo_action_tb_onward_add").addClass("hide");
	
	document.getElementById('editContDispoBtn_onward_add').innerHTML = '<button class="btn btn-success pull-right" onclick="show_container_dispo_editBtn_onward_add(\''+id_con_booking+'\',\''+ord_schedule_id+'\');" style="margin-top:10px;" type="button"><i class="fa fa-edit"></i></button>';
}

function hide_container_dispo_editBtn_add(id_con_booking,ord_schedule_id) {
	toastr.success('Container disposition editing saved successfully',{timeOut:15000})
	container_dispo_cancelBtn_onward_add(id_con_booking,ord_schedule_id);
}


function contPositionningHdr(value) { 
	if(value == 0){
		$("#collapseConPositioning").addClass("in");
	} else {
		$("#collapseConPositioning").removeClass("in");
	}
}


function isoAvailable(iso_available) {
	if(iso_available == 1){
		$('#iso_yes').iCheck('check');
	} else {
		$('#iso_no').iCheck('check');
	}
}


function save_container(id_con_booking,ord_schedule_id) { 
	
	var req='';
	var iso_available = 0;
	var ids_multiple_agent = 0;
	
	var iso_booking = document.getElementById('iso_booking').value;
	if(iso_booking){ req=req+'&iso_booking='+iso_booking; }
	
	var iso_vessel_name = document.getElementById('iso_vessel_name').value;
	if(iso_vessel_name){ req=req+'&iso_vessel_name='+iso_vessel_name; }
	
	var iso_vessel_mmsi = document.getElementById('iso_vessel_mmsi').value;
	if(iso_vessel_mmsi){ req=req+'&iso_vessel_mmsi='+iso_vessel_mmsi; }
	
	var iso_pol = document.getElementById('iso_pol').value;
	if(iso_pol){ req=req+'&iso_pol='+iso_pol; }
	
	if($("input[type='radio'].iso_available").is(':checked')) {
		iso_available = $("input[type='radio'].iso_available:checked").val();
	}
	if(iso_available){ req=req+'&iso_available='+iso_available; }
	
	var iso_etd = document.getElementById('iso_etd').value;
	if(iso_etd){ req=req+'&iso_etd='+iso_etd; }
	
	var iso_eta = document.getElementById('iso_eta').value;
	if(iso_eta){ req=req+'&iso_eta='+iso_eta; }
	
	var iso_date_available = document.getElementById('iso_date_available').value;
	if(iso_date_available){ req=req+'&iso_date_available='+iso_date_available; }
	
	var con_load_date_from = document.getElementById('con_load_date_from').value;
	if(con_load_date_from){ req=req+'&con_load_date_from='+con_load_date_from; }
	
	var con_load_date_to = document.getElementById('con_load_date_to').value;
	if(con_load_date_to){ req=req+'&con_load_date_to='+con_load_date_to; }

	var booking_type_id = document.getElementById('booking_type_id').value;
	if(booking_type_id){ req=req+'&booking_type_id='+booking_type_id; }

	// Laoding Status
	var end_state = document.getElementById('con_end_state').value;
	if(end_state){ req=req+'&end_state='+end_state; }
	
	var no_con_shipped = document.getElementById('no_con_shipped').value;
	if(no_con_shipped){ req=req+'&no_con_shipped='+no_con_shipped; }

	var container_nr = document.getElementById('cont_container_nr').value;
	if(container_nr){ req=req+'&container_nr='+container_nr; }

	var loadin_diff = document.getElementById('cont_loading_diff').value;
	if(loadin_diff){ req=req+'&loadin_diff='+loadin_diff; }

	var loading_note = document.getElementById('con_loading_note').value;
	if(loading_note){ req=req+'&loading_note='+loading_note; }

	var loading_manager_id = document.getElementById('b_loading_manager_id').value;  
	if(loading_manager_id){ req=req+'&loading_manager_id='+loading_manager_id; }
	
	if($("input[type='radio'].ids_agent_radio").is(':checked')) {
		ids_multiple_agent = $("input[type='radio'].ids_agent_radio:checked").val();
	}
	if(ids_multiple_agent){ req=req+'&ids_multiple_agent='+ids_multiple_agent; }  
	
	var sync_agent_1 = document.getElementById('loading_agent_1').value;  
	if(sync_agent_1){ req=req+'&sync_agent_1='+sync_agent_1; }
	
	var sync_agent_2 = document.getElementById('loading_agent_2').value;  
	if(sync_agent_2){ req=req+'&sync_agent_2='+sync_agent_2; }
	
	var loading_place = document.getElementById('loading_place').value;  
	if(loading_place){ req=req+'&loading_place='+loading_place; }

	var resurl='include/logistic.php?elemid=update_container_booking&ord_schedule_id='+ord_schedule_id+'&id_con_booking='+id_con_booking+'&booking_segment=1'+req;
    var xhr = getXhr();    
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;    
			
			if(leselect == 1){   
				toastr.success('Booking saved successfully',{timeOut:15000})
				container_cancelBtn(id_con_booking,ord_schedule_id);
				
				// if(end_state == 1){ 
					// logistique(0,0);
				// }
				
			} else 
			if(leselect == 0){   
				toastr.error('Error saving booking, please retry',{timeOut:15000})
			} else {
				internal_error();
			}

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);	
}


function save_container_add(id_con_booking,ord_schedule_id) { 

	var req='';
	var iso_available = 0;
	var ids_multiple_agent = 0;
	
	var iso_booking = document.getElementById('iso_booking_add').value;
	if(iso_booking){ req=req+'&iso_booking='+iso_booking; }
	
	var iso_vessel_name = document.getElementById('iso_vessel_name_add').value;
	if(iso_vessel_name){ req=req+'&iso_vessel_name='+iso_vessel_name; }
	
	var iso_vessel_mmsi = document.getElementById('iso_vessel_mmsi_add').value;
	if(iso_vessel_mmsi){ req=req+'&iso_vessel_mmsi='+iso_vessel_mmsi; }
	
	var iso_pol = document.getElementById('iso_pol_add').value;
	if(iso_pol){ req=req+'&iso_pol='+iso_pol; }
	
	if($("input[type='radio'].iso_available_add").is(':checked')) {
		iso_available = $("input[type='radio'].iso_available_add:checked").val();
	}
	if(iso_available){ req=req+'&iso_available='+iso_available; }
	
	var iso_etd = document.getElementById('iso_etd_add').value;
	if(iso_etd){ req=req+'&iso_etd='+iso_etd; }
	
	var iso_eta = document.getElementById('iso_eta_add').value;
	if(iso_eta){ req=req+'&iso_eta='+iso_eta; }
	
	var iso_date_available = document.getElementById('iso_date_available_add').value;
	if(iso_date_available){ req=req+'&iso_date_available='+iso_date_available; }
	
	var con_load_date_from = document.getElementById('con_load_date_from_add').value;
	if(con_load_date_from){ req=req+'&con_load_date_from='+con_load_date_from; }
	
	var con_load_date_to = document.getElementById('con_load_date_to_add').value;
	if(con_load_date_to){ req=req+'&con_load_date_to='+con_load_date_to; }

	var booking_type_id = document.getElementById('booking_type_id_add').value;
	if(booking_type_id){ req=req+'&booking_type_id='+booking_type_id; }

	// Laoding Status
	var end_state = document.getElementById('con_end_state_add').value;
	if(end_state){ req=req+'&end_state='+end_state; }
	
	var no_con_shipped = document.getElementById('no_con_shipped_add').value;
	if(no_con_shipped){ req=req+'&no_con_shipped='+no_con_shipped; }

	var loading_note = document.getElementById('con_loading_note_add').value;
	if(loading_note){ req=req+'&loading_note='+loading_note; }

	var loading_manager_id = document.getElementById('badd_loading_manager_id').value;  
	if(loading_manager_id){ req=req+'&loading_manager_id='+loading_manager_id; }
	
	if($("input[type='radio'].ids_agent_radio_add").is(':checked')) {
		ids_multiple_agent = $("input[type='radio'].ids_agent_radio_add:checked").val();
	}
	if(ids_multiple_agent){ req=req+'&ids_multiple_agent='+ids_multiple_agent; } 
	
	var loading_manager_id = document.getElementById('b_loading_manager_id').value;  
	if(loading_manager_id){ req=req+'&loading_manager_id='+loading_manager_id; }

	var resurl='include/logistic.php?elemid=update_container_booking&ord_schedule_id='+ord_schedule_id+'&id_con_booking='+id_con_booking+'&booking_segment=2'+req;
    var xhr = getXhr();    
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;    
			
			if(leselect == 1){   
				toastr.success('Booking saved successfully',{timeOut:15000})
				container_cancelBtn(id_con_booking,ord_schedule_id);
				
				if(end_state == 1){ 
					logistique(0,0);
				}
				
			} else 
			if(leselect == 0){
				toastr.error('Error saving booking, please retry',{timeOut:15000})
			} else {
				internal_error();
			}

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function getDiff() {
	var no_con_shipped = document.getElementById('no_con_shipped').value;
	var container_nr = document.getElementById('cont_container_nr').value;
	
	var diff = container_nr - no_con_shipped;  
	document.getElementById('cont_loading_diff').value = diff;
	
	if(diff>0){
		// $("#addBooking_btn").removeClass("hide");  
	} else {
		$("#addBooking_btn").addClass("hide");  
	}
}

// OCEAN Booking 
function show_shippingdoc_editBtn(req,ord_schedule_id,ord_con_list_id,id_con_booking) {
	$('#freight_shipping').find('input, select').prop("disabled", false);   
	
	if(req=='update'){
		document.getElementById('editShippingDoc').innerHTML = '<button onclick="shippingdoc_cancelBtn(\''+req+'\',\''+ord_schedule_id+'\',\''+ord_con_list_id+'\',\''+id_con_booking+'\');" class="btn btn-danger pull-right"><i class="fa fa-ban"></i></button>'
		+'<button onclick="save_shippingDoc(\''+req+'\',\''+ord_schedule_id+'\',\''+ord_con_list_id+'\',\''+id_con_booking+'\');" style="margin-right:10px;" class="btn btn-success pull-right"><i class="fa fa-save"></i></button>';
		
	} else {
		document.getElementById('editShippingDoc').innerHTML = '<button onclick="shippingdoc_cancelBtn(\''+req+'\',\''+ord_schedule_id+'\',\''+ord_con_list_id+'\',\''+id_con_booking+'\');" class="btn btn-danger pull-right"><i class="fa fa-ban"></i></button>'
		+'<button onclick="save_shippingDoc(\''+req+'\',\''+ord_schedule_id+'\',\''+ord_con_list_id+'\',\''+id_con_booking+'\');" style="margin-right:10px;" class="btn btn-success pull-right"><i class="fa fa-plus"></i></button>';
	}
}

function shippingdoc_cancelBtn(req,ord_schedule_id,ord_con_list_id,id_con_booking) {
	$('#freight_shipping').find('input, select').prop("disabled", true);
	
	document.getElementById('editShippingDoc').innerHTML = '<button class="btn btn-success pull-right" onclick="show_shippingdoc_editBtn(\''+req+'\',\''+ord_schedule_id+'\',\''+ord_con_list_id+'\',\''+id_con_booking+'\');" style="margin-top:10px;" type="button"><i class="fa fa-edit"></i></button>';
}


// OCEAN Booking add
function show_shippingdoc_editBtn_add(req,ord_schedule_id,ord_con_list_id,id_con_booking) {
	$('#freight_shipping_add').find('input, select').prop("disabled", false);   
	
	if(req=='update'){
		document.getElementById('editShippingDoc_add').innerHTML = '<button onclick="shippingdoc_cancelBtn_add(\''+req+'\',\''+ord_schedule_id+'\',\''+ord_con_list_id+'\',\''+id_con_booking+'\');" class="btn btn-danger pull-right"><i class="fa fa-ban"></i></button>'
		+'<button onclick="save_shippingDoc_add(\''+req+'\',\''+ord_schedule_id+'\',\''+ord_con_list_id+'\',\''+id_con_booking+'\');" style="margin-right:10px;" class="btn btn-success pull-right"><i class="fa fa-save"></i></button>';
		
	} else {
		document.getElementById('editShippingDoc_add').innerHTML = '<button onclick="shippingdoc_cancelBtn_add(\''+req+'\',\''+ord_schedule_id+'\',\''+ord_con_list_id+'\',\''+id_con_booking+'\');" class="btn btn-danger pull-right"><i class="fa fa-ban"></i></button>'
		+'<button onclick="save_shippingDoc_add(\''+req+'\',\''+ord_schedule_id+'\',\''+ord_con_list_id+'\',\''+id_con_booking+'\');" style="margin-right:10px;" class="btn btn-success pull-right"><i class="fa fa-plus"></i></button>';
	}
}

function shippingdoc_cancelBtn_add(req,ord_schedule_id,ord_con_list_id,id_con_booking) {
	$('#freight_shipping_add').find('input, select').prop("disabled", true);
	
	document.getElementById('editShippingDoc_add').innerHTML = '<button class="btn btn-success pull-right" onclick="show_shippingdoc_editBtn_add(\''+req+'\',\''+ord_schedule_id+'\',\''+ord_con_list_id+'\',\''+id_con_booking+'\');" style="margin-top:10px;" type="button"><i class="fa fa-edit"></i></button>';
}	


/*
* Lab Analysis
* START
*/

// LAB Analysis edit btn
function show_labAna_editBtn(ord_order_id,ord_schedule_id,id_con_booking) {
	$('#lab_analysis').find('input, select').prop("disabled", false);  
	$('.labTble').removeClass("hide");
	$('.labTble_no').addClass("hide");
	
	document.getElementById('editLabAnalysis').innerHTML = '<button onclick="labAna_cancelBtn(\''+ord_order_id+'\',\''+ord_schedule_id+'\',\''+id_con_booking+'\');" class="btn btn-danger pull-right"><i class="fa fa-ban"></i></button>'
		+'<button onclick="savedLab(\''+ord_order_id+'\',\''+ord_schedule_id+'\',\''+id_con_booking+'\');" style="margin-right:10px;" class="btn btn-success pull-right"><i class="fa fa-save"></i></button>';
}

// LAB Analysis cancel btn
function labAna_cancelBtn(ord_order_id,ord_schedule_id,id_con_booking) {
	$('#lab_analysis').find('input, select').prop("disabled", true);
	$('.labTble').addClass("hide");
	$('.labTble_no').removeClass("hide");
	
	document.getElementById('editLabAnalysis').innerHTML = '<button class="btn btn-success pull-right" onclick="show_labAna_editBtn(\''+ord_order_id+'\',\''+ord_schedule_id+'\',\''+id_con_booking+'\');" style="margin-top:10px;" type="button"><i class="fa fa-edit"></i></button>';
}

// LAB Analysis save btn
function savedLab(ord_order_id,ord_schedule_id,id_con_booking) {
	document.getElementById("labForm").reset();
	labAna_cancelBtn(ord_order_id,ord_schedule_id,id_con_booking);
}

// Delete Lab Analysis
function deleteLabAna(id_analysis_item,ord_schedule_id,id_con_booking){
	
	var resurl='include/logistic.php?elemid=delete_labAnalysis&id_analysis_item='+id_analysis_item;
    var xhr = getXhr();    
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;  
			
			if(leselect == 1){  
				toastr.success('Lab Analysis successfully deleted',{timeOut:15000})
				refresh_labTable(ord_schedule_id,id_con_booking);
			
			} else 
			if(leselect == 0){
				toastr.error('Error deleting Lab Analysis, please retry',{timeOut:15000})
			} else {
				internal_error();
			}

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);	
}

// Show lab Analysis Edit Form
function editLabAna(id_analysis_item) {
	document.getElementById("labAnalysisForm").reset();
	document.getElementById('editLab_id_analysis_item').value = id_analysis_item;
	
	var resurl='include/logistic.php?elemid=labAnalysis_editForm&id_analysis_item='+id_analysis_item;
    var xhr = getXhr();    
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;    
			var val = leselect.split('##');
			
			document.getElementById('editLab_id_prod_params').innerHTML = val[0];
			document.getElementById('editLab_lab_result').value = val[1];
			document.getElementById('editLab_date_analysis').value = val[2];

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);	
}

//Lab Analysis Data Entry
function save_labDataEntry(ord_order_id,ord_schedule_id,id_con_booking){
	var resurl='listeslies.php?elemid=labAnalysis_dataEntry&ord_order_id='+ord_order_id+'&ord_schedule_id='+ord_schedule_id+'&id_con_booking='+id_con_booking;
    var xhr = getXhr();    
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText; 
			var val=leselect.split('##');
		
			if(val[0] == 1){   
				toastr.success('Certificate requested successfully saved',{timeOut:15000})  
				saveOrderTracePdf(ord_schedule_id,id_con_booking,val[1],'entry');
				$("#cert_rqst_btn").prop("disabled", true);  
				document.getElementById('cert_rqst_val').innerHTML = val[2]; 
				
			} else 
			if(val[0] == 0){   
				toastr.error('Error sending certificate request, please retry',{timeOut:15000})
			} else {
				internal_error();
			}
			
			
			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}

// Save Lab Analysis
function save_labAna(ord_order_id,ord_schedule_id,id_con_booking){
	var req="";
	
	var result = document.getElementById('lab_result').value;  
	if(result){ req=req+'&result='+result; }
	
	var date_analysis = document.getElementById('date_analysis').value;  
	if(date_analysis){ req=req+'&date_analysis='+date_analysis; }
	
	var product_id = document.getElementById('lab_product_id').value;  
	if(product_id){ req=req+'&product_id='+product_id; }
	
	var supplier_id = document.getElementById('lab_supplier_id').value;  
	if(supplier_id){ req=req+'&supplier_id='+supplier_id; }
	
	var id_prod_params = document.getElementById('id_prod_params').value;  
	if(id_prod_params){ req=req+'&id_prod_params='+id_prod_params; }
	
	var resurl='include/logistic.php?elemid=save_lab_analysis&ord_schedule_id='+ord_schedule_id+'&ord_order_id='+ord_order_id+'&id_con_booking='+id_con_booking+req;
    var xhr = getXhr();    
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;    
			
			if(leselect == 1){  
				toastr.success('Lab Analysis successfully saved',{timeOut:15000})
				refresh_labTable(ord_schedule_id,id_con_booking);
			
			} else 
			if(leselect == 0){
				toastr.error('Error saving Lab Analysis, please retry',{timeOut:15000})
			} else {
				internal_error();
			}

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);	
}

// Refresh Lab Analysis table
function refresh_labTable(ord_schedule_id,id_con_booking) {
	var resurl='include/logistic.php?elemid=refresh_lab_analysis_table&ord_schedule_id='+ord_schedule_id+'&id_con_booking='+id_con_booking+'&labAna_delete='+logLabAnalysis_delete;
    var xhr = getXhr();     
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;  
			
			document.getElementById('saved_lab_units').innerHTML = leselect;
	
			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}

// Edit Saved Lab Analysis
function saveEditedLabAna() {
	var req="";
	
	var id_prod_params = document.getElementById('editLab_id_prod_params').value;  
	if(id_prod_params){ req=req+'&id_prod_params='+id_prod_params; }
	
	var date_analysis = document.getElementById('editLab_date_analysis').value;  
	if(date_analysis){ req=req+'&date_analysis='+date_analysis; }
	
	var result = document.getElementById('editLab_lab_result').value;  
	if(result){ req=req+'&result='+result; }
	
	var id_analysis_item = document.getElementById('editLab_id_analysis_item').value;  
	if(id_analysis_item){ req=req+'&id_analysis_item='+id_analysis_item; }
	
	
	var resurl='include/logistic.php?elemid=edit_lab_analysis'+req;
    var xhr = getXhr();    
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;    
			
			if(leselect == 1){  
				toastr.success('Lab Analysis successfully saved',{timeOut:15000})
			
			} else 
			if(leselect == 0){
				toastr.error('Error saving Lab Analysis, please retry',{timeOut:15000})
			} else {
				internal_error();
			}

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);	
}


/*
* Lab Analysis
* END
*/


function save_shippingDoc(type,ord_schedule_id,ord_con_list_id,id_con_booking){
	var req="";
	
	var lab_awb_no = document.getElementById('lab_awb_no').value;  
	if(lab_awb_no){ req=req+'&lab_awb_no='+lab_awb_no; }
	
	var lab_cus_awb_date = document.getElementById('lab_cus_awb_date').value;  
	if(lab_cus_awb_date){ req=req+'&lab_cus_awb_date='+lab_cus_awb_date; }

	var fa_awb_no = document.getElementById('fa_awb_no').value;  
	if(fa_awb_no){ req=req+'&fa_awb_no='+fa_awb_no; }
	
	var fa_awb_date = document.getElementById('fa_awb_date').value;  
	if(fa_awb_date){ req=req+'&fa_awb_date='+fa_awb_date; }
	
	var cust_awb_no = document.getElementById('cust_awb_no').value; 
	if(cust_awb_no){ req=req+'&cust_awb_no='+cust_awb_no; }
	
	var cus_awb_date = document.getElementById('cus_awb_date').value; 
	if(cus_awb_date){ req=req+'&cus_awb_date='+cus_awb_date; }
	
	var lab_contact_id = document.getElementById('lab_contact_id').value;  
	if(lab_contact_id){ req=req+'&lab_contact_id='+lab_contact_id; }
	
	var bl_number = document.getElementById('bl_number').value;  
	if(bl_number){ req=req+'&bl_number='+bl_number; }
	
	var pol_etd_actual = document.getElementById('pol_etd_actual').value;  
	if(pol_etd_actual){ req=req+'&pol_etd_actual='+pol_etd_actual; }
	
	var resurl='include/logistic.php?elemid=save_shipping_documents&ord_schedule_id='+ord_schedule_id+'&ord_con_list_id='+ord_con_list_id+'&id_con_booking='+id_con_booking+req+'&type='+type;
    var xhr = getXhr();    
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;    
			
			if(leselect == 1){  
				toastr.success('Shipping document saved successfully',{timeOut:15000})
				shippingdoc_cancelBtn(type,ord_schedule_id,ord_con_list_id);
			
			} else 
			if(leselect == 0){
				toastr.error('Error saving shipping documents, please retry',{timeOut:15000})
			} else {
				internal_error();
			}

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);	
}


function save_shippingDoc_add(type,ord_schedule_id,ord_con_list_id,id_con_booking){
	var req="";
	
	var lab_awb_no = document.getElementById('lab_awb_no_add').value;
	if(lab_awb_no){ req=req+'&lab_awb_no='+lab_awb_no; }
	
	var lab_cus_awb_date = document.getElementById('lab_cus_awb_date_add').value;
	if(lab_cus_awb_date){ req=req+'&lab_cus_awb_date='+lab_cus_awb_date; }

	var fa_awb_no = document.getElementById('fa_awb_no_add').value;
	if(fa_awb_no){ req=req+'&fa_awb_no='+fa_awb_no; }
	
	var fa_awb_date = document.getElementById('fa_awb_date_add').value;
	if(fa_awb_date){ req=req+'&fa_awb_date='+fa_awb_date; }
	
	var cust_awb_no = document.getElementById('cust_awb_no_add').value;
	if(cust_awb_no){ req=req+'&cust_awb_no='+cust_awb_no; }
	
	var cus_awb_date = document.getElementById('cus_awb_date_add').value;
	if(cus_awb_date){ req=req+'&cus_awb_date='+cus_awb_date; }
	
	var lab_contact_id = document.getElementById('lab_contact_id_add').value;
	if(lab_contact_id){ req=req+'&lab_contact_id='+lab_contact_id; }
	
	var bl_number = document.getElementById('bl_number_add').value;  
	if(bl_number){ req=req+'&bl_number='+bl_number; }
	
	var pol_etd_actual = document.getElementById('pol_etd_actual_add').value;  
	if(pol_etd_actual){ req=req+'&pol_etd_actual='+pol_etd_actual; }
	
	var resurl='listeslies.php?elemid=save_shipping_documents&ord_schedule_id='+ord_schedule_id+'&ord_con_list_id='+ord_con_list_id+'&id_con_booking='+id_con_booking+req+'&type='+type;
    var xhr = getXhr();     
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;   
			
			if(leselect == 1){  
				toastr.success('Shipping document saved successfully',{timeOut:15000})
				shippingdoc_cancelBtn_add(type,ord_schedule_id,ord_con_list_id);
			
			} else 
			if(leselect == 0){  
				toastr.error('Error saving shipping documents, please retry',{timeOut:15000})
			} else {
				internal_error();
			}

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);	
}


function addBooking(ord_schedule_id) {
	$('#crm_containers_tab').addClass('hide'); 
	$('#crm_addendum_ct').addClass('hide');
	
	var resurl='include/logistic.php?elemid=add_booking_addendum&ord_schedule_id='+ord_schedule_id;
    var xhr = getXhr();     
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;  

			if(leselect == 1){  
				toastr.success('Booking add successfully',{timeOut:15000})
				$('#crm_containers_tab').removeClass('hide'); 
				$('#crm_addendum_ct').removeClass('hide');
			} else 
			if(leselect == 0){  
				toastr.error('Error adding booking, please retry',{timeOut:15000})
			} else {
				internal_error();
			}

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function addOnward(ord_schedule_id) {
	var resurl='listeslies.php?elemid=add_onward_addendum&ord_schedule_id='+ord_schedule_id;
    var xhr = getXhr();     
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;  

			if(val[0] == 1){  
				toastr.success('Onward Booking add successfully',{timeOut:15000})
				$('#crm_carriage_addendum_tab').removeClass('hide'); 
				$('#crm_carriage_addendum_ct').removeClass('hide');
			} else 
			if(val[0] == 0){  
				toastr.error('Error adding Onward Booking, please retry',{timeOut:15000})
			} else {
				internal_error();
			}

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


/* Check for vessel MMSI */

function checkMMSI(variable){  

	var vessel_name = '';
	if(variable == 'ocean'){
		vessel_name = document.getElementById('vessel_name').value;
	} else
	if(variable == 'ocean_add'){
		vessel_name = document.getElementById('vessel_name_add').value;
	} else
	if(variable == 'onward'){
		vessel_name = document.getElementById('vessel_name_onward').value;
	} else
	if(variable == 'onward_add'){
		vessel_name = document.getElementById('vessel_name_onward_add').value;
	} else 
	if(variable == 'iso'){
		vessel_name = document.getElementById('iso_vessel_name').value;
	} else 
	if(variable == 'iso_add'){
		vessel_name = document.getElementById('iso_vessel_name_add').value;
	} else 
	if(variable == 'feeder'){
		vessel_name = document.getElementById('vessel_feeder_name').value;
	} else
	if(variable == 'feeder2'){
		vessel_name = document.getElementById('vessel_feeder_name1').value;
	} else { }
	
	if(vessel_name == ''){
		toastr.info('Enter a vessel name.',{timeOut:15000})
		
	} else {
		
		var spinner = '<div class="sk-spinner sk-spinner-double-bounce div_ov_spanner">'+
			'<div class="sk-double-bounce1"></div>'+
			'<div class="sk-double-bounce2"></div>'+
		'</div>';

		$("#collapseBooking").append("<div class='div_overlay'>"+spinner+"</div>");
	
		var resurl='listeslies.php?elemid=check_for_vessel_mmsi&vessel_name='+vessel_name;
		var xhr = getXhr();     
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;   

				if(leselect == 0){  
					var shipname = '';
					var mmsi = '';
					var imo = '';
					
					var api_vessel_name = vessel_name.split(' ').join('%20'); 
					$.getJSON("https://services.marinetraffic.com/api/shipsearch/0790f8edd0d38948f232663c6736d223da533b32/shipname:"+api_vessel_name+"/protocol:json", function(result){
						shipname = result[0][0];
						mmsi = result[0][1];
						imo = result[0][2];
					});
	
					setTimeout(function() {
						if((shipname!="")&&(mmsi!="")&&(imo!="")) {
							
							var resurl='listeslies.php?elemid=manage_system_ship&conf=add&shipname='+shipname+'&mmsi='+mmsi+'&imo='+imo; 
							var xhr = getXhr();
							xhr.onreadystatechange = function(){
								if(xhr.readyState == 4 ){
									leselect = xhr.responseText;

									if(leselect == 1){
										toastr.success('MMSI added',{timeOut:15000})
										if(variable == 'ocean'){
											document.getElementById('vessel_mmsi_id').value = mmsi;
										} else
										if(variable == 'ocean_add'){
											document.getElementById('vessel_mmsi_id_add').value = mmsi;
										} else
										if(variable == 'onward'){
											document.getElementById('vessel_mmsi_id_onward').value = mmsi;
										} else
										if(variable == 'onward_add'){
											document.getElementById('vessel_mmsi_id_onward_add').value = mmsi;
										} else
										if(variable == 'feeder'){
											document.getElementById('vessel_feeder_mmsi_id').value = mmsi;
										} else
										if(variable == 'iso'){
											document.getElementById('iso_vessel_mmsi').value = mmsi;
										} else
										if(variable == 'iso_add'){
											document.getElementById('iso_vessel_mmsi_add').value = mmsi;
										} else
										if(variable == 'feeder2'){ 
											document.getElementById('vessel_feeder_mmsi_id1').value = mmsi;
										} else {}
										
										toastr.success('MMSI added',{timeOut:15000})
									
									} else {
										toastr.error('No MMSI found for this Vessel.',{timeOut:15000})
									}
									
									leselect = xhr.responseText;
								}
							};

							xhr.open("GET",resurl,true);
							xhr.send(null);
							
						} else {
							toastr.error('No MMSI found for this Vessel.',{timeOut:15000})
						}
						
					}, 3500);
	
				} else {
					
					if(variable == 'ocean'){
						document.getElementById('vessel_mmsi_id').value = leselect;
					} else
					if(variable == 'ocean_add'){
						document.getElementById('vessel_mmsi_id_add').value = leselect;
					} else
					if(variable == 'onward'){
						document.getElementById('vessel_mmsi_id_onward').value = leselect;
					} else
					if(variable == 'onward_add'){
						document.getElementById('vessel_mmsi_id_onward_add').value = leselect;
					} else
					if(variable == 'feeder'){
						document.getElementById('vessel_feeder_mmsi_id').value = leselect;
					} else
					if(variable == 'iso'){
						document.getElementById('iso_vessel_mmsi').value = leselect;
					} else
					if(variable == 'iso_add'){
						document.getElementById('iso_vessel_mmsi_add').value = leselect;
					} else
					if(variable == 'feeder2'){  
						document.getElementById('vessel_feeder_mmsi_id1').value = leselect;
					} else {}
					
					toastr.success('MMSI added',{timeOut:15000})
				}

				$(".div_overlay").remove();
				
				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
	}
}

/* Save Ocean booking */

function save_booking(ord_schedule_id,ref_num,val,id_con_booking) { 
	
	$("#booking_btn").prop("disabled", true);
	$("#booking_btn_spnr").removeClass("hide");
	
	var req="";
	
	var pod_id = document.getElementById('booking_pod_id').value;
	if(pod_id){ req=req+'&pod_id='+pod_id; }
	
	var pol_id = document.getElementById('booking_pol_id').value;
	if(pol_id){ req=req+'&pol_id='+pol_id; }
	
	var freight_agent = document.getElementById('freight_agent_list').value;
	if(freight_agent){ req=req+'&freight_agent='+freight_agent; }
	
	var agent_note = document.getElementById('agent_note').value;
	if(agent_note){ req=req+'&agent_note='+agent_note; }
	
	var carrier_name = document.getElementById('carrier_name_list').value;
	if(carrier_name){ req=req+'&carrier_name='+carrier_name; }
	
	var carrier_note = document.getElementById('carrier_note').value;
	if(carrier_note){ req=req+'&carrier_note='+carrier_note; }
	
	var forwarder_name = document.getElementById('forwarder_name_list').value;
	if(forwarder_name){ req=req+'&forwarder_name='+forwarder_name; }
	
	var forwarder_note = document.getElementById('forwarder_note').value;
	if(forwarder_note){ req=req+'&forwarder_note='+forwarder_note; }
	
	var booking_nr = document.getElementById('booking_nr').value;
	if(booking_nr){ req=req+'&booking_nr='+booking_nr; }
	
	var vessel_name = document.getElementById('vessel_name').value;
	if(vessel_name){ req=req+'&vessel_name='+vessel_name; }
	
	var vessel_mmsi_id = document.getElementById('vessel_mmsi_id').value;
	if(vessel_mmsi_id){ req=req+'&vessel_mmsi_id='+vessel_mmsi_id; }
	
	var voyage_nr = document.getElementById('voyage_nr').value;
	if(voyage_nr){ req=req+'&voyage_nr='+voyage_nr; }
	
	var pol = document.getElementById('pol').value;
	if(pol){ req=req+'&pol='+pol; }
	
	var cutoff_date = document.getElementById('cutoff_date').value;
	if(cutoff_date){ req=req+'&cutoff_date='+cutoff_date; }
	
	var cutoff_time = document.getElementById('cutoff_time').value;
	if(cutoff_time){ req=req+'&cutoff_time='+cutoff_time; }
	
	var vgm_cutoff = document.getElementById('vgm_cutoff').value;
	if(vgm_cutoff){ req=req+'&vgm_cutoff='+vgm_cutoff; }
	
	var vgm_cutoff_time = document.getElementById('vgm_cutoff_time').value;
	if(vgm_cutoff_time){ req=req+'&vgm_cutoff_time='+vgm_cutoff_time; }
	
	var etd = document.getElementById('etd').value;
	if(etd){ req=req+'&etd='+etd; }
	
	var etd_time = document.getElementById('etd_time').value;
	if(etd_time){ req=req+'&etd_time='+etd_time; }
	
	var pod = document.getElementById('pod').value;
	if(pod){ req=req+'&pod='+pod; }
	
	var eta = document.getElementById('eta').value;
	if(eta){ req=req+'&eta='+eta; }
	
	var eta_time = document.getElementById('eta_time').value;
	if(eta_time){ req=req+'&eta_time='+eta_time; }
	
	var log_contact_name = document.getElementById('log_contact_name').value;
	if(log_contact_name){ req=req+'&log_contact_name='+log_contact_name; }
	
	var sl_contact_name = document.getElementById('sl_contact_name').value;
	if(sl_contact_name){ req=req+'&sl_contact_name='+sl_contact_name; }
	
	var fa_contact_person_id = document.getElementById('fa_contact_person_id').value;
	if(fa_contact_person_id){ req=req+'&fa_contact_person_id='+fa_contact_person_id; }
	
	var trans_port_id = document.getElementById('trans_port_id').value;
	if(trans_port_id){ req=req+'&trans_port_id='+trans_port_id; }
	
	var vessel_feeder_name = document.getElementById('vessel_feeder_name').value;
	if(vessel_feeder_name){ req=req+'&vessel_feeder_name='+vessel_feeder_name.toUpperCase(); }
	
	var vessel_feeder_mmsi_id = document.getElementById('vessel_feeder_mmsi_id').value;
	if(vessel_feeder_mmsi_id){ req=req+'&vessel_feeder_mmsi_id='+vessel_feeder_mmsi_id; }
	
	var tport_eta = document.getElementById('tport_eta').value;
	if(tport_eta){ req=req+'&tport_eta='+tport_eta; }
	
	var tport_eta_time = document.getElementById('tport_eta_time').value;
	if(tport_eta_time){ req=req+'&tport_eta_time='+tport_eta_time; }
	
	var tport_etd = document.getElementById('tport_etd').value;
	if(tport_etd){ req=req+'&tport_etd='+tport_etd; }
	
	var tport_etd_time = document.getElementById('tport_etd_time').value;
	if(tport_etd_time){ req=req+'&tport_etd_time='+tport_etd_time; }
	
	var fa_reference_nr = document.getElementById('fa_reference_nr').value;
	if(fa_reference_nr){ req=req+'&fa_reference_nr='+fa_reference_nr; }
	
	
	var booking_type_id = document.getElementById('booking_type_id').value;
	if(booking_type_id){ req=req+'&booking_type_id='+booking_type_id; }
	
	var vessel_imo_id = document.getElementById('vessel_imo_id').value;
	if(vessel_imo_id){ req=req+'&vessel_imo_id='+vessel_imo_id; }
	
	
	var transport_id1 = document.getElementById('transport_id1').value;
	if(transport_id1){ req=req+'&transport_id1='+transport_id1; }
	
	var vessel_feeder_name1 = document.getElementById('vessel_feeder_name1').value;
	if(vessel_feeder_name1){ req=req+'&vessel_feeder_name1='+vessel_feeder_name1.toUpperCase(); }
	
	var vessel_feeder_mmsi_id1 = document.getElementById('vessel_feeder_mmsi_id1').value;
	if(vessel_feeder_mmsi_id1){ req=req+'&vessel_feeder_mmsi_id1='+vessel_feeder_mmsi_id1; }
	
	var tport_etd1 = document.getElementById('tport_etd1').value;
	if(tport_etd1){ req=req+'&tport_etd1='+tport_etd1; }
	
	var tport_etd_time1 = document.getElementById('tport_etd_time1').value;
	if(tport_etd_time1){ req=req+'&tport_etd_time1='+tport_etd_time1; }
	
	var tport_eta1 = document.getElementById('tport_eta1').value;
	if(tport_eta1){ req=req+'&tport_eta1='+tport_eta1; }
	
	var tport_eta_time1 = document.getElementById('tport_eta_time1').value;
	if(tport_eta_time1){ req=req+'&tport_eta_time1='+tport_eta_time1; }
	
	
	req=req+'&booking_segment=1';
	
	var resurl='listeslies.php?elemid=insert_booking_header&ord_schedule_id='+ord_schedule_id+'&id_con_booking='+id_con_booking+'&ref_num='+ref_num+req+'&type='+val+'&doc_right='+bookingDocManager_read;
    var xhr = getXhr();     
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;     
			var val1 = leselect.split('#');
			
			if(val1[0] == 1){  
				toastr.success('Booking saved successfully',{timeOut:15000})
				edit_ocean_loading(val1[1],ref_num,ord_schedule_id,'edit'); 
				$('#containerEditBTN').prop("disabled", false); 
				
				if(val == 'add'){
					var b = val1[8].split('@@');
					ocean_containers_loading(b[0],b[1],b[2],b[3],b[4],b[5],b[6],b[7],b[8],b[9],b[10],b[11],b[12],b[13],b[14],b[15]);
					container_list(ord_schedule_id); 
					
					setTimeout(function() {
						$("#bookingCard1").trigger("click"); 
					}, 1000); 
				}
				
				if(val1[2]!=""){
					saveMailAsPdf(val1[3],val1[4],val1[5],'system',val1[6],val1[2],'NewBooking',val1[7],'',val1[1]);
				}
				
			} else 
			if(val1[0] == 0){  
				toastr.error('Error saving booking, please retry',{timeOut:15000})
			} else {
				internal_error();
			}
			
			hide_ocean_loading_editBtn(id_con_booking,ord_schedule_id,ref_num,val);
			
			$("#booking_btn").prop("disabled", false);
			$("#booking_btn_spnr").addClass("hide");

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);	
}


function bookingPodId(portname) {
	var resurl='listeslies.php?elemid=booking_pod_id&portname='+portname;
    var xhr = getXhr();     
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;  

			document.getElementById('booking_pod_id').value = leselect;   

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function container_list(ord_schedule_id){
	
	var rights="";
	
	// Logistic Ocean Booking Container
	rights=rights+'&oceanCont_create='+logOceanContainer_create; 
	
	// Logistics Move Container rights
	rights=rights+'&LogContMove='+LogContMove;
	
	var resurl='listeslies.php?elemid=refresh_containers_list&ord_schedule_id='+ord_schedule_id+rights;
    var xhr = getXhr();     
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;  

			document.getElementById('container_list').innerHTML = leselect; 

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


/* Save Ocean booking (add) */

function save_booking_add(ord_schedule_id,ref_num,val,id_con_booking) { 
	
	$("#booking_btn_add").prop("disabled", true);
	$("#booking_btn_spnr_add").removeClass("hide");
	
	var req="";
	
	var pod_id = document.getElementById('booking_pod_id_add').value;
	if(pod_id){ req=req+'&pod_id='+pod_id; }
	
	var pol_id = document.getElementById('booking_pol_id_add').value;
	if(pol_id){ req=req+'&pol_id='+pol_id; }
	
	var freight_agent = document.getElementById('freight_agent_list_add').value;
	if(freight_agent){ req=req+'&freight_agent='+freight_agent; }
	
	var agent_note = document.getElementById('agent_note_add').value;
	if(agent_note){ req=req+'&agent_note='+agent_note; }
	
	var carrier_name = document.getElementById('carrier_name_list_add').value;
	if(carrier_name){ req=req+'&carrier_name='+carrier_name; }
	
	var carrier_note = document.getElementById('carrier_note_add').value;
	if(carrier_note){ req=req+'&carrier_note='+carrier_note; }
	
	var forwarder_name = document.getElementById('forwarder_name_list_add').value;
	if(forwarder_name){ req=req+'&forwarder_name='+forwarder_name; }
	
	var forwarder_note = document.getElementById('forwarder_note_add').value;
	if(forwarder_note){ req=req+'&forwarder_note='+forwarder_note; }
	
	var booking_nr = document.getElementById('booking_nr_add').value;
	if(booking_nr){ req=req+'&booking_nr='+booking_nr; }
	
	var vessel_name = document.getElementById('vessel_name_add').value;
	if(vessel_name){ req=req+'&vessel_name='+vessel_name; }
	
	var vessel_mmsi_id = document.getElementById('vessel_mmsi_id_add').value;
	if(vessel_mmsi_id){ req=req+'&vessel_mmsi_id='+vessel_mmsi_id; }
	
	var voyage_nr = document.getElementById('voyage_nr_add').value;
	if(voyage_nr){ req=req+'&voyage_nr='+voyage_nr; }
	
	var pol = document.getElementById('pol_add').value;
	if(pol){ req=req+'&pol='+pol; }
	
	var cutoff_date = document.getElementById('cutoff_date_add').value;
	if(cutoff_date){ req=req+'&cutoff_date='+cutoff_date; }
	
	var cutoff_time = document.getElementById('cutoff_time_add').value;
	if(cutoff_time){ req=req+'&cutoff_time='+cutoff_time; }
	
	var vgm_cutoff = document.getElementById('vgm_cutoff_add').value;
	if(vgm_cutoff){ req=req+'&vgm_cutoff='+vgm_cutoff; }
	
	var vgm_cutoff_time = document.getElementById('vgm_cutoff_time_add').value;
	if(vgm_cutoff_time){ req=req+'&vgm_cutoff_time='+vgm_cutoff_time; }
	
	var etd = document.getElementById('etd_add').value;
	if(etd){ req=req+'&etd='+etd; }
	
	var etd_time = document.getElementById('etd_time_add').value;
	if(etd_time){ req=req+'&etd_time='+etd_time; }
	
	var pod = document.getElementById('pod_add').value;
	if(pod){ req=req+'&pod='+pod; }
	
	var eta = document.getElementById('eta_add').value;
	if(eta){ req=req+'&eta='+eta; }
	
	var eta_time = document.getElementById('eta_time_add').value;
	if(eta_time){ req=req+'&eta_time='+eta_time; }
	
	var log_contact_name = document.getElementById('log_contact_name_add').value;
	if(log_contact_name){ req=req+'&log_contact_name='+log_contact_name; }
	
	var sl_contact_name = document.getElementById('sl_contact_name_add').value;
	if(sl_contact_name){ req=req+'&sl_contact_name='+sl_contact_name; }
	
	var fa_contact_person_id = document.getElementById('fa_contact_person_id_add').value;
	if(fa_contact_person_id){ req=req+'&fa_contact_person_id='+fa_contact_person_id; }
	
	var trans_port_id = document.getElementById('trans_port_id_add').value;
	if(trans_port_id){ req=req+'&trans_port_id='+trans_port_id; }
	
	var vessel_feeder_name = document.getElementById('vessel_feeder_name_add').value;
	if(vessel_feeder_name){ req=req+'&vessel_feeder_name='+vessel_feeder_name; }
	
	var vessel_feeder_mmsi_id = document.getElementById('vessel_feeder_mmsi_id_add').value;
	if(vessel_feeder_mmsi_id){ req=req+'&vessel_feeder_mmsi_id='+vessel_feeder_mmsi_id; }
	
	var tport_eta = document.getElementById('tport_eta_add').value;
	if(tport_eta){ req=req+'&tport_eta='+tport_eta; }
	
	var tport_eta_time = document.getElementById('tport_eta_time_add').value;
	if(tport_eta_time){ req=req+'&tport_eta_time='+tport_eta_time; }
	
	var tport_etd = document.getElementById('tport_etd_add').value;
	if(tport_etd){ req=req+'&tport_etd='+tport_etd; }
	
	var tport_etd_time = document.getElementById('tport_etd_time_add').value;
	if(tport_etd_time){ req=req+'&tport_etd_time='+tport_etd_time; }
	
	var fa_reference_nr = document.getElementById('fa_reference_nr_add').value;
	if(fa_reference_nr){ req=req+'&fa_reference_nr='+fa_reference_nr; }
	
	
	var booking_type_id = document.getElementById('booking_type_id_add').value;
	if(booking_type_id){ req=req+'&booking_type_id='+booking_type_id; }
	
	var vessel_imo_id = document.getElementById('vessel_imo_id_add').value;
	if(vessel_imo_id){ req=req+'&vessel_imo_id='+vessel_imo_id; }
	
	req=req+'&booking_segment=2';
	
	var resurl='listeslies.php?elemid=insert_booking_header&ord_schedule_id='+ord_schedule_id+'&id_con_booking='+id_con_booking+'&ref_num='+ref_num+req+'&type='+val;
    var xhr = getXhr();     
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;   
			var val = leselect.split('#');
			
			if(val[0] == 1){  
				toastr.success('Booking saved successfully',{timeOut:15000})
				edit_ocean_loading(val[1],ref_num,ord_schedule_id,'edit');
				$('#containerEditBTN').prop("disabled", false);
				
				if(val[0]!=""){
					saveMailAsPdf(val1[3],val1[4],val1[5],'system',val1[6],val1[2],'NewBooking',val1[7],'',val1[1]);
				}
				
			} else 
			if(val[0] == 0){ 
				toastr.error('Error saving booking, please retry',{timeOut:15000})
			} else {
				internal_error();
			}
			
			$("#booking_btn_add").prop("disabled", false);
			$("#booking_btn_spnr_add").addClass("hide");

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);	
}

/* Save Onward Carriage */

function save_onward(ord_schedule_id,ref_num,type,id_con_booking) {  
	
	$("#onward_btn").prop("disabled", true);
	$("#onward_btn_spnr").removeClass("hide");
	
	var req="";

	var booking_nr = document.getElementById('booking_nr_onward').value;
	if(booking_nr){ req=req+'&booking_nr='+booking_nr; }
	
	var vessel_name = document.getElementById('vessel_name_onward').value;
	if(vessel_name){ req=req+'&vessel_name='+vessel_name; }
	
	var vessel_mmsi_id = document.getElementById('vessel_mmsi_id_onward').value;
	if(vessel_mmsi_id){ req=req+'&vessel_mmsi_id='+vessel_mmsi_id; }

	var pol_polId = document.getElementById('pol_onward').value;
	if(pol_polId){ var polVal = pol_polId.split('??');
		var pol_id = polVal[0];
		var pol = polVal[1];
		req=req+'&pol='+pol+'&pol_id='+pol_id; 
	}
	
	var etd = document.getElementById('etd_onward').value;
	if(etd){ req=req+'&etd='+etd; }

	var pod_podId = document.getElementById('pod_onward').value;
	if(pod_podId){ var podVal = pod_podId.split('??');
		var pod_id = podVal[0];
		var pod = podVal[1];
		req=req+'&pod='+pod+'&pod_id='+pod_id; 
	}
	
	var eta = document.getElementById('eta_onward').value;
	if(eta){ req=req+'&eta='+eta; }
	
	req=req+'&booking_segment=3';
	
	var resurl='listeslies.php?elemid=insert_onward_carriage&ord_schedule_id='+ord_schedule_id+'&id_con_booking='+id_con_booking+'&ref_num='+ref_num+req+'&type='+type;
    var xhr = getXhr();     
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;    
			var val = leselect.split('#');
			
			if(val[0] == 1){  
				toastr.success('Booking saved successfully',{timeOut:15000})
				cancel_onward_carriage(id_con_booking,ref_num,type,ord_schedule_id);

			} else 
			if(val[0] == 0){  
				toastr.error('Error saving booking, please retry',{timeOut:15000})
			} else {
				internal_error();
			}
			
			$("#onward_btn").prop("disabled", false);
			$("#onward_btn_spnr").addClass("hide");

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);	
}


/* Save Onward Carriage Add */

function save_onward_add(ord_schedule_id,ref_num,type,id_con_booking) { 

	$("#onward_btn_add").prop("disabled", true);
	$("#onward_btn_spnr_add").removeClass("hide");
	
	var req="";

	var booking_nr = document.getElementById('booking_nr_onward_add').value;
	if(booking_nr){ req=req+'&booking_nr='+booking_nr; }
	
	var vessel_name = document.getElementById('vessel_name_onward_add').value;
	if(vessel_name){ req=req+'&vessel_name='+vessel_name; }
	
	var vessel_mmsi_id = document.getElementById('vessel_mmsi_id_onward_add').value;
	if(vessel_mmsi_id){ req=req+'&vessel_mmsi_id='+vessel_mmsi_id; }

	var pol = document.getElementById('pol_onward_add').value;
	if(pol){ req=req+'&pol='+pol; }
	
	var etd = document.getElementById('etd_onward_add').value;
	if(etd){ req=req+'&etd='+etd; }

	var pod = document.getElementById('pod_onward_add').value;
	if(pod){ req=req+'&pod='+pod; }
	
	var eta = document.getElementById('eta_onward_add').value;
	if(eta){ req=req+'&eta='+eta; }
	
	req=req+'&booking_segment=4';
	
	var resurl='listeslies.php?elemid=insert_onward_carriage&ord_schedule_id='+ord_schedule_id+'&id_con_booking='+id_con_booking+'&ref_num='+ref_num+req+'&type='+type;
    var xhr = getXhr();     
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;   
			var val = leselect.split('#');
			
			if(val[0] == 1){  
				toastr.success('Booking saved successfully',{timeOut:15000})
				cancel_onward_carriage_add(id_con_booking,ref_num,type,ord_schedule_id);

			} else 
			if(val[0] == 0){ 
				toastr.error('Error saving booking, please retry',{timeOut:15000})
			} else {
				internal_error();
			}
			
			$("#onward_btn_add").prop("disabled", false);
			$("#onward_btn_spnr_add").addClass("hide");

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);	
}

// Edit & Save Onward Container

function edit_container_onward(id_con_list,ord_schedule_id,vgm_weight) {
	var resurl='listeslies.php?elemid=edit_onward_container&ord_schedule_id='+ord_schedule_id+'&id_con_list='+id_con_list;
    var xhr = getXhr();     
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;   
		
			document.getElementById('OnwardcontainerHeader').innerHTML = 'Edit Onward Carriage Container';  
			document.getElementById('OnwardcontainerContent').innerHTML = leselect;  
			document.getElementById('OnwardcontainerFooter').innerHTML = '<button type="button" class="btn btn-primary pull-left" onclick="save_edit_container_onward(\''+id_con_list+'\',\''+ord_schedule_id+'\',\''+vgm_weight+'\');" data-dismiss="modal"><i class="fa fa-save"></i> Save</button> '
				+'<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Close</button>';  
			$("#OnwardcontainerModal").modal("show");			
		
			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function save_edit_container_onward(id_con_list,ord_schedule_id,vgm_weight) { 
	var vgm_delivery = document.getElementById('onw_vgm_delivery').value;  
	var cus_con_ref1 = document.getElementById('onw_cus_con_ref1').value;  
	var gross_weight_arrival = document.getElementById('onw_gross_weight_arrival').value;  
	
	var resurl='listeslies.php?elemid=save_edit_container_onward&id_con_list='+id_con_list+'&vgm_delivery='+vgm_delivery+'&vgm_weight='+vgm_weight+'&cus_con_ref1='+cus_con_ref1+'&gross_weight_arrival='+gross_weight_arrival;
    var xhr = getXhr();     
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;    
		
			if(leselect == 1){  
				toastr.success('Container saved',{timeOut:15000})
				refresh_onward_container(ord_schedule_id);
				
			} else 
			if(leselect == 0){  
				toastr.error('Editing not save, please retry',{timeOut:15000})
			} else {
				internal_error();
			}
		
			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function GrossCalc(conf) {
	if(conf == "onward"){
		var tare = document.getElementById('onw_tare').value;
		var gross_weight_arrival = document.getElementById('onw_gross_weight_arrival').value;
		
		document.getElementById('onw_vgm_delivery').value = (gross_weight_arrival - tare);
	}
}


function LoadingAgent(value){
	if(value==1){
		$('#h_lAgent2').removeClass('hide');
	} else {
		$('#h_lAgent2').addClass('hide');
	}
}


function refresh_onward_container(ord_schedule_id) {
	var resurl='listeslies.php?elemid=refresh_onward_container&ord_schedule_id='+ord_schedule_id;
    var xhr = getXhr();     
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;   
		
			document.getElementById('container_list_carr').innerHTML = leselect;  

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}

// Edit & Save Onward 
// Container disposition

function edit_onward_container_disposition(id_con_list,ord_schedule_id) {
	var resurl='listeslies.php?elemid=edit_onward_container_disposition&ord_schedule_id='+ord_schedule_id+'&id_con_list='+id_con_list;
    var xhr = getXhr();     
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;    
		
			document.getElementById('OnwardcontainerHeader').innerHTML = 'Edit Onward Carriage Container Disposition';  
			document.getElementById('OnwardcontainerContent').innerHTML = leselect;  
			document.getElementById('OnwardcontainerFooter').innerHTML = '<button type="button" class="btn btn-primary pull-left" onclick="save_edit_onward_container_disposition(\''+id_con_list+'\',\''+ord_schedule_id+'\');" data-dismiss="modal"><i class="fa fa-save"></i> Save</button> '
				+'<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Close</button>';  
			$("#OnwardcontainerModal").modal("show");
			
			$('.clockpicker').clockpicker();
			$('.edit_delivery_date').datepicker({
				format: "yyyy/mm/dd",
				calendarWeeks:true,
				autoclose: true
			});
		
			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function save_edit_onward_container_disposition(id_con_list,ord_schedule_id) {
	var dispo_order_nr = document.getElementById('onw_disp_dispo_order_nr').value;  
	var dispo_delivery_nr = document.getElementById('onw_disp_dispo_delivery_nr').value;  
	var terminal_date = document.getElementById('onw_disp_terminal_date').value; 
	var terminal_dispo = document.getElementById('onw_disp_terminal_dispo').value; 
	var dispo_hour = document.getElementById('onw_disp_dispo_hour').value; 
	
	var resurl='listeslies.php?elemid=save_edit_onward_container_disposition&id_con_list='+id_con_list+'&dispo_order_nr='+dispo_order_nr+'&dispo_delivery_nr='+dispo_delivery_nr+'&terminal_date='+terminal_date+'&terminal_dispo='+terminal_dispo+'&dispo_hour='+dispo_hour;
    var xhr = getXhr();     
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;     
		
			if(leselect == 1){  
				toastr.success('Container saved',{timeOut:15000})
				refresh_onward_container_disposition(ord_schedule_id);
				
			} else 
			if(leselect == 0){  
				toastr.error('Editing not save, please retry',{timeOut:15000})
			} else {
				internal_error();
			}
		
			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function refresh_onward_container_disposition(ord_schedule_id){
	var resurl='listeslies.php?elemid=refresh_onward_container_disposition&ord_schedule_id='+ord_schedule_id;
    var xhr = getXhr();     
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;    
		
			document.getElementById('container_dispo').innerHTML = leselect; 

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


/* booking type magement */

function bTypeChoice(booking_type_id) {
	
	if(booking_type_id == 1){
		$('#trans_port_id').prop("disabled", true);
	} else
	if(booking_type_id == 0){
		$('#trans_port_id').prop("disabled", false);
	} else {}
}

function bTypeChoice_add(booking_type_id) {
	
	if(booking_type_id == 1){
		$('#trans_port_id_add').prop("disabled", true);
	} else
	if(booking_type_id == 0){
		$('#trans_port_id_add').prop("disabled", false);
	} else {}
}


function supp_invoice() {

	$('#ship_invoice_form').on('submit', function(e) {

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
					// var rear_outlet = document.getElementById("supp_pdf").value.replace(/C:\\fakepath\\/i, '');
					
					// var resurl='loading_listeslies.php?elemid=rear_outlet&rear_outlet='+rear_outlet+'&ord_loading_id='+ord_loading_id;   
					// var xhr = getXhr(); 
					// xhr.onreadystatechange = function(){
						// if(xhr.readyState == 4 ){
							// leselect = xhr.responseText;   
							
							// if(leselect == 1){
								// $("#loadingRear_outlet").addClass("hide"); 
							// } 

							// leselect = xhr.responseText;
						// }
					// };

					// xhr.open("GET",resurl,true);
					// xhr.send(null);
					
				} else { toastr.error('Pdf not uploaded.',{timeOut:15000}) }
            }
        });
    });
}


function certificateOrigin() {
	
	$('#ship_form_a_doc_form').on('submit', function(e) {

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
					// var form_a_doc = document.getElementById("form_a_doc").value.replace(/C:\\fakepath\\/i, '');
					
					// var resurl='listeslies.php?elemid=form_a_doc&form_a_doc='+form_a_doc+'&ord_loading_id='+ord_loading_id;   
					// var xhr = getXhr(); 
					// xhr.onreadystatechange = function(){
						// if(xhr.readyState == 4 ){
							// leselect = xhr.responseText;   
							
							// if(leselect == 1){
								// $("#loadingRear_outlet").addClass("hide"); 
							// } 

							// leselect = xhr.responseText;
						// }
					// };

					// xhr.open("GET",resurl,true);
					// xhr.send(null);
					
				} else { toastr.error('Pdf not uploaded.',{timeOut:15000}) }
            }
        });
    });
}

$('#containerModal').on('shown.bs.modal', function () {
  $('#container_number').focus()  
})


function contdeleteConfirm(id_con_list,container_nr,ord_schedule_id,tare,vgm_weight,date_loaded){
	document.getElementById('deleteConfirmationHeader').innerHTML = 'Delete Container';  
	document.getElementById('deleteConfirmationContent').innerHTML = '<span style="font-size: 16;">Are you sure you want to delete this container ?</span><br/><br/>'
	+'<table style="font-size: 14;">'
		+'<tr><td><b>Number : </b></td><td>'+container_nr+'</td></tr>'
		+'<tr><td><b>Tare : </b></td><td>'+tare+'</td></tr>'
		+'<tr><td><b>Weight : </b></td><td>'+vgm_weight+'</td></tr>'
		+'<tr><td><b>Date : </b></td><td>'+date_loaded+'</td></tr>'
	+'</table>';
	
	document.getElementById('deleteConfirmationFooter').innerHTML = '<button type="button" class="btn btn-danger pull-left" onclick="delete_container(\''+id_con_list+'\',\''+ord_schedule_id+'\');" data-dismiss="modal">Delete</button> '
		+'<button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>';  
	$("#deleteConfirmation").modal("show");	
}


function delete_container(id_con_list,ord_schedule_id){
	var resurl='listeslies.php?elemid=delete_container&id_con_list='+id_con_list;  
	var xhr = getXhr();  
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;   
		
			if(leselect == 1){  
				toastr.success('Container deleted successfully',{timeOut:15000})
				container_list(ord_schedule_id);
				
			} else 
			if(leselect == 0){  
				toastr.error('Error deleting container, please retry',{timeOut:15000})
				
			} else {
				internal_error();
			}

			leselect = xhr.responseText;
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}


function edit_container(id_con_list,container_nr,ord_schedule_id,tare,vgm_weight,seal_1_nr,seal_2_nr,seal_3_nr,seal_4_nr,seal_5_nr,date_loaded) {
	document.getElementById('id_con_list').value = id_con_list;  
	document.getElementById('container_number').value = container_nr; 
	
	document.getElementById('container_tare').value = tare;  
	document.getElementById('container_vgm_weight').value = vgm_weight;  
	document.getElementById('container_seal_1_nr').value = seal_1_nr;  
	document.getElementById('container_seal_2_nr').value = seal_2_nr;  
	document.getElementById('container_seal_3_nr').value = seal_3_nr;  
	document.getElementById('container_seal_4_nr').value = seal_4_nr;  
	document.getElementById('container_seal_5_nr').value = seal_5_nr;  
	document.getElementById('container_date_loaded').value = date_loaded;  
	
	document.getElementById('ord_schedule_idCont').value = ord_schedule_id;  
	$("#containerModal").modal("show");
}


function edit_booking_containers() {
	
	var req='';
	
	var container_nr = document.getElementById('container_number').value;
	var id_con_list = document.getElementById('id_con_list').value;
	var ord_schedule_id = document.getElementById('ord_schedule_idCont').value;
	
	var tare = document.getElementById('container_tare').value;
	if(tare){ req=req+'&tare='+tare; }
	
	var vgm_weight = document.getElementById('container_vgm_weight').value;
	if(vgm_weight){ req=req+'&vgm_weight='+vgm_weight; }
	
	var seal_1_nr = document.getElementById('container_seal_1_nr').value;
	if(seal_1_nr){ req=req+'&seal_1_nr='+seal_1_nr; }
	
	var seal_2_nr = document.getElementById('container_seal_2_nr').value;
	if(seal_2_nr){ req=req+'&seal_2_nr='+seal_2_nr; }
	
	var seal_3_nr = document.getElementById('container_seal_3_nr').value;
	if(seal_3_nr){ req=req+'&seal_3_nr='+seal_3_nr; }
	
	var seal_4_nr = document.getElementById('container_seal_4_nr').value;
	if(seal_4_nr){ req=req+'&seal_4_nr='+seal_4_nr; }
	
	var seal_5_nr = document.getElementById('container_seal_5_nr').value;
	if(seal_5_nr){ req=req+'&seal_5_nr='+seal_5_nr; }
	
	var date_loaded = document.getElementById('container_date_loaded').value;
	if(date_loaded){ req=req+'&date_loaded='+date_loaded; }

	var resurl='listeslies.php?elemid=edit_booking_containers&container_nr='+container_nr+'&id_con_list='+id_con_list+'&ord_schedule_id='+ord_schedule_id+req;
	var xhr = getXhr();  
	xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;    
				var val = leselect.split('??');
				
				if(val[0] == 1){  
					toastr.success('Container edited successfully',{timeOut:15000})
					document.getElementById('container_list').innerHTML = val[1];  
					
				} else 
				if(val[0] == 0){  
					toastr.error('Error editing container, please retry',{timeOut:15000})
				} else {
					internal_error();
				}

				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
		
	document.getElementById('container_number').value = "";
	
}



function saveBookingDoc(id_con_booking) {

	$('#booking_reserv_doc_form').on('submit', function(e) {

        e.preventDefault();
 
        var $form = $(this);
        var formdata = (window.FormData) ? new FormData($form[0]) : null;
        var data = (formdata !== null) ? formdata : $form.serialize();
 
        $.ajax({
            url: $form.attr('action'),
            type: $form.attr('method'),
            contentType: false, // obligatoire pour de l'upload
            processData: false, // obligatoire pour de l'upload
            dataType: 'text', // selon le retour attendu
            data: data,
            success: function (response) {  
				if(response==1){ 
				
					var confirmation_document = document.getElementById("confirmation_document").value.replace(/C:\\fakepath\\/i, '');
					
					var resurl='listeslies.php?elemid=save_booking_doc&confirmation_document='+confirmation_document+'&id_con_booking='+id_con_booking;   
					var xhr = getXhr(); 
					xhr.onreadystatechange = function(){
						if(xhr.readyState == 4 ){
							leselect = xhr.responseText;    
							
							if(leselect == 1){
								toastr.success('File uploaded successfully',{timeOut:15000})
							} else 
							if(leselect == 0){
								toastr.error('Error uploading file, please retry',{timeOut:15000})
							} else {
								internal_error();
							}

							leselect = xhr.responseText;
						}
					};

					xhr.open("GET",resurl,true);
					xhr.send(null);
					
				} else { toastr.error('File not uploaded.',{timeOut:15000}) }
            }
        });
    });
}


function viewBookingDoc(confirmation_document,id_document) {
	$("#bookingDocModal").modal("show");
	
	var spinner = '<div class="sk-spinner sk-spinner-double-bounce div_ov_spanner">'+
			'<div class="sk-double-bounce1"></div>'+
			'<div class="sk-double-bounce2"></div>'+
		'</div>';
		
	document.getElementById('booking_document_show').innerHTML = spinner;

	var resurl='listeslies.php?elemid=view_booking_document&confirmation_document='+confirmation_document+'&id_document='+id_document;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;
			var val=leselect.split('##');

			document.getElementById('booking_document_show').innerHTML = val[0];
			document.getElementById('booking_document_footer').innerHTML = val[1];

			documentAsRead(id_document); 
			
			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function docTatus(id_document,ord_schedule_id,ord_order_id){  

	if ($('#doc_active').is(':checked')) {
		var new_stat = 1; 
	} else {
		var new_stat = 0;
	}
	
	var doc_type_id = document.getElementById('popup_doc_type_id').value;  
	var doc_desc = document.getElementById('popup_doc_desc').value;  
	
	var resurl='listeslies.php?elemid=document_status&active='+new_stat+'&id_document='+id_document+'&doc_desc='+doc_desc;
    var xhr = getXhr();  
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;  
			
			if(leselect == 1){
				toastr.success('Status successfully updated',{timeOut:15000})
				
				var type = document.getElementById('documentCurrentType').value;
				var position = document.getElementById('documentCurrentPosition').value;
				
				documentList(ord_order_id,ord_schedule_id,type,position);
				if(doc_type_id == 176){
					if(new_stat == 1){ $("#btn_inv2_status").prop("disabled", false); } else { $("#btn_inv2_status").prop("disabled", true); }  
				}
				
				if(doc_type_id == 16){ 
					if(new_stat == 1){ $("#btn_inv1_status").prop("disabled", false); } else { $("#btn_inv1_status").prop("disabled", true); }  
				}
				
				if(doc_type_id == 179){ 
					if(new_stat == 1){ $("#btn_invC_status").prop("disabled", false); } else { $("#btn_invC_status").prop("disabled", true); }  
				}
				
				
			} else 
			if(leselect == 0){
				toastr.error('Status not updated',{timeOut:15000})
			} else {
				internal_error();
			}
			
			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function documentAsRead(id_document) {
	var resurl='listeslies.php?elemid=document_as_read&id_document='+id_document;  
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText; 

			if(leselect==1){ 
				uploadCounter();
			}
			
			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function getForwarderAgent(log_contact_id) {
	
	$("#log_contact_box").removeClass("hide");
	
	var resurl='listeslies.php?elemid=get_agents_list&company_id='+log_contact_id+'&val=id';
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;

			document.getElementById('log_contact_name').innerHTML = leselect;

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function getForwarderAgent_add(log_contact_id) {
	
	$("#log_contact_box_add").removeClass("hide");
	
	var resurl='listeslies.php?elemid=get_agents_list&company_id='+log_contact_id+'&val=id';
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;

			document.getElementById('log_contact_name_add').innerHTML = leselect;

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function getCarrierAgent(sl_line_id) {
	
	if(sl_line_id == ""){
		$("#sl_line_box").addClass("hide");
	} else {
		$("#sl_line_box").removeClass("hide");
		var resurl='listeslies.php?elemid=get_agents_list&company_id='+sl_line_id+'&val=id';
		var xhr = getXhr();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;

				document.getElementById('sl_contact_name').innerHTML = leselect;

				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
	}
}


function getCarrierAgent_add(sl_line_id) {
	
	if(sl_line_id == ""){
		$("#sl_line_box_add").addClass("hide");
	} else {
		$("#sl_line_box_add").removeClass("hide");
		var resurl='listeslies.php?elemid=get_agents_list&company_id='+sl_line_id+'&val=id';
		var xhr = getXhr();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;

				document.getElementById('sl_contact_name_add').innerHTML = leselect;

				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
	}
}


function getFreightAgent(fa_contact_id) { 

	if(fa_contact_id == ""){
		$("#fa_person_box").addClass("hide");
	} else {
		$("#fa_person_box").removeClass("hide");
		var resurl='listeslies.php?elemid=get_agents_list&company_id='+fa_contact_id+'&val=id';
		var xhr = getXhr();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;

				document.getElementById('fa_contact_person_id').innerHTML = leselect;

				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
	}	
}


function getFreightAgent_add(fa_contact_id) { 

	if(fa_contact_id == ""){
		$("#fa_person_box_add").addClass("hide");
	} else {
		$("#fa_person_box_add").removeClass("hide");
		var resurl='listeslies.php?elemid=get_agents_list&company_id='+fa_contact_id+'&val=id';
		var xhr = getXhr();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;

				document.getElementById('fa_contact_person_id_add').innerHTML = leselect;

				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
	}	
}


/*
*
* Regvalues management
*
*/

function regvaluesManagement(conf,id_regvalue,val) {

	if(conf == 'add'){
		/* Add new port */
		
		var req = '';
		
		var id_register = document.getElementById("systReg_id_register").value;
		if(id_register){ req=req+'&id_register='+id_register; }
		
		var cvalue = document.getElementById("systReg_cvalue").value;
		if(cvalue){ req=req+'&cvalue='+cvalue; }
		
		var cvaluede = document.getElementById("systReg_cvaluede").value;
		if(cvaluede){ req=req+'&cvaluede='+cvaluede; }
		
		var cvaluefr = document.getElementById("systReg_cvaluefr").value;
		if(cvaluefr){ req=req+'&cvaluefr='+cvaluefr; }
		
		var cvaluept = document.getElementById("systReg_cvaluept").value;
		if(cvaluept){ req=req+'&cvaluept='+cvaluept; }
		
		var cvaluees = document.getElementById("systReg_cvaluees").value;
		if(cvaluees){ req=req+'&cvaluees='+cvaluees; }
		
		var cvaluesw = document.getElementById("systReg_cvaluesw").value;
		if(cvaluesw){ req=req+'&cvaluesw='+cvaluesw; }
		
		var cvalueit = document.getElementById("systReg_cvalueit").value;
		if(cvalueit){ req=req+'&cvalueit='+cvalueit; }
		
		var comment = document.getElementById("systReg_comment").value;
		if(comment){ req=req+'&comment='+comment; }
		
		
		if(id_register == ""){
			alert("Select a register");
			
		} else 
		if(cvalue == ""){
			alert("Enter english value.");
			
		} else {
			var resurl='listeslies.php?elemid=manag_system_regvalue&conf='+conf+'&id_regvalue='+id_regvalue+req;    
			var xhr = getXhr();  
			xhr.onreadystatechange = function(){
				if(xhr.readyState == 4 ){
					leselect = xhr.responseText;    
					
					if(leselect == 1){
						toastr.success('Regvalue successfully added',{timeOut:15000})
						$("#modalRegvalue").modal("hide");
						regvalues();
					} else 
					if(leselect == 0){
						toastr.error('Regvalue not added, please retry!',{timeOut:15000})
					} else {
						internal_error();
					}
					
					leselect = xhr.responseText;
				}
			};

			xhr.open("GET",resurl,true);
			xhr.send(null);
		}

	} else
	if(conf == 'show'){
		if(val== 'create'){
			document.getElementById("systRegvalueForm").reset();
			
			/* Save button */
			document.getElementById('regvalueModalLabel').innerHTML = "Create new regvalue";
			document.getElementById('regvalueModalFooter').innerHTML ='<button type="button" class="btn btn-success" onclick="regvaluesManagement(\'add\',\'\',\'\');"><i class="fa fa-save"></i></button>'
				+'<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i></button>';
		} else
		if(val== 'mod'){ 
			
			document.getElementById("systRegvalueForm").reset();
			
			var resurl='listeslies.php?elemid=show_system_regvalue&id_regvalue='+id_regvalue;   
			var xhr = getXhr();
			xhr.onreadystatechange = function(){
				if(xhr.readyState == 4 ){
					leselect = xhr.responseText;          
					var val = leselect.split('#');
					
					document.getElementById("systReg_id_register").value = val[0];
					document.getElementById("systReg_cvalue").value = val[1];
					document.getElementById("systReg_cvaluede").value = val[2];
					document.getElementById("systReg_cvaluefr").value = val[3];
					document.getElementById("systReg_cvaluept").value = val[4];
					document.getElementById("systReg_cvaluees").value = val[5];
					document.getElementById("systReg_cvaluesw").value = val[6];
					document.getElementById("systReg_cvalueit").value = val[7];
					document.getElementById("systReg_comment").value = val[8];
					
					document.getElementById('systReg_id_regvalue').value = id_regvalue;
				
					leselect = xhr.responseText;
				}
			};

			xhr.open("GET",resurl,true);
			xhr.send(null);
	
			
			/* Edit button */
			document.getElementById('regvalueModalLabel').innerHTML = "Edit regvalue";
			document.getElementById('regvalueModalFooter').innerHTML ='<button type="button" class="btn btn-success" onclick="regvaluesManagement(\'edit\',\''+id_regvalue+'\',\'\');"><i class="fa fa-save"></i></button>'
				+'<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i></button>';
		} else {}
		
	} else
	if(conf == 'edit'){	 
		
		/* Edit port */
		
		var req = "";

		var id_register = document.getElementById("systReg_id_register").value;
		if(id_register){ req=req+"&id_register="+id_register; }
		
		var cvalue = document.getElementById("systReg_cvalue").value; 
		if(cvalue){ req=req+"&cvalue="+cvalue; }
		
		var cvaluede = document.getElementById("systReg_cvaluede").value;
		if(cvaluede){ req=req+"&cvaluede="+cvaluede; }
		
		var cvaluefr = document.getElementById("systReg_cvaluefr").value;
		if(cvaluefr){ req=req+"&cvaluefr="+cvaluefr; }
		
		var cvaluept = document.getElementById("systReg_cvaluept").value;
		if(cvaluept){ req=req+"&cvaluept="+cvaluept; }
		
		var cvaluees = document.getElementById("systReg_cvaluees").value;
		if(cvaluees){ req=req+"&cvaluees="+cvaluees; }
		
		var cvaluesw = document.getElementById("systReg_cvaluesw").value;
		if(cvaluesw){ req=req+"&cvaluesw="+cvaluesw; }
		
		var cvalueit = document.getElementById("systReg_cvalueit").value;
		if(cvalueit){ req=req+"&cvalueit="+cvalueit; }
		
		var comment = document.getElementById("systReg_comment").value;
		if(comment){ req=req+"&comment="+comment; }
		
		var id_regvalue = document.getElementById("systReg_id_regvalue").value;
		if(id_regvalue){ req=req+"&id_regvalue="+id_regvalue; }
		
		
		var resurl="listeslies.php?elemid=manag_system_regvalue&conf="+conf+"&id_regvalue="+id_regvalue+req;   
		var xhr = getXhr();  
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;          
				
				if(leselect == 1){
					toastr.success('Regvalue successfully saved',{timeOut:15000})
					$("#modalRegvalue").modal("hide");
					regvalues();
					
				} else 
				if(leselect == 0){
					toastr.error('Unable to save regvalue, please retry!',{timeOut:15000})
				} else {
					internal_error();
				}
				
				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
		
	} else
	if(conf == 'del'){
		
		/* Delete port */
		
		var resurl='listeslies.php?elemid=delete_system_regvalue&id_regvalue='+id_regvalue;    
		var xhr = getXhr();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;         
				
				if(leselect == 1){
					toastr.success('Regvalue successfully deleted',{timeOut:15000})
					regvalues();
					
				} else 
				if(leselect == 0){
					toastr.error('Regvalue not deleted, please retry!',{timeOut:15000})
				} else {
					internal_error();
				}
				
				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
		
	} else {}
}

// listvalues
function regvalues() {
	hideAll();

	$("#db_regvalues").removeClass("hide");
	titleMenuManag("Register values","btn_syst_values");
	
	var resurl='listeslies.php?elemid=regvalues';
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;
			val = leselect.split('##');

			document.getElementById('listvalues').innerHTML = val[0];
			document.getElementById('listregisters').innerHTML = val[1];

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);

	var config = {
		'.chosen-select'           : {},
        '.chosen-select-deselect'  : {allow_single_deselect:true},
        '.chosen-select-no-single' : {disable_search_threshold:10},
        '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
        '.chosen-select-width'     : {width:"95%"}
    }
    for (var selector in config) {
        $(selector).chosen(config[selector]);
    }
}


/* List of table, regvalues and registers */

function listValues() {
	var id_register = document.getElementById('regcat').value;

	var resurl='listeslies.php?elemid=regvalues&id_register='+id_register;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;
			val = leselect.split('##');

			document.getElementById('listvalues').innerHTML = val[0];
			document.getElementById('listregisters').innerHTML = val[1];

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}

/* 
* 
* Register management 
*
*/

function registerManagement(conf,id_register,val) {

	if(conf == 'add'){ 
		/* Add new port */
		
		var req = '';
		
		var regcode = document.getElementById("syst_regcode").value;  
		if(regcode){ req=req+'&regcode='+regcode; }
		
		var regname = document.getElementById("syst_regname").value;  
		if(regname){ req=req+'&regname='+regname; }
		
		var regnamede = document.getElementById("syst_regnamede").value;  
		if(regnamede){ req=req+'&regnamede='+regnamede; }
		
		var regnamefr = document.getElementById("syst_regnamefr").value;  
		if(regnamefr){ req=req+'&regnamefr='+regnamefr; }
		
		var regnamept = document.getElementById("syst_regnamept").value; 
		if(regnamept){ req=req+'&regnamept='+regnamept; }
		
		var regnamees = document.getElementById("syst_regnamees").value; 
		if(regnamees){ req=req+'&regnamees='+regnamees; }
		
		
		if(regcode == ""){
			alert("Enter the code.");
			
		} else 
		if(regname == ""){
			alert("Enter english name.");
			
		} else { 
			var resurl='listeslies.php?elemid=manag_system_register&conf='+conf+'&id_register='+id_register+req;    
			var xhr = getXhr();  
			xhr.onreadystatechange = function(){
				if(xhr.readyState == 4 ){
					leselect = xhr.responseText;       
					
					if(leselect == 1){
						toastr.success('Register successfully added',{timeOut:15000})
						$("#modalRegister").modal("hide");
						regvalues();
					} else 
					if(leselect == 0){
						toastr.error('Register not added, please retry!',{timeOut:15000})
					} else {
						internal_error();
					}
					
					leselect = xhr.responseText;
				}
			};

			xhr.open("GET",resurl,true);
			xhr.send(null);
		}

	} else
	if(conf == 'show'){
		if(val== 'create'){
			document.getElementById("systRegisterForm").reset();
			
			/* Save button */
			document.getElementById('registerModalLabel').innerHTML = "Create new register";
			document.getElementById('registerModalFooter').innerHTML ='<button type="button" class="btn btn-success" onclick="registerManagement(\'add\',\'\',\'\');"><i class="fa fa-save"></i></button>'
				+'<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i></button>';
		} else
		if(val== 'mod'){ 
			
			document.getElementById("systRegisterForm").reset();
			
			var resurl='listeslies.php?elemid=show_system_register&id_register='+id_register;   
			var xhr = getXhr();
			xhr.onreadystatechange = function(){
				if(xhr.readyState == 4 ){
					leselect = xhr.responseText;          
					var val = leselect.split('#');
					
					document.getElementById("syst_regname").value = val[0];
					document.getElementById("syst_regnamede").value = val[1];
					document.getElementById("syst_regnamefr").value = val[2];
					document.getElementById("syst_regnamept").value = val[3];
					document.getElementById("syst_regnamees").value = val[4];
					document.getElementById("syst_regcode").value = val[5];
					
					document.getElementById('syst_id_register').value = id_register;
				
					leselect = xhr.responseText;
				}
			};

			xhr.open("GET",resurl,true);
			xhr.send(null);
	
			
			/* Edit button */
			document.getElementById('registerModalLabel').innerHTML = "Edit register";
			document.getElementById('registerModalFooter').innerHTML ='<button type="button" class="btn btn-success" onclick="registerManagement(\'edit\',\''+id_register+'\',\'\');"><i class="fa fa-save"></i></button>'
				+'<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i></button>';
		} else {}
		
	} else
	if(conf == 'edit'){	 
		
		/* Edit port */
		
		var req = '';

		var regcode = document.getElementById("syst_regcode").value;
		if(regcode){ req=req+'&regcode='+regcode; }
		
		var regname = document.getElementById("syst_regname").value;
		if(regname){ req=req+'&regname='+regname; }
		
		var regnamede = document.getElementById("syst_regnamede").value;
		if(regnamede){ req=req+'&regnamede='+regnamede; }
		
		var regnamefr = document.getElementById("syst_regnamefr").value;
		if(regnamefr){ req=req+'&regnamefr='+regnamefr; }
		
		var regnamept = document.getElementById("syst_regnamept").value;
		if(regnamept){ req=req+'&regnamept='+regnamept; }
		
		var regnamees = document.getElementById("syst_regnamees").value;
		if(regnamees){ req=req+'&regnamees='+regnamees; }
		
		var id_register = document.getElementById("syst_id_register").value;
		if(id_register){ req=req+'&id_register='+id_register; }
		
		
		var resurl='listeslies.php?elemid=manag_system_register&conf='+conf+'&id_register='+id_register+req;    
		var xhr = getXhr();  
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;       
				
				if(leselect == 1){
					toastr.success('Register successfully saved',{timeOut:15000})
					$("#modalRegister").modal("hide");
					regvalues();
					
				} else 
				if(leselect == 0){
					toastr.error('Unable to save register, please retry!',{timeOut:15000})
				} else {
					internal_error();
				}
				
				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
		
	} else
	if(conf == 'del'){
		
		/* Delete port */
		
		var resurl='listeslies.php?elemid=delete_system_register&id_register='+id_register;    
		var xhr = getXhr();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;         
				
				if(leselect == 1){
					toastr.success('Register successfully deleted',{timeOut:15000})
					regvalues();
					
				} else 
				if(leselect == 0){
					toastr.error('Register not deleted, please retry!',{timeOut:15000})
				} else {
					internal_error();
				}
				
				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
		
	} else {}
}


// Delete shipment

function deleteShipment(id_ord_schedule,id_ord_order) {
	swal({
		title: "Delete Shipment",
		text: "ARE You SURE YOU WANT TO DELETE THIS SHIPMENT? You may need to re-issue the following documents: Sales Contract PO to FA PO to Supplier",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#1ab394",
		confirmButtonText: "Delete",
		closeOnConfirm: true
	}, function () {
		deleteCureentShipment(id_ord_schedule,id_ord_order);  
	});
}


function deleteCureentShipment(id_ord_schedule,id_ord_order) {
	var resurl='listeslies.php?elemid=delete_shipment&id_ord_schedule='+id_ord_schedule;    
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;     
			var val = leselect.split('##');
			
			if(val[0] == 1){
				toastr.success('Shipment successfully deleted',{timeOut:15000})
				refreshScheduleShipement(id_ord_order);
				if(val[1]!=""){ 
					var ficheurl='pdf/notification.php?id_ord_schedule='+id_ord_schedule+'&doc_filename='+val[1]+'&old_month_eta=0&old_nr_containers=0&conf=del'; 
					saveNotificationMail(ficheurl);
				}

			} else 
			if(leselect == 0){
				toastr.error('Shipment not deleted, please retry!',{timeOut:15000})
			} else {
				internal_error();
			}
		
			leselect = xhr.responseText;
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}


// Add Shipement

function addShipment(order_id,nr_containers,month_eta,modify_date) {
	var resurl='listeslies.php?elemid=add_shipment&order_id='+order_id+'&nr_containers='+nr_containers+'&month_eta='+month_eta+'&modify_date='+modify_date;    
	var xhr = getXhr(); 
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;   
			var val = leselect.split('##');
			
			if(val[0] == 1){
				toastr.success('Shipment successfully added',{timeOut:15000})
				refreshScheduleShipement(order_id); 
				if(val[1]!=""){ 
					var ficheurl='pdf/notification.php?id_ord_schedule='+order_id+'&doc_filename='+val[1]+'&old_month_eta=0&old_nr_containers=0&conf=add'; 
					saveNotificationMail(ficheurl);
				}

			} else 
			if(val[0] == 0){
				toastr.error('Shipment not added, please retry!',{timeOut:15000})
			} else {
				internal_error();
			}
		
			leselect = xhr.responseText;
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}



function saveNotificationMail(ficheurl){
	var resurl=ficheurl;
    var xhr = getXhr();   
    xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;  

			notifications("mail"); 
			
			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


/* ----- SATRT - TASKS ----- */

function tasks(value) {
	hideAll();
	
	var data = [];
	var demo_tasks = {};

	$("#db_tasks").removeClass("hide");
	titleMenuManag("Task Management","btn_tasks");

	var filtre = document.getElementById('srch-tasks').value;  
	
	document.getElementById('task_gantt').innerHTML = '<div class="h1 m-t-xs text-navy"><span class="loading"></span></div>'; 
	
	var req="";
	if(value!=0){ req='&agent_id='+value; //gantt.clearAll();
	}
	
	var resurl='include/task.php?elemid=tasks_gantt&filtre='+filtre+req; 
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;     
			
			if(leselect == ""){
				document.getElementById('task_gantt').innerHTML = '<i class="fa fa-exclamation-triangle"></i> No data'; 
			} else {
			
				var elmt = leselect.split('@@'); 
				document.getElementById('gantt_task_agent').innerHTML = elmt[2];
				
				i=0;
				var project = elmt[0].split('??');  
				while (project[i]!= 'end') {  
					var pj = project[i].split('|');
					data.push({"id":pj[0], "text":pj[1], "start_date":pj[2], "duration":pj[3], "progress": 0, "open": true, color:"#1ab394"});
					i += 1;
				}
				
				e=0;
				var tasks = elmt[1].split('??');  
				while (tasks[e]!= 'end') {  
					var tk = tasks[e].split('|');
					if(value==0) {
						data.push({"id":tk[0], "text":tk[1], "start_date":tk[2], "duration":tk[3], "parent": tk[4], "progress": 0, "open": true});
					} else {
						data.push({"id":tk[0], "text":tk[1], "start_date":tk[2], "duration":tk[3], "progress": 0, "open": true});
					}
					e += 1;
				}
				
				demo_tasks = {
					"data": data
				}
				
				//console.log(JSON.stringify(demo_tasks));
	
				gantt.init("task_gantt");  
				gantt.clearAll();
				
				gantt.parse(demo_tasks);  
				// gantt.load("https://icoop.live/ic/gantt/gantt_task.php?agent_id="+value);
			
				
				var dp = new gantt.dataProcessor("https://icoop.live/ic/gantt/gantt_task.php?agent_id="+value);
				dp.init(gantt);   
				
			
				// if(filtre==""){
					scaleConfigs = [
						// minutes
						{ unit: "minute", step: 1, scale_unit: "hour", date_scale: "%H", subscales: [
							{unit: "minute", step: 1, date: "%H:%i"}
						]
						},
						// hours
						{ unit: "hour", step: 1, scale_unit: "day", date_scale: "%j %M",
							subscales: [
								{unit: "hour", step: 1, date: "%H:%i"}
							]
						},
						// days
						{ unit: "day", step: 1, scale_unit: "month", date_scale: "%F",
							subscales: [
								{unit: "day", step: 1, date: "%j"}
							]
						},
						// weeks
						{unit: "week", step: 1, scale_unit: "month", date_scale: "%F",
							subscales: [
								{unit: "week", step: 1, template: function (date) {
									var dateToStr = gantt.date.date_to_str("%d %M");
									var endDate = gantt.date.add(gantt.date.add(date, 1, "week"), -1, "day");
									return dateToStr(date) + " - " + dateToStr(endDate);
								}}
							]},
						// months
						{ unit: "month", step: 1, scale_unit: "year", date_scale: "%Y",
							subscales: [
								{unit: "month", step: 1, date: "%M"}
							]},
						// quarters
						{ unit: "month", step: 3, scale_unit: "year", date_scale: "%Y",
							subscales: [
								{unit: "month", step: 3, template: function (date) {
									var dateToStr = gantt.date.date_to_str("%M");
									var endDate = gantt.date.add(gantt.date.add(date, 3, "month"), -1, "day");
									return dateToStr(date) + " - " + dateToStr(endDate);
								}}
							]},
						// years
						{unit: "year", step: 1, scale_unit: "year", date_scale: "%Y",
							subscales: [
								{unit: "year", step: 5, template: function (date) {
									var dateToStr = gantt.date.date_to_str("%Y");
									var endDate = gantt.date.add(gantt.date.add(date, 5, "year"), -1, "day");
									return dateToStr(date) + " - " + dateToStr(endDate);
								}}
							]},
						// decades
						{unit: "year", step: 10, scale_unit: "year", template: function (date) {
							var dateToStr = gantt.date.date_to_str("%Y");
							var endDate = gantt.date.add(gantt.date.add(date, 10, "year"), -1, "day");
							return dateToStr(date) + " - " + dateToStr(endDate);
						},
						subscales: [
							{unit: "year", step: 100, template: function (date) {
								var dateToStr = gantt.date.date_to_str("%Y");
								var endDate = gantt.date.add(gantt.date.add(date, 100, "year"), -1, "day");
								return dateToStr(date) + " - " + dateToStr(endDate);
							}}
						]}
					];
				// }
			}

		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}


function saveGanttData() {
	console.log(12);
}
/* ----- END - TASKS ----- */


/* ----- SATRT - PROJECT ----- */


var label_offline = L.geoJson('', {}).addTo(offline_map);
var region_mrker_couche = L.geoJson('', {}).addTo(offline_map);
var region_quadrant_couche = L.geoJson('', {}).addTo(offline_map);

var label_offline_notIn = L.geoJson('', {});
var region_mrker_couche_notIn = L.geoJson('', {});

var notIn_towns = L.layerGroup([label_offline_notIn, region_mrker_couche_notIn]);

var overlayMaps_offline_map = { 
	"Collection Point": plantation_project_points, 
	// "Plantation Lines": plantation_lines_couche,
	"Plantation": plantation_project_couche,
	"Villages": notIn_towns
};

L.control.layers(baseMaps_offline, overlayMaps_offline_map).addTo(offline_map);

var project_drawControl = new L.Control.Draw({
	position: 'topright',
    edit: {
        featureGroup: drawnRectangle,
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


function projects() {
	hideAll();

	$("#db_projects").removeClass("hide");
	titleMenuManag("Agent Management","btn_projects");
	
	if(project_create == 1){
		$("#newProjectBtn").removeClass("hide");
		$("#newTaskBtn").removeClass("hide");
	}
	
	$("#projectSpanner").removeClass("hide");
	projectsList();
	
	offline_map.invalidateSize();
	offline_map.fitWorld().zoomIn();	
	L.control.scalefactor().addTo(offline_map);
}

// Projects

function projectsList() {
	var resurl='include/projects.php?elemid=projects_list';
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;   
			var val = leselect.split('##');  

			document.getElementById('agent_list').innerHTML = val[0];
			
			var options = {
				valueNames: ['project_reference_nr']
			};

			var projectList = new List('projects', options);

			$("#projectSpanner").addClass("hide");
			
			$('#agent_list li a').click(function() {
				$('ul li.on').removeClass('on');
				$(this).closest('li').addClass('on');
			});
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function editMoveTask(id_task,id_project,project_name) {
	$("#projectTaskModal_task_project_id").prop("disabled", false);
	document.getElementById('task_project_idEdit_box').innerHTML = '<button class="btn btn-primary" onclick="updateTaskProject(\''+id_task+'\',\''+id_project+'\',\''+project_name+'\');" style="margin-top: 30px;"><i class="fa fa-save" aria-hidden="true"></i></button>'+
	' &nbsp;<button class="btn btn-danger" onclick="cancelMoveTask(\''+id_task+'\',\''+id_project+'\',\''+project_name+'\');" style="margin-top: 30px;"><i class="fa fa-ban" aria-hidden="true"></i></button>';
}

function cancelMoveTask(id_task,id_project,project_name) {
	$("#projectTaskModal_task_project_id").prop("disabled", true);
	document.getElementById('task_project_idEdit_box').innerHTML = '<button class="btn" onclick="editMoveTask(\''+id_task+'\',\''+id_project+'\',\''+project_name+'\');" style="margin-top: 30px;"><i class="fa fa-edit" aria-hidden="true"></i></button>';
}

function updateTaskProject(id_task,id_project,project_name) {
	var selected_project_data = document.getElementById('projectTaskModal_task_project_id').value;
	
	var project = selected_project_data.split('@@');
	var selected_id_project = project[0];
	var selected_project_name = project[1];
	
	var confr = confirm("Are you sure you want to moove this task to "+selected_project_name+" ?");

	if(confr == true){ 
		var resurl='include/projects.php?elemid=update_task_project&id_project='+selected_id_project+'&id_task='+id_task; 
		var xhr = getXhr();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;  
				
				if(leselect==1){
					toastr.success('Task successfully moved',{timeOut:15000})
					showProjectSummary(id_project, project_name);
				} else 
				if(leselect==0){
					toastr.error('Task not moved',{timeOut:15000})
				} else {
					internal_error();
				}
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
	}
}


function showProjectSummary(id_project, project_name) { 

	document.getElementById('project_refnum').innerHTML = ' - '+project_name;
	document.getElementById('task_refnum').innerHTML = ' - '+project_name;
	document.getElementById('town_refnum').innerHTML = ' - '+project_name;
	document.getElementById('qM_refnum').innerHTML = ' - '+project_name;
	
	var thumb = '<span style="font-size: 14px;"><i class="fas fa-hand-point-left"></i> '+lg_sel_project_in_list+'</span>';
	
	document.getElementById('taskDetails').innerHTML = thumb;
	$("#projectTaskListFarmersNotInThumb").removeClass("hide");
	$("#projectTownsFarmers").addClass("hide");
	$("#projectTownsAgents").addClass("hide"); 
	
	var resurl='include/projects.php?elemid=show_project_summary&id_project='+id_project+'&project_update='+project_update;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText; //console.log(leselect);
			var val = leselect.split('##');

			document.getElementById('showProjectSum').innerHTML = val[0];
			
			if(project_create == 1){ var show=""; } else { var show="hide"; }
			document.getElementById('projectTaskAdd').innerHTML = '<a href="#" class="pull-right '+show+'" id="newTaskBtn" data-toggle="modal" onclick="new_task('+id_project+','+val[1]+');" data-target="#tasksModal"><i class="fa fa-plus" style="color:white;"></i></a>';
			
			if(val[2] == ""){
				document.getElementById('projectQuadrantAdd').innerHTML = '<a href="#" class="pull-right '+show+'" style="color:white;" id="projectQuadrant" onclick="addQuadrant(\''+id_project+'\',\'add\');">'+lg_add_quadrant_btn+' <i class="fa fa-plus"></i></a>';
			} else {
				document.getElementById('projectQuadrantAdd').innerHTML = '<a href="#" class="pull-right '+show+'" style="color:white;" id="projectQuadrant" onclick="addQuadrant(\''+id_project+'\',\'edit\');">'+lg_edit_quadrant_btn+' <i class="fa fa-edit"></i></a>';
			}
			
			document.getElementById('projectTaskModal_id_country').value = val[3];
			
			var thumb = '<span style="font-size: 14px;"><i class="fas fa-hand-point-left"></i> '+lg_sel_project_in_list+'</span>';
			document.getElementById('projectTaskAgentsListTowns').value = thumb;
			document.getElementById('projectTownsAgents').value = thumb;
			
			$("#selProjectInList").addClass("hide");
			$("#taskShow").removeClass("hide");
			$("#polyBox").addClass("hide");
			
			projectTaskList(id_project);
			showQuadrant(id_project,'',false);
			showProjectRegionMap(id_project);
			showProjectTown_notIn(id_project);
			
			document.getElementById('projectMbtilesAdd').innerHTML = '<a href="#" class="pull-right" style="color:white;" id="projectMbtiles" onclick="addMbtiles('+id_project+');">New Mbtiles <i class="fa fa-plus"></i></a>';
			$("#projectQuadrantThumb").addClass("hide");
			$("#projectQuadrantManage").removeClass("hide");
			
			mbtilesListe(id_project);
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function addMbtiles(id_project) {
	$("#projectQMbtiles").modal("show");
	document.getElementById('mbtiles_id_project').value = id_project;
}


function mbtilesFile(value) {
	if(value!=""){
		// var ext = $('#po_mbtiles').val().split('.').pop().toLowerCase();  
		// if(ext!='mbtiles'){
			// $('#upload_document').on('submit', function(e) {
				// e.preventDefault();
			// });
			
			// toastr.error('invalid file type!',{timeOut:15000})
			// return;
		// }
	
		var _size = $('#po_mbtiles')[0].files[0].size;
		var fSExt = new Array('Bytes', 'KB', 'MB', 'GB'),
		i=0;while(_size>900){_size/=1024;i++;}
		var exactSize = (Math.round(_size*100)/100)+' '+fSExt[i];
	
		$('.file-dummy').removeClass('po_bg_default');
		$('.file-dummy').addClass('po_bg_success');
		
		$('#mbtiles_success').removeClass('hide');
		$('#mbtiles_default').addClass('hide');
		
		$("#uploadMbtilesBtn").prop("disabled", false);
		
		var doc_filesize = '<br/>'+exactSize;
		var doc_filename = value.replace(/C:\\fakepath\\/i, '');
		document.getElementById('mbtiles_success').innerHTML = doc_filename+doc_filesize;
		
	} else {
		$('.file-dummy').removeClass('po_bg_success');
		$('.file-dummy').addClass('po_bg_default');
		
		$('#mbtiles_default').removeClass('hide');
		$('#mbtiles_success').addClass('hide');
		
		document.getElementById('po_default').innerHTML = '<i class="fa fa-file-pdf-o"></i> Drop files here or click to upload.';
	}
}

function mbtiles() { 

	var id_project = document.getElementById('mbtiles_id_project').value;
	
	var spinner = '<div class="sk-spinner sk-spinner-double-bounce div_ov_spanner">'+
		'<div class="sk-double-bounce1"></div>'+
		'<div class="sk-double-bounce2"></div>'+
	'</div>';
	
	var progressbar = '<div id="progress-div"><div id="progress-bar"></div></div>';

	$("#projectQMbtilesContent").append("<div class='div_overlay'>"+spinner+progressbar+"</div>");
	
	$('#upload_mbtilesForm').one('submit', function(e) {

		e.preventDefault();
		$('#progress-div').show();
		$(this).ajaxSubmit({  
			beforeSubmit: function() {
				$("#progress-bar").width('0%');
			},
			uploadProgress: function (event, position, total, percentComplete){	
				$("#progress-bar").width(percentComplete + '%');
				$("#progress-bar").html('<div id="progress-status">' + percentComplete +' %</div>')
			},
			success:function (response){
				var val = response.split('##');
				$(".div_overlay").remove(); 
				$('#progress-div').hide(); 
				$("#uploadMbtilesBtn").prop("disabled", true); 
				
				if(val[0] == 1){
					saveMbtiles(val[1],id_project,val[2],val[3]);
				} else { toastr.error(response,{timeOut:15000}) }
			},
			resetForm: true 
		}); 
		return false; 
	});
	
}


function saveMbtiles(filename,id_project,description,maptype) {

	var resurl='include/projects.php?elemid=save_mbtiles_file&filename='+filename+'&id_project='+id_project+'&description='+description+'&maptype='+maptype;
    var xhr = getXhr();  
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;   

			if(leselect==1){
				toastr.success('File successfully saved',{timeOut:15000})
				mbtilesListe(id_project);
				$("#projectQMbtiles").modal("hide");
			} else 
			if(leselect==0){
				toastr.error('File not saved',{timeOut:15000})
			} else {
				internal_error();
			}
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function mbtilesListe(id_project) {
	var resurl='include/projects.php?elemid=show_mbtiles_liste&id_project='+id_project;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;

			document.getElementById("qM_mbtiles_list").innerHTML = leselect; 
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}

function deleteMbtiles(id,id_project) {
	var resurl='include/projects.php?elemid=delete_mbtiles&id='+id;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;

			if(leselect==1){
				toastr.success('File successfully deleted',{timeOut:15000})
				mbtilesListe(id_project);
			} else 
			if(leselect==0){
				toastr.error('File not deleted',{timeOut:15000})
			} else {
				internal_error();
			}
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function toggleVisibleMbt(visible,id,id_project) {
	
	if(visible==1){ var new_value=0; } else { var new_value=1; }
	var resurl='include/projects.php?elemid=toggle_mbtiles_visibility&id='+id+'&visible='+new_value;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;

			if(leselect==1){
				toastr.success('Status successfully changed',{timeOut:15000})
				mbtilesListe(id_project);
			} else 
			if(leselect==0){
				toastr.error('Status not changed',{timeOut:15000})
			} else {
				internal_error();
			}
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function showProjectRegionMap(id_project) {
	
	showProjectCollectionPoints(id_project);
	showProjectPlantations(id_project);
	
	if(region_mrker_couche){ region_mrker_couche.clearLayers(); }
	if(label_offline){ label_offline.clearLayers(); }
	
	var resurl='include/projects.php?elemid=show_region_on_map&id_project='+id_project;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;   
			var data = leselect.split('@@');
			
			i = 0; var x, y;
			while(data[i] != 'end'){
				
				var elt=data[i].split('##');
				var popupContent;
			
				if((elt[1]!="")&&(elt[2]!="")) {
					popupContent = elt[0];
					var mark = L.marker([elt[1], elt[2]],{icon: townIcon,riseOnHover:true}).bindPopup(popupContent).addTo(region_mrker_couche); 
					
					var divIcon = L.divIcon({ 
						className: "labelClass",
						iconAnchor:[-15,25],
						html: elt[0]
					});

					var mark2 = L.marker([elt[1], elt[2]], {icon: divIcon }).addTo(label_offline); 
					offline_map.addLayer(region_mrker_couche);
				} 
				
				x= elt[1]; y= elt[2];
				i += 1;
			}
			
			if(i == 1){
				offline_map.setView([x, y], 9); 
			} else
			if(i > 1){
				offline_map.fitBounds(region_mrker_couche.getBounds().extend(region_quadrant_couche.getBounds()));  
			} else {
				offline_map.fitWorld().zoomIn();
			}
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function showProjectPlantations(id_project) {
	
	plantation_project_couche.clearLayers();
	
	var resurl='include/projects.php?elemid=show_project_plantations&id_project='+id_project;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4){
            leselect = xhr.responseText;
			
			var plantations = JSON.parse(leselect); 
			
			plantation_project_couche.addData(plantations);	
			// offline_map.fitBounds(plantation_project_couche.getBounds().extend(plantation_project_points.getBounds()));
			offline_map.addLayer(plantation_project_couche);   

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function showProjectCollectionPoints(id_project) {
	
	plantation_project_points.clearLayers();
	
	var resurl='include/projects.php?elemid=show_project_collection_points&id_project='+id_project;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4){
            leselect = xhr.responseText;  
		
			var plantations = JSON.parse(leselect); 
			var n = parseInt(JSON.stringify(plantations.length));   
			
			i=0;
			while(i<n){  
				
				if(plantations[i].properties.name_farmer === null){ var name_farmer=""; } else { var name_farmer=plantations[i].properties.name_farmer; } 
				if(plantations[i].properties.name_farmergroup === null){ var name_farmergroup=""; } else { var name_farmergroup=plantations[i].properties.name_farmergroup; }  
				if(plantations[i].properties.name_town === null){ var name_town=""; } else { var name_town=plantations[i].properties.name_town; }  
				if(plantations[i].properties.code_farmer === null){ var code_farmer=""; } else { var code_farmer=plantations[i].properties.code_farmer; } 
				if(plantations[i].properties.culture === null){ var culture=""; } else { var culture=plantations[i].properties.culture; } 
				if(plantations[i].properties.area === null){ var area=""; } else { var area=plantations[i].properties.area; } 
				if(plantations[i].properties.name_buyer === null){ var name_buyer=""; } else { var name_buyer=plantations[i].properties.name_buyer; }  
				
				var popupContent = "<div style=\"max-width:400px; max-height: 200px\"><h5 style=\"border-bottom: 1px solid #eee;\">"+blanc
					+"<i class=\"fa fa-check-square fa-fw\" style=\"color:#ed1b2c\"></i><strong style=\"color:#ed1b2c\">&nbsp;&nbsp;Collection Point</strong></h5>"+blanc
					+"<div class=\"icon_desc\" style=\"margin-left:0px;display:block\"><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Farmer name : </strong>"+name_farmer
					+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Farmer group : </strong>"+name_farmergroup
					+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Farmer residence : </strong>"+name_town
					+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Code Farmer : </strong>"+code_farmer
					+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Culture : </strong>"+culture
					+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Area (ha) : </strong>"+area
					+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Buyer : </strong>"+name_buyer
				+" </span></div></div>";
				
				mark = L.marker([plantations[i].properties.coordx, plantations[i].properties.coordy], {icon: pointIcon,riseOnHover:true})
					.bindPopup(popupContent)
					.addTo(plantation_project_points);  
				
				offline_map.addLayer(plantation_project_points);  
				
				i += 1;
			} 
	
			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
	
	// plantation_points.on('clusterclick', function (a) {
		// a.layer.zoomToBounds();
	// });
}


function new_project(conf) {
	
	clearProjectForm();
	
	var spinner = '<div class="sk-spinner sk-spinner-double-bounce div_ov_spanner">'+
		'<div class="sk-double-bounce1"></div>'+
		'<div class="sk-double-bounce2"></div>'+
	'</div>';
	
	$("#projectModal").append("<div class='div_overlay'>"+spinner+"</div>");
	
	var req="";
	if(conf != 'new'){
		var project_id_company = document.getElementById('projectID_company').value;
		req = req+'&project_id_company='+project_id_company;
	}

	var resurl='include/projects.php?elemid=new_projects_listlie'+req;  
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;  
			var val = leselect.split('##');  

			document.getElementById('projectModal_id_country').innerHTML = val[0];
			document.getElementById('projectModal_id_company').innerHTML = val[1];
			document.getElementById('projectModal_region_name').innerHTML = val[2];
			document.getElementById('projectModal_project_type').innerHTML = val[4];
			document.getElementById('projectModal_id_culture').innerHTML = val[5];
			document.getElementById('projectModal_project_status').innerHTML = val[6];
			document.getElementById('projectModal_cooperative_id').innerHTML = val[7];
			document.getElementById('projectModal_id_primary_company').innerHTML = val[8];
			document.getElementById('projectModal_project_manager_id').innerHTML = val[9]; 
			
			if(conf == 'new'){
				// $("#HQ_field").addClass("hide"); removed 30/11/2020
				$("#HQ_field").removeClass("hide");
				
				document.getElementById('projectModalLabel').innerHTML = 'New project';
				document.getElementById('projectModalFooter').innerHTML = '<button type="button" class="btn btn-primary" id="saveProjectBTN" onclick="saveProject();"><i class="fa fa-save"></i></button>'
					+'<button type="button" class="btn btn-danger" data-dismiss="modal" onclick="clearProjectForm();"><i class="fa fa-ban"></i></button>';
			} else {
				document.getElementById('projectModalLabel').innerHTML = 'Edit project';
				document.getElementById('projectModalFooter').innerHTML = '';
			}
			
			$('.edit_delivery_date').datepicker({
				format: "yyyy/mm/dd",
				calendarWeeks:true,
				autoclose: true
			}).datepicker('setDate', new Date());
	
			$(".div_overlay").remove();
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function regionsOfSelCountry(id_country) {
	var resurl='include/projects.php?elemid=regions_and_towns_of_selected_country&id_country='+id_country;  
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText; 
			var val = leselect.split('##');

			document.getElementById('projectModal_region_name').innerHTML = val[0];
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function townsOfSelRegionId(region1) {
	
	var id_country = document.getElementById('projectTaskModal_id_country').value;
	
	var resurl='include/projects.php?elemid=towns_of_selected_region_id&region1='+region1+'&id_country='+id_country;  
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText; 
			var val = leselect.split('##');

			document.getElementById('projectTaskModal_region_2').innerHTML = val[0];
			document.getElementById('projectTaskModal_region_3').innerHTML = val[0];
			document.getElementById('projectTaskModal_region_4').innerHTML = val[0];
			document.getElementById('list_townProjectTask').innerHTML = '<select class="form-control dual_select" id="projectTaskModal_towns" multiple >'+val[1]+'</select>';
			
			$('.dual_select').bootstrapDualListbox({
                selectorMinimalHeight: 160
            });
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function townsOfSelRegionName(conf) {
	
	var region1 = document.getElementById('projectTaskModal_region_1').value;
	var region2 = document.getElementById('projectTaskModal_region_2').value;
	var region3 = document.getElementById('projectTaskModal_region_3').value;
	var region4 = document.getElementById('projectTaskModal_region_4').value;
	
	var id_country = document.getElementById('projectTaskModal_id_country').value;
	
	
	var resurl='include/projects.php?elemid=towns_of_selected_region_name&region1='+region1+'&id_country='+id_country+'&region2='+region2+'&region3='+region3+'&region4='+region4;  
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText; 
			var val = leselect.split('##');

			if(conf == 2){
				document.getElementById('projectTaskModal_region_3').innerHTML = val[0];
				document.getElementById('projectTaskModal_region_4').innerHTML = val[0];
			} else
			if(conf == 3){
				document.getElementById('projectTaskModal_region_4').innerHTML = val[0];
			}
			
			document.getElementById('list_townProjectTask').innerHTML = '<select class="form-control dual_select" id="projectTaskModal_towns" multiple >'+val[1]+'</select>';  
			
			$('.dual_select').bootstrapDualListbox({
                selectorMinimalHeight: 160
            });

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function getCountryOfSelComp(value) {
	var data = value.split('??');
	var id_country = data[1];
	
	var resurl='include/projects.php?elemid=countries_of_selected_company&id_country='+id_country;  
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText; 

			document.getElementById('projectModal_id_country').innerHTML = leselect;  
	
			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function clearProjectForm() {
	$('#projectModalForm').find("input, textarea, select").val(""); 
}


function saveProject() {
	var req="";
	var project_name=document.getElementById('projectModal_project_name').value;
	if(project_name){ req=req+'&project_name='+project_name; }
	
	var project_type=document.getElementById('projectModal_project_type').value;
	if(project_type){ req=req+'&project_type='+project_type; }
	
	var project_status=document.getElementById('projectModal_project_status').value;
	if(project_status){ req=req+'&project_status='+project_status; }
	
	var start_date=document.getElementById('projectModal_start_date').value;
	if(start_date){ req=req+'&start_date='+start_date; }
	
	// var end_date=document.getElementById('projectModal_end_date').value;
	// if(end_date){ req=req+'&end_date='+end_date; }
	
	var due_date=document.getElementById('projectModal_due_date').value;
	if(due_date){ req=req+'&due_date='+due_date; }
	
	var country_id=document.getElementById('projectModal_id_country').value;
	if(country_id){ req=req+'&country_id='+country_id; }
	
	var data=document.getElementById('projectModal_id_company').value;
	if(data){ 
		var data1 = data.split('??');
		var id_company = data1[0];  
		req=req+'&id_company='+id_company; 
	}
	
	var id_culture=document.getElementById('projectModal_id_culture').value;
	if(id_culture){ req=req+'&id_culture='+id_culture; }
	
	var region_name=document.getElementById('projectModal_region_name').value;
	if(region_name){ req=req+'&region_name='+region_name; }
	
	var cooperative_id=document.getElementById('projectModal_cooperative_id').value;
	if(cooperative_id){ req=req+'&cooperative_id='+cooperative_id; }
	
	var project_mgr_company_id=document.getElementById('projectModal_id_primary_company').value;
	if(project_mgr_company_id){ req=req+'&project_mgr_company_id='+project_mgr_company_id; }
	
	var project_manager_id=document.getElementById('projectModal_project_manager_id').value;
	if(project_manager_id){ req=req+'&project_manager_id='+project_manager_id; }
	
	if(project_name==""){
		toastr.info('Enter a project name.',{timeOut:15000})
	} else 
	if(project_type==""){
		toastr.info('Select a project type.',{timeOut:15000})
		
	} else {
		var resurl='include/projects.php?elemid=project_management&conf=add'+req;    
		var xhr = getXhr();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;      
				
				if(leselect == 1){
					toastr.success('Project successfully saved',{timeOut:15000})
					$("#projectModal").modal("hide");
					projectsList();
				
				} else 
				if(leselect == 0){
					toastr.error('Project not saved, please retry!',{timeOut:15000})
				} else {
					internal_error();
				}
				
				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
	} 
}


function showProjectDetails(id_project) {
	
	new_project('show');  
	
	setTimeout(function(){
		
		$("#HQ_field").removeClass("hide");
		
        var resurl='include/projects.php?elemid=show_project_details&id_project='+id_project;  
		var xhr = getXhr();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText; 
				var val = leselect.split('##');
				
				regionsOfSelCountry(val[5]);

				document.getElementById('projectModal_project_name').value = val[0];
				document.getElementById('projectModal_project_type').value = val[1];
				document.getElementById('projectModal_project_status').value = val[2];
				document.getElementById('projectModal_start_date').value = val[3];
				// document.getElementById('projectModal_end_date').value = val[4];
				document.getElementById('projectModal_id_country').value = val[5];  
				document.getElementById('projectModal_id_company').value = val[6]; 
				document.getElementById('projectModal_id_culture').value = val[7];
				document.getElementById('projectModal_region_name').value = val[8];
				document.getElementById('projectModal_due_date').value = val[9];
				document.getElementById('projectModal_cooperative_id').value = val[10];
				document.getElementById('projectModal_id_primary_company').value = val[12];
				document.getElementById('projectModal_project_manager_id').value = val[13];
				

				document.getElementById('projectModalLabel').innerHTML = 'Edit '+val[0];
				document.getElementById('projectModalFooter').innerHTML = '<button type="button" class="btn btn-primary" onclick="saveEditedProject('+id_project+');"><i class="fa fa-save"></i></button>'
					+'<button type="button" class="btn btn-danger" data-dismiss="modal" onclick="clearProjectForm();"><i class="fa fa-ban"></i></button>';
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
    },3000);
}


function saveEditedProject(id_project) {
	var req="";
	var project_name=document.getElementById('projectModal_project_name').value;
	if(project_name){ req=req+'&project_name='+project_name; }
	
	var project_type=document.getElementById('projectModal_project_type').value;
	if(project_type){ req=req+'&project_type='+project_type; }
	
	var project_status=document.getElementById('projectModal_project_status').value;
	if(project_status){ req=req+'&project_status='+project_status; }

	var start_date=document.getElementById('projectModal_start_date').value;
	if(start_date){ req=req+'&start_date='+start_date; }

	var due_date=document.getElementById('projectModal_due_date').value;
	if(due_date){ req=req+'&due_date='+due_date; }
	
	var country_id=document.getElementById('projectModal_id_country').value;
	if(country_id){ req=req+'&country_id='+country_id; }
	
	var data=document.getElementById('projectModal_id_company').value;
	if(data){ 
		var data1 = data.split('??');
		var id_company = data1[0];  
		req=req+'&id_company='+id_company; 
	}
	
	var id_culture=document.getElementById('projectModal_id_culture').value;
	if(id_culture){ req=req+'&id_culture='+id_culture; }
	
	var region_name=document.getElementById('projectModal_region_name').value;
	if(region_name){ req=req+'&region_name='+region_name; }
	
	var cooperative_id=document.getElementById('projectModal_cooperative_id').value;
	if(cooperative_id){ req=req+'&cooperative_id='+cooperative_id; }
	
	var project_mgr_company_id=document.getElementById('projectModal_id_primary_company').value;
	if(project_mgr_company_id){ req=req+'&project_mgr_company_id='+project_mgr_company_id; }
	
	var project_manager_id=document.getElementById('projectModal_project_manager_id').value;
	if(project_manager_id){ req=req+'&project_manager_id='+project_manager_id; }
	

	var resurl='include/projects.php?elemid=project_management&conf=edit&id_project='+id_project+req;    
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;     
			
			if(leselect == 1){
				toastr.success('Project successfully saved',{timeOut:15000})
				$("#projectModal").modal("hide");
				showProjectSummary(id_project, project_name);
			
			} else 
			if(leselect == 0){
				toastr.error('Project not saved, please retry!',{timeOut:15000})
			} else {
				internal_error();
			}
			
			leselect = xhr.responseText;
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}


var rectangle="", edited_rectangle="";
var saveProjectQBtn, cancelProjectQBtn;


function showQuadrant(id_project,conf,show) {
	
	if(region_quadrant_couche) {
		region_quadrant_couche.removeLayer(rectangle);
		region_quadrant_couche.clearLayers();  
	}

	var resurl='include/projects.php?elemid=region_show_quadrant&id_project='+id_project;    
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;    
			
			if(leselect!=""){  
				var bounds = [];
				var quadrant = JSON.parse(leselect);  
				var long_ = quadrant.coordinates[0].length;
				for(var i=0; i<long_; i++){ 
					bounds.push([quadrant.coordinates[0][i][1],quadrant.coordinates[0][i][0]]);
				}
	
				rectangle = new L.Polygon(bounds);
				
				var polygon2 = turf.polygon([bounds]);   
				var box_area = turf.area(polygon2);
				var poly_ha = box_area * 0.0001;
				document.getElementById('polyBox').innerHTML = poly_ha.toFixed(2)+' Ha';
				
				$("#polyBox").removeClass("hide");
				
				if(show == true){
					rectangle.editing.enable();
					
					rectangle.on('edit', function(e) {
						var json = e.target.toGeoJSON();
						rectangle = JSON.stringify(json);
						seeArea = L.GeometryUtil.geodesicArea(e.target.getLatLngs());
					});	
				} 
				
				region_quadrant_couche.addLayer(rectangle);
				// offline_map.addLayer(region_quadrant_couche);
			}
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}


function addQuadrant(id_project,conf) { 

	$('body').animate({
        scrollTop: eval($('#offline_map').offset().top - 70)
    }, 1000);
	
	if(saveProjectQBtn){ offline_map.removeControl(saveProjectQBtn); }
	if(cancelProjectQBtn){ offline_map.removeControl(cancelProjectQBtn); }
	
	offline_map.removeControl(project_drawControl);
	offline_map.addControl(project_drawControl);
	drawnRectangle.clearLayers();
	
	saveProjectQBtn = L.easyButton('fa fa-save fa-lg', function(btn, offline_map){ saveQuadrantDraw(id_project,conf); });
	cancelProjectQBtn = L.easyButton('fa fa-ban fa-lg', function(btn, offline_map){ closeQuadrantDraw(id_project,conf); });
	
	saveProjectQBtn.addTo(offline_map);
	cancelProjectQBtn.addTo(offline_map);
	
	showQuadrant(id_project,conf,true);
	
	offline_map.on(L.Draw.Event.CREATED, function (event) {
		var layer = event.layer;

		drawnRectangle.addLayer(layer);
		offline_map.addLayer(drawnRectangle);

		var type = event.layerType;
		var seeArea = L.GeometryUtil.geodesicArea(layer.getLatLngs());
	
		var json = drawnRectangle.toGeoJSON();
		rectangle = JSON.stringify(json);
	});
	
	offline_map.on(L.Draw.Event.EDITED, function(event) {
		var layers = event.layers;
		
		drawnRectangle.addLayer(layers);
		offline_map.addLayer(drawnRectangle);
		
		var json = drawnRectangle.toGeoJSON();
		rectangle = JSON.stringify(json);
	});
	
	document.getElementById('projectQuadrantAdd').innerHTML = '<span class="pull-right"><a href="#" style="color:white;" onclick="saveQuadrantDraw(\''+id_project+'\',\''+conf+'\');"><i class="fa fa fa-save"></i></a>'
		+'&nbsp;&nbsp;<a href="#" style="color:white;" onclick="closeQuadrantDraw(\''+id_project+'\',\''+conf+'\');"><i class="fa fa fa-ban"></i></a></span>';
}


function saveQuadrantDraw(id_project,conf) { 

	var json;
	
	if(conf == "add") {
		json = JSON.parse(rectangle); 
		json = JSON.stringify(json.features[0].geometry);
	} else {  
		json = JSON.parse(rectangle); 
		json = JSON.stringify(json.geometry); 
	}

	var resurl='include/projects.php?elemid=update_project_quadrant&id_project='+id_project+'&region_quadrant='+json;    
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;    
			
			if(leselect == 1){
				toastr.success('Quadrant successfully saved',{timeOut:15000})
				closeQuadrantDraw(id_project,conf);
				sendQuadrantMail(id_project);
			} else 
			if(leselect == 0){
				toastr.error('Quadrant not saved, please retry!',{timeOut:15000})
			} else {
				internal_error();
			}
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}


function sendQuadrantMail(id_project) {
	var resurl='include/projects.php?elemid=send_quadrant_mail&id_project='+id_project;    
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;     
			
			if(leselect == 1){
				toastr.success('Mail successfully sent',{timeOut:15000})
			} else 
			if(leselect == 0){
				toastr.error('Mail not sent, please retry!',{timeOut:15000})
			} else {
				internal_error();
			}
			
			leselect = xhr.responseText;
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}


function closeQuadrantDraw(id_project,conf) { 

	drawnRectangle.clearLayers();
	
	if(rectangle){
		offline_map.removeLayer(rectangle);
	}
	offline_map.removeControl(project_drawControl);
	offline_map.removeControl(saveProjectQBtn);
	offline_map.removeControl(cancelProjectQBtn);

	var html='';
	if(conf=='edit'){ html=lg_edit_quadrant_btn+' <i class="fa fa-edit"></i>'; }
	else { html=lg_add_quadrant_btn+' <i class="fa fa-plus"></i>'; }
	
	document.getElementById('projectQuadrantAdd').innerHTML = '<a href="#" class="pull-right" style="color:white;" id="projectQuadrant" onclick="addQuadrant(\''+id_project+'\',\''+conf+'\');">'+html+'</i></a>';
	
	showQuadrant(id_project,conf,false);
}


// Tasks

function new_task(id_project,country_id) {
	
	clearProjectTaskForm();
	
	var spinner = '<div class="sk-spinner sk-spinner-double-bounce div_ov_spanner">'+
		'<div class="sk-double-bounce1"></div>'+
		'<div class="sk-double-bounce2"></div>'+
	'</div>';
	
	$("#tasksModal").append("<div class='div_overlay'>"+spinner+"</div>");

	var resurl='include/projects.php?elemid=new_task_listlie&country_id='+country_id+'&id_project='+id_project;  
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;   
			var val = leselect.split('##');
			
			document.getElementById('projectTaskModal_id_country').value = country_id;
			
			document.getElementById('projectTaskModal_region_1').innerHTML = val[0];
			document.getElementById('projectTaskModal_region_2').innerHTML = val[0];
			document.getElementById('projectTaskModal_region_3').innerHTML = val[0];
			document.getElementById('projectTaskModal_region_4').innerHTML = val[0];
			document.getElementById('list_townProjectTask').innerHTML = '<select class="form-control dual_select" id="projectTaskModal_towns" multiple >'+val[1]+'</select>';
		
			document.getElementById('projectTaskModalLabel').innerHTML = 'New Place';
			document.getElementById('projectTaskModalFooter').innerHTML = '<button type="button" class="btn btn-primary" onclick="saveProjectTask('+id_project+');"><i class="fa fa-save"></i></button>'
				+'<button type="button" class="btn btn-danger" data-dismiss="modal" onclick="clearProjectTaskForm();"><i class="fa fa-ban"></i></button>';
			
			$('.dual_select').bootstrapDualListbox({
                selectorMinimalHeight: 160
            });
	
			$(".div_overlay").remove();
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function saveProjectTask(id_project) {
	
	var req="";
	
	var towns = $('#projectTaskModal_towns').val(); //alert(towns);
	if(towns){ req=req+'&towns='+towns; }
	
	var resurl='include/projects.php?elemid=projectTask_management&conf=add&id_project='+id_project+req;    
	var xhr = getXhr();  //console.log(resurl);
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;   //console.log(leselect);
			
			if(leselect == 1){
				toastr.success('Task successfully saved',{timeOut:15000})
				$("#tasksModal").modal("hide");
				projectTaskList(id_project);
			
			} else 
			if(leselect == 0){
				toastr.error('Task not saved, please retry!',{timeOut:15000})
			} else {
				internal_error();
			}
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}


function clearProjectTaskForm() {
	$('#projectTaskModalForm').find("input, textarea, select").val(""); 
}


function projectTaskList(id_project) { 
	
	document.getElementById('projectTaskListTowns').innerHTML = "";
	
	var resurl='include/projects.php?elemid=tasks_list&id_project='+id_project+'&project_update='+project_update;    
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;  
			var val = leselect.split('##');		
			
			// TAB 1
			document.getElementById('projectTaskListTowns').innerHTML = val[0];
			
			$('#projectTowns').slimScroll({ height: '350px', railOpacity: 0.9 });
			
			var options = {
				valueNames: ['project_task_reference_nr']
			};

			var taskList = new List('projectTowns', options);
			
			$('#projectTaskListTowns li a').click(function() {
				$('ul li.on1').removeClass('on1');
				$(this).closest('li').addClass('on1');
			});
			
			// TAB 2
			document.getElementById('projectTaskAgentsListTowns').innerHTML = val[1];
			
			$('#projectAgentTowns').slimScroll({ height: '350px', railOpacity: 0.9 });
			
			var options = {
				valueNames: ['project_task_reference_nr2']
			};

			var taskList = new List('projectAgentTowns', options);
			
			$('#projectTaskAgentsListTowns li a').click(function() {
				$('ul li.on2').removeClass('on2');
				$(this).closest('li').addClass('on2');
			});
			
			// TAB 3  
			$("#offline_map_box").removeClass("col-md-12");
			$("#offline_map_towns").removeClass("hide");
			$("#offline_map_box").addClass("col-md-9");
			
			document.getElementById('projectTaskSequenceListTowns').innerHTML = val[2];
			
			$('#projectSequenceTowns').slimScroll({ height: '500px', railOpacity: 0.9 });
			
			$("#projectTaskSequenceListTowns").sortable({ 
                connectWith: ".connectList",
                update: function( event, ui ) {
                    var towns = $("#projectTaskSequenceListTowns").sortable("toArray");
					
					$.each(towns, function( index, value ) {
						var sequence = index + 1;
						updateTownsSequence(sequence,value);
					});
					
					projectTaskList(id_project);
					toastr.success('Sequence successfully updated',{timeOut:15000})
                }
            }).disableSelection();
			
			leselect = xhr.responseText;
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}

var drawnItemsTownCoords = L.featureGroup();
L.control.layers(baseMaps_townCoords_modal, overlayMaps, { 'drawlayer': drawnItemsTownCoords }, { position: 'topright', collapsed: false }).addTo(townCoords_modal_map);

var drawingControlTownCoords = new L.Control.Draw({
	draw: {
        polygon: false,
		polyline: false,
        circle: false,
		rectangle: false,
		circlemarker: false,
        marker: true
    },
    edit: {
        featureGroup: drawnItemsTownCoords,
        poly: {
            allowIntersection: false
        }
    }
    
});

function editTownCoordsModal(id_town,town_name,x,y) {  
	$("#townsCoordsModal").modal("show");
	
	drawnItemsTownCoords.clearLayers(); 
	
	setTimeout(function() {
		townCoords_modal_map.invalidateSize();
		townCoords_modal_map.fitWorld().zoomIn();
	}, 1000);
	
	document.getElementById('townCoordsModal_TownName').innerHTML = town_name;
	document.getElementById('townCoordsModal_id_town').value = id_town;	

    townCoords_modal_map.addControl(drawingControlTownCoords);

    townCoords_modal_map.on(L.Draw.Event.CREATED, function (event) {
        var layer = event.layer;  
		
		drawnItemsTownCoords.clearLayers();

        drawnItemsTownCoords.addLayer(layer);  
		townCoords_modal_map.addLayer(drawnItemsTownCoords);
		
		var json = drawnItemsTownCoords.toGeoJSON();  
		
		document.getElementById('townCoordsModal_x').value = json.features[0].geometry.coordinates[1];
		document.getElementById('townCoordsModal_y').value = json.features[0].geometry.coordinates[0];
    });
	
	townCoords_modal_map.on(L.Draw.Event.EDITED, function(event) {
        var layers = event.layers;
     
		var json = layers.toGeoJSON();
		
        document.getElementById('townCoordsModal_x').value = json.features[0].geometry.coordinates[1];
		document.getElementById('townCoordsModal_y').value = json.features[0].geometry.coordinates[0];
    });
}


function cleareditTownCoordsForm() {
	$("#townsCoordsModal").modal("hide");
	$('#townsCoordsModalForm').find("input, textarea, select").val(""); 
}

function editTownCoords() {
	var x = document.getElementById('townCoordsModal_x').value;
	var y = document.getElementById('townCoordsModal_y').value;
	var id_town = document.getElementById('townCoordsModal_id_town').value;
	
	var resurl='include/projects.php?elemid=update_towns_coords&id_town='+id_town+'&x='+x+'&y='+y;   
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;  

			if(leselect == 1){
				toastr.success('Coordinates successfully saved',{timeOut:15000})
				cleareditTownCoordsForm();
			} else 
			if(leselect == 0){
				toastr.error('Coordinates not saved, please retry!',{timeOut:15000})
			} else {
				internal_error();
			}
			
			leselect = xhr.responseText;
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}


function updateTownsSequence(sequence,id_task) {
	var resurl='include/projects.php?elemid=update_towns_sequence&id_task='+id_task+'&sequence='+sequence;  
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;   
			
			leselect = xhr.responseText;
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}


function showProjectTaskSummary(id_task, id_project) {

	var resurl='include/projects.php?elemid=task_details&id_task='+id_task+'&update_right='+project_update;    
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;    
			
			document.getElementById('taskDetails').innerHTML = leselect;
			
			$('.i-checks').iCheck({
				checkboxClass: 'icheckbox_square-green',
				radioClass: 'iradio_square-green'
			});
			
			showProjectTown(id_task);
			showProjectTown_notIn(id_project);
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);	
}


function projectTaskAgentList(id_task,agent_id,id_town,id_company,id_project) {
	
	offline_map.removeLayer(agent_couche);
	
	document.getElementById('all_farmer_in').innerHTML = '<button class="btn btn-sm btn-primary" onclick="allFarmerIn('+id_task+','+agent_id+','+id_town+','+id_company+','+id_project+');" style="margin-left: 10px;">'
		+'<i class="fa fa-chevron-right"></i> All'
	+'</button>';
	
	document.getElementById('all_farmer_out').innerHTML = '<button class="btn btn-sm btn-primary pull-left" onclick="allFarmerOut('+id_task+','+agent_id+','+id_town+','+id_company+','+id_project+');" style="margin-right: 10px;">'
		+'<i class="fa fa-chevron-left"></i> All'
	+'</button>';
	
	var resurl='include/projects.php?elemid=project_task_agent_list&id_task='+id_task+'&agent_id='+agent_id+'&id_town='+id_town+'&id_company='+id_company+'&id_project='+id_project;    
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;   
			var val = leselect.split('##');
			
			document.getElementById('projectTaskListAgents').innerHTML = val[0];
			document.getElementById('projectTaskListFarmersNotIn').innerHTML = val[1];
			
			$('#projectTaskListFarmersNotIn').slimScroll({ height: '350px', railOpacity: 0.9 });
			
			$("#projectTaskListFarmersNotInThumb").addClass("hide");
			$("#projectTownsAgents").removeClass("hide");
			
			var options = {
				valueNames: ['project_agents_nr']
			};

			var agentList = new List('agentCardRef', options);
			
			document.getElementById('projectTaskListFarmers').innerHTML = val[2];
			
			$("#projectTownsFarmers").removeClass("hide");
			
			$('#projectTaskListFarmers').slimScroll({ height: '300px', railOpacity: 0.9 });
			
			var options2 = {
				valueNames: ['project_farmers_nr']
			};

			var farmerList = new List('farmerCardRef', options2);
			
			// $('#projectTaskListFarmersNotIn li a').click(function() {
				// $('ul li.on3').removeClass('on3');
				// $(this).closest('li').addClass('on3');
			// });
			
			// showProjectAgentsMap(id_town,id_company);
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}


function allFarmerIn(id_task,agent_id,id_town,id_company,id_project) {
	
	var resurl='include/projects.php?elemid=project_task_add_all_farmer_in&id_task='+id_task+'&agent_id='+agent_id+'&id_town='+id_town+'&id_company='+id_company+'&id_project='+id_project;    
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;   //console.log(leselect);

			if(leselect==1){
				toastr.success('All Farmers successfully added',{timeOut:15000})
				projectTaskAgentList(id_task,agent_id,id_town,id_company,id_project);
			} else 
			if(leselect==0){
				toastr.error('All Farmers not added',{timeOut:15000})
			} else {
				internal_error();
			}
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}

function allFarmerOut(id_task,agent_id,id_town,id_company,id_project) {
	
	var resurl='include/projects.php?elemid=project_task_remove_all_farmer_out&id_task='+id_task+'&agent_id='+agent_id+'&id_town='+id_town+'&id_company='+id_company+'&id_project='+id_project;    
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;   //console.log(leselect);

			if(leselect==1){
				toastr.success('All Farmers successfully removed',{timeOut:15000})
				projectTaskAgentList(id_task,agent_id,id_town,id_company,id_project);
			} else 
			if(leselect==0){
				toastr.error('All Farmers not removed',{timeOut:15000})
			} else {
				internal_error();
			}
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}


function addFarmerInList(id_project,id_task,id_contact,id_company,id_town,plantation_id) {
	var agent_id = document.getElementById('projectTaskListAgents').value;
	
	if(agent_id!="") {
		var resurl='include/projects.php?elemid=project_task_add_farmer_in&id_task='+id_task+'&agent_id='+agent_id+'&id_contact='+id_contact+'&id_project='+id_project+'&plantation_id='+plantation_id;    
		var xhr = getXhr();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;   
				if(leselect==1){
					toastr.success('Farmer successfully added',{timeOut:15000})
					projectTaskAgentList(id_task,agent_id,id_town,id_company,id_project);
				} else 
				if(leselect==0){
					toastr.error('Farmer not added',{timeOut:15000})
				} else {
					internal_error();
				}
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
	
	} else {
		toastr.info('Select an agent',{timeOut:15000})
		agent_id.children(":first").focus();
	}
}


function removeFarmerInList(id_task,agent_id,id_town,id_company,id_project,id_contact) {
	
	var resurl='include/projects.php?elemid=project_task_remove_farmer&id_task='+id_task+'&id_contact='+id_contact+'&id_project='+id_project;    
		var xhr = getXhr();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;    
				if(leselect==1){
					toastr.success('Farmer successfully removed',{timeOut:15000})
					projectTaskAgentList(id_task,agent_id,id_town,id_company,id_project);
				} else 
				if(leselect==0){
					toastr.error('Farmer not removed',{timeOut:15000})
				} else {
					internal_error();
				}
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
}


function showProjectAgentsMap(id_town,id_company) {
	
	region_mrker_couche.clearLayers();
	offline_map.removeLayer(agent_couche);
	
	var resurl='include/projects.php?elemid=show_agents_on_map&id_town='+id_town+'&id_company='+id_company;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;   
			var data = leselect.split('@@');
			
			i = 0; var x, y;
			while(data[i] != 'end'){
				
				var elt=data[i].split('##');
				var popupContent;
			
				if((elt[1]!="")&&(elt[2]!="")) {
					popupContent = elt[0];
					var mark = L.marker([elt[1], elt[2]],{icon: agentIcon,riseOnHover:true}).bindPopup(popupContent).addTo(agent_couche); 
					offline_map.addLayer(agent_couche);
				} 
				
				x= elt[1]; y= elt[2];
				i += 1;
			}
			
			
			if(i == 1){
				offline_map.setView([x, y], 9); 
			} else
			if(i > 1){
				offline_map.fitBounds(agent_couche.getBounds());
			} else {
				offline_map.fitWorld().zoomIn();
			}
	
			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function showProjectTaskAgent(x,y,name) {
	offline_map.removeLayer(agent_couche); 
	
	if((x!="")&&(y!="")){
		var mark = L.marker([x, y], {icon: agentIcon,riseOnHover:true}).bindPopup(name).addTo(agent_couche);
		offline_map.addLayer(agent_couche);
		offline_map.setView([x, y], 14); 
	} else {
		offline_map.fitWorld().zoomIn();
	}
}


function showProjectTown_notIn(id_project){
	
	if(label_offline_notIn){ label_offline_notIn.clearLayers(); }
	if(region_mrker_couche_notIn){ region_mrker_couche_notIn.clearLayers(); }
	
	var resurl='include/projects.php?elemid=show_town_notIn_on_map&id_project='+id_project;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;    
			var data = leselect.split('@@');
			
			i = 0;
			while(data[i] != 'end'){
				var elt = data[i].split('##');
			
				if((elt[1]!="")&&(elt[2]!="")) {
					popupContent = elt[0];
					var mark = L.marker([elt[1], elt[2]],{icon: townNotInIcon,riseOnHover:true}).bindPopup(popupContent).addTo(region_mrker_couche_notIn); 
					
					var divIcon = L.divIcon({ 
						className: "labelClass_notIn",
						iconAnchor:[-15,25],
						html: elt[0]
					});

					var mark2 = L.marker([elt[1], elt[2]], {icon: divIcon }).addTo(label_offline_notIn);
					offline_map.addLayer(region_mrker_couche_notIn);
				} 
				
				i += 1;
			}
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function showProjectTown(id_task){
	
	plantation_project_points.clearLayers();
	plantation_project_couche.clearLayers();
	
	if(label_offline){ label_offline.clearLayers(); }
	region_mrker_couche.clearLayers();
	
	var resurl='include/projects.php?elemid=show_town_on_map&id_task='+id_task;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;   
			var elt = leselect.split('##');
			
			if((elt[1]!="")&&(elt[2]!="")) {
				popupContent = elt[0];
				var mark = L.marker([elt[1], elt[2]],{icon: townIcon,riseOnHover:true}).bindPopup(popupContent).addTo(region_mrker_couche); 
				
				var divIcon = L.divIcon({ 
					className: "labelClass",
					iconAnchor:[-15,25],
					html: elt[0]
				});

				var mark2 = L.marker([elt[1], elt[2]], {icon: divIcon }).addTo(label_offline);
			
				offline_map.addLayer(region_mrker_couche);
				offline_map.setView([elt[1], elt[2]], 9); 
			} else {
				offline_map.fitWorld().zoomIn();
			}

        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function showProjectTaskDetails(id_task,id_project) {
	
	$(".projectTaskShow").addClass("hide");
	$(".projectTaskEdit").removeClass("hide");
	
	$('.edit_delivery_date').datepicker({
		format: "yyyy/mm/dd",
		calendarWeeks:true,
		autoclose: true
	});
	
	$("#updateTaskStatus").prop("disabled", false);
	
	$('.i-checks').iCheck({
		checkboxClass: 'icheckbox_square-green',
		radioClass: 'iradio_square-green'
	});
	
	$('#updateTaskStatus').on('ifChanged', function(event){ updateProjectTaskDone($(event.target).val(),id_task); }); 
	
	document.getElementById('projectTaskBtnToggle').innerHTML = '<span class="pull-right"><button class="btn btn-primary" onclick="saveEdit_projectTask(\''+id_task+'\',\''+id_project+'\');"><i class="fa fa fa-save" aria-hidden="true"></i></button>'
		+'&nbsp;<button type="button" class="btn btn-danger" onclick="cancelEdit_projectTask(\''+id_task+'\',\''+id_project+'\');"><i class="fa fa-ban"></i></button></span>'
		+'<a href="javascript:deleteProjectTask(\''+id_task+'\',\''+id_project+'\');" class="btn btn-warning pull-left" style="margin-left:15px;" onclick="return confirm(\'Are you sure you want to delete this place ?\')"><i class="fa fa-trash" aria-hidden="true"></i></a>';
}


function changeAgent(conf) {
	if(conf == 1) {
		$("#projectTaskModal_agent_id").prop("disabled", false);
		document.getElementById('changeAgentBtn').innerHTML = '<button type="button" class="btn btn-danger" onclick="changeAgent(0);"><i class="fa fa-ban"></i></button>';
	} else {
		$("#projectTaskModal_agent_id").prop("disabled", true);
		document.getElementById('changeAgentBtn').innerHTML = '<button type="button" class="btn btn-primary" onclick="changeAgent(1);">'+lg_projet_change_agent+'</button>';
	}
}

function deleteProjectTask(id_task,id_project){
	var resurl='include/projects.php?elemid=delete_project_task&id_task='+id_task;    
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;     
			
			if(leselect == 1){
				toastr.success('Task successfully deleted',{timeOut:15000})
				
				var thumb = '<span style="font-size: 14px;"><i class="fa fa-hand-o-left"></i>'+lg_sel_project_in_list+'</span>';
				document.getElementById('taskDetails').innerHTML = thumb;
				projectTaskList(id_project);
				showProjectRegionMap(id_project);
			} else 
			if(leselect == 0){
				toastr.error('Task not deleted, please retry!',{timeOut:15000})
			} else {
				internal_error();
			}
			
			leselect = xhr.responseText;
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}


function updateProjectTaskDone(value,id_task) {

	if(value == 1){ var task_done = 0; } else { var task_done = 1; }
	
	var resurl='include/projects.php?elemid=update_project_task_done&id_task='+id_task+'&task_done='+task_done;    
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;       
			
			if(leselect == 1){
				toastr.success('Task status successfully saved',{timeOut:15000})
			} else 
			if(leselect == 0){
				toastr.error('Task status not saved, please retry!',{timeOut:15000})
			} else {
				internal_error();
			}
			
			leselect = xhr.responseText;
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}


function cancelEdit_projectTask(id_task,id_project){
	$(".projectTaskShow").removeClass("hide");
	$(".projectTaskEdit").addClass("hide");
	
	$("#updateTaskStatus").prop("disabled", true);
	
	document.getElementById('projectTaskBtnToggle').innerHTML = '<button class="btn btn-success pull-right" onclick="showProjectTaskDetails(\''+id_task+'\',\''+id_project+'\');"><i class="fa fa-edit" aria-hidden="true"></i></button>';
}


function saveEdit_projectTask(id_task){

	var req="";
	
	var task_titleshort=document.getElementById('projectTaskModal_task_titleshort').value;
	if(task_titleshort){ req=req+'&task_titleshort='+task_titleshort; }
	
	var task_status=document.getElementById('projectTaskModal_task_status').value;
	if(task_status){ req=req+'&task_status='+task_status; }
	
	var start_date=document.getElementById('projectTaskModal_start_date').value;
	if(start_date){ req=req+'&start_date='+start_date; }
	
	var end_date=document.getElementById('projectTaskModal_end_date').value;
	if(end_date){ req=req+'&end_date='+end_date; }
	
	var due_date=document.getElementById('projectTaskModal_due_date').value;
	if(due_date){ req=req+'&due_date='+due_date; }
	
	var task_description=document.getElementById('projectTaskModal_task_description').value;
	if(task_description){ req=req+'&task_description='+task_description; }
	
	var task_delegated_id=document.getElementById('projectTaskModal_task_delegated_id').value;
	if(task_delegated_id){ req=req+'&task_delegated_id='+task_delegated_id; }
	
	var agent_id=document.getElementById('projectTaskModal_agent_id').value;
	if(agent_id){ req=req+'&agent_id='+agent_id; }
	
	var agent_assist_id=document.getElementById('projectTaskModal_agent_assist_id').value;
	if(agent_assist_id){ req=req+'&agent_assist_id='+agent_assist_id; }
	
	
	var id_project=document.getElementById('projectTaskModal_id_project').value; 
	
	if(agent_id=="") {
		toastr.info('No agent selected',{timeOut:15000})
		
	} else {
		var resurl='include/projects.php?elemid=projectTask_management&conf=edit&id_task='+id_task+'&id_project='+id_project+req;    
		var xhr = getXhr();   // console.log(resurl);
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;   
				
				if(leselect == 1){
					toastr.success('Task successfully saved',{timeOut:15000})
					$("#tasksModal").modal("hide");
					
					projectTaskList(id_project);
					cancelEdit_projectTask(id_task,id_project);
					showProjectTaskSummary(id_task, id_project);
				
				} else 
				if(leselect == 0){
					toastr.error('Task not saved, please retry!',{timeOut:15000})
				} else {
					internal_error();
				}
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
	}
}


function delegatedAgents(value,agent_id) {
	
	if(value!=""){
		$("#projectTaskModal_agent_id").prop("disabled", false);
		$("#projectTaskModal_agent_assist_id").prop("disabled", false);
	} else {
		$("#projectTaskModal_agent_id").prop("disabled", true);
		$("#projectTaskModal_agent_assist_id").prop("disabled", true);
	}
	
	var resurl='include/projects.php?elemid=delegated_agents&id_primary_company='+value+'&agent_id='+agent_id;    
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;       
			
			document.getElementById('projectTaskModal_agent_id').innerHTML = leselect;
			document.getElementById('projectTaskModal_agent_assist_id').innerHTML = leselect;
			
			leselect = xhr.responseText;
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}


/* ----- END - PROJECT ----- */


function notes() {
	hideAll();

	$("#db_notes").removeClass("hide");
	titleMenuManag("notes","btn_notes");
}


L.control.layers(baseMaps3, overlayMaps).addTo(map3);


function agentLocation(coordx,coordy) {
	agent_couche.clearLayers();

	var mark = L.marker([coordx, coordy], {icon: agentIcon,riseOnHover:true}).addTo(agent_couche);
	map3.addLayer(agent_couche);

	map3.setView([coordx, coordy], 9);
	// map3.fitBounds(agent_couche.getBounds());
}



function roleManagement(conf,id) {

	var elmt = '';
	var cond = 0;

	if(conf == 'add'){
		var role_name = document.getElementById('Role_name').value;
		var Role_name_en = document.getElementById('Role_name_en').value;
		var Role_name_ge = document.getElementById('Role_name_ge').value;
		var Role_name_fr = document.getElementById('Role_name_fr').value;
		var Role_name_es = document.getElementById('Role_name_es').value;
		var Role_name_po = document.getElementById('Role_name_po').value;

		elmt = '&role_name='+role_name+'&Role_name_en='+Role_name_en+'&Role_name_ge='+Role_name_ge+'&Role_name_fr='+Role_name_fr+'&Role_name_es='+Role_name_es+'&Role_name_po='+Role_name_po;
		if(role_name == ''){ cond = 0; } else { cond = 1; }

	} else
	if(conf == 'show'){
		elmt = '&id_role='+id;
		cond = 1;

	} else
	if(conf == 'edit'){
		var editRole_name = document.getElementById('editRole_name').value;
		var editRole_name_en = document.getElementById('editRole_name_en').value;
		var editRole_name_ge = document.getElementById('editRole_name_ge').value;
		var editRole_name_fr = document.getElementById('editRole_name_fr').value;
		var editRole_name_es = document.getElementById('editRole_name_es').value;
		var editRole_name_po = document.getElementById('editRole_name_po').value;
		var editRole_id = document.getElementById('editRole_id').value;

		elmt = '&editRole_name='+editRole_name+'&editRole_name_en='+editRole_name_en+'&editRole_name_ge='+editRole_name_ge+'&editRole_name_fr='+editRole_name_fr+'&editRole_name_es='+editRole_name_es+'&editRole_name_po='+editRole_name_po+'&editRole_id='+editRole_id;
		if((editRole_name == '')&&(editRole_id == '')){ cond = 0; } else { cond = 1; }

	} else
	if(conf == 'del'){
		elmt = '&id_role='+id;
		cond = 1;

	} else {}

	if(cond == 1){
		var resurl='listeslies.php?elemid=roles_configurations&conf='+conf+elmt;   
		var xhr = getXhr();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;     

				if(conf == 'add'){
					if(leselect == 1){
						toastr.success('Role added successfully',{timeOut:15000})
						roles_def();
					} else {
						toastr.error('Error adding role, please retry',{timeOut:15000})
					}

				} else
				if(conf == 'show'){
					var val = leselect.split('#');

					document.getElementById('editRole_name').value = val[0];
					document.getElementById('editRole_name_en').value = val[1];
					document.getElementById('editRole_name_ge').value = val[2];
					document.getElementById('editRole_name_fr').value = val[3];
					document.getElementById('editRole_name_es').value = val[4];
					document.getElementById('editRole_name_po').value = val[5];
					document.getElementById('editRole_id').value = val[6];

					document.getElementById('editRoleModalLabel').innerHTML = val[0];

				} else
				if(conf == 'edit'){
					if(leselect == 1){
						toastr.success('Role edited successfully',{timeOut:15000})
						$("#editRolemodal").modal("hide");
						roles_def();
					} else {
						toastr.error('Error editing role, please retry',{timeOut:15000})
					}

				} else
				if(conf == 'del'){
					if(leselect == 1){
						toastr.success('Role deleted successfully',{timeOut:15000})
						roles_def();
					} else {
						toastr.error('Error deleting role, please retry',{timeOut:15000})
					}

				} else {}

				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);

	} else {
		if((conf == 'add')||(conf == 'edit')){
			toastr.error('Role name field is empty!',{timeOut:15000})
		}
	}

}


function addUserToRole(id_user) {

	var id_role = '';

	if($("input[type='radio'].radioBtnClass").is(':checked')) {
		id_role = $("input[type='radio'].radioBtnClass:checked").val();
	}

	if(id_role == ""){
		toastr.info('Select a role.',{timeOut:15000})

	} else {
		var resurl='listeslies.php?elemid=add_user_to_role&id_role='+id_role+'&id_user='+id_user;
		var xhr = getXhr();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;

				if(leselect == 1){
					toastr.success('User added successfully',{timeOut:15000})
					userInRole(id_role);
				} else 
				if(leselect == 0){
					toastr.error('Error adding user to role, please retry',{timeOut:15000})
				} else {
					internal_error();
				}

				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
	}
}


function addObjectToRole(id_object,name_object) {

	var id_role = '';

	if($("input[type='radio'].radioBtnDefClass").is(':checked')) {
		id_role = $("input[type='radio'].radioBtnDefClass:checked").val();
	}

	if(id_role == ""){
		toastr.info('Select a role.',{timeOut:15000})

	} else {
		var resurl='listeslies.php?elemid=add_object_to_role&id_role='+id_role+'&id_object='+id_object+'&name='+name_object;
		var xhr = getXhr();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;

				if(leselect == 1){
					toastr.success('User added successfully',{timeOut:15000})
					objectInRole(id_role);
				} else 
				if(leselect == 0){
					toastr.error('Error adding object to role, please retry',{timeOut:15000})
				} else {
					internal_error();
				}

				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
	}
}


function removeUserFromRole(id_role,id_user) {

	var resurl='listeslies.php?elemid=remove_user_from_role&id_role='+id_role+'&id_user='+id_user;
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;

			if(leselect == 1){
				toastr.success('User removed successfully',{timeOut:15000})
				userInRole(id_role);
			} else 
			if(leselect == 0){
				toastr.error('Error removing user from role, please retry',{timeOut:15000})
			} else {
				internal_error();
			}

			leselect = xhr.responseText;
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}


function removeObjectFromRole(id_role,id_object) {

	var resurl='listeslies.php?elemid=remove_object_from_role&id_role='+id_role+'&id_object='+id_object;
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;

			if(leselect == 1){
				toastr.success('Object removed successfully',{timeOut:15000})
				objectInRole(id_role);
			} else 
			if(leselect == 0){
				toastr.error('Error removing object from role, please retry',{timeOut:15000})
			} else {
				internal_error();
			}

			leselect = xhr.responseText;
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}


function addNewObject() {

	var menu_name = document.getElementById('object_menu_name').value;

	if(menu_name ==""){
		document.getElementById("alert-new-object-su").style.display = "none";
		document.getElementById("alert-new-object-er").style.display = "block";
		document.getElementById("alert-new-object-er").innerHTML = "Enter a menu name";

	} else {

		var resurl='listeslies.php?elemid=add_new_object&name='+menu_name;  
		var xhr = getXhr();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;  
 
				if(leselect == 1){
					document.getElementById("alert-new-object-er").style.display = "none";
					document.getElementById("alert-new-object-su").style.display = "block";
					document.getElementById("alert-new-object-su").innerHTML = "Object created successfully";
					document.getElementById('object_menu_name').value = "";
		
				} else {
					document.getElementById("alert-new-object-su").style.display = "none";
					document.getElementById("alert-new-object-er").style.display = "block";
					document.getElementById("alert-new-object-er").innerHTML = "Error creating object";
				}

				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
	}
	
	setTimeout(function(){
        $('#alert-new-object-er').hide();
        $('#alert-new-object-su').hide();
		$("#newObject").modal("hide");
    },3000);
}


function port_costs() {
	hideAll();
	
	var req='';
	
	$("#db_port_costs").removeClass("hide");
	titleMenuManag("Port costs assignement","btn_crm_pcosts");
	
	if(sysPort_create == 1){ $("#costAsignNewPort").removeClass("hide"); } else { $("#costAsignNewPort").addClass("hide"); }
	if(sysPortCost_create == 1){ $("#costAsignNewCost").removeClass("hide"); } else { $("#costAsignNewCost").addClass("hide"); }
	
	if(sysPortCostAssign_update ==1){ 
		$("#ptCostEditRight").removeClass("hide"); 
		$("#ptCostEditLeft").removeClass("hide");
	} else { 
		$("#ptCostEditRight").addClass("hide"); 
		$("#ptCostEditLeft").addClass("hide");
	}  
	
	if((sysPort_update==0)&&(sysPort_delete==0)){
		$("#prtCostPtList").addClass("hide"); 
	} else { $("#prtCostPtList").removeClass("hide"); }
	
	req='&update_port='+sysPort_update+'&delete_port='+sysPort_delete+'&update_cost='+sysPortCost_update+'&delete_cost='+sysPortCost_delete+'&assign_port='+sysPortCostAssign_update;
	
	var resurl='listeslies.php?elemid=port_costs'+req;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText; 
			var val = leselect.split('##');
			
			document.getElementById('list_ports').innerHTML = val[0];  
			document.getElementById('regCostList').innerHTML = val[1];  

			$('#posrtListTable tr').bind('click', function(e) {
				$('#posrtListTable tr td').css({ 'background-color' : '#FFF'});
				$(e.currentTarget).children('td, th').css('background-color','#f3f3f4');
			})

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function roles_def() {
	hideAll();

	$("#db_roles_def").removeClass("hide");
	titleMenuManag("Role definition","btn_role_def");

	if(sysRoleDef_create == 1){
		$("#newRoleForm_RoleDef").removeClass("hide");
		$("#newObject_RoleDef").removeClass("hide");
	} else { 
		$("#newRoleForm_RoleDef").addClass("hide"); 
		$("#newObject_RoleDef").addClass("hide"); 
	}
	
	var resurl='listeslies.php?elemid=roles_definition&createRight='+sysRoleDef_create+'&deleteRight='+sysRoleDef_delete+'&editRight='+sysRoleDef_update;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;  
			var val = leselect.split('##');

			document.getElementById('list_rolesDef').innerHTML = val[0];  
			document.getElementById('list_objectR').innerHTML = val[1];

			$('#rolestable_def tr').bind('click', function(e) {
				$('#rolestable_def tr td').css({ 'background-color' : '#FFF'});
				$(e.currentTarget).children('td, th').css('background-color','#f3f3f4');
			})

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function roles_ass() {
	hideAll();

	$("#db_roles_ass").removeClass("hide");
	titleMenuManag("Role assignement","btn_role_ass");
	
	var resurl='listeslies.php?elemid=roles_management&createRight='+sysRoleAssign_create+'&editRight='+sysRoleAssign_update+'&deleteRight='+sysRoleAssign_delete;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;
			var val = leselect.split('##');

			document.getElementById('list_roles').innerHTML = val[0];
			document.getElementById('list_usersR').innerHTML = val[1];

			$('#rolestable tr').bind('click', function(e) {
				$('#rolestable tr td').css({ 'background-color' : '#FFF'});
				$(e.currentTarget).children('td, th').css('background-color','#f3f3f4');
			})
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function filterUserRA() {
  // Declare variables
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("sysRlAssUerList");
  filter = input.value.toUpperCase();
  table = document.getElementById("sysRlAssUerList_table");
  tr = table.getElementsByTagName("tr");

  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[2];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}


function permissions() {
	hideAll();

	$("#db_permissions").removeClass("hide");
	titleMenuManag("Role Permission","btn_role_perm");
}


function crm_manag2(pipeline_sched_id) {
	hideAll();
	
	$("#db_crm_manag2").removeClass("hide");
	$("#crmSpanner2").removeClass("hide");
	
	titleMenuManag("CRM","btn_crm2");

	document.getElementById('summaryDocs2').innerHTML = '';
	document.getElementById('requestDocs2').innerHTML = '';

	var resurl='listeslies.php?elemid=order_reference_nr2&pipeline_sched_id='+pipeline_sched_id;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;
			var val = leselect.split('??');

			document.getElementById('order_refn_list2').innerHTML = val[0];

			var options = {
				valueNames: ['reference_nr2']
			};

			var userList = new List('order_reference_nembers2', options);

			$("#crmSpanner2").addClass("hide");
			
			$('#order_refn_list2 li a').click(function() {
				$('ul li.on').removeClass('on');
				$(this).closest('li').addClass('on');
			});

			leselect = xhr.responseText;
        }
    };

	$("#crm_request_freight_tab2").removeClass("hide"); 
	$("#crm_request_freight_ct2").removeClass("hide");
	
	
    xhr.open("GET",resurl,true);
    xhr.send(null);
}

function crmSchedulePipelineFilter(value) {
	crm_manag2(value);
}

function crm_manag(id_order,pipeline_id) {
	hideAll();
	
	$("#db_crm_manag").removeClass("hide");
	$("#crmSpanner").removeClass("hide");
	
	titleMenuManag("CRM","btn_crm");
	
	var mail = '<a href="#" class="pull-right" style="margin-left:10px; color:#fff;"  onclick="eMailForm(\''+id_order+'\',\'crm\',\'\');"><i class="fa fa-envelope"></i></a>';
	
	document.getElementById('summaryDocs').innerHTML = mail;
	document.getElementById('requestDocs').innerHTML = '';

	var resurl='listeslies.php?elemid=order_reference_nr&id_ord_order='+id_order+'&pipeline_id='+pipeline_id;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;
			var val = leselect.split('??');

			document.getElementById('order_refn_list').innerHTML = val[0];

			var options = {
				valueNames: ['reference_nr']
			};

			var userList = new List('order_reference_nembers', options);

			$("#crmSpanner").addClass("hide");
			
			$('#order_refn_list li a').click(function() {
				$('ul li.on').removeClass('on');
				$(this).closest('li').addClass('on');
			});

			leselect = xhr.responseText;
        }
    };

	$("#crm_request_freight_tab").removeClass("hide"); $("#crm_request_freight_ct").removeClass("hide");
	
	
    xhr.open("GET",resurl,true);
    xhr.send(null);
}

function crmContractPipelineFilter(value) {
	crm_manag(0,value);
}

function getProductDetails() { 
	var id_code = document.getElementById('id_product').value; 
	var productid_code = id_code.split(',');
	
	var package_type = document.getElementById("package_type").value;
	
	var id_product = productid_code[0];
	if(id_product==41){ 
		document.getElementById("weight_unit").value = 16; 
	} else {
		if(package_type == 269){
			document.getElementById("weight_unit").value = 22;
		} else {
			document.getElementById("weight_unit").value = 21.5;
		}
	}
	
	var resurl='listeslies.php?elemid=product_details&id_product='+id_product;  
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;   
			var val = leselect.split('##'); 
			
			document.getElementById('pdt_cas').innerHTML = 'CAS : '+val[0];
			document.getElementById('pdt_hs').innerHTML = 'HS : '+val[1];

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function schedule_product_qty(id_ord_schedule,weight_container) { 
	var nr_containers = document.getElementById('qty-'+id_ord_schedule).value; 
	var resurl='listeslies.php?elemid=edit_schedule_quantity&id_ord_schedule='+id_ord_schedule+'&nr_containers='+nr_containers+'&weight_container='+weight_container;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;
			var val = leselect.split('##');
			
			if(val[0] == 1){
				toastr.success(val[1],{timeOut:15000})
			} else 
			if(val[0] == 0){
				toastr.error(val[1],{timeOut:15000})
			} else {
				internal_error();
			}

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function schedule_date(id_ord_schedule,type) {
	var month = document.getElementById('date-'+id_ord_schedule).value; 
	
	var kk = $("#date-"+id_ord_schedule).val(); 
	var week = moment(kk, "YYYY/MM/DD").week(); 
	document.getElementById('sch_week_'+id_ord_schedule).innerHTML = week; 
	
	if(type == "ETA"){
		var req='&month_eta='+month+'&week_eta='+week;
	} else {
		var req='&month_etd='+month+'&week_etd='+week;
	}
	
	var resurl='listeslies.php?elemid=edit_schedule_date&id_ord_schedule='+id_ord_schedule+'&type='+type+req;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;  
			var val = leselect.split('##');
			
			if(val[0] == 1){
				toastr.success(val[1],{timeOut:15000})
			} else 
			if(val[0] == 0){
				toastr.error(val[1],{timeOut:15000})
			} else {
				internal_error();
			}

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function editImpPerson() {
	var ord_infos = document.getElementById('ord_imp_person_id').value;
	var data = ord_infos.split("#");
	
	var ord_imp_person_id = data[1];
	var id_ord_order = data[0];
	
	var resurl='listeslies.php?elemid=update_importer_person&id_ord_order='+id_ord_order+'&ord_imp_person_id='+ord_imp_person_id;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
           leselect = xhr.responseText;
			var val = leselect.split('##');
			
			if(val[0] == 1){
				toastr.success(val[1],{timeOut:15000})
			} else 
			if(val[0] == 0){
				toastr.error(val[1],{timeOut:15000})
			} else {
				internal_error();
			}

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function editOrderSmManager() {
	var ord_infos = document.getElementById('sm_person_id').value;
	var data = ord_infos.split("#");
	
	var sm_person_id = data[1];
	var id_ord_order = data[0];
	
	var resurl='listeslies.php?elemid=update_sm_person&id_ord_order='+id_ord_order+'&sm_person_id='+sm_person_id;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
           leselect = xhr.responseText;
			var val = leselect.split('##');
			
			if(val[0] == 1){
				toastr.success(val[1],{timeOut:15000})
				sendSM_ManagerMail(id_ord_order);
			} else 
			if(val[0] == 0){
				toastr.error(val[1],{timeOut:15000})
			} else {
				internal_error();
			}

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function sendSM_ManagerMail(ord_order_id) {
	var resurl='listeslies.php?elemid=sm_manager_mail&ord_order_id='+ord_order_id;    
    var xhr = getXhr();  
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;  
			var val = leselect.split('##');
			
			if(val[0]==1){
				toastr.success('Mail successfully sent',{timeOut:15000})
				
				var bcc=""; 
				saveMailAsPdf(val[1],val[2],val[3],'system',val[5],val[6],'SM_mail',val[5],bcc,ord_order_id);
				
			} else 
			if(val[0]==0){
				toastr.error('Error sending mail, please retry',{timeOut:15000})
			} else {
				internal_error();
			}
			
			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function editOrderPipeline() {
	var ord_infos = document.getElementById('pipeline_id').value;
	var data = ord_infos.split("#");
	
	var pipeline_id = data[1];
	var id_ord_order = data[0];
	
	var resurl='listeslies.php?elemid=update_pipeline&id_ord_order='+id_ord_order+'&pipeline_id='+pipeline_id;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
           leselect = xhr.responseText;
			var val = leselect.split('##');
			
			if(val[0] == 1){
				toastr.success(val[1],{timeOut:15000})
			} else 
			if(val[0] == 0){
				toastr.error(val[1],{timeOut:15000})
			} else {
				internal_error();
			}

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}



// function editOrderStatus() {
	// var ord_infos = document.getElementById('status_id').value;
	// var data = ord_infos.split("#");
	
	// var status_id = data[1];
	// var id_ord_order = data[0];
	
	// var resurl='listeslies.php?elemid=update_order_status&id_ord_order='+id_ord_order+'&status_id='+status_id;
    // var xhr = getXhr();
	// xhr.onreadystatechange = function(){
        // if(xhr.readyState == 4 ){
           // leselect = xhr.responseText;
			// var val = leselect.split('##');
			
			// if(val[0] == 1){
				// toastr.success(val[1],{timeOut:15000})
			// } else {
				// toastr.error(val[1],{timeOut:15000})
			// }

			// leselect = xhr.responseText;
        // }
    // };

    // xhr.open("GET",resurl,true);
    // xhr.send(null);
// }


/* 
*
* Port cost management
*
*/


/* Port cost assignement */

function assignPortCost() {
	
	document.getElementById("sysPortCostForm").reset();
	
	$('.edit_delivery_date').datepicker({
		format: "yyyy/mm/dd",
		calendarWeeks:true,
		autoclose: true
	});
	
	document.getElementById("portCostModalFooter").innerHTML = '<button type="button" class="btn btn-success" onclick="newSysPortCost(\'add\');"><i class="fa fa-save"></i></button>'
		+'<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i></button>';
}


/* Port cost currency selector */

function PtCostCurrSel(currency) {
	if(currency == 277){
		document.getElementById("cost_eur").disabled = true;
		document.getElementById("cost_usd").disabled = false;
		document.getElementById("cost_chf").disabled = true;
	} else 
	if(currency == 278){
		document.getElementById("cost_eur").disabled = false;
		document.getElementById("cost_usd").disabled = true;
		document.getElementById("cost_chf").disabled = true;
	} else
	if(currency == 279){
		document.getElementById("cost_eur").disabled = true;
		document.getElementById("cost_usd").disabled = true;
		document.getElementById("cost_chf").disabled = false;
	} else {
		document.getElementById("cost_eur").disabled = true;
		document.getElementById("cost_usd").disabled = true;
		document.getElementById("cost_chf").disabled = true;
	}
}

/* Adding system port cost */

function newSysPortCost(conf){
	
	var req='';
	
	var item_name = document.getElementById("item_name").value;
	if(item_name){ req=req+'&item_name='+item_name; }
	
	var measure_unit_id = document.getElementById("measure_unit_id").value;
	if(measure_unit_id){ req=req+'&measure_unit_id='+measure_unit_id; }
	
	var active = document.getElementById("active").value;
	if(active){ req=req+'&active='+active; }
	
	var validity_date = document.getElementById("validity_date").value;
	if(validity_date){ req=req+'&validity_date='+validity_date; }
	
	var sequence_nr = document.getElementById("sequence_nr").value;
	if(sequence_nr){ req=req+'&sequence_nr='+sequence_nr; }
	
	var currency_id = document.getElementById("currency_id").value;
	if(currency_id){ req=req+'&currency_id='+currency_id; }
	
	var cost_eur = document.getElementById("cost_eur").value;
	if(cost_eur){ req=req+'&cost_eur='+cost_eur; }
	
	var cost_usd = document.getElementById("cost_usd").value;
	if(cost_usd){ req=req+'&cost_usd='+cost_usd; }
	
	var cost_chf = document.getElementById("cost_chf").value;
	if(cost_chf){ req=req+'&cost_chf='+cost_chf; }
	
	var calculation_method = document.getElementById("calculation_method").value;
	if(calculation_method){ req=req+'&calculation_method='+calculation_method; }
	
	var id_reg_cost = document.getElementById("syst_port_cost_id_reg_cost").value;
	if(id_reg_cost){ req=req+'&id_reg_cost='+id_reg_cost; }

	var resurl='listeslies.php?elemid=manag_system_port_cost&conf='+conf+req;    
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;    
			
			if(leselect == 1){
				toastr.success('Port cost successfully added',{timeOut:15000})
				port_costs_tbl();
				
			} else 
			if(leselect == 0){
				toastr.error('Port cost not added, please retry!',{timeOut:15000})
			} else {
				internal_error();
			}
			
			leselect = xhr.responseText;
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}

/* Delete and show port cost */

function showDelSysPortCost(conf,id_reg_cost) { 
	
	if(conf == 'del'){
		
		var resurl='listeslies.php?elemid=delete_system_port_cost&id_reg_cost='+id_reg_cost;    
		var xhr = getXhr();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;         
				
				if(leselect == 1){
					toastr.success('Port cost successfully deleted',{timeOut:15000})
					port_costs();
					
				} else 
				if(leselect == 0){
					toastr.error('Port cost not deleted, please retry!',{timeOut:15000})
				} else {
					internal_error();
				}
				
				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
	
	} else
	if(conf == 'show'){
		
		document.getElementById("sysPortCostForm").reset();
		
		var resurl='listeslies.php?elemid=show_system_port_cost&id_reg_cost='+id_reg_cost;    
		var xhr = getXhr();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;          
				var val = leselect.split('#');
				
				document.getElementById("item_name").value = val[0];
				document.getElementById("measure_unit_id").value = val[1];
				document.getElementById("active").value = val[2];
				document.getElementById("validity_date").value = val[3];
				document.getElementById("sequence_nr").value = val[4];
				document.getElementById("currency_id").value = val[5];
				document.getElementById("cost_eur").value = val[6];
				document.getElementById("cost_usd").value = val[7];
				document.getElementById("cost_chf").value = val[8];
				document.getElementById("calculation_method").value = val[9];
				
				document.getElementById("syst_port_cost_id_reg_cost").value = val[10];
				
				if(val[5]!=""){ PtCostCurrSel(val[5]); }
				
				document.getElementById("portCostModalFooter").innerHTML = '<button type="button" class="btn btn-success" onclick="newSysPortCost(\'edit\');"><i class="fa fa-save"></i></button>'
					+'<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i></button>';

				$('.edit_delivery_date').datepicker({
					format: "yyyy/mm/dd",
					calendarWeeks:true,
					autoclose: true
				});
			
				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
		
	} else {}
}


/*  Show port cost table */

function port_costs_tbl() {
	hideAll();
	
	$("#db_port_costs_table").removeClass("hide");
	titleMenuManag("Port of cost table","btn_crm_pcosts_tbl");
	
	if(sysPortCost_create == 1){ $("#costTableNewCost").removeClass("hide"); } else { $("#costTableNewCost").addClass("hide"); }

	var resurl='listeslies.php?elemid=port_costs_table&update_right='+sysPortCost_update+'&delete_right='+sysPortCost_delete;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;

			document.getElementById('listSysPortCost').innerHTML = leselect;

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


/* 
*
* Port management
*
*/

function portManagement(conf,id_townport,val) {

	if(conf == 'add'){
		/* Add new port */
		
		var req = '';
		
		var portname = document.getElementById("portname").value;
		if(portname){ req=req+'&portname='+portname; }
		
		var port_type_id = document.getElementById("port_type_id").value;
		if(port_type_id){ req=req+'&port_type_id='+port_type_id; }
		
		var port_code = document.getElementById("port_code").value;
		if(port_code){ req=req+'&port_code='+port_code; }
		
		var qm_org_contact_id = document.getElementById("qm_org_contact_id").value;
		if(qm_org_contact_id){ req=req+'&qm_org_contact_id='+qm_org_contact_id; }
		
		var transit_days = document.getElementById("transit_days").value;
		if(transit_days){ req=req+'&transit_days='+transit_days; }
		
		var town_id = document.getElementById("town_id").value;
		if(town_id){ req=req+'&town_id='+town_id; }
		
		var onward_delay = document.getElementById("onward_delay").value;
		if(onward_delay){ req=req+'&onward_delay='+onward_delay; }
		
		if(portname == ""){
			alert("Enter port name.");
			
		} else 
		if(port_type_id == ""){
			alert("Select a port type.");
			
		} else 
		if(town_id == ""){
			alert("Select a town.");
			
		} else {
			var resurl='listeslies.php?elemid=manag_system_port&conf='+conf+'&id_townport='+id_townport+req;    
			var xhr = getXhr();
			xhr.onreadystatechange = function(){
				if(xhr.readyState == 4 ){
					leselect = xhr.responseText;         
					
					if(leselect == 1){
						toastr.success('Port successfully added',{timeOut:15000})
						$("#modalPort").modal("hide");
						port_table();
					
					} else 
					if(leselect == 0){
						toastr.error('Port not added, please retry!',{timeOut:15000})
					} else {
						internal_error();
					}
					
					leselect = xhr.responseText;
				}
			};

			xhr.open("GET",resurl,true);
			xhr.send(null);
		}

	} else
	if(conf == 'show'){
		if(val== 'create'){
			document.getElementById("systPortForm").reset();
			
			/* Save button */
			document.getElementById('portModalLabel').innerHTML = "Create new port";
			document.getElementById('portModalFooter').innerHTML ='<button type="button" class="btn btn-success" onclick="portManagement(\'add\',\'\',\'\');"><i class="fa fa-save"></i></button>'
				+'<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i></button>';
		} else
		if(val== 'mod'){ 
			
			document.getElementById("systPortForm").reset();
			
			var resurl='listeslies.php?elemid=show_system_port&id_townport='+id_townport;   
			var xhr = getXhr();
			xhr.onreadystatechange = function(){
				if(xhr.readyState == 4 ){
					leselect = xhr.responseText;          
					var val = leselect.split('#');
					
					document.getElementById("portname").value = val[0];
					document.getElementById("port_type_id").value = val[1];
					document.getElementById("port_code").value = val[2];
					document.getElementById("qm_org_contact_id").value = val[3];
					document.getElementById("transit_days").value = val[4];
					document.getElementById("town_id").value = val[5];
					document.getElementById("onward_delay").value = val[6];
					
					document.getElementById('id_townport').value = id_townport;
				
					leselect = xhr.responseText;
				}
			};

			xhr.open("GET",resurl,true);
			xhr.send(null);
	
			
			/* Edit button */
			document.getElementById('portModalLabel').innerHTML = "Edit port";
			document.getElementById('portModalFooter').innerHTML ='<button type="button" class="btn btn-success" onclick="portManagement(\'edit\',\''+id_townport+'\',\'\');"><i class="fa fa-save"></i></button>'
					+'<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i></button>';
		} else {}
		
	} else
	if(conf == 'edit'){	 
		
		/* Edit port */
		
		var req = '';
		
		var portname = document.getElementById("portname").value;
		if(portname){ req=req+'&portname='+portname; }
		
		var port_type_id = document.getElementById("port_type_id").value;
		if(port_type_id){ req=req+'&port_type_id='+port_type_id; }
		
		var port_code = document.getElementById("port_code").value;
		if(port_code){ req=req+'&port_code='+port_code; }
		
		var qm_org_contact_id = document.getElementById("qm_org_contact_id").value;
		if(qm_org_contact_id){ req=req+'&qm_org_contact_id='+qm_org_contact_id; }
		
		var transit_days = document.getElementById("transit_days").value;
		if(transit_days){ req=req+'&transit_days='+transit_days; }
		
		var town_id = document.getElementById("town_id").value;
		if(town_id){ req=req+'&town_id='+town_id; }
		
		var onward_delay = document.getElementById("onward_delay").value;
		if(onward_delay){ req=req+'&onward_delay='+onward_delay; }
		
		var id_townport = document.getElementById("id_townport").value;
		if(id_townport){ req=req+'&id_townport='+id_townport; }
		
		
		var resurl='listeslies.php?elemid=manag_system_port&conf='+conf+'&id_townport='+id_townport+req;    
		var xhr = getXhr();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;         
				
				if(leselect == 1){
					toastr.success('Port successfully saved',{timeOut:15000})
					$("#modalPort").modal("hide");
					port_table();
					
				} else 
				if(leselect == 0){
					toastr.error('Unable to save port, please retry!',{timeOut:15000})
				} else {
					internal_error();
				}
				
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
		
	} else
	if(conf == 'del'){
		
		/* Delete port */
		
		var resurl='listeslies.php?elemid=delete_system_port&id_townport='+id_townport;    
		var xhr = getXhr();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;         
				
				if(leselect == 1){
					toastr.success('Port successfully deleted',{timeOut:15000})
					port_table();
				} else 
				if(leselect == 0){
					toastr.error('Port not deleted, please retry!',{timeOut:15000})
				} else {
					internal_error();
				}
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
		
	} else {}
}


/*  Show port */

function port_table() { 
	hideAll();
	
	$("#db_port").removeClass("hide");
	titleMenuManag("Port","btn_crm_port");
	
	if(sysPort_create == 1){ $("#createPortBtn").removeClass("hide"); } else { $("#createPortBtn").addClass("hide"); }

	var resurl='listeslies.php?elemid=port_table&update_right='+sysPort_update+'&delete_right='+sysPort_delete;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;

			document.getElementById('listSysPort').innerHTML = leselect;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}



/* 
*
* Town management
*
*/

L.control.layers(baseMaps_town_modal, overlayMaps).addTo(town_modal_map);

function townManagement(conf,gid_town,val) {
 
	if(conf == 'add'){
		/* Add new port */
		
		var req = '';
		
		var id_country = document.getElementById("syst_country_Id").value;
		if(id_country){ req=req+'&id_country='+id_country; }
		
		var name_country = document.getElementById("syst_country_name").value;
		if(name_country){ req=req+'&name_country='+name_country; }

		var name_town = document.getElementById("syst_name_town").value;
		if(name_town){ req=req+'&name_town='+name_town; }
		
		var code_town = document.getElementById("syst_code_town").value;
		if(code_town){ req=req+'&code_town='+code_town; }
		
		var x = document.getElementById("syst_x").value;
		if(x){ req=req+'&x='+x; }
		
		var y = document.getElementById("syst_y").value;
		if(y){ req=req+'&y='+y; }
		
		var timezone = document.getElementById("syst_timezone").value;
		if(timezone){ req=req+'&timezone='+timezone; }
		
		var population = document.getElementById("syst_population").value;
		if(population){ req=req+'&population='+population; }
		
		var description_en = document.getElementById("syst_description_en").value;
		if(description_en){ req=req+'&description_en='+description_en; }
		
		var description_de = document.getElementById("syst_description_de").value;
		if(description_de){ req=req+'&description_de='+description_de; }
		
		var description_fr = document.getElementById("syst_description_fr").value;
		if(description_fr){ req=req+'&description_fr='+description_fr; }
		
		var description_pt = document.getElementById("syst_description_pt").value;
		if(description_pt){ req=req+'&description_pt='+description_pt; }
		
		var description_es = document.getElementById("syst_description_es").value;
		if(description_es){ req=req+'&description_es='+description_es; }
		
		var region1 = document.getElementById("syst_region1").value;
		if(region1){ req=req+'&region1='+region1; }
		
		var region2 = document.getElementById("syst_region2").value;
		if(region2){ req=req+'&region2='+region2; }
		
		var region3 = document.getElementById("syst_region3").value;
		if(region3){ req=req+'&region3='+region3; }
		
		var region4 = document.getElementById("syst_region4").value;
		if(region4){ req=req+'&region4='+region4; }
		
		var iso = document.getElementById("syst_iso").value;
		if(iso){ req=req+'&iso='+iso; }
		
		var language = document.getElementById("syst_language").value;
		if(language){ req=req+'&language='+language; }
		
		var postcode = document.getElementById("syst_postcode").value;
		if(postcode){ req=req+'&postcode='+postcode; }
		
		var suburb = document.getElementById("syst_suburb").value;
		if(suburb){ req=req+'&suburb='+suburb; }
		
		var utc = document.getElementById("syst_utc").value;
		if(utc){ req=req+'&utc='+utc; }
		
		var dst = document.getElementById("syst_dst").value;
		if(dst){ req=req+'&dst='+dst; }
		
		
		var resurl='listeslies.php?elemid=manag_system_town&conf='+conf+'&gid_town='+gid_town+req;    
		var xhr = getXhr();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;   
				
				if(leselect == 1){
					toastr.success('Town successfully added',{timeOut:15000})
					$("#modalTown").modal("hide");
					syst_town();
					
				} else 
				if(leselect == 0){
					toastr.error('Town not added, please retry!',{timeOut:15000})
				} else {
					internal_error();
				}
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
	
	} else
	if(conf == 'show'){
		if(val== 'create'){

			setTimeout(function() {
				town_modal_map.invalidateSize(); 
			}, 1000);
  
			town_modal_map.fitWorld().zoomIn();
	
			document.getElementById("systTownForm").reset();
			
			var resurl='listeslies.php?elemid=town_country&id_country=0';    
			var xhr = getXhr();
			xhr.onreadystatechange = function(){
				if(xhr.readyState == 4 ){
					leselect = xhr.responseText;         
					
					document.getElementById("syst_country_Id").innerHTML = leselect;
				}
			};

			xhr.open("GET",resurl,true);
			xhr.send(null);
			
			town_modal_map.on('click', function(e) {        
				var popLocation= e.latlng;
				var popup = L.popup()
				.setLatLng(popLocation)
				.setContent('<p><b>x = </b>'+popLocation.lat+'<br /><b>y = </b>'+popLocation.lng+'</p>')
				.openOn(town_modal_map);   

				document.getElementById("syst_x").value = popLocation.lat;
				document.getElementById("syst_y").value = popLocation.lng;
			});

			/* Save button */
			document.getElementById('townModalLabel').innerHTML = "Create new town";
			document.getElementById('townModalFooter').innerHTML ='<button type="button" class="btn btn-success" onclick="townManagement(\'add\',\'\',\'\');"><i class="fa fa-save"></i></button>'
					+'<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i></button>';
		} else
		if(val== 'mod'){
			
			document.getElementById("systTownForm").reset();
			
			var resurl2='listeslies.php?elemid=town_country&id_country=0';    
			var xhr2 = getXhr();
			xhr2.onreadystatechange = function(){
				if(xhr2.readyState == 4 ){
					leselect2 = xhr2.responseText;         
	
					document.getElementById("syst_country_Id").innerHTML = leselect2;
				}
			};

			xhr2.open("GET",resurl2,true);
			xhr2.send(null);
	
			setTimeout(function() {
				town_modal_map.invalidateSize(); 
			}, 1000);
			
			
			var resurl='listeslies.php?elemid=show_system_town&gid_town='+gid_town;   
			var xhr = getXhr();
			xhr.onreadystatechange = function(){
				if(xhr.readyState == 4 ){
					leselect = xhr.responseText;          
					var val = leselect.split('#');
					
					document.getElementById("syst_name_town").value = val[0];
					document.getElementById("syst_x").value = val[2];
					document.getElementById("syst_y").value = val[3];
					document.getElementById("syst_country_Id").value = val[4];
					document.getElementById("syst_country_name").value = val[1];
					document.getElementById("syst_timezone").value = val[5];
					document.getElementById("syst_population").value = val[6];
					document.getElementById("syst_description_en").value = val[7];
					document.getElementById("syst_description_de").value = val[8];
					document.getElementById("syst_description_fr").value = val[9];
					document.getElementById("syst_description_pt").value = val[10];
					document.getElementById("syst_description_es").value = val[11];
					document.getElementById("syst_code_town").value = val[12];
					document.getElementById("syst_region1").value = val[13];
					document.getElementById("syst_region2").value = val[14];
					document.getElementById("syst_region3").value = val[15];
					document.getElementById("syst_region4").value = val[16];
					document.getElementById("syst_iso").value = val[17];
					document.getElementById("syst_language").value = val[18];
					document.getElementById("syst_postcode").value = val[19];
					document.getElementById("syst_suburb").value = val[20];
					document.getElementById("syst_utc").value = val[21];
					document.getElementById("syst_dst").value = val[22];
					
					
					document.getElementById('syst_gid_town').value = gid_town;
				
					if((val[2]!="")&&(val[3]!="")){
						var popLocation= new L.LatLng(val[2],val[3]);
						var popup = L.popup()
							.setLatLng(popLocation)
							.setContent('<p><b>x = </b>'+val[2]+'<br /><b>y = </b>'+val[3]+'</p>')
							.openOn(town_modal_map);
							
						town_modal_map.setView([val[2], val[3]], 13);
						
					} else {
						town_modal_map.fitWorld().zoomIn();
					}
					
					town_modal_map.on('click', function(e) {        
						var popLocation= e.latlng;
						var popup = L.popup()
						.setLatLng(popLocation)
						.setContent('<p><b>x = </b>'+popLocation.lat+'<br /><b>y = </b>'+popLocation.lng+'</p>')
						.openOn(town_modal_map);   

						document.getElementById("syst_x").value = popLocation.lat;
						document.getElementById("syst_y").value = popLocation.lng;
					});
				}
			};

			xhr.open("GET",resurl,true);
			xhr.send(null);
	
			
			/* Edit button */
			document.getElementById('townModalLabel').innerHTML = "Edit town";
			document.getElementById('townModalFooter').innerHTML ='<button type="button" class="btn btn-success" onclick="townManagement(\'edit\',\''+gid_town+'\',\'\');"><i class="fa fa-save"></i></button>'
					+'<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i></button>';
		} else {}
		
	} else
	if(conf == 'edit'){	 
		
		/* Edit town */
		
		var req = '';
		
		var id_country = document.getElementById("syst_country_Id").value;
		if(id_country){ req=req+'&id_country='+id_country; }
		
		var name_country = document.getElementById("syst_country_name").value;
		if(name_country){ req=req+'&name_country='+name_country; }
	
		var name_town = document.getElementById("syst_name_town").value;
		if(name_town){ req=req+'&name_town='+name_town; }
		
		var code_town = document.getElementById("syst_code_town").value;
		if(code_town){ req=req+'&code_town='+code_town; }
		
		var x = document.getElementById("syst_x").value;
		if(x){ req=req+'&x='+x; }
		
		var y = document.getElementById("syst_y").value;
		if(y){ req=req+'&y='+y; }
		
		var timezone = document.getElementById("syst_timezone").value;
		if(timezone){ req=req+'&timezone='+timezone; }
		
		var population = document.getElementById("syst_population").value;
		if(population){ req=req+'&population='+population; }
		
		var description_en = document.getElementById("syst_description_en").value;
		if(description_en){ req=req+'&description_en='+description_en; }
		
		var description_de = document.getElementById("syst_description_de").value;
		if(description_de){ req=req+'&description_de='+description_de; }
		
		var description_fr = document.getElementById("syst_description_fr").value;
		if(description_fr){ req=req+'&description_fr='+description_fr; }
		
		var description_pt = document.getElementById("syst_description_pt").value;
		if(description_pt){ req=req+'&description_pt='+description_pt; }
		
		var description_es = document.getElementById("syst_description_es").value;
		if(description_es){ req=req+'&description_es='+description_es; }
		
		var region1 = document.getElementById("syst_region1").value;
		if(region1){ req=req+'&region1='+region1; }
		
		var region2 = document.getElementById("syst_region2").value;
		if(region2){ req=req+'&region2='+region2; }
		
		var region3 = document.getElementById("syst_region3").value;
		if(region3){ req=req+'&region3='+region3; }
		
		var region4 = document.getElementById("syst_region4").value;
		if(region4){ req=req+'&region4='+region4; }
		
		var iso = document.getElementById("syst_iso").value;
		if(iso){ req=req+'&iso='+iso; }
		
		var language = document.getElementById("syst_language").value;
		if(language){ req=req+'&language='+language; }
		
		var postcode = document.getElementById("syst_postcode").value;
		if(postcode){ req=req+'&postcode='+postcode; }
		
		var suburb = document.getElementById("syst_suburb").value;
		if(suburb){ req=req+'&suburb='+suburb; }
		
		var utc = document.getElementById("syst_utc").value;
		if(utc){ req=req+'&utc='+utc; }
		
		var dst = document.getElementById("syst_dst").value;
		if(dst){ req=req+'&dst='+dst; }
		
		var gid_town = document.getElementById("syst_gid_town").value;
		if(gid_town){ req=req+'&gid_town='+gid_town; }
		
		
		var resurl='listeslies.php?elemid=manag_system_town&conf='+conf+'&gid_town='+gid_town+req;    
		var xhr = getXhr();  
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;    //console.log(leselect);   
				
				if(leselect == 1){
					toastr.success('Town successfully saved',{timeOut:15000})
					$("#modalTown").modal("hide");
					syst_town();
					
				} else 
				if(leselect == 0){
					toastr.error('Unable to save town, please retry!',{timeOut:15000})
				} else {
					internal_error();
				}
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
		
	} else
	if(conf == 'del'){
		
		/* Delete town */
		
		var resurl='listeslies.php?elemid=delete_system_town&gid_town='+gid_town;    
		var xhr = getXhr();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;         
				
				if(leselect == 1){
					toastr.success('Town successfully deleted',{timeOut:15000})
					syst_town();
				} else 
				if(leselect == 0){
					toastr.error('Town not deleted, please retry!',{timeOut:15000})
				} else {
					internal_error();
				}
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
		
	} else {}
}


/*  Select country name */

function selCountryName(id_country) { 
	var resurl='listeslies.php?elemid=town_country&id_country='+id_country;    
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;         
			document.getElementById("syst_country_name").value = leselect;
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}

/*  Filter by country */

function filterByCountry(id_country) {
	
	$("#listSysTownsFooter").addClass("hide");
	
	var resurl='listeslies.php?elemid=town_table&id_country='+id_country;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;
			var val = leselect.split('??');

			document.getElementById('listSysTowns').innerHTML = val[0];
			$('.footable').footable();
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}

/*  Show towns */

function syst_town() { 
	hideAll();
	
	$("#db_town").removeClass("hide");
	titleMenuManag("Town","btn_syst_town");
	
	$("#townspanner").removeClass("hide");
	$("#listSysTownsFooter").removeClass("hide");
	
	var resurl='listeslies.php?elemid=town_table';
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;  
			var val = leselect.split('??');

			document.getElementById('listSysTowns').innerHTML = val[0];
			document.getElementById('systCountryId').innerHTML = val[1];
			
			$("#townspanner").addClass("hide");
			$('.footable').footable();
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}



/* 
*
* Culure management
*
*/

function cultureManagement(conf,id_culture,val) {

	if(conf == 'add'){
		/* Add new culture */
		
		var req = '';
		
		var name_culture = document.getElementById("name_culture").value;
		if(name_culture){ req=req+'&name_culture='+name_culture; }
		
		
		var resurl='listeslies.php?elemid=manag_system_culture&conf='+conf+'&id_culture='+id_culture+req;    
		var xhr = getXhr();  
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;     
				
				if(leselect == 1){
					toastr.success('Culture successfully added',{timeOut:15000})
					document.getElementById("cultureForm").reset();
					culture();
				} else 
				if(leselect == 0){
					toastr.error('Culture not added, please retry!',{timeOut:15000})
				} else {
					internal_error();
				}
				
				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
	
	} else
	if(conf == 'show'){
		if(val== 'create'){
			// /* Save button */
			// document.getElementById('portModalLabel').innerHTML = "Create new country";
			// document.getElementById('portModalFooter').innerHTML ='<button type="button" class="btn btn-success" onclick="countryManagement(\'add\',\'\',\'\');"><i class="fa fa-save"></i></button>';
		} else
		if(val== 'mod'){ 
			
			document.getElementById("systCultureForm").reset();
			
			var resurl='listeslies.php?elemid=show_system_culture&id_culture='+id_culture;   
			var xhr = getXhr();
			xhr.onreadystatechange = function(){
				if(xhr.readyState == 4 ){
					leselect = xhr.responseText;    
					
					document.getElementById("edit_culture_name").value = leselect;
					
					document.getElementById('id_culture_systEdit').value = id_culture;
				
					leselect = xhr.responseText;
				}
			};

			xhr.open("GET",resurl,true);
			xhr.send(null);
	
			
			// /* Edit button */
			document.getElementById('cultureModalLabel').innerHTML = "Edit culture";
			document.getElementById('cultureModalFooter').innerHTML ='<button type="button" class="btn btn-success" onclick="cultureManagement(\'edit\',\''+id_culture+'\',\'\');"><i class="fa fa-save"></i></button>'
					+'<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i></button>';
		} else {}
		
	} else
	if(conf == 'edit'){	 
		
		/* Edit culture */
		
		var req = '';
		
		var name_culture = document.getElementById("edit_culture_name").value;
		if(name_culture){ req=req+'&name_culture='+name_culture; }
		
		var id_culture = document.getElementById("id_culture_systEdit").value;
		if(id_culture){ req=req+'&id_culture='+id_culture; }
		
		var resurl='listeslies.php?elemid=manag_system_culture&conf='+conf+req;    
		var xhr = getXhr();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;         
				
				if(leselect == 1){
					toastr.success('Culture successfully saved',{timeOut:15000})
					$("#modalCulture").modal("hide");
					culture();
					
				} else 
				if(leselect == 0){
					toastr.error('Unable to save culture, please retry!',{timeOut:15000})
				} else {
					internal_error();
				}
				
				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
		
	} else
	if(conf == 'del'){
		
		/* Delete culture */
		
		var resurl='listeslies.php?elemid=delete_system_culture&id_culture='+id_culture;    
		var xhr = getXhr();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;         
				
				if(leselect == 1){
					toastr.success('Culture successfully deleted',{timeOut:15000})
					culture();
				} else 
				if(leselect == 0){
					toastr.error('Culture not deleted, please retry!',{timeOut:15000})
				} else {
					internal_error();
				}
				
				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
		
	} else {}
}


/* Show culture page */

function culture() {
	hideAll();
	
	$("#db_crm_cult").removeClass("hide");
	titleMenuManag("Culture","btn_crm_cult");
	
	if(sysCulture_create == 1) { $("#createCultBox").removeClass("hide"); } else { $("#createCultBox").addClass("hide"); }
	
	var resurl='listeslies.php?elemid=culture&update_right='+sysCulture_update+'&delete_right='+sysCulture_delete;    
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;         
			
			document.getElementById("cultures_table").innerHTML = leselect;

			leselect = xhr.responseText;
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}



/* 
*
* Country management
*
*/

L.control.layers(baseMaps_country_modal, overlayMaps).addTo(country_modal_map);
	
function countryManagement(conf,id_country,val) {  

	if(conf == 'add'){
		/* Add new country */
		
		var req = '';
		
		var name_country = document.getElementById("syst_name_country").value;
		if(name_country){ req=req+'&name_country='+name_country; }
		
		var code = document.getElementById("syst_code").value;
		if(code){ req=req+'&code='+code; }
		
		var capitale = document.getElementById("syst_capitale").value;
		if(capitale){ req=req+'&capitale='+capitale; }
		
		var capitale_x = document.getElementById("syst_capitale_y").value;
		if(capitale_x){ req=req+'&capitale_x='+capitale_x; }
		
		var capitale_y = document.getElementById("syst_capitale_x").value;
		if(capitale_y){ req=req+'&capitale_y='+capitale_y; }
		
		var number_population = document.getElementById("syst_number_population").value;
		if(number_population){ req=req+'&number_population='+number_population; }
		
		var area = document.getElementById("syst_area").value;
		if(area){ req=req+'&area='+area; }
		
		var culture = $('select#syst_culture').val();      
		if(culture){ req=req+'&culture='+culture; }
		
		if(name_country == ""){
			alert("Enter a country name.");
			
		} else
		if(code == ""){
			alert("Enter a counrty code.");
			
		} else
		if(capitale == ""){
			alert("Enter a capitale name.");
			
		} else {
			var resurl='listeslies.php?elemid=manag_system_country&conf='+conf+'&id_country='+id_country+req;      
			var xhr = getXhr();
			xhr.onreadystatechange = function(){
				if(xhr.readyState == 4 ){
					leselect = xhr.responseText;     
					
					if(leselect == 1){
						toastr.success('Country successfully added',{timeOut:15000})
						$("#modalCountry").modal("hide");
						syst_country();
					} else 
					if(leselect == 0){
						toastr.error('Country not added, please retry!',{timeOut:15000})
					} else {
						internal_error();
					}
				}
			};

			xhr.open("GET",resurl,true);
			xhr.send(null);
		}
		
	} else
	if(conf == 'show'){
		if(val== 'create'){ 
			
			$('.chosen-select').chosen({width: "100%"});
			
			setTimeout(function() {
				country_modal_map.invalidateSize(); 
			}, 1000);
  
			country_modal_map.fitWorld().zoomIn();

			document.getElementById("systCountryForm").reset();
			
			$("#syst_culture option:selected").prop("selected", false);
			
			country_modal_map.on('click', function(e) {        
				var popLocation= e.latlng;
				var popup = L.popup()
				.setLatLng(popLocation)
				.setContent('<p><b>x = </b>'+popLocation.lat+'<br /><b>y = </b>'+popLocation.lng+'</p>')
				.openOn(country_modal_map);   

				document.getElementById("syst_capitale_y").value = popLocation.lat;
				document.getElementById("syst_capitale_x").value = popLocation.lng;
			});
			
			
			/* Save button */
			document.getElementById('countryModalLabel').innerHTML = "Create new country";
			document.getElementById('countryModalFooter').innerHTML ='<button type="button" class="btn btn-success" onclick="countryManagement(\'add\',\'\',\'\');"><i class="fa fa-save"></i></button>'
					+'<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i></button>';
		} else
		if(val== 'mod'){ 
			
			$('.chosen-select').chosen({width: "100%"});
			
			document.getElementById("systPortForm").reset();
			
			setTimeout(function() {
				country_modal_map.invalidateSize(); 
			}, 1000);
			
			$("#syst_culture option:selected").prop("selected", false);
			
			var resurl='listeslies.php?elemid=show_system_country&id_country='+id_country;   
			var xhr = getXhr();
			xhr.onreadystatechange = function(){
				if(xhr.readyState == 4 ){
					leselect = xhr.responseText;          
					var val = leselect.split('#');
					
					document.getElementById("syst_name_country").value = val[0];
					document.getElementById("syst_code").value = val[1];
					document.getElementById("syst_number_population").value = val[2];
					document.getElementById("syst_area").value = val[3];
					document.getElementById("syst_capitale_y").value = val[4];
					document.getElementById("syst_capitale_x").value = val[5];
					// document.getElementById("syst_culture").value = val[6];
					
					var values=val[6];
					$.each(values.split(","), function(i,e){
						$("#syst_culture option[value='" + e + "']").prop("selected", true);
						$('.chosen-select').e;
						$('.chosen-select').trigger("chosen:updated");
					});
					
					document.getElementById("syst_capitale").value = val[7];
					
					document.getElementById('syst_id_country').value = id_country;
					
					if((val[4]!="")&&(val[5]!="")){
						var popLocation= new L.LatLng(val[5],val[4]);
						var popup = L.popup()
							.setLatLng(popLocation)
							.setContent('<p><b>x = </b>'+val[5]+'<br /><b>y = </b>'+val[4]+'</p>')
							.openOn(country_modal_map);
							
						country_modal_map.setView([val[5], val[4]], 13);
						
					} else {
						country_modal_map.fitWorld().zoomIn();
					}
					
					country_modal_map.on('click', function(e) {        
						var popLocation= e.latlng;
						var popup = L.popup()
						.setLatLng(popLocation)
						.setContent('<p><b>'+val[7]+'</b><br /><b>x = </b>'+popLocation.lat+'<br /><b>y = </b>'+popLocation.lng+'</p>')
						.openOn(country_modal_map);   

						document.getElementById("syst_capitale_y").value = popLocation.lat;
						document.getElementById("syst_capitale_x").value = popLocation.lng;
					});
				
					leselect = xhr.responseText;
				}
			};

			xhr.open("GET",resurl,true);
			xhr.send(null);
	
			
			/* Edit button */
			document.getElementById('countryModalLabel').innerHTML = "Edit country";
			document.getElementById('countryModalFooter').innerHTML ='<button type="button" class="btn btn-success" onclick="countryManagement(\'edit\',\''+id_country+'\',\'\');"><i class="fa fa-save"></i></button>'
					+'<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i></button>';
		} else {}
		
	} else
	if(conf == 'edit'){	 
		
		/* Edit country */
		
		var req = '';
		
		var name_country = document.getElementById("syst_name_country").value;
		if(name_country){ req=req+'&name_country='+name_country; }
		
		var code = document.getElementById("syst_code").value;
		if(code){ req=req+'&code='+code; }
		
		var capitale = document.getElementById("syst_capitale").value;
		if(capitale){ req=req+'&capitale='+capitale; }
		
		var capitale_x = document.getElementById("syst_capitale_y").value;
		if(capitale_x){ req=req+'&capitale_x='+capitale_x; }
		
		var capitale_y = document.getElementById("syst_capitale_x").value;
		if(capitale_y){ req=req+'&capitale_y='+capitale_y; }
		
		var number_population = document.getElementById("syst_number_population").value;
		if(number_population){ req=req+'&number_population='+number_population; }
		
		var area = document.getElementById("syst_area").value;
		if(area){ req=req+'&area='+area; }
		
		var culture = $('select#syst_culture').val();
		if(culture){ req=req+'&culture='+culture; }
		
		var id_country = document.getElementById("syst_id_country").value;
		if(id_country){ req=req+'&id_country='+id_country; }
		
		
		var resurl='listeslies.php?elemid=manag_system_country&conf='+conf+'&id_country='+id_country+req;    
		var xhr = getXhr();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;         
				
				if(leselect == 1){
					toastr.success('Country successfully saved',{timeOut:15000})
					$("#modalCountry").modal("hide");
					syst_country();
					
				} else 
				if(leselect == 0){
					toastr.error('Unable to save counrty, please retry!',{timeOut:15000})
				} else {
					internal_error();
				}
				
				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
		
	} else
	if(conf == 'del'){
		
		/* Delete country */
		
		var resurl='listeslies.php?elemid=delete_system_country&id_country='+id_country;    
		var xhr = getXhr();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;         
				
				if(leselect == 1){
					toastr.success('Country successfully deleted',{timeOut:15000})
				} else 
				if(leselect == 0){
					toastr.error('Country not deleted, please retry!',{timeOut:15000})
				} else {
					internal_error();
				}
				
				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
		
	} else {}
}


/*  Show country */

function syst_country() { 
	hideAll();
	
	$("#db_country").removeClass("hide");
	titleMenuManag("Country","btn_syst_cunt");
	
	$("#countrypanner").removeClass("hide");
	
	var resurl='listeslies.php?elemid=country_table';
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;

			document.getElementById('listSysCountry').innerHTML = leselect;
			
			$("#countrypanner").addClass("hide");

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}



function showQuoteForm(id_ord_schedule,last_ship_nr,id_ord_order) { 

	$('#quote_list li').click(function() { 
		$('ul li.highlight_qlist').removeClass('highlight_qlist');
		$(this).closest('li').addClass('highlight_qlist');
	});
	
	// var mail = '<a href="#" class="pull-right" style="margin-left:10px; color:#fff;"  onclick="eMailForm(\''+id_ord_order+'\',\'crm\',\'\');"><i class="fa fa-envelope"></i></a>';

	// document.getElementById('summaryDocs').innerHTML = mail;
	// document.getElementById('requestDocs').innerHTML = '';
	
	// document.getElementById('schedule_quote').innerHTML = '<div class="h1 m-t-xs text-navy"><span class="loading"></span></div>';

	var resurl='listeslies.php?elemid=show_quote_form&id_ord_schedule='+id_ord_schedule+'&last_ship_nr='+last_ship_nr+'&update_right='+exporter_update;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;

			document.getElementById('schedule_quote').innerHTML = leselect;
			document.getElementById('id_ord_schedule').value = id_ord_schedule;
			
			$('#exporter_quote_formContent').find('input, textarea, select').prop("disabled", true);

			$('.edit_delivery_date').datepicker({
				format: "yyyy/mm/dd",
				calendarWeeks:true,
				autoclose: true
			});

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function saveEditScheduleLine(id_ord_order,id_ord_schedule) {

	var month_eta = document.getElementById('date-'+id_ord_schedule).value;   
	var kk = $("#date-"+id_ord_schedule).val(); 
	var week_eta = moment(kk, "YYYY/MM/DD").week(); 
	
	var containers = document.getElementById('qty-'+id_ord_schedule).value;
	if(containers != ""){
		var nr_containers = containers;
	} else {
		var nr_containers = 0;
	}

	var weight_container = document.getElementById('wgt-'+id_ord_schedule).value;  
	
	var resurl='listeslies.php?elemid=save_edit_schedule&id_ord_schedule='+id_ord_schedule+'&nr_containers='+nr_containers+'&weight_container='+weight_container+'&month_eta='+month_eta+'&week_eta='+week_eta+'&id_ord_order='+id_ord_order+'&sched_update='+sched_update;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText; 
			var val = leselect.split('##');
			
			if(val[0] == 1){
				toastr.success(val[1],{timeOut:15000})
				refreshScheduleShipement(id_ord_order); 
				if(val[2]!=""){ 
					var ficheurl='pdf/notification.php?id_ord_schedule='+id_ord_schedule+'&doc_filename='+val[2]+'&old_month_eta='+val[3]+'&old_nr_containers='+val[4]+'&conf=edit'; 
					saveNotificationMail(ficheurl);
				}
				
			} else 
			if(val[0] == 0){
				toastr.error(val[1],{timeOut:15000})
			} else {
				internal_error();
			}

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function refreshScheduleShipement(id_ord_order) {
	var resurl='listeslies.php?elemid=refresh_shipment_grid&id_ord_order='+id_ord_order;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;
		
			document.getElementById('request_schedule').innerHTML = leselect;
			document.getElementById('request_schedule2').innerHTML = leselect;
		
			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function sendeMAil() {  

	var subject = document.getElementById('eMailSubject').value;   
	var contenu = quill.root.innerHTML;    
	
	i = 0;
	var cc =''; 
	var bcc ='';
	var recep ='';
	var msg_recipients ='';

	var data_cc = $("#eMailCc").chosen().val()+',end'; 
	var data_cc = data_cc.split(',');
	while (data_cc[i] != 'end') {       
        var elt_cc=data_cc[i].split('??'); 
		if (elt_cc[0] != null){ cc=cc+elt_cc[0]+','; }
		if (elt_cc[1] != null){ msg_recipients=msg_recipients+elt_cc[1]+','; }
		i+=1;
	}  

	e = 0;
	var data_bcc = $("#eMailBcc").chosen().val()+',end'; 
	var data_bcc = data_bcc.split(',');
	while (data_bcc[e] != 'end') {       
        var elt_bcc=data_bcc[e].split('??');  
		if (elt_bcc[0] != null){ bcc=bcc+elt_bcc[0]+','; }
		if (elt_bcc[1] != null){ msg_recipients=msg_recipients+elt_bcc[1]+','; }
		e+=1;
	} 
	
	a = 0;
	var data_recep = $("#eMailTo").chosen().val()+',end';   
	var data_recep = data_recep.split(',');
	while (data_recep[a] != 'end') {       
        var elt_recep=data_recep[a].split('??');  
		if (elt_recep[0] != null){ recep=recep+elt_recep[0]+','; }
		if (elt_recep[1] != null){ msg_recipients=msg_recipients+elt_recep[1]+','; }
		a+=1;
	} 
	
	var resurl='listeslies.php?elemid=send_eMail&recipient='+recep+'&subject='+subject+'&contenu='+contenu+'&cc='+cc+'&bcc='+bcc+'&msg_recipients='+msg_recipients;  
    var xhr = getXhr();      
    xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;    
			var val = leselect.split('##');
			
			if(val[0]==1){
				toastr.success('Email successfully sent.',{timeOut:15000})
				document.getElementById("eMailform").reset();
				$("#icoopMsgModal").modal("hide");
				resetSendeMAil();
				
				saveMailAsPdf(val[1],recep,subject,contenu,val[2],val[3],val[5],cc,bcc); 
				
			} else 
			if(val[0]==0){
				toastr.error('Email not sent.',{timeOut:15000})
			} else {
				internal_error();
			}

        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}

function saveMailAsPdf(sender,to,subject,content,datetime,filename,ref,cc,bcc) {  

	var resurl='pdf/mail.php?from='+sender+'&to='+to+'&subject='+subject+'&content='+content+'&datetime='+datetime+'&filename='+filename+'&ref='+ref+'&cc='+cc+'&bcc='+bcc;

    var xhr = getXhr();    
    xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;  
			notifications("mail"); 
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function showEditScheduleLine(id_ord_order,id_ord_schedule,contact_code,product_code,order_number,pipeline_id) {
	var conf='&edit_schedule_line='+id_ord_schedule;
	showOrderSummary(id_ord_order,conf,contact_code,product_code,order_number,pipeline_id);
}


var scaleConfigs = [];

function showOrderSummary(id_ord_order,conf,contact_code,product_code,order_number,pipeline_id) {
	
	$("#crmSummarySpanner1").removeClass("hide");
	$("#crmSummarySpanner2").removeClass("hide");

	var mail = '<a href="#" class="pull-right" style="margin-left:10px; color:#fff;"  onclick="eMailForm(\''+id_ord_order+'\',\'crm\',\'\');"><i class="fa fa-envelope"></i></a>';
	
	if(docManager_read==1){
		var doc_crm = '<a href="#" class="pull-right" style="margin-left:10px; color:#fff;" onclick="showDocList(\''+id_ord_order+'\',\'\',\'crm\',\'\');"><i class="fa fa-file-text"></i></a>';
		var doc_log = '<a href="#" class="pull-right" style="margin-left:10px; color:#fff;" onclick="showDocList(\''+id_ord_order+'\',\'\',\'crm\',\'schedule\');"><i class="fa fa-file-text"></i></a>';
		
		document.getElementById('summaryDocs').innerHTML = mail+doc_crm;
		document.getElementById('requestDocs').innerHTML = doc_log;
	}
	
	var ord_schedule_id='';
	documentList(id_ord_order,ord_schedule_id,'crm');
	
	var resurl='listeslies.php?elemid=show_order_summary&id_ord_order='+id_ord_order+'&sched_update='+sched_update+'&sched_create='+sched_create+conf;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;    
			var val = leselect.split('##');
			
			document.getElementById('user_summary').innerHTML = val[0];   
			document.getElementById('importer_summary').innerHTML = val[1];  
			document.getElementById('contract_summary').innerHTML = val[9];  
			document.getElementById('request_schedule').innerHTML = val[2]; 
			document.getElementById('quote_list').innerHTML = val[3];  
			document.getElementById('freight_list').innerHTML = val[4];   
			document.getElementById('proposal_list').innerHTML = val[7];  
			document.getElementById('ord_confrim_list').innerHTML = val[8]; 
	
			document.getElementById('request_header').innerHTML = val[5];  
			document.getElementById('quote_calc').innerHTML = val[6];  
	
			document.getElementById('schedule_quote').innerHTML = '<span style="font-size: 14px;"><i class="fa fa-hand-o-left"></i> Select a schedule in your list</span>';
			document.getElementById('schedule_calc_table').innerHTML = '<span style="font-size: 14px;"><i class="fa fa-hand-o-left"></i> Select a schedule in your list</span>';
			document.getElementById('proposal_content').innerHTML = '<span style="font-size: 14px;"><i class="fa fa-hand-o-left"></i> Select a schedule in your list</span>';
			document.getElementById('ord_confrim_ctn').innerHTML = '<span style="font-size: 14px;"><i class="fa fa-hand-o-left"></i> Select a schedule in your list</span>';
		
			document.getElementById('sum_refnum').innerHTML = contact_code+'-'+order_number+'-'+product_code;
			document.getElementById('req_refnum').innerHTML = contact_code+'-'+order_number+'-'+product_code;
		
			document.getElementById('freight_1').innerHTML = '';
			document.getElementById('freight_2').innerHTML = '';
	
			$("#crmSummarySpanner1").addClass("hide");
			$("#crmSummarySpanner2").addClass("hide");
		
			// Exporter status
			if(val[10]==1){
				if(freight_read == 1){ $("#crm_request_freight_tab").removeClass("hide"); $("#crm_request_freight_ct").removeClass("hide"); } 
				else { $("#crm_request_freight_tab").addClass("hide"); $("#crm_request_freight_ct").addClass("hide"); }

			} else {
				$("#crm_request_freight_tab").addClass("hide"); $("#crm_request_freight_ct").addClass("hide");
			}
		
			// Calculate status
			if(val[12]==1){
				if(proposal_read == 1){ $("#crm_request_proposal_tab").removeClass("hide"); $("#crm_request_proposal_ct").removeClass("hide"); } 
				else { $("#crm_request_proposal_tab").addClass("hide"); $("#crm_request_proposal_ct").addClass("hide"); }
				
			} else {
				$("#crm_request_proposal_tab").addClass("hide"); $("#crm_request_proposal_ct").addClass("hide");
			}
		
			// Order status
			if(val[13]==1){
				if(ordcon_read == 1){ $("#crm_request_ordconfirm_tab").removeClass("hide"); $("#crm_request_ordconfirm_ct").removeClass("hide");  } 
				else { $("#crm_request_ordconfirm_tab").addClass("hide"); $("#crm_request_ordconfirm_ct").addClass("hide"); }
				
				$('#crm_contract_tab').removeClass("hide"); $('#crm_contract').removeClass("hide");
			} else {
				$("#crm_request_ordconfirm_tab").addClass("hide"); $("#crm_request_ordconfirm_ct").addClass("hide");
				$('#crm_contract_tab').addClass("hide"); $('#crm_contract').addClass("hide");
			}
			
			// Freight status
			if(val[14]==1){ 
				if(calc_read == 1){ $("#crm_request_calc_tab").removeClass("hide"); $("#crm_request_calc_ct").removeClass("hide"); } 
				else { $("#crm_request_calc_tab").addClass("hide"); $("#crm_request_calc_ct").addClass("hide"); }
			
			} else { 
				$("#crm_request_calc_tab").addClass("hide"); $("#crm_request_calc_ct").addClass("hide");
			}
			
			if(val[15]){
				document.getElementById('addShipmentROW').innerHTML = val[15];
			}
		
			if(contract_update == 1){ $("#contractTabEdit").removeClass("hide"); } else { $("#contractTabEdit").addClass("hide"); }
			if(sum_update == 1){ $("#sumCusRequestToggler").removeClass("hide"); } else { $("#sumCusRequestToggler").addClass("hide"); }
			if(sumNote_update == 1){ $("#sumBtnsToggler").removeClass("hide"); } else { $("#sumBtnsToggler").addClass("hide"); }
		
			$('.edit_delivery_date').datepicker({
				format: "yyyy/mm/dd",
				calendarWeeks:true,
				autoclose: true
			}); 
	
			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function edit_contract(id_ord_order) {
	$("#cusRefNumbCTManagBtn").removeClass("hide");
	$("#orderSupRefNrManagBtn").removeClass("hide");
	$("#orderFaCompManagBtn").removeClass("hide");
	$("#orderFaRefNrManagBtn").removeClass("hide");
	
	document.getElementById('contractTabEdit').innerHTML = '<button class="btn btn-danger pull-right" onclick="cancel_contractEditing(\''+id_ord_order+'\');" type="button"><i class="fa fa-ban"></i></button>'+
		' &nbsp;<button class="btn btn-success pull-right" onclick="save_contract(\''+id_ord_order+'\');" style="margin-right:10px;" type="button"><i class="fa fa-save"></i></button>';
}

function cancel_contractEditing(id_ord_order){ 
	$("#cusRefNumbCTManagBtn").addClass("hide"); 
	$("#orderSupRefNrManagBtn").addClass("hide"); 
	$("#orderFaCompManagBtn").addClass("hide"); 
	$("#orderFaRefNrManagBtn").addClass("hide"); 
	
	document.getElementById('contractTabEdit').innerHTML = '<button class="btn btn-success pull-right" onclick="edit_contract(\''+id_ord_order+'\');" style="margin-top:10px;" type="button"><i class="fa fa-edit"></i></button>';
}

/*
 Manage
 Contract customer reference number
*/

function editCusRefNumbCT(id_ord_order){
	$("#orderCusRefNrLabel").addClass("hide");
	$("#orderCusRefNrInput").removeClass("hide");
	
	document.getElementById("cusRefNumbCTManagBtn").innerHTML = '<a href="#" class="btn btn-white btn-sm" onclick="save_contract('+id_ord_order+');"><i class="fa fa-check" style="color:green;"></i></a>'+
		' <a href="#" class="btn btn-white btn-sm" onclick="cancelCusRefNumbCT('+id_ord_order+');"><i class="fa fa-times" style="color:red;"></i></a>';
}

function cancelCusRefNumbCT(id_ord_order) {
	$('#orderCusRefNrLabel').removeClass("hide");
	$('#orderCusRefNrInput').addClass("hide");
	
	document.getElementById("cusRefNumbCTManagBtn").innerHTML = '<a herf="#" onclick="editCusRefNumbCT('+id_ord_order+');" class="btn btn-white btn-sm"><i class="fa fa-edit"></i></a>';
}

/*
 Manage
 Contract supplier reference number
*/

function editorderSupRefNr(id_ord_order){
	$("#orderSupRefNrLabel").addClass("hide");
	$("#orderSupRefNrInput").removeClass("hide");
	
	document.getElementById("orderSupRefNrManagBtn").innerHTML = '<a href="#" class="btn btn-white btn-sm" onclick="save_contract('+id_ord_order+');"><i class="fa fa-check" style="color:green;"></i></a>'+
		' <a href="#" class="btn btn-white btn-sm" onclick="cancelSupRefNrCT('+id_ord_order+');"><i class="fa fa-times" style="color:red;"></i></a>';
}

function cancelSupRefNrCT(id_ord_order) {
	$('#orderSupRefNrLabel').removeClass("hide");
	$('#orderSupRefNrInput').addClass("hide");
	
	document.getElementById("orderSupRefNrManagBtn").innerHTML = '<a herf="#" onclick="editorderSupRefNr('+id_ord_order+');" class="btn btn-white btn-sm"><i class="fa fa-edit"></i></a>';
}

/*
 Manage
 Contract Freight Agent Company
*/

function editorderFaComp(id_ord_order){
	$("#orderFaCompLabel").addClass("hide");
	$("#orderFaCompSelect").removeClass("hide");
	
	document.getElementById("orderFaCompManagBtn").innerHTML = '<a href="#" class="btn btn-white btn-sm" onclick="save_contract('+id_ord_order+');"><i class="fa fa-check" style="color:green;"></i></a>'+
		' <a href="#" class="btn btn-white btn-sm" onclick="cancelorderFaComp('+id_ord_order+');"><i class="fa fa-times" style="color:red;"></i></a>';
}

function cancelorderFaComp(id_ord_order) {
	$('#orderFaCompLabel').removeClass("hide");
	$('#orderFaCompSelect').addClass("hide");
	
	document.getElementById("orderFaCompManagBtn").innerHTML = '<a herf="#" onclick="editorderFaComp('+id_ord_order+');" class="btn btn-white btn-sm"><i class="fa fa-edit"></i></a>';
}

/*
 Manage
 Freight Agent Contract Number
*/

function editorderFaRefNr(id_ord_order){
	$("#orderFaRefNrLabel").addClass("hide");
	$("#orderFaRefNrInput").removeClass("hide");
	
	document.getElementById("orderFaRefNrManagBtn").innerHTML = '<a href="#" class="btn btn-white btn-sm" onclick="save_contract('+id_ord_order+');"><i class="fa fa-check" style="color:green;"></i></a>'+
		' <a href="#" class="btn btn-white btn-sm" onclick="cancelorderFaRefNr('+id_ord_order+');"><i class="fa fa-times" style="color:red;"></i></a>';
}

function cancelorderFaRefNr(id_ord_order) {
	$('#orderFaRefNrLabel').removeClass("hide");
	$('#orderFaRefNrInput').addClass("hide");
	
	document.getElementById("orderFaRefNrManagBtn").innerHTML = '<a herf="#" onclick="editorderFaRefNr('+id_ord_order+');" class="btn btn-white btn-sm"><i class="fa fa-edit"></i></a>';
}

/*
* Save contract
*/

function save_contract(id_ord_order){
	var sup_reference_nr = document.getElementById("sup_reference_nr_CT").value;
	var fa_reference_nr = document.getElementById("fa_reference_nr_CT").value;
	var customer_reference_nr = document.getElementById("customer_reference_nr_CT").value;
	var ord_fa_contact_id = document.getElementById("ord_fa_contact_id").value;
	
	var resurl='listeslies.php?elemid=save_edited_contract&id_ord_order='+id_ord_order+'&sup_reference_nr='+sup_reference_nr+'&fa_reference_nr='+fa_reference_nr+'&customer_reference_nr='+customer_reference_nr+'&ord_fa_contact_id='+ord_fa_contact_id;
    var xhr = getXhr(); 
    xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText; 
            var val=leselect.split('##');

			if(val[0]==1){
				toastr.success('Contract saved successfully.',{timeOut:15000})
				document.getElementById("orderSupRefNrLabel").innerHTML = val[1]; 
				document.getElementById("orderFaRefNrLabel").innerHTML = val[2];  
				document.getElementById("orderCusRefNrLabel").innerHTML = val[3];  
				document.getElementById("orderFaCompLabel").innerHTML = val[4];  
				cancel_contractEditing(id_ord_order);
				
				cancelCusRefNumbCT(id_ord_order);
				cancelSupRefNrCT(id_ord_order);
				cancelorderFaComp(id_ord_order);
				cancelorderFaRefNr(id_ord_order);
				
			} else {
				toastr.error('Note not saved.',{timeOut:15000})
			}

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null)
}


// CRM Summary Notes
function showSumEditBtns() {
	$("#intNotesManagBtn").removeClass("hide");
	$("#editSumStatus").removeClass("hide");
	$("#editSumPipeline").removeClass("hide");
	$("#orderNrOldManagBtn").removeClass("hide");

	document.getElementById("sm_person_id").disabled = false;
	document.getElementById("ord_imp_person_id").disabled = false;

	document.getElementById('sumBtnsToggler').innerHTML = '<button class="btn btn-danger pull-right" style="margin-top:10px; margin-right:20px;" onclick="showSumCloseBtn();" type="button"><i class="fa fa-ban"></i></button>'+
		' &nbsp;<button class="btn btn-success pull-right" style="margin-top:10px; margin-right:10px; " onclick="save_notes_summary();" type="button"><i class="fa fa-save"></i></button>';
}
	
	
function showSumCloseBtn() {
	$("#intNotesManagBtn").addClass("hide");
	$("#editSumStatus").addClass("hide");
	$("#editSumPipeline").addClass("hide");
	$("#orderNrOldManagBtn").addClass("hide");
	
	document.getElementById("sm_person_id").disabled = true;
	document.getElementById("ord_imp_person_id").disabled = true;
		
	document.getElementById('sumBtnsToggler').innerHTML = '<button class="btn btn-success pull-right" onclick="showSumEditBtns();" style="margin-top:10px; margin-right:20px;" type="button">'
		+'<i class="fa fa-edit"></i></button>';
}

function save_notes_summary(){
	showSumCloseBtn();
}


	function toggleMode(toggle) { 
		toggle.enabled = !toggle.enabled;
		if (toggle.enabled) {
			toggle.innerHTML = "Set default Scale";
			//Saving previous scale state for future restore
			saveConfig();
			zoomToFit();
		} else {

			toggle.innerHTML = "Zoom to Fit";
			//Restore previous scale state
			restoreConfig();
			gantt.render();
		}
	}

	var cachedSettings = {};
	function saveConfig() {
		var config = gantt.config;
		cachedSettings = {};
		cachedSettings.scale_unit = config.scale_unit;
		cachedSettings.date_scale = config.date_scale;
		cachedSettings.step = config.step;
		cachedSettings.subscales = config.subscales;
		cachedSettings.template = gantt.templates.date_scale;
		cachedSettings.start_date = config.start_date;
		cachedSettings.end_date = config.end_date;
	}
	function restoreConfig() {
		applyConfig(cachedSettings);
	}

	function applyConfig(config, dates) {
		gantt.config.scale_unit = config.scale_unit;
		if (config.date_scale) {
			gantt.config.date_scale = config.date_scale;
			gantt.templates.date_scale = null;
		}
		else {
			gantt.templates.date_scale = config.template;
		}

		gantt.config.step = config.step;
		gantt.config.subscales = config.subscales;

		if (dates) {
			gantt.config.start_date = gantt.date.add(dates.start_date, -1, config.unit);
			gantt.config.end_date = gantt.date.add(gantt.date[config.unit + "_start"](dates.end_date), 2, config.unit);
		} else {
			gantt.config.start_date = gantt.config.end_date = null;
		}
	}



	function zoomToFit() {
		var project = gantt.getSubtaskDates(),
				areaWidth = gantt.$task.offsetWidth;

		for (var i = 0; i < scaleConfigs.length; i++) {
			var columnCount = getUnitsBetween(project.start_date, project.end_date, scaleConfigs[i].unit, scaleConfigs[i].step);
			if ((columnCount + 2) * gantt.config.min_column_width <= areaWidth) {
				break;
			}
		}

		if (i == scaleConfigs.length) {
			i--;
		}

		applyConfig(scaleConfigs[i], project);
		gantt.render();
	}

	// get number of columns in timeline
	function getUnitsBetween(from, to, unit, step) {
		var start = new Date(from),
				end = new Date(to);
		var units = 0;
		while (start.valueOf() < end.valueOf()) {
			units++;
			start = gantt.date.add(start, step, unit);
		}
		return units;
	}
	
	
function showOrderConfirm(id_ord_schedule,ord_order_id) {
	
	$('#ord_confrim_list li').click(function() { 
		$('ul li.highlight_orderClist').removeClass('highlight_orderClist');
		$(this).closest('li').addClass('highlight_orderClist');
	});

	// var mail = '<a href="#" class="pull-right" style="margin-left:10px; color:#fff;"  onclick="eMailForm(\''+ord_order_id+'\',\'crm\',\'\');"><i class="fa fa-envelope"></i></a>';
	
	// if(docManager_read==1){
		// var doc = '<a href="#" class="pull-right" style="margin-left:10px; color:#fff;" onclick="showDocList(\''+ord_order_id+'\',\''+id_ord_schedule+'\',\'crm\',\'o_confirmation\');"><i class="fa fa-file-text"></i></a>';

		// document.getElementById('summaryDocs').innerHTML = mail+doc;
		// document.getElementById('requestDocs').innerHTML = doc;
	// }
	
	document.getElementById('ord_confrim_ctn').innerHTML = '<div class="h1 m-t-xs text-navy"><span class="loading"></span></div>';
	
	var resurl='listeslies.php?elemid=show_order_confirmation&id_ord_schedule='+id_ord_schedule+'&ord_order_id='+ord_order_id;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;  

			document.getElementById('ord_confrim_ctn').innerHTML = leselect;
	
			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function create_contract(ord_order_id,cus_incoterms_id) {

	var resurl='listeslies.php?elemid=create_contract&ord_order_id='+ord_order_id+'&cus_incoterms_id='+cus_incoterms_id;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;  
			var val = leselect.split('##');  
			
			if(val[0] == 3){
				viewBookingDoc(val[1],0);
			} else {
				$("#modalCreateContract").modal("show");
				document.getElementById('create_contract_modal_ctn').innerHTML = val[0];
				document.getElementById('create_contract_modal_ttle').innerHTML = 'Create Contract';
				document.getElementById('pdf_contract').innerHTML = '<button type="button" class="btn btn-info pull-left" onclick="generate_pdfContract(\''+ord_order_id+'\',\''+cus_incoterms_id+'\',\''+val[1]+'\',\'0\');"><i class="fa fa-file-pdf-o"></i>&nbsp;Preview/Print</button>';
			}
			
			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function puchase_order(ord_order_id,cus_incoterms_id,id_ord_schedule) {
	
	var resurl='listeslies.php?elemid=puchase_order&ord_order_id='+ord_order_id+'&cus_incoterms_id='+cus_incoterms_id;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;  
			var val = leselect.split('##');  
			
			if(val[0] == 3){
				viewBookingDoc(val[1],val[2]);
			} else {
				$("#modalCreateContract").modal("show");
				document.getElementById('create_contract_modal_ctn').innerHTML = val[0];
				document.getElementById('create_contract_modal_ttle').innerHTML = 'Create Purchase Order to FA';
				document.getElementById('pdf_contract').innerHTML = '<button type="button" class="btn btn-info pull-left" onclick="tankModal(\''+ord_order_id+'\',\''+cus_incoterms_id+'\',\''+id_ord_schedule+'\');"><i class="fa fa-save"></i>&nbsp;Save</button>';
			}
			
			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function tankModal(ord_order_id,cus_incoterms_id,id_ord_schedule) {
	$("#modalTankProvider").modal("show");
	$("#modalCreateContract").modal("hide");
	
	document.getElementById('modalTankProviderFooter').innerHTML = '<button type="button" class="btn btn-info pull-left" onclick="saveSend_puchase_order(\''+ord_order_id+'\',\''+cus_incoterms_id+'\',\''+id_ord_schedule+'\');"><i class="fa fa-save"></i>&nbsp;Use</button>';
}


function saveSend_puchase_order(ord_order_id,cus_incoterms_id,id_ord_schedule) {
	
	fileName(ord_order_id,'',5,'invoice'); 
	setTimeout(function(){
		doc_filename = document.getElementById('generatedFileName').value;  
		
		if(doc_filename!=""){
			var resurl='listeslies.php?elemid=save_puchase_order&ord_order_id='+ord_order_id+'&id_ord_schedule='+id_ord_schedule+'&doc_filename='+doc_filename+'&doc_type_id=5';
			var xhr = getXhr();
			xhr.onreadystatechange = function(){
				if(xhr.readyState == 4 ){
					leselect = xhr.responseText;  
					
					if(leselect==1){
						
						var tank_provider = document.getElementById('tank_provider').value;  
						
						var link="img/documents/";
						var ficheurl='pdf/purchase_order.php?ord_order_id='+ord_order_id+'&cus_incoterms_id='+cus_incoterms_id+'&doc_filename='+doc_filename+'&tank_provider='+tank_provider; 
			
						$("#bookingDocModal").modal("show");
						$("#modalTankProvider").modal("hide");
						document.getElementById('booking_document_show').innerHTML = '<div><iframe src="'+ficheurl+'" style="width:100%; height:500px;"></iframe></div>';
						document.getElementById('booking_document_footer').innerHTML = '<a href="'+link+ficheurl+'" target="_blank" class="btn btn-info pull-left"><i class="fa fa-file-pdf-o"></i>&nbsp;Preview/Print</a>'
						+'<button type="button" class="btn btn-danger pull-right" data-dismiss="modal"><i class="fa fa-ban"></i> Close</button>';
						
						toastr.success('Purchase Order saved successfully.',{timeOut:15000})
						// sendPurchaseOrder(doc_filename,'fa',ord_order_id,cus_incoterms_id);
						
					} else
					if(leselect==0){
						toastr.error('Purchase Order not saved.',{timeOut:15000})
						
					} else {
						internal_error();
					}
					
					leselect = xhr.responseText;
				}
			};

			xhr.open("GET",resurl,true);
			xhr.send(null);
		}
		
	}, 3500);
}


function puchase_order_supp(ord_order_id,cus_incoterms_id,id_ord_schedule,supplier_contact_id) {
	
	var resurl='listeslies.php?elemid=puchase_order_supp&ord_order_id='+ord_order_id+'&cus_incoterms_id='+cus_incoterms_id+'&supplier_contact_id='+supplier_contact_id;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;  
			var val = leselect.split('##');  
			
			if(val[0] == 3){
				viewBookingDoc(val[1],val[2]);
			} else {
				$("#modalCreateContract").modal("show");
				document.getElementById('create_contract_modal_ctn').innerHTML = val[0];
				document.getElementById('create_contract_modal_ttle').innerHTML = 'Create Purchase Order to Supplier';
				document.getElementById('pdf_contract').innerHTML = '<button type="button" class="btn btn-info pull-left" onclick="saveSend_puchase_order_supp(\''+ord_order_id+'\',\''+cus_incoterms_id+'\',\''+id_ord_schedule+'\',\''+supplier_contact_id+'\');"><i class="fa fa-save"></i>&nbsp;Save</button>';
			}
			
			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function saveSend_puchase_order_supp(ord_order_id,cus_incoterms_id,id_ord_schedule,supplier_contact_id) {
	
	fileName(ord_order_id,'',4,'invoice'); 
	setTimeout(function(){
		doc_filename = document.getElementById('generatedFileName').value;  
		
		if(doc_filename!=""){
			var resurl='listeslies.php?elemid=save_puchase_order&ord_order_id='+ord_order_id+'&id_ord_schedule='+id_ord_schedule+'&doc_filename='+doc_filename+'&doc_type_id=4';
			var xhr = getXhr();
			xhr.onreadystatechange = function(){
				if(xhr.readyState == 4 ){
					leselect = xhr.responseText;  
					
					if(leselect==1){
						
						var link="img/documents/";
						var ficheurl='pdf/purchase_order_supp.php?ord_order_id='+ord_order_id+'&cus_incoterms_id='+cus_incoterms_id+'&supplier_contact_id='+supplier_contact_id+'&doc_filename='+doc_filename;  
			
						$("#bookingDocModal").modal("show");
						$("#modalCreateContract").modal("hide");
						document.getElementById('booking_document_show').innerHTML = '<div><iframe src="'+ficheurl+'" style="width:100%; height:500px;"></iframe></div>';
						document.getElementById('booking_document_footer').innerHTML = '<a href="'+link+ficheurl+'" target="_blank" class="btn btn-info pull-left"><i class="fa fa-file-pdf-o"></i>&nbsp;Preview/Print</a>'
						+'<button type="button" class="btn btn-danger pull-right" data-dismiss="modal"><i class="fa fa-ban"></i> Close</button>';
						
						toastr.success('Purchase Order saved successfully.',{timeOut:15000})
						// sendPurchaseOrder(doc_filename,'supp',ord_order_id,cus_incoterms_id);
						
					} else
					if(leselect==0){
						toastr.error('Purchase Order not saved.',{timeOut:15000})
						
					} else {
						internal_error();
					}
					
					leselect = xhr.responseText;
				}
			};

			xhr.open("GET",resurl,true);
			xhr.send(null);
		}
		
	}, 3500);
}


function sendPurchaseOrder(doc_filename,info,ord_order_id,cus_incoterms_id) {
	var resurl='listeslies.php?elemid=send_puchase_order&doc_filename='+doc_filename+'&info='+info;
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;  	
				if(leselect==1){
					toastr.success('Purchase Order successfully sent by mail.',{timeOut:15000})
					
				} else
				if(leselect==0){
					toastr.error('Purchase Order not sent by mail.',{timeOut:15000})
				
				} else {
					internal_error();
				}
			
			leselect = xhr.responseText;
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}


function generate_pdfContract(ord_order_id,cus_incoterms_id,pdf,save) {
	
	var qualite_val = document.getElementById('qualite_contr').value.split('??');
	var qualite = qualite_val[1];
	
	var parity_val = document.getElementById('parity_contr').value.split('??');
	var parity = parity_val[1];
	
	var contract_val = document.getElementById('contract_contr').value.split('??');
	var contract = contract_val[1];
	
	var payment_val = document.getElementById('payment_contr').value.split('??');
	var payment = payment_val[1];
	
	var ware_added = document.getElementById('addedWare').value;
	var spezifikationen = $('#spezifikationen').val().split("&").join("@");

	
	if((qualite!="") &&
		(parity!="") &&
		(contract!="") &&
		(payment!="")
	){
		var ficheurl='pdf/contract.php?ord_order_id='+ord_order_id+'&cus_incoterms_id='+cus_incoterms_id+'&qualite='+qualite+'&parity='+parity+'&contract='+contract+'&payment='+payment+'&spezifikationen='+spezifikationen+'&save='+save+'&pdf='+pdf+'&ware_added='+ware_added; 
		
		$("#bookingDocModal").modal("show");
		$("#modalCreateContract").modal("hide");
		document.getElementById('booking_document_show').innerHTML = '<div><iframe src="'+ficheurl+'" style="width:100%; height:500px;"></iframe></div>';
		document.getElementById('booking_document_footer').innerHTML = '<a href="#" onclick="save_pdfContract(\''+ord_order_id+'\',\''+cus_incoterms_id+'\',\''+pdf+'\',\'1\');" class="btn btn-info pull-left">SAVE</a>'
		+'<button type="button" class="btn btn-danger pull-right" data-dismiss="modal"><i class="fa fa-ban"></i> Close</button>';
		
	} else {
		alert('All the select fields need to have a value.');
	}
}


function save_pdfContract(ord_order_id,cus_incoterms_id,pdf,save) {
	
	var qualite_val = document.getElementById('qualite_contr').value.split('??');
	var qualite = qualite_val[1];
	
	var parity_val = document.getElementById('parity_contr').value.split('??');
	var parity = parity_val[1];
	
	var contract_val = document.getElementById('contract_contr').value.split('??');
	var contract = contract_val[1];
	
	var payment_val = document.getElementById('payment_contr').value.split('??');
	var payment = payment_val[1];
	
	var ware_added = document.getElementById('addedWare').value;
	var spezifikationen = $('#spezifikationen').val().split("&").join("@");
	
	if((qualite!="") &&
		(parity!="") &&
		(contract!="") &&
		(payment!="")
	){
		var resurl='listeslies.php?elemid=save_contract&ord_order_id='+ord_order_id+'&pdf='+pdf+'&qualite='+qualite_val[0]+'&parity='+parity_val[0]+'&contract='+contract_val[0]+'&payment='+payment_val[0];
		var xhr = getXhr();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;  

				if(leselect==1){
					toastr.success('Contract saved successfully.',{timeOut:15000})
					
					var ficheurl='pdf/contract.php?ord_order_id='+ord_order_id+'&cus_incoterms_id='+cus_incoterms_id+'&qualite='+qualite+'&parity='+parity+'&contract='+contract+'&payment='+payment+'&save='+save+'&pdf='+pdf+'&spezifikationen='+spezifikationen+'&ware_added='+ware_added; 
					//window.open(ficheurl, "resultat","width=500px,height=600px,menubar=no,scrollbar=auto,resizable=yes,top=0,left=0,status=yes");
					
					savePDF(ficheurl);
					$("#bookingDocModal").modal("hide");
					$("#modalCreateContract").modal("hide");
					
					notifications('document');
					
				} else 
				if(leselect==0){
					toastr.error('Contract not saved.',{timeOut:15000})
				} else {
					internal_error();
				}
				
				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
	}
}


function showCalcFormAndTable(id_ord_schedule,order_ship_nr,ord_order_id,pipeline_id) {
	
	$('#quote_calc li').click(function() { 
		$('ul li.highlight_quote').removeClass('highlight_quote');
		$(this).closest('li').addClass('highlight_quote');
	});

	// var mail = '<a href="#" class="pull-right" style="margin-left:10px; color:#fff;"  onclick="eMailForm(\''+ord_order_id+'\',\'crm\',\'\');"><i class="fa fa-envelope"></i></a>';

	// document.getElementById('summaryDocs').innerHTML = mail;
	// document.getElementById('requestDocs').innerHTML = '';
	
	var resurl='listeslies.php?elemid=show_calc_form_and_table&id_ord_schedule='+id_ord_schedule+'&update_right='+calc_update+'&order_ship_nr='+order_ship_nr+'&pipeline_id='+pipeline_id;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;

			document.getElementById('schedule_calc_table').innerHTML = leselect;
			
			$('#calcVariableBloc').find('input, select').prop("disabled", true);
			$('#tableCalcBtn').prop("disabled", true);
			$('#oandaBtn').prop("disabled", true);
			
			$('.i-checks').iCheck({
				checkboxClass: 'icheckbox_square-green',
				radioClass: 'iradio_square-green'
			});
			
			$('input').on('ifChanged', function(event){ calcActiveState($(event.target).val()); });
			
			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function calcSurchargePerctage() {
	var percentage = document.getElementById('calcPercentage').value;
	var sales_mt = document.getElementById('calcSalesMt').value;
	
	if((percentage!="")&&(sales_mt!="")){
		var new_amount = (Number(percentage) * Number(sales_mt)) / 100;
		
		document.getElementById('calcAmount').value = new_amount.toFixed(2);;
	}
}

function addSurchage(id_proposal_calc) {
	
	var amount = document.getElementById('calcAmount').value;
	var percentage = document.getElementById('calcPercentage').value;
	var sales_mt = document.getElementById('calcSalesMt').value;
	
	var new_sales_mt = (Number(sales_mt) + Number(amount)).toFixed(2);  
	
	var resurl='listeslies.php?elemid=add_surcharge_to_marge&id_proposal_calc='+id_proposal_calc+'&ship_sales_surcharge_amount='+amount+'&ship_sales_surcharge='+percentage+'&sales_mt='+new_sales_mt;
    var xhr = getXhr();  
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;   
			var val = leselect.split('##');

			if(val[0] == 1){
				toastr.success('Surcharge successfully added.',{timeOut:15000})
				showCalcTable(val[1],val[2],val[3]);
				
				setTimeout(function() {
					showCalcFormAndTable(val[2],val[6],val[4],val[5]);
				}, 100);
				
			}  else 
			if(val[0] == 0){
				toastr.error('Surcharge not added.',{timeOut:15000})
			} else {
				internal_error();
			}
			
			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}



function getCurrency(id_proposal_calc) {
	
	var usd_chf = '';
	var usd_eur = '';
	var eur_chf = '';
	var time = '';
	
	document.getElementById('saveIdTimeStampExcRt').innerHTML ='<div class="h1 m-t-xs text-navy"><span class="loading"></span></div>';
	
	$.getJSON("https://openexchangerates.org/api/latest.json?app_id=797603139cec40c9a1c0241c56b8c4c5", function(result){
		usd_chf = result.rates.CHF;  
		usd_eur = result.rates.EUR; 
	});
	
	$.getJSON("https://openexchangerates.org/api/latest.json?app_id=0a04051fbc9747dcb3e116ec4f1e7d6f&base=EUR", function(result2){
		eur_chf = result2.rates.CHF;  
		time = result2.timestamp;  
	});

	setTimeout(function() {
		if((usd_chf!="")&&(usd_eur!="")&&(eur_chf!="")&&(time!="")) {
			var date = convertTimestamp(time);
			// var date = new Date(time);
			
			document.getElementById('saveIdUsdChf').value = usd_chf;
			document.getElementById('saveIdUsdEur').value = usd_eur;
			document.getElementById('saveIdEurChf').value = eur_chf;
			document.getElementById('saveIdTimeStampExcRt').innerHTML = 'Open Exchange Rates @ '+date;
			
			var resurl='listeslies.php?elemid=save_openexchangerates_date&id_proposal_calc='+id_proposal_calc+'&exch_datetime='+date;
			var xhr = getXhr();
			xhr.onreadystatechange = function(){
				if(xhr.readyState == 4 ){
					leselect = xhr.responseText;

					
					leselect = xhr.responseText;
				}
			};

			xhr.open("GET",resurl,true);
			xhr.send(null);
		}
	}, 3500);
	
}


function convertTimestamp(timestamp) {
  var d = new Date(timestamp * 1000),	// Convert the passed timestamp to milliseconds
		yyyy = d.getFullYear(),
		mm = ('0' + (d.getMonth() + 1)).slice(-2),	// Months are zero based. Add leading 0.
		dd = ('0' + d.getDate()).slice(-2),			// Add leading 0.
		hh = d.getHours(),
		h = hh,
		min = ('0' + d.getMinutes()).slice(-2),		// Add leading 0.
		ampm = 'AM',
		time;
			
	if (hh > 12) {
		h = hh - 12;
		ampm = 'PM';
	} else if (hh === 12) {
		h = 12;
		ampm = 'PM';
	} else if (hh == 0) {
		h = 12;
	}
	
	// ie: 2013-02-18, 8:35 AM	
	time = yyyy + '-' + mm + '-' + dd + ' ' + h + ':' + min;
	
	return time;
}

function editCalcVariables(id_ord_schedule,order_status) {
	$('#calcVariableBloc').find('input, select').prop("disabled", false);
	$('#oandaBtn').prop("disabled", false);
	
	$('#tableCalcBtn').prop("disabled", false);
	
	// if(order_status!=1){ 
		// $('#tableCalcBtn').prop("disabled", false);
	// } else {
		// $('#tableCalcBtn').prop("disabled", true);
	// }
	
	$("#edSalesMT").removeClass("hide");
	
	document.getElementById('show_saveCalcVariables').innerHTML = '<button onclick="cancelCalcVariables(\''+id_ord_schedule+'\',\''+order_status+'\');" class="btn btn-danger pull-right"><i class="fa fa-ban"></i></button>'
		+'<button onclick="saveCalcModBy('+id_ord_schedule+');" style="margin-right:10px;" class="btn btn-success pull-right"><i class="fa fa-save"></i></button>';
}

function cancelCalcVariables(id_ord_schedule,order_status) {
	$('#calcVariableBloc').find('input, select').prop("disabled", true);
	$('#tableCalcBtn').prop("disabled", true);
	$('#oandaBtn').prop("disabled", true);
	
	$("#edSalesMT").addClass("hide");
	$("#salesMTnewVal").addClass("hide");
	
	document.getElementById('show_saveCalcVariables').innerHTML = '<button onclick="editCalcVariables(\''+id_ord_schedule+'\',\''+order_status+'\');" class="btn btn-success pull-right"><i class="fa fa-edit"></i></button>';
}


function increaseSP(id_proposal_calc,ship_sales_value_tone,margin_mt,id_ord_schedule) {
	$("#salesMTnewVal").removeClass("hide");
	document.getElementById('salesMTnewVal').value ="";
	document.getElementById('edSalesMT').innerHTML = '<a href="#" onclick="saveIncreasedSP(\''+id_proposal_calc+'\',\''+ship_sales_value_tone+'\',\''+margin_mt+'\',\''+id_ord_schedule+'\');" style="color:blue;"><i class="fa fa-save"></i></a>'
		+'&nbsp;&nbsp;<a href="#" onclick="cancelIncreasedSP(\''+id_proposal_calc+'\',\''+ship_sales_value_tone+'\',\''+margin_mt+'\',\''+id_ord_schedule+'\');" style="color:red;"><i class="fa fa-ban"></i></a>';
}


function saveIncreasedSP(id_proposal_calc,ship_sales_value_tone,margin_mt,id_ord_schedule) {
	var new_sales_price = document.getElementById('salesMTnewVal').value; 
	var resurl='listeslies.php?elemid=save_increased_sp&id_proposal_calc='+id_proposal_calc+'&ship_sales_value_tone='+ship_sales_value_tone+'&new_sales_price='+new_sales_price+'&old_margin_mt='+margin_mt+'&id_ord_schedule='+id_ord_schedule;
    var xhr = getXhr();
    xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;
			var val = leselect.split('##');

			if(val[0]==1){
				toastr.success('Margin successfully updated',{timeOut:15000})
				cancelIncreasedSP(id_proposal_calc,ship_sales_value_tone,margin_mt,id_ord_schedule);
				showCalcFormAndTable(id_ord_schedule,val[1],val[2],val[3]);
			} else 
			if(val[0]==0){
				toastr.error('Margin not updated',{timeOut:15000})
			} else {
				internal_error();
			}

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null)
}


function cancelIncreasedSP(id_proposal_calc,ship_sales_value_tone,margin_mt,id_ord_schedule) {
	$("#salesMTnewVal").addClass("hide");
	document.getElementById('edSalesMT').innerHTML = '<a href="#" onclick="reduiceSP(\''+id_proposal_calc+'\',\''+ship_sales_value_tone+'\',\''+margin_mt+'\',\''+id_ord_schedule+'\');" style="color:red;"><i class="fa fa-minus"></i></a>'
		+'&nbsp;&nbsp;<a href="#" onclick="increaseSP(\''+id_proposal_calc+'\',\''+ship_sales_value_tone+'\',\''+margin_mt+'\',\''+id_ord_schedule+'\');" style="color:green;"><i class="fa fa-plus"></i></a>';
}


function reduiceSP(id_proposal_calc,ship_sales_value_tone,margin_mt,id_ord_schedule) {
	$("#salesMTnewVal").removeClass("hide");
	document.getElementById('salesMTnewVal').value ="";
	document.getElementById('edSalesMT').innerHTML = '<a href="#" onclick="saveReduiceSP(\''+id_proposal_calc+'\',\''+ship_sales_value_tone+'\',\''+margin_mt+'\',\''+id_ord_schedule+'\');" style="color:blue;"><i class="fa fa-save"></i></a>'
		+'&nbsp;&nbsp;<a href="#" onclick="cancelReduiceSP(\''+id_proposal_calc+'\',\''+ship_sales_value_tone+'\',\''+margin_mt+'\',\''+id_ord_schedule+'\');" style="color:red;"><i class="fa fa-ban"></i></a>';
}


function saveReduiceSP(id_proposal_calc,ship_sales_value_tone,margin_mt,id_ord_schedule) {
	var new_sales_price = document.getElementById('salesMTnewVal').value; 
	var resurl='listeslies.php?elemid=save_reduiced_sp&id_proposal_calc='+id_proposal_calc+'&ship_sales_value_tone='+ship_sales_value_tone+'&new_sales_price='+new_sales_price+'&old_margin_mt='+margin_mt+'&id_ord_schedule='+id_ord_schedule;
    var xhr = getXhr();
    xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;
			var val = leselect.split('##');

			if(val[0]==1){
				toastr.success('Margin successfully updated',{timeOut:15000})
				cancelReduiceSP(id_proposal_calc,ship_sales_value_tone,margin_mt,id_ord_schedule);
				showCalcFormAndTable(id_ord_schedule,val[1],val[2],val[3]);
			} else 
			if(val[0]==0){
				toastr.error('Margin not updated',{timeOut:15000})
			} else {
				internal_error();
			}

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null)
}


function cancelReduiceSP(id_proposal_calc,ship_sales_value_tone,margin_mt,id_ord_schedule) {
	$("#salesMTnewVal").addClass("hide");
	document.getElementById('edSalesMT').innerHTML = '<a href="#" onclick="reduiceSP(\''+id_proposal_calc+'\',\''+ship_sales_value_tone+'\',\''+margin_mt+'\',\''+id_ord_schedule+'\');" style="color:red;"><i class="fa fa-minus"></i></a>'
		+'&nbsp;&nbsp;<a href="#" onclick="increaseSP(\''+id_proposal_calc+'\',\''+ship_sales_value_tone+'\',\''+margin_mt+'\',\''+id_ord_schedule+'\');" style="color:green;"><i class="fa fa-plus"></i></a>';
}

/*
*
* Order Confirmation
*
*/

/* Customer Reference Number */

function edit_order_confirmation(id_ord_schedule){
	$('#supRefNrManagBtn').removeClass("hide");
	$('#cusRefShipNrManagBtn').removeClass("hide");  
	$('#faRefNrManagBtn').removeClass("hide");  
	
	document.getElementById('showhideOrdConfirmEditBtn').innerHTML = '<button class="btn btn-danger pull-right" onclick="order_confirmation_cancelEditing('+id_ord_schedule+');" style="margin-top:10px;" type="button"><i class="fa fa-ban"></i></button>'
	 +'&nbsp;<button class="btn btn-success pull-right" onclick="save_order_confirmation('+id_ord_schedule+');" style="margin-top:10px; margin-right:15px;" type="button"><i class="fa fa-save"></i></button>';
	
	$('#offer_accepted_btn').prop("disabled", false);
	
	$('#create_contract_btn').prop("disabled", false);
	$('#puchase_order_fa_btn').prop("disabled", false);
	$('#puchase_order_supp_btn').prop("disabled", false);
	$('#puchase_order_supp_btn1').prop("disabled", false);
	$('#puchase_order_supp_btn2').prop("disabled", false);
}


function order_confirmation_cancelEditing(id_ord_schedule){
	$('#supRefNrManagBtn').addClass("hide");
	$('#cusRefShipNrManagBtn').addClass("hide");  
	$('#faRefNrManagBtn').addClass("hide");  
	
	document.getElementById('showhideOrdConfirmEditBtn').innerHTML = '<button class="btn btn-success pull-right" onclick="edit_order_confirmation('+id_ord_schedule+');" style="margin-top:10px;" type="button"><i class="fa fa-edit"></i></button>';
	
	$('#offer_accepted_btn').prop("disabled", true);
	
	$('#create_contract_btn').prop("disabled", true);
	$('#puchase_order_fa_btn').prop("disabled", true);
	$('#puchase_order_supp_btn').prop("disabled", true);
	$('#puchase_order_supp_btn1').prop("disabled", true);
	$('#puchase_order_supp_btn2').prop("disabled", true);
}


// Cancel Edition
function cancelEditcusRefShipNr(id_ord_schedule) {
	$('#cusRefShipNrShow').removeClass("hide");
	$('#cusRefShipNrInput').addClass("hide");
	
	document.getElementById("cusRefShipNrManagBtn").innerHTML = '<a herf="#" onclick="editCusRefShipNr('+id_ord_schedule+');" class="btn btn-white btn-sm"><i class="fa fa-edit"></i></a>';
}


// Show input
function editCusRefShipNr(id_ord_schedule) {
	$('#cusRefShipNrShow').addClass("hide");
	$('#cusRefShipNrInput').removeClass("hide");
	
	document.getElementById("cusRefShipNrManagBtn").innerHTML = '<a href="#" class="btn btn-white btn-sm" onclick="saveEditcusRefShipNr('+id_ord_schedule+');"><i class="fa fa-check" style="color:green;"></i></a>'+
		' <a href="#" class="btn btn-white btn-sm" onclick="cancelEditcusRefShipNr('+id_ord_schedule+');"><i class="fa fa-times" style="color:red;"></i></a>';
}


function save_order_confirmation(id_ord_schedule) {
	var resurl='listeslies.php?elemid=save_order_confirmation&id_ord_schedule='+id_ord_schedule;
    var xhr = getXhr();
    xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;

			if(leselect==1){
				toastr.success('Order confirmation saved successfully.',{timeOut:15000})
				order_confirmation_cancelEditing(id_ord_schedule);
				
			} else 
			if(leselect==0){
				toastr.error('Order confirmation Number not saved.',{timeOut:15000})
			} else {
				internal_error();
			}

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null)
}


// Save Edited
function saveEditcusRefShipNr(id_ord_schedule) {
	var customer_ref_ship_nr = document.getElementById("customer_ref_ship_nr").value;
	
	var resurl='listeslies.php?elemid=save_edited_customer_reference_nrd&id_ord_schedule='+id_ord_schedule+'&customer_ref_ship_nr='+customer_ref_ship_nr;
    var xhr = getXhr();
    xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;
            var val=leselect.split('##');

			if(val[0]==1){
				toastr.success('Customer Reference Number saved successfully.',{timeOut:15000})
				document.getElementById("cusRefShipNrShow").innerHTML = val[1];
				cancelEditcusRefShipNr(id_ord_schedule);
				
			} else 
			if(val[0]==0){
				toastr.error('Customer Reference Number not saved.',{timeOut:15000})
			} else {
				internal_error();
			}

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null)
}

/* Supplier Reference Number */

// Cancel Edition
function cancelEditsupRefNr(id_ord_schedule) {
	$('#supRefNrShow').removeClass("hide");
	$('#supRefNrInput').addClass("hide");
	
	document.getElementById("supRefNrManagBtn").innerHTML = '<a herf="#" onclick="editsupRefNr('+id_ord_schedule+');" class="btn btn-white btn-sm"><i class="fa fa-edit"></i></a>';
}


// Show input
function editsupRefNr(id_ord_schedule) {
	$('#supRefNrShow').addClass("hide");
	$('#supRefNrInput').removeClass("hide");
	
	document.getElementById("supRefNrManagBtn").innerHTML = '<a href="#" class="btn btn-white btn-sm" onclick="saveEditsupRefNr('+id_ord_schedule+');"><i class="fa fa-check" style="color:green;"></i></a>'+
		' <a href="#" class="btn btn-white btn-sm" onclick="cancelEditsupRefNr('+id_ord_schedule+');"><i class="fa fa-times" style="color:red;"></i></a>';
}

// Save Edited
function saveEditsupRefNr(id_ord_schedule) {
	var supplier_reference_nr = document.getElementById("supplier_reference_nr").value;
	
	var resurl='listeslies.php?elemid=save_edited_supplier_reference_nrd&id_ord_schedule='+id_ord_schedule+'&supplier_reference_nr='+supplier_reference_nr;
    var xhr = getXhr();
    xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;
            var val=leselect.split('##');

			if(val[0]==1){
				toastr.success('Supplier Reference Number saved successfully.',{timeOut:15000})
				document.getElementById("supRefNrShow").innerHTML = val[1];
				cancelEditsupRefNr(id_ord_schedule);
				
			} else 
			if(val[0]==0){
				toastr.error('Supplier Reference Number not saved.',{timeOut:15000})
			} else {
				internal_error();
			}

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null)
}

/* Freight Agent Reference Number */

// Cancel Edition
function cancelEditfaRefNr(id_ord_schedule) {
	$('#faRefNrShow').removeClass("hide");
	$('#faRefNrInput').addClass("hide");
	
	document.getElementById("faRefNrManagBtn").innerHTML = '<a herf="#" onclick="editfaRefNr('+id_ord_schedule+');" class="btn btn-white btn-sm"><i class="fa fa-edit"></i></a>';
}


// Show input
function editfaRefNr(id_ord_schedule) {
	$('#faRefNrShow').addClass("hide");
	$('#faRefNrInput').removeClass("hide");
	
	document.getElementById("faRefNrManagBtn").innerHTML = '<a href="#" class="btn btn-white btn-sm" onclick="saveEditfaRefNr('+id_ord_schedule+');"><i class="fa fa-check" style="color:green;"></i></a>'+
		' <a href="#" class="btn btn-white btn-sm" onclick="cancelEditfaRefNr('+id_ord_schedule+');"><i class="fa fa-times" style="color:red;"></i></a>';
}

// Save Edited
function saveEditfaRefNr(id_ord_schedule) {
	var fa_reference_nr = document.getElementById("fa_reference_nr_OC").value;
	
	var resurl='listeslies.php?elemid=save_edited_freight_agent_reference_nrd&id_ord_schedule='+id_ord_schedule+'&fa_reference_nr='+fa_reference_nr;
    var xhr = getXhr();
    xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;
            var val=leselect.split('##');

			if(val[0]==1){
				toastr.success('Freight Agent Reference Number saved successfully.',{timeOut:15000})
				document.getElementById("faRefNrShow").innerHTML = val[1];
				cancelEditfaRefNr(id_ord_schedule);
				
			} else 
			if(val[0]==0){
				toastr.error('Freight Agent Reference Number not saved.',{timeOut:15000})
			} else {
				internal_error();
			}

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null)
}


function saveCalcModBy(id_ord_schedule) {
	
	var resurl='listeslies.php?elemid=save_calculation_modify_by&id_ord_schedule='+id_ord_schedule; 
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;

			if(leselect == 1){
				toastr.success('Changes saved successfully',{timeOut:15000})
				cancelCalcVariables(id_ord_schedule);
			} else 
			if(leselect == 0){
				toastr.error('Changes not saved',{timeOut:15000})
			} else {
				internal_error();
			}
			
			leselect = xhr.responseText;
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}


function saveCalcVariables(id_proposal_calc,ord_order_id) { 
	
	var currency_id = document.getElementById('saveIdCurrency').value;
	var usd_chf = document.getElementById('saveIdUsdChf').value;
	var eur_chf = document.getElementById('saveIdEurChf').value;
	var usd_eur = document.getElementById('saveIdUsdEur').value;
	var margin = document.getElementById('saveIdMargin').value;
	
	if(currency_id == ""){ toastr.info('Select a currency',{timeOut:15000}) }
	if(usd_chf == ""){ toastr.info('Enter exchange rate US$/EUR',{timeOut:15000}) }
	if(eur_chf == ""){ toastr.info('Enter exchange rate EUR/CHF',{timeOut:15000}) }
	if(usd_eur == ""){ toastr.info('Enter exchange rate US$/EUR',{timeOut:15000}) }
	if(margin == ""){ toastr.info('Enter a Margin/MT',{timeOut:15000}) }
	
	if((currency_id!="")&&(usd_chf!="")&&(eur_chf!="")&&(usd_eur!="")&&(margin!="")){
		var resurl='listeslies.php?elemid=save_calculation_variables&id_proposal_calc='+id_proposal_calc+'&currency_id='+currency_id+'&usd_chf='+usd_chf+'&eur_chf='+eur_chf+'&usd_eur='+usd_eur+'&margin='+margin+'&ord_order_id='+ord_order_id; 
		var xhr = getXhr();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;

				if(leselect == 1){
					toastr.success('Variables successfully saved',{timeOut:15000})
					// cancelCalcVariables();
					
				} else 
				if(leselect == 0){
					toastr.error('Variables not saved',{timeOut:15000})
				} else {
					internal_error();
				}
				
				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
		
	} 
	
}


function showSalesForm(id_ord_schedule,order_ship_nr,ord_order_id,pipeline_id) {

	$('#proposal_list li').click(function() { 
		$('ul li.highlight_proposal').removeClass('highlight_proposal');
		$(this).closest('li').addClass('highlight_proposal');
	});
	
	// var mail = '<a href="#" class="pull-right" style="margin-left:10px; color:#fff;"  onclick="eMailForm(\''+ord_order_id+'\',\'crm\',\'\');"><i class="fa fa-envelope"></i></a>';
	
	// document.getElementById('summaryDocs').innerHTML = mail;
	// document.getElementById('requestDocs').innerHTML = '';
	
	document.getElementById('proposal_content').innerHTML = '<div class="h1 m-t-xs text-navy"><span class="loading"></span></div>';
	
	var resurl='listeslies.php?elemid=show_sales_form&id_ord_schedule='+id_ord_schedule+'&order_ship_nr='+order_ship_nr+'&pipeline_id='+pipeline_id;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;

			document.getElementById('proposal_content').innerHTML = leselect;
			
			if(proposal_update == 1){ $('#proposal_doc_toggle').removeClass('hide'); } else { $('#proposal_doc_toggle').addClass('hide'); }
	
			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function sales_pipeline(id_ord_schedule,order_ship_nr) {
	var resurl='listeslies.php?elemid=sales_pipeline&id_ord_schedule='+id_ord_schedule;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;
			var val = leselect.split('#');
			
			if(val[0] == 1){
				toastr.success('Pipeline changed successfully',{timeOut:15000})
				if(ordcon_read == 1){ 
					$("#crm_request_ordconfirm_tab").removeClass("hide"); 
					$("#crm_request_ordconfirm_ct").removeClass("hide");  
				} else { 
					$("#crm_request_ordconfirm_tab").addClass("hide"); 
					$("#crm_request_ordconfirm_ct").addClass("hide"); 
				}
				
				cancelProposalDocBtn(val[1],order_ship_nr);
				crm_manag(0,0);
			} else 
			if(val[0] == 0){
				toastr.error('Unable to change pipeline',{timeOut:15000})
			} else {
				internal_error();
			}
		
			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function showEditCalcLine(id_ord_calc_item,id_proposal_calc) {
	var resurl='listeslies.php?elemid=edit_calc_table_line&id_ord_calc_item='+id_ord_calc_item+'&id_proposal_calc='+id_proposal_calc;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;
			
			document.getElementById('calcTable').innerHTML = leselect;
			
			$('.i-checks').iCheck({
				checkboxClass: 'icheckbox_square-green',
				radioClass: 'iradio_square-green'
			});
			
			$('input').on('ifChanged', function(event){ calcActiveState($(event.target).val()); });
			
			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function calcActiveState(state) {
	
	var data = state.split('#');
	
	if(data[0] == 't'){
		var active = 1;
	} else {
		var active = 0;
	}
	
	var resurl='listeslies.php?elemid=save_active_state&id_ord_calc_item='+data[1]+'&active='+active;  
	var xhr = getXhr();  
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;   

			if(leselect == 1){
				toastr.success('Data updated successfully',{timeOut:15000})
			} else 
			if(leselect == 0){
				toastr.error('Unable to update data',{timeOut:15000})
			} else {
				internal_error();
			}
		
			showEditCalcLine(0,data[2]);
			
			leselect = xhr.responseText;
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}


function saveEditCalcLine(id_ord_calc_item,id_proposal_calc) {
	
	var req="";
	
	var cost_usd = document.getElementById("calc_cost_usd").value;
	if(cost_usd){ req=req+'&cost_usd='+cost_usd; }
	
	var cost_eur = document.getElementById("calc_cost_eur").value;
	if(cost_eur){ req=req+'&cost_eur='+cost_eur; }
	
	var cost_chf = document.getElementById("calc_cost_chf").value;
	if(cost_chf){ req=req+'&cost_chf='+cost_chf; }
	
	
	var resurl='listeslies.php?elemid=save_edited_calculation_item&id_ord_calc_item='+id_ord_calc_item+'&id_proposal_calc='+id_proposal_calc+req;
    var xhr = getXhr();  
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;

			if(leselect == 1){
				toastr.success('Data updated successfully',{timeOut:15000})
			} else
			if(leselect == 0){
				toastr.error('Unable to update data',{timeOut:15000})
			} else {
				internal_error();
			}
			
			showEditCalcLine(0,id_proposal_calc);

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function showCalcTable(id_proposal_calc,id_ord_schedule,last_shipment) {
	
	var margin_mt = document.getElementById("saveIdMargin").value;
	var eur_chf_exc_rate = document.getElementById("saveIdEurChf").value;
	var usd_chf_exc_rate = document.getElementById("saveIdUsdChf").value;
	var usd_eur_exc_rate = document.getElementById("saveIdUsdEur").value;
	var currency_id = document.getElementById("saveIdCurrency").value;
	
	if((margin_mt)&&(eur_chf_exc_rate)&&(usd_chf_exc_rate)&&(usd_eur_exc_rate)){
		var resurl='listeslies.php?elemid=save_edited_proposal_calc&id_proposal_calc='+id_proposal_calc+'&margin_mt='+margin_mt+'&eur_chf_exc_rate='+eur_chf_exc_rate+'&usd_chf_exc_rate='+usd_chf_exc_rate+'&usd_eur_exc_rate='+usd_eur_exc_rate+'&currency_id='+currency_id+'&id_ord_schedule='+id_ord_schedule+'&last_shipment='+last_shipment; 
		var xhr = getXhr();  
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText; 
				var val = leselect.split('##');

				if(val[0] == 1){
					toastr.success('Data updated successfully',{timeOut:15000})
					showCalcFormAndTable(id_ord_schedule,val[1],val[2],val[3]);
					
					if(last_shipment == 1){
						if(proposal_read == 1){ 
							$("#crm_request_proposal_tab").removeClass("hide"); 
							$("#crm_request_proposal_ct").removeClass("hide"); 
						} else { 
							$("#crm_request_proposal_tab").addClass("hide"); 
							$("#crm_request_proposal_ct").addClass("hide"); 
						}
						
					} else {
						$("#crm_request_proposal_tab").addClass("hide"); 
						$("#crm_request_proposal_ct").addClass("hide");
					}
					
				
				} else 
				if(val[0] == 0){
					toastr.error('Unable to update data',{timeOut:15000})
					
				} else {
					internal_error();
				}
				
				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
		
	} else {
		toastr.info('Fill empty values',{timeOut:15000})
	}
}



function new_req() {

	var resurl='listeslies.php?elemid=wizard_form1';
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;  

			document.getElementById('ord_cus_contact_id2').innerHTML = leselect;
			
			$("#data_5").addClass("hide");
			document.getElementById('package_type').value = 269;
			document.getElementById('product_type_w').value = 284;
			document.getElementById('nr_shipments').value = 1;
			document.getElementById('weight_unit').value = 21.50;

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
	
	var step1 = 0;
	var step2 = 0;
	var step3 = 0;
	var step4 = 0;
	
	
	// Toolbar extra buttons
    var btnFinish = $('<button></button>').text('Finish')
    .addClass('btn btn-info hide btn-finish')
    .on('click', function(){ 
        if( !$(this).hasClass('disabled')){ 
            var elmForm = $("#myForm");
            if(elmForm){
                elmForm.validator('validate'); 
                var elmErr = elmForm.find('.has-error');
                if(elmErr && elmErr.length > 0){
                    alert('Oops we still have error in the form');
                    return false;    
                } else {
					
                    var order_infos = document.getElementById("order_infos").value;   
					var notes = document.getElementById("notes").value;				
					
					var resurl='listeslies.php?elemid=savenote&order_infos='+order_infos+'&notes='+notes;    
					var xhr = getXhr();
					xhr.onreadystatechange = function(){
						if(xhr.readyState == 4 ){
							leselect = xhr.responseText;    
							var val = leselect.split('##');
							if(val[0]==1){
								toastr.success(val[1],{timeOut:15000})
								
								crm_manag(0,0);
								document.getElementById("myForm").reset();
								
								$('#smartwizard').smartWizard("reset"); 
								$('#myForm').find("input, textarea, select").val(""); 
								$('#order_client').addClass('hide');
								// $('#selport').removeClass('hide');
								
								var step1 = 0;
								var step2 = 0;
								var step3 = 0;
								var step4 = 0;
		
								$("#wizardModal").modal("hide");
								
								var bcc="";  
								saveMailAsPdf(val[2],val[3],val[4],'system',val[5],val[6],val[2],val[8],bcc,val[7]);
								
							} else {
								toastr.error(val[1],{timeOut:15000})
							}
					
							leselect = xhr.responseText;
						}
					};

					xhr.open("GET",resurl,true);
					xhr.send(null);
				
                    return false;
                }
            }
        }
    });

	var btnCancel = $('<button></button>').text('Cancel')
    .addClass('btn btn-danger')
    .on('click', function(){ 

		var ord_order_id = document.getElementById("last_inserted_order_id").value;
		
		var resurl='listeslies.php?elemid=cancel_wizard&ord_order_id='+ord_order_id; 
		var xhr = getXhr();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;  

				if(leselect == 1){
					toastr.success('Record successfully deleted',{timeOut:15000}) 
					
				} else
				if(leselect == 0) {
					toastr.error('No record deleted',{timeOut:15000})
					
				} else {}
	
				$('#smartwizard').smartWizard("reset"); 
				$('#myForm').find("input, textarea, select").val(""); 
				$('#order_client').addClass('hide');
				// $('#selport').removeClass('hide');
		
				var step1 = 0;
				var step2 = 0;
				var step3 = 0;
				var step4 = 0;
				
				$("#wizardModal").modal("hide");
		
				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
		
		return false;
    });                         

	
    // Smart Wizard
    $('#smartwizard').smartWizard({ 
        selected: 0, 
        theme: 'dots',
        transitionEffect:'fade',
        toolbarSettings: {toolbarPosition: 'bottom',
            toolbarExtraButtons: [btnFinish, btnCancel]
        },
        anchorSettings: {
			markDoneStep: true, // add done css
            markAllPreviousStepsAsDone: true, // When a step selected by url hash, all previous steps are marked done
            removeDoneStepOnNavigateBack: true, // While navigate back done step after active step will be cleared
            enableAnchorOnDoneStep: true // Enable/Disable the done steps navigation
        }
    });
  
    $("#smartwizard").on("leaveStep", function(e, anchorObject, stepNumber, stepDirection) {
        var elmForm = $("#form-step-" + stepNumber);
        // stepDirection === 'forward' :- this condition allows to do the form validation 
        // only on forward navigation, that makes easy navigation on backwards still do the validation when going next
        if(stepDirection === 'forward' && elmForm){
            elmForm.validator('validate'); 
			if ($("#form-step-" + stepNumber).find(".has-error").length > 0){ 
				return false;
			}
        }
        return true;
    });
    
    $("#smartwizard").on("showStep", function(e, anchorObject, stepNumber, stepDirection) {
		
		if(stepNumber == 1){
			
			$('.sw-btn-prev').addClass("disabled");
			
			var status_id = 229;
			var pipeline_id = 293;
			var req='&status_id='+status_id+'&pipeline_id='+pipeline_id;
			
			var ord_cus_contact_id = $("[name=ord_cus_contact_id]").val();  
			if(ord_cus_contact_id){ req=req+'&ord_cus_contact_id='+ord_cus_contact_id; }
		
			var ord_cus_person_id = document.getElementById("ord_cus_person_id").value;  
			if(ord_cus_person_id){ req=req+'&ord_cus_person_id='+ord_cus_person_id; }
			
			var customer_reference_nr = document.getElementById("customer_reference_nr").value; 
			if(customer_reference_nr){ req=req+'&customer_reference_nr='+customer_reference_nr; }	
		
			var order_incoterms_id = document.getElementById("order_incoterms_id").value;  
			if(order_incoterms_id){ req=req+'&order_incoterms_id='+order_incoterms_id; }
			
			var port_id_code = document.getElementById("port_id_code").value;  
			if(port_id_code){ var idcode = port_id_code.split(',');
				var port_id = idcode[0]; 
				var port_code = idcode[1]; 
				req=req+'&port_id='+port_id+'&port_code='+port_code;
			} else { port_id = ''; port_code = ''; }
			
			var package_type_id = document.getElementById("package_type").value;
			if(package_type_id){ req=req+'&package_type_id='+package_type_id; }
			
			var nr_shipments = document.getElementById("nr_shipments").value;
			if(nr_shipments){ req=req+'&nr_shipments='+nr_shipments; }
			
			var delivery_date = document.getElementById("delivery_date").value;
			if(delivery_date){ req=req+'&delivery_date='+delivery_date; }
			
			var delivery_week = document.getElementById("delivery_week").value;
			if(delivery_week){ req=req+'&delivery_week='+delivery_week; }
			
			var delivery_date2 = document.getElementById("delivery_date2").value;
			if(delivery_date2){ req=req+'&delivery_date2='+delivery_date2; }
			
			var delivery_week2 = document.getElementById("delivery_week2").value;
			if(delivery_week2){ req=req+'&delivery_week2='+delivery_week2; }
			
			var id_supchain_type = document.getElementById("id_supchain_type").value;
			if(id_supchain_type != 110){ 
				var ord_cus_person_id2 = document.getElementById("ord_cus_person_id2").value;
				if(ord_cus_person_id2){ req=req+'&ord_cus_person_id2='+ord_cus_person_id2; }
			}
		
			var product_type = document.getElementById("product_type_w").value;
			
			if(step1==0){
				var resurl='listeslies.php?elemid=product_saveorder&product_type='+product_type+req;    
				var xhr = getXhr();
				xhr.onreadystatechange = function(){
					if(xhr.readyState == 4 ){
						leselect = xhr.responseText;         
						var val = leselect.split('##');
		
						if(val[0]==1){ 
							step1 = 1;
							
							if(package_type_id == 269){  
								document.getElementById("weight_unit").value = 22;
							}
							
							toastr.success('Order saved successfully',{timeOut:15000}) 
					
						} else
						if(val[0]==3) { 
							toastr.info('Order already in database',{timeOut:15000})
			
						} else {
							toastr.error('Order not saved',{timeOut:15000})
						}
						
						document.getElementById('id_product').innerHTML = val[1];
						document.getElementById('order_infos').value = val[2];
						document.getElementById("last_inserted_order_id").value = val[3];   
						
						leselect = xhr.responseText;
					}
				};

				xhr.open("GET",resurl,true);
				xhr.send(null);
			}
		}
		
		if(stepNumber == 2){
			var req='';
			$('.sw-btn-prev').addClass("disabled");
			
			var id_product_code = document.getElementById("id_product").value;
			if(id_product_code){ var idcode = id_product_code.split(',');
				var id_product = idcode[0]; 
				var product_code = idcode[1]; 
				req=req+'&id_product='+id_product+'&product_code='+product_code;
			} else { id_product = ''; product_code = ''; }
		
			var measure_unit = document.getElementById("measure_unit").value;
			if(measure_unit){ req=req+'&measure_unit='+measure_unit; }
		
			var product_quantity = document.getElementById("product_quantity").value;
			if(product_quantity){ req=req+'&product_quantity='+product_quantity; }
			
			var package_type_id = document.getElementById("package_type").value;
			if(package_type_id){ req=req+'&package_type_id='+package_type_id; }
			
			var weight_total = document.getElementById("weight_total").value;
			if(weight_total){ req=req+'&weight_total='+weight_total; }
		
			var weight_unit = document.getElementById("weight_unit").value;
			if(weight_unit){ req=req+'&weight_unit='+weight_unit; }
		
			var order_infos = document.getElementById("order_infos").value;
			
			var nr_shipments = document.getElementById("nr_shipments").value;
			if(nr_shipments){ req=req+'&nr_shipments='+nr_shipments; }
			
			var delivery_date = document.getElementById("delivery_date").value;
			if(delivery_date){ req=req+'&delivery_date='+delivery_date; }
			
			var delivery_week = document.getElementById("delivery_week").value;
			if(delivery_week){ req=req+'&delivery_week='+delivery_week; }
			
			var delivery_date2 = document.getElementById("delivery_date2").value;
			if(delivery_date2){ req=req+'&delivery_date2='+delivery_date2; }
			
			var delivery_week2 = document.getElementById("delivery_week2").value;
			if(delivery_week2){ req=req+'&delivery_week2='+delivery_week2; }
			
			var port_id_code = document.getElementById("port_id_code").value;  
			if(port_id_code){ var idcode = port_id_code.split(',');
				var port_id = idcode[0]; 
				req=req+'&pod_id='+port_id;
			} 
			
			var order_incoterms_id = document.getElementById("order_incoterms_id").value;  
			if(order_incoterms_id){ req=req+'&cus_incoterms_id='+order_incoterms_id; }
			
			if(step2==0){
				
				$("#gridSheduleSpanner").removeClass("hide");
				
				var resurl='listeslies.php?elemid=saveproduct_oceanschedule&order_infos='+order_infos+req;      
				var xhr = getXhr();  
				xhr.onreadystatechange = function(){
					if(xhr.readyState == 4 ){
						leselect = xhr.responseText;       
						var val = leselect.split('#');

						var glist = '';
						
						if(val[0]=='zzz'){
							toastr.info('Order not saved cheick your internet connection and try again.',{timeOut:20000})
							glist = '<th colspan="5"><i class="fa fa-exclamation-triangle"></i> Order not saved cheick your internet connection.</th>';
						} else {
							if((val[0]==1)&&(val[1]==1)){
								step2 = 1;
								document.getElementById('wizShedTh').innerHTML = val[4];
								toastr.success('Ocean chedule saved successfully',{timeOut:15000})
								
							} else {
								toastr.error('Ocean chedule not saved',{timeOut:15000})
							}
						
							glist = val[2];
						}
				
						document.getElementById('listGrid').innerHTML = glist;
						document.getElementById('req_saved_importer').innerHTML = val[3];

						$("#gridSheduleSpanner").addClass("hide");
						
						$('.edit_delivery_date').datepicker({
							format: "yyyy-mm-dd",
							calendarWeeks:true,
							autoclose: true
						});
						
						leselect = xhr.responseText;
					}
				};

				xhr.open("GET",resurl,true);
				xhr.send(null);
			}
		}
		
		if(stepNumber == 2){
			
		}
		
        // Enable finish button only on last step
        if(stepNumber == 3){ 
            $('.btn-finish').removeClass('hide');  
        }else{
            $('.btn-finish').addClass('hide');
        }
    });
	

	$('#delivery_date').datepicker({
		format: "yyyy/mm/dd",
		calendarWeeks:true,
		autoclose: true
	});
	
	$('#delivery_date2').datepicker({
		format: "yyyy/mm/dd",
		calendarWeeks:true,
		autoclose: true
	});
}


function userInRoleP() {
	$("#usersRpannerP").removeClass("hide");
	$("#usersRpannerUP").removeClass("hide");

	var id_role = document.getElementById('PermissionsIdRole').value;
	var resurl='listeslies.php?elemid=usersInrole&id_role='+id_role+'&typ=permission';
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;
			var val = leselect.split('##');

			document.getElementById('list_usersIR_P').innerHTML = val[0];
			document.getElementById('selectedRoleP').innerHTML = val[1];
			document.getElementById('list_users_permission').innerHTML = val[2]; 

			$("#usersRpannerP").addClass("hide");
			$("#usersRpannerUP").addClass("hide");

			$('.i-checks').iCheck({
				checkboxClass: 'icheckbox_square-green',
				radioClass: 'iradio_square-green'
			});

			$('input').on('ifChanged', function(event){ changePermission($(event.target).val()); });
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function changePermission(value) {
	var elmt = value.split('#');

	if(elmt[2]==1){ var val = 0; } else { var val = 1; }

	var resurl='listeslies.php?elemid=change_permission&id_permission='+elmt[0]+'&type='+elmt[1]+'&valeur='+val;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;

			if(leselect == 1){
				toastr.success('Permission updated successfully',{timeOut:15000})
			} else 
			if(leselect == 0){
				toastr.error('Error updating permission, please retry',{timeOut:15000})
			} else {
				internal_error();
			}
			userInRoleP();

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function objectInRole(id_role) {
	$("#objectRpanner").removeClass("hide");

	var resurl='listeslies.php?elemid=objectsInrole&id_role='+id_role+'&typ=object&editRight='+sysRoleDef_update;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;
			var val = leselect.split('##');

			document.getElementById('list_objectIR').innerHTML = val[0];
			document.getElementById('selectedRoleDef').innerHTML = val[1];
			document.getElementById('list_objectR').innerHTML = val[2];

			$("#objectRpanner").addClass("hide");

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function regCostList(id_townport) {
	$("#portspanner").removeClass("hide");

	var resurl='listeslies.php?elemid=reg_cost_list&id_townport='+id_townport+'&assign_port='+sysPortCostAssign_update;  
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;
			var val = leselect.split('##');

			document.getElementById('regCostList').innerHTML = val[0];
			document.getElementById('selectedPortID').innerHTML = val[1];
			document.getElementById('portCosts').innerHTML = val[2];

			$("#portspanner").addClass("hide");

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}



function addToCostPort(id_reg_cost) {
	var id_townport = '';

	if($("input[type='radio'].radioBtnPortClass").is(':checked')) {
		id_townport = $("input[type='radio'].radioBtnPortClass:checked").val();
	}

	if(id_townport == ""){
		toastr.info('Select a port.',{timeOut:15000})

	} else {
		var resurl='listeslies.php?elemid=add_cost_to_port&id_townport='+id_townport+'&id_reg_cost='+id_reg_cost;
		var xhr = getXhr();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;

				if(leselect == 1){
					toastr.success('Reg cost added successfully',{timeOut:15000})
					attachedToPort(id_townport);
				} else 
				if(leselect == 0){
					toastr.error('Error adding reg cost to port, please retry',{timeOut:15000})
				} else {
					internal_error();
				}

				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
	}
}



function removeCostFromPort(id_reg_cost) {
	var id_townport = '';

	if($("input[type='radio'].radioBtnPortClass").is(':checked')) {
		id_townport = $("input[type='radio'].radioBtnPortClass:checked").val();
	}

	if(id_townport == ""){
		toastr.info('Select a port.',{timeOut:15000})

	} else {
		var resurl='listeslies.php?elemid=remove_cost_from_port&id_townport='+id_townport+'&id_reg_cost='+id_reg_cost;
		var xhr = getXhr();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;

				if(leselect == 1){
					toastr.success('Cost removed successfully',{timeOut:15000})
					attachedToPort(id_townport);
				} else 
				if(leselect == 0){
					toastr.error('Cost not removed, please retry',{timeOut:15000})
				} else {
					internal_error();
				}

				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
	}
}


function attachedToPort(id_townport) {
	var resurl='listeslies.php?elemid=attached_port&id_townport='+id_townport+'&assign_port='+sysPortCostAssign_update;
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;
			var val = leselect.split('##');

			document.getElementById('portCosts').innerHTML = val[0];
			document.getElementById('regCostList').innerHTML = val[1];
	
			leselect = xhr.responseText;
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}



function userInRole(id_role) {
	$("#usersRpanner").removeClass("hide");

	var resurl='listeslies.php?elemid=usersInrole&id_role='+id_role+'&typ=role&editRight='+sysRoleAssign_update;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;
			var val = leselect.split('##');

			document.getElementById('list_usersIR').innerHTML = val[0];
			document.getElementById('selectedRole').innerHTML = val[1];
			
			document.getElementById('list_roles_def').innerHTML = val[3];
			document.getElementById('selectedRole_def').innerHTML = val[1];
			
			document.getElementById('list_usersR').innerHTML = val[4];

			$("#usersRpanner").addClass("hide");

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function users() {
	hideAll();

	$("#db_users").removeClass("hide");
	titleMenuManag("Users management","btn_user");
	
	// var role = document.getElementById('rolesList').value;
	// if(role!=""){ var id_role = role; } else { var id_role = 0; }
	var id_role = 0;
	
	if(sysUserManag_create == 1){
		document.getElementById('userTopbtn').innerHTML = '<button type="button" id="sysNewUser" onclick="manageUser(\'add\');" class="btn btn-success btn-sm pull-right" ><i class="fa fa-plus"></i> New</button>';
	}
	
	$("#userCList").css('border-right', 'none');
	$("#userCList").addClass("col-lg-12");
	$("#userCList").removeClass("col-lg-8");

	$("#newUserform").addClass("hide");
	$("#editUserRoleform").addClass("hide");
	$("#userspanner").removeClass("hide");

	var resurl='listeslies.php?elemid=users_management&id_role='+id_role+'&editRight='+sysUserManag_update+'&deleteRight='+sysUserManag_delete+'&createRight='+sysUserManag_create;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;
			var val = leselect.split('##');

			document.getElementById('list_usersC').innerHTML = val[0];
			$("#userspanner").addClass("hide");

			document.getElementById('notYet_usersC').innerHTML = val[1];

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function userRoleForm(firstname,lastname,id_user) {
	
	$("#saveRoleBtn").prop("disabled", true);
	
	document.getElementById('userTopbtn').innerHTML = '<button type="button" id="sysNewUser" onclick="users();" style="margin-right:15px;" class="btn btn-danger btn-sm pull-right" ><i class="fa fa-ban"></i></button>';
	document.getElementById('userRoleInfos').innerHTML = firstname+'<br/>'+lastname;
	
	$("#userCList").css('border-right', '1px solid #e7eaec');
	$("#userCList").removeClass("col-lg-12");
	$("#userCList").addClass("col-lg-8");

	$("#newUserform").addClass("hide");
	$("#editUserRoleform").removeClass("hide");
	
	RoleList(id_user);
}


function RoleList(id_user) {
	var resurl='listeslies.php?elemid=role_list&id_user='+id_user;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;
			var val = leselect.split('##');

			document.getElementById('userRoleList').innerHTML = val[0];
			document.getElementById('userRole_value').value = val[1];
			document.getElementById('userRole_idUser').value = id_user;
	
			$("#saveRoleBtn").prop("disabled", false);
			
			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function updateUserRole(id_user) {
	
	var id_role = document.getElementById('userRoleList').value;
	var id_user = document.getElementById('userRole_idUser').value;
	var conf = document.getElementById('userRole_value').value;
	
	var resurl='listeslies.php?elemid=update_user_role&id_user='+id_user+'&id_role='+id_role+'&conf='+conf;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;

			if(conf=="edit"){
				if(leselect==1){ var reponse ="Role successfully updated."; }
				else { var reponse ="Role not updated."; }
			} else
			if(conf=="add"){
				if(leselect==1){ var reponse ="Role successfully added."; }
				else { var reponse ="Role not added."; }
			}
			
			if(leselect==1){ toastr.success(reponse,{timeOut:15000}) }
			else { toastr.error(reponse,{timeOut:15000}) }
			
			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function manageUser(conf) {
	document.getElementById('userTopbtn').innerHTML = '<button type="button" id="sysNewUser" onclick="users();" style="margin-right:15px;" class="btn btn-danger btn-sm pull-right" ><i class="fa fa-ban"></i></button>';

	$("#userCList").css('border-right', '1px solid #e7eaec');
	$("#userCList").removeClass("col-lg-12");
	$("#userCList").addClass("col-lg-8");

	$("#newUserform").removeClass("hide");
	$("#editUserRoleform").addClass("hide");
}


function agentm() {
	hideAll();

	$("#db_agentm").removeClass("hide");
	titleMenuManag("Agent Tracking","btn_amgt");
	
	var agent_id = document.getElementById('agentm_agents').value;
	var id_contact = document.getElementById('agentm_farmer').value;
	var id_town = document.getElementById('agentm_town').value;
	var limit = document.getElementById('agentm_sel').value;
	
	// if(value == 100){
		// document.getElementById("agentm_sel").selectedIndex = "0";
	// }
	
	var spinner = '<div class="h1 m-t-xs text-navy"><span class="loading"></span></div>';
	document.getElementById('list_management').innerHTML = spinner;
	
	agent_couche.clearLayers();

	var resurl='listeslies.php?elemid=agent_management&limit='+limit+'&agent_id='+agent_id+'&id_contact='+id_contact+'&id_town='+id_town;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;    
			var list = leselect.split('**');

			document.getElementById('list_management').innerHTML = list[0];
			document.getElementById('agentm_agents').innerHTML = list[2];
			document.getElementById('agentm_farmer').innerHTML = list[3];
			document.getElementById('agentm_town').innerHTML = list[4];

			var val = list[1].split('??');

			i = 0;
			while (val[i] != 'end') {
				var val2=val[i].split('#');

				if((val2[0]!="")&&(val2[1]!="")) {
					var mark = L.marker([val2[0], val2[1]], {icon: agentIcon,riseOnHover:true}).addTo(agent_couche);
					map3.addLayer(agent_couche);
				}
				
				i += 1;
			}

			map3.fitBounds(agent_couche.getBounds());
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


/* ----- START - CALENDAR ----- */

function calendar(value) {
	hideAll();

	$("#db_calendar").removeClass("hide");
	titleMenuManag("Project Calendar","btn_cal");

	var prev = null;
	var curr = null;
	var next = null;

	var data = [];
	
	document.getElementById('calendar').innerHTML = '<div class="h1 m-t-xs text-navy"><span class="loading"></span></div>'; 
	
	var req="";
	if(value!=0){ req='&agent_id='+value; }
	
	var resurl='include/calendar.php?elemid=project_calendar'+req; 
	var xhr = getXhr();  
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;    
			var val = leselect.split('##');  
			
			if(val[1] == ""){
				document.getElementById('calendar').innerHTML = '<i class="fa fa-exclamation-triangle"></i> No data'; 
			} else {
				document.getElementById('calendar').innerHTML = val[0]; 
				document.getElementById('calendar_agent').innerHTML = val[2]; 
				
				var sched_val = val[1].split('??');  
				
				i=0;
				while (sched_val[i]) {  
					var val1 = sched_val[i].split('|');
					data.push({"id":val1[0], "start_date":val1[2], "end_date":val1[3], "text":val1[1], "details":val1[4]});
					i +=1;
				}
				
				scheduler.config.multi_day = true;
				scheduler.config.xml_date="%Y-%m-%d %H:%i";
				scheduler.init('scheduler_calendar',new Date(),"week"); 
				scheduler.clearAll();				
				scheduler.parse(data, "json");  
				
				var calendar = scheduler.renderCalendar({
					container:"cal_here", 
					navigation:true,
					handler:function(date){
						scheduler.setCurrentView(date, scheduler._mode);
					}
				});
				scheduler.linkCalendar(calendar);
				scheduler.setCurrentView(scheduler._date, scheduler._mode);
				
				var dp = new dataProcessor("https://icoop.live/ic/dhtmlx/scheduler/scheduler_calendar.php");
				dp.init(scheduler);
				
			}
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}

/* ----- END - CALENDAR ----- */



/* ----- START - TIME LINE ----- */

function timeLine() {
	hideAll();

	$("#db_timeLine").removeClass("hide");
	titleMenuManag("Project TimeLine","btn_time");
	
	showTMPjMenu(0);
	showCompleteTimeLine();
	
	var resurl='include/projects.php?elemid=projects_list';
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;
			var val = leselect.split('##');

			document.getElementById('projectTime_list').innerHTML = val[1]; 
			
			var options = {
				valueNames: ['project_time_reference_nr']
			};

			var projectList = new List('projectsTime', options);

			$("#projectTimeLineSpanner").addClass("hide");
			
			$('#projectTime_list li a').click(function() {
				$('ul li.on').removeClass('on');
				$(this).closest('li').addClass('on');
			});

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);	
}


function showCompleteTimeLine() {
	
	var data = [];
	var elements = [];
	
	$("#TM_agent_view_toggler").addClass("hide");
	$("#TM_agent_view_toggler2").removeClass("hide");
	
	var resurl='include/calendar.php?elemid=project_timeline'; 
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;   
			var val = leselect.split('@@');
			
			if(val[1]!="" && val[0]!=""){
				scheduler.locale.labels.timeline_tab = "Timeline";
				scheduler.locale.labels.section_custom="Section";
				scheduler.config.details_on_create=true;
				scheduler.config.details_on_dblclick=true;
				scheduler.config.xml_date="%Y-%m-%d %H:%i";
				
				i=0;
				var project = val[0].split('??');  
				while (project[i]!= 'end') { 
					var children = [];				
					var pj = project[i].split('|');
					elements.push({key:pj[0], label:pj[1], open:true, children: children});
					
					e=0;
					var agents = val[2].split('??');  
					while (agents[e]!= 'end') {  
						var ag = agents[e].split('|');
						if(pj[0]==ag[1]){
							children.push({key:ag[0], label:ag[2]});
						}
						e += 1;
					}
				
					i += 1;
				}
				
				scheduler.createTimelineView({
					section_autoheight: false,
					name:	"timeline",
					x_unit:	"minute",
					x_date:	"%H:%i",
					x_step:	30,
					x_size: 24,
					x_start: 16,
					x_length:	48,
					y_unit: elements,
					y_property:	"agent_id",
					render: "tree",
					folder_dy:30,
					dy:60
				});
				
				scheduler.config.lightbox.sections=[	
					{name:"description", height:50, map_to:"text", type:"textarea" , focus:true},
					{name:"custom", height:30, type:"timeline", options:null , map_to:"agent_id" }, //type should be the same as name of the tab
					{name:"time", height:72, type:"time", map_to:"auto"}
				];
				
				scheduler.init('scheduler_timeline',new Date(),"timeline");
				scheduler.clearAll();

				scheduler.load("https://icoop.live/ic/dhtmlx/scheduler/scheduler_complete.php");
		
				var dp = new dataProcessor("https://icoop.live/ic/dhtmlx/scheduler/scheduler_complete.php");
				dp.init(scheduler);
			}
			
			leselect = xhr.responseText;
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}


function hideTMPjMenu2() {
	document.getElementById('TM_agent_view_toggler2').innerHTML = '<a href="#" onclick="showTMPjMenu2();"><i class="fa fa-compress"></i></a>';
	showCompleteTimeLine();
	
	$("#TM_project_box").addClass("hide");
	$(".dhx_cal_tab").removeClass("hide");
	
	$("#TM_box").removeClass("col-md-9 col-sm-8 col-xs-8");
	$("#TM_box").addClass("col-md-12 col-sm-12 col-xs-12");
}


function showTMPjMenu2() {
	document.getElementById('TM_agent_view_toggler2').innerHTML = '<a href="#" onclick="hideTMPjMenu2();"><i class="fa fa-expand"></i></a>';
	showCompleteTimeLine();
	
	$("#TM_project_box").removeClass("hide");
	$(".dhx_cal_tab").addClass("hide");
	
	$("#TM_box").removeClass("col-md-12 col-sm-12 col-xs-12");
	$("#TM_box").addClass("col-md-9 col-sm-8 col-xs-8");
}


function showProjectTimeLine(id_project) { 
	// $("#TimeBox").addClass("hide"); 
	document.getElementById('selected_TL_projet_id').value = id_project;
	
	var resurl='include/projects.php?elemid=project_timeline_agents&id_project='+id_project; 
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;   
			
			document.getElementById('TM_agent_selector_content').innerHTML = leselect;  
			showAgentTimeLine(0,id_project);
			
			leselect = xhr.responseText;
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}


function showAgentTimeLine(agent_id,id_project) {
	
	$("#TM_agent_view_toggler").removeClass("hide");
	$("#TM_agent_view_toggler2").addClass("hide");
	
	document.getElementById('selected_TL_agent_id').value = agent_id;

	scheduler.locale.labels.timeline_tab = "Timeline";
	scheduler.locale.labels.section_custom="Section";
	scheduler.config.details_on_create=true;
	scheduler.config.details_on_dblclick=true;
	scheduler.config.xml_date="%Y-%m-%d %H:%i";

	// timeline config
	scheduler.attachEvent("onTemplatesReady", function(){
		scheduler.xy.scale_height = 50;
	});

	scheduler.load("https://icoop.live/ic/dhtmlx/scheduler/scheduler_connector.php?agent_id="+agent_id+"&id_project="+id_project);
	
	var view_name = "timeline";
	scheduler.createTimelineView({
		name: view_name,
		x_unit: "day",
		x_date: "%D<br><b>%j</b>",
		x_step: 1,
		x_size: 31,
		section_autoheight: false,
		y_unit:   scheduler.serverList("sections"),
		y_property: "agent_id",
		render:"bar",
		round_position:true,
		dy:60
	});

	scheduler.attachEvent("onBeforeViewChange", function(old_mode,old_date,mode,date){
		var year = date.getFullYear();
		var month= (date.getMonth() + 1);
		var d = new Date(year, month, 0);
		scheduler.matrix[view_name].x_size = d.getDate();//number of days in month;
		return true;
	});
	
	scheduler.date['add_' + view_name] = function(date, step){
		if(step > 0){
			step = 1;
		}else if(step < 0){
			step = -1;
		}
		return scheduler.date.add(date, step, "month")
	};

	scheduler.date[view_name + '_start'] = scheduler.date.month_start;

	scheduler.config.lightbox.sections=[
		{name:"description", height:130, map_to:"text", type:"textarea" , focus:true},
		{name:"custom", height:23, type:"select", options:scheduler.serverList("sections"), map_to:"agent_id" },
		{name:"time", height:72, type:"time", map_to:"auto"}
	];

	scheduler.init('scheduler_timeline',new Date(),"timeline");
	scheduler.clearAll();
	
	var dp = new dataProcessor("https://icoop.live/ic/dhtmlx/scheduler/scheduler_connector.php?agent_id="+agent_id+"&id_project="+id_project);  
	dp.init(scheduler);
}


function hideTMPjMenu(cond) {
	document.getElementById('TM_agent_view_toggler').innerHTML = '<a href="#" onclick="showTMPjMenu(1);"><i class="fa fa-compress"></i></a>';
	$("#TM_project_box").addClass("hide");
	
	$("#TM_box").removeClass("col-md-9 col-sm-8 col-xs-8");
	$("#TM_box").addClass("col-md-12 col-sm-12 col-xs-12");
	
	if(cond==1){
		var id_project = document.getElementById('selected_TL_projet_id').value;
		var agent_id = document.getElementById('selected_TL_agent_id').value;
		showAgentTimeLine(agent_id,id_project);
		
		$(".dhx_cal_tab").removeClass("hide");
	}
}

function showTMPjMenu(cond) {
	document.getElementById('TM_agent_view_toggler').innerHTML = '<a href="#" onclick="hideTMPjMenu(1);"><i class="fa fa-expand"></i></a>';
	$("#TM_project_box").removeClass("hide");
	
	$("#TM_box").removeClass("col-md-12 col-sm-12 col-xs-12");
	$("#TM_box").addClass("col-md-9 col-sm-8 col-xs-8");
	
	if(cond==1){
		var id_project = document.getElementById('selected_TL_projet_id').value;
		var agent_id = document.getElementById('selected_TL_agent_id').value;
		showAgentTimeLine(agent_id,id_project);
		
		$(".dhx_cal_tab").addClass("hide");
	}
}


/* ----- END - TIME LINE ----- */


function dataCollection() {
	hideAll();

	$("#db_data_collection").removeClass("hide");
	$("#dtCollectSpanner").removeClass("hide");
	
	titleMenuManag("Data Collection","btn_collectDt");
	
	var resurl='include/data_collection.php?elemid=project_list';
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;    
			
			document.getElementById('dt_collect_content').innerHTML = leselect;

			var options = {
				valueNames: ['dt_collect_projectName']
			};

			var userList = new List('dtCollect_Projects', options);

			$('#dt_collect_content li a').click(function() {
				$('ul li.on').removeClass('on');
				$(this).closest('li').addClass('on');
			});

			$("#dtCollectSpanner").addClass("hide");
			
			dtCollect_agents(0);
			dtCollect_agentData(0, 0);
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function dtCollect_agents(id_project) {

	$("#dtCollect_agtVisit").addClass("hide");
	$("#dt_collect_agt_data").removeClass("hide");
	document.getElementById('dt_collect_agt_data').innerHTML = '<span style="font-size: 14px;"><i class="fas fa-hand-point-left"></i> '+lg_sel_project_in_list+'</span>';
	
	dtCollect_agentData(id_project, 0);
	
	var resurl='include/data_collection.php?elemid=agent_list&id_project='+id_project;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;    
			
			document.getElementById('dt_collect_agt_content').innerHTML = leselect;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function dtCollect_agentData(id_project, agent_id) {
	
	$("#fmrVisit_map").addClass("hide");
	$("#dt_collect_agtVisit_data").removeClass("hide");
	
	document.getElementById('dt_collect_agt_data').innerHTML = '<div id="dtCollectSpanner" class="h1 m-t-xs text-navy"><span class="loading"></span></div>';
	
	
	var resurl='include/data_collection.php?elemid=agent_data&id_project='+id_project;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;    

			$('#dtCollect_Content').removeClass('hide');
			
			document.getElementById('dt_collect_agt_data').innerHTML = leselect;
			
			agentVisits(agent_id);			
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


var overlayMaps_fmrVisit_map = { };

L.control.layers(baseMaps_fmrVisit, overlayMaps_fmrVisit_map).addTo(fmrVisit_map);

function farmerOnMap(id_plantation) {

	plantation_project_couche.clearLayers();
	plantation_project_points.clearLayers();
	
	$("#dt_collect_agtVisit_data").addClass("hide");
	$("#fmrVisit_map").removeClass("hide");
	
	var resurl='include/data_collection.php?elemid=farmer_plantations&id_plantation='+id_plantation;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;     
			
			fmrVisit_map.fitWorld().zoomIn();
			
			var plantations = JSON.parse(leselect); 
			var n = parseInt(JSON.stringify(plantations.length));   
			
			i=0;
			while(i<n){  
				
				if(plantations[i].properties.name_farmer === null){ var name_farmer=""; } else { var name_farmer=plantations[i].properties.name_farmer; } 
				if(plantations[i].properties.name_farmergroup === null){ var name_farmergroup=""; } else { var name_farmergroup=plantations[i].properties.name_farmergroup; }  
				if(plantations[i].properties.name_town === null){ var name_town=""; } else { var name_town=plantations[i].properties.name_town; }  
				if(plantations[i].properties.code_farmer === null){ var code_farmer=""; } else { var code_farmer=plantations[i].properties.code_farmer; } 
				if(plantations[i].properties.culture === null){ var culture=""; } else { var culture=plantations[i].properties.culture; } 
				if(plantations[i].properties.area === null){ var area=""; } else { var area=plantations[i].properties.area; } 
				if(plantations[i].properties.name_buyer === null){ var name_buyer=""; } else { var name_buyer=plantations[i].properties.name_buyer; }  
				
				var popupContent = "<div style=\"max-width:400px; max-height: 200px\"><h5 style=\"border-bottom: 1px solid #eee;\">"+blanc
					+"<i class=\"fa fa-check-square fa-fw\" style=\"color:#ed1b2c\"></i><strong style=\"color:#ed1b2c\">&nbsp;&nbsp;Collection Point</strong></h5>"+blanc
					+"<div class=\"icon_desc\" style=\"margin-left:0px;display:block\"><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Farmer name : </strong>"+name_farmer
					+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Farmer group : </strong>"+name_farmergroup
					+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Farmer residence : </strong>"+name_town
					+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Code Farmer : </strong>"+code_farmer
					+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Culture : </strong>"+culture
					+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Area (ha) : </strong>"+area
					+" </span><br><span><i class=\"fa fa-arrows-alt fa-fw\"></i> <strong> Buyer : </strong>"+name_buyer
				+" </span></div></div>";
				
				mark = L.marker([plantations[i].properties.coordx, plantations[i].properties.coordy], {icon: pointIcon,riseOnHover:true})
					.bindPopup(popupContent)
					.addTo(plantation_project_points);  
				
				fmrVisit_map.addLayer(plantation_project_points);  
				
				i += 1;
			} 
			
			plantation_project_couche.addData(plantations);	
			fmrVisit_map.addLayer(plantation_project_couche);
			
			fmrVisit_map.fitBounds(plantation_project_couche.getBounds().extend(plantation_project_points.getBounds())); 
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function agentVisits(agent_id) {
	var resurl='include/data_collection.php?elemid=agent_visits&agent_id='+agent_id;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;    
			
			document.getElementById('dt_collect_agtVisit_content').innerHTML = leselect;
			$("#dtCollect_agtVisit").removeClass("hide");
			
			var options = {
				valueNames: ['dt_collect_farmerName']
			};

			var userList = new List('dtCollect_agentVisit', options);

			$('#dt_collect_agtVisit_content li a').click(function() {
				$('ul li.on2').removeClass('on2');
				$(this).closest('li').addClass('on2');
			});

        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}

// Apex

function apex() {
	hideAll();
	
	$("#db_apex").removeClass("hide");
	
	titleMenuManag("Apex","btn_apex2");
}


// Certification Status

function crt_status(coop) {
	hideAll();

	$("#db_cert_status").removeClass("hide");
	titleMenuManag("Certification Status","btn_crt_status");
	
	document.getElementById('certStatus_table').innerHTML = '<div class="h1 m-t-xs text-navy"><span class="loading"></span></div>';
	
	var resurl='include/certification_status.php?elemid=status&id_cooperative='+coop;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText; 
			val = leselect.split('@@');
	
			document.getElementById('certStatus_thead').innerHTML = val[1];
			document.getElementById('certStatus_table').innerHTML = val[0];
		
			// DataTable
			$('.dashboard-certStatus').DataTable({
					pageLength: 10,
					responsive: true,
					dom: '<"html5buttons"B>lTfgitp',
					buttons: [
						{extend: 'copy'},
						{extend: 'csv'},
						{extend: 'excel', title: 'ExampleFile'},
						{extend: 'pdf', title: 'ExampleFile'},

						{extend: 'print',
							customize: function (win){
									$(win.document.body).addClass('white-bg');
									$(win.document.body).css('font-size', '10px'); 

									$(win.document.body).find('table')
											.addClass('compact')
											.css('font-size', 'inherit'); 
							}
						}
					],
					"bDestroy": true
				});
		}
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function certStatusFilter(value) {
	$('.dashboard-certStatus').DataTable().destroy();
	
	document.getElementById('certStatus_thead').innerHTML = "";
	document.getElementById('certStatus_table').innerHTML = '<div class="h1 m-t-xs text-navy"><span class="loading"></span></div>';
	
	crt_status(value);
}


// Workflow Progress

function work_progress(value) { 
	hideAll();

	$("#db_workflow_progress").removeClass("hide");

	titleMenuManag("Workflow Progress","btn_wfPgs");
	
	var spinner = '<div class="sk-spinner sk-spinner-double-bounce div_ov_spanner">'+
		'<div class="sk-double-bounce1"></div>'+
		'<div class="sk-double-bounce2"></div>'+
	'</div>';

	$(".wf_charts_box").append("<div class='div_overlay'>"+spinner+"</div>");
	
	var cond;
	if((value == 645) || (value == 646) || (value == 647)){
		cond = '&id_headquarter='+value;
	} else { cond = '&id_cooperative='+value; }
	
	var resurl='include/work_progress.php?elemid=all_charts'+cond;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText; 
			var data = leselect.split('@@');    
			
			// Workflow Status
			
			var elt = data[0].split('##');
			
			document.getElementById('db_wf_new').innerHTML = elt[0];
			document.getElementById('db_wf_review').innerHTML = elt[1];
			document.getElementById('db_wf_approved').innerHTML = elt[2];
			document.getElementById('db_wf_contracted').innerHTML = elt[3];
			document.getElementById('db_wf_sensibilisation').innerHTML = elt[4];
			document.getElementById('db_wf_audit').innerHTML = elt[5];
			document.getElementById('db_wf_certified').innerHTML = elt[6];
			document.getElementById('db_wf_tt').innerHTML = elt[7];
			
			new Chartist.Bar('#statusChart', {
                labels: [lg_db_wf_new, lg_db_wf_review, lg_db_wf_approved, lg_db_wf_contracted, lg_db_wf_sensibilisation, lg_db_wf_audit, lg_db_wf_certified, "Total"],
                series: [
                    [elt[0], elt[1], elt[2], elt[3], elt[4], elt[5], elt[6], elt[7]]
                ]
            }, {
                seriesBarDistance: 10,
                reverseData: true,
                horizontalBars: true,
                axisY: {
                    offset: 70
                }
            });
	
			// Gender
			
			var elt1 = data[1].split('##');
			
			document.getElementById('db_gender_men').innerHTML = elt1[0];
			document.getElementById('db_gender_women').innerHTML = elt1[1];
			document.getElementById('db_gender_farmers').innerHTML = elt1[2];
			
			new Chartist.Bar('#genderChart', {
                labels: ['Men', 'Women', 'Total'],
                series: [
                    [elt1[0], elt1[1], elt1[2]]
                ]
            }, {
                seriesBarDistance: 10,
                reverseData: true,
                horizontalBars: true,
                axisY: {
                    offset: 70
                }
            });
			
			
			// Certification Status
			
			var elt2 = data[2].split('##'); 
			
			document.getElementById('db_cert_ggap_cand').innerHTML = elt2[0];
			document.getElementById('db_cert_ggap_appr').innerHTML = elt2[1];
			document.getElementById('db_cert_ggap_cert').innerHTML = elt2[2];
			document.getElementById('db_cert_rspo_cand').innerHTML = elt2[3];
			document.getElementById('db_cert_rspo_appr').innerHTML = elt2[4];
			document.getElementById('db_cert_rspo_cert').innerHTML = elt2[5];
			document.getElementById('db_cert_bioue_cand').innerHTML = elt2[6];
			document.getElementById('db_cert_bioue_appr').innerHTML = elt2[7];
			document.getElementById('db_cert_bioue_cert').innerHTML = elt2[8];
			document.getElementById('db_cert_bioss_cand').innerHTML = elt2[9];
			document.getElementById('db_cert_bioss_appr').innerHTML = elt2[10];
			document.getElementById('db_cert_bioss_cert').innerHTML = elt2[11];
			document.getElementById('db_cert_ftrad_cand').innerHTML = elt2[12];
			document.getElementById('db_cert_ftrad_appr').innerHTML = elt2[13];
			document.getElementById('db_cert_ftrad_cert').innerHTML = elt2[14];
			
			var barData2 = {
				labels: ["GlobalGap", "RSPO", "Bio UE", "Bio Suisse", "Fair Trade"],
				datasets: [
					{
						label: "Candidate",
						fillColor: "rgba(220,220,220,0.5)",
						strokeColor: "rgba(220,220,220,0.8)",
						highlightFill: "rgba(220,220,220,0.75)",
						highlightStroke: "rgba(220,220,220,1)",
						data: [elt2[0], elt2[3], elt2[6], elt2[9], elt2[12]]
					},
					{
						label: "Approved",
						fillColor: "rgba(26,179,148,0.5)",
						strokeColor: "rgba(26,179,148,0.8)",
						highlightFill: "rgba(26,179,148,0.75)",
						highlightStroke: "rgba(26,179,148,1)",
						data: [elt2[1], elt2[4], elt2[7], elt2[10], elt2[13]]
					},
					{
						label: "Certified",
						fillColor: "rgba(255,127,0,0.5)",
						strokeColor: "rgba(255,127,0,0.8)",
						highlightFill: "rgba(255,127,0,0.75)",
						highlightStroke: "rgba(255,127,0,1)",
						data: [elt2[2], elt2[5], elt2[8], elt2[11], elt2[14]]
					}
				]
			};

			var barOptions2 = {
				scaleBeginAtZero: true,
				scaleShowGridLines: true,
				scaleGridLineColor: "rgba(0,0,0,.05)",
				scaleGridLineWidth: 1,
				barShowStroke: true,
				barStrokeWidth: 2,
				barValueSpacing: 5,
				barDatasetSpacing: 1,
				responsive: true,
			}

			var ctx = document.getElementById("certificationChart").getContext("2d");
			var myNewChart = new Chart(ctx).Bar(barData2, barOptions2);
			
			// Mapping Progress
			
			var elt3 = data[3].split('##');  //console.log(elt3[3]); console.log(elt3[4]); console.log(elt3[5]); 
			
			document.getElementById('db_mp_plantation').innerHTML = elt3[0];
			document.getElementById('db_mp_surface').innerHTML = elt3[1];
			document.getElementById('db_mp_point').innerHTML = elt3[2];
			
			var barData = {
				labels: ["Plantation", "Surface map", "Collection Point"],
				datasets: [
					{
						label: "My First dataset",
						fillColor: "rgba(127,0,255,0.5)",
						strokeColor: "rgba(127,0,255,0.8)",
						highlightFill: "rgba(127,0,255,0.75)",
						highlightStroke: "rgba(127,0,255,1)",
						data: [elt3[0], elt3[1], elt3[2]]
					}
				]
			};

			var barOptions = {
				scaleBeginAtZero: true,
				scaleShowGridLines: true,
				scaleGridLineColor: "rgba(0,0,0,.05)",
				scaleGridLineWidth: 1,
				barShowStroke: true,
				barStrokeWidth: 2,
				barValueSpacing: 5,
				barDatasetSpacing: 1,
				responsive: true,
			}

			var ctx = document.getElementById("mappingChart").getContext("2d");
			var myNewChart = new Chart(ctx).Bar(barData, barOptions);
			
			// Surface by Project
			
			var html = '';
			var elt4 = data[4].split('**');
			
			var labels = [];
			// var data_acrea = [];
			// var data_m2 = [];
			var data_ha = [];
			
			// var total_acres = 0;
			// var total_m2 = 0;
			var total_ha = 0;
			
			i=0;
			while(elt4[i] != 'end'){
				
				var val = elt4[i].split('##');
				
				// var acres = parseFloat(val[1]).toFixed(2);
				// var m2 = parseFloat(val[2]).toFixed(2);
				var ha = parseFloat(val[3]).toFixed(2);
				
				// total_acres += Number(val[1]);
				// total_m2 += Number(val[2]);
				total_ha += Number(val[3]);
				
				labels.push([val[0]]);
				// data_acrea.push([acres]);
				// data_m2.push([m2]);
				data_ha.push([ha]);
				
				// html += '<tr><td>'+val[0]+'</td><td>'+acres+'</td><td>'+m2+'</td><td>'+ha+'</td></tr>';
				html += '<tr><td>'+val[0]+'</td><td>'+ha+'</td></tr>';
				
				i += 1;
			}
			
			// html += '<tr><td>Total</td><td>'+parseFloat(total_acres).toFixed(2)+'</td><td>'+parseFloat(total_m2).toFixed(2)+'</td><td>'+parseFloat(total_ha).toFixed(2)+'</td></tr>';
			html += '<tr><td>Total</td><td>'+parseFloat(total_ha).toFixed(2)+'</td></tr>';
			
			document.getElementById('srf_prj_content').innerHTML = html;
			
			var barData3 = {
				labels: labels,
				datasets: [
					// {
						// label: "Acres",
						// fillColor: "rgba(220,220,220,0.5)",
						// strokeColor: "rgba(220,220,220,0.8)",
						// highlightFill: "rgba(220,220,220,0.75)",
						// highlightStroke: "rgba(220,220,220,1)",
						// data: data_acrea
					// },
					// {
						// label: "m2",
						// fillColor: "rgba(26,179,148,0.5)",
						// strokeColor: "rgba(26,179,148,0.8)",
						// highlightFill: "rgba(26,179,148,0.75)",
						// highlightStroke: "rgba(26,179,148,1)",
						// data: data_m2
					// },
					{
						label: "Ha",
						fillColor: "rgba(255,127,0,0.5)",
						strokeColor: "rgba(255,127,0,0.8)",
						highlightFill: "rgba(255,127,0,0.75)",
						highlightStroke: "rgba(255,127,0,1)",
						data: data_ha
					}
				]
			};

			var barOptions3 = {
				scaleBeginAtZero: true,
				scaleShowGridLines: true,
				scaleGridLineColor: "rgba(0,0,0,.05)",
				scaleGridLineWidth: 1,
				barShowStroke: true,
				barStrokeWidth: 2,
				barValueSpacing: 5,
				barDatasetSpacing: 1,
				responsive: true,
			}

			var ctx = document.getElementById("surfaceByProjectChart").getContext("2d");
			var myNewChart = new Chart(ctx).Bar(barData3, barOptions3);
			
			$(".div_overlay").remove();
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);

}

var polylineMeasure = L.control.polylineMeasure ({
	position:'topleft', 
	unit:'metres', 
	showBearings:true, 
	clearMeasurementsOnStop: false, 
	showClearControl: true, 
	showUnitControl: true
});


 
// function showHidePlanFilter() {  
	// if ( $("#geoMap_Filter").hasClass("hide") ) {   
		// $("#geoMap_Filter").removeClass("hide");
		// $(".dashPlantRightFilterBtn").removeClass("dashPlantRightFilterBtn_width_0");
		// $(".dashPlantRightFilterBtn").addClass("dashPlantRightFilterBtn_width_210");
		
		// $(".dashPlantRightFilterBtn").removeClass("fadeInLeft");
		// $("#geoMap_Filter").removeClass("fadeInLeftBig");
		
		// $(".dashPlantRightFilterBtn").addClass("fadeInRight");
		// $("#geoMap_Filter").addClass("fadeInRightBig");
	
	// } else {
		// $("#geoMap_Filter").addClass("hide");
		// $(".dashPlantRightFilterBtn").removeClass("dashPlantRightFilterBtn_width_210");
		// $(".dashPlantRightFilterBtn").addClass("dashPlantRightFilterBtn_width_0");
		
		// $(".dashPlantRightFilterBtn").removeClass("fadeInRight");
		// $("#geoMap_Filter").removeClass("fadeInRightBig");
		
		// $(".dashPlantRightFilterBtn").addClass("fadeInLeft");
		// $("#geoMap_Filter").addClass("fadeInLeftBig");
	// }
// }


// var scalefactor, scale;

// function geolocation() { 
	// hideAll();

	// $("#db_geolocation").removeClass("hide");
	// $("#geolocationSpanner").removeClass("hide");
	
	// titleMenuManag("Geolocation/fields","btn_fields");
	
	// $(".div_overlay").remove();
	// var spinner = '<div class="sk-spinner sk-spinner-double-bounce div_ov_spanner">'+
		// '<div class="sk-double-bounce1"></div>'+
		// '<div class="sk-double-bounce2"></div>'+
	// '</div>';

	// $("#geoMap").append("<div class='div_overlay'>"+spinner+"</div>");
	
	// if(drawControl){ map.removeControl(drawControl); }
	// if(scalefactor){ map.removeControl(scalefactor); }
	// if(scale){ map.removeControl(scale); }
	
	// var resurl='include/geolocation.php?elemid=geolocation_contacts';
    // var xhr = getXhr();
	// xhr.onreadystatechange = function(){
        // if(xhr.readyState == 4 ){
            // leselect = xhr.responseText;  
			// var val = leselect.split('##');   
			
			// map.fitWorld().zoomIn();
			
			// document.getElementById('d4_content').innerHTML = val[0];

			// var options = {
				// valueNames: ['geo_contact_name']
			// };

			// var userList = new List('geo_contacts', options);

			// $('#d4_content li a').click(function() {
				// $('ul li.on').removeClass('on');
				// $(this).closest('li').addClass('on');
			// });

			// document.getElementById('list_towns').innerHTML = val[1];
			// document.getElementById('list_projects').innerHTML = val[2];


			// load_regions();
			// load_departements();
			// load_sousprefectures();
			// load_towns();
			// load_infrastructures();
			// load_zones(0);
			
			// showAllLines();
			// showAllPlantations(0);
	
			// scale = L.control.scale({maxWidth:240, metric:true, imperial:false, position: 'bottomright'});
			// scale.addTo(map);
            
            // polylineMeasure.addTo(map);
			
			// scalefactor = L.control.scalefactor();
			// scalefactor.addTo(map);
			
            
			// $('input#plt_filter_bio').on('ifChecked', function (event){ showAllPlantations(1); });
			// $('input#plt_filter_bio').on('ifUnchecked', function (event) { showAllPlantations(1); });
			
			// $('input#plt_filter_bio_suisse').on('ifChecked', function (event){ showAllPlantations(1); });
			// $('input#plt_filter_bio_suisse').on('ifUnchecked', function (event) { showAllPlantations(1); });
			
			// $('input#plt_filter_rspo').on('ifChecked', function (event){ showAllPlantations(1); });
			// $('input#plt_filter_rspo').on('ifUnchecked', function (event) { showAllPlantations(1); });
			
			// $('input#plt_filter_fair_trade').on('ifChecked', function (event){ showAllPlantations(1); });
			// $('input#plt_filter_fair_trade').on('ifUnchecked', function (event) { showAllPlantations(1); });
			
			// $('input#plt_filter_global_gap').on('ifChecked', function (event){ showAllPlantations(1); });
			// $('input#plt_filter_global_gap').on('ifUnchecked', function (event) { showAllPlantations(1); });
			
			// $('input#plt_filter_utz').on('ifChecked', function (event){ showAllPlantations(1); });
			// $('input#plt_filter_utz').on('ifUnchecked', function (event) { showAllPlantations(1); });
			
			// $('input#plt_filter_perimeter').on('ifChecked', function (event){ showAllPlantations(1); });
			// $('input#plt_filter_perimeter').on('ifUnchecked', function (event) { showAllPlantations(1); });
			
			// $('input#plt_filter_eco_river').on('ifChecked', function (event){ showAllPlantations(1); });
			// $('input#plt_filter_eco_river').on('ifUnchecked', function (event) { showAllPlantations(1); });
			
			// $('input#plt_filter_eco_shallows').on('ifChecked', function (event){ showAllPlantations(1); });
			// $('input#plt_filter_eco_shallows').on('ifUnchecked', function (event) { showAllPlantations(1); });
			
			// $('input#plt_filter_eco_wells').on('ifChecked', function (event){ showAllPlantations(1); });
			// $('input#plt_filter_eco_wells').on('ifUnchecked', function (event) { showAllPlantations(1); });
			
			// $('input#plt_filter_synthetic_fertilizer').on('ifChecked', function (event){ showAllPlantations(1); });
			// $('input#plt_filter_synthetic_fertilizer').on('ifUnchecked', function (event) { showAllPlantations(1); });
			
			// $('input#plt_filter_synthetic_herbicides').on('ifChecked', function (event){ showAllPlantations(1); });
			// $('input#plt_filter_synthetic_herbicides').on('ifUnchecked', function (event) { showAllPlantations(1); });
			
			// $('input#plt_filter_synthetic_pesticide').on('ifChecked', function (event){ showAllPlantations(1); });
			// $('input#plt_filter_synthetic_pesticide').on('ifUnchecked', function (event) { showAllPlantations(1); });
			
			// $('input#plt_filter_intercropping').on('ifChecked', function (event){ showAllPlantations(1); });
			// $('input#plt_filter_intercropping').on('ifUnchecked', function (event) { showAllPlantations(1); });
			
			// $('input#plt_filter_forest').on('ifChecked', function (event){ showAllPlantations(1); });
			// $('input#plt_filter_forest').on('ifUnchecked', function (event) { showAllPlantations(1); });
			
			// $('input#plt_filter_sewage').on('ifChecked', function (event){ showAllPlantations(1); });
			// $('input#plt_filter_sewage').on('ifUnchecked', function (event) { showAllPlantations(1); });
			
			// $('input#plt_filter_waste').on('ifChecked', function (event){ showAllPlantations(1); });
			// $('input#plt_filter_waste').on('ifUnchecked', function (event) { showAllPlantations(1); });
			
			// $('input#plt_filter_fire').on('ifChecked', function (event){ showAllPlantations(1); });
			// $('input#plt_filter_fire').on('ifUnchecked', function (event) { showAllPlantations(1); });
			
			// $('input#plt_filter_irrigation').on('ifChecked', function (event){ showAllPlantations(1); });
			// $('input#plt_filter_irrigation').on('ifUnchecked', function (event) { showAllPlantations(1); });
			
			// $('input#plt_filter_drainage').on('ifChecked', function (event){ showAllPlantations(1); });
			// $('input#plt_filter_drainage').on('ifUnchecked', function (event) { showAllPlantations(1); });
			
			// $('input#plt_filter_slope').on('ifChecked', function (event){ showAllPlantations(1); });
			// $('input#plt_filter_slope').on('ifUnchecked', function (event) { showAllPlantations(1); });
			
			// $('input#plt_filter_pest').on('ifChecked', function (event){ showAllPlantations(1); });
			// $('input#plt_filter_pest').on('ifUnchecked', function (event) { showAllPlantations(1); });
			
			// $('input#plt_filter_extension').on('ifChecked', function (event){ showAllPlantations(1); });
			// $('input#plt_filter_extension').on('ifUnchecked', function (event) { showAllPlantations(1); });
			
			// $('input#plt_filter_replanting').on('ifChecked', function (event){ showAllPlantations(1); });
			// $('input#plt_filter_replanting').on('ifUnchecked', function (event) { showAllPlantations(1); });
			
			// $('input#plt_filter_road_access').on('ifChecked', function (event){ showAllPlantations(1); });
			// $('input#plt_filter_road_access').on('ifUnchecked', function (event) { showAllPlantations(1); });
			
			// $("#geolocationSpanner").addClass("hide");
        // }
    // };

    // xhr.open("GET",resurl,true);
    // xhr.send(null);
// }


function debugevent(e) { console.debug(e.type, e, polylineMeasure._currentLine) }

map.on('polylinemeasure:toggle', debugevent);
map.on('polylinemeasure:start', debugevent);
map.on('polylinemeasure:resume', debugevent);
map.on('polylinemeasure:finish', debugevent);
map.on('polylinemeasure:clear', debugevent);
map.on('polylinemeasure:add', debugevent);
map.on('polylinemeasure:insert', debugevent);
map.on('polylinemeasure:move', debugevent);
map.on('polylinemeasure:remove', debugevent);


function email() {
	hideAll();

	$("#db_email").removeClass("hide");
	titleMenuManag("Email","btn_email");
	
	// $('.summernote').summernote();
}


function clearMenu() {

	$('#side-menu li *').removeAttr('style');
	
	$('#btn_idiscover, #btn_db, #btn_db_1, #btn_db_2, #btn_db_3, #btn_db_4, #btn_cal, #btn_ctp, #btn_cto, #btn_ctf, #btn_fields, #btn_collectDt, #btn_story, #btn_projects').removeClass("bg-success");
	$('#btn_tasks, #btn_notes, #btn_email, #btn_amgt, #btn_pref, #btn_crm, #btn_crm2, #btn_logistic, #btn_survey, #btn_suv_camp, #btn_suv_camp_result').removeClass("bg-success");
	$('#btn_syst_values, #btn_syst_town, #btn_syst_cunt, #btn_crm_pcosts_tbl, #btn_time').removeClass("bg-success");
	$('#btn_user, #btn_role_def, #btn_role_ass, #btn_role_perm, #btn_crm_freights, #btn_crm_ship').removeClass("bg-success");
	$('#btn_crm_cult, #btn_crm_pdct, #btn_crm_contract, #btn_crm_relship, #btn_crm_port, #btn_crm_pcosts').removeClass("bg-success");
	$('#btn_workflow, #btn_wf_process, #btn_wf_trigger, #btn_wf_action, #btn_wf_group, #btn_wfPgs, #btn_apex, #btn_apex2, #btn_crt_status').removeClass("bg-success");
}


function titleMenuManag(title,menu) {
	
	clearMenu();
	
	$('#'+menu).addClass("bg-success");
	$('#'+menu+' a').css("color", "white");
	
	$("#icoop_msg").addClass("hide");
	
	document.getElementById('pageTitle').innerHTML = title;
}


L.control.layers(baseMaps_db, overlayMaps).addTo(db_map);


/* Show idiscover life */

function idiscover() {
	titleMenuManag("iDiscover.live","btn_idiscover");
	
	window.open('playground.php','_blank');
}


/* Show dashboard */

function dashboard(conf) {
	hideAll();
	var data = [];
	var demo_tasks = {};
	// vessel_couche.clearLayers();

	if(conf==1){
		$("#db_dashboard_1").removeClass("hide");
		titleMenuManag(lg_menu_in_transit,"btn_db_1");
	
		var resurl='include/dashboard.php?elemid=dashboard_vessels';
		var xhr = getXhr();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;     
				
				var val = leselect.split('??');
				var x = '';
				var y = '';
				
				i = 0; 
				
				var features = [];

				while (val[i] != 'end') {
					var val2=val[i].split('##');

					features.push(
						{ "type": "Feature", "properties": { "mmsi": val2[2], "speed": val2[3], "shipname": val2[4], "timest": val2[5], "photo": val2[6], "shipment_number": val2[7], "con_booking_id": val2[8] }, "geometry": { "type": "Point", "coordinates": [ val2[1], val2[0] ] } },
					);
					
					x = val2[0]; 
					y = val2[1];
					i += 1;
				}
				
				var json_vessel = {
					"type": "FeatureCollection",
					"crs": { "type": "name", "properties": { "name": "urn:ogc:def:crs:OGC:1.3:CRS84" } },
					"features": features
				};
			
				db_map.invalidateSize(); 
				
				db_map.removeLayer(ggl);
				db_map.addLayer(googlemap);
		
				db_map.addLayer(vessel_couche);
				vessel_couche.addData(json_vessel);
				
		
				if(i==1){
					db_map.setView([x, y], 8);
					
				} else
				if(i>1){
					db_map.fitBounds(vessel_couche.getBounds());	
				
				} else {
					db_map.fitBounds([
						[47.441710, 10.848654],
						[47.915113, 6.574972]
					]);
				}
	
				// setTimeout(function() { 
					// db_map.invalidateSize(); 
				// }, 100);
	
				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
		
	} else 
	if(conf==2){
	
		data.length = 0;
		demo_tasks.length = 0;  
		
		$("#db_dashboard_2").removeClass("hide");
		titleMenuManag(lg_menu_gantt,"btn_db_2");
		
		var filtre = document.getElementById('srch-term').value;  
		
		document.getElementById('gantt_here').innerHTML = '<div class="h1 m-t-xs text-navy"><span class="loading"></span></div>'; 
	
		var resurl='include/dashboard.php?elemid=dashboard_gantt&filtre='+filtre;  
		var xhr = getXhr();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;    
			
				var gant_val = leselect.split('??');  
				i=1;

				while (gant_val[i]) {  
					var val1 = gant_val[i].split('|');
					data.push({"id":i, "text":val1[0], "start_date":val1[1], "duration":val1[2], "progress": 0.6, "open": true});
					i +=1;
				}
			
				demo_tasks = {
					"data": data
				}

				gantt.init("gantt_here");
				gantt.clearAll();
				gantt.parse(demo_tasks);
			
			
				if(filtre==""){
					scaleConfigs = [
						// minutes
						{ unit: "minute", step: 1, scale_unit: "hour", date_scale: "%H", subscales: [
							{unit: "minute", step: 1, date: "%H:%i"}
						]
						},
						// hours
						{ unit: "hour", step: 1, scale_unit: "day", date_scale: "%j %M",
							subscales: [
								{unit: "hour", step: 1, date: "%H:%i"}
							]
						},
						// days
						{ unit: "day", step: 1, scale_unit: "month", date_scale: "%F",
							subscales: [
								{unit: "day", step: 1, date: "%j"}
							]
						},
						// weeks
						{unit: "week", step: 1, scale_unit: "month", date_scale: "%F",
							subscales: [
								{unit: "week", step: 1, template: function (date) {
									var dateToStr = gantt.date.date_to_str("%d %M");
									var endDate = gantt.date.add(gantt.date.add(date, 1, "week"), -1, "day");
									return dateToStr(date) + " - " + dateToStr(endDate);
								}}
							]},
						// months
						{ unit: "month", step: 1, scale_unit: "year", date_scale: "%Y",
							subscales: [
								{unit: "month", step: 1, date: "%M"}
							]},
						// quarters
						{ unit: "month", step: 3, scale_unit: "year", date_scale: "%Y",
							subscales: [
								{unit: "month", step: 3, template: function (date) {
									var dateToStr = gantt.date.date_to_str("%M");
									var endDate = gantt.date.add(gantt.date.add(date, 3, "month"), -1, "day");
									return dateToStr(date) + " - " + dateToStr(endDate);
								}}
							]},
						// years
						{unit: "year", step: 1, scale_unit: "year", date_scale: "%Y",
							subscales: [
								{unit: "year", step: 5, template: function (date) {
									var dateToStr = gantt.date.date_to_str("%Y");
									var endDate = gantt.date.add(gantt.date.add(date, 5, "year"), -1, "day");
									return dateToStr(date) + " - " + dateToStr(endDate);
								}}
							]},
						// decades
						{unit: "year", step: 10, scale_unit: "year", template: function (date) {
							var dateToStr = gantt.date.date_to_str("%Y");
							var endDate = gantt.date.add(gantt.date.add(date, 10, "year"), -1, "day");
							return dateToStr(date) + " - " + dateToStr(endDate);
						},
						subscales: [
							{unit: "year", step: 100, template: function (date) {
								var dateToStr = gantt.date.date_to_str("%Y");
								var endDate = gantt.date.add(gantt.date.add(date, 100, "year"), -1, "day");
								return dateToStr(date) + " - " + dateToStr(endDate);
							}}
						]}
					];
				}
				
				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
		
	} else 
	if(conf==3){

		$("#db_dashboard_3").removeClass("hide");
		titleMenuManag(lg_menu_analytics,"btn_db_3");
		
		var spinner = '<div class="h1 m-t-xs text-navy"><span class="loading"></span></div>';
		document.getElementById('analytic_table').innerHTML = spinner; 
		document.getElementById('planned_arrivals').innerHTML = spinner; 
		document.getElementById('planned_departures').innerHTML = spinner; 
		document.getElementById('planned_laoding').innerHTML = spinner; 
		
		$('#aBox').slimScroll({ height: '180px', railOpacity: 0.9 });
		$('#dBox').slimScroll({ height: '180px', railOpacity: 0.9 });
		$('#lBox').slimScroll({ height: '180px', railOpacity: 0.9 });
		
		var resurl='include/dashboard.php?elemid=dashboard_analytics&a_week=1&d_week=1&l_week=1'; 
		var xhr = getXhr();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;   
				var list = leselect.split('##');
			
				document.getElementById('analytic_table').innerHTML = list[0];
				document.getElementById('planned_arrivals').innerHTML = list[1];
				document.getElementById('planned_departures').innerHTML = list[2];
				document.getElementById('planned_laoding').innerHTML = list[3];
				
				// DataTable
				$('.dashboard-analytics').DataTable({
					pageLength: 10,
					responsive: true,
					dom: '<"html5buttons"B>lTfgitp',
					buttons: [
						{extend: 'copy'},
						{extend: 'csv'},
						{extend: 'excel', title: 'ExampleFile'},
						{extend: 'pdf', title: 'ExampleFile'},

						{extend: 'print',
							customize: function (win){
									$(win.document.body).addClass('white-bg');
									$(win.document.body).css('font-size', '10px'); 

									$(win.document.body).find('table')
											.addClass('compact')
											.css('font-size', 'inherit'); 
							}
						}
					],
					"bDestroy": true
				});
				
				$('.ana_cal_date').datepicker({
					format: "yyyy/mm/dd",
					calendarWeeks:true,
					autoclose: true
				});
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
		
	} else 
	if(conf==4){ 
		
		$("#db_dashboard_4").removeClass("hide");
		titleMenuManag(lg_menu_account,"btn_db_4");
		
		var spinner = '<div class="h1 m-t-xs text-navy"><span class="loading"></span></div>';
		document.getElementById('account_table').innerHTML = spinner;  
	
		var resurl='include/dashboard.php?elemid=dashboard_account&inv_done=0'; 
		var xhr = getXhr();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;   
			
				document.getElementById('account_table').innerHTML = leselect;
				
				// DataTable
				$('.dashboard-account').DataTable({
					pageLength: 10,
					responsive: true,
					dom: '<"html5buttons"B>lTfgitp',
					buttons: [
						{extend: 'copy'},
						{extend: 'csv'},
						{extend: 'excel', title: 'ExampleFile'},
						{extend: 'pdf', title: 'ExampleFile'},
						{extend: 'print',
							customize: function (win){
								$(win.document.body).addClass('white-bg');
								$(win.document.body).css('font-size', '10px'); 
								$(win.document.body).find('table')
									.addClass('compact')
									.css('font-size', 'inherit'); 
							}
						}
					],
					"bDestroy": true
				});

				
				$('.i-checks').iCheck({
					checkboxClass: 'icheckbox_square-green',
					radioClass: 'iradio_square-green'
				});
				
				$('input#showAllPaid').on('ifChecked', function (event){ paidToggleView(1); });
				$('input#showAllPaid').on('ifUnchecked', function (event) { paidToggleView(0); });
				
				$('input.weight_ticket').on('ifChanged', function(event){ weighTicketValue($(event.target).val()); });
				$('input.inv_done').on('ifChanged', function(event){ invDoneValue($(event.target).val()); });
			
				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
		
	} else {
		
	}
}


function departureWeek(value) {
	var spinner = '<div class="h1 m-t-xs text-navy"><span class="loading"></span></div>'; 
	document.getElementById('planned_departures').innerHTML = spinner; 
	
	$('#dBox').slimScroll({ height: '180px', railOpacity: 0.9 });
	
	var resurl='include/dashboard.php?elemid=departures_by_week&d_week='+value; 
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;   
			
			document.getElementById('planned_departures').innerHTML = leselect;
		
			leselect = xhr.responseText;
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}


function arrivalWeek(value) {
	var spinner = '<div class="h1 m-t-xs text-navy"><span class="loading"></span></div>';
	document.getElementById('planned_arrivals').innerHTML = spinner;  

	$('#aBox').slimScroll({ height: '180px', railOpacity: 0.9 });
	
	var resurl='include/dashboard.php?elemid=arrivals_by_week&a_week='+value; 
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;   
			
			document.getElementById('planned_arrivals').innerHTML = leselect;
			
			leselect = xhr.responseText;
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}


function loadingWeek(value) {
	var spinner = '<div class="h1 m-t-xs text-navy"><span class="loading"></span></div>';
	document.getElementById('planned_laoding').innerHTML = spinner;  
	
	$('#lBox').slimScroll({ height: '180px', railOpacity: 0.9 });
	
	var resurl='include/dashboard.php?elemid=loading_by_week&l_week='+value; 
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;   
			
			document.getElementById('planned_laoding').innerHTML = leselect;
			
			leselect = xhr.responseText;
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}


function editDbAccount(id_con_booking) {
	
	var resurl='include/dashboard.php?elemid=dashboard_account&id_con_booking='+id_con_booking; 
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;   
			
			document.getElementById('account_table').innerHTML = leselect;
			
			$('.edit_delivery_date').datepicker({
				format: "yyyy/mm/dd",
				calendarWeeks:true,
				autoclose: true
			});
			
			$('.i-checks').iCheck({
				checkboxClass: 'icheckbox_square-green',
				radioClass: 'iradio_square-green'
			});
			
			$('input#showAllPaid').on('ifChecked', function (event){ paidToggleView(1); });
			$('input#showAllPaid').on('ifUnchecked', function (event) { paidToggleView(0); });
			
			$('input.weight_ticket').on('ifChanged', function(event){ weighTicketValue($(event.target).val()); });
			$('input.inv_done').on('ifChanged', function(event){ invDoneValue($(event.target).val()); });
		
			leselect = xhr.responseText;
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}


function weighTicketValue(value){
	var data = value.split('##');
	if(data[0]==1){
		var weight_ticket_in = 0;
	} else { var weight_ticket_in = 1; }
	
	var resurl='include/dashboard.php?elemid=weight_ticket_in_value&id_con_booking='+data[1]+'&weight_ticket_in='+weight_ticket_in;  
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;   
			
			if(leselect==1){
				toastr.success('Weight ticket in successfully saved.',{timeOut:15000})
				$('.dashboard-account').DataTable().destroy();
				dashboard(4);
			} else 
			if(leselect==0){
				toastr.error('Weight ticket in not saved. please retry',{timeOut:15000})
			} else {
				internal_error();
			}
			
			leselect = xhr.responseText;
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}


function invDoneValue(value){
	var data = value.split('##');
	if(data[0]==1){
		var inv_done = 0;
	} else { var inv_done = 1; }
	
	var resurl='include/dashboard.php?elemid=inv_done_value&id_con_booking='+data[1]+'&inv_done='+inv_done+'&id_ord_schedule='+data[2];  
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;   
			
			if(leselect==1){
				toastr.success('Saved successfully.',{timeOut:15000})
				$('.dashboard-account').DataTable().destroy();
				dashboard(4);
			} else 
			if(leselect==0){
				toastr.error('Changes not saved. please retry',{timeOut:15000})
			} else {
				internal_error();
			}
			
			leselect = xhr.responseText;
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}


function saveEditedDbAccount(id_con_booking){
	
	var req="";
	var inv1_due_date1 = document.getElementById("inv1_due_date1_"+id_con_booking).value; 
	if(inv1_due_date1) { req=req+'&inv1_due_date1='+inv1_due_date1; }
	
	var inv1_due_date2 = document.getElementById("inv1_due_date2_"+id_con_booking).value; 
	if(inv1_due_date2) { req=req+'&inv1_due_date2='+inv1_due_date2; }

	var inv1_paid1 = document.getElementById("inv1_paid1_"+id_con_booking).value; 
	if(inv1_paid1) { req=req+'&inv1_paid1='+inv1_paid1; }
	
	var inv1_paid2 = document.getElementById("inv1_paid2_"+id_con_booking).value; 
	if(inv1_paid2) { req=req+'&inv1_paid2='+inv1_paid2; }
	
	var inv2_due_date1 = document.getElementById("inv2_due_date1_"+id_con_booking).value; 
	if(inv2_due_date1) { req=req+'&inv2_due_date1='+inv2_due_date1; }
	
	var inv2_paid1 = document.getElementById("inv2_paid1_"+id_con_booking).value; 
	if(inv2_paid1) { req=req+'&inv2_paid1='+inv2_paid1; }

	var inv_note = document.getElementById("inv_note_"+id_con_booking).value; 
	if(inv_note) { req=req+'&inv_note='+inv_note; }

	
	var resurl='include/dashboard.php?elemid=save_edited_db_account&id_con_booking='+id_con_booking+req;   
	var xhr = getXhr();  
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;      
	
			if(leselect==1){
				toastr.success('Successfully saved.',{timeOut:15000})
				$('.dashboard-account').DataTable().destroy();
				dashboard(4);
			} else 
			if(leselect==0){
				toastr.error('Not saved. please retry',{timeOut:15000})
			} else {
				internal_error();
			}
		
			leselect = xhr.responseText;
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}


function paidToggleView(conf) {

	var resurl='include/dashboard.php?elemid=dashboard_account&inv_done='+conf;  
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;   
			
			document.getElementById('account_table').innerHTML = leselect;
			
			$('.edit_delivery_date').datepicker({
				format: "yyyy/mm/dd",
				calendarWeeks:true,
				autoclose: true
			});
			
			$('.i-checks').iCheck({
				checkboxClass: 'icheckbox_square-green',
				radioClass: 'iradio_square-green'
			});
			
			$('input#showAllPaid').on('ifChecked', function (event){ paidToggleView(1); });
			$('input#showAllPaid').on('ifUnchecked', function (event) { paidToggleView(0); });
			
			$('input.weight_ticket').on('ifChanged', function(event){ weighTicketValue($(event.target).val()); });
			$('input.inv_done').on('ifChanged', function(event){ invDoneValue($(event.target).val()); });
		
			leselect = xhr.responseText;
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}


function zoom_vessel_plus(x,y) {
	db_map.addLayer(ggl);
	db_map.setView([x, y], 16);
}


function toggle_vessel(mmsi,sn,con_booking_id) {  
	$("#vessel_card_head").removeClass("widget");
	$("#vessel_card_head").addClass("widget-head-color-box");

	var spanner = '<div class="h1 m-t-xs text-navy"><span class="loading"></span></div>';
	$('#vessel_details').removeClass("hide");
	document.getElementById("vessel_details").innerHTML = spanner;
	
	var resurl='include/dashboard.php?elemid=vessel_details&mmsi='+mmsi+'&sn='+sn+'&con_booking_id='+con_booking_id;      
	var xhr = getXhr();  
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;  
			var val = leselect.split('##');
			
			document.getElementById("vessel_details").innerHTML = val[0];
			document.getElementById("vessel_name_type").innerHTML = val[1];
			document.getElementById("vessel_booking_infos").innerHTML = val[2];
			
			leselect = xhr.responseText;
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}

/*
* Story Management
* START
*/


// Stories Table
function storybulding() {    
	hideAll();

	$("#db_story").removeClass("hide");
	titleMenuManag("Story management","btn_story");
	
	var media_type = document.getElementById("story_media").value;
	var id_country = document.getElementById("story_country").value;
	document.getElementById("storyTbListe").innerHTML = '<div class="h1 m-t-xs text-navy"><span class="loading"></span></div>';
	
				// var storyManag_create = 0; 
// var storyManag_update = 0; 
// var storyManag_delete = 0; 

	var resurl='listeslies.php?elemid=liste_stories&media='+media_type+'&country='+id_country;      
    var xhr = getXhr();  
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;    
		 
			document.getElementById("storyTbListe").innerHTML = leselect;
			
			if(storyManag_create == 1){ $(".st_create_action").removeClass("hide"); }
			if(storyManag_update == 1){ $(".st_edit_action").removeClass("hide"); }
			if(storyManag_delete == 1){ $(".st_delete_action").removeClass("hide"); }
			
			if((storyManag_update == 0)&&(storyManag_delete == 0)){ 
				$(".row_actions").addClass("hide");
			} else { $(".row_actions").removeClass("hide"); }
			
			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}

// Steps Table
function storySteps(id_story) { 

	document.getElementById("stepsTbListe").innerHTML = '<div class="h1 m-t-xs text-navy"><span class="loading"></span></div>';
	
	if(storyManag_create == 1){
		document.getElementById("addStepBTN").innerHTML = '<a href="#" class="pull-right btn btn-primary btn-xs" onclick="new_step('+id_story+');" data-toggle="modal" data-target="#modalStep">Create New Step</a>';
	}
	
	var resurl='listeslies.php?elemid=liste_steps&id_story='+id_story;      
    var xhr = getXhr();  
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;      
		 
			if(leselect == ""){
				document.getElementById("stepsTbListe").innerHTML = '<td colspan="5"><i class="fa fa-warning"></i> The list is empty.</td>';
			} else { 
				document.getElementById("stepsTbListe").innerHTML = leselect;
			}
			
			if(storyManag_update == 1){ $(".st_edit_action").removeClass("hide"); }
			if(storyManag_delete == 1){ $(".st_delete_action").removeClass("hide"); }
			
			if((storyManag_update == 0)&&(storyManag_delete == 0)){ 
				$(".row_actions").addClass("hide");
			} else { $(".row_actions").removeClass("hide"); }
			
			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}

// Select Story Media Type
function MDstoryMedia(type) {
	if(type == 1){
		document.getElementById("MDmedia_select").innerHTML = '<label for="MDmedia_link" style="width:100%;"><i class="fa fa-youtube-play"></i> Media link <span class="pull-right" id="ST_show_media"></span></label>'
			+'<input type="text" class="form-control" id="MDmedia_link" />';
	} else 
	if(type == 2){
		document.getElementById("MDmedia_select").innerHTML = '<label for="MDmedia_link" style="width:100%;"><i class="fa fa-picture-o"></i> Media link <span class="pull-right" id="ST_show_media"></span></label>'
			+'<input type="file" name="image" id="MDmedia_link" />';
	} else {
		document.getElementById("MDmedia_select").innerHTML = '';
	}
}

// Story Exporter
function MDstoryExporter(id_country) {
	
	if(id_country === 0){ 
		document.getElementById("storyForm").reset();
	}
	
	var resurl='listeslies.php?elemid=select_exporter&id_country='+id_country;      
    var xhr = getXhr();  
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;      
		 
			document.getElementById("MDid_exporter").innerHTML = leselect; 
			
			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}

// Save Story
function saveStory() {
	var req="";
	var story_titleen = document.getElementById("MDstory_titleen").value; 
	if(story_titleen) { req=req+'&story_titleen='+story_titleen; }
	
	var story_titlede = document.getElementById("MDstory_titlede").value;  
	if(story_titlede) { req=req+'&story_titlede='+story_titlede; }
	
	var story_titlefr = document.getElementById("MDstory_titlefr").value; 
	if(story_titlefr) { req=req+'&story_titlefr='+story_titlefr; }
	
	var story_titlept = document.getElementById("MDstory_titlept").value;
	if(story_titlept) { req=req+'&story_titlept='+story_titlept; }
	
	var story_titlees = document.getElementById("MDstory_titlees").value;  
	if(story_titlees) { req=req+'&story_titlees='+story_titlees; }
	
	var story_titleit = document.getElementById("MDstory_titleit").value;  
	if(story_titleit) { req=req+'&story_titleit='+story_titleit; }
	
	var id_country = document.getElementById("MDid_country").value;  
	if(id_country) { req=req+'&id_country='+id_country; }
	
	var media_type = document.getElementById("MDmedia_type").value;  
	if(media_type) { req=req+'&media_type='+media_type; }
	
	if(media_type!=""){
		var media_link = document.getElementById("MDmedia_link").value; 
		if(media_link) { req=req+'&media_link='+media_link; }
	}
	
	var id_exporter = document.getElementById("MDid_exporter").value;  
	if(id_exporter) { req=req+'&id_exporter='+id_exporter; }
	
	if((story_titleen!="")&&(id_country!="")&&(id_exporter!="")&&(media_type!="")){
		var resurl='listeslies.php?elemid=save_story&conf=add'+req;   
		var xhr = getXhr();  
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;      
			 
				if(leselect==1){
					toastr.success('Story saved successfully.',{timeOut:15000})
					if(media_type == 2){ 
						if( document.getElementById("MDmedia_link").files.length != 0 ){
							uploadStoryImg();
						}
					}  
					storybulding();
					$("#modalStory").modal("hide"); 
					
				} else 
				if(leselect==0){
					toastr.error('Story not saved.',{timeOut:15000})
					
				} else {
					internal_error();
				}
				
				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
		
	} else {
		toastr.info('Please fill all required fields.',{timeOut:15000})
	}
}

// Upload Story Image
function uploadStoryImg() {
	var spinner = '<div class="sk-spinner sk-spinner-double-bounce div_ov_spanner">'+
		'<div class="sk-double-bounce1"></div>'+
		'<div class="sk-double-bounce2"></div>'+
	'</div>';
	
	var progressbar = '<div id="progress-div"><div id="progress-bar"></div></div>';
	$("#modalStoryContent").append("<div class='div_overlay'>"+spinner+progressbar+"</div>");
	
	$('#storyForm').one('submit', function(e) {
		e.preventDefault();
		$('#progress-div').show();
		$(this).ajaxSubmit({  
			beforeSubmit: function() {
				$("#progress-bar").width('0%');
			},
			uploadProgress: function (event, position, total, percentComplete){	
				$("#progress-bar").width(percentComplete + '%');
				$("#progress-bar").html('<div id="progress-status">' + percentComplete +' %</div>')
			},
			success:function (response){
				if(response == 1){
					$(".div_overlay").remove();
					$('#progress-div').hide();
				
				} else { toastr.error(response,{timeOut:15000}) }
			},
			resetForm: true 
		}); 
		return false; 
	});
}

// Edit Story
function editStory(id_story) {

	var resurl='listeslies.php?elemid=edit_story&id_story='+id_story;      
    var xhr = getXhr();  
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;   
			var val = leselect.split('##');
			
			MDstoryMedia(val[6]);
			MDstoryExporter(val[5]);
		 
			document.getElementById("MDstory_titleen").value = val[0];
			document.getElementById("MDstory_titlede").value = val[1];
			document.getElementById("MDstory_titlefr").value = val[2];
			document.getElementById("MDstory_titlept").value = val[3]; 
			document.getElementById("MDstory_titlees").value = val[4]; 
			document.getElementById("MDstory_titleit").value = val[9];  
			document.getElementById("MDid_country").value = val[5]; 
			document.getElementById("MDmedia_type").value = val[6]; 
			if(val[6] == 1){ document.getElementById("MDmedia_link").value = val[7]; }
			document.getElementById("MDid_exporter").value = val[8]; 
			
			document.getElementById("ST_show_media").innerHTML = '<a href="#" onclick="ST_media(\''+val[6]+'\',\''+val[7]+'\');"><i class="fa fa-eye" aria-hidden="true"></i></a>';
			
			document.getElementById("modalStoryFooter").innerHTML = '<button type="button" class="btn btn-primary" onclick="saveEditedStory('+id_story+');"><i class="fa fa-save"></i></button>'
				+'<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i></button>';
			
			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


// Show Media
function ST_media(media_type,media_link) {
	$("#modalStoryImage").modal("show");
	
	if(media_type==2){
		document.getElementById("storyImgContent").innerHTML = '<img style="width:100%;" src="playground/img/story/'+media_link+'" class="responsive" />';
	} else {
		document.getElementById("storyImgContent").innerHTML = '<iframe style="width:100%; height:349px;" frameborder="0" allowfullscreen="true" src="https://www.youtube.com/embed/'+media_link+'?autoplay=0&rel=0&loop=1" allowTransparency="true" frameborder="0" scrolling="no"></iframe>';
	}
}

// Delete Story
function deleteStory(id_story) {
	var resurl='listeslies.php?elemid=delete_story&id_story='+id_story;      
    var xhr = getXhr();  
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;      
		 
			if(leselect==1){
				toastr.success('Story successfully deleted.',{timeOut:15000})
				storybulding();
				
			} else 
			if(leselect==0){
				toastr.error('Story not deleted.',{timeOut:15000})
				
			} else {
				internal_error();
			}
			
			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}

// Save Edited Story
function saveEditedStory(id_story) { 
	var req="";
	var story_titleen = document.getElementById("MDstory_titleen").value;
	if(story_titleen) { req=req+'&story_titleen='+story_titleen; }
	
	var story_titlede = document.getElementById("MDstory_titlede").value;
	if(story_titlede) { req=req+'&story_titlede='+story_titlede; }
	
	var story_titlefr = document.getElementById("MDstory_titlefr").value;
	if(story_titlefr) { req=req+'&story_titlefr='+story_titlefr; }
	
	var story_titlept = document.getElementById("MDstory_titlept").value;
	if(story_titlept) { req=req+'&story_titlept='+story_titlept; }
	
	var story_titlees = document.getElementById("MDstory_titlees").value;
	if(story_titlees) { req=req+'&story_titlees='+story_titlees; }
	
	var story_titleit = document.getElementById("MDstory_titleit").value;  
	if(story_titleit) { req=req+'&story_titleit='+story_titleit; }
	
	var id_country = document.getElementById("MDid_country").value;
	if(id_country) { req=req+'&id_country='+id_country; }
	
	var media_type = document.getElementById("MDmedia_type").value;
	if(media_type) { req=req+'&media_type='+media_type; }
	
	if(media_type!=""){
		var media_link = document.getElementById("MDmedia_link").value;
		if(media_link) { req=req+'&media_link='+media_link; }
	}
	
	var id_exporter = document.getElementById("MDid_exporter").value;
	if(id_exporter) { req=req+'&id_exporter='+id_exporter; }
	
	var resurl='listeslies.php?elemid=save_story&conf=edit&id_story='+id_story+req;      
    var xhr = getXhr();    
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;    
		 
			if(leselect==1){
				toastr.success('Story saved successfully.',{timeOut:15000}) 
				if(media_type == 2){ 
					if( document.getElementById("MDmedia_link").files.length != 0 ){
						uploadStoryImg();
					}
				} 
				$("#modalStory").modal("hide"); 
				storybulding(); 
				
			} else 
			if(leselect==0){
				toastr.error('Story not saved.',{timeOut:15000})
				
			} else {
				internal_error();
			}
			
			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}

// Select Step Media Type
function MDstepMedia(type) {
	if(type == 1){
		document.getElementById("MDseq_media_select").innerHTML = '<label for="MDseq_link" style="width:100%;"><i class="fa fa-youtube-play"></i> Media link <span class="pull-right" id="STP_show_media"></span></label>'
			+'<input type="text" class="form-control" id="MDseq_link" />';
	} else 
	if(type == 2){
		document.getElementById("MDseq_media_select").innerHTML = '<label for="MDseq_link" style="width:100%;"><i class="fa fa-picture-o"></i> Media link <span class="pull-right" id="STP_show_media"></span></label>'
			+'<input type="file" name="image" id="MDseq_link" />';
	} else {
		document.getElementById("MDseq_media_select").innerHTML = '';
	}
}

// Save Step
function saveStep() {
	var req="";
	var seq_texten = document.getElementById("MDseq_texten").value;
	if(seq_texten) { req=req+'&seq_texten='+seq_texten; }
	
	var seq_textde = document.getElementById("MDseq_textde").value;
	if(seq_textde) { req=req+'&seq_textde='+seq_textde; }
	
	var seq_textfr = document.getElementById("MDseq_textfr").value;
	if(seq_textfr) { req=req+'&seq_textfr='+seq_textfr; }
	
	var seq_textpt = document.getElementById("MDseq_textpt").value;
	if(seq_textpt) { req=req+'&seq_textpt='+seq_textpt; }
	
	var seq_textes = document.getElementById("MDseq_textes").value;
	if(seq_textes) { req=req+'&seq_textes='+seq_textes; }
	
	var seq_textit = document.getElementById("MDseq_textit").value;
	if(seq_textit) { req=req+'&seq_textit='+seq_textit; }
	
	var id_story = document.getElementById("MDseq_id_story").value;
	if(id_story) { req=req+'&id_story='+id_story; }
	
	var seq_mediatype = document.getElementById("MDseq_mediatype").value;
	if(seq_mediatype) { req=req+'&seq_mediatype='+seq_mediatype; }
	
	if(seq_mediatype!=""){
		var seq_link = document.getElementById("MDseq_link").value; 
		if(seq_link) { req=req+'&seq_link='+seq_link; }
	} else {
		var seq_link = "";
	}
	
	var seq_number = document.getElementById("MDseq_number").value;
	if(seq_number) { req=req+'&seq_number='+seq_number; }
	
	var seq_coordx = document.getElementById("MDseq_coordx").value;
	if(seq_coordx) { req=req+'&seq_coordx='+seq_coordx; }
	
	var seq_coordy = document.getElementById("MDseq_coordy").value;
	if(seq_coordy) { req=req+'&seq_coordy='+seq_coordy; }
	
	if((seq_texten!="")&&(seq_number!="")&&(seq_mediatype!="")){
		var resurl='listeslies.php?elemid=save_step&conf=add'+req;      
		var xhr = getXhr();  
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;       
			 
				if(leselect==1){
					toastr.success('Step saved successfully.',{timeOut:15000})
					$("#modalStep").modal("hide");
					if(seq_mediatype == 2){   
						if(seq_link !== ""){
							uploadStepImg(); 
						}
						
					}
					storySteps(id_story);
					
				} else 
				if(leselect==0){
					toastr.error('Step not saved.',{timeOut:15000})
					
				} else {
					internal_error();
				}
				
				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
	}
}

// Upload Step Image
function uploadStepImg() {
	var spinner = '<div class="sk-spinner sk-spinner-double-bounce div_ov_spanner">'+
		'<div class="sk-double-bounce1"></div>'+
		'<div class="sk-double-bounce2"></div>'+
	'</div>';
	
	var progressbar = '<div id="progress-div"><div id="progress-bar"></div></div>';
	$("#modalStepContent").append("<div class='div_overlay'>"+spinner+progressbar+"</div>");
	
	$('#stepForm').one('submit', function(e) {
		e.preventDefault();
		$('#progress-div').show();
		$(this).ajaxSubmit({  
			beforeSubmit: function() {
				$("#progress-bar").width('0%');
			},
			uploadProgress: function (event, position, total, percentComplete){	
				$("#progress-bar").width(percentComplete + '%');
				$("#progress-bar").html('<div id="progress-status">' + percentComplete +' %</div>')
			},
			success:function (response){
				if(response == 1){
					$(".div_overlay").remove();
					$('#progress-div').hide();
				
				} else { toastr.error(response,{timeOut:15000}) }
			},
			resetForm: true 
		}); 
		return false; 
	});
}


var newStep = new LeafIcon({iconUrl: 'img/new_step.png'});
var lastStep = new LeafIcon({iconUrl: 'img/last_step.png'});
var currentStep = new LeafIcon({iconUrl: 'img/current_step.png'});

// Story Map
var ggl_story = new L.Google('HYBRID');
var googlemap_story = new L.Google('ROADMAP');
var story_map = new L.Map('storymap', {layers: [googlemap_story]});
var baseMaps_story = {
	"Google Map": googlemap_story,
	"Google Satellite": ggl_story
};

L.control.layers(baseMaps_story, overlayMaps).addTo(story_map);


// Step Map
var ggl_step = new L.Google('HYBRID');
var googlemap_step = new L.Google('ROADMAP');
var step_map = new L.Map('stepmap', {layers: [googlemap_step]});
var baseMaps_step = {
	"Google Map": googlemap_story,
	"Google Satellite": ggl_story
};

var drawnItems = L.featureGroup();

L.control.layers(baseMaps_step, overlayMaps, { 'drawlayer': drawnItems }, { position: 'topright', collapsed: false }).addTo(step_map);

var newStep_layer = L.geoJson('', {}).addTo(story_map);  
var lastStep_layer = L.geoJson('', {}).addTo(story_map);  
var stepMarker_layer = L.geoJson('', {}).addTo(step_map); 


var drawingControl = new L.Control.Draw({
	draw: {
        polygon: true,
		// polyline: false,
        circle: false,
		rectangle: false,
		circlemarker: false,
        marker: false
    },
    edit: {
        featureGroup: drawnItems,
        poly: {
            allowIntersection: false
        }
    }
    
});


function new_step(id_story) {

	document.getElementById("stepForm").reset();
	document.getElementById("MDseq_id_story").value = id_story;
	document.getElementById("MDseq_coordy").value = '';
	document.getElementById("MDseq_coordx").value = '';
	
	newStep_layer.clearLayers();  
	lastStep_layer.clearLayers(); 
	
	var resurl='listeslies.php?elemid=story_title_lastStep&id_story='+id_story;      
    var xhr = getXhr();  
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;     
			var val = leselect.split('##');
		 
			document.getElementById("modalStepHeader").innerHTML = val[0];
			
			if((val[1]!="")&&(val[2]!="")){
				L.marker([val[1],val[2]], {icon: lastStep,riseOnHover:true})
				.bindPopup('<p><b>Last Step</b><br /><b>x = </b>'+val[1]+'<br /><b>y = </b>'+val[2]+'</p>')
				.addTo(lastStep_layer);
				story_map.addLayer(lastStep_layer);
				
				story_map.setView([val[1],val[2]], 15);
			} else {
				story_map.fitWorld().zoomIn();
			}
			
			story_map.invalidateSize();
			
			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
	

	story_map.on('click', function(e) {
		newStep_layer.clearLayers();
		new L.marker(e.latlng, {icon: newStep,riseOnHover:true})
		.bindPopup('<p><b>New Step</b><br /><b>x = </b>'+e.latlng.lat+'<br /><b>y = </b>'+e.latlng.lng+'</p>')
		.addTo(newStep_layer)
		.openPopup();

		document.getElementById("MDseq_coordy").value = e.latlng.lng;
		document.getElementById("MDseq_coordx").value = e.latlng.lat;
	});
}

// Delete Step
function deleteStep(id_storycon,id_story) {
	var resurl='listeslies.php?elemid=delete_step&id_storycon='+id_storycon;      
    var xhr = getXhr();  
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;      
		 
			if(leselect==1){
				toastr.success('Step successfully deleted.',{timeOut:15000})
				storySteps(id_story);
				
			} else 
			if(leselect==0){
				toastr.error('Step not deleted.',{timeOut:15000})
				
			} else {
				internal_error();
			}
			
			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}

// Step Line
function stepLine(id_storycon,id_story) { 
	
	stepMarker_layer.clearLayers();  
	drawnItems.clearLayers();  
	
	step_map.removeControl(drawingControl);
	
    step_map.addControl(drawingControl);

    step_map.on(L.Draw.Event.CREATED, function (event) {
        var layer = event.layer;

        drawnItems.addLayer(layer);
		step_map.addLayer(drawnItems);
		
		var json = drawnItems.toGeoJSON();
		var line = JSON.stringify(json);
		document.getElementById("newStepsLine").value = line;
    });
	
	step_map.on(L.Draw.Event.EDITED, function(event) {
        var layers = event.layers;
     
		var json = layers.toGeoJSON();
        var line = JSON.stringify(json);
		document.getElementById("newStepsLine").value = line;
    });
	
	
	var resurl='listeslies.php?elemid=stored_step_line&id_storycon='+id_storycon+'&id_story='+id_story;      
    var xhr = getXhr();  
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;    
			var val = leselect.split('##');  
			
			document.getElementById("modalStepLineHeader").innerHTML = val[1]+'-'+val[0];
		
			if((val[2]!=="")&&(val[3]!=="")){ 
				L.marker([val[2],val[3]], {icon: currentStep,riseOnHover:true})
				.bindPopup('<p><b>Current Step</b><br /><b>x = </b>'+val[2]+'<br /><b>y = </b>'+val[3]+'</p>')
				.addTo(stepMarker_layer);
				step_map.addLayer(stepMarker_layer);
		
				step_map.setView([val[2],val[3]], 15);
			} else {
				step_map.fitWorld().zoomIn();
			}
			
			if((val[4]!="")&&(val[5]!="")){
				L.marker([val[4],val[5]], {icon: lastStep,riseOnHover:true})
				.bindPopup('<p><b>Last Step</b><br /><b>x = </b>'+val[4]+'<br /><b>y = </b>'+val[5]+'</p>')
				.addTo(stepMarker_layer);
				step_map.addLayer(stepMarker_layer);
			} 
			
			if((val[2]!="")&&(val[3]!="")&&(val[4]!="")&&(val[5]!="")){
				step_map.fitBounds([[val[2],val[3]],[val[4],val[5]]]);
			}
			
			step_map.invalidateSize();
			
			
			if(val[6]!==""){   
				var coords = [];
				var json = JSON.parse(val[6]);
				var numb = json.features[0].geometry.coordinates.length; 
			
				for(var i = 0; i < numb; i++) {
					var latlng = new L.latLng(json.features[0].geometry.coordinates[i]); 
					coords.push([latlng.lng,latlng.lat]);
				}

				var line = L.polyline(coords, {
					color: 'green',
					opacity: 1.0
				}).addTo(stepMarker_layer);
			}
	
			document.getElementById("modalStepLineFooter").innerHTML = '<button type="button" class="btn btn-primary" onclick="saveEditedStepLine('+id_storycon+','+id_story+');"><i class="fa fa-save"></i></button>'
			+'<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i></button>';
			
			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);	
}


function saveEditedStepLine(id_storycon,id_story) {
	var newLine = document.getElementById("newStepsLine").value;  
	
	var resurl='listeslies.php?elemid=save_step_line&id_storycon='+id_storycon+'&newLine='+newLine;      
    var xhr = getXhr();  
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;   
			var val = leselect.split('##');
			
			if(leselect==1){
				toastr.success('Step line saved successfully.',{timeOut:15000})
				stepLine(id_storycon,id_story);
				
			} else 
			if(leselect==0){
				toastr.error('Step line not saved.',{timeOut:15000})
				
			} else {
				internal_error();
			}
			
			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


// Edit Step
function editStep(id_storycon,id_story) {

	newStep_layer.clearLayers();  
	lastStep_layer.clearLayers();
	
	var resurl='listeslies.php?elemid=edit_step&id_storycon='+id_storycon+'&id_story='+id_story;      
    var xhr = getXhr();  
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;   
			var val = leselect.split('##');
			
			MDstepMedia(val[6]);
			
			document.getElementById("MDseq_texten").value = val[0];
			document.getElementById("MDseq_textde").value = val[1];
			document.getElementById("MDseq_textfr").value = val[2];
			document.getElementById("MDseq_textpt").value = val[3]; 
			document.getElementById("MDseq_textes").value = val[4];
			document.getElementById("MDseq_textit").value = val[12];			
			document.getElementById("MDseq_number").value = val[5];   
			document.getElementById("MDseq_mediatype").value = val[6];  
			if(val[6] == 1){ document.getElementById("MDseq_link").value = val[7]; }
			document.getElementById("MDseq_coordx").value = val[8];   
			document.getElementById("MDseq_coordy").value = val[9];   
			document.getElementById("MDseq_id_story").value = id_story; 
			
			document.getElementById("STP_show_media").innerHTML = '<a href="#" onclick="ST_media(\''+val[6]+'\',\''+val[7]+'\');"><i class="fa fa-eye" aria-hidden="true"></i></a>';
			
			document.getElementById("modalStepFooter").innerHTML = '<button type="button" class="btn btn-primary" onclick="saveEditedStep('+id_storycon+','+id_story+');"><i class="fa fa-save"></i></button>'
				+'<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i></button>';
			
			
			if((val[8]!="")&&(val[9]!="")){
				L.marker([val[8],val[9]], {icon: currentStep,riseOnHover:true})
				.bindPopup('<p><b>Current Step</b><br /><b>x = </b>'+val[8]+'<br /><b>y = </b>'+val[9]+'</p>')
				.addTo(lastStep_layer);
				story_map.addLayer(lastStep_layer);
				
				story_map.setView([val[8],val[9]], 15);
			} else {
				story_map.fitWorld().zoomIn();
			}
			
			if((val[10]!="")&&(val[11]!="")){
				L.marker([val[10],val[11]], {icon: lastStep,riseOnHover:true})
				.bindPopup('<p><b>Last Step</b><br /><b>x = </b>'+val[10]+'<br /><b>y = </b>'+val[11]+'</p>')
				.addTo(lastStep_layer);
				story_map.addLayer(lastStep_layer);
			} 
			
			if((val[8]!="")&&(val[9]!="")&&(val[10]!="")&&(val[11]!="")){
				story_map.fitBounds([[val[8],val[9]],[val[10],val[11]]]);
			}
			
			story_map.invalidateSize();
			
			
			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
	
	story_map.on('click', function(e) {
		newStep_layer.clearLayers();
		new L.marker(e.latlng, {icon: newStep,riseOnHover:true})
		.bindPopup('<p><b>New Step</b><br /><b>x = </b>'+e.latlng.lat+'<br /><b>y = </b>'+e.latlng.lng+'</p>')
		.addTo(newStep_layer)
		.openPopup();

		document.getElementById("MDseq_coordy").value = e.latlng.lng;
		document.getElementById("MDseq_coordx").value = e.latlng.lat;
	});
}


function saveEditedStep(id_storycon,id_story) {
	var req="";
	var seq_texten = document.getElementById("MDseq_texten").value;
	if(seq_texten) { req=req+'&seq_texten='+seq_texten; }
	
	var seq_textde = document.getElementById("MDseq_textde").value;
	if(seq_textde) { req=req+'&seq_textde='+seq_textde; }
	
	var seq_textfr = document.getElementById("MDseq_textfr").value;
	if(seq_textfr) { req=req+'&seq_textfr='+seq_textfr; }
	
	var seq_textpt = document.getElementById("MDseq_textpt").value;
	if(seq_textpt) { req=req+'&seq_textpt='+seq_textpt; }
	
	var seq_textes = document.getElementById("MDseq_textes").value;
	if(seq_textes) { req=req+'&seq_textes='+seq_textes; }
	
	var seq_textit = document.getElementById("MDseq_textit").value;
	if(seq_textit) { req=req+'&seq_textit='+seq_textit; }
	
	var seq_mediatype = document.getElementById("MDseq_mediatype").value;
	if(seq_mediatype) { req=req+'&seq_mediatype='+seq_mediatype; }
	
	if(seq_mediatype!=""){
		var seq_link = document.getElementById("MDseq_link").value;
		if(seq_link) { req=req+'&seq_link='+seq_link; }
	} else {
		var seq_link = "";
	}

	var seq_number = document.getElementById("MDseq_number").value;
	if(seq_number) { req=req+'&seq_number='+seq_number; }
	
	var seq_coordx = document.getElementById("MDseq_coordx").value;
	if(seq_coordx) { req=req+'&seq_coordx='+seq_coordx; }
	
	var seq_coordy = document.getElementById("MDseq_coordy").value;
	if(seq_coordy) { req=req+'&seq_coordy='+seq_coordy; }
	
	var resurl='listeslies.php?elemid=save_step&conf=edit&id_storycon='+id_storycon+req;      
    var xhr = getXhr();  
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;       
		 
			if(leselect==1){
				toastr.success('Step saved successfully.',{timeOut:15000})
				$("#modalStep").modal("hide");
				if(seq_mediatype == 2){  
					if(seq_link !== ""){
						uploadStepImg();   
					}
				}
				storySteps(id_story);
				
			} else 
			if(leselect==0){
				toastr.error('Step not saved.',{timeOut:15000})
				
			} else {
				internal_error();
			}
			
			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}

/*
* Story Management
* END
*/


function save_exporter_quote(conf,incoterms,ord_schedule_id,last_ship_nr,id_ord_order) {

	var req="";
	var req_quote_supplier_contact_id=document.getElementById('req_quote_supplier_contact_id').value;
	if(req_quote_supplier_contact_id){ req=req+'&req_quote_supplier_contact_id='+req_quote_supplier_contact_id; }
	
	var req_quote_incoterms_id=document.getElementById('req_quote_incoterms_id').value;
	if(req_quote_incoterms_id){ req=req+'&req_quote_incoterms_id='+req_quote_incoterms_id; }
	
	var req_quote_pol_id=document.getElementById('req_quote_pol_id').value;
	if(req_quote_pol_id){ req=req+'&req_quote_pol_id='+req_quote_pol_id; }
	
	var req_quote_month_etd=document.getElementById('req_quote_month_etd').value;
	if(req_quote_month_etd){ req=req+'&req_quote_month_etd='+req_quote_month_etd; }
	
	var req_quote_week_etd=document.getElementById('req_quote_week_etd').value;
	if(req_quote_week_etd){ req=req+'&req_quote_week_etd='+req_quote_week_etd; }
	
	var req_quote_currency_id=document.getElementById('req_quote_currency_id').value;
	if(req_quote_currency_id){ req=req+'&req_quote_currency_id='+req_quote_currency_id; }
	
	var req_quote_price_sup_eur=document.getElementById('req_quote_price_sup_eur').value;
	if(req_quote_price_sup_eur){ req=req+'&req_quote_price_sup_eur='+req_quote_price_sup_eur; }  
	
	var req_quote_supplier_person_id=document.getElementById('req_quote_supplier_person_id').value;
	if(req_quote_supplier_person_id){ req=req+'&req_quote_supplier_person_id='+req_quote_supplier_person_id; }
	
	var req_quote_reference_nr=document.getElementById('req_quote_reference_nr').value;
	if(req_quote_reference_nr){ req=req+'&req_quote_reference_nr='+req_quote_reference_nr; }
	
	var req_quote_sup_quote_validity=document.getElementById('req_quote_sup_quote_validity').value;
	if(req_quote_sup_quote_validity){ req=req+'&req_quote_sup_quote_validity='+req_quote_sup_quote_validity; }
	
	var req_quote_supplier_cf_date=document.getElementById('req_quote_supplier_cf_date').value;
	if(req_quote_supplier_cf_date){ req=req+'&req_quote_supplier_cf_date='+req_quote_supplier_cf_date; }
	
	var req_quote_sm_notes=document.getElementById('req_quote_sm_notes').value;
	if(req_quote_sm_notes){ req=req+'&req_quote_sm_notes='+req_quote_sm_notes; }
	
	var req_quote_week_eta=document.getElementById('req_quote_week_eta').value;
	if(req_quote_week_eta){ req=req+'&req_quote_week_eta='+req_quote_week_eta; }
	
	var req_quote_month_eta=document.getElementById('req_quote_month_eta').value;
	if(req_quote_month_eta){ req=req+'&req_quote_month_eta='+req_quote_month_eta; }
	
	var req_quote_tank_provider=document.getElementById('req_quote_tank_provider').value;
	if(req_quote_tank_provider){ req=req+'&req_quote_tank_provider='+req_quote_tank_provider; }
	
	var id_ord_schedule=document.getElementById('id_ord_schedule').value;
	
	if(conf==1){ req=req+'&proposal='+conf; }
	
	var resurl='listeslies.php?elemid=save_exporter_quote&id_ord_schedule='+id_ord_schedule+req;      
    var xhr = getXhr();  
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;    
		 
			if(leselect==1){
				toastr.success('Profil updated shedule',{timeOut:15000})
				
				exporter_quote_cancelEditing(ord_schedule_id,last_ship_nr,id_ord_order);
				showQuoteForm(ord_schedule_id,last_ship_nr,id_ord_order);
				
				if(conf==1){ 
					$("#crm_request_freight_tab").removeClass("hide");
					$("#crm_request_freight_ct").removeClass("hide");
				}
				crm_manag(0,0);
				
			} else 
			if(leselect==0){
				toastr.error('Unable to update shedule',{timeOut:15000})
			} else {
				internal_error(); 
			}
			
			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function exporter_quote_editForm(id_ord_schedule,last_ship_nr,id_ord_order,pipeline_id){
	
	$('#exporter_quote_formContent').find('input, textarea, select').prop("disabled", false);
	if((pipeline_id == 293)||(pipeline_id == 294)||(pipeline_id == 295)){
		$('#sendProposalBtnId').prop("disabled", false);
	} else {
		$('#sendProposalBtnId').prop("disabled", true);
	}
	
	document.getElementById('exporterQuoteBtn').innerHTML = '<button class="btn btn-success" onclick="save_exporter_quote(\'0\',\'0\',\''+id_ord_schedule+'\',\''+last_ship_nr+'\',\''+id_ord_order+'\',\''+pipeline_id+'\');" type="button"><i class="fa fa-save"></i></button>'+
		' &nbsp;<button class="btn btn-danger" onclick="exporter_quote_cancelEditing(\''+id_ord_schedule+'\',\''+last_ship_nr+'\',\''+id_ord_order+'\',\''+pipeline_id+'\');" type="button"><i class="fa fa-ban"></i></button>';
}


function exporter_quote_cancelEditing(id_ord_schedule,last_ship_nr,id_ord_order,pipeline_id){
	
	$('#exporter_quote_formContent').find('input, textarea, select').prop("disabled", true);
	$('#sendProposalBtnId').prop("disabled", true);
	
	document.getElementById('exporterQuoteBtn').innerHTML = '<button class="btn btn-success" onclick="exporter_quote_editForm(\''+id_ord_schedule+'\',\''+last_ship_nr+'\',\''+id_ord_order+'\',\''+pipeline_id+'\');" type="button"><i class="fa fa-edit"></i></button>';
}


function sendProposal(id_ord_schedule){

	var resurl='listeslies.php?elemid=send_proposal&id_ord_schedule='+id_ord_schedule;    
    var xhr = getXhr();  
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;  
			
			if(leselect==1){
				toastr.success('Proposal successfully sent',{timeOut:15000})
				$("#crm_request_freight_tab").removeClass("hide");
				$("#crm_request_freight_ct").removeClass("hide");
				
				sendProposalMail(id_ord_schedule,0);
				crm_manag(0,0);
			} else 
			if(leselect==0){
				toastr.error('Error sending proposal, please retry',{timeOut:15000})
			} else {
				internal_error(); 
			}
			
			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function sendProposalMail(id_ord_schedule,send){  
	var resurl='listeslies.php?elemid=send_proposal_mail&id_ord_schedule='+id_ord_schedule+'&proposal_mail='+send;    
    var xhr = getXhr();  
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;  
			var val = leselect.split('##');
			
			if(send==1){  
				if(val[0]==1){
					toastr.success('Proposal mail successfully sent',{timeOut:15000})
					$("#proposalMailModal").modal("hide");
					
					var bcc="";  
					saveMailAsPdf(val[1],val[2],val[3],'system',val[5],val[6],'ProposalMail',val[4],bcc,val[7]);
					
				} else
				if(val[0]==0){
					toastr.error('Error sending proposal mail, please retry',{timeOut:15000})
				} else {
					internal_error(); 
				}
				
			} else {
				document.getElementById('show_proposal_content').innerHTML = leselect;
				document.getElementById('proposal_mail_footer').innerHTML = '<button type="button" onclick="javascript:sendProposalMail('+id_ord_schedule+',1);" class="btn btn-info pull-left">Send mail</button>'
				+'<button type="button" class="btn btn-danger pull-right" data-dismiss="modal"><i class="fa fa-ban"></i> Close</button>';
				
				$("#proposalMailModal").modal("show");
			}
			
			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function proposalDoc(id_ord_schedule,order_ship_nr,ord_order_id){
	var resurl='listeslies.php?elemid=create_proposal_document&id_ord_schedule='+id_ord_schedule+'&order_ship_nr='+order_ship_nr;    
    var xhr = getXhr();  
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;   
			
			if(leselect==1){
				toastr.success('Proposal document successfully created',{timeOut:15000})
				sendProposalToCustomerMail(ord_order_id,0);
				crm_manag(0,0);
				
			} else
			if(leselect==0){
				toastr.error('Error creating proposal document, please retry',{timeOut:15000})
			} else {
				internal_error(); 
			}
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function sendProposalToCustomerMail(ord_order_id,send) {
	var resurl='listeslies.php?elemid=proposal_to_customer_mail&ord_order_id='+ord_order_id+'&proposal_mail='+send;    
    var xhr = getXhr();  
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;
			var val = leselect.split('##');
			
			if(send==1){  
				if(val[0]==1){
					toastr.success('Mail successfully sent',{timeOut:15000})
					$("#proposalToCusMailModal").modal("hide");
					
					var bcc="";  
					saveMailAsPdf(val[1],val[2],val[3],'system',val[5],val[6],'ProposaltoCustomer',val[4],bcc,val[6]);
					
				} else 
				if(val[0]==0){
					toastr.error('Error sending mail, please retry',{timeOut:15000})
				} else {
					internal_error(); 
				}
				
			} else {
				document.getElementById('show_proposal_to_cus_content').innerHTML = leselect;
				document.getElementById('proposal_to_cus_mail_footer').innerHTML = '<button type="button" onclick="javascript:sendProposalToCustomerMail('+ord_order_id+',1);" class="btn btn-info pull-left">Send mail</button>'
				+'<button type="button" class="btn btn-danger pull-right" data-dismiss="modal"><i class="fa fa-ban"></i> Close</button>';
				
				$("#proposalToCusMailModal").modal("show");
			}
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


var contact_type;

function contactList(conf,status,id_cooperative,completed,pending) {
	contact_type = conf;
	
	if(conf == 9){ 
		titleMenuManag(lg_menu_contact_people,"btn_ctp");
		$("#contCoopFilter").addClass("hide");
		$("#contFarmerFilter").addClass("hide");
		$("#contFarmerFilter2").addClass("hide");
		
		$("#contSatusFilter").addClass("hide");
		$("#contSearch").removeClass("col-md-7");
		$("#contSearch").addClass("col-md-12");
		
	} else	
	if(conf == 10){
		titleMenuManag(lg_menu_contact_organization,"btn_cto");
		$("#contCoopFilter").addClass("hide");
		$("#contFarmerFilter").addClass("hide");
		$("#contFarmerFilter2").addClass("hide");
		
		$("#contSatusFilter").addClass("hide");
		$("#contSearch").removeClass("col-md-7");
		$("#contSearch").addClass("col-md-12");
		
	} else	
	if(conf == 115){
		titleMenuManag(lg_menu_contact_farmer,"btn_ctf");
		
		$("#contCoopFilter").removeClass("hide");
		$("#contFarmerFilter").removeClass("hide");
		$("#contFarmerFilter2").removeClass("hide");
		$("#contSatusFilter").removeClass("hide");
		$("#contSearch").removeClass("col-md-12");
		$("#contSearch").addClass("col-md-7");
		
		$('.i-checks').iCheck({
			checkboxClass: 'icheckbox_square-green',
			radioClass: 'iradio_square-green'
		});

		$('#FarmerCompleted').on('ifChecked', function(event){  
			contactList(conf,status,id_cooperative,1,pending);
			return;
		});
		
		$('#FarmerCompleted').on('ifUnchecked', function(event){   
			contactList(conf,status,id_cooperative,0,pending);
			return;
		});
		
		$('#FarmerPending').on('ifChecked', function(event){  
			contactList(conf,status,id_cooperative,completed,1);
			return;
		});
		
		$('#FarmerPending').on('ifUnchecked', function(event){   
			contactList(conf,status,id_cooperative,completed,0);
			return;
		});
	
		$("#icoop_msg").removeClass("hide");
		
	} else {
		document.getElementById('pageTitle').innerHTML = lg_menu_contact;
	}
	
	if(conf!=0) {
		hideAll();
		$("#db_content").removeClass("hide");
		$("#contactSpanner").removeClass("hide");
	}
	
	var resurl='include/contact.php?elemid=contact_list&conf='+conf+'&status='+status+'&id_cooperative='+id_cooperative+'&completed='+completed+'&pending='+pending;   
    var xhr = getXhr(); 
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;   // console.log(leselect);
			var val = leselect.split('##');  

			if(conf == 0){
				document.getElementById('nb_people').innerHTML = val[1];
				document.getElementById('nb_organisation').innerHTML = val[2];
				document.getElementById('nb_farmer').innerHTML = val[3];
				
			} else {
				document.getElementById('d2_content').innerHTML = val[0];

				var options = {
					valueNames: ['contact_name']
				};

				var userList = new List('contacts', options);

				document.getElementById('nb_people').innerHTML = val[1];
				document.getElementById('nb_organisation').innerHTML = val[2];
				document.getElementById('nb_farmer').innerHTML = val[3];
				
				$("#contactSpanner").addClass("hide");
				
				$('#d2_content li a').click(function() {
					$('ul li.on').removeClass('on');
					$(this).closest('li').addClass('on');
				});

				document.getElementById('bio').innerHTML = '<span style="font-size: 14px;"><i class="fa fa-hand-o-left"></i> '+lg_cont_user_in_list+'</span>';
				document.getElementById('demog').innerHTML = '<span style="font-size: 14px;"><i class="fa fa-hand-o-left"></i> '+lg_cont_user_in_list+'</span>';
				document.getElementById('links').innerHTML = '<span style="font-size: 14px;"><i class="fa fa-hand-o-left"></i> '+lg_cont_user_in_list+'</span>';
				document.getElementById('plantation').innerHTML = '<span style="font-size: 14px;"><i class="fa fa-hand-o-left"></i> '+lg_cont_user_in_list+'</span>';
				document.getElementById('household').innerHTML = '<span style="font-size: 14px;"><i class="fa fa-hand-o-left"></i> '+lg_cont_user_in_list+'</span>';
				document.getElementById('ct_household_content').innerHTML = '';
			}
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function contFilter(id_supchain_type) {
	var statut = document.getElementById('list_mobile_status').value;
	
	var id_cooperative = 0;
	if(id_supchain_type!=331) {
		id_cooperative = document.getElementById('list_cooperatives').value;
	} 
	
	contactList(contact_type,statut,id_cooperative,0);
}


/*
** Start Survey **
*/

function survey() {
	titleMenuManag(lg_menu_survey,"btn_survey");
	
	hideAll();
	$("#db_survey").removeClass("hide");
	
	var resurl='include/survey.php?elemid=template_list';    
	var xhr = getXhr();   
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;
			document.getElementById('sv_template_list').innerHTML = leselect;
			
			var options = {
				valueNames: ['sv_template_name']
			};

			var contractingList = new List('templates', options);
			
			$('#sv_template_list li').click(function() {
				$('ul li.on2').removeClass('on2');
				$(this).closest('li').addClass('on2');
			});
		}
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);	
}

var quill;

function msgManage() {
	
	if(quill){ destory_editor('#eMailText'); }

	var resurl='listeslies.php?elemid=eMail_form';  
	var xhr = getXhr(); 
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;   
			
			document.getElementById('box_eMailTo').innerHTML = '<select class="form-control dual_select" id="eMailTo" multiple>'+leselect+'</select>';
			document.getElementById('box_eMailCc').innerHTML = '<select class="form-control dual_select" id="eMailCc" multiple>'+leselect+'</select>';
			document.getElementById('box_eMailBcc').innerHTML = '<select class="form-control dual_select" id="eMailBcc" multiple>'+leselect+'</select>';
		
			$('.dual_select').bootstrapDualListbox({
				selectorMinimalHeight: 160
			}); 
			
			$("#icoopMsgModal").modal("show");
			
			quill = new Quill('#eMailText', {
				theme: 'snow' 
			}); 
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}

function resetSendeMAil() { 
	document.getElementById("list_eMailTo").innerHTML = "";
	document.getElementById("control_eMailTo").innerHTML = '<a href="#" data-toggle="modal" data-target="#eMailToModal">Add</a>';
	
	document.getElementById("list_eMailCc").innerHTML = "";
	document.getElementById("control_eMailCc").innerHTML = '<a href="#" data-toggle="modal" data-target="#eMailCcModal">Add</a>';
	
	document.getElementById("list_eMailBcc").innerHTML = "";
	document.getElementById("control_eMailBcc").innerHTML = '<a href="#" data-toggle="modal" data-target="#eMailBccModal">Add</a>';
	
	if(quill){ quill.setContents([]); }
}

function showEmailToList() {  
	$("#eMailToModal").modal("hide");
	
	var recep ='';
	var action = 'Add';
	
	var to = $('#eMailTo').val();  
	if(to != null){
		$.each( to, function( index, value ){
			if (value != null){ recep=recep+value+'<br>'; }
		});
		
		action = 'Edit';
	}
	
	document.getElementById("list_eMailTo").innerHTML = recep;
	document.getElementById("control_eMailTo").innerHTML = '<a href="#" data-toggle="modal" data-target="#eMailToModal">'+action+'</a>';
}


function showEmailCcList() {
	$("#eMailCcModal").modal("hide");
	
	var cc_rp =''; 
	var action = 'Add';
	
	var cc = $("#eMailCc").val();  
	if(cc != null){
		$.each( cc, function( index, value ){
			if (value != null){ cc_rp=cc_rp+value+'<br>'; }
		});
		
		action = 'Edit';
	}
	
	document.getElementById("list_eMailCc").innerHTML = cc_rp;
	document.getElementById("control_eMailCc").innerHTML = '<a href="#" data-toggle="modal" data-target="#eMailCcModal">'+action+'</a>';
}



function showEmailBccList() {
	$("#eMailBccModal").modal("hide");
	
	var bcc_rp ='';
	var action = 'Add';
	
	var bcc = $("#eMailBcc").val();  
	if(bcc != null){
		$.each( bcc, function( index, value ){ 
			if (value != null){ bcc_rp=bcc_rp+value+'<br>'; }
		});
		
		action = 'Edit';
	}
	
	document.getElementById("list_eMailBcc").innerHTML = bcc_rp;
	document.getElementById("control_eMailBcc").innerHTML = '<a href="#" data-toggle="modal" data-target="#eMailBccModal">'+action+'</a>';
}


function newTemplate() { 
	document.getElementById('surveyModalHeader').innerHTML = "New Template";
	document.getElementById('surveyModalContent').innerHTML = '<div class="row">'+
		'<div class="col-md-12">'+
			'<div class="form-group">'+
				'<label for="surv_description">Description *</label>'+
				'<input type="text" class="form-control" value="" id="surv_description">'+
			'</div>'+
		'</div>'+
		
		'<div class="col-md-12">'+
			'<div class="form-group">'+
				'<label for="surv_survey_date">Date *</label>'+
				'<div class="input-group date">'+
					'<span class="input-group-addon"><i class="fa fa-calendar"></i></span>'+
					'<input type="text" class="form-control edit_delivery_date" value="" id="surv_survey_date">'+
				'</div>'+
			'</div>'+
		'</div>'+
	'</div>';

	document.getElementById('surveyModalFooter').innerHTML = '<button type="button" class="btn btn-primary pull-left" onclick="save_newTemplate();">Save</button> '
	+'<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>'; 
	
	$('.edit_delivery_date').datepicker({
		format: "yyyy/mm/dd",
		calendarWeeks:true,
		autoclose: true
	}).datepicker('setDate', new Date());
}

function save_newTemplate() {
	var description = document.getElementById('surv_description').value;
	var survey_date = document.getElementById('surv_survey_date').value;
	
	var resurl='include/survey.php?elemid=save_new_template&description='+description+'&survey_date='+survey_date;     
	var xhr = getXhr();   
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText; 
			
			if(leselect==1){
				toastr.success('Template successfully saved',{timeOut:15000})
				$("#surveyModal").modal("hide");
				survey();
			} else 
			if(leselect==0){
				toastr.error('Template not saved',{timeOut:15000})
			} else {
				internal_error();
			}
		}
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);	
}

function showSurvQuestions(id_survey) {
	document.getElementById('list_tpQuestions').innerHTML = "";
	document.getElementById('list_tpAnswers').innerHTML = "";
	document.getElementById('surv_template_id').value = id_survey;
	
	var resurl='include/survey.php?elemid=questions_list&id_survey='+id_survey+'&deleteRight='+survey_delete+'&editRight='+survey_update;     
	var xhr = getXhr();   
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;
			document.getElementById('list_tpQuestions').innerHTML = leselect;
		}
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);	
}

function newQuestion() {
	document.getElementById('surveyModalHeader').innerHTML = "New Question";
	document.getElementById('surveyModalContent').innerHTML = '<div class="row">'+
		'<div class="col-md-6">'+
			'<div class="form-group">'+
				'<label for="surv_q_seq">Sequence *</label>'+
				'<input type="number" class="form-control" value="" id="surv_q_seq">'+
			'</div>'+
		'</div>'+
		
		'<div class="col-md-12">'+
			'<div class="form-group">'+
				'<label for="surv_q_text">Question *</label>'+
				'<input type="text" class="form-control" value="" id="surv_q_text">'+
			'</div>'+
		'</div>'+
	'</div>';
	
	document.getElementById('surveyModalFooter').innerHTML = '<button type="button" class="btn btn-primary pull-left" onclick="save_newQuestion();">Save</button> '
	+'<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>'; 
}

function save_newQuestion() {
	var surtemplate_id = document.getElementById('surv_template_id').value;
	if(surtemplate_id!="") {
		var q_seq = document.getElementById('surv_q_seq').value;
		var q_text = document.getElementById('surv_q_text').value;
		
		var resurl='include/survey.php?elemid=save_new_question&surtemplate_id='+surtemplate_id+'&q_seq='+q_seq+'&q_text='+q_text;     
		var xhr = getXhr();   
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;  
				
				if(leselect==1){
					toastr.success('Question successfully saved',{timeOut:15000})
					$("#surveyModal").modal("hide");
					showSurvQuestions(surtemplate_id);
				} else 
				if(leselect==0){
					toastr.error('Question not saved',{timeOut:15000})
				} else {
					internal_error();
				}
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);	
	
	} else {
		toastr.info('Select a template',{timeOut:15000})
	}
}

function editQuestion(id_surq) {
	document.getElementById('surveyModalHeader').innerHTML = "Edit Question";
	
	var resurl='include/survey.php?elemid=edit_question&id_surq='+id_surq;     
	var xhr = getXhr();   
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText; 
			
			document.getElementById('surveyModalContent').innerHTML = leselect;
			document.getElementById('surveyModalFooter').innerHTML = '<button type="button" class="btn btn-primary pull-left" onclick="save_editQuestion('+id_surq+');">Save</button> '
			+'<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>'; 
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}

function save_editQuestion(id_surq) {
	var surtemplate_id = document.getElementById('surv_template_id').value;
	var q_seq = document.getElementById('surv_q_seq').value;
	var q_text = document.getElementById('surv_q_text').value;
	
	var resurl='include/survey.php?elemid=save_edited_question&id_surq='+id_surq+'&q_seq='+q_seq+'&q_text='+q_text;     
	var xhr = getXhr();   
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;  
		
			if(leselect==1){
				toastr.success('Question successfully saved',{timeOut:15000})
				$("#surveyModal").modal("hide");
				showSurvQuestions(surtemplate_id);
			} else 
			if(leselect==0){
				toastr.error('Question not saved',{timeOut:15000})
			} else {
				internal_error();
			}
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}

function delQuestion(id_surq) {
	var surtemplate_id = document.getElementById('surv_template_id').value;
	
	var confr = confirm("This action will delete also all the answers linked to this question, Are you sure you want to delete this question ?");
	if(confr == true){ 
		var resurl='include/survey.php?elemid=delete_question&id_surq='+id_surq;     
		var xhr = getXhr();   
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;  
				
				if(leselect==1){
				toastr.success('Question successfully deleted',{timeOut:15000})
					showSurvQuestions(surtemplate_id);
				} else 
				if(leselect==0){
					toastr.error('Question not deleted',{timeOut:15000})
				} else {
					internal_error();
				}
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
	}
}


function showSurvAnswers(id_surq) {
	document.getElementById('surv_id_surq').value = id_surq;
	var resurl='include/survey.php?elemid=answers_list&id_surq='+id_surq+'&deleteRight='+survey_delete+'&editRight='+survey_update;    
	var xhr = getXhr();   
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;
			document.getElementById('list_tpAnswers').innerHTML = leselect;
		}
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);	
}

function newAnswer() {
	document.getElementById('surveyModalHeader').innerHTML = "New Answer";
	document.getElementById('surveyModalContent').innerHTML = '<div class="row">'+
		'<div class="col-md-6">'+
			'<div class="form-group">'+
				'<label for="surv_score">Score *</label>'+
				'<input type="number" class="form-control" value="" id="surv_score">'+
			'</div>'+
		'</div>'+
		
		'<div class="col-md-6">'+
			'<div class="form-group">'+
				'<label for="surv_ans_code">Code *</label>'+
				'<input type="text" class="form-control" value="" id="surv_ans_code">'+
			'</div>'+
		'</div>'+
		
		'<div class="col-md-12">'+
			'<div class="form-group">'+
				'<label for="surv_ans_text_en">Answer En *</label>'+
				'<textarea class="form-control" id="surv_ans_text_en"></textarea>'+
			'</div>'+
		'</div>'+
		
		'<div class="col-md-12">'+
			'<div class="form-group">'+
				'<label for="surv_ans_text_fr">Answer Fr</label>'+
				'<textarea class="form-control" id="surv_ans_text_fr"></textarea>'+
			'</div>'+
		'</div>'+
	'</div>';
	
	document.getElementById('surveyModalFooter').innerHTML = '<button type="button" class="btn btn-primary pull-left" onclick="save_newAnswer();">Save</button> '
	+'<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>'; 
}

function save_newAnswer() {
	var id_surq = document.getElementById('surv_id_surq').value;
	if(id_surq!=""){
		var ans_text_fr = document.getElementById('surv_ans_text_fr').value;
		var ans_text_en = document.getElementById('surv_ans_text_en').value;
		var ans_code = document.getElementById('surv_ans_code').value;
		var score = document.getElementById('surv_score').value;
		
		var resurl='include/survey.php?elemid=save_new_answer&id_surq='+id_surq+'&ans_text_fr='+ans_text_fr+'&ans_text_en='+ans_text_en+'&ans_code='+ans_code+'&score='+score;     
		var xhr = getXhr();   
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;  
				
				if(leselect==1){
					toastr.success('Answer successfully saved',{timeOut:15000})
					$("#surveyModal").modal("hide");
					showSurvAnswers(id_surq);
				} else 
				if(leselect==0){
					toastr.error('Answer not saved',{timeOut:15000})
				} else {
					internal_error();
				}
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
	
	} else {
		toastr.info('Select a question',{timeOut:15000})
	}
}

function editAnswer(id_suranswer) { 

	document.getElementById('surveyModalHeader').innerHTML = "Edit Answer";
	
	var resurl='include/survey.php?elemid=edit_answer&id_suranswer='+id_suranswer;     
	var xhr = getXhr();   
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText; 
			
			document.getElementById('surveyModalContent').innerHTML = leselect;
			document.getElementById('surveyModalFooter').innerHTML = '<button type="button" class="btn btn-primary pull-left" onclick="save_editAnswer('+id_suranswer+');">Save</button> '
			+'<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>'; 
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}

function save_editAnswer(id_suranswer) {
	var id_surq = document.getElementById('surv_id_surq').value;
	var ans_text_fr = document.getElementById('surv_ans_text_fr').value;
	var ans_text_en = document.getElementById('surv_ans_text_en').value;
	var ans_code = document.getElementById('surv_ans_code').value;
	var score = document.getElementById('surv_score').value;
	
	var resurl='include/survey.php?elemid=save_edited_answer&id_suranswer='+id_suranswer+'&ans_text_fr='+ans_text_fr+'&ans_text_en='+ans_text_en+'&ans_code='+ans_code+'&score='+score;     
	var xhr = getXhr();   
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;  
			
			if(leselect==1){
				toastr.success('Answer successfully saved',{timeOut:15000})
				$("#surveyModal").modal("hide");
				showSurvAnswers(id_surq);
			} else 
			if(leselect==0){
				toastr.error('Answer not saved',{timeOut:15000})
			} else {
				internal_error();
			}
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}

function delAnswer(id_suranswer) {
	var id_surq = document.getElementById('surv_id_surq').value;
	
	var confr = confirm("Are you sure you want to delete this answer ?");
	if(confr == true){ 
		var resurl='include/survey.php?elemid=delete_answer&id_suranswer='+id_suranswer;     
		var xhr = getXhr();   
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;
				
				if(leselect==1){
				toastr.success('Answer successfully deleted',{timeOut:15000})
					showSurvAnswers(id_surq);
				} else 
				if(leselect==0){
					toastr.error('Answer not deleted',{timeOut:15000})
				} else {
					internal_error();
				}
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
	}
}


/*  Campain */

function survey_campaign() {
	titleMenuManag(lg_menu_survey_campaign,"btn_suv_camp");
	
	hideAll();
	$("#db_survey_campaign").removeClass("hide");
	$("#no_campain_cp").removeClass("hide");
	
	$("#surv_camp_send_btn").prop("disabled", true);
	
	if(quill){ destory_editor('#surv_camp_content'); }
	
	var resurl='include/survey.php?elemid=organisation_list&conf=campaign';    
	var xhr = getXhr();   
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;  
			var val = leselect.split('@@');
			
			document.getElementById('surv_camp_org').innerHTML = val[0];
			document.getElementById('surv_camp_template').innerHTML = val[1];
			
			quill = new Quill('#surv_camp_content', {
				theme: 'snow' 
			});
			
			var options = {
				valueNames: ['camp_org_name']
			};

			var contractingList = new List('campaign_orgs', options);
			
			$('#surv_camp_org li').click(function() {
				$('ul li.on2').removeClass('on2');
				$(this).closest('li').addClass('on2');
			});
			
			$('.i-checks').iCheck({
				checkboxClass: 'icheckbox_square-green',
				radioClass: 'iradio_square-green'
			});
		}
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);	
}

var liste = [];

function showCampContacts(id_contact) {
	
	var resurl='include/survey.php?elemid=contact_list&conf=campaign&id_company='+id_contact;    
	var xhr = getXhr();   
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;   
			
			$("#surv_camp_send_btn").prop("disabled", false);
			$("#no_campain_cp").addClass("hide");
			
			
			document.getElementById('surv_camp_ppl').innerHTML = leselect;
			
			var options = {
				valueNames: ['camp_contact']
			};

			var contractingList = new List('campaign_contacts', options);

			$('.i-checks').iCheck({
				checkboxClass: 'icheckbox_square-green',
				radioClass: 'iradio_square-green'
			});
			
			liste = [];
			
			$('input[name=camp_contact]').on('ifChecked', function(event){
				liste.push($(event.target).val());
				
				var html="";
				liste.forEach(function(item) {
					html+='<span class="badge m-xxs">'+item+'</span>';
				});

				document.getElementById('surv_camp_to').innerHTML = html;
			});
			
			$('input[name=camp_contact]').on('ifUnchecked', function(event){
				liste = jQuery.grep(liste, function(value) {
					return value != $(event.target).val();
				});
				
				var html="";
				liste.forEach(function(item) {
					html+='<span class="badge m-xxs">'+item+'</span>';
				});

				document.getElementById('surv_camp_to').innerHTML = html;
			});
			
			$('input[name=all_comp]').on('ifChecked', function(event){
				$('input[name=camp_contact]').iCheck('check');
			});
			
			$('input[name=all_comp]').on('ifUnchecked', function(event){
				$('input[name=camp_contact]').iCheck('uncheck');
			});
		}
    };  

    xhr.open("GET",resurl,true);
    xhr.send(null);	
}

function sendCampagn() {
	
	var l = $( '.ladda-button-demo' ).ladda();
	
	var subject = document.getElementById('surv_camp_subject').value;
	var template = document.getElementById('surv_camp_template').value;
	var contenu = quill.root.innerHTML;    
	
	var itemsProcessed = 0;
	l.ladda( 'start' );
	
	liste.forEach(function(item) {
		var resurl='include/survey.php?elemid=send_campaign&subject='+subject+'&template='+template+'&contenu='+contenu+'&to='+item;    
		var xhr = getXhr();    
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;   
				
				if(leselect==1){
					toastr.success('Email sent.',{timeOut:15000})
				} else 
				if(leselect==0){
					toastr.error('Email not sent.',{timeOut:15000})
				} else {
					internal_error();
				}
			
				itemsProcessed++;
				if(itemsProcessed === liste.length) {
				  l.ladda('stop');
				}
			}
		};  

		xhr.open("GET",resurl,true);
		xhr.send(null);	
	});	
}

function survCtDiscard() {
	quill.setContents([]);
}


/*  Campain Results */

function survey_campaign_result() {
	titleMenuManag(lg_menu_survey_campaign_results,"btn_suv_camp_result");
	
	hideAll();
	$("#db_survey_campaign_results").removeClass("hide");
	$("#surCampDelAll").prop("disabled", true);
	
	var resurl='include/survey.php?elemid=organisation_list&conf=result';    
	var xhr = getXhr();   
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;   
			var val = leselect.split('@@');
			
			document.getElementById('surv_rslt_org').innerHTML = val[0];
			document.getElementById('surv_camp_rslt_template').innerHTML = val[1];
			
			var options = {
				valueNames: ['campResult_org_name']
			};

			var contractingList = new List('campaign_rslt_org', options);
			
			$('#surv_rslt_org li').click(function() {
				$('ul li.on2').removeClass('on2');
				$(this).closest('li').addClass('on2');
			});
		}
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);	
}

function showCampResultContacts(id_contact) {
	
	var resurl='include/survey.php?elemid=contact_list&conf=results&id_company='+id_contact;    
	var xhr = getXhr();   
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;   
			
			$("#no_campain_rslt_cp").addClass("hide");
			
			document.getElementById('surv_camp_rslt_ppl').innerHTML = leselect;
			
			var options = {
				valueNames: ['camp_resutl_contact']
			};

			var contractingList = new List('campaign_rslt_contacts', options);

			$('.i-checks').iCheck({
				checkboxClass: 'icheckbox_square-green',
				radioClass: 'iradio_square-green'
			});
			
			$('input[name=comp_result]').on('ifChecked', function(event){  
				document.getElementById('surv_camp_rslt_contact').value = $(event.target).val();
				showCampaignResult();
			});
		}
    };  

    xhr.open("GET",resurl,true);
    xhr.send(null);	
}

function showCampaignResult() {
	var surtemplate_id = document.getElementById('surv_camp_rslt_template').value;
	var id_contact = document.getElementById('surv_camp_rslt_contact').value;
	var resurl='include/survey.php?elemid=campaign_results_contact&id_contact='+id_contact+'&surtemplate_id='+surtemplate_id;    
	var xhr = getXhr();   
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText; 
			document.getElementById('campain_rslt').innerHTML = leselect;
			$("#surCampDelAll").prop("disabled", false);
		}
    };  

    xhr.open("GET",resurl,true);
    xhr.send(null);	
}

function delCampUserAns(id_suranswer) {
	
	var confr = confirm("Are you sure you want to delete this answer ?");

	if(confr == true){ 
		var resurl='include/survey.php?elemid=delete_user_survey_answer&id_suranswer='+id_suranswer;    
		var xhr = getXhr();   
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;
				
				if(leselect==1){
					toastr.success('Answer deleted.',{timeOut:15000})
					showCampaignResult();
				} else 
				if(leselect==0){
					toastr.error('Answer not deleted.',{timeOut:15000})
				} else {
					internal_error();
				}	
				
			}
		};  

		xhr.open("GET",resurl,true);
		xhr.send(null);	
	}
}


function delAllCampUserAns() {
	var surtemplate_id = document.getElementById('surv_camp_rslt_template').value;
	var id_contact = document.getElementById('surv_camp_rslt_contact').value;
	
	var confr = confirm("Are you sure you want to all the answers ?");

	if(confr == true){ 
		var resurl='include/survey.php?elemid=delete_all_user_survey_answer&id_contact='+id_contact+'&surtemplate_id='+surtemplate_id; 
		var xhr = getXhr();   
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;
				
				if(leselect==1){
					toastr.success('Answers deleted.',{timeOut:15000})
					showCampaignResult();
				} else 
				if(leselect==0){
					toastr.error('Answers not deleted.',{timeOut:15000})
				} else {
					internal_error();
				}	
				
			}
		};  

		xhr.open("GET",resurl,true);
		xhr.send(null);	
	}
}


/*
** End Survey **
*/



/*
** Start Freight **
*/

function listOfFreight(pol_id,pod_id,package_type_id,id_ord_schedule) { 

	var resurl='include/freight.php?elemid=first_separate_freights_list&pol_id='+pol_id+'&package_type_id='+package_type_id;    
	var xhr = getXhr();   
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;     
			var val = leselect.split('##');

			if(val[0]==1){
				
				document.getElementById('freightModalLabel').innerHTML = "First freight";
				
				document.getElementById('freightListTable').innerHTML = val[1];
				document.getElementById('freight_id_ord_schedule').value = id_ord_schedule;
				document.getElementById('freight_sequence_nr').value = 1;
				
				document.getElementById('freight_pol_id').value = pol_id;
				document.getElementById('freight_pod_id').value = pod_id;
				document.getElementById('freight_package_type_id').value = package_type_id;
				crm_manag(0,0);
				
				document.getElementById('freight_modal_footer').innerHTML = '<button type="button" class="btn btn-success" onclick="saveFreight();"><i class="fa fa-save"></i></button>'
				+'<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i></button>';
				
				$("#freightListModal").modal("show");
				
			} else {
				swal({
					title: "Sorry",
					text: "No freight found!",
					type: "warning"
				});
			}
			
			leselect = xhr.responseText;
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}


function cheickForFreight(pol_id,pod_id,cus_incoterms_id,package_type_id,id_ord_schedule) { 

	if((pol_id!="")&&(pod_id!="")&&(cus_incoterms_id!="")&&(package_type_id!="")){  
		var resurl='include/freight.php?elemid=cheick_for_freight&pol_id='+pol_id+'&pod_id='+pod_id+'&cus_incoterms_id='+cus_incoterms_id+'&package_type_id='+package_type_id+'&id_ord_schedule='+id_ord_schedule;    
		var xhr = getXhr();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;  
				var val2 = leselect.split('##');
				
				if(val2[0]==0){ 
					swal({
						title: "Not found",
						text: "No direct freight schedule found!",
						type: "warning",
						showCancelButton: true,
						confirmButtonColor: "#1ab394",
						confirmButtonText: "Add new freight",
						closeOnConfirm: true
					}, function () {
						listOfFreight(pol_id,pod_id,package_type_id,id_ord_schedule);  
					});
					
				} else
				if(val2[0]==1){
					document.getElementById('freightModalLabel').innerHTML = "First freight";
				
					document.getElementById('freightListTable').innerHTML = val2[1];
					document.getElementById('freight_sequence_nr').value = 1;
					document.getElementById('freight_id_ord_schedule').value = id_ord_schedule;
			
					document.getElementById('freight_modal_footer').innerHTML = '<button type="button" class="btn btn-success" onclick="saveFirstFreight();"><i class="fa fa-save"></i></button>'
						+'<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i></button>';
					
					$("#freightListModal").modal("show");
				
				} else {
					internal_error(); 
				}
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
		
	} 
	
}


function saveFirstFreight() {
	var id_con_box_fr = '';

	if($("input[type='radio'].radioBtnFreightListClass").is(':checked')) {
		id_con_box_fr = $("input[type='radio'].radioBtnFreightListClass:checked").val();
	}
	
	var id_ord_schedule = document.getElementById('freight_id_ord_schedule').value;
	var sequence_nr = document.getElementById('freight_sequence_nr').value;
	
	var resurl='include/freight.php?elemid=save_freight&id_ord_schedule='+id_ord_schedule+'&id_con_box_fr='+id_con_box_fr+'&sequence_nr='+sequence_nr;   
	var xhr = getXhr();  
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;    
			var val = leselect.split('##');
			
			if(val[0] == 1){
				toastr.success('Freight saved successfully',{timeOut:15000})
		
				var pol_name = val[1];
				var dem_pol_free = val[2];
				var incoterm_name = val[3];
				var pod_name = val[4];
				var dem_pod_free = val[5];
				var carrier  = val[6];
				var rate_valid_until  = val[7];
				var packaging_type_name  = val[8];
				var trans_delay  = val[9];
				
				showFreight(pol_name,dem_pol_free,incoterm_name,pod_name,dem_pod_free,carrier,rate_valid_until,packaging_type_name,trans_delay,1);
				
				$("#freightListModal").modal("hide");
				
			} else 
			if(val[0] == 0){
				toastr.error('Error saving freight, please retry',{timeOut:15000})
			} else {
				internal_error();
			}
			
			leselect = xhr.responseText;
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}


function showFreight(pol_name,dem_pol_free,incoterm_name,pod_name,dem_pod_free,carrier,rate_valid_until,packaging_type_name,trans_delay,num) {
	
	document.getElementById('freight_1').innerHTML = '';
	document.getElementById('freight_2').innerHTML = '';
	
	var resurl='include/freight.php?elemid=show_saved_freigt&pol_name='+pol_name+'&dem_pol_free='+dem_pol_free+'&incoterm_name='+incoterm_name+'&pod_name='+pod_name+'&dem_pod_free='+dem_pod_free+'&carrier='+carrier+'&rate_valid_until='+rate_valid_until+'&packaging_type_name='+packaging_type_name+'&trans_delay='+trans_delay;   
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText; 
			
			document.getElementById('freight_'+num).innerHTML = leselect;
			
			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


// function newFreight() {
	// var resurl='listeslies.php?elemid=freight_form&num=2';   
    // var xhr = getXhr();
	// xhr.onreadystatechange = function(){
        // if(xhr.readyState == 4 ){
            // leselect = xhr.responseText; 
			
			// document.getElementById('freight_2').innerHTML = leselect;
			
			// leselect = xhr.responseText;
        // }
    // };

    // xhr.open("GET",resurl,true);
    // xhr.send(null);
// }

	
function showFreightList(id_ord_schedule,last_shipment,ord_order_id,pipeline_id) {  
	
	document.getElementById('freight_1').innerHTML = '<div class="h1 m-t-xs text-navy"><span class="loading"></span></div>';
	document.getElementById('freight_2').innerHTML = '';
	
	var mail = '<a href="#" class="pull-right" style="margin-left:10px; color:#fff;"  onclick="eMailForm(\''+ord_order_id+'\',\'crm\',\'\');"><i class="fa fa-envelope"></i></a>';
	
	document.getElementById('summaryDocs').innerHTML = mail;
	document.getElementById('requestDocs').innerHTML = '';
	
	var resurl='include/freight.php?elemid=list_of_saved_freight&id_ord_schedule='+id_ord_schedule;
		var xhr = getXhr();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;      
				var val2 = leselect.split('??');
				
				$('#freight_list li a').click(function() { 
					$('ul li.highlight_freight').removeClass('highlight_freight');
					$(this).closest('li').addClass('highlight_freight');
				});
	
				if(val2[0]==0){
					var elmt = val2[1].split('#');
					if(freight_update == 1){
						var addFreightBtn1 = '<button type="submit" id="addFreight1" class="btn btn-success btn-sm pull-right" onclick="cheickForFreight(\''+elmt[0]+'\',\''+elmt[1]+'\',\''+elmt[2]+'\',\''+elmt[3]+'\',\''+elmt[4]+'\');"><i class="fa fa-plus"></i></button>';
						var addFreightBtn2 = '<button type="submit" id="addFreight2" class="btn btn-success btn-sm pull-right" onclick="secondFreightList(\''+elmt[1]+'\',\''+elmt[3]+'\',\''+elmt[4]+'\');"><i class="fa fa-plus"></i></button>';
					} else {
						var addFreightBtn1 = '';
						var addFreightBtn2 = '';
					}
					
					// Freight status 
					if(elmt[6]==1){  
						if(calc_read == 1){ $("#crm_request_calc_tab").removeClass("hide"); $("#crm_request_calc_ct").removeClass("hide"); } 
						else { $("#crm_request_calc_tab").addClass("hide"); $("#crm_request_calc_ct").addClass("hide"); }
						
						if(proposal_read == 1){ $("#crm_request_proposal_tab").removeClass("hide"); $("#crm_request_proposal_ct").removeClass("hide"); } 
						else { $("#crm_request_proposal_tab").addClass("hide"); $("#crm_request_proposal_ct").addClass("hide"); }
						
					} else {
						$("#crm_request_calc_tab").addClass("hide"); $("#crm_request_calc_ct").addClass("hide"); 
						$("#crm_request_proposal_tab").addClass("hide"); $("#crm_request_proposal_ct").addClass("hide");
					}
				
					document.getElementById('F1_title').innerHTML = 'Ocean Freight '+addFreightBtn1;
					document.getElementById('F2_title').innerHTML = 'Onward Freight '+addFreightBtn2;
					
					document.getElementById('freight_1').innerHTML = '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> No saved freight';
					
				} else {
					
					document.getElementById('freight_1').innerHTML = '';
					document.getElementById('freight_2').innerHTML = '';
					
					var elmt2 = val2[2].split('#');
					if(freight_update == 1){
						var addFreightBtn1 = '<button type="submit" id="addFreight1" class="btn btn-success btn-sm pull-right" onclick="cheickForFreight(\''+elmt2[0]+'\',\''+elmt2[1]+'\',\''+elmt2[2]+'\',\''+elmt2[3]+'\',\''+elmt2[4]+'\');"><i class="fa fa-plus"></i></button>';
						var addFreightBtn2 = '<button type="submit" id="addFreight2" class="btn btn-success btn-sm pull-right" onclick="secondFreightList(\''+elmt2[1]+'\',\''+elmt2[3]+'\',\''+elmt2[4]+'\');"><i class="fa fa-plus"></i></button>';
					} else {
						var addFreightBtn1 = '';
						var addFreightBtn2 = '';
					}
					
					// Freight status 
					if(elmt2[6]==1){  
						if(calc_read == 1){ $("#crm_request_calc_tab").removeClass("hide"); $("#crm_request_calc_ct").removeClass("hide"); } 
						else { $("#crm_request_calc_tab").addClass("hide"); $("#crm_request_calc_ct").addClass("hide"); }
						
						if(proposal_read == 1){ $("#crm_request_proposal_tab").removeClass("hide"); $("#crm_request_proposal_ct").removeClass("hide"); } 
						else { $("#crm_request_proposal_tab").addClass("hide"); $("#crm_request_proposal_ct").addClass("hide"); }
						
					} else {
						$("#crm_request_calc_tab").addClass("hide"); $("#crm_request_calc_ct").addClass("hide"); 
						$("#crm_request_proposal_tab").addClass("hide"); $("#crm_request_proposal_ct").addClass("hide");
					}
					
					document.getElementById('F1_title').innerHTML = 'Ocean Freight '+addFreightBtn1;
					document.getElementById('F2_title').innerHTML = 'Onward Freight '+addFreightBtn2;
					
					var data=val2[1].split('%%');  
			
					i = 0;
					while (data[i] != 'end') { 
						var elt=data[i].split('##');   
						
						var pol_name = elt[0];
						var dem_pol_free = elt[1];
						var incoterm_name = elt[2];
						var pod_name = elt[3];
						var dem_pod_free = elt[4];
						var carrier  = elt[5];
						var rate_valid_until  = elt[6];
						var packaging_type_name  = elt[7];
						var trans_delay  = elt[8];
					
						var num=i+1;
						
						showFreight(pol_name,dem_pol_free,incoterm_name,pod_name,dem_pod_free,carrier,rate_valid_until,packaging_type_name,trans_delay,num);
						
						i += 1;	
					}
					
					freightModifyBy(id_ord_schedule);
				}

				document.getElementById('freight_copy_to_all').innerHTML = '<button type="button" id="copyFreightBtn" class="btn btn-info pull-left" onclick="copyFreight('+id_ord_schedule+','+last_shipment+');"> '+lg_contract_copy_to_all+' </button>';
				if(freight_update == 1){
					// if(pipeline_id == 296){ var dis="disabled"; } else { var dis=""; }
					document.getElementById('editFreight').innerHTML = '<button type="button" id="editFreightBtn" '+dis+' class="btn btn-success pull-right" onclick="editFreight('+id_ord_schedule+','+last_shipment+');"><i class="fa fa-edit"></i></button>';
				} 
				
				$('#copyFreightBtn').prop("disabled", true);
				
				$('#addFreight1').prop("disabled", true);
				$('#addFreight2').prop("disabled", true);
			
				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
}


function copyFreight(id_ord_schedule,last_shipment) {
	var resurl='include/freight.php?elemid=freight_copy&id_ord_schedule='+id_ord_schedule+'&last_shipment='+last_shipment;   
	var xhr = getXhr();  
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText; 
			
			if(leselect == 1){
				toastr.success('Shipping routes copied. You may need to re-calculate all shipments in CALCULATE!',{timeOut:15000})
				if(calc_read == 1){ $("#crm_request_calc_tab").removeClass("hide"); $("#crm_request_calc_ct").removeClass("hide"); } 
				else { $("#crm_request_calc_tab").addClass("hide"); $("#crm_request_calc_ct").addClass("hide"); }
				
				if(proposal_read == 1){ $("#crm_request_proposal_tab").removeClass("hide"); $("#crm_request_proposal_ct").removeClass("hide"); }
				else { $("#crm_request_proposal_tab").addClass("hide"); $("#crm_request_proposal_ct").addClass("hide"); }
			
			} else
			if(leselect == 0){
				toastr.error('Error coping, please retry',{timeOut:15000})
				
				$("#crm_request_calc_tab").addClass("hide"); 
				$("#crm_request_calc_ct").addClass("hide");
				
				$("#crm_request_proposal_tab").addClass("hide"); 
				$("#crm_request_proposal_ct").addClass("hide"); 
			} else {
				internal_error(); 
			}

		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}


function editFreight(id_ord_schedule,last_shipment) {
	$('#copyFreightBtn').prop("disabled", false);
	
	$('#addFreight1').prop("disabled", false);
	$('#addFreight2').prop("disabled", false);
	
	document.getElementById('editFreight').innerHTML = '<button type="button" '+dis+' class="btn btn-danger pull-right" onclick="cancelFreight_editing('+id_ord_schedule+','+last_shipment+','+pipeline_id+');"><i class="fa fa-ban"></i></button>'
		+'<button type="button" class="btn btn-success pull-right" style="margin-right:10px;" onclick="saveFreight_editing('+id_ord_schedule+','+last_shipment+');"><i class="fa fa-save"></i></button>';
}


function cancelFreight_editing(id_ord_schedule,last_shipment) {
	$('#copyFreightBtn').prop("disabled", true);
	
	$('#addFreight1').prop("disabled", true);
	$('#addFreight2').prop("disabled", true);
	
	document.getElementById('editFreight').innerHTML = '<button type="button" id="editFreightBtn" class="btn btn-success pull-right" onclick="editFreight('+id_ord_schedule+','+last_shipment+');"><i class="fa fa-edit"></i></button>';
}


function saveFreight_editing(id_ord_schedule,last_shipment) {
	var resurl='include/freight.php?elemid=save_edited_freight&id_ord_schedule='+id_ord_schedule+'&last_shipment='+last_shipment;   
	var xhr = getXhr();  
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText; 
			
			if(leselect == 1){
				toastr.success('Freight saved successfully',{timeOut:15000})
			
				if(last_shipment == 1){
					if(calc_read == 1){ 
						$("#crm_request_calc_tab").removeClass("hide"); 
						$("#crm_request_calc_ct").removeClass("hide"); 
					} else { 
						$("#crm_request_calc_tab").addClass("hide"); 
						$("#crm_request_calc_ct").addClass("hide"); 
					}
		
					if(proposal_read == 1){ 
						$("#crm_request_proposal_tab").removeClass("hide"); 
						$("#crm_request_proposal_ct").removeClass("hide"); 
					} else { 
						$("#crm_request_proposal_tab").addClass("hide"); 
						$("#crm_request_proposal_ct").addClass("hide"); 
					}
				}
				
				cancelFreight_editing(id_ord_schedule);
				freightModifyBy(id_ord_schedule);
				
			} else
			if(leselect == 0){
				toastr.error('Error saving freight, please retry',{timeOut:15000})
				$("#crm_request_proposal_tab").addClass("hide"); $("#crm_request_proposal_ct").addClass("hide");
				$("#crm_request_calc_tab").addClass("hide"); $("#crm_request_calc_ct").addClass("hide");
			} else {
				internal_error();
			}
			
			leselect = xhr.responseText;
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}

function freightModifyBy(id_ord_schedule) {

	var resurl='include/freight.php?elemid=freight_modify_by&id_ord_schedule='+id_ord_schedule;   
	var xhr = getXhr();  
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText; 
			
			document.getElementById('freight_footer').innerHTML = leselect;
			
			leselect = xhr.responseText;
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}

function saveFreight() {  

	var id_con_box_fr = '';

	if($("input[type='radio'].radioBtnFreightListClass").is(':checked')) {
		id_con_box_fr = $("input[type='radio'].radioBtnFreightListClass:checked").val();
	}
	
	var id_ord_schedule = document.getElementById('freight_id_ord_schedule').value;
	var sequence_nr = document.getElementById('freight_sequence_nr').value;
	
	var resurl='include/freight.php?elemid=save_freight&id_ord_schedule='+id_ord_schedule+'&id_con_box_fr='+id_con_box_fr+'&sequence_nr='+sequence_nr;   
	var xhr = getXhr();  
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;    
			var val = leselect.split('#');
			
			if(val[0] == 1){
				toastr.success('Freight saved successfully',{timeOut:15000})
				showFreightList(id_ord_schedule,val[1],val[2],val[3]);
				
				if(calc_read == 1){ 
					$("#crm_request_calc_tab").removeClass("hide"); 
					$("#crm_request_calc_ct").removeClass("hide"); 
				} else { 
					$("#crm_request_calc_tab").addClass("hide"); 
					$("#crm_request_calc_ct").addClass("hide"); 
				}
				
				if(proposal_read == 1){ 
					$("#crm_request_proposal_tab").removeClass("hide"); 
					$("#crm_request_proposal_ct").removeClass("hide"); 
				} else { 
					$("#crm_request_proposal_tab").addClass("hide"); 
					$("#crm_request_proposal_ct").addClass("hide"); 
				}
				
				$("#freightListModal").modal("hide");
				
			} else 
			if(val[0] == 0){
				toastr.error('Error saving freight, please retry',{timeOut:15000})
			} else {
				internal_error();
			}
			
			leselect = xhr.responseText;
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}


function secondFreightList(pod_id,package_type_id,id_ord_schedule) {  

	var resurl2='include/freight.php?elemid=second_separate_freights_list&pod_id='+pod_id+'&package_type_id='+package_type_id;    
	var xhr2 = getXhr(); 
	
	xhr2.onreadystatechange = function(){
		if(xhr2.readyState == 4 ){
			leselect2 = xhr2.responseText;     
			var val = leselect2.split('##');

			if(val[0]==1){
				document.getElementById('freightModalLabel').innerHTML = "Second freight";
				
				document.getElementById('freightListTable').innerHTML = val[1];
				document.getElementById('freight_sequence_nr').value = 2;
				document.getElementById('freight_id_ord_schedule').value = id_ord_schedule;
		
				document.getElementById('freight_modal_footer').innerHTML = '<button type="button" class="btn btn-success" onclick="saveFreight();"><i class="fa fa-save"></i></button>'
					+'<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i></button>';
				
				$("#freightListModal").modal("show");
			} else
			if(val[0]==0){
				toastr.info('No freight exists!',{timeOut:15000})
			} else {
				internal_error();
			}
		
			leselect2 = xhr2.responseText;
		}
	};

	xhr2.open("GET",resurl2,true);
	xhr2.send(null);
}


/*
* CRM2 - Request
* Edit Freight
*/

function copyFreight2(id_ord_schedule,last_shipment) {
	var resurl='include/freight.php?elemid=freight_copy&id_ord_schedule='+id_ord_schedule+'&last_shipment='+last_shipment;   
	var xhr = getXhr();  
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText; 
			
			if(leselect == 1){
				toastr.success('Shipping routes copied. You may need to re-calculate all shipments in CALCULATE!',{timeOut:15000})
				if(calc_read_2 == 1){ $("#crm_request_calc_tab2").removeClass("hide"); $("#crm_request_calc_ct2").removeClass("hide"); } 
				else { $("#crm_request_calc_tab2").addClass("hide"); $("#crm_request_calc_ct2").addClass("hide"); }
				
				if(proposal_read_2 == 1){ $("#crm_request_proposal_tab2").removeClass("hide"); $("#crm_request_proposal_ct2").removeClass("hide"); }
				else { $("#crm_request_proposal_tab2").addClass("hide"); $("#crm_request_proposal_ct2").addClass("hide"); }
			
			} else
			if(leselect == 0){
				toastr.error('Error coping, please retry',{timeOut:15000})
				
				$("#crm_request_calc_tab2").addClass("hide"); 
				$("#crm_request_calc_ct2").addClass("hide");
				
				$("#crm_request_proposal_tab2").addClass("hide"); 
				$("#crm_request_proposal_ct2").addClass("hide"); 
			} else {
				internal_error(); 
			}
			
			leselect = xhr.responseText;
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}


function editFreight2(id_ord_schedule,last_shipment) {
	$('#copyFreightBtn2').prop("disabled", false);
	
	$('#addFreight12').prop("disabled", false);
	$('#addFreight22').prop("disabled", false);
	
	document.getElementById('editFreight2').innerHTML = '<button type="button" '+dis+' class="btn btn-danger pull-right" onclick="cancelFreight_editing2('+id_ord_schedule+','+last_shipment+','+pipeline_id+');"><i class="fa fa-ban"></i></button>'
		+'<button type="button" class="btn btn-success pull-right" style="margin-right:10px;" onclick="saveFreight_editing2('+id_ord_schedule+','+last_shipment+');"><i class="fa fa-save"></i></button>';
}


function cancelFreight_editing2(id_ord_schedule,last_shipment) {
	$('#copyFreightBtn2').prop("disabled", true);
	
	$('#addFreight12').prop("disabled", true);
	$('#addFreight22').prop("disabled", true);
	
	document.getElementById('editFreight2').innerHTML = '<button type="button" id="editFreightBtn2" class="btn btn-success pull-right" onclick="editFreight2('+id_ord_schedule+','+last_shipment+');"><i class="fa fa-edit"></i></button>';
}


function saveFreight_editing2(id_ord_schedule,last_shipment) {
	var resurl='include/freight.php?elemid=save_edited_freight&id_ord_schedule='+id_ord_schedule+'&last_shipment='+last_shipment;   
	var xhr = getXhr();  
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText; 
			
			if(leselect == 1){
				toastr.success('Freight saved successfully',{timeOut:15000})
			
				if(last_shipment == 1){
					if(calc_read_2 == 1){ 
						$("#crm_request_calc_tab2").removeClass("hide"); 
						$("#crm_request_calc_ct2").removeClass("hide"); 
					} else { 
						$("#crm_request_calc_tab2").addClass("hide"); 
						$("#crm_request_calc_ct2").addClass("hide"); 
					}
		
					if(proposal_read_2 == 1){ 
						$("#crm_request_proposal_tab2").removeClass("hide"); 
						$("#crm_request_proposal_ct2").removeClass("hide"); 
					} else { 
						$("#crm_request_proposal_tab2").addClass("hide"); 
						$("#crm_request_proposal_ct2").addClass("hide"); 
					}
				}
				
				cancelFreight_editing2(id_ord_schedule);
				freightModifyBy2(id_ord_schedule);
				
			} else
			if(leselect == 0){
				toastr.error('Error saving freight, please retry',{timeOut:15000})
				$("#crm_request_proposal_tab2").addClass("hide"); $("#crm_request_proposal_ct2").addClass("hide");
				$("#crm_request_calc_tab2").addClass("hide"); $("#crm_request_calc_ct2").addClass("hide");
			} else {
				internal_error();
			}
			
			leselect = xhr.responseText;
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}

function showFreight2(pol_name,dem_pol_free,incoterm_name,pod_name,dem_pod_free,carrier,rate_valid_until,packaging_type_name,trans_delay,num) {
	
	document.getElementById('freight_12').innerHTML = '';
	document.getElementById('freight_22').innerHTML = '';
	
	var resurl='include/freight.php?elemid=show_saved_freigt&pol_name='+pol_name+'&dem_pol_free='+dem_pol_free+'&incoterm_name='+incoterm_name+'&pod_name='+pod_name+'&dem_pod_free='+dem_pod_free+'&carrier='+carrier+'&rate_valid_until='+rate_valid_until+'&packaging_type_name='+packaging_type_name+'&trans_delay='+trans_delay;   
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText; 
			
			document.getElementById('freight_'+num+'2').innerHTML = leselect;
			
			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}

function freightModifyBy2(id_ord_schedule) {

	var resurl='include/freight.php?elemid=freight_modify_by&id_ord_schedule='+id_ord_schedule;   
	var xhr = getXhr();  
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText; 
			
			document.getElementById('freight_footer2').innerHTML = leselect;
			
			leselect = xhr.responseText;
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}

/*
** End Freight **
*/

function calculateAll(id_ord_schedule) {
	var resurl='listeslies.php?elemid=calculate_all&id_ord_schedule='+id_ord_schedule;   
	var xhr = getXhr();  
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText; 
			
			if(leselect == 1){
				toastr.success('Calculate successfully',{timeOut:15000})
			} else 
			if(leselect == 0){
				toastr.error('Error calculating, please retry',{timeOut:15000})
			} else {
				internal_error();
			}
			
			leselect = xhr.responseText;
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}


function copyExporterQuote(id_ord_schedule) {

	var resurl='listeslies.php?elemid=copy_exporter_quote&id_ord_schedule='+id_ord_schedule;   
	var xhr = getXhr();  
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText; 
			
			if(leselect == 1){
				toastr.success('Exporter Data copied, you need to select the port and shipping date for each shipment manually!',{timeOut:15000})	
			} else 
			if(leselect == 0){
				toastr.error('Error coping exporter quote, please retry',{timeOut:15000})
			} else {
				internal_error();
			}
			
			leselect = xhr.responseText;
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}


function showTranshipment(val) { 
	if(val != ""){
		$("#transBox").removeClass("hide");
	} else {
		$("#transBox").addClass("hide");
	}
}


function add_feeder2() {  
	var pol = $("#trans_port_id option:selected").text();
	document.getElementById('feeder_2_pol').innerHTML = pol;
	$("#transBox2").removeClass("hide");
	$("#feeder2_addbtn").addClass("hide");
}

function showTranshipment_add(val) { 
	if(val != ""){
		$("#transBox_add").removeClass("hide");
	} else {
		$("#transBox_add").addClass("hide");
	}
}


/*
*
* Product management 
*
*/

function productManagement(conf,id_product,val) {

	if(conf == 'add'){
		/* Add new product */
		
		var req = '';
		
		var product_name = document.getElementById("product_name").value;
		if(product_name){ req=req+'&product_name='+product_name; }
		
		var id_culture = document.getElementById("id_culture_pdct").value;
		if(id_culture){ req=req+'&id_culture='+id_culture; }
		
		var product_code = document.getElementById("product_code").value;
		if(product_code){ req=req+'&product_code='+product_code; }
		
		var product_desc = document.getElementById("product_desc").value;
		if(product_desc){ req=req+'&product_desc='+product_desc; }
		
		var measure_unit = document.getElementById("measure_unit").value;
		if(measure_unit){ req=req+'&measure_unit='+measure_unit; }
		
		var product_type = document.getElementById("product_type").value;
		if(product_type){ req=req+'&product_type='+product_type; }
		
		var product_hs = document.getElementById("product_hs").value;
		if(product_hs){ req=req+'&product_hs='+product_hs; }
		
		var product_cas = document.getElementById("product_cas").value;
		if(product_cas){ req=req+'&product_cas='+product_cas; }
		
		var product_name_de = document.getElementById("product_name_de").value;
		if(product_name_de){ req=req+'&product_name_de='+product_name_de; }
		
		var q_ffa = document.getElementById("q_ffa").value;
		if(q_ffa){ req=req+'&q_ffa='+q_ffa; }
		
		var q_mineraloil = document.getElementById("q_mineraloil").value;
		if(q_mineraloil){ req=req+'&q_mineraloil='+q_mineraloil; }
		
		var q_humidity = document.getElementById("q_humidity").value;
		if(q_humidity){ req=req+'&q_humidity='+q_humidity; }
		
		var c18_1 = document.getElementById("c18_1").value;
		if(c18_1){ req=req+'&c18_1='+c18_1; }
		
		var c18_2 = document.getElementById("c18_2").value;
		if(c18_2){ req=req+'&c18_2='+c18_2; }
		
		var q_impurity = document.getElementById("q_impurity").value;
		if(q_impurity){ req=req+'&q_impurity='+q_impurity; }
		
		var q_dobi = document.getElementById("q_dobi").value;
		if(q_dobi){ req=req+'&q_dobi='+q_dobi; }
		
		var q_m_i = document.getElementById("q_m_i").value;
		if(q_m_i){ req=req+'&q_m_i='+q_m_i; }
		
		
		if(product_name == ""){
			alert("Enter product name.");
			
		} else
		if(id_culture == ""){
			alert("Select culture.");
			
		} else 
		if(measure_unit == ""){
			alert("Select measure Unit.");
			
		} else
		if(product_type == ""){
			alert("Select product Type.");
			
		} else {
			var resurl='listeslies.php?elemid=manag_system_product&conf='+conf+'&id_product='+id_product+req;    
			var xhr = getXhr();
			xhr.onreadystatechange = function(){
				if(xhr.readyState == 4 ){
					leselect = xhr.responseText;         
					
					if(leselect == 1){
						toastr.success('Town successfully added',{timeOut:15000})
					} else
					if(leselect == 0){
						toastr.error('Town not added, please retry!',{timeOut:15000})
					} else {
						internal_error();
					}
					
					leselect = xhr.responseText;
				}
			};

			xhr.open("GET",resurl,true);
			xhr.send(null);
		}
		
	} else
	if(conf == 'show'){
		if(val== 'create'){
			document.getElementById("systProductForm").reset();
			
			/* Save button */
			document.getElementById('productModalLabel').innerHTML = "Create new product";
			document.getElementById('productModalFooter').innerHTML ='<button type="button" class="btn btn-success" onclick="productManagement(\'add\',\'\',\'\');"><i class="fa fa-save"></i></button>'
					+'<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i></button>';
		} else
		if(val== 'mod'){ 
			
			document.getElementById("systProductForm").reset();
			
			var resurl='listeslies.php?elemid=show_system_product&id_product='+id_product;   
			var xhr = getXhr();
			xhr.onreadystatechange = function(){
				if(xhr.readyState == 4 ){
					leselect = xhr.responseText;          
					var val = leselect.split('#');
					
					document.getElementById("id_culture_pdct").value = val[0];
					document.getElementById("product_code").value = val[1];
					document.getElementById("product_name").value = val[2];
					document.getElementById("product_desc").value = val[3];
					document.getElementById("measure_unit").value = val[4];
					document.getElementById("product_type").value = val[5];
					document.getElementById("product_hs").value = val[6];
					document.getElementById("product_cas").value = val[7];
					
					document.getElementById('id_product_pdct').value = id_product;
				
					leselect = xhr.responseText;
				}
			};

			xhr.open("GET",resurl,true);
			xhr.send(null);
	
			
			/* Edit button */
			document.getElementById('productModalLabel').innerHTML = "Edit Product";
			document.getElementById('productModalFooter').innerHTML ='<button type="button" class="btn btn-success" onclick="productManagement(\'edit\',\''+id_product+'\',\'\');"><i class="fa fa-save"></i></button>'
					+'<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i></button>';
		} else {}
		
	} else
	if(conf == 'edit'){	 
		
		/* Edit product */
		
		var req = '';
		
		var product_name = document.getElementById("product_name").value;
		if(product_name){ req=req+'&product_name='+product_name; }
		
		var id_culture = document.getElementById("id_culture_pdct").value;
		if(id_culture){ req=req+'&id_culture='+id_culture; }
		
		var product_code = document.getElementById("product_code").value;
		if(product_code){ req=req+'&product_code='+product_code; }
		
		var product_desc = document.getElementById("product_desc").value;
		if(product_desc){ req=req+'&product_desc='+product_desc; }
		
		var measure_unit = document.getElementById("measure_unit").value;
		if(measure_unit){ req=req+'&measure_unit='+measure_unit; }
		
		var product_type = document.getElementById("product_type").value;
		if(product_type){ req=req+'&product_type='+product_type; }
		
		var product_hs = document.getElementById("product_hs").value;
		if(product_hs){ req=req+'&product_hs='+product_hs; }
		
		var id_product = document.getElementById("id_product_pdct").value;
		if(id_product){ req=req+'&id_product='+id_product; }
		
		var product_name_de = document.getElementById("product_name_de").value;
		if(product_name_de){ req=req+'&product_name_de='+product_name_de; }
		
		var q_ffa = document.getElementById("q_ffa").value;
		if(q_ffa){ req=req+'&q_ffa='+q_ffa; }
		
		var q_mineraloil = document.getElementById("q_mineraloil").value;
		if(q_mineraloil){ req=req+'&q_mineraloil='+q_mineraloil; }
		
		var q_humidity = document.getElementById("q_humidity").value;
		if(q_humidity){ req=req+'&q_humidity='+q_humidity; }
		
		var c18_1 = document.getElementById("c18_1").value;
		if(c18_1){ req=req+'&c18_1='+c18_1; }
		
		var c18_2 = document.getElementById("c18_2").value;
		if(c18_2){ req=req+'&c18_2='+c18_2; }
		
		var q_impurity = document.getElementById("q_impurity").value;
		if(q_impurity){ req=req+'&q_impurity='+q_impurity; }
		
		var q_dobi = document.getElementById("q_dobi").value;
		if(q_dobi){ req=req+'&q_dobi='+q_dobi; }
		
		var q_m_i = document.getElementById("q_m_i").value;
		if(q_m_i){ req=req+'&q_m_i='+q_m_i; }
		
		var resurl='listeslies.php?elemid=manag_system_product&conf='+conf+'&id_product='+id_product+req;    
		var xhr = getXhr();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;         
				
				if(leselect == 1){
					toastr.success('Product successfully saved',{timeOut:15000})
					$("#modalProduct").modal("hide");
					
				} else
				if(leselect == 0){
					toastr.error('Unable to save product, please retry!',{timeOut:15000})
				} else {
					internal_error();
				}
				
				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
		
	} else
	if(conf == 'del'){
		
		/* Delete product */
		
		var resurl='listeslies.php?elemid=delete_system_product&id_product='+id_product;    
		var xhr = getXhr();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;         
				
				if(leselect == 1){
					toastr.success('Product successfully deleted',{timeOut:15000})
				} else 
				if(leselect == 0){
					toastr.error('Product not deleted, please retry!',{timeOut:15000})
				} else {
					internal_error();
				}
				
				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
		
	} else {}
}


function product_exp(typ) {
	hideAll();
	
	$("#db_product_exp").removeClass("hide");
	titleMenuManag("Product","btn_crm_pdct");
	
	if(sysProduct_create == 1){ $("#createProductBtn").removeClass("hide"); } else { $("#createProductBtn").addClass("hide"); }

	var resurl='listeslies.php?elemid=products_exporters&typ='+typ+'&update_right='+sysProduct_update+'&delete_right='+sysProduct_delete;    
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText; 
			var val = leselect.split('##'); 
			
			document.getElementById('list_exporters').innerHTML = val[0];
			document.getElementById('list_productNA').innerHTML = val[1];
			document.getElementById('listOfproduct').innerHTML = val[2];
			
			document.getElementById('list_productA').innerHTML = '';
			document.getElementById('selectedExporter').innerHTML = '';
			
			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


/* Exporters products */

function exportersProducts(id_exporter) {

	$("#ProductSpanner").removeClass("hide");

	var resurl='listeslies.php?elemid=products_selected_exporter&id_exporter='+id_exporter+'&typ=exporter';
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;
			var val = leselect.split('##');

			document.getElementById('list_productA').innerHTML = val[0];
			document.getElementById('selectedExporter').innerHTML = val[1];
			document.getElementById('list_productNA').innerHTML = val[2];

			$("#ProductSpanner").addClass("hide");

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


/* Add product to exporters products list  */

function addProductToExporter(id_product){
	var contact_id = '';

	if($("input[type='radio'].radioBtnExpClass").is(':checked')) {
		contact_id = $("input[type='radio'].radioBtnExpClass:checked").val();
	}

	if(contact_id == ""){
		toastr.info('Select an exporter.',{timeOut:15000})

	} else {
		var resurl='listeslies.php?elemid=add_product_to_exporter&contact_id='+contact_id+'&id_product='+id_product;  
		var xhr = getXhr();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;

				if(leselect == 1){
					toastr.success('Product added successfully',{timeOut:15000})
					productAttachedAndNot(contact_id);
				} else 
				if(leselect == 0){
					toastr.error('Error adding product, please retry',{timeOut:15000})
				} else {
					internal_error();
				}

				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
	}
}


/* Remove product from exporters products list */

function removeProductFromExporter(id_product) {
	var contact_id = '';

	if($("input[type='radio'].radioBtnExpClass").is(':checked')) {
		contact_id = $("input[type='radio'].radioBtnExpClass:checked").val();
	}

	if(contact_id == ""){
		toastr.info('Select an exporter.',{timeOut:15000})

	} else {
		var resurl='listeslies.php?elemid=remove_product_from_exporter&contact_id='+contact_id+'&id_product='+id_product;
		var xhr = getXhr();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;

				if(leselect == 1){
					toastr.success('Product removed successfully',{timeOut:15000})
					productAttachedAndNot(contact_id);
				} else 
				if(leselect == 0){
					toastr.error('Error removing product, please retry',{timeOut:15000})
				} else {
					internal_error();
				}

				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
	}
}


function productAttachedAndNot(id_exporter) {
	
	$("#ProductSpanner").removeClass("hide");
	var typ = document.getElementById('pdtElementSel').value;
	
	var resurl='listeslies.php?elemid=productAttachedAndNot&id_exporter='+id_exporter+'&typ='+typ;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;
			var val = leselect.split('##');

			document.getElementById('list_productA').innerHTML = val[0];
			document.getElementById('list_productNA').innerHTML = val[1];
			
			$("#ProductSpanner").addClass("hide");

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


/* Clients products */

function clientsProducts(id_client) {

	$("#ProductSpanner").removeClass("hide");

	var resurl='listeslies.php?elemid=products_selected_exporter&id_exporter='+id_client+'&typ=client';
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;
			var val = leselect.split('##');

			document.getElementById('list_productA').innerHTML = val[0];
			document.getElementById('selectedExporter').innerHTML = val[1];
			document.getElementById('list_productNA').innerHTML = val[2];

			$("#ProductSpanner").addClass("hide");

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


/* Add product to client products list  */

function addProductToClient(id_product){
	var contact_id = '';

	if($("input[type='radio'].radioBtnCltClass").is(':checked')) {
		contact_id = $("input[type='radio'].radioBtnCltClass:checked").val();
	}

	if(contact_id == ""){
		toastr.info('Select a client.',{timeOut:15000})

	} else {
		var resurl='listeslies.php?elemid=add_product_to_exporter&contact_id='+contact_id+'&id_product='+id_product;  
		var xhr = getXhr();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;

				if(leselect == 1){
					toastr.success('Product added successfully',{timeOut:15000})
					productAttachedAndNot(contact_id);
				} else 
				if(leselect == 0){
					toastr.error('Error adding product, please retry',{timeOut:15000})
				} else {
					internal_error();
				}

				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
	}
}


/* Remove product from client products list */

function removeProductFromClient(id_product) {
	var contact_id = '';

	if($("input[type='radio'].radioBtnCltClass").is(':checked')) {
		contact_id = $("input[type='radio'].radioBtnCltClass:checked").val();
	}

	if(contact_id == ""){
		toastr.info('Select a client.',{timeOut:15000})

	} else {
		var resurl='listeslies.php?elemid=remove_product_from_exporter&contact_id='+contact_id+'&id_product='+id_product;
		var xhr = getXhr();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;

				if(leselect == 1){
					toastr.success('Product removed successfully',{timeOut:15000})
					productAttachedAndNot(contact_id);
				} else 
				if(leselect == 0){
					toastr.error('Error removing product, please retry',{timeOut:15000})
				} else {
					internal_error();
				}

				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
	}
}


/*
* SYSTEM Freight 
* management
*/

function crm_freight() {
	hideAll();
	
	$("#db_crm_freight").removeClass("hide");
	titleMenuManag("Freight","btn_crm_freights");
	
	if(sysFreight_create == 1){ $("#sysFreightCreate").removeClass("hide"); } else { $("#sysFreightCreate").addClass("hide"); }

	var resurl='listeslies.php?elemid=crm_freight&update_right='+sysFreight_update+'&delete_right='+sysFreight_delete;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;

			document.getElementById('freight_table').innerHTML = leselect;

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function newSystemFreight() {
	
	document.getElementById("sysFreightForm").reset();
	
	document.getElementById('systemFreight_Modaltitle').innerHTML = 'New Freight';
	document.getElementById('systemFreight_Modalfooter').innerHTML = '<button type="button" class="btn btn-success" onclick="systemFreight(\'add\');"><i class="fa fa-save"></i></button>'
		+'<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i></button>';
	
	$('.edit_delivery_date').datepicker({
		format: "yyyy/mm/dd",
		calendarWeeks:true,
		autoclose: true
	});
}


function systemFreight(conf) {
	
	var req="";
	var shipping_company_id=document.getElementById('shipping_company_id_sysFrei').value;
	if(shipping_company_id){ req=req+'&shipping_company_id='+shipping_company_id; }
	
	var shipping_company=document.getElementById('shipping_company_sysFrei').value;
	if(shipping_company){ req=req+'&shipping_company='+shipping_company; }
	
	var freight_eur=document.getElementById('freight_eur_sysFrei').value;
	if(freight_eur){ req=req+'&freight_eur='+freight_eur; }
	
	var freight_usd=document.getElementById('freight_usd_sysFrei').value;
	if(freight_usd){ req=req+'&freight_usd='+freight_usd; }
	
	var freight_chf=document.getElementById('freight_chf_sysFrei').value;
	if(freight_chf){ req=req+'&freight_chf='+freight_chf; }
	
	var add_eur=document.getElementById('add_eur_sysFrei').value;
	if(add_eur){ req=req+'&add_eur='+add_eur; }
	
	var add_usd=document.getElementById('add_usd_sysFrei').value;
	if(add_usd){ req=req+'&add_usd='+add_usd; }
	
	var add_chf=document.getElementById('add_chf_sysFrei').value;
	if(add_chf){ req=req+'&add_chf='+add_chf; }
	
	var total_eur=document.getElementById('total_eur_sysFrei').value;
	if(total_eur){ req=req+'&total_eur='+total_eur; }
	
	var total_usd=document.getElementById('total_usd_sysFrei').value;
	if(total_usd){ req=req+'&total_usd='+total_usd; }
	
	var total_chf=document.getElementById('total_chf_sysFrei').value;
	if(total_chf){ req=req+'&total_chf='+total_chf; }
	
	var dem_pol_free=document.getElementById('dem_pol_free_sysFrei').value;
	if(dem_pol_free){ req=req+'&dem_pol_free='+dem_pol_free; }
	
	var dem_pol_cost_after=document.getElementById('dem_pol_cost_after_sysFrei').value;
	if(dem_pol_cost_after){ req=req+'&dem_pol_cost_after='+dem_pol_cost_after; }
	
	var dem_pod_free=document.getElementById('dem_pod_free_sysFrei').value;
	if(dem_pod_free){ req=req+'&dem_pod_free='+dem_pod_free; }
	
	var dem_pod_cost_after=document.getElementById('dem_pod_cost_after_sysFrei').value;
	if(dem_pod_cost_after){ req=req+'&dem_pod_cost_after='+dem_pod_cost_after; }
	
	//2
	var dem_pol_free2=document.getElementById('dem_pol_free2_sysFrei').value;
	if(dem_pol_free2){ req=req+'&dem_pol_free2='+dem_pol_free2; }
	
	var dem_pol_cost_after2=document.getElementById('dem_pol_cost_after2_sysFrei').value;
	if(dem_pol_cost_after2){ req=req+'&dem_pol_cost_after2='+dem_pol_cost_after2; }
	
	var dem_pod_free2=document.getElementById('dem_pod_free2_sysFrei').value;  
	if(dem_pod_free2){ req=req+'&dem_pod_free2='+dem_pod_free2; }
	
	var dem_pod_cost_after2=document.getElementById('dem_pod_cost_after2_sysFrei').value;
	if(dem_pod_cost_after2){ req=req+'&dem_pod_cost_after2='+dem_pod_cost_after2; }
	
	
	var transit_time=document.getElementById('transit_time_sysFrei').value;
	if(transit_time){ req=req+'&transit_time='+transit_time; }
	
	var trans_location_id=document.getElementById('trans_location_id_sysFrei').value;
	if(trans_location_id){ req=req+'&trans_location_id='+trans_location_id; }
	
	var incoterm_id=document.getElementById('incoterm_id_sysFrei').value;
	if(incoterm_id){ req=req+'&incoterm_id='+incoterm_id; }
	
	var trans_type_id=document.getElementById('trans_type_id_sysFrei').value;
	if(trans_type_id){ req=req+'&trans_type_id='+trans_type_id; }
	
	var returns_empty_id=document.getElementById('returns_empty_id_sysFrei').value;
	if(returns_empty_id){ req=req+'&returns_empty_id='+returns_empty_id; }
	
	var rate_valid_until=document.getElementById('rate_valid_until_sysFrei').value;
	if(rate_valid_until){ req=req+'&rate_valid_until='+rate_valid_until; }
	
	var packaging_type_id=document.getElementById('packaging_type_id_sysFrei').value;
	if(packaging_type_id){ req=req+'&packaging_type_id='+packaging_type_id; }
	
	var weight_packaging_type=document.getElementById('weight_packaging_type_sysFrei').value;
	if(weight_packaging_type){ req=req+'&weight_packaging_type='+weight_packaging_type; }
	
	var pod_townport_id=document.getElementById('pod_townport_id_sysFrei').value;
	if(pod_townport_id){ req=req+'&pod_townport_id='+pod_townport_id; }
	
	var pol_townport_id=document.getElementById('pol_townport_id_sysFrei').value;
	if(pol_townport_id){ req=req+'&pol_townport_id='+pol_townport_id; }
	
	var transport_type_id=document.getElementById('transport_type_id_sysFrei').value;
	if(transport_type_id){ req=req+'&transport_type_id='+transport_type_id; }
	
	var id_con_box_fr=document.getElementById('id_con_box_fr_sysFrei').value;
	if(id_con_box_fr){ req=req+'&id_con_box_fr='+id_con_box_fr; }
	
	
	if(pol_townport_id==""){
		alert("Select port of loading");
		
	} else
	if(pod_townport_id==""){
		alert("Select port of discharge");
		
	} else 
	if(incoterm_id==""){
		alert("Select incoterms");
		
	} else
	if(transport_type_id==""){
		alert("Select transport type");
		
	} else 
	if(shipping_company==""){
		alert("Enter shipping company");
		
	} else
	if(packaging_type_id==""){
		alert("Select packaging type");
		
	} else {
		var resurl='listeslies.php?elemid=manage_system_freight&conf='+conf+req;     
		var xhr = getXhr();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;   
			 
				if(leselect==1){
					toastr.success('Freight successfully saved',{timeOut:15000})
					$("#newSystFreightmodal").modal("hide");
					crm_freight();
					
				} else 
				if(leselect==0){
					toastr.error('Freight not saved',{timeOut:15000})
				} else {
					internal_error();
				}
				
				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
	}
}


function showSystemFreight(id_con_box_fr){
	var resurl='listeslies.php?elemid=show_system_freight&id_con_box_fr='+id_con_box_fr;    
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;  
			var val = leselect.split('#');  
			
			document.getElementById('shipping_company_sysFrei').value = val[0];   
			document.getElementById('freight_eur_sysFrei').value = val[1];
			document.getElementById('freight_usd_sysFrei').value = val[2];
			document.getElementById('add_eur_sysFrei').value = val[3];
			document.getElementById('add_usd_sysFrei').value = val[4];
			document.getElementById('total_eur_sysFrei').value = val[5];
			document.getElementById('packaging_type_id_sysFrei').value = val[6];
			document.getElementById('transit_time_sysFrei').value = val[7];
			document.getElementById('trans_location_id_sysFrei').value = val[8];
			document.getElementById('dem_pol_free_sysFrei').value = val[9];
			document.getElementById('dem_pol_cost_after_sysFrei').value = val[10];
			document.getElementById('dem_pod_free_sysFrei').value = val[11];
			document.getElementById('dem_pod_cost_after_sysFrei').value = val[12];
			document.getElementById('returns_empty_id_sysFrei').value = val[13];
			document.getElementById('rate_valid_until_sysFrei').value = val[14];
			document.getElementById('total_usd_sysFrei').value = val[15];
			document.getElementById('freight_chf_sysFrei').value = val[16];
			document.getElementById('total_chf_sysFrei').value = val[17];
			document.getElementById('add_chf_sysFrei').value = val[18];
			document.getElementById('transport_type_id_sysFrei').value = val[19];
			document.getElementById('weight_packaging_type_sysFrei').value = val[20];
			document.getElementById('incoterm_id_sysFrei').value = val[21];  
			document.getElementById('pod_townport_id_sysFrei').value = val[22];  
			document.getElementById('pol_townport_id_sysFrei').value = val[23];   
			document.getElementById('trans_type_id_sysFrei').value = val[24];
			document.getElementById('shipping_company_id_sysFrei').value = val[25];
			document.getElementById('dem_pol_free2_sysFrei').value = val[26];
			document.getElementById('dem_pol_cost_after2_sysFrei').value = val[27];
			document.getElementById('dem_pod_free2_sysFrei').value = val[28];
			document.getElementById('dem_pod_cost_after2_sysFrei').value = val[29];
			
			document.getElementById('id_con_box_fr_sysFrei').value = id_con_box_fr;
			
			document.getElementById('systemFreight_Modaltitle').innerHTML = 'Edit Freight';
			document.getElementById('systemFreight_Modalfooter').innerHTML = '<button type="button" class="btn btn-success" onclick="systemFreight(\'edit\');"><i class="fa fa-save"></i></button>'
				+'<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i></button>';
			
			$('.edit_delivery_date').datepicker({
				format: "yyyy/mm/dd",
				calendarWeeks:true,
				autoclose: true
			});
	
			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function deleteSystemFreight(id_con_box_fr){
	var resurl='listeslies.php?elemid=delete_system_freight&id_con_box_fr='+id_con_box_fr;    
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;
			
			if(leselect==1){
				toastr.success('Freight successfully deleted',{timeOut:15000})
				crm_freight();
				
			} else
			if(leselect==0){
				toastr.error('Freight not deleted',{timeOut:15000})
			} else {
				internal_error();
			}
			
			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}



function sysFreiTransShipToggle(value){
	if(value==1){
		document.getElementById("trans_location_id_sysFrei").disabled = false;
	} else {
		document.getElementById("trans_location_id_sysFrei").disabled = true;
	}
}

/*
* SYSTEM Ship 
* management
*/

function crm_ship() {
	hideAll();
	
	$("#db_crm_ship").removeClass("hide");
	titleMenuManag("Ship","btn_crm_ship");
	
	if(sysShip_create == 1){ $("#sysShipCreate").removeClass("hide"); } else { $("#sysShipCreate").addClass("hide"); }

	var resurl='listeslies.php?elemid=crm_ship&update_right='+sysShip_update+'&delete_right='+sysShip_delete;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;

			document.getElementById('ship_table').innerHTML = leselect;

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}

function newSystemShip() {
	
	document.getElementById("sysShipForm").reset();
	document.getElementById('photo_preview_sysShip').innerHTML = '';
	
	document.getElementById('systemShip_Modaltitle').innerHTML = 'New Ship';
	document.getElementById('systemShip_Modalfooter').innerHTML = '<button type="button" class="btn btn-success" onclick="systemShip(\'add\');"><i class="fa fa-save"></i></button>'
		+'<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i></button>';
}

function systemShip(conf) {
	
	var req="";
	var shipname=document.getElementById('shipname_sysShip').value;
	if(shipname){ req=req+'&shipname='+shipname; }
	
	var mmsi=document.getElementById('mmsi_sysShip').value;
	if(mmsi){ req=req+'&mmsi='+mmsi; }
	
	var imo=document.getElementById('imo_sysShip').value;
	if(imo){ req=req+'&imo='+imo; }
	
	var photo=document.getElementById('photo_sysShip').value;
	if(photo){ req=req+'&photo='+photo; }
	
	var id_ship=document.getElementById('id_ship_sysShip').value;
	if(id_ship){ req=req+'&id_ship='+id_ship; }
	
	
	if(shipname==""){
		alert("Enter a Ship name");
		
	} else
	if(mmsi==""){
		alert("Enter the MMSI");
		
	} else {
		var resurl='listeslies.php?elemid=manage_system_ship&conf='+conf+req;     
		var xhr = getXhr();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;   
			 
				if(leselect==1){
					toastr.success('Ship successfully saved',{timeOut:15000})
					$("#newSystShipmodal").modal("hide");
					crm_ship();
					
				} else
				if(leselect==0){
					toastr.error('Ship not saved',{timeOut:15000})
				} else {
					internal_error();
				}
				
				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
	}
}


function showSystemShip(id_ship){
	var resurl='listeslies.php?elemid=show_system_ship&id_ship='+id_ship;    
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;  
			var val = leselect.split('#');  
			
			document.getElementById('shipname_sysShip').value = val[2];   
			document.getElementById('mmsi_sysShip').value = val[0];
			document.getElementById('imo_sysShip').value = val[1];
			document.getElementById('photo_sysShip').value = val[3];
			document.getElementById('photo_preview_sysShip').innerHTML = '<img src="'+val[3]+'" class="img-responsive" />';
			
			document.getElementById('id_ship_sysShip').value = id_ship;
			
			document.getElementById('systemShip_Modaltitle').innerHTML = 'Edit Ship';
			document.getElementById('systemShip_Modalfooter').innerHTML = '<button type="button" class="btn btn-success" onclick="systemShip(\'edit\');"><i class="fa fa-save"></i></button>'
				+'<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i></button>';

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function deleteSystemShip(id_ship){
	var resurl='listeslies.php?elemid=delete_system_ship&id_ship='+id_ship;    
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;
			
			if(leselect==1){
				toastr.success('Ship successfully deleted',{timeOut:15000})
				crm_ship();
				
			} else 
			if(leselect==0){
				toastr.error('Ship not deleted',{timeOut:15000})
			} else {
				internal_error();
			}
			
			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function sysShipPhoto(id_ship){
	
	var resurl='listeslies.php?elemid=show_system_ship&id_ship='+id_ship;    
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;  
			var val = leselect.split('#');  

			document.getElementById('sysShip_photo').innerHTML = '<img src="'+val[3]+'" class="img-responsive" />';

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


// Packing List 

function packingList(save,ord_schedule_id,conf) {
	var ficheurl='pdf/packinglist.php?ord_schedule_id='+ord_schedule_id+'&save='+save+'&conf='+conf; 

	$("#bookingDocModal").modal("show");
	document.getElementById('booking_document_show').innerHTML = '<div><iframe src="'+ficheurl+'" style="width:100%; height:500px;"></iframe></div>';
	document.getElementById('booking_document_footer').innerHTML = '<a href="#" onclick="save_packingList(\'1\',\''+ord_schedule_id+'\',\''+conf+'\');" class="btn btn-info pull-left">SAVE</a>'
	+'<button type="button" class="btn btn-danger pull-right" data-dismiss="modal"><i class="fa fa-ban"></i> Close</button>';  
}


function save_packingList(save,ord_schedule_id,conf) {
	
	var doc_filename="";
	if(conf == 'supplier'){
		fileName('',ord_schedule_id,8,'invoice');
	} else
	if(conf == 'importer'){
		fileName('',ord_schedule_id,178,'invoice');
	}

	setTimeout(function(){
		doc_filename = document.getElementById('generatedFileName').value;  
		
		var resurl='listeslies.php?elemid=save_invoice&ord_schedule_id='+ord_schedule_id+'&doc_filename='+doc_filename+'&typ=packing_list'+'&conf='+conf;
		var xhr = getXhr();    
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;    
			
				if(leselect == 1){
					toastr.success('Packing List successfully saved',{timeOut:15000})
					
					var ficheurl='pdf/packinglist.php?ord_schedule_id='+ord_schedule_id+'&doc_filename='+doc_filename+'&save='+save+'&conf='+conf; 
					//window.open(ficheurl, "resultat","width=500px,height=600px,menubar=no,scrollbar=auto,resizable=yes,top=0,left=0,status=yes");
					
					savePDF(ficheurl);
					$("#bookingDocModal").modal("hide");
					
					documentList('',ord_schedule_id,'logistic'); 
					if(conf == 'supplier'){
						$("#btn_packinglist_supp").prop("disabled", true);
					} else
					if(conf == 'importer'){
						$("#btn_packinglist").prop("disabled", true);
					}
					
				} else 
				if(leselect == 0){
					toastr.error('Packing List not saved',{timeOut:15000})
				} else {
					internal_error();
				}

				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
    },3500);
}

function savePDF(ficheurl){
	var resurl=ficheurl;
    var xhr = getXhr();   
    xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;  
			
			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}

// Invoices Customs

function invoice_customs(save,ord_schedule_id,price) {
	if(price==""){
		swal({
			title: "Sorry!",
			text: "There is no sales-price for this record",
			type: "warning"
		});
		
	} else {
		
		var resurl='listeslies.php?elemid=invoice_customs_data&ord_schedule_id='+ord_schedule_id;
		var xhr = getXhr();    
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;    
				var val = leselect.split('##');
			
				$("#invoiceCostumsModal").modal("show");
				document.getElementById('invoice_cus_date').value = val[0];
				document.getElementById('invoice_cus_numb').value = val[1];
				document.getElementById('invoice_cus_ord_schedule_id').value = ord_schedule_id;		

				$('.edit_delivery_date').datepicker({
					format: "yyyy/mm/dd",
					calendarWeeks:true,
					autoclose: true
				});
				
				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
	}	
}

function invoice_customs_pdf() {	
	var invoice_date = document.getElementById('invoice_cus_date').value;
	var invoice_number = document.getElementById('invoice_cus_numb').value;	
	var ord_schedule_id = document.getElementById('invoice_cus_ord_schedule_id').value;	  
	
	var ficheurl='pdf/invoice_customs.php?ord_schedule_id='+ord_schedule_id+'&invoice_date='+invoice_date+'&invoice_number='+invoice_number+'&save=0';

	$("#bookingDocModal").modal("show");
	document.getElementById('booking_document_show').innerHTML = '<div><iframe src="'+ficheurl+'" style="width:100%; height:500px;"></iframe></div>';
	document.getElementById('booking_document_footer').innerHTML = '<a href="#" onclick="save_invoice_customs(\'1\',\''+ord_schedule_id+'\',\''+invoice_date+'\',\''+invoice_number+'\');" class="btn btn-info pull-left">SAVE</a>'
	+'<button type="button" class="btn btn-danger pull-right" data-dismiss="modal"><i class="fa fa-ban"></i> Close</button>';
}


function save_invoice_customs(save,ord_schedule_id,invoice_date,invoice_number){
	var doc_filename="";
	fileName('',ord_schedule_id,179,'invoice'); 
	setTimeout(function(){
		doc_filename = document.getElementById('generatedFileName').value;  
		var resurl='listeslies.php?elemid=save_invoice&ord_schedule_id='+ord_schedule_id+'&doc_filename='+doc_filename+'&typ=invoice_customs';
		var xhr = getXhr();    
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;   
			
				if(leselect == 1){
					toastr.success('Invoice Customs successfully saved',{timeOut:15000})
					
					var ficheurl='pdf/invoice_customs.php?ord_schedule_id='+ord_schedule_id+'&doc_filename='+doc_filename+'&invoice_date='+invoice_date+'&invoice_number='+invoice_number+'&save='+save;
		
					savePDF(ficheurl);
					$("#bookingDocModal").modal("hide");
					
					refreshInvoiceTable(ord_schedule_id,'invoice1');
					documentList('',ord_schedule_id,'logistic'); 
					
				} else 
				if(leselect == 0){
					toastr.error('Invoice Customs not saved',{timeOut:15000})
				} else {
					internal_error();
				}

				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
    },3500);
}


// Invoices 1

function invoice1(save,ord_schedule_id,price) {
	if(price==""){
		swal({
			title: "Sorry!",
			text: "There is no sales-price for this record",
			type: "warning"
		});
		
	} else {
		
		var resurl='listeslies.php?elemid=invoice_1_data&ord_schedule_id='+ord_schedule_id;
		var xhr = getXhr();    
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;   
				var val = leselect.split('##');
			
				$("#invoice1DateModal").modal("show");
				document.getElementById('invoice_1_number').value = val[1];
				document.getElementById('invoice_1_date').value = val[0];
				document.getElementById('invoice1DateFooter').innerHTML = '<button type="button" class="btn btn-primary pull-left" onclick="invoice_1_pdf(\''+ord_schedule_id+'\',\''+save+'\');" data-dismiss="modal"> Use</button>'
					+'<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Close</button>';			
				
				$('.edit_delivery_date').datepicker({
					format: "yyyy/mm/dd",
					calendarWeeks:true,
					autoclose: true
				}).datepicker('setDate', val[2]);
			
				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
	}	
}


function invoice_1_pdf(ord_schedule_id,save){
	
	var inv1_date = document.getElementById('invoice_1_date').value;
	var invoice_number = document.getElementById('invoice_1_number').value;
	var ficheurl='pdf/invoice1.php?ord_schedule_id='+ord_schedule_id+'&inv1_date='+inv1_date+'&invoice_number='+invoice_number+'&save='+save; 

	$("#bookingDocModal").modal("show");
	document.getElementById('booking_document_show').innerHTML = '<div><iframe src="'+ficheurl+'" style="width:100%; height:500px;"></iframe></div>';
	document.getElementById('booking_document_footer').innerHTML = '<a href="#" onclick="save_invoice1(\'1\',\''+ord_schedule_id+'\');" class="btn btn-info pull-left">SAVE</a>'
	 +'<button type="button" class="btn btn-danger pull-right" data-dismiss="modal"><i class="fa fa-ban"></i> Close</button>';
}


function save_invoice1(save,ord_schedule_id){
	var doc_filename="";
	fileName('',ord_schedule_id,16,'invoice'); 
	setTimeout(function(){
		doc_filename = document.getElementById('generatedFileName').value;  
		var inv1_date = document.getElementById('invoice_1_date').value;
		var invoice_number = document.getElementById('invoice_1_number').value;
		var resurl='listeslies.php?elemid=save_invoice&ord_schedule_id='+ord_schedule_id+'&doc_filename='+doc_filename+'&typ=invoice1';
		var xhr = getXhr();    
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;   
			
				if(leselect == 1){
					toastr.success('Invoice 1 successfully saved',{timeOut:15000})
					
					var ficheurl='pdf/invoice1.php?ord_schedule_id='+ord_schedule_id+'&doc_filename='+doc_filename+'&inv1_date='+inv1_date+'&invoice_number='+invoice_number+'&save='+save; 
					//window.open(ficheurl, "resultat","width=500px,height=600px,menubar=no,scrollbar=auto,resizable=yes,top=0,left=0,status=yes");
					
					savePDF(ficheurl);
					$("#bookingDocModal").modal("hide");
					
					refreshInvoiceTable(ord_schedule_id,'invoice1');
					documentList('',ord_schedule_id,'logistic'); 
					$("#btn_inv1_status").prop("disabled", true);
				} else 
				if(leselect == 0){
					toastr.error('Invoice 1 not saved',{timeOut:15000})
				} else {
					internal_error();
				}

				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
    },3500);
}


// Invoices 2

function invoice2_option(save,ord_schedule_id,price,total_diff) {
	if(price==""){
		swal({
			title: "Sorry!",
			text: "There is no sales-price for this record",
			type: "warning"
		});
	} else {	
	
		var resurl='listeslies.php?elemid=invoice_2_data&ord_schedule_id='+ord_schedule_id;
		var xhr = getXhr();    
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText; 
				var val = leselect.split('##');
			
				$("#invoice2DateModal").modal("show");
				document.getElementById('invoice_2_number').value = val[1];
				document.getElementById('invoice_2_date').value = val[0];
				document.getElementById('invoice2DateFooter').innerHTML = '<button type="button" class="btn btn-primary pull-left" onclick="invoice_2_pdf(\''+save+'\',\''+ord_schedule_id+'\',\''+price+'\',\''+total_diff+'\');" data-dismiss="modal"> Use</button>'
					+'<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Close</button>';			
				
				$('.edit_delivery_date').datepicker({
					format: "yyyy/mm/dd",
					calendarWeeks:true,
					autoclose: true
				}).datepicker('setDate', val[2]);
			
				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
	}
}


function invoice_2_pdf(save,ord_schedule_id,price,total_diff){
	
	var inv2_date = document.getElementById('invoice_2_date').value;
	var invoice_number = document.getElementById('invoice_2_number').value;
	
	if(total_diff>0){
		$("#invoice2OptionModal").modal("show");
		document.getElementById('zahlungskonditionen').value = '';
		document.getElementById('invoice2OptionModal_footer').innerHTML = '<button type="button" class="btn btn-primary pull-left" onclick="invoice2(\''+save+'\',\''+ord_schedule_id+'\',\''+price+'\');" data-dismiss="modal"> Use</button>'
			+'<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Close</button>'; 
		
	} else {
		
		var ficheurl='pdf/invoice2.php?ord_schedule_id='+ord_schedule_id+'&inv2_date='+inv2_date+'&invoice_number='+invoice_number+'&save='+save; 

		$("#bookingDocModal").modal("show");
		document.getElementById('booking_document_show').innerHTML = '<div><iframe src="'+ficheurl+'" style="width:100%; height:500px;"></iframe></div>';
		document.getElementById('booking_document_footer').innerHTML = '<a href="#" onclick="save_invoice2(\'1\',\''+ord_schedule_id+'\');" class="btn btn-info pull-left">SAVE</a>'
		+'<button type="button" class="btn btn-danger pull-right" data-dismiss="modal"><i class="fa fa-ban"></i> Close</button>';
	}
}


function invoice2(save,ord_schedule_id,price) {
	if(price==""){
		swal({
			title: "Sorry!",
			text: "There is no sales-price for this record",
			type: "warning"
		});
	} else {
		var inv2_date = document.getElementById('invoice_2_date').value;
		var invoice_number = document.getElementById('invoice_2_number').value;
		// var zahlungskonditionen = document.getElementById('zahlungskonditionen').value;
		var zahlungskonditionen = $('#zahlungskonditionen').val().replace(/\n/g,'@@');
		var ficheurl='pdf/invoice2.php?ord_schedule_id='+ord_schedule_id+'&save='+save+'&inv2_date='+inv2_date+'&invoice_number='+invoice_number+'&zahlungskonditionen='+zahlungskonditionen; 

		$("#invoice2OptionModal").modal("hide");
		$("#bookingDocModal").modal("show");
		document.getElementById('booking_document_show').innerHTML = '<div><iframe src="'+ficheurl+'" style="width:100%; height:500px;"></iframe></div>';
		document.getElementById('booking_document_footer').innerHTML = '<a href="#" onclick="save_invoice2(\'1\',\''+ord_schedule_id+'\');" class="btn btn-info pull-left">SAVE</a>'
		+'<button type="button" class="btn btn-danger pull-right" data-dismiss="modal"><i class="fa fa-ban"></i> Close</button>';
	}
}


function save_invoice2(save,ord_schedule_id){
	var doc_filename="";
	fileName('',ord_schedule_id,176,'invoice'); 
	setTimeout(function(){
		doc_filename = document.getElementById('generatedFileName').value;  
		var inv2_date = document.getElementById('invoice_2_date').value;
		var invoice_number = document.getElementById('invoice_2_number').value;
		var zahlungskonditionen = $('#zahlungskonditionen').val().replace(/\n/g,'@@');
		var resurl='listeslies.php?elemid=save_invoice&ord_schedule_id='+ord_schedule_id+'&doc_filename='+doc_filename+'&typ=invoice2';
		var xhr = getXhr();    
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;    
			
				if(leselect == 1){
					toastr.success('Invoice 2 successfully saved',{timeOut:15000})
					
					var ficheurl='pdf/invoice2.php?ord_schedule_id='+ord_schedule_id+'&doc_filename='+doc_filename+'&save='+save+'&inv2_date='+inv2_date+'&invoice_number='+invoice_number+'&zahlungskonditionen='+zahlungskonditionen; 
					//window.open(ficheurl, "resultat","width=500px,height=600px,menubar=no,scrollbar=auto,resizable=yes,top=0,left=0,status=yes");
					
					savePDF(ficheurl);
					$("#bookingDocModal").modal("hide");
					
					refreshInvoiceTable(ord_schedule_id,'invoice2');
					documentList('',ord_schedule_id,'logistic'); 
					$("#btn_inv2_status").prop("disabled", true);
				} else
				if(leselect == 0){
					toastr.error('Invoice 2 not saved',{timeOut:15000})
				} else {
					internal_error();
				}

				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
    },3500);
}



// Refresh Invoices Tables

function refreshInvoiceTable(ord_schedule_id,type) {
	var resurl='listeslies.php?elemid=refresh_invoice_tables&ord_schedule_id='+ord_schedule_id+'&type='+type;
	var xhr = getXhr();    
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;   
		
			if(type=='invoice1'){
				document.getElementById('onward_invoice_1').innerHTML = leselect;  
			} else
			if(type=='invoice2'){
				document.getElementById('onward_invoice_2').innerHTML = leselect;  
			} else {}
			
			leselect = xhr.responseText;
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}


function editBio() {
	$("#edit_bio").removeClass("hide");
	$("#edit_bio").removeClass("fadeOut");
	$("#edit_bio").addClass("fadeInRightBig");
	$("#card-1-modal").removeClass("hide");
	
	$('.edit_delivery_date').datepicker({
		format: "yyyy/mm/dd",
		calendarWeeks:true,
		autoclose: true
	});
}


function CancelEditBio() {
	$("#edit_bio").addClass("hide");
	$("#edit_bio").removeClass("fadeInRightBig");
	$("#edit_bio").addClass("fadeOut");
	$("#card-1-modal").addClass("hide");
}

function editLinks() {
	$("#edit_links").removeClass("hide");
	$("#edit_links").removeClass("fadeOut");
	$("#edit_links").addClass("fadeInRightBig");
	$("#card-2-modal").removeClass("hide");
}

function CancelEditLinks() {
	$("#edit_links").addClass("hide");
	$("#edit_links").removeClass("fadeInRightBig");
	$("#edit_links").addClass("fadeOut");
	$("#card-2-modal").addClass("hide");
}

function editDemog() {
	$("#edit_demog").removeClass("hide");
	$("#edit_demog").removeClass("fadeOut");
	$("#edit_demog").addClass("fadeInRightBig");
	$("#card-3-modal").removeClass("hide");
}

function CancelEditDemog() {
	$("#edit_demog").addClass("hide");
	$("#edit_demog").removeClass("fadeInRightBig");
	$("#edit_demog").addClass("fadeOut");
	$("#card-3-modal").addClass("hide");
}

function editPlantation() {
	$("#edit_plantation").removeClass("hide");
	$("#edit_plantation").removeClass("fadeOut");
	$("#edit_plantation").addClass("fadeInRightBig");
	$("#card-4-modal").removeClass("hide");
}

function CancelEditPlantation() {
	$("#edit_plantation").addClass("hide");
	$("#edit_plantation").removeClass("fadeInRightBig");
	$("#edit_plantation").addClass("fadeOut");
	$("#card-4-modal").addClass("hide");
}

function editPlantationCertificate() {
	$("#edit_plantation_certificate").removeClass("hide");
	$("#edit_plantation_certificate").removeClass("fadeOut");
	$("#edit_plantation_certificate").addClass("fadeInRightBig");
	$("#card-6-modal").removeClass("hide");
	
	$('.edit_delivery_date').datepicker({
		format: "yyyy-mm-dd",
		calendarWeeks:true,
		autoclose: true
	});
}
 
function CancelEditPlantationCertificate() {
	$("#edit_plantation_certificate").addClass("hide");
	$("#edit_plantation_certificate").removeClass("fadeInRightBig");
	$("#edit_plantation_certificate").addClass("fadeOut");
	$("#card-6-modal").addClass("hide");
}

function editBio2(id_contact) {
	$("#edit_bio2").removeClass("hide");
	$("#edit_bio2").removeClass("fadeOut");
	$("#edit_bio2").addClass("fadeInRightBig");
	$("#card-1-modal").removeClass("hide");
	
	showContactDetails(id_contact);
}

function CancelEditBio2() {
	$("#edit_bio2").addClass("hide");
	$("#edit_bio2").removeClass("fadeInRightBig");
	$("#edit_bio2").addClass("fadeOut");
	$("#card-1-modal").addClass("hide");
}

function editHousehold() {
	$("#edit_household").removeClass("hide");
	$("#edit_household").removeClass("fadeOut");
	$("#edit_household").addClass("fadeInRightBig");
	$("#card-5-modal").removeClass("hide");
}

function CancelEditHousehold() {
	$("#edit_household").addClass("hide");
	$("#edit_household").removeClass("fadeInRightBig");
	$("#edit_household").addClass("fadeOut");
	$("#card-5-modal").addClass("hide");
}

function editContactContent(id_contact){
	
	document.getElementById('edit_bio2').innerHTML = '<div class="h1 m-t-xs text-navy"><span class="loading"></span></div>';
	
	var resurl='include/contact.php?elemid=second_edit_contact_form&id_contact='+id_contact;
	var xhr = getXhr();    
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;   
		
			document.getElementById('edit_bio2').innerHTML = leselect; 
			
			$('.edit_delivery_date').datepicker({
				format: "yyyy/mm/dd",
				calendarWeeks:true,
				autoclose: true
			});
			
			leselect = xhr.responseText;
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}


function analyticCalendar(date) { 
	$('.ana_cal_date').datepicker({
		format: "yyyy/mm/dd",
		calendarWeeks:true,
		autoclose: true
	}).datepicker('setDate', date);
}


function showContactDetails(id_contact) {
	
	document.getElementById('edit_bio2').innerHTML = '<div class="h1 m-t-xs text-navy"><span class="loading"></span></div>';
	
	var resurl='include/contact.php?elemid=second_show_contact_form&id_contact='+id_contact;
	var xhr = getXhr();    
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;   
		
			document.getElementById('edit_bio2').innerHTML = leselect; 

			leselect = xhr.responseText;
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}


function archive(ord_schedule_id) {
	var resurl='listeslies.php?elemid=add_to_archive&ord_schedule_id='+ord_schedule_id;
	var xhr = getXhr();    
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;    
			
			if(leselect == 1){
				toastr.success('Successfully added to archive',{timeOut:15000})
				$("#onw_archive").prop("disabled", true);
				// logistique(0,0);
				
			} else
			if(leselect == 0){
				toastr.error('Archive not saved',{timeOut:15000})
				
			} else {
				internal_error();
			}

			leselect = xhr.responseText;
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}


function accounting(ord_schedule_id) {
	var resurl='listeslies.php?elemid=accounting&ord_schedule_id='+ord_schedule_id;
	var xhr = getXhr();    
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;    
			
			if(leselect == 1){
				toastr.success('Pipeline successfully changed',{timeOut:15000})
				$("#onw_accounting").prop("disabled", true);
				
			} else
			if(leselect == 0){
				toastr.error('Pipeline not changed',{timeOut:15000})
				
			} else {
				internal_error();
			}

			leselect = xhr.responseText;
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}


/*
* CRM 
* Version 2
***********/

function new_crm_loading(id_ord_schedule,incoterms_id,id_con_booking,ord_order_id,pipeline_id,ref_number,grid_id) {
	
	var req="";
	
	req=req+'&sched_update='+sched_update+'&sched_create='+sched_create;
	req=req+'&update_request_exporter='+exporter_update_2;
	req=req+'&calc_update='+calc_update_2;
	
	var mail = '<a href="#" class="pull-right" style="margin-left:10px; color:#fff;"  onclick="eMailForm(\''+ord_order_id+'\',\'crm\',\'\');"><i class="fa fa-envelope"></i></a>';
	
	if(docManager_read==1){
		var doc_crm = '<a href="#" class="pull-right" style="margin-left:10px; color:#fff;" onclick="showDocList(\''+ord_order_id+'\',\'\',\'crm\',\'\');"><i class="fa fa-file-text"></i></a>';
		var doc_log = '<a href="#" class="pull-right" style="margin-left:10px; color:#fff;" onclick="showDocList(\''+ord_order_id+'\',\''+id_ord_schedule+'\',\'logistic\',\'\');"><i class="fa fa-file-text"></i></a>';
		
		document.getElementById('summaryDocs2').innerHTML = mail+doc_crm;
		document.getElementById('requestDocs2').innerHTML = doc_log;
	}
	
	document.getElementById('sum_refnum2').innerHTML = ref_number;  
	document.getElementById('req_refnum2').innerHTML = ref_number;  
	
	var spanner = '<div class="h1 m-t-xs text-navy"><span class="loading"></span></div>';
	
	document.getElementById('freight_12').innerHTML = spanner;
	document.getElementById('freight_22').innerHTML = '';
	
	
	document.getElementById('user_summary2').innerHTML = spanner;   
	document.getElementById('importer_summary2').innerHTML = spanner;  
	document.getElementById('contract_summary2').innerHTML = spanner;
	document.getElementById('request_schedule2').innerHTML = spanner;
	document.getElementById('schedule_exporter').innerHTML = spanner;
	document.getElementById('schedule_calc_table2').innerHTML = spanner;
	document.getElementById('proposal_content2').innerHTML = spanner;
	document.getElementById('ord_confrim_ctn2').innerHTML = spanner;
	
	
	var resurl='listeslies.php?elemid=new_crm_loading&id_ord_schedule='+id_ord_schedule+'&cus_incoterms_id='+incoterms_id+'&id_con_booking='+id_con_booking+'&ord_order_id='+ord_order_id+'&pipeline_sched_id='+pipeline_id+'&grid_id='+grid_id+'&ref_number='+ref_number+req;
	var xhr = getXhr();    
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;  
			var val = leselect.split('@@');
		
			document.getElementById('user_summary2').innerHTML = val[0];   
			document.getElementById('importer_summary2').innerHTML = val[1];  
			document.getElementById('contract_summary2').innerHTML = val[2];
			document.getElementById('request_schedule2').innerHTML = val[3];
			document.getElementById('schedule_exporter').innerHTML = val[4];
			
			
			// Exporter status
			if(val[11]==1){
				if(freight_read_2 == 1){ $("#crm_request_freight_tab2").removeClass("hide"); $("#crm_request_freight_ct2").removeClass("hide"); } 
				else { $("#crm_request_freight_tab2").addClass("hide"); $("#crm_request_freight_ct2").addClass("hide"); }

			} else {
				$("#crm_request_freight_tab2").addClass("hide"); $("#crm_request_freight_ct2").addClass("hide");
			}
		
			// Calculate status
			if(val[12]==1){
				if(proposal_read_2 == 1){ $("#crm_request_proposal_tab2").removeClass("hide"); $("#crm_request_proposal_ct2").removeClass("hide"); } 
				else { $("#crm_request_proposal_tab2").addClass("hide"); $("#crm_request_proposal_ct2").addClass("hide"); }
				
			} else {
				$("#crm_request_proposal_tab2").addClass("hide"); $("#crm_request_proposal_ct2").addClass("hide");
			}
		
			// Order status
			if(val[13]==1){
				if(ordcon_read_2 == 1){ $("#crm_request_ordconfirm_tab2").removeClass("hide"); $("#crm_request_ordconfirm_ct2").removeClass("hide");  } 
				else { $("#crm_request_ordconfirm_tab2").addClass("hide"); $("#crm_request_ordconfirm_ct2").addClass("hide"); }
				
				if(contract_read_2 == 1){
					$('#crm_contract_tab2').removeClass("hide"); $('#crm_contract2').removeClass("hide");
				}
				
			} else {
				$("#crm_request_ordconfirm_tab2").addClass("hide"); $("#crm_request_ordconfirm_ct2").addClass("hide");
				
				if(contract_read_2 == 1){
					$('#crm_contract_tab2').addClass("hide"); $('#crm_contract2').addClass("hide");
				}
			}
			
			// Freight status
			if(val[14]==1){ 
				if(calc_read_2 == 1){ $("#crm_request_calc_tab2").removeClass("hide"); $("#crm_request_calc_ct2").removeClass("hide"); } 
				else { $("#crm_request_calc_tab2").addClass("hide"); $("#crm_request_calc_ct2").addClass("hide"); }
			
			} else { 
				$("#crm_request_calc_tab2").addClass("hide"); $("#crm_request_calc_ct2").addClass("hide");
			}
			
			
			if(proposal_update_2 == 1){ $('#proposal_doc_toggle2').removeClass('hide'); } else { $('#proposal_doc_toggle2').addClass('hide'); }
			if(contract_update == 1){ $("#contractTabEdit2").removeClass("hide"); } else { $("#contractTabEdit2").addClass("hide"); }
			if(sum_update_2 == 1){ $("#sumCusRequestToggler2").removeClass("hide"); } else { $("#sumCusRequestToggler2").addClass("hide"); }
			if(sumNote_update == 1){ $("#sumBtnsToggler2").removeClass("hide"); } else { $("#sumBtnsToggler2").addClass("hide"); }
		
		
			if(val[5]){
				document.getElementById('addShipmentROW2').innerHTML = val[5];
			}
			
			$('#exporter_quote_formContent2').find('input, textarea, select').prop("disabled", true);
			
			// FREIGHT
			var f_val = val[6].split('??');   
			
			if(f_val[0]==0){
				var elmt = f_val[1].split('#');
				if(freight_update_2 == 1){
					var addFreightBtn1 = '<button type="submit" id="addFreight12" class="btn btn-success btn-sm pull-right" onclick="cheickForFreight(\''+elmt[0]+'\',\''+elmt[1]+'\',\''+elmt[2]+'\',\''+elmt[3]+'\',\''+elmt[4]+'\');"><i class="fa fa-plus"></i></button>';
					var addFreightBtn2 = '<button type="submit" id="addFreight22" class="btn btn-success btn-sm pull-right" onclick="secondFreightList(\''+elmt[1]+'\',\''+elmt[3]+'\',\''+elmt[4]+'\');"><i class="fa fa-plus"></i></button>';
				} else {
					var addFreightBtn1 = '';
					var addFreightBtn2 = '';
				}
				
				// Freight status 
				if(elmt[6]==1){  
					if(calc_read_2 == 1){ $("#crm_request_calc_tab2").removeClass("hide"); $("#crm_request_calc_ct2").removeClass("hide"); } 
					else { $("#crm_request_calc_tab2").addClass("hide"); $("#crm_request_calc_ct2").addClass("hide"); }
					
					if(proposal_read_2 == 1){ $("#crm_request_proposal_tab2").removeClass("hide"); $("#crm_request_proposal_ct2").removeClass("hide"); } 
					else { $("#crm_request_proposal_tab2").addClass("hide"); $("#crm_request_proposal_ct2").addClass("hide"); }
					
				} else {
					$("#crm_request_calc_tab2").addClass("hide"); $("#crm_request_calc_ct2").addClass("hide"); 
					$("#crm_request_proposal_tab2").addClass("hide"); $("#crm_request_proposal_ct2").addClass("hide");
				}
				
				document.getElementById('F1_title2').innerHTML = 'Ocean Freight '+addFreightBtn1;
				document.getElementById('F2_title2').innerHTML = 'Onward Freight '+addFreightBtn2;
				
				document.getElementById('freight_12').innerHTML = '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> No saved freight';
			
			} else
			if(f_val[0]==1) {
				
				document.getElementById('freight_12').innerHTML = '';
				document.getElementById('freight_22').innerHTML = '';
			
				var elmt2 = f_val[2].split('#');
				if(freight_update_2 == 1){
					var addFreightBtn1 = '<button type="submit" id="addFreight12" class="btn btn-success btn-sm pull-right" onclick="cheickForFreight(\''+elmt2[0]+'\',\''+elmt2[1]+'\',\''+elmt2[2]+'\',\''+elmt2[3]+'\',\''+elmt2[4]+'\');"><i class="fa fa-plus"></i></button>';
					var addFreightBtn2 = '<button type="submit" id="addFreight22" class="btn btn-success btn-sm pull-right" onclick="secondFreightList(\''+elmt2[1]+'\',\''+elmt2[3]+'\',\''+elmt2[4]+'\');"><i class="fa fa-plus"></i></button>';
				} else {
					var addFreightBtn1 = '';
					var addFreightBtn2 = '';
				}
				
				// Freight status 
				if(elmt2[6]==1){  
					if(calc_read_2 == 1){ $("#crm_request_calc_tab2").removeClass("hide"); $("#crm_request_calc_ct2").removeClass("hide"); } 
					else { $("#crm_request_calc_tab2").addClass("hide"); $("#crm_request_calc_ct2").addClass("hide"); }
					
					if(proposal_read_2 == 1){ $("#crm_request_proposal_tab2").removeClass("hide"); $("#crm_request_proposal_ct2").removeClass("hide"); } 
					else { $("#crm_request_proposal_tab2").addClass("hide"); $("#crm_request_proposal_ct2").addClass("hide"); }
					
				} else {
					$("#crm_request_calc_tab2").addClass("hide"); $("#crm_request_calc_ct2").addClass("hide"); 
					$("#crm_request_proposal_tab2").addClass("hide"); $("#crm_request_proposal_ct2").addClass("hide");
				}
				
				document.getElementById('F1_title2').innerHTML = 'Ocean Freight '+addFreightBtn1;
				document.getElementById('F2_title2').innerHTML = 'Onward Freight '+addFreightBtn2;
				
				var data=f_val[1].split('%%');  
			
				i = 0;
				while (data[i] != 'end') { 
					var elt=data[i].split('##');   
				
					var pol_name = elt[0];
					var dem_pol_free = elt[1];
					var incoterm_name = elt[2];
					var pod_name = elt[3];
					var dem_pod_free = elt[4];
					var carrier  = elt[5];
					var rate_valid_until  = elt[6];
					var packaging_type_name  = elt[7];
					var trans_delay  = elt[8];
					
					var num=i+1;
					
					showFreight2(pol_name,dem_pol_free,incoterm_name,pod_name,dem_pod_free,carrier,rate_valid_until,packaging_type_name,trans_delay,num);
					
					i += 1;	
				}
				
				freightModifyBy2(id_ord_schedule);
				
			} else {}
			
			if(f_val[0]!=3){
				var last_shipment = val[7];  

				document.getElementById('freight_copy_to_all2').innerHTML = '<button type="button" id="copyFreightBtn2" class="btn btn-info pull-left" onclick="copyFreight('+id_ord_schedule+','+last_shipment+');"> '+lg_contract_copy_to_all+' </button>';
				if(freight_update_2 == 1){
					// if(pipeline_id == 296){ var dis="disabled"; } else { var dis=""; }
					document.getElementById('editFreight2').innerHTML = '<button type="button" id="editFreightBtn2" '+dis+' class="btn btn-success pull-right" onclick="editFreight('+id_ord_schedule+','+last_shipment+');"><i class="fa fa-edit"></i></button>';
				} 
			
				$('#copyFreightBtn2').prop("disabled", true);
				
				$('#addFreight12').prop("disabled", true);
				$('#addFreight22').prop("disabled", true);
			}
			// CALCULATION
			
			document.getElementById('schedule_calc_table2').innerHTML = val[8]; 
			
			$('#calcVariableBloc2').find('input, select').prop("disabled", true);
			// $('#tableCalcBtn2').prop("disabled", true);
			$('#tableCalcBtn2').prop("disabled", false);
			$('#oandaBtn2').prop("disabled", true);
			
			$('.i-checks').iCheck({
				checkboxClass: 'icheckbox_square-green',
				radioClass: 'iradio_square-green'
			});
			
			$('input').on('ifChanged', function(event){ calcActiveState($(event.target).val()); });
			
			
			// PROPOSAL
			
			document.getElementById('proposal_content2').innerHTML = val[9];  
			
			// ORDER
			
			document.getElementById('ord_confrim_ctn2').innerHTML = val[10]; 
			
			
			$('.edit_delivery_date').datepicker({
				format: "yyyy/mm/dd",
				calendarWeeks:true,
				autoclose: true
			}); 

			leselect = xhr.responseText;
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}

/*
* CRM2 - Summary
* Edit Customer Notes
*/

// Show input
function editIntNotes2(id_ord_order) {
	$('#intNotesShow2').addClass("hide");
	$('#intNotesInput2').removeClass("hide");
	
	document.getElementById("intNotesManagBtn2").innerHTML = '<a href="#" class="btn btn-white btn-sm" onclick="saveEditIntNotes2('+id_ord_order+');"><i class="fa fa-check" style="color:green;"></i></a>'+
		' <a href="#" class="btn btn-white btn-sm" onclick="cancelEditIntNotes2('+id_ord_order+');"><i class="fa fa-times" style="color:red;"></i></a>';
}

// Save Edited
function saveEditIntNotes2(id_ord_order) {
	var notes_internal = document.getElementById("edit_notes_internal2").value;
	
	var resurl='listeslies.php?elemid=save_edited_int_notes&id_ord_order='+id_ord_order+'&notes_internal='+notes_internal;
    var xhr = getXhr();
    xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;
            var val=leselect.split('##');

			if(val[0]==1){
				toastr.success('Note saved successfully.',{timeOut:15000})
				document.getElementById("intNotesShow2").innerHTML = val[1];
				cancelEditIntNotes2(id_ord_order);
				
			} else 
			if(val[0]==0){
				toastr.error('Note not saved.',{timeOut:15000})
				
			} else {
				internal_error();
			}

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null)
}


// Cancel Edition
function cancelEditIntNotes2(id_ord_order) {
	$('#intNotesShow2').removeClass("hide");
	$('#intNotesInput2').addClass("hide");
	
	document.getElementById("intNotesManagBtn2").innerHTML = '<a herf="#" onclick="editIntNotes2('+id_ord_order+');" class="btn btn-white btn-sm"><i class="fa fa-edit"></i></a>';
}


// Show input
function editorderNrOld2(id_ord_order) {
	$('#orderNrOldShow2').addClass("hide");
	$('#orderNrOldInput2').removeClass("hide");
	
	document.getElementById("orderNrOldManagBtn2").innerHTML = '<a href="#" class="btn btn-white btn-sm" onclick="saveEditOrderNrOld2('+id_ord_order+');"><i class="fa fa-check" style="color:green;"></i></a>'+
		' <a href="#" class="btn btn-white btn-sm" onclick="cancelEditOrderNrOld2('+id_ord_order+');"><i class="fa fa-times" style="color:red;"></i></a>';
}

// Save Edited
function saveEditOrderNrOld2(id_ord_order) {
	var order_nr_old = document.getElementById("edit_order_nr_old2").value;
	
	var resurl='listeslies.php?elemid=save_edited_order_nr_old&id_ord_order='+id_ord_order+'&order_nr_old='+order_nr_old;
    var xhr = getXhr();
    xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;
            var val=leselect.split('##');

			if(val[0]==1){
				toastr.success('Note saved successfully.',{timeOut:15000})
				document.getElementById("orderNrOldShow2").innerHTML = val[1];
				cancelEditOrderNrOld2(id_ord_order);
				
			} else 
			if(val[0]==0){
				toastr.error('Note not saved.',{timeOut:15000})
				
			} else {
				internal_error();
			}

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null)
}


// Cancel Edition
function cancelEditOrderNrOld2(id_ord_order) {
	$('#orderNrOldShow2').removeClass("hide");
	$('#orderNrOldInput2').addClass("hide");
	
	document.getElementById("orderNrOldManagBtn2").innerHTML = '<a herf="#" onclick="editorderNrOld2('+id_ord_order+');" class="btn btn-white btn-sm"><i class="fa fa-edit"></i></a>';
}

/*
* CRM2 - Summary
* Edit Customer Ref-number
*/

// Show input
function editCusRefNumb2(id_ord_order) {
	$('#cusRefNumbShow2').addClass("hide");
	$('#cusRefNumbInput2').removeClass("hide");
	
	document.getElementById("cusRefNumbManagBtn2").innerHTML = '<a href="#" class="btn btn-white btn-sm" onclick="saveEditCusRefNumb2('+id_ord_order+');"><i class="fa fa-check" style="color:green;"></i></a>'+
		' <a href="#" class="btn btn-white btn-sm" onclick="cancelEditCusRefNumb2('+id_ord_order+');"><i class="fa fa-times" style="color:red;"></i></a>';
}

// Save Edited
function saveEditCusRefNumb2(id_ord_order) {
	var customer_reference_nr = document.getElementById("edit_customer_reference_nr2").value;
	
	var resurl='listeslies.php?elemid=save_edited_cus_ref_number&id_ord_order='+id_ord_order+'&customer_reference_nr='+customer_reference_nr;
    var xhr = getXhr();
    xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;
            var val=leselect.split('##');

			if(val[0]==1){
				toastr.success('Customer Ref-Number saved successfully.',{timeOut:15000})
				document.getElementById("cusRefNumbShow2").innerHTML = val[1];
				cancelEditCusRefNumb2(id_ord_order);
				
			} else 
			if(val[0]==0){
				toastr.error('Customer Ref-Number not saved.',{timeOut:15000})
				
			} else {
				internal_error();
			}

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null)
}


// Cancel Edition
function cancelEditCusRefNumb2(id_ord_order) {
	$('#cusRefNumbShow2').removeClass("hide");
	$('#cusRefNumbInput2').addClass("hide");
	
	document.getElementById("cusRefNumbManagBtn2").innerHTML = '<a href="#" onclick="editCusRefNumb2('+id_ord_order+');" class="btn btn-white btn-sm"><i class="fa fa-edit"></i></a>';
}


/*
* CRM2 - Summary
* Edit Customer Notes
*/

function showEditCusNotes2() {
	$("#cusNotesManagBtn2").removeClass("hide");

	document.getElementById('sumCusRequestToggler2').innerHTML = '<button class="btn btn-danger pull-right" style="margin-top:10px; margin-right:20px;" onclick="closeEditCusNotes2();" type="button"><i class="fa fa-ban"></i></button>'+
		' &nbsp;<button class="btn btn-success pull-right" style="margin-top:10px; margin-right:10px; " onclick="save_customer_req_notes2();" type="button"><i class="fa fa-save"></i></button>';
}
	
	
function closeEditCusNotes2() {
	$("#cusNotesManagBtn2").addClass("hide");
	
	document.getElementById('sumCusRequestToggler2').innerHTML = '<button class="btn btn-success pull-right" onclick="showEditCusNotes2();" style="margin-top:10px; margin-right:20px;" type="button">'
		+'<i class="fa fa-edit"></i></button>';
}

function save_customer_req_notes2(){
	closeEditCusNotes2();
}

// Show input
function editCusNotes2(id_ord_order) {
	$('#cusNotesShow2').addClass("hide");
	$('#cusNotesInput2').removeClass("hide");
	
	document.getElementById("cusNotesManagBtn2").innerHTML = '<a href="#" onclick="saveEditCusNotes2('+id_ord_order+');" class="btn btn-white btn-sm"><i class="fa fa-check" style="color:green;"></i></a>'+
		' <a href="#"onclick="cancelEditCusNotes('+id_ord_order+');" class="btn btn-white btn-sm"><i class="fa fa-times" style="cursor:pointer; color:red;" onclick="cancelEditCusNotes2('+id_ord_order+');"></i></a>';
}

// Save Edited
function saveEditCusNotes2(id_ord_order) {
	var notes_customer = document.getElementById("edit_notes_customer2").value;
	
	var resurl='listeslies.php?elemid=save_edited_cus_notes&id_ord_order='+id_ord_order+'&notes_customer='+notes_customer;
    var xhr = getXhr();
    xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;
            var val=leselect.split('##');

			if(val[0]==1){
				toastr.success('Customer Note saved successfully.',{timeOut:15000})
				document.getElementById("cusNotesShow2").innerHTML = val[1];
				cancelEditCusNotes2(id_ord_order);
				
			} else 
			if(val[0]==0){
				toastr.error('Customer Note not saved.',{timeOut:15000})
				
			} else {
				internal_error();
			}

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null)
}


// Cancel Edition
function cancelEditCusNotes2(id_ord_order) {
	$('#cusNotesShow2').removeClass("hide");
	$('#cusNotesInput2').addClass("hide");
	
	document.getElementById("cusNotesManagBtn2").innerHTML = '<a href="#" onclick="editCusNotes2('+id_ord_order+');" class="btn btn-white btn-sm"><i class="fa fa-edit"></i></a>';
}

// CRM2 Summary Notes
function showSumEditBtns2() {
	$("#intNotesManagBtn2").removeClass("hide");
	$("#editSumStatus2").removeClass("hide");
	$("#editSumPipeline2").removeClass("hide");
	$("#orderNrOldManagBtn2").removeClass("hide");

	document.getElementById("sm_person_id2").disabled = false;
	document.getElementById("ord_imp_person_id2").disabled = false;

	document.getElementById('sumBtnsToggler2').innerHTML = '<button class="btn btn-danger pull-right" style="margin-top:10px; margin-right:20px;" onclick="showSumCloseBtn2();" type="button"><i class="fa fa-ban"></i></button>'+
		' &nbsp;<button class="btn btn-success pull-right" style="margin-top:10px; margin-right:10px; " onclick="save_notes_summary2();" type="button"><i class="fa fa-save"></i></button>';
}

function showSumCloseBtn2() {
	$("#intNotesManagBtn2").addClass("hide");
	$("#editSumStatus2").addClass("hide");
	$("#editSumPipeline2").addClass("hide");
	$("#orderNrOldManagBtn2").addClass("hide");
	
	document.getElementById("sm_person_id2").disabled = true;
	document.getElementById("ord_imp_person_id2").disabled = true;
		
	document.getElementById('sumBtnsToggler2').innerHTML = '<button class="btn btn-success pull-right" onclick="showSumEditBtns2();" style="margin-top:10px; margin-right:20px;" type="button">'
		+'<i class="fa fa-edit"></i></button>';
}

function save_notes_summary2(){
	showSumCloseBtn2();
}

function editImpPerson2() {
	var ord_infos = document.getElementById('ord_imp_person_id2').value;
	var data = ord_infos.split("#");
	
	var ord_imp_person_id = data[1];
	var id_ord_order = data[0];
	
	var resurl='listeslies.php?elemid=update_importer_person&id_ord_order='+id_ord_order+'&ord_imp_person_id='+ord_imp_person_id;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
           leselect = xhr.responseText;
			var val = leselect.split('##');
			
			if(val[0] == 1){
				toastr.success(val[1],{timeOut:15000})
			} else 
			if(val[0] == 0){
				toastr.error(val[1],{timeOut:15000})
			} else {
				internal_error();
			}

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function editOrderSmManager2() {
	var ord_infos = document.getElementById('sm_person_id2').value;
	var data = ord_infos.split("#");
	
	var sm_person_id = data[1];
	var id_ord_order = data[0];
	
	var resurl='listeslies.php?elemid=update_sm_person&id_ord_order='+id_ord_order+'&sm_person_id='+sm_person_id;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
           leselect = xhr.responseText;
			var val = leselect.split('##');
			
			if(val[0] == 1){
				toastr.success(val[1],{timeOut:15000})
				sendSM_ManagerMail(id_ord_order);
			} else 
			if(val[0] == 0){
				toastr.error(val[1],{timeOut:15000})
			} else {
				internal_error();
			}

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}

/*
* CRM2 - Summary
* Edit Pipeline/Status
*/

function editSumPipeline2(id_ord_order) {
	document.getElementById("sumPipeline_id_ord_order2").value = id_ord_order;
	$("#modalEditSumPipeline2").modal("show");
}

function saveEditedSumPipeline2() {
	
	var id_ord_order = document.getElementById("sumPipeline_id_ord_order2").value;
	var pipeline_id = document.getElementById("sumStatus_pipeline_id2").value;
	
	var resurl='listeslies.php?elemid=save_edited_sum_pipeline&id_ord_order='+id_ord_order+'&pipeline_id='+pipeline_id;
    var xhr = getXhr();
    xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;
            var val=leselect.split('##');

			if(val[0]==1){
				toastr.success('Pipeline saved successfully.',{timeOut:15000})
				document.getElementById("sumPipelineName2").innerHTML = val[1];
				document.getElementById("sumPipelineForm2").reset();
				$("#modalEditSumPipeline2").modal("hide");
				crm_manag(0,0);
				
			} else 
			if(val[0]==0){
				toastr.error('Pipeline not saved.',{timeOut:15000})
			} else {
				internal_error();
			}

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null)
}


/*
* CRM2 - Summary
* Edit Notes/Status
*/

function editSumStatus2(id_ord_order) {
	document.getElementById("sumStatus_id_ord_order2").value = id_ord_order;
	$("#modalEditSumStatus2").modal("show");
}

function saveEditedSumStatus2() {
	
	var id_ord_order = document.getElementById("sumStatus_id_ord_order2").value;
	var status_id = document.getElementById("sumStatus_status_id2").value;
	
	var resurl='listeslies.php?elemid=save_edited_sum_status&id_ord_order='+id_ord_order+'&status_id='+status_id;
    var xhr = getXhr();
    xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;
            var val=leselect.split('##');

			if(val[0]==1){
				toastr.success('Status saved successfully.',{timeOut:15000})
				document.getElementById("sumStatusName2").innerHTML = val[1];
				document.getElementById("sumStatusForm2").reset();
				$("#modalEditSumStatus2").modal("hide");
				
			} else 
			if(val[0]==0){
				toastr.error('Status not saved.',{timeOut:15000})
			} else {
				internal_error();
			}

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null)
}

/*
* CRM2 - Request
* Edit Schedule
*/

function saveEditScheduleLine2(id_ord_order,id_ord_schedule) {

	var month_eta = document.getElementById('date2-'+id_ord_schedule).value;   
	var kk = $("#date2-"+id_ord_schedule).val(); 
	var week_eta = moment(kk, "YYYY/MM/DD").week(); 
	
	var containers = document.getElementById('qty2-'+id_ord_schedule).value;
	if(containers != ""){
		var nr_containers = containers;
	} else {
		var nr_containers = 0;
	}

	var weight_container = document.getElementById('wgt2-'+id_ord_schedule).value;  
	
	var resurl='listeslies.php?elemid=save_edit_schedule&id_ord_schedule='+id_ord_schedule+'&nr_containers='+nr_containers+'&weight_container='+weight_container+'&month_eta='+month_eta+'&week_eta='+week_eta+'&id_ord_order='+id_ord_order+'&sched_update='+sched_update;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText; 
			var val = leselect.split('##');
			
			if(val[0] == 1){
				toastr.success(val[1],{timeOut:15000})
				refreshScheduleShipement(id_ord_order); 
				if(val[2]!=""){ 
					var ficheurl='pdf/notification.php?id_ord_schedule='+id_ord_schedule+'&doc_filename='+val[2]+'&old_month_eta='+val[3]+'&old_nr_containers='+val[4]+'&conf=edit'; 
					saveNotificationMail(ficheurl);
				}
				
			} else 
			if(val[0] == 0){
				toastr.error(val[1],{timeOut:15000})
			} else {
				internal_error();
			}

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}

function showEditScheduleLine2(id_ord_schedule,incoterms_id,id_con_booking,id_ord_order,pipeline_id,ref_number,grid_id) {
	new_crm_loading(id_ord_schedule,incoterms_id,id_con_booking,id_ord_order,pipeline_id,ref_number,grid_id);
}


function edit_contract2(id_ord_order) {
	$("#cusRefNumbCTManagBtn2").removeClass("hide");
	$("#orderSupRefNrManagBtn2").removeClass("hide");
	$("#orderFaCompManagBtn2").removeClass("hide");
	$("#orderFaRefNrManagBtn2").removeClass("hide");
	
	document.getElementById('contractTabEdit2').innerHTML = '<button class="btn btn-danger pull-right" onclick="cancel_contractEditing2(\''+id_ord_order+'\');" type="button"><i class="fa fa-ban"></i></button>'+
		' &nbsp;<button class="btn btn-success pull-right" onclick="save_contract2(\''+id_ord_order+'\');" style="margin-right:10px;" type="button"><i class="fa fa-save"></i></button>';
}

function cancel_contractEditing2(id_ord_order){ 
	$("#cusRefNumbCTManagBtn2").addClass("hide"); 
	$("#orderSupRefNrManagBtn2").addClass("hide"); 
	$("#orderFaCompManagBtn2").addClass("hide"); 
	$("#orderFaRefNrManagBtn2").addClass("hide"); 
	
	document.getElementById('contractTabEdit2').innerHTML = '<button class="btn btn-success pull-right" onclick="edit_contract2(\''+id_ord_order+'\');" style="margin-top:10px;" type="button"><i class="fa fa-edit"></i></button>';
}

/*
 Manage
 Contract customer reference number
*/

function editCusRefNumbCT2(id_ord_order){
	$("#orderCusRefNrLabel2").addClass("hide");
	$("#orderCusRefNrInput2").removeClass("hide");
	
	document.getElementById("cusRefNumbCTManagBtn2").innerHTML = '<a href="#" class="btn btn-white btn-sm" onclick="save_contract2('+id_ord_order+');"><i class="fa fa-check" style="color:green;"></i></a>'+
		' <a href="#" class="btn btn-white btn-sm" onclick="cancelCusRefNumbCT2('+id_ord_order+');"><i class="fa fa-times" style="color:red;"></i></a>';
}

function cancelCusRefNumbCT2(id_ord_order) {
	$('#orderCusRefNrLabel2').removeClass("hide");
	$('#orderCusRefNrInput2').addClass("hide");
	
	document.getElementById("cusRefNumbCTManagBtn2").innerHTML = '<a herf="#" onclick="editCusRefNumbCT2('+id_ord_order+');" class="btn btn-white btn-sm"><i class="fa fa-edit"></i></a>';
}

/*
 Manage
 Contract supplier reference number
*/

function editorderSupRefNr2(id_ord_order){
	$("#orderSupRefNrLabel2").addClass("hide");
	$("#orderSupRefNrInput2").removeClass("hide");
	
	document.getElementById("orderSupRefNrManagBtn2").innerHTML = '<a href="#" class="btn btn-white btn-sm" onclick="save_contract2('+id_ord_order+');"><i class="fa fa-check" style="color:green;"></i></a>'+
		' <a href="#" class="btn btn-white btn-sm" onclick="cancelSupRefNrCT2('+id_ord_order+');"><i class="fa fa-times" style="color:red;"></i></a>';
}

function cancelSupRefNrCT2(id_ord_order) {
	$('#orderSupRefNrLabel2').removeClass("hide");
	$('#orderSupRefNrInput2').addClass("hide");
	
	document.getElementById("orderSupRefNrManagBtn2").innerHTML = '<a herf="#" onclick="editorderSupRefNr2('+id_ord_order+');" class="btn btn-white btn-sm"><i class="fa fa-edit"></i></a>';
}

/*
 Manage
 Contract Freight Agent Company
*/

function editorderFaComp2(id_ord_order){
	$("#orderFaCompLabel2").addClass("hide");
	$("#orderFaCompSelect2").removeClass("hide");
	
	document.getElementById("orderFaCompManagBtn2").innerHTML = '<a href="#" class="btn btn-white btn-sm" onclick="save_contract2('+id_ord_order+');"><i class="fa fa-check" style="color:green;"></i></a>'+
		' <a href="#" class="btn btn-white btn-sm" onclick="cancelorderFaComp2('+id_ord_order+');"><i class="fa fa-times" style="color:red;"></i></a>';
}

function cancelorderFaComp2(id_ord_order) {
	$('#orderFaCompLabel2').removeClass("hide");
	$('#orderFaCompSelect2').addClass("hide");
	
	document.getElementById("orderFaCompManagBtn2").innerHTML = '<a herf="#" onclick="editorderFaComp2('+id_ord_order+');" class="btn btn-white btn-sm"><i class="fa fa-edit"></i></a>';
}

/*
 Manage
 Freight Agent Contract Number
*/

function editorderFaRefNr2(id_ord_order){
	$("#orderFaRefNrLabel2").addClass("hide");
	$("#orderFaRefNrInput2").removeClass("hide");
	
	document.getElementById("orderFaRefNrManagBtn2").innerHTML = '<a href="#" class="btn btn-white btn-sm" onclick="save_contract2('+id_ord_order+');"><i class="fa fa-check" style="color:green;"></i></a>'+
		' <a href="#" class="btn btn-white btn-sm" onclick="cancelorderFaRefNr2('+id_ord_order+');"><i class="fa fa-times" style="color:red;"></i></a>';
}

function cancelorderFaRefNr2(id_ord_order) {
	$('#orderFaRefNrLabel2').removeClass("hide");
	$('#orderFaRefNrInput2').addClass("hide");
	
	document.getElementById("orderFaRefNrManagBtn2").innerHTML = '<a herf="#" onclick="editorderFaRefNr2('+id_ord_order+');" class="btn btn-white btn-sm"><i class="fa fa-edit"></i></a>';
}

/*
* Save contract
*/

function save_contract2(id_ord_order){
	var sup_reference_nr = document.getElementById("sup_reference_nr_CT2").value;
	var fa_reference_nr = document.getElementById("fa_reference_nr_CT2").value;
	var customer_reference_nr = document.getElementById("customer_reference_nr_CT2").value;
	var ord_fa_contact_id = document.getElementById("ord_fa_contact_id2").value;
	
	var resurl='listeslies.php?elemid=save_edited_contract&id_ord_order='+id_ord_order+'&sup_reference_nr='+sup_reference_nr+'&fa_reference_nr='+fa_reference_nr+'&customer_reference_nr='+customer_reference_nr+'&ord_fa_contact_id='+ord_fa_contact_id;
    var xhr = getXhr(); 
    xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText; 
            var val=leselect.split('##');

			if(val[0]==1){
				toastr.success('Contract saved successfully.',{timeOut:15000})
				document.getElementById("orderSupRefNrLabel2").innerHTML = val[1]; 
				document.getElementById("orderFaRefNrLabel2").innerHTML = val[2];  
				document.getElementById("orderCusRefNrLabel2").innerHTML = val[3];  
				document.getElementById("orderFaCompLabel2").innerHTML = val[4];  
				cancel_contractEditing(id_ord_order);
				
				cancelCusRefNumbCT2(id_ord_order);
				cancelSupRefNrCT2(id_ord_order);
				cancelorderFaComp2(id_ord_order);
				cancelorderFaRefNr2(id_ord_order);
				
			} else {
				toastr.error('Note not saved.',{timeOut:15000})
			}

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null)
}


function bySupplierContactId2(id_company) {
	var resurl='listeslies.php?elemid=by_supplier_id_company&id_company='+id_company;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;
			var val = leselect.split('##');

			document.getElementById('req_quote_supplier_person_id2').innerHTML = val[0];
			document.getElementById('req_quote_pol_id2').innerHTML = val[1];

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}

function transitDaysCal2(id_townport,ord_order_id,id_ord_schedule) {  

	// var incoterms_id = document.getElementById('req_quote_incoterms_id2').value;  
	// if(incoterms_id==263){ process=0; }
	// else if(incoterms_id==264){ process=0; }
	// else{ process=1; }
	
	var resurl='listeslies.php?elemid=transit_days_calcul&ord_order_id='+ord_order_id+'&id_townport='+id_townport+'&id_ord_schedule='+id_ord_schedule;
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;  
			var val = leselect.split('##');

			if((val[2]=="")&&(val[3]=="")){
				document.getElementById('transDays2').innerHTML = 'Transit days : '+val[1];
				document.getElementById('req_quote_month_etd2').value = val[0];
		
				$('#req_quote_month_etd2').datepicker({
					format: "yyyy/mm/dd",
					calendarWeeks:true,
					autoclose: true
				}).datepicker('setDate', val[0]);
				
				document.getElementById("req_quote_week_etd2").value = moment(val[0], "YYYY/MM/DD").week();
				
			} else {
				document.getElementById('transDays2').innerHTML = '';
				document.getElementById("req_quote_week_eta2").value = moment(val[2], "YYYY/MM/DD").week();
				document.getElementById("req_quote_month_eta2").value = val[2];
			}

			leselect = xhr.responseText;
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}

function getWeekShowQuote_etd2() {
	var kk = $("#req_quote_month_etd2").val(); 
	document.getElementById("req_quote_week_etd2").value = moment(kk, "YYYY/MM/DD").week();
}

function save_exporter_quote2(conf,incoterms,ord_schedule_id,last_ship_nr,id_ord_order) {

	var req="";
	var req_quote_supplier_contact_id=document.getElementById('req_quote_supplier_contact_id2').value;
	if(req_quote_supplier_contact_id){ req=req+'&req_quote_supplier_contact_id='+req_quote_supplier_contact_id; }
	
	var req_quote_incoterms_id=document.getElementById('req_quote_incoterms_id2').value;
	if(req_quote_incoterms_id){ req=req+'&req_quote_incoterms_id='+req_quote_incoterms_id; }
	
	var req_quote_pol_id=document.getElementById('req_quote_pol_id2').value;
	if(req_quote_pol_id){ req=req+'&req_quote_pol_id='+req_quote_pol_id; }
	
	var req_quote_month_etd=document.getElementById('req_quote_month_etd2').value;
	if(req_quote_month_etd){ req=req+'&req_quote_month_etd='+req_quote_month_etd; }
	
	var req_quote_week_etd=document.getElementById('req_quote_week_etd2').value;
	if(req_quote_week_etd){ req=req+'&req_quote_week_etd='+req_quote_week_etd; }
	
	var req_quote_currency_id=document.getElementById('req_quote_currency_id2').value;
	if(req_quote_currency_id){ req=req+'&req_quote_currency_id='+req_quote_currency_id; }
	
	var req_quote_price_sup_eur=document.getElementById('req_quote_price_sup_eur2').value;
	if(req_quote_price_sup_eur){ req=req+'&req_quote_price_sup_eur='+req_quote_price_sup_eur; }  
	
	var req_quote_supplier_person_id=document.getElementById('req_quote_supplier_person_id2').value;
	if(req_quote_supplier_person_id){ req=req+'&req_quote_supplier_person_id='+req_quote_supplier_person_id; }
	
	var req_quote_reference_nr=document.getElementById('req_quote_reference_nr2').value;
	if(req_quote_reference_nr){ req=req+'&req_quote_reference_nr='+req_quote_reference_nr; }
	
	var req_quote_sup_quote_validity=document.getElementById('req_quote_sup_quote_validity2').value;
	if(req_quote_sup_quote_validity){ req=req+'&req_quote_sup_quote_validity='+req_quote_sup_quote_validity; }
	
	var req_quote_supplier_cf_date=document.getElementById('req_quote_supplier_cf_date2').value;
	if(req_quote_supplier_cf_date){ req=req+'&req_quote_supplier_cf_date='+req_quote_supplier_cf_date; }
	
	var req_quote_sm_notes=document.getElementById('req_quote_sm_notes2').value;
	if(req_quote_sm_notes){ req=req+'&req_quote_sm_notes='+req_quote_sm_notes; }
	
	var req_quote_week_eta=document.getElementById('req_quote_week_eta2').value;
	if(req_quote_week_eta){ req=req+'&req_quote_week_eta='+req_quote_week_eta; }
	
	var req_quote_month_eta=document.getElementById('req_quote_month_eta2').value;
	if(req_quote_month_eta){ req=req+'&req_quote_month_eta='+req_quote_month_eta; }
	
	var req_quote_tank_provider=document.getElementById('req_quote_tank_provider2').value;
	if(req_quote_tank_provider){ req=req+'&req_quote_tank_provider='+req_quote_tank_provider; }
	
	var id_ord_schedule=document.getElementById('exporter_id_ord_schedule').value;
	
	if(conf==1){ req=req+'&proposal='+conf; }
	
	var resurl='listeslies.php?elemid=save_exporter_quote&id_ord_schedule='+id_ord_schedule+req;      
    var xhr = getXhr();  
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;    
		 
			if(leselect==1){
				toastr.success('Profil updated shedule',{timeOut:15000})
				
				exporter_quote_cancelEditing2(ord_schedule_id,last_ship_nr,id_ord_order);
				// showQuoteForm(ord_schedule_id,last_ship_nr,id_ord_order);
				
				if(conf==1){ 
					$("#crm_request_freight_tab").removeClass("hide");
					$("#crm_request_freight_ct").removeClass("hide");
				}
				crm_manag2(0);
				
			} else 
			if(leselect==0){
				toastr.error('Unable to update shedule',{timeOut:15000})
			} else {
				internal_error(); 
			}
			
			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}


function exporter_quote_editForm2(id_ord_schedule,last_ship_nr,id_ord_order,pipeline_id){
	
	$('#exporter_quote_formContent2').find('input, textarea, select').prop("disabled", false);
	if((pipeline_id == 293)||(pipeline_id == 294)){
		$('#sendProposalBtnId2').prop("disabled", false);
	} else {
		$('#sendProposalBtnId2').prop("disabled", true);
	}
	
	document.getElementById('exporterQuoteBtn2').innerHTML = '<button class="btn btn-success" onclick="save_exporter_quote2(\'0\',\'0\',\''+id_ord_schedule+'\',\''+last_ship_nr+'\',\''+id_ord_order+'\',\''+pipeline_id+'\');" type="button"><i class="fa fa-save"></i></button>'+
		' &nbsp;<button class="btn btn-danger" onclick="exporter_quote_cancelEditing2(\''+id_ord_schedule+'\',\''+last_ship_nr+'\',\''+id_ord_order+'\',\''+pipeline_id+'\');" type="button"><i class="fa fa-ban"></i></button>';
}


function exporter_quote_cancelEditing2(id_ord_schedule,last_ship_nr,id_ord_order,pipeline_id){
	
	$('#exporter_quote_formContent2').find('input, textarea, select').prop("disabled", true);
	$('#sendProposalBtnId2').prop("disabled", true);
	
	document.getElementById('exporterQuoteBtn2').innerHTML = '<button class="btn btn-success" onclick="exporter_quote_editForm2(\''+id_ord_schedule+'\',\''+last_ship_nr+'\',\''+id_ord_order+'\',\''+pipeline_id+'\');" type="button"><i class="fa fa-edit"></i></button>';
}


/* Customer Reference Number */

function edit_order_confirmation2(id_ord_schedule){
	$('#supRefNrManagBtn2').removeClass("hide");
	$('#cusRefShipNrManagBtn2').removeClass("hide");  
	$('#faRefNrManagBtn2').removeClass("hide");  
	
	document.getElementById('showhideOrdConfirmEditBtn2').innerHTML = '<button class="btn btn-danger pull-right" onclick="order_confirmation_cancelEditing2('+id_ord_schedule+');" style="margin-top:10px;" type="button"><i class="fa fa-ban"></i></button>'
	 +'&nbsp;<button class="btn btn-success pull-right" onclick="save_order_confirmation('+id_ord_schedule+');" style="margin-top:10px; margin-right:15px;" type="button"><i class="fa fa-save"></i></button>';
	
	$('#offer_accepted_btn2').prop("disabled", false);
	
	$('#create_contract_btn2').prop("disabled", false);
	$('#puchase_order_fa_btn2').prop("disabled", false);
	$('#puchase_order_supp_btn2').prop("disabled", false);
	$('#puchase_order_supp_btn21').prop("disabled", false);
	$('#puchase_order_supp_btn22').prop("disabled", false);	
}


function order_confirmation_cancelEditing2(id_ord_schedule){
	$('#supRefNrManagBtn2').addClass("hide");
	$('#cusRefShipNrManagBtn2').addClass("hide");  
	$('#faRefNrManagBtn2').addClass("hide"); 

	document.getElementById('showhideOrdConfirmEditBtn2').innerHTML = '<button class="btn btn-success pull-right" onclick="edit_order_confirmation2('+id_ord_schedule+');" style="margin-top:10px;" type="button"><i class="fa fa-edit"></i></button>';	
	
	$('#offer_accepted_btn2').prop("disabled", true);
	
	$('#create_contract_btn2').prop("disabled", true);
	$('#puchase_order_fa_btn2').prop("disabled", true);
	$('#puchase_order_supp_btn2').prop("disabled", true);
	$('#puchase_order_supp_btn21').prop("disabled", true);
	$('#puchase_order_supp_btn22').prop("disabled", true);	
}


// Cancel Edition
function cancelEditcusRefShipNr2(id_ord_schedule) {
	$('#cusRefShipNrShow2').removeClass("hide");
	$('#cusRefShipNrInput2').addClass("hide");
	
	document.getElementById("cusRefShipNrManagBtn2").innerHTML = '<a herf="#" onclick="editCusRefShipNr2('+id_ord_schedule+');" class="btn btn-white btn-sm"><i class="fa fa-edit"></i></a>';
}


// Show input
function editCusRefShipNr2(id_ord_schedule) {
	$('#cusRefShipNrShow2').addClass("hide");
	$('#cusRefShipNrInput2').removeClass("hide");
	
	document.getElementById("cusRefShipNrManagBtn2").innerHTML = '<a href="#" class="btn btn-white btn-sm" onclick="saveEditcusRefShipNr2('+id_ord_schedule+');"><i class="fa fa-check" style="color:green;"></i></a>'+
		' <a href="#" class="btn btn-white btn-sm" onclick="cancelEditcusRefShipNr2('+id_ord_schedule+');"><i class="fa fa-times" style="color:red;"></i></a>';
}

// Save Edited
function saveEditcusRefShipNr2(id_ord_schedule) {
	var customer_ref_ship_nr = document.getElementById("customer_ref_ship_nr2").value;
	
	var resurl='listeslies.php?elemid=save_edited_customer_reference_nrd&id_ord_schedule='+id_ord_schedule+'&customer_ref_ship_nr='+customer_ref_ship_nr;
    var xhr = getXhr();
    xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;
            var val=leselect.split('##');

			if(val[0]==1){
				toastr.success('Customer Reference Number saved successfully.',{timeOut:15000})
				document.getElementById("cusRefShipNrShow2").innerHTML = val[1];
				cancelEditcusRefShipNr2(id_ord_schedule);
				
			} else 
			if(val[0]==0){
				toastr.error('Customer Reference Number not saved.',{timeOut:15000})
			} else {
				internal_error();
			}

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null)
}

/* Supplier Reference Number */

// Cancel Edition
function cancelEditsupRefNr2(id_ord_schedule) {
	$('#supRefNrShow2').removeClass("hide");
	$('#supRefNrInput2').addClass("hide");
	
	document.getElementById("supRefNrManagBtn2").innerHTML = '<a herf="#" onclick="editsupRefNr2('+id_ord_schedule+');" class="btn btn-white btn-sm"><i class="fa fa-edit"></i></a>';
}


// Show input
function editsupRefNr2(id_ord_schedule) {
	$('#supRefNrShow2').addClass("hide");
	$('#supRefNrInput2').removeClass("hide");
	
	document.getElementById("supRefNrManagBtn2").innerHTML = '<a href="#" class="btn btn-white btn-sm" onclick="saveEditsupRefNr2('+id_ord_schedule+');"><i class="fa fa-check" style="color:green;"></i></a>'+
		' <a href="#" class="btn btn-white btn-sm" onclick="cancelEditsupRefNr2('+id_ord_schedule+');"><i class="fa fa-times" style="color:red;"></i></a>';
}

// Save Edited
function saveEditsupRefNr2(id_ord_schedule) {
	var supplier_reference_nr = document.getElementById("supplier_reference_nr2").value;
	
	var resurl='listeslies.php?elemid=save_edited_supplier_reference_nrd&id_ord_schedule='+id_ord_schedule+'&supplier_reference_nr='+supplier_reference_nr;
    var xhr = getXhr();
    xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;
            var val=leselect.split('##');

			if(val[0]==1){
				toastr.success('Supplier Reference Number saved successfully.',{timeOut:15000})
				document.getElementById("supRefNrShow2").innerHTML = val[1];
				cancelEditsupRefNr2(id_ord_schedule);
				
			} else 
			if(val[0]==0){
				toastr.error('Supplier Reference Number not saved.',{timeOut:15000})
			} else {
				internal_error();
			}

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null)
}

/* Freight Agent Reference Number */

// Cancel Edition
function cancelEditfaRefNr2(id_ord_schedule) {
	$('#faRefNrShow2').removeClass("hide");
	$('#faRefNrInput2').addClass("hide");
	
	document.getElementById("faRefNrManagBtn2").innerHTML = '<a herf="#" onclick="editfaRefNr2('+id_ord_schedule+');" class="btn btn-white btn-sm"><i class="fa fa-edit"></i></a>';
}


// Show input
function editfaRefNr2(id_ord_schedule) {
	$('#faRefNrShow2').addClass("hide");
	$('#faRefNrInput2').removeClass("hide");
	
	document.getElementById("faRefNrManagBtn2").innerHTML = '<a href="#" class="btn btn-white btn-sm" onclick="saveEditfaRefNr2('+id_ord_schedule+');"><i class="fa fa-check" style="color:green;"></i></a>'+
		' <a href="#" class="btn btn-white btn-sm" onclick="cancelEditfaRefNr2('+id_ord_schedule+');"><i class="fa fa-times" style="color:red;"></i></a>';
}

// Save Edited
function saveEditfaRefNr2(id_ord_schedule) {
	var fa_reference_nr = document.getElementById("fa_reference_nr_OC2").value;
	
	var resurl='listeslies.php?elemid=save_edited_freight_agent_reference_nrd&id_ord_schedule='+id_ord_schedule+'&fa_reference_nr='+fa_reference_nr;
    var xhr = getXhr();
    xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;
            var val=leselect.split('##');

			if(val[0]==1){
				toastr.success('Freight Agent Reference Number saved successfully.',{timeOut:15000})
				document.getElementById("faRefNrShow2").innerHTML = val[1];
				cancelEditfaRefNr2(id_ord_schedule);
				
			} else 
			if(val[0]==0){
				toastr.error('Freight Agent Reference Number not saved.',{timeOut:15000})
			} else {
				internal_error();
			}

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null)
}

/* ----- END - CRM2 ----- */


/* ----- START - WORKFLOW ----- */

// Process

function wfProcess() {
	hideAll();
	
	$("#db_wf_process").removeClass("hide");
	titleMenuManag("Workflow - Process","btn_wf_process");
	
	wf_process_table();
}

function clearWf_newProcessForm() {
	$('#processForm').find("input, textarea, select").val(""); 
}

function wf_process_table() {
	var resurl='include/workflow.php?elemid=wf_process_table&update_right='+wfProcess_update+'&delete_right='+wfProcess_delete; 
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;   

			document.getElementById('list_wfProcess').innerHTML = leselect;

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}

function triggerInProcess(id_process,process_name) {
	var resurl='include/workflow.php?elemid=wf_trigger_in_process_table&id_process='+id_process;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;

			document.getElementById('list_triggerInP').innerHTML = leselect;
			document.getElementById('selectedProcess').innerHTML = process_name;

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}

function wfProcessManagement(conf,id_process) {

	if(conf == 'add'){
		/* Add new process */
		
		var req = '';
		
		var process_name = document.getElementById("nPForm_process_name").value;
		if(process_name){ req=req+'&process_name='+process_name; }
		
		var ord_order_id = document.getElementById("nPForm_ord_order_id").value;
		if(ord_order_id){ req=req+'&ord_order_id='+ord_order_id; }
		
		var ord_schedule_id = document.getElementById("nPForm_ord_schedule_id").value;
		if(ord_schedule_id){ req=req+'&ord_schedule_id='+ord_schedule_id; }
		
		
		if(process_name == ""){
			alert("Enter process name.");
			
		} else {
			var resurl='include/workflow.php?elemid=wf_process_management&conf='+conf+req;   
			var xhr = getXhr();
			xhr.onreadystatechange = function(){
				if(xhr.readyState == 4 ){
					leselect = xhr.responseText;       
					
					if(leselect == 1){
						toastr.success('Process successfully added',{timeOut:15000})
						wf_process_table();
						clearWf_newProcessForm();
					
					} else 
					if(leselect == 0){
						toastr.error('Process not added, please retry!',{timeOut:15000})
					} else {
						internal_error();
					}
					
					leselect = xhr.responseText;
				}
			};

			xhr.open("GET",resurl,true);
			xhr.send(null);
		}

	} else
	if(conf == 'show'){
		
		document.getElementById("wfPocessModalForm").reset();
		
		var resurl='include/workflow.php?elemid=show_wf_process&id_process='+id_process;   
		var xhr = getXhr();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;          
				var val = leselect.split('#');
				
				document.getElementById("wfProcessModal_process_name").value = val[0];
				document.getElementById("wfProcessModal_ord_order_id").value = val[1];
				document.getElementById("wfProcessModal_ord_schedule_id").value = val[2];
				
				document.getElementById('wfProcessModal_id_process').value = id_process;
				
				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
	
	
		/* Edit button */
		document.getElementById('wf_processModalLabel').innerHTML = "Edit Process";
		document.getElementById('wf_ProcessModalFooter').innerHTML ='<button type="button" class="btn btn-primary" onclick="wfProcessManagement(\'edit\',\''+id_process+'\');"><i class="fa fa-save"></i></button>'
			+'<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i></button>';
		
	} else
	if(conf == 'edit'){	 
		
		/* Edit process */
		
		var req = '';
		
		var process_name = document.getElementById("wfProcessModal_process_name").value;
		if(process_name){ req=req+'&process_name='+process_name; }
		
		var ord_order_id = document.getElementById("wfProcessModal_ord_order_id").value;
		if(ord_order_id){ req=req+'&ord_order_id='+ord_order_id; }
		
		var ord_schedule_id = document.getElementById("wfProcessModal_ord_schedule_id").value;
		if(ord_schedule_id){ req=req+'&ord_schedule_id='+ord_schedule_id; }
		
		
		
		var resurl='include/workflow.php?elemid=wf_process_management&conf='+conf+'&id_process='+id_process+req;    
		var xhr = getXhr();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;         
				
				if(leselect == 1){
					toastr.success('Process successfully saved',{timeOut:15000})
					$("#modalWfProcess").modal("hide");
					wf_process_table();
					
				} else 
				if(leselect == 0){
					toastr.error('Unable to save process, please retry!',{timeOut:15000})
				} else {
					internal_error();
				}
				
				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
		
	} else
	if(conf == 'del'){
		
		/* Delete process */
		
		var resurl='include/workflow.php?elemid=delete_wf_process&id_process='+id_process;    
		var xhr = getXhr();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;         
				
				if(leselect == 1){
					toastr.success('Process successfully deleted',{timeOut:15000})
					wf_process_table();
				} else 
				if(leselect == 0){
					toastr.error('Process not deleted, please retry!',{timeOut:15000})
				} else {
					internal_error();
				}
				
				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
		
	} else {}
}

// Triger

function wfTrigger() {
	hideAll();
	
	$("#db_wf_trigger").removeClass("hide");
	titleMenuManag("Workflow - Triger","btn_wf_trigger");
	
	wf_trigger_table(0);
}

function clearWf_newTriggerForm() {
	$('#triggerForm').find("input, textarea, select").val(""); 
}

function wf_trigger_table(value) {
	var resurl='include/workflow.php?elemid=wf_trigger_table&update_right='+wfTrigger_update+'&delete_right='+wfTrigger_delete+'&value='+value;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;

			document.getElementById('list_wfTrigger').innerHTML = leselect;

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}

function triggerFilterByProcess(value) {
	wf_trigger_table(value);
}

function wfTriggerManagement(conf,id_trigger) {

	if(conf == 'add'){
		
		/* Add new trigger */
		
		var req = '';
		
		var trigger_name = document.getElementById("nTForm_trigger_name").value;
		if(trigger_name){ req=req+'&trigger_name='+trigger_name; }
		
		var id_process = document.getElementById("nTForm_id_process").value;
		if(id_process){ req=req+'&id_process='+id_process; }
		
		var sequence_nr = document.getElementById("nTForm_sequence_nr").value;
		if(sequence_nr){ req=req+'&sequence_nr='+sequence_nr; }
		
		
		if(trigger_name == ""){
			alert("Enter trigger name.");
			
		} else	
		if(id_process == ""){
			alert("Select a process.");
			
		} else {
			var resurl='include/workflow.php?elemid=wf_trigger_management&conf='+conf+req;   
			var xhr = getXhr();
			xhr.onreadystatechange = function(){
				if(xhr.readyState == 4 ){
					leselect = xhr.responseText;       
					
					if(leselect == 1){
						toastr.success('Trigger successfully added',{timeOut:15000})
						wf_trigger_table(0);
						clearWf_newTriggerForm();
					
					} else 
					if(leselect == 0){
						toastr.error('Trigger not added, please retry!',{timeOut:15000})
					} else {
						internal_error();
					}
					
					leselect = xhr.responseText;
				}
			};

			xhr.open("GET",resurl,true);
			xhr.send(null);
		}	

	} else
	if(conf == 'show'){
		
		document.getElementById("wfTriggerModalForm").reset();
		
		var resurl='include/workflow.php?elemid=show_wf_trigger&id_trigger='+id_trigger;   
		var xhr = getXhr();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;          
				var val = leselect.split('#');
				
				document.getElementById("wfTriggerModal_trigger_name").value = val[0];
				document.getElementById("wfTriggerModal_id_process").value = val[1];
				document.getElementById("wfTriggerModal_sequence_nr").value = val[2];
				
				document.getElementById('wfTriggerModal_id_trigger').value = id_trigger;
				
				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
	
	
		/* Edit button */
		document.getElementById('wf_triggerModalLabel').innerHTML = "Edit Trigger";
		document.getElementById('wf_TriggerModalFooter').innerHTML ='<button type="button" class="btn btn-primary" onclick="wfTriggerManagement(\'edit\',\''+id_trigger+'\');"><i class="fa fa-save"></i></button>'
			+'<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i></button>';
		
	} else
	if(conf == 'edit'){	 
		
		/* Edit trigger */
		
		var req = '';
		
		var trigger_name = document.getElementById("wfTriggerModal_trigger_name").value;
		if(trigger_name){ req=req+'&trigger_name='+trigger_name; }
		
		var id_process = document.getElementById("wfTriggerModal_id_process").value;
		if(id_process){ req=req+'&id_process='+id_process; }
		
		var sequence_nr = document.getElementById("wfTriggerModal_sequence_nr").value;
		if(sequence_nr){ req=req+'&sequence_nr='+sequence_nr; }
		
		
		var resurl='include/workflow.php?elemid=wf_trigger_management&conf='+conf+'&id_trigger='+id_trigger+req;    
		var xhr = getXhr();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;         
				
				if(leselect == 1){
					toastr.success('Process successfully saved',{timeOut:15000})
					$("#modalWfTrigger").modal("hide");
					wf_trigger_table(0);
					
				} else 
				if(leselect == 0){
					toastr.error('Unable to save process, please retry!',{timeOut:15000})
				} else {
					internal_error();
				}
				
				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
		
	} else
	if(conf == 'del'){
		
		/* Delete trigger */
		
		var resurl='include/workflow.php?elemid=delete_wf_trigger&id_trigger='+id_trigger;    
		var xhr = getXhr();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;         
				
				if(leselect == 1){
					toastr.success('Trigger successfully deleted',{timeOut:15000})
					wf_trigger_table(0);
				} else 
				if(leselect == 0){
					toastr.error('Trigger not deleted, please retry!',{timeOut:15000})
				} else {
					internal_error();
				}
				
				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
		
	} else {}
}


// Actions

function wfActions() {
	hideAll();
	
	$("#db_wf_actions").removeClass("hide");
	titleMenuManag("Workflow - Actions","btn_wf_action");
	
	
}

// Member Group

function wfGroup() {
	hideAll();
	
	$("#db_wf_group").removeClass("hide");
	titleMenuManag("Workflow - Member Group","btn_wf_group");
	
	wf_group_table();
	wf_usersList();
}

function clearWf_newGroupForm() {
	$('#groupForm').find("input, textarea, select").val(""); 
}

function wf_group_table() {
	var resurl='include/workflow.php?elemid=wf_group_table&update_right='+wfGroup_update+'&delete_right='+wfGroup_delete;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText; 

			document.getElementById('list_wfGroup').innerHTML = leselect;

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}

function wf_usersList() {
	
	var group = '';

	if($("input[type='radio'].radioBtnGroupClass").is(':checked')) {
		group = $("input[type='radio'].radioBtnGroupClass:checked").val();
	}
	
	var data = group.split('##');
	var group_id = data[0];

	var resurl='include/workflow.php?elemid=wf_users_list&update_right='+wfGroup_update+'&group_id='+group_id;
	var xhr = getXhr();
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText; 

			document.getElementById('list_usersWFgroup').innerHTML = leselect;

			leselect = xhr.responseText;
		}
	};
	
	xhr.open("GET",resurl,true);
	xhr.send(null);
}

function userInGroup(group_id,group_name) {
	var resurl='include/workflow.php?elemid=wf_users_in_group_table&group_id='+group_id+'&update_right='+wfGroup_update;
    var xhr = getXhr();
	xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 ){
            leselect = xhr.responseText;

			document.getElementById('list_usersInG').innerHTML = leselect;
			document.getElementById('selectedGroup').innerHTML = group_name;
			wf_usersList();

			leselect = xhr.responseText;
        }
    };

    xhr.open("GET",resurl,true);
    xhr.send(null);
}

function addUserToGroup_wf(id_contact) {
	var group = '';

	if($("input[type='radio'].radioBtnGroupClass").is(':checked')) {
		group = $("input[type='radio'].radioBtnGroupClass:checked").val();
	}
	
	if(group == ""){
		toastr.info('Select a group in the list',{timeOut:15000})
		
	} else {
		var data = group.split('##');
		var group_id = data[0];
		var group_name = data[1];
		
		var resurl='include/workflow.php?elemid=add_user_to_group_wf&id_contact='+id_contact+'&group_id='+group_id;
		var xhr = getXhr();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;

				if(leselect == 1){
					toastr.success('User successfully added',{timeOut:15000})
					userInGroup(group_id,group_name);
					wf_usersList();
					
				} else 
				if(leselect == 0){
					toastr.error('User not added, please retry!',{timeOut:15000})
				} else {
					internal_error();
				}

				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
	}
}

function removeUserFromGroup_wf(id_contact) {
	var group = '';

	if($("input[type='radio'].radioBtnGroupClass").is(':checked')) {
		group = $("input[type='radio'].radioBtnGroupClass:checked").val();
	}
	
	if(group == ""){
		toastr.info('Select a group in the list',{timeOut:15000})
		
	} else {
		var data = group.split('##');
		var group_id = data[0];
		var group_name = data[1];
		
		var resurl='include/workflow.php?elemid=remove_user_from_group_wf&id_contact='+id_contact;
		var xhr = getXhr();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;

				if(leselect == 1){
					toastr.success('User successfully removed',{timeOut:15000})
					userInGroup(group_id,group_name);
					wf_usersList();
					
				} else 
				if(leselect == 0){
					toastr.error('User not removed, please retry!',{timeOut:15000})
				} else {
					internal_error();
				}

				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
	}
}

function wfGroupManagement(conf,id_msg_group) {

	if(conf == 'add'){
		/* Add new process */
		
		var req = '';
		
		var group_name = document.getElementById("nGForm_group_name").value;
		if(group_name){ req=req+'&group_name='+group_name; }

		
		if(group_name == ""){
			alert("Enter group name.");
			
		} else {
			var resurl='include/workflow.php?elemid=wf_group_management&conf='+conf+req;   
			var xhr = getXhr();
			xhr.onreadystatechange = function(){
				if(xhr.readyState == 4 ){
					leselect = xhr.responseText;       
					
					if(leselect == 1){
						toastr.success('Group successfully added',{timeOut:15000})
						wf_group_table();
						clearWf_newGroupForm();
					
					} else 
					if(leselect == 0){
						toastr.error('Group not added, please retry!',{timeOut:15000})
					} else {
						internal_error();
					}
					
					leselect = xhr.responseText;
				}
			};

			xhr.open("GET",resurl,true);
			xhr.send(null);
		}

	} else
	if(conf == 'show'){
		
		document.getElementById("wfGroupModalForm").reset();
		
		var resurl='include/workflow.php?elemid=show_wf_group&id_msg_group='+id_msg_group;   
		var xhr = getXhr();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;          
				var val = leselect.split('#');
				
				document.getElementById("wfGroupModal_group_name").value = val[0];
				
				document.getElementById('wfGroupModal_id_msg_group').value = id_msg_group;
				
				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
	
	
		/* Edit button */
		document.getElementById('wf_groupModalLabel').innerHTML = "Edit Group";
		document.getElementById('wf_GroupModalFooter').innerHTML ='<button type="button" class="btn btn-primary" onclick="wfGroupManagement(\'edit\',\''+id_msg_group+'\');"><i class="fa fa-save"></i></button>'
			+'<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i></button>';
		
	} else
	if(conf == 'edit'){	 
		
		/* Edit group */
		
		var req = '';
		
		var group_name = document.getElementById("wfGroupModal_group_name").value;
		if(group_name){ req=req+'&group_name='+group_name; }
		
		
		var resurl='include/workflow.php?elemid=wf_group_management&conf='+conf+'&id_msg_group='+id_msg_group+req;    
		var xhr = getXhr();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;         
				
				if(leselect == 1){
					toastr.success('Group successfully saved',{timeOut:15000})
					$("#modalWfGroup").modal("hide");
					wf_group_table();
					
				} else 
				if(leselect == 0){
					toastr.error('Unable to save group, please retry!',{timeOut:15000})
				} else {
					internal_error();
				}
				
				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
		
	} else
	if(conf == 'del'){
		
		/* Delete group */
		
		var resurl='include/workflow.php?elemid=delete_wf_group&id_msg_group='+id_msg_group;    
		var xhr = getXhr();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 ){
				leselect = xhr.responseText;         
				
				if(leselect == 1){
					toastr.success('Group successfully deleted',{timeOut:15000})
					wf_group_table();
				} else 
				if(leselect == 0){
					toastr.error('Group not deleted, please retry!',{timeOut:15000})
				} else {
					internal_error();
				}
				
				leselect = xhr.responseText;
			}
		};

		xhr.open("GET",resurl,true);
		xhr.send(null);
		
	} else {}
}

/* ----- END - WORKFLOW ----- */



function changeCopraPurchaseDays(days,ord_schedule_id) {
	document.getElementById("trace_days_link").innerHTML = '<a href="traceability_certificate/index.php?id='+ord_schedule_id+'&d='+days+'" target="_blank" id="issue_trace_certf" class="btn btn-warning">'+lg_log_trac_btn_show+'</a>';
}


function internal_error() { 
	swal({
		title: "Internal Server Error",
		text: "The server encountered something unexpected that didn't allow it to complete the request. We apologize. Please refresh the page",
		type: "warning",
		showCancelButton: false,
		confirmButtonColor: "#1ab394",
		confirmButtonText: "Refresh",
		closeOnConfirm: true
	}, function () {
		location.reload();
	});
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

	//This function disables buttons when needed
    function disableButtons(counter_max, counter_current) {
      $('#show-previous-image, #show-next-image')
        .show();
      if (counter_max === counter_current) {
        $('#show-next-image')
          .hide();
      } else if (counter_current === 1) {
        $('#show-previous-image')
          .hide();
      }
    }

	/**
     *
     * @param setIDs        Sets IDs when DOM is loaded. If using a PHP counter, set to false.
     * @param setClickAttr  Sets the attribute for the click handler.
     */

    function loadGallery(setIDs, setClickAttr) {
      let current_image,
        selector,
        counter = 0;

      $('#show-next-image, #show-previous-image')
        .click(function () {
          if ($(this)
            .attr('id') === 'show-previous-image') {
            current_image--;
          } else {
            current_image++;
          }

          selector = $('[data-image-id="' + current_image + '"]');
          updateGallery(selector);
        });

      function updateGallery(selector) {
        let $sel = selector;
        current_image = $sel.data('image-id');
        $('#image-gallery-title')
          .text($sel.data('title'));
        $('#image-gallery-image')
          .attr('src', $sel.data('image'));
        disableButtons(counter, $sel.data('image-id'));
      }

      if (setIDs == true) {
        $('[data-image-id]')
          .each(function () {
            counter++;
            $(this)
              .attr('data-image-id', counter);
          });
      }
      $(setClickAttr)
        .on('click', function () {
          updateGallery($(this));
        });
    }