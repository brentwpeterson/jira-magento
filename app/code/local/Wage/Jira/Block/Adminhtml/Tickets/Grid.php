<?php
class Wage_Jira_Block_Adminhtml_Tickets_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('ticketsGrid');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('jira/tickets')->getCollection();
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {

        $this->addColumn('issues_id', array(
            'header'    =>Mage::helper('jira')->__('Ticket Id'),
            'index'     =>'issues_id',
            'type'      =>'number'
        ));

        $this->addColumn('project_name', array(
            'header' => Mage::helper('jira')->__('Project'),
            'index' => 'project_name',

        ));
        $this->addColumn('assignee', array(
            'header'    =>Mage::helper('jira')->__('Assignee'),
            'index'     =>'assignee',
        ));
       
        $this->addColumn('summary', array(
            'header' => Mage::helper('jira')->__('Summary'),
            'index' => 'summary',

        ));

        $this->addColumn('issue_type', array(
            'header' => Mage::helper('jira')->__('Type'),
            'index' => 'issue_type',

        ));
        $this->addColumn('priority', array(
            'header' => Mage::helper('jira')->__('Priority'),
            'index' => 'priority',

        ));
        $this->addColumn('status', array(
            'header' => Mage::helper('jira')->__('Status'),
            'index' => 'status',

        ));
       
        $this->addColumn('Jira Link', array(
            'header' => Mage::helper('jira')->__('Jira Link'),
            'align' => 'left',
            'index' => 'ticket_id',
            'width'     => '70',
            'renderer' => 'Wage_jira_Block_Adminhtml_Tickets_Grid_Renderer_Link',
            'issueskey' => 'issues_key'
        ));

         $this->addColumn('Codebase Link', array(
            'header' => Mage::helper('jira')->__('Codebase Link'),
            'align' => 'left',
            'index' => 'ticket_id',
            'width'     => '70',
            'renderer' => 'Wage_jira_Block_Adminhtml_Tickets_Grid_Renderer_Codebaselink',
            'codebaseticketid' => 'codebase_ticket_id',
            'codebasepermalink' => 'codebase_permalink',
        ));

        $this->addColumn('updated_at', array(
            'header'    => Mage::helper('customer')->__('Updated At'),
            'index'     => 'updated_at',
            'type'      => 'datetime',
            'width'     => '70',
        ));
        $this->addExportType('*/*/exportCsv', Mage::helper('jira')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('jira')->__('XML'));
        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setFormFieldName('ids');
        //$this->getMassactionBlock()->setUseSelectAll(false);

        $this->getMassactionBlock()->addItem('clone_ticket', array(
            'label'=> Mage::helper('jira')->__('Clone To Codebase'),
            'url'  => $this->getUrl('*/*/createCodebaseTicket'),
        ));

        
        return $this;
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }

}