<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");
  $agora = now();


  if($_GET['id']!="")
  {
    $acao = "Atualizar";
    logger("Atualização","SES - PNCD ", "Armadilhas");
    $sql = "SELECT * FROM {$schema}ses_trap T WHERE id = '{$_GET['id']}'";
    $res = pg_query($sql)or die("Error ".__LINE__."<br>Query: {$sql}");
    $d   = pg_fetch_assoc($res);

  }else{
    $acao = "Inserir";
    logger("Inserção","SES - PNCD ", "Armadilhas");
  }

?>


<section role="main" class="content-body">
  <header class="page-header">
    <h2>Registro diário de serviço</h2>
    <div class="right-wrapper pull-right" style='margin-right:15px;'>
      <ol class="breadcrumbs">
        <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
        <li><span class='text-muted'>SES-PNCD</span></li>
        <li><a href="ses/armadilhas.php">Visualização geral</a></li>
        <li><span class='#'>Cadastro de armadilha</span></li>
      </ol>
    </div>
  </header>

  <section class="panel box_shadow">
  		<header class="panel-heading" style="height:70px">
            Registro: <?=$_GET['id'];?>
            <div class="panel-actions" style="margin-top:5px">
              <a href="ses/armadilhas.php">
                  <button type="button" class="btn btn-default">Voltar</button>
              </a>
            </div>
      </header>

  		<div class="panel-body">

<form action="ses/armadilhas_FORM_sql.php" method="post">
        <div class="row">
          <div class="col-md-12">
<!------------------------------------------------------------------------------->
<!------------------------------------------------------------------------------->
<div class="row">
  <div class="col-md-12">

      <div class='col-md-6'>
        <div class='form-group'>
          <label class='control-label' for='estabelecimento'>Nome do estabelecimento:</label>
        <input type='text' class='form-control' id='estabelecimento' name='estabelecimento' placeholder='' value='<?=$d['estabelecimento'];?>' >
      </div>
    </div>

</div>
</div>


<div class="row">
  <div class="col-md-12">
<?
    $sql = "SELECT id, name, nickname, area, job FROM {$schema}users U WHERE U.id_company='{$_SESSION['id_company']}' AND U.active = 't' ORDER BY U.name ASC";
    $res = pg_query($sql)or die("SQL Error ".__LINE__."Query: ".$sql);
    while($userData = pg_fetch_assoc($res)){ $userArray[] = $userData; }
?>
<div class='col-md-6'>
  <div class='form-group'>
  <label class='control-label' for='id_user'>Agente: <sup>(Sistema)</sup></label>
  <select class='form-control select2' id='id_user' name='id_user'>
  <option value="">- - -</option>
      <?
          for($i=0;$i<count($userArray);$i++)
          {
            if($d['id_user']==$userArray[$i]['id']){ $sel = "selected"; }else{$sel="";}
            echo "<option value='{$userArray[$i]['id']}' {$sel}>{$userArray[$i]['name']}</option>";
          }
      ?>
  </select>
</div>
</div>


</div>
</div>


<div class="row">
  <div class="col-md-12">





          <div class='col-md-2'>
            <div class='form-group'>
            <label class='control-label' for='agente'>Agente: <sup>(Importado da planilha)</sup></label>
            <input type='text' class='form-control' id='agente' name='agente' placeholder='' value='<?=$d['agente'];?>' >
          </div>
          </div>




            <div class='col-md-1'>
              <div class='form-group'>
              <label class='control-label' for='seq_dia'>Seq. dia:</label>
              <input type='text' class='form-control' id='seq_dia' name='seq_dia' placeholder='' value='<?=$d['seq_dia'];?>' >
            </div>
            </div>



            <div class='col-md-3'>
                <div class='form-group'>
                  <label class='control-label' for='dia_semana'>Dia da semana:</label>
                  <input type='text' class='form-control' id='dia_semana' name='dia_semana' placeholder='' value='<?=$d['dia_semana'];?>' >
            </div>
            </div>


  </div>
</div>

<hr>
<h4>Endereço:</h4>

<div class="row">
  <div class="col-md-12">

            <div class='col-md-4'>
              <div class='form-group'>
              <label class='control-label' for='rua'>Logradouro: <sup>(Importado da planilha)</sup></label>
              <input type='text' class='form-control' id='rua' name='rua' placeholder='' value='<?=$d['rua'];?>' >
            </div>
          </div>


          <div class='col-md-2'>
            <div class='form-group'>
              <label class='control-label' for='num_imov'>Num.:</label>
              <input type='text' class='form-control' id='num_imov' name='num_imov' placeholder='' value='<?=$d['num_imov'];?>' >
          </div>
        </div>

</div>
</div>

