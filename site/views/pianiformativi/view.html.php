<?php

/**
 * @version		1
 * @package		webtv
 * @author 		antonio
 * @author mail	tony@bslt.it
 * @link
 * @copyright	Copyright (C) 2011 antonio - All rights reserved.
 * @license		GNU/GPL
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

jimport('joomla.application.component.helper');

require_once JPATH_COMPONENT . '/models/budget.php';
require_once JPATH_COMPONENT . '/models/vocicosto.php';
require_once JPATH_COMPONENT . '/models/ruoli.php';
require_once JPATH_COMPONENT . '/models/task.php';
require_once JPATH_COMPONENT . '/models/dipendenti.php';

class ggpmViewPianiformativi extends JViewLegacy {

    public $piani_formativi, $id_piano_formativo_attivo, $budget,$descrizione_piano_formativo_attivo,$voci_costo,$totale,$budget_utilizzato,$array_ruolo_dipendente,$calendario_piano_formativo,$mesi,$cruscottodipendenti,$cruscottodipendentipiano,$id_task_to_modify,$task_to_modify;


    function display($tpl = null)
    {
        //JHtml::_('stylesheet', 'components/com_ggpm/libraries/css/bootstrap.min.css');
        JHtml::_('stylesheet', 'components/com_ggpm/libraries/open-iconic/font/css/open-iconic-bootstrap.css');
        JHtml::_('stylesheet', 'https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous');

        $this->mesi = array(1=>['gennaio',31], ['febbraio',28], ['marzo',31], ['aprile',30],
            ['maggio',31], ['giugno',30], ['luglio',31], ['agosto',31],
            ['settembre',30], ['ottobre',31], ['novembre',30],['dicembre',31]);
        $this->piani_formativi=$this->getModel()->getPianiformativi();
        $this->id_piano_formativo_attivo=JRequest::getVar('id_piano_formativo_attivo');
        $this->id_task_to_modify=JRequest::getVar('id_task_to_modify');
        $this->budget=null;
        $vociModel=new ggpmModelVocicosto();
        $this->voci_costo=$vociModel->getVocicosto();
        $ruoliModel=new ggpmModelRuoli();
        $this->ruoli=$ruoliModel->getRuoli();
        $dipendentiModel=new ggpmModelDipendenti();
        $this->array_ruolo_dipendente=$dipendentiModel->getArrayRuoloDipendente();
        $this->totale=0;
        $this->cruscottodipendenti=$dipendentiModel->getCruscottodipendenti();
        if($this->id_piano_formativo_attivo!=null){
            $model=new ggpmModelBudget();
            $this->budget=$model->getBudget(null,$this->id_piano_formativo_attivo);
            $this->descrizione_piano_formativo_attivo=$this->getModel()->getPianiformativi($this->id_piano_formativo_attivo)[0]['descrizione'];
            $taskModel=new ggpmModelTask();
            $this->task=$taskModel->getTask(null,$this->id_piano_formativo_attivo,null);
            $data_inizio=date_create($this->task[1]);
            $data_fine=date_create($this->task[2]);
            $this->calendario_piano_formativo=$this->createCalendario($data_inizio,$data_fine);
            $this->cruscottodipendentipiano=$dipendentiModel->getCruscottodipendenti($this->id_piano_formativo_attivo);
        }

        if($this->id_task_to_modify!=null){

            $taskModel=new ggpmModelTask();
            $this->task_to_modify=$taskModel->getTask($this->id_task_to_modify,null,null)[0][0];
            //var_dump($this->task_to_modify);die;

        }

        parent::display($tpl);
    }

    private function createCalendario($data_inizio,$data_fine){

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
    