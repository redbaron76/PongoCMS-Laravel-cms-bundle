{{View::make('cms::interface.partials.header', $header_data)}}

{{$menu}}

{{View::make('cms::interface.partials.top', $top_data);}}

{{$content}}

{{$footer}}