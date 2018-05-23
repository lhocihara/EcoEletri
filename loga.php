<?
  session_start();

  require("conf.php"); // Estabelecendo conexão com o banco
  require("php/funcoes.php"); // Funções php

  $acao = $_POST["hd_ac"];

  switch ($acao) {
    case 'loga':
      if(isset($_POST["txt_usuario"]) && isset($_POST["psw_senha"])){
        $usuario = $_POST["txt_usuario"];
        $senha   = md5($_POST["psw_senha"]);

        logar($usuario,$senha);
      }
      break;

    default:
      deslogar($id);
      break;
  }



?>
