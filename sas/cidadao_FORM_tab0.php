<!-- TAB: CADASTRO -->
<div id='error' align='center' style='display:none;padding:30px;z-index:1000;position:absolute;background-color:#FFD700;color:#555555;width:100%'></div>

                      <form id="form" name="form" class="form-horizontal" method="post" action="sas/cidadao_FORM_tab0_SQL.php" debug='0'>

                        <div class='row'>
                          <div class='col-sm-8 col-sm-offset-2'>

<!---------------------------------------------------------------------------------->
<!---------------------------------------------------------------------------------->
<hr class="dotted">
<h4 class="mb-xlg text-primary"><i>Informações:</i></h4>
<fieldset>

    <div class='form-group'>
      <label class='col-md-2 control-label' for='name'>Nome:</label>
      <div class='col-md-10'>
        <input type='text' class='form-control' id='name' name='name' placeholder='' value='<?=$d['name'];?>'>
      </div>
    </div>

    <div class='form-group'>
      <label class='col-md-2 control-label' for='mother_name'>Nome da mãe:</label>
      <div class='col-md-10'>
        <input type='text' class='form-control' id='mother_name' name='mother_name' placeholder='' value='<?=$d['mother_name'];?>'>
      </div>
    </div>

    <div class='form-group'>
      <label class='col-md-2 control-label' for='birth'>Data de nasc.:</label>
      <div class='col-md-4'>
        <input type='date' class='form-control' id='birth' name='birth' placeholder='' value='<?=$d['birth'];?>'>
      </div>

      <label class='col-md-3 control-label' for='phone'>Telefone principal:</label>
      <div class='col-md-3'>
        <input type='text' class='form-control' id='phone' name='phone' placeholder='(DDD) número do telefone' value='<?=$d['phone'];?>'>
      </div>
    </div>


</fieldset>

<hr>
<h4 class="mb-xlg text-primary"><i>Contatos adicionais:</i></h4>
<fieldset>
    <div class='form-group'>
      <label class='col-md-2 control-label' for='phone1'>Telefone 1:</label>
      <div class='col-md-4'>
        <input type='text' class='form-control' id='phone1' name='phone1' placeholder='(DDD) número do telefone' value='<?=$d['phone1'];?>'>
      </div>

      <label class='col-md-2 control-label' for='phone2'>Telefone 2:</label>
      <div class='col-md-4'>
        <input type='text' class='form-control' id='phone2' name='phone2' placeholder='(DDD) número do telefone' value='<?=$d['phone2'];?>'>
      </div>
    </div>

    <div class='form-group'>
      <label class='col-md-2 control-label' for='obs_contatos_adicionais'>Informações dos contatos:</label>
      <div class='col-md-10'>
        <textarea  class='form-control' id='obs_contatos_adicionais' name='obs_contatos_adicionais' rows="2"><?=$d['obs_contatos_adicionais'];?></textarea>
      </div>
    </div>
</fieldset>


<hr class="dotted">
<h4 class="mb-xlg text-primary"><i>Registros:</i></h4>
<fieldset>

    <div class='form-group'>
      <label class='col-md-2 control-label' for='rg'>RG:</label>
      <div class='col-md-4'>
        <input type='text' class='form-control' id='rg' name='rg' placeholder='NÃO INFORMADO' value='<?=$d['rg'];?>'>
      </div>

      <label class='col-md-2 control-label' for='cpf'>CPF:</label>
      <div class='col-md-4'>
        <input type='text' class='form-control' id='cpf' name='cpf' placeholder='NÃO INFORMADO' value='<?=$d['cpf'];?>'>
      </div>
    </div>

    <div class='form-group'>
      <label class='col-md-2 control-label' for='gmas'>GMAS:</label>
      <div class='col-md-4'>
        <input type='text' class='form-control' id='gmas' name='gmas' placeholder='' value='<?=$d['gmas'];?>'>
      </div>

      <label class='col-md-2 control-label' for='cadunico'>Cadastro Único:</label>
      <div class='col-md-4'>
        <input type='text' class='form-control' id='cadunico' name='cadunico' placeholder='' value='<?=$d['cadunico'];?>'>
      </div>
    </div>

    <div class='form-group'>
      <label class='col-md-2 control-label' for='nis'>NIS:</label>
      <div class='col-md-10'>
        <input type='text' class='form-control' id='nis' name='nis' placeholder='' value='<?=$d['nis'];?>'>
      </div>
    </div>

</fieldset>

<hr class="dotted">
<h4 class="mb-xlg text-primary"><i>Outras informações:</i></h4>
<fieldset>

    <div class='form-group'>
      <label class='col-md-2 control-label' for='sas_monitor'>Família acomp.:</label>
      <div class='col-md-4'>
        <select class='form-control' id='sas_monitor' name='sas_monitor'>
            <option value="t" <?=($d['sas_monitor']=='t'?"selected":"");?>>Sim</option>
            <option value="f" <?=($d['sas_monitor']=='f'?"selected":"");?>>Não</option>
        </select>
      </div>
    </div>
</fieldset>

