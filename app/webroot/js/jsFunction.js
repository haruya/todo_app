$(function() {

	/********************************************************* プロジェクト */
	// プロジェクトstatus変更処理
	$(document).on('click', '.checkProject', function() {
		var id = $(this).parent().parent().data('id');
		var name = $(this).parent().next().children();
		$.ajax({
			type: "POST",
			url: "/todo_app/projects/checkProject/",
			dataType: "json",
			data: {
				id: id
			}
		}).done(function(data) {
			if (data) {
				if (name.hasClass('done')) {
					name.removeClass('done').parent().next().children('span:eq(0)').addClass('editProject');
				} else {
					name.addClass('done').parent().next().children('span:eq(0)').removeClass('editProject');
				}
			} else {
				alert('ステータス変更に失敗しました。');
			}
		}).fail(function() {
			alert('通信失敗');
		});
	});

	// プロジェクト並び順変更処理
	$('#projects tbody').sortable({
		axis: 'y',
		opacity: 0.4,
		handle: '.projectDrag',
		update: function() {
			$.ajax({
				type: "POST",
				url: "/todo_app/projects/sortProject/",
				dataType: "json",
				data: {
					project: $(this).sortable('serialize')
				}
			}).done(function(data) {
				if (!data) {
					alert("並び替えに失敗しました。");
				}
			}).fail(function() {
				alert("通信失敗");
			});
		}
	});

	// プロジェクト削除処理
	$(document).on('click', '.deleteProject', function() {
		if (confirm('本当に削除しますか？')) {
			var id = $(this).parent().parent().data('id');
			$.ajax({
				type: "POST",
				url: "/todo_app/projects/delete/",
				dataType: "json",
				data: {
					id: id
				}
			}).done(function(data) {
				if (data.id != null) {
					$('#project_'+data.id).fadeOut(800, function() {
						alert('プロジェクトを削除しました。');
					});

				} else {
					alert('プロジェクト削除に失敗しました。');
				}
			}).fail(function() {
				alert('通信失敗');
			});
		}
	});

	// プロジェクト新規追加処理
	$('#frmProjectAdd').click(function() {
		$(this).attr('disabled', 'disabled');
		$('#projectNameErr').remove();
		var error = false;
		var projectName = $('#projectAddDialog #frmProjectName').val();
		if (projectName.length == 0) {
			$('#projectAddDialog #frmProjectName').after('<p id="projectNameErr" style="color: red">「プロジェクト名」は入力必須です。</p>');
			error = true;
		} else if (projectName.length > 64) {
			$('#projectAddDialog #frmProjectName').after('<p id="projectNameErr" style="color: red">「プロジェクト名」は64文字以内で入力してください。</p>');
			error = true;
		}
		if (error == false) {
			$.ajax({
				type: "POST",
				url: "/todo_app/projects/add/",
				dataType: "json",
				data: {
					name: projectName
				}
			}).done(function(data) {
				if (!data) {
					$('#projectAddDialog').dialog('close');
					alert('プロジェクト新規追加に失敗しました。');
				} else {
					var e = $(
						'<tr id="project_'+data+'" data-id="'+data+'">' +
						'<td><input type="checkbox" class="checkProject" /></td>' +
						'<td><span class="notyet"></span></td>' +
						'<td><a href="/todo_app/tasks?id='+data+'" class="taskLink">[タスク]</a>&nbsp;<span class="editProject">[編集]</span>&nbsp;<span class="deleteProject">[削除]</span>&nbsp;<span class="projectDrag">[drag]</span></td>' +
						'</tr>'
					);
					$('#projects').append(e).find('tr:last td:eq(1) span:first-child').text(projectName);
					$('#projectAddDialog').dialog('close');
				}
			}).fail(function() {
				alert('通信失敗');
			});
		}
		$(this).removeAttr('disabled');
	});

	// プロジェクト編集処理
	$('#frmProjectEdit').click(function() {
		$(this).attr('disabled', 'disabled');
		$('#projectNameErr').remove();
		var error = false;
		var projectId = $('#projectEditDialog #frmProjectId').val();
		var projectName = $('#projectEditDialog #frmProjectName').val();
		if (projectName.length == 0) {
			$('#projectEditDialog #frmProjectName').after('<p id="projectNameErr" style="color: red">「プロジェクト名」は入力必須です。</p>');
			error = true;
		} else if (projectName.length > 64) {
			$('#projectEditDialog #frmProjectName').after('<p id="projectNameErr" style="color: red">「プロジェクト名」は64文字以内で入力してください。</p>');
			error = true;
		}
		if (error == false) {
			$.ajax({
				type: "POST",
				url: "/todo_app/projects/edit/",
				dataType: "json",
				data: {
					id: projectId,
					name: projectName
				}
			}).done(function(data) {
				if (!data) {
					$('#projectEditDialog').dialog('close');
					alert('プロジェクト編集に失敗しました。');
				} else {
					$('#project_'+projectId).append().find('td:eq(1) span:first-child').text(projectName);
					$('#projectEditDialog').dialog('close');
				}
			}).fail(function() {
				alert('通信失敗');
			});
		}
		$(this).removeAttr('disabled');
	});

	// プロジェクトダイアログ(新規追加)オープン
	$('#addForm').click(function() {
		$('#projectAddDialog').dialog('open');
	});

	// プロジェクトダイアログ(編集)オープン
	$(document).on('click', '.editProject', function() {
		var projectId = $(this).parent().parent().data('id');
		var projectName = $(this).parent().prev().children('span:first-child').text();
		$('#projectEditDialog #frmProjectName').val(projectName);
		$('#projectEditDialog #frmProjectId').val(projectId);
		$('#projectEditDialog').dialog('open');
	});


	// プロジェクト(新規、編集)dialog設定
	  $('#projectAddDialog, #projectEditDialog').dialog({
		  autoOpen: false,
		  width: "400px",
		  height: "auto",
		  show: "drop",
		  hide: "drop",
		  modal: true,
		  close: function() {
			  $('#projectNameErr').remove();
			  $('#frmProjectName').val('');
			  $('#frmProjectId').val('');
		  }
	  });
		/********************************************************* タスク */
		// タスクstatus変更処理
		$(document).on('change', '.checkTask', function() {
			var status = $(this).val();
			var id = $(this).parent().parent().data('id');
			$.ajax({
				type: "POST",
				url: "/todo_app/tasks/checkTask/",
				dataType: "json",
				data: {
					status: status,
					id: id
				}
			}).done(function(data) {
				if (data) {
					$('#task_' + id).removeClass().addClass(status);
				} else {
					alert('ステータス変更に失敗しました。');
				}
			}).fail(function() {
				alert('通信失敗');
			});
		});

		// タスク並び順変更処理
		$('#tasks tbody').sortable({
			axis: 'y',
			opacity: 0.4,
			handle: '.taskDrag',
			update: function() {
				$.ajax({
					type: "POST",
					url: "/todo_app/tasks/sortTask/",
					dataType: "json",
					data: {
						task: $(this).sortable('serialize'),
						projectId: $('#projectId').val()
					}
				}).done(function(data) {
					if (!data) {
						alert("並び替えに失敗しました。");
					}
				}).fail(function() {
					alert("通信失敗");
				});
			}
		});

		// タスク削除処理
		$(document).on('click', '.deleteTask', function() {
			if (confirm('本当に削除しますか？')) {
				var id = $(this).parent().parent().data('id');
				$.ajax({
					type: "POST",
					url: "/todo_app/tasks/delete/",
					dataType: "json",
					data: {
						id: id
					}
				}).done(function(data) {
					if (data.id != null) {
						$('#task_'+data.id).fadeOut(800, function() {
							alert('プロジェクトを削除しました。');
						});

					} else {
						alert('プロジェクト削除に失敗しました。');
					}
				}).fail(function() {
					alert('通信失敗');
				});
			}
		});
});