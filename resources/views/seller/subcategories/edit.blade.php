@extends('layouts.seller.app')

@section('content')
    <!-- Main content -->
    <section class="content pt-4">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Chỉnh sửa danh mục</h3>
                            <a href="{{ route('seller.categories.index') }}" class="btn btn-success shadow-sm float-right"> <i
                                    class="fa fa-arrow-left"></i> Trở lại</a>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <form method="post" action="{{ route('seller.categories.update', ['category'=>$shopCategories->id()]) }}">
                                @csrf
                                @method('put')
                                <div class="form-group row border-bottom pb-4">
                                    <label for="parent_id" class="col-sm-2 col-form-label">Danh mục chính</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" name="parent_id" id="parent_id">
                                            @foreach ($categories as $category)
                                                <option {{ $shopCategories->data()['idCategory'] == $category->id() ? 'selected' : null }}
                                                    value="{{ $category->id() }}"> {{ $category->data()['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row border-bottom pb-4">
                                    <label for="parent_id" class="col-sm-2 col-form-label">Danh mục phụ</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" name="subparent_id" id="subparent_id">
                                            @foreach ($subcategoryList as $subcategory)
                                                <option {{ $shopCategories->data()['idSubCategory'] == $subcategory->id() ? 'selected' : null }}
                                                    value="{{ $subcategory->id() }}"> {{ $subcategory->data()['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row border-bottom pb-4">
                                    <label for="name" class="col-sm-2 col-form-label">Tên danh mục</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="name"
                                            value="{{ old('name', $shopCategories->data()['name']) }}" id="name">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success">Lưu</button>
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
    <script>
        $('#parent_id').on('change', function() {
            var parent_id = this.value;
            $('#subparent_id').html('<option value="">Select</option>');
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
                            //console.log(value);
                            $("#subparent_id").append('<option value="' + key + '">' + value +
                                '</option>');
                        });
                    }
                });
            }
        });
    </script>
@endpush

