# DB ダンプ置き場

ローカルへ流し込む例:

```bash
docker exec -i tecono-wp-db mysql -uroot -p"${MYSQL_ROOT_PASSWORD}" \
  --default-character-set=utf8mb4 "${MYSQL_DATABASE}" < db/backup.sql
```

本番（ホスト DB）:

```bash
mysql -h 127.0.0.1 -u"${MYSQL_USER}" -p "${MYSQL_DATABASE}" < db/backup.sql
```
