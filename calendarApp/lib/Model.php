<?php

namespace Db_log;
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
		$stmt = $this->_db->query("SELECT * from db_log ORDER BY date_time ASC");

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
			case 'delete':
				$this->delete();
				break;
			case 'comment':
				$this->comment();
				break;
			case 'New_check_and_text':
				$this->New_check_and_text();
				break;
			case 'Edit_check_and_text':
				$this->Edit_check_and_text();
				break;
		}
	}

	//新規登録
	private function registration() {
		// //登録名の重複チェック
		// $pre_sql = 'SELECT task FROM db_log WHERE name = "'.$_POST['name'].'"';
		// $pre_stmt = $this->_db->prepare($pre_sql);
		// $pre_stmt->execute();
		// $result = $pre_stmt->fetch();

		// if ($result > 0) {
		// 	 return [
		// 		 'message' => 'すでに登録しています',
		// 		 'msg' => 1
		// 	 ];

		// } else {
			//重複がなければ登録
			$sql = 'INSERT INTO db_log (date_time,category,task,date_time_task) VALUES (:date_time, :category, :task, :date_time_task)';

			$stmt = $this->_db->prepare($sql);

			$stmt->execute([
				':date_time'      => $_POST['date_time'],
				':category'       => $_POST['category'],
				':task'           => $_POST['task'],
				':date_time_task' => $_POST['date_time_task']
			]);

			$stmt = null;
			$_POST = array();
			unset($_POST);
			return [
				'message' => '登録しました',
				'msg' => 0
			];
		// }
	}

	//修正
	private function edit() {

		$sql = 'UPDATE db_log SET category=:category,task=:task,date_time_task=:date_time_task WHERE date_time=:date_time';

		$stmt = $this->_db->prepare($sql);

		$stmt->execute([
			':date_time'      => $_POST['date_time'],
			':category'       => $_POST['category'],
			':task'           => $_POST['task'],
			':date_time_task' => $_POST['date_time_task']
		
		]);

		$stmt = null;
		$_POST = array();
		unset($_POST);
		return [
			'message' => '変更しました',
			'msg' => 0
		];
	}

	//削除
	private function delete() {

		$sql = 'DELETE FROM db_log WHERE date_time=:date_time';

		$stmt = $this->_db->prepare($sql);

		$stmt->execute([
			':date_time' => $_POST['date_time']		
		]);

		$stmt = null;
		$_POST = array();
		unset($_POST);
		return [
			'message' => '削除しました',
			'msg' => 0
		];
	}

	//コメント追加
	private function comment() {

		$sql = 'UPDATE db_log SET comment=:comment WHERE date_time=:date_time';

		$stmt = $this->_db->prepare($sql);

		$stmt->execute([
			':date_time' => $_POST['date_time'],
			':comment'   => $_POST['comment']		
		]);

		$stmt = null;
		$_POST = array();
		unset($_POST);
		return [
			'message' => '変更しました',
			'msg' => 0
		];
	}

	//条件に該当するデータを全て表示
	public function getAllCheckbox_text($date) {

		$stmt = $this->_db->query("SELECT * from db_log_text WHERE date='" .$date. "' ORDER BY date ASC");
		//fetchAll(\PDO::FETCH_OBJ)でオブジェクト形式で返す
		return $stmt->fetchAll(\PDO::FETCH_OBJ);
	}

	//チェックボックス・テキストデータ新規作成
	private function New_check_and_text() {

		$sql = 'INSERT INTO db_log_text (date,check1) VALUES (:date,:check1)';

		$stmt = $this->_db->prepare($sql);

		$stmt->execute([
			':date'  => $_POST['date'],
		    ':check1'=> $_POST['check1'],		
			':text'  => $_POST['text']
		]);

		$stmt = null;
		$_POST = array();
		unset($_POST);
		return [
			'message' => '新規作成しました',
		];
	}
//チェックボックス・テキストデータ変更
	private function Edit_check_and_text() {

		$sql = 'UPDATE db_log_text SET check1=:check1, text=:text WHERE date=:date';

		if (!isset( $_POST['check1'])) {
			$check1 = '';
		} else {
			$check1 = $_POST['check1'];
		}
		$stmt = $this->_db->prepare($sql);
		$stmt->execute([
			':date' => $_POST['date'],
			':check1' => $check1,		
			':text' => $_POST['text']
		]);

		$stmt = null;
		$_POST = array();
		unset($_POST);
		return [
			'message' => '変更しました',
		];
	}

}