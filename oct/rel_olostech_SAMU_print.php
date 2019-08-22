<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");

  $agora = now();

  if(isset($_GET['filtro_data']))
  {
    $filtro_data = mkt2date(date2mkt($_GET['filtro_data']));
  }else {
    $filtro_data = now();
  }

  logger("Impressão","SAUDE - Relatório de contagem de atendimento por funcionário, período: ".$filtro_data['mes_txt']."/".$filtro_data['ano']);

  $meses[1]['curto'] = "Jan";
  $meses[2]['curto'] = "Fev";
  $meses[3]['curto'] = "Mar";
  $meses[4]['curto'] = "Abr";
  $meses[5]['curto'] = "Mai";
  $meses[6]['curto'] = "Jun";
  $meses[7]['curto'] = "Jul";
  $meses[8]['curto'] = "Ago";
  $meses[9]['curto'] = "Set";
  $meses[10]['curto'] = "Out";
  $meses[11]['curto'] = "Nov";
  $meses[12]['curto'] = "Dez";


  $meses[1]['longo'] = "Janeiro";
  $meses[2]['longo'] = "Fevereiro";
  $meses[3]['longo'] = "Março";
  $meses[4]['longo'] = "Abril";
  $meses[5]['longo'] = "Maio";
  $meses[6]['longo'] = "Junho";
  $meses[7]['longo'] = "Julho";
  $meses[8]['longo'] = "Agosto";
  $meses[9]['longo'] = "Setembro";
  $meses[10]['longo'] = "Outubro";
  $meses[11]['longo'] = "Novembro";
  $meses[12]['longo'] = "Dezembro";
?>
<!doctype html>
<html class="fixed">
	<head>

		<!-- Basic -->
		<meta charset="UTF-8">

		<title><?=$_SESSION['company_acron']."_ROTSS_Relatorio_total_de_atendimentos_".$filtro_data['mes_txt']."_".$filtro_data['ano'];?></title>
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
                        <td><h4>Secretaria Municipal da Saúde</h4><h3 style="margin-top:-10px"><b><?=$turno['company_name'];?></b></h3></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class='row'>
          <div class='col-md-12 text-center'>
              <h3><b><i>Relatório contagem de atendimentos por motorista socorrista</i></b></h3>
          </div>
        </div>
        <div class="panel-body">
          <?
        //  echo "Mês de referencia: <b>".$agora['mes_txt']."/".$agora['ano']."</b>";
          $sql = "SELECT
                    P.id_hospital, H.name as hospital,
                    U.id as id_user, U.name, U.nickname, U.registration,
                    E.date::date
                  FROM
                    sepud.oct_rel_events_providence P
                  JOIN sepud.users U ON U.id = P.id_user_resp
                  JOIN sepud.oct_events E ON E.id = P.id_event AND E.date BETWEEN '".$filtro_data['ano']."-".$filtro_data['mes']."-01 00:00:00' AND '".$filtro_data['ano']."-".$filtro_data['mes']."-".$filtro_data['ultimo_dia']." 23:59:59'
                  LEFT JOIN sepud.hospital H ON H.id = P.id_hospital
                  WHERE
                    P.id_user_resp IS NOT NULL
                    AND P.id_event IN (SELECT id FROM sepud.oct_events WHERE id_company = '".$_SESSION['id_company']."')
                  ORDER BY U.name ASC";

           $res = pg_query($sql)or die("SQL Error ".__LINE__);
           while($d = pg_fetch_assoc($res))
           {
              unset($local);
              $local = ($d['hospital']!=""?$d['hospital']:"Atendimento local");
              $pessoas[$d['name']][$d['date']]++;
              $total_pessoa[$d['name']]++;
              $total_dia[$d['date']]++;
              $total_hospital[$local]++;
              $total++;
           }
