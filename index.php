<?php
$string = file_get_contents("data-1.json");
$json_a = json_decode($string,true);
$filtered = array();
$filtered_tipo = array();
$test = array();
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$dbname = 'intelcost_bienes';
$j=0;

//echo print_r($_POST,true);
//echo print_r($json_a,true);
if(isset($_POST['ciudad']) || isset($_POST['tipo'])){
  
  if(isset($_POST['ciudad'])){
    
    $ciudades = array_column($json_a, 'Ciudad');
    //echo print_r($ciudades,true);
    $counts = array_count_values($ciudades);
    $filtered = array_filter($ciudades, function ($value) use ($counts) {
      if (strcmp($value, $_POST['ciudad']) == 0) {
          return $counts[$value];
      }
    });
    //echo print_r($filtered,true);

  }
  if(isset($_POST['tipo'])){
    
    $tipos = array_column($json_a, 'Tipo');
    //echo print_r($tipos,true);
    $counts = array_count_values($tipos);
    $filtered_tipo = array_filter($tipos, function ($value) use ($counts) {
      if (strcmp($value, $_POST['tipo']) == 0) {
          return $counts[$value];
      }
    });
    //echo print_r($filtered_tipo,true);
  }
              
}
if(isset($_POST['guardardb'])){

  $conn_mls = mysqli_connect($dbhost, $dbuser, $dbpass,$dbname);

  //echo "Información del host: " . mysqli_get_host_info($conn_mls) . PHP_EOL;
  if (!$conn_mls) {
    die('Could not connect:' . mysqli_error($conn_mls));
  }
  //echo print_r($json_a[$_POST['guardardb']],true);

  $sql = "INSERT INTO bien (id,direccion,ciudad,telefono,codigo_postal,tipo,precio) VALUES ('".$json_a[$_POST['guardardb']]['Id']."','".$json_a[$_POST['guardardb']]['Direccion']."','".$json_a[$_POST['guardardb']]['Ciudad']."','".$json_a[$_POST['guardardb']]['Telefono']."','".$json_a[$_POST['guardardb']]['Codigo_Postal']."','".$json_a[$_POST['guardardb']]['Tipo']."','".$json_a[$_POST['guardardb']]['Precio']."')"; 
  //echo $sql;
    if(!mysqli_query($conn_mls, $sql)) {
        die('Error: ' . mysqli_error($conn_mls));
    }

    mysqli_close($conn_mls);
  
}


if(isset($_POST['eliminardb'])){

  $conn_mls = mysqli_connect($dbhost, $dbuser, $dbpass,$dbname);
  if (!$conn_mls) {
    die('Could not connect:' . mysqli_error($conn_mls));
  }
  $myid = $_POST['eliminardb'];
  $sql = "UPDATE bien SET eliminado=1 where id='$myid'"; 
  
    if(!mysqli_query($conn_mls, $sql)) {
        die('Error: ' . mysqli_error($conn_mls));
    }
    mysqli_close($conn_mls);
}

if(isset($_POST['submitButtonExcel'])){

  if(isset($_POST['ciudad']) || isset($_POST['tipo'])){
    if(isset($_POST['ciudad'])){
      //echo "tipo:";
      $data=$filtered;
    }else if(isset($_POST['tipo'])){
      //echo "tipo2:";
      $data=$filtered_tipo;
    }
    $file="demo.xls";

    foreach ($data as $key => $value){
      $test[$j] = '
      Direccion:,'.$json_a[$key]['Direccion'].',
      Ciudad:,'.$json_a[$key]['Ciudad'].',
      Telefono:,'.$json_a[$key]['Telefono'].',
      Codigo Postal:,'.$json_a[$key]['Codigo_Postal'].',
      Tipo:,'.$json_a[$key]['Tipo'].',
      Precio:,'.$json_a[$key]['Precio'].'';
    // echo print_r($test[$j],true);

      $j++;
    }
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=$file");
    echo implode("\t",$test) ."\n";

  }else if(empty($filtered)){
    echo "no ha seleccionado ciudad";
  }else if(empty($filtered_tipo)){
    echo "no ha seleccionado tipo";
  }
}

function cleanData( &$str ) {
  $str = preg_replace( "/\t/", "\\t", $str );
  $str = preg_replace("/\r?\n/", "\\n", $str);
}
    // [ciudad] => Houston
    // [tipo] => 
    // [precio] => 200;80000

// Array
// (
//     [guardardb] => 3
// )
  
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>
  <link type="text/css" rel="stylesheet" href="css/customColors.css"  media="screen,projection"/>
  <link type="text/css" rel="stylesheet" href="css/ion.rangeSlider.css"  media="screen,projection"/>
  <link type="text/css" rel="stylesheet" href="css/ion.rangeSlider.skinFlat.css"  media="screen,projection"/>
  <link type="text/css" rel="stylesheet" href="css/index.css"  media="screen,projection"/>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Formulario</title>
