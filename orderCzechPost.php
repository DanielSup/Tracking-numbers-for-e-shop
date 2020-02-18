<?php
require_once "order.php";

class OrderCzechPost extends Order{
	public function __construct($id, $customerName, $trackingNumber){
		parent::__construct($id, $customerName, $trackingNumber);
	}
	
	public function getMailBody(){
		return 'Dobrý den,<br>'.parent::getThanksCZ().' Dnes jsem odeslal zásilku Českou poštou. Sledovací číslo zásilky je '.$this->trackingNumber.

						". Pohyb zásilky k vám můžete sledovat na stránkách České pošty.<br><a href=\"https://www.postaonline.cz/trackandtrace/-/zasilka/cislo?parcelNumbers="

						.$this->trackingNumber."\">https://www.postaonline.cz/trackandtrace/-/zasilka/cislo?parcelNumbers="

						.$this->trackingNumber."</a><br>Přeji Vám hezký den,<br>Marcel Šup<br><br>"

						.parent::getGreeting()."<br>".parent::getThanksEN()."

						Today I sent your consignment by Czech post. The tracking number is ".$this->trackingNumber.". You can watch the movement of the consignment to you with this tracking number on the website of Czech post.

						<br><a href=\"https://www.postaonline.cz/trackandtrace/-/zasilka/cislo?parcelNumbers="

						.$this->trackingNumber."\">https://www.postaonline.cz/trackandtrace/-/zasilka/cislo?parcelNumbers="

						.$this->trackingNumber."</a><br>

						Have a nice day!<br>

						Marcel";
	}
} 
?>