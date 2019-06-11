<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");

  $agora = now();

if($_GET['placa']!="")
{


$sql = "SELECT
        	U.NAME           AS nome_registrou,
          UN.NAME          AS nome_notificou,
          UW.NAME          AS nome_guinchou,
          UC.NAME          AS nome_baixou,
        	S.NAME           AS logradouro,
        	PT.TYPE          AS tipo_vaga,
        	PT.TIME          AS tempo_permanencia,
        	PT.observation   AS tipo_desc,
        	P.NAME           AS vaga,
        	P.description    AS vaga_obs,
        	SP.licence_plate AS placa_veiculo,
        	SP.ID            AS id_registro,
        	SP.TIMESTAMP,
        	SP.notified_timestamp,
        	SP.closed_timestamp,
        	SP.winch_timestamp,
          SP.obs
        FROM
        			      sepud.eri_schedule_parking SP
        	     JOIN sepud.eri_parking 					P ON  P.ID = SP.id_parking
        	     JOIN sepud.eri_parking_type 		 PT ON PT.ID = P.id_parking_type
        	     JOIN sepud.streets 							S ON  S.ID = P.id_street
        	     JOIN sepud.users 								U ON  U.ID = SP.id_user
          LEFT JOIN sepud.users 							 UN ON UN.ID = SP.id_user_notified
          LEFT JOIN sepud.users 							 UW ON UW.ID = SP.id_user_winch
          LEFT JOIN sepud.users 							 UC ON UC.ID = SP.id_user_closed
        WHERE
        	  SP.licence_plate = '".$_GET['placa']."'
            --SP.licence_plate = 'AWP7828'
        ORDER BY SP.timestamp DESC";

  $res = pg_query($conn_neogrid,$sql)or die("Erro ".__LINE__);
  while($d = pg_fetch_assoc($res))
  {
    $dados[] = $d;
  }
}
  logger("Acesso","SERP - Impressão do relatório por placa de veículo");
