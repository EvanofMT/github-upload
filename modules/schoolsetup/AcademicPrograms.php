<?php

#**************************************************************************
#  OASIS is a free student information system for public and non-public 
#  schools from Africa's Hope based on openSIS from Open Solutions for Education, Inc. web: www.os4ed.com
#
#  OASIS is  web-based, open source, and comes packed with features that 
#  include student demographic info, scheduling, grade book, attendance, 
#  report cards, eligibility, transcripts, parent portal, 
#  student portal and more.   
#
#  Visit the openSIS web site at http://www.opensis.com to learn more.
#  If you have question regarding openSIS, please send 
#  an email to info@os4ed.com.
#
#  This program is released under the terms of the GNU General Public License as  
#  published by the Free Software Foundation, version 2 of the License. 
#  See license.txt.
#
#  This program is distributed in the hope that it will be useful,
#  but WITHOUT ANY WARRANTY; without even the implied warranty of
#  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#  GNU General Public License for more details.
#
#  You should have received a copy of the GNU General Public License
#  along with this program.  If not, see <http://www.gnu.org/licenses/>.
#
#***************************************************************************************

include('lang/language.php');
include('../../RedirectModulesInc.php');

/*
unset($_SESSION['_REQUEST_vars']['academic_program_id']);
unset($_SESSION['_REQUEST_vars']['program_course_id']);
unset($_SESSION['_REQUEST_vars']['course_period_id']);
*/
foreach ($_REQUEST['tables']['academic_program_courses'][$_REQUEST['program_course_id']] as $in => $dt) {
    $_REQUEST['tables']['academic_program_courses'][$_REQUEST['program_course_id']][$in] = str_replace("\'", "'", $dt);

//    $_REQUEST['tables']['academic_program_courses'][$_REQUEST['program_course_id']][$in] = str_replace("'", "\'", $dt);
}
foreach ($_REQUEST['tables']['course_periods'][$_REQUEST['cp_id']] as $in => $dt) {
    $_REQUEST['tables']['course_periods'][$_REQUEST['cp_id']][$in] = str_replace("\'", "'", $dt);
}

if ($_REQUEST['error'] == 'Blocked_assoc') {

    echo "<font color=red><b>"._cannotModifyThisCoursePeriodAsItHasAssociation."</b></font>";
    unset($_REQUEST['error']);
    $msgFlag=1;
}


if ($_REQUEST['action'] == 'delete') {
    if (scheduleAssociation($_REQUEST['course_period_id'])) {
        $scheduleAssociation = true;
    }
    if (gradeAssociation($_REQUEST['course_period_id'])) {
        $gradeAssociation = true;
    }
    if (!$scheduleAssociation && !$gradeAssociation) {
        if (DeletePromptCommon('course period')) {
            $checking_days1 = DBGet(DBQuery('SELECT COUNT(*) AS TOTAL FROM course_period_var WHERE course_period_id=' . $_REQUEST[course_period_id]));
            if ($checking_days1[1]['TOTAL'] > 1) {
                DBQuery("DELETE FROM course_period_var WHERE id=$_REQUEST[cpv_id]");
                $data_sql = "SELECT period_id,days FROM course_period_var WHERE course_period_id=$_REQUEST[course_period_id]";
                $data_RET = DBGet(DBQuery($data_sql));
                foreach ($data_RET as $count => $data) {
                    if ($data['PERIOD_ID'] != '') {
                        $period = '';
                        $qry = "SELECT short_name FROM school_periods WHERE period_id=$data[PERIOD_ID]";
                        $period = DBGet(DBQuery($qry));
                        $period = $period[1];
                        $p.=$period['SHORT_NAME'];
                    }
                    if ($data['DAYS'] != '')
                        $d.=$data['DAYS'];
                }
                $cp_data_sql = "SELECT mp,short_name,marking_period_id,teacher_id FROM course_periods WHERE course_period_id=$_REQUEST[course_period_id]";
                $cp_data_RET = DBGet(DBQuery($cp_data_sql));

                $cp_data_RET = $cp_data_RET[1];
                if ($cp_data_RET['MP'] != 'FY' && $_REQUEST['date_range'] == 'mp') {
                    if ($cp_data_RET['MP'] == 'SEM')
                        $table = ' school_semesters';
                    if ($cp_data_RET['MP'] == 'QTR')
                        $table = '  school_quarters';

                    $mp_sql = "SELECT short_name FROM $table WHERE marking_period_id=$cp_data_RET[MARKING_PERIOD_ID]";
                    $mp = DBGet(DBQuery($mp_sql));
                    $mp = ' - ' . $mp[1]['SHORT_NAME'];
                } else
                    $mp = 'Custom';

                $teacher_sql = "SELECT first_name,last_name,middle_name FROM staff WHERE staff_id=$cp_data_RET[TEACHER_ID]";
                $teacher_RET = DBGet(DBQuery($teacher_sql));
                $teacher_RET = $teacher_RET[1];
                $teacher.=$teacher_RET['FIRST_NAME'];
                if ($teacher_RET['MIDDLE_NAME'] != '')
                    $teacher.=' ' . $teacher_RET['MIDDLE_NAME'];
                $teacher.=' ' . $teacher_RET['LAST_NAME'];

                $title_full = $p . $mp . ' - ' . $d . ' - ' . $cp_data_RET['SHORT_NAME'] . ' - ' . $teacher;
                $update_title_sql = "UPDATE course_periods SET title='" . str_replace("'", "''", trim($title_full)) . "' WHERE course_period_id=$_REQUEST[course_period_id]";
                DBQuery($update_title_sql);
            }
            else {
                echo '<font color=red>'._unableToDeleteDataCoursePeriodShouldHaveAtleastOnePeriod.'</font>';
            }
            unset($_REQUEST['action']);
        }
    } else {
        echo '<font color=red>'._unableToDeleteCoursePeriodBecauseItHasAssociation.'</font>';
    }
}
if (isset($_SESSION['conflict'])) {
    echo '<font color=red>' . $_SESSION['conflict'] . '</font>';
    $_REQUEST['tables'] = $_SESSION['tables'];
    $_REQUEST['conflict'] = 'y';
    unset($_SESSION['conflict']);
    unset($_SESSION['tables']);
}
if ($_REQUEST['modfunc'] != 'delete' && !$_REQUEST['academic_program_id']) {
    $subjects_RET = DBGet(DBQuery("SELECT SUBJECT_ID,TITLE FROM academic_programs WHERE SCHOOL_ID='" . UserSchool() . "' AND SYEAR='" . UserSyear() . "'"));
    if (count($subjects_RET) == 1)
        $_REQUEST['academic_program_id'] = $subjects_RET[1]['academic_program_id'];
}

