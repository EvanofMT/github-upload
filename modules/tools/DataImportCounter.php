<?php
#**************************************************************************
#  openSIS is a free student information system for public and non-public 
#  schools from Open Solutions for Education, Inc. web: www.os4ed.com
#
#  openSIS is  web-based, open source, and comes packed with features that 
#  include student demographic info, scheduling, grade book, attendance, 
#  report cards, eligibility, transcripts, parent portal, 
#  student portal and more.   
#
#  Visit the openSIS web site at http://www.opensis.com to learn more.
#  If you have question regarding this system or the license, please send 
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

session_start();

error_reporting(E_ALL); //for troubleshooting

include('../../RedirectRootInc.php');
include('../../Warehouse.php');

$category = $_REQUEST['cat'];

echo '<div class="panel-body">';

//================================Student info Begins==============================================
if ($category == 'student') {

    if (count($_SESSION['data']) > 1) {
        $total_records = 0;
        $inserted_records = 0;
        $duplicate_records = 0;
        $arr_data = $_SESSION['data'];

        $temp_array_index = array();
        foreach ($arr_data[0] as $key => $value) {
            if ($value != '') {
                $temp_array_index[$value] = $key;
            }
        }

        foreach ($_SESSION['student'] as $key => $value) {
            if($value=='LANGUAGE')
                $value='LANGUAGE_ID';
            if($value=='ETHNICITY')
                $value='ETHNICITY_ID';
            if (!is_array($value) && $value != '') {
                $array_index[$value] = $temp_array_index[$key];
            }
        }
        $students = array('FIRST_NAME', 'LAST_NAME', 'MIDDLE_NAME', 'NAME_SUFFIX', 'GENDER', 'ETHNICITY_ID', 'COMMON_NAME', 'SOCIAL_SECURITY', 'BIRTHDATE', 'LANGUAGE_ID', 'ESTIMATED_GRAD_DATE', 'ALT_ID', 'EMAIL', 'PHONE', 'IS_DISABLE');
        $login_authentication = array('USERNAME', 'PASSWORD');
        $student_enrollments = array('GRADE_ID', 'SECTION_ID', 'START_DATE', 'END_DATE');
        $custom = DBGet(DBQuery('SELECT * FROM custom_fields'));
        foreach ($custom as $c) {
            $students[] = 'CUSTOM_' . $c['ID'];
        }


        $student_address = array('STREET_ADDRESS_1', 'STREET_ADDRESS_2', 'CITY', 'STATE', 'ZIPCODE');
        $primary = array('PRIMARY_FIRST_NAME', 'PRIMARY_MIDDLE_NAME', 'PRIMARY_LAST_NAME', 'PRIMARY_WORK_PHONE', 'PRIMARY_HOME_PHONE', 'PRIMARY_CELL_PHONE', 'PRIMARY_EMAIL', 'PRIMARY_RELATION');
        $secondary = array('SECONDARY_FIRST_NAME', 'SECONDARY_MIDDLE_NAME', 'SECONDARY_LAST_NAME', 'SECONDARY_WORK_PHONE', 'SECONDARY_HOME_PHONE', 'SECONDARY_CELL_PHONE', 'SECONDARY_EMAIL', 'SECONDARY_RELATION');

        $id = DBGet(DBQuery("SHOW TABLE STATUS LIKE 'students'"));
        $student_id[1]['STUDENT_ID'] = $id[1]['AUTO_INCREMENT'];
        $student_id = $student_id[1]['STUDENT_ID'];
        $accepted = 0;
        $rejected = 0;
        $records = 0;
        $err_msg=array();
        //ADDED by Africa's Hope - Evan Stewart
        $lineReject=array();
        $lineNumber = 2; //counter for Excel file row number, assuming the first Excel row is header
        $processBegin = new DateTime(time());
        $abortTime = $processBegin->add(new DateInterval('P5M'));

        echo "<p>Starting the PRocess -- Evan </p>";
        //END Africa's Hope addition
        foreach ($arr_data as $arr_i => $arr_v) {

            ///////////////////////////For Students////////////////////////////////////////////////////////////
            $student_columns = array();
            $student_values = array();
            if ($arr_i > 0) {


                $student_columns = array('STUDENT_ID ');
                $student_values = array($student_id);
                $check_query = array();
                $check_query_alt_id = array();
                $check_query_username = array();
                $check_exist = 0;

                $processTime = new DateTime(time()); //ADDED by Africa's Hope - Evan Stewart - 07142021
                if($processTime > $abortTime){
                    echo "<p>The import stalled.  It lasted longer than 5 minutes</p>";
                    exit;
                }

                foreach ($students as $students_v) {

                    if ($arr_v[$array_index[$students_v]] != '') {
                        $student_columns[] = $students_v;
                        if ($students_v == 'BIRTHDATE' || $students_v == 'ESTIMATED_GRAD_DATE' ) {
                            $student_values[] = "'" . fromExcelToLinux(singleQuoteReplace("", "", $arr_v[$array_index[$students_v]])) . "'";
             
                            
                        }elseif($students_v == 'LANGUAGE_ID'){
                            if(is_numeric($arr_v[$array_index[$students_v]]))
                            {
                                $student_values[] = "'".$arr_v[$array_index[$students_v]]."'";
                            }
                            else
                            {
                                $lang_id= DBGet(DBQuery('SELECT language_id FROM `language` WHERE LANGUAGE_NAME =\''. $arr_v[$array_index[$students_v]] . '\''));
                                $student_values[] = "'".$lang_id[1]['LANGUAGE_ID']."'";
                            }
                        }elseif($students_v == 'ETHNICITY_ID'){
                            if(is_numeric($arr_v[$array_index[$students_v]]))
                            {
                                $student_values[] = "'".$arr_v[$array_index[$students_v]]."'";
                            }
                            else
                            {
                                $lang_id= DBGet(DBQuery('SELECT ethnicity_id FROM `ethnicity` WHERE ethnicity_name =\''. $arr_v[$array_index[$students_v]] . '\''));
                                $student_values[] = "'".$lang_id[1]['ETHNICITY_ID']."'";
                            }
                        }
                        //ADDED by Africa's Hope - Evan Stewart 
                        elseif($students_v == 'GENDER' AND $arr_v[$array_index[$students_v]] == 'M'|| $arr_v[$array_index[$students_v]] == 'F') {
                            if($arr_v[$array_index[$students_v]] == 'M'){
                                $student_values[] = "'Male'";
                            }
                            
                            if($arr_v[$array_index[$students_v]] == 'F'){
                                $student_values[] = "'Female'";
                            }
                        }
                        //END Africa's Hope Addition
                        else {
                            $student_values[] = "'" . singleQuoteReplace("", "", $arr_v[$array_index[$students_v]]) . "'";
                        }
                        //ADDED by Africa's Hope - Evan Stewart
                        //Add ALT_ID and MIDDLE_NAME, some records in Africa will have dup First,Last and birthdate with no email supplied
                        //ORIG - if ($students_v == 'FIRST_NAME' || $students_v == 'LAST_NAME' || $students_v == 'EMAIL' || $students_v == 'BIRTHDATE' )
                        if ($students_v == 'FIRST_NAME' || $students_v == 'LAST_NAME' || $students_v == 'EMAIL' || $students_v == 'BIRTHDATE' || $students_v == 'MIDDLE_NAME' || $students_v == 'ALT_ID')
                            
                             $check_query[] = $students_v . '=' . "'" .($students_v=='BIRTHDATE'?fromExcelToLinux(singleQuoteReplace("", "", $arr_v[$array_index[$students_v]])):singleQuoteReplace("", "", $arr_v[$array_index[$students_v]]) ). "'";

                        if ($students_v == 'ALT_ID'){
                            $check_query_alt_id[]= $students_v . '=' . "'" .(singleQuoteReplace("", "", $arr_v[$array_index[$students_v]]) ). "'";
                        }
                    }
                }
          
                foreach ($login_authentication as $username) {
                    if ($arr_v[$array_index[$username]] != '') {
                        if ($username == 'USERNAME')
                            $check_query_username[]= $username . '=' . "'" .(singleQuoteReplace("", "", $arr_v[$array_index[$username]]) ). "'";
                    }
                }
                
                
                if (count($check_query) > 0) {
                    $check_exist = DBGet(DBQuery('SELECT COUNT(*) as REC_EXISTS FROM students WHERE ' . implode(" AND ", $check_query)));
                    $check_exist = $check_exist[1]['REC_EXISTS'];

                    if ($check_exist != 0){
                        $err_msg[0]= 'duplicate student';
                    }
                }
                
                if (count($check_query_alt_id) > 0) {
                    $check_exist_al= DBGet(DBQuery('SELECT COUNT(*) as REC_EXISTS FROM students WHERE ' . implode(" ",$check_query_alt_id)));
                    $check_exist_alt= $check_exist_al[1]['REC_EXISTS'];
                
                    if ($check_exist_alt != 0){
                        $err_msg[1]= 'duplicate alternet id';
                    }
                }

                if (count($check_query_username) > 0) {
                    $check_exist_al_username= DBGet(DBQuery('SELECT COUNT(*) as REC_EXISTS FROM login_authentication WHERE ' . implode(" ",$check_query_username)));
                    $check_exist_alt_username= $check_exist_al_username[1]['REC_EXISTS'];
                
                    if ($check_exist_alt_username != 0){
                        $err_msg[2]= 'duplicate username';
                    }
                }
                
                if ( $check_exist == 0 && $check_exist_alt == 0 && $check_exist_alt_username == 0) {
                    DBQuery('INSERT INTO students (' . implode(',', $student_columns) . ') VALUES (' . implode(',', $student_values) . ')');
                    unset($student_columns);
                    unset($student_values);
                    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    ///////////////////////////For Student Enrollment////////////////////////////////////////////////////////////
                    $enrollment_code = DBGet(DBQuery('SELECT ID FROM  student_enrollment_codes WHERE SYEAR=' . UserSyear() . '  AND TITLE=\'New\''));
                    $enrollment_columns = array('SYEAR', 'SCHOOL_ID', 'STUDENT_ID', 'ENROLLMENT_CODE');
                    $enrollment_values = array(UserSyear(), UserSchool(), $student_id, $enrollment_code[1]['ID']);
                    $calendar_id = DBGet(DBQuery('SELECT CALENDAR_ID FROM school_calendars  WHERE SYEAR=' . UserSyear() . ' AND SCHOOL_ID=' . UserSchool() . ' AND DEFAULT_CALENDAR=\'Y\' '));
                    if ($calendar_id[1]['CALENDAR_ID'] != '') {
                        $enrollment_columns+=array('CALENDAR_ID');
                        $enrollment_values+=array($calendar_id[1]['CALENDAR_ID']);
                    }
                    foreach ($student_enrollments as $student_enrollments_v) {
                        if ($arr_v[$array_index[$student_enrollments_v]] != '') {
                            $enrollment_columns[] = $student_enrollments_v;
                            if ($student_enrollments_v == 'GRADE_ID') {
                                $enr_value = DBGet(DBQuery('SELECT ID FROM school_gradelevels WHERE SHORT_NAME=\'' . singleQuoteReplace("", "", trim($arr_v[$array_index[$student_enrollments_v]])) . '\' and school_id=\'' . UserSchool() . '\''));
                                $enr_value = $enr_value[1]['ID'];
                            } elseif ($student_enrollments_v == 'SECTION_ID') {
                                $enr_value = DBGet(DBQuery('SELECT ID FROM school_gradelevel_sections WHERE NAME=\'' . singleQuoteReplace("", "", $arr_v[$array_index[$student_enrollments_v]]) . '\' and school_id=\'' . UserSchool() . '\''));
                                $enr_value = $enr_value[1]['ID'];
                            } elseif ($student_enrollments_v == 'START_DATE') {
                                $enr_value = fromExcelToLinux(singleQuoteReplace("", "", $arr_v[$array_index[$student_enrollments_v]]));
                            } elseif ($student_enrollments_v == 'END_DATE') {
                                $enr_value = fromExcelToLinux(singleQuoteReplace("", "", $arr_v[$array_index[$student_enrollments_v]]));
                            } else
                                $enr_value = singleQuoteReplace("", "", $arr_v[$array_index[$student_enrollments_v]]);
                            $enrollment_values[] = "'" . $enr_value . "'";
                        }
                    }
                    DBQuery('INSERT INTO student_enrollment (' . implode(',', $enrollment_columns) . ') VALUES (' . implode(',', $enrollment_values) . ')');
                    unset($enrollment_columns);
                    unset($enrollment_values);
                    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    ///////////////////////////For Student Login Authentication////////////////////////////////////////////////////////////
                    $la_columns = array('USER_ID', 'PROFILE_ID');
                    $la_values = array($student_id, 3);
                    if ($arr_v[$array_index['USERNAME']] != '') {
                        $la_columns[] = 'USERNAME';
                        $la_values[] = "'" . str_replace("'", "", $arr_v[$array_index['USERNAME']]) . "'";
                    } else {
                        $la_columns[] = 'USERNAME';
                        $la_values[] = "' '";
                    }

                    if ($arr_v[$array_index['PASSWORD']] != '') {
                        $la_columns[] = 'PASSWORD';
                        $la_values[] = "'" . md5(str_replace("'", "", $arr_v[$array_index['PASSWORD']])) . "'";
                    } else {
                        $la_columns[] = 'PASSWORD';
                        $la_values[] = "' '";
                    }

                    //Blank username and password are needed for Student Info query in the modules/students/Student.php script
                    DBQuery('INSERT INTO login_authentication (' . implode(',', $la_columns) . ') VALUES (' . implode(',', $la_values) . ')');

                    unset($la_columns);
                    unset($la_values);
                    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    /////////////////////////For Student Address////////////////////////////////////////////////////////////
                    $sa_columns = array('STUDENT_ID', 'SYEAR', 'SCHOOL_ID');
                    $sa_values = array($student_id, UserSyear(), UserSchool());

                    foreach ($student_address as $student_address_v) {

                        if ($arr_v[$array_index[$student_address_v]] != '') {
                            $sa_columns[] = $student_address_v;
                            $sa_values[] = "'" . singleQuoteReplace("", "", $arr_v[$array_index[$student_address_v]]) . "'";
                        }
                    }
                    DBQuery('INSERT INTO student_address (' . implode(',', $sa_columns) . ',TYPE) VALUES (' . implode(',', $sa_values) . ',\'Home Address\')');
                    DBQuery('INSERT INTO student_address (' . implode(',', $sa_columns) . ',TYPE) VALUES (' . implode(',', $sa_values) . ',\'Mail\')');
                    DBQuery('INSERT INTO student_address (' . implode(',', $sa_columns) . ',TYPE) VALUES (' . implode(',', $sa_values) . ',\'Primary\')');
                    DBQuery('INSERT INTO student_address (' . implode(',', $sa_columns) . ',TYPE) VALUES (' . implode(',', $sa_values) . ',\'Secondary\')');
                    unset($sa_columns);
                    unset($sa_values);
                    //////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    /////////////////////////For Primary////////////////////////////////////////////////////////////
                    $primary_columns = array('CURRENT_SCHOOL_ID', 'PROFILE', 'PROFILE_ID');
                    $primary_values = array(UserSchool(), "'parent'", '4');
                    $relationship = '';
                    foreach ($primary as $primary_v) {

                        if ($primary_v != 'PRIMARY_RELATION') {
                            if ($arr_v[$array_index[$primary_v]] != '') {
                                $primary_columns[] = str_replace("PRIMARY_", "", $primary_v);
                                $primary_values[] = "'" . singleQuoteReplace("", "", $arr_v[$array_index[$primary_v]]) . "'";
                            }
                        } else
                            $relationship = ($arr_v[$array_index[$primary_v]] != '' ? singleQuoteReplace("", "", $arr_v[$array_index[$primary_v]]) : 'Legal Guardian');
                    }
                    if (count($primary_columns) > 3) {
                        DBQuery('INSERT INTO people (' . implode(',', $primary_columns) . ') VALUES (' . implode(',', $primary_values) . ')');
                        $people_id = DBGet(DBQuery('SELECT MAX(STAFF_ID) as PEOPLE_ID FROM people'));
                        $people_id = $people_id[1]['PEOPLE_ID'];
                        DBQuery('UPDATE student_address SET PEOPLE_ID=' . $people_id . ' WHERE STUDENT_ID=' . $student_id . ' AND TYPE=\'Primary\' ');
                        DBQuery('INSERT INTO students_join_people (STUDENT_ID,PERSON_ID,EMERGENCY_TYPE,RELATIONSHIP) VALUES (' . $student_id . ',' . $people_id . ',\'Primary\',\'' . $relationship . '\')');
                    }
                    unset($primary_columns);
                    unset($primary_values);

                    //////////////////////////////////////////////////////////////////////////////////////////////
                    /////////////////////////For Secondary////////////////////////////////////////////////////////////
                    $secondary_columns = array('CURRENT_SCHOOL_ID', 'PROFILE', 'PROFILE_ID');
                    $secondary_values = array(UserSchool(), "'parent'", '4');
                    $relationship = '';
                    foreach ($secondary as $secondary_v) {

                        if ($secondary_v != 'SECONDARY_RELATION') {
                            if ($arr_v[$array_index[$secondary_v]] != '') {
                                $secondary_columns[] = str_replace("SECONDARY_", "", $secondary_v);
                                $secondary_values[] = "'" . singleQuoteReplace("", "", $arr_v[$array_index[$secondary_v]]) . "'";
                            }
                        } else
                            $relationship = ($arr_v[$array_index[$secondary_v]] != '' ? singleQuoteReplace("", "", $arr_v[$array_index[$secondary_v]]) : 'Legal Guardian');
                    }
                    if (count($secondary_columns) > 3) {
                        DBQuery('INSERT INTO people (' . implode(',', $secondary_columns) . ') VALUES (' . implode(',', $secondary_values) . ')');
                        $people_id = DBGet(DBQuery('SELECT MAX(STAFF_ID) as PEOPLE_ID FROM people'));
                        $people_id = $people_id[1]['PEOPLE_ID'];
                        DBQuery('UPDATE student_address SET PEOPLE_ID=' . $people_id . ' WHERE STUDENT_ID=' . $student_id . ' AND TYPE=\'Secondary\' ');
                        DBQuery('INSERT INTO students_join_people (STUDENT_ID,PERSON_ID,EMERGENCY_TYPE,RELATIONSHIP) VALUES (' . $student_id . ',' . $people_id . ',\'Secondary\',\'' . $relationship . '\')');
                    }
                    unset($secondary_columns);
                    unset($secondary_values);

                    //////////////////////////////////////////////////////////////////////////////////////////////
                    $student_id++;
                    $accepted++;
                } else {
                    $rejected++;
                    //ADDED by Africa's Hope - Evan Stewart
                    $lineReject[] = $lineNumber;
                    //END Africa's Hope addition
                }
                $records++;
                //ADDED by Africa's Hope - Evan Stewart
                $lineNumber++;
                //END Africa's Hope addition
            }
        }


        if ($records > 0) {
            if ($records == $accepted) {
                echo '<div class="text-center"><img src="assets/images/check-clipart-animated.gif"></div>';
                echo '<h2 class="text-center text-success m-b-0">Congratulations !!!</h2>';
                echo '<h5 class="text-center m-t-0 m-b-35 text-grey">The data import has successfully concluded.</h5>';
            } elseif ($accepted > 0 && $rejected > 0) {
                echo '<div class="text-center"><img src="assets/images/info-icon-animated.gif" width="90"></div>';
                echo '<h2 class="text-center text-warning m-b-0">Partial Import !!!</h2>';
                echo '<h5 class="text-center m-t-0 m-b-35 text-grey">Some data couldn\'t be processed.</h5>';
            } elseif ($accepted == 0) {
                echo '<div class="text-center"><img src="assets/images/error-icon-animated.gif" width="100"></div>';
                echo '<h2 class="text-center text-danger m-b-0 m-t-0">Oops !!!</h2>';
                echo '<h5 class="text-center m-t-0 m-b-35 text-grey">The data import was rejected by the system.</h5>';
            }

            echo '<div class="row m-b-10">';
            echo '<div class="col-xs-10">Number of input records:</div><div class="col-xs-2">' . $records . '</div>';
            echo '</div>';
            echo '<div class="row m-b-10">';
            echo '<div class="col-xs-10">Number of records loaded into the database:</div><div class="col-xs-2">' . $accepted . '</div>';
            echo '</div>';
            echo '<div class="row">';
            echo '<div class="col-xs-10">Number or records rejected:</div><div class="col-xs-2">' . $rejected . '</div>';
            //ADDED by Africa's Hope - Evan Stewart
            if ($rejected > 0 AND !empty($lineReject)){
                echo '<div class="col-xs-10">The following rows in the Excel file were rejected:<br>';
                echo '<ul>';
                foreach ($lineReject as $line){
                    echo "<li>$line</li>";
                }
                echo '</ul></div>';
            }    
            //END Africa's Hope addition
            echo '</div>';
            

            if(count($err_msg)==1){
                $msg='';
               foreach($err_msg as $key=>$val)
               {
                   $msg=$val;
               }
                echo '<div class="row m-t-10">';
                echo '<div class="col-xs-12 text-danger"><i class="icon-info22"></i> Possible cause for rejection is '.$msg.' found.</div>';
                echo '</div>';
            }
            if(count($err_msg)==2){
                 $msg='';
               foreach($err_msg as $key=>$val)
               {
                   $msg.= $val.' and ';
               }
               $msg=substr($val,0,-5);
                echo '<div class="row m-t-10">';
                echo '<div class="col-xs-12 text-danger"><i class="icon-info22"></i> Possible causes for rejection are '.$msg.' found.</div>';
                echo '</div>';
            }
            if(count($err_msg)==3){
                  $msg='';
               foreach($err_msg as $key=>$val)
               {
                   $msg.= $val.' and ';
               }
               $msg=substr($val,0,-5);
                echo '<div class="row m-t-10">';
                echo '<div class="col-xs-12 text-danger"><i class="icon-info22"></i> Possible causes for rejection are '.$msg.' found.</div>';
                echo '</div>';
            }   
            
        }


        unset($arr_data);
        unset($_SESSION['data']);
        unset($_SESSION['student']);
        unset($array_index);
        unset($temp_array_index);
    }
}
//================================Student info Ends==============================================

