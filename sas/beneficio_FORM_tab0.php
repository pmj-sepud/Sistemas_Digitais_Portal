<!-- TAB: AVALIAÇÃO -->
<?
//print_r_pre($_GET);



$date = new DateTime($requerente['birth']);
$idade = $date->diff( new DateTime( date('Y-m-d') ) );



?>

<div class="row">
  <div class="col-md-2 text-center">
    <?
          if($acao=="Atualizar")
          {
            $protocolo = str_replace("-","",substr($d['date'],0,-12)).".".$id_request;
            echo "<h3><small><sup>Protocolo:</sup></small><br><b>{$protocolo}</b><br><small class='".($d['status']=="Aberto"?"text-success":"text-danger")."'><sup><b>{$d['status']}</b></sup></small></h3>";
          }
    ?>

  </div>
  <div class="col-md-8">
    <h4 class="mb-xlg text-primary"><i>Requerente:</i></h4>
    <fieldset>
          <table class="table table-condensed">
              <tr><td><small class='text-muted'><i>Nome completo:</i></small><br><b><?=$requerente['name'];?></b>
                      <a href='sas/cidadao_FORM.php?id=<?=$d['id_citizen'];?>'><button class='btn btn-xs btn-default text-muted'><i class='fa fa-search'></i></button></a>
                  </td>
                  <td><small class='text-muted'><i>Idade:</i></small><br><?=$idade->format('%Y anos');?></td>
                  <td><small class='text-muted'><i>RG:</i></small><br><?=($requerente['rg']!=""?$requerente['rg']:"<i class='text-muted'>Não informado</i>");?></td>
                  <td><small class='text-muted'><i>CPF:</i></small><br><?=($requerente['cpf']!=""?$requerente['cpf']:"<i class='text-muted'>Não informado</i>");?></td>
                  <td><small class='text-muted'><i>Origem:</i></small><br><?=$requerente['company_acron']." - ".$requerente['company_name'];?></td>
              </tr>
          </table>
          <? //print_r_pre($d); ?>
    </fieldset>
  </div>
</div>
<hr class="dotted">

<form id="form" name="form" class="form-horizontal" method="post" action="sas/beneficio_FORM_tab0_SQL.php" debug='0'>


<div class="row">
  <div class="col-md-6">
  <!---------------------------------------------------------------------------------->
  <!---------------------------------------------------------------------------------->
  <h4 class="mb-xlg text-primary"><i>Demanda:</i></h4>
  <fieldset>
      <table class="table">
        <tr><td class='text-muted'><i>Selecione pelo menos uma demanda:</i></td>
            <td class='text-muted'><i>Status:</i></td></tr>
        <tbody>
              <tr>
                  <td>
                    <div class="checkbox-custom checkbox-default">
                      <input type="checkbox" id="demand_alimentacao" name="demand[]" value="alimentacao" <?=(isset($demandsel)  && in_array("alimentacao",$demandsel)?"checked":"");?>>
                      <label for="demand_alimentacao"><span class='text-muted'>auxílio</span> <b>ALIMENTAÇÃO</b></label>
                    </div>
                  </td>

                  <td>
                    <select class='form-control' name="demand_status[]" id="demand_status_alimentacao">
                        <option value="-"       <?=($demandstatus[0]=="-"?"selected":"")?>>- - -</option>
                        <option value="Aberto"  <?=($demandstatus[0]=="Aberto"?"selected":"")?>>Aberto</option>
                        <option value="Fechado" <?=($demandstatus[0]=="Fechado"?"selected":"disabled")?>>Fechado</option>
                        <option value="Negado"  <?=($demandstatus[0]=="Negado"?"selected":"")?>>Negado</option>
                        <option value="Cancelado"  <?=($demandstatus[0]=="Cancelado"?"selected":"")?>>Cancelado</option>
                    </select>
                  </td>
                </tr>


                <tr>
                    <td>
                      <div class="checkbox-custom checkbox-default">
                        <input type="checkbox" id="demand_natalidade" name="demand[]" value="natalidade" <?=(isset($demandsel)  && in_array("natalidade",    $demandsel)?"checked":"");?>>
                        <label for="demand_natalidade"><span class='text-muted'>auxílio</span> <b>NATALIDADE</b></label>
                      </div>
                    </td>

                    <td>
                      <input type="hidden" name="demand_status[]" id="demand_status_natalidade">
