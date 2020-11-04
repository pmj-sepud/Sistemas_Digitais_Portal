<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");


  $agora = now();

  if($_GET['id_workshift']=="")
  {
      $sql   = "SELECT * FROM ".$schema."oct_workshift WHERE id_company = ".$_SESSION['id_company']." AND status = 'aberto'";
  }else {
      $sql   = "SELECT * FROM ".$schema."oct_workshift WHERE id ='".$_GET['id_workshift']."'";
  }


  $res   = pg_query($sql)or die("Erro ".__LINE__);

  if(pg_num_rows($res))
  {
        $turno = pg_fetch_assoc($res);
        $ano   = substr($turno['opened'],0,4);
        logger("Acesso","OCT", "Sistema - página inicial, Turno nº ".$turno['id']);
        $aux = explode(" ",formataData($turno['opened'],1));
        $data_abertura_filtro = $aux[0];

        $sql = "SELECT
                    U.name as nome, U.id as id_user, U.registration, U.nickname,
                    WP.*
                  FROM
                          ".$schema."oct_rel_workshift_persona WP
                     JOIN ".$schema."users                      U ON U.id = WP.id_person
                  WHERE
                    WP.id_shift =  '".$turno['id']."'
                  ORDER BY WP.opened ASC";
        $resRecursos = pg_query($sql)or die("Erro ".__LINE__."<br>SQL: ".$sql);

        while($d = pg_fetch_assoc($resRecursos))
        {
          if($d['type']=="agente")
          {
            if($d['status']=="ativo"          ||
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
                  	".$schema."oct_events E
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


  //Quantidade de ocorrencias e providencias por guarnição//
  $sql = "SELECT id_garrison, count(*) as qtd_oc FROM ".$schema."oct_events E WHERE E.id_workshift = '".$turno['id']."' GROUP BY id_garrison";
  $res = pg_query($sql)or die("SQL Error: ".$sql);
  while($aux = pg_fetch_assoc($res)){
      $produtividade_guarnicoes[$aux['id_garrison']]['qtd_oc'] = $aux['qtd_oc'];
      $produtividade_guarnicoes[$aux['id_garrison']]['total']  = $aux['qtd_oc']; }

  $sql = "SELECT id_garrison, count(*) as qtd_prov FROM	".$schema."oct_rel_events_providence WHERE id_garrison in (SELECT id FROM ".$schema."oct_garrison WHERE id_workshift = '".$turno['id']."') GROUP BY id_garrison";
  $res = pg_query($sql)or die("SQL Error: ".__LINE__);
  while($aux = pg_fetch_assoc($res)){ $produtividade_guarnicoes[$aux['id_garrison']]['qtd_prov'] = $aux['qtd_prov'];
                                      $produtividade_guarnicoes[$aux['id_garrison']]['total']   += $aux['qtd_prov']; }


  }else{
    $$turno_aberto = false;
    logger("Acesso","OCT", "Sistema - página inicial, nenhum turno aberto");
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

#custom-bootstrap-menu.navbar-default .navbar-brand {
    color: rgba(214, 213, 211, 1);
}
#custom-bootstrap-menu.navbar-default {
    font-size: 14px;
    background-color: rgba(255, 255, 255, 1);
    background: -webkit-linear-gradient(top, rgba(250, 250, 250, 1) 0%, rgba(255, 255, 255, 1) 100%);
    background: linear-gradient(to bottom, rgba(250, 250, 250, 1) 0%, rgba(255, 255, 255, 1) 100%);
    border-width: 1px;
    border-radius: 9px;
}
#custom-bootstrap-menu.navbar-default .navbar-nav>li>a {
    color: rgba(119, 119, 119, 1);
    background-color: rgba(248, 248, 248, 0);
}
#custom-bootstrap-menu.navbar-default .navbar-nav>li>a:hover,
#custom-bootstrap-menu.navbar-default .navbar-nav>li>a:focus {
    color: rgba(51, 51, 51, 1);
    background-color: rgba(222, 222, 222, 1);
}
#custom-bootstrap-menu.navbar-default .navbar-nav>.active>a,
#custom-bootstrap-menu.navbar-default .navbar-nav>.active>a:hover,
#custom-bootstrap-menu.navbar-default .navbar-nav>.active>a:focus {
    color: rgba(85, 85, 85, 1);
    background-color: rgba(230, 230, 230, 1);
}
#custom-bootstrap-menu.navbar-default .navbar-toggle {
    border-color: #e6e6e6;
}
#custom-bootstrap-menu.navbar-default .navbar-toggle:hover,
#custom-bootstrap-menu.navbar-default .navbar-toggle:focus {
    background-color: #e6e6e6;
}
#custom-bootstrap-menu.navbar-default .navbar-toggle .icon-bar {
    background-color: #e6e6e6;
}
#custom-bootstrap-menu.navbar-default .navbar-toggle:hover .icon-bar,
#custom-bootstrap-menu.navbar-default .navbar-toggle:focus .icon-bar {
    background-color: #ffffff;
}

