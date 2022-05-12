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
#
# This file was added by Africa's Hope modification of OpenSIS 7.6
# https://africashope.org/
# Created 2020-12-15 by Evan Stewart
#***************************************************************************************

/* FOR TROUBLESHOOTING
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

include('../../RedirectModules.php');
require_once("Data.php");
$print_form = 1;
$output_messages = array();


ini_set('memory_limit', '9000M');

ini_set('max_execution_time', '50000');
ini_set('max_input_time', '50000');

$host = $DatabaseServer;
$name = $DatabaseName;
$user = $DatabaseUsername;
$pass = $DatabasePassword;
$port = $DatabasePort;

if (('Export' == $_REQUEST['action']) || ($_REQUEST['action'] == 'export')) {
   
    $dataExport = $_POST['select_data'];
    $print_form = 0;
    $date_time = date("m-d-Y");
    $Export_FileName = $name . '-' . $dataExport . '-Export' . $date_time . '.csv';
    $dbconn = new mysqli($host, $user, $pass, $name, $port);
    if ($dbconn->connect_errno != 0){
        exit($dbconn->error);
    }

    $simpleStudentQ = "SELECT  
    students.student_id,
    students.last_name,
    students.first_name,
    students.middle_name,
    students.name_suffix,
    students.gender,
    ethnicity.ethnicity_name,
    students.common_name,
    students.birthdate,
    language.language_name,
    students.estimated_grad_date,
    students.alt_id,
    students.email,
    students.phone,
    HA.street_address_1 AS home_address,
    HA.city AS home_city,
    HA.state AS home_state,
    HA.zipcode AS home_zipcode,
    MA.street_address_1 AS mail_address,
    MA.city AS mail_city,
    MA.state AS mail_state,
    MA.zipcode AS mail_zipcode,
    students.is_disable
    FROM ((((students 
    LEFT OUTER JOIN ethnicity on students.ethnicity_id=ethnicity.ethnicity_id)
    LEFT OUTER JOIN language on students.language_id=language.language_id)
    LEFT OUTER JOIN student_address HA on students.student_id=HA.student_id AND HA.type='Home Address')
    LEFT OUTER JOIN student_address MA on students.student_id=MA.student_id AND MA.type='Mail')
    WHERE students.student_id > 0
    ";
    
    $simpleStaffQ = "SELECT 
    staff.staff_id,
    schools.title AS school,
    staff.title,
    staff.last_name,
    staff.first_name,
    staff.middle_name,
    staff.name_suffix,
    staff.gender,
    ethnicity.ethnicity_name,
    staff.birthdate AS birthdate_YYYY_MM_DD,
    L1.language_name AS primary_language,
    L2.language_name AS second_language,
    L3.language_name AS third_language,
    staff.alternate_id,
    staff.email,
    staff.phone,
    staff.homeroom,
    staff.profile,
    staff.profile_id,
    staff.is_disable
    FROM (((((staff 
    LEFT OUTER JOIN schools on staff.current_school_id=schools.id)
    LEFT OUTER JOIN ethnicity on staff.ethnicity_id=ethnicity.ethnicity_id)
    LEFT OUTER JOIN language L1 on staff.primary_language_id=L1.language_id)
    LEFT OUTER JOIN language L2 on staff.second_language_id=L2.language_id)
    LEFT OUTER JOIN language L3 on staff.third_language_id=L3.language_id)
    WHERE staff.staff_id > 0
    ";

    $sql = "";

    if($dataExport == 'simpleStudents'){
        $sql = $simpleStudentQ;
    }

    if($dataExport == 'simpleStaff'){
        $sql = $simpleStaffQ;
    }

    if($sql != ""){
        $result = DBGet(DBQuery($sql));
    } else{
        echo "<b>" . _exportFailedInvalidDataSelectPleaseTryAgain . "</b>";
        exit;
    }

    $content = ""; //decalre content variable
    
    //Format data in CSV formatting
    foreach ($result as $row => $arr){
        //Capture the column headers from the first array index (same for all others)
        if($row == 1){
            foreach ($arr as $key => $item){
                    $content .= "$key,";
                }
                $content .= "\r\n";
            }
        //Capture the row content
        foreach ($arr as $key => $item){
            if (empty($item)){
                $content .= "\"na\",";
            } else {
                $content .= "\"$item\",";
            }
        }
        $content .= "\r\n"; //Line break
    }

    ob_get_clean(); //clear the buffer
    header("Content-Type: text/csv");
    header("Content-Transfer-Encoding: Binary");
    header("Content-Disposition: attachment; filename=\"" . $Export_FileName . "\"");
    ob_get_clean(); //clear buffer again to remove line breaks generated by header commands.  If buffer is not cleaned here again, the text file generated will contain three line breaks at the top of file
    echo $content;
  
    exit;
}


if ($print_form > 0 && !$_REQUEST['modfunc'] == 'cancel') {
    ?>
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <form id="dataForm" name="dataForm" method="post" action="ForExport.php?modname=tools/DataExport.php&action=export&_openSIS_PDF=true" target=_blank>
                <?php
                PopTable('header',  'Export');
                echo '<h4 class="text-danger">'._note.':</h4><p>'._exportTableInfo.'</p>';
                echo "<select name='select_data' id='select_data'>
                <option value='simpleStudents'>"._students."</option>
                <option value='simpleStaff'>"._staff."</option>
                </select>";

                $btn = '<input type="submit" name="action"  value="Export" class="btn btn-primary"> &nbsp; ';
                $modname = 'tools/DataExport.php';
                $btn .= '<a href=javascript:void(0); onClick="check_content(\'Ajax.php?modname=miscellaneous/Portal.php\');" STYLE="TEXT-DECORATION: NONE"> <INPUT type=button class="btn btn-default" name=Cancel value="'._cancel.'"></a>';
                
                PopTable('footer', $btn);
                ?>
            </form>
        </div>
    </div>
    <?php
}

?>
