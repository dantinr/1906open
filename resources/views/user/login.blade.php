<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>登录</title>
</head>
<body>

<form action="/user/login" method="post">
    {{csrf_field()}}
    用户名: <input type="text" name="u_name" placeholder="Email/Mobile/Username"> <br>
    密码: <input type="password" name="pass"> <br>
    <input type="submit" value="登录">
</form>
</body>
</html>