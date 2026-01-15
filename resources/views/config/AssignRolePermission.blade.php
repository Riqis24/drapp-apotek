<x-app-layout>
    <div id="main">
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>
        <div class="page-heading">
            <h3>Assign Role</h3>
        </div>
        <div class="page-content">
            <div class="card">
                <div class="card-body">
                    <h4>Assign Permissions for: {{ $role->name }}</h4>

                    <form method="POST" action="{{ route('RoleMstr.update', $role->id) }}">
                        @csrf
                        @method('PUT')
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Permission Group</th>
                                    <th>
                                        Permissions
                                        <br>
                                        <input type="checkbox" id="check-all-global"><small><b>Check All</b></small>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($permissions as $group => $groupPermissions)
                                    @php
                                        $sortedPermissions = collect($groupPermissions)->sortBy(function ($permission) {
                                            return Str::after($permission->name, '.');
                                        });
                                    @endphp

                                    <tr>
                                        <td>
                                            <strong>{{ ucfirst($group) }}</strong><br>
                                            <input type="checkbox" class="check-all-row" data-row="{{ $loop->index }}">
                                            <small><b>Check Group</b></small>
                                        </td>
                                        <td>
                                            @foreach ($sortedPermissions as $permission)
                                                <label style="margin-right: 1rem;">
                                                    <input type="checkbox" name="permissions[]"
                                                        class="checkbox-permission permission-row-{{ $loop->parent->index }}"
                                                        value="{{ $permission->name }}"
                                                        {{ $role->permissions->contains('name', $permission->name) ? 'checked' : '' }}>
                                                    {{ Str::after($permission->name, '.') }}
                                                </label>
                                            @endforeach
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <br>
                        <button type="submit" class="btn btn-sm btn-success rounded">Update Permissions</button>
                        <button type="button" class="btn btn-sm btn-dark rounded rounded"
                            onclick="window.location.href='{{ route('RoleMstr.index') }}'">⬅ Back</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Global Check All
            document.getElementById('check-all-global').addEventListener('change', function() {
                const allPermissions = document.querySelectorAll('.checkbox-permission');
                allPermissions.forEach(cb => cb.checked = this.checked);
            });

            // Check All per Row
            document.querySelectorAll('.check-all-row').forEach((check, index) => {
                check.addEventListener('change', function() {
                    const groupCheckboxes = document.querySelectorAll('.permission-row-' + index);
                    groupCheckboxes.forEach(cb => cb.checked = this.checked);
                });
            });
        </script>

        {{-- <script src="{{ 'assets/js/PermissionMstr/getData.js' }}"></script> --}}
        <script src="{{ asset('assets/js/alert.js') }}"></script>
    @endpush
</x-app-layout>
