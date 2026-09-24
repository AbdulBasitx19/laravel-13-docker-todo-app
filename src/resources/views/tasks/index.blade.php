<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Docker Laravel Todo App </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-light p-5">
    <div class="container" style="max-width: 600px">
        <h2 class="mb-4 text-center">Docker Laravel TODO List </h2>

        <!-- Add Form -->
        <form id="add-task-form" class="d-flex mb-4">
            <input type="text" id="task-title" class="form-control me-2" placeholder="Enter new task..." required>
            <button type="submit" class="btn btn-primary">Add Task</button>
        </form>

        <!-- Task List Container -->
        <ul class="list-group" id="tasks-list">
            @forelse($tasks as $task)
                <li class="list-group-item d-flex justify-content-between align-items-center" id="task-row-{{ $task->id }}">
                    <span class="task-title {{ $task->is_completed ? 'text-decoration-line-through' : '' }}">
                        {{ $task->title }}
                    </span>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm toggle-btn {{ $task->is_completed ? 'btn-warning' : 'btn-success' }}" data-id="{{ $task->id }}">
                            {{ $task->is_completed ? 'Undo' : 'Done' }}
                        </button>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $task->id }}">Delete</button>
                    </div>
                </li>
            @empty
                <li class="list-group-item text-center text-muted no-task-msg">No tasks available.</li>
            @endforelse
        </ul>
    </div>  

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        
        $('#add-task-form').on('submit', function(e) {
            e.preventDefault();
            let title = $('#task-title').val();

            $.ajax({
                url: "{{ route('tasks.store') }}",
                type: "POST",
                data: { title: title },
                success: function(response) {
                    if(response.success) {
                        $('.no-task-msg').remove();
                        let newTaskHtml = `
                            <li class="list-group-item d-flex justify-content-between align-items-center" id="task-row-${response.task.id}">
                                <span class="task-title">${response.task.title}</span>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-success toggle-btn" data-id="${response.task.id}">Done</button>
                                    <button class="btn btn-sm btn-danger delete-btn" data-id="${response.task.id}">Delete</button>
                                </div>
                            </li>`;
                        $('#tasks-list').prepend(newTaskHtml);
                        $('#task-title').val('');
                    }
                }
            });
        });

        
        $(document).on('click', '.toggle-btn', function() {
            let taskId = $(this).data('id');
            let btn = $(this);
            let titleSpan = $(`#task-row-${taskId} .task-title`);

            $.ajax({
                url: `/tasks/${taskId}`,
                type: "PUT",
                success: function(response) {
                    if(response.is_completed) {
                        titleSpan.addClass('text-decoration-line-through');
                        btn.removeClass('btn-success').addClass('btn-warning').text('Undo');
                    } else {
                        titleSpan.removeClass('text-decoration-line-through');
                        btn.removeClass('btn-warning').addClass('btn-success').text('Done');
                    }
                }
            });
        });

        
        $(document).on('click', '.delete-btn', function() {
            let taskId = $(this).data('id');

            $.ajax({
                url: `/tasks/${taskId}`,
                type: "DELETE",
                success: function(response) {
                    $(`#task-row-${taskId}`).remove();
                    if($('#tasks-list li').length === 0) {
                        $('#tasks-list').html('<li class="list-group-item text-center text-muted no-task-msg">No tasks available.</li>');
                    }
                }
            });
        });
    </script>
</body>
</html>