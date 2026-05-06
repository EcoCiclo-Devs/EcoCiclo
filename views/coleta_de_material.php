<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml" href="../public/assets/img/favicon.svg" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/assets/css/header.css">
    <link rel="stylesheet" href="../public/assets/css/body.css">
    <link rel="stylesheet" href="../public/assets/css/footer.css">

    <title>Agendamento - EcoCiclo</title>

    <style>
        .agenda-card {
            background: #fff;
            border-radius: 18px;
            padding: 24px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }
        .coleta-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px;
            border: 1px solid #e9ecef;
            border-radius: 10px;
            margin-bottom: 10px;
            background: white;
            transition: 0.2s;
        }
        .coleta-item:hover {
            background: #f8f9fa;
        }
        .coleta-item input {
            transform: scale(1.3);
            accent-color: #198754;
        }
        .indisponivel {
            opacity: 0.5;
            background: #f1f1f1;
            pointer-events: none;
        }
    </style>
</head>

<body>

    <header class="header">
        <article class="menu_container flex">
            <div id="titulo">
                <img src="../public/assets/img/icone.svg" class="logo">
                <a href="../index.php" class="text-success fw-bold fs-3">EcoCiclo</a>
            </div>
            <nav>
                <ul class="flex menu_list">
                    <li><a href="../index.php">Início</a></li>
                    <li><a href="../views/ecopontos.php">Ecopontos</a></li>
                    <li><a href="../views/coleta_de_material.php">Coleta de Materiais</a></li>
                    <li><a href="../views/faleconosco.html">Contato</a></li>
                    <li><a href="../views/login.php">Login</a></li>
                    <li><a href="../views/login.php" class="btn btn-success">Sair</a></li>
                </ul>
            </nav>
        </article>
    </header>

    <main style="padding-top:20px;">
        <section class="py-5 bg-light">
            <div class="container">
                <h2 class="text-success fw-bold text-center mb-3">Agendamento de Coleta</h2>
                <p class="text-muted text-center mb-4">
                    Coletas disponíveis: <strong>Segunda, Quarta e Sexta.</strong>
                </p>

                <div class="row justify-content-center">
                    <div class="col-lg-6">
                        <div class="agenda-card">
                            <form action="../controllers/agendar_coleta.php" method="POST" onsubmit="return validarEnvio()">
                                
                                <div class="mt-3">
                                    <label class="form-label fw-bold">CEP (Opcional)</label>
                                    <input type="text" name="cep" id="cep" class="form-control" placeholder="00000-000" onblur="buscarCEP()">
                                </div>

                                <div class="mt-3">
                                    <label class="form-label fw-bold">Endereço Completo</label>
                                    <input type="text" name="endereco" id="endereco" class="form-control" required>
                                </div>

                                <div class="mt-4">
                                    <label class="form-label fw-bold">Data da Coleta</label>
                                    <input type="date" name="data_coleta" id="data_coleta" class="form-control" required onchange="validarData()">
                                </div>

                                <div id="horarios-container" class="mt-4">
                                    <p class="text-muted small text-center">Selecione uma data para ver os horários.</p>
                                </div>

                                <div class="mt-4">
                                    <label class="form-label fw-bold">Materiais para Coleta</label>
                                    <div class="input-group mb-2">
                                        <input type="text" id="inputMaterial" class="form-control" placeholder="Ex: Armário, Mesa, Cadeira.">
                                        <button type="button" class="btn btn-success" onclick="adicionarMaterialTexto()">+</button>
                                    </div>
                                    <div id="materiais-container"></div>
                                </div>

                                <button type="submit" class="btn btn-warning fw-bold w-100 mt-4 py-2">
                                    Confirmar Agendamento
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer bg-success text-white py-4 mt-5">
        <div class="container text-center">
            <h3 class="fw-bold mb-0">EcoCiclo</h3>
            <small>© 2026 - Sustentabilidade Inteligente</small>
        </div>
    </footer>

    <script>
        // Função para validar se horário e materiais foram preenchidos
        function validarEnvio() {
            const horarioSelecionado = document.querySelector('input[name="hora_coleta"]:checked');
            if (!horarioSelecionado) {
                alert("Por favor, selecione um horário disponível.");
                return false;
            }

            const containerMateriais = document.getElementById("materiais-container");
            if (containerMateriais.children.length === 0) {
                alert("Por favor, adicione pelo menos um material para a coleta clicando no botão '+'.");
                document.getElementById("inputMaterial").focus();
                return false;
            }

            return true;
        }

        async function buscarCEP() {
            const cep = document.getElementById('cep').value.replace(/\D/g, '');
            if (cep.length !== 8) return;

            try {
                const res = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
                const data = await res.json();
                if (!data.erro) {
                    document.getElementById('endereco').value = `${data.logradouro}, ${data.bairro}, ${data.localidade} - ${data.uf}`;
                }
            } catch (e) { console.error("Erro ao buscar CEP"); }
        }

        function validarData() {
            const inputData = document.getElementById("data_coleta");
            const dataSelecionada = new Date(inputData.value + "T00:00:00");
            const diaSemana = dataSelecionada.getDay();

            if (diaSemana !== 1 && diaSemana !== 3 && diaSemana !== 5) {
                alert("Desculpe! Realizamos coletas apenas às Segundas, Quartas e Sextas.");
                inputData.value = "";
                document.getElementById("horarios-container").innerHTML = "";
                return;
            }
            gerarHorarios();
        }

        async function gerarHorarios() {
            const data = document.getElementById("data_coleta").value;
            const container = document.getElementById("horarios-container");
            const slots = ["08:00", "11:00", "14:00", "17:00"];

            container.innerHTML = "<div class='text-center text-muted'>Verificando disponibilidade...</div>";

            try {
                const res = await fetch(`../controllers/horarios_ocupados.php?data=${data}`);
                const ocupados = await res.json();

                container.innerHTML = "<label class='form-label fw-bold'>Selecione o Horário</label>";

                slots.forEach(hora => {
                    const estaOcupado = ocupados.includes(hora) || ocupados.includes(hora + ":00");
                    
                    const div = document.createElement("div");
                    div.className = `coleta-item ${estaOcupado ? 'indisponivel' : ''}`;

                    div.innerHTML = `
                        <div>
                            <strong>${hora}</strong><br>
                            <small>${estaOcupado ? "Já reservado" : "Disponível"}</small>
                        </div>
                        <input type="radio" name="hora_coleta" value="${hora}" ${estaOcupado ? "disabled" : "required"}>
                    `;
                    container.appendChild(div);
                });
            } catch (error) {
                container.innerHTML = "<p class='text-danger'>Erro ao carregar horários. Tente novamente.</p>";
            }
        }

        function adicionarMaterialTexto() {
            const input = document.getElementById("inputMaterial");
            const valor = input.value.trim();

            if (!valor) return;

            const div = document.createElement("div");
            div.className = "input-group mb-2";
            div.innerHTML = `
                <input type="text" name="material[]" class="form-control bg-light" value="${valor}" readonly>
                <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()">×</button>
            `;

            document.getElementById("materiais-container").appendChild(div);
            input.value = "";
            input.focus();
        }
    </script>
</body>
</html>