<?

require("../libs/php/funcoes.php");
require("../libs/php/conn.php");





    $agora        = now();
    $limit_top    = 20;
    $sql          = "SELECT *, start_time AT TIME ZONE '0' as data_local FROM waze.data_files ORDER BY id DESC LIMIT 1";
    $res          = pg_query($sql)or die("SQL Error ".$sql);


    $legenda = implode(",",$legenda);
    $dados   = implode(",",$dados);
?>

<div class='row'>
  <div class="col-sm-6">
  <!--  <img src="../assets/images/waze-logo.png" width="200px">-->
  </div>
  <div class="col-sm-6" align="center">
  </div>
</div>

<div class="row">
  <div class="col-md-6 offset-3" style="background-color:white">
    <div id="graf" style="width:100%; height:500px;margin-top:20px"></div>
      <script>
      Highcharts.chart('graf', {
    chart: {
        type: 'bar'
    },
    title: {
        text: 'Alertas reportado pelos usuários da plataforma do WAZE'
    },
    subtitle: {
        text: 'Data de referência: <?=$data_leitura;?>'
    },
    xAxis: {
        categories: [<?=$legenda;?>],
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
        name: 'Quantidade de alertas reportado pelos usuários do WAZE em <?=$data_leitura;?>',
        data: [<?=$dados;?>]
    }]
});
      </script>
  </div>
</div>
