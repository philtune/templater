# Templater
Simple, extensible PHP templater, no frills.

## Install
Only need `.htaccess`, `index.php`, and `app.php`.

## Configure
`index.php` includes all the controller settings. A new instance of the `Page` class is declared (defined in the `app.php`) then calls the `render()` method.

A template or page (partial) document consists of declared variables and snippets or blocks of HTML:

**From `home.php`:**

	<% VARS = {
		"title": "Home Page",
		"template": "_t_layout.htm"
	} %>

	<% @main = %>
	this is the main element of <%= title %>
	<% end; %>

<sub>I use the `@` to distinguish blocks from variables, but they are basically the same thing. Use whatever convention works best for you.</sub>

`VARS` is a JSON object for all the pages variables. Variables (along with blocks, which are basically multiline variables) can be called with `<%= [variable_name] %>`. (If you want to use a different syntax, just update the `Page::$regex['macro']` RegEx.) "`template`" just declares the parent partial. (Notice my naming convention of using "_t_" prefixes for template names. You can use your own, of course.)

In the browser, pulling up `http://localhost/path/to/site/home` will rewrite to `http://localhost/path/to/site/index.php?url=home`, which loads `home.htm`, which requires `_t_layout.htm` (see `home.php` above), which in turn will require `_t_base.htm`. `_t_base.htm` does not declare a "`template`" so the `render()` method will look for a `@base` block and start compiling all the partials.
