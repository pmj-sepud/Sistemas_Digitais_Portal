<?
session_start();
require_once("../libs/php/funcoes.php");
require_once("../libs/php/conn.php");


?>
<style>
.select2-selection__rendered {
line-height: 32px !important;
}

.select2-selection {
height: 34px !important;
}
</style>
<section role="main" class="content-body">

  <header class="page-header">
    <h2>Página para testes de scripts</h2>
    <div class="right-wrapper pull-right" style='margin-right:15px;'>
      <ol class="breadcrumbs">
        <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
        <li><span>Configurações</span></li>
        <li><span>Desenvolvimento</span></li>
      </ol>
    </div>
  </header>

  <div class="row">
        <div class="col-md-12">
<?
              //waze data retrive//
              $agora = now();
              $tipos = "traffic,alerts,irregularities";
              $url; // WAZE URL get from .env vars
              $json  = file_get_contents($url);
              $obj   = json_decode($json);

              $alertas = $obj->alerts;
              $congest = $obj->jams;
              $irregul = $obj->irregularities;

              $stats["start_time_millis"] = (date2mkt(formataData(str_replace(":000","",$obj->startTime),1)))*1000;
              $stats["end_time_millis"]   = (date2mkt(formataData(str_replace(":000","",$obj->endTime),1)))*1000;
              $stats["start_time"]        = str_replace(":000","",$obj->startTime);
              $stats["end_time"]          = str_replace(":000","",$obj->endTime);
              $stats['date_created']      = $agora['datatimesrv'];
              $stats['date_updated']      = $agora['datatimesrv'];

              $stats['file_name']        = "processo_interno";
              $stats['json_hash']        =  md5(json_encode($obj));


              $stats['total_alerts']         = $stats_info["alertas"]           = (is_array($alertas)?count($alertas):"0");
              $stats['total_jams']           = $stats_info["congestionamentos"] = (is_array($congest)?count($congest):"0");
              $stats['total_irregularities'] = $stats_info["irregularidades"]   = (is_array($irregul)?count($irregul):"0");

              for($i=0;$i<count($alertas);$i++){ // $stats_info["total_alertas"];
                $array                  = json_decode(json_encode($alertas[$i]), True);
                //$stats_info['total_alertas'][$array['type']]['total']++;
                $stats_info['tipo_alertas'][$array['type']][($array['subtype']!=""?$array['subtype']:$array['type'])]++;
              }

              for($i=0;$i<count($congest);$i++){
                $array                  = json_decode(json_encode($congest[$i]), True);
                if($array['length']>0){ $stats['jams_legth'] += $array['length'];    }
                if($array['delay'] >0){ $stats['jams_delay']  += $array['delay']; }
              }
              print_r_pre($congest);
?>
        </div>
  </div>

</section>
<script>
</script>
