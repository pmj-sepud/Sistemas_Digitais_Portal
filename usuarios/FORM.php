<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");

  if($_GET['id'] != "")
  {
    $acao = "atualizar";
    $sql  = "SELECT C.name AS name_company,
            			  C.acron AS acron_company,
            			  C.workshift_groups_repetition,
            			  C.workshift_groups,
                    C.workshift_subgroups_repetition,
            			  C.workshift_subgroups,
			              U.*
             FROM sepud.users U
	           JOIN sepud.company C ON C.id = U.id_company
             WHERE
              	U.id = '".$_GET['id']."'";
    $res  = pg_query($sql)or die("Erro ".__LINE__);
    $d    = pg_fetch_assoc($res);

    $sql = "SELECT
                R.value
              FROM
                sepud.users_rel_perm_user R
              WHERE
                R.id_user = '".$_GET['id']."'";
     $res = pg_query($sql)or die("SQL Error ".__LINE__);

     if(pg_num_rows($res))
     {
       $p               = pg_fetch_assoc($res);
       $userperms_resum = (array) json_decode(codificar($p['value'],'d'));
     }

     logger("Acesso","Perfil de usuário", "Acesso aos dados: [".$_GET["id"]."] - ".$d['name']);

     if($_GET['nav']=="permissoes"){ $nav_perm  = "active"; }
     else{                           $nav_dados = "active"; }
  }
?>
				<section role="main" class="content-body">
					<header class="page-header">
						<h2>Perfil do Usuário</h2>

						<div class="right-wrapper pull-right" style='margin-right:15px;'>
							<ol class="breadcrumbs">
								<li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
				        <li><span class='text-muted'>Configurações</span></li>
				        <li><a href="usuarios/index.php"><span>Usuários</span></a></li>
								<li><span class='text-muted'>Perfil do usuário</span></li>
							</ol>
						</div>
					</header>

					<!-- start: page -->

					<div class="row">
						<div class="col-md-12 col-lg-12">

							<div class="tabs">
								<ul class="nav nav-tabs tabs-primary">
									<li class="<?=$nav_dados;?>">
										<a href="#dados" data-toggle="tab" ajax='false'>Dados</a>
									</li>
                  <li class="<?=$nav_perm;?>">
										<a href="#permissoes" data-toggle="tab" ajax='false'>Permissões</a>
									</li>
                </ul>


								<div class="tab-content">
<!--------------------------------------------->
<!--------------------------------------------->
                  <div id="permissoes" class="tab-pane <?=$nav_perm;?>">
                      <div class="row">
                        <div class="col-md-12">

