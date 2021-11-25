<?php
	session_start();
	
	include_once 'common.php';

	if(!isset($_SESSION['username'])){
		header("Location: login.php");
	}
?>


<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo $lang['PAGE_TITLE']; ?></title>

    <link href="css/playground/bootstrap.min.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="js/playground/leaflet/leaflet.css" rel="stylesheet">
	<link href="css/plugins/slick/slick.css" rel="stylesheet">
    <link href="css/plugins/slick/slick-theme.css" rel="stylesheet">
	<link href="css/plugins/jQueryUI/jquery-ui-1.10.4.custom.min.css" rel="stylesheet">
	<link href="css/plugins/jqGrid/ui.jqgrid.css" rel="stylesheet">

	<link href="js/plugins/ion.rangeSlider-2.1.4/css/ion.rangeSlider.css" rel="stylesheet">
    <link href="js/plugins/ion.rangeSlider-2.1.4/css/ion.rangeSlider.skinModern.css" rel="stylesheet">

    <!--  -->
    <link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">
	<script src="js/plugins/Leaflet.iconlabel-master/lib/leaflet-dist/leaflet.js"></script>
	<script src="js/plugins/Leaflet.iconlabel-master/src/Icon.Label.js"></script>
	<script src="js/plugins/Leaflet.iconlabel-master/src/Icon.Label.Default.js"></script>
	<link href="js/plugins/Leaflet.ZoomBox-master/L.Control.ZoomBox.css" rel="stylesheet"/>
	<link href="css/plugins/sweetalert/sweetalert.css" rel="stylesheet">

	<link href="js/plugins/Leaflet.groupedlayercontrol-gh-pages/src/leaflet.groupedlayercontrol.css" rel="stylesheet" />

    <!-- Gritter -->
	<link href="css/plugins/blueimp/css/blueimp-gallery.min.css" rel="stylesheet">
    <link href="js/plugins/gritter/jquery.gritter.css" rel="stylesheet">
	<link href="css/plugins/steps/jquery.steps.css" rel="stylesheet">
    <link href="js/plugins/leaflet-icon-pulse-master/src/L.Icon.Pulse.css" rel="stylesheet" />
	<link href="js/plugins/leaflet-routing-machine-3.0.3/dist/leaflet-routing-machine.css" rel="stylesheet" />
	<link href="js/plugins/Leaflet.iconlabel-master/src/Icon.Label.css" rel="stylesheet" />
	<link href="js/playground/Leaflet.EasyButton-master/easy-button.css" rel="stylesheet" />
    <link href="css/plugins/select2/select2.min.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/playground/style.css" rel="stylesheet">
	<link href='https://fonts.googleapis.com/css?family=Lato:900,300' rel='stylesheet' type='text/css'>


	<style>
		.sweet-deal-label {
			background-color: #ffffff;
			background-color: rgba(100, 100, 100, 0);
			-moz-box-shadow: none;
			-webkit-box-shadow: none;
			box-shadow: none;
			/* text-shadow: 1px 1px 1px #000;
			color: #ffffff; */
			color: #ffa500;
			font-weight: bold;
		}

		.sweet-deal-label2 {
			background-color: #ffffff;
			background-color: rgba(100, 100, 100, 0);
			-moz-box-shadow: none;
			-webkit-box-shadow: none;
			box-shadow: none;
			color: orange;
			font-weight: bold;
		}

		select.icon-menu option {
			background-repeat:no-repeat;
			background-position:bottom left;
			padding-left:30px;
		}

		/* Let's get this party started */
		::-webkit-scrollbar {
			width: 7px;
			height: 7px;
		}

		/* Track */
		::-webkit-scrollbar-track {
			-webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
			-webkit-border-radius: 10px;
			border-radius: 10px;
		}

		/* Handle */
		::-webkit-scrollbar-thumb {
			-webkit-border-radius: 10px;
			border-radius: 10px;
			background: rgba(26,179,148,0.8);
			-webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.5);
		}

		::-webkit-scrollbar-thumb:window-inactive {
			background: rgba(26,179,148,0.4);
		}

		#video {
			  position: relative;
			  background: #000;
			  width: 640px;
			  margin: 0px auto;
			}

			#video img,
			#video iframe { display: block; }

			.play {
			  position: absolute;
			  top: 0;
			  left: 15%;
			  width: 70%;
			  height: 100%;
			  cursor: pointer;
			  background: url('js/plugins/Lightweight-jQuery-Youtube-Video-Player-simplePlayer/play-button.png') no-repeat 50% 50%;
			  background-size: auto, cover;
			  z-index: 9999;
			}

			.play:hover { background-color: rgba(0,0,0,0.2) !important; }


	</style>


