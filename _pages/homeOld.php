<?php
	$showcaseData = getRest($endPoint['vitrine']);
?>

<script>
	$('body').addClass('home-page');
	function enviarNewsLetter() {
		$('#retornoNews').html('Enviando...');

		var dataString = 'emailInscricao=' + document.getElementById('newsEmail').value;
		dataString += '&postnews=<?= md5("enviarNewsLetter") ?>';

		$.ajax({
			type: "post",
			url: "/_pages/enviarContato.php",
			data: dataString,
			cache: false,
			success: function (retornoPHP) {
				$('#retornoNews').html(retornoPHP);
			}
		});

		document.getElementById('newsEmail').value = '';
	}
</script>

<main class="main-container">

	<!-- Main banner -->
	<section class="main-banner">
		<?php
			$bannersSite = getRest($endPoint['banner']);
			foreach ((array) $bannersSite[0]['BannerItens'] as $banner) :
		?>
			<a href="<?= $banner['HotSite'] ?>" class="banner-link">
				<div class="box-inner">
					<img src="<?= $banner['Imagem'] ?>" alt="<?= $banner['Descricao'] ?>" title="<?= $banner['Titulo'] ?>" class="img-dsk">
					<img src="<?= $banner['ImagemMobile'] ?>" alt="<?= $banner['Descricao'] ?>" title="<?= $banner['Titulo'] ?>" class="img-mb">
				</div>
			</a>
		<?php endforeach; ?>
	</section>

	<!-- Content mosaic -->
	<section class="content-mosaic">
		<div class="container">
			<div class="inner-mosaic">
				<div class="m-col">
					<?php
						$showcaseColumn = array ( // larguras das colunas da vitrine
							1 => 'col-xs-12',
							2 => 'col-xs-12',
							3 => 'col-xs-6',
							4 => 'col-xs-6'
						);
						$showcaseCounter = 1;
						foreach ((array) $showcaseData as $showcase) :
							if (in_array($showcaseCounter, [3,5])) // gera uma nova linha nos itens 4 e 6
							{
								echo '</div><div class="m-col">';
							}
							$linkVitrine = "#";    
							if (!empty($showcase['Produto'])) {
								$linkVitrine = "/produto?id=" . $showcase['Produto']['ID'];
							} elseif (!empty($showcase['BlogArtigo'])) {
								$linkVitrine = "/blogpost?id=" . $showcase['BlogArtigo']['ID'];
							} elseif (!empty($showcase['Categoria'])) {
								$linkVitrine = "/categoria?id=" . $showcase['Categoria']['ID'];
							} elseif (!empty($showcase['Marca'])) {
								$linkVitrine = "/blogpost?id=" . $showcase['Marca']['ID'];
							}
					?>
						<div class="<?= $showcaseColumn[$showcaseCounter] ?>">
							<div class="item">
								<a href="<?= $linkVitrine ?>">
									<img src="<?= $showcase['UrlImagem'] ?>" alt="<?= $showcase['Titulo'] ?>">
								</a>
							</div>
						</div>
					<?php $showcaseCounter ++; endforeach; ?>
				</div>
			</div>
		</div>
	</section>

	<!-- Content featured -->
	<section class="content-featured content-slider">
		<div class="container">
			<div class="product-slider">
				<?php
					$maisVendidosSite = getRest($endPoint['maisvedidos']);
					foreach ((array) $maisVendidosSite[0]['Itens'] as $maisvendidos) :
						$idProd = $maisvendidos['Produto']['ID'];
						$imgProd = $maisvendidos['Produto']['Imagem'];
						$titleProd = $maisvendidos['Produto']['Descricao'];
						$brandProd = $maisvendidos['Produto']['Marca']['Descricao'];
						$priceProd = $maisvendidos['Produto']['PrecoVigente'];
						$soldProd = $maisvendidos['Produto']['Esgotado'];
				?>
					<div class="product-item col-xs-12 col-sm-4 col-lg-3">
						<div class="inner-prod <?= $label ?>">
							<figure class="product-img">
								<a href="/produto?id=<?= $idProd ?>">
									<img src="<?= $imgProd ?>" />
								</a>
								<?php if (!empty($maisvendidos['Produto']['PercentualDesconto']) && $maisvendidos['Produto']['PercentualDesconto'] > 0) : ?>
									<span class="p-promo percentage"><?= floor($maisvendidos['Produto']['PercentualDesconto']) ?>% OFF</span>
								<?php elseif(!empty($maisvendidos['Produto']['Lancamento'])) : ?>
									<span class="p-promo new">New</span>
								<?php endif; ?>
							</figure>
							<div class="product-info">
								<h3 class="title">
									<a href="/produto?id=<?= $idProd ?>" title="<?= $titleProd ?>"><?= $titleProd ?></a>
								</h3>
								<span class="brand"><?= $brandProd ?></span>
								<a href="/produto?id=<?= $idProd ?>" class="box-price">
									<?= (!empty($maisvendidos['Produto']['PrecoDePor'])) ? '<s class="price-old">' . formatar_moeda($maisvendidos['Produto']['PrecoDePor']['PrecoDe']) . '</s>' : '' ?><?= '<span class="price">' . formatar_moeda($priceProd) . '</span>' ?>
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
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<!-- Showcase home -->
	<section class="showcase-home">
		<div class="container">
			<?php foreach ((array) $showcaseData as $prodShowcase) : 
				$idProd = $prodShowcase['Produto']['ID'];
				$imgProd = $prodShowcase['Produto']['Imagem'];
				$titleProd = $prodShowcase['Produto']['Descricao'];
				$brandProd = $prodShowcase['Produto']['Marca']['Descricao'];
				$priceProd = $prodShowcase['Produto']['PrecoVigente'];
				$soldProd = $prodShowcase['Produto']['Esgotado'];
			?><div class="product-item col-xs-12 col-sm-4 col-lg-3">
					<div class="inner-prod <?= $label ?>">
						<figure class="product-img">
							<a href="/produto?id=<?= $idProd ?>">
								<img src="<?= $imgProd ?>" />
							</a>
							<?php if (!empty($maisvendidos['Produto']['PercentualDesconto']) && $maisvendidos['Produto']['PercentualDesconto'] > 0) : ?>
								<span class="p-promo percentage"><?= floor($maisvendidos['Produto']['PercentualDesconto']) ?>% OFF</span>
							<?php elseif(!empty($maisvendidos['Produto']['Lancamento'])) : ?>
								<span class="p-promo new">New</span>
							<?php endif; ?>
						</figure>
						<div class="product-info">
							<h3 class="title">
								<a href="/produto?id=<?= $idProd ?>" title="<?= $titleProd ?>"><?= $titleProd ?></a>
							</h3>
							<span class="brand"><?= $brandProd ?></span>
							<a href="/produto?id=<?= $idProd ?>" class="box-price">
								
                                                      <s class="price-old">  <?php 
                                                         if(is_null($maisvendidos['Produto']['PrecoDePor']))
                                                         {
                                                              formatar_moeda($maisvendidos['Produto']['PrecoVigente']);                                                                                                                   
                                                         }
                                                         else
                                                         {
                                                           formatar_moeda($maisvendidos['Produto']['PrecoDePor']);
                                                         }
                                                         
                                                         ?> 
                                                        </s>  
                                                            	<?php
									$parcelamento = getRest(str_replace(['{IDProduto}', '{valorProduto}'], [$idProd, $priceProd], $endPoint['parcelamento']));
									$contParc = 1;
									foreach ((array) $parcelamento as $parcela) :
										if ($parcela['Numero'] === 1) :
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
				</div><?php endforeach; ?>
		</div>
	</section>

</main>