if (clean_param($_REQUEST['course_modfunc'], PARAM_ALPHAMOD) == 'search') {
    PopTable('header',  _search);
    echo "<FORM name=F1 id=F1 action=Modules.php?modname=$_REQUEST[modname]&modfunc=$_REQUEST[modfunc]&course_modfunc=search method=POST>";
    echo '<TABLE><TR><TD><INPUT type=text class=form-control name=search_term value="' . $_REQUEST['search_term'] . '"></TD><TD><INPUT type=submit class="btn btn-primary m-l-10" value='._search.' onclick=\'formload_ajax("F1")\';></TD></TR></TABLE>';
    echo '</FORM>';
    PopTable('footer');

    if ($_REQUEST['search_term']) {
        $programs_RET = DBGet(DBQuery("SELECT ID,NAME FROM academic_programs WHERE (UPPER(name) LIKE '%" . strtoupper($_REQUEST['search_term']) . "%' AND SYEAR='" . UserSyear() . "' AND SCHOOL_ID='" . UserSchool() . "'"));
        $courses_RET = DBGet(DBQuery("SELECT ACADEMIC_PROGRAM_ID,COURSE_ID FROM academic_program_courses WHERE (UPPER(TITLE) LIKE '%" . strtoupper($_REQUEST['search_term']) . "%' AND SYEAR='" . UserSyear() . "' AND SCHOOL_ID='" . UserSchool() . "'"));
        //$subjects_RET = DBGet(DBQuery("SELECT SUBJECT_ID,TITLE FROM academic_programs WHERE (UPPER(TITLE) LIKE '%" . strtoupper($_REQUEST['search_term']) . "%' OR UPPER(SHORT_NAME) = '" . strtoupper($_REQUEST['search_term']) . "') AND SYEAR='" . UserSyear() . "' AND SCHOOL_ID='" . UserSchool() . "'"));
        //$courses_RET = DBGet(DBQuery("SELECT SUBJECT_ID,COURSE_ID,TITLE FROM courses WHERE (UPPER(TITLE) LIKE '%" . strtoupper($_REQUEST['search_term']) . "%' OR UPPER(SHORT_NAME) = '" . strtoupper($_REQUEST['search_term']) . "') AND SYEAR='" . UserSyear() . "' AND SCHOOL_ID='" . UserSchool() . "'"));
        //$periods_RET = DBGet(DBQuery("SELECT c.SUBJECT_ID,cp.COURSE_ID,cp.COURSE_PERIOD_ID,cp.TITLE FROM course_periods cp,courses c WHERE cp.COURSE_ID=c.COURSE_ID AND (UPPER(cp.TITLE) LIKE '%" . strtoupper($_REQUEST['search_term']) . "%' OR UPPER(cp.SHORT_NAME) = '" . strtoupper($_REQUEST['search_term']) . "') AND cp.SYEAR='" . UserSyear() . "' AND cp.SCHOOL_ID='" . UserSchool() . "'"));

        echo '<div class="row">';
        echo '<div class="col-md-4">';
        echo '<div class="panel panel-white">';
        //Academic Program section (Top level)
        $link['TITLE']['link'] = "Modules.php?modname=$_REQUEST[modname]&modfunc=$_REQUEST[modfunc]";
        $link['TITLE']['variables'] = array('academic_program_id' => 'ACADEMIC_PROGRAM_ID'); //for Program List (like the Divisions list on Courses.php)
        ListOutput($programs_RET, array('TITLE' => ''._academicProgram.''), ''._academicProgram.'', ''._academicPrograms.'', $link, array(), array('search' =>false, 'save' =>false));
        echo '</div>'; //.panel-white
        echo '</div>'; //.col-md-4
        //Academic Program Course selection section (Second Level)
        echo '<div class="col-md-4">';
        echo '<div class="panel panel-white">';
        $link['TITLE']['link'] = "Modules.php?modname=$_REQUEST[modname]&modfunc=$_REQUEST[modfunc]";
        $link['TITLE']['variables'] = array('academic_program_id' => 'ACADEMIC_PROGRAM_ID', 'program_course_id' => 'PROGRAM_COURSE_ID');
        ListOutput($courses_RET, array('TITLE' => ''._course.''), ''._course.'', ''._courses.'', $link, array(), array('search' =>false, 'save' =>false));
        echo '</div>'; //.panel-white
        echo '</div>'; //.col-md-4
        
    }
}

