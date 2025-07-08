<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Form Kuisioner – Infrastruktur Banyumas</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css" />
  <script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>
  <script src="https://unpkg.com/feather-icons"></script>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    #map { height: 340px; border-radius: 12px; }
  </style>
</head>
<body class="bg-gray-50 text-gray-800 font-sans">
  <header class="sticky top-0 z-50 flex items-center justify-between px-6 py-3 bg-white shadow">
    <h1 class="flex items-center gap-2 text-xl font-bold"><i data-feather="edit-3"></i>Form Kuisioner Infrastruktur</h1>
    <span class="text-xs text-gray-500">Tanggal: {{ date('d-m-Y H:i') }}</span>
  </header>

  <main class="px-6 py-6 max-w-4xl mx-auto">
    <form method="POST" action="{{ route('submit') }}" class="bg-white p-6 rounded-xl shadow space-y-6">
      @csrf
      <h2 class="text-lg font-semibold text-gray-700 mb-4">Identitas Responden</h2>

      <div class="grid md:grid-cols-3 gap-4">
        <div>
          <label class="block text-sm font-medium">Nama</label>
          <input type="text" name="nama" class="w-full border rounded px-3 py-2 mt-1" required>
        </div>
        <div>
          <label class="block text-sm font-medium">Usia</label>
          <input type="number" name="usia" class="w-full border rounded px-3 py-2 mt-1" required>
        </div>
        <div>
          <label class="block text-sm font-medium">Desa</label>
          <input type="text" name="desa" class="w-full border rounded px-3 py-2 mt-1" required>
        </div>
      </div>

      <!-- Hidden lokasi otomatis -->
      <input type="hidden" name="latitude" id="latitude">
      <input type="hidden" name="longitude" id="longitude">

      <div class="text-sm text-gray-600 mt-2">
        Lokasi Anda akan diambil secara otomatis untuk keperluan pemetaan. Pastikan GPS/izin lokasi aktif di browser Anda.
      </div>

      <h2 class="text-lg font-semibold text-gray-700 mt-6">Pertanyaan Survei per Kategori</h2>

      @php
        $grouped = $questions->groupBy('kategori');
      @endphp

      @foreach($grouped as $kategori => $qList)
        <div class="mt-6">
          <h3 class="text-md font-bold text-green-800 mb-2 border-b pb-1">{{ ucfirst(str_replace('_', ' ', $kategori)) }}</h3>
          @foreach($qList as $q)
            <div class="mb-4">
              <label class="block font-medium text-sm">{{ $q->pertanyaan }}</label>
              <div class="mt-2 grid grid-cols-5 gap-2 text-center text-xs text-gray-600">
                <label><input type="radio" name="jawaban[{{ $q->id }}]" value="1" required> Sangat Tidak Puas</label>
                <label><input type="radio" name="jawaban[{{ $q->id }}]" value="2"> Tidak Puas</label>
                <label><input type="radio" name="jawaban[{{ $q->id }}]" value="3"> Cukup Puas</label>
                <label><input type="radio" name="jawaban[{{ $q->id }}]" value="4"> Puas</label>
                <label><input type="radio" name="jawaban[{{ $q->id }}]" value="5"> Sangat Puas</label>
              </div>
            </div>
          @endforeach
        </div>
      @endforeach

      <button type="submit" class="mt-6 px-6 py-2 bg-blue-600 text-white font-semibold rounded hover:bg-blue-700">Kirim Kuisioner</button>
    </form>
  </main>

  <script>
    feather.replace();

    // Auto isi lokasi (latitude & longitude)
    window.onload = function () {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (pos) {
          document.getElementById('latitude').value = pos.coords.latitude;
          document.getElementById('longitude').value = pos.coords.longitude;
        }, function (err) {
          console.warn("Gagal mengambil lokasi: " + err.message);
        });
      } else {
        alert("Browser Anda tidak mendukung geolokasi.");
      }
    };
  </script>
</body>
</html>
