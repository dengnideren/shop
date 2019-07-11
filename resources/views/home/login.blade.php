@extends('layout.common')
@section('body')
    <!-- login -->
    <div class="pages section">
        <div class="container">
            <div class="pages-head">
                <h3>LOGIN</h3>
            </div>
            <div class="login">
                <div class="row">
                    <form class="col s12" method="post" action="{{url('home/dologin')}}">
                    @csrf
                        <div class="input-field">
                            <input type="text" name="name" class="validate" placeholder="USERNAME" required>
                        </div>
                        <div class="input-field">
                            <input type="password" name="pwd" class="validate" placeholder="PASSWORD" required>
                        </div>
                        <a href=""><h6>Forgot Password ?</h6></a>
                        <input type="submit" class="btn button-default" value="LOGIN">
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- end login -->
    @endsection