if (clean_param($_REQUEST['course_modfunc'], PARAM_ALPHAMOD) == 'standard_search') {
    PopTable('header', ''._search.'');
    echo "<FORM name=F1 id=F1 action=Modules.php?modname=$_REQUEST[modname]&modfunc=$_REQUEST[modfunc]&course_modfunc=search method=POST>";
    echo '<TABLE><TR><TD><INPUT type=text class=cell_floating name=search_term value="' . $_REQUEST['search_term'] . '"></TD><TD><INPUT type=submit class=btn_medium value='._search.' onclick=\'formload_ajax("F1")\';></TD></TR></TABLE>';
    echo '</FORM>';
    PopTable('footer');

    if ($_REQUEST['search_term']) {
        $programs_RET = DBGet(DBQuery("SELECT ACADEMIC_PROGRAM_ID,NAME FROM academic_programs WHERE (UPPER(name) LIKE '%" . strtoupper($_REQUEST['search_term']) . "%' AND SYEAR='" . UserSyear() . "' AND SCHOOL_ID='" . UserSchool() . "'"));
        $courses_RET = DBGet(DBQuery("SELECT PROGRAM_COURSE_ID,COURSE_ID FROM academic_program_courses WHERE (UPPER(TITLE) LIKE '%" . strtoupper($_REQUEST['search_term']) . "%' AND SYEAR='" . UserSyear() . "' AND SCHOOL_ID='" . UserSchool() . "'"));
        /*
        $subjects_RET = DBGet(DBQuery("SELECT SUBJECT_ID,TITLE FROM academic_programs WHERE (UPPER(TITLE) LIKE '%" . strtoupper($_REQUEST['search_term']) . "%' OR UPPER(SHORT_NAME) = '" . strtoupper($_REQUEST['search_term']) . "') AND SYEAR='" . UserSyear() . "' AND SCHOOL_ID='" . UserSchool() . "'"));
        $courses_RET = DBGet(DBQuery("SELECT SUBJECT_ID,COURSE_ID,TITLE FROM courses WHERE (UPPER(TITLE) LIKE '%" . strtoupper($_REQUEST['search_term']) . "%' OR UPPER(SHORT_NAME) = '" . strtoupper($_REQUEST['search_term']) . "') AND SYEAR='" . UserSyear() . "' AND SCHOOL_ID='" . UserSchool() . "'"));
        $periods_RET = DBGet(DBQuery("SELECT c.SUBJECT_ID,cp.COURSE_ID,cp.COURSE_PERIOD_ID,cp.TITLE FROM course_periods cp,courses c WHERE cp.COURSE_ID=c.COURSE_ID AND (UPPER(cp.TITLE) LIKE '%" . strtoupper($_REQUEST['search_term']) . "%' OR UPPER(cp.SHORT_NAME) = '" . strtoupper($_REQUEST['search_term']) . "') AND cp.SYEAR='" . UserSyear() . "' AND cp.SCHOOL_ID='" . UserSchool() . "'"));
        */

        echo '<TABLE><TR><TD valign=top>';
        $link['TITLE']['link'] = "Modules.php?modname=$_REQUEST[modname]&modfunc=$_REQUEST[modfunc]";
        $link['TITLE']['variables'] = array('academic_program_id' => 'ACADEMIC_PROGRAM_ID');
        ListOutput($programs_RET, array('TITLE' => ''._academicProgram.''), ''._academicProgram.'', ''._academicPrograms.'', $link, array(), array('search' =>false, 'save' =>false));
        echo '</TD><TD valign=top>';
        $link['TITLE']['link'] = "Modules.php?modname=$_REQUEST[modname]&modfunc=$_REQUEST[modfunc]";
        $link['TITLE']['variables'] = array('academic_program_id' => 'ACADEMIC_PROGRAM_ID', 'program_course_id' => 'PROGRAM_COURSE_ID');
        ListOutput($courses_RET, array('TITLE' => ''._course.''), ''._course.'', ''._courses.'', $link, array(), array('search' =>false, 'save' =>false));
        echo '</TD>';
        /*echo '<TD valign=top>';
        $link['TITLE']['link'] = "Modules.php?modname=$_REQUEST[modname]&modfunc=$_REQUEST[modfunc]";
        $link['TITLE']['variables'] = array('subject_id' => 'SUBJECT_ID', 'course_id' => 'COURSE_ID', 'course_period_id' => 'COURSE_PERIOD_ID');
        ListOutput($periods_RET, array('TITLE' => ''._coursePeriod.''), ''._coursePeriod.'', ''._coursePeriods.'', $link, array(), array('search' =>false, 'save' =>false));
        echo '</TD>';
        */
        echo '</TR></TABLE>';
    }
}
// UPDATING
if (clean_param($_REQUEST['tables'], PARAM_NOTAGS) && ($_POST['tables'] || $_REQUEST['ajax']) && AllowEdit()) {
    //where array values TABLE => COLUMN
    $where = array(
        'academic_programs' => 'ACADEMIC_PROGRAM_ID',
        'academic_program_courses' => 'PROGRAM_COURSE_ID',
    );

    if ($_REQUEST['program_course_id'] == 'new') {
        if ($_REQUEST['tables']['academic_program_courses']['new']['ACADEMIC_PROGRAM_ID'] != '') {
            $_REQUEST['academic_program_id'] = $_REQUEST['tables']['academic_program_courses']['new']['ACADEMIC_PROGRAM_ID'];
            unset($_REQUEST['tables']['academic_program_courses']['new']['ACADEMIC_PROGRAM_ID']);
        }
        foreach ($_REQUEST['tables']['academic_program_courses']['new'] as $ci => $cd) {
            $_REQUEST['tables']['academic_program_courses']['new'][$ci] = str_replace('"', '""', $cd);
        }
    }
    if ($_REQUEST['program_course_id'] != 'new' && $_REQUEST['program_course_id'] != '') {
        foreach ($_REQUEST['tables']['academic_program_courses'][$_REQUEST['program_course_id']] as $ci => $cd) {
            $_REQUEST['tables']['academic_program_courses'][$_REQUEST['program_course_id']][$ci] = str_replace('"', '""', $cd);
            $_REQUEST['tables']['academic_program_courses'][$_REQUEST['program_course_id']][$ci] = str_replace("\'", "'", $cd);
        }
    }

    $school_fy_dates = DBGet(DBQuery('select start_date,end_date from school_years where syear = ' . UserSyear() . ' and school_id = ' . UserSchool()));
    $school_fy_bdate = $school_fy_dates[1]['START_DATE'];
    $school_fy_edate = $school_fy_dates[1]['END_DATE'];

    /*if ($assoc_err) {
        echo $assoc_err;
        unset($assoc_err);
    }*/
  
    //------------course period name with period and days------//
    if ($not_pass == false){
        unset($_REQUEST['tables']);
    }
}

if (clean_param($_REQUEST['modfunc'], PARAM_ALPHAMOD) == 'delete' && AllowEdit()) {
    unset($sql);
    //$course_period_id = paramlib_validation($colmn = PERIOD_ID, $_REQUEST[course_period_id]);
    $program_course_id = paramlib_validation($colmn = PERIOD_ID, $_REQUEST['program_course_id']);
    $academic_program_id = paramlib_validation($colmn = PERIOD_ID, $_REQUEST['academic_program_id']);

    if (clean_param($_REQUEST['program_course_id'], PARAM_ALPHANUM)) {
        $table = 'academic_program_courses';
        //$course_period = DBGet(DBQuery("SELECT COURSE_PERIOD_ID FROM course_periods WHERE COURSE_ID='$program_course_id'"));
        foreach ($course_period as $course1){
            if ($course1['COURSE_PERIOD_ID'] == '') {
                $sql[] = "DELETE FROM academic_program_courses WHERE program_course_id='$program_course_id'";
                //$extra_sql = "SELECT COURSE_PERIOD_ID FROM course_periods WHERE COURSE_ID='$program_course_id'";
                //$result_sql = DBGet(DBQuery($extra_sql));
                //$sql[] = "UPDATE course_periods SET PARENT_ID=NULL WHERE PARENT_ID = '" . $result_sql . "'";
                //$sql[] = "DELETE FROM course_periods WHERE COURSE_ID='$program_course_id'";
                //$sql[] = "DELETE FROM schedule WHERE COURSE_ID='$program_course_id'";
                //$sql[] = "DELETE FROM schedule_requests WHERE COURSE_ID='$program_course_id'";
                //$sql[] = "DELETE FROM  gradebook_assignment_types WHERE COURSE_ID='$program_course_id'";
                //$sql[] = "DELETE FROM  gradebook_assignments WHERE COURSE_ID='$program_course_id'";
                if (DeletePromptCommon($table)) {
                    if (BlockDelete($table)) {
                        DBQuery($sql);
                        unset($_REQUEST['modfunc']);
                    }
                }
            }
        
            if ($course1['COURSE_PERIOD_ID'] != '') {
                PopTable('header', ''._unableToDelete.'');
                DrawHeaderHome('<font color=red>'._courseCannotBeDeleted.'</font>');
                echo '<div align=right><a href=Modules.php?modname=schoolsetup/Courses.php&subject_id=' .  $academic_program_id. '&course_id=' . $program_course_id . ' style="text-decoration:none"><b>'._backToCourse.'</b></a></div>';
                PopTable('footer');
            } else {
                if (DeletePromptCommon($table)) {
                    if (BlockDelete($table)) {
                        $sql[] = "DELETE FROM academic_program_courses WHERE program_course_id='$program_course_id'";
                        //$extra_sql = "SELECT COURSE_PERIOD_ID FROM course_periods WHERE COURSE_ID='$program_course_id'";
                        //$result_sql = DBGet(DBQuery($extra_sql));
                        //$sql[] = "UPDATE course_periods SET PARENT_ID=NULL WHERE PARENT_ID = '" . $result_sql . "'";
                        //$sql[] = "DELETE FROM course_periods WHERE COURSE_ID='$program_course_id'";
                        //$sql[] = "DELETE FROM schedule WHERE COURSE_ID='$program_course_id'";
                        //$sql[] = "DELETE FROM schedule_requests WHERE COURSE_ID='$program_course_id'";
                        foreach ($sql as $query){
                            DBQuery($query);
                        }
                        unset($_REQUEST['modfunc']);
                        unset($_REQUEST['program_course_id']);
                    }
                }
            }
        }
    } elseif (clean_param($_REQUEST['academic_program_id'], PARAM_ALPHANUM)) {
        $table = 'academic_programs';
        $academic_program = DBGet(DBQuery("SELECT PROGRAM_COURSE_ID FROM academic_program_courses WHERE academic_program_id='$academic_program_id'"));
        foreach ($academic_program as $academic_program1)
            if ($academic_program['program_course_id'] == '') {
                $sql[] = "DELETE FROM academic_programs WHERE ACADEMIC_PROGRAM_ID='$academic_program_id'";
                $courses = DBGet(DBQuery("SELECT PROGRAM_COURSE_ID FROM academic_program_courses WHERE ACADEMIC_PROGRAM_ID='$academic_program_id'"));
                if (count($courses)) {
                    foreach ($courses as $course) {
                        $sql[] = "DELETE FROM academic_program_courses WHERE PROGRAM_COURSE_ID={$course['PROGRAM_COURSE_ID']}";
                    }
                }
            }
        if ($subject1['program_course_id'] != '') {
            PopTable('header', ''._unableToDelete.'');
            DrawHeaderHome('<font color=red>'._divisionCannotBeDeleted.'</font>');
            echo '<div align=right><a href=Modules.php?modname=schoolsetup/Courses.php&subject_id=' .  $academic_program_id. ' style="text-decoration:none"><b>'._backToSubject.'</b></a></div>';
            PopTable('footer');
        } else {
            if (DeletePromptCommon($table)) {
                if (BlockDelete($table)) {
                    $sql[] = "DELETE FROM academic_programs WHERE ACADEMIC_PROGRAM_ID='$academic_program_id'";
                    $courses = DBGet(DBQuery("SELECT PROGRAM_COURSE_ID FROM academic_program_courses WHERE ACADEMIC_PROGRAM_ID='$academic_program_id'"));
                    if (count($courses)) {
                        foreach ($courses as $course) {
                            $sql[] = "DELETE FROM academic_program_courses WHERE PROGRAM_COURSE_ID='$course[PROGRAM_COURSE_ID]'";
                        }
                    }
                    foreach ($sql as $query){
                        DBQuery($query);
                    }
                    unset($_REQUEST['modfunc']);
                    unset($_REQUEST['academic_program_id']);
                }
            }
        }
    }
}

