<?php
session_start();

// Conexão com o banco de dados
$host = "localhost:3306";
$dbname = 'portal';
$username = "root";
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erro na conexão: " . $e->getMessage();
    exit;
}

// Verificando se o formulário de login foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['email'], $_POST['senha'])) {
        $email = $_POST['email'];
        $senha = $_POST['senha'];

        // Preparando o comando SQL para buscar o usuário no banco
        $sql = "SELECT * FROM usuarios WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        // Verificando se o usuário foi encontrado
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Verificando se a senha fornecida bate com a criptografada no banco
            if (password_verify($senha, $row['senha'])) {
                // Login bem-sucedido
                $_SESSION['usuario_id'] = $row['id'];
                $_SESSION['usuario_nome'] = $row['nome'];
                $_SESSION['usuario_tipo'] = $row['tipo_usuario'];  // Armazenando o tipo de usuário

                // Criando um cookie para manter o usuário logado por 1 dia
                setcookie('usuario_id', $row['id'], time() + (86400), "/"); // O cookie expira em 1 dia

                // Redirecionar para a página correta após o login
                if ($row['tipo_usuario'] == 'admin') {
                    // Se for administrador, redireciona para a página de aprovação
                    header("Location: aprovacao.php");
                } else {
                    // Se for escritor, redireciona para a página de criar matéria
                    header("Location: cria_materia.php");
                }
                exit();
            } else {
                echo "Senha incorreta.";
            }
        } else {
            echo "Usuário não encontrado.";
        }
    } else {
        echo "Por favor, preencha todos os campos.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            background-color: #0a0a23;
            color: white;
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 50px;
        }
        h1 {
            color: #00ffff;
            font-size: 36px;
        }
        label, input {
            font-size: 18px;
            color: #ff00ff;
        }
        input[type="email"], input[type="password"] {
            margin: 10px 0;
            padding: 10px;
            width: 80%;
            border: 2px solid #00ffff;
            background-color: #1c1c1c;
            color: white;
            border-radius: 10px;
        }
        input[type="submit"] {
            background-color: #ff00ff;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #ff33ff;
        }
    </style>
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <form method="POST">
        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" required><br><br>
        
        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required><br><br>
        
        <input type="submit" value="Entrar">
    </form>
</body>
</html>