<!--
                      <select class='form-control' name="demand_status[]" id="demand_status_natalidade">
                        <option value="-"       <?=($demandstatus[1]=="-"?"selected":"")?>>- - -</option>
                        <option value="Aberto"  <?=($demandstatus[1]=="Aberto"?"selected":"")?>>Aberto</option>
                        <option value="Fechado" <?=($demandstatus[1]=="Fechado"?"selected":"")?>>Fechado</option>
                        <option value="Negado"  <?=($demandstatus[1]=="Negado"?"selected":"")?>>Negado</option>
                      </select>
-->
                    </td>
                  </tr>

                  <tr>
                      <td>
                        <div class="checkbox-custom checkbox-default">
                          <input type="checkbox" id="demand_funeral" name="demand[]" value="funeral" <?=(isset($demandsel)  && in_array("funeral",    $demandsel)?"checked":"");?>>
                          <label for="demand_funeral"><span class='text-muted'>auxílio</span> <b>FUNERAL</b></label>
                        </div>
                      </td>

                      <td>
                        <input type="hidden" name="demand_status[]" id="demand_status_funeral">
<!--
                        <select class='form-control' name="demand_status[]" id="demand_status_funeral">
                          <option value="-"       <?=($demandstatus[2]=="-"?"selected":"")?>>- - -</option>
                          <option value="Aberto"  <?=($demandstatus[2]=="Aberto"?"selected":"")?>>Aberto</option>
                          <option value="Fechado" <?=($demandstatus[2]=="Fechado"?"selected":"")?>>Fechado</option>
                          <option value="Negado"  <?=($demandstatus[2]=="Negado"?"selected":"")?>>Negado</option>
                        </select>
-->
                      </td>
                    </tr>
        </tbody>

      </table>

  </fieldset>




  <h4 class="mb-xlg text-primary"><i>Avaliação:</i></h4>
  <fieldset>

    <div class='form-group'>
      <label class='col-md-8 control-label' for='average_income'>Renda média:</label>
      <div class='col-md-4'>
        <input type='text' class='form-control' id='average_income' name='average_income' placeholder='' value='<?=number_format($d['average_income'],2,',','.');?>'>
      </div>
    </div>

      <div class='form-group'>
        <label class='col-md-8 control-label' for='food_count'>Quantidade de moradores na mesma residência:</label>
        <div class='col-md-4'>
          <input type='number' min="1" class='form-control' id='count_people' name='count_people' placeholder='' value='<?=($d['count_people']>0?$d['count_people']:"1");?>'>
        </div>
      </div>
            <div class='form-group'>
              <label class='col-md-2 control-label' for='food_aid'>Classificação:</label>
              <div class='col-md-10'>
                <?
                    $sql = "SELECT * FROM {$schema}sas_vars WHERE status = 't' ORDER BY description ASC, subgroup ASC";
                    $res = pg_query($sql)or die("Error ".__LINE__."<br>Query: {$sql}");
                    while($v = pg_fetch_assoc($res)){
                      $vars[$v['subgroup']][] = $v;
                    }
                ?>
                <select class='form-control' id='vars' name='vars[]' multiple size='10'>
                    <?
                        foreach ($vars as $subgroup => $var) {
                          echo "<optgroup label='{$subgroup}'>";
                            for($i=0;$i<count($var);$i++)
                            {
                              if(in_array($var[$i]['id'],$varssel)){
                                  $sel="selected";
                                  $subgroupsel[$subgroup]++;
                              }else{ $sel=""; }
                              echo "<option value='{$var[$i]['id']}' {$sel}>{$var[$i]['description']}</option>";
                            }
                          echo "</optgroup>";
                        }
                    ?>
                </select>
              </div>
            </div>

            <div class='form-group'>
              <label class='col-md-2 control-label' for='observations'>Observações:</label>
              <div class='col-md-10'>
                <textarea type='text' class='form-control' id='observations' name='observations' rows="5"><?=$d['observations']?></textarea>
              </div>
            </div>




    </fieldset>
  <!---------------------------------------------------------------------------------->
  <!---------------------------------------------------------------------------------->
  </div><!--<div class='col-md-6'>-->

  <div class="col-sm-6">
    <!---------------------------------------------------------------------------------->
    <!---------------------------------------------------------------------------------->
              <h4 class="mb-xlg text-primary"><i>Pontuação:</i></h4>
              <fieldset>
                  <table class="table table-striped">
                      <thead><tr><th class="text-center">Grupo(s) selecionado(s)</th>
                                 <th class="text-center">Variáveis selecionada(s)</th>
                      </thead>
                      <tbody>
                          <tr>
                              <?
                                if(isset($subgroupsel) && count($subgroupsel)>0)
                                {
                                    if(count($subgroupsel)<=2){ $class_subgrupo = "text-danger";}else{$class_subgrupo="text-success";}
                                    echo "<td class='text-center'><span id='qtdgrupos'><h1 class='{$class_subgrupo}'>".count($subgroupsel)."</h1></span></td>";
                                    echo "<td class='text-center'><span id='qtdvars'><h3>".count($varssel)."</h3></span></td>";
                                }else
                                {
                                  echo "<td class='text-center'><span id='qtdgrupos'><h1 class='text-muted'>0</h1></span></td>";
                                  echo "<td class='text-center'><span id='qtdvars'><h3>0</h3></span></td>";
                                }
                              ?>
                          </tr>
                      </tbody>
                  </table>
              </fieldset>

