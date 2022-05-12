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
error_reporting(0);
echo "<div id='mapping'></div>";

include('../../RedirectModules.php');
//include('Classes/PHPExcel.php');
require('modules/tools/Classes/PHPExcel.php');
echo '<link rel="stylesheet" type="text/css" href="modules/tools/assets/css/tools.css">';
DrawBC(""._schoolSetup." > "._dataImport." >" . ProgramTitle());

function add_person($first, $middle, $last, $email) {
    global $data;

    $data [] = array(
        'first' => $first,
        'middle' => $middle,
        'last' => $last,
        'email' => $email
    );
}

//for troubleshooting
//print_r($_REQUEST);

//===============================Student info Begins==============================================
if (clean_param($_REQUEST['page_display'], PARAM_ALPHAMOD) == 'STUDENT_INFO') {

    if ($_REQUEST['action'] != 'insert' && $_REQUEST['action'] != 'display' && $_REQUEST['action'] != 'process') {
        echo '<div class="row">';
        echo '<div class="col-md-6 col-md-offset-3">';
        echo '<form enctype="multipart/form-data" action="Modules.php?modname=' . $_REQUEST['modname'] . '&action=insert&page_display=STUDENT_INFO" method="POST" onSubmit="return map_upload_validation();">';
        echo '<div class="panel panel-default">';
        echo '<div class="panel-body text-center">';

        echo '<h5 class="text-center">'._clickOnTheBrowseButtonToNavigateToTheExcelFileInYourComputerSHardDriveThatHasYourDataAndSelectIt.'. <b>'._afterSelectingClickUpload.'.</b></h5>';
        echo '<div class="form-group">';
        echo '<input type="hidden"  name="MAX_FILE_SIZE" value="2000000" />';
        echo '<div class="text-center"><label id="select-file-input"><input type="file" class="upload" id="file_id" name="file" /><i class="icon-upload"></i><br/><span>'._clickHereToSelectAFile.'</span></label></div>';
        echo '<p class="help-block">'._supportedFileTypesXlsXlsx.'</p>';
        echo '</div>';

        echo '</div>'; //.panel-body
        echo '<div class="panel-footer text-center"><input type="submit" class="btn btn-primary" value="'._upload.'" /> &nbsp; <a href="Modules.php?modname=' . $_REQUEST['modname'] . '" class="btn btn-default">'._cancel.'</a></div>';
        echo '</div>'; //.panel
        echo '</form>';
        echo '</div>'; //.col-md-6
        echo '</div>'; //.row
        ?>
        <script>
            $(function () {
                $('#file_id').change(function (e) {
                    var fileName = e.target.files[0].name;
                    $('#select-file-input span').html('<b>Selected File: </b><br/>' + fileName + '<br/>(click to change)');
                });

            });
        </script>
        <?php

    } elseif ($_REQUEST['action'] == 'insert') {
        $arr_data = array();
        echo '<form action="Modules.php?modname=' . $_REQUEST['modname'] . '&action=display&page_display=STUDENT_INFO" name="student_form"  method="POST">';
        echo '<div class="panel panel-default">';
        echo '<div class="panel-heading">';
        echo '<h4 class="text-center">'._pleaseCreateAOneToOneRelationshipBetweenTheFieldsInYourSpreadsheetAndTheFieldsInTheOpenSisDatabaseBySelectingTheAppropriateFieldsFromTheRightColumn.'. '._afterYouAreDoneClickMapIt.'.</h4>';
        echo '</div>'; //.panel-heading

        echo '<div class="panel-body p-0">';
        echo '<div class="table-responsive">';
        echo '<table class="table table-striped">';
	echo '<thead>';
        echo '<tr class="bg-grey-200"><th width="260">'._theseFieldsAreInYourExcelSpreadSheet.'</td><td width="200">&nbsp;</td><td>'._theseAreAvailableFieldsInOpenSis.'</td></tr>';
	echo '</thead>';
        $inputFileName = $_FILES['file']['tmp_name'];
        //$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
        //$objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        //$objReader->setReadDataOnly(true);  //ADDED by Africa's Hope - Evan Stewart - the object doesn't exist in the IOFactory.php file.  Disable to see if it breaks anything.
        /**  Load $inputFileName to a PHPExcel Object  * */
        $objPHPExcel = $objReader->load($inputFileName);
        $total_sheets = $objPHPExcel->getSheetCount(); // here 4  
        $allSheetName = $objPHPExcel->getSheetNames(); // array ([0]=>'student',[1]=>'teacher',[2]=>'school',[3]=>'college')  
        $objWorksheet = $objPHPExcel->setActiveSheetIndex(0); // first sheet  
        $highestRow = $objWorksheet->getHighestRow(); // here 5  
        $highestColumn = $objWorksheet->getHighestColumn(); // here 'E'  
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);  // here 5  
        for ($row = 1; $row <= $highestRow; ++$row) {
            for ($col = 0; $col <= $highestColumnIndex; ++$col) {
                $value = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
                if (is_array($arr_data)) {
                    $arr_data[$row - 1][$col] = $value;
                }
            }
        }

        $_SESSION['data'] = $arr_data;
        $options = array('ALT_ID' => _alternateId,
         'LAST_NAME' => _lastName,
         'MIDDLE_NAME' =>_middleName,
         'FIRST_NAME' =>_firstName,
         'NAME_SUFFIX' => _nameSuffix,
         'GENDER' => _gender,
         'ETHNICITY' => _ethnicity,
         'COMMON_NAME' => _commonName,
         'SOCIAL_SECURITY' => _socialSecurity,
         'BIRTHDATE' => _birthDate,
         'LANGUAGE' => _language,
         'EMAIL' => _emailStudentS,
         'PHONE' => _contactNoStudentS,
         'IS_DISABLE' => _disabled,
        );
        $options+=array('USERNAME' => _username,
         'PASSWORD' => _password,
        );
        $options+=array('GRADE_ID' => _grade,
         'SECTION_ID' => _section,
         'START_DATE' => _studentEnrollmentDate,
         'ESTIMATED_GRAD_DATE' => _estimatedGraduationDate,
         'END_DATE' => _studentEnrollmentEndDate,
        );

        $options+=array('STREET_ADDRESS_1' => _addressLine_1StudentS,
         'STREET_ADDRESS_2' => _addressLine_2StudentS,
         'CITY' => _cityStudentS,
         'STATE' => _stateStudentS,
         'ZIPCODE' => _zipcodeStudentS,
        );
        $options+=array('PRIMARY_FIRST_NAME' => _primaryFirstName,
         'PRIMARY_MIDDLE_NAME' => _primaryMiddleName,
         'PRIMARY_LAST_NAME' => _primaryLastName,
         'PRIMARY_WORK_PHONE' => _workPhonePrimaryContactS,
         'PRIMARY_HOME_PHONE' => _homePhonePrimaryContactS,
         'PRIMARY_CELL_PHONE' => _cellPhonePrimaryContactS,
         'PRIMARY_EMAIL' => _emailPrimaryContactS,
         'PRIMARY_RELATION' => _relationshipPrimaryContactS,
        );
        $options+=array('SECONDARY_FIRST_NAME' => _secondaryFirstName,
         'SECONDARY_MIDDLE_NAME' => _secondaryMiddleName,
         'SECONDARY_LAST_NAME' => _secondaryLastName,
         'SECONDARY_WORK_PHONE' => _workPhoneSecondaryContactS,
         'SECONDARY_HOME_PHONE' => _homePhoneSecondaryContactS,
         'SECONDARY_CELL_PHONE' => _cellPhoneSecondaryContactS,
         'SECONDARY_EMAIL' => _emailSecondaryContactS,
         'SECONDARY_RELATION' => _relationshipSecondaryContactS,
        );

        $custom = DBGet(DBQuery('SELECT * FROM custom_fields'));
        foreach ($custom as $c) {
            $options['CUSTOM_' . $c['ID']] = $c['TITLE'];
        }
        $class = "odd";
        $i = 0;
        foreach ($arr_data[0] as $key => $value) {

            if ($class == "odd")
                $class = "even";
            else
                $class = "odd";
            $i++;
            if ($value)
                echo "<tr class=" . $class . "><td class='" . $class . " p-t-20'>" . $value . "</td><td><div id='" . preg_replace('/[()\/]/', '', $value) . "' class='text-center p-t-15'></div></td><td class=" . $class . ">" . SelectInput($value, 'stu[' . $value . ']', '', $options, 'N/A', ' onchange=drawmapping(this.value,' . 'k' . $i . ',' . preg_replace('/[()\/]/', '', $value) . ');') . "</td></tr>";
            echo "<input type='hidden' name='student_map_value[]' id=k$i>";
        }

        echo '</table>';
        echo '</div>'; //.table-responsive
        echo '</div>'; //.panel-body
        echo '<input type=hidden name="filename"  value='.$inputFileName.'/>';
        echo '<div class="panel-footer text-center"><input id="mapItStuBtnOne" type="submit" value="Map it" class="btn btn-primary" onClick="return valid_mapping_student('.$i.', this);"  /> &nbsp; <a href="Modules.php?modname=' . $_REQUEST['modname'] . '" class="btn btn-default">Cancel</a></div>';
        echo '</div>'; //.panel

        echo "</form>";
    }
    elseif ($_REQUEST['action'] == 'display') {
        
        echo '<form action="Modules.php?modname='.$_REQUEST['modname'].'&action=process&page_display=STUDENT_INFO" name="STUDENT_INFO_CONFIRM" method="POST">';
        echo '<div class="panel panel-default">';
        
        echo '<div class="panel-heading">';
        echo '<h4 class="text-center">'._pleaseCreateAOneToOneRelationshipBetweenTheFieldsInYourSpreadsheetAndTheFieldsInTheOpenSisDatabaseBySelectingTheAppropriateFieldsFromTheRightColumn.'. '._afterYouAreDoneClickConfirm.'.</h4>';
        echo '</div>'; //.panel-body
        
        echo '<div class="panel-body p-0">';
        echo '<div class="table-responsive">';
        echo '<table class="table table-striped">';
	echo '<thead>';
        echo '<tr class="bg-grey-200"><th style="word-wrap: break-word;">'._theseFieldsAreInYourExcelSpreadSheet.'</th><th width="200">&nbsp;</th><th>'._theseAreAvailableFieldsInOpenSis.'</th></tr>';
	echo '</thead>';
        echo '<tbody>';

        $options = array('FIRST_NAME' =>_firstName,
         'LAST_NAME' => _lastName,
         'MIDDLE_NAME' =>_middleName,
         'NAME_SUFFIX' => _nameSuffix,
         'GENDER' => _gender,
         'ETHNICITY' => _ethnicity,
         'COMMON_NAME' => _commonName,
         'SOCIAL_SECURITY' => _socialSecurity,
         'BIRTHDATE' => _birthDate,
         'LANGUAGE' => _language,
         'ESTIMATED_GRAD_DATE' => _estimatedGraduationDate,
         'ALT_ID' => _alternateId,
         'EMAIL' => _emailStudentS,
         'PHONE' => _contactNoStudentS,
         'IS_DISABLE' => _disabled,
        );
        $options+=array('USERNAME' => _username,
         'PASSWORD' => _password,
        );
        $options+=array('GRADE_ID' => _grade,
         'SECTION_ID' => _section,
         'START_DATE' => _studentEnrollmentDate,
         'END_DATE' => _studentEnrollmentEndDate,
        );

        $options+=array('STREET_ADDRESS_1' => _addressLine_1StudentS,
         'STREET_ADDRESS_2' => _addressLine_2StudentS,
         'CITY' => _cityStudentS,
         'STATE' => _stateStudentS,
         'ZIPCODE' => _zipcodeStudentS,
        );
        $options+=array('PRIMARY_FIRST_NAME' => _primaryFirstName,
         'PRIMARY_MIDDLE_NAME' => _primaryMiddleName,
         'PRIMARY_LAST_NAME' => _primaryLastName,
         'PRIMARY_WORK_PHONE' => _workPhonePrimaryContactS,
         'PRIMARY_HOME_PHONE' => _homePhonePrimaryContactS,
         'PRIMARY_CELL_PHONE' => _cellPhonePrimaryContactS,
         'PRIMARY_EMAIL' => _emailPrimaryContactS,
         'PRIMARY_RELATION' => _relationshipPrimaryContactS,
        );
        $options+=array('SECONDARY_FIRST_NAME' => _secondaryFirstName,
         'SECONDARY_MIDDLE_NAME' => _secondaryMiddleName,
         'SECONDARY_LAST_NAME' => _secondaryLastName,
         'SECONDARY_WORK_PHONE' => _workPhoneSecondaryContactS,
         'SECONDARY_HOME_PHONE' => _homePhoneSecondaryContactS,
         'SECONDARY_CELL_PHONE' => _cellPhoneSecondaryContactS,
         'SECONDARY_EMAIL' => _emailSecondaryContactS,
         'SECONDARY_RELATION' => _relationshipSecondaryContactS,
        );
        $custom = DBGet(DBQuery('SELECT * FROM custom_fields'));
        foreach ($custom as $c) {
            $options['CUSTOM_' . $c['ID']] = $c['TITLE'];
        }
        $class = "odd";

        $i = 0;
        foreach ($_REQUEST['stu'] as $key_stu => $value_stu) {

            if ($class == "odd")
                $class = "even";
            else
                $class = "odd";
            $i++;
            
            echo '<tr class="' . $class . '"><td class="' . $class . ' p-t-20">' . $key_stu . '</td>';
            if ($value_stu) {
                echo "<td class='" . $class . "'><div id='" . preg_replace('/[()\/]/', '', $key_stu) . "' class='text-center p-t-15'><img src=modules/tools/assets/images/arrow_mapping.png /></div></td>";
            } else {
                echo "<td class='" . $class . "'><div id='" . preg_replace('/[()\/]/', '', $key_stu) . "' class='text-center p-t-15'></div></td>";
            }

            echo "<td class=" . $class . "><input type=hidden name=student[$key_stu] value=$value_stu>" . SelectInput($value_stu, 'student[' . $key_stu . '][' . $value_stu . ']', '', $options, 'N/A', ' onchange=drawmapping_full(this.value,' . 'k' . $i . ',' . preg_replace('/[()\/]/', '', $key_stu) . ');');
            echo "<input type='hidden' name='student_map_value[$key_stu]' id=k$i value=$value_stu>";
            echo "</td></tr>";
        }

        echo '</tbody>';
        echo '</table>';
        echo '</div>'; //.table-responsive
        echo '</div>'; //.panel-body
        echo '<div class="panel-footer text-center"><input id="mapItStuBtnTwo" type="submit" value="'._confirm.'" class="btn btn-primary" onClick="return valid_mapping_student('.$i.', this);" /> &nbsp; <a href="Modules.php?modname=' . $_REQUEST['modname'] . '" class="btn btn-default">Cancel</a></div>';
        echo '</div>'; //.panel
        echo '</form>';
        
    } elseif ($_REQUEST['action'] == 'process') {

        echo '<div class="row">';
        echo '<div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">';
        echo '<div id="calculating" class="panel panel-default">';
        echo '<div class="panel-body text-center">';
        echo '<h3 class="text-center m-b-0">Importing data in to the database</h3>';
        echo '<h6 class="text-center text-danger m-t-0">'._pleaseDoNotInterruptThisProcess.'.....</h6>';
        echo '<div class="p-t-35 p-b-35"><img src="modules/tools/assets/images/copy-to-database.gif" width="80%" /></div>';
        echo '</div>'; //.panel-body
        echo '</div>'; //.panel
        echo '</div>'; //.col-md-6
        echo '</div>'; //.row

        $_SESSION['student'] = $_POST['student_map_value'];

        echo "<script>ajax_progress('student');</script>";
    }
}
//================================Student info Ends==============================================
//================================Staff info Ends==============================================
elseif (clean_param($_REQUEST['page_display'], PARAM_ALPHAMOD) == 'STAFF_INFO') {
    if ($_REQUEST['action'] != 'insert' && $_REQUEST['action'] != 'display' && $_REQUEST['action'] != 'process') {
        
    echo '<div class="row">';
    echo '<div class="col-md-6 col-md-offset-3">';
    echo '<form enctype="multipart/form-data" action="Modules.php?modname='.$_REQUEST['modname'].'&action=insert&page_display=STAFF_INFO" method="POST" onSubmit="return map_upload_validation();">';
    echo '<div class="panel panel-default">';
    echo '<div class="panel-body text-center">';

    echo '<h5 class="text-center">'._clickOnTheBrowseButtonToNavigateToTheExcelFileInYourComputerSHardDriveThatHasYourDataAndSelectIt.'. <b>'._afterSelectingClickUpload.'.</b></h5>';
    echo '<div class="form-group">';
    echo '<input type="hidden"  name="MAX_FILE_SIZE" value="2000000" />';
    echo '<div class="text-center"><label id="select-file-input"><input type="file" class="upload" id="file_id" name="file" /><i class="icon-upload"></i><br/><span>'._clickHereToSelectAFile.'</span></label></div>';
    echo '<p class="help-block">'._supportedFileTypesXlsXlsx.'</p>';
    echo '</div>';

    echo '</div>'; //.panel-body
    echo '<div class="panel-footer text-center"><input type="submit" class="btn btn-primary" value="'._upload.'" /> &nbsp; <a href="Modules.php?modname=' . $_REQUEST['modname'] . '" class="btn btn-default">'._cancel.'</a></div>';
    echo '</div>'; //.panel
    echo '</form>';
    echo '</div>'; //.col-md-6
    echo '</div>'; //.row
    ?>
    <script>
        $(function () {
            $('#file_id').change(function (e) {
                var fileName = e.target.files[0].name;
                $('#select-file-input span').html('<b>Selected File: </b><br/>' + fileName + '<br/>(click to change)');
            });

        });
    </script>
    <?php 
        
    } elseif ($_REQUEST['action'] == 'insert') {
        $arr_data = array();
        echo '<form action="Modules.php?modname=' . $_REQUEST['modname'] . '&action=display&page_display=STAFF_INFO" name="staff_form"  method="POST">';
        echo '<div class="panel panel-default">';
        echo '<div class="panel-heading">';
        echo '<h4 class="text-center">'._pleaseCreateAOneToOneRelationshipBetweenTheFieldsInYourSpreadsheetAndTheFieldsInTheOpenSisDatabaseBySelectingTheAppropriateFieldsFromTheRightColumn.'. '._afterYouAreDoneClickMapIt.'.</h4>';
        echo '</div>'; //.panel-heading

        echo '<div class="panel-body p-0">';
        echo '<div class="table-responsive">';
        echo '<table class="table table-striped">';
	echo '<thead>';
        echo '<tr class="bg-grey-200"><th width="260">'._theseFieldsAreInYourExcelSpreadSheet.'</td><td width="200">&nbsp;</td><td>'._theseAreAvailableFieldsInOpenSis.'</td></tr>';
	echo '</thead>';
        $inputFileName = $_FILES['file']['tmp_name'];
        $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        //$objReader->setReadDataOnly(true); //ADDED by Africa's Hope - Evan Stewart - the object doesn't exist in the IOFactory.php file.  Disable to see if it breaks anything.
        /**  Load $inputFileName to a PHPExcel Object  * */
        $objPHPExcel = $objReader->load($inputFileName);
        $total_sheets = $objPHPExcel->getSheetCount(); // here 4  
        $allSheetName = $objPHPExcel->getSheetNames(); // array ([0]=>'student',[1]=>'teacher',[2]=>'school',[3]=>'college')  
        $objWorksheet = $objPHPExcel->setActiveSheetIndex(0); // first sheet  
        $highestRow = $objWorksheet->getHighestRow(); // here 5  
        $highestColumn = $objWorksheet->getHighestColumn(); // here 'E'  
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);  // here 5  
        for ($row = 1; $row <= $highestRow; ++$row) {
            for ($col = 0; $col <= $highestColumnIndex; ++$col) {
                $value = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
                if (is_array($arr_data)) {
                    $arr_data[$row - 1][$col] = $value;
                }
            }
        }
        $_SESSION['data'] = $arr_data;

        $options = array('TITLE' => _salutation,
         'FIRST_NAME' =>_firstName,
         'LAST_NAME' => _lastName,
         'MIDDLE_NAME' =>_middleName,
         'EMAIL' => _email,
         'PHONE' => _phone,
         'PROFILE' => _profile,
         'HOMEROOM' => _homeroom,
         'BIRTHDATE' => _birthDate,
         'ETHNICITY_ID' => _ethnicity,
         'ALTERNATE_ID' =>_alternateId,
         'PRIMARY_LANGUAGE_ID' => _primaryLanguage,
         'GENDER' => _gender,
         'SECOND_LANGUAGE_ID' => _secondaryLanguage,
         'THIRD_LANGUAGE_ID' => _thirdLanguage,
         'IS_DISABLE' => _disabled,
        );
        $options+=array('USERNAME' => _username,
         'PASSWORD' => _password,
        );
        $options+=array('START_DATE' => _startDate,
         'END_DATE' => _endDate,
        );
        $options+=array('CATEGORY' => _category,
         'JOB_TITLE' =>_jobTitle,
         'JOINING_DATE' => _joiningDate,
        );
        $custom = DBGet(DBQuery('SELECT * FROM staff_fields'));
        foreach ($custom as $c) {
            $options['CUSTOM_' . $c['ID']] = $c['TITLE'];
        }

        $class = "odd";
        //  print_r($arr_data);
        $i = 0;
        foreach ($arr_data[0] as $key => $value) {
            if ($class == "odd")
                $class = "even";
            else
                $class = "odd";
            $i++;
            if ($value)
                echo "<tr class=" . $class . "><td class=" . $class . ">" . $value . "</td><td><div id='" . preg_replace('/[()\/]/', '', $value) . "'></div></td><td class=" . $class . ">" . SelectInput($valuee, 'staff[' . $value . ']', '', $options, 'N/A', ' onchange=drawmapping(this.value,' . 'k' . $i . ',' . preg_replace('/[()\/]/', '', $value) . ');') . "</td></tr>";
            echo "<input type='hidden' name='student_map_value[]' id=k$i>";
        }
        
        echo '</table>';
        echo '</div>'; //.table-responsive
        echo '</div>'; //.panel-body
        echo '<input type=hidden name="filename"  value='.$inputFileName.'/>';
        echo '<div class="panel-footer text-center"><input id="mapItStaBtnOne" type="submit" value="'._mapIt.'" class="btn btn-primary" onClick="return valid_mapping_staff('.$i.', this);"  /> &nbsp; <a href="Modules.php?modname=' . $_REQUEST['modname'] . '" class="btn btn-default">'._cancel.'</a></div>';
        echo "</form>";
    }
    elseif ($_REQUEST['action'] == 'display') {
        $staff_keys = array_keys($_REQUEST['staff']);
        $staff_keys_string = implode(',', $staff_keys);
        $staff_values = implode(',', $_REQUEST['staff']);
        echo "<script>ajax_mapping('" . $staff_keys_string . "','" . $staff_values . "','staff');</script>";
        echo '<form action="Modules.php?modname='.$_REQUEST['modname'].'&action=process&page_display=STAFF_INFO" name="STAFF_INFO_CONFIRM" method="POST">';
        echo '<div class="panel panel-default">';
        
        echo '<div class="panel-heading">';
        echo '<h4 class="text-center">'._pleaseCreateAOnetooneRelationshipBetweenTheFieldsInYourSpreadSheetAndTheFieldsInTheOpenSISDatabaseBySelectingTheAppropriateFieldsFromtheRightColumnAfterYouAreDoneClickConfirm.'.</h4>';
        echo '</div>'; //.panel-body
        
        echo '<div class="panel-body p-0">';
        echo '<div class="table-responsive">';
        echo '<table class="table table-striped">';
	echo '<thead>';
        echo '<tr class="bg-grey-200"><th width="260">'._theseFieldsAreInYourExcelSpreadSheet.'</th><th width="200">&nbsp;</th><th>'._theseAreAvailableFieldsInOpenSis.' (Click to change the field values)</th></tr>';
	echo '</thead>';
        echo '<tbody>';
        

        $options = array('TITLE' => _salutation,
         'FIRST_NAME' =>_firstName,
         'LAST_NAME' => _lastName,
         'MIDDLE_NAME' =>_middleName,
         'EMAIL' => _email,
         'PHONE' => _phone,
         'PROFILE' => _profile,
         'HOMEROOM' => _homeroom,
         'BIRTHDATE' => _birthDate,
         'ETHNICITY_ID' => _ethnicity,
         'ALTERNATE_ID' =>_alternateId,
         'PRIMARY_LANGUAGE_ID' => _primaryLanguage,
         'GENDER' => _gender,
         'SECOND_LANGUAGE_ID' => _secondaryLanguage,
         'THIRD_LANGUAGE_ID' => _thirdLanguage,
         'IS_DISABLE' => _disabled,
        );
        $options+=array('USERNAME' => _username,
         'PASSWORD' => _password,
        );
        $options+=array('START_DATE' => _startDate,
         'END_DATE' => _endDate,
        );
        $options+=array('CATEGORY' => _category,
         'JOB_TITLE' =>_jobTitle,
         'JOINING_DATE' => _joiningDate,
        );
        $class = "odd";
        $custom = DBGet(DBQuery('SELECT * FROM staff_fields'));
        foreach ($custom as $c) {
            $options['CUSTOM_' . $c['ID']] = $c['TITLE'];
        }

        $i = 0;
        foreach ($_REQUEST['staff'] as $key_stu => $value_stu) {
            if ($class == "odd")
                $class = "even";
            else
                $class = "odd";
            echo "<tr class=" . $class . "><td class=" . $class . ">" . $key_stu . "</td>";
            if ($value_stu) {
                echo "<td><div id='" . preg_replace('/[()\/]/', '', $key_stu) . "'><img src=modules/tools/assets/images/arrow_mapping.png /></div></td>  ";
            } else {
                echo "<td><div id='" . preg_replace('/[()\/]/', '', $key_stu) . "'></div></td>  ";
            }
            $i++;
            echo "<td class=" . $class . "><input type=hidden name=staff[$key_stu] value=$value_stu>" . SelectInput($value_stu, 'staff[' . $key_stu . '][' . $value_stu . ']', '', $options, 'N/A', 'onchange=drawmapping_full(this.value,' . 'k' . $i . ',' . preg_replace('/[()\/]/', '', $key_stu) . ');') . "</td></tr>";
            echo "<input type='hidden' name='staff_map_value[$key_stu]' id=k$i value=$value_stu>";
        }
        
        
        echo '</tbody>';
        echo '</table>';
        echo '</div>'; //.table-responsive
        echo '</div>'; //.panel-body
        echo '<div class="panel-footer text-center"><input id="mapItStaBtnTwo" type="submit" value="'._confirm.'" class="btn btn-primary" onClick="return valid_mapping_staff('.$i.', this);" /> &nbsp; <a href="Modules.php?modname=' . $_REQUEST['modname'] . '" class="btn btn-default">Cancel</a></div>';
        echo '</div>'; //.panel
        echo '</form>';
        
    } elseif ($_REQUEST['action'] == 'process') {
        
        
        
        echo '<div class="row">';
        echo '<div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">';
        echo '<div id="calculating" class="panel panel-default">';
        echo '<div class="panel-body text-center">';
        echo '<h3 class="text-center m-b-0">'._importingDataInToTheDatabase.'</h3>';
        echo '<h6 class="text-center text-danger m-t-0">'._pleaseDoNotInterruptThisProcess.'.....</h6>';
        echo '<div class="p-t-35 p-b-35"><img src="modules/tools/assets/images/copy-to-database.gif" width="80%" /></div>';
        echo '</div>'; //.panel-body
        echo '</div>'; //.panel
        echo '</div>'; //.col-md-6
        echo '</div>'; //.row

        $_SESSION['staff'] = $_POST['staff_map_value'];

        echo "<script>ajax_progress('staff');</script>";
    }
}
//================================Staff info Ends==============================================
//ADDED by Africa's Hope - Evan Stewart
//================================Course info Begins==============================================
elseif (clean_param($_REQUEST['page_display'], PARAM_ALPHAMOD) == 'COURSE_INFO') {
    if ($_REQUEST['action'] != 'insert' && $_REQUEST['action'] != 'display' && $_REQUEST['action'] != 'process') {
        
    echo '<div class="row">';
    echo '<div class="col-md-6 col-md-offset-3">';
    echo '<form enctype="multipart/form-data" action="Modules.php?modname='.$_REQUEST['modname'].'&action=insert&page_display=COURSE_INFO" method="POST" onSubmit="return map_upload_validation();">';
    echo '<div class="panel panel-default">';
    echo '<div class="panel-body text-center">';

    echo '<h5 class="text-center">'._clickOnTheBrowseButtonToNavigateToTheExcelFileInYourComputerSHardDriveThatHasYourDataAndSelectIt.'. <b>'._afterSelectingClickUpload.'.</b></h5>';
    echo '<div class="form-group">';
    echo '<input type="hidden"  name="MAX_FILE_SIZE" value="2000000" />';
    echo '<div class="text-center"><label id="select-file-input"><input type="file" class="upload" id="file_id" name="file" /><i class="icon-upload"></i><br/><span>'._clickHereToSelectAFile.'</span></label></div>';
    echo '<p class="help-block">'._supportedFileTypesXlsXlsx.'</p>';
    echo '</div>';

    echo '</div>'; //.panel-body
    echo '<div class="panel-footer text-center"><input type="submit" class="btn btn-primary" value="'._upload.'" /> &nbsp; <a href="Modules.php?modname=' . $_REQUEST['modname'] . '" class="btn btn-default">'._cancel.'</a></div>';
    echo '</div>'; //.panel
    echo '</form>';
    echo '</div>'; //.col-md-6
    echo '</div>'; //.row
    ?>
    <script>
        $(function () {
            $('#file_id').change(function (e) {
                var fileName = e.target.files[0].name;
                $('#select-file-input span').html('<b>Selected File: </b><br/>' + fileName + '<br/>(click to change)');
            });

        });
    </script>
    <?php 
        
    } elseif ($_REQUEST['action'] == 'insert') {
        $arr_data = array();
        echo '<form action="Modules.php?modname=' . $_REQUEST['modname'] . '&action=display&page_display=COURSE_INFO" name="course_form"  method="POST">';
        echo '<div class="panel panel-default">';
        echo '<div class="panel-heading">';
        echo '<h4 class="text-center">'._pleaseCreateAOneToOneRelationshipBetweenTheFieldsInYourSpreadsheetAndTheFieldsInTheOpenSisDatabaseBySelectingTheAppropriateFieldsFromTheRightColumn.'. '._afterYouAreDoneClickMapIt.'.</h4>';
        echo '</div>'; //.panel-heading

        echo '<div class="panel-body p-0">';
        echo '<div class="table-responsive">';
        echo '<table class="table table-striped">';
	echo '<thead>';
        echo '<tr class="bg-grey-200"><th width="260">'._theseFieldsAreInYourExcelSpreadSheet.'</td><td width="200">&nbsp;</td><td>'._theseAreAvailableFieldsInOpenSis.'</td></tr>';
	echo '</thead>';
        $inputFileName = $_FILES['file']['tmp_name'];
        $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        //$objReader->setReadDataOnly(true); //ADDED by Africa's Hope - Evan Stewart - the object doesn't exist in the IOFactory.php file.  Disable to see if it breaks anything.
        /**  Load $inputFileName to a PHPExcel Object  * */
        $objPHPExcel = $objReader->load($inputFileName);
        $total_sheets = $objPHPExcel->getSheetCount(); // here 4  
        $allSheetName = $objPHPExcel->getSheetNames(); // array ([0]=>'student',[1]=>'teacher',[2]=>'school',[3]=>'college')  
        $objWorksheet = $objPHPExcel->setActiveSheetIndex(0); // first sheet  
        $highestRow = $objWorksheet->getHighestRow(); // here 5  
        $highestColumn = $objWorksheet->getHighestColumn(); // here 'E'  
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);  // here 5  
        for ($row = 1; $row <= $highestRow; ++$row) {
            for ($col = 0; $col <= $highestColumnIndex; ++$col) {
                $value = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
                if (is_array($arr_data)) {
                    $arr_data[$row - 1][$col] = $value;
                }
            }
        }
        $_SESSION['data'] = $arr_data;

        $options = array('SUBJECT_TITLE' => _subject,
         'COURSE_SHORT_NAME' =>_courseShortName,
         'COURSE_TITLE' => _importCourseName,
        );

        $class = "odd";
        //  print_r($arr_data);
        $i = 0;
        foreach ($arr_data[0] as $key => $value) {
            if ($class == "odd")
                $class = "even";
            else
                $class = "odd";
            $i++;
            if ($value)
                echo "<tr class=" . $class . "><td class=" . $class . ">" . $value . "</td><td><div id='" . preg_replace('/[()\/]/', '', $value) . "'></div></td><td class=" . $class . ">" . SelectInput($value, 'course[' . $value . ']', '', $options, 'N/A', ' onchange=drawmapping(this.value,' . 'k' . $i . ',' . preg_replace('/[()\/]/', '', $value) . ');') . "</td></tr>";
            echo "<input type='hidden' name='course_map_value[]' id=k$i>";
        }
        
        echo '</table>';
        echo '</div>'; //.table-responsive
        echo '</div>'; //.panel-body
        echo '<input type=hidden name="filename"  value='.$inputFileName.'/>';
        echo '<div class="panel-footer text-center"><input id="mapItStaBtnOne" type="submit" value="'._mapIt.'" class="btn btn-primary" onClick="return valid_mapping_course('.$i.', this);"  /> &nbsp; <a href="Modules.php?modname=' . $_REQUEST['modname'] . '" class="btn btn-default">'._cancel.'</a></div>';
        echo "</form>";
    }
    elseif ($_REQUEST['action'] == 'display') {
        $course_keys = array_keys($_REQUEST['course']);
        $course_keys_string = implode(',', $course_keys);
        $course_values = implode(',', $_REQUEST['course']);
        echo "<script>ajax_mapping('" . $course_keys_string . "','" . $course_values . "','courses');</script>";
        echo '<form action="Modules.php?modname='.$_REQUEST['modname'].'&action=process&page_display=COURSE_INFO" name="COURSE_INFO_CONFIRM" method="POST">';
        echo '<div class="panel panel-default">';
        
        echo '<div class="panel-heading">';
        echo '<h4 class="text-center">'._pleaseCreateAOnetooneRelationshipBetweenTheFieldsInYourSpreadSheetAndTheFieldsInTheOpenSISDatabaseBySelectingTheAppropriateFieldsFromtheRightColumnAfterYouAreDoneClickConfirm.'.</h4>';
        echo '</div>'; //.panel-body
        
        echo '<div class="panel-body p-0">';
        echo '<div class="table-responsive">';
        echo '<table class="table table-striped">';
	echo '<thead>';
        echo '<tr class="bg-grey-200"><th width="260">'._theseFieldsAreInYourExcelSpreadSheet.'</th><th width="200">&nbsp;</th><th>'._theseAreAvailableFieldsInOpenSis.' (Click to change the field values)</th></tr>';
	echo '</thead>';
        echo '<tbody>';
        

        $options = array('SUBJECT_TITLE' => _subject,
        'COURSE_SHORT_NAME' =>_courseShortName,
        'COURSE_TITLE' => _importCourseName,
        );

        $class = "odd";
        

        $i = 0;
        foreach ($_REQUEST['course'] as $key_course => $value_course) {
            if ($class == "odd")
                $class = "even";
            else
                $class = "odd";
            echo "<tr class=" . $class . "><td class=" . $class . ">" . $key_course . "</td>";
            if ($value_course) {
                echo "<td><div id='" . preg_replace('/[()\/]/', '', $key_course) . "'><img src=modules/tools/assets/images/arrow_mapping.png /></div></td>  ";
            } else {
                echo "<td><div id='" . preg_replace('/[()\/]/', '', $key_course) . "'></div></td>  ";
            }
            $i++;
            echo "<td class=" . $class . "><input type=hidden name=course[$key_course] value=$value_course>" . SelectInput($value_course, 'course[' . $key_course . '][' . $value_course . ']', '', $options, 'N/A', 'onchange=drawmapping_full(this.value,' . 'k' . $i . ',' . preg_replace('/[()\/]/', '', $key_course) . ');') . "</td></tr>";
            echo "<input type='hidden' name='course_map_value[$key_course]' id=k$i value=$value_course>";
        }
        
        
        echo '</tbody>';
        echo '</table>';
        echo '</div>'; //.table-responsive
        echo '</div>'; //.panel-body
        echo '<div class="panel-footer text-center"><input id="mapItStaBtnTwo" type="submit" value="'._confirm.'" class="btn btn-primary" onClick="return valid_mapping_course('.$i.', this);" /> &nbsp; <a href="Modules.php?modname=' . $_REQUEST['modname'] . '" class="btn btn-default">Cancel</a></div>';
        echo '</div>'; //.panel
        echo '</form>';
        
    } elseif ($_REQUEST['action'] == 'process') {
        
       
        
         echo '<div class="row">';
        echo '<div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">';
        echo '<div id="calculating" class="panel panel-default">';
        echo '<div class="panel-body text-center">';
        echo '<h3 class="text-center m-b-0">'._importingDataInToTheDatabase.'</h3>';
        echo '<h6 class="text-center text-danger m-t-0">'._pleaseDoNotInterruptThisProcess.'.....</h6>';
        echo '<div class="p-t-35 p-b-35"><img src="modules/tools/assets/images/copy-to-database.gif" width="80%" /></div>';
        echo '</div>'; //.panel-body
        echo '</div>'; //.panel
        echo '</div>'; //.col-md-6
        echo '</div>'; //.row

        $_SESSION['course'] = $_POST['course_map_value'];

        echo "<script>ajax_progress('course');</script>";
    }
}

