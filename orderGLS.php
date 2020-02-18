<?php
require_once "order.php";

class OrderGLS extends Order {
	public function __construct($id, $customerName, $trackingNumber){
		parent::__construct($id, $customerName, $trackingNumber);
	}
	
	public function getMailBody(){
		return 'Dobrý den,<br>'.parent::getThanksCZ().' Dnes jsem odeslal zásilku společností GLS. Sledovací číslo zásilky je '.$this->trackingNumber.

						". Pohyb zásilky k vám můžete sledovat na stránkách GLS po zadání sledovaciho čísla.<br><a href=\"https://gls-group.eu/EU/en/parcel-tracking\">

						https://gls-group.eu/EU/en/parcel-tracking</a><br>Přeji Vám hezký den,<br>Marcel Šup<br><br>

						".parent::getGreeting()."<br>".parent::getThanksEN()."

						Today I gave the consignment shipping company GLS. The tracking number of the consignment is ".$_POST["trackingNumber"].". 

						You can watch the movement of the consignment to you on the website of GLS after entering the tracking number.<br>

						<a href=\"https://gls-group.eu/EU/en/parcel-tracking\">

						https://gls-group.eu/EU/en/parcel-tracking</a>

						<br>

						Have a nice day!<br>

						Marcel";
	}
}
?>