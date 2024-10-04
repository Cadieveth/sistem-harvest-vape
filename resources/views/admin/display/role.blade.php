@extends('layouts.app')

@section('title')
    <title>Access Control - Harvest Vape</title>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Access Control</h5>
                {{-- button add --}}
                <a href="#" type="button" class="btn btn-outline-primary m-1">Add Role</a>
                <hr>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Role</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roles as $role)
                                <tr>
                                    <td>
                                        <div>
                                            <div id="role"><b>{{ $role->role }}</b></div>
                                            <div>penjelasan</div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirm Delete Modal -->
    {{-- <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Anda yakin akan delete data ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirm-delete">Delete</button>
                </div>
            </div>
        </div>
    </div> --}}
@endsection

@section('css')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
@endsection

@section('js')
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#table1').on('click', '.delete-icon', function(e) {
                var form = $(this).closest("form");
                e.preventDefault();
                $('#confirmDeleteModal').modal('show');

                $('#confirm-delete').click(function() {
                    form.submit();
                });
            });

            @if (session()->has('success'))
                toastr.success('{{ session('success') }}');
            @elseif (session()->has('error'))
                toastr.error('{{ session('error') }}');
            @endif
        });
    </script>
@endsection
