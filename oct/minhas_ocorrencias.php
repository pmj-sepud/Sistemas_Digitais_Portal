<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");

  $agora        = now();
  logger("Acesso","OCT - Minhas Ocorrências");

  $sql   = "SELECT * FROM ".$schema."oct_workshift WHERE id_company = ".$_SESSION['id_company']." AND status = 'aberto'";
  $res   = pg_query($sql)or die("Erro ".__LINE__);

  if(pg_num_rows($res))
  {
        $turno = pg_fetch_assoc($res);
  }

?>

<section role="main" class="content-body">
  <header class="page-header">
    <h2>Ocorrências</h2>
    <div class="right-wrapper pull-right" style='margin-right:15px;'>
      <ol class="breadcrumbs">
        <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
        <li><span class='text-muted'>Aplicações</span></li>
        <li><span class='text-muted'>Ocorrências</span></li>
      </ol>
    </div>
  </header>


<?

?>
<div class="col-md-12">
								<section class="panel box_shadow">
									<header class="panel-heading" style="height:70px">
                    <span class="text-muted"> Data de referência: </span><b><?=$agora['data'];?></b>
                    <br><small class="text-muted"><?=$txt_oc_abertas;?><?=($txt_oc_baixadas!=""?", ".$txt_oc_baixadas:"");?>
                        </small>
                    <div class="panel-actions" style='margin-top:0px;'>
                        <?
                            if(isset($turno))
                            {
                              echo " <a href='oct/veiculo_turno_FORM.php?turno=".$turno['id']."'><button id='bt_atualizar_veiculo' type='button' class='btn btn-sm btn-info'><i class='fa fa-cab'></i> Utilizar veículo</button></a>";
                              echo " <a href='oct/veiculo_registros_FORM.php?turno=".$turno['id']."'><button id='bt_atualizar_veiculo' type='button' class='btn btn-sm btn-success'><i class='fa fa-cab'></i> Registros da VTR</button></a>";
                            }else {
                              echo " <a href='#'><button id='bt_atualizar_veiculo' type='button' class='btn btn-sm btn-info' disable><i class='fa fa-cab'></i> Inserir guarnições - Nenhum turno aberto</button></a>";
                            }
                        ?>
                    </div>
                  </header>
									<div class="panel-body">

                  <div class="row">
                    <div class="col-sm-6">
                      <?
                          $sql = "SELECT count(*) as qtd FROM ".$schema."oct_events E WHERE id_user = '".$_SESSION['id']."' AND E.active = true";
                          $res = pg_query($sql)or die("Error ".__LINE__."<br>".$sql);
                          $aux = pg_fetch_assoc($res);

                          $qtd_responsavel_ativos = $aux['qtd'];
                      ?>
                                <section class="panel panel-featured panel-featured-success">
                      								<header class="panel-heading">
                      									<h2 class="panel-title">Ocorrências ativas</h2>
                      									<p class="panel-subtitle text-muted">Abertas por mim</p>
                      								</header>
                      								<div class="panel-body">
                                        <div class="row">
                                          <div class="col-sm-8">
                                            <h4><b><?=$qtd_responsavel_ativos;?> </b>ocorrências ativas.</h4>
                                          </div>
                                          <div class="col-sm-4 text-right">
                                            <a href="oct/ocorrencias.php" class="btn btn-success">Visualizar</a>
                                          </div>
                      								</div>
                                    </div>
            							       </section>



                    </div>

                    <div class="col-sm-6">
                      <?
                          $sql = "SELECT count(*) as qtd
                                  FROM ".$schema."oct_events E
                                  JOIN ".$schema."oct_rel_garrison_persona GP ON GP.id_garrison = E.id_garrison AND GP.id_user = '".$_SESSION['id']."'
                                  WHERE E.active = true
                                  AND E.id_garrison is not null
                                  AND E.id_user <> '".$_SESSION['id']."'";
                          $res = pg_query($sql)or die("Error ".__LINE__."<br>".$sql);
                          $aux = pg_fetch_assoc($res);

                          $qtd_empenhado_ativos = $aux['qtd'];
                      ?>
                      <section class="panel panel-featured panel-featured-warning">
                          <header class="panel-heading">
                            <h2 class="panel-title">Ocorrências empenhadas</h2>
                            <p class="panel-subtitle text-muted">Direcionadas a minha guarnição</p>
                          </header>
                          <div class="panel-body">
                            <div class="row">
                              <div class="col-sm-8">
                                <h4><b><?=$qtd_empenhado_ativos;?> </b>ocorrências ativas.</h4>
                              </div>
                              <div class="col-sm-4 text-right">
                                <a href="oct/ocorrencias.php" class="btn btn-warning">Visualizar</a>
                              </div>
                          </div>
                        </div>
                       </section>

                    </div>
                  </div>
								</div>

                <div class="row">
                  <div class="col-sm-6">
                    <?
                        $sql = "SELECT count(*) as qtd
                                  FROM ".$schema."oct_events E
                                  WHERE E.active = false
                                  AND E.id_user = '".$_SESSION['id']."'
                                  AND E.closure BETWEEN '".$agora['datasrv']." 00:00:00' AND '".$agora['datasrv']." 23:59:59'";
                        $res = pg_query($sql)or die("Error ".__LINE__."<br>".$sql);
                        $aux = pg_fetch_assoc($res);

                        $qtd_baixado = $aux['qtd'];
                    ?>
                    <section class="panel panel-featured panel-featured-dafault">
                          <header class="panel-heading">
                            <h2 class="panel-title">Ocorrências baixadas</h2>
                            <p class="panel-subtitle text-muted">Nesta data</p>
                          </header>
                          <div class="panel-body">
                            <div class="row">
                              <div class="col-sm-8">
                                <h4><b><?=$qtd_baixado;?> </b>ocorrências baixadas.</h4>
                              </div>
                              <div class="col-sm-4 text-right">
                                <a href="oct/ocorrencias.php" class="btn btn-default">Visualizar</a>
                              </div>
                          </div>
                        </div>
                     </section>
                  </div>
                <div class="col-sm-6">
                  <section class="panel panel-featured panel-featured-danger">
                        <header class="panel-heading">
                          <h2 class="panel-title">Providências</h2>
                          <p class="panel-subtitle text-muted">Providências destinadas a minha guarnição</p>
                        </header>
                        <div class="panel-body">
                          <div class="row">
                            <div class="col-sm-8">
                              <h4><b><?=$qtd_responsavel_ativos;?> </b>ocorrências ativas.</h4>
                            </div>
                            <div class="col-sm-4 text-right">
                              <a href="oct/ocorrencias.php" class="btn btn-danger">Visualizar</a>
                            </div>
                        </div>
                      </div>
                   </section>
                </div>
              </div>



								</section>
							</div>





</section>

<script>
  $("#minhas_oc_abertas").click(function(){
      alert("Clicou em minhas ocorrencias em aberto");
  });
</script>
