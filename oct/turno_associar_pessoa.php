<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");

  logger("Acesso","OCT", "Turno");
  $agora = now();

  if($_GET['id_workshift']!=""){
    $id_workshift    = $_GET['id_workshift'];
    $sql             = "SELECT * FROM sepud.oct_workshift WHERE id = ".$id_workshift;
    $res             = pg_query($sql)or die("Erro ".__LINE__."<br>SQL: ".$sql);
    $dados_workshift = pg_fetch_assoc($res)or die("SQL Error ".__LINE__);

    if($_GET['id_user']!="")
    {
      $id_user = $_GET['id_user'];
      $sql = "SELECT
              	U.name, U.nickname, U.registration,
              	R.*
              FROM
              	sepud.oct_rel_workshift_persona R,
              	sepud.users U
              WHERE
              	R.id_person = U.id AND
              	R.id_shift  = '".$id_workshift."'   AND
              	R.id_person = '".$id_user."'";
      $res = pg_query($sql)or die("SQL error ".__LINE__);
      $dados_user = pg_fetch_assoc($res);
      $acao = "atualizar_associado";
      $bt_acao = "Atualizar";
    }else {
      $acao    = "novo_associado";
      $bt_acao = "Inserir nova associação";
    }
  }else {
    echo "Nenhum turno informado...";
    exit();
  }
?>

<section role="main" class="content-body">
  <header class="page-header">
    <h2>Pessoal vinculado ao turno</h2>
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
                  <? if($id_workshift == ""){ echo "<h4><i><b>Nenhum turno aberto</b></i></h4>"; }
                     else{ echo "<h4><i><span class='text-muted'>Turno <b>nº ".str_pad($id_workshift,5,"0",STR_PAD_LEFT)."</b> aberto</span></i></h4>"; }
                 ?>
                </div>
            </header>
            <div class="panel-body">