/*
if ($_REQUEST['modfunc'] == 'detail') {
    if ($_POST['button'] == 'Save' && $_REQUEST['mode'] == 'add') {
        $conflict = VerifyBlockedSchedule($_REQUEST['values'], $_REQUEST['course_period_id'], 'cpv');
        $_SESSION['block_schedule_err'] = $conflict;
    }
    if ($_POST['button'] == 'Save' && $_REQUEST['mode'] == 'edit') {

        $title_val = DBGet(DBQuery("SELECT PERIOD_ID,ROOM_ID FROM course_period_var WHERE course_period_id='" . $_REQUEST['course_period_id'] . "'"));

        if ($_REQUEST['values']['PERIOD_ID'])
            $col_blk['PERIOD_ID'] = $_REQUEST['values']['PERIOD_ID'];
        else
            $col_blk['PERIOD_ID'] = $title_val[1]['PERIOD_ID'];
        if ($_REQUEST['values']['ROOM_ID'])
            $col_blk['ROOM_ID'] = $_REQUEST['values']['ROOM_ID'];
        else
            $col_blk['ROOM_ID'] = $title_val[1]['ROOM_ID'];

        $conflict = VerifyBlockedSchedule($col_blk, $_REQUEST['course_period_id'], 'cpv', true);
        $_SESSION['block_schedule_err'] = $conflict;
    }

    if ($_POST['button'] == 'Save' && $conflict === true) {

        if ($_REQUEST['mode'] == 'add') {
            
            $chek_assoc = DBGet(DBQuery('SELECT COUNT(*) as REC_EX FROM schedule WHERE COURSE_PERIOD_ID=' . $_REQUEST['course_period_id'] . ' AND (START_DATE<=\'' . date('Y-m-d') . '\' AND (END_DATE IS NULL OR END_DATE=\'0000-00-00\' OR END_DATE>=\'' . date('Y-m-d') . '\' ))'));
            if ($chek_assoc[1]['REC_EX'] == 0) {
                $weekday = date('l', strtotime($_REQUEST['meet_date']));
                if ($weekday == 'Sunday')
                    $d = 'U';
                if ($weekday == 'Monday')
                    $d = 'M';
                if ($weekday == 'Tuesday')
                    $d = 'T';
                if ($weekday == 'Wednesday')
                    $d = 'W';
                if ($weekday == 'Thursday')
                    $d = 'H';
                if ($weekday == 'Friday')
                    $d = 'F';
                if ($weekday == 'Saturday')
                    $d = 'S';
                $sql = "INSERT INTO course_period_var ";
                $fields = 'COURSE_PERIOD_ID,COURSE_PERIOD_DATE,DAYS,';
                $values = "'" . $_REQUEST['course_period_id'] . "','" . $_REQUEST['meet_date'] . "','" . $d . "',";
                $go = false;
                foreach ($_REQUEST['values'] as $column => $value) {
                    if (trim($value)) {
                        $value = paramlib_validation($column, $value);
                        $fields .= $column . ',';
                        $values .= '"' . str_replace("", "", trim($value)) . '",';
                        $go = true;
                    }
                }
                $sql .= '(' . substr($fields, 0, -1) . ') values(' . substr($values, 0, -1) . ')';
            } else {
                $error = 'Blocked_assoc';
            }
        } elseif ($_REQUEST['mode'] == 'edit') {
            $chek_assoc = DBGet(DBQuery('SELECT COUNT(*) as REC_EX FROM schedule WHERE COURSE_PERIOD_ID=' . $_REQUEST['course_period_id'] . ' AND (START_DATE<=\'' . date('Y-m-d') . '\' AND (END_DATE IS NULL OR END_DATE=\'0000-00-00\' OR END_DATE>=\'' . date('Y-m-d') . '\' ))'));
            if ($chek_assoc[1]['REC_EX'] == 0) {
                if ($_REQUEST['values']['PERIOD_ID'] != '' && $_REQUEST['values']['DOES_ATTENDANCE'] == '') {
                    $check_sp = DBGet(DBQuery('SELECT * FROM school_periods WHERE PERIOD_ID=' . $_REQUEST['values']['PERIOD_ID']));
                    $check_sp = $check_sp[1];
                    $cp_per_id = DBGet(DBQuery('SELECT * FROM course_period_var WHERE ID=' . $_REQUEST['cpv_id']));
                    $cp_per_id = $cp_per_id[1];
                    if ($check_sp['PERIOD_ID'] != $cp_per_id['PERIOD_ID']) {
                        if ($check_sp['ATTENDANCE'] == '' && $cp_per_id['DOES_ATTENDANCE'] == 'Y') {
                            $_REQUEST['values']['DOES_ATTENDANCE'] = '';
                        }
                    }
                } elseif ($_REQUEST['values']['PERIOD_ID'] == '' && $_REQUEST['values']['DOES_ATTENDANCE'] != '') {

                    $cp_per_id = DBGet(DBQuery('SELECT * FROM course_period_var WHERE ID=' . $_REQUEST['cpv_id']));
                    $cp_per_id = $cp_per_id[1];
                    $check_sp = DBGet(DBQuery('SELECT * FROM school_periods WHERE PERIOD_ID=' . $cp_per_id['PERIOD_ID']));
                    $check_sp = $check_sp[1];
                    if ($check_sp['ATTENDANCE'] == '') {
                        $_REQUEST['values']['DOES_ATTENDANCE'] = '';
                    }
                }
                if ($_REQUEST['values']['PERIOD_ID'] != '' && $_REQUEST['values']['DOES_ATTENDANCE'] != '') {
                    $check_sp = DBGet(DBQuery('SELECT * FROM school_periods WHERE PERIOD_ID=' . $_REQUEST['values']['PERIOD_ID']));
                    $check_sp = $check_sp[1];
                    if ($check_sp['ATTENDANCE'] == '') {
                        $_REQUEST['values']['DOES_ATTENDANCE'] = '';
                    }
                }
                $sql = "UPDATE course_period_var SET ";
                $go = false;
                foreach ($_REQUEST['values'] as $column => $value) {
                    $value = paramlib_validation($column, $value);
                    if ($column == 'DOES_ATTENDANCE' && $value == '') {
                        $sql .= $column . '=null,';
                    } else
                        $sql .= $column . '="' . str_replace("", "", trim($value)) . '",';
                    $go = true;
                }
                $sql = substr($sql, 0, -1);
                $sql.=" WHERE COURSE_PERIOD_ID={$_REQUEST['course_period_id']} AND course_period_date='" . $_REQUEST['meet_date'] . "' AND ID='" . $_REQUEST['cpv_id'] . "'";
                $sql;
            }
            else {
                $check_take_attn = DBGet(DBQuery('SELECT COUNT(*) AS TOTAL FROM attendance_period WHERE COURSE_PERIOD_ID=' . $_REQUEST['course_period_id'] . ' AND school_date=\'' . $_REQUEST['meet_date'] . '\''));
                $check_miss_attn = DBGet(DBQuery('SELECT COUNT(*) AS TOTAL FROM missing_attendance WHERE COURSE_PERIOD_ID=' . $_REQUEST['course_period_id'] . ' AND school_date=\'' . $_REQUEST['meet_date'] . '\''));
                if ($check_take_attn[1]['TOTAL'] > 0 || $check_miss_attn[1]['TOTAL'] > 0)
                    $error = 'Blocked_assoc';

                else {
                    if ($_REQUEST['values']['PERIOD_ID'] != '' && $_REQUEST['values']['DOES_ATTENDANCE'] == '') {
                        $check_sp = DBGet(DBQuery('SELECT * FROM school_periods WHERE PERIOD_ID=' . $_REQUEST['values']['PERIOD_ID']));
                        $check_sp = $check_sp[1];
                        $cp_per_id = DBGet(DBQuery('SELECT * FROM course_period_var WHERE ID=' . $_REQUEST['cpv_id']));
                        $cp_per_id = $cp_per_id[1];
                        if ($check_sp['PERIOD_ID'] != $cp_per_id['PERIOD_ID']) {
                            if ($check_sp['ATTENDANCE'] == '' && $cp_per_id['DOES_ATTENDANCE'] == 'Y' && isset($_REQUEST['values']['DOES_ATTENDANCE'])) {
                                $_REQUEST['values']['DOES_ATTENDANCE'] = '';
                            }
                        }
                    } elseif ($_REQUEST['values']['PERIOD_ID'] == '' && $_REQUEST['values']['DOES_ATTENDANCE'] != '') {

                        $cp_per_id = DBGet(DBQuery('SELECT * FROM course_period_var WHERE ID=' . $_REQUEST['cpv_id']));
                        $cp_per_id = $cp_per_id[1];
                        $check_sp = DBGet(DBQuery('SELECT * FROM school_periods WHERE PERIOD_ID=' . $cp_per_id['PERIOD_ID']));
                        $check_sp = $check_sp[1];
                        if ($check_sp['ATTENDANCE'] == '') {
                            $_REQUEST['values']['DOES_ATTENDANCE'] = '';
                        }
                    }
                    if ($_REQUEST['values']['PERIOD_ID'] != '' && $_REQUEST['values']['DOES_ATTENDANCE'] != '') {
                        $cp_per_id = DBGet(DBQuery('SELECT * FROM course_period_var WHERE ID=' . $_REQUEST['cpv_id']));
                        $cp_per_id = $cp_per_id[1];
                        $check_sp = DBGet(DBQuery('SELECT * FROM school_periods WHERE PERIOD_ID=' . $cp_per_id['PERIOD_ID']));
                        $check_sp = $check_sp[1];
                        if ($check_sp['ATTENDANCE'] == '') {
                            $_REQUEST['values']['DOES_ATTENDANCE'] = '';
                        }
                    }
                    $sql = "UPDATE course_period_var SET ";
                    $go = false;
                    foreach ($_REQUEST['values'] as $column => $value) {
                        $value = paramlib_validation($column, $value);
                        if ($column == 'DOES_ATTENDANCE' && $value == '') {
                            $sql .= $column . '=NULL,';
                            $go = true;
                        } elseif ($column == 'DOES_ATTENDANCE' && $value != '') {
                            $sql .= $column . '="' . str_replace("", "", trim($value)) . '",';
                            $go = true;
                        } elseif (($column == 'PERIOD_ID' && $value != '') || ($column == 'ROOM_ID' && $value != ''))
                            $error = 'Blocked_period_room';
                    }
                    $sql = substr($sql, 0, -1);
                    $sql.=" WHERE COURSE_PERIOD_ID={$_REQUEST['course_period_id']} AND course_period_date='" . $_REQUEST['meet_date'] . "' AND ID='" . $_REQUEST['cpv_id'] . "'";
                }
            }
        }
        if ($go) {

            if (!attendanceAssociation($_REQUEST['course_period_id']))
                DBQuery($sql);
            if ($_REQUEST['course_period_id'] != 'new') {
                $schedule_type = DBGet(DBQuery('SELECT SCHEDULE_TYPE FROM course_periods WHERE COURSE_PERIOD_ID=' . $_REQUEST['course_period_id']));
                if ($schedule_type[1]['SCHEDULE_TYPE'] == 'BLOCKED') {
                    $check_does_attendanceRE = DBGet(DBQuery('SELECT COUNT(*) as REC_EX FROM course_period_var WHERE COURSE_PERIOD_ID=' . $_REQUEST['course_period_id'] . ' AND DOES_ATTENDANCE=\'Y\' '));
                    if ($check_does_attendanceRE[1]['REC_EX'] == 0)
                        DBQuery('UPDATE course_periods SET HALF_DAY=NULL WHERE COURSE_PERIOD_ID=' . $_REQUEST['course_period_id']);
                }
            }
        }
        DBQuery("UPDATE course_period_var cpv,school_periods sp SET cpv.start_time=sp.start_time,cpv.end_time=sp.end_time WHERE sp.period_id=cpv.period_id AND course_period_id=$_REQUEST[course_period_id]");
        unset($_REQUEST['values']);
        unset($_SESSION['_REQUEST_vars']['values']);
        if ($error == 'Blocked_assoc')
            echo '<SCRIPT language=javascript>window.location.href = "Modules.php?modname=' . $_REQUEST['modname'] . '&error=Blocked_assoc&subject_id=' . $_REQUEST['academic_program_id'] . '&course_id=' . $_REQUEST['program_course_id'] . '&course_period_id=' . $_REQUEST['course_period_id'] . '&month=' . date(strtotime($_REQUEST['meet_date'])) . '"; window.close();</script>';

        elseif ($error == 'Blocked_period_room') {
            echo '<SCRIPT language=javascript>window.location.href = "Modules.php?modname=' . $_REQUEST['modname'] . '&error=Blocked_period_room&subject_id=' . $_REQUEST['academic_program_id'] . '&course_id=' . $_REQUEST['program_course_id'] . '&course_period_id=' . $_REQUEST['course_period_id'] . '&month=' . date(strtotime($_REQUEST['meet_date'])) . '"; window.close();</script>';
        } else
            echo '<SCRIPT language=javascript>window.location.href = "Modules.php?modname=' . $_REQUEST['modname'] . '&subject_id=' . $_REQUEST['academic_program_id'] . '&course_id=' . $_REQUEST['program_course_id'] . '&course_period_id=' . $_REQUEST['course_period_id'] . '&month=' . date(strtotime($_REQUEST['meet_date'])) . '"; window.close();</script>';
    } else {
        echo '<SCRIPT language=javascript>window.location.href = "Modules.php?modname=' . $_REQUEST['modname'] . '&subject_id=' . $_REQUEST['academic_program_id'] . '&course_id=' . $_REQUEST['program_course_id'] . '&course_period_id=' . $_REQUEST['course_period_id'] . '&month=' . date(strtotime($_REQUEST['meet_date'])) . '"; window.close();</script>';
    }
}*/

