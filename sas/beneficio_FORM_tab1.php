<!-- TAB: BUSCA ATIVA -->

<form id="form" name="form" class="form-horizontal" method="post" action="sas/beneficio_FORM_tab1_SQL.php" debug='0'>
  <div class="row">
    <div class='col-sm-8 col-md-offset-2'>
<!---------------------------------------------------------------------------------->
<!---------------------------------------------------------------------------------->


  <hr class="dotted">
  <h4 class="mb-xlg text-primary"><i>Busca ativa:</i></h4>
  <fieldset>

    <div class='form-group'>
      <label class='col-md-2 control-label' for='schedule_date'>Data:</label>
      <div class='col-md-4'>
        <input type='date' class='form-control' id='active_search_date' name='active_search_date' value='<?=$d['active_search_date'];?>'>
      </div>
    </div>



    <div class='form-group'>
      <label class='col-md-2 control-label' for='active_search_observation'>Resultado:</label>
      <div class='col-md-10'>
        <textarea class='form-control' id='active_search_observations' name='active_search_observations' rows='20'><?=$d['active_search_observations']?></textarea>
      </div>
    </div>


  </fieldset>

  </div>
</div>



                    <hr class="dotted">
                        <div class='row' style="margin-top:10px">
                          <div class='col-sm-6 col-sm-offset-3 text-center'>
                                <!--<a href="sas/cidadao.php"><button type="button" class="btn btn-default">Voltar</button></a>-->
                                <? if($acao=="Atualizar")
                                    {
                                        echo "<input type='hidden' id='acao'       name='acao'       value='atualizar'>";
                                        echo "<input type='hidden' id='id'         name='id'         value='{$id_request}'>";
                                        echo "<input type='hidden' id='id_citizen' name='id_citizen' value='{$id_citizen}'>";
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
                                    }
                                 ?>
                          </div>
                        </div>
</form>

<div class="panel-footer text-right" style="margin-right:-25px">
<?

?>
</div>
<script>
$(".loading").click(function(){ $(this).html("<i class=\"fa fa-spinner fa-spin\"></i> <small>Aguarde.</small>"); });
</script>
