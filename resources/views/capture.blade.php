<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse" aria-expanded="false">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                </div>
            
                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @guest
                            <li><a href="{{ route('login') }}">Login</a></li>
                            <li><a href="{{ route('register') }}">Register</a></li>
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
    </div>
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div id="my_camera"></div>
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

    
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    
</body>
</html>



