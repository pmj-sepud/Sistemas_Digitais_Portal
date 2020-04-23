<?
    session_start();
    require_once("../libs/php/funcoes.php");
    require_once("../libs/php/conn.php");
    $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");

    foreach ($_POST as $var => $val)
    {
      if($var != "acao" && $var != "id" && $var != "id_garrison" && $var != "id_fleet" && $var != "id_user" && $var != "id_workshift" && $var != "qtd_veiculos")
      {
          if($val ==  ""){ $_POST[$var] = "Null";      }
          else{            $_POST[$var] = "'".$val."'";}
      }
    }
    foreach ($_GET as $var => $val)
    {
      if($var != "acao" && $var != "id" && $var != "id_garrison" && $var != "id_fleet" && $var != "id_user" && $var != "id_workshift")
      {
          if($val ==  ""){ $_GET[$var] = "Null";      }
          else{            $_GET[$var] = "'".$val."'";}
      }
    }

    extract($_POST);
    extract($_GET);

//echo "<div class='col-md-6 col-md-offset-3'>";

if($acao == "encerrar_guarnicao")
{
    $agora = now();
    if($qtd_veiculos >= 1)
    {
          foreach ($_POST as $key => $val)
          {
              $pos = strpos($key,"_kmfinal");
              if($pos !== false)
              {
                  $aux = explode("_",$key);
                  if(count($aux)==2 && $val != "Null")
                  {
                    $veiculos++;
                    $sqlUv .= "UPDATE ".$schema."oct_rel_garrison_vehicle SET final_km = ".$val." WHERE id = '".$aux[0]."';";
                  }
              }
          }
          if($veiculos == $qtd_veiculos)//Todas as vituras que estão na guarnição devem ter seu km final preenchido//
          {

            logger("Encerrar guarnição","Guarnição USERFORM", "Encerrando a guarnição ID: ".$id_garrison.", com ".$qtd_veiculos." veículo(s) empenhado(s)");

            $sqlUv .= "UPDATE ".$schema."oct_garrison SET closed = '".$agora['datatimesrv']."' WHERE id = '".$id_garrison."';";
            pg_query($sqlUv)or die("SQL Error: ".__LINE__."<br>Query: ".$sqlUv);
          }
    }

    header("Location: guarnicao_USERFORM.php");
    exit();
}

if($acao == "nova_guarnicao")
{
  $agora = now();
  $name = "'alfa'";
  $vet_guar = array(97  => "alfa",   98  => "bravo",    99  => "charlie", 100 => "delta",  101 => "echo",    102 => "fox",
                    103 => "golf",   104 => "hotel",    105 => "india",   106 => "juliet", 107 => "kilo",    108 => "lima",
                    109 => "mike",   110 => "november", 111 => "oscar",   112 => "papa",   113 => "quebec",  114 => "romeo",
                    115 => "sierra", 116 => "tango",    117 => "uniform", 118 => "victor", 119 => "whiskey", 120 => "xray",
                    121 => "yankee", 122 => "zulu");

  $sql = "SELECT G.name FROM ".$schema."oct_garrison G  WHERE G.id_workshift = '".$id_workshift."' ORDER BY G.id DESC LIMIT 1";
  $res = pg_query($sql)or die("SQL error ".__LINE__."<br>Query: ".$sql);
  $aux = pg_fetch_assoc($res);
  if($aux['name']!="")
  {
    $cod_ascii = ord(strtolower($aux['name'][0]));
    $cod_ascii++;
    if($cod_ascii >= 123){ $cod_ascii = 97;}
    $name = "'".$vet_guar[$cod_ascii]."'";
  }
  $sql = "INSERT INTO ".$schema."oct_garrison(
                      id_workshift,
                      opened,
                      observation,
                      name)
          VALUES ($id_workshift,
                 '".$agora['datatimesrv']."',
                  $obs,
                  $name)RETURNING id";

    $res = pg_query($sql)or die("SQL Error ".__LINE__);
    $aux = pg_fetch_assoc($res);
    $id_garrison = $aux['id'];

    if($id_fleet != "" && $id_garrison != "")//Guarnicao com veículo
    {
      $sql = "INSERT INTO ".$schema."oct_rel_garrison_vehicle
                          (id_fleet, initial_km, id_garrison)
                  VALUES($id_fleet, $initial_km, $id_garrison)RETURNING id";
      $res = pg_query($sql)or die("SQL Error ".__LINE__);
      $aux = pg_fetch_assoc($res);
      $id_rel_garrison_vehicle = $aux['id'];

      $sql = "INSERT INTO ".$schema."oct_rel_garrison_persona
              (id_garrison, id_user, type, id_rel_garrison_vehicle)
              VALUES ($id_garrison, $id_user, $type, $id_rel_garrison_vehicle)";
      pg_query($sql)or die("SQL Error ".__LINE__);
    }else{ //Guarnição sem veículo
      $sql = "INSERT INTO ".$schema."oct_rel_garrison_persona
              (id_garrison, id_user)
              VALUES ($id_garrison, $id_user)";
      pg_query($sql)or die("SQL Error ".__LINE__);
    }
    logger("Criando nova guarnição","Guarnição USERFORM", "Criando uma nova guarnição");
    header("Location: guarnicao_USERFORM.php");
}

  if($acao == "inserir_user_guarnicao_existente")
  {
     $sql = "INSERT INTO ".$schema."oct_rel_garrison_persona(id_garrison, id_user, id_rel_garrison_vehicle, type)
                                                VALUES(   $id_garrison,
                                                          $id_user,
                                                          $id_rel_garrison_vehicle,
                                                          $posicao)";

   //logger("Associar agente ao veiculo","OCT - Guarnição", "Guarnição ID:".$id_garrison.".<br>Dados: ".$postvars);
   pg_query($sql)or die("SQL error ".__LINE__."<br>Query: ".$sql);

   logger("Entrando numa guarnição existente","Guarnição USERFORM", "Guarnição criada");
   header("Location: guarnicao_USERFORM.php");
   exit();
  }

  if($acao == "encerrar_guarnicao_a_pe")
  {
      $agora = now();
      $sql   = "UPDATE ".$schema."oct_garrison SET closed = '".$agora['datatimesrv']."' WHERE id = '".$id_garrison."'";
      pg_query($sql)or die("SQL Error ".__LINE__);
      logger("Encerrar guarnição","Guarnição USERFORM", "Encerrando a guarnição a pé ID: ".$id_garrison);
      header("Location: guarnicao_USERFORM.php");
      exit();
  }
//echo "</div>";

?>
