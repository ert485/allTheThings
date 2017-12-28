@extends('layouts.app')

@section('content-for-capture') <!-- a bug caused the need for a new content location (outsite div 'app') -->

{{ Form::open(['action' => 'BinsController@store', 'method' => 'POST']) }}
<input id="binImage" type="hidden" name="binImage" value="" />

<p align="center">
    Bin Name <input value="a0000" type="text" name="name" size="4" onfocus="this.value=''" /> &ensp;  &ensp; 
    Tags <input value="tag1-tag2-tag3" type="text" name="tags" size="70" onfocus="this.value=''" />
    <input type="submit" name="submit" value="Submit" onClick="capture()"/>
    <div id="preview"></div>
</p>
{{ Form::close() }}

<!-- webcam.js library. See public/js/webcam.js for attribution -->
<script type="text/javascript" src="{{ asset('js/webcam.js') }}"></script>

<!-- webcam settings -->
<script language="JavaScript">
	Webcam.set({
		height: 720,
		image_format: 'jpeg',
		jpeg_quality: 100,
	});
	Webcam.attach( 'preview' );
</script>

<!-- take the shot -->
<script language="JavaScript">
  function capture() {
	  Webcam.snap( function(uri) {
	  var image = uri.replace(/^data\:image\/\w+\;base64\,/, '');
	  document.getElementById('binImage').value = image;	
} );
 }
</script>

@endsection