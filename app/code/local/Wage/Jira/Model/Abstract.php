<?php

class Wage_Jira_Model_Abstract {

	public function __construct(){

        $this->username = Mage::getStoreConfig('jira/general/username');
        $this->password = Mage::getStoreConfig('jira/general/password');
        $this->hostname = Mage::getStoreConfig('jira/general/host');
        $this->secure = 's';
        $this->debug = Mage::getStoreConfigFlag("jira/general/jiralog");
        $this->url = $this->hostname.'/rest/api/2/';
	}
	
	public function debugLog($log){
		if($this->debug){
			Mage::log($log,null,"wage_jira.log");
		}
	}

    protected function post($url=null,$json=null) {
        return $this->request($url,$json,1);
    }
    protected function put($url=null,$json=null) {
        return $this->putrequest($url,$json);
    }
    protected function get($url=null, $json=null) {
        return json_decode($this->request($url, $json), 1);
    }
    protected function delete($url=null) {
        return $this->request($url,null,0);
    }

    private function request($url=null,$post=null) {
        $this->debugLog("url: ".$this->url.$url);
        $ch = curl_init($this->url.$url);

        if($post) {
            curl_setopt($ch, CURLOPT_POST, $post);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }

        $headers = array(
            'Content-Type: application/json'
        );
        try {
            curl_setopt($ch, CURLOPT_USERPWD, $this->username.':'.$this->password);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            $output = curl_exec($ch);
        } catch (Exception $e){
            $this->debugLog("error: ".$e->getMessage());
        }
        //$this->debugLog("response: ".$output);
        if(!$output || strlen($output)==1) {
            return false;
        } else {
            return $output;
        }
        curl_close($ch);
    }

    private function putrequest($url=null,$post=null) {

        $this->debugLog("url: ".$this->url.$url);
        $ch = curl_init($this->url.$url);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

        $headers = array(
            'Content-Type: application/json'
        );
        try {
            curl_setopt($ch, CURLOPT_USERPWD, $this->username.':'.$this->password);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            $output = curl_exec($ch);
        } catch (Exception $e){
            $this->debugLog("error: ".$e->getMessage());
        }
        $this->debugLog("response: ".$output);
        if(!$output || strlen($output)==1) {
            return false;
        } else {
            return $output;
        }
        curl_close($ch);
    }
 

}

