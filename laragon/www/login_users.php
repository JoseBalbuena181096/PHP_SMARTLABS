<?php
session_start();
$_SESSION['logged'] = false;

$msg="";
$matricula="";

if(isset($_POST['matricula'])) {

  if ($_POST['matricula']==""){
    $msg.="Debe ingresar una Matrícula/Nómina <br>";
  }else {
    $matricula = strip_tags($_POST['matricula']);
    $registration_ = strtoupper($matricula);
    //momento de conectarnos a db
   $db_host        = '192.168.0.100';
   $db_user        = 'admin_iotcurso';
   $db_pass        = '181096';
   $db_database    = 'emqx'; 
   $db_port        = '4000';
   $conn = mysqli_connect($db_host,$db_user,$db_pass,$db_database,$db_port);



    if ($conn==false){
      echo "Hubo un problema al conectarse a María DB";
      die();
    }
    $result = $conn->query("SELECT * FROM `habintants` WHERE `hab_registration` = '$registration_'");
    $users = $result->fetch_all(MYSQLI_ASSOC);

    //cuento cuantos elementos tiene $tabla,
    $count = count($users);

    if ($count == 1){

      //cargo datos del usuario en variables de sesión
      $_SESSION['hab_id'] = $users[0]['hab_id'];
      $_SESSION['hab_registration'] = $users[0]['hab_registration'];

      $msg .= "Exito!!!";
      $_SESSION['logged'] = true;

      //RECUPERAMOS LOS DISPOSITIVOS DE ESTE USUARIO
      $result = $conn->query("SELECT * FROM `devices`");
      $devices = $result->fetch_all(MYSQLI_ASSOC);

      //guardamos los dispositivos en una variable de sesión
      $_SESSION['devices'] = $devices;

      //echo "<pre>";
      //print_r($devices);
      //die();

      echo '<meta http-equiv="refresh" content="2; url=dashboard_users.php">';
    }else{
      $msg .= "Acceso denegado registrate!!!";
      $_SESSION['logged'] = false;
    }
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
  <link rel="apple-touch-icon" href="../assets/images/logo.png">
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
  <!-- build:css ../assets/styles/app.min.css -->
  <link rel="stylesheet" href="assets/styles/app.css" type="text/css" />
  <!-- endbuild -->
  <link rel="stylesheet" href="assets/styles/font.css" type="text/css" />
</head>
<body>
  <div class="app" id="app">

<!-- ############ LAYOUT START-->
  <div class="center-block w-xxl w-auto-xs p-y-md">
    <div class="navbar">
      <div class="pull-center">
      </div>
    </div>
    <div class="p-a-md box-color r box-shadow-z1 text-color m-a">
    <img src="assets/images/smartlabs.png" style="height: 180px;"alt="">
      <div class="m-b text-sm text-primary _600" style="font-size: 16px">
      Ingresa con tu nómina / matrícula 
      </div>
      <form target="" method="post" name="form">
        <div class="md-form-group float-label">
          <input name="matricula" type="text" class="md-input" value="<?php echo $matricula ?>" ng-model="user.matricula" required >
          <label>matricula</label>
        </div>
        <button type="submit" class="btn primary btn-block p-x-md">Ingresar</button>
      </form>

      <div style="color:red" class="">
        <?php echo $msg ?>
      </div>
    </div>
  </div>

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

<!-- endbuild -->
</body>
</html>
