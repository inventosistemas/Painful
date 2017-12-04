<?php
$phpPost = filter_input_array(INPUT_POST);

define('HoorayWeb', TRUE);

include_once ("../p_settings.php");

if (!empty($phpPost['postenviarcadbasico']) && $phpPost['postenviarcadbasico'] == md5("cadastro"))
{
    $dataNasc = preg_replace('/\D/', '', $phpPost['postnascimento']);

    $diaNasc = (is_numeric(substr($dataNasc, 0, 2))) ? substr($dataNasc, 0, 2) : 0;
    $mesNasc = (is_numeric(substr($dataNasc, 2, 2))) ? substr($dataNasc, 2, 2) : 0;
    $anoNasc = (is_numeric(substr($dataNasc, 4, 4))) ? substr($dataNasc, 4, 4) : 0;
    
    $numTel = preg_replace('/\D/', '', $phpPost['posttelefone']);
    
    if (trim($phpPost['postnome']) == "")
    {
        echo "!!Por favor informe seu nome.";
    }
    elseif (!checkdate($mesNasc, $diaNasc, $anoNasc))
    {
        echo "!!Por favor informe a data de nascimento no formato DD/MM/AAAA.";
    }
    elseif ((!preg_match("/^\([0-9]{2}\) [0-9]{8,9}$/", $phpPost['posttelefone'])) &&
            (!preg_match("/^\([0-9]{2}\) [0-9]{4,5}-[0-9]{4}$/", $phpPost['posttelefone'])))
    {
        echo "!!Por favor informe o tefone no formato (00) 0000-0000.";
    }
    else 
    {
        $cadastroUsuario = ["LoginID" => $phpPost['postloginid'],
                            "Nome" => $phpPost['postnome'],
                            "DDDTelefone" => substr($numTel, 0, 2),
                            "Telefone" => substr($numTel, 2),
                            "DataNascimento" => $anoNasc . "-". $mesNasc . "-" . $diaNasc
            ];
        
        $atualizaoCadastro = sendRest($endPoint['alterarcadastro'], $cadastroUsuario, "PUT");
        
        echo "Cadastro atualizado.";
    }
}    

