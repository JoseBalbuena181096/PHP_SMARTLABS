<?php
session_start();
$logged = $_SESSION['logged'];

if(!$logged){
  echo "Ingreso no autorizado";
  die();
}

$devices = $_SESSION['devices'];
$users_traffic_device = [];
$time_full = 0;
$jobs_ = 0;

$array = array();

foreach ($devices as $device ) {
  array_push($array,$device['devices_serie']);
}

//momento de conectarnos a db
$db_host        = '192.168.0.100';
$db_user        = 'admin_iotcurso';
$db_pass        = '181096';
$db_database    = 'emqx'; 
$db_port        = '4000';
$conn = mysqli_connect($db_host,$db_user,$db_pass,$db_database,$db_port);
 
// Verificar la conexión
if ($conn==false){
    echo "Hubo un problema al conectarse a María DB";
    die();
}

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

// Verificar si se ha enviado el formulario
if (isset($_GET['serie_device']) && isset($_GET['start_date']) && isset($_GET['end_date']) && isset($_GET['matricula'])) {

    // Recuperar el valor del input del formulario
    $device = $_GET['serie_device'];
    $start_date = $_GET['start_date'];
    $end_date = $_GET['end_date'];
    $matricula_ = strip_tags($_GET['matricula']);
    $matricula_ = strtoupper($matricula_);
    // Consulta SQL para filtrar dispositivos que coincidan con el input
    //$sql = "SELECT * FROM traffic_devices WHERE traffic_device = '$device'";
    if($matricula_ == ""){
        $sql = "SELECT *
        FROM traffic_devices
        WHERE traffic_device = '$device'
        AND traffic_date BETWEEN '$start_date' AND '$end_date'
        ORDER BY traffic_date ASC;";
    }
    else{
        $sql = "SELECT *
        FROM traffic_devices
        WHERE traffic_device = '$device'
        AND hab_registration = '$matricula_'
        AND traffic_date BETWEEN '$start_date' AND '$end_date'
        ORDER BY traffic_date ASC;";
    }
    $result = $conn->query($sql);
    $users_traffic_device= $result->fetch_all(MYSQLI_ASSOC);
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

          <!-- SWItCH1 y 2 -->
          <div class="row">
            <!-- SWItCH1 -->
            <div class="col-sm-12">
              <div class="box p-a">
                <div class="form-group row">
                  <div class="form-group" style="margin-left:25px">
                    <select  id="device_id" class="form-control select2" ui-jp="select2" ui-options="{theme: 'bootstrap'}">
                      <?php foreach ($devices as $device ) { ?>
                        <option value="<?php echo  $device['devices_serie']?>"> <?php echo $device['devices_alias'] ?> </option>
                      <?php } ?>
                    </select>
                    <br>
                    <br>
                    <div class="md-form-group" style="margin-left:10px;margin-right:10px;">
                        <input name="registration" id="registration" type="text" class="md-input" placeholder="Ej: A01736666" value="" onchange="enviarDatos()" required>
                        <label>INGRESE MATRICULA y/o NOMINA: </label>
                    </div>
                    <br>
                    <br>
                    <div class="md-form-group" style="margin-left:10px;margin-right:10px;">
                        <input name="name" id='name'type="text" class="md-input" value="" required>
                        <label>NOMBRE: </label>
                    </div>
                  </div>

                  
                  <div class="box"  style="margin-left:25px">
                    <div class="box-header">
                      <h2>CONSULTAR ESTADÍSTICAS  </h2>
                    </div>
                    <form target="" method="get" name="form" class="form-group row" style="margin-left:25px">
                        <div class="md-form-group" style="margin-left:25px">
                            <input type="date" name="start_date" id="start_date" placeholder="Fecha de inicio" class="md-input" value="" required>
                            <label>Fecha de inicio: </label>
                        </div>
                        <div class="md-form-group" style="margin-left:25px">
                            <input type="date" name="end_date" id="end_date" placeholder="Fecha de finalización" class="md-input" value="" required>
                            <label>Fecha de finalización: </label>
                        </div>
                        <input name="serie_device" id = "serie_device" type="text" class="md-input" style="margin:20px;"  required>
                        <input name="matricula" id='matricula'type="text" class="md-input" value="" style="margin:20px;">
                        <button type="submit" class="btn primary"  style="margin-left:25px">GENERAR REPORTE</button>
                    </form>
                    <br>
                  </div>
                </div>
              </div>
            </div>
          </div>

          
          <!-- VALORES EN TIEMPO REAL -->
          <div class="row">
            <div class="col-sm-9">
                  <div class="box">
                    <div class="box-header">
                      <h2>USER TRAFFIC</h2>
                      <small>
                        DEVICE USER USAGE <?php echo $_SESSION['users_email'] ?>.
                      </small>
                    </div>
                    <table class="table table-striped b-t">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>DATE</th>
                          <th>NAME</th>
                          <th>REGISTER</th>
                          <th>EMAIL</th>
                          <th>DEVICE SERIE</th>
                          <th>DEVICE STATE</th>
                        </tr>
                      </thead>
                      <tbody>

                      <?php
                            
                            $time_start = 0;
                            $time_end = 0;
                            $detal_time = 0;

                            if (!empty($users_traffic_device)) {
                                
                                foreach ($users_traffic_device as $traffic): ?>
                                    <?php if ($traffic['traffic_state'] == 1) { 
                                        $time_start = strtotime($traffic['traffic_date']);
                                        $jobs_ += 1;
                                     }else{
                                        $time_end = strtotime($traffic['traffic_date']);
                                    }   
                                    ?>
                                    <?php if (($time_start > 0 && $time_end > 0)  && ($time_end > $time_start)) { 
                                       $detal_time =  $time_end -  $time_start;
                                       $time_full += $detal_time;
                                       $time_start = 0;
                                       $time_end = 0;
                                     }  
                                    ?>
                                <tr>
                                    <td><?php echo $traffic['traffic_id'] ?></td>
                                    <td><?php echo $traffic['traffic_date'] ?></td>
                                    <td><?php echo $traffic['hab_name'] ?></td>
                                    <td><?php echo $traffic['hab_registration'] ?></td>
                                    <td><?php echo $traffic['hab_email'] ?></td>
                                    <td><?php echo $traffic['traffic_device'] ?></td>
                                    <td><?php echo $traffic['traffic_state'] ?></td>
                                </tr>
                                <?php endforeach;
                            } else {
                                // Código a ejecutar si $users_traffic_device está vacío
                                echo "NO DATA AVAILABLE";
                            }
                        ?>
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="col-sm-3">
                  <div class="box">
                    <div class="box-header">
                      <h1 style="text-align:center;">Horas de uso: </h1>
                      <br>
                      <h1 style="color:#0cc2aa;text-align:center;font-weight: bold;"><?php echo round($time_full/ 3600, 4); ?></h1>
                  </div>
                  <div class="box">
                    <div class="box-header">
                      <h1 style="text-align:center;">Número de veces usado: </h1>
                      <br>
                      <h1 style="color:#0cc2aa;text-align:center;font-weight: bold;"><?php echo  $jobs_; ?></h1>
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
            xhr.open('POST', 'horas_uso.php', true);
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

setInterval(function () { 
    var input_serie = document.getElementById("serie_device");
    input_serie.value = $( "#device_id" ).val();
}, 500);

</script>
<!-- endbuild -->
</body>
</html>
