<?php
session_start();

if(!isset($_SESSION['username'])){
	header("Location: login.php");
}

include_once 'common.php';
include_once 'include.php';


// Cloudinary
require 'cloudinary/Cloudinary.php';
require 'cloudinary/Uploader.php';
require 'cloudinary/Api.php';

\Cloudinary::config(array( 
  "cloud_name" => "www-idiscover-live", 
  "api_key" => "582937155511965", 
  "api_secret" => "dZlMbtlOCpES1RpKRgd64uiD-N8" 
));

?>

<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo $lang['PAGE_TITLE']; ?></title>

	<link href="img/icrm_logo-57x57.png" rel="shortcut icon">
	
	<!-- SUMMERNOTE -->
	<link href="css/plugins/summernote/summernote.css" rel="stylesheet">
    <link href="css/plugins/summernote/summernote-bs3.css" rel="stylesheet"> 
	
	<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet"> 
	
    <link href="css/bootstrap.min.css" rel="stylesheet">
	<!-- <link href="font-awesome/css/font-awesome.css" rel="stylesheet"> -->
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <link href="css/plugins/iCheck/custom.css" rel="stylesheet">
	<link href="css/plugins/jasny/jasny-bootstrap.min.css" rel="stylesheet">
    <link href="css/proposal.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">

	<!-- Ladda style -->
    <link href="css/plugins/ladda/ladda-themeless.min.css" rel="stylesheet">
	
	<!-- Clock picker -->
	<link href="css/plugins/clockpicker/clockpicker.css" rel="stylesheet">
	
	<!-- Chosen -->
	<link href="css/plugins/chosen/chosen.css" rel="stylesheet">

	<!-- Text spinners style -->
	<link href="css/plugins/textSpinners/spinners.css" rel="stylesheet">

	<!-- Leaflet -->
	<link rel="stylesheet" href="js/plugins/leaflet/leaflet.css">
	<link rel="stylesheet" href="js/plugins/Leaflet.draw-develop/src/leaflet.draw.css"/>
	<link rel="stylesheet" href="js/plugins/leaflet-locatecontrol-gh-pages/src/L.Control.Locate.css">
	
	<link rel="stylesheet" href="js/plugins/Leaflet.EasyButton-master/src/easy-button.css" />
	<link rel="stylesheet" href="js/plugins/Leaflet.markercluster/MarkerCluster.css" />
	<link rel="stylesheet" href="js/plugins/Leaflet.markercluster/MarkerCluster.Default.css" />
	<link rel="stylesheet" href="js/plugins/Leaflet.groupedlayercontrol-gh-pages/src/leaflet.groupedlayercontrol.css" />
	<link rel="stylesheet" href="js/Leaflet.fullscreen-gh-pages/dist/leaflet.fullscreen.css" />
	
	<link rel="stylesheet" href="js/Leaflet.PolylineMeasure-master/Leaflet.PolylineMeasure.css" />
	<link rel="stylesheet" type="text/css" href="https://rawgit.com/MarcChasse/leaflet.ScaleFactor/master/leaflet.scalefactor.min.css">

	<!-- FooTable -->
    <link href="css/plugins/footable/footable.core.css" rel="stylesheet">
	
	<link href="css/plugins/chartist/chartist.min.css" rel="stylesheet">
	
	<!-- rangeSlider -->
	<link href="css/plugins/ionRangeSlider/ion.rangeSlider.css" rel="stylesheet">
    <link href="css/plugins/ionRangeSlider/ion.rangeSlider.skinFlat.css" rel="stylesheet">
	

	<!-- Material Design Bootstrap -->
    <link href="css/style.css" rel="stylesheet">
	<link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">

	<link href="css/plugins/datapicker/datepicker3.css" rel="stylesheet">

	<!-- Sweet Alert -->
    <link href="css/plugins/sweetalert/sweetalert.css" rel="stylesheet">
	
	<!-- Gantt -->
	<link rel="stylesheet" href="gantt/dhtmlxgantt_last.css" type="text/css" media="screen" charset="utf-8"> 
	<link rel="stylesheet" href="css/dhtmlxscheduler_material.css" type="text/css" charset="utf-8"> 
	
	<!-- Include SmartWizard CSS -->
    <link href="css/plugins/SmartWizard/smart_wizard.css" rel="stylesheet" type="text/css" />

	<link rel="stylesheet" href="js/plugins/jQuery-iviewer/jquery.iviewer.css" />
    <link href="css/crm_5.css" rel="stylesheet">
	
	<!-- Dual Listbox -->
	<link href="css/plugins/dualListbox/bootstrap-duallistbox.min.css" rel="stylesheet">

	<!-- slick carousel-->
	<link href="css/plugins/slick/slick.css" rel="stylesheet">
    <link href="css/plugins/slick/slick-theme.css" rel="stylesheet">
	
	<!-- rangeSlider -->
	<link href="css/plugins/ionRangeSlider/ion.rangeSlider.css" rel="stylesheet">
    <link href="css/plugins/ionRangeSlider/ion.rangeSlider.skinFlat.css" rel="stylesheet">
	
	<!-- Flickity carousel-->
	<link rel="stylesheet" href="https://unpkg.com/flickity@2/dist/flickity.min.css">
	
	<style>
		transform: none;
		
		.contact-tab{height:79vh; overflow-y:auto;}
		
		.gantt_task_cell.week_end{
			background-color: #EFF5FD;
		}
		.gantt_task_row.gantt_selected .gantt_task_cell.week_end{
			background-color: #F8EC9C;
		}
		
		#pass-info{
			width: 97.5%;
			height: 25px;
			border: 1px solid #DDD;
			border-radius: 4px;
			color: #829CBD;
			text-align: center;
			font: 12px/25px Arial, Helvetica, sans-serif;
		}
		#pass-info.weakpass{
			border: 1px solid #FF9191;
			background: #FFC7C7;
			color: #94546E;
			text-shadow: 1px 1px 1px #FFF;
		}
		#pass-info.stillweakpass {
			border: 1px solid #FBB;
			background: #FDD;
			color: #945870;
			text-shadow: 1px 1px 1px #FFF;
		}
		#pass-info.goodpass {
			border: 1px solid #C4EEC8;
			background: #E4FFE4;
			color: #51926E;
			text-shadow: 1px 1px 1px #FFF;
		}
		#pass-info.strongpass {
			border: 1px solid #6ED66E;
			background: #79F079;
			color: #348F34;
			text-shadow: 1px 1px 1px #FFF;
		}
		#pass-info.vrystrongpass {
			border: 1px solid #379137;
			background: #48B448;
			color: #CDFFCD;
			text-shadow: 1px 1px 1px #296429;
		}
		.clockpicker-popover {
			z-index: 999999;
		}
		
		.labelClass{
		  white-space:nowrap;
		  text-shadow: 0 0 0.1em black, 0 0 0.1em black,
				0 0 0.1em black,0 0 0.1em black,0 0 0.1em;
		  color: yellow
		}
		
		.labelClass_notIn{
		  white-space:nowrap;
		  text-shadow: 0 0 0.1em black, 0 0 0.1em black,
				0 0 0.1em black,0 0 0.1em black,0 0 0.1em;
		  color: red
		}
		
		.one_line{
			white-space:nowrap;
			overflow:hidden;
			padding-top:5px; padding-left:5px;
			text-align:left !important;
		}
		
		.dhx_scale_bar{
			line-height: 23px;
		}
		
		.dz-default{
			display:none;
		}
		
		#sysRlAssUerList {
			float: right;
			border-radius: 5px;
			border: 1px solid #DDD;
			padding-left: 5px;
		}
		
		table.small_table th {
			font-size: 12px;
		}
		
		.slick_demo_2 .ibox-content {
            margin: 0 10px;
        }
		
		.slick-slide {
			height: auto;
		}
		
		.poly_box {
			position: absolute;
			border: 2px solid #3388ff;
			padding: 3px 10px;
			z-index: 99;
			border-radius: 5px;
			right: 20%;
			background: #FFF;
			color: #3388ff;
			top: 25px;
		}
	</style>
