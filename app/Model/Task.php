<?php

/**
 * Taskモデル
 */
class Task extends AppModel {
	// テーブル同士の関係性を定義
	public $belongsTo = array(
		'Project'
	);

	// バリデーション
	public $validate = array(
		'title' => array(
			array(
				'rule' => 'notBlank',
				'message' => '* タイトルは入力必須です。'
			),
			array(
				'rule' => array('maxLength', 64),
				'message' => 'タイトルは64文字以内で入力してください。'
			),
		),
		'worker' => array(
			array(
				'rule' => array('workerCheck'),
				'message' => '作業者は32文字以内で入力してください。'
			),
		),
		'start_date' => array(
			array(
				'rule' => array('dateFormatCheck', 0),
				'message' => '作業開始日を正しい形式で入力してください。'
			),
			array(
				'rule' => array('dateCheck', 0),
				'message' => '作業開始日を正しい日付で入力してください。'
			),
		),
		'end_date' => array(
			array(
				'rule' => array('dateFormatCheck', 1),
				'message' => '作業終了日を正しい形式で入力してください。'
			),
			array(
				'rule' => array('dateCheck', 1),
				'message' => '作業完了日を正しい日付で入力してください。'
			),
		),
	);

	// バリデーション関数(作業者が空か、32文字以内かの判別)
	public function workerCheck($check) {
		if ($check['worker'] != '' && mb_strlen($check['worker'], "UTF-8") > 32) {
			return false;
		} else {
			return true;
		}
	}

	// バリデーション関数(日付が空か、「数値4」-「数値2」-「数値2」(2012-01-01)の書式の判別)
	public function dateFormatCheck($check, $flag) {
		if ($flag == 0) {
		 	$target = $check['start_date'];
		} else {
			$target = $check['end_date'];
		}
		if ($target != '' && !preg_match('/^([1-9][0-9]{3})\-(0[1-9]{1}|1[0-2]{1})\-(0[1-9]{1}|[1-2]{1}[0-9]{1}|3[0-1]{1})$/', $target)) {
			return false;
		} else {
			return true;
		}
	}

	// バリデーション関数(日付が空か、正しい日付かの判別)
	public function dateCheck($check, $flag) {
		if ($flag == 0) {
			$target = $check['start_date'];
		} else {
			$target = $check['end_date'];
		}
		if ($target != '') {
			// 正しい日付かチェック
			$year = mb_substr($target, 0, 4);
			$month = mb_substr($target, 5, 2);
			$day = mb_substr($target, -2, 2);
			$targetDateCheck = checkdate((int)$month, (int)$day, (int)$year);
			if ($targetDateCheck) {
				return true;
			} else {
				return false;
			}
		} else {
			return true;
		}
	}

	// 新規追加の際に必要な「seq」の値の取得
	public function maxSeq($projectId) {
		$sql = "
			SELECT
				MAX(seq) + 1 as maxSeq
			FROM
				tasks
			WHERE
				project_id = :project_id
		";
		$params = array(
			'project_id' => $projectId
		);
		$data = $this->query($sql, $params);
		if ($data[0][0]['maxSeq'] == null) {
			return 0;
		} else {
			return $data[0][0]['maxSeq'];
		}
	}

	// タスクのステータス変更のSQL実行
	public function editStatus($status, $id) {
		$error = false;
		$dataSource = $this->getDataSource();
		try {
			$dataSource->begin();
			$params = array(
				'Task' => array(
					'id' => $id,
					'status' => $status
				)
			);
			$fields = array('status');
			if (!$this->save($params, false, $fields)) {
				throw new Exception();
			}
			$dataSource->commit();
			return true;
		} catch (Exception $e) {
			$dataSource->rollback();
			return false;
		}

	}

	// タスクの並び順変更のSQL実行
	public function editSort($projectId, $task) {
		$error = false;
		$dataSource = $this->getDataSource();
		$dataSource->begin();
		foreach ($task as $key => $val) {
			$params = array();
			$sql = "
				UPDATE
					tasks
				SET
					seq = :seq
				WHERE
					id = :id
				AND
					project_id = :project_id
			";
			$params = array(
					'seq' => $key,
					'id' => $val,
					'project_id' => $projectId
			);
			$data = $this->query($sql, $params);
			if ($data === false) {
				$error = true;
				break;
			}
		}
		if ($error) {
			$dataSource->rollback();
			return false;
		} else {
			$dataSource->commit();
			return true;
		}
	}


}
