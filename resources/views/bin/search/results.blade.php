<div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title">Search results</h3>
    </div>
    <ul class="list-group list-group-flush">
            <li class="list-group-item">
                @if(isset($binNames) && (sizeof($binNames)>0))
                    <p hidden>{{$i=0}}</p>
                    @foreach ($binNames as $binName)
                        <a href="{{ url('/bin') . "/" . $binName }}">
                        {{$binName}}
                        <img id="image_{{$binName}}"
                            src="{{ url('/showImage') . '/' . $binFileNames[$i++] }}">
                        </img>
                        <script>
                            document.getElementById('image_{{$binName}}').height
                                = Math.floor(window.innerHeight / 4)
                        </script>
                        </a>
                    @endforeach
                @else
                    none
                @endif
            </li>
    </ul>
</div>