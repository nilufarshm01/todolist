<!DOCTYPE html>
<html lang="en">

<head>
    <title>Update Task</title>
</head>

<body style="font-family: Chalkboard,serif">

<h1>You searched for ( {{ $task->title }} ) ?</h1>

<ul>
    <li>Title: {{ $task->title }}</li>
    <li>Description: {{ $task->description }}</li>
    <li>Status: {{ $task->status }}</li>
</ul>
</body>
</html>
