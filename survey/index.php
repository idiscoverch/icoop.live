<?php 
	$id_contact = $_GET['u']; 
	$id_agent = $_GET['s']; 
	$template = $_GET['tp'];
	
	include_once("../fcts.php");
	$conn = connect();
	
	$tt_answers = 0;
	$question = "";
	$answers = "";
	$seq = 0;
	
	// User data
	$sqlUser = "SELECT id_contact FROM v_icw_contacts WHERE id_contact = $id_contact";
	$rsUser = pg_query($conn, $sqlUser);
	$rowUser = pg_fetch_assoc($rsUser);

	if((($id_contact!="") && ($rowUser['id_contact']!="")) &&
		($id_agent!="") &&
		($template!="")
	){
		$view = TRUE;
		
		// Survey Data
		$sqlUserAns = "SELECT COUNT(id_suranswer) tt_answers FROM public.sur_survey_answers WHERE id_contact = $id_contact AND surtemplate_id = $template";
		$rsUserAns = pg_query($conn, $sqlUserAns);
		$rowUserAns = pg_fetch_assoc($rsUserAns);
		
		$tt_answers = $rowUserAns['tt_answers'];
		$seq = $tt_answers + 1;
		
		// Template
		$sqlTemplate = "SELECT description FROM public.sur_template WHERE id_survey = $template";
		$rsTemplate= pg_query($conn, $sqlTemplate);
		$rowTemplate = pg_fetch_assoc($rsTemplate);
		
		$description = $rowTemplate['description'];
		
		// Question
		$sqlQuestion = "SELECT * FROM public.sur_questions WHERE q_seq = $seq AND surtemplate_id = $template";
		$rsQuestion = pg_query($conn, $sqlQuestion);
		$rowQuestion = pg_fetch_assoc($rsQuestion);
		
		$question = $rowQuestion['q_text'];
		$id_surq = $rowQuestion['id_surq'];
		
		if(!empty($id_surq)) {
			// Answer
			$sqlAnswers = "SELECT * FROM public.sur_answers WHERE surq_id = $id_surq ORDER BY ans_text_en ASC";
			$rsAnswers = pg_query($conn, $sqlAnswers);
			while($rowAnswers = pg_fetch_assoc($rsAnswers)) {
				$answers .= '<div class="m-xs">
					<input type="radio" name="radio_ans" id="radio_ans_'.$rowAnswers['id_suranswer'].'" class="i-checks" value="'.$rowAnswers['id_suranswer'].'">
					<label for="radio_ans_'.$rowAnswers['id_suranswer'].'">'.$rowAnswers['ans_text_en'].'</label>
				</div>';
			}
			
		} else {
			$question = "end";
		}
		
	} else {
		$view = FALSE;
	}
?>

<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo $description; ?></title>

	<link href="../img/icrm_logo-57x57.png" rel="shortcut icon">
	
    <link href="../css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <link href="../css/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="../css/animate.css" rel="stylesheet">

	<!-- Material Design Bootstrap -->
    <link href="../css/style.css" rel="stylesheet">
	<link href="../css/plugins/toastr/toastr.min.css" rel="stylesheet">
	
	<!-- Ladda style -->
    <link href="../css/plugins/ladda/ladda-themeless.min.css" rel="stylesheet">
	
</head>

