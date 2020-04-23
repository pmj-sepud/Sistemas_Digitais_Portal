<?
  error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
  //error_reporting(E_ALL);
  session_start();
  require("../libs/php/funcoes.php");
  require("../libs/php/conn.php");

    logger("Acesso","WAZE - Dashboard");

if(isset($_POST['waze_filtro_data']))
{
  $filtro_data = mkt2date(date2mkt($_POST['waze_filtro_data']));
}else {
  $filtro_data = now();
}
  $agora = now();



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

<style>
.flot-x-axis .flot-tick-label {
    white-space: nowrap;
    transform: translate(-30px, 0) rotate(-90deg);
    text-indent: -100%;
    transform-origin: top right;
    text-align: right !important;

}
</style>
<section role="main" class="content-body">
        <header class="page-header">
          <h2>Dashboard</h2>
          <div class="right-wrapper pull-right" style='margin-right:15px;'>
            <ol class="breadcrumbs">
              <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
              <li><span class='text-muted'>Waze</span></li>
              <li><span class='text-muted'>Evolução Diária</span></li>
            </ol>
            <!--<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>-->
          </div>
        </header>
        <div class="col-md-12">
                  <section class="panel">
                        <header class="panel-heading">
                            Referência: <b><?=$agora['data'];?></b>
                            <div class="panel-actions">
                              <!--
                                <button type="button" class="mb-xs mt-xs mr-xs btn btn-xs btn-primary" data-toggle="modal" data-target="#waze_modal_filtro">
                                Filtros
                                </button>
                              -->
                            </div>
                        </header>
                        <div class="panel-body">
                          <div class='row'>
                            <div class="col-sm-12">
                                  <h4 class='info'>Alertas de eventos gerado pelos usuários:</h4>
                            </div>
                          </div>

                          <div class='row'>
                            <div class="col-sm-8">
                              <?
                                      $sql = "SELECT MAX(total_alerts)         AS max_alerts,         AVG(total_alerts)::integer 			   AS med_alerts,         MIN(total_alerts) 				AS min_alerts,
                                              			 MAX(total_jams)   				 AS max_jams,           AVG(total_jams)::integer 				   AS med_jams,           MIN(total_jams) 				  AS min_jams,
                                              			 MAX(total_irregularities) AS max_irregularities, AVG(total_irregularities)::integer AS med_irregularities, MIN(total_irregularities) AS MIN_irregularities,
                                              			 MAX(jams_delay)      		 AS max_delay,          AVG(jams_delay)::integer 				   AS med_delay,          MIN(jams_delay) 				  AS min_delay,
                                              			 MAX(jams_length)      		 AS max_length,         AVG(jams_length)::integer 				 AS med_length,         MIN(jams_length) 				  AS min_length,
                                              			 EXTRACT(HOUR FROM start_time AT TIME ZONE '-01') AS hora
                                              FROM
                                              	waze.data_files
                                              WHERE
                                              	    start_time AT TIME ZONE '-01' BETWEEN '".$agora['datasrv']." 00:00:00' AND '".$agora['datasrv']." 23:59:59'
                                              GROUP BY
                                              	EXTRACT(HOUR FROM start_time AT TIME ZONE '-01')
                                              ORDER BY
                                              	EXTRACT(HOUR FROM start_time AT TIME ZONE '-01') ASC";

                                      $res = pg_query($sql)or die("SQL Error: ".$sql);

                                      $stats['max_alert'] = $stats['max_alert_hour'] = 0;
                                      $stats['min_alert'] = $stats['min_alert_hour'] = 0;
                                      $stats['max_jam']   = $stats['max_jam_hour']   = 0;
                                      $stats['min_jam']   = $stats['min_jam_hour']   = 0;
                                      $stats['max_delay'] = $stats['max_delay_hour'] = 0;
                                      $stats['min_delay'] = $stats['min_delay_hour'] = 0;
                                      $stats['max_length'] = $stats['max_length_hour'] = 0;
                                      $stats['min_lenght'] = $stats['min_length_hour'] = 0;

                                      while($d = pg_fetch_assoc($res))
                                      {
                                                    foreach ($d as $field => $val)
                                                    {

                                                                  if($field=="max_alerts" || $field=="min_alerts" || $field=="med_alerts")
                                                                  {
                                                                    switch ($field) {
                                                                      case 'max_alerts':
                                                                        $nome = 'Máximo';
                                                                        break;
                                                                      case 'min_alerts':
                                                                        $nome = 'Mínimo';
                                                                        break;
                                                                      case 'med_alerts':
                                                                        $nome = 'Média';
                                                                        $stats['alert_media_diaria'] += $val;
                                                                        $stats['alert_media_diaria_count']++;
                                                                        break;
                                                                      default:
                                                                        $nome = $field;
                                                                        break;
                                                                    }

                                                                    if($stats['max_alert']<=$val){ $stats['max_alert'] = $val; $stats['max_alert_hour'] = $d['hora']; }
                                                                    if($stats['min_alert']==0 || $val <= $stats['min_alert']){ $stats['min_alert'] = $val; $stats['min_alert_hour'] = $d['hora']; }

                                                                    $alertas[$nome][$d['hora']] = $val;
                                                                  }elseif($field=="max_jams" || $field=="min_jams" || $field=="med_jams")
                                                                  {
                                                                    switch ($field) {
                                                                      case 'max_jams':
                                                                        $nome = 'Máximo';
                                                                        break;
                                                                      case 'min_jams':
                                                                        $nome = 'Mínimo';
                                                                        break;
                                                                      case 'med_jams':
                                                                        $nome = 'Média';
                                                                        $stats['jam_media_diaria'] += $val;
                                                                        $stats['jam_media_diaria_count']++;
                                                                        break;
                                                                      default:
                                                                        $nome = $field;
                                                                        break;
                                                                    }

                                                                    if($stats['max_jam']<=$val){ $stats['max_jam'] = $val; $stats['max_jam_hour'] = $d['hora']; }
                                                                    if($stats['min_jam']==0 || $val <= $stats['min_jam']){ $stats['min_jam'] = $val; $stats['min_jam_hour'] = $d['hora']; }
                                                                    $jams[$nome][$d['hora']] = $val;

                                                                }elseif($field=="max_delay" || $field=="min_delay" || $field=="med_delay"){
                                                                  $val = round(($val/60), 1);
                                                                  switch ($field) {
                                                                    case 'max_delay':
                                                                      $nome = 'Máximo';
                                                                      break;
                                                                    case 'min_delay':
                                                                      $nome = 'Mínimo';
                                                                      break;
                                                                    case 'med_delay':
                                                                      $nome = 'Média';
                                                                      $stats['delay_media_diaria'] += $val;
                                                                      $stats['delay_media_diaria_count']++;
                                                                      break;
                                                                    default:
                                                                      $nome = $field;
                                                                      break;
                                                                  }
                                                                  if($stats['max_delay']<=$val){ $stats['max_delay'] = $val; $stats['max_delay_hour'] = $d['hora']; }
                                                                  if($stats['min_delay']==0 || $val <= $stats['min_delay']){ $stats['min_delay'] = $val; $stats['min_delay_hour'] = $d['hora']; }
                                                                  //$delay[$nome][$d['hora']] = ceil($val/60);
                                                                  $delay[$nome][$d['hora']] = $val;
                                                                }elseif($field=="max_length" || $field=="min_length" || $field=="med_length")
                                                                {
                                                                  $val = round(($val/1000), 1);
                                                                  switch ($field) {
                                                                    case 'max_length':
                                                                      $nome = 'Máximo';
                                                                      break;
                                                                    case 'min_length':
                                                                      $nome = 'Mínimo';
                                                                      break;
                                                                    case 'med_length':
                                                                      $nome = 'Média';
                                                                      $stats['length_media_diaria'] += $val;
                                                                      $stats['length_media_diaria_count']++;
                                                                      break;
                                                                    default:
                                                                      $nome = $field;
                                                                      break;
                                                                  }
                                                                  if($stats['max_length']<=$val){ $stats['max_length'] = $val; $stats['max_length_hour'] = $d['hora']; }
                                                                  if($stats['min_length']==0 || $val <= $stats['min_length']){ $stats['min_length'] = $val; $stats['min_length_hour'] = $d['hora']; }
                                                                  $length[$nome][$d['hora']] = $val;
                                                                }else{
                                                                  $dados[$field][$d['hora']] = $val;
                                                                }
                                                  }
                                    }



                                    if(isset($alertas) && count($alertas))
                                    {
                                      echo "<table class='table table-striped table-condensed'>";
                                      echo "<thead><tr><th class='text-right text-muted'><i>Hora:</i></th>";
                                        for($i=0;$i<=23;$i++)
                                        {
                                          echo "<th>".$i."</th>";
                                        }
                                      echo "</thead>";
                                      foreach ($alertas as $key => $value) {
                                        $class="";
                                        if($key=="Máximo"){ $class="style='color:#DDDDDD'";}
                                        if($key=="Mínimo"){ $class="style='color:#DDDDDD'";}
                                        echo "<tr><td ".$class.">".$key."</td>";
                                            for($i=0;$i<=23;$i++)
                                            {
                                              if($key=="Máximo" && $stats['max_alert_hour']==$i)
                                              {
                                                $class="style='color:#FF0000;font-weight:bold;'";
                                              }elseif($key=="Mínimo" && $stats['min_alert_hour']==$i)
                                              {
                                                $class="style='color:#00BB00;font-weight:bold;'";
                                              }else {
                                                if($key=="Máximo"){ $class="style='color:#DDDDDD'";}
                                                if($key=="Mínimo"){ $class="style='color:#DDDDDD'";}
                                              }
                                              echo "<td ".$class.">".($value[$i]!=""?$value[$i]:"-")."</td>";
                                            }
                                        echo "</tr>";
                                      }
                                      echo "</table>";
                                    }
