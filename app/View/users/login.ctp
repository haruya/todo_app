<h1>タスク管理<h1>
<h2>ログイン</h2>

<?php echo $this->Form->create('User', array('action' => 'login', 'novalidate' => true)); ?>

<?php echo $this->Form->input('email'); ?>

<?php echo $this->Form->input('password'); ?>

<?php echo $this->Form->end('ログイン'); ?>