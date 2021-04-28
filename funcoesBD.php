<?php
require_once "funcoes.php";
require_once "funcoesArquivo.php";
require_once "funcoesImagens.php";
require_once "funcoesDataHora.php";
class DBFunctions {
	use Functions, FileFunctions, DateTimeFunctions, ImageFunctions;
	/* $count is an important variable used in the function save() and in the function valueFormat(), for saving operation in a database. This will be seen later. */
	private $count = 0;
	public $con;
	/* The function Connect() is used to create a conection between the application and MySQL. Also, it connects the application to a give database in MySQL.
	
	The parameter $host is the IP or the url where MySQL is. The parameters $user and $password are the user name and the password used to connect to MySQL. $DB is the name of a database that will be used by the application.
	 */
	public function Connect ($host, $DB, $user, $password) {
		//mysqli_query($con, "create database if not exists $DB");
		global $status;
		/* The variable $connection receives the result of the connection between the application and MySQL. If it fails, the global variable $status receives an error message related to the failed connection.*/
		$connection=mysqli_connect($host,$user,$password);
		if(!$connection){
			echo $status = "An error occured while connecting.";
			exit;
		} 
		/* The variable $database receives the result of the application attempt to connect to the database whose name is $DB. If this fails, $status receives an error message saying that the database wasn't found.*/
		$database=mysqli_select_db($connection, $DB);
		if(!$database){
			echo $status = "The database was not found.";
			exit;
		}
		$this->con = $connection;
		return $connection;
	}
	
	public function ValorSelecionado ($POST, $rs, $pk, $acao, $value) {
		$n = 0; 
		$sel = false; 
		if (!empty($rs)) {       
			while ($row = mysqli_fetch_array($rs)) {
				$n = $row[$pk];
				if ($POST[$acao.$n]  == $value) {
					$sel = true;
					break;
				}
			}
		}
		$ret = array($n, $sel);
		return $ret;
	}
	
	// The function GetPrimaryKeys() returns all the primary keys of $table, and also the number of primary keys it has.
	public function GetPrimaryKeys($table) {
		// The SQL code executed below gets the names of the primary keys of $table.
		$con = $this->con; 
		$rs = mysqli_query($con, "show keys from $table where key_name = 'PRIMARY'");
		$ret = array();	// The names of the primary keys will be added to $ret.
		$i = 0;
		//If $rs is a valid result set of the SQL command execution above, then the code snippet that adds the primary keys names to $ret is executed.
		if ($rs && mysqli_num_rows($rs) > 0) {
			while ($row = mysqli_fetch_array($rs)) {
				$ret[$i] = $row['Column_name'];
				$i++; // $i will be the number of primary keys of $table, and will be returned along with $ret.
			}
		}
		$ret = array($ret, $i);
		return $ret;
	}
	/*
	The function select() will execute a SQL select command and return a variable $args2 with all the values taken from the datababe through this SQL command. Also, if the array $args is not null, its values will become global variables and receive the values taken from the database.
	
	The $sql parameter contains the sql command that will take data from the database.
	
	The $args parameter contains global PHP variables names that will be created by the function eval ($code), and will receive values that will be available to be displayed on the page, these variables names must be without $. If the result set of the SQL select execution in this function has more than one row, then each variable in args will be turned into vectors containg all the values of its correspondent databae field. $args is an optional parameter, whose default value is array().
	
	*/
	public function select($sql, $args=array(), $isHTML=FALSE) {
		$con = $this->con; 
		$args2 = array(); // This variable will receive each value of the data taken from the database by the $sql command.
		global $status; // The global variable $status will receive in this function the message of success or failure of this select operation.
		//global $result; // The variable $result will receive the result set of this select operation, and it is declared as a global variable, so that it can be available for use globally.
		$result = mysqli_query($con, $sql);
		if (mysqli_error($con)) $status = mysqli_error($con);
		$j = 0;
		/* Below we have the creation of the global variables whose names are in $args, in case $result has only one row. Then each of these variables will receive its correspondent value. So that we won't get an error from the execution of the code snippet below, we have to test if $result is a valid variable and if we get a row from it.*/
		$vars_exist = (!empty($args));
		$c1 = (!is_array($args)) ? 0 : count($args);
		if ($result && ($row = mysqli_fetch_array($result, MYSQLI_NUM)) && mysqli_num_rows($result) === 1 && $vars_exist) {
			while ($j < $c1){
				$r = htmlentities($row[$j], ENT_QUOTES, 'UTF-8', true);
				if ($isHTML || !is_string($row[$j]))
					$r = $row[$j];
				eval("global \$".$args[$j].';');
				eval("\$".$args[$j].' = "$r";');
				$j++;
			}
		}
		// After executing the code snippet above, we have to return to the beginning of the result set $result.
		if ($result && mysqli_num_rows($result) > 0) mysqli_data_seek($result, 0);
		/* Below we have the data take from the database by the $sql command being assigned to the matrix $args2, which will be returned by this function. Also, these data will be assigned to the variables whose names are in $args, if it is not empty, and each of these names will be vectors containing all the values of its corresponding database field according to the $sql command, if the number of rows of $result is more than 1.*/
		if ($result && mysqli_num_rows($result) > 0) $is_matrix = (mysqli_num_rows($result) > 1);
		if ($result) { 
			$k = 0;
			while ($row = mysqli_fetch_array($result)) {
				$keys = array_keys($row);
				$c2 = (!is_array($row)) ? 0 : count($row);
				for ($i = 0; $i < $c2; $i++) {
					$r = htmlentities($row[$i], ENT_QUOTES, 'UTF-8', true);
					if ($isHTML || !is_string($row[$i]))
						$r = $row[$i];
					if ($vars_exist && $is_matrix && $i < $j) {
						eval("\$".$args[$i]."[\$k] = '$r';");
					} 
					$args2[$k][$i] = $r;
					$key = $keys[2*$i+1];
					$args2[$k][$key] = $r;
				}
				$k++;
			}
		} 
		return $args2;
	}
	
