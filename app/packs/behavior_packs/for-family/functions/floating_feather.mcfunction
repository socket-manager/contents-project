# 浮遊の羽
effect @s levitation 3 1 true
execute unless entity @s[hasitem={item=customize:floating_feather,location=slot.weapon.mainhand}] run loot replace entity @s slot.weapon.mainhand 1 loot floating_feather
