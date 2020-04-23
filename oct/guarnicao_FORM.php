<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");
  $agora = now();

  $id_workshift = $_GET['id_workshift'];
  $id_garrison  = $_GET['id_garrison'];

  if($_GET['id_garrison']==""){
      $acao = "Inserir";
      logger("Acesso - Inserção","OCT - Guarnição");
  }else{
    $acao  = "Atualizar";
    $sql   = "SELECT * FROM ".$schema."oct_garrison G WHERE id = '".$id_garrison."'";
    $res   = pg_query($sql)or die("SQL Error ".__LINE__);
    $dados = pg_fetch_assoc($res);

    //Listando todos os colaboradores do turno atual//
    $sql = "SELECT DISTINCT U.id, U.name, U.nickname, U.registration
            FROM ".$schema."oct_rel_workshift_persona W
            JOIN ".$schema."users U ON U.id = W.id_person
            WHERE W.id_shift = '".$id_workshift."' AND W.status in ('ativo', 'HE-Compensação', 'Serviços') ORDER BY U.nickname ASC";
    $res = pg_query($sql)or die("<option>SQL ERROR ".__LINE__."</option>");

    while($d = pg_fetch_assoc($res))
    {
      $opt_users .= "<option value='".$d['id']."'>[".$d['nickname']."] ".$d['name']." - Matrícula: ".$d['registration']."</option>";
    }

    //Veículos vinculados a guarnição//
    $sqlVeic = "SELECT
                 F.plate, F.type, F.model, F.brand, F.nickname,
                 G.*
               FROM
                ".$schema."oct_rel_garrison_vehicle G
               JOIN ".$schema."oct_fleet F ON F.id = G.id_fleet
               WHERE
                id_garrison = '".$id_garrison."'
               ORDER BY F.brand DESC, F.model ASC, F.nickname ASC";
    $resV    = pg_query($sqlVeic)or die("SQL error ".__LINE__."<br>Query: ".$sqlVeic);

    $acao_veiculo = "associar_veiculo";
    while($dv = pg_fetch_assoc($resV)){
      $veic_assoc[$dv['type']][] = $dv;

      if($_GET['acao']=="atualizar_veiculo" && $dv['id']==$_GET['id_rel_garrison_vehicle'])
      {
        $acao_veiculo  = "atualizar_veiculo";
        $dados_veiculo = $dv;
      }
    }

    //Agentes vinculados a guarnição//
    $sqlPer = "SELECT
                	G.*,
                	U.name, U.nickname, U.registration
                FROM
                	".$schema."oct_rel_garrison_persona G
                JOIN ".$schema."users U ON U.id = G.id_user
                WHERE
                	id_garrison = '".$id_garrison."'";
    $resPer   = pg_query($sqlPer)or die("SQL error ".__LINE__."<br>Query: ".$sqlPer);
    while($dp = pg_fetch_assoc($resPer)){

      if($dp['id_rel_garrison_vehicle']!="")
      {
        $per_assoc['veiculo'][$dp['id_rel_garrison_vehicle']][] = $dp;
      }else {
        $per_assoc['a_pe'][] = $dp;
      }
    }
    logger("Acesso - Atualização","OCT - Guarnição", "Guarnição ID:".$_GET['id_garrison']);
  }
?>
<style>

</style>
<section role="main" class="content-body">
  <header class="page-header">
    <h2>Guarnição <sup>(versão 2)</sup></h2>
    <div class="right-wrapper pull-right" style='margin-right:15px;'>
      <ol class="breadcrumbs">
        <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
        <li><a href="oct/index.php">Sistema</a></li>
        <li><span class='text-muted'>Guarnição</span></li>
      </ol>
      <!--<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>-->
    </div>
  </header>