</head>

<body>
  <?php include("include/playground.php"); ?>
    <div id="wrapper" style="margin-top: 50px;">
	    <div class="row  border-bottom white-bg dashboard-header">
			<nav class="navbar navbar-default navbar-fixed-top" role="navigation" style="margin-bottom: 0">
				<div class="navbar-header" style="display: block; float: left;">
					<a href="#" class="navbar-brand" style=""><img class="" alt=""  src="img/playground/logo.png"  width="120" /></a>
				</div>

				<div class="navbar-header page-scroll pull-right">
					<button type="button" class="navbar-toggle collapsed" style="background-color:transparent; border:none; overflow-x:hidden;" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				</div>

				<div class="navbar-collapse collapse" id="navbar" style="width:100%;">
					<ul class="nav navbar-top-links navbar-left" style="margin-left:10px;">
						<li style="margin:10px 0;"><select  class="chosen-language" onchange="sitelang();" id="langue" style="width:70px;z-index: 2008;" tabindex="2">
							<option value="playground.php?lang=fr" <?php if($lang['DB_LANG_stat']=='fr'){ echo 'selected'; } ?>>Fr</option>
							<option value="playground.php?lang=en" <?php if($lang['DB_LANG_stat']=='en'){ echo 'selected'; } ?>>En</option>
							<option value="playground.php?lang=de" <?php if($lang['DB_LANG_stat']=='de'){ echo 'selected'; } ?>>De</option>
							<option value="playground.php?lang=pt" <?php if($lang['DB_LANG_stat']=='pt'){ echo 'selected'; } ?>>Pt</option>
						</select></li>
					</ul>

					<ul class="nav navbar-nav menu" style="margin-left:10px;">
						<li class="menu_h active">
							<a aria-expanded="false" class="page-scroll" style="position:relative; padding:0;" onclick="refrechSelection();" role="button" href="#">
								<div  class="home-search" style="position:absolue;"> <?php echo $lang['MENU_SEARCH']; ?> </div>
							</a>
						</li>
						
						<?php 
							if($_SESSION['id_culture']!=0){
								$culture = explode(',', $_SESSION['id_culture']);
								$id_culture = $culture[0];
								$id_culture1 = $culture[1];
							} else {
								$id_culture = $_SESSION['id_culture'];
								$id_culture1 = $_SESSION['id_culture'];
							}
							
							if($_SESSION['id_exporter'] == 0){ 
								$cult_1=''; 
								$cult_2='';
								$cult_3='';
								$cult_4='';
								$cult_5='';
							} else { 
								// Menu Coconut
								if($id_culture==1){ $cult_1=''; } else if($id_culture1==1){ $cult_1=''; } else { $cult_1='hide'; } 
								// Menu Palm
								if($id_culture==2){ $cult_2=''; } else if($id_culture1==2){ $cult_2=''; } else { $cult_2='hide'; }
								// Menu Peanut
								if($id_culture==3){ $cult_3=''; } else if($id_culture1==3){ $cult_3=''; } else { $cult_3='hide'; }
								// Menu Sunflower
								if($id_culture==4){ $cult_4=''; } else if($id_culture1==4){ $cult_4=''; } else { $cult_4='hide'; }
								// Menu Safflower
								if($id_culture==5){ $cult_5=''; } else if($id_culture1==5){ $cult_5=''; } else { $cult_5='hide'; }
							}
						?>
						
						<li id="cult_1" class="<?php echo $cult_1; ?>">
							<a aria-expanded="false" class="ci_mz page-scroll" role="button" href="#" onclick="select_culture(1);" style="padding: 10px 5px;">
								<img class="icons" src="img/playground/coconut_oil.png" height="28" /> <span style="line-height:28px;"><?php echo $lang['MENU_COCO']; ?>&nbsp;&nbsp;</span>
							</a>
						</li>
						<li id="cult_2" class="<?php echo $cult_2; ?>">
							<a aria-expanded="false" class="ci1_kh page-scroll" role="button" href="#" onclick="select_culture(2);" style="padding: 10px 5px;">
								<img class="icons" src="img/playground/palm_oil.png" height="28" /> <span style="line-height:28px;"><?php echo $lang['MENU_PALM']; ?> &nbsp;&nbsp;</span>
							</a>
						</li>
						<li id="cult_3" class="<?php echo $cult_3; ?>">
							<a aria-expanded="false" class="sn_sd page-scroll" role="button" href="#" onclick="select_culture(3);" style="padding: 10px 5px;">
								<img class="icons" src="img/playground/peanut_oil.png" height="28" /> <span style="line-height:28px;"><?php echo $lang['MENU_ARACHIDE']; ?> &nbsp;&nbsp;</span>
							</a>
						</li>
						 <li id="cult_4" class="<?php echo $cult_4; ?>">
							<a aria-expanded="false" class="tz_mz1 page-scroll" role="button" href="#" onclick="select_culture(4);" style="padding: 10px 5px;">
								<img class="icons" src="img/playground/sunflower_oil.png" height="28" /> <span style="line-height:28px;"><?php echo $lang['MENU_TOURNESOL']; ?> &nbsp;&nbsp;</span>
							</a>
						</li>
						<li id="cult_5" class="<?php echo $cult_5; ?>">
							<a aria-expanded="false" class="tz1_sd1 page-scroll" role="button" href="#" onclick="select_culture(5);" style="padding: 10px 5px;">
								<img class="icons" src="img/playground/safflower_oil.png" height="28" /> <span style="line-height:28px;"><?php echo $lang['MENU_CARTHAME']; ?> &nbsp;&nbsp;</span>
							</a>
						</li>
						<li id="cult_5" class="<?php echo $cult_5; ?>">
							<a aria-expanded="false" class="tz1_sd1 page-scroll" role="button" href="#" onclick="select_culture(5);" style="padding: 10px 5px;">
								<img class="icons" src="img/playground/soybeanoil.png" height="28" /> <span style="line-height:28px;"><?php echo $lang['MENU_SOY']; ?> &nbsp;&nbsp;</span>
							</a>
						</li>
					</ul>

					<ul class="nav navbar-top-links navbar-right" style="margin-left:20px;">
						<?php
							$idview = 0;
							
							if (isset($_SESSION['username'])){
							$session = 1;

							if(isset($_SESSION['id_buyer'])){ $id_buyer = $_SESSION['id_buyer']; } else { $id_buyer = 0; }
							$id_exporter = $_SESSION['id_exporter'];
							$code_country = $_SESSION['code_country'];
							
							if(isset($_SESSION['idview'])){ $idview = $_SESSION['idview']; } else { $idview = 0; }
							
							$culture = $_SESSION['id_culture'];

							echo '<li><span class="mob-off">'. $_SESSION['username'].'</span></li>';
						?>

							<li style="margin-right: 20px;">
								<a href="logout.php">
									<i class="fa fa-sign-out"></i><span class="mob-off"> <?php echo $lang['MENU_LOGOUT']; ?></span>
								</a>
							</li>

						<?php } else {
							$session = 0;

							$id_buyer = 'null';
							$id_exporter = 'null';
							$code_country = 'null';
							$idview = 0;
							$culture = 'null';
						?>
							<li style="margin-right: 20px;">
								<a href="#" data-toggle="collapse" data-target=".navbar-collapse.in" id="login-btn">
									<i class="fa fa-sign-in"></i> <?php //echo $lang['MENU_LOGIN']; ?>
								</a>
							</li>

						<?php } ?>
					</ul>
				</div>
			</nav>
		</div>


        <div id="page-wrapper" class="gray-bg dashbard-1">
			<div class="row">
				<!-- <div class="col-lg-12"> -->
					<div class="wrapper wrapper-content animated rollIn" >
						<div class="row">
                            <div class="col-md-8 col-sm-8 col-xs-12" style="padding-right: 20px;">
								<div class="ibox float-e-margins">
                                    <div class="ibox-content no-padding" style="box-shadow: 0 0 10px rgba(0,0,0,0.5); border-radius:5px;">
										<div id="map" style="height:450px;"></div>
									    <div id="loading" class="spiner-example" style="padding-top: 0px;position: absolute;z-index: 20001;top: 40%;left: 50%;">
											<div class="sk-spinner sk-spinner-wave">
												<div class="sk-rect1"></div>
												<div class="sk-rect2"></div>
												<div class="sk-rect3"></div>
												<div class="sk-rect4"></div>
												<div class="sk-rect5"></div>
											</div>
										</div>
                                    </div>
                                </div>
                            </div>


							<div class="col-md-4 col-sm-4 col-xs-12">
								<div class="ibox">
									<div class="ibox-content" style="box-shadow: 0 0 10px rgba(0,0,0,0.5); border-radius:5px;">
										<div style="min-height:415px;" class="hide" id="navi">
											<div class="tabs-container" style="padding:0;">
												<ul class="nav nav-tabs">
													<li class="active tab1"><a data-toggle="tab" href="#tab-1"> <?php echo $lang['RB_TAB_1']; ?></a></li>
													<li class="tab2"><a data-toggle="tab" href="#tab-2"> <?php echo $lang['RB_TAB_2']; ?></a></li>
													<li class="tab3"><a data-toggle="tab" href="#tab-3"> <?php echo $lang['RB_TAB_3']; ?></a></li>
													<li class="tab4"><a data-toggle="tab" href="#tab-4"> <?php echo $lang['RB_TAB_4']; ?></a></li>
												</ul>
												<div class="tab-content">
													<div id="tab-1" class="tab-pane active">
														<div class="animated panel-body" id="storyBox" style="border:none; padding:10px 0;">

														</div>
													</div>
													<div id="tab-2" class="tab-pane">
														<div class="panel-body" id="lifeBox" style="border:none; padding:10px 0;">

														</div>
													</div>
													<div id="tab-3" class="tab-pane">
														<div class="panel-body" id="socialBox" style="border:none; padding:10px 0;">

														</div>
													</div>
													<div id="tab-4" class="tab-pane">
														<div class="panel-body" id="economyBox" style="border:none; padding:10px 0;">

														</div>
													</div>
												</div>
											</div>
										</div>

										<div style=" height: 415px;overflow-y: scroll;width: auto;" id="acc">

										</div>

										<div id="loading1" class="spiner-example" style="padding-top: 0px;position: absolute;z-index: 20001;top: 40%;left: 50%;">
											<div class="sk-spinner sk-spinner-wave">
												<div class="sk-rect1"></div>
												<div class="sk-rect2"></div>
												<div class="sk-rect3"></div>
												<div class="sk-rect4"></div>
												<div class="sk-rect5"></div>
											</div>
										</div>
									</div>
                                </div>
                            </div>

                        </div>

						<div class="row">
							<div id="bt_panel">
								<div class="col-md-2 col-sm-3 col-xs-12">
									<div class="ibox float-e-margins">
									   <div class="panel panel-primary" style="box-shadow: 0 0 10px rgba(0,0,0,0.5);">
											<div class="panel-heading" style="padding: 5px 10px;">
												<h5  id="box1_title" style=""></h5>
											</div>
											<div class="panel-body" style="padding: 25px 15px;">
											   <h1 class="no-margins mob-font-h1" id="box1"></h1>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-2 col-sm-3 col-xs-12">
									<div class="ibox float-e-margins">

									  <div class="panel panel-primary" style="box-shadow: 0 0 10px rgba(0,0,0,0.5);">
											<div class="panel-heading" style="padding: 5px 10px;">
												 <h5  id="box2_title" style=""></h5>
											</div>
											<div class="panel-body" style="padding: 25px 15px;">
											   <h1 class="no-margins mob-font-h1" id="box2"></h1>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-2 col-sm-3 col-xs-12">
									<div class="ibox float-e-margins">
										 <div class="panel panel-primary" style="box-shadow: 0 0 10px rgba(0,0,0,0.5);">
											<div class="panel-heading" style="padding: 5px 10px;">
												<h5  id="box3_title" style=""></h5>
											</div>
											<div class="panel-body" style="padding: 25px 15px;">
											   <h1 class="no-margins mob-font-h1" id="box3"></h1>
											</div>

										</div>
									</div>
								</div>
								<div class="col-md-2 col-sm-3 col-xs-12">
									<div class="ibox float-e-margins">
										<div class="panel panel-primary" style="box-shadow: 0 0 10px rgba(0,0,0,0.5);">
											<div class="panel-heading" style="padding: 5px 10px;">
												<h5  id="box4_title" style=""></h5>
											</div>
											<div class="panel-body" style="padding: 25px 15px;">
											   <h1 class="no-margins mob-font-h1" id="box4"></h1>
											</div>

										</div>
									</div>
								</div>
							</div>

							<div id="bt_images" class="hide">
								<div class="col-md-8 col-sm-6 col-xs-12">
									<div class="ibox bt_img_box">
										<div class="panel panel-primary bt_img">
											<div class="lightBoxGallery" id="lightBoxGallery">

											</div>
										</div>
									</div>

								</div>
							</div>

							<div class="col-md-4 col-sm-12 col-xs-12 hide">
								<div class="ibox float-e-margins" style="box-shadow: 0 0 10px rgba(0,0,0,0.5); border-radius:5px;">
									<div class="panel panel-primary jqGrid_wrapper" style="box-shadow: 0 0 10px rgba(0,0,0,0.5);">
										<div class="panel-heading" style="padding: 5px 10px;">
											<h5  id="ticker_title" style=""><?php echo $lang['TCK_TITLE']; ?></h5>
										</div>
										<table id="table_list_1"></table>
									</div>
								</div>
							</div>
						</div>
                    </div>

                <!-- </div> -->
			</div>

			<div class="footer fixed">

                <div>
                    <strong>Copyright</strong> Pro Fair Trade AG &copy; 2016 - Developed by <a target="_blanck" href="https://www.linkedin.com/in/christoph-roth-28698924">dev4impact</a>
                </div>
            </div>

			<div class="small-chat-box fadeInRight animated">
				<div class="content" id="img_content">

				</div>
			</div>

			<div id="small-chat" class="hide">
				<a class="open-small-chat">
					<i class="fa fa-camera"></i>
				</a>
			</div>
		</div>
    </div>

	<div id="sideBarBtnToggle" class="hide animated" style="color:white;opacity:0.8"><i class="fa fa-caret-left"></i></div>
    <div id="right-sidebar" class="col-lg-4 animated" style="padding:0; max-width:408px; background-color: rgba(255, 255, 255, 0.6);">
		<!-- Mainly scripts -->
		<div id="sideBarSearchBox" class="sideBarSearchBox">

		</div>
        <div class="sidebar-container">
			<div id="rightInfos">

			</div>
        </div>
    </div>

