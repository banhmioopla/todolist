<?php
class WorkModel {
    private $db;
    private $table_name = "work";
    const STATUS_DOING = 'Doing';
    const STATUS_PLANNING = 'Planning';
    const STATUS_COMPLETE = 'Complete';

    public function __construct(){
        $this->db = new Database();
    }

    public function getListStatus(){
        return [
            self::STATUS_PLANNING,
            self::STATUS_DOING,
            self::STATUS_COMPLETE,
        ];
    }

    public function getListColorStatus(){
        return [
            self::STATUS_DOING => '#f5af00',
            self::STATUS_PLANNING => '#0000b3',
            self::STATUS_COMPLETE => '#00a357',
        ];
    }

    public function select($where_string = ""){
        $sql = "SELECT * FROM ".$this->table_name;

        if(!empty($where_string)){
            $sql .= " WHERE ".$where_string;
        }

        return $this->db->query($sql)->fetchAll();
    }

    public function updateById($id, $data){
        if(is_array($data)){
            $sql = "UPDATE ".$this->table_name. " SET ";
            foreach ($data as $col_name => $val){
                $sql .= ($col_name . " = " . "'". $val . "',");
            }

            $sql = rtrim($sql, ",");
            $sql .= " WHERE id = ". $id;

            return $this->db->query($sql);
        }
        return false;
    }

    public function deleteById($id){
        $sql = "DELETE FROM ".$this->table_name." WHERE id =".$id;
        return $this->db->query($sql);
    }

    public function insert($data){
        $string_data = "";
        $string_col = "";
        if(is_array($data)){
            foreach ($data as $col_name => $value){
                $string_data .= "'". $value . "',";
                $string_col .= $col_name.",";
            }
        }

        $string_data = rtrim($string_data, ",");
        $string_col = rtrim($string_col, ",");

        $sql = "INSERT INTO ".$this->table_name."(".$string_col.") VALUES(".$string_data.")";

        $this->db->query($sql);
        $result = $this->db->query("SELECT LAST_INSERT_ID() FROM ".$this->table_name);
        return $result->fetchColumn();
    }
}
