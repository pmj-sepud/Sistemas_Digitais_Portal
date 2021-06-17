<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");

  $sql = "SELECT * FROM {$schema}logs L
          WHERE L.id_user = '{$_GET['id']}'
          ORDER BY timestamp DESC";
  $res = pg_query($sql)or die("Query error ".__LINE__);


  echo "<div id='logs' class='tab-pane  {$nav_log}'>
        <div class='row'>
           <div class='col-md-12'>";
  echo "<table class='table table-striped' id='tabela'>
        	<thead>
        		<tr>
        			<th>Data</th>
        	      <th>IP</th>
        			<th>Módulo</th>
        			<th>Ação</th>
        			<th>Detalhamento</th>
        		</tr>
        	</thead>
         <tbody>";

  while($l = pg_fetch_assoc($res))
  {
     unset($data);
     $data = formataData($l['timestamp'],1);
     $data = explode(" ",$data);
     echo "<tr>";
        echo "<td width='10px' class='text-center' >{$data[0]}<br><small>{$data[1]}</small></td>";
     	  echo "<td >".$l['ip']."</td>";
        echo "<td nowrap>".$l['module']."</td>";
        echo "<td nowrap>".$l['action']."</td>";
        echo "<td width='80%'>".($l['obs']!="Null"?$l['obs']:"")."</td>";
     echo "</tr>";
  }

  echo "</tbody>
      </table>
   </div>
  </div>
</div>";
 ?>
