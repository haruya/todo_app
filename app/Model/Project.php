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
		$affected = $this->getAffectedRows();
		if ($affected < 1) {
			return false;
		} else {
			return true;
		}
	}


}