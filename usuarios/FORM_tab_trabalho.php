<?
  session_start();
?>
<div id="trabalho" class="tab-pane <?=$nav['trabalho'];?>">
   <div class="row">
      <div class="col-md-8 col-md-offset-2">
         <!-------------------------------------------------------------------->
         <form id="userform_trabalho" name="userform_trabalho" method="post" action="usuarios/FORM_sql.php">
            <hr class="dotted">
            <h4 class="mb-xlg">Turno de trabalho</h4>
               <fieldset class="mb-xl">

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
                                   <div class="col-md-6">
                                       <?
                                           if($d['workshift_groups']!=""){
                                             echo "<select name='workshift_group' class='form-control'>";
                                             echo "<option value=''>- - -</option>";
                                             $grupos = json_decode($d['workshift_groups']);
                                             for($i=0;$i<count($grupos);$i++){
                                               if($d['workshift_group'] == $grupos[$i]){ $sel = "selected"; }else{ $sel = "";}
                                               echo "<option value='".$grupos[$i]."' ".$sel.">".$grupos[$i]."</option>";
                                             }
                                             echo "</select>";
                                           }else{
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
                                 echo "<option value=''>- - -</option>";
                                 $subgrupos = json_decode($d['workshift_subgroups']);
                                 for($i=0;$i<count($subgrupos);$i++){
                                   if($d['workshift_subgroup'] == $subgrupos[$i]){ $sel = "selected"; }else{ $sel = "";}
                                   echo "<option value='".$subgrupos[$i]."' ".$sel.">".$subgrupos[$i]."</option>";
                                 }
                                 echo "</select>";
                               }else{
                                 echo "<select name='workshift_group' class='form-control' disabled>";
                                 echo "<option value=''>Informações do turno não configuradas.</option>'";
                                 echo "</select>";
                               }
                           ?>
                        </div>
                  </div>
               </fieldset>

            <div class="panel-footer"  style="margin-top:20px;height:60px;margin-bottom:10px;">
               <div class="row pull-right">
                  <div class="col-md-12">
                     <?
                        if(check_perm("1_1","U")){
                           echo "<input type='hidden' name='acao' value='atualizar_trabalho' />";
                           echo "<input type='hidden' name='id' value='".$_GET['id']."' />";
                           echo " <a href='usuarios/index.php'><button type='button' class='btn btn-default loading'>Voltar</button></a>&nbsp;";
                           echo " <button type='submit' class='btn btn-primary loading'>Atualizar</button>";
                        }
                     ?>
                  </div>
               </div>
            </div>
         </form>
         <!-------------------------------------------------------------------->
      </div>
   </div>
</div>
