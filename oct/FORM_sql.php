<?
    error_reporting(E_ALL);
    session_start();
    require_once("../libs/php/funcoes.php");
    require_once("../libs/php/conn.php");

    extract($_POST);

    $datafinal = $data." ".$hora;


    if($acao == "inserir" && $userid != "" && $_SESSION['id_company'])
    {

      if($id_street       == ""){ $id_street      ="Null"; }else{ $id_street       = "'".$id_street."'";     }
      if($street_number   == ""){ $street_number  ="Null"; }else{ $street_number   = "'".$street_number."'"; }
      if($id_workshift    == ""){ $id_workshift   ="Null"; }else{ $id_workshift    = "'".$id_workshift."'";  }
      if($id_addressbook  == ""){ $id_addressbook ="Null"; }else{ $id_addressbook  = "'".$id_addressbook."'";}
      if($id_garrison     == ""){ $id_garrison    ="Null"; }else{ $id_garrison     = "'".$id_garrison."'";   }
      if($victim_inform   == ""){ $victim_inform  ="0";    }else{ $victim_inform   =     $victim_inform;     }
      if($region          == ""){ $region         ="Null"; }else{ $region          = "'".$region."'";        }


        $sql = "INSERT INTO sepud.oct_events
                    (date,
                     description,
                     address_reference,
                     address_complement,
                     geoposition,
                     id_event_type,
                     status,
                     victim_inform,
                     id_user,
                     id_street,
                     street_number,
                     id_company,
                     id_workshift,
                     requester,
                     requester_phone,
                     id_addressbook,
                     id_garrison,
                     region)
              VALUES ('".$datafinal."',
                      '".$description."',
                      '".$endereco."',
                      '".$endereco_complemento."',
                      '".$coordenadas."',
                      '".$tipo_oc."',
                      '".$initial_status."',
                      '".$victim_inform."',
                      '".$userid."',
                      ".$id_street.",
                      ".$street_number.",
                      '".$_SESSION['id_company']."',
                      ".$id_workshift.",
                      '".$requester."',
                      '".$requester_phone."',
                      ".$id_addressbook.",
                      ".$id_garrison.",
                      ".$region.") returning id";

        $res = pg_query($sql) or die("Erro ".__LINE__."SQL: ".$sql);
        $aux = pg_fetch_assoc($res);

        logger("Inserção","OCT", "Ocorrência n.".$aux['id']);

        header("Location: FORM.php?id=".$aux['id']);
        exit();
    }


    if($acao == "atualizar" && $userid != "" && $id != "")
    {
        if(isset($_POST['condicoes']))
        {
          $sqlCond = "DELETE FROM sepud.oct_rel_events_event_conditions WHERE id_events = '".$id."';";
          for($i = 0;$i < count($_POST['condicoes']); $i++)
          {
              $sqlCond .= " INSERT INTO sepud.oct_rel_events_event_conditions (id_events, id_event_conditions) VALUES ('".$id."', '".$_POST['condicoes'][$i]."');";
          }
        }

        if($id_street       == ""){ $id_street      ="Null"; }else{ $id_street       = "'".$id_street."'";     }
        if($street_number   == ""){ $street_number  ="Null"; }else{ $street_number   = "'".$street_number."'"; }
        if($id_addressbook  == ""){ $id_addressbook ="Null"; }else{ $id_addressbook  = "'".$id_addressbook."'";}
        if($id_garrison     == ""){ $id_garrison    ="Null"; }else{ $id_garrison     = "'".$id_garrison."'";   }
        if($id_workshift    == ""){ $id_workshift   ="Null"; }else{ $id_workshift    = "'".$id_workshift."'";  }
        if($closure         != ""){ $sql_closure    = ",closure = '".$closure."'";  }else{ $sql_closure    = ",closure = Null"; }
        if($arrival         != ""){ $sql_arrival    = ",arrival = '".$arrival."'";  }else{ $sql_arrival    = ",arrival = Null";}
        if($on_way          != ""){ $sql_on_way     = ",on_way = '".$on_way."'";    }else{ $sql_on_way     = ",on_way = Null";}
        if($victim_inform   == ""){ $victim_inform  ="Null"; }else{ $victim_inform = "'".$victim_inform."'";  }
        if($region          == ""){ $region         ="Null"; }else{ $region        = "'".$region."'";         }

        $sql = "UPDATE sepud.oct_events SET
                     date               = '".$datafinal."',
                     description        = '".$description."',
                     address_reference  = '".$endereco."',
                     address_complement = '".$endereco_complemento."',
                     geoposition        = '".$coordenadas."',
                     id_event_type      = '".$tipo_oc."',
                     status             = '".$status."',
                     victim_inform      = ".$victim_inform.",
                     id_street          = ".$id_street.",
                     street_number      = ".$street_number.",
                     requester          = '".$requester."',
                     requester_phone    = '".$requester_phone."',
                     id_addressbook     = ".$id_addressbook.",
                     id_garrison        = ".$id_garrison.",
                     id_workshift       = ".$id_workshift.",
                     region             = ".$region."
                     ".$sql_closure."
                     ".$sql_arrival."
                     ".$sql_on_way."
               WHERE id                 = '".$id."'";

        pg_query($sqlCond.$sql) or die("Erro ".__LINE__."<br>SQL: ".$sql);

        logger("Atualização","OCT", "Ocorrência n.".$id);

        header("Location: FORM.php?id=".$id);
        exit();
    }