<div class="col-md-12">
								<section class="panel box_shadow">
									<header class="panel-heading" style="height:70px">

                    <div class="panel-actions" style='margin-top:-12px'>
                      <h4>Guarnicão ID: <b><?=$id_garrison;?></b></h4>
                    </div>
                  </header>
									<div class="panel-body">
<form action="oct/guarnicao_SQL.php" method="post">
                    <div class="row">
                      <div class="col-md-6 col-md-offset-3">
                          <h4>Guarnição:</h4>
                          <div class="row">
                                <div class="col-sm-12">
                                  <div class="form-group">
                                   <label class="control-label">Grupamento:</label>
                                    <select class="form-control" disabled>
                                        <option value=""                                                         >O nome será atribuido automaticamente</option>
                                        <option value="alfa"     <?=($dados['name']=='alfa'?"selected":"");?>    >Alfa</option>
                                        <option value="bravo"    <?=($dados['name']=='bravo'?"selected":"");?>   >Bravo</option>
                                        <option value="charlie"  <?=($dados['name']=='charlie'?"selected":"");?> >Charlie</option>
                                        <option value="delta"    <?=($dados['name']=='delta'?"selected":"");?>   >Delta</option>
                                        <option value="echo"     <?=($dados['name']=='echo'?"selected":"");?>    >Echo</option>
                                        <option value="fox"      <?=($dados['name']=='fox'?"selected":"");?>     >Fox</option>
                                        <option value="golf"     <?=($dados['name']=='golf'?"selected":"");?>    >Golf</option>
                                        <option value="hotel"    <?=($dados['name']=='hotel'?"selected":"");?>   >Hotel</option>
                                        <option value="india"    <?=($dados['name']=='india'?"selected":"");?>   >India</option>
                                        <option value="juliet"   <?=($dados['name']=='juliet'?"selected":"");?>  >Juliet</option>
                                        <option value="kilo"     <?=($dados['name']=='kilo'?"selected":"");?>    >Kilo</option>
                                        <option value="lima"     <?=($dados['name']=='lima'?"selected":"");?>    >Lima</option>
                                        <option value="mike"     <?=($dados['name']=='mike'?"selected":"");?>    >Mike</option>
                                        <option value="november" <?=($dados['name']=='november'?"selected":"");?>>November</option>
                                        <option value="oscar"    <?=($dados['name']=='oscar'?"selected":"");?>    >Oscar</option>
                                        <option value="papa"     <?=($dados['name']=='papa'?"selected":"");?>     >Papa</option>
                                        <option value="quebec"   <?=($dados['name']=='quebec'?"selected":"");?>   >Quebec</option>
                                        <option value="romeo"    <?=($dados['name']=='romeo'?"selected":"");?>    >Romeo</option>
                                        <option value="sierra"   <?=($dados['name']=='sierra'?"selected":"");?>   >Sierra</option>
                                        <option value="tango"    <?=($dados['name']=='tango'?"selected":"");?>    >Tango</option>
                                        <option value="uniform"  <?=($dados['name']=='uniform'?"selected":"");?>  >Uniform</option>
                                        <option value="victor"   <?=($dados['name']=='victor'?"selected":"");?>   >Victor</option>
                                        <option value="whiskey"  <?=($dados['name']=='whiskey'?"selected":"");?>  >Whiskey</option>
                                        <option value="xray"     <?=($dados['name']=='xray'?"selected":"");?>     >Xray</option>
                                        <option value="yankee"   <?=($dados['name']=='yankee'?"selected":"");?>   >Yankee</option>
                                        <option value="zulu"     <?=($dados['name']=='zulu'?"selected":"");?>     >Zulu</option>
                                    </select>
                                  </div>
                                </div>
                              </div>
                              <div class='row'>
                                      <div class="col-md-6">
                                                  <div class="form-group">
                                                   <label class="control-label">Início:</label>
                                                   <input id="opened" name="opened" type="datetime-local" class="form-control" value="<?=($dados['opened']!=""?str_replace(" ","T",$dados['opened']):substr(str_replace(" ","T",$agora['datatimesrv']),0,-3));?>">
                                                  </div>
                                                </div>
                                      <div class="col-md-6">
                                                  <div class="form-group">
                                                   <label class="control-label">Fim:</label>
                                                   <input id="closed" name="closed" type="datetime-local" class="form-control" value="<?=($dados['closed']!=""?str_replace(" ","T",$dados['closed']):"");?>">
                                                  </div>
                                      </div>
                                </div>




                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                         <label class="control-label">Observações:</label>
                         <textarea id="observation" name="observation" class="form-control" rows="5"><?=$dados['observation'];?></textarea>
                       </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-12 text-center" style="margin-top:15px">
                          <input type="hidden" id="id_workshift" name="id_workshift" value="<?=$id_workshift;?>">
                          <input type="hidden" id="acao"         name="acao"         value="<?=$acao;?>">
                          <a href="oct/index.php?id_workshift=<?=$id_workshift;?>" class="btn btn-default loading2">Voltar</a>
                          <?
                              if($acao=="Atualizar")
                              {
                                echo "<input type='hidden' name='id_garrison' value='".$_GET['id_garrison']."'>";
                                echo "<input type='hidden' name='name' value='".$dados['name']."'>";
                                echo  "<a href='oct/guarnicao_SQL.php?acao=Remover&id_workshift=".$id_workshift."&id_garrison=".$_GET['id_garrison']."' class='btn btn-danger loading2'>Remover</a>";

                              }
                          ?>
                          <button type="submit" class="btn btn-primary loading"><?=$acao;?></button>
                      </div>
                   </div>

				   </div>
        </div>
