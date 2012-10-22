<?php
// spiel.php
	function cgi_param($feld, $defdault = "") {
		// variable zunaechst auf default-wert setzen
		$var = $default;
		if (isset($_GET[$feld]) && $_GET[$feld] != "") {
			// get-feld gefunden
			$var = $_GET[$feld];
		} elseif (isset($_POST[$feld]) && $_POST[$feld] != "") {
			// post-feld gefunden
			$var = $_POST[$feld];
		}
		// ermittelten wert zurueckgeben
		return $var;
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Gewinnspiel</title>
		<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	</head>
	<body>
		<h1>Gewinnspiel</h1>
		<p>Beantworten Sie die abgebildeten Fragen und gewinnen Sie eine All-Inclkusive-Wochenendreise in eine europ&auml;ische Gro&szlig;stadt aus unserem Angebot</p>
		
		<?php
			// wurde die seite nach einem eingabefehler erneut aufgerufen?
			$fehler = cgi_param("fehler",0) {
				if($fehler) {
					echo "<p style=\"color:red\">Bitte alle Felder vollst&auml;ndig ausf&uuml;llen!</p>";
				}
		?>
		<form action="teilnahme.php" method="post">
			<?php
				// verbindungsparameter
				$host = 'localhost';
				$user = 'admin';
				$pw = 'passwd';
				$db = 'training_gewinnspiel';
				
				// verbindung zum mysql-server herstellen
				$conn = mysql_connect($host,$user,$pw) or die(mysql_error());
				mysql_select_db($conn,$db) or die(mysql_error());
				
				// abfrage senden
				$fr_sql = "SELECT fr_id, fr_frage, FROM gw_fragen ORDER BY fr_id ASC";
				$fr_query = mysql_query($fr_sql);
				
				// zeilen lesen und fragen stellen
				while (list($fr_id,$fr_frage) = mysql_fetch_row($fr_query)) {
					// fragetext ausgeben
					echo "<p><label>$fr_id. $fr_frage</label>";
					// antworten holen
					$an_sql = "SELECT an_antwort, an_text FROM gw_antworten WHERE an_frage = $fr_id ORDER BY an_antwort ASC";
					$an_query = mysql_query($an_sql);
					// radio-buttons und antworten ausgeben
					while (list($an_antwort, $an_text) = mysql_fetch_row($an_query)) {
						echo "<input type=\"radio\" name=\"f$fr_id\" value=\"$an_antwort\" />";
					}
					echo "</p>";
				}
				// db-verbindung schliessen
				mysql_close();
			?>
		
		<h2>pers&ouml;nliche Angaben</h2>
		<table border="0" cellpadding="4">
			<tr>
				<td>Benutzername</td>
				<td colspan="3"><input type="text" name="username" /></td>
			</tr>
			<tr>
				<td>Email</td>
				<td colspan="3"><input type="text" name="mail" /></td>
			</tr>
			<tr>
				<td colspan="4">Welche dieser St&auml;dte w&uuml;rden Sie in n&auml;chster Zeit gern besuchen?</td>
			</tr>
			<tr>
				<td><input type="radio" name="wish" value="paris" />&nbsp;Paris</td>
				<td><input type="radio" name="wish" value="london" />&nbsp;London</td>
				<td><input type="radio" name="wish" value="istanbul" />&nbsp;Istanbul</td>
				<td><input type="radio" name="wish" value="rom" />&nbsp;Rom</td>
			</tr>
		</table>
		<input type="submit" value="Am Gewinnspiel teilnehmen!" />
		
		</form>
		
	</body>
</html>