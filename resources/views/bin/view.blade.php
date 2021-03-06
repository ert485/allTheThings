<div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title">Selected Bin</h3>
    </div>
    <ul class="list-group list-group-flush">
            <li class="list-group-item">
                @if(isset($viewBin))
                    <table>
                            <td width=50>
                                {{$viewBinName . ": " }}
                            </td>
                            <td width=900>
                                @foreach ($viewTags as $tag)
                                {{ $tag . ", " }}
                                @endforeach
                            </td>
                            <td width=150>
                            @if(! $checked)
                            <a href="{{ action('BinsController@index', ['checkout' => $viewBin, 'viewBin' => '_'.$viewBin, 'tag' => $tag]) }}">
                                {{ Form::submit('CheckOut', ['class' => 'btn btn-primary']) }} 
                            </a><br>
                            @else
                                Already Checked Out!
                            @endif
                            </td>
                    </table>
                    <img src="{{ url('/showImage') . '/' . $viewBin }}" width="100%" />
                    
                @else
                    none
                @endif
            </li>
    </ul>
</div>