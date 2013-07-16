<div{{$options}}></div>
<script>
$('#{{$id}}.{{$class}}').css({width:'{{$w}}', height:'{{$h}}'});
$('#{{$id}}.{{$class}}').gmap3({
	marker: {
		@if(strlen($lat) > 0 && strlen($lng) > 0)
			values: [
				{latLng:[{{$lat}},{{$lng}}]}
			],			
		@else
			address: '{{$address}}',
		@endif
		options: {
			icon: '{{$icon}}'
		}
	},	
	map:{
		options: {
			zoom:{{$zoom}},
			mapTypeControl: true,
			navigationControl: true,
			scrollwheel: true,
			streetViewControl: true,
			@if(strlen($maptype) > 0)
				mapTypeId: {{$maptype}},
			@endif
		}
	}
});
</script>
