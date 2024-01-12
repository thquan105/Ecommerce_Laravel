@extends('layouts.seller.app')

@section('content')
    <!-- Main content -->
    <section class="content pt-4">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Sửa Sản phẩm</h3>
                            <a href="{{ route('seller.products.index') }}" class="btn btn-success shadow-sm float-right"> <i
                                    class="fa fa-arrow-left"></i>
                                Trở về</a>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <form method="post" action="{{ route('seller.products.update', $product->id()) }}"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="form-group row border-bottom pb-4">
                                    <label for="parent_id" class="col-sm-2 col-form-label">Danh mục chính</label>
                                    <div class="col-sm-10">
                                        <select class="form-control select2bs4" data-live-search="true" name="parent_id"
                                            id="parent_id">
                                            <option value="">Select</option>
                                            @foreach ($categories as $category)
                                                <option
                                                    {{ $product->data()['idCategory'] == $category->id() ? 'selected' : null }}
                                                    value="{{ $category->id() }}"
                                                    data-tokens="{{ $category->data()['name'] }}">
                                                    {{ $category->data()['name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row border-bottom pb-4">
                                    <label for="subparent_id" class="col-sm-2 col-form-label">Danh mục phụ</label>
                                    <div class="col-sm-10">
                                        <select class="form-control select2bs4" data-live-search="true" name="subparent_id"
                                            id="subparent_id">
                                            <option value="">Select</option>
                                            @foreach ($subcategories as $subcategory)
                                                <option
                                                    {{ $product->data()['idSubCategory'] == $subcategory->id() ? 'selected' : null }}
                                                    value="{{ $subcategory->id() }}"
                                                    data-tokens="{{ $subcategory->data()['name'] }}">
                                                    {{ $subcategory->data()['name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row border-bottom pb-4">
                                    <label for="cateShop_id" class="col-sm-2 col-form-label">Danh mục Shop</label>
                                    <div class="col-sm-10">
                                        <select class="form-control select2bs4" data-live-search="true" name="cateShop_id"
                                            id="cateShop_id">
                                            <option value="">Select</option>
                                            @foreach ($ShopCategories as $ShopCategory)
                                                <option
                                                    {{ $product->data()['idCategoryShop'] == $ShopCategory->id() ? 'selected' : null }}
                                                    value="{{ $ShopCategory->id() }}"
                                                    data-tokens="{{ $ShopCategory->data()['name'] }}">
                                                    {{ $ShopCategory->data()['name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row border-bottom pb-4">
                                    <label for="name" class="col-sm-2 col-form-label">Tên Sản phẩm</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="name"
                                            value="{{ $product->data()['name'] ?? '' }}" id="name">
                                    </div>
                                </div>
                                <div class="form-group row border-bottom pb-4">
                                    <label for="description" class="col-sm-2 col-form-label">Mô Tả</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" name="description" id="description" cols="30" rows="5">{{ $product->data()['description'] ?? '' }}</textarea>
                                    </div>
                                </div>
                                @php
                                    $productRef = app('firebase.firestore')
                                        ->database()
                                        ->collection('product');
                                    $imgProduct = $productRef
                                        ->document($product->id())
                                        ->collection('image')
                                        ->documents();
                                    $attributes = $productRef
                                        ->document($product->id())
                                        ->collection('option')
                                        ->documents();
                                @endphp
                                <div
                                    class="form-group border-bottom pb-4 {{ $errors->has('gallery') ? 'has-error' : '' }}">
                                    <label for="gallery" class="col-sm-2 col-form-label">Hình Ảnh</label>
                                    <div class="needsclick dropzone" id="gallery-dropzone">
                                        @foreach($imgProduct as $img)
                                                <img src="{{ $img->data()['url'] }}" alt="Preview"
                                                    style="max-width:200px; max-height:200px;">
                                        @endforeach
                                    </div>
                                    @if ($errors->has('gallery'))
                                        <em class="invalid-feedback">
                                            {{ $errors->first('gallery') }}
                                        </em>
                                    @endif
                                </div>
                                <!-- Attribute -->
                                <div class="form-group border-bottom pb-4">
                                    <label for="gallery" class="col-sm-2 col-form-label">Phân loại</label>
                                    <div id="attributeContainer">
                                        @foreach ($attributes as $attribute)
                                            <hr width="100%" size="5px" align="center" color="black" />
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label class="font-italic" for="attributeImage">Hình ảnh</label>
                                                    <input type="file" class="form-control" name="attributeImage[]"
                                                        accept="image/*" onchange="previewImage(this)">
                                                    <img id="imagePreview" src="{{ $attribute->data()['image'] }}"
                                                        alt="Preview" style="max-width:150px; max-height:150px;">
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-md-4">
                                                    <label class="font-italic" for="attributeName">Tên phân loại</label>
                                                    <input class="form-control" type="text" name="attributeName[]"
                                                        value="{{ $attribute->data()['name'] }}">
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label class="font-italic" for="attributePrice">Giá</label>
                                                    <input type="number" class="form-control"
                                                        value="{{ $attribute->data()['price'] }}" name="attributePrice[]"
                                                        required>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label class="font-italic" for="attributeQuantity">Số lượng</label>
                                                    <input type="number" class="form-control"
                                                        value="{{ $attribute->data()['quantity'] }}"
                                                        name="attributeQuantity[]">
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-danger btn-sm"
                                                onclick="removeAttributeRow(this)">Xóa phân loại</button>
                                            <hr width="100%" size="5px" align="center" color="black" />
                                        @endforeach
                                    </div>
                                    <!-- Button add attribute -->
                                    <button type="button" class="btn btn-info btn-sm" onclick="addAttributeRow()">Thêm
                                        phân
                                        loại</button>
                                </div>
                                <div class="form-group row justify-content-center">
                                    <button type="submit" class="btn btn-success btn-lg">Lưu sản phẩm</button>
                                </div>
                            </form>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection

@push('script-alt')
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"
        integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(function() {
            //Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })
        })
    </script>
@endpush
@push('script-alt')
    <script>
        $('#parent_id').on('change', function() {
            var parent_id = this.value;
            if (parent_id == null || parent_id == '') {
                return;
            } else {
                $.ajax({
                    url: "{{ url('get-subcategories') }}",
                    type: "POST",
                    data: {
                        category_id: parent_id,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(result) {
                        $('#subparent_id').html('<option value="">Select</option>');
                        $.each(result.subcategories, function(key, value) {
                            // console.log(value);
                            $("#subparent_id").append('<option value="' + key +
                                '" data-tokens="' + value + '">' + value +
                                '</option>');
                        });
                    }
                });
            }
        });
    </script>
@endpush
@push('script-alt')
    <script>
        var uploadedGalleryMap = {}
        Dropzone.options.galleryDropzone = {
            url: "{{ route('seller.products.storeImage') }}",
            maxFilesize: 5, // MB
            acceptedFiles: '.jpeg,.jpg,.png,.gif',
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            success: function(file, response) {
                $('form').append('<input type="hidden" name="gallery[]" value="' + response.name + '">')
                uploadedGalleryMap[file.name] = response.name
            },
            removedfile: function(file) {
                file.previewElement.remove()
                var name = ''
                if (typeof file.file_name !== 'undefined') {
                    name = file.file_name
                } else {
                    name = uploadedGalleryMap[file.name]
                }
                $('form').find('input[name="gallery[]"][value="' + name + '"]').remove()
            },
            init: function() {

            },
            error: function(file, response) {
                if ($.type(response) === 'string') {
                    var message = response //dropzone sends it's own error messages in string
                } else {
                    var message = response.errors.file
                }
                file.previewElement.classList.add('dz-error')
                _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
                _results = []
                for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                    node = _ref[_i]
                    _results.push(node.textContent = message)
                }
                return _results
            }
        }
    </script>
@endpush

@push('script-alt')
    <script>
        function addAttributeRow() {
            // Tạo một dòng mới cho attribute
            var attributeRow = document.createElement("div");
            attributeRow.innerHTML = `
            <hr width="100%" size="5px" align="center" color="black" />
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label class="font-italic" for="attributeImage">Hình ảnh</label>
                    <input type="file" class="form-control" name="attributeImage[]" accept="image/*" onchange="previewImage(this)" required>
                    <img id="imagePreview" src="#" alt="Preview" style="max-width:45px; max-height:45px; display:none;">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label class="font-italic" for="attributeName">Tên phân loại</label>
                    <input class="form-control" type="text" name="attributeName[]" required>
                </div>
                <div class="form-group col-md-4">
                    <label class="font-italic" for="attributePrice">Giá</label>
                    <input type="number" class="form-control" name="attributePrice[]" required>
                </div>
                <div class="form-group col-md-4">
                    <label class="font-italic" for="attributeQuantity">Số lượng</label>
                    <input type="number" class="form-control" name="attributeQuantity[]" required>
                </div>
            </div>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeAttributeRow(this)">Xóa phân loại</button>
            <hr width="100%" size="5px" align="center" color="black" />
        `;

            // Thêm dòng mới vào container
            document.getElementById("attributeContainer").appendChild(attributeRow);
        }

        function removeAttributeRow(button) {
            // Xóa dòng attribute khi click nút Remove
            button.parentNode.remove();
        }

        function previewImage(input) {
            var imagePreview = input.nextElementSibling; // Lấy phần tử img ngay sau input
            var file = input.files[0];

            if (file) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';
                };

                reader.readAsDataURL(file);
            } else {
                imagePreview.src = '#';
                imagePreview.style.display = 'none';
            }
        }
    </script>
@endpush
