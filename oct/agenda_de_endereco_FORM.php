<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");
  $agora = now();


  logger("Acesso","Agenda de Endereço - Visualização detalhada");

  extract($_GET);
  if($id != "")
  {
      $acao = "Atualizar";
      $sql = "SELECT S.name as street_name, A.* FROM ".$schema."oct_addressbook A
              LEFT JOIN ".$schema."streets S ON S.id = A.id_street
              WHERE A.id = '".$id."'";
      $res = pg_query($sql)or die("SQL error ".__LINE__);
      $d   = pg_fetch_assoc($res);
  }else {
      $acao = "Inserir";
  }
?>

<section role="main" class="content-body">
  <header class="page-header">
    <h2>Agenda de Endereços</h2>
    <div class="right-wrapper pull-right" style='margin-right:15px;'>
      <ol class="breadcrumbs">
        <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
        <li><a href="oct/index.php">Sistema</a></li>
        <li><a href="oct/agenda_de_endereco_INDEX.php">Agenda de Endereço</a></li>
        <li><span class='text-muted'>Visualização detalhada</span></li>
      </ol>
      <!--<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>-->
    </div>
  </header>



								<section class="panel box_shadow">
  									<header class="panel-heading" style="height:70px">
                      <div class="panel-actions" style="margin-top:5px">
                        <a href="oct/agenda_de_endereco_INDEX.php"><button type="button" class="btn btn-default">Voltar</button></a>
                        <!--<button type="button" class="btn btn-primary"  data-toggle='modal' data-target='#modalFiltro'><i class='fa fa-search'></i> Pesquisar</button>-->
                        <!--<button type="button" class="btn btn-info" id="bt_print"><i class='fa fa-print'></i> Imprimir</button>-->
                        <!--<button type="button" class="btn btn-info"><i class='fa fa-map-marker'></i> <sup><i class='fa fa-plus'></i></sup> Novo registro</button>-->
                      </div>
                    </header>
  									<div class="panel-body">
                      <form id="form" name="form" class="form-horizontal" method="post" action="oct/agenda_de_endereco_SQL.php" debug='0'>

                        <div class='row'>
                          <div class='col-sm-6 col-sm-offset-3'>

                                <div class="form-group">
                                  <label class="col-md-2 control-label" for="type">Local:</label>
                                  <div class="col-md-10">
                                    <input type="text" class="form-control" name="name" value="<?=$d['name'];?>">
                                  </div>
                                </div>

                                <div class="form-group">
                                  <label class="col-md-2 control-label" for="type">Tipo:</label>
                                  <div class="col-md-10">
                                    <select id="type" name="type" class="form-control select2" style="width: 100%; height:100%">
                                        <option value="">- - -</option>
                                        <option value="abrigo"     <?=($d['type']=="abrigo"?"selected":"");?>>Abrigo</option>
                                        <option value="aeroporto"  <?=($d['type']=="aeroporto"?"selected":"");?>>Aeroporto</option>
                                        <option value="associação" <?=($d['type']=="associação"?"selected":"");?>>Associação</option>
                                        <option value="biblioteca" <?=($d['type']=="biblioteca"?"selected":"");?>>Biblioteca</option>
                                        <option value="cemitério"  <?=($d['type']=="cemitério"?"selected":"");?>>Cemitério</option>
                                        <option value="centro de educação infantil"                              <?=($d['type']=="centro de educação infantil"?"selected":"");?>>Centro de Educação Infantil</option>
                                        <option value="centro de referência especializado de assistência Social" <?=($d['type']=="centro de referência especializado de assistência Social"?"selected":"");?>>CREAS - Centro de Referência Especializado de Assistência Social</option>
                                        <option value="delegacia"        <?=($d['type']=="delegacia"?"selected":"");?>>Delegacia</option>
                                        <option value="escola municipal" <?=($d['type']=="escola municipal"?"selected":"");?>>Escola Municipal</option>
                                        <option value="estação de tratamento de esgosto" <?=($d['type']=="estação de tratamento de esgosto"?"selected":"");?>>Estação de Tratamento de Esgosto</option>
                                        <option value="estádio"             <?=($d['type']=="estádio"?"selected":"");?>>Estádio</option>
                                        <option value="ginásio de esportes" <?=($d['type']=="ginásio de esportes"?"selected":"");?>>Ginásio de Esportes</option>
                                        <option value="hospital"           <?=($d['type']=="hospital"?"selected":"");?>>Hospital</option>
                                        <option value="local de evento"    <?=($d['type']=="local de evento"?"selected":"");?>>Local de Evento</option>
                                        <option value="museu"              <?=($d['type']=="museu"?"selected":"");?>>Museu</option>
                                        <option value="órgão público"      <?=($d['type']=="órgão público"?"selected":"");?>>Órgão Público</option>
                                        <option value="parque"             <?=($d['type']=="parque"?"selected":"");?>>Parque</option>
                                        <option value="praça"              <?=($d['type']=="praça"?"selected":"");?>>Praça</option>
                                        <option value="pronto atendimento" <?=($d['type']=="pronto atendimento"?"selected":"");?>>Pronto Atendimento</option>
                                        <option value="quartel"            <?=($d['type']=="quartel"?"selected":"");?>>Quartel</option>
                                        <option value="terminal de ônibus"            <?=($d['type']=="terminal de ônibus"?"selected":"");?>>Terminal de ônibus</option>
                                        <option value="unidade de proteção social básica" <?=($d['type']=="unidade de proteção social básica"?"selected":"");?>>CRAS - Unidade de Proteção Social Básica</option>
                                        <option value="unidade de saúde"   <?=($d['type']=="unidade de saúde"?"selected":"");?>>Unidade de Saúde</option>
                                        <option value="outros"             <?=($d['type']=="outros"?"selected":"");?>>Outros</option>
                                    </select>
                                  </div>
                                </div>

                                <div class="form-group">
                                  <label class="col-md-2 control-label" for="type">Rua:</label>
                                  <div class="col-md-10">
                                    <select id="id_street" name="id_street" class="form-control select2" style="width: 100%; height:100%">
                                      <option value="">- - -</option>
                                      <?
                                        $sql = "SELECT * FROM ".$schema."streets ORDER BY name ASC";
                                        $res = pg_query($sql)or die();
                                        while($s = pg_fetch_assoc($res))
                                        {
                                          if($d["id_street"] == $s["id"]){ $sel = "selected";}else{$sel="";}
                                          echo "<option value='".$s['id']."' ".$sel.">".$s['name']."</option>";
                                        }
                                      ?>
                                    </select>
                                  </div>
                                </div>

                                <div class="form-group">
                                  <label class="col-md-2 control-label" for="type">Número:</label>
                                  <div class="col-md-4">
                                    <input type="number" class="form-control" name="num_ref" value="<?=$d['num_ref'];?>">
                                  </div>
                                  <label class="col-md-2 control-label" for="type">CEP:</label>
                                  <div class="col-md-4">
                                    <input type="text" class="form-control" name="zipcode" value="<?=$d['zipcode'];?>">
                                  </div>
                                </div>

                                <div class="form-group">
                                  <label class="col-md-2 control-label" for="type">Bairro:</label>
                                  <div class="col-md-4">
                                    <input type="text" class="form-control" name="neighborhood" value="<?=$d['neighborhood'];?>">
                                  </div>
                                  <label class="col-md-2 control-label" for="type">Zona:</label>
                                  <div class="col-md-4">
                                    <input type="text" class="form-control" name="zone" value="<?=$d['zone'];?>">
                                  </div>
                                </div>

                                <div class="form-group">
                                  <label class="col-md-2 control-label" for="type">Lat/Long:</label>
                                  <div class="col-md-4">
                                    <input type="text" class="form-control" name="geoposition" value="<?=$d['geoposition'];?>">
                                  </div>
                                  <label class="col-md-2 control-label" for="type">Status:</label>
                                  <div class="col-md-4">
                                      <select name="active" class="form-control">
                                          <option value="t" <?=($d['active']=="t"?"selected":"");?>>Ativo</option>
                                          <option value="f" <?=($d['active']=="f"?"selected":"")?>>Baixado</option>
                                      </select>
                                  </div>
                                </div>

                                <div class="form-group">
                                  <label class="col-md-2 control-label" for="type">Observações:</label>
                                  <div class="col-md-10">
                                    <textarea  class="form-control" name="obs"><?=$d['obs'];?></textarea>
                                  </div>
                                </div>


                          </div>
                        </div>

                        <div class='row' style="margin-top:10px">
                          <div class='col-sm-6 col-sm-offset-3 text-center'>
                            <input type="hidden" name="id" value="<?=$d['id'];?>" />
                            <input type="hidden" name="acao" value="<?=$acao;?>" />
                            <a href="oct/agenda_de_endereco_INDEX.php"><button type="button" class="btn btn-default">Voltar</button></a>
                            <? if($acao=="Atualizar")
                                {
                                    if(check_perm("3_16"))
                                    {
                                      echo " <a href='oct/agenda_de_endereco_SQL.php?id=".$d['id']."&acao=Remover'><button type='button' class='btn btn-danger loading'>Remover</button></a>";
                                    }

                                    if(check_perm("3_16"))
                                    {
                                      echo " <button type='submit' class='btn btn-primary loading'>".$acao."</button>";
                                    }
                                }

                                if($acao=="Inserir")
                                {
                                    if(check_perm("3_16"))
                                    {
                                      echo " <button type='submit' class='btn btn-primary loading'>".$acao."</button>";
                                    }
                                }
                             ?>

                          </div>
                        </div>


                      </form>
                      <?
                        /*
                        Array
                        (
                            [street_name] => MINISTRO CALOGERAS
                            [id] => 343
                            [name] => 62 BI
                            [numRef] => 1200
                            [id_street] => 2402
                            [geoposition] => -26.308850, -48.851531
                            [obs] =>
                            [zipcode] => 89203-000
                            [neighborhood] => ATIRADORES
                            [zone] => ZONA CENTRAL
                            [nonMappedStreet] =>
                            [id_company] => 2
                        )
                        */
                      ?>

                    </div>
                </section>
</section>


<script>
$('.select2').select2();
$(".loading").click(function(){ $(this).html("<i class=\"fa fa-spinner fa-spin\"></i> Aguarde");});
$(".loading2").click(function(){ $(this).addClass("disabled").html("<i class=\"fa fa-spinner fa-spin\"></i>");});
</script>
