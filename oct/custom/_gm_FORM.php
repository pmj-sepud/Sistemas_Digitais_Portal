<?
  session_start();
  require_once("../../libs/php/funcoes.php");
  require_once("../../libs/php/conn.php");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");
  if($_SESSION['rotss_nav_retorno_origem']==""){ $retorno_origem = "ocorrencias.php";}else{ $retorno_origem = $_SESSION['rotss_nav_retorno_origem'];}


  if($_GET['id']==""){
    $acao = "inserir";
    $agora = now();
    $collapse_inicial = "in";
    $txt_bread = "Nova ocorrência";
  }else{
    $acao = "atualizar";
    extract($_GET);
    $txt_bread = "Atualizando ocorrência";

    $sql   = "SELECT
                  F.plate, F.brand, F.model, F.nickname as fleet_nickname,
                  UG.name  as user_name_garrison, UG.nickname as nickname_garrison, UG.registration as registration_garrison,
                  G.closed as closed_garrison, G.name as name_garrison,
                  W.opened as workshift_opened,
                  W.closed as workshift_closed,
                  W.workshift_group as workshift_period,
                  W.status as workshift_status,
                  U.NAME AS user_name, C.name AS company_name,
                  EV.*

              FROM
                        ".$schema."oct_events    EV
                   JOIN ".$schema."users         U  ON U.ID = EV.id_user
                   JOIN ".$schema."company       C  ON C.id = U.id_company
              LEFT JOIN ".$schema."oct_workshift W  ON W.id = EV.id_workshift
              LEFT JOIN ".$schema."oct_garrison  G  ON G.ID = EV.id_garrison
              LEFT JOIN ".$schema."oct_fleet     F  ON F.id = G.id_fleet
              LEFT JOIN ".$schema."oct_rel_garrison_persona GP ON GP.id_garrison = EV.id_garrison AND GP.type = 'Motorista'
              LEFT JOIN ".$schema."users        UG ON UG.id = GP.id_user
              WHERE EV.ID =  '".$id."'";

    $res   = pg_query($conn_neogrid,$sql)or die("Error ".__LINE__);
    $dados = pg_fetch_assoc($res);
  }


?>
<style>
.circle {
    width: 120px;
    line-height: 120px;
    border-radius: 50%;
    text-align: center;
    font-size: 32px;
    border: 2px solid #666;
}
.select2-selection 					 {height: 44px !important;		  }
</style>
<section role="main" class="content-body">
    <header class="page-header">
      <div class="right-wrapper pull-right" style='margin-right:15px;'>
        <ol class="breadcrumbs">
          <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
          <li><span class='text-muted'>Aplicações</span></li>
          <li><a href='oct/<?=$retorno_origem;?>?filtro_data=<?=$_GET['filtro_data'];?>'>Ocorrências - MOBILE - GM</a></li>
          <li><span class='text-muted'><?=$txt_bread;?></span></li>
        </ol>
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
              //echo "<li><a href='#'><i class='fa fa-file-text-o'></i> <sup><i class='fa fa-search'></i></sup> Item 00</a></li>";
              //echo "<li><a href='#'><i class='fa fa-calendar'></i> <sup><i class='fa fa-plus'></i></sup> Item 01</a></li>";
              echo "<li><a href='oct/{$retorno_origem}'><i class='fa fa-mail-reply'></i> Voltar</a></li>";
            echo '</ul>
          </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
      </nav>';

    ?>

    <section class="panel">
      <header class="panel-heading" style="height:50px">
              <div class='row' style="margin-top:-8px">
                  <div class='col-sm-12'>
                        <?
                            if($acao=="inserir"){
                              echo "Inserir nova ocorrência.";
                            }else {
                              echo "<h5>Ocorrência n° ".number_format(str_pad($_GET['id'],3,"0",STR_PAD_LEFT),0,'','.')."</h5>";
                            }
                        ?>
                  </div>
                </div>
      </header>
      <div class="panel-body">

                <div class='row'>
                  <div class="col-md-12">


