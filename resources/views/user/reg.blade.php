<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>注册</title>
</head>
<body>

<form action="/user/reg" method="post">
    {{csrf_field()}}
    用户名: <input type="text" name="u_name"> <br>
    Email: <input type="text" name="u_email"> <br>
    手机号: <input type="text" name="u_mobile"> <br>
    密码: <input type="password" name="pass1"> <br>
    确认密码: <input type="password" name="pass2"> <br>
    <input type="submit" value="注册">
</form>
</body>
</html>