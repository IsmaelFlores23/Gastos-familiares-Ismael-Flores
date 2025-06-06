
<?php
    require 'conexion.php';

    $error="";
    $hay_post = false;
    $nombre = "";
    $gastos = "";
    $pais = "";
    // $codigoUsuario = null;
    if(isset($_REQUEST['submit1'])){
        $hay_post = true;
        $nombre = isset($_REQUEST['txtNombre']) ? $_REQUEST['txtNombre'] : "";
        $sexo = isset($_REQUEST['radiogastos']) ? $_REQUEST['radiogastos'] : "";
        $pais = isset($_REQUEST['cmbPais']) ? $_REQUEST['cmbPais'] : "";

        if(!empty($nombre)){
            $nombre = preg_replace("/[^a-zA-ZáéíóúÁÉÍÓÚ]/u","",$nombre);
        }
        else{
            $error .= "El nombre no puede esta vácio<br>";
        }


        if($gastos == ""){
            $error .= "Seleccione un gasto.<br>";
        }
        
        if($pais==""){
            $error .= "Seleccione un país";
        }

        if(!$error){
            $stm_insertarRegistro = $conexion->prepare("insert into cliente(nombreUsuario, sexo, pais) values(:nombre, :sexo, :pais)");
            $stm_insertarRegistro->execute([':nombre'=>$nombre, ':gastos'=>$gastos, ':pais'=>$pais]);
            header("Location: index.php?mensaje=registroGuardado");
            exit();
        }
    }
    
    if(isset($_REQUEST['submit2'])){
        $codigoUsuario = $_REQUEST['id'];
        $nombre = isset($_REQUEST['txtNombre']) ? $_REQUEST['txtNombre'] : "";
        $sexo = isset($_REQUEST['radiogastos']) ? $_REQUEST['radiogastos'] : "";
        $pais = isset($_REQUEST['cmbPais']) ? $_REQUEST['cmbPais'] : "";

        if(!empty($nombre)){
            $nombre = preg_replace("/[^a-zA-ZáéíóúÁÉÍÓÚ]/u","",$nombre);
        }
        else{
            $error .= "El nombre no puede esta vácio<br>";
        }

        if($sexo == ""){
            $error .= "Seleccione un gasto.<br>";
        }
        
        if($pais==""){
            $error .= "Seleccione un país";
        }

        if(!$error){
            $stm_modificar = $conexion->prepare("update cliente set nombreUsuario = :nombre, sexo = :sexo, pais = :pais where codigoUsuario = :id");
            $stm_modificar->execute([
                ':nombre'=>$nombre, 
                ':gastos'=>$gastos, 
                ':pais'=>$pais,
                ':id'=> $codigoUsuario
            ]);
            header("Location: index.php?mensaje=registroModificado");
            exit();
        }
    }
    
    if(isset($_REQUEST['id']) && isset($_REQUEST['op'])){
        $id = $_REQUEST['id'];
        $op = $_REQUEST['op'];
        
        if($op == 'm'){
            // $stm_seleccionarRegistro = $conexion->prepare("update cliente set nombreUsuario=:nombre, sexo=:sexo, pais:pais");
            $stm_seleccionarRegistro = $conexion->prepare("select * from cliente where codigoUsuario=:id");
            $stm_seleccionarRegistro->execute([':id'=>$id]);
            $resultado = $stm_seleccionarRegistro->fetch();
            $codigoUsuario = $resultado['codigoUsuario'];
            $nombre = $resultado['nombreUsuario'];
            $sexo = $resultado['gastos'];
            $pais = $resultado['pais'];
        }
        else if($op == 'e'){
            $stm_eliminar = $conexion->prepare("delete from cliente where codigoUsuario = :id");
            $stm_eliminar->execute([':id'=>$id]);
            header("Location: index.php?mensaje=registroEliminado");
            exit();
        }
    }

    

        

    /* $sql = 'select * from cliente';
    $resultado = $conexion->query($sql);
    foreach($resultado as $registro){
        print($registro['nombreUsuario']);
        print($registro['sexo']);
        print($registro['pais']);
    } */

    //$id = 1;    

    $stm = $conexion->prepare("select * from cliente");
    $stm->execute([]);
    $resultados = $stm->fetchAll();
   /*  foreach ($resultados as $registro) {
        echo $registro['nombreUsuario'].'<br>';
        echo $registro['sexo'].'<br>';
        echo $registro['pais'].'<br>'.'<br>';
    }  */
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <h1 class="text-center">CRUD</h1>
    <div class="container">
        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
            <input type="hidden" name="id" value="<?php echo isset($codigoUsuario)? $codigoUsuario : "" ?>">
            <label class="form-label" for="nombre">Nombre Completo:</label>
            <input class="form-control" type="text" name="txtNombre" id="nombre" value="<?php echo isset($nombre)? $nombre : "" ?>"><br>


            <label class="form-label" for="gastos">Ingrese sus Gastos :</label>
            <input class="form-control" type="text" name="txtNombre" id="gastos" value="<?php echo isset($nombre)? $nombre : "" ?>"><br>
            
            <label class="form-label" for="alimen">Alimentacion</label>
            <input class="form-check-input" type="radio" name="radiogastos" id="alimen" value="Alimentacion" <?php  if($gastos=='Alimentacion'){echo "checked";} ?> >
            
            <label class="form-label" for="transp">Transporte</label>
            <input class="form-check-input" type="radio" name="radiogastos " id="transp" value="Transporte" <?php if($gastos=='Tra'){ echo "checked"; } ?> ><br>
            
            <label class="form-label" for="pais">País</label>
            <select class="form-select" name="cmbPais" id="pais">
                <option value="">Seleccione un país</option>
                <option value="Honduras" <?php echo ($pais=='Honduras')? 'selected' : '' ?> >Honduras</option>
                <option value="Guatemala" <?php echo ($pais=='Guatemala')? 'selected' : '' ?>>Guatemala</option>
                <option value="Mexico" <?php echo  ($pais=='Mexico')? 'selected' : '' ?>>Mexico</option>
            </select><br>
            <input class="btn btn-primary" type="submit" value="Enviar" name="submit1">
            <input class="btn btn-dark" type="submit" value="Modificar" name="submit2">
            <a class="btn btn-secondary" href="index.php">Limpiar</a>
        </form>
        <br>
        <?php
        if($error){
            echo "<p class='alert alert-danger' role='alert'>$error</p>";
        }

        if(isset($_REQUEST['mensaje'])){
            $mensaje = $_REQUEST['mensaje'];
            if($mensaje=='registroGuardado'){
                echo "<p class='alert alert-success'>Registro guardado.</p>";
            }
            elseif($mensaje == 'registroModificado'){
                echo "<p class='alert alert-success'>Registro modificado.</p>";
            }
            elseif($mensaje=='registroEliminado'){
                echo "<p class='alert alert-success'>Registro eliminado.</p>";
            }
        }
        ?>

    <table class="table table-bordered table-hover">
        <thead>
            <th>Nombre</th>
            <th>Sexo</th>
            <th>País</th>
            <th colspan="2">Acciones</th>
        </thead>
        <tbody>
            <?php foreach($resultados as $registro): ?>
                <tr>
                    <td><?php echo $registro['nombreUsuario']; ?></td>
                    <td><?php echo $registro['sexo']; ?></td>
                    <td><?php echo $registro['pais']; ?></td>
                    <td><a class="btn btn-primary" href="index.php?id=<?php echo $registro['codigoUsuario'] ?>&op=m">Modificar</a></td>
                    <td><a class="btn btn-danger" href="index.php?id=<?php echo $registro['codigoUsuario'] ?>&op=e">Eliminar</a></td>
                    <?php endforeach; ?>
                </tr>
        </tbody>
    </table>
</div>
</body>
</html>