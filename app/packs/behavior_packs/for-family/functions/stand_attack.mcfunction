# スタンド攻撃
execute as @s[r=10] if entity @e[family=mob,tag=stand_attack,r=10,c=1] at @e[family=mob,tag=stand_attack,r=10,c=1] run agent tp ~ ~-1 ~
execute if entity @e[family=mob,tag=stand_attack,r=10,c=1] run agent attack up
