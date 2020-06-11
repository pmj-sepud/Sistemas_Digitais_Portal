<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");

  if($_GET['filtro_mes']!=""){
    $filtro = true;
    $agora = mkt2date(date2mkt($_GET['filtro_mes']." 00:00:00"));
    $prox_mes = date("01/m/Y", strtotime('+1 month', $agora['mkt']));
    $ant_mes  = date("01/m/Y", strtotime('-1 month', $agora['mkt']));
  }else{
    $filtro = false;
    $agora    = now();
    $prox_mes = date('01/m/Y', strtotime('+1 month'));
    $ant_mes  = date('01/m/Y', strtotime('-1 month'));
    $btn_mes_atual_class = "text-muted";
  }

  $sql = "SELECT *
          FROM ".$schema."oct_workshift
          WHERE id_company = '".$_SESSION['id_company']."' AND
          opened BETWEEN '{$agora['ano']}-{$agora['mes']}-01 00:00:00' AND '{$agora['ano']}-{$agora['mes']}-{$agora['ultimo_dia']} 23:59:59'
          ORDER BY opened DESC";
  $rs  = pg_query($sql)or die("Erro ".__LINE__."<br>SQL: ".$sql);
  while($tmp = pg_fetch_assoc($rs))
  {
      $dados[] = $tmp;
      if($tmp['status'] == "aberto"){ $turno_aberto = true; $id_turno = $tmp['id'];}
      $turnos[$tmp['status']][] = $tmp;
      $qtd_turno_por_datas[substr($tmp['opened'],0,10)]++;
  }
?>
<section role="main" class="content-body">
  <header class="page-header">
    <h2>Gestão do turno</h2>
    <div class="right-wrapper pull-right" style='margin-right:15px;'>
      <ol class="breadcrumbs">
        <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
        <li><span class='text-muted'>Turnos</span></li>
      </ol>
    </div>
  </header>

<div class="col-md-12">
								<section class="panel box_shadow">
									<header class="panel-heading" style="height:50px">
                    Mês de referência: <b><?=$agora['mes_txt']." de ".$agora['ano'];?></b>
                    <div class="panel-actions" style='margin-top:-7px;'>

                      <div class="btn-group">
													<a href="oct/turnos_INDEX.php?filtro_mes=<?=$ant_mes;?>"><button type="button" class="btn btn-default"><i class="fa fa-angle-double-left"></i></button></a>
                          <? if($filtro){ ?>
                           <a href="oct/turnos_INDEX.php"><button type="button" class="btn btn-default">Ir para mês atual</button></a>
                         <? }else{ ?>
                            <a href="#"><button type="button" class="btn btn-default text-muted disabled">Ir para mês atual</button></a>
                         <? } ?>
													<a href="oct/turnos_INDEX.php?filtro_mes=<?=$prox_mes;?>"><button type="button" class="btn btn-default"><i class="fa fa-angle-double-right"></i></button></a>
												</div>


                      <a href='#'>
                        <button type='button' class='btn btn-primary'><i class="fa fa-search"></i> Pesquisar</button>
                      </a>
                      <a href='oct/turno.php'>
                        <button type='button' class='btn btn-info'><i class="fa fa-file-text-o"></i> <sup><i class="fa fa-plus"></i></sup> Novo turno</button>
                      </a>
									  </div>
                  </header>
