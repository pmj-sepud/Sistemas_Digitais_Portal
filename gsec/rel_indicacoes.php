<?
  session_start();
  error_reporting(0);
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");
  logger("Acesso","Relatório", "Indicações");

?>

<section role="main" class="content-body">
  <header class="page-header">
    <h2>GSEC - Relatorio - Indicações</h2>
    <div class="right-wrapper pull-right" style='margin-right:15px;'>
      <ol class="breadcrumbs">
        <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
        <li><span class='text-muted'>GSEC</span></li>
        <li><span class='#'>Relatório - Indicações políticas para zeladoria urbana</span></li>
      </ol>
    </div>
  </header>

  <section class="panel box_shadow">
    <header class="panel-heading" style="height:70px;">
      <div style="margin-top:-10px">
      <h4>
            <b><?=$name_company;?></b>
      </h4>
      </div>
      <div class="panel-actions" style="margin-top:-5px">

<!--
        <button type="button" class="mb-xs mt-xs mr-xs btn btn-primary" data-toggle="modal" data-target="#modal_filtro_gsec_dashboard">
          <i class="fa fa-search"></i> Filtros</button>
        </button>
-->
    </header>
    <div class="panel-body">

<?
   $sql = "SELECT
              'geradas' as status,
            	count(*) as qtd, CI.name, CO.acron as company_acron, CO.name as company_name,
            	date_part('month', C.date_added) as mes, date_part('year', C.date_added) as ano
            FROM sepud.gsec_callcenter C
            JOIN sepud.gsec_citizen CI ON CI.id = C.id_citizen
            JOIN sepud.company CO ON CO.id = C.id_company
            WHERE C.origin_type = 'Vereador'
            AND C.date_added BETWEEN '2021-01-01 00:00:00' AND '2021-12-31 23:59:50'
            GROUP BY CI.name, CO.acron, C.date_added, CO.name
            UNION ALL
            SELECT
            	 'baixadas' as status,
            	count(*) as qtd, CI.name, CO.acron as company_acron, CO.name as company_name,
            	date_part('month', C.date_closed) as mes, date_part('year', C.date_closed) as ano
            FROM sepud.gsec_callcenter C
            JOIN sepud.gsec_citizen CI ON CI.id = C.id_citizen
            JOIN sepud.company CO ON CO.id = C.id_company
            WHERE c.origin_type = 'Vereador' AND date_closed is not null
            AND C.date_closed BETWEEN '2021-01-01 00:00:00' AND '2021-12-31 23:59:50'
            GROUP BY CI.name, CO.acron, C.date_closed, CO.name";
   $res = pg_query($sql)or die("SQL ERROR: ".__LINE__."<br>Query: {$sql}");

   while($dd = pg_fetch_assoc($res)){
      $vereadores[$dd['name']][$dd['status']]   += $dd['qtd'];
      $subs[$dd['company_name']][$dd['status']] += $dd['qtd'];

      $subsmensal[$dd['company_name']][$dd['status']][$dd['mes']] += $dd['qtd'];

      $aux = explode(" - ",$dd['name']);
      if(count($aux) == 2){ $partidos[$aux[1]][$dd['status']] += $dd['qtd']; }
}
////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////
   ksort($subs);
   echo "<h4 class='text-center'><b>Consolidado anual por subprefeitura</b></h4>";
   echo "<table class='table table-striped table-hover'>";
   echo "<thead><tr>
            <th>Subprefeitura</th>
            <th class='text-center'>Geradas</th>
            <th class='text-center'>Baixadas</th>
            <th class='text-center'>Produção</th>";
   echo "<tbody>";
   foreach ($subs as $subpref => $dados){
      echo "<tr>";
         echo "<td>{$subpref}</td>";
         echo "<td width='10px' class='text-center'>{$dados['geradas']}</td>";
         echo "<td width='10px' class='text-center'>{$dados['baixadas']}</td>";
         echo "<td width='10px' class='text-center'>".round($dados['baixadas']*100/$dados['geradas'],1)." %</td>";
      echo "</tr>";
      $totalgerado += $dados['geradas'];
      $totalbaixado += $dados['baixadas'];
   }
   echo "<tr><td class='text-muted pull-right'>Total:</td>
             <td class='text-center'>{$totalgerado}</td>
             <td class='text-center'>{$totalbaixado}</td>
             <td class='text-center'>".round($totalbaixado*100/$totalgerado,1)." %</td></tr>";
   echo "</tbody>";
   echo "</table>";
