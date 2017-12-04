<?php
	$paginaInstitucional = getRest($endPoint['rodape']);

	$phpGet = filter_input_array(INPUT_GET);

	if (!empty($phpGet)) {
		$secaoPagina = $phpGet[array_keys($phpGet)[0]];
	}
?>

<section class="content-institutional">
	<div class="container">
		<h4 class="title-page">Institucional</h4>
		<div class="box-institutional">
			<p class="text-center">
				Conte com nosso material de apoio.<br />
				Caso não encontre o que procura, por favor entre em contato com a nossa equipe, através da nossa <a href="/contato">área atendimento</a>.
			</p>
			<div class="panel-group institucional-accordion" id="institucional-accordion">
				<?php
					foreach ((array) $paginaInstitucional as $institucional) { 
						if (in_array($institucional['ID'], ['8'])) // TODO colocar flag no Expert para indicar se a sessão será exibida.
						{
							foreach ((array) $institucional['Itens'] as $item) {
								if (in_array($item['ID'],['42','47'])) // TODO colocar flag no Expert para indicar se a sessão será exibida.
								{
				?>
					<a name="<?= $item['ID'] ?>"></a>
					<div class="panel">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a name="institucional-accordion-<?= $item['ID'] ?>" data-toggle="collapse" data-parent="#institucional-accordion" href="#institucional-accordion-<?= $item['ID'] ?>" class="cursor-toggle">
									<span class="nav-plus"></span><span><?= $item['Descricao'] ?></span>
								</a>
							</h4>
						</div>
						<div id="institucional-accordion-<?= $item['ID'] ?>" class="panel-collapse collapse<?= (!empty($secaoPagina) && $secaoPagina == $item['ID']) ? " in" : "" ?>"> <?php // abre a secao quando a ID da secao informada no URL for igual ao ID da secao corrente. ?>
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
		<div class="make-space-bet clearfix"></div>
	</div>
</section>
<div class="make-space-bet clearfix"></div>