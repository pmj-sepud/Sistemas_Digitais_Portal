<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");

  $agora = now();

  function query($sql)
  {
    //$res = pg_query($)
  }

  if($_GET['turno']==""){ header("Location: index.php"); exit(); }
  $turno = $_GET['turno'];
  if($_GET['id_garrison']==""){
      $acao = "Inserir";
  }else {
    $acao  = "Atualizar";
    $sql   = "SELECT * FROM sepud.oct_garrison G WHERE id = '".$_GET['id_garrison']."'";
    $res   = pg_query($sql)or die("SQL Error ".__LINE__);
    $dados = pg_fetch_assoc($res);

    $sql = "SELECT * FROM sepud.oct_rel_garrison_persona WHERE id_garrison = '".$_GET['id_garrison']."'";
    $res   = pg_query($sql)or die("SQL Error ".__LINE__);
    while($ret = pg_fetch_assoc($res))
    {
      $associados[] = $ret;
      if($ret['type']=="Motorista"){ $motorista = $ret['id_user'];}
    }
    /*
    Array
      (
        [id] => 32
        [id_fleet] => 22
        [id_workshift] => 117
        [opened] => 2019-07-16 06:30:00
        [closed] =>
        [initial_fuel] =>
        [final_fuel] =>
        [observation] => Passageiro Maguiroski
        [initial_km] => 64648
        [final_km] =>
      )
*/
      //echo "<div class='row'><div class='col-sm-6 col-sm-offset-3'><pre>";
      //echo "Motorista ID: ".$motorista."<br>Associados ao veículo:";
      //print_r($_GET);
      //echo "<br>";
      //print_r($dados);
      //echo "</div></div>";

  }
?>

<section role="main" class="content-body">
  <header class="page-header">
    <h2>Guarnição</h2>
    <div class="right-wrapper pull-right" style='margin-right:15px;'>
      <ol class="breadcrumbs">
        <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
        <li><a href="oct/index.php">Sistema</a></li>
        <li><span class='text-muted'>Guarnição</span></li>
      </ol>
      <!--<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>-->
    </div>
  </header>

<form action="oct/veiculo_turno_SQL.php" method="post">
<div class="col-md-12">
								<section class="panel box_shadow">
									<header class="panel-heading">

                    <div class="panel-actions" style='margin-top:-12px'>
									  </div>
                  </header>
									<div class="panel-body">
<?

?>

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
                                                                $sel = ($dados['id_fleet']==$auto[$i]["id"]?"selected":"");
                                                                echo "<option value='".$auto[$i]["id"]."' ".$sel.">".$auto[$i]["nickname"]." [".$auto[$i]["plate"]."] - ".$auto[$i]["brand"]." ".$auto[$i]["model"]."</option>";
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
                                       <label class="control-label">Motorista:</label>
                                       <select id="id_user" name="id_user" class="form-control select2">
                                          <?
                                              $sql = "SELECT U.id, U.name, U.nickname, U.registration
                                                      FROM sepud.oct_rel_workshift_persona W
                                                      JOIN sepud.users U ON U.id = W.id_person
                                                      WHERE W.id_shift = '".$turno."'";
                                              $res = pg_query($sql)or die("<option>SQL ERROR ".__LINE__."</option>");
                                              echo "<option value=''></option>";
                                              while($d = pg_fetch_assoc($res))
                                              {
                                                $sel = ($motorista==$d['id']?"selected":"");
                                                echo "<option value='".$d['id']."' ".$sel.">[".$d['nickname']."] ".$d['name']." - Matrícula: ".$d['registration']."</option>";
                                              }
                                          ?>
                                       </select>
                                       <span class='text-muted'><sup>*</sup>Aparecerão apenas os agentes associados ao turno.</span>
                                     </div>
                                    </div>
                                  </div>


                    <div class="row">
                          <div class="col-md-12">
                                  <div class="row">
                                    <div class="col-md-6">
                                      <div class="form-group">
                                       <label class="control-label">Início do uso:</label>
                                       <? //substr(str_replace(" ","T",$agora['datatimesrv']),0,-3)) ?>
                                       <input id="opened" name="opened" type="datetime-local" class="form-control" value="<?=($dados['opened']!=""?str_replace(" ","T",$dados['opened']):"");?>">
                                      </div>
                                    </div>
                                    <div class="col-md-6">
                                      <div class="form-group">
                                       <label class="control-label">Término do uso:</label>
                                       <input id="closed" name="closed" type="datetime-local" class="form-control" value="<?=($dados['closed']!=""?str_replace(" ","T",$dados['closed']):"");?>">
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
                                       <input id="initial_fuel" name="initial_fuel" type="text" class="form-control" value="<?=$dados['initial_fuel'];?>">
                                      </div>
                                    </div>
                                    <div class="col-md-6">
                                      <div class="form-group">
                                        <label class="control-label">Combustível final:</label>
                                        <input id="final_fuel" name="final_fuel" type="text" class="form-control" value="<?=$dados['final_fuel'];?>">
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
                                       <input id="initial_km" name="initial_km" type="text" class="form-control" value="<?=$dados['initial_km'];?>">
                                      </div>
                                    </div>
                                    <div class="col-md-6">
                                      <div class="form-group">
                                        <label class="control-label">KM final:</label>
                                        <input id="final_km" name="final_km" type="text" class="form-control" value="<?=$dados['final_km'];?>">
                                      </div>
                                    </div>
                                </div>
    									  </div>
                    </div>

                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                         <label class="control-label">Observações:</label>
                         <textarea id="observation" name="observation" class="form-control" rows="5"><?=$dados['observation'];?></textarea>
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
                                echo "<input type='hidden' name='id' value='".$_GET['id_garrison']."'>";
                                //echo  "<a href='oct/eventos_administrativos_SQL.php?acao=Remover&id=".$_GET['id']."' class='btn btn-danger loading2'>Remover</a>";
                              }
                          ?>
                          <button type="submit" class="btn btn-primary loading"><?=$acao;?></button>
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
