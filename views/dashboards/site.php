<?php
session_start();
require_once "C:/xampp/htdocs/sistema-login-completo/config/config.php";

function logout() {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit;
}

function uploadFile($file) {
    $uploadDir = 'uploads/';
    $uploadFile = $uploadDir . basename($file['name']);

    if ($file['error'] !== UPLOAD_ERR_OK) {
        echo "Erro no upload do arquivo: " . $file['error'];
        return null;
    }

    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0755, true)) {
            echo "Erro ao criar diretório de upload.";
            return null;
        }
    }

    if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
        return $uploadFile;
    } else {
        echo "Erro ao mover o arquivo para o diretório de upload.";
        return null;
    }
}

if (!isset($_SESSION["email"])) {
    header("Location: index.php");
    exit;
}

if (isset($_POST["logout"])) {
    logout();
}

$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_SESSION["email"];
$profilePicture = $coverPicture = null;

// Processar o upload das imagens de perfil e capa
if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
    $profilePicture = uploadFile($_FILES['profile_picture']);
}

if (isset($_FILES['cover_picture']) && $_FILES['cover_picture']['error'] == 0) {
    $coverPicture = uploadFile($_FILES['cover_picture']);
}

if ($profilePicture || $coverPicture) {
    $updates = [];
    if ($profilePicture) {
        $updates[] = "foto_perfil = '{$conn->real_escape_string($profilePicture)}'";
    }
    if ($coverPicture) {
        $updates[] = "foto_capa = '{$conn->real_escape_string($coverPicture)}'";
    }

    if (!empty($updates)) {
        $query = "UPDATE usuarios SET " . implode(", ", $updates) . " WHERE email = '{$conn->real_escape_string($email)}'";

        if ($conn->query($query) === FALSE) {
            echo "Error: " . $conn->error;
        }
    }
}

$query = "SELECT foto_perfil, foto_capa FROM usuarios WHERE email = '{$conn->real_escape_string($email)}'";
$result = $conn->query($query);

if ($result && $row = $result->fetch_assoc()) {
    $profilePicture = $row['foto_perfil'];
    $coverPicture = $row['foto_capa'];
}

        //----------------------------------------------------
        //Publicações
        
        // Inclua o arquivo de conexão com o banco de dados
        include 'C:/xampp/htdocs/sistema-login-completo/config/config.php';
        //require_once "C:/xampp/htdocs/sistema-login-completo/config/config.php";

        if (isset($_POST['enviarpost'])) {
            // Obtém o texto do post e faz a verificação
            $texto = isset($_POST['novopost']) ? trim($_POST['novopost']) : '';
        
            // Verifica se o campo de texto está vazio
            if (empty($texto)) {
                echo "O texto do post não pode estar vazio.";
                exit;
            }
        
            // Obtém informações do arquivo de mídia
            $media = isset($_FILES['media']) ? $_FILES['media'] : null;
            $mediaNomeNovo = null; // Inicializa como null
            $mediaTipo = null; // Inicializa como null
        
            // Verifica se há um arquivo de mídia e se não houve erro no upload
            if ($media && $media['error'] === 0) {
                $mediaNome = $media['name'];
                $mediaTmp = $media['tmp_name'];
                $mediaTipo = $media['type'];
        
                // Diretório onde os arquivos serão armazenados
                $diretorio = 'uploads/';
                
                // Cria um nome único para o arquivo
                $mediaNomeNovo = uniqid('', true) . "." . strtolower(pathinfo($mediaNome, PATHINFO_EXTENSION));
        
                // Move o arquivo para o diretório de uploads
                if (!move_uploaded_file($mediaTmp, $diretorio . $mediaNomeNovo)) {
                    echo "Erro ao enviar arquivo.";
                    exit;
                }
            }
        
            // Insere o post no banco de dados
            $sql = "INSERT INTO posts (texto, media_nome, media_tipo) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
        
            if ($stmt) {
                $stmt->bind_param("sss", $texto, $mediaNomeNovo, $mediaTipo);
                if ($stmt->execute()) {
                    echo "Post publicado com sucesso!";
                } else {
                    echo "Erro ao publicar post: " . $stmt->error;
                }
                $stmt->close();
            } else {
                echo "Erro ao preparar a query: " . $conn->error;
            }
        }