?>
</div>
<div class="col-sm-4">
  <table class='table'>
    <tr><td><i><b>Alertas</b></i></td><td class='text-right text-muted'><i>Estatísticas</i></td></tr>
    <tr><td>Último maior pico:</td><td class="text-right"><?=$stats['max_alert']." registros as ".$stats['max_alert_hour']."h";?></td></tr>
    <tr><td>Último menor pico:</td><td class="text-right"><?=$stats['min_alert']." registros as ".$stats['min_alert_hour']."h";?></td></tr>
    <tr><td>Média de alertas por hora:</td><td class="text-right"><?=ceil($stats['alert_media_diaria']/$stats['alert_media_diaria_count'])." registros/hora";?></td></tr>
  </table>
</div>

</div>

<div class='row'>
  <div class="col-sm-12">
        <h4 class='info'>Alertas de congestionamentos:</h4>
  </div>
</div>

<div class="row">
<div class="col-sm-8">
<?

                                    if(isset($jams) && count($jams))
                                    {
                                      echo "<table class='table table-striped table-condensed'>";
                                      echo "<thead><tr><th class='text-right text-muted'><i>Hora:</i></th>";
                                        for($i=0;$i<=23;$i++)
                                        {
                                          echo "<th>".$i."</th>";
                                        }
                                      echo "</thead>";
                                      foreach ($jams as $key => $value) {
                                        $class="";
                                        if($key=="Máximo"){ $class="style='color:#DDDDDD'";}
                                        if($key=="Mínimo"){ $class="style='color:#DDDDDD'";}
                                        echo "<tr><td ".$class.">".$key."</td>";
                                            for($i=0;$i<=23;$i++)
                                            {
                                              if($key=="Máximo" && $stats['max_jam_hour']==$i)
                                              {
                                                $class="style='color:#FF0000;font-weight:bold;'";
                                              }elseif($key=="Mínimo" && $stats['min_jam_hour']==$i)
                                              {
                                                $class="style='color:#00BB00;font-weight:bold;'";
                                              }else {
                                                if($key=="Máximo"){ $class="style='color:#DDDDDD'";}
                                                if($key=="Mínimo"){ $class="style='color:#DDDDDD'";}
                                              }
                                              echo "<td ".$class.">".($value[$i]!=""?$value[$i]:"-")."</td>";
                                            }
                                        echo "</tr>";
                                      }
                                      echo "</table>";
                                    }

