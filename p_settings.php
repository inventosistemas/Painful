<?php
if (!defined('HoorayWeb')) 
{
    die;
}
define ('URLSite', 'http://painful.inventosistemas.com.br/'); 
define ('URLWebAPI', 'http://painfulwebapi.inventosistemas.com.br/'); 

$endPoint = ['token'           => URLWebAPI . "Token",
            'login'            => URLWebAPI . "v1/login/",
            'alterarcadastro'  => URLWebAPI . "v1/cadastro/alterarcadastro/",
            'atualizarsenha'   => URLWebAPI . "v1/login/atualizarsenha/",
            'cadastroparte1'   => URLWebAPI . "v1/cadastroparte1/",
            'recuperarsenha'   => URLWebAPI . "v1/login/{endEmail}/recuperarsenha/",
            'tokensenha'       => URLWebAPI . "v1/login/{IDLogin}/{IDToken}/validartoken/",
            'alterarsenha'     => URLWebAPI . "v1/login/alterarsenha/",    
            'menu'             => URLWebAPI . "v1/vitrine/obtermenu3/",
            'banner'           => URLWebAPI . "v1/banner/",
            'marcas'           => URLWebAPI . "v1/vitrine/obtermarcas/",
            'marcasdestaque'   => URLWebAPI . "v1/marca/destaque/",
            'detalhesmarca'    => URLWebAPI . "v1/marca/{IDMarca}/obterporid/",
            'vitrine'          => URLWebAPI . "v1/vitrine/16/",
            'rodape'           => URLWebAPI . "v1/rodape/",
            'maisvedidos'      => URLWebAPI . "v1/produto/maisvendidos/",
            'categoria'        => URLWebAPI . "v1/produto/{IDCategoria}/obtercategoria/",
            'prodcategoria'    => URLWebAPI . "v1/produto/{IDCategoria}/produtocategoria/",
            'produto'          => URLWebAPI . "v1/produto/{IDProduto}/",
            'busca'            => URLWebAPI . "v1/produto/buscar/",
            'buscaestendida'   => URLWebAPI . "v1/produto/buscaestendida/",
            'dadoscadastrais'  => URLWebAPI . "v1/cadastro/{IDParceiro}/",
            'endcadastral'     => URLWebAPI . "v1/cadastro/{IDParceiro}/obterenderecos/",
            'assuntoscontato'  => URLWebAPI . "v1/atendimento/obterlistaassuntos/",
            'enviarcontato'    => URLWebAPI . "v1/atendimento/gravar/",
            'relacionados'     => URLWebAPI . "v1/produto/{IDProduto}/produtosrelacionados/",
            'blog'             => URLWebAPI . "v1/vitrine/blog/",
            'blogdestaques'    => URLWebAPI . "v1/vitrine/blog/{IDBlog}/{count}/obterartigosdestaque/",
            'blogrecentes'     => URLWebAPI . "v1/vitrine/blog/{IDBlog}/{count}/obterartigosblog/",
            'blogcategorias'   => URLWebAPI . "v1/vitrine/blog/categorias/",
            'blogbusca'        => URLWebAPI . "v1/vitrine/blog/{IDBlog}/{termoBusca}/buscartexto/",
            'blogartigo'       => URLWebAPI . "v1/vitrine/blog/{IDArtigo}/obterdetalheartigo/",
            'blogartigoscat'   => URLWebAPI . "v1/vitrine/blog/{IDBlog}/{IDCategoria}/buscarcategoria/",
            'parcelamento'     => URLWebAPI . "v1/pagamento/{IDProduto}/{valorProduto}/obterparcelamento/",
            'enderecoporcep'   => URLWebAPI . "v1/cadastro/{CEP}/obterlogradourocep/",
            'delendereco'      => URLWebAPI . "v1/cadastro/{IDEndereco}/deletarendereco/",
            'addendereco'      => URLWebAPI . "v1/cadastro/adicionarendereco/",
            'endpadrao'        => URLWebAPI . "v1/cadastro/{IDEndereco}/marcarenderercopadrao/",
            'newsletter'       => URLWebAPI . "v1/cadastro/newsletter/",
            'obtercarrinho'    => URLWebAPI . "v1/carrinho/{IDCarrinho}/",
            'addcarrinho'      => URLWebAPI . "v1/carrinho/item/adicionaritem/",
            'delcarrinho'      => URLWebAPI . "v1/carrinho/item/{IDItem}/delete/",
            'qtdecarrinho'     => URLWebAPI . "v1/carrinho/item/adicionarquantidade/",
            'addlogincarrinho' => URLWebAPI . "v1/carrinho/adicionarlogin/",
            'fretecarrinho'    => URLWebAPI . "v1/carrinho/calcularfrete/",
            'servicoentrega'   => URLWebAPI . "v1/carrinho/obterservicosentrega/",
            'atualizarfrete'   => URLWebAPI . "v1/carrinho/atualizarservicofreteloja/",
            'checkout'         => URLWebAPI . "v1/pedido/gravarpedido/",
            'formaspagamento'  => URLWebAPI . "v1/pagamento/{valorCarrinho}/obterformaspagamentoloja2/",
            'parcarrinho'      => URLWebAPI . "v1/pagamento/{IDCarrinho}/{valorCarrinho}/obterparcelamentocarrinho/",
            'addwishlist'      => URLWebAPI . "v1/produto/adicionarwishlist/",
            'delwishlist'      => URLWebAPI . "v1/produto/removerwishlist/",
            'freteproduto'     => URLWebAPI . "v1/produto/{cep}/{produtoID}/{skuID}/calcularfrete",
            'obterwishlist'    => URLWebAPI . "v1/produto/{IDLogin}/obterwishlistporlogin/",
            'meuspedidos'      => URLWebAPI . "v1/pedido/{IDParceiro}/meuspedidosheader/",
            'meuspedidosdet'   => URLWebAPI . "v1/pedido/{IDPedido}/meuspedidosdetail/"
    ];
