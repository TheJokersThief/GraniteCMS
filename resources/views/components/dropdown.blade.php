<div class="form-group {{ (isset($field['class']) ? $field['class'] : '') }}">
    {{ \Form::label($field['name'], $field['title'], ['class' => 'control-label col-xs-12']) }}

    <div class="col-xs-12">
        {{ \Form::select($field['name'], $options, null, ['class' => 'form-control select2_single', 'style' => 'display: none;', 'id' => ($uid = hash('sha512', $field['name']) ) ]) }}
    </div>
</div>

<script>
 addLoadEvent(function() {
     $(document).ready(function() {
        $("#{{ $uid }}").select2({
          @if( isset($field['placeholder']) )
            placeholder: '{{$field['placeholder']}}',
          @endif
          allowClear: true
        });
      });
  });
</script>
