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

    public function insert($id_piano_formativo,$descrizione,$data_inizio,$data_fine,$durata,$ore,$id_voce_costo,$id_ruolo,$id_dipendente,$id_task_propedeutico,$valore_orario){


        $object = new StdClass;
        $object->id_piano_formativo=$id_piano_formativo;
        $object->descrizione=$descrizione;
        $object->data_inizio = $data_inizio;
        $object->data_fine = $data_fine;
        $object->durata=$durata;
        $object->ore=(intval($ore/$durata))*$durata;
        $object->id_voce_costo=$id_voce_costo;
        $object->id_ruolo=$id_ruolo;
        $object->id_dipendente=$id_dipendente;
        $object->id_task_propedeutico=$id_task_propedeutico;
        $object->valore_orario=$valore_orario;
        $object->timestamp=Date('Y-m-d h:i:s',time());
        $result=$this->_db->insertObject('u3kon_gg_task',$object);
        $task_id=$this->_db->insertid();
        $object_ = new StdClass;
        $data_inizio=date_create($data_inizio);
        $data_fine=date_create($data_fine);
        $ore_medie_giorno=intval($ore/$durata);
        $data_corrente=clone $data_inizio;
        while($data_corrente<=$data_fine){
            if(!$this->isFestivo($data_corrente) && !$this->isFerie($data_corrente,$id_dipendente)) {

                $object_->id_task = $task_id;
                $object_->data_giorno = $data_corrente->format('Y-m-d');
                $object_->ore = $this->calcola_capienza_giorno_dipendente($ore_medie_giorno,$id_dipendente,$data_corrente);
                $object_->timestamp = Date('Y-m-d h:i:s', time());
                $result_ = $this->_db->insertObject('u3kon_gg_days_of_tasks', $object_);
            }
            $data_corrente= date_add($data_corrente,date_interval_create_from_date_string("1 day"));

        }
        return $result;
    }

    private function calcola_capienza_giorno_dipendente($ore_medie_giorno,$id_dipendente,$data_giorno){

        $query=$this->_db->getQuery(true);
        $query->select('sum(d.ore)');
        $query->from('u3kon_gg_days_of_tasks as d');
        $query->join('inner','u3kon_gg_task as t on d.id_task=t.id');
        $query->where('t.id_dipendente='.$id_dipendente.' and d.data_giorno=\''.$data_giorno->format('Y-m-d').'\'');
//echo $query;die;
        $this->_db->setQuery($query);
        $oreimpegnate = $this->_db->loadResult();
        echo $oreimpegnate.'<br>';
        if($oreimpegnate+$ore_medie_giorno<=8){
            return $ore_medie_giorno;
        }else{
            return 0;
        }

    }
    public function delete($id){


        $sql="delete from u3kon_gg_task where id=".$id;
        $this->_db->setQuery($sql);
        $result=$this->_db->execute();

        return $result;
    }

    public function deletedaysoftasks($id_task){


        $sql="delete from u3kon_gg_days_of_tasks where id_task=".$id_task;
        $this->_db->setQuery($sql);
        $result=$this->_db->execute();

        return $result;
    }

    public function modify($id,$id_piano_formativo,$descrizione,$data_inizio,$data_fine,$durata,$id_voce_costo,$ore, $id_ruolo,$id_dipendente,$id_task_propedeutico,$valore_orario){


        if(
        $this->delete($id)
        && $this->deletedaysoftasks($id)
        && $this->insert($id_piano_formativo,$descrizione,$data_inizio,$data_fine,$durata,$ore,$id_voce_costo,$id_ruolo,$id_dipendente,$id_task_propedeutico,$valore_orario)
        //&& $this->aggiusta_propedeuticita($id,$data_fine,$this->_db->insertid())
        ){

            return true;
        }else {

            return false;
        }
    }

    public function updateoregiorno($id_task,$data_giorno,$ore,$ore_vecchie)
    {
/*
        $sql = 'insert into u3kon_gg_days_of_tasks  (id_task,data_giorno,ore,timestamp) values (' . $id_task . ',\'' . $data_giorno . '\',' . $ore . ',now()) on duplicate key update ore=' . $ore;
        //echo $sql;die;
        $this->_db->setQuery($sql);
        $result = $this->_db->execute();

        $diff_ore = $ore - $ore_vecchie;
        $sql_ = 'update u3kon_gg_task set ore=ore+(' . $diff_ore . ') where id=' . $id_task;
        $this->_db->setQuery($sql_);
        $result = $this->_db->execute();
//var_dump($this->getTask($id_task, null, null)[0][0]['data_fine']);die;
        if ($data_giorno > $this->getTask($id_task, null, null)[0][0]['data_fine']) {
            $sql = 'update u3kon_gg_task set data_fine=\'' . $data_giorno . '\' where id=' . $id_task;
            $this->_db->setQuery($sql);
            $result = $this->_db->execute();

        }

        if ($data_giorno < $this->getTask($id_task, null, null)[0][0]['data_inizio']) {
            $sql = 'update u3kon_gg_task set data_inizio=\'' . $data_giorno . '\' where id=' . $id_task;
            $this->_db->setQuery($sql);
            $result = $this->_db->execute();


        }*/

        $sql='update u3kon_gg_days_of_tasks set ore='.$ore.' where id_task='.$id_task.' and data_giorno=\''.$data_giorno.'\'';
        $this->_db->setQuery($sql);
        $result=$this->_db->execute();

        $diff_ore=$ore-$ore_vecchie;
        $sql_='update u3kon_gg_task set ore=ore+('.$diff_ore.') where id='.$id_task;
        $this->_db->setQuery($sql_);
        $result=$this->_db->execute();
    }

    private function aggiusta_propedeuticita($id,$data_fine,$nuovo_id){

        $query=$this->_db->getQuery(true);
        $query->select('*');
        $query->from('u3kon_gg_task');
        $query->where('id_task_propedeutico='.$id);
        $this->_db->setQuery($query);
        $tasks = $this->_db->loadAssocList();
        foreach ($tasks as $task){

            $durata_aggiornata=$this->gestioneGiornidaaggiungere($task['data_inizio'],$task['durata'],$task['id_dipendente'])+1;
            $sql='UPDATE u3kon_gg_task SET data_inizio=DATE_ADD(\''.$data_fine.'\', INTERVAL 1 DAY), data_fine=date_add(\''.$data_fine.'\', INTERVAL '.$durata_aggiornata.' DAY ), id_task_propedeutico='.$nuovo_id.' WHERE id='.$task['id'];
            $this->_db->setQuery($sql);
            $result=$this->_db->execute();
            $this->aggiusta_propedeuticita($task['id'],$task['data_fine']);
        }

    }

    public function getTask($id=null, $id_piano_formativo,$id_dipendente=null)
    {

        $query = $this->_db->getQuery(true);
        $query->select('task.*,task.data_fine as data_fine, d.cognome as cognome, (select sum(ore) from u3kon_gg_days_of_tasks as dot where dot.id_task=task.id) as ore,
                p.descrizione as descrizione_piano, task.ore*task.valore_orario as task_budget, f.descrizione as descrizione_fase, 
                v.descrizione as descrizione_voce_costo, task.id_voce_costo as id_voce_costo, task.id_ruolo as id_ruolo, task.id_dipendente as id_dipendente, task.id_task_propedeutico as id_task_propedeutico');
        $query->from('u3kon_gg_task as task');

        $query->join('inner','u3kon_gg_dipendenti as d on task.id_dipendente=d.id');
        $query->join('inner','u3kon_gg_piani_formativi as p on p.id=task.id_piano_formativo');
        $query->join('inner','u3kon_gg_voci_costo as v on v.id=task.id_voce_costo');
        $query->join('inner','u3kon_gg_fasi as f on v.id_fase=f.id');
        if ($id != null)
            $query->where('task.id=' . $id);
        if ($id_piano_formativo != null)
            $query->where('task.id_piano_formativo=' . $id_piano_formativo);
        if ($id_dipendente!=null)
           $query->where('task.id_dipendente='.$id_dipendente);
        $query->order('task.data_inizio ASC');
        //echo $query; die;
        $this->_db->setQuery($query);

        $task = $this->_db->loadAssocList();
        if (count($task) > 0) {

            $data_minore = min(array_column($task, 'data_inizio'));
            $data_maggiore = max(array_column($task, 'data_fine'));
            $data_maggiore=date_add(date_create($data_maggiore),date_interval_create_from_date_string("30 days"));
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



        $colori=['#ff9900','#00cc00','#9999ff'];
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

                $rowtask[$giorno_progetto][1]=$this->getOreFromDaysOftasks($item['id'],$data_corrente, $item['id_dipendente']);
                $rowtask[$giorno_progetto][0]=($rowtask[$giorno_progetto][1]!=0)?$colori[$colorindex]:'#ff0000';
                $rowtask[$giorno_progetto][2]=$data_corrente->format('Y-m-d');

            }else{
                $rowtask[$giorno_progetto][0]='none';
                $rowtask[$giorno_progetto][1]=null;
                $rowtask[$giorno_progetto][2]=$data_corrente->format('Y-m-d');
            }
            $giorno_progetto++;
            while($data_corrente<=$data_fine){

                $nuova= clone date_add($data_corrente,date_interval_create_from_date_string("1 day"));
                // var_dump($item['data_inizio']);var_dump($nuova);
                if(date_create($item['data_inizio'])<=$nuova && date_create($item['data_fine'])>=$nuova) {

                    $rowtask[$giorno_progetto][1]=$this->getOreFromDaysOftasks($item['id'],$data_corrente, $item['id_dipendente']);
                    $rowtask[$giorno_progetto][0]=($rowtask[$giorno_progetto][1]!=0)?$colori[$colorindex]:'#ff0000';
                    $rowtask[$giorno_progetto][2]=$data_corrente->format('Y-m-d');

                }else{
                    $rowtask[$giorno_progetto][0]='none';
                    $rowtask[$giorno_progetto][1]=null;
                    $rowtask[$giorno_progetto][2]=$data_corrente->format('Y-m-d');
                }
                $giorno_progetto++;
            }
            array_push($taskrows,$rowtask);
        }

        return $taskrows;
    }

    private function getOreFromDaysOfTasks($id_task,$data_giorno, $id_dipendente){

        if ($this->isFestivo($data_giorno) || $this->isFerie($data_giorno, $id_dipendente))
            return null;


        $query = $this->_db->getQuery(true);
        $query->select('ore');
        $query->from('u3kon_gg_days_of_tasks');
        $query->where('data_giorno=\''.$data_giorno->format('Y-m-d').'\' and id_task='.$id_task);
        $this->_db->setQuery($query);
        $ore = $this->_db->loadResult();
        return $ore;


    }

    public function isFestivo($giorno){

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



