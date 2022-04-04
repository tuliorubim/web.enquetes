<?php
require_once "funcoesBD.php";


class DesignFunctions extends DBFunctions {
	public $html = '';
	public $idSession = 'user';
	/*
	The function write_table is a function created to write tables that has a pre-defined style.
	
	$args is an array that contains the data that will be displayed in the table, which is the result of executing a SQL select statement.
	
	$table_params contains data that instructs how the data will be displayed in the table. Each of these data is described below.
	
	$table_params['header'] is an array with the names of the table header.
	
	$table_params['header_style'] is a string with the properties and styles applied to each header cell.
	
	$table_params['style'] is a string with the properties and styles applied to the table.
	
	$table_params['cell_style'] can be a string containing an only set of properties and styles for all the table cells, or it can be a vector containing one only set of properties and styles for all cells of a given table row. In this case, each row of the table will have its own set of properties for its cells. $table_params['cell_style'] can also be a matrix containing a different set of HTML or CSS properties for each cell of the table.
	
	$table_params['amountCols'] sets the amount of columns of $args that will be written by the function, from the last column 
	
	$table_params['page'] is a string defined to be a web page to which links direct. These links are defined according to the values of $table_params['links'].
	
	$table_params['links'] is an array of doubles containing integers which are column indexes in the array $args, where the second integer is the column index of $args whose values must be turned into links, and the first integer is the column index of args whose values are urls that these links directs to, if $table_params['page'] was not defined. But if this variable contains the name of a page, the $args column who would be pages are actually parameters of the page defined in $table_params['page'], which instructs what will be displayed on it, according to the link that directs to it.
	
	*/
	
	public function write_table ($args, $table_params=array()) {
		$style = $table_params['style'];
		$table = "<table '$style'><tr>";
		$i = 0;
		while ($args[0][$i] != NULL) {
			$i++;
		}
		$amountCols = $table_params['amountCols'];
		if ($amountCols === NULL) $amountCols = $i;
		$i = $i - $amountCols; /* The starting index of the $args columns that will be written is the total number of columns minus the value defined in $table_params['amountCols']. This is useful to not write in the table primary keys and/or foreign keys which are defined to be invisible values.*/
		$k = 0;
		$links = $table_params['links'];
		/* $page stores what is in $table_params['page'], which can be a page defined by the programmer, as explained before. If it is actually a web page, and not a null value, then we add to the string $page the value "?id=", so that the column of $args defined to be destination urls can be parameters of $page, instead of pages themselves. But if $page is null, the $args column cited right before will be destination urls.*/
		$page = $table_params['page']; 
		if (empty($page) || strlen($page) < 6) $page = '';
		else $page .= "?id=";  
		$links_aux = $this->transposed_matrix($links); // $links_aux is the matrix $links transposed, and will be useful later.
		$cell = $table_params['cell_style'];
		$is_vector = false;
		$is_matrix = false;
		if (!is_array($cell)) $cell_style = $cell;
		elseif (is_array($cell) && !is_array($cell[0])) $is_vector = true;
		else $is_matrix = true;
		$header = $table_params['header'];
		$header_style = $table_params['header_style'];
		/* The first thing is to add the header to the table, if a header was defined by the programmer. It will be written in td tags rather than th tags.*/
		$j = 0;
		if (!empty($header)) {
			//$table .= "<tr>";
			while ($header[$j] != NULL) {
				$table .= "<th $header_style>".$header[$j]."</th>";
				$j++;
			}
			$table .= "</tr><tr>";
		}
		while ($args[$k][$i] != NULL) {
			if ($is_vector) $cell_style = $cell[$k];
			while ($args[$k][$i] != NULL) {
				if ($is_matrix) $cell_style = $cell[$k][$i];
				$table .= "<td $cell_style>";
				/* The variable $aux receives the current $args value, and it will be turned into an image by the img tag, if it is a image url and if it is not a destination url defined in $table_params['links'].*/
				$aux = $args[$k][$i];
				if ($this->is_image($aux) && !in_array($i, $links_aux[0])) $aux = "<img src='$aux' width='90'/>";
				/*The variable $i is the column index of the current value of $args. $links_aux is a vector with two rows. The row 0 is the row of the destination urls ahd the row 1 is the row of the links to these urls. If the index $i is in $links_aux[1], that is, if it's the index of an $args column whose values are defined to be links, then the current $args value will become a link to $page.$args[$k][$c], where $args[$k][$c] is the destination url or the index of $page. $page will be null or something like 'page.php'.*/
				if (is_array($links_aux[1]) && in_array($i, $links_aux[1])) {
					$index = array_keys($links_aux[1], $i);
					$ind = $index[0];
					$c = $links[$ind][0];
					$table .= "<a href='$page".$args[$k][$c]."'>".$aux."</a>";
				} else	$table .= $args[$k][$i];
				$table .= "</td>";
				$i++;
			}
			$i = $i - $amountCols; // $i will always started from $i - $amountCols, as seen previously.
			$k++;
			$table .= "</tr><tr>";
		}
		$table .= "</tr></table>";
		echo $table;
	}
	
	
	
	public function insereImagens($addresses, $props) {
		for ($i = 0; $addresses[$i] != NULL; $i++) {
			$addresses[$i] = "<img src='".$addresses[$i]."' $props>";
		}
		return $addresses;
	}
	
	public function insereLinks($addresses, $links, $props) {
		for ($i = 0; $addresses[$i] != NULL; $i++) {
			//echo $addresses[$i];
			$links[$i] = "<a href='".$addresses[$i]."' $props>".$links[$i]."</a>";
			
		}
		return $links;
	}
	
	public function insereLista($conteudo, $tipo) {
		$lista = "<ul type='$tipo'>";
		for ($i = 0; $conteudo[$i] != NULL; $i++) {
			$lista .= "<li>".$conteudo[$i]."</li>";
		}
		$lista .= "</ul>";
		return $lista;
	}
	
