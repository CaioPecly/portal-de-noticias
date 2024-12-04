<?php
session_start(); // Verifica se a sessão foi iniciada

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

// Verificando se o administrador está logado (pode ser melhorado com um sistema de login)
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php"); // Redireciona para login se não estiver logado
    exit();
}

// Buscando todas as notícias pendentes
$sql = "SELECT * FROM imagens_materias WHERE status = 'pendente'";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$noticias = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aprovação de Notícias</title>
    <style>
        /* Estilos semelhantes à página de login */
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
        .noticia {
            margin: 20px;
            background-color: #1c1c1c;
            padding: 20px;
            border-radius: 10px;
            border: 2px solid #00ffff;
        }
        button {
            background-color: #ff00ff;
            color: white;
            border: none;
            padding: 10px;
            margin: 5px;
            cursor: pointer;
            border-radius: 5px;
        }
        button:hover {
            background-color: #ff33ff;
        }
        .btn-listar {
            background-color: #33cc33; /* Verde para listar */
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            margin: 20px;
            text-decoration: none;
        }
        .btn-listar:hover {
            background-color: #66cc66;
        }
    </style>
</head>
<body>
    <h1>Aprovação de Notícias</h1>

    <!-- Botão para listar notícias -->
    <a href="listar.php" class="btn-listar">Listar Notícias</a>

    <?php foreach ($noticias as $noticia): ?>
    <div class="noticia">
        <h3><?php echo htmlspecialchars($noticia['titulo']); ?></h3>
        <p><?php echo nl2br(htmlspecialchars($noticia['materia'])); ?></p>
        <img src="data:image/jpeg;base64,<?php echo base64_encode($noticia['imagem']); ?>" width="300" /><br><br>
        <form action="aprovar_noticia.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $noticia['id']; ?>">
            <button type="submit" name="acao" value="aprovado">Aprovar</button>
            <button type="submit" name="acao" value="rejeitado">Rejeitar</button>
        </form>
    </div>
    <?php endforeach; ?>
</body>
</html>
