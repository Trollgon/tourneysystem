{include file='documentHeader'}

<head>
	<title>{if $__wcf->getPageMenu()->getLandingPage()->menuItem != 'tourneysystem.header.menu.teams'}{lang}tourneysystem.header.menu.teams{/lang} - {/if}{PAGE_TITLE|language}</title>
	
	{include file='headInclude' sandbox=false}
</head>



<body id="tpl{$templateName|ucfirst}">
{include file='header' sandbox=false}

<header class="boxHeadline">
	{if $__wcf->getPageMenu()->getLandingPage()->menuItem == 'tourneysystem.header.menu.teams'}
		<h1>{PAGE_TITLE|language}</h1>
		{hascontent}<h2>{content}{PAGE_DESCRIPTION|language}{/content}</h2>{/hascontent}
	{else}
		<h1>{lang}tourneysystem.header.menu.teams{/lang}</h1>
	{/if}
</header>

{include file='userNotice'}




	
<div class="contentNavigation">
    {hascontent}
        <nav>
            <ul>
                {content}
                    <li><a href="{link application='tourneysystem' controller='TeamCreate'}{/link}"
                           title="{lang}tourneysystem.team.add{/lang}" class="button"><span
                                class="icon icon16 icon-asterisk"></span>
                        <span>{lang}tourneysystem.team.add{/lang}</span></a></li>
					<li><a href="{link application='tourneysystem' controller='TeamPage'}{/link}"
                           title="{lang}tourneysystem.team.page.user{/lang}" class="button"><span
                                class="icon icon16 icon-group"></span>
                        <span>{lang}tourneysystem.team.page.user{/lang}</span></a></li>
                    <li><a href="{link application='tourneysystem' controller='PcTeamsList'}{/link}"
                           title="{lang}tourneysystem.team.page.user{/lang}" class="button"><span
                                class="icon icon16 icon-group"></span>
                        <span>{lang}tourneysystem.team.page.teams.pc{/lang}</span></a></li>
                {event name='contentNavigationButtonsTop'}
                {/content}
            </ul>
        </nav>
    {/hascontent}
</div>

<div class="contentNavigation">
	{hascontent}
		<nav>
			<ul>
				{content}
					{event name='contentNavigationButtonsBottom'}
				{/content}
			</ul>
		</nav>
	{/hascontent}
</div>

{include file='footer'}

</body>
</html>
