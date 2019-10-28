@if (session('success'))
    <div class="alert alert-success">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
            ×
        </button>
        <span class="glyphicon glyphicon-ok"></span>
        {{ session('success') }}
    </div>
@endif
@if (session('warning'))
    <div class="alert alert-warning">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
            ×
        </button>
        <span class="glyphicon glyphicon-warning-sign"></span>
        {{ session('warning') }}
    </div>
@endif
@if (session('error'))
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
        ×
    </button>
    <span class="glyphicon glyphicon-remove"></span>
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
@if (count($errors) > 0)
    <div class="alert alert-danger alert-dismissable">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif