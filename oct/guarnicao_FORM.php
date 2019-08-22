<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");

  $agora = now();

  $id_workshift = $_GET['id_workshift'];
  $id_garrison  = $_GET['id_garrison'];

  if($_GET['id_garrison']==""){
      $acao = "Inserir";
  }else {
    $acao  = "Atualizar";
    $sql   = "SELECT * FROM sepud.oct_garrison G WHERE id = '".$id_garrison."'";
    $res   = pg_query($sql)or die("SQL Error ".__LINE__);
    $dados = pg_fetch_assoc($res);

    //Listando todos os colaboradores do turno atual//
    $sql = "SELECT DISTINCT U.id, U.name, U.nickname, U.registration
            FROM sepud.oct_rel_workshift_persona W
            JOIN sepud.users U ON U.id = W.id_person
            WHERE W.id_shift = '".$id_workshift."' AND W.status in ('ativo', 'HE-Compensação', 'Serviços') ORDER BY U.nickname ASC";
    $res = pg_query($sql)or die("<option>SQL ERROR ".__LINE__."</option>");

    while($d = pg_fetch_assoc($res))
    {
      $opt_users .= "<option value='".$d['id']."'>[".$d['nickname']."] ".$d['name']." - Matrícula: ".$d['registration']."</option>";
    }
  }
?>
<style>

</style>
<section role="main" class="content-body">
  <header class="page-header">
    <h2>Guarnição v2</h2>
    <div class="right-wrapper pull-right" style='margin-right:15px;'>
      <ol class="breadcrumbs">
        <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
        <li><a href="oct/index.php">Sistema</a></li>
        <li><span class='text-muted'>Guarnição</span></li>
      </ol>
      <!--<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>-->
    </div>
  </header>