<!--
              <h4 class="mb-xlg text-primary"><i>Realizar busca ativa:</i></h4>
              <fieldset>
              <div class='form-group'>
                <div class='col-md-12'>
                  <select class='form-control' id='active_search' name='active_search'>
                      <option value="f" <?=($d['active_search']=="f"?"selected":"");?>>NÃO realizar busca ativa</option>
                      <option value="t" <?=($d['active_search']=="t"?"selected":"");?>>SIM, realizar busca ativa</option>
                  </select>
                </div>
              </fieldset>
-->

          <h4 class="mb-xlg text-primary"><i>Informações adiconais:</i></h4>
          <fieldset>
            <table class='table table-condensed'>
              <tbody>
                <? if($acao=="Atualizar"){ ?>
                <tr><td><small class='text-muted'><i>Avaliador:</i></small><br><?=$d['name_user_register'];?></td></tr>
                <tr><td><small class='text-muted'><i>Órgão:</i></small><br><?=$d['company_acron']." - ".$d['company_name'];?></td></tr>
                <tr><td><small class='text-muted'><i>Data da avaliação:</i></small><br><?=formataData($d['date'],1);?></td></tr>
              <? }else{ ?>
                <tr><td><small class='text-muted'><i>Avaliador:</i></small><br><?=$_SESSION['name'];?></td></tr>
                <tr><td><small class='text-muted'><i>Órgão:</i></small><br><?=$_SESSION['company_acron']." - ".$_SESSION['company_name'];?></td></tr>
                <tr><td><small class='text-muted'><i>Data da avaliação:</i></small><br><?=$agora['dthm'];?></td></tr>
              <? } ?>
              </tbody>
            </table>
          </fieldset>

    <!---------------------------------------------------------------------------------->
    <!---------------------------------------------------------------------------------->

  </div><!--<div class="col-md-6">-->
</div><!--<div class='row' id="formulario">-->


