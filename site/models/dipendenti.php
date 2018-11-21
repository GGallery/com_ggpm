<?php
/**
 * Created by PhpStorm.
 * User: Tony
 * Date: 04/05/2017
 * Time: 17:03
 */

require_once JPATH_COMPONENT . '/models/task.php';
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

    public function getCruscottodipendenti($id_piano_formativo=null){

        $query=$this->_db->getQuery(true);
        $query->select('d.id, d.cognome, sum(dot.ore*t.valore_orario) as budget_impegnato, d.monte_ore as monte_ore, sum(dot.ore) as ore_impegnate');
        $query->from('u3kon_gg_dipendenti as d');
        $query->join('left','u3kon_gg_task as t on t.id_dipendente=d.id');
        $query->join('left','u3kon_gg_days_of_tasks as dot on dot.id_task=t.id');
        if($id_piano_formativo!=null)
            $query->where('t.id_piano_formativo='.$id_piano_formativo);
        $query->group('d.id');
        //echo $query;die;
        $this->_db->setQuery($query);
        $result=$this->_db->loadAssocList();
        foreach ($result as &$dipendente){

            $dipendente['ore_ferie']=$this->calcola_ore_ferie($dipendente['id']);
            $dipendente['ore_residue']=$dipendente['monte_ore']-$dipendente['ore_impegnate']-$dipendente['ore_ferie'];
        }


        return $result;


    }

    private function calcola_ore_ferie($id_dipendente){

        $model=new ggpmModeltask();
        $query=$this->_db->getQuery(true);
        $query->select('data_inizio, data_fine');
        $query->from('u3kon_gg_assenze_dipendente');
        $query->where('id_dipendente='.$id_dipendente);
        //echo $query;die;
        $this->_db->setQuery($query);
        $result=$this->_db->loadAssocList();
        $giorni_di_ferie=0;
        foreach ($result as $feria){
            $data_inizio=date_create($feria['data_inizio']);
            $data_fine=date_create($feria['data_fine']);
            $data_corrente=clone $data_inizio;
            while($data_corrente<=$data_fine){


                if(!$model->isFestivo($data_corrente)){
                    $giorni_di_ferie++;
                }
                date_add($data_corrente,date_interval_create_from_date_string("1 day"));
            }
        }
        return $giorni_di_ferie*8;
    }

}