<form action="oct/guarnicao_SQL.php" method="post">
<div class="col-md-12">
								<section class="panel box_shadow">
									<header class="panel-heading">

                    <div class="panel-actions" style='margin-top:-12px'>
									  </div>
                  </header>
									<div class="panel-body">
                    <div class="row">
                      <div class="col-md-6">
                          <h4>Guarnição:</h4>

                          <div class="row">
                                <div class="col-md-6">
                                  <div class="form-group">
                                   <label class="control-label">Grupamento:</label>
                                    <select name="name" class="form-control select2">
                                        <option value="alfa"     <?=($dados['name']=='alfa'?"selected":"");?>    >Alfa</option>
                                        <option value="bravo"    <?=($dados['name']=='bravo'?"selected":"");?>   >Bravo</option>
                                        <option value="charlie"  <?=($dados['name']=='charlie'?"selected":"");?> >Charlie</option>
                                        <option value="delta"    <?=($dados['name']=='delta'?"selected":"");?>   >Delta</option>
                                        <option value="echo"     <?=($dados['name']=='echo'?"selected":"");?>    >Echo</option>
                                        <option value="fox"      <?=($dados['name']=='fox'?"selected":"");?>     >Fox</option>
                                        <option value="golf"     <?=($dados['name']=='golf'?"selected":"");?>    >Golf</option>
                                        <option value="hotel"    <?=($dados['name']=='hotel'?"selected":"");?>   >Hotel</option>
                                        <option value="india"    <?=($dados['name']=='india'?"selected":"");?>   >India</option>
                                        <option value="juliet"   <?=($dados['name']=='juliet'?"selected":"");?>  >Juliet</option>
                                        <option value="kilo"     <?=($dados['name']=='kilo'?"selected":"");?>    >Kilo</option>
                                        <option value="lima"     <?=($dados['name']=='lima'?"selected":"");?>    >Lima</option>
                                        <option value="mike"     <?=($dados['name']=='mike'?"selected":"");?>    >Mike</option>
                                        <option value="november" <?=($dados['name']=='november'?"selected":"");?>>November</option>
                                        <option value="oscar"    <?=($dados['name']=='oscar'?"selected":"");?>    >Oscar</option>
                                        <option value="papa"     <?=($dados['name']=='papa'?"selected":"");?>     >Papa</option>
                                        <option value="quebec"   <?=($dados['name']=='quebec'?"selected":"");?>   >Quebec</option>
                                        <option value="romeo"    <?=($dados['name']=='romeo'?"selected":"");?>    >Romeo</option>
                                        <option value="sierra"   <?=($dados['name']=='sierra'?"selected":"");?>   >Sierra</option>
                                        <option value="tango"    <?=($dados['name']=='tango'?"selected":"");?>    >Tango</option>
                                        <option value="uniform"  <?=($dados['name']=='uniform'?"selected":"");?>  >Uniform</option>
                                        <option value="victor"   <?=($dados['name']=='victor'?"selected":"");?>   >Victor</option>
                                        <option value="whiskey"  <?=($dados['name']=='whiskey'?"selected":"");?>  >Whiskey</option>
                                        <option value="xray"     <?=($dados['name']=='xray'?"selected":"");?>     >Xray</option>
                                        <option value="yankee"   <?=($dados['name']=='yankee'?"selected":"");?>   >Yankee</option>
                                        <option value="zulu"     <?=($dados['name']=='zulu'?"selected":"");?>     >Zulu</option>
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
                                       <input id="opened" name="opened" type="datetime-local" class="form-control" value="<?=($dados['opened']!=""?str_replace(" ","T",$dados['opened']):substr(str_replace(" ","T",$agora['datatimesrv']),0,-3));?>">
                                      </div>
                                    </div>
                                    <div class="col-md-6">
                                      <div class="form-group">
                                       <label class="control-label">Fim:</label>
                                       <input id="closed" name="closed" type="datetime-local" class="form-control" value="<?=($dados['closed']!=""?str_replace(" ","T",$dados['closed']):"");?>">
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
                          <input type="hidden" id="id_workshift" name="id_workshift" value="<?=$id_workshift;?>">
                          <input type="hidden" id="acao" name="acao" value="<?=$acao;?>">
                          <a href="oct/index.php?id_workshift=<?=$id_workshift;?>" class="btn btn-default loading2">Voltar</a>
                          <?
                              if($acao=="Atualizar")
                              {
                                echo "<input type='hidden' name='id' value='".$_GET['id_garrison']."'>";
                                echo  "<a href='oct/veiculo_turno_SQL.php?id_workshift=".$id_workshift."&acao=remover&id=".$_GET['id_garrison']."' class='btn btn-danger loading2'>Remover</a>";
                              }
                          ?>
                          <button type="submit" class="btn btn-primary loading"><?=$acao;?></button>
                      </div>
                   </div>

							   </div>
