<?
session_start();
session_start();
require_once("../libs/php/funcoes.php");
require_once("../libs/php/conn.php");
$schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");

$filtro = ($_GET['filtro']!=""?$_GET['filtro']:"todos");
$class_filtro[$filtro] = "active";
logger("Acesso","Logs");
?>
				<section role="main" class="content-body">
					<header class="page-header">
						<h2>Visualizador de Log</h2>
						<div class="right-wrapper pull-right" style='margin-right:15px;'>
							<ol class="breadcrumbs">
								<li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
								<li><span class='text-muted'>Configurações</span></li>
								<li><span class='text-muted'>Visualização de logs</span></li>
							</ol>
						</div>
					</header>

					<section class="panel">
						<div class="panel-body box_shadow">
<?
	$agora = now();
		$sql = "SELECT U.name, L.id_user, L.timestamp, L.ip, L.module, L.action, L.obs
						FROM ".$schema."logs L
						LEFT JOIN ".$schema."users U ON U.id = L.id_user
						--WHERE id_user = 110
						--WHERE L.obs like '%Baixou o%'
						WHERE
						--L.module <> 'Logs' AND
						L.timestamp >= '".$agora['datasrv']." 00:00:00'
						--L.timestamp >= '2019-11-06 00:00:00'
						ORDER BY L.timestamp DESC
						--LIMIT 2500";
	$rs  = pg_query($sql)or die("Erro ".__LINE__);
	while($aux = pg_fetch_assoc($rs)){ $d[] = $aux; }
	unset($sql,$rs);
	if(isset($d) && count($d)){
?>

<table class="table table-striped" id="tabela_dinamica">
	<thead>
		<tr>
			<th>Usuário</th>
			<th>Data</th>
	<!--		<th><span class="text-weight-normal text-sm">IP</span></th>-->
			<th>Módulo</th>
			<th>Ação</th>
			<th>Detalhamento</th>
		</tr>
	</thead>
	<tbody class="">
	  <?
						for($i=0;$i<count($d);$i++)
						{
							switch($d[$i]['tipo'])
							{
								case "DEBUG":
										$icon = "<i class='fa fa-bug fa-fw text-muted text-md va-middle'></i><span class='va-middle'>Debug</span>";
										break;
								case "INFO":
										$icon = "<i class='fa fa-info fa-fw text-info text-md va-middle'></i><span class='va-middle'>Info</span>";
										break;
								case "WARNING":
										$icon = "<i class='fa fa-warning fa-fw text-warning text-md va-middle'></i><span class='va-middle'>Warning</span>";
										break;
								case "DANGER":
										$icon = "<i class='fa fa-times-circle fa-fw text-danger text-md va-middle'></i><span class='va-middle'>Danger</span>";
										break;
								default:
										$icon = "";
							}
							unset($data);
							$data = explode(" ",formataData($d[$i]['timestamp'],1));
							echo "<tr>";
								echo "<td nowrap>".$d[$i]['name']."</td>";
								echo "<td>{$data[0]}<br><small>{$data[1]}</small></td>";
							//	echo "<td>".$d[$i]['ip']."</td>";
								echo "<td nowrap>".$d[$i]['module']."</td>";
								echo "<td nowrap>".$d[$i]['action']."</td>";
								echo "<td>".($d[$i]['obs']!="Null"?$d[$i]['obs']:"")."</td>";
							echo "</tr>";
						}
	echo "</tbody>
				</table>";
	}else
	{
		echo "<tr><td colspan='4'>
		<div class='alert alert-warning text-center col-md-6 col-md-offset-3'>
			Nenhum log encontrado no sistema.
		</div>";
	}
		?>


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
