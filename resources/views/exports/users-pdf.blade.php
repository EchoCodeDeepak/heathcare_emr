<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            color: #333;
        }

        .header p {
            margin: 5px 0;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th {
            background-color: #f2f2f2;
            color: #333;
            font-weight: bold;
            text-align: left;
            padding: 8px;
            border: 1px solid #ddd;
        }

        td {
            padding: 8px;
            border: 1px solid #ddd;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .badge {
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
        }

        .badge-admin {
            background-color: #dc3545;
            color: white;
        }

        .badge-user {
            background-color: #28a745;
            color: white;
        }

        .badge-manager {
            background-color: #007bff;
            color: white;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
            color: #666;
            font-size: 10px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <p>Generated on: {{ date('Y-m-d H:i:s') }}</p>
        <p>Total Users: {{ $users->count() }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Created At</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    @php
                    $badgeClass = '';
                    switch($user->role_id) {
                    case 1: $badgeClass = 'badge-admin'; break;
                    case 2: $badgeClass = 'badge-user'; break;
                    case 3: $badgeClass = 'badge-manager'; break;
                    default: $badgeClass = 'badge-secondary';
                    }
                    @endphp
                    <span class="badge {{ $badgeClass }}">{{ $user->role->name }}</span>
                </td>
                <td>{{ $user->created_at->format('Y-m-d') }}</td>
                <td>{{ $user->deleted_at ? 'Inactive' : 'Active' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Page 1 of 1
    </div>
</body>

</html>