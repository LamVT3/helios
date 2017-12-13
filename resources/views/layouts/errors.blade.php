@if(count($errors))
    <div class="form-group">
        <div class="alert alert-danger">
            <a class="close" data-dismiss="alert" href="#">×</a>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif
@if($flash = session('message'))
    <div class="alert alert-block alert-success">
        <a class="close" data-dismiss="alert" href="#">×</a>
        <p>
            {{ $flash }}
        </p>
    </div>
@endif