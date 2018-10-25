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

      public function getArrayRuoloDipendente(){

          $query=$this->_db->getQuery(true);
          $query->select('r.ruolo, d.cognome, d.id as id, r.id as ruolo_id, d.valore_orario as valore_orario');
          $query->from('u3kon_gg_dipendenti as d');
          $query->join('left','u3kon_gg_map_dip_ruolo as m on d.id=m.id_dipendente');
          $query->join('left','u3kon_gg_ruoli as r on r.id=m.id_ruolo');


          $this->_db->setQuery($query);

          $result=$this->_db->loadAssocList();



          return $result;

      }

      public function getCruscottodipendenti(){

          $query=$this->_db->getQuery(true);
          $query->select('d.cognome, sum(t.ore*t.valore_orario) as budget_impegnato, monte_ore-sum(t.ore) as ore_impegnate');
          $query->from('u3kon_gg_dipendenti as d');
          $query->join('inner','u3kon_gg_task as t on t.id_dipendente=d.id');
          $query->group('d.id');
          //echo $query;die;
          $this->_db->setQuery($query);
          $result=$this->_db->loadAssocList();



          return $result;


      }


}



