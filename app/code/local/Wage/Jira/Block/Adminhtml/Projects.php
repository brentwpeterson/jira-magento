<?php
class Wage_Jira_Block_Adminhtml_Projects extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_projects';
    $this->_blockGroup = 'jira';
    $this->_headerText = Mage::helper('jira')->__('Projects Manager');
    parent::__construct();
      $this->_removeButton('add');

  }
}
