<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_contact
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
require_once JPATH_COMPONENT . '/models/dipendenti.php';

/**
 * Controller for single contact view
 *
 * @since  1.5.19
 */
class ggpmControllerDipendenti extends JControllerLegacy
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
        $this->_filterparam->nome=JRequest::getVar('nome');
        $this->_filterparam->cognome=JRequest::getVar('cognome');
        $this->_filterparam->valore_orario=JRequest::getVar('valore_orario');
        $this->_filterparam->monte_ore=JRequest::getVar('monte_ore');

    }
    public function insert(){

        $model=new ggpmModelDipendenti();
        if($model->insert($this->_filterparam->nome,$this->_filterparam->cognome,$this->_filterparam->valore_orario,$this->_filterparam->monte_ore)) {
            echo "1";
        }else{
            echo "0";
        }
        $this->_app->close();

    }

    public function delete(){

        $model=new ggpmModelDipendenti();
        if($model->delete($this->_filterparam->id)) {
            echo "1";
        }else{
            echo "0";
        }
        $this->_app->close();

    }
    public function modify(){

        $model=new ggpmModelDipendenti();
        if($model->modify($this->_filterparam->id, $this->_filterparam->nome,$this->_filterparam->cognome,$this->_filterparam->valore_orario,$this->_filterparam->monte_ore)) {
            echo "1";
        }else{
            echo "0";
        }
        $this->_app->close();

    }

    public function getdipendentevaloreorario(){

        $model=new ggpmModelDipendenti();
        $dipendente=$model->getDipendenti($this->_filterparam->id);

        echo $dipendente[0]['valore_orario'];
        $this->_app->close();


    }

}
