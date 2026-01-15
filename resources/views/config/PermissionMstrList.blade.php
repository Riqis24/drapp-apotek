<x-app-layout>
    <div id="main">
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>
        <div class="page-heading">
            <h3>Permission Master</h3>
        </div>
        <div class="page-content">
            <div class="card">
                <div class="card-header">
                    <button class="btn btn-outline-primary btn-sm rounded" type="button" data-bs-toggle="modal"
                        data-bs-target="#modalAddRole">
                        Add Permission
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="myTable" class="table table-striped table-bordered table-sm nowrap"
                            style="width:100%">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width:5%; text-align: center">No</th>
                                    <th style="width:75%;text-align: center">Name</th>
                                    <th style="width:20%; text-align: center">Guard Name</th>
                                    {{-- <th>Password</th> --}}
                                    {{-- <th>Role</th> --}}
                                    {{-- <th>Status</th> --}}
                                    {{-- <th>Aksi</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($perms as $perm)
                                    <tr>
                                        <td style="text-align: center">{{ $loop->iteration }}</td>
                                        <td style="text-align: left">{{ $perm->name }}</td>
                                        <td style="text-align: center">{{ $perm->guard_name }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    ini footer
                </div>
            </div>
        </div>
    </div>

    {{-- modal add user --}}
    <form action="{{ route('PermissionMstr.store') }}" method="POST">
        @csrf
        <div class="modal fade" id="modalAddRole" tabindex="-1" aria-labelledby="modalAddRoleLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalAddRoleLabel">Modal title</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="dynamicPermissionInputs">
                            <div class="permission-row">
                                <div class="row">
                                    <div class="col-md-9">
                                        <input type="text" class="form-control form-control-sm" name="permissions[]"
                                            placeholder="Contoh: produk.view" required>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="button"
                                            class="btn btn-danger btn-sm rounded removeRow">❌</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-info" id="addRow">Add Row</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    @push('scripts')
        <script src="{{ 'assets/js/PermissionMstr/getData.js' }}"></script>
        <script src="{{ 'assets/js/alert.js' }}"></script>
    @endpush
</x-app-layout>
