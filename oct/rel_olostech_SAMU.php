<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");

  $agora = now();

  if(isset($_POST['filtro_data']))
  {
    $filtro_data = mkt2date(date2mkt($_POST['filtro_data']));
  }else {
    $filtro_data = now();
  }

  logger("Acesso","SAUDE - Relatório de contagem de atendimento por funcionário, período: ".$filtro_data['mes_txt']."/".$filtro_data['ano']);

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

<section role="main" class="content-body">
  <header class="page-header">
    <h2>Relatório Olostech</h2>
    <div class="right-wrapper pull-right" style='margin-right:15px;'>
      <ol class="breadcrumbs">
        <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
        <li><a href="oct/index.php">Sistema</a></li>
        <li><span class='text-muted'>Relatório Olostech</span></li>
      </ol>
      <!--<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>-->
    </div>
  </header>



								<section class="panel box_shadow">
  									<header class="panel-heading" style="height:70px">
                      <div class="panel-actions" style="margin-top:5px">
                        <button type="button" class="btn btn-primary"  data-toggle='modal' data-target='#modalFiltro'><i class='fa fa-search'></i> Pesquisar</button>
                        <button type="button" class="btn btn-info" id="bt_print"><i class='fa fa-print'></i> Imprimir</button>
                      </div>
                    </header>
  									<div class="panel-body">
                        <div class='text-right'>
                            <small>Relatório para inserção de dados no sistema Olostech</small>
                            <h4 style="margin-top:0px">Contagem de atendimentos por motorista socorrista</h4>
                        </div>

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
                </section>
</section>
<!-- Modal FILTROS -->
<div class="modal fade"  id="modalFiltro" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Filtros de pesquisa:</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-20px">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="form_filtro" action="oct/rel_olostech_SAMU.php" method="post">
      <div class="modal-body">
        <div class="row" style="margin-bottom:10px">
            <div class="form-group">
                <label class="col-md-3 control-label" for="filtro_data">Perído:</label>
                <div class="col-md-8">
                  <select id="filtro_data" name="filtro_data" class="form-control">

                     <?
                      for($a = 2019; $a <= $agora['ano']; $a++)
                      {
                          echo "<optgroup label='".$a."'>";

                            if($a == $agora['ano']){ $mes_ate = date('n'); }
                            else                   { $mes_ate = 12;        }

                            for($m = 1; $m <= $mes_ate; $m++)
                            {
                                if($a == $agora['ano'] && $m == $mes_ate){ $sel = "selected"; }

                                echo  "<option value='01/".$m."/".$a." 00:00:00' ".$sel.">".$meses[$m]['longo']."/".$a."</option>";
                            }
                          echo "</optgroup>";

                      }
                     ?>
                  </select>
                </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
        <button type="button" class="btn btn-primary" id="bt_submit">Filtrar</button>
      </div>
      </form>
    </div>
  </div>
</div>

<script>
$("#bt_print").click(function(){
	var vw = window.open('oct/rel_olostech_SAMU_print.php?filtro_data=<?=$filtro_data['data'];?>',
									     'popup',
								 	     'width=800, height=600, top=10, left=10, scrollbars=no,location=no,status=no');
});


$("#bt_submit").click(function(){
    $('#modalFiltro').modal('hide');
    $('body').removeClass('modal-open');
    $('.modal-backdrop').remove();
    $("#form_filtro").submit();
});
$('.select2').select2();
$(".loading").click(function(){ $(this).html("<i class=\"fa fa-spinner fa-spin\"></i> Aguarde");});
$(".loading2").click(function(){ $(this).addClass("disabled").html("<i class=\"fa fa-spinner fa-spin\"></i>");});
</script>
