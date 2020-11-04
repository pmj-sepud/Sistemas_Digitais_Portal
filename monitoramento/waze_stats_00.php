<?

require("../libs/php/funcoes.php");
require("../libs/php/conn.php");

$agora = now();


?>
<div style="position:absolute;z-index:10;margin-top:5px;margin-left:5px"><img src="../assets/images/waze-logo.png" width="200px"></div>
<div class='row'>
  <div class="col-sm-12">
      <?
            $sql = "SELECT
              			 jams_delay,
              			 jams_length,
              			 EXTRACT(MIN FROM start_time AT TIME ZONE '0') AS minuto_local,
                     EXTRACT(HOUR FROM start_time AT TIME ZONE '0') AS hora_local
                  FROM
                      	waze.data_files
                  WHERE
                      	    start_time AT TIME ZONE '0' BETWEEN '".$agora['datasrv']." 00:00:00' AND '".$agora['datasrv']." 23:59:59'
                  ORDER BY  start_time AT TIME ZONE '0' ASC";
            $res = pg_query($sql)or die("SQL Error ".$sql);

            $maxdelay = $maxdelayTime = $maxlenght = $maxlenghtTime = 0;

            while($d = pg_fetch_assoc($res))
            {
                //$vet['Atraso'][$d['hora_local'].":".$d['minuto_local']]  = round(($d['jams_delay']/60),1);
                //$vet['Comprimento'][$d['hora_local'].":".$d['minuto_local']] = round(($d['jams_length']/1000),1);

                //if($d['hora_local']==0){ $minutos = $d['minuto_local'];}
                //else{                    $minutos = $d['hora_local']+$d['minuto_local'];}

              //  $vet2['Atraso'][$minutos]  = round(($d['jams_delay']/60),1);

                $hora = str_pad($d['hora_local'],2,"0",STR_PAD_LEFT).":".str_pad($d['minuto_local'],2,"0",STR_PAD_LEFT);

                $legenda[] = "'".$hora."'";

                $vet2['Atraso'][]  = ceil($d['jams_delay']/60);
                $vet2['Comprimento'][]  = round(($d['jams_length']/1000),1);

                if(ceil($d['jams_delay']/60)        >= $maxdelay) { $maxdelay  = ceil($d['jams_delay']/60);         $maxdelayTime  = $hora; }
                if(round(($d['jams_length']/1000),1) >= $maxlenght){ $maxlenght = round(($d['jams_length']/1000),1); $maxlenghtTime = $hora; }

                $atualdelay  = ceil($d['jams_delay']/60);         $atualdelayTime  = $hora;
                $atuallenght = round(($d['jams_length']/1000),1); $atuallenghtTime = $hora;
            }

            //$serieAtraso  = "{name: 'Atraso (Minutos)', data: [".implode(",",$vet['Atraso'])."]}";
            //$serieTamanho = "{name: 'Comprimento (Km)', data: [".implode(",",$vet['Comprimento'])."]}";


            $serieAtraso  = "{name: 'Atraso (Minutos)', data: [".implode(",",$vet2['Atraso'])."]}";
            $serieTamanho = "{name: 'Comprimento (Km)', data: [".implode(",",$vet2['Comprimento'])."]}";

//echo "<h3>".implode(",",$legenda)."</h3>";
      ?>



      <div id="graf" style="width:100%; height:500px;margin-top:10px"></div>
      <script>

      $(function () {
          var myChart = Highcharts.chart('graf', {
            chart: {
                    type: 'spline',
                    inverted: false
            },
            credits: {
                    enabled: false
            },
  title: {
      text: 'WAZE - Evolução diária'
  },
  tooltip: {
      crosshairs: [true,true],
      formatter: function() {
        return '<b>' + this.y + '</b>, '+ this.series.name + "  - " + this.x;
      }
  },
  subtitle: {
      text: 'Somatória de congestionamentos em km e tempo de atraso em minutos'
  },

  yAxis: {
      title: {
          text: 'Comprimento de fila<br>Somatória dos atrasos'
      }
  },
  xAxis: {
      title: {
          text: 'Minuto do dia'
      },
      categories: [<?=implode(",",$legenda);?>]
  },
  legend: {
      layout: 'vertical',
      align: 'center',
      verticalAlign: 'bottom'
  },

  plotOptions: {
      series: {
          label: {
              connectorAllowed: false
          },
          pointStart: 0,
          marker:{ enabled: false }
      }
  },

  series: [
      <?=$serieAtraso;?>,
      <?=$serieTamanho;?>
  ],

  responsive: {
      rules: [{
          condition: {
              maxWidth: 500
          },
          chartOptions: {
              legend: {
                  layout: 'horizontal',
                  align: 'center',
                  verticalAlign: 'bottom'
              }
          }
      }]
  }

});


        });
      </script>
  </div>
</div>


<div class='row' style="margin-top:20px">
  <div class="col-sm-6" align="center">
      <span><i>Somatória dos congestionamentos</i></span><br>
      <span style="font-size:35px;">Atual:</span>
      <span style="font-size:80px"><b><?=$atuallenght;?> <sup><small>Km</small></sup></b></span>
      <br>Hora: <?=$atuallenghtTime;?>
      <br><br><br><span><i>Somatória dos atrasos</i></span><br>
      <br><span style="font-size:80px"><b><?=$atualdelay;?> <sup><small>Minutos</small></sup></b></span>
      <br>Hora: <?=$atualdelayTime;?>
  </div>


  <div class="col-sm-6" align="center">
      <span><i>Somatória dos congestionamentos</i></span><br>
      <span style="font-size:35px;">Pico:</span>
      <span style="font-size:80px"><b><?=$maxlenght;?> <sup><small>Km</small></sup></b></span>
      <br>Hora: <?=$maxlenghtTime;?>
      <br><br><br><span><i>Somatória dos atrasos</i></span><br>
      <br><span style="font-size:80px"><b><?=$maxdelay;?> <sup><small>Minutos</small></sup></b></span>
      <br>Hora: <?=$maxdelayTime;?>
  </div>
</div>
