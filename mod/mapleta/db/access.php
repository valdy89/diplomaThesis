<?php
/**
 * Plugin capabilities
 *
 * @package    mapleta
 */

defined('MOODLE_INTERNAL') || die();

$capabilities = array(

    'mod/mapleta:addinstance' => array(
        'riskbitmask' => RISK_XSS,

        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
        'archetypes' => array(
            'teacher' => CAP_ALLOW,
			'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW
        ),
    ),
);