<div class="panel-body">
  <div class="tabs">
              <ul class="nav nav-tabs text-right tabs-primary">
                <li class="active">
                  <a href="#turno_pag1" data-toggle="tab" ajax="false"><i class="fa fa-folder-open-o"></i> Aberto <b><sup><?=(isset($turnos['aberto'])?count($turnos['aberto']):"0");?></sup></b></a>
                </li>
                <li>
                  <a href="#turno_pag2" data-toggle="tab" ajax="false"><i class="fa fa-folder-o"></i> Inativos <b><sup><?=(isset($turnos['inativo'])?count($turnos['inativo']):"0");?></sup></b></a>
                </li>
                <li>
                  <a href="#turno_pag3" data-toggle="tab" ajax="false"><i class="fa fa-folder"></i> Fechados <b><sup><?=(isset($turnos['fechado'])?count($turnos['fechado']):"0");?></sup></b></a>
                </li>
              </ul>
              <div class="tab-content">
                <div id="turno_pag1" class="tab-pane active">
                              <table class="table table-hover mb-none">
                                <thead>
                                  <tr class='success'><td colspan='3'>Turno aberto:</td><td colspan='3' class='text-right'><b><?=(isset($turnos['aberto'])?count($turnos['aberto']):"0");?></b> turno(s)</td></tr>
                                </thead>
                                <tbody>
                                    <?
                                        unset($i, $d);
                                        if(isset($turnos['aberto']) && count($turnos['aberto']))
                                        {
                                            echo "<tr>
                                                    <td width='10px'><small><i>Turno</i></small></td>
                                                    <td width='10px'><small><i>Grupo</i></small></td>
                                                    <td width='10px'><small><i>Abertura</i></small></td>
                                                    <td width='10px'><small><i>Fechamento</i></small></td>
                                                    <td width='10px' class='text-center'><i class='fa fa-cogs'></i></td>
                                                  </tr>";
                                            for($i = 0; $i<count($turnos['aberto']);$i++)
                                            {
                                              $d = $turnos['aberto'][$i];
                                              echo "<tr>";
                                              echo "<td><b>".$d['id']." </b></td>";
                                              echo "<td nowrap>".ucfirst($d['workshift_group'])."</td>";
                                              echo "<td nowrap>".formataData($d['opened'],1)."</td>";
                                              echo "<td nowrap>".formataData($d['closed'],1)."</td>";

                                              if($d['status'] == "aberto"){ $icon = "fa-cogs";}
                                              else                        { $icon = "fa-eye"; }

                                              echo "<td class='actions text-center'>
                                                      <a href='oct/index.php?id_workshift=".$d['id']."' class='btn btn-default loading2'><i class='fa ".$icon."'></i></a>
                                                    </td>";

                                              echo "</tr>";
                                              echo "<tr>";
                                                echo "<td colspan='5'><small class='text-muted'><i>Observações:</i></br></small>".$d['observation']."</td>";
                                              echo "</tr>";
                                          }
                                        }else {
                                          echo "<tr><td colspan='6'><div class='alert alert-warning text-center'><i>Nenhum turno aberto.</i></div></td></tr>";
                                        }
                                    ?>
                                </tbody>
                              </table>
                </div>
                <div id="turno_pag2" class="tab-pane">

                  <div class="row">
                    <div class="col-sm-12">

                          <table class="table table-hover mb-none tabela_dinamica">
                            <thead>
                              <!--<tr class='info'><td colspan='3'>Turno(s) inativo(s):</td><td colspan='3' class='text-right'><b><?=(isset($turnos['inativo'])?count($turnos['inativo']):"0");?></b> turno(s)</td></tr>-->
                              <tr>
                                      <th width='10px'><small><i>Turno</i></small></th>
                                      <th width='10px'><small><i>Grupo</i></small></th>
                                      <th width='10px'><small><i>Abertura</i></small></th>
                                      <th width='10px'><small><i>Fechamento</i></small></th>
                                      <th><small><i>Observações</i></small></td>
                                      <th width='10px' class='text-center'><i class='fa fa-cogs'></i></th>
                              </tr>
                            </thead>
                            <tbody>
                                <?
                                    unset($i, $d);
                                    if(isset($turnos['inativo']) && count($turnos['inativo']))
                                    {

                                        for($i = 0; $i<count($turnos['inativo']);$i++)
                                        {
                                          $d = $turnos['inativo'][$i];
                                          if($qtd_turno_por_datas[substr($d['opened'],0,10)] > 1){ $bg_color="background:#FFFFD0";}else{ $bg_color = "";}
                                          echo "<tr>";
                                          echo "<td class='text-muted'>".$d['id']."</td>";
                                          echo "<td nowrap>".ucfirst($d['workshift_group'])."</td>";
                                          echo "<td nowrap style='".$bg_color."'>".formataData($d['opened'],1)."</td>";
                                          echo "<td nowrap>".formataData($d['closed'],1)."</td>";
                                          echo "<td>".$d['observation']."</td>";

                                          if($d['status'] == "aberto"){ $icon = "fa-cogs";}
                                          else                        { $icon = "fa-eye"; }

                                          echo "<td class='actions text-center'>
                                                  <a href='oct/index.php?id_workshift=".$d['id']."' class='btn btn-default loading2'><i class='fa ".$icon."'></i></a>
                                                </td>";
                                          echo "</tr>";
                                      }
                                      //echo "<tr><td></td><td></td><td style='background:#FFFFD0'></td><td colspan='3' calss='text-muted'><i><small><b>Legenda: </b>Mais de um turno com mesma data inicial.</small></i></td></tr>";
                                    }else {
                                      echo "<tr><td colspan='6'><div class='alert alert-warning text-center'><i>Nenhum turno inativo.</i></div></td></tr>";
                                    }
                                ?>
                            </tbody>
                          </table>

                  </div>
                </div>



                </div>
                <div id="turno_pag3" class="tab-pane">

                        <table class="table table-hover mb-none tabela_dinamica">
                          <thead>
                            <!--<tr class='warning'><td colspan='3'>Turno(s) fechado(s):</td><td colspan='3' class='text-right'><b><?=(isset($turnos['fechado'])?count($turnos['fechado']):"0");?></b> turno(s)</td></tr>-->
                            <tr>
                                    <th width='10px'><small><i>Turno</i></small></th>
                                    <th width='10px'><small><i>Grupo</i></small></th>
                                    <th width='10px'><small><i>Abertura</i></small></th>
                                    <th width='10px'><small><i>Fechamento</i></small></th>
                                    <th><small><i>Observações</i></small></th>
                                    <th width='10px' class='text-center'><i class='fa fa-cogs'></i></th>
                                  </tr>
                          </thead>
                          <tbody>
                              <?
                                  unset($i, $d);
                                  if(isset($turnos['fechado']) && count($turnos['fechado']))
                                  {
                                      for($i = 0; $i<count($turnos['fechado']);$i++)
                                      {
                                        $d = $turnos['fechado'][$i];
                                        if($qtd_turno_por_datas[substr($d['opened'],0,10)] > 1){ $bg_color="background:#FFFFD0";}else{ $bg_color = "";}
                                        echo "<tr>";
                                        echo "<td><b>".$d['id']."</b></td>";
                                        echo "<td nowrap>".ucfirst($d['workshift_group'])."</td>";
                                        echo "<td nowrap style='".$bg_color."'>".formataData($d['opened'],1)."</td>";
                                        echo "<td nowrap>".formataData($d['closed'],1)."</td>";
                                        echo "<td>".$d['observation']."</td>";

                                        if($d['status'] == "aberto"){ $icon = "fa-cogs";}
                                        else                        { $icon = "fa-eye"; }

                                        echo "<td class='actions text-center'>
                                                <a href='oct/index.php?id_workshift=".$d['id']."' class='btn btn-default loading2'><i class='fa ".$icon."'></i></a>
                                              </td>";

                                        echo "</tr>";
                                    }
                                    //echo "<tr><td></td><td></td><td style='background:#FFFFD0'></td><td colspan='3' calss='text-muted'><i><small><b>Legenda: </b>Mais de um turno com mesma data inicial.</small></i></td></tr>";
                                  }else {
                                    echo "<tr><td colspan='6'><div class='alert alert-warning text-center'><i>Nenhum turno fechado.</i></div></td></tr>";
                                  }
                              ?>
                          </tbody>
                        </table>

                </div>
              </div>
    </div>


</div>






								</section>
							</div>

</section>
<script>
$(document).ready( function () {
    $('.tabela_dinamica').DataTable({
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
     "order": [[2, "desc" ]]
    });
});
</script>
