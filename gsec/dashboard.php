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

  $id_company   = ($_POST['id_company']  !=""?$_POST['id_company']  :$_SESSION['id_company']  );
  $name_company = ($_POST['name_company']!=""?$_POST['name_company']:$_SESSION['company_name']);

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


  logger("Acesso","GSEC", "Dashboard setor");

  $sql = "SELECT
              G.id,
              G.date_added,
              C.name as company,
              T.type, T.request
              FROM sepud.gsec_callcenter G
              LEFT JOIN sepud.gsec_request_type T ON T.id = G.id_subject
              LEFT JOIN sepud.company C ON C.id = G.id_company
              WHERE G.id_company = '{$id_company}' AND G.active = 't'
              ORDER BY G.date_added ASC LIMIT 1";
  $res = pg_query($sql)or die("SQL Error: ".__LINE__."<br>".$sql);

  if(pg_num_rows($res))
  {
        $maisAntigo              = pg_fetch_assoc($res);
        $aux                     = substr(str_replace("-","",$maisAntigo['date_added']),0,6);
        $maisAntigo['protocolo'] = $aux.".".str_pad($maisAntigo['id'],4,"0",STR_PAD_LEFT);
  }
?>

</style>
<section role="main" class="content-body">
  <header class="page-header">
    <h2>GSEC - Dashboard</h2>
    <div class="right-wrapper pull-right" style='margin-right:15px;'>
      <ol class="breadcrumbs">
        <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
        <li><span class='text-muted'>G.SEC</span></li>
        <li><span class='#'>Dashboard</span></li>
      </ol>
    </div>
  </header>

  <section class="panel box_shadow">
    <header class="panel-heading" style="height:70px;">
      <div style="margin-top:-10px">
      <h4>
            <b><?=$name_company;?></b>
            <br><small><sup>Mês de referência: <b><?=$agora['mes_txt']."/".$agora['ano'];?></b></sup></small>
      </h4>
      </div>
      <div class="panel-actions" style="margin-top:-5px">

        <button type="button" class="mb-xs mt-xs mr-xs btn btn-primary" data-toggle="modal" data-target="#modal_filtro_gsec_dashboard">
          <i class="fa fa-search"></i> Filtros</button>
        </button>
    </header>
    <div class="panel-body">
<?

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
                                                        <h5 class="title">Solicitação mais antiga em aberto:</h5>
                                                        <div style="font-size:18px;margin-top:15px">
                                                            <?
                                                               if(isset($maisAntigo))
                                                               {
                                                                  $datahora = explode(" ",formataData($maisAntigo['date_added'], 1));
                                                                  echo '<b>'.$datahora[0].'</b> <sup>'.$datahora[1].' ('.humanTiming($maisAntigo['date_added']).' atrás)</sup>';
                                                                  echo "<br><small>{$maisAntigo['type']}:{$maisAntigo['request']} <a href='gsec/callcenter_FORM.php?id={$maisAntigo['id']}'><i class='fa fa-search'></i></a></small>";
                                                                  echo "<br><small><sup>{$maisAntigo['company']}</sup></small>";
                                                                  //echo "<br><small><span class='text-muted'>Protocolo: </span><b>{$maisAntigo['protocolo']}</b> <a href='gsec/callcenter_FORM.php?id={$maisAntigo['id']}'><i class='fa fa-search'></i></a></small>";
                                                               }else{
                                                                  echo "<span class='text-muted'><small>Nenhum atendimento em aberto no sistema.</small></span>";
                                                               }
                                                            ?>
                  													</div>
                  												</div>
                  											</div>
                  										</div>
                                                <small class='text-muted pull-right'>* Filtro de data não se aplica a este campo.</small>
                  									</div>
                  								</section>

                    </div>

                    <div class="col-sm-6">

<?
      $sql = "SELECT count(*) as qtd FROM {$schema}gsec_callcenter G WHERE G.id_company = {$id_company} AND G.active='t'";
      $res = pg_query($sql)or die("SQL Error: ".__LINE__."<br>".$sql);
      $stats['totalaberto'] = pg_fetch_assoc($res);
