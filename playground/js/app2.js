
// var URL = 'http://localhost/full/idiscover/';
var URL = '/idiscover/';
var blanc = "";
var niveau = 0  ;
var niveau_content = new Array();
var farmers_list = new Array();
niveau_content[0] = "0??0";


$('.menu li a').click(function() {
    $('ul li.active').removeClass('active');
    $(this).closest('li').addClass('active');
});


$('#sideBarBtnToggle').click( function(){
	volet_gauche_animated();
});

$('#sideBarClose').click( function(){
	$('#right-sidebar').removeClass('sidebar-open');
	$('#sideBarBtnToggle').removeClass("toggleOpen");
	$('#sideBarBtnToggle').addClass("hide");
});


$(window).resize(function() {
  if (($(this).width() >= 768)&&($(this).width() <= 1004)) {
    $("#wrapper").css("margin-top", 100 + "px");
  }
  else {
     $("#wrapper").css("margin-top", 50 + "px");
  }
});

$('#liveticker').slimScroll({ height: '90px', railOpacity: 0.9 });

function doBounce(element, times, distance, speed) {
	for(var i = 0; i < times; i++) {
       element.animate({marginTop: '-='+distance}, speed)
           .animate({marginTop: '+='+distance}, speed);
	}
}


function number_format (number, decimals, dec_point, thousands_sep) {
	number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
	var n = !isFinite(+number) ? 0 : +number,
	prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
	sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
	dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
	s = '',
	toFixedFix = function (n, prec) {
		var k = Math.pow(10, prec);
		return '' + Math.round(n * k) / k;
	};
	// Fix for IE parseFloat(0.55).toFixed(0) = 0;
	s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
	if (s[0].length > 3) {
		s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
	}
	if ((s[1] || '').length < prec) {
		s[1] = s[1] || '';
		s[1] += new Array(prec - s[1].length + 1).join('0');
	}
	return s.join(dec);
}


function closeAllpaysBtn() {
	$('#sideBarBtnToggle').addClass("hide");
	$('#right-sidebar').removeClass('sidebar-open');
	$('#mz').slideUp(400); $('#mz').addClass("hide");
	$('#mz1').slideUp(400); $('#mz1').addClass("hide");
	$('#tz').slideUp(400); $('#tz').addClass("hide");
	$('#tz1').slideUp(400); $('#tz1').addClass("hide");
	$('#sn').slideUp(400); $('#sn').addClass("hide");
	$('#sd').slideUp(400); $('#sd').addClass("hide");
	$('#sd1').slideUp(400); $('#sd1').addClass("hide");
	$('#ci').slideUp(400); $('#ci').addClass("hide");
	$('#ci1').slideUp(400); $('#ci1').addClass("hide");
	$('#kh').slideUp(400); $('#kh').addClass("hide");
}


var BING_KEY = 'AuhiCJHlGzhg93IqUH_oCpl_-ZUrIE6SPftlyGYUvr9Amx5nzA-WqGcPquyFZl4L';
var	cartodb_light = L.tileLayer('http://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}.png', {maxZoom: 19,minZoom: 1,  attribution: 'Positron'});

var bingLayer = L.tileLayer.bing(BING_KEY);

var map = new L.Map('map', {layers: [cartodb_light]});

var t = new L.terminator();
t.addTo(map);

var control = L.control.zoomBox({
    modal: true,
});

map.addControl(control);


function ticker(code,buyer) {

	var resurl='listeslies.php?elemid=ticker&code='+code+'&buyer='+buyer;
	var xhr = getXhr();

	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;   //alert(leselect);

			return  leselect;

			leselect = xhr.responseText;
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}


$(document).ready(function() {

	var mydata  = [];

	var resurl='listeslies.php?elemid=ticker&code=&culture=';
	var xhr = getXhr();

	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;

			var content = leselect;  //alert(leselect);
			var val = content.split('##');

			i=0;

			while (val[i]) { 
				var val1 = val[i].split('|');  
				mydata.push({date: val1[0], by: val1[1], to: val1[2], product: val1[3], contract: val1[4]});
				i +=1;
			}

            $("#table_list_1").jqGrid({
                data: mydata,
                datatype: "local",
                height: 55,
                autowidth: true,
                shrinkToFit: true,
                rowNum: 1000,
                rowList: [10, 20, 30],
                colNames: ['Date', 'By', 'To', 'Product', 'Contract'],
                colModel: [
                    {name: 'date', index: 'date', width: 70, align: "center", sorttype: "float"},
                    {name: 'by', index: 'by', width: 90, align: "center", sorttype: "float"},
                    {name: 'to', index: 'to', width: 100, align: "center", sorttype: "float"},
                    {name: 'product', index: 'product', width: 70, align: "center"},
                    {name: 'contract', index: 'contract', width: 80, align: "center", sorttype: "int"}
                ],
                pager: "#pager_list_1",
                viewrecords: true,
                caption: "",
                hidegrid: false
            });

            $(window).bind('resize', function () {
                var width = $('.jqGrid_wrapper').width();
                $('#table_list_1').setGridWidth(width);
            });

			leselect = xhr.responseText;
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);

	$("#video").simplePlayer();

});


$('.slick_demo_2').slick({
    infinite: true,
    slidesToShow: 3,
    slidesToScroll: 1,
    centerMode: true,
    responsive: [
        {
            breakpoint: 1024,
            settings: {
                slidesToShow: 3,
                slidesToScroll: 3,
                infinite: true,
				dots: true
            }
        },
        {
            breakpoint: 600,
			settings: {
                slidesToShow: 2,
                slidesToScroll: 2
            }
        },
        {
            breakpoint: 480,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1
			}
        }
    ]
});