	public function formarSlides ($portfolio, $quantPorSlides, $num_cols, $estiloTabela) {
		$table = '';
		for ($i = 0; $portfolio[$i] != NULL; $i += $quantPorSlides) {
			$slide = array();
			for ($j = $i; $j < $i+$quantPorSlides; $j++) {
				$slide[$j] = $portfolio[$j];	
			}
			$id = " id='tabela$i'";
			$invisivel = '';
			if ($i > 0) {
				$invisivel = " style='display:none;'";
			}
			$estiloTabela2 = $estiloTabela.$id.$invisivel;
			echo designTable($slide, $estiloTabela2, $arquivoOuEstilos, $estilo, $num_cols);
			if ($i == 0) {
				//$table = designTable($slide, $estiloTabela, $arquivoOuEstilos, $estilo, $num_cols);
			}
			//echo $table;
		}
		return $table;
	}
	public function formLogin ($action, $labels=array('Login', 'Senha'), $names=array('user', 'password', 'enter')) {
	?>
		<div id="login"><form name="login" method="post" action="<?php echo $action;?>">
			<p><label for="<?php echo $names[0];?>"><?php echo $labels[0];?>:</label><input type="text" name='<?php echo $names[0];?>' id='<?php echo $names[0];?>' /></p>	
			<p><label for="<?php echo $names[1];?>"><?php echo $labels[1];?>:</label><input type="password" name="<?php echo $names[1];?>" id="<?php echo $names[1];?>" /></p>
			<p><input type="submit" name="<?php echo $names[2];?>" value="Log In" /></p>	
		</form></div>
	<?php
	}
	public function formGeral ($SESSION, $formTable1, $formTable2, $formTable3, $select, $public=FALSE, $JSIndex=NULL, $isHTML=FALSE) {
		$values = array();
		$indTab1 = $formTable1[8];
		$args = $formTable1[0]; //$args receives $formTable1[0], which contains the names of the fields of the database table 1. These names are also the names of this form fields.$sql = $select[0];
		$args2 = $formTable2[0]; 
		$table1 = $formTable1[5];
		$PK = $this->GetPrimaryKeys($table1);
		$con = $this->con;
		global $status; //The global variable $status is used to notify the result of a certain form operation.
		$status = '';
		$table2 = $formTable2[5]; 
		$table3 = $formTable3[5];
		if (!empty($formTable1)) $this->createTable ($formTable1, 1); 
		//If the tables used in this form of database manipulation do not exist, they will be created, as well as their foreign keys
		if (!empty($table2) && empty($table3)) {
			$this->createTable ($formTable2, 1);
			$this->addForeignKey ($table2, $formTable2[0][1], $formTable1[5], $formTable1[0][0]);
		} elseif (!empty($table3)) {
			$this->createTable ($formTable2, 1);
			$this->createTable ($formTable3, 2);	
			$this->addForeignKey ($table3, $formTable3[0][0], $formTable1[5], $formTable1[0][0]);
			$this->addForeignKey ($table3, $formTable3[0][1], $table2, $formTable2[0][0]);
		}
		/*For the PHP variable $_SESSION, an index must be defined. The global variable $idSession contains the name of this index, and one only form can work with more than one sessions. If $idSession is not defined in the PHP file that calls the function adminPage(), then its value will be 'user'.*/
		$idSession = $this->idSession;
		$Ses = !empty($SESSION[$idSession]);
		$button = 'butPres';
		$is_editing = false;
		//SELECT	
		$sql = $select[0];
		if ($select[5] && ($Ses || $public)){
			$values = $this->select($sql, array(), $isHTML);
			/*In the example above, we have the variable $values receiving data from the database, and not from the form. This happens when the user requests recorded information to manipulate it. The data is also stored in variables whose names are the values of $args.*/
			if ($values[0][0] == NULL) {
				$status = "Error on selecting: ".mysqli_error($con);
			} else $is_editing = true;
		} //end select
		
		//CREATING AND FILLING UP THE FORM
		
		$i = 0;
		$k = count($args);
		// The date, time and datetime values must be shown in the form in the formats defined by the programmer, and not in the database format. The code snippet below forces these values to be in such formats so that they can be correctly displayed in all the form.
		while ($args[$i] != NULL) {
			// If the current field is date, time or datetime, then all the values of the current $values column are formatted according to the formats defined by the programmer.
			if (in_array($formTable1[1][$i], array('date', 'time', 'datetime'))) { 
				if (!empty($values[0][$i]) && strtotime($values[0][$i]) > 10800)
					$values[0][$i] = $this->form_datetime_create ($values[0][$i], $formTable1[1][$i]);
				else $values[0][$i] = '';
			}
			$i++;
		}
		$i = 0;
		// The same process above is performed for the form fields related to table 2.
		while ($args2[$i] != NULL) {
			if (in_array($formTable2[1][$i], array('date', 'time', 'datetime'))) {
				$j = 0; 
				while ($values[$j][$k] !== NULL) {
					if (!empty($values[$j][$i+$k]) && strtotime($values[$j][$i+$k]) > 10800)
						$values[$j][$i+$k] = $this->form_datetime_create ($values[$j][$i+$k], $formTable2[1][$i]);
					else $values[$j][$i+$k] = '';
					$j++;
				}
			}
			$i++;
		}
		/*The string variables $list1 and $list2 store the values that best identify to the user each record of their respective tables (1 or 2). Through them, the desired record is chosen to make the desired changes in it. Note that the code snippet below will run only if the parameter $JSIndex are not null. This will be better understood below.*/
		$i = 0;
		$n = 0;
		$it1 = ($indTab1 === '') ? '' : $indTab1.'_';
		if ($JSIndex !== NULL) {
			$list1 = '';
			$list2 = '';
			/*The integer variables $r1 and $r2 are the starting indexes for important JavaScript variables. They are useful for creating a form using this function for n times in one only page. This will be better understood below.*/
			$r1 = $JSIndex[0];
			$r2 = $JSIndex[1];
			$n = $PK[1]; // this is the index of the field in the variable $formTabela1 that best identifies to the user the records of table 1. It will always be the first field after the primary keys.
			/*The variable $select is a parameter of this function. Note that $list1 will receive an HTML code that is a select tag list with the values of the field used to identify to the user each record of table 1, but this will happen only if $select[1] is equal to "select", which means that the list mentioned previously will be created only in this case, because $select[1] defines the way how the main field to the user will be shown when a select operation is made in the database. When the current value of this list is changed, the values of the form inputs relative to table 1 are also changed, to show the values of the current record of this table. If there is a table 2, changing the value of $list1 also changes the values of the form inputs relative to table 2, to show the values of this table related to the current record of table 1.*/
			if ($select[1] == "select") {
				$properties = $formTable1[7]; //$formTable1[7] and $formTable2[7] contains HTML properties for the form inputs.
				if (is_array($formTable1[7])) $properties = $formTable1[7][$n]; 
				/*The list in $list1 will execute two lines of Javascript code when its value is changed. The first line assigns to the global Javascript variable si the current list value, and the second line performs the Javascript function select(), which is responsible for changing all values of the form inputs according to the new value chosen for the list. */
				$list1 .= "<select name='".$args[$n]."$indTab1' id='".$args[$n]."$indTab1' onchange='si = this.selectedIndex; seleciona(this.options[si].value, $n, $k, $r1, $r2); ".$select[3]."' $properties>";
				$j = 0;
				while ($values[$j][$n] != NULL) {
					$list1 .= "<option value='".$values[$j][$n]."'>".$values[$j][$n]."</option>";
					$j++;
					/* When we have a table 2 associated with the table 1 and we have more than one table 2 records associated with a given table 1 record, the values of this record will be repeated in $values for each table 2 record related to it. The code snippet below is useful not to repeat these values in the list being created here. */
					if (!empty($table2)) {
						while ($values[$j][0] === $values[$j-1][0]) $j++;
					}
				}
				$list1 .= "</select>";
			} 
			/*Since $k is the index of the primary key of table 2 in the $values vector, the variable $p will point to the first column in $values after the column of the primary keys and/or the column of the foreign keys of table 2 related to table 1. These values will be shown by means of $list2, and will identify to the user the records of table 2.*/
			$p = $k+1;
			if (empty($table3)) {
				$p++;
			}
			//if (!empty($formTable2[11])) $p = $k+$formTable2[11];
			/*When $select[1] == "select", the $list2 is also created, and will also receive a HTML code that is a select tag list of the values mentioned above.*/
			if ($select[1] == "select") {
				/* The variable $formTable2[7] can be a vector or a matrix, and it contains a different set of properties for each input related to table 2, whether it's a vetor or it's a matrix. But if it's a matrix, then the programmer can define a different set of properties for each repetition of every input associated with table 2.*/
				$properties = $formTable2[7][$p-$k];
				if (is_array($formTable2[7][0])) $properties = $formTable2[7][0][$p-$k];
				/*The list in $list2 will also execute two lines of Javascript code when its value is changed. The first line assigns to the global Javascript variable si the current list value, and the second line performs the Javascript function select(), which is responsible for changing the values of the form inputs related to table 2 according to the new value chosen for the list. */
				$list2 .= "<select name='".$args2[$p-$k].$it1."0' id='".$args2[$p-$k].$it1."0' onchange='si = this.selectedIndex; seleciona(this.options[si].value, $p, $k, $r1, $r2);' $properties ".$select[4].">\n";
				for ($j = 0; $values[$j][$p] != NULL; $j++) {
					$list2 .= "<option value='".$values[$j][$p]."'>".$values[$j][$p]."</option>\n";
					
				}
				$list2 .= "</select>\n";
			}
			/*In the structures below, the names of the fields of tables 1 and 2 and their values are assigned to Javascript variables.*/
			echo "<script language='javascript'>\n";	
			//Now the starting indexes of the Javascript variables below are assigned to the Javascript variables r1 and r2.
			echo "r1 = $r1;\n";
			echo "r2 = $r2;\n";
			/*In the following 'for', the Javascript array args receives the names of the inputs related to table 1. These are also the names of the fields of table 1. Note that the starting index of args is not 0, but $r1. When the funcion adminPage() is called more than once in one only page, args must receive the names of all the form inputs. Each call of adminPage() creates part of these inputs, but they add the names of these inputs to one only array args. So, there must be a way not to repeat the indexes of args whenever this function is called on the page. This function returns the last index of args plus 1, and when it is called again on the page, this last index plus 1 must be one of the arguments of the parameter $JSIndex and the starting index of args in the current call of adminPage(). This is how we can make args receive all the names of the form input. The variable args and the variables ValuesTable0, ValuesTable1, ValuesTable2... are useful for manipulation of the form on the client side. */
			for ($i = 0; !empty($args[$i]); $i++){
				echo "args[".($i+$r1)."] = '".$args[$i]."$indTab1';\n";
			}
			//Below, args also receives the names of the fields of table 2.
			for ($j = 0; !empty($args2[$j]); $j++){
				echo "args[".($j+$i+$r1)."] = '".$args2[$j].$it1."0';\n";
			}
			$r1 += $j+$i;
			/*The Javascript variables ValuesTable0,... ValuestableN, receive from $values all the values related to the tables this form is associated with. Each ValuesTableI receives a row of $values. Note that the numbers that end the names of ValuesTable0,... ValuesTableN starts at $r2 in the current call of this function, which is a variable that, like the variable $r1, is used not to repeat these numbers with each call of this function adminPage(), so that every ValuesTavleI can receive all the data from the whole form. So, as seen before, $r1 and $r2 receives the starting indexes of args and ValuesTableI.*/
			for ($i = 0; $values[$i][0] !== NULL; $i++) {
				echo "var ValuesTable".($i+$r2)." = new Array();\n\n";
				for ($j = 0; $values[$i][$j] !== NULL; $j++) {
					echo "ValuesTable".($i+$r2)."[$j] = '".$values[$i][$j]."';\n";
				} 		
			} 
			echo "ValuesTable".($i+$r2)." = new Array();\n";
			echo "</script>\n";
			$r2 += $i;
		}
		/*Below we have the form inputs referring to table 1 being constructed. For each of them there is a label. The types of these inputs are contained in the $formTable1[3] vector, their names are in $args and their values in $values. The size of each of these inputs that are not textareas is the value in $maxlength if it's less than or equal to 50, and 50 if it is more than 50 ($max = 50), but the inputs maxlengths will always be equal to the value contained in $maxlength. There is also a variable called $properties, which receives the values provided in the $formTable1[7] vector, which contains other HTML properties that you want to assign to the inputs. Each div that surrounds each component of the form has an id and/or a class defined in this function. You must be aware of these values for you to create the CSS styles of this form.*/
		$i = 0;
		$max = 50;
		$html = $this->html; // This global variable receives the HTML code of all the form inputs.
		$html = "<div id='data$indTab1'>"; /* This div surrounds all the inputs associated with the tables of the current call of this function. Let's remember that $indTab1 is the index of the form inputs if they are repeated N times on the page by calling this function N times, and if they are and related to the same tables 1 and 2.*/
		$c = count($formTable1[2]);
		while ($args[$i] != NULL && $formTable1[3][$i] !== NULL) {
			// The div that surrounds a form input related to table 1 has its id equal to the name of this input.
			$html .= "<div id='d_".$args[$i]."$indTab1'";
			if ($formTable1[3][$i] !== 'hidden') $html .= " class='item'";
			$html .= ">";
			// If the last element of $formTable1[2] is the boolean value 'true', then the labels will be displayed along with their inputs.
			$is_label = ($formTable1[2][$c-1] === true || !in_array($formTable1[3][$i], array("text", "password", "textarea")));
			if ($is_label)
				$html .= "<label for='".$args[$i]."$indTab1'>".$formTable1[2][$i]."</label>";
			$maxlength = $formTable1[6][$i]; // The input maxlength can be any size.
			$comma_pos = strpos($maxlength, ',');
			if ($comma_pos !== false) {
				$m = ((int) substr($maxlength, 0, $comma_pos))+((int) substr($maxlength, $comma_pos+1))+1;
				$maxlength = "$m";
			} else $m = (int) $maxlength;
			if ($m > $max) {
				$size = "$max"; // The input size is limited in $max (=50), as seen before.
			} else $size = $maxlength;
			/*The variable $properties receives from $formTable1[7] a string containing other HTML properties that you pre-defined in order to assign it to each input. Each input has its own set of properties.*/
			$properties = $formTable1[7];
			if (is_array($formTable1[7])) $properties = $formTable1[7][$i]; 
			// The nth column of $values contains the main values of the records of table 1 (identifying these records to the user). If all the values of this column are not equal ($same_value = false), they will be shown in a select tag list.
			$same_value = true;
			for ($j = 0; !empty($values[$j][$n]); $j++) {
				if (!empty($values[$j+1][$n]) && $values[$j+1][$n] !== $values[$j][$n]) {
					$same_value = false;
					break;
				}
			}
			/*If $i points to the field that identifies to the user the record of table 1 ($i = $n), as seen before, and if more than one records were selected from table 1 through the SQL code pre-defined by the programmer, and if $select[1] = "select", that is, if the values of the main field to the user were defined to be shown in a select tag list when a select operation was made in the database, then the first list created previously will be shown for form manipulation, instead of an input with a value for this field.*/
			$html .= "<div class='c'>";
			if ($i == $n && !$same_value && $select[1] == "select") {
				$html .= "$list1\n";
			} 
			/* If you want the form to show any message so that it can be better understood, you can specify an 'input' whose 'type' is "html". It will actually be a hidden input whose value is this message, but it will be shown along with this hidden input, which menans that only it will be visible. Since we don't have a visible input to save such message into the database through this form, it can be defined through a database manager, like the phpmyadmin, or through another form.*/
			elseif ($formTable1[3][$i] === "html") {
				$html .= "<input type='hidden' name='".$args[$i]."$indTab1' id='".$args[$i]."$indTab1' value='".$values[0][$i]."'/>".$values[0][$i]."\n";
			/* If the current input is not a textarea, it can be defined by the HTML tag "input", and its type is in the vector $formTable1[3]. Note that each input is filled with its value, that is in the vector $values[0], When the inputs of this form are empty, it means that the variable $values is also empty.*/
			} elseif ($formTable1[3][$i] != "textarea") {
				$html .= "<input type='".$formTable1[3][$i]."' name='".$args[$i]."$indTab1' id='".$args[$i]."$indTab1' value='".$values[0][$i]."' size='$size' maxlength='$maxlength'  $properties ";
				// If the current form input is a checkbox, the code below makes it become checked in case its boolean value is true.
				if ($formTable1[3][$i] === "checkbox" && $values[0][$i]) $html .= "checked='checked' "; 
				// If the last element of $formTable1[2] is not the boolean value 'true', then the label will be displayed inside the input as a placeholder.
				if (!$is_label) $html .= "placeholder='".$formTable1[2][$i]."' ";
				$html .= "/>\n";
				/* If the value of the current input is the url of an image, This image will also be shown along with is input, through the tag "img".*/
				if ($formTable1[3][$i] === "file" && $this->is_image($values[0][$i])){
					$values[0][$i] = "<img src='".$values[0][$i]."' width=280>";
					$html .= "<div class='lab'>".$values[0][$i]."</div>\n";
				}
			/*If the type of the current input is "textarea", then it must be created by the tag "textarea"*/
			} else {
				$html .= "<textarea rows=5 cols=40 name='".$args[$i]."$indTab1' id='".$args[$i]."$indTab1' maxlength='$maxlength' $properties ";
				// If the last element of $formTable1[2] is not the boolean value 'true', then the label will be displayed inside the input as a placeholder.
				if (!$is_label) $html .= "placeholder='".$formTable1[2][$i]."' ";
				$html .= ">".$values[0][$i]."</textarea>\n";
			}
			$html .= "</div></div>\n\n";
			$i++;
		}
		$i = 0;
		//Creation of the fields of table 2 in the same way the forms of table 1 were created.
		$it1 = $indTab1;
		if ($args2 != NULL && $formTable2[3] != NULL) {
			$i = 0;
			$c = count($formTable2[2]);
			/*If the form inputs associated with table 2 is in the editing mode, it will have one more repetition of the inputs related to table 2 before all the other repetitions, and there will be a '0' in the end of their names, as seen previously. This inputs will never be empty. If their values are text or numerical values, the labels are written and just after each of them the values are written in inputs whose types are in $formTable2[3]. If any of them is an image, first the input is written on the left, whose type is "file", which will not be useful to update the image besite it, that is, the image that will be displayed on the right. These inputs are added to the variable $html.*/
			/* If the variable $indTab1 is not empty, then the inputs of table two are also repeated with every repetition of table 1, by calling this function N times in relation to the same tables 1 and 2. Then the inputs of table 2 will have names like 'name1_2', where the first number is related to the repetitions of the inputs of table 1 and the second number are related to the repetitions of the inputs of table 2 for every instance of table 1 inputs. */
			if ($indTab1 != '') {
				$indTab1 .= '_';
			}
			$value[0] = $values[0][$k];
			$edit2 = $this->editingMode ($table2, $value, $formTable2[1]);
			$is_editing2 = $edit2[0];
			if ($is_editing2) {
				$html .= "<div class='form2' id='form2_0'>"; // This div surrounds all the inputs of the first record of table 2, if it exists.
				while ($args2[$i] !== NULL) {
					$html .= "<div id='d_".$args2[$i].$indTab1."0'";
					if ($formTable2[3][$i] !== 'hidden') $html .= " class='item'";
					$html .= ">";// This div surrounds an input, and its id is equal to the input name, like "name0" or "name2_0"
					// If the last element of $formTable2[2] is the boolean value 'true', then the labels will be displayed along with their inputs.
					$is_label = ($formTable2[2][$c-1] === true || !in_array($formTable2[3][$i], array("text", "password", "textarea")));
					if ($is_label) {
						$html .= "<label for='".$args2[$i].$indTab1."0'>".$formTable2[2][$i]."</label>";
					}
					// Again the input maxlength can be any size, but is size cannot be more than $max (=50).
					$maxlength = $formTable2[6][$i];                                
					$comma_pos = strpos($maxlength, ',');
					if ($comma_pos !== false) {
						$m = ((int) substr($maxlength, 0, $comma_pos))+((int) substr($maxlength, $comma_pos+1))+1;
						$maxlength = "$m";
					} else $m = (int) $maxlength;   
					if ($m > $max) {
						$size = "$max";
					} else $size = $maxlength;
					// The variable $formTable2[7] can be a vector or a matrix of strings with sets of properties.
					$properties = $formTable2[7][$i];
					if (is_array($formTable2[7][0])) $properties = $formTable2[7][0][$i];
					/*If $i points to the field that identifies to the user any record of table 2, as seen before, and if more than one records were selected from table 2 through the SQL code pre-defined by the programmer, and if $select[1] = "select", that is, if the values of the main field to the user were defined to be shown in a select tag list when a select operation was made in the database, then the second list created previously will be shown for manipulation of the form inputs related to table 2, instead of an input with a value for this field.*/
					
					/* This first instance of the inputs related to table 2 is where the values of this table will be changed as the value of the second list is changed, if it exists.*/
					if ($i == $p-$k && !empty($values[1][$p]) && $select[1] === "select") {
						$html .= "<div class='c'>$list2</div>\n";
						//If the values in $list2 are images, then the $list2 current image will be displayed beside it.
						if (is_image($values[0][$p])) {
							$values[0][$p] = "<img src='".$values[0][$p]."' width=280 border='0'>";
							$html .= "<div class='lab' id='".$args2[$i].$indTab1."00'>".$values[0][$p]."</div>\n";
						}
					}
					// Note below that the variable used to assign values to the table 2 inputs is $values, and not $values2.
					// If the input type is "html", only the value with any important message for the form will be displayed.
					elseif ($formTable2[3][$i] === "html") {
						$html .= "<div class='c'>".$values[0][$i+$k]."</div>\n";
					/*If the type of the current input is not textarea, the tag 'input' is used to create it, and if it's not an file input either, a value will be assigned to its value property, otherwise, it will be an empty input, even if the form is in the editing mode, because file names cannot be updated.*/
					} elseif ($formTable2[3][$i] !== "file" && $formTable2[3][$i] !== "textarea") {
						$html .= "<div class='c'>";
						$html .= "<input type='".$formTable2[3][$i]."' id='".$args2[$i].$indTab1."0' name='".$args2[$i].$indTab1;
						/*If the type of the current input is radio, its name will be someting like "name" or "name1_", so that the repetitions of this input can have one only name and so that it is possible to choose only one option among the radio buttons with the same name. */
						if ($formTable2[3][$i] !== "radio") $html .= "0";
						// If the last element of $formTable2[2] is not the boolean values 'true', then the label will be displayed inside the input as a placeholder.
						if (!$is_label) $html .= "' placeholder='".$formTable2[2][$i];
						$html .= ($formTable2[3][$i] !== "checkbox" && $formTable2[3][$i] !== "radio") ? "' value='".$values[0][$i+$k]."' size='$size' maxlength='$maxlength' $properties /></div>\n" : "' value='".$values[0][$k]."' size='$size' maxlength='$maxlength' $properties /> ".$values[0][$i+$k]."</div>\n";
					}
					elseif ($formTable2[3][$i] === "file"){
						$html .= "<div class='c'><input type='".$formTable2[3][$i]."' name='".$args2[$i].$indTab1."0' id='".$args2[$i].$indTab1."0' value='' maxlength='$maxlength' $properties /></div>";
						//If the value of the current input is an image, it will be displayed beside it.
						if (is_image($values[0][$i+$k])) {
							$values[0][$i+$k] = "<img src='".$values[0][$i+$k]."' width=280 border='0'>";
							$html .= "<div class='lab' id='".$args2[$i].$indTab1."00'>".$values[0][$i+$k]."</div>\n";
						}
					}
					// The code inside the 'else' below is executed only if the current input type is textarea.
					else {
						$html .= "<div class='c'><textarea rows=3 cols=40 name='".$args2[$i].$indTab1."0' id='".$args2[$i].$indTab1."0' maxlength='$maxlength' $properties ";
						// If the last element of $formTable2[2] is not the boolean values 'true', then the label will be displayed inside the input as a placeholder.
						if (!$is_label) $html .= "placeholder='".$formTable2[2][$i]."' ";
						$html .= ">".$values[0][$i+$k]."</textarea></div>\n";
	
					}
					$i++;
					$html .= "</div>\n\n";
				}
				/* When $formTable2[9] == 'readonly', it means that the table 2 data can only be read. For instance, if the form is public and the user can't update these data, they must be defined  as 'readonly'. Otherwise, a delete button will be written on the page along with them in this first repetition of its inputs, if also $select[1] != 'form', that is, if the way of displaying the data of table 2 selected from the database is not a form format, wich means that the select tag list above will be created. This delete button will delete one record from table 2 as cescribed previously, that is, the ones shown in these inputs.*/
				if ($formTable2[9] !== 'readonly') $html .= "<input type='submit' name='delete".$values[0][$k]."' id='exclude' value='Delete'/><br>\n";
				$html .= '</div>';
			}
			$i = 1;
			$j = 0;
			$amountTable2 = $formTable2[8]; /* This is the number of times the inputs of table two are repeated on the page in order to create multiple records of it associated with one record of table 1.*/
			/*Below we have the writing of the inputs of table 2 just like it was done above, but for $amountTable2 times.*/
			while ($i <= $amountTable2) {
				$j = 0;
				$html .= "<div class='form2' id='form2_$i'>"; // the div that surrounds each repetition of the inputs of table 2
				while ($args2[$j] !== NULL && $formTable2[3][$j] != NULL) {
					$html .= "<div id='d_".$args2[$j].$indTab1."$i'";
					if ($formTable2[3][$j] !== 'hidden') $html .= " class='item'";
					$html .= ">";// the div that surrounds an input, whose id is equal to the input name.
					$maxlength = $formTable2[6][$j];
					// The inputs maxlengths can be any size, but their sizes are limited in $max (=50).
					$comma_pos = strpos($maxlength, ',');
					if ($comma_pos !== false) {
						$m = ((int) substr($maxlength, 0, $comma_pos))+((int) substr($maxlength, $comma_pos+1))+1;
						$maxlength = "$m";
					} else $m = (int) $maxlength;   
					if ($m > $max) {
						$size = "$max";
					} else $size = $maxlength;
					/* As seen before, the variable $formTable2[7] can be a vector or a matrix, and it contains a different set of properties for each input related to table 2, whether it's a vetor or it's a matrix. But if it's a matrix, then the programmer can define a different set of properties for each repetition of every input associated with table 2.*/
					$properties = $formTable2[7][$j];
					if (is_array($formTable2[7][$i])) $properties = $formTable2[7][$i][$j];
					/* The code below makes a red star (*) appear after labels related to required fields. $req is a variable that receives a value defined by the programmer in the array $formTable2, which is the maximum number of repetitions of each input that is defined as required. If the form is in the editing mode, the first instance of the table 2 inputs created previously are also composed of required fields whenever $req > 0, as seen before, which means that each $req must be decreased by 1.*/
					// If the last element of $formTable2[2] is the boolean value 'true', then the labels will be displayed along with their inputs.
					$is_label = ($formTable2[2][$c-1] === true || !in_array($formTable2[3][$j], array("text", "password", "textarea")));
					if ($is_label) {
						$html .= "<label for='".$args2[$j].$indTab1."$i'>".$formTable2[2][$j].'</label>';
					}
					$value = '';
					/* If $select[1] == "select", then the data of the part of the form related to table 2 are manipulated only in the first instance of the table 2 inputs, created previously, by changing the value of the list in $list2. But if $select[1] == "form", each record of these data will be written in each instance of the table 2 inputs. */
					if ($select[1] == "form") $value = $values[$i][$j+$k];
					//Now the table 2 inputs are builded for $amountTable2 times just like it was done above.
					if ($formTable2[3][$j] === "html") {
						$html .= "<div class='c'>".$values[$i][$j+$k]."</div>\n";
					} elseif ($formTable2[3][$j] !== "file" && $formTable2[3][$j] !== "textarea") {
						$html .= "<div class='c'>"; 
						$html .= "<input type='".$formTable2[3][$j]."' id='".$args2[$j].$indTab1."$i' name='".$args2[$j].$indTab1;
						if ($formTable2[3][$j] !== "radio") $html .= "$i";
						// If the last element of $formTable2[2] is not the boolean values 'true', then the label will be displayed inside the input as a placeholder.
						if (!$is_label) $html .= "' placeholder='".$formTable2[2][$j];
						$html .= ($formTable2[3][$j] !== "checkbox" && $formTable2[3][$j] !== "radio") ? "' value='$value' size='$size' maxlength='$maxlength' $properties /></div>\n" : "' value='".$values[$i][$k]."' size='$size' maxlength='$maxlength' $properties /> ".$values[$i][$j+$k]."</div>\n";
					} elseif ($formTable2[3][$j] === "file"){
						$html .= "<div class='c'><input type='".$formTable2[3][$j]."' name='".$args2[$j].$indTab1."$i' id='".$args2[$j].$indTab1."$i' value='' maxlength='$maxlength' $properties /></div>";
						if (is_image($value)) {
							$value = "<img src='$value' width=280 border='0'>";
							$html .= "<div class='lab' style='float: down;'>$value</div>\n";
						}
					}
					else {
						$html .= "<div class='c'><textarea rows=3 cols=40 name='".$args2[$j].$indTab1."$i' id='".$args2[$j].$indTab1."$i' maxlength='$maxlength' $properties ";
						// If the last element of $formTable2[2] is not the boolean values 'true', then the label will be displayed inside the input as a placeholder.
						if (!$is_label) $html .= "placeholder='".$formTable2[2][$j]."' ";
						$html .= ">$value</textarea></div>\n";
	
					}	
					$j++;
					$html .= "</div>";
				}
				/* If $select[1] == 'form', each record of data selected from table 2 in the database will fill one instance of the table 2 inputs, and when this happens, the table 2 inputs is in the editing mode. In this case, and if the data from table 2 is not 'readonly', there will be a Delete button for each repetition of the table 2 inputs, to delete the record shown in it. */
				if ($select[1] == 'form' && $formTable2[9] !== 'readonly' && $is_editing2 && !empty($values[$i][$k])) $html .= "<input type='submit' name='delete".$values[$i][$k]."' id='exclude' value='Delete'/><br>\n";
				$i++;
				$html .= '</div>';
			}
			/* For each record of table 2 shown in the form, there is only one record of table 3 that corresponds to it and to the record of table 1, if table 3 exists, that is, if the relation between tables 1 and 2 are M x N. */
			$k2 = count($args2);
			$args3 = $formTable3[0];
			$i = 0;
			if (!$is_editing2) $i = 1;
			if ($table3 !== NULL) {
				while ($i <= $amountTable2) {
					$j = 0;
					while ($args3[$j] !== NULL && $formTable3[3][$j] != NULL) {
						$name = $args3[$j];
						$html .= "<input type='".$formTable3[3][$j]."' name='$name".$indTab1."$i' value='".$values[$j+$k+$k2]."'>";
						$j++;
					}
					$i++;
				}
			}
		}
		$html .= '</div>';
		/*When you call the function adminPage(), it will automatically print the form created by it, unless the programmer defines $select[2] to be equal to "no_print". In this case, the programmer must have in mind that $html, the variable that contains the form, is a global variable, and so the form can be printed only when the programmer wants it to be printed.*/
		$this->html = $html;
		if ($select[2] !== "no_print")
			echo $this->html;
		//$values = transposed_matrix ($values);
		$ret = NULL;
		/*This function returns an array with the values of $r1 and $r2. These integer variables are the starting indexes of important Javascript variables in a possible next call of adminPage(), as we learnt previously. */
		if ($JSIndex !== NULL) $ret = array($r1, $r2);
		return $ret;	
			
	}
	
