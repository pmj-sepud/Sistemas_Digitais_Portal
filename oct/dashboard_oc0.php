<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");

  $agora = now();

  //id_providence: 52 - Responsável pela ocorrência//
/*
  $sql = "SELECT
            	E.status, E.id_company, E.date, E.closure, E.id, C.acron as company_acron,
            	(SELECT U.name
            				FROM ".$schema."oct_rel_events_providence P, ".$schema."users U
            				WHERE
            				P.id_user_resp  = U.id AND
            				P.id_providence = 52   AND
                    P.id_event      = E.id
            	 ORDER BY P.id DESC LIMIT 1) as userresponsavel
            FROM
            	".$schema."oct_events E, ".$schema."company C
            WHERE
              C.id  = E.id_company AND
            	E.id_company = '".$_SESSION['id_company']."' AND
              (E.date BETWEEN '2020-".$agora['mes']."-01 00:00:00' AND '2020-".$agora['mes']."-".$agora['ultimo_dia']." 23:59:59' OR E.active = 't')
            ORDER BY date ASC";
*/


  $sql = "SELECT
                	E.status, E.id_company, C.acron as company_acron, E.date, E.closure, E.id,
                	(SELECT U.name
                	 FROM ".$schema."oct_rel_events_providence P, ".$schema."users U
                	 WHERE P.id_user_resp = U.id AND id_providence = 52 AND id_event = E.id
                	 ORDER BY P.id DESC LIMIT 1) as user_resp
          FROM
          	".$schema."oct_events E
            JOIN ".$schema."company C ON C.id = E.id_company
          WHERE
          	E.id_company = '".$_SESSION['id_company']."' AND (E.date BETWEEN '".$agora['ano']."-".$agora['mes']."-01 00:00:00' AND '".$agora['ano']."-".$agora['mes']."-".$agora['ultimo_dia']." 23:59:59' OR E.active = 't')
          	OR E.id IN
          	(SELECT DISTINCT(PO.id_event) FROM ".$schema."oct_rel_events_providence PO, ".$schema."oct_events OE
          	 WHERE PO.id_providence = 53 AND PO.id_company_requested = '".$_SESSION['id_company']."' AND
          				 PO.id_event = OE.id AND
          				 (OE.date BETWEEN '".$agora['ano']."-".$agora['mes']."-01 00:00:00' AND '".$agora['ano']."-".$agora['mes']."-".$agora['ultimo_dia']." 23:59:59' OR OE.active = 't'))";


  $res = pg_query($sql)or die("SQL Error ".__LINE__."<br>Query: ".print_r_pre($sql));
  while($d = pg_fetch_assoc($res))
  {
    if($d['user_resp']=="")
    {
        $semresp[$d['status']]++;
    }else{
        $resp[$d['user_resp']][$d['status']]++;
    }
      $porstatus[$d['status']]++;
      $porstatuseorgao[$d['company_acron']][$d['status']]++;
    unset($d);
  }
  if(isset($porstatus)){ ksort($porstatus); }
  if(isset($semresp))  { $resp["Sem responsável"] = $semresp; }
?>
<style>