<body class="top-navigation bg-muted">
	<div id="wrapper">
		<div id="page-wrapper" class="gary-bg">
			<div class="wrapper wrapper-content">
				<div class="row animated fadeInUp">
					<div class="container">
						
						<div class="col-md-8 col-md-offset-2 white-bg">
							<div class="ibox">
							  <div class="ibox-title">
								<h1><strong id="surv_title"><?php echo $description; ?></strong></h1>
							  </div>
							  <div class="ibox-content">
								<?php if($view == TRUE) { ?>
									<?php if($question != "end") { ?>
										<div id="surv_content">
											<div class="row">
												<h2 id="surv_question"><?php echo $question; ?></h2>
											</div>
											
											<div class="row" style="margin-top:15px;">
												<div class="col-md-12" id="surv_answers">
													<?php echo $answers; ?>
												</div>
											</div>
										</div>
										
										<input type="hidden" value="<?php echo $seq; ?>" id="surv_q_seq" />
										
									<?php } else { 
										echo '<div class="row"><h3>Merci pour vos commentaires.</h3>
											<a href="https://icoop.live/ic/">Retourner sur icoop</a>
										</div>';
									} ?>
									
								<?php } else { ?>
									<p>Ce compte n'exist pas.</p>
									<a href="https://icoop.live/ic/" class="btn btn-primary">Retourner sur icoop</a>
								<?php } ?>
							  </div>
							  
							  <?php if(($view == TRUE) && ($question != "end")) { ?>
							  <div class="ibox-footer" id="surv_footer" style="margin-top:15px;">
								 <button class="ladda-button ladda-button-demo btn btn-primary" onclick="saveUserAnswers();" data-style="zoom-in">Valider</button>
							  </div>
							<?php } ?>
							</div>
						</div>
						
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Mainly scripts -->
	<script src="../js/plugins/fullcalendar/moment.min.js"></script>
    <script src="../js/jquery-2.1.1.js"></script>
    <script src="../js/bootstrap.min.js"></script>

	<!-- Custom and plugin javascript -->
    <script src="../js/inspinia.js"></script>
    <script src="../js/plugins/pace/pace.min.js"></script> 
	<script src="../js/plugins/toastr/toastr.min.js"></script>

	<!-- jquery UI -->
    <script src="../js/plugins/jquery-ui/jquery-ui.min.js"></script>
	
	<!-- jQuery UI custom -->
	<script src="../js/jquery-ui.custom.min.js"></script>

	<!-- iCheck -->
	<script src="../js/plugins/iCheck/icheck.min.js"></script>
	
	<!-- Ladda -->
    <script src="../js/plugins/ladda/spin.min.js"></script>
    <script src="../js/plugins/ladda/ladda.min.js"></script>
    <script src="../js/plugins/ladda/ladda.jquery.min.js"></script>
	
	<script type="text/javascript">
		var value;
		var id_contact = <?php echo $id_contact; ?>; 
		var id_agent = <?php echo $id_agent; ?>; 
		var id_template = <?php echo $template; ?>; 
		
		
		$('.i-checks').iCheck({
			checkboxClass: 'icheckbox_square-green',
			radioClass: 'iradio_square-green'
		});
	
		$('input').on('ifChecked', function(event){
			value = $(event.target).val();  
		});

		
		function saveUserAnswers() {
			var q_seq = document.getElementById('surv_q_seq').value;
			var l = $( '.ladda-button-demo' ).ladda();
			
			l.ladda( 'start' );
			
			var resurl='../include/survey?elemid=save_user_servey&id_suranswer='+value+'&id_contact='+id_contact+'&id_agent='+id_agent+'&surtemplate_id='+id_template+'&q_seq='+q_seq;  
			var xhr = getXhr();  
			xhr.onreadystatechange = function(){
				if(xhr.readyState == 4 ){
					leselect = xhr.responseText;   
					var val = leselect.split('##');
					
					if(val[0] == 1){
						toastr.success('Réponse enregistrée.',{timeOut:20000})
						document.getElementById('surv_q_seq').value = val[2];
						
						if(val[1] != "end"){
							document.getElementById('surv_question').innerHTML = val[1];
							document.getElementById('surv_answers').innerHTML = val[3];
							
							$('.i-checks').iCheck({
								checkboxClass: 'icheckbox_square-green',
								radioClass: 'iradio_square-green'
							});
						
							$('input').on('ifChecked', function(event){
								value = $(event.target).val();  
							});
							
						} else { 
							$('#surv_footer').addClass('hide');
							document.getElementById('surv_content').innerHTML = '<div class="row"><h3>Merci pour vos commentaires.</h3><a href="https://icoop.live/ic/">Retourner sur icoop</a></div>';
						}
						
					} else {
						toastr.error("Echec d'enregistrement.",{timeOut:20000})
					}
				
					l.ladda('stop');
				}
			};

			xhr.open("GET",resurl,true);
			xhr.send(null);
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
	</script>

</body>

</html>
