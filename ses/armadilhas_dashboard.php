<?
  session_start();
  error_reporting(0);
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");


  if($_POST['filtro_data']!="")
  {
    $agora = mkt2date(date2mkt($_POST['filtro_data']));
  }else{
    $agora = now();
  }

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


  logger("Visualização","SAS - BEV", "Dashboard armadilhas");


  $sql   = "SELECT count(*) as qtd, dia_semana, agente FROM {$schema}ses_trap GROUP BY dia_semana, agente ORDER BY agente ASC";


  $res = pg_query($sql)or die("Error ".__LINE__."<br>Query: {$sql}");
  while($d = pg_fetch_assoc($res)){
      $agentes[$d['agente']][$d['dia_semana']]  = $d['qtd'];
      $total_agentes[$d['agente']]  += $d['qtd'];
      $total_dias_semana[$d['dia_semana']]  += $d['qtd'];
      $total_armadilhas += $d['qtd'];

  }


  $sql2 =  "SELECT count(*) as qtd, bairro FROM {$schema}ses_trap GROUP BY bairro";
  $res = pg_query($sql2)or die("Error ".__LINE__."<br>Query: {$sql2}");
  while($d = pg_fetch_assoc($res)){
      $bairros[$d['bairro']]  = $d['qtd'];
      $bairros_total         += $d['qtd'];
  }




?>

</style>
<section role="main" class="content-body">
  <header class="page-header">
    <h2>SES-PNCD - Dashboard - Armadilhas
    </h2>
    <div class="right-wrapper pull-right" style='margin-right:15px;'>
      <ol class="breadcrumbs">
        <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
        <li><span class='text-muted'>SAS-BEV</span></li>
        <li><span class='#'>Dashboard</span></li>
      </ol>
    </div>
  </header>

  <section class="panel box_shadow">
    <header class="panel-heading" style="height:70px">
    <!--  Mês de referência: <b><?=$agora['mes_txt']."/".$agora['ano'];?></b> -->
      <div class="panel-actions" style="margin-top:5px">
<!--
        <button type="button" class="mb-xs mt-xs mr-xs btn btn-primary" data-toggle="modal" data-target="#modal_filtro">
          <i class="fa fa-search"></i> Filtros</button>
        </button>
-->
    </header>
    <div class="panel-body">

      <div class="row">
        <div class="col-sm-6">
            <?
              echo "<table class='table table-striped table-condensed'>";
              echo "<thead><tr><th class='text-center'>Agente</th>
                               <th class='text-center'>Seg</th>
                               <th class='text-center'>Ter</th>
                               <th class='text-center'>Qua</th>
                               <th class='text-center'>Qui</th>
                               <th class='text-center'>Sex</th>
                               <th class='text-center'>Total</th></tr>";
              foreach ($agentes as $agente => $qtd) {
                echo "<tr class='text-center'>";
                  echo "<td>".$agente."</td>";
                  echo "<td>".$qtd['SEGUNDA-FEIRA']."</td>";
                  echo "<td>".$qtd['TERÇA-FEIRA']."</td>";
                  echo "<td>".$qtd['QUARTA-FEIRA']."</td>";
                  echo "<td>".$qtd['QUINTA-FEIRA']."</td>";
                  echo "<td>".$qtd['SEXTA-FEIRA']."</td>";
                  echo "<td>".$total_agentes[$agente]."</td>";
                echo  "</tr>";
              }
              echo "<tr class='text-center'>";
                echo "<td>Total:</td>";
                echo "<td>".$total_dias_semana['SEGUNDA-FEIRA']."</td>";
                echo "<td>".$total_dias_semana['TERÇA-FEIRA']."</td>";
                echo "<td>".$total_dias_semana['QUARTA-FEIRA']."</td>";
                echo "<td>".$total_dias_semana['QUINTA-FEIRA']."</td>";
                echo "<td>".$total_dias_semana['SEXTA-FEIRA']."</td>";
                echo "<td>".number_format($total_armadilhas,0,'','.')."</td>";

              echo "</tr>";

              echo "</table>";
            ?>
        </div>
      <div class="col-sm-6">
          <?
            echo "<table class='table table-striped table-condensed'>";
            echo "<thead><tr><th>Bairro</th><th class='text-center'>Quantidade</th></tr>";
            foreach ($bairros as $bairro => $qtd) {
              echo "<tr>";
                echo "<td>".$bairro."</td>";
                echo "<td class='text-center'>".$qtd."</td>";
              echo  "</tr>";
            }
            echo "<tr><td>Total:</td><td>{$bairros_total}</td>";
            echo "</table>";
          ?>
      </div>
      </div>





    </div>
  </section>
</section>


<div class="modal fade" id="modal_filtro" tabindex="-1" role="dialog" aria-labelledby="modal_filtro" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Filtros de pesquisa</h5>
      </div>
      <form id="filtro" name="filtro" method="post" action="../sas/dashboard.php">
      <div class="modal-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                      <label for="filtro_data">Período:</label>
                          <select id="filtro_data" name="filtro_data" class="form-control">

                             <?
                             if(isset($agora['ano']))
                             {
                                    for($a = 2020; $a <= $agora['ano']; $a++)
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
                              }
                             ?>
                          </select>
                    </div>
                </div>
              </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        <button id="bt_submit" type="submit" class="btn btn-primary"   data-dismiss="modal">Filtrar</button>
      </div>
     </form>
    </div>
  </div>
</div>
<script>
$('#bt_submit').click(function(e) {
    e.preventDefault();
     $("#modal_filtro").removeClass("in");
     $(".modal-backdrop").remove();
     $('body').removeClass('modal-open');
     $('body').css('padding-right', '');
     $("#modal_filtro").hide();

     $("#filtro").submit();
    return false;
});
</script>
