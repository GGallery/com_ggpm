<?php
/**
 * Created by PhpStorm.
 * User: Tony
 * Date: 04/05/2017
 * Time: 17:03
 */
class ggpmModelRuoli  extends JModelLegacy {

    protected $_db;
    private $_params;
    private $_app;


    public function __construct($config = array()) {
        parent::__construct($config);


        $this->_db = $this->getDbo();
        $this->_app = JFactory::getApplication();
        $this->_params = $this->_app->getParams();

    }

    public function insert($ruolo){


        $object = new StdClass;
        $object->ruolo=$ruolo;
        $object->timestamp=Date('Y-m-d h:i:s',time());
        $result=$this->_db->insertObject('u3kon_gg_ruoli',$object);
        return $result;
    }

    public function insert_map($id_dipendente, $id_ruolo){

        $object = new StdClass;
        $object->id_dipendente=$id_dipendente;
        $object->id_ruolo=$id_ruolo;
        $object->timestamp=Date('Y-m-d h:i:s',time());
        $result=$this->_db->insertObject('u3kon_gg_map_dip_ruolo',$object);
        return $result;

        return $result;
    }


    public function delete($id){


        $sql="delete from u3kon_gg_ruoli where id=".$id;
        $this->_db->setQuery($sql);
        $result=$this->_db->execute();

        return $result;
    }

    public function delete_map($id){


        $sql="delete from u3kon_gg_map_dip_ruolo where id=".$id;
        $this->_db->setQuery($sql);
        $result=$this->_db->execute();

        return $result;
    }

    public function modify($id,$ruolo){


        $sql="update u3kon_gg_ruoli set ruolo='".$ruolo."' where id=".$id;
        
        $this->_db->setQuery($sql);
        $result=$this->_db->execute();

        return $result;
    }

    public function getRuoli(){

        $query=$this->_db->getQuery(true);
        $query->select('*');
        $query->from('u3kon_gg_ruoli');
        $this->_db->setQuery($query);

        $result=$this->_db->loadAssocList();

        return $result;



    }

}

