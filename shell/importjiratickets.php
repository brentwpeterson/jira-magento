<?php
require_once 'abstract.php';
class Wagento_Shell_Importjiratickets extends Mage_Shell_Abstract {
    public function run() {
        try{
            $tickets = Mage::getModel('jira/jira')->importTickets();
            echo "Tickets imported successfully";
        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }

}
$shell = new Wagento_Shell_Importjiratickets();
$shell->run();