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

    public function insert($nome,$cognome,$valore_orario,$monte_ore){


        $object = new StdClass;
        $object->nome=$nome;
        $object->cognome=$cognome;
        $object->valore_orario=$valore_orario;
        $object->monte_ore=$monte_ore;
        $object->timestamp=Date('Y-m-d h:i:s',time());

        $result=$this->_db->insertObject('u3kon_gg_dipendenti',$object);
        return $result;
    }

    public function delete($id){


        $sql="delete from u3kon_gg_dipendenti where id=".$id;
        $this->_db->setQuery($sql);
        $result=$this->_db->execute();

        return $result;
    }

    public function modify($id,$nome,$cognome,$valore_orario,$monte_ore){


        $sql="update u3kon_gg_dipendenti set nome='".$nome."', cognome='".$cognome."', valore_orario='".$valore_orario."', monte_ore='".$monte_ore."' where id=".$id;

        $this->_db->setQuery($sql);
        $result=$this->_db->execute();

        return $result;
    }

    public function getDipendenti($id=null){

        $query=$this->_db->getQuery(true);
        $query->select('*');
        $query->from('u3kon_gg_dipendenti');
        if($id!=null)
            $query->where('id='.$id);

        $this->_db->setQuery($query);

        $dipendenti=$this->_db->loadAssocList();

        foreach ($dipendenti as &$dipendente) {

            $query_=$this->_db->getQuery(true);
            $query_->select('m.id as id, r.ruolo as ruolo ');
            $query_->from('u3kon_gg_dipendenti as d');
            $query_->join('left','u3kon_gg_map_dip_ruolo as m on d.id=m.id_dipendente');
            $query_->join('left','u3kon_gg_ruoli as r on r.id=m.id_ruolo');
            $query_->where('d.id='.$dipendente['id']);
            $this->_db->setQuery($query_);
            $ruoli=$this->_db->loadAssocList();
            $dipendente['ruoli']=$ruoli;

        }


        return $dipendenti;
      }

}



