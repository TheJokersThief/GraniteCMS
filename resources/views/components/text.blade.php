@if(isset($field['read_only']) && $field['read_only'])
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
			{{ \Form::text($field['name'], $value, ['class' => 'col-sm-12 col-xs-12', 'placeholder' => (isset($field['placeholder']) ? $field['placeholder'] : '') ]) }}
		</div>
	</div>
@endif
