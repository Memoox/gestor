<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Documentos</title>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th>Folio</th>
                <th>Documento</th>
                <th>Asunto</th>
                <th>Dependencia</th>
                <th>Turnado a</th>
                <th>Fecha recepción</th>
                <th>Fecha vencimiento</th>
                <th>Prioridad</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($documentos as $key => $documento)
            <tr>
                <td>{{$documento['folio']}}</td>
                <td>{{$documento['no_documento']}}</td>
                <td>{{$documento['asunto']}}</td>
                <td>{{$documento['dependencia']}}</td>
                <td>{{$documento['dirigido_a']}}</td>
                <td>{{$documento['fecha_emision']}}</td>
                <td>{{$documento['fecha_recepcion']}}</td>
                <td>{{$documento['prioridad']}}</td>
                <td>{{$documento['estatus']}}</td>
            </tr>
                
            @endforeach
        </tbody>
    </table>
</body>
</html>