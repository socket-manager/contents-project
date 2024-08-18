# いなずまの剣改
execute if entity @s[hasitem={item=customize:thunder_sword_revised,location=slot.weapon.mainhand}] run hud @s hide status_effects
execute if entity @s[hasitem={item=customize:thunder_sword_revised,location=slot.weapon.mainhand}] run effect @s resistance 2 4 true
execute if entity @s[hasitem={item=customize:thunder_sword_revised,location=slot.weapon.mainhand}] as @e[type=!player,r=10] at @s run summon lightning_bolt ~ ~ ~