<? if($dados['id'] != ""){ ?>

                      <div class="col-sm-6">
                        <h4>Veículo(s):</h4>
                        <div class="row">
                          <div class="col-sm-10">
                            <?
                              $sql = "SELECT * FROM sepud.oct_fleet WHERE id_company = '".$_SESSION['id_company']."' ORDER BY brand DESC, model ASC, nickname ASC";
                              $res = pg_query($sql)or die("Erro ".__LINE__);
                              while($d = pg_fetch_assoc($res)){ $autos[$d['type']][] = $d;}
                            ?>
                            <div class="form-group">
                             <label class="control-label">Associar veículo:</label>
                             <select id="id_fleet" name="id_fleet" class="form-control select2">
                               <?
                                      echo "<option value=''></option>";
                                      foreach ($autos as $tipo => $auto) {
                                          echo "<optgroup label='".$tipo."'>";
                                          for($i=0;$i<count($auto);$i++)
                                          {
                                              echo "<option value='".$auto[$i]["id"]."'>".$auto[$i]["nickname"]." [".$auto[$i]["plate"]."] - ".$auto[$i]["brand"]." ".$auto[$i]["model"]."</option>";
                                          }
                                          echo "</optgroup>";
                                      }
                               ?>
                             </select>
                           </div>
                         </div>
                         <div class="col-sm-2" style="margin-top:27px">
                            <button id="bt_add_veic" class="btn  btn-sm btn-primary loading2"><i class="fa fa-cab"></i><sup> <i class="fa fa-plus"></i></sup></button>
                        </div>
                       </div>

                       <div class="row" style="margin-top:10px">
                          <div class="col-sm-12">
                            <?
                                $sqlVeic = "SELECT
                                            F.plate, F.type, F.model, F.brand, F.nickname,
                                             G.*
                                           FROM
                                            sepud.oct_rel_garrison_vehicle G
                                           JOIN sepud.oct_fleet F ON F.id = G.id_fleet
                                           WHERE
                                            id_garrison = '".$id_garrison."'
                                           ORDER BY F.brand DESC, F.model ASC, F.nickname ASC";
                                $resV    = pg_query($sqlVeic)or die("SQL error ".__LINE__."<br>Query: ".$sqlVeic);
                                while($dv = pg_fetch_assoc($resV)){$veic_assoc[$dv['type']][] = $dv; }

                                if(isset($veic_assoc))
                                {
                                  echo "<table class='table table-striped'>";
                                  echo "<tbody>";
                                    foreach ($veic_assoc as $tipo => $veics) {
                                      for($i = 0; $i < count($veics); $i++)
                                      {
                                        unset($v);$v = $veics[$i];
                                        echo "<tr>";
                                            echo "<td>".$v["nickname"]." [".$v["plate"]."] - ".$v["brand"]." ".$v["model"]."</td>";
                                            echo "<td width='80px'><button type='button' class='btn btn-sm btn-danger'><i class='fa fa-trash'></i></button></td>";
                                        echo "</tr>";
                                      }
                                    }
                                  echo "</tbody>";
                                  echo "</table>";
                                }else{
                                  echo "<div class='alert alert-warning text-center'>Nenhum veículo associado a esta guarnição.</div>";
                                }
                            ?>
                          </div>
                       </div>

                          <hr>
                          <h4>Integrantes(s):</h4>
                          <div class="row">
                            <div class="col-sm-10">
                              <div class="form-group">
                               <label class="control-label">Associar integrante:</label>
                               <select id="id_user_pass" name="id_user_pass" class="form-control select2">
                                  <option value=""></option>
                                  <?=$opt_users;?>
                               </select>
                             </div>
                           </div>
                           <div class="col-sm-2" style="margin-top:27px">
                              <button id="bt_add_pass" class="btn  btn-sm btn-primary loading2"><i class="fa fa-user"></i><sup> <i class="fa fa-plus"></i></sup></button>
                          </div>
                         </div>


                         <div class="row">
                           <div class="col-sm-10">

                             <div class="form-group">
                              <label class="control-label">Vincular ao veículo:</label>
                              <select id="id_fleet_user_assoc" name="id_fleet_user_assoc" class="form-control select2">
                                  <?
                                      echo "<option value=''></option>";
                                      if(isset($veic_assoc))
                                      {
                                          foreach ($veic_assoc as $tipo => $veics) {
                                            echo "<optgroup label='".$tipo."'>";
                                              for($i = 0; $i < count($veics);$i++)
                                              {
                                                unset($v);$v = $veics[$i];
                                                echo "<option value='".$v["id_fleet"]."'>".$v["nickname"]." [".$v["plate"]."] - ".$v["brand"]." ".$v["model"]."</option>";
                                              }
                                            echo "</optgroup>";
                                          }
                                      }else{
                                        echo "<option disabled value=''>Nenhum veículo associado a esta guarnição.</option>";
                                      }
                                  ?>
                              </select>
                            </div>
                           </div>
                         </div>

                         <div class="row" style="margin-top:10px">
                            <div class="col-sm-12">
                                      <?
                                          $sql = "SELECT
                                                    	R.id_garrison,
                                                    	U.id, U.name, U.nickname, U.registration
                                                    FROM
                                                    	sepud.oct_rel_garrison_persona R
                                                    JOIN sepud.users U ON U.id = R.id_user
                                                    WHERE
                                                    	R.id_garrison = '".$dados['id']."'
                                                    	AND R.TYPE = 'Passageiro'";
                                          $resPass = pg_query($sql)or die("SQL error ".__LINE__);
                                          if(pg_num_rows($resPass))
                                          {
                                              echo "<table class='table table-striped'>";
                                              echo "<thead><tr>
                                                           <th class='text-muted'><small><i>Matrícula</i></small></th>
                                                           <th class='text-muted'><small><i>Nome de guerra</i></small></th>
                                                           <th class='text-muted'><small><i>Nome</i></small></th>
                                                           <th class='text-muted text-center'><i class='fa fa-trash'></th>
                                                    </tr></thead>";
                                              while($dp = pg_fetch_assoc($resPass))
                                              {
                                                echo "<tr>";
                                                  echo "<td>".$dp['registration']."</td>";
                                                  echo "<td>".$dp['nickname']."</td>";
                                                  echo "<td>".$dp['name']."</td>";
                                                  echo "<td class='text-center'><button class='btn btn-xs btn-danger loading2' onClick='remove_passageiro(\"".$dp['id']."\");'><i class='fa fa-trash'></button></td>";
                                                echo "<tr>";
                                              }
                                              echo "</table>";
                                         }else {
                                           echo "<div class='alert alert-warning text-center'>Nenhum integrante nesta guarnição.</div>";
                                         }
                                      ?>

                            </div>
                         </div>

                      </div>
<? }else {
  echo "<div class='col-sm-6'>
          <h4>Passageiro(s):</h4>
            <div class='row'>
              <div class='col-sm-10'><div class='alert alert-warning text-center'>Após cadastrar a guarnição, será liberado para inserção de passageiros.</div>
            </div>
        </div>";
} ?>
               </div>
             </div>
    </section>
