<?
    session_start();
    require_once("../libs/php/funcoes.php");
    require_once("../libs/php/conn.php");

    extract($_POST);

    if($acao == "inserir" && $name != "")
    {
      if($id_vehicle  != ""){ $id_vehicle  = "'".$id_vehicle."'";  }else{ $id_vehicle  = "Null"; }
      if($description != ""){ $description = "'".$description."'"; }else{ $description = "Null"; }
      if($age         != ""){ $age         = "'".$age."'";         }else{ $age         = "Null"; }
      if($refuse_help != ""){ $refuse_help = "'".$refuse_help."'"; }else{ $refuse_help = "Null"; }
      if($state       != ""){ $state       = "'".$state."'";       }else{ $state       = "Null"; }

      $sql = "INSERT INTO sepud.oct_victim
                          (name,
                           id_events,
                           age,
                           description,
                           genre,
                           state,
                           id_vehicle,
                           position_in_vehicle,
                           refuse_help,
                           rg,
                           cpf,
                           mother_name,
                           conducted)
                  VALUES ('".$name."',
                          '".$id."',
                          ".$age.",
                          ".$description.",
                          '".$genre."',
                          ".$state.",
                          ".$id_vehicle.",
                          '".$position_in_vehicle."',
                          ".$refuse_help.",
                          '".$rg."',
                          '".$cpf."',
                          '".$mother_name."',
                          '".$conducted."')";
      pg_query($sql)or die("Erro ".__LINE__."<br>SQL: ".$sql);

      /*

      SQL: INSERT INTO sepud.oct_victim (name, id_events, age, description, genre, state, id_vehicle, position_in_vehicle, refuse_help, rg, cpf, mother_name, conducted) VALUES ('Jonathan', '13139', Null, Null, 'Masc', 'Nada', Null, '', 't', '', '', 'Sniecikoski', '')
      */

      logger("Inserção","OCT - Vítima", "Ocorrência n.".$id);

      if($retorno_acao == "continuar"){ header("Location: FORM_vitima.php?id_workshift=".$id_workshift."&id=".$id);}
      else                            { header("Location: FORM.php?id=".$id);       }


      exit();
    }

    if($acao == "atualizar" && $name != "")
    {
      if($id_vehicle != "") { $id_vehicle  = "'".$id_vehicle."'";  }else{ $id_vehicle  = "Null"; }
      if($description != ""){ $description = "'".$description."'"; }else{ $description = "Null"; }
      if($age != "")        { $age         = "'".$age."'";         }else{ $age         = "Null"; }
      if($refuse_help != ""){ $refuse_help = "'".$refuse_help."'"; }else{ $refuse_help = "Null"; }
      if($state       != ""){ $state       = "'".$state."'";       }else{ $state       = "Null"; }

      $sql = "UPDATE sepud.oct_victim SET
                     name                = '".$name."',
                     age                 = ".$age.",
                     description         = ".$description.",
                     genre               = '".$genre."',
                     state               = ".$state.",
                     id_vehicle          = ".$id_vehicle.",
                     position_in_vehicle = '".$position_in_vehicle."',
                     refuse_help         = ".$refuse_help.",
                     rg                  = '".$rg."',
                     cpf                 = '".$cpf."',
                     mother_name         = '".$mother_name."',
                     conducted           = '".$conducted."'
              WHERE id = '".$victim_sel."'";
      pg_query($sql)or die("Erro ".__LINE__);

      logger("Atualização","OCT - Vítima", "Ocorrência n.".$id.", ID: ".$victim_sel);

      if($retorno_acao == "continuar"){ header("Location: FORM_vitima.php?id_workshift=".$id_workshift."&id=".$id);}
      else                            { header("Location: FORM.php?id=".$id);       }
      exit();
    }

    extract($_GET);
    if($acao == "remover")
    {

      $sql  = "SELECT * FROM sepud.oct_victim WHERE id = '".$victim_sel."'";
      $res  = pg_query($sql)or die("Erro ".__LINE__);
      $d    = pg_fetch_assoc($res);
      $vit  = print_r($d,true);

      $sql  = "DELETE FROM sepud.oct_victim WHERE id = '".$victim_sel."'";

      logger("Remoção","OCT - Vítima", "Ocorrência n.".$id.", Dados: ".$vit);

      pg_query($sql)or die("Erro ".__LINE__);
      header("Location: FORM_vitima.php?id_workshift=".$id_workshift."&id=".$id);
      exit();
    }
?>
