<?
    session_start();
    require_once("../libs/php/funcoes.php");
    require_once("../libs/php/conn.php");

    extract($_POST);


    if($acao == "inserir" && (($license_plate_letters && $license_plate_numbers != "") || $license_plate_numbers_mercosul != ""))
    {

      $agora     = now();
      //////////////////////////////////////////////////////////
      //Bloqueios de inserção após o horario final de operação//
      //Dia de semana após as 18:30
      if(($agora['dia_semana']>=1 && $agora['dia_semana']<=5) && (($agora['hora']>=18 && $agora['min']>=30) || $agora['hora']>=19))
      {
        header("Location: app_FORM.php?erro=fora_do_horario");
        exit();
      }
      //Sabado após as 13h ou domingo o dia todo
      if(($agora['dia_semana']==6 && $agora['hora']>13) || $agora['dia_semana']==7)
      {
        header("Location: app_FORM.php?erro=fora_do_horario");
        exit();
      }
      //////////////////////////////////////////////////////////

      //Registro dia de semana antes das 08:30 (Seg a Sex - 8:30 a 18:30)
      if(($agora['dia_semana']>=1 && $agora['dia_semana']<=5) && (($agora['hora']<=8 && $agora['min']<=29) || $agora['hora']<=7))
      {
        $agora['datatimesrv'] = $agora['datasrv']." 08:30:00";
      }

      //Registro SÁBADO antes das 08:00 (Sab - 8:00 a 13:00)
      if($agora['dia_semana']==6 && $agora['hora']<=7)
      {
        $agora['datatimesrv'] = $agora['datasrv']." 08:00:00";
      }




      $timestamp = $agora['datatimesrv'];
      if($license_plate_numbers != ""){ $placa = $license_plate_letters.$license_plate_numbers;         }
      else                            { $placa = $license_plate_numbers_mercosul;}

      //checagem se a vaga não esta ocupada//
      if($multi_parking == "false") //Se a vaga não permite multiplos veiculos, baixa o anterior (caso exista)
      {
          $sql = "UPDATE sepud.eri_schedule_parking
                  SET closed = true, closed_timestamp = '".$agora['datatimesrv']."', id_user_closed = '".$_SESSION['id']."', obs = obs || ' - BAIXADO: outro veículo nesta vaga.'
                  WHERE id_parking = '".$id_parking."' AND closed_timestamp is null AND timestamp >= '".$agora['datasrv']." 00:00:00'";
          pg_query($sql)or die("Erro ".__LINE__."<br>SQL: ".$sql);
      }

      //Se a placa estiver ativa em outra vaga, também realiza a baixa
      $sql = "UPDATE sepud.eri_schedule_parking
              SET closed = true, closed_timestamp = '".$agora['datatimesrv']."', id_user_closed = '".$_SESSION['id']."', obs = obs || ' - BAIXADO: veiculo encontrado em outra vaga.'
              WHERE licence_plate = '".$placa."' AND id_parking != '".$id_parking."' AND closed_timestamp is null AND timestamp >= '".$agora['datasrv']." 00:00:00'";
      pg_query($sql)or die("Erro ".__LINE__."<br>SQL: ".$sql);


      $sql = "INSERT INTO sepud.eri_schedule_parking
                        (id_parking,
                         timestamp,
                         id_user,
                         licence_plate,
                          obs)
             VALUES
      	               (".$id_parking.",
                        '".$timestamp."',
                        ".$id_user.",
                        '".$placa."',
                        '".$obs."') RETURNING id";
      $res = pg_query($sql)or die("Erro ".__LINE__."<br>SQL: ".$sql);
      $aux = pg_fetch_assoc($res);

      logger("Inserção","SERP - Registro","Novo registro, ID: ".$aux['id'].", Placa do veículo: ".$placa);

      header("Location: app_FORM.php?id=".$aux['id']);
      exit();
    }

    extract($_GET);

    if($acao == "notificar" && $id != "")
    {
        $agora = now();
        logger("Notificação","SERP - Registro","Registro ID: ".$id);


          $sql = "SELECT
                  			 PT.type as parking_type, PT.time as parking_time, PT.observation as parking_desc,
                  			 P.name as parking_name, P.description as parking_obs, P.id_street, P.area,
                  			 SP.*
                  FROM sepud.eri_schedule_parking SP
                  JOIN sepud.eri_parking P ON P.id = SP.id_parking
                  JOIN sepud.eri_parking_type PT ON PT.id = P.id_parking_type
                  WHERE SP.id = '".$id."'";
          $res = pg_query($sql)or die("SQL error ".__LINE__);
          $dados = pg_fetch_assoc($res);
          //print_r_pre($dados);

          $date               = $agora['datatimesrv'];
          $arrival            = $agora['datatimesrv'];
          $on_way             = $agora['datatimesrv'];
          $closure            = $agora['datatimesrv'];
          $initial_status     = "Ocorrência terminada";
          $userid             = $_SESSION['id'];
          $id_street          = $dados['id_street'];
          $id_company         = $_SESSION['id_company'];
          $requester          = "Registro SERP: ".$dados['id'];
          $address_complement = "Vaga: ".$dados['parking_name']." - ".$dados['area'];

          $description  = "# Ocorrência inserida automaticamente a partir do sistema SERP #";
          $description .= "\nVaga: ".$dados['parking_name']." - ".$dados['area'];
          $description .= "\nTipo: ".$dados['parking_type'];
          $description .= "\nTempo máximo de ocupação: ".$dados['parking_time']." minutos";
          $description .= "\n----------------------------------------------------\nInformações da notificação:";
          $description .= "\nRegistro SERP: ".number_format($dados['id'],0,'','.');
          $description .= "\nPlaca: ".$dados['licence_plate'];
          $description .= "\nEntrada: ".formataData($dados['timestamp'],1);
          $description .= "\nNotificado: ".formataData($agora['datatimesrv'],1);

          switch ($dados['area']) {
            case 'area1':
              $id_event_type = 584; //Fiscalização Estacionamento Rotativo - Área 1
              break;
            case 'area2':
              $id_event_type = 585; //Fiscalização Estacionamento Rotativo - Área 2
              break;
            case 'area3':
              $id_event_type = 586; //Fiscalização Estacionamento Rotativo - Área 3
              break;
            case 'area4':
              $id_event_type = 587; //Fiscalização Estacionamento Rotativo - Área 4
              break;
            case 'area5':
              $id_event_type = 588; //Fiscalização Estacionamento Rotativo - Área 5
              break;
            case 'area6':
              $id_event_type = 589; //Fiscalização Estacionamento Rotativo - Área 6
              break;
            case 'area7':
              $id_event_type = 590; //Fiscalização Estacionamento Rotativo - Área 7
              break;
            case 'area8':
              $id_event_type = 591; //Fiscalização Estacionamento Rotativo - Área 8
              break;
            case 'area9':
              $id_event_type = 592; //Fiscalização Estacionamento Rotativo - Área 9
              break;
            case 'area10':
              $id_event_type = 593; //Fiscalização Estacionamento Rotativo - Área 10
              break;
            default:
              $id_event_type = 554; //Fiscalização Estacionamento Rotativo
              break;
          }


          if($id_company!=""){
                $sql_ws   = "SELECT id FROM sepud.oct_workshift WHERE id_company = ".$_SESSION['id_company']." AND status = 'aberto'";
                $res      = pg_query($sql_ws)or die("SQL Error ".__LINE__);
                if(pg_num_rows($res))
                {
                  $dados_ws     = pg_fetch_assoc($res);
                  $id_workshift = $dados_ws['id'];
                }else{
                  $id_workshift = "Null";
                }
          }else{ $id_workshift = "Null"; }

          if($id_workshift != "Null" && $userid != "")
          {
              $sql_gar = "SELECT P.id_garrison FROM sepud.oct_rel_garrison_persona P
                          JOIN sepud.oct_garrison G ON G.id = P.id_garrison AND G.closed is null
                          WHERE id_user = '".$userid."' LIMIT 1";
              $res     = pg_query($sql_gar)or die("SQL Error ".__LINE__);
              if(pg_num_rows($res))
              {
                $dados_gar   = pg_fetch_assoc($res);
                $id_garrison = $dados_gar['id_garrison'];
              }else
              {
                $id_garrison  = "Null";
              }
          }else{
            $id_garrison  = "Null";
          }


          $sql = "INSERT INTO sepud.oct_events
                      (active,
                       date,
                       arrival,
                       on_way,
                       closure,
                       description,
                       id_event_type,
                       status,
                       id_user,
                       id_street,
                       id_company,
                       id_workshift,
                       id_garrison,
                       requester,
                       address_complement)
                VALUES ('f',
                        '".$date."',
                        '".$arrival."',
                        '".$on_way."',
                        '".$closure."',
                        '".$description."',
                        '".$id_event_type."',
                        '".$initial_status."',
                        '".$userid."',
                        ".$id_street.",
                        '".$id_company."',
                        ".$id_workshift.",
                        ".$id_garrison.",
                        '".$requester."',
                        '".$address_complement."') returning id";


            $res = pg_query($sql)or die("SQL Error ".__LINE__."<br>Query: ".$sql);
            $aux = pg_fetch_assoc($res);
            if($aux['id']!="")
            {
              //Atualização do campo de controle do SERP//
              $sql_id_oct_event = "id_oct_event = '".$aux['id']."', ";

              //Inserção do veículo na ocorrência//
              $sqlv = "INSERT INTO sepud.oct_vehicles(
                                    description,
                                    id_events,
                                    licence_plate,
                                    data_rec_auto,
                                    auto_id_user)
                            VALUES('Não identificado',
                                    '".$aux['id']."',
                                    '".$dados['licence_plate']."',
                                    '".$date."',
                                    '".$userid."')";
              pg_query($sqlv)or die("SQL error ".__LINE__."<br>Query: ".$sqlv);
            }


        $sql = "UPDATE sepud.eri_schedule_parking
                SET ".$sql_id_oct_event." notified = true, notified_timestamp = '".$agora['datatimesrv']."', id_user_notified = '".$_SESSION['id']."'
                WHERE id = '".$id."'";
        pg_query($sql)or die("Erro ".__LINE__."<br>SQL: ".$sql);


        header("Location: app_FORM.php?id=".$id);
        exit();
    }

    if($acao == "guinchar" && $id != "")
    {
        $agora = now();
        $sql = "UPDATE sepud.eri_schedule_parking
                SET winch_timestamp = '".$agora['datatimesrv']."', id_user_winch = '".$_SESSION['id']."'
                WHERE id = '".$id."'
                RETURNING id_oct_event";
        $res = pg_query($sql)or die("Erro ".__LINE__."<br>SQL: ".$sql);
        $aux = pg_fetch_assoc($res);

        if($aux['id_oct_event']!="")
        {
            $sqlu = "UPDATE sepud.oct_events
                     SET description = description || '\nGuincho: ".$agora['dthms']."' WHERE id = '".$aux['id_oct_event']."';";

            //id_providence = 11 ==> Guincho solicitado//
            $sqlp = "INSERT INTO sepud.oct_rel_events_providence
						         (opened_date, id_owner, id_vehicle, observation, id_event, id_providence)
                     VALUES
							        ('".$agora['datatimesrv']."', '".$_SESSION['id']."',
                      (SELECT id FROM sepud.oct_vehicles WHERE id_events = '".$aux['id_oct_event']."' ORDER BY id LIMIT 1)
                      , 'Solicitado através do sistema SERP', '".$aux['id_oct_event']."', '11');";
            pg_query($sqlu.$sqlp)or die("SQL Error ".__LINE__."<br>Query: ".$sqlu."<br>Query: ".$sqlp);
        }

        logger("Guinchamento","SERP - Registro","Registro ID: ".$id);
        header("Location: app_FORM.php?id=".$id);
        exit();
    }

    if($acao == "baixar" && $id != "")
    {
        $agora = now();
        $sql = "UPDATE sepud.eri_schedule_parking
                SET closed = true, closed_timestamp = '".$agora['datatimesrv']."', id_user_closed = '".$_SESSION['id']."'
                WHERE id = '".$id."'";
        pg_query($sql)or die("Erro ".__LINE__."<br>SQL: ".$sql);

        logger("Baixa","SERP - Registro","Registro ID: ".$id);

        header("Location: app_FORM.php?id=".$id);
        exit();
    }

      header("Location: app_FORM.php");
      exit();
?>
