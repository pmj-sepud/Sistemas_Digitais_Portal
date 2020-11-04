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


  logger("Acesso","SAS - BEV", "Dashboard geral");


  $sql = "SELECT * FROM {$schema}sas_vars ORDER BY description ASC";
  $res = pg_query($sql)or die("Error ".__LINE__."<br>Query: {$sql}");
  while($d = pg_fetch_assoc($res)){
      $vars[$d['id']]['nome']  = $d['description'];
      $vars[$d['id']]['grupo'] = $d['subgroup'];
  }

  $sql = "SELECT count(*)as qtd, R.status, date_part('day', R.date) as dia,
                 R.demand::text,
                 R.vars::text,
                 C.acron as company_acron
          FROM {$schema}sas_request R
          JOIN {$schema}company C ON C.id = R.id_company
          WHERE date BETWEEN '{$agora['ano']}-{$agora['mes']}-01 00:00:00' AND
                             '{$agora['ano']}-{$agora['mes']}-{$agora['ultimo_dia']} 23:59:59'
          GROUP BY R.status, C.acron, date_part('day', R.date), R.demand::text, R.vars::text";
  $res = pg_query($sql)or die("Error ".__LINE__."<br>Query: {$sql}");

  for($dia=1;$dia<=$agora['ultimo_dia'];$dia++){ $evo_mensal_legenda[] = $dia; $evo_mensal_dados[$dia]=0; $evolucao_diaria[$dia]= array("Aberto" => 0, "Fechado" => 0, "Total" => 0); }

  while($x = pg_fetch_assoc($res)){
    $dados[]=$x;
    $demanda = json_decode($x['demand']);

    $total_demandas                           += $x['qtd'];
    $evolucao_diaria[$x['dia']][$x['status']] += $x['qtd'];
    $evolucao_diaria[$x['dia']]['Total']      += $x['qtd'];

    $evo_mensal_dados[$x['dia']]              += (count($demanda)*$x['qtd']);

    $evolucao_orgao[$x['company_acron']]      += (count($demanda)*$x['qtd']);

    $demanda = json_decode($x['demand']);
    for($i=0;$i<=2;$i++){ if($demanda[$i]!=""){$evolucao_demanda[$demanda[$i]] += $x['qtd']; $evolucao_demanda['Total'] += $x['qtd'];}}

      $varsel = json_decode($x['vars']);
      for($i=0;$i<count($varsel);$i++)
      {
        $stats_vars[$vars[$varsel[$i]]['grupo']]['total']++;
        $stats_vars[$vars[$varsel[$i]]['grupo']]['vars'][$vars[$varsel[$i]]['nome']]++;
        $total_vars_sel++;

      }

  }
?>

</style>
<section role="main" class="content-body">
  <header class="page-header">
    <h2>SAS-BEV - Dashboard <sup>(Todos os equipamentos)</sup></h2>
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
      Mês de referência: <b><?=$agora['mes_txt']."/".$agora['ano'];?></b>
      <div class="panel-actions" style="margin-top:5px">

        <button type="button" class="mb-xs mt-xs mr-xs btn btn-primary" data-toggle="modal" data-target="#modal_filtro">
          <i class="fa fa-search"></i> Filtros</button>
        </button>
    </header>
    <div class="panel-body">

