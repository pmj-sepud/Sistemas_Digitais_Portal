<?
  header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
  header("Cache-Control: post-check=0, pre-check=0", false);
  header("Pragma: no-cache");

  require("../libs/php/funcoes.php");
  require("../libs/php/conn.php");

  extract($_GET);
  $bg           = rand(0,5);
  $timeToChange = 60;
  $agora        = now();

  if($arq!=""){ $monit[] = $arq; }
  else{
        //$monit[] = "waze_stats_04.php";
        $monit[] = "waze_stats_05.php";
        $monit[] = "waze_stats_00.php";
        $monit[] = "waze_stats_03.php";
        $monit[] = "waze_stats_06.php";
        $monit[] = "waze_stats_07.php";
        //$monit[] = "waze_stats_01.php"; // Evolução manhã
        //if($agora['hora']>11){ $monit[] = "waze_stats_02.php"; } //Evolução tarde
  }




  if(!isset($next) || $next >= count($monit)){ $next = 0; }


?>
<script>
  var timeToChange = new Date().getTime() + (<?=$timeToChange;?>*1000);
</script>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
    <title>Monitoramento</title>
    <style>
    html {
            overflow: hidden;
    }
    body{
          color: white;
          background-color: black;
          margin:10px;
          font-family: 'Roboto', sans-serif;
          overflow:hidden;
          }

    #divbg{
          background-image:url('backgrounds/bg_<?=$bg;?>.jpg');
          opacity:0.5;
          position:absolute;
          top:0;
          bottom:0;
          right:0;
          left:0;
          background-repeat: no-repeat;
          background-size: cover;
          -webkit-background-size: cover;
          -moz-background-size: cover;
          -o-background-size: cover;
          z-index:-1;
    }

    #clock
    {
        position: fixed;
        bottom: -30px;
        font-size:100px;
        color:#AAA;
        font-weight:bold;
        opacity:0.2;
        z-index: 99999;
    }
    </style>

  </head>
  <body>
    <div id="divbg"></div>
    <span id="clock"></span>
    <div  id="conteudo"></div>



          <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
          <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
          <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
          <script src="../assets/javascripts/jquery.countdown-2.1.0/jquery.countdown.min.js"></script>
          <script src="https://code.highcharts.com/highcharts.js"></script>

          <script>

                $("#conteudo").hide();

                $(document).ready(function(){ $("#conteudo").load("<?=$monit[$next];?>").fadeIn(2000); });

<? if($acao!="vis"){ ?>
                $('#clock').countdown(timeToChange, function(event)
                {
                      $(this).html(event.strftime('%S'));
                      if(event.elapsed)
    		              {
                            $("#clock").css({"opacity":"0.3", "font-size":"24px", "bottom":"0px"});
                            $("#clock").html("Aguarde, carregando próximo monitoramento...");
                            window.location.href = 'exec.php?next=<?=($next+1);?>';
                      }else{
                            if(event.strftime('%S') == '10'){ $("#clock").css({"opacity":"0.4","font-size":"300px","bottom":"-100px"}); }
                            if(event.strftime('%S') == '07'){ $("#clock").css("color", '#FFAA00'); }
                            if(event.strftime('%S') == '04')
        			              {
                              $("#clock").css("color", '#FF9999');
                              $("#conteudo").fadeOut(5000);
                            }
                      }
                });
<? } ?>
          </script>

  </body>
</html>
