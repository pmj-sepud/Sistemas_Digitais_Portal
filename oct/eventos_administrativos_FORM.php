<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");

  $agora = now();

  if($_GET['id']!="")
  {
    $sql   = "SELECT * FROM sepud.oct_administrative_events WHERE id = '".$_GET['id']."'";
    $res   = pg_query($sql)or die("SQL Error ".__LINE__);
    $dados = pg_fetch_assoc($res);
    $acao  = "Atualizar";

    if($dados['opened_timestamp']!=""){ $dados['opened_timestamp'] = str_replace(" ","T",$dados['opened_timestamp']);}
    if($dados['closed_timestamp']!=""){ $dados['closed_timestamp'] = str_replace(" ","T",$dados['closed_timestamp']);}
  }else{
    $acao            = "Inserir";
    $dados['status'] = "Nova ocorrência administrativa";
    $sql        = "SELECT * FROM sepud.oct_workshift WHERE id_company = ".$_SESSION['id_company']." AND status = 'aberto'";
    $resTurno   = pg_query($sql)or die("Erro ".__LINE__);
    if(pg_num_rows($resTurno))
    {
        $turno = pg_fetch_assoc($resTurno);
    }
  }
?>

<section role="main" class="content-body">
  <header class="page-header">
    <h2>Eventos administrativos</h2>
    <div class="right-wrapper pull-right" style='margin-right:15px;'>
      <ol class="breadcrumbs">
        <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
        <li><a href="oct/index.php">Sistema</a></li>
        <li><a href="oct/eventos_administrativos_INDEX.php">Eventos administrativos</a></li>
        <li><span class='text-muted'>Formulário</span></li>
      </ol>
      <!--<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>-->
    </div>
  </header>

