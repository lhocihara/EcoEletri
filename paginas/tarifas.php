<?
  

  if($pg_include_manutencao){
    include("manutencao.html");
    exit;
  }

  echo '<h1>Sistema de Cobrança Tarifário - 2016</h1>
        <h2>
          Veja aqui o sistema de cobrança tarifário estipulado em 2013.
        </h2>
        <div class="box_divisoria" style="text-align:left;">
          <h3>Uma breve introdução</h3>
          <p>
            Com o crescimento das indústrias devido aos incentivos para o consumo interno juntamente com a crise hídrica, houve um uso de outras fontes energéticas mais caras, as termelétricas, cujo custo não estava previsto na equação do consumo estável brasileiro.
          </p>
          <p>
            Segundo a Resolução Normativa nº. 547/13 da Agência Nacional de Energia Elétrica – ANEEL, as contas de energia devem ser faturadas de acordo com o Sistema de Bandeiras Tarifárias.
          </p>
          <p>
            As bandeiras determinam se a energia custará mais ou menos, em função das condições de geração de eletricidade. O sistema possui quatro classificações de bandeiras: Verde, Amarela e Vermelha Patamar 1 e Vermelha Patamar 2.
          </p>
          <p>
            O acionamento de cada bandeira tarifária é sinalizado mensalmente pela ANEEL, de acordo com informações prestadas pelo Operador Nacional do Sistema – ONS, conforme a capacidade de geração de energia elétrica do país.
          </p>
        </div>
        <br>
        <table class="box_divisoria" rules="all" cellpadding="8px" align="center">
          <caption>Tabela de acréscimos</caption>
          <tr>
            <td>Verde</td>
            <td>Não existe acréscimo a conta do consumidor</td>
          </tr>
          <tr>
            <td>Amarela</td>
            <td>Acréscimo de R$ 0,015 a cada 1 kWh consumido</td>
          </tr>
          <tr>
            <td>Vermelha (Patamar 1)</td>
            <td>Acréscimo de R$ 0,030 a cada 1 kWh consumido</td>
          </tr>
          <tr>
            <td>Vermelha (Patamar 2)</td>
            <td>Acréscimo de R$ 0,045 a cada 1 kWh consumido</td>
          </tr>
        </table>';
?>
