<?php session_start();?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
</head>

<body>
<form name="email" method="post" action="email_marketing.php">
<label for="ini_date">Data inicial:</label><input type="text" name="ini_date" id="ini_date" maxlength="10"/><br />
<label for="end_date">Data final:</label><input type="text" name="end_date" id="end_date" maxlength="10" /><br />
<label for="subject">Assunto:</label><input type="text" name="subject" id="subject" maxlength="512" /><br />
<label for="message">Mensagem:</label><textarea name="message" id="message" cols="40" rows="3" maxlength="2048"></textarea><br />
<input type="submit" name="enviar" value="Enviar" />
</form>
</body>
</html>
