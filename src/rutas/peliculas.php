<?php
//Elementos necesarios para el correcto funcionamiento del framework SLIM
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;

/*
@autor Tatiana Hernández Villanueva

A continuación encontraremos las diferentes funciones que llevará a cabo la API como son
- Mostrar todas las películas de la base de datos
- Mostrar una pelicula en particular mediante su id
- Registrar una nueva película
- Modificar los datos de una película
- Eliminar la película registrada

Todas estas funciones son funciones REST, el cliente hace una petición sobre qué desea hacer
y la API se encarga de dar una respuesta. Como resultado, en algunas de ellas, se retorna
una variable de tipo JSON que contiene toda la información solicitada.
*/


/******* LISTAR TODAS LAS PELICULAS ********/

$app->get('/api/peliculas', function(Request $request, Response $response){

    $consulta = 'SELECT * FROM peliculas'; //Variable que guarda la consulta para ejecutar

    try{
        //instancia base de datos
        $db = new db();

        //conexion a la base de datos
        $db = $db->conectar();
        $ejec = $db->query($consulta);
        $peliculas = $ejec->fetchAll(PDO::FETCH_OBJ);
        
        //Limpieza de la variable para evitar residuos
        $db = null;

        //Exportación de los datos resultantes a formato JSON y los imprime en pantalla
        echo json_encode($peliculas, JSON_UNESCAPED_UNICODE);


    }catch(PDOException $e){
        echo '{"error":{"text":' .$e->getMessage().'}';
    }

});


/******* LISTAR PELICULA EN PARTICULAR POR ID ********/

$app->get('/api/peliculas/{id}', function(Request $request, Response $response){

    $id = $request->getAttribute('id');

    $consulta = "SELECT * FROM peliculas WHERE id = '$id'";

    try{
        //instancia base de datos
        $db = new db();

        //conexion a la base de datos
        $db = $db->conectar();
        $ejec = $db->query($consulta);
        $pelicula = $ejec->fetchAll(PDO::FETCH_OBJ);
        

        //Exportación de los datos resultantes a formato JSON y los imprime en pantalla
        echo json_encode($pelicula, JSON_UNESCAPED_UNICODE);


    }catch(PDOException $e){
        echo '{"error":{"text":' .$e->getMessage().'}';
    }

});


/******* REGISTRAR PELICULAS NUEVAS  ********/

$app->post('/api/peliculas/agregar', function(Request $request, Response $response){

    //Se obtienen las variables que fueron enviadas por el método POST a través del ajax en down_value.js 
    $titulo = $_POST['titulo'];
    $sinopsis = $_POST['sinopsis'];
    $resena = $_POST['resena'];
    $fecha = $_POST['fecha'];

    $target_path = "../public/images/"; //Dirección donde se guardarán las nuevas imágenes.

    $poster = "".basename($_FILES['poster']['name'][0]); //Esta variable guarda el valor del nombre del archivo que fue
    //seleccionado desde el formulario. Ya que se permite subir 1 o más archivos, sus nombres son guardados en un array automáticamente
    //así que nos interesa acceder al nombre del primer elemento, en nuestro caso sólo será un archivo que se situa en la primer posición.

    //Consulta para registrar los valores en la base de datos
    $consulta = "INSERT INTO peliculas (titulo, sinopsis, poster, resena, fecha) VALUES (:titulo, :sinopsis, :poster, :resena, :fecha)";        

    try{
        //instancia base de datos
        $db = new db();

        //conexion a la base de datos
        $db = $db->conectar();

        //Preparación de los datos para colocarlos de manera más segura
        $stmt = $db->prepare($consulta);
        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':sinopsis', $sinopsis);
        $stmt->bindParam(':poster', $poster);
        $stmt->bindParam(':resena', $resena);
        $stmt->bindParam(':fecha', $fecha);
        //Ejecución de la consulta en la base de datos con los valores correctamente preparados
        $stmt->execute();
        echo '{"Notice":{"text": "Pelicula registrada correctamente"}';


    }catch(PDOException $e){
        echo '{"error":{"text":' .$e->getMessage().'}';
    }
    
    //Subir la imagen al servidor o carpeta de interés
    if (strlen($_FILES['poster']['name'][0]) > 1) { //Garantiza que la cant de caracteres del nombre sea mayor a 1 (No es esencial).
        if (move_uploaded_file($_FILES['poster']['tmp_name'][0], $target_path.$poster)) {
            //Línea de código generalmente usada para llevar a cabo el "movimiento o la copia" del archivo al lugar deseado.
        }else{echo "Error, no se ha subido el archivo";}
        }

        
});


/******* ACTUALIZAR PELICULA POR ID  ********/

$app->post('/api/peliculas/editar/{id}', function(Request $request, Response $response){

    $id = $request->getAttribute('id');

    $titulo = $request->getParam('titulo');
    $sinopsis = $request->getParam('sinopsis');
    $poster = $request->getParam('poster');
    $resena = $request->getParam('resena');
    $fecha = $request->getParam('fecha');


    $consulta = "UPDATE peliculas SET
                titulo = :titulo,
                sinopsis = :sinopsis, 
                poster = :poster, 
                resena = :resena,  
                fecha = :fecha
                WHERE id = $id";

    try{
        //instancia base de datos
        $db = new db();

        //conexion
        $db = $db->conectar();

        $stmt = $db->prepare($consulta);
        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':sinopsis', $sinopsis);
        $stmt->bindParam(':poster', $poster);
        $stmt->bindParam(':resena', $resena);
        $stmt->bindParam(':fecha', $fecha);

        $stmt->execute();
        echo '{"Notice":{"text": "Pelicula modificada correctamente"}';


    }catch(PDOException $e){
        echo '{"error":{"text":' .$e->getMessage().'}';
    }

});


/******* ELIMINAR PELICULA POR ID  ********/

$app->delete('/api/peliculas/eliminar/{id}', function(Request $request, Response $response){

    $id = $request->getAttribute('id');


    $consulta = "DELETE FROM peliculas WHERE id = $id";

    try{
        //instancia base de datos
        $db = new db();

        //conexion
        $db = $db->conectar();

        $stmt = $db->prepare($consulta);
        $stmt->execute();
        
        $db = null;
        
        echo '{"Notice":{"text": "Pelicula eliminada correctamente"}';


    }catch(PDOException $e){
        echo '{"error":{"text":' .$e->getMessage().'}';
    }

});







