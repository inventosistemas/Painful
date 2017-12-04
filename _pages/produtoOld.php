<?php
	if (!empty($dadosLogin['CarrinhoId'])) {
		$IDCarrinho = $dadosLogin['CarrinhoId'];
	} elseif (!empty($_SESSION['carrinho'])) {
		$IDCarrinho = $_SESSION['carrinho'];
	} else {
		$IDCarrinho = "-1";
	}
?>

<script type="text/javascript">
	function atualizarCarrinho() {
		$('#resultCart').html('Adicionando ao seu carrinho...');

		var qdtProduto = $('#qdtProduto').val();
		var TipoDescritor = <?= (!empty($dadosProduto['TipoDescritor'])) ? "$('#TipoDescritor').val();" : "'-1';" ?>
		var TipoDescritor2 = <?= (!empty($dadosProduto['TipoDescritor2'])) ? "$('#TipoDescritor2').val();" : "'-1';" ?>
		var TipoDescritor3 = <?= (!empty($dadosProduto['TipoDescritor3'])) ? "$('#TipoDescritor3').val();" : "'-1';" ?>
		var TipoDescritor4 = <?= (!empty($dadosProduto['TipoDescritor4'])) ? "$('#TipoDescritor4').val();" : "'-1';" ?>
		var TipoDescritor5 = <?= (!empty($dadosProduto['TipoDescritor5'])) ? "$('#TipoDescritor5').val();" : "'-1';" ?>

		$.post('/_pages/carrinhoEditar.php', {
			postidlogin:'<?= (!empty($dadosLogin['ID']) && $dadosLogin['ID'] > 0) ? $dadosLogin['ID'] : "-1" ?>',
			postidcarrinho:'<?= $IDCarrinho ?>',
			postidproduto:'<?= $dadosProduto['ID'] ?>',
			postqdteproduto:qdtProduto,
			postdescritor:TipoDescritor,
			postdescritor2:TipoDescritor2,
			postdescritor3:TipoDescritor3,
			postdescritor4:TipoDescritor4,
			postdescritor5:TipoDescritor5,
			postcarrinho:'<?= md5("addCarrinho") ?>'
		},
		function(dataCarrinho) {
			if (dataCarrinho.substring(0,2) == "!!") {
				$('#resultCart').html(dataCarrinho.substring(2));
			} else {
				$('#resultCart').html('Produto adicionado ao seu carrinho.');
				$('#previewCart').html(dataCarrinho);

				$('.cart-qtd').each(function(){
					$(this).html($('#previewCart ul li').length);
				});
			}
		});
	}

	function addWishList() {
		$.post('/_pages/atualizarCadastro.php', {
			postidlogin:'<?= (!empty($dadosLogin['ID']) && $dadosLogin['ID'] > 0) ? $dadosLogin['ID'] : "-1" ?>',
			postidproduto:'<?= $dadosProduto['ID'] ?>',
			postwishlist:'<?= md5("adicionar") ?>'
		},
		function(dataWishList) {
			$('#retornoWishlist').html(dataWishList);
		});
	}

	function loginaddwish() {
		$('#addwhislist').val('<?= $dadosProduto['ID'] ?>');
		$('#link-login')[0].click();
	}
</script>

<?php if (!empty($phpPost['addwhislist'])) { ?>
	<script type="text/javascript">
		addWishList();
		$('#addwhislist').val('0');
	</script>
<?php } ?>

<section class="content-breadcrumb">
	<div class="container">
		<div class="box-breadcrumb">
			<a href="/">Home</a> <span class="glyphicon glyphicon-menu-right"></span>
			<a href="/categoria?id=<?= $dadosProduto['Categoria']['ID'] ?>"><?= $dadosProduto['Categoria']['Descricao'] ?></a> <span class="glyphicon glyphicon-menu-right"></span>
			<a href="/marca?id=<?= $dadosProduto['Marca']['ID'] ?>"><?= $dadosProduto['Marca']['Descricao'] ?></a> <span class="glyphicon glyphicon-menu-right"></span>
			<span class="page-title"><?= $dadosProduto['Descricao'] ?></span>
		</div>
	</div>
</section>

