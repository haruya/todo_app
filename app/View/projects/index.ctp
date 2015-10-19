<div class="index">

<h2>プロジェクト一覧</h2>

<?php if (!empty($projects)) : ?>

<table id="projects">
<thead>
<tr>
<th>完了</th>
<th>プロジェクト名</th>
<th>操作</th>
</tr>
</thead>
<tbody>
<?php foreach($projects as $project) : ?>
<tr id="project_<?php echo h($project['Project']['id']); ?>" data-id="<?php echo h($project['Project']['id']); ?>">
<td><input type="checkbox" class="checkProject" <?php if ($project['Project']['status'] == 'done') : ?>checked="checked"<?php endif; ?> /></td>
<td><?php echo h($project['Project']['name']); ?></td>
<td>
<span class="deleteProject">[削除]</span>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

<?php else : ?>

<p>現在プロジェクトは存在しません。</p>

<?php endif; ?>

</div>

<div class="actions">

<h3>メニュー</h3>
<ul>
<li><?php echo $this->Html->link('ログアウト', array('controler' => 'users', 'action' => 'logout')); ?></li>
</ul>

</div>