<?php
// Conexão com o banco de dados
$host = "localhost:3306";
$dbname = 'portal';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erro na conexão: " . $e->getMessage();
    exit;
}

// Buscar as imagens e matérias no banco
$sql = "SELECT * FROM imagens_materias ORDER BY data_criacao DESC";
$stmt = $pdo->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Imagens e Matérias</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Imagens e Matérias</h1>
    <?php
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $id = $row['id']; 
        $titulo = $row['titulo'];
        $imagem = $row['imagem'];
        $materia = $row['materia'];

        // Exibindo a imagem e a matéria breve
        echo "<h2>titulo:</h2><p>$titulo</p>";
        echo "<img src='data:image/jpeg;base64," . base64_encode($imagem) . "' alt='Imagem' width='300'><br><br>";
        
        // Link para a página de detalhes
        echo "<a href='materia.php?id=$id'><button>Leia Mais</button></a>";
    }
    ?>
</body>
</html>

