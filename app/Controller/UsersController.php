<?php

/**
 * Usersコントローラー
 */
class UsersController extends AppController {
	// 利用するモデルの定義
	public $uses = array('User');

	/**
	 * ログイン処理
	 */
	public function login() {
		// ログインしていた場合はindexに飛ばす
		if ($this->Auth->user() != null) {
			$this->redirect(array('action' => 'index'));
		}
		// ログイン処理後プロジェクト一覧へ
		if ($this->request->is('post')) {
			if ($this->Auth->login()) {
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->set('ログイン情報を正しく入力してください。');
			}
		}
	}

	/**
	 * プロジェクト一覧へ
	 */
	public function index() {
		// ログイン成功の場合プロジェクト一覧に飛ぶ
		$this->redirect(array('controller' => 'projects', 'action' => 'index'));
	}

	/**
	 * ログアウト処理
	 */
	public function logout() {
		$this->Auth->logout();
	}
}