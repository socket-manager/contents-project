# スウィープロッド
execute positioned as @s run particle minecraft:critical_hit_emitter ~ ~1 ~
execute if entity @s[hasitem={item=blaze_rod,data=501,location=slot.weapon.mainhand}] as @e[type=!player,r=5] at @s run particle minecraft:sonic_explosion ~ ~2 ~
execute if entity @s[hasitem={item=blaze_rod,data=501,location=slot.weapon.mainhand}] as @e[type=!player,r=5] at @s run damage @s 15 entity_attack
