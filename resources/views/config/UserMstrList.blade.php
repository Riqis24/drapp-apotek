<x-app-layout>
    <div id="main">
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>
        <div class="page-heading">
            <h3>User Master</h3>
        </div>
        <div class="page-content">
            <div class="card">
                <div class="card-header">
                    {{-- @can('user.create') --}}
                    <button class="btn btn-outline-primary btn-sm rounded" type="button" data-bs-toggle="modal"
                        data-bs-target="#modalAddUser">
                        Add User
                    </button>
                    {{-- @endcan --}}
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="userTable" class="table table-striped table-bordered table-sm nowrap"
                            style="width:100%">
                            <thead class="table-dark">
                                <tr>
                                    <th style="text-align:center;width:5%">No</th>
                                    <th style="text-align:center;">Name</th>
                                    <th style="text-align:center;width:20%">Email</th>
                                    <th style="text-align:center;width:20%">Password</th>
                                    <th style="text-align:center;width:20%">Role</th>
                                    <th style="text-align:center;width:5%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr data-id="{{ $user->user_mstr_id }}">
                                        <td>{{ $loop->iteration }}</td>
                                        <!-- Name -->
                                        <td>
                                            <span class="name-display">{{ $user->user_mstr_name }}</span>
                                            <input type="text" name="f_Name"
                                                class="form-control form-control-sm name-input d-none"
                                                value="{{ $user->user_mstr_name }}">
                                        </td>

                                        <!-- Email -->
                                        <td>
                                            <span class="email-display">{{ $user->user_mstr_email }}</span>
                                            <input type="email" name="f_Email"
                                                class="form-control form-control-sm email-input  d-none"
                                                value="{{ $user->user_mstr_email }}">
                                        </td>

                                        <!-- Password -->
                                        <td>
                                            <span class="password-display">{{ $user->user_mstr_password }}</span>
                                            <input type="text" name="f_Password"
                                                class="form-control form-control-sm password-input  d-none"
                                                value="{{ $user->user_mstr_password }}">
                                        </td>

                                        <!-- Role -->
                                        <td>
                                            <span class="role-display">{{ $user->user_mstr_role }}</span>
                                            <select name="role"
                                                class="form-control form-control-sm role-input d-none">
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->name }}"
                                                        {{ $role->name == $user->user_mstr_role ? 'selected' : '' }}>
                                                        {{ ucfirst($role->name) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <!-- Actions -->
                                        <td>
                                            <button class="btn btn-sm btn-warning edit-btn">Edit</button>
                                            <button
                                                data-url="{{ route('UserMstr.updateInline', $user->user_mstr_id) }}"
                                                class="btn btn-sm btn-success update-btn d-none">✔ Save</button>
                                        </td>
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
    <form action="{{ route('UserMstr.store') }}" method="POST">
        @csrf
        <div class="modal fade" id="modalAddUser" tabindex="-1" aria-labelledby="modalAddUserLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalAddUserLabel">Add User Master</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="fid_Name">Name</label>
                                <input type="text" class="form-control form-control-sm" id="fid_Name"
                                    name="f_Name">
                            </div>
                            <div class="col-md-12 mt-2">
                                <label for="fid_Email">Email</label>
                                <input type="text" class="form-control form-control-sm" id="fid_Email"
                                    name="f_Email">
                            </div>
                            <div class="col-md-12 mt-2">
                                <label for="fid_Password">Password</label>
                                <input type="text" class="form-control form-control-sm" id="fid_Password"
                                    name="f_Password">
                            </div>
                            <div class="col-md-12 mt-2">
                                <label for="fid_Password">Role</label>
                                <select class="form-select form-select-sm" style="width: 100%" name="f_Role"
                                    id="fid_Role">
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    @push('scripts')
        <script src="{{ asset('assets/js/UserMstr/getData.js') }}"></script>
        <script src="{{ asset('assets/js/alert.js') }}"></script>
    @endpush
</x-app-layout>
