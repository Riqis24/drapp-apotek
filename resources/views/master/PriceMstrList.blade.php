<x-app-layout>
    <div id="main">
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>
        <div class="page-heading">
            <h3>Price Master</h3>
        </div>
        <div class="page-content">
            <div class="card">
                <div class="card-header">
                    <button class="btn btn-outline-primary btn-sm rounded" type="button" data-bs-toggle="modal"
                        data-bs-target="#modalAddPrice">
                        Add Price
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="priceTable" class="table table-striped table-bordered table-sm nowrap"
                            style="width:100%">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width:5%; text-align: center">No</th>
                                    <th style="text-align: center">Product</th>
                                    <th style="width:20%; text-align: center">Measurement</th>
                                    <th style="width:20%; text-align: center">Price</th>
                                    <th style="width:5%; text-align: center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($prices as $price)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $price->productMeasurement->product->name }}</td>
                                        <td>{{ $price->productMeasurement->measurement->name }}</td>
                                        <td>{{ rupiah($price->price) }}</td>
                                        <td style="text-align: center">
                                            <button class="btn btn-sm btn-warning btn-edit-price"
                                                data-price-id="{{ $price->id }}"><i class="bi bi-pen"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger rounded"
                                                onclick="window.location.href=''">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
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
    <form action="{{ route('PriceMstr.store') }}" method="POST">
        @csrf
        <div class="modal fade" id="modalAddPrice" tabindex="-1" aria-labelledby="modalAddPriceLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalAddPriceLabel">Price Master</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="code" class="form-label">Product - Satuan</label>
                                <select name="product" style="width: 100%" class="form-control form-control-sm select2"
                                    id="">
                                    <option value=""></option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->product->name }} -
                                            {{ $product->measurement->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label for="" class="form-label">Price</label>
                                <input type="text" class="form-control form-control-sm" name="price" required>
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

    {{-- modal edit --}}
    <div class="modal fade" id="priceModal" tabindex="-1">
        <div class="modal-dialog">
            <form id="priceForm">
                @csrf
                <input type="hidden" id="price_id">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Harga</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-2">
                            <label>Harga</label>
                            <input type="number" class="form-control" id="price" required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit">Simpan</button>
                        <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Batal</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    @push('scripts')
        <script src="{{ 'assets/js/PriceMstr/getData.js' }}"></script>
        <script src="{{ 'assets/js/alert.js' }}"></script>
        <script>
            $(document).on('click', '.btn-edit-price', function() {
                let priceId = $(this).data('price-id');

                $.get('/price/' + priceId, function(res) {
                    $('#price_id').val(res.id);
                    $('#price').val(res.price);

                    $('#priceModal').modal('show');
                });
            });

            $('#priceForm').submit(function(e) {
                e.preventDefault();

                $.ajax({
                    url: '/price/update',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        price_id: $('#price_id').val(),
                        price: $('#price').val(),

                    },
                    success: function() {
                        $('#priceModal').modal('hide');
                        location.reload(); // atau redraw DataTable
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>
