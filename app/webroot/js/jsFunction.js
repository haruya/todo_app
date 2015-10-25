$(function() {
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
					name.removeClass('done');
				} else {
					name.addClass('done');
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
		handle: '.drag',
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
			$(this).removeAttr('disabled');
		} else if (projectName.length >= 64) {
			$('#projectAddDialog #frmProjectName').after('<p id="projectNameErr" style="color: red">「プロジェクト名」は64文字以内で入力してください。</p>');
			error = true;
			$(this).removeAttr('disabled');
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
					alert('プロジェクト新規追加に失敗しました。');
				} else {
					var e = $(
						'<tr id="project_'+data+'" data-id="'+data+'">' +
						'<td><input type="checkbox" class="checkProject" /></td>' +
						'<td><span class="notyet"></span></td>' +
						'<td><span class="deleteProject">[削除]</span>&nbsp;<span class="drag">[drag]</span></td>' +
						'</tr>'
					);
					$('#projects').append(e).find('tr:last td:eq(1) span:first-child').text(projectName);
					$('#projectAddDialog').dialog('close');
					$('#frmProjectAdd').removeAttr('disabled');
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


	// プロジェクト(新規追加)dialog設定
	  $('#projectAddDialog').dialog({
		  autoOpen: false,
		  width: "400px",
		  height: "auto",
		  show: "drop",
		  hide: "drop",
		  modal: true,
		  close: function() {
			  $('#projectNameErr').remove();
			  $('#frmProjectName').val('');
		  }
	  });
});