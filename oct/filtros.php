<?
//session_start();
//require_once("../libs/php/funcoes.php");
//require_once("../libs/php/conn.php");
?>

            <!-- ========================================================= -->

            <div class="row" style="margin-bottom:10px">
                <div class="form-group">
                    <label class="col-md-3 control-label" for="filtro_num_oc">Nº da ocorrência:</label>
                    <div class="col-md-8">
                        <input type="number" name="filtro_num_oc" id="filtro_num_oc" class="form-control">
                    </div>
                </div>
            </div>

            <div class="row" style="margin-bottom:10px">
                <div class="form-group">
                    <label class="col-md-3 control-label" for="filtro_num_oc">Nº do protocolo:</label>
                    <div class="col-md-8">
                        <input type="text" name="filtro_num_proto" id="filtro_num_proto" class="form-control">
                    </div>
                </div>
            </div>





              <div class="row" style="margin-bottom:10px">
              <div class="form-group">
              <label class="col-md-3 control-label">Logradouro:</label>
                  <div class="col-md-8">
                      <select id="filtro_id_street" name="filtro_id_street" class="form-control select2" style="width: 100%; height:100%;z-index:10000">
                        <option value="">- - -</option>
                        <?
                          $sql = "SELECT * FROM ".$schema."streets ORDER BY name ASC";
                          $res = pg_query($sql)or die();
                          while($s = pg_fetch_assoc($res))
                          {
                            if($dados["id_street"] == $s["id"]){ $sel = "selected";}else{$sel="";}
                            echo "<option value='".$s['id']."' ".$sel.">".$s['name']."</option>";
                          }
                        ?>
                      </select>
                  </div>
             </div>
            </div>

            <hr>
            <div class="row" style="margin-bottom:10px">
                <div class="form-group">
                    <label class="col-md-3 control-label" for="filtro_placaveiculo">Placa do veículo:</label>
                    <div class="col-md-8">
                        <input type="text" name="filtro_placaveiculo" id="filtro_placaveiculo" class="form-control">
                    </div>
                </div>
            </div>

<? /* if($_SESSION["id"]==1){ ?>

            <div class="row" style="margin-bottom:10px">
                <div class="form-group">
                    <label class="col-md-3 control-label" for="placaVeiculo">Tipo de ocorrência:</label>
                    <div class="col-md-8">
                      <select id="filtro_tipo_oc" name="filtro_tipo_oc" class="form-control">
                        <option value="">- - -</option>
                        <?
                          $sql = "SELECT * FROM sepud.oct_event_type ORDER BY name ASC";
                          $res = pg_query($conn_neogrid,$sql)or die("Error: ".__LINE__);
                          while($d = pg_fetch_assoc($res)){ $vet[$d['type']][] = $d;}
                          foreach($vet as $type => $d)
                          {
                            echo "<optgroup label='".$type."'>";
                              for($i = 0; $i < count($d); $i++)
                              {
                                echo "<option value='".$d[$i]['id']."' $sel>".$d[$i]['name']."</option>";
                              }
                            echo "</optgroup>";
                          }
                        ?>
                      </select>
                    </div>
                </div>
            </div>
<? } */ ?>
              <div class="row" style="margin-bottom:10px">
                <div class="col-sm-12">
                    <hr />Ou apenas:
                </div>
              </div>

              <div class="row" style="margin-bottom:10px">
                  <div class="form-group">
                      <label class="col-md-3 control-label" for="filtro_data">Data:</label>
                      <div class="col-md-8">
                          <input type="text" name="filtro_data" id="filtro_data" class="form-control">
                      </div>
                  </div>
              </div>



            <!-- ========================================================= -->
<script>
$('.select2').select2({
  dropdownParent: $('#modalFiltro'),
  language: {
        noResults: function() {
          return 'Nenhum resultado encontrado.';
        }
      }
});
</script>
