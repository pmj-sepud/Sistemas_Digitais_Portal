<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");
  $agora = now();


  logger("Acesso","Cidadão - Visualização detalhada");

  extract($_GET);
  if($id != "")
  {
      $acao = "Atualizar";
      $sql = "SELECT * FROM {$schema}sas_citizen WHERE id = '{$id}'";
      $res = pg_query($sql)or die("SQL error ".__LINE__);
      $d   = pg_fetch_assoc($res);
  }else {
      $acao = "Inserir";
  }
?>

<section role="main" class="content-body">
  <header class="page-header">
    <h2>Cadastro do cidadão</h2>
    <div class="right-wrapper pull-right" style='margin-right:15px;'>
      <ol class="breadcrumbs">
        <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
        <li><span class='text-muted'>SAS-BEV</span></li>
        <li><a href="sas/cidadao.php">Cidadão</a></li>
        <li><span class='text-muted'>Visualização detalhada</span></li>
      </ol>
      <!--<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>-->
    </div>
  </header>



								<section class="panel box_shadow">
  									<header class="panel-heading" style="height:70px">
                      <div class="panel-actions" style="margin-top:5px">
                        <a href="sas/cidadao.php"><button type="button" class="btn btn-default">Voltar</button></a>
                        <!--<button type="button" class="btn btn-primary"  data-toggle='modal' data-target='#modalFiltro'><i class='fa fa-search'></i> Pesquisar</button>-->
                        <!--<button type="button" class="btn btn-info" id="bt_print"><i class='fa fa-print'></i> Imprimir</button>-->
                        <!--<button type="button" class="btn btn-info"><i class='fa fa-map-marker'></i> <sup><i class='fa fa-plus'></i></sup> Novo registro</button>-->
                      </div>
                    </header>






  									<div class="panel-body">
                      <?
                        //print_r_pre($d);
                      ?>
                      <form id="form" name="form" class="form-horizontal" method="post" action="sas/cidadao_SQL.php" debug='0'>

                        <div class='row'>
                          <div class='col-sm-6 col-sm-offset-3'>