<?


  $sql = "SELECT R.date, R.demand::text, R.demand_status::text
          FROM {$schema}sas_request R WHERE R.status = 'Aberto'";
  $res = pg_query($sql)or die("Error ".__LINE__."<br>Query: ".$sql);
  while($d=pg_fetch_assoc($res))
  {
    unset($geral, $mes);

    $mes = substr($d['date'],0,7);

    $demand = json_decode($d['demand']);
    $demand_status = json_decode($d['demand_status']);

    if($demand[0]!=""){
            if($demand_status[0]=="Aberto"){ $stat['aberta']++;  $stat[$mes]['aberta']++; }
        elseif($demand_status[0]=="Negado"){ $stat['negado']++;  $stat[$mes]['negado']++; }
                                       else{ $stat['fechado']++; $stat[$mes]['fechado']++;}
    }

    if($demand[1]!=""){
            if($demand_status[1]=="Aberto"){ $stat['aberta']++;  $stat[$mes]['aberta']++; }
        elseif($demand_status[1]=="Negado"){ $stat['negado']++;  $stat[$mes]['negado']++; }
                                       else{ $stat['fechado']++; $stat[$mes]['fechado']++;}
    }

    if($demand[2]!=""){
            if($demand_status[1]=="Aberto"){ $stat['aberta']++;  $stat[$mes]['aberta']++; }
        elseif($demand_status[1]=="Negado"){ $stat['negado']++;  $stat[$mes]['negado']++; }
                                       else{ $stat['fechado']++; $stat[$mes]['fechado']++;}
    }


  }

  $sql = "SELECT R.date, R.demand::text, R.demand_status::text
          FROM {$schema}sas_request R WHERE R.status = 'Fechado'
          AND  R.date_closed BETWEEN '{$agora['ano']}-{$agora['mes']}-01 00:00:00' AND
                              '{$agora['ano']}-{$agora['mes']}-{$agora['ultimo_dia']} 23:59:59'";
  $res = pg_query($sql)or die("Error ".__LINE__."<br>Query: ".$sql);
  while($d=pg_fetch_assoc($res))
  {
    //print_r_pre($d);
    unset($geral, $mes);

    $mes = substr($d['date'],0,7);

    $demand = json_decode($d['demand']);
    $demand_status = json_decode($d['demand_status']);

    if($demand[0]!=""){
            if($demand_status[0]=="Aberto"){ $stat['aberta']++;  $stat[$mes]['aberta']++; }
        elseif($demand_status[0]=="Negado"){ $stat['negado']++;  $stat[$mes]['negado']++; }
                                       else{ $stat['fechado']++; $stat[$mes]['fechado']++;}
    }

    if($demand[1]!=""){
            if($demand_status[1]=="Aberto"){ $stat['aberta']++;  $stat[$mes]['aberta']++; }
        elseif($demand_status[1]=="Negado"){ $stat['negado']++;  $stat[$mes]['negado']++; }
                                       else{ $stat['fechado']++; $stat[$mes]['fechado']++;}
    }

    if($demand[2]!=""){
            if($demand_status[1]=="Aberto"){ $stat['aberta']++;  $stat[$mes]['aberta']++; }
        elseif($demand_status[1]=="Negado"){ $stat['negado']++;  $stat[$mes]['negado']++; }
                                       else{ $stat['fechado']++; $stat[$mes]['fechado']++;}
    }

  }



    $sql =  "SELECT count(*) as qtd_solicitacoes, R.demand::text,
            (SELECT MIN(date) FROM {$schema}sas_request WHERE status = 'Aberto') as data_sol_mais_antiga
             FROM {$schema}sas_request R
             WHERE R.status = 'Aberto'
             GROUP BY R.demand::text, (SELECT MIN(date) FROM {$schema}sas_request WHERE status = 'Aberto')";
    $res = pg_query($sql)or die("Error ".__LINE__."<br>Query: ".$sql);
    while($s=pg_fetch_assoc($res))
    {
      $demand = json_decode($s['demand']);
      $s['qtd_demanda'] = count($demand) * $s['qtd_solicitacoes'];

      $stats['qtd_demandas'] += $s['qtd_demanda'];
      $stats['qtd_solicitacoes'] += $s['qtd_solicitacoes'];
      $stats['data_sol_aberta_mais_antiga'] = $s['data_sol_mais_antiga'];

    }
