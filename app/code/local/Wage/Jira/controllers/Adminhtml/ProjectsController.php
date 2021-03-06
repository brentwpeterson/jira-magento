<?php

class Wage_Jira_Adminhtml_ProjectsController extends Mage_Adminhtml_Controller_action
{

	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('jira/projects')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Project Manager'), Mage::helper('adminhtml')->__('Project Manager'));
		
		return $this;
	}   
 
	public function indexAction() {
		$this->_initAction()
			->renderLayout();
	}
  
    public function exportCsvAction()
    {
        $fileName   = 'projects.csv';
        $content    = $this->getLayout()->createBlock('jira/adminhtml_projects_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('jira/adminhtml_projects_grid')->toHtml()
        );
    }

    public function exportXmlAction()
    {
        $fileName   = 'projects.xml';
        $content    = $this->getLayout()->createBlock('jira/adminhtml_projects_grid')
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
