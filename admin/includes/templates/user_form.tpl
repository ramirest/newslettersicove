<form name="settings" method="post" action="index.php?Page=%%PAGE%%&%%GLOBAL_FormAction%%" onsubmit="return CheckForm();">
	<table cellspacing="0" cellpadding="3" width="95%" align="center">
		<tr>
			<td class="heading1">%%GLOBAL_Heading%%</td>
		</tr>
		<tr>
			<td class="body">%%GLOBAL_Help_Heading%%</td>
		</tr>
		<tr>
			<td>
				%%GLOBAL_Message%%
			</td>
		</tr>
		<tr>
			<td class=body>
				<input class="formbutton" type="button" value="%%LNG_Cancel%%" onClick="ConfirmCancel()">
				<input class="formbutton" type="submit" value="%%LNG_Save%%">
			</td>
		</tr>
		<tr>
			<td><br/>
			<table border="0" cellspacing="0" cellpadding="2" width="100%" class="panel">
				<tr>
					<td class=heading2 colspan=2>
						%%LNG_UserDetails%%
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						%%TPL_Required%%
						%%LNG_UserName%%:
					</td>
					<td>
						<input type="text" name="username" value="%%GLOBAL_UserName%%" class="field250">
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						%%TPL_Required%%
						%%LNG_Password%%:
					</td>
					<td>
						<input type="password" name="ss_p" value="" class="field250">
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						%%TPL_Required%%
						%%LNG_PasswordConfirm%%:
					</td>
					<td>
						<input type="password" name="ss_p_confirm" value="" class="field250">
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						%%TPL_Not_Required%%
						%%LNG_FullName%%:
					</td>
					<td>
						<input type="text" name="fullname" value="%%GLOBAL_FullName%%" class="field250">
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						%%TPL_Required%%
						%%LNG_EmailAddress%%:
					</td>
					<td>
						<input type="text" name="emailaddress" value="%%GLOBAL_EmailAddress%%" class="field250">&nbsp;%%LNG_HLP_EmailAddress%%
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						%%TPL_Required%%
						%%LNG_TimeZone%%:
					</td>
					<td>
						<select name="usertimezone">
							%%GLOBAL_TimeZoneList%%
						</select>&nbsp;&nbsp;&nbsp;%%LNG_HLP_TimeZone%%
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						%%TPL_Not_Required%%
						%%LNG_HTMLFooter%%:
					</td>
					<td>
						<textarea name="htmlfooter" rows="3" cols="28" wrap="virtual">%%GLOBAL_HTMLFooter%%</textarea>&nbsp;&nbsp;&nbsp;%%LNG_HLP_HTMLFooter%%
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						%%TPL_Not_Required%%
						%%LNG_TextFooter%%:
					</td>
					<td>
						<textarea name="textfooter" rows="3" cols="28" wrap="virtual">%%GLOBAL_TextFooter%%</textarea>&nbsp;&nbsp;&nbsp;%%LNG_HLP_TextFooter%%
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						%%TPL_Not_Required%%
						%%LNG_Active%%:
					</td>
					<td>
						<label for="status"><input type="checkbox" name="status" id="status" value="1"%%GLOBAL_StatusChecked%%> %%LNG_YesIsActive%%</label> %%LNG_HLP_Active%%
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						%%TPL_Not_Required%%
						%%LNG_EditOwnSettings%%:
					</td>
					<td>
						<label for="editownsettings"><input type="checkbox" name="editownsettings" id="editownsettings" value="1"%%GLOBAL_EditOwnSettingsChecked%%> %%LNG_YesEditOwnSettings%%</label> %%LNG_HLP_EditOwnSettings%%
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						%%TPL_Not_Required%%
						%%LNG_ShowInfoTips%%:
					</td>
					<td>
						<label for="infotips"><input type="checkbox" name="infotips" id="infotips" value="1"%%GLOBAL_InfoTipsChecked%%> %%LNG_YesShowInfoTips%%</label> %%LNG_HLP_ShowInfoTips%%
					</td>
				</tr>

					<tr>
						<td class="EmptyRow" colspan=2>
							&nbsp;
						</td>
					</tr>
					<tr>
						<td colspan="2" class="heading2">
							%%LNG_UserRestrictions%%
						</td>
					</tr>

					<tr>
					<td class="FieldLabel">
						%%TPL_Not_Required%%
						%%LNG_LimitLists%%:
					</td>
					<td>
						<label for="limitmaxlists"><input type="checkbox" name="limitmaxlists" id="limitmaxlists" value="1"%%GLOBAL_LimitListsChecked%% onClick="DisplayOption(this, 'DisplayMaxLists');"> %%LNG_LimitListsExplain%%</label> %%LNG_HLP_LimitLists%%
					</td>
				</tr>
				<tr id="DisplayMaxLists" style="display: %%GLOBAL_DisplayMaxLists%%;">
					<td class="FieldLabel">
						%%TPL_Not_Required%%
						%%LNG_MaximumLists%%:
					</td>
					<td>
						<input style="margin-left: 25px;" type="text" name="maxlists" value="%%GLOBAL_MaxLists%%" class="field50">&nbsp;%%LNG_HLP_MaximumLists%%
					</td>
				</tr>

				<tr>
					<td class="FieldLabel">
						%%TPL_Not_Required%%
						%%LNG_LimitEmailsPerHour%%:
					</td>
					<td>
						<label for="limitperhour"><input type="checkbox" name="limitperhour" id="limitperhour" value="1"%%GLOBAL_LimitPerHourChecked%% onClick="DisplayOption(this, 'DisplayEmailsPerHour');"> %%LNG_LimitEmailsPerHourExplain%%</label> %%LNG_HLP_LimitEmailsPerHour%%
					</td>
				</tr>
				<tr id="DisplayEmailsPerHour" style="display: %%GLOBAL_DisplayEmailsPerHour%%;">
					<td class="FieldLabel">
						%%TPL_Not_Required%%
						%%LNG_EmailsPerHour%%:
					</td>
					<td>
						<input style="margin-left: 25px;" type="text" name="perhour" value="%%GLOBAL_PerHour%%" class="field50">&nbsp;%%LNG_HLP_EmailsPerHour%%
					</td>
				</tr>

				<tr>
					<td class="FieldLabel">
						%%TPL_Not_Required%%
						%%LNG_LimitEmailsPerMonth%%:
					</td>
					<td>
						<label for="limitpermonth"><input type="checkbox" name="limitpermonth" id="limitpermonth" value="1"%%GLOBAL_LimitPerMonthChecked%% onClick="DisplayOption(this, 'DisplayEmailsPerMonth');"> %%LNG_LimitEmailsPerMonthExplain%%</label> %%LNG_HLP_LimitEmailsPerMonth%%
					</td>
				</tr>
				<tr id="DisplayEmailsPerMonth" style="display: %%GLOBAL_DisplayEmailsPerMonth%%;">
					<td class="FieldLabel">
						%%TPL_Not_Required%%
						%%LNG_EmailsPerMonth%%:
					</td>
					<td>
						<input style="margin-left: 25px;" type="text" name="permonth" value="%%GLOBAL_PerMonth%%" class="field50">&nbsp;%%LNG_HLP_EmailsPerMonth%%
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						%%TPL_Not_Required%%
						%%LNG_LimitMaximumEmails%%:
					</td>
					<td>
						<label for="unlimitedmaxemails"><input type="checkbox" name="unlimitedmaxemails" id="unlimitedmaxemails" value="1" %%GLOBAL_LimitMaximumEmailsChecked%% onClick="DisplayOption(this, 'DisplayEmailsMaxEmails');"> %%LNG_LimitMaximumEmailsExplain%%</label> %%LNG_HLP_LimitMaximumEmails%%
					</td>
				</tr>
				<tr id="DisplayEmailsMaxEmails" style="display: %%GLOBAL_DisplayEmailsMaxEmails%%;">
					<td class="FieldLabel">
						%%TPL_Not_Required%%
						%%LNG_MaximumEmails%%:
					</td>
					<td>
						<input style="margin-left: 25px;" type="text" name="maxemails" value="%%GLOBAL_MaxEmails%%" class="field50">&nbsp;%%LNG_HLP_MaximumEmails%%
					</td>
				</tr>
				<tr>
					<td class="EmptyRow" colspan=2>
						&nbsp;
					</td>
				</tr>
				<tr>
					<td colspan="2" class="heading2">
						%%LNG_SmtpServerIntro%%
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						%%TPL_Not_Required%%
						%%LNG_SmtpServer%%:
					</td>
					<td>
						<select name="smtptype" id="smtptype" class="field250" onChange="ToggleSMTPDetails(true)">
							<option value="0">%%LNG_SmtpDefault%%</option>
							<option value="1">%%LNG_SmtpCustom%%</option>
						</select>
						%%LNG_HLP_SmtpServer%%
					</td>
				</tr>
				<tr id="smtp1" style="display:none">
					<td class="FieldLabel">
						%%TPL_Required%%
						%%LNG_SmtpServerName%%:
					</td>
					<td>
						<input type="text" name="smtpserver" value="%%GLOBAL_SmtpServer%%" class="field250"> %%LNG_HLP_SmtpServerName%%
					</td>
				</tr>
				<tr id="smtp2" style="display:none">
					<td class="FieldLabel">
						%%TPL_Not_Required%%
						%%LNG_SmtpServerUsername%%:
					</td>
					<td>
						<input type="text" name="smtpusername" value="%%GLOBAL_SmtpUsername%%" class="field250"> %%LNG_HLP_SmtpServerUsername%%
					</td>
				</tr>
				<tr id="smtp3" style="display:none">
					<td class="FieldLabel">
						%%TPL_Not_Required%%
						%%LNG_SmtpServerPassword%%:
					</td>
					<td>
						<input type="password" name="smtp_p" value="%%GLOBAL_SmtpPassword%%" class="field250"> %%LNG_HLP_SmtpServerPassword%%
					</td>
				</tr>
				<tr id="smtp4" style="display:none">
					<td class="FieldLabel">
						%%TPL_Not_Required%%
						%%LNG_SmtpServerPort%%:
					</td>
					<td>
						<input type="text" name="smtpport" value="%%GLOBAL_SmtpPort%%" class="field50"> %%LNG_HLP_SmtpServerPort%%
					</td>
				</tr>
				<tr>
					<td colspan="2" class="EmptyRow">
						&nbsp;
					</td>
				</tr>
				<tr>
					<td class=heading2 colspan=2>
						%%LNG_AccessPermissions%%
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						%%TPL_Required%%
						%%LNG_AdministratorType%%:
					</td>
					<td>
						<select name="admintype" onchange="ChangeAdminPermissions(this);" class="field250">
							%%GLOBAL_PrintAdminTypes%%
						</select>
						%%LNG_HLP_AdministratorType%%
					</td>
				</tr>
				<tr id="autoresponder_permissions" style="display: %%GLOBAL_DisplayPermissions%%">
					<td class="FieldLabel">
						%%TPL_Not_Required%%
						%%LNG_AutoresponderPermissions%%:
					</td>
					<td>
						<table border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td width="170">
									<label for="autoresponders_create"><input type="checkbox" value="1" name="permissions[autoresponders][create]" id="autoresponders_create"%%GLOBAL_Permissions_Autoresponders_Create%%>&nbsp;%%LNG_CreateAutoresponders%%</label>
								</td>
								<td width="35">
									&nbsp;
								</td>
								<td width="200">
									<label for="autoresponders_approve"><input type="checkbox" value="1" name="permissions[autoresponders][approve]" id="autoresponders_approve"%%GLOBAL_Permissions_Autoresponders_Approve%%>&nbsp;%%LNG_ApproveAutoresponders%%</label>
								</td>
								<td>
									&nbsp;
								</td>
							</tr>
							<tr>
								<td>
									<label for="autoresponders_edit"><input type="checkbox" value="1" name="permissions[autoresponders][edit]" id="autoresponders_edit"%%GLOBAL_Permissions_Autoresponders_Edit%%>&nbsp;%%LNG_EditAutoresponders%%</label>
								</td>
								<td>
									&nbsp;
								</td>
								<td>
									&nbsp;
								</td>
								<td>
									&nbsp;
								</td>
							</tr>
							<tr>
								<td>
									<label for="autoresponders_delete"><input type="checkbox" value="1" name="permissions[autoresponders][delete]" id="autoresponders_delete"%%GLOBAL_Permissions_Autoresponders_Delete%%>&nbsp;%%LNG_DeleteAutoresponders%%</label>
								</td>
								<td>
									&nbsp;
								</td>
								<td>
									&nbsp;
								</td>
								<td>
									&nbsp;
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr id="form_permissions" style="display: %%GLOBAL_DisplayPermissions%%">
					<td class="FieldLabel">
						%%TPL_Not_Required%%
						%%LNG_FormPermissions%%:
					</td>
					<td>
						<table border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td width="170">
									<label for="forms_create"><input type="checkbox" value="1" name="permissions[forms][create]" id="forms_create"%%GLOBAL_Permissions_Forms_Create%%>&nbsp;%%LNG_CreateForms%%</label>
								</td>
								<td width="35">
									&nbsp;
								</td>
								<td width="200">
									&nbsp;
								</td>
								<td>
									&nbsp;
								</td>
							</tr>
							<tr>
								<td>
									<label for="forms_edit"><input type="checkbox" value="1" name="permissions[forms][edit]" id="forms_edit"%%GLOBAL_Permissions_Forms_Edit%%>&nbsp;%%LNG_EditForms%%</label>
								</td>
								<td>
									&nbsp;
								</td>
								<td>
									&nbsp;
								</td>
								<td>
									&nbsp;
								</td>
							</tr>
							<tr>
								<td>
									<label for="forms_delete"><input type="checkbox" value="1" name="permissions[forms][delete]" id="forms_delete"%%GLOBAL_Permissions_Forms_Delete%%>&nbsp;%%LNG_DeleteForms%%</label>
								</td>
								<td>
									&nbsp;
								</td>
								<td>
									&nbsp;
								</td>
								<td>
									&nbsp;
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr id="list_permissions" style="display: %%GLOBAL_DisplayPermissions%%">
					<td class="FieldLabel">
						%%TPL_Not_Required%%
						%%LNG_ListPermissions%%:
					</td>
					<td>
						<table border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td width="170">
									<label for="lists_create"><input type="checkbox" value="1" name="permissions[lists][create]" id="lists_create"%%GLOBAL_Permissions_Lists_Create%%>&nbsp;%%LNG_CreateMailingLists%%</label>
								</td>
								<td width="35">
									&nbsp;
								</td>
								<td width="200">
									<label for="lists_bounce"><input type="checkbox" value="1" name="permissions[lists][bounce]" id="lists_bounce"%%GLOBAL_Permissions_Lists_Bounce%%>&nbsp;%%LNG_MailingListsBounce%%</label>
								</td>
								<td>
									&nbsp;
								</td>
							</tr>
							<tr>
								<td>
									<label for="lists_edit"><input type="checkbox" value="1" name="permissions[lists][edit]" id="lists_edit"%%GLOBAL_Permissions_Lists_Edit%%>&nbsp;%%LNG_EditMailingLists%%</label>
								</td>
								<td>
									&nbsp;
								</td>
								<td width="200">
									<label for="lists_bouncesettings"><input type="checkbox" value="1" name="permissions[lists][bouncesettings]" id="lists_bouncesettings"%%GLOBAL_Permissions_Lists_Bouncesettings%%>&nbsp;%%LNG_MailingListsBounceSettings%%</label>
								</td>
								<td>
									&nbsp;
								</td>
							</tr>
							<tr>
								<td>
									<label for="lists_delete"><input type="checkbox" value="1" name="permissions[lists][delete]" id="lists_delete"%%GLOBAL_Permissions_Lists_Delete%%>&nbsp;%%LNG_DeleteMailingLists%%</label>
								</td>
								<td>
									&nbsp;
								</td>
								<td>
									&nbsp;
								</td>
								<td>
									&nbsp;
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr id="customfield_permissions" style="display: %%GLOBAL_DisplayPermissions%%">
					<td class="FieldLabel">
						%%TPL_Not_Required%%
						%%LNG_CustomFieldPermissions%%:
					</td>
					<td>
						<table border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td width="170">
									<label for="customfields_create"><input type="checkbox" value="1" name="permissions[customfields][create]" id="customfields_create"%%GLOBAL_Permissions_Customfields_Create%%>&nbsp;%%LNG_CreateCustomFields%%</label>
								</td>
								<td width="35">
									&nbsp;
								</td>
								<td width="200">
									&nbsp;
								</td>
								<td>
									&nbsp;
								</td>
							</tr>
							<tr>
								<td>
									<label for="customfields_edit"><input type="checkbox" value="1" name="permissions[customfields][edit]" id="customfields_edit"%%GLOBAL_Permissions_Customfields_Edit%%>&nbsp;%%LNG_EditCustomFields%%</label>
								</td>
								<td>
									&nbsp;
								</td>
								<td>
									&nbsp;
								</td>
								<td>
									&nbsp;
								</td>
							</tr>
							<tr>
								<td>
									<label for="customfields_delete"><input type="checkbox" value="1" name="permissions[customfields][delete]" id="customfields_delete"%%GLOBAL_Permissions_Customfields_Delete%%>&nbsp;%%LNG_DeleteCustomFields%%</label>
								</td>
								<td>
									&nbsp;
								</td>
								<td>
									&nbsp;
								</td>
								<td>
									&nbsp;
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr id="newsletter_permissions" style="display: %%GLOBAL_DisplayPermissions%%">
					<td class="FieldLabel">
						%%TPL_Not_Required%%
						%%LNG_NewsletterPermissions%%:
					</td>
					<td>
						<table border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td width="170">
									<label for="newsletters_create"><input type="checkbox" value="1" name="permissions[newsletters][create]" id="newsletters_create"%%GLOBAL_Permissions_Newsletters_Create%%>&nbsp;%%LNG_CreateNewsletters%%</label>
								</td>
								<td width="35">
									&nbsp;
								</td>
								<td width="200">
									<label for="newsletters_approve"><input type="checkbox" value="1" name="permissions[newsletters][approve]" id="newsletters_approve"%%GLOBAL_Permissions_Newsletters_Approve%%>&nbsp;%%LNG_ApproveNewsletters%%</label>
								</td>
								<td>
									&nbsp;
								</td>
							</tr>
							<tr>
								<td>
									<label for="newsletters_edit"><input type="checkbox" value="1" name="permissions[newsletters][edit]" id="newsletters_edit"%%GLOBAL_Permissions_Newsletters_Edit%%>&nbsp;%%LNG_EditNewsletters%%</label>
								</td>
								<td>
									&nbsp;
								</td>
								<td>
									<label for="newsletters_send"><input type="checkbox" value="1" name="permissions[newsletters][send]" id="newsletters_send"%%GLOBAL_Permissions_Newsletters_Send%%>&nbsp;%%LNG_SendNewsletters%%</label>
								</td>
								<td>
									&nbsp;
								</td>
							</tr>
							<tr>
								<td>
									<label for="newsletters_delete"><input type="checkbox" value="1" name="permissions[newsletters][delete]" id="newsletters_delete"%%GLOBAL_Permissions_Newsletters_Delete%%>&nbsp;%%LNG_DeleteNewsletters%%</label>
								</td>
								<td>
									&nbsp;
								</td>
								<td>
									&nbsp;
								</td>
								<td>
									&nbsp;
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr id="subscriber_permissions" style="display: %%GLOBAL_DisplayPermissions%%">
					<td class="FieldLabel">
						%%TPL_Not_Required%%
						%%LNG_SubscriberPermissions%%:
					</td>
					<td>
						<table border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td width="170">
									<label for="subscribers_add"><input type="checkbox" value="1" name="permissions[subscribers][add]" id="subscribers_add"%%GLOBAL_Permissions_Subscribers_Add%%>&nbsp;%%LNG_AddSubscribers%%</label>
								</td>
								<td width="35">
									&nbsp;
								</td>
								<td width="200">
									<label for="subscribers_import"><input type="checkbox" value="1" name="permissions[subscribers][import]" id="subscribers_import"%%GLOBAL_Permissions_Subscribers_Import%%>&nbsp;%%LNG_ImportSubscribers%%</label>
								</td>
								<td>
									&nbsp;
								</td>
							</tr>
							<tr>
								<td>
									<label for="subscribers_edit"><input type="checkbox" value="1" name="permissions[subscribers][edit]" id="subscribers_edit"%%GLOBAL_Permissions_Subscribers_Edit%%>&nbsp;%%LNG_EditSubscribers%%</label>
								</td>
								<td>
									&nbsp;
								</td>
								<td>
									<label for="subscribers_export"><input type="checkbox" value="1" name="permissions[subscribers][export]" id="subscribers_export"%%GLOBAL_Permissions_Subscribers_Export%%>&nbsp;%%LNG_ExportSubscribers%%</label>
								</td>
								<td>
									&nbsp;
								</td>
							</tr>
							<tr>
								<td>
									<label for="subscribers_delete"><input type="checkbox" value="1" name="permissions[subscribers][delete]" id="subscribers_delete"%%GLOBAL_Permissions_Subscribers_Delete%%>&nbsp;%%LNG_DeleteSubscribers%%
								</td>
								<td>
									&nbsp;
								</td>
								<td>
									<label for="subscribers_banned"><input type="checkbox" value="1" name="permissions[subscribers][banned]" id="subscribers_banned"%%GLOBAL_Permissions_Subscribers_Banned%%>&nbsp;%%LNG_ManageBannedSubscribers%%</label>
								</td>
								<td>
									&nbsp;
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr id="template_permissions" style="display: %%GLOBAL_DisplayPermissions%%">
					<td class="FieldLabel">
						%%TPL_Not_Required%%
						%%LNG_TemplatePermissions%%:
					</td>
					<td>
						<table border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td width="170">
									<label for="templates_create"><input type="checkbox" value="1" name="permissions[templates][create]" id="templates_create"%%GLOBAL_Permissions_Templates_Create%%>&nbsp;%%LNG_CreateTemplates%%</label>
								</td>
								<td width="35">
									&nbsp;
								</td>
								<td width="200">
									<label for="templates_approve"><input type="checkbox" value="1" name="permissions[templates][approve]" id="templates_approve"%%GLOBAL_Permissions_Templates_Approve%%>&nbsp;%%LNG_ApproveTemplates%%</label>
								</td>
								<td>
									&nbsp;
								</td>
							</tr>
							<tr>
								<td>
									<label for="templates_edit"><input type="checkbox" value="1" name="permissions[templates][edit]" id="templates_edit"%%GLOBAL_Permissions_Templates_Edit%%>&nbsp;%%LNG_EditTemplates%%</label>
								</td>
								<td>
									&nbsp;
								</td>
								<td>
									<label for="templates_global"><input type="checkbox" value="1" name="permissions[templates][global]" id="templates_global"%%GLOBAL_Permissions_Templates_Global%%>&nbsp;%%LNG_GlobalTemplates%%</label>
								</td>
								<td>
									%%LNG_HLP_GlobalTemplates%%
								</td>
							</tr>
							<tr>
								<td>
									<label for="templates_delete"><input type="checkbox" value="1" name="permissions[templates][delete]" id="templates_delete"%%GLOBAL_Permissions_Templates_Delete%%>&nbsp;%%LNG_DeleteTemplates%%</label>
								</td>
								<td>
									&nbsp;
								</td>
								<td>
									<label for="templates_builtin"><input type="checkbox" value="1" name="permissions[templates][builtin]" id="templates_builtin"%%GLOBAL_Permissions_Templates_Builtin%%>&nbsp;%%LNG_BuiltInTemplates%%</label>
								</td>
								<td>
									&nbsp;
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr id="statistics_permissions" style="display: %%GLOBAL_DisplayPermissions%%">
					<td class="FieldLabel">
						%%TPL_Not_Required%%
						%%LNG_StatisticsPermissions%%:
					</td>
					<td>
						<table border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td width="170">
									<label for="statistics_newsletter"><input type="checkbox" value="1" name="permissions[statistics][newsletter]" id="statistics_newsletter"%%GLOBAL_Permissions_Statistics_Newsletter%%>&nbsp;%%LNG_NewsletterStatistics%%</label>
								</td>
								<td width="35">

								</td>
								<td>
									<label for="statistics_user"><input type="checkbox" value="1" name="permissions[statistics][user]" id="statistics_user"%%GLOBAL_Permissions_Statistics_User%%>&nbsp;%%LNG_UserStatistics%%</label>
								</td>
								<td>

								</td>
							</tr>
							<tr>
								<td width="170">
									<label for="statistics_autoresponder"><input type="checkbox" value="1" name="permissions[statistics][autoresponder]" id="statistics_autoresponder"%%GLOBAL_Permissions_Statistics_Autoresponder%%>&nbsp;%%LNG_AutoresponderStatistics%%</label>
								</td>
								<td width="35">

								</td>
								<td>
									<label for="statistics_list"><input type="checkbox" value="1" name="permissions[statistics][list]" id="statistics_list"%%GLOBAL_Permissions_Statistics_List%%>&nbsp;%%LNG_ListStatistics%%</label>
								</td>
								<td>

								</td>
						</table>
					</td>
				</tr>
				<tr id="admin_permissions" style="display: %%GLOBAL_DisplayPermissions%%">
					<td class="FieldLabel">
						%%TPL_Not_Required%%
						%%LNG_AdminPermissions%%:
					</td>
					<td>
						<table border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td width="170">
									<label for="system_system"><input type="checkbox" value="1" name="permissions[system][system]" id="system_system"%%GLOBAL_Permissions_System_System%%>&nbsp;%%LNG_SystemAdministrator%%</label>
								</td>
								<td width="35">
									%%LNG_HLP_SystemAdministrator%%
								</td>
								<td width="200">
									<label for="system_list"><input type="checkbox" value="1" name="permissions[system][list]" id="system_list"%%GLOBAL_Permissions_System_List%%>&nbsp;%%LNG_ListAdministrator%%</label>
								</td>
								<td>
									%%LNG_HLP_ListAdministrator%%
								</td>
							</tr>
							<tr>
								<td>
									<label for="system_user"><input type="checkbox" value="1" name="permissions[system][user]" id="system_user"%%GLOBAL_Permissions_System_User%%>&nbsp;%%LNG_UserAdministrator%%</label>
								</td>
								<td>
									&nbsp;
								</td>
								<td width="200">
									<label for="system_template"><input type="checkbox" value="1" name="permissions[system][template]" id="system_template"%%GLOBAL_Permissions_System_List%%>&nbsp;%%LNG_TemplateAdministrator%%</label>
								</td>
								<td>
									%%LNG_HLP_TemplateAdministrator%%
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr id="other_permissions" style="display: %%GLOBAL_DisplayPermissions%%">
					<td class="FieldLabel">
						%%TPL_Not_Required%%
						%%LNG_OtherPermissions%%:
					</td>
					<td>
						<table border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td width="170">
									<label for="user_smtp"><input type="checkbox" value="1" name="permissions[user][smtp]" id="user_smtp"%%GLOBAL_Permissions_User_Smtp%%>&nbsp;%%LNG_User_SMTP%%</label>
								</td>
								<td width="35">
									%%LNG_HLP_User_SMTP%%
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td class="EmptyRow" colspan=2>
						&nbsp;
					</td>
				</tr>

				<tr>
					<td class=heading2 colspan=2>
						%%LNG_MailingListAccessPermissions%%
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						%%TPL_Required%%
						%%LNG_ChooseMailingLists%%:
					</td>
					<td height="35">
						<select name="listadmintype" onchange="DisplayLists();">
							%%GLOBAL_PrintListAdminList%%
						</select>&nbsp;&nbsp;%%LNG_HLP_ChooseMailingLists%%
					</td>
				</tr>
				<tr id="PrintLists" style="display: %%GLOBAL_ListDisplay%%">
					<td class="FieldLabel">
						%%TPL_Not_Required%%
					</td>
					<td style="padding-bottom:10px">
						%%GLOBAL_PrintMailingLists%%
					</td>
				</tr>
				<tr>
					<td class="EmptyRow" colspan=2>
						&nbsp;
					</td>
				</tr>

				<tr>
					<td class=heading2 colspan=2>
						%%LNG_TemplateAccessPermissions%%
					</td>
				</tr>
				<tr>
					<td class="FieldLabel" height="35">
						%%TPL_Required%%
						%%LNG_ChooseTemplates%%:
					</td>
					<td>
						<select name="templateadmintype" onchange="DisplayTemplates();">
							%%GLOBAL_PrintTemplateAdminList%%
						</select>&nbsp;&nbsp;%%LNG_HLP_ChooseTemplates%%
					</td>
				</tr>
				<tr id="PrintTemplates" style="display: %%GLOBAL_TemplateDisplay%%">
					<td class="FieldLabel">
						%%TPL_Not_Required%%
					</td>
					<td style="padding-bottom:10px">
						%%GLOBAL_PrintTemplateLists%%
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td height="30" valign="top">
						<input class="formbutton" type="button" value="%%LNG_Cancel%%" onClick="ConfirmCancel()">
						<input class="formbutton" type="submit" value="%%LNG_Save%%">
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<script language="javascript">
	var form = document.forms[0];

	function DisplayLists() {
		if (form.listadmintype.selectedIndex == 0) {
			document.getElementById("PrintLists").style.display = "none";
		}
		if (form.listadmintype.selectedIndex == 1) {
			document.getElementById("PrintLists").style.display = "";
		}
	}

	function DisplayTemplates() {
		if (form.templateadmintype.selectedIndex == 0) {
			document.getElementById("PrintTemplates").style.display = "none";
		}
		if (form.templateadmintype.selectedIndex == 1) {
			document.getElementById("PrintTemplates").style.display = "";
		}
	}

	function ChangeAdminPermissions(obj) {
		idx = obj.selectedIndex;

		// if its custom permissions show the big list.
		if (idx == 5) {
			document.getElementById('autoresponder_permissions').style.display = '';
			document.getElementById('form_permissions').style.display = '';
			document.getElementById('list_permissions').style.display = '';
			document.getElementById('customfield_permissions').style.display = '';
			document.getElementById('newsletter_permissions').style.display = '';
			document.getElementById('subscriber_permissions').style.display = '';
			document.getElementById('template_permissions').style.display = '';
			document.getElementById('statistics_permissions').style.display = '';
			document.getElementById('admin_permissions').style.display = '';
			document.getElementById('other_permissions').style.display = '';
		} else {
			document.getElementById('autoresponder_permissions').style.display = 'none';
			document.getElementById('form_permissions').style.display = 'none';
			document.getElementById('list_permissions').style.display = 'none';
			document.getElementById('customfield_permissions').style.display = 'none';
			document.getElementById('newsletter_permissions').style.display = 'none';
			document.getElementById('subscriber_permissions').style.display = 'none';
			document.getElementById('template_permissions').style.display = 'none';
			document.getElementById('statistics_permissions').style.display = 'none';
			document.getElementById('admin_permissions').style.display = 'none';
			document.getElementById('other_permissions').style.display = 'none';
		}

		var form = document.forms[0];
		for (i = 0; i < form.length; i++) {
			var name = form[i].name;
			if (name.match(/permissions/)) {
				if (idx == 0) {
					form[i].checked = true;
				}
				if (idx == 1) {
					if (name.match(/list/)) {
						form[i].checked = true;
					} else {
						form[i].checked = false;
					}
				}
				if (idx == 2) {
					if (name.match(/newsletter/)) {
						form[i].checked = true;
					} else {
						form[i].checked = false;
					}
				}
				if (idx == 3) {
					if (name.match(/template/)) {
						form[i].checked = true;
					} else {
						form[i].checked = false;
					}
				}
				if (idx == 4) {
					if (name.match(/user/)) {
						form[i].checked = true;
					} else {
						form[i].checked = false;
					}
				}
			}
		}
	}

	function ConfirmCancel()
	{
		if(confirm('%%LNG_ConfirmCancel%%'))
		{
			document.location.href='index.php?Page=Users';
		}
		else
		{
			return false;
		}
	}

	function CheckForm() {
		f = document.forms[0];

		if (f.username.value == "") {
			alert("%%LNG_EnterUsername%%");
			f.username.focus();
			return false;
		}

		if (f.ss_p.value != "") {
			if (f.ss_p_confirm.value == "") {
				alert("%%LNG_PasswordConfirmAlert%%");
				f.ss_p_confirm.focus();
				return false;
			}

			if (f.ss_p.value != f.ss_p_confirm.value) {
				alert("%%LNG_PasswordsDontMatch%%");
				f.ss_p_confirm.select();
				f.ss_p_confirm.focus();
				return false;
			}
		}

		if (f.emailaddress.value == "") {
			alert("%%LNG_EnterEmailaddress%%");
			f.emailaddress.focus();
			return false;
		}

		return true;
	}

	function ToggleSMTPDetails(Override)
	{
		var state = "%%GLOBAL_CustomSmtpServer_Display%%";

		if(Override) {
			state = document.getElementById("smtptype").options[document.getElementById("smtptype").selectedIndex].value;
		}

		var sel = document.getElementById("smtptype");
		var s1 = document.getElementById("smtp1");
		var s2 = document.getElementById("smtp2");
		var s3 = document.getElementById("smtp3");
		var s4 = document.getElementById("smtp4");

		if(state == 0) {
			vis = "none";
		} else {
			vis = "";
		}

		sel.selectedIndex = state;
		s1.style.display = vis;
		s2.style.display = vis;
		s3.style.display = vis;
		s4.style.display = vis;
	}

	ToggleSMTPDetails(false);
	DisplayLists();
	DisplayTemplates();

	function DisplayOption(fld, element) {
		if (fld.checked) {
			document.getElementById(element).style.display = 'none';
		} else {
			document.getElementById(element).style.display = '';
		}
	}
</script>
</form>
