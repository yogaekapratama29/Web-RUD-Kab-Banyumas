<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard – Indeks Layanan Infrastruktur</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css" />
  <script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js"></script>
  <script src="https://unpkg.com/feather-icons"></script>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    #map { height: 380px; border-radius: 12px; }
    .card-hover { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .card-hover:hover { transform: translateY(-3px); box-shadow: 0 8px 16px rgba(0, 0, 0, 0.08); }
    #chart-scroll-wrapper::-webkit-scrollbar { height: 6px; }
    #chart-scroll-wrapper::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
  </style>
</head>
<body class="bg-gray-50 text-gray-800 font-sans">
<header class="bg-green-900 text-white p-4 flex justify-between items-center">
  <h1 class="text-xl font-bold">Dashboard Indeks Layanan Infrastruktur</h1>
  <div class="flex gap-4 items-center">
    <input type="text" placeholder="Cari..." class="text-sm px-2 py-1 rounded text-gray-800" />
    <i data-feather="bell"></i>
    <i data-feather="user"></i>
    <i data-feather="settings"></i>
  </div>
</header>

<section class="bg-white px-6 py-4 border-b">
  <div class="flex justify-between items-center mb-4">
    <h2 class="font-semibold text-lg">Indeks Layanan Infrastruktur per Kategori</h2>
    <select id="filterKecamatan" onchange="filterData()" class="text-sm px-2 py-1 border rounded">
      <option value="">Semua Kecamatan</option>
    </select>
  </div>
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    <div class="bg-amber-100 rounded-xl p-4 text-center shadow card-hover">
      <img src="https://img.icons8.com/ios-filled/50/a16207/bridge.png" class="mx-auto h-10 mb-2" alt="jembatan">
      <div class="font-semibold text-sm text-amber-800">Indeks kepuasan layanan jembatan</div>
    </div>
    <div class="bg-gray-100 rounded-xl p-4 text-center shadow card-hover">
      <img src="https://img.icons8.com/ios-filled/50/6b7280/highway.png" class="mx-auto h-10 mb-2" alt="jalan">
      <div class="font-semibold text-sm text-gray-700">Indeks kepuasan layanan jalan</div>
    </div>
    <div class="bg-orange-50 rounded-xl p-4 text-center shadow card-hover">
      <img src="https://img.icons8.com/color/48/exhaust-pipe.png" class="mx-auto h-10 mb-2" alt="drainase" />
      <div class="font-semibold text-sm text-orange-700">Indeks kepuasan layanan drainase</div>
    </div>
    <div class="bg-blue-50 rounded-xl p-4 text-center shadow card-hover">
      <img src="https://img.icons8.com/ios-filled/50/2563eb/water.png" class="mx-auto h-10 mb-2" alt="air bersih">
      <div class="font-semibold text-sm text-blue-700">Indeks kepuasan layanan air bersih</div>
    </div>
  </div>
</section>

<main class="p-6">
  <div class="flex items-center justify-between mb-2">
    <h2 class="text-lg font-semibold">Peta Kabupaten Banyumas</h2>
  </div>
  <div class="flex flex-col md:flex-row gap-6">
    <div class="flex-1">
      <div id="map" class="w-full"></div>
      <p class="text-sm mt-2 text-gray-600">Survey kepuasan indeks layanan infrastruktur di Kabupaten Banyumas terdiri dari: Kecamatan, Desa</p>
    </div>
    <div class="flex flex-col gap-4">
      <div class="flex items-center gap-2">
        <i data-feather="bar-chart-2"></i>
        <span class="text-sm">Statistik Responden</span>
      </div>
      <div class="flex gap-2 flex-wrap">
        <button class="bg-gray-200 px-2 py-1 rounded" onclick="switchChart('pie')" title="Pie"><i data-feather="pie-chart"></i></button>
        <button class="bg-gray-200 px-2 py-1 rounded" onclick="switchChart('bar')" title="Bar"><i data-feather="bar-chart"></i></button>
        <button class="bg-gray-200 px-2 py-1 rounded" onclick="switchChart('line')" title="Line"><i data-feather="activity"></i></button>
        <button class="bg-gray-200 px-2 py-1 rounded" onclick="switchChart('doughnut')" title="Doughnut"><i data-feather="circle"></i></button>
      </div>
    </div>
  </div>

  <div id="chart-scroll-wrapper" class="overflow-x-auto whitespace-nowrap mt-6 pb-4">
    <div id="chart-container" class="flex gap-4"></div>
  </div>
</main>

<script>
  feather.replace();
  Chart.register(ChartDataLabels);

  const map = L.map('map', { attributionControl: false }).setView([-7.425, 109.25], 10);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

  const geoUrl = '{{ asset("geo/banyumas.geojson") }}';
  const markers = [];
  let geoLayer;

  fetch(geoUrl).then(r => r.json()).then(g => {
    geoLayer = L.geoJSON(g, {
      style: {
        color: '#2563eb', weight: 2, opacity: 1, fillColor: '#3b82f6', fillOpacity: 0.2
      }
    }).addTo(map);
    map.fitBounds(geoLayer.getBounds());
  });

  const koordinat = @json($koordinat);
  function renderMarkers(filterKec) {
    markers.forEach(m => map.removeLayer(m));
    koordinat.forEach(p => {
      if (!p.latitude || !p.longitude) return;
      if (filterKec && p.desa && !p.desa.includes(filterKec)) return;
      const marker = L.circleMarker([p.latitude, p.longitude], {
        radius: 5, color: '#ef4444', fillOpacity: .8
      }).bindPopup(`<strong>${p.nama}</strong>`);
      marker.addTo(map);
      markers.push(marker);
    });
  }

  function filterData() {
    const kec = document.getElementById('filterKecamatan').value;
    renderMarkers(kec);
  }

  renderMarkers();

  const pieData = @json($pieCharts);
  const pieLabels = @json($pieLabels);

  function switchChart(type) {
    const container = document.getElementById('chart-container');
    container.innerHTML = '';

    pieData.forEach((data, i) => {
      const div = document.createElement('div');
      div.className = 'bg-white p-4 rounded shadow w-72 flex-shrink-0';
      div.innerHTML = `<h3 class="text-sm font-medium mb-2 truncate">${pieLabels[i]}</h3><canvas id="chart${i}"></canvas>`;
      container.appendChild(div);

      new Chart(document.getElementById(`chart${i}`), {
        type: type,
        data: {
          labels: data.map(x => x.jawaban),
          datasets: [{
            data: data.map(x => x.jumlah),
            backgroundColor: ['#2563eb', '#10b981', '#f97316', '#facc15', '#ef4444']
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: { position: 'right' },
            datalabels: {
              formatter: (value, ctx) => {
                const dataArr = ctx.chart.data.datasets[0].data;
                const total = dataArr.reduce((a, b) => a + b, 0);
                return ((value / total) * 100).toFixed(1) + '%';
              },
              color: '#fff',
              font: { weight: 'bold', size: 14 }
            }
          }
        }
      });
    });
  }

  switchChart('pie');
</script>
</body>
</html>
