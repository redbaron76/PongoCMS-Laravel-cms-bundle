<div{{$options}}>
	@foreach($services as $service)

		@if($service == 'facebook')
		<a class="addthis_button_facebook_like" fb:like:layout="button_count" fb:like:width="105" addthis:url="{{Config::get('application.url').SLUG_FULL}}"></a>
		@endif

		@if($service == 'twitter')
		<a class="addthis_button_tweet"></a>
		@endif

		@if($service == 'linkedin' and $user != '')
		<a class="addthis_button_linkedin_follow" addthis:userid="{{$user}}"></a>
		@endif

		@if($service == 'google')
		<a class="addthis_button_google_plusone" g:plusone:size="medium"></a> 
		@endif

		@if($service == 'follow' and $user != '')
		<a class="addthis_button_twitter_follow_native" tw:screen_name="{{$user}}"></a>
		@endif

	@endforeach
</div>