<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");



  logger("Acesso","OCT", "Sistema - página inicial");

  $agora = now();

  if($_GET['id_workshift']=="")
  {
      $sql   = "SELECT * FROM sepud.oct_workshift WHERE id_company = ".$_SESSION['id_company']." AND status = 'aberto'";
  }else {
      $sql   = "SELECT * FROM sepud.oct_workshift WHERE id ='".$_GET['id_workshift']."'";
  }


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
                  ORDER BY WP.opened ASC";
        $resRecursos = pg_query($sql)or die("Erro ".__LINE__."<br>SQL: ".$sql);

        while($d = pg_fetch_assoc($resRecursos))
        {
          if($d['type']=="agente")
          {
            if($d['status']=="ativo" ||
               $d['status']=="HE-Compensação" ||
               $d['status']=="Serviços")
            {
              $agentes_turno['ativos'][] = $d;
            }else{
              $agentes_turno['outros'][] = $d;
            }
          }
          $dados_turno[$d['type']][] = $d;
          $qtd_agentes[] = $d["id_user"];
        }

        if(isset($qtd_agentes) && count($qtd_agentes)){ $qtd_agentes = count(array_values(array_unique($qtd_agentes))); }else{ $qtd_agentes = 0;}

        $turno_aberto = true;

        $sqlOc = "SELECT
                  	E.active, count(*) AS qtd
                  FROM
                  	sepud.oct_events E
                  WHERE
                  	id_workshift = '".$turno['id']."'
                  GROUP BY
                  	E.active";
        $resOc = pg_query($sqlOc)or die("SQL error ".__LINE__);
        $status_ocs['abertas'] = $status_ocs['fechadas'] = $status_ocs['total'] = 0;
        while($dOc = pg_fetch_assoc($resOc))
        {
          if($dOc['active']=='t'){ $status_ocs['abertas'] = $dOc['qtd']; }else{ $status_ocs['fechadas'] = $dOc['qtd']; }
          $status_ocs['total'] += $dOc['qtd'];
        }

  }else{
    $$turno_aberto = false;
  }
?>
<style>
.pulse {
  box-shadow: 0 0 0 rgba(0,0,255, 1);
  animation: pulse 3s infinite;
}
.pulse:hover {
  animation: none;
}

