<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");

  $agora = now();

  if($_GET['id_workshift']==""){ header("Location: index.php"); exit(); }
  $id_workshift = $_GET['id_workshift'];
  $gotoback     = $_GET['gotoback'];

  $show_veiculo = $show_pessoa = $show_guarnicao = false;
  switch ($_GET['tipo_registro']) {
    case 'veiculo':   $show_veiculo   = true; $txt = "Registro para veículo";   break;
    case 'pessoa':    $show_pessoa    = true; $txt = "Registro para pessoa";    break;
    case 'guarnicao': $show_guarnicao = true; $txt = "Registro para guarnição"; break;
  }

  if($_GET['id']==""){ $acao = "Inserir";  }
  else {               $acao = "Atualizar";
    $sqlU  = "SELECT * FROM ".$schema."oct_workshift_history WHERE id = '".$_GET['id']."'";
    $res   = pg_query($sqlU)or die("SQL Error ".__LINE__."<br>Query: ".$sqlU);
    $dados = pg_fetch_assoc($res);

    /*
    Array
    (
        [id] => 23
        [id_garrison] =>
        [id_vehicle] =>
        [id_user] => 55
        [obs] => Parada para almoço. fora de hora pois as 12h estará em ronda.
        [id_workshift] => 197
        [km_initial] =>
        [km_final] =>
        [type] => lanche
        [origin] => pessoa
        [opened] => 2019-08-13 09:00:00
        [closed] => 2019-08-13 10:00:00
    )
    */

  }
?>

<section role="main" class="content-body">
  <header class="page-header">
    <h2>Registros de turno</h2>
    <div class="right-wrapper pull-right" style='margin-right:15px;'>
      <ol class="breadcrumbs">
        <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
        <li><a href="oct/index.php">Sistema</a></li>
        <li><span class='text-muted'>Registros de turno</span></li>
      </ol>
      <!--<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>-->
    </div>
  </header>

<form action="oct/registros_de_turno_SQL.php" method="post">
<div class="col-md-12">
								<section class="panel box_shadow">
									<header class="panel-heading" style="height:70px">
                    <h4><?=$txt;?></h4>
                    <div class="panel-actions"  style='margin-top:5px'>

                      <style>
                      .panel-actions a,
                      .panel-actions .panel-action {
                        text-align: left;
                        width: 100%;
                      }
                      </style>
                      <div class='btn-group'>
                                <button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown'><i class='fa fa-file-text-o'></i> Registros do turno <small><sup><i class='fa fa-chevron-down'></i></sup></small></button>
                                <ul class='dropdown-menu'><span style='margin-left:5px;color:#BBBBBB'><i>Novo registro:</i></span>
                                    <? if(!$show_veiculo)  { ?><li><a href='oct/registros_de_turno_FORM.php?gotoback=<?=$gotoback;?>&id_workshift=<?=$id_workshift;?>&tipo_registro=veiculo'>Veículo</a></li><? } ?>
                                    <? if(!$show_pessoa)   { ?><li><a href='oct/registros_de_turno_FORM.php?gotoback=<?=$gotoback;?>&id_workshift=<?=$id_workshift;?>&tipo_registro=pessoa'>Pessoa</a></li><? } ?>
                                    <? if(!$show_guarnicao){ ?><li><a href='oct/registros_de_turno_FORM.php?gotoback=<?=$gotoback;?>&id_workshift=<?=$id_workshift;?>&tipo_registro=guarnicao'>Guarnição</a></li><? } ?>
                                    <!--<hr style='margin-top:5px;margin-bottom:5px'>-->
                                    <span style='margin-left:5px;color:#BBBBBB'><i>Visualizar:</i></span>
                                    <li><a href='oct/registros_de_turno_VIS.php?id_workshift=<?=$id_workshift;?>'><i class='fa fa-search'></i> Registros</a></li>
                                </ul>
                            </div>

                    </div>
                  </header>
									<div class="panel-body">
                    <div class="row">
                      <div class="col-md-6 col-md-offset-3">