//================================Staff info Begins==============================================
if ($category == 'staff') {

    if (count($_SESSION['data']) > 1) {
        $total_records = 0;
        $inserted_records = 0;
        $duplicate_records = 0;
        $arr_data = $_SESSION['data'];

        $temp_array_index = array();
        foreach ($arr_data[0] as $key => $value) {

            if ($value != '') {
                $temp_array_index[$value] = $key;
            }
        }


        foreach ($_SESSION['staff'] as $key => $value) {

            if (!is_array($value) && $value != '') {
                $array_index[$value] = $temp_array_index[$key];
            }

        }

        $staff = array('TITLE', 'FIRST_NAME', 'LAST_NAME', 'MIDDLE_NAME', 'EMAIL', 'PHONE', 'PROFILE', 'HOMEROOM', 'BIRTHDATE', 'ETHNICITY_ID', 'ALTERNATE_ID', 'PRIMARY_LANGUAGE_ID', 'GENDER', 'SECOND_LANGUAGE_ID', 'THIRD_LANGUAGE_ID', 'IS_DISABLE');
        $login_authentication = array('USERNAME', 'PASSWORD');
        $staff_school_relationship = array('START_DATE', 'END_DATE');
        $staff_school_info = array('CATEGORY', 'JOB_TITLE', 'JOINING_DATE');
        $custom = DBGet(DBQuery('SELECT * FROM staff_fields'));
        foreach ($custom as $c) {
            $staff[] = 'CUSTOM_' . $c['ID'];
        }

        $id = DBGet(DBQuery("SHOW TABLE STATUS LIKE 'staff'"));
        $staff_id[1]['STAFF_ID'] = $id[1]['AUTO_INCREMENT'];
        $staff_id = $staff_id[1]['STAFF_ID'];
        $accepted = 0;
        $rejected = 0;
        $records = 0;
        $err_msg=array();
        //ADDED by Africa's Hope - Evan Stewart
        $lineReject=array();
        $lineNumber = 2; //counter for Excel file row number, assuming the first Excel row is header
        //END Africa's Hope addition
        foreach ($arr_data as $arr_i => $arr_v) {
            
            ///////////////////////////For Staff////////////////////////////////////////////////////////////
            $staff_columns = array();
            $staff_values = array();
            if ($arr_i > 0) {


                $staff_columns = array('STAFF_ID', 'CURRENT_SCHOOL_ID');
                $staff_values = array($staff_id, UserSchool());
                $check_query = array();
                $check_query_alt_id = array();
                $check_query_username = array();
                $check_exist = 0;
                foreach ($staff as $staff_v) {

                    if ($staff_v == 'PROFILE') {
                        $arr_v[$array_index[$staff_v]]=strtolower($arr_v[$array_index[$staff_v]]);
                        $profile = DBGet(DBQuery('SELECT * FROM user_profiles WHERE title=\'' . singleQuoteReplace("", "", $arr_v[$array_index[$staff_v]]) . '\' '));
                        if ($profile[1]['ID'] == '') {
                            $profile_id = '2';
                            $arr_v[$array_index[$staff_v]] = 'teacher';
                        } else
                            $profile_id = $profile[1]['ID'];
                        $staff_columns[] = 'PROFILE_ID';
                        $staff_values[] = $profile_id;
                    }
                    if ($staff_v == 'ETHNICITY_ID') {
                        $ethnicity = DBGet(DBQuery('SELECT * FROM ethnicity WHERE ethnicity_name=\'' . singleQuoteReplace("", "", $arr_v[$array_index[$staff_v]]) . '\' '));
                        if ($ethnicity[1]['ETHNICITY_ID'] != '')
                            $arr_v[$array_index[$staff_v]] = $ethnicity[1]['ETHNICITY_ID'];
                        else
                            $arr_v[$array_index[$staff_v]] = '';
                    }
                    if ($staff_v == 'PRIMARY_LANGUAGE_ID') {
                        $language = DBGet(DBQuery('SELECT * FROM language WHERE language_name=\'' . singleQuoteReplace("", "", $arr_v[$array_index[$staff_v]]) . '\' '));
                        if ($language[1]['LANGUAGE_ID'] != '')
                            $arr_v[$array_index[$staff_v]] = $language[1]['LANGUAGE_ID'];
                        else
                            $arr_v[$array_index[$staff_v]] = '';
                    }
                    if ($staff_v == 'SECOND_LANGUAGE_ID') {
                        $language = DBGet(DBQuery('SELECT * FROM language WHERE language_name=\'' . singleQuoteReplace("", "", $arr_v[$array_index[$staff_v]]) . '\' '));
                        if ($language[1]['LANGUAGE_ID'] != '')
                            $arr_v[$array_index[$staff_v]] = $language[1]['LANGUAGE_ID'];
                        else
                            $arr_v[$array_index[$staff_v]] = '';
                    }
                    if ($staff_v == 'THIRD_LANGUAGE_ID') {
                        $language = DBGet(DBQuery('SELECT * FROM language WHERE language_name=\'' . singleQuoteReplace("", "", $arr_v[$array_index[strtolower($staff_v)]]) . '\' '));
                        if ($language[1]['LANGUAGE_ID'] != '')
                            $arr_v[$array_index[$staff_v]] = $language[1]['LANGUAGE_ID'];
                        else
                            $arr_v[$array_index[$staff_v]] = '';
                    }
                    //ADDED by Africa's Hope - Evan Stewart 
                    if($staff_v == 'GENDER' AND $arr_v[$array_index[$staff_v]] == 'M'|| $arr_v[$array_index[$staff_v]] == 'F') {
                        if($arr_v[$array_index[$staff_v]] == 'M' || $arr_v[$array_index[$staff_v]] == 'U' || $arr_v[$array_index[$staff_v]] == ''){
                            //$staff_values[] = "'Male'";
                            $arr_v[$array_index[$staff_v]] = "Male";
                        }
                        
                        if($arr_v[$array_index[$staff_v]] == 'F'){
                            //$staff_values[] = "'Female'";
                            $arr_v[$array_index[$staff_v]] = "Female";
                        }

                    }
                    //END Africa's Hope Addition
                    if ($arr_v[$array_index[$staff_v]] != '') {

                        $staff_columns[] = $staff_v;
                        if ($staff_v == 'BIRTHDATE'){
                            $staff_values[] = "'" .fromExcelToLinux(singleQuoteReplace("", "", $arr_v[$array_index[$staff_v]])) . "'";
                        }else{
                        $staff_values[] = "'" . singleQuoteReplace("", "", $arr_v[$array_index[$staff_v]]) . "'";
                          }
                        if ($staff_v == 'FIRST_NAME' || $staff_v == 'LAST_NAME' || $staff_v == 'EMAIL')
                            $check_query[] = $staff_v . '=' . "'" . singleQuoteReplace("", "", $arr_v[$array_index[$staff_v]]) . "'";

                        if ($staff_v == 'BIRTHDATE')
                            $check_query[] = $staff_v . '=' . "'" .fromExcelToLinux(singleQuoteReplace("", "", $arr_v[$array_index[$staff_v]])) . "'";

                        if($staff_v == 'ALTERNATE_ID')
                        
                         $check_query_alt_id[] = $staff_v . '=' . "'" . singleQuoteReplace("", "", $arr_v[$array_index[$staff_v]]) . "'";

                    }
                }
                
                   
                foreach ($login_authentication as $username) {
                
                    if ($arr_v[$array_index[$username]] != '') {

                         if ($username == 'USERNAME')
                            $check_query_username[]= $username . '=' . "'" .(singleQuoteReplace("", "", $arr_v[$array_index[$username]]) ). "'";
 
                        }
                }
                
                if (count($check_query) > 0) {

                    $check_exist = DBGet(DBQuery('SELECT COUNT(*) as REC_EXISTS FROM staff WHERE ' . implode(" AND ", $check_query)));

                    $check_exist = $check_exist[1]['REC_EXISTS'];
                if ($check_exist != 0){
                $err_msg[0]= 'duplicate staff';
                }
                }
                
                 if (count($check_query_alt_id) > 0) {
                    $check_exist_al = DBGet(DBQuery('SELECT COUNT(*) as REC_EXISTS FROM staff WHERE ' . implode(" ", $check_query_alt_id)));

                    $check_exist_alt = $check_exist_al[1]['REC_EXISTS'];
                 if ($check_exist_alt != 0){
                 $err_msg[1]= 'duplicate alternet id';
                }
            
                }
                
                 if (count($check_query_username) > 0) {

                    $check_exist_al_username= DBGet(DBQuery('SELECT COUNT(*) as REC_EXISTS FROM login_authentication WHERE ' . implode(" ",$check_query_username)));
                    $check_exist_alt_username= $check_exist_al_username[1]['REC_EXISTS'];
                
                 if ($check_exist_alt_username != 0){
                 $err_msg[2]= 'duplicate username';
 
                 }
                 }

                if ($check_exist == 0 && $check_exist_alt == 0 && $check_exist_alt_username == 0) {
                    DBQuery('INSERT INTO staff (' . implode(',', $staff_columns) . ') VALUES (' . implode(',', $staff_values) . ')');
                    unset($staff_columns);
                    unset($staff_values);
                    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    ///////////////////////////For Staff Enrollment////////////////////////////////////////////////////////////
                    $ssr_columns = array('STAFF_ID', 'SYEAR', 'SCHOOL_ID');
                    $ssr_values = array($staff_id, UserSyear(), UserSchool());
                    $start_date_i = 0;
                    foreach ($staff_school_relationship as $ssr_v) {

                        if ($arr_v[$array_index[$ssr_v]] != '') {
                            $ssr_columns[] = $ssr_v;
                            if ($ssr_v == 'START_DATE') {
                                $start_date_i = 1;
                                if ($arr_v[$array_index[$ssr_v]] == '') {
                                    $start_date = DBGet(DBQuery('SELECT START_DATE FROM school_years WHERE SCHOOL_ID=' . UserSchool() . ' AND SYEAR=' . UserSyear()));

                                    $ssr_values[] = "'" . $start_date[1]['START_DATE'] . "'";
                                } else
                                    $ssr_values[] = "'" . fromExcelToLinux(singleQuoteReplace("", "", $arr_v[$array_index[$ssr_v]])) . "'";
                            } elseif ($ssr_v == 'END_DATE') {
                                $ssr_values[] = "'" . fromExcelToLinux(singleQuoteReplace("", "", $arr_v[$array_index[$ssr_v]])) . "'";
                            } else
                                $ssr_values[] = "'" . singleQuoteReplace("", "", $arr_v[$array_index[$ssr_v]]) . "'";
                        }
                    }
                    if ($start_date_i == 0) {
                        $start_date = DBGet(DBQuery('SELECT START_DATE FROM school_years WHERE SCHOOL_ID=' . UserSchool() . ' AND SYEAR=' . UserSyear()));
                        $ssr_columns[] = 'START_DATE';
                        $ssr_values[] = "'" . $start_date[1]['START_DATE'] . "'";
                    }

                    DBQuery('INSERT INTO staff_school_relationship (' . implode(',', $ssr_columns) . ') VALUES (' . implode(',', $ssr_values) . ')');
                    unset($ssr_columns);
                    unset($ssr_values);
                    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    ///////////////////////////For Staff Login Authentication////////////////////////////////////////////////////////////
                    $la_columns = array('USER_ID', 'PROFILE_ID');
                    $la_values = array($staff_id, $profile_id);
                    if ($arr_v[$array_index['USERNAME']] != '') {
                        $la_columns[] = 'USERNAME';
                        $la_values[] = "'" . str_replace("'", "", $arr_v[$array_index['USERNAME']]) . "'";
                    } else {
                        $la_columns[] = 'USERNAME';
                        $la_values[] = "'" . trim(strtolower(str_replace("'", "", str_replace(" ", "", $arr_v[$array_index['FIRST_NAME']]))) . $staff_id) . "'";
                    }

                    if ($arr_v[$array_index['PASSWORD']] != '') {
                        $la_columns[] = 'PASSWORD';
                        $la_values[] = "'" . md5(str_replace("'", "", $arr_v[$array_index['PASSWORD']])) . "'";
                    } else {
                        $la_columns[] = 'PASSWORD';
                        $la_values[] = "'" . md5(trim(strtolower(str_replace("'", "", str_replace(" ", "", $arr_v[$array_index['FIRST_NAME']]))) . $staff_id)) . "'";
                    }
                    DBQuery('INSERT INTO login_authentication (' . implode(',', $la_columns) . ') VALUES (' . implode(',', $la_values) . ')');
                    unset($la_columns);
                    unset($la_values);
                    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    ///////////////////////////For Staff School Info////////////////////////////////////////////////////////////
                    $ssi_columns = array('STAFF_ID', 'HOME_SCHOOL', 'OPENSIS_ACCESS', 'OPENSIS_PROFILE', 'SCHOOL_ACCESS');
                    $ssi_values = array($staff_id, UserSchool(), '"Y"', $profile_id, '",' . UserSchool() . ',"');
                    if ($arr_v[$array_index['CATEGORY']] != '') {
                        $ssi_columns[] = 'CATEGORY';
                        $ssi_values[] = "'" . str_replace("'", "", $arr_v[$array_index['CATEGORY']]) . "'";
                    } else {
                        $ssi_columns[] = 'CATEGORY';
                        $ssi_values[] = "'Teacher'";
                    }

                    if ($arr_v[$array_index['JOB_TITLE']] != '') {
                        $ssi_columns[] = 'JOB_TITLE';
                        $ssi_values[] = "'" . str_replace("'", "", $arr_v[$array_index['JOB_TITLE']]) . "'";
                    } else {
                        $ssi_columns[] = 'JOB_TITLE';
                        $ssi_values[] = "'Teacher'";
                    }

                    if ($arr_v[$array_index['JOINING_DATE']] != '') {
                        $ssi_columns[] = 'JOINING_DATE';
                        $ssi_values[] = "'" . fromExcelToLinux(singleQuoteReplace("", "", $arr_v[$array_index['JOINING_DATE']])) . "'";
                    }

                    DBQuery('INSERT INTO staff_school_info (' . implode(',', $ssi_columns) . ') VALUES (' . implode(',', $ssi_values) . ')');
                    unset($ssi_columns);
                    unset($ssi_values);
                    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                    $staff_id++;
                    $accepted++;
                } else {
                    $rejected++;
                    //ADDED by Africa's Hope - Evan Stewart 
                    $lineReject[] = $lineNumber;
                    //END Africa's Hope addition
                }
                $records++;
                //ADDED by Africa's Hope - Evan Stewart
                $lineNumber++;
                //END Africa's Hope addition
            }
        }


        if ($records > 0) {
            if ($records == $accepted) {
                echo '<div class="text-center"><img src="assets/images/check-clipart-animated.gif"></div>';
                echo '<h2 class="text-center text-success m-b-0">Congratulations !!!</h2>';
                echo '<h5 class="text-center m-t-0 m-b-35 text-grey">The data import has successfully concluded.</h5>';
            } elseif ($accepted > 0 && $rejected > 0) {
                echo '<div class="text-center"><img src="assets/images/info-icon-animated.gif" width="90"></div>';
                echo '<h2 class="text-center text-warning m-b-0">Partial Import !!!</h2>';
                echo '<h5 class="text-center m-t-0 m-b-35 text-grey">Some data could not be processed.</h5>';
            } elseif ($accepted == 0) {
                echo '<div class="text-center"><img src="assets/images/error-icon-animated.gif" width="100"></div>';
                echo '<h2 class="text-center text-danger m-b-0 m-t-0">Oops !!!</h2>';
                echo '<h5 class="text-center m-t-0 m-b-35 text-grey">The data import was rejected by the system.</h5>';
            }

            echo '<div class="row m-b-10">';
            echo '<div class="col-xs-10">Number of input records:</div><div class="col-xs-2">' . $records . '</div>';
            echo '</div>';
            echo '<div class="row m-b-10">';
            echo '<div class="col-xs-10">Number of records loaded into the database:</div><div class="col-xs-2">' . $accepted . '</div>';
            echo '</div>';
            echo '<div class="row m-b-10">';
            echo '<div class="col-xs-10">Number or records rejected:</div><div class="col-xs-2">' . (($rejected > 0) ? '<span class="text-danger">' . $rejected . '</span>' : $rejected) . '</div>';
            //ADDED by Africa's Hope - Evan Stewart
            if ($rejected > 0 AND !empty($lineReject)){
                echo '<div class="col-xs-10">The following rows in the Excel file were rejected:<br>';
                echo '<ul>';
                foreach ($lineReject as $line){
                    echo "<li>$line</li>";
                }
                echo '</ul></div>';
            }    
            //END Africa's Hope addition
            echo '</div>';
            
            


            if(count($err_msg)==1){
                $msg='';
               foreach($err_msg as $key=>$val)
               {
                   $msg=$val;
               }
                echo '<div class="row m-t-10">';
                echo '<div class="col-xs-12 text-danger"><i class="icon-info22"></i> Possible cause for rejection is '.$msg.' found.</div>';
                echo '</div>';
                 
                
            }
            if(count($err_msg)==2){
                 $msg='';
               foreach($err_msg as $key=>$val)
               {
                   $msg.= $val.' and ';
               }
               $msg=substr($val,0,-5);
                echo '<div class="row m-t-10">';
                echo '<div class="col-xs-12 text-danger"><i class="icon-info22"></i> Possible causes for rejection are '.$msg.' found.</div>';
                echo '</div>';
            }
            if(count($err_msg)==3){
                  $msg='';
               foreach($err_msg as $key=>$val)
               {
                   $msg.= $val.' and ';
               }
               $msg=substr($val,0,-5);
                echo '<div class="row m-t-10">';
                echo '<div class="col-xs-12 text-danger"><i class="icon-info22"></i> Possible causes for rejection are '.$msg.' found.</div>';
                echo '</div>';
            }            

        }
        unset($err_msg);
        unset($arr_data);
        unset($_SESSION['data']);
        unset($_SESSION['staff']);
        unset($array_index);
        unset($temp_array_index);
    }
}
//================================Staff info Ends==============================================

