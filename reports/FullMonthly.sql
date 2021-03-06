--Start MAINTENANCE
VACUUM;
REINDEX;
ANALYZE;
pragma integrity_check;

--Delete players who havent been online in 30 Days
delete from buildable_health where object_id in (select distinct object_id from buildings where owner_id in (select id from characters where lastTimeOnline < strftime('%s', 'now', '-30 days')));
delete from building_instances where object_id in (select distinct object_id from buildings where owner_id in (select id from characters where lastTimeOnline < strftime('%s', 'now', '-30 days')));
delete from properties where object_id in (select distinct object_id from buildings where owner_id in (select id from characters where lastTimeOnline < strftime('%s', 'now', '-30 days')));
delete from actor_position where id in (select distinct object_id from buildings where owner_id in (select id from characters where lastTimeOnline < strftime('%s', 'now', '-30 days')));
delete from properties where object_id in (select id from characters where lastTimeOnline < strftime('%s', 'now', '-30 days'));
delete from buildings where owner_id in (select id from characters where lastTimeOnline < strftime('%s', 'now', '-30 days'));
delete from item_properties where owner_id in (select id from characters where lastTimeOnline < strftime('%s', 'now', '-30 days'));
delete from item_inventory where owner_id in (select id from characters where lastTimeOnline < strftime('%s', 'now', '-30 days'));
delete from actor_position where id in (select id from characters where lastTimeOnline < strftime('%s', 'now', '-30 days'));
delete from character_stats where char_id in (select id from characters where lastTimeOnline < strftime('%s', 'now', '-30 days'));
delete from characters where lastTimeOnline < strftime('%s', 'now', '-30 days');


--Bedrolls & Campfires
delete from buildable_health where object_id in (select distinct object_id from buildings where object_id in (select distinct object_id from properties where name like '%Bedroll%' or name like '%CampFire%'));
delete from building_instances where object_id in (select distinct object_id from buildings where object_id in (select distinct object_id from properties where name like '%Bedroll%' or name like '%CampFire%'));
delete from actor_position where id in (select distinct object_id from buildings where object_id in (select distinct object_id from properties where name like '%Bedroll%' or name like '%CampFire%'));
delete from item_inventory where template_id in ('12001','10001');
delete from buildings where object_id in (select distinct object_id from properties where name like '%Bedroll%' or name like '%CampFire%');
delete from properties where name like '%Bedroll%' or name like '%CampFire%';

--REMOVE NO OWNERSHIP
delete from buildable_health where object_id in (select distinct object_id from buildings where owner_id not in (select id from characters) and owner_id not in (select guildid from guilds));
delete from building_instances where object_id in (select distinct object_id from buildings where owner_id not in (select id from characters) and owner_id not in (select guildid from guilds));
delete from properties where object_id in (select distinct object_id from buildings where owner_id not in (select id from characters) and owner_id not in (select guildid from guilds));
delete from actor_position where id in (select distinct object_id from buildings where owner_id not in (select id from characters) and owner_id not in (select guildid from guilds));
delete from item_properties where owner_id in (select distinct owner_id from buildings where owner_id not in (select id from characters) and owner_id not in (select guildid from guilds));
delete from properties where object_id in (select distinct object_id from properties where name like '%Player%') and object_id not in (select id from characters) and object_id not in (select guildid from guilds);
delete from item_inventory where owner_id in (select distinct owner_id from buildings where owner_id not in (select id from characters) and owner_id not in (select guildid from guilds));
delete from buildings where owner_id not in (select id from characters) and owner_id not in (select guildid from guilds);

