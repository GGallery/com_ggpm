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
        $object->data_inizio = $data_inizio;
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

    public function getTask($id=null, $id_piano_formativo,$id_dipendente=null)
    {

        $query = $this->_db->getQuery(true);
        $query->select('task.* ,DATE_ADD(task.data_inizio,INTERVAL task.durata DAY) as \'data_fine\', d.cognome as cognome, p.descrizione as descrizione_piano, task.ore*task.valore_orario as task_budget');
        $query->from('u3kon_gg_task as task');
        $query->join('inner','u3kon_gg_dipendenti as d on task.id_dipendente=d.id');
        $query->join('inner','u3kon_gg_piani_formativi as p on p.id=task.id_piano_formativo');

        if ($id != null)
            $query->where('task.id=' . $id);
        if ($id_piano_formativo != null)
            $query->where('task.id_piano_formativo=' . $id_piano_formativo);
        if ($id_dipendente!=null)
           $query->where('task.id_dipendente='.$id_dipendente);
        $query->order('data_inizio ASC');
        $this->_db->setQuery($query);

        $task = $this->_db->loadAssocList();
        if (count($task) > 0) {

            $data_minore = min(array_column($task, 'data_inizio'));
            $data_maggiore = max(array_column($task, 'data_fine'));

            $daysoftasks = $this->createDaysoftasks($task, $data_minore, $data_maggiore);

            return [$task, $data_minore, $data_maggiore, $daysoftasks];
        }else{

            return null;
        }

    }
    public function createDaysoftasks($task,$data_inizio,$data_fine){

        $anno_inizio=date_create($data_inizio)->format('Y');
        $mese_inizio=date_create($data_inizio)->format('m');
        $data_inizio=date_create($anno_inizio.'-'.$mese_inizio.'-01');
        $data_fine=date_create($data_fine);
        $colori=['#008000','#FF0000','	#0000FF'];
        $taskrows=[];
        $colorindex=0;
        foreach ($task as $item){
            $colorindex++;
            if($colorindex>2)
                $colorindex=0;
            $rowtask=[];
            $giorno_progetto=1;
            $data_corrente=clone $data_inizio;

            if(date_create($item['data_inizio'])<=$data_inizio && date_create($item['data_fine'])>=$data_inizio) {
                $rowtask[$giorno_progetto]=$colori[$colorindex];

            }else{
                $rowtask[$giorno_progetto]='none';
            }
            $giorno_progetto++;
            while($data_corrente<=$data_fine){

                $nuova= clone date_add($data_corrente,date_interval_create_from_date_string("1 day"));
                // var_dump($item['data_inizio']);var_dump($nuova);
                if(date_create($item['data_inizio'])<=$nuova && date_create($item['data_fine'])>=$nuova) {
                    $rowtask[$giorno_progetto]=$colori[$colorindex];

                }else{
                    $rowtask[$giorno_progetto]='none';
                }
                $giorno_progetto++;
            }
            array_push($taskrows,$rowtask);
        }
        return $taskrows;
    }

    public function isFestivo($giorno){

        /*$query = $this->_db->getQuery(true);
        $query->select('data');
        $query->from('u3kon_gg_date_festivita');
        $query->where('data=\'1900-'.$giorno->format('m').'-'.$giorno->format('d').'\'');
        $this->_db->setQuery($query);
        $isFestivo = count($this->_db->loadAssocList());
        return $isFestivo;*/

        $festivitas=['01-01','01-06','04-25','05-01','06-02','08-15','11-01','12-08','12-25','12-26'];
        $pasquettas=['2019-04-22','2020-04-13','2021-04-05','2022-04-18','2023-04-10','2024-04-01','2025-04-21'];
        foreach ($festivitas as $festivita){

            if ($giorno->format('m-d')==$festivita){
                return 1;
            }
        }
        foreach ($pasquettas as $pasquetta){

            if ($giorno->format('Y-m-d')==$pasquetta){
                return 1;
            }
        }
        if($giorno->format('w')==6 || $giorno->format('w')==0){

            return 1;
        }
        return 0;
    }

    public function gestioneGiornidaaggiungere($data_inizio,$durata,$id_dipendente){

        $data_inizio_calcolo_ferie=date_create($data_inizio);
        $durata_aggiornata=$durata;
        $durata_aggiornata_dopoferie=$durata_aggiornata;
        if ($this->isFerieoFestivo($data_inizio_calcolo_ferie,$id_dipendente))

            $durata_aggiornata++;

        for ($giorno=1; $giorno<$durata_aggiornata;$giorno++) {
            if ($this->isFerieoFestivo(date_add($data_inizio_calcolo_ferie, date_interval_create_from_date_string("1 day")),$id_dipendente)) {

                $durata_aggiornata++;
            }
        }

        return $durata_aggiornata;
    }

    public function isFerie($giorno,$id_dipendente){

        $query = $this->_db->getQuery(true);
        $query->select('count(*)');
        $query->from('u3kon_gg_assenze_dipendente');
        $query->where('data_inizio<=\''.$giorno->format('Y').'-'.$giorno->format('m').'-'.$giorno->format('d').'\'
        and data_fine>=\''.$giorno->format('Y').'-'.$giorno->format('m').'-'.$giorno->format('d').'\'
        and id_dipendente='.$id_dipendente);
        $this->_db->setQuery($query);
        $isFerie = $this->_db->loadResult();
        //var_dump($giorno);var_dump($isFerie);
        return $isFerie;
    }

    private function isFerieoFestivo($giorno,$id_dipendente){
        $result=0;
        if($this->isFestivo($giorno) || $this->isFerie($giorno,$id_dipendente))
            $result=1;

        return $result;
    }

    public function verificaCaricodipendente($id_dipendente, $data_inizio,$data_fine){

        $data_inizio=date_create($data_inizio);
        $data_fine=date_create($data_fine);
        $tasks_dipendente=$this->getTask(null,null,$id_dipendente)[0];

        $elenco_task_concorrenti=[];
        foreach ($tasks_dipendente as $task){

            if (
                (date_create($task['data_inizio'])>=$data_inizio && date_create($task['data_inizio'])<=$data_fine) || //se il task preso in considerazione inizia all'interno del nuovo task
                (date_create($task['data_fine'])>=$data_inizio && date_create($task['data_fine'])<=$data_fine) || // se il task preso in considerazione finisce all'interno del nuovo task
                ((date_create($task['data_inizio'])<=$data_inizio) && date_create($task['data_fine'])>=$data_fine) //se il task preso in considerazione inizia prima e finisce dopo del nuovo task
            ){
                array_push($elenco_task_concorrenti,$task);
            }
        }
        return $elenco_task_concorrenti;
    }
}



