@extends('layouts.app')

@section('title')
    <title>Laporan Neraca Saldo Awal</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

@section('content')
    <div class="w-full px-32">
        <div class="card">
            <div class="card-body p-4 mt-20">
                <div class="flex justify-between">
                    <h4 class="card-title fw-semibold mb-4">Laporan Neraca Saldo Awal</h4>
                    <div class="btn btn-circle btn-outline-primary" data-bs-toggle="tooltip" title="Help"><a a
                            href="#" class="flex justify-end items-end" data-bs-toggle="modal"
                            data-bs-target="#detailModal">?</a>
                    </div>
                </div>

                <form id="balanceForm" method="POST" action="{{ route('admin.balances.store') }}">
                    @csrf
                    <input type="hidden" id="balanceFormMethod" name="_method" value="POST">
                    <input type="hidden" id="balanceId" name="id">
                    <div class="flex">
                        <div class="col-md-4 mr-3">
                            <div class="flex justify-between">
                                <label for="id_akun" class="form-label">Account:</label>
                                <div class="mr-1 mt-1" data-bs-toggle="tooltip" title="Add Account"><a
                                        href="{{ route('admin.accounts.index') }}">
                                        <i class="material-icons" style="font-size: 16px;">library_add</i>
                                    </a></div>
                            </div>
                            <select class="form-select @error('id_akun') is-invalid @enderror" id="id_akun"
                                name="id_akun">
                                <option value="" disabled selected>- Choose Account -</option>
                                @foreach ($account as $row)
                                    <option value="{{ $row->id }}">{{ $row->nama_akun }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">
                                @error('id_akun')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4 mr-3" style="width: 35%">
                            <label for="nominal" class="form-label">Nominal:</label>
                            <input type="number" class="form-control @error('nominal') is-invalid @enderror" id="nominal"
                                name="nominal" placeholder="Rp..." value="{{ old('nominal') }}">
                            <div class="invalid-feedback">
                                @error('nominal')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                        <div class=" mt-4 -ml-1">
                            <a href="{{ route('admin.details.index') }}" type="button"
                                class="btn btn-outline-primary m-1"><i class="bi bi-arrow-up-right-circle mr-1"></i> Rincian
                                Data</a>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <div>
                            <button type="button" class="btn btn-outline-danger m-1" id="cancelButton"
                                style="display:none;" onclick="resetForm()">
                                <i class="bi bi-x-lg mr-1"></i> Cancel
                            </button>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-outline-primary m-1" id="submitButton">
                                <i class="bi bi-plus-lg mr-1"></i> Add Data
                            </button>
                        </div>
                    </div>
                </form>

                <hr>

                <div class="overflow-auto table-responsive">
                    @if ($neracaAwal->isEmpty())
                        <div id="data-empty">
                            <p class="flex justify-center items-center font-bold text-center alert alert-info">No Data
                                Available</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <div id="judul" class="text-center mb-4">
                                <p class="font-bold">HARVEST VAPE</p><br>
                                <p class="font-bold -mt-8">NERACA SALDO AWAL</p>
                            </div>
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td class="font-bold" style="width: 3%">Action</td>
                                        <td class="font-bold" style="width: 30%">Akun</td>
                                        <td class="text-nowrap text-end font-bold" style="width: 25%">Aktiva</td>
                                        <td class="text-nowrap text-end font-bold" style="width: 40%">Liabilitas dan Ekuitas
                                        </td>
                                    </tr>
                                    {{-- Aktiva --}}
                                    @forelse ($aktiva as $data)
                                        <tr>
                                            <td style="width: 3%">
                                                <form action="{{ route('admin.balances.destroy', ['id' => $data->id]) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="action-icons">
                                                        <a href="#" class="edit-icon" data-id="{{ $data->id }}"
                                                            data-id_akun="{{ $data->id_akun }}"
                                                            data-nominal="{{ $data->nominal }}" onclick="editData(this)"
                                                            data-bs-toggle="tooltip" title="Edit">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </a>
                                                        <a href="#" class="delete-icon" data-bs-toggle="tooltip"
                                                            title="Edit" data-bs-target="#confirmDeleteModal"
                                                            title="Delete">
                                                            <i class="ti ti-trash"></i>
                                                        </a>

                                                    </div>
                                                </form>
                                            </td>
                                            <td style="width: 30%">{{ $data->account->nama_akun }}</td>
                                            <td class="text-nowrap text-end" style="width: 30%">Rp
                                                {{ number_format($data->nominal, 2, ',', '.') }}</td>
                                            <td style="width: 40%"></td>
                                        </tr>
                                    @empty
                                    @endforelse
                                    {{-- Liabilitas dan Ekuitas --}}
                                    @forelse ($liabilitasEkuitas as $data)
                                        <tr>
                                            <td style="width: 3%">
                                                <form action="{{ route('admin.balances.destroy', ['id' => $data->id]) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="action-icons">
                                                        <a href="#" class="edit-icon"
                                                            data-id="{{ $data->id }}"
                                                            data-id_akun="{{ $data->id_akun }}"
                                                            data-nominal="{{ $data->nominal }}" onclick="editData(this)"
                                                            data-bs-toggle="tooltip" title="Edit">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </a>
                                                        <a href="#" class="delete-icon" data-bs-toggle="tooltip"
                                                            title="Edit" data-bs-target="#confirmDeleteModal"
                                                            title="Delete">
                                                            <i class="ti ti-trash"></i>
                                                        </a>

                                                    </div>
                                                </form>
                                            </td>
                                            <td style="width: 30%">{{ $data->account->nama_akun }}</td>
                                            <td style="width: 40%"></td>
                                            <td class="text-nowrap text-end" style="width: 40%">Rp
                                                {{ number_format($data->nominal, 2, ',', '.') }}</td>
                                        </tr>
                                    @empty
                                    @endforelse
                                    @if ($totalModal > 0)
                                        <tr>
                                            <td style="width: 3%">
                                            </td>
                                            <td style="width: 30%">Modal Harvest Vape</td>
                                            <td style="width: 25%"></td>
                                            <td class="text-nowrap text-end" style="width: 40%">Rp
                                                {{ number_format($totalModal, 2, ',', '.') }}</td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td style="width: 3%">
                                            </td>
                                            <td style="width: 30%">Modal Harvest Vape</td>
                                            <td style="width: 30%"></td>
                                            <td class="text-nowrap text-end" style="width: 40%">Rp
                                                {{ number_format(0, 2, ',', '.') }}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td style="width: 3%">
                                        </td>
                                        <td class="font-bold" style="width: 30%">Total</td>
                                        <td class="text-nowrap text-end font-bold" style="width: 25%">Rp
                                            {{ number_format($totalKiri, 2, ',', '.') }}</td>
                                        <td class="text-nowrap text-end font-bold" style="width: 40%">Rp
                                            {{ number_format($totalKanan, 2, ',', '.') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Confirm Delete Modal -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">
                        Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah anda yakin akan menghapus data ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="confirm-delete-cancel"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirm-delete">Delete</button>
                </div>


            </div>
        </div>
    </div>

    {{-- Detail Modal  --}}
    <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog" style="width: 50%" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="w-full">
                        <div class="flex justify-between w-full">
                            <div class="w-1/2">
                                <h5 class="modal-title font-bold text-sm" id="detailModalLabel">
                                    Informasi Menu Neraca Saldo AWal
                                </h5>
                            </div>
                            <div class="w-1/2 flex justify-end items-end">
                                <button type="button" data-bs-dismiss="modal">
                                    <i class="material-icons" style="font-size: 16px;">clear</i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-body -mt-3">
                    <div class="w-full">
                        <table>
                            <tr>
                                <td class="align-text-top text-start">1.</td>
                                <td>
                                    <P>Menu ini digunakan untuk memasukkan saldo awal usaha sebelum menggunakan sistem</P>
                                </td>
                            </tr>
                            <tr>
                                <td class="align-text-top text-start">2.</td>
                                <td>
                                    <P>Pilih Akun dan input nilai Nominal untuk mengisi data, kemudian klik + Add Data</P>
                                </td>
                            </tr>
                            <tr>
                                <td class="align-text-top text-start">3.</td>
                                <td>
                                    <P>untuk mengubah data yang telah di input, klik icon edit -> <i
                                            class="bi bi-pencil-square"></i>, kemudian ubah data pada field Akun dan
                                        Nominal. Setelah
                                        itu klik <i class="bi bi-pencil-square"></i> Edit Data
                                    </P>
                                </td>
                            </tr>

                            <tr>
                                <td class="align-text-top text-start">4.</td>
                                <td>
                                    <P>Klik <i class="bi bi-x-lg"></i> Cancel, untuk keluar dari mode edit data.
                                    </P>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
@endsection

@section('js')
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        $(document).ready(function() {
            // Gunakan event delegation untuk tombol delete
            $(document).on('click', '.delete-icon', function(e) {
                e.preventDefault();
                var form = $(this).closest("form");
                $('#confirmDeleteModal').modal('show');

                $('#confirm-delete').off('click').on('click', function() {
                    form.submit(); // Submit form untuk fungsi destroy
                });
            });

            $('#confirm-delete-cancel').on('click', function() {
                $('#confirmDeleteModal').modal('hide'); // Menutup modal jika tombol Cancel di klik
            });
        });



        function resetForm() {
            document.getElementById('balanceForm').reset();
            document.getElementById('balanceFormMethod').value = 'POST';
            document.getElementById('balanceForm').action = '{{ route('admin.balances.store') }}';
            document.getElementById('submitButton').innerHTML = '<i class="bi bi-plus-lg mr-1"></i> Add Data';
            document.getElementById('cancelButton').style.display = 'none';
        }

        function editData(element) {
            var id = element.getAttribute('data-id');
            var id_akun = element.getAttribute('data-id_akun');
            var nominal = element.getAttribute('data-nominal');

            document.getElementById('balanceId').value = id;
            document.getElementById('id_akun').value = id_akun;
            document.getElementById('nominal').value = nominal;

            document.getElementById('balanceFormMethod').value = 'PUT';
            document.getElementById('balanceForm').action = '{{ route('admin.balances.update', '') }}/' + id;
            document.getElementById('submitButton').innerHTML = '<i class="bi bi-pencil-square mr-1"></i> Edit Data';
            document.getElementById('cancelButton').style.display = 'inline-block';
        }
    </script>
@endsection