if (!$_REQUEST['modfunc'] && !$_REQUEST['course_modfunc'] && !$_REQUEST['action']) {
    DrawBC(""._schoolSetup." > " . ProgramTitle());
    $sql = "SELECT SUBJECT_ID,TITLE FROM academic_programs WHERE SCHOOL_ID='" . UserSchool() . "' AND SYEAR='" . UserSyear() . "' ORDER BY TITLE";
    $QI = DBQuery($sql);
    $subjects_RET = DBGet($QI);
    $credit_div = 'hide';
    if (AllowEdit())
        $delete_button = "<INPUT type=button class=\"btn btn-default\" value="._delete." onClick='javascript:window.location=\"Modules.php?modname=$_REQUEST[modname]&modfunc=delete&subject_id=$_REQUEST[subject_id]&course_id=$_REQUEST[course_id]&course_period_id=$_REQUEST[course_period_id]\"'>";
    // ADDING & EDITING FORM

    //CONTINUE HERE EVAN
    if (clean_param($_REQUEST['program_course_id'], PARAM_ALPHANUM)) {
        $grade_level_RET = DBGet(DBQuery("SELECT ID,TITLE FROM school_gradelevels WHERE school_id='" . UserSchool() . "'"));
        if ($_REQUEST['program_course_id'] != 'new') {
            $sql = "SELECT c.TITLE,sg.TITLE AS GRADE_LEVEL_TITLE,c.SHORT_NAME,GRADE_LEVEL
                        FROM courses c LEFT JOIN school_gradelevels sg ON c.grade_level=sg.id
                        WHERE COURSE_ID='$_REQUEST[course_id]'";
            $QI = DBQuery($sql);
            $RET = DBGet($QI);
            $RET = $RET[1];
            $title = trim($RET['TITLE'] . ' - ' . $RET['GRADE_LEVEL_TITLE'], '- ');
        } else {
            $sql = "SELECT TITLE
                        FROM academic_programs
                        WHERE ACADEMIC_PROGRAM_ID='$_REQUEST[subject_id]' ORDER BY TITLE";
            $QI = DBQuery($sql);
            $RET = DBGet($QI);
            $title = $RET[1]['TITLE'] . ' - '._newCourse.'';
            unset($delete_button);
            unset($RET);
        }

        echo '<div class="panel panel-default">';
        echo "<FORM name=F3 id=F3 class=form-horizontal action=Modules.php?modname=$_REQUEST[modname]&subject_id=$_REQUEST[subject_id]&course_id=$_REQUEST[course_id] method=POST>";
        echo '<div class="panel-heading">
                <h6 class="panel-title">' . $title . '</h6>
                <div class="heading-elements">' . $delete_button . SubmitButton(_save, '', 'id="setupCourseBtn" class="btn btn-primary" onclick="return formcheck_Timetable_course_F3(this);"') . '</div>
            </div>';
        echo '<hr class="no-margin"/>';
        echo '<div class="panel-body">';
        foreach ($grade_level_RET as $grade_level)
            $grade_levels[$grade_level['ID']] = $grade_level['TITLE'];
        $header .= '<div class="row">';
        $header .= '<div class="col-lg-4">';
        //ADDED by Africa's Hope - Evan Stewart  //add text-danger and * to label, add 'required' to TextInput options parameter
        $header .= '<div class="form-group"><label class="col-md-4 control-label text-right">'._title.' :<span class="text-danger">*</span></label><div class="col-md-8">' . TextInput($RET['TITLE'], 'tables[courses][' . $_REQUEST['program_course_id'] . '][TITLE]', '', 'id=course_title class=cell_mod_wide required') . '</div></div>';
        $header .= '</div>'; //.col-md-8
        $header .= '<div class="col-lg-4">';
        //ADDED by Africa's Hope - Evan Stewart  //add text-danger and * to label, add 'required' to TextInput options parameter
        $header .= '<div class="form-group"><label class="col-md-4 control-label text-right">'._shortName.' :<span class="text-danger">*</span></label><div class="col-md-8">' . TextInput($RET['SHORT_NAME'], 'tables[courses][' . $_REQUEST['program_course_id'] . '][SHORT_NAME]', '', 'id=short_name class=form-control required') . '</div></div>';
        $header .= '</div>'; //.col-md-4
        $header .= '</div>'; //.row

        $header .= '<div class="row">';
        $header .= '<div class="col-lg-4">';
        $header .= "<input type=hidden value=" . $_REQUEST['program_course_id'] . " id=course_id_div />";
        //ADDED by Africa's Hope - Evan Stewart  //add text-danger and * to label, add 'required' to SelectInput options parameter
        $header .= '<div class="form-group"><label class="col-md-4 control-label text-right">'._gradeLevel.' :<span class="text-danger">*</span></label><div class="col-md-8">' . SelectInput($RET['GRADE_LEVEL'], 'tables[courses][' . $_REQUEST['program_course_id'] . '][GRADE_LEVEL]', '', $grade_levels,'','','','required') . '</div></div>';
        $header .= '</div>'; //.col-md-4
        $header .= '<div class="col-lg-4">';
        foreach ($subjects_RET as $type)
            $options[$type['academic_program_id']] = $type['TITLE'];

        $header .= '<div class="form-group"><label class="col-md-4 control-label text-right">'._division.' :</label><div class="col-md-8">' . SelectInput($RET['academic_program_id'] ? $RET['academic_program_id'] : $_REQUEST['academic_program_id'], 'tables[courses][' . $_REQUEST['program_course_id'] . '][SUBJECT_ID]', '', $options, false) . '</div></div>';
        $header .= '</div>'; //.col-md-4
        $header .= '</div>'; //.row

        // $header .= '<div class="row">';
        // $header .= '<div class="col-lg-8">';
        // $header .= '<div class="form-group"><label class="col-md-2 control-label text-right">Description :</label><div class="col-md-9">' . TextareaInput($RET['DESCRIPTION'], 'tables[courses][' . $_REQUEST['program_course_id'] . '][DESCRIPTION]', '', 'size=44 class=form-control') . '</div></div>';
        // $header .= '</div>'; //.col-md-4
        // $header .= '</div>'; //.row

        DrawHeaderHome($header);
        echo '</div>'; //.panel-body
        echo '</FORM>';
        echo '</div>'; //.panel.panel-default
    } elseif (clean_param($_REQUEST['academic_program_id'], PARAM_ALPHANUM)) {
        if ($_REQUEST['academic_program_id'] != 'new') {
            $sql = "SELECT TITLE
                        FROM academic_programs
                        WHERE ACADEMIC_PROGRAM_ID='$_REQUEST[subject_id]'";
            $QI = DBQuery($sql);
            $RET = DBGet($QI);
            $RET = $RET[1];
            $title = $RET['TITLE'];
        } else {
            $title = _newDivision;
            unset($delete_button);
        }

        echo '<div class="panel panel-default">';
        echo "<FORM name=F4 id=F4 class=form-horizontal action=Modules.php?modname=$_REQUEST[modname]&subject_id=$_REQUEST[subject_id] method=POST>";
        echo '<div class="panel-heading">
                <h6 class="panel-title">' . $title . '</h6>
                <div class="heading-elements">' . $delete_button . SubmitButton(_save, '', 'id="setupSubjectBtn" class="btn btn-primary" onclick="formcheck_Timetable_course_F4(this);"') . '</div>
            </div>';
        echo '<hr class="no-margin"/>';

        echo '<div class="panel-body">';
        $header .= '<div class="form-group">';
        $header .= '<label class="col-md-1 control-label text-right">'._title.':</label>';
        $header .= "<input type=hidden value=" . $_REQUEST['academic_program_id'] . " id=subject_id_div />";
        $header .= '<div class="col-md-6">' . TextInput($RET['TITLE'], 'tables[course_subjects][' . $_REQUEST['academic_program_id'] . '][TITLE]') . '</div>';

        $header .= '</div>';
        DrawHeader($header);
        echo '</div>'; //.panel-body
        echo '</FORM>';
        echo '</div>'; //.panel
    }


    // DISPLAY THE MENU
    $LO_options = array('save' =>false, 'search' =>false);

    if (!$_REQUEST['academic_program_id']) {
        echo '<div class="panel panel-default">';
        echo "<FORM name=F1 id=F1 action=Modules.php?modname=$_REQUEST[modname]&modfunc=$_REQUEST[modfunc]&course_modfunc=search method=POST>";
        DrawHeader(_courses, '<div class="form-group"><div class="input-group"><INPUT placeholder="'._searchCourse.'" type=text class=form-control name=search_term value="' . $_REQUEST['search_term'] . '"><span class="input-group-btn"><INPUT type=submit class="btn btn-primary" value='._search.' onclick=\'formload_ajax("F1")\';></span></div></div>');
        echo '</FORM>';
        echo '</div>';
    }


    if (count($subjects_RET)) {
        if (clean_param($_REQUEST['academic_program_id'], PARAM_ALPHANUM)) {
            foreach ($subjects_RET as $key => $value) {
                if ($value['academic_program_id'] == $_REQUEST['academic_program_id'])
                    $subjects_RET[$key]['row_color'] = Preferences('HIGHLIGHT');
            }
        }
    }

    echo '<div class="row">';

    $columns = array('TITLE' =>_division);
    $link = array();
    $link['TITLE']['link'] = "Modules.php?modname=$_REQUEST[modname]";
    $link['TITLE']['variables'] = array('subject_id' => 'SUBJECT_ID');
    $link['add']['link'] = "Modules.php?modname=$_REQUEST[modname]&subject_id=new";
    echo '<div class="col-md-4">';
    echo '<div class="panel panel-white">';
    ListOutput($subjects_RET, $columns,  _division, _divisions, $link, array(), $LO_options);
    echo '</div>'; // .panel
    echo '</div>'; // .col-md-4

    if (clean_param($_REQUEST['academic_program_id'], PARAM_ALPHANUM) && $_REQUEST['academic_program_id'] != 'new') {
        //Order courses alphabetically by Title
        //$sql = "SELECT COURSE_ID,c.TITLE, CONCAT_WS(' - ',c.short_name,c.title) AS GRADE_COURSE FROM courses c LEFT JOIN school_gradelevels sg ON c.grade_level=sg.id WHERE ACADEMIC_PROGRAM_ID='$_REQUEST[subject_id]' ORDER BY c.TITLE";
        //Order courses alphabetically by Short_Name
        $sql = "SELECT COURSE_ID,c.TITLE, CONCAT_WS(' - ',c.short_name,c.title) AS GRADE_COURSE FROM courses c LEFT JOIN school_gradelevels sg ON c.grade_level=sg.id WHERE ACADEMIC_PROGRAM_ID='$_REQUEST[subject_id]' ORDER BY c.SHORT_NAME";
        $QI = DBQuery($sql);
        $courses_RET = DBGet($QI);

        if (count($courses_RET)) {
            if (clean_param($_REQUEST['program_course_id'], PARAM_ALPHANUM)) {
                foreach ($courses_RET as $key => $value) {
                    if ($value['program_course_id'] == $_REQUEST['program_course_id'])
                        $courses_RET[$key]['row_color'] = Preferences('HIGHLIGHT');
                }
            }
        }

        $columns = array('GRADE_COURSE' => _course);
        $link = array();
        $link['GRADE_COURSE']['link'] = "Modules.php?modname=$_REQUEST[modname]&subject_id=$_REQUEST[subject_id]";

        $link['GRADE_COURSE']['variables'] = array('course_id' => 'COURSE_ID');
        $link['add']['link'] = "Modules.php?modname=$_REQUEST[modname]&subject_id=$_REQUEST[subject_id]&course_id=new";

        echo '<div class="col-md-4">';
        echo '<div class="panel panel-white">';
        ListOutput($courses_RET, $columns, _course , _courses, $link, array(), $LO_options);
        echo '</div>'; // .panel
        echo '</div>'; // .col-md-4

        if (clean_param($_REQUEST['program_course_id'], PARAM_ALPHANUM) && $_REQUEST['program_course_id'] != 'new') {
            $sql = "SELECT COURSE_PERIOD_ID,TITLE,COALESCE(TOTAL_SEATS-FILLED_SEATS,0) AS AVAILABLE_SEATS FROM course_periods WHERE COURSE_ID='$_REQUEST[course_id]' AND (marking_period_id IN(" . GetAllMP(GetMPTable(GetMP(UserMP(), 'TABLE')), UserMP()) . ") OR (MARKING_PERIOD_ID IS NULL)) ORDER BY TITLE";

            $QI = DBQuery($sql);
            $periods_RET = DBGet($QI);

            if (count($periods_RET)) {
                if (clean_param($_REQUEST['course_period_id'], PARAM_ALPHANUM)) {
                    foreach ($periods_RET as $key => $value) {
                        if ($value['COURSE_PERIOD_ID'] == $_REQUEST['course_period_id'])
                            $periods_RET[$key]['row_color'] = Preferences('HIGHLIGHT');
                    }
                }
            }

            $columns = array('TITLE' =>_coursePeriod);
            if ($_REQUEST['modname'] == 'Schdeuling/Schedule.php')
                $columns += array('AVAILABLE_SEATS' => 'Available Seats');
            $link = array();
            $link['TITLE']['link'] = "Modules.php?modname=$_REQUEST[modname]&subject_id=$_REQUEST[subject_id]&course_id=$_REQUEST[course_id]";
            $link['TITLE']['variables'] = array('course_period_id' => 'COURSE_PERIOD_ID');
            $link['add']['link'] = "Modules.php?modname=$_REQUEST[modname]&subject_id=$_REQUEST[subject_id]&course_id=$_REQUEST[course_id]&course_period_id=new";
            echo '<div class="col-md-4">';
            echo '<div class="panel panel-white">';
            ListOutput($periods_RET, $columns,  _coursePeriod, _coursePeriods, $link, array(), $LO_options);
            echo '</div>'; // .panel
            echo '</div>'; // .col-md-4
        }
    }

    unset($_REQUEST['conflict']);
    echo '</div>'; //.row
}

