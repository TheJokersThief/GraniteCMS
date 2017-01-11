<div class="form-group {{ (isset($field['class']) ? $field['class'] : '') }}">
	{{ \Form::label($field['name'], $field['title'], ['class' => 'control-label col-xs-12']) }}
    <div class="col-xs-12">
      {{ \Form::password($field['name'], ['class' => 'col-sm-12 col-xs-12 form-control', 'placeholder' => (isset($field['placeholder']) ? $field['placeholder'] : '') ]) }}
    </div>
</div>