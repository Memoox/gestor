<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{--  <meta http-equiv="X-UA-Compatible" content="ie=edge">  --}}
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <title>Comprobante</title>
</head>
<body>
        
    <table>
            
        <tr>
            
            <td colspan="2"></td>
            <td colspan="1"><img loading="lazy" width="150"  src="../public/img/logo_.jpeg" alt="Logo Poder Judicial del Estado de Puebla"></td>
        </tr>
        {{--  <tr><td></td></tr>  --}}
        <tr>
           <td colspan="1"><span class="derecho">FOLIO:</span> {{$oficio->folio}}</td>
            <td colspan="2"><span class="derecho">No. DOCUMENTO:</span> {{$oficio->no_documento}}</td>
        </tr>
        <tr>
            <td colspan="1"><span class="derecho">FECHA EMISION:</span> {{$oficio->fecha_emision}}</td>
            <td colspan="2" ><span class="derecho">FECHA OFICIALIA:</span> {{$oficio->fecha_recepcion}} {{$oficio->hora}}</td>
            {{--  <td colspan="1"><span class="derecho">FECHA RECEPCION AREA:</span> {{$oficio->fecha_recepcion_area}}</td>  --}}
            {{--  <td colspan="2">{{$oficio->fecha_recepcion}} {{$oficio->hora}}</td>  --}}
        </tr>
        <tr><td></td></tr>
        <tr>
            <td colspan="1"><span class="derecho">REMITENTE:</span></td>
            <td colspan="2">{{$oficio->emisor}}</td>
        </tr>
        {{--  <tr><td></td></tr>  --}}
        <tr>
            <td colspan="1"><span class="derecho">CARGO:</span></td>
            <td colspan="2">{{$oficio->cargo}}</td>
        </tr>
        {{--  <tr><td></td></tr>  --}}
        <tr>
            <td colspan="1"><span class="derecho">AREA REMITENTE:</span></td>
            <td colspan="2">{{$oficio->dependencia}}</td>
        </tr>
        <tr><td></td></tr>
        <tr>
            <td colspan="1"><span class="derecho">DESTINATARIO:</span></td>
            <td colspan="2">{{$oficio->dirigido_a}}</td>
        </tr>
        <tr>
            <td colspan="1"><span class="derecho">CARGO:</span></td>
            <td colspan="2">{{$oficio->cargo_a}}</td>
        </tr>
        <tr>
            <td colspan="1"><span class="derecho">AREA DE TURNADO:</span></td>
            <td colspan="2">{{$oficio->departamento ? $oficio->departamento->nombre : ''}}</td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td colspan="1"><span class="derecho">MEDIO DE RECEPCION:</span></td>
            <td colspan="2">{{$oficio->medio}}</td>
        </tr>
        <tr><td></td></tr>
        <tr>
            <td colspan="3" class="texto">ASUNTO</td>
        </tr>
        <tr>
            <td colspan="3" rowspan="4" class="contenido-td texto-datos justificado">{{$oficio->asunto}}</td>
        </tr>
        <tr><td></td></tr>
        <tr><td></td></tr>
        <tr><td></td></tr>
        <tr><td></td></tr>
        <tr>
            <td colspan="3" class="texto">OBSERVACIONES</td>
        </tr>
        <tr>
            <td colspan="2" rowspan="6" class="contenido-td texto-datos justificado">{{$oficio->observaciones_a}}</td>
            <td colspan="1" rowspan="6" class="contenido-td texto-datos texto">Sello de recibido</td>
        </tr>
        <tr><td></td></tr>
        <tr><td></td></tr>
        <tr><td></td></tr>
        <tr><td></td></tr>
        <tr><td></td></tr>
                
        {{--  <tr>
            
            <td colspan="2"></td>
            <td colspan="1"><img loading="lazy" width="150"  src="../public/img/logo_.jpeg" alt="Logo Poder Judicial del Estado de Puebla"></td>
        </tr>
        <tr><td></td></tr>
        <tr>
           <td colspan="1"><span class="derecho">FOLIO:</span> {{$oficio->folio}}</td>
            <td colspan="2"><span class="derecho">No. DOCUMENTO:</span> {{$oficio->no_documento}}</td>
        </tr>
        <tr>
            <td colspan="1"><span class="derecho">FECHA EMISION:</span> {{$oficio->fecha_emision}}</td>
            <td colspan="2" ><span class="derecho">FECHA OFICIALIA:</span> {{$oficio->fecha_recepcion}} {{$oficio->hora}}</td>
            
        </tr>
        <tr><td></td></tr>
        <tr>
            <td colspan="1"><span class="derecho">REMITENTE:</span></td>
            <td colspan="2">{{$oficio->emisor}}</td>
        </tr>
        
        <tr>
            <td colspan="1"><span class="derecho">CARGO:</span></td>
            <td colspan="2">{{$oficio->cargo}}</td>
        </tr>
        
        <tr>
            <td colspan="1"><span class="derecho">AREA REMITENTE:</span></td>
            <td colspan="2">{{$oficio->dependencia}}</td>
        </tr>
        <tr><td></td></tr>
        <tr>
            <td colspan="1"><span class="derecho">DESTINATARIO:</span></td>
            <td colspan="2">{{$oficio->dirigido_a}}</td>
        </tr>
        <tr>
            <td colspan="1"><span class="derecho">CARGO:</span></td>
            <td colspan="2">{{$oficio->cargo_a}}</td>
        </tr>
        <tr>
            <td colspan="1"><span class="derecho">AREA DESTINATARIO:</span></td>
            <td colspan="2">{{$oficio->departamento ? $oficio->departamento->nombre : ''}}</td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td colspan="1"><span class="derecho">MEDIO DE RECEPCION:</span></td>
            <td colspan="2">{{$oficio->medio}}</td>
        </tr>
        <tr><td></td></tr>
        <tr>
            <td colspan="3" class="texto">ASUNTO</td>
        </tr>
        <tr>
            <td colspan="3" rowspan="4" class="contenido-td texto-datos justificado">{{$oficio->asunto}}</td>
        </tr>
        <tr><td></td></tr>
        <tr><td></td></tr>
        <tr><td></td></tr>
        <tr><td></td></tr>
        <tr>
            <td colspan="3" class="texto">OBSERVACIONES</td>
        </tr>
        <tr>
            <td colspan="2" rowspan="6" class="contenido-td texto-datos justificado">{{$oficio->observaciones_a}}</td>
            <td colspan="1" rowspan="6" class="contenido-td texto-datos texto">Sello de recibido</td>
        </tr>
        <tr><td></td></tr>
        <tr><td></td></tr>
        <tr><td></td></tr>
        <tr><td></td></tr>
        <tr><td></td></tr>
        <tr><td></td></tr>
        <tr><td></td></tr>  --}}
       
        

    </table>

        {{--  /////////////7////////////////////////////////////////////////////////////////////////////////////////////////////////////////  --}}
        
      
{{--         
            <p class="derecho">FOLIO: {{$oficio->folio}}</p>
            <p class="derecho">No. DOCUMENTO: {{$oficio->no_documento}}</p>
            <p class="derecho">FECHA DE EMISION: {{$oficio->fecha_emision}}</p>
            <p class="derecho">FECHA DE RECEPCION: {{$oficio->fecha_recepcion}}</p>
            <p class="derecho">TURNADO A: {{$oficio->departamento ? $oficio->departamento->nombre : ''}}</p>  --}}

        {{--  <div class="col-md-4 col-12"></div>  --}}
       
        {{--  <table>
            
            <tr>
                <td colspan="2"></td>
                <td colspan="1"><img loading="lazy" width="150"  src="../public/img/logo.png" alt="Logo Poder Judicial del Estado de Puebla"></td>
            </tr>
            <tr><td></td></tr>
            <tr><td></td></tr>
            <tr>
                <td colspan="1" class="derecho">FOLIO:</td>
                <td colspan="2">{{$oficio->folio}}</td>
            </tr>
            <tr>
                <td colspan="1" class="derecho">FECHA:</td>
                <td colspan="2">{{$oficio->fecha_recepcion}}</td>
            </tr>
            <tr>
                <td colspan="1" class="derecho">REMITENTE:</td>
                <td colspan="2">OFICIALIA DEL CONSEJO DE LA JUDICATURA</td>
            </tr>
            <tr>
                <td colspan="1" class="derecho">DESTINATARIO:</td>
                <td colspan="2">{{$oficio->departamento ? $oficio->departamento->nombre : ''}}</td>
            </tr>
            <tr><td></td></tr>
            <tr><td></td></tr>
            <tr>
                <td colspan="3" class="texto">ASUNTO</td>
            </tr>
            <tr>
                <td colspan="3" rowspan="3" class="contenido-td texto-datos">{{$oficio->asunto}}</td>
            </tr>
            <tr><td></td></tr>
            <tr><td></td></tr>
            <tr><td></td></tr>
            <tr>
                <td colspan="3" class="texto">OBSERVACIONES</td>
            </tr>
            <tr>
                <td colspan="2" rowspan="6" class="contenido-td texto-datos">{{$oficio->observaciones_a}}</td>
                <td colspan="1" rowspan="6" class="contenido-td texto-datos texto">Sello de recibido</td>
            </tr>
            <tr><td></td></tr>
            <tr><td></td></tr>
            <tr><td></td></tr>
            <tr><td></td></tr>
            <tr><td></td></tr>
        </table>  --}}
   
</body>
</html>

<style>
     .contenido-td {
        border: 1px solid #302f2f;
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
        font-size: 8px;
    }
    .texto{
        text-align: center;
    }
    .texto-derecha{
        text-align: right;
    }
    
    .derecho{
       
        font-size: 8px;
        font-weight: bold;
    }
    .ancho{
        height: 300px;
    }
    .justificado{
        text-align: justify;
    }
</style>