<style>
	table{
        border-collapse: collapse;
    }
    td{
        font-size: 10px;
    }
    h1{
        font-size: 21px;
        text-align:center;
        font-weight: bold;
    }
    .tabla2 {
        margin-bottom: 10px;
    }

    .tabla3 td{
        border: 0.9px solid #000;
        text-align : left;;
    }
    .emisor{
        color: red;
    }
    .linea{
        border-bottom: 1px dotted #000;
    }
    .border{
        border: 1px solid #000;
    }
    .fondo{
        background-color: #dfdfdf;
    }
    .fisico{
        color: #fff;
    }
    .fisico td{
        color: #fff;
    }
    .fisico .border{
        border: 1px solid #fff;
    }
    .fisico .tabla3 td{
        border: 1px solid #fff;
    }
    .fisico .linea{
        border-bottom: 1px dotted #fff;
    }
</style>
        <h1>REPORTE DE INSCRITOS</h1>
		<table width="100%" class="tabla2">
            <tr>
                <td width="8%" style="font-weight: bold ">OFERTA:</td>
                <td width="92%" ><span class="text">{{ $oferta->nombre }}</span></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td width="8%" style="font-weight: bold">DESDE:</td>
                <td width="15%"><span class="text">{{ Date::parse($oferta->fechai)->format('d/m/y') }}</span></td>
                <td width="8%" style="font-weight: bold">HASTA:</td>
                <td width="15%"><span class="text">{{ Date::parse($oferta->fechaf)->format('d/m/y') }}</span></td>
                <td width="59%"></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
        </table>

		<table width="100%" class="tabla3">
            <tr>
                <td width="3%" align="center" class="fondo"><strong>#</strong></td>
                <td width="8%" align="center" class="fondo"><strong>FACULTAD</strong></td>
                <td width="12%" align="center" class="fondo"><strong>ESCUELA</strong></td>
                <td width="14%" align="center" class="fondo"><strong>ESPECIALIDAD</strong></td>
                <td width="6%" align="center" class="fondo"><strong>CODIGO</strong></td>
				<td width="30%" align="center" class="fondo"><strong>ALUMNO</strong></td>
				<td width="9%" align="center" class="fondo"><strong>TELEFONO</strong></td>
				<td width="18%" align="center" class="fondo"><strong>EMAIL</strong></td>
            </tr>
            @foreach($lista as $value )
            <tr>
                <td width="3%" align="center"><span class="text">{{ $loop->iteration }}</span></td>
                <td width="8%" align="center"><span class="text">{{ $value->nombre_facultad or  '-' }}</span></td>
                <td width="12%" align="left"><span class="text">{{ $value->nombre_escuela or  '-' }}</span></td>
                <td width="14%" align="left"><span class="text">{{ $value->nombre_especialidad or  '-' }}</span></td>
                <td width="6%" align="center"><span class="text">{{ $value->alumno_codigo }}</span></td>
				<td width="30%" align="left"><span class="text">{{ $value->alumno_nombres.' '.$value->alumno_apellidopaterno.' '.$value->alumno_apellidomaterno }}</span></td>
				<td width="9%" align="center"><span class="text">{{ $value->alumno_telefono or  '-' }}</span></td>
				<td width="18%" align="left"><span class="text">{{ $value->alumno_email or  '-' }}</span></td>
            </tr>
            @endforeach
            
        </table>



