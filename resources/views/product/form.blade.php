<dialog id="modalForm" class="modal">
    <div class="modal-box">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" onclick="closeModal()">✕</button>
        </form>
        <h3 id="form-title" class="font-bold text-lg mb-2 form-title"></h3>
        <hr>
        <form id="form-item" class="flex flex-col gap-2">
            @csrf
            <input type="hidden" id="id" name="id"> 
            <div class="flex items-center mt-4 mb-2">
                <label for="name" class="w-24">Product Name</label>
                <input type="text" placeholder="Name" class="input input-bordered input-primary flex-grow" name="name" id="name">
            </div>
            <div class="flex items-center  mb-2">
                <label for="stock" class="w-24">Stock</label>
                <input type="number" placeholder="Stock" class="input input-bordered input-primary flex-grow" name="stock" id="stock">
            </div>
            <div class="flex items-center  mb-4">
                <label for="price" class="w-24">Price</label>
                <input type="number" placeholder="Price" class="input input-bordered input-primary flex-grow" name="price" id="price">
            </div>
            <button type="submit" class="btn btn-primary" id="submit-button">Simpan</button>
        </form>
    </div>
</dialog>