<!---------------------------------------------------------------------------------->
<!---------------------------------------------------------------------------------->



    <div class='form-group'>
      <label class='col-md-2 control-label' for='name'>Nome:</label>
      <div class='col-md-10'>
        <input type='text' class='form-control' id='name' name='name' placeholder='' value='<?=$d['name'];?>'>
      </div>
    </div>

    <div class='form-group'>
      <label class='col-md-2 control-label' for='mother_name'>Nome da mãe:</label>
      <div class='col-md-10'>
        <input type='text' class='form-control' id='mother_name' name='mother_name' placeholder='' value='<?=$d['mother_name'];?>'>
      </div>
    </div>

    <div class='form-group'>
      <label class='col-md-2 control-label' for='birth'>Data de nasc.:</label>
      <div class='col-md-4'>
        <input type='date' class='form-control' id='birth' name='birth' placeholder='' value='<?=$d['birth'];?>'>
      </div>

      <label class='col-md-2 control-label' for='phone'>Telefone:</label>
      <div class='col-md-4'>
        <input type='text' class='form-control' id='phone' name='phone' placeholder='' value='<?=$d['phone'];?>'>
      </div>
    </div>

    <div class='form-group'>
      <label class='col-md-2 control-label' for='rg'>RG:</label>
      <div class='col-md-4'>
        <input type='text' class='form-control' id='rg' name='rg' placeholder='' value='<?=$d['rg'];?>'>
      </div>

      <label class='col-md-2 control-label' for='cpf'>CPF:</label>
      <div class='col-md-4'>
        <input type='text' class='form-control' id='cpf' name='cpf' placeholder='' value='<?=$d['cpf'];?>'>
      </div>
    </div>

    <div class='form-group'>
      <label class='col-md-2 control-label' for='gmas'>GMAS:</label>
      <div class='col-md-4'>
        <input type='text' class='form-control' id='gmas' name='gmas' placeholder='' value='<?=$d['gmas'];?>'>
      </div>

      <label class='col-md-2 control-label' for='cadunico'>Cadastro Único:</label>
      <div class='col-md-4'>
        <input type='text' class='form-control' id='cadunico' name='cadunico' placeholder='' value='<?=$d['cadunico'];?>'>
      </div>
    </div>

    <div class='form-group'>
      <label class='col-md-2 control-label' for='nis'>NIS:</label>
      <div class='col-md-10'>
        <input type='text' class='form-control' id='nis' name='nis' placeholder='' value='<?=$d['nis'];?>'>
      </div>
    </div>

    <div class='form-group'>
      <label class='col-md-2 control-label' for='average_income'>Renda média:</label>
      <div class='col-md-4'>
        <input type='text' class='form-control' id='average_income' name='average_income' placeholder='' value='<?=str_replace(".",",",$d['average_income']);?>'>
      </div>

      <label class='col-md-2 control-label' for='sas_monitor'>Família acomp.:</label>
      <div class='col-md-4'>
        <select class='form-control' id='sas_monitor' name='sas_monitor'>
            <option value="t" <?=($d['sas_monitor']=='t'?"selected":"");?>>Sim</option>
            <option value="f" <?=($d['sas_monitor']=='f'?"selected":"");?>>Não</option>
        </select>
      </div>
    </div>

    <div class='form-group'>
      <label class='col-md-2 control-label' for='id_street'>Logradouro:</label>
      <div class='col-md-10'>
        <?
            $sql = "SELECT id, name FROM {$schema}streets ORDER BY name ASC";
            $res = pg_query($sql)or die("Error ".__LINE__."<br>Query: {$sql}");
        ?>
        <select class='form-control select2' id='id_street' name='id_street'>
          <?
              while ($s = pg_fetch_assoc($res)) {
                if($s['id']==$d['id_street']){$sel="selected";}else{$sel="";}
                echo "<option value='{$s['id']}' {$sel}>{$s['name']}</option>";
              }
          ?>
        </select>
      </div>
    </div>

    <div class='form-group'>
      <label class='col-md-2 control-label' for='address_number'>Num:</label>
      <div class='col-md-2'>
        <input type='number' class='form-control' id='address_number' name='address_number' placeholder='' value='<?=$d['address_number'];?>'>
      </div>

      <label class='col-md-2 control-label' for='address_complement'>Complemento:</label>
      <div class='col-md-6'>
        <input type='text' class='form-control' id='address_complement' name='address_complement' placeholder='' value='<?=$d['address_complement'];?>'>
      </div>
    </div>

    <div class='form-group'>
      <label class='col-md-2 control-label' for='id_neighborhood'>Bairro:</label>
      <div class='col-md-10'>
        <?
            $sql = "SELECT * FROM {$schema}neighborhood";
            $res = pg_query($sql)or die("Error ".__LINE__."<br>Query: {$sql}");
        ?>
        <select class='form-control select2' id='id_neighborhood' name='id_neighborhood'>
          <?
              while ($n = pg_fetch_assoc($res)) {
                if($n['id']==$d['id_neighborhood']){$sel="selected";}else{$sel="";}
                echo "<option value='{$n['id']}' {$sel}>{$n['neighborhood']}</option>";
              }
          ?>
        </select>
      </div>
    </div>


    <div class='form-group'>
      <label class='col-md-2 control-label' for='observations'>Observações:</label>
      <div class='col-md-10'>
        <textarea class='form-control' id='observations' name='observations' rows='3'><?=$d['observations'];?></textarea>
      </div>
    </div>
<!---------------------------------------------------------------------------------->
<!---------------------------------------------------------------------------------->



                          </div>
                        </div>

                        <div class='row' style="margin-top:10px">
                          <div class='col-sm-6 col-sm-offset-3 text-center'>
                            <input type="hidden" name="acao" value="<?=$acao;?>" />
                            <a href="sas/cidadao.php"><button type="button" class="btn btn-default">Voltar</button></a>
                            <? if($acao=="Atualizar")
                                {
                                    echo "<input type='hidden' name='id' value='{$d['id']}'/>";

                                    if(check_perm("7_21","D"))
                                    {
                                      echo " <a href='sas/cidadao_SQL.php?id=".$d['id']."&acao=Remover'><button type='button' class='btn btn-danger loading'>Remover</button></a>";
                                    }
                                    if(check_perm("7_21","U"))
                                    {
                                      echo " <button type='submit' class='btn btn-primary loading'>".$acao."</button>";
                                    }
                                }

                                if($acao=="Inserir")
                                {
                                    echo "<input type='hidden' name='id_user_register' value='{$_SESSION['id']}'/>";
                                    echo "<input type='hidden' name='id_company_register' value='{$_SESSION['id_company']}'/>";
                                    echo "<input type='hidden' name='date' value='{$agora['datatimesrv']}'/>";
                                    if(check_perm("7_21","C"))
                                    {
                                      echo " <button type='submit' class='btn btn-primary loading'>".$acao."</button>";
                                    }
                                }
                             ?>

                          </div>
                        </div>
                      </form>
                    </div>
                </section>
</section>


<script>
$('.select2').select2({
  language: {
        noResults: function() {
          return 'Nenhum resultado encontrado.';
        }
      }
});
$(".loading").click(function(){ $(this).html("<i class=\"fa fa-spinner fa-spin\"></i> Aguarde");});
$(".loading2").click(function(){ $(this).addClass("disabled").html("<i class=\"fa fa-spinner fa-spin\"></i>");});
</script>