echo '<div id="modal_default_block_cp" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">';
echo '<div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">×</button>
                        </div>';

echo '<div class="modal-body">';

echo'<div id="modal-res">';

echo '</div>';
echo '</div>';
echo '</div>';
echo '</div>';
echo '</div>';
if (clean_param($_REQUEST['modname'], PARAM_ALPHAEXT) == 'Schedule/Courses.php' && clean_param($_REQUEST['course_period_id'], PARAM_ALPHANUM)) {
    $course_title = DBGet(DBQuery("SELECT TITLE FROM course_periods WHERE COURSE_PERIOD_ID='" . $_REQUEST['course_period_id'] . "'"));
    $course_title = $course_title[1]['TITLE'] . '<INPUT type=hidden name=tables[parent_id] value=' . $_REQUEST['course_period_id'] . '>';
    echo "<script language=javascript>opener.document.getElementById(\"course_div\").innerHTML = \"$course_title</small>\"; window.close();</script>";
}





echo '<div id="modal_default" class="modal fade">';
echo '<div class="modal-dialog modal-lg">';
echo '<div class="modal-content">';
echo '<div class="modal-header">';
echo '<button type="button" class="close" data-dismiss="modal">×</button>';
echo '<h5 class="modal-title">'._chooseCourse.'</h5>';
echo '</div>';

