<?
  session_start();
  require_once("../../libs/php/funcoes.php");
  require_once("../../libs/php/conn.php");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");
?>
<div class="row">
   <div class="col-md-12">
        <div class="tabs">
          <ul class="nav nav-tabs text-right tabs-primary">
            <li class="active">
              <a href="#s02_p1" data-toggle="tab" ajax="false">Agenda</a>
            </li>
            <li>
              <a href="#s02_p2" data-toggle="tab" ajax="false">Logradouro</a>
            </li>
            <li>
              <a href="#s02_p3" data-toggle="tab" ajax="false">Mapa</a>
            </li>
          </ul>
          <div class="tab-content">
            <div id="s02_p1" class="tab-pane active">

              <script>
               var livro_de_endereco = [];
              <?
                   //$sql = "SELECT * FROM ".$schema."oct_addressbook WHERE id_company = '".$_SESSION['id_company']."' ORDER BY name ASC";

                   $sql = "SELECT
                             S.name as street_name, A.*
                           FROM
                             {$schema}oct_addressbook A
                           LEFT JOIN {$schema}streets S ON S.id = A.id_street
                           --WHERE A.id_company = '{$_SESSION['id_company']}'
                           WHERE active = 't'
                           ORDER BY
                           A.NAME ASC";

                    $res = pg_query($sql)or die("Erro ".__LINE__);
                    if(pg_num_rows($res))
                    {
                        while($d = pg_fetch_assoc($res))
                        {
                          $vet_livro_end[$d['neighborhood']][] = $d;

                          if($d['id_street']!=""){
                            //echo "livro_de_endereco.push({id_street:'".$d['id_street']."', street_name: '".$d['street_name']."'});";
                            echo "livro_de_endereco[".$d['id']."] = {id_street:'".$d['id_street']."', street_name: '".$d['street_name']."', num_ref: '".$d['num_ref']."', geoposition: '".$d['geoposition']."'};";
                          }
                        }
                    }
              ?>
              </script>
                          <div class="row">
                                <div class="col-md-12">
                                      <div class="form-group">
                                      <label class="control-label">Agenda de Endereço:</label>
                                      <select id="id_addressbook" name="id_addressbook" class="form-control select2 changefield">
                                         <?

                                                 if(isset($vet_livro_end) && count($vet_livro_end))
                                                 {
                                                    echo "<option value=''></option>";
                                                    foreach ($vet_livro_end as $bairro => $livro_end) {
                                                        echo "<optgroup label='".$bairro."'>";
                                                        for($i=0;$i<count($livro_end);$i++)
                                                        {
                                                            if($dados['id_addressbook']==$livro_end[$i]["id"]){ $sel = "selected";}else{$sel="";}
                                                            echo "<option value='".$livro_end[$i]["id"]."' ".$sel.">".$livro_end[$i]["name"]."</option>";
                                                        }
                                                        echo "</optgroup>";
                                                    }
                                               }
                                         ?>
                                      </select>
                                     </div>
                                </div>
                          </div>

            </div>
            <div id="s02_p2" class="tab-pane">

                          <div class="row">
                            <div class="col-sm-12">
                              <div class="form-group">
                              <label class="control-label">Logradouro:</label>
                                  <select id="id_street" name="id_street" class="form-control select2 changefield input-lg" style="width: 100%; height:100%">
                                    <option value="">- - -</option>
                                    <?
                                      $sql = "SELECT * FROM {$schema}streets ORDER BY name ASC";
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

                          <div class="row">
                                <div class="col-sm-6">
                                  <div class="form-group">
                                  <label class="control-label">Numero:</label>
                                      <input type="number" id="street_number" name="street_number" class="form-control changefield input-lg" value="<?=$dados['street_number'];?>">
                                 </div>
                                </div>

                                <div class="col-sm-6">
                                        <div class="form-group">
                                        <label class="control-label">Região:</label>
                                        <select id="region" name="region" class="form-control changefield">
                                          <option value="">- - -</option>
                                          <option value="Norte"    <?=($dados['region']=="Norte"?"selected":"");?>   >Norte</option>
                                          <option value="Sul"      <?=($dados['region']=="Sul"?"selected":"");?>     >Sul</option>
                                          <option value="Leste"    <?=($dados['region']=="Leste"?"selected":"");?>   >Leste</option>
                                          <option value="Oeste"    <?=($dados['region']=="Oeste"?"selected":"");?>   >Oeste</option>
                                          <option value="Nordeste" <?=($dados['region']=="Nordeste"?"selected":"");?>>Nordeste</option>
                                          <option value="Sudeste"  <?=($dados['region']=="Sudeste"?"selected":"");?> >Sudeste</option>
                                          <option value="Noroeste" <?=($dados['region']=="Noroeste"?"selected":"");?>>Noroeste</option>
                                          <option value="Sudoeste" <?=($dados['region']=="Sudoeste"?"selected":"");?>>Sudoeste</option>
                                        </select>
                                      </div>
                                </div>
                          </div>

                          <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="control-label">Complemento:</label>
                                        <input type="text" id="endereco_complemento" name="endereco_complemento" class="form-control changefield input-lg" value="<?=$dados['address_complement'];?>">
                                    </div>
                                </div>
                          </div>
            </div>


                      <div id="s02_p3" class="tab-pane">

                        <div class="row">
                          <div class="col-sm-12">
                                    <div class="form-group">
                                          <button id="geocode" type="button" class="btn btn-lg btn-block btn-primary disabled" style="width:100%"><i class="fa fa-map-marker"></i> Localizar no mapa</button>
                                      </div>
                          </div>
                        </div>


                                  <div class="row">
                                    <div class="col-sm-12">
                                      <div id="map" style="width:100%;height:350px;margin-top:15px"></div>
                                    </div>
                                  </div>
                                    <div class="row">
                                    <div class="col-sm-12">
                                      <div id="mapinfo" align="right" style="color:#EEEEEE;width:100%;margin:5px;">Debug</div>
                                    </div>
                                  </div>


                      </div>
          </div>
        </div>
      </div>
    </div>

