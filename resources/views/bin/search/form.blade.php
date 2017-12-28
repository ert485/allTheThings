<div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title">Search for tag</h3>
    </div>
    <ul class="list-group list-group-flush">
        {{ Form::open(['action' => 'BinsController@index', 'method' => 'GET']) }}
            <li class="list-group-item">
                {{ Form::text('tag', '', ['class' => 'form-control']) }}
            </li>
            <!--  
            <div class="panel-footer">
                {{ Form::submit('Search', ['class' => 'btn btn-primary']) }} 
            </div>
            -->
        {{ Form::close() }}
    </ul>
</div>