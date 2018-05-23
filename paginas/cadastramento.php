<?

  

  if($pg_include_manutencao){
    include("manutencao.html");
    exit;
  }

  echo '<h1>Cadastramento</h1>
        <h2>
          Cadastre-se aqui para poder utilizar funções exclusivas.
        </h2>';

  $usuario = $email = $conf_email = $senha = $conf_senha = $pergunta = $resposta = "";
  $msg_erro = "<br>";
  // Verificação - Qual a ação será executada
  if(isset($_POST["hd_ac"])){$acao = $_POST["hd_ac"];}else{$acao = "";}

  // Escolha das ações
  switch($acao){
    case 'cads':
    $usuario    = (isset($_POST["txt_usuario"]))    ? $_POST["txt_usuario"]         : null;
    $email      = (isset($_POST["txt_email"]))      ? $_POST["txt_email"]           : null;
    $conf_email = (isset($_POST["txt_conf_email"])) ? $_POST["txt_conf_email"]      : null;
    $senha      = (isset($_POST["psw_senha"]))      ? md5($_POST["psw_senha"])      : null;
    $conf_senha = (isset($_POST["psw_conf_senha"])) ? md5($_POST["psw_conf_senha"]) : null;
    $pergunta   = (isset($_POST["txt_pergunta"]))   ? base64_encode($_POST["txt_pergunta"]) : null;
    $resposta   = (isset($_POST["txt_resposta"]))   ? base64_encode($_POST["txt_resposta"]) : null;

      if(!empty($usuario) || !empty($email) || !empty($conf_email) || !empty($senha) || !empty($conf_senha) || !empty($pergunta) || empty($resposta)){
        $proc_usu = 'select * from clientes where email = "'.$email.'";';
        $query_proc = mysqli_query($conn, $proc_usu);

        $linhas_retornadas = mysqli_num_rows($query_proc);

        if($_POST["txt_email"] != $_POST["txt_conf_email"]){
          $msg_erro = "O e-mail e a confirmação do e-mail devem ser iguais.";
        }
        elseif($_POST["psw_senha"] != $_POST["psw_conf_senha"]){
          $msg_erro = "A senha e a confirmação da senha devem ser iguais.";
        }
        elseif(!$linhas_retornadas){
          $inse_usuario = 'insert into clientes (nome,email,senha, pergunta, resposta) value ("'.$usuario.'","'.$email.'","'.$senha.'","'.$pergunta.'","'.$resposta.'")';

          $query_inse = mysqli_query($conn, $inse_usuario);

          logar($email, $senha);
        }
        else{
          $msg_erro = "Este e-mail já foi cadastrado, digite um e-mail válido.";
        }
      }
      else{
        $msg_erro = "Preencha todos os campos para fazer o cadastro.";
      }

    default:
      echo '<div class="box_divisoria" style="min-height:400px;">
              <br>
              <div class="msg_erro">'.$msg_erro.'</div>
              <table id="form_calc">
                <form action="" method="post">
                  <input type="hidden" name="hd_ac" value="cads">
                  <tr>
                    <td align="right">
                      <label for="txt_nome"><span style="color:red;">*</span>Nome de usuário:</label>
                    </td>
                    <td>
                      <input type="text" name="txt_usuario" placeholder="usuário" value="'.$usuario.'" autocomplete="off" required>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2">
                      <hr>
                    </td>
                  </tr>
                  <tr>
                    <td align="right">
                      <label for="txt_email"><span style="color:red;">*</span>E-mail:</label>
                    </td>
                    <td>
                      <input type="text" name="txt_email" placeholder="e-mail" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}" title="E-mail: exemplo@exemplo.com" value="'.$email.'" autocomplete="off" required>
                    </td>
                  </tr>
                  <tr>
                    <td align="right">
                      <label for="txt_email"><span style="color:red;">*</span>Confirme o E-mail:</label>
                    </td>
                    <td>
                      <input type="text" name="txt_conf_email" placeholder="confirmação de e-mail" autocomplete="off" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}" title="E-mail: exemplo@exemplo.com" required>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2">
                      <hr>
                    </td>
                  </tr>
                  <tr>
                    <td align="right">
                      <label for="psw_senha"><span style="color:red;">*</span>Senha:</label>
                    </td>
                    <td>
                      <input type="password" name="psw_senha" placeholder="senha" autocomplete="off" required>
                    </td>
                  </tr>
                  <tr>
                    <td align="right">
                      <label for="psw_senha"><span style="color:red;">*</span>Confirme a senha:</label>
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
                    <td align="right">
                      <label for="psw_senha"><span style="color:red;">*</span>Pergunta secreta:</label>
                    </td>
                    <td>
                      <input type="text" name="txt_pergunta" placeholder="pergunta secreta" value="'.$pergunta.'" autocomplete="off" required>
                    </td>
                  </tr>
                  <tr>
                    <td align="right">
                      <label for="psw_senha"><span style="color:red;">*</span>Resposta secreta:</label>
                    </td>
                    <td>
                      <input type="text" name="txt_resposta" placeholder="resposta secreta" value="'.$resposta.'" autocomplete="off" required>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2">
                      <hr>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2" >
                      <input type="submit" name="btn_cadastrar" value="Cadastrar">
                    </td>
                  </tr>
                </form>
              </table>
            </div>';
          break;
        }
?>
