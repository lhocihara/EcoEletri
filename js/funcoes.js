function verifica_campos(valor_1, valor_2){
  if(valor_1 != valor_2){
    alert("A senha e a confirmação da senha precisam ser iguais.");
    return false;
  }
  else{
    return true;
  }
}


function MudarCor(seletor){
  seletor.className = "bandeiras " +seletor.options[seletor.selectedIndex].className;
}

function box_log(){
  if(document.getElementById("box_login").className == "ap_box_log"){
    document.getElementById("box_login").className = "np_";
  }
  else {
    document.getElementById("box_login").className = "ap_box_log";
  }
}

function MudarAba(){
  $(document).ready(function(){
 		$(".aba").click(function(){
 			$(".aba").removeClass("selected");
 			$(this).addClass("selected");
 			var indice = $(this).parent().index();
 			indice++;
 			$("#aba_conteudo .conteudo").hide();
 			$("#aba_conteudo .conteudo:nth-child("+indice+")").show();
		});
  });
}

function MudarAbaAtualizacao(aba){
  $("#aba_conteudo .conteudo:nth-child("+aba+")").show();
  $(".abas li:nth-child("+aba+") .conteudo").addClass("selected");
}

function verificaCampos(){
		var preenchidos = 0;
		for(var i = 0; i<=6; i++)
		{
			var mes = (document.getElementById("cmb_mes_"+i) != null) ? document.getElementById("cmb_mes_"+i).value : "";
			var ano = (document.getElementById('nmb_ano_'+i) != null) ? document.getElementById("txt_ano_"+i).value : "";
			var bandeira = (document.getElementById("cmb_band_"+i) != null) ? document.getElementById("cmb_band_"+i).value : "";
			var kwh = (document.getElementById("nmb_con_"+i) != null) ? document.getElementById("txt_con_"+i).value : "";

			if((mes!='')&&(ano!='')&&(bandeira!='')&&(kwh!=''))
			{
				preenchidos++;
			}
		}

		if(preenchidos < 2)
		{
			alert("Preencha no mínimo as informações de dois meses.");
			return false;
		}
		else
		{
			return true;
		}
	}
