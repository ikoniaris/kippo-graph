<?php
#CSV Export for Kippo Graph
#Author: Kevin Breen
#Website: techanarchy.net

//TOTAL LOGIN ATTEMPTS
$db_AllCount = 'SELECT COUNT(*) AS logins '
	. "FROM auth";

//TOTAL DISTINCT IPs
$db_CountIP = 'SELECT COUNT(DISTINCT ip) AS IPs '
	. "FROM sessions";
	
//NUMBER CONNECTIONS PER IP

$db_IP = 'SELECT ip, COUNT(ip) '
	. "FROM sessions "
	. "GROUP BY ip "
	. "ORDER BY COUNT(ip) DESC ";

//OPERATIONAL TIME PERIOD
$db_OpTime = 'SELECT MIN(timestamp) AS start, MAX(timestamp) AS end '
	. "FROM auth";

// ALL PASSWORDS AND COUNT
$db_Pass = 'SELECT password, COUNT(password) '
	. "FROM auth "
	. "WHERE password <> '' "
	. "GROUP BY password "
	. "ORDER BY COUNT(password) DESC ";

// ALL USERNAMES AND COUNT
$db_User = 'SELECT username, COUNT(username) '
	. "FROM auth "
	. "WHERE username <> '' "
	. "GROUP BY username "
	. "ORDER BY COUNT(username) DESC ";
	
//ALL USER PASS COMBINATIONS
$db_Combo = 'SELECT username, password, COUNT(username) '
	. "FROM auth "
	. "WHERE username <> '' AND password <> '' "
	. "GROUP BY username, password "
	. "ORDER BY COUNT(username) DESC ";

//SUCCESS RATIO
$db_Success = 'SELECT success, COUNT(success) '
	. "FROM auth "
	. "GROUP BY success "
	. "ORDER BY success";	


//MOST SUCCESFUL LOGONS PER DAY

$db_SuccessLogon = 'SELECT COUNT(session), timestamp '
	. "FROM auth "
	. "WHERE success = 1 "
	. "GROUP BY DAYOFYEAR(timestamp) "
	//."HAVING COUNT(session) >= XX "
	. "ORDER BY COUNT(session) DESC "
	//."ORDER BY timestamp ASC "
	;

//SUCCESS PER DAY
$db_SuccessDay = 'SELECT COUNT(session), timestamp '
	. "FROM auth "
	. "WHERE success = 1 "
	. "GROUP BY DAYOFYEAR(timestamp) "
	. "ORDER BY timestamp ASC ";
			
//SUCCESS PER WEEK
$db_SuccessWeek = 'SELECT COUNT(session), MAKEDATE( '
	. "CASE "
	. "WHEN WEEKOFYEAR(timestamp) = 52 "
	. "THEN YEAR(timestamp)-1 "
	. "ELSE YEAR(timestamp) "
	. "END, (WEEKOFYEAR(timestamp) * 7)-4) AS DateOfWeek_Value "
	. "FROM auth "
	. "WHERE success = 1 "
	. "GROUP BY WEEKOFYEAR(timestamp) "
	. "ORDER BY timestamp ASC";

//ALL SUCCESS FROM SAME IP
$db_SuccessIP = 'SELECT sessions.ip, COUNT(sessions.ip) '
	. "FROM sessions INNER JOIN auth ON sessions.id = auth.session "
	. "WHERE auth.success = 1 "
	. "GROUP BY sessions.ip "
	. "ORDER BY COUNT(sessions.ip) DESC ";
			
	
// probes per day
$db_ProbesDay = 'SELECT COUNT(session), timestamp '
	. "FROM auth "
	. "GROUP BY DAYOFYEAR(timestamp) "
	. "ORDER BY timestamp ASC ";

// probes per week

