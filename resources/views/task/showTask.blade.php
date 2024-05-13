@php use Illuminate\Pagination\LengthAwarePaginator; @endphp
    <!DOCTYPE html>
<html lang="en">

<head>
    <title>Show Tasks</title>
    <style>
        .pagination {
            margin-top: 20px;
            display: flex;
            list-style: none;
            padding-left: 0;
        }

        .page-item {
            margin-right: 5px;
        }

        .page-link {
            padding: 8px 12px;
            background-color: #007bff;
            color: #fff;
            border: 1px solid #007bff;
            border-radius: 5px;
            text-decoration: none;
        }

        .page-link:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .page-item.active .page-link {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .page-item.disabled .page-link {
            background-color: #f2f2f2;
            border-color: #f2f2f2;
            color: #333;
            pointer-events: none;
        }
    </style>
</head>

<body style="font-family: Chalkboard,serif">

<form action="{{ route('tasks.index') }}" method="GET">
    <label> Status
        <select name="status">
            <option>-----</option>
            <option value="complete">complete</option>
            <option value="incomplete">incomplete</option>
        </select>
    </label>

    <label style="padding-left: 30px; padding-right: 30px">Paginate
        <select name="perPage">
            <option>-----</option>
            <option value='3'>3</option>
            <option value='5'>5</option>
            <option value='10'>10</option>
        </select>
    </label>

    <button type="submit">Filter</button>
</form>
<br>
<table style="width:auto; border-collapse: collapse;">
    <thead>
    <tr>
        <th style="padding: 8px; text-align: left; border: 1px solid #ddd; background-color: #f2f2f2;">Title</th>
        <th style="padding: 8px; text-align: left; border: 1px solid #ddd; background-color: #f2f2f2;">Description</th>
        <th style="padding: 8px; text-align: left; border: 1px solid #ddd; background-color: #f2f2f2;">Status</th>
        <th style="padding: 8px; text-align: left; border: 1px solid #ddd; background-color: #f2f2f2;">Created At</th>
    </tr>
    </thead>
    <tbody>
    @foreach($tasks as $task)
        <tr>
            <td style="padding: 8px; text-align: left; border: 1px solid #ddd;">{{ $task->title }}</td>
            <td style="padding: 8px; text-align: left; border: 1px solid #ddd;">{{ $task->description }}</td>
            <td style="padding: 8px; text-align: left; border: 1px solid #ddd;">{{ $task->status }}</td>
            <td style="padding: 8px; text-align: left; border: 1px solid #ddd;">{{ $task->created_at ? $task->created_at->format('Y/m/d H:i:s') : 'N/A' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
<br>

@if ($tasks instanceof LengthAwarePaginator)
    {{ $tasks->appends(['status' => $status, 'perPage' => $perPage])->links() }}
@endif

</body>
</html>
