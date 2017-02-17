function addProfesores() {
    var nombreProfesor = $("#nombre_profesor").val();
    nombreProfesor = nombreProfesor.trim();

    var apellidoProfesor = $("#apellido_profesor").val();
    apellidoProfesor = apellidoProfesor.trim();

    var prioridad = $("#prioridad").val();
    prioridad = prioridad.trim();

    var operacion = "create";
    if (nombreProfesor == "") {
        alert("El nombre del profesor es requerido");
    }else if (apellidoProfesor == ""){
        alert("El apellido del profesor es requerido");
    }else if (prioridad=="") {
    	alert("La prioridad es requerida");
    }else {
        $.post("../controller/edit_db_profesores_controller.php", {
            nombre_profesor: nombreProfesor,
            apellido_profesor:apellidoProfesor,
           	prioridad: prioridad,
            operacion: operacion
        }, function (data, status) {
            $("#add_new_profesores_modal").modal("hide");
            readProfesores();
            $("#nombre_profesor").val("");
            $("#apellido_profesor").val("");
			$("#prioridad").val("");
        });
    }
}

function readProfesores() {
    var operacion="read";
    $.post("../controller/edit_db_profesores_controller.php", {operacion:operacion}, function (data, status) {
        $(".records_content").html(data);
    });
}

function getProfesoresDetails(id) {
    var operacion="details";
    $("#hidden_profesor_id").val(id);
    $.post("../controller/edit_db_profesores_controller.php", {id: id,operacion: operacion},
        function (data, status) {
            var profesores = JSON.parse(data);
            $("#update_nombre_profesor").val(profesores.Nombre);
            $("#update_apellido_profesor").val(profesores.Apellido);
            $("#update_prioridad").val(profesores.Prioridad);
        }
    );
    $("#update_profesores_modal").modal("show");
}

function updateProfesoresDetails() {
    var nombreProfesor = $("#update_nombre_profesor").val();
    nombreProfesor = nombreProfesor.trim();

    var apellidoProfesor = $("#update_apellido_profesor").val();
    apellidoProfesor = apellidoProfesor.trim();

    var prioridad = $("#update_prioridad").val();
    prioridad = prioridad.trim();

    var operacion = "update";
    if (nombreProfesor == "") {
        alert("El nombre del profesor es requerido");
    }else if (apellidoProfesor == ""){
        alert("El apellido del profesor es requerido");
    }else if (prioridad=="") {
        alert("La prioridad es requerida");
    }else {
        var id = $("#hidden_profesor_id").val();
        $.post("../controller/edit_db_profesores_controller.php", {
                id: id,
                nombre_profesor: nombreProfesor,
                apellido_profesor:apellidoProfesor,
                prioridad: prioridad,
                operacion: operacion
            },
            function (data, status) {
                $("#update_profesores_modal").modal("hide");
                readProfesores();
            }
        );
    }
}

function deleteProfesores(id) {
    var operacion="delete";
    var conf = confirm("¿Estas seguro que deseas borrar al profesor?");
    if (conf == true) {
        $.post("../controller/edit_db_profesores_controller.php", {
                id: id,
                operacion: operacion
            },
            function (data, status) {
                readProfesores();
            }
        );
    }
}