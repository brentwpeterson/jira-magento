<?php

class Wage_Jira_Adminhtml_TicketsController extends Mage_Adminhtml_Controller_action
{

	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('jira/tickets')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Tickets Manager'), Mage::helper('adminhtml')->__('Tickets Manager'));
		
		return $this;
	}   
 
	public function indexAction() {
		$this->_initAction()
			->renderLayout();
	}

    public function createCodebaseTicketAction()
    {
        $codebase = Mage::getModel('codebase/codebase');
        $ticketIds = $this->getRequest()->getParam('ids');

        foreach ($ticketIds as $id) {

            $model = Mage::getModel('jira/tickets')->load($id);

            $project = Mage::getStoreConfig("jira/projects/projectspermalink");
            $params['summary'] = $model->getSummary();
            $params['description'] = $model->getDescription();
            $params['assignee-id'] = Mage::getStoreConfig("jira/projects/assignee");
            
            try {
                $ticketAdded = $codebase->addTicket($project,$params);
                $ticketId = $ticketAdded['ticket-id']; 
                $projectId = $ticketAdded['project-id'];  

                $projects = Mage::getModel('codebase/projects')->loadProjectByProjectId($projectId);
                $permalink = $projects->getPermalink();
                if($permalink && $ticketId)
                {
                    $model->setCodebaseTicketId($ticketId);
                    $model->setCodebasePermalink($permalink);
                    $model->save();
                }

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('jira')->__('Ticket was created in Codebase'));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }

        }
        $this->_redirect('*/*/');
    }

    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('jira/adminhtml_tickets_grid')->toHtml()
        );
    }
  
    public function exportCsvAction()
    {
        $fileName   = 'tickets.csv';
        $content    = $this->getLayout()->createBlock('jira/adminhtml_tickets_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'tickets.xml';
        $content    = $this->getLayout()->createBlock('jira/adminhtml_tickets_grid')
            ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }

    protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream')
    {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK','');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename='.$fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }
}