</form>


<? if($dados['id'] != ""){ ?>


  <div class='row' style="margin-top:20px">

    <form action="oct/guarnicao_SQL.php" method="post">
    <div class="col-sm-6 col-md-offset-3">
        <div class="row">
            <div class="col-sm-12">
                                  <h4>Veículo(s):</h4>
                                        <?
                                        if($_GET['acao']=="atualizar_veiculo" && isset($dados_veiculo))
                                        {
                                            echo "<div class='form-group'>
                                            <label class='control-label'>Atualizar veículo:</label>
                                            <select id='id_fleet' name='id_fleet' class='form-control'>";
                                            echo "<option value='".$dados_veiculo['id_fleet']."'>".$dados_veiculo['nickname']." [".$dados_veiculo['plate']."] - ".$dados_veiculo['brand']." ".$dados_veiculo['model']."</option>";
                                            echo "</select></div>";

                                        }else{
                                            $sql = "SELECT * FROM ".$schema."oct_fleet WHERE id_company = '".$_SESSION['id_company']."' ORDER BY brand DESC, model ASC, nickname ASC";
                                            $res = pg_query($sql)or die("Erro ".__LINE__);
                                            while($d = pg_fetch_assoc($res)){ $autos[$d['type']][] = $d;}

                                            echo "<div class='form-group'>
                                            <label class='control-label'>Associar veículo:</label>
                                            <select id='id_fleet' name='id_fleet' class='form-control select2'>";

                                                  echo "<option value=''></option>";
                                                  foreach ($autos as $tipo => $auto) {
                                                      echo "<optgroup label='".$tipo."'>";
                                                      for($i=0;$i<count($auto);$i++)
                                                      {
                                                          echo "<option value='".$auto[$i]["id"]."'>".$auto[$i]["nickname"]." [".$auto[$i]["plate"]."] - ".$auto[$i]["brand"]." ".$auto[$i]["model"]."</option>";
                                                      }
                                                      echo "</optgroup>";
                                                  }

                                            echo "</select></div>";
                                         }
                                        ?>

            </div><!--<div class="col-sm-12">-->
        </div><!--<div class="row">-->

          <div class="row">
                <div class="col-md-12">
                                <div class="row">
                                  <div class="col-md-6">
                                    <div class="form-group">
                                     <label class="control-label">KM inicial:</label>
                                     <input name="initial_km" type="number" class="form-control campo_km" value="<?=$dados_veiculo['initial_km'];?>">
                                    </div>
                                  </div>
                                  <div class="col-md-6">
                                    <div class="form-group">
                                      <label class="control-label">KM final:</label>
                                      <input name="final_km" type="number" class="form-control campo_km" value="<?=$dados_veiculo['final_km'];?>">
                                    </div>
                                  </div>
                              </div>
              </div><!--<div class="col-md-12">-->
          </div><!--<div class="row">-->

            <div class="row">
              <div class="col-md-12">
                          <div class="form-group">
                           <label class="control-label">Observações:</label>
                           <textarea id="obs" name="obs" class="form-control" rows="5"><?=$dados_veiculo['obs'];?></textarea>
                         </div>
             </div><!--<div class="col-md-12">-->
            </div><!--<div class="row">-->


        <div class="row">
            <div class="col-sm-12 text-center" style="margin-top:10px">
                          <input  type="hidden" name="id_workshift" id="id_workshift" value="<?=$id_workshift;?>">
                          <input  type="hidden" name="id_garrison"  id="id_garrison"  value="<?=$id_garrison;?>">
                          <input  type="hidden" name="acao"         id="acao"         value="<?=$acao_veiculo;?>">
                          <input  type="hidden" name="id"                             value="<?=$_GET['id_rel_garrison_vehicle'];?>">
                          <?
                              if($acao_veiculo=="atualizar_veiculo")
                              {
                                  echo "<button type='submit' class='btn btn-sm btn-success loading2'><i class='fa fa-cab'></i><sup> <i class='fa fa-plus'></i></sup> Atulizar dados do veículo</button>";
                              }else{
                                  echo "<button type='submit' class='btn btn-sm btn-primary loading2'><i class='fa fa-cab'></i><sup> <i class='fa fa-plus'></i></sup> Associar veículo</button>";
                              }
                          ?>

            </div><!--<div class="col-sm-12">-->
        </div><!--<div class="row">-->
    </div><!--<div class="col-sm-6">-->
    </form>
</div>

<div class='row'>

    <div class="col-md-6 col-md-offset-3">

      <form action="oct/guarnicao_SQL.php" method="post">
      <div class="row">
          <div class="col-sm-12">
                              <h4>Integrantes(s):</h4>
                              <div class="form-group">
                               <label class="control-label">Associar integrante:</label>
                               <select id="id_user" name="id_user" class="form-control select2">
                                  <option value=""></option>
                                  <?=$opt_users;?>
                               </select>
                              </div>
          </div><!--<div class="col-sm-12">-->
      </div><!--<div class="row">-->
      <div class="row">
          <div class="col-sm-12">
                              <div class="form-group">
                               <label class="control-label">Vincular ao veículo:</label>
                               <select id="id_rel_garrison_vehicle" name="id_rel_garrison_vehicle" class="form-control select2">
                                   <?
                                       echo "<option value=''></option>";
                                       if(isset($veic_assoc))
                                       {
                                           foreach ($veic_assoc as $tipo => $veics) {
                                             echo "<optgroup label='".$tipo."'>";
                                               for($i = 0; $i < count($veics);$i++)
                                               {
                                                 unset($v);$v = $veics[$i];
                                                 echo "<option value='".$v["id"]."'>".$v["nickname"]." [".$v["plate"]."] - ".$v["brand"]." ".$v["model"]."</option>";
                                               }
                                             echo "</optgroup>";
                                           }
                                       }else{
                                         echo "<option disabled value=''>Nenhum veículo associado a esta guarnição.</option>";
                                       }
                                   ?>
                               </select>
                             </div>
          </div><!--<div class="col-sm-12">-->
      </div><!--<div class="row">-->


      <div class="row">
          <div class="col-sm-12">
                        <div class="form-group">Posição no veículo:</label>
                         <select name="type" class="form-control select2">
                            <option value=""></option>
                            <option value="Motorista">Motorista</option>
                            <option value="Passageiro">Passageiro</option>
                         </select>
                        </div>
          </div><!--<div class="col-sm-12">-->
      </div><!--<div class="row">-->



      <div class="row">
          <div class="col-sm-12 text-center" style="margin-top:10px">
                    <input  type="hidden" name="id_workshift" id="id_workshift" value="<?=$id_workshift;?>">
                    <input  type="hidden" name="id_garrison"  id="id_garrison"  value="<?=$id_garrison;?>">
                    <input  type="hidden" name="acao"         id="acao"         value="associar_agente_e_veiculo">
                    <input  type="hidden" name="id"                             value="<?=$id_garrison;?>">
                    <button type="submit" class="btn  btn-sm btn-primary loading2"><i class="fa fa-user"></i><sup> <i class="fa fa-plus"></i></sup> Associar agente</button>
          </div><!--<div class="col-sm-12">-->
      </div><!--<div class="row">-->
<? /*
      <div class="row">
          <div class="col-sm-12">

          </div><!--<div class="col-sm-12">-->
      </div><!--<div class="row">-->
*/ ?>
    </div><!--<div class="col-sm-6">-->
  </div><!--<div class='row'>-->


    <div class="row" style="margin-top:30px">
        <div class="col-md-12">

          <div class='row'>
                          <div class="col-md-12">
                            <h5>Agentes designados sem veículo:</h5>
                            <?
                                //print_r_pre($per_assoc['a_pe']);
                                if(isset($per_assoc['a_pe']) && count($per_assoc['a_pe']))
                                {
                                  echo "<div class='table-responsive'>";
                                  echo "<table class='table table-striped'>";
                                  echo "<thead><tr class='text-muted'>
                                              <td><i><small>#</small></i></td>
                                              <td><i><small>Matrícula</small></i></td>
                                              <td><i><small>Apelido</small></i></td>
                                              <td><i><small>Nome</small></i></td></tr></thead>";
                                  echo "</tbody>";
                                    for($i=0;$i<count($per_assoc['a_pe']);$i++)
                                    {
                                      echo "<tr>";
                                       echo "<td><small>".number_format($per_assoc['a_pe'][$i]['id'],0,'','.')."</small></td>";
                                        echo "<td><small>".number_format($per_assoc['a_pe'][$i]['registration'],0,'','.')."</small></td>";
                                        echo "<td>".$per_assoc['a_pe'][$i]['nickname']."</td>";
                                        echo "<td>".$per_assoc['a_pe'][$i]['name']."</td>";
                                        echo "<td class='text-center'>
                                                  <a href='oct/guarnicao_SQL.php?acao=remover_pessoa&id=".$per_assoc['a_pe'][$i]['id']."&id_workshift=".$id_workshift."&id_garrison=".$id_garrison."'>
                                                  <button class='btn btn-xs btn-danger'><i class='fa fa-trash'></i></button>
                                                  </a>
                                              </td>";
                                      echo "</tr>";
                                    }
                                  echo "</tbody>";
                                  echo "</table></div>";
                                }else {
                                  echo "<div class='alert alert-info text-center'>Nenhum agente designido.</div>";
                                }
                            ?>
                          </div>
                      </div>

            <div class="row">
                <div class="col-md-12">
                  <h5>Veículos associados:</h5>
                  <?
                      if(isset($veic_assoc) && count($veic_assoc))
                      {
                        echo "<div class='table-responsive'><table class='table table-striped  table-condensed'>";

                          foreach ($veic_assoc as $tipo => $veiculos) {
                             for($i=0;$i<count($veiculos);$i++)
                             {
                                unset($veiculo,$km_rodado);$veiculo=$veiculos[$i];
                                $km_rodado = ($veiculo['initial_km']!="" && $veiculo['final_km'] != ""?($veiculo['final_km']-$veiculo['initial_km']):0);
                                $class='info';
                                if($km_rodado == 0 || $km_rodado >= 100){ $class="warning";}
                                if($km_rodado < 0)   { $class="danger"; }
                                echo "<tr>";
                                  echo "<td class='info'><small>".$veiculo['id']."</small></td>";
                                  echo "<td class='info'><small><i>Tipo:</i></small><br>".$tipo."</td>";
                                  echo "<td class='info'><small><i>Apelido:</i></small><br><b>".$veiculo['nickname']."</b></td>";
                                  echo "<td class='info'><small><i>Placa:</i></small><br>".$veiculo['plate']."</td>";
                                  echo "<td class='info'><small><i>Marca/Modelo:</i></small><br>".$veiculo['brand']." ".$veiculo['model']."</td>";
                                  echo "<td class='info'><small><i>Km inicial:</i></small><br>".number_format($veiculo['initial_km'],0,'','.')."</td>";
                                  echo "<td class='info'><small><i>Km final:</i></small><br>".number_format($veiculo['final_km'],0,'','.')."</td>";
                                  echo "<td  class='".$class."'><small><i>Total percorrido:</i></small><br>".number_format($km_rodado,0,'','.')." km</td>";

                                  if(isset($per_assoc['veiculo'][$veiculo['id']]) && count($per_assoc['veiculo'][$veiculo['id']]))
                                  {
                                    echo "<td class='info text-center' width='10px'>
                                              <button class='btn btn-sm btn-default' disabled><i class='fa fa-trash'></i></button>
                                          </td>";
                                  }else {
                                    echo "<td class='info text-center' width='10px'>
                                              <a href='oct/guarnicao_SQL.php?acao=remover_veiculo&id=".$veiculo['id']."&id_workshift=".$id_workshift."&id_garrison=".$id_garrison."'>
                                              <button class='btn btn-sm btn-danger'><i class='fa fa-trash'></i></button>
                                              </a>
                                          </td>";
                                  }
                                  echo "<td class='info text-center' width='10px'>
                                            <a href='oct/guarnicao_FORM.php?acao=atualizar_veiculo&id_rel_garrison_vehicle=".$veiculo['id']."&id_workshift=".$id_workshift."&id_garrison=".$id_garrison."'>
                                            <button class='btn btn-sm btn-success'><i class='fa fa-cogs'></i></button>
                                            </a>
                                        </td>";
                                echo "</tr>";

                                echo "<tr><td colspan='10'><small class='text-muted'><i>Observações:</i></small><br>".$veiculo['obs']."</td></tr>";

                                if(isset($per_assoc['veiculo'][$veiculo['id']]) && count($per_assoc['veiculo'][$veiculo['id']]))
                                {
                                  unset($ocupantes);$ocupantes = $per_assoc['veiculo'][$veiculo['id']];
                                  echo "<tr>
                                          <td class='text-muted'>&nbsp;</td>
                                          <td class='text-muted'><i><small>Matrícula</small></i></td>
                                          <td class='text-muted'><i><small>Posição</small></i></td>
                                          <td class='text-muted'><i><small>Apelido</small></i></td>
                                          <td class='text-muted' colspan='4'><i><small>Nome</small></i></td>
                                          <td class='text-muted' colspan='2'>Ação</td>";
                                  for($c=0;$c<count($ocupantes);$c++)
                                  {
                                    echo "<tr>";
                                    echo "<td class='text-muted'><small>".number_format($ocupantes[$c]['id'],0,'','.')."</small></td>";
                                    echo "<td class='text-muted'><small>".number_format($ocupantes[$c]['registration'],0,'','.')."</small></td>";
                                    echo "<td><b>".$ocupantes[$c]['type']."</b></td>";
                                    echo "<td>".$ocupantes[$c]['nickname']."</td>";
                                    echo "<td colspan='4'>".$ocupantes[$c]['name']."</td>";
                                    echo "<td colspan='2' class='text-left'>
                                              <a href='oct/guarnicao_SQL.php?acao=remover_pessoa&id=".$ocupantes[$c]['id']."&id_workshift=".$id_workshift."&id_garrison=".$id_garrison."'>
                                              <button class='btn btn-sm btn-danger'><i class='fa fa-trash'></i></button>
                                              </a>
                                          </td>";
                                    echo "</tr>";
                                  }
                                }else{
                                  echo "<tr><td colspan='6' class='text-danger'><small><i><b>Atenção:</b> Veículo sem condutor, favor atualizar.</i></small></td></tr>";
                                }
                              }
                          }
                        echo "</table></div>";
                      }else{
                        echo "<div class='alert alert-info text-center'>Nenhum veículo associado.</div>";
                      }
                  ?>
                </div>
</div>


        </div><!--<div class="col-sm-12">-->
    </div><!--<div class="row">-->


<? } ?>

                </div>
            </section>
        </div>

