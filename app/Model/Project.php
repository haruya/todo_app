<?php

/**
 * Projectモデル
 */
class Project extends AppModel {
	// バリデーション
	public $validate = array(
		'name' => array(
			array(
				'rule' => 'notBlank',
				'message' => '* プロジェクト名は入力必須です。'
			),
			array(
				'rule' => array('maxLength', 64),
				'message' => '* 64文字以内で入力してください。'
			),
		),
	);

	// プロジェクトのステータス変更のSQL実行
	public function editStatus($id) {
		$dataSource = $this->getDataSource();
		$dataSource->begin();
		$sql = "
			UPDATE
				projects
			SET
				status = (
					CASE
						WHEN status = 'done' THEN 'notyet'
						ELSE 'done'
					END
				)
			WHERE
				id = :id
		";
		$params = array(
			'id' => $id
		);
		$data = $this->query($sql, $params);
		if ($data === false) {
			$dataSource->rollback();
			return false;
		} else {
			$dataSource->commit();
			return true;
		}
	}

	// プロジェクトの並び順変更のSQL実行
	public function editSort($project) {
		$error = false;
		$dataSource = $this->getDataSource();
		$dataSource->begin();
		foreach ($project as $key => $val) {
			$params = array();
			$sql = "
				UPDATE
					projects
				SET
					seq = :seq
				WHERE
					id = :id
			";
			$params = array(
				'seq' => $key,
				'id' => $val
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