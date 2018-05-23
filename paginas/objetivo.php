<?
  
  $msg_erro = "<br>";
  if($pg_include_manutencao){
    include("manutencao.html");
    exit;
  }

  echo '<h1>O objetivo do projeto</h1>
        <h2>
          PROJETO INTEGRADO - 1º SEMESTRE 2016
        </h2>';

  echo '<div class="box_divisoria" style="text-align:left; height:450px;">
          <p>
            O consumo de energia elétrica brasileiro chegou ao limite da capacidade instalada, agravado pelo período de   seca mais extensa e não previsto pelos agentes econômicos no ano de 2015.
          </p>
          <p>
            Divulgado em março de 2015, o relatório de Informações Gerenciais da ANEEL registrou que apenas 66%, ou o equivalente a 89.813.109kW do consumo do país é produzido de fontes hidrelétricas.
          </p>
          <p>
            Com o crescimento das indústrias devido aos incentivos para o consumo interno juntamente com a crise hídrica, levaram ao uso de outras fontes energéticas mais caras, as termelétricas, cujo custo não estava previsto na equação do consumo estável brasileiro.
          </p>
        </div>';

?>
