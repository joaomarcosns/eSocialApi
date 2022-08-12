<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.1/js/bootstrap.min.js">
    <title>Export Domains</title>
</head>

<body>
    <table>
        <thead style="background-color: yellow">
            <tr>
                <th>Domais</th>
                <th>Tld</th>
                <th>created_et</th>
                <th>update_et</th>
                <th>expiration_data</th>
                <th>register</th>
                <th>Names Servers</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($domains as $domain)
                <tr>
                    <td>{{ $domain->name }}</td>
                    <td>{{ $domain->tld }}</td>
                    <td>{{ $domain->created_at }}</td>
                    <td>{{ $domain->updated_at }}</td>
                    <td>{{ $domain->expiration_date }}</td>
                    <td>{{ $domain->registers->name }}</td>
                    @foreach ($domain->names_servers as $names_server)
                        <td>{{ $names_server->names_server }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