	/*
	The function designBlocks is able to write a whole page or most of its contents. It writes on a given page contents coming from a database and also an extra content that is pre-defined in values of the array $params. $params is the only argument of this function, and it is an array containing all the data necessary to build a page or most of its contents from a database. $params keys are described below:
	
	database: contains the database name from which the data to be witten is taken.
	fields: contains an sql 'select' code to get the data to be written on a page, or contanins an array that provides the database tables and its fields from which the data is taken to be witten. In this case, this function creates, from this fields and tables, the sql code that will take the data drom the database.
	labels: contains the labels associated with each datum coming from the database. 
	filter: in case the key 'fields' contains tables and its fields, the key filter is a string that contains what comes after whe 'where' clause of the sql code created as described above.
	properties: contains the properties of the div tags that surrounds each field value selected from the database or each extra content to be written among the data coming from the databae.
	linking: contains an array of doubles composed of two database fields of a table, where the first one contains web page addresses and the other contains the respective links to them. Alternatively, linking can be an array containing a string as its first value, wich is a given page. The other values of this array are doubles composed of two datababe fields of a given table, where the first one contains values that identifies instances of the page defined as the first value of 'linking', as in: page.php?id=5, where 5 is a value of this first database field. The second database field of these doubles contains links to their respective instances of the page described previously.
	sizes: In case images are selected from the database, sizes is an array of strings like "width = '300' height = '200'" that defines the size of each of these images. Also, sizes can be one only string like "width = '300' height = '200'" that will be the common size of every image selected from the database.
	includeContent: is an array containing pre-defined contents to be included along with each field value from the database to be written on the page where designBlocks is called, as defined below.
	insertContent: is an array containing pre-defined contents to be inserted among the data taken from the database, as defined below.
	*/
	function designBlocks ($params) {
		$con = $this->con;
		$i = 0;
		$k = 0;
		$fields = '';
		$fields2 = array();
		//$keys = array_keys($fields1);
		/*The code inside the if statement below will create the query to select from the database the data that will be written in the page where this function is called, in case $params["fields"] contains tables and fields instead of a sql code.*/
		if (is_array($params["fields"])) {
			//The first thing to be done is to get all the tables from the defined database.
			$sql = "show tables from ".$params["database"];
			$rs = mysqli_query($con, $sql);
			$tables = array();//the array containing the tables names.
			$tables_abbrev = array();//the array containing strings with the three first characters of each table. This strings will be the prefixes of the fields in the query to be built in case it is not provided as the 'fields' value.
			/*The code inside the if statement below will create the query in case $params["fields"] contains only fields, and not tables.*/
			if (!is_array($params["fields"][0])) {
				/*The while statement below is used to get all fields from all the tables of the given database. When any of these fields is found in the array $fields1, the name of the table this field belongs to is added to the array $table, and the first three characters of this table name are added to the array $tables_abbrev. $fields is a string that will be part of the query that is being created, and it will receive a concatenation of all fields names that exist in the database tables and are found in the array $fields1, separated by commas and preceded by the three first characters of the names of the tables they belong to. The array $fields2 receives all the fields that are both in $row2 and in $fields1. The boolean variable $foundIt is used to increment the index of $table and $tablesAbbrev, anytime a field in $row2 is found in $fields.*/
				while ($row = mysqli_fetch_array($rs, MYSQLI_NUM)) {
					$rs2 = mysqli_query($con, 'show fields from '.$row[0]);
					$j = 0;
					$foundIt = false;
					while ($row2 = mysqli_fetch_array($rs2, MYSQLI_NUM)) {
						if (in_array($row2[0], $fields1)) {
							$field = $row2[0];
							$table[$k] = $row[0]; 
							$tables_abbrev[$k] = substr($row[0], 0, 3);
							$fields .= $tables_abbrev[$k].".".$field.", ";
							$foundIt = true;
							$fields2[$i] = $field; 
							$i++;
						}
					}
					if ($foundIt)	$k++;
				}
			/*In case the array $params['fields'] is an array of arrays, which are doubles whose values are fields and the tables they belong to, then each of this tables is added to the array $table, and their first three characters are added to $tables_abbrev. The contents of the variable $fields and $fields2 are created the same way they are created in the case above. */
			} else {
				$k = 0;
				$j = 0;
				foreach ($params["fields"] as $field) {
					/*Notice that a same table can appear more than once in the array $params["fields"], because each double of it must contain a field name and its table and its very often to select more than one field from one only table. But the array $table will not receive repeated values of a same table, as shown below.*/
					if ($j == 0 || $field[1] != $table[$j-1]) {
						$table[$j] = $field[1];
						$tables_abbrev[$j] = substr($field[1], 0, 3);
						$j++;
					}
					if (!empty($field[0])) {
						$fields .= $tables_abbrev[$j-1].".".$field[0].", ";
						$fields2[$k] = $field[0];
						$k++;	
					}
				}
			}
			/*The last comma of $fields must be removed as in the line below, so that it can be used do create the query.*/
			$fields = substr($fields, 0, strlen($fields)-2);
			//Now the query begins to be created, and the for statement below will add to it all the tables related to each other with an "inner join", which means that every table provided to $params["fields"] (but the first one) must be related to the previous provided table by a foreign key.
			$sql = "select $fields from ".$table[0]." ".$tables_abbrev[0];
			for ($i = 1; $i < $j; $i++) {
				$sql .= " inner join ".$table[$i]." ".$tables_abbrev[$i]." on ";
				/*The lines below get the foreign keys of the tables from the code "show create table 'table'".*/
				$rs = mysqli_query($con, "show create table ".$table[$i]);	
				$row = mysqli_fetch_array($rs, MYSQLI_NUM);
				$s = $row[1];
				$start = strpos($s, "FOREIGN");
				$end  = strpos($s, "ENGINE")-2; 
				/*$s2 will contain a string like FOREIGN KEY (`cd_pergunta`) REFERENCES `pergunta` (`idPergunta`)*/
				$s2 = substr($s, $start, $end-$start);
				$start = strpos($s2, "(`")+2;
				$end  = strpos($s2, "`)"); 
				/*$field1 will get the field between the first parentheses in $s2, that is the foreign key of the current table.*/
				$field1 = substr($s2, $start, $end-$start);
				$start = strpos($s2, "(`", $end+2)+2;
				$end  = strpos($s2, "`)", $end+2); 
				/*$field2 will get the field between the seconde parentheses in $s2, that is the primary key of the referenced table.*/
				$field2 = substr($s2, $start, $end-$start);
				$offset = strpos($s2, "`)")+2; 
				$start = strpos($s2, "`", $offset)+1;
				$end  = strpos($s2, "`", $start+1); 
				/*$reftable is the referenced table in $s2.*/
				$reftable = substr($s2, $start, $end-$start);
				//Now it's added to $sql what comes after the on clause. The foreign key of the current table must be equal to the primary key of the referenced table.
				$sql .= $tables_abbrev[$i].".".$field1." = ".substr($reftable, 0, 3).".".$field2;
			}
			if (!empty($params["filter"])) {
				/*$params['filtro'] is a string to be added to the query string after the where clause, containing conditions that restricts the data selection by this query.*/
				$sql .= " where ".$params["filter"];
			}
		/*Alternatively, $params['fields'] can be defined as a string containing the very query that will select the data from the database to be written on the page, specially in cases where the query can't be created the way it is created previously. */
		} elseif (is_string($params["fields"])) {
			$sql = $params["fields"];
			$rs = mysqli_query($con, $sql);
			$row = NULL;
			if ($rs)	
				$row = mysqli_fetch_array($rs);
			//It is always necessary to make the array $fields2 receive the names of the fields that provide the data to be written on the page, as in the 'for' statement below. This array will be useful posteriorly. 
			$aux = array_keys($row);
			for ($i = 0; $row[$i] !== NULL; $i++) {
				$fields2[$i] = $aux[2*$i+1];
			} 
		}
		//SELECTION OF THE CONTENT FROM THE TABLES
		
		$args = array();
		/*The PHP function select(), defined in functionsdb.php, will be used to execute the query.*/
		$args = $this->select($sql, $fields2);
		$html = '';//$html is the string with whe output HLML code.
		/*When we have links among the data to be written on the page, there will be two data related to each of them, which are the links themselves and the urls they direct to. Only one of this two data must be shown on the page, and it is the link. In order not to write the urls on the page, the array $exclude is defined to contain the indexes of these urls in the array $args. But if the link is the very url it directs to, it will be written on the page.*/
		$exclude = array();
		$i = 0;
		
		/*Among the selected data, there are data of various types. When there are images, what is stored about them is just the urls referring to them, and when they are displayed on the page, it is necessary that the url be the information that fills the 'src' property of the img tag, in order to make the image itself appear, which is stored in a specific folder. The $params['sizes'] variable can be a vector containing different sizes for each image or a string with a single size for all images.*/
		foreach ($args as $arg) {
			$j = 0;
			$k = 0;
			foreach ($arg as $a) {
				if ($this->is_image($a)){
					$size = NULL;
					if (is_array($params["sizes"])) {
						$size = $params["sizes"][$k];
						$k++;
					} else $size = $params["sizes"];
					$linkPicture = FALSE;
					/*If the image $a is an url to which another image directs, then it will not be turned into an image through the tag img.*/
					foreach ($params["linking"] as $link) {
						if ($fields2[$j] === $link[0]) {
							$linkPicture = TRUE;	
							break;	
						}
					}
					if (!$linkPicture) {
						$args[$i][$j] = "<img src='".$a."' border=0 $size/>\n"; 
					} 
				}
				$j++;
			}
			$i++;
		}
		/*When there are links among the data selected from the database, then we have urls and the information that will be the links to these urls among these data. In the code below, the selected values which have been saved to be links are converted into links to their specific url, according to the linking rules established in $params["linking"]. */
		if (!empty($params["linking"])) {
			if (is_array($params["linking"][0])) {
				$k = 0;
				foreach ($params["linking"] as $link) {
					/*Given that the data from the database contained in $args belong to the fields in the array $fields2 and that these fields are ordered correctly according to their data in $args, then the fields names contained in each $link will have a position inside the array $fields2, and these positions will be the same positions of the data in $args that will be converted into links to be written on the page.*/
					$index1 = array_keys($fields2, $link[0]);	
					$index2 = array_keys($fields2, $link[1]);
					$i1 = $index1[0];	
					$i2 = $index2[0];
					//In this linking rule, each value of $params["linking"] is a double containing the url and the url link. 
					for ($i = 0; $args[$i][0] !== NULL; $i++) {
						$args[$i][$i2] = "<a href='".$args[$i][$i1]."' target='_blank'>".$args[$i][$i2]."</a>";	
					}
					if ($i1 != $i2) $exclude[$k] = $i1;
					$k++;
				}
			}
			/*When $params["linking"][0] is not an array, it is the string that contains the url of all links that have been selected in the database. The following values of $params["linking"] are doubles containing a link and an index that will define the content of what will be written on the page, which is related to the link.*/
			else {
				for ($k = 1; $params["linking"][$k] !== NULL; $k++) {
					$index1 = array_keys($fields2, $params["linking"][$k][0]);	
					$index2 = array_keys($fields2, $params["linking"][$k][1]);
					$i1 = $index1[0];	
					$i2 = $index2[0];
					for ($i = 0; $args[$i][0] != NULL; $i++) {
						$args[$i][$i2] = "<a href='".$params["linking"][0]."?id=".$args[$i][$i1]."' target='_blank'>".$args[$i][$i2]."</a>";	
					}
					if ($i1 != $i2) $exclude[$k] = $i1;	
					$k++;
				}
			}
		}
		
		//ESCRITA DOS VALORES EM HTML
		
		/*For each row of data selected by the query, a div tag is defined, and it will have an id = 'post_i' and html properties $p. Inside these divs, the values of each field that is not excluded, relative to the current row of data whose html properties are $p, will be written, each of them also surrounded by div tags, and before each value written there is a label, surrounded by a span tag whose class is 'label_j'.
		*/
		$itChanged = true;
		$count = 1;
		/*Among the $args values, other contents can be inserted, like a HTML table with data from any database table or a list with data defined manually. The boolean variable $insert is true if there are such contents.*/
		$insert = !empty($params["insertContent"]);
		/*The variable $insertArray is true if the contents to be inserted are in a matrix, which means that each row of $args has different values for the contents to be inserted in a same column among the $args values. Otherwise, a given content will be repeated in each row of a same column where it will be inserted in $args. */
		$insertArray = is_array($params["insertContent"]["content"][0]);
		for ($i = 0; $args[$i][0] !== NULL; $i++) {
			$p = '';
			/*The variable $p will be fed with the contents of $params['properties']. If $params['properties'] is a string, then one only set of properties is applied to the divs of each row and to the divs of each column. But if $params['properties'] is an array, the divs of the rows will have the same set of properties and the columns will have another one, or each column will have its own set of properties. $params["properties"][0] will be the properties of each div tag that surrounds all the data of each $args row.
			*/
			if (is_array($params["properties"])) {
				$p = $params["properties"][0];
			}
			else $p = $params["properties"];
			/*Whenever the boolean variable $itChanged is true, the contents pre-defined to be included along with each line of the array #args will be written before or after all the data of these lines. It will be true every time the first column of the current line of the variable $args is different from the first column of its previous line.*/
			$itChanged = ($args[$i][0] !== $args[$i-1][0]);
			/*If $params['includeContent']['level'] is an empty value, the extra content will be writen only once for each $args line.*/
			if ($i > 0 && $itChanged) {
				/*The HTML content to be written through this function will be in the variable $html. Its content won't begin to be built in the code snippet below. Note that it will work only for $i > 0. $html begins to receive values when $i = 0, and the first tag div will be opened then, in the code snippet after this one. Here the extra content will be added to $html after the data of $args current line.*/
				if (!empty($params['includeContent']) && empty($params['includeContent']['level']) && $params['includeContent']['beforeAfter'] === 'after') {
					$html .= $params['includeContent']['content'][$count-1];
				}
				$html .= "</div>\n\n";
				$count++;
			}
			if ($itChanged) {
				//Here the extra content will be added to $html before the data of $args current line and the variable $html begins to be built.
				$html .= "<div id='post_".($count)."' $p>";
				if (!empty($params['includeContent']) && empty($params['includeContent']['level']) && $params['includeContent']['beforeAfter'] === 'before') {
					$html .= $params['includeContent']['content'][$count-1];
				}
			}
			$j = 0;
			$k = 0; 
			/*The code below adds to $html each field value (column) of $args current line.*/
			$index = 0;
			while ($args[$i][$j] !== NULL) {
				/*An $args value (that is, any $args[$i][$j]) will be added to $html string only if it is not a repeated value and if it is not a value to be excluded, as defined previously. But if $j = $params["insertContent"]["index"][$index], then another content will be added to $html instead of an $args value.*/
				$insertContent = ($j === $params["insertContent"]["index"][$index]);
				if (empty($params['i1'])) $params['i1'] = 1;
				if ($i == 0 || ($i > 0 && ($j >= $params['i1'] || $args[$i][$j] != $args[$i-1][$j])) || $insertContent) {
					if (!in_array($j, $exclude)) {
						/* If $args[$i][$j} is a date, time or datetime, it must be written on the page in the format defined by the programmer. This is done below. */
						if ($this->is_dbdate($args[$i][$j])) $args[$i][$j] = $this->form_datetime_create ($args[$i][$j], 'date');
						elseif ($this->is_dbtime($args[$i][$j])) $args[$i][$j] = $this->form_datetime_create ($args[$i][$j], 'time');
						elseif ($this->is_dbdatetime($args[$i][$j])) $args[$i][$j] = $this->form_datetime_create ($args[$i][$j], 'datetime');
						$onde = 0;
						$p = '';
						/*The variable $onde receives a value tnat defines if the extra content will be writen after of before the current $args value. If $params['includeContent']['level'] == 1 the extra content will be writen once for each $args value.*/
						if ($params['includeContent']['level'] == 1) { 
							$onde = $params['includeContent']['beforeAfter'];
						}
						/*Each $args value will be surrounded by a div tag that has a set of properties. Each of these div tags can have its own properties, or there will be an only set of properties for each value of each $args line, or there will be an only set of properties common to all $args values.*/
						if (is_array($params["properties"])) {
							if (is_array($params["properties"][1])) {
								$p = $params["properties"][1][$k];
							}
							else $p = $params["properties"][1];
						} else {
							$p = $params["properties"];
						}
						/*The if-else structure below contains the main part of the HTML building. It adds to $html each $args value. If there are extra contents to be written with each $args value, they will be added to $html before or after these values. The parameter 'separator' is a number that defines which $args values will be written along with the extra content that is common to each column of the current line. If the column index ($j) is less than this number, then the extra content won't be written along with $args[$i][$j]. If it's equal to or more than this number, then the extra content of the current line will be added to $html with the current $args value.*/
						if ($onde === 'before') {
							$html .= "<div $p";
							if ($j >= $params['separator']) 
								$html .= " class='item2'>".$params['includeContent']['content'][$i];
							else $html .= " class='item1'>";
							if (!empty($params["labels"][$k])) $html .= "<span class='label".($k+1)."'>".$params["labels"][$k]."</span>";
							// If $insertContent is true, then another content will be added to $html instead of an $args value.
							if (!$insertContent)
								$html .= $args[$i][$j];
							else {
								if ($insertArray) $html .= $params['insertContent']['content'][$i][$index];
								else $html .= $params['insertContent']['content'][$index];
								$index++;
								/* The variable $j must be decreased so that no column of the current $args line is skipped and fails to be added to $html.*/
								$j--;
							}
							$html .= "</div>\n";
						} elseif ($onde === 'after') {
							$html .= "<div $p";
							if ($j < $params['separator']) $html .= "  class='item1'>";
							else $html .= " class='item2'>";
							if (!empty($params["labels"][$k])) $html .= "<span class='label".($k+1)."'>".$params["labels"][$k]."</span>";
							// If $insertContent is true, then another content will be added to $html instead of an $args value.
							if (!$insertContent)
								$html .= $args[$i][$j];
							else {
								if ($insertArray) $html .= $params['insertContent']['content'][$i][$index];
								else $html .= $params['insertContent']['content'][$index];
								$index++;
								/* The variable $j must be decreased so that no column of the current $args line is skipped and fails to be added to $html.*/
								$j--;
							}
							if ($j >= $params['separator']) 
								$html .= $params['includeContent']['content'][$i];
							$html .= "</div>\n";
						}
						else {	
							$html .= "<div $p";
							if ($j < $params['separator']) $html .= "  class='item1'>";
							else $html .= " class='item2'>";
							if (!empty($params["labels"][$k])) $html .= "<span class='label".($k+1)."'>".$params["labels"][$k]."</span>";	
							// If $insertContent is true, then another content will be added to $html instead of an $args value.
							if (!$insertContent)
								$html .= $args[$i][$j];
							else {
								if ($insertArray) $html .= $params['insertContent']['content'][$i][$index];
								else $html .= $params['insertContent']['content'][$index];
								$index++;
								/* The variable $j must be decreased so that no column of the current $args line is skipped and fails to be added to $html.*/
								$j--;
							}
							$html .= "</div>\n";
						}
						$k++;
					}
				}
				$j++;
			}
		}
		if (!empty($params['includeContent']) && empty($params['includeContent']['level']) && $params['includeContent']['beforeAfter'] === 'after') {
			$html .= $params['includeContent']['content'][$count-1];
		}
		$html .= "</div>";
		return $html;
	}
	
