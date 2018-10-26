<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_contact
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
require_once JPATH_COMPONENT . '/models/assenze.php';

/**
 * Controller for single contact view
 *
 * @since  1.5.19
 */
class ggpmControllerAssenze extends JControllerLegacy
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
        $this->_filterparam->id_dipendente=JRequest::getVar('id_dipendente');
        $this->_filterparam->data_inizio=JRequest::getVar('data_inizio');
        $this->_filterparam->data_fine=JRequest::getVar('data_fine');
        $this->_filterparam->causale=JRequest::getVar('causale');
        //$this->_filterparam->valore_orario=JRequest::getVar('valore_orario');


    }
    public function insert(){

        $model=new ggpmModelAssenze();
        if($model->insert($this->_filterparam->id_dipendente,$this->_filterparam->data_inizio,$this->_filterparam->data_fine,$this->_filterparam->causale)) {
            echo "1";
        }else{
            echo "0";
        }
        $this->_app->close();

    }

    public function delete(){

        $model=new ggpmModelAssenze();
        if($model->delete($this->_filterparam->id)) {
            echo "1";
        }else{
            echo "0";
        }
        $this->_app->close();

    }

    public function getassenze(){

        $model=new ggpmModelAssenze();
        echo json_encode($model->getAssenze($this->_filterparam->id_dipendente));
        $this->_app->close();
    }


}
