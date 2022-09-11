@extends('ui_http_kernel::layout')
@section('title','Sign In')
<style>
    html {
        height: 100%
    }

    body {
        background-color: #555;
    }

    .login-form {
        background: #333333;
        border: 1px solid #75fe99;
        border-radius: 6px;
        height: auto;
        padding-bottom: 30px;
        margin: 150px auto 0;
        width: 298px;
    }

    input[type="password"], input[type="text"] {
        border: 1px solid #a1a3a3;
        border-radius: 4px;
        box-sizing: border-box;
        color: #696969;
        height: 39px;
        margin: 33px auto 0;
        padding-left: 15px;
        width: 240px;
    }

    input[type="password"]:focus, input[type="text"]:focus {
        box-shadow: 0 0 4px 1px rgba(55, 166, 155, 0.3);
        outline: 0;
    }

    input[type="submit"] {
        width: 240px;
        height: 45px;
        display: block;
        font-size: 16px;
        font-weight: bold;
        color: #fff;
        text-align: center;
        padding-top: 3px;
        margin: 29px 0 0 29px;
        position: relative;
        cursor: pointer;
        border: none;
        background-color: #fa7901;
        border-radius: 4px;
    }

    input[type="submit"]:active {
        background-color: #d5833e;
    }

    .form-error {
        color: #fff;
        font-size: 15px;
        margin-top: 20px;
    }

</style>
<div class="login-form">
    <form action="{{$this->route('auth.auth')}}" method="post">
        <!-- todo: @csrf  -->
        <input name="login" type="text" placeholder="Login">
        <input name="password" type="password" placeholder="Password">
        <input type="submit" value="Sign In">
    </form>
    @if(isset($errors) && isset($errors['form']))
        <div class="form-error">{{$errors['form']}}</div>
    @endif


</div>