	/* The function valueFormat() is used to format each value that will be used in SQL codes. This formatted value will be added to an insert or update SQL code that is being created by the function save() below or to a select SQL code that is being created by the function editingMode() below. Some values, for instance, like the ones of the type varchar or datetime, must be surrounded by '' in the SQL code. When the value is a file, it means that the SQL code being built is an insert or update SQL code. So the file name added to its destination address in the server is stored in the database as a varchar, and the file is saved in the folder defined in the address cited. This way valueFormat() defines how a given value will be used in an SQL code.
	
	The parameter $type contains the database type of the value being formatted, and the parameter $value is the value being formatted. $addresses are the addresses of folders where files will be saved, whenever a value being formatted is a file. $addresses is an array of addresses and is an optional parameter. All the values to be saved by an insert or update operation are formatted before, and if there are files among them, their addresses are in the parameter $addresses.*/
	public function valueFormat($type, $value, $addresses=array()) {
		$con = $this->con; 
		global $status; // $status is the global variable that shows the result of an operation performed by the user. It's a success or failure message.
		try{
			$field = '';  // $field is the variable that will receive the formatted value.
			$count = $this->count; // $count is the current index of the array $addresses. It will be increased by 1 when $value is a file, to point to the following address.
			/* The command below is performed so that the SQL code being created automatically can be not case-sensitive*/
			$type = strtolower($type); 
			if (strpos($type, 'int') !== FALSE) {
				$field = (int) $value;
			} elseif (in_array($type, array('numeric', 'decimal', 'float', 'double'))) {
				$field = (float) $value;
			} elseif ($type == 'varchar') {
				$field = "'".mysqli_real_escape_string($con, $value)."'"; // If the value type is varchar, the only thing to do is to surround the value by '', in order to be ready to be used in a SQL code.
			} elseif ($type == 'blob') {
				/* If the value to be formatted is a file, the procedure to prepare images to be saved is slightly different from the one to prepare other files to be stored. In this case, the value is an array of data related to a file and it's taken from the PHP variable $_FILES. $values['name'] is the name of a file sent through the form. The first thing to do is save the file in the folder defined in one of the values of $addresses. */
				$is_image = $this->is_image($value['name']); 
				if (!empty($value['name'])) {
					$file = $value;
					$e = $addresses[$count]; // $e receives the address where the current file will be saved.
					$this->mkdirs($e);	// $this->mkdirs() is a function useful to create all the folders that is in the path $e but don't exist.
					// $destination receives the file name with the complete address where it will be saved, if this address was not previously added to this file name.
					$destination = $file['name']; 
					if (strpos($destination, $e) === FALSE) $destination = $e."/".$destination;
					// When a file is sent through the form, a temporary file is automatically generated on the server, which contains the content of this original file sent from the client. The name of this themporary file is $value['tmp_name']. If it is empty, an error occurred on the file sending.
					$thumbnail = ($is_image && strpos(strtoupper($file['name']), "THUMB") !== FALSE);
					$move = false;
					try {
						if (!$thumbnail && !empty($value['tmp_name'])) {
							$move = copy($file['tmp_name'],$destination);
						}
					} catch (Exception $e) {
						echo ($e->getMessage());
					}
					if (!$thumbnail && !empty($value['tmp_name']) && $move) {
						/* If the file isn't an image, there is no need to create a thumbnail of it with the function Thumb(). If it's an image, then a thumbnail image will be created from it, whose width will be 90 px. Also, the original image will be resized in case its width is more than 1024 px. It means that the maximum width of the original image saved in the server will be 1024 px. This is also done through the function Thumb().*/
						if ($is_image){
							$this->Thumb($file, $destination, $destination, 1024);	
						}	
						$status .= "<br>Arquivo enviado corretamente.";
					} elseif ($thumbnail) {
						$from = substr($file['name'], 5);
						$folder = $addresses[$count-1];
						if (strpos($from, $folder) === FALSE) $from = $folder."/".$from;
						$this->Thumb($file, $from, $destination, 90);	
						$status .= "<br>Arquivo enviado corretamente.";
					}
					else {
						/* If an error occurs with the transfering of the file to the server, then the variable $status will show the error that happened. If it's not one of the four error below, then only the number of the error will be shown. */
						switch($file['error']) {
							case UPLOAD_ERR_INI_SIZE:
								$status = 'O tamanho do arquivo excede o tamanho m&aacute;ximo permitido.';
							break;
							case UPLOAD_ERR_FORM_SIZE:
								$status = 'O arguivo enviado &eacute; muito grande.';
							break;
							case UPLOAD_ERR_PARTIAL:
								$status = 'O upload n&atilde;o se completou.';
							break;
							case UPLOAD_ERR_NO_FILE:
								$status = 'Nenhuum arquivo foi fornecido para upload.';
							break;
							default: 
								if (isset($file['error'])) $status = 'The error '.$file['error'].' occured while transfering the file.';
							break;	
						}
					}
					/* As seen above, the variable $destination contains the file name added to the address where it will be saved in the server. Thus the file is saved in the server outsite the database. What will be saved in the database is the content of $destination, which must be between '' to be formatted for the SQL insert or update code. */
					$field = "'$destination'"; 
					$this->count++;
				} else {
					/* If no file was uploaded, then an empty string will be saved in the database. */
					$field = "''";
				}
			/* If the value type is date, time or datetime, then the first thing to do is change the value format to the format that the database accepts for date and/or time. Values of these data type also must be between '' for the SQL code being created.*/	
			} elseif (!empty($value) && $this->strtotime2($value)) {
				if  ($type == 'date') {
					$field = "'".$this->db_date_create($value)."'";
				} elseif ($type == 'time') {
					$field = "'".$this->db_time_create($value)."'";	
				} elseif ($type == 'datetime'){
					$field = "'".$this->db_datetime_create($value)."'";
				}
			/* If the value type is a numeric type and the value is empty, then what will be saved is the number 0, but if it's empty and not numeric, the value saved will be an empty string. */
			} elseif (empty($value)) {
				if (($type == "integer" || $type == "numeric" || $type == "boolean"))
					$field = 0;
				else $field = "''";
			}
			/* If the value type is any other one, then there is no need of formatting the value. It will be added to the SQL code being created just as it came from the form. */
			else $field = $value;
		} catch (Exception $e) {
			$status = $e->getMessage();
		}
		return $field; // the formatted value is returned to be added to the SQL code being created by other function.
	}
	
