<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add Task</title>
</head>
<body style="font-family: Chalkboard,serif">
    <h1>Here you go :)</h1>

    <form action="{{ route('tasks.store') }}" method="post">

        @csrf

        <input type="text" name="task_title" placeholder="Title" required> <br><br>
        <input type="text" name="task_description" placeholder="Description(Optional)"> <br><br>
        <input type="text" name="task_status" placeholder="Status" required> <br><br>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <button type="submit">Submit</button>

    </form>

</body>

</html>
