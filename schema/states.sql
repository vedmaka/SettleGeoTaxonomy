CREATE TABLE /*_*/sgt_states (
  id INT NOT NULL AUTO_INCREMENT,
  code VARCHAR(255) NOT NULL,
  code_iso VARCHAR(255) NOT NULL,
  code_fips VARCHAR(255) NOT NULL,
  code_geonames VARCHAR(255) NOT NULL,
  short_name VARCHAR(255) NOT NULL,
  name VARCHAR(255) NOT NULL,
  long_name VARCHAR(255) DEFAULT NULL,
  parent_id INT NOT NULL,
  PRIMARY KEY (id),
  UNIQUE INDEX code (code),
  UNIQUE INDEX code_iso (code_iso),
  UNIQUE INDEX code_fips (code_fips),
  UNIQUE INDEX code_geonames (code_fips),
  UNIQUE INDEX parent_id (parent_id),
  UNIQUE INDEX code_parent_id (code, parent_id),
  UNIQUE INDEX code_geonames_parent_id (code_geonames, parent_id),
  UNIQUE INDEX name (name),
  UNIQUE INDEX short_name (short_name)
) /*$wgDbTableOptions*/;