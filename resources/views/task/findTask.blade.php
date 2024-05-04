<!DOCTYPE html>
<html lang="en">

<head>
    <title>Update Task</title>
</head>

<body style="font-family: Chalkboard,serif">

<h1>You searched for ( {{ $task->Title }} ) ?</h1>

<ul>
    <li>Title: {{ $task->Title }}</li>
    <li>Description: {{ $task->Description }}</li>
    <li>Status: {{ $task->Status }}</li>
</ul>
</body>
</html>
