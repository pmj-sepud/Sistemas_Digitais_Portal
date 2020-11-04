<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");
  $agora = now();

  logger("Visualização geral","SES - PNCD", "Armadilhas");

  $sql = "SELECT S.name as logradouro, T.*
          FROM {$schema}ses_trap T
          LEFT JOIN {$schema}streets S ON S.id = T.id_street";
  $res = pg_query($sql)or die("<div class='text-center'>SQL error ".__LINE__."<br>SQL: ".$sql."</div>");
?>


<section role="main" class="content-body">
  <header class="page-header">
    <h2>Cadastro de Armadilhas</h2>
    <div class="right-wrapper pull-right" style='margin-right:15px;'>
      <ol class="breadcrumbs">
        <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
        <li><span class='text-muted'>SES-PNCD</span></li>
        <li><span class='#'>Cadastro de Armadilhas</span></li>
      </ol>
    </div>
  </header>



								<section class="panel box_shadow">
  									<header class="panel-heading hidden-xs" style="height:70px">
                      <div class="panel-actions" style="margin-top:5px">
                        <?
                              if(check_perm("8_26","C"))
                              {
                                  echo "<a href='ses/armadilhas_FORM.php'><button type='button' class='btn btn-primary'><i class='fa fa-plus'></i> Novo cadastro</button></a>";
                              }
                          ?>
                      </div>
                    </header>

                    <header class="panel-heading visible-xs" style="height:100px">
                      <div class="text-center">
                        <?
                            if(check_perm("7_21","C"))
                            {
                                echo "<a href='sas/cidadao_FORM.php'><button type='button' class='btn btn-info'><i class='fa fa-plus'></i> Novo cadastro</button></a>";
                            }
                        ?>
                      </div>
                    </header>
  									<div class="panel-body">
                      <div class="row">
                        <div class="col-md-12">
                    <?

                        if(pg_num_rows($res))
                        {
                          echo "<div class='table-responsive'>";
                          echo "<table class='table table-condensed' id='tabela'>";

                          echo "<thead><tr>
                                  <th class='text-muted'>#</i></th>
                                  <th class='text-muted'>Agente</th>
                                  <th class='text-muted'>Logradouro <small>(Planilha)</small></th>
                                  <th class='text-muted'>Logradouro <small>(Oficial - Singel)</small></th>
                                  <th class='text-muted'>Tipo</th>
                                  <th class='text-muted'>Bairro</th>
                                  <th class='text-muted'>Estabelecimento/Referência</th>
                                  <th class='text-muted'>Status</th>
                                  <th class='text-center'><i class='fa fa-cogs'></i></th>
                                </thead></tr>";

                          echo "<tbody>";
                          while($d = pg_fetch_assoc($res))
                          {
/*
Array
(
  [id] => 1
  [seq_dia] => 1
  [dia_semana] => SEGUNDA-FEIRA
  [agente] => HILLE
  [rua] => CAMPOS NOVOS
  [quart] => 15
  [tipo_imov] => R
  [num_imov] => 92
  [complemento] =>
  [num_armadilha] => 1
  [bairro] => GLÓRIA
  [estabelecimento] => RESIDÊNCIA
  [local_armadilha] => LADO DIREITO
  [insc_imob] => 13-20-12-92-665
  [novo_dia] => 1
  [nova_rota] => 1
  [obs] =>
  [georef] => -26.305720, -48.864226
  [id_street] =>
  [id_neighborhood] =>
  [id_user] =>
)
*/
                                echo "<tr>";
                                echo "<td width='5px'><small class='text-muted'>{$d['id']}</small></td>";
                                echo "<td>{$d['agente']}</td>";
                                echo "<td>{$d['rua']}, {$d['num_imov']}</td>";
                                echo "<td>{$d['logradouro']}</td>";
                                echo "<td>{$d['tipo_imov']}</td>";
                                echo "<td>{$d['bairro']}</td>";
                                echo "<td>{$d['estabelecimento']} - {$d['local_armadilha']}</td>";
                                echo "<td>".($d['ativo']=="t"?"Ativa":"Inativa")."</td>";
                                echo "<td class='text-center' width='5px'>";
                                if(check_perm("7_21","U"))
                                {
                                  echo "<a href='ses/armadilhas_FORM.php?id={$d['id']}'><button class='btn btn-xs btn-default text-muted'><i class='fa fa-cogs'></i></button></a>";
                                }
                                echo "</td>";
                                echo "</tr>";
                          }
                          echo "</tbody>";
                          echo "</table>";
                          echo "</div>";
                        }else{

                            echo "<div class='alert alert-warning text-center'>Nenhum cidadão cadastrado no sistema.</div>";

                        }

                    ?>
                  </div>
                </div>

                    </div>
                </section>
</section>


<script>

$(document).ready( function () {
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

$("#bt_print").click(function(){
	var vw = window.open('oct/rel_olostech_SAMU_print.php?filtro_data=<?=$filtro_data['data'];?>',
									     'popup',
								 	     'width=800, height=600, top=10, left=10, scrollbars=no,location=no,status=no');
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
