<?php
//if (!defined('HoorayWeb')) 
//{
//    die;
//}
	$phpGet = filter_input_array(INPUT_GET);
	if (!empty($phpGet)) {
		$tipoContato = $phpGet[array_keys($phpGet)[0]];
	}
	$dadosCadastrais = getRest(str_replace("{IDParceiro}","1031", $endPoint['dadoscadastrais']));
	$assuntosContato = getRest($endPoint['assuntoscontato']);
?>

<script type="text/javascript">
	function enviarContato() {
	 $('#retornoContato').html('Enviando mensagem...');
		var dataString  = 'contAssunto=' + document.getElementById('contAssunto').value;
			dataString += '&contNome=' + document.getElementById('contNome').value;
			dataString += '&contCPFCNPJ=' + document.getElementById('contCPFCNPJ').value;
			dataString += '&contPedido=' + document.getElementById('contPedido').value;
			dataString += '&contEmail=' + document.getElementById('contEmail').value;
			dataString += '&contTelefone=' + document.getElementById('contTelefone').value;
			dataString += '&contMensagem=' + document.getElementById('contMensagem').value;
			dataString += '&postcontato=<?= md5("enviarContato") ?>';
		$.ajax({
			type: "post",
			url: "/_pages/enviarContato.php",
			data: dataString,
			cache: false,
			success: function (retornoPHP) {
				if (retornoPHP.substring(0,2) == "!!") {
					$('#retornoContato').html(retornoPHP.substring(2));
				} else {
					$('#retornoContato').html(retornoPHP);
					document.contForm.reset();
				}
			}
		});
		return false;
	}
</script>

<section class="content-contact conteudo">
	<div class="container">
    <h3 class="title-page">Contato</h3>
		<p class="text-center">
			SAC <span> <?= mascara(substr($dadosCadastrais['DDDTelefone'],-2) . $dadosCadastrais['Telefone'], "(##) ####-####") ?></span>
		</p>
		<p class="text-center">
			E-mail <span> <?= $dadosCadastrais['Email'] ?></span>
		</p>
		<p class="text-center">
			ou se preferir, selecione o assunto e responda o formulário abaixo:
		</p>
		<form id="contForm" name="contForm" class="form-group-contato" method="post" onSubmit="return enviarContato()">
			<div class="form-group input-icone">
				<select class="form-control-contato select" name="contAssunto" id="contAssunto" required="required">
					<option value="" disabled selected>Selecione o Assunto</option>
					<?php
						foreach ((array) $assuntosContato as $assunto) {
							$selecionado = (!empty($tipoContato) && $tipoContato == "trocasdevolucoes" && $assunto['ID'] == "1002") ? " SELECTED" : "";

							echo "<option value=\"" . $assunto['ID'] . "\"" . $selecionado . ">" . htmlentities($assunto['Descricao']) . "</option>";
						}
					?>
				</select>
			</div>
			<div class="text-left-grey">
				<p>Quer falar sobre a entrega do seu pedido?</p>
				<p>Está com dúvidas sobre o andamento da sua entrega, algo no endereço, seu pedido chegou incompleto? Fale conosco sobre qualquer assunto relacionado a sua entrega</p>
			</div>
			<label>Para podermos agilizar nosso atendimento, por favor nos informe alguns dados</label>
			<div class="form-group input-icone">
				<input class="form-control" placeholder="Nome" type="text" name="contNome" id="contNome" required="required">
			</div>
			<div class="form-group input-icone">
				<input class="form-control" placeholder="CPF / CNPJ" type="text" name="contCPFCNPJ" id="contCPFCNPJ" required="required">
			</div>
			<div class="form-group">
				<input class="form-control" placeholder="Pedido (digite apenas o número)" type="text" name="contPedido" id="contPedido">
			</div>
			<div class="form-group input-icone">
				<input class="form-control" placeholder="E-mail" type="email" name="contEmail" id="contEmail" required="required">
			</div>
			<div class="form-group input-icone">
				<input class="form-control" placeholder="Telefone (00) 0000-0000" type="text" name="contTelefone" id="contTelefone" maxlength="15" required="required">
			</div>
			<div class="form-group input-icone">
				<textarea class="form-control" placeholder="Mensagem" name="contMensagem" id="contMensagem" required="required"></textarea>
			</div>
			<div class="row">
				<div class="col-md-12 text-center">
					<label id="retornoContato"></label>
				</div>
			</div>
			<div class="row">
				<div class="box-btn col-md-12 text-right">
					<button type="reset" class="btn btn-default">Cancelar</button>
					<button type="submit" class="btn btn-primary">Enviar</button>
				</div>
			</div>
		</form>
		<script type="text/javascript">
		var meus_campos = {
				'contEmail': 'email',
				'contNome': 'nome',
				'contCPFCNPJ': 'CFPCNPJ',
				'contPedido': 'Pedido',
				'contTelefone': 'telefone',
				'contMensagem': 'Mensagem',
				'contAssunto': 'Assunto'
		 };
		options = { fieldMapping: meus_campos };
		RdIntegration.integrate('19be3ce6a7bd0b40fbe376160c87784f', 'Formulario_Atendimento_Contato', options); 
		</script>
		<script>
			jQuery(function($){
				$("#contTelefone").mask("(99) 99999999?9");
			});
		</script> 
	</div>
</section>