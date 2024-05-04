<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add Task</title>
</head>
<body style="font-family: Chalkboard,serif">
    <h1>Here you go :)</h1>

    <form action="{{ route('tasks.store') }}" method="post">

        @csrf

        <input type="text" name="TaskName" placeholder="Task Title" required> <br><br>
        <input type="text" name="TaskDesc" placeholder="Task Description"> <br><br>
        <input type="text" name="TaskStatus" placeholder="Task Status" required> <br><br>

        <button type="submit">Submit</button>

    </form>

</body>

</html>
