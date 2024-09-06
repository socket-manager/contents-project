# customize:sit召喚
summon customize:sit ~ ~-2 ~ ~
execute unless entity @s[hasitem={item=customize:immovable_stone,location=slot.weapon.mainhand}] run loot replace entity @s slot.weapon.mainhand 1 loot immovable_stone
