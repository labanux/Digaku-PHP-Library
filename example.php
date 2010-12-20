<?php session_start();?>

<html>
<head>
<title>Digaku API Example</title>
<style type="text/css">
	body {font-family:verdana;font-size:11px;}
</style>
</head>
<body>

<h1>Digaku Client Example</h1>

<?php
include 'Digaku.php';

$digaku = new Digaku($_SESSION['a_token']);

if($digaku->access_token == null) {
	$digaku->authorizeCode();	
} else {
	echo '<a href="http://localhost/digaku/logout.php">Logout</a>';
}
?>

<?php 
$my_info = json_decode($digaku->my_info());

if($_POST['stream'] != null) {
	//$digaku->getApi('write/mind', array('access_token' => $digaku->access_token, 'origin_id' => $my_info->result->id, 'message' => $_POST['stream']), 'post');	
}

$stream = $digaku->getApi('my/streams', array('access_token' => $digaku->access_token));
$stream = json_decode($stream);

?>

<ol>
<?php foreach($stream->result as $item) :?>
	<li>
		<?php echo $item->writer[1];?><br/>
		<?php echo $item->message;?>
	</li>
<?php endforeach;?>
</ol>

<form action='' method='post'>
<input type="text" name="stream">
<input type="submit" value="SPAM !">
</form>

<?
//echo '<pre>';
//var_dump($stream->result);

?>
</body>
</html>

