<?
  session_start();
  error_reporting(0);
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");


  if($_GET['exp']=="csv")
  {
    //header ('Content-type: text/html; charset=UTF-8');
    //header ('Content-type: text/html; charset=ISO-8859-1');
    $agora = now();
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="fonte_de_dados_'.$agora['datasrv'].'.csv"');



    $dtini = explode("-",$_GET['dataini']);
    $dtfim = explode("-",$_GET['datafim']);
    $filtro_data_ini = mkt2date(date2mkt($dtini[2]."/".$dtini[1]."/".$dtini[0]));
    $filtro_data_fim = mkt2date(date2mkt($dtfim[2]."/".$dtfim[1]."/".$dtfim[0]));


    $sql = "SELECT
                E.id as ocorrência,
                E.requester AS solicitante, E.requester_origin as origem, E.requester_phone as contato,
                E.description as relato, ET.name as enquadramento,
                TO_CHAR(E.date::date, 'dd/mm/yyyy') as data_de_abertura,  E.date::time as hora,
                E.arrival::time as chegada, E.closure::time as fechamento,
                E.victim_inform as qtd,
                ST.name as logradouro,
                --S.name as logradouro,
                E.street_number as numero, AB.name as agenda,
                E.id_garrison as id_guarnição
            FROM ".$schema."oct_events E
                 JOIN ".$schema."oct_event_type  ET ON ET.id = E.id_event_type
            LEFT JOIN ".$schema."oct_addressbook AB ON AB.id = E.id_addressbook
            --LEFT JOIN ".$schema."streets          S ON  S.id = AB.id_street
            LEFT JOIN ".$schema."streets         ST ON ST.id = E.id_street
            WHERE E.date BETWEEN '".$filtro_data_ini['datasrv']." 00:00:00' AND '".$filtro_data_fim['datasrv']." 23:59:59'
            AND E.id_company = '".$_SESSION['id_company']."'
            ORDER BY E.date DESC";
    $res = pg_query($sql)or die("SQL Error ".__LINE__);
    $c=0;
    if(pg_num_rows($res))
    {
        while($a=pg_fetch_assoc($res))
        {

              $a['relato'] = nl2br($a['relato']);

              if($c==0){ $cabecalho = array_keys($a); }
              unset($linha,$veic, $pess, $abordagens, $conduzidos);

                        foreach ($a as $key => $val)
                        {
                                    $linha[] = preg_replace('~[\r\n]+~',' ',trim($val));
                                    if($key == 'id_guarnição' && $val !="")
                                    {
                                      $sql = "SELECT V.id as id_rel_garrison_vehicle,
                                                     F.plate as placa, F.nickname as apelido
                                              FROM ".$schema."oct_rel_garrison_vehicle V
                                              JOIN ".$schema."oct_fleet F ON F.id = V.id_fleet
                                              WHERE id_garrison = '".$val."'";
                                      $resVeic = pg_query($sql)or die("SQL Error ".__LINE__);
                                      if(pg_num_rows($resVeic))
                                      {
                                        $veic    = pg_fetch_assoc($resVeic);
                                        $linha['veiculo'] = $veic['apelido'];
                                        $linha['placa']   = $veic['placa'];
                                        if($c==0){ $cabecalho[] = "veiculo"; $cabecalho[] = "placa"; }
                                        $sqlPe   = "SELECT P.type, U.nickname FROM ".$schema."oct_rel_garrison_persona P
                                                    JOIN ".$schema."users U ON U.id = P.id_user
                                                    WHERE id_rel_garrison_vehicle = '".$veic['id_rel_garrison_vehicle']."'";
                                        $resPe   = pg_query($sqlPe)or die("SQL Error ".__LINE__);

                                        for($count_pass=0;$count_pass<5;$count_pass++)
                                        {
                                          if($c==0){
                                            $cabecalho[] = "GM".($count_pass+1);
                                            $cabecalho[] = "GM".($count_pass+1)."_posicao";
                                          }
                                          $aux = pg_fetch_assoc($resPe,$count_pass);
                                          $linha["GM".($count_pass+1)] = $aux['nickname'];
                                          $linha["GM".($count_pass+1)."_posicao"] = $aux['type'];
                                        }

                                      }
                                    }
                          }

              $sqlAbordagens = "SELECT count(*) as qtd, conducted
                                FROM ".$schema."oct_victim WHERE id_events = '".$a['ocorrência']."' GROUP BY conducted";
              $resAb = pg_query($sqlAbordagens)or die("SQL Error ".__LINE__);

              while($aux = pg_fetch_assoc($resAb))
              {
                $abordagens += $aux['qtd'];
                if($aux['conducted']=="t"){ $conduzidos += $aux['qtd']; }
              }
              if($c==0)
              {
                  $cabecalho[] = "abordados";
                  $cabecalho[] = "conduzidos";
              }
              $linha['abordados']  = $abordagens;
              $linha['conduzidos'] = $conduzidos;


              if($c==0){ echo implode(";",$cabecalho)."\r\n"; }
              echo implode(";",$linha)."\r\n";
              $c++;
        }
    }else{
      echo "\r\nAVISO: A pesquisa não retornou informações.";
    }
    exit();
}


  $agora = now();

  $filtrou = false;
  $error   = "";

  if($_POST['postou']=="true")
  {

            if(isset($_POST['filtro_data_inicial']) && isset($_POST['filtro_data_final']) &&
               $_POST['filtro_data_inicial'] != ""  && $_POST['filtro_data_final'] != "" )
            {
              $dtini = explode("-",$_POST['filtro_data_inicial']);
              $dtfim = explode("-",$_POST['filtro_data_final']);
              $filtro_data_ini = mkt2date(date2mkt($dtini[2]."/".$dtini[1]."/".$dtini[0]));
              $filtro_data_fim = mkt2date(date2mkt($dtfim[2]."/".$dtfim[1]."/".$dtfim[0]));
              $filtrou = true;

              $sql = "SELECT
                          E.id as ocorrência,
                          E.requester AS solicitante, E.requester_origin as origem, E.requester_phone as contato,
                          E.description as relato, ET.name as enquadramento,
                          TO_CHAR(E.date::date, 'dd/mm/yyyy') as data_de_abertura,  E.date::time as hora,
                          E.arrival::time as chegada, E.closure::time as fechamento,
                          E.victim_inform as qtd,
                          ST.name as logradouro,
                          --S.name as logradouroAgenda,
	                        E.street_number as numero, AB.name as agenda,
                          E.id_garrison as id_guarnição
                      FROM ".$schema."oct_events E
                           JOIN ".$schema."oct_event_type  ET ON ET.id = E.id_event_type
                      LEFT JOIN ".$schema."oct_addressbook AB ON AB.id = E.id_addressbook
                      --LEFT JOIN ".$schema."streets        S ON  S.id = AB.id_street
                      LEFT JOIN ".$schema."streets         ST ON ST.id = E.id_street
                      WHERE E.date BETWEEN '".$filtro_data_ini['datasrv']." 00:00:00' AND '".$filtro_data_fim['datasrv']." 23:59:59'
                      AND E.id_company = '".$_SESSION['id_company']."'
                      ORDER BY E.date DESC";
              $res = pg_query($sql)or die("SQL Error ".__LINE__."<pre class='text-center'>{$sql}</pre>");
              if(pg_num_rows($res))
              {
                  while($a=pg_fetch_assoc($res)){

//print_r_pre($a);
                      if($a['id_guarnição']!="")
                      {
                        unset($veic, $pess, $abordagens, $conduzidos);
                        $sql = "SELECT V.id as id_rel_garrison_vehicle,
                                			 F.plate as placa, F.nickname as apelido
                                FROM ".$schema."oct_rel_garrison_vehicle V
                                JOIN ".$schema."oct_fleet F ON F.id = V.id_fleet
                                WHERE id_garrison = '".$a['id_guarnição']."'";
                        $resVeic = pg_query($sql)or die("SQL Error ".__LINE__);
                        if(pg_num_rows($resVeic))
                        {

                          $veic    = pg_fetch_assoc($resVeic);
                          $a['veiculo'] = $veic['apelido'];
                          $a['placa']   = $veic['placa'];
                          //Array ( [id_rel_garrison_vehicle] => 3160 [placa] => QIO7792 [apelido] => 7792 )

                          $sqlPe   = "SELECT P.type, U.nickname FROM ".$schema."oct_rel_garrison_persona P
                                      JOIN ".$schema."users U ON U.id = P.id_user
                                      WHERE id_rel_garrison_vehicle = '".$veic['id_rel_garrison_vehicle']."'";
                          $resPe   = pg_query($sqlPe)or die("SQL Error ".__LINE__);


                          $count_pass=1;
                          while($aux = pg_fetch_assoc($resPe))
                          {
                            $pess[]=$aux;
                            $a["GM".$count_pass] = $aux['nickname'];
                            $a["GM".$count_pass++."_posicao"] = $aux['type'];
                          }
                          if($count_pass <= 5)
                          {
                              for($count_pass; $count_pass <= 5; $count_pass++)
                              {
                                $a["GM".$count_pass] = "";
                                $a["GM".$count_pass."_posicao"] = "";
                              }
                          }


                        }
                      }

                      $sqlAbordagens = "SELECT count(*) as qtd, conducted
                                        FROM ".$schema."oct_victim WHERE id_events = '".$a['ocorrência']."' GROUP BY conducted";
                      $resAb = pg_query($sqlAbordagens)or die("SQL Error ".__LINE__);

                      while($aux = pg_fetch_assoc($resAb))
                      {
                        $abordagens += $aux['qtd'];
                        if($aux['conducted']=="t"){ $conduzidos += $aux['qtd']; }
                      }


                        $a['abordados']  = $abordagens;
                        $a['conduzidos'] = $conduzidos;

                        $dados[]= $a;
                  }
              }else{
                  $error = "A pesquisa não retornou informações.";
              }
            }else{
              $filtrou = true;
              $error  = "O campo data incial e final devem ser preenchidos.";
            }
    }


  //logger("Acesso","ROTSS - Relatório de exportação de dados ROTSS em CSV, período: ".$filtro_data_ini['mes_txt']."/".$filtro_data_fim['ano']);

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


