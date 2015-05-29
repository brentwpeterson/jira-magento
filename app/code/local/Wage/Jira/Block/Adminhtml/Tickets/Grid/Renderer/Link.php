<?php
class Wage_Jira_Block_Adminhtml_Tickets_Grid_Renderer_Link extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        return $this->_getValue($row);
    }
    protected function _getValue(Varien_Object $row)
    {      
        $val = $row->getData($this->getColumn()->getIndex());
	    $issueskey = $row->getData($this->getColumn()->getIssueskey());
        $url = Mage::getStoreConfig('jira/general/host').'/browse/'.$issueskey;
        $out = "<div><a href=$url target='_blank'>".$issueskey."</a></div>"; 
        return $out;
    }
}
