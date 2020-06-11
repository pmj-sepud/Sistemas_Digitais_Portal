<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");
  $agora = now();


  logger("Acesso","Gestão de frota - Visualização detalhada");

  extract($_GET);
  if($id != "")
  {
      $acao = "Atualizar";
      $sql = "SELECT  * FROM {$schema}oct_fleet WHERE id = {$id}";
      $res = pg_query($sql)or die("SQL error ".__LINE__);
      $d   = pg_fetch_assoc($res);
  }else {
      $acao = "Inserir";
  }
?>

<section role="main" class="content-body">
  <header class="page-header">
    <h2>Frota de veículos</h2>
    <div class="right-wrapper pull-right" style='margin-right:15px;'>
      <ol class="breadcrumbs">
        <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
        <li><a href="oct/index.php">Sistema</a></li>
        <li><a href="oct/frota_INDEX.php">Frota de veículos</a></li>
        <li><span class='text-muted'>Visualização detalhada</span></li>
      </ol>
      <!--<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>-->
    </div>
  </header>



								<section class="panel box_shadow">
  									<header class="panel-heading" style="height:70px">
                      <div class="panel-actions" style="margin-top:5px">
                        <a href="oct/frota_INDEX.php"><button type="button" class="btn btn-default">Voltar</button></a>
                        <!--<button type="button" class="btn btn-primary"  data-toggle='modal' data-target='#modalFiltro'><i class='fa fa-search'></i> Pesquisar</button>-->
                        <!--<button type="button" class="btn btn-info" id="bt_print"><i class='fa fa-print'></i> Imprimir</button>-->
                        <!--<button type="button" class="btn btn-info"><i class='fa fa-map-marker'></i> <sup><i class='fa fa-plus'></i></sup> Novo registro</button>-->
                      </div>
                    </header>
  									<div class="panel-body">
                      <form id="form" name="form" class="form-horizontal" method="post" action="oct/frota_SQL.php" debug='0'>

                        <div class='row'>
                          <div class='col-sm-6 col-sm-offset-3'>


                            <div class="form-group">
                              <label class="col-md-2 control-label" for="type">Placa:</label>
                              <div class="col-md-4">
                                <input type="text" class="form-control" name="plate" value="<?=$d['plate'];?>">
                              </div>
                              <label class="col-md-2 control-label" for="nickname">Apelido:</label>
                              <div class="col-md-4">
                                <input type="text" class="form-control" name="nickname" value="<?=$d['nickname'];?>">
                              </div>
                            </div>



                            <div class="form-group">
                              <label class="col-md-2 control-label" for="type">Tipo:</label>
                              <div class="col-md-10">
                                <select id="type" name="type" class="form-control select2" style="width: 100%; height:100%">
                                    <option value="">- - -</option>
                                    <option value="Ambulância"  <?=($d['type']=="Ambulância"?"selected":"");?>>Ambulância</option>
                                    <option value="Automóvel"   <?=($d['type']=="Automóvel"?"selected":"");?>>Automóvel</option>
                                    <option value="Bicicleta"   <?=($d['type']=="Bicicleta"?"selected":"");?>>Bicicleta</option>
                                    <option value="Motocicleta" <?=($d['type']=="Motocicleta"?"selected":"");?>>Motocicleta</option>
                                    <option value="Ônibus"      <?=($d['type']=="Ônibus"?"selected":"");?>>Ônibus</option>

                                </select>
                              </div>
                            </div>


                            <div class="form-group">
                              <label class="col-md-2 control-label" for="type">Marca:</label>
                              <div class="col-md-4">
                                <input type="text" class="form-control" name="brand" value="<?=$d['brand'];?>">
                              </div>
                              <label class="col-md-2 control-label" for="type">Modelo:</label>
                              <div class="col-md-4">
                                <input type="text" class="form-control" name="model" value="<?=$d['model'];?>">
                              </div>
                            </div>


                                <div class="form-group">
                                  <label class="col-md-2 control-label" for="type">Observações:</label>
                                  <div class="col-md-10">
                                    <textarea  class="form-control" name="observation"><?=$d['observation'];?></textarea>
                                  </div>
                                </div>

                                <div class="form-group">
                                  <label class="col-md-2 control-label" for="type">Status:</label>
                                  <div class="col-md-10">
                                      <select name="active" class="form-control">
                                          <option value="t" <?=($d['active']=="t"?"selected":"");?>>Ativo</option>
                                          <option value="f" <?=($d['active']=="f"?"selected":"")?>>Baixado</option>
                                      </select>
                                  </div>
                                </div>

                          </div>
                        </div>

                        <div class='row' style="margin-top:10px">
                          <div class='col-sm-6 col-sm-offset-3 text-center'>
                            <input type="hidden" name="id" value="<?=$d['id'];?>" />
                            <input type="hidden" name="acao" value="<?=$acao;?>" />
                            <a href="oct/frota_INDEX.php"><button type="button" class="btn btn-default">Voltar</button></a>
                            <? if($acao=="Atualizar")
                                {
                                    if(check_perm("3_19","D"))
                                    {
                                      echo " <a href='oct/frota_SQL.php?id=".$d['id']."&acao=Remover'><button type='button' class='btn btn-danger loading'>Remover</button></a>";
                                    }

                                    if(check_perm("3_19","U"))
                                    {
                                      echo " <button type='submit' class='btn btn-primary loading'>".$acao."</button>";
                                    }
                                }

                                if($acao=="Inserir")
                                {
                                    if(check_perm("3_19","C"))
                                    {
                                      echo " <button type='submit' class='btn btn-primary loading'>".$acao."</button>";
                                    }
                                }
                             ?>

                          </div>
                        </div>


                      </form>
                      <?
                        /*
                        Array
                        (
                            [street_name] => MINISTRO CALOGERAS
                            [id] => 343
                            [name] => 62 BI
                            [numRef] => 1200
                            [id_street] => 2402
                            [geoposition] => -26.308850, -48.851531
                            [obs] =>
                            [zipcode] => 89203-000
                            [neighborhood] => ATIRADORES
                            [zone] => ZONA CENTRAL
                            [nonMappedStreet] =>
                            [id_company] => 2
                        )
                        */
                      ?>

                    </div>
                </section>
</section>


<script>
$('.select2').select2();
$(".loading").click(function(){ $(this).html("<i class=\"fa fa-spinner fa-spin\"></i> Aguarde");});
$(".loading2").click(function(){ $(this).addClass("disabled").html("<i class=\"fa fa-spinner fa-spin\"></i>");});
</script>