echo '<div class="modal-body">';
echo '<div id="conf_div" class="text-center"></div>';
echo '<div class="row" id="resp_table">';
echo '<div class="col-md-4">';
$sql = "SELECT SUBJECT_ID,TITLE FROM academic_programs WHERE SCHOOL_ID='" . UserSchool() . "' AND SYEAR='" . UserSyear() . "' ORDER BY TITLE";
$QI = DBQuery($sql);
$subjects_RET = DBGet($QI);

echo '<h6>' . count($subjects_RET) . ((count($subjects_RET) == 1) ? ''._divisionWas.'' : ''._divisionsWere.'') . ' '._found.'.</h6>';
if (count($subjects_RET) > 0) {
    echo '<table class="table table-bordered"><thead><tr class="alpha-grey"><th>'._division.'</th></tr></thead><tbody>';
    foreach ($subjects_RET as $val) {
        echo '<tr><td><a href=javascript:void(0); onclick="chooseCpModalSearch(' . $val['academic_program_id'] . ',\'courses\')">' . $val['TITLE'] . '</a></td></tr>';
    }
    echo '</tbody></table>';
}
echo '</div>';
echo '<div class="col-md-4"><div id="course_modal"></div></div>';
echo '<div class="col-md-4"><div id="cp_modal"></div></div>';
echo '</div>'; //.row
echo '</div>'; //.modal-body

