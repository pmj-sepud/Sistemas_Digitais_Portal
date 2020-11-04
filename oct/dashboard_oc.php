<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");


  $hoje = now();

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

  $sql = "SELECT count(*) as qtd, date_part('day', date) as dia
          FROM  {$schema}oct_events
          WHERE date BETWEEN '{$hoje['ano']}-{$hoje['mes']}-01 00:00:00' AND '{$hoje['ano']}-{$hoje['mes']}-{$hoje['ultimo_dia']} 23:59:59' AND
                id_company = '{$_SESSION['id_company']}'
          GROUP BY date_part('day', date)
          ORDER BY date_part('day', date) ASC";

  $res = pg_query($sql)or die("SQL error ".__LINE__."<br>Query: ".$sql);
  while($d = pg_fetch_assoc($res)){
    if($d['dia']!=$hoje['dia']){ $oc_acum['outros_dias'] += $d['qtd']; }
                           else{        $oc_acum['hoje'] += $d['qtd']; }
    $oc_acum['total'] += $d['qtd'];
  }
?>
<style>

</style>
<section role="main" class="content-body">
  <header class="page-header">
    <h2>Dashboard - Ocorrências</h2>
    <div class="right-wrapper pull-right" style='margin-right:15px;'>
      <ol class="breadcrumbs">
        <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
        <li><a href="oct/index.php">Sistema</a></li>
        <li><span class='text-muted'>Dashboard - Ocorrências</span></li>
      </ol>
    </div>
  </header>


  <div class="col-md-12">
        <section class="panel box_shadow">
              <header class="panel-heading" style="height:70px">
                <?
                    echo "<h5>".$_SESSION['company_acron']." - ".$_SESSION['company_name']."</h5>";
                ?>
              <div class="panel-actions">
                <button type="button" class="mb-xs mt-xs mr-xs btn btn-primary" data-toggle="modal" data-target="#modal_filtro">
                  <i class="fa fa-search"></i> Filtros</button>
                </button>
              </div>
              </header>
              <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-4">
                          <?
                            $sql = "SELECT
                                      (SELECT MIN(date) FROM {$schema}oct_events WHERE id_company = '{$_SESSION['id_company']}' AND active = 't') as data_oc_aberta_mais_antiga,
                                      (SELECT count(*)  FROM {$schema}oct_events WHERE id_company = '{$_SESSION['id_company']}' AND active = 't') as total_oc_ativas,
                                      (SELECT count(*)  FROM {$schema}oct_events WHERE date BETWEEN '".$hoje['datasrv']." 00:00:00' AND '".$hoje['datasrv']." 23:59:59' AND id_company = '{$_SESSION['id_company']}' AND active = 'f') oc_fechadas_hoje,
                                      (SELECT count(*)  FROM {$schema}oct_events WHERE date BETWEEN '".$hoje['datasrv']." 00:00:00' AND '".$hoje['datasrv']." 23:59:59' AND id_company = '{$_SESSION['id_company']}' AND active = 't') oc_abertas_hoje";
                              $res = pg_query($sql) or die("SQL error ".__LINE__."<br>Query: ".$sql);
                              $stat = pg_fetch_assoc($res);


                          ?>
                          <section class="panel panel-featured-left panel-featured-secondary">
                                            <div class="panel-body">
                                              <div class="widget-summary">
                                                <div class="widget-summary-col widget-summary-col-icon">
                                                  <div class="summary-icon bg-secondary">
                                                    <i class="fa fa-calendar"></i>
                                                  </div>
                                                </div>
                                                <div class="widget-summary-col">
                                                  <div class="summary">
                                                    <h5 class="title">Ocorrência mais antiga<br>em aberto:</h5>
                                                    <div style="font-size:18px;margin-top:15px">
                                                        <?
                                                            $datahora = explode(" ",formataData($stat['data_oc_aberta_mais_antiga'], 1));
                                                            echo '<b>'.$datahora[0].'</b> <sup>'.$datahora[1].'</sup>';
                                                            echo "<br><small>Tempo decorrido: </small><small class='text-danger'><b>".humanTiming($stat['data_oc_aberta_mais_antiga']).".</b></small>";
                                                        ?><br>&nbsp;
                                                    </div>
                                                  </div>

                                                </div>
                                              </div>
                                            </div>
                                          </section>

                        </div>

                        <div class="col-sm-4">

                          <section class="panel panel-featured-left panel-featured-quartenary">
                      <div class="panel-body">
                        <div class="widget-summary">
                          <div class="widget-summary-col widget-summary-col-icon">
                            <div class="summary-icon bg-quartenary">
                              <i class="fa fa-line-chart"></i>
                            </div>
                          </div>
                          <div class="widget-summary-col">
                            <div class="summary">
                              <h5 class="title">Ocorrências anteriores<br>ainda aberta:</h5>
                              <div style="font-size:18px;margin-top:15px">
                                <b><?=$stat['total_oc_ativas']-$stat['oc_abertas_hoje'];?></b> <small>Ocorrências de datas<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;anteriores ainda em aberta</small><br><br>&nbsp;
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </section>

                        </div>

                    <div class='col-sm-4'>
                                <section class="panel panel-featured-left panel-featured-tertiary">
                            <div class="panel-body">
                              <div class="widget-summary">
                                <div class="widget-summary-col widget-summary-col-icon">
                                  <div class="summary-icon bg-tertiary">
                                    <i class="fa fa-bar-chart"></i>
                                  </div>
                                </div>
                                <div class="widget-summary-col">
                                  <div class="summary">
                                    <h5 class="title">Estatísticas para o dia atual:</h5>
                                    <div style="font-size:18px;margin-top:15px">
                                      <b><?=$stat['oc_abertas_hoje'];?></b>  <small>Ocorrências em aberto.</small><br>
                                      <b><?=$stat['oc_fechadas_hoje'];?></b>  <small>Ocorrências fechadas.</small><br>
                                      <small class='text-muted'><sup>Data de referência: <b><?=$hoje['data'];?></b></sup></small>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </section>
                    </div>
