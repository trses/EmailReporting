<?php
auth_reauthenticate( );
access_ensure_global_level( config_get( 'manage_plugin_threshold' ) );

html_page_top( plugin_lang_get( 'plugin_title' ) );

print_manage_menu();

require_once( plugin_config_get( 'path_erp', NULL, TRUE ) . 'core/config_api.php' );
require_once( 'custom_field_api.php' );

$t_this_page = 'manage_rule';
ERP_print_menu( $t_this_page );

?>

<br />
<table align="center" class="width75" cellspacing="1">

<tr>
	<td class="left">
<?php
	echo '<p>' . plugin_lang_get( 'rule_wildcards' ) . '<br />' . nl2br( plugin_lang_get( 'rule_wildcards_help' ) ) . '</p>';
	echo '<p>' . plugin_lang_get( 'rule_conditions' ) . '<br />' . nl2br( plugin_lang_get( 'rule_conditions_help' ) ) . '</p>';
	echo '<p>' . plugin_lang_get( 'rule_actions' ) . '<br />' . nl2br( plugin_lang_get( 'rule_actions_help' ) ) . '</p>';
	echo '<p>' . plugin_lang_get( 'rule_exceptions' ) . '<br />' . nl2br( plugin_lang_get( 'rule_exceptions_help' ) ) . '</p>';
?>
	</td>
</tr>

</table>
<br />

<form action="<?php echo plugin_page( $t_this_page . '_edit' )?>" method="post">

<?php

$t_rules = plugin_config_get( 'rules' );

$f_rule_action = gpc_get_string( 'rule_action', 'add' );
$f_select_rule = gpc_get_int( 'select_rule', -1 );

$t_rule = array();

if ( $f_rule_action !== 'add' )
{
	if ( isset( $t_rules[ $f_select_rule ] ) )
	{
		$t_rule = $t_rules[ $f_select_rule ];

		// Add "Copy of" text if necessary to rules being copied
		if ( $f_rule_action === 'copy' )
		{
			$t_rule[ 'description' ] = plugin_lang_get( 'copy_of') . ' ' . $t_rule[ 'description' ];
		}
	}
	else
	{
		$f_rule_action = 'add';
	}
}

ERP_output_config_option( 'rule_action', 'hidden', $f_rule_action );
ERP_output_config_option( 'select_rule', 'hidden', $f_select_rule );

?>
<table align="center" class="width50 nowrap" cellspacing="1">
<?php

ERP_output_config_option( 'rule_settings', 'header', 'manage_mailbox' );

ERP_output_config_option( 'enabled', 'boolean', $t_rule );
ERP_output_config_option( 'description', 'string', $t_rule );

ERP_output_config_option( NULL, 'empty' );
ERP_output_config_option( 'rule_conditions', 'header' );
ERP_output_config_option( 'cond_issue_creation_method', 'dropdown_any', $t_rule, 'ERP_print_descriptions_option_list', array( 'emailreporting', 'mantisbt' ) );// TODO think about name and think this over whether selected mailboxes should be here or on the manage mailboxes page
ERP_output_config_option( 'cond_mailbox', 'dropdown_multiselect_any', $t_rule, 'ERP_print_descriptions_option_list', plugin_config_get( 'mailboxes' ) );
ERP_output_config_option( 'cond_issue_issue_issuenote', 'dropdown_any', $t_rule, 'ERP_print_descriptions_option_list', array( 'newnote', 'newissue' ) ); //TODO note or new issue
ERP_output_config_option( 'cond_issue_reporter', 'dropdown_multiselect_any', $t_rule, 'ERP_print_reporter_option_list' );
ERP_output_config_option( 'cond_issue_project', 'dropdown_multiselect_any', $t_rule, 'ERP_print_projects_option_list' );
ERP_output_config_option( 'cond_issue_category', 'dropdown_multiselect_any', $t_rule, 'ERP_print_global_category_option_list' );
ERP_output_config_option( 'cond_issue_priority', 'dropdown_multiselect_any', $t_rule, 'ERP_print_priority_option_list' );
ERP_output_config_option( 'cond_issue_summary', 'string', $t_rule );
ERP_output_config_option( 'cond_issue_description', 'string', $t_rule );

ERP_output_config_option( NULL, 'empty' );
ERP_output_config_option( 'rule_actions', 'header' );
ERP_output_config_option( 'act_issue_category', 'dropdown_any', $t_rule, 'ERP_print_global_category_option_list' );
ERP_output_config_option( 'act_issue_tag', 'dropdown_multiselect', $t_rule, 'ERP_print_tag_attach_option_list' );
ERP_output_config_option( 'act_issue_custom_field', 'custom', $t_rule, 'ERP_print_custom_fields' );

ERP_output_config_option( NULL, 'empty' );
ERP_output_config_option( 'rule_exceptions', 'header' );
ERP_output_config_option( 'excep_issue_summary', 'string', $t_rule );
ERP_output_config_option( 'excep_issue_description', 'string', $t_rule );

ERP_output_config_option( $f_rule_action . '_action', 'submit' );

?>
</table>
</form>

<br />

<form action="<?php echo plugin_page( $t_this_page )?>" method="post">
<table align="center" class="width50 nowrap" cellspacing="1">
<?php

ERP_output_config_option( 'rules', 'header', 'manage_mailbox' );

ERP_output_config_option( 'rule_action', 'radio_buttons', $f_rule_action, 'ERP_print_rule_action_radio_buttons', $t_rules );
ERP_output_config_option( 'select_rule', 'dropdown', $f_select_rule, 'ERP_print_descriptions_option_list', $t_rules );
ERP_output_config_option( 'disabled', 'empty' );

ERP_output_config_option( 'select_rule', 'submit' );

?>
</table>
<form>
	
<?php
html_page_bottom( __FILE__ );
?>