echo '</div>'; //.modal-content
echo '</div>'; //.modal-dialog
echo '</div>'; //.modal

function conv_day($short_date, $type = '') {
    $days = array('U' => 'Sun', 'M' => 'Mon', 'T' => 'Tue', 'W' => 'Wed', 'H' => 'Thu', 'F' => 'Fri', 'S' => 'Sat');
    if ($type == 'key')
        return array_search($short_date, $days);
    else
        return $days[$short_date];
}

function disabled() {
    if ($_REQUEST['course_period_id'] != 'new')
        return 'disabled';
}

function _makeMonths($link, $begin_date, $end_date) {
    $begin_date = strtotime($begin_date);
    $end_date = strtotime($end_date);
    $one_day = 86400;
    if (!$_REQUEST['month']) {
        $_REQUEST['month'] = date($begin_date);
    }
    $days = date('t', $_REQUEST['month']);

    $last_day_end = date('t', $end_date);
    $begin = strtotime(date('Y-m-1', $begin_date)) . "<br>";
    $end = strtotime(date('Y-m-' . $last_day_end, $end_date));
    $prev = $_REQUEST['month'] - $one_day * 30;
    $next = $_REQUEST['month'] + $one_day * $days;
    $prev_month_f = strtotime(date('Y-m-d', $next));
    $prev_month_f = strtotime('Previous Month', $prev_month_f);
    $html = '<ul class="pagination pagination-flat">';
    if ($link != '') {
        if ($prev >= $begin) {
            $prev_month_f = strtotime('Previous Month', $prev_month_f);
            $prev = $prev_month_f;
            $html .= "<li><a href='javascript:void(0);' title='Previous' onclick=\"window.location='" . $link . $prev . "';\"><i class=\"icon-arrow-left12\"></i> "._prev."</a></li>";
        }
        $html .= "<li class=\"active\"><a href=\"javascript:void(0);\">" . date('F', $_REQUEST['month']) . "&nbsp;" . date('Y', $_REQUEST['month']) . "</a></li>";
        if ($next <= $end)
            $html .= "<li><a href='javascript:void(0);' title='Next' onclick=\"window.location='" . $link . $next . "';\">"._next." <i class=\"icon-arrow-right13\"></i> </a></li>";
    }
    $html .= '</ul>';

    return $html;
}

function makeTextInput($value, $name) {
    global $THIS_RET;
    if ($THIS_RET['ID'])
        $id = $THIS_RET['ID'];
    else
        $id = 'new';

    if ($name != 'TITLE')
        $extra = 'size=5 maxlength=5 class=cell_small';
    else
        $extra = 'class=cell_wide ';


    return $comment . TextInput($value, 'values[' . $id . '][' . $name . ']', '', $extra);
}

function _makeChooseCheckbox($value, $title) {
    return "<INPUT type=checkbox name=stand_arr[] value=$value checked>";
}
?>