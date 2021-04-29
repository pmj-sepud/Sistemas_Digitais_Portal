                              <div class="row">
                                <div class="col-md-6 col-md-offset-3">

                              <form autocomplete="off" id="userform" name="userform" class="form-horizontal" method="post" action="configs/company_SQL.php" debug='0'>

                                <h4 class="mb-xlg">Dados principais</h4>
                                <fieldset>
                                  <div class="form-group">
                                    <label class="col-md-2 control-label" for="name">Nome</label>
                                    <div class="col-md-10">
                                      <input type="text" class="form-control" id="name" name="name" placeholder='Nome completo' value="<?=$d['name'];?>">
                                    </div>
                                  </div>

                                  <div class="form-group">
                                    <label class="col-md-2 control-label" for="name">Apelido</label>
                                    <div class="col-md-10">
                                      <input type="text" class="form-control" id="acron" name="acron" placeholder='Apelido' value="<?=$d['acron'];?>">
                                    </div>
                                  </div>

                                  <div class="form-group">
                                    <label class="col-md-2 control-label" for="id_father">Setor pai:</label>
                                    <div class="col-md-10">
                                      <?
                                            if($acao=="atualizar")
                                            {
                                                $sql = "SELECT id, name FROM {$schema}company WHERE id <>'{$_GET['id']}' AND id_father is null AND active = 't' ORDER BY name ASC";
                                            }else {
                                                $sql = "SELECT id, name FROM {$schema}company WHERE id_father is null AND active = 't' ORDER BY name ASC";
                                            }
                                                $res = pg_query($sql)or die("Error ".__LINE__."<br>SQL: {$sql}");
                                                $optCompany .= "<option value=''>- - -</option>";
                                                while($u = pg_fetch_assoc($res)){
                                                  if($d['id_father'] == $u['id']){$sel = "selected";}else{$sel="";}
                                                  $optCompany .= "<option value='{$u['id']}' {$sel}>{$u['name']}</option>";
                                                }

                                      ?>
                                      <select class='form-control select2' name='id_father'>
                                        <?=$optCompany;?>
                                      </select>
                                    </div>
                                  </div>

                                  <div class="form-group">
                                    <label class="col-md-2 control-label" for="phone">Telefone</label>
                                    <div class="col-md-10">
                                      <input type="text" class="form-control" id="phone" name="phone" placeholder='(xx) xxxxx-xxxx' value="<?=$d['phone'];?>">
                                    </div>
                                  </div>


                                  <div class="form-group">
                                    <label class="col-md-2 control-label" for="observation">Observações</label>
                                    <div class="col-md-10">
                                      <textarea class="form-control" name="observations" id="observations"><?=$d['observations'];?></textarea>
                                    </div>
                                  </div>
                                </fieldset>


                                <hr class="dotted">
                                <h4 class="mb-xlg">Informações adicionais</h4>
                                <fieldset class="mb-xl">
                                  <div class="form-group">
                                    <label class="col-md-2 control-label" for="id_user_contact">Contato</label>
                                    <div class="col-md-10">
                                      <?
                                        if($acao == "atualizar")
                                        {
                                                $sql = "SELECT id, name FROM {$schema}users WHERE id_company='{$_GET['id']}' ORDER BY name ASC";
                                                $res = pg_query($sql)or die("Error ".__LINE__."<br>SQL: {$sql}");
                                                $optUsers .= "<option value=''>- - -</option>";
                                                while($u = pg_fetch_assoc($res)){
                                                  if($d['id_user_contact'] == $u['id']){$sel = "selected";}else{$sel="";}
                                                  $optUsers .= "<option value='{$u['id']}' {$sel}>{$u['name']}</option>";
                                                }
                                        }else{
                                                $optUsers .= "<option value=''>- - -</option>";
                                        }
                                      ?>
                                      <select class='form-control select2' name='id_user_contact'>
                                        <?=$optUsers;?>
                                      </select>
                                    </div>
                                  </div>



                                  <div class="form-group">
                                    <label class="col-md-2 control-label" for="active">Status</label>
                                    <div class="col-md-6">
                                      <select class="form-control" id="active" name="active">
                                          <option value="t" <?=($d['active']=="t"?"selected":"");?>>Ativo</option>
                                          <option value="f" <?=($d['active']=="f"?"selected":"");?>>Inativo</option>
                                      </select>
                                    </div>
                                  </div>

                                  <div class="form-group">
                                    <label class="col-md-3 control-label" for="active">Externo a PMJ?</label>
                                    <div class="col-md-5">
                                      <select class="form-control" id="is_external" name="is_external">
                                          <option value="f" <?=($d['is_external']=="f"?"selected":"");?>>Interno</option>
                                          <option value="t" <?=($d['is_external']=="t"?"selected":"");?>>Externo</option>
                                      </select>
                                    </div>
                                  </div>

                                </fieldset>


                                <div class="panel-footer"  style="margin-top:20px;height:60px;margin-bottom:10px;">
                                  <div class="row pull-right">
                                    <!--<div class="col-md-9 col-md-offset-3">-->
                                      <div class="col-md-12">

                            <? if($acao != "atualizar")
                            {
                            if(check_perm("2_20","C")){
                            echo "<input type='hidden' name='acao' value='inserir' />";
                            echo " <a href='configs/company.php'><button type='button' class='btn btn-default loading'>Voltar</button></a>&nbsp;";
                            echo "<button type='submit' class='btn btn-primary pull-right loading'>Inserir</button>";
                            }
                            }else{
                            if(check_perm("2_20","U")){
                            echo "<input type='hidden' name='acao' value='atualizar' />";
                            echo "<input type='hidden' name='id' value='".$_GET['id']."' />";
                            echo " <a href='configs/company.php'><button type='button' class='btn btn-default loading'>Voltar</button></a>&nbsp;";
                            echo " <button type='submit' class='btn btn-primary loading'>Atualizar</button>";
                            }


                            }
                            ?>
                                    </div>
                                  </div>
                                </div>

                              </form>
                            </div>
                            </div>
