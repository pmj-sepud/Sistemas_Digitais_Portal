<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");
  $agora = now();

  logger("Acesso","GSEC - Estoque", "Controle de estoque do setor - Visualização do produto");


if($_GET['id']!="")
{
   $acao = "atualizar";

   $sql = "SELECT * FROM {$schema}gsec_stock_company WHERE id = '{$_GET['id']}'";
   $res = pg_query($sql)or die("SQL Error ".__LINE__);
   $d   = pg_fetch_assoc($res);


   $sql  = "SELECT P.*, T.name AS type
            FROM {$schema}gsec_stock_product P
            JOIN {$schema}gsec_stock_product_type T ON T.id = P.id_product_type
            WHERE P.id = '{$d['id_product']}'
            ORDER BY P.name ASC";
    $res = pg_query($sql)or die("SQL Error ".__LINE__);
    $produto = pg_fetch_assoc($res);

}else {
   $acao = "inserir";
   $sql  = "SELECT P.*, T.name AS type
            FROM {$schema}gsec_stock_product P
            JOIN {$schema}gsec_stock_product_type T ON T.id = P.id_product_type
            WHERE P.id_company = '{$_SESSION['id_company']}'
            AND P.id NOT IN (SELECT id_product FROM {$schema}gsec_stock_company WHERE id_company = '{$_SESSION['id_company']}')
            ORDER BY P.name ASC";
    $res = pg_query($sql)or die("SQL Error ".__LINE__);

    if(pg_num_rows($res))
    {
       while($aux = pg_fetch_assoc($res))
       {
           $produtos[$aux['type']][] = $aux;
       }
       ksort($produtos);
    }else{
      $sql  = "SELECT P.*, T.name AS type
                FROM {$schema}gsec_stock_product P
                JOIN {$schema}gsec_stock_product_type T ON T.id = P.id_product_type
                WHERE P.id_company_father = '{$_SESSION['id_company_father']}'
                AND P.id NOT IN (SELECT id_product FROM {$schema}gsec_stock_company WHERE id_company = '{$_SESSION['id_company']}')
                ORDER BY P.name ASC";
        $res = pg_query($sql)or die("SQL Error ".__LINE__);
        if(pg_num_rows($res))
        {
          while($aux = pg_fetch_assoc($res))
          {
              $produtos[$aux['type']][] = $aux;
          }
          ksort($produtos);
        }
   }

}
   $name_company = ($_POST['name_company']!=""?$_POST['name_company']:$_SESSION['company_name']);
?>
<style>
.link:hover{
   cursor: pointer;
}

