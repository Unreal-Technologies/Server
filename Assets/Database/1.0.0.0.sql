drop database if exists `a-lonely-gameserver`;
create database `a-lonely-gameserver`;
use `a-lonely-gameserver`;

create table `currency`
(
    `id` int(11) not null auto_increment,
    `description-short` varchar(4) not null,
    `description-long` varchar(16) not null,
    `base-on-gold` float(13, 2) not null,
    primary key(`id`)
)Engine=InnoDB;

create table `objects`
(
    `id` int(11) not null auto_increment,
    `name` varchar(64) not null,
    `description` blob not null,
    `type` enum('Weapon', 'Gear', 'Scroll', 'Light Armor', 'Shield', 'Other') not null,
    `subtype` enum('Simple', 'Tool', 'Adventuring Gear', 'Holy Symbol', 'Leather', 'Arcane Focus') null,
    primary key(`id`)
)Engine=InnoDB;

create table `damage-types`
(
    `id` int(11) not null auto_increment,
    `description` varchar(16) not null,
    `color-override` varchar(10) null,
    primary key(`id`)
)Engine=InnoDB;

create table `object-rolls`
(
    `id` int(11) not null auto_increment,
    `object-id` int(11) not null,
    `amount` int(2) not null,
    `value` int(2) not null,
    `damage-type-id` int(11) not null,
    primary key(`id`),
    foreign key(`object-id`) references `objects`(`id`) on delete cascade,
    foreign key(`damage-type-id`) references `damage-types`(`id`) on delete restrict
)Engine=InnoDB;

create table `object-modifiers`
(
    `id` int(11) not null auto_increment,
    `object-id` int(11) not null,
    `type` enum('Damage', 'Reach', 'Range', 'Weight', 'Value', 'Finesse', 'Light', 'Thrown', 'Capacity', 'Ability', 'Craft', 'AC', 'AC+') null,
    `min` int(3) null,
    `max` int(3) null,
    primary key(`id`),
    foreign key(`object-id`) references `objects`(`id`) on delete cascade
)Engine=InnoDB;

create table `abilities`
(
    `id` int(11) not null auto_increment,
    `description` varchar(16) not null,
    primary key(`id`)
)Engine=InnoDB;

create table `object-modifiers-reference`
(
    `id` int(11) not null auto_increment,
    `object-modifier-id` int(11) not null,
    `damage-type-id` int(11) null,
    `value-type-id` int(11) null,
    `ability-id` int(11) null,
    `object-id` int(11) null,
    primary key(`id`),
    foreign key(`object-modifier-id`) references `object-modifiers`(`id`) on delete cascade,
    foreign key(`damage-type-id`) references `damage-types`(`id`) on delete restrict,
    foreign key(`value-type-id`) references `currency`(`id`) on delete restrict,
    foreign key(`ability-id`) references `abilities`(`id`) on delete restrict,
    foreign key(`object-id`) references `objects`(`id`) on delete restrict
)Engine=InnoDB;

insert into `currency`(`id`, `description-short`, `description-long`, `base-on-gold`)
values
(1, 'cp', 'Copper', 0.01),
(2, 'sp', 'Silver', 0.1),
(3, 'ep', 'Electrum', 0.5),
(4, 'gp', 'Gold', 1),
(5, 'pp', 'Platinum', 10);

insert into `abilities`(`id`, `description`)
values
(1, 'Strength'),
(2, 'Dexterity'),
(3, 'Constitution'),
(4, 'Intelligence'),
(5, 'Wisdom'),
(6, 'Charisma');