if($_GET['status_acao'] == "atualizar" && $_GET['id'] != "")
{
    $agora = now();
    switch ($_GET['status_alterar']) {
      case 'ab':
        $var_status = "Aberta";
        $sqlU = "UPDATE sepud.oct_events SET active = true, closure = null, arrival = null,  status = '".$var_status."', on_way = null, date = '".$agora['datasrv']." ".$agora['hms']."' WHERE id = '".$_GET['id']."'";
        break;
      case 'in':
        $var_status = "Inativa";
        $sqlU = "UPDATE sepud.oct_events SET active = true, closure = null, arrival = null,  status = '".$var_status."', on_way = null WHERE id = '".$_GET['id']."'";
        break;
      case 'd':
        $var_status = "Em deslocamento";
        $sqlU = "UPDATE sepud.oct_events SET active = true, closure = null, arrival = null,  status = '".$var_status."', on_way = '".$agora['datasrv']." ".$agora['hms']."' WHERE id = '".$_GET['id']."'";
        break;
      case 'a':
        $var_status = "Em atendimento";
        $sqlU = "UPDATE sepud.oct_events SET active = true, closure = null, status = '".$var_status."', arrival = '".$agora['datasrv']." ".$agora['hms']."' WHERE id = '".$_GET['id']."'";
        break;
      case 'e':
        $var_status = "Encaminhamento";
        $sqlU = "UPDATE sepud.oct_events SET active = true, closure = null,  status = '".$var_status."' WHERE id = '".$_GET['id']."'";
        break;
      case 'f':
        $var_status = "Ocorrência terminada";
        $sqlU = "UPDATE sepud.oct_events SET status = '".$var_status."', active = false, closure = '".$agora['datasrv']." ".$agora['hms']."' WHERE id = '".$_GET['id']."'";
        break;

      case 'ce':
        $var_status = "Ocorrência cancelada - Evadido/Não localizado";
        $sqlU = "UPDATE sepud.oct_events SET status = '".$var_status."', active = false, closure = '".$agora['datasrv']." ".$agora['hms']."' WHERE id = '".$_GET['id']."'";
        break;

      case 'ct':
        $var_status = "Ocorrência cancelada - trote";
        $sqlU = "UPDATE sepud.oct_events SET status = '".$var_status."', active = false, closure = '".$agora['datasrv']." ".$agora['hms']."' WHERE id = '".$_GET['id']."'";
        break;

      case 'cc':
        $var_status = "Ocorrência cancelada - Central de atendimento";
        $sqlU = "UPDATE sepud.oct_events SET status = '".$var_status."', active = false, closure = '".$agora['datasrv']." ".$agora['hms']."' WHERE id = '".$_GET['id']."'";
        break;

      case 'cs':
        $var_status = "Ocorrência cancelada - Sem recurso";
        $sqlU = "UPDATE sepud.oct_events SET status = '".$var_status."', active = false, closure = '".$agora['datasrv']." ".$agora['hms']."' WHERE id = '".$_GET['id']."'";
        break;

      default:
        $var_status = "Em atendimento";
        $sqlU = "UPDATE sepud.oct_events SET active = true, closure = null, status = '".$var_status."' WHERE id = '".$_GET['id']."'";
        break;
    }

     pg_query($sqlU)or die("<span class='text-center'>Erro ".__LINE__."<br>".$sqlU);

     logger("Atualização de status","OCT", "Ocorrência n.".$_GET['id']." - ".$var_status);

     header("Location: FORM.php?id=".$_GET['id']);
}
?>
