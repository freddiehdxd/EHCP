<?php
// ehcp language file
$this->lang['en']=
array(
'undefined_limittype'=>'Undefined limittype.',
'norecordfound'=>'Nothing/No Record Found.',
'recordcount'=>'Record Count:',
'areyousuretodelete'=>'Are you sure to delete ? <br>',
'error_occured'=>'Error Occured ',
'write_yourmessage_here'=>'Write Your Messsage Here',
'name_surname'=>'Name Surname',
'enter_yourcontactinfo'=>'Enter Your Contact Info Here',
'yourmessage_received'=>'Thanks. We Received Your Message.',
'search_'=>'Search',
'list_all'=>'List All',
'int_undefined_limittype'=>'internal ehcp error: undefined limittype',
'perhaps_db_error'=>'This may mean that your db settings are not correct, you cannot connect to db; or your db/html table has missing rows.<br> You may edit config.php for ehcp mysql user password. <br> Try<a href=misc/mysqltroubleshooter.php>mysql troubleshooter</a> to solve this problem.',
'welcome to world'=>'Welcome to World...',
'Panel Home'=>'Panel Home/Deselect',
'Domain Home'=>'Domain Home',
'Domains'=>'Domains',
'Selected Domain'=>'Selected Domain',
'domain_deactivated_contact_administrator'=>'This domain is deactivated by owner/admin. Contact Your domain administrator..  / Bu domain, sahibi ya da yoneticisi tarafindan kapatilmistir. yonetici ile irtibata geciniz.',
'updatehostsfile'=>'This machine is used for Desktop access too (Update hosts file with domains)',
'dnsip'=>'dnsip (outside/real/static ip of server)',
'dnsipv6'=>'dnsip V6(outside/real/static V6 ip of server)',
'disablecustomhttp'=>'Disable Custom http',
'disableeditapachetemplate'=>'Disable Custom http for non-admins',
'disableeditdnstemplate'=>'Disable Custom dns for non-admins',
'turnoffoverquotadomains'=>'Turn off over quota domains',
'domain_format_wrong'=>'(Sub)Domain format is not suitable, check it..',

# These are separated to this lang file, to enable editing html of pages easily, for ex, add buttons for functions..
'similar_functions_ftp' => "<a href='?op=addftpuser'>Add New ftp</a>, <a href='?op=addftptothispaneluser'>Add ftp Under My ftp</a>, <a href='?op=addsubdirectorywithftp'>Add ftp in a subDirectory Under Domainname</a>, <a href='?op=addsubdomainwithftp'>Add subdomain with ftp</a>, <a href='?op=add_ftp_special'>Add ftp under /home/xxx (admin)</a>, <a href='net2ftp' target=_blank>WebFtp (Net2Ftp)</a>, <a href='?op=addcustomftp'>Add Custom FTP Account (Admins Only)</a>, <a href='?op=listallftpusers'>List All Ftp Users</a> ",
'similar_functions_mysql' => "<a href='?op=domainop&amp;action=listdb'>List / Delete Mysql Db's</a>, <a href='?op=addmysqldb'>Add Mysql Db&amp;dbuser</a>, <a href='?op=addmysqldbtouser'>Add Mysql db to existing dbuser</a>, <a href='?op=dbadduser'>Add Mysql user to existing db</a>, <a href='/phpmyadmin' target=_blank>phpMyadmin</a>",
'similar_functions_email' => "<a href='?op=listemailusers'>List Email Users / Change Passwords</a>, <a href='?op=addemailuser'>Add Email User</a>, Email forwardings: <a href='?op=emailforwardings'>List</a> - <a href='?op=addemailforwarding'>Add</a>, <a href='?op=bulkaddemail'>Bulk Add Email</a>, <a href='?op=editEmailUserAutoreply'>edit Email Autoreply</a> ,<a href='webmail' target=_blank>Webmail (Squirrelmail)</a>",
'similar_functions_domain' => "<a href='?op=addDomainToThisPaneluser'>Add Domain To my ftp user (Most Easy)</a> - <a href='?op=adddomaineasy'>Easy Add Domain (with separate ftpuser)</a> - <a href='?op=adddomain'>Normal Add Domain (Separate ftp&panel user)</a> - <a href='?op=bulkadddomain'>Bulk Add Domain</a> - <a href='?op=adddnsonlydomain'>Add dns-only hosting</a> - <a href='?op=adddnsonlydomainwithpaneluser'>Add dns-only hosting with separate paneluser</a>-<br><a href='?op=addslavedns'>Make Domain a DNS Slave</a> - <a href='?op=removeslavedns'>Remove DNS Slave, if any</a><br><br>Different IP(in this server, not multiserver): <a href='?op=adddomaineasyip'>Easy Add Domain to different IP</a> - <a href='?op=setactiveserverip'>set Active webserver IP</a><br>List Domains: <a href='?op=listselectdomain'>short listing</a> - <a href='?op=listdomains'>long listing</a>",
'similar_functions_redirect' => "<a href='?op=editdomainaliases'>Edit Domain Aliases</a>",
'similar_functions_customhttpdns' => "Custom Http: <a href='?op=customhttp'>List</a> - <a href='?op=addcustomhttp'>Add</a>, Custom dns: <a href='?op=customdns'>List</a> - <a href='?op=addcustomdns'>Add</a> --  Custom Permissions: <a href='?op=custompermissions'>List</a> - <a href='?op=addcustompermission'>Add</a>",
'similar_functions_subdomainsDirs' => "SubDomains: <a href='?op=subdomains'>List</a> - <a href='?op=addsubdomain'>Add</a> - <a href='?op=addsubdomainwithftp'>Add subdomain with ftp</a> - <a href='?op=addsubdirectorywithftp'>Add subdirectory with ftp (Under domainname)</a> - <a href='?op=sync_directories'>Sync Directories</a>",
'similar_functions_HttpDnsTemplatesAliases' => "<a href='?op=editdnstemplate'>Edit dns template for this domain </a> - <a href='?op=editapachetemplate'>Edit apache template for this domain </a> - <a href='?op=editdomainaliases'>Edit Aliases for this domain </a>",
'similar_functions_panelusers' => "<a href='?op=listpanelusers'>List All Panelusers/Clients</a>, <a href='?op=resellers'>List Resellers</a>, <a href='?op=addpaneluser'>Add Paneluser/Client/Reseller</a>",
'similar_functions_server' => "<a href='?op=listservers'>List Servers/IP's</a> - <a href='?op=addserver'>Add Server</a> - <a href='?op=addiptothisserver'>Add ip to this server</a> - <a href='?op=setactiveserverip'>set Active webserver IP</a>",
'similar_functions_backup' => "<a href='?op=dobackup'>Backup</a> - <a href='?op=dorestore'>Restore</a> - <a href='?op=listbackups'>List Backups</a>",
'similar_functions_vps' => "<a href='?op=vps'>VPS Home</a> - <a href='?op=add_vps'>Add new VPS</a> - <a href='?op=copy_vps_image'>Copy VPS Image</a> - <a href='?op=settings&group=vps'>VPS Settings</a> - <a href='?op=vps&op2=other'>Other Vps Ops</a>",
'similar_functions_pagerewrite' => "<a href='?op=pagerewrite'>page rewrite home</a> - <a href='?op=pagerewrite&op2=add'>add page rewrite</a>",
'similar_functions_custompermissions' => "<a href='?op=custompermissions'>List Custom Permissions</a> - <a href='?op=addcustompermission'>Add Custom Permissions</a>",
'similar_functions_vpn' => "<a href='?op=list_vpn'>List Vpn Users</a> <a href='?op=add_vpn'>Add Vpn User</a>",

'similar_functions_options'=> 
"
	<br><a href='?op=options&edit=1'>Edit/Change Options</a><br>
	<br><a href='?op=changemypass'>Change My Password</a>
	<br><a href='?op=listpanelusers'>List/Add Panelusers/Resellers</a>
	<br><a href='?op=dosyncdns'>Sync Dns</a>
	<br><a href='?op=dosyncdomains'>Sync Domains</a><br>
	<br><a href='?op=dosyncftp'>Sync Ftp (for non standard home dirs)</a><br>
	<hr><a href='?op=advancedsettings'>Advanced Settings</a><br><br>
	<br><a href='?op=dofixmailconfiguration'>Fix Mail Configuration<br>Fix ehcp Configuration</a> (This is used after changing ehcp mysql user pass, or if you upgraded from a previous version, in some cases)<br>
	<br><br><a href='?op=dofixapacheconfigssl'>Fix Apache Configuration with ssl</a>(use with caution,may be risky)<br><br>
	<br><a href='?op=dofixapacheconfignonssl'>Fix Apache Configuration without ssl</a><br>
	<br><a href='?op=dofixapacheconfignonssl2'>Fix Apache Configuration without ssl, way2</a> - use this if first wone does not work. this disables custom apache configurations, if any<br>
	<br>
	<hr>
	<a href='?op=listservers'>List/Add Servers/ IP's</a><br>
	<hr>
	Roles: <a href='?op=list_roles'>List</a> - <a href='?op=assign_role'>Assign Role</a><br>

	<hr>
	Experimental:
	<br><a href='?op=donewsyncdns'>New Sync Dns - Multiserver</a>
	<br><a href='?op=donewsyncdomains'>New Sync Domains - Multiserver</a><br>
	<br><a href='?op=multiserver_add_domain'>Multiserver Add Domain</a>
	<hr>

	"
,

'select_language'=>
"
(<a href='?op=setlanguage&id=en'>En</a>
<a href='?op=setlanguage&id=tr'>Tr</a>
<a href='?op=setlanguage&id=german'>German</a>
<a href='?op=setlanguage&id=spanish'>Spanish</a>
<a href='?op=setlanguage&id=fr'>Fr</a>
<a href='?op=setlanguage&id=lv'>Latvie&#353;u</a>
<a href='?op=setlanguage&id=it'>Italian</a>
<a href='?op=setlanguage&id=zh'>Taiwan</a>
)
<br>
<table>
<tr>
<td style=\" padding: 3px; \"><a href='?op=setlanguage&id=en'><img height=30 width=50 src=images/en.jpg border=0></a></td>
<td style=\" padding: 3px; \"><a href='?op=setlanguage&id=tr'><img height=30 width=50 src=images/tr.jpg border=0></a></td>
<td style=\" padding: 3px; \"><a href='?op=setlanguage&id=german'><img height=30 width=50 src=images/german.jpg border=0></a></td>
<td style=\" padding: 3px; \"><a href='?op=setlanguage&id=spanish'><img height=30 width=50 src=images/spanish.jpg border=0></a></td>
<td style=\" padding: 3px; \"><a href='?op=setlanguage&id=fr'><img height=30 width=50 src=images/fr.jpg border=0></a></td>
<td style=\" padding: 3px; \"><a href='?op=setlanguage&id=lv'><img height=30 width=50 src=images/lv.jpg border=0></a></td>
<td style=\" padding: 3px; \"><a href='?op=setlanguage&id=it'><img height=30 width=50 src=images/it.jpg border=0></a></td>
<td style=\" padding: 3px; \"><a href='?op=setlanguage&id=zh'><img height=30 width=50 src=images/zh.jpg border=0></a></td>

</tr>
</table>
",

''=>''
);




?>
