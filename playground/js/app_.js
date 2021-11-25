
var map = new L.Map('map', {fullscreenControl: true});	

function pays(code)
{ 
	var zoom = 0;

	if(code==0){ 
		var pays_couche = L.geoJson(null, {
			style: function (feature) { 
				if (feature.properties.country == 'Switzerland'){
					return { color: "red", weight: 1, fill: true, opacity: 1 };
		   
				} else if ( feature.properties.country == 'Côte d\'Ivoire' || feature.properties.country == 'Senegal' || feature.properties.country == 'Mozambique' || feature.properties.country == 'Tanzanie' || feature.properties.country == 'Sudan' || feature.properties.country == 'Cambodge' ) {
					return { color: "green", weight:1, fill: true, opacity: 1 };
				
				} else {
					return { color: "none", weight:1, fill: false, opacity: 1 };
				}
			}

		}).addTo(map);
		
		fleches();
		capitales(0);
	}
	
	if(code=='ci'){ zoom = 1;
		var pays_couche = L.geoJson(null, {
			style: function (feature) { 
				if ( feature.properties.country == 'Côte d\'Ivoire' ) {
					return { fill: false, weight: 2, opacity: 1, color: "green" };
				
				} else {
					return { color: "none", weight:1, fill: false, opacity: 1 };
				}
			}

		}).addTo(map);

		capitales('ci');
	}
	
	if(code=='mz'){}
	if(code=='sn'){}
	if(code=='sd'){}
	if(code=='tz'){}
	if(code=='kh'){}
	
	$.getJSON("data/pays.geojson", function (data) {
		pays_couche.addData(data);  
		if(zoom!=0){ map.fitBounds(pays_couche.getBounds()); }
	});
}

function fleches() {
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
					weight: 4,
					fill: true,
					opacity: 1
				};
			}
		
		}

	}).addTo(map);

	$.getJSON("data/fleche.geojson", function (data) {
		fleche.addData(data);
		map.fitBounds(fleche.getBounds());
	});
}

function capitales(code) {
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
	
	var capitale = L.geoJson(null, {
		pointToLayer: function (feature, latlng) {
			
			if (feature.properties.pays == 'Suisse'){
				var pulsingIcon = L.icon.pulse({iconSize:[10,10],color:'red'});
			} else {
				var pulsingIcon = L.icon.pulse({iconSize:[8,8],color:'green'});
			}
			
			if(code==0){ mark = L.marker(latlng,{icon: new SweetIcon({iconUrl: 'images/icon.png',labelText: feature.properties.pays})	,riseOnHover:false}).addTo(map); }
			else { mark = L.marker(latlng,{icon: new SweetIcon({iconUrl: 'images/icon.png',labelText: feature.properties.name})	,riseOnHover:false}).addTo(map); }
			
			return  L.marker(latlng,{icon: pulsingIcon});
		}
		
	}).addTo(map);

	$.getJSON("data/capitale.geojson", function (data) {
		capitale.addData(data);
	}); 
}


var overlayMaps = {
	// "Antennes Régionales": bureau_couche
};
 
	
osmAttrib = '&copy; <a href="http://openstreetmap.org/copyright">OpenStreetMap</a> contributors',
osm = L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {maxZoom: 19,minZoom: 1, attribution: osmAttrib});

var BING_KEY = 'AuhiCJHlGzhg93IqUH_oCpl_-ZUrIE6SPftlyGYUvr9Amx5nzA-WqGcPquyFZl4L';
var	cartodb_light = L.tileLayer('http://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}.png', {maxZoom: 19,minZoom: 1,  attribution: 'Positron'}).addTo(map);
var cartodb_dark = L.tileLayer('http://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}.png', {maxZoom: 19,minZoom: 1,  attribution: 'Dark matter'});
var bingLayer = L.tileLayer.bing(BING_KEY);
	
var baseMaps = {
	"CartoDB Light": cartodb_light,
	"CartoDB Dark": cartodb_dark,
	"Bing Satellite": bingLayer
};


L.control.layers(baseMaps, overlayMaps).addTo(map);