<form id="form_oct" action="../oct/FORM_sql.php" method="post">
                    <div class="panel-group" id="accordion">
            								<div class="panel panel-accordion">
            									<div class="panel-heading">
            										<h4 class="panel-title">
            											<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#step_01" ajax="false"><span class="badge">1</span> Tipo da ocorrência <span id="isok_01" class="hidden"><i class='fa fa-check text-success'></i></span></a>
            										</h4>
            									</div>
            									<div id="step_01" class="accordion-body collapse <?=$collapse_inicial;?>">
            										<div class="panel-body">
            											  <? require_once("_gm_FORM_step_01.php");?>
            										</div>
            									</div>
            								</div>


            								<div class="panel panel-accordion">
            									<div class="panel-heading">
            										<h4 class="panel-title">
            											<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#step_02"  ajax="false"><span class="badge badge-default">2</span> Localização <span id="isok_02" class="hidden"><i class='fa fa-check text-success'></i></a>
            										</h4>
            									</div>
            									<div id="step_02" class="accordion-body collapse">
            										<div class="panel-body">
            											<? require_once("_gm_FORM_step_02.php");?>
            										</div>
            									</div>
            								</div>

          								<div class="panel panel-accordion">
          									<div class="panel-heading">
          										<h4 class="panel-title">
          											<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#step_03"  ajax="false"><span class="badge badge-default">3</span> Descrição <span id="isok_03" class="hidden"><i class='fa fa-check text-success'></i></a>
          										</h4>
          									</div>
          									<div id="step_03" class="accordion-body collapse">
          										<div class="panel-body">
          											<? require_once("_gm_FORM_step_03.php");?>
          										</div>
          									</div>
          								</div>

                          <div class="panel panel-accordion">
          									<div class="panel-heading">
          										<h4 class="panel-title">
          											<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#step_04"  ajax="false"><span class="badge badge-default">4</span> Status <span id="isok_04" class="hidden"><i class='fa fa-check text-success'></i></a>
          										</h4>
          									</div>
          									<div id="step_04" class="accordion-body collapse">
          										<div class="panel-body">
          											<? require_once("_gm_FORM_step_04.php");?>
          										</div>
          									</div>
          								</div>

</form>
<? if($acao=="atualizar"){ ?>
                          <div class="panel panel-accordion">
                            <div class="panel-heading">
                              <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#step_05"  ajax="false"><span class="badge badge-default">5</span> Providências <span id="isok_05" class="hidden"><i class='fa fa-check text-success'></i></a>
                              </h4>
                            </div>
                            <div id="step_05" class="accordion-body collapse">
                              <div class="panel-body">
                                <? include("_gm_FORM_step_05.php");?>
                              </div>
                            </div>
                          </div>

                          <div class="panel panel-accordion">
                            <div class="panel-heading">
                              <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#step_06"  ajax="false"><span class="badge badge-default">6</span> Envolvidos <span id="isok_06" class="hidden"><i class='fa fa-check text-success'></i></a>
                              </h4>
                            </div>
                            <div id="step_06" class="accordion-body collapse">
                              <div class="panel-body">
                                <? include("_gm_FORM_step_06.php");?>
                              </div>
                            </div>
                          </div>

                          <div class="panel panel-accordion">
                            <div class="panel-heading">
                              <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#step_07"  ajax="false"><span class="badge badge-default">7</span> Fotos <span id="isok_07" class="hidden"><i class='fa fa-check text-success'></i></a>
                              </h4>
                            </div>
                            <div id="step_07" class="accordion-body collapse">
                              <div class="panel-body">
                                <? include("_gm_FORM_step_07.php");?>
                              </div>
                            </div>
                          </div>
<? } ?>

							</div>


                  </div>
                </div>
      </div>


      <footer class="panel-footer text-center">
              <input type="hidden" name="userid" value="<?=$_SESSION['id'];?>">
              <input type="hidden" name="acao"   value="<?=$acao;?>">
              <input type="hidden" name="id"     value="<?=$id;?>">

        <? if($acao=="inserir")
           {
              echo "<button type='submit' class='btn btn-success btn-lg'>Inserir</button>";
           }else {
              echo "<button type='button' class='btn btn-primary btn-lg disabled'>Atualizar</button>";
           }
        ?>
      </footer>

    </section>
</section>
<script>
    $(".campo_data").mask('00/00/0000');
</script>
