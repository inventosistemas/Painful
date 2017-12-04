<?php

	$paginaAjuda = getRest($endPoint['rodape']);

	$phpGet = filter_input_array(INPUT_GET);

	if (!empty($phpGet)) {
		$secaoPagina = $phpGet[array_keys($phpGet)[0]];
	}
?>

<section class="content-institutional">
	<div class="container">
		<h4 class="title-page">Ajuda</h4>
		<div class="box-institutional">
			<p class="text-center">
				Conte com nosso material de apoio.<br />
				Caso não encontre o que procura, por favor entre em contato com a nossa equipe, através da nossa <a href="/contato">área atendimento</a>.
			</p>
			<div class="panel-group recursos-accordion" id="recursos-accordion">
				<?php
					foreach ((array) $paginaAjuda as $ajuda) {
						if (in_array($ajuda['ID'], ['5'])) // TODO colocar flag no Expert para indicar se a sessão será exibida.
						{
							foreach ((array) $ajuda['Itens'] as $item) {
								if (in_array($item['ID'],['24','25','26','45','57'])) // TODO colocar flag no Expert para indicar se a sessão será exibida.
								{
				?>
					<a name="<?= $item['ID'] ?>"></a>
					<div class="panel">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a name="recursos-accordion-<?= $item['ID'] ?>" data-toggle="collapse" data-parent="#recursos-accordion" href="#recursos-accordion-<?= $item['ID'] ?>" class="cursor-toggle">
									<span class="nav-plus"></span><span><?= $item['Descricao'] ?></span>
								</a>
							</h4>
						</div>
						<div id="recursos-accordion-<?= $item['ID'] ?>" class="panel-collapse collapse<?= (!empty($secaoPagina) && $secaoPagina == $item['ID']) ? " in" : "" ?>"> <?php // abre a secao quando a ID da secao informada no URL for igual ao ID da secao corrente. ?>
							<div class="panel-body">
								 <?= $item['Html'] ?>
							</div>
						</div>
					</div>
				<?php
								}
							}
						}
					}
				?>
			</div>
		</div>
	</div>
</section>

<div class="make-space-bet clearfix"></div>