<script>

$('#id_addressbook').on('select2:select', function (e) { $("#isok_02").removeClass("hidden"); $("#step_02").collapse('hide'); $("#step_03").collapse('shiow'); });
$('#id_street').on('select2:select', function (e) { $("#isok_02").removeClass("hidden");      });

<?
  if($dados['geoposition'] != "")
  {
    $zoommap = 16;
    $posicao = $dados['geoposition'];
  }else{
    $zoommap = 13;
    $posicao = "-26.301033,-48.840862";
  }
?>
zoommap 		= <?=$zoommap;?>;
var latlon  = new L.latLng(<?=$posicao;?>);
var map 		= new L.map('map', {attributionControl: false, zoomControl: true}).setView(latlon, zoommap);


L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1Ijoiam9uc25pZSIsImEiOiJjazdvdHg2cmQwY3NoM2VwOXg1YWdzNWN0In0.teqXLAHyVSJwut8hqAMONw', {
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
    maxZoom: 18,
    id: 'mapbox.streets',
    accessToken: 'pk.eyJ1Ijoiam9uc25pZSIsImEiOiJjazdvdHg2cmQwY3NoM2VwOXg1YWdzNWN0In0.teqXLAHyVSJwut8hqAMONw'
}).addTo(map);

map.on("dragend",   function (e) {$("#mapinfo").html("MAPA: dragend");	count=0;	});
map.on("dragstart", function (e) {});
map.on("drag",      function (e) {$("#mapinfo").html("MAPA: drag    ["+count+++"]");});
map.on("zoom",      function (e) {$("#mapinfo").html("MAPA: zoom"); map.flyTo(marco.getLatLng()); });

map.removeControl(map.zoomControl);
if (!L.Browser.mobile){  L.control.zoom({position:'bottomright'}).addTo(map);}

</script>