	/* The function editingMode() is used to check if the part of a form relative to a given table is or isn't in the editing mode, that is, if the data shown through this part of the form were taken from a database or are new data to be inserted.
	
	The parameter $table is the database table whose inputs in the form are being tested by this function, which checks whether they are or aren't in the editing mode. 
	
	The parameter $values contains values of the $table fields which are its primary keys. These fields names are first taken from $table through the function GetPrimaryKeys(). Then a SQL command is executed to check if the values in the array $values exist in the table defined in $table. If so, then the form is in the editing mode in relation to $table, because this means that the values in this part of the form exist in the database.
	
	The parameter $types is used to format the values in $values so that they can be used in the SQL code that will check if these values exist in $table.
	*/
	public function editingMode ($table, $values, $types) {
		$PK = $this->GetPrimaryKeys($table); // The first thing is to get the primary keys of $table.
		// The first value is formatted for the query that checks if the form in relation to $table is being edited.
		$values[0] = $this->valueFormat($types[0], $values[0]); 
		$sql = "select ".$PK[0][0]." from $table"; // The cited query begins to be created.
		// If the first value is NULL, then it receives the value 0.
		if ($values[0] == NULL) $values[0] = 0; 
		/* The query that tests if the part of the form relative to $table is being edited selects the value of the first primary key where each primary key field value is equal to their respective values in the array $values. If this first primary key value exists in the table, then the form is in the editing mode. */
		$aux = " where ".$PK[0][0]." = ".$values[0];
		for ($i = 1; $PK[0][$i] != NULL; $i++) {
			$values[$i] = $this->valueFormat($types[$i], $values[$i]);
			$aux .= " and ".$PK[0][$i]." = ".$values[$i]; 
		}
		$sql .= $aux;
		global $pk;
		$pk = NULL;
		$this->select($sql, array("pk")); //The value of the first primary key is assigned to the variable $pk.
		$editing = true;
		if ($pk === NULL) $editing = false;
		/* The variable $editing tells if the form is or isn't in the editing mode in relation to $table. It is returned by this function along with the part of the query after the where clause. */
		return array($editing, $aux);
	}
	
