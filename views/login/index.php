<?php
session_start();

// Verifica se o formulário foi enviado
if (isset($_POST['submit'])) {
    if (!empty($_POST['email']) && !empty($_POST['senha'])) {
        include_once('C:/xampp/htdocs/sistema-login-completo/config/config.php');

        $email = $_POST['email'];
        $senha = $_POST['senha'];

        // Verifica se a conexão foi estabelecida
        if ($conn) {
            // Prepara a consulta
            $sql = "SELECT * FROM usuarios WHERE email = ?";
            $stmt = $conn->prepare($sql);

            if ($stmt) {
                // Vincula os parâmetros
                $stmt->bind_param("s", $email);

                // Executa a consulta
                $stmt->execute();

                // Obtém o resultado
                $result = $stmt->get_result();

                // Verifica se há uma linha com o e-mail fornecido
                if ($result->num_rows === 1) {
                    $row = $result->fetch_assoc();

                    // Verifica a senha
                    if (password_verify($senha, $row['senha'])) {
                        // Define as variáveis de sessão
                        $_SESSION['email'] = $email;
                        $_SESSION['usuario'] = $row['usuario'];
                        $_SESSION['foto_perfil'] = $row['foto_perfil'];
                          // Use o nome correto do campo da sua tabela

                        // Redireciona para o site
                        header('Location: http://localhost/sistema-login-completo/views/dashboards/site.php');
                        exit();
                    } else {
                        $error = "Usuário ou senha incorretos";
                    }
                } else {
                    $error = "Usuário ou senha incorretos";
                }

                // Fecha a declaração
                $stmt->close();
            } else {
                $error = "Erro ao preparar a consulta: " . $conn->error;
            }

            // Fecha a conexão
            $conn->close();
        } else {
            $error = "Erro ao conectar ao banco de dados";
        }
    } else {
        $error = "Por favor, preencha todos os campos";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <h1>Login</h1>

    <form action="index.php" method="post">
        E-mail: <input type="email" name="email" required><br>
        Senha: <input type="password" name="senha" required><br>
        <input type="submit" value="Logar" name="submit">
    </form>
    <br>
    <a href="cadastrar.php">Ainda não é cadastrado?</a>

    <?php
    // Exibe a mensagem de erro, se existir
    if (isset($error)) {
        echo "<p>$error</p>";
    }
    ?>
</body>
</html>