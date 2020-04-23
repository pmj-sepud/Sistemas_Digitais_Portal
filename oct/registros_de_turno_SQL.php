<?
    session_start();
    require_once("../libs/php/funcoes.php");
    require_once("../libs/php/conn.php");
    $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");

    //echo "<div class='row'><div class='col-md-6 col-md-offset-3'><pre>";

    foreach ($_POST as $var => $val)
    {
      if($var != "acao" && $var != "id" && $var != "id_workshift" && $var != "tipo_registro" && $var != "goto")
      {
          if($val ==  ""){ $_POST[$var] = "Null";      }
          else{            $_POST[$var] = "'".$val."'";}
      }
    }

    extract($_POST);

    if($_GET['acao'] == "Remover" && $_GET['id'] != "")
    {
        $sql = "DELETE FROM ".$schema."oct_workshift_history WHERE id = '".$_GET['id']."'";
        pg_query($sql)or die("Sql error ".__LINE__);
        header("Location: registros_de_turno_VIS.php?id_workshift=".$_GET['id_workshift']);
    }

    if($acao == "Atualizar")
    {
          $sql = "UPDATE ".$schema."oct_workshift_history SET
                           id_garrison = $id_garrison,
                           id_vehicle  = $id_fleet,
                           id_user     = $id_user,
                           km_initial  = $km_initial,
                           km_final    = $km_final,
                           type        = $type,
                           obs         = $observation,
                           origin      = '$tipo_registro',
                           opened      = $opened,
                           closed      = $closed
                    WHERE id = '".$id."'";
        $res =  pg_query($sql)or die("<div class='row'><div class='col-md-6 col-md-offset-3'><pre>SQL error ".__LINE__."<br>SQL: ".$sql."</div></div>");
        header("Location: registros_de_turno_FORM.php?id=".$id."&tipo_registro=".$tipo_registro."&gotoback=".$gotoback."&id_workshift=".$id_workshift);
        exit();

    }

    if($acao == "Inserir")
    {
          $sql = "INSERT INTO ".$schema."oct_workshift_history
                        (id_garrison,
                         id_vehicle,
                         id_user,
                         id_workshift,
                         km_initial,
                         km_final,
                         type,
                         obs,
                         origin,
                         opened,
                         closed)
                        VALUES(
                         $id_garrison,
                         $id_fleet,
                         $id_user,
                         $id_workshift,
                         $km_initial,
                         $km_final,
                         $type,
                         $observation,
                         '$tipo_registro',
                         $opened,
                         $closed) RETURNING id";


          $res =  pg_query($sql)or die("<div class='row'><div class='col-md-6 col-md-offset-3'><pre>SQL error ".__LINE__."<br>SQL: ".$sql."</div></div>");
          $id  = pg_fetch_assoc($res);

        //print_r_pre($_POST);
        header("Location: registros_de_turno_FORM.php?id=".$id['id']."&tipo_registro=".$tipo_registro."&gotoback=".$gotoback."&id_workshift=".$id_workshift);
        exit();

    }
/*

"id_garrison" int4,
"id_vehicle" int4,
"id_user" int4,
"obs" text COLLATE "pg_catalog"."default",
"id_workshift" int4,
"km_initial" int4,
"km_final" int4,
"type"

Veiculo:
    [id_fleet] => '9'
    [id_user] => Null
    [km_initial] => '2222'
    [km_final] => Null
    [status] => 'obs'
    [id_garrison] => '920'
    [opened] => '2019-08-09T16:01'
    [closed] => Null
    [observation] => 'teste'
    [id_workshift] => '191'
    [acao] => Inserir

Pessoa:
    [id_fleet] => Null
    [status] => 'obs'
    [km_initial] => Null
    [km_final] => Null
    [id_user] => '115'
    [id_garrison] => '920'
    [opened] => '2019-08-09T16:19'
    [closed] => '2019-08-09T18:00'
    [observation] => 'teste de inserção de pessoas'
    [id_workshift] => '191'
    [acao] => Inserir

Guarnição:
    [id_fleet] => Null
    [status] => 'lanche'
    [km_initial] => Null
    [km_final] => Null
    [id_user] => Null
    [id_garrison] => '920'
    [opened] => '2019-08-09T16:20'
    [closed] => Null
    [observation] => 'teste de inserção para guarnição...'
    [id_workshift] => '191'
    [acao] => Inserir

*/
  //print_r_pre($_POST);

  echo "</pre></div></div>";

?>
