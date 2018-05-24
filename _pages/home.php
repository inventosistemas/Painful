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
						$oldPriceProdArray = $maisvendidos['Produto']['PrecoDePor'];
						$oldPriceProd = $maisvendidos['Produto']['PrecoDePor']['PrecoDe'];
						$soldProd = $maisvendidos['Produto']['Esgotado'];
						$promoNew = $maisvendidos['Produto']['Lancamento'];
						$promoPercentage = $maisvendidos['Produto']['PercentualDesconto'];
						if($maisvendidos['Produto']) :
				?>
					<div class="product-item col-xs-12 col-sm-4 col-lg-3">
						<div class="inner-prod <?= $label ?>">
							<figure class="product-img">
								<a href="/produto?id=<?= $idProd ?>">
									<img src="<?= $imgProd ?>" />
								</a>
								<?php if ($promoPercentage && $promoPercentage != 0) : ?>
									<span class="p-promo percentage"><?= floor($promoPercentage) ?>% OFF</span>
								<?php elseif($promoNew) : ?>
									<span class="p-promo new">New</span>
								<?php endif; ?>
							</figure>
							<div class="product-info">
								<h3 class="title">
									<a href="/produto?id=<?= $idProd ?>" title="<?= $titleProd ?>"><?= $titleProd ?></a>
								</h3>
								<span class="brand"><?= $brandProd ?></span>
								<a href="/produto?id=<?= $idProd ?>" class="box-price">
									<?php 
										if($oldPriceProdArray && $oldPriceProd > 0) {
											echo '<s class="price-old">' . formatar_moeda($oldPriceProd) . '</s>';
										}
									?>
									<?= '<span class="price">' . formatar_moeda($priceProd) . '</span>' ?>
									<?php
										$parcelamento = getRest(str_replace(['{IDProduto}', '{valorProduto}'], [$idProd, $priceProd], $endPoint['parcelamento']));
										echo '<span class="installment">' . end($parcelamento)['Descricao'] . '</span>';
									?>
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
				<?php endif; endforeach; ?>
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
				$oldPriceProdArray = $prodShowcase['Produto']['PrecoDePor'];
				$oldPriceProd = $prodShowcase['Produto']['PrecoDePor']['PrecoDe'];
				$soldProd = $prodShowcase['Produto']['Esgotado'];
				$promoNew = $prodShowcase['Produto']['Lancamento'];
				$promoPercentage = $prodShowcase['Produto']['PercentualDesconto'];
				if($prodShowcase['Produto']) :
			?><div class="product-item col-xs-12 col-sm-4 col-lg-3">
					<div class="inner-prod <?= $label ?>">
						<figure class="product-img">
							<a href="/produto?id=<?= $idProd ?>">
								<img src="<?= $imgProd ?>" />
							</a>
							<?php if ($promoPercentage && $promoPercentage != 0) : ?>
								<span class="p-promo percentage"><?= floor($promoPercentage) ?>% OFF</span>
							<?php elseif($promoNew) : ?>
								<span class="p-promo new">New</span>
							<?php endif; ?>
						</figure>
						<div class="product-info">
							<h3 class="title">
								<a href="/produto?id=<?= $idProd ?>" title="<?= $titleProd ?>"><?= $titleProd ?></a>
							</h3>
							<span class="brand"><?= $brandProd ?></span>
							<a href="/produto?id=<?= $idProd ?>" class="box-price">
								<?php 
									if($oldPriceProdArray && $oldPriceProd > 0) {
										echo '<s class="price-old">' . formatar_moeda($oldPriceProd) . '</s>';
									}
								?>
								<?= '<span class="price">' . formatar_moeda($priceProd) . '</span>' ?>
								<?php
									$parcelamento = getRest(str_replace(['{IDProduto}', '{valorProduto}'], [$idProd, $priceProd], $endPoint['parcelamento']));
									echo '<span class="installment">' . end($parcelamento)['Descricao'] . '</span>';
								?>
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
				</div><?php endif; endforeach; ?>
		</div>
	</section>

</main>