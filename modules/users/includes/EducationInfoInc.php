<?php

#**************************************************************************
#  OASIS is a free student information system for public and non-public 
#  schools from Africa's Hope based on OpenSIS developed by Open Solutions for Education, Inc. web: www.os4ed.com
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
#
# This file was created by Africa's Hope modification of OpenSIS 7.6
# https://africashope.org/
# Created 2020-12-15 by Evan Stewart
#***************************************************************************************

include('../../../RedirectIncludes.php');

include_once('modules/users/includes/FunctionsInc.php');

//ADDED ES - TROUBLESHOOTING stuff. Uncomment this section to see variables in play
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_POST)){
    echo "<b>The POST array</b><br><ul>";
    foreach($_POST as $key => $item){
        echo "<li><b>$key</b>: $item";
        echo "<ul>";
        foreach($item as $sub_key => $sub_item){
            echo "<li><b>$sub_key</b>: $sub_item</li>";
        }
        echo "</ul>";
        echo "</li>";
    }
    echo "</li>";
    echo "</ul>";

    //echo "The POST array is: <br>" . print_r($_POST) . "<br>";
}
if (isset($_GET)){
    echo "<b>The GET array</b><br><ul>";
    foreach($_GET as $key => $item){
        echo "<li><b>$key</b>: $item</li>";
    }
    echo "</ul>";
}
//echo "The modname is: " . $_REQUEST['modname'] . "<br><br>";
echo "The URL is " . curPageURL() ."<br><br>";
echo "The REQUEST values from the form are<br>";
if (isset($_REQUEST)){
    echo "<b>The REQUEST array</b><br><ul>";
    foreach($_REQUEST as $key => $item){
        echo "<li><b>$key</b>: $item</li>";
    }
    echo "</ul>";
}
echo "<br><br>";
echo "The staff array value Staff_ID is" . $staff['STAFF_ID'] . " and the session value staff_selected is " . $_SESSION['staff_selected'];
echo User('PROFILE') . " and ID of " . User('PROFILE_ID');

//END troubleshooting
*/

$table = 'staff_degrees_earned';

//If the deleted button was clicked, the command is processed by Staff.php, line 981, and then loads here
if (clean_param($_REQUEST['modfunc'], PARAM_ALPHAMOD) == 'remove') {
    
    DeletePromptStaffDegree($_REQUEST['title'],$_REQUEST['id']);

    if (!$_REQUEST['delete_ok'] && !$_REQUEST['delete_cancel']){
        echo '</FORM>';
       unset($_REQUEST['modfunc']);
    }
}

//delete_ok set in the block above by DeletePromptStaffDegree
if ($_REQUEST['delete_ok']==1) {
    DBQuery("DELETE FROM $table WHERE ID={$_REQUEST['id']}");
    unset($_REQUEST['modfunc']);
}

if (clean_param($_REQUEST['modfunc'], PARAM_ALPHAMOD) == 'update')
    unset($_REQUEST['modfunc']);

if (!$_REQUEST['modfunc']) {
    echo '<div id="dc"></div>';
    echo '<h5 class="text-primary">' . _educationInformation . " - " ._degreesEarned . '</h5>';

    //Degrees Earned Records
    //$table = 'staff_degrees_earned';
    $sql_deg_RET = 'SELECT ID,DEGREE_TYPE,FIELD_OF_STUDY,EARNED_DATE,SCHOOL_NAME,SCHOOL_LOCATION FROM ' . $table . ' WHERE STAFF_ID=\'' . UserStaffID(). '\' ORDER BY EARNED_DATE,DEGREE_TYPE';
    $functions = array('DEGREE_TYPE' => '_makeComments','FIELD_OF_STUDY' => '_makeComments','SCHOOL_NAME' => '_makeComments','SCHOOL_LOCATION' => '_makeComments');
    $deg_RET = DBGet(DBQuery($sql_deg_RET), $functions);
    $columns = array('DEGREE_TYPE' =>_degreeType,
     'FIELD_OF_STUDY' =>_fieldOfStudy,
     'EARNED_DATE' =>_date.' <span class="dateTip">'._dateFormatTip.'</span>',
     'SCHOOL_NAME' =>_schoolName,
     'SCHOOL_LOCATION' =>_schoolLocation,
    );

    foreach ($deg_RET as $mi => $md) {
        $counter_for_date = $counter_for_date + 1;
        $deg_RET[$mi]['EARNED_DATE'] = _makeDate($md['EARNED_DATE'], 'EARNED_DATE', $counter_for_date, array('ID' => $md['ID'], 'TABLE' => $table));
    }
    $counter_for_date = $counter_for_date + 1;
    $link['add']['html'] = array('DEGREE_TYPE' => _makeComments('', 'DEGREE_TYPE'), 'FIELD_OF_STUDY' => _makeComments('', 'FIELD_OF_STUDY'),'EARNED_DATE' => _makeDate('', 'EARNED_DATE', $counter_for_date), 'SCHOOL_NAME' => _makeComments('', 'SCHOOL_NAME'), 'SCHOOL_LOCATION' => _makeComments('', 'SCHOOL_LOCATION'));
    $link['remove']['link'] = "Modules.php?modname=$_REQUEST[modname]&include=$_REQUEST[include]&modfunc=remove&table=$table&title=" . urlencode(_educationInformation);
    //$link['remove']['link'] = "Modules.php?modname=$_REQUEST[modname]&include=$_REQUEST[include]&modfunc=remove&table=$table";
    $link['remove']['variables'] = array('id' => 'ID');

    if (count($deg_RET) == 0){
        $plural = _degreeEarned;
    }else{
        $plural = _degreesEarned;
    }

    echo '<div class="panel panel-default"><div class="panel-heading"><h5 class="panel-title">'._educationInformation.' - '._degreesEarned.'</h5></div>';
    //Handles the creation of HTML FORM to show content from DB
    //if (!isset($_REQUEST['dwnl']) || $_REQUEST['dwnl'] == 'degrees'){
        echo '<div class="table-responsive">';
        ListOutputMod($deg_RET, $columns, _degreeEarned,$plural, $link, array(), array('count' =>false));
        echo '</div>';
        echo '</div>';
    //}
}
?>