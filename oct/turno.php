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
    $acao  = "atualizar";
    $bt_enviar_txt = "Atualizar";
  }else{
    $acao = "inserir";
    $bt_enviar_txt = "Inserir novo turno de trabalho";
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
                     else{ echo "<h4><i><span class='text-muted'>Turno <b>nº ".str_pad($id,5,"0",STR_PAD_LEFT)."</b> aberto</span></i></h4>"; }
                 ?>
                </div>
            </header>
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
                                  <input type="text" name="opened" class="form-control text-center" value="<?=$agora['data'];?>">
                             </div>
                           </div>

                            <div class="col-sm-6">
                              <div class="form-group">
                              <label class="control-label">Data de fechamento:</label>
                                  <input type="text" name="closed" class="form-control text-center" value="<?=$agora['data'];?>">
                             </div>
                           </div>
                      </div>
                      <div class="row">
                            <div class="col-sm-6">
                              <div class="form-group">
                              <label class="control-label">Hora de abertura:</label>
                                  <input type="text" name="opened_hour" id="opened_hour" class="form-control text-center campo_hora" placeholder="00:00"  value="">
                             </div>
                           </div>

                            <div class="col-sm-6">
                              <div class="form-group">
                              <label class="control-label">Hora de fechamento:</label>
                                  <input type="text" name="closed_hour" class="form-control text-center campo_hora" placeholder="00:00" value="">
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
                                  <select name="period" class="form-control">
                                      <option value="alfa"    <?=($dados['status']=="alfa"  ?"selected":"");?>>Alfa</option>
                                      <option value="bravo"   <?=($dados['status']=="bravo" ?"selected":"");?>>Bravo</option>
                                      <option value="charlie" <?=($dados['status']=="charlie"?"selected":"");?>>Charlie</option>
                                  </select>
                             </div>
                           </div>

                           <div class="col-sm-6">
                             <div class="form-group">
                             <label class="control-label">Status do turno:</label>
                                 <select name="status" class="form-control">
                                   <? if($acao == "inserir")
                                      {
                                          echo "<option value='aberto'>Aberto</option>";
                                      }else{
                                          echo "<option value='aberto'>Aberto</option>";
                                          echo "<option value='fechado'>Fechado</option>";
                                      }
                                   ?>
                                 </select>
                            </div>
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
                              <a href="oct/index.php">
                                <button type='button' class='mb-xs mt-xs mr-xs btn btn-default loading'>Voltar</button>
                              </a>
                              <? if($acao=="atualizar"){ ?>
                                  <a href='oct/turno_sql.php?id=<?=$id;?>&acao=fechar'><button id='bt_fechar_turno'    type='button' class='mb-xs mt-xs mr-xs btn btn-warning loading'>Fechar turno</button></a>
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

                                <? if($id != ""){ ?>
                                    <form name="person" method="post" action="oct/turno_sql.php">
                                          <div class="row">
                                                <div class="col-sm-12">
                                                  <div class="form-group">
                                                  <label class="control-label">Colaborador:</label>
                                                      <select name="id_user" class="form-control select2">
                                                          <?

                                                              //Lista os colaboradores que ainda não estão associados ao turno, evita que um mesmo colaborador seja associado a mais de uma função dentro do turno.
                                                              /*
                                                              $sql = "SELECT
                                                                      	U.*
                                                                      FROM
                                                                      	sepud.users U
                                                                      LEFT JOIN sepud.oct_rel_workshift_persona T ON T.id_person = U.id AND T.id_shift = '".$id."'
                                                                      WHERE
                                                                      	U.id_company = '".$_SESSION[id_company]."' AND T.id_shift is null";
                                                              */
                                                              //Libera para que um mesmo usuario possa receber mais de uma atribuição no turno, ex: coordenação e central de atendimento.
                                                              $sql = "SELECT * FROM sepud.users WHERE id_company = '".$_SESSION['id_company']."' ORDER BY name ASC";
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
                                                          ?>
                                                      </select>
                                                 </div>
                                               </div>
                                              </div>

                                             <div class="row">
                                                     <div class="col-sm-6">
                                                       <div class="form-group">
                                                       <label class="control-label">Data de entrada:</label>
                                                           <input type="text" name="opened" class="form-control text-center" value="<?=($_SESSION['user_opened']!=""?$_SESSION['user_opened']:$agora['data']);?>">
                                                      </div>
                                                    </div>

                                                     <div class="col-sm-6">
                                                       <div class="form-group">
                                                       <label class="control-label">Data de saída:</label>
                                                           <input type="text" name="closed" class="form-control text-center" value="<?=($_SESSION['user_closed']!=""?$_SESSION['user_closed']:$agora['data']);?>">
                                                      </div>
                                                    </div>
                                               </div>
                                               <div class="row">
                                                     <div class="col-sm-6">
                                                       <div class="form-group">
                                                       <label class="control-label">Hora de entrada:</label>
                                                           <input type="text" name="opened_hour" onclick="$(this).val('');" class="form-control text-center campo_hora" placeholder="00:00" value="<?=($_SESSION['user_opened_hour']!=""?$_SESSION['user_opened_hour']:"");?>">
                                                      </div>
                                                    </div>

                                                     <div class="col-sm-6">
                                                       <div class="form-group">
                                                       <label class="control-label">Hora de saída:</label>
                                                           <input type="text" name="closed_hour"  onclick="$(this).val('');" class="form-control text-center campo_hora" placeholder="00:00" value="<?=($_SESSION['user_closed_hour']!=""?$_SESSION['user_closed_hour']:"");?>">
                                                      </div>
                                                    </div>
                                               </div>

                                               <div class="row">
                                                     <div class="col-sm-12">
                                                       <div class="form-group">
                                                       <label class="control-label">Funcão:</label>
                                                           <select name="type" class="form-control">
                                                               <option value="agente">Agente de campo</option>
                                                               <option value="central">Central de atendimento</option>
                                                               <option value="coordenacao">Coordenação</option>
                                                           </select>
                                                      </div>
                                                    </div>
                                              </div>
<!--
                                              <div class="row">
                                                    <div class="col-sm-8">
                                                      <div class="form-group">
                                                      <label class="control-label">Veículo:</label>
                                                        <select name="id_fleet" class="form-control">
                                                            <option value="">- - -</option>
                                                            <?
                                                                $sql = "SELECT * FROM sepud.oct_fleet WHERE id_company = '".$_SESSION['id_company']."' ORDER BY type ASC, brand ASC, model ASC";
                                                                //$res = pg_query($sql)or die("Erro ".__LINE__."<br>SQL: ".$sql);
                                                                while($v = pg_fetch_assoc($res))
                                                                {
                                                                  $veic[$v['type']][]=$v;
                                                                }
                                                                  foreach ($veic as $type => $info) {
                                                                    echo "<optgroup label='".ucfirst($type)."'>";
                                                                    for($i=0; $i<count($info);$i++)
                                                                    {
                                                                        echo "<option value='".$info[$i]['id']."'>".$info[$i]['plate']." - ".$info[$i]['brand']." ".$info[$i]['model']."</option>";
                                                                    }
                                                                  }
                                                            ?>
                                                          </select>
                                                     </div>
                                                   </div>
                                                   <div class="col-sm-4">
                                                     <div class="checkbox" style="margin-top:35px">
                             														<label><input name="is_driver" type="checkbox" value="true">É o motorista ?</label>
                             													</div>
                                                   </div>
                                              </div>
-->

                                              <div class="row">
                                                  <div class="col-sm-12 text-center">
                                                      <input type="hidden" name="acao" value="associar" />
                                                      <input type="hidden" name="id_workshift" value="<?=$id?>" />
                                                      <button type='submit' class='mb-xs mt-xs mr-xs btn btn-primary loading' data-loading-msg="Aguarde, processando informação." <?=$bt_associar_recurso;?>>Associar ao turno corrente</button>
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

                                echo "<table class='table table-striped'>";
                                echo "<thead><tr>
                                      <th>Matrícula</th><th>Nome</th><th>Inicio</th><th>Fim</th>
                                      </tr></thead>";
                                echo "<tbody>";
                              foreach($agentes_dados as $designacao => $agentes)
                              {
                                  echo "<tr class='info'><td colspan='5'><b>".$traducao[$designacao]."</b></td></tr>";
                                  for($i=0;$i<count($agentes);$i++)
                                  {
                                      $d = $agentes[$i];
                                      echo "<tr>";
                                        echo "<td>".$d['registration']."</td>";
                                        echo "<td>".$d['nome']."</td>";
                                        echo "<td>".formataData($d['opened'],1)."</td>";
                                        echo "<td>".formataData($d['closed'],1)."</td>";
                                      echo "</tr>";
                                 }
                              }
                              echo "</tbody></table>";
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