	/*
	The function save() is used for inserting or updating data in a database. It's useful to make the process of updating a database become significantly easier. This function creates the insert or update SQL code used to save data in $table by means of its parameters.
	
	The parameter $table is the database table that will be updated or where data will be inserted into.
	
	The parameter $fields contains the names of the fields of $table where a record will be updated or where data will be inserted into.
	
	The parameter $types contains the types of the database fields whose names are in the array $fields. These types are used to format the values that will be saved in $table, so that they can be used by the SQL code that this function creates.
	
	The parameter $value contains the values that will be saved in $table.
	
	The parameter $addresses contains the server destination addresses where files will be saved and is an optional parameter. When the type of the value being saved is blob, what will be saved in the database is the destination address of the file added to its name, and the file itself will be saved in the address cited.  
	*/
	public function save($table, $fields, $types, $values, $addresses=array(), $indexes=NULL){
		try{
			$con = $this->con;
			$count = $this->count; 
			global $status; // The global variable used to show success or failure messages when an operation is performed.
			$code = 0;
			$PK = $this->GetPrimaryKeys($table);
			// The boolean variable $auto_increment will be true if $table has only one auto-increment primary key.
			$rs = mysqli_query($con, "show create table $table");
			$row = mysqli_fetch_array($rs, MYSQLI_NUM);
			$auto_increment = (strpos($row[1], 'AUTO_INCREMENT') !== FALSE);
			if ($indexes !== NULL) {
				if (!is_array($indexes)) {
					if ($indexes === -1) {
						for ($i = 0; $fields[$i] !== NULL; $i++) {
							$f = $fields[$i];
							$values[$i] = $values[$f];
						}
					} else {
						for ($i = 0; $fields[$i] !== NULL; $i++) {
							$f = $fields[$i].$indexes;
							$values[$i] = $values[$f];
						}
					}
				} else {
					for ($i = 0; $fields[$i] !== NULL; $i++) {
						$f = $fields[$i].$indexes[0].'_'.$indexes[1];
						$values[$i] = $values[$f];
					}
				}
			}
			/* The first thing is to test if the part of the form relative to $table is in the editing mode. If so, $table will be updated, otherwise, data will be inserted into it. */
			$edit = $this->editingMode ($table, $values, $types);
			// The if below creates and executes an insert SQL code, if the part of the form related to $table is not being edited.
			if (!$edit[0]) {
				$i = ($auto_increment && $PK[0][0] == $fields[0]) ? 1 : 0; 
				// The insert SQL code begins to be created, using the table name in it.
				$sql = "insert into $table (";
				// The fields names are included in the SQL code, and global variables whose names are the fields names are created too.
				while ($fields[$i] != NULL) {
					$sql .= $fields[$i].', ';
					eval ("global \$".$fields[$i].";");
					$i++;
				}
				// Now the values begin to be included in the SQL code. Before it, the comma after the last field name is taken off the SQL code.
				$sql = substr($sql, 0, strlen($sql)-2).') values (';
				$i = ($auto_increment && $PK[0][0] == $fields[0]) ? 1 : 0;
				/* Below, the vales are formatted in order to be suitable to be used by the SQL code and then they are added to it. Also, the values are assigned to the global variables whose names are the fields names.*/
				while ($fields[$i] != NULL) {
					$value = $this->valueFormat($types[$i], $values[$i], $addresses);
					if ($value == NULL) $value = 0;
					$sql .= $value.', ';
					if (is_array($values[$i]) && $this->is_image($values[$i]['name'])) eval ("\$".$fields[$i]." = '".$values[$i]['name']."';");
					//else eval ("\$".$fields[$i]." = $value;");
					$i++;
				}
				// Below, the comma after the last value is taken off the SQL code, and then a ')' is added to it to complete its building.
				$sql = substr($sql, 0, strlen($sql)-2).')';
				/* Below, the insert SQL code is executed if the array $types is not empty, because it doesn't make sense to execute it if no field or value was provided to it. */
				if (!empty($types)) {
					//mysqli_query($con, "insert into queries (is_editing, sql_string, dt_sql) values (0, '".addslashes($sql)."', '".date('Y-m-d H:i:s')."')");
					mysqli_query($con, $sql);
					if (mysqli_error($con))
						$status = mysqli_error($con);
				}
				/* When the table where data will be inserted into has only one primary key and it is auto-increment, then this primary key won't be visible to the user, and it is generated in the data inserting process. In this case, the code snippet below takes from the database the value of the last primary key, that is, the primary key value of the record that has just been inserted into $table, and assigns it to the variable $code, so that it can be used by the form.*/
				if ($PK[1] == 1) {
					if ($auto_increment) {
						$rs = mysqli_query($con, "select max(".$PK[0][0].") from $table");
						$row = mysqli_fetch_array($rs, MYSQLI_NUM);
						$code = (int) $row[0];
					} else $code = $values[0];
					// A global variable, whose name is the name of the primary key of $table, is created, and its value is assigned to it.
					eval ("global \$".$fields[0].";");
					eval ("\$".$fields[0]." = $code;");
				}
				/* An insert operation will set the form to be in the editing mode. */
				$edit[0] = true;
			/* If the form is in the editing mode, the saving operation will be a data updating in $table*/
			} else {
				$i = 0;
				$sql = "update $table set "; // the update code begins to be built.
				$value = ''; // $value receives the current value formatted to be added to the SQL update code.
				/* The while below creates the part of the code where the fields receives their values, like "field1 = value1, field2 = 'value2', ..." */
				while ($fields[$i] != NULL) {
					// If the current field is a file, it will be updated only if its value is not empty, so that the file name stored in this field won't be replaced by an empty value in case the current register is updated. 
					if ($types[$i] !== 'blob'|| strlen($values[$i]['name']) >= 5) {
						$sql .= $fields[$i].' = ';
						$value = $this->valueFormat($types[$i], $values[$i], $addresses);
						// A global variable whose name is the current field name is created, and its respective value is assigned to it.
						eval ("global \$".$fields[$i].";");
						/* If the value is an image file, then the formatted value will be something like "'image.jpg', 'Thumbimage.jpg', ". But this can't be used in the SQL update code. For this, it's necessary to add to the formatted value something like "Thumbimagefile = " before 'Thumbimage.jpg'. So it will become something like "'image.jpg', Thumbimagefile = 'Thumbimage.jpg', ", and the part of the update code created in the current repetition of this while statement will be like this: "imagefile = 'image.jpg', Thumbimagefile = 'Thumbimage.jpg', ", so that this can be correctly added to the SQL code being created.*/
						if (is_array($values[$i]) && $this->is_image($values[$i]['name'])) {
							eval ("\$".$fields[$i]." = '".$values[$i]['name']."';");
						} else {
							//echo ("\$".$fields[$i]." = $value; <br>");
							eval ("\$".$fields[$i]." = $value;"); 
						}
						$sql .= $value.', ';
					}
					$i++;
				}
				/* Now the last comma of the update SQL code is taken off it so that it is correctly created. Now it's necessary to add to it the where clause and what comes after it, which is something like "pk1 = value1, pk2 = 'value2'...". This is nothing but the second value returned by the function editingMode().*/
				$sql = substr($sql, 0, strlen($sql)-2).$edit[1]; 
				/* Now the update SQL code is executed in the same contition the insert SQL code is done, and $status receives an error message if an error occured.*/
				if (!empty($types)) {
					//mysqli_query($con, "insert into queries (is_editing, sql_string, dt_sql) values (".$edit[0].", '".addslashes($sql)."', '".date('Y-m-d H:i:s')."')");
					mysqli_query($con, $sql);
					if (mysqli_error($con))
						$status = mysqli_error($con);
				}
				/* The variable code receives $values[0] if $table has only one auto-increment primary key. $values[0] contains the value of the current primary key. */
				if ($PK[1] == 1) {
					if ($auto_increment)		
						$code = $values[0];
					// A global variable, whose name is the name of the primary key of $table, is created, and its value is assigned to it.
					eval ("global \$".$fields[0].";");
					eval ("\$".$fields[0]." = $code;");
				}
			}
			/* Whenever a saving operation in a database is performed by this function, the global variable $count is initialized receiving the value 0, so that it can index the array $addresses again in a new saving operation.*/
			$this->count = 0; 
		} catch (Exception $e) {
			$status = $e->getMessage();
		}
		/* This function returns the boolean that indicates if the form is or not in the editing mode after the saving operation. It also returns the value of $code. */
		return array($edit[0], $code);
	}
	
