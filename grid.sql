CREATE TABLE `errors` (
  `action` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `error` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `count` tinyint(3) UNSIGNED NOT NULL,
  PRIMARY KEY (`action`,`error`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `latest` (
  `source` varchar(22) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
  `value` decimal(7,2) NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`source`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `latest_half_hours` (
  `source` varchar(22) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
  `value` decimal(7,2) NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`source`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `past_days` (
  `time` datetime NOT NULL,
  `embedded_wind` decimal(4,2) UNSIGNED NOT NULL,
  `embedded_solar` decimal(4,2) UNSIGNED NOT NULL,
  `pumped_storage_pumping` decimal(4,2) NOT NULL,
  `coal` decimal(4,2) UNSIGNED NOT NULL,
  `ccgt` decimal(4,2) UNSIGNED NOT NULL,
  `ocgt` decimal(4,2) UNSIGNED NOT NULL,
  `nuclear` decimal(4,2) UNSIGNED NOT NULL,
  `oil` decimal(4,2) UNSIGNED NOT NULL,
  `wind` decimal(4,2) UNSIGNED NOT NULL,
  `hydro` decimal(4,2) UNSIGNED NOT NULL,
  `pumped` decimal(4,2) UNSIGNED NOT NULL,
  `biomass` decimal(4,2) UNSIGNED NOT NULL,
  `other` decimal(4,2) UNSIGNED NOT NULL,
  `ifa` decimal(3,2) NOT NULL,
  `moyle` decimal(3,2) NOT NULL,
  `britned` decimal(3,2) NOT NULL,
  `ewic` decimal(3,2) NOT NULL,
  `nemo` decimal(3,2) NOT NULL,
  `ifa2` decimal(3,2) NOT NULL,
  `nsl` decimal(3,2) NOT NULL,
  `eleclink` decimal(3,2) NOT NULL,
  `price` decimal(7,2) NOT NULL,
  `emissions` smallint(5) UNSIGNED NOT NULL,
  PRIMARY KEY (`time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `past_half_hours` (
  `time` datetime NOT NULL,
  `embedded_wind` decimal(4,2) UNSIGNED NOT NULL,
  `embedded_solar` decimal(4,2) UNSIGNED NOT NULL,
  `pumped_storage_pumping` decimal(4,2) NOT NULL,
  `coal` decimal(4,2) UNSIGNED NOT NULL,
  `ccgt` decimal(4,2) UNSIGNED NOT NULL,
  `ocgt` decimal(4,2) UNSIGNED NOT NULL,
  `nuclear` decimal(4,2) UNSIGNED NOT NULL,
  `oil` decimal(4,2) UNSIGNED NOT NULL,
  `wind` decimal(4,2) UNSIGNED NOT NULL,
  `hydro` decimal(4,2) UNSIGNED NOT NULL,
  `pumped` decimal(4,2) UNSIGNED NOT NULL,
  `biomass` decimal(4,2) UNSIGNED NOT NULL,
  `other` decimal(4,2) UNSIGNED NOT NULL,
  `ifa` decimal(3,2) NOT NULL,
  `moyle` decimal(3,2) NOT NULL,
  `britned` decimal(3,2) NOT NULL,
  `ewic` decimal(3,2) NOT NULL,
  `nemo` decimal(3,2) NOT NULL,
  `ifa2` decimal(3,2) NOT NULL,
  `nsl` decimal(3,2) NOT NULL,
  `eleclink` decimal(3,2) NOT NULL,
  `price` decimal(7,2) NOT NULL,
  `emissions` smallint(5) UNSIGNED NOT NULL,
  PRIMARY KEY (`time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `past_weeks` (
  `time` datetime NOT NULL,
  `embedded_wind` decimal(4,2) UNSIGNED NOT NULL,
  `embedded_solar` decimal(4,2) UNSIGNED NOT NULL,
  `pumped_storage_pumping` decimal(4,2) NOT NULL,
  `coal` decimal(4,2) UNSIGNED NOT NULL,
  `ccgt` decimal(4,2) UNSIGNED NOT NULL,
  `ocgt` decimal(4,2) UNSIGNED NOT NULL,
  `nuclear` decimal(4,2) UNSIGNED NOT NULL,
  `oil` decimal(4,2) UNSIGNED NOT NULL,
  `wind` decimal(4,2) UNSIGNED NOT NULL,
  `hydro` decimal(4,2) UNSIGNED NOT NULL,
  `pumped` decimal(4,2) UNSIGNED NOT NULL,
  `biomass` decimal(4,2) UNSIGNED NOT NULL,
  `other` decimal(4,2) UNSIGNED NOT NULL,
  `ifa` decimal(3,2) NOT NULL,
  `moyle` decimal(3,2) NOT NULL,
  `britned` decimal(3,2) NOT NULL,
  `ewic` decimal(3,2) NOT NULL,
  `nemo` decimal(3,2) NOT NULL,
  `ifa2` decimal(3,2) NOT NULL,
  `nsl` decimal(3,2) NOT NULL,
  `eleclink` decimal(3,2) NOT NULL,
  `price` decimal(7,2) NOT NULL,
  `emissions` smallint(5) UNSIGNED NOT NULL,
  PRIMARY KEY (`time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `past_years` (
  `time` datetime NOT NULL,
  `embedded_wind` decimal(4,2) UNSIGNED NOT NULL,
  `embedded_solar` decimal(4,2) UNSIGNED NOT NULL,
  `pumped_storage_pumping` decimal(4,2) NOT NULL,
  `coal` decimal(4,2) UNSIGNED NOT NULL,
  `ccgt` decimal(4,2) UNSIGNED NOT NULL,
  `ocgt` decimal(4,2) UNSIGNED NOT NULL,
  `nuclear` decimal(4,2) UNSIGNED NOT NULL,
  `oil` decimal(4,2) UNSIGNED NOT NULL,
  `wind` decimal(4,2) UNSIGNED NOT NULL,
  `hydro` decimal(4,2) UNSIGNED NOT NULL,
  `pumped` decimal(4,2) UNSIGNED NOT NULL,
  `biomass` decimal(4,2) UNSIGNED NOT NULL,
  `other` decimal(4,2) UNSIGNED NOT NULL,
  `ifa` decimal(3,2) NOT NULL,
  `moyle` decimal(3,2) NOT NULL,
  `britned` decimal(3,2) NOT NULL,
  `ewic` decimal(3,2) NOT NULL,
  `nemo` decimal(3,2) NOT NULL,
  `ifa2` decimal(3,2) NOT NULL,
  `nsl` decimal(3,2) NOT NULL,
  `eleclink` decimal(3,2) NOT NULL,
  `price` decimal(7,2) NOT NULL,
  `emissions` smallint(5) UNSIGNED NOT NULL,
  PRIMARY KEY (`time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `wind_records` (
  `value` decimal(4,2) UNSIGNED NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`value`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
