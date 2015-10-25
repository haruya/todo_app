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

	// プロジェクトの新規追加し追加したIDを返す
	public function projectInsert($projectName) {
		$selectSql = "
			SELECT
				MAX(seq) + 1 as maxSeq
			FROM
				projects as Project
		";
		$data = $this->query($selectSql);
		if ($data[0][0]['maxSeq'] != null) {
			$seq = $data[0][0]['maxSeq'];
		} else {
			$seq = 0;
		}
		$dataSource = $this->getDataSource();
		try {
			$dataSource->begin();
			$params = array(
				'Project' => array(
				'name' => $projectName,
				'seq' => $seq
				)
			);
			if (!$this->save($params)) {
				throw new Exception();
			}
			$dataSource->commit();
			return $this->getLastInsertID();
		} catch (Exception $e) {
			$dataSource->rollback();
			return false;
		}


	}

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