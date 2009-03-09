<HTML>
<HEAD>
	<link rel="shortcut icon" href="templates/{$SKIN_NAME}/images/favicon.ico">
	<link rel="icon" href="templates/{$SKIN_NAME}/images/animated_favicon1.gif" type="image/gif">

	<title>..:: {$CCMAINTITLE} ::..</title>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

	<link href="templates/{$SKIN_NAME}/css/main.css" rel="stylesheet" type="text/css">
	<link href="templates/{$SKIN_NAME}/css/menu.css" rel="stylesheet" type="text/css">
	<link href="templates/{$SKIN_NAME}/css/style-def.css" rel="stylesheet" type="text/css">
	<link href="templates/{$SKIN_NAME}/css/invoice.css" rel="stylesheet" type="text/css">
	<link href="templates/{$SKIN_NAME}/css/receipt.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="./javascript/jquery/jquery-1.2.6.min.js"></script>
	<script type="text/javascript" src="./javascript/jquery/jquery.debug.js"></script>
	<script type="text/javascript" src="./javascript/jquery/ilogger.js"></script>
	<script type="text/javascript" src="./javascript/jquery/handler_jquery.js"></script>
	<script language="javascript" type="text/javascript" src="./javascript/misc.js"></script>
</HEAD>

<BODY leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
{if ($popupwindow == 0)}
<p class="version" align="right">{$WEBUI_VERSION} - {$WEBUI_DATE}</p>
<br>
{/if}
<DIV border=0 width="1000">


