<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="icon" type="image/svg+xml" href="../public/assets/img/favicon.svg" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <link rel="stylesheet" href="../public/assets/css/header.css">
  <link rel="stylesheet" href="../public/assets/css/body.css">
  <link rel="stylesheet" href="../public/assets/css/footer.css">

  <title>Ecopontos - EcoCiclo</title>

  <style>
    #map {
      width: 100%;
      height: 620px;
      border-radius: 18px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.12);
    }

    .map-card,
    .sidebar-card,
    .admin-card {
      background: #fff;
      border-radius: 18px;
      padding: 24px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    }

    .sidebar-card {
      height: 722px;
      display: flex;
      flex-direction: column;
    }

    .sidebar-list {
      flex: 1;
      overflow-y: auto;
      padding-right: 6px;
    }

    .ecoponto-item {
      border: 1px solid #e9ecef;
      border-radius: 12px;
      padding: 12px;
      margin-bottom: 12px;
      background: #fff;
      transition: 0.2s ease;
    }

    .ecoponto-item:hover {
      box-shadow: 0 6px 18px rgba(0,0,0,0.08);
      transform: translateY(-2px);
    }

    .ecoponto-item h6 {
      margin-bottom: 6px;
      color: #198754;
      font-weight: 700;
    }

    .ecoponto-info {
      font-size: 14px;
      margin-bottom: 4px;
      color: #495057;
    }

    .popup-card {
      min-width: 250px;
    }

    .popup-title {
      color: #198754;
      font-weight: 700;
      margin-bottom: 8px;
    }

    .popup-info {
      font-size: 14px;
      margin-bottom: 4px;
    }

    .nivel-badge {
      display: inline-block;
      background: #198754;
      color: white;
      padding: 5px 10px;
      border-radius: 999px;
      font-size: 12px;
      font-weight: 600;
      margin: 8px 0 10px;
    }

    .admin-card {
      margin-top: 20px;
    }

    @media (max-width: 991px) {
      #map,
      .sidebar-card {
        height: 500px;
      }
    }
  </style>
</head>

<body>

<header class="header">
  <article class="menu_container flex">
    <div id="titulo">
      <img src="../public/assets/img/icone.svg" alt="Ícone EcoCiclo" class="logo">
      <a href="../index.php" class="text-success fw-bold fs-3">EcoCiclo</a>
    </div>
    <nav>
      <ul class="flex menu_list">
        <li><a href="painelrecicla.php">Painel Recicla</a></li>
        <li><a href="administrador.html">Painel Administrativo</a></li>
        <li><a href="login.php" class="btn btn-success">Sair</a></li>
      </ul>
    </nav>
  </article>
</header>

<main style="padding-top: 20px;">
  <section class="py-5 bg-light">
    <div class="container">
      <h2 class="text-success fw-bold text-center mb-3">Ecopontos de Coleta Seletiva</h2>
      <p class="text-muted text-center mb-4">
        Encontre pontos de coleta, filtre por cidade, veja os materiais aceitos, o nível da lixeira e planeje sua rota.
      </p>

      <?php if (isset($_GET['sucesso']) && $_GET['sucesso'] == '1'): ?>
        <div class="alert alert-success text-center">
          Ecoponto cadastrado com sucesso.
        </div>
      <?php endif; ?>

      <!-- LISTA + MAPA -->
      <div class="row g-4">
        <div class="col-lg-4">
          <div class="sidebar-card">
            <h5 class="text-success fw-bold mb-3">Filtrar ecopontos</h5>

            <select id="cidadeFilter" class="form-select mb-3">
              <option value="">Todas as cidades</option>
            </select>

            <div id="listaEcopontos" class="sidebar-list"></div>
          </div>
        </div>

        <div class="col-lg-8">
          <div class="map-card">
            <div class="mb-3">
              <div class="input-group">
                <input
                  type="text"
                  id="buscaEndereco"
                  class="form-control"
                  placeholder="Ex.: Av. Paulista, São Paulo">
                <button class="btn btn-success" type="button" onclick="buscarEndereco()">
                  Buscar
                </button>
              </div>
            </div>

            <div id="map"></div>
          </div>
        </div>
      </div>

      <!-- FORMULÁRIO ADMIN CENTRALIZADO ABAIXO -->
      <?php if (!empty($_SESSION['is_admin']) && $_SESSION['is_admin'] === true): ?>
        <div class="row justify-content-center mt-4">
          <div class="col-lg-8 col-xl-6">
            <div class="admin-card">
              <h5 class="text-success fw-bold mb-3 text-center">Adicionar novo ecoponto</h5>
              <p class="text-muted small text-center">
                Clique no mapa para preencher latitude e longitude automaticamente.
              </p>

              <form action="../controllers/cadastrar_ecoponto.php" method="POST">
                <div class="mb-3">
                  <label class="form-label">Nome do ecoponto</label>
                  <input type="text" name="nome" class="form-control" required>
                </div>

                <div class="mb-3">
                  <label class="form-label">Cidade</label>
                  <input type="text" name="cidade" class="form-control" required>
                </div>

                <div class="mb-3">
                  <label class="form-label">Endereço</label>
                  <input type="text" name="endereco" class="form-control" required>
                </div>

                <div class="mb-3">
                  <label class="form-label">Tipo de resíduo</label>
                  <input type="text" name="tipo_residuo" class="form-control" placeholder="Plástico, Papel, Vidro...">
                </div>

                <div class="mb-3">
                  <label class="form-label">Nível atual da lixeira (%)</label>
                  <input type="number" name="nivel_lixo" class="form-control" min="0" max="100" value="0" required>
                </div>

                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label class="form-label">Latitude</label>
                    <input type="text" id="latitude" name="latitude" class="form-control" required readonly>
                  </div>

                  <div class="col-md-6 mb-3">
                    <label class="form-label">Longitude</label>
                    <input type="text" id="longitude" name="longitude" class="form-control" required readonly>
                  </div>
                </div>

                <button type="submit" class="btn btn-success w-100">Salvar ecoponto</button>
              </form>
            </div>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </section>