?>
<!doctype html>
<html class="fixed">
	<head>

		<!-- Basic -->
		<meta charset="UTF-8">

		<title>SISTEMAS DIGITAIS</title>
		<meta name="keywords" content="GESTÃO CONTROLE CONVÊNIO" />
		<meta name="description" content="SISTEMA DE GESTÃO INTERNO">
		<meta name="author" content="">

		<!-- Mobile Metas -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

		<!-- Web Fonts  -->
		<link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">

		<!-- Vendor CSS -->
		<link rel="stylesheet" href="../assets/vendor/bootstrap/css/bootstrap.css" />

		<link rel="stylesheet" href="../assets/vendor/font-awesome/css/font-awesome.css" />
		<!--<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">-->
		<link rel="stylesheet" href="../assets/vendor/magnific-popup/magnific-popup.css" />
		<link rel="stylesheet" href="../assets/vendor/bootstrap-datepicker/css/datepicker3.css" />

		<!-- Specific Page Vendor CSS -->

		<link rel="stylesheet" href="../assets/vendor/jquery-ui/css/ui-lightness/jquery-ui-1.10.4.custom.css" />

		<link rel="stylesheet" href="../assets/vendor/bootstrap-multiselect/bootstrap-multiselect.css" />

		<link rel="stylesheet" href="../assets/vendor/morris/morris.css" />
		<link rel="stylesheet" href="../assets/vendor/pnotify/pnotify.custom.css" />





		<link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css"
			integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA=="
			crossorigin=""/>

			<script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js"
				integrity="sha512-nMMmRyTVoLYqjP9hrbed9S+FzjZHW5gY1TWCHA5ckwXZBadntCNs8kEqAWdrb9O7rxbCaA4lKTIWjDXZxflOcA=="
				crossorigin=""></script>

		<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">

		<!-- Theme CSS -->
		<link rel="stylesheet" href="../assets/stylesheets/theme.css" />

		<!-- Skin CSS -->
		<link rel="stylesheet" href="../assets/stylesheets/skins/default.css" />

		<!-- Theme Custom CSS -->
		<link rel="stylesheet" href="../assets/stylesheets/theme-custom.css">

		<!-- Head Libs -->
		<script src="../assets/vendor/modernizr/modernizr.js"></script>


		<link  href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />


		<style>
			.box_shadow
			{
			  -webkit-box-shadow: 10px 10px 56px -12px rgba(0,0,0,0.3);
			  -moz-box-shadow: 10px 10px 56px -12px rgba(0,0,0,0.3);
			  box-shadow: 10px 10px 56px -12px rgba(0,0,0,0.3);
			}
		</style>
	</head>
	<body>


  <!-- start: page -->
  <div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">
      <section class="box_shadow">
        <div class="row">
            <div class='col-md-12 text-center'>
                <table border='0' width='100%'>
                    <tr>
                        <td><img src="https://www.joinville.sc.gov.br/wp-content/uploads/2017/07/logoPMJ2x.png"></td>
                        <td><h4>Secretaria de Proteção Civil e Segurança Pública<br>SEPROT - Departamento de Trânsito</h4></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class='row'>
          <div class='col-md-12 text-center'>
              <h4><b>SERP - Sistema de Estacionamento Rotativo Público<br>Relatório de utilização de vagas</b></h4>
          </div>
        </div>
        <header class="panel-heading" style="height:60px">
          <h3 style="margin-top:0px"><small class='text-muted'><i>Placa do veículo:</small> <b><?=$_GET['placa'];?></b></i></h3>
        </header>

        <div class="panel-body">
          <div class="row">
            <div class="col-sm-12">
                      <?
                        if(isset($res) && pg_num_rows($res)){
                              echo "<table class='table table-condensed'>";

                              echo       "<thead><tr>
                                               <th nowrap>Registro nº</th>
                                               <th>Placa</th>
                                               <th nowrap>Vaga nº</th>
                                               <th>Tipo</th>
                                               <th>Logradouro</th>
                                               <th class='text-right'>Status</th>

                                          </tr></thead>";

                              echo "<tbody>";
                              for($i=0;$i<count($dados);$i++)
                              {

                                if($dados[$i]['notified_timestamp']){  $diff_notified = "+".floor((strtotime($dados[$i]['notified_timestamp']) - strtotime($dados[$i]['timestamp']))/60);}else{ $diff_notified = ""; }
                                   if($dados[$i]['winch_timestamp']){  $diff_winch    = "+".floor((strtotime($dados[$i]['winch_timestamp'])    - strtotime($dados[$i]['timestamp']))/60);}else{ $diff_winch = ""; }
                                  if($dados[$i]['closed_timestamp']){  $diff_closed   = "+".floor((strtotime($dados[$i]['closed_timestamp'])   - strtotime($dados[$i]['timestamp']))/60);}else{ $diff_closed = ""; }

                                  if($dados[$i]['notified_timestamp'] != ""){ $aux = explode(" ",$dados[$i]['notified_timestamp']); $notified_timestamp_hour = $aux[1];}else{ $notified_timestamp_hour = "";}
                                  if($dados[$i]['winch_timestamp']    != ""){ $aux = explode(" ",$dados[$i]['winch_timestamp']);    $winch_timestamp_hour    = $aux[1];}else{ $winch_timestamp_hour = "";}
                                  if($dados[$i]['closed_timestamp']   != ""){ $aux = explode(" ",$dados[$i]['closed_timestamp']);   $closed_timestamp_hour   = $aux[1];}else{ $closed_timestamp_hour = "";}

                                echo "<tr style='background-color:#E0E0E0'>";
                                  echo "<td class='text-muted'><small>".$dados[$i]['id_registro']."</small></td>";
                                  echo "<td><b>".$dados[$i]['placa_veiculo']."</b></td>";
                                  echo "<td><b>".$dados[$i]['vaga']."</b></td>";
                                  echo "<td nowrap>".$dados[$i]['tipo_vaga']." <sup><small>(".$dados[$i]['tempo_permanencia']." min.)</sup></small></td>";
                                  echo "<td>".$dados[$i]['logradouro']."</td>";

                                  if($dados[$i]['notified_timestamp'] != "")
                                  {
                                    if($dados[$i]['winch_timestamp'] != "")
                                    {
                                      echo "<td class='danger text-right'><b>Notificado e Guinchado</b></td>";
                                    }else {
                                      echo "<td class='danger text-right'><b>Notificado</b></td>";
                                    }
                                  }else {
                                      echo "<td class='primary text-right'><b>Baixado</b></td>";
                                  }


                                echo "</tr>";


                                echo "<tr><td colspan='2' class='text-right'><i>Ação</i></td>
                                          <td colspan='2'><i>Responsável pelo registro</i></td>
                                          <td><i>Data/Hora</i></td>
                                          <td><i>Tempo</i></td><tr>";

                                echo "<tr><td colspan='2' class='text-right'>Registro de entrada:</td>";
                                  echo "<td colspan='2'>".$dados[$i]['nome_registrou']."</td>";
                                  echo "<td>".formataData($dados[$i]['timestamp'],1)."</td>";
                                  echo "<td>&nbsp;</td>";
                                echo "</tr>";

                                if($dados[$i]['notified_timestamp']!="")
                                {
                                    echo "<tr><td colspan='2' class='text-right'>Notificação:</td>";
                                      echo "<td colspan='2'>".($dados[$i]['nome_notificou']!=""?$dados[$i]['nome_notificou']:"<span class='text-muted'>- - - -</span>")."</td>";
                                      echo "<td>".formataData($dados[$i]['notified_timestamp'],1)."</td>";
                                      echo "<td>".$diff_notified." min.</td>";
                                    echo "</tr>";
                                }

                                if($dados[$i]['winch_timestamp']!="")
                                {
                                      echo "<tr><td colspan='2' class='text-right'>Guinchamento:</td>";
                                        echo "<td colspan='2'>".($dados[$i]['nome_guinchou']!=""?$dados[$i]['nome_guinchou']:"<span class='text-muted'>- - - -</span>")."</td>";
                                        echo "<td>".formataData($dados[$i]['winch_timestamp'],1)."</td>";
                                        echo "<td>".$diff_winch." min.</td>";
                                      echo "</tr>";
                                }

                                if($dados[$i]['closed_timestamp']!="")
                                {
                                    echo "<tr><td colspan='2' class='text-right'>Baixa do registro:</td>";
                                      echo "<td colspan='2'>".($dados[$i]['nome_baixou']!=""?$dados[$i]['nome_baixou']:"<span class='text-muted'>- - - -</span>")."</td>";
                                      echo "<td>".formataData($dados[$i]['closed_timestamp'],1)."</td>";
                                      echo "<td>".$diff_closed." min.</td>";
                                    echo "</tr>";
                                }

                              }

                              echo "</tbody></table>";

                          }else {
                            echo "<div class='alert alert-warning text-center'>Nenhuma placa de veículo com estas características registrada no sistema.</div>";
                          }

                      ?>
                  </div>
              </div>
            </div>
            <div class='panel-footer' style="height:45px">
              <span class='row pull-right' style="margin-right:5px"><small><i>Relatório gerado em <b><?=$agora['dthm'];?></b></i></small></span>
            </div>
          </section>
          </div>
        </div>