</div>
<div class="row">
                    <div class="col-sm-4">

                      <section class="panel panel-featured-left panel-featured-primary">
                    <div class="panel-body">
                    <div class="widget-summary">
                      <div class="widget-summary-col widget-summary-col-icon">
                        <div class="summary-icon bg-primary">
                          <i class="fa fa-area-chart"></i>
                        </div>
                      </div>
                      <div class="widget-summary-col">
                        <div class="summary">
                          <h5 class="title">Evolução:</h5>
                          <div style="font-size:18px;margin-top:15px">
                            <b><?=$oc_acum['hoje'];?></b> <small>Ocorrências geradas hoje</small><br>
                            <b><?=$oc_acum['total'];?></b> <small>Total de ocorrências<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;no mês atual</small><br>
                            <small class='text-muted'><sup>Mês de referência: <b><?="{$hoje['mes']}/{$hoje['ano']}";?></b></sup></small>
                          </div>
                        </div>
                      </div>
                    </div>
                    </div>
                    </section>

                    </div>

                  </div><!--<div class='row'>-->


                    <div class="row" style="margin-top:10px">
                        <div class="col-sm-12"><hr>
                        </div></div>

        <div class="row">
          <div class="col-md-12">
              <div id="graf_evo_mensal" style="width:100%; height:300px;margin-top:10px"></div>

