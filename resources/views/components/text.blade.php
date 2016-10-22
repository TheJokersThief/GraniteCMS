<div class="form-group {{ (isset($field['class']) ? $field['class'] : '') }}">
	{{ \Form::label($field['name'], $field['title'], ['class' => 'control-label col-md-1 col-sm-2 col-xs-12']) }}
	<div class="col-md-11 col-sm-10 col-xs-12">
		{{ \Form::text($field['name'], null, ['class' => 'col-sm-12 col-xs-12', 'placeholder' => (isset($field['placeholder']) ? $field['placeholder'] : '') ]) }}
	</div>
</div>