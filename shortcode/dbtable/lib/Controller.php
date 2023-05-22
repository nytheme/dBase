<?php

require_once(__DIR__ . '/../config/config.php');

$dbtable = new \Dbtable\Model();

//POSTされたときだけ処理する
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	try {
		// $reviewer->post();
		$res = $dbtable->post();
		//「json形式のデータ」と指定しTodo.phpからの返り値をヘッダーに格納
		header('Content-Type: application/json');
		echo json_encode($res);
		exit;
	} catch (Exception $e) {
		//SERVER_PROTOCOL で HTTP/1.0 や HTTP/1.1 を返す
		header($_SERVER['SERVER_PROTOCOL'] . '500 Internal Server Error' , true, 500);
		echo $e->getMessage();
		exit;
	}
}