?>

<section role="main" class="content-body">
  <header class="page-header">
    <h2>Relatório exportação CSV</h2>
    <div class="right-wrapper pull-right" style='margin-right:15px;'>
      <ol class="breadcrumbs">
        <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
        <li><a href="oct/index.php">Sistema</a></li>
        <li><span class='text-muted'>Relatório exportação CSV</span></li>
      </ol>
      <!--<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>-->
    </div>
  </header>



								<section class="panel box_shadow">
  									<header class="panel-heading" style="height:70px">
                      <div class="panel-actions" style="margin-top:5px">
                        <button type="button" class="btn btn-primary"  data-toggle='modal' data-target='#modalFiltro'><i class='fa fa-search'></i> Pesquisar</button>

                        <?
                            if($filtrou == true && $error == "")
                            {
                              echo "<a href='oct/rel_export_rotss_csv.php?exp=csv&dataini=".$_POST['filtro_data_inicial']."&datafim=".$_POST['filtro_data_final']."' target='_blank' ajax='false'>
                                      <button type='button' class='btn btn-info'><i class='fa fa-print'></i> Gerar CSV</button>
                                    </a>";
                            }else{
                              echo "<button type='button' class='btn btn-info disabled'><i class='fa fa-print'></i> Gerar CSV</button>";
                            }
                        ?>

                      </div>
                    </header>
  									<div class="panel-body">
                        <?
                            if(!$filtrou)
                            {
                                echo "<br><br><br><div class='alert alert-warning text-center'>Exportação CSV, escolha uma data de incio e fim.</div><br><br><br>";
                            }else
                            {
                              if($error=="")
                              {
                                monta_tabela($dados);
                              //print_r_pre($dados);
                              }
                            }
                            if($error)
                            {
                                echo "<br><br><br><div class='alert alert-danger text-center'>".$error."</div><br><br><br>";
                            }
                        ?>

                    </div>
                </section>
