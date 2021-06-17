<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");
  $agora = now();

  logger("Acesso","GSEC - Estoque", "Controle de estoque do setor - Visualização geral");

   $sql  = "SELECT C.*,
            P.name, P.description, P.unit,
            T.name as type
            FROM {$schema}gsec_stock_company      C
            JOIN {$schema}gsec_stock_product      P ON P.id = C.id_product
            JOIN {$schema}gsec_stock_product_type T ON T.id = P.id_product_type
            WHERE C.id_company = '{$_SESSION['id_company']}'";
   $res = pg_query($sql)or die("<div class='text-center'>SQL error ".__LINE__."<br>SQL: ".$sql."</div>");

   $name_company = ($_POST['name_company']!=""?$_POST['name_company']:$_SESSION['company_name']);
?>
<style>
.link:hover{
   cursor: pointer;
}

</style>
<section role="main" class="content-body">
      <header class="page-header">
       <h2>Controle de estoque</h2>
       <div class="right-wrapper pull-right" style='margin-right:15px;'>
         <ol class="breadcrumbs">
           <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
           <li><span class='#'>Controle de estoque</span></li>
         </ol>
       </div>
      </header>


      <section class="panel box_shadow">

               <header class="panel-heading" style="height:70px">
                  <div style="margin-top:-10px">
                  <h4>
                        <b><?=$name_company;?></b>
                  </h4>
                  </div>
                  <div class="panel-actions" style="margin-top:5px">
                  <?
                  echo "<a href='gsec/stock_company_product.php'><button type='button' class='btn btn-success'><i class='fa fa-clipboard'></i><sup><i class='fa fa-plus'></i></sup> Novo produto</button></a>";
                  //echo " <a href='gsec/citizen.php'><button type='button' class='btn btn-primary'><i class='fa fa-user-plus'></i> Novo cidadão</button></a>";
                  //echo " <a href='gsec/workteam_FORM.php'><button type='button' class='btn btn-success'><i class='fa fa-users'></i><sup><i class='fa fa-plus'></i></sup> Novo time de trabalho</button></a>";
                  ?>
                  </div>
               </header>


               <div class="panel-body">
                  <div class="row">
                     <div class="col-md-12">
                        <?
                           if(isset($res) && pg_num_rows($res))
                           {
                              echo "<table class='table table-hover' id='tabela'>";
                              echo "<thead class='thead-light'><tr>";
                                 echo "<th width='10px'>#</th>";
                                 echo "<th>Produto</th>";
                                 echo "<th>Tipo</th>";
                                 echo "<th>Descrição</th>";
                                 echo "<th class='text-center' width='10px'>Mínimo</th>";
                                 echo "<th class='text-center' width='10px'>Atual</th>";
                                 echo "<th class='text-center' width='10px'>%</th>";

                              echo "</tr></thead>";
                              echo "<tbody>";
                              while($d = pg_fetch_assoc($res))
                              {

                                 if($d['min_count']>0)
                                 {
                                    $perc = ceil($d['actual_count']*100/$d['min_count'])-100;
                                    $icon = "<i class='fa fa-arrow-up'></i>";

                                        if($perc <=  0)              { $class='danger';  $icon = "<i class='fa fa-arrow-down'></i>"; }
                                    elseif($perc <= 30 && $perc > 20){ $class='primary'; $icon = "<i class='fa fa-arrow-right'></i>";}
                                    elseif($perc <= 20 && $perc > 10){ $class='warning'; $icon = "<i class='fa fa-arrow-right' style='transform: rotate(45deg);'></i>";}
                                    elseif($perc <= 10)              { $class='warning'; $icon = "<i class='fa fa-arrow-down'></i>";}
                                      else                           { $class=""; }
                                 }else{
                                    unset($icon, $perc, $class);
                                 }

                                 echo "<tr class='link {$class}' id='{$d['id']}'>";
                                       echo "<td class='text-muted' nowrap><small>{$d['id']}</small></td>";
                                       echo "<td>{$d['name']}</td>";
                                       echo "<td>{$d['type']}</td>";
                                       echo "<td>{$d['description']}</td>";

                                       if($d['min_count']>0)
                                       {
                                         echo "<td class='text-center' nowrap>{$d['min_count']} {$d['unit']}</td>";
                                         echo "<td class='text-center' nowrap>{$d['actual_count']} {$d['unit']}</td>";
                                         echo "<td class='text-center' nowrap>{$icon} {$perc} %</td>";
                                       }else {
                                         echo "<td class='text-center'>-</td><td class='text-center' nowrap>{$d['actual_count']} {$d['unit']}</td><td class='text-center'>-</td>";
                                       }
                                 echo "</tr>";
                              }
                              echo "</tbody>";
                              echo "</table>";
                           }else{
                              echo "<div class='alert alert-warning'>Nenhum produto cadastrado cadastrado.</div>";
                           }
                        ?>
                     </div>
                  </div>
               </div>

      </section>
</section>


<script>

$(".link").click(function(){
   var id = $(this).attr("id");
   $('#wrap').load("gsec/stock_company_product.php?id="+id);
})

$(document).ready( function () {
   var table = $('#tabela').DataTable({
      mark: true,
      responsive: true,
      stateSave: true,
      language:{
                 processing:     "Pesquisando...",
                 search:         "Pesquisar:",
                 lengthMenu:     "_MENU_ &nbsp;Registros por página.",
                 info:           "Mostrando _START_ a _END_ de um total de  _TOTAL_ registros.",
                 infoEmpty:      "0 registros encontrado.",
                 infoFiltered:   "(_MAX_ registros pesquisados)",
                 infoPostFix:    "",
                 loadingRecords: "Carregando registros...",
                 zeroRecords:    "Nenhum registro encontrado com essa característica.",
                 emptyTable:     "Nenhuma informação nesta tabela de dados.",
                 paginate: {
                     first:      "Primeiro",
                     previous:   "Anterior",
                     next:       "Próximo",
                     last:       "Último"
                 },
                 aria: {
                     sortAscending:  ": Ordem ascendente.",
                     sortDescending: ": Ordem decrescente."
                 }
               },
    "columnDefs": [{"searchable": false, "orderable": false,"targets": 0}],
    "order": [[ 1, 'asc' ]]

    });
 $('.dataTables_filter input').click(function () { $(this).val('');});

});



$('.select2').select2();
$(".loading").click(function(){ $(this).html("<i class=\"fa fa-spinner fa-spin\"></i> Aguarde");});
$(".loading3").click(function(){ $(this).html("<i class=\"fa fa-spinner fa-spin\"></i> Aguarde").addClass("disabled");});
</script>
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
