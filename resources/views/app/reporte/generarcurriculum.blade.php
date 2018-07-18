<img src="assets/images/users/avatar-2.jpg"/>
<h1 style="color: blue">{{ $alumno->nombres }} {{ $alumno->apellidopaterno }} {{ $alumno->apellidomaterno }}</h1>
<ul>
	<li>Email: {{ $alumno->email }}</li>
	<li>Fecha de Nacimiento: {{ Date::parse($alumno->fechanacimiento)->format('d/m/y') }}</li>
	<li>Dirección: {{ $alumno->direccion }}</li>
	<li>Teléfono: {{ $alumno->telefono }}</li>
</ul>

<h1 style="color: blue">EXPERIENCIAS LABORALES</h1>

<?php $i=1; ?>

@foreach($explaborales as $explaboral)
	<section>
		<article>
			<h3 style="color:red">{{ $i }}.- RUC:{{ $explaboral->ruc }} / {{ $explaboral->empresa }}</h3>
			<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CARGO: {{ $explaboral->cargo }}</p>
			<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;FECHA: {{ Date::parse($explaboral->fechainicio)->format('d/m/y') }} - {{ Date::parse($explaboral->fechafin)->format('d/m/Y') }}</p>			
		</article>
	</section>
	<?php $i++; ?>
@endforeach	

<h1 style="color: blue">COMPETENCIAS Y HABILIDADES</h1>

<?php $i=1; ?>

@foreach($competencias as $competencia)
	<section>
		<article>
			<h3>{{ $i }}.- {{ $competencia->competencia_nombre }} </h3>		
			<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<?php 
					$calificacion = '';
					for ($i=0; $i < $competencia->calificacion; $i++) { 
						$calificacion .= ' <img src="assets/images/estrella.png"width="10" height="10"/>';
					}
					echo $calificacion;
				?>
			</p>		
		</article>
	</section>
	<?php $i++; ?>
@endforeach	
	