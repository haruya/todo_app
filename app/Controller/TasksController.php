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
			$this->redirect(array('controller' => 'projects', 'action' => 'index'));
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
		if (!$project) {
			throw new NotFoundException();
		}
		if ($this->request->is('post')) {
			// 登録用データの生成
			$data['Task']['project_id'] = $this->request->data['Task']['project_id'];
			$data['Task']['title'] = $this->request->data['Task']['title'];
			$data['Task']['content'] = $this->request->data['Task']['content'];
			$data['Task']['remarks'] = $this->request->data['Task']['remarks'];
			$data['Task']['seq'] = $this->Task->maxSeq($data['Task']['project_id']);
			$data['Task']['priority'] = $this->request->data['Task']['priority'];
			$data['Task']['worker'] = $this->request->data['Task']['worker'];
			$data['Task']['start_date'] = $this->request->data['Task']['start_date'];
			$data['Task']['end_date'] = $this->request->data['Task']['end_date'];
			if ($this->Task->save($data)) {
				$this->Flash->set('タスクを新規追加しました。');
				$this->redirect(array('action' => 'index', $project['Project']['id']));
			} else {
				$this->Flash->set('タスクの新規追加に失敗しました。');
			}
		}
		$this->set('project', $project);
	}

	/**
	 * タスク編集
	 */
	public function edit($id = null) {
		if ($this->request->is('post')) {
			// 登録用データの生成
			$data['Task']['id'] = $this->request->data['Task']['id'];
			$data['Task']['project_id'] = $this->request->data['Task']['project_id'];
			$data['Task']['title'] = $this->request->data['Task']['title'];
			$data['Task']['content'] = $this->request->data['Task']['content'];
			$data['Task']['remarks'] = $this->request->data['Task']['remarks'];
			$data['Task']['seq'] = $this->request->data['Task']['seq'];
			$data['Task']['status'] = $this->request->data['Task']['status'];
			$data['Task']['priority'] = $this->request->data['Task']['priority'];
			$data['Task']['worker'] = $this->request->data['Task']['worker'];
			$data['Task']['start_date'] = $this->request->data['Task']['start_date'];
			$data['Task']['end_date'] = $this->request->data['Task']['end_date'];
			if ($this->Task->save($data)) {
				$this->Flash->set('タスクを編集しました。');
				$this->redirect(array('action' => 'index', $data['Task']['project_id']));
			} else {
				$this->Flash->set('タスクの新規追加に失敗しました。');
			}
		} else {
			$this->request->data = $this->Task->findById($id);
			if (empty($this->request->data)) {
				$this->Flash->set('タスクが見つかりませんでした。');
				$this->redirect(array('controller' => 'projects', 'action' => 'index'));
			}
		}
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