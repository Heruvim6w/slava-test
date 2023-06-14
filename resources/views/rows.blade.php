<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rows</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div id="rows">
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Date</th>
        </tr>
        </thead>
        <tbody>
        @foreach($rows as $row)
            <tr>
                <td>{{ $row->id }}</td>
                <td>{{ $row->name }}</td>
                <td>{{ $row->date }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
