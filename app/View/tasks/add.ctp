<div class="form">

<h2>
<?php echo h($project['Project']['name']); ?>のタスク新規追加
<input type="hidden" id="projectId" value ="<?php echo h($project['Project']['id']); ?>" />
</h2>

<?php echo $this->Form->create('Task', array('action' => 'add', 'novalidate' => true)); ?>
<p>
<?php echo $this->Form->label('Task.worker', '作業者'); ?>
<?php echo $this->Form->input('worker', array('label' => false)); ?>
</p>

<p>
<?php echo $this->Form->label('Task.priority', '優先度'); ?>
<?php echo $this->Form->input('proority', array('label' => false, 'options' => $priorities)); ?>
</p>


<?php echo $this->Form->end('追加'); ?>

</div>

<div class="actions">

<h3>メニュー</h3>
<ul>
<li><?php echo $this->Html->link('プロジェクト一覧', array('controller' => 'projects', 'action' => 'index')); ?></li>
<li><?php echo $this->Html->link('タスク一覧', array('action' => 'index', $project['Project']['id'])); ?></li>
<li><?php echo $this->Html->link('ログアウト', array('controller' => 'users', 'action' => 'logout')); ?></li>
</ul>

</div>