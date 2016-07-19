CREATE TABLE /*_*/sgt_geo_index (
  body TEXT NOT NULL,
  type VARCHAR(50) DEFAULT NULL,
  code VARCHAR(50) DEFAULT NULL,
  parent_id INT DEFAULT NULL,
  code_geonames VARCHAR(255) DEFAULT NULL,
  name VARCHAR(255) NOT NULL,
  suffix VARCHAR(255) DEFAULT '',
  FULLTEXT INDEX IDX_sgt_geo_index_body (body),
  FULLTEXT INDEX IDX_sgt_geo_index3 (body, code_geonames)
) ENGINE = INNODB
AVG_ROW_LENGTH = 207
CHARACTER SET utf8
COLLATE utf8_general_ci;