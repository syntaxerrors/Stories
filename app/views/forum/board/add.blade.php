{{ Form::open() }}
	<div class="row-fluid">
		<small>
			<ul class="breadcrumb">
				<li>{{ HTML::link('forum', 'Forums') }} <span class="divider">/</span></li>
				@if ($category != null)
					<li>{{ HTML::link('forum/category/view/'. $category->uniqueId, $category->name) }} <span class="divider">/</span></li>
				@endif
				<li class="active">Add Board</li>
			</ul>
		</small>
		<div class="offset3 span6">
			<div class="well text-center">
				<div class="well-title">Add new forum board</div>
				<div class="control-group">
					<div class="controls">
						{{ Form::select('forum_category_id', $categories, ($category != null ? array($category->id) : null)) }}
					</div>
				</div>
				<div class="control-group">
					<div class="controls">
						{{ Form::text('name', null, array('placeholder' => 'Name')) }}
					</div>
				</div>
				<div class="control-group">
					<div class="controls">
						{{ Form::select('forum_board_type_id', $types, array(1), array('onChange' => 'isChild(this)')) }}
					</div>
				</div>
				<div class="control-group">
					<div class="controls">
						{{ Form::select('parent_id', $boards, array(), array('id' => 'child', 'style' => 'display: none;')) }}
					</div>
				</div>
				<div class="control-group">
					<div class="controls">
						{{ Form::textarea('description', null, array('placeholder' => 'Description')) }}
					</div>
				</div>
				<div class="controls">
					{{ Form::submit('Add Board', array('class' => 'btn btn-small btn-primary')) }}
				</div>
			</div>
		</div>
	</div>
{{ Form::close() }}
<script type="text/javascript">
	function isChild(object) {
		if ($(object).val() == 2) {
			$('#child').css('display', 'inline');
		} else {
			$('#child').css('display', 'none');
		}
	}
</script>