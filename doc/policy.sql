CREATE TABLE `policy` (
  `password_force_random` tinyint(1) DEFAULT NULL,
  `password_length_minimum` tinyint(2) DEFAULT NULL,
  `password_length_maximum` tinyint(2) DEFAULT NULL,
  `password_special_characters` tinyint(1) DEFAULT NULL,
  `password_numeric_digits` tinyint(1) DEFAULT NULL,
  `password_upper_lower_characters` tinyint(1) DEFAULT NULL,
  `password_expiry` tinyint(1) DEFAULT NULL,
  `password_past_passwords` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci


insert into `policy`
(
  `password_force_random`,
  `password_length_minimum`,
  `password_length_maximum`,
  `password_special_characters`,
  `password_numeric_digits`,
  `password_upper_lower_characters`,
  `password_expiry`,
  `password_past_passwords`
)
values
(NULL,8,NULL,1,1,1,60,2);