if (!empty($phpPost['postenviarcadcompleto']) && $phpPost['postenviarcadcompleto'] == md5("cadastro"))
{
    $dataNasc = preg_replace('/\D/', '', $phpPost['postnascimento']);

    $diaNasc = (is_numeric(substr($dataNasc, 0, 2))) ? substr($dataNasc, 0, 2) : 0;
    $mesNasc = (is_numeric(substr($dataNasc, 2, 2))) ? substr($dataNasc, 2, 2) : 0;
    $anoNasc = (is_numeric(substr($dataNasc, 4, 4))) ? substr($dataNasc, 4, 4) : 0;
    
    $numCNPJ = preg_replace('/\D/', '', $phpPost['postcnpj']);
    
    $numTel = preg_replace('/\D/', '', $phpPost['posttelefone']);
    
    $cepTratado = preg_replace('/\D/', '', $phpPost['postcep']);
    $cepConsulta = (!empty($cepTratado)) ? $cepTratado : "0";
    $enderecoPorCEP = getRest(str_replace("{CEP}", $cepConsulta, $endPoint['enderecoporcep']));    
    
    if (trim($phpPost['postnome']) == "")
    {
        echo "!!Por favor informe seu nome.";
    }
    elseif (!filter_var($phpPost['postemail'], FILTER_VALIDATE_EMAIL))
    {
        echo "!!Por favor informe um endereço de e-mail válido.";
    }
    elseif ((!preg_match("/^\([0-9]{2}\) [0-9]{4,5}-[0-9]{4}$/", $phpPost['posttelefone'])) &&
            (!preg_match("/^\([0-9]{2}\) [0-9]{8,9}$/", $phpPost['posttelefone'])))
    {
        echo "!!Por favor informe o tefone no formato (00) 0000-0000.";
    }
    elseif (!checkdate($mesNasc, $diaNasc, $anoNasc))
    {
        echo "!!Por favor informe a data de nascimento no formato DD/MM/AAAA.";
    }
    elseif ($phpPost['postsexo'] == "2" && strlen($numCNPJ) != 14)
    {
        echo "!!Por favor informe o CNPJ com 14 digitos.";
    }
    elseif (($phpPost['postsexo'] == "0" || $phpPost['postsexo'] == "1") && strlen($numCNPJ) != 11)
    {
        echo "!!Por favor informe o CPF com 11 digitos.";
    }
    elseif (trim($phpPost['postcep']) == "")
    {
        echo "!!Por favor informe o CEP.";
    }
    elseif (empty($enderecoPorCEP))
    {
        echo "!!O CEP informado é inválido.";
    }
    elseif (trim($phpPost['postlogradouro']) == "")
    {
        echo "!!Por favor informe o CEP e clique em \"Buscar endereço\"";
    }
    elseif (trim($phpPost['postendnumero']) == "")
    {
        echo "!!Por favor informe o número.";
    }
    elseif (trim($phpPost['postbairro']) == "")
    {
        echo "!!Por favor informe o bairro.";
    }
    elseif (trim($phpPost['postcidade']) == "")
    {
        echo "!!Por favor informe o CEP e clique em \"Buscar endereço\"";
    }
    elseif (trim($phpPost['postuf']) == "")
    {
        echo "!!Por favor informe o CEP e clique em \"Buscar endereço\"";
    }    
    elseif ($phpPost['postsenha'] != $phpPost['postconfsenha'])
    {
        echo "!!As senhas informadas não coincidem.";
    }
    elseif (strlen($phpPost['postsenha']) < TamanhoSenha)
    {
        echo "!!A senha deve ter pelo menos " . TamanhoSenha . " caracteres.";
    }
    else 
    {
        $dadosCadastro = ["CNPJ" => $numCNPJ,
                        "DataNascimento" => $anoNasc . "-" . $mesNasc . "-" . $diaNasc,
                        "Email" => $phpPost['postemail'],
                        "Fantasia" => $phpPost['postnome'],
                        "RazaoSocial" => $phpPost['postnome'],
                        "RG" => "",
                        "Sexo" => $phpPost['postsexo'],
                        "DDDTelefone" => substr($numTel, 0, 2),
                        "Telefone" => substr($numTel, 2),
                        "TipoPessoa" => ($phpPost['postsexo'] == 2) ? "0" : "1",
                        "Senha" => $phpPost['postsenha']
            ];
        
        $fazerCadastro = sendRest($endPoint['cadastroparte1'], $dadosCadastro, "POST");
        
        if (!empty($fazerCadastro['Erro']))
        {
            echo "!!" . $fazerCadastro['Mensagem'];
        }
        else
        {
            $dadosEnderecoPrincipal = ["ParceiroID" => $fazerCadastro['ParceiroID'],
                                        "Destinatario" => $phpPost['postnome'],
                                        "Identificacao" => "Endereço Principal",
                                        "CEP" => $cepTratado,
                                        "Logradouro" => $phpPost['postlogradouro'],
                                        "Numero" => $phpPost['postendnumero'],
                                        "Complemento" => (!empty($phpPost['postcomplemento'])) ? $phpPost['postcomplemento'] : "",
                                        "Bairro" => $phpPost['postbairro'],
                                        "CidadeID" => $enderecoPorCEP['Cidade']['ID'],
                                        "Principal" => TRUE,
                ];

            $cadastrarEndereco = sendRest($endPoint['addendereco'], $dadosEnderecoPrincipal, "POST");
            
            if (!empty($cadastrarEndereco['ID']))
            {
                $endPadrao = sendRest(str_replace("{IDEndereco}", $cadastrarEndereco['ID'], $endPoint['endpadrao']), [], "PUT");
                
                echo $fazerCadastro['Mensagem'] . " Faça seu login e boas compras.";
            }
            else
            {
                echo "!!Erro ao cadastrar endereço.";
            }            
        }
    }
}    

