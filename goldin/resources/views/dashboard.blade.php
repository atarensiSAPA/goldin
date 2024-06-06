<x-app-layout>

    <div class="container">
        <div class="row text-white">
            @forelse ($clothes as $c)
                <div class="col-md-3 mt-4 d-flex">
                    <div class="card bg-transparent text-white d-flex flex-column align-items-center" id="card-{{ $c->name }}">
                        <div class="clothesDiv card-body position-relative rounded-lg dark:bg-gray-800 d-flex flex-column align-items-center borderClothes" data-bs-toggle="modal" data-bs-target="#clothesModal">                            <div class="d-flex justify-content-center align-items-center" style="flex-grow: 1;">
                                <img src="{{ asset('images/clothes/' . $c->clothes_img) }}" alt="" class="img-fluid rounded-0">
                            </div>
                            <div class="overlay position-absolute top-0 start-0 pt-2 pl-2 radius-0 h-auto d-flex align-items-center">
                                <b class="fs-5 fw-bold">{{ $c->price }}</b>
                                <img src="{{ asset('images/user_coin.png') }}" alt="coin" width="30" height="30" class="ms-2">
                            </div>
                            <div class="text-center text-white mt-auto">
                                <b>{{ $c->name }}</b>
                            </div>
                        </div>
                        <!-- Add to cart button -->
                        <button class="btn btn-primary add-to-cart-button position-absolute top-0 end-0 me-3 mt-1 displayNone" id="add-to-cart-{{ $c->id }}">
                            🛒
                        </button>
                    </div>
                </div>
            @empty
                <p>No clothes found.</p>
            @endforelse
        </div>
    </div>

</x-app-layout>

<!-- Modal -->
<div class="modal fade" id="clothesModal" tabindex="-1" aria-labelledby="clothesModalLabel" aria-hidden="true">
    <div class="modal-dialog text-light">
      <div class="modal-content">
        <div class="modal-header dark:bg-gray-800">
          <h5 class="modal-title" id="clothesModalLabel">Clothes Description</h5>
        </div>
        <div class="modal-body bg-gray-100 dark:bg-gray-900" id="clothesDescription">
          <!-- Clothes description will be inserted here -->
        </div>
        <div class="modal-footer modal-body bg-gray-100 dark:bg-gray-900">
            <x-secondary-button x-on:click="$dispatch('close')" class="bg-gray-700 text-black" data-dismiss="modal">{{ __('Cancel') }}</x-secondary-button>
        </div>
      </div>
    </div>
</div>