<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            Add Boxes
        </h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Fill the form below to add a new box.
        </p>
    </header>
    
    <!-- Button to open the modal -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBoxModal">
        Add New Box
    </button>

    <!-- Modal -->
    <div class="modal fade" id="addBoxModal" tabindex="-1" aria-labelledby="addBoxModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addBoxModalLabel">Add New Box</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addBoxForm" action="{{ route('boxes.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="boxName" class="form-label">Box Name</label>
                            <input type="text" class="form-control" id="boxName" name="box_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="boxImage" class="form-label">Box Image</label>
                            <input type="file" class="form-control" id="boxImage" name="box_img" required>
                        </div>
                        <div class="mb-3">
                            <label for="boxCost" class="form-label">Box Cost (coins)</label>
                            <input type="number" class="form-control" id="boxCost" name="cost" required>
                        </div>
                        <div class="mb-3">
                            <label for="isDaily" class="form-check-label">Is Daily?</label>
                            <input type="checkbox" class="form-check-input" id="isDaily" name="daily">
                        </div>
                        <div id="weaponsSection" class="mb-3">
                            <label for="weapons" class="form-label">Select Weapons</label>
                            <select multiple class="form-control" id="weapons" name="weapons[]">
                                @foreach ($weapons as $weapon)
                                    <option value="{{ $weapon->id }}">{{ $weapon->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Box</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>