</head>

<body>
  <div class="contenedor">
    <div class="card rowTitulo">
      <h1>Bienes Intelcost</h1>
    </div>
    <div class="colFiltros">
      <form action="#" method="post" id="formulario">
        <div class="filtrosContenido">
          <div class="tituloFiltros">
            <h5>Filtros</h5>
          </div>
          <div class="filtroCiudad input-field">
            <p><label for="selectCiudad">Ciudad:</label><br></p>
            <select name="ciudad" id="selectCiudad">
              <option value="" selected>Elige una ciudad</option>
              <?php 
               
                $ciudades = array_column($json_a, 'Ciudad');
                sort($ciudades); // optional
                $ciudad_unique = array_unique($ciudades);
                
                foreach ($ciudad_unique as $key => $value) {
                  # code...
                  echo "<option>".$value."</option>";
                }
                ?>
            </select>
          </div>
          <div class="filtroTipo input-field">
            <p><label for="selecTipo">Tipo:</label></p>
            <br>
            <select name="tipo" id="selectTipo">
              <option value="">Elige un tipo</option>
              <?php 
               
                $tipos = array_column($json_a, 'Tipo');
                sort($tipos); // optional
                $tipo_unique = array_unique($tipos);
                
                foreach ($tipo_unique as $key => $value) {
                  # code...
                  echo "<option>".$value."</option>";
                }
                ?>
            </select>
          </div>
          <div class="filtroPrecio">
            <label for="rangoPrecio">Precio:</label>
            <input type="text" id="rangoPrecio" name="precio" value="" />
          </div>
          <div class="botonField">
            <input type="submit" class="btn white" value="Buscar" id="submitButton">
          </div>
        </div>
      </form>
    </div>
    <div id="tabs" style="width: 75%;">
      <ul>
        <li><a href="#tabs-1">Bienes disponibles</a></li>
        <li><a href="#tabs-2">Mis bienes</a></li>
        <li><a href="#tabs-3">Reportes</a></li>
      </ul>
      <div id="tabs-1">
        <div class="colContenido" id="divResultadosBusqueda">
          <div class="tituloContenido card" style="justify-content: center;">
            <h5>Resultados de la búsqueda:</h5>
            <form action="#" method="post" id="formulariobusqueda" style="width: inherit;">
            <?php
           
            if(isset($filtered) && count($filtered)>0 ){
     
              foreach ($filtered as $key => $value){              
                ?>
                <div style="width: inherit; margin-bottom: 20px;">
                <img src="img/home.jpg" border="1" alt="Esta es la imagen" width="200" height="150" style="float:left; padding-bottom: 20px;">
                <span><b>Direccion:</b><?php echo $json_a[$key]['Direccion'];?></span></br>
                <span><b>Ciudad:</b><?php echo $json_a[$key]['Ciudad'];?></span></br>
                <span><b>Telefono:</b><?php echo $json_a[$key]['Telefono'];?></span><br>
                <span><b>Codigo Postal:</b><?php echo $json_a[$key]['Codigo_Postal'];?></span></br>
                <span><b>Tipo:</b><?php echo $json_a[$key]['Tipo'];?></span></br>
                <span><b>Precio:</b><?php echo $json_a[$key]['Precio'];?></span></br>
                <button type="submit" class="btn" name="guardardb" value="<?php echo $json_a[$key]['Id']?>">GUARDAR</button>
                <hr>
                </div>
                <br>
                <?php
              }
            }else if(isset($filtered_tipo) && count($filtered_tipo)>0 ){

              foreach ($filtered_tipo as $key => $value){              
                ?>
                <div style="width: inherit; margin-bottom: 20px;">
                <img src="img/home.jpg" border="1" alt="Esta es la imagen" width="200" height="150" style="float:left; padding-bottom: 20px;">
                <span><b>Direccion:</b><?php echo $json_a[$key]['Direccion'];?></span></br>
                <span><b>Ciudad:</b><?php echo $json_a[$key]['Ciudad'];?></span></br>
                <span><b>Telefono:</b><?php echo $json_a[$key]['Telefono'];?></span><br>
                <span><b>Codigo Postal:</b><?php echo $json_a[$key]['Codigo_Postal'];?></span></br>
                <span><b>Tipo:</b><?php echo $json_a[$key]['Tipo'];?></span></br>
                <span><b>Precio:</b><?php echo $json_a[$key]['Precio'];?></span></br>
                <button type="submit" class="btn" name="guardardb" value="<?php echo $json_a[$key]['Id']?>">GUARDAR</button>
                <br>
                <hr>
                </div>
                <br>
                <?php
              }
            }else if(empty($filtered) ){

              foreach ($json_a as $key => $value){
                ?>
                <div style="width: inherit; margin-bottom: 20px;">
                <img src="img/home.jpg" border="1" alt="Esta es la imagen" width="200" height="150" style="float:left; padding-bottom: 20px;">
                <br>
                <span><b>Direccion:</b><?php echo $value['Direccion'];?></span></br>
                <span><b>Ciudad:</b><?php echo $value['Ciudad'];?></span></br>
                <span><b>Telefono:</b><?php echo $value['Telefono'];?></span><br>
                <span><b>Codigo Postal:</b><?php echo $value['Codigo_Postal'];?></span></br>
                <span><b>Tipo:</b><?php echo $value['Tipo'];?></span></br>
                <span><b>Precio:</b><?php echo $value['Precio'];?></span>
                <br>
                <hr>
                </div>
                <br>
                <?php
              }
            }
            ?>
            </form>
            <div class="divider"></div>
          </div>
        </div>
      </div>
      
      <div id="tabs-2" >
        <div class="colContenido" id="divResultadosBusqueda">
          <div class="tituloContenido card" style="justify-content: center;">
            <h5>Bienes guardados:</h5>
            <form action="#" method="post" id="formulariobusqueda" style="width: inherit;">
            <?php
            $conn_mls = mysqli_connect($dbhost, $dbuser, $dbpass,$dbname);

            if (!$conn_mls) {
              die('Could not connect:' . mysqli_error($conn_mls));
            }

            $sql = "SELECT * FROM bien WHERE eliminado=0";
            $result = $conn_mls->query($sql);
            
            if ($result->num_rows > 0) {
              // output data of each row
              while($value = $result->fetch_assoc()) {
                //echo "Direccion: " . $value["direccion"]. " - Ciudad: " . $value["ciudad"]. " " . $value["telefono"]. "<br>";
                  ?>
                  <div style="width: inherit; margin-bottom: 20px;">
                  <img src="img/home.jpg" border="1" alt="Esta es la imagen" width="200" height="150" style="float:left; padding-bottom: 20px;">
                  <span><b>Direccion:</b><?php echo $value['direccion'];?></span></br>
                  <span><b>Ciudad:</b><?php echo $value['ciudad'];?></span></br>
                  <span><b>Telefono:</b><?php echo $value['telefono'];?></span><br>
                  <span><b>Codigo Postal:</b><?php echo $value['codigo_postal'];?></span></br>
                  <span><b>Tipo:</b><?php echo $value['tipo'];?></span></br>
                  <span><b>Precio:</b><?php echo $value['precio'];?></span></br>
                  <button type="submit" class="btn" name="eliminardb" value="<?php echo $value['id']?>">ELIMINAR</button>
                  <hr>
                  </div>
                  <br>
                  <?php
                }
              } else {
                echo "0 results";
              }
              $conn_mls->close();
              ?>
            <div class="divider"></div>
          </div>
        </div>
      </div>

      <div id="tabs-3" >
        <div class="colContenido" id="divResultadosReporte">
          <div class="tituloContenido card" style="justify-content: center;">
            <h5>Exportar Reporte:</h5>
            <div class="divider"></div>
            <form action="#" method="post" id="formularioreporte" style="width: inherit;">
              <div class="filtrosContenido">
                <div class="tituloFiltros">
                  <h5>Filtros</h5>
                </div>
                <div class="filtroCiudad input-field">
                  <p><label for="selectCiudad">Ciudad:</label><br></p>
                  <select name="ciudad" id="selectCiudad">
                    <option value="" selected>Elige una ciudad</option>
                    <?php 
                    
                      $ciudades = array_column($json_a, 'Ciudad');
                      sort($ciudades); // optional
                      $ciudad_unique = array_unique($ciudades);
                      
                      foreach ($ciudad_unique as $key => $value) {
                        # code...
                        echo "<option>".$value."</option>";
                      }
                      ?>
                  </select>
                </div>
                <div class="filtroTipo input-field">
                  <p><label for="selecTipo">Tipo:</label></p>
                  <br>
                  <select name="tipo" id="selectTipo">
                    <option value="">Elige un tipo</option>
                    <?php 
                    
                      $tipos = array_column($json_a, 'Tipo');
                      sort($tipos); // optional
                      $tipo_unique = array_unique($tipos);
                      
                      foreach ($tipo_unique as $key => $value) {
                        # code...
                        echo "<option>".$value."</option>";
                      }
                      ?>
                  </select>
                </div>
                <div class="botonField">
                  <input type="submit" class="btn white" value="GENERAR EXCEL" name="submitButtonExcel" id="submitButtonExcel">
                </div>
              </div>
            </form>
              <?php
            
              ?>
            </form>
          </div>
        </div>
      </div>
    </div>


    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    
    <script type="text/javascript" src="js/ion.rangeSlider.min.js"></script>
    <script type="text/javascript" src="js/materialize.min.js"></script>
    <script type="text/javascript" src="js/index.js"></script>
    <script type="text/javascript" src="js/buscador.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script type="text/javascript">
      $( document ).ready(function() {
          $( "#tabs" ).tabs();
      });
    </script>
  </body>
  </html>
