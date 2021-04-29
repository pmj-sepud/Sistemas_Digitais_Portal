<?
session_start();

$basedir = "/var/www/html";
$envfile = ".env";
if(file_exists($basedir."/".$envfile))
{
  $arq = file($basedir."/".$envfile);
  for($i=0;$i<count($arq);$i++)
  {
    putenv($arq[$i]);
  }
}

require_once("../libs/php/funcoes.php");
require_once("../libs/php/conn.php");

system("clear");
echo "\n#==========================================#";
echo "\n|   Aquisicao dos dados do WAZE            |";
echo "\n#==========================================#";

echo "\n\n> Iniciando processo de importacao:";

                //waze data retrive//
                $agora   = now();
                $url     = base64_decode(getenv("WAZE_URL"));
                $json    = file_get_contents($url);
                $obj     = json_decode($json);
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

                $stats['jams_length'] = $stats['jams_delay'] = 0;

                for($i=0;$i<count($alertas);$i++){
                  $array                  = json_decode(json_encode($alertas[$i]), True);
                  $stats_info['tipo_alertas'][$array['type']][($array['subtype']!=""?$array['subtype']:$array['type'])]++;
                }

                for($i=0;$i<count($congest);$i++){
                  $array                  = json_decode(json_encode($congest[$i]), True);
                  if($array['length']>0){ $stats['jams_length'] += $array['length'];    }
                  if($array['delay'] >0){ $stats['jams_delay']  += $array['delay']; }
                }

                $stats['json_alerts_type'] = json_encode($stats_info['tipo_alertas']);

echo "\nCabecalho:\n";
                print_r($stats);
echo "\nEstatisticas:\n";
                print_r($stats_info);
                $sql = makeSql("waze.data_files", $stats, "ins", "id");


                $res       = pg_query($sql)or die("SQL Error: ".$sql);
                if(is_resource($res))
                {
                  $aux         = pg_fetch_assoc($res);
                  $datafile_id = $aux['id'];
                  echo "\nCabecalho gerado: ID ".$datafile_id;
                }


if(isset($datafile_id))
{

echo "\n\n###################\nProcessando ALERTAS: ";
                $inserts = 0;
                for($i=0;is_array($alertas) && $i<count($alertas);$i++)
                {
                  $array                       = json_decode(json_encode($alertas[$i]), True);
                  if($array['city']=="Joinville"){
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
                        $sql = makeSql("waze.alerts", $array, "ins");
                        @pg_query($sql);
                        $inserts++;
                        echo "\n> [".$inserts."] Processado !";
                  }else{ echo "\n> Cidade nao informada ou incorreta => ".utf8_decode($array['city']); }

                }
echo "\n".$inserts." registros inseridos\n";
                //Irregularidades//
echo "\n\n###################\nProcessando IRREGULARIDADES: ";
                $inserts = 0;
                for($i=0;is_array($irregul) && $i<count($irregul);$i++)
                {
                        $array                          = json_decode(json_encode($irregul[$i]), True);
                        if($array['city']=="Joinville")
                        {
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
                                $array['end_node']              = str_replace("'","",$array['endNode']);
                                $array['start_node']            = str_replace("'","",$array['startNode']);
                                $array['datafile_id']           = $datafile_id;

                                unset($array['detectionDateMillis'],$array['detectionDate'],$array['updateDateMillis'],$array['updateDate'],$array['highway'],$array['regularSpeed'],$array['delaySeconds'],$array['jamLevel'],$array['driversCount'],$array['alertsCount'],$array['nThumbsUp'],$array['nComments'],$array['nImages'],$array['endNode'],$array['startNode']);

                                $sql = makeSql("waze.irregularities", $array, "ins");

                                @pg_query($sql);
                                $inserts++;
                                echo "\n> [".$inserts."] Processado !";
                            }else{ echo "\n> Cidade nao informada ou incorreta => ".utf8_decode($array['city']); }
                }
echo "\n".$inserts." registros inseridos.\n";
                //Congestonamentos//
echo "\n\n###################\nProcessando CONGESTIONAMENTOS: ";
                $inserts = 0;
                for($i=0;is_array($congest) && $i<count($congest);$i++)
                {

                    $array                      = json_decode(json_encode($congest[$i]), True);
                    if($array['city']=="Joinville")
                    {
                        $array['line']              = json_encode($array['line']);
                        $array['datafile_id']       = $datafile_id;
                        $array['id']                = md5(json_encode($array));
                        $array['pub_millis']        = $array['pubMillis'];
                        $array['street']            = str_replace("'","",$array['street']);
                        $array['end_node']          = str_replace("'","",$array['endNode']);
                        $array['start_node']        = str_replace("'","",$array['startNode']);
                        $array['road_type']         = $array['roadType'];
                        $array['speed_kmh']         = $array['speedKMH'];
                        $array['turn_type']         = $array['turnType'];

                        $jam_stats['jam_street']        = str_replace("'","",$array['street']);
                        $jam_stats['jam_speedkmh']      = $array['speedKMH'];
                        $jam_stats['jam_length']        = $array['length'];
                        $jam_stats['jam_delay']         = $array['delay'];
                        $jam_stats['jam_timestamp_utc'] = $stats["start_time"];


                        //echo "\n==============================================================\n";
                        ///  print_r($array);
                        //echo "\n==============================================================\n";

                        $dttmp                  = mkt2date($array['pub_millis']/1000);
                        $array['pub_utc_date']  = $dttmp['datatimesrv_utc'];

                        $array['blocking_alert_id'] = $array['blockingAlertUuid'];
                        unset($array['segments'], $array['pubMillis'],$array['endNode'],$array['speedKMH'],$array['turnType'],$array['blockingAlertUuid'],$array['roadType'],$array['startNode']);
                        $sql = makeSql("waze.jams", $array, "ins");

                        @pg_query($sql);
                        $inserts++;

                        $sqlS = makeSql("waze.stats", $jam_stats, "ins");
                        @pg_query($sqlS);
                        echo "\n> [".$inserts."] Processado !";
                  }else{ echo "\n> Cidade nao informada ou incorreta => ".utf8_decode($array['city']);}

                }
echo "\n".$inserts." registros inseridos\n";
}else{
  echo "\n\nArquivo de cabeçalho não gerado, nem inserções de alertas, irregularidades e congestionamentos\n";
}

//
echo "\n\n-------------------- WAZE DATAFILE --------------------------\n\n";
print_r($obj);
echo "\n\n-------------------- WAZE DATAFILE --------------------------\n\n";

echo "\n\n\n";
