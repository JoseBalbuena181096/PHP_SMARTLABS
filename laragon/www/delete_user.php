<?php
session_start();
$logged = $_SESSION['logged'];

if(!$logged){
  echo "Ingreso no autorizado";
  die();
}

$devices = $_SESSION['devices'];
$users_traffic_device = [];

$array = array();

foreach ($devices as $device ) {
  array_push($array,$device['devices_serie']);
}


$db_host        = '192.168.0.100';
$db_user        = 'admin_iotcurso';
$db_pass        = '181096';
$db_database    = 'emqx'; 
$db_port        = '4000';

//momento de conectarnos a db
// $conn = mysqli_connect("localhost","adminiot","emqxpass","admin_iot", "4000");
$conn = mysqli_connect($db_host,$db_user,$db_pass,$db_database,$db_port);

if ($conn==false){
  echo "Hubo un problema al conectarse a María DB";
  die();
}

//declaramos variables vacias servirán también para repoblar el formulario

$registration_ = "";

$matricula_ = "";


if(isset($_POST['registration'])) {


  $registration_ = strip_tags($_POST['registration']);
  $registration_ = strtoupper($registration_);
  $result = $conn->query("SELECT * FROM `habintants` WHERE `hab_registration` = '$registration_'");

    // Verificar si la consulta fue exitosa
    if ($result) {
        // Obtener el nombre desde el primer registro (asumiendo que es único)
        if ($row = $result->fetch_assoc()) {
            $nameValue = $row["hab_name"];
            $regValue = $row["hab_registration"];
            // También puedes devolver el nombre como parte de la respuesta
            echo "Nombre: " . $nameValue . " - Matricula: " . $regValue ;
        } else {
            echo "No existe el usuario.";
        }
    } else {
        // Mostrar un mensaje de error si la consulta falla
        echo "Error en la consulta: " . $conn->error;
    }


}else{
    echo $registration_;
}

