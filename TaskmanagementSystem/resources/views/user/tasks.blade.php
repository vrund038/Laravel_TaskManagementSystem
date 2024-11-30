<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tasks</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>  thead th:first-child {
            border-top-left-radius: 0.5rem; /* Top-left corner */
        }

        thead th:last-child {
            border-top-right-radius: 0.5rem; /* Top-right corner */
        }</style>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #343a40;">
    <div class="container-fluid">
            <a class="navbar-brand" href="#">Task Management</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Welcome, {{ $LoggedUserInfo->name }}</a>
                    </li>
                    <li class="nav-item">
                        <form action="{{ route('user.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger nav-link" style="border:none; background:none;">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="container mt-5">
    <div class="row mb-4">
          
    <!-- Adjusted column for button alignment -->
    <div class="col text-end mb-4">
        <a href="{{ route('user.addtasks') }}" class="btn btn-warning">Add Task</a>
    </div>
</div>


        <!-- Filters Section -->
        <div class="row mb-4">
            <div class="col-md-4">
                <label for="filterStatus" class="form-label">Filter by Status</label>
                <select class="form-select" id="filterStatus" onchange="filterTasks()">
                    <option value="all">All</option>
                    <option value="completed">Completed  </option>
                    <option value="pending">Pending  </option>
                </select>
            </div>

            <div class="col-md-4">
                <label for="filterDueDate" class="form-label">Filter by Due Date</label>
                <select class="form-select" id="filterDueDate" onchange="filterTasks()">
                    <option value="">All Dates</option>
                    <option value="today">Today</option>
                    <option value="tomorrow">Tomorrow</option>
                    <option value="next7days">Next 7 Days</option>
                </select>
            </div>

            <div class="col-md-4">
                <label for="searchTask" class="form-label">Search by Title</label>
                <input type="text" class="form-control" id="searchTask" onkeyup="filterTasks()" placeholder="Search tasks...">
            </div>
        </div>

        <!-- Tasks Table -->
        <div class="row">
            <div class="col-md-12">
            <table class="table table-bordered table-striped" id="tasksTable">
            <thead  rounded style="background-color: #343a40; border-radius: 0.5rem; color:white">
            <tr>
            <th scope="col">Image</th>
            <th scope="col">Title</th>
            <th scope="col">Description</th>
            <th scope="col">Due Date</th>
            <th scope="col">Status</th>
            <th scope="col">Actions</th>
        </tr>
    </thead>
    <tbody id="tasksBody">
        @if($tasks && $tasks->count())
            @foreach($tasks as $task)
                <tr>
                    <td>
                        @if($task->image)
                            <img src="{{ asset('storage/' . $task->image) }}" alt="{{ $task->title }}" class="img-thumbnail" style="width: 100px; height: auto;">
                        @else
                            <span>No Image</span>
                        @endif
                    </td>
                    <td>{{ $task->title }}</td>
                    <td>{{ $task->description }}</td>
                    <td>{{ \Carbon\Carbon::parse($task->due_date)->format('d M Y') }}</td>
                    <td class="task-status">{{ $task->completed ? 'Completed  ' : 'Pending  ' }}</td>
                    <td>
                    <a href="{{ route('tasks.show', ['id' => $task->id]) }}" class="btn btn-sm btn-info">View</a>
                    <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-sm btn-warning">Edit</a>
    
    <!-- Delete Button -->
    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display:inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
    </form>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="6" class="text-center">No tasks found</td>
            </tr>
        @endif
    </tbody>
</table>

            </div>
        </div>
    </div>

    <!-- Bootstrap JS and optional Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Filtering and Searching Script -->
    <script>
        function filterTasks() {
            const statusFilter = document.getElementById('filterStatus').value.toLowerCase();
            const dueDateFilter = document.getElementById('filterDueDate').value.toLowerCase();
            const searchFilter = document.getElementById('searchTask').value.toLowerCase();
            const rows = document.querySelectorAll('#tasksBody tr');

            rows.forEach(row => {
                const status = row.querySelector('.task-status').innerText.toLowerCase();
                const dueDate = row.cells[3].innerText.toLowerCase();
                const title = row.cells[1].innerText.toLowerCase();

                let statusMatch = (statusFilter === 'all' || status.includes(statusFilter));
                let dateMatch = filterDueDate(dueDate, dueDateFilter);
                let searchMatch = (title.includes(searchFilter));

                if (statusMatch && dateMatch && searchMatch) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        function filterDueDate(dueDate, filter) {
            const today = new Date();
            const taskDate = new Date(dueDate);

            if (filter === 'today') {
                return today.toDateString() === taskDate.toDateString();
            } else if (filter === 'tomorrow') {
                let tomorrow = new Date(today);
                tomorrow.setDate(today.getDate() + 1);
                return tomorrow.toDateString() === taskDate.toDateString();
            } else if (filter === 'next7days') {
                let nextWeek = new Date(today);
                nextWeek.setDate(today.getDate() + 7);
                return taskDate >= today && taskDate <= nextWeek;
            }
            return true; // Show all if no filter is selected
        }
    </script>
</body>
</html>