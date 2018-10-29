<?php
/**
 * Created by PhpStorm.
 * User: Tony
 * Date: 04/05/2017
 * Time: 17:03
 */
class ggpmModelBudget  extends JModelLegacy {

    protected $_db;
    private $_params;
    private $_app;


    public function __construct($config = array()) {
        parent::__construct($config);


        $this->_db = $this->getDbo();
        $this->_app = JFactory::getApplication();
        $this->_params = $this->_app->getParams();

    }

    public function insert($id_piano_formativo,$id_voce_costo,$budget){


        $object = new StdClass;
        $object->id_piano_formativo=$id_piano_formativo;
        $object->id_voce_costo=$id_voce_costo;
        $object->budget=$budget;
        $object->timestamp=Date('Y-m-d h:i:s',time());
        $result=$this->_db->insertObject('u3kon_gg_budget',$object);
        return $result;
    }

    public function delete($id){


        $sql="delete from u3kon_gg_budget where id=".$id;
        $this->_db->setQuery($sql);
        $result=$this->_db->execute();

        return $result;
    }

    public function modify($id,$budget){


        $sql="update u3kon_gg_budget set budget='".$budget."' where id=".$id;

        $this->_db->setQuery($sql);
        $result=$this->_db->execute();

        return $result;
    }

    public function getBudget($id=null,$id_piano_formativo=null){

        $query=$this->_db->getQuery(true);
        $query->select('b.id as id, c.id as id_voce_costo, c.descrizione as voce_costo, p.descrizione as piano_formativo, b.budget as budget, f.descrizione as descrizione_fase,
        (select sum(ore*valore_orario) from u3kon_gg_task as t where t.id_piano_formativo=p.id and t.id_voce_costo=c.id) as totale_costo');
        $query->from('u3kon_gg_budget as b');
        $query->join('inner','u3kon_gg_piani_formativi as p on b.id_piano_formativo=p.id');
        $query->join('inner','u3kon_gg_voci_costo as c on b.id_voce_costo=c.id');
        $query->join('inner','u3kon_gg_fasi as f on c.id_fase=f.id');

        if($id!=null)
            $query->where('id='.$id);
        if($id_piano_formativo!=null)
            $query->where('id_piano_formativo='.$id_piano_formativo);

        $this->_db->setQuery($query);
        $budget=$this->_db->loadAssocList();
        return $budget;
      }

}