?>
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
                          <h5 class="title">Estatísticas - Solicitações em aberto:</h5>
                          <div style="font-size:18px;margin-top:15px">
                             <table class='table table-condensed'>
                                <tbody>
                                   <tr><td><b><?=number_format($stats['totalaberto']['qtd'],0,'','.');?></b> <small>Solicitações em aberto</small></td></tr>
                                </tbody>
                             </table>
                          </div>
												</div>
											</div>
										</div>
                              <small class='text-muted pull-right'>* Filtro de data não se aplica a este campo.</small>
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
               for($dia=1;$dia<=$agora['ultimo_dia'];$dia++){ $evo_mensal_legenda[] = $dia; $evo_mensal_dados[$dia]=0; }

               $sql = "SELECT
                        count(*) as qtd, date_part('day', date_added) as dia
                        FROM {$schema}gsec_callcenter C
                        WHERE C.id_company = '{$id_company}'AND
                              C.date_added BETWEEN '".$agora['ano']."-".$agora['mes']."-01 00:00:00' AND '".$agora['ano']."-".$agora['mes']."-".$agora['ultimo_dia']." 23:59:59'
                        GROUP BY date_part('day', C.date_added)";
               $res = pg_query($sql)or die("SQL Error: ".__LINE__."<br>SQL: {$sql}");
               while($d = pg_fetch_assoc($res)){
                  $evo_mensal_dados[$d['dia']] = $d['qtd'];
                  $media['total'] += $d['qtd'];
                  $media['dias']++;
               }


              for($i=1;$i<=count($evo_mensal_dados);$i++)
              {
                $evo_mensal_d[] = (int)$evo_mensal_dados[$i];
              }
            ?>
            <div id="graf_evo_mensal" class="box_shadow box_radius_10" style="width:100%; height:300px;margin-top:10px"></div>
            <script>

            Highcharts.chart('graf_evo_mensal', {
    chart: {
        type: 'spline'
    },
    title: {
        text: 'Evolução diária - Quantidade de solicitações geradas'
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
        },
        plotLines: [{
           color: 'orange',
           value: '<?=ceil($media['total']/$media['dias']);?>', // Insert your average here
           width: '1',
           zIndex: 4, // To not get stuck below the regular plot lines or series
           label: {
                     text: '<span style="color:#CCCCCC">Média diária&nbsp;&nbsp;<br><?=ceil($media['total']/$media['dias']);?> solicitações</span>',
                     align: 'right',
                     x:-10
                 }
       }]
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
        name: 'Solicitações',
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
<?

   $sql = "SELECT count(*) as qtd,
            	 T.type, T.request,
            	 N.neighborhood
            FROM  sepud.gsec_callcenter C
            LEFT JOIN sepud.gsec_request_type T ON T.id = C.id_subject
            LEFT JOIN sepud.neighborhood      N ON N.id = C.id_neighborhood
            WHERE C.date_added BETWEEN '".$agora['ano']."-".$agora['mes']."-01 00:00:00' AND '".$agora['ano']."-".$agora['mes']."-".$agora['ultimo_dia']." 23:59:59'
            	AND C.id_company = {$id_company}
            GROUP BY T.type, T.request,N.neighborhood";

   $res = pg_query($sql)or die("SQL Error: ".__LINE__."<br>SQL: {$sql}");
   while($s = pg_fetch_assoc($res))
   {
      $tipos[$s['type']]          += $s['qtd'];
      $total                      += $s['qtd'];
      $setor[$s['neighborhood']]  += $s['qtd'];
      $stat_tipos[$s['type']][$s['request']]+= $s['qtd'];
   }

   foreach ($tipos as $key => $val){ $tiposqtd[] = $val;}
?>


            <div id="graf_evo_dem" class="box_shadow box_radius_10" style="width:100%; height:400px;margin-top:20px"></div>
            <script>
            Highcharts.chart('graf_evo_dem', {
          chart: {
              type: 'bar'
          },
          title: {
              text: 'Evolução mensal por tipo de solicitação'
          },
          subtitle: {
              text: 'Data de referência: <?=$agora['mes_txt']."/".$agora['ano'];?> - <?=$total;?> solicitações'
          },
          xAxis: {
              categories: <?=json_encode(array_keys($tipos));?>,
              title: {
                  text: null
              }
          },
          yAxis: {
              min: 0,
              title: {
                  text: null
              }
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
              showInLegend: false,
              name: 'Quantidade de solicitações',
              data: <?=json_encode($tiposqtd);?>
          }]
      });
            </script>
        </div>
        <div class="col-md-6">
          <?
            //print_r_pre($setor);
            foreach ($setor as $orgao => $qtd){ $legenda[] = $orgao; $quantitativo[] = $qtd;}
            //print_r_pre($quantitativo);
            //print_r_pre(json_encode($quantitativo));
          ?>
          <div id="graf_evo_org" class="box_shadow box_radius_10" style="width:100%; height:400px;margin-top:20px"></div>
          <script>
          Highcharts.chart('graf_evo_org', {
        chart: {
            type: 'bar'
        },
        title: {
            text: 'Evolução mensal de solicitações por bairro'
        },
        subtitle: {
            text: 'Data de referência: <?=$agora['mes_txt']."/".$agora['ano'];?> - <?=$total;?> solicitações'
        },
        xAxis: {
            categories: <?=json_encode($legenda);?>,
            title: {
                text: null
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: null
            }
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
            showInLegend: false,
            name: 'Quantidade de solicitações',
            data: <?=json_encode($quantitativo);?>
        }]
    });
          </script>
        </div>
      </div>

      <div class="row">
       <div class="col-md-6">
            <?
                  $sql = "SELECT count(*) as y, C.origin_type as name
                          FROM {$schema}gsec_callcenter C
                          WHERE C.date_added BETWEEN '".$agora['ano']."-".$agora['mes']."-01 00:00:00' AND '".$agora['ano']."-".$agora['mes']."-".$agora['ultimo_dia']." 23:59:59'
                          	 AND C.id_company = {$id_company}
                        GROUP BY C.origin_type";
                  $res = pg_query($sql)or die("SQL Error: ".__LINE__);
                  while($d=pg_fetch_assoc($res)){ $porOrigem[] = array( "name" => ($d['name']!=""?$d['name']:"Não informado"), "y" => (int)$d['y']); }
            ?>
            <div id="graf_evo_tipoOrg" class="box_shadow box_radius_10" style="width:100%; height:400px;margin-top:20px;"></div>
           <script>
           Highcharts.chart('graf_evo_tipoOrg', {
                      chart: {
                          plotBackgroundColor: null,
                          plotBorderWidth: null,
                          plotShadow: false,
                          type: 'pie'
                      },
                      title: { text: 'Evolução mensal das solicitações por tipo de origem' },
                      subtitle: {
                         text: 'Data de referência: <?=$agora['mes_txt']."/".$agora['ano'];?> - <?=$total;?> solicitações'
                     },
                      tooltip: { pointFormat: '<b>{point.y}</b> Solicitações<br><b>{point.percentage:.1f}%</b> do total' },
                      accessibility: {
                          point: {
                              valueSuffix: ' solicitações'
                          }
                      },
                      credits: { enabled: false },
                      plotOptions: {
                          pie: {
                              allowPointSelect: true,
                              cursor: 'pointer',
                              dataLabels: {
                                  enabled: true,
                                  format: '<b>{point.name}</b><br>{point.y} Solicitações - {point.percentage:.1f} %'
                              }
                          }
                      },
                      series: [{
                          name: 'Quantidade',
                          colorByPoint: true,
                          data: <?=json_encode($porOrigem);?>
                      }]
                  });
           </script>
       </div>
       <div class="col-md-6">
          <?
                     $sql = "SELECT
                              (SELECT count(*) FROM sepud.gsec_callcenter C
                                    WHERE C.id_company = '{$id_company}' AND C.date_added  BETWEEN '".$agora['ano']."-".$agora['mes']."-01 00:00:00' AND '".$agora['ano']."-".$agora['mes']."-".$agora['ultimo_dia']." 23:59:59') as geradas,
                              (SELECT count(*) FROM sepud.gsec_callcenter C
                                    WHERE C.id_company = '{$id_company}' AND C.date_closed BETWEEN '".$agora['ano']."-".$agora['mes']."-01 00:00:00' AND '".$agora['ano']."-".$agora['mes']."-".$agora['ultimo_dia']." 23:59:59') as baixadas";

                     $res   = pg_query($sql)or die("SQL Error ".__LINE__);
                     $stats = pg_fetch_assoc($res);
                     $percentualExecutado = number_format(round(($stats['baixadas']*100/$stats['geradas']),1),1,',','');

