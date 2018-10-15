<?php
/**
 * Created by PhpStorm.
 * User: Tony
 * Date: 04/05/2017
 * Time: 17:03
 */
class ggpmModelAssenze  extends JModelLegacy {

    protected $_db;
    private $_params;
    private $_app;


    public function __construct($config = array()) {
        parent::__construct($config);


        $this->_db = $this->getDbo();
        $this->_app = JFactory::getApplication();
        $this->_params = $this->_app->getParams();

    }

    public function insert($id_dipendente,$data_inizio,$data_fine,$causale){


        $object = new StdClass;
        $object->id_dipendente=$id_dipendente;
        $object->data_inizio=$data_inizio;
        $object->data_fine=$data_fine;
        $object->causale=$causale;
        $object->timestamp=Date('Y-m-d h:i:s',time());

        $result=$this->_db->insertObject('u3kon_gg_assenze_dipendente',$object);
        return $result;
    }

    public function delete($id){


        $sql="delete from u3kon_gg_assenze_dipendente where id=".$id;
        $this->_db->setQuery($sql);
        $result=$this->_db->execute();

        return $result;
    }



    public function getAssenze($id_dipendente){

            $query_=$this->_db->getQuery(true);
            $query_->select('a.data_inizio as data_inizio,a.data_fine as data_fine, a.causale as causale');
            $query_->from('u3kon_gg_assenze_dipendente as a');
            $query_->where('a.id_dipendente='.$id_dipendente);
            $this->_db->setQuery($query_);
            $assenze=$this->_db->loadAssocList();
            return $assenze;
    }

    public function getAssenzeAll(){

        $query=$this->_db->getQuery(true);
        $query->select('*');
        $query->from('u3kon_gg_dipendenti');
        $this->_db->setQuery($query);

        $dipendenti=$this->_db->loadAssocList();

        foreach ($dipendenti as &$dipendente) {

            $query_=$this->_db->getQuery(true);
            $query_->select('a.data as data, a.causale as causale');
            $query_->from('u3kon_gg_assenze_dipendente as a');
            $query_->where('a.id_dipendente='.$dipendente['id']);
            $this->_db->setQuery($query_);
            $assenze=$this->_db->loadAssocList();
            $dipendente['assenze']=$assenze;

        }


        return $dipendenti;



    }


}