</style>
<section role="main" class="content-body">
  <header class="page-header hidden-print">
    <h2>ROTSS</h2>
    <div class="right-wrapper pull-right" style='margin-right:15px;'>
      <ol class="breadcrumbs">
        <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
        <li><span class='text-muted'>Aplicações</span></li>
        <li><a href="oct/turnos_INDEX.php?filtro_id_workshift=<?=$turno['id'];?>&tab=<?=$_GET['tab'];?>&filtro_mes=<?=$data_abertura_filtro;?>">Turnos</a></li>
        <li><span class='text-muted'>Turno nº <?=$turno['id'];?></span></li>
      </ol>
      <!--<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>-->
    </div>
  </header>
<?
   echo '<nav id="custom-bootstrap-menu" class="navbar navbar-default">
            <div class="container-fluid">
                  <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                          <span class="icon-bar"></span>
                          <span class="icon-bar"></span>
                          <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand text-muted" href="#"><small><i>Menu de ações:</i></small></a>
                  </div>


    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav navbar-right">';

      if($turno['id']!="")
      {
        echo "<li><a href='oct/turno.php?id=".$turno['id']."'><i class='fa fa-cogs'></i> Turno <sup>(nº ".$turno['id'].")</sup></a></li>";
        echo "<li><a href='oct/turno_associar_pessoa.php?id_workshift=".$turno['id']."'><i class='fa fa-users'></i> Pessoal</a></li>";


        if(isset($qtd_agentes) && $qtd_agentes > 0)
        {

                echo "<li><a href='oct/guarnicao_FORM.php?id_workshift=".$turno['id']."'><i class='fa fa-cab'></i> Guarnições</a></li>";
                echo "<li class='dropdown'>
                          <a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false'><i class='fa fa-file-text-o'></i> Registros do turno <span class='caret'></span></a>

                          <ul class='dropdown-menu'>
                              <li class='dropdown-header'><i>Novo registro:</i></li>
                              <!--<span style='margin-left:5px;color:#BBBBBB'><i>Novo registro:</i></span>-->
                              <li><a href='oct/registros_de_turno_FORM.php?id_workshift=".$turno['id']."&tipo_registro=veiculo'>Veículo</a></li>
                              <li><a href='oct/registros_de_turno_FORM.php?id_workshift=".$turno['id']."&tipo_registro=pessoa'>Pessoa</a></li>";
                        echo "<li><a href='oct/registros_de_turno_FORM.php?id_workshift=".$turno['id']."&tipo_registro=guarnicao'>Guarnição</a></li>";
                        echo "<li role='separator' class='divider'></li>
                              <li class='dropdown-header'><i>Visualizar:</i></li>
                              <!--<span style='margin-left:5px;color:#BBBBBB'><i>Visualizar:</i></span>-->
                              <li><a href='oct/registros_de_turno_VIS.php?id_workshift=".$turno['id']."'><i class='fa fa-search'></i> Registros</a></li>
                          </ul>
                      </li>";
        }else{
                echo "<li class='disabled'><a href='#'><i class='fa fa-cab'></i> Inserir guarnições</a></li>";
        }
        echo "<li><a id='bt_print_2' href='#'><i class='fa fa-print'></i> Imprimir</a></li>";
        echo "<li><a href='oct/turno.php'><i class='fa fa-calendar'></i> <sup><i class='fa fa-plus'></i></sup> Novo turno</a></li>";
      }else{
        echo "<li><a href='oct/turnos_INDEX.php'><i class='fa fa-file-text-o'></i> <sup><i class='fa fa-search'></i></sup> Visualizar turnos</a></li>";
        echo "<li><a href='oct/turno.php'><i class='fa fa-calendar'></i> <sup><i class='fa fa-plus'></i></sup> Novo turno</a></li>";
      }
      echo "<li><a href='oct/turnos_INDEX.php?filtro_id_workshift={$turno['id']}&tab={$_GET['tab']}&filtro_mes={$data_abertura_filtro}'><i class='fa fa-mail-reply'></i> Voltar</a></li>";
      echo '</ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>';

