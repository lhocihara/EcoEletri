<?

  $msg_erro = "<br>";
  if($pg_include_manutencao){
    include("manutencao.html");
    exit;
  }

  echo '<h1>Contato</h1>
        <h2>
          Alguma sugestão, ou comentário? Nos mande uma mensagem.
        </h2>';

  // Verificação - Qual a ação será executada
  if(isset($_POST["hd_ac"])){$acao = $_POST["hd_ac"];}else{$acao = "";}

  // Escolha das ações
  switch($acao){

    // Página que calcula e exibe os dados para o usuário
    case "inse":
      $nome  = (isset($_POST["txt_nome"]))   ? $_POST["txt_nome"]   : '';
      $email = (isset($_POST["txt_email"]))     ? $_POST["txt_email"]     : '';
      $mensa = (isset($_POST["txta_mensagem"])) ? $_POST["txta_mensagem"] : '';

      if (!empty($nome) && !empty($email) && !empty($mensa)) {
        $inse = 'insert into mensagens (nome, email, mensagem,data) value ("'.$nome.'","'.$email.'","'.$mensa.'","'.date("Y-m-d").'")';
        $query_inse = mysqli_query($conn,$inse);

        if ($query_inse == true) {
          $msg_erro = "Mensagem enviada com sucesso.";
        }
        else {
          $msg_erro = "Erro ao enviar a mensagem.";
        }
      }

    default:
      echo '<div class="msg_erro">'.$msg_erro.'</div>
            <form method="post">
              <input type="hidden" name="hd_ac" value="inse">
              <table border="0" id="form_calc">
                <tr>
                  <td>
                    <label for="txt_nome"><font color="red">*</font>Nome:</label>
                  </td>
                  <td>
                    <input type="text" id="txt_nome" name="txt_nome" value="" placeholder="Nome Completo"size="50%" autocomplete="off" required>
                  </td>
                </tr>
                <tr>
                  <td>
                    <label for="txt_email"><font color="red">*</font>E-mail:</label>
                  </td>
                  <td>
                    <input type="text" id="txt_email" name="txt_email" value="" placeholder="E-mail" autocomplete="off" size="50%" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" required>
                  </td  >
                </tr>
                <tr>
                  <td valign="top">
                    <label for="txt_email"><font color="red">*</font>Mensagem:</label>
                  </td>
                  <td align="left ">
                    <textarea name="txta_mensagem" rows="12" cols="52%" maxlength="500" style="resize:none;" placeholder="Digite aqui sua mensagem..." required></textarea>
                  </td>
                </tr>
                <tr>
                  <td>

                  </td>
                </tr>
                <tr>
                  <td colspan="2" align="right">
                    <input type="submit" name="btn_enviar" value="Enviar">
                  </td>
                </tr>
            </form>';
      break;
  }
      ?>
