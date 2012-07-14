<div{{$options}}></div>
<script>
$('#{{$id}}').css({width:'{{$w}}', height:'{{$h}}'});
$('#{{$id}}').gmap3({
	action: 'addMarker',
	address: '{{$address}}',
	map:{
		center:true,
		zoom:{{$zoom}}
	}
});
</script>