<section class="content-product">
	<div class="container">

		<!-- Product left -->
		<div class="col-sm-6 prod-lf">
			<div id="large-img" class="col-xs-12">
				<div class="box-img">
					<?php if($dadosProduto['ImagemPlus1']) : ?>
						<div class="box-zoom">
							<img src="<?= $dadosProduto['ImagemPlus1'] ?>" data-zoom-image="<?= $dadosProduto['ImagemPlus1'] ?>" />
						</div>
					<?php else : ?>
						<div>
							<img src="images/site/required/unavailable.jpg" alt="Imagem não disponível" />
						</div>
					<?php endif; ?>
					<?php if($dadosProduto['UrlVideoYoutube']) : ?>
						<div class="box-video">
							<div class="video">
								<iframe src="<?= $dadosProduto['UrlVideoYoutube'] ?>" frameborder="0" allowfullscreen></iframe>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<div class="content-thumb col-xs-12">
				<div id="box-thumb">
					<?php if($dadosProduto['ImagemPlus1']) : ?>
						<a class="active" data-image="<?= $dadosProduto['ImagemPlus1'] ?>" data-zoom-image="<?= $dadosProduto['ImagemPlus1'] ?>"><img src="<?= $dadosProduto['ImagemPlus1'] ?>" /></a>
					<?php endif; ?>
					<?php if($dadosProduto['ImagemPlus2']) : ?>
						<a class="active" data-image="<?= $dadosProduto['ImagemPlus2'] ?>" data-zoom-image="<?= $dadosProduto['ImagemPlus2'] ?>"><img src="<?= $dadosProduto['ImagemPlus2'] ?>" /></a>
					<?php endif; ?>
					<?php if($dadosProduto['ImagemPlus3']) : ?>
						<a class="active" data-image="<?= $dadosProduto['ImagemPlus3'] ?>" data-zoom-image="<?= $dadosProduto['ImagemPlus3'] ?>"><img src="<?= $dadosProduto['ImagemPlus3'] ?>" /></a>
					<?php endif; ?>
					<?php if($dadosProduto['ImagemPlus4']) : ?>
						<a class="active" data-image="<?= $dadosProduto['ImagemPlus4'] ?>" data-zoom-image="<?= $dadosProduto['ImagemPlus4'] ?>"><img src="<?= $dadosProduto['ImagemPlus4'] ?>" /></a>
					<?php endif; ?>
					<?php if($dadosProduto['UrlVideoYoutube']) : ?>
						<a class="thumb-video"><img src="images/site/required/thumb-video.png" alt="Vídeo"></a>
					<?php endif; ?>
				</div>
			</div>
			<div class="box-share">
				<span class="title">Compartilhar</span>
				<a href="javascript: void(0);" onclick="window.open('https://twitter.com/intent/tweet?text=Gostei+de+um+produto+da+Hooray&url=<?= urlencode(URLSite . ltrim($URISite,"/")) ?>&hashtags=hooraybrasil','twitter', 'toolbar=0, status=0, width=650, height=450');"><i class="fa fa-twitter-square" aria-hidden="true"></i></a>
				<a href="javascript: void(0);" onclick="window.open('https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(URLSite . ltrim($URISite,"/")) ?>','facebook', 'toolbar=0, status=0, width=650, height=450');"><i class="fa fa-facebook-square" aria-hidden="true"></i></a>
			</div>
		</div>

		<!-- Product right -->
		<div class="col-sm-6 prod-rt">
			<form name="dadosProduto" id="dadosProduto" method="post" onsubmit="false">
				<h4 class="title"><?= $dadosProduto['Descricao'] ?></h4>
				<div class="box-wish">
					<?php if (!empty($dadosLogin['ID']) && $dadosLogin['ID'] > 0) : ?>
						<a href="javascript:addWishList();" class="wish"><i class="fa fa-heart-o"></i> <span id="retornoWishlist">Adicionar à minha Wishlist</span></a>
					<?php else : ?>    
						<a href="javascript:loginaddwish();"><i class="fa fa-heart-o"></i> <span id="retornoWishlist">Fazer login e adicionar à minha Wishlist</span></a>
					<?php endif; ?>
				</div>
				<?php if($dadosProduto['Marca']['Descricao']) : ?>
					<p class="brand-p"><strong>Marca:</strong> <span><?= $dadosProduto['Marca']['Descricao'] ?></span></p>
				<?php endif; ?>
				<?php if($dadosProduto['Fornecedor']) : ?>
					<p class="provider-p"><strong>Vendido e entregue por:</strong> <span><?= $dadosProduto['Fornecedor'] ?></span></p>
				<?php endif; ?>
				<div class="box-price">
					<div class="col-xs-6">
						<?= (!empty($dadosProduto['PrecoDePor'])) ? "<s class=\"price-old\">" . formatar_moeda($dadosProduto['PrecoDePor']['PrecoDe']) . "</s>" : "" ?>
						<span class="price"><?= formatar_moeda($dadosProduto['PrecoVigente']) ?></span>
					</div><!--
					--><div class="col-xs-6">
						<div class="box-qtd">
							<label for="qdtProduto">Quantidade</label>
							<div class="add-qtd">
								<div class="i-less">
									<i class="fa fa-minus" aria-hidden="true"></i>
									<input type="button" value="-" class="i-disincrease" field="quantity" />
								</div>
								<input name="quantity" id="qdtProduto" type="text" value="1" class="qty" data-quantidade maxlength="4"/>
								<div class="i-plus">
									<i class="fa fa-plus" aria-hidden="true"></i>
									<input type="button" value="+" class="i-increase" field="quantity" />
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="box-type">
					<?php if (!empty($dadosProduto['TipoDescritor'])) : ?>                        
						<div class="type-p">
							<label><?= $dadosProduto['TipoDescritor']['Descricao'] ?></label>
							<select name="TipoDescritor" id="TipoDescritor" class="select" min="0" class="input-lg">
								<?php
									$opcoesDescritor = [];
									foreach ((array) $dadosProduto['Skus'] as $sku) {
										if (!in_array($sku['Descritor'], $opcoesDescritor)) // não exibe descritores repetidos
										{
											array_push($opcoesDescritor, $sku['Descritor']);
											echo "<option value=\"" . $sku['Descritor'] . "\">" . $sku['Descritor'] . "</option>";
										}
									}
								?>
							</select>
						</div>
					<?php endif; ?>
				 <?php if (!empty($dadosProduto['TipoDescritor2'])) : ?>                        
						<div class="type-p">
							<label><?= $dadosProduto['TipoDescritor2']['Descricao'] ?></label>
							<select name="TipoDescritor2" id="TipoDescritor2" class="select" min="0" class="input-lg">
								<?php
									$opcoesDescritor2 = [];
									foreach ((array) $dadosProduto['Skus'] as $sku) {
										if (!in_array($sku['Descritor2'], $opcoesDescritor2)) {
											array_push($opcoesDescritor2, $sku['Descritor2']);
											echo "<option value=\"" . $sku['Descritor2'] . "\">" . $sku['Descritor2'] . "</option>";
										}
									}
								?>
							</select>
						</div>
					<?php endif; ?>
					<?php if (!empty($dadosProduto['TipoDescritor3'])) : ?>                        
						<div class="type-p">
							<label><?= $dadosProduto['TipoDescritor3']['Descricao'] ?></label>
							<select name="TipoDescritor3" id="TipoDescritor3" class="select" min="0" class="input-lg">
								<?php
									$opcoesDescritor3 = [];
									foreach ((array) $dadosProduto['Skus'] as $sku) {
										if (!in_array($sku['Descritor3'], $opcoesDescritor3)) {
											array_push($opcoesDescritor3, $sku['Descritor3']);
											echo "<option value=\"" . $sku['Descritor3'] . "\">" . $sku['Descritor3'] . "</option>";
										}
									}
								?>
							</select>
						</div>
					<?php endif; ?>
					<?php if (!empty($dadosProduto['TipoDescritor4'])) : ?>                        
						<div class="type-p">
							<label><?= $dadosProduto['TipoDescritor4']['Descricao'] ?></label>
							<select name="TipoDescritor4" id="TipoDescritor4" class="select" min="0" class="input-lg">
								<?php
									$opcoesDescritor4 = [];
									foreach ((array) $dadosProduto['Skus'] as $sku) {
										if (!in_array($sku['Descritor4'], $opcoesDescritor4)) {
											array_push($opcoesDescritor4, $sku['Descritor4']);
											echo "<option value=\"" . $sku['Descritor4'] . "\">" . $sku['Descritor4'] . "</option>";
										}
									}
								?>
							</select>
						</div>
					<?php endif; ?>
					<?php if (!empty($dadosProduto['TipoDescritor5'])) : ?>                        
						<div class="type-p">
							<label><?= $dadosProduto['TipoDescritor5']['Descricao'] ?></label>
							<select name="TipoDescritor5" id="TipoDescritor5" class="select" min="0" class="input-lg">
								<?php
									$opcoesDescritor5 = [];
									foreach ((array) $dadosProduto['Skus'] as $sku) {
										if (!in_array($sku['Descritor5'], $opcoesDescritor5)) {
											array_push($opcoesDescritor5, $sku['Descritor5']);
											echo "<option value=\"" . $sku['Descritor5'] . "\">" . $sku['Descritor5'] . "</option>";
										}
									}
								?>
							</select>
						</div>
					<?php endif; ?>
				</div>
				<?php
					$indexEmEstoque = array_search("0", array_column($dadosProduto['Skus'], 'Disponibilidade'));
					$indexSobEncomenda = array_search("1", array_column($dadosProduto['Skus'], 'Disponibilidade'));
					$indexIndisponivel = array_search("2", array_column($dadosProduto['Skus'], 'Disponibilidade'));
					if ($indexEmEstoque !== false) {
						echo "<h6 class=\"stock\">Em estoque</h6>";
					} elseif ($indexSobEncomenda !== false) {
						echo "<h6 class=\"stock\">Sob encomenda</h6>";
					}
				?>
				<h5 id="resultCart"></h5>
				<?php
					if ($indexEmEstoque === false && $indexSobEncomenda === false) // nao retornou em estoque nem sobencomenda
					{
				?>
					<div class="box-btn">
						<h4 class="btn-sold">Indisponível</h4>
					</div>
				<?php } else { ?>
					<div class="box-btn">
						<button type="button" onclick="atualizarCarrinho()" class="btn-buy">Adicionar ao carrinho</button>
					</div>
				<?php
						$parcelamento = getRest(str_replace(['{IDProduto}', '{valorProduto}'], [$dadosProduto['ID'], $dadosProduto['PrecoVigente']], $endPoint['parcelamento']));
				?>
					<div class="box-installment">
						<?php
						if (!empty($parcelamento[0]) && $parcelamento[0]['Numero'] === 0) {
							$parcBoleto = $parcelamento[0];
						?>    
							<div class="col-md-12">
								<ul>
									<li>À vista no boleto, desconto de <?= floor(100 - ($parcBoleto['Valor'] / $dadosProduto['PrecoVigente'] * 100)) ?>%: <span><?= formatar_moeda($parcBoleto['Valor']) ?></span></li>
								</ul>
							</div>
						<?php } ?>
						<div class="col-md-12">
							<span class="title">Pagamento no cartão de crédito:</span>
						</div>
						 <div class="col-md-6">
							<ul>                                
								<?php
									$contParc = 1;
									foreach ((array) $parcelamento as $parcela) {
										if ($parcela['Numero'] === 0) continue; //pula parcela do boleto, ja exibida acima

										if ($contParc == ceil((count($parcelamento)) / 2) && count($parcelamento) > 2) //divide em duas colunas
										{
											echo "</ul></div><div class=\"col-md-6\"><ul>";
										}

										echo "<li>" . $parcela['Numero'] . "x sem juros de <span>" . formatar_moeda($parcela['Valor']) . "</span></li>";
										$contParc++;
									}
								?>
							</ul>
						</div>
					</div>
				<?php } ?>
				<div class="box-attr">
					<span class="title">Atributos</span>
					<ul>
						<?php
							function ordenacaoCaracteristica($a, $b) {
								if ($a['Posicao'] == $b['Posicao']) {
									return 0;
								}
								return ($a['Posicao'] < $b['Posicao']) ? -1 : 1;
							}

							if (!empty($dadosProduto['Caracteristicas'])) {
								usort($dadosProduto['Caracteristicas'], "ordenacaoCaracteristica"); // ordenas as caracteristicas por ordem alfabetica
							}

							$infoGenerica = "";
							foreach ((array) $dadosProduto['Caracteristicas'] as $caracteristica) {
								if ($caracteristica['Visivel'] == true && $caracteristica['Descricao'] != "Vitrine" && !strpos($caracteristica['Descricao'], "GENÉRICA")) {
									echo "<li>" . $caracteristica['Descricao'] . ": <span>" . $caracteristica['Valor'] . "</span></li>";
								}

								if (strpos($caracteristica['Descricao'], "GENÉRICA") > 0 || strpos($caracteristica['Descricao'], "GENERICA") > 0) {
									$infoGenerica .= " " . $caracteristica['Valor'];
								}
							}
							echo "<li><span>" . $infoGenerica . "</span></li>";
						?>
					</ul>
				</div>
			</form>
		</div>
	</div>