<?
for($dia=1;$dia<=$agora['ultimo_dia'];$dia++){
  //$evo_mensal_legenda[] = $dia;
  $evo_mensal_dados[$dia]=0;
}
  $sql = "SELECT
		          count(*) As qtd,
		          date_part('day', date) as dia
	        FROM {$schema}oct_events
          WHERE  date BETWEEN '{$agora['ano']}-{$agora['mes']}-01 00:00:00' AND '{$agora['ano']}-{$agora['mes']}-{$agora['ultimo_dia']} 23:59:59'
             AND id_company = '{$_SESSION['id_company']}'
          GROUP BY date_part('day', date)
          ORDER BY date_part('day', date) ASC";
  $res  = pg_query($sql)or die("Error ".__LINE__."<br>Query: ".$sql);
  while($d = pg_fetch_assoc($res)){ $evo_mensal_dados[$d['dia']]=$d['qtd'];}
  for($i=1;$i<=$agora['ultimo_dia'];$i++){ $dados[] = (int)$evo_mensal_dados[$i]; }
  $legenda = json_encode(array_keys($evo_mensal_dados));

?>
              <script>
              Highcharts.chart('graf_evo_mensal', {
      chart: {
          type: 'spline'
      },
      title: {
          text: 'Evolução diária - Quantidade de ocorrências geradas'
      },
      subtitle: {
          text: 'Data de referência: <?=$agora['mes_txt']."/".$agora['ano'];?>'
      },
      xAxis: {
          categories: <?=$legenda;?>
      },
      yAxis: {
          title: {
              text: 'Quantidade'
          }
      },
      plotOptions: {
          line: {
              dataLabels: {
                  enabled: true
              },
              enableMouseTracking: false
          }
      },
      series: [{
          name: 'Ocorrências',
          data:  <?=json_encode($dados);?>
        }],
      credits: {
          enabled: false
      },
  });
              </script>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
                Por responsável que abriu a ocorrência:
                <br><small>Referência: <?=$hoje['data'];?></small>
                <?
                    $sql = "SELECT U.name, count(*) as qtd FROM {$schema}oct_events E
                            JOIN {$schema}users U ON U.id = E.id_user
                            WHERE E.id_company = 3
                            AND date BETWEEN '".$hoje['datasrv']." 00:00:00' AND '".$hoje['datasrv']." 23:59:59'
                            GROUP BY U.name
                            ORDER BY count(*) DESC";
                    $dados_resp = return_query($sql);

                    echo "<table class='table table-striped'>";
                    echo "<thead><tr><th>Nome</th><th>Qtd.</th></tr></thead>";
                    echo "<tbody>";
                    for($i=0; $i<count($dados_resp);$i++)
                    {
                        echo "<tr>";
                          echo  "<td>".$dados_resp[$i]['name']."</td>";
                          echo  "<td>".$dados_resp[$i]['qtd']."</td>";
                        echo "</tr>";
                    }
                    echo "</tbody>";
                    echo "</table>";

  function return_query($sql)
  {
    $res = pg_query($sql)or die("Query error: ".$sql);
    while($d = pg_fetch_assoc($res)){ $ret[] = $d;}
    return $ret;
  }
                ?>
          </div>
        </div>



              </div>
        </section>
  </div>
</section>

<div class="modal fade" id="modal_filtro" tabindex="-1" role="dialog" aria-labelledby="modal_filtro" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Filtros de pesquisa</h5>
      </div>
      <form id="filtro" name="filtro" method="post" action="../oct/dashboard_oc.php">
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
<?
function humanTiming($data)
{

    $time = strtotime($data);
    $time = time() - $time;
    $time = ($time<1)? 1 : $time;
    $tokens = array (
        31536000 => 'ano',
        2592000 => 'mês',
        604800 => 'semana',
        86400 => 'dia',
        3600 => 'hora',
        60 => 'minuto',
        1 => 'segundo'
    );

    foreach ($tokens as $unit => $text) {
        if ($time < $unit) continue;
        $numberOfUnits = floor($time / $unit);
        if($text=="mês" && $numberOfUnits>1){ $text="meses"; $ext = ""; }
        return $numberOfUnits.' '.$text.(($numberOfUnits>1)?$ext:'');
    }

}
?>
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
