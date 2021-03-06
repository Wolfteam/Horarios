var operacion;

$(document).ready(function (){
    read(); 
    operacion="materias";
    $.post("../controller/secciones_controller.php",{
        operacion:operacion
    }, function(data){
        $("#selector_materias").html(data);
    });
});

function create() {
    operacion="create";
    var codigo = $("#selector_materias option:selected").val();
    var cantidadSecciones = $("#cantidad_secciones").val().trim();
    var cantidadAlumnos = $("#cantidad_alumnos").val().trim();
    //Aca falta la validacion de que los campos sean correctos y 
    //q el selector no sea el primero
    $.post("../controller/secciones_controller.php",{      
        codigo:codigo,
        cantidad_secciones: cantidadSecciones,
        cantidad_alumnos: cantidadAlumnos,
        operacion: operacion
    }, function (data, status) {
        $("#add_new_record_modal").modal("hide");
        $('#selector_materias > option[value=0]').attr('selected', 'selected');
        $("#cantidad_secciones").val("");
        $("#cantidad_alumnos").val("");
        read();
    });   
}

function read() {
    operacion="read";
    $.post("../controller/secciones_controller.php", {
        operacion: operacion
    }, function (data) {
        $(".records_content").html(data);
    });
}

function deleteStuff(codigo) {
    operacion="delete";
    var conf = confirm("¿Estas seguro que deseas borrar esta seccion?");
    if (conf == true) {
        $.post("../controller/secciones_controller.php", {
            codigo: codigo,
            operacion: operacion
        },function (data, status) {
            read();
        });
    }
}

$("#create_secciones_button").click(function(){
    create();
});