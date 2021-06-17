<?
  session_start();
?>
<div id="acesso" class="tab-pane <?=$nav['acesso'];?>">
   <div class="row">
      <div class="col-md-8 col-md-offset-2">
         <!-------------------------------------------------------->
         <form id="userform_acesso" name="userform_acesso" method="post" action="usuarios/FORM_sql.php">
            <hr class="dotted">
            <h4 class="mb-xlg">Informações de acesso</h4>
               <fieldset class="mb-xl">
                  <div class="form-group">
                     <label class="col-md-2 control-label" for="email">E-mail</label>
                     <div class="col-md-10">
                        <input type="text" class="form-control" onclick="$(this).val('');" name="email" id="email" placeholder='Endereço de e-mail' value="<?=($d['email']!=""?$d['email']:"Endereço de e-mail");?>"
                               readonly
                               onfocus="if(this.hasAttribute('readonly')){this.removeAttribute('readonly');this.blur();this.focus();}"
                               onmouseover="this.style.cursor='pointer'">
                     </div>
                  </div>

                  <div class="form-group">
                     <label class="col-md-2 control-label" for="senha">Senha</label>
                     <div class="col-md-5">
                        <input type="password"  onclick="$(this).val('');"  class="form-control" name="senha" id="senha" placeholder='Nova senha' value="nova_senha"
                               readonly
                               onfocus="if(this.hasAttribute('readonly')){this.removeAttribute('readonly');this.blur();this.focus();}"
                               onmouseover="this.style.cursor='pointer'">
                     </div>
                     <div class="col-md-5">
                        <input type="password"  onclick="$(this).val('');"  class="form-control" name="senha_repete" id="senha_repete"  placeholder='Repita nova senha' value="nova_senha"
                               readonly
                               onfocus="if(this.hasAttribute('readonly')){this.removeAttribute('readonly');this.blur();this.focus();}"
                               onmouseover="this.style.cursor='pointer'">
                     </div>
                  </div>

                  <div class="form-group">
                     <label class="col-md-2 control-label" for="active">Status da conta</label>
                     <div class="col-md-10">
                     <select class="form-control" id="active" name="active">
                        <option value="t" <?=($d['active']=="t"?"selected":"");?>>Ativo</option>
                        <option value="f" <?=($d['active']=="f"?"selected":"");?>>Inativo</option>
                        </select>
                     </div>
                  </div>

                  <div class="form-group">
                     <label class="col-md-2 control-label" for="active">Trocar senha</label>
                     <div class="col-md-10">
                     <select class="form-control" id="in_activation" name="in_activation">
                        <option value="t" <?=($d['in_activation']=="t"?"selected":"");?>>Sim</option>
                        <option value="f" <?=($d['in_activation']=="f"?"selected":"");?>>Não</option>
                     </select>
                     </div>
                  </div>
               </fieldset>

               <div class="panel-footer"  style="margin-top:20px;height:60px;margin-bottom:10px;">
                  <div class="row pull-right">
                     <div class="col-md-12">
                        <?
                           if(check_perm("1_1","U")){
                              echo "<input type='hidden' name='acao' value='atualizar_acesso' />";
                              echo "<input type='hidden' name='id' value='{$_GET['id']}' />";
                              echo " <a href='usuarios/index.php'><button type='button' class='btn btn-default loading'>Voltar</button></a>&nbsp;";
                              echo " <a href='usuarios/FORM_sql.php?acao=remover&id={$_GET['id']}'><button type='button' class='btn btn-danger loading'><i class='fa fa-trash'></i> Remover</button></a>&nbsp;";
                              echo " <button type='submit' class='btn btn-primary loading'>Atualizar</button>";
                           }
                        ?>
                     </div>
                  </div>
               </div>
         </form>
         <!-------------------------------------------------------->
      </div>
   </div>
   <div class="row">
      <div class="col-md-8 col-md-offset-2">
         <?
            if($_SESSION['error']!=""){
               echo "<div class='alert alert-warning'>{$_SESSION['error']}</div>";
               unset($_SESSION['error']);
            }
         ?>
      </div>
   </div>
</div>
