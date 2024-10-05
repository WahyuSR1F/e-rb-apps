<!DOCTYPE html>
<html lang="en">
<head>
    @include('component.header')
</head>
<body>
    @php
    $auth = encrypt(Auth::user()->token);
@endphp
<input type="hidden" id="auth_token" name="auth_token" value="eyJpdiI6ImxUdmxXVERjMnhXcjRtMXM5ZnM1VWc9PSIsInZhbHVlIjoiTmVGTXlHaDNvTjFCeTB1MnozMkJyVDRwYU1OUmdaOTU2QnpESVBsRndCND0iLCJtYWMiOiJhMzg3NGQwOWYzNGYwMDM4YTNhMzYzN2M0N2QwOWNhYTJkZmRmZWI5MjQ0NDkwZjkyZDdhNjlmYzU5YmQ5NTIwIiwidGFnIjoiIn0=" autocomplete="off" style="visibility: hidden">
    @include('component.component-dasboard.sidebar')
</body>
</html>
