<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");
  $agora = now();

  logger("Acesso","GSEC - CALLCENTER", "Cidadão - Visualização geral");

   $sql  = "SELECT
                C.*,
                CO.name as company_name, CO.acron as company_acron,
                U.name  as user_added_name
             FROM {$schema}gsec_citizen C
             LEFT JOIN {$schema}company CO  ON CO.id = C.id_company
             LEFT JOIN {$schema}users U     ON U.id  = C.id_user_added
             ORDER BY C.name ASC";
  $res = pg_query($sql)or die("<div class='text-center'>SQL error ".__LINE__."<br>SQL: ".$sql."</div>");
?>
<style>
.link:hover{
   cursor: pointer;
}
</style>
<section role="main" class="content-body">
      <header class="page-header">
       <h2>Cadastro do cidadão</h2>
       <div class="right-wrapper pull-right" style='margin-right:15px;'>
         <ol class="breadcrumbs">
           <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
           <li><span class='gsec/callcenter_index.php'>Central de atendimentos</span></li>
           <li><span class='#'>Cidadão</span></li>
         </ol>
       </div>
      </header>


      <section class="panel box_shadow">

               <header class="panel-heading" style="height:70px">
                  <div class="panel-actions" style="margin-top:5px">
                  <?
                  //echo "<a href='gsec/callcenter_FORM.php'><button type='button' class='btn btn-success'><i class='fa fa-laptop'></i><sup><i class='fa fa-plus'></i></sup> Novo atendimento</button></a>";
                  //echo " <a href='gsec/citizen.php'><button type='button' class='btn btn-primary'><i class='fa fa-user-plus'></i> Novo cidadão</button></a>";
                     echo " <a href='gsec/citizen_FORM.php'><button type='button' class='btn btn-success'><i class='fa fa-user-plus'></i> Novo cidadão</button></a>";
                  ?>
                  </div>
               </header>


               <div class="panel-body">
                  <div class="row">
                     <div class="col-md-12">
                        <?
                           if(pg_num_rows($res))
                           {
                              echo "<table class='table table-hover' id='tabela'>";
                              echo "<thead class='thead-light'><tr>";
                                 echo "<th>#</th>";
                                 echo "<th>Cidadão</th>";
                                 echo "<th>RG/CPF/CNPJ</th>";
                                 echo "<th>Órgão</th>";
                                 echo "<th>Cadastrado em</th>";
                              echo "</tr></thead>";
                              echo "<tbody>";
                              while($d = pg_fetch_assoc($res))
                              {
                                 unset($str);
                                 echo "<tr class='link' id='{$d['id']}'>";
                                       echo "<td class='text-muted'><small>{$d['id']}</small></td>";
                                       echo "<td>{$d['name']}</td>";
                                       echo "<td>";
                                             if($d['rg']   !=""){ $str[]="<sup class='text-muted'>RG:</sup>".str_replace(".","",str_replace("-","",$d['rg'])); }
                                             if($d['cpf']  !=""){ $str[]="<sup class='text-muted'>CPF:</sup>".str_replace(".","",str_replace("-","",$d['cpf'])); }
                                             if($d['cnpj'] !=""){ $str[]="<sup class='text-muted'>CNPJ:</sup>".str_replace(".","",str_replace("/","",$d['cnpj'])); }
                                             if(isset($str)){ echo implode(", ", $str); }
                                       echo "</td>";
                                       echo "<td>{$d['company_acron']}</td>";
                                       echo "<td>".formataData($d['date_added'],1);
                                       echo " <sup>(".humanTiming($d['date_added']).")</sup></td>";
                                 echo "</tr>";
                              }
                              echo "</tbody>";
                              echo "</table>";
                              /*
                              Array
(
 [id] => 1
 [name] => Jonathan Sniecikoski
 [phone1] => 991876457
 [phone2] =>
 [phone3] =>
 [id_residence_address] =>
 [num_residence_address] =>
 [complement_residence_address] =>
 [cpf] =>
 [rg] =>
 [date_added] => 2021-03-15 15:44:33
 [cnpj] =>
 [observations] =>
 [email] =>
)
                              */
                           }else{
                              echo "<div class='alert alert-warning'>Nenhum cidadão cadastrado.</div>";
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
   $('#wrap').load("gsec/citizen_FORM.php?id="+id);
})

$(document).ready( function () {
    $('#tabela').DataTable({
      mark: true,
      responsive: true,
      language: {
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
    "columnDefs": [ {
            "searchable": false,
            "orderable": false,
            "targets": 0
        } ],
        "order": [[ 1, 'asc' ]]

    });
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
