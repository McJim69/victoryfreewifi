<?php
	require("connect.php");
	require("header.php");
?>

<body>

<?php require("menunav.php");?>

<script>setActive("admin");</script>
<script>setActive("backup");</script>

<main id="main">
	<section id="breadcrumbs" class="breadcrumbs" >
		<div class="container" style="margin-bottom:-10px">
			<ol>
				<li><a href="index.php">Home</a></li>
				<li><a href="admin.php">Admin</a></li> 
				<li>DB Management</li> 
			</ol>
			<form method="post" enctype="multipart/form-data">
			<div><h2>
				<span>DB Management</span> &nbsp; 
				<span>
					<input class='btn btn-primary btn-sm' type='submit' name='backup' value="Backup DB" onclick="return confirm('Execute backup now?')" />
					<input class='btn btn-danger btn-sm' type='submit' name='dropDB' value=" Drop DB " onclick="return confirm('Are you sure you want to drop this databse?')" />
					<input class='btn btn-success btn-sm' type='button' value="Restore DB" onclick="$('#file').click();" />
					<input type='file' name='file' id='file' onchange="$('#upload').click();" style="display:none" />
					<input type='submit' value='Submit' name='upload' id="upload" style="display:none" />
				</span></h2>				
			</div>
			</form>
		</div>
	</section>
	<section style="margin-top:-45px;min-height:540px;" >
		<div class="container">
			<div class="row">		

			<?php
		
			if(isset($_POST["dropDB"])){
				header("location:eraseDatabase.php");
			}
			
			if(isset($_POST["backup"])){

				define("DB_USER", 'McJim');
				define("DB_PASSWORD", 'Restricted654123');
				define("DB_NAME", 'zdsfreewifi');
				define("DB_HOST", 'localhost');
				define("BACKUP_DIR", 'BACKUP'); 
				define("TABLES", '*'); 

				//define("TABLES", 'table1, table2, table3'); // Partial backup
				define("CHARSET", 'utf8');
				define("GZIP_BACKUP_FILE", true); // Set to false if you want plain SQL backup files (not gzipped)
				define("DISABLE_FOREIGN_KEY_CHECKS", true); // Set to true if you are having foreign key constraint fails
				define("BATCH_SIZE", 1000); // Batch size when selecting rows from database in order to not exhaust system memory
											// Also number of rows per INSERT statement in backup file
				class Backup_Database {
					var $dbHost;
					var $dbUser;
					var $dbPass;
					var $dbName;
					var $charset;
					var $conn;
					var $backupDir;
					var $backupFile;
					var $gzipBackupFile;
					var $output;
					var $disableForeignKeyChecks;
					var $batchSize;

					public function __construct($dbHost, $dbUser, $dbPass, $dbName, $charset = 'utf8') {
						$this->host                    = $dbHost;
						$this->username                = $dbUser;
						$this->passwd                  = $dbPass;
						$this->dbName                  = $dbName;
						$this->charset                 = $charset;
						$this->conn                    = $this->initializeDatabase();
						$this->backupDir               = BACKUP_DIR ? BACKUP_DIR : '.';
						$this->backupFile              = 'backup-'.$this->dbName.'-'.date("Ymd_His", time()).'.sql';
						$this->gzipBackupFile          = defined('GZIP_BACKUP_FILE') ? GZIP_BACKUP_FILE : true;
						$this->disableForeignKeyChecks = defined('DISABLE_FOREIGN_KEY_CHECKS') ? DISABLE_FOREIGN_KEY_CHECKS : true;
						$this->batchSize               = defined('BATCH_SIZE') ? BATCH_SIZE : 1000; // default 1000 rows
						$this->output                  = '';
					}

					protected function initializeDatabase() {
						try {
							$conn = mysqli_connect($this->host, $this->username, $this->passwd, $this->dbName);
							if (mysqli_connect_errno()) {
								throw new Exception('ERROR connecting database: ' . mysqli_connect_error());
								die();
							}
							if (!mysqli_set_charset($conn, $this->charset)) {
								mysqli_query($conn, 'SET NAMES '.$this->charset);
							}
						} catch (Exception $e) {
							print_r($e->getMessage());
							die();
						}
						return $conn;
					}

					public function backupTables($tables = '*') {
						try {
							if($tables == '*') {
								$tables = array();
								$result = mysqli_query($this->conn, 'SHOW TABLES');
								while($row = mysqli_fetch_row($result)) {
									$tables[] = $row[0];
								}
							} else {
								$tables = is_array($tables) ? $tables : explode(',', str_replace(' ', '', $tables));
							}
							$sql = 'CREATE DATABASE IF NOT EXISTS `'.$this->dbName."`;\n\n";
							$sql .= 'USE `'.$this->dbName."`;\n\n";

							if ($this->disableForeignKeyChecks === true) {
								$sql .= "SET foreign_key_checks = 0;\n\n";
							}

							foreach($tables as $table) {
								$this->obfPrint("Backing up `".$table."` table...".str_repeat('.', 50-strlen($table)), 0, 0);
								$sql .= 'DROP TABLE IF EXISTS `'.$table.'`;';
								$row = mysqli_fetch_row(mysqli_query($this->conn, 'SHOW CREATE TABLE `'.$table.'`'));
								$sql .= "\n\n".$row[1].";\n\n";
								$row = mysqli_fetch_row(mysqli_query($this->conn, 'SELECT COUNT(*) FROM `'.$table.'`'));
								$numRows = $row[0];
								$numBatches = intval($numRows / $this->batchSize) + 1; // Number of while-loop calls to perform
								for ($b = 1; $b <= $numBatches; $b++) {

									$query = 'SELECT * FROM `' . $table . '` LIMIT ' . ($b * $this->batchSize - $this->batchSize) . ',' . $this->batchSize;
									$result = mysqli_query($this->conn, $query);
									$realBatchSize = mysqli_num_rows ($result); // Last batch size can be different from $this->batchSize
									$numFields = mysqli_num_fields($result);
									if ($realBatchSize !== 0) {
										$sql .= 'INSERT INTO `'.$table.'` VALUES ';
										for ($i = 0; $i < $numFields; $i++) {
											$rowCount = 1;
											while($row = mysqli_fetch_row($result)) {
												$sql.='(';
												for($j=0; $j<$numFields; $j++) {
													if (isset($row[$j])) {
														$row[$j] = addslashes($row[$j]);
														$row[$j] = str_replace("\n","\\n",$row[$j]);
														$row[$j] = str_replace("\r","\\r",$row[$j]);
														$row[$j] = str_replace("\f","\\f",$row[$j]);
														$row[$j] = str_replace("\t","\\t",$row[$j]);
														$row[$j] = str_replace("\v","\\v",$row[$j]);
														$row[$j] = str_replace("\a","\\a",$row[$j]);
														$row[$j] = str_replace("\b","\\b",$row[$j]);
														if ($row[$j] == 'true' or $row[$j] == 'false' or preg_match('/^-?[0-9]+$/', $row[$j]) or $row[$j] == 'NULL' or $row[$j] == 'null') {
															$sql .= $row[$j];
														} else {
															$sql .= '"'.$row[$j].'"' ;
														}
													} else {
														$sql.= 'NULL';
													}
				 
													if ($j < ($numFields-1)) {
														$sql .= ',';
													}
												}
				 
												if ($rowCount == $realBatchSize) {
													$rowCount = 0;
													$sql.= ");\n"; //close the insert statement
												} else {
													$sql.= "),\n"; //close the row
												}
				 
												$rowCount++;
											}
										}
				 
										$this->saveFile($sql);
										$sql = '';
									}
								}
				 
								$sql.="\n\n";
								$this->obfPrint('SUCCESS!');
							}
							if ($this->disableForeignKeyChecks === true) {
								$sql .= "SET foreign_key_checks = 1;\n";
							}
							$this->saveFile($sql);
							if ($this->gzipBackupFile) {
								$this->gzipBackupFile();
							} else {
								$this->obfPrint('Backup file succesfully saved to ' . $this->backupDir.'/'.$this->backupFile, 1, 1);
							}
						} catch (Exception $e) {
							print_r($e->getMessage());
							return false;
						}
						return true;
					}

					protected function saveFile(&$sql) {
						if (!$sql) return false;
						try {
							if (!file_exists($this->backupDir)) {
								mkdir($this->backupDir, 0777, true);
							}
							file_put_contents($this->backupDir.'/'.$this->backupFile, $sql, FILE_APPEND | LOCK_EX);
						} catch (Exception $e) {
							print_r($e->getMessage());
							return false;
						}
						return true;
					}

					protected function gzipBackupFile($level = 9) {
						if (!$this->gzipBackupFile) {
							return true;
						}
						$source = $this->backupDir . '/' . $this->backupFile;
						$dest =  $source . '.gz';
						$this->obfPrint('Gzipping Backup File to ' . $dest . '.....', 1, 0);
						$mode = 'wb' . $level;
						if ($fpOut = gzopen($dest, $mode)) {
							if ($fpIn = fopen($source,'rb')) {
								while (!feof($fpIn)) {
									gzwrite($fpOut, fread($fpIn, 1024 * 256));
								}
								fclose($fpIn);
							} else {
								return false;
							}
							gzclose($fpOut);
							if(!unlink($source)) {
								return false;
							}
						} else {
							return false;
						}
				 
						$this->obfPrint('SUCCESS!');

						echo '<br><a href="'.$dest.'"><input class="btn btn-success btn" type=button value="Download Backup" /></a><br>';

						return $dest;
					}

					public function obfPrint ($msg = '', $lineBreaksBefore = 0, $lineBreaksAfter = 1) {
						if (!$msg) {
							return false;
						}
						if ($msg != 'SUCCESS!' and $msg != 'FAILED!') {
							$msg = date("Y-m-d H:i:s") . ' - ' . $msg;
						}
						$output = '';
						if (php_sapi_name() != "cli") {
							$lineBreak = "<br />";
						} else {
							$lineBreak = "\n";
						}
						if ($lineBreaksBefore > 0) {
							for ($i = 1; $i <= $lineBreaksBefore; $i++) {
								$output .= $lineBreak;
							}                
						}
						$output .= $msg;
						if ($lineBreaksAfter > 0) {
							for ($i = 1; $i <= $lineBreaksAfter; $i++) {
								$output .= $lineBreak;
							}                
						}
						$this->output .= str_replace('<br />', '\n', $output);
						echo $output;
						if (php_sapi_name() != "cli") {
							if( ob_get_level() > 0 ) {
								ob_flush();
							}
						}
						$this->output .= " ";
						flush();
					}
					public function getOutput() {
						return $this->output;
					}
					
				}

				error_reporting(E_ALL);
				// Set script max execution time
				set_time_limit(900); // 15 minutes
				if (php_sapi_name() != "cli") {
				echo '<div style="margin:20px;font-family: monospace;background:darkred;color:#FFF;border-radius:5px;padding:20px;">';
					echo '<a href="backup.php"><input class="btn btn-danger" type=button value=" Close Console " /></a><br><br>';
				}
				$backupDatabase = new Backup_Database(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, CHARSET);
				$result = $backupDatabase->backupTables(TABLES, BACKUP_DIR) ? 'SUCCESS!' : 'FAILED!';
				// $backupDatabase->obfPrint('Backup result: ' . $result, 1);
				// Use $output variable for further processing, for example to send it by email
				$output = $backupDatabase->getOutput();
				if (php_sapi_name() != "cli") {
					echo '</div><br>';
				}
			}

			//RESTORE
				if(isset($_POST["upload"])){

					move_uploaded_file($_FILES['file']['tmp_name'], "BACKUP/backup-sql.sql.gz");
					$file = "backup-sql.sql.gz"; 
				//	$fh = fopen($file, 'r'); 
				//	$text = fread($fh, filesize($file)); 
				//	fclose($fh); 

					// Define database parameters here
					define("DB_USER", 'McJim');
					define("DB_PASSWORD", 'Restricted654123');
					define("DB_NAME", 'zdsfreewifi');
					define("DB_HOST", 'localhost');
					define("BACKUP_DIR", 'BACKUP'); // Comment this line to use same script's directory ('.')
					define("BACKUP_FILE", $file); // Script will autodetect if backup file is gzipped based on .gz extension
					define("CHARSET", 'utf8');
					define("DISABLE_FOREIGN_KEY_CHECKS", true); // Set to true if you are having foreign key constraint fails

					//The Restore_Database class
					class Restore_Database {
						var $dbHost;
						var $dbUser;
						var $dbPass;
						var $dbName;
						var $charset;
						var $conn;
						var $disableForeignKeyChecks;

						function __construct($dbHost, $dbUser, $dbPass, $dbName, $charset = 'utf8') {
							$this->host                    = $dbHost;
							$this->username                = $dbUser;
							$this->passwd                  = $dbPass;
							$this->dbName                  = $dbName;
							$this->charset                 = $charset;
							$this->disableForeignKeyChecks = defined('DISABLE_FOREIGN_KEY_CHECKS') ? DISABLE_FOREIGN_KEY_CHECKS : true;
							$this->conn                    = $this->initializeDatabase();
							$this->backupDir               = defined('BACKUP_DIR') ? BACKUP_DIR : '.';
							$this->backupFile              = defined('BACKUP_FILE') ? BACKUP_FILE : null;
						}

						function __destructor() {
							if ($this->disableForeignKeyChecks === true) {
								mysqli_query($this->conn, 'SET foreign_key_checks = 1');
							}
						}

						protected function initializeDatabase() {
							try {
								$conn = mysqli_connect($this->host, $this->username, $this->passwd, $this->dbName);
								if (mysqli_connect_errno()) {
									throw new Exception('ERROR connecting database: ' . mysqli_connect_error());
									die();
								}
								if (!mysqli_set_charset($conn, $this->charset)) {
									mysqli_query($conn, 'SET NAMES '.$this->charset);
								}
								if ($this->disableForeignKeyChecks === true) {
									mysqli_query($conn, 'SET foreign_key_checks = 0');
								}
							} catch (Exception $e) {
								print_r($e->getMessage());
								die();
							}
							return $conn;
						}

						public function restoreDb() {
							try {
								$sql = '';
								$multiLineComment = false;
								$backupDir = $this->backupDir;
								$backupFile = $this->backupFile;
								$backupFileIsGzipped = substr($backupFile, -3, 3) == '.gz' ? true : false;
								if ($backupFileIsGzipped) {
									if (!$backupFile = $this->gunzipBackupFile()) {
										throw new Exception("ERROR: couldn't gunzip backup file " . $backupDir . '/' . $backupFile);
									}
								}
								$handle = fopen($backupDir . '/' . $backupFile, "r");
								if ($handle) {
									while (($line = fgets($handle)) !== false) {
										$line = ltrim(rtrim($line));
										if (strlen($line) > 1) { // avoid blank lines
											$lineIsComment = false;
											if (preg_match('/^\/\*/', $line)) {
												$multiLineComment = true;
												$lineIsComment = true;
											}
											if ($multiLineComment or preg_match('/^\/\//', $line)) {
												$lineIsComment = true;
											}
											if (!$lineIsComment) {
												$sql .= $line;
												if (preg_match('/;$/', $line)) {
													// execute query
													if(mysqli_query($this->conn, $sql)) {
														if (preg_match('/^CREATE TABLE `([^`]+)`/i', $sql, $tableName)) {
															$this->obfPrint("Table succesfully created: `" . $tableName[1] . "`");
														}
														$sql = '';
													} else {
														throw new Exception("ERROR: SQL execution error: " . mysqli_error($this->conn));
													}
												}
											} else if (preg_match('/\*\/$/', $line)) {
												$multiLineComment = false;
											}
										}
									}
									fclose($handle);
								} else {
									throw new Exception("ERROR: couldn't open backup file " . $backupDir . '/' . $backupFile);
								} 
							} catch (Exception $e) {
								print_r($e->getMessage());
								return false;
							}
							if ($backupFileIsGzipped) {
								unlink($backupDir . '/' . $backupFile);
							}
							return true;
						}

						protected function gunzipBackupFile() {
							$bufferSize = 4096; // read 4kb at a time
							$error = false;
							$source = $this->backupDir . '/' . $this->backupFile;
							$dest = $this->backupDir . '/' . date("Ymd_His", time()) . '_' . substr($this->backupFile, 0, -3);
							$this->obfPrint('Gunzipping backup file ' . $source . '... ', 1, 1);
							if (file_exists($dest)) {
								if (!unlink($dest)) {
									return false;
								}
							}
							if (!$srcFile = gzopen($this->backupDir . '/' . $this->backupFile, 'rb')) {
								return false;
							}
							if (!$dstFile = fopen($dest, 'wb')) {
								return false;
							}
							while (!gzeof($srcFile)) {
								if(!fwrite($dstFile, gzread($srcFile, $bufferSize))) {
									return false;
								}
							}
							fclose($dstFile);
							gzclose($srcFile);
							return str_replace($this->backupDir . '/', '', $dest);
						}

						public function obfPrint ($msg = '', $lineBreaksBefore = 0, $lineBreaksAfter = 1) {
							if (!$msg) {
								return false;
							}
							$msg = date("Y-m-d H:i:s") . ' - ' . $msg;
							$output = '';
							if (php_sapi_name() != "cli") {
								$lineBreak = "<br />";
							} else {
								$lineBreak = "\n";
							}
							if ($lineBreaksBefore > 0) {
								for ($i = 1; $i <= $lineBreaksBefore; $i++) {
									$output .= $lineBreak;
								}                
							}
							$output .= $msg;
							if ($lineBreaksAfter > 0) {
								for ($i = 1; $i <= $lineBreaksAfter; $i++) {
									$output .= $lineBreak;
								}                
							}
							if (php_sapi_name() == "cli") {
								$output .= "\n";
							}
							echo $output;
							if (php_sapi_name() != "cli") {
								ob_flush();
							}
							flush();
						}
					}
					error_reporting(E_ALL);
					set_time_limit(900); // 15 minutes
					if (php_sapi_name() != "cli") {
					echo '<div style="margin:20px;font-family: monospace;background:darkred;color:#FFF;border-radius:5px;padding:20px;">';
					echo '<a href="backup.php"><input class="btn btn-danger btn-sm" type=button value=" Close Console " /></a><br>';
					}
					$restoreDatabase = new Restore_Database(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
					$result = $restoreDatabase->restoreDb(BACKUP_DIR, BACKUP_FILE) ? 'SUCCESS!' : 'FAILED!';
					$restoreDatabase->obfPrint("Restoration result: ".$result, 1);
					if (php_sapi_name() != "cli") {
						echo '</div><br>';
						unlink("BACKUP/backup-sql.sql.gz");
					}
				}
			?>
				
			</div>		
		</div>
	</section>
</main>

<?php require("footer.php");?>

</body>

</html>

<script>
	function deleteTeam(cid){	
		if(confirm("Are you sure you want to Remove this Team Member?")){
			xmlhttp.onreadystatechange=function(){
				if (xmlhttp.readyState==4 && xmlhttp.status==200){
					if(xmlhttp.responseText=="Success"){
						$("#div_"+cid).animate({
					opacity:0
					},500);
				}else{
					alert(xmlhttp.responseText);
					}
					$("#div_"+cid).animate({
					opacity:0
					},500);
				}
			}					
			xmlhttp.open("GET","delete_team.php?cid="+cid,true);
			xmlhttp.send();
		}
	}
</script>

<script>	
	if ( window.history.replaceState ) {
	  window.history.replaceState( null, null, window.location.href );
	}
</sript>