</section>

<!-- Vendor -->
<script src="../assets/vendor/jquery/jquery.js"></script>

<script src="../assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>

<script src="../assets/vendor/bootstrap/js/bootstrap.js"></script>

<script src="../assets/vendor/nanoscroller/nanoscroller.js"></script>

<script src="../assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>

<script src="../assets/vendor/magnific-popup/magnific-popup.js"></script>

<script src="../assets/vendor/jquery-placeholder/jquery.placeholder.js"></script>

<!-- Specific Page Vendor -->

<script src="../assets/vendor/jquery-ui/js/jquery-ui-1.10.4.custom.js"></script>

<script src="../assets/vendor/jquery-ui-touch-punch/jquery.ui.touch-punch.js"></script>

<script src="../assets/vendor/jquery-appear/jquery.appear.js"></script>

<script src="../assets/vendor/bootstrap-multiselect/bootstrap-multiselect.js"></script>

<script src="../assets/vendor/jquery-easypiechart/jquery.easypiechart.js"></script>

<script src="../assets/vendor/flot/jquery.flot.js"></script>

<script src="../assets/vendor/flot-tooltip/jquery.flot.tooltip.js"></script>

<script src="../assets/vendor/flot/jquery.flot.pie.js"></script>

<script src="../assets/vendor/flot/jquery.flot.categories.js"></script>

<script src="../assets/vendor/flot/jquery.flot.resize.js"></script>

<script src="../assets/vendor/jquery-sparkline/jquery.sparkline.js"></script>

<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
<script src="../assets/vendor/gauge/gauge.js"></script>

<script src="../assets/vendor/snap-svg/snap.svg.js"></script>

<script src="../assets/vendor/liquid-meter/liquid.meter.js"></script>

<script src="../assets/vendor/jqvmap/jquery.vmap.js"></script>

<script src="../assets/vendor/jqvmap/data/jquery.vmap.sampledata.js"></script>

<script src="../assets/vendor/jqvmap/maps/jquery.vmap.world.js"></script>

<script src="../assets/vendor/jqvmap/maps/continents/jquery.vmap.africa.js"></script>

<script src="../assets/vendor/jqvmap/maps/continents/jquery.vmap.asia.js"></script>

<script src="../assets/vendor/jqvmap/maps/continents/jquery.vmap.australia.js"></script>

<script src="../assets/vendor/jqvmap/maps/continents/jquery.vmap.europe.js"></script>

<script src="../assets/vendor/jqvmap/maps/continents/jquery.vmap.north-america.js"></script>

<script src="../assets/vendor/jqvmap/maps/continents/jquery.vmap.south-america.js"></script>
<script src="../assets/vendor/pnotify/pnotify.custom.js"></script>
<script src="../assets/vendor/intercooler/intercooler-0.4.8.js"></script>
<script src="../assets/vendor/jquery-mockjax/jquery.mockjax.js"></script>
<script src="../assets/vendor/gauge/gauge.js"></script>

<script src="http://oss.maxcdn.com/jquery.form/3.50/jquery.form.min.js"></script>



<!-- Theme Base, Components and Settings -->
<script src="../assets/javascripts/theme.js"></script>

<!-- Theme Custom -->
<script src="../assets/javascripts/theme.custom.js"></script>

<!-- Theme Initialization Files -->
<script src="../assets/javascripts/theme.init.js"></script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>


</body>
</html>
<script>
window.print();
$(".loading").click(function(event){ $(this).addClass("disabled").html("<i class=\"fa fa-spinner fa-spin\"></i> <small>Aguarde</small>");});
//$("#bt_refresh").click(function(){ $("#bt_refresh_icon").addClass('fa-spin'); $("#wrap").load("erg/rel_placa.php");});
</script>
