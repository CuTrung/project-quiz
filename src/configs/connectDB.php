<?php
class ConnectDB
{
    public $db = null;

    function __construct()
    {
        $dsn = 'mysql:host=localhost;dbname=project_quiz';
        $user = 'root';
        $pass = '';
        try {
            $this->db = new PDO($dsn, $user, $pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        } catch (\Throwable $th) {
        }
    }

    // public function get($select)
    // {
    //     return $this->db->query($select)->fetchAll();
    // }

    // public function insert($query, $value)
    // {
    //     return $this->db->prepare($query)->execute($value);
    // }

    public function executeQuery($query, $value = [])
    {
        // SELECT
        if ($query[0] === 'S')
            return $this->db->query($query)->fetchAll();

        // INSERT, UPDATE, DELETE 
        return $this->db->prepare($query)->execute($value);
    }
}
