<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  $acao = "inserir";
  logger("Acesso","Novo usuário");

?>
				<section role="main" class="content-body">
					<header class="page-header">
						<h2>Perfil do Usuário</h2>

						<div class="right-wrapper pull-right" style='margin-right:15px;'>
							<ol class="breadcrumbs">
								<li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
				        <li><span class='text-muted'>Configurações</span></li>
				        <li><a href="usuarios/index.php"><span>Usuários</span></a></li>
								<li><span class='text-muted'>Novo usuário</span></li>
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

										<form id="userform" name="userform" class="form-horizontal" method="post" action="usuarios/FORM_sql.php" debug='0'>


                      <h4 class="mb-xlg">Informações Pessoais</h4>
											<fieldset>
												<div class="form-group">
													<label class="col-md-2 control-label" for="name">Nome</label>
													<div class="col-md-10">
														<input type="text" class="form-control" id="name" name="name" placeholder='Nome completo'>
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
														<input type="text" class="form-control" id="phone" name="phone" placeholder='(xx) xxxxx-xxxx'>
													</div>
												</div>

                        <div class="form-group">
                          <label class="col-md-2 control-label" for="registration">Matrícula</label>
                          <div class="col-md-10">
                            <input type="text" class="form-control" id="registration" name="registration" placeholder='' value="<?=$d['registration'];?>">
                          </div>
                        </div>


                        <div class="form-group">
                          <label class="col-md-2 control-label" for="id_company">Orgão</label>
                          <div class="col-md-10">
                            <select class="form-control" id="id_company" name="id_company">
                                <?
                                    $sql = "SELECT * FROM sepud.company ORDER BY name ASC";
                                    $res = pg_query($sql)or die();
                                    while($d = pg_fetch_assoc($res))
                                    {
                                        echo "<option value='".$d['id']."'>".$d['name']."</option>";
                                    }
                                ?>
                            </select>
                          </div>
                        </div>


                        <div class="form-group">
                          <label class="col-md-2 control-label" for="area">Setor</label>
                          <div class="col-md-10">
                            <input type="text" class="form-control" id="area" name="area" placeholder="Setor">
                          </div>
                        </div>

                        <div class="form-group">
													<label class="col-md-2 control-label" for="job">Cargo</label>
													<div class="col-md-10">
														<input type="text" class="form-control" id="job" name="job" placeholder="Cargo">
													</div>
												</div>

                        <div class="form-group">
													<label class="col-md-2 control-label" for="observation">Observações</label>
													<div class="col-md-10">
                            <textarea class="form-control" name="observation" id="observation"><?=$d['obs'];?></textarea>
												  </div>
												</div>





											</fieldset>

											<hr class="dotted tall">
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



											</fieldset>


											<div class="panel-footer" style="height:60px">
												<div class="row pull-right">
														<div class="col-md-12"  style="margin-bottom:20px">
                            <input type="hidden" name="acao" value="inserir" />
                            <a href="usuarios/index.php"><button type="button" class="btn btn-default loading">Voltar</button></a>
														<button type="submit" class="btn btn-primary loading">Inserir</button>
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
<script type="text/javascript">
$(document).ready(function(){
    $(window).scrollTop(0);
})
//  $("#email").val('Digite o endereço de email');
  //$("#email").val('');
  //$("#senha").val('');
  //$("#senha_repete").val('');
  $(".loading").click(function(){ $(this).html("<i class=\"fa fa-spinner fa-spin\"></i> <small>Aguarde.</small>"); });

  $("#userform").on("submit", function(){
    if($("#email").val()        == "Endereço de e-mail"){ $("#email").val('');}
    if($("#senha").val()        == "nova_senha")        { $("#senha").val('');}
    if($("#senha_repete").val() == "nova_senha")        { $("#senha_repete").val('');}
  });
</script>