</section>
<!-- Modal FILTROS -->
<div class="modal fade"  id="modalFiltro" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Filtros de pesquisa:</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-20px">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="form_filtro" action="oct/rel_export_rotss_csv.php" method="post">
      <div class="modal-body">
        <div class="row" style="margin-bottom:10px">
            <div class="form-group">
                <label class="col-md-3 control-label" for="filtro_data">Data inicial:</label>
                <div class="col-md-4">
                  <input type="date" id="filtro_data_inicial" name="filtro_data_inicial" class="form-control" value="<?=$agora['ano']."-".$agora['mes']."-01";?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3 control-label" for="filtro_data">Data final:</label>
                <div class="col-md-4">
                  <input type="date" id="filtro_data_final" name="filtro_data_final" class="form-control" value="<?=$agora['datasrv'];?>">
                </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <input type="hidden" name="postou" value="true">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
        <button type="button" class="btn btn-primary" id="bt_submit">Filtrar</button>
      </div>
      </form>
    </div>
  </div>
</div>

<script>
$("#bt_print").click(function(){
	var vw = window.open('oct/rel_olostech_SAMU_print.php?filtro_data=<?=$filtro_data['data'];?>',
									     'popup',
								 	     'width=800, height=600, top=10, left=10, scrollbars=no,location=no,status=no');
});


$("#bt_submit").click(function(){
    $('#modalFiltro').modal('hide');
    $('body').removeClass('modal-open');
    $('.modal-backdrop').remove();
    $("#form_filtro").submit();
});
$('.select2').select2();
$(".loading").click(function(){ $(this).html("<i class=\"fa fa-spinner fa-spin\"></i> Aguarde");});
$(".loading2").click(function(){ $(this).addClass("disabled").html("<i class=\"fa fa-spinner fa-spin\"></i>");});
</script>
<?
  function monta_tabela($dados)
  {
    if(count($dados))
    {
      echo "<div class='table-responsive'>";
      echo "<table class='table table-sm'>";
      echo "<thead><tr>";
      $headers = $dados[0];
      foreach ($headers as $h => $v)
      {
          echo "<th>".str_replace("_"," ",ucfirst($h))."</th>";
      }
      echo "</tr></thead>";
      echo "<tbody>";
      for($i=0;$i<count($dados);$i++)
      {
          echo "<tr>";
          foreach($dados[$i] as $key => $val)
          {
            echo "<td>".$val."</td>";
          }
          echo "</tr>";
      }
      echo "</tbody>";
      echo "</table>";
      echo "</div>";
    }else{
      echo "<div class='alert alert-danger'>Nenhum dado informado</div>";
    }
  }
?>
