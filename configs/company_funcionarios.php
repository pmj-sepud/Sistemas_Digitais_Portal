<?
session_start();
require_once("../libs/php/funcoes.php");
require_once("../libs/php/conn.php");

if(check_perm("1_1","U")){
?>
<style>.linkFunc:hover{ cursor: pointer; }</style>
<script>
   $(".linkFunc").click(function(){ $('#wrap').load("usuarios/FORM.php?id="+$(this).attr("id"));});
</script>
<? }else{ ?>
<style>.linkFunc:hover{ cursor: not-allowed; }</style>
<? } ?>

<div class="row">
   <div class="col-md-12">
      <?
            if($_GET['e_setor']=="sim"){
               $sqlfiltro = "({$_GET['id']})";
            }else {
               $sqlfiltro = "(SELECT id FROM {$schema}company WHERE id_father = {$_GET['id']})";
            }
            $sql = "SELECT U.id, U.name, C.name as company, C.acron FROM {$schema}users U
                                 JOIN {$schema}company C ON C.id = U.id_company
                                 WHERE U.id_company in {$sqlfiltro}";
            $res = pg_query($sql)or die("Error ".__LINE__."<br>SQL: {$sql}");
            if(pg_num_rows($res))
            {
               echo "<table class='table table-stripped' id='tabela_dinamica2'>";
               echo "<thead>";
                  echo "<tr>";
                     echo "<th>#</th>";
                     echo "<th>Funcionário</th>";
                     echo "<th>Setor</th>";
                     echo "<th>Apelido</th>";
                  echo "</tr>";
               echo "</thead>";
               echo "<tbody>";
               while($setor = pg_fetch_assoc($res)){
                  echo "<tr id='{$setor['id']}' class='linkFunc'>";
                     echo "<td class='text-muted'><small>{$setor['id']}</small></td>";
                     echo "<td>{$setor['name']}</td>";
                     echo "<td>{$setor['company']}</td>";
                     echo "<td>{$setor['acron']}</td>";
                  echo "</tr>";
               }
               echo "</tbody>";
               echo "</table>";
            }else{
               echo "<div class='alert alert-warning text-center'>Nenhum funcionário cadastrado.</div>";
            }
      ?>
   </div>
</div>
