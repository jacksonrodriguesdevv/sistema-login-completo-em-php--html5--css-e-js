<?php

require_once "C:/xampp/htdocs/sistema-login-completo/config/config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica se todos os campos foram preenchidos
    if (isset($_POST["usuario"], $_POST["email"], $_POST["senha"]) && 
        !empty($_POST["usuario"]) && 
        !empty($_POST["email"]) && 
        !empty($_POST["senha"])) {
        
        $usuario = $_POST["usuario"];
        $email = $_POST["email"];
        $senha = $_POST["senha"];

        // Valida o formato do e-mail
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            die("Formato de e-mail inválido.");
        }

        // Cria o hash da senha
        $hashed_password = password_hash($senha, PASSWORD_DEFAULT);

        // Verifica se a conexão com o banco de dados está funcionando
        if ($conn->connect_error) {
            die("Conexão falhou: " . $conn->connect_error);
        }

        // Prepara a consulta SQL
        $sql = "INSERT INTO usuarios (usuario, email, senha) VALUES (?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            // Vincula os parâmetros
            $stmt->bind_param("sss", $usuario, $email, $hashed_password);

            // Executa a consulta
            if ($stmt->execute()) {
                // Redireciona para a página de sucesso ou exibe uma mensagem de sucesso
                header('Location: C:/xampp/htdocs/sistema-login-completo/views/login/index.php"'); // Substitua 'sucesso.php' com a página desejada
                exit();
            } else {
                // Exibe erro específico da execução
                echo "Erro ao executar a consulta: " . $stmt->error;
            }

            // Fecha a instrução
            $stmt->close();
        } else {
            // Exibe erro específico da preparação
            echo "Erro ao preparar a consulta: " . $conn->error;
        }

        // Fecha a conexão
        $conn->close();
    } else {
        echo "Todos os campos são obrigatórios.";
    }
    echo "Senha Hasheada: " . $hashed_password . "<br>";
}
