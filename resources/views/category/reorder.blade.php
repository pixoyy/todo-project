@extends('main')

@section('main-content')
    <x-custom-card>
        <div class="mb-4">
            <label for="project_id" class="form-label fw-semibold">Pilih Project:</label>
            <select id="project_id" name="project_id" class="form-select border-grey" style="max-width: 400px;">
                <option value="">-- Pilih Project --</option>
                @foreach ($projects as $project)
                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                @endforeach
            </select>
        </div>

        <div id="categories-container" style="display: none;">
            <div class="alert alert-info mb-3">
                <i class="bi bi-info-circle"></i>
                <strong>Cara Menggunakan:</strong> Ubah angka urutan pada setiap category, lalu klik tombol <strong>Simpan Perubahan</strong> untuk menyimpan.
            </div>

            <form id="reorder-form">
                @csrf
                <input type="hidden" name="project_id" id="form_project_id">
                
                <div id="categories-list" class="mb-3">
                    <!-- Categories will be loaded here -->
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-blue">
                        <i class="bi bi-check-lg"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('categories') }}" class="btn btn-secondary">
                        <i class="bi bi-x-lg"></i> Batal
                    </a>
                </div>
            </form>
        </div>

        <div id="empty-state" class="text-center py-5">
            <i class="bi bi-folder2-open" style="font-size: 3rem; color: #ccc;"></i>
            <p class="mt-3 text-muted">Pilih project untuk menampilkan categories</p>
        </div>
    </x-custom-card>
@endsection

@push('css')
    <style>
        .category-item {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            transition: all 0.2s;
        }
        .category-item:hover {
            background: #e9ecef;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .order-input {
            width: 80px;
            text-align: center;
            font-weight: 600;
        }
        .category-color-dot {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            display: inline-block;
            border: 2px solid #fff;
            box-shadow: 0 0 0 1px #dee2e6;
        }
    </style>
@endpush

@push('js')
    <script>
        $(() => {
            $('#project_id').on('change', function() {
                const projectId = $(this).val();
                
                if (!projectId) {
                    $('#categories-container').hide();
                    $('#empty-state').show();
                    return;
                }

                loadCategories(projectId);
            });

            $('#reorder-form').on('submit', function(e) {
                e.preventDefault();
                
                const projectId = $('#form_project_id').val();
                const orders = {};
                
                $('.category-order').each(function() {
                    const categoryId = $(this).data('category-id');
                    const order = $(this).val();
                    orders[categoryId] = order;
                });

                // Check for duplicate orders
                const orderValues = Object.values(orders);
                const hasDuplicates = orderValues.some((val, idx) => orderValues.indexOf(val) !== idx);
                
                if (hasDuplicates) {
                    toastr.error('Terdapat nomor urutan yang duplikat! Pastikan setiap category memiliki urutan yang unik.');
                    return;
                }

                $.ajax({
                    url: "{{ route('categories_bulk_update') }}",
                    method: 'PATCH',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        project_id: projectId,
                        orders: orders
                    },
                    beforeSend: () => {
                        loading();
                    },
                    success: (data) => {
                        if (data.status) {
                            toastr.success(data.message);
                            setTimeout(() => {
                                window.location.href = "{{ route('categories') }}";
                            }, 1000);
                        } else {
                            toastr.error(data.message);
                        }
                    },
                    error: () => {
                        toastr.error('Terjadi kesalahan! Silakan coba lagi!');
                    },
                    complete: () => {
                        doneLoading();
                    }
                });
            });
        });

        function loadCategories(projectId) {
            $.ajax({
                url: "{{ route('categories_reorder_data') }}",
                method: 'GET',
                data: { project_id: projectId },
                beforeSend: () => {
                    loading();
                },
                success: (data) => {
                    if (data.categories && data.categories.length > 0) {
                        renderCategories(data.categories, projectId);
                        $('#categories-container').show();
                        $('#empty-state').hide();
                    } else {
                        $('#categories-list').html(`
                            <div class="text-center py-4">
                                <p class="text-muted">Tidak ada category untuk project ini</p>
                            </div>
                        `);
                        $('#categories-container').show();
                        $('#empty-state').hide();
                    }
                },
                error: () => {
                    toastr.error('Terjadi kesalahan saat memuat categories!');
                },
                complete: () => {
                    doneLoading();
                }
            });
        }

        function renderCategories(categories, projectId) {
            $('#form_project_id').val(projectId);
            
            let html = '';
            categories.forEach((category, index) => {
                const colorDot = category.color 
                    ? `<span class="category-color-dot" style="background-color: ${category.color}"></span>`
                    : '<span class="text-muted">-</span>';
                
                html += `
                    <div class="category-item">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <input type="number" 
                                    class="form-control order-input category-order" 
                                    data-category-id="${category.id}"
                                    value="${category.order}"
                                    min="0"
                                    required>
                            </div>
                            <div class="col">
                                <div class="d-flex align-items-center gap-2">
                                    ${colorDot}
                                    <strong>${category.name}</strong>
                                    <span class="badge bg-secondary">${category.tasks_count} task(s)</span>
                                    ${category.status == 1 
                                        ? '<span class="badge bg-success">Aktif</span>' 
                                        : '<span class="badge bg-danger">Tidak Aktif</span>'}
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            $('#categories-list').html(html);
        }
    </script>
@endpush
