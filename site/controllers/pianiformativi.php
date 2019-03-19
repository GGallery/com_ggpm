<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_contact
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
require_once JPATH_COMPONENT . '/models/pianiformativi.php';
require_once JPATH_COMPONENT . '/models/task.php';

/**
 * Controller for single contact view
 *
 * @since  1.5.19
 */
class ggpmControllerPianiformativi extends JControllerLegacy
{
    protected $_db;
    private $_app;
    private $params;
    private $_filterparam;

    public function __construct($config = array())
    {
        parent::__construct($config);
        $this->_app = JFactory::getApplication();
        $this->_filterparam = new stdClass();
        $this->_filterparam->id=JRequest::getVar('id');
        $this->_filterparam->descrizione=JRequest::getVar('descrizione');
        $this->_filterparam->data_inizio=JRequest::getVar('data_inizio');
        $this->_filterparam->data_fine=JRequest::getVar('data_fine');

    }
    public function insert(){

        $model=new ggpmModelPianiformativi();
        if($model->insert($this->_filterparam->descrizione,$this->_filterparam->data_inizio,$this->_filterparam->data_fine)) {
            echo "1";
        }else{
            echo "0";
        }
        $this->_app->close();

    }

    public function delete(){

        $model=new ggpmModelPianiformativi();
        if($model->delete($this->_filterparam->id)) {
            echo "1";
        }else{
            echo "0";
        }
        $this->_app->close();

    }
    public function modify(){

        $model=new ggpmModelPianiformativi();
        if($model->modify($this->_filterparam->id,$this->_filterparam->descrizione,$this->_filterparam->data_inizio,$this->_filterparam->data_fine)) {
            echo "1";
        }else{
            echo "0";
        }
        $this->_app->close();

    }

    public function getCsv(){

        $id_piano_formativo_attivo=$this->_filterparam->id;
        if ($id_piano_formativo_attivo==null){
            echo null;
            $this->_app->close();
        }

        $taskModel=new ggpmModelTask();
        $this->task=$taskModel->getTask(null,$id_piano_formativo_attivo,null);
        $data_inizio=date_create($this->task[1]);

        $data_fine=$this->task[2];

        $calendario_piano_formativo=$this->createCalendario($data_inizio,$data_fine);
        $taskModel=new ggpmModelTask();
        $task=$taskModel->getTask(null,$this->_filterparam->id,null);

        $rows=[];
        $riga_calendario=[];
        array_push($riga_calendario,"task");

        foreach ($calendario_piano_formativo[1] as $giorno){

            array_push($riga_calendario,$giorno['data']->format('d-m-Y'));
        }

        $rows[0]=$riga_calendario;

        $this->mesi = array(1=>['gennaio',31], ['febbraio',28], ['marzo',31], ['aprile',30],
            ['maggio',31], ['giugno',30], ['luglio',31], ['agosto',31],
            ['settembre',30], ['ottobre',31], ['novembre',30],['dicembre',31]);
        $totale_giorni_progetto=0;
        if($calendario_piano_formativo) {
            foreach ($calendario_piano_formativo[0] as $mese) {
                $giorni_mese = $this->mesi[intval(date_format($mese, 'm'))][1];
                $totale_giorni_progetto = $totale_giorni_progetto + $giorni_mese;
            }
        }
        if(isset($this->task[3]))
            $dayoftasks=$task[3];

        $tasknumber=0;
        if(isset($task[0])) {

            foreach ($task[0] as $item) {
              $riga_task=[];
              $riga_task[0]=$item['descrizione_fase'] .'-' . $item['descrizione'] . '-'.$item['cognome'].'-'.$item['task_budget'].' - '.$item['ore'];

                for ($i = 1; $i < $totale_giorni_progetto+1; $i++) {
                    if (isset($dayoftasks[$tasknumber][$i])) {

                        $ore_del_giorno=$dayoftasks[$tasknumber][$i][1];


                    } else {

                        $ore_del_giorno=null;

                    }
                     array_push($riga_task,$ore_del_giorno);
                }

            array_push($rows,$riga_task);
            $tasknumber++;
            }

        }

        $csv_save ='';

            foreach ($rows as $row) {
                if (!empty($row)) {
                    $comma = ';';
                    $quote = '"';
                    $CR = "\015\012";
                    // Make csv rows for field name
                    $i = 0;
                    // Make csv rows for data
                    $csv_values = '';
                    $i = 0;
                    $comma = ';';
                    foreach ($row as $val) {
                       $i++;
                       $csv_values .= $quote . $val . $quote . $comma;
                    }
                    $csv_values .= $CR;

                }
                $csv_save .= $csv_values;
            }

        echo $csv_save;

        $filename = "report.csv";

        //var_dump($filename);die;


        header("Content-Type: text/plain");
        header("Content-disposition: attachment; filename=$filename");
        header("Content-Transfer-Encoding: binary");
        header("Pragma: no-cache");
        header("Expires: 0");
        $this->_app->close();


    }

    public function createCalendario($data_inizio,$data_fine){

        $array_dei_mesi=[];
        $array_dei_giorni=[];
        $giorno_corrente_=[];
        $anno_inizio=$data_inizio->format('Y');
        $mese_inizio=$data_inizio->format('m');
        $data_inizio=date_create($anno_inizio.'-'.$mese_inizio.'-01');
        $anno_inizio=$data_inizio->format('Y');
        $mese_inizio=$data_inizio->format('m');
        $data_inizio=date_create($anno_inizio.'-'.$mese_inizio.'-01');
        $data_corrente=clone $data_inizio;
        $data_corrente_=clone $data_inizio;
        array_push($array_dei_mesi,clone $data_inizio);//AGGIUNGE IL PRIMO MESE
        $giorno_corrente_['data']=clone $data_corrente_;
        $taskModel=new ggpmModelTask();
        $giorno_corrente_['f']=$taskModel->isFestivo($data_corrente_);
        array_push($array_dei_giorni,$giorno_corrente_);
        while($data_corrente<$data_fine){
            $mese__corrente= clone date_add($data_corrente,date_interval_create_from_date_string("1 month"));
            if($mese__corrente<$data_fine)//previene l'aggiunta del mese successivo alla data di fine progetto
                array_push($array_dei_mesi,$mese__corrente);
        }

        while($data_corrente_<$data_fine){

            $giorno_corrente_['data']=clone date_add($data_corrente_,date_interval_create_from_date_string("1 day"));
            //$taskModel=new ggpmModelTask();
            $giorno_corrente_['f']=$taskModel->isFestivo($data_corrente_);
            array_push($array_dei_giorni,$giorno_corrente_);

        }
        //var_dump($data_fine);
        //var_dump($array_dei_mesi);die;
        return [$array_dei_mesi,$array_dei_giorni];

    }

}
