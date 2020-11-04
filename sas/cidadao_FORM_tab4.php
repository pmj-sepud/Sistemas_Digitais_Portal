<!-- TAB: HISTÓRICO -->


<div class="row">
    <div class="col-sm-12 text-right">
        <a href='sas/beneficio_FORM.php?id_citizen=<?=$_GET['id'];?>'>
          <button type="button" class="mb-xs mt-xs mr-xs btn btn-primary"><i class="fa fa-plus"></i> Novo atendimento</button>
        </a>
    </div>
</div>

<div class="row" style="margin-top:10px">
    <div class="col-sm-12">
            <?
                  echo "<table class='table table-condensed table-striped'>";
                  echo "<tbody>";
                    echo "<tr><td colspan='7' class='text-right info'><h5>Benefício em aberto:</h5></td></tr>";
                    echo "<tr><th>#</th>
                              <th>Demanda</th>
                              <th>Registrado em</th>
                              <th></th>
                              <th>Cesta</th>
                              <th>Status</th>
                              <th>Ver</th></tr>";

                    if(isset($beneficio_aberto))
                    {

                      for($i=0;$i<count($beneficio_aberto);$i++)
                      {
                          $demand = implode(", ",json_decode($beneficio_aberto[$i]['demand']));
                          $demand = str_replace("alimentacao", "Alimentação",$demand);
                          $demand = str_replace("funeral", "Funeral",$demand);
                          $demand = str_replace("natalidade", "Natalidade",$demand);
                          echo "<tr>";
                            echo "<td><small class='text-muted'>{$beneficio_aberto[$i]['id']}</small></td>";
                            echo "<td>{$demand}</td>";
                            echo "<td>".formataData($beneficio_aberto[$i]['date'],1)."</td>";
                            echo "<td>".formataData($beneficio_aberto[$i]['schedule_date'],1)."</td>";

                            if($beneficio_aberto[$i]['food_count']!="" || $beneficio_aberto[$i]['food_size']!="")
                            {
                              echo "<td>{$beneficio_aberto[$i]['food_count']}x {$beneficio_aberto[$i]['food_size']} Kg</td>";
                            }else{
                              echo "<td> - </td>";
                            }
                            echo "<td>{$beneficio_aberto[$i]['status']}</td>";
                            echo "<td>";
                                echo "<a href='sas/beneficio_FORM.php?id_citizen={$beneficio_aberto[$i]['id_citizen']}&id_request={$beneficio_aberto[$i]['id']}'><button class='btn btn-xs btn-default text-muted'><i class='fa fa-cogs'></i></button></a>";
                            echo "</td>";
                          echo "</tr>";
                      }
                    }else {
                      echo "<tr><td colspan='7' class='text-muted'><small><i>Nenhum benefício em aberto.</i></small></td></tr>";
                    }

                    echo "<tr><td colspan='7' class='text-right warning'><h5>Benefício anteriores:</h5></td></tr>";
                    echo "<tr><th>#</th>
                              <th>Demanda</th>
                              <th>Registrado em</th>
                              <th>Finalizado em</th>
                              <th>Cesta</th>
                              <th>Status</th>
                              <th>Ver</th></tr>";

                    if(isset($beneficio_fechado))
                    {
                          for($i=0;$i<count($beneficio_fechado);$i++)
                          {
                            $demand = implode(", ",json_decode($beneficio_fechado[$i]['demand']));
                            $demand = str_replace("alimentacao", "Alimentação",$demand);
                            $demand = str_replace("funeral", "Funeral",$demand);
                            $demand = str_replace("natalidade", "Natalidade",$demand);
                            echo "<tr>";
                              echo "<td><small class='text-muted'>{$beneficio_fechado[$i]['id']}</small></td>";
                              echo "<td>{$demand}</td>";
                              echo "<td>".formataData($beneficio_fechado[$i]['date'],1)."</td>";
                              echo "<td>".formataData($beneficio_fechado[$i]['date_closed'],1)."</td>";
                              if($beneficio_fechado[$i]['food_count']!="" || $beneficio_fechado[$i]['food_size']!="")
                              {
                                echo "<td>{$beneficio_fechado[$i]['food_count']}x {$beneficio_fechado[$i]['food_size']} Kg</td>";
                              }else{
                                echo "<td> - </td>";
                              }
                              echo "<td>{$beneficio_fechado[$i]['status']}</td>";
                              echo "<td>";
                                echo "<a href='sas/beneficio_FORM.php?id_citizen={$beneficio_fechado[$i]['id_citizen']}&id_request={$beneficio_fechado[$i]['id']}'><button class='btn btn-xs btn-default text-muted'><i class='fa fa-cogs'></i></button></a>";
                              echo "</td>";
                            echo "</tr>";
                          }
                    }else{
                      echo "<tr><td colspan='7' class='text-muted'><small><i>Nenhum benefício ou atendimento realizado anteriormente.</i></small></td></tr>";
                    }
                      echo "</tbody>";
                      echo "</table>";

                ?>
        </fieldset>
    </div>
</div>





<div class="panel-footer text-right" style="margin-right:-25px">
<?
  if($acao=="Atualizar"){
      echo "<small class='text-muted'><i>Cadastrado por <b>{$d['name_user_register']}</b><br><b>{$d['company_user_register']}</b>, em <b>".formataData($d['date'],1)."</b></i></small>";
  }else{
      echo "<small><i><span class='text-muted'>Realizando um novo cadatro.</span><br><span class='text-danger'>Certifique-se que já não foi cadatrado anteriormente.</span></i></small>";
  }
?>
</div>
<?

?>