</div>

	<!-- The Gallery as lightbox dialog, should be a child element of the document body -->
	<div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls">
		<div class="slides"></div>
		<h3 class="title" style="display: block;"></h3>
		<a class="prev" style="display: block;">‹</a>
		<a class="next" style="display: block;">›</a>
		<a class="close" style="display: block;">×</a>
		<a class="play-pause" style="display: block;"></a>
		<ol class="indicator" style="display: block;"></ol>
	</div>


	<div class="modal inmodal" id="countryModal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content animated flipInY">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title"><?php echo $lang['COUNTRY_TITLE']; ?></h4>
				</div>

				<div class="modal-body">
					<div class="row">
						<div class="col-lg-12">
							<div class="ibox float-e-margins">
								<div class="ibox-content">
									<table class="table table-hover no-margins">
										<thead align="center">
											<tr>
												<th>Continents</th>
												<th>Countries</th>
												<th>Flags</th>
											</tr>
										</thead>

										<tbody id="countryM">
											<tr>
												<td><small>Afrique</small></td>
												<td> <?php echo $lang['DB_PAYS_ci']; ?></td>
												<td><img src="img/ci.png" height="20"/></td>
											</tr>
											<tr>
												<td><small>Afrique</small></td>
												<td> <?php echo $lang['DB_PAYS_sn']; ?></td>
												<td><img src="img/sn.png" height="20"/></td>
											</tr>
											<tr>
												<td><small>Afrique</small></td>
												<td> <?php echo $lang['DB_PAYS_sd']; ?></td>
												<td><img src="img/sd.png" height="20"/></td>
											</tr>
											<tr>
												<td><small>Afrique</small></td>
												<td> <?php echo $lang['DB_PAYS_mz']; ?></td>
												<td><img src="img/mz.png" height="20"/></td>
											</tr>
											<tr>
												<td><small>Afrique</small></td>
												<td> <?php echo $lang['DB_PAYS_tz']; ?></td>
												<td><img src="img/tz.png" height="20"/></td>
											</tr>
											<tr>
												<td><small>Asie</small></td>
												<td> <?php echo $lang['DB_PAYS_kh']; ?></td>
												<td><img src="img/kh.png" height="20"/></td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal inmodal" id="productModal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content animated flipInY">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title"><?php echo $lang['PRODUCT_TITLE']; ?></h4>
				</div>

				<div class="modal-body">
					<div class="row">
						<div class="col-lg-12">
							<div class="ibox float-e-margins">
								<div class="ibox-content">
									<table class="table table-hover no-margins">
										<thead>
											<tr>
												<th>Products</th>
												<th>Images</th>
											</tr>
										</thead>

										<tbody>
											<tr>
												<td><?php echo $lang['MENU_COCO']; ?></td>
												<td><img src="img/playground/coconut_oil.png" height="20"/></td>
											</tr>
											<tr>
												<td><?php echo $lang['MENU_PALM']; ?></td>
												<td><img src="img/playground/palm_oil.png" height="20"/></td>
											</tr>
											<tr>
												<td><?php echo $lang['MENU_ARACHIDE']; ?></td>
												<td><img src="img/playground/peanut_oil.png" height="20"/></td>
											</tr>
											<tr>
												<td><?php echo $lang['MENU_TOURNESOL']; ?></td>
												<td><img src="img/playground/sunflower_oil.png" height="20"/></td>
											</tr>
											<tr>
												<td><?php echo $lang['MENU_CARTHAME']; ?></td>
												<td><img src="img/playground/safflower_oil.png" height="20"/></td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>


	<div class="modal inmodal" id="suppliertModal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content animated flipInY">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title"><?php echo $lang['SUPPLIER_TITLE']; ?></h4>
				</div>

				<div class="modal-body">
					<div class="row">
						<div class="col-lg-12">
							<div class="ibox float-e-margins">
								<div class="ibox-content">
									<table class="table table-hover no-margins">
										<thead>
											<tr>
												<th>Suppliers</th>
												<th>Logo</th>
											</tr>
										</thead>

										<tbody>
											<tr>
												<td></td>
												<td></td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal inmodal" id="farmerstModal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content animated flipInY">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title"><?php echo $lang['FARMERS_TITLE']; ?></h4>
				</div>

				<div class="modal-body">
					<div class="row">
						<div class="col-lg-12">
							<div class="ibox float-e-margins">
								<div class="ibox-content">
									<table class="table table-hover no-margins">
										<thead>
											<tr>
												<th>Farmers Names</th>
												<th>Number of plantations</th>
												<th>Surfaces (Ha)</th>
											</tr>
										</thead>

										<tbody>
											<tr>
												<td></td>
												<td></td>
												<td></td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal inmodal" id="surfacetModal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content animated flipInY">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title"><?php echo $lang['SURFACE_TITLE']; ?></h4>
				</div>

				<div class="modal-body">
					<div class="row">
						<div class="col-lg-12">
							<div class="ibox float-e-margins">
								<div class="ibox-content">
									<table class="table table-hover no-margins">
										<thead>
											<tr>
												<th>Surface</th>
												<th>Ha</th>
											</tr>
										</thead>

										<tbody>
											<tr>
												<td></td>
												<td></td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>


	<div class="modal fade" id="loginModal" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-sm">
			<div class="panel panel-info modal-content">
				<div class="panel-heading modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title"><i class="fa fa-user white">&nbsp;</i>Connexion</h4>
					<div style="float:right; font-size: 80%; position: relative; top:-10px">

					<a href="#" onClick=" $('#updateModal').modal('hide');  $('#mdpo').modal('show');" style="color:#fff;">
						Mot de passe Oublié?
					</a></div>
				   </div>

				<div style="padding-top:30px" class="panel-body modal-body">
					<div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
					<div style="display:none" id="login-alert2" class="alert alert-success col-sm-12"></div>

					<form id="loginform" class="form-horizontal" role="form">
						<fieldset style="display:inline" >
							<div style="margin-bottom: 25px" class="input-group">
							  <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
							  <input id="login-username" type="text" class="form-control" name="nom" value="" placeholder=" nom utilisateur">
							</div>

							<div style="margin-bottom: 15px" class="input-group">
							  <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
							  <input id="login-password" type="password" class="form-control" name="password" placeholder="Mot de passe">
							</div>
						</fieldset>
					  <br/> <br/>

						<button type="button" class="btn btn-default" onclick="reset_connexion()">Annuler</button>
						<button type="button" class="btn btn-primary" onclick="connexion()">S'identifier</button>
					</form>
				</div>

				<div class="modal-footer">
					<div class="form-group">
						<div class="col-md-12 control">
							<div style="font-size:85%" >
								Vous n'avez pas de compte!
								<a href="#" onClick=" $('#loginModal').modal('hide');  $('#signupbox').modal('show');">
									S'inscrire ici
								</a>
							</div>
						</div>
					</div>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


    <!-- Mainly scripts -->
    <script src="js/jquery-2.1.1.js"></script>
    <script src="js/bootstrap.min.js"></script>

	<script src="js/arc.js"></script>
    <script src="js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="js/playground/leaflet/leaflet.js"></script>
	<script src="js/plugins/Leaflet.markercluster/leaflet.markercluster-src.js"></script>
	<script src="js/plugins/metisMenu/jquery.metisMenu.js"></script>

	<!-- blueimp gallery -->
    <script src="js/plugins/blueimp/jquery.blueimp-gallery.min.js"></script>
    <script src="js/plugins/Leaflet.PolylineDecorator-master/leaflet.polylineDecorator.js"></script>

    <!-- Flot -->
    <script src="js/plugins/flot/jquery.flot.js"></script>
    <script src="js/plugins/flot/jquery.flot.tooltip.min.js"></script>
    <script src="js/plugins/flot/jquery.flot.spline.js"></script>
    <script src="js/plugins/flot/jquery.flot.resize.js"></script>
    <script src="js/plugins/flot/jquery.flot.pie.js"></script>

    <!-- Peity -->
    <script src="js/plugins/peity/jquery.peity.min.js"></script>
    <script src="js/demo/peity-demo.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="js/playground/inspinia.js"></script>
    <script src="js/plugins/pace/pace.min.js"></script>

    <!-- jQuery UI -->
    <script src="js/plugins/jquery-ui/jquery-ui.min.js"></script>

    <!-- GITTER -->
    <script src="js/plugins/gritter/jquery.gritter.min.js"></script>

    <!-- Sparkline -->
    <script src="js/plugins/sparkline/jquery.sparkline.min.js"></script>

    <!-- Sparkline demo data  -->
    <script src="js/demo/sparkline-demo.js"></script>
    <script src='js/plugins/Leaflet.fullscreen-gh-pages/dist/Leaflet.fullscreen.min.js'></script>
	<script src="js/plugins/slick/slick.min.js"></script>

    <!-- Additional style only for demo purpose -->
    <style>
        .slick_demo_2 .ibox-content {
            margin: 0 10px;
        }
    </style>

    <!-- ChartJS-->
    <script src="js/plugins/chartJs/Chart.min.js"></script>
    <script src="js/underscore-min.js"></script>

    <script src="js/plugins/Leaflet.iconlabel-master/src/Icon.Label.js"></script>
	<script src="js/plugins/select2/select2.full.min.js"></script>
	<script src="js/plugins/leaflet-icon-pulse-master/src/L.Icon.Pulse.js"></script>
	<script src="js/plugins/jqGrid/i18n/grid.locale-en.js"></script>
    <script src="js/plugins/jqGrid/jquery.jqGrid.min.js"></script>
	<script src="js/plugins/Lightweight-jQuery-Youtube-Video-Player-simplePlayer/simplePlayer.js"></script>

    <!-- Toastr -->
	<script src="js/plugins/sweetalert/sweetalert.min.js"></script>
    <script src="js/plugins/toastr/toastr.min.js"></script>
	<script src="https://cdn.polyfill.io/v2/polyfill.min.js?features=Promise"></script>

	<script src="js/plugins/Leaflet.ZoomBox-master/L.Control.ZoomBox.min.js"></script>
	<script src="js/plugins/leaflet-routing-machine-3.0.3/dist/leaflet-routing-machine.js"></script>
	<script src="js/playground/Leaflet.EasyButton-master/easy-button.js"></script>
	<script src="js/plugins/Leaflet.Terminator-master/L.Terminator.js"></script>
	<script src="js/plugins/staps/jquery.steps.min.js"></script>
	<script src="js/jquery.jclock.js"></script>
	<script src="js/plugins/jsKnob/jquery.knob.js"></script>
	<script src="js/plugins/ion.rangeSlider-2.1.4/js/ion-rangeSlider/ion.rangeSlider.min.js"></script>

	<script src="https://maps.google.com/maps/api/js?key=AIzaSyBcOXamzcMVv4w0sCQBnXFaFjVwrL4k73E&exp&sensor=false"></script>

	<script src="js/plugins/leaflet-plugins-master/layer/tile/Google1.js"></script>
	<script src="js/plugins/Leaflet.Polyline.SnakeAnim-master/L.Polyline.SnakeAnim.js"></script>

   <script src="js/plugins/Leaflet.groupedlayercontrol-gh-pages/src/leaflet.groupedlayercontrol.js"></script>


	<script> 
		var idview = <?php echo $idview; ?>; 	
	
		var lg_countries = "<?php echo $lang['RB_Countries'];  ?>"; 
		var lg_view_story = "<?php echo $lang['RB_Visualiser'];  ?>"; 
		var lg_pays_ci = "<?php echo $lang['DB_PAYS_ci'];  ?>"; 
		var lg_pays_sn = "<?php echo $lang['DB_PAYS_sn'];  ?>"; 
		var lg_pays_sd = "<?php echo $lang['DB_PAYS_sd'];  ?>"; 
		var lg_pays_kh = "<?php echo $lang['DB_PAYS_kh'];  ?>"; 
		var lg_pays_mz = "<?php echo $lang['DB_PAYS_mz'];  ?>"; 
		var lg_pays_tz = "<?php echo $lang['DB_PAYS_tz'];  ?>"; 
		var lg_pays_ch = "<?php echo $lang['DB_PAYS_ch'];  ?>"; 

		var lg_product_first = "<?php echo $lang['DB_product_first'];  ?>"; 
		var lg_local_time = "<?php echo $lang['DB_local_time'];  ?>"; 

		// Village left drawer
		
		var lg_farmervillage = "<?php echo $lang['DB_farmervillage'];  ?>"; 
		var lg_equipement = "<?php echo $lang['DB_equipement'];  ?>"; 
		var lg_farmers_list = "<?php echo $lang['DB_farmers_list'];  ?>"; 
		var lg_farmers_plantations = "<?php echo $lang['DB_plantation'];  ?>"; 
		var lg_helf = "<?php echo $lang['DB_helf'];  ?>"; 
		var lg_schools = "<?php echo $lang['DB_schools'];  ?>"; 
		var lg_show_all_collection_points = "<?php echo $lang['DB_show_all_collection_points'];  ?>"; 
		var lg_hide_all_collection_points = "<?php echo $lang['DB_hide_all_collection_points'];  ?>"; 
		var lg_show_all_plantations = "<?php echo $lang['DB_show_all_plantations'];  ?>"; 
		var lg_hide_all_plantations = "<?php echo $lang['DB_hide_all_plantations'];  ?>"; 
		var lg_protected_areas = "<?php echo $lang['DB_protected_areas'];  ?>"; 
		
		var lg_crop = "<?php echo $lang['DB_crop'];  ?>"; 
		var lg_other_crops = "<?php echo $lang['DB_other_crops'];  ?>"; 
		var lg_other_source_income = "<?php echo $lang['DB_other_source_income'];  ?>"; 
		var lg_no_employees = "<?php echo $lang['DB_no_employees'];  ?>"; 
		var lg_last_audit_profairtrade = "<?php echo $lang['DB_last_audit_profairtrade'];  ?>"; 
		var lg_fields_surface_total = "<?php echo $lang['DB_fields_surface_total'];  ?>"; 
		var lg_plantation_quality = "<?php echo $lang['DB_plantation_quality'];  ?>"; 
		var lg_professional_capacity = "<?php echo $lang['DB_professional_capacity'];  ?>"; 
		var lg_professional_experience = "<?php echo $lang['DB_professional_experience'];  ?>"; 
		var lg_farmer_village = "<?php echo $lang['DB_farmer_village'];  ?>"; 
		var lg_farmer_group = "<?php echo $lang['DB_farmer_group'];  ?>"; 
		var lg_farmer_birthday = "<?php echo $lang['DB_farmer_birthday'];  ?>"; 
		var lg_farmer_gender = "<?php echo $lang['DB_farmer_gender'];  ?>"; 
		var lg_farmer_contacts = "<?php echo $lang['DB_farmer_contacts'];  ?>"; 
		var lg_civil_state = "<?php echo $lang['DB_civil_state'];  ?>"; 
		var lg_farmer_children = "<?php echo $lang['DB_farmer_children'];  ?>"; 
		var lg_farmer_children_school = "<?php echo $lang['DB_farmer_children_school'];  ?>"; 

		// Ticker
		
		var lg_ticker_date = "<?php echo $lang['TCK_DATE'];  ?>";
		var lg_ticker_by = "<?php echo $lang['TCK_BY'];  ?>";
		var lg_ticker_to = "<?php echo $lang['TCK_TO'];  ?>";
		var lg_ticker_product = "<?php echo $lang['TCK_PRODUCT'];  ?>";
		var lg_ticker_contract = "<?php echo $lang['TCK_CONTRACT'];  ?>";

		//Plantation Pop-up
	
		var lg_collection_point_details = "<?php echo $lang['DB_collection_point_details'];  ?>";
		var lg_plantation_details = "<?php echo $lang['DB_plantation_details'];  ?>";
		var lg_plantation_farmer_name = "<?php echo $lang['DB_plantation_farmer_name'];  ?>";
		var lg_plantation_farmer_residence = "<?php echo $lang['DB_plantation_farmer_residence'];  ?>";
		var lg_plantation_farmer_groups = "<?php echo $lang['DB_plantation_farmer_groups'];  ?>";
		var lg_plantation_area = "<?php echo $lang['DB_plantation_area'];  ?>";
		var lg_plantation_culture = "<?php echo $lang['DB_plantation_culture'];  ?>";
		var lg_plantation_culture_variety = "<?php echo $lang['DB_plantation_culture_variety'];  ?>";
		var lg_plantation_buyer = "<?php echo $lang['DB_plantation_buyer'];  ?>";
		
		
		var loggedin = <?php echo $session; ?>;
		var id_buyer = <?php echo $id_buyer; ?>;
		var id_exporter = <?php echo $id_exporter; ?>;
		var code = '<?php echo $code_country; ?>'; 
		var id_culture = <?php echo $culture; ?>;  
	</script>

	<script src="js/playground.js"></script>
	<script src="js/plugins/video/responsible-video.js"></script>

</body>
</html>
