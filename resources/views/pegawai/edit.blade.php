@extends('base')
@section('title','Pegawai')
@section('menupegawai', 'underline decoration-4 underline-offset-7')

@section('content')
<section class="p-4 bg-white rounded-lg min-h-[50vh]">
    <h1 class="text-3xl font-bold text-[#C0392B] mb-6 text-center">Edit</h1>

    @if($errors->any())
        <div class="mx-auto max-w-screen-xl mb-4 rounded-md bg-red-100 border border-red-300 px-4 py-3 text-red-800 text-sm">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="mx-auto max-w-screen-xl">
        <form action="{{ route('pegawai.update', $data->id)  }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Nama Pegawai
                </label>
                <input type="text"
                       name="nama"
                       value="{{ old('nama') }}"
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                       required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Email
                </label>
                <input type="email"
                       name="email"
                       value="{{ old('email') }}"
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                       required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Gender
                </label>
                <select name="gender"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                        required>
                    <option value="">-- Pilih Gender --</option>
                    <option value="male" {{ old('gender')=='male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ old('gender')=='female' ? 'selected' : '' }}>female</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Pekerjaan
                </label>
                <select name="pekerjaan_id"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                        required>
                    <option value="">-- Pilih Pekerjaan --</option>
                    @foreach($pekerjaan as $p)
                        <option value="{{ $p->id }}"
                            {{ old('pekerjaan_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Status Pegawai
                </label>
                <select name="is_active"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                        required>
                    <option value="1" {{ old('is_active', 1)==1 ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ old('is_active')==0 ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="captcha" class="block text-sm font-medium text-gray-700">Captcha</label>
                <div class="flex items-center gap-3 mt-1">
                    <input type="text" name="captcha" class="rounded-md border px-3 py-2 w-32" placeholder="Masukkan kode">
                    <img>{!! captcha_img() !!}</img>
                    <button type="button" class="text-sm text-blue-600 hover:underline" onclick="this.previousElementSibling.src='/captcha?'+Math.random()">Refresh</button>

                <script>
                    function refreshCaptcha() {
                        document.querySelector('.captcha-img').src = '/captcha?' + Math.random();
                    }
                </script>
                </div>
                @error('captcha')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex justify-end gap-2">
                <button type="reset"
                        class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                    Reset
                </button>
                <button type="submit"
                        class="rounded-md bg-green-600 px-4 py-2 text-sm text-white hover:bg-green-700">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</section>
@endsection
