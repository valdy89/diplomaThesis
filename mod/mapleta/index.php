<?php // $Id: index.php,v 1.5 2006/08/28 16:41:20 mark-nielsen Exp $
/**
 * This page lists all the instances of mapleta in a particular course
 *
 * @author
 * @version $Id: index.php,v 1.5 2006/08/28 16:41:20 mark-nielsen Exp $
 * @package mapleta
 **/

/// Replace mapleta with the name of your module

    require_once("../../config.php");
    require_once("lib.php");

    $id = required_param('id', PARAM_INT);   // course

    if (! $course = mapleta_get_record("course", "id", $id)) {
        print_error('course_id_incorrect', 'mapleta');
    }

    require_login($course->id);

    add_to_log($course->id, "mapleta", "view all", "index.php?id=$course->id", "");


/// Get all required stringsmapleta

    $strmapletas = get_string("modulenameplural", "mapleta");
    $strmapleta  = get_string("modulename", "mapleta");


/// Print the header

    if ($course->category) {
        $navigation = "<a href=\"../../course/view.php?id=$course->id\">$course->shortname</a> ->";
    } else {
        $navigation = '';
    }

    print_header("$course->shortname: $strmapletas", "$course->fullname", "$navigation $strmapletas", "", "", true, "", navmenu($course));

/// Get all the appropriate data

    if (! $mapletas = get_all_instances_in_course("mapleta", $course)) {
        notice("There are no $strmapletas defined in the course.", "../../course/view.php?id=$course->id");
        die;
    }

/// Print the list of instances (your module will probably extend this)

    $timenow = time();
    $strname  = get_string("name");
    $strweek  = get_string("week");
    $strtopic  = get_string("topic");

    if ($course->format == "weeks") {
        $table->head  = array ($strweek, $strname);
        $table->align = array ("center", "left");
    } else if ($course->format == "topics") {
        $table->head  = array ($strtopic, $strname);
        $table->align = array ("center", "left", "left", "left");
    } else {
        $table->head  = array ($strname);
        $table->align = array ("left", "left", "left");
    }

    foreach ($mapletas as $mapleta) {
        if (!$mapleta->visible) {
            //Show dimmed if the mod is hidden
            $link = "<a class=\"dimmed\" href=\"view.php?id=$mapleta->coursemodule\">$mapleta->name</a>";
        } else {
            //Show normal if the mod is visible
            $link = "<a href=\"view.php?id=$mapleta->coursemodule\">$mapleta->name</a>";
        }

        if ($course->format == "weeks" or $course->format == "topics") {
            $table->data[] = array ($mapleta->section, $link);
        } else {
            $table->data[] = array ($link);
        }
    }

    echo "<br />";

    print_table($table);

/// Finish the page

echo $OUTPUT->footer();
    
?>
