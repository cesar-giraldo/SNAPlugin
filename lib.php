<?php


/**
 * Adds specific settings to the settings block
 *
 * @param settings_navigation $nav Current settings navigation object
 * @param context $context Current context
 */
function local_cicei_snatools_extends_settings_navigation(settings_navigation $nav, context $context) {
    // If plugin is enabled and user can use it add links
    if (get_config('local_cicei_snatools', 'enabled') && has_capability('local/cicei_snatools:use', $context)) {

        if ($context->contextlevel == CONTEXT_COURSE) {
            $params = array(
                'searchcontext' => 'course',
                'id' => $context->instanceid
            );
            $url = new moodle_url('/local/cicei_snatools/forum_analysis.php', $params);
            $icon = new pix_icon('icon', '', 'local_cicei_snatools');
            // select node to add link
            $parentnode = $context->instanceid == 1 ? 'frontpage' : 'courseadmin';
            $nav->get($parentnode)->add(get_string('analyze_course', 'local_cicei_snatools'), $url, navigation_node::TYPE_SETTING, null, 'forumcoursesna', $icon);
        }

        if ($context->contextlevel == CONTEXT_MODULE) {
            // Check if this is a forum coursemodule
            $cm = get_coursemodule_from_id('forum', $context->instanceid);
            if ($cm) {
                $d = optional_param('d', 0, PARAM_INT);
                if ($d) {
                    $params = array(
                        'searchcontext' => 'discussion',
                        'id' => $d,
                    );
                } else {
                    $params = array(
                        'searchcontext' => 'forum',
                        'id' => $cm->instance,
                    );
                }

                $text = $d ? get_string('analyze_discussion', 'local_cicei_snatools') : get_string('analyze_forum', 'local_cicei_snatools');
                $url = new moodle_url('/local/cicei_snatools/forum_analysis.php', $params);
                $icon = new pix_icon('icon', '', 'local_cicei_snatools');
                $nav->get('modulesettings')->add($text, $url, navigation_node::TYPE_SETTING, null, 'forummodulesna', $icon);
            }
        }
    }
}
