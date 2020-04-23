<?

require("../libs/php/funcoes.php");
require("../libs/php/conn.php");

$agora = now();

$limit_top = 20;

$sql = "SELECT *, jam_timestamp_utc AT TIME ZONE '0' as data_local
        FROM waze.stats
        WHERE jam_timestamp_utc = (SELECT max(jam_timestamp_utc) FROM waze.stats)
        ORDER BY jam_length DESC";

        $res = pg_query($sql)or die("SQL Error ".$sql);
        $x=0;
        while($d = pg_fetch_assoc($res))
        {
              $data_leitura           = $d['data_local'];
              $vet[$x]["rua"]         = $d['jam_street'];
              $vet[$x]["comprimento"] = $d['jam_length'];
              $vet[$x++]["atraso"]    = $d['jam_delay'];

              $legenda[] = "'".$d['jam_street']."'";

              $comprimento[] = $d['jam_length'];
              $atraso[]       = ceil($d['jam_delay']/60);

              if($x==($limit_top)){break;}
        }
if(isset($legenda) && count($legenda)){
    $legenda     = implode(",",$legenda);
    $comprimento = implode(",",$comprimento);
    $atraso      = implode(",",$atraso);
}
$data_leitura = substr(formataData(substr($data_leitura,0,19),1),0,16);
?>

<div class='row'>
  <div class="col-sm-6">
    <img src="../assets/images/waze-logo.png" width="200px">
  </div>
  <div class="col-sm-6" align="center">
    <h4 style="margin-top:30px"></h4>
  </div>
</div>

<div class="row">
  <div class="col-md-12 ">
      <?
/*
if(isset($vet) && count($vet))
{  $c=1;
  echo "<table class='table table-dark table-hover table-striped' id='datatable'>";
  echo "<thead class='thead-light'>
    <tr><th colspan='4' class='text-center'><h4><b>TOP ".$limit_top." - Vias mais congestionadas neste momento.</b></h4><small><span class='text-muted'>Data de referência: ".$data_leitura."</span></small></th></tr>
    <tr>
      <th class='text-muted'><small><i>#</i></small></th>
      <th class='text-muted'><small><i>Logradouro</i></small></th>
      <th class='text-muted'><small><i>Comprimento</th>
      <th class='text-muted'><small><i>Tempo</th>
    </tr></thead>";
  echo "<tbody>";
  for($i=0; $i<count($vet);$i++) {


    //if($c<=3){ $bg = "bg-danger";}
    //elseif($c>=3 && $c<=6){$bg = "bg-warning";}
    //else{ $bg="";}

    echo "<tr class='".$bg."'><td class='text-muted'><small>".$c++."</small></td>
              <td>".$vet[$i]['rua']."</td>
              <td>".number_format($vet[$i]["comprimento"],0,'','.')." <sup><small><i>mts</i></small></sup></td>
              <td>".ceil($vet[$i]["atraso"]/60)." <sup><small><i>min</i></small></sup></td></tr>";
  }
  echo "</tbody>";
  echo "</table>";
}
*/
      ?>
     <div id="graf" style="width:100%; height:900px;margin-top:20px"></div>
      <script>
      Highcharts.chart('graf', {
    chart: {
        type: 'bar'
    },
    title: {
        text: 'TOP <?=$limit_top;?> - Vias mais congestionadas neste momento'
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
        valueSuffix: ' metros'
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
        name: 'Comprimento de fila em metros em <?=$data_leitura;?>',
        data: [<?=$comprimento;?>]
    }]
});
      </script>
  </div>
</div>
