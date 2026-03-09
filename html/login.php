<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="../assets/img/favicon.svg" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Animação -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <link rel="stylesheet" href="../assets/css/header.css">
    <link rel="stylesheet" href="../assets/css/body.css">
    <link rel="stylesheet" href="../assets/css/footer.css">

    <title>Login - EcoCiclo</title>
</head>
<script>

const urlParams = new URLSearchParams(window.location.search);

if(urlParams.get("cadastro") === "sucesso"){
    document.getElementById("mensagem").innerHTML =
    `<div class="alert alert-success text-center">
        Cadastro realizado com sucesso! Faça login.
    </div>`;
}

</script>
<body>
    <!-- NAVBAR / MENU -->
    <header class="header">
        <article class="menu_container flex">
            <div id="titulo">
                <img src="../assets/img/icone.svg" alt="Ícone EcoCiclo" class="logo">
                <a href="#" class="text-success fw-bold fs-3">EcoCiclo</a>
            </div>
            <nav>
                <ul class="flex menu_list">
                    <li><a href="index.php">Início</a></li>
                    <!-- <li><a href="#sobre">Sobre</a></li> -->
                    <li><a href="ecopontos.php">Ecopontos</a></li>
                    <li><a href="faleconosco.html">Contato</a></li>
                    <li><a href="login.html">Login</a></li>
                </ul>
            </nav>
        </article>
    </header>

    <main style="padding-top: 10px;">
        <section style="padding-top: 2px; padding-bottom: 100px; background-color: #fff;">
            <div class="container">
                <div class="row align-items-center justify-content-center">
                    <div id="mensagem"></div>
                    <!-- Formulário de Login -->
                    <div class="col-lg-4 mb-lg-0">
                        <h2 class="text-success fw-bold text-center mb-4">Login do Usuário</h2>
                        <p class="text-muted text-center mb-4">
                            Acesse sua conta para gerenciar pontos de coleta e monitorar os sensores.
                        </p>
                        
                        <!-- Formulário: futuramente conectará com PHP -->
                        <form action="../php/login_cidadao.php" method="POST">
                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Seu email"
                                    required>
                            </div>

                            <!-- Senha -->
                            <div class="mb-3">
                                <label for="password" class="form-label">Senha</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Sua senha" required>
                            </div>

                            <!-- Botão de Login -->
                            <div class="d-grid mt-3">
                                <button type="submit" class="btn btn-success btn-lg">Entrar</button>
                            </div>

                            <!-- Links lado a lado -->
                            <div class="d-flex justify-content-center mt-3">
                                <a href="esquecesenha.html" class="btn btn-link text-success me-3 p-1" style="font-size: 16px;">Esqueceu
                                    a senha?</a>
                                <a href="novocadastro.html" class="btn btn-link text-success p-1" style="font-size: 16px;">Novo
                                    Cadastro</a>
                            </div>
                        </form>
                    </div>

                    <!-- Imagem do galho de folha -->
                    <div class="col-lg-5 text-center px-4">
                        <img src="../assets/img/folha.png" alt="Galho de folha" class="img-fluid"
                            style="max-height: 400px; margin-left: 120px; margin-top: 150px;">
                    </div>

                </div>
            </div>
        </section>
    </main>

    <!-- FOOTER PADRÃO -->
    <footer class="footer bg-success text-white py-5">
        <div class="container">
            <div class="row align-items-center">

                <!-- Coluna esquerda: redes sociais -->
                <div class="col-md-4 d-flex flex-column align-items-center mb-4 mb-md-0">
                    <h4 class="fw-bold mb-3">Junte-se à comunidade</h4>
                    <div class="d-flex gap-3 social-icons">
                        <a href="#"><img src="../assets/img/facebook-logo.svg" alt="Facebook" class="social-icon"></a>
                        <a href="#"><img src="../assets/img/instagram-logo.svg" alt="Instagram" class="social-icon"></a>
                        <a href="#"><img src="../assets/img/twitter-logo.svg" alt="Twitter" class="social-icon"></a>
                    </div>
                </div>

                <!-- Coluna central: vazia -->
                <div class="col-md-4 d-flex justify-content-center"></div>

                <!-- Coluna direita: EcoCiclo -->
                <div class="col-md-4 d-flex flex-column align-items-center">
                    <h3 class="fw-bold mb-2">
                        <img src="../assets/img/tree-branco.svg" alt="Ícone EcoCiclo" class="logo me-2">
                        EcoCiclo
                    </h3>
                    <p class="mb-0 text-center">Monitoramento inteligente para coleta eficiente</p>
                </div>

            </div>
        </div>
    </footer>
</body>

</html>