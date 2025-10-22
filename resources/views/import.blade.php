<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Catalog Import</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-900 min-h-screen flex items-center justify-center">
  <div class="bg-gray-800 p-8 rounded-lg shadow-lg w-full max-w-md">
    <h1 class="text-2xl font-bold text-center text-white mb-6">Import Catalog</h1>

    @if(session('ok'))
    <div class="bg-green-900 border border-green-600 text-green-300 px-4 py-3 rounded mb-4">
      {{ session('ok') }}
    </div>
    @endif

    <form method="POST" action="{{ route('import.store') }}" enctype="multipart/form-data">
      @csrf
      <div class="mb-4">
        <input type="file" name="file" required accept=".csv" class="w-full p-3 border-2 border-dashed border-gray-600 rounded-md cursor-pointer hover:border-blue-400 focus:outline-none focus:border-blue-500 bg-gray-700 text-white">
      </div>

      @error('file')
      <div class="bg-red-900 border border-red-600 text-red-300 px-4 py-3 rounded mb-4">
        {{ $message }}
      </div>
      @enderror

      <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-md transition duration-200">
        Import
      </button>
    </form>
  </div>
</body>
</html>