	/*
	The function delete() is used to delete one or more database records and to exclude files related to their urls which are part of these records.
	
	$table is the database table from which a record will be deleted.
	
	$PKfields is the names of the fields used after the where clause of the SQL delete code to define which record(s) will be deleted.
	
	$types is the database types of the fields whose names are in $PKfields. This types are used to format the values of the fields in $PKfields so that they can be used in the SQL codes which are created in this function.
	
	$values is the values of the fields in $PKfields, used to define which record(s) will be deleted.
	*/
	public function delete ($table, $PKfields, $types, $values){
		$con = $this->con;
		global $status;
		try {
			/* If we have only one field to be used after the where clause of the SQL codes created below, then $PKfields, $types and $values must not be arrays, but strings. */
			$type = $types;
			if (is_array($types)) $type = $types[0];
			$value = $values;
			if (is_array($values)) $value = $values[0];
			// The first value is formatted to be used in the SQL codes.
			$value = $this->valueFormat($type, $value);
			$PK = $PKfields;
			if (is_array($PKfields)) $PK = $PKfields[0];
			/* The SQL select code that begins to be built below is used to take from $table all the data that will be deleted from it. The use of these data will be explained later.*/
			$sql1 = "select * from $table where $PK = $value";
			/* The SQL delete code begins to be built below.*/
			$sql2 = "delete from $table where $PK = $value";
			/* If $PKfields is an array of names of fields used to define which record(s) will be deleted, then it's necessary to add to the SQL codes above the other conditions for this definition, like " and PKfield1 = value1", or " and PKfield2 = 'value2'". This is done below.*/
			if (is_array($PKfields)) {
				for ($i = 1; !empty($PKfields[$i]); $i++) {
					$value = $this->valueFormat($types[$i], $values[$i]);
					$aux = " and ".$PKfields[$i]." = $value";
					$sql1 .= $aux;
					$sql2 .= $aux;
				}
			}
			/* The first query to be executed is the SQL select code, to get by it all the values in $table that will be deleted.*/
			$args = $this->select($sql1);
			/* All the values that will be deleted from $table are assigned to $args. Before deleting this values, all the files whose names are among them must be deleted, because, in this system, binary data are not stored in the databases. They only store their names added to their addresses in the server. Then, so that we can actually exclude data from a given database, we must also exclude the files related to their names stored in it. This is done below.*/
			for ($i = 0; !empty($args[$i][0]); $i++) {
				for ($j = 0; !empty($args[$i][$j]); $j++) {
					/* The contitions for $args[$i][$j] to be a file name are these: $args[$i][$j] must be a string; there must be in the server a file whose name is $args[$i][$j]. In this case, the cited file is deleted.*/
					if (is_string($args[$i][$j]) && file_exists($args[$i][$j])) {
						unlink($args[$i][$j]);
					}
				}
			}
			/* After excluding the files related to the record(s) to be deleted, if these files exist, then we finally exclude this (these) record(s).*/
			//mysqli_query($con, "insert into queries (is_editing, sql_string, dt_sql) values (1, '".addslashes($sql2)."', '".date('Y-m-d H:i:s')."')");
			mysqli_query($con, $sql2);
			/* If the records were correctly deleted, $status receives a success message. Otherwise it receives mysqli_error($con).*/
			if (!mysqli_error($con)) $status = "Registro exclu&iacute;do corretamente.";
			else $status = mysqli_error($con);
		} catch (Exception $e) {
			$status = $e->getMessage();
		}	
	}
	
