<?
  session_start();
  error_reporting(0);
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");


  if($_POST['filtro_data']!="")
  {
    $agora['datasrv'] = $_POST['filtro_data'];
    $agora['data'] = formataData($_POST['filtro_data'],1);
  }else{
    $agora = now();
  }

  $meses[1]['curto'] = "Jan";
  $meses[2]['curto'] = "Fev";
  $meses[3]['curto'] = "Mar";
  $meses[4]['curto'] = "Abr";
  $meses[5]['curto'] = "Mai";
  $meses[6]['curto'] = "Jun";
  $meses[7]['curto'] = "Jul";
  $meses[8]['curto'] = "Ago";
  $meses[9]['curto'] = "Set";
  $meses[10]['curto'] = "Out";
  $meses[11]['curto'] = "Nov";
  $meses[12]['curto'] = "Dez";


  $meses[1]['longo'] = "Janeiro";
  $meses[2]['longo'] = "Fevereiro";
  $meses[3]['longo'] = "Março";
  $meses[4]['longo'] = "Abril";
  $meses[5]['longo'] = "Maio";
  $meses[6]['longo'] = "Junho";
  $meses[7]['longo'] = "Julho";
  $meses[8]['longo'] = "Agosto";
  $meses[9]['longo'] = "Setembro";
  $meses[10]['longo'] = "Outubro";
  $meses[11]['longo'] = "Novembro";
  $meses[12]['longo'] = "Dezembro";



  logger("Acesso","SAS - BEV", "Relatório de atividades diárias");

  $sql = "SELECT R.id, R.date, R.status, R.demand::text, R.id_citizen,
                 C.name,
                 U.name as tecnico
          FROM {$schema}sas_request R
          JOIN {$schema}sas_citizen C ON C.id = R.id_citizen
          JOIN {$schema}users       U ON U.id = R.id_user
          WHERE R.date BETWEEN '{$agora['datasrv']} 00:00:00' AND '{$agora['datasrv']} 23:59:59' AND
                R.id_company = {$_SESSION['id_company']}
          ORDER BY R.date ASC";

  $res = pg_query($sql)or die("Error ".__LINE__."<br>Query: ".$sql);
  while($d = pg_fetch_assoc($res))
  {
    $d['demand'] = str_replace("alimentacao","alimentação",$d['demand']);
    $aux = json_decode($d['demand']);
    $d['demand'] = implode(", ",$aux);
    $vet[$d['tecnico']][] = $d;
    $total_solicitacoes++;
    $total_demanda += count($aux);
  }
?>

</style>
<section role="main" class="content-body">
  <header class="page-header">
    <h2>SAS-BEV - Relatório de atividades diária</h2>
    <div class="right-wrapper pull-right" style='margin-right:15px;'>
      <ol class="breadcrumbs">
        <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
        <li><span class='text-muted'>SAS-BEV</span></li>
        <li><span class='#'>Relatório de atividades diária</span></li>
      </ol>
    </div>
  </header>

  <section class="panel box_shadow">
    <header class="panel-heading" style="height:70px">
      Data de referência: <b><?=$agora['data'];?></b>
      <div class="panel-actions" style="margin-top:5px">
          <?
              if(check_perm("7_21","C"))
              {
            //      echo "<a href='sas/cidadao_FORM.php'><button type='button' class='btn btn-primary'><i class='fa fa-plus'></i> Novo cadastro</button></a> ";
              }
          ?>
        <button type="button" class="mb-xs mt-xs mr-xs btn btn-primary" data-toggle="modal" data-target="#modal_filtro">
          <i class="fa fa-search"></i> Filtros</button>
        </button>
    </header>
    <div class="panel-body">
      <div class="row">
        <div class="col-sm-12 text-center">
            <h3><small><?=$_SESSION['company_name'];?></small><br>Relatório de atividades diária</h3>
            Data: <?=$agora['data'];?>
        </div>
      </div>
