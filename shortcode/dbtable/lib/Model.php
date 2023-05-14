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
			case 'revise':
				$this->revision();
				break;
		}
	}

	//単語登録
	private function registration() {
		//登録単語の重複チェック
		$pre_sql = 'SELECT word FROM words WHERE word = "'.$_POST['word'].'"';
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
			$sql = 'INSERT INTO words (word, part_of_speach,japanese,part_of_speach2,japanese2,category,memo,next_date,correct,updated) VALUES (:word, :part_of_speach, :japanese, :part_of_speach2, :japanese2, :category, :memo, :next_date, :correct, :updated)';

			$stmt = $this->_db->prepare($sql);

			$stmt->execute([
				':word' => $_POST['word'],
				':part_of_speach' => $_POST['part_of_speach'],
				':japanese' => $_POST['japanese'],
				':part_of_speach2' => $_POST['part_of_speach2'],
				':japanese2' => $_POST['japanese2'],
				':category' => $_POST['category'],
				':memo' => $_POST['memo'],
				':next_date' => date('Y-m-d'),
				':correct' => 0,
				':updated' => date('Y-m-d')
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
	private function revision() {
		$sql = 'UPDATE words SET word=:word, part_of_speach=:part_of_speach,japanese=:japanese,part_of_speach2=:part_of_speach2,japanese2=:japanese2,category=:category,memo=:memo WHERE id =:id';
		// ,next_date=:next_date,correct=:correct 

		$stmt = $this->_db->prepare($sql);

		$stmt->execute([
			':word' => $_POST['word'],
			':part_of_speach' => $_POST['part_of_speach'],
			':japanese' => $_POST['japanese'],
			':part_of_speach2' => $_POST['part_of_speach2'],
			':japanese2' => $_POST['japanese2'],
			':category' => $_POST['category'],
			':memo' => $_POST['memo'],
			// ':next_date' => date('Y-m-d'),
			// ':correct' => 0,
			':id' => $_POST['wordId']
		]);

		$stmt = null;
	}

}