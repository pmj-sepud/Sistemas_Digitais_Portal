<!-- TAB: ENTREGA -->

<form id="form" name="form" class="form-horizontal" method="post" action="sas/beneficio_FORM_tab2_SQL.php" debug='0'>
<div class='row' id="formulario">
  <div class='col-sm-8 col-md-offset-2'>
<!---------------------------------------------------------------------------------->
<!---------------------------------------------------------------------------------->


  <hr class="dotted">
  <h4 class="mb-xlg text-primary"><i>Entrega:</i></h4>
  <fieldset>

            <div class='form-group'>
              <label class='col-md-2 control-label' for='schedule_date'>Agendado:</label>
              <div class='col-md-4'>

                <input type='datetime-local' class='form-control' id='schedule_date' name='schedule_date' value='<?=str_replace(" ","T",$d['schedule_date']);?>'>
              </div>
            </div>


              <div class='form-group'>
                <label class='col-md-2 control-label' for='delivery_type'>Forma:</label>
                <div class='col-md-4'>
                  <select class='form-control select2' id='delivery_type' name='delivery_type'>
                          <option value="">- - -</option>
                          <option value="retirada_eqp" <?=($d['delivery_type']=="retirada_eqp"?"selected":"");?>>Retirada no equipamento</option>
                          <option value="entrega_dom"  <?=($d['delivery_type']=="entrega_dom"?"selected":"");?>>Entrega em domicílio</option>
                  </select>
                </div>
              </div>

            <div class='form-group'>
              <label class='col-md-2 control-label' for='delivery_observations'>Observações:</label>
              <div class='col-md-10'>
                <textarea class='form-control' id='delivery_observations' name='delivery_observations' rows='5'><?=$d['delivery_observations']?></textarea>
              </div>
            </div>


            <div class='form-group'>
                  <label class='col-md-2 control-label' for='delivery_date'>Quantidade:</label>
                  <div class='col-md-4'>
                    <input type='number' min="1" class='form-control' id='food_count' name='food_count' value='<?=$d['food_count'];?>'>
                  </div>

                  <label class='col-md-2 control-label' for='delivery_date'>Peso:</label>
                  <div class='col-md-4'>
                          <div class="input-group mb-md">
                              <input type='number' min="1" class='form-control' id='food_size' name='food_size' value='<?=$d['food_size'];?>'>
                              <span class="input-group-addon">Kg</span>
                          </div>
                  </div>
            </div>




            <div class='form-group'>
                <label class='col-md-2 control-label' for='delivery_date'>Entrega:</label>
                <div class='col-md-4'>
                  <input type='datetime-local' class='form-control' id='delivery_date' name='delivery_date' value='<?=str_replace(" ","T",$d['delivery_date']);?>'>
                </div>
                <div class='col-md-6'>
                  * Aviso: Ao informar a data de entrega o sistema irá automáticamente finalizar essa demanda.
                </div>

              </div>

              <div class='form-group'>
                <label class='col-md-2 control-label' for='delivery_status'>Status:</label>
                <div class='col-md-4'>
                  <select class='form-control' id='delivery_status' name='delivery_status'>
                        <option value="">- - -</option>
                        <option value="agendado"     <?=($d['delivery_status']=="agendado"?"selected":"");?>>Aguardando</option>
                        <option value="entregue"     <?=($d['delivery_status']=="entregue"?"selected":"");?>>Entregue</option>
                      <optgroup label='Cancelamento'>
                        <option value="rua_nao_loc"  <?=($d['delivery_status']=="rua_nao_loc"?"selected":"");?>>Rua não localizada</option>
                        <option value="num_nao_loc"  <?=($d['delivery_status']=="num_nao_loc"?"selected":"");?>>Número não localizado</option>
                        <option value="2_tentativas" <?=($d['delivery_status']=="2_tentativas"?"selected":"");?>>2 tentativas e ninguém em casa</option>
                        <option value="outros"       <?=($d['delivery_status']=="outros"?"selected":"");?>>Outros (informar nas observações)</option>
                      </optgroup>
                  </select>
                </div>
              </div>

  </fieldset>

</div>






                        </div>


<hr class="dotted">
                        <div class='row' style="margin-top:10px">
                          <div class='col-sm-6 col-sm-offset-3 text-center'>


                                <a href="sas/cidadao.php"><button type="button" class="btn btn-default">Voltar</button></a>
                                <? if($acao=="Atualizar")
                                    {
                                        echo "<input type='hidden' id='demand_status' name='demand_status'  value='".json_encode($demandstatus)."'>";
                                        echo "<input type='hidden' id='acao'          name='acao'           value='atualizar'>";
                                        echo "<input type='hidden' id='id'            name='id'             value='{$d['id']}'>";
                                        echo "<input type='hidden' id='id_citizen'    name='id_citizen'     value='{$d['id_citizen']}'>";
                                        if(check_perm("7_21","D"))
                                        {
                                        //  echo " <a href='sas/cidadao_SQL.php?id=".$d['id']."&acao=Remover'><button type='button' class='btn btn-danger loading'>Remover</button></a>";
                                        }

                                        if(check_perm("7_21","U"))
                                        {
                                          if($d['status']=="Aberto")
                                          {
                                            echo " <button type='submit' class='btn btn-primary loading'>".$acao."</button>";
                                          }elseif(check_perm("7_24"))
                                          {
                                            echo " <button type='submit' class='btn btn-info loading'>".$acao."<br><sup>[Permissão especial]</sup></button>";
                                          }
                                        }
                                        echo " <button id='bt_print' type='button' class='btn btn-success'><i class='fa fa-print'></i> Imprimir</button>";
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
$("#delivery_date").change(function(){

    if($("#delivery_date").val())
    {
      $("#delivery_status").prop('selectedIndex', 2);
    }else{
      $("#delivery_status").prop('selectedIndex', 1);
    }
});
$("#bt_print").click(function(){
	var vw = window.open('sas/beneficio_recibo_print.php?id_citizen=<?=$id_citizen;?>&id_request=<?=$id_request;?>',
									     'popup',
								 	     'width=800, height=600, top=10, left=10, scrollbars=no,location=no,status=no');
});
$(".loading").click(function(){ $(this).html("<i class=\"fa fa-spinner fa-spin\"></i> <small>Aguarde.</small>"); });
</script>