if (!empty($phpPost['postenviarendereco']) && $phpPost['postenviarendereco'] == md5("alterarEndereco"))
{
    $cepTratado = preg_replace('/\D/', '', $phpPost['postcep']);
    $cepConsulta = (!empty($cepTratado)) ? $cepTratado : "0";
    $enderecoPorCEP = getRest(str_replace("{CEP}", $cepConsulta, $endPoint['enderecoporcep']));
    
    if (trim($phpPost['postdestinatario']) == "")
    {
        echo "!!Por favor informe o destinatário.";
    }
    elseif (trim($phpPost['postcep']) == "")
    {
        echo "!!Por favor informe o CEP.";
    }
    elseif (empty($enderecoPorCEP))
    {
        echo "!!O CEP informado é inválido.";
    }
    elseif (trim($phpPost['postlogradouro']) == "")
    {
        echo "!!Por favor informe o CEP e clique em \"Buscar endereço\"";
    }
    elseif (trim($phpPost['postnumero']) == "")
    {
        echo "!!Por favor informe o número.";
    }
    elseif (trim($phpPost['postbairro']) == "")
    {
        echo "!!Por favor informe o bairro.";
    }
    elseif (trim($phpPost['postcidade']) == "")
    {
        echo "!!Por favor informe o CEP e clique em \"Buscar endereço\"";
    }
    elseif (trim($phpPost['postuf']) == "")
    {
        echo "!!Por favor informe o CEP e clique em \"Buscar endereço\"";
    }
    else
    {
        if (!empty($phpPost['postidendereco']))
        {
            $deleteEndereco = sendRest(str_replace("{IDEndereco}", $phpPost['postidendereco'], $endPoint['delendereco']), [], "DELETE");
        }
        
        $dadosEnderecoPrincipal = ["ParceiroID" => $phpPost['postidparceiro'],
                                    "Destinatario" => $phpPost['postdestinatario'],
                                    "Identificacao" => "Endereço Principal",
                                    "CEP" => $cepTratado,
                                    "Logradouro" => $phpPost['postlogradouro'],
                                    "Numero" => $phpPost['postnumero'],
                                    "Complemento" => (!empty($phpPost['postcomplemento'])) ? $phpPost['postcomplemento'] : "",
                                    "Bairro" => $phpPost['postbairro'],
                                    "CidadeID" => $enderecoPorCEP['Cidade']['ID'],
                                    "Principal" => TRUE,
            ];
        
        $atualizacaoEndereco = sendRest($endPoint['addendereco'], $dadosEnderecoPrincipal, "POST");
        
        if (!empty($atualizacaoEndereco['ID']))
        {
            if ($phpPost['posttipoendereco'] == "principal")
            {
                $endPadrao = sendRest(str_replace("{IDEndereco}", $atualizacaoEndereco['ID'], $endPoint['endpadrao']), [], "PUT");
            }
            echo "Endereco atualizado.";
        }
        else
        {
            echo "!!Erro ao atualizar endereço.";
        }
    }
}    

if (!empty($phpPost['postapagarendereco']) && $phpPost['postapagarendereco'] == md5("excluirEndereco"))
{

    if (empty($phpPost['postidendereco']) || !is_numeric($phpPost['postidendereco']))
    {
        echo "!!Erro ao excluir endereço.";
    }    
    else
    {
        $deleteEndereco = sendRest(str_replace("{IDEndereco}", $phpPost['postidendereco'], $endPoint['delendereco']), [], "DELETE");    
        
        echo "Endereço excluído.";
    }
}

if (!empty($phpPost['postbuscarcep']) && $phpPost['postbuscarcep'] == md5("buscarCep"))
{
    $cepTratado = preg_replace('/\D/', '', $phpPost['postcep']);

    $cepConsulta = (!empty($cepTratado)) ? $cepTratado : "0";

    $enderecoPorCEP = getRest(str_replace("{CEP}", $cepConsulta, $endPoint['enderecoporcep']));

    if (!empty($enderecoPorCEP))
    {
        echo $enderecoPorCEP['ID']
           . '#' . $enderecoPorCEP['Tipo'] . " " . $enderecoPorCEP['Nome']
           . '#' . $enderecoPorCEP['Cidade']['Nome']
           . '#' . $enderecoPorCEP['Cidade']['Estado']['Sigla'];
    }
    else
    {
        echo "!!CEP não encontrado.";
    }    
}

if (!empty($phpPost['postalterarsenha']) && $phpPost['postalterarsenha'] == md5("alterarSenha"))
{
    if (trim($phpPost['postsenhaatual']) == "")
    {
        echo "Por favor informe a senha atual.";
    }
    elseif (trim($phpPost['postnovasenha']) == "")
    {
        echo "Por favor informe a nova senha.";
    }
    elseif(trim($phpPost['postconfnovasenha']) == "")
    {
        echo "Por favor informe a confirmação da nova senha.";
    }
    elseif ($phpPost['postnovasenha'] != $phpPost['postconfnovasenha'])
    {
        echo "As senhas informadas não coincidem.";
    }
    elseif (strlen($phpPost['postnovasenha']) < TamanhoSenha)
    {
        echo "A nova senha deve ter pelo menos " . TamanhoSenha . " caracteres.";
    } 
    elseif ($phpPost['postsenhaatual'] == $phpPost['postnovasenha'])
    {
        echo "A nova senha deve ser diferente da senha anterior.";
    }
    else
    {
        $dadosSenha = ["LoginID" => $phpPost['postloginid'],
                       "SenhaAntiga" => $phpPost['postsenhaatual'],
                       "NovaSenha" => $phpPost['postnovasenha']
            ];
        
        $trocaSenha = sendRest($endPoint['atualizarsenha'], $dadosSenha, "PUT");
        
        if (!empty($trocaSenha['Mensagem']))
        {
            echo $trocaSenha['Mensagem'];
        }
        else
        {
            echo "Erro na troca da senha.";
        }
    }
}

