<?php
session_start();
header('Content-Type: application/json');
require_once "funcoes/funcoesDesign.php";
$dateformat = "d/m/Y";
$con = Connect('localhost', 'enquetes', 'root', '');
$table = $_POST['table']; //$formTable1[5] contains the name of the table 1.
$table2 = $_POST['table2']; 
$table3 = $_POST['table3'];
$PK = GetPrimaryKeys($table); // $PK receives the number of primary keys of table 1 and also their names.
$values = array(); //$values receives all the data coming from de database as a result of running the given SQL code. If no SQL code is run then $values gets the form data that is in $POST. 
$code = 0;
$i = 0;
//The 5th element of the array $formTableN is the name of the table N.
$auxK = 0; //The utility of $ auxK will be explained later, when it is used
/*The function adminPage can be used more than once to create a form and its relation to a database. If we have the inputs of $formTable1 repeated in the form for N times, which means that adminPage() will be called N times on the page, then there must be an index that is related to the current instance of these inputs, and this index is the 8th element of the array $formTable1, and the varable $indTab1 will receibe this value.*/
$indTab1 = $_POST['indTab1'];
$POST = $_POST;
$FILES = $_FILES; 
$args = explode(',', $POST['args']);
$args2 = explode(',', $POST['args2']);
$types = explode(',', $POST['types']);
$types2 = explode(',', $POST['types2']);
$inputs_types = explode(',', $POST['inputs_types']);
$inputs_types2 = explode(',', $POST['inputs_types2']);
$i = 0;
$status2 = '';
while ($args[$i] != NULL) {
	eval("global \$".$args[$i].";"); //The values contained in $args will be transformed into PHP global variables to be used for the form. 
	$s = $args[$i].$indTab1;
	if (is_image($FILES[$s]['name'])) {
		$auxK++; //Notice that the $auxK value increments whenever the data type in $formTable1[3] is an image
	}
	//The code snippet below fills $values with the values acquired from the submitted form and relative only to the table 1.
	if ((($POST[$s] !== NULL) || ($FILES[$s] !== NULL)) && $POST['butPres'] != "Select") {
		if (!isset($FILES[$s])) {
			$values[0][$i] = $POST[$s];
		} else {
			$values[0][$i] = $FILES[$s];
		}
	}
	$i++;
}
$values2 = array(); /*$values2 gets only the data relative to the table 2. This variable is necessary for saving operation in table 2. The variable $values also gets the data that belong to table 2, and all its values are used to fill the form again after it is submitted for a database operation. */
$secondTablePK = $i;
$k = $i+$auxK;
/*If there is a second table, which will be linked to the first one by a foreign key, the code snippet below feeds $values2 with the values of that second table, coming from the submitted form, which can receive multiple data records, as shown below. Note that the $values variable also receives the values of table 2, filling the columns that were not filled with values. Also note that the $k offset, which causes $values to be filled in unfilled places, is added with the value of $auxK variable. This is because each recorded image generates a thumbnail of itself, which is why $auxK needed to be incremented every time an image was detected in the previous code snippet. The usefulness of this will be better understood later on. The variables $values and $values2 will receive values related to the table 2 from the submitted form only if they won't receive values from the database. This happens if $POST['butPres'] equalt to "Select". 
*/
$amountTable2 = $POST["amountTable2"]; /* This is the number of times the inputs of table two are repeated on the page in order to create multiple records of it associated with one record of table 1.*/
if ($args2 != NULL && $POST['butPres'] !== "Select") {
	$i = 0;
	while ($args2[$i] != NULL) {
		eval("global \$".$args2[$i].";"); //The values contained in $args2 will be transformed into PHP global variables to be used for the form.
		$s = $args2[$i];
		$j = 0;
		$s .= ''.$indTab1;
		/*If the current form is repeated for N times on the page, then $indTab1 is not empty, and an index of $POST related to the second table will be something like name1_3.*/
		if (!empty($indTab1)){
			$s .= "_";
		} 
		/*The indexes of $POST related to the table 2 are ended by a number. The index $s.'0' won't be NULL only if the values coming from the form were taken from a database to fill it up. If $POST[$s.'0'] is NULL, it's because the input whose name attribute is equal to $s.'0' does not extist.*/
		if ($POST[$s.'0'] === NULL && $FILES[$s.'0'] === NULL) {
			$j = 1;
		}
		/*Now if the table 2 is repeated for N times, the arrays $values and $values2 get the values of each instance of the current form input.*/
		if ($inputs_types2[$i] !== 'file') {
			if ($inputs_types2[$i] !== "radio") {
				while ($j <= $amountTable2) {
					if (!empty($POST[$s.$j])) {
						$values[$j][$i+$k] = $POST[$s.$j];
						$values2[$j][$i] = $POST[$s.$j];
					}
					$j++;	
				} 
			/*When you have checkboxes or radios in the form, you have to select from them one or more than one values among a certain amount of choices. These choices are data stored in a database. We have N choices for one form. This is a relation 1 x N, which means that the data that will be the choices in a radio or a checkbox system must come from the table 2, which have a relation N x 1 with the table 1, as seen previously. If the type of the current form input is radio, we have multiple choices with the same name for their name attribute, and so we get one only value from this radio system, as shown below.*/
			} elseif ($POST[$s] !== NULL && $POST[$s] !== '') {
				$j = 0;
				if ($POST[$s.'0'] === NULL) $j = 1;
				$values[$j][$i+$k] = $POST[$s];
				$values2[$j][$i] = $POST[$s];
			} 
		} else {
		// If the current input type is file, then we get the data from the variable $_FILES, and not from $_POST.
			while ($j <= $amountTable2) {
				if (!empty($FILES[$s.$j]['name'])) {
					$values[$j][$i+$k] = $FILES[$s.$j];
					$values2[$j][$i] = $FILES[$s.$j];
				} 
				/* There may be situations where the value being saved is a file, but the form field containing its name is not of the type file. In this case, the value is a string containing a file name, and this is not a case where a file will actually be saved, but only its name in the database. The code snippet below is a solution for saving in the database records that contain files whose names didn't come from a file form input. */
				elseif (!empty($POST[$s.$j])) {
					$values[$j][$i+$k]['name'] = $POST[$s.$j];
					$values2[$j][$i]['name'] = $POST[$s.$j];
				}
				$j++;
			}
		}
		$i++;
	}
}
$sel = $args[1];
$sql = $POST['sql'];

