<?
  // Configurando a página em codificação UTF-8
  header("content-type: text/html; charset=UTF-8");

  // Estabelecendo
  $conn = mysqli_connect("localhost","root","1@Minhafamilia") or die ("Erro ao acessar o servidor.");
  $banco = mysqli_select_db($conn,"banco_pi1") or die ("Erro ao acessar o banco de dados");

  mysqli_set_charset($conn,'utf8');

?>
