# 不動の杖
execute unless entity @s[hasitem={item=customize:immovable_rod,location=slot.weapon.mainhand}] run loot replace entity @s slot.weapon.mainhand 1 loot immovable_rod
execute positioned as @s run particle minecraft:critical_hit_emitter ~ ~2 ~
execute as @e[family=mob,r=10] at @s run summon customize:immovable
execute as @e[family=mob,r=10] at @s run particle minecraft:sonic_explosion ~ ~1 ~
