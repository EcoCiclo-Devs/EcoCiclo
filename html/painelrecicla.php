<?php
session_start();

// BLOQUEIA ACESSO SE NÃO ESTIVER LOGADO
if(!isset($_SESSION['cidadao_id'])){
    header("Location: ../html/login_cidadao.html");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="icon" type="image/svg+xml" href="../assets/img/favicon.svg"/>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<link rel="stylesheet" href="../assets/css/header.css">
<link rel="stylesheet" href="../assets/css/body.css">
<link rel="stylesheet" href="../assets/css/footer.css">

<title>Painel Monitoramento Recicla - EcoCiclo</title>
</head>

<body>

<!-- NAVBAR -->
<header class="header">
<article class="menu_container flex">

<div id="titulo">
<img src="../assets/img/icone.svg" class="logo">
<a href="../index.php" class="text-success fw-bold fs-3">EcoCiclo</a>
</div>

<nav>

<ul class="flex menu_list">

<li><a href="../index.php">Início</a></li>
<li><a href="../html/ecopontos.html">Ecopontos</a></li>
<li><a href="../html/faleconosco.html">Contato</a></li>

<?php if(isset($_SESSION['cidadao_nome'])): ?>

<li class="nav-item dropdown">

<a class="nav-link dropdown-toggle d-flex align-items-center gap-2"
href="#"
role="button"
data-bs-toggle="dropdown">

<img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['cidadao_nome']); ?>&background=28a745&color=fff&size=32"
style="border-radius:50%">

</a>

<ul class="dropdown-menu">

<li>
<a class="dropdown-item text-danger" href="../php/logout.php">Sair</a>
</li>

</ul>

</li>

<?php else: ?>

<li><a href="../html/login_cidadao.html">Login</a></li>

<?php endif; ?>

</ul>

</nav>

</article>
</header>

<main style="padding-top:80px;">

<section class="py-5 bg-light">

<div class="container">

<h1 class="text-success fw-bold text-center mb-3">Dashboard EcoCiclo</h1>

<p class="text-muted text-center mb-5">
Acompanhe os níveis de coleta seletiva em tempo real.
</p>

<div class="row g-4">

<!-- GRÁFICO RESÍDUOS -->
<div class="col-lg-6 col-xl-4">

<div class="card shadow-sm p-4 h-100">

<h5 class="fw-bold text-success mb-2">Resíduos Coletados por Tipo</h5>

<select id="filtroPeriodo" class="form-select mb-3" onchange="atualizarGraficoResiduos()">
<option value="7">Últimos 7 dias</option>
<option value="30">Últimos 30 dias</option>
<option value="90">Últimos 90 dias</option>
</select>

<canvas id="graficoResiduos"></canvas>

</div>

</div>

<!-- LIXEIRAS MAIS CHEIAS -->
<div class="col-lg-6 col-xl-4">

<div class="card shadow-sm p-4 h-100">

<h5 class="fw-bold text-success mb-2">Top 5 Lixeiras Mais Cheias</h5>

<canvas id="graficoNivel"></canvas>

</div>

</div>

<!-- MÉDIA -->
<div class="col-lg-6 col-xl-4">

<div class="card shadow-sm p-4 h-100">

<h5 class="fw-bold text-success mb-2">Preenchimento Médio</h5>

<canvas id="graficoPreenchimento"></canvas>

</div>

</div>

<!-- RANKING -->
<div class="col-lg-6">

<div class="card shadow-sm p-4">

<h5 class="fw-bold text-success mb-2">Ranking de Pontos</h5>

<canvas id="graficoRanking"></canvas>

</div>

</div>

<!-- ALERTAS -->
<div class="col-lg-6">

<div class="card shadow-sm p-4">

<h5 class="fw-bold text-success mb-2">Alertas</h5>

<div id="alertasContainer"></div>

</div>

</div>

</div>

</div>

</section>

</main>

<!-- FOOTER -->

<footer class="footer bg-success text-white py-5">

<div class="container">

<div class="row align-items-center">

<div class="col-md-4 text-center">

<h4>Junte-se à comunidade</h4>

</div>

<div class="col-md-4 text-center"></div>

<div class="col-md-4 text-center">

<h3>EcoCiclo</h3>

<p>Monitoramento inteligente para coleta eficiente</p>

</div>

</div>

</div>

</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>

// DADOS SIMULADOS

const dadosLixeiras = [
{nome:"Ponto 1",nivel:90},
{nome:"Ponto 2",nivel:85},
{nome:"Ponto 3",nivel:82},
{nome:"Ponto 4",nivel:75},
{nome:"Ponto 5",nivel:70},
{nome:"Ponto 6",nivel:60}
];

// GRÁFICO NÍVEL

new Chart(document.getElementById('graficoNivel'),{
type:'bar',
data:{
labels:dadosLixeiras.slice(0,5).map(l=>l.nome),
datasets:[{
label:'Nível (%)',
data:dadosLixeiras.slice(0,5).map(l=>l.nivel),
backgroundColor:'#28a745'
}]
},
options:{scales:{y:{max:100,beginAtZero:true}}}
});

// MÉDIA

new Chart(document.getElementById('graficoPreenchimento'),{
type:'line',
data:{
labels:["Seg","Ter","Qua","Qui","Sex","Sáb","Dom"],
datasets:[{
label:"Nível médio",
data:[45,55,60,50,70,65,60],
borderColor:"#28a745",
fill:true
}]
}
});

// RESÍDUOS

const graficoResiduos = new Chart(document.getElementById('graficoResiduos'),{
type:'doughnut',
data:{
labels:["Plástico","Papel","Metal","Vidro","Óleo"],
datasets:[{
data:[35,25,15,20,5],
backgroundColor:["#28a745","#ffc107","#17a2b8","#6c757d","#fd7e14"]
}]
}
});

function atualizarGraficoResiduos(){
graficoResiduos.data.datasets[0].data =
Array.from({length:5},()=>Math.floor(Math.random()*50));
graficoResiduos.update();
}

// RANKING

new Chart(document.getElementById('graficoRanking'),{
type:'bar',
data:{
labels:["Ponto 1","Ponto 3","Ponto 5","Ponto 2","Ponto 4"],
datasets:[{
label:"Uso %",
data:[90,80,70,60,50],
backgroundColor:"#28a745"
}]
},
options:{indexAxis:'y'}
});

// ALERTAS

const alertas = [
{ponto:"Ponto 1",nivel:75},
{ponto:"Ponto 3",nivel:82},
{ponto:"Ponto 5",nivel:78}
];

const container = document.getElementById("alertasContainer");

alertas.forEach(a=>{
const div=document.createElement("div");
div.className="alert alert-danger animate__animated animate__pulse animate__infinite";
div.innerText=`${a.ponto} atingiu ${a.nivel}% da capacidade`;
container.appendChild(div);
});

</script>

</body>
</html>