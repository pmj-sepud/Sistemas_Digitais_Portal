<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");

  if($_GET['id'] != "")
  {
    $acao = "atualizar";
    $sql  = "SELECT C.*
             FROM {$schema}company C
             WHERE
              	C.id = '{$_GET['id']}'";
    $res  = pg_query($sql)or die("Erro ".__LINE__);
    $d    = pg_fetch_assoc($res);

    logger("Acesso","Órgãos - visualizadetalhado", "Acesso aos dados: [".$_GET["id"]."] - ".$d['name']);

  }else {
    $acao = "inserir";
  }
?>
				<section role="main" class="content-body">
					<header class="page-header">
						<h2>Dados cadastrais</h2>

						<div class="right-wrapper pull-right" style='margin-right:15px;'>
							<ol class="breadcrumbs">
								<li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
				        <li><span class='text-muted'>Configurações</span></li>
				        <li><a href="config/company.php"><span>Órgãos</span></a></li>
								<li><span class='text-muted'>Visualização detalhada</span></li>
							</ol>
						</div>
					</header>

					<!-- start: page -->


          <div class="row">
            <div class="col-md-12 col-lg-12">

              <div class="tabs">
                <ul class="nav nav-tabs tabs-primary">
                  <li class="<?=$nav_dados;?>">
                    <a href="#dados" data-toggle="tab" ajax='false'>Dados Cadastrais</a>
                  </li>
                  <li class="<?=$nav_trab;?>">
                    <a href="#dados" data-toggle="tab" ajax='false'>Setores</a>
                  </li>
                  <li class="<?=$nav_acess;?>">
                    <a href="#dados" data-toggle="tab" ajax='false'>Usuários</a>
                  </li>

                </ul>


                <div class="tab-content">
                </div>
            </div>
        </div>
      </div>




					<div class="row">
						<div class="col-md-12 col-lg-12">

							<div class="tabs">
								<ul class="nav nav-tabs tabs-primary">
									<li class="active">
										<a href="#dados" data-toggle="tab" ajax='false'>Dados cadastrais</a>
									</li>

                </ul>


								<div class="tab-content">

									<div id="dados" class="tab-pane active">
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
													<label class="col-md-2 control-label" for="id_pai">Setor pai:</label>
													<div class="col-md-10">
                            <?
                                  if($acao=="atualizar")
                                  {
                                      $sql = "SELECT id, name FROM {$schema}company WHERE id <>'{$_GET['id']}' AND id_pai is null AND active = 't' ORDER BY name ASC";
                                  }else {
                                      $sql = "SELECT id, name FROM {$schema}company WHERE id_pai is null AND active = 't' ORDER BY name ASC";
                                  }
                                      $res = pg_query($sql)or die("Error ".__LINE__."<br>SQL: {$sql}");
                                      $optCompany .= "<option value=''>- - -</option>";
                                      while($u = pg_fetch_assoc($res)){
                                        if($d['id_pai'] == $u['id']){$sel = "selected";}else{$sel="";}
                                        $optCompany .= "<option value='{$u['id']}' {$sel}>{$u['name']}</option>";
                                      }

                            ?>
														<select class='form-control select2' name='id_pai'>
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
                          <div class="col-md-2">
                            <select class="form-control" id="active" name="active">
                                <option value="t" <?=($d['active']=="t"?"selected":"");?>>Ativo</option>
                                <option value="f" <?=($d['active']=="f"?"selected":"");?>>Inativo</option>
                            </select>
                          </div>

                          <label class="col-md-2 control-label" for="active">Externo a PMJ?</label>
                          <div class="col-md-2">
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
$('.select2').select2({
  language: {
        noResults: function() {
          return 'Nenhum resultado encontrado.';
        }
      }
});
</script>
