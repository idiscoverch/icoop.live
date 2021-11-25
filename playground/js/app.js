// var URL = 'http://localhost/full/idiscover/';

var URL = '';
var blanc = "";
var niveau = 0  ;
var niveau_content = new Array();
var farmers_list = new Array();
niveau_content[0] = "0??0";

var img_elt = '';

$("#login-btn").click(function() {
	$("#loginModal").modal("show");
	$(".navbar-collapse.in").collapse("hide");
	// reset_connexion();
	return false;
});


function longin_connexion(){
	var username = document.getElementById('username').value;
	var password = document.getElementById('password').value;

	if (username == '' || password == ''  ){
		document.getElementById("login-alert12").style.display = "none";
	    document.getElementById("login-alert11").style.display = "block";
	    document.getElementById('login-alert11').innerHTML = '<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>Vous n\'avez pas entré votre mail ou votre mot de passe!';

	} else {
		var resurl='listeslies.php?elemid=connexion&username='+username+'&password='+password;
        var xhr = getXhr();
        xhr.onreadystatechange = function(){
            if(xhr.readyState == 4 ){
                leselect = xhr.responseText;
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


function connexion(){
	var username = document.getElementById('login-username').value;
	var password = document.getElementById('login-password').value;

	if (username == '' || password == ''  ){
		document.getElementById("login-alert2").style.display = "none";
	    document.getElementById("login-alert").style.display = "inline";
	    document.getElementById('login-alert').innerHTML = 'Vous n\'avez pas entré votre mail ou votre mot de passe!';

	} else {
		var resurl='listeslies.php?elemid=connexion&username='+username+'&password='+password;
        var xhr = getXhr();
        xhr.onreadystatechange = function(){
            if(xhr.readyState == 4 ){
                leselect = xhr.responseText;
                var val=leselect;
                var val1=val.split('##');

                if (val1[0] == 1) {
					window.open("index.php",'_self');

				} else {
					document.getElementById("login-alert2").style.display = "none";
				    document.getElementById("login-alert").style.display = "inline";
	                document.getElementById('login-alert').innerHTML = val1[1];
				}

				leselect = xhr.responseText;
            }
        };

        xhr.open("GET",resurl,true);
        xhr.send(null);
	}
}


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
		element.animate({marginTop: '-='+distance}, speed).animate({marginTop: '+='+distance}, speed);
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


var	cartodb_light = L.tileLayer('https://cartodb-basemaps-{s}.global.ssl.fastly.net/light_all/{z}/{x}/{y}.png', {maxZoom: 19,minZoom: 1,  attribution: 'Positron'});

var ggl = new L.Google('HYBRID');
var googlemap = new L.Google('ROADMAP');
var map = new L.Map('map', {layers: [cartodb_light]});
var t = new L.terminator();

t.addTo(map);


var control = L.control.zoomBox({
    modal: true,
});

if(idview == 1){
	map.addControl(control);
} else {
	// map.touchZoom.disable();
	// map.doubleClickZoom.disable();
	map.scrollWheelZoom.disable();
	map.boxZoom.disable();
	map.keyboard.disable();
	$(".leaflet-control-zoom").hide();
}


function ticker(code,buyer) {
	var resurl='listeslies.php?elemid=ticker&code='+code+'&buyer='+buyer;
	var xhr = getXhr();

	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;

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

			var content = leselect;
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
                colNames: [lg_ticker_date, lg_ticker_by, lg_ticker_to, lg_ticker_product, lg_ticker_contract],
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

	// $("#video").simplePlayer();
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
	                    +"<br><strong>Area(ha):</strong> "+layer.feature.properties.superficie
						;
	if (feature.properties) {
		layer.bindPopup(popupContent);
	}
}


var time_zone = L.geoJson(null, {
	style: function (feature) {
		return {
			color: "black",
			dashArray: '3',
			weight: 0.3,
			fill: false,
			opacity: 0.5
		};
	}
}).addTo(map);


var sante = L.geoJson(null, {
	pointToLayer: function (feature, latlng) {
		if (feature.properties.type == 'hopital'){
			return L.marker(latlng, {
				icon: L.icon({
					iconUrl: "img/hopital.png",
					iconSize: [15, 15],
					iconAnchor: [12, 28],
					popupAnchor: [0, -25]
				}),

				title: feature.properties.NAME,
				riseOnHover: true
			});

		} else  if (feature.properties.type == 'pharmacie') {

			return L.marker(latlng, {
				icon: L.icon({
					iconUrl: "img/pharmacie.png",
					iconSize: [15, 15],
					iconAnchor: [12, 28],
					popupAnchor: [0, -25]
				}),

				title: feature.properties.NAME,
				riseOnHover: true
			});
		}

	}, onEachFeature : onEachFeature_sante
});


function onEachFeature_sante(feature, layer) {
	var popupContent = layer.feature.properties.name;

	if (feature.properties) {
		layer.bindPopup(popupContent);
	}
}


var ecole = L.geoJson(null, {
  pointToLayer: function (feature, latlng) {
    return L.marker(latlng, {
      icon: L.icon({
        iconUrl: "img/ecole.png",
        iconSize: [35, 35],
        iconAnchor: [12, 28],
        popupAnchor: [0, -25]
      }),

      title: feature.properties.NAME,
      riseOnHover: true

    });

  }, onEachFeature : onEachFeature_ecole
});


function onEachFeature_ecole(feature, layer) {
	var popupContent = layer.feature.properties.name;

	if (feature.properties) {
		layer.bindPopup(popupContent);
	}
}


function onClick_country_0(e) {
	swal("", lg_product_first, "error");
}


function onClick_country_1(e) {
   pays(layer.feature.properties.code,culture);
}


////////////////


var var_pays = '';
var culture = 0;

if(id_exporter != 0){
	var culture = id_culture;
}

var arrow = new L.LayerGroup().addTo(map);
var arrHd = new L.LayerGroup().addTo(map);

var nbre_pays = json_pays.length-3;
// var nbre_pays = json_pays.length-1;
var nbre_supplier = json_exporter.length;
var nbre_produit = 5;
var nbre_farmer = json_village.length;

var json_plantation_group =  _.pluck(json_plantation, 'properties');
var surface =  _.reduce(json_plantation_group, function(memo, num){return memo + num.area;},0);
var nbre_surface = number_format(surface, 2, '.', ' ');

var pays_couche0 = L.geoJson('', { });

// alert(22);
var pays_couche = L.geoJson('', { style:style, onEachFeature: function (feature, layer) {

	popupOptions = {maxWidth: 250};

	var features = [];
	var val = String(layer.feature.properties.culture).split(',');
	i = 0;

	var content1 = "";

	if (layer.feature.properties.id_country == 7){
		var pulsingIcon = L.icon.pulse({iconSize:[10,10],color:'red'});
		var lang_pays = lg_pays_ch;

	} else {
		var pulsingIcon = L.icon.pulse({iconSize:[8,8],color:'green'});
	}


	if (feature.properties.id_country == 1){
		var lang_pays = lg_pays_ci;
	} else

    if (feature.properties.id_country == 2){
		var lang_pays = lg_pays_sn;
	} else

    if (feature.properties.id_country == 5){
		var lang_pays = lg_pays_sd;
	} else

    if (feature.properties.id_country == 6){
		var lang_pays = lg_pays_kh;
	} else

    if (feature.properties.id_country == 3){
	   var lang_pays = lg_pays_mz;
	} else

    if (feature.properties.id_country == 4){
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
			click: function(e) {
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

var region_frame = L.geoJson('', { style:style_frame}).addTo(map);
var pays_couche1 = L.geoJson('', { color: "green",weight:1,fill:false }).addTo(map);



pays_couche.addData(json_pays);
pays_couche0.addData(json_pays);

map.fitBounds(pays_couche.getBounds());


function refrechSelection() {

	if(id_exporter != 0){
		pays(code,id_culture);
		return;
	}

	culture = 0;
    clear_map_layer();

    map.removeLayer(ggl);
	map.addLayer(cartodb_light);
	map.addLayer(time_zone);

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
	$('#small-chat').addClass('hide');
	$('.small-chat-box').addClass('hide');

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
var circle_layer = L.geoJson(null, { }).addTo(map);

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

	if(layer.feature.properties.name_farmer == null){ var name_farmer=''; }else{ var name_farmer = layer.feature.properties.name_farmer; }
	if(layer.feature.properties.name_farmergroup == null){ var name_farmergroup=''; }else{ var name_farmergroup = layer.feature.properties.name_farmergroup; }
	if(layer.feature.properties.name_town == null){ var name_town=''; }else{ var name_town = layer.feature.properties.name_town; }
	if(layer.feature.properties.culture == null){ var culture=''; }else{ var culture = layer.feature.properties.culture; }
	if(layer.feature.properties.area == null){ var area=''; }else{ var area = layer.feature.properties.area; }
	if(layer.feature.properties.variety == null){ var variety=''; }else{ var variety = layer.feature.properties.variety; }
	if(layer.feature.properties.name_buyer == null){ var name_buyer=''; }else{ var name_buyer = layer.feature.properties.name_buyer; }
	if(layer.feature.properties.code_buyer == null){ var code_buyer=''; }else{ var code_buyer = layer.feature.properties.code_buyer; }
	if(layer.feature.properties.code_town == null){ var code_town=''; }else{ var code_town = layer.feature.properties.code_town; }

	if(idview == 1){
		var popup_buyer = name_buyer;
		var popup_town = name_town;
	} else {
		var popup_buyer = code_buyer;
		var popup_town = code_town;
	}

	var popupContent = "<div style=\"max-width:400px; max-height: 200px\"><h5 style=\"border-bottom: 1px solid #eee; color:#ed1b2c\"><i class=\"fa fa-check-square fa-fw\" style=\"color:#ed1b2c\"></i><strong>&nbsp;&nbsp;"+lg_plantation_details+"</strong></h5>"+blanc
		+"<div class=\"icon_desc\" style=\"margin-left:0px;display:block\"><span><i class=\"fa fa-arrows fa-fw\"></i> <strong>  "+lg_plantation_farmer_name+" : </strong>"+name_farmer.substr(0, 5)+"...("+layer.feature.properties.id_contact+")"+blanc
		  +" </span><br><span><i class=\"fa fa-arrows fa-fw\"></i> <strong> "+lg_plantation_farmer_groups+" : </strong>"+name_farmergroup
		  +" </span><br><span><i class=\"fa fa-arrows fa-fw\"></i> <strong> "+lg_plantation_farmer_residence+" : </strong>"+popup_town
		  +" </span><br><span><i class=\"fa fa-arrows fa-fw\"></i> <strong> "+lg_plantation_culture+" : </strong>"+culture
		 +" </span><br><span><i class=\"fa fa-arrows fa-fw\"></i> <strong> "+lg_plantation_area+" : </strong>"+area
		 +" </span><br><span><i class=\"fa fa-arrows fa-fw\"></i> <strong> "+lg_plantation_culture_variety+" : </strong>"+variety
		 +" </span><br><span><i class=\"fa fa-arrows fa-fw\"></i> <strong> "+lg_plantation_buyer+" : </strong>"+popup_buyer
	+" </span></div></div>";


	if (feature.properties) {
		layer.bindPopup(popupContent);
	}
}

var collection_point_couche = new L.LayerGroup();

var plantation_couche0 = L.geoJson('', {});
var plantation_couche = L.geoJson('', { color: '#e38217', weight: 2, opacity:0.5, onEachFeature: onEachFeature_plantation}).addTo(map);

plantation_couche0.addData(json_plantation);


//////////////


function select_culture(cult) {

	if(loggedin == 0){
		$('#loginModal').modal('show');
		return;
	}

	if(id_exporter != 0){
		pays(code,id_culture);
		return;
	}

   $("#loading").show();
   $("#loading1").show();
   $('.navbar-collapse').removeClass("in");

	clear_map_layer();

	map.removeLayer(ggl);
	map.addLayer(cartodb_light);

	$('#bt_panel').removeClass('hide');
	$('#bt_images').addClass('hide');
	$('#small-chat').removeClass('hide');

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

				if(layer.feature.properties.code != 'ch'){
				code += layer.feature.properties.code+'|';
				}
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

	// alert(JSON.stringify(json_village));

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
	collection_point_couche.clearLayers();

	circle_layer.clearLayers();
    route.clearLayers();
	highlight_red.clearLayers();
	plantation_farmer.clearLayers();

	$('#bt_panel').removeClass('hide');
	$('#bt_images').addClass('hide');


	map.removeLayer(sante);
	map.removeLayer(ecole);
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
		$('#right-sidebar').addClass('hide');

		$('#sideBarBtnToggle').removeClass("toggleOpen");
		$('#sideBarBtnToggle').removeClass("fadeInLeftBig");
		// $('#sideBarBtnToggle').addClass("fadeOutLeftBig");

	} else {
		document.getElementById('sideBarBtnToggle').innerHTML = '<i class="fa fa-caret-left"></i>';
	    $('#right-sidebar').removeClass('hide');
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

	map.removeLayer(ggl);
	map.addLayer(cartodb_light);

	$('#bt_panel').removeClass('hide');
	$('#bt_images').addClass('hide');

    var latitude = e.latlng.lat ;
    var longitude = e.latlng.lng ;

	for (j in json_exporter) {
        if ((json_exporter[j].properties.coord_y == longitude) && (json_exporter[j].properties.coord_x == latitude)){
			if(idview == 1){ var exporter=json_exporter[j].properties.name_exporter; } else { var exporter=json_exporter[j].properties.initials; }
			var pulsingIcon = L.icon.pulse({iconSize:[10,10],color:'blue'});
			mark1 =  L.marker([json_exporter[j].properties.coord_x, json_exporter[j].properties.coord_y],{icon: pulsingIcon}).addTo(exporter_couche).on('click', onClick_exporter);
			mark = L.marker([json_exporter[j].properties.coord_x, json_exporter[j].properties.coord_y],{icon: new SweetIcon1({iconUrl: 'images/icon.png',labelText:exporter})   ,riseOnHover:false}).addTo(labels).on('click', onClick_exporter);

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

			var content = '';

			var tz = json_exporter[j].properties.timezone;
			if(tz == 0){ var gmt = '(GMT '+tz+')'; }
			else if(tz > 0){ var gmt = '(GMT +'+tz+')'; }
			else if(tz < 0){ var gmt = '(GMT -'+tz+')'; }
			else{ var gmt =''; }


			content += '<div style="position: relative">';

			if(json_exporter[j].properties.e0_02logo != null && json_exporter[j].properties.e0_02logo != ''){
				content += '<img src="img/'+json_exporter[j].properties.e0_02logo+'" height="60" style="box-shadow:0 1px 6px 0 rgba(0,0,0,.3);position:absolute;right:20px;margin-top:180px;float:right"/>';
			}

			if(json_exporter[j].properties.e0_01photo != null && json_exporter[j].properties.e0_01photo != ''){
				content += '<img height="200px" src="img/'+json_exporter[j].properties.e0_01photo+'" style="width:100%;"/></div>';
			}

			if(idview == 1){ var exporter=json_exporter[j].properties.name_exporter; } else { var exporter=json_exporter[j].properties.initials; }

			content +='<div style="background-color: #4169e1;color: #fff;padding:10px 20px">'+blanc
					+'<h3>'+exporter+' </h3>'+blanc
					+'<img src="img/'+json_exporter[j].properties.code_country+'.png" height="12" style=""/>&nbsp;<strong>'+json_exporter[j].properties.e0_05+'</strong>'+blanc
					+'<br><div style="display:inline-block"><div class="pull-left">'+lg_local_time+' : &nbsp;&nbsp; </div><div id="time-cont-1" class="pull-left"></div> &nbsp;&nbsp; '+gmt+'</div>'+blanc
				+'</div>'+blanc
				+'<div style="padding:10px 20px; border-bottom:1px solid #e7eaec;width:100%">';

			if(json_exporter[j].properties.e1_01 != null && json_exporter[j].properties.e1_01 != ''){
				content += '<div class="panel panel-default" style="margin-bottom: 0px;padding: 5px 10px;width:100%;opacity:0.8"><h3 style="color:#ffa500"> '+json_exporter[j].properties.e1+'  </h3>'+blanc
					+json_exporter[j].properties.e1_01+'</div>'+blanc;
			}

			content += '<br><div class="panel panel-default" style="margin-bottom: 0px;padding: 5px 10px;width:100%;opacity:0.8">';

			if(json_exporter[j].properties.e2 != null && json_exporter[j].properties.e2 != ''){
				content += '<h3 style="color:#ffa500"><i class="fa fa-map-marker" aria-hidden="true"></i> &nbsp;'+json_exporter[j].properties.e2+'</h3>'+blanc
				content += '<div style="width:97%; margin-left:3%;">'+blanc
			}

			if(json_exporter[j].properties.e0_03name != null && json_exporter[j].properties.e0_03name != ''){
			   content += json_exporter[j].properties.e0_03name;
			}

			if(json_exporter[j].properties.e2_01 != null && json_exporter[j].properties.e2_01 != ''){
			   content += '<br>'+json_exporter[j].properties.e2_01;
			}

			if(json_exporter[j].properties.e2_02 != null && json_exporter[j].properties.e2_02 != ''){
			   content += '<br>'+json_exporter[j].properties.e2_02;
			}

			if(json_exporter[j].properties.e2_03 != null && json_exporter[j].properties.e2_03 != ''){
			   content += '<br>'+json_exporter[j].properties.e2_03;
			}

			if(json_exporter[j].properties.e2_04 != null && json_exporter[j].properties.e2_04 != ''){
			   content += '<br>'+json_exporter[j].properties.e2_04;
			}

			if(json_exporter[j].properties.e2_05 != null && json_exporter[j].properties.e2_05 != ''){
			   content += '<br>'+json_exporter[j].properties.e2_05;
			}

			content += '</div></div>'+blanc

			+'<br><div class="panel panel-default" style="margin-bottom: 0px;padding: 5px 10px;width:100%;opacity:0.8">';

			if(json_exporter[j].properties.e3 != null && json_exporter[j].properties.e3 != ''){
				content += '<h3 style="color:#ffa500"><i class="fa fa-map-marker" aria-hidden="true"></i>&nbsp;'+json_exporter[j].properties.e3+'</h3>'+blanc
				content += '<div style="width:97%; margin-left:3%;">'+blanc
			}

			if(json_exporter[j].properties.e3_01 != null && json_exporter[j].properties.e3_01 != ''){
				content += json_exporter[j].properties.e3_01+blanc;
			}

			if(json_exporter[j].properties.e3_02 != null && json_exporter[j].properties.e3_02 != ''){
				content += '<br>'+json_exporter[j].properties.e3_02+blanc;
			}

			if(json_exporter[j].properties.e3_03 != null && json_exporter[j].properties.e3_03 != ''){
				content += '<br>'+json_exporter[j].properties.e3_03;
			}

			if(json_exporter[j].properties.e3_04 != null && json_exporter[j].properties.e3_04 != ''){
			   content += '<br>'+json_exporter[j].properties.e3_04;
			}

			content += '</div></div>'+blanc

			+'<br><div class="panel panel-default" style="margin-bottom: 0px;padding: 5px 10px;width:100%;opacity:0.8">';

			if(json_exporter[j].properties.e4 != null && json_exporter[j].properties.e4 != ''){
				content += '<h3 style="color:#ffa500"><i class="fa fa-map-marker" aria-hidden="true"></i>&nbsp;'+json_exporter[j].properties.e4+'</h3>'+blanc
				content += '<div style="width:97%; margin-left:3%;">'+blanc
			}

			if(json_exporter[j].properties.e4_01 != null && json_exporter[j].properties.e4_01 != ''){
			   content += json_exporter[j].properties.e4_01;
			}

			if(json_exporter[j].properties.e4_02 != null && json_exporter[j].properties.e4_02 != ''){
			   content += '<br>'+json_exporter[j].properties.e4_02;
			}

			if(json_exporter[j].properties.e4_03 != null && json_exporter[j].properties.e4_03 != ''){
			   content += '<br>'+json_exporter[j].properties.e4_03;
			}

			if(json_exporter[j].properties.e4_04 != null && json_exporter[j].properties.e4_04 != ''){
			   content += '<br>'+json_exporter[j].properties.e4_04;
			}

			if(json_exporter[j].properties.e4_05 != null && json_exporter[j].properties.e4_05 != ''){
			   content += '<br>'+json_exporter[j].properties.e4_05;
			}

			if(json_exporter[j].properties.e4_06 != null && json_exporter[j].properties.e4_06 != ''){
			   content += '<br>'+json_exporter[j].properties.e4_06;
			}

			if(json_exporter[j].properties.e4_07 != null && json_exporter[j].properties.e4_07 != ''){
			   content += '<br>'+json_exporter[j].properties.e4_07;
			}

			if(json_exporter[j].properties.e4_08 != null && json_exporter[j].properties.e4_08 != ''){
			   content += '<br>'+json_exporter[j].properties.e4_08;
			}

			if(json_exporter[j].properties.e4_09 != null && json_exporter[j].properties.e4_09 != ''){
			   content += '<br>'+json_exporter[j].properties.e4_09;
			}

			content += '</div></div>'+blanc

			+'</div>';

			document.getElementById('rightInfos').innerHTML = content;

			Localtime(json_exporter[j].properties.timezone);
			img_elt = json_exporter[j].properties.id_contact;

			var c_code ='';
			var arr = [];

			for (i in json_city) {
				var features = [];

				c_code = json_city[i].properties.code_country;
				c_code = c_code.replace(/\s+/g, '');

				if(( json_exporter[j].properties.code_country == c_code) && ( json_exporter[j].properties.id_culture == json_city[i].properties.id_culture)){
					
					if(idview == 1){
						var name_city = json_city[i].properties.name_city;
					} else {
						var name_city = json_city[i].properties.code_city;
					}
				
					var pulsingIcon = L.icon.pulse({iconSize:[10,10],color:'orange'});

					mark1 =  L.marker([json_city[i].properties.coord_x, json_city[i].properties.coord_y],{icon: pulsingIcon}).addTo(city_couche).on('click', city);
				    mark = L.marker([json_city[i].properties.coord_x, json_city[i].properties.coord_y],{icon: new SweetIcon({iconUrl: 'images/icon.png',labelText:name_city})   ,riseOnHover:false}).addTo(labels).on('click', city);
					k += 1;
				}
			}
		}
	}

	map.fitBounds(exporter_couche.getBounds().extend(city_couche.getBounds()));
	var currentZoom = map.getZoom();

	if(currentZoom > 12){
	    map.setZoom(10);
	}

	imgManagement('id_exporter',img_elt);
}


function imgManagement(val,id) {
	var resurl='listeslies.php?elemid=imgManagement&val='+val+'&id='+id;
	var xhr = getXhr();

	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;

			if(leselect!=''){
				$('#small-chat').removeClass('hide');
				$('.small-chat-box').removeClass('hide');
			} else {
				$('#small-chat').addClass('hide');
				$('.small-chat-box').addClass('hide');
			}
			document.getElementById('img_content').innerHTML = leselect;

			if(val = 'id_town'){
				document.getElementById('lightBoxGallery').innerHTML = leselect;
			}

			leselect = xhr.responseText;
		}
	};

	xhr.open("GET",resurl,true);
	xhr.send(null);
}


function pays(code_pays,culture) {
	if (code_pays == 'ch') {
	  return;
	}

	$("#loading").show();
	$("#loading1").show();

	clear_map_layer();

	map.removeLayer(time_zone);
	map.removeLayer(ggl);
	map.addLayer(cartodb_light);

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

	if(id_buyer!=''){
		for (j in json_buyer) {
			if(json_buyer[j].properties.id_buyer == id_buyer){
				if(idview == 1){ var byer=json_buyer[j].properties.name_buyer; } else { var byer=json_buyer[j].properties.initials; }

				var pulsingIcon = L.icon.pulse({iconSize:[10,10],color:'yellow'});
				mark1 =  L.marker([json_buyer[j].properties.coord_x, json_buyer[j].properties.coord_y],{icon: pulsingIcon}).addTo(city_couche).on('click', onClick_buyer);
				mark = L.marker([json_buyer[j].properties.coord_x, json_buyer[j].properties.coord_y],{icon: new SweetIcon1({iconUrl: 'images/icon.png',labelText:byer})   ,riseOnHover:false}).addTo(labels).on('click', onClick_buyer);
			}
		}

	} else {

		for (j in json_exporter) {
			if(( code_pays == json_exporter[j].properties.code_country) && ( culture == json_exporter[j].properties.id_culture)){
				if(idview == 1){ var exporter=json_exporter[j].properties.name_exporter; } else { var exporter=json_exporter[j].properties.initials; }

				var pulsingIcon = L.icon.pulse({iconSize:[10,10],color:'blue'});
				mark1 = L.marker([json_exporter[j].properties.coord_x, json_exporter[j].properties.coord_y],{icon: pulsingIcon}).addTo(exporter_couche).on('click', onClick_exporter);
				mark = L.marker([json_exporter[j].properties.coord_x, json_exporter[j].properties.coord_y],{icon: new SweetIcon1({iconUrl: 'images/icon.png',labelText:exporter})   ,riseOnHover:false}).addTo(labels).on('click', onClick_exporter);
			}
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
	var story_content = "";
	var link = '';
	var link_video = [];
    var j = 0;
	for (i in json_stories) {
		if(( code_pays == json_stories[i].properties.code) && ( culture == json_stories[i].properties.id_culture)){

			if(j==0){ var active = 'active';} else { var active = '';}

			if( json_stories[i].properties.media_type == 1 ){
				link_video[j] = '<figure><iframe id="myFrame" style="width:100%;" height="250" frameborder="0" allowfullscreen="true" src="https://www.youtube.com/embed/'+json_stories[i].properties.media_link.replace(/\s+/g, '')+'?autoplay=1&rel=0&loop=1" marginwidth="0" marginheight="0"></iframe></figure>';

				story_content += '<div class="item '+active+'">'+blanc
					+'<div id="video_'+j+'" style="height:250px; overflow:hidden; width:100%;" data-video="'+json_stories[i].properties.media_link.replace(/\s+/g, '')+'" class="video" >'+blanc
					+'<img src="https://i1.ytimg.com/vi/'+json_stories[i].properties.media_link.replace(/\s+/g, '')+'/hqdefault.jpg" alt="Video.">'+blanc
					+'<div  class="play" onclick="click_play('+j+',\''+json_stories[i].properties.media_link.replace(/\s+/g, '')+'\')" style=""></div>'+blanc
					+'</div>'+blanc
					+'<div class="carCtn"><div>'+json_stories[i].properties.title+'</div><span id="btn_story_'+j+'"></span><br/>'+blanc
				+'</div></div>';

			} else {

				link = '<img alt="image" style="width:100%;" class="img-responsive" src="img/story/'+json_stories[i].properties.media_link.replace(/\s+/g, '')+'">';

				story_content += '<div class="item '+active+'">'+blanc
					+'<div style="height:250px; overflow:hidden; width:100%;">'+link+'</div>'+blanc
					+'<div class="carCtn"><div>'+json_stories[i].properties.title+'</div><br/>'+blanc
					  +'<button type="button" onclick="histoire('+json_stories[i].properties.id_story+');" class="pull-right  btn btn-primary btn-xs animation_select" data-animation="bounceOut">'+lg_view_story+'</button>'+blanc
					+'</div>'+blanc
				+'</div>';

			}
			j += 1;
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

	$(".play").click(function() {

	});
}


function click_play(inc,code_video) {

	$('.carousel-control').addClass('hide');
	$('#carousel1').carousel('pause');
	document.getElementById('btn_story_'+inc).innerHTML = '<button onclick="pays(\''+var_pays+'\','+culture+');" type="button" class="pull-right  btn btn-primary btn-xs" data-dismiss="modal">Close</button>';
	document.getElementById('video_'+inc).innerHTML = '<figure><iframe id="myFrame"  style="width:100%;" height="250" frameborder="0" allowfullscreen="true" src="https://www.youtube.com/embed/'+code_video+'?autoplay=1&rel=0&loop=1" marginwidth="0" marginheight="0"></iframe></figure>';
}


var plantation_farmer = L.geoJson('', {});

function style_red(feature) {
	return {
		stroke: true,
		fill: true,
		fillColor: '#e38217',
		fillOpacity: 0.2,
		color: 'red',
		opacity: 1,
		weight: 3
	};
}


var highlight_red = L.geoJson('', { style:style_red }).addTo(map);

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
	}
}


var village_entite;
function hide_plantation(code) {
	plantation_couche.clearLayers();
	highlight_red.clearLayers();

	document.getElementById('toggle_plant').innerHTML = '<a href="javascript:show_plantation('+code+')" class="pull-right" style="margin-top:8px;"><i class="fa fa-map-marker" aria-hidden="true"></i>&nbsp; '+lg_show_all_plantations+'</a>';

	var latitude = village_entite.latlng.lat ;
    var longitude = village_entite.latlng.lng ;

    map.fitBounds([[latitude,longitude],[latitude+0.0000000000001,longitude+0.00000000000001]]);
	map.setZoom(12);
}


function hide_collection_point(code) {
	collection_point_couche.clearLayers();

	document.getElementById('toggle_point').innerHTML = '<a href="javascript:all_collection_point('+code+')" class="pull-right" style="margin-top:8px;"><i class="fa fa-map-pin" aria-hidden="true"></i>&nbsp; '+lg_show_all_plantations+'</a>';

	var latitude = village_entite.latlng.lat ;
    var longitude = village_entite.latlng.lng ;

    map.fitBounds([[latitude,longitude],[latitude+0.0000000000001,longitude+0.00000000000001]]);
	map.setZoom(12);
}


var LeafIcon = L.Icon.extend({
    options: {
           // shadowUrl: '../img/cocotier.png',
        iconSize:     [25, 30],
           // shadowSize:   [41, 41],
		iconAnchor:   [16, 32],
           // shadowAnchor: [4, 41],
        popupAnchor:  [0, -33]
    }
});

var pointIcon = new LeafIcon({iconUrl: 'img/icon_point.png'}) ;


function onClick_village(e) {
    clear_map_layer();
	farmers_list = [];

	niveau = 5;
	village_entite = e;

	$('#bt_images').removeClass('hide');
	$('#bt_panel').addClass('hide');
	// $('#small-chat').addClass('hide');

    var latitude = e.latlng.lat ;
    var longitude = e.latlng.lng ;
	var t = 0;
	var a = 1;
	var k1 = 0;
	var p1 = 0;

	for (i in json_village) {
		if ((json_village[i].properties.x == latitude) && (json_village[i].properties.y == longitude)){
			if(t == 0){

				if(idview == 1){
					var towns = json_village[i].properties.name_town;
				} else {
					var towns = json_village[i].properties.code_town;
				}

                var pulsingIcon = L.icon.pulse({iconSize:[10,10],color:'green'});
				mark1 =  L.marker([json_village[i].properties.x, json_village[i].properties.y],{icon: pulsingIcon}).addTo(buyer_couche).on('click', onClick_village);
				mark = L.marker([json_village[i].properties.x, json_village[i].properties.y],{icon: new SweetIcon1({iconUrl: 'images/icon.png',labelText:towns})   ,riseOnHover:false}).addTo(labels).on('click', onClick_village);
		        t = 1;

				var centre_x = json_village[i].properties.x;
				var centre_y = json_village[i].properties.y;
				volet_gauche_animated();
			}

			var coll_point = '';

		    var total_ha = 0;
		    var nom_cult;
		    var k=0;

			plantation_couche0.eachLayer(function (layer) {
				if(layer.feature.properties.code_farmer == json_village[i].properties.code_farmer){
					total_ha += layer.feature.properties.area;
					nom_cult =	layer.feature.properties.culture;
					k+=1;
				}
			});

			var total_ha2 = 0;
		    var nom_cult2;

			for (c in json_plantation) {
				if (json_plantation[c].properties.code_farmer == json_village[i].properties.code_farmer){

					total_ha2 += json_plantation[c].properties.area;
					nom_cult2 =	json_plantation[c].properties.culture;

					if((json_plantation[c].properties.coordx != null) && (json_plantation[c].properties.coordy != null)){

						coll_point = '<a href="#" onclick="show_collection_point(\''+json_plantation[c].properties.code_farmer+'\');"><i class="fa fa-map-pin"></i></a>';
						p1 =1;
					}
				}
			}

			var html_show_plant = '';
			var html_show_points = '';
			img_elt = json_village[i].properties.id_town;

			if(k != 0){
				var mapped_plantation = '<a class="" href="#" onclick="highlight_feature(\''+json_village[i].properties.code_farmer+'\');"><i class="fa fa-map-marker"></i></a>';
				k1 = 1;
			} else {
				var mapped_plantation = '';
			}

			if(k1 != 0){
				var html_show_plant = '<a href="javascript:show_plantation('+json_village[i].properties.id_town+')" class="pull-right" style="margin-top:8px;"><i class="fa fa-map-marker" aria-hidden="true"></i>&nbsp; '+lg_show_all_plantations+'</a>';
			} else {

				total_ha = total_ha2;
				nom_cult = nom_cult2;
			}

			if(p1 != 0){
				html_show_points = '<a href="javascript:all_collection_point(\''+json_village[i].properties.id_town+'\')" class="pull-right" style="margin-top:8px;"><i class="fa fa-map-pin" aria-hidden="true"></i>&nbsp; '+lg_show_all_collection_points+'</a>';
			}


			$('#right-sidebar').addClass('sidebar-open');
			$('#sideBarBtnToggle').removeClass("hide");

			document.getElementById('sideBarBtnToggle').innerHTML = '<i class="fa fa-caret-left"></i>';
			document.getElementById('sideBarBtnToggle').style.backgroundColor = '#20b2aa';
			document.getElementById('sideBarSearchBox').style.backgroundColor = '#20b2aa';

		    farmers_ct = '<div class="panel-group" id="accordion" style="margin-bottom:5px;opacity:0.8">'+blanc
                +'<div class="panel panel-default">'+blanc
                    +'<div class="panel-heading">'+blanc
                        +'<table style="width:100%" class="panel-title"><tr>'+blanc
						  +'<td style="width:25%">'+json_village[i].properties.name_farmer.substr(0, 5)+'...</td>'+blanc
						  +'<td style="width:35%">'+json_village[i].properties.number_plantation+' '+lg_farmers_plantations+'</td>'+blanc
						  +'<td style="width:30%">'+number_format(total_ha, 2, '.', ' ')+' Ha</td>'+blanc
						  +'<td>'+coll_point+' '+mapped_plantation+'</td>'+blanc
						  +'<td><a data-toggle="collapse" data-parent="#accordion" class="collapse-link pull-right" href="#farmer'+a+'"><i class="fa fa-chevron-down"></i></a></td></tr>'+blanc
						+'</table></h5>'+blanc
                    +'</div>'+blanc

                    +'<div id="farmer'+a+'" class="panel-collapse collapse">'+blanc
                        +'<div class="panel-body">'+blanc
						  +'<table class="table table-striped">'+blanc
						     +'<tr><td>'+lg_crop+'</td><td>'+nom_cult+'</td></tr>'+blanc
						     +'<tr><td>'+lg_other_crops+'</td><td></td></tr>'+blanc
						     +'<tr><td>'+lg_other_source_income+'</td><td></td></tr>'+blanc
						     +'<tr><td>'+lg_no_employees+'</td><td></td></tr>'+blanc
						     +'<tr><td>'+lg_last_audit_profairtrade+'</td><td></td></tr>'+blanc
						     // +'<tr><td>'+lg_fields_surface_total+'</td><td></td></tr>'+blanc
						     +'<tr><td>'+lg_plantation_quality+'</td><td></td></tr>'+blanc
						     +'<tr><td>'+lg_professional_capacity+'</td><td></td></tr>'+blanc
						     +'<tr><td>'+lg_professional_experience+'</td><td></td></tr>'+blanc
						     // +'<tr><td>'+lg_farmer_village+'</td><td>'+json_village[i].properties.name_town+'</td></tr>'+blanc
						     // +'<tr><td>'+lg_farmer_group+'</td><td>'+json_village[i].properties.name_farmergroup+'</td></tr>'+blanc
						     // +'<tr><td>'+lg_farmer_birthday+'</td><td>'+json_village[i].properties.birthday+'</td></tr>'+blanc
						     +'<tr><td>'+lg_farmer_gender+'</td><td>'+json_village[i].properties.sex+'</td></tr>'+blanc
						     // +'<tr><td>'+lg_farmer_contacts+'</td><td>'+json_village[i].properties.contact+'</td></tr>'+blanc
						     +'<tr><td>'+lg_civil_state+'</td><td></td></tr>'+blanc
						     +'<tr><td>'+lg_farmer_children+'</td><td>'+json_village[i].properties.number_child+'</td></tr>'+blanc
						     +'<tr><td>'+lg_farmer_children_school+'</td><td></td></tr>'+blanc
						  +'</table>'+blanc
						+'</div>'+blanc
                    +'</div>'+blanc
                +'</div>'+blanc
			+'</div>';

			farmers_list.push(farmers_ct);
			// alert(json_village[i].properties.timezone);

			var tz = json_village[i].properties.timezone;
			if(tz == 0){ var gmt = '(GMT '+tz+')'; }
			else if(tz > 0){ var gmt = '(GMT +'+tz+')'; }
			else if(tz < 0){ var gmt = '(GMT -'+tz+')'; }
			else{ var gmt =''; }

			a +=1;
			var content = '';

			content += '<div style="position: relative">';

			if(json_village[i].properties.e0_01photo != null && json_village[i].properties.e0_01photo != ''){
				content += '<img height="200px" src="img/'+json_village[i].properties.e0_01photo+'" style="width:100%;"/></div>';
			}

   			content += '<div style="background-color: #20b2aa;color: #fff;padding:10px 20px">'+blanc
				+'<h3>'+towns+' </h3>'+blanc
				   +'<img src="img/'+var_pays+'.png" height="12" style=""/>&nbsp;<strong>'+lg_farmervillage+'</strong>'+blanc
				+'<br><div style="display:inline-block"><div class="pull-left">'+lg_local_time+' : &nbsp;&nbsp; </div><div id="time-cont-3" class="pull-left"></div> &nbsp;&nbsp;'+gmt+' </div>'+blanc
				+'</div>'+blanc
				+'<div class="pull-left" style="padding:10px 20px; border-bottom:1px solid #e7eaec; width:100%">'+blanc
				+'<br><div class="panel panel-default" style="margin-bottom: 10px;padding: 5px 10px;width:100%;opacity:0.8">'+blanc
					+'<h3 style="color:#ffa500">&nbsp;'+lg_equipement+'</h3>'+blanc
					+'&nbsp;&nbsp;<input id="sante_checkbox" onclick="checked_layer(\'sante\',this);" class="leaflet-control-layers-selector" type="checkbox"> &nbsp;<label for="sante_checkbox"><img src="img/hopital_small.png">&nbsp;'+lg_helf+'</label>&nbsp;&nbsp;&nbsp;&nbsp; '+blanc
					+'<input id="ecole_checkbox" onclick="checked_layer(\'ecole\',this);" class="leaflet-control-layers-selector" type="checkbox">&nbsp;<label for="ecole_checkbox"><img src="img/ecole_small.png">&nbsp;'+lg_schools+'</label>&nbsp;&nbsp;'+blanc
					// +'<br>&nbsp;&nbsp;<input id="aire_protegee_checkbox" onclick="checked_layer(\'aire_protegee\',this);" class="leaflet-control-layers-selector" type="checkbox"> &nbsp;<label for="aire_protegee_checkbox"><img src="img/protected_area_small.png">&nbsp;'+lg_protected_areas+'</label>&nbsp;&nbsp;'+blanc
					+'<br><div id="ionrange_equipement"></div>'+blanc
				+'</div>'+blanc
				+'<br><div class="panel panel-default" style="margin-bottom: 10px;padding: 5px 10px;width:100%;opacity:0.8;display:inline-block;">'+blanc
				+'<h3 style="color:#ffa500" class="pull-left">&nbsp;'+lg_farmers_list+'</h3><span id="toggle_plant">'+html_show_plant+'</span><br/><span id="toggle_point">'+html_show_points+'</span>'+blanc
				+'</div>'+farmers_list.join("")+blanc
			+'</div>';

			document.getElementById('rightInfos').innerHTML = content;
			Localtime(json_village[i].properties.timezone);
		}
	}

	imgManagement('id_town',img_elt);

	$("#ionrange_equipement").ionRangeSlider({
		min: 0,
		max: 100,
		postfix: " Km",
    });

	$("#ionrange_equipement").on("change", function () {
		var $this = $(this),
		value = $this.prop("value");
		create_circle(centre_x,centre_y,value);
	});

	create_circle(centre_x,centre_y,0);
	map.setZoom(12);
}


function highlight_feature(code_farmer) {
	collection_point_couche.clearLayers();
	highlight_red.clearLayers();
    var k=0;

    plantation_couche0.eachLayer(function (layer) {
		if(layer.feature.properties.code_farmer == code_farmer){
			highlight_red.addData(layer.feature);

			if(layer.feature.properties.name_farmer == null){ var name_farmer=''; }else{ var name_farmer = layer.feature.properties.name_farmer; }
			if(layer.feature.properties.name_farmergroup == null){ var name_farmergroup=''; }else{ var name_farmergroup = layer.feature.properties.name_farmergroup; }
			if(layer.feature.properties.name_town == null){ var name_town=''; }else{ var name_town = layer.feature.properties.name_town; }
			if(layer.feature.properties.culture == null){ var culture=''; }else{ var culture = layer.feature.properties.culture; }
			if(layer.feature.properties.area == null){ var area=''; }else{ var area = layer.feature.properties.area; }
			if(layer.feature.properties.variety == null){ var variety=''; }else{ var variety = layer.feature.properties.variety; }
			if(layer.feature.properties.name_buyer == null){ var name_buyer=''; }else{ var name_buyer = layer.feature.properties.name_buyer; }
			if(layer.feature.properties.code_buyer == null){ var code_buyer=''; }else{ var code_buyer = layer.feature.properties.code_buyer; }
			if(layer.feature.properties.code_town == null){ var code_town=''; }else{ var code_town = layer.feature.properties.code_town; }

			if(idview == 1){
				var popup_buyer = name_buyer;
				var popup_town = name_town;
			} else {
				var popup_buyer = code_buyer;
				var popup_town = code_town;
			}

			var popupContent = "<div style=\"max-width:400px; max-height: 200px\"><h5 style=\"border-bottom: 1px solid #eee; color:#ed1b2c\"><i class=\"fa fa-check-square fa-fw\" style=\"color:#ed1b2c\"></i><strong>&nbsp;&nbsp;"+lg_plantation_details+"</strong></h5>"+blanc
				+"<div class=\"icon_desc\" style=\"margin-left:0px;display:block\"><span><i class=\"fa fa-arrows fa-fw\"></i> <strong>  "+lg_plantation_farmer_name+" : </strong>"+name_farmer.substr(0, 5)+"... ("+layer.feature.properties.id_contact+")"+blanc
				  +" </span><br><span><i class=\"fa fa-arrows fa-fw\"></i> <strong> "+lg_plantation_farmer_groups+" : </strong>"+name_farmergroup
				  +" </span><br><span><i class=\"fa fa-arrows fa-fw\"></i> <strong> "+lg_plantation_farmer_residence+" : </strong>"+popup_town
				  +" </span><br><span><i class=\"fa fa-arrows fa-fw\"></i> <strong> "+lg_plantation_culture+" : </strong>"+culture
				 +" </span><br><span><i class=\"fa fa-arrows fa-fw\"></i> <strong> "+lg_plantation_area+" : </strong>"+area
				 +" </span><br><span><i class=\"fa fa-arrows fa-fw\"></i> <strong> "+lg_plantation_culture_variety+" : </strong>"+variety
				 +" </span><br><span><i class=\"fa fa-arrows fa-fw\"></i> <strong> "+lg_plantation_buyer+" : </strong>"+popup_buyer
			+" </span><br><br><div style=\"text-align:center\"> <a href=\"javascript:details('"+layer.feature.properties.code_farmer+"')\" ><span> </span></a></div></div></div>";

			highlight_red.bindPopup(popupContent).openPopup();
			k += 1;
		}
	});


	if (k != 0){
		map.fitBounds(highlight_red.getBounds());
		highlight_red.bringToFront();

	} else {
		onClick_village(village_entite);
	}
}


function show_collection_point(code_farmer) {
	collection_point_couche.clearLayers();
	plantation_farmer.clearLayers();
	highlight_red.clearLayers();

	for (c in json_plantation) {
		if (json_plantation[c].properties.code_farmer == code_farmer){

			if(json_plantation[c].properties.name_farmer == null){ var name_farmer=''; }else{ var name_farmer = json_plantation[c].properties.name_farmer; }
			if(json_plantation[c].properties.name_farmergroup == null){ var name_farmergroup=''; }else{ var name_farmergroup = json_plantation[c].properties.name_farmergroup; }
			if(json_plantation[c].properties.name_town == null){ var name_town=''; }else{ var name_town = json_plantation[c].properties.name_town; }
			if(json_plantation[c].properties.culture == null){ var culture=''; }else{ var culture = json_plantation[c].properties.culture; }
			if(json_plantation[c].properties.area == null){ var area=''; }else{ var area = json_plantation[c].properties.area; }
			if(json_plantation[c].properties.variety == null){ var variety=''; }else{ var variety = json_plantation[c].properties.variety; }
			if(json_plantation[c].properties.name_buyer == null){ var name_buyer=''; }else{ var name_buyer = json_plantation[c].properties.name_buyer; }
			if(json_plantation[c].properties.code_buyer == null){ var code_buyer=''; }else{ var code_buyer = json_plantation[c].properties.code_buyer; }
			if(json_plantation[c].properties.code_town == null){ var code_town=''; }else{ var code_town = json_plantation[c].properties.code_town; }

			if(idview == 1){
				var popup_buyer = name_buyer;
				var popup_town = name_town;
			} else {
				var popup_buyer = code_buyer;
				var popup_town = code_town;
			}

			var popupContent = "<div style=\"max-width:400px; max-height: 200px\"><h5 style=\"border-bottom: 1px solid #eee; color:#ed1b2c\"><i class=\"fa fa-check-square fa-fw\" style=\"color:#ed1b2c\"></i><strong>&nbsp;&nbsp;"+lg_collection_point_details+"</strong></h5>"+blanc
				+"<div class=\"icon_desc\" style=\"margin-left:0px;display:block\"><span><i class=\"fa fa-arrows fa-fw\"></i> <strong>  "+lg_plantation_farmer_name+" : </strong>"+name_farmer.substr(0, 5)+"... ("+json_plantation[c].properties.id_contact+")"+blanc
				  +" </span><br><span><i class=\"fa fa-arrows fa-fw\"></i> <strong> "+lg_plantation_farmer_groups+" : </strong>"+name_farmergroup
				  +" </span><br><span><i class=\"fa fa-arrows fa-fw\"></i> <strong> "+lg_plantation_farmer_residence+" : </strong>"+popup_town
				  +" </span><br><span><i class=\"fa fa-arrows fa-fw\"></i> <strong> "+lg_plantation_culture+" : </strong>"+culture
				 +" </span><br><span><i class=\"fa fa-arrows fa-fw\"></i> <strong> "+lg_plantation_area+" : </strong>"+area
				 +" </span><br><span><i class=\"fa fa-arrows fa-fw\"></i> <strong> "+lg_plantation_culture_variety+" : </strong>"+variety
				 +" </span><br><span><i class=\"fa fa-arrows fa-fw\"></i> <strong> "+lg_plantation_buyer+" : </strong>"+popup_buyer
			+" </span><br><br><div style=\"text-align:center\"> <a href=\"javascript:details('"+json_plantation[c].properties.code_farmer+"')\" ><span> </span></a></div></div></div>";


			var mark = L.marker([json_plantation[c].properties.coordx, json_plantation[c].properties.coordy],{icon: pointIcon,riseOnHover:true}).bindPopup(popupContent).openPopup();
			mark.addTo(collection_point_couche);
			map.addLayer(collection_point_couche);
			map.setView([json_plantation[c].properties.coordx, json_plantation[c].properties.coordy], 19);
		}
	}

}


function all_collection_point(id_town) {
	collection_point_couche.clearLayers();
	plantation_farmer.clearLayers();
	highlight_red.clearLayers();

	document.getElementById('sideBarBtnToggle').innerHTML = '<i class="fa fa-caret-right"></i>';
	$('#right-sidebar').removeClass('fadeInLeftBig');
	$('#right-sidebar').addClass('fadeOutLeftBig');
	$('#right-sidebar').addClass('hide');

	$('#sideBarBtnToggle').removeClass("toggleOpen");
	$('#sideBarBtnToggle').removeClass("fadeInLeftBig");

	var x1 = [];
	var y1 = [];

	for (c in json_plantation) {
		if (json_plantation[c].properties.id_town == id_town){

			if((json_plantation[c].properties.coordx != null) && (json_plantation[c].properties.coordy != null)){

				if(json_plantation[c].properties.name_farmer == null){ var name_farmer=''; }else{ var name_farmer = json_plantation[c].properties.name_farmer; }
				if(json_plantation[c].properties.name_farmergroup == null){ var name_farmergroup=''; }else{ var name_farmergroup = json_plantation[c].properties.name_farmergroup; }
				if(json_plantation[c].properties.name_town == null){ var name_town=''; }else{ var name_town = json_plantation[c].properties.name_town; }
				if(json_plantation[c].properties.culture == null){ var culture=''; }else{ var culture = json_plantation[c].properties.culture; }
				if(json_plantation[c].properties.area == null){ var area=''; }else{ var area = json_plantation[c].properties.area; }
				if(json_plantation[c].properties.variety == null){ var variety=''; }else{ var variety = json_plantation[c].properties.variety; }
				if(json_plantation[c].properties.name_buyer == null){ var name_buyer=''; }else{ var name_buyer = json_plantation[c].properties.name_buyer; }
				if(json_plantation[c].properties.code_buyer == null){ var code_buyer=''; }else{ var code_buyer = json_plantation[c].properties.code_buyer; }
				if(json_plantation[c].properties.code_town == null){ var code_town=''; }else{ var code_town = json_plantation[c].properties.code_town; }

				x1.push(json_plantation[c].properties.coordx);
				y1.push(json_plantation[c].properties.coordy);

				if(idview == 1){
					var popup_buyer = name_buyer;
					var popup_town = name_town;
				} else {
					var popup_buyer = code_buyer;
					var popup_town = code_town;
				}

				var popupContent = "<div style=\"max-width:400px; max-height: 200px\"><h5 style=\"border-bottom: 1px solid #eee; color:#ed1b2c\"><i class=\"fa fa-check-square fa-fw\" style=\"color:#ed1b2c\"></i><strong>&nbsp;&nbsp;"+lg_collection_point_details+"</strong></h5>"+blanc
					+"<div class=\"icon_desc\" style=\"margin-left:0px;display:block\"><span><i class=\"fa fa-arrows fa-fw\"></i> <strong>  "+lg_plantation_farmer_name+" : </strong>"+name_farmer.substr(0, 5)+"... ("+json_plantation[c].properties.id_contact+")"+blanc
					  +" </span><br><span><i class=\"fa fa-arrows fa-fw\"></i> <strong> "+lg_plantation_farmer_groups+" : </strong>"+name_farmergroup
					  +" </span><br><span><i class=\"fa fa-arrows fa-fw\"></i> <strong> "+lg_plantation_farmer_residence+" : </strong>"+popup_town
					  +" </span><br><span><i class=\"fa fa-arrows fa-fw\"></i> <strong> "+lg_plantation_culture+" : </strong>"+culture
					 +" </span><br><span><i class=\"fa fa-arrows fa-fw\"></i> <strong> "+lg_plantation_area+" : </strong>"+area
					 +" </span><br><span><i class=\"fa fa-arrows fa-fw\"></i> <strong> "+lg_plantation_culture_variety+" : </strong>"+variety
					 +" </span><br><span><i class=\"fa fa-arrows fa-fw\"></i> <strong> "+lg_plantation_buyer+" : </strong>"+popup_buyer
				+" </span><br><br><div style=\"text-align:center\"> <a href=\"javascript:details('"+json_plantation[c].properties.code_farmer+"')\" ><span> </span></a></div></div></div>";

				var bounds = [[Math.min.apply(null, x1)-0.01 , Math.min.apply(null, y1)-0.01], [Math.max.apply(null, x1)+0.01 , Math.max.apply(null, y1)+0.01]];
				var mark = L.marker([json_plantation[c].properties.coordx, json_plantation[c].properties.coordy],{icon: pointIcon,riseOnHover:true}).bindPopup(popupContent).addTo(collection_point_couche);
				map.addLayer(collection_point_couche);
			}
		}
	}

	map.fitBounds(bounds);

	document.getElementById('toggle_point').innerHTML = '<a href="javascript:hide_collection_point('+id_town+')" class="pull-right" style="margin-top:8px;"><i class="fa fa-map-pin" aria-hidden="true"></i>&nbsp; '+lg_hide_all_collection_points+'</a>';
}


function show_plantation(code) {
	collection_point_couche.clearLayers();
	plantation_farmer.clearLayers();
	highlight_red.clearLayers();

	document.getElementById('sideBarBtnToggle').innerHTML = '<i class="fa fa-caret-right"></i>';
	$('#right-sidebar').removeClass('fadeInLeftBig');
	$('#right-sidebar').addClass('fadeOutLeftBig');
	$('#right-sidebar').addClass('hide');

	$('#sideBarBtnToggle').removeClass("toggleOpen");
	$('#sideBarBtnToggle').removeClass("fadeInLeftBig");

	plantation_couche0.eachLayer(function (layer) {
		if(layer.feature.properties.id_town == code){
			plantation_couche.addData(layer.feature);
			plantation_farmer.addData(layer.feature);
		}
	});

	document.getElementById('toggle_plant').innerHTML = '<a href="javascript:hide_plantation('+code+')" class="pull-right" style="margin-top:8px;"><i class="fa fa-map-marker" aria-hidden="true"></i>&nbsp; '+lg_hide_all_plantations+'</a>';

	map.fitBounds(plantation_couche.getBounds().extend(buyer_couche.getBounds()));
}


function checked_layer(type,valeur){
	if(type == 'sante'){
		if(valeur.checked == true){
			map.addLayer(sante);

		} else {
			map.removeLayer(sante);
		}
	} else

	if(type == 'ecole'){
		if(valeur.checked == true){
			map.addLayer(ecole);

		} else {
			map.removeLayer(ecole);
		}
	} else

	if(type == 'aire_protegee'){
		if(valeur.checked == true){
			map.addLayer(parc_national);
			map.addLayer(reserve);
			map.addLayer(foret_classee);

		} else {
			map.removeLayer(parc_national);
			map.removeLayer(reserve);
			map.removeLayer(foret_classee);
		}
	}

	document.getElementById('sideBarBtnToggle').innerHTML = '<i class="fa fa-caret-right"></i>';
	$('#right-sidebar').removeClass('fadeInLeftBig');
	$('#right-sidebar').addClass('fadeOutLeftBig');
	$('#right-sidebar').addClass('hide');

	$('#sideBarBtnToggle').removeClass("toggleOpen");
	$('#sideBarBtnToggle').removeClass("fadeInLeftBig");
}


function create_circle(centre_x,centre_y,rayon) {
	circle_layer.clearLayers();

	var circle = L.circle([centre_x, centre_y], rayon*1000, {
		color: 'red',
		fillColor: '#f03',
		fillOpacity: 0.2
	}).addTo(circle_layer);

	map.fitBounds(circle_layer.getBounds());
}

var buyer_entite;


function onClick_buyer(e) {
	clear_map_layer();
	var latitude = e.latlng.lat ;
	var longitude = e.latlng.lng ;

	$('#bt_panel').removeClass('hide');
	$('#bt_images').addClass('hide');
	// $('#small-chat').removeClass('hide');

	niveau = 4;
	buyer_entite = e;

	for (i in json_buyer) {
		if ((json_buyer[i].properties.coord_y == longitude) && (json_buyer[i].properties.coord_x == latitude)){
            var pulsingIcon = L.icon.pulse({iconSize:[10,10],color:'yellow'});
			if(idview == 1){ var buyer=json_buyer[i].properties.name_buyer; } else { var buyer=json_buyer[i].properties.initials }
			mark1 =  L.marker([json_buyer[i].properties.coord_x, json_buyer[i].properties.coord_y],{icon: pulsingIcon}).addTo(buyer_couche).on('click', onClick_buyer);
			mark = L.marker([json_buyer[i].properties.coord_x, json_buyer[i].properties.coord_y],{icon: new SweetIcon1({iconUrl: 'images/icon.png',labelText:buyer})   ,riseOnHover:false}).addTo(labels).on('click', onClick_buyer);

			var json_village_group1 =  _.pluck(json_village, 'properties');
			var json_village_group12 = _.where(json_village_group1, {id_buyer: json_buyer[i].properties.id_buyer});
			var json_village_group2 =  _.uniq(json_village_group12, 'id_town');

			$('#right-sidebar').addClass('sidebar-open');
			$('#sideBarBtnToggle').removeClass('hide');

			document.getElementById('sideBarBtnToggle').innerHTML = '<i class="fa fa-caret-left"></i>';
			document.getElementById('sideBarBtnToggle').style.backgroundColor = '#dae456';
			document.getElementById('sideBarSearchBox').style.backgroundColor = '#dae456';

			$('#tab-1,#tab-2,#tab-4').removeClass('active');
			$('.tab1,.tab2,.tab4').removeClass('active');

			$('.tab3').addClass('active');
			$('#tab-3').addClass('active');

			volet_gauche_animated();
			img_elt = json_buyer[i].properties.id_buyer;

			var tz = json_buyer[i].properties.timezone;
			if(tz == 0){ var gmt = '(GMT '+tz+')'; }
			else if(tz > 0){ var gmt = '(GMT +'+tz+')'; }
			else if(tz < 0){ var gmt = '(GMT -'+tz+')'; }
			else{ var gmt =''; }


			var content = '';

			content += '<div style="position: relative">';

			if(json_buyer[i].properties.e0_02logo != null && json_buyer[i].properties.e0_02logo != ''){
				content += '<img src="img/'+json_buyer[i].properties.e0_02logo+'" height="60" style="box-shadow:0 1px 6px 0 rgba(0,0,0,.3);position:absolute;right:20px;margin-top:180px;float:right"/>';
			}

			if(json_buyer[i].properties.e0_01photo != null && json_buyer[i].properties.e0_01photo != ''){
				content += '<img height="200px" src="img/'+json_buyer[i].properties.e0_01photo+'" style="width:100%;"/></div>';
			}

			content +='<div style="background-color: #dae456;color: #fff;padding:10px 20px">'+blanc
				+'<h3>'+buyer+' </h3>'+blanc
				+'<img src="img/'+json_buyer[i].properties.code_country+'.png" height="12" style=""/>&nbsp;<strong>'+json_buyer[i].properties.e0_05+'</strong>'+blanc
				+'<br><div style="display:inline-block"><div class="pull-left">'+lg_local_time+' : &nbsp;&nbsp; </div><div id="time-cont-1" class="pull-left"></div> &nbsp;&nbsp; '+gmt+'</div>'+blanc
				+'</div>'+blanc
			+'<div style="padding:10px 20px; border-bottom:1px solid #e7eaec;width:100%">';

			if(json_buyer[i].properties.e1_01 != null && json_buyer[i].properties.e1_01 != ''){
				content += '<div class="panel panel-default" style="margin-bottom: 0px;padding: 5px 10px;width:100%;opacity:0.8"><h3 style="color:#ffa500"> '+json_buyer[i].properties.e1+'  </h3>'+blanc
				  +json_buyer[i].properties.e1_01+'</div>'+blanc;
			}

			content += '<br><div class="panel panel-default" style="margin-bottom: 0px;padding: 5px 10px;width:100%;opacity:0.8">';

			if(json_buyer[i].properties.e2 != null && json_buyer[i].properties.e2 != ''){
				content += '<h3 style="color:#ffa500"><i class="fa fa-map-marker" aria-hidden="true"></i> &nbsp;'+json_buyer[i].properties.e2+'</h3>'+blanc
				content += '<div style="width:97%; margin-left:3%;">'+blanc
			}

			if(json_buyer[i].properties.e0_03name != null && json_buyer[i].properties.e0_03name != ''){
				content += json_buyer[i].properties.e0_03name;
			}

			if(json_buyer[i].properties.e2_01 != null && json_buyer[i].properties.e2_01 != ''){
				content += '<br>'+json_buyer[i].properties.e2_01;
			}

			if(json_buyer[i].properties.e2_02 != null && json_buyer[i].properties.e2_02 != ''){
				content += '<br>'+json_buyer[i].properties.e2_02;
			}

			if(json_buyer[i].properties.e2_03 != null && json_buyer[i].properties.e2_03 != ''){
				content += '<br>'+json_buyer[i].properties.e2_03;
			}

			if(json_buyer[i].properties.e2_04 != null && json_buyer[i].properties.e2_04 != ''){
				content += '<br>'+json_buyer[i].properties.e2_04;
			}

			if(json_buyer[i].properties.e2_05 != null && json_buyer[i].properties.e2_05 != ''){
				content += '<br>'+json_buyer[i].properties.e2_05;
			}

			content += '</div></div>'+blanc

			+'<br><div class="panel panel-default" style="margin-bottom: 0px;padding: 5px 10px;width:100%;opacity:0.8">';

			if(json_buyer[i].properties.e3 != null && json_buyer[i].properties.e3 != ''){
				content += '<h3 style="color:#ffa500"><i class="fa fa-map-marker" aria-hidden="true"></i>&nbsp;'+json_buyer[i].properties.e3+'</h3>'+blanc
				content += '<div style="width:97%; margin-left:3%;">'+blanc
			}

			if(json_buyer[i].properties.e3_01 != null && json_buyer[i].properties.e3_01 != ''){
			   content += json_buyer[i].properties.e3_01;
			}

			if(json_buyer[i].properties.e3_02 != null && json_buyer[i].properties.e3_02 != ''){
				content += '<br>'+json_buyer[i].properties.e3_02;
			}

			if(json_buyer[i].properties.e3_03 != null && json_buyer[i].properties.e3_03 != ''){
				content += '<br>'+json_buyer[i].properties.e3_03;
			}

			if(json_buyer[i].properties.e3_04 != null && json_buyer[i].properties.e3_04 != ''){
				content += '<br>'+json_buyer[i].properties.e3_04;
			}

			content += '</div></div>'+blanc

			+'<br><div class="panel panel-default" style="margin-bottom: 0px;padding: 5px 10px;width:100%;opacity:0.8">';

			if(json_buyer[i].properties.e4 != null && json_buyer[i].properties.e4 != ''){
				content += '<h3 style="color:#ffa500"><i class="fa fa-map-marker" aria-hidden="true"></i>&nbsp;'+json_buyer[i].properties.e4+'</h3>'+blanc
				content += '<div style="width:97%; margin-left:3%;">'+blanc
			}

			if(json_buyer[i].properties.e4_01 != null && json_buyer[i].properties.e4_01 != ''){
				content += json_buyer[i].properties.e4_01;
			}

			if(json_buyer[i].properties.e4_02 != null && json_buyer[i].properties.e4_02 != ''){
				content += '<br>'+json_buyer[i].properties.e4_02;
			}

			if(json_buyer[i].properties.e4_03 != null && json_buyer[i].properties.e4_03 != ''){
				content += '<br>'+json_buyer[i].properties.e4_03;
			}

			if(json_buyer[i].properties.e4_04 != null && json_buyer[i].properties.e4_04 != ''){
				content += '<br>'+json_buyer[i].properties.e4_04;
			}

			if(json_buyer[i].properties.e4_05 != null && json_buyer[i].properties.e4_05 != ''){
				content += '<br>'+json_buyer[i].properties.e4_05;
			}

			if(json_buyer[i].properties.e4_06 != null && json_buyer[i].properties.e4_06 != ''){
				content += '<br>'+json_buyer[i].properties.e4_06;
			}

			if(json_buyer[i].properties.e4_07 != null && json_buyer[i].properties.e4_07 != ''){
				content += '<br>'+json_buyer[i].properties.e4_07;
			}

			if(json_buyer[i].properties.e4_08 != null && json_buyer[i].properties.e4_08 != ''){
				content += '<br>'+json_buyer[i].properties.e4_08;
			}

			if(json_buyer[i].properties.e4_09 != null && json_buyer[i].properties.e4_09 != ''){
				content += '<br>'+json_buyer[i].properties.e4_09;
			}

			content += '</div></div>'+blanc
			+'</div>';

			document.getElementById('rightInfos').innerHTML = content;
			Localtime(json_buyer[i].properties.timezone);

			var k = 0;
			var arr = [];

			for (j in json_village_group2) {

				if(idview == 1){
					var towns = json_village_group2[j].name_town;
				} else {
					var towns = json_village_group2[j].code_town;
				}

				var pulsingIcon = L.icon.pulse({iconSize:[8,8],color:'green'});
				mark1 = L.marker([json_village_group2[j].x, json_village_group2[j].y],{icon: pulsingIcon}).addTo(buyer_couche).on('click', onClick_village);
				mark = L.marker([json_village_group2[j].x, json_village_group2[j].y],{icon: new SweetIcon1({iconUrl: 'images/icon.png',labelText:towns})   ,riseOnHover:false}).addTo(labels).on('click', onClick_village);

				var start = { x: json_village_group2[j].x , y: json_village_group2[j].y };
				var end = { x: json_buyer[i].properties.coord_x, y: json_buyer[i].properties.coord_y };

				arr[k] = L.polyline([[json_village_group2[j].x, json_village_group2[j].y], [json_buyer[i].properties.coord_x, json_buyer[i].properties.coord_y]], {color: "orange", weight: 2,dashArray: '3'}).addTo(arrow);

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

	imgManagement('id_buyer',img_elt);

    map.fitBounds(buyer_couche.getBounds());
}



var city_entite;

function city(e) {
	clear_map_layer();
	niveau = 3;

	if(idview == 1){
		map.removeLayer(cartodb_light);
		map.addLayer(ggl);
	} else {
		map.addLayer(cartodb_light);
		map.removeLayer(ggl);
	}

	$('#bt_panel').removeClass('hide');
	$('#bt_images').addClass('hide');

	city_entite = e;

	var latitude = e.latlng.lat ;
	var longitude = e.latlng.lng ;

    for (i in json_city) {
		if ((json_city[i].properties.coord_y == longitude) && (json_city[i].properties.coord_x == latitude)){
			var pulsingIcon = L.icon.pulse({iconSize:[10,10],color:'orange'});

			var k = 0;
			var t = 0;

		    var arr = [];
            var x1 = [];
            var y1 = [];

			img_elt = json_city[i].properties.id_city;

			for (j in json_buyer) {
				if(json_buyer[j].properties.id_city == json_city[i].properties.id_city){
					var features = [];

					for (x in json_exporter) {
						if((json_exporter[x].properties.id_exporter == json_buyer[j].properties.id_exporter) && (t == 0)) {
							var pulsingIcon = L.icon.pulse({iconSize:[10,10],color:'blue'});
							var x_exporter = json_exporter[x].properties.coord_x;
							var y_exporter = json_exporter[x].properties.coord_y;

							if(idview == 1){ var exporter=json_exporter[x].properties.name_exporter; } else { var exporter=json_exporter[x].properties.initials; }
							mark1 =  L.marker([json_exporter[x].properties.coord_x, json_exporter[x].properties.coord_y],{icon: pulsingIcon}).addTo(city_couche).on('click', onClick_country_1);
							mark = L.marker([json_exporter[x].properties.coord_x, json_exporter[x].properties.coord_y],{icon: new SweetIcon1({iconUrl: 'images/icon.png',labelText:exporter})   ,riseOnHover:false}).addTo(labels).on('click', onClick_country_1);

							t = 1;
						}
					}

					x1.push(json_buyer[j].properties.coord_x);
					y1.push(json_buyer[j].properties.coord_y);

					if(idview == 1){ var byer=json_buyer[j].properties.name_buyer; } else { var byer=json_buyer[j].properties.initials; }

					var pulsingIcon = L.icon.pulse({iconSize:[10,10],color:'yellow'});
					mark1 =  L.marker([json_buyer[j].properties.coord_x, json_buyer[j].properties.coord_y],{icon: pulsingIcon}).addTo(city_couche).on('click', onClick_buyer);
					mark = L.marker([json_buyer[j].properties.coord_x, json_buyer[j].properties.coord_y],{icon: new SweetIcon1({iconUrl: 'images/icon.png',labelText:byer})   ,riseOnHover:false}).addTo(labels).on('click', onClick_buyer);

					var start = { x: x_exporter , y: y_exporter };
					var end = { x: json_buyer[j].properties.coord_x, y: json_buyer[j].properties.coord_y };
					arr[k] = L.polyline([[json_buyer[j].properties.coord_x, json_buyer[j].properties.coord_y], [x_exporter, y_exporter]], {color: "orange", weight: 2,dashArray: '3'}).addTo(arrow);


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

	imgManagement('id_town',img_elt);

	// volet_gauche_animated();

	document.getElementById('sideBarBtnToggle').innerHTML = '<i class="fa fa-caret-right"></i>';
	$('#right-sidebar').removeClass('fadeInLeftBig');
	$('#right-sidebar').addClass('fadeOutLeftBig');
	$('#right-sidebar').addClass('hide');

	$('#sideBarBtnToggle').removeClass("toggleOpen");
	$('#sideBarBtnToggle').removeClass("fadeInLeftBig");

	// map.fitBounds(city_couche.getBounds().extend(labels.getBounds()));
	map.fitBounds(city_couche.getBounds());
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

			if(id_exporter != 0){
				pays(code,id_culture);

			} else {
				val = niveau_content[niveau].split('??');
				pays(val[0],val[1]);
			}

		} else

		if(niveau == 3){
			onClick_exporter(exporter_entite);

		}  else

		if(niveau == 4){
			if(id_buyer!=''){
				pays(code,id_culture);
			} else {
				city(city_entite);
			}

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
	+'</div>';
}


function voletdroit(param,valeur) {
	$('.nav-tabs li.active').removeClass('active');
	$('.tab1').addClass('active');

	var resurl='listeslies.php?elemid=voletdroit&param='+param+'&valeur='+valeur;
	var xhr = getXhr();

	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){

			leselect = xhr.responseText;

			var val=leselect.split('##');

			if((param==0)&&(valeur==0)){
				document.getElementById('acc').innerHTML = val[0];
				$('#acc').removeClass('hide');
				$('#navi').addClass('hide');

			} else {
				$('#navi').removeClass('hide');
				$('#acc').addClass('hide');
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
var x_prec = '' ;
var y_prec = '' ;

var route = new L.LayerGroup();
map.addLayer(route);

function next_step(step_coord, current_step) {

	$("#loading").show();
	$("#loading1").show();

	var coord_xy = step_coord.split('#')[current_step];

	if (coord_xy != ',,'){
        step_active.clearLayers();
		var coord = coord_xy.split(',');

		step_v = current_step;
		var pulsingIcon = L.icon.pulse({iconSize:[9,9],color:'#e9967a'});

		mark1 = L.marker([coord[0], coord[1]],{icon: pulsingIcon}).addTo(story_couche);
		mark = L.marker([coord[0], coord[1]],{icon: new SweetIcon1({iconUrl: 'images/icon.png',labelText:current_step})   ,riseOnHover:false}).addTo(labels);

		if(step_v != 1){
            map.fitBounds([[coord[0],coord[1]],[x_prec,y_prec]],{maxZoom : 19});


		  // alert(current_step);

		   // alert(JSON.stringify(trace_array));
		   // alert(trace_array[current_step]);
			        if(trace_array.length != 0 && trace_array[current_step] != undefined){
			            for (var i = 0, latlngs = [], len = trace_array[current_step].coordinates.length; i < len; i++) {

							latlngs.push(new L.LatLng(trace_array[current_step].coordinates[i][1], trace_array[current_step].coordinates[i][0]));
						}

					    path = L.polyline(latlngs, {snakingSpeed: 100}).addTo(route);


			            function snake() {
							path.snakeIn();
						}

						path.on('snakestart snake snakeend', function(ev){
							console.log(ev.type);
						});

						snake();

						}


		} else {
			map.fitBounds([[coord[0],coord[1]],[coord[0],coord[1]]],{maxZoom : 19});
			map.setZoom(coord[2]);
		}
	}

	var pulsingIcon_red = L.icon.pulse({iconSize:[14,14],color:'red'});
	mark2 =  L.marker([coord[0], coord[1]],{icon: pulsingIcon_red}).addTo(step_active);

   $("#loading").hide();
   $("#loading1").hide();

	// if((x_prec!='')&&(y_prec!='')){
		// L.Routing.control({
			// waypoints: [
				// L.latLng(x_prec, y_prec),
				// L.latLng(coord[0], coord[1])
			// ]
		// }).addTo(map);
	// }

    x_prec = coord[0] ;
    y_prec = coord[1] ;
}


var trace_array = [];
function histoire(id_histoire){

	$("#loading").show();
	$("#loading1").show();
     trace_array = [];
	clear_map_layer();
	step_v = 0 ;

	map.removeLayer(cartodb_light);
	map.addLayer(ggl);

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

	var trace = L.geoJson(null, { onEachFeature : function (feature, layer) {
		trace_array[layer.feature.properties.no_step] = layer.feature.geometry;
	}});


	$.ajax({
		url: "data/story_path/"+id_histoire+".geojson", //or your url
		success: function(data){
			$.getJSON("data/story_path/"+id_histoire+".geojson", function (data) {
				trace.addData(data);
			});
		},
		error: function(data){
						   // $.getJSON("data/story_path/data.geojson", function (data) {
							  // trace.addData(data);
							// });
		},
	})
}


function bottomBoxes(code_pays,culture) {
	var resurl='listeslies.php?elemid=bottomBoxes&code='+code_pays+'&cult='+culture;
	var xhr = getXhr();

	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 ){
			leselect = xhr.responseText;
			var val = leselect.split('#');

			if(val[2]==""){var box3 = 0;} else { var box3 = val[2];}

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


$.getJSON("data/parc_national.geojson", function (data) {
    parc_national.addData(data);
});

$.getJSON("data/reserve.geojson", function (data) {
    reserve.addData(data);
});

$.getJSON("data/foret_classee.geojson", function (data) {
    foret_classee.addData(data);
});



$.getJSON("data/time_zone.geojson", function (data) {
	time_zone.addData(data);
});

$.getJSON("data/sante.geojson", function (data) {
	sante.addData(data);
});


$.getJSON("data/ecole.geojson", function (data) {
	ecole.addData(data);
});


var overlayMaps = {
    // "Country": pays_couche,
	// "National Parc": parc_national,
	// "National Reserve": reserve,
	// "Classified Area": foret_classee,

	// "PROTECTED AREA": {
       // "&nbsp;&nbsp;<img src='img/parc.png' width='20' height='20'>&nbsp;National Parc": parc_national,
      // "&nbsp;&nbsp;<img src='img/reserve.png' width='20' height='20'>&nbsp;National Reserve": reserve,
      // "&nbsp;&nbsp;<img src='img/classified.png' width='20' height='20'>&nbsp;Classified Area": foret_classee
     // },
	"OTHERS": {
	   "&nbsp;&nbsp;Time zone": time_zone,
       "&nbsp;&nbsp;Day/Night Zones": t
	}
};

if(idview == 1){
	var baseMaps = {
		"CartoDB Light": cartodb_light,
		"Google Map": googlemap,
		"Google Satellite": ggl
	};

} else {
	var baseMaps = {
		"CartoDB Light": cartodb_light,
		"Google Map": googlemap
	};
}



var layerControl = L.control.groupedLayers(baseMaps, overlayMaps).addTo(map);

labels.addTo(map);


function formatState (state) {
	if(state.text == 'Fr'){
		var $state = $('<span style="z-index:100000000"><img width="20" src="img/fr.jpg" class="img-flag" alt="Français"/> Fr</span>');

	} else

	if(state.text == 'En'){
		var $state = $('<span><img width="20" src="img/en.jpg" class="img-flag" alt="English"/> En</span>');

	} else

	if(state.text == 'De'){
		var $state = $('<span><img width="20" src="img/de.jpg" class="img-flag" alt="Deutch"/> De</span>');

	} else

	if(state.text == 'Pt'){
		var $state = $('<span><img width="20" src="img/pt.jpg" class="img-flag" alt="English"/> Pt</span>');
	}

	return $state;
};



$(".chosen-language").select2({
    templateResult: formatState,
    templateSelection: formatState,
	minimumResultsForSearch: Infinity
});


bottomBoxes(0,0);

if(id_exporter != 0){
	pays(code,id_culture);
}
