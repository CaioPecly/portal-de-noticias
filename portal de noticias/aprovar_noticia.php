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

// Verificando se os dados estão presentes
if (isset($_POST['id'], $_POST['acao'])) {
    $id = $_POST['id'];
    $acao = $_POST['acao']; // 'aprovado' ou 'rejeitado'

    // Atualizando o status da notícia
    $sql = "UPDATE imagens_materias SET status = :status WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':status', $acao);
    $stmt->bindParam(':id', $id);
    
    if ($stmt->execute()) {
        echo "Notícia $acao com sucesso!";
        header("Location: aprovacao.php"); // Redireciona de volta para a página de aprovação
    } else {
        echo "Erro ao atualizar status da notícia.";
    }
}
?>
