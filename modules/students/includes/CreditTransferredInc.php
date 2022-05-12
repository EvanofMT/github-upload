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
#
# This file was added by Africa's Hope modification of OpenSIS 7.6
# https://africashope.org/
# Created 2020-12-15 by Evan Stewart
#***************************************************************************************

include('../../../RedirectIncludes.php');

include_once('modules/students/includes/FunctionsInc.php');


//ADDED ES - TROUBLESHOOTING stuff. Uncomment this section to see variables in play
/*
if (isset($_POST)){
    foreach($_POST as $key => $item){
        echo "The POST $key is $item<br><br>";
    }
}

if (isset($_GET)){
    foreach($_GET as $key => $item){
        echo "The GET $key is $item<br><br>";
    }
}
echo "The URL is " . curPageURL() ."<br><br>";
echo "The POST values from the form are <br><br>";
print_r($_REQUEST['values']);
echo "<br><br>";

echo User('PROFILE') . " and ID of " . User('PROFILE_ID');
*/


if (clean_param($_REQUEST['modfunc'], PARAM_ALPHAMOD) == 'delete') {
    if (!$_REQUEST['delete_ok'] && !$_REQUEST['delete_cancel'])
        echo '</FORM>';

    if (DeletePromptMod($_REQUEST['title'], '&include=CreditTransferredInc&category_id=' . $_REQUEST['category_id'])) { //CHANGED ES
        DBQuery("DELETE FROM $_REQUEST[table] WHERE ID='$_REQUEST[id]'");
        unset($_REQUEST['modfunc']);
    }
}

if (clean_param($_REQUEST['modfunc'], PARAM_ALPHAMOD) == 'update')
    unset($_REQUEST['modfunc']);
    
if (!$_REQUEST['modfunc']) {
    echo '<div id="dc"></div>';
    echo '<h5 class="text-primary">' . _creditTransferred . '</h5>
    <p>'._toAddOrEditCreditsTransferredFromAnotherSchoolUseTheMenu.' <b>'._grades.'</b> > <b>'._utilities.'</b> > <b>'._addEditHistoricalReportCardGrades.'</b></p>';
    //CHANGED ES
    //I'm not sure why setting the category_id is necessary.  This was used in MedicalInc.php from OpenSIS 7.5.
    //But category_id is already set when this script is activated.  Uncomment if problems arise in the future
    //$_REQUEST['category_id'] = 8;
    
    //Credit Transferred Records
    //$table = 'student_credit_transferred';
    //Functions used to assign edit capabilities to form fields.  Credits Transferred is a report and doesn't need edit ability
    //$functions = array('CREDIT_HOURS' => '_makeComments','SCHOOL_NAME' => '_makeComments','SCHOOL_LOCATION' => '_makeComments');
    $functions = '';
    //$cred_RET = DBGet(DBQuery('SELECT ID, CREDIT_HOURS,TRANS_DATE,SCHOOL_NAME,SCHOOL_LOCATION FROM ' . $table . ' WHERE STUDENT_ID=\'' . UserStudentID() . '\' ORDER BY TRANS_DATE,SCHOOL_NAME'), $functions);
    $cred_RET = DBGet(DBQuery('SELECT rcg.credit_earned, hs.school_name, hmp.name AS term, hmp.syear AS year_earned, rcg.course_code, rcg.course_title FROM student_report_card_grades rcg INNER JOIN history_marking_periods hmp ON rcg.marking_period_id = hmp.marking_period_id AND rcg.school_id = hmp.school_id INNER JOIN history_school hs ON rcg.marking_period_id = hs.marking_period_id AND rcg.student_id = hs.student_id WHERE rcg.student_id = ' . UserStudentID() . ' AND rcg.school_id = ' . UserSchool() . ' ORDER BY hmp.syear'), $functions);
    $columns = array('CREDIT_EARNED' =>_creditHours,
    'SCHOOL_NAME' =>_schoolName,
    'TERM' =>_term, 
    'YEAR_EARNED' =>_yearEarned,
    'COURSE_TITLE' => _courseTitle, 
    );
    /*
    //USE this area for enabling form edits.  Credits Transferred is a report and doesn't need edit ability
    foreach ($cred_RET as $mi => $md) {
        $counter_for_date = $counter_for_date + 1;
        $cred_RET[$mi]['TRANS_DATE'] = _makeDate($md['TRANS_DATE'], 'TRANS_DATE', $counter_for_date, array('ID' => $md['ID'], 'TABLE' => $table));
    }
    $counter_for_date = $counter_for_date + 1;
   
    $link['add']['html'] = array('CREDIT_HOURS' => _makeComments('', 'CREDIT_HOURS'),'TRANS_DATE' => _makeDate('', 'TRANS_DATE', $counter_for_date), 'SCHOOL_NAME' => _makeComments('', 'SCHOOL_NAME'), 'SCHOOL_LOCATION' => _makeComments('', 'SCHOOL_LOCATION'));
    $link['remove']['link'] = "Modules.php?modname=$_REQUEST[modname]&include=$_REQUEST[include]&modfunc=delete&table=$table&title=" . urlencode(_creditTransferred);
    $link['remove']['variables'] = array('id' => 'ID');
    */

    if (count($cred_RET) == 0){
        $plural = _creditTransferred;
    }else{
        $plural = _creditsTransferred;
    }
    
    echo '<div class="panel panel-default"><div class="panel-heading"><h5 class="panel-title">'._creditTransferred.'</h5></div>';
    //Handles the creation of HTML FORM to show content from DB
    if (!isset($_REQUEST['dwnl']) || $_REQUEST['dwnl'] == 'degrees'){
        echo '<div class="table-responsive">';
    //FROM EnrollmentInfoInc.php
       ListOutputMod($cred_RET, $columns, _creditsTransferred,$plural, $link, array(), array('count' =>false));
        echo '</div>';
        echo '</div>';
    }
}
?>