/*For the PHP variable $_SESSION, an index must be defined. The global variable $idSession contains the name of this index, and one only form can work with more than one sessions. If $idSession is not defined in the PHP file that calls the function adminPage(), then its value will be 'user'.*/
$idSession = $POST['idSession'];
$Ses = !empty($_SESSION[$idSession]);
$button = 'butPres';
$focus = array(0, -1); /*If the value of $focus[1] becomes greater than -1, it is because there are fields in the form that are required and was not filled, and the first required field from top to bottom that has not been filled will be focused.*/
$status = '';
$is_editing = false;
//The 'if' structure below is used to define whether the form is or isn't in the editing mode, that is, if the data that fills it will update a record in the database or insert a new record into it. If the form button pressed is the one used to select data from the database then $is_editing will be true in a further code snippet.
if ($POST[$button] !== "Select"){
	if ($POST[$button] !== "New"){
		$edit = editingMode ($table, $values[0], $types);
		$is_editing = $edit[0];
	} else $is_editing = false;
}
global $status2;
//$status2 = "z".$values[0][0];
//IMPLEMENTATION OF DATABASE ADMINISTRATION BUTTONS
$public = $POST['public'];
$it1 = ($indTab1 === '') ? '' : $indTab1.'_';
$json = '';
if ($POST[$button] != NULL) {
	
	
	//SELECT 
	
	
	if ($POST[$button] == "Select" && ($Ses || $public)){
		$i = 0;
		$rs = mysqli_query($con, $sql);
		$dec = 0;
		// The passwords taken from the database are deleted
		while ($row = mysqli_fetch_array($rs, MYSQLI_NUM)) {
			$j = 0;
			while ($row[$j-$dec] !== NULL) {
				if ($inputs_types[$j] !== "password" && $inputs_types2[$j-$secondTablePK] !== "password") {
					$values[$i][$j] = $row[$j-$dec];
				} else {
					$values[$i][$j] = '';
					$j++;
					$values[$i][$j] = '';
					$dec++;
				}
				$j++;
			}
			$i++;
		}
		/*In the example above, we have the variable $values receiving data from the database, and not from the form. This happens when the user requests recorded information to manipulate it. The data is also stored in variables whose names are the values of $args.*/
		if ($values[0][0] == NULL) {
			$status = "Error in select: ".mysqli_error($con);
		} else $is_editing = true;
		$json = "{";
		$i = 0;
		while ($args[$i] != NULL) {
			$s = $args[$i].$indTab1;
			//The code snippet below fills $values with the values acquired from the submitted form and relative only to the table 1.
			$json .= '"'.$s.'": [';
			for ($j = 0; $j < mysqli_num_rows($rs); $j++) 
				$json .= '"'.$values[$j][$i].'", ';
			$json = substr($json, 0, strlen($json)-2).'], ';
			$i++;
		}
		if ($args2 != NULL) {
			$i = 0;
			while ($args2[$i] != NULL) {
				$s = $args2[$i];
				$j = 0;
				$s .= ''.$indTab1;
				/*If the current form is repeated for N times on the page, then $indTab1 is not empty, and an index of $POST related to the second table will be something like name1_3.*/
				if (!empty($indTab1)){
					$s .= "_";
				} 
				/*Now if the table 2 is repeated for N times, the arrays $values and $values2 get the values of each instance of the current form input.*/
				if ($inputs_types2[$i] !== "radio") {
					while ($j <= $amountTable2) {
						if (!empty($POST[$s.$j])) {
							$json .= '"'.$s.$j.'": "'.$values[$j][$i+$k].'", ';
						}
						$j++;	
					} 
				/*When you have checkboxes or radios in the form, you have to select from them one or more than one values among a certain amount of choices. These choices are data stored in a database. We have N choices for one form. This is a relation 1 x N, which means that the data that will be the choices in a radio or a checkbox system must come from the table 2, which have a relation N x 1 with the table 1, as seen previously. If the type of the current form input is radio, we have multiple choices with the same name for their name attribute, and so we get one only value from this radio system, as shown below.*/
				} elseif ($POST[$s] !== NULL && $POST[$s] !== '') {
					$json .= '"'.$s.'0": "'.$values[0][$i+$k].'", ';
				} 
				$i++;
			}
		}
		$json .= '"status": "'.$status.'"}';
	} //end select
	
	
	//SAVE
	
	//The saving operation will be allowed to happen if the form is public or if the one who uses it to save data logged in the site before this operation.
	
	elseif ($POST[$button] == 'Save' && ($Ses || $public)) {
		$willSave = true;
		/*When we have to save a new password through the form, we have to type it twice. The 'for' structure below tests if the new password and the confirmation password match. If not, a message of error is issued.*/
		for ($j = 0; $$inputs_types[$j] != NULL; $j++) {
			if ($inputs_types[$j] == "password") {
				if ($values[0][$j] !== $values[0][$j+1]) {
					$status = "ERROR: Your password and confirmation password do not match.";
					$willSave = false;				
					$focus[1] = $j;
				}
				break;
			}
		}
		$code = 0;
		if ($willSave) {
			$insert = true;
			/*$code is the primary key value of table 1, and the 'if' structure below indicates that there will be an updating in this write operation, instead of an inserting, if $is_editing is true, that is, if the form is in the editing mode.*/
			if ($is_editing) {
				$code = $values[0][0];
				$insert = false;
			}
			$fields = $args;
			$addresses = explode(',', $POST['addresses']); //$formTable1[4] will receive the vector of the addresses of the photos that there could be to be saved in the database.
			/*The function save() will write to the database the form data, whether it came from the database or provided to be inserted, and it will return an array with a value that says if the form is or isn't in the editing mode and with the value of the first primary key that is generated from the insert or update SQL operations. Is there is no user session, then no data can be updated in the database, but they can be inserted into it if the form is public.*/
			if ($Ses || $insert) {
				$status = "Record has been stored successfully.";
				/*The if below contains a code that is a remedy to prevent a form from saving empty values for passwords, since a password can only be saved if it contains characters. This code runs only if there is a password input among the form inputs.*/
				if (is_array($inputs_types) && in_array("password", $inputs_types)) {
					$cont = 0; 
					while ($inputs_types[$cont] !== "password")
						$cont++;
					/*Almost every time we save a password, it is necessary to provide the repetition of this password. In this case, the 'if' below causes the field, type and value related to the repetition of a password to desapear, because we must save a given password only once in the database.*/
					if ($inputs_types[$cont+1] === "password") {
						for ($i = $cont+1; $inputs_types[$i] !== NULL; $i++) {
							$fields[$i] = $fields[$i+1];
							$types[$i] = $types[$i+1];
							$values[0][$i] = $values[0][$i+1];
						}
					}
					/*If no password was provided in the password input, the 'if' below causes the field, type and value related to the password to desapear, so that no empty value will be saved for it.*/
					if (empty($values[0][$cont])) {
						while ($fields[$cont] !== NULL){
							$fields[$cont] = $fields[$cont+1];
							$types[$cont] = $types[$cont+1];
							$values[0][$cont] = $values[0][$cont+1];
							$cont++;
						}
					}
				}
				
				$edit = save($table, $fields, $types, $values[0], $addresses);//here we have the save operation itself.
				
				$is_editing = $edit[0]; //$is_editing stores a value that says if the form is os is not in the editing mode.
				/* If $values[0][0] is an empty value, it means that table 1 has one only primary key and that it is auto-increment. Also, it means that the primary key of the current record was generated in the saving operationm */
				$auto_inc = false;
				if (empty($values[0][0])) {
					$auto_inc = true;
					$values[0][0] = $edit[1];
				}	 
				if (mysqli_error($con)) $status = "Error on saving: ".mysqli_error($con);	
				$code = $values[0][0];
				/*If the form data has been saved successfully, then a session is automaticly created in the code snippet below, if the form is public, whose value is the value of the first primary key related to the data saved. If the form is restricted access, it makes no sense creating a session, because such forms can be handled only within a session created previously.*/
				if ($public) {
					$_SESSION[$idSession] = $code;
				}
				//If table 2 exists, there will be a save operation in it.
				if ($table2 != NULL && !mysqli_error($con)) {
					/*Below, we have variables being supplied with the values to be used in the function save() for table 2, which can receive more than one data record.*/
					$fields2 = $args2; 
					$addresses2 = explode(',', $POST['addresses2']); 
					$value = array();
					$value[0] = $values[0][$k];
					/*The editing mode cannot be checked for the whole form at once. We can only check if the form is in the editing mode in relation to one only table. The code snippet below checks if the form inputs related to the table 2 are in the editing mode.*/	
					$edit2 = editingMode ($table2, $value, $types2);
					$is_editing2 = $edit2[0];
					/*The index $i starts at 1 if the inputs of the table 2 are not editing data, and not at 0. This is because when the table 2 inputs are in the editing mode, the names of the inputs of the first instance of table 2 end with the number 0, instead of 1. This will be better understood later.*/
					$i = 0;
					if (!$is_editing2) {
						$i = 1;
					}
					/*The structure below, including the while, will write the data in table 2 and in table 3 if it exists, which creates a relation m X n between tables 1 and 2. Note that when there is a table 2, both tables 1 and 2 have only one primary key each, even though the function save() allows that the table where data will be saved through it has more than one primary key. If there is a table 3, its first two data will be foreign keys referring to the primary keys of tables 1 and 2, but if there is no table 3, table 2 will have a foreign key referring to the primary key of table 1, which will be necessarily its second field. $q is the index of the column of $values2 that contains the values of the main table 2 field.*/
					$q = 1;
					if ($table3 == NULL) {
						$q = 2;
						$values2[$i][1] = $code;
						$values[$i][$k+1] = $code;
					} 
					$field = $values2[$i][$q];
					/* Below, the data taken from the form and related to table 2 will be stored in the database only when the table 2 main visible value ($field) is not empty.*/
					while ($i <= $amountTable2) {
						if (!empty($field)) {
							$edit2 = save($table2, $fields2, $types2, $values2[$i], $addresses2); //as seen previously, $values2 is used for saving operation in table 2.
							$values2[$i][0] = $edit2[1];	
							$code2 = $values2[$i][0]; //$code2 is the primary key of the table 2, which will always have only one auto-increment PK.
							$values[$i][$k] = $code2;
							/*If there is a table 3, then the relation between tables 1 and 2 is m X n, and not 1 X n. The save operation in the table 3 will not be done with the function save(), but with the PHP function mysqli_query($con, ).*/
							if (!empty($table3)){
								$fields3 = explode(',', $POST['args3']); //$fields3 receives the names of the table 3 fields.
								$types3 = explode(',', $POST['types3']); //$fields3 receives the names of the table 3 fields.
								/*The SQL code to insert into table 3 the relation between table 1 and table 2 is now created. Its first fields are the two primary keys, which are foreign keys referring tho the primary ones of table 1 and 2. If the table 3 have other fields, they are taken from $formTable3[0] to build the SQL code as shown below. */
								$sql2 = "insert into $table3 (".$fields3[0].", ".$fields3[1];
								for ($j = 2; $fields3[$j] !== NULL; $j++) {
									$sql2 .= ", ".$fields3[$j];
								}	
								/*Now we get the data to be inserted into table 3. The data that are not the values of its primary keys are taken from $POST, and the data which are varchar or date types must be surrounded by '' in the SQL code.*/
								$sql2 .= ") values ($code, $code2"; 		
								for ($j = 2; $fields3[$j] !== NULL; $j++) {
									$c = $fields3[$j];
									$v = $POST[$c.$it1.$i];
									if ($types3[$j] === "varchar" || strpos($types3[$j], "date") !== FALSE) $v = "'$v'";
									$sql2 .= ", $v";
								}	
								$sql2 .=	")";
								mysqli_query($con, $sql2);			
							}
						}
						$i++;  
						$field = $values2[$i][$q]; 
						/* If there is no table 3, the relation between tables 1 and 2 will be 1 X n, and the columns of $values and $values2 that contain the foreign key of table 2 must be filled with the value of the current table 1 primary key, so that the records of table 2 can be correctly stored.*/
						if (empty($table3)) {
							$values2[$i][1] = $code;
							$values[$i][$k+1] = $code;
						}
					}
				}//end table2 storing
			}//end table2 test	
		}//end storing
		$json = '{ "status" : "'.$status.'"';	
		if ($auto_inc) $json .= ', "pk1": "'.$code.'"';
		$json .= ' }';	
	}//end saving
	
	//Delete operation
	
	elseif ($POST[$button] == "Delete" && $Ses) {
		$status = "Record has been deleted successfully.";
		$table1 = $table; // $table1 receives the first table.
		/* $PKfields receives an array with the names of the fields used to define the records that will be deleted from each table. Its first value is the name of the unique or the first primary key of table 1. This primary key will be used in the Delete operation anyway. */
		$PKfields = array($fields[0]);
		$types = array($ftypes[0]); // $types contains the types of the fields cited previously.
		$PK0 = $PKfields[0];
		$values = array($POST[$PK0.$indTab1]); // $values contains the values of the fields cited previously, taken from the variable $_POST.
		/* If only the table 1 exists, then it can have more than one primary keys, and all the fields whose names are in $PKfields will be these primary keys. Also, $types will contain the types of these fields, and $values will contain the values assigned to them.*/
		if (empty($table2) && empty($table3)) {
			for ($i = 1; $i < $PK[1]; $i++) {
				$PKfields[$i] = $PK[0][$i];
				$PKi = $PKfields[$i];
				$values[$i] = $POST[$PKi.$indTab];
			}
			/* The function delete() will create the SQL code that deletes the current record of table 1, which is associated with all the form in this case. The current record is defined by the values of the primary keys of $table1. The function delete() also delete the files whose names added to their addresses on the server are among the data of the record that is being deleted, if they exist.*/
			delete ($table1, $PKfields, $types, $values);
		/* If at least the table 2 also exists, then the first table must have only one primary key, which is related to one of the foreign keys of $table2 or one of the foreign keys of $table3. */
		} else {
			/* If only the first and the second table exist, then the primary key of table 1 will be related to one of the foreign keys of table 2, and all its records associated with the current record of table 1 will be deleted.*/
			if (empty($table3)) {
				/* $PKfields[1] receives the foreign key of table 2, that refferences the primary key of table 1. */
				$PKfields[1] = $fields2[1]; 
				delete ($table2, $PKfields[1], $types[0], $values[0]);
			/* If the table 3 exists, it will be the relation M x N between $table1 and $table2, and each of these two tables will have only one primary key, which will be related to foreign keys of table 3. */
			} else {
				// In this case, the second value of $PKfields will be the name of the primary key of $table2.
				$PKfields[1] = $fields2[0];
				/* $PKfields[2] and $PKfields[3] receive names of foreign keys of table 3, which are related to the primary keys of table 1 and table 2.*/
				$PKfields[2] = $fields3[0];
				$PKfields[3] = $fields3[1];
				/* The values that will be assigned to the foreign keys above in one of the delete queries are the current values of the primary keys of tables 1 and 2. Then, $values[1] receives the current value of the primary key of table 2, and its type is assignet to $types[1].*/
				$types[1] = $types2[0];
				$PK2 = $fields2[0];
				$values[1] = $POST[$PK2.$it1.'0'];
				/* The first thing to do is to delete the relation between the current records of tables 1 and 2 stored in $table3.*/
				delete ($table3, array($PKfields[2], $PKfields[3]), array($types[0], $types[1]), array($values[0], $values[1]));
				/* Then we delete a record from $table2 where its primary key equals its current value.*/
				delete ($table2, $PKfields[1], $types[1], $values[1]);
			}
			/* Finally we delete a record from $table1 where its unique primary key equals its current value.*/
			delete ($table1, $PKfields[0], $types[0], $values[0]);
		}
		if (mysqli_error($con)) $status = "Error on deleting: ".mysqli_error($con);
		$json = '{ "status" : "'.$status.'" }';	
	}//end delete
	elseif (!$public && !$Ses) {
		$status = "Please log in to operate this form.";
		$json = '{ "status" : "'.$status.'" }';	
	}
} //end buttons
if (!empty($table2)) {
	$rs = mysqli_query($con, $sql);
	
	/*When a table 2 exists, as has been said, the form can repeat its fields several times to make multiple records in this table. The structure below serves to delete a single record from table 2 through a Delete button associated with each of these records. Now the $secondTablePK variable is used to index the primary key of this table in the resultset below. The value of this primary key will be assigned to $code and the name of each of these Delete buttons will be 'delete'.$code. The code snippet below identifies which of them was pressed, if this happened.*/
	
	if (!empty($rs)) {
		while ($row = mysqli_fetch_array($rs)) {
			try {
				$code = $row[$secondTablePK];    
				if ($POST['delete'.$code] != NULL) {
					delete ($table2, $fields2[0], $types2[0], $code);
					//mysqli_query($con, "delete from ".$formTable2[5]." where ".$formTable2[0][0]." = $code");
					break;
				}
			} catch (Exception $e) {
				$status = $e->getMessage();
			} 
		}
	}
}
echo $json;
?>
