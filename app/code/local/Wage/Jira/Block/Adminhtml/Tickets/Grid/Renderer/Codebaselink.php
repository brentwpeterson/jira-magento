<?php
class Wage_Jira_Block_Adminhtml_Tickets_Grid_Renderer_Codebaselink extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        return $this->_getValue($row);
    }
    protected function _getValue(Varien_Object $row)
    {      
        $val = $row->getData($this->getColumn()->getIndex());
        $permalink = $row->getData($this->getColumn()->getCodebasepermalink());
        $ticketID = $row->getData($this->getColumn()->getCodebaseticketid());
        if($permalink && $ticketID) {
            $url = Mage::getStoreConfig('codebase/general/host').'/projects/'.$permalink.'/tickets/'.$ticketID;
            $out = "<div><a href=$url target='_blank'>Ticket-".$ticketID."</a></div>"; 
        }else{
            $out = '';
        }
        return $out;
    }
}
