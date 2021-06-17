<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");
  $agora = now();

  logger("Acesso","GSEC - CALLCENTER", "Callcenter - Visualização geral");

   if(isset($_POST) && count($_POST))
   {
      if($_POST['id_company']=='todos')
      {
         $id_company   = "SELECT id FROM {$schema}company WHERE id_father = '{$_SESSION['id_company_father']}'";
         $name_company = $_POST['name_company'];
      }else{
         $id_company   = $_POST['id_company'];
         $name_company = $_POST['name_company'];
      }
   }else{
      $id_company   = $_SESSION['id_company'];
      $name_company = $_SESSION['company_name'];
   }

  $filtroativo = ($_GET['filtro_ativo']=="f"?"f":"t");

  $sql = "SELECT
  		      (SELECT count(*) FROM {$schema}gsec_files F WHERE F.id_callcenter = C.id) AS qtd_foto,
            C.id, C.status, C.date_added, C.coords,
            T.type, T.request,
            CO.name AS company_name, CO.acron as company_acron,
            S.name as street, C.address_num, C.address_complement, C.address_reference,
            N.neighborhood,
            CI.name as citizen, CI.rg, CI.cpf, CI.cnpj, CI.email, CI.phone1
                 FROM {$schema}gsec_callcenter C
            LEFT JOIN {$schema}gsec_citizen CI ON CI.id = C.id_citizen
            LEFT JOIN {$schema}streets S ON S.id = C.id_address
            LEFT JOIN {$schema}neighborhood N ON N.id = C.id_neighborhood
            LEFT JOIN {$schema}company CO ON CO.id = C.id_company
            LEFT JOIN {$schema}gsec_request_type T ON T.id = id_subject
            WHERE C.id_company in ({$id_company})
              AND C.active =  '{$filtroativo}'
            ORDER BY C.date_added DESC";

  $res = pg_query($sql)or die("<div class='text-center'>SQL error ".__LINE__."<br>SQL: ".$sql."</div>");
  while($d = pg_fetch_assoc($res))
  {
     if($d['coords']!=""){
        $vet[$d['id']]['coords']     = $d['coords'];
        $vet[$d['id']]['id']         = $d['id'];
        $vet[$d['id']]['subject']    = $d['type'].":".$d['request'];
        $vet[$d['id']]['date_added'] = $d['date_added'];

     }

     /*

    [qtd_foto] => 0
    [id] => 639
    [status] => Em análise
    [date_added] => 2021-04-29 15:52:04
    [coords] => -26.32718,-48.90676
    [type] => Serviços gerais
    [request] => Roçada e Capina
    [company_name] => Subprefeitura Sudoeste
    [company_acron] => SPSO
    [street] => AQUILINO RODOLFO BUZZI
    [address_num] =>
    [address_complement] =>
    [address_reference] => Em frente ao Colégio
    [neighborhood] => MORRO DO MEIO
    [citizen] => Eliezer Alves
    [rg] =>
    [cpf] => 913.807.159-20
    [cnpj] =>
    [email] => 91380715920
    [phone1] => (47) 9 9686-8185
     */
  }
  //print_r_pre($vet);

?>
<style>
#myMap{
min-height: 700px;

/*height: calc(100% - 10px) !important;*/
}
</style>
<section role="main" class="content-body">
  <header class="page-header">
    <h2>Mapa da Reginal</h2>
    <div class="right-wrapper pull-right" style='margin-right:15px;'>
      <ol class="breadcrumbs">
        <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
        <li><span class='#'>Mapa da Reginal</span></li>
      </ol>
    </div>
  </header>

		<section class="panel box_shadow">
				<header class="panel-heading" style="height:70px">
               <div style="margin-top:-10px">
               <h4>
                     <b><?=$name_company;?></b>
                     <br><small><?=(isset($vet)?"<b>".count($vet)."</b> eventos em aberto.":"<span class='text-muted'><i>Nenhum evento em aberto.</i></span>");?></small>
               </h4>
               </div>
                <div class="panel-actions" style="margin-top:5px">
                   <?
                     echo " <button type='button' class='btn btn-info' data-toggle='modal' data-target='#modalFiltro'><i class='fa fa-search'></i></button>";
                   ?>
                </div>
             </div>
         </header>

				<div class="panel-body">
            <div class="row">
               <div class="col-md-12">
                  <?

                  ?>
                     <div id="myMap" style='position:relative;width:100%;'></div>
               </div>
            </div>
         </div>
      </section>