<form id="userform_perms" name="userform_perms" method="post" action="usuarios/FORM_sql.php">
                            <?
                                $sql = "SELECT M.id as id_module, M.descrition as module_description, M.module as module_name, M.show_order,
                                        			 S.id as id_perm, S.permission, S.description as perm_description, S.type
                                        FROM
                                        sepud.users_perm_modules M
                                        LEFT JOIN sepud.users_perm_modules_subgroup S ON S.id_module = M.id
                                        ORDER BY M.show_order ASC, S.id ASC";
                                $res = pg_query($sql)or die("SQL Error ".__LINE__);
                                while ($p = pg_fetch_assoc($res)) {
                                    $permissoes[$p['id_module']]['infos']['name']                       = $p['module_name'];
                                    $permissoes[$p['id_module']]['infos']['description']                = $p['module_description'];
                                    if($p['id_perm'] != "")
                                    {
                                      $permissoes[$p['id_module']]['perms'][$p['id_perm']]['name']        = $p['permission'];
                                      $permissoes[$p['id_module']]['perms'][$p['id_perm']]['description'] = $p['perm_description'];
                                      $permissoes[$p['id_module']]['perms'][$p['id_perm']]['type']        = $p['type'];
                                    }
                                }

      if(isset($permissoes))
      {
        echo "<table class='table table-hover'>";
        foreach($permissoes as $id_module => $dados)
        {
          echo "<tr class='info'>";
            echo "<td class='text-right' width='10px'><small>".$id_module."</small></td>";
            echo "<td colspan='3'><b>".$dados['infos']['name']."</b> - <span class=''>".$dados['infos']['description']."</span></td>";
          echo "</tr>";
          if(isset($dados['perms']))
          {
                    foreach ($dados['perms'] as $id_perm => $dados_perm)
                    {
                        echo "<tr>";
                          echo "<td class='text-muted text-right'><small>".$id_module.".".$id_perm."</small></td>";
                          echo "<td>".$dados_perm['name']."</td>";
                          echo "<td>".$dados_perm['description']."</td>";


                            if($dados_perm['type']=="CRUD")
                            {
                              //0 1 2 3
                              //C R U D
                              echo "<td class='' width='200px'>";
                              echo "<table class='table table-condensed' style='margin-bottom:-5px'>";
                              echo "<tr><td class=''>
                                                  <div class='checkbox-custom checkbox-default'>
                                                      <input type  ='checkbox' class='crud'
                                                             value ='1'
                                                             id    ='".$id_perm."_c' ".
                                                             ($userperms_resum[$id_module."_".$id_perm][0]=="1"?"checked":"").">
                                                             <label><span class='text-muted'><small> Incluir</small></label>
                                                    </div>
                                        </td>
          													    <td class=''>
                                                    <div class='checkbox-custom checkbox-default'>
                                                                <input type  ='checkbox' class='crud'
                                                                       value ='1'
                                                                       id    ='".$id_perm."_d' ".
                                                                       ($userperms_resum[$id_module."_".$id_perm][3]=="1"?"checked":"").">
                                                                       <label><span class='text-muted'><small> Remover</small></label>
                                                      </div>
                                        </td></tr>";
                              echo "<tr><td class=''>
                                              <div class='checkbox-custom checkbox-default'>
                                                          <input type  ='checkbox' class='crud'
                                                                 value ='1'
                                                                 id    ='".$id_perm."_r' ".
                                                                 ($userperms_resum[$id_module."_".$id_perm][1]=="1"?"checked":"").">
                                                                 <label><span class='text-muted'><small> Visualizar</small></label>
                                                </div>
                                        </td>
          													    <td class=''>
                                              <div class='checkbox-custom checkbox-default'>
                                                          <input type  ='checkbox' class='crud'
                                                                 value ='1'
                                                                 id    ='".$id_perm."_u'" .
                                                                 ($userperms_resum[$id_module."_".$id_perm][2]=="1"?"checked":"").">
                                                                 <label><span class='text-muted'><small> Atualização</small></label>
                                                </div>
                                        </td></tr>";
                              echo "</table>";
                              echo "<input type='hidden' id='".$id_perm."' name='".$id_module."_".$id_perm."' style='margin-top:10px' value='".$userperms_resum[$id_module."_".$id_perm]."'>";

                              echo "</td>";
                            }

                            if($dados_perm['type']=="Bool")
                            {

                              //echo "<td class='text-center' width='200px'>";
                              //echo "<label><input type='checkbox' value='1' id='".$id_perm."' name='".$id_perm."' ".($userperms_resum[$id_module.".".$id_perm]=="1"?"checked":"")." ><span class='text-muted'><small> Ativar</small></label></span>";
                              //echo "</td>";

                              echo "<td class='' width='200px'>
                                      <div class='checkbox-custom checkbox-default' style='margin-left:5px'>
                                        <input type  ='checkbox'
                                               value ='1'
                                               name  ='".$id_module."_".$id_perm."' ".
                                               ($userperms_resum[$id_module."_".$id_perm]=="1"?"checked":"").">
                                               <label><span class='text-muted'><small> Ativar</small></label>
                                      </div>
                                    </td>";
                            }
                            //echo $userperms_resum[$id_module.".".$id_perm];
                          echo "</td>";
                        echo "</tr>";
                    }
         }
        }
        echo "</table>";
      }
?>

<div class="panel-footer"  style="margin-top:20px;height:60px;margin-bottom:10px;">
	<div class="row pull-right">
		<!--<div class="col-md-9 col-md-offset-3">-->
			<div class="col-md-12">
              <input type='hidden' name='acao' value='permissoes' />
              <input type='hidden' name='id' value='<?=$_GET['id'];?>' />
              <a href='usuarios/index.php'><button type='button' class='btn btn-default loading'>Voltar</button></a>
              <? if(check_perm("1_4")){ ?>
              <button type='submit' class='btn btn-primary loading'>Atualizar permissões</button>
              <? } ?>
        </div>
    </div>
