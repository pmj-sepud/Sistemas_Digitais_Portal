<div class="row">
  <div class="col-md-12">


      <form action="ses/rds_FORM_tab0_SQL.php" method="post">
            <div class='form-group'>
            <label class='col-md-2 control-label' for='atividade'>Atividade:</label>
            <div class='col-md-4'>
              <select class='form-control select2' id='atividade' name='atividade'>
                  <option value="1 - RA - Revisão da área"                       <?=($d['atividade']=="1 - RA - Revisão da área"?"selected":"");?>                      >1 - RA - Revisão da área</option>
                  <option value="2 - Li + T - Levantamento de índice+Tratamento" <?=($d['atividade']=="2 - Li + T - Levantamento de índice+Tratamento"?"selected":"");?>>2 - Li + T - Levantamento de índice+Tratamento</option>
                  <option value="3 - PE - Ponto estratégico"                     <?=($d['atividade']=="3 - PE - Ponto estratégico"?"selected":"");?>                    >3 - PE - Ponto estratégico</option>
                  <option value="4 - T - Tratamento"                             <?=($d['atividade']=="4 - T - Tratamento"?"selected":"");?>                            >4 - T - Tratamento</option>
                  <option value="5 - DF - Delimitação de foco"                   <?=($d['atividade']=="5 - DF - Delimitação de foco"?"selected":"");?>                  >5 - DF - Delimitação de foco</option>
                  <option value="6 - PVE - Pesquisa vetorial especial"           <?=($d['atividade']=="6 - PVE - Pesquisa vetorial especial"?"selected":"");?>          >6 - PVE - Pesquisa vetorial especial</option>
                  <option value="7 - ID - Investigação de denúncia"              <?=($d['atividade']=="7 - ID - Investigação de denúncia"?"selected":"");?>             >7 - ID - Investigação de denúncia</option>
                  <option value="8 - BT - Bloqueio de transmissão"               <?=($d['atividade']=="8 - BT - Bloqueio de transmissão"?"selected":"");?>              >8 - BT - Bloqueio de transmissão</option>
              </select>
            </div>

            <label class='col-md-2 control-label' for='data_atividade'>Data:</label>
            <div class='col-md-4'>
              <input type='date' class='form-control' id='data_atividade' name='data_atividade' placeholder='' value='<?=$agora['datasrv'];?>'>
            </div>
            </div>


            <div class='form-group'>
                <label class='col-md-2 control-label' for='codigo_e_nome_localidade'>Localidade:</label>
                <div class='col-md-4'>
                  <select class='form-control select2' id='codigo_e_nome_localidade' name='codigo_e_nome_localidade'>
                  <?
                      $sql = "SELECT * FROM {$schema}neighborhood ORDER BY neighborhood ASC";
                      $res = pg_query($sql);
                      while($n = pg_fetch_assoc($res))
                      {
                        echo "<option value='{$n['id']}' ".($d['codigo_e_nome_localidade'] == $n['id']?"selected":"").">{$n['neighborhood']}</option>";
                      }
                  ?>
                  </select>
                </div>

                <label class='col-md-2 control-label' for='categoria_localidade'>Categoria:</label>
                <div class='col-md-4'>
                  <select  class='form-control' id='categoria_localidade' name='categoria_localidade'>
                      <option value="Bairro">Bairro</option>
                  </select>
                </div>
            </div>

            <div class='form-group'>
            <label class='col-md-2 control-label' for='zona'>Zona:</label>
            <div class='col-md-4'>
              <select  class='form-control' id='zona' name='zona'>
                  <option value="Urbana" <?=($d['zona'] == 'Urbana'?"selected":"");?>>Urbana</option>
                  <option value="Rural"  <?=($d['zona'] == 'Rural'?"selected":"");?> >Rural</option>
              </select>
            </div>

            <label class='col-md-2 control-label' for='tipo'>Tipo:</label>
            <div class='col-md-4'>
              <select class='form-control' id='tipo' name='tipo'>
                  <option value="1 - Sede"   <?=($d['tipo'] == '1 - Sede'?"selected":"");?>>1 - Sede</option>
                  <option value="2 - Outros" <?=($d['tipo'] == '2 - Outros'?"selected":"");?>>2 - Outros</option>
              </select>
            </div>
            </div>






            <? if($acao == "Atualizar"){ ?>
                    <div class='form-group'>
                              <label class='col-md-2 control-label' for='ciclo_ano'>Ciclo/Ano:</label>
                                  <div class='col-md-4'>
                                    <input type='text' class='form-control' id='ciclo_ano' name='ciclo_ano' placeholder='' value='<?=$d['ciclo_ano'];?>'>
                                  </div>
                              <label class='col-md-2 control-label' for='concluido'>Concluído:</label>
                                  <div class='col-md-4'>
                                    <select class='form-control' id='concluido' name='concluido'>
                                        <option value="f" <?=($d['concluido'] == 'f'?"selected":"");?>>Não</option>
                                        <option value="t" <?=($d['concluido'] == 't'?"selected":"");?>>Sim</option>
                                    </select>
                                  </div>
                    </div>

                    <input type="hidden" name="id" id="id" value="<?=$d['id'];?>" />
            <? }else{

              echo "<div class='form-group'>
                    <label class='col-md-2 control-label' for='ciclo_ano'>Ciclo/Ano:</label>
                    <div class='col-md-2'>
                      <input type='text' class='form-control' id='ciclo_ano' name='ciclo_ano' placeholder='' value='".$d['ciclo_ano']."'>
                    </div>
                    </div>";

                  echo "<input type='hidden'  id='concluido' name='concluido'  value='f'>";
                  echo "<input type='hidden'  id='municipio' name='municipio'  value='Joinville'>";
                  echo "<input type='hidden'  id='id_user'   name='id_user'    value='{$_SESSION['id']}'>";
               } ?>

               <div class='form-group'>
                 <label class='col-md-2 control-label' for='observacao'>Observações:</label>
                 <div class='col-md-10'>
                   <textarea rows='3' class='form-control' id='observacao' name='observacao'><?=$d['observacao'];?></textarea>
                 </div>
               </div>


            <input type='hidden'  id='acao' name='acao'  value='<?=$acao;?>'>


            <div class='form-group'>
                <div class='col-md-12 text-center'>
                    <button type="submit" class="btn btn-primary loading"><?=$acao;?></button>
                </div>
            </div>

    </form>

  </div>
</div>