<div class="row">
  <div class="col-md-12">

          <div class='col-md-4'>
            <div class='form-group'>
              <label class='control-label' for='complemento'>Complemento:</label>
              <input type='text' class='form-control' id='complemento' name='complemento' placeholder='' value='<?=$d['complemento'];?>' >
          </div>
        </div>

                      <div class='col-md-2'>
                        <div class='form-group'>
                          <label class='control-label' for='bairro'>Bairro:</label>
                          <input type='text' class='form-control' id='bairro' name='bairro' placeholder='' value='<?=$d['bairro'];?>' >
                      </div>
                    </div>

</div>
</div>


<div class="row">
  <div class="col-md-12">

    <div class='col-md-6'>
      <div class='form-group'>
        <label class='control-label' for='id_street'>Logradouro: <sup>(Oficial - SinGel)</sup></label>
      <?
          $sql = "SELECT id, name FROM {$schema}streets ORDER BY name ASC";
          $res = pg_query($sql)or die("Error ".__LINE__."<br>Query: {$sql}");
      ?>
      <select class='form-control select2' id='id_street' name='id_street'>
          <option value="">- - -</option>
        <?
            while ($s = pg_fetch_assoc($res)) {
              if($s['id']==$d['id_street']){$sel="selected";}else{$sel="";}
              echo "<option value='{$s['id']}' {$sel}>{$s['name']}</option>";
            }
        ?>
      </select>
    </div>
  </div>

</div>
</div>




<hr>
<h4>Outras informações:</h4>


<div class="row">
  <div class="col-md-12">

    <div class='col-md-1'>
      <div class='form-group'>
        <label class='control-label' for='quart'>Quarteirão:</label>
        <input type='text' class='form-control' id='quart' name='quart' placeholder='' value='<?=$d['quart'];?>' >
    </div>
  </div>

                <div class='col-md-2'>
                  <div class='form-group'>
                  <label class='control-label' for='insc_imob'>Inscrição imobiliária:</label>
                  <input type='text' class='form-control' id='insc_imob' name='insc_imob' placeholder='' value='<?=$d['insc_imob'];?>' >
                </div>
              </div>

              <div class='col-md-1'>
                <div class='form-group'>
                  <label class='control-label' for='tipo_imov'>Tipo imov.:</label>
                  <input type='text' class='form-control' id='tipo_imov' name='tipo_imov' placeholder='' value='<?=$d['tipo_imov'];?>' >
              </div>
            </div>

            <div class='col-md-2'>
                <div class='form-group'>
                <label class='col-md-2 control-label' for='georef'>Georeferência:</label>
                <input type='text' class='form-control' id='georef' name='georef' placeholder='' value='<?=$d['georef'];?>' >
              </div>
            </div>

  </div>
</div>

<hr>
<h4>Armadilha:</h4>


<div class="row">
  <div class="col-md-12">



                <div class='col-md-1'>
                  <div class='form-group'>
                    <label class='control-label' for='num_armadilha'>Num.:</label>
                    <input type='text' class='form-control' id='num_armadilha' name='num_armadilha' placeholder='' value='<?=$d['num_armadilha'];?>' >
                </div>
              </div>



              <div class='col-md-5'>
                <div class='form-group'>
                  <label class='control-label' for='local_armadilha'>Local:</label>
                  <input type='text' class='form-control' id='local_armadilha' name='local_armadilha' placeholder='' value='<?=$d['local_armadilha'];?>' >
              </div>
              </div>

</div>
</div>

<div class="row">
  <div class="col-md-12">
                      <div class='col-md-2'>
                        <div class='form-group'>
                          <label class='control-label' for='status'>Status:</label>
                          <select class='form-control' id='ativo' name='ativo'>
                              <option value="t" <?=($d['ativo']=='t'?'selected':'');?>>Ativa</option>
                              <option value="f" <?=($d['ativo']=='f'?'selected':'');?>>Inativa</option>
                          </select>
                          </div>
                      </div>
  </div>
</div>

<hr>
<div class="row" style="margin-top:10px">
          <div class='col-md-6'>


              <input type="hidden" name="acao" value="<?=$acao;?>" />

              <a href="ses/armadilhas.php">
                  <button type="button" class="btn btn-default">Voltar</button>
              </a>


            <?
                if($acao=="Atualizar"){
                  echo "<input type='hidden' name='id' value='{$_GET['id']}' />";
                  echo "<a href='ses/armadilhas_FORM_sql.php?id={$_GET['id']}&acao=Remover'><button type='button' class='btn btn-danger'>Remover</button></a>";
                }
            ?>

            <button type="submit" class="btn btn-primary loading"><?=$acao;?></button>
          </div>
</div>
<!------------------------------------------------------------------------------->
<!------------------------------------------------------------------------------->


          </div>
        </div>
</form>
</div>







  </section><!--  <section class="panel box_shadow">-->
</section>


<script>
$('.select2').select2();
$(".loading").click(function(){ $(this).addClass("disabled").html("<i class=\"fa fa-spinner fa-spin\"></i> Aguarde");});
$(".loading2").click(function(){ $(this).addClass("disabled").html("<i class=\"fa fa-spinner fa-spin\"></i>");});

</script>
