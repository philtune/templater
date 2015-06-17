<% VARS = {
	"stylesheets": ["css/reset.css"],
	"favicon": "img/fav.ico",
	"html5_ie8": "header,main,footer,section,article,aside,figure,nav,dl,dt,dd,small,time"
} %>

<% @base = %>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title><%= title %> - <%= sitename %></title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<%= favicon %>
		<%= html5_ie8 %>
		<%= stylesheets %>
	</head>
	<body>
		<%= @body %>
		<script src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
		<%= bottom_scripts %>
	</body>
</html>
<% end; %>
