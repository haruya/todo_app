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
}