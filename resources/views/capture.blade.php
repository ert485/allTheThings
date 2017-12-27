@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-body">
                    	<!-- A button for taking snaps -->
                    	<form>
                    		<input type=button value="Take Snapshot" onClick="take_snapshot()">
                    	</form>
                    	
                    	
                    	<!-- First, include the Webcam.js JavaScript Library -->
                    	<script type="text/javascript" src="{{ asset('js/webcam.js') }}"></script>
                    	
                    	<!-- Configure a few settings and attach camera -->
                    	<script language="JavaScript">
                    		Webcam.set({
                    			width: 320,
                    			height: 240,
                    			dest_width: 640,
                    			dest_height: 480,
                    			// final cropped size
                    			crop_width: 480,
                    			crop_height: 480,
                    
                    			image_format: 'jpeg',
                    			jpeg_quality: 90,
                    			upload_name: 'headshot'
                    		});
                    		Webcam.attach( '#my_camera' );
                    	</script>
                    	
                    	
                    	<!-- Code to handle taking the snapshot and displaying it locally -->
                    	<script language="JavaScript">
                    	  function take_snapshot() {
                        	  // take snapshot and get image data
                        	  Webcam.snap( function(data_uri) {
                        	  // display results in page
                        	  document.getElementById('results').innerHTML = 
                        		'<img src="'+data_uri+'"/>';
                        	  var raw_image_data =
                        		data_uri.replace(/^data\:image\/\w+\;base64\,/, '');
                        	  document.getElementById('headshot').value = raw_image_data;	
                    		
                    	} );
                         }
                    	</script>
                    
                    {!! Form::open(['action' => 'CaptureController@store', 'method' => 'POST']) !!}
                    
                    <!--Image: <input type="file" name="headshot" /><br />-->
                    <input id="headshot" type="hidden" name="headshot" value="" />
                    <div id="results"></div>
                    
                    <table>
                      <tr><td>Bin Name</td><td><input type="text" name="name" size="50" /></td></tr>
                      <tr><td>Tags</td><td><input type="text" name="tags" size="50" /></td></tr>
                    </table>
                    
                    
                    <input type="submit" name="submit" value="Submit" />
                    
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


