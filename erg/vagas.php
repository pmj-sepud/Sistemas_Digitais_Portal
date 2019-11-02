<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  $agora = now();

  $sql = "SELECT PT.type, S.name as street, P.* FROM sepud.eri_parking P
          JOIN sepud.streets S ON S.id = P.id_street
          JOIN sepud.eri_parking_type PT ON PT.id = P.id_parking_type
          ORDER BY P.name ASC";

  $res = pg_query($conn_neogrid,$sql)or die("Erro ".__LINE__);
  while($d = pg_fetch_assoc($res))
  {
      $dados[] = $d;
  }
  logger("Acesso","SERP - Visualização das vagas");
?>
<section role="main" class="content-body">
  <header class="page-header">
    <h2>SERP - Sistema de Estacionamento Rotativo Público</h2>
    <div class="right-wrapper pull-right" style='margin-right:15px;'>
      <ol class="breadcrumbs">
        <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
        <li><span>SERP - Vagas</span></li>
      </ol>
      <!--<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>-->
    </div>
  </header>

  <!-- start: page -->
  <div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">
      <section class="panel">
        <header class="panel-heading">
          <div class="panel-actions" style='margin-top:-12px'>
          </div>
        </header>
<style>

.yellow {
  background-color: #EFEF55;
  color:#333333;
}
.white {
  background-color: #FFFFFF;
  color:#333333;
}
</style>
        <div class="panel-body">
                  <div class="row">
                    <div class="col-lg-12">
                        <table class="table table-striped table-hover" id="tabela_dinamica">
                          <thead>
                            <tr><th nowrap>Nº da vaga</th>
                                <th>Tipo</th>
                                <th class='text-center'>Status</th>
                                <th>Logradouro</th>
                                <th>Área</th>
                                <th><i class='fa fa-cogs'></i></th>
                            </tr>
                          </thead>
                        <tbody>
                            <?
                                for($i = 0; $i<count($dados);$i++)
                                {
                                  $class_type = $class = "";

                                  if($dados[$i]['active'] == 't')
                                  {
                                        $dados[$i]['active'] = "Ativa";
                                        if($dados[$i]['type']=="Zona Azul - Veículo de passeio") { $class_type = 'primary';}
                                        if($dados[$i]['type']=="Zona Amarela - Veículo de carga"){ $class_type = 'yellow'; }
                                        if($dados[$i]['type']=="Zona Verde - Idoso")             { $class_type = 'success';}
                                        if($dados[$i]['type']=="Zona Preta - Moto")              { $class_type = 'dark';   }
                                        if($dados[$i]['type']=="Zona Branca - Deficiente")       { $class_type = 'white';  }
                                        if($dados[$i]['type']=="Zona Vermelha - Curta-duração")  { $class_type = 'danger'; }
                                  }else{
                                        $dados[$i]['active'] = "Desativada"; $class="style='color:#BBBBBB'";
                                  }

                                  echo "<tr ".$class.">";
                                    echo "<td>".$dados[$i]['name']."</td>";
                                    echo "<td nowrap class='".$class_type."'>".$dados[$i]['type']."</td>";
                                    echo "<td class='text-center'>".$dados[$i]['active']."</td>";
                                    echo "<td>".$dados[$i]['street']."</td>";
                                    echo "<td>".$dados[$i]['area']."</td>";
                                    echo "<td class='actions'><a href='erg/vagas_FORM.php?id=".$dados[$i]['id']."'><i class='fa fa-pencil'></i></a></td>";
                                  echo "</tr>";
                                }
                            ?>
                        </tbody>
                        </table>
                    </div>
                  </div>
        </div>
    </section>
  </div>
</div>
</section>
<script>
$(document).ready( function () {
    $('#tabela_dinamica').DataTable({
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
