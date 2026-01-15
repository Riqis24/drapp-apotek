<x-app-layout>
    <div id="main">
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>
        <div class="page-heading">
            <h3>Product Master</h3>
        </div>
        <div class="page-content">
            <div class="card">
                <div class="card-header">
                    <button class="btn btn-outline-primary btn-sm rounded" type="button" data-bs-toggle="modal"
                        data-bs-target="#modalAddPlacement">
                        Add Place
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="productTable" class="table table-striped table-bordered table-sm nowrap"
                            style="width:100%">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width:5%; text-align: center">No</th>
                                    <th style="width:20%; text-align: center">Code</th>
                                    <th style="width:20%; text-align: center">Name</th>
                                    <th style="text-align: center">Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($placements as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->code }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->description }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- modal add user --}}
    <form action="{{ route('ProductPlacement.store') }}" method="POST">
        @csrf
        <div class="modal fade" id="modalAddPlacement" tabindex="-1" aria-labelledby="modalAddPlacementLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalAddPlacementLabel">Product Placement Master</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="code" class="form-label">Code</label>
                                <input type="text" class="form-control form-control-sm" name="code"
                                    placeholder="Contoh: PP-001" required>
                            </div>
                            <div class="col-md-4">
                                <label for="product" class="form-label">Name</label>
                                <input type="text" class="form-control form-control-sm" name="name" required>
                            </div>
                            <div class="col-md-4">
                                <label for="" class="form-label">Description</label>
                                <input type="text" class="form-control form-control-sm" name="description" required>
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
        <script src="{{ 'assets/js/ProductMstr/getData.js' }}"></script>
        <script src="{{ 'assets/js/alert.js' }}"></script>
    @endpush
</x-app-layout>
