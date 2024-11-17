<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h1 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 8px 12px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }

        /* Make the page in portrait orientation */
        @page {
            size: A4;
            margin: 20mm;
        }
    </style>
</head>
<body>
    <h1>Data Karyawan</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Bidang</th>
                <th>Role</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($selectedData as $data)
                <tr>
                    <td>{{ $data->id }}</td>
                    <td>{{ $data->name }}</td>
                    <td>{{ $data->email }}</td>
                    <td>{{ $data->phone }}</td>
                    <td>{{ $data->bidang->name ?? 'N/A' }}</td> <!-- Menampilkan nama bidang -->
                    <td>{{ $data->role->name ?? 'N/A' }}</td> <!-- Menampilkan nama role -->
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
