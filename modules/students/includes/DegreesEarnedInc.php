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
echo "The REQUEST values from the form are<br>";
print_r($_REQUEST['values']);
echo "<br><br>";
echo User('PROFILE') . " and ID of " . User('PROFILE_ID');
*/
//END Troubleshooting

if (clean_param($_REQUEST['modfunc'], PARAM_ALPHAMOD) == 'delete') {
    if (!$_REQUEST['delete_ok'] && !$_REQUEST['delete_cancel'])
        echo '</FORM>';

    if (DeletePromptMod($_REQUEST['title'], '&include=DegreesEarnedInc&category_id=' . $_REQUEST['category_id'])) { //CHANGED ES
        DBQuery("DELETE FROM $_REQUEST[table] WHERE ID='$_REQUEST[id]'");
        unset($_REQUEST['modfunc']);
    }
}

if (clean_param($_REQUEST['modfunc'], PARAM_ALPHAMOD) == 'update')
    unset($_REQUEST['modfunc']);

if (!$_REQUEST['modfunc']) {
    echo '<div id="dc"></div>';
    echo '<h5 class="text-primary">' . _degreesEarned . '</h5>';
    //CHANGED ES
    //I'm not sure why setting hte category_id is necessary.  This was used in MedicalInc.php from OpenSIS 7.5.
    //But category_id is already set when this script is activated.  Uncomment if problems arise in the future
    //$_REQUEST['category_id'] = 9;

    //Degrees Earned Records
    $table = 'student_degrees_earned';
    //Changed ES - change _makeType to _makeTypeD
    //Using drop down menu for TYPE.  But this requires a list to be created.  Easier to make a free text field -- $functions = array('TYPE' => '_makeTypeD','FIELD_OF_STUDY' => '_makeComments','SCHOOL_NAME' => '_makeComments','SCHOOL_LOCATION' => '_makeComments');
    $functions = array('DEGREE_TYPE' => '_makeComments','FIELD_OF_STUDY' => '_makeComments','SCHOOL_NAME' => '_makeComments','SCHOOL_LOCATION' => '_makeComments');
    $sql_deg_RET = 'SELECT ID,DEGREE_TYPE,FIELD_OF_STUDY,EARNED_DATE,SCHOOL_NAME,SCHOOL_LOCATION FROM ' . $table . ' WHERE STUDENT_ID=\'' . UserStudentID() . '\' ORDER BY EARNED_DATE,DEGREE_TYPE';
    $deg_RET = DBGet(DBQuery('SELECT ID,DEGREE_TYPE,FIELD_OF_STUDY,EARNED_DATE,SCHOOL_NAME,SCHOOL_LOCATION FROM ' . $table . ' WHERE STUDENT_ID=\'' . UserStudentID() . '\' ORDER BY EARNED_DATE,DEGREE_TYPE'), $functions);
    
    $columns = array('DEGREE_TYPE' =>_type,
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
    //CHANGE by ES
    //IF using Drop Down menu for Type -- $link['add']['html'] = array('TYPE' => _makeTypeD('', 'TYPE'), 'FIELD_OF_STUDY' => _makeComments('', 'FIELD_OF_STUDY'),'EARNED_DATE' => _makeDate('', 'EARNED_DATE', $counter_for_date), 'SCHOOL_NAME' => _makeComments('', 'SCHOOL_NAME'), 'SCHOOL_LOCATION' => _makeComments('', 'SCHOOL_LOCATION'));
    $link['add']['html'] = array('DEGREE_TYPE' => _makeComments('', 'DEGREE_TYPE'), 'FIELD_OF_STUDY' => _makeComments('', 'FIELD_OF_STUDY'),'EARNED_DATE' => _makeDate('', 'EARNED_DATE', $counter_for_date), 'SCHOOL_NAME' => _makeComments('', 'SCHOOL_NAME'), 'SCHOOL_LOCATION' => _makeComments('', 'SCHOOL_LOCATION'));
    $link['remove']['link'] = "Modules.php?modname=$_REQUEST[modname]&include=$_REQUEST[include]&modfunc=delete&table=$table&title=" . urlencode(_degreesEarned);
    $link['remove']['variables'] = array('id' => 'ID');

    if (count($deg_RET) == 0){
        $plural = _degreesEarned;
    }else{
        $plural = _degreesEarned;
    }
    
    echo '<div class="panel panel-default"><div class="panel-heading"><h5 class="panel-title">'._degreesEarned.'</h5></div>';
    //Handles the creation of HTML FORM to show content from DB
    if (!isset($_REQUEST['dwnl']) || $_REQUEST['dwnl'] == 'degrees'){
        echo '<div class="table-responsive">';
       // ListOutput_Medical($deg_RET, $columns, _degreesEarned, $plural, $link, 'degrees', array(), array('search' =>false));
    //FROM EnrollmentInfoInc.php
       //ListOutputMod($deg_RET, $columns, _degreesEarned,$plural, $link, array(), array('count' =>false));
       ListOutput($deg_RET,$columns,_degreesEarned,$plural,$link,array(),array('search'=>false));
        echo '</div>';
        echo '</div>';
    }
}
?>