?>
</div>
<div class="col-sm-4">
  <table class='table'>
    <tr><td><i><b>Congestionamentos</b></i></td><td class='text-right text-muted'><i>Estatísticas</i></td></tr>
    <tr><td>Último maior pico:</td><td class="text-right"><?=$stats['max_jam']." registros as ".$stats['max_jam_hour']."h";?></td></tr>
    <tr><td>Último menor pico:</td><td class="text-right"><?=$stats['min_jam']." registros as ".$stats['min_jam_hour']."h";?></td></tr>
    <tr><td>Média de alertas por hora:</td><td class="text-right"><?=ceil($stats['jam_media_diaria']/$stats['jam_media_diaria_count'])." registros/hora";?></td></tr>
  </table>
</div>
</div>

<div class='row'>
  <div class="col-sm-12">
        <h4 class='info'>Somatória dos atrasos em minutos:</h4>
  </div>
</div>
<div class='row'>
  <div class="col-sm-8">
    <?
                                        if(isset($delay) && count($delay))
                                        {
                                          echo "<table class='table table-striped table-condensed'>";
                                          echo "<thead><tr><th class='text-right text-muted'><i>Hora:</i></th>";
                                            for($i=0;$i<=23;$i++)
                                            {
                                              echo "<th>".$i."</th>";
                                            }
                                          echo "</thead>";
                                          foreach ($delay as $key => $value) {
                                            $class="";
                                            if($key=="Máximo"){ $class="style='color:#DDDDDD'";}
                                            if($key=="Mínimo"){ $class="style='color:#DDDDDD'";}
                                            echo "<tr><td ".$class.">".$key."</td>";
                                                for($i=0;$i<=23;$i++)
                                                {
                                                  if($key=="Máximo" && $stats['max_delay_hour']==$i)
                                                  {
                                                    $class="style='color:#FF0000;font-weight:bold;'";
                                                  }elseif($key=="Mínimo" && $stats['min_delay_hour']==$i)
                                                  {
                                                    $class="style='color:#00BB00;font-weight:bold;'";
                                                  }else {
                                                    if($key=="Máximo"){ $class="style='color:#DDDDDD'";}
                                                    if($key=="Mínimo"){ $class="style='color:#DDDDDD'";}
                                                  }
                                                  echo "<td ".$class.">".($value[$i]!=""?$value[$i]:"-")."</td>";
                                                }
                                            echo "</tr>";
                                          }
                                          echo "</table>";
                                        }
    ?>
  </div>
  <div class="col-sm-4">
    <table class='table'>
      <tr><td><i><b>Atrasos</b></i></td><td class='text-right text-muted'><i>Estatísticas</i></td></tr>
      <tr><td>Último maior pico:</td><td class="text-right"><?=$stats['max_delay']." minutos as ".$stats['max_delay_hour']."h";?></td></tr>
      <tr><td>Último menor pico:</td><td class="text-right"><?=$stats['min_delay']." minutos as ".$stats['min_delay_hour']."h";?></td></tr>
      <tr><td>Média de atraso por hora:</td><td class="text-right"><?=round(($stats['delay_media_diaria']/$stats['delay_media_diaria_count']),1)." minutos";?></td></tr>
    </table>
  </div>
