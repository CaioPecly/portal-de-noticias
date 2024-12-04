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

// Verifica se o parâmetro 'id' foi passado na URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
   // Busca a matéria completa no banco
    $sql = "SELECT * FROM imagens_materias WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    // Exibe a matéria, se encontrada
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $imagem = $row['imagem'];
        $materia = $row['materia'];
        
        echo "<!DOCTYPE html>";
        echo "<html lang='pt-br'>";
        echo "<head>";
        echo "<meta charset='UTF-8'>";
        echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
        echo "<title>Matéria Completa</title>";
        
        // Estilo da página
        echo "<style>";
        echo "body {";
        echo "    background-color: #0a0a23; /* Fundo azul escuro neon */";
        echo "    color: white;";
        echo "    font-family: Arial, sans-serif;";
        echo "    text-align: center;";
        echo "    padding: 20px;";
        echo "}";
        echo "h1 {";
        echo "    color: #00ffff; /* Texto neon ciano */";
        echo "}";
        echo "h2 {";
        echo "    color: #ff00ff; /* Texto neon rosa */";
        echo "}";
        echo "img {";
        echo "    border: 2px solid #00ffff; /* Borda neon */";
        echo "    border-radius: 10px;       /* Borda arredondada */";
        echo "    margin: 10px 0;";
        echo "    width: 100%; /* Imagem responsiva, ajusta-se ao tamanho da tela */";
        echo "    max-width: 600px; /* Limite máximo de largura */";
        echo "}";
        echo "button {";
        echo "    background-color: #ff00ff; /* Fundo rosa neon */";
        echo "    color: white;";
        echo "    border: none;";
        echo "    padding: 10px 20px;";
        echo "    margin-top: 20px;";
        echo "    cursor: pointer;";
        echo "    border-radius: 5px;        /* Borda arredondada */";
        echo "    font-size: 16px;";
        echo "    transition: transform 0.3s ease, background-color 0.3s ease;";
        echo "}";
        echo "button:hover {";
        echo "    transform: scale(1.1);         /* Aumenta o botão */";
        echo "    background-color: #ff33ff;    /* Rosa mais claro no hover */";
        echo "}";
        echo "</style>";
        echo "</head>";
        
        echo "<body>";
        
        echo "<h1>Matéria Completa</h1>";
        echo "<p>$materia</p>";
        echo "<img src='data:image/jpeg;base64," . base64_encode($imagem) . "' alt='Imagem' width='600'><br><br>";

        echo "<button onclick='history.back()'>Voltar</button>";  // Botão para voltar à página anterior

        echo "</body>";
        echo "</html>";
    } else {
        echo "<p>Matéria não encontrada.</p>";
    }
} else {
    echo "<p>Parâmetro ID não fornecido.</p>";
}   
    
?>