</div>
</form>
                        </div>
                      </div>
                  </div>
<!--------------------------------------------->
<!--------------------------------------------->
									<div id="dados" class="tab-pane <?=$nav_dados;?>">
                    <div class="row">
                      <div class="col-md-6 col-md-offset-3">

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
                            <select class="form-control" id="id_company" name="id_company">
                                <?
                                    $sql = "SELECT * FROM sepud.company ORDER BY name ASC";
                                    $res = pg_query($sql)or die();
                                    while($comp = pg_fetch_assoc($res))
                                    {
                                        if($comp['id'] == $d['id_company']){ $sel = "selected"; }else{ $sel = "";}
                                        echo "<option value='".$comp['id']."' ".$sel.">".$comp['name']."</option>";
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
														<input type="text" class="form-control" onclick="$(this).val('');" name="email" id="email" placeholder='Endereço de e-mail' value="<?=($d['email']!=""?$d['email']:"Endereço de e-mail");?>">
													</div>
												</div>

												<div class="form-group">
													<label class="col-md-2 control-label" for="senha">Senha</label>
													<div class="col-md-5">
														<input type="password"  onclick="$(this).val('');"  class="form-control" name="senha" id="senha" placeholder='Nova senha' value="nova_senha">
													</div>
													<div class="col-md-5">
														<input type="password"  onclick="$(this).val('');"  class="form-control" name="senha_repete" id="senha_repete"  placeholder='Repita nova senha' value="nova_senha">
													</div>
												</div>

                        <div class="form-group">
                          <label class="col-md-2 control-label" for="active">Status</label>
                          <div class="col-md-10">
                            <select class="form-control" id="active" name="active">

                                <option value="t" <?=($d['active']=="t"?"selected":"");?>>Ativo</option>
                                <option value="f" <?=($d['active']=="f"?"selected":"");?>>Inativo</option>

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
						</div>

					<!-- end: page -->
				</section>
<script>

$(".crud").click(function(){

    var str     = $(this).attr('id').split("_");
    var perm    = str[0];
    var str_bin = "";

    if($("#"+perm+"_c").is(":checked")){str_bin += "1";}else{str_bin += "0";}
    if($("#"+perm+"_r").is(":checked")){str_bin += "1";}else{str_bin += "0";}
    if($("#"+perm+"_u").is(":checked")){str_bin += "1";}else{str_bin += "0";}
    if($("#"+perm+"_d").is(":checked")){str_bin += "1";}else{str_bin += "0";}

    //var hex = parseInt(str_bin, 2).toString(16);
    //$("#"+perm).val(hex.toUpperCase());

    $("#"+perm).val(str_bin);

});


$(document).ready(function(){

    $(window).scrollTop(0);

    $('#tabela_dinamica').DataTable({
      responsive: true,
      language: {
        processing:     "Pesquisando...",
        search:         "Pesquisar:",
        lengthMenu:     "_MENU_ &nbsp;Registros por página.",
        info:           "Mostrando _START_ a _END_ de um total de  _TOTAL_ registros.",
        infoEmpty:      "0 registros encontrado.",
        infoFiltered:   "(_MAX_ registros pesquisados)",
        infoPostFix:    "",
        loadingRecords: "Carregando registros...",
        zeroRecords:    "Nenhum registro encontrado com essa característica.",
        emptyTable:     "Nenhuma informação nesta tabela de dados.",
        paginate: {
            first:      "Primeiro",
            previous:   "Anterior",
            next:       "Próximo",
            last:       "Último"
        },
        aria: {
            sortAscending:  ": Ordem ascendente.",
            sortDescending: ": Ordem decrescente."
        }
    }
    });
});



$(".campo_hora").mask('00:00');
$(".loading").click(function(){ $(this).html("<i class=\"fa fa-spinner fa-spin\"></i> <small>Aguarde.</small>"); });
$("#userform").on("submit", function(){
  if($("#email").val()        == "Endereço de e-mail"){ $("#email").val('');}
  if($("#senha").val()        == "nova_senha")        { $("#senha").val('');}
  if($("#senha_repete").val() == "nova_senha")        { $("#senha_repete").val('');}

});
</script>
