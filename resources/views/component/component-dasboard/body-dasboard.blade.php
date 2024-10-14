<!DOCTYPE html>
<html lang="en">

<head>
    @include('component.header')
</head>

<body>
    @php
        $auth = encrypt(Auth::user()->token);
    @endphp
    <input type="hidden" id="auth_token" name="auth_token" value="{{ $auth }}" autocomplete="off"
        style="visibility: hidden">
    @include('component.component-dasboard.sidebar')
</body>

</html>