@-webkit-keyframes pulse {
  0% {
    -webkit-box-shadow: 0 0 0 0 rgba(240, 173, 78, 1);
  }
  70% {
      -webkit-box-shadow: 0 0 0 20px rgba(240, 173, 78, 0);
  }
  100% {
      -webkit-box-shadow: 0 0 0 0 rgba(240, 173, 78, 0);
  }
}
@keyframes pulse {
  0% {
    -moz-box-shadow: 0 0 0 0 rgba(240, 173, 78, 1);
    box-shadow: 0 0 0 0 rgba(240, 173, 78, 1);
  }
  70% {
      -moz-box-shadow: 0 0 0 15px rgba(240, 173, 785, 0);
      box-shadow: 0 0 0 15px rgba(240, 173, 78, 0);
  }
  100% {
      -moz-box-shadow: 0 0 0 0 rgba(240, 173, 785, 0);
      box-shadow: 0 0 0 0 rgba(240, 173, 78, 0);
  }
}
</style>
<section role="main" class="content-body">
  <header class="page-header hidden-print">
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

                      if($turno['id']!="")
                      {
                            echo " <a href='oct/turno.php?id=".$turno['id']."'><button id='bt_atualizar_turno' type='button' class='btn btn-primary'><i class='fa fa-cogs'></i> Turno <sup>(nº ".$turno['id'].")</sup></button></a>";
                            echo " <a href='oct/turno_associar_pessoa.php?id_workshift=".$turno['id']."'><button type='button' class='btn btn-primary'><i class='fa fa-users'></i> Pessoal</button></a>";

                            if(isset($qtd_agentes) && $qtd_agentes > 0)
                            {
                                    if($_SESSION['id']==1){
                                        echo " <a href='oct/guarnicao_FORM.php?id_workshift=".$turno['id']."'><button id='bt_atualizar_veiculo' type='button' class='btn btn-warning'><i class='fa fa-cab'></i> Guarnições v2</button></a>";
                                    }else{
                                      echo " <a href='oct/veiculo_turno_FORM.php?turno=".$turno['id']."'><button id='bt_atualizar_veiculo' type='button' class='btn btn-primary'><i class='fa fa-cab'></i> Guarnições</button></a>";
                                    }


                                    echo "<style>";
                                    echo ".panel-actions a,
                                    .panel-actions .panel-action {
                                    	text-align: left;
                                    	width: 100%;
                                    }";
                                    echo "</style>";

                                    echo " <div class='btn-group'>
              												        <button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown'><i class='fa fa-file-text-o'></i> Registros do turno <small><sup><i class='fa fa-chevron-down'></i></sup></small></button>
              												        <ul class='dropdown-menu'><span style='margin-left:5px;color:#BBBBBB'><i>Novo registro:</i></span>
              												            <li><a href='oct/registros_de_turno_FORM.php?id_workshift=".$turno['id']."&tipo_registro=veiculo'>Veículo</a></li>
              												            <li><a href='oct/registros_de_turno_FORM.php?id_workshift=".$turno['id']."&tipo_registro=pessoa'>Pessoa</a></li>";
                                            echo "<li><a href='oct/registros_de_turno_FORM.php?id_workshift=".$turno['id']."&tipo_registro=guarnicao'>Guarnição</a></li>";
                                            echo "<!--<hr style='margin-top:5px;margin-bottom:5px'>-->
                                                  <span style='margin-left:5px;color:#BBBBBB'><i>Visualizar:</i></span>
                                                  <li><a href='oct/registros_de_turno_VIS.php?id_workshift=".$turno['id']."'><i class='fa fa-search'></i> Registros</a></li>
              												        </ul>
              												    </div>";

                            }else {

                                  echo " <button type='button' class='btn  btn-primary disabled'><i class='fa fa-cab'></i> Inserir guarnições</button>";

                            }

                            echo " <button id='bt_print' class='btn btn-primary'><i class='fa fa-print'></i> Imprimir</button>";

                            echo " <a href='oct/turno.php'><button type='button' class='btn btn-info'><i class='fa fa-calendar'></i> <sup><i class='fa fa-plus'></i></sup> Novo turno</button></a>";
                      }else {
                        echo " <a href='oct/turnos_INDEX.php'><button type='button' class='btn btn-primary'><i class='fa fa-file-text-o'></i> <sup><i class='fa fa-search'></i></sup> Visualizar turnos</button></a>";
                        echo " <a href='oct/turno.php'><button type='button' class='btn btn-info'><i class='fa fa-calendar'></i> <sup><i class='fa fa-plus'></i></sup> Novo turno</button></a>";
                      }
                  ?>
                </div>
            </header>
            <div class="panel-body">
<?
?>
              <div class="row">
                <div class="col-sm-12">
                    <h4><b>Informações gerais</b></h4>
                </div>
              </div>
<? if($turno_aberto){ ?>
  <div class="row">
    <div class="col-sm-2 text-center">

          <?
              echo "<small class='text-muted'><i>Turno atual:</i></small>";
              echo "<h2 style='margin-top:0px'><b>".str_pad($turno['id'],5,"0",STR_PAD_LEFT)."</b></h2>";
              echo "<h5 style='margin-top:-5px'>".strtoupper($turno['workshift_group'])."</h5>";
          ?>
    </div>
    <div class="col-sm-4 text-center">
        <?
            echo "<small class='text-muted'><i>Período de trabalho:</i></small>";
            echo  "<h5 style='margin-top:0px'><b>".formataData($turno['opened'],1)."</b><br><small>a</small><br><b>".formataData($turno['closed'],1)."</b></h5>";
        ?>
    </div>


  <div class="col-sm-2 text-center">
        <?
            echo "<small class='text-muted'><i>Agentes envolvidos:</i></small>";
            echo "<h2 style='margin-top:0px'><b>".$qtd_agentes."</b></h2>";
        ?>
  </div>

  <div class="col-sm-2 text-center">
        <?
            echo "<small class='text-muted'><i>Total de ocorrências:</i></small>";
            echo "<h2 style='margin-top:0px'><b>".$status_ocs['total']."</b></h2>";
            echo "<h5 style='margin-top:-5px'><b>".$status_ocs['abertas']."</b> <i class='text-muted'>Aberta(s)</i><br><b>".$status_ocs['fechadas']."</b> <i class='text-muted'>Fechada(s)</i></h5>";
        ?>
  </div>
  <div class="col-sm-2 text-center">
        <?
            $class = ($turno['status']=="aberto"?"text-success":"text-warning");
            echo "<small class='text-muted'><i>Status:</i></small>";
            echo "<h2 style='margin-top:0px' class='".$class."'><b>".$turno['status']."</b></h2>";

        ?>
  </div>

</div>
<div class="row"><div class="col-sm-12">

  <table class='table table-condensed'><tbody>
  <tr><td><b>Observações:</b></td></tr>
  <tr><td><?=($turno['observation']==""?"<span class='text-muted'><small>Nenhuma observação.</small></span>":$turno['observation']);?></td></tr>
  </tbody></table>


</div></div>


<?
}else {
  echo "<div class='row'><div class='col-sm-12 text-center'><div class='alert alert-warning'>Nehum turno aberto ou selecionado</div></div></div>";
}

