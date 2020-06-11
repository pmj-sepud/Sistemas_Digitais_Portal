<?
  session_start();
  require_once("../../libs/php/funcoes.php");
  require_once("../../libs/php/conn.php");
  $schema = ($_SESSION['schema']?$_SESSION['schema'].".":"");
  if($_SESSION['rotss_nav_retorno_origem']==""){ $retorno_origem = "ocorrencias.php";}else{ $retorno_origem = $_SESSION['rotss_nav_retorno_origem'];}
  $txt_bread = "Nova ocorrência";

?>
<style>
.tab {
  display: none;
}
.step {
  height: 15px;
  width: 15px;
  margin: 0 2px;
  background-color: #bbbbbb;
  border: none;
  border-radius: 50%;
  display: inline-block;
  opacity: 0.5;
}

/* Mark the active step: */
.step.active {
  opacity: 1;
}

/* Mark the steps that are finished and valid: */
.step.finish {
  background-color: #4CAF50;
}
input.invalid {
  background-color: #ffdddd;
}
</style>
<section role="main" class="content-body">
    <header class="page-header">
      <div class="right-wrapper pull-right" style='margin-right:15px;'>
        <ol class="breadcrumbs">
          <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
          <li><span class='text-muted'>Aplicações</span></li>
          <li><a href='oct/<?=$retorno_origem;?>?filtro_data=<?=$_GET['filtro_data'];?>'>Ocorrências - MOBILE - GM</a></li>
          <li><span class='text-muted'><?=$txt_bread;?></span></li>
        </ol>
      </div>
    </header>


    <?
       echo '<nav id="custom-bootstrap-menu" class="navbar navbar-default">
                <div class="container-fluid">
                      <div class="navbar-header">
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                              <span class="icon-bar"></span>
                              <span class="icon-bar"></span>
                              <span class="icon-bar"></span>
                            </button>
                            <a class="navbar-brand text-muted" href="#"><small><i>Menu de ações:</i></small></a>
                      </div>
          <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">';
              echo "<li><a href='#'><i class='fa fa-file-text-o'></i> <sup><i class='fa fa-search'></i></sup> Visualizar turnos</a></li>";
              echo "<li><a href='#'><i class='fa fa-calendar'></i> <sup><i class='fa fa-plus'></i></sup> Novo turno</a></li>";
            echo '</ul>
          </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
      </nav>';

    ?>

    <section class="panel">

      <header class="panel-heading" style="height:50px">
              <div class='row' style="margin-top:-8px">
                  <div class='col-sm-12 text-right'>
                      Nova ocorrência.
                  </div>
                </div>
      </header>
      <div class="panel-body">

                <div class='row'>
                  <div class="col-md-12">

                    <form id="regForm" action="">
                          <div class="tab">Name:
                          <p><input class="form-control" placeholder="First name..." oninput="this.className = ''"></p>
                          <p><input class="form-control" placeholder="Last name..." oninput="this.className = ''"></p>
                          </div>

                          <div class="tab">Contact Info:
                          <p><input placeholder="E-mail..." oninput="this.className = ''"></p>
                          <p><input placeholder="Phone..." oninput="this.className = ''"></p>
                          </div>

                          <div class="tab">Birthday:
                          <p><input placeholder="dd" oninput="this.className = ''"></p>
                          <p><input placeholder="mm" oninput="this.className = ''"></p>
                          <p><input placeholder="yyyy" oninput="this.className = ''"></p>
                          </div>
                    </form>

                  </div>
                </div>
      </div>


      <footer class="panel-footer">

        <div style="overflow:auto;">
  <div style="float:right;">
    <button class="btn btn-lg btn-primary" type="button" id="prevBtn" onclick="nextPrev(-1)">Anterior</button>
    <button class="btn btn-lg btn-primary" type="button" id="nextBtn" onclick="nextPrev(1)">Proximo</button>
  </div>
</div>


        <div style="text-align:center;margin-top:40px;">
        <span class="step"></span>
        <span class="step"></span>
        <span class="step"></span>
        <span class="step"></span>
        </div>

      </footer>

    </section>
</section>
</form>



<script>
var currentTab = 0; // Current tab is set to be the first tab (0)
showTab(currentTab); // Display the current tab

function showTab(n) {
  // This function will display the specified tab of the form ...
  var x = document.getElementsByClassName("tab");
  x[n].style.display = "block";
  // ... and fix the Previous/Next buttons:
  if (n == 0) {
    document.getElementById("prevBtn").style.display = "none";
  } else {
    document.getElementById("prevBtn").style.display = "inline";
  }
  if (n == (x.length - 1)) {
    document.getElementById("nextBtn").innerHTML = "Enviar";
  } else {
    document.getElementById("nextBtn").innerHTML = "Próximo";
  }
  // ... and run a function that displays the correct step indicator:
  fixStepIndicator(n)
}

function nextPrev(n) {
  // This function will figure out which tab to display
  var x = document.getElementsByClassName("tab");
  // Exit the function if any field in the current tab is invalid:
  if (n == 1 && !validateForm()) return false;
  // Hide the current tab:
  x[currentTab].style.display = "none";
  // Increase or decrease the current tab by 1:
  currentTab = currentTab + n;
  // if you have reached the end of the form... :
  if (currentTab >= x.length) {
    //...the form gets submitted:
    //document.getElementById("regForm").submit();
    return false;
  }
  // Otherwise, display the correct tab:
  showTab(currentTab);
}

function validateForm() {
  // This function deals with validation of the form fields
  var x, y, i, valid = true;
  x = document.getElementsByClassName("tab");
  y = x[currentTab].getElementsByTagName("input");
  // A loop that checks every input field in the current tab:
  for (i = 0; i < y.length; i++) {
    // If a field is empty...
    if (y[i].value == "") {
      // add an "invalid" class to the field:
      y[i].className += " invalid";
      // and set the current valid status to false:
      valid = false;
    }
  }
  // If the valid status is true, mark the step as finished and valid:
  if (valid) {
    document.getElementsByClassName("step")[currentTab].className += " finish";
  }
  return valid; // return the valid status
}

function fixStepIndicator(n) {
  // This function removes the "active" class of all steps...
  var i, x = document.getElementsByClassName("step");
  for (i = 0; i < x.length; i++) {
    x[i].className = x[i].className.replace(" active", "");
  }
  //... and adds the "active" class to the current step:
  x[n].className += " active";
}
</script>
