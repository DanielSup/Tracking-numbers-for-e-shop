
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
  <option value="d1">Česká pošta</option>
  <option value="d2">GLS</option>
  <option value="d3">Náš přepravce</option>
</select><br>
<input type="submit" name="odeslat" value="odeslat"/>
</form>
    <?php
		if(isset($_POST["odeslat"]) && isset($_POST["order"]) && isset($_POST["trackingNumber"])){
			require 'PHPMailerAutoload.php';
			require_once '../config.php';
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
				$osloveni = "Dear ";
				foreach($query->rows as $row){
					$osloveni .= $row["shipping_firstname"]."!";
				}
				$query2 = $db->query("SELECT * FROM ". DB_PREFIX ."tracking WHERE order_id=".$_POST["order"]);
				$cnt = 0;
				foreach($query2->rows as $row){
					$cnt++;
				}
				if($osloveni == "Dear "){
					echo "Objednavka se zadanym ID neexistuje.";
				} else if ($cnt != 0) {
					echo "Sledovaci cislo je k objednavce s danym ID jiz prirazeno.";
				} else {
					$thanksCZ="děkuji Vám za Vaši objednávku. Objednávku s náhledy zboží si můžete zobrazit na této adrese: <a href=\"http://www.flakon.cz/viewOrder.php?order=".$_POST["order"]."\">www.flakon.cz/viewOrder.php?order=".$_POST["order"]."</a>";
					$thanksEN="Thank you for your order! You can view the order with the thumbnails of the goods on this address: <a href=\"http://www.flakon.cz/viewOrder.php?order=".$_POST["order"]."\">www.flakon.cz/viewOrder.php?order=".$_POST["order"]."</a>";
					$mail = new PHPMailer;
					$mail->CharSet = 'UTF-8';
					$mail->setFrom('flakoncz@gmail.com', 'Marcel Sup');
					foreach($query->rows as $row){
						$mail->addAddress($row["email"]); 
					}
					$mail->addAddress('flakoncz@gmail.com'); 
					$mail->Subject = 'Odeslání zásilky - www.flakon.cz / Sending of the consignment - www.flakon.cz';
					if($_POST["sluzba"] == "d1"){
						$mail->Body = 'Dobrý den,<br>'.$thanksCZ.' Dnes jsem odeslal zásilku Českou poštou. Sledovací číslo zásilky je '.$_POST["trackingNumber"].
						". Pohyb zásilky k vám můžete sledovat na stránkách České pošty.<br><a href=\"https://www.postaonline.cz/trackandtrace/-/zasilka/cislo?parcelNumbers="
						.$_POST["trackingNumber"]."\">https://www.postaonline.cz/trackandtrace/-/zasilka/cislo?parcelNumbers="
						.$_POST["trackingNumber"]."</a><br>Přeji Vám hezký den,<br>Marcel Šup<br><br>"
						.$osloveni."<br>".$thanksEN."
						Today I sent your consignment by Czech post. The tracking number is ".$_POST["trackingNumber"].". You can watch the movement of the consignment to you with this tracking number on the website of Czech post.
						<br><a href=\"https://www.postaonline.cz/trackandtrace/-/zasilka/cislo?parcelNumbers="
						.$_POST["trackingNumber"]."\">https://www.postaonline.cz/trackandtrace/-/zasilka/cislo?parcelNumbers="
						.$_POST["trackingNumber"]."</a><br>
						Have a nice day!<br>
						Marcel
						";
					} else if($_POST["sluzba"] == "d2") {
						$mail->Body = 'Dobrý den,<br>'.$thanksCZ.' Dnes jsem odeslal zásilku společností GLS. Sledovací číslo zásilky je '.$_POST["trackingNumber"].
						". Pohyb zásilky k vám můžete sledovat na stránkách GLS po zadání sledovaciho čísla.<br><a href=\"https://gls-group.eu/EU/en/parcel-tracking\">
						https://gls-group.eu/EU/en/parcel-tracking</a><br>Přeji Vám hezký den,<br>Marcel Šup<br><br>
						".$osloveni."<br>".$thanksEN."
						Today I gave the consignment shipping company GLS. The tracking number of the consignment is ".$_POST["trackingNumber"].". 
						You can watch the movement of the consignment to you on the website of GLS after entering the tracking number.<br>
						<a href=\"https://gls-group.eu/EU/en/parcel-tracking\">
						https://gls-group.eu/EU/en/parcel-tracking</a>
						<br>
						Have a nice day!<br>
						Marcel
						";
					} else {
						$mail->Body = 'Dobrý den,<br>'.$thanksCZ.' Dnes jsem odeslal zásilku. Sledovací číslo zásilky je '.$_POST["trackingNumber"].
						'.<br>Přeji Vám hezký den,<br>Marcel Šup<br><br>
						'.$osloveni."<br>".$thanksEN."
						Today I gave the consignment shipping company GLS. The tracking number of the consignment is ".$_POST["trackingNumber"].".<br>
						Have a nice day!<br>
						Marcel
						";
					}
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

