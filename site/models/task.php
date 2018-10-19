<?php
/**
 * Created by PhpStorm.
 * User: Tony
 * Date: 04/05/2017
 * Time: 17:03
 */
class ggpmModelTask  extends JModelLegacy {

    protected $_db;
    private $_params;
    private $_app;


    public function __construct($config = array()) {
        parent::__construct($config);


        $this->_db = $this->getDbo();
        $this->_app = JFactory::getApplication();
        $this->_params = $this->_app->getParams();

    }

    public function insert($id_piano_formativo,$descrizione,$data_inizio,$durata,$ore,$id_voce_costo,$id_ruolo,$id_dipendente,$id_task_propedeutico,$valore_orario){


        $object = new StdClass;
        $object->id_piano_formativo=$id_piano_formativo;
        $object->descrizione=$descrizione;
        $object->data_inizio=$data_inizio;
        $object->durata=$durata;
        $object->ore=$ore;
        $object->id_voce_costo=$id_voce_costo;
        $object->id_ruolo=$id_ruolo;
        $object->id_dipendente=$id_dipendente;
        $object->id_task_propedeutico=$id_task_propedeutico;
        $object->valore_orario=$valore_orario;
        $object->timestamp=Date('Y-m-d h:i:s',time());
        $result=$this->_db->insertObject('u3kon_gg_task',$object);
        return $result;
    }

    public function delete($id){


        $sql="delete from u3kon_gg_task where id=".$id;
        $this->_db->setQuery($sql);
        $result=$this->_db->execute();

        return $result;
    }

    public function modify($id,$id_piano_formativo,$descrizione,$data_inizio,$durata,$id_voce_costo,$id_ruolo,$id_dipendente,$id_task_propedeutico,$valore_orario){


        $sql="update u3kon_gg_task 
              set id_piano_formativo='".$id_piano_formativo."',
                    descrizione='".$descrizione."', 
                    id_piano_formativo='".$id_piano_formativo."',
                    data_inizio='".$data_inizio."', 
                    durata='".$durata."', 
                    id_voce_costo='".$id_voce_costo."',
                    id_ruolo='".$id_ruolo."',
                    id_dipendente='".$id_dipendente."',
                    id_task_propedeutico='".$id_task_propedeutico."',
                    valore_orario='".$valore_orario."'
                    where id=".$id;

        $this->_db->setQuery($sql);
        $result=$this->_db->execute();

        return $result;
    }

    public function getTask($id=null, $id_piano_formativo){

        $query=$this->_db->getQuery(true);
        $query->select('*');
        $query->from('u3kon_gg_task');

        if($id!=null)
            $query->where('id='.$id);
        if($id_piano_formativo!=null)
            $query->where('id_piano_formativo='.$id_piano_formativo);

        $this->_db->setQuery($query);

        $task=$this->_db->loadAssocList();

        return $task;
      }

}



