<?php
require_once($CFG->dirroot .
'/mod/mapleta/lib.php');

class block_mapleta_course_tools extends block_base {

	function init() {
		$this->title= get_string('blockname', 'block_mapleta_course_tools');
		$this->version= 20090706;
	}
	
	function applicable_formats() {
		global $CFG;
		global $COURSE;
		global $USER;
		
		if (mapleta_is_administrator($COURSE->id)) {
			return array('site' => false, 'course' => true, 'my' => true);
		} else {
			return array('site' => false, 'course' => true, 'my' => false);
		}
	}
	
	function get_content() {
		global $CFG;
		global $COURSE;
		global $USER;

		if($this->content != null) {
			return $this->content;
		}

		$this->content= new stdClass;

		if(mapleta_get_role($COURSE->id) == 'UNSET') {
			$this->content->text= '';
			$this->content->footer= '';
			return $this->content;
		}

		if(!mapleta_ws_ping()) {
			
			$this->content->text= 	'<div class="info"><font color="red">' . 
									get_string('mapleta_not_available', 'block_mapleta_course_tools') . 
									'</font></div><br/>';
			$this->content->footer= '<div class="info">' . 
									get_string('contact_sys_admin', 'block_mapleta_course_tools') . 
									'</div><br/>';
			return $this->content;
		}

		$course_mapping= mapleta_get_record('mapleta_course_map', 'courseid', $COURSE->id);

		if($COURSE->format != 'site' && !$course_mapping) {
			if(mapleta_is_administrator($COURSE->id) || mapleta_is_teacher($COURSE->id)) {
				
				$this->content->footer= '<div class="info"><a href="' . 
										$CFG->wwwroot . 
										'/mod/mapleta/course_mapping.php?id=' . 
										$COURSE->id . 
										'&action=create">' . 
										get_string('map_the_course', 'block_mapleta_course_tools') . 
										'</a></div>';
										
				$this->content->text= 	'<div class="info"><font color="red">' . 
										get_string('course_is_not_mapped', 'block_mapleta_course_tools') . 
										'</font></div><br/>';
			}
		} else {
			if(mapleta_is_administrator($COURSE->id)) {
				if($COURSE->format != 'site') {
				
					$params= "wsActionID=classHome&wsClassId=$course_mapping->classid&wsCourseId=$course_mapping->courseid";
				
					$this->content->text= 	'<div class="info"><a href="' . 
											$CFG->wwwroot . 
											'/mod/mapleta/launcher_form.php?' . 
											$params . 
											'" onclick="this.target=\'mapleta\'; return openpopup(\'/mod/mapleta/launcher_form.php?' . 
											$params . 
											'\', \'mapleta\', \'menubar=0,location=0,scrollbars,status,resizable,width=1024,height=800\', 0);">' . 
											get_string('class_homepage', 'block_mapleta_course_tools') . 
											'</a></div><br/><hr>';
//											. '<a href="'.$CFG->wwwroot.'/mod/mapleta/grade.php">Grade</a>';
											
					$this->content->footer= '<div class="info"><a href="' . 
											$CFG->wwwroot . 
											'/mod/mapleta/course_mapping.php?id=' . 
											$COURSE->id . 
											'&action=delete">' . 
											get_string('remove_course_mapping', 'block_mapleta_course_tools') . 
											'</a></div>';
				} else {
				
					$params= "wsActionID=systemHome&wsClassId=-1&wsCourseId=$COURSE->id";
				
					$this->content->footer= '<a href="' . 
											$CFG->wwwroot . 
											'/mod/mapleta/launcher_form.php?' . 
											$params . 
											'" onclick="this.target=\'mapleta\'; return openpopup(\'/mod/mapleta/launcher_form.php?' . 
											$params . 
											'\', \'mapleta\', \'menubar=0,location=0,scrollbars,status,resizable,width=1024,height=800\', 0);">' . 
											get_string('system_homepage', 'block_mapleta_course_tools') . 
											'</a>';

				}
			} else
				if(mapleta_is_teacher($COURSE->id) && $COURSE->format != 'site') {
				
					$params= "wsActionID=classHome&wsClassId=$course_mapping->classid&wsCourseId=$course_mapping->courseid";
				
					$this->content->text= 	'<div class="info"><a href="' . 
											$CFG->wwwroot . 
											'/mod/mapleta/launcher_form.php?' . 
											$params . 
											'" onclick="this.target=\'mapleta\'; return openpopup(\'/mod/mapleta/launcher_form.php?' . 
											$params . 
											'\', \'mapleta\', \'menubar=0,location=0,scrollbars,status,resizable,width=1024,height=800\', 0);">' . 
											get_string('class_homepage', 'block_mapleta_course_tools') . 
											'</a></div><br/><hr>';
											
					$this->content->footer= '<div class="info"><a href="' . 
											$CFG->wwwroot . 
											'/mod/mapleta/course_mapping.php?id=' . 
											$COURSE->id . 
											'&action=delete">' . 
											get_string('remove_course_mapping', 'block_mapleta_course_tools') . 
											'</a></div>';
				} else
					if(mapleta_is_proctor($COURSE->id) && $COURSE->format != 'site') {
				
						$params= "wsActionID=proctorTools&wsClassId=$course_mapping->classid&wsCourseId=$course_mapping->courseid";
				
						$this->content->text= 	'<div class="info"><a href="' . 
												$CFG->wwwroot . 
												'/mod/mapleta/launcher_form.php?' . 
												$params . 
												'" onclick="this.target=\'mapleta\'; return openpopup(\'/mod/mapleta/launcher_form.php?' . 
												$params . 
												'\', \'mapleta\', \'menubar=0,location=0,scrollbars,status,resizable,width=1024,height=800\', 0);">' . 
												get_string('proctor_tools', 'block_mapleta_course_tools') . 
												'</a></div><br/>';
						$this->content->footer= '';
					} else
						if(mapleta_is_student($COURSE->id) && $COURSE->format != 'site') {

							$params= "wsActionID=gradeBook&wsClassId=$course_mapping->classid&wsCourseId=$course_mapping->courseid&showStudents=true&actionID=4&uid=$USER->username";
				
							$this->content->text= 	'<div class="info"><a href="' . 
													$CFG->wwwroot . 
													'/mod/mapleta/launcher_form.php?' . 
													$params . 
													'" onclick="this.target=\'mapleta\'; return openpopup(\'/mod/mapleta/launcher_form.php?' . 
													$params . 
													'\', \'mapleta\', \'menubar=0,location=0,scrollbars,status,resizable,width=1024,height=800\', 0);">' . 
													get_string('gradebook', 'block_mapleta_course_tools') . 
													'</a></div><br/>';
							$this->content->footer= '';
						} else {
							$this->content->text= '';
							$this->content->footer= '';
						}
		}

		return $this->content;
	}

	function instance_allow_config() {
		return false;
	}
}
?>