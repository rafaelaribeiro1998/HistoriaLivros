<?php
// Conexão com o banco de dados (substitua pelos seus próprios detalhes de conexão)
$usuario = "root";
$senha = "BemVindo!";
$database = "livraria";
$host = "localhost";

$mysqli = new mysqli($host, $usuario, $senha, $database);

// Verificar a conexão
if ($mysqli->connect_errno) {
    echo "Falha ao conectar ao MySQL: " . $mysqli->connect_error;
    exit();
}

// Verifica se a variável POST 'carrinho' não está vazia
if (!empty($_POST['carrinho'])) {
    // Decodifica a string JSON e atribui à array $carrinho
    $carrinho = json_decode($_POST['carrinho'], true);

    // Coletar dados do cliente
    $nomeCliente = $_POST['nomeCompleto'];
    $emailCliente = $_POST['email'];
    $endereco = $_POST['endereco'];
    $cidade = $_POST['cidade'];
    $cep = $_POST['zipCode'];
    $metodoPagamento = $_POST['paymentMethod'];

    // Inserir dados do cliente na tabela CarrinhoCheckoutTotal
    $sqlCheckout = "INSERT INTO CarrinhoCheckoutTotal (Nome_do_Cliente, Email, Endereco, Cidade, CEP, Metodo_de_Pagamento) 
                    VALUES (?, ?, ?, ?, ?, ?)";

    $stmtCheckout = $mysqli->prepare($sqlCheckout);
    if (!$stmtCheckout) {
        echo "Erro ao preparar a consulta: " . $mysqli->error;
        exit();
    }
    $stmtCheckout->bind_param("ssssss", $nomeCliente, $emailCliente, $endereco, $cidade, $cep, $metodoPagamento);

    if ($stmtCheckout->execute()) {
        echo "Registro na tabela CarrinhoCheckoutTotal inserido com sucesso.<br>";
    } else {
        echo "Erro ao inserir registro na tabela CarrinhoCheckoutTotal: " . $stmtCheckout->error . "<br>";
    }

    $stmtCheckout->close();

    // Iterar sobre os itens do carrinho e adicioná-los à tabela CarrinhoCheckoutTotal
    foreach ($carrinho as $item) {
        if (isset($item['nome'], $item['preco'], $item['quantidade'])) {
            $nomeLivro = $item['nome'];
            $preco = $item['preco'];
            $quantidade = $item['quantidade'];
            $total = $preco * $quantidade;

            // Usando prepared statements para evitar injeção de SQL
            $stmt = $mysqli->prepare("INSERT INTO CarrinhoCheckoutTotal (Nome_do_Livro, Preco, Quantidade, Total) VALUES (?, ?, ?, ?)");
            if (!$stmt) {
                echo "Erro ao preparar a consulta: " . $mysqli->error;
                exit();
            }
            $stmt->bind_param("sidi", $nomeLivro, $preco, $quantidade, $total);

            if ($stmt->execute()) {
                echo "Registro do carrinho inserido com sucesso.<br>";
            } else {
                echo "Erro ao inserir registro no carrinho: " . $stmt->error . "<br>";
            }

            $stmt->close();

            echo "Nome do Livro: $nomeLivro, Preço: $preco, Quantidade: $quantidade, Total: $total<br>";
        } else {
            // Lida com o caso em que um índice necessário está ausente
            echo "Erro: Índices ausentes no item do carrinho.<br>";
        }
    }

    // Calcular o total a pagar
    $totalPagar = 0;
    foreach ($carrinho as $item) {
        if (isset($item['preco'], $item['quantidade'])) {
            $totalPagar += $item['preco'] * $item['quantidade'];
        } else {
            // Lida com o caso em que um índice necessário está ausente
            echo "Erro: Índices ausentes no item do carrinho.<br>";
        }
    }

    // Inserir dados na tabela TotalCompra
    $sqlTotalCompra = "INSERT INTO TotalCompra (Nome_do_Cliente, Total_a_Pagar) VALUES (?, ?)";

    $stmtTotalCompra = $mysqli->prepare($sqlTotalCompra);
    if (!$stmtTotalCompra) {
        echo "Erro ao preparar a consulta: " . $mysqli->error;
        exit();
    }
    $stmtTotalCompra->bind_param("sd", $nomeCliente, $totalPagar);

    if ($stmtTotalCompra->execute()) {
        echo "Registro na tabela TotalCompra inserido com sucesso.<br>";
    } else {
        echo "Erro ao inserir registro na tabela TotalCompra: " . $stmtTotalCompra->error . "<br>";
    }

    $stmtTotalCompra->close();
} else {
    // A variável POST 'carrinho' está vazia, faça algo se necessário
    echo "Erro: A variável POST 'carrinho' está vazia.";
}

// Fechar conexão
$mysqli->close();
?>
