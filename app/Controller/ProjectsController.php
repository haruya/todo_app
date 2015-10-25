<?php

/**
 * Projectsコントローラー
 */
class ProjectsController extends AppController {
    // 利用するモデルの定義
    public $uses = array('Project');

    /**
     * プロジェクト一覧
     */
    public function index() {
    	// プロジェクト一覧取得
    	$projects = $this->Project->find('all', array(
    		'order' => array('Project.seq'),
    	));
    	//var_dump($projects);exit;
    	$this->set('projects', $projects);
    }

    /**
     * プロジェクトステータス変更処理
     */
    public function checkProject() {
    	if ($this->request->is('ajax')) {
    		$this->autoRender = false;
    		$this->autoLayout = false;
    		$id = (int)$this->request->data('id');
    		$data = $this->Project->editStatus($id);
    		$this->header('Content-Type: application/json');
    		echo json_encode($data);
    	} else {
    		throw new MethodNotAllowedException();
    	}
    }

    /**
     * プロジェクト並び順変更処理
     */
    public function sortProject() {
    	if ($this->request->is('ajax')) {
    		$this->autoRender = false;
    		$this->autoLayout = false;
    		parse_str($this->request->data('project'));
    		$param = $this->Project->editSort($project);
    		header('Content-Type: application/json; charset=utf-8');
    		echo json_encode($param);
    	} else {
    		throw new MethodNotAllowedException();
    	}
    }

    /**
     * プロジェクト新規追加処理
     */
    public function add() {
    	if ($this->request->is('ajax')) {
    		$this->autoRender = false;
    		$this->autoLayout = false;
    		$name = $this->request->data('name');
			$param = $this->Project->projectInsert($name);
			$this->header('Content-Type: application/json');
			echo json_encode($param);
    	} else {
    		throw new MethodNotAllowedException();
    	}
    }

    /**
     * プロジェクト削除処理
     */
    public function delete() {
    	if ($this->request->is('ajax')) {
    		$this->autoRender = false;
    		$this->autoLayout = false;
    		$id = (int)$this->request->data('id');
    		$transaction = $this->Project->begin();
    		if ($this->Project->delete($id)) {
    			$this->Project->commit($transaction);
    			$response = array('id' => $id);
    		} else {
    			$this->Project->rollback($transaction);
    			$response = array('id' => null);
    		}
    		$this->header('Content-Type: application/json');
    		echo json_encode($response);
    	} else {
    		throw new MethodNotAllowedException();
    	}
    }
}