<? if($show_veiculo){ ?>
<div id="veiculo">
<input type="hidden" name="id_garrison" value="">
<input type="hidden" name="id_user" value="">
<hr>
                                    <div class="row">
                                        <div class="col-md-12">
                                          <?
                                          $sql = "SELECT * FROM ".$schema."oct_fleet WHERE id_company = '".$_SESSION['id_company']."' ORDER BY brand DESC, model ASC";
                                          $res = pg_query($sql)or die("Erro ".__LINE__);
                                          while($d = pg_fetch_assoc($res)){ $autos[$d['type']][] = $d;}
                                          ?>
                                              <div class="form-group">
                                              <label class="control-label">Veículo:</label>
                                              <select id="id_fleet" name="id_fleet" class="form-control select2">
                                                 <?
                                                        foreach ($autos as $tipo => $auto) {
                                                            echo "<optgroup label='".$tipo."'>";
                                                            for($i=0;$i<count($auto);$i++)
                                                            {
                                                                if($dados['id_vehicle']==$auto[$i]["id"]){$sel = "selected"; }else{$sel="";}
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
                                       <select name="type" class="form-control select2">
                                          <option value="observação"    <?=($dados['type']=="observação"?"selected":"");?>>Observação geral</option>
                                          <option value="abastecimento" <?=($dados['type']=="abastecimento"?"selected":"");?>>Abastecimento</option>
                                          <option value="manutenção"    <?=($dados['type']=="manutenção"?"selected":"");?>>Manutenção</option>
                                          <option value="lavação"       <?=($dados['type']=="lavação"?"selected":"");?>>Lavação</option>
                                       </select>

                                     </div>
                                    </div>
                                  </div>

                    <div class="row">
                          <div class="col-md-12">
                                  <div class="row">
                                    <div class="col-md-6">
                                      <div class="form-group">
                                       <label class="control-label">KM inicial:</label>
                                       <input id="km_initial" name="km_initial" type="number" class="form-control" value="<?=$dados['km_initial'];?>">
                                      </div>
                                    </div>
                                    <div class="col-md-6">
                                      <div class="form-group">
                                       <label class="control-label">KM final:</label>
                                       <input id="km_final" name="km_final" type="number" class="form-control" value="<?=$dados['km_final'];?>">
                                      </div>
                                    </div>


                                </div>
    									  </div>
                    </div>
<hr>
</div><!--id veiculo-->
<?
  }

if($show_pessoa){

?>
<div id="pessoa">
  <input type="hidden" name="id_fleet"    value="">
  <input type="hidden" name="id_garrison" value="">
  <input type="hidden" name="km_initial"  value="">
  <input type="hidden" name="km_final"    value="">
<hr>
<div class="row">
    <div class="col-md-12">
      <?
      $sql = "SELECT
              	DISTINCT U.id, U.name, U.nickname, U.registration
              FROM
              	".$schema."oct_rel_workshift_persona  R
              JOIN ".$schema."users U ON U.id = R.id_person
              WHERE
              	id_shift = '".$id_workshift."'
              ORDER BY U.nickname ASC";
      $res = pg_query($sql)or die("Erro ".__LINE__);
      while($d = pg_fetch_assoc($res)){ $users[] = $d;}
      ?>
          <div class="form-group">
          <label class="control-label">Colaborador:</label>
          <select id="id_user" name="id_user" class="form-control select2">
             <?
                    for($i=0;$i<count($users);$i++)
                    {
                        if($dados['id_user']==$users[$i]["id"]){$sel="selected";}else{$sel="";}
                        echo "<option value='".$users[$i]["id"]."' ".$sel.">".$users[$i]["nickname"]." - ".$users[$i]["name"]." [matrícula: ".$users[$i]["registration"]."]</option>";
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
     <select name="type" class="form-control select2">
         <option value="observação" <?=($dados['type']=="observação"?"selected":"");?>>Observação geral</option>
         <option value="descanso"   <?=($dados['type']=="descanso"?"selected":"");?>>Descanso</option>
         <option value="lanche"     <?=($dados['type']=="lanche"?"selected":"");?>>Lanche</option>
         <option value="base"       <?=($dados['type']=="base"?"selected":"");?>>Base</option>
         <option value="reunião"    <?=($dados['type']=="reunião"?"selected":"");?>>Reunião</option>
         <option value="palestra"   <?=($dados['type']=="palestra"?"selected":"");?>>Palestra</option>
     </select>

   </div>
  </div>
</div>
<hr>
</div>
<?
}

if($show_guarnicao){
?>
<div id="guarnicao">
  <input type="hidden" name="id_fleet"   value="">
  <input type="hidden" name="id_user"    value="">
  <input type="hidden" name="km_initial" value="">
  <input type="hidden" name="km_final"   value="">
<hr>
<div class="row">
    <div class="col-md-12">
      <?
         $sql = "SELECT
                   F.nickname, F.plate, F.model, F.brand,
                   G.*
                 FROM
                   ".$schema."oct_garrison G
                 LEFT JOIN ".$schema."oct_fleet F ON F.id = G.id_fleet
                 WHERE
                   G.id_workshift = '".$id_workshift."' AND G.closed is null AND G.name is not null";
         $res = pg_query($sql)or die("SQL error ".__LINE__);
         while($aux = pg_fetch_assoc($res)){
           if($aux['name']!=""){ //Nome da guarnição, novo modelo de gestão//
             $vetor_nova_guarnicao[$aux['id']] = $aux;
           }
         }

         $sql = "SELECT
                   F.nickname,
                   F.brand,
                   F.model,
                   F.plate,
                   V.*
                 FROM
                   ".$schema."oct_rel_garrison_vehicle V
                   JOIN ".$schema."oct_fleet F ON F.ID = V.id_fleet
                 WHERE
                   V.id_garrison IN (SELECT G.ID FROM ".$schema."oct_garrison G WHERE G.id_workshift = '".$id_workshift."' AND G.closed is null AND G.name is not null)";

         $res = pg_query($sql)or die("SQL error ".__LINE__);
         while($aux = pg_fetch_assoc($res)){
           $vetor_nova_guarnicao[$aux['id_garrison']]['veiculos'][$aux['id']] = $aux;
         }

         $sql = "SELECT
                   U.NAME,
                   U.nickname,
                   U.registration,
                   P.*
                 FROM
                   ".$schema."oct_rel_garrison_persona
                   P JOIN ".$schema."users U ON U.ID = P.id_user
                 WHERE
                   P.id_garrison IN (SELECT G.ID FROM ".$schema."oct_garrison G WHERE G.id_workshift = '".$id_workshift."' AND G.closed is null AND G.name is not null)
                 ORDER BY U.nickname ASC";

         $res = pg_query($sql)or die("SQL error ".__LINE__);
         while($aux = pg_fetch_assoc($res)){

           if($aux['id_rel_garrison_vehicle']!="")
           {
             $vetor_nova_guarnicao[$aux['id_garrison']]['veiculos'][$aux['id_rel_garrison_vehicle']]['pessoas'][] = $aux;
           }else {
             $vetor_nova_guarnicao[$aux['id_garrison']]['pessoas_a_pe'][] = $aux;
           }
         }
         echo "<div class='form-group'>
               <label class='control-label'>Guarnição</label>
               <select class='form-control changefield select2' name='id_garrison'>
                 <option value=''></option>";
                 if($dados['closed_garrison']!="")
                 {
                     if($dados['name_garrison']=="")//Guarnição modelo antigo//
                     {
                       echo "<optgroup label='Guarnição empenhada (baixada):'>
                             <option value='".$dados['id_garrison']."' selected>".$dados['fleet_nickname']." - ".$dados['plate'].": ".$dados['nickname_garrison']."</option>
                             </optgroup>";
                     }else{

                       $sql = "SELECT F.nickname as fleet_nickname, G.name, R.* FROM ".$schema."oct_rel_garrison_vehicle R, ".$schema."oct_garrison G, ".$schema."oct_fleet F WHERE R.id_fleet = F.id AND id_garrison = '".$dados['id_garrison']."' AND R.id_garrison = G.id";
                       $res = pg_query($sql)or die("Erro ".__LINE__);
                       while($auxg = pg_fetch_assoc($res)){
                           $guarnicao_nova_baixada[$auxg['id_garrison']]['name']=$auxg['name'];
                           $guarnicao_nova_baixada[$auxg['id_garrison']]['veiculos'][$auxg['id']] = $auxg;
                       }
                       $sql = "SELECT G.name, P.*, U.nickname FROM ".$schema."oct_rel_garrison_persona P, ".$schema."oct_garrison G, ".$schema."users U WHERE id_garrison = '".$dados['id_garrison']."' AND P.id_garrison = G.id AND P.id_user = U.id";
                       $res = pg_query($sql)or die("Erro ".__LINE__);
                       while($auxg = pg_fetch_assoc($res)){
                           $guarnicao_nova_baixada[$auxg['id_garrison']]['veiculos'][$auxg['id_rel_garrison_vehicle']]['pessoas'][] = $auxg;
                       }
                       unset($str);
                       foreach ($guarnicao_nova_baixada as $id_garrison_baixada => $info_veic) {
                       echo "<optgroup label='Guarnição ".strtoupper($info_veic['name'])." (baixada):'>";
                         foreach ($info_veic['veiculos'] as $id_rel_gar_veic => $values)
                         {
                           if(isset($str)){ $str .= " | ";}
                           $str .= $values['fleet_nickname'].": ";
                           unset($straux);
                           for($i=0;$i<count($values['pessoas']);$i++)
                           {
                             $straux[] = $values['pessoas'][$i]['nickname'];
                           }
                           $str .= implode(", ",$straux);
                         }
                         echo "<option value='".$id_garrison_baixada."' selected>".$str."</option>";
                       echo "</optgroup>";
                       }
                     }
                 }
               foreach ($vetor_nova_guarnicao as $id_garrison => $info)
               {
                 echo "<optgroup label='Guarnição ".strtoupper($info['name'])."'>";
                 unset($str_veic, $str_pess);
                 foreach ($info['veiculos'] as $id_fleet => $info_veic)
                 {
                   if(isset($str_veic)){ $str_veic .= " | "; }
                   $str_veic .= $info_veic['nickname'].": ";
                   unset($str_pess);
                   for($i=0;$i<count($info_veic['pessoas']);$i++)
                   {
                     $str_pess[] = $info_veic['pessoas'][$i]['nickname'];
                   }
                   $str_veic .= implode(", ",$str_pess);
                 }
                 if(count($info['pessoas_a_pe'])){
                   unset($str_pess);
                   for($i=0;$i<count($info['pessoas_a_pe']);$i++)
                   {
                     $str_pess[] = $info['pessoas_a_pe'][$i]['nickname'];
                   }
                   if(isset($str_veic)){ $str_veic .= " | ";}
                   $str_veic .= "A PÉ: ".implode(", ",$str_pess);
                 }
                 if($dados['id_garrison']==$id_garrison){ $sel = "selected";}else{$sel="";}
                 echo "<option value='".$id_garrison."' ".$sel.">".strtoupper($info['name'])." - ".$str_veic."</option>";
                 echo "</optgroup>";
               }

               $sql = "SELECT
                          U.name as user_name, U.registration, U.nickname as user_nickname,
                          G.*,
                          F.plate,
                          F.TYPE,
                          F.model,
                          F.brand,
                          F.nickname
                        FROM
                          ".$schema."oct_garrison G
                        JOIN ".$schema."oct_fleet F ON F.id = G.id_fleet
                        JOIN ".$schema."oct_rel_garrison_persona GP ON GP.id_garrison = G.id AND GP.type = 'Motorista'
                        JOIN ".$schema."users U ON U.id = GP.id_user
                        WHERE
                            G.id_workshift = '".$id_workshift."'
                        AND G.closed is null";
               $res = pg_query($sql)or die("Erro ".__LINE__."<br>SQL: ".$sql);;
               if(pg_num_rows($res))
               {
                 echo "<optgroup label='Guarnições ativas - Modelo antigo:'>";
                 while($dg = pg_fetch_assoc($res))
                 {
                   if($dados['id_garrison']==$dg['id']){ $sel = "selected";}else{$sel="";}
                   echo "<option value='".$dg['id']."' ".$sel.">".$dg['nickname']." - ".$dg['plate'].": ".$dg['user_nickname']."</option>";
                 }
                 echo "</optgroup>";
               }

         echo "</select></div>";


      ?>
  </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="form-group">
     <label class="control-label">Ação:</label>
     <select name="type" class="form-control select2">
         <option value="observação" <?=($dados['type']=="observação"?"selected":"");?>>Observação geral</option>
         <option value="descanso"   <?=($dados['type']=="descanso"?"selected":"");?>>Descanso</option>
         <option value="lanche"     <?=($dados['type']=="lanche"?"selected":"");?>>Lanche</option>
         <option value="base"       <?=($dados['type']=="base"?"selected":"");?>>Base</option>
         <option value="reunião"    <?=($dados['type']=="reunião"?"selected":"");?>>Reunião</option>
         <option value="palestra"   <?=($dados['type']=="palestra"?"selected":"");?>>Palestra</option>
     </select>

   </div>
  </div>
</div>
<hr>
</div>
<? } ?>
                    <div class="row">
                          <div class="col-md-12">
                                  <div class="row">
                                    <div class="col-md-6">
                                      <div class="form-group">
                                       <label class="control-label">Início:</label>
                                       <input id="opened" name="opened" type="datetime-local" class="form-control" value="<?=str_replace(" ","T",$dados['opened']);?>">
                                      </div>
                                    </div>
                                    <div class="col-md-6">
                                      <div class="form-group">
                                       <label class="control-label">Fim:</label>
                                       <input id="closed" name="closed" type="datetime-local" class="form-control" value="<?=str_replace(" ","T",$dados['closed']);?>">
                                      </div>
                                    </div>
                                </div>
                        </div>
                    </div>


                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                         <label class="control-label">Observações:</label>
                         <textarea id="observation" name="observation" class="form-control" rows="5"><?=$dados['obs'];?></textarea>
                       </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-12 text-center" style="margin-top:15px">
                          <input type="hidden" id="id_workshift" name="id_workshift" value="<?=$id_workshift;?>">
                          <input type="hidden" id="acao" name="acao" value="<?=$acao;?>">
                          <input type="hidden" name="tipo_registro" value="<?=$_GET['tipo_registro'];?>">
                          <input type="hidden" name="gotoback" value="<?=$_GET['gotoback'];?>">

                          <? if($gotoback == "vis"){
                            echo "<a href='oct/registros_de_turno_VIS.php?id_workshift=".$id_workshift."' class='btn btn-default loading2'>Voltar</a>";
                          }else {
                            echo "<a href='oct/index.php?id_workshift=".$id_workshift."' class='btn btn-default loading2'>Voltar</a>";
                          }
                          ?>

                          <?
                              if($acao=="Atualizar")
                              {
                                echo "<input type='hidden' id='id' name='id' value='".$_GET['id']."'>";
                                echo  "<a href='oct/registros_de_turno_SQL.php?id_workshift=".$id_workshift."&acao=Remover&id=".$_GET['id']."' class='btn btn-danger loading2'>Remover</a>";
                              }

                          ?>
                          <button id="bt_inserir" type="submit" class="btn btn-primary loading"><?=$acao;?></button>
                      </div>
                   </div>

							   </div>
               </div>
             </div>
    </section>
</form>
</section>
<script>
$(".select2").select2({
  language: {
        noResults: function() {
          return 'Nenhum resultado encontrado.';
        }
      }
});
$(".loading").click(function(){ $(this).html("<i class=\"fa fa-spinner fa-spin\"></i> Aguarde");});
$(".loading2").click(function(){ $(this).addClass("disabled").html("<i class=\"fa fa-spinner fa-spin\"></i>");});
</script>
