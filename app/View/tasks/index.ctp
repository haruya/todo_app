<div class="index">

<h2>
<?php echo h($project['Project']['name']); ?>のタスク一覧
<input type="hidden" id="projectId" value ="<?php echo h($project['Project']['id']); ?>" />
</h2>

<table id="tasks">
<thead>
<tr>
<th>ステータス</th>
<th>登録日</th>
<th>作業開始日</th>
<th>作業完了日</th>
<th>作業者</th>
<th>優先度</th>
<th>タイトル</th>
<th>内容</th>
<th>備考</th>
<th>操作</th>
</tr>
</thead>
<tbody>
<?php if (!empty($tasks)) : ?>
<?php foreach($tasks as $task) : ?>
<tr id="task_<?php echo h($task['Task']['id']); ?>" class="<?php echo h($task['Task']['status']); ?>" data-id="<?php echo h($task['Task']['id']); ?>">
<td>
<select class="checkTask">
<option value="before_work" <?php if ($task['Task']['status'] == 'before_work') : ?>selected="selected"<?php endif; ?>>作業前</option>
<option value="working" <?php if ($task['Task']['status'] == 'working') : ?>selected="selected"<?php endif; ?>>作業中</option>
<option value="after_work" <?php if ($task['Task']['status'] == 'after_work') : ?>selected="selected"<?php endif; ?>>作業後</option>
</select>
</td>
<td><?php echo $this->Time->format($task['Task']['created'], '%Y-%m-%d'); ?></td>
<td><?php echo $this->Time->format($task['Task']['start_date'], '%Y-%m-%d'); ?></td>
<td><?php echo $this->Time->format($task['Task']['end_date'], '%Y-%m-%d'); ?></td>
<td><?php echo h($task['Task']['worker']); ?></td>
<td>
<?php if ($task['Task']['priority'] == 0) : ?>
高
<?php elseif ($task['Task']['priority'] == 1) : ?>
中
<?php elseif ($task['Task']['priority'] == 2) : ?>
低
<?php endif; ?>
</td>
<td><?php echo h($task['Task']['title']); ?></td>
<td><?php echo h($task['Task']['content']); ?></td>
<td><?php echo h($task['Task']['remarks']); ?></td>
<td>
<?php echo $this->Html->link('[編集]', array('action' => 'edit', $task['Task']['id']), array('class' => 'sosaLink')); ?>&nbsp;
<span class="deleteTask">[削除]</span>&nbsp;
<span class="taskDrag">[drag]</span>
</td>
</tr>
<?php endforeach; ?>
<?php endif; ?>
</tbody>
</table>

</div>

<div class="actions">

<h3>メニュー</h3>
<ul>
<li><?php echo $this->Html->link('プロジェクト一覧', array('controller' => 'projects', 'action' => 'index')); ?></li>
<li><?php echo $this->Html->link('タスク追加', array('action' => 'add', $project['Project']['id'])); ?></li>
<li><?php echo $this->Html->link('ログアウト', array('controller' => 'users', 'action' => 'logout')); ?></li>
</ul>

</div>