<?
  if(count($vet))
  {
?>
<div class="row">
  <div class="col-sm-4">
      <table class='table'>
          <tr><td>Total de solicitações:</td><td><?=$total_solicitacoes;?></td></tr>
          <tr><td>Total de demandas:</td><td><?=$total_demanda;?></td></tr>
      </table>
    </div>
</div>

<div class="row">
  <div class="col-sm-12">
      <table class='table'>
          <?
              unset($dados);
              foreach ($vet as $func => $dados){
                  echo "<tr class='warning'><td colspan='5'><b>{$func}</b></td><td colspan='2' class='text-right'><b>".count($dados)."</b> demanda(s) gerada(s), (<b>".round(count($dados)*100/$total_solicitacoes,1)."</b>% do total)</td></tr>";
                  echo "<tr>
                        <td><small><i>Protocolo</i></td>
                        <td class='text-center'><small><i class='fa fa-search'></i></td>
                        <td><small><i>Demanda(s)</i></td>
                        <td><small><i>Data</i></td>
                        <td><small><i>Status</i></td>
                        <td><small><i>Requerente</i></td>
                        <td class='text-center'><small><i class='fa fa-cogs'></i></td>
                        </tr>";
                  for($i=0;$i<count($dados);$i++)
                  {
                    $protocolo = "<small class='text-muted'>".str_replace("-","",substr($dados[$i]['date'],0,-12))."</small>.<b>".$dados[$i]['id']."</b>";
                    echo "<tr>";
                      echo "<td width='10px'>{$protocolo}</td>";
                      echo "<td width='10px'>";
                      if(check_perm("7_21","CRUD") || check_perm("7_23","CRUD"))
                      {
                        echo "<a href='sas/beneficio_FORM.php?id_citizen={$dados[$i]['id_citizen']}&id_request={$dados[$i]['id']}'><button class='btn btn-xs btn-default text-muted'><i class='fa fa-search'></i></button></a>";
                      }else {
                        echo "<button class='btn btn-xs btn-default text-muted'><i class='fa fa-search'></i></button>";
                      }
                      echo "</td>";
                      echo "<td>{$dados[$i]['demand']}</td>";
                      echo "<td>".formataData($dados[$i]['date'],1)."</td>";
                      echo "<td>{$dados[$i]['status']}</td>";
                      echo "<td>{$dados[$i]['name']}</td>";
                      echo "<td width='10px'>";
                      if(check_perm("7_21","U"))
                      {
                        echo "<a href='sas/cidadao_FORM.php?id={$dados[$i]['id_citizen']}'><button class='btn btn-xs btn-default text-muted'><i class='fa fa-cogs'></i></button></a>";
                      }else {
                        echo "<button class='btn btn-xs btn-default text-muted'><i class='fa fa-cogs'></i></button>";
                      }
                      echo "</td>";
                    echo "</tr>";
                  }
              }
          ?>
      </table>
    </div>
</div>
<? }else{
  echo "<div class='row' style='margin-top:50px;margin-bottom:50px'>
          <div class='col-sm-12 text-center'>
            <div class='alert alert-warning'>
                Nenhuma solicitação gerada neste dia para este equipamento.
            </div>
        </div>
      </div>";
}?>

  </section>
</section>


<div class="modal fade" id="modal_filtro" tabindex="-1" role="dialog" aria-labelledby="modal_filtro" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Filtros de pesquisa</h5>
      </div>
      <form id="filtro" name="filtro" method="post" action="../sas/rel_atividades_diaria.php">
      <div class="modal-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                      <label for="filtro_data">Data:</label>
                      <input class="form-control" type="date" name="filtro_data">
                    </div>
                </div>
              </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        <button id="bt_submit" type="submit" class="btn btn-primary"   data-dismiss="modal">Filtrar</button>
      </div>
     </form>
    </div>
  </div>
</div>
<script>
$('#bt_submit').click(function(e) {
    e.preventDefault();
     $("#modal_filtro").removeClass("in");
     $(".modal-backdrop").remove();
     $('body').removeClass('modal-open');
     $('body').css('padding-right', '');
     $("#modal_filtro").hide();

     $("#filtro").submit();
    return false;
});
</script>