<hr class="dotted">
                        <div class='row' style="margin-top:10px">
                          <div class='col-sm-6 col-sm-offset-3 text-center'>

                              <!--  <a href="sas/cidadao.php"><button type="button" class="btn btn-default">Voltar</button></a> -->
                                <? if($acao=="Atualizar")
                                    {

                                        echo "<input type='hidden' id='acao'       name='acao'       value='atualizar'>";
                                        echo "<input type='hidden' id='id'         name='id'         value='{$id_request}'>";
                                        echo "<input type='hidden' id='id_citizen' name='id_citizen' value='{$_GET['id_citizen']}'>";
                                        //echo "<input type='hidden' id='status'     name='status'     value='{$d['status']}'>";
                                        if(check_perm("7_21","D"))
                                        {
                                        //  echo " <a href='sas/cidadao_SQL.php?id=".$d['id']."&acao=Remover'><button type='button' class='btn btn-danger loading'>Remover</button></a>";
                                        }

                                        if(check_perm("7_21","U"))
                                        {
                                          if($d['status']=="Aberto")
                                          {
                                            echo " <button id='bt_enviar' type='submit' class='btn btn-primary loading2'>".$acao."</button>";
                                          }elseif(check_perm("7_24"))
                                          {
                                            echo " <button id='bt_enviar' type='submit' class='btn btn-info loading2'>".$acao."<br><sup>[Permissão especial]</sup></button>";
                                          }
                                        }
                                    }else{
                                        echo "<input type='hidden' id='acao'       name='acao'       value='inserir'>";
                                        echo "<input type='hidden' id='id_user'    name='id_user'    value='{$_SESSION['id']}'>";
                                        echo "<input type='hidden' id='id_company' name='id_company' value='{$_SESSION['id_company']}'>";
                                        echo "<input type='hidden' id='date'       name='date'       value='{$agora['datatimesrv']}'>";
                                        echo "<input type='hidden' id='id_citizen' name='id_citizen' value='{$_GET['id_citizen']}'>";
                                        //echo "<input type='hidden' id='status'     name='status'     value='Aberto'>";

                                        if(check_perm("7_21","C"))
                                        {
                                          echo " <button id='bt_enviar' type='submit' class='btn btn-primary loading2'>Inserir</button>";
                                        }
                                    }
                                 ?>
                          </div>
                        </div>
</form>

<div class="panel-footer text-right" style="margin-right:-25px">
<?
  if($acao_beneficio=="Atualizar"){
  //    echo "<small class='text-muted'><i>Cadastrado por <b>{$d['name_user_register']}</b><br><b>{$d['company_user_register']}</b>, em <b>".formataData($d['date'],1)."</b></i></small>";
  }
?>
</div>
<script>
  check_demand();
  $('#average_income').mask("#.##0,00", {reverse: true});

  function check_demand(){
      if($("#demand_alimentacao").is(":checked") ||
         $("#demand_natalidade").is(":checked") ||
         $("#demand_funeral").is(":checked")){
           $('#bt_enviar').prop('disabled', false);
         }else{
           $('#bt_enviar').prop('disabled', true);
         }

  }

  $("#demand_alimentacao").change(function (){
        check_demand();
        if($("#demand_alimentacao").is(":checked")){ $("#demand_status_alimentacao").prop('selectedIndex', 1);}
                                               else{ $("#demand_status_alimentacao").prop('selectedIndex', 0);}
  });
  $("#demand_natalidade").change(function (){
    check_demand();
    if($("#demand_natalidade").is(":checked")){ $("#demand_status_natalidade").val('Fechado');}
                                          else{ $("#demand_status_natalidade").val('');}
/*
        if($("#demand_natalidade").is(":checked")){ $("#demand_status_natalidade").prop('selectedIndex', 1);}
                                              else{ $("#demand_status_natalidade").prop('selectedIndex', 0);}
*/
  });
  $("#demand_funeral").change(function (){
    check_demand();
    if($("#demand_funeral").is(":checked")){ $("#demand_status_funeral").val('Fechado');}
                                       else{ $("#demand_status_funeral").val('');}
/*
        if($("#demand_funeral").is(":checked")){ $("#demand_status_funeral").prop('selectedIndex', 1);}
                                           else{ $("#demand_status_funeral").prop('selectedIndex', 0);}
*/
  });

  //$(".loading").click(function(){ $(this).html("<i class=\"fa fa-spinner fa-spin\"></i> <small>Aguarde.</small>"); });
</script>
