<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8"/>
	 {!! Html::style($protocol .'fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all') !!}
	 {!! Html::style($publicUrl .'/assets/global/plugins/bootstrap/css/bootstrap.min.css') !!}
	 {!! Html::script($publicUrl .'/assets/global/plugins/jquery.min.js') !!}
     {!! Html::script($publicUrl .'/assets/global/plugins/bootstrap/js/bootstrap.min.js') !!}
<style>
htnl,body,*{
	margin:0;
	padding:0;
	font-family:'Open Sans',sans-serif
}
.fullpage,body{
	background:#f0f0f0 !important;
}
#content{
	max-width:600px;
	margin:100px auto;
	background:#fff;
	padding:20px 30px;
	border-radius:4px;
	border:1px solid #ddd;
}
.logo{
	text-align:center;
}
.footer p,.footer a{
	color:#333;
}
.logo img{
	max-width:160px;
}
.content-email p{
	font-size:15px;
}
.divide{
	text-align:center;
	margin: 12px 0px;
}
.divide img{
	max-width:100px;
}
</style>
</head>
<body>
	<div class="fullpage">
		<div class="container">
			<div id="content">
				 <div class="logo">
					<a href="{{ $siteUrl }}" style="text-decoration:none;">
					<img src="{{ $publicUrl }}/images/{{ $config['logo_transparent'] }}" alt="{{ $config['logo_transparent'] }}" border="0" />
					</a>
				 </div>
				 <div class="divide">
					<img src="{{ $publicUrl }}/img/divider3.png" />
				 </div>
				 <h1 style="text-align:center"><strong>Thank you for choosing AllPaving!</strong></h1>
				 <div class="content-email">
						<p>Hi {{$first_name}},</p>
						<p>Thank you for choosing AllPaving,</p>
						<p>below is date and time for our appointment:</p>
						<p>Meeting Day: {{$meeting_date}}</p>
						<p>Meeting Time: {{$meeting_time}}</p>
						<p>This is address for a meeting: {{$address}}</p>
				 </div>
				 <div class="divide">
					<img src="{{ $publicUrl }}/img/divider3.png" />
				 </div>
				 <div class="footer">
					<p style="font-weight: bold; text-align: center;font-size: 11px; padding: 0; margin: 0;">&copy; {{ date('Y') }} &bull; <a href="{{ $siteUrl }}" style="text-decoration: none">{{ $config['company'] }}</a></p>
				 </div>
			</div>
		</div>
	</div>
</body>
</html>