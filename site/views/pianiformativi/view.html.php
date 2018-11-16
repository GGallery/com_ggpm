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
require_once JPATH_COMPONENT . '/controllers/pianiformativi.php';

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
            $piano_formativo=$this->getModel()->getPianiformativi($this->id_piano_formativo_attivo)[0];
            $this->descrizione_piano_formativo_attivo=$piano_formativo['descrizione'];
            $this->data_fine_piano_formativo=$piano_formativo['data_fine'];

            $taskModel=new ggpmModelTask();
            $this->task=$taskModel->getTask(null,$this->id_piano_formativo_attivo,null);
            $data_inizio=date_create($this->task[1]);
            $data_fine=date_create($this->task[2]);
            $controller=new ggpmControllerPianiformativi();
            $this->calendario_piano_formativo=$controller->createCalendario($data_inizio,$data_fine);
            $this->cruscottodipendentipiano=$dipendentiModel->getCruscottodipendenti($this->id_piano_formativo_attivo);
        }

        if($this->id_task_to_modify!=null){

            $taskModel=new ggpmModelTask();
            $this->task_to_modify=$taskModel->getTask($this->id_task_to_modify,null,null)[0][0];
            //var_dump($this->task_to_modify);die;

        }

        parent::display($tpl);
    }




}
    