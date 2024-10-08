CREATE TABLE `{{prefix}}categories` (
    `id`          int(6) NOT NULL AUTO_INCREMENT,
    `title`       varchar(255) NOT NULL,
    `slug`        varchar(40)  NOT NULL,
    `description` text         NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB CHARSET={{charset}};

CREATE TABLE `{{prefix}}comments` (
    `id`                int(6) NOT NULL AUTO_INCREMENT,
    `parent_comment_id` int(6) NOT NULL,
    `post`              int(6) NOT NULL,
    `status`            enum('pending','approved','spam') NOT NULL,
    `date`              datetime     NOT NULL DEFAULT current_timestamp(),
    `name`              varchar(255) NOT NULL,
    `email`             varchar(255) NOT NULL,
    `text`              text         NOT NULL,
    PRIMARY KEY (`id`),
    KEY                 `post` (`post`),
    KEY                 `status` (`status`)
) ENGINE=InnoDB CHARSET={{charset}};

CREATE TABLE `{{prefix}}likes` (
    `id`         int(6) NOT NULL AUTO_INCREMENT,
    `post`       int(6) NOT NULL,
    `visitor_ip` varchar(255) NOT NULL,
    `created_at` datetime     NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB CHARSET={{charset}};

CREATE TABLE `{{prefix}}meta` (
    `key`   varchar(140) NOT NULL,
    `value` text         NOT NULL,
    PRIMARY KEY (`key`)
) ENGINE=InnoDB CHARSET={{charset}};

CREATE TABLE `{{prefix}}notifications` (
    `id`         int(11) NOT NULL AUTO_INCREMENT,
    `user_id`    int(11) NOT NULL,
    `type`       enum('comment','post','like','subscriber') NOT NULL,
    `related_id` int(11) NOT NULL,
    `is_read`    tinyint(1) DEFAULT 0,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    KEY          `user_id` (`user_id`),
    KEY          `type` (`type`)
) ENGINE=InnoDB CHARSET={{charset}};

CREATE TABLE `{{prefix}}pages` (
    `id`             int(6) NOT NULL AUTO_INCREMENT,
    `slug`           varchar(150) NOT NULL,
    `name`           varchar(255) NOT NULL,
    `title`          varchar(255) NOT NULL,
    `description`    longtext     NOT NULL,
    `status`         enum('draft','published','archived') NOT NULL,
    `redirect`       text         NOT NULL,
    `show_in_footer` tinyint(1) NOT NULL,
    `footer_order`   int(4) NOT NULL DEFAULT 0,
    `show_in_menu`   tinyint(1) NOT NULL,
    `menu_order`     int(4) NOT NULL DEFAULT 0,
    `updated`        datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`),
    KEY              `status` (`status`),
    KEY              `slug` (`slug`)
) ENGINE=InnoDB CHARSET={{charset}};

CREATE TABLE `{{prefix}}posts` (
    `id`               int(6) NOT NULL AUTO_INCREMENT,
    `title`            varchar(255) NOT NULL,
    `slug`             varchar(150) NOT NULL,
    `description`      longtext     NOT NULL,
    `meta_description` longtext     NOT NULL,
    `image`            varchar(250) NOT NULL,
    `videolink`        varchar(256) NOT NULL,
    `imagelink`        varchar(256) NOT NULL,
    `created`          datetime     NOT NULL DEFAULT current_timestamp(),
    `updated`          datetime              DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    `author`           int(6) NOT NULL,
    `category`         int(6) NOT NULL,
    `status`           enum('draft','published','archived') NOT NULL,
    `comments`         tinyint(1) DEFAULT 0,
    PRIMARY KEY (`id`),
    KEY                `status` (`status`),
    KEY                `slug` (`slug`)
) ENGINE=InnoDB CHARSET={{charset}};

CREATE TABLE `{{prefix}}sessions` (
    `id`     int(6) NOT NULL AUTO_INCREMENT,
    `expire` int(10) NOT NULL,
    `data`   text NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB CHARSET={{charset}};

CREATE TABLE `{{prefix}}subscribers` (
    `id`         int(6) NOT NULL AUTO_INCREMENT,
    `email`      varchar(255) NOT NULL,
    `created_at` timestamp    NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB CHARSET={{charset}};

CREATE TABLE `{{prefix}}users` (
    `id`         int(6) NOT NULL AUTO_INCREMENT,
    `username`   varchar(100) NOT NULL,
    `password`   text         NOT NULL,
    `email`      varchar(140) NOT NULL,
    `real_name`  varchar(140) NOT NULL,
    `image`      varchar(250) NOT NULL,
    `bio`        text         NOT NULL,
    `status`     enum('inactive','active') NOT NULL,
    `role`       enum('administrator','demo','editor','contributor') NOT NULL,
    `created_at` datetime     NOT NULL DEFAULT current_timestamp(),
    `updated`    datetime              DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB CHARSET={{charset}};

CREATE TABLE `{{prefix}}views` (
    `id`         int(6) NOT NULL AUTO_INCREMENT,
    `post`       int(6) NOT NULL,
    `visitor_ip` varchar(255) NOT NULL,
    `created_at` datetime     NOT NULL DEFAULT current_timestamp(),
    `updated_at` datetime              DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB CHARSET={{charset}};

CREATE TABLE `{{prefix}}visitors` (
    `id`         int(6) NOT NULL AUTO_INCREMENT,
    `visitor_ip` varchar(255) NOT NULL,
    `referer`    varchar(255) NOT NULL,
    `created_at` datetime     NOT NULL DEFAULT current_timestamp(),
    `updated_at` datetime              DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB CHARSET={{charset}};

INSERT INTO `{{prefix}}categories` (`title`, `slug`, `description`)
VALUES ('Uncategorised', 'uncategorised', 'Ain\'t no category here.');

INSERT INTO `{{prefix}}meta` (`key`, `value`) VALUES
('admin_theme', 'auto'),
('auto_published_comments', '0'),
('comment_moderation_keys', ''),
('comment_notifications', '0'),
('cookie', '0'),
('cookie_policy_page', ''),
('dashboard_page', 'panel'),
('date_format', 'jS M, Y'),
('facebook', ''),
('instagram', ''),
('linkedin', ''),
('maintenance', '0'),
('pinterest', ''),
('posts_page',  '1'),
('posts_per_page',  '10'),
('show_all_posts', ''),
('tumblr', ''),
('twitter', ''),
('vkontakte', ''),
('youtube', '');

INSERT INTO `{{prefix}}pages` (`slug`, `name`, `title`, `description`, `status`, `redirect`, `show_in_menu`, `menu_order`) VALUES
('posts', 'Posts', 'My posts and thoughts', 'Welcome!', 'published', '', '1', '0');

INSERT INTO `{{prefix}}posts` (`title`, `slug`, `description`, `created`,  `author`, `category`, `status`, `comments`) VALUES
('Hello World', 'hello-world', '<p>This is the first post.</p>', '{{now}}', '1', '1', 'published', '1');



