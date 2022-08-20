<?php
require __DIR__.'/../Manager/DatabaseManager.php';

class Consumers
{
    private $doctrine;
    private $className;

    public function __construct() {
        $this->doctrine = new DatabaseManager();
        $this->className = 'Consumers';
    }

    public function getAll()
    {
        $result = $this->doctrine->queryAllDoctrine($this->className);

        return array(
          'data' => json_decode($result, true)
        );
    }

    public function getOne($id)
    {
        if (empty($id)) {
            return [];
        }

        $result = $this->doctrine->querySinglDoctrine($this->className, $id);

        return array(
            'data' => json_decode($result, true)
        );
    }

    public function insertData($data)
    {
        if (empty($data)) {
            return false;
        }

        $result = $this->doctrine->queryInsertData($this->className,$data);

        return array(
            'data' => json_decode($result, true)
        );
    }

}
