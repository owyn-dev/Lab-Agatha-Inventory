<div x-data="{ showModal: false, selectedProduct: {} }">
  <div class="mb-4">
    <input class="form-control" type="text" wire:model.live.debounce.500ms="search" placeholder="Search products" />
  </div>

  <div class="table-responsive text-nowrap">
    <table class="table table-striped table-hover">
      <thead class="table m-0 p-0">
        <tr>
          <th scope="col">
            <a href="#" wire:click.prevent="sortBy('code')">
              Code {!! $sortField === 'code' ? ($sortDirection === 'asc' ? '↑' : '↓') : '' !!}
            </a>
          </th>
          <th scope="col">
            <a href="#" wire:click.prevent="sortBy('name')">
              Name {!! $sortField === 'name' ? ($sortDirection === 'asc' ? '↑' : '↓') : '' !!}
            </a>
          </th>
          <th scope="col">
            <a href="#" wire:click.prevent="sortBy('variant')">
              Variant {!! $sortField === 'variant' ? ($sortDirection === 'asc' ? '↑' : '↓') : '' !!}
            </a>
          </th>
          <th scope="col">
            <a href="#" wire:click.prevent="sortBy('total_current_stock')">
              Inventory Stock {!! $sortField === 'total_current_stock' ? ($sortDirection === 'asc' ? '↑' : '↓') : '' !!}
            </a>
          </th>
          <th scope="col">Action</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($this->products as $product)
          <tr>
            <td>{{ $product->code }}</td>
            <td>{{ $product->name }}</td>
            <td>{{ $product->variant->label() }}</td>
            <td>{{ $product->total_current_stock }}</td>
            <td>
              <a class="btn icon btn-sm btn-primary" href="#" wire:loading.remove wire:target="save" @click="selectedProduct = { id: '{{ $product->id }}', code: '{{ $product->code }}', name: '{{ $product->name }}', variant: '{{ $product->variant->label() }}' }; setTimeout(() => showModal = true, 400);">
                <i class="bi bi-plus-circle"></i>
              </a>
              <a class="btn icon btn-sm btn-primary" href="#" wire:loading wire:target="save" disabled>
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
              </a>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <div class="mt-4">
    {{ $this->products->links() }}
  </div>

  <div class="modal fade" id="modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" wire:ignore x-show="showModal" x-transition x-init="let modal = new bootstrap.Modal($el);
  $watch('showModal', value => value ? modal.show() : modal.hide());
  $el.addEventListener('hidden.bs.modal', () => {
      showModal = false;
      selectedProduct = {};
      $wire.set('quantity_requested', '');
  });">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" x-text="`${selectedProduct.code} - ${selectedProduct.name} - ${selectedProduct.variant}`"></h1>
          <button class="btn-close" type="button" @click="let modal = bootstrap.Modal.getInstance(document.getElementById('modal')); modal.hide(); setTimeout(() => showModal = false, 400);"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Quantity Requested</label>
            <input class="form-control" type="text" wire:model="quantity_requested" min="1">
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" @click="let modal = bootstrap.Modal.getInstance(document.getElementById('modal')); modal.hide(); setTimeout(() => showModal = false, 400);">Close</button>
          <button class="btn btn-primary" type="button" x-data="{ isLoading: false }" x-on:click.debounce="
          if (selectedProduct) {
              isLoading = true;
              await $wire.addProduct(selectedProduct.id, $wire.quantity_requested);
              isLoading = false;
              let modal = bootstrap.Modal.getInstance(document.getElementById('modal'));
              modal.hide();
              setTimeout(() => showModal = false, 400);
          }" x-bind:disabled="isLoading">
            <span x-show="!isLoading">Add Product</span>
            <span x-show="isLoading">
              <i class="fas fa-spinner fa-spin"></i> Loading...
            </span>
          </button>
        </div>
      </div>
    </div>
  </div>
</div>
