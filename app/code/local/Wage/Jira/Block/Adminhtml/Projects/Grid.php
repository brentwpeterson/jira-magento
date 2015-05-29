<?php
class Wage_Jira_Block_Adminhtml_Projects_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('projectsGrid');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('jira/projects')->getCollection()
                    ->setOrder('project_name','ASC');

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
      $this->addColumn('project_id', array(
          'header'    => Mage::helper('jira')->__('Project ID'),
          'align'     =>'left',
          'width'     => '50px',
          'index'     => 'project_id'
      ));

      $this->addColumn('project_name', array(
          'header'    => Mage::helper('jira')->__('Project Name'),
          'align'     =>'left',
          'width'     => '100px',
          'index'     => 'project_name'

      ));

      $this->addColumn('key', array(
          'header'    => Mage::helper('jira')->__('Project Key'),
          'align'     =>'left',
          'width'     => '100px',
          'index'     => 'key'

      ));
        
  		$this->addExportType('*/*/exportCsv', Mage::helper('jira')->__('CSV'));
  		$this->addExportType('*/*/exportXml', Mage::helper('jira')->__('XML'));
  	  
      return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setFormFieldName('ids');
        $this->getMassactionBlock()->setUseSelectAll(false);
        
        return $this;
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }

}