//ADDED by Africa's Hope - Evan Stewart
//================================Course info Begins==============================================
if ($category == 'course') {

    if (count($_SESSION['data']) > 1) {
        $total_records = 0;
        $inserted_records = 0;
        $duplicate_records = 0;
        $arr_data = $_SESSION['data'];

        //FOR TROUBLESHOOTING
        //echo "The $ arr_data is:<br>";
        //print_r($arr_data);
        //echo "<br><br>";

        //match Mapped column headers to submitted row values
        $temp_array_index = array();
        foreach ($arr_data[0] as $key => $value) {

            if ($value != '') {
                $temp_array_index[$value] = $key;
            }
        }

        foreach ($_SESSION['course'] as $key => $value) {

            if (!is_array($value) && $value != '') {
                $array_index[$value] = $temp_array_index[$key];
            }
        }

        $course_columns = array('syear', 'subject_id', 'school_id', 'title', 'short_name');

        $subjectId = "";
        $accepted = 0;
        $rejected = 0;
        $records = 0;
        $err_msg=array();
        $lineReject=array(); //store the line count corresponding to the row in the Excel document that is rejected
        $lineNumber = 2; //counter for Excel file row number, assuming the first Excel row is header

        //FOR TROUBLESHOOTING
        //echo "<div>The temp_array_index is:<br>"; print_r($temp_array_index);
        //echo "<br>The array_index is:<br>"; print_r($array_index);
        //echo "</div>";

///////////////////////////Begin Courses Data Processing////////////////////////////////////////////////////////////
        $stp = $array_index['SUBJECT_TITLE']; //subject title position in the array index
        $ctp = $array_index['COURSE_TITLE']; //course title position in the array index
        $snp = $array_index['COURSE_SHORT_NAME']; //course short name position in the array index
        foreach ($arr_data as $arr_i => $arr_v) {
            
            $course_values = array();
            $check_query = array();
            $selectSubId = array();
            $subjectTitle = $arr_v[$stp];
            if($arr_i > 0){

                //$subjectTitle = $arr_v[$stp];
                $selectSubjectSQL = "SELECT subject_id FROM course_subjects WHERE title = '$subjectTitle'";
                $selectSubId = DBGet(DBQuery($selectSubjectSQL));
                
                //If course subject doesn't exist the insert it and select the new ID
                if(count($selectSubId) == 0){
                    $insertSubjectSQL = 'INSERT INTO course_subjects (syear,school_id,title) VALUES (\'' . UserSyear() . '\',\'' . UserSchool() . '\',\'' . singleQuoteReplace("", "", $subjectTitle) . '\')';
                    $insertSubject = DBQuery($insertSubjectSQL);
                  
                    //Run the select query again
                    $selectSubId = DBGet(DBQuery('SELECT subject_id FROM course_subjects WHERE title = \'' . $subjectTitle . '\''));
                    $subjectId = $selectSubId[1]['SUBJECT_ID'];
                   
                } else {
                    $subjectId = $selectSubId[1]['SUBJECT_ID'];
                }

                //In order of the Course Columns
                $course_values[] = "'" . singleQuoteReplace("", "", UserSyear()) . "'"; //syear
                $course_values[] = "'" . singleQuoteReplace("", "", $subjectId) . "'"; //subject_id
                $course_values[] = "'" . singleQuoteReplace("", "", UserSchool()) . "'"; //school_id
                $course_values[] = "'" . singleQuoteReplace("", "", $arr_v[$ctp]) . "'"; //Course Title
                $course_values[] = "'" . singleQuoteReplace("", "", $arr_v[$snp]) . "'"; //Course Short Name

                //Check if subject exists in db table
                $check_query[] = $course_columns[0] . '=' . $course_values[0]; //School Year
                $check_query[] = $course_columns[1] . '=' . $course_values[1]; //Subject ID
                $check_query[] = $course_columns[2] . '=' . $course_values[2]; //School ID
                $check_query[] = $course_columns[3] . '=' . $course_values[3]; //Course Title
                $check_query[] = $course_columns[4] . '=' . $course_values[4]; //Course Short Name
                
                if (count($check_query) > 0) {
                    
                    $check_exist = DBGet(DBQuery('SELECT COUNT(*) as REC_EXISTS FROM courses WHERE ' . implode(" AND ", $check_query)));

                    $check_exist = $check_exist[1]['REC_EXISTS'];
                
                    if ($check_exist != 0){
                        $err_msg[0]= 'duplicate course';
                    }
                }
                
                if ($check_exist == 0) {
                   DBQuery('INSERT INTO courses (' . implode(',', $course_columns) . ') VALUES (' . implode(',', $course_values) . ')');
                   $accepted++;
                } else {
                    $rejected++;
                    $lineReject[] = $lineNumber;
                }
                $records++;
                $lineNumber++;
            }
        }

        if ($records > 0) {
            if ($records == $accepted) {
                echo '<div class="text-center"><img src="assets/images/check-clipart-animated.gif"></div>';
                echo '<h2 class="text-center text-success m-b-0">Congratulations !!!</h2>';
                echo '<h5 class="text-center m-t-0 m-b-35 text-grey">The data import has successfully concluded.</h5>';
            } elseif ($accepted > 0 && $rejected > 0) {
                echo '<div class="text-center"><img src="assets/images/info-icon-animated.gif" width="90"></div>';
                echo '<h2 class="text-center text-warning m-b-0">Partial Import !!!</h2>';
                echo '<h5 class="text-center m-t-0 m-b-35 text-grey">Some data could not be processed.</h5>';
            } elseif ($accepted == 0) {
                echo '<div class="text-center"><img src="assets/images/error-icon-animated.gif" width="100"></div>';
                echo '<h2 class="text-center text-danger m-b-0 m-t-0">Oops !!!</h2>';
                echo '<h5 class="text-center m-t-0 m-b-35 text-grey">The data import was rejected by the system.</h5>';
            }

            echo '<div class="row m-b-10">';
            echo '<div class="col-xs-10">Number of input records:</div><div class="col-xs-2">' . $records . '</div>';
            echo '</div>';
            echo '<div class="row m-b-10">';
            echo '<div class="col-xs-10">Number of records loaded into the database:</div><div class="col-xs-2">' . $accepted . '</div>';
            echo '</div>';
            echo '<div class="row m-b-10">';
            echo '<div class="col-xs-10">Number or records rejected:</div><div class="col-xs-2">' . (($rejected > 0) ? '<span class="text-danger">' . $rejected . '</span>' : $rejected) . '</div>';
            if ($rejected > 0 AND !empty($lineReject)){
                echo '<div class="col-xs-10">The following rows in the Excel file were rejected:<br>';
                echo '<ul>';
                foreach ($lineReject as $line){
                    echo "<li>$line</li>";
                }
                echo '</ul></div>';
            }
            echo '</div>';

            if(count($err_msg)==1){
                $msg='';
                foreach($err_msg as $key=>$val)
               {
                   $msg=$val;
               }
                echo '<div class="row m-t-10">';
                echo '<div class="col-xs-12 text-danger"><i class="icon-info22"></i> Possible cause for rejection is '.$msg.' found.</div>';
                echo '</div>';
            }
            if(count($err_msg)==2){
                $msg='';
               foreach($err_msg as $key=>$val)
               {
                   $msg.= $val.' and ';
               }
               $msg=substr($val,0,-5);
                echo '<div class="row m-t-10">';
                echo '<div class="col-xs-12 text-danger"><i class="icon-info22"></i> Possible causes for rejection are '.$msg.' found.</div>';
                echo '</div>';
            }
            if(count($err_msg)==3){
                $msg='';
               foreach($err_msg as $key=>$val)
               {
                   $msg.= $val.' and ';
               }
               $msg=substr($val,0,-5);
                echo '<div class="row m-t-10">';
                echo '<div class="col-xs-12 text-danger"><i class="icon-info22"></i> Possible causes for rejection are '.$msg.' found.</div>';
                echo '</div>';
            }            

        }
        unset($err_msg);
        unset($arr_data);
        unset($_SESSION['data']);
        unset($_SESSION['course']);
        unset($array_index);
        unset($temp_array_index);
    }
}
//================================Course info Ends==============================================
//================================TROUSERS Grades info Begins==============================================
//This section is designed to be used with grade data exported from the old TROUSERS program
if ($category == 'trousers_grades') {

    if (count($_SESSION['data']) > 1) {
        $total_records = 0;
        $inserted_records = 0;
        $duplicate_records = 0;
        $arr_data = $_SESSION['data'];

        //FOR TROUBLESHOOTING
        //echo "The $ arr_data is:<br>";
        //print_r($arr_data);
        //echo "<br><br>";

        //match Mapped column headers to submitted row values
        $temp_array_index = array();
        foreach ($arr_data[0] as $key => $value) {
            if ($value != '') {
                $temp_array_index[$value] = $key;
            }
        }

        foreach ($_SESSION['trousers_grades'] as $key => $value) {
            if (!is_array($value) && $value != '') {
                $array_index[$value] = $temp_array_index[$key];
            }
        }

        //columns used in various DB tables
        //table history_marking_periods
        $hmp_col = array(
            'MP_TYPE',
            'NAME',
            'POST_END_DATE',
            'SCHOOL_ID',
            'SYEAR',
            'MARKING_PERIOD_ID'
        );
        //for history_school table
        $hs_col = array(
            'STUDENT_ID',
            'MARKING_PERIOD_ID',
            'SCHOOL_NAME'
        );
        //for student_report_card_grades table
        $rc_columns = array(
            'SYEAR',
            'SCHOOL_ID',
            'STUDENT_ID',
            'GRADE_PERCENT',
            'MARKING_PERIOD_ID',
            'GRADE_LETTER',
            //'WEIGHTED_GP',
            'UNWEIGHTED_GP',
            'GP_SCALE',
            'GPA_CAL',
            'CREDIT_ATTEMPTED',
            'CREDIT_EARNED',
            'COURSE_CODE',
            'COURSE_TITLE'
        );

        $mpId = "";
        $accepted = 0;
        $rejected = 0;
        $records = 0;
        $id_index=array();
        $err_msg=array();
        $lineReject=array(); //store the line count corresponding to the row in the Excel document that is rejected
        $lineNumber = 2; //counter for Excel file row number, assuming the first Excel row is header
        
        //Get grade scale from database
        //$sql_gs = "SELECT title, break_off, unweighted_gp FROM report_card_grades WHERE grade_scale_id = 1 AND syear = {$_SESSION['UserSyear']} AND school_id = {$_SESSION[‘UserSchool’]} ORDER BY break_off DESC";
        $sql_gs = "SELECT rc.title, rc.break_off, rc.unweighted_gp, rcs.gp_scale FROM report_card_grades AS rc
        JOIN report_card_grade_scales AS rcs ON rc.grade_scale_id = rcs.id
        WHERE rc.grade_scale_id = 1 AND rc.syear = '{$_SESSION['UserSyear']}' AND rc.school_id = {$_SESSION['UserSchool']} 
        ORDER BY break_off DESC;";
        $gsList = DBGET(DBQUERY($sql_gs));
        //end Grade scale get
        ///////////////////////////Begin TROUSERS Grades Data Processing////////////////////////////////////////////////////////////
 
        foreach ($arr_data as $arr_i => $arr_v) {
            
            $check_query = array();

            //check student id exists
            //Get Student ID
            if($arr_i > 0){
                $stu_id_sql="SELECT student_id FROM students WHERE alt_id='{$arr_v[$array_index['ALT_ID']]}'";
                $stu_RET=DBGET(DBQUERY($stu_id_sql));
                if(count($stu_RET) == 0){
                    $student_id = 0; //if Student ID not found, set value to none and use this to skip the record on final insert
                } else {
                    $student_id = $stu_RET[1]['STUDENT_ID'];
                }
            }

            //Start student grade processing
            //$subjectTitle = $arr_v[$stp];
            if($arr_i > 0 AND $student_id > 0){
                $termId = $arr_v[$array_index['TROUSERS_TERM_ID']];
                $history_school_name = $arr_v[$array_index['SCHOOL_NAME']];
                $UserSchool = "'" . singleQuoteReplace("", "", UserSchool()) . "'";

                $period = substr($termId,5);
                $t_year = substr($termId,0,4);

                //Need to find the start month of current school semesters/trimesters
                //lookup current year semester start and end dates
                $sem_sql = "SELECT start_date, end_date FROM school_semesters WHERE syear = {$_SESSION['syear']} ORDER BY start_date ASC";
                $sem_RET=DBGET(DBQUERY($sem_sql));
                $p = 1; //a marker for the term period (1,2,3...n). Expecting schools will use 1,2 for semesters OR 1,2,3 for trimesters
                $term = array(); //temp array to hold approximate start and end months of semester/trimester
                //try to guess the approximate start and end of the school terms
                foreach($sem_RET as $key=>$value){
                    $date_str = explode("-",$value);
                    if($key == 'start_date'){
                        $dayOfWeek = date("l",$value);
                        $term[$p]['startMonth'] = $date_str[1];
                        $term[$p]['startDay'] = date("Y-m-d",strtotime("first $dayOfWeek $t_year-{$date_str[1]}"));
                    } else {
                        $dayOfWeek = date("l",$value);
                        $term[$p]['endMonth'] = $date_str[1];
                        $term[$p]['endDay'] = date("Y-m-d",strtotime("last $dayOfWeek $t_year-{$date_str[1]}"));
                    }
                    $p++;
                }
	            $start = $term[$period]['startDay'];
	            $end = $term[$period]['endDay'];
        
                //OLD WAY
                /*
                //set default values as start and end of calendar year
                $start = '01/01/'.$t_year;
                $end = '12/31/'.$t_year;

                if($period == 1){
	                $start = '01/01/'.$t_year;
	                $end = '03/31/'.$t_year;
                }

                if($period == 2){
	                $start = '05/01/'.$t_year;
	                $end = '07/31/'.$t_year;
                }

                if($period == 3){
	                $start = '09/01/'.$t_year;
	                $end = '11/30/'.$t_year;
                }

                $end_date = strtotime($end);
                $t_postdate = date("Y-m-d",$end_date);
                */

                //$arr_v is the entire Excel row. $arr_v[X] is the cell value.  If $mp_columns is the table column header array, then
                //$arr_v[$array_index[$mp_columns_v]] is matchin the arr_v value (the cell) with the designated key/column name as defined by $mp_columns and searched for by the array_index array
                
                //////////////Find or Create a value for marking_period_id table and insert records into history_marking_period table
                $mp_select_sql="SELECT marking_period_id FROM history_marking_periods WHERE name='{$arr_v[$array_index['TROUSERS_TERM_NAME']]}' AND syear=$t_year";
                $mp_id_RET = DBGET(DBQuery($mp_select_sql));
                //print_r("</p><p>The mp id return is: "); 
                if(count($mp_id_RET) == 0){
                    $mp_gen_sql="INSERT INTO marking_period_id_generator (id) VALUES (NULL)";
                    $mp_gen_RET=DBQuery($mp_gen_sql);
                    if($mp_gen_RET){
                        $mp_gen_id_RET = DBGet(DBQuery('SELECT max(id) AS ID from marking_period_id_generator'));
                        $mpId = $mp_gen_id_RET[1]['ID'];
                        $mp_type = 'Semester'; //Full Year, Semester, or Quarter is required.  Assuming TROUSERS data will be organized as 'Trimesters' but OASIS doesn't accept Trimester as valid.
                        //$mp_name = $arr_v[$array_index['TROUSERS_TERM_NAME']]; //might need this to be TROUSERS_TERM_ID.
                        $mp_name = $termId . "-TROUSERS"; //Ex. TermID is 199801, so name will be 199801-TROUSERS
                        $mp_insert_sql = "INSERT INTO history_marking_periods (mp_type,name,post_end_date,school_id,syear,marking_period_id) VALUES ('$mp_type','$mp_name','$t_postdate',$UserSchool,$t_year,$mpId)";
                        DBQuery($mp_insert_sql);
                    } else {
                        $mpId = NULL; //set to NULL and use this to cause final insert to fail
                    }
                } else {
                    $mpId = $mp_id_RET[1]['MARKING_PERIOD_ID'];
                }

                //Insert History SChool Name into history_school
                $hs_select_sql = "SELECT id FROM history_school WHERE student_id='$student_id' AND marking_period_id='$mpId' AND school_name='$history_school_name'";
                $hs_id_RET = DBGET(DBQuery($hs_select_sql));
                                
                if(count($hs_id_RET) == 0){
                    $hs_insert_sql = "INSERT INTO history_school (student_id,marking_period_id,school_name) VALUES ($student_id, $mpId,'$history_school_name')";
                    DBQuery($hs_insert_sql);
                }
                
                //////////////Prepare records for student_report_card_grades table
                foreach ($rc_columns as $rc_columns_v){
                    //,'credit_attempted','credit_earned','course_code','course_title');
                    if($rc_columns_v == 'STUDENT_ID'){
                        $rc_keys[] = $rc_columns_v;
                        $rc_values[] = $student_id;
                    } elseif($rc_columns_v == 'SYEAR'){
                        $rc_keys[] = $rc_columns_v;
                        //$rc_values[] = UserSyear();
                        $rc_values[] = "'" . singleQuoteReplace("", "", UserSyear()) . "'";

                    } elseif($rc_columns_v == 'SCHOOL_ID'){
                        $rc_keys[] = $rc_columns_v;
                        //$rc_values[] = UserSchool();
                        $rc_values[] = $UserSchool;
                    } elseif ($rc_columns_v == 'MARKING_PERIOD_ID'){
                        $rc_keys[] = $rc_columns_v;
                        $rc_values[] = $mpId;
                    } elseif ($rc_columns_v == 'GRADE_LETTER'){
                        //A simple TROUSERS report does not export grade letter. Only grade percent. Use the grade percent to match to the letter/title of the grade scale
                        $rc_keys[] = $rc_columns_v;
                        //find the gradescale from the database
                        foreach($gsList AS $gradeInfo){
                            if($arr_v[$array_index['GRADE_PERCENT']] >= $gradeInfo['breakoff']){
                                $rc_values[] = $gradeInfo['title']; //title is name of the grade letter column in the db table
                                break; //get out of the loop or else lower grade letters will be assigned
                            }
                        }

                        /*//Old way using defined 4 point scale
                        if ($arr_v[$array_index['GRADE_PERCENT']] >= 90){
                            $rc_values[] = "'A'";
                        }
                        if ($arr_v[$array_index['GRADE_PERCENT']] >= 80 AND $arr_v[$array_index['GRADE_PERCENT']] < 90){
                            $rc_values[] = "'B'";
                        }
                        if ($arr_v[$array_index['GRADE_PERCENT']] >= 70 AND $arr_v[$array_index['GRADE_PERCENT']] < 80){
                            $rc_values[] = "'C'";
                        }
                        if ($arr_v[$array_index['GRADE_PERCENT']] >= 60 AND $arr_v[$array_index['GRADE_PERCENT']] < 70){
                            $rc_values[] = "'D'";
                        }
                        if ($arr_v[$array_index['GRADE_PERCENT']] < 60){
                            $rc_values[] = "'F'";
                        }
                        //End old way using 4 point scale*/
                    } elseif ($rc_columns_v == 'UNWEIGHTED_GP'){
                        $rc_keys[] = $rc_columns_v;
                        foreach($gsList AS $gradeInfo){
                            if($arr_v[$array_index['GRADE_PERCENT']] >= $gradeInfo['breakoff']){
                                $rc_values[] = $gradeInfo['unweighted_gp'];
                                break; //get out of the loop or else lower grade point will be assigned
                            }
                        }
                        /*//OLD way using pre-defined 4 point scale
                        if ($arr_v[$array_index['GRADE_PERCENT']] >= 90){
                            $rc_values[] = '4';
                        }
                        if ($arr_v[$array_index['GRADE_PERCENT']] >= 80 AND $arr_v[$array_index['GRADE_PERCENT']] < 90){
                            $rc_values[] = '3';
                        }
                        if ($arr_v[$array_index['GRADE_PERCENT']] >= 70 AND $arr_v[$array_index['GRADE_PERCENT']] < 80){
                            $rc_values[] = '2';
                        }
                        if ($arr_v[$array_index['GRADE_PERCENT']] >= 60 AND $arr_v[$array_index['GRADE_PERCENT']] < 70){
                            $rc_values[] = '1';
                        }
                        if ($arr_v[$array_index['GRADE_PERCENT']] < 60){
                            $rc_values[] = '0';
                        }
                        //END old way of pre-defined 4point scale */
                    } elseif ($rc_columns_v == 'GP_SCALE'){
                        $rc_keys[] = $rc_columns_v;
                        $rc_values[] = $gsList[1]['gp_scale'];
                        /*//OLD way using pre-defined gp 4
                        $rc_values[] = 4;*/
                    } elseif ($rc_columns_v == 'GPA_CAL'){
                        $rc_keys[] = $rc_columns_v;
                        $rc_values[] = "'Y'";
                    } elseif ($rc_columns_v == 'CREDIT_EARNED'){
                    //CONTINUE HERE.  NEED a way to capture credit attempted if School uses the TROUSERS Enrollment export report.  THis one does not include credits
                        $rc_keys[] = $rc_columns_v;
                        //COTINUE HERE
                        //if the imported file does not include a credit earned but the grade percent is above 60,then assign a credit hours
                        if($arr_v[$array_index[$rc_columns_v]] == 0 AND $arr_v[$array_index['GRADE_PERCENT']] > 60){
                            //$rc_values[] = $arr_v[$array_index['CREDIT_ATTEMPTED']];
                            $rc_values[] = "'" . singleQuoteReplace("", "", $arr_v[$array_index['CREDIT_ATTEMPTED']]) . "'";
                        }
                    } else {
                        //all other columns values come from the Excel file upload
                        //COURSE_CODE, COURSE_TITLE
                        $rc_keys[] = $rc_columns_v;
                        //$rc_values[] = $arr_v[$array_index[$rc_columns_v]];
                        $rc_values[] = "'" . singleQuoteReplace("", "", $arr_v[$array_index[$rc_columns_v]]) . "'";
                    }
                }
                
                //Check if student grades exists in the student_report_card_grades db table
                $q_count = count($rc_keys);
                $i=0;
                while ($i < $q_count){
                    $check_query_rc[] =  $rc_keys[$i] . '=' . $rc_values[$i];
                    $i++;
                }

                if(count($check_query_rc) > 0) {

                    $check_exist = DBGet(DBQuery('SELECT COUNT(*) as REC_EXISTS FROM student_report_card_grades WHERE ' . implode(" AND ", $check_query_rc)));

                    $check_exist = $check_exist[1]['REC_EXISTS'];
                
                    if ($check_exist != 0){
                        $err_msg[0]= 'duplicate course';
                    }
                }
                unset($check_query_rc); //get ready for next row of data
                
                if($check_exist == 0) {
                   DBQuery('INSERT INTO student_report_card_grades (' . implode(',', $rc_keys) . ') VALUES (' . implode(',', $rc_values) . ')');
                   $accepted++;
                } else {
                    $rejected++;
                    $lineReject[] = $lineNumber;
                }
                $records++;
                $lineNumber++;
                unset($rc_keys);
                unset($rc_values);
            }
        }

        if ($records > 0) {
            if ($records == $accepted) {
                echo '<div class="text-center"><img src="assets/images/check-clipart-animated.gif"></div>';
                echo '<h2 class="text-center text-success m-b-0">Congratulations !!!</h2>';
                echo '<h5 class="text-center m-t-0 m-b-35 text-grey">The data import has successfully concluded.</h5>';
            } elseif ($accepted > 0 && $rejected > 0) {
                echo '<div class="text-center"><img src="assets/images/info-icon-animated.gif" width="90"></div>';
                echo '<h2 class="text-center text-warning m-b-0">Partial Import !!!</h2>';
                echo '<h5 class="text-center m-t-0 m-b-35 text-grey">Some data could not be processed.</h5>';
            } elseif ($accepted == 0) {
                echo '<div class="text-center"><img src="assets/images/error-icon-animated.gif" width="100"></div>';
                echo '<h2 class="text-center text-danger m-b-0 m-t-0">Oops !!!</h2>';
                echo '<h5 class="text-center m-t-0 m-b-35 text-grey">The data import was rejected by the system.</h5>';
            }

            echo '<div class="row m-b-10">';
            echo '<div class="col-xs-10">Number of input records:</div><div class="col-xs-2">' . $records . '</div>';
            echo '</div>';
            echo '<div class="row m-b-10">';
            echo '<div class="col-xs-10">Number of records loaded into the database:</div><div class="col-xs-2">' . $accepted . '</div>';
            echo '</div>';
            echo '<div class="row m-b-10">';
            echo '<div class="col-xs-10">Number or records rejected:</div><div class="col-xs-2">' . (($rejected > 0) ? '<span class="text-danger">' . $rejected . '</span>' : $rejected) . '</div>';
            if ($rejected > 0 AND !empty($lineReject)){
                echo '<div class="col-xs-10">The following rows in the Excel file were rejected:<br>';
                echo '<ul>';
                foreach ($lineReject as $line){
                    echo "<li>$line</li>";
                }
                echo '</ul></div>';
            }
            echo '</div>';

            if(count($err_msg)==1){
                $msg='';
                foreach($err_msg as $key=>$val)
               {
                   $msg=$val;
               }
                echo '<div class="row m-t-10">';
                echo '<div class="col-xs-12 text-danger"><i class="icon-info22"></i> Possible cause for rejection is '.$msg.' found.</div>';
                echo '</div>';
            }
            if(count($err_msg)==2){
                $msg='';
               foreach($err_msg as $key=>$val)
               {
                   $msg.= $val.' and ';
               }
               $msg=substr($val,0,-5);
                echo '<div class="row m-t-10">';
                echo '<div class="col-xs-12 text-danger"><i class="icon-info22"></i> Possible causes for rejection are '.$msg.' found.</div>';
                echo '</div>';
            }
            if(count($err_msg)==3){
                $msg='';
               foreach($err_msg as $key=>$val)
               {
                   $msg.= $val.' and ';
               }
               $msg=substr($val,0,-5);
                echo '<div class="row m-t-10">';
                echo '<div class="col-xs-12 text-danger"><i class="icon-info22"></i> Possible causes for rejection are '.$msg.' found.</div>';
                echo '</div>';
            }            

        }
        unset($err_msg);
        unset($arr_data);
        unset($_SESSION['data']);
        unset($_SESSION['trousers_grades']);
        unset($array_index);
        unset($temp_array_index);
    }
}
//================================TROUSERS Grade info Ends==============================================
//END Africa's Hope Addition
echo '</div>'; //.panel-body
echo '<div class="panel-footer text-center"><a href="Modules.php?modname=tools/DataImport.php" class="btn btn-default"><i class="icon-arrow-left8"></i> Back to Data Import Tool</a></div>';

function fromExcelToLinux($excel_time) {
    $ex_date = ($excel_time - 25569) * 86400;

    return gmdate("Y-m-d", $ex_date);

}

?>