if (!empty($phpPost['postwishlist']) && $phpPost['postwishlist'] == md5("adicionar"))
{
    if ($phpPost['postidlogin'] < 0)
    {
        echo "Por favor efetue login para adicionar o produto à sua Wishlist.";
    }
    else
    {
        $dadosWishList = ["LoginID" => $phpPost['postidlogin'],
                          "ProdutoID" => $phpPost['postidproduto']
            ];
        
        $addWL = sendRest($endPoint['addwishlist'], $dadosWishList, "POST");
        
        if ($addWL['ID'] > 1 && $addWL['Gravou'] == true)
        {
            echo "Produto adicionado à sua Wishlist!";
        }
        else
        {
            echo "Este produto já havia sido adicionado à sua Wishlist.";
        }
    }
}

if (!empty($phpPost['postwishlist']) && $phpPost['postwishlist'] == md5("remover"))
{
    if ($phpPost['postidlogin'] < 0)
    {
        echo "Por favor efetue login para remover o produto da sua Wishlist.";
    }
    else
    {
        $dadosWishList = ["LoginID" => $phpPost['postidlogin'],
                          "ProdutoID" => $phpPost['postidproduto']
            ];
        
        $removeWL = sendRest($endPoint['delwishlist'], $dadosWishList, "DELETE");
        
        if ($removeWL['Gravou'] == true)
        {
            echo "<div class=\"col-md-12\">";
            echo "Produto removido da sua Wishlist.";
            echo "</div>";
        }
        else
        {
            echo "<div class=\"col-md-12\">";
            echo $removeWL['Mensagem'];
            echo "</div>";
        }
    }
}

if (!empty($phpPost['postrecsenha']) && $phpPost['postrecsenha'] == md5("recuperarsenha"))
{
    if (!filter_var($phpPost['postemail'], FILTER_VALIDATE_EMAIL))
    {
        echo "Por favor informe um endereço de e-mail válido.";
    }
    else
    {
        $recSenha = sendRest(str_replace("{endEmail}", $phpPost['postemail'], $endPoint['recuperarsenha']), [], "PUT");
        
        if ($recSenha)
        {
            echo "Em breve você receberá um e-mail com instruções para cadastrar uma nova senha.";
        }
        else
        {
            echo "Endereço não encontrado na nossa base de dados.";
        }
    }
}

if (!empty($phpPost['postcadastrarsenha']) && $phpPost['postcadastrarsenha'] == md5("cadastrarSenha"))
{
    if (trim($phpPost['postnovasenha']) == "")
    {
        echo "Por favor informe a nova senha.";
    }
    elseif(trim($phpPost['postconfnovasenha']) == "")
    {
        echo "Por favor informe a confirmação da nova senha.";
    }
    elseif ($phpPost['postnovasenha'] != $phpPost['postconfnovasenha'])
    {
        echo "As senhas informadas não coincidem.";
    }
    elseif (strlen($phpPost['postnovasenha']) < TamanhoSenha)
    {    
        echo "A nova senha deve ter pelo menos " . TamanhoSenha . " caracteres.";
    }
    else
    {
        $validarRecSenha = getRest(str_replace(['{IDLogin}','{IDToken}'], ['-1',$phpPost['posttoken']], $endPoint['tokensenha']));
        
        if ($validarRecSenha)
        {
            $dadosSenha = ["LoginID" => "-1",
                           "TokenID" => $phpPost['posttoken'],
                           "NovaSenha" => $phpPost['postnovasenha']
                ];
            
            $retornoTrocaSenha = sendRest($endPoint['alterarsenha'], $dadosSenha, "PUT");
            
            if ($retornoTrocaSenha)
            {
                echo "Senha alterada com sucesso. Faça seu login e boas compras.";
            }
            else
            {
                echo "Erro na troca da senha.";
            }
        }
        else
        {
            echo "Token de troca de senha expirado.";
        }
    }
}
?>