<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");
  $agora = now();



  if($_GET['id']!="")
  {
    $acao = "Atualizar";
    $sql = "SELECT *
            FROM {$schema}ses_pncd_registro_diario_atividade
            WHERE id = '{$_GET['id']}'";
    $res = pg_query($sql)or die("Error ".__LINE__."<br>Query: {$sql}");
    $d   = pg_fetch_assoc($res);
  }else
  {
    $acao = "Inserir";
    $d['id_ses_pncd_registro_diario'] = $_GET['id_rds'];
  }
?>


<section role="main">
   <header class="page-header" style="top:0px;left:0px">
    <h2>Registro diário de serviço</h2>
    <div style='position: absolute;top: 8px;right: 10px;'>
      <a href='auth/logout.php' ajax="false"><button type="button" class="btn btn-default">Sair</button></a>
   </div>
   </header>

  <section class="panel box_shadow">

<div class="panel-body" style="margin-top:60px">
      <div class="row">
          <div class="col-md-12">

            <form action="ses/mobile_rds_atividade_FORM_sql.php" method="post">

<h4>Registro de atividade:</h4>

<div class="row">
  <div class="col-md-4">
          <div class='form-group'>
                <label class='col-md-6 control-label' for='hora_entrada'>Hora de entrada:</label>
                <div class='col-md-6'>
                  <input type='text' class='form-control' id='hora_entrada' name='hora_entrada' placeholder='' value='<?=$d['hora_entrada'];?>' >
                </div>
          </div>
  </div>
  <div class="col-md-8 text-right">
      <span class='text-muted'><i><small>Agente:</small> <b><?=$_SESSION['name'];?></b></i></span><br>
      <span class='text-muted'><i><?=$_SESSION['company_name'];?></i></span>
  </div>
</div>

<hr>
<h4>Endereço:</h4>


                      <div class="row">

                            <div class='col-md-6'>
                              <div class='form-group'>
                                <label class='control-label' for='id_logradouro'>Logradouro:</label>
                                <?
                                    $sql = "SELECT id, name FROM {$schema}streets ORDER BY name ASC";
                                    $res = pg_query($sql)or die("Error ".__LINE__."<br>Query: {$sql}");
                                ?>
                                <select class='form-control select2' id='id_logradouro' name='id_logradouro'>
                                    <option value="">- - -</option>
                                  <?
                                      while ($s = pg_fetch_assoc($res)) {
                                        if($s['id']==$d['id_logradouro']){$sel="selected";}else{$sel="";}
                                        echo "<option value='{$s['id']}' {$sel}>{$s['name']}</option>";
                                      }
                                  ?>
                                </select>
                              </div>
                            </div>


                            <div class='col-md-2'>
                              <div class='form-group'>
                                <label class='control-label' for='num_quarteirao'>Quarteirão:</label>
                                <input type='number' class='form-control' id='num_quarteirao' name='num_quarteirao' placeholder='' value='<?=$d['num_quarteirao'];?>' >
                              </div>
                            </div>

                            <div class='col-md-2'>
                              <div class='form-group'>
                                <label class='control-label' for='sequencia_lado'>Lado:</label>
                                <select class='form-control' id='sequencia_lado' name='sequencia_lado'>
                                    <option value="D - Direito"   <?=($d['sequencia_lado']=="D - Direito"?"selected":"");?>>D - Direito</option>
                                    <option value="E - Esquerdo"  <?=($d['sequencia_lado']=="E - Esquerdo"?"selected":"");?>>E - Esquerdo</option>
                                </select>
                              </div>
                            </div>

                            <div class='col-md-2'>
                              <div class='form-group'>
                                    <label class='control-label' for='num_sequencia'>Nº Seq:</label>
                                      <input type='text' class='form-control' id='num_sequencia' name='num_sequencia' placeholder='' value='<?=$d['num_sequencia'];?>' >
                              </div>
                            </div>

                      </div>

