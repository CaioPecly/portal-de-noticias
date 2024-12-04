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
    if (isset($_POST['titulo'], $_POST['materia'], $_FILES['imagem'])) {

        // Obtendo dados do formulário
        $titulo = $_POST['titulo'];
        $materia = $_POST['materia'];
        $imagem = file_get_contents($_FILES['imagem']['tmp_name']);

        // Preparando o comando SQL para inserir no banco de dados
        $sql = "INSERT INTO imagens_materias (imagem, materia, titulo, status) VALUES (:imagem, :materia, :titulo, 'pendente')";
        $stmt = $pdo->prepare($sql);

        // Bind dos parâmetros
        $stmt->bindParam(':imagem', $imagem, PDO::PARAM_LOB); // Bind correto para a imagem (BLOB)
        $stmt->bindParam(':titulo', $titulo, PDO::PARAM_STR); // Bind correto para o título (STRING)
        $stmt->bindParam(':materia', $materia, PDO::PARAM_STR); // Bind correto para a matéria (STRING)

        // Executando o comando
        if ($stmt->execute()) {
            echo "Notícia enviada com sucesso! Aguardando aprovação.";
        } else {
            echo "Erro ao enviar a notícia.";
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
    <title>Criação de Notícia</title>
    <style>
        /* Estilos personalizados conforme solicitado anteriormente */
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
        label, textarea {
            font-size: 18px;
            color: #ff00ff;
        }
        input[type="file"], textarea, input[type="submit"] {
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
    <h1>Crie sua Notícia</h1>
    <form action="upload.php" method="POST" enctype="multipart/form-data">
        <label for="imagem">Escolha uma imagem:</label>
        <input type="file" name="imagem" id="imagem" required><br><br>
        
        <label for="titulo">Título da Notícia:</label><br>
        <textarea name="titulo" id="titulo" rows="4" cols="50" required></textarea><br><br>

        <label for="materia">Texto da Notícia:</label><br>
        <textarea name="materia" id="materia" rows="4" cols="50" required></textarea><br><br>
        
        <input type="submit" value="Enviar para Aprovação">
    </form>
</body>
</html>

