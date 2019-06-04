<html>
    <head>
        <title>绑定公众号</title>
    </head>
    <body>
        <form action="/doBinding" method="post">
            <table align="center">
                <tr>
                    <th>账号</th>
                    <th>
                        <input type="text" name="username">
                    </th>
                </tr>
                <tr>
                    <th>密码</th>
                    <th>
                    <input type="hidden" name="openid" value="{{$OpenId}}"> 
                        <input type="password" name="password">
                    </th>
                </tr>
                <tr>
                    <th></th>
                    <th>
                        <button>提交</button>
                    </th>
                </tr>
            </table>
        </form>
    </body>
</html>