--14 DAY DECAY (V2.1)
update characters set lastTimeOnline = strftime('%s', 'now') where lastTimeOnline is NULL;
delete from buildable_health where object_id in (select distinct object_id from buildings where owner_id in (select id from characters where lastTimeOnline < strftime('%s', 'now', '-14 days')) and owner_id not in (select id from characters where guild in (select distinct guild from characters where lastTimeOnline > strftime('%s', 'now', '-14 days') and guild is not null)));
delete from buildable_health where object_id in (select distinct object_id from buildings where owner_id in (select guildid from guilds where guildid not in (select distinct guild from characters where lastTimeOnline > strftime('%s', 'now', '-14 days') and guild is not null)));
delete from building_instances where object_id in (select distinct object_id from buildings where owner_id in (select id from characters where lastTimeOnline < strftime('%s', 'now', '-14 days')) and owner_id not in (select id from characters where guild in (select distinct guild from characters where lastTimeOnline > strftime('%s', 'now', '-14 days') and guild is not null)));
delete from building_instances where object_id in (select distinct object_id from buildings where owner_id in (select guildid from guilds where guildid not in (select distinct guild from characters where lastTimeOnline > strftime('%s', 'now', '-14 days') and guild is not null)));
delete from properties where object_id in (select distinct object_id from buildings where owner_id in (select id from characters where lastTimeOnline < strftime('%s', 'now', '-14 days')) and owner_id not in (select id from characters where guild in (select distinct guild from characters where lastTimeOnline > strftime('%s', 'now', '-14 days') and guild is not null)));
delete from properties where object_id in (select distinct object_id from buildings where owner_id in (select guildid from guilds where guildid not in (select distinct guild from characters where lastTimeOnline > strftime('%s', 'now', '-14 days') and guild is not null)));
delete from properties where object_id in (select id from characters where lastTimeOnline < strftime('%s', 'now', '-14 days')) and object_id not in (select id from characters where guild in (select distinct guild from characters where lastTimeOnline > strftime('%s', 'now', '-14 days') and guild is not null));
delete from actor_position where id in (select distinct object_id from buildings where owner_id in (select id from characters where lastTimeOnline < strftime('%s', 'now', '-14 days')) and owner_id not in (select id from characters where guild in (select distinct guild from characters where lastTimeOnline > strftime('%s', 'now', '-14 days') and guild is not null)));
delete from actor_position where id in (select distinct object_id from buildings where owner_id in (select guildid from guilds where guildid not in (select distinct guild from characters where lastTimeOnline > strftime('%s', 'now', '-14 days') and guild is not null)));
delete from buildings where owner_id in (select id from characters where lastTimeOnline < strftime('%s', 'now', '-14 days') and guild is null);
delete from buildings where owner_id in (select guildid from guilds where guildid not in (select distinct guild from characters where lastTimeOnline > strftime('%s', 'now', '-14 days') and guild is not null));
delete from item_properties where owner_id in (select id from characters where lastTimeOnline < strftime('%s', 'now', '-14 days')) and owner_id not in (select id from characters where guild in (select guild from characters where lastTimeOnline > strftime('%s', 'now', '-14 days') and guild is not null));
delete from item_properties where owner_id in (select guildid from guilds where guildid not in (select distinct guild from characters where lastTimeOnline > strftime('%s', 'now', '-14 days') and guild is not null));
delete from item_inventory where owner_id in (select id from characters where lastTimeOnline < strftime('%s', 'now', '-14 days')) and owner_id not in (select id from characters where guild in (select guild from characters where lastTimeOnline > strftime('%s', 'now', '-14 days') and guild is not null));
delete from item_inventory where owner_id in (select guildid from guilds where guildid not in (select distinct guild from characters where lastTimeOnline > strftime('%s', 'now', '-14 days') and guild is not null));
delete from actor_position where id in (select id from characters where lastTimeOnline < strftime('%s', 'now', '-14 days') and id not in (select distinct guild from characters where lastTimeOnline > strftime('%s', 'now', '-14 days') and guild is not null));
delete from actor_position where id in (select guildid from guilds where guildid not in (select distinct guild from characters where lastTimeOnline > strftime('%s', 'now', '-14 days') and guild is not null));
delete from guilds where guildid not in (select distinct guild from characters where lastTimeOnline > strftime('%s', 'now', '-14 days') and guild is not null);
delete from character_stats where char_id in (select id from characters where lastTimeOnline < strftime('%s', 'now', '-14 days') and guild is null);
delete from character_stats where char_id in (select id from characters where lastTimeOnline < strftime('%s', 'now', '-14 days') and guild not in (select distinct guild from characters where lastTimeOnline > strftime('%s', 'now', '-14 days') and guild is not null));
delete from characters where id in (select id from characters where lastTimeOnline < strftime('%s', 'now', '-14 days') and guild is null);
delete from characters where id in (select id from characters where lastTimeOnline < strftime('%s', 'now', '-14 days') and guild not in (select distinct guild from characters where lastTimeOnline > strftime('%s', 'now', '-14 days') and guild is not null));
update actor_position set x='59939.539063', y='310979.625', z='-21411.023438' where x = '1.0' or x = '0.0' or z < '-99999.0' or z = '0.0';
insert into actor_position (class,map,id,x,y,z,sx,sy,sz,rx,ry,rz,rw) select 'BasePlayerChar_C', 'ConanSandbox', id, '-11875.3369140625','123886.0625', '-9016.935546875', '0.949999988079071', '0.949999988079071', '0.949999988079071', '1.87273170603776e-13', '1.7312404764977e-14', '0.092052161693573', '0.995754182338715' from characters where id in (select id from characters where id not in (select id from actor_position));

--End MAINTENANCE
VACUUM;
REINDEX;
ANALYZE;
pragma integrity_check;

.quit