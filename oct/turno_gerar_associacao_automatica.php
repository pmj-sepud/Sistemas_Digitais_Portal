<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");

  logger("Acesso","OCT", "Turno - Gerar associação automática");
  $agora = now();

  if($_GET['id_workshift']!=""){
    $id_workshift    = $_GET['id_workshift'];
    $sql             = "SELECT * FROM sepud.oct_workshift WHERE id = ".$id_workshift;
    $res             = pg_query($sql)or die("Erro ".__LINE__."<br>SQL: ".$sql);
    $dados_workshift = pg_fetch_assoc($res)or die("SQL Error ".__LINE__);
  }else {
    echo "Erro na passagem dos parametros.";
    exit();
  }

  /*

  (
      [id] => 141
      [opened] => 2019-07-22 00:00:00
      [closed] => 2019-07-23 23:59:00
      [id_company] => 8
      [observation] => #Teste de inserção e abertura de turno#
      [workshift_group] => Turno 1
      [status] => fechado
      [is_populate] => f
      [create_date] => 2019-07-25 08:53:58
  )
  */

  $dia_da_semana_txt[0]['completo']   = "Domingo";
  $dia_da_semana_txt[0]['abreviado']  = "Dom";

  $dia_da_semana_txt[1]['completo']   = "Segunda-feira";
  $dia_da_semana_txt[1]['abreviado']  = "Seg";

  $dia_da_semana_txt[2]['completo']   = "Terça-feira";
  $dia_da_semana_txt[2]['abreviado']  = "Ter";

  $dia_da_semana_txt[3]['completo']   = "Quarta-feira";
  $dia_da_semana_txt[3]['abreviado']  = "Qua";

  $dia_da_semana_txt[4]['completo']   = "Quinta-feira";
  $dia_da_semana_txt[4]['abreviado']  = "Qui";

  $dia_da_semana_txt[5]['completo']   = "Sexta-feira";
  $dia_da_semana_txt[5]['abreviado']  = "Sex";

  $dia_da_semana_txt[6]['completo']   = "Sábado";
  $dia_da_semana_txt[6]['abreviado']  = "Sab";



?>