?>

            <div class="row" style="margin-top:10px">
                <div class="col-sm-6">
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
                                            <h5 class="title">Demanda mais antiga ainda em aberto:</h5>
                                            <div style="font-size:18px;margin-top:15px">
                  														  <?
                                                    $datahora = explode(" ",formataData($stats['data_sol_aberta_mais_antiga'], 1));
                                                    echo '<b>'.$datahora[0].'</b> <sup>'.$datahora[1].'</sup>';
                                                ?>
                  													</div>
                  												</div>

                  											</div>
                  										</div>
                  									</div>
                  								</section>

                    </div>

                    <div class="col-sm-6">


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
                          <h5 class="title">Estatísticas:</h5>
                          <div style="font-size:18px;margin-top:15px">
                            <b><?=$stat['aberta'];?></b>  demandas abertas.<br>
                            <b><?=$stat['fechado'];?></b>  demandas finalizadas no mês atual.<br>
                            <b><?=$stat['negado'];?></b>  demandas negadas no mês atual.<br>
                          </div>
												</div>
											</div>
										</div>
									</div>
								</section>


                    </div>
                  </div>
                  <div class="row" style="margin-top:10px">
                      <div class="col-sm-12"><hr>
                      </div></div>

      <div class="row">
        <div class="col-md-12">
            <?
              for($i=1;$i<count($evo_mensal_dados);$i++)
              {
                $evo_mensal_d[] = $evo_mensal_dados[$i];
              }
            ?>
            <div id="graf_evo_mensal" style="width:100%; height:300px;margin-top:10px"></div>
            <script>

            Highcharts.chart('graf_evo_mensal', {
    chart: {
        type: 'spline'
    },
    title: {
        text: 'Evolução diária - Quantidade de demandas geradas'
    },
    subtitle: {
        text: 'Data de referência: <?=$agora['mes_txt']."/".$agora['ano'];?>'
    },
    xAxis: {
        categories: <?=json_encode($evo_mensal_legenda);?>
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
        name: 'Demandas',
        data:  <?=json_encode($evo_mensal_d);?>
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
            <div id="graf_evo_dem" style="width:100%; height:400px;margin-top:20px"></div>
            <script>
            Highcharts.chart('graf_evo_dem', {
          chart: {
              type: 'bar'
          },
          title: {
              text: 'Evolução mensal por demanda'
          },
          subtitle: {
              text: 'Data de referência: <?=$agora['mes_txt']."/".$agora['ano'];?> - <?=$evolucao_demanda['Total'];?> benefício(s)'
          },
          xAxis: {
              categories: ["Alimentação", "Natalidade", "Funeral"],
              title: {
                  text: null
              }
          },
          yAxis: {
              min: 0
          },
          tooltip: {
              valueSuffix: ''
          },
          plotOptions: {
              bar: {
                  dataLabels: {
                      enabled: true
                  }
              }
          },
          legend: {
              layout: 'vertical',
              align: 'center',
              verticalAlign: 'bottom',
              floating: true,
              y: 10,
              borderWidth: 1,
              backgroundColor:
                  Highcharts.defaultOptions.legend.backgroundColor || '#FFFFFF',
              shadow: true
          },
          credits: {
              enabled: false
          },
          series: [{
              name: 'Quantidade de solicitações',
              data: [<?=$evolucao_demanda['alimentacao'];?>,
                     <?=$evolucao_demanda['natalidade'];?>,
                     <?=$evolucao_demanda['funeral'];?>]
          }]
      });
            </script>
        </div>
        <div class="col-md-6">
          <?
              foreach ($evolucao_orgao as $orgao => $qtd){ $legenda[] = $orgao; $quantitativo[] = $qtd; $total += $qtd;}
          ?>
          <div id="graf_evo_org" style="width:100%; height:400px;margin-top:20px"></div>
          <script>
          Highcharts.chart('graf_evo_org', {
        chart: {
            type: 'bar'
        },
        title: {
            text: 'Evolução mensal da demanda por órgão'
        },
        subtitle: {
            text: 'Data de referência: <?=$agora['mes_txt']."/".$agora['ano'];?> - <?=$total;?> benefício(s)'
        },
        xAxis: {
            categories: <?=json_encode($legenda);?>,
            title: {
                text: null
            }
        },
        yAxis: {
            min: 0
        },
        plotOptions: {
            bar: {
                dataLabels: {
                    enabled: true
                }
            }
        },
        legend: {
            layout: 'vertical',
            align: 'center',
            verticalAlign: 'bottom',
            floating: true,
            y: 10,
            borderWidth: 1,
            backgroundColor:
                Highcharts.defaultOptions.legend.backgroundColor || '#FFFFFF',
            shadow: true
        },
        credits: {
            enabled: false
        },
        series: [{
            name: 'Quantidade de solicitações',
            data: <?=json_encode($quantitativo);?>
        }]
    });
          </script>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <hr>
          <h4>Estatísticas por variável:</h4>
          <table class='table table-striped'>
            <tbody>
                <?
                    foreach ($stats_vars as $group => $var) {
                      echo "<tr class='info'><td><small>Grupo: </small><b>{$group}</b></td>";
                      echo "<td><b>".number_format($var['total'],0,'','.')."</b></td>";
                      echo "<td><b>".round(($var['total']*100)/$total_vars_sel,1)."<b> <small>%</small></td>";
                      echo "<td></td>";
                      echo "<tr><td><small><i class='text-muted'>Variável</i></small></td>
                                <td><small><i class='text-muted'>Quantidade</i></small></td>
                                <td><small><i class='text-muted'>Distribuição em relação as var. selecionadas</i></small></td>
                                <td><small><i class='text-muted'>Percentual em relação as demandas</i></small></td></tr>";
                      foreach ($var['vars'] as $nome => $qtd){
                        echo "<tr><td>{$nome}</td>";
                        echo "<td>".number_format($qtd,0,'','.')."</td>";
                        echo "<td>".round(($qtd*100)/$total_vars_sel,1)." <small>%</small></b></td>";
                        echo "<td>".round(($qtd*100)/$total_demandas,1)." <small>%</small></b></td>";
                      }
                      echo "</tr>";
                    }
                ?>
            </body>
          </table>

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
