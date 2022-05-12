<?php
#**************************************************************************
#  OASIS is a free student information system for public and non-public 
#  schools from Africa's Hope, based on openSIS by Open Solutions for Education, Inc. web: www.os4ed.com
#
#  OASIS is  web-based, open source, and comes packed with features that 
#  include student demographic info, scheduling, grade book, attendance, 
#  report cards, eligibility, transcripts, parent portal, 
#  student portal and more.   
#
#  Visit the openSIS web site at http://www.opensis.com to learn more about openSIS.
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

include('../../RedirectModulesInc.php');
require_once("Data.php");
$print_form = 1;
$upgradeStatus = 0;
$output_messages = array();
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

ini_set('memory_limit', '9000M');
ini_set('max_execution_time', '50000');
ini_set('max_input_time', '50000');

$host = $DatabaseServer;
$name = $DatabaseName;
$user = $DatabaseUsername;
$pass = $DatabasePassword;
$port = $DatabasePort;


if(strpos(PHP_OS,"Win")){
    $currentPath = explode("\\",ltrim($_SERVER['PHP_SELF'],"\\"));
    $baseDir = $_SERVER['DOCUMENT_ROOT'] . "\\" . $currentPath[0] . "\\";
} else {
    $currentPath = explode("/",ltrim($_SERVER['PHP_SELF'],"/"));
    $baseDir = $_SERVER['DOCUMENT_ROOT'] . "/" . $currentPath[0] . "/";
}


if(clean_param($_REQUEST['page_display'], PARAM_ALPHAMOD) == 'APP_UPGRADE_COMP'){

    if(clean_param($_REQUEST['copy_status'], PARAM_ALPHAMOD) == 'completed'){
    //if($_REQUEST['copy_status'] == 1){
        $upgradeMsg = "<p>" . _upgradeProcessComplete . " " . _oasisNowAtVersion . $get_app_details[1]['VALUE'] . "</p><p><i>"  . _reasonCode . $_REQUEST['reason'] . "</i></p>";
        $upgradeStatus = 1;
    } else {
        $upgradeMsg = "<p>" . _upgradeProcessFailedPleaseTryAgain . " " . _upgradePartialOrIncomplete . "</p><p><i>" . _reasonCode . $_REQUEST['reason'] . "</i></p>";
        $upgradeStatus = 0;
    }
}

