<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");

  logger("Acesso","OCT", "Turno");
  $agora = now();

  if($_GET['id']!=""){
    $id    = $_GET['id'];
    $sql   = "SELECT * FROM sepud.oct_workshift WHERE id = ".$id;
    $res   = pg_query($sql)or die("Erro ".__LINE__."<br>SQL: ".$sql);
    $dados = pg_fetch_assoc($res);

    unset($sql,$res);
    $sql   = "SELECT id FROM sepud.oct_workshift WHERE id_company = '".$_SESSION['id_company']."' AND status = 'aberto'";
    $res   = pg_query($sql)or die("Erro ".__LINE__."<br>SQL: ".$sql);
    $aux   = pg_fetch_assoc($res);
    if($aux['id']>0){ $ha_turno_aberto = $aux['id']; }else{ $ha_turno_aberto = false;}

    $acao  = "atualizar";
    $bt_enviar_txt = "Atualizar";

  }else{
    $sql   = "SELECT id FROM sepud.oct_workshift WHERE id_company = '".$_SESSION['id_company']."' AND status = 'aberto'";
    $res   = pg_query($sql)or die("Erro ".__LINE__."<br>SQL: ".$sql);
    $aux   = pg_fetch_assoc($res);
    if($aux['id']>0){ $ha_turno_aberto = $aux['id']; }else{ $ha_turno_aberto = false;}

    $acao = "inserir";
    $bt_enviar_txt = "Inserir novo turno de trabalho";
    $dados['status'] = "Preparando abertura de turno";
  }
?>

<section role="main" class="content-body">
  <header class="page-header">
    <h2>Abertura de turno de trabalho</h2>
    <div class="right-wrapper pull-right" style='margin-right:15px;'>
      <ol class="breadcrumbs">
        <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
        <li><span class='text-muted'>Aplicações</span></li>
        <li><span class='text-muted'>Turno de trabalho</span></li>
      </ol>
      <!--<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>-->
    </div>
  </header>



  <div class="col-md-12">
        <section class="panel box_shadow">
            <header class="panel-heading" style="height:70px">
                <?=$_SESSION['company_acron']." - ".$_SESSION['company_name'];?><br>
                <span class="text-muted"><small><i>Data atual:</i></small> <b><?=$agora['data'];?></b></span>
                <div class="panel-actions text-right">
                  <? if($id == ""){ echo "<h4><i><b>Abertura de turno</b></i></h4>"; }
                     else{ echo "<h4><i><span class='text-muted'>Turno <b>nº ".str_pad($id,5,"0",STR_PAD_LEFT)."</b> ".ucfirst($dados['status'])."</span></i></h4>"; }
                 ?>
                </div>
            </header>
<?
  //echo "<hr>Turno aberto: ".$ha_turno_aberto."<hr>";
