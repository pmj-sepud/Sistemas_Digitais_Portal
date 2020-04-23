<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");

  $agora        = now();
  $id_workshift = $_GET['id_workshift'];
  $id_oc        = $_GET['id_oc'];
  logger("Acesso","OCT - Impressão da ocorrência", "Oc. num: ".$id_oc.", turno: ".$id_workshift);
  $num_oc = ($id_workshift!=""?number_format($id_oc,0,'','.')."-".$id_workshift:number_format($id_oc,0,'','.'));

  if($id_oc != ""){
    $sql   = "SELECT
                  F.plate, F.brand, F.model, F.nickname as fleet_nickname,
                  UG.name  as user_name_garrison, UG.nickname as nickname_garrison, UG.registration as registration_garrison,
                  G.closed as closed_garrison, G.name as name_garrison,
                  W.opened as workshift_opened,
                  W.closed as workshift_closed,
                  W.workshift_group as workshift_period,
                  W.status as workshift_status,
                  U.NAME as user_name,
                  C.name as company_name,
                  T.name as event_name,
                  S.name as street_name,
                  EV.*

              FROM
                        ".$schema."oct_events     EV
                   JOIN ".$schema."users          U  ON U.ID = EV.id_user
                   JOIN ".$schema."company        C  ON C.id = U.id_company
                   JOIN ".$schema."oct_event_type T  ON T.id = EV.id_event_type
              LEFT JOIN ".$schema."streets        S  ON S.id = EV.id_street
              LEFT JOIN ".$schema."oct_workshift  W  ON W.id = EV.id_workshift
              LEFT JOIN ".$schema."oct_garrison   G  ON G.ID = EV.id_garrison
              LEFT JOIN ".$schema."oct_fleet      F  ON F.id = G.id_fleet
              LEFT JOIN ".$schema."oct_rel_garrison_persona GP ON GP.id_garrison = EV.id_garrison AND GP.type = 'Motorista'
              LEFT JOIN ".$schema."users          UG ON UG.id = GP.id_user
              WHERE EV.ID =  '".$id_oc."'";

    $res   = pg_query($conn_neogrid,$sql)or die("Error ".__LINE__);
    $dados = pg_fetch_assoc($res);

/*
    $sql = "SELECT * FROM ".$schema."oct_rel_events_event_conditions WHERE id_events = '".$id."'";
    $res   = pg_query($conn_neogrid,$sql)or die("Error ".__LINE__);
    while($d = pg_fetch_assoc($res))
    {
      $dadosCondicoes[] = $d['id_event_conditions'];
    }

    $sql        = "SELECT * FROM ".$schema."oct_workshift WHERE id_company = ".$_SESSION['id_company']." AND status = 'aberto'";
    $resTurno   = pg_query($sql)or die("Erro ".__LINE__);
    if(pg_num_rows($resTurno))
    {
        $turno_aberto = pg_fetch_assoc($resTurno);
    }
*/
  }else {
    echo "<h4>Erro na passagem dos parametros, não será possível realizar a impressão.</h4>";
    exit();
  }

?>
<!doctype html>
<html class="fixed">
	<head>

		<!-- Basic -->
		<meta charset="UTF-8">

		<title><?=$_SESSION['company_acron']."_ROTSS_Ocorrência_".$num_oc;?></title>
		<meta name="keywords" content="">
		<meta name="description" content="">
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
      body{
        background: white;
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
                                <td><h4>Secretaria de Proteção Civil e Segurança Pública</h4><h3 style="margin-top:-10px"><b><?=$dados['company_name'];?></b></h3></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class='row'>
                  <div class='col-md-12 text-center'>
                      <h3><b><i>Ocorrência nº <?=$num_oc;?></i></b></h3>
                  </div>
                </div>
                <div class='row'>
                  <div class='col-md-12'>
                      <table class='table table-condensed'>
                        <tr><td><small class='text-muted'>Abertura:</small><br><b><?=formatadata($dados['date'],1);?></b></td>
                            <td><small class='text-muted'>Fechamento:</small><br><?=formatadata($dados['closure'],1);?></td>
                            <td><small class='text-muted'>Status:</small><br><?=$dados['status'];?></td>
                        </tr>
                        <tr>
                          <td colspan='2'><small class='text-muted'>Responsável pela abertura:</small><br><?=$dados['user_name'];?></td>
                          <td colspan='2'><small class='text-muted'>Setor:</small><br><?=$dados['company_name'];?></td>
                        </tr>
                        <tr>
                          <td colspan='4'><small class='text-muted'>Logradouro:</small><br><?=$dados['street_name'];?>, nº <?=$dados['street_number'];?></td>
                        </tr>
                        <tr>
                          <td colspan='4'><small class='text-muted'>Tipo:</small><br><b><?=$dados['event_name'];?></b></td>
                        </tr>
                        <tr>
                          <td colspan='4'><small class='text-muted'>Descrição:</small><br><?=nl2br($dados['description']);?></td>
                        </tr>

                        <tr>
                          <td colspan="4">
                            <small class='text-muted'>Fotos da ocorrência:</small><br>
                                <?
                                      $sql = "SELECT * FROM ".$schema."oct_rel_events_images WHERE id_events = '".$id_oc."' ORDER BY id DESC";
                                      $res = pg_query($sql)or die("Erro ".__LINE__."SQL: ".$sql);
                                      if(pg_num_rows($res))
                                      {
                                        while($f = pg_fetch_assoc($res))
                                        {
                                          list($width, $height) = getimagesize("../oct/uploads/".$id_oc."/".$f['image']);
                                          if ($width > $height) {
                                              $arqs_imgs['Landscape'][] = "../oct/uploads/".$id_oc."/".$f['image'];
                                          }else{
                                              $arqs_imgs['Portrait'][] = "../oct/uploads/".$id_oc."/".$f['image'];
                                          }
                                        }
                                          if(isset($arqs_imgs['Landscape']))
                                          {
                                            for($i=0;$i<count($arqs_imgs['Landscape']);$i++)
                                            {
                                              echo  "<img class='img-rounded img-thumbnail' src='".$arqs_imgs['Landscape'][$i]."' style='width:350px' />";
                                            }
                                          }
                                      ?>
                            </td>
                          </tr>
                          <tr>
                            <td colspan='4'>
                                      <?
                                          if(isset($arqs_imgs['Portrait']))
                                          {

                                            for($i=0;$i<count($arqs_imgs['Portrait']);$i++)
                                            {
                                              echo  "<img class='img-rounded img-thumbnail' src='".$arqs_imgs['Portrait'][$i]."' style='width:200px' />";
                                            }
                                          }
                                      }else {
                                        echo "<div class='text-center' style='margin-bottom:20px'><i class='fa fa-camera fa-5x text-muted'></i><br><small class='text-muted'>Nenhuma foto associada.</small></div>";
                                      }
                                ?>
                              </td>
                          </tr>
                        </table>
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
<? if($_GET['print']!="false"){ echo "window.print();"; } ?>
$(".loading").click(function(event){ $(this).addClass("disabled").html("<i class=\"fa fa-spinner fa-spin\"></i> <small>Aguarde</small>");});
//$("#bt_refresh").click(function(){ $("#bt_refresh_icon").addClass('fa-spin'); $("#wrap").load("erg/rel_placa.php");});
</script>
