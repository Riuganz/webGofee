@echo off
cd /d c:\xampp\htdocs\webGofee
echo Starting at %date% %time% >> comp_log.txt
start /b cmd /c "composer install --no-interaction --prefer-dist --no-dev --no-scripts >> comp_log.txt 2>&1 && echo DONE >> comp_log.txt"
echo Launched at %date% %time% >> comp_log.txt