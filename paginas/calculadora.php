  <?
  // Verifica se a página esta em manutenção
  $taxa_fixa = 0.337; // Taxa mínima (em reais), cobrada pela ANEEL
  $m = 6; // Maximo de registros

      // Verificação - Página em manutenção
      if($pg_include_manutencao){
        include("manutencao.html");
        exit;
      }

      echo '<h1>Cálculo de Gastos</h1>
            <h2>
              Simule o seu consumo mensal e se livre do susto no fim do mês.
            </h2>';
    // Verificação - Qual a ação será executada
    if(isset($_POST["hd_ac"])){$acao = $_POST["hd_ac"];}else{$acao = "";}

    // Escolha das ações
    switch($acao){

      // Página que calcula e exibe os dados para o usuário
      case "calc":
        // Verificação - se há uma sessão (se o usuário fez login).
        $u_id = ver_log();

        // Variaveis padrões
        $valor_total = 0;     // Valor total
        $acrescimo_total = 0; // Acréscimo total de todas taxas
        $media_consumo = 0;   // Média de kWh consumido pelo usuário
        $c_rg_valid = 0; // Qtd de registrosv validos
        $c_rg_salvos = 0;

        $qtd_verde = 0;
        $qtd_amarelo = 0;
        $qtd_vermerlho1 = 0;
        $qtd_vermerlho2 = 0;

        $per_verde = 0;
        $per_amarelo = 0;
        $per_vermerlho1 = 0;
        $per_vermerlho2 = 0;

        echo "<table rules='all' cellpadding='5px'>
                <tr>
                  <th>Mês/Ano</th><th>kWh</th><th>Valor sem acréscimo</th><th>Acréscimo</th><th>Valor com acréscimo</th>";
        // Contador para receber os itens do formulário

        for ($i=0; $i < $m; $i++) {
          // Verificação - se existe linha de registro completa
          if((!empty($_POST["cmb_mes_$i"])) && (!empty($_POST["nmb_ano_$i"])) && (!empty($_POST["nmb_con_$i"]))){
            $c_rg_valid++;
            // Recebimento dos dados da variavél do Formulário
            $meses[$c_rg_valid] = $_POST["cmb_mes_$i"];      // Recebendo o mês do registro
            $anos[$c_rg_valid]  = $_POST["nmb_ano_$i"];      // Recebendo o ano do registro
            $bandeiras[$c_rg_valid] = $_POST["cmb_band_$i"]; // Recebendo o bandeira do registro
            $consumo[$c_rg_valid]   = $_POST["nmb_con_$i"];  // Recebendo o consumo(kWh) do registro

            //echo $meses[$i] . "-" .  $anos[$i] . "-" . $bandeiras[$i] . "-" . $consumo[$i] . "<br>";

            $media_consumo += $consumo[$c_rg_valid]; // Soma o consumo de cada registro, para calcular a média consumida

            $query = "select * from bandeiras where id = ".$bandeiras[$c_rg_valid];
            $resul = mysqli_query($conn,$query);

            if($linha = mysqli_fetch_array($resul)){
              //$bandeiras_usadas[$i] = $linha["tipo"];
              $taxa = $linha["acrescimo"];
            }
            else{
              $taxa = null;
            }

            // Verificação - Se bandeira sem cor definida, erro de sistema
            if(is_null($taxa)){
              break;
            }

            // Processamentos de dados
            $acrescimo_valor[$c_rg_valid] = $taxa * $consumo[$c_rg_valid];            // Calculo do acrescimo para cada iten
            $acrescimo_total = $acrescimo_total + ($taxa * $consumo[$c_rg_valid]);    // Calcula o acresimo total

            $valor_sem_acrescimo[$c_rg_valid] = ($consumo[$c_rg_valid] * $taxa_fixa); // Calcula o valor pago pelo cliente em cada registro
            $valor_com_acrescimo[$c_rg_valid] = $valor_sem_acrescimo[$c_rg_valid] + $acrescimo_valor[$c_rg_valid]; // Calcula o valor pago pelo cliente em cada registro

            $valor_total += $valor_com_acrescimo[$c_rg_valid]; // Calcula o valor total pago pelo usuário

            $inse_consumo = " insert into consumo (
                                ano, mes, cliente, consumo, bandeira, acrescimo)
                              values (" .
                                $anos[$c_rg_valid] . "," . $meses[$c_rg_valid] . "," . $u_id . "," .
                                $consumo[$c_rg_valid] . "," . $bandeiras[$c_rg_valid] . "," . $acrescimo_valor[$c_rg_valid].")";

            $query_consumo = mysqli_query($conn, $inse_consumo);

            if(mysqli_affected_rows($conn) != 0){
              $c_rg_salvos++;
            }

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

            // Exibição do [mes] | [taxa de consumo] | [valor pago]
            echo "<tr>
                    <td width='80px'>" . $lista_meses[$meses[$c_rg_valid]]." - ".$anos[$c_rg_valid]. "</td>
                    <th width='70px'>" . $consumo[$c_rg_valid]             . "</th>
                    <td align='right'>". str_replace(".",",",number_format(round($valor_sem_acrescimo[$c_rg_valid],2),2)) . "</td>
                    <td align='right'>". str_replace(".",",",number_format(round($acrescimo_valor[$c_rg_valid],2),2))     . "</td>
                    <td align='right'>". str_replace(".",",",number_format(round($valor_com_acrescimo[$c_rg_valid],2),2)) . "</td>
                  </tr>";
          }
        }
        echo "</table>";

        if(!isset($taxa)){
          echo "Erro ao processar os dados, por favor insira-os novamente.";
        }
        else{
          for ($i=1; $i <= $c_rg_valid; $i++) {
            switch ($bandeiras[$i]) {
              case '1':
                $qtd_verde++;
                break;

              case '2':
                $qtd_amarelo++;
                break;

              case '3':
                $qtd_vermerlho1++;
                break;

              case '4':
                $qtd_vermerlho2++;
                break;
            }
          }

          $per_verde = ($qtd_verde * 100)/$c_rg_valid;
          $per_amarelo = ($qtd_amarelo * 100)/$c_rg_valid;
          $per_vermerlho1 = ($qtd_vermerlho1 * 100)/$c_rg_valid;
          $per_vermerlho2 = ($qtd_vermerlho2 * 100)/$c_rg_valid;


          $media_consumo /= $c_rg_valid;

          for ( $i = 1; $i <= count($consumo); $i++){
            for ($j = 1; $j <= count($consumo); $j++){
              if($consumo[$i] < $consumo[$j]){
                $aux = $consumo[$i];
                $consumo[$i] = $consumo[$j];
                $consumo[$j] = $aux;
              }
            }
          }

          $menor_consumo = $consumo[1];
          $maior_consumo = $consumo[$c_rg_valid];

          if ($u_id) {
            echo "<div class='card_unico'>";

            switch ($c_rg_salvos) {
              case 0:
              echo "<font color='red'>Nenhum registro foi salvo no seu histórico.</font>";
                break;

              case 1:
              echo "<font color='red'>O registro foi salvo no seu histórico.</font>";
                break;

              case ($c_rg_salvos > 1):
                echo "<font color='red'>$c_rg_salvos registro foram salvos no seu histórico.</font>";
                break;

              default:

                break;
            }
            echo "</div>";
          }

          echo '<script type="text/javascript">
                    var options = {
                        responsive:false
                    };

                    var data = [
                        {
                            value: '.$per_vermerlho2.',
                            color: "#A4181B",
                            highlight: "#a22b2d",
                            label: "Vemelho patamar 2"
                        },
                        {
                            value: '.$per_vermerlho1.',
                            color: "#D48462",
                            highlight: "#d59073",
                            label: "Vermelho patamar 1"
                        },
                        {
                            value: '.$per_amarelo.',
                            color:"#FBED9C",
                            highlight: "#f7eaa2",
                            label: "Amarelo"
                        },
                        {
                            value: '.$per_verde.',
                            color:"#A8C46E",
                            highlight: "#adc37e",
                            label: "Verde"
                        }
                    ]

                    window.onload = function(){
                        var ctx = document.getElementById("GraficoDonut").getContext("2d");
                        var PizzaChart = new Chart(ctx).Doughnut(data, options);
                    }
                </script>';

          echo "<div class='box_cards txt_centro'>
                  <div class='box_divisoria card_tb txt_esquerda'>
                    <h2>Dados Gerais</h2>
                    <p>Acréscimo total de R$ ".str_replace(".",",",number_format(round($acrescimo_total,2),2)) ."</p>
                    <p>Média de consumo de ".round($media_consumo,2)." kWh.</p>
                    <p>Maior consumo de ".round($maior_consumo,2)." kWh.</p>
                    <p>Menor consumo de ".round($menor_consumo,2)." kWh.</p>
                  </div>
                  <div class='box_divisoria card_tb'>
                    <h2>Porcentagem de meses/Bandeira</h2>
                    <div class='box-chart'>
                      <canvas id='GraficoDonut'></canvas>
                    </div>
                  </div>
                </div>";
        }
        break;

      default:
        echo '<table id="form_calc">
                <form name="form_calc" method="post"  onsubmit="return verificaCampos();">
                  <input type="hidden" name="hd_ac" value="calc">
                  <tr>
                    <th>
                      Mês
                    </th>
                    <th>
                      Ano
                    </th>
                    <th>
                      Bandeira
                    </th>
                    <th>
                      Consumo
                    </th>
                  </tr>
                  <tr>
                    <th colspan="4">
                      <hr>
                    </th>
                  </tr>';

        for ($i=0; $i < $m; $i++) {
          echo '<tr>
                  <th>
                    <select name="cmb_mes_'.$i.'" id="cmb_mes_'.$i.'">
                      <option value="" selected ></option>
                      <option value="1">Janeiro</option>
                      <option value="2">Fevereiro</option>
                      <option value="3">Março</option>
                      <option value="4">Abril</option>
                      <option value="5">Maio</option>
                      <option value="6">Junho</option>
                      <option value="7">Julho</option>
                      <option value="8">Agosto</option>
                      <option value="9">Setembro</option>
                      <option value="10">Outubro</option>
                      <option value="11">Novembro</option>
                      <option value="12">Dezembro</option>
                    </select>
                  </th>
                  <th>
                    <input type="number" name="nmb_ano_'.$i.'" id="nmb_ano_'.$i.'" value="" min="2013" min="2099" placeholder="Ano">
                  </th>
                  <th>
                    <select name="cmb_band_'.$i.'" id="cmb_band_'.$i.'" class="bandeiras" onchange="MudarCor(this)">
                      <option value="1" class="verde"></option>
                      <option value="2" class="amarelo"></option>
                      <option value="3" class="vermelho1"></option>
                      <option value="4" class="vermelho2"></option>
                    </select>
                  </th>
                  <th>
                    <input type="number" name="nmb_con_'.$i.'" id="nmb_con_'.$i.'" value="" min="1" placeholder="Consumo">
                  </th>
                </tr>';
        }

        echo '    <tr>
                    <td colspan="4">
                      <input type="submit" name="btn_calcular" value="Calcular">
                    </td>
                  </tr>
                </form>
              </table>
              <script type="text/javascript">
                for (var i = 0; i < 6; i++) {
                  MudarCor(document.getElementById("cmb_band_"+i));
                }
              </script>';
    break;
  }
  ?>
