@echo off
rem START or STOP Services
rem ----------------------------------
rem Check if argument is STOP or START

if not ""%1"" == ""START"" goto stop

if exist C:\custom-xampp\hypersonic\scripts\ctl.bat (start /MIN /B C:\custom-xampp\server\hsql-sample-database\scripts\ctl.bat START)
if exist C:\custom-xampp\ingres\scripts\ctl.bat (start /MIN /B C:\custom-xampp\ingres\scripts\ctl.bat START)
if exist C:\custom-xampp\mysql\scripts\ctl.bat (start /MIN /B C:\custom-xampp\mysql\scripts\ctl.bat START)
if exist C:\custom-xampp\postgresql\scripts\ctl.bat (start /MIN /B C:\custom-xampp\postgresql\scripts\ctl.bat START)
if exist C:\custom-xampp\apache\scripts\ctl.bat (start /MIN /B C:\custom-xampp\apache\scripts\ctl.bat START)
if exist C:\custom-xampp\openoffice\scripts\ctl.bat (start /MIN /B C:\custom-xampp\openoffice\scripts\ctl.bat START)
if exist C:\custom-xampp\apache-tomcat\scripts\ctl.bat (start /MIN /B C:\custom-xampp\apache-tomcat\scripts\ctl.bat START)
if exist C:\custom-xampp\resin\scripts\ctl.bat (start /MIN /B C:\custom-xampp\resin\scripts\ctl.bat START)
if exist C:\custom-xampp\jetty\scripts\ctl.bat (start /MIN /B C:\custom-xampp\jetty\scripts\ctl.bat START)
if exist C:\custom-xampp\subversion\scripts\ctl.bat (start /MIN /B C:\custom-xampp\subversion\scripts\ctl.bat START)
rem RUBY_APPLICATION_START
if exist C:\custom-xampp\lucene\scripts\ctl.bat (start /MIN /B C:\custom-xampp\lucene\scripts\ctl.bat START)
if exist C:\custom-xampp\third_application\scripts\ctl.bat (start /MIN /B C:\custom-xampp\third_application\scripts\ctl.bat START)
goto end

:stop
echo "Stopping services ..."
if exist C:\custom-xampp\third_application\scripts\ctl.bat (start /MIN /B C:\custom-xampp\third_application\scripts\ctl.bat STOP)
if exist C:\custom-xampp\lucene\scripts\ctl.bat (start /MIN /B C:\custom-xampp\lucene\scripts\ctl.bat STOP)
rem RUBY_APPLICATION_STOP
if exist C:\custom-xampp\subversion\scripts\ctl.bat (start /MIN /B C:\custom-xampp\subversion\scripts\ctl.bat STOP)
if exist C:\custom-xampp\jetty\scripts\ctl.bat (start /MIN /B C:\custom-xampp\jetty\scripts\ctl.bat STOP)
if exist C:\custom-xampp\hypersonic\scripts\ctl.bat (start /MIN /B C:\custom-xampp\server\hsql-sample-database\scripts\ctl.bat STOP)
if exist C:\custom-xampp\resin\scripts\ctl.bat (start /MIN /B C:\custom-xampp\resin\scripts\ctl.bat STOP)
if exist C:\custom-xampp\apache-tomcat\scripts\ctl.bat (start /MIN /B /WAIT C:\custom-xampp\apache-tomcat\scripts\ctl.bat STOP)
if exist C:\custom-xampp\openoffice\scripts\ctl.bat (start /MIN /B C:\custom-xampp\openoffice\scripts\ctl.bat STOP)
if exist C:\custom-xampp\apache\scripts\ctl.bat (start /MIN /B C:\custom-xampp\apache\scripts\ctl.bat STOP)
if exist C:\custom-xampp\ingres\scripts\ctl.bat (start /MIN /B C:\custom-xampp\ingres\scripts\ctl.bat STOP)
if exist C:\custom-xampp\mysql\scripts\ctl.bat (start /MIN /B C:\custom-xampp\mysql\scripts\ctl.bat STOP)
if exist C:\custom-xampp\postgresql\scripts\ctl.bat (start /MIN /B C:\custom-xampp\postgresql\scripts\ctl.bat STOP)

:end