<form action="oct/eventos_administrativos_SQL.php" method="post">
<div class="col-md-12">
								<section class="panel box_shadow">
									<header class="panel-heading">

                    <h4><span class="text-muted">Status: </span><strong><i id='txt_status'><?=$dados['status'];?></i></strong>
                              <?
                                  if(isset($turno))
                                  {
                                    echo "<br><small class='text-muted'>Turno nº <b>".str_pad($turno['id'],5,"0",STR_PAD_LEFT)."</b> - ".$turno['period']. " - ";
                                    if($turno['status']=="fechado"){ echo " <span class='text-warning'>Turno fechado</span>";}else{ echo " <span class='text-success'>Turno aberto</span>";}
                                    echo "<br>Início: <b>".formataData($turno['opened'],1)."</b>";
                                    if($turno['closed']!=""){echo ", fim: <b>".formataData($turno['closed'],1)."</b>";}
                                    echo "</small>";
                                  }else{
                                    //echo "<br><small class='text-danger'>Nenhum turno de trabalho aberto.</small>";
                                  }
                              ?>
                    </h4>

                    <div class="panel-actions" style='margin-top:-12px'>
									  </div>
                  </header>
									<div class="panel-body">
                    <div class="row">
                      <div class="col-md-6 col-md-offset-3">


                                    <div class="row">
                                        <div class="col-md-12">
                                                        <div class="form-group">
                                                        <label class="control-label">Agenda de Endereço:</label>
                                                        <select id="id_addressbook" name="id_addressbook" class="form-control select2">
                                                           <?
                                                                 $sql = "SELECT * FROM sepud.oct_addressbook WHERE id_company = '".$_SESSION['id_company']."' ORDER BY name ASC";
                                                                 $res = pg_query($sql)or die("Erro ".__LINE__);
                                                                 while($d = pg_fetch_assoc($res))
                                                                 {
                                                                   $vet[$d['neighborhood']][] = $d;
                                                                 }
                                                                  echo "<option value=''></option>";
                                                                  foreach ($vet as $bairro => $info) {
                                                                      echo "<optgroup label='".$bairro."'>";
                                                                      for($i=0;$i<count($info);$i++)
                                                                      {

                                                                          if($dados['id_addressbook']==$info[$i]["id"]){ $sel = "selected";}else{$sel="";}
                                                                          echo "<option value='".$info[$i]["id"]."' ".$sel.">".$info[$i]["name"]."</option>";
                                                                      }
                                                                      echo "</optgroup>";
                                                                  }
                                                           ?>
                                                        </select>
                                                       </div>
                                      </div>
                                  </div>

                                  <div class="row">
                                    <div class="col-md-8">
                                      <div class="form-group">
                                      <label class="control-label">Logradouro:</label>
                                      <select id="id_street" name="id_street" class="form-control select2">
                                         <?
                                               $sql = "SELECT * FROM sepud.streets ORDER BY name ASC";
                                               $res = pg_query($sql)or die("Erro ".__LINE__);
                                               echo "<option value=''></option>";
                                               while($d = pg_fetch_assoc($res))
                                               {
                                                  if($dados['id_street']==$d['id']){$sel="selected";}else{$sel="";}
                                                  echo "<option value='".$d["id"]."' ".$sel.">".$d["name"]."</option>";
                                               }
                                         ?>
                                      </select>
                                     </div>
                                    </div>
                                    <div class="col-md-4">
                                      <div class="form-group">
                                        <label class="control-label">Referência:</label>
                                        <input id="street_ref" name="street_ref" type="text" class="form-control" value="<?=$dados['street_ref'];?>">
                                      </div>
                                    </div>

                                  </div>
                    <div class="row">
                          <div class="col-md-12">
                                  <div class="row">
                                    <div class="col-md-6">
                                      <div class="form-group">
                                       <label class="control-label">Entrada:</label>
                                       <input id="opened_timestamp" name="opened_timestamp" type="datetime-local" class="form-control" value="<?=($dados['opened_timestamp']!=""?$dados['opened_timestamp']:substr(str_replace(" ","T",$agora['datatimesrv']),0,-3));?>">
                                      </div>
                                    </div>
                                    <div class="col-md-6">
                                      <div class="form-group">
                                       <label class="control-label">Saída:</label>
                                       <input id="closed_timestamp" name="closed_timestamp" type="datetime-local" class="form-control" value="<?=($dados['closed_timestamp']!=""?$dados['closed_timestamp']:substr(str_replace(" ","T",$agora['datatimesrv']),0,-3));?>">
                                      </div>
                                    </div>
                                </div>
    									  </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                         <label class="control-label">Agente:</label>
                         <select id="id_user" name="id_user" class="form-control select2">
                            <?
                                $sql = "SELECT * FROM sepud.users WHERE id_company = '".$_SESSION['id_company']."' ORDER BY name ASC";
                                $res = pg_query($sql)or die("<option>SQL ERROR ".__LINE__."</option>");
                                echo "<option value=''></option>";
                                while($d = pg_fetch_assoc($res))
                                {
                                  if($dados['id_user']==$d['id']){$sel="selected";}else{$sel="";}
                                  echo "<option value='".$d['id']."' ".$sel.">".$d['name']."</option>";
                                }
                            ?>
                         </select>
                       </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                         <label class="control-label">Descrição:</label>
                         <textarea id="description" name="description" class="form-control" rows="5"><?=$dados['description'];?></textarea>
                       </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-12 text-center" style="margin-top:15px">
                          <input type="hidden" id="id_workshift" name="id_workshift" value="<?=$_GET['turno'];?>">
                          <input type="hidden" id="acao" name="acao" value="<?=$acao;?>">
                          <a href="oct/eventos_administrativos_INDEX.php" class="btn btn-default loading2">Voltar</a>
                          <?
                              if($acao=="Atualizar")
                              {
                                echo "<input type='hidden' id='id' name='id' value='".$_GET['id']."'>";
                                echo  "<a href='oct/eventos_administrativos_SQL.php?acao=Remover&id=".$_GET['id']."' class='btn btn-danger loading2'>Remover</a>";
                              }
                          ?>
                          <button type="submit" class="btn btn-primary loading"><?=$acao;?></button>
                      </div>
                   </div>

							   </div>
               </div>
             </div>
    </section>
</form>
</section>
<script>
$('.select2').select2();
$(".loading").click(function(){ $(this).html("<i class=\"fa fa-spinner fa-spin\"></i> Aguarde");});
$(".loading2").click(function(){ $(this).addClass("disabled").html("<i class=\"fa fa-spinner fa-spin\"></i>");});
</script>