</style>
<section role="main" class="content-body">
  <header class="page-header">
    <h2>Dashboard - Ocorrências</h2>
    <div class="right-wrapper pull-right" style='margin-right:15px;'>
      <ol class="breadcrumbs">
        <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
        <li><a href="oct/index.php">Sistema</a></li>
        <li><span class='text-muted'>Dashboard - Ocorrências</span></li>
      </ol>
      <!--<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>-->
    </div>
  </header>


  <div class="col-md-12">
        <section class="panel box_shadow">
              <header class="panel-heading">
              <div class="panel-actions"></div>
              </header>
              <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-8">
                          <h4>Ocorrências por status e responsável:</h4>
                            <?
                                if(isset($resp) && count($resp))
                                {
                                  echo "<table class='table table-stripped table-hover'>";
                                  echo "<thead><tr>";
                                  echo "<th>Nome</th>";
                                  foreach ($porstatus as $st => $qtd) {
                                      echo "<th nowrap>";
                                          echo $st;
                                      echo "</th>";
                                  }
                                  echo "</tr></thead><tbody>";

                                  foreach ($resp as $uresp => $infos) {
                                          echo "<tr>";
                                          echo "<td>".$uresp."</td>";
                                          foreach ($porstatus as $st => $qtd) {
                                              echo "<td>";
                                                  echo ($infos[$st]!=""?$infos[$st]:"<span class='text-muted'>-</span>");
                                              echo "</td>";
                                          }
                                          echo "</tr>";
                                  }
                                  echo "</tbody>";

                                  echo "<tfoot><tr>";
                                  echo "<td>Total:</td>";
                                  foreach ($porstatus as $st => $qtd){ echo "<td>".$qtd."</td>";}
                                  echo "</tr></tfoot>";

                                  echo "</table>";
                                }else {
                                  echo "<i class='text-muted'>Nenhuma ocorrência.</i>";
                                }


                            ?>
                        </div>
                        <div class="col-sm-4">

                                      <div class="row">
                                        <div class="col-sm-12">
                                                        <div class="well"><span class="pull-right" style="margin-top:-10px"><i><b>Por status</b></i></span></div>
                                        </div>
                                      </div>
                                      <div class="row">
                                        <div class="col-sm-12">
                                                        <section class="panel">
                                                              <div class="panel-body">
                                                                        <?
                                                                          if(isset($porstatus))
                                                                          {
                                                                                $c=0;
                                                                                foreach ($porstatus as $st => $qtd){
                                                                                    if($c++==0){ echo "<div class='row'>";}
                                                                                    echo "<div class='col-sm-6'><div class='h4 text-weight-bold mb-none'>".$qtd."</div><p class='text-xs text-muted mb-none'>".$st."</p></div>";
                                                                                    if($c>1){ echo "</div>"; $c=0;}
                                                                                }
                                                                                if($c>0&&$c<2){ echo "</div>";}
                                                                          }else{ echo "<div class='text-center'><small><i class='text-muted text-center'>Nenhuma ocorrência.</i></small></div>"; }
                                                                        ?>
                                                              </div>
                                                        </section>

                                        </div>
                                      </div>
                                      <div class="row">
                                        <div class="col-sm-12">
                                                  <div class="well"><span class="pull-right" style="margin-top:-10px"><i><b>Por origem</b></i></span></div>
                                        </div>
                                      </div>
                                                  <div class="row">
                                                    <div class="col-sm-12">




<?
                                                      if(isset($porstatuseorgao))
                                                      {
                                                        $arrkey=array_keys($porstatuseorgao);
                                                        if(isset($arrkey) && count($arrkey)==1 && $arrkey[0] = $_SESSION['company_acron'])
                                                        {
                                                          echo "<div class='text-center'><small><i class='text-muted text-center'>Nenhuma ocorrência vinda de outro órgão.</i></small></div>";
                                                        }else{

                                                                echo "<div class='panel-group' id='accordion2'>";

                                                                $x=0;
                                                                foreach ($porstatuseorgao as $orgao => $porstatus)
                                                                {
                                                                  if($x++==0){ $in = "in"; }else{ $in = "";}
                                                                  echo "<div class='panel panel-accordion panel-accordion-primary'>
                                                                    <div class='panel-heading'>
                                                                      <h4 class='panel-title'>
                                                                        <a class='accordion-toggle' data-toggle='collapse'
                                                                            data-parent='#accordion2' href='#".$orgao."' ajax='false'>
                                                                          <i class='fa fa-bar-chart-o'></i> ".$orgao."
                                                                        </a>
                                                                      </h4>
                                                                    </div>
                                                                    <div id='".$orgao."' class='accordion-body collapse ".$in."'>
                                                                      <div class='panel-body'>";

                                                                            $c=0;
                                                                            foreach ($porstatus as $st => $qtd){
                                                                                if($c++==0){ echo "<div class='row'>";}
                                                                                echo "<div class='col-sm-6'><div class='h4 text-weight-bold mb-none'>".$qtd."</div><p class='text-xs text-muted mb-none'>".$st."</p></div>";
                                                                                if($c>1){ echo "</div>"; $c=0;}
                                                                            }
                                                                            if($c>0&&$c<2){ echo "</div>";}

                                                                  echo "</div></div></div>
                                                                        <section>";
                                                                }
                                                          echo "</div>";

                                                        }
                                                    }else{ echo "<div class='text-center'><small><i class='text-muted text-center'>Nenhuma ocorrência.</i></small></div>"; }


?>





</div></div>

                                                  </div>
                    </div>
              </div>
        </section>
  </div>

</section>
