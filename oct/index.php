<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");



  logger("Acesso","OCT", "Sistema - página inicial");

  $agora = now();
  $sql   = "SELECT * FROM sepud.oct_workshift WHERE id_company = ".$_SESSION['id_company']." AND status = 'aberto'";
  $res   = pg_query($sql)or die("Erro ".__LINE__);

  if(pg_num_rows($res))
  {
        $turno = pg_fetch_assoc($res);
        $ano   = substr($turno['opened'],0,4);

        $sql = "SELECT
                    U.name as nome, U.id as id_user, U.registration, U.nickname,
                    WP.*
                  FROM
                          sepud.oct_rel_workshift_persona WP
                     JOIN sepud.users                      U ON U.id = WP.id_person
                  WHERE
                    WP.id_shift =  '".$turno['id']."'
                  ORDER BY WP.opened ASC, U.name ASC";
        $resRecursos = pg_query($sql)or die("Erro ".__LINE__."<br>SQL: ".$sql);
        $qtd_agentes = pg_num_rows($resRecursos);
        while($d = pg_fetch_assoc($resRecursos))
        {
          $dados_turno[$d['type']][] = $d;
        }
//echo "<pre>";
  //print_r($dados_turno);
//echo "</pre>";
        /*

        (
               [nome] => Robinson Da Maia
               [id_user] => 120
               [registration] => 45542
               [nickname] => DA MAIA
               [id_shift] => 82
               [id_person] => 120
               [id_fleet] =>
               [opened] => 2019-07-04 09:30:00
               [closed] => 2019-07-04 22:30:00
               [type] => coordenacao
               [is_driver] => f
        */
        $turno_aberto = true;
  }else {
    $turno_aberto = false;
  }
?>

