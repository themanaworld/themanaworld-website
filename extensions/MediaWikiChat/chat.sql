CREATE TABLE /*_*/chat (
  chat_user_id int(10),
  chat_message text,
  chat_type int(4),
  chat_timestamp bigint NOT NULL PRIMARY KEY,
  chat_to_id int(10)
) /*$wgDBTableOptions*/;