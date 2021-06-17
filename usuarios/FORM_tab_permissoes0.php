<?
  session_start();
?>
<form id="userform_perms" name="userform_perms" method="post" action="usuarios/FORM_sql.php">
 <div id="permissoes" class="tab-pane">
    <div class="row">
       <div class="col-md-12">
           <?
              $sql = "SELECT M.id as id_module, M.descrition as module_description, M.module as module_name, M.show_order,
                               S.id as id_perm, S.permission, S.description as perm_description, S.type
                       FROM
                       ".$schema."users_perm_modules M
                       LEFT JOIN ".$schema."users_perm_modules_subgroup S ON S.id_module = M.id
                       ORDER BY M.show_order ASC, S.id ASC";
              $res = pg_query($sql)or die("SQL Error ".__LINE__);
              while ($p = pg_fetch_assoc($res)) {
                   $permissoes[$p['id_module']]['infos']['name']                       = $p['module_name'];
                   $permissoes[$p['id_module']]['infos']['description']                = $p['module_description'];
                   if($p['id_perm'] != "")
                   {
                     $permissoes[$p['id_module']]['perms'][$p['id_perm']]['name']        = $p['permission'];
                     $permissoes[$p['id_module']]['perms'][$p['id_perm']]['description'] = $p['perm_description'];
                     $permissoes[$p['id_module']]['perms'][$p['id_perm']]['type']        = $p['type'];
                   }
              }

 if(isset($permissoes))
 {
 echo "<table class='table table-hover'>";
 foreach($permissoes as $id_module => $dados)
 {
 echo "<tr class='info'>";
 echo "<td class='text-right' width='10px'><small>".$id_module."</small></td>";
 echo "<td colspan='3'><b>".$dados['infos']['name']."</b> - <span class=''>".$dados['infos']['description']."</span></td>";
 echo "</tr>";
 if(isset($dados['perms']))
 {
   foreach ($dados['perms'] as $id_perm => $dados_perm)
   {
       echo "<tr>";
        echo "<td class='text-muted text-right'><small>".$id_module.".".$id_perm."</small></td>";
        echo "<td>".$dados_perm['name']."</td>";
        echo "<td>".$dados_perm['description']."</td>";


           if($dados_perm['type']=="CRUD")
           {
             //0 1 2 3
             //C R U D
             echo "<td class='' width='200px'>";
             echo "<table class='table table-condensed' style='margin-bottom:-5px'>";
             echo "<tr><td class=''>
                                 <div class='checkbox-custom checkbox-default'>
                                     <input type  ='checkbox' class='crud'
                                            value ='1'
                                            id    ='".$id_perm."_c' ".
                                            ($userperms_resum[$id_module."_".$id_perm][0]=="1"?"checked":"").">
                                            <label><span class='text-muted'><small> Incluir</small></label>
                                   </div>
                       </td>
                                   <td class=''>
                                   <div class='checkbox-custom checkbox-default'>
                                               <input type  ='checkbox' class='crud'
                                                      value ='1'
                                                      id    ='".$id_perm."_d' ".
                                                      ($userperms_resum[$id_module."_".$id_perm][3]=="1"?"checked":"").">
                                                      <label><span class='text-muted'><small> Remover</small></label>
                                     </div>
                       </td></tr>";
             echo "<tr><td class=''>
                             <div class='checkbox-custom checkbox-default'>
                                         <input type  ='checkbox' class='crud'
                                                value ='1'
                                                id    ='".$id_perm."_r' ".
                                                ($userperms_resum[$id_module."_".$id_perm][1]=="1"?"checked":"").">
                                                <label><span class='text-muted'><small> Visualizar</small></label>
                               </div>
                       </td>
                                   <td class=''>
                             <div class='checkbox-custom checkbox-default'>
                                         <input type  ='checkbox' class='crud'
                                                value ='1'
                                                id    ='".$id_perm."_u'" .
                                                ($userperms_resum[$id_module."_".$id_perm][2]=="1"?"checked":"").">
                                                <label><span class='text-muted'><small> Atualização</small></label>
                               </div>
                       </td></tr>";
             echo "</table>";
             echo "<input type='hidden' id='".$id_perm."' name='".$id_module."_".$id_perm."' style='margin-top:10px' value='".$userperms_resum[$id_module."_".$id_perm]."'>";

             echo "</td>";
           }

           if($dados_perm['type']=="Bool")
           {

             //echo "<td class='text-center' width='200px'>";
             //echo "<label><input type='checkbox' value='1' id='".$id_perm."' name='".$id_perm."' ".($userperms_resum[$id_module.".".$id_perm]=="1"?"checked":"")." ><span class='text-muted'><small> Ativar</small></label></span>";
             //echo "</td>";

             echo "<td class='' width='200px'>
                     <div class='checkbox-custom checkbox-default' style='margin-left:5px'>
                       <input type  ='checkbox'
                              value ='1'
                              name  ='".$id_module."_".$id_perm."' ".
                              ($userperms_resum[$id_module."_".$id_perm]=="1"?"checked":"").">
                              <label><span class='text-muted'><small> Ativar</small></label>
                     </div>
                   </td>";
           }
           //echo $userperms_resum[$id_module.".".$id_perm];
        echo "</td>";
       echo "</tr>";
   }
 }
 }
 echo "</table>";
 }
 ?>

    <div class="panel-footer"  style="margin-top:20px;height:60px;margin-bottom:10px;">
       <div class="row pull-right">
          <div class="col-md-12">
             <input type='hidden' name='acao' value='permissoes' />
             <input type='hidden' name='id' value='<?=$_GET['id'];?>' />
             <a href='usuarios/index.php'><button type='button' class='btn btn-default loading'>Voltar</button></a>
             <? if(check_perm("1_4")){ echo "<button type='submit' class='btn btn-primary loading'>Atualizar permissões</button>"; } ?>
          </div>
       </div>
    </div>


      </div><!--<div class="col-md-12">-->
    </div><!--<div class="row">-->
 </div><!--<div id="permissoes" class="tab-pane <?=$nav_perm;?>">-->
</form>
