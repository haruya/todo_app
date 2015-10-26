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
				'message' => '* 64文字以内で入力してください。'
			),
		),
	);

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
