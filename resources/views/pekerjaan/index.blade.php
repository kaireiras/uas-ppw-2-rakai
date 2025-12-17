@extends('base')
@section('title','Pekerjaan')
@section('menupekerjaan', 'underline decoration-4 underline-offset-7')
@section('content')
    <section class="p-4 bg-white rounded-lg min-h-[50vh]">
        <h1 class="text-3xl font-bold text-[#C0392B] mb-6 text-center">Pekerjaan</h1>

        <div class="mx-auto max-w-screen-xl">
            @if(session('success'))
                <div class="mb-4 rounded-md bg-green-100 border border-green-300 px-4 py-3 text-green-800 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 rounded-md bg-red-100 border border-red-300 px-4 py-3 text-red-800 text-sm">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <a href="{{ route('pekerjaan.add') }}" class="rounded-md bg-green-600 px-4 py-2 text-sm text-white hover:bg-green-700">
                    Tambah Data
                </a>

                {{-- search dan filter terhapus --}}
                <form class="flex w-full max-w-sm gap-2 items-center" autocomplete="off">
                    <input type="text"
                           name="keyword"
                           value="{{ request('keyword') }}"
                           placeholder="Masukkan kata kunci..."
                           class="w-full rounded-md border px-3 py-2 text-sm">

                    <label class="flex items-center gap-1 text-sm whitespace-nowrap">
                        <input type="checkbox"
                               name="deleted"
                               value="1"
                               {{ request('deleted') ? 'checked' : '' }}>
                        Terhapus
                    </label>

                    <button type="submit"
                            class="rounded-md bg-blue-600 px-4 py-2 text-sm text-white hover:bg-blue-700 cursor-pointer">
                        Cari
                    </button>
                </form>
            </div>

            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="min-w-full divide-y divide-x divide-gray-200 text-sm">
                    <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700" width="1">No</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Nama Pekerjaan</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Deskripsi</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Jumlah Pegawai</th>
                        <th class="px-4 py-3 text-center font-semibold text-gray-700" width="1"></th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($data as $k => $d)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $data->firstItem() + $k }}</td>
                            <td class="px-4 py-3 font-medium text-gray-900">{{ $d->nama }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $d->deskripsi }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $d->pegawai_count }}</td>

                            {{-- pembeda antara aktif dan terhapus --}}
                            <td class="px-4 py-3 text-center text-gray-600">
                                <div class="inline-flex rounded-md shadow-sm" role="group">

                                    @if($d->deleted_at)
                                        {{-- restore --}}
                                        <form action="{{ route('pekerjaan.restore', $d->id) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                    class="cursor-pointer rounded-md border border-gray-300 bg-white px-3 py-1.5 text-xs font-medium text-green-600 hover:bg-green-50">
                                                Restore
                                            </button>
                                        </form>
                                    @else
                                        {{-- edit --}}
                                        <a href="{{ route('pekerjaan.edit', ['id' => $d->id]) }}"
                                           class="cursor-pointer rounded-l-md border border-gray-300 bg-white px-3 py-1.5 text-xs font-medium text-blue-600 hover:bg-blue-50">
                                            Edit
                                        </a>

                                        {{-- letak soft delete di sini --}}
                                        <form action="{{ route('pekerjaan.destroy', ['id' => $d->id]) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="cursor-pointer rounded-r-md border border-l-0 border-gray-300 bg-white px-3 py-1.5 text-xs font-medium text-red-600 hover:bg-red-50">
                                                Delete
                                            </button>
                                        </form>
                                    @endif

                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                                Data kosong
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $data->links() }}
            </div>
        </div>
    </section>
@endsection
