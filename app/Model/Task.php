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
}