<div class="row">
    <div class='col-md-3'>
      <div class='form-group'>
        <label class='control-label' for='complemento'>Complemento:</label>
        <input type='text' class='form-control' id='complemento' name='complemento' placeholder='' value='<?=$d['complemento'];?>' >
      </div>
    </div>


    <div class='col-md-3'>
      <div class='form-group'>
        <label class='control-label' for='tipo_imovel'>Tipo do imóvel:</label>
        <select class="form-control" id='tipo_imovel' name='tipo_imovel'>
            <option value="">- - -</option>
            <option value="R - Residêncial"        <?=($d['tipo_imovel']=="E - Esquerdo"?"selected":"");?>>R - Residêncial</option>
            <option value="C - Comércio"           <?=($d['tipo_imovel']=="C - Comércio"?"selected":"");?>>C - Comércio</option>
            <option value="TB - Terreno baldio"    <?=($d['tipo_imovel']=="TB - Terreno baldio"?"selected":"");?>>TB - Terreno baldio</option>
            <option value="PE - Ponto estratégico" <?=($d['tipo_imovel']=="PE - Ponto estratégico"?"selected":"");?>>PE - Ponto estratégico</option>
            <option value="O - Outros"             <?=($d['tipo_imovel']=="O - Outros"?"selected":"");?>>O - Outros</option>
        </select>
      </div>
    </div>

    <div class='col-md-2'>
      <div class='form-group'>
        <label class='control-label' for='visita'>Visita:</label>
        <select class="form-control" id='visita' name='visita'>
            <option value="N - Normal"     <?=($d['visita']=="N - Normal"?"selected":"");?>>N - Normal</option>
            <option value="R - Recuperada" <?=($d['visita']=="R - Recuperada"?"selected":"");?>>R - Recuperada</option>
        </select>
      </div>
    </div>

    <div class='col-md-2'>
      <div class='form-group'>
        <label class='control-label' for='pendencia'>Pendência:</label>
        <select class='form-control' id='pendencia' name='pendencia'>
            <option value="">- - -</option>
            <option value="F - Fechado"  <?=($d['pendencia']=="F - Fechado"?"selected":"");?>>F - Fechado</option>
            <option value="R - Recusado" <?=($d['pendencia']=="R - Recusado"?"selected":"");?>>R - Recusado</option>
        </select>
      </div>
    </div>

</div>

<hr>
<h4>Inspeção:
  <button type="button" class="mb-xs mt-xs mr-xs btn btn-info" data-toggle="modal" data-target="#modal_help">
    <i class="fa fa-info"></i></button>
  </button>
</h4>
<div class="row">

          <div class='col-md-1'>
            <div class='form-group'>
                <label class='control-label' for='inspecao_a1'>A1:</label>
                <input type='number' class='form-control' id='inspecao_a1' name='inspecao_a1' placeholder='' value='<?=$d['inspecao_a1'];?>' >
              </div>
            </div>

        <div class='col-md-1'>
          <div class='form-group'>
              <label class='control-label' for='inspecao_a2'>A2:</label>
              <input type='number' class='form-control' id='inspecao_a2' name='inspecao_a2' placeholder='' value='<?=$d['inspecao_a2'];?>' >
          </div>
        </div>


        <div class='col-md-1'>
          <div class='form-group'>
            <label class='control-label' for='inspecao_b'>B:</label>
            <input type='number' class='form-control' id='inspecao_b' name='inspecao_b' placeholder='' value='<?=$d['inspecao_b'];?>' >
          </div>
        </div>

        <div class='col-md-1'>
          <div class='form-group'>
              <label class='control-label' for='inspecao_c'>C:</label>
              <input type='number' class='form-control' id='inspecao_c' name='inspecao_c' placeholder='' value='<?=$d['inspecao_c'];?>' >
          </div>
        </div>

        <div class='col-md-1'>
          <div class='form-group'>
            <label class='control-label' for='inspecao_d1'>D1:</label>
            <input type='number' class='form-control' id='inspecao_d1' name='inspecao_d1' placeholder='' value='<?=$d['inspecao_d1'];?>' >
          </div>
        </div>


<div class='col-md-1'>
        <div class='form-group'>
                    <label class='control-label' for='inspecao_d2'>D2:</label>
                      <input type='number' class='form-control' id='inspecao_d2' name='inspecao_d2' placeholder='' value='<?=$d['inspecao_d2'];?>' >
                    </div>
                  </div>
