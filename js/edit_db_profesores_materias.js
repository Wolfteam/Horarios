function addProfesoresMaterias() {
    var idProfesor = document.getElementById("selector_profesores").value;
    var codigoMateria = document.getElementById("selector_materias").value;
    var operacion = "create";
    if (codigoMateria == "0") {
        alert("Debe seleccionar una materia");
    }
    else if (idProfesor == "0") {
        alert("Debe seleccionar un profesor");
    }
    else {
        $.post("../controller/edit_db_profesores_materias_controller.php", {
            id_profesor: idProfesor,
            codigo_materia:codigoMateria,
            operacion: operacion
        }, function (data, status) {
            $("#add_new_profesores_materias_modal").modal("hide");
            readProfesoresMaterias();
        //Deberia limpiar los valores seleccionados al crear el beta
        });
    }
}

function readProfesoresMaterias() {
    var operacion="read";
    $.post("../controller/edit_db_profesores_materias_controller.php", {operacion: operacion}, function (data, status) {
        $(".records_content").html(data);
    });
}

function getProfesoresMateriasDetails(idProfesor,codigoMateria) {
    $("#hidden_profesor_materia_id").val(idProfesor);
    $("#hidden_profesor_materia_codigo").val(codigoMateria); 
    var operacion = "update_data";
    addContent(operacion);
 /*   $.post("../controller/edit_db_profesores_materias_controller.php", {
            id_profesor: idProfesor,
            codigo_materia:codigoMateria,
            operacion: operacion
        },
        function (data, status) {
            // PARSE json data
            var user = JSON.parse(data);
            // Assign existing values to the modal popup fields
            //Deberia mostrar los valores que ya contenia el registro antes de cambiarlos
        }
    );*/
    $("#update_profesores_materias_modal").modal("show");
}

function updateProfesoresMateriasDetails() {
    var operacion="update";
    var idProfesor = document.getElementById("selector_update_profesores").value;
    var codigoMateria = document.getElementById("selector_update_materias").value;
 
    if (codigoMateria == "0") {
        alert("Debe seleccionar una materia");
    }
    else if (idProfesor == "0") {
        alert("Debe seleccionar un profesor");
    }
    else {
        var idProfesorOld = $("#hidden_profesor_materia_id").val();
        var codigoMateriaOld = $("#hidden_profesor_materia_codigo").val();
        $.post("../controller/edit_db_profesores_materias_controller.php", {
                id_profesor_nuevo: idProfesor,
                codigo_materia_nuevo: codigoMateria,
                id_profesor_viejo: idProfesorOld,
                codigo_materia_viejo: codigoMateriaOld,                
                operacion: operacion
            },
            function (data, status) {
                $("#update_profesores_materias_modal").modal("hide");
                readProfesoresMaterias();
            }
        );
    }
}

function deleteProfesoresMaterias(idProfesor,codigoMateria) {
    var operacion="delete";
    var conf = confirm("¿Estas seguro que deseas borrar la asignacion de este profesor a esta materia?");
    if (conf == true) {
        $.post("../controller/edit_db_profesores_materias_controller.php", {
                id_profesor: idProfesor,
                codigo_materia:codigoMateria,
                operacion: operacion
            },
            function (data, status) {
                readProfesoresMaterias();
            }
        );
    }
}

function addContent(op){
    if (op=="update_data") {
         var operacion="mostrar_materias";
        $.post("../controller/edit_db_profesores_materias_controller.php",{operacion:operacion},function(data){
            $("#selector_update_materias").html(data);
        });
        operacion="mostrar_profesores";
        $.post("../controller/edit_db_profesores_materias_controller.php",{operacion:operacion},function(data){
             $("#selector_update_profesores").html(data);
        }); 
    }else{
        var operacion="mostrar_materias";
        $.post("../controller/edit_db_profesores_materias_controller.php",{operacion:operacion},function(data){
            $("#selector_materias").html(data);
        });
        operacion="mostrar_profesores";
        $.post("../controller/edit_db_profesores_materias_controller.php",{operacion:operacion},function(data){
             $("#selector_profesores").html(data);
        });
    }
}