if($total)
{




           if(isset($total_hospital)){ ksort($total_hospital);}

           //Total por dia e por pessoa
           //print_r_pre($total_dia);
           echo "<div class='row'>";
           echo "<div class='col-sm-12'>";
           echo "<table class='table table-condensed'>";
           echo "<thead><tr><th colspan='33'><h4>Total de atendimentos por pessoa</h4></th></tr>";
           echo "<tr><td class='text-center'><b>".$filtro_data['mes_txt']."/".$filtro_data['ano']."</b></td>";
           echo "<td class='info text-center'>Total</td>";
            for($i=1;$i<=$filtro_data['ultimo_dia'];$i++)
            {
              echo "<td class='text-muted'>".str_pad($i,2,"0",STR_PAD_LEFT)."</td>";
            }

           echo "</tr>";
           echo "</thead>";
           echo "<tbody>";
           if(isset($pessoas))
           {
                foreach ($pessoas as $pessoa => $datas) {
                    echo "<tr>";
                      echo "<td>".$pessoa."</td>";
                      echo "<td class='text-center' style='background:#d5dcde;'>".$total_pessoa[$pessoa]."</td>";
                      for($i=1;$i<=$filtro_data['ultimo_dia'];$i++)
                      {
                        unset($var_total_dia);
                        $var_total_dia = $datas[$filtro_data['ano']."-".$filtro_data['mes']."-".str_pad($i,2,"0",STR_PAD_LEFT)];
                        echo "<td>".($var_total_dia?$var_total_dia:"<small class='' style='color:#DDDDDD'>0</small>")."</td>";
                      }

                    echo "</tr>";
                }
           }
           echo "<tr><td class='text-right'><small class='text-muted'><i>Total:</i></small></td>";
           echo "<td class='text-center info'><b>".$total."</b></td>";
               for($i=1;$i<=$filtro_data['ultimo_dia'];$i++)
               {
                 unset($var_total_dia);
                 $var_total_dia = $total_dia[$filtro_data['ano']."-".$filtro_data['mes']."-".str_pad($i,2,"0",STR_PAD_LEFT)];
                 echo "<td>".($var_total_dia?$var_total_dia:"<small class='' style='color:#DDDDDD'>0</small>")."</td>";
               }
           echo "</tr>";
           echo "</tbody>";
           echo "</table>";
           echo "</div></div>";


           //Total de atendimentos locais e encaminhamento a hospital
           echo "<div class='row'>";

                     echo "<div class='col-sm-6'>";
                     echo "<table class='table table-condensed'>";
                     echo "<thead><tr><th colspan='2'><h4>Total de encaminhamentos aos hospitais</h4></th></tr></thead>";
                     echo "<tbody>";
                     if(isset($total_hospital))
                     {
                         foreach ($total_hospital as $hospital => $qtd)
                         {
                           if(strpos($hospital,"Hospital")!==false || strpos($hospital,"Maternidade")!==false)
                           {
                            echo "<tr>";
                              echo "<td>".$hospital."</td><td class='text-center'>".$qtd."</td>";
                            echo "</tr>";
                            $total_por_hospital += $qtd;
                           }
                         }
                     }
                     echo "<tr>";
                       echo "<td class='text-right'><small class='text-muted'><i>Total:</i></small></td><td width='2px' class='text-center'>".$total_por_hospital."</td>";
                     echo "</tr>";
                     echo "</tbody>";
                     echo "</table>";
                     echo "</div>";

                     echo "<div class='col-sm-6'>";
                     echo "<table class='table table-condensed'>";
                     echo "<thead><tr><th colspan='2'><h4>Total de encaminhamentos aos P.A.</h4></th></tr></thead>";
                     echo "<tbody>";
                     if(isset($total_hospital))
                     {
                         foreach ($total_hospital as $hospital => $qtd)
                         {
                            if(strpos($hospital,"PA ")!==false)
                            {
                                echo "<tr>";
                                  echo "<td>".$hospital."</td><td class='text-center'>".$qtd."</td>";
                                echo "</tr>";
                                $total_por_pa += $qtd;
                            }
                         }
                    }
                     echo "<tr>";
                       echo "<td class='text-right'><small class='text-muted'><i>Total:</i></small></td><td width='2px' class='text-center'>".$total_por_pa."</td>";
                     echo "</tr>";
                     echo "</tbody>";
                     echo "</table>";

                     echo "<table class='table table-condensed'>";
                     echo "<thead><tr><th colspan='2'><h4>Total de atendimentos sem encaminhamento</h4></th></tr></thead>";
                     echo "<tbody>";
                     if(isset($total_hospital))
                     {
                         foreach ($total_hospital as $hospital => $qtd)
                         {
                            if(strpos($hospital," local")!==false)
                            {
                                echo "<tr>";
                                  echo "<td>".$hospital."</td><td width='2px' class='text-center'>".$qtd."</td>";
                                echo "</tr>";
                            }
                         }
                     }
                     echo "</tbody>";
                     echo "</table>";

                     echo "</div>";

           echo "</div>";
}else{


           echo "<div class='row'>
                   <div class='col-sm-12'>
                     <div class='alert alert-warning text-center'>Nenhuma informação registrada em <b>".$filtro_data['mes_txt']."/".$filtro_data['ano']."</b></div>
                   </div>
                 </div>";
}

?>
        </div>
        <div class="panel-footer text-right">
            <small class='text-muted'><i>Relatório impresso por <?="<b>".$_SESSION['name']."</b> em <b>".$agora['dthm']."</b>";?></i></small>
        </div>
      </section>
    </div>
</div>


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
<? if($_GET['print']!="false"){ ?>
window.print();
<? } ?>
$(".loading").click(function(event){ $(this).addClass("disabled").html("<i class=\"fa fa-spinner fa-spin\"></i> <small>Aguarde</small>");});
//$("#bt_refresh").click(function(){ $("#bt_refresh_icon").addClass('fa-spin'); $("#wrap").load("erg/rel_placa.php");});
</script>