$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f0f2f5;
        }
        .nomeUsuario {
            font-size: 26px;
        }
        .profile-cover {
            height: 141px;
            margin-top: 20px;
            background: url('uploads/<?php echo isset($coverPicture) ? basename($coverPicture) : 'default-cover.jpg'; ?>') no-repeat center center;
            background-size: cover;
            position: relative;
        }

        .profile-picture {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 5px solid #fff;
            position: absolute;
            bottom: 10px;
            left: 30px;
            overflow: hidden;
        }
        .profile-picture img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
        }
        .profile-info {
            margin-top: 0px;
            display: flex;
            padding-left: 200px;
            padding-bottom: 20px;
            position: relative;
        }
        .profile-info h2 {
            margin: 0;
        }
        .profile-info p {
            color: #777;
        }
        .nav-profile {
            border-bottom: 1px solid #ddd;
            margin-top: -10px;
        }
        .nav-profile .nav-link {
            color: #333;
        }
        .nav-profile .nav-link.active {
            font-weight: bold;
            border-bottom: 2px solid #1877f2;
        }
        .post, .post-input {
            background-color: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .sidebar {
            position: sticky;
            top: 80px;
        }
        .upload-icon {
            color: white;
            cursor: pointer;
            display: block;
            position: absolute;
            z-index: 10;
            background-color: transparent;
            border-radius: 50%;
            padding: 5px;
        }

        .upload-icon-cover {
            top: 10px;
            right: 10px;
        }
        .upload-icon-profile {
            bottom: -2px;
            left: 27px;
            animation: ani-ring 2s ease infinite;
        }
        .upload-icon-profile:hover{
            animation: ani-ring 2s ease infinite;
        }
        .upload-icon input[type="file"] {
            display: none;
        }
        .upload-icon img {
            vertical-align: middle;
            width: 24px;
            height: 24px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Seção de capa do perfil -->
        <div class="profile-cover">
            <!-- Formulário de upload da capa -->
            <form id="coverPictureForm" action="site.php" method="POST" enctype="multipart/form-data">
                <label class="upload-icon upload-icon-cover">
                    <img src="https://img.icons8.com/?size=100&id=b7xgidjVJTtd&format=png&color=000000" alt="Upload Icon">
                    <input type="file" name="cover_picture" accept="image/*" onchange="this.form.submit()">
                </label>
            </form>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-sm-5 col-md-6">
                    <!-- Seção de informações do perfil -->
                    <div class="profile-info">
                        <!-- Formulário de upload do perfil -->
                        <form id="profilePictureForm" action="site.php" method="POST" enctype="multipart/form-data">
                            <div class="profile-picture">
                                <img src="uploads/<?php echo isset($profilePicture) ? basename($profilePicture) : 'default-profile.jpg'; ?>" alt="Foto de Perfil">
                                <!-- Ícone de upload do perfil -->
                                <label class="upload-icon upload-icon-profile">
                                    <img src="https://img.icons8.com/?size=100&id=b7xgidjVJTtd&format=png&color=000000"  alt="Upload Icon">
                                    <input type="file" name="profile_picture" accept="image/*" onchange="this.form.submit()">
                                </label>
                            </div>
                            <a class="nomeUsuario"> <?php echo $_SESSION['usuario']; ?></a>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navegação do perfil -->
        <ul class="nav nav-tabs nav-profile">
            <li class="nav-item">
                <a class="nav-link active" href="#">Conteúdo</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Biografia</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Seguidores</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Fotos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Vídeos</a>
            </li>
        </ul>

        <div class="row">
            <!-- Coluna da esquerda -->
            <div class="col-md-4">
                <div class="sidebar">
                    <div class="card mb-3">
                        <div class="card-body">
                            <p>21 amigos em comum, incluindo James R. Hairston e Shoob Omar</p>
                            <p>Vive em San Francisco, Califórnia</p>
                            <p>Seu amigo desde Outubro de 2014</p>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-header">Amigos</div>
                        <div class="card-body">
                            <!-- Lista de amigos -->
                            <div class="media mb-2">
                                <img src="https://via.placeholder.com/40" class="mr-3 rounded-circle" alt="Friend 1">
                                <div class="media-body">
                                    <h6 class="mt-0 mb-1">Amigo 1</h6>
                                </div>
                            </div>
                            <div class="media mb-2">
                                <img src="https://via.placeholder.com/40" class="mr-3 rounded-circle" alt="Friend 2">
                                <div class="media-body">
                                    <h6 class="mt-0 mb-1">Amigo 2</h6>
                                </div>
                            </div>
                            <div class="media">
                                <img src="https://via.placeholder.com/40" class="mr-3 rounded-circle" alt="Friend 3">
                                <div class="media-body">
                                    <h6 class="mt-0 mb-1">Amigo 3</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Coluna da direita -->
            <div class="col-md-8">
                <!-- Input para novo post -->
                
                <div class="post-input">
                    <form action="site.php" method="POST" enctype="multipart/form-data">
                        <textarea class="form-control" rows="3" placeholder="Escreva algo..." name="novopost" requered></textarea>
                        <input type="file" name="media" accept="image/*,video/*">
                        <button class="btn btn-primary mt-2" type="submit" name="enviarpost">Postar</button>
                    </form>
                </div>
                <!-- Exemplo de post -->
                <div class="posts" name="posts">
    <?php
        //-------------------------------------------------------
        // Carregar comentários 

        // Conecte ao banco de dados e busque os posts
        include 'C:/xampp/htdocs/sistema-login-completo/config/config.php';
        $sql = "SELECT * FROM posts ORDER BY data_criação DESC"; // Ordena por data de criação em ordem decrescente
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $texto = $row['texto'];
                $mediaNome = $row['media_nome'];
                $mediaTipo = $row['media_tipo'];

                echo "<div class='post'>";

                // Verifique se o nome do usuário está armazenado na sessão
                if (isset($_SESSION['usuario'])) {
                    $nomeUsuario = $_SESSION['usuario'];




                    
                    // Buscar a URL da foto de perfil do banco de dados
                    $userProfileQuery = "SELECT foto_perfil FROM usuarios WHERE usuario ";
                    $userProfileResult = $conn->query($userProfileQuery);
                    $userProfileRow = $userProfileResult->fetch_assoc();
                    $fotoPerfil = $userProfileRow['foto_perfil'] ?? 'default-profile.png'; // Substitua por um valor padrão se não houver foto

                    echo "<div class='user-info'>
                            <img src='dashboards/uploads/$fotoPerfil' alt='Foto de Perfil' class='profile-pic'>
                            <a>$nomeUsuario</a>
                          </div>"; // Exibe o nome do usuário e a foto de perfil
                } else {
                    echo "<a>Usuário não identificado</a>";
                }




                


                

                echo "<p>$texto</p>";

                if ($mediaNome) {
                    if (strpos($mediaTipo, 'image') !== false) {
                        echo "<img src='uploads/$mediaNome' alt='Imagem' class='img-fluid'>";
                    } elseif (strpos($mediaTipo, 'video') !== false) {
                        echo "<video controls class='video-fluid'>
                                <source src='uploads/$mediaNome' type='$mediaTipo'>
                              </video>";
                    }
                }

                echo "</div>";
            }
        } else {
            echo "<p>Nenhum post encontrado.</p>";
        }
    ?>
</div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Formulário de logout -->
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <input type="submit" name="logout" value="Logout">
    </form>
</body>
</html>