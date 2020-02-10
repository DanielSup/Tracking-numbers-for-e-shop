
<head>
    <meta charset="UTF-8">
    <title></title>
</head>
<body>
<form name="odesilani" method="post" action="viewtrackingnumber.php">
<label>ID objednavky:</label>
<input type="text" name="order"/><br>
<input type="submit" name="odeslat" value="odeslat"/>
</form>
    <?php
		if(isset($_POST["odeslat"]) && isset($_POST["order"])){
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
			} else {
				$query2 = $db->query("SELECT * FROM ". DB_PREFIX ."tracking WHERE order_id=".$_POST["order"]);
				$tracking="Sledovaci cislo zasilky s objednavkou se zadanym ID je: ";
				foreach($query2->rows as $row){
					$tracking.= $row["tracking_number"].".";
				}
				if($tracking == "Sledovaci cislo zasilky s objednavkou se zadanym ID je: "){
					echo "Objednavka se zadanym ID bud neexistuje, nebo jeste nebyla odeslana.";
				} else {
					echo $tracking;
				}
			}
		}
    ?>
</body>
</html>

