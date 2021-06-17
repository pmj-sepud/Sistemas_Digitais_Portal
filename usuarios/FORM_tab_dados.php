<?
  session_start();
?>
<div id="dados" class="tab-pane <?=$nav['dados'];?>">
   <div class="row">
      <div class="col-md-8 col-md-offset-2">
         <!------------------------------------------------------------------------------>
         <!------------------------------------------------------------------------------>
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
            <div class="panel-footer"  style="margin-top:20px;height:60px;margin-bottom:10px;">
               <div class="row pull-right">
                  <div class="col-md-12">
                     <?
                        if(check_perm("1_1","U")){
                           echo "<input type='hidden' name='acao' value='atualizar_dados' />";
                           echo "<input type='hidden' name='id' value='".$_GET['id']."' />";
                           echo " <a href='usuarios/index.php'><button type='button' class='btn btn-default loading'>Voltar</button></a>&nbsp;";
                           echo " <button type='submit' class='btn btn-primary loading'>Atualizar</button>";
                        }
                     ?>
                  </div>
               </div>
            </div>
         </form>
         <!------------------------------------------------------------------------------>
         <!------------------------------------------------------------------------------>
      </div>
   </div>
</div>
