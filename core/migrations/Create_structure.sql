-- Таблица миграций --
CREATE TABLE IF NOT EXISTS `migrations` (
    `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `path` VARCHAR(255) NOT NULL,
    `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    primary key (id)
)
auto_increment = 1
character set UTF8MB4 
collate utf8mb4_unicode_ci;

-- Таблица планет --
CREATE TABLE IF NOT EXISTS `planets` (
    `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `rotation_period` INT(10) NOT NULL,
    `orbital_period` INT(10) NOT NULL,
    `diameter` INT(20) NOT NULL,
    `population` INT(30) NOT NULL,
    `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    primary key (id)
)
auto_increment = 1
character set UTF8MB4 
collate utf8mb4_unicode_ci;

-- Таблица жителей --
CREATE TABLE IF NOT EXISTS `peoples` (
    `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
	`height` INT(10) NOT NULL,
    `mass` INT(10) NOT NULL,
    `birth_year` VARCHAR(255) NOT NULL,
    `gender` VARCHAR(255) NOT NULL,
    `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    primary key (id)
)
auto_increment = 1
character set UTF8MB4 
collate utf8mb4_unicode_ci;

-- Таблица жители->планеты --
CREATE TABLE IF NOT EXISTS `peoples_to_planets` (
    `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `planet_id` INT(10) NOT NULL,
    `people_id` INT(10) NOT NULL,
    `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    primary key (id)
)
auto_increment = 1
character set UTF8MB4 
collate utf8mb4_unicode_ci;