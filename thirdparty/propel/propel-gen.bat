@ECHO OFF

:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
:: The propel-gen build script for Windows based systems
:: $Id: pear-propel-gen.bat,v 1.2 2004/10/17 13:24:09 hlellelid Exp $
:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

::----------------------------------------------------------------------------------
:: Please set following to the "phing" script.  By default this is expected to be
:: on your path.  (You don't need to modify this file if that is the case.)
  
  SET phingScript=phing

::---------------------------------------------------------------------------------
::---------------------------------------------------------------------------------
:: Do not modify below this line!! (Unless you know what your doing :)
::---------------------------------------------------------------------------------
::---------------------------------------------------------------------------------

"%phingScript%" -f "C:\Program Files\Apache Software Foundation\Apache2.2\htdocs\pear\PEAR\data\propel_generator\pear-build.xml" -Dproject.dir=%*
GOTO :EOF

:PAUSE_END
PAUSE