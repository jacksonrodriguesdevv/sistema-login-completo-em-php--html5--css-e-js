<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link rel="stylesheet" href="style.css">
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
<header>  
  <div class="search">
    <h1>LOGO</h1>
    <button type="button" class="btn btn-primary btn-sm">entrar</button>
  </div>
</header>


<div class="card mb-3" style="max-width: 61%; margin:auto;">
    <div class="row g-0">
        
        <div class="col-md-8" style="max-width: 50%;">
            <div class="card-body">
                <h5 class="card-title" style="color: #48da89;">Conheça a LOOV3 PRIVATE</h5>
                <p class="card-text" style="font-size: 31px; color:blueviolet">A melhor forma de produzir e monetizar seus Conteúdos 🔥</p> 
                <p class="card-text"><small class="text-muted"  style="font-size: 18px;">O Loov3 private oferece ferramentas exclusivas para transformar suas vendas e aumentar seus resultados! </small></p>
                <button type="button"  class="btn btn-primary btn-lg" style="background-color: #a201c1;" href="http://localhost/sistema-login-completo/views/cadastro/cadastrar.php">CRIAR CONTA</button>
                <button type="button" class="btn btn-secondary btn-lg" style="background-color: #f8ba59;" >ENTRAR</button>
            </div>
        </div>
        <div class="col-md-4" style="width: 46%;">
            <img src="http://localhost/sistema-login-completo/views/inicio/images/3.png" class="img-fluid rounded-start" alt="...">
        </div>
    </div>
</div>

<div class="entrega">
    <h1 class="entrega-h1">Nenhuma outra plataforma entrega tanto! 🚀</h1>
    <h3>O Anonimatta é a revolução do mercado de venda de conteúdos.</h3>
    <h2>Agora suas fotos e vídeos estão mais seguros e você ainda conta com uma série de serviços que não existem em nenhuma outra plataforma! Confira...</h2>
</div>



<div class="perfils">
    <?php
    // Inclua o arquivo de configuração que contém informações de conexão com o banco de dados
    include 'C:/xampp/htdocs/sistema-login-completo/config/config.php';

    // Verifique a conexão com o banco de dados
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Consulta para buscar todos os usuários com foto de perfil e foto de capa
    $sql = "SELECT foto_capa, foto_perfil, usuario FROM usuarios ORDER BY usuario";

    // Execute a consulta
    $result = $conn->query($sql);

    if ($result === FALSE) {
        die("Erro na consulta: " . $conn->error);
    }

    if ($result->num_rows > 0) {
        // Loop através dos resultados e exiba as informações dos usuários
        while ($row = $result->fetch_assoc()) {
            // Definir caminhos relativos para as imagens
            $foto_capa_relativo = !empty($row['foto_capa']) ? 'http://localhost/sistema-login-completo/views/dashboards/uploads/' . $row['foto_capa'] : 'views/dashboards/uploads/default-cover.png';
            $foto_perfil_relativo = !empty($row['foto_perfil']) ? 'http://localhost/sistema-login-completo/views/dashboards/uploads/' . $row['foto_perfil'] : 'views/dashboards/uploads/default-profile.png';
            $usuario = htmlspecialchars($row['usuario']);

            // Construir URLs completas para as imagens
            $foto_capa_url = 'http://' . $_SERVER['HTTP_HOST'] . '/' . $foto_capa_relativo;
            $foto_perfil_url = 'http://' . $_SERVER['HTTP_HOST'] . '/' . $foto_perfil_relativo;

            // Exiba a foto de capa e a foto de perfil
            echo "<div class='post'>";
            echo "<div class='user-info'>";
            echo "<img src='$foto_capa_url' alt='Foto de Capa' class='cover-pic'>";
            echo "<img src='$foto_perfil_url' alt='Foto de Perfil' class='profile-pic'>";
            echo "<a href='#'>$usuario</a>";
            echo "</div>"; // Fecha a div .user-info
            echo "</div>"; // Fecha a div .post
        }
    } else {
        // Caso não haja usuários no banco de dados
        echo "<p>Nenhum usuário encontrado.</p>";
    }

    // Fecha a conexão com o banco de dados
    $conn->close();
    ?>
</div>

</body>
</html>