$(document).ready(function() {
    setTimeout(function() {
        toastr.options = {
            closeButton: true,
            progressBar: true,
            showMethod: 'slideDown',
            timeOut: 4000
        };
    
		toastr.success('M/Mme Mamadou KONE', 'Bienvenue à iDiscover');

    }, 1300);

	var data1 = [
        [0,4],[1,8],[2,5],[3,10],[4,4],[5,16],[6,5],[7,11],[8,6],[9,11],[10,30],[11,10],[12,13],[13,4],[14,3],[15,3],[16,6]
    ];
    
	var data2 = [
        [0,1],[1,0],[2,2],[3,0],[4,1],[5,3],[6,1],[7,5],[8,2],[9,3],[10,2],[11,1],[12,0],[13,2],[14,8],[15,0],[16,0]
    ];
    
	$("#flot-dashboard-chart").length && $.plot($("#flot-dashboard-chart"), [
        data1, data2
    ],
    {
        series: {
            lines: {
				show: false,
				fill: true
            },
            splines: {
                show: true,
                tension: 0.4,
                lineWidth: 1,
                fill: 0.4
            },
            points: {
                radius: 0,
                show: true
            },
            shadowSize: 2
        },
        
		grid: {
            hoverable: true,
			clickable: true,
            tickColor: "#d5d5d5",
            borderWidth: 1,
            color: '#d5d5d5'
        },
        
		colors: ["#1ab394", "#1C84C6"],
        xaxis:{},
        yaxis: {
            ticks: 4
        },
        tooltip: false
    }
    );

    var doughnutData = [
		{
			value: 300,
			color: "#a3e1d4",
			highlight: "#1ab394",
			label: "App"
        },
        {
            value: 50,
            color: "#dedede",
            highlight: "#1ab394",
            label: "Software"
        },
        {
			value: 100,
            color: "#A4CEE8",
            highlight: "#1ab394",
            label: "Laptop"
        }
    ];

    var doughnutOptions = {
        segmentShowStroke: true,
        segmentStrokeColor: "#fff",
        segmentStrokeWidth: 2,
        percentageInnerCutout: 45, 
        animationSteps: 100,
        animationEasing: "easeOutBounce",
        animateRotate: true,
        animateScale: false
    };

    var ctx = document.getElementById("doughnutChart").getContext("2d");
    var DoughnutChart = new Chart(ctx).Doughnut(doughnutData, doughnutOptions);

    var polarData = [
        {
            value: 300,
            color: "#a3e1d4",
            highlight: "#1ab394",
            label: "App"
        },
        {
            value: 140,
            color: "#dedede",
            highlight: "#1ab394",
            label: "Software"
        },
        {
            value: 200,
            color: "#A4CEE8",
            highlight: "#1ab394",
            label: "Laptop"
        }
    ];

    var polarOptions = {
        scaleShowLabelBackdrop: true,
		scaleBackdropColor: "rgba(255,255,255,0.75)",
        scaleBeginAtZero: true,
        scaleBackdropPaddingY: 1,
        scaleBackdropPaddingX: 1,
        scaleShowLine: true,
        segmentShowStroke: true,
        segmentStrokeColor: "#fff",
        segmentStrokeWidth: 2,
        animationSteps: 100,
        animationEasing: "easeOutBounce",
        animateRotate: true,
        animateScale: false
    };
    
	var ctx = document.getElementById("polarChart").getContext("2d");
    var Polarchart = new Chart(ctx).PolarArea(polarData, polarOptions);
	
    WinMove();
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


$('.ci_mz').click( function(){
	closeAllpaysBtn();
	
	$('#ci').removeClass('hide');
	$('#mz').removeClass('hide');
	
	$('#ci').slideDown("fast");
	$('#mz').slideDown("fast");
	
	doBounce($("#ci"), 3, '10px', 200);   
	doBounce($("#mz"), 3, '10px', 200); 
});
	
$('.ci_kh').click( function(){
	closeAllpaysBtn();
	
	$('#ci').removeClass('hide');
	$('#kh').removeClass('hide');
	
	$('#ci').slideDown("fast");
	$('#kh').slideDown("fast");
	
	doBounce($("#ci"), 3, '10px', 200);   
	doBounce($("#kh"), 3, '10px', 200); 
});

$('.tz_mz').click( function(){
	closeAllpaysBtn();
	
	$('#tz').removeClass('hide');
	$('#mz').removeClass('hide');
	
	$('#tz').slideDown("fast");
	$('#mz').slideDown("fast");
	
	doBounce($("#tz"), 3, '10px', 200);   
	doBounce($("#mz"), 3, '10px', 200); 
});

$('.tz_sd').click( function(){
	closeAllpaysBtn();
	
	$('#tz').removeClass('hide');
	$('#sd').removeClass('hide');
	
	$('#tz').slideDown("fast");
	$('#sd').slideDown("fast");

	doBounce($("#tz"), 3, '10px', 200);   
	doBounce($("#sd"), 3, '10px', 200); 
});
	
$('.sn_sd').click( function(){ 
	closeAllpaysBtn();
	
	$('#sn').removeClass("hide");
	$('#sd').removeClass("hide");
	
	$('#sn').slideDown("fast");
	$('#sd').slideDown("fast");
	
	doBounce($("#sn"), 3, '10px', 200);   
	doBounce($("#sd"), 3, '10px', 200);   
});
	
	
function doBounce(element, times, distance, speed) {
	for(var i = 0; i < times; i++) {
       element.animate({marginTop: '-='+distance}, speed)
           .animate({marginTop: '+='+distance}, speed);
	}        
}

function closeAllpaysBtn()
{
	$('#maptitle').addClass("hide");
	$('#mz').slideUp(400); $('#mz').addClass("hide");
	$('#tz').slideUp(400); $('#tz').addClass("hide");
	$('#sn').slideUp(400); $('#sn').addClass("hide");
	$('#sd').slideUp(400); $('#sd').addClass("hide");
	$('#ci').slideUp(400); $('#ci').addClass("hide");
	$('#kh').slideUp(400); $('#kh').addClass("hide");
}

function refrechSelection()
{
	// pays_couche.clearLayers();
	closeAllpaysBtn();
	map.invalidateSize();
	$('#maptitle').removeClass("hide");
	pays(0);
	
	// map.fitBounds([
		// [-30.1715, -21.5511],
		// [53.41, 115.6861]
	// ]);
}

$('#navbar ul li a').click(function() {
    $('ul li.active').removeClass('active');
    $(this).closest('li').addClass('active');
});	

var control = L.control.zoomBox({
    modal: true, 
});
    
map.addControl(control);

pays(0);