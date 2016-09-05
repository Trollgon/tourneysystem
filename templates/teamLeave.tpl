{include file='documentHeader'}

<head>
	<title>{if $__wcf->getPageMenu()->getLandingPage()->menuItem != 'teamsystem.header.menu.teams'}{lang}teamsystem.header.menu.teams{/lang} - {/if}{PAGE_TITLE|language}</title>
	
	{include file='headInclude' sandbox=false}
</head>

<body id="tpl{$templateName|ucfirst}">

{include file='teamSidebar'  application='teamsystem' assign='sidebar'}

{include file='header' sidebarOrientation='left'}

<header class="boxHeadline">
	<h1>{lang}teamsystem.team.leave.header{/lang}</h1>
</header>

{include file='userNotice'}

{include file='formError'}

<p class="warning">{lang}teamsystem.team.leave.warning{/lang}</p>

<form method="post" action="{link application='teamsystem' controller='TeamLeave' teamID=$teamID}{/link}">

	<div class="formSubmit">

		<input type="submit" value="{lang}wcf.user.register.disclaimer.accept{/lang}" accesskey="s" />

		<a class="button" href="{link application='teamsystem' controller='InvitationList'}{/link}">{lang}wcf.user.register.disclaimer.decline{/lang}</a>

		{@SECURITY_TOKEN_INPUT_TAG}

	</div>

</form>

{include file='footer' sandbox=false}
</body>
</html>
