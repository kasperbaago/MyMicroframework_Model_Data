<?php
namespace app\model\data;
use \Exception;

/**
 * ::: OPTION CLASS :::
 * Retrives and set options in the option tabtle
 */

class Options extends \app\model\db\DbModel
{
    protected $table = "lojbib_meta";
    protected $Key, $Value;

    /**
     *
     * @param null $key
     * @return bool
     * @throws \Exception
     */
    public function get($key = null) {
        if ( !is_string($key) ) throw new Exception('Key given is not string!');
        $res = $this->db->get_where($this->table, array('Key' => $key), array("ID"));
        if($res->num_rows != 1 || $res == false) return false;
        $this->load($res->fetch_object()->ID);
        return $this->Value;
    }

    /**
     * Set key value pair
     *
     * @param String $key
     * @param String|Array $value
     * @return bool
     */
    public function set($key = null, $value = null) {
        if( !is_string($key) ) throw new Exception('Keu given is not string!');
        if( !is_string($value) && !is_array($value)) throw new Exception('Value given is not array or string');
        $this->Key = $key;
        $this->Value = $value;
        $this->save();
        return true;
    }


    public function save() {
        $r = $this->db->get_where($this->table, array('Key' => $this->Key), array("ID"), null, 1);
        if($r->num_rows > 0) {
            $this->ID = $r->fetch_object()->ID;
        } else {
            $this->ID = null;
        }

        if(is_array($this->Value)) $this->Value = json_encode($this->Value);
        parent::save();
    }

    public function load($id = null) {
        parent::load($id);
        $json = json_decode($this->Value, true);
        if($json != null && is_array($json)) {
            $this->Value = $json;
        }
    }

    public function setValue($value)
    {
        $this->Value = $value;
    }

    public function getValue()
    {
        return $this->Value;
    }

    public function getKey()
    {
        return $this->Key;
    }


}
