#!/usr/bin/env python
# -*- coding: utf-8 -*-
# written by qwaszx000 and maxsc
import re
import os
import fnmatch
import sys

id = str(sys.argv[1])
idg = str(int(id)-1)
ridg = "round-"+idg
rid = "round-"+id

def find(pattern, path):
    result = []
    for root, dirs, files in os.walk(path):
        for name in dirs:
            if fnmatch.fnmatch(name, pattern):
                result.append(os.path.join(root, name))
    return result

log_file_dir = find(rid, 'logs')
log_file_dir_g = find(ridg, 'logs')
l1 = len(log_file_dir)
if l1 != 1:
    sys.exit
else:
    ridp = rid+"/"
    log_file_dir = "".join(log_file_dir)
    log_file_dir_g = "".join(log_file_dir_g)
    log_file_path = log_file_dir_g+"/game.log"
    log_file_pathh = log_file_dir+"/hrefs.log"
    log_new_f = log_file_path.split("ga")
    log_new_f1 = log_new_f[0]
    log_new_f2 = log_new_f1.split("logs/")
    log_new_f3 = log_new_f2[1]
    log_new_path = "logs/new/"+ridp
    game_new_path = "logs/new/"+ridp+"log.txt"
    hrefs_new_path = "logs/new/"+ridp+"hrefs.txt"
    os.makedirs(os.path.dirname(game_new_path), exist_ok=True)
    regex = r'from\s\d+.\d+.\d+.\d+.\d+\s'
    regex1 = r'"ip":".*}'
    
    match_list = []
    if os.path.exists(log_file_path):
        log_newg = open(game_new_path, 'w',)
        with open(log_file_path, "r") as file:
            for line in file:
                new = re.sub(regex, 'from xyu-pizda', line)
                log_newg.write(new)
        log_newg.close()

    if os.path.exists(log_file_pathh):
        log_newh = open(hrefs_new_path, 'w',)
        with open(log_file_pathh, "r") as file:
            for line in file:
                new = re.sub(regex1, 'ip;cid:xyu-pizda}', line)
                log_newh.write(new)
        log_newh.close()

    def hc(name):
        if os.path.exists(log_file_dir+"/"+name+".html"):
            a = open(log_file_dir+"/"+name+".html", "r", errors='ignore')
            a = a.readlines()
            na = open (log_new_path+"/"+name+".html", "w")
            na.writelines(a)

    def lc(name):
        if os.path.exists(log_file_dir+"/"+name+".log"):
            a = open(log_file_dir+"/"+name+".log", "r", errors='ignore')
            a = a.readlines()
            na = open (log_new_path+"/"+name+".txt", "w")
            na.writelines(a)

    def jc(name):
        if os.path.exists(log_file_dir+"/"+name+".json"):
            a = open(log_file_dir+"/"+name+".json", "r", errors='ignore')
            a = a.readlines()
            na = open (log_new_path+"/"+name+".txt", "w")
            na.writelines(a)
    
    hc("atmos")
    lc("attack")
    hc("cargo")
    lc("config_error")
    lc("dd")
    hc("gravity")
    hc("hallucinations")
    lc("initialize")
    lc("job_debug")
    lc("manifest")
    jc("newscaster")
    lc("overlay")
    lc("pda")
    lc("qdel")
    jc("round_end_data")
    lc("runtime")
    hc("singulo")
    hc("supermatter")
    lc("telecomms")


    byond_enc = ["1040","1072","1041","1073",
	"1042","1074","1043","1075",
	"1044", "1076","1045","1077",
	"1046","1078","1047","1079",
	"1048", "1080","1049","1081",
	"1050","1082","1051","1083",
	"1052","1084","1053","1085",
	"1054", "1086","1055","1087",
	"1056","1088","1057","1089",
	"1058","1090","1059","1091",
	"1060","1092","1061","1093",
	"1062","1094","1063","1095",
	"1064","1096","1065","1097",
	"1066","1098","1067","1099",
	"1068", "1100","1069","1101",
	"1070", "1102","1071","1103",
	"1025", "1105"]

    byond_dec = ["А","а","Б","б","В","в","Г","г","Д","д","Е","е",
             "Ж","ж","З","з","И","и","Й","й","К","к","Л","л","М",
             "м","Н","н","О","о","П","п","Р","р","С","с","Т","т",
             "У","у","Ф","ф","Х","х","Ц","ц","Ч","ч","Ш","ш","Щ",
             "щ","Ъ","ъ","Ы","ы","Ь","ь","Э","э","Ю","ю","Я","я",
             "Ё","ё"]

    def conv_log(path):
        f = open(path)
        buf = f.read()
        f.close()
        for i in range(len(byond_enc)):
            buf = buf.replace("&#"+byond_enc[i]+";", byond_dec[i])
        #print(buf)
        f = open(path, 'w')
        f.write(buf)
        f.close()

    #path = os.getcwd()
    #f_list = os.listdir(path)
    #dir_list = list(filter(lambda x: x.rfind('.')==-1, f_list))#Получаем все директории с помощью... МАГИИ
    p = str(sys.argv[1])
    for root, dirs, files in os.walk(log_new_path):
        for log_file in files:
            conv_log("logs/new/"+ridp+log_file)