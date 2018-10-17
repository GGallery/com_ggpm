<?php
/**
 * Created by PhpStorm.
 * User: Tony
 * Date: 04/05/2017
 * Time: 17:03
 */
class ggpmModelVocicosto  extends JModelLegacy {

    protected $_db;
    private $_params;
    private $_app;


    public function __construct($config = array()) {
        parent::__construct($config);


        $this->_db = $this->getDbo();
        $this->_app = JFactory::getApplication();
        $this->_params = $this->_app->getParams();

    }

    public function insert($id_fase,$descrizione){


        $object = new StdClass;
        $object->id_fase=$id_fase;
        $object->descrizione=$descrizione;
        $object->timestamp=Date('Y-m-d h:i:s',time());
        $result=$this->_db->insertObject('u3kon_gg_voci_costo',$object);
        return $result;
    }

    public function delete($id){


        $sql="delete from u3kon_gg_voci_costo where id=".$id;
        $this->_db->setQuery($sql);
        $result=$this->_db->execute();

        return $result;
    }

    public function modify($id,$id_fase,$descrizione){


        $sql="update u3kon_gg_voci_costo set id_fase='".$id_fase."', descrizione='".$descrizione."' where id=".$id;

        $this->_db->setQuery($sql);
        $result=$this->_db->execute();

        return $result;
    }

    public function getVocicosto($id=null){

        $query=$this->_db->getQuery(true);
        $query->select('c.id as id, f.descrizione as fase, c.descrizione as descrizione');
        $query->from('u3kon_gg_voci_costo as c');
        $query->join('inner','u3kon_gg_fasi as f on c.id_fase=f.id');
        if($id!=null)
            $query->where('id='.$id);

        $this->_db->setQuery($query);

        $voci_costo=$this->_db->loadAssocList();

        return $voci_costo;
      }

}



