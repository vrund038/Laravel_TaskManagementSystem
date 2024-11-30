<div>
    <!-- Navbar -->
   

    <!-- Main Container -->
    <div class="container mt-5">
        <div class="row mb-4">
            <div class="col">
                <h2 class="text-center">Add New Task</h2>
            </div>
        </div>
        @if (session()->has('message'))
            <div class="alert alert-success mt-3">
                {!! session('message') !!}
            </div>
        @endif
        <!-- Form -->
        <form wire:submit.prevent="submit" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" wire:model.defer="title" required>
                @error('title') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" wire:model.defer="description"></textarea>
                @error('description') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="due_date" class="form-label">Due Date</label>
                <input type="date" class="form-control" id="due_date" wire:model.defer="due_date">
                @error('due_date') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Image</label>
                <input type="file" class="form-control" id="image" wire:model="image">
                @error('image') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="completed" wire:model.defer="completed">
                <label class="form-check-label" for="completed">Completed</label>
            </div>

            <button type="submit" class="btn btn-primary">Add Task</button>
        </form>

      
    </div>
</div>


<!-- Bootstrap JS and optional Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>