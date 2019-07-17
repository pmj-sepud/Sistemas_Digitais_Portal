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
    logger("Acesso","Perfil de usuário", "Acesso aos dados: [".$_GET["id"]."] - ".$d['name']);
  }
  /*
  Array
(
[name_company] => Departamento de Trânsito
[acron_company] => DETRANS
[workshift_groups_repetition] => 1
[workshift_groups] => ["Turno"]
[workshift_subgroups_repetition] => 4
[workshift_subgroups] => ["Alfa","Bravo","Charlie","Delta"]
[id] => 88
[name] => Cristiano Luis Bergmann
[email] => cristiano.bergmann@joinville.sc.gov.br
[password] => b59a51a3c0bf9c5228fde841714f523a
[nickname] =>
[area] => Seprot
[job] => Agente de Transito
[active] => t
[in_ativaction] => f
[company_acron] =>
[id_company] => 3
[phone] => 47 99760-1693
[observation] =>
[cpf] =>
[date_of_birth] =>
[registration] =>
[workshift_group_time_init] =>
[workshift_group_time_finish] =>
[workshift_group] =>
[workshift_subgroup_time_init] =>
[workshift_subgroup_time_finish] =>
[workshift_subgroup] =>
)
  */

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
									<li class="active">
										<a href="#dados" data-toggle="tab">Dados</a>
									</li>
                </ul>
								<div class="tab-content">
									<div id="dados" class="tab-pane active">



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
                          <label class="col-md-2 control-label" for="registration">Matricula</label>
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
              echo "<input type='hidden' name='acao' value='inserir' />";
              echo "<button type='submit' class='btn btn-primary pull-right loading'>Inserir</button>";
}else {
              echo "<input type='hidden' name='acao' value='atualizar' />";
              echo "<input type='hidden' name='id' value='".$_GET['id']."' />";
              echo " <a href='usuarios/index.php'><button type='button' class='btn btn-default loading'>Voltar</button></a>&nbsp;";
              echo " <a href='usuarios/FORM_novo_usuario.php'><button type='button' class='btn btn-primary loading'><i class='fa fa-user-plus'></i> Novo usuário</button></a>";
              echo " <button type='submit' class='btn btn-primary loading'>Atualizar</button>";
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
$(".campo_hora").mask('00:00');
$(".loading").click(function(){ $(this).html("<i class=\"fa fa-spinner fa-spin\"></i> <small>Aguarde.</small>"); });
$("#userform").on("submit", function(){
  if($("#email").val()        == "Endereço de e-mail"){ $("#email").val('');}
  if($("#senha").val()        == "nova_senha")        { $("#senha").val('');}
  if($("#senha_repete").val() == "nova_senha")        { $("#senha_repete").val('');}

});
</script>