function getXhr(){
	var xhr = null;
    if(window.XMLHttpRequest) {
		xhr = new XMLHttpRequest();

	} else if(window.ActiveXObject){
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


function is_int(value){
		  if((parseFloat(value) == parseInt(value)) && !isNaN(value)){
			  return true;
		  } else {
			  return false;
		  }
		}


function style_1(feature) {

	if (feature.couche == 'pays_contours') {
		return {
			color: "green",
			weight:1,
			fill: false,
			opacity: 1
		};
	}

 }


function style_frame(feature) {
       return {
			color: "red",
			weight:1,
			fill: true,
			opacity: 1
		};
}


function style(feature) {
	if (feature.properties.code == 'ch') {
		return {
			color: "red",
			weight:1,
			fill: true,
			opacity: 1
		};
	} else {
	    return {
			color: "green",
			weight:1,
			fill: true,
			opacity: 1
		};
	}
}


var SweetIcon = L.Icon.Label.extend({
		options: {
			iconUrl: 'images/icon.png',
			shadowUrl: null,
			iconSize: new L.Point(0, 0),
			iconAnchor: new L.Point(0, 0),
			labelAnchor: new L.Point(20, 5),
			wrapperAnchor: new L.Point(12, 13),
			labelClassName: 'sweet-deal-label',
			popupAnchor:  new L.Point(0, -16)
		}
	});

var SweetIcon1 = L.Icon.Label.extend({
		options: {
			iconUrl: 'images/icon.png',
			shadowUrl: null,
			iconSize: new L.Point(0, 0),
			iconAnchor: new L.Point(0, 0),
			labelAnchor: new L.Point(0, -20),
			wrapperAnchor: new L.Point(12, 13),
			labelClassName: 'sweet-deal-label',
			popupAnchor:  new L.Point(0, -16)
		}
	});

	SweetIcon2 = L.Icon.Label.extend({
		options: {
			iconUrl: 'images/icon.png',
			shadowUrl: null,
			iconSize: new L.Point(0, 0),
			iconAnchor: new L.Point(0, 0),
			labelAnchor: new L.Point(20, 5),
			wrapperAnchor: new L.Point(12, 13),
			labelClassName: 'sweet-deal-label2',
			popupAnchor:  new L.Point(0, -16)
		}
	});

var labels = new L.LayerGroup();


var capitale = L.geoJson(null, {
	pointToLayer: function (feature, latlng) {

		if (feature.properties.name_country == 'Switzerland'){
			var pulsingIcon = L.icon.pulse({iconSize:[10,10],color:'red'});
		} else {
			var pulsingIcon = L.icon.pulse({iconSize:[8,8],color:'green'});
		}

		mark = L.marker(latlng,{icon: new SweetIcon({iconUrl: 'images/icon.png',labelText: feature.properties.name_country})	,riseOnHover:false}).addTo(labels);

		return  L.marker(latlng,{icon: pulsingIcon});
	}

}).addTo(map);


var fleche = L.geoJson(null, {
	style: function (feature) {
		if (feature.properties.type == 'ligne'){
			return {
				color: "orange",
				dashArray: '3',
				weight: 2,
				fill: false,
				opacity: 1
			};

		} else {
			return {
				color: "orange",
				dashArray: '3',
				weight: 2,
				fill: false,
				opacity: 1
			};
		}

	}

}).addTo(map);


function onClick_country_0(e) {
    // alert('select product first !');
	 swal("", lg_product_first, "error");
}


function onClick_country_1(e) {
   pays(layer.feature.properties.code,culture);
}

var var_pays = '';
var culture = 0;

var arrow = new L.LayerGroup().addTo(map);
var arrHd = new L.LayerGroup().addTo(map);

// alert(json_pays.code);
var nbre_pays = json_pays.length-1;
var nbre_supplier = json_exporter.length;
var nbre_produit = 5;
var nbre_farmer = json_village.length;

var json_plantation_group =  _.pluck(json_plantation, 'properties');

var surface =  _.reduce(json_plantation_group, function(memo, num){return memo + num.area;},0);
var nbre_surface = number_format(surface, 2, '.', ' ');

var pays_couche0 = L.geoJson('', { });
var pays_couche = L.geoJson('', { style:style, onEachFeature: function (feature, layer) {
	popupOptions = {maxWidth: 250};

	var features = [];
	var val = String(layer.feature.properties.culture).split(',');
	i = 0;

	var content1 = "";

	if (layer.feature.properties.name_country == 'Switzerland'){
		var pulsingIcon = L.icon.pulse({iconSize:[10,10],color:'red'});
		var lang_pays = lg_pays_ch;
	} else {
		var pulsingIcon = L.icon.pulse({iconSize:[8,8],color:'green'});
	}

	if (feature.properties.code == 'ci'){
		var lang_pays = lg_pays_ci;
	} else
    if (feature.properties.code == 'sn'){
		var lang_pays = lg_pays_sn;
	} else
    if (feature.properties.code == 'sd'){
		var lang_pays = lg_pays_sd;
	} else
    if (feature.properties.code == 'kh'){
		var lang_pays = lg_pays_kh;
	} else
    if (feature.properties.code == 'mz'){
	   var lang_pays = lg_pays_mz;
	} else
    if (feature.properties.code == 'tz'){
	   var lang_pays = lg_pays_tz;
	}

	if (culture == 0) {
		mark = L.marker([layer.feature.properties.capitale_y, layer.feature.properties.capitale_x],{icon: new SweetIcon({iconUrl: 'images/icon.png',labelText:lang_pays})   ,riseOnHover:false}).addTo(labels).on('click', onClick_country_0);
		mark1 =  L.marker([layer.feature.properties.capitale_y, layer.feature.properties.capitale_x],{icon: pulsingIcon}).addTo(capitale).on('click', onClick_country_0);

		layer.on({
			click: function (e) {
				swal("", lg_product_first, "error");
			}
		});

	} else {
		mark = L.marker([layer.feature.properties.capitale_y, layer.feature.properties.capitale_x],{
			icon: new SweetIcon({iconUrl: 'images/icon.png',labelText:lang_pays})   ,riseOnHover:false
		}).addTo(labels).on('click', function(e) {
			niveau = 2;
			niveau_content[niveau] = layer.feature.properties.code+"??"+culture;
			pays(layer.feature.properties.code,culture);
		});

		mark1 =  L.marker([layer.feature.properties.capitale_y, layer.feature.properties.capitale_x],{
			icon: pulsingIcon
		}).addTo(capitale).on('click', function(e) {
			niveau = 2;
			niveau_content[niveau] = layer.feature.properties.code+"??"+culture;
			pays(layer.feature.properties.code,culture);
		});

		layer.on({
			click: function (e) {
				niveau = 2;
				niveau_content[niveau] = layer.feature.properties.code+"??"+culture;
				pays(layer.feature.properties.code,culture);
			}
		});
	}

    if (layer.feature.properties.name_country != 'Switzerland'){
		var arr = L.polyline([[layer.feature.properties.capitale_y, layer.feature.properties.capitale_x], [46.9609193365608, 7.34189030466942]], {color: "orange", weight: 2, dashArray: '3'}).addTo(arrow);
		var arrowHead = L.polylineDecorator(arr).addTo(arrHd);

		var arrowOffset = 25;
		var anim = window.setInterval(function() {
			arrowHead.setPatterns([{offset: arrowOffset+'%', repeat: 100,
				symbol: L.Symbol.arrowHead({pixelSize: 12,pathOptions: {color:"green", fillOpacity: 1, weight: 0}})
			}])

			if(++arrowOffset > 100)
			arrowOffset = 0;
		}, 100);
	}

   $("#loading").hide();
   $("#loading1").hide();
}}).addTo(map);


$('#acc').removeClass('hide');
$('#navi').addClass('hide');

rightPanel(culture,nbre_pays,nbre_produit,nbre_supplier,nbre_farmer,nbre_surface);

// ticker("","");

var region_frame = L.geoJson('', { style:style_frame}).addTo(map);
var pays_couche1 = L.geoJson('', { color: "green",weight:1,fill:false }).addTo(map);

pays_couche.addData(json_pays);
pays_couche0.addData(json_pays);

map.fitBounds(pays_couche.getBounds());


function refrechSelection() {

	culture = 0;
    clear_map_layer();

	nbre_pays = json_pays.length-1;
    nbre_supplier = json_exporter.length;
    nbre_produit = 5;
    nbre_farmer = json_village.length;

	var json_plantation_group =  _.pluck(json_plantation, 'properties');
    var surface =  _.reduce(json_plantation_group, function(memo, num){return memo + num.area;},0);
	var nbre_surface = number_format(surface, 2, '.', ' ');

	pays_couche.addData(json_pays);
	map.fitBounds(pays_couche.getBounds());

	$('#right-sidebar').removeClass('sidebar-open');
	$('#sideBarBtnToggle').removeClass("toggleOpen");
	$('#sideBarBtnToggle').addClass("hide");

	$('#acc').removeClass('hide');
	$('#navi').addClass('hide');

	rightPanel(culture,nbre_pays,nbre_produit,nbre_supplier,nbre_farmer,nbre_surface);

	ticker("","");
	bottomBoxes(0,0);

}

var exporter_couche0 = L.geoJson(null, { });

var exporter_couche = L.geoJson(null, {
    pointToLayer: function (feature, latlng) {
		var pulsingIcon = L.icon.pulse({iconSize:[8,8],color:'blue'});
		mark = L.marker(latlng,{icon: new SweetIcon({iconUrl: 'images/icon.png',labelText: feature.properties.initials})	,riseOnHover:false}).addTo(labels);
		return  L.marker(latlng,{icon: pulsingIcon});
	}
}).addTo(map);

var city_couche0 = L.geoJson(null, { });

var city_couche = L.geoJson(null, {
    pointToLayer: function (feature, latlng) {
		var pulsingIcon = L.icon.pulse({iconSize:[8,8],color:'blue'});
		mark = L.marker(latlng,{icon: new SweetIcon({iconUrl: 'images/icon.png',labelText: feature.properties.name_city})	,riseOnHover:false}).addTo(labels);
		return  L.marker(latlng,{icon: pulsingIcon});
	}
}).addTo(map);

var buyer_couche0 = L.geoJson(null, { });

var buyer_couche = L.geoJson(null, {
    pointToLayer: function (feature, latlng) {
		var pulsingIcon = L.icon.pulse({iconSize:[8,8],color:'blue'});
		mark = L.marker(latlng,{icon: new SweetIcon({iconUrl: 'images/icon.png',labelText: feature.properties.name_buyer})	,riseOnHover:false}).addTo(labels);
		return  L.marker(latlng,{icon: pulsingIcon});
	}
}).addTo(map);

var village_couche0 = L.geoJson(null, { });

var village_couche = L.geoJson(null, {
	pointToLayer: function (feature, latlng) {
		var pulsingIcon = L.icon.pulse({iconSize:[8,8],color:'blue'});
		mark = L.marker(latlng,{icon: new SweetIcon({iconUrl: 'images/icon.png',labelText: feature.properties.name_town})	,riseOnHover:false}).addTo(labels);
		return  L.marker(latlng,{icon: pulsingIcon});
	}
}).addTo(map);

var story_couche = L.geoJson(null, {
    pointToLayer: function (feature, latlng) {
		var pulsingIcon = L.icon.pulse({iconSize:[8,8],color:'blue'});
		mark = L.marker(latlng,{icon: new SweetIcon({iconUrl: 'images/icon.png',labelText: feature.properties.no_step})	,riseOnHover:false}).addTo(labels);
		return  L.marker(latlng,{icon: pulsingIcon});
	}
}).addTo(map);


var step_active = L.geoJson(null, {
    pointToLayer: function (feature, latlng) {
		var pulsingIcon = L.icon.pulse({iconSize:[15,15],color:'red'});
		mark = L.marker(latlng,{icon: new SweetIcon({iconUrl: 'images/icon.png',labelText: feature.properties.no_step})	,riseOnHover:false}).addTo(labels);
		return  L.marker(latlng,{icon: pulsingIcon});
	}
}).addTo(map);


function onEachFeature_plantation(feature, layer) {
	var popupContent = "<div style=\"max-width:400px; max-height: 200px\"><h5 style=\"border-bottom: 1px solid #eee; color:#ed1b2c\"><i class=\"fa fa-check-square fa-fw\" style=\"color:#ed1b2c\"></i><strong>&nbsp;&nbsp;Plantation details</strong></h5>"+blanc
		+"<div class=\"icon_desc\" style=\"margin-left:0px;display:block\"><span><i class=\"fa fa-arrows fa-fw\"></i> <strong>  Farmer : </strong>"+layer.feature.properties.name_farmer
		  +" </span><br><span><i class=\"fa fa-arrows fa-fw\"></i> <strong> Farmer group : </strong>"+layer.feature.properties.name_farmergroup
		  +" </span><br><span><i class=\"fa fa-arrows fa-fw\"></i> <strong> Farmer residence : </strong>"+layer.feature.properties.name_town
		  +" </span><br><span><i class=\"fa fa-arrows fa-fw\"></i> <strong> Product : </strong>"+layer.feature.properties.culture
		 +" </span><br><span><i class=\"fa fa-arrows fa-fw\"></i> <strong> Area (ha) : </strong>"+layer.feature.properties.area
		 +" </span><br><span><i class=\"fa fa-arrows fa-fw\"></i> <strong> Variety : </strong>"+layer.feature.properties.variety
		 +" </span><br><span><i class=\"fa fa-arrows fa-fw\"></i> <strong> Buyer : </strong>"+layer.feature.properties.name_buyer
	+" </span></div></div>";

	if (feature.properties) {
		layer.bindPopup(popupContent);
	}
}


var plantation_couche0 = L.geoJson('', {});
var plantation_couche = L.geoJson('', { color: '#e38217', weight: 2, opacity:0.5, onEachFeature: onEachFeature_plantation}).addTo(map);


plantation_couche0.addData(json_plantation);


function select_culture(cult) {
   $("#loading").show();
   $("#loading1").show();
   $('.navbar-collapse').removeClass("in");

	clear_map_layer();
	map.removeLayer(bingLayer);

	$('#bt_panel').removeClass('hide');
	$('#bt_images').addClass('hide');

	code = "";
	culture = cult;
	nbre_pays = 0;
	nbre_produit = 1;

	nbre_supplier = 0;
	pays_couche0.eachLayer(function (layer) {
        var val = String(layer.feature.properties.culture).split(',');
		var i = 0;
		while (val[i]) {
			if ((val[i] == String(culture)) || (val[i] == 0)) {
				pays_couche.addData(layer.feature);
				code += layer.feature.properties.code+'|';
				if (val[i] != 0) {
					nbre_pays += 1;
				}
			}

			i += 1;
		}
	});


	bottomBoxes(code,culture);

	var json_exporter_group =  _.pluck(json_exporter, 'properties');
	var json_exporter_group1 = _.where(json_exporter_group, {id_culture: culture});

	nbre_supplier = json_exporter_group1.length;

	var json_farmer_group =  _.pluck(json_village, 'properties');
	var json_farmer_group1 = _.where(json_farmer_group, {id_culture: culture});

	nbre_farmer = json_farmer_group1.length;

	var json_plantation_group =  _.pluck(json_plantation, 'properties');
	var surface =  _.reduce(json_plantation_group, function(memo, num){ if(num.id_culture == culture){return memo + num.area;}},0);
	var nbre_surface = number_format(surface, 2, '.', ' ');

    map.fitBounds(pays_couche.getBounds());

    $('#right-sidebar').removeClass('sidebar-open');
	$('#sideBarBtnToggle').removeClass("toggleOpen");
	$('#sideBarBtnToggle').addClass("hide");

	$('#acc').removeClass('hide');
	$('#navi').addClass('hide');

	rightPanel(culture,nbre_pays,nbre_produit,nbre_supplier,nbre_farmer,nbre_surface);
	 $("#loading").hide();
	 $("#loading1").hide();
}


function clear_map_layer() {
    pays_couche.clearLayers();
    pays_couche1.clearLayers();
    exporter_couche.clearLayers();
    city_couche.clearLayers();
    buyer_couche.clearLayers();
    village_couche.clearLayers();
    plantation_couche.clearLayers();
	capitale.clearLayers();
	fleche.clearLayers();
	labels.clearLayers();
	arrow.clearLayers();
	arrHd.clearLayers();
	region_frame.clearLayers();
	story_couche.clearLayers();
	step_active.clearLayers();

	highlight_red.clearLayers();
	plantation_farmer.clearLayers();

	$('#bt_panel').removeClass('hide');
	$('#bt_images').addClass('hide');
}


function Localtime(offset) {
	if (offset != null) {
		$('#time-cont-1').append('<div class="time"></div>');
		$('#time-cont-2').append('<div class="time"></div>');
		$('#time-cont-3').append('<div class="time"></div>');

		var options = {
			format:'<span class=\"dt\">%H:%M:%S</span>',
			timeNotation: '24h',
			am_pm: false,
			fontFamily: 'Verdana, Times New Roman',
			fontSize: '13px',
			foreground: 'white',
			// background: 'yellow',
			utc:true,
			utc_offset: offset
		}

		$('#time-cont-1 .time').jclock(options);
		$('#time-cont-2 .time').jclock(options);
		$('#time-cont-3 .time').jclock(options);
	}
}


function volet_gauche_animated() {
	if ($('#sideBarBtnToggle').hasClass("toggleOpen")) {
		document.getElementById('sideBarBtnToggle').innerHTML = '<i class="fa fa-caret-right"></i>';
		$('#right-sidebar').removeClass('fadeInLeftBig');
		$('#right-sidebar').addClass('fadeOutLeftBig');

		$('#sideBarBtnToggle').removeClass("toggleOpen");
		$('#sideBarBtnToggle').removeClass("fadeInLeftBig");
		// $('#sideBarBtnToggle').addClass("fadeOutLeftBig");

	} else {
		document.getElementById('sideBarBtnToggle').innerHTML = '<i class="fa fa-caret-left"></i>';
	    $('#right-sidebar').removeClass('fadeOutLeftBig');
		$('#right-sidebar').addClass('fadeInLeftBig');

		$('#sideBarBtnToggle').addClass("toggleOpen");
		$('#sideBarBtnToggle').removeClass("fadeOutLeftBig");
		$('#sideBarBtnToggle').addClass("fadeInLeftBig");
	}
}


var exporter_entite;
function onClick_exporter(e) {

	exporter_entite  = e ;
    clear_map_layer();
	niveau = 2;

	map.removeLayer(bingLayer);
	$('#bt_panel').removeClass('hide');
	$('#bt_images').addClass('hide');

    var latitude = e.latlng.lat ;
    var longitude = e.latlng.lng ;

	for (j in json_exporter) {
        if ((json_exporter[j].properties.coord_y == longitude) && (json_exporter[j].properties.coord_x == latitude)){

			var pulsingIcon = L.icon.pulse({iconSize:[10,10],color:'blue'});
			mark1 =  L.marker([json_exporter[j].properties.coord_x, json_exporter[j].properties.coord_y],{icon: pulsingIcon}).addTo(exporter_couche).on('click', onClick_exporter);
			mark = L.marker([json_exporter[j].properties.coord_x, json_exporter[j].properties.coord_y],{icon: new SweetIcon1({iconUrl: 'images/icon.png',labelText:json_exporter[j].properties.name_exporter})   ,riseOnHover:false}).addTo(labels).on('click', onClick_exporter);

			var k = 0;
			$('#right-sidebar').addClass('sidebar-open');
			$('#sideBarBtnToggle').removeClass('hide');

			document.getElementById('sideBarBtnToggle').innerHTML = '<i class="fa fa-caret-left"></i>';
			document.getElementById('sideBarBtnToggle').style.backgroundColor = '#4169e1';
			document.getElementById('sideBarSearchBox').style.backgroundColor = '#4169e1';

			$('#tab-2,#tab-3,#tab-4').removeClass('active');
			$('.tab2,.tab3,.tab4').removeClass('active');

			$('.tab1').addClass('active');
			$('#tab-1').addClass('active');



			volet_gauche_animated();
			// $('#right-sidebar').removeAttr('class').attr('class', 'animated');

		var content = '';


			content += '<div style="position: relative">';
			
			if(json_exporter[j].properties.e0_02logo != null && json_exporter[j].properties.e0_02logo != ''){
				content += '<img src="img/'+json_exporter[j].properties.e0_02logo+'" height="60" style="box-shadow:0 1px 6px 0 rgba(0,0,0,.3);position:absolute;right:20px;margin-top:180px;float:right"/>';
			}
			
			if(json_exporter[j].properties.e0_01photo != null && json_exporter[j].properties.e0_01photo != ''){
				content += '<img height="200px" src="img/'+json_exporter[j].properties.e0_01photo+'" style="width:100%;"/></div>';
			}
			
			content +='<div style="background-color: #4169e1;color: #fff;padding:10px 20px">'+blanc
					+'<h3>'+json_exporter[j].properties.name_exporter+' </h3>'+blanc
					+'<img src="img/'+json_exporter[j].properties.code_country+'.png" height="12" style=""/>&nbsp;<strong>'+json_exporter[j].properties.e0_05+'</strong>'+blanc
					+'<br><div style="display:inline-block"><div class="pull-left">Local Time : &nbsp;&nbsp; </div><div id="time-cont-1" class="pull-left"></div></div>'+blanc
				+'</div>'+blanc
				+'<div style="padding:10px 20px; border-bottom:1px solid #e7eaec;width:100%">';

		    if(json_exporter[j].properties.e1_01 != null && json_exporter[j].properties.e1_01 != ''){
				content += '<div class="panel panel-default" style="margin-bottom: 0px;padding: 5px 10px;width:100%"><h3 style="color:#ffa500"> '+json_exporter[j].properties.e1+'  </h3>'+blanc
					+json_exporter[j].properties.e1_01+'</div>'+blanc;
			}
			
			content += '<br><div class="panel panel-default" style="margin-bottom: 0px;padding: 5px 10px;width:100%">';
			
			if(json_exporter[j].properties.e2 != null && json_exporter[j].properties.e2 != ''){
				content += '<h3 style="color:#ffa500"><i class="fa fa-map-marker" aria-hidden="true"></i> &nbsp;'+json_exporter[j].properties.e2+'</h3>'+blanc
			}
			
			if(json_exporter[j].properties.e0_03name != null && json_exporter[j].properties.e0_03name != ''){
			   content += '&nbsp;&nbsp;&nbsp;'+json_exporter[j].properties.e0_03name;
			}
			
			if(json_exporter[j].properties.e2_01 != null && json_exporter[j].properties.e2_01 != ''){
			   content += '<br>&nbsp;&nbsp;&nbsp;'+json_exporter[j].properties.e2_01;
			}
			
			if(json_exporter[j].properties.e2_02 != null && json_exporter[j].properties.e2_02 != ''){
			   content += '<br>&nbsp;&nbsp;&nbsp;'+json_exporter[j].properties.e2_02;
			}
			
			if(json_exporter[j].properties.e2_03 != null && json_exporter[j].properties.e2_03 != ''){
			   content += '<br>&nbsp;&nbsp;&nbsp;'+json_exporter[j].properties.e2_03;
			}
			
			if(json_exporter[j].properties.e2_04 != null && json_exporter[j].properties.e2_04 != ''){
			   content += '<br>&nbsp;&nbsp;&nbsp;'+json_exporter[j].properties.e2_04;
			}

			if(json_exporter[j].properties.e2_05 != null && json_exporter[j].properties.e2_05 != ''){
			   content += '<br>&nbsp;&nbsp;&nbsp;'+json_exporter[j].properties.e2_05;
			}

			content += '</div>'+blanc
			+'<br><div class="panel panel-default" style="margin-bottom: 0px;padding: 5px 10px;width:100%">';
			
			if(json_exporter[j].properties.e3 != null && json_exporter[j].properties.e3 != ''){
				content += '<h3 style="color:#ffa500"><i class="fa fa-map-marker" aria-hidden="true"></i>&nbsp;'+json_exporter[j].properties.e3+'</h3>'+blanc
			}
			
			if(json_exporter[j].properties.e3_01 != null && json_exporter[j].properties.e3_01 != ''){
				content += '&nbsp;&nbsp;&nbsp;'+json_exporter[j].properties.e3_01+blanc;
			}
			
			if(json_exporter[j].properties.e3_02 != null && json_exporter[j].properties.e3_02 != ''){
				content += '<br>&nbsp;&nbsp;&nbsp;'+json_exporter[j].properties.e3_02+blanc;
			}
			
			if(json_exporter[j].properties.e3_03 != null && json_exporter[j].properties.e3_03 != ''){
				content += '<br>&nbsp;&nbsp;&nbsp;'+json_exporter[j].properties.e3_03;
			}

			if(json_exporter[j].properties.e3_04 != null && json_exporter[j].properties.e3_04 != ''){
			   content += '<br>&nbsp;&nbsp;&nbsp;'+json_exporter[j].properties.e3_04;
			}

			content += '</div>'+blanc
			+'<br><div class="panel panel-default" style="margin-bottom: 0px;padding: 5px 10px;width:100%">';
			
			if(json_exporter[j].properties.e4 != null && json_exporter[j].properties.e4 != ''){
				+'<h3 style="color:#ffa500"><i class="fa fa-map-marker" aria-hidden="true"></i>&nbsp;'+json_exporter[j].properties.e4+'</h3>'+blanc
			}
			
			if(json_exporter[j].properties.e4_01 != null && json_exporter[j].properties.e4_01 != ''){
			   content += '&nbsp;&nbsp;&nbsp;'+json_exporter[j].properties.e4_01;
			}
			
			if(json_exporter[j].properties.e4_02 != null && json_exporter[j].properties.e4_02 != ''){
			   content += '<br>&nbsp;&nbsp;&nbsp;'+json_exporter[j].properties.e4_02;
			}
			
			if(json_exporter[j].properties.e4_03 != null && json_exporter[j].properties.e4_03 != ''){
			   content += '<br>&nbsp;&nbsp;&nbsp;'+json_exporter[j].properties.e4_03;
			}
			
			if(json_exporter[j].properties.e4_04 != null && json_exporter[j].properties.e4_04 != ''){
			   content += '<br>&nbsp;&nbsp;&nbsp;'+json_exporter[j].properties.e4_04;
			}
			
			if(json_exporter[j].properties.e4_05 != null && json_exporter[j].properties.e4_05 != ''){
			   content += '<br>&nbsp;&nbsp;&nbsp;'+json_exporter[j].properties.e4_05;
			}
			
			if(json_exporter[j].properties.e4_06 != null && json_exporter[j].properties.e4_06 != ''){
			   content += '<br>&nbsp;&nbsp;&nbsp;'+json_exporter[j].properties.e4_06;
			}

			if(json_exporter[j].properties.e4_07 != null && json_exporter[j].properties.e4_07 != ''){
			   content += '<br>&nbsp;&nbsp;&nbsp;'+json_exporter[j].properties.e4_07;
			}

			if(json_exporter[j].properties.e4_08 != null && json_exporter[j].properties.e4_08 != ''){
			   content += '<br>&nbsp;&nbsp;&nbsp;'+json_exporter[j].properties.e4_08;
			}

			if(json_exporter[j].properties.e4_09 != null && json_exporter[j].properties.e4_09 != ''){
			   content += '<br>&nbsp;&nbsp;&nbsp;'+json_exporter[j].properties.e4_09;
			}

			content += '</div>'+blanc
			+'</div>';

	  document.getElementById('rightInfos').innerHTML = content;

			Localtime(json_exporter[j].properties.timezone);

			var arr = [];
			for (i in json_city) {   var features = [];
				if(( json_exporter[j].properties.code_country == json_city[i].properties.code_country) && ( json_exporter[j].properties.id_culture == json_city[i].properties.id_culture)){
					var pulsingIcon = L.icon.pulse({iconSize:[10,10],color:'orange'});

					mark1 =  L.marker([json_city[i].properties.coord_y, json_city[i].properties.coord_x],{icon: pulsingIcon}).addTo(city_couche).on('click', city);
				    mark = L.marker([json_city[i].properties.coord_y, json_city[i].properties.coord_x],{icon: new SweetIcon({iconUrl: 'images/icon.png',labelText:json_city[i].properties.name_city})   ,riseOnHover:false}).addTo(labels).on('click', city);

					k += 1;
				}
			}

		}
	}

	map.fitBounds(exporter_couche.getBounds().extend(city_couche.getBounds()));
}


function pays(code_pays,culture) {

	if (code_pays == 'ch') {
	  return;
	}
	$("#loading").show();
	$("#loading1").show();

	clear_map_layer();

	map.removeLayer(bingLayer);
	$('#bt_panel').removeClass('hide');
	$('#bt_images').addClass('hide');

	$('#right-sidebar').removeClass('sidebar-open');
	$('#sideBarBtnToggle').removeClass("toggleOpen");
	$('#sideBarBtnToggle').addClass("hide");

	var_pays = code_pays;
	if (code_pays == 0) {
	    culture = 0;
		pays_couche0.eachLayer(function (layer) {
			pays_couche.addData(layer.feature);
		});

		map.fitBounds(pays_couche.getBounds());
		return;
	}

	niveau = 1;
	bottomBoxes(code_pays,culture);

	pays_couche0.eachLayer(function (layer) {
	    if(layer.feature.properties.code ==  code_pays){
			pays_couche1.addData(layer.feature);
		}
	});

	for (j in json_exporter) {
		if(( code_pays == json_exporter[j].properties.code_country) && ( culture == json_exporter[j].properties.id_culture)){
			var pulsingIcon = L.icon.pulse({iconSize:[10,10],color:'blue'});
			mark1 =  L.marker([json_exporter[j].properties.coord_x, json_exporter[j].properties.coord_y],{icon: pulsingIcon}).addTo(exporter_couche).on('click', onClick_exporter);
			mark = L.marker([json_exporter[j].properties.coord_x, json_exporter[j].properties.coord_y],{icon: new SweetIcon1({iconUrl: 'images/icon.png',labelText:json_exporter[j].properties.name_exporter})   ,riseOnHover:false}).addTo(labels).on('click', onClick_exporter);
		}
	}

	map.fitBounds(pays_couche1.getBounds());
	$("#loading").hide();
	$("#loading1").hide();
	$('.nav-tabs li.active').removeClass('active');

	liste_stories(code_pays,culture);

	$('#navi').removeClass('hide');
	$('#acc').addClass('hide');

	$('#tab-1,#tab-2,#tab-3').removeClass('active');
	$('.tab1,.tab2,.tab3').removeClass('active');

	$('.tab4').addClass('active');
	$('#tab-4').addClass('active');



	var resurl='listeslies.php?elemid=stats&code='+code_pays;
	var xhr = getXhr();

	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;
			var val = leselect.split('##');

			document.getElementById('economyBox').innerHTML = '<ul class="stat-list">'+val[0]+'</ul>';
			document.getElementById('socialBox').innerHTML = val[1];
			document.getElementById('lifeBox').innerHTML = '<ul class="stat-list">'+val[2]+'</ul>';


			 $(".dial").knob();

			leselect = xhr.responseText;
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);

	$('#economyBox,#socialBox,#lifeBox').slimScroll({ height: '365px', railOpacity: 0.9 });

	document.getElementById('storyBox').innerHTML = '<div class="carousel slide" id="carousel1" style="margin:10px;">'+blanc
		+'<div class="carousel-inner">'+story_content+'</div>'+blanc
		+'<a data-slide="prev" href="#carousel1" class="left carousel-control" style="background-image:none; height:30px;top:50%;">'+blanc
		+'<span class="icon-prev"></span>'+blanc
		+'</a>'+blanc
		+'<a data-slide="next" href="#carousel1" class="right carousel-control"  style="background-image:none;height:30px;top:50%;">'+blanc
		+'<span class="icon-next"></span>'+blanc
		+'</a>'+blanc
	+'</div>';

	$('#storyBox').slimScroll({ height: '365px', railOpacity: 0.9 });

	ticker(code_pays,"");
}


function liste_stories(code_pays,culture) {
	var story_content = '';
	var link = '';

	for (i in json_stories) {
		if(( code_pays == json_stories[i].properties.code_country) && ( culture == json_stories[i].properties.id_culture)){

			if(i==0){ var active = 'active';} else { var active = '';}

			if( json_stories[i].properties.type == 'youtube' ){
				// link = ''+blanc
				           // +'<figure><iframe id="myFrame" onclick="javascript:alert(4);" style="width:100%;" height="250" frameborder="0" allowfullscreen="true" src="'+json_stories[i].properties.link+'" marginwidth="0" marginheight="0"></iframe></figure>'+blanc
						   // +'';
				// story_content += '<div class="item '+active+'">'+blanc
					// +'<div style="height:250px; overflow:hidden; width:100%;" >'+link+'</div>'+blanc
					// +'<div class="carCtn"><div>'+json_stories[i].properties.title+'</div><br/>'+blanc
					// +'</div>'+blanc
				// +'</div>';
				// alert(json_stories[i].properties.link);
				story_content += '<div class="item '+active+'">'+blanc
									 +'<div style="height:250px; overflow:hidden; width:100%;" data-video="'+json_stories[i].properties.link+'" class="video" id="video">'+blanc
									  +'<img src="https://i1.ytimg.com/vi/'+json_stories[i].properties.link+'/hqdefault.jpg" alt="Video.">'+blanc
									+'</div>'+blanc
                                    +'<div class="carCtn"><div>'+json_stories[i].properties.title+'</div><span id="btn_story"></span><br/>'+blanc
								+'</div>';


			} else {
				link = '<img alt="image" style="width:100%;" class="img-responsive" src="img/story/'+code_pays+'/'+culture+'/'+json_stories[i].properties.gid+'/title.jpg">';
				story_content += '<div class="item '+active+'">'+blanc
					+'<div style="height:250px; overflow:hidden; width:100%;">'+link+'</div>'+blanc
					+'<div class="carCtn"><div>'+json_stories[i].properties.title+'</div><br/>'+blanc
					  +'<button type="button" onclick="histoire('+json_stories[i].properties.gid+');" class="pull-right  btn btn-primary btn-xs animation_select" data-animation="bounceOut">'+lg_view_story+'</button>'+blanc
					+'</div>'+blanc
				+'</div>';
			}

		}
	}

	document.getElementById('storyBox').innerHTML = '<div class="carousel slide" id="carousel1" style="margin:10px;">'+blanc
		+'<div class="carousel-inner">'+story_content+'</div>'+blanc
		+'<a data-slide="prev" href="#carousel1" class="left carousel-control" style="background-image:none; height:30px;top:50%;">'+blanc
		  +'<span class="icon-prev" style="font-size: 80px;"></span>'+blanc
		+'</a>'+blanc
		+'<a data-slide="next" href="#carousel1" class="right carousel-control"  style="background-image:none;height:30px;top:50%;">'+blanc
		  +'<span class="icon-next" style="font-size: 80px;"></span>'+blanc
		+'</a>'+blanc
	+'</div>';

	$('#storyBox').slimScroll({ height: '365px', railOpacity: 0.9 });

	$('#carousel1').carousel({
		interval: 4000
	});


		$(".video").simplePlayer({

		autoplay: 0,
		loop: 0,
		autohide: 1,
		border: 0,
		wmode: 'opaque',
		enablejsapi: 1,
		modestbranding: 1,
		version: 3,
		hl: 'en_US',
		rel: 0,
		showinfo: 0,
		hd: 1,
		iv_load_policy: 3 // add origin

		});

		    $("#play").click(function() {
				$('#carousel1').carousel('pause');
				document.getElementById('btn_story').innerHTML = '<button onclick="pays(\''+var_pays+'\','+culture+');" type="button" class="pull-right  btn btn-primary btn-xs" data-dismiss="modal">Close</button>';
			});
}

function bottomBoxes(code_pays,culture) {

	var resurl='listeslies.php?elemid=bottomBoxes&code='+code_pays+'&cult='+culture;
	var xhr = getXhr();

	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;
			var val = leselect.split('#');

			if(val[2]==""){var box3 = 0;} else {var box3 = val[2];}

			document.getElementById('box1').innerHTML = val[0];
			document.getElementById('box2').innerHTML = val[1];
			document.getElementById('box3').innerHTML = box3;
			document.getElementById('box4').innerHTML = val[3];

			document.getElementById('box1_title').innerHTML = val[4];
			document.getElementById('box2_title').innerHTML = val[5];
			document.getElementById('box3_title').innerHTML = val[6];
			document.getElementById('box4_title').innerHTML = val[7];

			leselect = xhr.responseText;
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}


var plantation_farmer = L.geoJson('', {});

function style_red(feature) {
	return {
		stroke: true,
		fill: false,
		fillColor: "red",
		fillOpacity: 0.2,
		color: 'red',
		opacity: 1,
		weight: 3
	};
}

var highlight_red = L.geoJson('', { style:style_red}).addTo(map);

function info_feature(code_farmer) {
	highlight_red.clearLayers();
    var k=0;
    plantation_couche0.eachLayer(function (layer) {
		if(layer.feature.properties.code_farmer == code_farmer){
			highlight_red.addData(layer.feature);
			k += 1;
		}
	});

	if (k != 0){
		map.fitBounds(highlight_red.getBounds());
		highlight_red.bringToFront();
	} else {
		onClick_village(village_entite);
		// alert('No mapping plantation !!');
	}

}


function highlight_feature(code_farmer) {
	highlight_red.clearLayers();
    var k=0;
    plantation_couche0.eachLayer(function (layer) {
		if(layer.feature.properties.code_farmer == code_farmer){
			highlight_red.addData(layer.feature);

			var popupContent = "<div style=\"max-width:400px; max-height: 200px\"><h5 style=\"border-bottom: 1px solid #eee; color:#ed1b2c\"><i class=\"fa fa-check-square fa-fw\" style=\"color:#ed1b2c\"></i><strong>&nbsp;&nbsp;Plantation details</strong></h5>"+blanc
				+"<div class=\"icon_desc\" style=\"margin-left:0px;display:block\"><span><i class=\"fa fa-arrows fa-fw\"></i> <strong>  Farmer : </strong>"+layer.feature.properties.name_farmer
				  +" </span><br><span><i class=\"fa fa-arrows fa-fw\"></i> <strong> Farmer group : </strong>"+layer.feature.properties.name_farmergroup
				  +" </span><br><span><i class=\"fa fa-arrows fa-fw\"></i> <strong> Farmer residence : </strong>"+layer.feature.properties.name_town
				  +" </span><br><span><i class=\"fa fa-arrows fa-fw\"></i> <strong> Product : </strong>"+layer.feature.properties.culture
				 +" </span><br><span><i class=\"fa fa-arrows fa-fw\"></i> <strong> Area (ha) : </strong>"+layer.feature.properties.area
				 +" </span><br><span><i class=\"fa fa-arrows fa-fw\"></i> <strong> Variety : </strong>"+layer.feature.properties.variety
				 +" </span><br><span><i class=\"fa fa-arrows fa-fw\"></i> <strong> Buyer : </strong>"+layer.feature.properties.name_buyer
			+" </span><br><br><div style=\"text-align:center\"> <a href=\"javascript:details('"+layer.feature.properties.code_farmer+"')\" ><span> </span></a></div></div></div>";

			highlight_red.bindPopup(popupContent).openPopup();
			// layer.bindPopup('feature.properties.popupContent').openPopup();
			k += 1;
		}
	});

	if (k != 0){
		// highlight_red.openPopup();
		map.fitBounds(highlight_red.getBounds());
		highlight_red.bringToFront();
	} else {
		onClick_village(village_entite);
		// alert('No mapping plantation !!');
	}
}



function show_plantation(code) {
	
	clear_map_layer();
				plantation_farmer.clearLayers();

			plantation_couche0.eachLayer(function (layer) {
			    if(layer.feature.properties.id_town == code){
			        plantation_couche.addData(layer.feature);
			        plantation_farmer.addData(layer.feature);

				}
			});
			
			
			map.fitBounds(plantation_couche.getBounds());
}

var village_entite;
function onClick_village(e) {

    clear_map_layer();
	farmers_list = [];

	niveau = 5;
	village_entite = e;

	$('#bt_images').removeClass('hide');
	$('#bt_panel').addClass('hide');

    var latitude = e.latlng.lat ;
    var longitude = e.latlng.lng ;
	var t = 0;
	var a = 1;

	for (i in json_village) {
		if ((json_village[i].properties.y == latitude) && (json_village[i].properties.x == longitude)){
			if(t == 0){
                var pulsingIcon = L.icon.pulse({iconSize:[10,10],color:'green'});
				mark1 =  L.marker([json_village[i].properties.y, json_village[i].properties.x],{icon: pulsingIcon}).addTo(buyer_couche);
				mark = L.marker([json_village[i].properties.y, json_village[i].properties.x],{icon: new SweetIcon1({iconUrl: 'images/icon.png',labelText:json_village[i].properties.name_town})   ,riseOnHover:false}).addTo(labels); ;
		        t = 1;
			}
		    var total_ha = 0;
		    var nom_cult;
		    var k=0;


			plantation_farmer.clearLayers();

			plantation_couche0.eachLayer(function (layer) {
			    if(layer.feature.properties.code_farmer == json_village[i].properties.code_farmer){
			        // plantation_couche.addData(layer.feature);
			        // plantation_farmer.addData(layer.feature);

                    total_ha += layer.feature.properties.area;
                    nom_cult = 	layer.feature.properties.culture;
                    k+=1;
				}
			});

			if(k != 0){
				// var info_bulle = '<a class="" href="#" onclick="highlight_feature(\''+json_village[i].properties.code_farmer+'\');"><i class="fa fa-info-circle"></i></a>';
				var mapped_plantation = '<a class="" href="#" onclick="highlight_feature(\''+json_village[i].properties.code_farmer+'\');"><i class="fa fa-map-marker"></i></a>';
				var html_show_plant = '<a href="javascript:show_plantation('+json_village[i].properties.id_town+')" class="pull-right" style="color:#fff">Show all plantations</a>';
				
			} else {
				// var info_bulle = '';
				var mapped_plantation = '';
				var html_show_plant = '';
			}

			$('#right-sidebar').addClass('sidebar-open');
			$('#sideBarBtnToggle').removeClass("hide");
			document.getElementById('sideBarBtnToggle').innerHTML = '<i class="fa fa-caret-left"></i>';
			document.getElementById('sideBarBtnToggle').style.backgroundColor = '#20b2aa';
			document.getElementById('sideBarSearchBox').style.backgroundColor = '#20b2aa';

			volet_gauche_animated();


		    farmers_ct = '<div class="panel-group" id="accordion" style="margin-bottom:5px;">'+blanc
                +'<div class="panel panel-default">'+blanc
                    +'<div class="panel-heading">'+blanc
                        +'<table style="width:100%" class="panel-title"><tr>'+blanc
						  +'<td style="width:25%">'+json_village[i].properties.name_farmer.substr(0, 5)+'...</td>'+blanc
						  +'<td style="width:35%">'+json_village[i].properties.number_plantation+' Plantation(s)</td>'+blanc
						  +'<td style="width:30%">'+number_format(total_ha, 2, '.', ' ')+' Ha</td>'+blanc
						  // +'<td style="padding:0 5px">'+info_bulle+'</td>'+blanc
						  +'<td>'+mapped_plantation+'</td>'+blanc
						  +'<td><a data-toggle="collapse" data-parent="#accordion" class="collapse-link pull-right" href="#farmer'+a+'"><i class="fa fa-chevron-down"></i></a></td></tr>'+blanc
						+'</table></h5>'+blanc
                    +'</div>'+blanc
                    +'<div id="farmer'+a+'" class="panel-collapse collapse">'+blanc
                        +'<div class="panel-body">'+blanc
						  +'<table class="table table-striped">'+blanc
						     +'<tr><td>Crop</td><td>'+nom_cult+'</td></tr>'+blanc
						     +'<tr><td>Other Crops</td><td></td></tr>'+blanc
						     +'<tr><td>Other Source Income</td><td></td></tr>'+blanc
						     +'<tr><td>No Employees</td><td></td></tr>'+blanc
						     +'<tr><td>Last Audit ProfairTrade</td><td></td></tr>'+blanc

						     +'<tr><td>Fields Surface Total</td><td></td></tr>'+blanc
						     +'<tr><td>Plantation Quality</td><td></td></tr>'+blanc
						     +'<tr><td>Professional Capacity</td><td></td></tr>'+blanc
						     +'<tr><td>Professional Experience</td><td></td></tr>'+blanc
						     +'<tr><td>Village</td><td>'+json_village[i].properties.name_town+'</td></tr>'+blanc
						     +'<tr><td>Farmer Group</td><td>'+json_village[i].properties.name_farmergroup+'</td></tr>'+blanc
						     +'<tr><td>Birthday</td><td>'+json_village[i].properties.birthday+'</td></tr>'+blanc
						     +'<tr><td>Gender</td><td>'+json_village[i].properties.sex+'</td></tr>'+blanc
						     +'<tr><td>Contacts</td><td>'+json_village[i].properties.contact+'</td></tr>'+blanc
						     +'<tr><td>Civil State</td><td></td></tr>'+blanc
						     +'<tr><td>Children</td><td>'+json_village[i].properties.number_child+'</td></tr>'+blanc
						     +'<tr><td>Children in school</td><td></td></tr>'+blanc
						  +'</table>'+blanc
						+'</div>'+blanc
                    +'</div>'+blanc
                +'</div>'+blanc
			+'</div>';

			farmers_list.push(farmers_ct);

			a +=1;
			
			var content = '';
			
			content += '<div style="position: relative">';
			
			if(json_village[i].properties.e0_01photo != null && json_village[i].properties.e0_01photo != ''){
				content += '<img height="200px" src="img/'+json_village[i].properties.e0_01photo+'" style="width:100%;"/></div>';
			}
			
   			content += '<div style="background-color: #20b2aa;color: #fff;padding:10px 20px">'+blanc
				+'<h3>'+json_village[i].properties.name_town+' </h3>'+blanc
				   +'<img src="img/ci.png" height="12" style=""/>&nbsp;<strong>Ivory Coast Farmer Village</strong>'+blanc
				+'<br><div style="display:inline-block"><div class="pull-left">Local Time : &nbsp;&nbsp; </div><div id="time-cont-3" class="pull-left"></div></div>'+html_show_plant
			
				+'</div>'+blanc

				+'<div class="pull-left" style="padding:10px 20px; border-bottom:1px solid #e7eaec; width:100%">'+blanc

				+'<br><div class="panel panel-default" style="margin-bottom: 10px;padding: 5px 10px;width:100%">'+blanc
				
				+'<h3 style="color:#ffa500"><i class="fa fa-map-marker" aria-hidden="true"></i> &nbsp;Farmers List</h3>'+blanc
				+'</div>'+farmers_list.join("")+blanc
			+'</div>';
			
			document.getElementById('rightInfos').innerHTML = content;
			
			Localtime(json_village[i].properties.timezone);
		}
	}

	// map.fitBounds(plantation_couche.getBounds().extend(buyer_couche.getBounds()));
	map.fitBounds(buyer_couche.getBounds());
	
	map.setZoom(12);
}

var buyer_entite;
function onClick_buyer(e) {

	clear_map_layer();
	var latitude = e.latlng.lat ;
	var longitude = e.latlng.lng ;


	$('#bt_panel').removeClass('hide');
	$('#bt_images').addClass('hide');

	niveau = 4;
	buyer_entite = e;


	for (i in json_buyer) {
		if ((json_buyer[i].properties.coord_y == latitude) && (json_buyer[i].properties.coord_x == longitude)){
            var pulsingIcon = L.icon.pulse({iconSize:[10,10],color:'yellow'});
			mark1 =  L.marker([json_buyer[i].properties.coord_y, json_buyer[i].properties.coord_x],{icon: pulsingIcon}).addTo(buyer_couche);
			mark = L.marker([json_buyer[i].properties.coord_y, json_buyer[i].properties.coord_x],{icon: new SweetIcon1({iconUrl: 'images/icon.png',labelText:json_buyer[i].properties.initials})   ,riseOnHover:false}).addTo(labels);

			var json_village_group1 =  _.pluck(json_village, 'properties');
			var json_village_group12 = _.where(json_village_group1, {id_buyer: json_buyer[i].properties.id_buyer});
			var json_village_group2 =  _.uniq(json_village_group12, 'id_town');
			// _.uniq([1, 2, 1, 4, 1, 3]) ;

			$('#right-sidebar').addClass('sidebar-open');
			$('#sideBarBtnToggle').removeClass("hide");
			document.getElementById('sideBarBtnToggle').innerHTML = '<i class="fa fa-caret-left"></i>';
			document.getElementById('sideBarBtnToggle').style.backgroundColor = '#dae456';
			document.getElementById('sideBarSearchBox').style.backgroundColor = '#dae456';

			$('#tab-1,#tab-2,#tab-4').removeClass('active');
			$('.tab1,.tab2,.tab4').removeClass('active');

			$('.tab3').addClass('active');
			$('#tab-3').addClass('active');

			volet_gauche_animated();

			var content = '';


			content += '<div style="position: relative">';
			
			if(json_buyer[i].properties.e0_02logo != null && json_buyer[i].properties.e0_02logo != ''){
				content += '<img src="img/'+json_buyer[i].properties.e0_02logo+'" height="60" style="box-shadow:0 1px 6px 0 rgba(0,0,0,.3);position:absolute;right:20px;margin-top:180px;float:right"/>';
			}
			
			if(json_buyer[i].properties.e0_01photo != null && json_buyer[i].properties.e0_01photo != ''){
				content += '<img height="200px" src="img/'+json_buyer[i].properties.e0_01photo+'" style="width:100%;"/></div>';
			}
			
			content +='<div style="background-color: #dae456;color: #fff;padding:10px 20px">'+blanc
					+'<h3>'+json_buyer[i].properties.name_buyer+' </h3>'+blanc
					+'<img src="img/'+json_buyer[i].properties.code_country+'.png" height="12" style=""/>&nbsp;<strong>'+json_buyer[i].properties.e0_05+'</strong>'+blanc
					+'<br><div style="display:inline-block"><div class="pull-left">Local Time : &nbsp;&nbsp; </div><div id="time-cont-1" class="pull-left"></div></div>'+blanc
				+'</div>'+blanc
			+'<div style="padding:10px 20px; border-bottom:1px solid #e7eaec;width:100%">';
			
			if(json_buyer[i].properties.e1_01 != null && json_buyer[i].properties.e1_01 != ''){
				content += '<div class="panel panel-default" style="margin-bottom: 0px;padding: 5px 10px;width:100%"><h3 style="color:#ffa500"> '+json_buyer[i].properties.e1+'  </h3>'+blanc
				  +json_buyer[i].properties.e1_01+'</div>'+blanc;
			}

			content += '<br><div class="panel panel-default" style="margin-bottom: 0px;padding: 5px 10px;width:100%">';
			
			if(json_buyer[i].properties.e2 != null && json_buyer[i].properties.e2 != ''){
				content += '<h3 style="color:#ffa500"><i class="fa fa-map-marker" aria-hidden="true"></i> &nbsp;'+json_buyer[i].properties.e2+'</h3>';
			}
			
			if(json_buyer[i].properties.e0_03name != null && json_buyer[i].properties.e0_03name != ''){
				content += '&nbsp;&nbsp;&nbsp;'+json_buyer[i].properties.e0_03name;
			}
			
			if(json_buyer[i].properties.e2_01 != null && json_buyer[i].properties.e2_01 != ''){
				content += '<br>&nbsp;&nbsp;&nbsp;'+json_buyer[i].properties.e2_01;
			}
			
			if(json_buyer[i].properties.e2_02 != null && json_buyer[i].properties.e2_02 != ''){
				content += '<br>&nbsp;&nbsp;&nbsp;'+json_buyer[i].properties.e2_02;
			}
			
			if(json_buyer[i].properties.e2_03 != null && json_buyer[i].properties.e2_03 != ''){
				content += '<br>&nbsp;&nbsp;&nbsp;'+json_buyer[i].properties.e2_03;
			}
			
			if(json_buyer[i].properties.e2_04 != null && json_buyer[i].properties.e2_04 != ''){
				content += '<br>&nbsp;&nbsp;&nbsp;'+json_buyer[i].properties.e2_04;
			}
			
			if(json_buyer[i].properties.e2_05 != null && json_buyer[i].properties.e2_05 != ''){
				content += '<br>&nbsp;&nbsp;&nbsp;'+json_buyer[i].properties.e2_05;
			}

			content += '</div>'+blanc
			+'<br><div class="panel panel-default" style="margin-bottom: 0px;padding: 5px 10px;width:100%">';
		
			if(json_buyer[i].properties.e3 != null && json_buyer[i].properties.e3 != ''){
				content += '<h3 style="color:#ffa500"><i class="fa fa-map-marker" aria-hidden="true"></i>&nbsp;'+json_buyer[i].properties.e3+'</h3>';
			}
			
			if(json_buyer[i].properties.e3_01 != null && json_buyer[i].properties.e3_01 != ''){
			   content += '&nbsp;&nbsp;&nbsp;'+json_buyer[i].properties.e3_01;
			}
		
			if(json_buyer[i].properties.e3_02 != null && json_buyer[i].properties.e3_02 != ''){
				content += '<br>&nbsp;&nbsp;&nbsp;'+json_buyer[i].properties.e3_02;
			}
				
			if(json_buyer[i].properties.e3_03 != null && json_buyer[i].properties.e3_03 != ''){
				content += '<br>&nbsp;&nbsp;&nbsp;'+json_buyer[i].properties.e3_03;
			}
			
			if(json_buyer[i].properties.e3_04 != null && json_buyer[i].properties.e3_04 != ''){
				content += '<br>&nbsp;&nbsp;&nbsp;'+json_buyer[i].properties.e3_04;
			}

			content += '</div>'+blanc
			+'<br><div class="panel panel-default" style="margin-bottom: 0px;padding: 5px 10px;width:100%">';
			
			if(json_buyer[i].properties.e4 != null && json_buyer[i].properties.e4 != ''){
				content += '<h3 style="color:#ffa500"><i class="fa fa-map-marker" aria-hidden="true"></i>&nbsp;'+json_buyer[i].properties.e4+'</h3>';
			}
			
			if(json_buyer[i].properties.e4_01 != null && json_buyer[i].properties.e4_01 != ''){
				content += '&nbsp;&nbsp;&nbsp;'+json_buyer[i].properties.e4_01;
			}
			
			if(json_buyer[i].properties.e4_02 != null && json_buyer[i].properties.e4_02 != ''){
				content += '<br>&nbsp;&nbsp;&nbsp;'+json_buyer[i].properties.e4_02;
			}
		
			if(json_buyer[i].properties.e4_03 != null && json_buyer[i].properties.e4_03 != ''){
				content += '<br>&nbsp;&nbsp;&nbsp;'+json_buyer[i].properties.e4_03;
			}
		
			if(json_buyer[i].properties.e4_04 != null && json_buyer[i].properties.e4_04 != ''){
				content += '<br>&nbsp;&nbsp;&nbsp;'+json_buyer[i].properties.e4_04;
			}
		
			if(json_buyer[i].properties.e4_05 != null && json_buyer[i].properties.e4_05 != ''){
				content += '<br>&nbsp;&nbsp;&nbsp;'+json_buyer[i].properties.e4_05;
			}
		
			if(json_buyer[i].properties.e4_06 != null && json_buyer[i].properties.e4_06 != ''){
				content += '<br>&nbsp;&nbsp;&nbsp;'+json_buyer[i].properties.e4_06;
			}

			if(json_buyer[i].properties.e4_07 != null && json_buyer[i].properties.e4_07 != ''){
				content += '<br>&nbsp;&nbsp;&nbsp;'+json_buyer[i].properties.e4_07;
			}

			if(json_buyer[i].properties.e4_08 != null && json_buyer[i].properties.e4_08 != ''){
				content += '<br>&nbsp;&nbsp;&nbsp;'+json_buyer[i].properties.e4_08;
			}

			if(json_buyer[i].properties.e4_09 != null && json_buyer[i].properties.e4_09 != ''){
				content += '<br>&nbsp;&nbsp;&nbsp;'+json_buyer[i].properties.e4_09;
			}

			content += '</div>'+blanc
			+'</div>';

		document.getElementById('rightInfos').innerHTML = content;

			Localtime(json_buyer[i].properties.timezone);

			var k = 0;
			var arr = [];
			for (j in json_village_group2) {
				var pulsingIcon = L.icon.pulse({iconSize:[8,8],color:'green'});
				mark1 = L.marker([json_village_group2[j].y, json_village_group2[j].x],{icon: pulsingIcon}).addTo(buyer_couche).on('click', onClick_village);
				mark = L.marker([json_village_group2[j].y, json_village_group2[j].x],{icon: new SweetIcon1({iconUrl: 'images/icon.png',labelText:json_village_group2[j].name_town})   ,riseOnHover:false}).addTo(labels).on('click', onClick_village);

				var start = { x: json_village_group2[j].x , y: json_village_group2[j].y };
				var end = { x: json_buyer[i].properties.coord_x, y: json_buyer[i].properties.coord_y };
				arr[k] = L.polyline([[json_village_group2[j].y, json_village_group2[j].x], [json_buyer[i].properties.coord_y, json_buyer[i].properties.coord_x]], {color: "orange", weight: 2,dashArray: '3'}).addTo(arrow);

				var arrowHead = L.polylineDecorator(arr).addTo(arrHd);
				var arrowOffset = 25;
				var anim = window.setInterval(function() {
					arrowHead.setPatterns([{
						offset: arrowOffset+'%', repeat: 200,
						symbol: L.Symbol.arrowHead({pixelSize: 12,pathOptions: {color:"green", fillOpacity: 1, weight: 0}})
					}])

					if(++arrowOffset > 100)
					arrowOffset = 0;
				}, 200);
				k += 1;
			}

        }
    }

    map.fitBounds(buyer_couche.getBounds());
}

var city_entite;
function city(e) {

	clear_map_layer();
	niveau = 3;

	map.addLayer(bingLayer);
	$('#bt_panel').removeClass('hide');
	$('#bt_images').addClass('hide');

	city_entite = e;
	var latitude = e.latlng.lat ;
	var longitude = e.latlng.lng ;

    for (i in json_city) {
		if ((json_city[i].properties.coord_y == latitude) && (json_city[i].properties.coord_x == longitude)){
			var pulsingIcon = L.icon.pulse({iconSize:[10,10],color:'orange'});

			var k = 0;
			var t = 0;
		    var arr = [];

            var x1 = [];
            var y1 = [];

			for (j in json_buyer) {
				if(json_buyer[j].properties.id_city == json_city[i].properties.id_city){
					var features = [];

					for (x in json_exporter) {
						if((json_exporter[x].properties.id_exporter == json_buyer[j].properties.id_exporter) && (t == 0)) {
							var pulsingIcon = L.icon.pulse({iconSize:[10,10],color:'blue'});

							var x_exporter = json_exporter[x].properties.coord_x;
							var y_exporter = json_exporter[x].properties.coord_y;

							mark1 =  L.marker([json_exporter[x].properties.coord_x, json_exporter[x].properties.coord_y],{icon: pulsingIcon}).addTo(city_couche).on('click', onClick_country_1);
							mark = L.marker([json_exporter[x].properties.coord_x, json_exporter[x].properties.coord_y],{icon: new SweetIcon1({iconUrl: 'images/icon.png',labelText:json_exporter[x].properties.initials})   ,riseOnHover:false}).addTo(labels).on('click', onClick_country_1);

							t = 1;
						}
					}

					x1.push(json_buyer[j].properties.coord_x);
					y1.push(json_buyer[j].properties.coord_y);

					var pulsingIcon = L.icon.pulse({iconSize:[10,10],color:'yellow'});
					mark1 =  L.marker([json_buyer[j].properties.coord_y, json_buyer[j].properties.coord_x],{icon: pulsingIcon}).addTo(city_couche).on('click', onClick_buyer);
					mark = L.marker([json_buyer[j].properties.coord_y, json_buyer[j].properties.coord_x],{icon: new SweetIcon1({iconUrl: 'images/icon.png',labelText:json_buyer[j].properties.initials})   ,riseOnHover:false}).addTo(labels).on('click', onClick_buyer);

					var start = { x: x_exporter , y: y_exporter };
					var end = { x: json_buyer[j].properties.coord_x, y: json_buyer[j].properties.coord_y };
					arr[k] = L.polyline([[json_buyer[j].properties.coord_y, json_buyer[j].properties.coord_x], [x_exporter, y_exporter]], {color: "orange", weight: 2,dashArray: '3'}).addTo(arrow);

					var arrowHead = L.polylineDecorator(arr).addTo(arrHd);
					var arrowOffset = 25;
					var anim = window.setInterval(function() {
						arrowHead.setPatterns([{
							offset: arrowOffset+'%', repeat: 200,
							symbol: L.Symbol.arrowHead({pixelSize: 12,pathOptions: {color:"green", fillOpacity: 1, weight: 0}})
						}])

						if(++arrowOffset > 100)
						arrowOffset = 0;
					}, 100);

					k += 1;

				}
			}

			var bounds = [[Math.min.apply(null, y1)-0.01 , Math.min.apply(null, x1)-0.01], [Math.max.apply(null, y1)+0.01 , Math.max.apply(null, x1)+0.01]];
			L.rectangle(bounds, {color: "#ff7800",fill:false, weight: 1}).addTo(region_frame);
			mark = L.marker([Math.min.apply(null, y1)-0.02, Math.min.apply(null, x1)-0.02],{icon: new SweetIcon2({iconUrl: 'images/icon.png',labelText:json_city[i].properties.name_city})   ,riseOnHover:false}).addTo(labels);

		}
	}

    map.fitBounds(city_couche.getBounds());
// volet_gauche_animated();
	$('#right-sidebar').removeClass('sidebar-open');
	$('#sideBarBtnToggle').removeClass("toggleOpen");
	$('#sideBarBtnToggle').addClass("hide");

}


function sitelang() {
	var lien = document.getElementById('langue').value;
	if (lien) {
		window.location = URL + lien;
	}

	return false;
}


function rightPanel(cult,pays,produit,supplier,farmer,surface) {
	var resurl='listeslies.php?elemid=rightPanel&culture='+cult+'&nbre_pays='+pays+'&nbre_produit='+produit+'&nbre_supplier='+supplier+'&nbre_farmer='+farmer+'&nbre_surface='+surface;
	var xhr = getXhr();

	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;

			document.getElementById('acc').innerHTML = leselect;

			leselect = xhr.responseText;
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}


L.easyButton('fa-reply', function () {
	var stateObj = { foo: "index" };
    history.pushState(stateObj, "page 2", "index.php");

	if(culture != 0){

		if(niveau == 0){
			$('ul li.active').removeClass('active');
			$('.menu_h').addClass('active');
			refrechSelection();

		} else
		if(niveau == 1){
			select_culture(culture);
			niveau -=1;

		} else
		if(niveau == 2){
			val = niveau_content[niveau].split('??');
			pays(val[0],val[1]);
		} else
		if(niveau == 3){
			onClick_exporter(exporter_entite);

		}  else
		if(niveau == 4){
			city(city_entite);

		}  else
		if(niveau == 5){
			onClick_buyer(buyer_entite);

		}

		volet_gauche_animated();
	}

}, 'Previous').addTo(map);


function voletgauche(param,valeur) {

    $('#right-sidebar').addClass('sidebar-open');
	$('#sideBarBtnToggle').removeClass("hide");

	document.getElementById('sideBarBtnToggle').innerHTML = '<i class="fa fa-caret-left"></i>';
	$('#sideBarBtnToggle').addClass("toggleOpen");

	document.getElementById('rightInfos').innerHTML = '<img height="200px" src="img/hma.jpg" style="width:100%;"/><br>'
		+'<div class="pull-left" style="padding:10px 20px; border-bottom:1px solid #e7eaec;">'+blanc
	    +'<strong><i class="fa fa-map-marker" aria-hidden="true"></i> Localisation : </strong><br>'+blanc
	    +'<strong><i class="fa fa-envelope" aria-hidden="true"></i> Boite postale : </strong><br>'+blanc
		+'<strong><i class="fa fa-phone" aria-hidden="true"></i> Contact 1 : </strong><br>'+blanc
		// +contact2+fax+infos
	+'</div>';
}


function voletdroit(param,valeur) {

	$('.nav-tabs li.active').removeClass('active');
	$('.tab1').addClass('active');

	var resurl='listeslies.php?elemid=voletdroit&param='+param+'&valeur='+valeur;
	var xhr = getXhr();

	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;    alert(leselect);
			var val=leselect.split('##');

			if((param==0)&&(valeur==0)){
				document.getElementById('acc').innerHTML = val[0];
				$('#acc').removeClass('hide');
				$('#navi').addClass('hide');

			} else {
				$('#navi').removeClass('hide');
				$('#acc').addClass('hide');

				// document.getElementById('socialBox').innerHTML = val[0];
				$('#socialBox').slimScroll({ height: '365px', railOpacity: 0.9 });

				document.getElementById('storyBox').innerHTML = val[1];
				$('#storyBox').slimScroll({ height: '365px', railOpacity: 0.9 });

			}


			leselect = xhr.responseText;
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}


function founisseur(code) {
	var resurl='listeslies.php?elemid=founisseur&code='+code;
	var xhr = getXhr();

	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;
			var data=leselect.split('??');

			if(data[0]!=''){
				i = 0;
				while (data[i] != 'end') {
					var val=data[i].split('#');

					var pulsingIcon = L.icon.pulse({iconSize:[10,10],color:'blue'});
					mark = L.marker([val[0], val[1]],{icon: new SweetIcon({iconUrl: 'images/icon.png',labelText:val[2]})   ,riseOnHover:false}).addTo(labels);
					mark1 =  L.marker([val[0], val[1]],{icon: pulsingIcon}).addTo(capitale);

					i += 1;

					zone(code);
				}

				if(code=='ci'){document.getElementById('fournisseurBox').innerHTML = '<button type="button" id="tft" class="btn btn-xs btn-white" onclick="fournissuers(\'tft\');">'+val[2]+'</button>';}
				else{document.getElementById('fournisseurBox').innerHTML = '';}
			}

			leselect = xhr.responseText;
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}


function zoomfournisseur(id) {
	labels.clearLayers();
	capitale.clearLayers();
	var resurl='listeslies.php?elemid=zoomfournisseur&id='+id;
	var xhr = getXhr();

	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;
			var val=leselect.split('#');

			var pulsingIcon = L.icon.pulse({iconSize:[10,10],color:'blue'});
			mark = L.marker([val[0], val[1]],{icon: new SweetIcon({iconUrl: 'images/icon.png',labelText:val[2]})   ,riseOnHover:false}).addTo(labels);
			mark1 =  L.marker([val[0], val[1]],{icon: pulsingIcon}).addTo(capitale);

            map.fitBounds(capitale.getBounds());
			// voletdroit('histoire',val[3]);

			$('#right-sidebar').addClass('sidebar-open');
			$('#sideBarBtnToggle').removeClass("hide");

			if ($('#sideBarBtnToggle').hasClass("toggleOpen")) {
				document.getElementById('sideBarBtnToggle').innerHTML = '<i class="fa fa-caret-left"></i>';
			} else {
				document.getElementById('sideBarBtnToggle').innerHTML = '<i class="fa fa-caret-right" style="margin:0 5px;"></i>';
				$('#sideBarBtnToggle').addClass("toggleOpen");
			}

			if(val[7]){ var contact2 = '<strong>Contact 2 : </strong>'+val[7]+'<br>'; } else { var contact2 = ''; }
			if(val[8]){ var fax = '<strong>Fax : </strong>'+val[8]+'<br>'; } else { var fax = ''; }
			if(val[9]){ var infos = val[9]; } else { var infos = ''; }

			document.getElementById('rightInfos').innerHTML = '<img height="200px" src="img/'+val[10]+'" style="width:100%;"/><br>'
				+'<div class="pull-left" style="padding:10px 20px;">'
			    +'<strong>Localisation : </strong>'+val[4]+'<br>'
			    +'<strong>Boite postale : </strong>'+val[5]+'<br>'
				+'<strong>Contact 1 : </strong>'+val[6]+'<br>'
				+contact2+fax+infos
				+'</div></div>';

			leselect = xhr.responseText;
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}


function zone(code){
	var resurl='listeslies.php?elemid=zone&code='+code;
	var xhr = getXhr();

	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;
			var data=leselect.split('??');

			i = 0;
			while (data[i] != 'end') {
				var elt=data[i].split('#');

				var pulsingIcon = L.icon.pulse({iconSize:[8,8],color:'orange'});
				mark = L.marker([elt[0], elt[1]],{icon: new SweetIcon({iconUrl: 'images/icon.png',labelText:elt[2]})   ,riseOnHover:false}).addTo(labels);
				mark1 =  L.marker([elt[0], elt[1]],{icon: pulsingIcon}).addTo(capitale);

				i += 1;
			}
            map.fitBounds(capitale.getBounds());
			leselect = xhr.responseText;
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}


var step_v = 0 ;
function next_step(step_coord, current_step) {
	$("#loading").show();
	$("#loading1").show();
	var coord_xy = step_coord.split('#')[current_step];

	if (coord_xy != ','){
        step_active.clearLayers();
		var coord = coord_xy.split(',');

		if (step_v < current_step) {
			step_v = current_step;
			var pulsingIcon = L.icon.pulse({iconSize:[9,9],color:'#e9967a'});

			mark1 = L.marker([coord[0], coord[1]],{icon: pulsingIcon}).addTo(story_couche);

			mark = L.marker([coord[0], coord[1]],{icon: new SweetIcon1({iconUrl: 'images/icon.png',labelText:current_step})   ,riseOnHover:false}).addTo(labels);
			map.fitBounds(story_couche.getBounds());

			if(step_v != 1){

			} else {
			   map.setZoom(9);
			}
		}

		var pulsingIcon_red = L.icon.pulse({iconSize:[14,14],color:'red'});
		mark2 =  L.marker([coord[0], coord[1]],{icon: pulsingIcon_red}).addTo(step_active);

	}

   $("#loading").hide();
   $("#loading1").hide();

}


function histoire(id_histoire){

	$("#loading").show();
	$("#loading1").show();
	clear_map_layer();
	step_v = 0 ;
	map.addLayer(bingLayer);
	var resurl='listeslies.php?elemid=historique&id_histoire='+id_histoire+'&code_pays='+var_pays+'&culture='+culture;
	var xhr = getXhr();

	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;
			var val=leselect.split('|');

			document.getElementById('storyBox').innerHTML = val[0];
			var step_coord = val[1];

			$('#storyBox').slimScroll({
				height: '365px',
				railOpacity: 0.9
			});

			$("#example-vertical").steps({
				headerTag: "h3",
				bodyTag: "section",
				transitionEffect: "slideLeft",
				stepsOrientation: "vertical",
				enableCancelButton: true,
				enableFinishButton : false,
				onStepChanging: function (event, currentIndex, newIndex) {
					next_step(step_coord,newIndex+1);
					return true;
				},
				onCanceled: function (event, currentIndex) {
					pays(var_pays,culture);
				}
			});

	        next_step(step_coord,1);
			$("#loading").hide();
			$("#loading1").hide();

			leselect = xhr.responseText;
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}


function capitales(code) {

	$.getJSON("data/capitale.geojson", function (data) {
		capitale.addData(data);
	});
}


function fleches() {

	$.getJSON("data/fleche.geojson", function (data) {
		fleche.addData(data);
		map.fitBounds(fleche.getBounds());
	});
}


var overlayMaps = {
    "Country": pays_couche,
    "Day/Night Zones": t
};


var baseMaps = {
	"CartoDB Light": cartodb_light,
	// "CartoDB Dark": cartodb_dark,
	"Bing Satellite": bingLayer
};

L.control.layers(baseMaps, overlayMaps).addTo(map);

labels.addTo(map);


function formatState (state) {

  if(state.text == 'Fr'){
     var $state = $(
		'<span style="z-index:100000000"><img width="20" src="img/fr.jpg" class="img-flag" alt="Français"/> Fr</span>'
	  );
  } else
   if(state.text == 'En'){
     var $state = $(
		'<span><img width="20" src="img/en.jpg" class="img-flag" alt="English"/> En</span>'
	  );
  } else
   if(state.text == 'De'){
      var $state = $(
		'<span><img width="20" src="img/de.jpg" class="img-flag" alt="Deutch"/> De</span>'
	  );
  } else
   if(state.text == 'Pt'){
     var $state = $(
		'<span><img width="20" src="img/pt.jpg" class="img-flag" alt="English"/> Pt</span>'
	  );
  }

  return $state;
};


$(".chosen-language").select2({
    templateResult: formatState,
    templateSelection: formatState,
	minimumResultsForSearch: Infinity
     // allowClear: true
});

bottomBoxes(0,0);
