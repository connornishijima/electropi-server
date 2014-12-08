from crontab import CronTab

cron = CronTab()

print "Adding cronjob to run ElectroPi command watchdog in background @reboot..."
watchdog = cron.new(command='sudo cpulimit -l 40 python /var/www/control_watch.py', comment='ElectroPi Command Watchdog')
watchdog.every_reboot()

print "Adding cronjob to run ElectroPi command watchdog in background @reboot..."
client = cron.new(command='sudo cpulimit -l 80 python /var/www/client_watch.py', comment='ElectroPi Client Watchdog')
client.every_reboot()

print "Adding cronjob for daily 3 AM update checks..."
update = cron.new(command='sudo python /var/www/conf/update.py', comment='ElectroPi Update Check')
update.hour.on(3)
update.minute.on(0)

print "Adding cronjob for daily 4 AM reboots..."
reboot = cron.new(command='sudo reboot', comment='ElectroPi Daily Reboot')
reboot.hour.on(4)
reboot.minute.on(0)

print "Writing changes to crontab..."
cron.write()

print "...done."