/*
colors: ['#2f7ed8' azul,
         '#0d233a' azul escuro,
         '#8bbc21' verde forte,
         '#910000' vermelho,
         '#1aadce' azul agua,
        '#492970' roxo,
        '#f28f43' laranja,
        '#77a1e5' azull +agua,
        '#c42525' vermelho vivo,
        '#a6c96a' verde agua]
*/
                     //echo json_encode($arraystats);
                     //echo "<br>".json_encode($porOrigem);

          ?>
          <div id="graf_aberto_fechado" class="box_shadow box_radius_10" style="width:100%; height:300px;margin-top:20px;"></div>
          <script>
          Highcharts.chart('graf_aberto_fechado', {
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: 'Produção mensal de solicitações no mês corrente'
                    },
                    subtitle: {
                        text: 'Data de referência: <?=$agora['mes_txt']."/".$agora['ano'];?>'
                    },
                    xAxis: {
                        categories: ['Geradas no mês', 'Baixadas no mês'],
                        crosshair: true
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: null
                        }
                    },
                    tooltip: { pointFormat: '<b>{point.y}</b> Solicitações no mês de <b><?=strtoupper($agora['mes_txt']);?></b>' },
                    /*
                    tooltip: {
                        headerFormat: '<b>{point.y}</b> Solicitações {point.key} no mês de <?=$agora['mes_txt'];?>',
                        shared: true,
                        useHTML: true
                    },
                    */
                    credits:{
                     enabled: false
                    },
                    plotOptions: {
                        column: {
                            pointPadding: 0.2,
                            borderWidth: 0,
                        }
                    },
                    series: [{
                        showInLegend: false,
                        name: 'Solicitações',
                        colorByPoint: true,
                        data: [{y: <?=$stats['geradas'];?>, color:'#17bde0'},
                               {y: <?=$stats['baixadas'];?>, color:'#f37c6c'}],
                        dataLabels: {
                            enabled: true,
                            color: '#000000',
                            align: 'center',
                            style: {
                                 fontSize: '12px',
                                 fontFamily: 'helvetica, arial, sans-serif',
                                 textShadow: false,
                                 fontWeight: 'bold'

                            }
                         }

                    }]
 });
          </script>
          <?
            $bg = "alert-danger";
                if($percentualExecutado > 30 && $percentualExecutado < 50 ){$bg="alert-warning"; }
            elseif($percentualExecutado >=50 && $percentualExecutado < 70 ){$bg="alert-info";    }
            elseif($percentualExecutado > 70)                              {$bg="alert-success"; }
          ?>
          <div id="statusPercentual" class="box_shadow box_radius_10 text-center <?=$bg;?>" style="width:100%; height:80px;margin-top:3px;">
             <h2><small>Percentual de execução: <sup>*</sup></small><br><b><?=$percentualExecutado;?> %</b></h2><small class='text-muted'>* Percentual de solicitações baixadas em relação as solicitações que foram abertas no mês.</small>
          </div>
       </div>
    </div>

      <div class="row">
        <div class="col-md-12" style="margin-top:20px">
            <?
   $dataAtual = now();
   $sql = "SELECT 'Aberto' as status, count(*) as qtd, T.type, T.request
               FROM sepud.gsec_callcenter C
               LEFT JOIN sepud.gsec_request_type T ON T.id = C.id_subject
               WHERE C.date_added BETWEEN '".$agora['ano']."-".$agora['mes']."-01 00:00:00' AND '".$agora['ano']."-".$agora['mes']."-".$agora['ultimo_dia']." 23:59:59' AND C.id_company = {$id_company}
               GROUP BY T.type, T.request
         UNION
         SELECT 'Fechado' as status, count(*) as qtd, T.type, T.request
               FROM sepud.gsec_callcenter C
               LEFT JOIN sepud.gsec_request_type T ON T.id = C.id_subject
               WHERE C.date_closed BETWEEN '".$agora['ano']."-".$agora['mes']."-01 00:00:00' AND '".$agora['ano']."-".$agora['mes']."-".$agora['ultimo_dia']." 23:59:59' AND C.id_company = {$id_company}
               GROUP BY T.type, T.request
         UNION
         SELECT 'Saldo anterior' as status, count(*) as qtd, T.type, T.request
               FROM sepud.gsec_callcenter C
               LEFT JOIN sepud.gsec_request_type T ON T.id = C.id_subject
               WHERE C.date_added < '".$agora['ano']."-".$agora['mes']."-01 00:00:00' AND active='t' AND C.id_company = {$id_company}
               GROUP BY T.type, T.request";
   $res = pg_query($sql)or die("SQL Error");
   while($d = pg_fetch_assoc($res))
   {
      $detalhado[$d['type']][$d['request']][$d['status']] = $d['qtd'];
      $totalstats[$d['status']] += $d['qtd'];
   }
   ksort($detalhado);

   echo "<div class='table-responsive box_shadow box_radius_10'>";
   echo "<table class='table table-hover'>";
   echo "<thead><tr><th colspan='4' class='text-center'><h3>Extrato mensal detalhado por grupo/subgrupo<br><small>Mês de referência: <b>{$agora['mes_txt']}/{$agora['ano']}</b> *<br><sup>* O saldo anterior se altera a medida que as solicitaçãos antigas são fechadas.</sup></small></h3></th></tr></thead>";
   foreach ($detalhado as $tipo => $dados) {
      echo "<thead><tr class=''><td rowspan='2' style='vertical-align:middle;background-color:#EEEEFF'><h4><b>{$tipo}</b></h4></td>
                               <td colspan='3' class='text-center info'>Solicitações</td>
                               <td nowrap class='text-center warning' rowspan='2' style='vertical-align:bottom;'>Baixadas</td>
            </tr><tr class='info'>
                               <td nowrap class='text-center'>Saldo anterior<br><small>Em aberto</small></td>
                               <td nowrap class='text-center'>Mês atual<br><small>Total gerado</small></td>
                               <td nowrap class='text-center' style='vertical-align:middle;'>Total</td>
                               </tr></thead>";
      foreach ($dados as $requisicao => $vals) {
         $calcs['saldoant']+=$vals['Saldo anterior'];
         $calcs['aberto']  +=$vals['Aberto'];
         $calcs['fechado'] +=$vals['Fechado'];
         echo "<tr>";
         echo "<td>{$requisicao}</td>";
         echo "<td width='1px' class='text-center'>".($vals['Saldo anterior']!=""?$vals['Saldo anterior']:"<span style='color:#CCCCCC'>0</span>")."</td>";
         echo "<td width='1px' class='text-center'>".($vals['Aberto']!=""?$vals['Aberto']:"<span style='color:#CCCCCC'>0</span>")."</td>";
         echo "<td width='1px' class='text-center'>".($vals['Aberto']+$vals['Saldo anterior'])."</td>";
         echo "<td width='1px' class='text-center'>".($vals['Fechado']!=""?$vals['Fechado']:"<span style='color:#CCCCCC'>0</span>")."</td>";
         echo "</tr>";
      }
      echo "<tr><td class='text-muted text-right'><i>Subtotal:</i></td>
                <td class='text-center'>{$calcs['saldoant']}</td>
                <td class='text-center'>{$calcs['aberto']}</td>
                <td class='text-center'>".($calcs['aberto']+$calcs['saldoant'])."</td>
                <td class='text-center'>{$calcs['fechado']}</td></tr>";
      unset($calcs);

   }
   echo "<tr class='warning'><td class='text-right'>Total geral:</td>
         <td class='text-center'>{$totalstats['Saldo anterior']}</td>
         <td class='text-center'>{$totalstats['Aberto']}</td>
         <td class='text-center'>".($totalstats['Aberto']+$totalstats['Saldo anterior'])."</td>
         <td class='text-center'>{$totalstats['Fechado']}</td></tr>";
   echo "<tr><td colspan='5' class='text-right text-muted'><i>Relatório gerado em <b>{$dataAtual['dthm']}</b></i></td></tr>";
   echo "</table>";



