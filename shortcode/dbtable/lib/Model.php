<?php

namespace Dbtable;
// use Reviewer\Model;

session_start();

class Model {
	protected $_db;

	//全関数で使える変数を設定
	public function __construct() {
		try {
			$this->_db = new \PDO(DSN, USER, PW);
			$this->_db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

		} catch (\PDOException $e) {
			echo $e->getMessage();
			exit;
		}
	}

	//条件に該当するデータを全て表示
	public function getAll() {
		$stmt = $this->_db->query("SELECT * from daw_plugins ORDER BY company ASC");

		//fetchAll(\PDO::FETCH_OBJ)でオブジェクト形式で返す
		return $stmt->fetchAll(\PDO::FETCH_OBJ);
	}


	//Controller.php で起動。
	//main.js でPOSTされたmodeの内容によって処理を分岐
	public function post() {
		switch ($_POST['mode']) {
			case 'register':
				$this->registration();
				break;
			case 'edit':
				$this->edit();
				break;
		}
	}

	//単語登録
	private function registration() {
		//登録単語の重複チェック
		$pre_sql = 'SELECT name FROM daw_plugins WHERE name = "'.$_POST['name'].'"';
		$pre_stmt = $this->_db->prepare($pre_sql);
		$pre_stmt->execute();
		$result = $pre_stmt->fetch();

		if ($result > 0) {
			 return [
				 'message' => 'すでに登録している単語です',
				 'msg' => 1
			 ];

		} else {
			//重複がなければ単語を登録
			$sql = 'INSERT INTO daw_plugins (company,name,category,memo) VALUES (:company, :name, :category, :memo)';

			$stmt = $this->_db->prepare($sql);

			$stmt->execute([
				':company'  => $_POST['company'],
				':name'     => $_POST['name'],
				':category' => $_POST['category'],
				':memo'     => $_POST['memo']
			]);

			$stmt = null;
			$_POST = array();
			unset($_POST);
			return [
				'message' => '登録しました',
				'msg' => 0
			];
		}
	}

	//単語修正
	private function edit() {

		$sql = 'UPDATE daw_plugins SET company=:company, name=:name,category=:category,memo=:memo WHERE name=:nameForSQL';

		$stmt = $this->_db->prepare($sql);

		$stmt->execute([
			':company' 	  => $_POST['company'],
			':name'       => $_POST['name'],
			':category'   => $_POST['category'],
			':memo'       => $_POST['memo'],
			':nameForSQL' => $_POST['nameForSQL']
			
		]);

		$stmt = null;
		$_POST = array();
		unset($_POST);
		return [
			'message' => '変更しました',
			'msg' => 0
		];
	}
}