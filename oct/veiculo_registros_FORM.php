<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");

  $agora = now();

  if($_GET['turno']==""){ header("Location: index.php"); exit(); }
  $turno = $_GET['turno'];
  if($_GET['id']==""){
      $acao = "Inserir";
  }else {
    $acao = "Atualizar";
  }
?>

<section role="main" class="content-body">
  <header class="page-header">
    <h2>Registros da viatura</h2>
    <div class="right-wrapper pull-right" style='margin-right:15px;'>
      <ol class="breadcrumbs">
        <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
        <li><a href="oct/index.php">Sistema</a></li>
        <li><span class='text-muted'>Registros da viatura</span></li>
      </ol>
      <!--<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>-->
    </div>
  </header>

<form action="" method="post">
<div class="col-md-12">
								<section class="panel box_shadow">
									<header class="panel-heading">

                    <div class="panel-actions" style='margin-top:-12px'>
									  </div>
                  </header>
									<div class="panel-body">
                    <div class="row">
                      <div class="col-md-6 col-md-offset-3">
                                    <div class="row">
                                        <div class="col-md-12">
                                          <?
                                          $sql = "SELECT * FROM sepud.oct_fleet WHERE id_company = '".$_SESSION['id_company']."' ORDER BY brand DESC, model ASC";
                                          $res = pg_query($sql)or die("Erro ".__LINE__);
                                          while($d = pg_fetch_assoc($res)){ $autos[$d['type']][] = $d;}
                                          ?>
                                              <div class="form-group">
                                              <label class="control-label">Veículo:</label>
                                              <select id="id_fleet" name="id_fleet" class="form-control select2">
                                                 <?

                                                        echo "<option value=''></option>";
                                                        foreach ($autos as $tipo => $auto) {
                                                            echo "<optgroup label='".$tipo."'>";
                                                            for($i=0;$i<count($auto);$i++)
                                                            {
                                                                echo "<option value='".$auto[$i]["id"]."' ".$sel.">".$auto[$i]["plate"]." - ".$auto[$i]["brand"]." ".$auto[$i]["model"]."</option>";
                                                            }
                                                            echo "</optgroup>";
                                                        }
                                                 ?>
                                              </select>
                                             </div>
                                      </div>
                                  </div>

                                  <div class="row">
                                    <div class="col-md-12">
                                      <div class="form-group">
                                       <label class="control-label">Ação:</label>
                                       <select name="id_user" class="form-control select2">
                                          <option value="">Observação geral</option>
                                          <option value="">Abastecimento</option>
                                          <option value="">Manutenção</option>
                                          <option value="">Lavação</option>
                                       </select>

                                     </div>
                                    </div>
                                  </div>


                    <div class="row">
                          <div class="col-md-12">
                                  <div class="row">
                                    <div class="col-md-6">
                                      <div class="form-group">
                                       <label class="control-label">Início:</label>
                                       <input id="opened" name="opened" type="datetime-local" class="form-control" value="<?=($dados['opened']!=""?$dados['opened']:substr(str_replace(" ","T",$agora['datatimesrv']),0,-3));?>">
                                      </div>
                                    </div>
                                    <div class="col-md-6">
                                      <div class="form-group">
                                       <label class="control-label">Fim:</label>
                                       <input id="closed" name="closed" type="datetime-local" class="form-control" value="">
                                      </div>
                                    </div>
                                </div>
    									  </div>
                    </div>

                    <div class="row">
                          <div class="col-md-12">
                                  <div class="row">
                                    <div class="col-md-6">
                                      <div class="form-group">
                                       <label class="control-label">Combustível inicial:</label>
                                       <input id="initial_fuel" name="initial_fuel" type="text" class="form-control">
                                      </div>
                                    </div>
                                    <div class="col-md-6">
                                      <div class="form-group">
                                        <label class="control-label">Combustível final:</label>
                                        <input id="final_fuel" name="final_fuel" type="text" class="form-control">
                                      </div>
                                    </div>
                                </div>
    									  </div>
                    </div>

                    <div class="row">
                          <div class="col-md-12">
                                  <div class="row">
                                    <div class="col-md-6">
                                      <div class="form-group">
                                       <label class="control-label">KM inicial:</label>
                                       <input id="initial_km" name="initial_km" type="text" class="form-control">
                                      </div>
                                    </div>
                                    <div class="col-md-6">
                                      <div class="form-group">
                                        <label class="control-label">KM final:</label>
                                        <input id="final_km" name="final_km" type="text" class="form-control">
                                      </div>
                                    </div>
                                </div>
    									  </div>
                    </div>

                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                         <label class="control-label">Observações:</label>
                         <textarea id="observation" name="observation" class="form-control" rows="5"><?=$dados['description'];?></textarea>
                       </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-12 text-center" style="margin-top:15px">
                          <input type="hidden" id="id_workshift" name="id_workshift" value="<?=$turno;?>">
                          <input type="hidden" id="acao" name="acao" value="<?=$acao;?>">
                          <a href="oct/index.php" class="btn btn-default loading2">Voltar</a>
                          <?
                              if($acao=="Atualizar")
                              {
                                echo "<input type='hidden' id='id' name='id' value='".$_GET['id']."'>";
                                echo  "<a href='oct/eventos_administrativos_SQL.php?acao=Remover&id=".$_GET['id']."' class='btn btn-danger loading2'>Remover</a>";
                              }
                          ?>
                          <button type="button" class="btn btn-primary loading"><?=$acao;?></button>
                      </div>
                   </div>

							   </div>
               </div>
             </div>
    </section>
</form>
</section>
<script>
$('.select2').select2();
$(".loading").click(function(){ $(this).html("<i class=\"fa fa-spinner fa-spin\"></i> Aguarde");});
$(".loading2").click(function(){ $(this).addClass("disabled").html("<i class=\"fa fa-spinner fa-spin\"></i>");});
</script>
