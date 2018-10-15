<?php

// no direct access
defined('_JEXEC') or die('Restricted access');




jimport('joomla.application.component.controller');
jimport('joomla.access.access');


class ggpmController extends JControllerLegacy {

    private $_user;
    private $_japp;
    public  $_params;

    public function __construct($config = array())
    {
        parent::__construct($config);

        $this->_japp = JFactory::getApplication();


        JHtml::_('jquery.framework');


        JHtml::script(Juri::base() . 'components/com_ggpm/libraries/js/mediaelement-and-player.js');
        JHtml::script(Juri::base() . 'components/com_ggpm/libraries/js/bootstrap.min.js');



    }

}