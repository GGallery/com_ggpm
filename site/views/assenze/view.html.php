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

require_once JPATH_COMPONENT . '/models/dipendenti.php';
require_once JPATH_COMPONENT . '/models/assenze.php';

class ggpmViewAssenze extends JViewLegacy {

    public  $_filterparam;


    function display($tpl = null)
    {
        //JHtml::_('stylesheet', 'components/com_ggpm/libraries/css/bootstrap.min.css');
        //JHtml::_('stylesheet', 'components/com_ggpm/libraries/css/bootstrap-year-calendar.css');
        //JHtml::_('stylesheet', 'components/com_ggpm/libraries/css/bootstrap-year-calendar.min.css');
        JHtml::_('stylesheet', 'components/com_ggpm/libraries/open-iconic/font/css/open-iconic-bootstrap.css');
        JHtml::_('stylesheet', 'https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous');
        $this->_filterparam = new stdClass();
        $this->_filterparam->id_dipendente=JRequest::getVar('id_dipendente');
        $this->assenze = $this->getModel()->getAssenze($this->_filterparam->id_dipendente);

        parent::display($tpl);
    }
}
    