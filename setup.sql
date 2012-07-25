CREATE TABLE IF NOT EXISTS `Fascel_releases` (
  `id` int(5) NOT NULL,
  `version` text COLLATE utf8_unicode_ci NOT NULL,
  `codename` text COLLATE utf8_unicode_ci NOT NULL,
  `ts` int(10) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `Fascel_changes` (
  `id` int(5) NOT NULL,
  `type` int(2) NOT NULL,
  `change` text COLLATE utf8_unicode_ci NOT NULL,
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
