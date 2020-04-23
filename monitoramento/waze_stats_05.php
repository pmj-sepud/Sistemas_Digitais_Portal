<?

require("../libs/php/funcoes.php");
require("../libs/php/conn.php");


   $traducao["HAZARD_ON_ROAD_POT_HOLE"]            = "Buraco";
   $traducao["WEATHERHAZARD"]                      = "Perigo";
   $traducao["HAZARD_ON_ROAD"]                     = "Perigo na via";
   $traducao["HAZARD_ON_ROAD_CAR_STOPPED"]         = "Veículo parado";
   $traducao["HAZARD_ON_ROAD_CONSTRUCTION"]        = "Via em construção";
   $traducao["HAZARD_ON_ROAD_ICE"]                 = "Gelo na via";
   $traducao["HAZARD_ON_ROAD_OBJECT"]              = "Objeto na via";
   $traducao["HAZARD_ON_ROAD_TRAFFIC_LIGHT_FAULT"] = "Semáforo queimado";
   $traducao["HAZARD_WEATHER"]                     = "Clima perigoso";
   $traducao["HAZARD_WEATHER_FLOOD"]               = "Inundação";
   $traducao["HAZARD_WEATHER_FOG"]                 = "Neblina";
   $traducao["HAZARD_ON_SHOULDER_MISSING_SIGN"]    = "Sinalização perdida";
   $traducao["HAZARD_ON_SHOULDER_CAR_STOPPED"]     = "Veículo parado na via";
   $traducao["HAZARD_ON_SHOULDER_ANIMALS"]         = "Animal na via";
   $traducao["HAZARD_ON_SHOULDER"]                 = "Resalto na via";
   $traducao["HAZARD_ON_ROAD_ROAD_KILL"]           = "Via perigosa";
   $traducao["HAZARD_WEATHER_HAIL"]                = "Granizo";
   $traducao["JAM_STAND_STILL_TRAFFIC"]            = "Tráfego remanecente";
   $traducao["JAM_HEAVY_TRAFFIC"]                  = "Tráfego pesado";
   $traducao["JAM_MODERATE_TRAFFIC"]               = "Tráfego moderado";
   $traducao["ROAD_CLOSED_EVENT"]                  = "Via fechada";
   $traducao["ACCIDENT"]                           = "Acidente";
   $traducao["ACCIDENT_MINOR"]                     = "Acidente menor";
   $traducao["JAM"]                                = "Congestionamento";
   $traducao["ROAD_CLOSED_CONSTRUCTION"]           = "Via em construção";


    $agora        = now();
    $limit_top    = 20;
    $sql          = "SELECT *, start_time AT TIME ZONE '0' as data_local FROM waze.data_files ORDER BY id DESC LIMIT 1";
    $res          = pg_query($sql)or die("SQL Error ".$sql);
    $d            = pg_fetch_assoc($res);
    $vet          = (array)json_decode($d['json_alerts_type']);
    $data_leitura = substr(formataData(substr($d['data_local'],0,19),1),0,16);
    foreach ($vet as $key => $vals){
      foreach ($vals as $subtype => $qtd) {
          $legenda[] = "'".($traducao[$subtype]?$traducao[$subtype]:$subtype)."'";
          $dados[]   = $qtd;
      }
    }

    $legenda = implode(",",$legenda);
    $dados   = implode(",",$dados);
?>

<div class='row'>
  <div class="col-sm-6">
    <img src="../assets/images/waze-logo.png" width="200px">
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