<hr class="dotted">
<h4 class="mb-xlg text-primary"><i>Endereço:</i></h4>
<fieldset>
    <div class='form-group'>
      <label class='col-md-2 control-label' for='id_street'>Logradouro:</label>
      <div class='col-md-10'>
        <?
            $sql = "SELECT id, name FROM {$schema}streets ORDER BY name ASC";
            $res = pg_query($sql)or die("Error ".__LINE__."<br>Query: {$sql}");
        ?>
        <select class='form-control select2' id='id_street' name='id_street'>
            <option value="">- - -</option>
          <?
              while ($s = pg_fetch_assoc($res)) {
                if($s['id']==$d['id_street']){$sel="selected";}else{$sel="";}
                echo "<option value='{$s['id']}' {$sel}>{$s['name']}</option>";
              }
          ?>
        </select>
      </div>
    </div>

    <div class='form-group'>
      <label class='col-md-2 control-label' for='address_number'>Num:</label>
      <div class='col-md-2'>
        <input type='number' class='form-control' id='address_number' name='address_number' placeholder='' value='<?=$d['address_number'];?>'>
      </div>

      <label class='col-md-2 control-label' for='address_complement'>Complemento:</label>
      <div class='col-md-6'>
        <input type='text' class='form-control' id='address_complement' name='address_complement' placeholder='' value='<?=$d['address_complement'];?>'>
      </div>
    </div>

    <div class='form-group'>

      <label class='col-md-2 control-label' for='address_complement'>CEP:</label>
      <div class='col-md-2'>
        <input type='text' class='form-control' id='cep' name='cep' placeholder='' value='<?=$d['cep'];?>'>
      </div>


      <label class='col-md-2 control-label' for='id_neighborhood'>Bairro:</label>
      <div class='col-md-6'>
        <?
            $sql = "SELECT * FROM {$schema}neighborhood";
            $res = pg_query($sql)or die("Error ".__LINE__."<br>Query: {$sql}");
        ?>
        <select class='form-control select2' id='id_neighborhood' name='id_neighborhood'>
          <option value="">- - -</option>
          <?
              while ($n = pg_fetch_assoc($res)) {
                if($n['id']==$d['id_neighborhood']){$sel="selected";}else{$sel="";}
                echo "<option value='{$n['id']}' {$sel}>{$n['neighborhood']}</option>";
              }
          ?>
        </select>
      </div>

    </div>


  <div class='form-group'>
    <label class='col-md-2 control-label' for='address_reference'>Ponto ref.:</label>
    <div class='col-md-10'>
      <input type='text' class='form-control' id='address_reference' name='address_reference' placeholder='' value='<?=$d['address_reference'];?>'>
    </div>
  </div>
</fieldset>

<hr class="dotted">
    <div class='form-group'>
      <label class='col-md-2 control-label' for='observations'>Observações:</label>
      <div class='col-md-10'>
        <textarea class='form-control' id='observations' name='observations' rows='3'><?=$d['observations'];?></textarea>
      </div>
    </div>
<!---------------------------------------------------------------------------------->
<!---------------------------------------------------------------------------------->



                          </div>
                        </div>


<hr class="dotted">
                        <div class='row' style="margin-top:10px">
                          <div class='col-sm-8 col-sm-offset-2 text-center'>
                            <input type="hidden" name="acao" value="<?=$acao;?>" />
                            <a href="sas/cidadao.php"><button type="button" class="btn btn-default">Voltar</button></a>
                            <? if($acao=="Atualizar")
                                {
                                    echo "<input type='hidden' name='id' value='{$d['id']}'/>";

                                    if(check_perm("7_21","D"))
                                    {
                                      echo " <a href='sas/cidadao_FORM_tab0_SQL.php?id=".$d['id']."&acao=Remover'><button type='button' class='btn btn-danger loading2'>Remover</button></a>";
                                    }
                                    if(check_perm("7_21","U"))
                                    {
                                      echo " <button type='submit' class='btn btn-primary loading2'>".$acao."</button>";
                                    }
                                }

                                if($acao=="Inserir")
                                {
                                    echo "<input type='hidden' name='id_user_register' value='{$_SESSION['id']}'/>";
                                    echo "<input type='hidden' name='id_company_register' value='{$_SESSION['id_company']}'/>";
                                    echo "<input type='hidden' name='date' value='{$agora['datatimesrv']}'/>";
                                    if(check_perm("7_21","C"))
                                    {
                                      echo " <button type='submit' class='btn btn-primary loading2'>".$acao."</button>";
                                    }
                                }

                                if($_SESSION['error']!=""){
                                  echo "<p>{$_SESSION['error']}</p>";
                                }
                             ?>

                          </div>
                        </div>
                      </form>
<div class="panel-footer text-right" style="margin-right:-25px">
<?
  if($acao=="Atualizar"){
      echo "<small class='text-muted'><i>Cadastrado por <b>{$d['name_user_register']}</b><br><b>{$d['company_user_register']}</b>, em <b>".formataData($d['date'],1)."</b></i></small>";
  }else{
      echo "<small><i><span class='text-muted'>Realizando um novo cadatro.</span><br><span class='text-danger'>Certifique-se que já não foi cadatrado anteriormente.</span></i></small>";
  }
?>
</div>
<script>

  $('#cpf').mask('000.000.000-00', {reverse: true});
  //$("#rg").mask('99.999.99-[9|S]');
  $("#phone").mask("(00) 0 0000-0000");
  $("#phone1").mask("(00) 0 0000-0000");
  $("#phone2").mask("(00) 0 0000-0000");
  $("#cep").mask('00000-000');
  $('#average_income').mask("#.##0,00", {reverse: true});
  //$("#average_income").mask("9.999,00");
</script>
<? unset($_SESSION['error']); ?>