<div class='col-md-1'>
        <div class='form-group'>
                    <label class='control-label' for='inspecao_e'>E:</label>
                      <input type='number' class='form-control' id='inspecao_e' name='inspecao_e' placeholder='' value='<?=$d['inspecao_e'];?>' >
                    </div>
                  </div>
<div class='col-md-2'>
        <div class='form-group'>
                    <label class='control-label' for='eliminado'>Elimidado:</label>
                      <input type='number' class='form-control' id='eliminado' name='eliminado' placeholder='' value='<?=$d['eliminado'];?>' >
                    </div>
                  </div>
<div class='col-md-3'>
            <div class='form-group'>
                        <label class='control-label' for='imovel_inspec_li'>Imov. insp. LI</label>
                          <input type='number' class='form-control' id='imovel_inspec_li' name='imovel_inspec_li' placeholder='' value='<?=$d['imovel_inspec_li'];?>' >
                        </div>
                      </div>


</div>
<div class="row">
  <div class="col-sm-6">

  </div>
</div>

<hr>
<h4>Coleta de amostras:</h4>
<div class="row">

    <div class='col-md-2'>
    <div class='form-group'>
              <label class='control-label' for='num_amostra_inicial'>Nº amostra inicial:</label>
                <input type='text' class='form-control' id='num_amostra_inicial' name='num_amostra_inicial' placeholder='' value='<?=$d['num_amostra_inicial'];?>' >
              </div>
            </div>

<div class='col-md-2'>
  <div class='form-group'>
              <label class='control-label' for='num_amostra_final'>Nº amostra final:</label>
                <input type='text' class='form-control' id='num_amostra_final' name='num_amostra_final' placeholder='' value='<?=$d['num_amostra_final'];?>' >
              </div>
            </div>

<div class='col-md-2'>
  <div class='form-group'>
              <label class='control-label' for='qtd_tubitos'>Qtd. tubitos:</label>
                <input type='text' class='form-control' id='qtd_tubitos' name='qtd_tubitos' placeholder='' value='<?=$d['qtd_tubitos'];?>' >
              </div>
            </div>

</div>


<hr>
<h4>Tratamento Focal:</h4>

<div class="row">

            <div class='col-md-2'>
              <div class='form-group'>
                  <label class='control-label' for='lm_trat'>Im. Trat.:</label>
                  <input type='text' class='form-control' id='lm_trat' name='lm_trat' placeholder='' value='<?=$d['lm_trat'];?>' >
              </div>
          </div>
</div>

<hr>

<div class="row">
  <div class="col-sm-6">
    <h4>Larvicida 1:
      <button type="button" class="mb-xs mt-xs mr-xs btn btn-info" data-toggle="modal" data-target="#modal_calclarv">
        <i class="fa fa-tint"></i><sup><i class="fa fa-plus"></i></sup></button>
      </button></h4>
          <div class='col-md-4'>
            <div class='form-group'>
              <label class='control-label' for='larvicida_1_tipo'>Tipo:</label>
              <input type='text' class='form-control' id='larvicida_1_tipo' name='larvicida_1_tipo' placeholder='' value='<?=$d['larvicida_1_tipo'];?>' >
            </div>
          </div>

          <div class='col-md-4'>
              <div class='form-group'>
                <label class='control-label' for='larvicida_1_qtd'>Qtd:</label>
                <input type='text' class='form-control' id='larvicida_1_qtd' name='larvicida_1_qtd' placeholder='' value='<?=$d['larvicida_1_qtd'];?>' >
            </div>
          </div>

          <div class='col-md-4'>
            <div class='form-group'>
              <label class='control-label' for='larvicida_1_qtd_dep_trat'>Qtd. dep. trat:</label>
              <input type='text' class='form-control' id='larvicida_1_qtd_dep_trat' name='larvicida_1_qtd_dep_trat' placeholder='' value='<?=$d['larvicida_1_qtd_dep_trat'];?>' >
            </div>
          </div>
  </div>
  <div class="col-sm-6">
    <h4>Larvicida 2:</h4>
    <div class="row">

