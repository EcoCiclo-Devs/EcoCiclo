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

    <link rel="stylesheet" href="../public/assets/css/header.css">
    <link rel="stylesheet" href="../public/assets/css/body.css">
    <link rel="stylesheet" href="../public/assets/css/footer.css">

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
                <img src="../public/assets/img/icone.svg" alt="Ícone EcoCiclo" class="logo">
                <a href="#" class="text-success fw-bold fs-3">EcoCiclo</a>
            </div>
        </article>
    </header>

    <main style="padding-top: 10px;">
        <section style="padding-top: 2px; padding-bottom: 100px; background-color: #fff;">
            <div class="container">
                <div class="row align-items-center justify-content-center">
                    <div id="mensagem"></div>
                    <!-- Formulário de Login -->
                    <div class="col-lg-4 mb-lg-0">
                        <h2 class="text-success fw-bold text-center mb-4">Login do Administrador</h2>
                        <p class="text-muted text-center mb-4">
                            Acesse sua conta para gerenciar pontos de coleta e monitorar os sensores.
                        </p>
                        
                        <!-- Formulário: futuramente conectará com PHP -->
                        <form action="../controllers/login_admin.php" method="POST">
                            <!-- Número de registro -->
                            <div class="mb-3">
                                <label for="numero_registro" class="form-label">Número de Registro</label>
                                <input type="text" class="form-control" id="numero_registro" name="numero_registro"
                                    placeholder="Seu número de registro" required>  
                            </div>
                            <!-- Senha -->
                            <div class="mb-3">
                                <label for="password" class="form-label">Senha</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Sua senha" required>
                            </div>

                            <!-- Botão de Login -->
                            <div class="d-grid mt-3">
                                <a href="painelrecicla.php" class="btn btn-success btn-lg">Entrar</a>
                            </div>

                            <!-- Links lado a lado -->
                            <div class="d-flex justify-content-center mt-3">
                                <a href="esquecesenha.html" class="btn btn-link text-success me-3 p-1" style="font-size: 16px;">Esqueceu
                                    a senha?</a>
                            </div>
                        </form>
                    </div>

                    <!-- Imagem do galho de folha -->
                    <div class="col-lg-5 text-center px-4">
                        <img src="../public/assets/img/folha.png" alt="Galho de folha" class="img-fluid"
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

                

                <!-- Coluna central: vazia -->
                <div class="col-md-4 d-flex justify-content-center"></div>

                <!-- Coluna direita: EcoCiclo -->
                <div class="col-md-4 d-flex flex-column align-items-center">
                    <h3 class="fw-bold mb-2">
                        <img src="../public/assets/img/tree-branco.svg" alt="Ícone EcoCiclo" class="logo me-2">
                        EcoCiclo
                    </h3>
                    <p class="mb-0 text-center">Monitoramento inteligente para coleta eficiente</p>
                </div>

            </div>
        </div>
    </footer>
</body>

</html>