//================================Course info Ends==============================================

//================================Import Grades / Trousers Grade info Begins==============================================
elseif (clean_param($_REQUEST['page_display'], PARAM_ALPHAMOD) == 'TROUSERS_GRADE_INFO') {

    //options for the one-to-one relationship fields. Used to match upload file columns to columns in the database
    //the index key will be used in import process in DataImportCounter.php; reference the value using this key.  The index value will be displayed on the GUI for the user benefit
    $options = array('SCHOOL_NAME'=>_schoolName,
        'TROUSERS_TERM_ID'=>_trousersTermId,
        'TROUSERS_TERM_NAME'=>_trousersTermName,
        'ALT_ID' => _trousersStudentId,
        'LAST_NAME'=>_lastName,
        'MIDDLE_NAME'=>_middleName,
        'FIRST_NAME' => _firstName, 
        'NAME_SUFFIX'=>_nameSuffix,
        'COURSE_CODE'=>_courseShortName,
        'COURSE_TITLE'=>_importCourseName,
        'GRADE_PERCENT'=>_gradePercent,
        'CREDIT_EARNED'=>_creditEarned,
        'CREDIT_ATTEMPTED'=>_creditAttempted
    );

    if ($_REQUEST['action'] != 'insert' && $_REQUEST['action'] != 'display' && $_REQUEST['action'] != 'process') {
        echo '<div class="row">';
        echo '<div class="col-md-6 col-md-offset-3">';
        echo '<form enctype="multipart/form-data" action="Modules.php?modname='.$_REQUEST['modname'].'&action=insert&page_display=TROUSERS_GRADE_INFO" method="POST" onSubmit="return map_upload_validation();">';
        echo '<div class="panel panel-default">';
        echo '<div class="panel-body text-center">';

        echo '<h5 class="text-center">'._clickOnTheBrowseButtonToNavigateToTheExcelFileInYourComputerSHardDriveThatHasYourDataAndSelectIt.'. <b>'._afterSelectingClickUpload.'.</b></h5>';
        echo '<div class="form-group">';
        echo '<input type="hidden" name="MAX_FILE_SIZE" value="5000000" />'; //Unit of Measure = Bytes. this value cannot exceed the upload_max_filesize parameter defined php.ini.  
        echo '<div class="text-center"><label id="select-file-input"><input type="file" class="upload" id="file_id" name="file" /><i class="icon-upload"></i><br/><span>'._clickHereToSelectAFile.'</span></label></div>';
        echo '<p class="help-block">'._supportedFileTypesXlsXlsx.'</p>';
        echo '</div>';

        echo '</div>'; //.panel-body
        echo '<div class="panel-footer text-center"><input type="submit" class="btn btn-primary" value="'._upload.'" /> &nbsp; <a href="Modules.php?modname=' . $_REQUEST['modname'] . '" class="btn btn-default">'._cancel.'</a></div>';
        echo '</div>'; //.panel
        echo '</form>';
        echo '</div>'; //.col-md-6
        echo '</div>'; //.row
        ?>
        <script>
            $(function () {
                $('#file_id').change(function (e) {
                    var fileName = e.target.files[0].name;
                    $('#select-file-input span').html('<b>Selected File: </b><br/>' + fileName + '<br/>(click to change)');
                });
            });
        </script>
        <?php 
        
    } elseif ($_REQUEST['action'] == 'insert') {
        $arr_data = array();
        echo '<form action="Modules.php?modname=' . $_REQUEST['modname'] . '&action=display&page_display=TROUSERS_GRADE_INFO" name="grades_form"  method="POST">';
        echo '<div class="panel panel-default">';
        echo '<div class="panel-heading">';
        echo '<h4 class="text-center">'._pleaseCreateAOneToOneRelationshipBetweenTheFieldsInYourSpreadsheetAndTheFieldsInTheOpenSisDatabaseBySelectingTheAppropriateFieldsFromTheRightColumn.'. '._afterYouAreDoneClickMapIt.'.</h4>';
        echo '</div>'; //.panel-heading

        echo '<div class="panel-body p-0">';
        echo '<div class="table-responsive">';
        echo '<table class="table table-striped">';
	    echo '<thead>';
        echo '<tr class="bg-grey-200"><th width="260">'._theseFieldsAreInYourExcelSpreadSheet.'</td><td width="200">&nbsp;</td><td>'._theseAreAvailableFieldsInOpenSis.'</td></tr>';
	    echo '</thead>';
        //Check upload file size
        ### If $_FILES['file']['error'] == 1, then the file size is larger than upload_max_filesize parameter defined in php.ini.  
        ### If $_FILES['file']['error'] == 2, then the file size is larger than the MAX_FILE_SIZE directive defined in the HTML form (see above)
        if(isset($_FILES['file']['error']) AND ($_FILES['file']['error'] == 2 OR $_FILES['file']['error'] == 1)){
            if($_FILES['file']['error'] == 1){
                $maxFileSize = ini_get('upload_max_filesize');
            } else {
                $maxFileSize = round($_REQUEST['MAX_FILE_SIZE'] / 1048576, 0) . "MB";
            }
            echo "</table><p style='color:red;'>" . _uploadFileSizeLargerThanMaxAllowed . "$maxFileSize.</p>";
        }

        $inputFileName = $_FILES['file']['tmp_name'];
        $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        //$objReader->setReadDataOnly(true); //ADDED by Africa's Hope - Evan Stewart - the object doesn't exist in the IOFactory.php file.  Disable to see if it breaks anything.
        /**  Load $inputFileName to a PHPExcel Object  * */
        $objPHPExcel = $objReader->load($inputFileName);
        $total_sheets = $objPHPExcel->getSheetCount(); // here 4  
        $allSheetName = $objPHPExcel->getSheetNames(); // array ([0]=>'student',[1]=>'teacher',[2]=>'school',[3]=>'college')  
        $objWorksheet = $objPHPExcel->setActiveSheetIndex(0); // first sheet  
        $highestRow = $objWorksheet->getHighestRow(); // here 5  
        $highestColumn = $objWorksheet->getHighestColumn(); // here 'E'  
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);  // here 5  
        for ($row = 1; $row <= $highestRow; ++$row) {
            for ($col = 0; $col <= $highestColumnIndex; ++$col) {
                $value = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
                if (is_array($arr_data)) {
                    $arr_data[$row - 1][$col] = $value;
                }
            }
        }
        $_SESSION['data'] = $arr_data;

         //options for insert logic were here

        $class = "odd";
        $i = 0;
        foreach ($arr_data[0] as $key => $value) {
            if ($class == "odd")
                $class = "even";
            else
                $class = "odd";
            $i++;
            if ($value)
                echo "<tr class=" . $class . "><td class=" . $class . ">" . $value . "</td><td><div id='" . preg_replace('/[()\/]/', '', $value) . "'></div></td><td class=" . $class . ">" . SelectInput($value, 'grades[' . $value . ']', '', $options, 'N/A', ' onchange=drawmapping(this.value,' . 'k' . $i . ',' . preg_replace('/[()\/]/', '', $value) . ');') . "</td></tr>";
            echo "<input type='hidden' name='grades_map_value[]' id=k$i>";
        }
        
        echo '</table>';
        echo '</div>'; //.table-responsive
        echo '</div>'; //.panel-body
        echo '<input type=hidden name="filename"  value='.$inputFileName.'/>';
        echo '<div class="panel-footer text-center"><input id="mapItStaBtnOne" type="submit" value="'._mapIt.'" class="btn btn-primary" onClick="return valid_mapping_grades('.$i.', this);"  /> &nbsp; <a href="Modules.php?modname=' . $_REQUEST['modname'] . '" class="btn btn-default">'._cancel.'</a></div>';
        echo "</form>";
    } elseif ($_REQUEST['action'] == 'display') {
        $grade_keys = array_keys($_REQUEST['grades']);
        $grades_keys_string = implode(',', $grades_keys);
        $grades_values = implode(',', $_REQUEST['grades']);
        echo "<script>ajax_mapping('" . $grades_keys_string . "','" . $grades_values . "','grades');</script>";
        echo '<form action="Modules.php?modname='.$_REQUEST['modname'].'&action=process&page_display=TROUSERS_GRADE_INFO" name="TROUSERS_GRADE_INFO_CONFIRM" method="POST">';
        echo '<div class="panel panel-default">';
        
        echo '<div class="panel-heading">';
        echo '<h4 class="text-center">'._pleaseCreateAOnetooneRelationshipBetweenTheFieldsInYourSpreadSheetAndTheFieldsInTheOpenSISDatabaseBySelectingTheAppropriateFieldsFromtheRightColumnAfterYouAreDoneClickConfirm.'.</h4>';
        echo '</div>'; //.panel-body
        
        echo '<div class="panel-body p-0">';
        echo '<div class="table-responsive">';
        echo '<table class="table table-striped">';
	    echo '<thead>';
        echo '<tr class="bg-grey-200"><th width="260">'._theseFieldsAreInYourExcelSpreadSheet.'</th><th width="200">&nbsp;</th><th>'._theseAreAvailableFieldsInOpenSis.' (Click to change the field values)</th></tr>';
	    echo '</thead>';
        echo '<tbody>';
        
        //options for display logic were here

        $class = "odd";
        
        $i = 0;
        foreach ($_REQUEST['grades'] as $key_grades => $value_grades) {
            if ($class == "odd")
                $class = "even";
            else
                $class = "odd";
            echo "<tr class=" . $class . "><td class=" . $class . ">" . $key_grades . "</td>";
            if ($value_grades) {
                echo "<td><div id='" . preg_replace('/[()\/]/', '', $key_grades) . "'><img src=modules/tools/assets/images/arrow_mapping.png /></div></td>  ";
            } else {
                echo "<td><div id='" . preg_replace('/[()\/]/', '', $key_grades) . "'></div></td>  ";
            }
            $i++;
            echo "<td class=" . $class . "><input type=hidden name=grades[$key_grades] value=$value_grades>" . SelectInput($value_grades, 'grades[' . $key_grades . '][' . $value_grades . ']', '', $options, 'N/A', 'onchange=drawmapping_full(this.value,' . 'k' . $i . ',' . preg_replace('/[()\/]/', '', $key_grades) . ');') . "</td></tr>";
            echo "<input type='hidden' name='grades_map_value[$key_grades]' id=k$i value=$value_grades>";
        }
        
        echo '</tbody>';
        echo '</table>';
        echo '</div>'; //.table-responsive
        echo '</div>'; //.panel-body
        echo '<div class="panel-footer text-center"><input id="mapItStaBtnTwo" type="submit" value="'._confirm.'" class="btn btn-primary" onClick="return valid_mapping_grades('.$i.', this);" /> &nbsp; <a href="Modules.php?modname=' . $_REQUEST['modname'] . '" class="btn btn-default">Cancel</a></div>';
        echo '</div>'; //.panel
        echo '</form>';
        
    } elseif ($_REQUEST['action'] == 'process') {
        
        echo '<div class="row">';
        echo '<div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">';
        echo '<div id="calculating" class="panel panel-default">';
        echo '<div class="panel-body text-center">';
        echo '<h3 class="text-center m-b-0">'._importingDataInToTheDatabase.'</h3>';
        echo '<h6 class="text-center text-danger m-t-0">'._pleaseDoNotInterruptThisProcess.'.....</h6>';
        echo '<div class="p-t-35 p-b-35"><img src="modules/tools/assets/images/copy-to-database.gif" width="80%" /></div>';
        echo '</div>'; //.panel-body
        echo '</div>'; //.panel
        echo '</div>'; //.col-md-6
        echo '</div>'; //.row

        $_SESSION['trousers_grades'] = $_POST['grades_map_value'];

        echo "<script>ajax_progress('trousers_grades');</script>"; //script function defined in /js/DataImport.js
    }
}

