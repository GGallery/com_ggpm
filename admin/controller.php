<?php

/**
 * @package		Joomla.Tutorials
 * @subpackage	Component
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		License GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die;

require_once 'models/libs/getid3/getid3.php';
require_once 'models/libs/debugg/debugg.php';

//require_once JPATH_COMPONENT.DS.'models'.DS.'libs'.DS.'debugg'.DS.'debugg.php';
jimport('joomla.application.component.controller');

class ggpmController extends JControllerLegacy {

    public function __construct($config = array()) {
        parent::__construct($config);


    }

    public function __destruct() {
    }

    function display($cachable = false, $urlparams = false) {
        gglmsHelper::addSubmenu('messages');
//        echo  $this->sidebar = JHtmlSidebar::render();  //RS
        
        JRequest::setVar('view', JRequest::getCmd('view', 'contents'));
        parent::display($cachable);
    }



}
