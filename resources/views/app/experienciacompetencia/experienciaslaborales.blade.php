<style>
        fieldset 
       {
           border: 1px solid #ddd !important;
           margin: 0;
           xmin-width: 0;
           padding: 10px;       
           position: relative;
           border-radius:4px;
           background-color:#f5f5f5;
           padding-left:10px!important;
       }	
       
           legend
           {
               font-size:14px;
               font-weight:bold;
               margin-bottom: 0px; 
               width: 35%; 
               border: 1px solid #ddd;
               border-radius: 4px; 
               padding: 5px 5px 5px 10px; 
               background-color: #ffffff;
           }
   </style>
   <style>
        #form {
      width: 250px;
      margin: 0 auto;
      height: 50px;
    }
    
    #form p {
      text-align: center;
    }
    
    #form label {
      font-size: 20px;
    }
    
    input[type="radio"] {
      display: none;
    }
    
    label {
      color: grey;
    }
    
    .clasificacion {
      direction: rtl;
      unicode-bidi: bidi-override;
    }
    
    label:hover,
    label:hover ~ label {
      color: orange;
    }
    
    input[type="radio"]:checked ~ label {
      color: orange;
    }
    
</style>
<div class="row">
        <div class="col-xs-12">
            <div id="myCarousel" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators" style="display: none">
                    <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                    <li data-target="#myCarousel" data-slide-to="1"></li>
                    <li data-target="#myCarousel" data-slide-to="2"></li>
                </ol>
                <div class="carousel-inner">
                    <div class="item active" id="item1">
                        <!--div class="text-right">
                            <button class='btn btn-xs btn-info' id="btnabrirNuevo"><i class='glyphicon glyphicon-plus'></i> Agregar</button>
                        </div-->
                        <fieldset class="col-12" style="margin-top: 0px;">
                                <legend style="width: 150px">EXP. LABORALES</legend>
                                <div class="panel panel-default" style="margin-bottom: 10px;">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-xs-5">
                                                <div class="form-group col-xs-12" >
                                                    <label for="fechai" class="control-label col-xs-3" style="top: 10px">DESDE:</label>
                                                    <div class="col-xs-9">
                                                        <input type="date" name="fechai" id="fechai" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-5">
                                                    <div class="form-group col-xs-12">
                                                        <label for="fechaf" class="control-label col-xs-3" style="top: 10px">HASTA:</label>
                                                        <div class="col-xs-9">
                                                            <input type="date" name="fechaf" id="fechaf" class="form-control">
                                                        </div>
                                                    </div>
                                            </div>
                                            <div class="col-xs-2">
                                                <button class='btn btn-xs btn-info' id="btnBuscarExperiencias" style="margin-top: 15px;"><i class='glyphicon glyphicon-search'></i> BUSCAR</button>
                                            </div>
                                        </div>
                                        <table id="example1" class="table table-bordered table-striped table-condensed table-hover table-sm">
                                            <thead>
                                                <tr>
                                                    <th style="width: 4%">#</th>
                                                    <th style="width: 8%">RUC</th>
                                                    <th>EMPRESA</th>
                                                    <th>CARGO</th>
						    <th>CONTACTO</th>
                                                    <th style="width: 4%">COMP</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tbody_experiencias">
                                                                    
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                        </fieldset>
                        <?php
                            echo "<input type='hidden' name='idalumno' id='idalumno' value='".$id."'>";
                        ?>
                        <input type="hidden" id="idexperiencia_laboral" value=""> 
                    </div>
                    <div class="item" id="item2">
                            
                            <fieldset class="col-12" style="margin-top: 0px;">
                                    <legend style="width: 170px">MIS COMPETENCIAS</legend>
                                    <div class="panel panel-default" style="margin-bottom: 10px;">
                                        <div class="panel-body">
                                                <div class="text-right" style="margin-bottom: 5px;">
                                                        <button class='btn btn-xs btn-default' id="btnatras"><i class='glyphicon glyphicon-chevron-left'></i> ATRÁS</button>
                                                    </div>
                                                <table id="example1" class="table table-bordered table-striped table-condensed table-hover table-sm">
                                                        <thead>
                                                            <tr>
                                                                <th style="width: 4%">#</th>
                                                                <th>COMPETENCIA</th>
                                                                <th style="width: 30%">CALIFICACION (MAX 5 &#9733;)</th>
                                                                <th style="width: 10%">CALIFICAR</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="tbody_competencias">
                                                                                
                                                        </tbody>
                                                </table>
                                        </div>
                                    </div>
                            </fieldset>
                    </div>
                    <div class="item" id="item3">
                           
                            <fieldset class="col-12" style="margin-top: 0px;">
                                    <legend style="width: 240px">CALIFICACION DE COMPETENCIA</legend>
                                    <div class="panel panel-default" style="margin-bottom: 10px;">
                                        <div class="panel-body">
                                                <div class="text-right col-xs-12">
                                                        <button class='btn btn-xs btn-default' id="btnatras2"><i class='glyphicon glyphicon-chevron-left'></i> ATRÁS</button>
                                                </div>
                                            <div class="form-group col-xs-12">
                                                <label class="control-label col-xs-3">CALIFICACION: </label>
                                                <div class="col-xs-9">
                                                    <p class='clasificacion text-left'>
                                                        <input id="radio1" type="radio" class="iestrella" name="estrellas" value="5"><label for="radio1" class="lblestrella">&#9733;</label>
                                                        <input id="radio2" type="radio" class="iestrella" name="estrellas" value="4"><label for="radio2" class="lblestrella">&#9733;</label>
                                                        <input id="radio3" type="radio" class="iestrella" name="estrellas" value="3"><label for="radio3" class="lblestrella">&#9733;</label>
                                                        <input id="radio4" type="radio" class="iestrella" name="estrellas" value="2"><label for="radio4" class="lblestrella">&#9733;</label>
                                                        <input id="radio5" type="radio" class="iestrella" name="estrellas" value="1"><label for="radio5" class="lblestrella">&#9733;</label>
                                                    </p>
                                                    <input type="hidden" value="" name="calificacion" id="calificacion">
                                                    <input type="hidden" id="idexperiencia_competencia" value=""> 
                                                </div>
                                            </div>
                                            <div class="form-group col-xs-12 text-right">
                                                <button class='btn btn-xs btn-success' id="btnGuardarCalificacion"><i class='fa fa-save'></i> Guardar Calificación</button>
                                            </div>
                                        </div>
                                    </div>
                            </fieldset>
                    </div>
                </div>
            </div>
        </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {   
        configurarAnchoModal('900');
        $('.modal-body').css('padding-top','10px');
        $('.modal-body').css('padding-bottom','10px');
        var fechaActual = new Date();
        var day = ("0" + fechaActual.getDate()).slice(-2);
	    var month = ("0" + (fechaActual.getMonth() + 1)).slice(-2);
    	var fechai = (fechaActual.getFullYear() - 1) + "-01-01";
	    var fechaf = fechaActual.getFullYear() + "-"+month+"-"+day;
	    $('#fechai').val(fechai);
        $('#fechaf').val(fechaf);
        
        $('#btnatras').click(function(){
            $('#item2').removeClass('active');
            $('#item3').removeClass('active');
            $('#item1').addClass('active');
        });

        $('#btnatras2').click(function(){
            $('#item3').removeClass('active');
            $('#item1').removeClass('active');
            $('#item2').addClass('active');
        });

        $('#btnGuardarCalificacion').click(function(){
            if($('#calificacion').val()!==""){
                $('#btnatras2').trigger('click');
                procesarAjax('editarCompetencia','tbody_competencias');
            }else{
                mostrarMensaje('No hay Competencias para agregar!','ERROR');
            }
        });

        $('.carousel').carousel({
  		    pause: true,
    	    interval: false,    	
        });

        $('#btnBuscarExperiencias').click(function(){
            procesarAjax('listarExperienciasLaborales','tbody_experiencias');
        });

        var idclick;
	    $('.lblestrella').each(function(index, value){
		    $(this).click(function(){
			    idclick = $(this).attr('for');
			    $("#" + idclick).prop('checked', true);
			    $('#calificacion').val($('#'+idclick).val());
		    });
        });
    
        procesarAjax('listarExperienciasLaborales','tbody_experiencias');

    });

    function procesarAjax(accion, idelementCargando){
        var route = 'experiencia_competencia/'+accion;
        route += '?idalumno='+$('#idalumno').val();
        if(accion.toLowerCase()==="listarexperienciaslaborales"){
            route += "&fechai=" + $('#fechai').val();
            route += "&fechaf=" + $('#fechaf').val();
        }
        if(accion.toLowerCase()==="listarcompetencias"){
            route += "&id=" + $('#idexperiencia_laboral').val();
        }
        if(accion.toLowerCase()==="editarcompetencia"){
            route += "&id=" + $('#idexperiencia_laboral').val();
            route += "&calificacion=" + $('#calificacion').val();
            route += "&idexperiencia_competencia=" + $('#idexperiencia_competencia').val();
        }
        //console.log(route);
        $.ajax({
			url: route,
			headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}' },
			type: 'GET',
			//data: $('#formnueva' + tipo).serialize(),
			beforeSend: function(){
                var tempCargando;
                if(idelementCargando.toLowerCase()==="tbody_experiencias"){
                    tempCargando= "<tr><td colspan='6'>"+imgCargando()+"</td></tr>";
                }else{
                    tempCargando= "<tr><td colspan='4'>"+imgCargando()+"</td></tr>";
                }
				$('#'+ idelementCargando).html(tempCargando);
	        },
	        success: function(JSONRESPONSE){
                $('#'+ idelementCargando).html('');
                //console.log(JSONRESPONSE);
                switch(JSONRESPONSE.RESPUESTA_SERVER.toLowerCase()){
                    case "modificado":
                        mostrarMensaje('Calificacion exitosa!','OK');
                        $('#item3').removeClass('active');
                        $('#item1').removeClass('active');
                        $('#item2').addClass('active');
                        listarCompetencias(JSONRESPONSE.LIST);
                        break;
                    case "get_experiencias_laborales":
                        listarExperienciasLaborales(JSONRESPONSE.LIST);
                        break;
                    case "":
                        listarCompetencias(JSONRESPONSE.LIST);
                        break
                    case 'error_interno':
                        mostrarMensaje('No se completo transaccion!','ERROR');
                        break
                }
            },
            error: function () {
                $('#'+ idelementCargando).html('');
                /*MOSTRAMOS MENSAJE ERROR SERVIDOR*/
                mostrarMensaje('Error interno en el Servidor!','ERROR');
            }
        });
    }

    function listarExperienciasLaborales(LIST){
        var fila;
        if(LIST.length > 0){
            $.each(LIST, function (index, value) {
                fila = "<tr>" +
                                "<td> "+ (index + 1)+"</td>"+
                                "<td class='align-middle'>" + value.ruc + "</td>"+
                                "<td class='align-middle'>" + value.empresa + "</td>"+
                                "<td class='align-middle'>" + value.cargo + "</td>"+
				"<td class='align-middle'>" + value.telefono + "</td>"+
                                "<td class='text-center align-middle'><button idexperiencia_laboral='" + value.id + "' class='btn btn-warning btn-xs competencias-experiencia' title='Competencias'><i class='fa fa-list' aria-hidden='true'></i></button></td>" +
                                "</tr>";
                $('#tbody_experiencias').append(fila);
            });
            $('.competencias-experiencia').each(function(index,value){
                    $(this).click(function(){
                        $('#idexperiencia_laboral').val($(this).attr('idexperiencia_laboral'));
                        $('#item2').addClass('active');
                        $('#item1').removeClass('active');
                        $('#item3').removeClass('active');
                        //PETICION PARA CARGAR LAS COMPETENCIAS DEL ALUMNO QUE NO ESTAN EN LA EXPERIENCIA LABORAL
                        $('#tbody_competencias').empty();
                        procesarAjax('listarCompetencias','tbody_competencias');
                                                       
                    });
            });
        }
    }

    function listarCompetencias(LIST){
        var fila;
        if(LIST.length > 0){
            $.each(LIST, function (index, value) {
                fila = "<tr>" +
                                "<td> "+ (index + 1)+"</td>"+
                                "<td class='align-middle'>" + value.nombre_competencia + "</td>"+
                                "<td class='align-middle estrellas-item' calificacion='"+value.calificacion_experiencia+"'></td>"+
                                "<td class='text-center align-middle'><button idexperiencia_competencia='" + value.idexperiencia_competencia + "' class='btn btn-warning btn-xs calificacion-experiencia' title='Calificar'><i class='fa fa-check fa-lg' aria-hidden='true'></i></button></td>" +
                                "</tr>";
                $('#tbody_competencias').append(fila);
            });
            var tr;
            $('.calificacion-experiencia').each(function(index,value){
                    $(this).click(function(){
                        tr = $(this.parentElement.parentElement).children();
                        $('#idexperiencia_competencia').val($(this).attr('idexperiencia_competencia'));
                        $('#item2').removeClass('active');
                        $('#item1').removeClass('active');
                        $('#item3').addClass('active');
                        //LIMPIAMOS TODAS LAS ESTRELLAS
                        $('.iestrella').each(function(index,value){
                            $(this).prop('checked', false); 
                        });
                        if($(tr[2]).attr('calificacion')!=='null'){
                            $('#calificacion').val($(tr[2]).attr('calificacion'));
                        }else{
                            $('#calificacion').val('');
                        }
                        if($('#calificacion').val()!==""){
                            $('.iestrella').each(function(index, value){
                                //console.log('COMPARANDO ' + $(this).val() + " - " +$('#calificacion').val());
                                if($(this).val() === $('#calificacion').val()){
                                    $(this).prop('checked', true);
                                    return false;
                                }
                            });
                        }
                    });
            });

            var cadenaEstrellas;
            var cantEstrellas;
            $('.estrellas-item').each(function(index,value){
                if($(this).attr('calificacion') !== "null"){
                    cadenaEstrellas = '';
                    for(var i=0; i < parseInt($(this).attr('calificacion')); i++){
                        cadenaEstrellas += ' &#9733;';
                    }
                    $(this).html(cadenaEstrellas);
                }
            });
        }
    }
</script>
