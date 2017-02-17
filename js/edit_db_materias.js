function addMaterias() {
    var codigo = $("#codigo").val();
    codigo = codigo.trim(); 

    var nombreMateria = $("#nombre_materia").val();
    nombreMateria = nombreMateria.trim();

    var semestre = $("#semestre").val();
    semestre = semestre.trim();

    var horasTotales = $("#horas_totales").val();
    horasTotales = horasTotales.trim();   

    var horasSemanales = $("#horas_semanales").val();
    horasSemanales = horasSemanales.trim();    

    var tipo = $("#tipo_materia").val();
    tipo = tipo.trim();

    var carrera = $("#carrera").val();
    carrera = carrera.trim();

    var operacion = "create";
    if (codigo == "") {
        alert("El codigo de la materia es requerido");
    }else if (nombreMateria == ""){
        alert("El nombre de la materia es requerido");
    }else if (semestre == "") {
        alert("El semestre de la materia es requerido");
    }else if (horasTotales == ""){
        alert("Las horas totales de la materia es requerido");
    }else if (horasSemanales == ""){
        alert("Las horas semanales de la materia es requerido");
    }else if (tipo=="") {
    	alert("El tipo de la materia es requerido");
    }else if (carrera == ""){
        alert("Las carrera a la que se dicta la materia es requerido");
    }else {
        $.post("../controller/edit_db_materias_controller.php", {
            codigo: codigo,
            nombre_materia: nombreMateria,
            semestre: semestre,
            horas_totales: horasTotales,
            horas_semanales: horasSemanales,
           	tipo_materia: tipo,
            carrera:carrera,
            operacion: operacion
        }, function (data, status) {
            $("#add_new_materias_modal").modal("hide");
            readMaterias();
            $("#codigo").val("");
            $("#nombre_materia").val("");
            $("#semestre").val("");
            $("#horas_totales").val("");
            $("#horas_semanales").val("");
			$("#tipo_materia").val("");
            $("#carrera").val("");
        });
    }
}

function readMaterias() {
    var operacion="read";
    $.post("../controller/edit_db_materias_controller.php", {operacion:operacion}, function (data, status) {
        $(".records_content").html(data);
    });
}

function getMateriasDetails(codigo) {
    var operacion="details";
    $("#hidden_materia_codigo").val(codigo);
    $.post("../controller/edit_db_materias_controller.php", {codigo: codigo,operacion: operacion},
        function (data, status) {
            var materias = JSON.parse(data);
            $("#update_codigo").val(materias.Codigo);
            $("#update_nombre_materia").val(materias.Asignatura);
            $("#update_semestre").val(materias.Semestre);
            $("#update_horas_totales").val(materias.Horas_Academicas);
            $("#update_horas_semanales").val(materias.Horas_Academicas_Semanales);
            $("#update_tipo_materia").val(materias.Tipo);
            $("#update_carrera").val(materias.Carrera);
        }
    );
    $("#update_materias_modal").modal("show");
}

function updateMateriasDetails() {
    var nombreMateria = $("#update_nombre_materia").val();
    nombreMateria = nombreMateria.trim();

    var semestre = $("#update_semestre").val();
    semestre = semestre.trim();

    var horasTotales = $("#update_horas_totales").val();
    horasTotales = horasTotales.trim();   

    var horasSemanales = $("#update_horas_semanales").val();
    horasSemanales = horasSemanales.trim();    

    var tipo = $("#update_tipo_materia").val();
    tipo = tipo.trim();

    var carrera = $("#update_carrera").val();
    carrera = carrera.trim();

    var operacion = "update";
    if (nombreMateria == ""){
        alert("El nombre de la materia es requerido");
    }else if (semestre == "") {
        alert("El semestre de la materia es requerido");
    }else if (horasTotales == ""){
        alert("Las horas totales de la materia es requerido");
    }else if (horasSemanales == ""){
        alert("Las horas semanales de la materia es requerido");
    }else if (tipo=="") {
        alert("El tipo de la materia es requerido");
    }else if (carrera == ""){
        alert("Las carrera a la que se dicta la materia es requerido");
    }else {
        var codigo = $("#hidden_materia_codigo").val();
        $.post("../controller/edit_db_materias_controller.php", {
                codigo: codigo,
                nombre_materia: nombreMateria,
                semestre: semestre,
                horas_totales: horasTotales,
                horas_semanales: horasSemanales,
                tipo_materia: tipo,
                carrera:carrera,
                operacion: operacion
            },
            function (data, status) {
                $("#update_materias_modal").modal("hide");
                readMaterias();
            }
        );
    }
}

function deleteMaterias(codigo) {
    var operacion="delete";
    var conf = confirm("¿Estas seguro que deseas borrar la materia?");
    if (conf == true) {
        $.post("../controller/edit_db_materias_controller.php", {
                codigo: codigo,
                operacion: operacion
            },
            function (data, status) {
                readMaterias();
            }
        );
    }
}