<div class='col-md-4'>
    <div class='form-group'>
                <label class='control-label' for='larvicida_2_tipo'>Tipo:</label>
                  <input type='text' class='form-control' id='larvicida_2_tipo' name='larvicida_2_tipo' placeholder='' value='<?=$d['larvicida_2_tipo'];?>' >
                </div>
              </div>
<div class='col-md-4'>
    <div class='form-group'>
                <label class='control-label' for='larvicida_2_qtd'>Qtd:</label>
                  <input type='text' class='form-control' id='larvicida_2_qtd' name='larvicida_2_qtd' placeholder='' value='<?=$d['larvicida_2_qtd'];?>' >
                </div>
              </div>
<div class='col-md-4'>
    <div class='form-group'>
                <label class='control-label' for='larvicida_2_qtd_dep_trat'>Qtd. dep. trat:</label>
                  <input type='text' class='form-control' id='larvicida_2_qtd_dep_trat' name='larvicida_2_qtd_dep_trat' placeholder='' value='<?=$d['larvicida_2_qtd_dep_trat'];?>' >
                </div>
              </div>
    </div>
  </div>
</div>

<hr>



<hr>
<h4>Tratamento perifocal adulticída:</h4>
<div class="row">

              <div class='col-md-2'>
                  <div class='form-group'>
                      <label class='control-label' for='trat_perifocal_tipo'>Tipo:</label>
                      <input type='text' class='form-control' id='trat_perifocal_tipo' name='trat_perifocal_tipo' placeholder='' value='<?=$d['trat_perifocal_tipo'];?>' >
                  </div>
              </div>

            <div class='col-md-2'>
              <div class='form-group'>
                <label class='control-label' for='trat_perifocal_qtd_cargas'>Qtd. cargas:</label>
                <input type='text' class='form-control' id='trat_perifocal_qtd_cargas' name='trat_perifocal_qtd_cargas' placeholder='' value='<?=$d['trat_perifocal_qtd_cargas'];?>' >
            </div>
          </div>
</div>

    <?
        if($acao == "Atualizar"){
          echo "<input type='hidden' id='id'   name='id' value='{$d['id']}'>";
        }
    ?>


            <input type='hidden' id='acao' name='acao' value='<?=$acao;?>' >
            <input type='hidden' id='id_ses_pncd_registro_diario' name='id_ses_pncd_registro_diario' value='<?=$d['id_ses_pncd_registro_diario'];?>' >


            <div class='row' style="margin-top:10px">
              <div class='col-sm-12 text-center'>
                 <a href="ses/mobile_rds_FORM.php?id=<?=$d['id_ses_pncd_registro_diario'];?>&tab=tab1">
                     <button type="button" class="btn btn-default">Voltar</button>
                 </a>
                  <button type="submit" class='btn btn-primary loading'><?=$acao;?></button>
              </div>
            </div>


</form>


          </div>
      </div>
</div>