</style>
<section role="main" class="content-body">
      <header class="page-header">
       <h2>Movimentação de produto</h2>
       <div class="right-wrapper pull-right" style='margin-right:15px;'>
         <ol class="breadcrumbs">
           <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
           <li><a href="gsec/stock_company.php">Controle de estoque</a></li>
           <li><span class='#'>Movimentação de produto</span></li>
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

                  //echo "<a href='gsec/stock_products_FORM.php'><button type='button' class='btn btn-success'><i class='fa fa-clipboard'></i><sup><i class='fa fa-plus'></i></sup> Novo produto</button></a>";
                  //echo " <a href='gsec/citizen.php'><button type='button' class='btn btn-primary'><i class='fa fa-user-plus'></i> Novo cidadão</button></a>";
                  echo " <a href='gsec/stock_company.php'><button type='button' class='btn btn-default'> Voltar</button></a> ";
                  echo "<a href='gsec/stock_company_product.php'><button type='button' class='btn btn-success'><i class='fa fa-clipboard'></i><sup><i class='fa fa-plus'></i></sup> Novo produto</button></a>";
                  ?>
                  </div>
               </header>


               <div class="panel-body">
                  <div class="row">
                     <div class="col-md-12">
                        <div class="panel-group" id="accordion2">
								<div class="panel panel-accordion panel-accordion-primary">
									<div class="panel-heading">
										<h4 class="panel-title">
											<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse2One" ajax="false">
												<i class="fa fa-cogs"></i> Produto
											</a>
										</h4>
									</div>
									<div id="collapse2One" class="accordion-body collapse">
										<div class="panel-body">
                                 <div class='row'>
                                    <form action="gsec/stock_company_product_SQL.php" method="post">
                                       <div class='col-md-6'>
                                             <div class='row'>
                                                <div class='col-sm-12'>
                                                   <div class='form-group'>
                                                      <label class='control-label' for='id_product'>Produto:</label>
                                                      <select class='form-control selectProduct' id='id_product' name='id_product'>
                                                         <?
                                                         if($acao=="atualizar"){
                                                            echo "<optgroup label='{$produto['type']}'>
                                                                  <option value='{$produto['id']}'>{$produto['name']}</option>'
                                                                  </optgroup>";
                                                         }else{

                                                               foreach ($produtos as $type => $dados) {
                                                                  echo "<optgroup label='{$type}'>";
                                                                     for($i=0;$i<count($dados);$i++){
                                                                        echo "<option value='{$dados[$i]['id']}'>{$dados[$i]['name']}</option>";
                                                                     }
                                                                  echo "</optgroup>";
                                                               }
                                                         }
                                                         ?>
                                                      </select>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class='row'>
                                                <div class='col-sm-6'>
                                                   <div class='form-group'>
                                                      <label class='control-label' for='min_count'>Estoque mínimo regulador:</label>
                                                      <input type="number" class='form-control' id='min_count' name='min_count' value="<?=$d['min_count'];?>">
                                                   </div>
                                                </div>
                                             </div>
                                             <div class='row'>
                                                <div class='col-sm-12' style="margin-top:20px">
                                                   <?
                                                      echo "<input type='hidden' name='acao' value='{$acao}'>";
                                                      if($acao=="atualizar")
                                                      {
                                                            echo "<input type='hidden' name='id' value='{$_GET['id']}'>";
                                                            echo "<button type='submit' class='btn btn-primary'>Atualizar</button>";
                                                      }else{
                                                            echo "<input type='hidden' name='id_company' value='{$_SESSION['id_company']}'>";
                                                            echo "<button type='submit' class='btn btn-success'>Inserir</button>";
                                                      }
                                                   ?>
                                                </div>
                                             </div>
                                       </div>
                                    </form>
                                    <div class='col-md-6 text-center'>
                                       <?
                                          if($acao=="atualizar")
                                          {
                                                echo "<h3><small>Saldo atual:</small><br>{$d['actual_count']} {$produto['unit']}</h3>";
                                          }
                                       ?>
                                    </div>
                                 </div>



										</div>
									</div>
								</div>
