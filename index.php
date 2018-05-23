<?
	session_start();
	$pg_manutencao = false;     // Verifica se a página esta em manutenção
	require("conf.php");        // conexão com o banco
	require("php/funcoes.php"); // Funções php

	$id_usuario = ver_log();

	$sigla = (isset($_GET["s"])) ? $_GET["s"] : "";

	$query_proc = "select pagina,caminho,manutencao from paginas where sigla = '$sigla'";
  $resul_proc = mysqli_query($conn,$query_proc);

  if ($linha = mysqli_fetch_array($resul_proc)) {
    $pagina = "- " . $linha["pagina"];
		$caminho = $linha["caminho"];
    $pg_include_manutencao = $linha["manutencao"];
  }
  else {
    $pagina = "";
  }

?>
<!DOCTYPE html>
<html lang="pt-br" class="no-js">
<head>
	<meta charset="UTF-8" />
	<title>EcoEletri <?=$pagina?></title>
	<meta name="author" content="DanMenegasso| Lucas Hocihara" />
	<link rel="page icon" href="eco.ico">
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.3.0/css/font-awesome.min.css" />
	<link rel="stylesheet" type="text/css" href="css/slide.css" />
	<link rel="stylesheet" type="text/css" href="css/page.css" />
	<link rel="stylesheet" type="text/css" href="css/menu.css" />
	<link rel="stylesheet" href="css/principal.css" media="screen" title="no title" charset="utf-8">
	<script src="js/funcoes.js"></script>
	<script src="js/chart.min.js"></script>
	<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
</head>

