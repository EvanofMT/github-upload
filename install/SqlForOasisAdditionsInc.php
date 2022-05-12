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
require_once("../functions/PragRepFnc.php");
$text = "
--
-- Dumping data for table `app`
--

INSERT INTO `app` (`name`, `value`) VALUES
('version', '7.6'),
('date', 'September 11, 2020'),
('build', '20200811001'),
('update', '0'),
('last_updated', 'September 11, 2020');


--
-- Dumping data for table `profile_exceptions`
--

INSERT INTO `profile_exceptions` (`profile_id`, `modname`, `can_use`, `can_edit`, `last_updated`, `updated_by`) VALUES
('2', 'students/Student.php&category_id=6', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('2', 'students/Student.php&category_id=7', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('3', 'students/Student.php&category_id=6', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('4', 'students/Student.php&category_id=6', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('2', 'users/User.php&category_id=5', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('3', 'schoolsetup/Schools.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('3', 'schoolsetup/Calendar.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('3', 'students/Student.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('3', 'students/Student.php&category_id=1', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('3', 'students/Student.php&category_id=3', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('3', 'students/ChangePassword.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('3', 'scheduling/ViewSchedule.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('3', 'scheduling/PrintSchedules.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('3', 'scheduling/Requests.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('3', 'grades/StudentGrades.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('3', 'grades/FinalGrades.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('3', 'grades/ReportCards.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('3', 'grades/Transcripts.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('3', 'grades/GPARankList.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('3', 'attendance/StudentSummary.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('3', 'attendance/DailySummary.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('3', 'eligibility/Student.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('3', 'eligibility/StudentList.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('2', 'schoolsetup/Schools.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('2', 'schoolsetup/MarkingPeriods.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('2', 'schoolsetup/Calendar.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('2', 'students/Student.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('2', 'students/AddUsers.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('2', 'students/AdvancedReport.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('2', 'students/StudentLabels.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('2', 'students/Student.php&category_id=1', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('2', 'students/Student.php&category_id=3', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('2', 'students/Student.php&category_id=4', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('2', 'users/User.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('5', 'schoolsetup/Rooms.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('2', 'grades/Grades.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('2', 'users/Preferences.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('2', 'scheduling/Schedule.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('2', 'scheduling/PrintSchedules.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('2', 'scheduling/PrintClassLists.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('2', 'scheduling/PrintClassPictures.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('2', 'grades/InputFinalGrades.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('2', 'grades/ReportCards.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('2', 'grades/Grades.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('2', 'grades/Assignments.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('2', 'grades/AnomalousGrades.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('2', 'grades/Configuration.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('2', 'grades/ProgressReports.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('2', 'grades/StudentGrades.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('2', 'grades/FinalGrades.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('2', 'grades/ReportCardGrades.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('2', 'grades/ReportCardComments.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('2', 'attendance/TakeAttendance.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('2', 'attendance/DailySummary.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('2', 'attendance/StudentSummary.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('2', 'eligibility/EnterEligibility.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('2', 'scheduling/ViewSchedule.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('4', 'attendance/StudentSummary.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('4', 'attendance/DailySummary.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('4', 'eligibility/Student.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('4', 'eligibility/StudentList.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('4', 'schoolsetup/Schools.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('4', 'schoolsetup/Calendar.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('4', 'students/Student.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('4', 'students/Student.php&category_id=1', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('4', 'students/Student.php&category_id=3', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('4', 'users/User.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('4', 'users/User.php&category_id=1', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('4', 'users/Preferences.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('4', 'scheduling/ViewSchedule.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('4', 'scheduling/Requests.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('4', 'grades/StudentGrades.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('4', 'grades/FinalGrades.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('4', 'grades/ReportCards.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('4', 'grades/Transcripts.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('4', 'grades/GPARankList.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('4', 'users/User.php&category_id=2', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('4', 'users/User.php&category_id=3', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('2', 'schoolsetup/Courses.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('2', 'schoolsetup/CourseCatalog.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('2', 'schoolsetup/PrintCatalog.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('2', 'schoolsetup/PrintAllCourses.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('2', 'students/Student.php&category_id=5', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('4', 'students/ChangePassword.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('4', 'scheduling/StudentScheduleReport.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('3', 'grades/ParentProgressReports.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('3', 'scheduling/StudentScheduleReport.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('5', 'schoolsetup/PortalNotes.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('5', 'schoolsetup/MarkingPeriods.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('5', 'schoolsetup/Calendar.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('5', 'schoolsetup/Periods.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('5', 'schoolsetup/GradeLevels.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('5', 'schoolsetup/Schools.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('5', 'schoolsetup/UploadLogo.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('5', 'schoolsetup/Schools.php?new_school=true', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('5', 'schoolsetup/CopySchool.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('5', 'schoolsetup/SystemPreference.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('5', 'schoolsetup/Courses.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('5', 'schoolsetup/CourseCatalog.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('5', 'schoolsetup/PrintCatalog.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('5', 'schoolsetup/PrintCatalogGradeLevel.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('5', 'schoolsetup/PrintAllCourses.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('5', 'schoolsetup/TeacherReassignment.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('5', 'students/Student.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('5', 'students/Student.php&include=GeneralInfoInc&student_id=new', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('5', 'students/AssignOtherInfo.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('5', 'students/AddUsers.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('5', 'students/AdvancedReport.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('5', 'students/AddDrop.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('5', 'students/Letters.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('5', 'students/MailingLabels.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('5', 'students/StudentLabels.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('5', 'students/PrintStudentInfo.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('5', 'students/PrintStudentContactInfo.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('5', 'students/GoalReport.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('5', 'students/StudentFields.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('5', 'students/EnrollmentCodes.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('5', 'students/Upload.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('5', 'students/Upload.php?modfunc=edit', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('5', 'students/Student.php&category_id=1', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('5', 'students/Student.php&category_id=2', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('5', 'students/Student.php&category_id=3', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('5', 'students/Student.php&category_id=4', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('5', 'students/Student.php&category_id=5', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('5', 'users/User.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('5', 'users/User.php&staff_id=new', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('5', 'users/AddStudents.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('5', 'users/Preferences.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('5', 'users/Profiles.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('5', 'users/Exceptions.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('5', 'users/UserFields.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('5', 'users/TeacherPrograms.php?include=grades/InputFinalGrades.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('5', 'users/TeacherPrograms.php?include=grades/Grades.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('5', 'users/TeacherPrograms.php?include=grades/ProgressReports.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('5', 'users/TeacherPrograms.php?include=attendance/TakeAttendance.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('5', 'users/TeacherPrograms.php?include=attendance/Missing_Attendance.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('5', 'users/TeacherPrograms.php?include=eligibility/EnterEligibility.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('5', 'users/User.php&category_id=1', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('5', 'users/User.php&category_id=2', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('5', 'scheduling/Schedule.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('5', 'scheduling/ViewSchedule.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('5', 'scheduling/Requests.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('5', 'scheduling/MassSchedule.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('5', 'scheduling/MassRequests.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('5', 'scheduling/MassDrops.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('5', 'scheduling/PrintSchedules.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('5', 'scheduling/PrintClassLists.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('5', 'scheduling/PrintClassPictures.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('5', 'scheduling/PrintRequests.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('5', 'scheduling/ScheduleReport.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('5', 'scheduling/RequestsReport.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('5', 'scheduling/UnfilledRequests.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('5', 'scheduling/IncompleteSchedules.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('5', 'scheduling/AddDrop.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('5', 'scheduling/Scheduler.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('5', 'grades/ReportCards.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('5', 'grades/CalcGPA.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('5', 'grades/Transcripts.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('5', 'grades/TeacherCompletion.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('5', 'grades/GradeBreakdown.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('5', 'grades/FinalGrades.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('5', 'grades/GPARankList.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('5', 'grades/AdminProgressReports.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('5', 'grades/HonorRoll.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('5', 'grades/ReportCardGrades.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('5', 'grades/ReportCardComments.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('5', 'grades/HonorRollSetup.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('5', 'grades/FixGPA.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('5', 'grades/EditReportCardGrades.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('5', 'grades/EditHistoryMarkingPeriods.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('5', 'attendance/Administration.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('5', 'attendance/AddAbsences.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('5', 'attendance/AttendanceData.php?list_by_day=true', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('5', 'attendance/Percent.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('5', 'attendance/Percent.php?list_by_day=true', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('5', 'attendance/DailySummary.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('5', 'attendance/StudentSummary.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('5', 'attendance/TeacherCompletion.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('5', 'attendance/FixDailyAttendance.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('5', 'attendance/DuplicateAttendance.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('5', 'attendance/AttendanceCodes.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('5', 'eligibility/Student.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('5', 'eligibility/AddActivity.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('5', 'eligibility/StudentList.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('5', 'eligibility/TeacherCompletion.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('5', 'eligibility/Activities.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('5', 'eligibility/EntryTimes.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('5', 'tools/LogDetails.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('5', 'tools/DeleteLog.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('5', 'tools/Rollover.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('2', 'users/Staff.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('1', 'schoolsetup/SchoolCustomFields.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'students/Student.php&category_id=6', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'students/Student.php&category_id=7', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'users/User.php&category_id=5', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'schoolsetup/PortalNotes.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'schoolsetup/Schools.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'schoolsetup/Schools.php?new_school=true', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'schoolsetup/CopySchool.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'schoolsetup/MarkingPeriods.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'schoolsetup/Calendar.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'schoolsetup/Periods.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'schoolsetup/GradeLevels.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'schoolsetup/Rollover.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'schoolsetup/Courses.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'schoolsetup/CourseCatalog.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'schoolsetup/PrintCatalog.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'schoolsetup/PrintCatalogGradeLevel.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'schoolsetup/PrintAllCourses.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'schoolsetup/UploadLogo.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'schoolsetup/TeacherReassignment.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'students/Student.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'students/Student.php&include=GeneralInfoInc&student_id=new', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'students/AssignOtherInfo.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'students/AddUsers.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'students/AdvancedReport.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'students/AddDrop.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'students/Letters.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'students/MailingLabels.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'students/StudentLabels.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'students/PrintStudentInfo.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'students/PrintStudentContactInfo.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'students/GoalReport.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'students/StudentFields.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'students/AddressFields.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'students/PeopleFields.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'students/EnrollmentCodes.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'students/Upload.php?modfunc=edit', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'students/Upload.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'students/Student.php&category_id=1', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'students/Student.php&category_id=3', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'students/Student.php&category_id=2', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'students/Student.php&category_id=4', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'students/StudentReenroll.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'students/EnrollmentReport.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'users/User.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'users/User.php&category_id=1', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'users/User.php&category_id=2', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'users/User.php&staff_id=new', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'users/AddStudents.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'users/Preferences.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'users/Profiles.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'users/Exceptions.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'users/UserFields.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'users/TeacherPrograms.php?include=grades/InputFinalGrades.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'users/TeacherPrograms.php?include=grades/Grades.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'users/TeacherPrograms.php?include=attendance/TakeAttendance.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'users/TeacherPrograms.php?include=attendance/Missing_Attendance.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'users/TeacherPrograms.php?include=eligibility/EnterEligibility.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'users/UploadUserPhoto.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'users/UploadUserPhoto.php?modfunc=edit', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'users/UserAdvancedReport.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'users/UserAdvancedReportStaff.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'scheduling/Schedule.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'scheduling/Requests.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'scheduling/MassSchedule.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'scheduling/MassRequests.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'scheduling/MassDrops.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'scheduling/ScheduleReport.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'scheduling/RequestsReport.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'scheduling/UnfilledRequests.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'scheduling/IncompleteSchedules.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'scheduling/AddDrop.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'scheduling/PrintSchedules.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'scheduling/PrintRequests.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'scheduling/PrintClassLists.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'scheduling/PrintClassPictures.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'scheduling/Courses.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'scheduling/Scheduler.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'scheduling/ViewSchedule.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'grades/ReportCards.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'grades/CalcGPA.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'grades/Transcripts.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'grades/TeacherCompletion.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'grades/GradeBreakdown.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'grades/FinalGrades.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'grades/GPARankList.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'grades/ReportCardGrades.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'grades/ReportCardComments.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'grades/FixGPA.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'grades/EditReportCardGrades.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'grades/EditHistoryMarkingPeriods.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'grades/HistoricalReportCardGrades.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'attendance/Administration.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'attendance/AddAbsences.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'attendance/AttendanceData.php?list_by_day=true', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'attendance/Percent.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'attendance/Percent.php?list_by_day=true', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'attendance/DailySummary.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'attendance/StudentSummary.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'attendance/TeacherCompletion.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'attendance/DuplicateAttendance.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'attendance/AttendanceCodes.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'attendance/FixDailyAttendance.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'eligibility/Student.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'eligibility/AddActivity.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'eligibility/StudentList.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'eligibility/TeacherCompletion.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'eligibility/Activities.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'eligibility/EntryTimes.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'tools/LogDetails.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'tools/DeleteLog.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('5', 'schoolsetup/SchoolCustomFields.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'tools/Rollover.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'students/Upload.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'students/Upload.php?modfunc=edit', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'schoolsetup/SystemPreference.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'students/Student.php&category_id=5', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'grades/HonorRoll.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'users/TeacherPrograms.php?include=grades/ProgressReports.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'users/User.php&category_id=2', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'grades/HonorRollSetup.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'grades/AdminProgressReports.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'users/Staff.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'users/Staff.php&staff_id=new', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'users/Exceptions_staff.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'users/StaffFields.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'users/Staff.php&category_id=1', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'users/Staff.php&category_id=2', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'users/Staff.php&category_id=3', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'users/Staff.php&category_id=4', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'messaging/Inbox.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'messaging/Compose.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'messaging/SentMail.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'messaging/Trash.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'messaging/Group.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('4', 'messaging/Inbox.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('4', 'messaging/Compose.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('4', 'messaging/SentMail.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('4', 'messaging/Trash.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('4', 'messaging/Group.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('2', 'messaging/Inbox.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('2', 'messaging/Compose.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('2', 'messaging/SentMail.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('2', 'messaging/Trash.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('2', 'messaging/Group.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('3', 'messaging/Inbox.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('3', 'messaging/Compose.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('3', 'messaging/SentMail.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('3', 'messaging/Trash.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('3', 'messaging/Group.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('0', 'students/Student.php&category_id=6', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'students/Student.php&category_id=7', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'users/User.php&category_id=5', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'schoolsetup/PortalNotes.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'schoolsetup/Schools.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'schoolsetup/Schools.php?new_school=true', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'schoolsetup/CopySchool.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'schoolsetup/MarkingPeriods.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'schoolsetup/Calendar.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'schoolsetup/Periods.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'schoolsetup/GradeLevels.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'schoolsetup/Rollover.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'schoolsetup/Courses.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'schoolsetup/CourseCatalog.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'schoolsetup/PrintCatalog.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'schoolsetup/PrintCatalogGradeLevel.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'schoolsetup/PrintAllCourses.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'schoolsetup/UploadLogo.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'schoolsetup/TeacherReassignment.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'students/Student.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'students/Student.php&include=GeneralInfoInc&student_id=new', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'students/AssignOtherInfo.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'students/AddUsers.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'students/AdvancedReport.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'students/AddDrop.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'students/Letters.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'students/MailingLabels.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'students/StudentLabels.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'students/PrintStudentInfo.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'students/PrintStudentContactInfo.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'students/GoalReport.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'students/StudentFields.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'students/AddressFields.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'students/PeopleFields.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'students/EnrollmentCodes.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'students/Upload.php?modfunc=edit', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'students/Upload.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'students/Student.php&category_id=1', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'students/Student.php&category_id=3', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'students/Student.php&category_id=2', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'students/Student.php&category_id=4', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'students/StudentReenroll.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'students/EnrollmentReport.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'users/User.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'users/User.php&category_id=1', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'users/User.php&category_id=2', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'users/User.php&staff_id=new', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'users/AddStudents.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'users/Preferences.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'users/Profiles.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'users/Exceptions.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'users/UserFields.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'users/TeacherPrograms.php?include=grades/InputFinalGrades.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'users/TeacherPrograms.php?include=grades/Grades.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'users/TeacherPrograms.php?include=attendance/TakeAttendance.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'users/TeacherPrograms.php?include=attendance/Missing_Attendance.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'users/TeacherPrograms.php?include=eligibility/EnterEligibility.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'users/UploadUserPhoto.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'users/UploadUserPhoto.php?modfunc=edit', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'users/UserAdvancedReport.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'users/UserAdvancedReportStaff.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'scheduling/Schedule.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'scheduling/Requests.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'scheduling/MassSchedule.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'scheduling/MassRequests.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'scheduling/MassDrops.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'scheduling/ScheduleReport.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'scheduling/RequestsReport.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'scheduling/UnfilledRequests.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'scheduling/IncompleteSchedules.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'scheduling/AddDrop.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'scheduling/PrintSchedules.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'scheduling/PrintRequests.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'scheduling/PrintClassLists.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'scheduling/PrintClassPictures.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'scheduling/Courses.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'scheduling/Scheduler.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'scheduling/ViewSchedule.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'grades/ReportCards.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'grades/CalcGPA.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'grades/Transcripts.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'grades/TeacherCompletion.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'grades/GradeBreakdown.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'grades/FinalGrades.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'grades/GPARankList.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'grades/ReportCardGrades.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'grades/ReportCardComments.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'grades/FixGPA.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'grades/EditReportCardGrades.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'grades/EditHistoryMarkingPeriods.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'grades/HistoricalReportCardGrades.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'attendance/Administration.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'attendance/AddAbsences.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'attendance/AttendanceData.php?list_by_day=true', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'attendance/Percent.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'attendance/Percent.php?list_by_day=true', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'attendance/DailySummary.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'attendance/StudentSummary.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'attendance/TeacherCompletion.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'attendance/DuplicateAttendance.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'attendance/AttendanceCodes.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'attendance/FixDailyAttendance.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'eligibility/Student.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'eligibility/AddActivity.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'eligibility/StudentList.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'eligibility/TeacherCompletion.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'eligibility/Activities.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'eligibility/EntryTimes.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'tools/LogDetails.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'tools/DeleteLog.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'tools/Backup.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'tools/Rollover.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'students/Upload.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'students/Upload.php?modfunc=edit', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'schoolsetup/SystemPreference.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'students/Student.php&category_id=5', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'grades/HonorRoll.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'users/TeacherPrograms.php?include=grades/ProgressReports.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'users/User.php&category_id=2', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'grades/HonorRollSetup.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'grades/AdminProgressReports.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'users/Staff.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'users/Staff.php&staff_id=new', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'users/Exceptions_staff.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'users/StaffFields.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'users/Staff.php&category_id=1', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'users/Staff.php&category_id=2', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'users/Staff.php&category_id=3', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'users/Staff.php&category_id=4', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'schoolsetup/SchoolCustomFields.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'messaging/Inbox.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'messaging/Compose.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'messaging/SentMail.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'messaging/Trash.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'messaging/Group.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'schoolsetup/Rooms.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'schoolsetup/school_specific_standards.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'users/TeacherPrograms.php?include=grades/AdminProgressReports.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'tools/Reports.php?func=Basic', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'tools/Reports.php?func=Ins_r', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'tools/Reports.php?func=Ins_cf', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'schoolsetup/us_common_standards.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'schoolsetup/EffortGradeLibrary.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'grades/EffortGradeSetup.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('4', 'scheduling/PrintSchedules.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('0', 'users/TeacherPrograms.php?include=attendance/MissingAttendance.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('0', 'users/Staff.php&category_id=5', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'schoolsetup/Rooms.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'users/TeacherPrograms.php?include=attendance/MissingAttendance.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('1', 'users/Staff.php&category_id=5', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('5', 'students/EnrollmentReport.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('5', 'users/TeacherPrograms.php?include=attendance/MissingAttendance.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('5', 'messaging/Inbox.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('5', 'messaging/Compose.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('5', 'messaging/SentMail.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('5', 'messaging/Trash.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('5', 'messaging/Group.php', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('2', 'users/Staff.php&category_id=1', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('2', 'users/Staff.php&category_id=2', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('2', 'users/Staff.php&category_id=3', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('2', 'users/Staff.php&category_id=4', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('2', 'users/Staff.php&category_id=5', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
('4', 'grades/ParentProgressReports.php', 'Y', NULL, '2019-07-28 15:26:33', NULL),
('0', 'schoolsetup/Sections.php', 'Y', 'Y', '2019-07-25 21:53:00', NULL),
('1', 'schoolsetup/Sections.php', 'Y', 'Y', '2019-07-25 21:53:25', NULL),
('0', 'tools/DataImport.php', 'Y', 'Y', '2019-07-25 21:53:25', NULL),
('1', 'tools/DataImport.php', 'Y', 'Y', '2019-07-25 21:53:25', NULL),
('0', 'tools/GenerateApi.php', 'Y', 'Y', '2020-11-03 00:34:02', NULL),
('1', 'tools/GenerateApi.php', 'Y', 'Y', '2019-08-04 22:33:56', NULL);


--
-- Dumping data for table `program_config`
--

INSERT INTO `program_config` (`syear`, `school_id`, `program`, `title`, `value`, `last_updated`, `updated_by`) VALUES
('".$_SESSION['syear']."', NULL, 'Currency', 'US Dollar (USD)', '1', '2019-07-28 15:26:33', NULL),
('".$_SESSION['syear']."', NULL, 'Currency', 'British Pound (GBP)', '2', '2019-07-28 15:26:33', NULL),
('".$_SESSION['syear']."', NULL, 'Currency', 'Euro (EUR)', '3', '2019-07-28 15:26:33', NULL),
('".$_SESSION['syear']."', NULL, 'Currency', 'Canadian Dollar (CAD)', '4', '2019-07-28 15:26:33', NULL),
('".$_SESSION['syear']."', NULL, 'Currency', 'Australian Dollar (AUD)', '5', '2019-07-28 15:26:33', NULL),
('".$_SESSION['syear']."', NULL, 'Currency', 'Brazilian Real (BRL)', '6', '2019-07-28 15:26:33', NULL),
('".$_SESSION['syear']."', NULL, 'Currency', 'Chinese Yuan Renminbi (CNY)', '7', '2019-07-28 15:26:33', NULL),
('".$_SESSION['syear']."', NULL, 'Currency', 'Danish Krone (DKK)', '8', '2019-07-28 15:26:33', NULL),
('".$_SESSION['syear']."', NULL, 'Currency', 'Japanese Yen (JPY)', '9', '2019-07-28 15:26:33', NULL),
('".$_SESSION['syear']."', NULL, 'Currency', 'Indian Rupee (INR)', '10', '2019-07-28 15:26:33', NULL),
('".$_SESSION['syear']."', NULL, 'Currency', 'Indonesian Rupiah (IDR)', '11', '2019-07-28 15:26:33', NULL),
('".$_SESSION['syear']."', NULL, 'Currency', 'Korean Won  (KRW)', '12', '2019-07-28 15:26:33', NULL),
('".$_SESSION['syear']."', NULL, 'Currency', 'Malaysian Ringit (MYR)', '13', '2019-07-28 15:26:33', NULL),
('".$_SESSION['syear']."', NULL, 'Currency', 'Mexican Peso (MXN)', '14', '2019-07-28 15:26:33', NULL),
('".$_SESSION['syear']."', NULL, 'Currency', 'New Zealand Dollar (NZD)', '15', '2019-07-28 15:26:33', NULL),
('".$_SESSION['syear']."', NULL, 'Currency', 'Norwegian Krone  (NOK)', '16', '2019-07-28 15:26:33', NULL),
('".$_SESSION['syear']."', NULL, 'Currency', 'Pakistan Rupee  (PKR)', '17', '2019-07-28 15:26:33', NULL),
('".$_SESSION['syear']."', NULL, 'Currency', 'Philippino Peso (PHP)', '18', '2019-07-28 15:26:33', NULL),
('".$_SESSION['syear']."', NULL, 'Currency', 'Saudi Riyal (SAR)', '19', '2019-07-28 15:26:33', NULL),
('".$_SESSION['syear']."', NULL, 'Currency', 'Singapore Dollar (SGD)', '20', '2019-07-28 15:26:33', NULL),
('".$_SESSION['syear']."', NULL, 'Currency', 'South African Rand  (ZAR)', '21', '2019-07-28 15:26:33', NULL),
('".$_SESSION['syear']."', NULL, 'Currency', 'Swedish Krona  (SEK)', '22', '2019-07-28 15:26:33', NULL),
('".$_SESSION['syear']."', NULL, 'Currency', 'Swiss Franc  (CHF)', '23', '2019-07-28 15:26:33', NULL),
('".$_SESSION['syear']."', NULL, 'Currency', 'Thai Bhat  (THB)', '24', '2019-07-28 15:26:33', NULL),
('".$_SESSION['syear']."', NULL, 'Currency', 'Turkish Lira  (TRY)', '25', '2019-07-28 15:26:33', NULL),
('".$_SESSION['syear']."', NULL, 'Currency', 'United Arab Emirates Dirham (AED)', '26', '2019-07-28 15:26:33', NULL);

INSERT INTO `program_config` (`syear`, `school_id`, `program`, `title`, `value`) VALUES
('".$_SESSION['syear']."',1, 'MissingAttendance', 'LAST_UPDATE','".date('Y-m-d',  strtotime($_SESSION['user_school_beg_date']))."'),
('".$_SESSION['syear']."', 1, 'eligibility', 'START_DAY', '1'),
('".$_SESSION['syear']."', 1, 'eligibility', 'START_HOUR', '8'),
('".$_SESSION['syear']."', 1, 'eligibility', 'START_MINUTE', '00'),
('".$_SESSION['syear']."', 1, 'eligibility', 'START_M', 'AM'),
('".$_SESSION['syear']."', 1, 'eligibility', 'END_DAY', '5'),
('".$_SESSION['syear']."', 1, 'eligibility', 'END_HOUR', '16'),
('".$_SESSION['syear']."', 1, 'eligibility', 'END_MINUTE', '00'),
('".$_SESSION['syear']."', 1, 'eligibility', 'END_M', 'PM'),
('".$_SESSION['syear']."', 1, 'UPDATENOTIFY', 'display', 'Y'),
('".$_SESSION['syear']."', 1, 'UPDATENOTIFY', 'display_school', 'Y'),
('".$_SESSION['syear']."', 1, 'SeatFill', 'LAST_UPDATE', '".date('Y-m-d')."');

--
-- Dumping data for table `program_user_config`
--

INSERT INTO `program_user_config` (`user_id`, `school_id`, `program`, `title`, `value`, `last_updated`, `updated_by`) VALUES
('1', NULL, 'Preferences', 'THEME', 'blue', '2019-07-28 09:56:33', NULL),
('1', NULL, 'Preferences', 'MONTH', 'M', '2019-07-28 09:56:33', NULL),
('1', NULL, 'Preferences', 'DAY', 'j', '2019-07-28 09:56:33', NULL),
('1', NULL, 'Preferences', 'YEAR', 'Y', '2019-07-28 09:56:33', NULL),
('1', NULL, 'Preferences', 'HIDDEN', 'Y', '2019-07-28 09:56:33', NULL),
('1', NULL, 'Preferences', 'CURRENCY', '1', '2019-07-28 09:56:33', NULL),
('1', NULL, 'Preferences', 'HIDE_ALERTS', 'N', '2019-07-28 09:56:33', NULL);


--
-- Dumping data for table `schools`
--


INSERT INTO `schools` (`syear`, `title`, `address`, `city`, `state`, `zipcode`, `area_code`, `phone`, `principal`, `www_address`, `e_mail`, `reporting_gp_scale`, `last_updated`, `updated_by`) VALUES
(".$_SESSION['syear'].", '".$_SESSION['sname']."', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL,'', NULL);

--
-- Dumping data for table `login_authentication`
--

INSERT INTO `login_authentication` (`id`, `user_id`, `profile_id`, `username`, `password`, `last_login`, `failed_login`, `last_updated`, `updated_by`) VALUES
(1, 1, 0, 'os4ed', 'f7658b271318b97a17e625f875ea5a24', '2019-08-19 23:59:43', 0, '2019-07-28 09:56:33', NULL);


--
-- Dumping data for table `system_preference`
--


INSERT INTO `system_preference` (`id`, `school_id`, `full_day_minute`, `half_day_minute`, `last_updated`, `updated_by`) VALUES
(1, 1, 5, 2, '2019-07-28 15:26:33', NULL);


--
-- Dumping data for table `login_message`
--

INSERT INTO `login_message` (`id`, `message`, `display`) VALUES
(1, 'This is a restricted network. Use of this network, its equipment, and resources is monitored at all times and requires explicit permission from the network administrator. If you do not have this permission in writing, you are violating the regulations of this network and can and will be prosecuted to the fullest extent of law. By continuing into this system, you are acknowledging that you are aware of and agree to these terms.', 'Y');



--
-- Dumping data for table `school_years`
--
INSERT INTO `school_years` (`marking_period_id`, `syear`, `school_id`, `title`, `short_name`, `sort_order`, `start_date`, `end_date`, `does_exam`, `rollover_id`, `last_updated`, `updated_by`) VALUES
(1, '".$_SESSION['syear']."', 1, 'Full Year', 'FY', 1, '".$_SESSION['user_school_beg_date']."', '".$_SESSION['user_school_end_date']."', NULL, NULL, '2020-01-22 04:18:02', NULL);
--
-- Dumping data for table `marking_period_id_generator`
--

INSERT INTO `marking_period_id_generator` (`id`) VALUES
(1);

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`staff_id`, `current_school_id`, `title`, `first_name`, `last_name`, `middle_name`, `phone`, `email`, `profile`, `homeroom`, `profile_id`, `primary_language_id`, `gender`, `ethnicity_id`, `birthdate`, `alternate_id`, `name_suffix`, `second_language_id`, `third_language_id`, `is_disable`, `physical_disability`, `disability_desc`, `img_name`, `img_content`, `last_updated`, `updated_by`) VALUES
(1, '1', NULL, 'Joe', 'Administrator', '', NULL, 'joe@pshs.edu', 'admin', NULL, '0', 1, 'Male', 1, NULL, NULL, NULL, 5, NULL, 'N', 'N', NULL, NULL, NULL, '2015-07-28 09:56:33', NULL);


--
-- Dumping data for table `staff_school_relationship`
--

INSERT INTO `staff_school_relationship` (`staff_id`, `school_id`, `syear`, `start_date`, `end_date`) VALUES
(1, 1, ".$_SESSION['syear'].",'".$_SESSION['user_school_beg_date']."', '0000-00-00');
    
--
-- Dumping data for table `staff_school_info`
--
INSERT INTO `staff_school_info` (`staff_school_info_id`, `staff_id`, `category`, `job_title`, `joining_date`, `end_date`, `home_school`, `opensis_access`, `opensis_profile`, `school_access`, `last_updated`, `updated_by`) VALUES
(1, 1, 'Super Administrator', 'Super Administrator', '2019-01-01', NULL, 1, 'Y', '0', '1', '2020-01-22 09:18:03', NULL);



--
-- Dumping data for table `people_field_categories`
--

INSERT INTO `people_field_categories` (`id`, `title`, `sort_order`, `include`, `admin`, `teacher`, `parent`, `none`, `last_updated`, `updated_by`) VALUES
(1, 'General Info', '1', NULL, 'Y', 'Y', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
(2, 'Address Info', '2', NULL, 'Y', 'Y', 'Y', 'Y', '2019-07-28 15:26:33', NULL);
--
-- Dumping data for table `staff_field_categories`
--

INSERT INTO `staff_field_categories` (`id`, `title`, `sort_order`, `include`, `admin`, `teacher`, `parent`, `none`, `last_updated`, `updated_by`) VALUES
(1, 'Demographic Info', '1', NULL, 'Y', 'Y', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
(2, 'Addresses & Contacts', '2', NULL, 'Y', 'Y', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
(3, 'School Information', '3', NULL, 'Y', 'Y', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
(4, 'Certification Information', '4', NULL, 'Y', 'Y', 'Y', 'Y', '2019-07-28 15:26:33', NULL),
(5, 'Schedule', '5', NULL, 'Y', 'Y', NULL, NULL, '2019-07-28 15:26:33', NULL);

--
-- Dumping data for table `ethnicity`
--

INSERT INTO `ethnicity` (`ethnicity_id`, `ethnicity_name`, `sort_order`, `last_updated`, `updated_by`) VALUES
(1, 'White, Non-Hispanic', 1, '0000-00-00 00:00:00', NULL),
(2, 'Black, Non-Hispanic', 2, '0000-00-00 00:00:00', NULL),
(3, 'Hispanic', 3, '0000-00-00 00:00:00', NULL),
(4, 'American Indian or Native Alaskan', 4, '0000-00-00 00:00:00', NULL),
(5, 'Pacific Islander', 5, '0000-00-00 00:00:00', NULL),
(6, 'Asian', 6, '0000-00-00 00:00:00', NULL),
(7, 'Indian', 7, '0000-00-00 00:00:00', NULL),
(8, 'Middle Eastern', 8, '0000-00-00 00:00:00', NULL),
(9, 'African', 9, '0000-00-00 00:00:00', NULL),
(10, 'Mixed Race', 10, '0000-00-00 00:00:00', NULL),
(11, 'Other', 11, '0000-00-00 00:00:00', NULL);


--
-- Dumping data for table `language`
--

INSERT INTO `language` (`language_id`, `language_name`, `sort_order`, `last_updated`, `updated_by`) VALUES
(1, 'English', 1, '2019-07-28 15:26:33', NULL),
(2, 'Arabic', 2, '2019-07-28 15:26:33', NULL),
(3, 'Bengali', 3, '2019-07-28 15:26:33', NULL),
(4, 'Chinese', 4, '2019-07-28 15:26:33', NULL),
(5, 'French', 5, '2019-07-28 15:26:33', NULL),
(6, 'German', 6, '2019-07-28 15:26:33', NULL),
(7, 'Haitian Creole', 7, '2019-07-28 15:26:33', NULL),
(8, 'Hindi', 8, '2019-07-28 15:26:33', NULL),
(9, 'Italian', 9, '2019-07-28 15:26:33', NULL),
(10, 'Japanese', 10, '2019-07-28 15:26:33', NULL),
(11, 'Korean', 11, '2019-07-28 15:26:33', NULL),
(12, 'Malay', 12, '2019-07-28 15:26:33', NULL),
(13, 'Polish', 13, '2019-07-28 15:26:33', NULL),
(14, 'Portuguese', 14, '2019-07-28 15:26:33', NULL),
(15, 'Russian', 15, '2019-07-28 15:26:33', NULL),
(16, 'Spanish', 16, '2019-07-28 15:26:33', NULL),
(17, 'Thai', 17, '2019-07-28 15:26:33', NULL),
(18, 'Turkish', 18, '2019-07-28 15:26:33', NULL),
(19, 'Urdu', 19, '2019-07-28 15:26:33', NULL),
(20, 'Vietnamese', 20, '2019-07-28 15:26:33', NULL);


--
-- Dumping data for table `student_enrollment_codes`
--
INSERT INTO `student_enrollment_codes` (`syear`, `title`, `short_name`, `type`, `last_updated`, `updated_by`) VALUES
(".$_SESSION['syear'].",  'Transferred Out', 'TRAN', 'TrnD', '2019-07-28 05:56:33', NULL),
(".$_SESSION['syear'].",  'Transferred In', 'TRAN', 'TrnE', '2019-07-28 05:56:33', NULL),
(".$_SESSION['syear'].",  'Rolled Over', 'ROLL', 'Roll', '2019-07-28 05:56:33', NULL),
(".$_SESSION['syear'].",  'Dropped Out', 'DROP', 'Drop', '2019-07-28 05:56:33', NULL),
(".$_SESSION['syear'].",  'New', 'NEW', 'Add', '2019-07-28 05:56:33', NULL);

--
-- Dumping data for table `student_field_categories`
--

INSERT INTO `student_field_categories` (`id`, `title`, `sort_order`, `include`, `last_updated`, `updated_by`) VALUES
(1, 'General Info', '1', NULL, '2019-07-28 15:26:33', NULL),
(2, 'Medical', '3', NULL, '2019-07-28 15:26:33', NULL),
(3, 'Addresses & Contacts', '2', NULL, '2019-07-28 15:26:33', NULL),
(4, 'Comments', '4', NULL, '2019-07-28 15:26:33', NULL),
(5, 'Goals', '5', NULL, '2019-07-28 15:26:33', NULL),
(6, 'Enrollment Info', '6', NULL, '2019-07-28 15:26:33', NULL),
(7, 'Files', '7', NULL, '2019-07-28 15:26:33', NULL);


--
-- Adding Schoolwide Schedule Report to `profile_exceptions`
--

INSERT INTO `profile_exceptions` (`profile_id`, `modname`, `can_use`, `can_edit`) VALUES
('0', 'scheduling/SchoolwideScheduleReport.php', 'Y', 'Y'),
('1', 'scheduling/SchoolwideScheduleReport.php', 'Y', 'Y');

--
-- Dumping data for table `user_profiles`
--

INSERT INTO `user_profiles` (`profile`, `title`, `last_updated`, `updated_by`) VALUES
('admin', 'Super Administrator', '2019-07-28 04:26:33', NULL);
UPDATE  `user_profiles` SET  `id` =  '0' ;
ALTER TABLE  `user_profiles` AUTO_INCREMENT=1;

INSERT INTO `user_profiles` (`id`, `profile`, `title`, `last_updated`, `updated_by`) VALUES
(1, 'admin', 'Administrator', '2019-07-28 04:26:33', NULL),
(2, 'teacher', 'Teacher', '2019-07-28 04:26:33', NULL),
(3, 'student', 'Student', '2019-07-28 04:26:33', NULL),
(4, 'parent', 'Parent', '2019-07-28 04:26:33', NULL),
(5, 'admin', 'Admin Asst', '2019-07-28 04:26:33', NULL);";

	$dbconn = new mysqli($_SESSION['host'],$_SESSION['username'],$_SESSION['password'],$_SESSION['db'],$_SESSION['port']);
	$sqllines = par_spt("/[\n]/",$text);
	$cmd = '';
	foreach($sqllines as $l)
	{
		if(par_rep_mt('/^\s*--/',$l) == 0)
		{
			$cmd .= ' ' . $l . "\n";
			if(par_rep_mt('/.+;/',$l) != 0)
			{
				$result = $dbconn->query($cmd) or die($dbconn->error);
				$cmd = '';
			}
		}
	}

?>
