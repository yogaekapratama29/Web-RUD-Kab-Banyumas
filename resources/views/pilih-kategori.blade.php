<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pilih Kategori – Kuisioner</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800 font-sans">
  <div class="max-w-xl mx-auto mt-20 p-6 bg-white shadow rounded">
    <h1 class="text-lg font-semibold mb-4">Pilih Kategori Kuisioner</h1>
    <form action="" method="GET">
      <select id="kategori" name="kategori" onchange="goToKategori(this.value)" class="w-full border p-2 rounded">
        <option value="">-- Pilih Kategori --</option>
        @foreach($kategoriList as $kat)
          <option value="{{ $kat }}">{{ ucfirst(str_replace('_', ' ', $kat)) }}</option>
        @endforeach
      </select>
    </form>
  </div>

  <script>
    function goToKategori(kat) {
      if (kat) {
        window.location.href = '/form/' + kat;
      }
    }
  </script>
</body>
</html>