?>
            <div class="panel-body">
                <div class="row">
                  <div class="col-md-6">
                    <div class="row">
                      <div class="col-sm-12 text-right">
                            <h4>Informações do turno</h4>
                            <hr style="margin-top:-3px;margin-bottom:5px">
                      </div>
                    </div>
                    <form name="form_turno" method="post" action="oct/turno_sql.php">
                      <div class="row">
                            <div class="col-sm-6">
                              <div class="form-group">
                              <label class="control-label">Data de abertura:</label>
                                  <input type="text" name="opened" class="form-control text-center" value="<?=($dados['opened']!=""?substr(formataData($dados['opened'],1),0,10):$agora['data']);?>">
                             </div>
                           </div>

                            <div class="col-sm-6">
                              <div class="form-group">
                              <label class="control-label">Data de fechamento:</label>
                                  <input type="text" name="closed" class="form-control text-center" value="<?=($dados['closed']!=""?substr(formataData($dados['closed'],1),0,10):$agora['data']);?>">
                             </div>
                           </div>
                      </div>
                      <div class="row">
                            <div class="col-sm-6">
                              <div class="form-group">
                              <label class="control-label">Hora de abertura:</label>
                                  <input type="text" name="opened_hour" class="form-control text-center campo_hora" placeholder="00:00"  value="<?=($dados['opened']!=""?substr($dados['opened'],11,5):"");?>">
                             </div>
                           </div>

                            <div class="col-sm-6">
                              <div class="form-group">
                              <label class="control-label">Hora de fechamento:</label>
                                  <input type="text" name="closed_hour" class="form-control text-center campo_hora" placeholder="00:00" value="<?=($dados['closed']!=""?substr($dados['closed'],11,5):"");?>">
                             </div>
                           </div>
                      </div>

                      <!--
                      <div class="row">
                            <div class="col-sm-12">
                              <div class="form-group">
                              <label class="control-label">Período:</label>
                                  <select name="period" class="form-control">
                                      <option value="matutino">Matutino (6h as 12h)</option>
                                      <option value="vespertino">Vespertino (12h as 18h)</option>
                                      <option value="noturno">Noturno(18h a 0h)</option>
                                      <option value="madrugada">Madrugada (0h as 6h)</option>
                                  </select>
                             </div>
                           </div>
                      </div>
                      -->
                      <div class="row">
                            <div class="col-sm-6">
                              <div class="form-group">
                              <label class="control-label">Livro diário:</label>
                                  <select name="workshift_group" class="form-control">
                                    <?
                                          $sql = "SELECT workshift_groups FROM sepud.company WHERE id = '".$_SESSION['id_company']."'";
                                          $res = pg_query($sql)or die("<option>SQL Error: ".__LINE__."</option>");
                                          if(pg_num_rows($res))
                                          {
                                            $wg = pg_fetch_assoc($res);
                                            $workshift_groups = json_decode($wg['workshift_groups']);

                                            for($i = 0; $i<count($workshift_groups);$i++)
                                            {
                                              if($dados['workshift_group'] == $workshift_groups[$i]){ $sel = "selected"; }else{$sel="";}
                                              echo "<option value='".$workshift_groups[$i]."' ".$sel.">".$workshift_groups[$i]."</option>";
                                            }
                                          }else{
                                              echo "<option value=''>Nenhum horário de turno configurado</option>";
                                          }
                                      ?>
                                  </select>
                             </div>
                           </div>

                           <div class="col-sm-6">
                             <div class="form-group">
                             <label class="control-label">Status do turno: *</label>
                                 <select name="status" class="form-control">
                                   <? if($acao == "inserir")
                                      {
                                          if($ha_turno_aberto){
                                            echo "<option value='inativo'>Inativo</option>";
                                          }else {
                                            echo "<option value='aberto'>Aberto</option>";
                                            echo "<option value='inativo'>Inativo</option>";
                                          }

                                      }else{
                                          if($ha_turno_aberto == $id){
                                            echo "<option value='aberto'  ".($dados['status']=='aberto'?'selected':'').">Aberto</option>";
                                            echo "<option value='fechado' ".($dados['status']=='fechado'?'selected':'').">Fechado</option>";
                                            echo "<option value='inativo' ".($dados['status']=='inativo'?'selected':'').">Inativo</option>";
                                          }else{
                                            if(!$ha_turno_aberto){
                                              echo "<option value='aberto'  ".($dados['status']=='aberto'?'selected':'').">Aberto</option>";
                                              echo "<option value='fechado' ".($dados['status']=='fechado'?'selected':'').">Fechado</option>";
                                              echo "<option value='inativo' ".($dados['status']=='inativo'?'selected':'').">Inativo</option>";
                                            }else{
                                              echo "<option value='fechado' ".($dados['status']=='fechado'?'selected':'').">Fechado</option>";
                                              echo "<option value='inativo' ".($dados['status']=='inativo'?'selected':'').">Inativo</option>";
                                            }
                                          }
                                      }
                                   ?>
                                 </select>
                            </div>
                          </div>
                      </div>

                      <div class="row">
                            <div class="col-sm-12">
                              <i>* Se já houver um turno aberto, primeiro deve-se fechá-lo para o status deste turno possa ser alterado para "Aberto".</i>
                            </div>
                      </div>

                      <div class="row">
                            <div class="col-sm-12">
                              <div class="form-group">
                              <label class="control-label">Orgão:</label>
                                  <select name="id_company" class="form-control">
                                    <option value="<?=$_SESSION['id_company'];?>"><?=$_SESSION['company_name'];?></option>
                                  </select>
                             </div>
                           </div>
                      </div>
                      <div class="row">
                            <div class="col-sm-12">
                              <div class="form-group">
                              <label class="control-label">Observações:</label>
                                  <textarea name="observation" class="form-control" rows="3"><?=$dados['observation'];?></textarea>
                             </div>
                           </div>
                      </div>

                      <div class="row" style='margin-top:10px'>
                            <div class="col-sm-12 text-center">
                              <input type="hidden" name="acao" value="<?=$acao;?>" />
                              <a href="oct/index.php?id_workshift=<?=$id;?>">
                                <button type='button' class='mb-xs mt-xs mr-xs btn btn-default loading'>Voltar</button>
                              </a>
                              <? if($acao=="atualizar"){ ?>
                                  <!--<a href='oct/turno_sql.php?id=<?=$id;?>&acao=fechar'><button id='bt_fechar_turno'    type='button' class='mb-xs mt-xs mr-xs btn btn-warning loading'>Fechar turno</button></a>-->
                                  <input type="hidden" name="id_workshift" value="<?=$id?>" />
                              <? } ?>
                              <button type='submit' class='mb-xs mt-xs mr-xs btn btn-primary loading'><?=$bt_enviar_txt;?></button>
                           </div>
                      </div>
                    </form>

                  </div><!--<div class="col-md-6">-->
                      <div class="col-md-6">

                          <div class="row">
                            <div class="col-sm-12 text-right">
                                  <h4>Vinculação ao turno</h4>
                                  <hr style="margin-top:-3px;margin-bottom:5px">
                            </div>
                          </div>

                                <? if($id != ""){
                                      echo "<div class='row'><div class='col-sm-12 text-center'><br><br><br><br><br><br><br><a href='oct/turno_associar_pessoa.php?id_workshift=".$id."'><button type='button' class='btn btn-primary'><i class='fa fa-users'></i> Associar pessoa ao turno corrente</button></a></div></div>";

                                    }else {
                                      echo "<br><br><br><br><br><div class='alert alert-warning text-center'>Primeiro deve-se abrir um turno de trabalho para depois associar um recurso.</div>";
                                    }
                                ?>



                      </div>
                </div>
            </div><!--<div class="panel-body">-->
        </section><!--<section class="panel">-->
  </div><!--<div class="col-md-12">-->

