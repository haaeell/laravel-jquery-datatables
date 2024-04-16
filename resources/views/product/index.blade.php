@extends('layouts.app')

@section('content')
<div class="container mx-auto py-3 md:py-8">

  <div class="overflow-x-auto shadow-md p-5 rounded-xl">
    <button class="btn btn-primary mb-4" onclick="addForm()">
      Add Product
    </button>
    @php
        $tHead = ['No','Name', 'Stock', 'Price', 'Action'];
    @endphp
    <table class="table border-red-200" id="product-table">
      <thead>
        <tr>
            @foreach ($tHead as $item)
                <th>{{ $item }}</th>
            @endforeach
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
  </div>
</div>

@include('product.form')

@endsection

@section('scripts')
<script>

    function showToast(message, backgroundColor) {
        Toastify({
            text: message,
            duration: 3000,
            gravity: "top", 
            position: "right", 
            backgroundColor: backgroundColor,
            style: {
                boxShadow: "0 0 20px rgba(0, 0, 255, 0.5)",
                borderRadius: "10px", 
            }
        }).showToast();
    }
    
    function deleteData(id) {
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        if (confirm('Are you sure you want to delete this product?')) {
            $.ajax({
                url: '/product/' + id,
                type: 'DELETE',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': csrfToken // Menambahkan token CSRF ke header permintaan
                },
                success: function(response) {
                    if (response.success) {
                        $('#product-table').DataTable().row($('#product-table tr[data-id="' + id + '"]')).remove().draw();
                        showToast(response.message, "#07e868");
                    } else {
                        showToast('An error occurred while deleting the product.', "#e80707");
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    showToast(xhr.responseText, "#e80707");
                }
            });
        }
    }

    function addForm() {
        save_method = "add";
        $('input[name=_method]').val('POST');
        $('#modalForm').addClass('modal'); 
        $('#modalForm').addClass('modal-open');
        $('#modalForm').removeClass('hidden'); 
        $('#modalForm form')[0].reset();
        $('.form-title').text('Add Product');
    }

    function editForm(id) {
        save_method = "edit";
        $('input[name=_method]').val('PUT');
        
        $.ajax({
            url: "{{ url('product') }}" + '/' + id + "/edit",
            type: 'GET',
            dataType: "JSON",
            success: function(response) {
                console.log(response);
                $('#modalForm').addClass('modal');
                $('#modalForm').addClass('modal-open');
                $('#modalForm').removeClass('hidden');


                $('#id').val(response.id);
                $('#name').val(response.name);
                $('#stock').val(response.stock);
                $('#price').val(response.price);
                $('.form-title').text('Edit Product');
            },
            error: function(xhr, status, error) {
                alert("Nothing Data");
                console.error(error);
            }
        });
    }

    function closeModal() {
        $('#form-item')[0].reset();
        $('#modalForm').removeClass('modal-open'); 
        $('#modalForm').addClass('hidden'); 
    }

    // PROSES 
    $(document).ready(function() {

        // GET DATA
        const table =  $('#product-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('products') }}",
            columns: [
                { data: null, render: function(data, type, row, meta) {
                    return meta.row + 1;
                }},
                { data: 'name', name: 'name' },
                { data: 'stock', name: 'stock' },
                { data: 'price', name: 'price' },
                { data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            order: [[0, 'desc']]
        });

        // ADD AND UPDATE DATA
        $('#form-item').submit(function(event) {
            event.preventDefault(); 

            const id = $('#id').val(); 
            const url = id ? '/product/' + id : '/product';
            const method = id ? 'PUT' : 'POST';


            $.ajax({
                url: url,
                type: method,
                data: $(this).serialize(),
                success: function(response) {
                    console.log(response)
                    closeModal();

                    table.row.add({
                        'id': response.data.id,
                        'name': response.data.name,
                        'stock': response.data.stock,
                        'price': response.data.price,
                        'action': '<a href="#" onclick="editForm(' + response.data.id + ')" class="btn btn-warning">Edit</a> ' +
                                '<a href="#" onclick="deleteData(' + response.data.id + ')" class="btn btn-error">Delete</a>'
                    }).draw();
                    showToast(response.message, "#07e868");

                    // Mengosongkan formulir
                    $('#form-item')[0].reset();
                },
                error: function(xhr, status, error) {
                    let errors = JSON.parse(xhr.responseText).errors;
                    let errorMessage = '';
                    for (let key in errors) {
                        if (errors.hasOwnProperty(key)) {
                            errorMessage += errors[key][0] + '\n';
                        }
                    }
                    console.error(errorMessage);
                    showToast(errorMessage.trim(), "#e80707");
                }

            });
        });

   
    });

</script>

@endsection

