@extends('layout.common')
@section('body')
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    <div class="pages section">
        <div class="container">
            <div class="pages-head">
                <h3>REGISTER</h3>
            </div>
            <div class="register">
                <div class="row">
                    <form class="col s12" method="post" action="{{url('home/doregister')}}">
                    @csrf
                        <div class="input-field">
                            <input type="text" name="name" class="validate" placeholder="NAME" required>
                        </div>
                        <div class="input-field">
                            <input type="email" name="email" placeholder="EMAIL" class="validate" required>
                        </div>
                        <div class="input-field">
                            <input type="password" name="pwd" placeholder="PASSWORD" class="validate" required>
                        </div>
                        <input type="submit" class="btn button-default" value="REGISTER">
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection