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

$syear = $_SESSION['syear'];
$begin = $_SESSION['user_school_beg_date'];
$end = $_SESSION['user_school_end_date'];

//SQL for execution
$text = "

INSERT INTO report_card_grade_scales (syear,id,school_id,title,comment,sort_order,gp_scale,gpa_cal) VALUES
($syear,1,1,'4 Point Scale','Simple Four point grade scale',1,4,'Y');

INSERT INTO report_card_grades (syear,school_id,title,sort_order,break_off,grade_scale_id,unweighted_gp) VALUES
($syear,1,'A+',1,97,1,4),
($syear,1,'A',2,93,1,4),
($syear,1,'A-',3,90,1,3.67),
($syear,1,'B+',4,87,1,3.33),
($syear,1,'B',5,83,1,3),
($syear,1,'B-',6,80,1,2.67),
($syear,1,'C+',7,77,1,2.33),
($syear,1,'C',8,73,1,2),
($syear,1,'C-',9,70,1,1.67),
($syear,1,'D+',10,67,1,1.33),
($syear,1,'D',11,63,1,1),
($syear,1,'D-',12,60,1,0.67),
($syear,1,'F',13,0,1,0);


INSERT INTO attendance_codes (syear,school_id,title,short_name,type,state_code,default_code,table_name,sort_order) VALUES
($syear,1,'Present','P','teacher','P','Y',0,1),
($syear,1,'Absent','A','teacher','A',NULL,0,2),
($syear,1,'Sick','S','teacher','A',NULL,0,3),
($syear,1,'Excused Absence','EA','teacher','P',NULL,0,4),
($syear,1,'Tardy','T','teacher','H',NULL,0,5);

INSERT INTO school_gradelevels (id,school_id,short_name,title,next_grade_id,sort_order) VALUES
(1,1,'Y1','Year 1',2,1),
(2,1,'Y2','Year 2',3,2),
(3,1,'Y3','Year 3',4,3),
(4,1,'Y4','Year 4',NULL,4);

INSERT INTO school_periods (syear,school_id,sort_order,title,short_name,length,start_time,end_time) VALUES
($syear,1,1,'Period 1','P1',75,'07:00:00','08:15:00'),
($syear,1,2,'Period 2','P2',75,'08:45:00','10:00:00'),
($syear,1,3,'Chapel','CP',60,'10:30:00','11:30:00'),
($syear,1,4,'Period 3','P3',90,'13:30:00','15:00:00');

INSERT INTO rooms (school_id,title,capacity,description,sort_order) VALUES
(1,'Large Room',50,'The large classroom',1),
(1,'Middle Room',25,'The medium classroom',2),
(1,'Small Room',15,'The small classroom',3),
(1,'Chapel',150,'The chapel',4);

INSERT INTO course_subjects (syear,school_id,subject_id,title) VALUES
($syear,1,1,'Applied Ministry'),
($syear,1,2,'Leadership Development'),
($syear,1,3,'New Testament'),
($syear,1,4,'Old Testament'),
($syear,1,5,'Theology');


INSERT INTO courses (syear,school_id,subject_id,grade_level,title,short_name) VALUES
($syear,1,4,1,'Old Testament Survey','OT100'),
($syear,1,4,2,'The Pentateuch: The Five Books of Moses','OT200'),
($syear,1,4,2,'The Old Testament Historical Books: The Plan of God Unfolds','OT205'),
($syear,1,4,2,'The Psalms & Wisdom Literature: Guidelines for Worship and Living','OT210'),
($syear,1,4,2,'Major Prophets: Hope in Turbulent Times','OT215'),
($syear,1,4,2,'Minor Prophets: Their Message and Times','OT220'),
($syear,1,4,3,'Bible Geography: Encountering the Land of the Bible','OT300'),
($syear,1,4,3,'Old Testament Issues: Positive Answers to Difficult Questions','OT310'),
($syear,1,4,3,'Old Testament Theology','OT320'),
($syear,1,3,1,'New Testament Survey','NT100'),
($syear,1,3,2,'The Life of Christ in the Synoptic Gospels','NT205'),
($syear,1,3,2,'Acts: The Spirit of God in Mission','NT215'),
($syear,1,3,2,'Romans and Galatians: God\'s Plan of Salvation','NT220'),
($syear,1,3,2,'The Corinthian Letters: Unity Amid Diversity','NT225'),
($syear,1,3,2,'Prison Epistles: Practical Christian Living','NT230'),
($syear,1,3,2,'General Epistles: Faith for Turbulent Times','NT235'),
($syear,1,3,3,'New Testament Backgrounds: History, Culture, and Daily Life of the New Testament World','NT300'),
($syear,1,3,3,'Revelation: Unveiling the Future','NT310'),
($syear,1,5,1,'Bible Doctrines','TH100'),
($syear,1,5,1,'Hermeneutics: Interpreting the Bible','TH110'),
($syear,1,5,1,'Power Ministry: How to Minister in the Spirit\'s Power','TH120'),
($syear,1,5,1,'The Kingdom of God: A Pentecostal Interpretation','TH130'),
($syear,1,5,2,'Abundant Life in the Son: A Study of Salvation','TH200'),
($syear,1,5,2,'A Biblical Theology of Missions','TH220'),
($syear,1,5,2,'Pneumatology: A Missional Study of the Holy Spirit','TH230'),
($syear,1,5,3,'Systematic Theology I','TH300'),
($syear,1,5,3,'Systematic Theology II','TH305'),
($syear,1,5,3,'Advanced Hermeneutics: Understanding the Bible in Context','TH310'),
($syear,1,5,3,'Compassion and the Local Church','TH320'),
($syear,1,2,1,'Preparing to Learn','LD100'),
($syear,1,2,1,'Orientation and Learning Strategies','LD110'),
($syear,1,2,1,'LD130 Foundations of Leadership','LD130'),
($syear,1,2,2,'Biblical Models of Leadership','LD200'),
($syear,1,2,2,'Biblical Principles of Marriage','LD205'),
($syear,1,2,2,'Leading Christian Organizations','LD210'),
($syear,1,2,2,'A History of the Church in Africa: A Survey from a Pentecostal Perspective','LD215'),
($syear,1,2,2,'Principles of Teaching: Discovering How to Make Learning Happen','LD220'),
($syear,1,2,2,'Expository Preaching','LD225'),
($syear,1,2,2,'Principles of Counseling','LD230'),
($syear,1,2,3,'Church Administration: Managing Ministry Resources','LD300'),
($syear,1,2,3,'Called to Lead: Dynamics of Pastoral Leadership','LD320'),
($syear,1,1,1,'Evangelism: Fulfilling the Great Commission','MN100'),
($syear,1,1,1,'Discipleship: Developing Healthy Christians','MN110'),
($syear,1,1,2,'God\'s Mission and Ours: Confident Witness in the 21st Century','MN205'),
($syear,1,1,2,'Transformational Development and the Church: A Biblical Approach to Human Need','MN210'),
($syear,1,1,2,'Muslim Ministry in the African Context','MN215'),
($syear,1,1,2,'Church Growth Dynamics: Planting Healthy Churches','MN220'),
($syear,1,1,2,'A Biblical Theology of Worship','MN225'),
($syear,1,1,2,'Children\'s Ministry: Strategies for Making Young Disciples','MN230'),
($syear,1,1,2,'Contemporary African Ministry: Theological, Ethical, and Practical Issues','MN235'),
($syear,1,1,2,'Pastoral Ministry: The Work and Role of the Minister','MN240'),
($syear,1,1,3,'Urban Ministry: Reaching the City','MN300'),
($syear,1,1,3,'Pastoral Care in a Muslim Culture','MN310');

";

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
