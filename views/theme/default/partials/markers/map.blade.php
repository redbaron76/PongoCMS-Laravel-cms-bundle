<div{{$options}}></div>
<script>
$('#{{$id}}').css({width:'{{$w}}', height:'{{$h}}'});
$('#{{$id}}').gmap3({
	action: 'addMarker',
	address: '{{$address}}',
	map:{
		center:true,
		zoom:{{$zoom}},
		@if(strlen($maptype) > 0)
		mapTypeId: {{$maptype}},
		@endif
	}
});
</script>