//================================Trouser Grade info Ends==============================================
//END Africa's Hope Addition
else {
  //Selection buttons on Main Import page
    echo '<h1 class="text-center m-b-0">'._dataImportUtility.'</h1>';
    echo '<p class="text-center text-grey m-b-30">'._pleaseSelectAProfileToImportTheirRelevantData.'.</p>';

    echo '<div class="row">';
    echo '<div class="col-md-3 col-md-offset-3">';
    echo '<div class="panel panel-default">';
    echo '<div class="panel-body text-center p-t-35">';
    echo '<a href=Modules.php?modname=' . $_REQUEST['modname'] . '&page_display=STUDENT_INFO><img src="modules/tools/assets/images/student.svg" width="60%" /><h4>'._importStudentData.'</h4></a>';
    echo '</div>'; //.panel-body
    echo '</div>'; //.panel
    echo '</div>'; //.col-md-3
    echo '<div class="col-md-3">';
    echo '<div class="panel panel-default">';
    echo '<div class="panel-body text-center p-t-35">';
    echo '<a href=Modules.php?modname=' . $_REQUEST['modname'] . '&page_display=STAFF_INFO><img src="modules/tools/assets/images/faculty.svg" width="60%" /><h4>'._importStaffData.'</h4></a>';
    echo '</div>'; //.panel-body
    echo '</div>'; //.panel
    echo '</div>';
    //ADDED by Africa's Hope - Evan Stewart
    //COURSES
    echo '<div class="col-md-3 col-md-offset-3">';
    echo '<div class="panel panel-default">';
    echo '<div class="panel-body text-center p-t-35">';
    echo '<a href=Modules.php?modname=' . $_REQUEST['modname'] . '&page_display=COURSE_INFO><img src="modules/tools/assets/images/courses.svg" width="60%" /><h4>'._importCourseData.'</h4></a>';
    echo '</div>'; //.panel-body
    echo '</div>'; //.panel
    echo '</div>';
    //TROUSERS GRADES
    //Not ready for use.  Hiding for now. 1/27/2022
    /*
    echo '<div class="col-md-3">';
    echo '<div class="panel panel-default">';
    echo '<div class="panel-body text-center p-t-35">';
    echo '<a href=Modules.php?modname=' . $_REQUEST['modname'] . '&page_display=TROUSERS_GRADE_INFO><img src="modules/tools/assets/images/courses.svg" width="60%" /><h4>'._importTrousersGradesData.'</h4></a>';
    echo '</div>'; //.panel-body
    echo '</div>'; //.panel
    echo '</div>';
    */
    //END Africa's Hope Addition
    echo '</div>';
}
?>