$db_ProbesWeek = 'SELECT COUNT(session), MAKEDATE( '
	. "CASE "
	. "WHEN WEEKOFYEAR(timestamp) = 52 "
	. "THEN YEAR(timestamp)-1 "
	. "ELSE YEAR(timestamp) "
	. "END, (WEEKOFYEAR(timestamp) * 7)-4) AS DateOfWeek_Value "
	. "FROM auth "
	. "GROUP BY WEEKOFYEAR(timestamp) "
	. "ORDER BY timestamp ASC";


// top ten ssh 

$db_SSH = 'SELECT clients.version, COUNT(client) '
	. "FROM sessions INNER JOIN clients ON sessions.client = clients.id "
	. "GROUP BY sessions.client "
	. "ORDER BY COUNT(client) DESC "
	//."ORDER BY clients.version ASC"; //alphabetical sorting
	;




//activity per day
$db_ActivityDay = 'SELECT COUNT(input), timestamp '
	. "FROM input "
	. "GROUP BY DAYOFYEAR(timestamp) "
	. "ORDER BY timestamp ASC ";
	
//activity per week

$db_ActivityWeek = 'SELECT COUNT(input), MAKEDATE( '
	. "CASE "
	. "WHEN WEEKOFYEAR(timestamp) = 52 "
	. "THEN YEAR(timestamp)-1 "
	. "ELSE YEAR(timestamp) "
	. "END, (WEEKOFYEAR(timestamp) * 7)-4) AS DateOfWeek_Value "
	. "FROM input "
	. "GROUP BY WEEKOFYEAR(timestamp) "
	. "ORDER BY timestamp ASC";


//Input
$db_Input = 'SELECT input, COUNT(input) '
	. "FROM input "
	. "GROUP BY input "
	. "ORDER BY COUNT(input) DESC ";

//top ten succes input
$db_Successinput = 'SELECT input, COUNT(input) '
	. "FROM input "
	. "WHERE success = 1 "
	. "GROUP BY input "
	. "ORDER BY COUNT(input) DESC ";

//top ten failed input

$db_FailedInput = 'SELECT input, COUNT(input) '
	. "FROM input "
	. "WHERE success = 0 "
	. "GROUP BY input "
	. "ORDER BY COUNT(input) DESC ";

// password commands

$db_passwd = 'SELECT timestamp, input '
	. "FROM input "
	. "WHERE realm like 'passwd' "
	. "GROUP BY input "
	. "ORDER BY timestamp DESC";

// wget commnads

$db_wget = "SELECT input, TRIM(LEADING 'wget' FROM input) as file "
	. "FROM input "
	. "WHERE input LIKE '%wget%' AND input NOT LIKE 'wget' "
	. "ORDER BY timestamp DESC";


// executed scrtipts

$db_Scripts = 'SELECT timestamp, input '
	. "FROM input "
	. "WHERE input like './%' "
	. "GROUP BY input "
	. "ORDER BY timestamp DESC";

//intereseting commands

$db_Interesting = 'SELECT timestamp, input '
	. "FROM input "
	. "WHERE (input like '%cat%' OR input like '%dev%' OR input like '%man%' OR input like '%gpg%' OR input like '%ping%' "
	. "OR input like '%ssh%' OR input like '%scp%' OR input like '%whois%' OR input like '%unset%' OR input like '%kill%' "
	. "OR input like '%ifconfig%' OR input like '%iwconfig%' OR input like '%traceroute%' OR input like '%screen%' OR input like '%user%') "
	. "AND input NOT like '%wget%' AND input NOT like '%apt-get%' "
	. "GROUP BY input "
	. "ORDER BY timestamp DESC";

// apt-get copmmands

$db_aptget = 'SELECT timestamp, input '
	. "FROM input "
	. "WHERE (input like '%apt-get install%' OR input like '%apt-get remove%' OR input like '%aptitude install%' OR input like '%aptitude remove%') "
	. "AND input NOT LIKE 'apt-get' AND input NOT LIKE 'aptitude'"
	. "GROUP BY input "
	. "ORDER BY timestamp DESC";

			
?>