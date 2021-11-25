<?php

include_once 'common.php';

if(!isset($_SESSION['username'])){
	header("Location: login.php");
}

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
	
	<!-- SUMMERNOTE 
	<link href="css/plugins/summernote/summernote.css" rel="stylesheet">
    <link href="css/plugins/summernote/summernote-bs3.css" rel="stylesheet">  -->
	
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
	
	<!-- c3 Charts  
    <link href="css/plugins/c3/c3.min.css" rel="stylesheet"> -->
	
	<link href="css/plugins/chartist/chartist.min.css" rel="stylesheet">
	
	<!-- rangeSlider -->
	<link href="css/plugins/ionRangeSlider/ion.rangeSlider.css" rel="stylesheet">
    <link href="css/plugins/ionRangeSlider/ion.rangeSlider.skinFlat.css" rel="stylesheet">
	
	<!-- Datatable -->
	<link href="css/plugins/dataTables/datatables.min.css" rel="stylesheet">
	
	<!-- Full Calendar 
	<link href="css/plugins/fullcalendar/fullcalendar.css" rel="stylesheet">
    <link href="css/plugins/fullcalendar/fullcalendar.print.css" rel='stylesheet' media='print'> -->

	<!-- Material Design Bootstrap -->
    <link href="css/style.css" rel="stylesheet">
	<link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">
	
	<!-- <link href="css/plugins/steps/jquery.steps.css" rel="stylesheet"> -->
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
		
		.ql-container {
			height: 250px;
		}
		
		.poly_box {
			position: absolute;
			border: 2px solid #3388ff;
			padding: 3px 10px;
			z-index: 99;
			border-radius: 5px;
			left: 40%;
			background: #FFF;
			color: #3388ff;
			top: 10px;
		}
	</style>
</head>

<body class="md-skin fixed-nav no-skin-config fixed-sidebar">

<div id="wrapper">

<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element"> <span>
                            <img src="<?php if(file_exists('img/avatar/' . $_SESSION['id_contact'] . ".jpg")) {
								echo 'img/avatar/' . $_SESSION['id_contact'] . ".jpg";
							} else { echo 'img/' . "user.jpg"; }
						?>" id="avatar_prev" class="img-circle" height="48"/>
                             </span>
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold"><?php echo $_SESSION['name']; ?></strong>
                             </span> <span class="text-muted text-xs block"><?php echo $_SESSION['company_name']; ?> <b class="caret"></b></span> </span> </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
                        <li><a href="#" data-toggle="modal" data-target="#modalAvatar"><?php echo $lang['MENU_AVATAR']; ?></a></li>
                        <li><a href="#" data-toggle="modal" data-target="#passwordRestModal"><?php echo $lang['MENU_RESET_PASS']; ?></a></li>
                        <li><a href="#" data-toggle="modal" data-target="#profilModal"><?php echo $lang['MENU_PROFILE']; ?></a></li>
                        <li class="divider"></li>
                        <li><a href="logout.php"><?php echo $lang['MENU_LOGOUT']; ?></a></li>
                    </ul>
                </div>
                <div class="logo-element">
                    CRM
                </div>
            </li>
			<li id="btn_idiscover" class="hide">
                <a href="#" onclick="idiscover();"> <span class="nav-label">iCoop - The Story</span> </a>
            </li>
            <li id="btn_db" class="hide active">
                <a href="#" onclick="work_progress(0);"><i class="fas fa-tachometer-alt"></i> <span class="nav-label"><?php echo $lang['MENU_DASHBOARD']; ?></span><span class="fa arrow"></span></a>
				<ul class="nav nav-second-level collapse">
					<li id="btn_apex" class="hide">
						<!--<a href="https://icoop.live/w/9090/ords/f?p=101:LOGIN_DESKTOP:699665142814:::::" target="_black"><i class="fas fa-chart-bar"></i> <span class="nav-label">Certification</span></a>-->
					</li>
					<?php if($_SESSION['id_contact']==5070){ ?>
					<li id="btn_apex2" class="">
						<a href="#" onclick="apex();"><i class="fas fa-chart-bar"></i> <span class="nav-label">Apex</span></a>
					</li>
					<?php  } ?>
					<li id="btn_wfPgs" class="">
						<a href="#" onclick="work_progress(0);"><i class="fas fa-chart-line"></i> <span class="nav-label"><?php echo $lang['MENU_WORK_PROGRESS']; ?></span></a>
					</li>
					<li id="btn_fields_plantation" class="hide">
						<a href="plantations.php" target="_blanck"><i class="fa fa-map-marker"></i> <span class="nav-label"><?php echo $lang['MENU_GEO_FIELDS']; ?></span></a>
					</li>
					<li id="btn_collectDt" class="hide">
						<a href="#" onclick="dataCollection();"><i class="fa fa-database"></i> <span class="nav-label">Data Collection</span></a>
					</li>
					<li id="btn_amgt" class="hide">
						<a href="#" onclick="agentm();"><i class="fa fa-user"></i> <span class="nav-label"><?php echo $lang['MENU_AGENT_MANAG']; ?></span>  </a>
					</li>
					<?php if(($_SESSION['id_company']==19) OR ($_SESSION['id_company']==14167) OR ($_SESSION['id_supchain_type']==331)
					OR ($_SESSION['id_company']==15064) OR ($_SESSION['id_company']==23103) OR ($_SESSION['id_company']==645)
					OR ($_SESSION['id_company']==646) OR ($_SESSION['id_company']==647) OR ($_SESSION['id_primary_company']==636)
					){ ?>
					<li id="btn_crt_status" class="hide">
						<a href="#" onclick="crt_status(0);"><i class="fas fa-certificate"></i> <span class="nav-label"><?php echo $lang['MENU_CERT_STATUS']; ?></span>  </a>
					</li>
					<?php } ?>
					
					<?php //if(($_SESSION['id_company']==636) OR ($_SESSION['id_company']==645) OR ($_SESSION['id_company']==646) OR ($_SESSION['id_company']==647)){ ?>
					<li id="btn_syst_analysis" class="hide">
						<!--<a href="http://33886.hostserv.eu:9090/ords/icoop/r/warehouse110/fs-agents" target="_black"><i class="fas fa-chart-line"></i> <span class="nav-label"><?php echo $lang['MENU_SYST_ANALYSIS']; ?></span>  </a>-->
						<?php  if($_SESSION['id_company']==636) { ?>
							<a href="https://ic.analysis.icertification.ch/ords/f?p=110:26:1342376819765::::P26_ID_COMPANY1,P26_ID_COMPANY2,P26_ID_COMPANY3:645,646,647" target="_black"><i class="fas fa-chart-line"></i> <span class="nav-label"><?php echo $lang['MENU_SYST_ANALYSIS']; ?></span>  </a>
						<?php } else { ?>
							<a href="https://ic.analysis.icertification.ch/ords/f?p=110:26:1342376819765::::P26_ID_COMPANY1,P26_ID_COMPANY2,P26_ID_COMPANY3:<?php echo $_SESSION['id_company']; ?>,1,1" target="_black"><i class="fas fa-chart-line"></i> <span class="nav-label"><?php echo $lang['MENU_SYST_ANALYSIS']; ?></span>  </a>
						<?php } ?>
					</li>
					<?php// } ?>
				</ul>
			</li>
            <li id="btn_ct" class="hide">
                <a href="#" onclick="contactList(0,0,0,0,0);"><i class="fa fa-phone"></i> <span class="nav-label"><?php echo $lang['MENU_CONTACT']; ?></span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse">
                    <li id="btn_ctp" class="hide">
						<a href="#" onclick="contactList(9,0,0,0,0);"><i class="fas fa-user"></i> <?php echo $lang['MENU_CONTACT_PEOPLE']; ?> <span class="label label-danger pull-right" id="nb_people"></span></a>
					</li>
					<li id="btn_ctf" class="">
						<a href="#" onclick="contactList(115,0,0,0,0);"><i class="fas fa-tractor"></i> <?php echo $lang['MENU_CONTACT_FARMER']; ?> <span class="label label-primary pull-right" id="nb_farmer"></span></a>
					</li>
                    <li id="btn_cto" class="hide">
						<a href="#" onclick="contactList(10,0,0,0,0);"><i class="fas fa-building"></i> <?php echo $lang['MENU_CONTACT_ORGANIZATION']; ?> <span class="label label-warning pull-right" id="nb_organisation"></span></a>
					</li>
                </ul>
            </li>
			<li id="btn_project_mgnt" class="hide">
                <a href="#"><i class="fa fa-chart-pie"></i> <span class="nav-label"><?php echo $lang['MENU_PROJECT']; ?></span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse">
                    <li id="btn_projects" class="hide">
					   <a href="#" onclick="projects();"><i class="fa fa-users"></i> <span class="nav-label"><?php echo $lang['MENU_AGENT_TRACKING']; ?></span>  </a>
					</li>
					<li id="" class="">
						<!--<a href="http://33886.hostserv.eu:9090/ords/f?p=101:LOGIN_DESKTOP:6253862018168:::::" target="_black"><i class="fas fa-certificate"></i> <span class="nav-label">Certification</span></a>-->
						<a href="https://base.icertification.ch/ords/f?p=101:LOGIN_DESKTOP:16257638236424:::::" target="_black"><i class="fas fa-certificate"></i> <span class="nav-label">Certification</span></a>
					</li>
					<li id="" class="">
					   <a href="#" onclick=""><i class="fas fa-chart-line"></i> <span class="nav-label">Monitoring</span>  </a>
					</li>
					<li id="" class="">
					   <a href="#" onclick=""><i class="fas fa-chalkboard-teacher"></i> <span class="nav-label">Training</span>  </a>
					</li>
					<li id="" class="">
					   <a href="#" onclick=""><i class="fas fa-concierge-bell"></i> <span class="nav-label">Ag-Service</span>  </a>
					</li>
					<li id="btn_tasks" class="hide"> 
						<a href="#" onclick="tasks(0);"><i class="fa fa-tasks"></i> <span class="nav-label"><?php echo $lang['MENU_TASKS']; ?></span>  </a>
					</li>
					<li id="btn_cal" class="hide">
						<a href="#" onclick="calendar(0);"><i class="fa fa-calendar"></i> <span class="nav-label"><?php echo $lang['MENU_CALENDAR']; ?></span></a>
					</li>
					<li id="btn_time" class="hide">
						<a href="#" onclick="timeLine();"><i class="fa fa-calendar"></i> <span class="nav-label"><?php echo $lang['MENU_TIME_LINE']; ?></span></a>
					</li>
					<li id="btn_story" class="hide">
					   <a href="#" onclick="storybulding();"><i class="fa fa-folder-open"></i> <span class="nav-label"><?php echo $lang['MENU_STORY_MNGT']; ?></span></a>
					</li>
					<li id="btn_survey" class="hide">
						<a href="#" onclick="survey();"><i class="fas fa-poll-h"></i> <span class="nav-label"><?php echo $lang['MENU_SURVEY']; ?></span></a>
					</li>
					<li id="btn_suv_camp" class="hide">
						<a href="#" onclick="survey_campaign();"><i class="fas fa-mail-bulk"></i> <span class="nav-label"><?php echo $lang['MENU_SURVEY_CAMPAIGN']; ?></span></a>
					</li>
					<li id="btn_suv_camp_result" class="hide">
						<a href="#" onclick="survey_campaign_result();"><i class="fas fa-restroom"></i> <span class="nav-label"><?php echo $lang['MENU_SURVEY_CAMPAIGN_RESLT']; ?></span></a>
					</li>
                </ul>
            </li>
			<li id="btn_finance_mgnt" class="">
				<a href="#"><i class="fas fa-coins"></i> <span class="nav-label">Finance</span><span class="fa arrow"></span></a>
				<ul class="nav nav-second-level collapse">
					<li id="" class="">
					   <a href="#" onclick=""><i class="fas fa-wallet"></i> <span class="nav-label">TopUp Mobile</span>  </a>
					</li>
					<li id="" class="">
					   <a href="#" onclick=""><i class="fas fa-wallet"></i> <span class="nav-label">Payments</span>  </a>
					</li>
					<li id="" class="">
					   <a href="#" onclick=""><i class="fas fa-wallet"></i> <span class="nav-label">Mobile Money</span>  </a>
					</li>
				</ul>
            </li>
            <li id="btn_notes" class="hide">
                <a href="#" onclick="notes();"><i class="fa fa-sticky-note"></i> <span class="nav-label"><?php echo $lang['MENU_NOTES']; ?></span>  </a>
            </li>
            <li id="btn_pref" class="hide">
               <a href="#"><i class="fa fa-thumbs-up"></i> <span class="nav-label">Preferences</span>  </a>
            </li>
			<li id="btn_crm" class="hide">
               <a href="#" onclick="crm_manag(0,0);"><i class="fa fa-cogs"></i> <span class="nav-label"><?php echo $lang['MENU_CRM_CONTRACT']; ?></span>  </a>
            </li>
			<li id="btn_crm2" class="hide">
               <a href="#" onclick="crm_manag2(0);">
					<i class="fa fa-cogs"></i> <span class="nav-label"><?php echo $lang['MENU_CRM_SCHEDULE']; ?> </span>  
					<!--<span class="label label-warning pull-right"><?php// echo $lang['MENU_NEW']; ?></span>-->
			   </a>
            </li>
			<li id="btn_logistic" class="hide">
               <a href="#" onclick="logistique(0,0);"><i class="fa fa-star"></i> <span class="nav-label"><?php echo $lang['MENU_LOGISTICS']; ?></span>  </a>
            </li>
            <li class="hide" id="btn_syst">
                <a href="#"><i class="fa fa-sitemap"></i> <span class="nav-label">System </span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse">
                    <li class="hide" id="btn_register">
						<a href="#">Register <span class="fa arrow"></span></a>
						<ul class="nav nav-third-level">
							<li class="hide" id="btn_syst_values"><a href="#" onclick="regvalues();">Register Values</a></li>
							<li class="hide" id="btn_syst_town"><a href="#" onclick="syst_town();">Towns</a></li>
							<li class="hide" id="btn_syst_cunt"><a href="#" onclick="syst_country();">Country</a></li>
						</ul>
					</li>
					<li class="hide" id="btn_user_manag">
						<a href="#">Manage users<span class="fa arrow"></span></a>
						<ul class="nav nav-third-level">
							<li class="hide" id="btn_user"><a href="#" onclick="users();">Users management</a></li>
							<li class="hide" id="btn_role_def"><a href="#" onclick="roles_def();">Role definition</a></li>
							<li class="hide" id="btn_role_ass"><a href="#" onclick="roles_ass();">Role assignement</a></li>
							<li class="hide" id="btn_role_perm"><a href="#" onclick="permissions();">Role Permission</a></li>
						</ul>
					</li>
					<li class="hide" id="btn_syst_crm">
						<a href="#">System CRM<span class="fa arrow"></span></a>
						<ul class="nav nav-third-level">
							<li class="hide" id="btn_crm_cult"><a href="#" onclick="culture();">Cultures</a></li>
							<li class="hide" id="btn_crm_pdct"><a href="#" onclick="product_exp('exporter');">Products</a></li>
							<!--<li class="hide" id="btn_crm_contract"><a href="#">Contracts</a></li>-->
							<li class="hide" id="btn_crm_relship"><a href="#">Relationships</a></li>
							<li class="hide" id="btn_crm_port"><a href="#" onclick="port_table();">Port</a></li>
							<li class="hide" id="btn_crm_pcosts"><a href="#" onclick="port_costs();">Port costs assignement</a></li>
							<li class="hide" id="btn_crm_pcosts_tbl"><a href="#" onclick="port_costs_tbl();">Port costs table</a></li>
							<li class="hide" id="btn_crm_freights"><a href="#" onclick="crm_freight();">Freight</a></li>
							<li class="hide" id="btn_crm_ship"><a href="#" onclick="crm_ship();">Ship</a></li>
						</ul>
					</li>
					<li class="hide" id="btn_workflow">
						<a href="#">Workflow<span class="fa arrow"></span></a>
						<ul class="nav nav-third-level">
							<li class="" id="btn_wf_process"><a href="#" onclick="wfProcess();">Process</a></li>
							<li class="" id="btn_wf_trigger"><a href="#" onclick="wfTrigger();">Trigger</a></li>
							<li class="" id="btn_wf_action"><a href="#" onclick="wfActions();">Action</a></li>
							<li class="" id="btn_wf_group"><a href="#" onclick="wfGroup();">Group Member</a></li>
						</ul>
					</li>
                </ul>
            </li>
        </ul>

    </div>
</nav>

<div id="page-wrapper" class="gray-bg">
<div class="row border-bottom">
    <nav class="navbar navbar-fixed-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
        </div>

		<div class="navbar-header" style="margin:18px 5px 0 0;">
			<a data-toggle="dropdown" class="dropdown-toggle" href="#" style="color:#fff;">
				<img width="20" src="img/<?php echo $lang['DB_LANG_stat']; ?>.jpg" class="img-flag" alt="<?php echo $lang['DB_LANG']; ?>"/>
                <strong><?php echo $lang['DB_LANG']; ?></strong>
				<b class="caret"></b>
			</a>
            <ul class="dropdown-menu animated fadeInRight m-t-xs" style="margin:-20px 0 0 60px;">
                <li><a href="index.php?lang=fr"><img width="24" src="img/fr.jpg" class="img-flag"/> Français</a></li>
                <li class="divider"></li>
				<li><a href="index.php?lang=en"><img width="24" src="img/en.jpg" class="img-flag"/> English</a></li>
                <li class="divider"></li>
				<li><a href="index.php?lang=de"><img width="24" src="img/de.jpg" class="img-flag"/> Deutsch</a></li>
                <li class="divider"></li>
				<li><a href="index.php?lang=pt"><img width="24" src="img/pt.jpg" class="img-flag"/> Português</a></li>
				<li class="divider"></li>
				<li><a href="index.php?lang=es"><img width="24" src="img/es.jpg" class="img-flag"/> Spanish</a></li>
				<li class="divider"></li>
				<li><a href="index.php?lang=it"><img width="24" src="img/it.jpg" class="img-flag"/> Italian</a></li>
            </ul>
		</div>

        <ul class="nav navbar-top-links navbar-right" style="margin-right:20px;">
            <li>
                <span class="m-r-sm text-muted welcome-message"><?php echo $_SESSION['username']; ?></span>
            </li>

			<?php// echo $mail_counter; ?>
			
			<li class="dropdown">
                <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#"> 
                    <i class="fa fa-bell" id="alertBell"></i>  
					<span class="label label-warning" id="nt_counter"><?php echo $nt_counter; ?></span>
                </a>
                <ul class="dropdown-menu dropdown-alerts" id="nt_list" style="height:250px; overflow-y:auto;">
					<li class="col-xs-12 no-padding">
                        <div class="text-center link-block">
                            <a href="#" onclick="clearAllNotifications('<?php echo $_SESSION['username']; ?>');">
                                <i class="fa fa-bell"></i> <strong> <?php echo $lang['NOTIF_CLEAR_ALL']; ?></strong>
                            </a>
                        </div>
                    </li>
					<li class="divider col-xs-12 no-padding"></li>
					<?php echo $notification_list; ?>
                </ul> 
            </li>
            <li>
                <a href="logout.php">
                    <i class="fas fa-sign-out-alt"></i> <?php echo $lang['MENU_LOGOUT']; ?>
                </a>
            </li>
        </ul>

    </nav>
</div>

<input type="hidden" id="generatedFileName" value="" />
<input type="hidden" id="documentCurrentType" value="" />
<input type="hidden" id="documentCurrentPosition" value="" />

