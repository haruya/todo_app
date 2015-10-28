<div class="form">

<h2>
<?php echo $projectName; ?>のタスク編集

</h2>

<?php echo $this->Form->create('Task', array('novalidate' => true)); ?>
<table>
<tr>
<th><?php echo $this->Form->label('Task.worker', '作業者'); ?></th>
<td><?php echo $this->Form->input('worker', array('label' => false, 'div' => false)); ?></td>
</tr>
<tr>
<th><?php echo $this->Form->label('Task.priority', '優先度<span class="req"> *</span>'); ?></th>
<td>
<?php echo $this->Form->input('priority', array(
	'type' => 'radio',
	'legend' => false,
	'value' => 0,
	'options' => $priorities
)); ?>
</td>
</tr>
<tr>
<th><?php echo $this->Form->label('Task.title', 'タイトル<span class="req"> *</span>'); ?></th>
<td><?php echo $this->Form->input('title', array('label' => false, 'div' => false)); ?></td>
</tr>
<tr>
<th><?php echo $this->Form->label('Task.content', '内容'); ?></th>
<td><?php echo $this->Form->input('content', array('label' => false, 'div' => false)); ?></td>
</tr>
<tr>
<th><?php echo $this->Form->label('Task.remarks', '備考'); ?></th>
<td><?php echo $this->Form->input('remarks', array('label' => false, 'div' => false)); ?></td>
</tr>
<tr>
<th><?php echo $this->Form->label('Task.start_date', '作業開始日'); ?></th>
<td>
<?php echo $this->Form->input('start_date', array(
	'label' => false,
	'div' => false,
	'type' => 'text',
	'id' => 'frmStartDate',
	'style' => 'width: 80%'
)); ?>
</td>
</tr>
<tr>
<th><?php echo $this->Form->label('Task.end_date', '作業完了日'); ?></th>
<td>
<?php echo $this->Form->input('end_date', array(
	'label' => false,
	'div' => false,
	'type' => 'text',
	'id' => 'frmEndDate',
	'style' => 'width: 80%'
)); ?>
</td>
</tr>
</table>

<?php echo $this->Form->input('id', array('type' => 'hidden', 'value' => $this->data['Task']['id'])); ?>
<?php echo $this->Form->input('project_id', array('type' => 'hidden', 'value' => $this->data['Task']['project_id'])); ?>
<?php echo $this->Form->input('seq', array('type' => 'hidden', 'value' => $this->data['Task']['seq'])); ?>
<?php echo $this->Form->input('status', array('type' => 'hidden', 'value' => $this->data['Task']['status'])); ?>
<?php echo $this->Form->input('project_name', array('type' => 'hidden', 'value' => $projectName)); ?>
<?php echo $this->Form->end('追加'); ?>

</div>

<div class="actions">

<h3>メニュー</h3>
<ul>
<li><?php echo $this->Html->link('プロジェクト一覧', array('controller' => 'projects', 'action' => 'index')); ?></li>
<li><?php echo $this->Html->link('タスク一覧', array('action' => 'index', $this->data['Task']['project_id'])); ?></li>
<li><?php echo $this->Html->link('ログアウト', array('controller' => 'users', 'action' => 'logout')); ?></li>
</ul>

</div>