<?
//echo "<div class='col-md-6 col-md-offset-3'>";
//print_r_pre($dados_user);
//echo "</div>";
?>


                <div class="row">

                      <div class="col-md-6 col-sm-offset-3">

                          <div class="row">
                            <div class="col-sm-12 text-right">
                                  <h4>Vinculação ao turno</h4>
                                  <hr style="margin-top:-3px;margin-bottom:5px">
                            </div>
                          </div>

                                <? if($id_workshift != ""){ ?>
                                    <form name="person" method="post" action="oct/turno_sql.php">
                                          <div class="row">
                                                <div class="col-sm-12">
                                                  <div class="form-group">
                                                  <label class="control-label">Colaborador:</label>
                                                      <select name="id_user" class="form-control <?=($id_user==""?"select2":"");?>">
                                                          <?

                                                              if($id_user=="")
                                                              {
                                                                    $sql = "SELECT * FROM sepud.users WHERE id_company = '".$_SESSION['id_company']."' AND active = true ORDER BY name ASC";

                                                                    $res = pg_query($sql)or die("<option>Erro ".__LINE__." - SQL: ".$sql."</option>");

                                                                    if(pg_num_rows($res))
                                                                    {
                                                                        while($u = pg_fetch_assoc($res))
                                                                        {
                                                                          if($u['nickname']!=""){ $nick = "[".$u['nickname']."] ";}else{$nick="";}
                                                                          if($u['registration']!=""){ $mat = " - Matrícula: ".$u['registration'];}else{$mat="";}
                                                                          echo "<option value='".$u['id']."'>".$nick.$u['name'].$mat."</option>";
                                                                        }
                                                                    }else{
                                                                          $bt_associar_recurso = "disabled";
                                                                          echo "<option>Todos os colaboradores já foram associados ao turno.</option>";
                                                                    }
                                                                }else {
                                                                  if($dados_user['nickname']!=""){ $nick = "[".$dados_user['nickname']."] ";}else{$nick="";}
                                                                  if($dados_user['registration']!=""){ $mat = " - Matrícula: ".$dados_user['registration'];}else{$mat="";}
                                                                  echo "<option value='".$dados_user['id_person']."'>".$nick.$dados_user['name'].$mat."</option>";
                                                                }
                                                          ?>
                                                      </select>
                                                 </div>
                                               </div>
                                              </div>

                                             <div class="row">
                                                     <div class="col-sm-6">
                                                       <div class="form-group">
                                                       <label class="control-label">Data de entrada:</label>
                                                        <?
                                                              if($dados_user['opened']!="")
                                                              {
                                                                     $dt_opened = substr(formataData($dados_user['opened'],1),0,10);
                                                              }else
                                                              {
                                                                    if($dados_workshift['opened']!="")
                                                                    {
                                                                      $dt_opened = substr(formataData($dados_workshift['opened'],1),0,10);
                                                                    }else{
                                                                      $dt_opened = $agora['data'];
                                                                    }
                                                              }

                                                              if($dados_user['closed']!="")
                                                              {
                                                                     $dt_closed = substr(formataData($dados_user['closed'],1),0,10);
                                                              }else
                                                              {
                                                                    if($dados_workshift['closed']!="")
                                                                    {
                                                                      $dt_closed = substr(formataData($dados_workshift['closed'],1),0,10);
                                                                    }else{
                                                                      $dt_closed = $agora['data'];
                                                                    }
                                                              }
                                                        ?>
                                                           <input type="text" name="opened" class="form-control text-center" value="<?=$dt_opened;?>">
                                                      </div>
                                                    </div>

                                                     <div class="col-sm-6">
                                                       <div class="form-group">
                                                       <label class="control-label">Data de saída:</label>
                                                           <input type="text" name="closed" class="form-control text-center" value="<?=$dt_closed;?>">
                                                      </div>
                                                    </div>
                                               </div>
                                               <div class="row">
                                                     <div class="col-sm-6">
                                                       <div class="form-group">
                                                       <label class="control-label">Hora de entrada:</label>
                                                           <input type="text" name="opened_hour" onclick="$(this).val('');" class="form-control text-center campo_hora" placeholder="00:00" value="<?=($dados_user['opened']!=""?substr($dados_user['opened'],11):"00:00");?>">
                                                      </div>
                                                    </div>

                                                     <div class="col-sm-6">
                                                       <div class="form-group">
                                                       <label class="control-label">Hora de saída:</label>
                                                           <input type="text" name="closed_hour"  onclick="$(this).val('');" class="form-control text-center campo_hora" placeholder="00:00" value="<?=($dados_user['closed']!=""?substr($dados_user['closed'],11):"23:59");?>">
                                                      </div>
                                                    </div>
                                               </div>

                                               <div class="row">
                                                     <div class="col-sm-6">
                                                       <div class="form-group">
                                                       <label class="control-label">Funcão:</label>
                                                           <select name="type" class="form-control">
                                                               <option value="agente"      <?=($dados_user['type']=="agente"?"selected":"");?>>Agente de campo</option>
                                                               <option value="central"     <?=($dados_user['type']=="central"?"selected":"");?>>Central de atendimento</option>
                                                               <option value="coordenacao" <?=($dados_user['type']=="coordenacao"?"selected":"");?>>Coordenação</option>
                                                           </select>
                                                      </div>
                                                    </div>

                                                    <div class="col-sm-6">
                                                      <div class="form-group">
                                                      <label class="control-label">Status:</label>
                                                          <select name="status" class="form-control">
                                                              <option value="ativo"    <?=($dados_user['status']=="baixado"?"selected":"");?>>Ativo</option>
                                                              <option value="baixado"  <?=($dados_user['status']=="baixado"?"selected":"");?>>Baixado</option>
                                                              <option value="folga"    <?=($dados_user['status']=="folga"?"selected":"");?>>Folga</option>
                                                              <option value="troca"    <?=($dados_user['status']=="troca"?"selected":"");?>>Troca</option>
                                                              <option value="ferias"   <?=($dados_user['status']=="ferias"?"selected":"");?>>Férias</option>
                                                              <option value="falta"    <?=($dados_user['status']=="falta"?"selected":"");?>>Faltou</option>
                                                              <option value="atestado" <?=($dados_user['status']=="atestado"?"selected":"");?>>Atestado</option>
                                                              <option value="licença"  <?=($dados_user['status']=="licença"?"selected":"");?>>Licença</option>
                                                          </select>
                                                     </div>
                                                   </div>
                                              </div>
                                              <div class="row">
                                                    <div class="col-sm-12">
                                                      <div class="form-group">
                                                      <label class="control-label">Observações:</label>
                                                      <textarea name="observation" class="form-control" rows="3"><?=$dados_user["observation"];?></textarea>
                                                    </div>
                                              </div>

                                              <div class="row">
                                                  <div class="col-sm-12 text-center">
                                                      <input type="hidden" name="acao"         value="<?=$acao;?>" />
                                                      <input type="hidden" name="id_rel_workshift_persona" value="<?=$dados_user['id'];?>" />
                                                      <input type="hidden" name="id_workshift" value="<?=$id_workshift?>" />

                                                      <a href="oct/index.php?id_workshift=<?=$id_workshift;?>"><button type='button' class='mb-xs mt-xs mr-xs btn btn-default loading'>Voltar</button></a>
                                                      <? if($id_user != ""){ ?>
                                                      <a href='oct/turno_associar_pessoa.php?id_workshift=<?=$id_workshift?>'><button type='button' class='btn btn-default'>Inserir nova associação</button></a>
                                                      <? } ?>
                                                      <button type='submit' class='mb-xs mt-xs mr-xs btn btn-primary loading' data-loading-msg="Aguarde, processando informação." <?=$bt_associar_recurso;?>><?=$bt_acao;?></button>
                                                      <? if($dados_workshift['is_populate']=='f'){ ?>
                                                         <a class='mb-xs mt-xs mr-xs btn btn-primary loading' href="oct/turno_gerar_associacao_automatica.php?id_workshift=<?=$id_workshift;?>">Inserir todos os funcionários configurados para este turno</a>
                                                      <? } ?>
                                                  </div>
                                            </div>

                                    </form>
                                <?
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
  if($id_workshift != "")
  {
?>
    <div class="col-md-12">
          <section class="panel box_shadow">
              <header class="panel-heading" style="height:70px">
                  <?="<h4><i><span class='text-muted'>Agentes vinculados ao turno de trabalho <b>nº ".str_pad($id_workshift,5,"0",STR_PAD_LEFT)."</b></span></i></h4>";?>
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
                                      	WP.id_shift =  '".$id_workshift."'
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
                                        echo "<td><a href='oct/turno_sql.php?id_workshift=".$id_workshift."&acao=remover_associado&id=".$d['id']."' class='btn btn-sm btn-danger'><i class='fa fa-trash'></i></a></td>";
                                        echo "<td><a href='oct/turno_associar_pessoa.php?id_workshift=".$id_workshift."&id_user=".$d['id_person']."' class='btn btn-sm btn-primary'><i class='fa fa-cogs'></i></a></td>";
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
$(document).ready(function(){ $(this).scrollTop(0);});
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