</form>
</section>
<script>
$('.select2').select2();
$(document).ready(function(){ $(this).scrollTop(0);});
//$(".campo_km").mask('000000');



$("#bt_add_veic").click(function(){
    <? if($acao=="Atualizar"){ ?>
    var id_garrison  = <?=$dados['id'];?>;
    var id_fleet     = $("#id_fleet").val();
    var id_workshift = <?=$id_workshift;?>;
    //alert("ASSOCIAR Passageiro:\nTurno: "+turno+"\nID da guarnição: "+id_garrison+"\nId user passageiro: "+id_user_pass);
    $('#wrap').load("oct/guarnicao_SQL.php?acao=associar_veiculo&id_workshift="+id_workshift+"&id_garrison="+id_garrison+"&id_fleet="+id_fleet);
    <? } ?>
    return false;

});

/*
function remove_passageiro(id_user_pass)
{
  <? if($acao=="Atualizar"){ ?>
  var id_garrison  = <?=$dados['id'];?>;
  var turno        = <?=$id_workshift;?>;
  //alert("REMOVER Passageiro:\nTurno: "+turno+"\nID da guarnição: "+id_garrison+"\nId user passageiro: "+id_user_pass);
  $('#wrap').load("oct/veiculo_turno_SQL.php?acao=remover_passageiro&turno="+turno+"&id_garrison="+id_garrison+"&id_user_pass="+id_user_pass);
  <? } ?>
  return false;
}

$("#bt_add_pass").click(function(){
    <? if($acao=="Atualizar"){ ?>
    var id_garrison  = <?=$dados['id'];?>;
    var id_user_pass = $("#id_user_pass").val();
    var turno        = <?=$id_workshift;?>;
    //alert("ASSOCIAR Passageiro:\nTurno: "+turno+"\nID da guarnição: "+id_garrison+"\nId user passageiro: "+id_user_pass);
    $('#wrap').load("oct/veiculo_turno_SQL.php?acao=associar_passageiro&turno="+turno+"&id_garrison="+id_garrison+"&id_user_pass="+id_user_pass);
    <? } ?>
    return false;

});
*/
$(".loading").click(function(){ $(this).html("<i class=\"fa fa-spinner fa-spin\"></i> Aguarde");});
$(".loading2").click(function(){ $(this).addClass("disabled").html("<i class=\"fa fa-spinner fa-spin\"></i>");});
</script>
