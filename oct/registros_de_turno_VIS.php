<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");

  $agora = now();
  $sql = "SELECT
              	U.name, U.nickname, U.registration,
              	F.plate, F.type as fleet_type, F.model, F.brand, F.nickname as fleet_nickname,
              	H.*
              FROM
              	sepud.oct_workshift_history H
              LEFT JOIN sepud.users U ON U.id = H.id_user
              LEFT JOIN sepud.oct_fleet F ON F.id = H.id_vehicle
              WHERE H.id_workshift = '".$_GET['id_workshift']."'";
  $res = pg_query($sql)or die("Erro ".__LINE__);

?>

<section role="main" class="content-body">
  <header class="page-header">
    <h2>Registros do turno</h2>
    <div class="right-wrapper pull-right" style='margin-right:15px;'>
      <ol class="breadcrumbs">
        <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
        <li><a href="oct/index.php">Sistema</a></li>
        <li><span class='text-muted'>Registros do turno</span></li>
      </ol>
      <!--<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>-->
    </div>
  </header>


<?
?>
<div class="col-md-12">
								<section class="panel box_shadow">
									<header class="panel-heading"  style="height:70px">
                    <div class="panel-actions"   style='margin-top:5px'>
                            <a href="oct/index.php?id_workshift=<?=$_GET['id_workshift'];?>"><button type="button" class="btn btn-default loading2"><i class='fa fa-mail-reply text-muted'></i> Voltar</button></a>
                            <style>
                                   .panel-actions a,
                                   .panel-actions
                                   .panel-action { text-align: left; width: 100%; }
                            </style>
                            <div class='btn-group'>

                                <button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown'><i class='fa fa-file-text-o'></i> Registros do turno <small><sup><i class='fa fa-chevron-down'></i></sup></small></button>
                                <ul class='dropdown-menu'><span style='margin-left:5px;color:#BBBBBB'><i>Novo registro:</i></span>
                                    <li><a href='oct/registros_de_turno_FORM.php?gotoback=vis&id_workshift=<?=$_GET['id_workshift'];?>&tipo_registro=veiculo'>Veículo</a></li>
                                    <li><a href='oct/registros_de_turno_FORM.php?gotoback=vis&id_workshift=<?=$_GET['id_workshift'];?>&tipo_registro=pessoa'>Pessoa</a></li>
                                    <li><a href='oct/registros_de_turno_FORM.php?gotoback=vis&id_workshift=<?=$_GET['id_workshift'];?>&tipo_registro=guarnicao'>Guarnição</a></li>
                                </ul>
                            </div>
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
                            <th>Apelido</th>
                            <th>Envolvido</th>
                            <th>Tipo</th>
                            <th>Observação</th>
                            <th>Inicio</th>
                            <th>Fim</th>
                            <th class='text-center' colspan='2'><i class='fa fa-cogs'></i></th>
													</tr>
												</thead>
												<tbody>
                            <?
                              while($d = pg_fetch_assoc($res))
                              {
                                  switch ($d['origin']) {
                                    case 'pessoa':
                                      $apelido = "<b>".$d['nickname']."</b>";
                                      $nome    = $d['name'];
                                      break;
                                    case 'veiculo':
                                      $apelido = "<b>".$d['fleet_nickname']."</b>";
                                      $nome    = $d['brand']." ".$d['model'];
                                      break;

                                    case 'guarnicao':
                                      unset($nome, $apelido, $garr);
                                      $sql = "SELECT
                                                G.id_garrison,
                                                U.id, U.name, U.nickname, U.registration
                                               FROM
                                                sepud.oct_rel_garrison_persona G
                                               JOIN sepud.users U ON U.id = G.id_user
                                               WHERE
                                                id_garrison = '".$d['id_garrison']."'";
                                      $resG = pg_query($sql)or die("Erro ".__LINE__."<br>SQL: ".$sql);
                                      while($g = pg_fetch_assoc($resG)){ $garr[] = $g['nickname']; }
                                      $apelido = "Guarnição";
                                      $nome    = implode(", ",$garr);
                                      break;

                                    default:
                                      $nome = $apelido = "- - -";
                                      break;
                                  }
                                  echo "<tr>";
                                    echo "<td class='text-muted'><small><sup>".$d['id']."</sup></small></td>";
                                    echo "<td>".$apelido."</td>";
                                    echo "<td>".$nome."</td>";
                                    echo "<td>".ucfirst($d['type'])."</td>";
                                    echo "<td>".$d['obs']."</td>";
                                    echo "<td>".formataData($d['opened'],1)."</td>";
                                    echo "<td>".formataData($d['closed'],1)."</td>";
                                    echo "<td width='20px'><a href='oct/registros_de_turno_SQL.php?id_workshift=".$_GET['id_workshift']."&acao=Remover&id=".$d['id']."' class='btn btn-xs btn-danger'><i class='fa fa-trash'></i></a></td>";
                                    echo "<td width='20px'><a href='oct/registros_de_turno_FORM.php?gotoback=vis&id=".$d['id']."&tipo_registro=".$d['origin']."&id_workshift=".$_GET['id_workshift']."'><button type='button' class='btn btn-xs btn-default'><i class='fa fa-pencil'></i></button></a></td>";
                                  echo "</tr>";

                              }
                            ?>
								         </tbody>
											</table>
										</div>
									</div>
								</section>
							</div>

<?
   }else{
      echo "<div class='col-md-6 col-md-offset-3 text-center'><div class='alert alert-warning'>Nenhum registro administrativo para este turno de trabalho.</div>";
   }
?>

</section>
<script>
$(".loading").click(function(){ $(this).html("<i class=\"fa fa-spinner fa-spin\"></i> Aguarde");});
$(".loading2").click(function(){$(this).addClass("disabled").html("<i class=\"fa fa-spinner fa-spin\"></i>"); });
</script>