	/* The function createTable() creates a given database table if it doesn't exist. The data used to create the table is in the parameter $formTable. This function uses these data to build the 'create table' SQL code, and the table will surely be created by this code.
	
	The parameter $formTable contains other data besides the data used to create the table. It also contains data to manipulate a form.
	
	$formTable[0] contains the names of the fields of the table that is being created, and $formTable[1] contains their types. $formTable[5] contains the name of the table, and $formTable[6] contains the maximum lengths or sizes of the values of the fields for which a maximum size or length was defined.
	
	$numPKs is the number of primary keys the table will have.
	*/
	public function createTable($formTable, $numPKs) {
		$con = $this->con;
		global $status;
		/* The table will be created only if its fields and their types were defined. Otherwise, no table will be built.*/
		if (empty($formTable[0]) || empty($formTable[1]))
			return;
		$sql = "create table if not exists ".$formTable[5]." ("; // the "create table" SQL code begins to be built.
		// Now all the fields will be defined in the code by their names, types and maximum lenghts or sizes
		for ($i = 0; $formTable[0][$i] !== NULL; $i++) {
			$sql .= $formTable[0][$i]; // As we know, the field name is added first to each part of the 'create table' code where a field is being defined.
			/* After the field name comes its type. If the current type is the binary type blob, then this type will be varchar instead. This is because this database system doesn't save files in a table. It saves their names added to their server destination folders addresses and then upload them into these folders.*/
			/* The command below is performed so that the SQL code being created automatically can be not case-sensitive*/
			$formTable[1][$i] = strtolower($formTable[1][$i]);
			if ($formTable[1][$i] != "blob") {
				$sql .= " ".$formTable[1][$i];
			} else {
				$sql .= " varchar";
			}
			// If a maximum length or size was defined for the current field, it is added to the SQL code right after the field type, between parentheses.
			if (!empty($formTable[6][$i])) {
				$sql .= '('.$formTable[6][$i].')';
			}
			/* If the current field is a date, a time or a dateTime, then only its name and its type are enough to define it. Otherwise, it's necessary to define whether it is or isn't null and other things like its default value.*/
			if ($formTable[1][$i] !== 'date' && $formTable[1][$i] !== 'time' && $formTable[1][$i] !== 'datetime') {
				$sql .= ' not null ';
				/* The code snippet of the 'if' below is executed if the current field is not an auto-increment primary key. It will be an auto-increment primary key if $i = 0, that is, if the current field is the first field in the array $formTable[0]. Also, this happens if the current field type is integer, and if the number of primary keys is only 1. Another condition for the current field to be an auto-increment primary key is $formTable[11] being different from "not_auto_inc". If the current field is the first field, is integer and there is only one primary key, then we must define $formTable[11] equal to "not_auto_inc" so that it won't become an auto-increment primary key. The code snippet cited previously defines a default value for the current field. But if the current field is actually an auto-increment primary key, then the value "auto-increment" is added to the SQL code after the definition of the field as not null.*/
				if (!($i == 0 && ($formTable[1][0] === 'integer' || $formTable[1][0] === 'int') && $formTable[11] !== "not_auto_inc" && $numPKs === 1)) {
					$sql .= 'default ';
					$s = strtoupper($formTable[1][$i]);
					/* If the current field type is not a numeric type, the default value of the field will be '', otherwise it will be 0.*/
					if (strpos($s, 'INT') === FALSE && !in_array($s, array('NUMERIC', 'DECIMAL', 'FLOAT', 'DOUBLE', 'BOOLEAN'))) {
						$sql .= "'', ";
					} else $sql .= "0, ";
				} else $sql .= 'auto_increment, ';
			} else $sql .= ",";
		}
		/* Now we have the definition of the primary keys by the syntax "primary key (PK1, PK2...)". They will always be the first fields contained in the array $formTable[0], according to the number of primary keys defined by $numPKS. For instance, if $numPKs = 3, the primary keys will be $formTable[0][0], $formTable[0][1] and $formTable[0][2]. */
		$sql .= " primary key (".$formTable[0][0];
		for ($i = 1; $i < $numPKs; $i++) {
			$sql .= ", ".$formTable[0][$i];
		}
		$sql .= "))"; // The SQL code is ended  by closing the parentheses of the primary key and the parentheses of the fields definition.
		//echo $sql;
		mysqli_query($con, $sql); // The table is created.
		/* $status will show a message only if it's a failure message, because it doesn't make sense to show a success message for table creation. */
		if (mysqli_error($con)) $status = mysqli_error($con); 
		$content = '';
		/* The file queries.txt contains all the "create table" SQL codes built by this function for the web project being created. The content of $sql will be added to this file if it was not previously added. This is because this function can be executed many times the same table, and the repetition of a given SQL code in the file queries.txt must be avoided. */
		/*if (file_exists("queries.txt")) {
			$content = open_file("queries.txt");
		}
		if (strpos($content, $sql) === FALSE) $content .= $sql."
		
		";
		save_file ($content, "queries.txt", "w");*/
	}
	
