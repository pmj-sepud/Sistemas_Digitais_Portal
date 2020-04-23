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
                $tipos = "traffic,alerts,irregularities";
                $url; // WAZE URL get from .env vars
                $json = file_get_contents($url);
                $obj = json_decode($json);

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



                print_r_pre($stats);
                print_r_pre($stats_info);
                $sql = makeSql("waze.rev1_data_files", $stats, "ins", "id");
                //print_r_pre($sql);

                //$res       = pg_query($sql)or die("SQL Error: ".$sql);
                $res         = @pg_query($sql);
                if(is_resource($res))
                {
                  $aux         = pg_fetch_assoc($res);
                  $datafile_id = $aux['id'];
                }

                print_r_pre("DATAFILE_ID: ".$datafile_id);

if($datafile_id!='')
{
                print_r_pre("<h4>ALERTAS:</h4>");
                $inserts = 0;
                for($i=0;is_array($alertas) && $i<count($alertas);$i++)
                {
                  $array                       = json_decode(json_encode($alertas[$i]), True);
                  $dttmp                       = mkt2date($alertas[$i]->pubMillis/1000);
                  $array['pub_utc_date']       = $dttmp['datatimesrv_utc'];

                  $array['location']           = json_encode($array['location']);
                  $array['thumbs_up']          = $array['nThumbsUp'];
                  $array['pub_millis']         = $array['pubMillis'];
                  $array['road_type']          = $array['roadType'];
                  $array['report_rating']      = $array['reportRating'];
                  $array['datafile_id']        = $datafile_id;
                  $array['id']                 = md5(json_encode($array));
                  $array['report_description'] = $array['reportDescription'];

                  unset($array['nThumbsUp'],$array['pubMillis'],$array['roadType'],$array['reportRating'],$array['reportDescription']);
                  $sql = makeSql("waze.rev1_alerts", $array, "ins");
                  pg_query($sql)or die("SQL Error: ".$sql);
                  $inserts++;
                  //print_r_pre($array);

                  /*
                  report_description
                  jam_uuid
                  datafile_id
                  */

                }
                print_r_pre("Inseriu: ".$inserts);
                //Irregularidades//
                print_r_pre("<h4>IRREGULARIDADES:</h4>");
                $inserts = 0;
                for($i=0;is_array($irregul) && $i<count($irregul);$i++)
                {
                        $array                          = json_decode(json_encode($irregul[$i]), True);
                                                          unset($array['alerts']);
                        $array['uuid']                  = $array['id'];
                        $array['line']                  = json_encode($array['line']);
                        $array['id']                    = md5(json_encode($array));
                        $array['detection_date_millis'] = $array['detectionDateMillis'];
                        $array['detection_date']        = $array['detectionDate'];
                        $array['update_date_millis']    = $array['updateDateMillis'];
                        $array['update_date']           = $array['updateDate'];
                        $array['update_utc_date']       = $array['updateDate'];
                        $array['is_highway']            = $array['highway'];
                        $array['regular_speed']         = $array['regularSpeed'];
                        $array['delay_seconds']         = $array['delaySeconds'];
                        $array['jam_level']             = $array['jamLevel'];
                        $array['drivers_count']         = $array['driversCount'];
                        $array['alerts_count']          = $array['alertsCount'];
                        $array['n_thumbs_up']           = $array['nThumbsUp'];
                        $array['n_comments']            = $array['nComments'];
                        $array['n_images']              = $array['nImages'];
                        $array['end_node']              = $array['endNode'];
                        $array['start_node']            = $array['startNode'];
                        $array['datafile_id']           = $datafile_id;

                        unset($array['detectionDateMillis'],$array['detectionDate'],$array['updateDateMillis'],$array['updateDate'],$array['highway'],$array['regularSpeed'],$array['delaySeconds'],$array['jamLevel'],$array['driversCount'],$array['alertsCount'],$array['nThumbsUp'],$array['nComments'],$array['nImages'],$array['endNode'],$array['startNode']);
                        /*
                          id
                          uuid
                          detection_date_millis
                          detection_date
                          detection_utc_date
                          update_date_millis
                          update_date
                          **update_utc_date**
                          street
                          city
                          country
                          is_highway
                          speed
                          regular_speed
                          delay_seconds
                          seconds
                          length
                          trend
                          type
                          severity
                          jam_level
                          drivers_count
                          alerts_count
                          n_thumbs_up
                          n_comments
                          n_images
                          line
                          cause_type
                          **start_node
                          end_node
                          datafile_id
                        */

                        //print_r_pre($array);
                        /*
                        Array
                        (
                            [id] => 185603324 //-->uuid
                            [detectionDateMillis] => 1579013901377
                            [detectionDate] => Tue Jan 14 14:58:21 +0000 2020 //-->detection_utc_date
                            [updateDateMillis] => 1579014584504
                            [updateDate] => Tue Jan 14 15:09:44 +0000 2020
                            [street] => BR-101 S
                            [city] => Joinville
                            [country] => BR
                            [highway] => 1
                            [speed] => 10.33
                            [regularSpeed] => 92.8
                            [delaySeconds] => 428
                            [seconds] => 487
                            [length] => 1399
                            [trend] => 1
                            [type] => Medium
                            [severity] => 5
                            [jamLevel] => 4
                            [driversCount] => 91
                            [alertsCount] => 2
                            [nThumbsUp] => 0
                            [nComments] => 0
                            [nImages] => 0
                            [line] => Array( [0] => Array( [x] => -48.857216 [y] => -26.370263))
                            [endNode] => BR-101 S






                        )
                        */
                        print_r_pre($array);
                        $sql = makeSql("waze.rev1_irregularities", $array, "ins");
                        pg_query($sql)or die("SQL Error: ".$sql);
                        print_r_pre($sql);
                        $inserts++;
                }
                print_r_pre("Inseriu: ".$inserts);
                //Congestonamentos//
                print_r_pre("<h4>CONGESTIONAMENTOS:</h4>");
                $inserts = 0;
                for($i=0;is_array($congest) && $i<count($congest);$i++)
                {
                    $array                      = json_decode(json_encode($congest[$i]), True);
                    $array['line']              = json_encode($array['line']);
                    $array['datafile_id']       = $datafile_id;
                    $array['id']                = md5(json_encode($array));
                    $array['pub_millis']        = $array['pubMillis'];
                    $array['end_node']          = $array['endNode'];
                    $array['start_node']        = $array['startNode'];
                    $array['road_type']         = $array['roadType'];
                    $array['speed_kmh']         = $array['speedKMH'];
                    $array['turn_type']         = $array['turnType'];


                    $dttmp                  = mkt2date($array['pub_millis']/1000);
                    $array['pub_utc_date']  = $dttmp['datatimesrv_utc'];

                    $array['blocking_alert_id'] = $array['blockingAlertUuid'];
                    unset($array['segments'], $array['pubMillis'],$array['endNode'],$array['speedKMH'],$array['turnType'],$array['blockingAlertUuid'],$array['roadType'],$array['startNode']);
                    //print_r_pre($array);
                    $sql = makeSql("waze.rev1_jams", $array, "ins");
                    //print_r_pre($sql);
                    pg_query($sql)or die("SQL Error: ".$sql);
                    $inserts++;
                  /*

                      id
                      uuid
                      pub_millis
                      **pub_utc_date
                      start_node
                      end_node
                      road_type
                      street
                      city
                      country
                      delay
                      speed
                      speed_kmh
                      length
                      turn_type
                      level
                      blocking_alert_id
                      line
                      type
                      **turn_line
                      datafile_id

                      [id] => ccbdbecbb89433e9c51ca235d2f04d37
                      [uuid] => 1886390807
                      [pubMillis] => 1578683206871
                      [endNode] => Av. Paulo Schroeder
                      [roadType] => 1
                      [street] => Est. Motucas
                      [city] => Joinville
                      [country] => BR
                      [delay] => 128
                      [speed] => 7.7027777777778
                      [speedKMH] => 27.73
                      [length] => 1905
                      [turnType] => NONE
                      [blockingAlertUuid] => 24c81c50-fc59-3365-b8cf-29c479758d3f
                      [level] => 2
                      [line] => [{"x":-48.93218,"y":-26.266376},{"x":-48.932325,"y":-26.263609}]
                      [type] => NONE


                      [datafile_id] => 521025





                  )
                  */

                }
                print_r_pre("Inseriu: ".$inserts);
}else {
  print_r_pre("Arquivo de cabeçalho não gerado, nem inserções de alertas, irregularidades e congestionamentos");
}


function makeSql($table, $fieldvals, $type, $returning="")
{
    switch ($type) {
      case 'ins':
              foreach ($fieldvals as $key => $val)
              {
                $keys[] = $key;
                $vals[] = ($val!=""?"'".$val."'":"Null");
              }

              $sql = "INSERT INTO ".$table." (".implode(", ", $keys).") VALUES (".implode(", ",$vals).") ".($returning!=""?"RETURNING ".$returning:"");
      break;
      case 'upd':
              foreach ($fieldvals as $key => $val)
              {
                if($val!="")
                {
                  $upds[] = $key."='".$val."'";
                }else {
                  $upds[] = $key."=Null";
                }
              }
              if($returning != "")
              {
                $sql = "UPDATE ".$table." SET ".implode(", ",$upds)." WHERE ".$returning;
              }
      break;

      default:
        break;
    }
  return $sql;
}

            ?>

        </div>
  </div>

</section>
<script>
</script>