</section>

<section class="content-info">
	<div class="container">
		<h4 class="title-master">Mais Informações</h4>
		<div class="box-info">
			<?= $dadosProduto['DescricaoDetalhada'] ?>
		</div>
	</div>
</section>

<?php
	$prodRelacionados = getRest(str_replace("{IDProduto}", $IDProduto, $endPoint['relacionados']));
	$tituloRelacionados = "Produtos relacionados";

	if (empty($prodRelacionados)) {
		$prodRelacionados = getRest($endPoint['maisvedidos']);
		$tituloRelacionados = "Destaques";
		$listaProdutos = $prodRelacionados[0]['Itens'];
		$tipoProdutos = "maisvendidos";
	} else {
		$listaProdutos = $prodRelacionados;
		$tipoProdutos = "relacionados";
	}

	if (!empty($prodRelacionados)) {
?>
	<section class="content-related content-slider hidden-xs">
		<div class="container">
			<h3 class="title-master"><?= $tituloRelacionados ?></h3>
			<div class="product-slider">
				<?php
					$contMV = 0;
					foreach ((array) $listaProdutos as $relacionado) {
						$produto = ($tipoProdutos == "relacionados") ? $relacionado : $relacionado['Produto'];
						$idProd = $produto['ID'];
						$imgProd = $produto['Imagem'];
						$titleProd = $produto['Descricao'];
						$brandProd = $produto['Marca']['Descricao'];
						$priceProd = $produto['PrecoVigente'];
						$soldProd = $produto['Esgotado'];
				?>
					<div class="product-item col-md-3">
						<div class="inner-prod <?= $label ?>">
							<figure class="product-img">
								<a href="/produto?id=<?= $idProd ?>">
									<img src="<?= $imgProd ?>" />
								</a>
								<?php if (!empty($produto['PercentualDesconto']) && $produto['PercentualDesconto'] > 0) : ?>
									<span class="p-promo percentage"><?= floor($produto['PercentualDesconto']) ?>% OFF</span>
								<?php elseif(!empty($produto['Lancamento'])) : ?>
									<span class="p-promo new">New</span>
								<?php endif; ?>
							</figure>
							<div class="product-info">
								<h3 class="title">
									<a href="/produto?id=<?= $idProd ?>" title="<?= $titleProd ?>"><?= $titleProd ?></a>
								</h3>
								<span class="brand"><?= $brandProd ?></span>
								<a href="/produto?id=<?= $idProd ?>" class="box-price">
									<?= (!empty($produto['PrecoDePor'])) ? '<s class="price-old">' . formatar_moeda($produto['PrecoDePor']['PrecoDe']) . '</s>' : '' ?><?= '<span class="price">' . formatar_moeda($priceProd) . '</span>' ?>
									<?php
										$parcelamento = getRest(str_replace(['{IDProduto}', '{valorProduto}'], [$idProd, $priceProd], $endPoint['parcelamento']));
										$contParc = 1;
										foreach ((array) $parcelamento as $parcela) :
											if ($parcela['Numero'] === 6) :
									?>
										<span class='installment'><?= $parcela['Numero'] ?> x <strong><?= formatar_moeda($parcela['Valor']) ?></strong> sem juros</span>
									<?php endif; $contParc++; endforeach; ?>
								</a>
								<div class="box-btn">
									<?php if(!$soldProd) : ?>
										<a href="/produto?id=<?= $idProd ?>" class="btn-buy"><span>Comprar</span></a>
									<?php else : ?>
										<a href="/produto?id=<?= $idProd ?>" class="btn-sold"><span>Esgotado</span></a>
									<?php endif; ?>
								</div>
							</div>
						</div>
					</div>                       
				<?php $contMV++; } ?>
			</div>
		</div>
	</section>
<?php } ?>