<div class="wrapper wrapper-content animated fadeInRight" style="padding-top:10px;">
    <div class="row">
        <div class="col-lg-12" style="">
			<div id="pageTitle"><div class="h1 m-t-xs text-navy"><span class="loading"></span></div></div>
            <div id="no_data" class="page hide"></div>

			<!-- Dashboard 1 -->
			<div id="db_dashboard_1" class="page hide">
				<div class="row animated fadeInRight">
					<div class="col-lg-8">
						<div class="ibox float-e-margins">
							<div class="ibox-content">
								<div id="db_map" style="width:100%; height:70vh;"></div>
							</div>
						</div>
					</div>
					
					<div class="col-lg-4">
						<div class="row">
							<div class="col-lg-12 col-xs-12">
								<div class="widget p-sm navy-bg" id="vessel_card_head" style="margin-top:0;">
									<div class="row">
										<div class="col-xs-3">
											<i class="fa fa-ship fa-3x"></i>
										</div>
										<div class="col-xs-9 text-right" id="vessel_name_type">
											<h2 class="font-bold" style="margin:5px 0 0 0;">Vessel-Tracking</h2>
										</div>
										<div class="col-xs-12 no-margins" id="vessel_booking_infos"></div>
									</div>
								</div>
								<div class="widget-text-box hide" id="vessel_details">
									
								</div>
							</div>
						</div>
					</div>

				</div>
			</div>
			
			<!-- Dashboard 2 -->
			<div id="db_dashboard_2" class="page hide">
				<div style="text-align: center;height: 40px;line-height: 40px;">
					<div class="col-md-3 col-sm-4 col-xs-4 pull-left">
						<div class="input-group">
							<input type="text" class="form-control typeahead_1" placeholder="<?php echo $lang['GTT_SEARCH']; ?>" name="srch-term" id="srch-term">
							<div class="input-group-btn">
								<button class="btn btn-default" onclick="dashboard(2);" style="padding:9px 12px;">
								<i class="fa fa-search"></i></button>
							</div>
						</div>
					</div>
			
					<button class="btn btn-warning" onclick="toggleMode(this)"><?php echo $lang['GTT_ZOOM_TFIT_BTN']; ?></button>
				</div>
				<div class="row animated fadeInRight">
					<div class="col-lg-12">
						<div class="ibox float-e-margins">
							<div class="ibox-content">
								<div id="gantt_here" style='width:100%; height:100%;'></div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Dashboard 3 -->
			<div id="db_dashboard_3" class="page hide">
				<div class="row animated fadeInRight">
					<div class="col-lg-8">
						<div class="ibox float-e-margins">
							<div class="ibox-content">
								<div class="table-responsive">
									<table class="table table-striped table-bordered table-hover dashboard-analytics" style="font-size:12px;">
										<thead>
											<tr>
												<?php if($_SESSION['id_user_supchain_type'] == 312){
													$week='<th>'.$lang['ANLY_WK'].'</th>';
													$sn_cus='<th>'.$lang['ANLY_SN_CUS'].'</th>';
												} else {
													$week='';
													if($_SESSION['id_supchain_type'] == 112){
														$sn_cus='<th>'.$lang['ANLY_SN_CUS'].'</th>';
													} else
													if($_SESSION['id_supchain_type'] == 113){
														$sn_cus='<th>'.$lang['ANLY_SN_CUS'].'</th>';
													} else { 
														$sn_cus='';
													}
												} ?>
				
												<th>P</th>
												<th><?php echo $lang['ANLY_CLIENT']; ?></th>
												<th>SN</th>
												<?php echo $sn_cus; ?>
												<th>Exp</th>
												<th><?php echo $lang['ANLY_QTY']; ?></th>
												<th><?php echo $lang['ANLY_TYPE']; ?></th>
												<th><?php echo $lang['ANLY_PCODE']; ?></th>
												<th><?php echo $lang['ANLY_ETD']; ?></th>
												<?php echo $week; ?>
												<th>Bk</th>
											</tr>
										</thead>
										<tbody id="analytic_table">
											
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					
					<div class="col-lg-4">
						<div class="row">
							<div class="col-lg-12">
								<div class="ibox float-e-margins">
									<div class="ibox-title">
										<h5>Planned Arrivals</h5>
										<select class="pull-right" onchange="arrivalWeek(this.value);" style="border: 1px solid #EBEBEB;">
											<?php
												for ($x = 1; $x <= 12; $x++) {
													if($x==1){
														echo '<option value="'.$x.'" selected>Week '.$x.'</option>';
													} else {
														echo '<option value="'.$x.'">Week '.$x.'</option>';
													}
												}
											?>
										</select>
									</div>
									<div class="ibox-content">
										<div style="height:180px;" id="aBox">
											<table class="table table-striped table-bordered table-hover" style="font-size:12px;">
												<thead>
													<tr>
														<th>SN</th>
														<th>ETA</th>
														<th>POD</th>
													</tr>
												</thead>
												<tbody id="planned_arrivals">
													
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class="col-lg-12">
								<div class="ibox float-e-margins">
									<div class="ibox-title">
										<h5>Planned Departures</h5>
										<select class="pull-right" onchange="departureWeek(this.value);" style="border: 1px solid #EBEBEB;">
											<?php
												for ($x = 1; $x <= 12; $x++) {
													if($x==1){
														echo '<option value="'.$x.'" selected>Week '.$x.'</option>';
													} else {
														echo '<option value="'.$x.'">Week '.$x.'</option>';
													}
												}
											?>
										</select>
									</div>
									<div class="ibox-content">
										<div id="dBox" style="height:180px;">
											<table class="table table-striped table-bordered table-hover" style="font-size:12px;">
												<thead>
													<tr>
														<th>SN</th>
														<th>ETD</th>
														<th>POL</th>
													</tr>
												</thead>
												<tbody id="planned_departures">
													
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class="col-lg-12">
								<div class="ibox float-e-margins">
									<div class="ibox-title">
										<h5>Planned Loading</h5>
										<select class="pull-right" onchange="loadingWeek(this.value);" style="border: 1px solid #EBEBEB;">
											<?php
												for ($x = 1; $x <= 4; $x++) {
													if($x==1){
														echo '<option value="'.$x.'" selected>Week '.$x.'</option>';
													} else {
														echo '<option value="'.$x.'">Week '.$x.'</option>';
													}
												}
											?>
										</select>
									</div>
									<div class="ibox-content">
										<div id="lBox" style="height:180px;"> 
											<table class="table table-striped table-bordered table-hover" style="font-size:12px;">
												<thead>
													<tr>
														<th>SN</th>
														<th>POL</th>
														<th>Loading date from</th>
													</tr>
												</thead>
												<tbody id="planned_laoding">
													
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					
				</div>
			</div>
			
			<!-- Dashboard 3 -->
			<div id="db_dashboard_4" class="page hide">
				<div class="row animated fadeInRight">
					<div class="col-lg-12">
						<div class="ibox float-e-margins">
							<div class="ibox-content">
								<label style="margin-bottom:0px;"> <input type="checkbox" id="showAllPaid" class="i-checks"> <?php echo $lang['ACC_SHOW_ALL_PAID']; ?> </label>
								<hr style="margin-top:10px; margin-bottom:10px;" />
								<div class="table-responsive">
									<table class="table table-striped table-bordered table-hover dashboard-account" style="font-size:12px;">
										<thead>
											<tr>
												<th><?php echo $lang['ACC_CUS']; ?></th>
												<th><?php echo $lang['ACC_PRODUCT']; ?></th>
												<th><?php echo $lang['ACC_ORDER']; ?></th>
												<th><?php echo $lang['ACC_PO_CUS']; ?></th>
												<th><?php echo $lang['ACC_POL']; ?></th>
												<th><?php echo $lang['ACC_ETA_O_POD']; ?></th>
												<th><?php echo $lang['ACC_ETA_BASEL']; ?></th>
												<th><?php echo $lang['ACC_POD']; ?></th>
												<th><?php echo $lang['ACC_POD_DATE']; ?></th>
												<th><?php echo $lang['ACC_INV1_1DUE']; ?></th>
												<th><?php echo $lang['ACC_INV1_2DUE']; ?></th>
												<th><?php echo $lang['ACC_INV1_1PAID']; ?></th>
												<th><?php echo $lang['ACC_INV1_2PAID']; ?></th>
												<th><?php echo $lang['ACC_INV2_DUE']; ?></th>
												<th><?php echo $lang['ACC_INV2_PAID']; ?></th>
												<th><?php echo $lang['ACC_INV_NOTE']; ?></th>
												<th><?php echo $lang['ACC_WT']; ?></th>
												<th><?php echo $lang['ACC_ALL_PAID']; ?></th>
												<th>--</th>
											</tr>
										</thead> 
										<tbody id="account_table">
											
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					
				</div>
			</div>
			
			
			<!-- Apex -->
			<div id="db_apex" class="page hide">
				<!-- <iframe style="height:80vh; width:100%;" src="https://icoop.live/w/9090/ords/f?p=101:21:4957977340175::NO"></iframe> -->
			</div> 
			
			<!-- Certificate Status -->
			<div id="db_cert_status" class="page hide">
				<div class="row animated fadeInRight">
					<?php if($_SESSION["id_supchain_type"]!=331){ ?>
					<div class="col-md-3 pull-right">
						<select id="workflow_filter" onchange="certStatusFilter(this.value);" class="form-control">
							<?php echo $cooperative_list; ?>
						</select>
					</div>
					<?php } ?>
					
					<div class="col-lg-12">
						<div class="ibox float-e-margins">
							<div class="ibox-content">
								<div class="table-responsive">
									<table class="table table-striped table-bordered table-hover dashboard-certStatus" style="font-size:12px;">
										<thead id="certStatus_thead">
											
										</thead>
										<tbody id="certStatus_table">
											
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<!-- Workflow Progress -->
			<div id="db_workflow_progress" class="page hide">
				<div class="row animated fadeInRight">
				
					<?php if($_SESSION["id_supchain_type"]==114){ ?>
					<div class="col-md-3 pull-right">
						<select id="workflow_filter" onchange="work_progress(this.value);" class="form-control">
							<?php echo $cooperative_list; ?>
						</select>
					</div>
					<?php } ?>
					
					<?php if($_SESSION["id_primary_company"]==636){ ?>
					<div class="col-md-3 pull-right">
						<select id="workflow_filter" onchange="work_progress(this.value);" class="form-control">
							<?php echo $headquarter_list; ?>
						</select>
					</div>
					<?php } ?>
					
					<div class="col-lg-12">
						<div class="ibox float-e-margins">
							<div class="ibox-content wf_charts_box">
								<div>
                                    <span class="no-margins">Workflow Status</span>
                                </div>

                                <div class="m-t-sm">
                                    <div class="row">
										<div class="col-md-4">
											<table class="table table-hover" style="font-size: 13px;">
												<thead>
													<tr>
														<th>Status</th>
														<th>NoFarmers</th>
													</tr>
												</thead>
												<tbody id="wf_status_content">
													<tr>
														<td><?php echo getRegvalues(643, $lang['DB_LANG_stat']); ?></td>
														<td id="db_wf_new"></td>
													</tr>
													<tr>
														<td><?php echo getRegvalues(644, $lang['DB_LANG_stat']); ?></td>
														<td id="db_wf_review"></td>
													</tr>
													<tr>
														<td><?php echo getRegvalues(645, $lang['DB_LANG_stat']); ?></td>
														<td id="db_wf_approved"></td>
													</tr>
													<tr>
														<td><?php echo getRegvalues(663, $lang['DB_LANG_stat']); ?></td>
														<td id="db_wf_contracted"></td>
													</tr>
													<tr>
														<td><?php echo getRegvalues(664, $lang['DB_LANG_stat']); ?></td>
														<td id="db_wf_sensibilisation"></td>
													</tr>
													<tr>
														<td><?php echo getRegvalues(665, $lang['DB_LANG_stat']); ?></td>
														<td id="db_wf_audit"></td>
													</tr>
													<tr>
														<td><?php echo getRegvalues(666, $lang['DB_LANG_stat']); ?></td>
														<td id="db_wf_certified"></td>
													</tr>
													<tr>
														<td>Total</td>
														<td id="db_wf_tt"></td>
													</tr>
												</tbody>
											</table>
                                        </div>
										
                                        <div class="col-md-8">
                                            <div>
												<div id="statusChart" class="ct-minor-sixth"></div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
							
							</div>
						</div>
					</div>
					
					<div class="col-lg-12">
						<div class="ibox float-e-margins">
							<div class="ibox-content wf_charts_box">
								<div>
                                    <span class="no-margins">Gender</span>
                                </div>

                                <div class="m-t-sm">
                                    <div class="row">
										<div class="col-md-6">
                                            <div>
												<div id="genderChart" class="ct-minor-sixth"></div>
                                            </div>
                                        </div>
										
										<div class="col-md-6">
											<table class="table table-hover" style="font-size: 13px;">
												<thead>
													<tr>
														<th>Gender</th>
														<th>Farmers</th>
													</tr>
												</thead>
												<tbody id="wf_gender_content">
													<tr>
														<td>Men</td>
														<td id="db_gender_men"></td>
													</tr>
													<tr>
														<td>Women</td>
														<td id="db_gender_women"></td>
													</tr>
													<tr>
														<td>Total Farmers</td>
														<td id="db_gender_farmers"></td>
													</tr>
												</tbody>
											</table>
                                        </div>
                                    </div>

                                </div>
							
							</div>
						</div>
					</div>
					
					<div class="col-lg-12">
						<div class="ibox float-e-margins">
							<div class="ibox-content wf_charts_box">
								<div>
                                    <span class="no-margins">Certification Status</span>
                                </div>

                                <div class="m-t-sm">
                                    <div class="row">
										<div class="col-md-6">
											<table class="table table-hover" style="font-size: 13px;">
												<thead>
													<tr>
														<th>Certification</th>
														<th>Candidate</th>
														<th>Approved</th>
														<th>Certified</th>
													</tr>
												</thead>
												<tbody id="wf_status_content">
													<tr>
														<td>globalGap</td>
														<td id="db_cert_ggap_cand"></td>
														<td id="db_cert_ggap_appr"></td>
														<td id="db_cert_ggap_cert"></td>
													</tr>
													<tr>
														<td>RSPO</td>
														<td id="db_cert_rspo_cand"></td>
														<td id="db_cert_rspo_appr"></td>
														<td id="db_cert_rspo_cert"></td>
													</tr>
													<tr>
														<td>Bio UE</td>
														<td id="db_cert_bioue_cand"></td>
														<td id="db_cert_bioue_appr"></td>
														<td id="db_cert_bioue_cert"></td>
													</tr>
													<tr>
														<td>Bio Suisse</td>
														<td id="db_cert_bioss_cand"></td>
														<td id="db_cert_bioss_appr"></td>
														<td id="db_cert_bioss_cert"></td>
													</tr>
													<tr>
														<td>Fair Trade</td>
														<td id="db_cert_ftrad_cand"></td>
														<td id="db_cert_ftrad_appr"></td>
														<td id="db_cert_ftrad_cert"></td>
													</tr>
												</tbody>
											</table>
                                        </div>
										
										<div class="col-md-6">
                                            <div>
												<canvas id="certificationChart"></canvas>
                                            </div>
                                        </div>
                                    </div>

                                </div>
							
							</div>
						</div>
					</div>
					
					<div class="col-lg-12">
						<div class="ibox float-e-margins">
							<div class="ibox-content wf_charts_box">
								<div>
                                    <span class="no-margins">Mapping Progress</span>
                                </div>

                                <div class="m-t-sm">
                                    <div class="row">
										<div class="col-md-6">
                                            <div>
												<canvas id="mappingChart"></canvas>
                                            </div>
                                        </div>
										
										<div class="col-md-6">
											<table class="table table-hover" style="font-size: 13px;">
												<thead>
													<tr>
														<th>Mapping</th>
														<th>Plantations</th>
													</tr>
												</thead>
												<tbody id="wf_status_content">
													<tr>
														<td>Plantation</td>
														<td id="db_mp_plantation"></td>
													</tr>
													<tr>
														<td>Surface map</td>
														<td id="db_mp_surface"></td>
													</tr>
													<tr>
														<td>Collection Point</td>
														<td id="db_mp_point"></td>
													</tr>
												</tbody>
											</table>
                                        </div>
                                    </div>

                                </div>
							
							</div>
						</div>
					</div>
					
					<div class="col-lg-12">
						<div class="ibox float-e-margins">
							<div class="ibox-content wf_charts_box">
								<div>
                                    <span class="no-margins">Surfaces par projet</span>
                                </div>

                                <div class="m-t-sm">
                                    <div class="row">
										<div class="col-md-6">
											<table class="table table-hover" style="font-size: 13px;">
												<thead>
													<!--<tr>
														<th>Projet</th>
														<th>Acres</th>
														<th>m2</th>
														<th>Ha</th>
													</tr>
												</thead>-->
												<thead>
													<tr>
														<th>Projet</th>
														<th>Ha</th>
													</tr>
												</thead>
												<tbody id="srf_prj_content"></tbody>
											</table>
                                        </div>
										
										<div class="col-md-6">
                                            <div>
												<canvas id="surfaceByProjectChart"></canvas>
                                            </div>
                                        </div>
                                    </div>

                                </div>
							
							</div>
						</div>
					</div>
				</div>
			</div>
			
			
			<!-- Calendar -->
			<div id="db_calendar" class="page hide">
				<div class="" style="text-align: center;height: 40px;line-height: 40px;">
					<div class="col-md-3 col-sm-4 col-xs-4 pull-left">
						<div class="input-group">
							<select class="form-control" id="calendar_agent" onchange="calendar(this.value);"></select>
						</div>
					</div>
				</div>
				<div class="row animated fadeInDown">
					<div class="col-sm-12">
						<div class="ibox float-e-margins">
							<div class="ibox-content">
								<div id="calendar" style="width:100%; height:auto;"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			
			<!-- Time Line -->
			<div id="db_timeLine" class="page hide">
				<div class="row no-margins">
					<div class="col-md-3 col-sm-4 col-xs-4 no-padding" id="TM_project_box">
						<div class="ibox float-e-margins">
							<div class="ibox-content mailbox-content">
								<div class="file-manager">
									<div class="row" id="projectsTime" style="margin-top:24px;">
										<div class="col-md-12">
											<div class="col-md-3 col-sm-4 col-xs-4" style="position:fixed; left:0; top:30px; margin-left:5px;">
												<div id="custom-search-input" style="margin-top:10px;">
													<div class="input-group col-md-12" id="agentSearch">
														<input type="text" class="form-control input-lg search" placeholder="Search" style="height:30px; margin-bottom:10px; border-radius:5px;" />
													</div>
												</div>
											</div>
										</div>

										<div class="col-md-12" style="overflow-y:auto; height:90vh;">
											<ul class="folder-list m-b-md list" id="projectTime_list" style="padding: 0;">

											</ul>

											<div id="projectTimeLineSpanner" class="h1 m-t-xs text-navy hide">
												<span class="loading"></span>
											</div>
										</div>
									</div>
									<div class="clearfix"></div>
								</div>
							</div>
						</div>
					</div>
					
				
					<div class="col-md-9 col-sm-8 col-xs-8 animated fadeInRight" id="TM_box">
						<div class="row" style="padding-bottom:10px;">
							<div class="col-md-2 hide" id="TM_agent_view_toggler" style="padding-top:10px;">
								<a href="#" onclick="hideTMPjMenu(1);"><i class="fa fa-expand"></i></a>
							</div>
							
							<div class="col-md-2 hide" id="TM_agent_view_toggler2" style="padding-top:10px;">
								<a href="#" onclick="hideTMPjMenu2();"><i class="fa fa-expand"></i></a>
							</div>
							
							<div class="col-md-4 pull-right" id="TM_agent_selector_content">
								
							</div>
						</div>
					
						<div class="row">
							<input value="0" type="hidden" id="selected_TL_projet_id" />
							<input value="0" type="hidden" id="selected_TL_agent_id" />
							
							<div class="col-md-12">
								<div class="ibox float-e-margins" id="TimeBox">
									<div id="scheduler_timeline" class="dhx_cal_container" style='width:100%; height:100%;'>
										<div class="dhx_cal_navline">
											<div class="dhx_cal_prev_button">&nbsp;</div>
											<div class="dhx_cal_next_button">&nbsp;</div>
											<div class="dhx_cal_today_button"></div>
											<div class="dhx_cal_date"></div>
											<div class="dhx_cal_tab hide" name="timeline_tab" style="right:280px;"></div>
											<div class="dhx_cal_tab hide" name="month_tab" style="right:76px;"></div>
										</div>
										<div class="dhx_cal_header">
										</div>
										<div class="dhx_cal_data">
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

				<!-- Survey -->
				<div class="page hide" id="db_survey" style="">
					<div class="row">
						<div class="col-md-3 col-sm-12 col-xs-12 animated bounceInUp" id="">
							<div class="ibox float-e-margins">
								<div class="ibox-content mailbox-content">
									<div class="file-manager" id="templates">
										<div class="row">
											<div class="col-md-10 col-sm-10 col-xs-10" id="sv_TemplateSearch">
												<input type="text" class="form-control search" placeholder="Search" />
											</div>
											
											<div class="col-md-2 col-sm-2 col-xs-2">
												<a href="#" data-target="#surveyModal" data-toggle="modal" onclick="newTemplate();"><i class="fas fa-plus"></i></a>
											</div>
										</div>
										<div style="height:69vh; overflow-y:auto; margin-top: 15px;">
											<ul class="folder-list m-b-md list" id="sv_template_list" style="padding: 0;">

											</ul>
										</div>
										<div class="clearfix"></div>
									</div>
								</div>
							</div>
						</div>
						
						<div class="col-md-5 col-sm-12 col-xs-12 animated bounceInUp" id="">
							<div class="ibox float-e-margins">
								<div class="ibox-content mailbox-content text-right">
									<a href="#" data-target="#surveyModal" data-toggle="modal" onclick="newQuestion();">New Question</a>
									<div style="height:71vh; overflow-y:auto; margin-top: 15px;">
										<table class="table table-hover" style="font-size:13px;">
											<thead>
												<tr>
													<th style="width:7%;">#</th>
													<th style="width:83%;">Questions </th>
													<th style="width:10%;">---</th>
												</tr>
											</thead>

											<tbody id="list_tpQuestions">

											</tbody>
										</table>
									</div>
									<input type="hidden" value="" id="surv_template_id" />
								</div>
							</div>
						</div>
						
						<div class="col-md-4 col-sm-12 col-xs-12 animated bounceInUp" id="">
							<div class="ibox float-e-margins">
								<div class="ibox-content mailbox-content text-right">
									<a href="#" data-target="#surveyModal" data-toggle="modal" onclick="newAnswer();">New Answers</a>
									<div style="height:71vh; overflow-y:auto; margin-top: 15px;">
										<table class="table table-hover" style="font-size:13px;">
											<thead>
												<tr>
													<th style="width:85%;">Answers </th>
													<th style="width:15%;">---</th>
												</tr>
											</thead>

											<tbody id="list_tpAnswers">

											</tbody>
										</table>
									</div>
									<input type="hidden" value="" id="surv_id_surq" />
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<!-- Survey Campaign -->
				<div class="page hide" id="db_survey_campaign" style="">
					<div class="row no-margins">
						<div class="col-md-3 col-sm-4 col-xs-4 animated bounceIn" id="">
							<div class="ibox float-e-margins">
								<div class="ibox-content mailbox-content">
									<div class="file-manager" id="campaign_orgs">
										<div class="row">
											<div id="custom-search-input">
												<div class="col-md-12 col-sm-6 col-xs-6" id="contSearch">
													<input type="text" class="form-control search" placeholder="Search" />
												</div>
											</div>
										</div>
										
										<div style="height:69vh; overflow-y:auto; margin-top: 15px;">
											<ul class="folder-list m-b-md list" id="surv_camp_org" style="padding: 0;">

											</ul>
										</div>
									</div>
								</div>
								
								<div class="clearfix"></div>
							</div>
						</div>
						
						<div class="col-md-9 col-sm-8 col-xs-8 animated fadeInRight" style="padding-right:0;">
							<div class="ibox float-e-margins">
								<div class="ibox-content mailbox-content">
									<div class="row">
										<div class="col-md-6" style="border-right: 1px solid #e7eaec;">
											<div class="file-manager" id="campaign_contacts">
												<div class="row">
													<div id="custom-search-input">
														<div class="col-md-2 col-sm-2 col-xs-2 text-right">
															<input type="checkbox" class="i-checks" value="" name="all_comp" /> 
														</div>
														
														<div class="col-md-10 col-sm-10 col-xs-10">
															<input type="text" class="form-control search" placeholder="Search" />
														</div>
													</div>
												</div>
											
												<div style="height:69.5vh; overflow-y:auto; margin-top:15px;">
													<ul class="folder-list m-b-md list" id="surv_camp_ppl" style="padding: 0;">

													</ul>
													<div id="no_campain_cp"><i class="fas fa-hand-point-left"></i> Select a company</div>
												</div>
											</div>
										</div>
										
										<div class="col-md-6">
											<div class="row">
												<div class="col-md-12">
													<div class="form-group row">	
														<label class="col-sm-2 col-form-label">	To: </label>
														<div class="col-sm-10" id="surv_camp_to"></div>
													</div>
													
													<div class="form-group row">	
														<label class="col-sm-2 col-form-label">	Subject: </label>
														<div class="col-sm-10">
															<input type="text" class="form-control" id="surv_camp_subject" value="Survey Campain" />
														</div>
													</div>
													
													<div class="form-group row">	
														<label class="col-sm-2 col-form-label">	Template: </label>
														<div class="col-sm-10">
															<select class="form-control" id="surv_camp_template"></select>
														</div>
													</div>
												</div>
												
												<div class="col-md-12">
													<div id="surv_camp_content" style="height:320px;"></div>
												</div>
												
												<div class="col-md-12 text-right" style="padding-top: 15px;">
													<button class="ladda-button ladda-button-demo btn btn-primary"  data-style="zoom-in" id="surv_camp_send_btn" onclick="sendCampagn();" disabled><i class="fas fa-paper-plane"></i> Send</button>
													<button class="btn btn-danger" onclick="survCtDiscard();"><i class="fas fa-times"></i> Discard</button>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>	
				</div>
				
				
				<!-- Survey Campaign Results -->
				<div class="page hide" id="db_survey_campaign_results" style="">
					<div class="row no-margins">
						<div class="col-md-3 col-sm-4 col-xs-4 animated bounceIn" id="">
							<div class="ibox float-e-margins">
								<div class="ibox-content mailbox-content">
									<div class="file-manager" id="campaign_rslt_org">
										<div class="row">
											<div id="custom-search-input">
												<div class="col-md-12 col-sm-6 col-xs-6" id="contSearch">
													<input type="text" class="form-control search" placeholder="Search" />
												</div>
											</div>
										</div>
										
										<div style="height:69vh; overflow-y:auto; margin-top: 15px;">
											<ul class="folder-list m-b-md list" id="surv_rslt_org" style="padding: 0;">

											</ul>
										</div>
									</div>
								</div>
								
								<div class="clearfix"></div>
							</div>
						</div>
						
						<div class="col-md-9 col-sm-8 col-xs-8 animated fadeInRight" style="padding-right:0;">
							<div class="ibox float-e-margins">
								<div class="ibox-content mailbox-content">
									<div class="row">
										<div class="col-md-6" style="border-right: 1px solid #e7eaec;">
											<div class="file-manager" id="campaign_rslt_contacts">
												<div class="row">
													<div id="custom-search-input">
														<div class="col-md-12 col-sm-12 col-xs-12">
															<input type="text" class="form-control search" placeholder="Search" />
														</div>
													</div>
												</div>
											
												<div style="height:69.5vh; overflow-y:auto; margin-top:15px;">
													<ul class="folder-list m-b-md list" id="surv_camp_rslt_ppl" style="padding: 0;">

													</ul>
													<div id="no_campain_rslt_cp"><i class="fas fa-hand-point-left"></i> Select a company</div>
												</div>
											</div>
										</div>
										
										<div class="col-md-6">
											<div class="form-group row">	
												<label class="col-sm-2 col-form-label">	Template: </label>
												<div class="col-sm-8">
													<select class="form-control" id="surv_camp_rslt_template" onchange="showCampaignResult();"></select>
												</div>
												<div class="col-sm-2">
													<button class="btn btn-danger" id="surCampDelAll" onclick="delAllCampUserAns();" disabled><i class="fas fa-trash-alt"></i></button>
												</div>
											</div>
											
											<input id="surv_camp_rslt_contact" type="hidden" value="" />
											<div id="campain_rslt">
											
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				
				<!-- Contact -->
				<div class="page hide" id="db_content" style="">
					<div class="row no-margins">
						<div class="col-md-3 col-sm-4 col-xs-4">
							<div class="ibox float-e-margins">
								<div class="ibox-content mailbox-content">
									<div class="file-manager" id="contacts">
										<div style="">
											<div id="custom-search-input">
												<div class="row">
													<div class="col-md-12 col-sm-6 col-xs-6" id="contSearch">
														<input type="text" class="form-control search" placeholder="Search" />
													</div>
												</div>

												<div class="row" style="margin-top:10px;">
													<div class="col-md-6 col-sm-6 col-xs-6 hide" id="contFarmerFilter">
														<div class="form-check abc-checkbox form-check-inline m-t-sm">
															<input class="form-check-input i-checks" type="checkbox" id="FarmerCompleted">
															<label class="form-check-label" for="FarmerCompleted"> <?php echo $lang['CONT_COMPLETED']; ?> </label>
														</div>
													</div>
													
													<div class="col-md-6 col-sm-6 col-xs-6 pull-right hide" id="contFarmerFilter2">
														<div class="form-check abc-checkbox form-check-inline m-t-sm">
															<input class="form-check-input i-checks" type="checkbox" id="FarmerPending">
															<label class="form-check-label" for="FarmerPending"> <?php echo $lang['CONT_PENDING']; ?> </label>
														</div>
													</div>
												</div>
											
												<div class="row" style="margin-top:10px;">
													<?php if($_SESSION['id_supchain_type'] != 331){ ?>
													<div class="col-md-7 col-sm-6 col-xs-6 hide" id="contCoopFilter">
														<select class="form-control" id="list_cooperatives" onchange="contFilter(<?php echo $_SESSION['id_supchain_type']; ?>);" >
															<?php echo $cooperative_list; ?>
														</select>
													</div>
													<?php } ?>
													
													<div class="col-md-5 col-sm-6 col-xs-6 hide" id="contSatusFilter">
														<select class="form-control" id="list_mobile_status" onchange="contFilter(<?php echo $_SESSION['id_supchain_type']; ?>);" >
															<?php echo $contact_status; ?>
														</select>
													</div>
												</div>
												
											</div>

											<div id="contactSpanner" class="h1 m-t-xs text-navy hide">
												<span class="loading"></span>
											</div>
										</div>
										
										<div style="height:68vh; overflow-y:auto; margin-top: 15px;">
											<ul class="folder-list m-b-md list" id="d2_content" style="padding: 0;">

											</ul>
										</div>
										<div class="clearfix"></div>
									</div>
								</div>
							</div>
						</div>
						
						<div class="col-md-9 col-sm-8 col-xs-8 animated fadeInRight" style="padding-right:0;">
							<div class="tabs-container">
								<ul class="nav cnt_nav-tabs">
									<li id="bio_tab" class="hide active"><a data-toggle="tab" href="#bio_tab_content"><i class="fa fa-address-card" title="Bio"></i></a></li>
									<li id="household_tab" class="hide"><a data-toggle="tab" href="#household_tab_content"><i class="fa fa-users" title="Household"></i></a></li>
									<li id="demog_tab" class="hide"><a data-toggle="tab" href="#demog_tab_content"><i class="fa fa-info-circle" title="Demography"></i></a></li>
									<li id="links_tab" class="hide"><a data-toggle="tab" href="#links_tab_content"><i class="fa fa-cogs" title="Links"></i></a></li>
									<li id="plantation_tab" class="hide"><a data-toggle="tab" href="#plantation_tab_content" onclick="refreshPlantMap();"><i class="fab fa-pagelines" title="Plantation"></i></a></li>
									<li id="plantManager_tab" class="hide"><a data-toggle="tab" href="#plantManager_tab_content" onclick=""><i class="fas fa-user-cog" title="Manager"></i></a></li>
									<li id="certification_tab" class="hide"><a data-toggle="tab" href="#certification_tab_content"><i class="fas fa-certificate" title="Certification"></i></a></li>
									<li id="environment_tab" class="hide"><a data-toggle="tab" href="#environment_tab_content" onclick="refreshEnvMap();"><i class="fas fa-tree" title="Environment"></i></a></li>
									<li id="busiFinace_tab" class="hide"><a data-toggle="tab" href="#busiFinace_tab_content"><i class="fas fa-coins" title="Business & Financials"></i></a></li>
									<li id="activityLog_tab" class="hide"><a data-toggle="tab" href="#activityLog_tab_content"><i class="fas fa-hiking" title="Activity Log"></i></a></li>
								</ul>

								<div class="cnt_tab-content">
									<div id="bio_tab_content" class="tab-pane hide active">
										<div class="panel-body contact-tab">
											<div class="col-md-6" id="bio">
												<span style="font-size: 14px;"><i class="fas fa-hand-point-left"></i> <?php echo $lang['CONT_USER_IN_LIST']; ?></span>
											</div>
											
											<div class="col-md-6" style="padding-top:30px;">
												<div class="bg-success text-center" style="padding:3px;">Google Map</div>
												<div id="contact_map" style="margin-top:10px; margin-bottom:10px; height:400px; width:100%;"></div>
												<div class="row">
													<div class="col-md-6 text-center">
														<img src="img/home_point.png" style="height: 40px;" /> Home marker
													</div>
													<div class="col-md-6 text-center">
														<img src="img/warehouse_point.png" style="height: 40px;" /> Warehouse marker
													</div>
												</div>
												
												<div class="bg-success text-center" style="padding:3px;">Documents</div>
												<div id="contactTabDocs" style="margin-top:10px;"></div>
											</div>
										</div>
									</div>
							
									<div id="household_tab_content" class="tab-pane hide">
										<div class="panel-body contact-tab">
											<div class="col-md-6" id="household">
												<span style="font-size: 14px;"><i class="fas fa-hand-point-left"></i> <?php echo $lang['CONT_USER_IN_LIST']; ?></span>
											</div>
											
											<div class="col-md-6" id="ct_household_content" style="margin-top: 30px;"></div>
										</div>
									</div>

									<div id="demog_tab_content" class="tab-pane hide">
										<div class="panel-body contact-tab">
											<div class="col-md-6" id="demog">
												<span style="font-size: 14px;"><i class="fas fa-hand-point-left"></i> <?php echo $lang['CONT_USER_IN_LIST']; ?></span>
											</div>
											
											<div class="col-md-6">
												<div class="text-center">
													<a href="https://www.povertyindex.org" target="_blank">
														<img src="img/PPI-logo.png" class="img-responsive text-center" />
													</a>
													<br/>
													<a href="https://www.povertyindex.org" target="_blank">https://www.povertyindex.org</a>
												</div>
												
												<div class="text-center" style="margin-top:45px">
													<a href="https://www.poverty-action.org" target="_blank">
														<img src="https://www.poverty-action.org/sites/default/files/poverty_action_logo.jpg" class="img-responsive text-center" />
													</a>
													<br/>
													<a href="https://www.poverty-action.org" target="_blank">https://www.poverty-action.org</a>
												</div>
											</div>
										</div>
									</div>

									<div id="links_tab_content" class="tab-pane hide">
										<div class="panel-body contact-tab">
											<div class="col-md-6" id="links">
												<span style="font-size: 14px;"><i class="fas fa-hand-point-left"></i> <?php echo $lang['CONT_USER_IN_LIST']; ?></span>
											</div>
										</div>
									</div>
 
									<div id="plantation_tab_content" class="tab-pane hide">
										<div class="panel-body contact-tab">
											<div class="col-md-6" id="plantation">
												<span style="font-size: 14px;"><i class="fas fa-hand-point-left"></i> <?php echo $lang['CONT_USER_IN_LIST']; ?></span>
											</div>
											<div class="col-md-6">
												<div id="allPlantBtn" style="margin-top: 30px;"></div>
												<div id="plantationMap" style="margin-top:10px; height:400px; width:100%;"></div>
												<div id="PlantationImages" style="margin-top: 25px;"></div>
												<div id="PlantationImagesDetails" class="text-center" style="margin-top: 45px;"></div>
											</div>
										</div>
									</div>
									
									<div id="plantManager_tab_content" class="tab-pane hide">
										<div class="panel-body contact-tab">
											<div class="col-md-6" id="plantManager_ct">
												<span style="font-size: 14px;"><i class="fas fa-hand-point-left"></i> <?php echo $lang['CONT_USER_IN_LIST']; ?></span>
											</div>
											<div class="col-md-6" id="plantManager_show" style="padding-top: 15px;">
												
											</div>
											
											<div class="col-md-12" id="plantManager_tabs">
												<div class="tabs-container">
													<div class="collapse-group">
														<div class="panel panel-primary" id="">
															<div class="panel-heading" role="tab" id="headingPlantManagerOne">
																<h4 class="panel-title">
																	<a role="button" data-toggle="collapse" href="#collapsePlantManagerOne" aria-expanded="true" aria-controls="collapsePlantManagerOne" class="trigger collapsed text-uppercase">
																	  <i class="fa fa-users"></i> Household
																	</a>
																</h4>
															</div>
														
															<div id="collapsePlantManagerOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingPlantManagerOne">
																<div class="panel-body">
																	<div class="row">
																		<div class="col-md-6" id="plantManager_Household_tabs">
																			<span style="font-size: 14px;"><i class="fas fa-hand-point-up"></i> <?php echo $lang['CONT_PLANTATION_IN_LIST']; ?></span>
																		</div>
																		<div class="col-md-6" id="pt_household_content"></div>
																	</div>
																</div>
															</div>
														</div>
													</div>
													
													<div class="collapse-group">
														<div class="panel panel-primary" id="">
															<div class="panel-heading" role="tab" id="headingPlantManagerTwo">
																<h4 class="panel-title">
																	<a role="button" data-toggle="collapse" href="#collapsePlantManagerTwo" aria-expanded="true" aria-controls="collapsePlantManagerTwo" class="trigger collapsed text-uppercase">
																	  <i class="fa fa-info-circle"></i> Demography
																	</a>
																</h4>
															</div>
														
															<div id="collapsePlantManagerTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingPlantManagerTwo">
																<div class="panel-body" id="plantManager_Demography_tabs">
																	<span style="font-size: 14px;"><i class="fas fa-hand-point-up"></i> <?php echo $lang['CONT_PLANTATION_IN_LIST']; ?></span>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									
									<div id="certification_tab_content" class="tab-pane hide">
										<div class="panel-body contact-tab">
											<div class="col-md-6" id="certification_ct">
												<span style="font-size: 14px;"><i class="fas fa-hand-point-left"></i> <?php echo $lang['CONT_USER_IN_LIST']; ?></span>
											</div>
											
											<div class="col-md-6">
												<div class="row" style="margin-top:30px;">
													<div class="col-md-3">
														<img src="img/certifications/rspo.jpg" class="img-responsive" />
													</div>
													
													<div class="col-md-3">
														<img src="img/certifications/globalgap.jpg" class="img-responsive" />
													</div>
													
													<div class="col-md-3">
														<img src="img/certifications/biosuisse.jpg" class="img-responsive" />
													</div>
													
													<div class="col-md-3">
														<img src="img/certifications/bioeurope.jpg" class="img-responsive" />
													</div>
												</div>
												
												<div class="row" id="cert_documents" style="margin-top:25px;"></div>
												<div class="row" id="certificationTabDocs" style="margin-top:20px;"></div>
											</div>
										</div>
									</div>
									
									<div id="environment_tab_content" class="tab-pane hide">
										<div class="panel-body contact-tab">
											<div class="col-md-6" id="environment_ct">
												<span style="font-size: 14px;"><i class="fas fa-hand-point-left"></i> <?php echo $lang['CONT_USER_IN_LIST']; ?></span>
											</div>
											
											<div class="col-md-6" style="padding-top:30px;">
												<div id="plantEnvMap" style="height:400px; width:100%;"></div>
												<div id="environment_media" style="padding:25px 0;"></div>
												<div id="EnvironmentImagesDetails" class="text-center" style="margin-top: 45px;"></div>
											</div>
										</div>
									</div>
									
									<div id="busiFinace_tab_content" class="tab-pane hide">
										<div class="panel-body contact-tab">
											<div class="col-md-6" id="busiFinace_ct">
												<span style="font-size: 14px;"><i class="fas fa-hand-point-left"></i> <?php echo $lang['CONT_USER_IN_LIST']; ?></span>
											</div>
											
											<div class="col-md-6" id="busiFinace_doc" style="padding-top: 30px;">
											</div>
										</div>
									</div>
									
									<div id="activityLog_tab_content" class="tab-pane hide">
										<div class="panel-body contact-tab">
											<div class="col-md-6" id="activityLog_ct">
												<span style="font-size: 14px;"><i class="fas fa-hand-point-left"></i> <?php echo $lang['CONT_USER_IN_LIST']; ?></span>
											</div>
											
											<div class="col-md-12" id="activityLog_tb">
												<div style="overflow-y:auto; height:53vh;">
													<table class="table table-hover" style="font-size:13px;">
														<thead>
															<tr>
																<th>Agent</th>
																<th style="width:120px;">Field name</th>
																<th style="width:120px;">Field value</th>
																<th>Town name</th>
																<th>Date time</th>
															</tr>
														</thead>

														<tbody id="list_activityLog">

														</tbody>
													</table>
												</div>
											</div>
										</div>
									</div>
								</div>

							</div>
						</div>
					</div>
				</div>
				
				
				<!-- Data Collection -->
				<div class="hide page" id="db_data_collection">
					<div class="row">
						<div class="col-md-3 col-sm-12 col-xs-12" id="dtCollect_ProjectsID">
							<div class="ibox float-e-margins">
								<div class="ibox-content">
									<div class="file-manager" id="dtCollect_Projects">
										<div style="">
											<div id="custom-search-input-dt-collect">
												<input type="text" class="form-control search" placeholder="Search" />
											</div>

											<div id="dtCollectSpanner" class="h1 m-t-xs text-navy hide">
												<span class="loading"></span>
											</div>
										</div>
										
										<div style="height:77vh; overflow-y:auto;">
											<ul class="folder-list m-b-md list" id="dt_collect_content" style="padding:0; margin-top:20px;">

											</ul>
										</div>
										<div class="clearfix"></div>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-9 col-sm-12 col-xs-12 animated fadeInRight">
							<div class="ibox float-e-margins">
								<div class="ibox-content">
									<div class="row" style="border-bottom:1px solid #e5e6e7; padding-bottom:10px;">
										<div class="col-md-1">
											<label style="line-height:30px;">Filter:</label>
										</div>
										<div class="col-md-4" id="dt_collect_agt_content">
											
										</div>
									</div>
			  
									<div id="dt_collect_agt_data">
										<span style="font-size: 14px;"><i class="fas fa-hand-point-left"></i> <?php echo $lang['SEL_PROJECT_IN_LIST']; ?></span>
									</div>
								</div>
							</div>
							
							<div id="dtCollect_agtVisit" class="hide">
								<div class="row">
									<div class="col-md-5 col-sm-12 col-xs-12">
										<div class="ibox float-e-margins">
											<div class="ibox-content">
												<div class="file-manager" id="dtCollect_agentVisit">
													<div id="custom-search-input-dt-collect">
														<input type="text" class="form-control search" placeholder="Search" />
													</div>
													
													<div style="height:47vh; overflow-y:auto;">
														<ul class="folder-list m-b-md list" id="dt_collect_agtVisit_content" style="padding:0; margin-top:20px;">
														
														</ul>
													</div>
													<div class="clearfix"></div>
												</div>
											</div>
										</div>
									</div>

									<div class="col-md-7 col-sm-12 col-xs-12 animated fadeInRight">
										<div class="ibox float-e-margins">
											<div class="ibox-content" id="dt_collect_agtVisit_data">
												<span style="font-size: 14px;"><i class="fas fa-hand-point-left"></i> <?php echo  $lang['SEL_PROJECT_IN_LIST']; ?></span>
											</div>
											
											<div id="fmrVisit_map" class="hide" style="width:100%; height:55vh;"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				

				<!-- Geolocation & Fiels -->
				<div class="hide page" id="db_geolocation">
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
				

				<!-- Emails -->
				<div class="hide page" id="db_email">
					<div class="row no-margins">
						<div class="col-md-12 col-sm-12 col-xs-12" style="overflow-y:auto; height:84vh; margin-bottom:25px; margin-top:0; padding:0; box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.14), 0 3px 1px -2px rgba(0, 0, 0, 0.2), 0 1px 5px 0 rgba(0, 0, 0, 0.12);">
							<!-- <iframe src="mail/index.php" style="width:100%; height:100%; border:none;"></iframe> -->
						</div>
					</div>
				</div>
				
				<!-- Story Management -->
				<div class="page hide" id="db_story">
					<div class="row">
						<div class="col-md-6 animated fadeInUpBig">
							<div class="ibox float-e-margins">
								<div class="ibox-title">
									<h5>Stories</h5>
									<div class="ibox-tools">
										<a href="#" class="pull-right btn btn-primary btn-xs st_create_action hide" data-toggle="modal" onclick="MDstoryExporter(0);" data-target="#modalStory">Create New Story</a>
									</div>
								</div>
								<div class="ibox-content">
									<div class="row">
										<div class="col-xs-12 col-md-6">
											<select id="story_media" class="form-control input-sm pull-right" onchange="storybulding()">
												<option value="">Select a media type</option>
												<option value="1">Video</option>
												<option value="2">Picture</option>
											</select>
										</div>

										<div class="col-xs-12 col-md-6">
											<select id="story_country" class="form-control input-sm pull-right" onchange="storybulding()">
												<option value="">Select a country</option>
												<option value="1">Ivory Coast</option>
												<option value="2">Senegal</option>
												<option value="3">Mozambique</option>
												<option value="4">Tanzania</option>
												<option value="5">Sudan</option>
												<option value="6">Cambodia</option>
											</select>
										</div>
									</div>
									
									<div class="row" style="margin-top:15px;">
										<div class="col-xs-12 col-md-12">
											<table class="table table-bordered table-hover">
												<thead>
													<tr>
														<td>--</td>
														<td>ID</td>
														<td style="width:50%;">Title</td>
														<td>Country</td>
														<td class="row_actions">Action</td>
													</tr>
												</thead>
												
												<tbody id="storyTbListe">
													
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
						
						<div class="col-md-6 animated fadeInLeftBig">
							<div class="ibox float-e-margins">
								<div class="ibox-title">
									<h5>Teps</h5>
									<div class="ibox-tools" id="addStepBTN">
										
									</div>
								</div>
								<div class="ibox-content">
									<div class="row">
										<div class="col-xs-12 col-md-12">
											<table class="table table-bordered table-hover">
												<thead>
													<tr>
														<td>N</td>
														<td style="width:50%;">Content</td>
														<td>Coord X</td>
														<td>Coord Y</td>
														<td class="row_actions">Action</td>
													</tr>
												</thead>
												
												<tbody id="stepsTbListe">
													<td colspan="5"><i class="fas fa-hand-point-left"></i> Select a story in the list</td>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>


				<!-- Projects -->
				<div id="db_projects" class="page hide">
					<div class="row no-margins">
						<div class="col-md-3 col-sm-4 col-xs-4 no-padding" style="">
							<div class="ibox float-e-margins">
								<div class="ibox-content mailbox-content">
									<div class="file-manager">
										<div class="row" id="projects" style="margin-top:24px;">
											<div class="col-md-12">
												<div class="col-md-3 col-sm-4 col-xs-4" style="position:fixed; left:0; top:30px; margin-left:5px;">
													<div id="custom-search-input" style="margin-top:10px;">
														<div class="input-group col-md-12" id="agentSearch">
															<input type="text" class="form-control input-lg search" placeholder="Search" style="height:30px; margin-bottom:10px; border-radius:5px;" />
														</div>
													</div>
												</div>
											</div>

											<div class="col-md-12" style="overflow-y:auto; height:76vh;">
												<ul class="folder-list m-b-md list" id="agent_list" style="padding: 0;">

												</ul>

												<div id="projectSpanner" class="h1 m-t-xs text-navy hide">
													<span class="loading"></span>
												</div>
											</div>
										</div>
										<div class="clearfix"></div>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-9 col-sm-8 col-xs-8 animated fadeInRight" style="padding-right:0;">
							<div class="tabs-container">
								<div class="collapse-group">
									<div class="panel panel-primary" id="project_summary">
										<div class="panel-heading" role="tab" id="headingProjectOne">
											<h4 class="panel-title">
												<a role="button" data-toggle="collapse" href="#collapseProjectOne" aria-expanded="true" aria-controls="collapseProjectOne" class="trigger collapsed text-uppercase">
												  <?php echo $lang['PROJECT_SUMMARY']; ?> <span id="project_refnum"></span>
												</a>
												
												<span id="projectSummaryDocs"></span>
												<a href="#" class="pull-right hide" id="newProjectBtn" data-toggle="modal" onclick="new_project('new');" data-target="#projectModal"><i class="fa fa-plus"></i></a>
											</h4>
										</div>
									
										<div id="collapseProjectOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingProjectOne">
											<div class="panel-body" id="showProjectSum">
												<span style="font-size: 14px;"><i class="fas fa-hand-point-left"></i> <?php echo $lang['SEL_PROJECT_IN_LIST']; ?></span>
											</div>
										</div>
									</div>
							
									<div class="panel panel-primary" id="project_request">
										<div class="panel-heading" role="tab" id="headingProjectTwo">
											<h4 class="panel-title">
												<a role="button" data-toggle="collapse" href="#collapseProjectTwo" aria-expanded="true" aria-controls="collapseProjectTwo" class="trigger collapsed text-uppercase">
													<?php echo $lang['PROJECT_TASK']; ?> <span id="task_refnum"></span>  
												</a>
											
												<span id="projectTaskDocs"></span>
												<span id="projectTaskAdd"></span>
											</h4>
										</div>
										
										<div id="collapseProjectTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingProjectTwo">
											<div class="panel-body">
												<span style="font-size: 14px;" id="selProjectInList"><i class="fas fa-hand-point-left"></i> <?php echo $lang['SEL_PROJECT_IN_LIST']; ?></span>
											
												<div id="taskShow" class="hide">
													<div class="row">
														<div class="tabs-container">
															<ul class="nav nav-tabs">
																<li class="active"><a data-toggle="tab" href="#project_task_agent_tab1"> <?php echo $lang['PROJECT_TASK_TAB_1']; ?></a></li>
																<li><a data-toggle="tab" href="#project_task_agent_tab2"> <?php echo $lang['PROJECT_TASK_TAB_2']; ?></a></li>
																<li><a data-toggle="tab" href="#project_task_agent_tab3"> <?php echo $lang['PROJECT_TASK_TAB_3']; ?></a></li>
															</ul>
															<div class="tab-content">
																<div id="project_task_agent_tab1" class="tab-pane active">
																	<div class="panel-body">	
																		<div class="row">
																			<div class="col-md-4">
																				<div id="projectTowns">
																					<div class="row" style="position:fixed; background:white;">
																						<div class="col-md-12">
																							<div class="input-group col-md-12" id="taskPSearch">
																								<input type="text" class="form-control input-lg search" placeholder="Search" style="height:30px; margin-bottom:10px; border-radius:5px;" />
																							</div>
																						</div>
																					</div>
																					
																					<ul id="projectTaskListTowns" class="folder-list m-b-md list" style="padding:0; border-top:1px solid #e7eaec; margin-top:50px;">
																					
																					</ul>
																				</div>
																			</div>
																			
																			<div class="col-md-8" id="taskDetails" style="border-left:1px solid #e7eaec;">
																				<span style="font-size: 14px;"><i class="fas fa-hand-point-left"></i> <?php echo $lang['SEL_PROJECT_IN_LIST']; ?></span>
																			</div>
																		</div>
																	</div>
																</div>
																<div id="project_task_agent_tab2" class="tab-pane">
																	<div class="panel-body">
																		<div class="row" id="agentDetails">
																			<div class="col-md-4">
																				<div id="projectAgentTowns">
																					<div class="row" style="position:fixed; background:white;">
																						<div class="col-md-12">
																							<div class="input-group col-md-12" id="taskPAgentSearch">
																								<input type="text" class="form-control input-lg search" placeholder="Search" style="height:30px; margin-bottom:10px; border-radius:5px;" />
																							</div>
																						</div>
																					</div>
																					
																					<ul id="projectTaskAgentsListTowns" class="folder-list m-b-md list" style="padding:0; border-top:1px solid #e7eaec; margin-top:50px;">
																					
																					</ul>
																				</div>
																			</div>
																			
																			<div class="col-md-4" style="border-left:1px solid #e7eaec;">
																				<div id="agentCardRef">
																					<div id="projectTaskListFarmersNotInThumb">
																						<span style="font-size: 14px;"><i class="fas fa-hand-point-left"></i> <?php echo $lang['SEL_PROJECT_IN_LIST']; ?></span>
																					</div>
																					
																					<div id="projectTownsAgents" class="hide">
																						<div class="row" style="position:fixed; background:white; z-index:999;">
																							<div class="col-md-12">
																								<div class="input-group col-md-12" id="taskPAgentsSearch">
																									<input type="text" class="form-control input-lg search" placeholder="Search" style="height:30px; margin-bottom:10px; border-radius:5px; width: calc(80% - 10px);" />
																								
																									<span id="all_farmer_in"></span>
																								</div>
																							</div>
																						</div>
																						
																						<ul id="projectTaskListFarmersNotIn" class="folder-list m-b-md list" style="padding:0; border-top:1px solid #e7eaec; margin-top:50px;">
																							
																						</ul>
																					</div>
																				</div>
																			</div>
																			
																			<div class="col-md-4" style="border-left:1px solid #e7eaec;">
																				<div class="row" style="padding-bottom: 15px;">
																					<div class="col-md-12">
																						<select class="form-control" id="projectTaskListAgents"></select>
																					</div>
																				</div>
																				
																				<div id="farmerCardRef">
																					<div id="projectTownsFarmers" class="hide">
																						<div class="row" style="position:fixed; background:white; z-index:999;">
																							<div class="col-md-12">
																								<div class="input-group col-md-12" id="taskPFarmersSearch">
																									<span id="all_farmer_out"></span>
																									
																									<input type="text" class="form-control input-lg search" placeholder="Search" style="height:30px; margin-bottom:10px; border-radius:5px; width: calc(80% - 10px);" />
																								</div>
																							</div>
																						</div>
																						
																						<ul id="projectTaskListFarmers" class="folder-list m-b-md list" style="padding:0; border-top:1px solid #e7eaec; margin-top:50px;">
																							
																						</ul>
																					</div>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
																
																<div id="project_task_agent_tab3" class="tab-pane">
																	<div class="panel-body">
																		<div class="row" id="">
																			<div class="col-md-3">
																				<div id="">
																					<div class="row">
																						<div class="col-md-12">
																							<div class="input-group" id="">
																								<input type="text" class="form-control input-lg search" placeholder="Search" style="height:30px; margin-bottom:10px; border-radius:5px;" />
																							</div>
																						</div>
																					</div>
																					
																					<ul id="" class="folder-list m-b-md list" style="padding:0; border-top:1px solid #e7eaec;">
																					
																					</ul>
																				</div>
																			</div>
																			
																			<div class="col-md-9" style="overflow:scroll;">
																				<table class="table table-hover table-bordered table-sm table-responsive small_table">
																					<thead>
																						<tr>
																							<th>Sync start</th>
																							<th>Sync last</th>
																							<th>Contact Completed</th>
																							<th>Plantation Completed</th>
																							<th>Collection Point</th>
																							<th>Home Point</th>
																							<th>Stockage Point</th>
																							<th>Path</th>
																							<th>Surface</th>
																							<th>Approved by</th>
																							<th>Show Contact</th>
																							<th>Next activit</th>
																						</tr>
																					</thead>
																					
																					<tbody id="">
																					
																					</tbody>
																				</table>
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
									</div>
									
									<div class="panel panel-primary" id="project_towns">
										<div class="panel-heading" role="tab" id="headingProjectFour">
											<h4 class="panel-title">
												<a role="button" data-toggle="collapse" href="#collapseProjectFour" aria-expanded="true" aria-controls="collapseProjectFour" class="trigger collapsed text-uppercase">
												  <?php echo $lang['PROJECT_TOWNS']; ?> <span id="town_refnum"></span>
												</a>
												
												<span id="projectTownsDocs"></span>
												<span id="projectQuadrantAdd"></span>
											</h4>
										</div>
									
										<div id="collapseProjectFour" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingProjectFour">
											<div class="panel-body">
												<div class="col-md-12" id="offline_map_box">
													<div id="polyBox" class="poly_box hide"></div>
													<div id="offline_map" style="height:67vh; width:100%"></div>
												</div>
												
												<div class="col-md-3 hide" id="offline_map_towns">
													<div id="projectSequenceTowns">
														<ul id="projectTaskSequenceListTowns" class="sortable-list connectList agile-list" style="padding:0;">
												
														</ul>
													</div>
												</div>
											</div>
										</div>
									</div>
									
									<div class="panel panel-primary" id="project_quadrantManage">
										<div class="panel-heading" role="tab" id="headingProjectFive">
											<h4 class="panel-title">
												<a role="button" data-toggle="collapse" href="#collapseProjectFive" aria-expanded="true" aria-controls="collapseProjectFive" class="trigger collapsed text-uppercase">
												  Quadrant Management <span id="qM_refnum"></span>
												</a>
												
												<span id="projectMbtilesAdd"></span>
											</h4>
										</div>
									
										<div id="collapseProjectFive" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingProjectFive">
											<div class="panel-body">
											
												<div id="projectQuadrantThumb">
													<span style="font-size: 14px;"><i class="fas fa-hand-point-left"></i> <?php echo $lang['SEL_PROJECT_IN_LIST']; ?></span>
												</div>
										
												<div id="projectQuadrantManage" class="hide">										
													<table class="table table-striped table-bordered table-hover dashboard-analytics" style="font-size:12px;">
														<thead>
															<th>N</th>
															<th style="width:50%;">Mbtiles</th>
															<th>Map Type</th>
															<th>Date</th>
															<th>Status</th>
														</thead>
														
														<tbody id="qM_mbtiles_list"></tbody>
													</table>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- Task Management -->
				<div id="db_tasks" class="page hide">
					<div class="" style="text-align: center;height: 40px;line-height: 40px;">
						<div class="col-md-3 col-sm-4 col-xs-4 pull-left hide">
							<div class="input-group">
								<input type="text" class="form-control typeahead_1" placeholder="<?php echo $lang['GTT_SEARCH']; ?>" name="srch-tasks" id="srch-tasks">
								<div class="input-group-btn">
									<button class="btn btn-default" onclick="tasks(0);" style="padding:9px 12px;">
									<i class="fa fa-search"></i></button>
								</div>
							</div>
						</div>
						
						<div class="col-md-3 col-sm-4 col-xs-4 pull-left">
							<div class="input-group">
								<select class="form-control" id="gantt_task_agent" onchange="tasks(this.value);"></select>
							</div>
						</div>
					</div>
					<div class="row animated fadeInRight">
						<div class="col-lg-12">
							<button class="btn btn-warning pull-right" onclick="toggleMode(this)"><?php echo $lang['GTT_ZOOM_TFIT_BTN']; ?></button>
							<div class="ibox float-e-margins">
								<div class="ibox-content">
									<div id="task_gantt" style='width:100%; height:90%;'></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<!-- Notes -->
				<div id="db_notes" class="page hide">
					<div class="row">
						<div class="col-lg-12">
							<div class="wrapper wrapper-content animated fadeInUp">
								<ul class="notes">
									<li>
										<div>
											<small>12:03:28 12-04-2014</small>
											<h4>Long established fact</h4>
											<p>The years, sometimes by accident, sometimes on purpose (injected humour and the like).</p>
											<a href="#"><i class="fa fa-trash-o "></i></a>
										</div>
									</li>
									<li>
										<div>
											<small>11:08:33 16-04-2014</small>
											<h4>Latin professor at Hampden-Sydney </h4>
											<p>The first line of Lorem Ipsum, "Lorem ipsum dolor sit amet..", comes from a line in section 1.10.32.</p>
											<a href="#"><i class="fa fa-trash-o "></i></a>
										</div>
									</li>
									<li>
										<div>
											<small>9:12:28 10-04-2014</small>
											<h4>The standard chunk of Lorem</h4>
											<p>Ipsum used since the 1500s is reproduced below for those interested.</p>
											<a href="#"><i class="fa fa-trash-o "></i></a>
										</div>
									</li>
									<li>
										<div>
											<small>3:33:12 6-03-2014</small>
											<h4>The generated Lorem Ipsum </h4>
											<p>The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.</p>
											<a href="#"><i class="fa fa-trash-o "></i></a>
										</div>
									</li>
									<li>
										<div>
											<small>5:20:11 4-04-2014</small>
											<h4>Contrary to popular belief</h4>
											<p>Hampden-Sydney College in Virginia, looked up one.</p>
											<a href="#"><i class="fa fa-trash-o "></i></a>
										</div>
									</li>
									<li>
										<div>
											<small>2:10:12 4-05-2014</small>
											<h4>There are many variations</h4>
											<p>All the Lorem Ipsum generators on the Internet .</p>
											<a href="#"><i class="fa fa-trash-o "></i></a>
										</div>
									</li>
									<li>
										<div>
											<small>10:15:26 6-04-2014</small>
											<h4>Ipsum used standard chunk of Lorem</h4>
											<p>Standard chunk  is reproduced below for those.</p>
											<a href="#"><i class="fa fa-trash-o "></i></a>
										</div>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>


				<!-- Agents management -->
				<div id="db_agentm" class="page hide">
					<div class="row">
						<div class="col-lg-12">
							<div class="wrapper wrapper-content animated fadeInUp">

								<div class="ibox">
									<div class="ibox-title">
										<div class="row">
											<div class="col-md-12">
												<h5>Data Collection Tracker</h5>
											</div>
											
											<div class="col-md-3">
												<select onchange="agentm();" id="agentm_agents" style="border: 1px solid #e7eaec; padding: 5px 10px; font-size: 12px; line-height: 1.5;">
													<option value="0">-- <?php echo $lang['PROJECT_TASK_AGENT']; ?> --</option>
												</select>
											</div>
											
											<div class="col-md-3">
												<select onchange="agentm();" id="agentm_farmer" style="border: 1px solid #e7eaec; padding: 5px 10px; font-size: 12px; line-height: 1.5;">
													<option value="0">-- <?php echo $lang['PROJECT_TASK_FARMER']; ?> --</option>
												</select>
											</div>
											
											<div class="col-md-2">
												<select onchange="agentm();" id="agentm_town" style="border: 1px solid #e7eaec; padding: 5px 10px; font-size: 12px; line-height: 1.5;">
													<option value="0">-- <?php echo $lang['PROJECT_TOWNS']; ?> --</option>
												</select>
											</div>
											
											<div class="col-md-4">
												<span style="line-height:2; margin-right:8px;"><?php echo $lang['LAST']; ?> </span>
												<select onchange="agentm();" id="agentm_sel" style="border: 1px solid #e7eaec; padding: 5px 10px; font-size: 12px; line-height: 1.5;">
													<option value="100" selected>100</option>
													<option value="200">200</option>
													<option value="300">300</option>
													<option value="400">400</option>
													<option value="500">500</option>
													<option value="1000">1000</option>
												</select>
												<span style="line-height:2; margin-left:8px;"> <?php echo $lang['RECORDS']; ?></span>
												
												<button type="button" id="loading-example-btn" onclick="agentm();" class="btn btn-white btn-sm pull-right" ><i class="fa fa-refresh"></i> <?php echo $lang['REFRESH']; ?></button>
											</div>
										</div>
									</div>
									<div class="ibox-content">
										<div class="row">
											<div class="col-md-8" style="border-right: 1px solid #e7eaec">
												<div style="overflow-y:auto; height:80vh;">
													<table class="table table-hover" style="font-size:13px;">
														<thead>
														<tr>
															<th>Agent</th>
															<th style="width:120px;">Field name</th>
															<th style="width:120px;">Field value</th>
															<th>Farmer name</th>
															<th>Town name</th>
															<th>Date time</th>
															<th>--</th>
														</tr>
														</thead>

														<tbody id="list_management">

														</tbody>
													</table>
												</div>
											</div>

											<div class="col-lg-4">
												<div id="agent_map" style="height:80vh; width:100%;"></div>
												<table class="table">
													<td style="width:20px"><img src="img/icon_agent.gif" width="20px"/></td>
													<td>Farmer Marker</td>
													<td style="width:20px"><img src="img/icon_town.png" width="20px"/></td>
													<td>Town Marker</td>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>


				<!-- Register values -->
				<div id="db_regvalues" class="page hide">
					<div class="row">
						<div class="col-lg-12">
							<div class="form-group">
								<div class="input-group">
									<select data-placeholder="Choose a Category..." class="chosen-select" style="width:350px;" id="regcat" onchange="listValues();" tabindex="2">
										<option value="">Select a Category</option>
										<?php echo $select_register; ?>
									</select>
								</div>
							</div>
						</div>

						<div class="col-lg-8">
							<div class="ibox float-e-margins">
								<div class="ibox-title">
									<h5>Regvalue list</h5>
									<div class="ibox-tools">
										<a href="#" class="pull-right btn btn-primary btn-xs" data-toggle="modal" onclick="regvaluesManagement('show','','create');" data-target="#modalRegvalue">
										Create New Regvalue</a>
									</div>
								</div>
								<div class="ibox-content">
									<div class="table-responsive" style="overflow-y:auto; height:100vh;">
										<table class="table table-striped" style="font-size:13px;">
											<thead>
												<tr>
													<th>#</th>
													<th>English </th>
													<th>Deutsch </th>
													<th>Français </th>
													<th>Português </th>
													<th>Español </th>
													<th>Swahili </th>
													<th>Italiano </th>
													<th style="width:30px;">---</th>
												</tr>
											</thead>

											<tbody id="listvalues">

											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
						
						<div class="col-lg-4">
							<div class="ibox float-e-margins">
								<div class="ibox-title">
									<h5>Register list</h5>
									<div class="ibox-tools">
										<a href="#" class="pull-right btn btn-primary btn-xs" data-toggle="modal" onclick="registerManagement('show','','create');" data-target="#modalRegister">
										Create New Register</a>
									</div>
								</div>
								<div class="ibox-content">
									<div class="table-responsive" style="overflow-y:auto; height:100vh;">
										<table class="table table-striped" style="font-size:13px;">
											<thead>
												<tr>
													<th style="width:8%;">#</th>
													<th>Name </th>
													<th>Code </th>
													<th style="width:12%;">---</th>
												</tr>
											</thead>

											<tbody id="listregisters">

											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- Manage users -->
				<div id="db_users" class="page hide">
					<div class="row">
						<div class="col-lg-12">
							<div class="wrapper wrapper-content animated fadeInUp">

								<div class="ibox">
									<div class="ibox-title">
										<h5>User list</h5>
										<div id="userTopbtn" style="margin-top:-8px; float:right;">

										</div>
									</div>
									<div class="ibox-content" style="padding-top:0;">
										<div class="row">
											<div class="col-lg-12" id="userCList">
												<!--<div class="row">
													<div class="col-sm-4 m-b-xs">
														<select class="input-sm form-control input-s-sm inline" onchange="users();" id="rolesList">
															<?php //echo $roles_list; ?>
														</select>
													</div>
												</div> -->
												<div class="table-responsive">
													<table class="table table-hover" style="font-size:13px;">
														<thead>
															<tr>
																<th>First name </th>
																<th>Family name</th>
																<th>Username</th>
																<th width="60">--</th>
															</tr>
														</thead>

														<tbody id="list_usersC">

														</tbody>
													</table>

													<div id="userspanner" class="h1 m-t-xs text-navy hide">
														<span class="loading"></span>
													</div>
												</div>
											</div>

											<div class="col-lg-4 hide" id="newUserform">
												<form>
													<div class="form-group">
														<label for="exampleSelect1">Contact</label>
														<select class="form-control" id="notYet_usersC">

														</select>
													</div>

													<div class="form-group">
														<label for="">User email</label>
														<input type="mail" class="form-control" id="" placeholder="Email">
													</div>

													<button type="submit" class="btn btn-primary">Send registration form</button>
												</form>
											</div>
											
											<div class="col-lg-4 hide" id="editUserRoleform">
												<form id="role_form">
													<div class="form-group" id="userRoleInfos">
														
													</div>

													<div class="form-group">
														<label for="">Role</label>
														<select class="form-control" id="userRoleList">

														</select>
													</div>
													
													<input type="hidden" id="userRole_idUser" />
													<input type="hidden" id="userRole_value" />

													<button onclick="updateUserRole();" id="saveRoleBtn" class="btn btn-primary"><i class="fa fa-save" aria-hidden="true"></i> Save</button>
												</form>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>


				<!-- Manage users roles definition -->
				<div id="db_roles_def" class="page hide">
					<div class="row">
						<div class="col-lg-6">
							<div class="wrapper wrapper-content animated fadeInUp" style="padding-bottom:10px;">
								<div class="ibox">
									<div class="ibox-title">
										<h5>Role list</h5>
									</div>
									<div class="ibox-content tb_roles">
										<table class="table table-hover roleList" id="rolestable" style="font-size:13px;">
											<thead>
												<tr>
													<th style="width:40px;">#</th>
													<th style="width:82%;">Role </th>
													<th style="width:10%;">---</th>
												</tr>
											</thead>

											<tbody id="list_rolesDef">

											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
						
						<div class="col-lg-6" id="newRoleForm_RoleDef">
							<div class="wrapper wrapper-content animated fadeInUp" style="padding-bottom:10px;">
								<div class="ibox">
									<div class="ibox-title">
										<h5>Create Role</h5>
									</div>
									<div class="ibox-content tb_roles">
										<div style="font-size:13px;">
											<div class="form-group">
												<label for="">Role</label>
												<input type="text" class="form-control" id="Role_name" placeholder="Role name">
											</div>

											<div class="form-group">
												<label for="">Role (En)</label>
												<input type="text" class="form-control" id="Role_name_en" placeholder="Role name in english">
											</div>

											<div class="form-group">
												<label for="">Role (Ge)</label>
												<input type="text" class="form-control" id="Role_name_ge" placeholder="Role name in german">
											</div>

											<div class="form-group">
												<label for="">Role (Fr)</label>
												<input type="text" class="form-control" id="Role_name_fr" placeholder="Role name in frensh">
											</div>

											<div class="form-group">
												<label for="">Role (Es)</label>
												<input type="text" class="form-control" id="Role_name_es" placeholder="Role name in spanish">
											</div>

											<div class="form-group">
												<label for="">Role (Po)</label>
												<input type="text" class="form-control" id="Role_name_po" placeholder="Role name in portuguese">
											</div>

											<div class="form-group">
												<button type="submit" onclick="roleManagement('add','');" class="btn btn-primary"><i class="fa fa-save"></i></button>
												<button type="submit" class="btn btn-danger"><i class="fa fa-ban"></i></button>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-lg-6">
							<div class="wrapper wrapper-content animated fadeInUp" style="padding-top:0px;">
								<div class="ibox">
									<div class="ibox-title">
										<h5>Object in role</h5>
										<div class="ibox-tools">
                                            <span class="label label-warning-light pull-right" id="selectedRoleDef"></span>
                                        </div>
									</div>
									<div class="ibox-content tb_roles">
										<table class="table table-hover" style="font-size:13px;">
											<thead>
												<tr>
													<th>Object </th>
													<th style="width:40px;">---</th>
												</tr>
											</thead>

											<tbody id="list_objectIR">

											</tbody>
										</table>

										<div id="objectRpanner" class="h1 m-t-xs text-navy hide">
											<span class="loading"></span>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="col-lg-6">
							<div class="wrapper wrapper-content animated fadeInUp" style="padding-top:0px;">
								<div class="ibox">
									<div class="ibox-title">
										<h5>Object list</h5>
										<div class="ibox-tools">
                                            <a href="#" data-toggle="modal" id="newObject_RoleDef" data-target="#newObject" class="btn btn-primary btn-xs">Create new object</a>
                                        </div>
									</div>
									<div class="ibox-content tb_roles" style="padding:0;">
										<table class="table table-hover table-responsive" style="font-size:13px;">
											<thead>
												<tr>
													<th style="width:40px;">---</th>
													<th>Object </th>
												</tr>
											</thead>

											<tbody id="list_objectR">

											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				
				
				<!-- Manage users roles assignement -->
				<div id="db_roles_ass" class="page hide">
					<div class="row">
						<div class="col-lg-6">
							<div class="wrapper wrapper-content animated fadeInUp" style="padding-bottom:10px;">
								<div class="ibox">
									<div class="ibox-title">
										<h5>Role list</h5>
									</div>
									<div class="ibox-content tb_roles">
										<table class="table table-hover roleList" id="rolestable" style="font-size:13px;">
											<thead>
												<tr>
													<th style="width:40px;">#</th>
													<th style="width:82%;">Role </th>
													<th style="width:10%;">---</th>
												</tr>
											</thead>

											<tbody id="list_roles">

											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>

						<div class="col-lg-6">
							<div class="wrapper wrapper-content animated fadeInUp" style="padding-bottom:10px;">
								<div class="ibox">
									<div class="ibox-title">
										<h5>Role definition</h5>
										<div class="ibox-tools">
                                            <span class="label label-info pull-right" id="selectedRole_def"></span>
                                        </div>
									</div>
									<div class="ibox-content tb_roles">
										<table class="table table-hover roleList" id="rolestable_def" style="font-size:13px;">
											<thead>
												<tr>
													<th style="width:40px;">#</th>
													<th>Definition </th>
												</tr>
											</thead>

											<tbody id="list_roles_def">

											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-lg-6">
							<div class="wrapper wrapper-content animated fadeInUp" style="padding-top:0px;">
								<div class="ibox">
									<div class="ibox-title">
										<h5>Users in role</h5>
										<div class="ibox-tools">
                                            <span class="label label-warning-light pull-right" id="selectedRole"></span>
                                        </div>
									</div>
									<div class="ibox-content tb_roles">
										<table class="table table-hover" style="font-size:13px;">
											<thead>
												<tr>
													<th>First name </th>
													<th>Last name</th>
													<th>Username</th>
													<th>---</th>
												</tr>
											</thead>

											<tbody id="list_usersIR">

											</tbody>
										</table>

										<div id="usersRpanner" class="h1 m-t-xs text-navy hide">
											<span class="loading"></span>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="col-lg-6">
							<div class="wrapper wrapper-content animated fadeInUp" style="padding-top:0px;">
								<div class="ibox">
									<div class="ibox-title">
										<h5>Users list</h5>
										<input type="text" id="sysRlAssUerList" onkeyup="filterUserRA()" placeholder="Search for Last names.." />
									</div>
									<div class="ibox-content tb_roles" style="padding:0;">
										<table class="table table-hover table-responsive" style="font-size:13px;" id="sysRlAssUerList_table">
											<thead>
												<tr>
													<th style="width:40px;">---</th>
													<th>First name </th>
													<th>Last name</th>
													<th>Username</th>
												</tr>
											</thead>

											<tbody id="list_usersR">

											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>


				<!-- Manage users permissions -->
				<div id="db_permissions" class="page hide">
					<div class="row">
						<div class="col-lg-6">
							<div class="wrapper wrapper-content animated fadeInUp" style="padding-bottom:10px;">
								<div class="ibox">
									<div class="ibox-title">
										<h5>Role</h5>
									</div>
									<div class="ibox-content" style="height:20em;">
										<select class="form-control" multiple="" onchange="userInRoleP();" id="PermissionsIdRole" style="height:100%;">
                                            <?php echo $roles_list; ?>
                                        </select>
									</div>
								</div>
							</div>
						</div>

						<div class="col-lg-6">
							<div class="wrapper wrapper-content animated fadeInUp" style="padding-bottom:10px;">
								<div class="ibox">
									<div class="ibox-title">
										<h5>Users in role</h5>
										<div class="ibox-tools">
                                            <span class="label label-warning-light pull-right" id="selectedRoleP"></span>
                                        </div>
									</div>
									<div class="ibox-content tb_roles">
										<table class="table table-hover" style="font-size:13px;">
											<thead>
												<tr>
													<th>First name </th>
													<th>Last name</th>
													<th>Username</th>
												</tr>
											</thead>

											<tbody id="list_usersIR_P">

											</tbody>
										</table>

										<div id="usersRpannerP" class="h1 m-t-xs text-navy hide">
											<span class="loading"></span>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-lg-12">
							<div class="wrapper wrapper-content animated fadeInUp" style="padding-top:0px;">
								<div class="ibox">
									<div class="ibox-title">
										<h5>Permission</h5>
									</div>
									<div class="ibox-content tb_roles" style="height:27em;">
										<table class="table table-hover" style="font-size:13px;">
											<thead>
												<tr>
													<th>Resource</th>
													<th>Full</th>
													<th>Create</th>
													<th>Read</th>
													<th>Update</th>
													<th>Delete</th>
												</tr>
											</thead>

											<tbody id="list_users_permission">

											</tbody>
										</table>

										<div id="usersRpannerUP" class="h1 m-t-xs text-navy hide">
											<span class="loading"></span>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>


				<!-- Manage CRM Management -->
				<div id="db_crm_manag" class="page hide" style="margin-top:-30px;">
					<div class="row no-margins">
						<div class="wrapper wrapper-content animated fadeInUp">
							<div class="col-md-3 col-sm-4 col-xs-4 no-padding" style=" margin-bottom:20px;">
								<div class="ibox float-e-margins">
									<div class="ibox-content mailbox-content">
										<div class="file-manager">
											<div class="row" id="order_reference_nembers" style="margin-top:40px;">
												<div class="col-md-12">
													<div class="col-md-3 col-sm-4 col-xs-4" style="position:fixed; left:0; top:30px;">
														<div id="custom-search-input" style="margin-top:10px;">
															<div class="input-group col-md-7 pull-left">
																<input type="text" class="form-control input-lg search" placeholder="<?php echo $lang['GTT_SEARCH']; ?>" style="height:30px; margin-bottom:10px; border-radius:5px;" />
															</div>
																
															<div class="input-group col-md-4 pull-right">
																<select class="form-control" onchange="crmContractPipelineFilter(this.value);" style="height:30px; margin-bottom:10px; border-radius:5px;">
																	<?php echo $list_all_pipelines; ?>
																</select>
															</div>
														</div>
													</div>
												</div>
												
												<div class="col-md-12" style="overflow-y:auto; height:100vh;">
													<ul class="folder-list m-b-md list" id="order_refn_list" style="padding:0;">
			
													</ul>
													
													<div id="crmSpanner" class="h1 m-t-xs text-navy hide">
														<span class="loading"></span>
													</div>
												</div>
											</div>
											<div class="clearfix"></div>
										</div>
									</div>
								</div>
							</div>

							<div class="col-md-9 col-sm-8 col-xs-8 animated fadeInRight" style="padding-right:0;">
								<div class="tabs-container">
									<div class="collapse-group">
										<div class="panel panel-primary hide" id="crm_summary">
											<div class="panel-heading" role="tab" id="headingOne">
											  <h4 class="panel-title">
												<a role="button" data-toggle="collapse" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne" class="trigger collapsed text-uppercase">
												  <?php echo $lang['CONTRACT_SUMMARY']; ?> <span id="sum_refnum"></span>
												</a>
												
												<span id="summaryDocs"></span>
												<a href="#" class="pull-right hide" id="startWizard" data-toggle="modal" onclick="new_req();" data-target="#wizardModal"><i class="fa fa-plus"></i></a>
											  </h4>
											</div>
											
											<div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
												<div class="panel-body">
													<div class="tabs-container">
														<ul class="nav nav-tabs">
															<li class="active " id="crm_customer_request_tab"><a data-toggle="tab" href="#crm_customer_request"> <?php echo $lang['CONTRACT_CUST_REQUEST']; ?></a></li>
															<li class="" id="crm_notes_tab"><a data-toggle="tab" href="#crm_notes"> <?php echo $lang['CONTRACT_NOTES']; ?></a></li>
															<li class="hide" id="crm_contract_tab"><a data-toggle="tab" href="#crm_contract"> <?php echo $lang['CONTRACT_CONTRACT']; ?></a></li>
														</ul>
														
														<div class="tab-content">
															<div id="crm_customer_request" class="tab-pane active ">
																<div class="panel-body">
																	<div id="crmSummarySpanner1" class="h1 m-t-xs text-navy hide" style="position:absolute; top:10px; z-index:99999;">
																		<span class="loading"></span>
																	</div>
																	
																	<div class="row" id="user_summary">
																		<span style="font-size: 14px;"><i class="fas fa-hand-point-left"></i> <?php echo $lang['CONTRACT_SEL_ORDER_IN_LIST']; ?></span>
																	</div>
																</div>
															</div>
														
															<div id="crm_notes" class="tab-pane ">
																<div class="panel-body">
																	<div id="crmSummarySpanner2" class="h1 m-t-xs text-navy hide" style="position:absolute; top:10px; z-index:99999;">
																		<span class="loading"></span>
																	</div>
																
																	<div class="row" id="importer_summary">
																		<span style="font-size: 14px;"><i class="fas fa-hand-point-left"></i> <?php echo $lang['CONTRACT_SEL_ORDER_IN_LIST']; ?></span>
																	</div>
																</div>
															</div>
															
															<div id="crm_contract" class="tab-pane hide">
																<div class="panel-body">
																	<div id="crmSummarySpanner3" class="h1 m-t-xs text-navy hide" style="position:absolute; top:10px; z-index:99999;">
																		<span class="loading"></span>
																	</div>
																
																	<div class="row" id="contract_summary">
																		<span style="font-size: 14px;"><i class="fas fa-hand-point-left"></i> <?php echo $lang['CONTRACT_SEL_ORDER_IN_LIST']; ?></span>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
							
										<div class="panel panel-primary hide" id="crm_request">
											<div class="panel-heading" role="tab" id="headingTwo">
											  <h4 class="panel-title">
												<a role="button" data-toggle="collapse" href="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo" class="trigger collapsed text-uppercase">
												  <?php echo $lang['CONTRACT_REQUEST']; ?> <span id="req_refnum"></span>  
												</a>
												
												<span id="requestDocs"></span>
											  </h4>
											</div>
											<div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
											  <div class="panel-body">
												<div id="request_header" class="col-md-12" style="font-size:12px; color:#aaa; margin-bottom:10px;"></div>
												<div class="tabs-container">
													<ul class="nav nav-tabs">
														<li class="active hide" id="crm_request_sched_tab"><a data-toggle="tab" href="#crm_request_sched_ct"> <?php echo $lang['CONTRACT_SCHEDULE']; ?></a></li>
														<li class="hide" id="crm_request_export_tab"><a data-toggle="tab" href="#crm_request_export_ct"> <?php echo $lang['CONTRACT_EXPORTER']; ?></a></li>
														<li class="hide" id="crm_request_freight_tab"><a data-toggle="tab" href="#crm_request_freight_ct"> <?php echo $lang['CONTRACT_FREIGHT']; ?></a></li>
														<li class="hide" id="crm_request_calc_tab"><a data-toggle="tab" href="#crm_request_calc_ct"> <?php echo $lang['CONTRACT_CALCULATION']; ?></a></li>
														<li class="hide" id="crm_request_proposal_tab"><a data-toggle="tab" href="#crm_request_proposal_ct"> <?php echo $lang['CONTRACT_PROPOSAL']; ?></a></li>
														<li class="hide" id="crm_request_ordconfirm_tab"><a data-toggle="tab" href="#crm_request_ordconfirm_ct"> <?php echo $lang['CONTRACT_ORDER']; ?> </a></li>
													</ul>
													<div class="tab-content">
														<div id="crm_request_sched_ct" class="tab-pane active hide">
															<div class="panel-body">	
																<div id="scheduleUpdate_info"></div>
																<div class="table-responsive">
																	<table class="table table-striped table-bordered table-hover dataTables-example" style="font-size:12px;">
																		<thead>
																			<tr>
																				<th><?php echo $lang['CONTRACT_#SHIPMENT']; ?></th>
																				<th><?php echo $lang['CONTRACT_#CON']; ?></th>
																				<th><?php echo $lang['CONTRACT_MT_C']; ?></th>
																				<th><?php echo $lang['CONTRACT_MT_T']; ?></th>
																				<th><?php echo $lang['CONTRACT_ETD']; ?></th>
																				<th><?php echo $lang['CONTRACT_ETA']; ?></th>
																				<th id="edit_schedule_btn" class="hide"><?php echo $lang['CONTRACT_EDIT']; ?></th>
																				<th><?php echo $lang['CONTRACT_MAIL']; ?></th>
																			</tr>
																		</thead>
																		<tbody id="request_schedule">
																			<tr>
																				<td colspan="8">
																					<span style="font-size: 14px;">
																						<i class="fas fa-hand-point-left"></i> <?php echo $lang['CONTRACT_SEL_SHIPMENT_IN_LIST']; ?>
																					</span>
																				</td>
																			</tr>
																		</tbody>
																	</table>
																	
																	<div id="addShipmentROW"></div>
																</div>
															</div>
														</div>
														<div id="crm_request_export_ct" class="tab-pane hide">
															<div class="panel-body">
																<div class="col-md-3 col-sm-3 col-xs-3 no-padding">
																	<ul class="folder-list m-b-md list" id="quote_list" style="padding:0;">
																		<span style="font-size: 14px;">
																			<i class="fas fa-hand-point-left"></i> <?php echo $lang['CONTRACT_SEL_SHIPMENT_IN_LIST']; ?>
																		</span>
																	</ul>
																</div>
																
																<div class="col-md-9 col-sm-9 col-xs-9" id="schedule_quote" style="border-left:1px solid #e7eaec;">
																	<span style="font-size: 14px;">
																		<i class="fas fa-hand-point-left"></i> <?php echo $lang['CONTRACT_SEL_SHIPMENT_IN_LIST']; ?>
																	</span>
																</div>
															</div>
														</div>
														<div id="crm_request_freight_ct" class="tab-pane hide">
															<div class="panel-body">
																<div class="col-md-3 col-sm-3 col-xs-3 no-padding" style="border-right:1px solid #e7eaec;">
																	<ul class="folder-list m-b-md list" id="freight_list" style="padding:0;">
																		<span style="font-size: 14px;">
																			<i class="fas fa-hand-point-left"></i> <?php echo $lang['CONTRACT_SEL_SHIPMENT_IN_LIST']; ?>
																		</span>
																	</ul>
																</div>
																
																<div class="col-md-9 col-sm-9 col-xs-9">
																	<div class="row no-margins">
																		<div class="col-md-6">
																			<div style="border-bottom:1px solid #e4e4e4; padding-bottom:4px;" id="F1_title">
																			
																			</div>
																			<div id="freight_1"></div>
																		</div>
																		
																		<div class="col-md-6">
																			<div style="border-bottom:1px solid #e4e4e4; padding-bottom:4px;" id="F2_title">
																	
																			</div>
																			<div id="freight_2"></div>
																		</div>
																	</div>
																	
																	<div class="row" style="margin-top:15px; border-top: 1px solid #e7eaec;">
																		<div class="col-md-8 text-left" id="freight_footer"></div>
																	</div>
																
																	<div class="row" style="margin-top:15px;">
																		<div class="col-md-6" id="freight_copy_to_all"></div>
																		<div class="col-md-6" id="editFreight"></div>
																	</div>
																</div>
															</div>
														</div>
														
														<div id="crm_request_calc_ct" class="tab-pane hide">
															<div class="panel-body">
																<div class="col-md-3 col-sm-3 col-xs-3 no-padding">
																	<ul class="folder-list m-b-md list" id="quote_calc" style="padding:0;">
																		<span style="font-size: 14px;">
																			<i class="fas fa-hand-point-left"></i> <?php echo $lang['CONTRACT_SEL_SHIPMENT_IN_LIST']; ?>
																		</span>
																	</ul>
																</div>
																
																<div class="col-md-9 col-sm-9 col-xs-9" id="schedule_calc_table" style="border-left:1px solid #e7eaec;">
																	<span style="font-size: 14px;">
																		<i class="fas fa-hand-point-left"></i> <?php echo $lang['CONTRACT_SEL_SHIPMENT_IN_LIST']; ?>
																	</span>
																</div>
															</div>
														</div>
														
														<div id="crm_request_proposal_ct" class="tab-pane hide">
															<div class="panel-body">
																<div class="col-md-3 col-sm-3 col-xs-3 no-padding" style="border-right:1px solid #e7eaec;">
																	<ul class="folder-list m-b-md list" id="proposal_list" style="padding:0;">
																		<span style="font-size: 14px;">
																			<i class="fas fa-hand-point-left"></i> <?php echo $lang['CONTRACT_SEL_SHIPMENT_IN_LIST']; ?>
																		</span>
																	</ul>
																</div>
																
																<div class="col-md-9 col-sm-9 col-xs-9">
																	<div class="row no-margins">
																		<div class="col-md-12" id="proposal_content">
																			<span style="font-size: 14px;">
																				<i class="fas fa-hand-point-left"></i> <?php echo $lang['CONTRACT_SEL_SHIPMENT_IN_LIST']; ?>
																			</span>
																		</div>
																	</div>
																</div>
															</div>
														</div>
														
														<div id="crm_request_ordconfirm_ct" class="tab-pane hide">
															<div class="panel-body">
																<div class="col-md-3 col-sm-3 col-xs-3 no-padding">
																	<ul class="folder-list m-b-md list" id="ord_confrim_list" style="padding:0;">
																		<span style="font-size: 14px;">
																			<i class="fas fa-hand-point-left"></i> <?php echo $lang['CONTRACT_SEL_SHIPMENT_IN_LIST']; ?>
																		</span>
																	</ul>
																</div>
																
																<div class="col-md-9 col-sm-9 col-xs-9" id="ord_confrim_ctn" style="border-left:1px solid #e7eaec;">
																	<span style="font-size: 14px;">
																		<i class="fas fa-hand-point-left"></i> <?php echo $lang['CONTRACT_SEL_SHIPMENT_IN_LIST']; ?>
																	</span>
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
							</div>
						</div>
					</div>
				</div>
			
			
				<!-- Manage CRM Management -->
				<div id="db_crm_manag2" class="page hide" style="margin-top:-30px;">
					<div class="row no-margins">
						<div class="wrapper wrapper-content animated fadeInUp">
							<div class="col-md-3 col-sm-4 col-xs-4 no-padding" style=" margin-bottom:20px;">
								<div class="ibox float-e-margins">
									<div class="ibox-content mailbox-content">
										<div class="file-manager">
											<div class="row" id="order_reference_nembers2" style="margin-top:40px;">
												<div class="col-md-12">
													<div class="col-md-3 col-sm-4 col-xs-4" style="position:fixed; left:0; top:30px;">
														<div id="custom-search-input2" style="margin-top:10px;">
															<div class="input-group col-md-7 pull-left">
																<input type="text" class="form-control input-lg search" placeholder="<?php echo $lang['GTT_SEARCH']; ?>" style="height:30px; margin-bottom:10px; border-radius:5px;" />
															</div>
																
															<div class="input-group col-md-4 pull-right">
																<select class="form-control" onchange="crmSchedulePipelineFilter(this.value);" style="height:30px; margin-bottom:10px; border-radius:5px;">
																	<?php echo $list_all_pipelines; ?>
																</select>
															</div>
														</div>
													</div>
												</div>
												
												<div class="col-md-12" style="overflow-y:auto; height:100vh;">
													<ul class="folder-list m-b-md list" id="order_refn_list2" style="padding:0;">
			
													</ul>
													
													<div id="crmSpanner2" class="h1 m-t-xs text-navy hide">
														<span class="loading"></span>
													</div>
												</div>
											</div>
											<div class="clearfix"></div>
										</div>
									</div>
								</div>
							</div>

							<div class="col-md-9 col-sm-8 col-xs-8 animated fadeInRight" style="padding-right:0;">
								<div class="tabs-container">
									<div class="collapse-group">
										<div class="panel panel-primary hide" id="crm_summary2">
											<div class="panel-heading" role="tab" id="headingOne2">
											  <h4 class="panel-title">
												<a role="button" data-toggle="collapse" href="#collapseOne2" aria-expanded="true" aria-controls="collapseOne2" class="trigger collapsed text-uppercase">
												  <?php echo $lang['CONTRACT_SUMMARY']; ?> <span id="sum_refnum2"></span>
												</a>
												
												<span id="summaryDocs2"></span>
												<a href="#" class="pull-right hide" id="startWizard2" data-toggle="modal" onclick="new_req();" data-target="#wizardModal"><i class="fa fa-plus"></i></a>
											  </h4>
											</div>
											
											<div id="collapseOne2" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne2">
												<div class="panel-body">
													<div class="tabs-container">
														<ul class="nav nav-tabs">
															<li class="active hide" id="crm_customer_request_tab2"><a data-toggle="tab" href="#crm_customer_request2"> <?php echo $lang['CONTRACT_CUST_REQUEST']; ?></a></li>
															<li class="hide" id="crm_notes_tab2"><a data-toggle="tab" href="#crm_notes2"> <?php echo $lang['CONTRACT_NOTES']; ?></a></li>
															<li class="hide" id="crm_contract_tab2"><a data-toggle="tab" href="#crm_contract2"> <?php echo $lang['CONTRACT_CONTRACT']; ?></a></li>
														</ul>
														
														<div class="tab-content">
															<div id="crm_customer_request2" class="tab-pane hide active">
																<div class="panel-body">
																	<div id="crmSummarySpanner12" class="h1 m-t-xs text-navy hide" style="position:absolute; top:10px; z-index:99999;">
																		<span class="loading"></span>
																	</div>
																	
																	<div class="row" id="user_summary2">
																		<span style="font-size: 14px;"><i class="fas fa-hand-point-left"></i> Select an order in your list</span>
																	</div>
																</div>
															</div>
														
															<div id="crm_notes2" class="tab-pane hide">
																<div class="panel-body">
																	<div id="crmSummarySpanner22" class="h1 m-t-xs text-navy hide" style="position:absolute; top:10px; z-index:99999;">
																		<span class="loading"></span>
																	</div>
																
																	<div class="row" id="importer_summary2">
																		<span style="font-size: 14px;"><i class="fas fa-hand-point-left"></i> Select an order in your list</span>
																	</div>
																</div>
															</div>
															
															<div id="crm_contract2" class="tab-pane hide">
																<div class="panel-body">
																	<div id="crmSummarySpanner32" class="h1 m-t-xs text-navy hide" style="position:absolute; top:10px; z-index:99999;">
																		<span class="loading"></span>
																	</div>
																
																	<div class="row" id="contract_summary2">
																		<span style="font-size: 14px;"><i class="fas fa-hand-point-left"></i> Select an order in your list</span>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
							
										<div class="panel panel-primary hide" id="crm_request2">
											<div class="panel-heading" role="tab" id="headingTwo2">
											  <h4 class="panel-title">
												<a role="button" data-toggle="collapse" href="#collapseTwo2" aria-expanded="true" aria-controls="collapseTwo2" class="trigger collapsed text-uppercase">
												  <?php echo $lang['CONTRACT_REQUEST']; ?> <span id="req_refnum2"></span>
												</a>
												
												<span id="requestDocs2"></span>
											  </h4>
											</div>
											<div id="collapseTwo2" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo2">
											  <div class="panel-body">
												<div id="request_header" class="col-md-12" style="font-size:12px; color:#aaa; margin-bottom:10px;"></div>
												<div class="tabs-container">
													<ul class="nav nav-tabs">
														<li class="active hide" id="crm_request_sched_tab2"><a data-toggle="tab" href="#crm_request_sched_ct2"> <?php echo $lang['CONTRACT_SCHEDULE']; ?></a></li>
														<li class="hide" id="crm_request_export_tab2"><a data-toggle="tab" href="#crm_request_export_ct2"> <?php echo $lang['CONTRACT_EXPORTER']; ?></a></li>
														<li class="hide" id="crm_request_freight_tab2"><a data-toggle="tab" href="#crm_request_freight_ct2"> <?php echo $lang['CONTRACT_FREIGHT']; ?></a></li>
														<li class="hide" id="crm_request_calc_tab2"><a data-toggle="tab" href="#crm_request_calc_ct2"> <?php echo $lang['CONTRACT_CALCULATION']; ?></a></li>
														<li class="hide" id="crm_request_proposal_tab2"><a data-toggle="tab" href="#crm_request_proposal_ct2"> <?php echo $lang['CONTRACT_PROPOSAL']; ?></a></li>
														<li class="hide" id="crm_request_ordconfirm_tab2"><a data-toggle="tab" href="#crm_request_ordconfirm_ct2"> <?php echo $lang['CONTRACT_ORDER']; ?> </a></li>
													</ul>
													<div class="tab-content">
														<div id="crm_request_sched_ct2" class="tab-pane active hide">
															<div class="panel-body">	
																<div id="scheduleUpdate_info2"></div>
																<div class="table-responsive">
																	<table class="table table-striped table-bordered table-hover dataTables-example" style="font-size:12px;">
																		<thead>
																			<tr>
																				<th><?php echo $lang['CONTRACT_#SHIPMENT']; ?></th>
																				<th><?php echo $lang['CONTRACT_#CON']; ?></th>
																				<th><?php echo $lang['CONTRACT_MT_C']; ?></th>
																				<th><?php echo $lang['CONTRACT_MT_T']; ?></th>
																				<th><?php echo $lang['CONTRACT_ETD']; ?></th>
																				<th><?php echo $lang['CONTRACT_ETA']; ?></th>
																				<th id="edit_schedule_btn2" class="hide"><?php echo $lang['CONTRACT_EDIT']; ?></th>
																				<th><?php echo $lang['CONTRACT_MAIL']; ?></th>
																			</tr>
																		</thead>
																		<tbody id="request_schedule2">
																			<tr>
																				<td colspan="8">
																					<span style="font-size: 14px;">
																						<i class="fas fa-hand-point-left"></i> <?php echo $lang['CONTRACT_SEL_SHIPMENT_IN_LIST']; ?>
																					</span>
																				</td>
																			</tr>
																		</tbody>
																	</table>
																	
																	<div id="addShipmentROW2"></div>
																</div>
															</div>
														</div>
														<div id="crm_request_export_ct2" class="tab-pane hide">
															<div class="panel-body">
																<div class="col-md-12 col-sm-12 col-xs-12" id="schedule_exporter">
																	<span style="font-size: 14px;">
																		<i class="fas fa-hand-point-left"></i> <?php echo $lang['CONTRACT_SEL_SHIPMENT_IN_LIST']; ?>
																	</span>
																</div>
															</div>
														</div>
														<div id="crm_request_freight_ct2" class="tab-pane hide">
															<div class="panel-body">
																<div class="col-md-12 col-sm-12 col-xs-12">
																	<div class="row no-margins">
																		<div class="col-md-6">
																			<div style="border-bottom:1px solid #e4e4e4; padding-bottom:4px;" id="F1_title2">
																			
																			</div>
																			<div id="freight_12"></div>
																		</div>
																		
																		<div class="col-md-6">
																			<div style="border-bottom:1px solid #e4e4e4; padding-bottom:4px;" id="F2_title2">
																	
																			</div>
																			<div id="freight_22"></div>
																		</div>
																	</div>
																	
																	<div class="row" style="margin-top:15px; border-top: 1px solid #e7eaec;">
																		<div class="col-md-8 text-left" id="freight_footer2"></div>
																	</div>
																
																	<div class="row" style="margin-top:15px;">
																		<div class="col-md-6" id="freight_copy_to_all2"></div>
																		<div class="col-md-6" id="editFreight2"></div>
																	</div>
																</div>
															</div>
														</div>
														
														<div id="crm_request_calc_ct2" class="tab-pane hide">
															<div class="panel-body">
																<div class="col-md-12 col-sm-12 col-xs-12" id="schedule_calc_table2">
																	<span style="font-size: 14px;">
																		<i class="fas fa-hand-point-left"></i> <?php echo $lang['CONTRACT_SEL_SHIPMENT_IN_LIST']; ?>
																	</span>
																</div>
															</div>
														</div>
														
														<div id="crm_request_proposal_ct2" class="tab-pane hide">
															<div class="panel-body">
																<div class="col-md-12 col-sm-12 col-xs-12">
																	<div class="row no-margins">
																		<div class="col-md-12" id="proposal_content2">
																			<span style="font-size: 14px;">
																				<i class="fas fa-hand-point-left"></i> <?php echo $lang['CONTRACT_SEL_SHIPMENT_IN_LIST']; ?>
																			</span>
																		</div>
																	</div>
																</div>
															</div>
														</div>
														
														<div id="crm_request_ordconfirm_ct2" class="tab-pane hide">
															<div class="panel-body">
																<div class="col-md-12 col-sm-12 col-xs-12" id="ord_confrim_ctn2">
																	<span style="font-size: 14px;">
																		<i class="fas fa-hand-point-left"></i> <?php echo $lang['CONTRACT_SEL_SHIPMENT_IN_LIST']; ?>
																	</span>
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
							</div>
						</div>
					</div>
				</div>
			
				
				<!-- CRM port costs -->
				<div id="db_port_costs" class="page hide">
					<div class="row">
						<div class="col-lg-6">
							<div class="wrapper wrapper-content animated fadeInUp" style="padding-bottom:10px;">
								<div class="ibox">
									<div class="ibox-title">
										<h5>Port List</h5>
										<div class="ibox-tools hide" id="costAsignNewPort">
											<a href="#" class="pull-right btn btn-primary btn-xs" data-toggle="modal" onclick="portManagement('show','','create');" data-target="#modalPort">
											Create New Port</a>
										</div>
									</div>
									<div class="ibox-content tb_roles">
										<table class="table table-hover roleList" id="posrtListTable" style="font-size:13px;">
											<thead>
												<tr>
													<th style="width:40px;">#</th>
													<th style="width:58%;">Port Name</th>
													<th style="width:20%;">Port Code</th>
													<th id="prtCostPtList" style="width:30px;">---</th>
												</tr>
											</thead>

											<tbody id="list_ports">

											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-lg-6">
							<div class="wrapper wrapper-content animated fadeInUp" style="padding-top:0px;">
								<div class="ibox">
									<div class="ibox-title">
										<h5>Port Costs</h5>
										<div class="ibox-tools">
                                            <span class="label label-warning-light pull-right" id="selectedPortID"></span>
                                        </div>
									</div>
									<div class="ibox-content tb_roles">
										<table class="table table-hover" style="font-size:13px;">
											<thead>
												<tr>
													<th>Name </th>
													<th id="ptCostEditRight" class="hide" style="width:40px;">---</th>
												</tr>
											</thead>

											<tbody id="portCosts">

											</tbody>
										</table>

										<div id="portspanner" class="h1 m-t-xs text-navy hide">
											<span class="loading"></span>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="col-lg-6">
							<div class="wrapper wrapper-content animated fadeInUp" style="padding-top:0px;">
								<div class="ibox">
									<div class="ibox-title">
										<h5>Port Reg-Costs</h5>
										<div class="ibox-tools hide" id="costAsignNewCost">
											<a href="#" class="pull-right btn btn-primary btn-xs" data-toggle="modal" onclick="assignPortCost();" data-target="#modalPortCost">
											Create New Cost</a>
										</div>
									</div>
									<div class="ibox-content tb_roles" style="padding:0;">
										<table class="table table-hover table-responsive" style="font-size:13px;">
											<thead>
												<tr>
													<th id="ptCostEditLeft" class="hide" style="width:40px;">---</th>
													<th>Name </th>
													<th style="width:30px;">---</th>
												</tr>
											</thead>

											<tbody id="regCostList">

											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				
				<!-- Port cost table -->
				<div id="db_port_costs_table" class="page hide">
					<div class="row">
						<div class="col-lg-12">
							<div class="ibox float-e-margins">
								<div class="ibox-title">
									<h5>Cost List</h5>
									<div class="ibox-tools hide" id="costTableNewCost">
										<a href="#" class="pull-right btn btn-primary btn-xs" data-toggle="modal" onclick="assignPortCost();" data-target="#modalPortCost">
											Create New Cost
										</a>
									</div>
								</div>
								
								<div class="ibox-content">
									<div class="table-responsive">
										<table class="table table-striped" style="font-size:13px;">
											<thead>
												<tr>
													<th style="width:10%;">ID</th>
													<th>Name </th>
													<th>Currency </th>
													<th>Cost </th>
													<th>Unit </th>
													<th> --- </th>
												</tr>
											</thead>

											<tbody id="listSysPortCost">

											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				
				<!-- Port -->
				<div id="db_port" class="page hide">
					<div class="row">
						<div class="col-lg-12">
							<div class="ibox float-e-margins">
								<div class="ibox-title">
									<h5>Port List</h5>
									<div class="ibox-tools hide" id="createPortBtn">
										<a href="#" class="pull-right btn btn-primary btn-xs" data-toggle="modal" onclick="portManagement('show','','create');" data-target="#modalPort">
											Create New Port
										</a>
									</div>
								</div>
								
								<div class="ibox-content">
									<div class="table-responsive">
										<table class="table table-striped" style="font-size:13px;">
											<thead>
												<tr>
													<th style="width:10%;">ID</th>
													<th>Port Name </th>
													<th>Port Type </th>
													<th>Port Code </th>
													<th>Contact </th>
													<th>Town </th>
													<th> --- </th>
												</tr>
											</thead>

											<tbody id="listSysPort">

											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				
				<!-- Town -->
				<div id="db_town" class="page hide">
					<div class="row">
						<div class="col-lg-12">
							<div class="ibox float-e-margins">
								<div class="ibox-title">
									<h5>Town list</h5>
									<div class="ibox-tools">
										<a href="#" class="pull-right btn btn-primary btn-xs" data-toggle="modal" onclick="townManagement('show','','create');" data-target="#modalTown">
											Create New Town
										</a>
									</div>
								</div>
								
								<div class="ibox-content">
									<div class="table-responsive">
										<div class="row no-margins">
											<div class="col-sm-4">
												<input type="text" class="form-control input-sm m-b-xs" id="filterTown" placeholder="Search in table">
											</div>
											
											<div class="col-sm-4 pull-right">
												<select class="form-control" id="systCountryId" onchange="filterByCountry(this.value);">
											
												</select>
											</div>
										</div>
										
										<table class="footable table table-stripped toggle-arrow-tiny" style="font-size:13px; margin-top:15px;" data-page-size="20" data-filter=#filterTown>
											<thead>
												<tr>
													<th>Code</th>
													<th>Town Name </th>
													<th>Region </th>
													<th>Country </th>
													<th>ISO </th>
													<th>Language </th>
													<th> --- </th>
												</tr>
											</thead>
										
											<tbody id="listSysTowns">

											</tbody>
											
											<tfoot id="listSysTownsFooter">
												<tr>
													<td colspan="7">
														<ul class="pagination pull-right"></ul>
													</td>
												</tr>
											</tfoot>
										</table>
										
										<div id="townspanner" class="h1 m-t-xs text-navy hide">
											<span class="loading"></span>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				
				<!-- Country -->
				<div id="db_country" class="page hide">
					<div class="row">
						<div class="col-lg-12">
							<div class="ibox float-e-margins">
								<div class="ibox-title">
									<h5>Country list</h5>
									<div class="ibox-tools">
										<a href="#" class="pull-right btn btn-primary btn-xs" data-toggle="modal" onclick="countryManagement('show','','create');" data-target="#modalCountry">
											Create New Country
										</a>
									</div>
								</div>
								
								<div class="ibox-content">
									<div class="table-responsive">
										<table class="table table-striped" style="font-size:13px;">
											<thead>
												<tr>
													<th>Code</th>
													<th>Country Name </th>
													<th>Capitale </th>
													<th>N Population </th>
													<th>Area </th>
													<th>Culture </th>
													<th> --- </th>
												</tr>
											</thead>

											<tbody id="listSysCountry">

											</tbody>
										</table>
										
										<div id="countrypanner" class="h1 m-t-xs text-navy hide">
											<span class="loading"></span>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				
				<!-- Products -->
				<div id="db_product_exp" class="page hide">
					<div class="row">
						<div class="col-lg-6">
							<div class="wrapper wrapper-content animated fadeInUp" style="padding-bottom:10px;">
								<div class="ibox">
									<div class="ibox-title">
										<h5 id="fb_title_pdt">Exporters list</h5>
										<div class="ibox-tools">
                                            <select id="pdtElementSel" onchange="product_exp(this.value);" style="border:none;">
												<option value="exporter">Exporter</option>
												<option value="client">Client</option>
											</select>
                                        </div>
									</div>
									<div class="ibox-content tb_roles">
										<table class="table table-hover roleList" id="exporterstable" style="font-size:13px;">
											<thead>
												<tr>
													<th style="width:40px;">#</th>
													<th>Name </th>
													<th>Town </th>
												</tr>
											</thead>

											<tbody id="list_exporters">

											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
						
						<div class="col-lg-6">
							<div class="wrapper wrapper-content animated fadeInUp" style="padding-bottom:10px;">
								<div class="ibox">
									<div class="ibox-title">
										<h5>List of products</h5>
										<div class="ibox-tools hide" id="createProductBtn">
											<a href="#" data-toggle="modal" data-target="#modalProduct" onclick="productManagement('show','','create');" class="btn btn-primary btn-xs">Create new product</a>
										</div>
									</div>
									
									<div class="ibox-content tb_roles">
										<table class="table table-hover table-responsive" style="font-size:13px;">
											<thead>
												<tr>
													<th style="width:10%;">ID</th>
													<th>Product Code</th>
													<th>Product Name</th>
													<th>Culture</th>
													<th> --- </th>
												</tr>
											</thead>

											<tbody id="listOfproduct">

											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-lg-6">
							<div class="wrapper wrapper-content animated fadeInUp" style="padding-top:0px;">
								<div class="ibox">
									<div class="ibox-title">
										<h5>Product attached</h5>
										<div class="ibox-tools">
                                            <span class="label label-warning-light pull-right" id="selectedExporter"></span>
                                        </div>
									</div>
									<div class="ibox-content tb_roles">
										<table class="table table-hover" style="font-size:13px;">
											<thead>
												<tr>
													<th>Name </th>
													<th>Code </th>
													<th style="width:40px;">---</th>
												</tr>
											</thead>

											<tbody id="list_productA">

											</tbody>
										</table>

										<div id="ProductSpanner" class="h1 m-t-xs text-navy hide">
											<span class="loading"></span>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="col-lg-6">
							<div class="wrapper wrapper-content animated fadeInUp" style="padding-top:0px;">
								<div class="ibox">
									<div class="ibox-title">
										<h5>Product not attached</h5>
									</div>
									<div class="ibox-content tb_roles" style="padding:0;">
										<table class="table table-hover table-responsive" style="font-size:13px;">
											<thead>
												<tr>
													<th style="width:40px;">---</th>
													<th>Name </th>
													<th>Code </th>
												</tr>
											</thead>

											<tbody id="list_productNA">

											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<!-- Logistiques -->
				<div id="db_logistiques" class="page hide" style="margin-top:-20px;">
					<div class="row no-margins">
						<div class="wrapper wrapper-content animated fadeInUp">
							<div class="col-md-3 col-sm-4 col-xs-4 no-padding" style=" margin-bottom:20px;">
								<div class="ibox float-e-margins">
									<div class="ibox-content mailbox-content">
										<div class="file-manager">
											<div class="row" id="logistiques_reference_nembers" style="margin-top:70px;">
												<div class="col-md-12">
													<div class="col-md-3 col-sm-4 col-xs-4" style="position:fixed; left:0; top:30px; padding-right:5px; z-index: 999;">
														<div id="custom-search-input" style="margin-top:10px;">
															<div id="log_default_search">
																<div class="input-group col-md-7 pull-left">
																	<input autocomplete="off" type="text" class="form-control input-lg search" name="logisticSearch" placeholder="Search" style="height:30px; margin-bottom:10px; border-radius:5px;" >
																</div>
																
																<div class="input-group col-md-4 pull-right">
																	<select class="form-control" onchange="logPipelineFilter(this.value);" style="height:30px; margin-bottom:10px; border-radius:5px;">
																		<?php echo $typ_sum_pipeline; ?>
																	</select>
																</div>
															</div>
															
															<div id="log_bl_search" class="hide" style="padding-bottom: 10px;">
																<select data-placeholder="B/L Number." onchange="logistique(0,this.value);" id="logBLSearchId" class="chosen-select" style="width:100%;" tabindex="4">
										
																</select>
															</div>
															
															<div id="log_container_search" class="hide" style="padding-bottom: 10px;">
																<select data-placeholder="Container Number." onchange="logistique(0,this.value);" id="logContainerSearchId" class="chosen-select" style="width:100%;" tabindex="4">
										
																</select>
															</div>
															
															<div class="input-group col-md-12 pull-left">
																<label> <input type="radio" checked="" value="default" onchange="logCustomSearch(this.value);" id="LogSearchDefault" class="logSearchRadio" name="optionsRadios"> <i></i> Default </label> &nbsp;
																<label> <input type="radio" value="container" onchange="logCustomSearch(this.value);" id="LogSearchCont" class="logSearchRadio" name="optionsRadios"> <i></i> Container </label> &nbsp;
																<label> <input type="radio" value="bl" id="LogSearchBL" onchange="logCustomSearch(this.value);" class="logSearchRadio" name="optionsRadios"> <i></i> B/L </label>
															</div>
														</div>
													</div>
												</div>
												
												<div class="col-md-12" style="overflow-y:auto; height:100vh;">
													<ul class="folder-list m-b-md list" id="logis_refn_list" style="padding:0;">
														
													</ul>
													
													<div id="logistiqueSpanner" class="h1 m-t-xs text-navy hide">
														<span class="loading"></span>
													</div>
												</div>
											</div>
											<div class="clearfix"></div>
										</div>
									</div>
								</div>
							</div>

							<div class="col-md-9 col-sm-8 col-xs-8 animated fadeInRight" style="padding-right:0;">
								<div class="tabs-container">
									<div class="tabs-container">
										<ul class="nav nav-tabs">
											<li class="active" id="crm_ocean_tab"><a data-toggle="tab" href="#crm_ocean_ct"> OCEAN Booking </a></li>
											<li class="hide" id="crm_containers_tab"><a data-toggle="tab" href="#crm_addendum_ct"> OCEAN Booking (Add) </a></li>
											<li class="hide" id="crm_onward_carriage_tab"><a data-toggle="tab" href="#crm_onward_carriage_ct"> Onward </a></li>
											<li class="hide" id="crm_carriage_addendum_tab"><a data-toggle="tab" href="#crm_carriage_addendum_ct"> Onward (Add) </a></li>
											<li class="hide" id="traceability_tab"><a data-toggle="tab" href="#traceability_ct"> <?php echo $lang['LOG_TRACEABILITY']; ?> </a></li>
										</ul>
										
										<div class="tab-content">
											<div id="crm_ocean_ct" class="tab-pane active">
												<div class="panel-body">
													<div class="table-responsive" id="freight_ocean">
														<i class="fas fa-hand-point-left"></i> Select a freight in your list
													</div>
												</div>
											</div>
											
											<div id="crm_addendum_ct" class="tab-pane hide">
												<div class="panel-body">
													<div class="table-responsive" id="booking_addendum">
														<i class="fas fa-hand-point-left"></i> Select a freight in your list
													</div>
												</div>
											</div>
											
											<div id="crm_onward_carriage_ct" class="tab-pane hide">
												<div class="panel-body">
													<div class="table-responsive" id="onward_carriage">
														<i class="fas fa-hand-point-left"></i> Select a freight in your list
													</div>
												</div>
											</div>
											
											<div id="crm_carriage_addendum_ct" class="tab-pane hide">
												<div class="panel-body">
													<div class="table-responsive" id="carriage_addendum">
														<i class="fas fa-hand-point-left"></i> Select a freight in your list
													</div>
												</div>
											</div>
											
											<div id="traceability_ct" class="tab-pane hide">
												<div class="panel-body">
													<div class="table-responsive" id="traceability">
														<i class="fas fa-hand-point-left"></i> Select a freight in your list
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
				
				
				<!-- CRM Freight -->
				<div id="db_crm_freight" class="page hide">

					<div class="row no-margins">
						<div class="wrapper wrapper-content animated fadeInUp">
							<div class="col-md-12 animated fadeInRight" style="padding-right:0;">
								<div class="ibox">
									<div class="ibox-title">
										<h5>List of freights</h5>
										<div class="ibox-tools hide" id="sysFreightCreate">
											<a href="#" data-toggle="modal" data-target="#newSystFreightmodal" onclick="newSystemFreight();" class="btn btn-primary btn-xs">Create new freight</a>
										</div>
									</div>
									<div class="ibox-content">
										<div class="table-responsive">
											<table class="table table-striped table-hover" style="font-size:13px;">
												<thead>
													<th>N</th>
													<th>POL</th>
													<th>Incoterm</th>
													<th>POD</th>
													<th>Shipping company</th>
													<th>Packaging</th>
													<th>---</th>
												</thead>
												
												<tbody id="freight_table">
													
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				
				<!-- CRM Ship -->
				<div id="db_crm_ship" class="page hide">

					<div class="row no-margins">
						<div class="wrapper wrapper-content animated fadeInUp">
							<div class="col-md-12 animated fadeInRight" style="padding-right:0;">
								<div class="ibox">
									<div class="ibox-title">
										<h5>List of ships</h5>
										<div class="ibox-tools hide" id="sysShipCreate">
											<a href="#" data-toggle="modal" data-target="#newSystShipmodal" onclick="newSystemShip();" class="btn btn-primary btn-xs">Create new ship</a>
										</div>
									</div>
									<div class="ibox-content">
										<div class="table-responsive">
											<table class="table table-striped table-hover" style="font-size:13px;">
												<thead>
													<th>ID</th>
													<th>Shipname</th>
													<th>MMSI</th>
													<th>IMO</th>
													<th>Photo</th>
													<th>---</th>
												</thead>
												
												<tbody id="ship_table">
													
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				
				<!-- Culture -->
				<div id="db_crm_cult" class="page hide">

					<div class="row no-margins">
						<div class="wrapper wrapper-content animated fadeInUp">
							<div class="col-md-4 hide" id="createCultBox">
								<div class="ibox">
									<div class="ibox-title">
										<h5>Create new culture</h5>
									</div>
									
									<form id="cultureForm" style="margin-bottom: 0px;">
										<div class="ibox-content">
											<div class="form-group">
												<label for="name_culture">Product name</label>
												<input type="text" class="form-control" id="name_culture" placeholder="Enter the name of the culture">
											</div>
										</div>
									</form>
								
									<div class="ibox-footer">
										<button type="button" class="btn btn-primary" onclick="cultureManagement('add','','');"><i class="fa fa-save"></i></button>
									</div>
								</div>
							</div>
							
							<div class="col-md-8 animated fadeInRight">
								<div class="ibox">
									<div class="ibox-title">
										<h5>List of cultures</h5>
									</div>
									<div class="ibox-content">
										<div class="table-responsive">
											<table class="table table-striped table-hover" style="font-size:13px;">
												<thead>
													<th style="width:5%;">N</th>
													<th style="width:85%;">Culture name</th>
													<th style="width:10%;">#</th>
												</thead>
												
												<tbody id="cultures_table">
													
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				
				<!-- Workflow - Process -->
				<div id="db_wf_process" class="page hide">
					<div class="row">
						<div class="col-lg-6">
							<div class="wrapper wrapper-content animated fadeInUp" style="padding-bottom:10px;">
								<div class="ibox">
									<div class="ibox-title">
										<h5>Process list</h5>
									</div>
									<div class="ibox-content tb_roles">
										<table class="table table-hover processList" id="rolestable" style="font-size:13px;">
											<thead>
												<tr>
													<th style="width:40px;">#</th>
													<th style="width:82%;">Process name </th>
													<th style="width:10%;">---</th>
												</tr>
											</thead>

											<tbody id="list_wfProcess">

											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
						
						<div class="col-lg-6">
							<div class="wrapper wrapper-content animated fadeInUp" style="padding-bottom:10px;">
								<div class="ibox">
									<div class="ibox-title">
										<h5>Create Process</h5>
									</div>
									<div class="ibox-content tb_roles">
										<form id="processForm">
											<div style="font-size:13px;">
												<div class="form-group">
													<label for="nPForm_process_name">Process Name</label>
													<input type="text" class="form-control" id="nPForm_process_name">
												</div>

												<div class="form-group">
													<label for="nPForm_ord_order_id">ord_order_id</label>
													<input type="text" class="form-control" id="nPForm_ord_order_id">
												</div>

												<div class="form-group">
													<label for="nPForm_ord_schedule_id">ord_schedule_id</label>
													<input type="text" class="form-control" id="nPForm_ord_schedule_id">
												</div>

												<div class="form-group">
													<button type="submit" onclick="wfProcessManagement('add','');" class="btn btn-primary"><i class="fa fa-save"></i></button>
													<button type="submit" onclick="clearWf_newProcessForm()" class="btn btn-danger"><i class="fa fa-ban"></i></button>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-lg-6">
							<div class="wrapper wrapper-content animated fadeInUp" style="padding-top:0px;">
								<div class="ibox">
									<div class="ibox-title">
										<h5>Trigger in process</h5>
										<div class="ibox-tools">
											<span class="label label-warning-light pull-right" id="selectedProcess"></span>
										</div>
									</div>
									<div class="ibox-content tb_roles">
										<table class="table table-hover" style="font-size:13px;">
											<thead>
												<tr>
													<th>ID </th>
													<th>Trigger Name </th>
													<th>Sequence </th>
												</tr>
											</thead>

											<tbody id="list_triggerInP">

											</tbody>
										</table>

										<div id="triggerInPspanner" class="h1 m-t-xs text-navy hide">
											<span class="loading"></span>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				
				<!-- Workflow - Trigger -->
				<div id="db_wf_trigger" class="page hide">
					<div class="row">
						<div class="col-lg-6">
							<div class="wrapper wrapper-content animated fadeInUp" style="padding-bottom:10px;">
								<div class="ibox">
									<div class="ibox-title">
										<h5>Trigger list</h5>
										<div class="ibox-tools">
											Filter by Process : 
											<select onchange="triggerFilterByProcess(this.value);">
												<option>---</option>
												<?php echo $wf_processList; ?>
											</select>
										</div>
									</div>
									<div class="ibox-content">
										<table class="table table-hover triggerList" id="triggerWfTable" style="font-size:13px;">
											<thead>
												<tr>
													<th style="width:40px;">ID</th>
													<th style="width:82%;">Trigger name </th>
													<th style="width:10%;">#</th>
												</tr>
											</thead>

											<tbody id="list_wfTrigger">

											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
						
						<div class="col-lg-6" id="newRoleForm_RoleDef">
							<div class="wrapper wrapper-content animated fadeInUp" style="padding-bottom:10px;">
								<div class="ibox">
									<div class="ibox-title">
										<h5>Create Trigger</h5>
									</div>
									<div class="ibox-content">
										<form id="triggerForm">
											<div style="font-size:13px;">
												
												<div class="form-group">
													<label for="nTForm_trigger_name">Trigger Name</label>
													<input type="text" class="form-control" id="nTForm_trigger_name">
												</div>
												
												<div class="form-group">
													<label for="nTForm_id_process">Process</label>
													<select id="nTForm_id_process" class="form-control">
														<option>---</option>
														<?php echo $wf_processList; ?>
													</select>
												</div>
												
												<div class="form-group">
													<label for="nTForm_sequence_nr">Sequence Nr</label>
													<input type="text" class="form-control" id="nTForm_sequence_nr">
												</div>

												<div class="form-group">
													<button type="submit" onclick="wfTriggerManagement('add','');" class="btn btn-primary"><i class="fa fa-save"></i></button>
													<button type="submit" onclick="clearWf_newTriggerForm()" class="btn btn-danger"><i class="fa fa-ban"></i></button>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				
				<!-- Workflow - Group -->
				<div id="db_wf_group" class="page hide">
					<div class="row">
						<div class="col-lg-6">
							<div class="wrapper wrapper-content animated fadeInUp" style="padding-bottom:10px;">
								<div class="ibox">
									<div class="ibox-title">
										<h5>Group list</h5>
									</div>
									<div class="ibox-content tb_roles">
										<table class="table table-hover groupList" id="rolestable" style="font-size:13px;">
											<thead>
												<tr>
													<th style="width:40px;">#</th>
													<th style="width:82%;">Group name </th>
													<th style="width:10%;">---</th>
												</tr>
											</thead>

											<tbody id="list_wfGroup">

											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
						
						<div class="col-lg-6">
							<div class="wrapper wrapper-content animated fadeInUp" style="padding-bottom:10px;">
								<div class="ibox">
									<div class="ibox-title">
										<h5>Create Group</h5>
									</div>
									<div class="ibox-content tb_roles">
										<form id="groupForm">
											<div style="font-size:13px;">
												<div class="form-group">
													<label for="nGForm_group_name">Group Name</label>
													<input type="text" class="form-control" id="nGForm_group_name">
												</div>

												<div class="form-group">
													<button type="submit" onclick="wfGroupManagement('add','');" class="btn btn-primary"><i class="fa fa-save"></i></button>
													<button type="submit" onclick="clearWf_newGroupForm()" class="btn btn-danger"><i class="fa fa-ban"></i></button>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-lg-6">
							<div class="wrapper wrapper-content animated fadeInUp" style="padding-top:0px;">
								<div class="ibox">
									<div class="ibox-title">
										<h5>Member in group</h5>
										<div class="ibox-tools">
											<span class="label label-warning-light pull-right" id="selectedGroup"></span>
										</div>
									</div>
									<div class="ibox-content tb_roles">
										<table class="table table-hover" style="font-size:13px;">
											<thead>
												<tr>
													<th>Member name </th>
													<th>Company </th>
													<th>--</th>
												</tr>
											</thead>

											<tbody id="list_usersInG">

											</tbody>
										</table>

										<div id="memberInGspanner" class="h1 m-t-xs text-navy hide">
											<span class="loading"></span>
										</div>
									</div>
								</div>
							</div>
						</div>
						
						
						<div class="col-lg-6">
							<div class="wrapper wrapper-content animated fadeInUp" style="padding-top:0px;">
								<div class="ibox">
									<div class="ibox-title">
										<h5>User list</h5>
									</div>
									<div class="ibox-content tb_roles">
										<table class="table table-hover" style="font-size:13px;">
											<thead>
												<tr>
													<th>--</th>
													<th>User name </th>
													<th>Company </th>
												</tr>
											</thead>

											<tbody id="list_usersWFgroup">

											</tbody>
										</table>

										<div id="userLspanner" class="h1 m-t-xs text-navy hide">
											<span class="loading"></span>
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

