<?php
class Wage_Jira_Block_Adminhtml_Tickets extends Mage_Adminhtml_Block_Widget_Grid_Container {


  public function __construct()
  {
    $this->_controller = 'adminhtml_tickets';
    $this->_blockGroup = 'jira';
    $this->_headerText = Mage::helper('jira')->__('Tickets Manager');
    parent::__construct();
    $this->_removeButton('add');
  }

	
}
