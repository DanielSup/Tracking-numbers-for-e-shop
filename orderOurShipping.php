<?php
require_once "order.php";

class OrderOurShipping extends Order{
	public function __construct($id, $customerName, $trackingNumber){
		parent::__construct($id, $customerName, $trackingNumber);
	}
	
	public function getMailBody(){
		return 'Dobrý den,<br>'.$this->getThanksCZ().' Dnes jsem odeslal zásilku. Sledovací číslo zásilky je '.$this->trackingNumber.

				'.<br>Přeji Vám hezký den,<br>Marcel Šup<br><br>

				'.parent::getGreeting()."<br>".parent::getThanksEN()."

				Today I gave the consignment shipping company GLS. The tracking number of the consignment is ".$this->trackingNumber.".<br>

				Have a nice day!<br>

				Marcel";
	}
}
?>