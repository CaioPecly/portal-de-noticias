<?php
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

// Verificando se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['nome'], $_POST['email'], $_POST['senha'], $_POST['tipo_usuario'])) {
        
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $senha = $_POST['senha'];
        $tipo_usuario = $_POST['tipo_usuario'];

        // Criptografando a senha
        $senha_criptografada = password_hash($senha, PASSWORD_DEFAULT);

        // Preparando o comando SQL para inserir no banco de dados
        $sql = "INSERT INTO usuarios (nome, email, senha, tipo_usuario) VALUES (:nome, :email, :senha, :tipo_usuario)";
        $stmt = $pdo->prepare($sql);
        
        // Bind dos parâmetros
        $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':senha', $senha_criptografada, PDO::PARAM_STR);
        $stmt->bindParam(':tipo_usuario', $tipo_usuario, PDO::PARAM_STR);
        
        // Executando o comando
        if ($stmt->execute()) {
            echo "Cadastro realizado com sucesso!";
            
            // Redirecionar para a página de login após o cadastro
            header("Location: login.php"); // Agora o usuário vai para o login após o cadastro
            exit();
        } else {
            echo "Erro ao cadastrar usuário.";
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
    <title>Cadastro</title>
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
        label, input, select {
            font-size: 18px;
            color: #ff00ff;
        }
        input[type="text"], input[type="email"], input[type="password"], select {
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
</head>
<body>
    <h1>Cadastro de Usuário</h1>
    <form method="POST">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required><br><br>
        
        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" required><br><br>
        
        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required><br><br>

        <label for="tipo_usuario">Tipo de Usuário:</label>
        <select id="tipo_usuario" name="tipo_usuario" required>
            <option value="escritor">Escritor</option>
            <option value="admin">Administrador</option>
        </select><br><br>
        
        <input type="submit" value="Cadastrar">
    </form>
</body>
</html>
