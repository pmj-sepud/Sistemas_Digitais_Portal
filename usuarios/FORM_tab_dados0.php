<?
  session_start();
?>
<div id="dados" class="tab-pane active">
                 <div class="row">
                   <div class="col-md-8 col-md-offset-2">

                           <form autocomplete="off" id="userform" name="userform" class="form-horizontal" method="post" action="usuarios/FORM_sql.php" debug='0'>

                   <h4 class="mb-xlg">Informações Pessoais</h4>
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
                         <input type="text" class="form-control" id="nickname" name="nickname" placeholder='Nome de guerra' value="<?=$d['nickname'];?>">
                       </div>
                     </div>

                                 <div class="form-group">
                                    <label class="col-md-2 control-label" for="phone">Telefone</label>
                                    <div class="col-md-10">
                                       <input type="text" class="form-control" id="phone" name="phone" placeholder='(xx) xxxxx-xxxx' value="<?=$d['phone'];?>">
                                    </div>
                                 </div>

                     <div class="form-group">
                       <label class="col-md-2 control-label" for="registration">Matrícula</label>
                       <div class="col-md-10">
                         <input type="text" class="form-control" id="registration" name="registration" placeholder='' value="<?=$d['registration'];?>">
                       </div>
                     </div>

                     <div class="form-group">
                       <label class="col-md-2 control-label" for="cargo">Orgão</label>
                       <div class="col-md-10">
                         <select class="form-control select2" id="id_company" name="id_company">
                             <?
                                 $sql = "SELECT id, name, acron, id_father FROM ".$schema."company WHERE active = 't' ORDER BY name ASC";
                                 $res = pg_query($sql)or die();
                                 while($comp = pg_fetch_assoc($res))
                                 {

                                     if($comp['id_father']!=""){
                                       $orgao_filhos[$comp['id_father']]['filhos'][]=$comp;
                                     }else{
                                       $orgao[$comp['id']]=$comp;
                                     }
                                 //    if($comp['id'] == $d['id_company']){ $sel = "selected"; }else{ $sel = "";}
                                 //    echo "<option value='".$comp['id']."' ".$sel.">".$comp['name']."</option>";
                                 }
                                 foreach ($orgao_filhos as $id_pai => $filhos) { $orgao[$id_pai]['filhos'] = $filhos['filhos'];}
                                 foreach ($orgao as $id_orgao => $orgao_dados){
                                   echo "<optgroup label='".$orgao_dados['name']."'>";
                                       for($i=0;$i<count($orgao_dados['filhos']);$i++)
                                       {
                                         if($orgao_dados['filhos'][$i]['id'] == $d['id_company']){ $sel = "selected"; }else{ $sel = "";}
                                         echo "<option value='".$orgao_dados['filhos'][$i]['id']."' ".$sel.">".$orgao_dados['filhos'][$i]['name']."</option>";
                                       }

                                   echo "</option>";
                                 }

                             ?>
                         </select>

                       </div>
                     </div>


                     <div class="form-group">
                       <label class="col-md-2 control-label" for="area">Setor</label>
                       <div class="col-md-10">
                         <input type="text" class="form-control" id="area" name="area" placeholder="Setor" value="<?=$d['area'];?>">
                       </div>
                     </div>

                     <div class="form-group">
                                    <label class="col-md-2 control-label" for="job">Cargo</label>
                                    <div class="col-md-10">
                                       <input type="text" class="form-control" id="job" name="job" placeholder="Cargo" value="<?=$d['job'];?>">
                                    </div>
                                 </div>

                     <div class="form-group">
                                    <label class="col-md-2 control-label" for="observation">Observações</label>
                                    <div class="col-md-10">
                         <textarea class="form-control" name="observation" id="observation"><?=$d['observation'];?></textarea>
                                   </div>
                                 </div>
                              </fieldset>



   <hr class="dotted">
   <h4 class="mb-xlg">Situação de trabalho:</h4>
   <fieldset class="mb-xl">

                     <div class="form-group">
                       <label class="col-md-2 control-label" for="work_status">Situação</label>
                       <div class="col-md-10">
                         <select name="work_status" class="form-control">
                             <option value="ativo"             <?=($d['work_status']=="ativo"?"selected":"");?>>Ativo</option>
                             <option value="HE-Compensação"    <?=($d['work_status']=="HE-Compensação"?"selected":"");?>>Hora extra - Compensação</option>
                             <option value="HF-Compensação"    <?=($d['work_status']=="HF-Compensação"?"selected":"");?>>Hora falta - Compensação</option>
                             <option value="Serviços"          <?=($d['work_status']=="Serviços"?"selected":"");?>>Serviços extraordinários</option>
                             <option value="folga"             <?=($d['work_status']=="folga"?"selected":"");?>>Folga</option>
                             <option value="troca"             <?=($d['work_status']=="troca"?"selected":"");?>>Troca</option>
                             <option value="ferias"            <?=($d['work_status']=="ferias"?"selected":"");?>>Férias</option>
                             <option value="falta"             <?=($d['work_status']=="falta"?"selected":"");?>>Faltou</option>
                             <option value="atestado"          <?=($d['work_status']=="atestado"?"selected":"");?>>Atestado</option>
                             <option value="licença"           <?=($d['work_status']=="licença"?"selected":"");?>>Licença</option>
                         </select>
                    </div>
                  </div>

   </fieldset>


                   <hr class="dotted">
                   <h4 class="mb-xlg">Turno de trabalho</h4>
                   <fieldset class="mb-xl">

                     <div class="row">
                       <div class="col-sm-12">
                         <div class="form-group">
                           <label class="col-md-6 control-label" for="initial_workshift_position">Posição inicial de trabalho:</label>
                           <div class="col-md-6">
                             <select class="form-control" id="initial_workshift_position" name="initial_workshift_position">
                               <option value="">- - -</option>
                               <option value="agente"      <?=($d['initial_workshift_position']=="agente"?"selected":"");?>>Agente de campo</option>
                               <option value="central"     <?=($d['initial_workshift_position']=="central"?"selected":"");?>>Central de atendimento</option>
                               <option value="coordenacao" <?=($d['initial_workshift_position']=="coordenacao"?"selected":"");?>>Coordenação</option>
                               <option value="gerencia"    <?=($d['initial_workshift_position']=="gerencia"?"selected":"");?>>Direção</option>
                             </select>
                       </div>
                    </div>

                     <div class="row">
                       <div class="col-sm-12">

                                 <div class="form-group">
                                                <label class="col-md-2 control-label" for="work_time_init">Horário</label>
                                                <div class="col-md-2">
                                                   <input type="text" class="form-control campo_hora" name="workshift_group_time_init" id="workshift_group_time_init" placeholder="Inicio" value="<?=$d['workshift_group_time_init'];?>" <?=($d['workshift_groups']==""?"disabled":"");?>>
                                                </div>
                                   <div class="col-md-2">
                                                   <input type="text" class="form-control campo_hora" name="workshift_group_time_finish" id="workshift_group_time_finish" placeholder="Fim" value="<?=$d['workshift_group_time_finish'];?>" <?=($d['workshift_groups']==""?"disabled":"");?>>
                                                </div>

                                                <!--<label class="col-md-2 control-label" for="workshift_group">Grupo</label>-->
                                                <div class="col-md-6">
                                     <?

                                         if($d['workshift_groups']!="")
                                         {
                                           echo "<select name='workshift_group' class='form-control'>";
                                           echo "<option value=''></option>";
                                           $grupos = json_decode($d['workshift_groups']);
                                           for($i=0;$i<count($grupos);$i++)
                                           {
                                             if($d['workshift_group'] == $grupos[$i]){ $sel = "selected"; }else{ $sel = "";}
                                             echo "<option value='".$grupos[$i]."' ".$sel.">".$grupos[$i]."</option>";
                                           }
                                           echo "</select>";
                                         }else {
                                           echo "<select name='workshift_subgroup' class='form-control' disabled>";
                                           echo "<option value=''>Informações do turno não configuradas.</option>'";
                                           echo "</select>";
                                         }



                                     ?>
                                               </div>
                                             </div>
                           </div>
                       </div>
                   </fieldset>

                   <hr class="dotted">
                   <h4 class="mb-xlg">Turno de trabalho (Sub-grupo)</h4>
                   <fieldset class="mb-xl">
                     <div class="form-group">
                                    <label class="col-md-2 control-label" for="work_time_init">Horário</label>
                                    <div class="col-md-2">
                                       <input type="text" class="form-control campo_hora" name="workshift_subgroup_time_init" id="workshift_subgroup_time_init" placeholder="Inicio" value="<?=$d['workshift_subgroup_time_init'];?>" <?=($d['workshift_subgroups']==""?"disabled":"");?>>
                                    </div>
                       <div class="col-md-2">
                                       <input type="text" class="form-control campo_hora" name="workshift_subgroup_time_finish" id="workshift_subgroup_time_finish" placeholder="Fim" value="<?=$d['workshift_subgroup_time_finish'];?>" <?=($d['workshift_subgroups']==""?"disabled":"");?>>
                                    </div>


                                    <div class="col-md-6">
                         <?

                             if($d['workshift_subgroups']!="")
                             {
                               echo "<select name='workshift_subgroup' class='form-control'>";
                               echo "<option value=''></option>";
                               $subgrupos = json_decode($d['workshift_subgroups']);
                               for($i=0;$i<count($subgrupos);$i++)
                               {
                                 if($d['workshift_subgroup'] == $subgrupos[$i]){ $sel = "selected"; }else{ $sel = "";}
                                 echo "<option value='".$subgrupos[$i]."' ".$sel.">".$subgrupos[$i]."</option>";
                               }
                               echo "</select>";
                             }else {
                               echo "<select name='workshift_group' class='form-control' disabled>";
                               echo "<option value=''>Informações do turno não configuradas.</option>'";
                               echo "</select>";
                             }



                         ?>
                                   </div>
                                 </div>
                   </fieldset>


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
                                    <!--<div class="col-md-9 col-md-offset-3">-->
                                       <div class="col-md-12">

<? if($acao != "atualizar")
{
if(check_perm("1_1","C")){
           echo "<input type='hidden' name='acao' value='inserir' />";
           echo "<button type='submit' class='btn btn-primary pull-right loading'>Inserir</button>";
}
}else{
if(check_perm("1_1","U")){
           echo "<input type='hidden' name='acao' value='atualizar' />";
           echo "<input type='hidden' name='id' value='".$_GET['id']."' />";
           echo " <a href='usuarios/index.php'><button type='button' class='btn btn-default loading'>Voltar</button></a>&nbsp;";
           echo " <button type='submit' class='btn btn-primary loading'>Atualizar</button>";
}

if(check_perm("1_1","C")){
           //echo " <a href='usuarios/FORM_novo_usuario.php'><button type='button' class='btn btn-primary loading'><i class='fa fa-user-plus'></i> Novo usuário</button></a>";
}
}
?>
                                 </div>
                                 </div>
                              </div>

                           </form>
</div>
</div>


                        </div>



             </div>




                  </div>
