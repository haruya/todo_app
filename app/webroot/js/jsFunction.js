$(function() {
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
});