<? if($acao=="atualizar"){ ?>
								<div class="panel panel-accordion panel-accordion-info">
									<div class="panel-heading">
										<h4 class="panel-title">
											<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse2Two"  ajax="false">
												<i class="fa fa-bar-chart"></i> Movimentação
											</a>
										</h4>
									</div>
									<div id="collapse2Two" class="accordion-body collapse in">
										<div class="panel-body">
                                 <?
                                       echo "<table class='table table-condensed'>";
                                          echo "<tbody>";
                                             echo "<tr>";
                                                echo "<td><small class='text-muted'><i>Produto:</i></small><br><small class='text-muted'>({$produto['id']}) - </small> {$produto['name']}</td>";
                                                echo "<td><small class='text-muted'><i>Descrição:</i></small><br>{$produto['description']}</td>";
                                                echo "<td><small class='text-muted'><i>Estoque mínimo:</i></small><br>{$d['min_count']} {$produto['unit']}</td>";
                                                echo "<td><small class='text-muted'><i>Saldo atual:</i></small><br><b>{$d['actual_count']} {$produto['unit']}</b></td>";
                                             echo "</tr>";
                                             echo "<tr><td colspan='4'><button type='button' class='btn btn-info' data-toggle='modal' data-target='#modalRegistro'>Registrar movimento</button></td></tr>";
                                          echo "</tbody>";
                                       echo "</table>";

                                       $sql = "SELECT * FROM {$schema}gsec_stock_company_transactions T WHERE T.id_product = {$produto['id']} AND id_company = {$d['id_company']} ORDER BY id DESC";
                                       $res = pg_query($sql)or die("SQL Error ".__LINE__);
                                       if(pg_num_rows($res))
                                       {
                                          echo "<table class='table table-hover table-striped'>";
                                          echo "<thead><tr><th width='10px'>#</th>
                                                           <th>Descrição</th>
                                                           <th class='text-center' width='10px'>Data</th>
                                                           <th class='text-center' width='10px'>Saída</th>
                                                           <th class='text-center' width='10px'>Entrada</th></tr>
                                                </thead>
                                                </tbody>";
                                          while($mov = pg_fetch_assoc($res))
                                          {
                                             echo "<tr>";
                                                echo "<td nowrap class='text-muted'><small>{$mov['id']}</small></td>";
                                                echo "<td>{$mov['description']}</td>";
                                                echo "<td class='text-center' nowrap>".formataData($mov['date_added'],1)."</td>";
                                                if($mov['type']=="in")
                                                {
                                                   echo "<td></td>";
                                                   echo "<td class='text-center' nowrap>{$mov['count']}</td>";
                                                }else{
                                                   echo "<td class='text-center text-danger'nowrap>-<b>{$mov['count']}</b></td>";
                                                   echo "<td></td>";
                                                }
                                             echo "</tr>";
                                          }
                                          echo "</tbody></table>";
                                       }else{
                                          echo "<div class='alert alert-info'>Não há registro de movimentação de estoque para este produto.</div>";
                                       }
                                 ?>
										</div>
									</div>
								</div>
<? } ?>
							</div>
						</div>


                     </div>
                  </div>
               </div>

      </section>
</section>
<!-- Modal FILTROS -->
<div class="modal fade"  id="modalRegistro" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
   <div class="modal-content">
      <div class="modal-header">
       <h5 class="modal-title" id="exampleModalLabel">Registro de movimentação:</h5>
       <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-20px">
          <span aria-hidden="true">&times;</span>
       </button>
      </div>
      <form id="form_modal" name="form_modal" action="gsec/stock_company_product_SQL.php" method="post">
      <div class="modal-body">
         <div class='row'>
            <div class='col-sm-6'>
                     <label class='control-label' for='min_count'>Quantidade:</label>
                     <div class="input-group mb-md">
      						<input name="count" type="text" class="form-control">
      						<span class="input-group-addon btn-warning"><?=$produto['unit'];?></span>
      					</div>
            </div>

            <div class='col-sm-6'>
               <div class='form-group'>
                  <label class='control-label' for='type'>Sentido:</label>
                  <select class='form-control select2Modal' id='type' name='type'>
                     <option value="in">Entrada</option>
                     <option value="out">Saída</option>
                  </select>
               </div>
            </div>
         </div>
         <div class='row'>
            <div class='col-sm-12'>
               <label class='control-label' for='description'>Observações:</label>
               <textarea class="form-control" id="description" name="description"></textarea>
            </div>
         </div>
      </div>
      <div class="modal-footer">
       <input type="hidden" name="id_product"             value='<?=$produto['id'];?>' />
       <input type="hidden" name="id_gsec_stock_company"  value='<?=$_GET['id'];?>' />
       <input type="hidden" name="id_company"             value='<?=$d['id_company'];?>' />
       <input type="hidden" name="id_user"                value='<?=$_SESSION['id'];?>' />
       <input type="hidden" name="acao"                   value="insere_transacao_modal">
       <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
       <button type="button" class="btn btn-primary" id="bt_submit_modal">Registrar</button>
      </div>
      </form>
   </div>
  </div>
</div>

<script>

$("#bt_submit_modal").click(function(){
    $('#modalRegistro').modal('hide');
    $('body').removeClass('modal-open');
    $('.modal-backdrop').remove();
    $("#form_modal").submit();
});


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

$('.selectProduct').select2();
$('.select2Modal').select2({
   dropdownParent: $('#modalRegistro'),
   language: { noResults: function() { return 'Nenhum resultado encontrado.';} }
   });
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
