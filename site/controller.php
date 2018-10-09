<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

require_once 'administrator/components/com_gglms/models/libs/debugg/debugg.php';
require_once JPATH_COMPONENT . '/helpers/output.php';
require_once JPATH_COMPONENT . '/helpers/utility.php';
require_once JPATH_COMPONENT . '/models/contenuto.php';
require_once JPATH_COMPONENT . '/models/unita.php';
require_once JPATH_COMPONENT . '/models/users.php';

jimport('joomla.application.component.controller');
jimport('joomla.access.access');


class gglmsController extends JControllerLegacy {

    private $_user;
    private $_japp;
    public  $_params;

    public function __construct($config = array())
    {
        parent::__construct($config);

        $this->_japp = JFactory::getApplication();


        JHtml::_('jquery.framework');


        JHtml::script(Juri::base() . 'components/com_gglms/libraries/js/mediaelement-and-player.js');
        JHtml::script(Juri::base() . 'components/com_gglms/libraries/js/bootstrap.min.js');

    }

}