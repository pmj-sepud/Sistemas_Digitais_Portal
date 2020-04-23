<?
error_reporting(0);
$basedir = "/var/www/html";
$envfile = ".env";
if(file_exists($basedir."/".$envfile))
{
  $arq = file($basedir."/".$envfile);
  for($i=0;$i<count($arq);$i++)
  {
    putenv($arq[$i]);
  }
}

require_once("../libs/php/funcoes.php");
require_once("../libs/php/conn.php");
$agora = now();

system("clear");
echo "\n#==========================================#";
echo "\n|   Reciclador tabela de logs              |";
echo "\n#==========================================#";

$c=0;
while($c++ <= 100)
{

        unset($meta,$compressed,$dados,$aux);
        $sql = "SELECT count(DISTINCT timestamp::DATE) as qtd,
        			         (SELECT MIN(timestamp::DATE)  FROM sepud.logs) as data
                FROM sepud.logs";
        $res  = pg_query($sql)or die("SQL Error");
        $info = pg_fetch_assoc($res);

        $txt  =  "\n > Quantidades de dias armazenados: ".$info['qtd'];
        $txt .= "\n > Dia mais antigo armazenado: ".$info['data']."\n------------------------\n";
        echo $txt;
        if($info['qtd'] > 3)
        {

                    $sql = "SELECT * FROM sepud.logs WHERE timestamp BETWEEN '".$info['data']." 00:00:00' AND '".$info['data']." 23:59:59'";
                    $res = pg_query($sql)or die("SQl error");
                    $meta  = number_format(pg_num_rows($res),0,'','.')." registros\n";
                    while($d = pg_fetch_assoc($res))
                    {
                      $dados[] = $d;
                    }
                    $aux = json_encode($dados);


                    $meta .= "Original: ".number_format(strlen($aux),0,'','.')." caracteres, ";
                    $meta .= number_format((strlen($aux) * 8),0,'','.')." bits";

                    $compressed = base64_encode(gzdeflate($aux,9));

                    $meta .= "\nComprimido: ".number_format(strlen($compressed),0,'','.')." caracteres, ";
                    $meta .= number_format((strlen($compressed) * 8),0,'','.')." bits";

                    echo "Data: ".$info['data']."\n";
                    echo $meta;

                    $sqlI = "INSERT INTO sepud.logs_hist(date,archive,metadata)VALUES('".$info['data']."', '".$compressed."', '".$meta."')";
                    pg_query($sqlI)or die("SQL Error");
                    $sqlD = "DELETE FROM sepud.logs WHERE timestamp BETWEEN '".$info['data']." 00:00:00' AND '".$info['data']." 23:59:59'";
                    pg_query($sqlD)or die("SQL Error");

                    logger("Processo automatizado","Logs", "Reclicagem:<br>".nl2br($txt)."<br>".nl2br($meta));

  }else{
    $c = 101;
    echo "\nNao ha logs para ser reciclado";
    logger("Processo automatizado","Logs", "Reclicagem:<br>".nl2br($txt)."<br>Não há logs para ser reciclado");
  }
echo "\n------------------------";
}
echo "\n\n\n";
?>