<div class="footer fixed">
    <div class="pull-left">
		<strong>Copyright</strong> <a target="_blanck" href="http://dev4impact.com/">dev4impact ltd.</a> &copy; 2018 - <?php echo date("Y");?>
	</div>

	<div class="pull-right">
		@iCoop.live - Version 1.0
	</div>
</div>

</div>
</div>

	<div class="bg-success hide" id="icoop_msg" onclick="msgManage();" style="position:fixed; top:140px; right:0; padding: 5px 20px 5px 10px; font-size: 28px; border-radius: 8px 0 0 8px; cursor: pointer;">
		<i class="fas fa-envelope"></i>
    </div>

	<div id="sideBarBtnToggle" class="hide animated bg-success" onclick="volet_droit_animated();" style="opacity:0.8; padding-top:13px;"><i class="fa fa-caret-right"></i></div>
    <div id="right-sidebar" class="animated" style="padding:20px; max-width:308px; background-color: rgba(255, 255, 255, 0.6);">
        <div class="sidebar-container">
			<div id="rightInfos">

			</div>
        </div>
    </div>

	
	<!-- 
	/*************
	/** Modals **
	/*************
	-->
	
	<div class="modal fade bs-example-modal-sm" id="deleteConfirmation" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="deleteConfirmationHeader" style="color:#1ab394"></h4>
				</div>

				<div class="modal-body" id="deleteConfirmationContent"></div>
				<div class="modal-footer" id="deleteConfirmationFooter"></div>
			</div>
		</div>
	</div>
	
	<!-- Messages -->
	<div class="modal fade bs-example-modal-lg" id="icoopMsgModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg" style="width: 600px;">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" style="color:#1ab394">New Mail</h4>
				</div>

				<div class="modal-body">
					<form class="form-horizontal" method="get" id="eMailform">
						<div class="form-group"><label class="col-sm-2 control-label">To:</label>
							<div class="col-sm-10">
								<div id="list_eMailTo" style="padding-top:8px;"></div>
								<div id="control_eMailTo">
									<a href="#" data-toggle="modal" data-target="#eMailToModal">Add</a>
								</div>
							</div>
						</div>
						<div class="form-group"><label class="col-sm-2 control-label">Subject:</label>
							<div class="col-sm-10"><input type="text" class="form-control" id="eMailSubject" value=""></div>
						</div>
						<div class="form-group"><label class="col-sm-2 control-label">Cc:</label>
							<div class="col-sm-10">
								<div id="list_eMailCc" style="padding-top:8px;"></div>
								<div id="control_eMailCc">
									<a href="#" data-toggle="modal" data-target="#eMailCcModal">Add</a>
								</div>
							</div>
						</div>
						<div class="form-group"><label class="col-sm-2 control-label">Bcc:</label>
							<div class="col-sm-10">
								<div id="list_eMailBcc" style="padding-top:8px;"></div>
								<div id="control_eMailBcc">
									<a href="#" data-toggle="modal" data-target="#eMailBccModal">Add</a>
								</div>
							</div>
						</div>
					</form>
					
					<div class="row">
						<div class="col-lg-12">
							<div class="mail-text" style="min-height:290px;">
								<div class="summernote" id="eMailText">
								
								</div>
								<div class="clearfix"></div>
							</div>
						</div>

						<div class="col-lg-12">
							<table style="font-size:12px; margin-top:12px;">
								<tr><td><strong>Company :</strong></td><td> &nbsp;<?php echo $_SESSION['company_name']; ?></td></tr>
								<tr><td><strong>Name :</strong></td><td> &nbsp;<?php echo $_SESSION['name']; ?></td></tr>
								<tr><td><strong>Email :</strong></td><td> &nbsp;<?php echo $_SESSION['p_email']; ?></td></tr>
								<tr><td><strong>Phone :</strong></td><td> &nbsp;<?php echo $_SESSION['p_phone']; ?></td></tr>
							</table>
						</div>
					</div>
				</div>
				
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" onclick="sendeMAil();"><i class="fa fa-reply"></i> Send</button>
					<button type="button" class="btn btn-white" data-dismiss="modal" onclick="resetSendeMAil();"><i class="fa fa-times"></i> Discard</button>
				</div>
			</div>
		</div>
	</div>
	
	<div class="modal fade bs-example-modal-lg" id="eMailToModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" style="color:#1ab394">
						Email sent To :
					</h4>
				</div>

				<div class="modal-body">
					<div class="row">
						<div class="col-md-6">
							<h4 class="text-center">List of Contacts</h4>
						</div>
						
						<div class="col-md-6">
							<h4 class="text-center">Contact Added</h4>
						</div>
				
						<div class="col-md-12" id="box_eMailTo">
							
						</div>
					</div>
				</div>
				
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" onclick="showEmailToList();"><i class="fa fa-check"></i></button>
					<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i></button>
				</div>
			</div>
		</div>
	</div>
	
	
	<div class="modal fade bs-example-modal-lg" id="eMailCcModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" style="color:#1ab394">
						Email Cc :
					</h4>
				</div>

				<div class="modal-body">
					<div class="row">
						<div class="col-md-6">
							<h4 class="text-center">List of Contacts</h4>
						</div>
						
						<div class="col-md-6">
							<h4 class="text-center">Contact Added</h4>
						</div>
				
						<div class="col-md-12" id="box_eMailCc">
							
						</div>
					</div>
				</div>
				
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" onclick="showEmailCcList();"><i class="fa fa-check"></i></button>
					<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i></button>
				</div>
			</div>
		</div>
	</div>
	
	
	<div class="modal fade bs-example-modal-lg" id="eMailBccModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" style="color:#1ab394">
						Email Bcc :
					</h4>
				</div>

				<div class="modal-body">
					<div class="row">
						<div class="col-md-6">
							<h4 class="text-center">List of Contacts</h4>
						</div>
						
						<div class="col-md-6">
							<h4 class="text-center">Contact Added</h4>
						</div>
				
						<div class="col-md-12" id="box_eMailBcc">
							
						</div>
					</div>
				</div>
				
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" onclick="showEmailBccList();"><i class="fa fa-check"></i></button>
					<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i></button>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Modal Survey -->
	<div class="modal fade bs-example-modal-md" id="surveyModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="surveyModalHeader" style="color:#1ab394"></h4>
				</div>

				<div class="modal-body" id="surveyModalContent"></div>
				<div class="modal-footer" id="surveyModalFooter"></div>
			</div>
		</div>
	</div>
	
	<!-- Modal Edit Certification -->
	<div class="modal fade bs-example-modal-sm" id="editPlantCertification" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="editPlantCertificationHeader" style="color:#1ab394">Edit Certificate</h4>
				</div>

				<div class="modal-body" id="editPlantCertificationContent"></div>
				<div class="modal-footer" id="editPlantCertificationFooter"></div>
			</div>
		</div>
	</div>
	
	<!-- Modal New Mbtiles -->
	<div class="modal fade bs-example-modal-sm" id="projectQMbtiles" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="projectQMbtilesHeader" style="color:#1ab394">Mbtile upload</h4>
				</div>

				<div class="modal-body" id="projectQMbtilesContent">
					<form id="upload_mbtilesForm" action="upload_mbtiles.php" method="post" enctype="multipart/form-data" style="background:#efefef; overflow:hidden; padding:0 0 10px 10px;">
						<div class="form-group file-area" style="padding-right:10px;">
							<input accept="*" type="file" name="mbtile" id="po_mbtiles" onchange="mbtilesFile(this.value);" />
							<div class="file-dummy po_bg_default">
							  <div id="mbtiles_success" class="success hide">Great, your file is selected. Keep on.</div>
							  <div id="mbtiles_default" class="default"><i class="fa fa-file-pdf-o"></i> Drop files here or click to upload.</div>
							</div>
						</div>
						
						<div class="form-group" style="padding-right:10px;">
							<label class="ord_sum_label">Description</label><br/>
							<textarea class="form-control" id="mbtiles_desc" name="mbtiles_desc"></textarea>
						</div>
						
						<div class="form-group" style="padding-right:10px;">
							<label class="ord_sum_label">Map Type</label><br/>
							<input type="text" class="form-control" id="mbtiles_maptype" name="mbtiles_maptype"/>
						</div>
						
						<input id="mbtiles_id_project" name="id_project" type="hidden" />
						<button id="uploadMbtilesBtn" onclick="mbtiles();" disabled class="btn btn-primary"><i class="fa fa-upload"></i></button>
					</form>
				</div>
			</div>
		</div>
	</div>
	
	
	<!-- Modal Trace Region -->
	<div class="modal fade bs-example-modal-sm" id="traceRegionModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-body">
					<form id="traceRegionModalForm"> 
						<div class="form-group">
							<label for="tracePlantation_id">Plantation ID</label>
							<input id="tracePlantation_id" type="number" class="form-control" />
						</div>
						
						<div class="form-group">
							<label for="traceRegion_id">Region ID</label>
							<input id="traceRegion_id" type="number" class="form-control" />
						</div>
					</form>
				</div>
				
				<input id="traceRegion_plant_line_id" type="hidden" class="form-control" />
				
				<div class="modal-footer">
					<button type="button" class="btn btn-warning pull-left" onclick="deleteTraceRegion();"><i class="fa fa-trash"></i></button>
					<button type="button" class="btn btn-primary" onclick="editTraceRegion();"><i class="fa fa-save"></i></button>
					<button type="button" class="btn btn-danger" data-dismiss="modal" onclick="clearTraceRegionForm();"><i class="fa fa-ban"></i></button>
				</div>
			</div>
		</div>
	</div>
	
	
	<!-- Modal Towns Coords -->
	<div class="modal fade bs-example-modal-md" id="townsCoordsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-body" style="padding:0;">
					<div id="townsCoordsMap" style="height:360px; width:100%;"></div>
					
					<form id="townsCoordsModalForm" style="margin:0; padding: 10px 20px 10px 20px;">  
						<input id="townCoordsModal_id_town" type="hidden" class="form-control" />
						<div class="row"> 
							<div class="col-md-6">
								<div class="form-group">
									<label for="townCoordsModal_x">X</label>
									<input id="townCoordsModal_x" type="text" class="form-control" />
								</div>
							</div>
							
							<div class="col-md-6">
								<div class="form-group">
									<label for="townCoordsModal_y">Y</label>
									<input id="townCoordsModal_y" class="form-control" />
								</div>
							</div>
							
							<div class="col-md-12 text-center">
								<label id="townCoordsModal_TownName"></label>
							</div>
						</div>
					</form>
				</div>
				
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" onclick="editTownCoords();"><i class="fa fa-save"></i></button>
					<button type="button" class="btn btn-danger" data-dismiss="modal" onclick="cleareditTownCoordsForm();"><i class="fa fa-ban"></i></button>
				</div>
			</div>
		</div>
	</div>
	
	
	<!-- Modal Contract -->
	<div class="modal fade bs-example-modal-md" id="contractModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="contractModalLabel" style="color:#1ab394">
						<?php echo $lang['CONTRACT_MODAL_TITLE']; ?>
					</h4>
				</div>

				<div class="modal-body">
					<form id="contractModalForm">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group" id="contractModal_contractor_box">
								</div>
							</div>
							
							<div class="col-md-6">
								<div class="form-group">
									<label for="contractModal_id_contracting_party"><?php echo $lang['CONTRACT_MODAL_CONTRACTING_PARTY']; ?></label>
									<select id="contractModal_id_contracting_party" class="chosen-select"></select>
								</div>
							</div>
						</div>
						
						<div class="row"> 
							<div class="col-md-4">
								<div class="form-group">
									<label for="contractModal_contract_code"><?php echo $lang['CONTRACT_MODAL_CONTRACT_CODE']; ?></label>
									<input id="contractModal_contract_code" type="text" class="form-control" />
								</div>
							</div>
							
							<div class="col-md-4">
								<div class="form-group">
									<label for="contractModal_id_contract_type"><?php echo $lang['CONTRACT_MODAL_CONTRACT_TYPE']; ?></label>
									<select id="contractModal_id_contract_type" class="form-control"></select>
								</div>
							</div>
							
							<div class="col-md-4">
								<div class="form-group">
									<label for="contractModal_contract_date"><?php echo $lang['CONTRACT_MODAL_CONTRACT_DATE']; ?></label>
									<div class="input-group date">
										<span class="input-group-addon"><i class="fa fa-calendar"></i></span>   
										<input type="text" class="form-control edit_delivery_date" value="<?php echo gmdate("Y/m/d H:i"); ?>" id="contractModal_contract_date" />
									</div>
								</div>
							</div>
						</div>
						
						<div class="row" style="background:#e4e4e4;padding-top:5px; margin-top:15px;"> 
							<div class="col-md-6">
								<div class="form-group">
									<label for="contractModal_start_date"><?php echo $lang['CONTRACT_MODAL_START_DATE']; ?></label>
									<div class="input-group date">
										<span class="input-group-addon"><i class="fa fa-calendar"></i></span>   
										<input type="text" class="form-control edit_delivery_date" value="<?php echo gmdate("Y/m/d H:i"); ?>" id="contractModal_start_date" />
									</div>
								</div>
							</div>
							
							<div class="col-md-6">
								<div class="form-group">
									<label for="contractModal_end_date"><?php echo $lang['CONTRACT_MODAL_END_DATE']; ?></label>
									<div class="input-group date">
										<span class="input-group-addon"><i class="fa fa-calendar"></i></span>   
										<input type="text" class="form-control edit_delivery_date" id="contractModal_end_date" />
									</div>
								</div>
							</div>
						</div>
						
						<div class="row" style="margin-top:15px;"> 
							<div class="form-group">
								<label for="contractModal_contract_desc"><?php echo $lang['CONTRACT_MODAL_CONTRACT_DESC']; ?></label>
								<textarea id="contractModal_contract_desc" class="form-control"></textarea>
							</div>
						</div>
					</form>
				</div>
				
				<div class="modal-footer" id="contractModalFooter">
					
				</div>
			</div>
		</div>
	</div>
	
	
	<!-- Modal Project -->
	<div class="modal fade bs-example-modal-md" id="projectModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="projectModalLabel" style="color:#1ab394">
						<?php echo $lang['PROJECT_MODAL_TITLE']; ?>
					</h4>
				</div>

				<div class="modal-body">
					<form id="projectModalForm">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for="projectModal_project_name"><?php echo $lang['PROJECT_MODAL_NAME']; ?></label>
									<input id="projectModal_project_name" type="text" class="form-control" />
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="projectModal_project_type"><?php echo $lang['PROJECT_MODAL_TYPE']; ?></label>
									<select id="projectModal_project_type" class="form-control"></select>  
								</div>
							</div>
							
							<div class="col-md-6">
								<div class="form-group">
									<label for="projectModal_project_status"><?php echo $lang['PROJECT_MODAL_STATUS']; ?></label>
									<select id="projectModal_project_status" class="form-control"></select>  
								</div>
							</div>
						</div>
						
						<hr/>
						
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="projectModal_id_company"><?php echo $lang['PROJECT_MODAL_COMPANY']; ?></label>
									<select id="projectModal_id_company" class="form-control" onchange="getCountryOfSelComp(this.value);"></select>  
								</div>
							</div>
							
							<div class="col-md-6"> 
								<div class="form-group">
									<label for="projectModal_cooperative_id"><?php echo $lang['PROJECT_MODAL_COOPERATIVE']; ?></label>
									<select id="projectModal_cooperative_id" class="form-control"></select>  
								</div>
							</div>
							
							<div class="col-md-6 hide" id="HQ_field"> 
								<div class="form-group">
									<label for="projectModal_id_primary_company"><?php echo $lang['PROJECT_MODAL_OWNER_HQ']; ?></label>
									<select id="projectModal_id_primary_company" class="form-control"></select>  
								</div>
							</div>
							
							<div class="col-md-6"> 
								<div class="form-group">
									<label for="projectModal_project_manager_id"><?php echo $lang['PROJECT_MODAL_BLOCK_SUPERVISOR']; ?></label>
									<select id="projectModal_project_manager_id" class="form-control"></select>  
								</div>
							</div>
						</div>
						
						<hr/>
						
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="projectModal_id_culture"><?php echo $lang['PROJECT_MODAL_CULTURE']; ?></label>
									<select id="projectModal_id_culture" class="form-control"></select>  
								</div>
							</div>
							
							<div class="col-md-4">
								<div class="form-group">
									<label for="projectModal_id_country"><?php echo $lang['PROJECT_MODAL_COUNTRY']; ?></label>
									<select id="projectModal_id_country" class="form-control" onchange="regionsOfSelCountry(this.value);"></select>  
								</div>
							</div>
							
							<div class="col-md-4">
								<div class="form-group">
									<label for="projectModal_region_name"><?php echo $lang['PROJECT_MODAL_REGION']; ?></label>
									<select id="projectModal_region_name" class="form-control"></select>  
								</div>
							</div>
						</div>
						
						<div class="row" style="background:#e4e4e4;padding-top:5px; margin-top:15px;">
							<div class="col-md-6">
								<div class="form-group">
									<label for="projectModal_start_date"><?php echo $lang['PROJECT_MODAL_START_DATE']; ?></label>
									<div class="input-group date">
										<span class="input-group-addon"><i class="fa fa-calendar"></i></span>   
										<input type="text" class="form-control edit_delivery_date" value="<?php echo gmdate("Y/m/d"); ?>" id="projectModal_start_date">
									</div>
								</div>
							</div>
						
							<div class="col-md-6">
								<div class="form-group">
									<label for="projectModal_due_date"><?php echo $lang['PROJECT_MODAL_DUE_DATE']; ?></label>
									<div class="input-group date">
										<span class="input-group-addon"><i class="fa fa-calendar"></i></span>   
										<input type="text" class="form-control edit_delivery_date" id="projectModal_due_date">
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
				
				<div class="modal-footer" id="projectModalFooter">
					<button type="button" class="btn btn-primary" onclick="saveProject();"><i class="fa fa-save"></i></button>
					<button type="button" class="btn btn-danger" data-dismiss="modal" onclick="clearProjectForm();"><i class="fa fa-ban"></i></button>
				</div>
			</div>
		</div>
	</div>
	
	
	<!-- Modal Task -->
	<div class="modal fade bs-example-modal-lg" id="tasksModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="projectTaskModalLabel" style="color:#1ab394">
						<?php echo $lang['PROJECT_TASK_MODAL_TITLE']; ?>
					</h4>
				</div>

				<div class="modal-body">
					<form id="projectTaskModalForm">
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label for="projectTaskModal_region_1"><?php echo $lang['PROJECT_TASK_MODAL_REGION_1']; ?></label>
									<select id="projectTaskModal_region_1" class="form-control" onchange="townsOfSelRegionId(this.value);"></select>  
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="projectTaskModal_region_2"><?php echo $lang['PROJECT_TASK_MODAL_REGION_2']; ?></label>
									<select id="projectTaskModal_region_2" class="form-control"  onchange="townsOfSelRegionName(2);"></select>  
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="projectTaskModal_region_3"><?php echo $lang['PROJECT_TASK_MODAL_REGION_3']; ?></label>
									<select id="projectTaskModal_region_3" class="form-control" onchange="townsOfSelRegionName(3);"></select>  
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="projectTaskModal_region_4"><?php echo $lang['PROJECT_TASK_MODAL_REGION_4']; ?></label>
									<select id="projectTaskModal_region_4" class="form-control" onchange="townsOfSelRegionName(4);"></select>  
								</div>
							</div>
							
							<input type="hidden" value="" id="projectTaskModal_id_country" />
						</div>
						
						<div class="row">
							<div class="col-md-6">
								<h4 class="text-center"><?php echo $lang['PROJECT_TASK_MODAL_TOWNS_LIST']; ?></h4>
							</div>
							
							<div class="col-md-6">
								<h4 class="text-center"><?php echo $lang['PROJECT_TASK_MODAL_ADD_TOWNS']; ?></h4>
							</div>
							
							<div class="col-md-12" id="list_townProjectTask">
								
							</div>
						</div>
					</form>
				</div>
				
				<div class="modal-footer" id="projectTaskModalFooter">
					<button type="button" class="btn btn-primary" onclick="saveProjectTask();"><i class="fa fa-save"></i></button>
					<button type="button" class="btn btn-danger" data-dismiss="modal" onclick="clearProjectTaskForm();"><i class="fa fa-ban"></i></button>
				</div>
			</div>
		</div>
	</div>
	
	
	<!-- Modal Workflow Group -->
	<div class="modal fade bs-example-modal-sm" id="modalWfGroup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="wf_groupModalLabel" style="color:#1ab394">
						Workflow Group
					</h4>
				</div>

				<div class="modal-body">
					<form id="wfGroupModalForm">
						<div class="form-group">
							<label for="wfGroupModal_group_name">Group Name</label>
							<input type="text" class="form-control" id="wfGroupModal_group_name">
						</div>
					</form>
				</div>
				
				<input type="hidden" value="" id="wfGroupModal_id_msg_group" />
				<div class="modal-footer" id="wf_GroupModalFooter"></div>
			</div>
		</div>
	</div>
	
	
	<!-- Modal Workflow Trigger -->
	<div class="modal fade bs-example-modal-sm" id="modalWfTrigger" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="wf_triggerModalLabel" style="color:#1ab394">
						Workflow Trigger
					</h4>
				</div>

				<div class="modal-body">
					<form id="wfTriggerModalForm">
						<div class="form-group">
							<label for="wfTriggerModal_trigger_name">Trigger Name</label>
							<input type="text" class="form-control" id="wfTriggerModal_trigger_name">
						</div>
						
						<div class="form-group">
							<label for="wfTriggerModal_id_process">Process</label>
							<select id="wfTriggerModal_id_process" class="form-control">
								<option>---</option>
								<?php echo $wf_processList; ?>
							</select>
						</div>
						
						<div class="form-group">
							<label for="wfTriggerModal_sequence_nr">Sequence Nr</label>
							<input type="text" class="form-control" id="wfTriggerModal_sequence_nr">
						</div>
					</form>
				</div>
				
				<input type="hidden" value="" id="wfTriggerModal_id_trigger" />
				<div class="modal-footer" id="wf_TriggerModalFooter"></div>
			</div>
		</div>
	</div>
	
	
	<!-- Modal Workflow Process -->
	<div class="modal fade bs-example-modal-sm" id="modalWfProcess" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="wf_processModalLabel" style="color:#1ab394">
						Workflow Process
					</h4>
				</div>

				<div class="modal-body">
					<form id="wfPocessModalForm">
						<div class="form-group">
							<label for="wfProcessModal_process_name">Process Name</label>
							<input type="text" class="form-control" id="wfProcessModal_process_name">
						</div>
						
						<div class="form-group">
							<label for="">ord_order_id</label>
							<input type="text" class="form-control" id="wfProcessModal_ord_order_id">
						</div>
						
						<div class="form-group">
							<label for="">ord_schedule_id</label>
							<input type="text" class="form-control" id="wfProcessModal_ord_schedule_id">
						</div>
					</form>
				</div>
				
				<input type="hidden" value="" id="wfProcessModal_id_process" />
				<div class="modal-footer" id="wf_ProcessModalFooter"></div>
			</div>
		</div>
	</div>
	
	
	<!-- Modal Move shipment list -->
	<div class="modal fade bs-example-modal-md" id="shipmentListMove" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="shipmentListMoveHeader" style="color:#1ab394">
						Select a new shipment
					</h4>
				</div>

				<div class="modal-body">
					<table class="table table-bordered">
						<thead>
							<th>no_imp</th>
							<th>no_sup</th>
							<th>--</th>
						</thead>

						<tbody id="shipmentListMoveContent"></tbody>
					</table>
				</div>
				
				<input type="hidden" value="" id="actualShipmentScheduleID" />
				<div class="modal-footer" id="shipmentListMoveFooter"></div>
			</div>
		</div>
	</div>
	
	<!-- Modal Story -->
	<div class="modal fade bs-example-modal-lg" id="modalStory" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" style="color:#1ab394">Story Management</h4>
				</div>
				
				<div class="modal-body" id="modalStoryContent">
					<form id="storyForm" action="upload_story_img.php" method="post" enctype="multipart/form-data">
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="MDstory_titleen">Story title EN *</label>
									<textarea id="MDstory_titleen" class="form-control input-sm" required></textarea>
								</div>
								
								<div class="form-group">
									<label for="MDstory_titlede">Story title DE</label>
									<textarea id="MDstory_titlede" class="form-control input-sm"></textarea>
								</div>
								
								<div class="form-group">
									<label for="MDstory_titlefr">Story title FR</label>
									<textarea id="MDstory_titlefr" class="form-control input-sm"></textarea>
								</div>
							</div>
							
							<div class="col-md-4">
								<div class="form-group">
									<label for="MDstory_titlept">Story title PT</label>
									<textarea id="MDstory_titlept" class="form-control input-sm"></textarea>
								</div>
								
								<div class="form-group">
									<label for="MDstory_titlees">Story title ES</label>
									<textarea id="MDstory_titlees" class="form-control input-sm"></textarea>
								</div>
								
								<div class="form-group">
									<label for="MDstory_titleit">Story title IT</label>
									<textarea id="MDstory_titleit" class="form-control input-sm"></textarea>
								</div>
							</div>
							
							<div class="col-md-4">
								<div class="form-group">
									<label for="MDid_country">Country *</label>
									<select id="MDid_country" class="form-control input-sm" required onchange="MDstoryExporter(this.value);">
										<option value="">Select a country</option>
										<option value="1">Ivory Coast</option>
										<option value="2">Senegal</option>
										<option value="3">Mozambique</option>
										<option value="4">Tanzania</option>
										<option value="5">Sudan</option>
										<option value="6">Cambodia</option>
									</select>
								</div>
								
								<div class="form-group">
									<label for="MDid_exporter">Exporter *</label>
									<select id="MDid_exporter" class="form-control input-sm" required>
										<option value="">Select an exporter</option>
									</select>
								</div>
								
								<div class="form-group">
									<label for="MDmedia_type">Media Type *</label>
									<select id="MDmedia_type" class="form-control input-sm" required onchange="MDstoryMedia(this.value);">
										<option value="">Select a media type</option>
										<option value="1">Video</option>
										<option value="2">Picture</option>
									</select>
								</div>
								
								<div class="form-group" id="MDmedia_select">
									
								</div>
							</div>
						</div>
					</form>
				</div>

				<div class="modal-footer" id="modalStoryFooter">
					<button type="button" class="btn btn-primary" onclick="saveStory();"><i class="fa fa-save"></i></button>
					<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i></button>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Modal Step -->
	<div class="modal fade bs-example-modal-lg" id="modalStep" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="modalStepHeader" style="color:#1ab394">Step Management</h4>
				</div>
				
				<div class="modal-body" id="modalStepContent">
					<form id="stepForm" action="upload_story_img.php" method="post" enctype="multipart/form-data">
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="MDseq_texten">Sequence text EN *</label>
									<textarea id="MDseq_texten" class="form-control input-sm" required></textarea>
								</div>
								
								<div class="form-group">
									<label for="MDseq_textde">Sequence text DE</label>
									<textarea id="MDseq_textde" class="form-control input-sm"></textarea>
								</div>
								
								<div class="form-group">
									<label for="MDseq_textfr">Sequence text FR</label>
									<textarea id="MDseq_textfr" class="form-control input-sm"></textarea>
								</div>
							</div>
							
							<div class="col-md-4">
								<div class="form-group">
									<label for="MDseq_textpt">Sequence text PT</label>
									<textarea id="MDseq_textpt" class="form-control input-sm"></textarea>
								</div>
								
								<div class="form-group">
									<label for="MDseq_textes">Sequence text ES</label>
									<textarea id="MDseq_textes" class="form-control input-sm"></textarea>
								</div>
								
								<div class="form-group"> 
									<label for="MDseq_textit">Sequence text IT</label>
									<textarea id="MDseq_textit" class="form-control input-sm"></textarea>
								</div>
							</div>
							
							<div class="col-md-4">
								<div class="form-group">
									<label for="MDseq_number">Sequence number *</label>
									<input id="MDseq_number" class="form-control input-sm" type="number" min="0" />
								</div>
								
								<div class="form-group">
									<label for="MDseq_mediatype">Media Type *</label>
									<select id="MDseq_mediatype" class="form-control input-sm" required onchange="MDstepMedia(this.value);">
										<option value="">Select a media type</option>
										<option value="1">Video</option>
										<option value="2">Picture</option>
									</select>
								</div>
								
								<div class="form-group" id="MDseq_media_select">
									
								</div>
								
								<div class="form-group">
									<label for="MDseq_coordx">Sequence coord X</label>
									<input id="MDseq_coordx" class="form-control input-sm" type="number" />
								</div>
								
								<div class="form-group">
									<label for="MDseq_coordy">Sequence coord Y</label>
									<input id="MDseq_coordy" class="form-control input-sm" type="number" />
								</div>
							</div>
						</div>
					</form>
					
					<div class="row">
						<div class="col-md-12">
							<div id="storymap" style="height:500px; width:100%;"></div>
						</div>
					</div>
				</div>
				
				<input id="MDseq_id_story" class="form-control input-sm" type="hidden" />

				<div class="modal-footer" id="modalStepFooter">
					<button type="button" class="btn btn-primary" onclick="saveStep();"><i class="fa fa-save"></i></button>
					<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i></button>
				</div>
			</div>
		</div>
	</div>
	
	
	<!-- Modal Step Line -->
	<div class="modal fade bs-example-modal-lg" id="modalStepLine" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="modalStepLineHeader" style="color:#1ab394">Step Line</h4>
				</div>
				<div class="modal-body" id="stepmap" style="height:500px;width:100%;padding:0;"></div>
				<div class="modal-footer" id="modalStepLineFooter"></div>
				<input type="hidden" id="newStepsLine" />
			</div>
		</div>
	</div>
	
	
	<!-- Modal Story Media -->
	<div class="modal fade bs-example-modal-md" id="modalStoryImage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-body viewer" id="storyImgContent" style="padding:5px;width:100%;"></div>

				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i></button>
				</div>
			</div>
		</div>
	</div>
	
	
	<div class="modal fade bs-example-modal-sm" id="modalAvatar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					  <div class="modal-dialog modal-sm">
						<div class="modal-content">
						  <div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="modalAvatarTitle" style="color:#1ab394"><?php echo $_SESSION['name']; ?></h4>
						  </div>

						  <div class="modal-body" style="padding: 20px 10px 30px 20px;">
							 <div style="display:none" id="update-alert11" class="alert alert-danger alert-dismissable col-sm-12"></div>
							 <div style="display:none" id="update-alert12" class="alert alert-success alert-dismissable col-sm-12"></div>
							 <div id="modalAvatarContent">
								<div style="height:130px; width:130px; overflow:hidden;"><img src="<?php
									if(file_exists('img/avatar/' . $_SESSION['id_contact'] . ".jpg")) {
										echo 'img/avatar/' . $_SESSION['id_contact'] . ".jpg";
									} else { echo 'img/' . "user.jpg"; }
								?>" id="img1" width="128" /></div>
							
								<form id="avatar_upload" action="avatar_upload.php" method="post" enctype="multipart/form-data" style="margin-top:20px;">
									<div class="fileinput fileinput-new" data-provides="fileinput">
										<span class="btn btn-default btn-file" style="display: inline-block; padding: 6px 12px; margin-bottom: 0; font-size: 14px; font-weight: 400; line-height: 1.42857143; text-align: center; white-space: nowrap;">
											<span class="fileinput-new"><?php echo $lang['AVATAR_SELECT']; ?></span>
											<span class="fileinput-exists">Change</span>
											<input type="file" name="image" accept="image/*" id="inputImage" onchange="readURL1(this);">
										</span>  
										<button id="upload" onclick="upload_avatar();" disabled class="btn btn-primary"><i class="fa fa-upload"></i> <?php echo $lang['AVATAR_UPLOAD']; ?></button>
										<div class="col-md-12">
											<span class="fileinput-filename"></span>
											<a href="#" onclick="default_avatar();" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
										</div>
									</div>
								</form>
							 </div>
						  </div>
						</div>
					  </div>
	</div>

	
	<div class="modal fade bs-example-modal-md" id="modalProfile" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					  <div class="modal-dialog modal-md">
						<div class="modal-content">
						  <div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="modalProfileTitle" style="color:#1ab394"><?php echo $_SESSION['name']; ?></h4>
						  </div>

						  <div class="modal-body">
							 <div style="display:none" id="update-alert21" class="alert alert-danger alert-dismissable col-sm-12"></div>
							 <div style="display:none" id="update-alert22" class="alert alert-success alert-dismissable col-sm-12"></div>
							 <div id="modalProfileContent">
								<form role="form">
                                    <div class="form-group">
										<label>Name</label>
										<input type="text" placeholder="Name" id="prfname" value="<?php echo $_SESSION['name']; ?>" class="form-control">
									</div>

									<div class="form-group">
										<label>Email</label>
										<input type="email" placeholder="Enter email" id="prfusername" value="<?php echo $_SESSION['p_email']; ?>" class="form-control">
									</div>

                                    <div class="form-group">
										<label>Company name</label>
										<input type="text" placeholder="Company name" id="prfCompany" value="<?php echo $_SESSION['company_name']; ?>" class="form-control">
									</div>

									<div class="form-group">
										<label>Supply chain type</label>
										<select id="prfSupchain" class="form-control">

										</select>
									</div>

									<div class="form-group">
										<label>Country name</label>
										<input type="text" placeholder="Country name" id="prfCountry" value="<?php echo $_SESSION['name_country']; ?>" class="form-control">
									</div>

									<div class="form-group">
										<label>Town name</label>
										<input type="text" placeholder="Town name" id="prfTown" value="<?php echo $_SESSION['name_town']; ?>" class="form-control">
									</div>

                                    <div>
                                        <button class="btn btn-sm btn-primary m-t-n-xs" onclick="saveProfileData();" type="submit"><strong>Save changes</strong></button>
                                    </div>
                                </form>
							 </div>
						  </div>

							  <div id="update-footer" class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						  </div>
						</div>
					  </div>
					</div>
	</div>


	<div class="modal fade bs-example-modal-lg" id="modalAddContact" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" style="color:#1ab394">New Contact</h4>
				</div>
				
				<div class="modal-body" id="modalAddContactContent">
					<form id="addContactForm">
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="cT_lastname">Name *</label>
									<input type="text" id="cT_lastname" class="form-control" required />
								</div>
								
								<div class="form-group">
									<label for="cT_firstname">First Name *</label>
									<input type="text" id="cT_firstname" class="form-control" required />
								</div>
								
								<div class="form-group">
									<label for="cT_middlename">Middlename</label>
									<input type="text" id="cT_middlename" class="form-control" />
								</div>
								
								<div class="form-group">
									<label for="cT_p_phone">Mobile 1 *</label>
									<input type="text" id="cT_p_phone" class="form-control" required />
								</div>
								
								<div class="form-group">
									<label for="cT_p_phone2">Mobile 2</label>
									<input type="text" id="cT_p_phone2" class="form-control" />
								</div>
								
								<div class="form-group">
									<label for="cT_p_phone3">Mobile 3</label>
									<input type="text" id="cT_p_phone3" class="form-control" />
								</div>
								
								<div class="form-group">
									<label for="cT_p_phone4">Mobile Money</label>
									<input type="text" id="cT_p_phone4" class="form-control" />
								</div>
							</div>
							
							<div class="col-md-4">
								<div class="form-group">
									<label for="cT_p_phone5">Phone Fix</label>
									<input type="text" id="cT_p_phone5" class="form-control" />
								</div>
								
								<div class="form-group">
									<label for="cT_p_email">eMail Business *</label>
									<input type="email" id="cT_p_email" class="form-control" required />
								</div>
								
								<div class="form-group">
									<label for="cT_p_email2">eMail Private</label>
									<input type="email" id="cT_p_email2" class="form-control" />
								</div>
								
								<div class="form-group">
									<label for="cT_skype_id">Skype id</label>
									<input type="text" id="cT_skype_id" class="form-control" />
								</div>
								
								<div class="form-group">
									<label for="cT_p_street">p_street</label>
									<input type="text" id="cT_p_street" class="form-control" />
								</div>
								
								<div class="form-group">
									<label for="cT_town_name">Town Name</label>
									<select id="cT_town_name" class="form-control">
										<?php echo $region_list; ?>
									</select>
								</div>
								
								<!--<div class="form-group">
									<label for="cT_postalcode">Postal Code</label>
									<input type="text" id="cT_postalcode" class="form-control" />
								</div>
								
								<div class="form-group">
									<label for="name_country">Country</label>
									<input type="text" id="name_country" class="form-control" />
								</div>
								
								<div class="form-group">
									<label for="street">Street No</label>
									<input type="text" id="street" class="form-control" />
								</div>
								
								<div class="form-group">
									<label for="p_street1">p_street1</label>
									<input type="text" id="p_street1" class="form-control" />
								</div>-->
							</div>
							
							<div class="col-md-4">
								<!--<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="cT_coordx">CoordX</label>
											<input type="text" id="cT_coordx" class="form-control" />
										</div>
									</div>
									
									<div class="col-md-6">
										<div class="form-group">
											<label for="cT_coordy">CoordY</label>
											<input type="text" id="cT_coordy" class="form-control" />
										</div>
									</div>
								</div>
								
								<div class="form-group">
									<button type="button" class="btn btn-primary btn-block" onclick="loadContactMap(0,0,'','');" data-toggle="modal" data-target="#modalContactMap">
										<i class="fa fa-map-marker"></i> Map Marker Home
									</button>
								</div>-->
								
								<div class="form-group">
									<label for="cT_gender">Gender</label>
									<select class="form-control" id="cT_gender">
										<?php echo $gender_list; ?>
									</select>
								</div>
								
								<div class="form-group">
									<label for="cT_birthday">Birthday</label>
									<div class="input-group date">
										<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
										<input type="text" class="form-control edit_delivery_date" id="cT_birthday">
									</div>
								</div>
								
								<div class="form-group">
									<label for="cT_national_lang">National Language</label>
									<select class="form-control" id="cT_national_lang">
										<?php echo $language_list; ?>   
									</select>
								</div>    
								
								<div class="form-group">
									<label for="cT_national_lang">Agent</label> 
									<select class="form-control" id="cT_agent_type">
										<?php echo $agent_type_list; ?>   
									</select>
								</div>
								
								<div class="form-group">
									<label for="cT_selected_company">Primary Company</label>
									<input type="text" class="form-control" id="cT_selected_company" disabled />
								</div>
								
								<div class="form-group">
									<label for="cT_notes">Note</label>
									<textarea id="cT_notes" style="height:108px;" class="form-control"></textarea>
								</div>
								
								<!--<div class="form-group">
									<label for="p_email3">eMail System</label>
									<input type="text" id="p_email3" class="form-control" />
								</div>-->
							</div>
						</div>
						
						<input type="hidden" id="cT_contact_primary_company" class="form-control" />
						<input type="hidden" id="cT_id_supchain_type" class="form-control" />
					</form>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-primary" onclick="saveContact();"><i class="fa fa-save"></i></button>
					<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i></button>
				</div>
			</div>
		</div>
	</div>
	
	<div class="modal fade bs-example-modal-lg" id="modalAddCoop" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" style="color:#1ab394">New Cooperative</h4>
				</div>
				
				<div class="modal-body" id="modalAddContactContent">
					<form id="addCooperativeForm">
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="coop_contact_code" style="color:#aaa; font-size:12px; font-weight:normal;">Contact Code <b class="text-danger">*</b></label>
									<input type="text" id="coop_contact_code" class="form-control" required>
								</div>
								
								<div class="form-group">
									<label for="coop_firstname" style="color:#aaa; font-size:12px; font-weight:normal;"><?php echo $lang['CONT_NAME']; ?> <b class="text-danger">*</b></label>
									<input type="text" id="coop_firstname" class="form-control" required>
								</div>
								
								<div class="form-group">
									<label for="coop_lastname" style="color:#aaa; font-size:12px; font-weight:normal;"><?php echo $lang['CONT_NAME']; ?> 2</label>
									<input type="text" id="coop_lastname" class="form-control">
								</div>
								
								<div class="form-group">
									<label for="coop_national_lang" style="top:-14px; color:#aaa; font-size:12px; font-weight:normal;"><?php echo $lang['CONT_LANGUAGE']; ?> <b class="text-danger">*</b></label>
									<select class="form-control" id="coop_national_lang" required><?php echo $language_list; ?></select>
								</div>
								
								<div class="form-group">
									<label for="coop_p_phone" style="color:#aaa; font-size:12px; font-weight:normal;"><?php echo $lang['CONT_MOBILE']; ?> 1 <b class="text-danger">*</b></label>
									<input type="text" id="coop_p_phone" class="form-control" required>
								</div>
							</div>
							
							<div class="col-md-4">
								<div class="form-group">
									<label for="coop_p_phone2" style="color:#aaa; font-size:12px; font-weight:normal;"><?php echo $lang['CONT_MOBILE']; ?> 2</label>
									<input type="text" id="coop_p_phone2" class="form-control">
								</div>

								<div class="form-group">
									<label for="coop_p_phone3" style="color:#aaa; font-size:12px; font-weight:normal;"><?php echo $lang['CONT_MOBILE']; ?> 3</label>
									<input type="text" id="coop_p_phone3" class="form-control">
								</div>
								
								<div class="form-group">
									<label for="coop_p_phone4" style="color:#aaa; font-size:12px; font-weight:normal;"><?php echo $lang['CONT_MOBILE_MONEY']; ?></label>
									<input type="text" id="coop_p_phone4" class="form-control">
								</div>
								
								<div class="form-group">
									<label for="coop_p_phone5" style="color:#aaa; font-size:12px; font-weight:normal;"><?php echo $lang['CONT_PHONE_FIX']; ?></label>
									<input type="text" id="coop_p_phone5" class="form-control">
								</div>
								
								<div class="form-group">
									<label for="coop_bankname" style="color:#aaa; font-size:12px; font-weight:normal;"><?php echo $lang['CONT_BANKNAME']; ?></label>
									<input type="text" id="coop_bankname" class="form-control">
								</div>
							</div>
							
							<div class="col-md-4">
								<div class="form-group">
									<label for="coop_p_email" style="color:#aaa; font-size:12px; font-weight:normal;"><?php echo $lang['CONT_EMAIL_BUSINESS']; ?> <b class="text-danger">*</b></label>
									<input type="email" id="coop_p_email" class="form-control" required>
								</div>
								
								<div class="form-group">
									<label for="coop_postalcode" style="top:10px; color:#aaa; font-size:12px; font-weight:normal;"><?php echo $lang['CONT_POSTAL_CODE']; ?></label>
									<input type="text" id="coop_postalcode" class="form-control">
								</div>
								
								<div class="form-group">
									<label for="coop_p_street" style="color:#aaa; font-size:12px; font-weight:normal;"><?php echo $lang['CONT_ADRESS']; ?></label>
									<input type="text" id="coop_p_street" class="form-control">
								</div>
								
								<div class="form-group">
									<label for="coop_town_name" style="top:10px; color:#aaa; font-size:12px; font-weight:normal;"><?php echo $lang['CONT_TOWN']; ?> <b class="text-danger">*</b></label>
									<select class="form-control" id="coop_town_name" required><?php echo $region_list; ?></select>
								</div>
								
								<div class="form-group">
									<label for="coop_notes" style="top:10px; color:#aaa; font-size:12px; font-weight:normal;"><?php echo $lang['CONT_NOTES']; ?></label>
									<textarea id="coop_notes" class="form-control"></textarea>
								</div>
							</div>
							
						</div>
					</form>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-primary" onclick="saveCoop();"><i class="fa fa-save"></i></button>
					<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i></button>
				</div>
			</div>
		</div>
	</div>
	
	<!--
	<div class="modal fade bs-example-modal-md" id="modalContactMap" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-body viewer" style="padding:5px;width:100%;">
					<div id="contact_map8" style="width:100%;height:400px;"></div>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-primary pull-left" onclick="getMyLocation();"><i class="fa fa-street-view"></i></button>
					<div id="modalContactMapFooter">
						<button type="button" class="btn btn-danger" onclick="loadCtMap();" data-dismiss="modal"><i class="fa fa-times"></i></button>
					</div>
				</div>
			</div>
		</div>
	</div>
	-->
	
	<div class="modal fade bs-example-modal-lg" id="modalLoadingImages" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-body viewer" id="loadingImgContent" style="padding:5px;width:100%;height:500px;"></div>

				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i></button>
				</div>
			</div>
		</div>
	</div>
	
	
	<div class="modal fade bs-example-modal-lg" id="modalLoadingPoint" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" style="color:#1ab394">Loading Point</h4>
				</div>

				<div class="modal-body">
					<div id="ct_map" style="width:100%; height:500px;"></div>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-white" data-dismiss="modal"><i class="fa fa-ban"></i></button>
				</div>
			</div>
		</div>
	</div>
	
	
	
	<div class="modal fade bs-example-modal-sm" id="modalLabAnalysis" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" style="color:#1ab394">Edit Lab Analysis</h4>
				</div>

				<div class="modal-body">
					<div id="contenu_modal">
						<form id="labAnalysisForm">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label class="ord_sum_label"><?php echo $lang['LOG_LAB_PARAM']; ?></label><br/>
										<select id="editLab_id_prod_params" class="form-control">
										
										</select>
									</div>
									
									<div class="form-group">
										<label class="ord_sum_label"><?php echo $lang['LOG_LAB_DATE']; ?></label><br/>
										<div class="input-group date">
											<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
											<input type="text" class="form-control edit_delivery_date" id="editLab_date_analysis">
										</div>
									</div>
									
									<div class="form-group">
										<label class="ord_sum_label"><?php echo $lang['LOG_LAB_RESULT']; ?></label><br/>
										<input type="text" class="form-control" id="editLab_lab_result">
									</div>
								</div>
							</div>
							
							<input id="editLab_id_analysis_item" type="hidden" />
						</form>
					</div>
				</div>

				<div id="labAnalysisModalFooter" class="modal-footer">
					<button type="button" class="btn btn-success" onclick="saveEditedLabAna();"><i class="fa fa-save"></i></button>
					<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i></button>
				</div>
			</div>
		</div>
	</div>
	
	
	<div class="modal fade bs-example-modal-sm" id="modalCreateForwarder" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" style="color:#1ab394">Create forwarder</h4>
				</div>

				<div class="modal-body">
					<form id="forwarderForm">
						<div class="form-group">
							<label>Forwarder name </label>
							<input id="forw_lastname" class="form-control" class="form-control" />
						</div>
						
						<div class="form-group">
							<label>Designation </label>
							<input id="forw_fname" class="form-control" class="form-control" />
						</div>
						
						<div class="form-group">
							<label>Forwarder code </label>
							<input id="forw_code" class="form-control" maxlength="5" class="form-control" />
						</div>
						
						<div class="form-group">
							<label>Place </label>
							<input id="forw_townname" class="form-control" class="form-control" />
						</div>
					</form>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-primary" onclick="saveForwarder();"><i class="fa fa-save"></i></button>
					<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i></button>
				</div>
			</div>
		</div>
	</div>
	
	
	<div class="modal fade bs-example-modal-sm" id="modalCreateCarrier" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" style="color:#1ab394">Create carrier</h4>
				</div>

				<div class="modal-body">
					<form id="carrierForm">
						<div class="form-group">
							<label>Carrier name </label>
							<input id="carr_lastname" class="form-control" class="form-control" />
						</div>
						
						<div class="form-group">
							<label>Designation </label>
							<input id="carr_fname" class="form-control" class="form-control" />
						</div>
						
						<div class="form-group">
							<label>Carrier code </label>
							<input id="carr_code" class="form-control" maxlength="5" class="form-control" />
						</div>
						
						<div class="form-group">
							<label>Place </label>
							<input id="carr_townname" class="form-control" class="form-control" />
						</div>
					</form>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-primary" onclick="saveCarrier();"><i class="fa fa-save"></i></button>
					<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i></button>
				</div>
			</div>
		</div>
	</div>
	
	
	<div class="modal fade bs-example-modal-sm" id="modalCreateLoadingPlace" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" style="color:#1ab394">Create loading place</h4>
				</div>

				<div class="modal-body">
					<form id="carrierForm">
						<div class="form-group">
							<label>Company name </label>
							<input id="company_name" class="form-control" class="form-control" />
						</div>
						
						<div class="form-group">
							<label>Town </label>
							
						</div>
					</form>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-primary" onclick="saveLoadingPlace();"><i class="fa fa-save"></i></button>
					<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i></button>
				</div>
			</div>
		</div>
	</div>
	
	
	<div class="modal fade bs-example-modal-lg" id="modalCreateContract" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="create_contract_modal_ttle" style="color:#1ab394">Create contract</h4>
				</div>

				<div class="modal-body">
					<div id="create_contract_modal_ctn">
						
					</div>
				</div>

				<div class="modal-footer">
					<span id="pdf_contract"></span>
					<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Close</button>
				</div>
			</div>
		</div>
	</div>
	
	
	<div class="modal fade bs-example-modal-sm" id="modalTankProvider" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" style="color:#1ab394">Tank Provider</h4>
				</div>

				<div class="modal-body">
					<div class="form-group">
						<label>Tank Provider </label>
						<input id="tank_provider" class="form-control" class="form-control" />
					</div>
				</div>

				<div class="modal-footer">
					<span id="modalTankProviderFooter"></span>
					<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Cancel</button>
				</div>
			</div>
		</div>
	</div>
	
	
	<div class="modal fade bs-example-modal-sm" id="modalEditSumPipeline" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" style="color:#1ab394">Edit pipeline</h4>
				</div>

				<div class="modal-body">
					<div id="contenu_modal">
						<form id="sumPipelineForm">
							<div class="form-group">
								<label for="sumStatus_pipeline_id">Pipeline</label>
								<select class="form-control" id="sumStatus_pipeline_id">
									<?php echo $typ_sum_pipeline; ?>
								</select>
							</div>
							
							<input id="sumPipeline_id_ord_order" type="hidden" />
						</form>
					</div>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-primary" onclick="saveEditedSumPipeline();"><i class="fa fa-save"></i></button>
					<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i></button>
				</div>
			</div>
		</div>
	</div>
	
	
	<div class="modal fade bs-example-modal-sm" id="modalEditSumStatus" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" style="color:#1ab394">Edit status</h4>
				</div>

				<div class="modal-body">
					<div id="contenu_modal">
						<form id="sumStatusForm">
							<div class="form-group">
								<label for="sumStatus_status_id">Status</label>
								<select class="form-control" id="sumStatus_status_id">
									
								</select>
							</div>
							
							<input id="sumStatus_id_ord_order" type="hidden" />
						</form>
					</div>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-primary" onclick="saveEditedSumStatus();"><i class="fa fa-save"></i></button>
					<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i></button>
				</div>
			</div>
		</div>
	</div>
	
	<!-- For CRM2 -->
	
	<div class="modal fade bs-example-modal-sm" id="modalEditSumStatus2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" style="color:#1ab394">Edit status</h4>
				</div>

				<div class="modal-body">
					<div>
						<form id="sumStatusForm2">
							<div class="form-group">
								<label for="sumStatus_status_id2">Status</label>
								<select class="form-control" id="sumStatus_status_id2">
									<?php echo $typ_sum_status; ?>
								</select>
							</div>
							
							<input id="sumStatus_id_ord_order2" type="hidden" />
						</form>
					</div>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-primary" onclick="saveEditedSumStatus2();"><i class="fa fa-save"></i></button>
					<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i></button>
				</div>
			</div>
		</div>
	</div>
	
	<div class="modal fade bs-example-modal-sm" id="modalEditSumPipeline2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" style="color:#1ab394">Edit pipeline</h4>
				</div>

				<div class="modal-body">
					<div>
						<form id="sumPipelineForm2">
							<div class="form-group">
								<label for="sumStatus_pipeline_id2">Pipeline</label>
								<select class="form-control" id="sumStatus_pipeline_id2">
									<?php echo $typ_sum_pipeline; ?>
								</select>
							</div>
							
							<input id="sumPipeline_id_ord_order2" type="hidden" />
						</form>
					</div>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-primary" onclick="saveEditedSumPipeline2();"><i class="fa fa-save"></i></button>
					<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i></button>
				</div>
			</div>
		</div>
	</div>
	
	<!-- End CRM2 -->

	<div class="modal fade bs-example-modal-lg" id="modalTown" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="townModalLabel" style="color:#1ab394">Town</h4>
				</div>

				<div class="modal-body">
					<div id="contenu_modal">
						<form id="systTownForm">
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label for="syst_country_Id">Country *</label>
										<select class="form-control" id="syst_country_Id" onchange="selCountryName(this.value);">
											
										</select>
										
										<input type="hidden" value="" id="syst_country_name" />
									</div>
									
									<div class="form-group">
										<label for="syst_name_town">Town Name *</label> 
										<input type="text" class="form-control" id="syst_name_town">
									</div>
									
									<div class="form-group">
										<label for="syst_code_town">Code town</label>
										<input type="text" class="form-control" id="syst_code_town">
									</div>
									
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="syst_x">Town coord x</label>
												<input type="text" class="form-control" id="syst_x">
											</div>
										</div>
										
										<div class="col-md-6">
											<div class="form-group">
												<label for="syst_y">Town coord y</label>
												<input type="text" class="form-control" id="syst_y">
											</div>
										</div>
										
										<div class="col-md-12" style="color:#c5c5c5; padding-bottom:8px;">
											Click on the map to select the coordinates x,y of the capitale.
										</div>
									</div>
									
									<div class="form-group">
										<label for="syst_timezone">Time zone </label>
										<input type="number" min="0" class="form-control" id="syst_timezone">
									</div>
									
									<div class="form-group">
										<label for="syst_population">Number of population </label>
										<input type="number" min="0" class="form-control" id="syst_population">
									</div>
									
									<div class="form-group">
										<label for="syst_description_en">Description En</label>
										<textarea style="height:40px;" class="form-control" id="syst_description_en"></textarea>
									</div>
									
									<div class="form-group">
										<label for="syst_description_de">Description De</label>
										<textarea style="height:40px;" class="form-control" id="syst_description_de"></textarea>
									</div>
									
									<div class="form-group">
										<label for="syst_description_fr">Description Fr</label>
										<textarea style="height:40px;" class="form-control" id="syst_description_fr"></textarea>
									</div>
									
									<div class="form-group">
										<label for="syst_description_pt">Description Pt</label>
										<textarea style="height:40px;" class="form-control" id="syst_description_pt"></textarea>
									</div>
									
									<div class="form-group">
										<label for="syst_description_es">Description Es</label>
										<textarea style="height:40px;" class="form-control" id="syst_description_es"></textarea>
									</div>
								</div>
								
								<div class="col-md-8">
									<div id="townModalMap" style="height:70%; width:100%;"></div>
									
									<div class="row" style="margin-top:20px;">
										<div class="col-md-6">
											<div class="form-group">
												<label for="syst_region1">Region 1 </label>
												<input type="text" class="form-control" id="syst_region1">
											</div>
										</div>
										
										<div class="col-md-6">
											<div class="form-group">
												<label for="syst_region2">Region 2 </label>
												<input type="text" class="form-control" id="syst_region2">
											</div>
										</div>
										
										<div class="col-md-6">
											<div class="form-group">
												<label for="syst_region3">Region 3 </label>
												<input type="text" class="form-control" id="syst_region3">
											</div>
										</div>
										
										<div class="col-md-6">
											<div class="form-group">
												<label for="syst_region4">Region 4 </label>
												<input type="text" class="form-control" id="syst_region4">
											</div>
										</div>
									</div>
									
									<div class="row">
										<div class="col-md-4">
											<div class="form-group">
												<label for="syst_iso">ISO </label>
												<input type="text" class="form-control" id="syst_iso">
											</div>
										</div>
										
										<div class="col-md-4">
											<div class="form-group">
												<label for="syst_language">Language </label>
												<input type="text" class="form-control" id="syst_language">
											</div>
										</div>
										
										<div class="col-md-4">
											<div class="form-group">
												<label for="syst_postcode">Postcode </label>
												<input type="text" class="form-control" id="syst_postcode">
											</div>
										</div>
									</div>
									
									<div class="row">
										<div class="col-md-4">
											<div class="form-group">
												<label for="syst_suburb">Suburb </label>
												<input type="text" class="form-control" id="syst_suburb">
											</div>
										</div>
										
										<div class="col-md-4">
											<div class="form-group">
												<label for="syst_utc">UTC </label>
												<input type="text" class="form-control" id="syst_utc">
											</div>
										</div>
										
										<div class="col-md-4">
											<div class="form-group">
												<label for="syst_dst">DST </label>
												<input type="text" class="form-control" id="syst_dst">
											</div>
										</div>
									</div>
									
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label for="syst_gid_town">Town ID </label>
												<input type="text" class="form-control" id="syst_gid_town" disabled>
											</div>
										</div>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>

				<div id="townModalFooter" class="modal-footer">
					
				</div>
			</div>
		</div>
	</div>
	
	
	<div class="modal fade bs-example-modal-lg" id="modalCountry" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="countryModalLabel" style="color:#1ab394">Country</h4>
				</div>

				<div class="modal-body">
					<div id="contenu_modal">
						<form id="systCountryForm">
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label for="syst_name_country">Country Name *</label>
										<input type="text" class="form-control" id="syst_name_country">
									</div>
									
									<div class="form-group">
										<label for="syst_code">Country Code *</label>
										<input type="text" class="form-control" id="syst_code">
									</div>
									
									<div class="form-group">
										<label for="syst_capitale">Capitale *</label>
										<input type="text" class="form-control" id="syst_capitale">
									</div>
									
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="syst_capitale_x">Capitale coord x</label>
												<input type="text" class="form-control" id="syst_capitale_x">
											</div>
										</div>
										
										<div class="col-md-6">
											<div class="form-group">
												<label for="syst_capitale_y">Capitale coord y</label>
												<input type="text" class="form-control" id="syst_capitale_y">
											</div>
										</div>
										
										<div class="col-md-12" style="color:#c5c5c5; padding-bottom:8px;">
											Click on the map to select the coordinates x,y of the capitale.
										</div>
									</div>
									
									<div class="form-group">
										<label for="syst_number_population">Number of population </label>
										<input type="number" min="0" class="form-control" id="syst_number_population">
									</div>
									
									<div class="form-group">
										<label for="syst_area">Area</label>
										<input type="text" class="form-control" id="syst_area">
									</div>
									
									<div class="form-group">
										<label for="syst_culture">Culture</label>
										<select data-placeholder="Choose a culture..." name="syst_culture" id="syst_culture" class="chosen-select" multiple style="width:100%;" tabindex="4">
											<?php echo $culture_list; ?>
										</select>
									</div>
								</div>
								
								<div class="col-md-8">
									<div id="countryModalMap" style="height:70%; width:100%;"></div>
								</div>
							</div>
							
							<input id="syst_id_country" type="hidden" />
						</form>
					</div>
				</div>

				<div id="countryModalFooter" class="modal-footer">
					
				</div>
			</div>
		</div>
	</div>
	
	
	
	<div class="modal fade bs-example-modal-sm" id="modalRegvalue" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="regvalueModalLabel" style="color:#1ab394">Regvalue</h4>
				</div>

				<div class="modal-body">
					<div id="contenu_modal">
						<form id="systRegvalueForm">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label for="systReg_id_register">Register *</label>
										<select class="form-control" id="systReg_id_register">
											<?php echo $select_register; ?>
										</select>
									</div>
									
									<div class="form-group">
										<label for="systReg_cvalue">Value En *</label>
										<input type="text" class="form-control" id="systReg_cvalue">
									</div>
									
									<div class="form-group">
										<label for="systReg_cvaluede">Value De</label>
										<input type="text" class="form-control" id="systReg_cvaluede">
									</div>
									
									<div class="form-group">
										<label for="systReg_cvaluefr">Value Fr</label>
										<input type="text" class="form-control" id="systReg_cvaluefr">
									</div>
									
									<div class="form-group">
										<label for="systReg_cvaluept">Value Pt</label>
										<input type="text" class="form-control" id="systReg_cvaluept">
									</div>
									
									<div class="form-group">
										<label for="systReg_cvaluees">Value Es</label>
										<input type="text" class="form-control" id="systReg_cvaluees">
									</div>
									
									<div class="form-group">
										<label for="systReg_cvaluesw">Value Sw</label>
										<input type="text" class="form-control" id="systReg_cvaluesw">
									</div>
									
									<div class="form-group">
										<label for="systReg_cvalueit">Value It</label>
										<input type="text" class="form-control" id="systReg_cvalueit">
									</div>
									
									<div class="form-group">
										<label for="systReg_comment">Comment</label>
										<textarea style="height:80px;" class="form-control" id="systReg_comment"></textarea>
									</div>
								</div>
							</div>
							
							<input id="systReg_id_regvalue" type="hidden" />
						</form>
					</div>
				</div>

				<div id="regvalueModalFooter" class="modal-footer">
					
				</div>
			</div>
		</div>
	</div>
	
	
	<div class="modal fade bs-example-modal-sm" id="modalRegister" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="registerModalLabel" style="color:#1ab394">Register</h4>
				</div>

				<div class="modal-body">
					<div id="contenu_modal">
						<form id="systRegisterForm">
							<div class="row">
								<div class="col-md-8">
									<div class="form-group">
										<label for="syst_regcode">CODE *</label>
										<input type="text" class="form-control" id="syst_regcode">
									</div>
								</div>
								
								<div class="col-md-12">
									<div class="form-group">
										<label for="syst_regname">Name En *</label>
										<input type="text" class="form-control" id="syst_regname">
									</div>
									
									<div class="form-group">
										<label for="syst_regnamede">Name De</label>
										<input type="text" class="form-control" id="syst_regnamede">
									</div>
									
									<div class="form-group">
										<label for="syst_regnamefr">Name Fr</label>
										<input type="text" class="form-control" id="syst_regnamefr">
									</div>
									
									<div class="form-group">
										<label for="syst_regnamept">Name Pt</label>
										<input type="text" class="form-control" id="syst_regnamept">
									</div>
									
									<div class="form-group">
										<label for="syst_regnamees">Name Es</label>
										<input type="text" class="form-control" id="syst_regnamees">
									</div>
								</div>
							</div>
							
							<input id="syst_id_register" type="hidden" />
						</form>
					</div>
				</div>

				<div id="registerModalFooter" class="modal-footer">
					
				</div>
			</div>
		</div>
	</div>
	
	
	<div class="modal fade bs-example-modal-sm" id="modalCulture" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="cultureModalLabel" style="color:#1ab394">Culture</h4>
				</div>

				<div class="modal-body">
					<div id="contenu_modal">
						<form id="systCultureForm">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label for="edit_culture_name">Culture name</label>
										<input type="text" class="form-control" id="edit_culture_name" />
									</div>
								</div>
							</div>
							
							<input id="id_culture_systEdit" type="hidden" />
						</form>
					</div>
				</div>

				<div id="cultureModalFooter" class="modal-footer">
					
				</div>
			</div>
		</div>
	</div>
	
	
	<div class="modal fade bs-example-modal-md" id="modalProduct" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="productModalLabel" style="color:#1ab394">Product</h4>
				</div>

				<div class="modal-body">
					<div id="contenu_modal">
						<form id="systProductForm">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label for="product_name">Product name *</label>
										<input type="text" class="form-control" id="product_name" placeholder="Enter the name of the product">
									</div>
								</div>
								
								<div class="col-md-6">
									<div class="form-group">
										<label for="id_culture_pdct">Culture *</label>
										<select class="form-control" id="id_culture_pdct">
											<?php echo $culture_list; ?>
										</select>
									</div>
								</div>
								
								<div class="col-md-6">
									<div class="form-group">
										<label for="product_code">Product Code</label>
										<input type="text" class="form-control" id="product_code">
									</div>
								</div>
							</div>
						
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label for="product_desc">Product Description</label>
										<textarea class="form-control" style="height:80px;" id="product_desc"></textarea>
									</div>
								</div>
							</div>
							
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label for="measure_unit">Measure Unit *</label>
										<select class="form-control" id="measure_unit">
											<?php echo $measure_unit_list; ?>
										</select>
									</div>
								</div>
							
								<div class="col-md-4">
									<div class="form-group">
										<label for="product_type">Product Type *</label>
										<select class="form-control" id="product_type">
											<?php echo $group_product_list; ?>
										</select>
									</div>
								</div>
								
								<div class="col-md-4">
									<div class="form-group">
										<label for="product_hs">Product HS</label>
										<input type="number" min="0" class="form-control" id="product_hs">
									</div>
								</div>
							</div>
							
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label for="product_cas">Product Cas</label>
										<textarea class="form-control" style="height:80px;" id="product_cas"></textarea>
									</div>
								</div>
							</div>
							
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label for="product_name_de">Product name (DE)</label>
										<input type="text" class="form-control" id="product_name_de" placeholder="Enter the name of the product (DE)">
									</div>
								</div>
							</div>
							
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label for="q_ffa">FFA</label>
										<input type="text" class="form-control" id="q_ffa">
									</div>
								</div>
							
								<div class="col-md-4">
									<div class="form-group">
										<label for="q_mineraloil">Mineral OIL</label>
										<input type="text" class="form-control" id="q_mineraloil">
									</div>
								</div>
								
								<div class="col-md-4">
									<div class="form-group">
										<label for="q_humidity">Humidity</label>
										<input type="text" class="form-control" id="q_humidity">
									</div>
								</div>
							</div>
							
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="c18_1">C18 1</label>
										<input type="text" class="form-control" id="c18_1">
									</div>
								</div>
							
								<div class="col-md-6">
									<div class="form-group">
										<label for="c18_2">C18 2</label>
										<input type="text" class="form-control" id="c18_2">
									</div>
								</div>
							</div>
							
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label for="q_impurity">Impurity</label>
										<input type="text" class="form-control" id="q_impurity">
									</div>
								</div>
							
								<div class="col-md-4">
									<div class="form-group">
										<label for="q_dobi">DOBI</label>
										<input type="text" class="form-control" id="q_dobi">
									</div>
								</div>
								
								<div class="col-md-4">
									<div class="form-group">
										<label for="q_m_i">Moisture & Impurities</label>
										<input type="text" class="form-control" id="q_m_i">
									</div>
								</div>
							</div>
							
							<input id="id_product_pdct" type="hidden" />
						</form>
					</div>
				</div>

				<div id="productModalFooter" class="modal-footer">
					
				</div>
			</div>
		</div>
	</div>
	
	
	<div class="modal fade bs-example-modal-md" id="newSystShipmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="systemShip_Modaltitle" style="color:#1ab394"> ship</h4>
				</div>

				<div class="modal-body">
					<div id="ship_contenu_modal">
						<form id="sysShipForm">
							<div class="row">	
								<div class="col-md-12">	
									<div class="form-group">
										<label for="shipname_sysShip">Ship name *</label>
										<input type="text" class="form-control" id="shipname_sysShip" placeholder="Name of the ship">
									</div>
								</div>
						
								<div class="col-md-6">
									<div class="form-group">
										<label for="mmsi_sysShip">MMSI *</label>
										<input type="number" min="0" class="form-control" id="mmsi_sysShip" placeholder="MMSI Number">
									</div>
								</div>
								
								<div class="col-md-6">
									<div class="form-group">
										<label for="imo_sysShip">IMO</label>
										<input type="number" min="0" class="form-control" id="imo_sysShip" placeholder="IMO Number">
									</div>
								</div>
								
								<div class="col-md-12">	
									<div class="form-group">
										<label for="photo_sysShip">Photo link</label>
										<input type="text" class="form-control" id="photo_sysShip" placeholder="HTML Link from cloudinary">
									</div>
								</div>
							</div>
							
							<div class="row">
								<div class="col-md-12" id="photo_preview_sysShip"></div>
							</div>
							
							<input id="id_ship_sysShip" type="hidden" />
						</form>
					</div>
				</div>

				<div id="systemShip_Modalfooter" class="modal-footer">
					
				</div>
			</div>
		</div>
	</div>
	
	
	<div class="modal fade bs-example-modal-lg" id="newSystShipPhotomodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-body text-center" id="sysShip_photo" style="padding:5px;"></div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i></button>
				</div>
			</div>
		 </div>
	</div>
	
	
	<div class="modal fade bs-example-modal-md" id="newSystFreightmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="systemFreight_Modaltitle" style="color:#1ab394"> freight</h4>
				</div>

				<div class="modal-body">
					<div id="contenu_modal">
						<form id="sysFreightForm">
							<div class="row">	
								<div class="col-md-6">
									<div class="form-group">
										<label for="pol_townport_id_sysFrei">Port of loading *</label>
										<select id="pol_townport_id_sysFrei" class="form-control">
											<?php echo $townports_pol_list; ?>
										</select>
									</div>
								</div>
								
								<div class="col-md-6">
									<div class="form-group">
										<label for="pod_townport_id_sysFrei">Port of discharge *</label>
										<select id="pod_townport_id_sysFrei" class="form-control">
											<?php echo $townports_pod_list; ?>
										</select>
									</div>
								</div>
								
								<div class="col-md-6">
									<div class="form-group">
										<label for="incoterm_id_sysFrei">Incoterms *</label>
										<select id="incoterm_id_sysFrei" class="form-control">
											<?php echo $incoterms_list; ?>
										</select>
									</div>
								</div>
								
								<div class="col-md-6">
									<div class="form-group">
										<label for="transport_type_id_sysFrei">Transport type *</label>
										<select id="transport_type_id_sysFrei" class="form-control">
											<?php echo $transport_type_list; ?>
										</select>
									</div>
								</div>
							</div>
							
							
							<div class="row" style="margin-top:40px;">
								<div class="col-md-12">
									<div class="form-group">
										<label for="shipping_company_id_sysFrei">Carrier</label>
										<select id="shipping_company_id_sysFrei" class="form-control">
											<?php echo $carrier_list; ?>
										</select>
									</div>
								</div>
								
								<div class="col-md-12">
									<div class="form-group">
										<label for="shipping_company_sysFrei">Info *</label>
										<input type="text" class="form-control" id="shipping_company_sysFrei" placeholder="Info">
									</div>
								</div>
								
								<div class="col-md-6">
									<div class="form-group">
										<label for="rate_valid_until_sysFrei">Rate valid until</label>
										<div class="input-group date">
											<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
											<input type="text" class="form-control edit_delivery_date" id="rate_valid_until_sysFrei">
										</div>
									</div>
								</div>
								
								<div class="col-md-6">
									<div class="form-group">
										<label for="packaging_type_id_sysFrei">Packaging type *</label>
										<select id="packaging_type_id_sysFrei" class="form-control">
											<?php echo $typ_package_list; ?>
										</select>
									</div>
								</div>
								
								
								<div class="col-md-6">
									<div class="form-group">
										<label for="returns_empty_id_sysFrei">Returns empty</label>
										<select id="returns_empty_id_sysFrei" class="form-control">
											<option value="">---</option>
											<option value="18">Rotterdam</option>
											<option value="19">Basel-Birsfelden</option>
										</select>
									</div>
								</div>
								
								<div class="col-md-6">
									<div class="form-group">
										<label for="weight_packaging_type_sysFrei">Weight packaging type</label>
										<input type="text" class="form-control" id="weight_packaging_type_sysFrei">
									</div>
								</div>
							</div>

							<div class="row" style="margin-top:40px;">
								<div class="col-md-4">
									<div class="form-group">
										<label for="freight_eur_sysFrei">Freight Eur</label>
										<input type="text" class="form-control" id="freight_eur_sysFrei">
									</div>
								</div>
								
								<div class="col-md-4">
									<div class="form-group">
										<label for="freight_usd_sysFrei">Freight USD</label>
										<input type="text" class="form-control" id="freight_usd_sysFrei">
									</div>
								</div>
								
								<div class="col-md-4">
									<div class="form-group">
										<label for="freight_chf_sysFrei">Freight CHF</label>
										<input type="text" class="form-control" id="freight_chf_sysFrei">
									</div>
								</div>
							</div>
							
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label for="add_eur_sysFrei">Add Eur</label>
										<input type="text" class="form-control" id="add_eur_sysFrei">
									</div>
								</div>
							
								<div class="col-md-4">
									<div class="form-group">
										<label for="add_usd_sysFrei">Add USD</label>
										<input type="text" class="form-control" id="add_usd_sysFrei">
									</div>
								</div>
								
								<div class="col-md-4">
									<div class="form-group">
										<label for="add_chf_sysFrei">Add CHF</label>
										<input type="text" class="form-control" id="add_chf_sysFrei">
									</div>
								</div>
							</div>
							
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label for="total_eur_sysFrei">Total Eur</label>
										<input type="text" class="form-control" id="total_eur_sysFrei">
									</div>
								</div>
							
								<div class="col-md-4">
									<div class="form-group">
										<label for="total_usd_sysFrei">Total USD</label>
										<input type="text" class="form-control" id="total_usd_sysFrei">
									</div>
								</div>
								
								<div class="col-md-4">
									<div class="form-group">
										<label for="total_chf_sysFrei">Total CHF</label>
										<input type="text" class="form-control" id="total_chf_sysFrei">
									</div>
								</div>
							</div>
					
							<div class="row" style="margin-top:40px;">	
								<div class="col-md-6">
									<div class="form-group">
										<label for="dem_pol_free_sysFrei">DEM POL Free</label>
										<input type="text" class="form-control" id="dem_pol_free_sysFrei">
									</div>
								</div>
								
								<div class="col-md-6">
									<div class="form-group">
										<label for="dem_pol_cost_after_sysFrei">DEM POL Cost After</label>
										<input type="text" class="form-control" id="dem_pol_cost_after_sysFrei">
									</div>
								</div>
							</div>
							
							<div class="row">	
								<div class="col-md-6">
									<div class="form-group">
										<label for="dem_pol_free2_sysFrei">DEM POL Free 2</label>
										<input type="text" class="form-control" id="dem_pol_free2_sysFrei">
									</div>
								</div>
								
								<div class="col-md-6">
									<div class="form-group">
										<label for="dem_pol_cost_after2_sysFrei">DEM POL Cost After 2</label>
										<input type="text" class="form-control" id="dem_pol_cost_after2_sysFrei">
									</div>
								</div>
							</div>
							
							<div class="row" style="margin-top:40px;">	
								<div class="col-md-6">
									<div class="form-group">
										<label for="dem_pod_free_sysFrei">DEM POD Free</label>
										<input type="text" class="form-control" id="dem_pod_free_sysFrei">
									</div>
								</div>
								
								<div class="col-md-6">
									<div class="form-group">
										<label for="dem_pod_cost_after_sysFrei">DEM POD Cost After</label>
										<input type="text" class="form-control" id="dem_pod_cost_after_sysFrei">
									</div>
								</div>
							</div>
							
							<div class="row">	
								<div class="col-md-6">
									<div class="form-group">
										<label for="dem_pod_free2_sysFrei">DEM POD Free 2</label>
										<input type="text" class="form-control" id="dem_pod_free2_sysFrei">
									</div>
								</div>
								
								<div class="col-md-6">
									<div class="form-group">
										<label for="dem_pod_cost_after2_sysFrei">DEM POD Cost After 2</label>
										<input type="text" class="form-control" id="dem_pod_cost_after2_sysFrei">
									</div>
								</div>
							</div>
						
							<div class="row" style="margin-top:40px;">	
								<div class="col-md-4">
									<div class="form-group">
										<label for="transit_time_sysFrei">Transit time</label>
										<input type="text" class="form-control" id="transit_time_sysFrei">
									</div>
								</div>
							
								<div class="col-md-4">
									<div class="form-group">
										<label for="trans_type_id_sysFrei">Transit type</label>
										<select id="trans_type_id_sysFrei" onchange="sysFreiTransShipToggle(this.value);" class="form-control">
											<option value="">---</option>
											<option value="0">Direct</option>
											<option value="1">Transshipment</option>
										</select>
									</div>
								</div>
								
								<div class="col-md-4">
									<div class="form-group">
										<label for="trans_location_id_sysFrei">Transit port</label>
										<select id="trans_location_id_sysFrei" class="form-control">
											<?php echo $townports_trans_list; ?>
										</select>
									</div>
								</div>
							</div>
							
							<input id="id_con_box_fr_sysFrei" type="hidden" />
						</form>
					</div>
				</div>

				<div id="systemFreight_Modalfooter" class="modal-footer">
					
				</div>
			</div>
		 </div>
	</div>
	
	
	<div class="modal fade bs-example-modal-lg" id="proposalToCusMailModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-body" id="show_proposal_to_cus_content"></div>
				<div id="proposal_to_cus_mail_footer" class="modal-footer"></div>
			</div>
		 </div>
	</div>
	
	
	<div class="modal fade bs-example-modal-lg" id="proposalMailModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-body" id="show_proposal_content"></div>
				<div id="proposal_mail_footer" class="modal-footer"></div>
			</div>
		 </div>
	</div>
	
	
	<div class="modal fade bs-example-modal-lg" id="bookingDocModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-body" id="booking_document_show" style="padding:5px;"></div>
				<div id="booking_document_footer" class="modal-footer"></div>
			</div>
		</div>
	</div>
	
	<div class="modal fade bs-example-modal-sm" id="verCertDocModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-body" id="verCertDocModal_show"></div>
				<div id="verCertDocModal_footer" class="modal-footer"></div>
			</div>
		</div>
	</div>
	
	
	<!-- AFS Contract Date picker Modal -->
	<div class="modal fade bs-example-modal-sm" id="afsContractDateModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-body">
					<div class="form-group">
						<label> Date </label>
						<div class="input-group date">
							<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							<input type="text" class="form-control edit_delivery_date" id="afsContract_date">
						</div>	
					</div>
				</div>
	
				<div id="afsContractDateFooter" class="modal-footer"> 
					
				</div>
			</div>
		</div>
	</div>
	
	
	<!-- Invoice 2 Date picker Modal -->
	<div class="modal fade bs-example-modal-sm" id="invoice2DateModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-body">
					<div class="form-group">
						<label>Invoice Number </label>
						<input type="text" id="invoice_2_number" class="form-control" />
					</div>
					
					<div class="form-group">
						<label>Invoice Date </label>
						<div class="input-group date">
							<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							<input type="text" class="form-control edit_delivery_date" id="invoice_2_date">
						</div>	
					</div>
				</div>
	
				<div id="invoice2DateFooter" class="modal-footer"> 
					
				</div>
			</div>
		</div>
	</div>
	
	
	<!-- Invoice 1 Date picker Modal -->
	<div class="modal fade bs-example-modal-sm" id="invoice1DateModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-body">
					<div class="form-group">
						<label>Invoice Number </label>
						<input type="text" id="invoice_1_number" class="form-control" /> 
					</div>
					
					<div class="form-group">
						<label>Invoice Date </label>
						<div class="input-group date">
							<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							<input type="text" class="form-control edit_delivery_date" id="invoice_1_date">
						</div>	
					</div>
				</div>
	
				<div id="invoice1DateFooter" class="modal-footer"> 
					
				</div>
			</div>
		</div>
	</div>
	
	
	<!-- Invoice Custom Modal -->
	<div class="modal fade bs-example-modal-sm" id="invoiceCostumsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-body">
					<div class="form-group">
						<label>Invoice Number </label>
						<input type="text" id="invoice_cus_numb" class="form-control" />
					</div>
					
					<div class="form-group">
						<label>Invoice Date </label>
						<div class="input-group date">
							<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							<input type="text" class="form-control edit_delivery_date" id="invoice_cus_date">
						</div>
					</div>
				</div>
	
				<div id="" class="modal-footer"> 
					<input type="hidden" id="invoice_cus_ord_schedule_id" />
					<button type="button" class="btn btn-primary pull-left" onclick="invoice_customs_pdf();" data-dismiss="modal"> Use</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Close</button>
				</div>
			</div>
		</div>
	</div>
	
	
	<div class="modal fade bs-example-modal-sm" id="invoice2OptionModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-body">
					<div class="form-group">
						<label>Zahlungskonditionen </label>
						<textarea id="zahlungskonditionen" style="height:80px;" class="form-control"></textarea>
					</div>
				</div>
	
				<div id="invoice2OptionModal_footer" class="modal-footer"> 
					
				</div>
			</div>
		</div>
	</div>
	
	
	<div class="modal fade bs-example-modal-md" id="profilModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="" style="color:#1ab394"><?php echo $lang['MENU_PROFILE']; ?></h4>
				</div>

				<div class="modal-body">
				
				</div>
	
				<div id="" class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> <?php echo $lang['RESET_PASS_BTN_CLOSE']; ?></button>
				</div>
			</div>
		</div>
	</div>
	
	
	<div class="modal fade bs-example-modal-sm" id="passwordRestModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="" style="color:#1ab394"><?php echo $lang['MENU_RESET_PASS']; ?></h4>
				</div>

				<div class="modal-body">
					<div class="form-group">
						<label><?php echo $lang['RESET_PASS_PASSWORD']; ?></label>
						<input type="password" id="password1" class="form-control" />
					</div>
					
					<div class="form-group">
						<label><?php echo $lang['RESET_PASS_VERIF']; ?></label>
						<input type="password" id="password2" class="form-control" />
					</div>
					
					<div id="pass-info"></div>
				</div>
	
				<div id="" class="modal-footer">
					<button type="button" class="btn btn-primary pull-left" onclick="resetPassword(0);" data-dismiss="modal"> <?php echo $lang['RESET_PASS_BTN_RESET']; ?></button>
					<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> <?php echo $lang['RESET_PASS_BTN_CLOSE']; ?></button>
				</div>
			</div>
		</div>
	</div>
	
	
	<div class="modal fade bs-example-modal-sm" id="OnwardcontainerModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="OnwardcontainerHeader" style="color:#1ab394"></h4>
				</div>

				<div class="modal-body" id="OnwardcontainerContent"></div>
				<div class="modal-footer" id="OnwardcontainerFooter"></div>
			</div>
		</div>
	</div>

	
	<div class="modal fade bs-example-modal-sm" id="containerModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="" style="color:#1ab394">Edit container</h4>
				</div>

				<div class="modal-body">
					<div class="form-group">
						<label>Container Number *</label>
						<input type="text" id="container_number" class="form-control" autofocus />
					</div>
			
					<div class="form-group">
						<label>Tare </label>
						<input type="number" min="0" id="container_tare" class="form-control" />
					</div>
			
					<div class="form-group">
						<label>Weight </label>
						<input type="number" min="0" id="container_vgm_weight" class="form-control" />
					</div>
			
					<div class="form-group">
						<label>Seal 1 </label>
						<input type="text" id="container_seal_1_nr" class="form-control" />
					</div>
			
					<div class="form-group">
						<label>Seal 2 </label>
						<input type="text" id="container_seal_2_nr" class="form-control" />
					</div>
			
					<div class="form-group">
						<label>Seal 3 </label>
						<input type="text" id="container_seal_3_nr" class="form-control" />
					</div>
			
					<div class="form-group">
						<label>Seal 4 </label>
						<input type="text" id="container_seal_4_nr" class="form-control" />
					</div>
			
					<div class="form-group">
						<label>Seal 5 </label>
						<input type="text" id="container_seal_5_nr" class="form-control" />
					</div>
				
					<div class="form-group">
						<label>Date loaded </label>
						<div class="input-group pull-left date">
							<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							<input type="text" class="form-control edit_delivery_date" id="container_date_loaded" />
						</div>
					</div>
				</div>
				
				<input type="hidden" id="id_con_list" />
				<input type="hidden" id="ord_schedule_idCont" />
	
				<div id="" class="modal-footer">
					<button type="button" class="btn btn-primary pull-left" onclick="edit_booking_containers();" data-dismiss="modal"><i class="fa fa-save"></i> Save</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Close</button>
				</div>
			</div>
		</div>
	</div>
	

	<div class="modal fade bs-example-modal-lg" id="salesModalInvoice" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="" style="color:#1ab394">Invoice</h4>
				</div>

				<div class="modal-body no-padding">
					<div class="ibox-content p-xl">
                        <div class="row">
                            <div class="col-sm-6">
								<h4>PFR0717_001 v5.84</h4>
                                <h5>Date: <span id="iv_date">12/04/2017</span></h5>
                            </div>

                            <div class="col-sm-6">
                                <h4 id="iv_sl_line_name">CMA CGM COTE D'IVOIRE</h4>
                                <address id="iv_sl_line_adress">
                                    VRIDI ZONE PORTUAIRE<br>
                                    BOULEVARD DU PORT<br>
                                    01 BP 37491<br>
									ABIDJAN
                                </address>
                                <p>
                                    <abbr title="Telephone" id="iv_sl_contact_tel">Tel:</abbr> <br>
                                    <abbr title="Fax" id="iv_sl_contact_fax">Fax:</abbr> <br>
                                    <abbr title="PAC" id="iv_sl_contact_name">PAC:</abbr> Ahi Rosalie<br>
                                    <span>Service client:</span>
								</p>
                            </div>
						</div>
						
						<div class="row">
							<div class="col-sm-6 col-sm-offset-2">
								<h4>Attn</h4>
								<span>OMA CI</span><br>
								<span id="iv_log_contact_name">KOFFI SONIA</span><br>
                                <address>
                                    105 BLVD DE MARSEILLE<br>
									11 BP 1460<br>
									ABIDJAN
                                </address>
                            </div>
                        </div>
	
						<div class="row" style="margin-top:40px;">
							<div class="col-sm-4">
								Numéro de booking: <span id="iv_booking_nr">AEV0134260</span><br>
								Transitaire: <span>OMA CI</span>
								Chargeur: <span>HUILERIE MODERNE D</span>
								Navire/Voyage: <span id="iv_vessel_voyage">IRENES RESOLVE / 19479N</span>
								navire transbordeur/voyage: <span></span>
							</div>
							
							<div class="col-sm-4">
								ref. client: <span>0003756149 001</span><br>
							</div>
							
							<div class="col-sm-4">
								date Réser: <span>23-MAR-17</span><br>
							</div>
						</div>
						
						<div class="row" style="margin-top:40px;">
							<div class="col-sm-6">
								Origine: <span></span><br>
								Alternate Base Port: <span></span><br>
								Alternate Base Pool: <span></span><br>
								Navire Feeder: <span></span><br>
								Port d'embarquement: <span id="iv_pol_name">ABIDJAN</span><br>
								Terminal d'embarquement: <span>ABIDJAN TERMINAL</span><br>
								Transbordement: <span></span><br>
								Port de débarquement: <span id="iv_pod_name">ANTWERP</span><br>
								Destination Finale: <span></span><br>
								Remarques: <span></span><br>
							</div>
							
							<div class="col-sm-6">
								date de cloture: <span></span><br>
								date de cloture: <span></span><br>
								ETD: <span></span><br>
								date de cloture: <span>01-APR-17 10:00 PM</span><br>
								VGM Cut-Off Date/Time: <span id="iv_vgm_cutoff">02-APR-17 07:00 AM</span><br>
								ETD: <span id="iv_etd">02-APR-17 07:00</span><br>
								ETA: <span></span><br>
								ETA: <span id="iv_eta">19-APR-17 14:00</span><br>
							</div>
						</div>
						
						<hr/>
						
						<div class="row">
							<div class="col-sm-12">
								<h4>Transport Client</h4>
							</div>
							
							<div class="col-sm-4">
								Nombre Tcs: <span id="iv_nb_containers"></span><br>
								Poids Net: <span></span><br>
								Cotation: <span id="iv_nb_confirmation"></span><br>
								Service Contract: <span></span><br>
								DGX: <span></span><br>
								FUM: <span></span><br>
								REEFER: <span></span><br>
								Gabarit: <span></span><br>
								Flexitank: <span></span><br>
							</div>
							
							<div class="col-sm-4">
								Container Number: <span></span><br>
							</div>
							
							<div class="col-sm-4">
								Date: <span id="iv_hoyer_date"></span><br>
							</div>
						</div>
						
						<div class="row text-center" style="margin-top:30px;">
							Booking Contact: <span id="iv_booking_contact"></span>
							EMAIL:<span id="iv_booking_contact_email"></span>
						</div>
                    </div>
				</div>
				
				<div id="" class="modal-footer">
					<a href="invoice_print.html" target="_blank" class="btn btn-primary"><i class="fa fa-print"></i> Print </a>
					<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Close</button>
				</div>
			</div>
		 </div>
	</div>
	
	
	<!--<div class="modal fade bs-example-modal-sm" id="todaycurrency" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog" style="width:302px;">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" style="color:#1ab394">To day currency</h4>
				</div>

				<div class="modal-body no-padding">
					<div id="oanda_ecc">
						<span style="color:#000; text-decoration:none; font-size:9px; float:left;">Currency Converter <a id="oanda_cc_link" style="color:#000; font-size:9px;" href="https://www.oanda.com/currency/converter/">by OANDA</a></span>
						<script src="https://www.oanda.com/embedded/converter/get/b2FuZGFlY2N1c2VyLy9kZWZhdWx0/?lang=en"></script>
					</div>
				</div>
				
				<div id="" class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Close</button>
				</div>
			</div>
		 </div>
	</div>-->
	
	
	<div class="modal fade bs-example-modal-lg" id="freightListModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="freightModalLabel" style="color:#1ab394"> </h4>
				</div>

				<div class="modal-body">
					<div class="table-responsive">
						<table class="table table-striped table-hover" style="font-size:13px;">
							<thead>
								<th>#</th>
								<th>POL</th>
								<th>Incoterm</th>
								<th>POD</th>
								<th>Shipping</th>
								<th>Package Type</th>
								<th>US$/EUR/CHF</th>
							</thead>
							
							<tbody id="freightListTable">
							
							</tbody>
						</table>
					</div>
				</div>
				
				<input id="freight_id_ord_schedule" type="hidden">
				<input id="freight_sequence_nr" type="hidden">
				
				<input id="freight_pol_id" type="hidden">
				<input id="freight_pod_id" type="hidden">
				<input id="freight_package_type_id" type="hidden">
				
				<div id="freight_modal_footer" class="modal-footer">
					
				</div>
			</div>
		 </div>
	</div>
	
	
	<div class="modal fade bs-example-modal-lg" id="wizardModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-body">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					
					<h2>Request for quote</h2>
					
					<form action="#" id="myForm" role="form" data-toggle="validator" method="#" accept-charset="utf-8">
					
						<input type="hidden" id="last_inserted_order_id" />
						
						<div id="smartwizard">
							<ul class="step">
								<li><a href="#Order">1.Order</a></li>
								<li><a href="#Product">2.Product</a></li>
								<li><a href="#Schedule">3.Schedule</a></li>
								<li><a href="#Note">4.Note</a></li>
							</ul>
							
							<div>
								<div id="Order">
									<h2>Order information</h2>
									<div id="form-step-0" role="form" data-toggle="validator">
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">  
													<label for="customer_reference_nr">Reference number </label>
													<input class="form-control" type="text" name="customer_reference_nr" id="customer_reference_nr"/>
												</div>
												
												<input type="hidden" id="ord_cus_person_id" value="<?php echo $_SESSION['id_contact']; ?>" />
												<input type="hidden" id="id_supchain_type" value="<?php echo $_SESSION['id_supchain_type']; ?>" />
												<?php if($_SESSION['id_supchain_type'] == 110){ ?>
													<input type="hidden" name="ord_cus_contact_id" id="ord_cus_contact_id" value="<?php echo $_SESSION['id_company']; ?>" />
													<?php if($_SESSION['id_company'] == 689){ ?>
														<input type="hidden" name="ord_imp_contact_id" id="ord_imp_contact_id" value="717" />
														<span style="height:63px;"><b>CLIENT : </b><?php echo $_SESSION['company_name']; ?></span><br/>
														<br/>
													<?php } else { ?>
														<input type="hidden" name="ord_imp_contact_id" id="ord_imp_contact_id2" value="641" />
														<span style="height:63px;"><b>CLIENT : </b><?php echo $_SESSION['company_name']; ?></span><br/>
														<br/>
													<?php } ?>
												<?php } else { ?>
													<div class="form-group">
														<label for="ord_cus_contact_id2">Client *</label>
														<select class="form-control" onchange="selectClient();" name="ord_cus_contact_id" id="ord_cus_contact_id2" required>
																
														</select>
														<div class="help-block with-errors"></div>
													</div>
														
													<div class="form-group hide" id="order_client">
														<label for="ord_cus_person_id2">Order client *</label>
														<select class="form-control" name="ord_cus_person_id" id="ord_cus_person_id2">
															
														</select>
													</div>
													<input type="hidden" name="ord_imp_contact_id" id="ord_imp_contact_id3" value="<?php echo $_SESSION['id_user']; ?>" />
												<?php } ?>
											
												<div class="form-group">
													<label for="order_incoterms_id">Incoterms *</label>
													<select class="form-control" name="order_incoterms_id" onchange="protverif();" id="order_incoterms_id" required>
														<?php echo $incoterms_list; ?>
													</select>
													<div class="help-block with-errors"></div>
												</div>
													
												<div class="form-group" id="selport">
													<label for="port_id_code">Port *</label>
													<select class="form-control" name="port_id_code" id="port_id_code">
														<?php echo $town_ports_list; ?>
													</select>
												</div>
											</div>
								
											<div class="col-md-6">
												<div class="form-group">
													<label for="package_type">Package type *</label>
													<select class="form-control" name="package_type" id="package_type" required>
														<?php echo $typ_package_list; ?>
													</select>
													<div class="help-block with-errors"></div>
												</div>
												
												<div class="form-group">
													<label for="product_type_w">Product group *</label>
													<select class="form-control" name="product_type_w" id="product_type_w" required>
														<?php echo $group_product_list; ?>
													</select>
													<div class="help-block with-errors"></div>
												</div>
												
												<div class="form-group">
													<label for="nr_shipments">Number shipments *</label>
													<select class="form-control" name="nr_shipments" id="nr_shipments" required>
														<option value="">-- Select shipment --</option>
														<?php
															for ($x = 1; $x <= 12; $x++) {
																if($x==1){ $sel='selected'; }else{ $sel=''; }
																echo '<option value="'. $x .'"'.$sel.'>'. $x .'</option>';
															}
														?>
													</select>
													<div class="help-block with-errors"></div>
												</div>
												
												<div class="form-group" id="data_4">
													<label class="font-normal" for="delivery_date">Date of first delivery ETA *</label>
													<div class="input-group date">
														<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
														<input type="text" class="form-control" name="delivery_date" id="delivery_date" onchange="getWeek();">
													</div>
													<div class="help-block with-errors"></div>
													<input type="hidden" id="delivery_week" />
												</div>
												
												<div class="form-group hide" id="data_5">
													<label class="font-normal" for="delivery_date2">Date of first shipment ETD</label>
													<div class="input-group date">
														<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
														<input type="text" class="form-control" name="delivery_date2" id="delivery_date2" onchange="getWeek2();">
													</div>
													<div class="help-block with-errors"></div>
													<input type="hidden" id="delivery_week2" />
												</div>
											</div>
										</div>
									</div>
								</div>
								
								<div id="Product">
									<h2>Product information</h2>
									<div id="form-step-1" role="form" data-toggle="validator">
										<div class="row">
											<input type="hidden" id="order_infos" name="order_infos" />
											<div class="col-md-8">
												<div class="form-group no-margins">
													<label for="id_product">Product *</label>
													<select class="form-control" onchange="getProductDetails();" name="id_product" id="id_product" required>
														
													</select>
													<div class="help-block with-errors"></div>
													<span id="pdt_cas"></span><br/>
													<span id="pdt_hs"></span>
												</div>
												
												<div class="form-group">
													<label for="measure_unit">Measure unit *</label>
													<select class="form-control" name="measure_unit" id="measure_unit" required>
														<?php echo $measure_unit_list; ?>
													</select>
													<div class="help-block with-errors"></div>
												</div>

												<div class="form-group">
													<label for="product_quantity">Number of containers *</label>
													<input id="product_quantity" name="product_quantity" onchange="ttWeightCal();" type="number" min="0" class="form-control" required>
													<div class="help-block with-errors"></div>
												</div>

												<div class="form-group">
													<label for="weight_unit">Weight per container *</label>
													<input id="weight_unit" name="weight_unit" type="number" step=".01" pattern="[0-9]+([,\.][0-9]+)?" onchange="ttWeightCal();" value="21.50" placeholder="21.50" class="form-control" required>
													<div class="help-block with-errors"></div>
												</div>
											</div>

											<div class="col-md-4">
												<div class="text-center">
													<div style="margin-top: 20px">
														<i class="fa fa-archive" style="font-size: 180px;color: #e5e5e5 "></i>
													</div><br/>
													<span><b>TOTAL WEIGHT : <br/><h4 id="Ttweight">0</h4></b></span>
													<input type="hidden" id="weight_total" name="weight_total" value="" />
												</div>
											</div>
										</div>
									</div>
								</div>
								<div id="Schedule">
									<h2>Schedule</h2>
									<div id="form-step-2" role="form" data-toggle="validator">
										<div class="row">
											<div class="col-md-12">
												<div class="table-responsive">
													<table class="table table-striped table-bordered table-hover dataTables-example" style="font-size:13px;">
														<thead>
															<tr>
																<th>Shipment</th>
																<th>NoCont</th>
																<th>WeightCont</th>
																<th>TotalWeight</th>
																<th id="wizShedTh">ArrMonth</th>
															</tr>
														</thead>
														<tbody id="listGrid">
															
														</tbody>
													</table>
													
													<div id="gridSheduleSpanner" class="h1 m-t-xs text-navy hide">
														<span class="loading"></span>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div id="Note" class="">
									<h2>Note</h2>
									<div class="row">
										<div class="col-md-8">
											<div class="form-group">
												<label>Note </label>
												<textarea row="6" style="height:109px;" name="notes" id="notes" class="form-control"></textarea>
											</div>
											
											<?php if($_SESSION['id_company'] == 717){ 
												$importer='CERES';
											} else
												if($_SESSION['id_company'] == 641){ 
												$importer='PRO FAIR TRADE';
											} else {
												$importer='';
											} ?>
				
											<div class="form-group" style="margin-top:130px;">
												<small><b>Created by :</b> <?php echo $_SESSION['name']; ?></small><br/>
												<small><b>Importer :</b> <span id="req_saved_importer"></span></small><br/>
												<small style="margin-top:0;"><b>Date :</b> <?php echo gmdate("Y-m-d H:i"); ?></small>
											</div>
										</div>
										<div class="col-md-4">
											<div class="text-center">
												<div style="margin-top: 20px">
													<i class="fa fa-sticky-note" style="font-size: 180px;color: #e5e5e5 "></i>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		 </div>
	</div>
	
	
	
	<div class="modal fade bs-example-modal-md" id="modalPort" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="portModalLabel" style="color:#1ab394">Port</h4>
				</div>

				<div class="modal-body">
					<div id="contenu_modal">
						<form id="systPortForm">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label for="portname">Port name *</label>
										<input type="text" class="form-control" id="portname" placeholder="Enter the name of the port">
									</div>
								</div>
								
								<div class="col-md-6">
									<div class="form-group">
										<label for="port_type_id">Port type *</label>
										<select class="form-control" id="port_type_id">
											<?php echo $port_list; ?>
										</select>
									</div>
								</div>
								
								<div class="col-md-6">
									<div class="form-group">
										<label for="port_code">Port Code</label>
										<input type="text" class="form-control" id="port_code">
									</div>
								</div>
							</div>
						
							<div class="row" style="margin-top:40px;">
								<div class="col-md-6">
									<div class="form-group">
										<label for="qm_org_contact_id">Qm Contact</label>
										<select class="form-control" id="qm_org_contact_id">
											<?php echo $qm_contact_list; ?>
										</select>
									</div>
								</div>
							
								<div class="col-md-6">
									<div class="form-group">
										<label for="transit_days">Transit days</label>
										<input type="number" min="0" class="form-control" id="transit_days">
									</div>
								</div>
					
								<div class="col-md-6">
									<div class="form-group">
										<label for="town_id">Town *</label>
										<select class="form-control" id="town_id">
											<?php echo $towns_list; ?>
										</select>
									</div>
								</div>
								
								<div class="col-md-6">
									<div class="form-group">
										<label for="onward_delay">Onward Delay</label>
										<input type="number" min="0" class="form-control" id="onward_delay">
									</div>
								</div>
							</div>
							
							<input id="id_townport" type="hidden" />
						</form>
					</div>
				</div>

				<div id="portModalFooter" class="modal-footer">
					
				</div>
			</div>
		</div>
	</div>
	
	
	
	<div class="modal fade bs-example-modal-md" id="modalPortCost" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="portModalLabel" style="color:#1ab394">Cost</h4>
				</div>

				<div class="modal-body">
					<div style="display:none" id="alert-new-portcost-er" class="alert alert-danger alert-dismissable col-sm-12"></div>
					<div style="display:none" id="alert-new-portcost-su" class="alert alert-success alert-dismissable col-sm-12"></div>

					<div id="contenu_modal">
						<form id="sysPortCostForm">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="item_name">Item name</label>
										<input type="text" class="form-control" id="item_name" placeholder="Enter the name of the item">
									</div>
									
									<div class="form-group">
										<label for="measure_unit_id">Measure unit</label>
										<select class="form-control" id="measure_unit_id">	
											<?php echo $measure_unit_rcost_list; ?>
										</select>
									</div>
									
									<div class="form-group">
										<label for="active">Active</label>
										<select class="form-control" id="active">	
											<option value="TRUE">YES</option>
											<option value="FALSE">NO</option>
										</select>
									</div>
									
									<div class="form-group">
										<label for="validity_date">Validity date</label>
										<div class="input-group date">
											<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
											<input type="text" class="form-control edit_delivery_date" id="validity_date">
										</div>
									</div>
									
									<div class="form-group">
										<label for="sequence_nr">Sequence Number</label>
										<input class="form-control" type="number" min="0" id="sequence_nr">
									</div>
								</div>
								
								<div class="col-md-6">
									<div class="form-group">
										<label for="currency_id">Currency</label>
										<select class="form-control" id="currency_id" onchange="PtCostCurrSel(this.value);">	
											<?php echo $currency_list; ?>
										</select>
									</div>
									
									<div class="form-group">
										<label for="cost_eur">Cost EUR</label>
										<input type="text" class="form-control" id="cost_eur" disabled>
									</div>
									
									<div class="form-group">
										<label for="cost_usd">Cost USD</label>
										<input type="text" class="form-control" id="cost_usd" disabled>
									</div>
									
									<div class="form-group">
										<label for="cost_chf">Cost CHF</label>
										<input type="text" class="form-control" id="cost_chf" disabled>
									</div>
									
									<div class="form-group">
										<label for="calculation_method">Calculation method</label>
										<select class="form-control" id="calculation_method">
											<option value="">--Method--</option>
											<option value="1">1. One per shipment</option>
											<option value="2">2. Multiply with Nr of containers</option>
											<option value="3">3. Multiply with product quantity</option>
										</select>
									</div>
									
									<input id="syst_port_cost_id_reg_cost" type="hidden" />
								</div>
							</div>
						</form> 
					</div>
				</div>

				<div id="portCostModalFooter" class="modal-footer">
					<button type="button" class="btn btn-primary" onclick="newSysPortCost('add');"><i class="fa fa-save"></i></button>
					<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i></button>
				</div>
			</div>
		 </div>
	</div>
	
	
	<div class="modal fade bs-example-modal-md" id="newObject" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="editRoleModalLabel" style="color:#1ab394">Create new object</h4>
				</div>

				<div class="modal-body">
					<div style="display:none" id="alert-new-object-er" class="alert alert-danger alert-dismissable col-sm-12"></div>
					<div style="display:none" id="alert-new-object-su" class="alert alert-success alert-dismissable col-sm-12"></div>

					<div id="contenu_modal">
						<div class="form-group">
							<label for="object_menu_name">Name</label>
							<input type="text" class="form-control" id="object_menu_name" placeholder="Enter the name of the object">
						</div>
					</div>
				</div>

				<div id="update-footer" class="modal-footer">
					<button type="button" class="btn btn-primary" onclick="addNewObject();"><i class="fa fa-save"></i></button>
					<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i></button>
				</div>
			</div>
		 </div>
	</div>


	<div class="modal fade bs-example-modal-md" id="editRolemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="editRoleModalLabel" style="color:#1ab394">Edit Role</h4>
				</div>

				<div class="modal-body">
					<div style="display:none" id="update-alert-editrole1" class="alert alert-danger alert-dismissable col-sm-12"></div>
					<div style="display:none" id="update-alert-editrole2" class="alert alert-success alert-dismissable col-sm-12"></div>
					<div id="contenu_modal">
						<div class="form-group">
							<label for="editRole_name">Role</label>
							<input type="text" class="form-control" id="editRole_name" placeholder="Role name">
						</div>

						<div class="form-group">
							<label for="editRole_name_en">Role (En)</label>
							<input type="text" class="form-control" id="editRole_name_en" placeholder="Role name in english">
						</div>

						<div class="form-group">
							<label for="editRole_name_ge">Role (Ge)</label>
							<input type="text" class="form-control" id="editRole_name_ge" placeholder="Role name in german">
						</div>

						<div class="form-group">
							<label for="editRole_name_fr">Role (Fr)</label>
							<input type="text" class="form-control" id="editRole_name_fr" placeholder="Role name in frensh">
						</div>

						<div class="form-group">
							<label for="editRole_name_es">Role (Es)</label>
							<input type="text" class="form-control" id="editRole_name_es" placeholder="Role name in spanish">
						</div>

						<div class="form-group">
							<label for="editRole_name_po">Role (Po)</label>
							<input type="text" class="form-control" id="editRole_name_po" placeholder="Role name in portuguese">
						</div>
					</div>
				</div>

				<input type="hidden" class="form-control" id="editRole_id">

				<div id="update-footer" class="modal-footer">
					<button type="button" class="btn btn-primary" onclick="roleManagement('edit','');"><i class="fa fa-save"></i></button>
					<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i></button>
				</div>
			</div>
		 </div>
	</div>
	
	
	<div class="modal fade" id="image-gallery" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="image-gallery-title"></h4>
					<button type="button" class="close" data-dismiss="modal">
						<span aria-hidden="true">×</span><span class="sr-only">Close</span>
					</button>
				</div>
				
				<div class="modal-body row">
					<img id="image-gallery-image" class="img-responsive col-md-12" src="">
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary float-left" id="show-previous-image">
						<i class="fa fa-arrow-left"></i>
					</button>

					<button type="button" id="show-next-image" class="btn btn-secondary float-right">
						<i class="fa fa-arrow-right"></i>
					</button>
				</div>
			</div>
		</div>
	</div>
	
	
	<?php include_once 'include_lg.php'; ?>

	<script> 
		var pwd_reset = '<?php echo $_SESSION['pwd_reset']; ?>';  
		var user_culture = "<?php echo $_SESSION['id_culture']; ?>";  
	</script>
	
	
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
	
	<!-- SUMMERNOTE 
    <script src="js/plugins/summernote/summernote.min.js"></script> -->
	
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
	
	<!-- Ladda -->
    <script src="js/plugins/ladda/spin.min.js"></script>
    <script src="js/plugins/ladda/ladda.min.js"></script>
    <script src="js/plugins/ladda/ladda.jquery.min.js"></script>
	
	<!-- custom Js -->
	<script src="js/crm_1_5_125.js?v=1.5.175"></script> 
	<script src="js/conatiner_loading.js"></script>
	
	<!-- Data table --> 
	<script src="js/plugins/dataTables/datatables.min.js"></script>
	
	<!-- Pusher 
    <script src="https://js.pusher.com/4.1/pusher.min.js"></script>
	<script>
		Pusher.logToConsole = true;

		var pusher = new Pusher('e94927ee2910f4db65a6', {
			cluster: 'eu',
			encrypted: true
		});

		var channel = pusher.subscribe('my-channel');
		channel.bind('my-event', function(data) { 
			toastr.success(data.message,{timeOut:20000})
			var audio = new Audio('audio_file.mp3')
			audio.play()
			uploadCounter();
		});
		
		// clipboard
		var clipboard = new ClipboardJS('.btn');

		clipboard.on('success', function(e) {
			console.log(e);
		});

		clipboard.on('error', function(e) {
			console.log(e);
		});
    </script> -->	
</body>

</html>