if(isset($_POST['matricula'])){
    $matricula_ = strip_tags($_POST['matricula']);
    $matricula_ = strtoupper($matricula_);
    $result_ = $conn->query("SELECT * FROM `habintants` WHERE `hab_registration` = '$matricula_'");
    if ($result_) {
      // Obtener el nombre desde el primer registro (asumiendo que es único)
      if ($row_ = $result_->fetch_assoc()) {
          $id = $row_["hab_card_id"];
          $regValue_ = $row_["hab_registration"];

          // Consulta SQL para eliminar la fila con el ID específico
          $sql_delete_card = $conn->query("DELETE FROM `cards` WHERE `cards_id` = '$id'");
          // Consulta SQL para eliminar al usuario especifico
          $sql_delete_user = $conn->query("DELETE FROM `habintants` WHERE `hab_registration` = '$regValue_'");
          echo "Usuario eliminado ";
      } else {
          echo "No existe el usuario.";
      }
  } else {
      // Mostrar un mensaje de error si la consulta falla
      echo "Error en la consulta: " . $conn->error;
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>SMARTLABS</title>
  <meta name="description" content="Admin, Dashboard, Bootstrap, Bootstrap 4, Angular, AngularJS" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimal-ui" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <!-- for ios 7 style, multi-resolution icon of 152x152 -->
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-barstyle" content="black-translucent">
  <link rel="apple-touch-icon" href="assets/images/logo.png">
  <meta name="apple-mobile-web-app-title" content="Flatkit">
  <!-- for Chrome on Android, multi-resolution icon of 196x196 -->
  <meta name="mobile-web-app-capable" content="yes">
  <link rel="shortcut icon" sizes="196x196" href="assets/images/descarga.png">

  <!-- style -->
  <link rel="stylesheet" href="assets/animate.css/animate.min.css" type="text/css" />
  <link rel="stylesheet" href="assets/glyphicons/glyphicons.css" type="text/css" />
  <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.min.css" type="text/css" />
  <link rel="stylesheet" href="assets/material-design-icons/material-design-icons.css" type="text/css" />

  <link rel="stylesheet" href="assets/bootstrap/dist/css/bootstrap.min.css" type="text/css" />
  <!-- build:css assets/styles/app.min.css -->
  <link rel="stylesheet" href="assets/styles/app.css" type="text/css" />
  <!-- endbuild -->
  <link rel="stylesheet" href="assets/styles/font.css" type="text/css" />
</head>
<body>
  <div class="app" id="app">

    <!-- ############ LAYOUT START-->

    <!-- BARRA IZQUIERDA -->
    <!-- aside -->
    <div id="aside" class="app-aside modal nav-dropdown">
      <!-- fluid app aside -->
      <div class="left navside dark dk" data-layout="column">
        <div class="navbar no-radius">
          <!-- brand -->
          <a class="navbar-brand">
            <img src="assets/images/descarga.png" alt=".">
            <span class="hidden-folded inline">SMARTLABS</span>
          </a>
          <!-- / brand -->
        </div>
        <div class="hide-scroll" data-flex>
          <nav class="scroll nav-light">

          <ul class="nav" ui-nav>
              <li class="nav-header hidden-folded">
                <small class="text-muted">MENÚ  IoT</small>
              </li>

              <li>
                <a href="dashboard.php" >
                  <span class="nav-icon">
                    <i class="fa fa-building-o"></i>
                  </span>
                  <span class="nav-text">Principal</span>
                </a>
              </li>

              <li>
                <a href="devices.php" >
                  <span class="nav-icon">
                    <i class="fa fa-cogs"></i>
                  </span>
                  <span class="nav-text">Dispositivos</span>
                </a>
              </li>
              <li>
                <a href="register_lab.php" >
                  <span class="nav-icon">
                    <i  class="fa fa-users" ></i>
                  </span>
                  <span class="nav-text">Registrar usuarios</span>
                </a>
              </li>
              <li>
                <a href="delete_user.php" >
                  <span class="nav-icon">
                    <i class="fa fa-user-times"></i>
                  </span>
                  <span class="nav-text">Eliminar usuarios</span>
                </a>
              </li>
              <li>
                <a href="horas_uso.php" >
                  <span class="nav-icon">
                    <i class="fa fa-bar-chart"></i>
                  </span>
                  <span class="nav-text">Estadísticas de equipos</span>
                </a>
              </li>
            </ul>
          </nav>
        </div>
        <div class="b-t">
          <div class="nav-fold">
            <a href="profile.html">
              <span class="pull-left">
              </span>
              <span class="clear hidden-folded p-x">
              </span>
            </a>
          </div>
        </div>
      </div>
    </div>
    <!-- / -->

    <!-- content -->
    <div id="content" class="app-content box-shadow-z0" role="main">
      <div class="app-header white box-shadow">
        <div class="navbar navbar-toggleable-sm flex-row align-items-center">
          <!-- Open side - Naviation on mobile -->
          <a data-toggle="modal" data-target="#aside" class="hidden-lg-up mr-3">
            <i class="material-icons">&#xe5d2;</i>
          </a>
          <!-- / -->
          <div class="">
            <b id="display_new_access">  </b>
          </div>
          <!-- Page title - Bind to $state's title -->
          <div class="mb-0 h5 no-wrap" ng-bind="$state.current.data.title" id="pageTitle"></div>

          <!-- navbar collapse -->
          <div class="collapse navbar-collapse" id="collapse">
            <!-- link and dropdown -->
            <ul class="nav navbar-nav mr-auto">
              <li class="nav-item dropdown">
                <a  class="nav-link" href data-toggle="dropdown">

                </a>
                <div ui-include="'views/blocks/dropdown.new.html'"></div>
              </li>
            </ul>

            <div ui-include="'views/blocks/navbar.form.html'"></div>
            <!-- / -->
          </div>
          <!-- / navbar collapse -->

          <!-- BARRA DE LA DERECHA -->
          <ul class="nav navbar-nav ml-auto flex-row">
            <li class="nav-item dropdown pos-stc-xs">
              <a class="nav-link mr-2" href data-toggle="dropdown">

              </a>
              <div ui-include="'views/blocks/dropdown.notification.html'"></div>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link p-0 clear" href="#" data-toggle="dropdown">

              </a>
              <div ui-include="'views/blocks/dropdown.user.html'"></div>
            </li>
            <li class="nav-item hidden-md-up">
              <a class="nav-link pl-2" data-toggle="collapse" data-target="#collapse">
              </a>
            </li>
          </ul>
          <!-- / navbar right -->
        </div>
      </div>


      <!-- PIE DE PAGINA -->
      <div class="app-footer">
        <div class="p-2 text-xs">
          <div class="pull-right text-muted py-1">
            <strong> SMARTLABS</strong> <span class="hidden-xs-down">- Built BY Jose Angel Balbuena</span>
            <a ui-scroll-to="content"><i class="fa fa-long-arrow-up p-x-sm"></i></a>
          </div>
          <div class="nav">
          </div>
        </div>
      </div>

      <div ui-view class="app-body" id="view">


        <!-- SECCION CENTRAL -->
        <div class="padding">
          <!-- VALORES EN TIEMPO REAL -->
          <div class="row">
            <div class="col-sm-12">
                  <div class="box">
                    <div class="box-header">
                      <h2>BUSCAR USUARIO </h2>
                    </div>

                        <div class="md-form-group" style="margin-left:25px">
                            <input name="registration" id="registration" type="text"  placeholder="Ej: A01736666" class="md-input" value="" onchange="enviarDatos()" required>
                            <label>INGRESE MATRICULA y/o NOMINA: </label>
                        </div>
                    <br>
                  </div> 
                  <div class="box">
                    <div class="box-header">
                      <h2>ELIMINAR USUARIO </h2>
                    </div>
                    <form method="post" target="delete_user.php" name="form">
                        <div class="md-form-group" style="margin-left:25px">
                            <input name="name" id='name'type="text" class="md-input" value="" required>
                            <label>NOMBRE: </label>
                        </div>
                        <div class="md-form-group" style="margin-left:25px">
                            <input name="matricula" id='matricula'type="text" class="md-input" value="" required>
                            <label>Matricula y/o Nómina: </label>
                        </div>
                        <button type="submit" class="btn btn-fw danger " style="margin-left:25px">ELIMINAR</button>
                    </form>
                    <br>
                  </div>
            </div>
          </div>
        </div>
        <!-- ############ PAGE END-->
      </div>
    </div>
    <!-- / -->
<!-- ############ LAYOUT END-->

</div>
<!-- build:js scripts/app.html.js -->
<!-- jQuery -->
<script src="libs/jquery/jquery/dist/jquery.js"></script>
<!-- Bootstrap -->
<script src="libs/jquery/tether/dist/js/tether.min.js"></script>
<script src="libs/jquery/bootstrap/dist/js/bootstrap.js"></script>
<!-- core -->
<script src="libs/jquery/underscore/underscore-min.js"></script>
<script src="libs/jquery/jQuery-Storage-API/jquery.storageapi.min.js"></script>
<script src="libs/jquery/PACE/pace.min.js"></script>

<script src="html/scripts/config.lazyload.js"></script>

<script src="html/scripts/palette.js"></script>
<script src="html/scripts/ui-load.js"></script>
<script src="html/scripts/ui-jp.js"></script>
<script src="html/scripts/ui-include.js"></script>
<script src="html/scripts/ui-device.js"></script>
<script src="html/scripts/ui-form.js"></script>
<script src="html/scripts/ui-nav.js"></script>
<script src="html/scripts/ui-screenfull.js"></script>
<script src="html/scripts/ui-scroll-to.js"></script>
<script src="html/scripts/ui-toggle-class.js"></script>

<script src="html/scripts/app.js"></script>

<!-- ajax -->
<script src="libs/jquery/jquery-pjax/jquery.pjax.js"></script>
<script src="html/scripts/ajax.js"></script>

<script src="libs/mqtt/dist/mqtt.min.js"></script>
<script type="text/javascript">

/*
******************************
****** PROCESOS  *************
******************************
*/
        function enviarDatos() {
            var valorInput = document.getElementById('registration').value;

            // Crear objeto XMLHttpRequest
            var xhr = new XMLHttpRequest();

            // Configurar la solicitud con la URL correcta
            xhr.open('POST', 'delete_user.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            // Definir la función de devolución de llamada
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    // Obtener el valor del nombre y la matrícula desde la respuesta
                    var nombre = xhr.responseText.match(/Nombre: (.+?) -/);
                    var matricula = xhr.responseText.match(/Matricula: (.+)/);
                    if (nombre && nombre[1]) {
                        // Actualizar el valor del input 'name'
                        document.getElementById('name').value = nombre[1];
                    }
                    if (matricula && matricula[1]) {
                        // Actualizar el valor del input 'name'
                        document.getElementById('matricula').value = matricula[1];
                    }
                }
            };

            // Enviar la solicitud con el valor del input
            xhr.send('registration=' + encodeURIComponent(valorInput));
        }
</script>
<!-- endbuild -->
</body>
</html>
