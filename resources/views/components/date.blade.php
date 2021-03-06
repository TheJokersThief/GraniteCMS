@if( isset($field['read_only']) && $field['read_only'] )
	<div class="form-group {{ (isset($field['class']) ? $field['class'] : '') }}">
		{{ \Form::label($field['name'], $field['title'], ['class' => 'control-label col-xs-12']) }}
		<div class="col-xs-12">
			{{ $value }}
		</div>
	</div>
@else
	<div class="form-group {{ (isset($field['class']) ? $field['class'] : '') }}">
		{{ \Form::label($field['name'], $field['title'], ['class' => 'control-label col-xs-12']) }}
		<div class="col-xs-12">
			{{ \Form::date($field['name'], $value, ['class' => 'form-control']) }}
		</div>
	</div>
@endif