?>
<div class="row"><div class="col-sm-12"><hr></div></div>



              <div class="row">
                <div class="col-sm-12">

                  <table class='table table-condensed'>
                  <thead><tr><th><h4><b>Coordenação:</h4></b></th></tr></thead>
                  <tbody>
                    <tr>
                      <td style="vertical-align: middle;">
                                              <?
                                                if(isset($dados_turno['coordenacao']))
                                                {
                                                  echo "<table class='table table-condensed'>
                                                        <thead><tr>
                                                          <th class='text-center' width='25px'><small><i>Matrícula</i></small></th>
                                                          <th><small><i>Nome</i></small></th>
                                                          <th class='text-center'><small><i>Entrada</i></small></th>
                                                          <th class='text-center'><small><i>Saída</i></small></th>
                                                        </tr>
                                                  <tbody>";

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
                                                  echo "</tbody></table>";
                                                }else {
                                                  echo "<small class='text-muted'>Nenhum coordenador designado.</small>";
                                                }
                                              ?>
                            </td>
                          </tr>
                                      </tbody>
                                    </table>



                </div>
              </div>






                <div class="row">
                <div class="col-sm-12">

<div class="table-responsive">

                                <table class='table table-condensed'>
                                <thead><tr><th><h4><b>Central de atendimento</h4></b></th></tr></thead>
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

                </div>
              </div>

              <div class="row">
                <div class="col-sm-12">