<?
  if($id != "")
  {
?>
    <div class="col-md-12">
          <section class="panel box_shadow">
              <header class="panel-heading" style="height:70px">
                  <?="<h4><i><span class='text-muted'>Agentes vinculados ao turno de trabalho <b>nº ".str_pad($id,5,"0",STR_PAD_LEFT)."</b></span></i></h4>";?>
                  <div class="panel-actions text-right"></div>
              </header>
              <div class="panel-body">
                  <div class="row">
                    <div class="col-md-12">
                    <?
                            $sql = "SELECT
                                      	U.name as nome, U.nickname, U.registration,
                                      	WP.*
                                      FROM
                                      	        sepud.oct_rel_workshift_persona WP
                                           JOIN sepud.users                      U ON U.id = WP.id_person
                                      WHERE
                                      	WP.id_shift =  '".$id."'
                                      ORDER BY WP.opened ASC, U.name ASC";
                            $res = pg_query($sql)or die("Erro ".__LINE__."<br>SQL: ".$sql);
                            if(pg_num_rows($res))
                            {

                              $traducao["agente"]      = "Agente de campo";
                              $traducao["central"]     = "Central de atendimento";
                              $traducao["coordenacao"] = "Coordenação";

                              while($d = pg_fetch_assoc($res))
                              {
                                  $agentes_dados[$d['type']][] = $d;
                              }
                                echo "<div class='table-responsive'>";
                                echo "<table class='table table-striped'>";
                                echo "<thead><tr>
                                      <th>Matrícula</th><th>Nome</th><th>Status</th><th>Inicio</th><th>Fim</th><th>Observações</th>
                                      </tr></thead>";
                                echo "<tbody>";
                              foreach($agentes_dados as $designacao => $agentes)
                              {
                                  echo "<tr class='info'><td colspan='8'><b>".$traducao[$designacao]."</b></td></tr>";
                                  for($i=0;$i<count($agentes);$i++)
                                  {
                                      $d = $agentes[$i];
                                      echo "<tr>";
                                        echo "<td>".$d['registration']."</td>";
                                        echo "<td>".$d['nome']."</td>";
                                        echo "<td>".ucfirst($d['status'])."</td>";
                                        echo "<td>".formataData($d['opened'],1)."</td>";
                                        echo "<td>".formataData($d['closed'],1)."</td>";
                                        echo "<td>".$d['observation']."</td>";
                                        echo "<td><a href='oct/turno_sql.php?id_workshift=".$_GET['id']."&acao=remover_associado&id_user=".$d['id']."' class='btn btn-sm btn-danger'><i class='fa fa-trash'></i></a></td>";
                                        echo "<td><a href='oct/turno_associar_pessoa.php?id_workshift=".$_GET['id']."&id_user=".$d['id']."' class='btn btn-sm btn-primary'><i class='fa fa-cogs'></i></a></td>";
                                      echo "</tr>";
                                 }
                              }
                              echo "</tbody></table>";
                              echo "</div>";
                            }else{
                              echo "<div class='alert alert-success text-center'>Nenhum recurso associado a este turno.</div>.";
                            }

                    ?>
                    </div>
                  </div>
              </div>
        </section>
    </div>
<? } ?>





</section>

<script>

$(".campo_hora").mask('00:00');

$('.select2').select2();
$(".loading").click(function(){

  var msg = "Aguarde";
  if($(this).attr("data-loading-msg"))
  {
    msg = $(this).attr("data-loading-msg");
  }
  $(this).html("<i class=\"fa fa-spinner fa-spin\"></i> <small>"+msg+"</small>");
  //$(this).attr("disabled", true);
});
</script>
