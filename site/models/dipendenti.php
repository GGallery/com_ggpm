<?php
/**
 * Created by PhpStorm.
 * User: Tony
 * Date: 04/05/2017
 * Time: 17:03
 */
class ggpmModelDipendenti  extends JModelLegacy {

    protected $_db;
    private $_params;
    private $_app;


    public function __construct($config = array()) {
        parent::__construct($config);


        $this->_db = $this->getDbo();
        $this->_app = JFactory::getApplication();
        $this->_params = $this->_app->getParams();

    }

    public function insert($nome,$cognome,$valore_orario){


        $object = new StdClass;
        $object->nome=$nome;
        $object->cognome=$cognome;
        $object->valore_orario=$valore_orario;
        $object->timestamp=Date('Y-m-d h:i:s',time());

        $result=$this->_db->insertObject('u3ukon_gg_dipendenti',$object);
        return $result;
    }

    public function getDipendenti(){

        $query=$this->_db->getQuery(true);
        $query->select('*');
        $query->from('u3ukon_gg_dipendenti');
        $this->_db->setQuery($query);

        $result=$this->_db->loadAssocList();

        return $result;



    }

}

