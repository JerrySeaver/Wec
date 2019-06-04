<html>
	<head>
		<title>注册</title>
	</head>
	<body>
		<h4>您好 您的验证码为{{$rand=rand(000000,999999)}}</h4>
		{{
			session()->put('rand',"$rand")
        }}
	</body>
</html>