</section>
<!-- Modal FILTROS -->
<div class="modal fade"  id="modalFiltro" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
   <div class="modal-content">
      <div class="modal-header">
       <h5 class="modal-title" id="exampleModalLabel">Filtros de pesquisa:</h5>
       <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-20px">
          <span aria-hidden="true">&times;</span>
       </button>
      </div>
      <form id="form_filtro" action="gsec/mapa_reginal.php" method="post">
      <div class="modal-body">
         <select class="form-control select2" id="id_company" name="id_company">
            <?
               if(check_perm("9_31")){
                 echo "<option value='todos'>TODOS OS ATENDIMENTOS ABERTOS</option>";

                 echo "<optgroup label='Setores'>";
                 $sql = "SELECT id, name, acron, id_father
                         FROM {$schema}company
                         WHERE active = 't' AND id_father = '{$_SESSION['id_company_father']}'
                         ORDER BY name ASC";
                 $res = pg_query($sql)or die();
                 while($setores = pg_fetch_assoc($res)){
                    if($setores['id']==$_SESSION['id_company']){ $sel = "selected"; }else{ $sel=""; }
                    echo "<option value='{$setores['id']}' {$sel}>{$setores['name']}</option>";
                 }
                 echo "</optgroup>";
              }else{
                 echo "<option value='{$_SESSION['id_company']}'>{$_SESSION['company_name']}</option>";
              }
            ?>
         </select>
         <input type="hidden" name="name_company" id="name_company" value='' />
      </div>
      <div class="modal-footer">
       <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
       <button type="button" class="btn btn-primary" id="bt_submit">Filtrar</button>
      </div>
      </form>
   </div>
  </div>
</div>
<script>

var defaultColor   = 'blue';
var hoverColor     = 'red';
var mouseDownColor = 'purple';
var map;
var pin;

$('.select2').select2({ dropdownParent: $('#modalFiltro')});
$("#bt_submit").click(function(){
    $('#modalFiltro').modal('hide');
    $('body').removeClass('modal-open');
    $('.modal-backdrop').remove();
    $("#name_company").val($("#id_company option:selected").text());
    $("#form_filtro").submit();
});


    function GetMap() {
         map = new Microsoft.Maps.Map('#myMap', {
            credentials: 'Ag2oAO30HR3VWnlUOEllUDh6Va6GBmboNrDqG1KZ5fJAt4105Zgnr1uQUqa6DhzX',
            center: new Microsoft.Maps.Location(-26.301033,-48.840862),
            mapTypeId: Microsoft.Maps.MapTypeId.street,
            setLang: "pt-BR",
            zoom: 13
         });
         infobox = new Microsoft.Maps.Infobox(map.getCenter(), {visible: false});
         infobox.setMap(map);

         <?
               if(isset($vet))
               {
                  foreach ($vet as $protocolo => $dados) {

                     $aux = substr(str_replace("-","",$dados['date_added']),0,6);
                     $numprotocolo = $aux.".".str_pad($protocolo,4,"0",STR_PAD_LEFT);

                     echo "var pin = new Microsoft.Maps.Pushpin(new Microsoft.Maps.Location(".$dados['coords']."),{title:'Protocolo: {$dados['id']}', subTitle: '{$dados['subject']}'}); map.entities.push(pin);";
//                      echo "var pin = new Microsoft.Maps.Pushpin(new Microsoft.Maps.Location(".$dados['coords']."));";
                      echo "pin.metadata = {
                                title: 'Protocolo: ".$numprotocolo."',
                                description: '".$dados['subject']."<br><small>".formataData($dados['date_added'],1)." (".humanTiming($dados['date_added']).")</small>'
                            };";

                      echo "Microsoft.Maps.Events.addHandler(pin, 'click', pushpinClicked);";
                      echo "map.entities.push(pin);";
                  }
               }
         ?>
    }
    function pushpinClicked(e) {
      //Make sure the infobox has metadata to display.
      if (e.target.metadata) {
           //Set the infobox options with the metadata of the pushpin.
           var newHeight = jQuery(".infobox-info").height();
           //alert("Tamanho: "+newHeight);
           infobox.setOptions({
               location: e.target.getLocation(),
               title: e.target.metadata.title,
               description: e.target.metadata.description,
               visible: true,
               height: 100,
           });
      }
  }

</script>
<script type='text/javascript' src='https://www.bing.com/api/maps/mapcontrol?callback=GetMap' async defer></script>


<?
function humanTiming($data)
{

    $time = strtotime($data);
    $time = time() - $time;
    $time = ($time<1)? 1 : $time;
    $tokens = array (
        31536000 => 'ano',
        2592000 => 'mes',
        604800 => 'semana',
        86400 => 'dia',
        3600 => 'hora',
        60 => 'min',
        1 => 'seg'
    );

    foreach ($tokens as $unit => $text) {
        if ($time < $unit) continue;
        $numberOfUnits = floor($time / $unit);
        if($text=="mes" && $numberOfUnits>1){ $ext = "es"; }else{ $ext = "s"; }
        return $numberOfUnits.' '.$text.(($numberOfUnits>1)?$ext:'');
    }

}
?>
