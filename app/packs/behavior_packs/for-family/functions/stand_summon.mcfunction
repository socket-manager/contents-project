# スタンド召喚
tag @e[type=arrow,tag=,c=1] add stand_summon
execute at @e[type=arrow,tag=stand_summon,c=1] run agent tp ~ ~ ~
kill @e[type=arrow,tag=stand_summon,c=1]
