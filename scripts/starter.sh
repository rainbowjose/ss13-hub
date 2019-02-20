#!/bin/bash

cd /home/traitor/YTgstation

case $1 in
	start)
		export LD_LIBRARY_PATH=/home/traitor/YTgstation
		: > dd.log
		screen -dmS yellow DreamDaemon tgstation.dmb -port 2019 -trusted -public -threads on -map-threads on -close -verbose -log dd.log
		;;
	stop)
		screen -X -S yellow quit
		;;
	reload)
		screen -X -S yellow quit
		export LD_LIBRARY_PATH=/home/traitor/YTgstation
		: > dd.log
		screen -dmS yellow DreamDaemon tgstation.dmb -port 2019 -trusted -public -threads on -map-threads on -close -verbose -log dd.log
		;; 
	update)
		: > u.log
		git pull > u.log &
		;;
	status)
		[ "$(pidof DreamDaemon)" ] && echo ONLINE || echo OFFLINE
		;;
	cstatus)
		[ "$(pidof DreamMaker)" ] && echo ONLINE || echo OFFLINE
		;;
	compile)
		: > compile.log
		DreamMaker tgstation.dme > compile.log &
		;;
	dlog)
		cat dd.log
		;;
	clog)
		cat compile.log
		;;
	ulog)
		cat u.log
		;;
	*)
		printf '	//ULTRA_EDGY_STARTER_MK3000//\n'
		printf 'Основные команды - start, stop, reload, update, status, cstatus, compile, dlog, clog, ulog\n'
		printf '	start - запускает сервер\n'
		printf '	stop - убивает сервер\n'
		printf '	reload - убивает и запускает сервер\n'
		printf '	update - обновляет локальный репозиторий\n'
		printf '	status - проверяет запущен ли сервер\n'
		printf '	cstatus - проверяет запущена ли компиляция\n'
		printf '	compile - компилирует сервер\n'
		printf '	dlog - выводит последний dd.log\n'
		printf '	clog - выводит последний лог компиляции\n'
		printf '	ulog - выводит последний лог обновления\n'
		;;
esac