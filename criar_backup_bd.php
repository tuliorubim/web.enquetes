<?php 
    //ENTER THE RELEVANT INFO BELOW
    $mysqlUserName      = "root";
    $mysqlPassword      = "5hondee5WBa0";
    $mysqlHostName      = "localhost";
    $DbName             = "webenque_enquetes";
    $backup_name        = "mybackup.sql";
    $tables             = array('administradores', 'aquisicao_servico', 'categoria', 'cliente', 'comentario', 'comentario2', 'contador', 'contador_tm', 'cont_enquete', 'enquete', 'enviados', 'formaspagamento', 'pergunta', 'resposta', 'servico', 'voto');
	function save_file ($content, $name, $mode='a') {
		$dest = $name;
		/* If the mode is 'x' or 'x+' and if the file whose name is $name exists, it will be deleted, because fopen() fails to access a file if it exists and the access mode to it is 'x' or 'x+'. This procedure is performed to avoid this failure.  */
		if ($mode[0] === 'x' && file_exists($dest)) {
			unlink($dest);
		} 
		$handle = fopen ($dest, $mode); // $handle receives the open file whose name is $dest ($name).
		$ret = fwrite ($handle, $content); // The content is written in the open file, according to the mode of access to it.
		fclose($handle); // Finally the open file is closed.
		return $ret; // This function returns the result of the file writing execution, that is, the number of bytes written or FALSE, if the execution fails.	
	}

   //or add 5th parameter(array) of specific tables:    array("mytable1","mytable2","mytable3") for multiple tables

    Export_Database($mysqlHostName,$mysqlUserName,$mysqlPassword,$DbName,  $tables=false, $backup_name=false );

    function Export_Database($host,$user,$pass,$name,  $tables=false, $backup_name=false )
    {
        $mysqli = new mysqli($host,$user,$pass,$name); 
        $mysqli->select_db($name); 
        $mysqli->query("SET NAMES 'utf8'");

        $queryTables    = $mysqli->query('SHOW TABLES'); 
        while($row = $queryTables->fetch_row()) 
        { 
            $target_tables[] = $row[0]; 
        }   
        if($tables !== false) 
        { 
            $target_tables = array_intersect( $target_tables, $tables); 
        }
        foreach($target_tables as $table)
        {
            $result         =   $mysqli->query('SELECT * FROM '.$table);  
            $fields_amount  =   $result->field_count;  
            $rows_num=$mysqli->affected_rows;     
            $res            =   $mysqli->query('SHOW CREATE TABLE '.$table); 
            $TableMLine     =   $res->fetch_row();
            $content        = (!isset($content) ?  '' : $content) . "\n\n".$TableMLine[1].";\n\n";

            for ($i = 0, $st_counter = 0; $i < $fields_amount;   $i++, $st_counter=0) 
            {
                while($row = $result->fetch_row())  
                { //when started (and every after 100 command cycle):
                    if ($st_counter%100 == 0 || $st_counter == 0 )  
                    {
                            $content .= "\nINSERT INTO ".$table." VALUES";
                    }
                    $content .= "\n(";
                    for($j=0; $j<$fields_amount; $j++)  
                    { 
                        $row[$j] = str_replace("\n","\\n", addslashes($row[$j]) ); 
                        if (isset($row[$j]))
                        {
                            $content .= '"'.$row[$j].'"' ; 
                        }
                        else 
                        {   
                            $content .= '""';
                        }     
                        if ($j<($fields_amount-1))
                        {
                                $content.= ',';
                        }      
                    }
                    $content .=")";
                    //every after 100 command cycle [or at last line] ....p.s. but should be inserted 1 cycle eariler
                    if ( (($st_counter+1)%100==0 && $st_counter!=0) || $st_counter+1==$rows_num) 
                    {   
                        $content .= ";";
                    } 
                    else 
                    {
                        $content .= ",";
                    } 
                    $st_counter=$st_counter+1;
                }
            } $content .="\n\n\n";
        }
        //$backup_name = $backup_name ? $backup_name : $name."___(".date('H-i-s')."_".date('d-m-Y').")__rand".rand(1,11111111).".sql";
		$w = date("w", strtotime(date()));
        $backup_name = $backup_name ? $backup_name : $name.$w.".sql";
		save_file ($content, $backup_name);
        /*header('Content-Type: application/octet-stream');   
        header("Content-Transfer-Encoding: Binary"); 
        header("Content-disposition: attachment; filename=\"".$backup_name."\"");  
        echo $content; exit;*/
    }
	
?>
<a id='download' href='<?php echo $backup_name;?>' download><?php echo $backup_name;?></a>
<script>//document.getElementById("download").click();</script>