	/*A função incuiConteudo inclui o(s) conteudos(s) $conteudo no(s) elemento(s) cujo(s) id('s) é (são) $class, no início ou no fim do(s) elemento(s), de acordo com a variável $antes_depois  */
	public function incluiConteudo($conteudo, $class, $antes_depois){
		if ($antes_depois == 'depois') {
			if (is_string($conteudo)) {
	?>
			<script language="javascript">
				document.getElementById("<?php echo $class;?>").innerHTML += "<?php echo $conteudo;?>";
			</script>		
	<?php
			}
			elseif (is_array($conteudo)) {
				$i = 0;
				foreach ($conteudo as $c) {
	?>
				<script language="javascript">
					document.getElementById("<?php echo $class[$i];?>").innerHTML += "<?php echo $c;?>";	
				</script>	
	<?php
					$i++;
				}
			}
		}
		elseif ($antes_depois == 'antes') {
			if (is_string($conteudo)) {
	?>
			<script language="javascript">
				document.getElementById("<?php echo $class;?>").innerHTML = "<?php echo $conteudo;?>"+document.getElementById("<?php echo $class;?>").innerHTML;	
			</script>	
	<?php
			}
			elseif (is_array($conteudo)) {
				$i = 0;
				foreach ($conteudo as $c) {
	?>
				<script language="javascript">
					document.getElementById("<?php echo $class[$i];?>").innerHTML = "<?php echo $c;?>"+document.getElementById("<?php echo $class[$i];?>").innerHTML;
				</script>		
	<?php
					$i++;
				}
			}
		}
	}
}	
	/* USELESS PHP FUNCTIONS FOR W3C STANDARDS
	
	
	$args é uma matriz bidimensional que contem uma tabela que é resultado da execução de um comando select do SQL 
	
	$quant define a quantidade de colunas de $args que serão escritas na função, a partir da última coluna
	
	Esta função acrescenta a possibilidade de se escrever tabelas em matriz bidimensional, onde a variável $n tão somente definirá quantas colunas a matriz terá.
	
	
	Se $img é true então a coluna $indiceLink será uma imagem, caso $indiceLink for maior ou igual a 0. Se $indiceLink for menor do que zero e $img for true, então todas as colunas da tabela serão imagens cujo endereço é o conteúdo da coluna.
	
	$estilo define todos os atributos html da tabela que será escrita contendo o resultado de uma consulta sql
	*/
	$menu = '';
	$child = FALSE;
	function escreveTabela ($args, $quant, $n, $img, $estilo, $cabecalho) {
		global $quantTabelas;
		$quantTabelas++;
		$i = 0;
		while ($args[0][$i] != NULL) {
			$i++;
		}
		$quant = (int) $quant;
		$i = $i - $quant;
		$k = 0;
		$table = '';
		$j = $i;
		if ($n == 1 && !empty($cabecalho)) {
			$table = "<table $estilo><tr>";
			while ($cabecalho[$j] != NULL) {
				$table .= "<td align='center'>".$cabecalho[$j]."</td>";
				$j++;
			}
			$table .= "</tr><tr>";
		} else	$table = "<table $estilo><tr>";
		while ($args[$k][$i] != NULL) {
			while ($args[$k][$i] != NULL) {
				$table .= "<td align='center'>";
				if ($img) {
					if (strpos($args[$k][$i], "[a]") !== FALSE) {
						$aux = substr($args[$k][$i], 3);
						$table .= "<a href='".$args[$k][$i-1]."'><img src='".$aux."' width='90'/></a>";
					} else	$table .= "<img src='".$args[$k][$i]."'/>";
				} else $table .= $args[$k][$i];
				$table .= "</td>";
				$i++;
			}
			$i = $i - $quant;
			$k++;
			if ($k % $n == 0) {
				$table .= "</tr><tr>";
			}
		}
		$table .= "</tr></table>";
		echo $table;
	}
	/*
	$args é uma matriz bidimensional que contem uma tabela que é resultado da execução de um comando select do SQL
	
	$links contem uma página php para a qual o site encaminhará quando for clicado um link correspondente a alguma coluna da tabela que será escrita.
	
	$indiceLink é a coluna da tabela que irá se exibida como um link que levará à página contida em links. Tal link irá conter o parâmetro código, na coluna zero de $args
	
	$quant define a quantidade de colunas de $args que serão escritas na função, a partir da última coluna
	
	Esta função acrescenta a possibilidade de se escrever tabelas em matriz bidimensional, onde a variável $n tão somente definirá quantas colunas a matriz terá.
	
	Se $img é true então a coluna $indiceLink será uma imagem, caso $indiceLink for maior ou igual a 0. Se $indiceLink for menor do que zero e $img for true, então todas as colunas da tabela serão imagens cujo endereço é o conteúdo da coluna.
	
	$estilo define todos os atributos html da tabela que será escrita contendo o resultado de uma consulta sql
	*/
	function escreveTabela2 ($args, $links, $indiceLink, $quant, $n, $img, $estilo) {
		global $quantTabelas;
		$quantTabelas++;
		$table = "<table '$estilo'><tr>";
		$i = 0;
		while ($args[0][$i] != NULL) {
			$i++;
		}
		$quant = (int) $quant;
		$i = $i - $quant;
		$k = 0;
		//echo $i;
		while ($args[$k][$i] != NULL) {
			while ($args[$k][$i] != NULL) {
				$table .= "<td>";
				if ($img) {
					if ($i == $indiceLink) {
						$aux = $args[$k][$i];
						$table .= "<a href='".$links."?ide=".$args[$k][0]."'><img src='".$aux."' width='90'/></a>";
					} else	$table .= "<img src='".$args[$k][$i]."'/>";
				} else {
					if ($i == $indiceLink) {
						$aux = $args[$k][$i];
						$table .= "<a href='".$links."?ide=".$args[$k][0]."'>".$aux."</a>";
					} else	$table .= $args[$k][$i];
					//$table .= $args[$k][$i];
				}	
				$table .= "</td>";
				$i++;
			}
			$i = $i - $quant;
			$k++;
			if ($k % $n == 0) {
				$table .= "</tr><tr>";
			}
			
		}
		$table .= "</tr></table>";
		echo $table;
	}
	
	function designSite ($top, $left, $width, $height) {
	
		echo "<script language='javascript'>";
		echo "document.all('site').style.top = $top*screen.height/100;";
		echo "document.all('site').style.left = $left*screen.width/100;";
		echo "document.all('site').style.width = $width*screen.width/100;";
		echo "document.all('site').style.height = $height*screen.height/100;";
		echo "</script>";
	}
	
	function designEstilosJV($file, $PixelOuPorcent, $props){
		/*$props = array("top", "left", "width", "height");
		for ($i = 0; $estilo[$i] != NULL; $i++) {
			$props[$i+4] = $estilo[$i];
		}*/
		$h = fopen ($file, 'r'); 
		$matriz = fread($h, filesize($file));
		$parentese = strpos($matriz, "(");
		$estilos = " ";
		$k = 1;
		while ($parentese !== FALSE){
			$j = $parentese+1;
			$posdim = array();
			$i = 0;
			$num = '';
			$estilos .= "#pos$k {\n ";
			while ($matriz[$j-1] != ')') {
				if ($matriz[$j] != ' ' && $matriz[$j] != ')'){
					$num .= $matriz[$j];
				} else if ($props[$i] != NULL){
					if ($PixelOuPorcent == '%' && $i < 4) {
						$num.='%';
					} else if ($i < 4) $num.='px';
					$estilos .= $props[$i].": ".$num."; " ;
					$num = '';
					$i++;
				}
				$j++;
			}
			$estilos .= "}";
			$matriz = substr($matriz, $j);
			$parentese = strpos($matriz, "(");
			$k++;
		}
		$estilos .= "";
		return $estilos;
	}
	function designEstilosPonto($file, $ehTag, $estilo){
		$props = array("font-family", "color", "font-size");
		for ($i = 0; $estilo[$i] != NULL; $i++) {
			$props[$i+3] = $estilo[$i];
		}
		$h = fopen ($file, 'r'); 
		$matriz = fread($h, filesize($file));
		$parentese = strpos($matriz, "(");
		$estilos = " ";
		$k = 1;
		while ($parentese !== FALSE){
			$j = $parentese+1;
			$posdim = array();
			$i = 0;
			$num = '';
			$nomeEstilo = substr($file, 0, strlen($file)-4);
			if (!$ehTag){ 
				$estilos .= ".estilo$k {";
			} else {
				$estilos .= "$nomeEstilo {";
			}
			while ($matriz[$j-1] != ')') {
				if ($matriz[$j] != ' ' && $matriz[$j] != ')'){
					$num .= $matriz[$j];
				} else if ($props[$i] != NULL){
					$estilos .= $props[$i].": ".$num."; " ;
					$num = '';
					$i++;
				}
				$j++;
			}
			$estilos .= "}";
			$matriz = substr($matriz, $j);
			$parentese = strpos($matriz, "(");
			$k++;
		}
		$estilos .= "";
		return $estilos;
	}
	
	function designTable($conteudoTabela, $estiloTabela, $arquivoOuEstilos, $estilo, $num_cols) {
		global $quantTabelas;
		$quantTabelas++;
		if (strpos($arquivoOuEstilos, '.txt') != FALSE || strpos($arquivoOuEstilos, '.rtf') != FALSE) {
			$props = array("align", "width", "height");
			for ($i = 0; $estilo[$i] != NULL; $i++) {
				$props[$i+3] = $estilo[$i];
			}
			$h = fopen ($arquivoOuEstilos, 'r'); 
			$matriz = fread($h, filesize($arquivoOuEstilos));
			$estilosCelulas = array();
			$estilosCelulas = explode("\n", $matriz);
			$i = 0;
			while ($estilosCelulas[$i] != NULL) {
				$estiloCelula = array();
				$estiloCelula = explode(" ", $estilosCelulas[$i]);
				$j = 0;
				$estilosCelulas[$i] = '';
				while ($estiloCelula[$j] != NULL) {
					$estiloCelula[$j] = $props[$j].'="'.$estiloCelula[$j].'" ';
					$estilosCelulas[$i] .= $estiloCelula[$j];
					//echo $estilosCelulas[$i]."<br>";
					$j++;
				}
				$i++;
			}
		} 
		
		$tabela = "<table $estiloTabela><tr id='Row$quantTabelas$i'>\n";
		for ($i = 0; $conteudoTabela[$i] != NULL; $i++) {	
			if (strpos($arquivoOuEstilos, '.txt') !== FALSE || strpos($arquivoOuEstilos, '.rtf') !== FALSE) {
				$tabela .= "<td ".$estilosCelulas[$i].">".$conteudoTabela[$i]."</td>";
			}
			else $tabela .= "<td $arquivoOuEstilos>".$conteudoTabela[$i]."</td>";
			if ($i % $num_cols == $num_cols-1) {
				$tabela .= "</tr>\n<tr id='Row$quantTabelas$i'>";
			}
			//echo $i;
		}
		//echo $i;
		$tabela .= "</tr></table>";
		return $tabela;
	}
	function designTable2($conteudoTabela, $estiloTabela, $arquivoOuEstilos, $estilo) {
		global $quantTabelas;
		$quantTabelas++;
		if (strpos($arquivoOuEstilos, '.txt') != FALSE || strpos($arquivoOuEstilos, '.rtf') != FALSE) {
			$props = array("align", "width", "height");
			for ($i = 0; $estilo[$i] != NULL; $i++) {
				$props[$i+3] = $estilo[$i];
			}
			$h = fopen ($arquivoOuEstilos, 'r'); 
			$matriz = fread($h, filesize($arquivoOuEstilos));	
			$estilosCelulas = array();
			$estilosCelulas = explode("\n", $matriz);
			$i = 0;
			while ($estilosCelulas[$i] != NULL) {
				$estiloCelula = array();
				$estiloCelula = explode(" ", $estilosCelulas[$i]);
				$j = 0;
				$estilosCelulas[$i] = '';
				while ($estiloCelula[$j] != NULL) {
					$estiloCelula[$j] = $props[$j].'="'.$estiloCelula[$j].'" ';
					$estilosCelulas[$i] .= $estiloCelula[$j];
					$j++;
				}
				$i++;
			}
		} 
		$tabela = "<table $estiloTabela>";
		$k = 0;
		$i = 0;
		foreach ($conteudoTabela as $linha) {
			$tabela .= "<tr id='Row$quantTabelas$i'>";	
			foreach ($linha as $cell) {
				if (strpos($arquivoOuEstilos, '.txt') !== FALSE || strpos($arquivoOuEstilos, '.rtf') !== FALSE) {
					$tabela .= "<td ".$estilosCelulas[$k].">".$cell."</td>";
					$k++;
				}
				else $tabela .= "<td $arquivoOuEstilos>".$cell."</td>";
				//echo $conteudoTabela[$i][$j];
			}
			//echo $conteudoTabela[$i][$j];
			$tabela .= "</tr>";
			$i++;
		}
		$tabela .= "</table>";
		return $tabela;
	}
	function get_menu($exclude, $parent) {
		global $con;
		$sql = "select t.term_id, t.name from wp_terms t inner join wp_term_taxonomy tt on t.term_id = tt.term_id where tt.parent = $parent order by t.term_id";
		global $menu;
		global $child;
		$rs = mysqli_query($con, $sql);
		if (!$child) {
			$menu .= "<ul class='sf-menu'>\n";
		} else $menu .= "<ul class='children'>\n";	
		while ($row = mysqli_fetch_array($rs)){
			$menu .= "<li><a href='".get_bloginfo("url")."/?cat=".$row["term_id"]."'>".$row["name"]."</a>";
			$rs2 = mysqli_query($con, "select tt.term_taxonomy_id from wp_term_taxonomy tt where tt.parent = ".$row["term_id"]);
			if ($row2 = mysqli_fetch_array($rs2)) {
				//$menu .= $row["term_id"];
				$child = TRUE;
				get_menu($exclude, $row["term_id"]);
			}
			$menu .= "</li>\n";
		}
		$menu .= "</ul>\n";
		$ret = $menu;
		return $ret;
	}
?>