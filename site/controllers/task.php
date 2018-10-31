<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_contact
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
require_once JPATH_COMPONENT . '/models/task.php';

/**
 * Controller for single contact view
 *
 * @since  1.5.19
 */
class ggpmControllerTask extends JControllerLegacy
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
        $this->_filterparam->id_piano_formativo=JRequest::getVar('id_piano_formativo');
        $this->_filterparam->descrizione=JRequest::getVar('descrizione');
        $this->_filterparam->data_inizio=JRequest::getVar('data_inizio');
        $this->_filterparam->data_fine=JRequest::getVar('data_fine');
        $this->_filterparam->durata=JRequest::getVar('durata');
        $this->_filterparam->ore=JRequest::getVar('ore');
        $this->_filterparam->id_voce_costo=JRequest::getVar('id_voce_costo');
        $this->_filterparam->id_ruolo=JRequest::getVar('id_ruolo');
        $this->_filterparam->id_dipendente=JRequest::getVar('id_dipendente');
        $this->_filterparam->id_task_propedeutico=JRequest::getVar('id_task_propedeutico');
        $this->_filterparam->valore_orario=JRequest::getVar('valore_orario');

    }
    public function insert(){

        $model=new ggpmModeltask();
        if($model->insert($this->_filterparam->id_piano_formativo,$this->_filterparam->descrizione,$this->_filterparam->data_inizio,$this->_filterparam->data_fine,$this->_filterparam->durata,$this->_filterparam->ore,$this->_filterparam->id_voce_costo,$this->_filterparam->id_ruolo,
                            $this->_filterparam->id_dipendente,$this->_filterparam->id_task_propedeutico,$this->_filterparam->valore_orario)) {
            echo "1";
        }else{
            echo "0";
        }
        $this->_app->close();

    }

    public function delete(){

        $model=new ggpmModelTask();
        if($model->delete($this->_filterparam->id)) {
            echo "1";
        }else{
            echo "0";
        }
        $this->_app->close();

    }
    public function modify(){

        $model=new ggpmModelTask();
        if($model->modify($this->_filterparam->id,$this->_filterparam->id_piano_formativo,$this->_filterparam->descrizione,$this->_filterparam->data_inizio,$this->_filterparam->data_fine,
            $this->_filterparam->durata,$this->_filterparam->id_voce_costo,$this->_filterparam->ore,$this->_filterparam->id_ruolo,
                            $this->_filterparam->id_dipendente,$this->_filterparam->id_task_propedeutico,$this->_filterparam->valore_orario)) {
            echo "1";
        }else{
            echo "0";
        }
        $this->_app->close();

    }

    public function gettask(){

        $model=new ggpmModelTask();
        echo json_encode($model->getTask($this->_filterparam->id,null));
        $this->_app->close();

    }

    public function gestionegiornidaaggiungere(){

        $model=new ggpmModelTask();
        echo json_encode($model->gestioneGiornidaaggiungere($this->_filterparam->data_inizio,$this->_filterparam->durata,$this->_filterparam->id_dipendente));
        $this->_app->close();

    }

    public function verificacaricodipendente(){

        $model=new ggpmModelTask();
        echo json_encode($model->verificaCaricodipendente($this->_filterparam->id_dipendente,$this->_filterparam->data_inizio,$this->_filterparam->data_fine));
        $this->_app->close();
    }
}
