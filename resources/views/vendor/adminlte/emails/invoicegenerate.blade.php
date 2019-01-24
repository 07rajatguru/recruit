<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		@yield('style')
	</head>

	<body>
		<table>
			<tr>
				<td>
					<b><p style="font-family: arial;">Dear {{ $join_mail['client_name'] }},</p></b>
	                <p>Please Find Attachment.</p>
				</td>
			</tr>
		</table>
	</body>
</html>