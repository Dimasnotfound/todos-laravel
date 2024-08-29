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

        <ul id="todo-list" class="list-group">
            @foreach($todos as $todo)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <input type="checkbox" class="todo-checkbox mr-2" data-id="{{ $todo->id }}" {{ $todo->completed ? 'checked' : '' }}>
                        {{ $todo->title }}
                    </div>
                </li>
            @endforeach
        </ul>
    </div>

    <script>
        $(document).ready(function() {
            $('#add-todo').click(function() {
                const title = $('#new-todo').val();
                if (title) {
                    $.post('/todos', {title: title, _token: '{{ csrf_token() }}'}, function(todo) {
                        $('#todo-list').append(
                            `<li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <input type="checkbox" class="todo-checkbox mr-2" data-id="${todo.id}">
                                    ${todo.title}
                                </div>
                            </li>`
                        );
                        $('#new-todo').val('');
                    });
                }
            });

            $(document).on('change', '.todo-checkbox', function() {
                const id = $(this).data('id');
                $.ajax({
                    url: `/todos/${id}`,
                    type: 'PUT',
                    data: {_token: '{{ csrf_token() }}'},
                    success: function(todo) {
                        console.log('Todo updated:', todo);
                    }
                });
            });
        });
    </script>
</body>
</html>