///Handle the ZIP file upload
if(clean_param($_REQUEST['page_display'], PARAM_ALPHAMOD) == 'APP_UPGRADE'){
    $print_form = 0;
    //if($_POST['action'] == "insert" || $_GET['action'] == "insert"){
    if($_POST['action'] == "Upgrade"){

        $errors_upload = array();
        $file_name = $_FILES['upgradeFile']['name'];
        $file_size = $_FILES['upgradeFile']['size'];
        $file_tmp = $_FILES['upgradeFile']['tmp_name'];
	    $file_type = $_FILES['upgradeFile']['type'];
	    $file_error = $_FILES['upgradeFile']['error'];
        $file_folder_name = pathinfo($file_name, PATHINFO_FILENAME);

        if(strpos(PHP_OS,"Win")){
            $currentPath = explode("\\",ltrim($_SERVER['PHP_SELF'],"\\"));
        } else {
            $currentPath = explode("/",ltrim($_SERVER['PHP_SELF'],"/"));
        }
   
        $baseDir = $_SERVER['DOCUMENT_ROOT'] . "/" . $currentPath[0] . "/";
        
        $upgradeKey = "appUpgradeKey/newVersion.json";
        $savePath = $baseDir . "modules/tools/uploads/";
        //$tempPath = $savePath . "temp/";
        $tempPath = $baseDir . "oasisupgrade/";
        
        //$AppUpgradeScript = $tempPath . "$file_folder_name/modules/tools/AppUpgradeProcess.php";
        //$AppUpgradeScript_C = $tempPath . "$file_folder_name/modules/tools/AppUpgradeProcess-new.php";
        $authorKeyTemp = $tempPath . "$file_folder_name/$upgradeKey";
        $authorKeyFinal = $baseDir . $upgradeKey;
        $copySrc = $tempPath . "$file_folder_name/*";
        $copyDest = $baseDir;
        $cmd = "cp -r $copySrc $copyDest";
        $cleanUpCmd1 = "rm -r " . $savePath . "*";
        $cleanUpCmd2 = "rm -r " . $baseDir . "appUpgradeKey";
        $cleanUpCmd3 = "rm -r $tempPath";
        if(file_exists($tempPath)){
            exec($cleanUpCmd3);
        }

        if(strpos(PHP_OS,"Win")){
            $AppUpgradeScript = str_replace("/","\\",$AppUpgradeScript);
            $AppUpgradeScript_C = str_replace("/","\\",$AppUpgradeScript_C);
            $baseDir = str_replace("/","\\",$baseDir);
            $savePath = str_replace("/","\\",$savePath);
            $tempPath = str_replace("/","\\",$tempPath);
            $authorKeyTemp = str_replace("/","\\",$authorKeyTemp);
            $authorKeyFinal = str_replace("/","\\",$authorKeyFinal);
            $copySrc = str_replace("/","\\",$copySrc);
            $copyDest = str_replace("/","\\",$copyDest);
            $cmd = "xcopy $copySrc $copyDest /c /v /q /s /e /i /y /z";
            $cleanUpCmd1 = "rmdir /Q /S " . $savePath . "*";
            $cleanUpCmd2 = "rmdir /Q /S " . $baseDir . "appUpgradeKey";
            $cleanUpCmd3 = "rmdir /Q /S " . $tempPath;
        }

        $file_mv = $savePath . $file_name;

        if(!file_exists($savePath)){
            mkdir($savePath);
        }

        if(file_exists($savePath)){
            if(move_uploaded_file($file_tmp,$file_mv)){
                //echo "<p>File moved</p>";
            }
        }

        if(file_exists($file_mv)){
            $ready = 0;
            $zip = new ZipArchive;
	        $res = $zip->open($file_mv);
	        if ($res === TRUE) {
		        if(!file_exists($tempPath)){
			        mkdir($tempPath);
		        }
		        $zip->extractTo($tempPath); //extract everything
                $zip->close();
                
                /*if(file_exists($AppUpgradeScript)){
                    rename($AppUpgradeScript,$AppUpgradeScript_C);
                }*/
                
                if(file_exists($authorKeyTemp)){
                    if(strpos($authorKeyTemp,".xml")){
                        $upgradeFile = simplexml_load_file($authorKeyTemp);
                        if ($upgradeFile === FALSE) {
                            echo "There were errors parsing the XML file.\n";
                            foreach(libxml_get_errors() as $error) {
                                echo $error->message;
                            }
                            exit;
                        }
                        $detailsObj = json_encode($upgradeFile);
                        $upgradeDetails = json_decode($detailsObj, TRUE);
                    }
                    
                    if(strpos($authorKeyTemp,".json")){
                        //If upgrade details file is JSON
                        $detailsFile = file_get_contents($authorKeyTemp);
                        $upgradeDetails = json_decode($detailsFile, TRUE);
                        if($upgradeDetails === FALSE){
                            var_dump(json_last_error());
                            exit;
                        }
                    }
                }

                $newVersion = "{$upgradeDetails['major']}.{$upgradeDetails['minor']}.{$upgradeDetails['patch']}";
                if($upgradeDetails['major'] < 10){
                    $buildMajor = "00" . $upgradeDetails['major'];
                } else {
                    $buildMajor = "0" . $upgradeDetails['major'];
                }
                $newDate = $upgradeDetails['date'];
                $newUpdate = $upgradeDetails['patch'];
                $newBuild = str_replace("-","",$upgradeDetails['date']) . $buildMajor . $upgradeDetails['minor'] . $upgradeDetails['patch'];
                $cDate = date("F j, Y"); //ex. July 29, 2021

                if($upgradeDetails['status'] == "go"){
                    $upIndex = fopen($tempPath . "index.php", "w") or die("<p>Unable to open file!</p>");

                    //$sql = "UPDATE app SET value='$newVersion' WHERE name='version' date='$newDate', build='$newBuild', update='$newUpdate';";
                    $sql = "UPDATE app SET value = CASE
                    WHEN name = 'version' THEN '$newVersion'
                    WHEN name ='date' THEN '$newDate'
                    WHEN name = 'build' THEN '$newBuild'
                    WHEN name = 'update' THEN '$newUpdate'
                    WHEN name = 'last_updated' THEN '$cDate'
                    ELSE value
                    END;";
                    
                    if(file_exists($tempPath . "index.php") AND is_writable($tempPath . "index.php")){

                        $instruct = "
                        <?php

                        \$dbconn = new mysqli($host, $user, $pass, $name, $port);

                        if (\$dbconn->connect_error) {
                            \$copyStatus = 'failed';
                            \$reasonCode = 'DBconnFail';
                            header('Location:../Modules.php?modname=tools/AppUpgrade.php&page_display=APP_UPGRADE_COMP&copy_status=' . \$copyStatus .'&reason=' . \$reasonCode .'');
                        } else {
                            exec('$cmd');
                        
                            if(file_exists('$authorKeyFinal')){
                                //the appUpgradeKey directory was copied to the base directory
                                \$copyStatus = 'completed';
                                \$reasonCode = 'CopySuccess';

                                \$sql = \"$sql\";
                                if (\$dbconn->query(\$sql) === TRUE) {
                                    \$reasonCode .= 'DBupSuccess';
                                } else {
                                    \$reasonCode .= 'DBupFail';
                                }

                                //Cleanup upgrade files
                                //Delete contents of modules/tools/uploads directory
                                if(file_exists('$savePath')){
                                    exec('$cleanUpCmd1');
                                }
                    
                                //Delete appUpgradeKey directory at the root of OASIS directory
                            
                                //if(file_exists('$baseDir' . 'appUpgradeKey')){
                                    exec('$cleanUpCmd2');
                                //}
                    
                                //Delete appUpgradeKey directory at the root of OASIS directory
                                //if(file_exists('$tempPath')){
                                    exec('$cleanUpCmd3');
                                //}
                            
                            } else {
                                \$copyStatus = 'failed';
                                \$reasonCode = 'Copyfail';
                            }
                        }

                        \$dbconn->close();

                        //Go back to modules/tools/AppUpgrade.php module
                        header('Location:../Modules.php?modname=tools/AppUpgrade.php&page_display=APP_UPGRADE_COMP&copy_status=' . \$copyStatus .'&reason=' . \$reasonCode .'');

                        ?>";

                        fwrite($upIndex, $instruct);
                        fclose($upIndex);
                        chmod($tempPath . "index.php", 0774);

                        $ready = 1;

                    } else {
                        echo "<p>The files $upIndex is not writable";
                    }

                } else {
                    $failStatusCheck = "<p>Failed the status check with status of <b>{$upgradeDetails['status']}</b>.";
                }

                if($ready == 1){
                    echo "<div class='row'>
                    <div class='col-md-6 col-md-offset-3'>";

                    if($upgradeStatus > 0){
                        PopTable('header', _upgrade);
                        echo $upgradeMsg;
                    } else {
                        
                        //echo "<form id='dataForm' name='dataForm' method='POST' enctype='multipart/form-data' action='Modules.php?modname=tools/AppUpgradeProcess.php&page_display=APP_UPGRADE'>";
                        echo "<form id='dataForm' name='dataForm' method='POST' enctype='multipart/form-data' action='oasisupgrade/index.php'>";
            
                        PopTable('header', _upgrade);
                        echo "<h4 class='text-danger'>"._note.":</h4><p>The Upgrade is ready to begin.</p>";
                        echo "<table class='upgradeCompare' style='width:95%'>
                        <tr><th>&nbsp;</th><th>Current version</th><th>Upgrade version</th></tr>
                        <tr><td><b>version:</b></td><td>{$get_app_details[1]['VALUE']}</td><td>$newVersion</td></tr>
                        <tr><td><b>build:</b></td><td>{$get_app_details[3]['VALUE']}</td><td>$newBuild</td></tr>
                        </table>";

                        $btn = '<input type="submit" name="action"  value="'._finishUpgrade.'" class="btn btn-primary"> &nbsp; ';
                        $btn .= '<a href=javascript:void(0); onClick="check_content(\'Ajax.php?modname=miscellaneous/Portal.php\');" STYLE="TEXT-DECORATION: NONE"> <INPUT type=button class="btn btn-default" name=Cancel value="'._cancel.'"></a>';
                
                        PopTable('footer', $btn);
        
                        echo "</form>";
                    }
    
                    echo "</div>
                    </div>";

                } else { //if the unzip process fails
                    echo "<div class='row'>
                    <div class='col-md-6 col-md-offset-3'>";

                    echo "<form id='dataForm' name='dataForm' method='POST' enctype='multipart/form-data' action='Modules.php?modname=tools/AppUpgrade.php'>";
            
                    PopTable('header', _upgrade);
                    echo "<h4 class='text-danger'>"._note.":</h4><p>" . _upgradeProcessFailedPleaseTryAgain . "</p>
                    <p>$failStatusCheck</p>";
                    //echo "<p>The key file is: $authorKeyTemp</p>";
                    

                    $btn = '<input type="submit" name="action"  value="'._tryAgain.'" class="btn btn-primary"> &nbsp; ';
                    //$modname = 'tools/AppUpgrade.php';
                    //$btn .= '<a href=javascript:void(0); onClick="check_content(\'Ajax.php?modname=miscellaneous/Portal.php\');" STYLE="TEXT-DECORATION: NONE"> <INPUT type=button class="btn btn-default" name=Cancel value="'._cancel.'"></a>';
                
                    PopTable('footer', $btn);
        
                    echo "</form>";
                    echo "</div>
                    </div>";
                }

                

                /*

                if($upgradeDetails['status'] == 'go'){
                    echo "<p>The upgrade Status is <b>{$upgradeDetails['status']}</b>. Will attempt to run command:<br>$cmd</br>";
                    exec($cmd);
                } else {
                    echo "<p>Failed the status check with status of <b>{$upgradeDetails['status']}</b>.";
                }
                
                if(file_exists($authorKeyFinal)){
                    $keyDetails = file_get_contents($authorKeyFinal);
                    $upgradeMsg = "<p>Upload Process completed.</p>";
                    $upgradeStatus = 1;
                    //echo "$upgradeMsg.  Is it in the form too?";
                } else {
                    $upgradeMsg = "<p>Upload Failed.  This doesn't look like an authorized file</p>";
                    $upgradeStatus = 1;
                    //echo "$upgradeMsg.  Is it in the form too?";
                }
		        $zip->close();

                clearstatcache();
                */
            }

            clearstatcache();
        }

        //Cleanup upgrade files
        //Delete contents of modules/tools/uploads directory
        /*
        if(file_exists($savePath)){
            exec($cleanUpCmd1);
        } else {
            echo "<p>The directory $savePath was not deleted.</p>";
        }

        //Delete appUpgradeKey directory at the root of OASIS directory
        if(file_exists($baseDir . "appUpgradeKey")){
            exec($cleanUpCmd2);
        } else {
            echo "<p>The directory " . $baseDir . "appUpgradeKey was not deleted.</p>";
        }

         //Delete appUpgradeKey directory at the root of OASIS directory
         if(file_exists($tempPath)){
            exec($cleanUpCmd3);
        } else {
            echo "<p>The directory $tempPath was not deleted.</p>";
        }
        */
    }
}

