<?php

use Dotenv\Dotenv;

require __DIR__.'/../../vendor/autoload.php';

class DatabaseManager
{
    private $env;
    private $params;
    public $conn;

    public function __construct()
    {
        $this->env = Dotenv::createImmutable(dirname(__DIR__.'/../../.env'));
        $this->env->load();

        $this->params = array(
            'host' => $_ENV['MYSQL_HOST'],
            'user' => $_ENV['MYSQL_USER'],
            'password' => $_ENV['MYSQL_PASSWORD'],
            'dbname' => $_ENV['MYSQL_DATABASE'],
            'driver' => 'pdo_mysql',
        );

        $this->conn = new mysqli($this->params['host'], $this->params['user'], $this->params['password'],$this->params['dbname']) or die("Connect failed: %s\n". $this->conn->error);

    }

    private function closeCon()
    {
        $this->conn->close();
    }

    public function querySinglDoctrine($className,$id)
    {
        $query = 'SELECT * FROM '.$className.' WHERE id = '.$id;
        $result = $this->conn->query($query)->fetch_array(MYSQLI_ASSOC);
        $this->closeCon();

        return  json_encode($result);
    }

    public function queryAllDoctrine($className)
    {
        $query = 'SELECT * FROM '.$className;
        $result = $this->conn->query($query)->fetch_all(MYSQLI_ASSOC);
        $this->closeCon();

        return  json_encode($result);
    }

    public function queryInsertData($className,$data)
    {
        if (empty($data['msg'])) {
            return false;
        }

        $date = date("Y-m-d H:i:s", time());
        $json = $data['content'] ? $data['content'] : null;
        $code = $data['code'] ? $data['code'] : null;
        $msg =  $data['msg'];

        $query = "
                INSERT INTO 
                    ${className}
                    (
                           msg,
                           code,
                           content,
                           createdAt
                    )
                VALUES (
                        '${msg}',
                        '${code}',
                        '${json}',
                        '${date}'
                )";
        $result = $this->conn->query($query);

        if ($result) {
            $id = $this->conn->insert_id;
            $response = array (
                'id' => $id
            );
        } else {
            $response = array (
                'error' => $this->conn->error
            );
        }

        $this->closeCon();

        return  json_encode($response);
    }
}
