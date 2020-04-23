<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");

  $agora = now();
  $sql   = "SELECT * FROM ".$schema."oct_workshift WHERE id_company = ".$_SESSION['id_company']." AND status = 'aberto'";
  $res   = pg_query($sql)or die("Erro ".__LINE__);

  if(pg_num_rows($res))
  {
    $turno = pg_fetch_assoc($res);
    $turnoFechado = false;
    $sql = "SELECT
          			 A.name as local, A.neighborhood as bairro, A.zone as zona,
          			 SAB.name as logradouroAddressBook,
          			 SAE.name as logradouroRegistro,
          			 U.name as nome_usuario,
          			 AE.*
            FROM      ".$schema."oct_administrative_events AE
            LEFT JOIN ".$schema."oct_addressbook   A ON A.id   = AE.id_addressbook
            LEFT JOIN ".$schema."streets 				SAB ON SAB.id = A.id_street
            LEFT JOIN ".$schema."streets 				SAE ON SAE.id = AE.id_street
            LEFT JOIN ".$schema."users             U ON U.id   = AE.id_user
            WHERE AE.id_workshift = '".$turno['id']."'
            ORDER BY AE.id DESC";
    $res = pg_query($sql)or die("Erro ".__LINE__);
  }else {
    $turnoFechado = true;
  }
?>

<section role="main" class="content-body">
  <header class="page-header">
    <h2>Eventos administrativos</h2>
    <div class="right-wrapper pull-right" style='margin-right:15px;'>
      <ol class="breadcrumbs">
        <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
        <li><a href="oct/index.php">Sistema</a></li>
        <li><span class='text-muted'>Eventos administrativos</span></li>
      </ol>
      <!--<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>-->
    </div>
  </header>


<?

  if($turnoFechado)
  {

    echo "<div class='col-md-12'>
    								<section class='panel'>
                    <header class='panel-heading'>
                    </header>
                      <div class='panel-body'>
                        <div class='alert alert-warning col-md-6 col-md-offset-3 text-center'><strong>Aviso: </strong> Nenhum turno aberto no sistema.</div>
                      </div>
                    </section>
          </div>";

  }else
  {
?>
<div class="col-md-12">
								<section class="panel box_shadow">
									<header class="panel-heading"  style="height:50px">
                    <div class="panel-actions" style='margin-top:-10px'>
                      <a href="oct/eventos_administrativos_FORM.php?turno=<?=$turno['id'];?>">
                        <button type="button" class="mb-xs mt-xs mr-xs btn btn-sm btn-primary"><i class="fa fa-plus"></i> Inserir evento</button>
                      </a>

									  </div>
                  </header>
									<div class="panel-body">
										<div class="table-responsive">
<?
  if(pg_num_rows($res))
  {
?>
											<table class="table table-hover mb-none">
												<thead>
													<tr>
														<th>#</th>
                            <th>Turno</th>
                            <th>Abertura</th>
                            <th>Fechamento</th>
                            <th>Local</th>
                            <th>Envolvido</th>
                            <th>Descrição</th>
                            <th class='text-center'><i class='fa fa-cogs'></i></th>
													</tr>
												</thead>
												<tbody>
                            <?
                              while($d = pg_fetch_assoc($res))
                              {
                                  echo "<tr>";
                                    echo "<td class='text-muted'><small><sup>".$d['id']."</sup></small></td>";
                                    echo "<td class='text-muted'>".$turno['id']."</td>";
                                    echo "<td>".formataData($d['opened_timestamp'],1)."</td>";
                                    echo "<td>".formataData($d['closed_timestamp'],1)."</td>";
                                    echo "<td>".$d['local']."</td>";
                                    echo "<td>".$d['nome_usuario']."</td>";
                                    echo "<td>".$d['description']."</td>";
                                    echo "<td><a href='oct/eventos_administrativos_FORM.php?id=".$d['id']."' class='btn btn-xs btn-default loading2'><i class='fa fa-pencil'></i></a></td>";
                                  echo "</tr>";
                              }
                            ?>
								         </tbody>
											</table>
										</div>
									</div>
								</section>
							</div>

<? }else {
  echo "<div class='col-md-6 col-md-offset-3 text-center'><div class='alert alert-warning'>Nenhum registro administrativo para este turno de trabalho.</div>";
}

} ?>

</section>
<script>

</script>
