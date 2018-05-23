<?

  $msg_cadastro = $msg_configuracao = $msg_cadastro_us = $msg_mensagens = "<br>";

  if($pg_include_manutencao){
    include("manutencao.html");
    exit;
  }

  if($usuario = ver_log()){
    $valores  = info_cliente();
    $id       = $valores["id"];
    $nome     = $valores["nome"];
    $email    = $valores["email"];
    $pergunta = $valores["pergunta"];
    $resposta = $valores["resposta"];

    if ($id != 1) {
      header("Location:../ecoeletri/");
    }

    echo '<script>
              function pergunta(){
                if(confirm("Deseja confirmar essa operação?")){
                  return true;
                } else {
                  return false;
                }
              }
          </script>';

    echo '<h1>Área Administrativa</h1>
          <h2>
            Administre o site a partir daqui.
          </h2>';

    // Verificação - Qual a ação será executada
    if(isset($_POST["hd_ac"])){$acao = $_POST["hd_ac"];}else{$acao = "";}

    $numero_aba = 1;

    // Escolha das ações
    switch($acao){
      case 'atua':
          $atualiza = 'update clientes set ';

          //verificando se vai atualizar o nome
          $atualiza .= (!empty($_POST["txt_usuario"])) ? 'nome = "'.$_POST["txt_usuario"].'" ' : 'nome = "'.$nome.'", ';

          //verificando se vai atualizar o e-mail
          if((!empty($_POST["txt_nov_email"]) && !empty($_POST["txt_conf_email"])) && ($_POST["txt_nov_email"] === $_POST["txt_conf_email"])){
            $atualiza .= ', email = "'.$_POST["txt_nov_email"].'"';
          }

          //verificando se vai atualizar o nome
          if((!empty($_POST["psw_senha"]) && !empty($_POST["psw_conf_senha"])) && ($_POST["psw_conf_senha"] === $_POST["psw_conf_senha"])){
            $atualiza .= ', senha = "'.md5($_POST["psw_senha"]).'"';
          }

          $atualiza .= (isset($_POST["txt_pergunta"])) ? ', pergunta = "'.base64_encode($_POST["txt_pergunta"]).'"' : "";
          $atualiza .= (isset($_POST["txt_resposta"])) ? ', resposta = "'.base64_encode($_POST["txt_resposta"]).'"' : "";

          $atualiza .= " where id='$usuario'";

          $query_atualiza = mysqli_query($conn, $atualiza);

          switch(mysqli_affected_rows($conn)){
            case 0:
              $msg_cadastro = "Nenhum dado cadastral foi atualizado.";
              break;

            case 1:
              $msg_cadastro = "Dados cadastrais atualizados com sucesso.";
              break;

            default:
              $msg_cadastro = "Erro ao atualizar os dados cadastrais.";
              break;
          }

          $valores  = info_cliente();
          $nome     = $valores["nome"];
          $email    = $valores["email"];
          $pergunta = $valores["pergunta"];
          $resposta = $valores["resposta"];
        break;

      case 'alt_band':
        $numero_aba = 2;
        $bandeiras = $_POST["cmb_band"];

        foreach ($bandeiras as $key => $value) {
          $id_banco = ++$key;
          $alt_bandeira = "update bandeiras set acrescimo = $value where id = $id_banco;";

          $query_bandeira = mysqli_query($conn,$alt_bandeira);

          if(mysqli_affected_rows($conn) == 0){
            $msg_configuracao = "Os valores das taxas foram atualizados.";
          }
          elseif (mysqli_affected_rows($conn) == 1) {
            $msg_configuracao = "Os valores das taxas foram atualizados.";

          }
          else {
            $msg_configuracao = "Erro ao atualizar valores das taxas.";
            break;
          }
        }

        break;

      case 'alt_status':
        $numero_aba = 2;

        $paginas = $_POST["cmb_pg"];

        foreach ($paginas as $key => $value) {
          $valores_explodidos = explode("|",$value);

          $id_banco = $valores_explodidos[0];
          $status = $valores_explodidos[1];

          $alt_bandeira = "update paginas set manutencao = $status where id = $id_banco;";

          $query_bandeira = mysqli_query($conn,$alt_bandeira);

          if(mysqli_affected_rows($conn) == 0){
            $msg_configuracao = "O status das páginas foram atualizados.";
          }
          elseif (mysqli_affected_rows($conn) == 1) {
            $msg_configuracao = "O status das páginas foram atualizados.";

          }
          else {
            $msg_configuracao = "Erro ao atualizar o status das páginas.";
            break;
          }

        }
        break;

      case 'pag_us':
        $numero_aba = 3;
        break;

      case 'pag_msg':
        $numero_aba = 4;
        break;

      case "excl_us":
        $numero_aba = 3;
        $id_us = $_POST['hd_id_us'];

        $delete_us = "delete from clientes where id = '$id_us'";

        $query_delete_us = mysqli_query($conn,$delete_us);

        switch(mysqli_affected_rows($conn)){
          case 0:
            $msg_cadastro_us = "Nenhum usuário foi excluído.";
            break;

          case 1:
            $msg_cadastro_us = "Usuário excluído com sucesso.";
            break;

          default:
            $msg_cadastro_us = "Erro ao excluir usuário.";
            break;
        }
        break;

      case 'excl':
        $itens = (isset($_POST["chk_excluir"])) ? $_POST["chk_excluir"] : null;

        $qtd_erros = $qtd_deletes = 0;

        if(count($itens) > 0){
          foreach ($itens as $key => $value) {
              $delete_registro = "delete from consumo where id='$value'";

              $qyery_delete = mysqli_query($conn,$delete_registro);

              if($qyery_delete == true){
                $qtd_deletes++;
              }
          }

          $msg_historico = "$qtd_deletes registro(s) deletado(s)";
        }

    }
    // Menu de abas
    echo '<script>MudarAba();</script>
          <div class="aba_controlador">
          	<div id="aba_menu">
          		<div class="abas">
          			<li>
          				<div class="aba">
          					<span>Perfil</span>
          				</div>
          			</li>
          			<li>
          				<div class="aba">
          					<span>Configurações Gerais</span>
          				</div>
          			</li>
                <li>
          				<div class="aba">
          					<span>Usuários</span>
          				</div>
          			</li>
                <li>
          				<div class="aba">
          					<span>Mensagens</span>
          				</div>
          			</li>
                <a href="loga.php"><input type="button" style="position:absolute;right:0;" value="Sair"></a>
          		</div>
          	</div>';

    // Conteudo das abas
    // Dados do usuario - perfil
    echo '  <div id="aba_conteudo">
          		<div class="conteudo">
                <br>
                <div class="msg_erro">'.$msg_cadastro.'</div>
                <table id="form_calc">
                  <form action="" method="post">
                    <input type="hidden" name="hd_ac" value="atua">
                    <tr>
                      <td align="right">
                        <label for="txt_nome"><span style="color:red;">*</span><span>Nome de usuário:</label>
                      </td>
                      <td>
                        <input type="text" name="txt_usuario" placeholder="usuário" value="'.$nome.'" autocomplete="off" required>
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
                        <input type="text" name="txt_email" placeholder="e-mail" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}"
                         title="E-mail: exemplo@exemplo.com" value="'.$email.'" autocomplete="off" disabled>
                      </td>
                    </tr>
                    <tr>
                      <td align="right">
                        <label for="txt_nov_email">Novo e-mail:</label>
                      </td>
                      <td>
                        <input type="text" name="txt_nov_email" placeholder="novo e-mail" autocomplete="off" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}" title="E-mail: exemplo@exemplo.com">
                      </td>
                    </tr>
                    <tr>
                      <td align="right">
                        <label for="txt_conf_email">Confirme o e-mail:</label>
                      </td>
                      <td>
                        <input type="text" name="txt_conf_email" placeholder="confirmação novo e-mail" autocomplete="off" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}" title="E-mail: exemplo@exemplo.com">
                      </td>
                    </tr>
                    <tr>
                      <td colspan="2">
                        <hr>
                      </td>
                    </tr>
                    <tr>
                      <td align="right">
                        <label for="psw_senha">Alterar a senha:</label>
                      </td>
                      <td>
                        <input type="password" name="psw_senha" placeholder="senha" autocomplete="off">
                      </td>
                    </tr>
                    <tr>
                      <td align="right">
                        <label for="psw_senha">Confirme a senha:</label>
                      </td>
                      <td>
                        <input type="password" name="psw_conf_senha" placeholder="confirmação de senha" autocomplete="off">
                      </td>
                    </tr>
                    <tr>
                      <td colspan="2">
                        <hr>
                      </td>
                    </tr>
                    <tr>
                      <td align="right">
                        <label for="txt_pergunta"><span style="color:red;">*</span>Pergunta secreta:</label>
                      </td>
                      <td>
                        <input type="text" name="txt_pergunta" placeholder="pergunta secreta" value="'.$pergunta.'" autocomplete="off" required>
                      </td>
                    </tr>
                    <tr>
                      <td align="right">
                        <label for="txt_resposta"><span style="color:red;">*</span>Resposta secreta:</label>
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
                      <td colspan="2" align="center">
                        <input type="submit" name="btn_atualizar" value="Atualizar">
                      </td>
                    </tr>
                  </form>
                </table>
          		</div>';
    // Fim da aba dos perfil

    // Configurações gerais
    echo '  <div class="conteudo txt_centro">
              <br>
              <div class="msg_erro">'.$msg_configuracao.'</div>';

    // Inicio coluna 1
    //Atualização das taxas da calculadora.
    $proc_bandeiras = "select * from bandeiras";
    $query_bandeiras = mysqli_query($conn,$proc_bandeiras);

    echo '    <div class="cards_col">
                <div class="box_divisoria card">
                  <form name="form_taxas" method="post">
                    <h5 style="margin:0;">Atualizar taxas das bandeiras</h5>
                    <input type="hidden" name="hd_ac" value="alt_band">
                    <table class="n_borda txt_esquerda" cellpadding="4">';

                    while ($linha_bandeira = mysqli_fetch_array($query_bandeiras)) {
                        $id_bandeira = $linha_bandeira["id"];
                        $tipo_bandeira = $linha_bandeira["tipo"];
                        $acrescimo     = $linha_bandeira["acrescimo"];

                        echo '<tr>
                                <td>'.$tipo_bandeira.'</td>
                                <td><input type="number" id="band'.$id_bandeira.'" name="cmb_band[]" min="0.000" step="any" value="'.$acrescimo.'" required></td>
                              </tr>';
                    }

    echo '            <tr>
                        <td align="right" colspan="2">
                          <input type="submit" name="btn_alt_band" value="Atualizar" align="right">
                        </td>
                      </tr>
                    </table>
                  </form>
                </div>';

    // echo '      <div class="box_divisoria card">
    //               <form>
    //                 <table class="n_borda">
    //                   <tr>
    //                     <td></td>
    //                   </tr>
    //                 </table>
    //               </form>
    //             </div>';
    echo "    </div>";
    //Fim da coluna 1

    //Inicio coluna 2
    //Atualização do status das paginas
    $proc_paginas = "select * from paginas where pagina != 'Área Administrativa'";
    $query_paginas = mysqli_query($conn,$proc_paginas);

    echo '<div class="cards_col">
            <div class="box_divisoria card">
              <form name="form_taxas" method="post">
                <h5 style="margin:0;">Status das Páginas</h5>
                <input type="hidden" name="hd_ac" value="alt_status">
                <table class="n_borda txt_esquerda">';

    while ($linha_bandeira = mysqli_fetch_array($query_paginas)) {
      $id_pagina   = $linha_bandeira["id"];
      $nome_pagina = $linha_bandeira["pagina"];
      $status      = $linha_bandeira["manutencao"];


      echo '      <tr>
                    <td>'.$nome_pagina.'</td>
                    <td>
                      <select id="pg'.$id_pagina.'" name="cmb_pg[]"required>';


      echo ($status == 0) ? '<option value="'.$id_pagina.'|0" selected>On-line</option>' : '<option value="'.$id_pagina.'|0">On-line</option>';
      echo ($status == 1) ? '<option value="'.$id_pagina.'|1" selected>Em manutenção</option>' : '<option value="'.$id_pagina.'|1">Em manutenção</option>';

      echo '          </select>
                    </td>
                  </tr>';
    }

    echo '        <tr>
                    <td align="right" colspan="2">
                      <input type="submit" name="btn_alt_band" value="Atualizar" align="right">
                    </td>
                  </tr>
                </table>
              </form>
            </div>';
    echo "</div>";

    // echo '<div class="cards_col">
    //         <div class="box_divisoria card">
    //           <form name="form_taxas" method="post">
    //             <h5 style="margin:0;">Incluir nova página</h5>
    //             <input type="hidden" name="hd_ac" value="alt_status">
    //             <table class="n_borda">
    //               <tr>
    //                 <td></td>
    //               </tr>
    //             </table>
    //           </form>
    //         </div>
    //       </div>';

  echo "</div>";
  //Fim da aba de configurações gerais

  // Dados de usuários
  echo '<div class="conteudo">
          <br>';

  $acao_usuarios = (isset($_POST["hd_ac_us"])) ? $_POST["hd_ac_us"] : null;

  switch ($acao_usuarios) {
    case "atua_us":
      $id_us = $_POST['hd_id_us'];
      $atualiza = 'update clientes set ';

      //verificando se vai atualizar o nome
      $atualiza .= (!empty($_POST["txt_usuario_us"])) ? 'nome = "'.$_POST["txt_usuario_us"].'" ' : 'nome = "'.$nome.'", ';

      //verificando se vai atualizar o e-mail
      if((!empty($_POST["txt_nov_email_us"]) && !empty($_POST["txt_conf_email_us"])) && ($_POST["txt_nov_email_us"] === $_POST["txt_conf_email_us"])){
        $atualiza .= ', email = "'.$_POST["txt_nov_email_us"].'"';
      }

      //verificando se vai atualizar o nome
      if((!empty($_POST["psw_senha_us"]) && !empty($_POST["psw_conf_senha_us"])) && ($_POST["psw_conf_senha_us"] === $_POST["psw_conf_senha_us"])){
        $atualiza .= ', senha = "'.md5($_POST["psw_senha_us"]).'"';
      }

      $atualiza .= (isset($_POST["txt_pergunta_us"])) ? ', pergunta = "'.base64_encode($_POST["txt_pergunta_us"]).'"' : "";
      $atualiza .= (isset($_POST["txt_resposta_us"])) ? ', resposta = "'.base64_encode($_POST["txt_resposta_us"]).'"' : "";

      $atualiza .= " where id='$id_us'";

      $query_atualiza = mysqli_query($conn, $atualiza);

      switch(mysqli_affected_rows($conn)){
        case 0:
          $msg_cadastro_us = "Nenhum dado cadastral foi atualizado.";
          break;

        case 1:
          $msg_cadastro_us = "Dados cadastrais atualizados com sucesso.";
          break;

        default:
          $msg_cadastro_us = "Erro ao atualizar os dados cadastrais.";
          break;
      }


    case 'vizu_us':
        $id_us = $_POST["hd_id_us"];

        $proc_us = "select * from clientes where id= '$id_us'";
        $query_procu_us = mysqli_query($conn,$proc_us);

        if($linha_proc_us = mysqli_fetch_array($query_procu_us)){
          $nome_us = $linha_proc_us["nome"];
          $email_us = $linha_proc_us["email"];
          $pergunta_us = base64_decode($linha_proc_us["pergunta"]);
          $resposta_us = base64_decode($linha_proc_us["resposta"]);

          echo '<div class="msg_erro">'.$msg_cadastro_us.'</div>
                <table id="form_calc">
                  <form name="form_volt_us" method="post">
                    <input type="hidden" name="hd_ac" value="pag_us">
                  </form>
                  <form action="" method="post">
                    <input type="hidden" name="hd_ac" value="pag_us">
                    <input type="hidden" name="hd_ac_us" value="atua_us">
                    <input type="hidden" name="hd_id_us" value="'.$id_us.'">
                    <tr>
                      <td align="right">
                        <label for="txt_nome"><span style="color:red;">*</span><span>Nome de usuário:</label>
                      </td>
                      <td>
                        <input type="text" name="txt_usuario_us" placeholder="usuário" value="'.$nome_us.'" autocomplete="off" required>
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
                        <input type="text" name="txt_email_us" placeholder="e-mail" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}"
                         title="E-mail: exemplo@exemplo.com" value="'.$email_us.'" autocomplete="off" disabled>
                      </td>
                    </tr>
                    <tr>
                      <td align="right">
                        <label for="txt_nov_email">Novo e-mail:</label>
                      </td>
                      <td>
                        <input type="text" name="txt_nov_email_us" placeholder="novo e-mail" autocomplete="off" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}" title="E-mail: exemplo@exemplo.com">
                      </td>
                    </tr>
                    <tr>
                      <td align="right">
                        <label for="txt_conf_email">Confirme o e-mail:</label>
                      </td>
                      <td>
                        <input type="text" name="txt_conf_email_us" placeholder="confirmação novo e-mail" autocomplete="off" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}" title="E-mail: exemplo@exemplo.com">
                      </td>
                    </tr>
                    <tr>
                      <td colspan="2">
                        <hr>
                      </td>
                    </tr>
                    <tr>
                      <td align="right">
                        <label for="psw_senha">Alterar a senha:</label>
                      </td>
                      <td>
                        <input type="password" name="psw_senha_us" placeholder="senha" autocomplete="off">
                      </td>
                    </tr>
                    <tr>
                      <td align="right">
                        <label for="psw_senha">Confirme a senha:</label>
                      </td>
                      <td>
                        <input type="password" name="psw_conf_senha_us" placeholder="confirmação de senha" autocomplete="off">
                      </td>
                    </tr>
                    <tr>
                      <td colspan="2">
                        <hr>
                      </td>
                    </tr>
                    <tr>
                      <td align="right">
                        <label for="txt_pergunta"><span style="color:red;">*</span>Pergunta secreta:</label>
                      </td>
                      <td>
                        <input type="text" name="txt_pergunta_us" placeholder="pergunta secreta" value="'.$pergunta_us.'" autocomplete="off" required>
                      </td>
                    </tr>
                    <tr>
                      <td align="right">
                        <label for="txt_resposta"><span style="color:red;">*</span>Resposta secreta:</label>
                      </td>
                      <td>
                        <input type="text" name="txt_resposta_us" placeholder="resposta secreta" value="'.$resposta_us.'" autocomplete="off" required>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="2">
                        <hr>
                      </td>
                    </tr>
                    <tr>
                      <td align="center">
                        <input type="button" name="btn_voltar" value="Voltar" onclick="form_volt_us.submit();">
                        <input type="submit" name="btn_atualizar" value="Atualizar">
                      </form>
                      </td>
                      <td align="right">
                        <form name="form_excl_us" method="post" onsubmit="return pergunta();">
                          <input type="hidden" name="hd_ac" value="excl_us">
                          <input type="hidden" name="hd_id_us" value="'.$id_us.'">
                          <input type="submit" name="btn_excluir" value="Excluir">
                        </form>
                      </td>
                    </tr>
                </table>
              </div>';
        }


      break;

    case 'proc_us':
      $proc_us = "select * from clientes where id != '1'";
      $proc_us .= (!empty($_POST["txt_nome_us"])) ? ' and nome = "'.$_POST["txt_nome_us"].'"' : null;
      $proc_us .= (!empty($_POST["txt_email_us"])) ? ' and email = "'.$_POST["txt_email_us"].'"' : null;

      $query_proc_us = mysqli_query($conn, $proc_us);

      echo '<div class="msg_erro">'.$msg_cadastro_us.'</div>
            <table cellpadding="4" rules="all" width="650px" class="txt_esquerda">
              <tr>
                <th align="center">
                  Nome de Usuário
                </th>
                <th align="center">
                  E-mail
                </th>
              </tr>';

      if (mysqli_num_rows($query_proc_us)) {
        while ($linha_proc_us = mysqli_fetch_array($query_proc_us)) {
          $id_us = $linha_proc_us["id"];
          $nome_us = $linha_proc_us["nome"];
          $email_us = $linha_proc_us["email"];


          echo '<tr>
                  <form name="form_idReg'.$id_us.'" method="post">
                    <input type="hidden" name="hd_ac" value="pag_us">
                    <input type="hidden" name="hd_ac_us" value="vizu_us">
                    <input type="hidden" name="hd_id_us" value="'.$id_us.'">
                  </form>
                  <td>
                    <a href="javascript:void();" onclick="form_idReg'.$id_us.'.submit();" style="color:#8A654E; text-decoration:none;">
                      '.$nome_us.'
                    </a>
                  </td>
                  <td>
                    <a href="javascript:void();" onclick="form_idReg'.$id_us.'.submit();" style="color:#8A654E; text-decoration:none;">
                      '.$email_us.'
                    </a>
                  </td>
                </tr>';
        }
      }
      else{
          echo "<tr>
                  <td align='center' colspan='2'>
                    Não foi encontrado nenhum usuário.
                  </td>
                <tr>";
      }

      echo '  <tr>
                <th colspan="2" align="center">
                <form name="form_volt_us" method="post">
                  <input type="hidden" name="hd_ac" value="pag_us">
                  <input type="submit" name="btn_voltar" value="Voltar">
                </form>
              </tr>
            </table>';

      break;

      default:
        echo '<div class="msg_erro">'.$msg_cadastro_us.'</div>
              <div class="box_divisoria card_tb" style="margin-left: calc(50% - 209px);min-height:auto;">
                <table class="n_borda" cellpadding="4">
                  <h5 class="txt_esquerda" style="margin:0;">Procurar usuários</h5>
                  <form action="" method="post">
                    <input type="hidden" name="hd_ac" value="pag_us">
                    <input type="hidden" name="hd_ac_us" value="proc_us">
                    <tr>
                      <td align="right">
                        <label for="txt_email_us">E-mail do usuário:</label>
                      </td>
                      <td>
                        <input type="text" id="txt_email_us" name="txt_email_us" placeholder="e-mail do usuário" value="" autocomplete="off">
                      </td>
                    </tr>
                    <tr>
                      <td align="right">
                        <label for="txt_email_us">Nome do usuário:</label>
                      </td>
                      <td>
                        <input type="text" id="txt_nome_us" name="txt_nome_us" placeholder="nome do usuário" value="" autocomplete="off">
                      </td>
                    </tr>
                    <tr>
                      <td align="right" colspan="2">
                        <input type="submit" name="btn_proc_us" value="Procurar" align="right">
                      </td>
                    </tr>
                  </form>
                </table>
              </div>';
        break;
    }

  echo '</div>';
  // Fim da aba dos Dados de usuarios

  // Mensagens dos usuários
  echo '<div class="conteudo">';
  $acao_mensagem = (isset($_POST["hd_ac_msg"])) ? $_POST["hd_ac_msg"] : null;

  switch ($acao_mensagem) {
    case "excl_msg":
      $id_msg = $_POST['hd_id_msg'];

      $delete_msg = "delete from mensagens where id = '$id_msg'";

      $query_delete_msg = mysqli_query($conn,$delete_msg);

      switch(mysqli_affected_rows($conn)){
        case 0:
          $msg_mensagens = "Nenhuma mensagem foi excluida.";
          break;

        case 1:
          $msg_mensagens = "Mensagem excluida com sucesso.";
          break;

        default:
          $msg_mensagens = "Erro ao excluir a mensagem.";
          break;
      }

    case 'vizu_msg':
        $id_msg = $_POST["hd_id_msg"];

        $proc_msg = "select * from mensagens where id= '$id_msg'";
        $query_procu_msg = mysqli_query($conn,$proc_msg);

        if($linha_proc_msg = mysqli_fetch_array($query_procu_msg)){
          $nome_msg = $linha_proc_msg["nome"];
          $email_msg = $linha_proc_msg["email"];
          $mensagem_msg = $linha_proc_msg["mensagem"];
          $data_msg = date("d/m/Y", strtotime($linha_proc_msg["data"]));

          echo '<br>
                <br>
                <table border="1" rules="all" width="549px" height="400px" cellpadding="4"class="txt_esquerda">
                    <tr  height="20px">
                      <td width="10%">
                        <label for="txt_nome">Nome:</label>
                      </td>
                      <td>
                        '.$nome_msg.'
                      </td>
                    </tr>
                    <tr height="20px">
                      <td>
                        <label for="txt_email">E-mail:</label>
                      </td>
                      <td>
                        '.$email_msg.'
                      </td>
                    </tr>
                    <tr>
                      <td valign="top" height="*">
                        <label for="txt_email">Mensagem:</label>
                      </td>
                      <td align="left" valign="top">
                        '.$mensagem_msg.'
                      </td>
                    </tr>
                    <tr height="20px">
                      <td colspan="2" align="right">
                        Enviado dia '.$data_msg.'
                      </td>
                    </tr>
                    <tr height="20px">
                    <td align="center">
                      <form method="post">
                        <input type="hidden" name="hd_ac" value="pag_msg">
                        <input type="submit" name="btn_voltar" value="Voltar">
                      </form>
                    </td>
                      <td align="right">
                        <form method="post">
                          <input type="hidden" name="hd_ac" value="pag_msg">
                          <input type="hidden" name="hd_ac_msg" value="excl_msg">
                          <input type="hidden" name="hd_id_msg" value="'.$id_msg.'">
                          <input type="submit" name="btn_excluir" value="Excluir">
                        </form>
                      </td>
                    </tr>
                  </table>';
          break;
        }

    case 'proc_msg':
      $proc_msg = "select * from mensagens where 1";
      $proc_msg .= (!empty($_POST["dat_data_msg"])) ? ' and data = "'.$_POST["dat_data_msg"].'"' : null;
      $query_proc_msg = mysqli_query($conn, $proc_msg);

      echo '<br>
            <div class="msg_erro">'.$msg_mensagens.'</div>
            <table cellpadding="4" rules="all" width="650px" class="txt_esquerda">
              <tr>
                <th align="center">
                  Nome
                </th>
                <th align="center">
                  E-mail
                </th>
                <th align="center">
                  data
                </th>
              </tr>';
      if (mysqli_num_rows($query_proc_msg)) {
        while ($linha_proc_msg = mysqli_fetch_array($query_proc_msg)) {
          $id_msg = $linha_proc_msg["id"];
          $nome_msg = $linha_proc_msg["nome"];
          $email_msg = $linha_proc_msg["email"];
          $data_msg = date("d/m/Y", strtotime($linha_proc_msg["data"]));


          echo '<tr>
                  <form name="form_idReg'.$id_msg.'" method="post">
                    <input type="hidden" name="hd_ac" value="pag_msg">
                    <input type="hidden" name="hd_ac_msg" value="vizu_msg">
                    <input type="hidden" name="hd_id_msg" value="'.$id_msg.'">
                  </form>
                  <td>
                    <a href="javascript:void();" onclick="form_idReg'.$id_msg.'.submit();" style="color:#8A654E; text-decoration:none;">
                      '.$nome_msg.'
                    </a>
                  </td>
                  <td>
                    <a href="javascript:void();" onclick="form_idReg'.$id_msg.'.submit();" style="color:#8A654E; text-decoration:none;">
                      '.$email_msg.'
                    </a>
                  </td>
                  <td align="center">
                    <a href="javascript:void();" onclick="form_idReg'.$id_msg.'.submit();" style="color:#8A654E; text-decoration:none;">
                      '.$data_msg.'
                    </a>
                  </td>
                </tr>';
        }
      }
      else{
          echo "<tr>
                  <td align='center' colspan='3'>
                    Não foi encontrado nenhuma mensagem.
                  </td>
                <tr>";
      }

      echo '  <tr>
                <th colspan="3" align="center">
                <form name="form_volt_us" method="post">
                  <input type="hidden" name="hd_ac" value="pag_msg">
                  <input type="submit" name="btn_voltar" value="Voltar">
                </form>
              </tr>
            </table>';

      break;

    default:
      echo '<br>
            <div class="msg_erro">'.$msg_mensagens.'</div>
            <div class="box_divisoria card_tb" style="margin-left: calc(50% - 147px);min-height:auto;">
              <table class="n_borda" cellpadding="4">
                <h5 class="txt_esquerda" style="margin:0;">Procurar Mensagens </h5>
                <form action="" method="post">
                  <input type="hidden" name="hd_ac" value="pag_msg">
                  <input type="hidden" name="hd_ac_msg" value="proc_msg">
                  <tr>
                    <td align="right">
                      <label for="txt_email_us">Data</label>
                    </td>
                    <td>
                      <input type="date" id="dat_data_msg" name="dat_data_msg" autocomplete="off">
                    </td>
                  </tr>
                  <tr>
                    <td align="right" colspan="2">
                      <input type="submit" name="btn_proc_msg" value="Procurar" align="right">
                    </td>
                  </tr>
                </form>
              </table>
            </div>';
      break;
  }
  echo '</div>';
  // Fim da aba de mensagens dos usuarios

  echo "</div>";

  echo "<script>MudarAbaAtualizacao($numero_aba)</script>";
  }
  else{
    if (isset($_GET["u"])) {
      $falha_logar = $_GET["u"];

      switch ($falha_logar) {
        case 0:
          $msg = "Erro ao executar o login.";
        default:
          # code...
          break;
      }
    }

    echo '<h1>Login</h1>
          <h2>
            Entre com seus dados para poder acessar o seu perfil.
          </h2>';

    echo '<center>
            <div class="msg_erro">'.$msg.'</div>
            <form action="loga.php" method="post">
              <input type="hidden" name="hd_ac" value="loga">
              <table width="100px">
                <tr>
                  <td>
                    <label for="txt_usuario"><span style="color:red;">*</span>Usuário:</label>
                  </td>
                  <td colspan="2">
                    <input type="text" name="txt_usuario" placeholder="usuário" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}"   autocomplete="off" required>
                  </td>
                </tr>
                <tr>
                  <td>
                    <label for="psw_senha"><span style="color:red;">*</span>Senha:</label>
                  </td>
                  <td colspan="2">
                    <input type="password" name="psw_senha" placeholder="senha" required>
                  </td>
                </tr>
                <tr>
                  <td colspan="3">
                    <hr>
                  </td>
                </tr>
                <tr>
                  <td colspan="2" >
                    <a href="?s=E" class="fnt_pequena">Esqueci minha senha</a>
                    <br>
                    <a href="?s=CD" class="fnt_pequena">Cadastrar</a>
                  </td>
                  <td width="20px" align="right">
                    <input type="submit" name="btn_entrar" value="Entrar">
                  </td>
                </tr>
              </table>
            </form>
          </center>';
  }
?>
