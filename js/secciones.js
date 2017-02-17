// Add Record
function addRecord() {
    // get values
    var numero_seccion = $("#numero_seccion").val();
    numero_seccion = numero_seccion.trim();
    var cantidad_alumnos = $("#cantidad_alumnos").val();
    cantidad_alumnos = cantidad_alumnos.trim();
    //var codigo = $("#codigo").val();
    //codigo = codigo.trim();
    var codigomateria = document.getElementById("materias").value;
    alert(codigomateria);
    var operacion = "create";


    if (numero_seccion == "") {
        alert("numero_seccion field is required!");
    }
    else if (cantidad_alumnos == "") {
        alert("cantidad_alumnos field is required!");
    }
   // else if (codigo == "") {
   //     alert("codigo field is required!");
   // }
    else {
        // Add record
        $.post("../controller/secciones_controller.php", {
            numero_seccion: numero_seccion,
            cantidad_alumnos: cantidad_alumnos,
            //codigo: codigo,
            codigomateria: codigomateria,
            operacion: operacion
        }, function (data, status) {
            // close the popup
            $("#add_new_record_modal").modal("hide");
 
            // read records again
            readRecords();
 
            // clear fields from the popup
            $("#numero_seccion").val("");
            $("#cantidad_alumnos").val("");

            //$("#codigo").val("");
        });
    }
}



// READ records
function readRecords() {
    var operacion="read";
    $.post("../controller/secciones_controller.php", {operacion: operacion}, function (data, status) {
        $(".records_content").html(data);
    });
}

//Este metodo es invocado al pulsar sobre el boton UPDATE para que los campos salgan 
//con los valores que tenian
function GetUserDetails(id) {
    var operacion="details";
    // Add User ID to the hidden field
    $("#hidden_user_id").val(id);
    $.post("../controller/secciones_controller.php", {
            id: id,
            operacion: operacion
        },
        function (data, status) {
            // PARSE json data
            var user = JSON.parse(data);
            // Assign existing values to the modal popup fields
            $("#update_first_name").val(user.Numero_Seccion);
            $("#update_last_name").val(user.Cantidad_Alumnos);
        }
    );
    // Open modal popup
    $("#update_user_modal").modal("show");
}

//Este metodo es llamado cuando el usuario ya ha edita lo que deseaba y presiona el boton SAVE
function UpdateUserDetails() {
    var operacion="update";
    // get values
    var numero_seccion = $("#update_first_name").val();
    numero_seccion = numero_seccion.trim();
    var cantidad_alumnos = $("#update_last_name").val();
    cantidad_alumnos = cantidad_alumnos.trim();
 
    if (numero_seccion == "") {
        alert("numero_seccion field is required!");
    }
    else if (cantidad_alumnos == "") {
        alert("cantidad_alumnos field is required!");
    }
    else {
        // get hidden field value
        var id = $("#hidden_user_id").val();
 
        // Update the details by requesting to the server using ajax
        $.post("../controller/secciones_controller.php", {
                id: id,
                numero_seccion: numero_seccion,
                cantidad_alumnos: cantidad_alumnos,
                operacion: operacion
            },
            function (data, status) {
                // hide modal popup
                $("#update_user_modal").modal("hide");
                // reload Users by using readRecords();
                readRecords();
            }
        );
    }
}


function DeleteUser(id) {
    var operacion="delete";
    var conf = confirm("Are you sure, do you really want to delete Seccion?");
    if (conf == true) {
        $.post("../controller/secciones_controller.php", {
                id: id,
                operacion: operacion
            },
            function (data, status) {
                // reload Users by using readRecords();
                readRecords();
            }
        );
    }
}


$(document).ready(function () {
    // READ records on page load
    readRecords(); // calling function

    var operacion="materias";
    $.post("../controller/secciones_controller.php",{operacion:operacion},function(data){
        $("#materias").html(data);
    });
});