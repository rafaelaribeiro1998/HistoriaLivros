<?php
include('conexao.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verifique se todos os campos obrigatórios estão preenchidos
    if (empty($_POST['nome']) || empty($_POST['email']) || empty($_POST['senha'])) {
        echo "Por favor, preencha todos os campos obrigatórios.";
    } else {
        $nome = $mysqli->real_escape_string($_POST['nome']);
        $email = $mysqli->real_escape_string($_POST['email']);
        $senha = $mysqli->real_escape_string($_POST['senha']);

        // Verifique se o email já está em uso
        $sql_check_email = "SELECT * FROM usuarios WHERE email = '$email'";
        $result_check_email = $mysqli->query($sql_check_email);

        if ($result_check_email->num_rows > 0) {
            // Exibir alerta em JavaScript
            echo "<script>alert('Este email já está cadastrado. Tente outro email.');</script>";
        }  else {
            // Insira os dados do novo usuário no banco de dados
            $sql_insert = "INSERT INTO usuarios (nome, email, senha) VALUES ('$nome', '$email', '$senha')";
            if ($mysqli->query($sql_insert)) {
                echo "<script>alert('Cadastro realizado com sucesso!');</script>";
            } else {
                echo "Erro ao cadastrar o usuário: " . $mysqli->error;
            }
        }
    }
}

if (isset($_POST['e-mail']) && isset($_POST['senha'])) {
    if (empty($_POST['e-mail'])) {
        echo "Preencha seu e-mail";
    } elseif (empty($_POST['senha'])) {
        echo "Preencha corretamente sua senha!";
    } else {
        $email = $mysqli->real_escape_string($_POST['e-mail']);
        $senha = $mysqli->real_escape_string($_POST['senha']);

        $sql_code = "SELECT * FROM usuarios WHERE email = '$email' AND senha = '$senha'";
        $sql_query = $mysqli->query($sql_code) or die("Falha na execução do código SQL: " . $mysqli->error);
        $quantidade = $sql_query->num_rows;

        if ($quantidade == 1) {
            $usuario = $sql_query->fetch_assoc();
            if (!isset($_SESSION)) {
                session_start();
            }

            $_SESSION['id'] = $usuario['id'];
            $_SESSION['nome'] = $usuario['nome'];

            header("Location: http://localhost/Historia/Historia/");
            exit;
        } else {
            echo "<script>alert('Falha ao efetuar o login! E-mail ou senha incorretos.');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Adicionar link para o Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Estilo adicional para personalizar o formulário */
        body {
            background-color: #fff;
            color: black;
        }

        .container {
            padding-top: 50px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        h2 {
            color: black;
        }
    </style>
    <title>Login e Cadastro</title>
</head>
<body>

<div class="container">
    <div class="row">
        <div class="col-md-6">
            <!-- Formulário de Cadastro com classes do Bootstrap -->
            <h5>Precisa de uma Conta?</h5>
            <br>
            <form action="" method="POST">
                <div class="form-group">
                    <label>Nome</label>
                    <input type="text" name="nome" class="form-control">
                </div>
                <div class="form-group">
                    <label>E-mail</label>
                    <input type="email" name="email" class="form-control">
                </div>
                <div class="form-group">
                    <label>Senha</label>
                    <input type="password" name="senha" class="form-control">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-dark">Cadastrar</button>
                </div>
            </form>
        </div>

        <div class="col-md-6">
            <!-- Formulário de Login com classes do Bootstrap -->
            <h5> Acesse a sua Conta</h5>
            <br>
            <form action="" method="POST">
                <div class="form-group">
                    <label>E-mail</label>
                    <input type="text" name="e-mail" class="form-control">
                </div>
                <div class="form-group">
                    <label>Senha</label>
                    <input type="password" name="senha" class="form-control">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-dark">Entrar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Adicionar script do Bootstrap (jQuery e Popper.js) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>