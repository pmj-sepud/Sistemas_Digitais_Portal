<div class="row">
   <div class="col-md-12">
      <?
            $sql = "SELECT id, id_father, acron, name,
                           (SELECT count(*) FROM {$schema}users U WHERE U.id_company = C.id)     AS qtd_func,
                           (SELECT count(*) FROM {$schema}users U WHERE U.id_company = C.id_father) AS qtd_func_setor_pai,
                           (SELECT count(*) FROM {$schema}users U WHERE U.id_company is null)    AS qtd_func_sem_setor
                    FROM {$schema}company C WHERE C.id_father = '{$_GET['id']}' ORDER BY C.name ASC";
            $res = pg_query($sql)or die("Error ".__LINE__."<br>SQL: {$sql}");
            if(pg_num_rows($res))
            {
               echo "<table class='table table-stripped' id='tabela_dinamica3'>";
               echo "<thead>";
                  echo "<tr>";
                     echo "<th>#</th>";
                     echo "<th>Apelido</th>";
                     echo "<th>Setor</th>";
                     echo "<th>Qtd. Funcionários</th>";
                     echo "<th class='text-center'><i class='fa fa-cogs'></i></th>";
                  echo "</tr>";
               echo "</thead>";
               echo "<tbody>";
               while($setor = pg_fetch_assoc($res)){
                  $qtd_func_setor_pai = $setor['qtd_func_setor_pai'];
                  $qtd_func_sem_setor = $setor['qtd_func_sem_setor'];
                  echo "<tr>";
                     echo "<td class='text-muted'><small>".$setor['id']."</small></td>";
                     echo "<td>".$setor['acron']."</td>";
                     echo "<td>".$setor['name']."</td>";
                     echo "<td>".$setor['qtd_func']."</td>";
                     echo "<td class='actions text-center'>";
                       if(check_perm("2_20","U")){ echo "<a href='configs/company_FORM.php?e_setor=sim&id=".$setor['id']."'><i class='fa fa-pencil'></i></a>"; }else{ echo " <span class='text-muted'><i class='fa fa-lock'></i></span>";}
                     echo "</td>";
                  echo "</tr>";
               }
               echo "</tbody>";
               echo "</table>";
            }else{
               echo "<div class='alert alert-warning text-center'>Nenhum setor cadastrado.</div>";
            }

            if($qtd_func_sem_setor > 0 || $qtd_func_setor_pai > 0)
            {
               echo "<div class='alert alert-warning text-center'><b>ATENÇÃO</b> Há funcionário(s) sem setor registrado <i><b>(total: {$qtd_func_sem_setor})</b></i> ou registrado no órgão pai <i><b>(Total: {$qtd_func_setor_pai})</b></i>.</div>";
            }
      ?>
   </div>
</div>
