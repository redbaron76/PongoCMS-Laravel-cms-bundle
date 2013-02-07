{{$encoding}}
<urlset xmlns="http://www.google.com/schemas/sitemap/0.9"
		xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">

	@foreach($data as $url)

	<url>

		<loc>{{$base}}/{{$url->lang}}{{$url->slug}}</loc>
		<lastmod>{{$url->sitemap_update}}</lastmod>
		<changefreq>daily</changefreq>

		@if($url->files)

		@foreach($url->files as $file)
		<image:image>
			<image:loc>{{$base.$file->path}}</image:loc>
		</image:image>
		@endforeach

		@endif

	</url>

	@endforeach

</urlset>
