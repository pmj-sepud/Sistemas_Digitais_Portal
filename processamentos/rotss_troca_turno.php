<?
  //Script para ser colocado na agenda de execuçao automatica cron a zero hora de todos os dias
  //Realiza a checagem se existe o turno do proximo dia com status INATIVO e altera para ATIVO.
  //O turno anterior é colocado como fechado. Se houver mais de um turno pra mesma data com
  //o status de inativo o sistema não realiza a troca de turno.


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
  echo "\n-------------------------------------------";
  echo "\nTroca de turno automatica.";
  echo "\n-------------------------------------------";

  $agora = now();


  $sql = "SELECT
          	C.name as company_name,
          	W.id, W.opened, W.id_company, W.status
          FROM
          	sepud.oct_workshift W
          JOIN sepud.company C ON C.id = W.id_company
          WHERE
          	status = 'inativo' AND opened BETWEEN '".$agora['datasrv']." 00:00:00' AND '".$agora['datasrv']." 23:59:59'";

  $res = pg_query($sql)or die("\nSQL error ".__LINE__."\n\n");

  while($d = pg_fetch_assoc($res))
  {
    $turnos[$d['id_company']][] = $d;
    $company[$d['id_company']] = $d['company_name'];
  }

  if(isset($turnos))
  {
        foreach ($turnos as $id_company => $turno){
          echo "\n > ".$company[$id_company].", ".count($turno)." turno encontrado.";
          if(count($turno)==1)
          {
              //Checagem se ha turno anterior aberto//
              echo "\n   Baixando turno anterior/posterior que esteja em aberto";
              $sql = "UPDATE sepud.oct_workshift
                        SET status = 'fechado'
                      WHERE
                      	id_company = '".$id_company."'
                      	AND status = 'aberto'
                      	AND opened NOT BETWEEN '".$agora['datasrv']." 00:00:00' AND '".$agora['datasrv']." 23:59:59'";
              pg_query($sql)or die("SQL error ".__LINE__);
              echo ", feito.";

              echo "\n   Atualizando status do novo turno para aberto";
              $sql = "UPDATE sepud.oct_workshift SET status = 'aberto' WHERE id = '".$turno[0]['id']."'";
              pg_query($sql)or die("SQL error ".__LINE__);
              echo ", feito.";
          }else{
            echo " (Nada a fazer, mais de um turno configurado para esta data)";
          }
        }
  }else {
    echo "\nNenhum turno INATIVO encontrado.";
  }

  echo "\nVerificando se ha turno antigo em ABERTO.";
  $sql = "UPDATE sepud.oct_workshift SET status = 'fechado' WHERE status = 'aberto' AND closed < '".$agora['datasrv']." 00:00:00'";
  $res = pg_query($sql) or die("\nError ".__LINE__);
  echo "\n".pg_affected_rows($res)." turnos baixados";

  echo "\n\n\n\n-------------------------------------------";
  echo "\nFim do processamento.\n\n";
