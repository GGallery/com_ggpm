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

class ggpmViewPianiformativi extends JViewLegacy {

    public $piani_formativi, $id_piano_formativo_attivo, $budget,$descrizione_piano_formativo_attivo,$voci_costo;


    function display($tpl = null)
    {
        //JHtml::_('stylesheet', 'components/com_ggpm/libraries/css/bootstrap.min.css');
        JHtml::_('stylesheet', 'components/com_ggpm/libraries/open-iconic/font/css/open-iconic-bootstrap.css');
        JHtml::_('stylesheet', 'https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous');

        $this->piani_formativi=$this->getModel()->getPianiformativi();
        $this->id_piano_formativo_attivo=JRequest::getVar('id_piano_formativo_attivo');
        $this->budget=null;
        $vociModel=new ggpmModelVocicosto();
        $this->voci_costo=$vociModel->getVocicosto();
        if($this->id_piano_formativo_attivo!=null){
            $model=new ggpmModelBudget();
            $this->budget=$model->getBudget(null,$this->id_piano_formativo_attivo);
            $this->descrizione_piano_formativo_attivo=$this->getModel()->getPianiformativi($this->id_piano_formativo_attivo)[0]['descrizione'];

        }
        parent::display($tpl);
    }
}
    