<body>
	<?
		if(!$id_usuario){
			echo '<div id="box_login" class="np_">
							<span style="position:block;">
								<input type="button" name="btn_fecha" value="X"  class="btn_fecha" onclick="box_log();">
							</span>
							<form action="loga.php" method="post">
								<input type="hidden" name="hd_ac" value="loga">
								<table class="form_login">
									<tr>
										<td>
											<label for="txt_usuario"><span style="color:red;">*</span>Usuário:</label>
										</td>
										<td colspan="2">
											<input type="text" name="txt_usuario" placeholder="usuário" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}" autocomplete="off" required>
										</td>
									</tr>
									<tr>
										<td>
											<label for="psw_senha"><span style="color:red;">*</span>Senha:</label>
										</td>
										<td colspan="2">
											<input type="password" name="psw_senha" placeholder="senha" autocomplete="off" required>
										</td>
									</tr>
									<tr>
										<td colspan="3">
											<hr>
										</td>
									</tr>
									<tr align="left">
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
						</div>';
		}
	?>
	<header>
		<ul class="index">
			<li class="index"><a class="active" href="?">EcoEletri</a></li>
			<li class="index"><a href="?s=O">Objetivo</a></li>
			<li class="index"><a href="?s=C">Calculadora</a></li>
			<li class="index"><a href="?s=T">Tarifas</a></li>
			<li class="index"><a href="?s=CT">Contato</a></li>
			<li class="index"><a href="?s=Q">Quem Somos Nós?</a></li>
			<li class="index"><a class="activez" href="?s=N">News</a></li>
			<?
				if(!$id_usuario){
					$item_final = '<li class="index"><a class="activex" href="javascript:void();" onclick="box_log();">Logar/Cadastrar</li></a>';
					$exibe_box_log = false;
				}
				else{
					if ($id_usuario == "1") {
					  $item_final = '  <li class="index"><a class="activex" href="?s=A">Área Administrativa</a></li>
					               	 </ul>';
					}
					else{
						$item_final = '	 <li class="index"><a class="activex" href="?s=SC">Sua Conta</a></li>
													 </ul>';
					}
					$exibe_box_log = true;
				}

				echo "$item_final";
			?>

			<!-- <li class="index"><a class="activex" href="">Logar</a></li> -->
		</ul>
	</header>
	<?
    if($pg_manutencao){
      include("manutencao.html");
      exit;
    }

		if(isset($caminho)){
			echo '<section id="conteudo">';

			if (file_exists("paginas/$caminho")) {
				include("paginas/$caminho");
			}
			else {
				include("manutencao.html");
			}
			echo '</section>';

		}
    else{
    ?>
			<section>
				<div class="deco deco--title"></div>
				<div id="slideshow" class="slideshow">
					<div class="slide">
						<h2 class="slide__title slide__title--preview">Objetivo</h2>
						<div class="slide__item">
							<div class="slide__inner">
								<img class="slide__img slide__img--small" src="img/small/1.png" alt="objetivo,alvo" />
								<button class="action action--open" aria-label="Saiba Mais"><i class="fa fa-plus"></i></button>
							</div>
						</div>
						<div class="slide__content">
							<div class="slide__content-scroller">
								<img class="slide__img slide__img--large" src="img/1.png" alt="objetivo,alvo" />
								<div class="slide__details">
									<h2 class="slide__title slide__title--main">Objetivo</h2>
									<p class="slide__description">Mas afinal, qual a função deste site?</p>
									<div>
										<span class="slide__price slide__price--large">Saiba</span>
										<a href="?s=O"><button class="button button--buy">Mais</button></a>
									</div>
								</div>
								<!-- /slide__details -->
							</div>
							<!-- slide__content-scroller -->
						</div>
						<!-- slide__content -->
					</div>
					<div class="slide">
						<h2 class="slide__title slide__title--preview">Calculadora</h2>
						<div class="slide__item">
							<div class="slide__inner">
								<img class="slide__img slide__img--small" src="img/small/2.png" alt="calc,calculadora" />
								<button class="action action--open" aria-label="Abrir Calculadora"><i class="fa fa-plus"></i></button>
							</div>
						</div>
						<div class="slide__content">
							<div class="slide__content-scroller">
								<img class="slide__img slide__img--large" src="img/2.png" alt="calc,calculadora" />
								<div class="slide__details">
									<h2 class="slide__title slide__title--main">Calculadora</h2>
									<p class="slide__description">Informe os dados a seguir para efetuar o Calculo</p>
									<div>
										<span class="slide__price slide__price--large">Abrir</span>
										<a href="?s=C"><button class="button button--buy">Calculadora</button></a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="slide">
						<h2 class="slide__title slide__title--preview">Tarifas</h2>
						<div class="slide__item">
							<div class="slide__inner">
								<img class="slide__img slide__img--small" src="img/small/3.png" alt="tarifas,custos" />
								<button class="action action--open" aria-label="Saiba Mais"><i class="fa fa-plus"></i></button>
							</div>
						</div>
						<div class="slide__content">
							<div class="slide__content-scroller">
								<img class="slide__img slide__img--large" src="img/3.png" alt="tarifas,custos" />
								<div class="slide__details">
									<h2 class="slide__title slide__title--main">Tabela de Tarifas</h2>
									<p class="slide__description">Dados utilizados por companhias de energia</p>
									<div>
										<span class="slide__price slide__price--large">Saiba</span>
										<a href="?s=T"><button class="button button--buy">Mais</button></a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="slide">
						<h2 class="slide__title slide__title--preview">Quem somos nós?</h2>
						<div class="slide__item">
							<div class="slide__inner">
								<img class="slide__img slide__img--small" src="img/small/4.png" alt="quem somos nos" />
								<button class="action action--open" aria-label="Saiba Mais"><i class="fa fa-plus"></i></button>
							</div>
						</div>
						<div class="slide__content">
							<div class="slide__content-scroller">
								<img class="slide__img slide__img--large" src="img/4.png" alt="quem somos nos" />
								<div class="slide__details">
									<h2 class="slide__title slide__title--main">Quem somos nós?</h2>
									<p class="slide__description">Pessoas que trabalham para facilitar sua vida</p>
									<div>
										<span class="slide__price slide__price--large">Saiba</span>
										<a href="?s=Q"><button class="button button--buy">Mais</button></a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<button class="action action--close" aria-label="Fechar"><i class="fa fa-close"></i></button>
				</div>
			</section>
			<script src="js/classie.js"></script>
			<script src="js/dynamics.min.js"></script>
			<script src="js/main.js"></script>
			<script>
				(function() {
					document.documentElement.className = 'js';
					var slideshow = new CircleSlideshow(document.getElementById('slideshow'));
				})();
			</script>
		<?
		}
		?>


</body>

</html>
