<?
  session_start();
  require_once("../../libs/php/funcoes.php");
  require_once("../../libs/php/conn.php");
?>


    <div class="row">
          <div class="col-sm-12">
                <div class="tabs">
                      <ul class="nav nav-tabs text-right tabs-primary">
                        <li class="active">
                          <a href="#s05_p1" data-toggle="tab" ajax="false">Providência</a>
                        </li>
                        <li>
                          <a href="#s05_p2" data-toggle="tab" ajax="false"><i class="fa fa-plus"></i> Infos</a>
                        </li>
                      </ul>

                      <form id="form_providencias" name="form_providencias" action="oct/FORM_providenciasXXX_sql.php" method="post">
                      <div class="tab-content">
                                      <div id="s05_p1" class="tab-pane active">
                                                        <div class="row">
                                                             <div class="col-sm-12">
                                                               <div class="form-group">
                                                                         <label class="control-label">Providência:</label>
                                                                         <select id="id_providence_type" name="id_providence_type" class="form-control select2">
                                                                            <?
                                                                                  $sql = "SELECT * FROM {$schema}oct_providence ORDER BY area, providence ASC";
                                                                                  $res = pg_query($sql)or die("Erro ".__LINE__);
                                                                                  while($p = pg_fetch_assoc($res))
                                                                                  {
                                                                                    $provs[$p['area']][] = $p;
                                                                                  }
                                                                                  foreach ($provs as $area => $prov)
                                                                                  {
                                                                                    echo "<optgroup label='".$area."'>";
                                                                                      for($i=0;$i<count($prov);$i++)
                                                                                      {
                                                                                          $sel = "";
                                                                                          if(isset($dados['id_providence']))
                                                                                          {
                                                                                              if($dados['id_providence']==$prov[$i]["id"]){$sel="selected";}else{$sel="";}
                                                                                          }else{
                                                                                              if($prov[$i]["id"]==26){$sel="selected";}else{$sel="";}
                                                                                          }
                                                                                          echo "<option value='".$prov[$i]["id"]."' $sel>".$prov[$i]["providence"]."</option>";
                                                                                      }
                                                                                    echo "</optgroup>";
                                                                                  }
                                                                            ?>
                                                                         </select>

                                                              </div>
                                                            </div>
                                                         </div>

                                                         <div class="row">
                                                               <div class="col-sm-12">
                                                                 <div class="form-group">
                                                                 <label class="control-label">Observações:</label>
                                                                     <textarea name="description" placeholder="Descrição detalhada providência tomada." rows="7" class="form-control"><?=$dadosprov['observation'];?></textarea>
                                                                </div>
                                                              </div>
                                                         </div>

                                                         <div class="row">


                                                             <div class="col-sm-6">
                                                               <div class="form-group">
                                                                   <label class="control-label">Data:</label>
                                                                   <input onclick="$(this).val('');" type="text" id="data" name="data" value="<?=($dados['opened_date']!=""?substr(formataData($dadosprov['opened_date'],1),0,10):$agora['data']);?>" class="form-control campo_data"/>
                                                                </div>
                                                            </div>

                                                            <div class="col-sm-6">
                                                              <div class="form-group">
                                                                  <label class="control-label">Hora:</label>
                                                                  <input onclick="$(this).val('');" type="time" id="hora" name="hora" value="<?=($dados['opened_date']!=""?substr(formataData($dadosprov['opened_date'],1),11,5):$agora['hm']);?>" class="form-control campo_hora"/>
                                                               </div>
                                                           </div>


                                                        </div>
                                      </div>

                                      <div id="s05_p2" class="tab-pane">02</div>
                      </div>
                      </form>
              </div>
    </div>
</div>
