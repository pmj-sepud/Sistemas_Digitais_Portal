<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");


  if($_POST['id']!="")
  {
    if($_POST['pass00']!="" && $_POST['pass01']!="")
    {
      if($_POST['pass00'] == $_POST['pass01'])
      {
        $error = "<h5 class='text-success'><b>Senha alterada com sucesso.</b></h5>";
        $sql = "UPDATE {$schema}users SET password = md5('{$_POST['pass00']}') WHERE id = '{$_POST['id']}'";
        pg_query($sql)or die("Error ".__LINE__);
        logger("Acesso","Senha alterada com sucesso.");
      }else{
        $error = "<h5 class='text-danger'>AVISO: Os campos de senha devem ser iguais.</h5>";
        logger("Acesso","Tentativa de troca de senha - senhas diferentes");
      }
    }else{
      $error = "<h5 class='text-danger'>AVISO: Todos os campos devem ser preenchidos.</h5>";
      logger("Acesso","Tentativa de troca de senha - campos em branco");
    }

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
								<li><span class='text-muted'>Trocar senha</span></li>
							</ol>
						</div>
					</header>

					<!-- start: page -->

					<div class="row">
						<div class="col-md-12 col-lg-12">

							<div class="tabs">
								<ul class="nav nav-tabs tabs-primary">
									<li class="active">
										<a href="#dados" data-toggle="tab">Trocar senha</a>
									</li>
                </ul>
								<div class="tab-content">
									<div id="dados" class="tab-pane active">



<div class="row">
  <div class="col-md-6 col-md-offset-3">

										<form  class="form-horizontal" method="post" action="usuarios/FORM_change_pass.php" debug='0' autocomplete="off">
                      <input autocomplete="off" name="hidden" type="text" style="display:none;">
                      <input name="id" type="hidden" value="<?=$_SESSION['id']?>">


                      <h5 class="text-center">Utilitário para troca de senha,<br>procure utilizar uma senha complexa, com letras, números e/ou caracteres especiais</h5>
                      <br><br>
                      <fieldset>
                      	<div class="form-group">

													<div class="col-md-6">
														<input type="password"    class="form-control" name="pass00" id="pass00" placeholder='Nova senha'  autocomplete="new-password">
													</div>


													<div class="col-md-6">
														<input type="password"    class="form-control" name="pass01" id="pass01"  placeholder='Repita nova senha'  autocomplete="new-password">
													</div>
												</div>
                      </fieldset>

                      <div class="row">
                        <div class="col-md-12 text-center" style="margin-top:15px">
                          <button type='submit' class='btn btn-primary loading'>Trocar senha</button>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12 text-center" style="margin-top:15px">
                          <? echo $error; $error="";?>
                        </div>
                      </div>
                    </form>

													</div>
												</div>
                      </div>
                  </div>
                </div>
              </div>
            </div>
					<!-- end: page -->
				</section>
<script type="text/javascript">
  $(".loading").click(function(){ $(this).html("<i class=\"fa fa-spinner fa-spin\"></i> <small>Aguarde.</small>"); });
</script>
