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
     * プロジェクト削除(AJAX)
     */
    public function delete() {
    	if ($this->request->is('ajax')) {
    		$this->autoRender = false;
    		$this->autoLayout = false;
    		$id = (int)$this->request->data('id');
    		if ($this->Project->delete($id)) {
    			$response = array('id' => $id);
    		} else {
    			$response = array('id' => null);
    		}
    		$this->header('Content-Type: application/json');
    		echo json_encode($response);
    	} else {
    		throw new MethodNotAllowedException();
    	}
    }
}