<div class="table-responsive">
                                    <table class='table table-condensed'>
                                      <thead><tr><th><h4><b>Agentes designados</b></h4></th></tr></thead>
                                      <tbody>
                                        <tr>
                                          <td style="vertical-align: middle;">
                                            <?
                                                if(isset($agentes_turno))
                                                {
                                                  echo "<table class='table table-condensed'>
                                                        <tbody>
                                                          <tr class='success'>
                                                              <td width='25px'><b>Ativos</b></td>
                                                              <td class='text-center' width='25px'><small><i>Matrícula</i></small></td>
                                                              <td><small><i>Nome</i></small></td>
                                                              <td><small><i>Status</i></small></td>
                                                              <td class='text-center'><small><i>Entrada</i></small></td>
                                                              <td class='text-center'><small><i>Saída</i></small></td>
                                                        </tr>";
                                                  if(isset($agentes_turno['ativos']))
                                                  {
                                                    $agentes = $agentes_turno['ativos'];
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
                                                              echo "<td>&nbsp;</td>";
                                                              echo "<td class='text-center'>".$agentes[$i]['registration']."</td>";
                                                              echo "<td>".$agentes[$i]['nome']."</td>";
                                                              echo "<td>".ucfirst($agentes[$i]['status'])."</td>";
                                                              echo "<td width='125px' class='text-center'>".$dt_opened."</td>";
                                                              echo "<td width='125px' class='text-center'>".$dt_closed."</td>";
                                                            echo "</tr>";
                                                    }
                                                  }else {
                                                    echo "<tr><td colspan='6' class='text-muted'><i>Nenhum agente ativo.</i></td></tr>";
                                                  }
                                                  echo "<tr class='warning'>
                                                          <td width='25px'><b>Afastados</b></td>
                                                          <td class='text-center' width='25px'><small><i>Matrícula</i></small></td>
                                                          <td><small><i>Nome</i></small></td>
                                                          <td><small><i>Status</i></small></td>
                                                          <td class='text-center'><small><i>Entrada</i></small></td>
                                                          <td class='text-center'><small><i>Saída</i></small></td>
                                                        </tr>";

                                                  if(isset($agentes_turno['outros']))
                                                  {
                                                    $agentes = $agentes_turno['outros'];
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
                                                              echo "<td>&nbsp;</td>";
                                                              echo "<td class='text-center'>".$agentes[$i]['registration']."</td>";
                                                              echo "<td>".$agentes[$i]['nome']."</td>";
                                                              echo "<td>".ucfirst($agentes[$i]['status'])."</td>";
                                                              echo "<td width='125px' class='text-center'>".$dt_opened."</td>";
                                                              echo "<td width='125px' class='text-center'>".$dt_closed."</td>";
                                                            echo "</tr>";
                                                    }
                                                  }else {
                                                    echo "<tr><td colspan='6' class='text-muted'><i>Nenhum agente afastado.</i></td></tr>";
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

                </div>


              </div>

<?
if($turno_aberto)
{
    $sql = "SELECT
              G.*
            FROM
              sepud.oct_garrison G
            WHERE
              G.id_workshift = '".$turno['id']."' AND G.name is not null ORDER BY G.opened ASC";
    $res = pg_query($sql)or die("Erro ".__LINE__."<br>SQL: ".$sql);;

    echo "<div class='row'>
            <div class='col-sm-12'>
              <div class='table-responsive'>
                        <table class='table table-condensed'>
                        <thead><tr><th colspan='5'><h4><b>Guarnições <sup>(versão 2)</sup></b></h4></th></tr>";
                        if(pg_num_rows($res))
                        {
                          echo "<tr><td><i><small>#</small></i></td>
                                    <td><i><small>Grupamento</small></i></td>
                                    <td><i><small>Início</small></i></td>
                                    <td><i><small>Fim</small></i></td>
                                    <td></td>
                                </tr>";
                          echo "</thead>";
                          while($dG = pg_fetch_assoc($res))
                          {

                            unset($dt_opened, $dt_closed);
                            if($dG['opened']!=""){
                                $aux       = explode(" ",formataData($dG['opened'],1));
                                $dt_opened = "<small>".$aux[0]."</small> <b>".$aux[1]."</b>";
                              }else {
                                $dt_opened = "";
                              }

                              if($dG['closed']!=""){
                                  $aux       = explode(" ",formataData($dG['closed'],1));
                                  $dt_closed = "<small>".$aux[0]."</small> <b>".$aux[1]."</b>";
                                }else {
                                  $dt_closed = "";
                                }
                            echo "<tr class='".($dG['closed']==""?"success":"warning")."'>";
                              echo "<td><small><i>".number_format($dG['id'],0,'','.')."</i></small></td>";
                              echo "<td width='90px'><b>".ucfirst($dG['name'])."</b></td>";
                              echo "<td width='125px'>".$dt_opened."</td>";
                              echo "<td width='125px'>".$dt_closed."</td>";
                              echo "<td width='100px' class='text-center'>";
                                echo "<a href='oct/guarnicao_FORM.php?id_garrison=".$dG['id']."&id_workshift=".$turno['id']."' class='btn btn-xs btn-default'><i class='fa fa-cab'></i> Atualizar</a>";
                              echo "</td>";
                            echo "</tr>";
                            echo "<tr>";
                              echo "<td colspan='5'><small class='text-muted'>Observações:</small><br>".$dG['observation']."</td>";
                            echo "<tr>";
                          }

                        }else {
                          echo "</thead><tbody><tr><td><small><i class='text-muted'>Aguarde, em desenvolvimento</i></small></td></tr></tbody>";
                        }
                        echo "</table>";
        echo "</div>
      </div>
  </div>";
}
?>

              <div class="row">
                <div class="col-sm-12">

                  <div class="table-responsive">
                                  <table class='table table-condensed'>
                                  <thead><tr><th><h4><b>Guarnições</b></h4></th></tr></thead>
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
                                                            	G.id_workshift = '".$turno['id']."' ORDER BY id DESC";
                                                    $res = pg_query($sql)or die("Erro ".__LINE__."<br>SQL: ".$sql);;
                                                    if(pg_num_rows($res))
                                                    {
                                                        echo "<table class='table table-condensed'>
                                                              <thead>
                                                              <tr>
                                                                <td colspan='4'>&nbsp;</td>
                                                                <td colspan='3' class=''>Quilometragem</td>
                                                                <td colspan='2' class=''>Datas</td>
                                                              </tr>
                                                              <tr>
                                                                <th>#</th>
                                                                <th>Apelido</th>
                                                                <th>Placa</th>
                                                                <th>Veículo</th>
                                                                <th class=''>Inicial</th>
                                                                <th class=''>Final</th>
                                                                <th class=''>Total</th>
                                                                <th class=''>Inicial</th>
                                                                <th class=''>Final</th>
                                                                <th class='text-center'>Status</th>
                                                                <th class='text-center hidden-print'><i class='fa fa-cogs'></i></th>";
                                                        echo "<tbody>";
                                                        while($d = pg_fetch_assoc($res))
                                                        {
                                                            echo "<tr class='".($d['closed']==""?"success":"warning")."'>";
                                                              echo "<td>".$d['id']."</td>";
                                                              echo "<td>".$d['nickname']."</td>";
                                                              echo "<td>".$d['plate']."</td>";
                                                              echo "<td>".$d['brand']." ".$d['model']."</td>";
                                                              echo "<td class=''>".number_format($d['initial_km'],0,'','.')."</td>";
                                                              echo "<td class=''>".number_format($d['final_km'],0,'','.')."</td>";
                                                              echo "<td class=''>".($d['final_km'] != "" && $d['initial_km'] != ""? $d['final_km'] - $d['initial_km']." Km":"-")."</td>";
                                                              echo "<td class=''>".formataData($d['opened'],1)."</td>";
                                                              echo "<td class=''>".formataData($d['closed'],1)."</td>";
                                                              echo "<td class='text-center ".($d['closed']==""?"success":"warning")."'>".($d['closed']==""?"Em uso":"Baixado")."</td>";
                                                              echo "<td class='text-center hidden-print'>";
                                                                echo "<a href='oct/veiculo_turno_FORM.php?id_garrison=".$d['id']."&turno=".$turno['id']."' class='btn btn-xs btn-default'><i class='fa fa-cab'></i> Atualizar</a>";
                                                              echo "</td>";
                                                            echo "</tr>";
                                                            echo "<tr><td colspan='12'><b>Observações:<b></td></tr>";
                                                            echo "<tr><td colspan='12'>".$d['observation']."</td></tr>";


                                                            echo "<tr><td colspan='12'>";
                                                            //Seleciona os Integrantes da guarnição//
                                                            $sqlGP = "SELECT
                                                                          GP.type,
                                                                          U.name, U.nickname, U.registration
                                                                        FROM
                                                                        	sepud.oct_rel_garrison_persona GP
                                                                        	JOIN sepud.users U ON U.ID = GP.id_user
                                                                        WHERE
                                                                        	GP.id_garrison = '".$d['id']."' ORDER BY GP.type ASC";

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
                                                        echo "<small class='text-muted'><i>Nenhuma guarnição criada.</i></small>";
                                                    }
                                                }else {
                                                  echo "<small class='text-muted'><i>Nenhuma guarnição criada.</i></small>";
                                                }
                                            ?>
                                      </td>
                                    </tr>
                                </tbody>
                            </table>
</div>

                </div>


              </div>





            </div><!--<div class="panel-body">-->
        </section><!--<section class="panel">-->
  </div><!--<div class="col-md-12">-->

</section>

<script>
$("#bt_print").click(function(){
	var vw = window.open('oct/turno_rel_print.php?id_workshift=<?=$turno['id'];?>',
									     'popup',
								 	     'width=800, height=600, top=10, left=10, scrollbars=no,location=no,status=no');
});
$("#bt_fecsssshar_turno").click(function(){
  var url = "oct/turno_sql.php?id=<?=$turno['id'];?>&acao=fechar";
  $("#wrap").load(url);
});
</script>