{if ($popupwindow == 0)}
{if ($EXPORT == 0)}
<div class="divleft">

	<div id="nav_before"></div>
	<ul id="nav">



		<div>
		<ul><li><a href="userinfo.php?section=1"><strong>{php} echo gettext("ACCOUNT INFO");{/php}</strong></a></li></ul>

		{if $ACXSIP_IAX>0 }
		<li><a href="#" target="_self"></a></li>
		<ul><li><a href="A2B_entity_sipiax_info.php?section=1"><strong>{php} echo gettext("SIP/IAX INFO");{/php}</strong></a></li></ul>
		{/if}

		{if $ACXCALL_HISTORY >0 }
		<li><a href="#" target="_self"></a></li>
		<ul><li><a href="call-history.php?section=2"><strong>{php} echo gettext("CALL HISTORY");{/php}</strong></a></li></ul>
		{/if}
		
		{if $ACXPAYMENT_HISTORY >0 }
		<li><a href="#" target="_self"></a></li>
		<ul><li><a href="payment-history.php?section=3"><strong>{php} echo gettext("PAYMENT HISTORY");{/php}</strong></a></li></ul>
		{/if}
		

		{if $ACXVOUCHER >0 }
		<li><a href="#" target="_self"></a></li>
		<ul><li><a href="A2B_entity_voucher.php?form_action=list&section=4"><strong>{php} echo gettext("VOUCHER");{/php}</strong></a></li></ul>
		{/if}

		<li><a href="#" target="_self"></a></li>
		</div>

		{if $ACXINVOICES >0 }
		<div class="toggle_menu">
		<li>
		<a href="javascript:;" class="toggle_menu" target="_self"><img id="img1"
		{if ($section == "5")}
		src="templates/{$SKIN_NAME}/images/minus.gif"
		{else}
		src="templates/{$SKIN_NAME}/images/plus.gif"
		{/if}
	 	onmouseover="this.style.cursor='hand';" WIDTH="9" HEIGHT="9">&nbsp; <strong>{php} echo gettext("INVOICES");{/php}</strong></a></li>
		<div class="tohide"
		{if ($section == "5")}
		style="">
		{else}
		style="display:none;">
		{/if}
		<ul>
			<li><ul>
					<li><a href="A2B_entity_receipt.php?section=5"><strong>{php} echo gettext("View Receipts");{/php}</strong></a></li>
					<li><a href="A2B_entity_invoice.php?section=5"><strong>{php} echo gettext("View Invoices");{/php}</strong></a></li>
					<li><a href="A2B_invoice_preview.php?section=5"><strong>{php} echo gettext("Preview Next Invoice");{/php}</strong></a></li>
			</ul></li>
		</ul>
		</div>
		</div>
		{/if}


		{if $ACXDID >0 }
		<li><a href="#" target="_self"></a></li>
		<ul><li><a href="A2B_entity_did.php?form_action=list&section=6"><strong>{php} echo gettext("DID");{/php}</strong></a></li></ul>
		{/if}

		{if $ACXSPEED_DIAL >0 }
		<li><a href="#" target="_self"></a></li>
		<ul><li><a href="A2B_entity_speeddial.php?atmenu=speeddial&stitle=Speed+Dial&section=7"><strong>{php} echo gettext("SPEED DIAL");{/php}</strong></a></li></ul>
		{/if}

		{if $ACXRATECARD >0 }
		<li><a href="#" target="_self"></a></li>
		<ul><li><a href="A2B_entity_ratecard.php?form_action=list&section=8"><strong>{php} echo gettext("RATECARD");{/php}</strong></a></li></ul>
		{/if}

		{if $ACXSIMULATOR >0 }
		<li><a href="#" target="_self"></a></li>
		<ul><li><a href="simulator.php?section=9"><strong>{php} echo gettext("SIMULATOR");{/php}</strong></a></li></ul>
		{/if}

		{if $ACXCALL_BACK >0 }
		<li><a href="#" target="_self"></a></li>
		<ul><li><a href="callback.php?section=10"><strong>{php} echo gettext("CALLBACK");{/php}</strong></a></li></ul>
		{/if}
		{if $ACXWEB_PHONE >0 }
		<li><a href="#" target="_self"></a></li>
		<ul><li><a href="webphone.php?section=11"><strong>{php} echo gettext("WEB-PHONE");{/php}</strong></a></li></ul>
		{/if}

		{if $ACXCALLER_ID >0 }
		<li><a href="#" target="_self"></a></li>
		<ul><li><a href="A2B_entity_callerid.php?atmenu=callerid&stitle=CallerID&section=12"><strong>{php} echo gettext("ADD CALLER ID");{/php}</strong></a></li></ul>
		{/if}

		{if $ACXPASSWORD>0 }
		<li><a href="#" target="_self"></a></li>
		<ul><li><a href="A2B_entity_password.php?atmenu=password&form_action=ask-edit&stitle=Password&section=13"><strong>{php} echo gettext("PASSWORD");{/php}</strong></a></li></ul>
		{/if}
		{if $ACXSUPPORT >0 }
		<li><a href="#" target="_self"></a></li>
		<ul><li><a href="A2B_support.php"><strong>{php} echo gettext("SUPPORT");{/php}</strong></a></li></ul>
		{/if}
		{if $ACXNOTIFICATION >0 }
		<li><a href="#" target="_self"></a></li>
		<ul><li><a href="A2B_notification.php?form_action=ask-edit"><strong>{php} echo gettext("NOTIFICATION");{/php}</strong></a></li></ul>
		
		{/if}
		
		{if $ACXAUTODIALER>0 }
		<li><a href="#" target="_self"></a></li>
		<div class="toggle_menu">
		<li>
		<a href="javascript:;" class="toggle_menu" target="_self"><img id="img1"
		{if ($section == "10")}
		src="templates/{$SKIN_NAME}/images/minus.gif"
		{else}
		src="templates/{$SKIN_NAME}/images/plus.gif"
		{/if}
	 	onmouseover="this.style.cursor='hand';" WIDTH="9" HEIGHT="9">&nbsp; <strong>{php} echo gettext("AUTO DIALLER");{/php}</strong></a></li>
		<div class="tohide"
		{if ($section == "10")}
		style="">
		{else}
		style="display:none;">
		{/if}
		<ul>
			<li><ul>
					<li><a href="A2B_entity_campaign.php?section=10">{php} echo gettext("Campaign's");{/php}</a></li>
					<li><a href="A2B_entity_phonebook.php?section=10">{php} echo gettext("Phone Book");{/php}</a></li>
					<li><a href="A2B_entity_phonenumber.php?section=10">{php} echo gettext("Phone Number");{/php}</a></li>
					<li><a href="A2B_phonelist_import.php?section=10">{php} echo gettext("Import Phone List");{/php}</a></li>
			</ul></li>
		</ul>
		</div>
		</div>
		{/if}
		
		
		

		<li><a href="#" target="_self"></a></li>
		<ul><li><a href="logout.php?logout=true" target="_parent"><font color="#DD0000"><strong>{php} echo gettext("LOGOUT");{/php}</strong></font></a></li></ul>

	</ul>
	<div id="nav_after"></div>

	<table width="150">
	<tr>
	   <td>
			<a href="{$PAGE_SELF}?language=spanish"><img src="templates/{$SKIN_NAME}/images/flags/es.gif" border="0" title="Spanish" alt="Spanish"></a>
			<a href="{$PAGE_SELF}?language=english"><img src="templates/{$SKIN_NAME}/images/flags/gb.gif" border="0" title="English" alt="English"></a>
			<a href="{$PAGE_SELF}?language=french"><img src="templates/{$SKIN_NAME}/images/flags/fr.gif" border="0" title="French" alt="French"></a>
			<a href="{$PAGE_SELF}?language=romanian"><img src="templates/{$SKIN_NAME}/images/flags/ro.gif" border="0" title="Romanian"alt="Romanian"></a>
			<a href="{$PAGE_SELF}?language=chinese"><img src="templates/{$SKIN_NAME}/images/flags/cn.gif" border="0" title="Chinese" alt="Chinese"></a>
			<a href="{$PAGE_SELF}?language=polish"><img src="templates/{$SKIN_NAME}/images/flags/pl.gif" border="0" title="Polish" alt="Polish"></a>
			<a href="{$PAGE_SELF}?language=italian"><img src="templates/{$SKIN_NAME}/images/flags/it.gif" border="0" title="Italian" alt="Italian"></a>
			<a href="{$PAGE_SELF}?language=russian"><img src="templates/{$SKIN_NAME}/images/flags/ru.gif" border="0" title="russian" alt="russian"></a>
			<a href="{$PAGE_SELF}?language=turkish"><img src="templates/{$SKIN_NAME}/images/flags/tr.gif" border="0" title="Turkish" alt="Turkish"></a>
			<a href="{$PAGE_SELF}?language=portuguese"><img src="templates/{$SKIN_NAME}/images/flags/pt.gif" border="0" title="Portuguese" alt="Portuguese"></a>
			<a href="{$PAGE_SELF}?language=urdu"><img src="templates/{$SKIN_NAME}/images/flags/pk.gif" border="0" title="Urdu" alt="Urdu"></a>
			<a href="{$PAGE_SELF}?language=brazilian"><img src="templates/{$SKIN_NAME}/images/flags/br.gif" border="0" title="Brazilian" alt="Brazilian"></a>
			<a href="{$PAGE_SELF}?language=ukrainian"><img src="templates/{$SKIN_NAME}/images/flags/ua.gif" border="0" title="Ukrainian" alt="Ukrainian"></a>
	   </td>
	</tr>
	
	{if 0 }
	<tr>
		<td>
			<form action="{$PAGE_SELF}" method="post">
				<select name="cssname" class="form_input_select" >
					<option value="default" {checkseleted file="default"}>Default</option>
				</select>
				<input type="submit" value="Change" class="form_input_button" >
			</form>
		</td>
	</tr>
	{/if}
	
	</table>


</div>
<div class="divright">
{/if}
{/if}