</head>

<body class="md-skin fixed-nav no-skin-config fixed-sidebar">

	<div id="wrapper">
		<div class="wrapper wrapper-content animated fadeInRight">
			<div class="row">
				<div class="col-lg-12" style="">
				<!--<div id="pageTitle"><div class="h1 m-t-xs text-navy"><span class="loading"></span></div></div>-->
				
					<div class="page" id="db_geolocation">
						<div class="row">
							<div class="col-md-4 col-sm-12 col-xs-12" id="geoFarmers">
								<div class="ibox float-e-margins">
									<div class="ibox-content mailbox-content">
										<div class="file-manager" id="geo_contacts">
											<div style="">
												<div id="custom-search-input">
													<div class="row">
														<div class="col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 10px;">
															<input type="text" class="form-control search" placeholder="Search" />
														</div>
														
														<div class="col-md-6 col-sm-6 col-xs-6">
															<select class="form-control" id="list_projects" onchange="byProjects();">

															</select>
														</div>

														<div class="col-md-6 col-sm-6 col-xs-6 pull-right">
															<select class="form-control" id="list_towns" onchange="byTowns();">

															</select>
														</div>
													</div>
												</div>

												<div id="geolocationSpanner" class="h1 m-t-xs text-navy hide">
													<span class="loading"></span>
												</div>
											</div>
											
											<div style="height:77vh; overflow-y:auto;">
												<ul class="folder-list m-b-md list" id="d4_content" style="padding:0; margin-top:20px;">

												</ul>
											</div>
											<div class="clearfix"></div>
										</div>
									</div>
								</div>
							</div>

							<div class="col-md-8 col-sm-12 col-xs-12 animated fadeInRight" id="geoMap">
								<div class="mail-box-header" style="padding:15px;">
									<div id="farmers_map" style="height:80vh; width:100%;"></div>
									<div id="polyBox" class="poly_box hide"></div>
									
									<div id="rangeSliderBox" class="plantRangeSlider_box">
										<div id="ionrange_equipement"></div>
									</div>
								</div>
							</div>
							
							<div class="dashPlantRightFilterBtn dashPlantRightFilterBtn_width_0 animated bg-success" onclick="showHidePlanFilter();">
								<i class="fas fa-filter"></i>
							</div>
							
							<div id="geoMap_Filter" class="dashPlantRightFilterCtn animated hide">
								<div style="padding: 10px; margin-bottom:20px;">
									<h4 style="text-align: center;">Filter</h4>
									<div class="dashPlantRightFilterCtn_box">
										<div class="i-checks">
											<span> <input type="checkbox" id="plt_filter_bio"> <i></i> <?php echo $lang['DB_PLANT_FILTER_BIO']; ?> </span>
										</div>
									</div>
									
									<div class="dashPlantRightFilterCtn_box">
										<div class="i-checks">
											<span> <input type="checkbox" id="plt_filter_bio_suisse"> <i></i> <?php echo $lang['DB_PLANT_FILTER_BIO_SUISSE']; ?> </span>
										</div>
									</div>
									
									<div class="dashPlantRightFilterCtn_box">
										<div class="i-checks">
											<span> <input type="checkbox" id="plt_filter_rspo"> <i></i> <?php echo $lang['DB_PLANT_FILTER_RSPO']; ?> </span>
										</div>
									</div>
									
									<div class="dashPlantRightFilterCtn_box">
										<div class="i-checks">
											<span> <input type="checkbox" id="plt_filter_fair_trade"> <i></i> <?php echo $lang['DB_PLANT_FILTER_FAIR_TRADE']; ?> </span>
										</div>
									</div>
									
									<div class="dashPlantRightFilterCtn_box">
										<div class="i-checks">
											<span> <input type="checkbox" id="plt_filter_global_gap"> <i></i> <?php echo $lang['DB_PLANT_FILTER_GLOBAL_GAP']; ?> </span>
										</div>
									</div>
									
									<div class="dashPlantRightFilterCtn_box">
										<div class="i-checks">
											<span> <input type="checkbox" id="plt_filter_utz"> <i></i> <?php echo $lang['DB_PLANT_FILTER_UTZ']; ?> </span>
										</div>
									</div>
									
									<div class="dashPlantRightFilterCtn_box">
										<div class="i-checks">
											<span> <input type="checkbox" id="plt_filter_perimeter"> <i></i> <?php echo $lang['DB_PLANT_FILTER_PERIMETER']; ?> </span>
										</div>
									</div>
									
									<div class="dashPlantRightFilterCtn_box">
										<div class="i-checks">
											<span> <input type="checkbox" id="plt_filter_eco_river"> <i></i> <?php echo $lang['DB_PLANT_FILTER_ECO_RIVER']; ?> </span>
										</div>
									</div>
									
									<div class="dashPlantRightFilterCtn_box">
										<div class="i-checks">
											<span> <input type="checkbox" id="plt_filter_eco_shallows"> <i></i> <?php echo $lang['DB_PLANT_FILTER_ECO_SHALLOWS']; ?> </span>
										</div>
									</div>
									
									<div class="dashPlantRightFilterCtn_box">
										<div class="i-checks">
											<span> <input type="checkbox" id="plt_filter_eco_wells"> <i></i> <?php echo $lang['DB_PLANT_FILTER_ECO_WELLS']; ?> </span>
										</div>
									</div>
									
									<div class="dashPlantRightFilterCtn_box">
										<div class="i-checks">
											<span> <input type="checkbox" id="plt_filter_synthetic_fertilizer"> <i></i> <?php echo $lang['DB_PLANT_FILTER_SYNTHETIC_FERTILIZER']; ?> </span>
										</div>
									</div>
									
									<div class="dashPlantRightFilterCtn_box">
										<div class="i-checks">
											<span> <input type="checkbox" id="plt_filter_synthetic_herbicides"> <i></i> <?php echo $lang['DB_PLANT_FILTER_SYNTHETIC_HERBICIDES']; ?> </span>
										</div>
									</div>
									
									<div class="dashPlantRightFilterCtn_box">
										<div class="i-checks">
											<span> <input type="checkbox" id="plt_filter_synthetic_pesticide"> <i></i> <?php echo $lang['DB_PLANT_FILTER_SYNTHETIC_PESTICIDE']; ?> </span>
										</div>
									</div>
									
									<div class="dashPlantRightFilterCtn_box">
										<div class="i-checks">
											<span> <input type="checkbox" id="plt_filter_intercropping"> <i></i> <?php echo $lang['DB_PLANT_FILTER_INTERCROPPING']; ?> </span>
										</div>
									</div>
									
									<div class="dashPlantRightFilterCtn_box">
										<div class="i-checks">
											<span> <input type="checkbox" id="plt_filter_forest"> <i></i> <?php echo $lang['DB_PLANT_FILTER_FOREST']; ?> </span>
										</div>
									</div>
									
									<div class="dashPlantRightFilterCtn_box">
										<div class="i-checks">
											<span> <input type="checkbox" id="plt_filter_sewage"> <i></i> <?php echo $lang['DB_PLANT_FILTER_SEWAGE']; ?> </span>
										</div>
									</div>
									
									<div class="dashPlantRightFilterCtn_box">
										<div class="i-checks">
											<span> <input type="checkbox" id="plt_filter_waste"> <i></i> <?php echo $lang['DB_PLANT_FILTER_WASTE']; ?> </span>
										</div>
									</div>
									
									<div class="dashPlantRightFilterCtn_box">
										<div class="i-checks">
											<span> <input type="checkbox" id="plt_filter_fire"> <i></i> <?php echo $lang['DB_PLANT_FILTER_FIRE']; ?> </span>
										</div>
									</div>
									
									<div class="dashPlantRightFilterCtn_box">
										<div class="i-checks">
											<span> <input type="checkbox" id="plt_filter_irrigation"> <i></i> <?php echo $lang['DB_PLANT_FILTER_IRRIGATION']; ?> </span>
										</div>
									</div>
									
									<div class="dashPlantRightFilterCtn_box">
										<div class="i-checks">
											<span> <input type="checkbox" id="plt_filter_drainage"> <i></i> <?php echo $lang['DB_PLANT_FILTER_DRAINAGE']; ?> </span>
										</div>
									</div>
									
									<div class="dashPlantRightFilterCtn_box">
										<div class="i-checks">
											<span> <input type="checkbox" id="plt_filter_slope"> <i></i> <?php echo $lang['DB_PLANT_FILTER_SLOPE']; ?> </span>
										</div>
									</div>
									
									<div class="dashPlantRightFilterCtn_box">
										<div class="i-checks">
											<span> <input type="checkbox" id="plt_filter_pest"> <i></i> <?php echo $lang['DB_PLANT_FILTER_PEST']; ?> </span>
										</div>
									</div>
									
									<div class="dashPlantRightFilterCtn_box">
										<div class="input-group">
											<span> <?php echo $lang['DB_PLANT_FILTER_RATING']; ?> </span>
											<select class="form-control m-b" id="plt_filter_rating" onchange="showAllPlantations(1);">
												<option value="">---</option>
												<option value="1">1</option>
												<option value="2">2</option>
												<option value="3">3</option>
												<option value="4">4</option>
												<option value="5">5</option>
												<option value="6">6</option>
												<option value="7">7</option>
												<option value="8">8</option>
												<option value="9">9</option>
												<option value="10">10</option>
											</select>
										</div>
									</div>
									
									<div class="dashPlantRightFilterCtn_box">
										<span> <?php echo $lang['DB_PLANT_FILTER_SURFACE_HA']; ?> </span>
										<div class="input-group">
											<input class="form-control" type="number" id="plt_filter_surface_ha"> <span class="input-group-btn"> 
											<button type="button" class="btn btn-primary" onclick="showAllPlantations(1);">Go!</button> </span>
										</div>
									</div>
									
									<div class="dashPlantRightFilterCtn_box">
										<span> <?php echo $lang['DB_PLANT_FILTER_YEAR_CREATION']; ?> </span>
										<div class="input-group">
											<input class="form-control" type="number" id="plt_filter_year_creation"> <span class="input-group-btn"> 
											<button type="button" class="btn btn-primary" onclick="showAllPlantations(1);">Go!</button> </span>
										</div>
									</div>
									
									<div class="dashPlantRightFilterCtn_box">
										<div class="i-checks">
											<span> <input type="checkbox" id="plt_filter_extension"> <i></i> <?php echo $lang['DB_PLANT_FILTER_EXTENSION']; ?> </span>
										</div>
									</div>
									
									<div class="dashPlantRightFilterCtn_box">
										<span> <?php echo $lang['DB_PLANT_FILTER_YEAR_EXTENSION']; ?> </span>
										<div class="input-group">
											<input class="form-control" type="number" id="plt_filter_year_extension"> <span class="input-group-btn"> 
											<button type="button" class="btn btn-primary" onclick="showAllPlantations(1);">Go!</button> </span>
										</div>
									</div>
									
									<div class="dashPlantRightFilterCtn_box">
										<div class="i-checks">
											<span> <input type="checkbox" id="plt_filter_replanting"> <i></i> <?php echo $lang['DB_PLANT_FILTER_REPLANTING']; ?> </span>
										</div>
									</div>
									
									<div class="dashPlantRightFilterCtn_box">
										<span> <?php echo $lang['DB_PLANT_FILTER_YEAR_TO_REPLANT']; ?> </span>
										<div class="input-group">
											<input class="form-control" type="number" id="plt_filter_year_to_replant"> <span class="input-group-btn"> 
											<button type="button" class="btn btn-primary" onclick="showAllPlantations(1);">Go!</button> </span>
										</div>
									</div>
									
									<div class="dashPlantRightFilterCtn_box">
										<div class="i-checks">
											<span> <input type="checkbox" id="plt_filter_road_access"> <i></i> <?php echo $lang['DB_PLANT_FILTER_ROAD_ACCESS']; ?> </span>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="footer fixed" style="margin-left:0;">
		<div class="pull-left">
			<strong>Copyright</strong> <a target="_blanck" href="http://dev4impact.com/">dev4impact ltd.</a> &copy; 2018 - <?php echo date("Y");?>
		</div>

		<div class="pull-right">
			@iCoop.live - Version 1.0
		</div>
	</div>

