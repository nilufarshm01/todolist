<!DOCTYPE html>
<html lang="en">

<head>
    <title>Update Task</title>
</head>

<body style="font-family: Chalkboard,serif">

<h1>Update here :</h1>

<form method="POST" action="{{ route('tasks.update', ['task' => $task->title]) }}">
    @csrf
    @method('PUT')

    <input type="text" name="title" placeholder="NEW Title"><br><br>
    <input name="description" placeholder="NEW Description"><br><br>
    <input type="text" name="status" placeholder="NEW Status"><br><br>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <button type="submit">Update Task</button>

</form>
</body>
</html>
