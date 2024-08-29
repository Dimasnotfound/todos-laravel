<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo App</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Todo List</h1>
        <div class="input-group mb-3">
            <input type="text" id="new-todo" class="form-control" placeholder="Add a new todo">
            <div class="input-group-append">
                <button id="add-todo" class="btn btn-primary">Add</button>
            </div>
        </div>

        <div class="btn-group mb-3" role="group">
            <button id="show-all" class="btn btn-secondary">All</button>
            <button id="show-active" class="btn btn-secondary">Active</button>
            <button id="show-completed" class="btn btn-secondary">Completed</button>
        </div>

        <ul id="todo-list" class="list-group">
            @foreach($todos as $todo)
                <li class="list-group-item d-flex justify-content-between align-items-center {{ $todo->completed ? 'completed' : 'active' }}">
                    <div>
                        <input type="checkbox" class="todo-checkbox mr-2" data-id="{{ $todo->id }}" {{ $todo->completed ? 'checked' : '' }}>
                        <span class="todo-title" data-id="{{ $todo->id }}">{{ $todo->title }}</span>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>

    <script>
        $(document).ready(function() {
            // Tambah todo baru
            $('#add-todo').click(function() {
                const title = $('#new-todo').val();
                if (title) {
                    $.post('/todos', { title: title, _token: '{{ csrf_token() }}' }, function(todo) {
                        $('#todo-list').append(
                            `<li class="list-group-item d-flex justify-content-between align-items-center active">
                                <div>
                                    <input type="checkbox" class="todo-checkbox mr-2" data-id="${todo.id}">
                                    <span class="todo-title" data-id="${todo.id}">${todo.title}</span>
                                </div>
                            </li>`
                        );
                        $('#new-todo').val('');
                    });
                }
            });

            // Mengubah status todo
            $(document).on('change', '.todo-checkbox', function() {
                const id = $(this).data('id');
                $.ajax({
                    url: `/todos/${id}`,
                    type: 'PUT',
                    data: { completed: true, _token: '{{ csrf_token() }}' },
                    success: function(todo) {
                        const li = $(`input[data-id="${todo.id}"]`).closest('li');
                        li.toggleClass('completed active');
                    }
                });
            });

            // Mengambil dan menampilkan todo berdasarkan filter
            function filterTodos(status) {
                $.ajax({
                    url: '/todos/filter',
                    type: 'GET',
                    data: { status: status },
                    success: function(todos) {
                        $('#todo-list').empty();
                        todos.forEach(function(todo) {
                            $('#todo-list').append(
                                `<li class="list-group-item d-flex justify-content-between align-items-center ${todo.completed ? 'completed' : 'active'}">
                                    <div>
                                        <input type="checkbox" class="todo-checkbox mr-2" data-id="${todo.id}" ${todo.completed ? 'checked' : ''}>
                                        <span class="todo-title" data-id="${todo.id}">${todo.title}</span>
                                    </div>
                                </li>`
                            );
                        });
                        // Update URL tanpa refresh
                        history.pushState(null, null, `?status=${status}`);
                    }
                });
            }

            $('.btn-group').on('click', 'button', function() {
                const filter = $(this).attr('id').replace('show-', '');
                filterTodos(filter);
            });

            // Mengedit judul todo
            $(document).on('dblclick', '.todo-title', function() {
                const id = $(this).data('id');
                const titleElement = $(this);
                const currentTitle = titleElement.text();
                const input = $('<input>', {
                    type: 'text',
                    value: currentTitle,
                    class: 'form-control'
                });
                titleElement.replaceWith(input);
                input.focus();

                input.on('blur', function() {
                    const newTitle = $(this).val();
                    $.ajax({
                        url: `/todos/${id}`,
                        type: 'PUT',
                        data: { title: newTitle, _token: '{{ csrf_token() }}' },
                        success: function() {
                            input.replaceWith(`<span class="todo-title" data-id="${id}">${newTitle}</span>`);
                        }
                    });
                });

                input.on('keypress', function(e) {
                    if (e.which === 13) { // Enter key
                        input.blur();
                    }
                });
            });

            // Menangani perubahan status filter saat pengguna menavigasi menggunakan history API
            window.onpopstate = function(event) {
                const query = new URLSearchParams(window.location.search);
                const status = query.get('status') || 'all';
                filterTodos(status);
            };

            // Muat todos berdasarkan status filter saat halaman dimuat
            const query = new URLSearchParams(window.location.search);
            const status = query.get('status') || 'all';
            filterTodos(status);
        });
    </script>
</body>
</html>
