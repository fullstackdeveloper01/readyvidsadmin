<!DOCTYPE html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>ReadyVids Emailer || Forget Password</title>
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
		<style type="text/css">
		body {
			margin: 0;
			padding: 0;
			background-color:#EDEDED;
			font-size:13px;
			color:#444;
			font-family:'Poppins', sans-serif;
			padding-top:75px;
		}
		table, tr, td, th {
			margin: 0;
			padding: 0;
		}
		img {
			border: none;
		}
		a {
			text-decoration: none; cursor:pointer !important;	
		}
		table[class="outer-tbl"] {
			width:768px !important;
			margin:0px auto !important;
			
		}
		p {

			padding:0;
		}
		img[class="main-image"] {width:100% !important; }
		div[class="foot-items"]{padding:0 190px;}
		table[class="full-wid"] {width:100%;}
		a[class="hide"]{display:inline;}

		@media only screen and (max-width:767px) {
		body {padding:0; }
		a[class="hide"]{display:none !important;}
		table[class="outer-tbl"] {width:320px !important; margin-top:0 !important; margin-bottom:0 !important;}
		div[class="foot-items"]{max-width:290px}
		td[class="logo"] {padding:20px 0  !important;}
		td[class="text"] {padding:5px 0 2px 0 !important;}
		td[class="footer"] {padding:15px 0 !important;}
		td[class="botm"] {padding:0 0 15px 0 !important;}
		td[class="less-wid"]{ font-family:'Poppins', sans-serif; font-size:13px;padding:10px !important;}
		td[class="pad-top"] {padding-top:10px !important;}
		p {margin-top:10px !important;}
		p[class="rdlinht"] {line-height:15px !important;}
		p[class="cnteimg"] {margin:20px 0 0 0!important; }
		img[class="main-image"] {width:100% !important; margin:0; padding:0; }
		p[class="pre"]{padding:0 10px !important;}
		td[class="pad-l-r-b"]{padding:0 15px 30px !important;}
		td[class="pad-l-r"]{padding:0 !important;}
		td[class="content"]{padding:20px 20px !important;}
		div[class="foot-items"]{padding:0 10px;}
		}
		</style>
	</head>

	<body style="margin: 0;	padding: 0; background-color:#fff; font-size:13px; color:#444; font-family:'Poppins', sans-serif;	padding-top:70px; padding-bottom:70px;">
		<table  cellspacing="0" cellpadding="0" align="center" width="768" class="outer-tbl" style="margin:0 auto;">
			<tr>
				<td class="pad-l-r-b" style="background-color:#29AAE2; padding:10px;">
					<!--<table cellpadding="0" cellspacing="0" class="full-wid">
						<tr>
						<td style="padding:20px 0; text-align:right; font-family:'Poppins', sans-serif;" align="right">
							<a href="javascript:void(0);" style="font-size:12px; color:#DDDDDD; "></a></td>
						</tr>        
					</table>-->
					<table cellpadding="0" cellspacing="0"  style="width:100%; background-color:#0080b7; border-radius:4px;">
						<tr>
							<td>
								<table border="0" style="margin:0; width:100%" cellpadding="0" cellspacing="0">
									<tr>
										<td class="logo" style="padding:40px 0 30px 0; text-align:center; border-bottom:1px solid #E1E1E1;margin:0 auto;">
											<img src="{{asset('logo.png')}}" alt="ReadyVids" title="ReadyVids" style="width:50%;margin:0 auto;text-align:center;">
											<h2 style="font-family:'Poppins', sans-serif; font-size:22px; color:#fff; margin-top:10px;letter-spacing:1px;font-weight:300;">Welcome to ReadyVids</h2>
										</td>
									</tr>
									<tr>
										<td class="content" style="padding:40px 40px;">
											<p style="font-family:'Poppins', sans-serif; font-size:15px; color:#fff; margin-top:10px;">Dear {{$user_name}}, </p>
									
											</p>
											<p style="font-family:'Poppins', sans-serif; font-size:15px; color:#fff; margin-top:0">Thank you for submitting your application to join ReadyVids as a Business Partner. We have received your details and are poised to embark on the thorough review process that such a pivotal role necessitates.</p>
											<p style="font-family:'Poppins', sans-serif; font-size:15px; color:#fff;">The role of a Business Partner within ReadyVids is not just a title, but a commitment to shared growth and excellence in the e-learning and digital content sphere. We recognize the profound impact our partners can have, which is why our selection process is meticulously designed to identify individuals who can truly resonate with and contribute to our vision.</p>
											<p style="font-family:'Poppins', sans-serif; font-size:15px; color:#fff;">Please expect a call from our partnership team within the next week. This conversation will be an opportunity for us to better understand your capabilities, your potential contribution to ReadyVids, and to answer any queries you may have about the partnership.</p>
											<p style="font-family:'Poppins', sans-serif; font-size:15px; color:#fff;">Given the limited number of Business Partner positions and the importance of these roles, we take great care in selecting the right fit for our expanding ecosystem. We seek individuals who are ready to invest not just time, but also innovation and dedication to our collective success.</p>
											<p style="font-family:'Poppins', sans-serif; font-size:15px; color:#fff;">Should you need any immediate assistance or have questions in the interim, please feel free to reach out to us.</p>
											<p style="font-family:'Poppins', sans-serif; font-size:15px; color:#fff;">We thank you for your patience and your interest in forging a path together with ReadyVids.</p>
											
											<p style="font-family:'Poppins', sans-serif; font-size:15px; color:#fff; margin-top:20px; margin-bottom:0;">Warm regards,</p>
											<p style="font-family:'Poppins', sans-serif; font-size:15px; color:#fff; font-weight:600; margin-top:5px">The ReadyVids Partnership Team</p>
										
										</td>
									</tr>
								</table>
							</td>
						</tr>        
					</table>
				</td>
			</tr>  
		</table>
	</body>
</html>