//End ZIP file upload

//Test 2
if ($print_form > 0 && !$_REQUEST['modfunc'] == 'cancel') {
    //orig <form id='dataForm' name='dataForm' method='post' action='ForExport.php?modname=tools/AppUpgrade.php&action=backup&_openSIS_PDF=true' target=_blank>";
    echo "<div class='row'>
        <div class='col-md-6 col-md-offset-3'>";
        if($upgradeStatus > 0){
            echo "<form id='dataForm' name='dataForm' method='POST' enctype='multipart/form-data' action='Modules.php?modname=tools/AppUpgrade.php'>";
            PopTable('header', _upgrade);
            echo "<h2>" . _upgradeProcessComplete ."</h2>";
            echo $upgradeMsg;
            $btn = '<input type="submit" name="action"  value="'._ok.'" class="btn btn-primary"> &nbsp; ';
            PopTable('footer', $btn);
            echo "</form>";
        } else {
            //echo "<form id='dataForm' name='dataForm' method='POST' enctype='multipart/form-data' action='Modules.php?modname=tools/AppUpgradeProcess.php&page_display=APP_UPGRADE'>";
            echo "<form id='dataForm' name='dataForm' method='POST' enctype='multipart/form-data' action='Modules.php?modname=tools/AppUpgrade.php&page_display=APP_UPGRADE'>";
            
                PopTable('header', _upgrade);
                echo "<h4 class='text-danger'>"._note.":</h4><p>"._selecttheupgradeZIPfileprovidedbytheOASISsupportteam . " </p>";

                if(isset($upgradeStatus) AND $upgradeStatus == 0){
                    echo "<p>$upgradeMsg</p>";
                }

                echo "<label for='upgradeFile'>"._choosetheZIPfile."</label>
                <input type='file' name='upgradeFile' id='upgradeFile'>";

                $btn = '<input type="submit" name="action"  value="'._upgrade.'" class="btn btn-primary"> &nbsp; ';
                $modname = 'tools/AppUpgrade.php';
                $btn .= '<a href=javascript:void(0); onClick="check_content(\'Ajax.php?modname=miscellaneous/Portal.php\');" STYLE="TEXT-DECORATION: NONE"> <INPUT type=button class="btn btn-default" name=Cancel value="'._cancel.'"></a>';
                
                PopTable('footer', $btn);
        
            echo "</form>";
        }
    
    echo "</div>
    </div>";
    
}

?>