insert into `objects`(`id`, `name`, `type`, `subtype`, `description`)
values
(1, 'Dagger', 'Weapon', 'Simple', 'Proficiency with a Dagger allows you to add your proficiency bonus to the attack roll for any attack you make with it.'),
(2, 'Backpack', 'Gear', 'Adventuring Gear', 'A Backpack holds up to 30 pounds within 1 cubic foot. It can also serve as a saddlebag.'),
(3, 'Calligrapher''s Supplies', 'Gear', 'Tool', 'If you have proficiency with a tool, add your Proficiency Bonus to any ability check you make that uses the tool. If you have proficiency in a skill that’s used with that check, you have Advantage on the check too.'),
(4, 'Ink', 'Gear', 'Adventuring Gear', 'Ink comes in a 1-ounce bottle, which provides enough ink to write about 500 pages.'),
(5, 'Spell Scroll', 'Scroll', null, 'A Spell Scroll bears the words of a single spell, written in a mystical cipher. If the spell is on your spell list, you can read the scroll and cast its spell without Material components. Otherwise, the scroll is unintelligible. Casting the spell by reading the scroll requires the spell’s normal casting time. Once the spell is cast, the scroll crumbles to dust. If the casting is interrupted, the scroll isn’t lost.

If the spell is on your spell list but of a higher level than you can normally cast, you make an ability check using your spellcasting ability to determine whether you cast the spell. The DC equals 10 plus the spell’s level. On a failed check, the spell disappears from the scroll with no other effect.

Copying a Scroll into a Spellbook. A Wizard spell on a Spell Scroll can be copied into a spellbook. When a spell is copied in this way, the copier must succeed on an Intelligence (Arcana) check with a DC equal to 10 plus the spell’s level. On a successful check, the spell is copied. Whether the check succeeds or fails, the Spell Scroll is destroyed.'),
(6, 'Holy Symbol', 'Gear', 'Holy Symbol', 'A Holy Symbol takes one of the forms in the Holy Symbol table and is bejeweled or painted to channel divine magic. A Cleric or Paladin can use a Holy Symbol as a Spellcasting Focus.

The table indicates whether a Holy Symbol needs to be held, worn, or borne on fabric (such as a tabard or banner) or a Shield.'),
(7, 'Ink Pen', 'Gear', 'Adventuring Gear', 'Using Ink, an Ink Pen is used to write or draw.'),
(8, 'Leather', 'Light Armor', 'Leather', 'The breastplate and shoulder protectors of this armor are made of leather that has been stiffened by being boiled in oil. The rest of the armor is made of softer and more flexible materials.'),
(9, 'Orb', 'Gear', 'Arcane Focus', 'An Arcane Focus is bejeweled or carved to channel arcane magic. A Sorcerer, Warlock, or Wizard can use such an item as a Spellcasting Focus.'),
(10, 'Parchment', 'Gear', 'Adventuring Gear', 'One sheet of Parchment can hold about 250 handwritten words.'),
(11, 'Robe', 'Gear', 'Adventuring Gear', 'A Robe has vocational or ceremonial significance. Some events and locations admit only people wearing a Robe bearing certain colors or symbols.'),
(12, 'Shield', 'Shield', null, 'Shields require the Utilize action to Don or Doff. You gain the Armor Class benefit of a Shield only if you have training with it.'),
(13, 'Tinderbox', 'Gear', 'Adventuring Gear', 'A Tinderbox is a small container holding flint, fire steel, and tinder (usually dry cloth soaked in light oil) used to kindle a fire. Using it to light a Candle, Lamp, Lantern, or Torch—or anything else with exposed fuel—takes a Bonus Action. Lighting any other fire takes 1 minute.'),
(14, 'Jade Statue of a Frog', 'Other', null, '');

insert into `damage-types`(`id`, `description`, `color-override`)
values
(1, 'Piercing', null);


insert into `object-rolls`(`object-id`, `damage-type-id`, `amount`, `value`)
values
(1, 1, 1, 4);

insert into `object-modifiers`(`id`, `object-id`, `type`, `min`, `max`)
values
(1, 1, 'Damage', 2, 2),
(2, 1, 'Reach', 5, 5),
(3, 1, 'Range', 20, 60),
(4, 1, 'Weight', 1, 1),
(5, 1, 'Value', 2, 2),
(6, 1, 'Finesse', null, null),
(7, 1, 'Light', null, null),
(8, 1, 'Thrown', null, null),
(9, 2, 'Weight', 5, 5),
(10, 2, 'Capacity', 30, 30),
(11, 2, 'Value', 2, 2),
(12, 3, 'Weight', 5, 5),
(13, 3, 'Value', 10, 10),
(14, 3, 'Ability', null, null),
(15, 3, 'Craft', null, null),
(16, 4, 'Value', 10, 10),
(17, 6, 'Weight', 1, 1),
(18, 6, 'Value', 5, 5),
(19, 7, 'Value', 2, 2),
(20, 8, 'Weight', 10, 10),
(21, 8, 'Value', 10, 10),
(22, 8, 'AC', 11, 11),
(23, 10, 'Value', 1, 1),
(24, 11, 'Weight', 4, 4),
(25, 11, 'Value', 1, 1),
(26, 12, 'Weight', 6, 6),
(27, 12, 'AC+', 2, 2),
(28, 12, 'Value', 10, 10),
(29, 13, 'Weight', 1, 1),
(30, 13, 'Value', 1, 1),
(31, 14, 'Value', 40, 40);

insert into `object-modifiers-reference`(`object-modifier-id`, `damage-type-id`)
values
(1, 1);

insert into `object-modifiers-reference`(`object-modifier-id`, `value-type-id`)
values
(5, 4),
(11, 4),
(13, 4),
(16, 4),
(18, 4),
(19, 1),
(21, 4),
(23, 2),
(25, 4),
(28, 4),
(30, 3),
(31, 4);

insert into `object-modifiers-reference`(`object-modifier-id`, `ability-id`)
values
(14, 2);

insert into `object-modifiers-reference`(`object-modifier-id`, `object-id`)
values
(15, 4),
(15, 5);