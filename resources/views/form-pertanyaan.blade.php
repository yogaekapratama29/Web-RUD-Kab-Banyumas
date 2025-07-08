<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Form Kuisioner – {{ ucfirst($kategori) }}</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans">
  <main class="px-6 py-6 max-w-4xl mx-auto">
    <form method="POST" action="{{ route('submit') }}" class="bg-white p-6 rounded-xl shadow space-y-6">
      @csrf
      <h2 class="text-lg font-semibold text-gray-700 mb-4">Kategori: {{ ucfirst(str_replace('_', ' ', $kategori)) }}</h2>

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

      <div class="grid md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium">Latitude</label>
          <input type="text" name="latitude" id="latitude" class="w-full border rounded px-3 py-2 mt-1" required>
        </div>
        <div>
          <label class="block text-sm font-medium">Longitude</label>
          <input type="text" name="longitude" id="longitude" class="w-full border rounded px-3 py-2 mt-1" required>
        </div>
      </div>

      <h3 class="text-md font-bold text-green-800 mt-6 mb-2 border-b pb-1">Pertanyaan Kategori {{ ucfirst(str_replace('_', ' ', $kategori)) }}</h3>
      @foreach($questions as $q)
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

      <button type="submit" class="mt-6 px-6 py-2 bg-blue-600 text-white font-semibold rounded hover:bg-blue-700">Kirim Kuisioner</button>
    </form>
  </main>
</body>
</html>
