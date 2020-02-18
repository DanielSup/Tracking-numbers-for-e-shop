
<head>
    <meta charset="UTF-8">
    <title></title>
</head>
<body>
<form name="odesilani" method="post" action="sledovacicisla.php">
<label>ID objednavky:</label>
<input type="text" name="order"/><br>
<label>Sledovaci cislo zasilky:</label>
<input type="text" name="trackingNumber"/><br>
<label>Prepravni sluzba:</label>
<select name="sluzba" size="1">
  <option value="CzechPost">Česká pošta</option>
  <option value="GLS">GLS</option>    <option value="Zasilkovna">Zásilkovna</option>
  <option value="OurShipping">Náš přepravce</option>
</select><br>
<input type="submit" name="odeslat" value="odeslat"/>
</form>
    <?php
		if(isset($_POST["odeslat"]) && isset($_POST["order"]) && isset($_POST["trackingNumber"])){
			require 'PHPMailerAutoload.php';
			require_once '../config.php';						require_once './order'.$_POST["sluzba"].'.php';
			require_once(DIR_SYSTEM . 'startup.php');
			require_once(DIR_DATABASE . 'mysql.php');
			$need_configs = array(
				'config_url',
				'config_ssl',
				'config_customer_group_id',
				'config_language'
			);

			// Config
			$config = new Config();
			$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
			$setting_query = $db->query("SELECT * FROM " . DB_PREFIX . "setting s WHERE s.key IN('".implode("','",$need_configs)."')");
			foreach ($setting_query->rows as $setting) {
				$config->set($setting['key'], $setting['value']);
			}
			unset($setting_query);
			if($_POST["order"] == ""){
				echo "Nebylo zadano ID objednavky.";
			} else if($_POST["trackingNumber"] == ""){
				echo "Nebylo zadano sledovaci cislo.";
			} else {
				$query = $db->query("SELECT * FROM ". DB_PREFIX ."order WHERE order_id=".$_POST["order"]);
				$customer = "";
				foreach($query->rows as $row){
					$customer .= $row["shipping_firstname"]."!";
				}
				$query2 = $db->query("SELECT * FROM ". DB_PREFIX ."tracking WHERE order_id=".$_POST["order"]);
				$cnt = 0;
				foreach($query2->rows as $row){
					$cnt++;
				}
				if($customer == ""){
					echo "Objednavka se zadanym ID neexistuje.";
				} else if ($cnt != 0) {
					echo "Sledovaci cislo je k objednavce s danym ID jiz prirazeno.";
				} else {
					$mail = new PHPMailer;
					$mail->CharSet = 'UTF-8';
					$mail->setFrom('flakoncz@gmail.com', 'Marcel Sup');
					foreach($query->rows as $row){
						$mail->addAddress($row["email"]); 
					}
					$mail->addAddress('flakoncz@gmail.com'); 
					$mail->Subject = 'Odeslání zásilky - www.flakon.cz / Sending of the consignment - www.flakon.cz';										$classname = "Order".$_POST["sluzba"];												$order = new $classname($_POST["order"], $customer, $_POST["trackingNumber"]);										$mail->Body = $order->getMailBody();
					$mail->IsHTML(true);
					$mail->WordWrap = 60;  
					if(!$mail->send()) {
						echo 'Message could not be sent.';
						echo 'Mailer Error: ' . $mail->ErrorInfo;
					} else {
						$query3 = $db->query("INSERT INTO ". DB_PREFIX ."tracking (order_id, tracking_number) VALUES ('".$_POST["order"]."', '".$_POST["trackingNumber"]."')");
						echo 'Message has been sent';
					}
				}
			}
		}
    ?>
</body>
</html>

