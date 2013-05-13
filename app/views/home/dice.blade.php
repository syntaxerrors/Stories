@foreach ($dice as $diceSet)
	@foreach ($diceSet as $die)
		{{ $die }}
	@endforeach
@endforeach