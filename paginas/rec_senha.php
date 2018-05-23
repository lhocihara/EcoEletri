<?
  
  $msg_erro = "<br>";
  if($pg_include_manutencao){
    include("manutencao.html");
    exit;
  }

  echo '<h1>Recuperar Senha</h1>';

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
        $inse = 'insert into mensagens (nome, email, mensagem) value ("'.$nome.'","'.$email.'","'.$mensa.'")';
        $query_inse = mysqli_query($conn,$inse);

        if ($query_inse == true) {
          $msg_erro = "Mensagem enviada com sucesso.";
        }
        else {
          $msg_erro = "Erro ao enviar a mensagem.";
        }
      }

    case "alte":
        $id_usuario = (isset($_POST["hd_id"]))          ? $_POST["hd_id"]          : header("Location:/Ecoeletri/?s=E");
        $senha      = (isset($_POST["psw_senha"]))      ? md5($_POST["psw_senha"]) : header("Location:/Ecoeletri/?s=E");
        $conf_senha = (isset($_POST["psw_conf_senha"])) ? $_POST["psw_conf_senha"] : header("Location:/Ecoeletri/?s=E");

        $atualiza = 'update clientes set senha = "'.$senha.'" where id = "'.$id_usuario.'"';

        $query_atualiza = mysqli_query($conn, $atualiza);

        switch(mysqli_affected_rows($conn)){
          case 0:
            //A senha foi atualizada com sucesso. (a senha nova era a mesma)

            $proc = "select email, senha from clientes where id = $id_usuario";
            $query_proc = mysqli_query($conn, $proc);

            $linha = mysqli_fetch_array($query_proc);

            $email = $linha["email"];
            $senha = $linha["senha"];

            logar($email,$senha);
            break;

          case 1:
            //A senha foi atualizada com sucesso.

            $proc = "select email, senha from clientes where id = $id_usuario";
            $query_proc = mysqli_query($conn, $proc);

            $linha = mysqli_fetch_array($query_proc);

            $email = $linha["email"];
            $senha = $linha["senha"];

            logar($email,$senha);
            break;

          default:
            echo "<div class='msg_erro'>Erro ao atualizar a senha.</div>
                  <br>
                  <br>
                  <a href='?'>Voltar ao início.</a>
                  ";


            break;
        }

        echo "$msg_erro";
        break;

    case "veri":
        $id_usuario = (isset($_POST["hd_id"]))        ? $_POST["hd_id"]        : header("Location:/Ecoeletri/?s=E");
        $resposta   = (isset($_POST["txt_resposta"])) ? $_POST["txt_resposta"] : header("Location:/Ecoeletri/?s=E");

        $proc = 'select id,resposta from clientes where id = "'.$id_usuario.'"';
        $query_proc = mysqli_query($conn, $proc);

        if (mysqli_num_rows($query_proc)) {
          $linha = mysqli_fetch_array($query_proc);

          $id_usuario = $linha["id"];
          $resposta_banco = base64_decode($linha["resposta"]);

          if ($resposta_banco === $resposta) {
            echo '<h2>
                    Digite uma nova senha.
                  </h2>';

            echo '<form name="form_rec" method="post" onsubmit="return verifica_campos(form_rec.psw_senha.value,form_rec.psw_conf_senha.value)">
                    <input type="hidden" name="hd_ac" value="alte">
                    <input type="hidden" name="hd_id" value="'.$id_usuario.'">
                    <table border="0" id="form_calc">
                      <tr>
                      <td align="right">
                        <label for="psw_senha"><span style="color:red;">*</span>Nova senha:</label>
                      </td>
                      <td>
                        <input type="password" name="psw_senha" placeholder="nova senha" autocomplete="off" required>
                      </td>
                    </tr>
                    <tr>
                      <td align="right">
                        <label for="psw_senha"><span style="color:red;">*</span>Confirme a nova senha:</label>
                      </td>
                      <td>
                        <input type="password" name="psw_conf_senha" placeholder="confirmação de senha" autocomplete="off" required>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="2">
                        <hr>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="2" align="right">
                        <input type="submit" name="btn_enviar" value="Enviar">
                      </td>
                    </tr>
                  </table>';
          }
          else{
            header("Location:/Ecoeletri/?s=E");
          }
        }


        break;

    case "perg":
        $email = (isset($_POST["txt_email"])) ? $_POST["txt_email"] : header("Location:/Ecoeletri/?s=E");

        $proc = 'select id,pergunta from clientes where email= "'.$email.'"';
        $query_proc = mysqli_query($conn, $proc);

        if (mysqli_num_rows($query_proc)) {
          $linha = mysqli_fetch_array($query_proc);

          $id_usuario = $linha["id"];
          $pergunta = base64_decode($linha["pergunta"]);

          echo '<h2>
                  Digite a resposta secreta para poder recuperar a senha.
                </h2>';
          echo '<form * method="post">
                  <input type="hidden" name="hd_ac" value="veri">
                  <input type="hidden" name="hd_id" value="'.$id_usuario.'">
                  <table border="0" id="form_calc">
                  <tr>
                    <td>
                      <label for="txt_resposta">'.$pergunta.'?</label>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <input type="text" name="txt_resposta" placeholder="resposta" title="Digite a resposta secreta." autocomplete="off" required>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2">
                      <hr>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2" align="right">
                      <input type="submit" name="btn_enviar" value="Enviar">
                    </td>
                  </tr>
                </table>';
          break;
        }
        else{
          $msg_erro = "Não existe uma conta com este e-mail.";
        }

    default:
      echo '<h2>
              Digite o e-mail do login para poder recuperar a senha.
            </h2>
            <div class="msg_erro">'.$msg_erro.'</div>
            <form * method="post">
              <input type="hidden" name="hd_ac" value="perg">
              <table border="0" id="form_calc">
                <tr>
                  <td align="right">
                    <label for="txt_email"><span style="color:red;">*</span>E-mail:</label>
                  </td>
                  <td>
                    <input type="text" name="txt_email" placeholder="e-mail" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}" title="E-mail: exemplo@exemplo.com" autocomplete="off" required>
                  </td>
                </tr>
                <tr>
                  <td colspan="2">
                    <hr>
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
