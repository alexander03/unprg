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
<div class="row">
    <div class="col-xs-12">
        <div id="myCarousel" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators" style="display: none">
                <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                <li data-target="#myCarousel" data-slide-to="1"></li>
            </ol>
            <div class="carousel-inner">
                <div class="item active" id="item1">
                    <div class="text-right">
                        <button class='btn btn-xs btn-info' id="btnabrirNuevo"><i class='glyphicon glyphicon-plus'></i> Agregar</button>
                    </div>
                    <fieldset class="col-12" style="margin-top: 0px;">
                            <legend style="width: 200px">Competencias Desarrolladas</legend>
                            <div class="panel panel-default" style="margin-bottom: 10px;">
                                <div class="panel-body">
                                        <table id="example1" class="table table-bordered table-striped table-condensed table-hover table-sm">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 4%">#</th>
                                                        <th>COMPETENCIA</th>
                                                        <th style="width: 4%">ELI</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tbody_competencias">
                                                                
                                                </tbody>
                                            </table>
                                </div>
                            </div>
                    </fieldset>
                    
                            <?php
                            echo "<input type='hidden' name='id' id='id' value='".$id."'>";
                            //echo "<input type='hidden' name='cargo' id='cargo' value='".$modelo->cargo."'>";
                            ?>
                    <input type="hidden" name="idexperiencia_competencia" id="idexperiencia_competencia" value="">
                </div>
                <div class="item" id="item2">
                        <div class="text-right">
                                <button class='btn btn-xs btn-default' id="btnatras"><i class='glyphicon glyphicon-chevron-left'></i> Atrás</button>
                        </div>
                        <fieldset class="col-12" style="margin-top: 0px;">
                                <legend>Mis Competencias</legend>
                                <div class="panel panel-default" style="margin-bottom: 10px;">
                                    <div class="panel-body">
                                        <div class="form-group col-xs-12">
                                            <label>Competencia <div id="cargandoCombo"></div></label>
                                            <select class="form-control input-xs" id="competencia_alumno_id" name="competencia_alumno_id">

                                            </select>
                                        </div>
                                        <div class="form-group col-xs-12 text-right">
                                            <button class='btn btn-xs btn-info' id="btnGuardarCompetenciaExperiencia"><i class='fa fa-save'></i> Agregar</button>
                                        </div>
                                    </div>
                                </div>
                        </fieldset>
                </div>
            </div>
        </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {   
        $('.modal-body').css('padding-top','10px');
        $('.modal-body').css('padding-bottom','10px');
        //$('.modal-title').html('<h5>EXP. LABORAL [ '+$('#cargo').val()+ ' ]</h5>');  
        $('#btnabrirNuevo').click(function(){
            $('#item1').removeClass('active');
            $('#item2').addClass('active');
            //PETICION PARA CARGAR LAS COMPETENCIAS DEL ALUMNO QUE NO ESTAN EN LA EXPERIENCIA LABORAL
            $('#competencia_alumno_id').empty();
            procesarAjax('listarCompetenciasAlumno','cargandoCombo');
        });
        
        $('#btnatras').click(function(){
            $('#item2').removeClass('active');
            $('#item1').addClass('active');
        });

        $('#btnGuardarCompetenciaExperiencia').click(function(){
            //console.log("id del combo  " + $('#competencia_alumno_id').val());
            $('#btnatras').trigger('click');
            if(parseInt($('#competencia_alumno_id').val())>0){
                $('#tbody_competencias').empty();
                procesarAjax('agregarCompetencia','tbody_competencias');
            }else{
                mostrarMensaje('No hay Competencias para agregar!','ERROR');
            }
        });

        $('.carousel').carousel({
  		    pause: true,
    	    interval: false,    	
        });

        procesarAjax('listarCompetencias','tbody_competencias');

    });

    function procesarAjax(accion, idelementCargando){
        var route = 'experiencia_competencia/'+accion;
        route += '?id='+$('#id').val();
        if(accion.toLowerCase()==="agregarcompetencia"){
            route += "&competencia_alumno_id=" + $('#competencia_alumno_id').val();
        }
        if(accion.toLowerCase()==="eliminarcompetencia"){
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
                if(idelementCargando.toLowerCase()!=="tbody_competencias"){
                    tempCargando = imgCargando();
                }else{
                    tempCargando= "<tr><td colspan='3'>"+imgCargando()+"</td></tr>";
                }
				$('#'+ idelementCargando).html(tempCargando);
	        },
	        success: function(JSONRESPONSE){
                $('#'+ idelementCargando).html('');
                //console.log(JSONRESPONSE);
                switch(JSONRESPONSE.RESPUESTA_SERVER.toLowerCase()){
                    case "registrado":
                        mostrarMensaje('Competencia agregada exitosmente!','OK');
                        break;
                    case "eliminado":
                        mostrarMensaje('Competencia eliminada exitosmente!','OK');
                        break;
                    case "listar_combo":
                        cargarItemsCombo(JSONRESPONSE.LIST_COMBO);
                        break;
                    case 'error_interno':
                        mostrarMensaje('No se completo transaccion!','ERROR');
                        break
                }
                listarCompetencias(JSONRESPONSE.LIST);
            },
            error: function () {
                $('#'+ idelementCargando).html('');
                /*MOSTRAMOS MENSAJE ERROR SERVIDOR*/
                mostrarMensaje('Error interno en el Servidor!','ERROR');
            }
        });
    }

    function listarCompetencias(LIST){
        var fila;
        if(LIST.length > 0){
            $.each(LIST, function (index, value) {
                fila = "<tr>" +
                                "<td> "+ (index + 1)+"</td>"+
                                "<td class='align-middle'>" + value.nombre_competencia + "</td>"+
                                "<td class='text-center align-middle'><button idexperiencia_competencia='" + value.idexperiencia_competencia + "' idcompetencia_alumno='" + value.idcompetencia_alumno + "' class='btn btn-primary btn-xs eliminar-experiencia_competencia' title='Eliminar'><i class='fa fa-trash' aria-hidden='true'></i></button></td>" +
                                "</tr>";
                $('#tbody_competencias').append(fila);
            });
            $('.eliminar-experiencia_competencia').each(function(index,value){
                    $(this).click(function(){
                        $('#idexperiencia_competencia').val($(this).attr('idexperiencia_competencia'));
                        procesarAjax('eliminarCompetencia','tbody_competencias');                                
                    });
            });
        }
    }

    function cargarItemsCombo(LIST_COMBO){
        var item;
        if(LIST_COMBO.length > 0){
            var add;
            var iditem;
            $.each(LIST_COMBO, function (index, value) {
                add = true;
                iditem = value.id;
                $('.eliminar-experiencia_competencia').each(function(index,value){
                    if($(this).attr('idcompetencia_alumno')===iditem.toString()){
                        add = false;
                        return false;
                    }
                });
                if(add){
                    item = "<option value='"+value.id+"'>"+value.competencia_nombre+"</option>";
                    $('#competencia_alumno_id').append(item);
                }
            });
        }
    }
</script>