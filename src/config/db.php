<?php 

class db{
    //Credenciales de conexión a la base de datos
    private $host = 'localhost';
    private $usuario = 'root';
    private $pass = '';
    private $base = 'ia_interactive';

    //Conectar a la base de datos
    public function conectar(){
        $conexion_mysql = "mysql:host=$this->host;dbname=$this->base";
        $conexionBD = new PDO($conexion_mysql, $this->usuario, $this->pass);
        $conexionBD->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //Solución de problemas con codificación de caracteres (Cotejamientos)
        $conexionBD->exec("set names utf8");

        return $conexionBD;
    }

}






?>