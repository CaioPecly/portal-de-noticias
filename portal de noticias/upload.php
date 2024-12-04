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
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0 && isset($_POST['materia'])) {
        
        // Obtendo dados do formulário
        $titulo = $_POST['titulo'];
        $materia = $_POST['materia'];
        $imagem = file_get_contents($_FILES['imagem']['tmp_name']);

        // Preparando o comando SQL para inserir no banco de dados
        $sql = "INSERT INTO imagens_materias (imagem, materia, titulo) VALUES (:imagem, :materia, :titulo)";
        $stmt = $pdo->prepare($sql);
        
        // Bind dos parâmetros
        $stmt->bindParam(':imagem', $imagem, PDO::PARAM_LOB); // Bind correto para a imagem (BLOB)
        $stmt->bindParam(':titulo', $titulo, PDO::PARAM_STR); // Bind correto para o título (STRING)
        $stmt->bindParam(':materia', $materia, PDO::PARAM_STR); // Bind correto para a matéria (STRING)
        
        // Executando o comando
        if ($stmt->execute()) {
            // Redirecionando para a página listar.php após inserção bem-sucedida
            header("Location: listar.php");
            exit(); // Importante chamar o exit() após o redirecionamento
        } else {
            echo "Erro ao inserir os dados.";
        }
    } else {
        echo "Por favor, preencha todos os campos.";
    }
}
?>
