<?php
	define('HoorayWeb', TRUE);
        $totalItens = 0;
	include_once ("p_settings.php");
	$phpPost = filter_input_array(INPUT_POST);
	session_start();
	if (!empty($phpPost['logoff']) && $phpPost['logoff'] == md5("logoff")) // se logoff solicitado, finaliza a sessão e recarrega a pagina
	{
		session_destroy();
		Header ("Location: " . URLSite);
	}
	if (!empty($_SESSION['bearer'])) // obtem dados dos cliente a partir do token da sessão
	{
		$dadosLogin = login($endPoint['login'], $_SESSION['bearer']);

		if (empty($dadosLogin['ID'])) {
			session_destroy();
			$dadosLogin = ['ID' => -1];
		} else {
			if (!empty($_SESSION['carrinho'])) // se houver carrinho na sessão, o associa ao login do usuario.
			{
				$dadosLoginCarrinhoLogin = ["CarrinhoID" => $_SESSION['carrinho'], "LoginID" => $dadosLogin['ID']];
				$associarCarrinhoLogin = sendRest($endPoint['addlogincarrinho'], $dadosLoginCarrinhoLogin, "PUT");
				if ($associarCarrinhoLogin == true) // se assicoação ocorrer com sucesso, gravar o ID do novo carrinho e apaga a session.
				{
					$dadosLogin['CarrinhoId'] = $_SESSION['carrinho'];
					unset($_SESSION['carrinho']);
				}
			}
		}
	} else {
		$dadosLogin = ['ID' => -1];
	}
	if (!empty($dadosLogin['CarrinhoId'])) {
		$numCarrinho = $dadosLogin['CarrinhoId'];
	} elseif (!empty($_SESSION['carrinho'])) {
		$numCarrinho = $_SESSION['carrinho'];
	} else {
		$numCarrinho = '';
	}
	$URISite = filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_STRING);
	$uriTratada = explode("?", $URISite);
	$paginas = explode("/", $uriTratada[0]);
	if (empty(trim($paginas[1]))) {
		$paginas[1] = "index.php";
	}
	$paginas[1] = strtolower($paginas[1]);
	$paginas[1] = str_replace(" ", "", $paginas[1]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name ="description" content="">
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	<title>Painful Pleasures Brasil</title>
	<link rel="shortcut icon" href="images/favicon/favicon.ico">
	<!--<link rel="apple-touch-icon" href="images/favicon/apple-touch-icon.png">
	<link rel="apple-touch-icon" sizes="72x72" href="images/favicon/apple-touch-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="114x114" href="images/favicon/apple-touch-icon-114x114.png">-->

	<!-- Styles -->
	<link href="/stylesheets/bootstrap.min.css" rel="stylesheet" type="text/css" />
	<link href="/stylesheets/nouislider.min.css" rel="stylesheet" type="text/css" />
	<link href="/stylesheets/jquery-ui.structure.min.css" rel="stylesheet" type="text/css" />
	<link href="/fonts/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	<link href="/stylesheets/slick.css" rel="stylesheet" type="text/css">
	<link href="/stylesheets/personalized.css" rel="stylesheet" type="text/css">
	<link href="/stylesheets/lenord.css" rel="stylesheet" type="text/css">

	<!-- newsletter -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
	<!-- newsletter -->

	<!-- Load jQuery -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>

	<!-- Scripts -->
	<script src="/javascripts/jquery.maskedinput.js"></script>
	<script>
      function enviarNewsLetter()
      {
        $('#retornoNews').html('Enviando...');
        
        var dataString = 'emailInscricao=' + document.getElementById('newsEmail').value;
            dataString += '&postnews=<?= md5("enviarNewsLetter") ?>';

        $.ajax({
            type: "post",
            url: "/_pages/enviarContato.php",
            data: dataString,
            cache: false,
            success: function (retornoPHP) 
            {
                $('#retornoNews').html(retornoPHP);
            }
        });
        
        document.getElementById('newsEmail').value = '';
    }
</script>
	<script type="text/javascript">
            function refreshCarrinho()
            {
                window.location.reload(true); 
            }
		function obterBearer() {
			$('#resultBearer').html('Autenticando...');

			var loginEmail = $('#loginEmail').val();
			var loginSenha = $('#loginSenha').val();

			$.post('_pages/login.php', {
					postlogin: loginEmail,
					postsenha: loginSenha
				},
				function (data) {
					if (data.substring(0, 2) == "!!") {
						$('#resultBearer').html(data.substring(2));

						document.loginForm.loginEmail.value = '';
						document.loginForm.loginSenha.value = '';
					} else {
						$('#resultBearer').html(data);

						document.autForm.submit();
					}
				});
			return false;
		}

		function recuperarSenha() {
			$('#resultRecuperarSenha').html('Solicitando nova senha...');
			var recEmail = $('#recEmail').val();
			$.post('_pages/atualizarCadastro.php', {
					postemail: recEmail,
					postrecsenha: '<?= md5("recuperarsenha") ?>'
				},
				function (dataSenha) {
					$('#resultRecuperarSenha').html(dataSenha);
					document.getElementById("recEmail").value = "";
				});
		}

		function retirarCarrinhoModal(IDProduto) {
			$('#resultDelCarrinho' + IDProduto).html('Retirando do carrinho...');

			$.post('_pages/carrinhoEditar.php', {
					postidproduto: IDProduto,
					postidcarrinho: '<?= $numCarrinho ?>',
					postcarrinho: '<?= md5("editCarrinho") ?>',
					posttipoedicao: '<?= md5("remover") ?>',
					posttipocarrinho: '<?= md5("modal") ?>'
				},
				function (dataCarrinho) {
					if (dataCarrinho.substring(0, 2) == "!!") {
						$('#resultDelCarrinho' + IDProduto).html(dataCarrinho.substring(2));
					} else {
						$('#resultDelCarrinho' + IDProduto).html('Atualizando o carrinho...');
						$('.cart-qtd').each(function(){
							var qtdCart = dataCarrinho.split('<script')[0].trim();
							if(qtdCart){
								$(this).html(dataCarrinho);
							} else {
								$(this).html('0');
							}
						});
						document.getElementById('itemCarinhoModal' + IDProduto).style.display = 'none';
					}
                                        refreshCarrinho();
				});
		}
	</script>
</head>
<body>

	<?php
		$dadosEmpresa = getRest(str_replace("{IDParceiro}","1", $endPoint['dadoscadastrais']));
		$endCadastral = getRest(str_replace("{IDParceiro}","1", $endPoint['endcadastral']));
		$footerData = getRest($endPoint['rodape']);
	?>

	<!-- Master Header -->
	<header id="header">

	<div class="nav">
			<div class="container">
				<div class="row">
					<nav>
					<div id="contact-link">
							<a href="https://pptattoo.com.br/contato">Contato</a>
						</div>'
					<div id="contact-link">
							<a href="http://pptattoo.com.br/ajuda">Ajuda</a>
						</div>
						<div id="contact-link">
						<?php if (!empty($dadosLogin['ID']) && $dadosLogin['ID'] > 0) :
									$nomeUsuario = explode(" ", $dadosLogin['Parceiro']['RazaoSocial']);
							?>
								<a href="/minhaconta"><span class="icon glyphicon glyphicon-user"></span><span class="hidden-xs hidden-sm">Olá <?= $nomeUsuario[0] ?></span></a> 
							<?php else : ?>    
								<a href="#modal-login" data-toggle="modal" id="link-login"><span class="icon glyphicon glyphicon-user"></span><span class="hidden-xs hidden-sm">Login</span></a>
						<?php endif; ?>	
						</div>	


				
					</nav>
				</div>
			</div>
		</div>	

		<!-- Header -->
		<div class="header">
			<div class="container">
				<div class="inner-header">
					<div class="box-logo">
						<a href="/" title="<?= $dadosEmpresa['Fantasia'] ?>">
							<img src="/images/site/logo.png" alt="<?= $dadosEmpresa['Fantasia'] ?>" />
						</a>
					</div>
					<!-- <nav class="main-navigation">
						<a class="menu-mb title-nav">
							<div class="icon">
								<span class="i-bar"></span>
								<span class="i-bar"></span>
								<span class="i-bar"></span>
							</div>
							<span class="text">Menu</span>
						</a>
						<div class="menu-mb-content box-nav">
							<span class="close-menu"></span>
							<div class="ue-menu">
								<ul class="menu-l1 ue-options-menu">
									<?php
										$menuSite = getRest($endPoint['menu']);
										foreach ((array) $menuSite as $secao) :
									?>
										<li class="ue-list-option">
											<a href="/secao?id=<?= $secao['SecaoID'] ?>">
												<span class="icon"><img src="<?= htmlentities($secao['Imagem']) ?>"></span>
												<span class="text"><?= htmlentities($secao['Descricao']) ?></span>
											</a>
											<?php if($secao['Categorias']) : ?>
												<span class="drop-toggle nav-plus"></span>
												<ul class="drop-content menu-l2">
											<?php endif; ?>
											<?php foreach ((array) $secao['Categorias'] as $categoria) : ?>
												<li style="border-bottom: 0.5px solid #666;">
													<a href="/categoria?id=<?= $categoria['ID'] ?>"><?= htmlentities($categoria['Descricao']) ?></a>
													<?php if($categoria['Categorias']) : ?>
														<span class="drop-toggle nav-plus"></span>
														<ul class="drop-content menu-l3">
													<?php endif; ?>
													<?php foreach ((array) $categoria['Categorias'] as $subcategoria) : ?>
															<li><a href="/categoria?id=<?= $subcategoria['ID'] ?>"><?= htmlentities($subcategoria['Descricao']) ?></a></li>
													<?php endforeach; ?>
													<?php if($categoria['Categorias']) : ?>
														</ul>
													<?php endif; ?>
												</li>
												<?php endforeach; ?>
											<?php if($secao['Categorias']) : ?>
												</ul>
											<?php endif; ?>
										</li>
									<?php endforeach; ?>
								</ul>
								<div class="ue-floated-menu">
									<div class="drop-content">
										<ul class="float-l1">
											<?php
												$menuSite = getRest($endPoint['menu']);
												foreach ((array) $menuSite as $secao) :
											?>
												<li class="ue-floated-option">
													<a href="/secao?id=<?= $secao['SecaoID'] ?>">
														<span><?= htmlentities($secao['Descricao']) ?></span>
													</a>
													<?php if($secao['Categorias']) : ?>
														<ul class="float-l2">
													<?php endif; ?>
													<?php foreach ((array) $secao['Categorias'] as $categoria) : ?>
														<li style="border-bottom: 0.5px solid #666">
															<a href="/categoria?id=<?= $categoria['ID'] ?>"><?= htmlentities($categoria['Descricao']) ?></a>
															<?php if($categoria['Categorias']) : ?>
																<ul class="float-l3">
															<?php endif; ?>
															<?php foreach ((array) $categoria['Categorias'] as $subcategoria) : ?>
																	<li style="border-bottom: 0.5px solid #666"><a href="/categoria?id=<?= $subcategoria['ID'] ?>"><?= htmlentities($subcategoria['Descricao']) ?></a></li>
															<?php endforeach; ?>
															<?php if($categoria['Categorias']) : ?>
																</ul>
															<?php endif; ?>
														</li>
														<?php endforeach; ?>
													<?php if($secao['Categorias']) : ?>
														</ul>
													<?php endif; ?>
												</li>
											<?php endforeach; ?>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</nav> -->
					<div class="header-rt">
						<?php if (!in_array($paginas[1], ['carrinho', 'checkout'])) : ?>
								<div class="box-cart">
									<a href="#modal-cart" data-toggle="modal"><span class="cart-qtd"></span></a>
								</div>
						<?php endif; ?>
						<div class="box-search">
							<form name="termobusca" method="get" action="/busca">
								<!-- <span class="btn-search" onclick="document.buscaresponsiva.submit();"></span> -->
								<span class="btn-search" onclick="document.termobusca.submit();"></span>
								<input class="textbox" type="text" name="termobusca" placeholder="O que você procura?" required="required" style="background: url(/images/site/icones/elements/icon-search.png) no-repeat; background-position: 95% 14px; background-size:5%;"/>
							</form>
						</div>
					</div>
				</div>



			</div>


					<nav class="main-navigation">
						<a class="menu-mb title-nav">
							<div class="icon">
								<span class="i-bar"></span>
								<span class="i-bar"></span>
								<span class="i-bar"></span>
							</div>
							<span class="text">Menu +</span>
						</a>
						<div class="menu-mb-content box-nav">
							<span class="close-menu"></span>
							<div class="ue-menu">
								<ul class="menu-l1 ue-options-menu">
									<?php
										$menuSite = getRest($endPoint['menu']);
										foreach ((array) $menuSite as $secao) :
									?>
										<li class="ue-list-option">
											<a href="/secao?id=<?= $secao['SecaoID'] ?>">
												<span class="icon"><img src="<?= htmlentities($secao['Imagem']) ?>"></span>
												<span class="text"><?= htmlentities($secao['Descricao']) ?></span>
											</a>
											<?php if($secao['Categorias']) : ?>
												<span class="drop-toggle nav-plus"></span>
												<ul class="drop-content menu-l2">
											<?php endif; ?>
											<?php foreach ((array) $secao['Categorias'] as $categoria) : ?>
												<li style="border-bottom: 0.5px solid #666;">
													<a href="/categoria?id=<?= $categoria['ID'] ?>" ><?= htmlentities($categoria['Descricao']) ?></a>
													<?php if($categoria['Categorias']) : ?>
														<span class="drop-toggle nav-plus"></span>
														<ul class="drop-content menu-l3">
													<?php endif; ?>
													<?php foreach ((array) $categoria['Categorias'] as $subcategoria) : ?>
															<li><a href="/categoria?id=<?= $subcategoria['ID'] ?>"><?= htmlentities($subcategoria['Descricao']) ?></a></li>
													<?php endforeach; ?>
													<?php if($categoria['Categorias']) : ?>
														</ul>
													<?php endif; ?>
												</li>
												<?php endforeach; ?>
											<?php if($secao['Categorias']) : ?>
												</ul>
											<?php endif; ?>
										</li>
									<?php endforeach; ?>
								</ul>
								<div class="ue-floated-menu">
									<div class="drop-content">
										<ul class="float-l1">
											<?php
												$menuSite = getRest($endPoint['menu']);
												foreach ((array) $menuSite as $secao) :
											?>
												<li class="ue-floated-option">
													<a href="/secao?id=<?= $secao['SecaoID'] ?>">
														<span><?= htmlentities($secao['Descricao']) ?></span>
													</a>
													<?php if($secao['Categorias']) : ?>
														<ul class="float-l2">
													<?php endif; ?>
													<?php foreach ((array) $secao['Categorias'] as $categoria) : ?>
														<li style="border-bottom: 0.5px solid #666">
															<a href="/categoria?id=<?= $categoria['ID'] ?>" ><?= htmlentities($categoria['Descricao']) ?></a>
															<?php if($categoria['Categorias']) : ?>
																<ul class="float-l3">
															<?php endif; ?>
															<?php foreach ((array) $categoria['Categorias'] as $subcategoria) : ?>
																	<li style="border-bottom: 0.5px solid #666"><a href="/categoria?id=<?= $subcategoria['ID'] ?>"  ><?= htmlentities($subcategoria['Descricao']) ?></a></li>
															<?php endforeach; ?>
															<?php if($categoria['Categorias']) : ?>
																</ul>
															<?php endif; ?>
														</li>
														<?php endforeach; ?>
													<?php if($secao['Categorias']) : ?>
														</ul>
													<?php endif; ?>
												</li>
											<?php endforeach; ?>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</nav>


		</div>
	</header>

	<!-- Content -->
	<?php
	if (!empty($phpPost['cadastroResult']) && $phpPost['cadastroResult'] == md5("cadastro")) //se efetuando cadastro, direciona para tela de cadastro.
	{
		$paginas[1] = "minhaconta";
	}

	switch ($paginas[1])
	{
		case "index.php" :
			include_once ("_pages/home.php");
			break;

		case "contato" :
			include_once ("_pages/contato.php");
			break;

		case "blog" :
			include_once ("_pages/blog.php");
			break;

		case "/blogpost" :
			$dadosBlog = getRest($endPoint['blog']);

			$phpGet = filter_input_array(INPUT_GET);
			if (!empty($phpGet)) {
				$IDArtigoBlog = $phpGet[array_keys($phpGet)[0]];
			}

			if (empty($IDArtigoBlog) || !is_numeric($IDArtigoBlog)) {
				include_once ("_pages/404.php");
			} else {
				$detalheArtigo = getRest(str_replace("{IDArtigo}", $IDArtigoBlog , $endPoint['blogartigo']));

				if (empty($detalheArtigo)) {
					include_once ("_pages/404.php");
				} else {
					include_once ("_pages/blogPost.php");
				}
			}
			break;     
       
		case "blogcategoria" :
			$dadosBlog = getRest($endPoint['blog']);

			$phpGet = filter_input_array(INPUT_GET);
			if (!empty($phpGet)) {
				$IDCategoria = $phpGet[array_keys($phpGet)[0]];
			}

			if (empty($IDCategoria) || !is_numeric($IDCategoria)) {
				include_once ("_pages/404.php");
			} else {
				$artigosCategoria = getRest(str_replace(["{IDBlog}", "{IDCategoria}"], [$dadosBlog['ID'], $IDCategoria], $endPoint['blogartigoscat']));

				include_once ("_pages/blogCategoria.php");
			}
			break;

		case "blogbusca" :
			include_once ("_pages/blogBusca.php");
			break;

		case "busca" :
			$tipoBusca = "busca";

			$phpGet = filter_input_array(INPUT_GET);
			if (!empty($phpGet)) {
				$termoBusca = $phpGet[array_keys($phpGet)[0]];
			}
			include_once ("_pages/busca.php");
			break;

		case "secao" :
			$tipoBusca = "secao";

			$phpGet = filter_input_array(INPUT_GET);
			if (!empty($phpGet)) {
				$IDSecao = $phpGet[array_keys($phpGet)[0]];                
			}

			if (!isset($IDSecao) || !is_numeric($IDSecao)) {
				include_once ("_pages/404.php");
			} else {
				include_once ("_pages/busca.php");
			}
			break;

		case "categoria" :
			$tipoBusca = "categoria";

			$phpGet = filter_input_array(INPUT_GET);
			if (!empty($phpGet)) {
				$IDCategoria = $phpGet[array_keys($phpGet)[0]];
			}

			if (!isset($IDCategoria) || !is_numeric($IDCategoria)) {
				include_once ("_pages/404.php");
			} else {
				$categoriaSite = getRest(str_replace("{IDCategoria}", $IDCategoria, $endPoint['categoria']));

				if (empty($categoriaSite)) {
					include_once ("_pages/404.php");
				} else {
					include_once ("_pages/busca.php");
				}
			}
			break;

		case "marca" :
			$tipoBusca = "marca";

			$phpGet = filter_input_array(INPUT_GET);
			if (!empty($phpGet)) {
				$IDMarca = $phpGet[array_keys($phpGet)[0]];
			}

			if (!isset($IDMarca) || !is_numeric($IDMarca)) {
				include_once ("_pages/404.php");
			} else {
				$detalheMarca = getRest(str_replace("{IDMarca}", $IDMarca, $endPoint['detalhesmarca']));

				if (empty($detalheMarca)) {
					include_once ("_pages/404.php");
				} else {
					include_once ("_pages/busca.php");
				}
			}
			break;                                        

		case "produto" :
				$phpGet = filter_input_array(INPUT_GET);
				if (!empty($phpGet)) {
					$IDProduto = $phpGet[array_keys($phpGet)[0]];
				}

				if (empty($IDProduto) || !is_numeric($IDProduto)) {
					include_once ("_pages/404.php");
				} else {
					$dadosProduto = getRest(str_replace("{IDProduto}", $IDProduto, $endPoint['produto']));

					if (empty($dadosProduto)) {
						include_once ("_pages/404.php");
					} else {
						include_once ("_pages/produto.php");
					}
				}
				break;

		case "marcas" :
			include_once ("_pages/marcasLista.php");
			break;

		case "recursos" :
			include_once ("_pages/recursos.php");
			break;

		case "sobreahooray" :
			include_once ("_pages/institucional.php");
			break;

		case "minhaconta" :
			include_once ("_pages/minhaConta.php");
			break;

		case "login" :
			if (!empty($paginas[2]) && strtolower($paginas[2]) == "recuperarsenha") {
				include_once ("_pages/recuperarSenha.php");
			} else {
				include_once ("_pages/404.php");
			}
			break;            

		case "carrinho" :
			include_once ("_pages/carrinho.php");
			break;

		case "checkout" :
			if ($dadosLogin['ID'] > 0) {
				include_once ("_pages/checkout.php");
			} else {
				include_once ("_pages/checkoutLogin.php");
			}
			break;

		case "marketplace" :
			include_once ("_pages/marketplace.php");
			break;

		default:
			include_once ("_pages/404.php");
			break;
		}
	?>        

	<!-- Master Footer -->
	<footer id="footer">

		<!-- Footer -->
		<div class="footer">
			<div class="container">
				<div class="inner-ft">
					<div class="ft-col col-xs-2">
						<div class="logo-ft">
							<?php
								foreach ((array) $footerData as $aboutFt) {
							 		if($aboutFt['Descricao'] == 'Sobre') {
								 		foreach ((array) $aboutFt['Itens'] as $aboutItem) {
											if($aboutItem['Descricao'] == 'sobre') {
							?>
								<p><?= $aboutItem['Html'] ?></p>
							<?php
											}
										}
									}
								}
							?>
						</div>
					</div>		
					<div class="ft-col col-xs-2">
						<div class="logo-ft">
								<?php
									foreach ((array) $footerData as $aboutFt) {
										if($aboutFt['Descricao'] == 'Produtos') {
											foreach ((array) $aboutFt['Itens'] as $aboutItem) {
												if($aboutItem['Descricao'] == 'produtos') {
								?>
									<p><?= $aboutItem['Html'] ?></p>
								<?php
												}
											}
										}
									}
								?>
							</div>
					</div>			
					<div class="ft-col col-xs-2">
						<div class="logo-ft">
								<?php
									foreach ((array) $footerData as $aboutFt) {
										if($aboutFt['Descricao'] == 'Acesso Rápido') {
											foreach ((array) $aboutFt['Itens'] as $aboutItem) {
												if($aboutItem['Descricao'] == 'acessorapido') {
								?>
									<p><?= $aboutItem['Html'] ?></p>
								<?php
												}
											}
										}
									}
								?>
						</div>
					</div>
					<div class="ft-col col-xs-2">
						<div class="logo-ft">
							<?php
								foreach ((array) $footerData as $aboutFt) {
							 		if($aboutFt['Descricao'] == 'Precisa de Ajuda') {
								 		foreach ((array) $aboutFt['Itens'] as $aboutItem) {
											if($aboutItem['Descricao'] == 'precisadeajuda') {
							?>
								<p><?= $aboutItem['Html'] ?></p>
							<?php
											}
										}
									}
								}
							?>
						</div>
					</div>
					<div class="ft-col col-xs-4">
						<div class="logo-ft">
							<p></p>
							<center>
								<h4>FIQUE POR DENTRO!</h4>
								Novidades, Lançamentos e Promoções
								<p></p>
								<form  class="index-search-form">
									<input id="newsEmail" name="newsEmail" type="text" class="search-box" placeholder="&#xf002;  Seu E-mail">
									
									<!--<input name="search" type="text" class="search-box" placeholder="&#xf002;  Seu E-mail" Jorge>--> 
									<!--<input class="form-control" type="text" id="newsEmail" name="newsEmail" placeholder="Digite seu e-mail" />-->
  									<button name="submit" onclick="enviarNewsLetter();" class="" type="button">Quero fazer parte </button>
									<!--<button type="button" class="btn" onclick="enviarNewsLetter();"><i class="glyphicon glyphicon-menu-right"></i> </button>-->
								</form>
							</center>
						</div>	
					</div>
					 <span id="retornoNews"></span>



					
					<div class="ft-col col-xs-12">
						<h5>Mídias Sociais</h5>
						<nav class="midias-ft">
							<?php
								foreach ((array) $footerData as $socialFt) {
							 		if($socialFt['Descricao'] == 'Footer') {
								 		foreach ((array) $socialFt['Itens'] as $socialItem) {
											if(strpos($socialItem['Descricao'], 'facebook') !== false) {
												$facebook = $socialItem['Descricao'];
											}
							?>
								<a href="<?= $socialItem['Descricao'] ?>" style="color:#22428e;" >
									<img src="<?= $socialItem['Html'] ?>">
								</a>
							<?php
										}
									}
								}
							?>
						</nav>
					</div>
				</div>
			</div>
		</div>

		<!-- Copyright -->
		<div id="copyright">
			<div class="container">
				<div class="copy-col col-xs-12 col-sm-7 col-md-7">
					<h2>Formas de pagamento</h2>
					<nav class="nav-pay">
						<?php
							foreach ((array) $footerData as $payFt) :
								if($payFt['Descricao'] == 'Segurança e Pagamento') :
									foreach ((array) $payFt['Itens'] as $payItem) :
						?>
							<?= $payItem['Descricao'] ?>
						<?php
									endforeach;
								endif;
							endforeach;
						?>
					</nav>
				</div>
				<div class="copy-col col-xs-12 col-sm-5 col-md-4 box-designed">
					<div class="item">
						<h2>Tecnologia</h2>
						<a href="#" target="_blank">
							<img src="/images/site/logo-invento.png" alt="Invento">
						</a>
					</div>
					<div class="item">
						<h2>Desenvolvimento</h2>
						<a href="https://lenord.com.br/" target="_blank">
							<img src="/images/site/logo-lenord.jpg" alt="Lenord">
						</a>
					</div>
				</div>
				<div class="copy-bt">
					<p>
						&copy; <?= ("2017") == date("Y") ? date("Y") : "2017-" . date("Y") ?> <?= $dadosEmpresa['RazaoSocial'] ?> - <?= "CNPJ: " . mascara($dadosEmpresa['CNPJ'],"##.###.###/####-##") ?> - Todos os direitos reservados<br>
						Preços e condições de pagamento exclusivos para compras via internet.
					</p>
				</div>
			</div>
		</div>
	</footer>

	<!-- Modal -->
	<div class="modal fade" id="modal-login" tabindex="-1" role="dialog" aria-labelledby="modal-efetuar-login">
		<div class="modal-dialog modal-sm" role="document">
			<div class="modal-content modal-login-content">
				<div class="modal-fechar hidden-xs hidden-sm" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</div>
				<div class="modal-body">
					<div class="modal-mobile-fechar hidden-lg hidden-md" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</div>
					<div class="title-modal">
						<ul class="ul-login">
							<li class="active">
								<a href="#tab-login" aria-controls="tab-cadastre" role="tab" data-toggle="tab">Login</a>
							</li>
							<li>
								<a href="#tab-cadastre" aria-controls="tab-cadastre" role="tab" data-toggle="tab">Cadastre-se</a>
							</li>
						</ul>
					</div>
					<div class="tab-content">
						<div role="tabpanel" class="tab-pane active" id="tab-login">
							<form name="loginForm" id="loginForm" method="post" action="/" onSubmit="return obterBearer()">
								<div class="box-ipt">
									<input type="email" name="loginEmail" id="loginEmail" placeholder="E-mail" required="required" class="textbox">
								</div>
								<div class="box-ipt">
									<input type="password" name="loginSenha" id="loginSenha" placeholder="Senha" required="required" class="textbox">
								</div>
								<a href="#tab-esqueci" aria-controls="tab-cadastre" role="tab" data-toggle="tab" class="btn-forgot">Esqueci a senha</a><br>
								<span id="resultBearer"></span>
								<button type="submit" class="btn btn-lg">Entrar</button>
							</form>
							<form name="autForm" id="autForm" method="post" action="<?= (!empty($paginas[2]) && strtolower($paginas[2]) == "recuperarsenha") ? "/" : $URISite ?>">
								<input type="hidden" name="loginResult" id="loginResult" value="<?= md5("login") ?>">
								<input type="hidden" name="addwhislist" id="addwhislist" value="0">
							</form>
						</div>
						<div role="tabpanel" class="tab-pane" id="tab-cadastre">
								<form name="cadForm" id="cadForm" method="post" action="/">
								<div class="box-ipt">
									<input type="text" name="cadNome" placeholder="Nome" required="required">
								</div>
								<div class="box-ipt">
									<input type="email" name="cadEmail" placeholder="E-mail" required="required">
								</div>
								<input type="hidden" name="cadastroResult" id="cadastroResult" value="<?= md5("cadastro") ?>">
								<button type="submit" class="btn btn-lg">Cadastrar</button>
							</form>
						</div>
						<div role="tabpanel" class="tab-pane" id="tab-esqueci">
							<h4>Esqueci a senha</h4>
							<form name="recuperarSenhaForm" id="recuperarSenhaForm" method="post" action="/" onsubmit="false">
								<div class="box-ipt">
									<input type="email" name="recEmail" id="recEmail" placeholder="Digite seu e-mail" required="required">
								</div>
								<span id="resultRecuperarSenha"></span>
								<button type="button" onclick="recuperarSenha();" class="btn btn-lg">Enviar</button>
								<a href="#tab-login" aria-controls="tab-cadastre" role="tab" data-toggle="tab" class="btn-back">Voltar</a>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modal-cart" tabindex="-1" role="dialog" aria-labelledby="modal-carrinho-compras">
		<div class="modal-dialog modal-sm" role="document">
			<div class="modal-content modal-carrinho-content">
				<div id="previewCart" class="modal-body">
					<div class="modal-fechar hidden-xs hidden-sm" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</div>
					<div class="modal-mobile-fechar hidden-lg hidden-md" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></div>
					<div class="title-modal">
						<h2>Carrinho</h2>
					</div>
					<div class="cart-preview">
						<?php
							if (!empty($numCarrinho)) {
								$carrinho = getRest(str_replace("{IDCarrinho}", $numCarrinho, $endPoint['obtercarrinho']));
							}

							if (!empty($carrinho) && !empty($carrinho['Itens'])) :
						?>
							<ul>
                                                            
                                                             <!--Evandro Contador Carrinho itens + qtdes exibir no ícone -->
                                                            <?php foreach ((array)$carrinho['Itens'] as $itemDoCarrinho)
                                                            {
                                                                $totalItens += $itemDoCarrinho['Quantidade'] ;
                                                            }
                                                            ?>
                                                             <!--Evandro Contador Carrinho itens + qtdes exibir no ícone -->
                                                            
                                                            
								<?php foreach ((array) $carrinho['Itens'] as $itemCarrinho) : ?>
									<li id="itemCarinhoModal<?= $itemCarrinho['Id'] ?>">
										<div class="row">
											<div class="col-xs-3">
												<img src="<?= $itemCarrinho['ProdutoImagemMobile'] ?>" title="<?= $itemCarrinho['ProdutoDescricao'] ?>" />
											</div>
											<div class="col-xs-7">
												<p class="title"><?= $itemCarrinho['ProdutoDescricao'] ?></p>
												<p class="brand"><?= $itemCarrinho['Marca'] ?></p>
												<p class="qtd">Quantidade: <?= $itemCarrinho['Quantidade'] ?></p>
												<p class="value"><?= formatar_moeda($itemCarrinho['ValorTotal']) ?></p>
												<p><i id="resultDelCarrinho<?= $itemCarrinho['Id'] ?>"></i></p>
                                                                                               
											</div>
											<div class="col-xs-2 text-right">
												<a href="javascript:retirarCarrinhoModal('<?= $itemCarrinho['Id'] ?>');" title="Retirar do carrinho" class="btn-remove"><i class="fa fa-trash" aria-hidden="true"></i></a>
											</div>
										</div>
									</li>
								<?php endforeach; ?>
							</ul>
                                                       
							<a href="/carrinho#cupomLink" class="m-coupon">Cupom de Desconto</a>
							<form method="post" action="/carrinho" class="form-btn">
								<button type="submit" class="btn btn-primary btn-lg">Checkout</button>
							</form>
						<?php else : ?>
							<div class="empty-cart">
								<p>Seu carrinho está vazio.</p>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php if (!empty($carrinho) && !empty($carrinho['Itens'])) : ?>
		<script type="text/javascript">
			$('.cart-qtd').each(function(){
				$(this).html('<?= $totalItens ?>');
			});
		</script>       
	<?php else : ?>
		<script type="text/javascript">
			$('.cart-qtd').each(function(){
				$(this).html('0');
			});
		</script> 
	<?php endif; ?>

	<!-- Scripts -->
	<script src="/javascripts/bootstrap.min.js"></script>
	<script src="/javascripts/nouislider.min.js"></script>
	<script src="/javascripts/jquery-ui.min.js"></script>
	<script src="/javascripts/slick.min.js"></script>
	<script src="/javascripts/jquery.elevatezoom.js"></script>
	<script src="/javascripts/instafeed.js"></script>
	<script src="/javascripts/uemenu-seriedesign.js"></script>
	<script src="/javascripts/seriedesign.js"></script>

	<?php
		if (!empty($tipoBusca)) // script para filtro de preço nas pagina de busca
		{
			?>
				<script type="text/javascript">
					var snapSlider = document.getElementById('slider-handles');
					noUiSlider.create(snapSlider, {
						start: ['<?= $minPreco ?>', '<?= $maxPreco ?>'],
						connect: true,
						range: {
							'min': [<?= $minPreco ?>],
							'max': [<?= $maxPreco ?>]
						}
					});
					var snapValues = [
						document.getElementById('slider-snap-value-lower'),
						document.getElementById('slider-snap-value-upper')
					];
					snapSlider.noUiSlider.on('update', function (values, handle) {
						snapValues[handle].innerHTML = Math.round(values[handle]);
					});
					snapSlider.noUiSlider.on('change', function (values, handle) {
						$('#postvalormin').val(values[0]);
						$('#postvalormax').val(values[1]);
						filtrarBusca(-1);
					});                
				</script>
			<?php
		}
	?>
</body>
</html>
