<?php
class Wage_Jira_Model_Tickets extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('jira/tickets');
    }

}
