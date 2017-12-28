<div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title">Results</h3>
    </div>
    <ul class="list-group list-group-flush">
            <li class="list-group-item">
                @if(isset($binNames) && (sizeof($binNames)>0))
                    {{ var_dump($binNames) }}
                @else
                    none
                @endif
            </li>
    </ul>
</div>