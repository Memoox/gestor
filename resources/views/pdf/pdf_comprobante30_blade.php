<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Citas en Línea</title>
</head>
<body>
        <p class="texto-derecha"><img loading="lazy" width="200" src="../public/img/logo.png"></p>

     <br>    

    {{--  <center>  --}}
        <p class="derecho">FOLIO: {{$oficio->folio}}</p>
        <p class="derecho">No. DOCUMENTO: {{$oficio->no_documento}}</p>
        <p class="derecho">FECHA DE EMISION: {{$oficio->fecha_emision}}</p>
        <p class="derecho">FECHA DE RECEPCION: {{$oficio->fecha_recepcion}}</p>
        <p class="derecho">TURNADO A: {{$oficio->departamento ? $oficio->departamento->nombre: ''}}</p>

    <div class="col-md-4 col-12"></div>
    <div class="col-md-4 col-12"></div>
    <table>
        <tr>
            <td class="texto">ASUNTO</td>
        </tr>
        <tr>
            <td class="contenido-td texto-dato">{{$oficio->asunto}}</td>
        </tr>
    </table>

    <br>
    <br>
    <br>
    <br>
    <br>
    {{--  <p class="texto">ASUNTO</p>  --}}
    <table>
        <tr>
            <td class="texto">OBSERVACIONES</td>
        </tr>
        <tr>
            <td class="contenido-td texto-dato">{{$oficio->observaciones_a}}</td>
        </tr>
    </table>
   
</body>
</html>

<style>
     .contenido-td {
        border: 1px solid #D4D7D8;
    }

    .texto-datos {
        padding: 10px;
    }
    .tabla_encabezado{
            background-color: #6a73a0;
            font-weight: bold;
            color: white;
            text-align: center;
    }
    table, td, th {
        font-size: 10px;
    }
    .texto{
        text-align: center;
    }
    .texto-derecha{
        text-align: right;
    }
    .derecho{
        text-align: left;
        font-size: 8px;
        font-weight: bold;
    }
</style>