</main>

<footer class="footer bg-success text-white py-5">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-md-4 d-flex flex-column align-items-center mb-4 mb-md-0">

      </div>
      
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

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
  const map = L.map('map').setView([-23.55052, -46.633308], 5);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; OpenStreetMap'
  }).addTo(map);

  const lixeiraIcon = L.icon({
    iconUrl: '../public/assets/img/lixeira-marker.png',
    iconSize: [42, 42],
    iconAnchor: [21, 42],
    popupAnchor: [0, -36]
  });

  let todosEcopontos = [];
  let markers = [];
  let adminMarker = null;

  function limparMarkers() {
    markers.forEach(marker => map.removeLayer(marker));
    markers = [];
  }
  let marcadorBusca = null;

async function buscarEndereco() {
  const termo = document.getElementById('buscaEndereco').value.trim();

  if (!termo) {
    alert('Digite um endereço, bairro ou cidade para buscar.');
    return;
  }

  try {
    const response = await fetch(
      `https://nominatim.openstreetmap.org/search?format=jsonv2&limit=1&countrycodes=br&q=${encodeURIComponent(termo)}`
    );

    const resultados = await response.json();

    if (!resultados || resultados.length === 0) {
      alert('Endereço não encontrado.');
      return;
    }

    const local = resultados[0];
    const lat = parseFloat(local.lat);
    const lon = parseFloat(local.lon);

    map.setView([lat, lon], 16);

    if (marcadorBusca) {
      map.removeLayer(marcadorBusca);
    }

    marcadorBusca = L.marker([lat, lon]).addTo(map)
      .bindPopup(`
        <strong>Local buscado</strong><br>
        ${local.display_name}
      `)
      .openPopup();

  } catch (error) {
    console.error('Erro ao buscar endereço:', error);
    alert('Não foi possível buscar o endereço agora.');
  }
}
document.getElementById('buscaEndereco').addEventListener('keydown', function(e) {
  if (e.key === 'Enter') {
    e.preventDefault();
    buscarEndereco();
  }
});
  function corNivel(nivel) {
    if (nivel >= 80) return '#dc3545';
    if (nivel >= 50) return '#ffc107';
    return '#198754';
  }

  function renderizarLista(ecopontos) {
    const lista = document.getElementById('listaEcopontos');
    lista.innerHTML = '';

    if (ecopontos.length === 0) {
      lista.innerHTML = '<p class="text-muted">Nenhum ecoponto encontrado.</p>';
      return;
    }

    ecopontos.forEach(ponto => {
      const div = document.createElement('div');
      div.className = 'ecoponto-item';

      div.innerHTML = `
        <h6>${ponto.nome}</h6>
        <div class="ecoponto-info"><strong>Cidade:</strong> ${ponto.cidade}</div>
        <div class="ecoponto-info"><strong>Endereço:</strong> ${ponto.endereco}</div>
        <div class="ecoponto-info"><strong>Resíduos:</strong> ${ponto.tipo_residuo || 'Não informado'}</div>
        <div class="ecoponto-info">
          <strong>Nível:</strong>
          <span style="color:${corNivel(ponto.nivel_lixo)}; font-weight:700;">
            ${ponto.nivel_lixo}%
          </span>
        </div>
        <div class="d-flex gap-2 mt-2">
          <a class="btn btn-success btn-sm" target="_blank"
             href="https://www.google.com/maps/dir/?api=1&destination=${encodeURIComponent(ponto.endereco)}">
             Gerar rota
          </a>
          <button class="btn btn-outline-success btn-sm" onclick="focarNoPonto(${ponto.latitude}, ${ponto.longitude}, ${ponto.id})">
            Ver no mapa
          </button>
        </div>
      `;

      lista.appendChild(div);
    });
  }

  function criarPopupHTML(ponto) {
    return `
      <div class="popup-card">
        <div class="popup-title">${ponto.nome}</div>
        <div class="popup-info"><strong>Cidade:</strong> ${ponto.cidade}</div>
        <div class="popup-info"><strong>Endereço:</strong> ${ponto.endereco}</div>
        <div class="popup-info"><strong>Materiais aceitos:</strong> ${ponto.tipo_residuo || 'Não informado'}</div>
        <div class="nivel-badge" style="background:${corNivel(ponto.nivel_lixo)};">
          Nível atual: ${ponto.nivel_lixo}%
        </div>
        <div style="width:220px;height:220px;margin:0 auto;">
          <canvas id="grafico-${ponto.id}"></canvas>
        </div>
        <div class="mt-2 text-center">
          <a class="btn btn-success btn-sm"
             href="https://www.google.com/maps/dir/?api=1&destination=${encodeURIComponent(ponto.endereco)}"
             target="_blank">
            Planejar rota
          </a>
        </div>
      </div>
    `;
  }

  function renderizarGrafico(pontoId, nivelLixo) {
    const canvas = document.getElementById(`grafico-${pontoId}`);
    if (!canvas) return;

    new Chart(canvas, {
      type: 'doughnut',
      data: {
        labels: ['Ocupado', 'Livre'],
        datasets: [{
          data: [nivelLixo, 100 - nivelLixo],
          backgroundColor: [corNivel(nivelLixo), '#e9f5ec'],
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            display: true,
            position: 'bottom'
          }
        }
      }
    });
  }

  function renderizarMapa(ecopontos) {
    limparMarkers();

    const bounds = [];

    ecopontos.forEach(ponto => {
      const marker = L.marker([ponto.latitude, ponto.longitude], { icon: lixeiraIcon }).addTo(map);

      marker.bindPopup(criarPopupHTML(ponto));

      marker.on('popupopen', function () {
        renderizarGrafico(ponto.id, ponto.nivel_lixo);
      });

      marker.pontoId = ponto.id;
      markers.push(marker);
      bounds.push([ponto.latitude, ponto.longitude]);
    });

    if (bounds.length > 0) {
      map.fitBounds(bounds, { padding: [40, 40] });
    }
  }

  function preencherFiltroCidades(ecopontos) {
    const select = document.getElementById('cidadeFilter');
    select.innerHTML = '<option value="">Todas as cidades</option>';

    const cidades = [...new Set(ecopontos.map(e => e.cidade))].sort();

    cidades.forEach(cidade => {
      const option = document.createElement('option');
      option.value = cidade;
      option.textContent = cidade;
      select.appendChild(option);
    });
  }

  function aplicarFiltro() {
    const cidade = document.getElementById('cidadeFilter').value;

    const filtrados = cidade
      ? todosEcopontos.filter(e => e.cidade === cidade)
      : todosEcopontos;

    renderizarMapa(filtrados);
    renderizarLista(filtrados);
  }

  function focarNoPonto(lat, lng, pontoId) {
    map.setView([lat, lng], 16);

    const markerEncontrado = markers.find(marker => marker.pontoId === pontoId);
    if (markerEncontrado) {
      markerEncontrado.openPopup();
    }
  }

  async function carregarEcopontos() {
    try {
      const response = await fetch('../controllers/listar_ecopontos.php');

      if (!response.ok) {
        throw new Error('Falha ao buscar ecopontos');
      }

      const ecopontos = await response.json();

      todosEcopontos = ecopontos;
      preencherFiltroCidades(ecopontos);
      aplicarFiltro();
    } catch (error) {
      console.error('Erro ao carregar ecopontos:', error);
    }
  }

  document.getElementById('cidadeFilter').addEventListener('change', aplicarFiltro);

  <?php if (!empty($_SESSION['is_admin']) && $_SESSION['is_admin'] === true): ?>
    map.on('click', async function (e) {
      const lat = e.latlng.lat.toFixed(8);
      const lng = e.latlng.lng.toFixed(8);

      const inputLat = document.getElementById('latitude');
      const inputLng = document.getElementById('longitude');
      const inputEndereco = document.querySelector('input[name="endereco"]');
      const inputCidade = document.querySelector('input[name="cidade"]');

      if (inputLat) inputLat.value = lat;
      if (inputLng) inputLng.value = lng;

      if (adminMarker) {
        map.removeLayer(adminMarker);
      }

      adminMarker = L.marker([lat, lng], { icon: lixeiraIcon }).addTo(map)
        .bindPopup("Novo ecoponto selecionado aqui.")
        .openPopup();

      try {
        const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`);
        const data = await response.json();

        if (data && data.display_name && inputEndereco) {
          inputEndereco.value = data.display_name;
        }

        if (data && data.address && inputCidade) {
          inputCidade.value =
            data.address.city ||
            data.address.town ||
            data.address.village ||
            data.address.municipality ||
            '';
        }
      } catch (error) {
        console.error('Erro ao buscar endereço automático:', error);
      }
    });
  <?php endif; ?>

  carregarEcopontos();
</script>

</body>
</html>