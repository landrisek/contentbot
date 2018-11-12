CREATE TABLE `keywords` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `content` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;
INSERT INTO `keywords` (`id`, `name`, `content`) VALUES
(1, 'dámský', '[\"dámskou\",\"dámským\",\"dámské\",\"dámská\",\"dámský\"]'),
(2, 'pánský', '[\"pánskou\",\"pánským\",\"pánské\",\"pánská\",\"pánský\"]'),
(3, 'dětský', '[\"dětskou\",\"dětským\",\"dětské\",\"dětská\",\"dětský\"]'),
(4, 'pohodlí',  '[\"pohodlnou\",\"pohodlným\",\"pohodlné\",\"pohodlná\",\"pohodlný\",\"pohodlí\",\"pohodlnou\",\"pohodlně\", \"pohodlí\"]'),
(5, 'sucho',  '[\"sucha\",\"sucho\",\"suché\",\"suchá\",\"suchý\"]'),
(6, 'mokro',  '[\"mokra\",\"mokro\",\"mokré\",\"mokrá\",\"mokrý\"]'),
(7, 'boty', '[\"boty\",\"bota\"]'),
(8, 'sandále',  '[\"sandále\"]'),
(9, 'těžký',  '[\"těžko\",\"těžké\",\"těžká\",\"těžký\",\"těžkého\",\"těžkou\"]'),
(10,  'lehký',  '[\"lehko\",\"lehké\",\"lehká\",\"lehký\",\"lehkého\",\"lehkou\"]'),
(11,  'terén',  '[\"terén\",\"terénu\",\"terénní\",\"terénná\",\"terénneho\",\"terénne\"]'),
(12,  'město',  '[\"město\",\"města\",\"městský\",\"městská\",\"městského\",\"městské\"]'),
(13,  'objem',  '[\"objem\", \"objemový\", \"objemová\", \"objemové\"]'),
(14,  'lyže', '[\"lyže\", \"lyžařský\", \"lyžařská\", \"lyžařské\"]'),
(15,  'kotník', '[\"kotník\",\"kotníkový\",\"kotníková\",\"kotníkové\"]'),
(16,  'muž',  '[\"muž\",\"muže\",\"muži\",\"mužovi\"]'),
(17,  'žena', '[\"žena\",\"ženě\",\"ženy\",\"ženám\",\"ženu\"]'),
(18,  'dítě', '[\"dítě\",\"ditěti\",\"děti\",\"dětech\"]'),
(19,  'dívka',  '[\"dívka\",\"dívce\",\"dívky\"]'),
(20,  'kluk', '[\"kluk\",\"klukovi\",\"kluci\"]'),
(21,  'aktivní',  '[\"aktívnou\",\"aktívným\",\"aktívne\",\"aktívna\",\"aktívny\"]'),
(43,  'kvalita',  '[\"kvalitnou\",\"kvalitným\",\"kvalitné\",\"kvalitná\",\"kvalitný\",\"kvalita\",\"kvalitou\",\"kvalitně\", \"kvalitě\"]'),
(45,  'špička', '[\"špičkovou\",\"špičkovým\",\"špičkové\",\"špičková\",\"špičkový\",\"špička\",\"špičkou\",\"špičkově\", \"špičce\"]'),
(46,  'luxus',  '[\"luxusnou\",\"luxusným\",\"luxusné\",\"luxusná\",\"luxusný\",\"luxus\",\"luxusnou\",\"luxusně\", \"luxusu\"]'),
(47,  'odolnost', '[\"odolnou\",\"odolným\",\"odolné\",\"odolná\",\"odolný\",\"odolnost\",\"odolností\",\"odolně\", \"odolnosti\"]'),
(48,  'voděodolnost', '[\"voděodolnou\",\"voděodolným\",\"voděodolné\",\"voděodolná\",\"voděodolný\",\"voděodolnost\",\"voděodolností\",\"voděodolně\", \"voděodolnosti\"]'),
(49,  'náročnost',  '[\"náročně\",\"náročné\",\"náročná\",\"náročný\",\"náročného\",\"náročnou\",\"náročným\",\"náročném\"]'),
(50,  'nerovnost',  '[\"nerovnou\",\"nerovným\",\"nerovné\",\"nerovná\",\"nerovný\",\"nerovnost\",\"nerovností\",\"nerovně\", \"nerovnosti\",\"nerovnostmi\",\"nerovném\"]'),
(51,  'drobonost',  '[\"drobně\",\"drobné\",\"drobná\",\"drobný\",\"drobného\",\"drobnou\",\"drobným\"]'),
(52,  'mírně',  '[\"mírně\",\"mírné\",\"mírná\",\"mírný\",\"mírneho\",\"mírnou\",\"mírným\"]');
CREATE TABLE IF NOT EXISTS `write` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_czech_ci NOT NULL,
  `content` text CHARACTER SET utf8 COLLATE utf8_czech_ci COMMENT '@hidden@cke3',
  `language` enum('en_GB','sk_SK','cs_CZ') CHARACTER SET utf8 COLLATE utf8_czech_ci NOT NULL DEFAULT 'cs_CZ' COMMENT '@hidden',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;
INSERT INTO `write` (`id`, `name`, `content`, `language`) VALUES
(1, 'keyword', '{"0":{"0":"option1","1":"option2","2":"option3","type":"select"},"2":"test text","plain":"","select":""}\n', 'cs_CZ'),
COMMIT;