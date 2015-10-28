<div class="index">

<h2>プロジェクト一覧</h2>

<p><span id="addForm">新規追加</span></p>

<table id="projects">
<thead>
<tr>
<th>完了</th>
<th>プロジェクト名</th>
<th>操作</th>
</tr>
</thead>
<tbody>
<?php if (!empty($projects)) : ?>
<?php foreach($projects as $project) : ?>
<tr id="project_<?php echo h($project['Project']['id']); ?>" data-id="<?php echo h($project['Project']['id']); ?>">
<td><input type="checkbox" class="checkProject" <?php if ($project['Project']['status'] == 'done') : ?>checked="checked"<?php endif; ?> /></td>
<td>
<span class="<?php echo h($project['Project']['status']); ?>"><?php echo h($project['Project']['name']); ?></span>
</td>
<td>
<?php echo $this->Html->link('[タスク]', array('controller' => 'tasks', 'action' => 'index', $project['Project']['id']), array('class' => 'sosaLink')); ?>&nbsp;
<span <?php if ($project['Project']['status'] == 'notyet') : ?>class="editProject"<?php endif; ?>>[編集]</span>&nbsp;
<span class="deleteProject">[削除]</span>&nbsp;
<span class="projectDrag">[drag]</span>
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
<li><?php echo $this->Html->link('ログアウト', array('controller' => 'users', 'action' => 'logout')); ?></li>
</ul>

</div>
<!-- ui-dialog -->
<div id="projectAddDialog" title="プロジェクト新規追加">
<p>プロジェクト名</p>
<p><input type="text" id="frmProjectName" value="" /></p>
<p style="text-align: center"><input type="button" id="frmProjectAdd" value="追加" style="width: 100px" /></p>
</div>
<div id="projectEditDialog" title="プロジェクト編集">
<p>プロジェクト名</p>
<p>
<input type="text" id="frmProjectName" value="" />
<input type="hidden" id="frmProjectId" value="" />
</p>
<p style="text-align: center"><input type="button" id="frmProjectEdit" value="編集" style="width: 100px" /></p>
</div>