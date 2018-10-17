<?php
/**
 * Created by PhpStorm.
 * User: Tony
 * Date: 04/05/2017
 * Time: 17:03
 */
class ggpmModelPianiformativi  extends JModelLegacy {

    protected $_db;
    private $_params;
    private $_app;


    public function __construct($config = array()) {
        parent::__construct($config);


        $this->_db = $this->getDbo();
        $this->_app = JFactory::getApplication();
        $this->_params = $this->_app->getParams();

    }

    public function insert($descrizione,$data_inizio,$data_fine){


        $object = new StdClass;
        $object->descrizione=$descrizione;
        $object->data_inizio=$data_inizio;
        $object->data_fine=$data_fine;
        $object->timestamp=Date('Y-m-d h:i:s',time());
        $result=$this->_db->insertObject('u3kon_gg_piani_formativi',$object);
        return $result;
    }

    public function delete($id){


        $sql="delete from u3kon_gg_piani_formativi where id=".$id;
        $this->_db->setQuery($sql);
        $result=$this->_db->execute();

        return $result;
    }

    public function modify($id,$descrizione,$data_inizio,$data_fine){


        $sql="update u3kon_gg_piani_formativi set descrizione='".$descrizione."', data_inizio='".$data_inizio."', data_fine='".$data_fine."' where id=".$id;

        $this->_db->setQuery($sql);
        $result=$this->_db->execute();

        return $result;
    }

    public function getPianiformativi($id=null){

        $query=$this->_db->getQuery(true);
        $query->select('*');
        $query->from('u3kon_gg_piani_formativi');

        if($id!=null)
            $query->where('id='.$id);

        $this->_db->setQuery($query);

        $piani=$this->_db->loadAssocList();

        return $piani;
      }

}



