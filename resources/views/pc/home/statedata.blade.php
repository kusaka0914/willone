@foreach( $state_data as $state)
<option value="{{ $state->id }}">{{ $state->addr2 }}</option>
@endforeach