</div>

<!-- Mainly scripts -->
	<script src="js/plugins/fullcalendar/moment.min.js"></script>
    <script src="js/jquery-2.1.1.js"></script>
    <script src="js/bootstrap.min.js"></script>

	<script>
	/* jquery.form.min.js */
	(function(e){"use strict";if(typeof define==="function"&&define.amd){define(["jquery"],e)}else{e(typeof jQuery!="undefined"?jQuery:window.Zepto)}})(function(e){"use strict";function r(t){var n=t.data;if(!t.isDefaultPrevented()){t.preventDefault();e(t.target).ajaxSubmit(n)}}function i(t){var n=t.target;var r=e(n);if(!r.is("[type=submit],[type=image]")){var i=r.closest("[type=submit]");if(i.length===0){return}n=i[0]}var s=this;s.clk=n;if(n.type=="image"){if(t.offsetX!==undefined){s.clk_x=t.offsetX;s.clk_y=t.offsetY}else if(typeof e.fn.offset=="function"){var o=r.offset();s.clk_x=t.pageX-o.left;s.clk_y=t.pageY-o.top}else{s.clk_x=t.pageX-n.offsetLeft;s.clk_y=t.pageY-n.offsetTop}}setTimeout(function(){s.clk=s.clk_x=s.clk_y=null},100)}function s(){if(!e.fn.ajaxSubmit.debug){return}var t="[jquery.form] "+Array.prototype.join.call(arguments,"");if(window.console&&window.console.log){window.console.log(t)}else if(window.opera&&window.opera.postError){window.opera.postError(t)}}var t={};t.fileapi=e("<input type='file'/>").get(0).files!==undefined;t.formdata=window.FormData!==undefined;var n=!!e.fn.prop;e.fn.attr2=function(){if(!n){return this.attr.apply(this,arguments)}var e=this.prop.apply(this,arguments);if(e&&e.jquery||typeof e==="string"){return e}return this.attr.apply(this,arguments)};e.fn.ajaxSubmit=function(r){function k(t){var n=e.param(t,r.traditional).split("&");var i=n.length;var s=[];var o,u;for(o=0;o<i;o++){n[o]=n[o].replace(/\+/g," ");u=n[o].split("=");s.push([decodeURIComponent(u[0]),decodeURIComponent(u[1])])}return s}function L(t){var n=new FormData;for(var s=0;s<t.length;s++){n.append(t[s].name,t[s].value)}if(r.extraData){var o=k(r.extraData);for(s=0;s<o.length;s++){if(o[s]){n.append(o[s][0],o[s][1])}}}r.data=null;var u=e.extend(true,{},e.ajaxSettings,r,{contentType:false,processData:false,cache:false,type:i||"POST"});if(r.uploadProgress){u.xhr=function(){var t=e.ajaxSettings.xhr();if(t.upload){t.upload.addEventListener("progress",function(e){var t=0;var n=e.loaded||e.position;var i=e.total;if(e.lengthComputable){t=Math.ceil(n/i*100)}r.uploadProgress(e,n,i,t)},false)}return t}}u.data=null;var a=u.beforeSend;u.beforeSend=function(e,t){if(r.formData){t.data=r.formData}else{t.data=n}if(a){a.call(this,e,t)}};return e.ajax(u)}function A(t){function T(e){var t=null;try{if(e.contentWindow){t=e.contentWindow.document}}catch(n){s("cannot get iframe.contentWindow document: "+n)}if(t){return t}try{t=e.contentDocument?e.contentDocument:e.document}catch(n){s("cannot get iframe.contentDocument: "+n);t=e.document}return t}function k(){function f(){try{var e=T(v).readyState;s("state = "+e);if(e&&e.toLowerCase()=="uninitialized"){setTimeout(f,50)}}catch(t){s("Server abort: ",t," (",t.name,")");_(x);if(w){clearTimeout(w)}w=undefined}}var t=a.attr2("target"),n=a.attr2("action"),r="multipart/form-data",u=a.attr("enctype")||a.attr("encoding")||r;o.setAttribute("target",p);if(!i||/post/i.test(i)){o.setAttribute("method","POST")}if(n!=l.url){o.setAttribute("action",l.url)}if(!l.skipEncodingOverride&&(!i||/post/i.test(i))){a.attr({encoding:"multipart/form-data",enctype:"multipart/form-data"})}if(l.timeout){w=setTimeout(function(){b=true;_(S)},l.timeout)}var c=[];try{if(l.extraData){for(var h in l.extraData){if(l.extraData.hasOwnProperty(h)){if(e.isPlainObject(l.extraData[h])&&l.extraData[h].hasOwnProperty("name")&&l.extraData[h].hasOwnProperty("value")){c.push(e('<input type="hidden" name="'+l.extraData[h].name+'">').val(l.extraData[h].value).appendTo(o)[0])}else{c.push(e('<input type="hidden" name="'+h+'">').val(l.extraData[h]).appendTo(o)[0])}}}}if(!l.iframeTarget){d.appendTo("body")}if(v.attachEvent){v.attachEvent("onload",_)}else{v.addEventListener("load",_,false)}setTimeout(f,15);try{o.submit()}catch(m){var g=document.createElement("form").submit;g.apply(o)}}finally{o.setAttribute("action",n);o.setAttribute("enctype",u);if(t){o.setAttribute("target",t)}else{a.removeAttr("target")}e(c).remove()}}function _(t){if(m.aborted||M){return}A=T(v);if(!A){s("cannot access response document");t=x}if(t===S&&m){m.abort("timeout");E.reject(m,"timeout");return}else if(t==x&&m){m.abort("server abort");E.reject(m,"error","server abort");return}if(!A||A.location.href==l.iframeSrc){if(!b){return}}if(v.detachEvent){v.detachEvent("onload",_)}else{v.removeEventListener("load",_,false)}var n="success",r;try{if(b){throw"timeout"}var i=l.dataType=="xml"||A.XMLDocument||e.isXMLDoc(A);s("isXml="+i);if(!i&&window.opera&&(A.body===null||!A.body.innerHTML)){if(--O){s("requeing onLoad callback, DOM not available");setTimeout(_,250);return}}var o=A.body?A.body:A.documentElement;m.responseText=o?o.innerHTML:null;m.responseXML=A.XMLDocument?A.XMLDocument:A;if(i){l.dataType="xml"}m.getResponseHeader=function(e){var t={"content-type":l.dataType};return t[e.toLowerCase()]};if(o){m.status=Number(o.getAttribute("status"))||m.status;m.statusText=o.getAttribute("statusText")||m.statusText}var u=(l.dataType||"").toLowerCase();var a=/(json|script|text)/.test(u);if(a||l.textarea){var f=A.getElementsByTagName("textarea")[0];if(f){m.responseText=f.value;m.status=Number(f.getAttribute("status"))||m.status;m.statusText=f.getAttribute("statusText")||m.statusText}else if(a){var c=A.getElementsByTagName("pre")[0];var p=A.getElementsByTagName("body")[0];if(c){m.responseText=c.textContent?c.textContent:c.innerText}else if(p){m.responseText=p.textContent?p.textContent:p.innerText}}}else if(u=="xml"&&!m.responseXML&&m.responseText){m.responseXML=D(m.responseText)}try{L=H(m,u,l)}catch(g){n="parsererror";m.error=r=g||n}}catch(g){s("error caught: ",g);n="error";m.error=r=g||n}if(m.aborted){s("upload aborted");n=null}if(m.status){n=m.status>=200&&m.status<300||m.status===304?"success":"error"}if(n==="success"){if(l.success){l.success.call(l.context,L,"success",m)}E.resolve(m.responseText,"success",m);if(h){e.event.trigger("ajaxSuccess",[m,l])}}else if(n){if(r===undefined){r=m.statusText}if(l.error){l.error.call(l.context,m,n,r)}E.reject(m,"error",r);if(h){e.event.trigger("ajaxError",[m,l,r])}}if(h){e.event.trigger("ajaxComplete",[m,l])}if(h&&!--e.active){e.event.trigger("ajaxStop")}if(l.complete){l.complete.call(l.context,m,n)}M=true;if(l.timeout){clearTimeout(w)}setTimeout(function(){if(!l.iframeTarget){d.remove()}else{d.attr("src",l.iframeSrc)}m.responseXML=null},100)}var o=a[0],u,f,l,h,p,d,v,m,g,y,b,w;var E=e.Deferred();E.abort=function(e){m.abort(e)};if(t){for(f=0;f<c.length;f++){u=e(c[f]);if(n){u.prop("disabled",false)}else{u.removeAttr("disabled")}}}l=e.extend(true,{},e.ajaxSettings,r);l.context=l.context||l;p="jqFormIO"+(new Date).getTime();if(l.iframeTarget){d=e(l.iframeTarget);y=d.attr2("name");if(!y){d.attr2("name",p)}else{p=y}}else{d=e('<iframe name="'+p+'" src="'+l.iframeSrc+'" />');d.css({position:"absolute",top:"-1000px",left:"-1000px"})}v=d[0];m={aborted:0,responseText:null,responseXML:null,status:0,statusText:"n/a",getAllResponseHeaders:function(){},getResponseHeader:function(){},setRequestHeader:function(){},abort:function(t){var n=t==="timeout"?"timeout":"aborted";s("aborting upload... "+n);this.aborted=1;try{if(v.contentWindow.document.execCommand){v.contentWindow.document.execCommand("Stop")}}catch(r){}d.attr("src",l.iframeSrc);m.error=n;if(l.error){l.error.call(l.context,m,n,t)}if(h){e.event.trigger("ajaxError",[m,l,n])}if(l.complete){l.complete.call(l.context,m,n)}}};h=l.global;if(h&&0===e.active++){e.event.trigger("ajaxStart")}if(h){e.event.trigger("ajaxSend",[m,l])}if(l.beforeSend&&l.beforeSend.call(l.context,m,l)===false){if(l.global){e.active--}E.reject();return E}if(m.aborted){E.reject();return E}g=o.clk;if(g){y=g.name;if(y&&!g.disabled){l.extraData=l.extraData||{};l.extraData[y]=g.value;if(g.type=="image"){l.extraData[y+".x"]=o.clk_x;l.extraData[y+".y"]=o.clk_y}}}var S=1;var x=2;var N=e("meta[name=csrf-token]").attr("content");var C=e("meta[name=csrf-param]").attr("content");if(C&&N){l.extraData=l.extraData||{};l.extraData[C]=N}if(l.forceSync){k()}else{setTimeout(k,10)}var L,A,O=50,M;var D=e.parseXML||function(e,t){if(window.ActiveXObject){t=new ActiveXObject("Microsoft.XMLDOM");t.async="false";t.loadXML(e)}else{t=(new DOMParser).parseFromString(e,"text/xml")}return t&&t.documentElement&&t.documentElement.nodeName!="parsererror"?t:null};var P=e.parseJSON||function(e){return window["eval"]("("+e+")")};var H=function(t,n,r){var i=t.getResponseHeader("content-type")||"",s=n==="xml"||!n&&i.indexOf("xml")>=0,o=s?t.responseXML:t.responseText;if(s&&o.documentElement.nodeName==="parsererror"){if(e.error){e.error("parsererror")}}if(r&&r.dataFilter){o=r.dataFilter(o,n)}if(typeof o==="string"){if(n==="json"||!n&&i.indexOf("json")>=0){o=P(o)}else if(n==="script"||!n&&i.indexOf("javascript")>=0){e.globalEval(o)}}return o};return E}if(!this.length){s("ajaxSubmit: skipping submit process - no element selected");return this}var i,o,u,a=this;if(typeof r=="function"){r={success:r}}else if(r===undefined){r={}}i=r.type||this.attr2("method");o=r.url||this.attr2("action");u=typeof o==="string"?e.trim(o):"";u=u||window.location.href||"";if(u){u=(u.match(/^([^#]+)/)||[])[1]}r=e.extend(true,{url:u,success:e.ajaxSettings.success,type:i||e.ajaxSettings.type,iframeSrc:/^https/i.test(window.location.href||"")?"javascript:false":"about:blank"},r);var f={};this.trigger("form-pre-serialize",[this,r,f]);if(f.veto){s("ajaxSubmit: submit vetoed via form-pre-serialize trigger");return this}if(r.beforeSerialize&&r.beforeSerialize(this,r)===false){s("ajaxSubmit: submit aborted via beforeSerialize callback");return this}var l=r.traditional;if(l===undefined){l=e.ajaxSettings.traditional}var c=[];var h,p=this.formToArray(r.semantic,c);if(r.data){r.extraData=r.data;h=e.param(r.data,l)}if(r.beforeSubmit&&r.beforeSubmit(p,this,r)===false){s("ajaxSubmit: submit aborted via beforeSubmit callback");return this}this.trigger("form-submit-validate",[p,this,r,f]);if(f.veto){s("ajaxSubmit: submit vetoed via form-submit-validate trigger");return this}var d=e.param(p,l);if(h){d=d?d+"&"+h:h}if(r.type.toUpperCase()=="GET"){r.url+=(r.url.indexOf("?")>=0?"&":"?")+d;r.data=null}else{r.data=d}var v=[];if(r.resetForm){v.push(function(){a.resetForm()})}if(r.clearForm){v.push(function(){a.clearForm(r.includeHidden)})}if(!r.dataType&&r.target){var m=r.success||function(){};v.push(function(t){var n=r.replaceTarget?"replaceWith":"html";e(r.target)[n](t).each(m,arguments)})}else if(r.success){v.push(r.success)}r.success=function(e,t,n){var i=r.context||this;for(var s=0,o=v.length;s<o;s++){v[s].apply(i,[e,t,n||a,a])}};if(r.error){var g=r.error;r.error=function(e,t,n){var i=r.context||this;g.apply(i,[e,t,n,a])}}if(r.complete){var y=r.complete;r.complete=function(e,t){var n=r.context||this;y.apply(n,[e,t,a])}}var b=e("input[type=file]:enabled",this).filter(function(){return e(this).val()!==""});var w=b.length>0;var E="multipart/form-data";var S=a.attr("enctype")==E||a.attr("encoding")==E;var x=t.fileapi&&t.formdata;s("fileAPI :"+x);var T=(w||S)&&!x;var N;if(r.iframe!==false&&(r.iframe||T)){if(r.closeKeepAlive){e.get(r.closeKeepAlive,function(){N=A(p)})}else{N=A(p)}}else if((w||S)&&x){N=L(p)}else{N=e.ajax(r)}a.removeData("jqxhr").data("jqxhr",N);for(var C=0;C<c.length;C++){c[C]=null}this.trigger("form-submit-notify",[this,r]);return this};e.fn.ajaxForm=function(t){t=t||{};t.delegation=t.delegation&&e.isFunction(e.fn.on);if(!t.delegation&&this.length===0){var n={s:this.selector,c:this.context};if(!e.isReady&&n.s){s("DOM not ready, queuing ajaxForm");e(function(){e(n.s,n.c).ajaxForm(t)});return this}s("terminating; zero elements found by selector"+(e.isReady?"":" (DOM not ready)"));return this}if(t.delegation){e(document).off("submit.form-plugin",this.selector,r).off("click.form-plugin",this.selector,i).on("submit.form-plugin",this.selector,t,r).on("click.form-plugin",this.selector,t,i);return this}return this.ajaxFormUnbind().bind("submit.form-plugin",t,r).bind("click.form-plugin",t,i)};e.fn.ajaxFormUnbind=function(){return this.unbind("submit.form-plugin click.form-plugin")};e.fn.formToArray=function(n,r){var i=[];if(this.length===0){return i}var s=this[0];var o=this.attr("id");var u=n?s.getElementsByTagName("*"):s.elements;var a;if(u&&!/MSIE [678]/.test(navigator.userAgent)){u=e(u).get()}if(o){a=e(':input[form="'+o+'"]').get();if(a.length){u=(u||[]).concat(a)}}if(!u||!u.length){return i}var f,l,c,h,p,d,v;for(f=0,d=u.length;f<d;f++){p=u[f];c=p.name;if(!c||p.disabled){continue}if(n&&s.clk&&p.type=="image"){if(s.clk==p){i.push({name:c,value:e(p).val(),type:p.type});i.push({name:c+".x",value:s.clk_x},{name:c+".y",value:s.clk_y})}continue}h=e.fieldValue(p,true);if(h&&h.constructor==Array){if(r){r.push(p)}for(l=0,v=h.length;l<v;l++){i.push({name:c,value:h[l]})}}else if(t.fileapi&&p.type=="file"){if(r){r.push(p)}var m=p.files;if(m.length){for(l=0;l<m.length;l++){i.push({name:c,value:m[l],type:p.type})}}else{i.push({name:c,value:"",type:p.type})}}else if(h!==null&&typeof h!="undefined"){if(r){r.push(p)}i.push({name:c,value:h,type:p.type,required:p.required})}}if(!n&&s.clk){var g=e(s.clk),y=g[0];c=y.name;if(c&&!y.disabled&&y.type=="image"){i.push({name:c,value:g.val()});i.push({name:c+".x",value:s.clk_x},{name:c+".y",value:s.clk_y})}}return i};e.fn.formSerialize=function(t){return e.param(this.formToArray(t))};e.fn.fieldSerialize=function(t){var n=[];this.each(function(){var r=this.name;if(!r){return}var i=e.fieldValue(this,t);if(i&&i.constructor==Array){for(var s=0,o=i.length;s<o;s++){n.push({name:r,value:i[s]})}}else if(i!==null&&typeof i!="undefined"){n.push({name:this.name,value:i})}});return e.param(n)};e.fn.fieldValue=function(t){for(var n=[],r=0,i=this.length;r<i;r++){var s=this[r];var o=e.fieldValue(s,t);if(o===null||typeof o=="undefined"||o.constructor==Array&&!o.length){continue}if(o.constructor==Array){e.merge(n,o)}else{n.push(o)}}return n};e.fieldValue=function(t,n){var r=t.name,i=t.type,s=t.tagName.toLowerCase();if(n===undefined){n=true}if(n&&(!r||t.disabled||i=="reset"||i=="button"||(i=="checkbox"||i=="radio")&&!t.checked||(i=="submit"||i=="image")&&t.form&&t.form.clk!=t||s=="select"&&t.selectedIndex==-1)){return null}if(s=="select"){var o=t.selectedIndex;if(o<0){return null}var u=[],a=t.options;var f=i=="select-one";var l=f?o+1:a.length;for(var c=f?o:0;c<l;c++){var h=a[c];if(h.selected){var p=h.value;if(!p){p=h.attributes&&h.attributes.value&&!h.attributes.value.specified?h.text:h.value}if(f){return p}u.push(p)}}return u}return e(t).val()};e.fn.clearForm=function(t){return this.each(function(){e("input,select,textarea",this).clearFields(t)})};e.fn.clearFields=e.fn.clearInputs=function(t){var n=/^(?:color|date|datetime|email|month|number|password|range|search|tel|text|time|url|week)$/i;return this.each(function(){var r=this.type,i=this.tagName.toLowerCase();if(n.test(r)||i=="textarea"){this.value=""}else if(r=="checkbox"||r=="radio"){this.checked=false}else if(i=="select"){this.selectedIndex=-1}else if(r=="file"){if(/MSIE/.test(navigator.userAgent)){e(this).replaceWith(e(this).clone(true))}else{e(this).val("")}}else if(t){if(t===true&&/hidden/.test(r)||typeof t=="string"&&e(this).is(t)){this.value=""}}})};e.fn.resetForm=function(){return this.each(function(){if(typeof this.reset=="function"||typeof this.reset=="object"&&!this.reset.nodeType){this.reset()}})};e.fn.enable=function(e){if(e===undefined){e=true}return this.each(function(){this.disabled=!e})};e.fn.selected=function(t){if(t===undefined){t=true}return this.each(function(){var n=this.type;if(n=="checkbox"||n=="radio"){this.checked=t}else if(this.tagName.toLowerCase()=="option"){var r=e(this).parent("select");if(t&&r[0]&&r[0].type=="select-one"){r.find("option").selected(false)}this.selected=t}})};e.fn.ajaxSubmit.debug=false})
	</script>

	<script src="js/plugins/Simple-Ajax-Uploader-master/SimpleAjaxUploader.js"></script>
	<script src="js/list.min.js"></script>
	<script src="js/plugins/wow/wow.min.js"></script>
	<script src="js/plugins/dropzone/dropzone.js"></script>

    <script src="js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

	<!-- jquery UI -->
    <script src="js/plugins/jquery-ui/jquery-ui.min.js"></script>
	
	<!-- Clock picker -->
    <script src="js/plugins/clockpicker/clockpicker.js"></script>
	
	<!-- Chosen -->
    <script src="js/plugins/chosen/chosen.jquery.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="js/inspinia.js"></script>
    <script src="js/plugins/pace/pace.min.js"></script> 

	<!-- ChartJS-->
    <script src="js/plugins/chartJs/Chart.min.js"></script>

	<!-- Chartist -->
    <script src="js/plugins/chartist/chartist.min.js"></script>
	
    <!-- Peity -->
    <script src="js/plugins/peity/jquery.peity.min.js"></script>
	
    <!-- Peity demo 
    <script src="js/demo/peity-demo.js"></script> -->

	<script src="js/plugins/toastr/toastr.min.js"></script>

	<!-- Leaflet -->
	<script src="js/plugins/leaflet/leaflet.js"></script>
	<script src="js/plugins/Leaflet.label-master/src/Marker.Label.js"></script>
	<script src="js/plugins/leaflet-locatecontrol-gh-pages/src/L.Control.Locate.js"></script>

	<!-- Leaflet draw-master -->
	<script src="js/plugins/Leaflet.draw-develop/src/Leaflet.draw.js"></script>
    <script src="js/plugins/Leaflet.draw-develop/src/Leaflet.Draw.Event.js"></script>

    <script src="js/plugins/Leaflet.draw-develop/src/Toolbar.js"></script>
    <script src="js/plugins/Leaflet.draw-develop/src/Tooltip.js"></script>

    <script src="js/plugins/Leaflet.draw-develop/src/draw/DrawToolbar.js"></script>
    <script src="js/plugins/Leaflet.draw-develop/src/draw/handler/Draw.Feature.js"></script>
    <script src="js/plugins/Leaflet.draw-develop/src/draw/handler/Draw.SimpleShape.js"></script>
    <script src="js/plugins/Leaflet.draw-develop/src/draw/handler/Draw.Polyline.js"></script>
    <script src="js/plugins/Leaflet.draw-develop/src/draw/handler/Draw.Marker.js"></script>
    <script src="js/plugins/Leaflet.draw-develop/src/draw/handler/Draw.Circle.js"></script>
    <script src="js/plugins/Leaflet.draw-develop/src/draw/handler/Draw.CircleMarker.js"></script>
    <script src="js/plugins/Leaflet.draw-develop/src/draw/handler/Draw.Polygon.js"></script>
    <script src="js/plugins/Leaflet.draw-develop/src/draw/handler/Draw.Rectangle.js"></script>

	<script src="js/plugins/Leaflet.draw-develop/src/ext/GeometryUtil.js"></script>
    <script src="js/plugins/Leaflet.draw-develop/src/ext/LatLngUtil.js"></script>
    <script src="js/plugins/Leaflet.draw-develop/src/ext/LineUtil.Intersect.js"></script>
    <script src="js/plugins/Leaflet.draw-develop/src/ext/Polygon.Intersect.js"></script>
    <script src="js/plugins/Leaflet.draw-develop/src/ext/Polyline.Intersect.js"></script>
    <script src="js/plugins/Leaflet.draw-develop/src/ext/TouchEvents.js"></script>

    <script src="js/plugins/Leaflet.draw-develop/src/edit/EditToolbar.js"></script>
    <script src="js/plugins/Leaflet.draw-develop/src/edit/handler/EditToolbar.Edit.js"></script>
    <script src="js/plugins/Leaflet.draw-develop/src/edit/handler/EditToolbar.Delete.js"></script>

    <script src="js/plugins/Leaflet.draw-develop/src/Control.Draw.js"></script>

    <script src="js/plugins/Leaflet.draw-develop/src/edit/handler/Edit.Poly.js"></script>
    <script src="js/plugins/Leaflet.draw-develop/src/edit/handler/Edit.SimpleShape.js"></script>
    <script src="js/plugins/Leaflet.draw-develop/src/edit/handler/Edit.Rectangle.js"></script>
    <script src="js/plugins/Leaflet.draw-develop/src/edit/handler/Edit.Marker.js"></script>
    <script src="js/plugins/Leaflet.draw-develop/src/edit/handler/Edit.CircleMarker.js"></script>
    <script src="js/plugins/Leaflet.draw-develop/src/edit/handler/Edit.Circle.js"></script>
	
	<script src="js/plugins/Leaflet.EasyButton-master/src/easy-button.js"></script>
	<script src="js/plugins/Leaflet.markercluster/leaflet.markercluster-src.js"></script>
	<script src="js/plugins/Leaflet.groupedlayercontrol-gh-pages/src/leaflet.groupedlayercontrol.js"></script>
	
	<script src="js/Leaflet.RotatedMarker-master/leaflet.rotatedMarker.js"></script>
	<script src="js/Leaflet.fullscreen-gh-pages/dist/Leaflet.fullscreen.min.js"></script>
	<script src="js/Leaflet.PolylineMeasure-master/Leaflet.PolylineMeasure.js"></script>
	<script src="https://rawgit.com/MarcChasse/leaflet.ScaleFactor/master/leaflet.scalefactor.min.js"></script>
	
	<!-- Google map -->
	<script src="https://maps.google.com/maps/api/js?libraries=geometry,places&key=AIzaSyBcOXamzcMVv4w0sCQBnXFaFjVwrL4k73E"></script>
	<script src="js/plugins/leaflet-plugins-master/layer/tile/Google1.js"></script>
	
	<script src="js/turf.min.js"></script>

	<!-- Jasny -->
    <script src="js/plugins/jasny/jasny-bootstrap.min.js"></script>

	<script>
        // SideNav init
        // $(".button-collapse").sideNav();
        // var el = document.querySelector('.custom-scrollbar');
        // Ps.initialize(el);
    </script>

	<!-- jQuery UI custom -->
	<script src="js/jquery-ui.custom.min.js"></script>

	<!-- iCheck -->
	<script src="js/plugins/iCheck/icheck.min.js"></script>

	<!-- Full Calendar 
	<script src="js/plugins/fullcalendar/fullcalendar.min.js"></script> -->
	
	
	<script type="text/javascript">
		$(document).ready(function() {
			$('.i-checks').iCheck({
				checkboxClass: 'icheckbox_square-green',
				radioClass: 'iradio_square-green'
			});
		});
	
		$(document).ready(function() {
			var password1 		= $('#password1'); //id of first password field
			var password2		= $('#password2'); //id of second password field
			var passwordsInfo 	= $('#pass-info'); //id of indicator element
			
			passwordStrengthCheck(password1,password2,passwordsInfo); //call password check function
			
		});

		function passwordStrengthCheck(password1, password2, passwordsInfo)
		{
			//Must contain 5 characters or more
			var WeakPass = /(?=.{5,}).*/; 
			//Must contain lower case letters and at least one digit.
			var MediumPass = /^(?=\S*?[a-z])(?=\S*?[0-9])\S{5,}$/; 
			//Must contain at least one upper case letter, one lower case letter and one digit.
			var StrongPass = /^(?=\S*?[A-Z])(?=\S*?[a-z])(?=\S*?[0-9])\S{5,}$/; 
			//Must contain at least one upper case letter, one lower case letter and one digit.
			var VryStrongPass = /^(?=\S*?[A-Z])(?=\S*?[a-z])(?=\S*?[0-9])(?=\S*?[^\w\*])\S{5,}$/; 
			
			$(password1).on('keyup', function(e) {
				if(VryStrongPass.test(password1.val()))
				{
					passwordsInfo.removeClass().addClass('vrystrongpass').html("Very Strong! (Awesome, please don't forget your pass now!)");
				}	
				else if(StrongPass.test(password1.val()))
				{
					passwordsInfo.removeClass().addClass('strongpass').html("Strong! (Enter special chars to make even stronger");
				}	
				else if(MediumPass.test(password1.val()))
				{
					passwordsInfo.removeClass().addClass('goodpass').html("Good! (Enter uppercase letter to make strong)");
				}
				else if(WeakPass.test(password1.val()))
				{
					passwordsInfo.removeClass().addClass('stillweakpass').html("Still Weak! (Enter digits to make good password)");
				}
				else
				{
					passwordsInfo.removeClass().addClass('weakpass').html("Very Weak! (Must be 5 or more chars)");
				}
			});
			
			$(password2).on('keyup', function(e) {
				
				if(password1.val() !== password2.val())
				{
					passwordsInfo.removeClass().addClass('weakpass').html("Passwords do not match!");	
				}else{
					passwordsInfo.removeClass().addClass('goodpass').html("Passwords match!");	
				}
					
			});
		}
	</script>

	<!-- Data picker -->
	<script src="js/plugins/datapicker/bootstrap-datepicker.js"></script>
	
	<!-- Steps 
    <script src="js/plugins/steps/jquery.steps.min.js"></script> -->

	<!-- Jquery Validate 
    <script src="js/plugins/validate/jquery.validate.min.js"></script> -->
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.5/validator.min.js"></script>
	
	<!-- Gantt -->
	<script src="gantt/dhtmlxgantt.js" type="text/javascript" charset="utf-8"></script>
	
	<!-- d3 and c3 charts 
    <script src="js/plugins/d3/d3.min.js"></script>
    <script src="js/plugins/c3/c3.min.js"></script>
	
	<!-- Sweet alert -->
    <script src="js/plugins/sweetalert/sweetalert.min.js"></script>

	<!-- Include SmartWizard JavaScript source -->
    <script type="text/javascript" src="js/plugins/SmartWizard/jquery.smartWizard.min.js"></script>
	
	<!-- FooTable -->
    <script src="js/plugins/footable/footable.all.min.js"></script>
	
	<!-- IonRangeSlider -->
    <script src="js/plugins/ionRangeSlider/ion.rangeSlider.min.js"></script>
	
	<!-- Typehead -->
    <script src="js/plugins/typehead/bootstrap3-typeahead.min.js"></script>
	
	<!-- SUMMERNOTE -->
    <script src="js/plugins/summernote/summernote.min.js"></script>
	
	<!-- Include the Quill library -->
	<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
	
	<script type="text/javascript" src="js/plugins/jQuery-iviewer/test/jquery.mousewheel.min.js"></script>
    <script type="text/javascript" src="js/plugins/jQuery-iviewer/jquery.iviewer.js" ></script>
	
	<!-- clipboard -->
	<script src="js/clipboard.min.js"></script>
	
	<!-- slick carousel-->
    <script src="js/plugins/slick/slick.min.js"></script>
	
	<!-- Flickity carousel-->
	<script src="https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js"></script>
	
	<!-- Dual Listbox -->
    <script src="js/plugins/dualListbox/jquery.bootstrap-duallistbox.js"></script> 
	
	<script src="js/dhtmlxscheduler.js" type="text/javascript" charset="utf-8"></script>
	<script src="js/dhtmlxscheduler_minical.js" type="text/javascript" charset="utf-8"></script>
	
	<script src="js/dhtmlxscheduler_timeline.js" type="text/javascript" charset="utf-8"></script>
	<script src="js/dhtmlxscheduler_treetimeline.js" type="text/javascript" charset="utf-8"></script>
	
	<!-- IonRangeSlider -->
    <script src="js/plugins/ionRangeSlider/ion.rangeSlider.min.js"></script>
	
	<!-- Ladda -->
    <script src="js/plugins/ladda/spin.min.js"></script>
    <script src="js/plugins/ladda/ladda.min.js"></script>
    <script src="js/plugins/ladda/ladda.jquery.min.js"></script>
	
	<!-- custom Js -->
	<script src="js/plantations.js?v=1.2.2"></script>  
	
</body>

</html>