<div class="modal fade" id="modal_help" tabindex="-1" role="dialog" aria-labelledby="modal_help" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Informações gerais</h5>
      </div>

      <div class="modal-body">
            <h4><b><i>Tipos de depósitos:</i></b></h4>
              <table class="table table-striped table-condensed text-center">
                <thead>
                  <tr>
                      <th class="text-center">Tipo</th><th class="text-center">Descrição</th>
                  </tr>
                </thead>
                <tbody>
                  <tr><td><h5><b>A1</b></h5></td><td>Caixa d'água</td></tr>
                  <tr><td><h5><b>A2</b></h5></td><td>Outros depósitos de armazenamento de água (baixo)</td></tr>
                  <tr><td><h5><b>D1</b></h5></td><td>Pneus e outros materiais rodantes</td></tr>
                  <tr><td><h5><b>D2</b></h5></td><td>Lixo (recipientes plásticos, latas), sucatas, entulhos</td></tr>
                  <tr><td><h5><b>B</b></h5></td><td>Pequeno depósito móveis</td></tr>
                </tbody>
            </table>
            <h4><b><i>Checklist de dengue em residência:</i></b></h4>
              <table class="table table-striped table-condensed text-center">
                <thead>
                  <tr>
                      <th class="text-center">Item</th><th class="text-center">Vistoria</th>
                  </tr>
                </thead>
                <tbody>
                  <tr><td><b>Ralos e caixas de passagem</b></td><td>Limpos, sem água e com tela</td></tr>
                  <tr><td><b>Pratos, vasos e bromélias</b></td><td>Sem água acumulada</td></tr>
                  <tr><td><b>Calhas</b></td><td>Limpas</td></tr>
                  <tr><td><b>Caixas d'água</b></td><td>Tampadas, tela na saída do ladrão</td></tr>
                  <tr><td><b>Lonas de coberturas</b></td><td>Esticadas e sem acumulo de água</td></tr>
                  <tr><td><b>Piscinas e fontes</b></td><td>Tratadas e limpa</td></tr>
                  <tr><td><b>Pneus</b></td><td>Em lugar coberto e sem água</td></tr>
                  <tr><td><b>Entulhos, telhas, tijolos</b></td><td>Relato de presença de escorpião</td></tr>
                  <tr><td><b>Larvas em algum tipo de depósito</b></td><td>Relatar na ocorrência para os ACE</td></tr>
                </tbody>
            </table>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="modal_calclarv" tabindex="-1" role="dialog" aria-labelledby="modal_calclarv" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Cálculo para aplicação de larvicida:</h5>
      </div>

      <div class="modal-body">
        <div class="row">
        <div class="col-md-12">
          <div class="alert alert-warning">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <strong>ATENÇÃO:</strong> Todas as medidas devem ser em centimetros.
                </div>
        </div>
        </div>

            <div class="row">
            <div class="col-md-12">
            							<div class="panel-group" id="accordion2">
            								<div class="panel panel-accordion panel-accordion-primary">
            									<div class="panel-heading">
            										<h4 class="panel-title">
            											<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse2One" ajax="no_ajax">
            												<i class="fa fa-cubes"></i> Depósitos cúbicos
            											</a>
            										</h4>
            									</div>
            									<div id="collapse2One" class="accordion-body collapse">
            										<div class="panel-body">
                                  <div class="row">
                                    <div class="col-sm-12 text-center">
                                        <img src="assets/images/dengue_img_00.jpg">
                                        <input type="number" name="box_c" id="box_c" class="form-control box" placeholder="Comprimento"><br>
                                        <input type="number" name="box_l" id="box_l" class="form-control box" placeholder="Largura"><br>
                                        <input type="number" name="box_h" id="box_h" class="form-control box" placeholder="Altura">
                                    </div>
                                  </div>
                                  <div class="row" style="margin-top:10px">
                                    <div class="col-sm-12 text-center">
                                        <h4 id="box_result"><small><sup>[entre com os valores para o cálculo de volume]</sup></small></h4>
                                    </div>
                                  </div>
            										</div>
            									</div>
            								</div>
            								<div class="panel panel-accordion panel-accordion-primary">
            									<div class="panel-heading">
            										<h4 class="panel-title">
            											<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse2Two" ajax="no_ajax">
            												<i class="fa fa-database"></i> Depósitos cilindricos
            											</a>
            										</h4>
            									</div>
            									<div id="collapse2Two" class="accordion-body collapse">
            										<div class="panel-body">
                                  <div class="row">
                                    <div class="col-sm-12 text-center">
                                        <img src="assets/images/dengue_img_01.jpg">
                                        <input type="number" name="cil_d" id="cil_d" class="form-control cil" placeholder="Diametro"><br>
                                        <input type="number" name="cil_h" id="cil_h" class="form-control cil" placeholder="Altura">
                                    </div>
                                  </div>
                                  <div class="row" style="margin-top:10px">
                                    <div class="col-sm-12 text-center">
                                        <h4 id="cil_result"><small><sup>[entre com os valores para o cálculo de volume]</sup></small></h4>
                                    </div>
                                  </div>
            										</div>
            									</div>
            								</div>
            								<div class="panel panel-accordion panel-accordion-primary">
            									<div class="panel-heading">
            										<h4 class="panel-title">
            											<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse2Three" ajax="no_ajax">
            												<i class="fa fa-eject"></i> Depósitos triangulares
            											</a>
            										</h4>
            									</div>
            									<div id="collapse2Three" class="accordion-body collapse">
            										<div class="panel-body">
                                  <div class="row">
                                    <div class="col-sm-12 text-center">
                                        <img src="assets/images/dengue_img_02.jpg">
                                        <input type="number" name="tri_b" id="tri_b" class="form-control tri" placeholder="Base"><br>
                                        <input type="number" name="tri_l" id="tri_l" class="form-control tri" placeholder="Largura"><br>
                                        <input type="number" name="tri_h" id="tri_h" class="form-control tri" placeholder="Altura">
                                    </div>
                                  </div>
                                  <div class="row" style="margin-top:10px">
                                    <div class="col-sm-12 text-center">
                                        <h4 id="tri_result"><small><sup>[entre com os valores para o cálculo de volume]</sup></small></h4>
                                    </div>
                                  </div>
            										</div>
            									</div>
            								</div>
            							</div>
            						</div>
                      </div>






      </div>
    </div>
  </div>
