<?php
/**
 * Created by PhpStorm.
 * User: Tony
 * Date: 04/05/2017
 * Time: 17:03
 */
class ggpmModelFasi  extends JModelLegacy {

    protected $_db;
    private $_params;
    private $_app;


    public function __construct($config = array()) {
        parent::__construct($config);


        $this->_db = $this->getDbo();
        $this->_app = JFactory::getApplication();
        $this->_params = $this->_app->getParams();

    }

    public function insert($descrizione){


        $object = new StdClass;
        $object->descrizione=$descrizione;
        $object->timestamp=Date('Y-m-d h:i:s',time());
        $result=$this->_db->insertObject('u3kon_gg_fasi',$object);
        return $result;
    }

    public function delete($id){


        $sql="delete from u3kon_gg_fasi where id=".$id;
        $this->_db->setQuery($sql);
        $result=$this->_db->execute();

        return $result;
    }

    public function modify($id, $descrizione){


        $sql="update u3kon_gg_fasi set descrizione='".$descrizione."' where id=".$id;

        $this->_db->setQuery($sql);
        $result=$this->_db->execute();

        return $result;
    }

    public function getFasi($id=null){

        $query=$this->_db->getQuery(true);
        $query->select('*');
        $query->from('u3kon_gg_fasi');
        if($id!=null)
            $query->where('id='.$id);

        $this->_db->setQuery($query);
        $fasi=$this->_db->loadAssocList();
        return $fasi;

      }

}