/*
                ksort($stat_tipos);
                  echo "<h4>Detalhado por tipo no mês de referência:</h4>";
                  echo "<div class='table-responsive box_shadow box_radius_10'>";
                  echo "<table class='table table-hover'>";
                  foreach($stat_tipos as $tipos => $subtipos)
                  {
                     echo "<tr class='primary'><td><b>{$tipos}</b></td>
                                               <td width='1px' class='text-center'>Qtd.</td>
                                               <td width='1px' class='text-center'>%</td></tr>";
                     foreach ($subtipos as $subtipo => $qtd) {
                        echo "<tr>";
                           echo "<td>{$subtipo}</td>";
                           echo "<td class='text-center'>{$qtd}</td>";
                           echo "<td class='text-center' nowrap>".number_format(round((($qtd*100)/$total),1),1,',','')." <small class='text-muted'>%</small></td>";
                        echo "</tr>";
                     }
                  }
                  echo "</table>";
                  echo "</div>";
*/
            ?>
        </div>
      </div>


    </div>

  </section>

</section>


<div class="modal fade" id="modal_filtro_gsec_dashboard" tabindex="-1" role="dialog" aria-labelledby="modal_filtro_gsec_dashboard" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Filtros de pesquisa</h5>
      </div>
      <form id="filtro" name="filtro" method="post" action="../gsec/dashboard.php">
      <div class="modal-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                      <label for="filtro_data">Período:</label>
                          <select id="filtro_data" name="filtro_data" class="form-control">
                             <?
                             if(isset($agora['ano']))
                             {
                                    for($a = 2021; $a <= $agora['ano']; $a++)
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

              <div class="row">
                  <div class='col-sm-12' style="margin-top:10px">
                     <label for="id_company">Setor:</label>
                     <select class="form-control select2" id="id_company" name="id_company">
                        <?
                           if(check_perm("9_31")){
                             $sql = "SELECT id, name, acron, id_father
                                     FROM {$schema}company
                                     WHERE active = 't' AND id_father = '{$_SESSION['id_company_father']}'
                                     ORDER BY name ASC";
                             $res = pg_query($sql)or die();
                             while($setores = pg_fetch_assoc($res)){
                                if($setores['id']==$_SESSION['id_company']){ $sel = "selected"; }else{ $sel=""; }
                                echo "<option value='{$setores['id']}' {$sel}>{$setores['name']}</option>";
                             }
                          }else{
                             echo "<option value='{$_SESSION['id_company']}'>{$_SESSION['company_name']}</option>";
                          }
                        ?>
                     </select>
                     <input type="hidden" name="name_company" id="name_company" value='' />
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
$('.select2').select2({ dropdownParent: $('#modal_filtro_gsec_dashboard')});
$('#bt_submit').click(function(e) {
    e.preventDefault();
     $("#modal_filtro").removeClass("in");
     $(".modal-backdrop").remove();
     $('body').removeClass('modal-open');
     $('body').css('padding-right', '');
     $("#modal_filtro").hide();
     $("#name_company").val($("#id_company option:selected").text());
     $("#filtro").submit();
    return false;
});
</script>
<?
function humanTiming($data)
{

    $time = strtotime($data);
    $time = time() - $time;
    $time = ($time<1)? 1 : $time;
    $tokens = array (
        31536000 => 'ano',
        2592000 => 'mes',
        604800 => 'semana',
        86400 => 'dia',
        3600 => 'hora',
        60 => 'min',
        1 => 'seg'
    );

    foreach ($tokens as $unit => $text) {
        if ($time < $unit) continue;
        $numberOfUnits = floor($time / $unit);
        if($text=="mes" && $numberOfUnits>1){ $ext = "es"; }else{ $ext = "s"; }
        return $numberOfUnits.' '.$text.(($numberOfUnits>1)?$ext:'');
    }

}
?>