</div>




  </section><!--  <section class="panel box_shadow">-->
</section>


<script>

$(".box").keyup(function(){
  var c = ($("#box_c").val())/10;
  var l = ($("#box_l").val())/10;
  var h = ($("#box_h").val())/10;
  var vol = c * l * h;
  var col = Math.ceil(vol/50);
  var colgrande = 0;
  if(col >= 10)
  {
    colgrande = Math.floor(col/10);
    col = col - (colgrande*10);
  }
  var str = "<table class='table table-condensed table-striped'><tr><td><small>Volume:</small></td><td><b>"+vol+"</b> litros</td></tr><tr><td><small>Colher de 1g:</small></td><td><b>"+colgrande+"</b></td></tr><tr><td><small>Colher 0,1g:</small></td><td><b>"+col+"</b></td></tr></table>";
  $("#box_result").html(str);
  //$("#box_result").html(Math.ceil(vol/50)+"<br>Resultado:<h3><b>"+vol+"</b> litros, <small>Utilizar <b>"+col+" </b>colher(es) (0,1g) e <b>"+colgrande+"</b> colher(es) (1,0g)</small></h3>");
});
$(".cil").keyup(function(){
  var d = $("#cil_d").val()/10;
  var h = $("#cil_h").val()/10;
  var vol = 0.8*(d * d)*h;
  //var vol = 3.14*((d/2)*(d/2))*h;
  //$("#cil_result").html("Resultado:<h3><b>"+vol+"</b> litros</h3>");
  var col = Math.ceil(vol/50);
  var colgrande = 0;
  if(col >= 10)
  {
    colgrande = Math.floor(col/10);
    col = col - (colgrande*10);
  }
  var str = "<table class='table table-condensed table-striped'><tr><td><small>Volume:</small></td><td><b>"+vol+"</b> litros</td></tr><tr><td><small>Colher de 1g:</small></td><td><b>"+colgrande+"</b></td></tr><tr><td><small>Colher 0,1g:</small></td><td><b>"+col+"</b></td></tr></table>";
  $("#cil_result").html(str);
});
$(".tri").keyup(function(){
  var b = $("#tri_b").val()/10;
  var l = $("#tri_l").val()/10;
  var h = $("#tri_h").val()/10;
  var vol = (b * l * h)/2;
  //$("#tri_result").html("Resultado:<h3><b>"+vol+"</b> litros</h3>");
  var col = Math.ceil(vol/50);
  var colgrande = 0;
  if(col >= 10)
  {
    colgrande = Math.floor(col/10);
    col = col - (colgrande*10);
  }
  var str = "<table class='table table-condensed table-striped'><tr><td><small>Volume:</small></td><td><b>"+vol+"</b> litros</td></tr><tr><td><small>Colher de 1g:</small></td><td><b>"+colgrande+"</b></td></tr><tr><td><small>Colher 0,1g:</small></td><td><b>"+col+"</b></td></tr></table>";
  $("#tri_result").html(str);
});


$("#hora_entrada").mask("00:00");
$('.select2').select2();
$(".loading").click(function(){ $(this).html("<i class=\"fa fa-spinner fa-spin\"></i> Aguarde");});
$(".loading2").click(function(){ $(this).addClass("disabled").html("<i class=\"fa fa-spinner fa-spin\"></i>");});
</script>
