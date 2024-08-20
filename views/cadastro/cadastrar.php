<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <h1>Cadastrar</h1><br>
    <form method="post" action="cadastro.php">
    
        <div class="input-group flex-nowrap">
            <span class="input-group-text" id="addon-wrapping">Nome de usuario</span>
            <input type="text" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="addon-wrapping">
        </div>

        <div class="input-group flex-nowrap">
            <span class="input-group-text" id="addon-wrapping">Email</span>
            <input type="email" class="form-control" placeholder="Digite seu email" aria-label="email" aria-describedby="addon-wrapping" name="email">
        </div>

        <div class="input-group flex-nowrap">
            <span class="input-group-text" id="addon-wrapping">Sua senha</span>
            <input type="password" class="form-control" placeholder="Escolha uma senha" aria-label="senha" aria-describedby="addon-wrapping" name="senha">
        </div>
    
    </form>
    <br>
    <a href="/sistema-login-completo/views/login/index.php">Cadastrar</a>

</body>
</html>