<?php

/**
 * Tasksコントローラー
 */
class TasksController extends AppController {
	// 利用するモデルの定義
	public $uses = array('Task', 'Project');

	// beforeRenderコールバック
	public function beforeRender() {
		$this->set('priorities', array('高', '中', '低'));
	}

	/**
	 * タスク一覧
	 */
	public function index($projectId = null) {
		$project = $this->projectCheck($projectId);
		if (!$project) {
			throw new NotFoundException();
		}
		$tasks = $this->Task->find('all', array(
			'conditions' => array('Task.project_id' => $projectId),
			'order' => array('Task.seq' => 'ASC')
		));

		$this->set('project', $project);
		$this->set('tasks', $tasks);
	}

	/**
	 * タスク新規追加
	 */
	public function add($projectId = null) {
		$project = $this->projectCheck($projectId);
		if ($this->request->is('post')) {
			$transaction = $this->Task->begin();
			if ($this->Task->save($this->request->data)) {
				$this->Task->commit($transaction);
				$this->Flash->set('タスクを新規追加しました。');
				$this->redirect(array('action' => 'index', $project['Project']['id']));
			} else {
				$this->Task->rollback($transaction);
				$this->Flash->set('タスクの新規追加に失敗しました。');
			}
		}
		$this->set('project', $project);
	}

	/**
	 * タスクステータス変更処理
	 */
	public function checkTask() {
		if ($this->request->is('ajax')) {
			$this->autoRender = false;
			$this->autoLayout = false;
			$status = $this->request->data('status');
			$id = (int)$this->request->data('id');
			$data = $this->Task->editStatus($status, $id);
			$this->header('Content-Type: application/json');
			echo json_encode($data);
		} else {
			throw new MethodNotAllowedException();
		}
	}

	/**
	 * タスク並び順変更処理
	 */
	public function sortTask() {
		if ($this->request->is('ajax')) {
			$this->autoRender = false;
			$this->autoLayout = false;
			$projectId = $this->request->data('projectId');
			parse_str($this->request->data('task'));
			$param = $this->Task->editSort($projectId, $task);
			header('Content-Type: application/json; charset=utf-8');
			echo json_encode($param);
		} else {
			throw new MethodNotAllowedException();
		}
	}

	/**
	 * タスク削除処理
	 */
	public function delete() {
		if ($this->request->is('ajax')) {
			$this->autoRender = false;
			$this->autoLayout = false;
			$id = (int)$this->request->data('id');
			$transaction = $this->Task->begin();
			if ($this->Task->delete($id)) {
				$this->Task->commit($transaction);
				$response = array('id' => $id);
			} else {
				$this->Task->rollback($transaction);
				$response = array('id' => null);
			}
			$this->header('Content-Type: application/json');
			echo json_encode($response);
		} else {
			throw new MethodNotAllowedException();
		}
	}

	/**
	 * プロジェクトの存在チェック(存在した場合プロジェクトの情報を返す)
	 */
	private function projectCheck($projectId) {
		$project = $this->Project->findById($projectId);
		if (empty($project)) {
			return false;
		} else {
			return $project;
		}
	}
}