define ('MoedaDecimal', ',');
define ('MoedaMilhar', '.');
define ('MoedaCasas', 2);
define ('MoedaSimbolo', 'R$');
define ('TamanhoSenha', 8);
//define ('MOIPPublicKey', 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAwxeLs9v3RgM8+mt/8NNoUjwZ8iG8oTVcJu5pLH934RYIU9OUEoZL216BuAKZzNw9cz8KTj2Yofpb1pSFy/Umc4STY5H3sAx5eET7O78aX43VqEON2EnMbibIQQdVO/pIcd6wOXu8pxv8hyTzCdluG0SHuWlBxGDJuGtuoxtD1GHeMCP08wIx0r/DDuNvl2BJJ63+j/cwa3IW/aiqvrBraJqVZFCT8PAmtTpk9gXLbcTVeQu3u8bPmmgdACZjFd2bymJyuZ+cl1Ou70DXmqhjJP+HeUd1U9feR/qKamowPGQtG6et/KgjTdn8yeHB37kgx0ZX3Bs3nKa7bQmEFlUm8QIDAQAB');
$meses = ["1" => "Janeiro",
          "2" => "Fevereiro",
          "3" => "Março",
          "4" => "Abril",
          "5" => "Maio",
          "6" => "Junho",
          "7" => "Julho",
          "8" => "Agosto",
          "9" => "Setembro",
          "10" => "Outubro",
          "11" => "Novembro",
          "12" => "Dezembo"
    ];
$sexo = [0 => "Feminino", 
         1 => "Masculino",
         2 => "Empresa"
    ];
$tipoEndereco = [0 => "Principal",
                 1 => "Entrega",
    ];
$tipoDisponibilidade = [0 => "Em estoque",
                        1 => "Sob encomenda",
                        2 => "Indisponível"
    ];
function login (string $url, string $bearer)
{
    $ch = curl_init($url);
    
    $authorization = 'Authorization: ' . $bearer;
    
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization ));
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    
    $result = curl_exec($ch);
    
    curl_close($ch);
    
    $retornoLogin = json_decode($result, TRUE);
    
    return $retornoLogin;
}
function getRest(string $url) 
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    $response = curl_exec($ch);
    $retornoArray = json_decode($response, TRUE);
    curl_close($ch);
    return $retornoArray;
}
function sendRest(string $url, array $dados, string $metodo)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $metodo);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dados));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    $response = curl_exec($ch);
    
    $retornoArray = json_decode($response, TRUE);
    
    return $retornoArray;
}
function formatar_moeda(float $valor)
{
    $valorFormatado = MoedaSimbolo
                    . " "
                    . number_format($valor, MoedaCasas, MoedaDecimal, MoedaMilhar);
    
    return $valorFormatado;
}
function mascara(string $val, string $mask)
{
    $maskared = '';
    $k = 0;
    for($i = 0; $i <= strlen($mask) -1; $i++)
    {
        if ($mask[$i] == '#')
        {
            if (isset($val[$k])) $maskared .= $val[$k++];
        }
        else
        {
            if (isset($mask[$i])) $maskared .= $mask[$i];
        }
    }
    return $maskared;
}
?>