////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////
      ksort($subsmensal);
      echo "<h4 class='text-center'><b>Evolução mensal por subprefeitura</b></h4>";
      echo "<table class='table table-striped table-hover'>";
      echo "<tr><th>Subprefeitura</th>
                <th width='10px' colspan='12' class='text-center'>Meses</th>
            </tr>";
      foreach ($subsmensal as $subpref => $dados)
      {
         echo "<tr><td nowrap>{$subpref}</td>";
         echo "<td class='text-muted'>Jan</td>";
         echo "<td class='text-muted'>Fev</td>";
         echo "<td class='text-muted'>Mar</td>";
         echo "<td class='text-muted'>Abr</td>";
         echo "<td class='text-muted'>Mai</td>";
         echo "<td class='text-muted'>Jun</td>";
         echo "<td class='text-muted'>Jul</td>";
         echo "<td class='text-muted'>Ago</td>";
         echo "<td class='text-muted'>Set</td>";
         echo "<td class='text-muted'>Out</td>";
         echo "<td class='text-muted'>Nov</td>";
         echo "<td class='text-muted'>Dez</td>";
         echo "<tr><td class='text-muted text-right'><i>Geradas:</i></td>";  for($i=1;$i<=12;$i++){ echo "<td width='20px' class='text-center'>{$dados['geradas'][$i]}</td>"; } echo "</tr>";
         echo "<tr><td class='text-muted text-right'><i>Baixadas:</i></td>"; for($i=1;$i<=12;$i++){ echo "<td width='20px' class='text-center'>{$dados['baixadas'][$i]}</td>"; }  echo "</tr>";
      }
      echo "</table>";
////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////
   ksort($partidos);
   unset($totalgerado, $totalbaixado);
   echo "<h4 class='text-center'><b>Consolidado anual por partido político</b></h4>";
   echo "<table class='table table-striped table-hover'>";
   echo "<thead><tr>
            <th>Partido</th>
            <th width='10px' class='text-center'>Geradas</th>
            <th width='10px' class='text-center'>Baixadas</th>
            <th width='10px' class='text-center'>Produção</th>";
   echo "<tbody>";
   foreach ($partidos as $partido => $dados){
      echo "<tr>";
         echo "<td>{$partido}</td>";
         echo "<td class='text-center'>{$dados['geradas']}</td>";
         echo "<td class='text-center'>{$dados['baixadas']}</td>";
         echo "<td class='text-center'>".round($dados['baixadas']*100/$dados['geradas'],1)." %</td>";
      echo "</tr>";
      $totalgerado += $dados['geradas'];
      $totalbaixado += $dados['baixadas'];
   }
   echo "<tr><td class='text-muted pull-right'>Total:</td>
             <td class='text-center'>{$totalgerado}</td>
             <td class='text-center'>{$totalbaixado}</td>
             <td class='text-center'>".round($totalbaixado*100/$totalgerado,1)." %</td></tr>";
   echo "</tbody>";
   echo "</table>";
////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////
   ksort($vereadores);
   unset($totalgerado, $totalbaixado);
   echo "<h4 class='text-center'><b>Consolidado anual por vereador</b></h4>";
   echo "<table class='table table-striped table-hover'>";
   echo "<thead><tr>
            <th>Vereador</th>
            <th width='10px' class='text-center'>Geradas</th>
            <th width='10px' class='text-center'>Baixadas</th>
            <th width='10px' class='text-center'>Produção</th>";
   echo "<tbody>";
   foreach ($vereadores as $vereador => $dados){
      echo "<tr>";
         echo "<td>{$vereador}</td>";
         echo "<td class='text-center'>{$dados['geradas']}</td>";
         echo "<td class='text-center'>{$dados['baixadas']}</td>";
         echo "<td class='text-center'>".round($dados['baixadas']*100/$dados['geradas'],1)." %</td>";
      echo "</tr>";
      $totalgerado += $dados['geradas'];
      $totalbaixado += $dados['baixadas'];
   }
   echo "<tr><td class='text-muted pull-right'>Total:</td>
             <td class='text-center'>{$totalgerado}</td>
             <td class='text-center'>{$totalbaixado}</td>
             <td class='text-center'>".round($totalbaixado*100/$totalgerado,1)." %</td></tr>";
   echo "</tbody>";
   echo "</table>";
?>
    </div>
  </section>
</section>
