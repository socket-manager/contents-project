# スタンド攻撃相手のタグ付与
execute as @e[type=arrow,tag=stand_attack,c=1] at @e[type=arrow,tag=stand_attack,r=5,c=1] run tag @e[family=mob,tag=!stand_attack,c=1] add stand_attack
kill @e[type=arrow,tag=stand_attack,c=1]