<section role="main" class="content-body">
  <header class="page-header">
    <h2>Registro de Ocorrências de Trânsito e Segurança</h2>
    <div class="right-wrapper pull-right" style='margin-right:15px;'>
      <ol class="breadcrumbs">
        <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
        <li><span class='text-muted'>Aplicações</span></li>
        <li><span class='text-muted'>Gestão do sistema</span></li>
      </ol>
      <!--<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>-->
    </div>
  </header>



  <div class="col-md-12">
        <section class="panel box_shadow">
            <header class="panel-heading" style="height:70px">
                <?=$_SESSION['company_acron']." - ".$_SESSION['company_name'];?><br>
                <span class="text-muted"><small><i>Data atual:</i></small> <b><?=$agora['data'];?></b></span>
                <div class="panel-actions" style="margin-top:5px">
                    <?
                        if($turno_aberto)
                        {
                            echo "<a href='oct/turno.php?id=".$turno['id']."'><button id='bt_atualizar_turno' type='button' class='btn btn-sm btn-primary'><i class='fa fa-cogs'></i> Atualizar turno</button></a>";
                            if(isset($qtd_agentes) && $qtd_agentes > 0)
                            {
                              echo " <a href='oct/veiculo_turno_FORM.php?turno=".$turno['id']."'><button id='bt_atualizar_veiculo' type='button' class='btn btn-sm btn-info'><i class='fa fa-cab'></i> Inserir guarnições</button></a>";
                            }else {
                              echo " <button type='button' class='btn btn-sm btn-info disabled'><i class='fa fa-cab'></i> Inserir guarnições</button>";
                            }
                        }else{
                            echo "<a href='oct/turno.php'><button type='button' class='btn btn-primary'><i class='fa fa-plus'></i> Abrir novo turno de trabalho</button></a>";
                        }
                  ?>
                </div>
            </header>
            <div class="panel-body">

              <div class="row">
                <div class="col-sm-12">
                                  <?
                                      if(pg_num_rows($res))
                                      {
                                          switch ($turno['period']) {
                                            case 'alfa':
                                              $livro_class="success";
                                              break;
                                            case 'bravo':
                                              $livro_class="warning";
                                              break;
                                            case 'charlie':
                                              $livro_class="danger";
                                              break;

                                            default:
                                              $livro_class = "";
                                              break;
                                          }
                                      ?>
                                      <section class='panel panel-horizontal'>
                             <header class='panel-heading bg-success' style='width:150px'>
                               <div class='panel-heading-icon'>
                                 <i class='fa fa-cogs'></i>
                               </div>
                             </header>

                             <div class='panel-body p-lg'>
                                    <table class='table table-condensed'>
                                      <thead>
                                          <tr>
                                            <th>Turno</th>
                                            <th>Período</th>
                                            <th>Total</th>
                                            <th>Livro</th>
                                          </tr>
                                      </thead>

                                      <tbody>
                                              <tr>
                                                  <td><b><?=str_pad($turno['id'],5,"0",STR_PAD_LEFT);?></b></td>
                                                  <td><?=formataData($turno['opened'],1)." a ".formataData($turno['closed'],1);?></td>
                                                  <td><?=$qtd_agentes;?> Agentes</td>
                                                  <td class="<?=$livro_class;?>"><?="<b>".strtoupper($turno['period'])."</b>";?></td>
                                              <tr>
                                              <tr><td colspan='4'><b>Observações:</b></td></tr>
                                              <tr><td colspan='4'><?=($turno['observation']==""?"<span class='text-muted'><small>Nenhuma observação.</small></span>":$turno['observation']);?></td></tr>
                                              <tr><td colspan='4'><b>Coordenador(es):</b></td></tr>
                                              <?
                                                if(isset($dados_turno['coordenacao']))
                                                {
                                                  echo "<tr>
                                                          <th class='text-center' width='25px'><small><i>Matrícula</i></small></th>
                                                          <th><small><i>Nome</i></small></th>
                                                          <th class='text-center'><small><i>Entrada</i></small></th>
                                                          <th class='text-center'><small><i>Saída</i></small></th>
                                                         </tr>";
                                                  $coord = $dados_turno['coordenacao'];
                                                  for($i=0;$i<count($coord);$i++)
                                                  {
                                                    if($coord[$i]['opened']!=""){
                                                        $aux       = explode(" ",formataData($coord[$i]['opened'],1));
                                                        $dt_opened = "<small>".$aux[0]."</small> <b>".$aux[1]."</b>";
                                                      }else {
                                                        $dt_opened = "";
                                                      }

                                                      if($coord[$i]['closed']!=""){
                                                          $aux       = explode(" ",formataData($coord[$i]['closed'],1));
                                                          $dt_closed = "<small>".$aux[0]."</small> <b>".$aux[1]."</b>";
                                                        }else {
                                                          $dt_closed = "";
                                                        }

                                                    echo "<tr>";
                                                      echo "<td class='text-center'>".$coord[$i]['registration']."</td>";
                                                      echo "<td>".$coord[$i]['nome']."</td>";
                                                      echo "<td width='125px' class='text-center'>".$dt_opened."</td>";
                                                      echo "<td width='125px' class='text-center'>".$dt_closed."</td>";
                                                    echo "</tr>";
                                                  }
                                                }else {
                                                  echo "<tr><td colspan='4'><span class='text-muted'><small>Nenhum coordenador designado.</small></span></td></tr>";
                                                }
                                              ?>
                                      </tbody>
<!--
                                      <tfoot>
                                              <tr class=""><td colspan="4" class=""><b>Ações:</b></td></tr>
                                              <tr class="">
                                                <td colspan="4" class="">
                                                    <a href='oct/turno_sql.php?id=<?=$turno['id'];?>&acao=fechar'><button id='bt_fechar_turno'    type='button' class='btn btn-sm btn-warning'>Fechar</button></a>
                                                    <a href='oct/turno.php?id=<?=$turno['id'];?>'><button id='bt_atualizar_turno' type='button' class='btn btn-sm btn-primary'>Atualizar</button></a>
                                                </td>
                                              </tr>
                                      </tfoot>
-->
                                    </table>

                             </div>
                           </section>

                           <? }else{ ?>
                                    <section class='panel panel-horizontal'>
                                        <header class='panel-heading bg-default' style='width:150px;min-height:50px'>
                                            <div class='panel-heading-icon'><i class='fa fa-cogs'></i></div>
                                        </header>
                                        <div class='panel-body p-lg text-center'>
                                            <div class="alert alert-warning">Nenhum turno de trabalho aberto.</div>

                                        </div>
                                    </section>
                          <?  } ?>
                </div>
              </div>
                <div class="row">
                <div class="col-sm-12">
                              <section class='panel panel-horizontal'>
                              <header class='panel-heading <?=(!$turno_aberto?"bg-default":"bg-info");?>' style='width:150px;min-height:50px'>
                              <div class='panel-heading-icon'>
                              <i class='fa fa-phone-square'></i>
                              </div>
                              </header>

                              <div class='panel-body p-lg'>

                                <table class='table table-condensed'>
                                <thead><tr><th>Central de atendimento</th></tr></thead>
                                <tbody>
                                  <tr>
                                    <td style='vertical-align: middle;'>
                                      <?
                                          if(isset($dados_turno['central']))
                                          {
                                            $central = $dados_turno['central'];
                                            echo "<table class='table table-condensed'>
                                                  <thead><tr>
                                                    <th class='text-center' width='25px'><small><i>Matrícula</i></small></th>
                                                    <th><small><i>Nome</i></small></th>
                                                    <th class='text-center'><small><i>Entrada</i></small></th>
                                                    <th class='text-center'><small><i>Saída</i></small></th>
                                                  </tr>
                                            <tbody>";


                                              for($i=0;$i<count($central);$i++)
                                              {
                                                if($central[$i]['opened']!=""){
                                                    $aux       = explode(" ",formataData($central[$i]['opened'],1));
                                                    $dt_opened = "<small>".$aux[0]."</small> <b>".$aux[1]."</b>";
                                                  }else {
                                                    $dt_opened = "";
                                                  }

                                                  if($central[$i]['closed']!=""){
                                                      $aux       = explode(" ",formataData($central[$i]['closed'],1));
                                                      $dt_closed = "<small>".$aux[0]."</small> <b>".$aux[1]."</b>";
                                                    }else {
                                                      $dt_closed = "";
                                                    }

                                                echo "<tr>";
                                                  echo "<td class='text-center'>".$central[$i]['registration']."</td>";
                                                  echo "<td>".$central[$i]['nome']."</td>";
                                                  echo "<td width='125px' class='text-center'>".$dt_opened."</td>";
                                                  echo "<td width='125px' class='text-center'>".$dt_closed."</td>";
                                                echo "</tr>";
                                              }

                                            echo "</table>";
                                          }else {
                                            echo "<small class='text-muted'>Nenhum agente associado.</small>";
                                          }
                                      ?>
                                    </td>
                                  </tr>
                                </tbody>
                                </table>

                              </div>
                              </section>
                </div>
              </div>

              <div class="row">
                <div class="col-sm-12">
                                <section class='panel panel-horizontal'>
                                <header class='panel-heading <?=(!$turno_aberto?"bg-default":"bg-info");?>' style='width:150px'>
                                <div class='panel-heading-icon'>
                                <i class='fa fa-users'></i>
                                </div>
                                </header>

                                <div class='panel-body p-lg'>

                                      <table class='table table-condensed'>
                                      <thead><tr><th>Agentes designados</th></tr></thead>
                                      <tbody>
                                        <tr>
                                          <td style="vertical-align: middle;">
                                            <?
                                                if(isset($dados_turno['agente']))
                                                {
                                                    $agentes = $dados_turno['agente'];
                                                    echo "<table class='table table-condensed'>
                                                          <thead><tr>
                                                            <th class='text-center' width='25px'><small><i>Matrícula</i></small></th>
                                                            <th><small><i>Nome</i></small></th>
                                                            <th class='text-center'><small><i>Entrada</i></small></th>
                                                            <th class='text-center'><small><i>Saída</i></small></th>
                                                          </tr>
                                                    <tbody>";
                                                      unset($aux, $dt_opened, $dt_closed);
                                                      for ($i=0;$i<count($agentes);$i++)
                                                      {
                                                              if($agentes[$i]['opened']!=""){
                                                                  $aux       = explode(" ",formataData($agentes[$i]['opened'],1));
                                                                  $dt_opened = "<small>".$aux[0]."</small> <b>".$aux[1]."</b>";
                                                                }else {
                                                                  $dt_opened = "";
                                                                }

                                                                if($agentes[$i]['closed']!=""){
                                                                    $aux       = explode(" ",formataData($agentes[$i]['closed'],1));
                                                                    $dt_closed = "<small>".$aux[0]."</small> <b>".$aux[1]."</b>";
                                                                  }else {
                                                                    $dt_closed = "";
                                                                  }
                                                              echo "<tr>";
                                                                echo "<td class='text-center'>".$agentes[$i]['registration']."</td>";
                                                                echo "<td>".$agentes[$i]['nome']."</td>";
                                                                echo "<td width='125px' class='text-center'>".$dt_opened."</td>";
                                                                echo "<td width='125px' class='text-center'>".$dt_closed."</td>";
                                                              echo "</tr>";
                                                      }
                                                      echo "</tbody></table>";
                                                }else {
                                                  echo "<small class='text-muted'>Nenhum agente designado.</small>";
                                                }
                                            ?>

                                          </td>
                                        </tr>
                                      </tbody>
                                      </table>

                                </div>
                                </section>
                </div>


              </div>


              <div class="row">
                <div class="col-sm-12">
                                <section class='panel panel-horizontal'>
                                <header class='panel-heading <?=(!$turno_aberto?"bg-default":"bg-info");?>' style='width:150px'>
                                <div class='panel-heading-icon'>
                                <i class='fa fa-cab'></i>
                                </div>
                                </header>

                                <div class='panel-body p-lg'>

                                  <table class='table table-condensed'>
                                  <thead><tr><th>Guarnições</th></tr></thead>
                                  <tbody>
                                        <tr>
                                          <td style="vertical-align: middle;">
                                            <?
                                                if($turno_aberto)
                                                {
                                                    $sql = "SELECT
                                                              G.*,
                                                              F.plate, F.type, F.model, F.brand, F.nickname
                                                            FROM
                                                            	sepud.oct_garrison
                                                            	G JOIN sepud.oct_fleet F ON F.ID = G.id_fleet
                                                            WHERE
                                                            	G.id_workshift = '".$turno['id']."'";
                                                    $res = pg_query($sql)or die("Erro ".__LINE__."<br>SQL: ".$sql);;
                                                    if(pg_num_rows($res))
                                                    {
                                                        echo "<table class='table table-condensed'>
                                                              <thead>
                                                              <tr>
                                                                <td colspan='3'>&nbsp;</td>
                                                                <td colspan='2' class='text-center'>Quilometragem</td>
                                                                <td colspan='2' class='text-center'>Combustível</td>
                                                                <td colspan='2' class='text-center'>Datas</td>
                                                              </tr>
                                                              <tr>
                                                                <th>#</th>
                                                                <th>Placa</th>
                                                                <th>Veículo</th>
                                                                <th class='text-center'>Inicial</th>
                                                                <th class='text-center'>Final</th>
                                                                <th class='text-center'>Inicial</th>
                                                                <th class='text-center'>Final</th>
                                                                <th class='text-center'>Inicial</th>
                                                                <th class='text-center'>Final</th>
                                                                <th class='text-center'>Status</th>";
                                                        echo "<tbody>";
                                                        while($d = pg_fetch_assoc($res))
                                                        {
                                                            echo "<tr class='".($d['closed']==""?"success":"warning")."'>";
                                                              echo "<td>".$d['id']."</td>";
                                                              echo "<td>".$d['plate']."</td>";
                                                              echo "<td>".$d['brand']." ".$d['model']."</td>";
                                                              echo "<td class='text-center'>".$d['initial_km']."</td>";
                                                              echo "<td class='text-center'>".$d['final_km']."</td>";
                                                              echo "<td class='text-center'>".$d['initial_fuel']."</td>";
                                                              echo "<td class='text-center'>".$d['final_fuel']."</td>";
                                                              echo "<td class='text-center'>".formataData($d['opened'],1)."</td>";
                                                              echo "<td class='text-center'>".formataData($d['closed'],1)."</td>";
                                                              echo "<td class='text-center ".($d['closed']==""?"success":"warning")."'>".($d['closed']==""?"Em uso":"Baixado")."</td>";
                                                            echo "</tr>";
                                                            echo "<tr><td colspan='10'><b>Observações:<b></td></tr>";
                                                            echo "<tr><td colspan='10'>".$d['observation']."</td></tr>";


                                                            echo "<tr><td colspan='10'>";
                                                            //Seleciona os Integrantes da guarnição//
                                                            $sqlGP = "SELECT
                                                                          GP.type,
                                                                          U.name, U.nickname, U.registration
                                                                        FROM
                                                                        	sepud.oct_rel_garrison_persona GP
                                                                        	JOIN sepud.users U ON U.ID = GP.id_user
                                                                        WHERE
                                                                        	GP.id_garrison = '".$d['id']."'";

                                                            $resGP = pg_query($sqlGP)or die("Erro ".__LINE__."<br>".$sqlGP);
                                                            echo "<table class='table table-condensed table-hover'>
                                                                  <thead><tr>
                                                                    <th class='text-center' width='25px'><small><i>Matrícula</i></small></th>
                                                                    <th><small><i>Nome</i></small></th>
                                                                    <th class='text-right'><small><i>Posição</i></small></th>
                                                                  </tr>
                                                            <tbody>";
                                                            while($dGP = pg_fetch_assoc($resGP))
                                                            {
                                                              echo "<tr>";
                                                              echo "<td>".$dGP['registration']."</td>";
                                                              echo "<td>".$dGP['name']."</td>";
                                                              echo "<td class='text-right'><b>".$dGP['type']."</b></td>";
                                                              echo "</tr>";
                                                            }
                                                            echo "</tbody></table>";

                                                            echo "</td></tr>";
                                                        }
                                                        echo "</tbody></table>";
                                                    }else {
                                                        echo "<small class='text-muted'>Nenhuma guarnição criada.</small>";
                                                    }
                                                }else {
                                                  echo "<small class='text-muted'>Nenhuma guarnição criada.</small>";
                                                }
                                            ?>
                                      </td>
                                    </tr>
                                </tbody>
                            </table>

                                </div>
                                </section>
                </div>


              </div>





            </div><!--<div class="panel-body">-->
        </section><!--<section class="panel">-->
  </div><!--<div class="col-md-12">-->

</section>

<script>
$("#bt_fecsssshar_turno").click(function(){
  var url = "oct/turno_sql.php?id=<?=$turno['id'];?>&acao=fechar";
  $("#wrap").load(url);
});
</script>
