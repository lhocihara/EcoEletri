<?
  // Função que puxa o id do usuário da sessão
  function ver_log(){
    // Verificação - Se existe uma sessão com o id do usuário
    if(isset($_SESSION["login_i"])){
      // Recebendo o id do usuário
      $u_id = $_SESSION["login_i"];
      // Retornando ele para a página
      return $u_id;
    }
    else{
      //Retorna falso caso não for encontrado nenhum
      return false;
    }
  }

  // Função que loga o usuário no sistema
  function logar($email,$senha){
    // Recebendo a variável com os parametros de conexão com o banco de dados
    $conn = $GLOBALS['conn'];

    // Criando e executando o comando que verifica se existe o usuario com o email e senha passados
    $proc  = "select id from clientes where email='$email' and senha='$senha'";
    $resul = mysqli_query($conn,$proc);

    // Verificação - Existe usuário
    if($linha = mysqli_fetch_array($resul)){
      // Iniciando uma sessão que será utilizada para identificação do usuário no sistema
      $_SESSION["login_i"] = $linha["id"];

      if($linha["id"] == 1){
        // Redirecionamento para a página do perfil do usuário
        header("Location:/Ecoeletri/?s=A");
      }
      else{
        // Redirecionamento para a página do perfil do usuário
        header("Location:/Ecoeletri/?s=SC");
      }


    }
    else{
      // Redirecionamento para a página do perfil do usuário, mas com o item "u" = 0 | erro no login
      header("Location:/Ecoeletri/?s=SC&u=0");
    }
  }

  // Função que desloga o usuário do sistema
  function deslogar(){
    //Verificação - se existe uma sessão iniciada
    if(isset($_SESSION["login_i"])){
      //deletando a sessão
      unset($_SESSION["login_i"]);

      // Redirecionamento para a página do perfil do usuário
      header("Location:/Ecoeletri/?");
    }
  }

  // Função que retorna os dados do usuário cadastrados no banco
  function info_cliente(){
    // Recebendo a variável com os parametros de conexão com o banco de dados
    $conn = $GLOBALS['conn'];

    if(isset($_SESSION["login_i"])){
      // Recebendo o id da sessão do usuário
      $u_id = $_SESSION["login_i"];
    }
    else{
      // Redirecionamento para a página do perfil do usuário
      return false;
    }

    // Criando e executando o comando que retorna os dados do cliente que tiver o mesmo id daquele que foi recebido pela sessão
    $proc  = "select * from clientes where id='$u_id'";
    $resul = mysqli_query($conn,$proc);

    // Verificação - se foi encontrado algum usuário cadastrado no banco
    if($linha = mysqli_fetch_array($resul)){
      // Array que recebe os dados do usuário
      $valores = array(
                        'id' => $linha["id"],       // identidade do usuário
                        'nome' => $linha["nome"],   // nome do usuário
                        'email' => $linha["email"], // e-mail do usuário
                        'pergunta' => base64_decode($linha["pergunta"]), //
                        'resposta' => base64_decode($linha["resposta"]) //
                      );
      return $valores; // Retorno dos dados do usuário
    }
    else{
      return false; // Retorno falso
    }
  }
?>
