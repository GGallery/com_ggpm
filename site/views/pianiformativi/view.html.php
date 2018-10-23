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

    public $piani_formativi, $id_piano_formativo_attivo, $budget,$descrizione_piano_formativo_attivo,$voci_costo,$totale,$array_ruolo_dipendente,$calendario_piano_formativo,$mesi;


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
        $this->budget=null;
        $vociModel=new ggpmModelVocicosto();
        $this->voci_costo=$vociModel->getVocicosto();
        $ruoliModel=new ggpmModelRuoli();
        $this->ruoli=$ruoliModel->getRuoli();
        $dipendentiModel=new ggpmModelDipendenti();
        $this->array_ruolo_dipendente=$dipendentiModel->getArrayRuoloDipendente();
        $this->totale=0;
        if($this->id_piano_formativo_attivo!=null){
            $model=new ggpmModelBudget();
            $this->budget=$model->getBudget(null,$this->id_piano_formativo_attivo);
            $this->descrizione_piano_formativo_attivo=$this->getModel()->getPianiformativi($this->id_piano_formativo_attivo)[0]['descrizione'];
            $taskModel=new ggpmModelTask();
            $this->task=$taskModel->getTask(null,$this->id_piano_formativo_attivo);
            $data_inizio=date_create($this->task[1]);
            $data_fine=date_create($this->task[2]);
            $this->calendario_piano_formativo=$this->createCalendario($data_inizio,$data_fine);
        }


        parent::display($tpl);
    }

    private function createCalendario($data_inizio,$data_fine){

          $array_to_return=[];
          $data_corrente=$data_inizio;
          array_push($array_to_return,clone $data_inizio);//AGGIUNGE IL PRIMO MESE
          while($data_corrente<$data_fine){
              $nuova= clone date_add($data_corrente,date_interval_create_from_date_string("1 month"));
              array_push($array_to_return,$nuova);
          }
          return $array_to_return;

    }
}
    