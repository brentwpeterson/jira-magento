<?php

class Wage_Jira_Model_Jira extends Wage_Jira_Model_Abstract
{
    public function getData()
    {
    	return $this->get('search?jql=project=CEP');
    }

    /* All Projects Methods */

    public function getAllProject()
    {
    	return $this->get('project?expand=description,lead,url,projectKeys');
    }

    public function getProjectById($id)
    {
    	return $this->get('project/'.$id);
    }

    public function loadProjectById($projectId)
    {

        $obj = Mage::getModel('jira/projects')->getCollection()
            ->addFieldToFilter('project_id',$projectId)
            ->getFirstItem();
        return $obj;
    }

    public function importProjects()
    {
        $projects = $this->getAllProject();
        $projectArray = array();
        if(count($projects) > 0)
        {
            foreach($projects as $proj){
                $loadedProject = $this->loadProjectById($proj['id']);
                $projectArray[] = $proj['id'];
                $data = array();
                if($loadedProject->getEntityId()) {
                    $project = Mage::getModel('jira/projects')->load($loadedProject->getEntityId());
                    $data = $project->getData();
                } else {
                    $project = Mage::getModel('jira/projects');
                }
                $data['project_name'] = $proj['name'];
                $data['project_id'] = $proj['id'];
                $data['key'] = $proj['key'];

                if($loadedProject->getEntityId()) {
                    $project->setEntityId($loadedProject->getEntityId());
                    $project->setId($project->getId());
                    $project->setData($data);
                } else {
                    $project->setData($data);
                }

                try {
                    $project->save();
                } catch(Exception $e) {
                    if(Mage::getStoreConfigFlag("jira/general/jiralog")) {
                        Mage::log($e->getMessage(),null,"wage_jira.log");
                    }
                }
            }
        }
    }

    /* All Tickets Methods */

    public function getTicketsByProject($projectkey, $maxResults, $startAt)
    {
    	return $this->get('search?jql=project='.$projectkey.'&maxResults='.$maxResults.'&startAt='.$startAt.'&fields=issuetype,project,resolution,assignee,created,priority,updated,status,description,summary,reporter');

    }

    public function loadTicketByKey($ticketKey)
    {
        $obj = Mage::getModel('jira/tickets')->getCollection()
            ->addFieldToFilter('issues_key',$ticketKey)
            ->getFirstItem();
        return $obj;
    }

    public function importTickets()
    {
    	$resource = Mage::getSingleton('core/resource');
    	$table = $resource->getTableName('jira/tickets');
        $readConnection = $resource->getConnection('core_read');
        $writeConnection = $resource->getConnection('core_write');
        $writeConnection->beginTransaction();


       	$projectKey = explode(',', Mage::getStoreConfig("jira/projects/projectskey"));
    	foreach($projectKey as $key)
    	{
    		$ProjectTestTickets = $this->getTicketsByProject($key, 1, 0);

    		$k = 0;
			$ProjectTotalCountTickets = ceil($ProjectTestTickets['total'] / 1000);
			for($i=1 ; $i<=$ProjectTotalCountTickets; $i++)
			{
				unset($ProjectTickets);
			    $ProjectTickets = $this->getTicketsByProject($key, 1000, $k);

			    foreach($ProjectTickets['issues'] as $issuesKey)
	    		{
	                unset($avail_ticket);
	    			$avail_ticket = $this->loadTicketByKey($issuesKey['key']);							
	                try {
				            if($avail_ticket->getData('id')) {
				                // Update Entry
				                $__fields = array();
				                $__fields['issues_id'] = $issuesKey['id'];
				    			$__fields['issues_key'] = $issuesKey['key'];
				    			$__fields['summary'] = $issuesKey['fields']['summary'];
				    			$__fields['description'] = $issuesKey['fields']['description'];
				    			$__fields['issue_type'] = $issuesKey['fields']['issuetype']['name'];
				    			$__fields['project_name'] = $issuesKey['fields']['project']['name'];
				    			$__fields['project_key'] = $issuesKey['fields']['project']['key'];
				    			$__fields['resolution'] = $issuesKey['fields']['resolution']['name'];
				    			$__fields['priority'] = $issuesKey['fields']['priority']['name'];
				    			$__fields['assignee'] = $issuesKey['fields']['assignee']['displayName'];
				    			$__fields['reporter'] = $issuesKey['fields']['reporter']['displayName'];
				    			$__fields['updated_at'] = $issuesKey['fields']['updated'];
				    			$__fields['created_at'] = $issuesKey['fields']['created'];
				    			$__fields['status'] = $issuesKey['fields']['status']['name'];
				                $__where = $writeConnection->quoteInto('id =?', $avail_ticket->getData('id'));
				                $writeConnection->update($table, $__fields, $__where);
				            } else {
				                // Insert Entry
				                $fields = array();
				                $fields['issues_id'] = $issuesKey['id'];
				    			$fields['issues_key'] = $issuesKey['key'];
				    			$fields['summary'] = $issuesKey['fields']['summary'];
				    			$fields['description'] = $issuesKey['fields']['description'];
				    			$fields['issue_type'] = $issuesKey['fields']['issuetype']['name'];
				    			$fields['project_name'] = $issuesKey['fields']['project']['name'];
				    			$fields['project_key'] = $issuesKey['fields']['project']['key'];
				    			$fields['resolution'] = $issuesKey['fields']['resolution']['name'];
				    			$fields['priority'] = $issuesKey['fields']['priority']['name'];
				    			$fields['assignee'] = $issuesKey['fields']['assignee']['displayName'];
				    			$fields['reporter'] = $issuesKey['fields']['reporter']['displayName'];
				    			$fields['updated_at'] = $issuesKey['fields']['updated'];
				    			$fields['created_at'] = $issuesKey['fields']['created'];
				    			$fields['status'] = $issuesKey['fields']['status']['name'];
				                $writeConnection->insert($table, $fields);
				            }
				            $writeConnection->commit();
	                    	
	                } catch(Exception $e) {
	                    if(Mage::getStoreConfigFlag("jira/general/jiralog")) {
	                        Mage::log($e->getMessage(),null,"wage_jira.log");
	                    }
	                }
	    		}	
			    $k += 1000;
			}    					
    		//break;
    	}
    }


}