var horas_semana_por_asignar;
var codigo_materia;

var id_seccion;
var hora_inicio;
var hora_fin;
var dia_seleccionado;
var cantidad_alumnos_seccion;

var aulas;

//Debe arreglarse que cuando selecciono la primera opcion da error
$(document).ready(function(){
  var operacion="obtener_materias";
  $.post("../controller/horario_profesor_controller.php",{operacion:operacion},function(materias){
    document.getElementById("selectorMaterias").innerHTML="<option>Seleccione materia</option>";

    document.getElementById("selectorMaterias").innerHTML+=materias;
    //$("#selectorMaterias").html(materias);
  });

  $("#selectorMaterias").change(function () {
    $("#selectorMaterias option:selected").each(function () {
      codigomateria=$(this).val();
      var operacion="obtener_profesorID";
      $.post("../controller/horario_profesor_controller.php", { codigoMateria: codigomateria,operacion:operacion },getProfesoresData);            
        });
   });
});

/*
function obtenDatos(){
  var codigomateria = document.getElementById("selectorMaterias").value;
  var operacion="obtener_profesorID";
  if (codigomateria==0){
    return;
  }
  alert("Materia seleccionada:"+codigomateria);
//  $.post("../controller/horario_profesor_controller.php", { codigoMateria: codigomateria,operacion: operacion },getProfesoresData);
}
*/

function getProfesoresData(profesoresID){
	console.log("IDs de los profesores: "+profesoresID);//Muestra los id de los profesores que dan la materia seleccionada
  var operacion="obtener_profesorData";
  if (profesoresID!="No hay resultados") {
      $.post("../controller/horario_profesor_controller.php", { profesoresID: profesoresID, operacion:operacion }, showProfesoresData);
  }
}

function showProfesoresData(profesoresData){
  console.log("Datos del profesor seleccionado: "+profesoresData);//Muestra los datos del profesor seleccionado
  $("#selectorProfesores").html(profesoresData);
	var operacion="obtener_seccionID";
  //var profesorSelectedID = document.getElementById("selectorProfesores").value;
  var codigomateria=document.getElementById("selectorMaterias").value;
  //alert("Codigo materia seleccionada:"+codigomateria +" ID Profesor seleccionado: "+profesorSelectedID);
  $.post("../controller/horario_profesor_controller.php",{codigoMateria: codigomateria,operacion: operacion}, getSecciones)
}

function getSecciones(secciones){
  console.log("Esto es lo que viene en secciones: "+secciones);
 // if (secciones =="false"){
   // alert("No se encontraron secciones");
  //}else{
    var operacion="mostrar_seccionID";
    $.post("../controller/horario_profesor_controller.php",{secciones: secciones,operacion: operacion}, showSecciones)
  //}
}

function showSecciones(secciones){
  console.log("options a crear: "+secciones); //Aca ta el numero de options de acuerdo al numero de secciones, false si no encontro nada
  $("#selectorSecciones").html(secciones); 
  if (secciones!="<option>No se encontraron secciones</option>"){
    var operacion="horas_por_asignar";
    var codigomateria=document.getElementById("selectorMaterias").value; 
    $.post("../controller/horario_profesor_controller.php",{codigomateria: codigomateria,operacion: operacion}, setHorasPorAsignar)
    //alert(horasPorAsignar);
  }

}

function setHorasPorAsignar(horasPorSemana){
  document.getElementById("boton_guardar").disabled=false;
  var horas = horasPorSemana;
  
  document.getElementById("horas_por_asignar").value=horas;
  //var horasPorSemana = document.getElementById("horas_por_asignar").value;
  //alert(horas);
  //console.log("Horas asignadas:"+horasPorSemana);
  printHoras();
}


function guardar(){
  
  horas_semana_por_asignar = document.getElementById("horas_por_asignar").value;
  codigo_materia= document.getElementById("selectorMaterias").value;

  id_seccion=document.getElementById("selectorSecciones").value;
  hora_inicio=document.getElementById("selectorHoraInicio").value;
  hora_fin=document.getElementById("selectorHoraFin").value;
  dia_seleccionado=document.getElementById("selector_dias").value;
  dia_seleccionado= JSON.stringify(dia_seleccionado); 
  var operacion="obtener_aula";
  console.log("Horas por semana:"+horas_semana_por_asignar+" Codigo Materia:"+codigo_materia+
    " Id seccion: "+id_seccion+" hora inicio: "+hora_inicio+ " hora fin: "+hora_fin+" dia seleccionado:" +dia_seleccionado);
  
  $.post("../controller/horario_profesor_controller.php",{horassemana:horas_semana_por_asignar,codigomateria: codigo_materia,
    idseccion:id_seccion,horainicio:hora_inicio,horafin:hora_fin,diaseleccionado:dia_seleccionado,operacion: operacion},function(tipoMateria){
    alert("Esto es lo que me devuelve el mierdero: "+tipoMateria);
  });

  
}

/*
function updateHorasPorAsignar(horas,horasPorSemana){
  //quizas una columna que se actualize con el valor de aca, puede llamarse horas asignadas
  var horasRestantes = horasPorSemana-horas;
  if (horasRestantes==0){
    alert("Se asigno todas las horas requeridas");
    return horasRestantes;
  }else{
    alert("Se requiere asignar mas horas");
    return horasRestantes;
  }
}
*/
function printHoras(){
  //console.log("Entre a printhoras");
  //Con estas 3 lineas me aseguro que no se llene de basura las option de cada select
  document.getElementById("selectorHoraInicio").innerHTML="<option>Seleccione hora</option>";
  document.getElementById("selectorHoraFin").innerHTML="<option>Seleccione hora</option>";
  document.getElementById("selector_dias").innerHTML="<option>Seleccione dia</option>";

  document.getElementById("selectorHoraInicio").innerHTML+=
  "<option value='1'>7:00  am</option>"+"<option value='2'>7:50  am</option>"+"<option value='3'>8:40  am</option>"+
  "<option value='4'>9:35  am</option>"+"<option value='5'>10:30 am</option>"+"<option value='6'>11:20 am</option>"+
  "<option value='7'>12:10 pm</option>"+"<option value='8'>1:00  pm</option>"+"<option value='9'>1:50  pm</option>"+
  "<option value='10'>2:40  pm</option>"+"<option value='11'>3:35  pm</option>"+"<option value='12'>4:25  pm</option>"+
  "<option value='13'>5:20  pm</option>"+"<option value='14'>6:10  pm</option>";

  document.getElementById("selectorHoraFin").innerHTML+=
  "<option value='2'>7:50  am</option>"+"<option value='3'>8:40  am</option>"+
  "<option value='4'>9:35  am</option>"+"<option value='5'>10:30 am</option>"+"<option value='6'>11:20 am</option>"+
  "<option value='7'>12:10 pm</option>"+"<option value='8'>1:00  pm</option>"+"<option value='9'>1:50  pm</option>"+
  "<option value='10'>2:40  pm</option>"+"<option value='11'>3:35  pm</option>"+"<option value='12'>4:25  pm</option>"+
  "<option value='13'>5:20  pm</option>"+"<option value='14'>6:10  pm</option>";

  document.getElementById("selector_dias").innerHTML+=
  "<option value='Lunes'>Lunes</option>"+"<option value='Martes'>Martes</option>"+"<option value='Miercoles'>Miercoles</option>"+
  "<option value='Jueves'>Jueves</option>"+"<option value='Viernes'>Viernes</option>"+"<option value='Sabado'>Sabado</option>";

}
