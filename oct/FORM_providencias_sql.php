<?
    session_start();
    require_once("../libs/php/funcoes.php");
    require_once("../libs/php/conn.php");
    $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");

    extract($_POST);
    $agora = now();


    if($acao == "inserir" && isset($_SESSION['id']))
    {

      $id_vehicle    = ($id_vehicle    != "" ? $id_vehicle              :"Null");
      $id_victim     = ($id_victim     != "" ? $id_victim               :"Null");
      $id_hospital   = ($id_hospital   != "" ? $id_hospital             :"Null");
      $id_company    = ($id_company    != "" ? $id_company              :"Null");
      $id_garrison   = ($id_garrison   != "" ? $id_garrison             :"Null");
      $id_user_resp  = ($id_user_resp  != "" ? $id_user_resp            :"Null");


      if($data != "" && $hora != "" && strlen($hora)==5 && strlen($data)==10){
        $dtsrv = formataData($data,4);
        if(strlen($hora)==5)
        {
          $dtsrv .= " ".$hora.":00";
        }
      }else {
        $dtsrv = $agora['datatimesrv'];
      }


        $sql = "INSERT INTO ".$schema."oct_rel_events_providence
                          (id_owner,
                           id_vehicle,
                           id_victim,
                           id_hospital,
                           id_company_requested,
                           observation,
                           id_event,
                           id_providence,
                           opened_date,
                           id_garrison,
                           id_user_resp)
             VALUES
	                      ( ".$_SESSION['id'].",
                          $id_vehicle,
                          $id_victim,
                          $id_hospital,
                          $id_company,
                          '".$description."',
                          '".$id."',
                          '".$id_providence_type."',
                          '".$dtsrv."',
                          $id_garrison,
                          $id_user_resp);";
      pg_query($sql)or die("<div align='center'>Erro ".__LINE__."<br>".$sql."<br></br><a class='btn' href='oct/FORM_providencias.php?id=".$id."'>Voltar</a></div>");

      logger("Inserção","OCT - Providências", "Ocorrência n.".$id);

      if($retorno_acao == "continuar"){ header("Location: FORM_providencias.php?id_workshift=".$id_workshift."&id=".$id); }
      else                            { header("Location: FORM.php?id=".$id);                                             }
      exit();
    }



    if($acao == "atualizar" && $data != "" && $hora != "")
    {
          $id_vehicle    = ($id_vehicle    != "" ? $id_vehicle              :"Null");
          $id_victim     = ($id_victim     != "" ? $id_victim               :"Null");
          $id_hospital   = ($id_hospital   != "" ? $id_hospital             :"Null");
          $id_company    = ($id_company    != "" ? $id_company              :"Null");
          $id_garrison   = ($id_garrison   != "" ? $id_garrison             :"Null");
          $id_user_resp  = ($id_user_resp  != "" ? $id_user_resp            :"Null");

          $sql = "UPDATE ".$schema."oct_rel_events_providence SET
               id_vehicle           = $id_vehicle,
               id_victim            = $id_victim,
               id_hospital          = $id_hospital,
               id_company_requested = $id_company,
               observation          = '".$description."',
               id_providence        = '".$id_providence_type."',
               opened_date          = '".$data." ".$hora.":00',
               id_garrison          = $id_garrison,
               id_user_resp         = $id_user_resp
        WHERE id = '".$id_providence."'";
        pg_query($sql)or die("<div align='center'>Erro ".__LINE__."<br>".$sql."<br></br><a class='btn' href='oct/FORM_providencias.php?id_workshift=".$id_workshift."&id=".$id."'>Voltar</a></div>");


        if($retorno_acao == "continuar"){ header("Location: FORM_providencias.php?id_providence=".$id_providence."&id_workshift=".$id_workshift."&id=".$id); }
        else                            { header("Location: FORM.php?id=".$id);                                             }
        exit();
    }


    extract($_GET);
    if($acao == "remover")
    {
      $sql  = "SELECT * FROM ".$schema."oct_rel_events_providence WHERE id = '".$id_providence."'";
      $res  = pg_query($sql)or die("Erro ".__LINE__);
      $d    = pg_fetch_assoc($res);
      $prov = print_r($d,true);
      $sql = "DELETE FROM ".$schema."oct_rel_events_providence WHERE id = '".$id_providence."'";
      pg_query($sql)or die("Erro ".__LINE__);
      logger("Remoção","OCT - Providências", "Ocorrência n.".$id.", Dados: ".$prov);
      header("Location: FORM_providencias.php?turno=".$id_workshift."&id=".$id);
      exit();
    }


    header("Location: FORM_providencias.php?id_providence=".$id_providence."&id_workshift=".$id_workshift."&id=".$id);
?>