?>
  <div class="col-md-12">
        <section class="panel box_shadow">
            <header class="panel-heading" style="height:70px">
                <?=$_SESSION['company_acron']." - ".$_SESSION['company_name'];?><br>
                <span class="text-muted"><small><i>Data atual:</i></small> <b><?=$agora['data'];?></b></span>
                <div class="panel-actions" style="margin-top:5px">
                </div>
            </header>
            <div class="panel-body">
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
                  <div class="table-responsive">
                  <table class='table table-condensed'>
                  <thead><tr><th><h4><b>Gerência/Coordenação:</h4></b></th></tr></thead>
                  <tbody>
                    <tr>
                      <td style="vertical-align: middle;">
                                              <?
                                                if(isset($dados_turno['coordenacao']) || isset($dados_turno['gerencia']))
                                                {
                                                  echo "<table class='table table-condensed'>
                                                        <thead><tr>
                                                          <th class='text-center' width='25px'><small><i>Matrícula</i></small></th>
                                                          <th><small><i>Nome</i></small></th>
                                                          <th><small><i>Área</i></small></th>
                                                          <th class='text-center'><small><i>Entrada</i></small></th>
                                                          <th class='text-center'><small><i>Saída</i></small></th>
                                                        </tr>
                                                  <tbody>";

                                                  if(isset($dados_turno['gerencia']))
                                                  {
                                                    $ger = $dados_turno['gerencia'];
                                                    for($i=0;$i<count($ger);$i++)
                                                    {
                                                      if($ger[$i]['opened']!=""){
                                                          $aux       = explode(" ",formataData($ger[$i]['opened'],1));
                                                          $dt_opened = "<small>".$aux[0]."</small> <b>".$aux[1]."</b>";
                                                        }else {
                                                          $dt_opened = "";
                                                        }

                                                        if($ger[$i]['closed']!=""){
                                                            $aux       = explode(" ",formataData($ger[$i]['closed'],1));
                                                            $dt_closed = "<small>".$aux[0]."</small> <b>".$aux[1]."</b>";
                                                          }else {
                                                            $dt_closed = "";
                                                          }

                                                      echo "<tr>";
                                                        echo "<td class='text-center'>".$ger[$i]['registration']."</td>";
                                                        echo "<td>".$ger[$i]['nome']."</td>";
                                                        echo "<td>Gerência</td>";
                                                        echo "<td width='125px' class='text-center'>".$dt_opened."</td>";
                                                        echo "<td width='125px' class='text-center'>".$dt_closed."</td>";
                                                      echo "</tr>";
                                                    }
                                                  }

                                                  if(isset($dados_turno['coordenacao']))
                                                  {
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
                                                            echo "<td>Coordenação</td>";
                                                            echo "<td width='125px' class='text-center'>".$dt_opened."</td>";
                                                            echo "<td width='125px' class='text-center'>".$dt_closed."</td>";
                                                          echo "</tr>";
                                                        }
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
              ".$schema."oct_garrison G
            WHERE
              G.id_workshift = '".$turno['id']."' AND G.name is not null ORDER BY G.opened ASC";
    $res = pg_query($sql)or die("Erro ".__LINE__."<br>SQL: ".$sql);;

    echo "<div class='row'>
            <div class='col-sm-12'>
              <div class='table-responsive'>
                        <table class='table table-condensed'>
                        <thead><tr><th colspan='5'><h4><b>Guarnições</b></h4></th></tr>";
                        if(pg_num_rows($res))
                        {

                          echo "</thead>";
                          while($dG = pg_fetch_assoc($res))
                          {

                            //veiculos da guarnição//
                            $sqlv = "SELECT F.plate, F.type, F.model, F.brand, F.nickname,
                                      			G.*
                                     FROM ".$schema."oct_rel_garrison_vehicle G
                                     JOIN ".$schema."oct_fleet F ON F.id = G.id_fleet
                                     WHERE id_garrison = '".$dG['id']."' ORDER BY F.nickname";
                            $resv = pg_query($sqlv) or die("SQL error ".__LINE__."<br>Query: ".$sqlv);
                            unset($aux, $veiculos_da_guarnicao, $pessoas_sem_veiculo);
                            while($aux = pg_fetch_assoc($resv))
                            {
                              $veiculos_da_guarnicao[$aux['id']] = $aux;
                            }
                            //pessoas da guarnição//
                            $sqlp = "SELECT
                                        U.nickname, U.name, U.registration,
                                        G.*
                                     FROM
                                        ".$schema."oct_rel_garrison_persona G
                                     JOIN ".$schema."users U ON U.id = G.id_user
                                     WHERE
                                      id_garrison = '".$dG['id']."'";
                            $resp = pg_query($sqlp) or die("SQL error ".__LINE__."<br>Query: ".$sqlp);
                            while($aux = pg_fetch_assoc($resp))
                            {
                              if($aux['id_rel_garrison_vehicle']!="")
                              {
                                $veiculos_da_guarnicao[$aux['id_rel_garrison_vehicle']]['pessoas'][] = $aux;
                              }else {
                                $pessoas_sem_veiculo[] = $aux;
                              }
                            }


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
                                if($produtividade_guarnicoes[$dG['id']]['qtd_oc']=="")  {$produtividade_guarnicoes[$dG['id']]['qtd_oc']  =0;}
                                if($produtividade_guarnicoes[$dG['id']]['qtd_prov']==""){$produtividade_guarnicoes[$dG['id']]['qtd_prov']=0;}
                            echo "<tr class='".($dG['closed']==""?"success":"warning")."'>";
                              echo "<td style='vertical-align:middle;'><i class='fa fa-cab'></i> <i class='fa fa-user'></i><br><small><i>".number_format($dG['id'],0,'','.')."</i></small></td>";
                              echo "<td><small><i>Grupamento:</i></small><br><b>".ucfirst($dG['name'])."</b></td>";
                              echo "<td><small><i>Ocorrências + Providências:</i></small><br>".$produtividade_guarnicoes[$dG['id']]['qtd_oc']." + ".$produtividade_guarnicoes[$dG['id']]['qtd_prov']." = ".$produtividade_guarnicoes[$dG['id']]['total']."</td>";
                              echo "<td width='130px'><small><i>Abertura:</i></small><br>".$dt_opened."</td>";
                              echo "<td width='130px'><small><i>Fechamento:</i></small><br>".$dt_closed."</td>";
                              echo "<td width='130px'><small><i>Status:</i></small><br>".($dt_closed!=""?"Encerrada":"Em operação")."</td>";
                              echo "<td width='130px' class='text-center' style='vertical-align:middle;'>";
                                echo "<a href='oct/guarnicao_FORM.php?id_garrison=".$dG['id']."&id_workshift=".$turno['id']."' class='btn btn-sm btn-default'><i class='fa fa-cab'></i> Atualizar</a>";
                              echo "</td>";
                            echo "</tr>";
                            echo "<tr>";
                              echo "<td colspan='7'><small class='text-muted'><i>Observações gerais:</i></small><br>".$dG['observation']."</td>";
                            echo "<tr>";
                            if(isset($veiculos_da_guarnicao))
                            {
                              foreach ($veiculos_da_guarnicao as $id_rel => $veic)
                              {

                                unset($km_rodado,$class);
                                $km_rodado = ($veic['initial_km']!=""&&$veic['final_km']!=""?($veic['final_km']-$veic['initial_km']):0);
                                $class='info';
                                if($km_rodado == 0 || $km_rodado >= 100){ $class="warning";}
                                if($km_rodado < 0)   { $class="danger"; }
                                echo "<tr>";
                                  echo "<td class='info'><small><i>Apelido:</i></small><br><b>".$veic['nickname']."</b></td>";
                                  echo "<td class='info'><small><i>Placa:</i></small><br>".$veic['plate']."</td>";
                                  echo "<td class='info' colspan='2'>".$veic['brand']." ".$veic['model']."</td>";
                                  echo "<td class='info'><small><i>Km inicial:</i></small><br>".number_format($veic['initial_km'],0,'','.')."</td>";
                                  echo "<td class='info'><small><i>Km final:</i></small><br>".number_format($veic['final_km'],0,'','.')."</td>";
                                  echo "<td class='".$class."'><small><i>Total percorrido:</i></small><br>".number_format($km_rodado,0,'','.')." km</td>";
                                echo "</tr>";
                                echo "<tr>";
                                  echo "<td colspan='7'><small class='text-muted'><i>Observações da viatura:</i></small><br>".$veic['obs']."</td>";
                                echo "</tr>";

                                if(isset($veic['pessoas']) && count($veic['pessoas']))
                                {
                                  for($cp=0;$cp<count($veic['pessoas']);$cp++)
                                  {
                                    echo "<tr>";
                                      echo "<td><b>".$veic['pessoas'][$cp]['nickname']."</b></td>";
                                      echo "<td colspan='5'>".$veic['pessoas'][$cp]['name']."</td>";
                                      echo "<td><b>".$veic['pessoas'][$cp]['type']."</b></td>";
                                    echo "</tr>";
                                  }
                                }else{
                                    echo "<tr><td colspan='3' class='text-danger'><small><i><b>Atenção:</b> Veículo sem condutor, favor atualizar.</i></small></td></tr>";
                                }

echo "<tr><td colspan='6'><hr></td></tr>";

                              }

                            }
                            if(isset($pessoas_sem_veiculo) && count($pessoas_sem_veiculo))
                            {
                              echo "<tr class='info'><td colspan='7'><b>AGENTE(S) SEM VEÍCULO:</b></td></tr>";
                              for($cpsv=0;$cpsv<count($pessoas_sem_veiculo);$cpsv++)
                              {
                                echo "<tr>";
                                  echo "<td><b>".$pessoas_sem_veiculo[$cpsv]['nickname']."</b></td>";
                                  echo "<td colspan='6'>".$pessoas_sem_veiculo[$cpsv]['name']."</td>";
                                echo "</tr>";
                              }
                            }

                          }

                        }else{
                          echo "</thead><tbody><tr><td><small><i class='text-muted'>Nenhuma guarnição configurada.</i></small></td></tr></tbody>";
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
                                                            	".$schema."oct_garrison
                                                            	G JOIN ".$schema."oct_fleet F ON F.ID = G.id_fleet
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
                                                                        	".$schema."oct_rel_garrison_persona GP
                                                                        	JOIN ".$schema."users U ON U.ID = GP.id_user
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
$("#bt_print_2").click(function(){
	var vw = window.open('oct/turno_rel_print.php?id_workshift=<?=$turno['id'];?>',
									     'popup',
								 	     'width=800, height=600, top=10, left=10, scrollbars=no,location=no,status=no');
});
$("#bt_fecsssshar_turno").click(function(){
  var url = "oct/turno_sql.php?id=<?=$turno['id'];?>&acao=fechar";
  $("#wrap").load(url);
});
</script>
