<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");
  $agora = now();

  logger("Acesso","SAS - BEV", "Variáveis de classificação - Visualização detalhada");


  extract($_GET);
  if($id != "")
  {
      $acao = "Atualizar";
      $sql = "SELECT * FROM {$schema}sas_vars WHERE id = '{$id}'";
      $res = pg_query($sql)or die("SQL error ".__LINE__);
      $d   = pg_fetch_assoc($res);
  }else {
      $acao = "Inserir";
  }
?>

<section role="main" class="content-body">
  <header class="page-header">
    <h2>Variáveis de classificação</h2>
    <div class="right-wrapper pull-right" style='margin-right:15px;'>
      <ol class="breadcrumbs">
        <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
        <li><span class='text-muted'>SAS-BEV</span></li>
        <li><a href="sas/variaveis.php">Variáveis de classificação</a></li>
        <li><span class='text-muted'>Visualização detalhada</span></li>
      </ol>
      <!--<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>-->
    </div>
  </header>



								<section class="panel box_shadow">
  									<header class="panel-heading" style="height:70px">
                      <div class="panel-actions" style="margin-top:5px">
                        <a href="sas/variaveis.php"><button type="button" class="btn btn-default">Voltar</button></a>
                        <!--<button type="button" class="btn btn-primary"  data-toggle='modal' data-target='#modalFiltro'><i class='fa fa-search'></i> Pesquisar</button>-->
                        <!--<button type="button" class="btn btn-info" id="bt_print"><i class='fa fa-print'></i> Imprimir</button>-->
                        <!--<button type="button" class="btn btn-info"><i class='fa fa-map-marker'></i> <sup><i class='fa fa-plus'></i></sup> Novo registro</button>-->
                      </div>
                    </header>
  									<div class="panel-body">
                      <form id="form" name="form" class="form-horizontal" method="post" action="sas/variaveis_SQL.php" debug='0'>

                        <div class='row'>
                          <div class='col-sm-6 col-sm-offset-3'>

                                <div class="form-group">
                                  <label class="col-md-2 control-label" for="description">Variável:</label>
                                  <div class="col-md-10">
                                    <input type="text" class="form-control" name="description" value="<?=$d['description'];?>">
                                  </div>
                                </div>

                                <div class="form-group">
                                  <label class="col-md-2 control-label" for="subgroup">Sub-grupo:</label>
                                  <div class="col-md-10">
                                    <input type="text" class="form-control" name="subgroup" value="<?=$d['subgroup'];?>">
                                  </div>
                                </div>

                                <div class="form-group">
                                  <label class="col-md-2 control-label" for="group">status:</label>
                                  <div class="col-md-2">
                                    <select class="form-control" name="status">
                                        <option value="t" <?=($d['status']=='t'?"selected":"");?>>Ativo</option>
                                        <option value="f" <?=($d['status']=='f'?"selected":"");?>>Inativo</option>
                                    </select>
                                  </div>
                                </div>



                          </div>
                        </div>


                      <div class="panel-footer">

                            <input type="hidden" name="id" value="<?=$d['id'];?>" />
                            <input type="hidden" name="acao" value="<?=$acao;?>" />
                            <a href="sas/variaveis.php"><button type="button" class="btn btn-default">Voltar</button></a>
                            <? if($acao=="Atualizar")
                                {
                                    if(check_perm("7_21","D"))
                                    {
                                      echo " <a href='sas/variaveis_SQL.php?id=".$d['id']."&acao=Remover'><button type='button' class='btn btn-danger loading'>Remover</button></a>";
                                    }

                                    if(check_perm("7_21","U"))
                                    {
                                      echo " <button type='submit' class='btn btn-primary loading'>".$acao."</button>";
                                    }
                                }

                                if($acao=="Inserir")
                                {
                                    if(check_perm("7_21","C"))
                                    {
                                      echo " <button type='submit' class='btn btn-primary loading'>".$acao."</button>";
                                    }
                                }
                             ?>

                        </div>


                      </form>
                    </div>
                </section>
</section>


<script>
$('.select2').select2();
$(".loading").click(function(){ $(this).html("<i class=\"fa fa-spinner fa-spin\"></i> Aguarde");});
$(".loading2").click(function(){ $(this).addClass("disabled").html("<i class=\"fa fa-spinner fa-spin\"></i>");});
</script>
