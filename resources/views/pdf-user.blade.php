<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Daftar Akun</title>
        <meta name="viewport" content="width=device-width,initial-scale=1"> 
        <style>
           table, td, th {
              /*border: 1px solid black;*/
            }

            table {
              border-collapse: collapse;
              width: 100%;
            }

            td {
              /*height: 50px;*/
              vertical-align: top;
            }
  
        </style>
    </head>
    <body>
        <div style="padding-bottom: 50px;">
            <h3 style="text-align: center;">Daftar Akun Smart-Library</h3>
            <br>
            <table class='table table1' border="1">
                <thead style="background-color: #009688; text-align: center;">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Level</th>
                        <th>Password</th>
                        <th>Kontak</th>
                    </tr>
                </thead>
                <tbody>
                    @php 
                        $i=1;
                    @endphp
                    @foreach($data as $r)
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td>{{ $r->name }}</td>
                        <td>{{ $r->email }}</td>
                        <td>{{ $r->level }}</td>
                        <td>{{ '-' }}</td>
                        <td>{{ $r->kontak }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div> 
    </body>
</html>
