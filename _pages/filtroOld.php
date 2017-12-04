<?php
	$phpPost = filter_input_array(INPUT_POST);

	define('HoorayWeb', TRUE);

	include_once ("../p_settings.php");

	if (!empty($phpPost['posttipofiltro']) && $phpPost['posttipofiltro'] == md5("buscaProduto")) {
		$dadosBusca = [
			"Chave" => ($phpPost['posttipobusca'] == "busca") ? $phpPost['posttermobusca'] : "",
			 "Count" => "9999",
			 "UltimoID" => -1,
			 "UltimoPreco" => -1,
			 "UltimaDescricao" => "",
			 "TipoOrdenacao" => (isset($phpPost['postordenacao']) && $phpPost['postordenacao'] >= 0) ? $phpPost['postordenacao'] : 0, // se nao informada a ordernação, orderna pela descrição
			 "ProdutoID" => -1,
			 "SecaoID" => ($phpPost['posttipobusca'] == "secao") ? $phpPost['posttermobusca'] : -1,
			 "MarcaID" => ($phpPost['posttipobusca'] == "marca") ? $phpPost['posttermobusca'] : -1,
			 "ProdutoCategoriaID" => ($phpPost['posttipobusca'] == "categoria") ? $phpPost['posttermobusca'] : -1,
			 "ProdutoSubCategoriaID" => -1
		];

		$caracteristicasFiltro = [];

		for ($i = 0; $i < count($phpPost); $i++) {
			if (in_array(array_keys($phpPost)[$i], ['posttermobusca', 'posttipofiltro', 'postordenacao', 'posttipobusca', 'postvalormin', 'postvalormax'])) continue; // variaveis de controle que nao entram no filtro.

			$opcoesFiltro = explode('##', $phpPost[array_keys($phpPost)[$i]]);
			$caracteristicasFiltro[$i] = [
				"TipoID" => $opcoesFiltro[0],
				"ValorID" => $opcoesFiltro[1]
			];
		}

		if (!empty($caracteristicasFiltro)) {
			$dadosBusca['CaracteristicasFiltro'] = $caracteristicasFiltro;
		}

		if (!empty($phpPost['postvalormin']) && !empty($phpPost['postvalormax'])) {
			$faixaPreco = [
				"PrecoInicial" => floor($phpPost['postvalormin']),
				"PrecoFinal" => ceil($phpPost['postvalormax'])
			];

			$dadosBusca['FaixaPreco'] = $faixaPreco;
		}

		$resultadoBusca = sendRest($endPoint['busca'], $dadosBusca, "POST");

		if (!empty($resultadoBusca['ProdutoBuscaItens']) && count($resultadoBusca['ProdutoBuscaItens']) > 0) {
			echo "<ul id=\"itensGrid\">";

			foreach ((array) $resultadoBusca['ProdutoBuscaItens'] as $produtoBusca) {
				$idProd = $produtoBusca['ID'];
				$imgProd = $produtoBusca['Imagem'];
				$titleProd = $produtoBusca['Descricao'];
				$brandProd = $produtoBusca['Marca'];
				$priceProd = $produtoBusca['PrecoVigente'];
				$soldProd = $produtoBusca['Esgotado'];
?><li class="product-item col-xs-12 col-sm-4 col-lg-3">
	<div class="inner-prod">
		<figure class="product-img">
			<a href="/produto?id=<?= $idProd ?>">
				<img src="<?= $imgProd ?>" />
			</a>
			<?php if (!empty($produtoBusca['PrecoDe']) && $produtoBusca['PrecoDe'] > 0) : ?>
				<span class="p-promo percentage"><?= floor($produtoBusca['PercentualDesconto']) ?>% OFF</span>
			<?php elseif(!empty($produtoBusca['Lancamento'])) : ?>
				<span class="p-promo new">New</span>
			<?php endif; ?>
		</figure>
		<div class="product-info">
			<h3 class="title">
				<a href="/produto?id=<?= $idProd ?>" title="<?= $titleProd ?>"><?= $titleProd ?></a>
			</h3>
			<span class="brand"><?= $brandProd ?></span>
			<a href="/produto?id=<?= $idProd ?>" class="box-price">
				<?= (!empty($produtoBusca['PrecoDe'])) ? '<s class="price-old">' . formatar_moeda($produtoBusca['PrecoDe']) . '</s>' : '' ?><?= '<span class="price">' . formatar_moeda($priceProd) . '</span>' ?>
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
</li><?php
		}
		echo "</ul>";
	} else {
?>
	<div class="list-wrapper">
		<p>Nenhum produto encontrado.</p>
	</div>
<?php
		}
	}
?>