<section role="main" class="content-body">
  <header class="page-header">
    <h2>Turno - Associação automática</h2>
    <div class="right-wrapper pull-right" style='margin-right:15px;'>
      <ol class="breadcrumbs">
        <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
        <li><span class='text-muted'>Aplicações</span></li>
        <li><span class='text-muted'>Turno - Associação automática</span></li>
      </ol>
      <!--<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>-->
    </div>
  </header>



  <div class="col-md-12">
        <section class="panel box_shadow">
            <header class="panel-heading" style="height:70px">
                <?=$_SESSION['company_acron']." - ".$_SESSION['company_name'];?><br>
                <span class="text-muted"><small><i>Data atual:</i></small> <b><?=$agora['data'];?></b></span>
                <div class="panel-actions text-right">
                </div>
            </header>
            <div class="panel-body">
              <?
                  extract($_GET);

                  $dia_da_semana = date('N', date2mkt(formataData($dados_workshift['opened'],1)));

                  echo     "01. ID Turno: <b>".$id_workshift."</b>";
                  echo "<br>02. Turno corrente: <b>".$dados_workshift['workshift_group']."</b>";
                  echo "<br>03. Data de abertura: <span style='font-size:18px'><b>".formataData($dados_workshift['opened'],1)."</b></span>";
                  echo "<br>03.1. Data de fechamento: <span style='font-size:18px'><b>".formataData($dados_workshift['closed'],1)."</b></span>";
                  echo "<br>04. Dia da semana do início do turno: <b>[".$dia_da_semana_txt[$dia_da_semana]['abreviado']."] ".$dia_da_semana_txt[$dia_da_semana]['completo']."</b>";
                  echo "<br>05. Orgão: <b>[".$_SESSION['id_company']."] ".$_SESSION['company_acron']." - ".$_SESSION['company_name']."</b>";
                  echo "<br>06. Preparando  colaboradores para inserção neste turno de trabalho: ";

                  $sql = "SELECT
                          		id, name, nickname, workshift_group_time_init, workshift_group_time_finish, initial_workshift_position
                          FROM
                          	sepud.users
                          WHERE
                          	id_company = '".$_SESSION['id_company']."'
                          	AND active = TRUE
                          	AND workshift_group IS NOT NULL
                          	AND workshift_group_time_init IS NOT NULL
                          	AND workshift_group_time_finish IS NOT NULL
                          	AND (workshift_group = '".$dados_workshift['workshift_group']."' OR workshift_subgroup = '".$dados_workshift['workshift_group']."')
                          ORDER BY
                          	workshift_group_time_init ASC, name ASC";
                  $res = pg_query($sql) or die("SQL error ".__LINE__);

                  if(pg_num_rows($res))
                  {
                    $c = 1;
                    echo "<b>".pg_num_rows($res)." colaboradores encontrados:</b>";
                    echo "<small class='text-muted'>";
                    while($us = pg_fetch_assoc($res))
                    {
                        $usuarios_a_incluir[$us['id']]['ini']  = substr($dados_workshift['opened'],0,10)." ".$us['workshift_group_time_init'];
                        $usuarios_a_incluir[$us['id']]['fim']  = substr($dados_workshift['opened'],0,10)." ".$us['workshift_group_time_finish'];
                        $usuarios_a_incluir[$us['id']]['type'] = ($us['initial_workshift_position']!=""?$us['initial_workshift_position']:"agente");
                        echo "<br>&nbsp;&nbsp;06.".$c++.". ".$us['workshift_group_time_init']." a ".$us['workshift_group_time_finish']." - [UID: ".$us['id']."] ".$us['name']." - Inicia como <b>".$usuarios_a_incluir[$us['id']]['type']."</b>";
                    }
                    echo "</small>";

                    echo "<br>07. Inserindo a associação do colaborador ao turno:<br><small class='text-muted'>";
                    $c=1;$total=pg_num_rows($res);
                    foreach($usuarios_a_incluir as $uid => $info)
                    {
                      $sql = "INSERT INTO sepud.oct_rel_workshift_persona(
                                          id_shift,
                                          id_person,
                                          opened,
                                          closed,
                                          type,
                                          status)
                              VALUES ('".$id_workshift."',
                                      '".$uid."',
                                      '".$info['ini']."',
                                      '".$info['fim']."',
                                      '".$info['type']."',
                                      'ativo')";
                      echo $c++."/".$total;
                      $res = pg_query($sql);
                      if($res){ echo ", ok ";}else{ echo ", <span class='text-danger'><b>, error</b></span>";}
                      echo " | ";
                    }
                    echo "</small>";
                    echo "<br>08. Bloqueando inserções automáticas futuras para este turno: ";
                    $sql = "UPDATE sepud.oct_workshift SET is_populate = true WHERE id = '".$id_workshift."'";
                    $res = pg_query($sql);
                    if($res){ echo ", <b>atualizado !</b>"; }else{ echo "<span class='text-danger'><b>, erro !</b></span>";}
                  }else {
                    echo "<hr><b>Nenhum funcionário encontrado ou configurado para este turno de trabalho<br>fim do processo...</b>";
                  }

              ?>
              <div class="row">
                <div class="col-md-12 text-center" style="margin-top:50px;margin-bottom:20px">
                  <a href='oct/turno_associar_pessoa.php?id_workshift=<?=$id_workshift;?>'><button type='button' class='btn btn-primary'><i class='fa fa-users'></i> Voltar para gestão de pessoal</button></a>
                </div>
              </div>
            </div><!--<div class="panel-body">-->
        </section><!--<section class="panel">-->
  </div><!--<div class="col-md-12">-->







</section>

<script>
</script>
