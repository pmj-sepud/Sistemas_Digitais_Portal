<?
session_start();
require_once("../libs/php/funcoes.php");
require_once("../libs/php/conn.php");


$agora = now();

$postvars = print_r($_POST,true);
$getvars  = print_r($_GET,true);

foreach ($_POST as $var => $val)
{
  if($var != "acao" && $var != "id" && $var != "id_workshift" && $var != "id_garrison")
  {
      if($val ==  ""){ $_POST[$var] = "Null";      }
      else{            $_POST[$var] = "'".$val."'";}
  }
}

extract($_POST);
extract($_GET);
echo "<div class='row'><div class='col-sm-6 col-sm-offset-3'>";

  if($acao == "Inserir") //Inserir os dados gerais da guarnição//
  {
    $name = "'alfa'";
    $vet_guar = array(97  => "alfa",   98  => "bravo",    99  => "charlie", 100 => "delta",  101 => "echo",    102 => "fox",
                      103 => "golf",   104 => "hotel",    105 => "india",   106 => "juliet", 107 => "kilo",    108 => "lima",
                      109 => "mike",   110 => "november", 111 => "oscar",   112 => "papa",   113 => "quebec",  114 => "romeo",
                      115 => "sierra", 116 => "tango",    117 => "uniform", 118 => "victor", 119 => "whiskey", 120 => "xray",
                      121 => "yankee", 122 => "zulu");

    $sql = "SELECT G.name FROM sepud.oct_garrison G  WHERE G.id_workshift = '".$id_workshift."' ORDER BY G.id DESC LIMIT 1";
    $res = pg_query($sql)or die("SQL error ".__LINE__."<br>Query: ".$sql);
    $aux = pg_fetch_assoc($res);
    if($aux['name']!="")
    {
      $cod_ascii = ord(strtolower($aux['name'][0]));
      $cod_ascii++;
      if($cod_ascii >= 123){ $cod_ascii = 97;}
      $name = "'".$vet_guar[$cod_ascii]."'";
    }

    $sql = "INSERT INTO sepud.oct_garrison(name, opened, closed, id_workshift, observation)
            VALUES(
                $name,
                $opened,
                $closed,
                $id_workshift,
                $observation)RETURNING id";
    $res = pg_query($sql)or die("SQL error ".__LINE__."<br>Query: ".$sql);
    $aux = pg_fetch_assoc($res);

    logger("Inserção","OCT - Guarnição", "Guarnição ID:".$aux['id'].".<br>Dados: ".$postvars);
    header("Location: guarnicao_FORM.php?id_garrison=".$aux['id']."&id_workshift=".$id_workshift);
    exit();

  }

  if($acao == "Atualizar") //Atualizar os dados gerais da guarnição//
  {
    $sql = "UPDATE sepud.oct_garrison SET
                    name        = $name,
                    opened      = $opened,
                    closed      = $closed,
                    observation = $observation
            WHERE id = '".$id_garrison."'";
    $res = pg_query($sql)or die("SQL error ".__LINE__."<br>Query: ".$sql);
    $aux = pg_fetch_assoc($res);
    logger("Atualização","OCT - Guarnição", "Guarnição ID:".$id_garrison.".<br>Dados: ".$postvars);
    header("Location: guarnicao_FORM.php?id_garrison=".$id_garrison."&id_workshift=".$id_workshift);
    exit();

  }

  if($acao == "Remover") //Remove a guarnição e todas as associações//
  {
    $sql   = "SELECT * FROM sepud.oct_garrison WHERE id = '".$id_garrison."'";
    $res   = pg_query($sql)or die("Erro sql ".__LINE__);
    $dados = pg_fetch_assoc($res);

    $sql = "DELETE FROM sepud.oct_garrison                      WHERE id          = '".$id_garrison."';
            DELETE FROM sepud.oct_rel_garrison_persona          WHERE id_garrison = '".$id_garrison."';
            DELETE FROM sepud.oct_rel_garrison_vehicle          WHERE id_garrison = '".$id_garrison."';
            UPDATE      sepud.oct_events SET id_garrison = null WHERE id_garrison = '".$id_garrison."';";
    $res  = pg_query($sql)or die("SQL error ".__LINE__."<br>Query: ".$sql);
    $aux  = pg_fetch_assoc($res);
    logger("Remoção","OCT - Guarnição", "Guarnição ID:".$id_garrison.".<br>Dados: ".print_r($dados,true));
    header("Location: index.php?id_workshift=".$id_workshift);
    exit();

  }

  if($acao == "associar_veiculo")
  {
    $sql = "INSERT INTO sepud.oct_rel_garrison_vehicle(id_fleet, id_garrison, initial_km, final_km, obs)
            VALUES ($id_fleet, $id_garrison, $initial_km, $final_km, $obs)";
    pg_query($sql)or die("SQL error ".__LINE__."<br>Query: ".$sql);
    logger("Associar veículo","OCT - Guarnição", "Guarnição ID:".$id_garrison.".<br>Dados: ".$postvars);
    header("Location: guarnicao_FORM.php?id_garrison=".$id_garrison."&id_workshift=".$id_workshift);
    exit();
  }

  if($acao == "atualizar_veiculo")
  {
    $sql = "UPDATE sepud.oct_rel_garrison_vehicle
               SET
                  initial_km = $initial_km,
                  final_km   = $final_km,
                  obs        = $obs
            WHERE id         = '".$id."'";
    logger("Atualizar veículo","OCT - Guarnição", "Guarnição ID:".$id_garrison.".<br>Dados: ".$postvars);
    pg_query($sql)or die("SQL error ".__LINE__."<br>Query: ".$sql);
    header("Location: guarnicao_FORM.php?id_garrison=".$id_garrison."&id_workshift=".$id_workshift);
    exit();
  }
  if($acao == "remover_veiculo")
  {
    $sql  = "UPDATE sepud.oct_rel_garrison_persona SET id_rel_garrison_vehicle = null WHERE id_rel_garrison_vehicle = '".$id."';";
    $sql .= "DELETE FROM sepud.oct_rel_garrison_vehicle WHERE id = '".$id."';";
    logger("Remover veículo","OCT - Guarnição", "Guarnição ID:".$id_garrison.".<br>Dados: ".$getvars);
    pg_query($sql)or die("SQL error ".__LINE__."<br>Query: ".$sql);
    header("Location: guarnicao_FORM.php?id_garrison=".$id_garrison."&id_workshift=".$id_workshift);
    exit();

  }
  if($acao == "associar_agente_e_veiculo")
  {

      $sql = "INSERT INTO sepud.oct_rel_garrison_persona(id_garrison, id_user, id_rel_garrison_vehicle, type)
                                                  VALUES('".$id_garrison."',
                                                            $id_user,
                                                            $id_rel_garrison_vehicle,
                                                            $type)";

     logger("Associar agente ao veiculo","OCT - Guarnição", "Guarnição ID:".$id_garrison.".<br>Dados: ".$postvars);
     pg_query($sql)or die("SQL error ".__LINE__."<br>Query: ".$sql);
     header("Location: guarnicao_FORM.php?id_garrison=".$id_garrison."&id_workshift=".$id_workshift);
     exit();
  }
  if($acao == "remover_pessoa")
  {
    $sql = "DELETE FROM sepud.oct_rel_garrison_persona WHERE id = '".$id."';";
    logger("Remover pessoa","OCT - Guarnição", "Guarnição ID:".$id_garrison.".<br>Dados: ".$getvars);
    pg_query($sql)or die("SQL error ".__LINE__."<br>Query: ".$sql);
    header("Location: guarnicao_FORM.php?id_garrison=".$id_garrison."&id_workshift=".$id_workshift);
    exit();

  }


echo "</div></div>";



?>
