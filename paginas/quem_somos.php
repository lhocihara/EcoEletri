<?

  $msg_erro = "<br>";
  if($pg_include_manutencao){
    include("manutencao.html");
    exit;
  }
  
  echo '<h1>Quem somos nós?</h1>
        <h2>
          Conheça a história da equipe que está por de trás do projeto da Ecoeletri.
        </h2>';

  echo "<div class='box_divisoria' id='box_objetivo' style='text-align:left;'>
          <h3>A história</h3>
          <p>
            Fundada em 2016 por um grupo de amigos com o intuito de ajudar no cálculo de gastos  elétricos, a EcoEletri tem como principal missão auxiliar com a redução de gastos mensais de energia elétrica em todo país.
          </p>
        </div>
        <br>
        <div class='box_divisoria' id='box_projeto' style='text-align:left;'>
          <h3>O projeto</h3>
          <p>
           A EcoEletri é uma empresa sustentável e que ajuda na implantação de projetos sustentáveis em toda Grande São Paulo. Além de disponibilizar a calculadora de gastos com eletricidade, faz palestras e atividades ao ar livre para toda a população, com o intuito de aproximar as pessoas da natureza educando-as sobre os gastos de energia e a importância dela em nossas vidas.
          </p>
        </div>
        <br>
        <div class='box_divisoria' id='box_equipe' style='text-align:left;'>
          <h3>A equipe</h3>
          <p>
            Atualmente a empresa conta com os fundadores Beatriz Lira, Juliana Godoy, Daniel Menegasso e Lucas Kenji, estudantes do primeiro semestre de Ciências da Computação na Universidade Municipal de São Caetano do Sul.
          </p>
        </div>";
?>
