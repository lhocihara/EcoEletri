<?

  $msg_cadastro = $msg_historico = "<br>";

  if($pg_include_manutencao){
    include("manutencao.html");
    exit;
  }

  if($usuario = ver_log()){
    $valores  = info_cliente();
    $nome     = $valores["nome"];
    $email    = $valores["email"];
    $pergunta = $valores["pergunta"];
    $resposta = $valores["resposta"];
  //print_r($valores);

    echo '<h1>Sua Conta</h1>
          <h2>
            Veja aqui seus dados, seu histórico de consumo.
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

      case 'excl':
        $numero_aba = 2;
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
        else {
          $msg_historico = "Selecione ao menos <b>1</b> registro para exclui-lo.";
        }
    }

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
          					<span>Histórico de Consumo</span>
          				</div>
          			</li>
                <a href="loga.php"><input type="button" style="position:absolute;right:0;" value="Sair"></a>
          		</div>
          	</div>
          	<div id="aba_conteudo">
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
          		</div>
              <div class="conteudo">
                <br>
                <div class="msg_erro">'.$msg_historico.'</div>
                <h3 style="margin:0;text-align:center">Histórico de Consumo</h3>
                  <table class="box_divisoria" rules="cols" cellpadding="8px" style="border-bottom: 1px solid #4CAF50;">
                    <tr height="40px">
                      <th width="84px">Mês/Ano</th>
                      <th width="68px">kWh</th>
                      <th width="117px">Valor sem acréscimo</th>
                      <th width="117px">Acréscimo</th>
                      <th width="118px">Valor com acréscimo</th>
                      <th width="50px">Excluir Registro</th>
                    </tr>
                  </table>';

                  $proc = "select * from consumo where cliente = '$usuario'";

                  $query_proc = mysqli_query($conn,$proc);

                  $qtd_linhas = mysqli_num_rows($query_proc);

                  if ($qtd_linhas > 5) {
                    $tam = "691";
                  }
                  else {
                    $tam = "674";
                  }

            echo '<form name="form_excl" method="post">
                    <input type="hidden" name="hd_ac" value="excl">
                    <div style="width:'.$tam.'px;height:267px;overflow-y:auto;margin-left:calc(50% - 337px);">
                      <table class="box_divisoria" rules="all" cellpadding="8px" width="100%" style="border-top: 0px solid #4CAF50;">';
                        $taxa_fixa = 0.337; // Taxa mínima (em reais), cobrada pela ANEEL

                        if($linha = mysqli_num_rows($query_proc)){
                          while ($linha = mysqli_fetch_array($query_proc)) {
                            $rg_id = $linha["id"];
                            $rg_ano = $linha["ano"];
                            $rg_mes = $linha["mes"];
                            $rg_con = $linha["consumo"];
                            $rg_ban = $linha["bandeira"];
                            $rg_acr = $linha["acrescimo"];

                            $query = "select * from bandeiras where id = ".$rg_ban;
                            $resul = mysqli_query($conn,$query);

                            if($linha = mysqli_fetch_array($resul)){
                              //$bandeiras_usadas[$i] = $linha["tipo"];
                              $taxa = $linha["acrescimo"];
                            }
                            else{
                              $taxa = null;
                            }

                            // Processamentos de dados
                            $acrescimo_valor = $taxa * $rg_con;            // Calculo do acrescimo para cada iten

                            $valor_sem_acrescimo = ($rg_con * $taxa_fixa); // Calcula o valor pago pelo cliente em cada registro
                            $valor_com_acrescimo = $valor_sem_acrescimo + $acrescimo_valor; // Calcula o valor pago pelo cliente em cada registro

                            // Lista de id / mes - para exibir o mes do registro
                            $lista_meses = [
                                            "1" => "Janeiro",
                                            "2" => "Fevereiro",
                                            "3" => "Março",
                                            "4" => "Abril",
                                            "5" => "Maio",
                                            "6" => "Junho",
                                            "7" => "Julho",
                                            "8" => "Agosto",
                                            "9" => "Setembro",
                                            "10" => "Outubro",
                                            "11" => "Novembro",
                                            "12" => "Dezembro"
                                        ];

                            echo "<tr>
                                    <td width='85px'>" . $lista_meses[$rg_ban] ."<br>". $rg_ano. "</td>
                                    <th width='70px'>" . $rg_con             . "</th>
                                    <td width='120px' align='right'>". str_replace(".",",",number_format(round($valor_sem_acrescimo,2),2)) . "</td>
                                    <td width='120px' align='right'>". str_replace(".",",",number_format(round($acrescimo_valor,2),2))     . "</td>
                                    <td width='120px' align='right'>". str_replace(".",",",number_format(round($valor_com_acrescimo,2),2)) . "</td>
                                    <th width='66px'><input type='checkbox' name='chk_excluir[]' value='$rg_id'></th>
                                  </tr>";
                          }
                        }
                        else{
                            echo "<tr>
                                    <th colspan='6'>Não foi encontrado nenhum registro no seu histórico</th>
                                  </tr>";
                        }
    echo '            </table>
                    </div>
                    <center>';

    echo ($linha = mysqli_num_rows($query_proc)) ? '<input type="submit" name="btn_excl" value="Excluir Registros Selecionados"></center>' : null;

    echo '          </form>
                  <br>
            		</div>
            	</div>
            </div>';

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
