function addAulas() {
    var nombreAula = $("#nombre_aula").val();
    nombreAula = nombreAula.trim();
    var capacidad = $("#capacidad").val();
    capacidad = capacidad.trim();
    var tipo = $("#tipo").val();
    tipo = tipo.trim();
    var operacion = "create";
    if (nombreAula == "") {
        alert("El nombre del aula es requerido");
    }else if (capacidad == "") {
        alert("La capacidad del aula es requerida");
    }else if (tipo=="") {
    	alert("El tipo de aula es requerido");
    }else {
        $.post("../controller/edit_db_aulas_controller.php", {
            nombre_aula: nombreAula,
            capacidad: capacidad,
           	tipo: tipo,
            operacion: operacion
        }, function (data, status) {
            $("#add_new_aulas_modal").modal("hide");
            readAulas();
            $("#nombre_aula").val("");
            $("#capacidad").val("");
			$("#tipo").val("");
        });
    }
}

function readAulas() {
    var operacion="read";
    $.post("../controller/edit_db_aulas_controller.php", {operacion:operacion}, function (data, status) {
        $(".records_content").html(data);
    });
}

function getAulasDetails(id) {
    var operacion="details";
    $("#hidden_aula_id").val(id);
    $.post("../controller/edit_db_aulas_controller.php", {id: id,operacion: operacion},
        function (data, status) {
        	var aulas = JSON.parse(data);
            $("#update_nombre_aula").val(aulas.Nombre_Aula);
            $("#update_capacidad").val(aulas.Capacidad);
            $("#update_tipo").val(aulas.Tipo);
        }
    );
    $("#update_aulas_modal").modal("show");
}

function updateAulasDetails() {
	var nombreAula = $("#update_nombre_aula").val();
    nombreAula = nombreAula.trim();
    var capacidad = $("#update_capacidad").val();
    capacidad = capacidad.trim();
    var tipo = $("#update_tipo").val();
    tipo = tipo.trim();
    var operacion = "update";
    if (nombreAula == "") {
        alert("El nombre del aula es requerido");
    }else if (capacidad == "") {
        alert("La capacidad del aula es requerida");
    }else if (tipo=="") {
    	alert("El tipo de aula es requerido");
    }else {
        var id = $("#hidden_aula_id").val();
        $.post("../controller/edit_db_aulas_controller.php", {
                id: id,
                nombre_aula: nombreAula,
                capacidad: capacidad,
                tipo: tipo,
                operacion: operacion
        	},
            function (data, status) {
                $("#update_aulas_modal").modal("hide");
                readAulas();
            }
        );
    }
}

function deleteAulas(id) {
    var operacion="delete";
    var conf = confirm("¿Estas seguro que deseas borrar el aula?");
    if (conf == true) {
        $.post("../controller/edit_db_aulas_controller.php", {
                id: id,
                operacion: operacion
            },
            function (data, status) {
                readAulas();
            }
        );
    }
}