</div>



<div class='row'>
  <div class="col-sm-12">
        <h4 class='info'>Somatória dos comprimentos de filas em quilômetro:</h4>
  </div>
</div>

<div class='row'>
  <div class="col-sm-8">
    <?
                                        if(isset($length) && count($length))
                                        {
                                          echo "<table class='table table-striped table-condensed'>";
                                          echo "<thead><tr><th class='text-right text-muted'><i>Hora:</i></th>";
                                            for($i=0;$i<=23;$i++)
                                            {
                                              echo "<th>".$i."</th>";
                                            }
                                          echo "</thead>";
                                          foreach ($length as $key => $value) {
                                            $class="";
                                            if($key=="Máximo"){ $class="style='color:#DDDDDD'";}
                                            if($key=="Mínimo"){ $class="style='color:#DDDDDD'";}
                                            echo "<tr><td ".$class.">".$key."</td>";
                                                for($i=0;$i<=23;$i++)
                                                {
                                                  if($key=="Máximo" && $stats['max_length_hour']==$i)
                                                  {
                                                    $class="style='color:#FF0000;font-weight:bold;'";
                                                  }elseif($key=="Mínimo" && $stats['min_length_hour']==$i)
                                                  {
                                                    $class="style='color:#00BB00;font-weight:bold;'";
                                                  }else {
                                                    if($key=="Máximo"){ $class="style='color:#DDDDDD'";}
                                                    if($key=="Mínimo"){ $class="style='color:#DDDDDD'";}
                                                  }
                                                  echo "<td ".$class.">".($value[$i]!=""?$value[$i]:"-")."</td>";
                                                }
                                            echo "</tr>";
                                          }
                                          echo "</table>";
                                        }
                                        //print_r_pre($stats);
    ?>
  </div>
  <div class="col-sm-4">
    <table class='table'>
      <tr><td><i><b>Comprimento de fila</b></i></td><td class='text-right text-muted'><i>Estatísticas</i></td></tr>
      <tr><td>Último maior pico:</td><td class="text-right"><?=$stats['max_length']." km as ".$stats['max_length_hour']."h";?></td></tr>
      <tr><td>Último menor pico:</td><td class="text-right"><?=$stats['min_length']." km as ".$stats['min_length_hour']."h";?></td></tr>
      <tr><td>Média dos comprimentos de fila por hora:</td><td class="text-right"><?=round(($stats['length_media_diaria']/$stats['length_media_diaria_count']),1)." Km";?></td></tr>
    </table>
  </div>
