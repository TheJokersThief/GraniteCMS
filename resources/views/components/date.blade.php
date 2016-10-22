<div class="form-group {{ (isset($field['class']) ? $field['class'] : '') }}">
	{{ \Form::label($field['name'], $field['title'], ['class' => 'control-label col-md-2 col-sm-2 col-xs-12']) }}
	<div class="col-md-10 col-sm-10 col-xs-12">
		{{ \Form::date($field['name'], null, ['class' => 'form-control']) }}
	</div>
</div>