	/* The function addForeignKeys() is used to add a foreign key to a given table automatically. This function replaces the manual creation of foreign keys, so that the programmer won't lose time with eventual errors in the SQL code used for this.
	
	The parameter $table contains the name of the table where a foreign key will be added to, and $field contains the name of the $table field that will be turned into a foreign key.
	
	The parameter $reftable is the table that the foreign key references, and $reffield is the field in $reftable the foreign key will be related to.
	*/
	public function addForeignKey($table, $field, $reftable, $reffield) {
		$con = $this->con;
		$sql = "alter table $table add foreign key (`$field`) references $reftable (`$reffield`)";
		$sql2 = "show create table $table";
		$rs = mysqli_query($con, $sql2);
		$row = mysqli_fetch_array($rs, MYSQLI_NUM);
		$s = $row[1];
		/* Before executing the SQL code that alters $table to add a foreign key to it, we get the "create table" SQL code of $table. If the field $reffield exists in this code, it's because the current foreign key has already been created, because this field doesn't belong to $table. In this case, it will appear in the "create table" code of $table as the $reftable field that the foreign key is related to. This way, the 'if' below tests if the current foreign key already exists. If not, it will be created, otherwise it won't be created again.*/
		if (strpos($s, $reffield) === FALSE) {
			mysqli_query($con, $sql);
			/* The "alter table" code used to add a foreign key to $table will be added to the content of the file queries.txt. And it will happen only once, just as a given foreign key is created only once. */
			/*$content .= $sql."
			
			";
			save_file ($content, "queries.txt");*/
		}
	}
}
/*function criaBD ($POST) {
	$formTable = array();
	$primario = array();
	$estrangeiro = array();
	$reftable = array();
	for ($i = 0; $POST['tablename'.$i] !== NULL; $i++) {
		$formTable[5][$i] = $POST['tablename'.$i];
		for ($j = 0; $POST['campo'.$j] !== NULL; $j++) {
			$formTable[0][$i][$j] = $POST['campo'.$j];		
		}
		for ($j = 0; $POST['chave'.$j] !== NULL; $j++) {
			if ($POST['chave'.$j] == "primaria") {
				$primario[$i][$j] = $POST['campo'.$j];		
			} elseif ($POST['chave'.$j] == "estrangeira") {
				$estrangeiro[$i][$j] = $POST['refcol'.$j];
				$reftable[$i][$j] = $POST['reftable'.$j];	
			}
		}
	}
}*/
?>