</div>

<div class='row'>
  <div class="col-sm-12">
        <h4 class='info'>Análise da hora crítica - <b><?=$stats['max_length_hour']."h";?></b>:</h4>
  </div>
</div>

<div class='row'>
  <div class="col-sm-12">
      <?
            $sql = "SELECT
              			 jams_delay,
              			 jams_length,
              			 EXTRACT(MIN FROM start_time AT TIME ZONE '-01') AS minuto_local,
                     EXTRACT(HOUR FROM start_time AT TIME ZONE '-01') AS hora_local
                  FROM
                      	waze.data_files
                  WHERE
                      	    start_time AT TIME ZONE '-01' BETWEEN '".$agora['datasrv']." ".str_pad($stats['max_length_hour'],2,"0",STR_PAD_LEFT).":00:00' AND '".$agora['datasrv']." ".str_pad($stats['max_length_hour'],2,"0",STR_PAD_LEFT).":59:59'
                  ORDER BY  start_time AT TIME ZONE '-01' ASC";
            $res = pg_query($sql)or die("SQL Error ".$sql);
            while($d = pg_fetch_assoc($res))
            {
                $vet['Atraso (Min)'][$d['minuto_local']]  = round(($d['jams_delay']/60),1);
                $vet['Comprimento (Km)'][$d['minuto_local']] = round(($d['jams_length']/1000),1);

                $hora = str_pad($d['hora_local'],2,"0",STR_PAD_LEFT).":".str_pad($d['minuto_local'],2,"0",STR_PAD_LEFT);
                $legenda[] = "'".$hora."'";
            }

            echo "<table class='table table-condensed'>";
            echo "<thead><tr><th colspan='32'>Primeira meia-hora</th></thead>";
            echo "<tbody>";
            echo "<tr><td class='text-muted'>Minuto:</td>";
            for($i=0;$i<=30;$i++){  echo "<td style='color:#DDDDDD'>".$i."</td>";}

              foreach ($vet as $k => $v) {
                echo "<tr><td class='text-muted'>".$k.":</td>";
                    for($i=0;$i<=30;$i++)
                    {
                      echo "<td>".$v[$i]."</td>";

                      if($k == "Comprimento (Km)"){ $media_comp_hora_critica += $v[$i]; }
                      if($k == "Atraso (Min)"){ $media_atra_hora_critica += $v[$i]; }
                    }

                echo "</tr>";
              }
            echo "</tbody>";
            echo "</table>";
            echo "<table class='table table-condensed'>";
            echo "<thead><tr><th colspan='32'>Segunda meia-hora</th></thead>";
            echo "<tbody>";
            echo "<tr><td style='color:#DDDDDD'>Minuto:</td>";
            for($i=31;$i<=59;$i++){  echo "<td style='color:#DDDDDD'>".$i."</td>";}

              foreach ($vet as $k => $v) {
                echo "<tr><td class='text-muted'>".$k.":</td>";
                    for($i=31;$i<=59;$i++)
                    {
                      echo "<td>".$v[$i]."</td>";

                      if($k == "Comprimento (Km)"){ $media_comp_hora_critica += $v[$i]; }
                      if($k == "Atraso (Min)"){ $media_atra_hora_critica += $v[$i]; }
                    }

                echo "</tr>";
              }
            echo "</tbody>";
            echo "</table>";


            //print_r_pre($vet);

            $serieAtraso  = "{name: 'Atraso (Min)', data: [".implode(",",$vet['Atraso (Min)'])."]}";
            $serieTamanho = "{name: 'Comprimento (Km)', data: [".implode(",",$vet['Comprimento (Km)'])."]}";


      ?>
  </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div id="graf" style="width:100%; height:500px;background-color:#efefef"></div>
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
        text: 'Análise da hora crítica'
    },
    tooltip: {
        crosshairs: [true,true],
        formatter: function() {
          return '<b>' + this.y + '</b>, '+ this.series.name + " a(s) <?=str_pad($stats['max_length_hour'],2,"0",STR_PAD_LEFT);?>:" + this.x.toString().padStart(2,"0");
        }
    },
    subtitle: {
        text: 'Basedada na hora com maior comprimento de fila - <?=str_pad($stats['max_length_hour'],2,"0",STR_PAD_LEFT);?>h'
    },

    yAxis: {
        title: {
            text: 'Comprimento de fila<br>Somatória dos atrasos'
        },
        tickInterval: 5,
        plotLines: [{
                      color: 'red', // Color value
                      dashStyle: 'solid', // Style of the plot line. Default to solid
                      value: <?=round(($media_comp_hora_critica/60),1);?>, // Value of where the line will appear
                      width: 2 // Width of the line
                    },
                    {
                      color: '#FF9000', // Color value
                      dashStyle: 'solid', // Style of the plot line. Default to solid
                      value: <?=round(($media_atra_hora_critica/60),1);?>, // Value of where the line will appear
                      width: 2 // Width of the line
                    }]
    },
    xAxis: {
        tickInterval: 1,
        categories: [<?=implode(",",$legenda);?>]
    },
    legend: {
        layout: 'vertical',
        align: 'center',
        verticalAlign: 'bottom'
    },

    plotOptions: {
        series: {
            label:{ connectorAllowed: false },
            pointStart: 0,
            marker:{ enabled: true }
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
<div class='row'>
  <div class="col-sm-12">
        <h4 class='info'>Última hora: <i><?=$agora['dthm'];?></i></h4>
  </div>
</div>
<div class='row'>
  <div class="col-sm-12">
      <?
            $sql = "SELECT
              			 jams_delay,
              			 jams_length,
              			 EXTRACT(MIN FROM start_time AT TIME ZONE '-01') AS minuto_local
                  FROM
                      	waze.data_files
                  WHERE
                      	    start_time AT TIME ZONE '-01' BETWEEN '".$agora['datasrv']." ".str_pad($agora['hora'],2,"0",STR_PAD_LEFT).":00:00' AND '".$agora['datasrv']." ".str_pad($agora['hora'],2,"0",STR_PAD_LEFT).":59:59'
                  ORDER BY  start_time AT TIME ZONE '-01' ASC";
            $res = pg_query($sql)or die("SQL Error ".$sql);
            while($d = pg_fetch_assoc($res))
            {
                $vetA['Atraso (Min)'][$d['minuto_local']]  = round(($d['jams_delay']/60),1);
                $vetA['Comprimento (Km)'][$d['minuto_local']] = round(($d['jams_length']/1000),1);
            }

            echo "<table class='table table-condensed'>";
            echo "<thead><tr><th colspan='32'>Primeira meia-hora</th></thead>";
            echo "<tbody>";
            echo "<tr><td class='text-muted'>Minuto:</td>";
            for($i=0;$i<=30;$i++){  echo "<td style='color:#DDDDDD'>".$i."</td>";}

              foreach ($vetA as $k => $v) {
                echo "<tr><td class='text-muted'>".$k.":</td>";
                    for($i=0;$i<=30;$i++)
                    {
                      echo "<td>".$v[$i]."</td>";

                      if($k == "Comprimento (Km)"){ $media_comp_hora_critica += $v[$i]; }
                      if($k == "Atraso (Min)"){ $media_atra_hora_critica += $v[$i]; }
                    }

                echo "</tr>";
              }
            echo "</tbody>";
            echo "</table>";
            echo "<table class='table table-condensed'>";
            echo "<thead><tr><th colspan='32'>Segunda meia-hora</th></thead>";
            echo "<tbody>";
            echo "<tr><td style='color:#DDDDDD'>Minuto:</td>";
            for($i=31;$i<=59;$i++){  echo "<td style='color:#DDDDDD'>".$i."</td>";}

              foreach ($vetA as $k => $v) {
                echo "<tr><td class='text-muted'>".$k.":</td>";
                    for($i=31;$i<=59;$i++)
                    {
                      echo "<td>".$v[$i]."</td>";

                      if($k == "Comprimento (Km)"){ $media_comp_hora_critica += $v[$i]; }
                      if($k == "Atraso (Min)"){ $media_atra_hora_critica += $v[$i]; }
                    }

                echo "</tr>";
              }
            echo "</tbody>";
            echo "</table>";
      ?>
    </div>
</div>


                        </div>
                  </section>
        </div>
</section>





<div class="modal fade" id="waze_modal_filtro" tabindex="-1" role="dialog" aria-labelledby="waze_modal_filtro" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Filtros de pesquisa</h5>
      </div>
      <form id="waze_form_filtro" name="waze_form_filtro" method="post" action="waze/index.php">
      <div class="modal-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                      <label for="filtro_data">Período:</label>
                          <select id="waze_filtro_data" name="waze_filtro_data" class="form-control">

                             <?
                              for($a = 2017; $a <= $agora['ano']; $a++)
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
                          <input type="hidden" id="popup_text" value="Filtrando resultado.">
                          <input type="hidden" id="popup_type" value="success">
                    </div>
                </div>
              </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        <button type="submit" class="btn btn-primary"   data-dismiss="modal" id="bt_submit">Filtrar</button>
      </div>
     </form>
    </div>
  </div>
</div>


<script>

</script>
