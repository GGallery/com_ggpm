<?php

/**
 * @package		Joomla.Tutorials
 * @subpackage	Components
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		License GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die;

//require_once 'models/libs/errors/debug.class.php';

jimport('joomla.application.component.controller');

if(!defined('DS')){
    define('DS',DIRECTORY_SEPARATOR);
}



// Get an instance of the controller prefixed by webtv
$controller = JControllerLegacy::getInstance('ggpm');
// Perform the Request task

$controller->execute(JFactory::getApplication()->input->get('task'));//RS $controller->execute(JRequest::getCmd('task'));

// Redirect if set by the controller
$controller->redirect();
