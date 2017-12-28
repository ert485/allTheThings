<div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title">Results</h3>
    </div>
    <ul class="list-group list-group-flush">
            <li class="list-group-item">
                @if(isset($binNames) && (sizeof($binNames)>0))
                    @foreach ($binNames as $binName)
                        <a href="{{ url('/bin') . "/" . $binName }}">
                        {{$binName}}
                        <!-- 
                        <img id="image_{{$binName}}"
                            src="{{ url('"
                            -->
                        </a>
                    @endforeach
                @else
                    none
                @endif
            </li>
    </ul>
</div>