</section>


<script>
$('.select2').select2({
  language: {
        noResults: function() {
          return 'Nenhum resultado encontrado.';
        }
      }
});
$(document).ready(function(){ $(this).scrollTop(0);});
$(".campo_km").mask('000000');

$("#bt_add_veic_aa").click(function(){
    <? if($acao=="Atualizar"){ ?>
    var id_garrison  = <?=$dados['id'];?>;
    var id_fleet     = $("#id_fleet").val();
    var id_workshift = <?=$id_workshift;?>;
    //alert("ASSOCIAR Passageiro:\nTurno: "+turno+"\nID da guarnição: "+id_garrison+"\nId user passageiro: "+id_user_pass);
    $('#wrap').load("oct/guarnicao_SQL.php?acao=associar_veiculo&id_workshift="+id_workshift+"&id_garrison="+id_garrison+"&id_fleet="+id_fleet);
    <? } ?>
    return false;

});

/*
function remove_passageiro(id_user_pass)
{
  <? if($acao=="Atualizar"){ ?>
  var id_garrison  = <?=$dados['id'];?>;
  var turno        = <?=$id_workshift;?>;
  //alert("REMOVER Passageiro:\nTurno: "+turno+"\nID da guarnição: "+id_garrison+"\nId user passageiro: "+id_user_pass);
  $('#wrap').load("oct/veiculo_turno_SQL.php?acao=remover_passageiro&turno="+turno+"&id_garrison="+id_garrison+"&id_user_pass="+id_user_pass);
  <? } ?>
  return false;
}
*/
$("#bt_add_passaaa").click(function(){
    <? if($acao=="Atualizar"){ ?>
    var id_garrison         = <?=$dados['id'];?>;
    var id_workshift        = <?=$id_workshift;?>;
    var id_user             = $("#id_user").val();
    var id_rel_garrison_vehicle = $("#id_rel_garrison_vehicle").val();

  //alert("ASSOCIAR agente e veículo:\nTurno: "+id_workshift+"\nID da guarnição: "+id_garrison+"\nId user: "+id_user+"\nId rel veiculo guarn.: "+id_rel_garrison_vehicle);
    $('#wrap').load("oct/guarnicao_SQL.php?acao=associar_agente_e_veiculo&id_workshift="+id_workshift+"&id_garrison="+id_garrison+"&id_user="+id_user+"&id_rel_garrison_vehicle="+id_rel_garrison_vehicle);
    <? } ?>
    return false;

});

$(".loading").click(function(){ $(this).html("<i class=\"fa fa-spinner fa-spin\"></i> Aguarde");});
$(".loading2").click(function(){ $(this).addClass("disabled").html("<i class=\"fa fa-spinner fa-spin\"></i>");});
</script>
