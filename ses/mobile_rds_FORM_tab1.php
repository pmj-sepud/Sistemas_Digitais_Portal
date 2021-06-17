<style>
.link:hover{ cursor: pointer; }
.dataTables_filter { width: 50%; float: right; text-align: right; }
</style>
<div class="row">
  <div class="col-md-12 text-right">
      <a href="ses/mobile_rds.php"><button type="button" class="btn btn-default">Voltar</button></a>
      <a href="ses/mobile_rds_atividade_FORM.php?id_rds=<?=$_GET['id'];?>"><button type="button" class="btn btn-primary"><i class="fa fa-plus"></i> Nova atividade</button></a>
  </div>
</div>
<div class="row" style="margin-top:10px">
  <div class="col-md-12">
    <?
        if(isset($a) && count($a))
        {
            echo "<table id='tabela' class='table table-striped table-hover'>";
            echo "<thead><tr>
                          <th>#</th>
                          <th>Horas</th>
                          <th>Logradouro</th>
                          <th>Num.</th>
                          <th>Tipo imóvel</th>
                          <th>A1|A2|B|C|D1|D2|E</th>
                  </tr></thead>";
            echo "<tbody>";
            for($c=0;$c<count($a);$c++)
            {
                echo "<tr id='{$a[$c]['id']}' class='link'>";
                  echo "<td>{$a[$c]['id']}</td>";
                  echo "<td>{$a[$c]['hora_entrada']}</td>";
                  echo "<td>{$a[$c]['street_name']}</td>";
                  echo "<td>{$a[$c]['num_sequencia']}</td>";
                  echo "<td>{$a[$c]['tipo_imovel']}</td>";
                  echo "<td>{$a[$c]['inspecao_a1']}|{$a[$c]['inspecao_a2']}|{$a[$c]['inspecao_b']}|{$a[$c]['inspecao_c']}|{$a[$c]['inspecao_d1']}|{$a[$c]['inspecao_d2']}|{$a[$c]['inspecao_e']}</td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
        }else {
          echo "<div class='alert alert-warning text-center'>
                <strong>AVISO:</strong> Nenhuma atividade registrada para este diário de serviço.
                </div>";
        }
    ?>
  </div>
</div>
<div class='row'>
   <div class='form-group'>
      <div class='col-md-12 text-center'>
           <a href="ses/mobile_rds.php"><button type="button" class="btn btn-default">Voltar</button></a>
           <a href="ses/mobile_rds_atividade_FORM.php?id_rds=<?=$_GET['id'];?>"><button type="button" class="btn btn-primary"><i class="fa fa-plus"></i> Nova atividade</button></a>
      </div>
   </div>
</div>
<script>

$(document).ready( function () {

   $(".link").click(function(){
      $('#wrap').load("ses/mobile_rds_atividade_FORM.php?id="+$(this).attr("id"));
   })

    $('#tabela').DataTable({
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
    }
    });
});
</script>
