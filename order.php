<?php
class Order{
	protected $id;
	protected $customerName;
	protected $trackingNumber;
	
	public function __construct($id, $customerName, $trackingNumber){
		$this->id = $id;
		$this->customerName = $customerName;
		$this->trackingNumber = $trackingNumber;
	}
	
	public function getGreeting(){
		return "Dear ".$this->customerName;
	}
	
	public function getThanksCZ(){
		return "děkuji Vám za Vaši objednávku. Objednávku s náhledy zboží si můžete zobrazit na této adrese: <a href=\"http://www.flakon.cz/viewOrder.php?order=".$this->id."\">www.flakon.cz/viewOrder.php?order=".$this->id."</a>";
	}
	
	public function getThanksEN(){
		return "Thank you for your order! You can view the order with the thumbnails of the goods on this address: <a href=\"http://www.flakon.cz/viewOrder.php?order=".$this->id."\">www.flakon.cz/viewOrder.php?order=".$this->id."</a>";
	}
} 
?>