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

// Verificar si se ha enviado el formulario
if (isset($_GET['serie_device'])) {
    //momento de conectarnos a db
    $db_host        = '192.168.0.100';
    $db_user        = 'admin_iotcurso';
    $db_pass        = '181096';
    $db_database    = 'emqx'; 
    $db_port        = '4000';
    $conn = mysqli_connect($db_host,$db_user,$db_pass,$db_database,$db_port);
 
    // Verificar la conexión
    if ($conn->connect_error) {
        die("Fail: " . $conn->connect_error);
    }

    // Recuperar el valor del input del formulario
    $device = $_GET['serie_device'];

    // Consulta SQL para filtrar dispositivos que coincidan con el input
    //$sql = "SELECT * FROM traffic_devices WHERE traffic_device = '$device'";
    $sql = "SELECT * FROM traffic_devices WHERE traffic_device = '$device' ORDER BY traffic_date DESC LIMIT 12";
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
              <li>
                <a href="becarios.php" >
                  <span class="nav-icon">
                    <i class="fa fa-user-plus"></i>
                  </span>
                  <span class="nav-text">Becarios</span>
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
            <div class="col-xs-12 col-sm-9">
              <div class="box p-a">
                <div class="form-group row">

                  <button onclick="command('open')" class="md-btn md-raised m-b-sm w-xs green" style="margin-left:25px">TURN ON</button>
                  <button onclick="command('close')" class="md-btn md-raised m-b-sm w-xs red"  style="margin-left:25px">TURN OFF</button>

                  <div class="form-group" style="margin-left:25px">
                    <select  id="device_id" class="form-control select2" ui-jp="select2" ui-options="{theme: 'bootstrap'}">
                      <?php foreach ($devices as $device ) { ?>
                        <option value="<?php echo  $device['devices_serie']?>"> <?php echo $device['devices_alias'] ?> </option>
                      <?php } ?>
                    </select>
                  </div>

                <form target="" method="get" name="form" class="form-group row" style="margin-left:25px">
                    <input name="serie_device" id = "serie_device" type="text" class="md-input"  required >
                    <br>
                    <br>
                    <button type="submit" class="btn primary">CHECK TRAFFIC</button>
                </form>

                </div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-3">
              <div class="box p-a">
                <div class="pull-left m-r">
                  <span class="w-48 rounded  accent">
                    <i class="fa fa-sun-o"></i>
                  </span>
                </div>
                <div class="clear">
                  <h4 class="m-0 text-lg _300"><b id="display_temp1">-- </b><span class="text-sm"> °C</span></h4>
                  <small class="text-muted">TEMPERATURA ESP32</small>
                </div>
              </div>
            </div>

          </div>

          
          <!-- VALORES EN TIEMPO REAL -->
          <div class="row">
            <div class="col-sm-12">
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
                            if (!empty($users_traffic_device)) {
                                foreach ($users_traffic_device as $traffic): ?>
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
function generarCadenaAleatoria(longitud) {
  const caracteres = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
  let cadenaAleatoria = '';

  for (let i = 0; i < longitud; i++) {
    const indiceAleatorio = Math.floor(Math.random() * caracteres.length);
    cadenaAleatoria += caracteres.charAt(indiceAleatorio);
  }

  return cadenaAleatoria;
}

const cadenaAleatoria = generarCadenaAleatoria(6);


function command(action){
  var device_serie = $( "#device_id" ).val();
  console.log(device_serie);
  if(action == "open"){
    client.publish(device_serie + "/command", 'open', (error) => {
      console.log(error || 'Abriendo!!!')
    });
  }else{
    client.publish(device_serie + "/command", 'close', (error) => {
      console.log(error || 'Cerrando!!!')
    });
  }
}

var audio = new Audio('audio.mp3');
function process_msg(topic, message){
  var msg = message.toString();
  var splitted_topic = topic.split("/");
  var serial_number = splitted_topic[0];
  var query = splitted_topic[1];

  if (query == "temp"){
    $("#display_temp1").html(msg);
  }

  if (query == "access_query"){
    $("#display_new_access").html("Nuevo acceso: " + msg);
    audio.play();

    setTimeout(function(){
      $("#display_new_access").html("");
    }, 3000);

  }
}

/*
******************************
****** CONEXION  *************
******************************
*/
// connect options
const options = {
      // Authentication
      clientId: 'iotmc'+generarCadenaAleatoria(6),
      username: 'jose',
      password: 'public',
      keepalive: 60,
      clean: true,
      connectTimeout: 4000,
}

var connected = false;

// WebSocket connect url
const WebSocket_URL = 'wss://192.168.0.100:8074/mqtt'
const client = mqtt.connect(WebSocket_URL, options);

client.on('connect', () => {
    console.log('Mqtt conectado por WS! Exito!')

    <?php foreach ($devices as $device) { ?>
      client.subscribe('<?php echo $device['devices_serie'] ?>/access_query', { qos: 0 }, (error) => {})
      client.subscribe('<?php echo $device['devices_serie'] ?>/temp', { qos: 0 }, (error) => {})
    <?php } ?>

    // publish(topic, payload, options/callback)
    client.publish('fabrica', 'esto es un verdadero éxito', (error) => {
      console.log(error || 'Mensaje enviado!!!');
    })
})

client.on('message', (topic, message) => {
  console.log('Mensaje recibido bajo tópico: ', topic, ' -> ', message.toString());
  process_msg(topic, message);
})

client.on('reconnect', (error) => {
    console.log('Error al reconectar', error)
})

client.on('error', (error) => {
    console.log('Error de conexión:', error)
})


setInterval(function () { 
    var input_serie = document.getElementById("serie_device");
    input_serie.value = $( "#device_id" ).val();
}, 500);

</script>
<!-- endbuild -->
</body>
</html>
