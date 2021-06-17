<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");
  $agora = now();

  logger("Acesso","GSEC - Estoque", "Produtos - Visualização geral");

   $sql  = "SELECT P.*, T.name as product_type,
                   C.name as company_name, C.acron AS company_acron,
                   CF.name as company_father_name, CF.acron AS company_father_acron
            FROM {$schema}gsec_stock_product P
            JOIN {$schema}gsec_stock_product_type T ON T.id = P.id_product_type
            LEFT JOIN {$schema}company C ON C.id = P.id_company
            LEFT JOIN {$schema}company CF ON CF.id = P.id_company_father
            ORDER BY P.name ASC";
  $res = pg_query($sql)or die("<div class='text-center'>SQL error ".__LINE__."<br>SQL: ".$sql."</div>");
?>
<style>
.link:hover{
   cursor: pointer;
}

</style>
<section role="main" class="content-body">
      <header class="page-header">
       <h2>Cadastros de materiais e equipamentos</h2>
       <div class="right-wrapper pull-right" style='margin-right:15px;'>
         <ol class="breadcrumbs">
           <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
           <li><span class='#'>Controle de estoque</span></li>
         </ol>
       </div>
      </header>


      <section class="panel box_shadow">

               <header class="panel-heading" style="height:70px">
                  <div class="panel-actions" style="margin-top:5px">
                  <?
                  echo "<a href='gsec/stock_products_FORM.php'><button type='button' class='btn btn-success'><i class='fa fa-clipboard'></i><sup><i class='fa fa-plus'></i></sup> Novo produto</button></a>";
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
                                 echo "<th>#</th>";
                                 echo "<th>Produto</th>";
                                 echo "<th>Descrição</th>";
                                 echo "<th>Métrica</th>";
                                 echo "<th>Mínimo Regulador</th>";
                                 echo "<th>Tipo</th>";
                                 echo "<th>Setor</th>";
                                 echo "<th>Órgão</th>";
                              echo "</tr></thead>";
                              echo "<tbody>";
                              while($d = pg_fetch_assoc($res))
                              {
                                 unset($str);
                                 echo "<tr class='link' id='{$d['id']}'>";
                                       echo "<td class='text-muted'><small>{$d['id']}</small></td>";
                                       echo "<td>{$d['name']}</td>";
                                       echo "<td>{$d['description']}</td>";
                                       echo "<td>{$d['unit']}</td>";
                                       echo "<td>{$d['min_count']}</td>";
                                       echo "<td>{$d['product_type']}</td>";
                                       echo "<td>{$d['company_name']}</td>";
                                       echo "<td>{$d['company_father_acron']}</td>";
                                 echo "</tr>";
                              }
                              echo "</tbody>";
                              echo "</table>";
                           }else{
                              echo "<div class='alert alert-warning'>Nenhum time de trabalho cadastrado cadastrado.</div>";
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
   $('#wrap').load("gsec/stock_products_FORM.php?id="+id);
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
