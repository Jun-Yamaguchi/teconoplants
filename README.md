# Tecono WordPress（Docker）

WordPress を Docker で運用するプロジェクトです。  
[1stlink Redmine](https://github.com/1st-link/1stlink_redmine) / [PlaPro](https://github.com/) と同じ方針です。

| 環境 | Compose | DB |
|------|---------|-----|
| ローカル | `docker-compose.yml` | MySQL コンテナ |
| 本番 | `docker-compose.prod.yml` | ホスト MariaDB/MySQL（`db-host` → `172.17.0.1`） |

本番では **アプリのみ Docker**、**httpd（Apache）が `127.0.0.1:8081` へリバースプロキシ** します。

---

## ローカル開発

### 初回

```bash
cp .env.example .env
docker compose up -d
```

- WordPress: http://localhost:8080
- MailCatcher: http://localhost:1080（`wp_mail` の確認用）

`html/` が空の場合、初回起動時に公式イメージが WordPress ファイルを展開します。  
**コア・テーマ・プラグイン・uploads を含む `html/` 配下は Git 管理対象**です（`.gitignore` で除外するのは `wp-content/cache/` 等の実行時キャッシュのみ）。

### 終了

```bash
docker compose down
```

ボリュームごと削除する場合:

```bash
docker compose down -v
```

### DB バックアップ

```bash
docker exec tecono-wp-db mysqldump -uroot -p"${MYSQL_ROOT_PASSWORD}" \
  --default-character-set=utf8mb4 "${MYSQL_DATABASE}" > db/backup.sql
```

---

## 本番デプロイ（Docker + ホスト DB）

PlaPro / Redmine と同じサーバー構成を想定しています。

| 項目 | 例 |
|------|-----|
| アプリ配置 | `/var/www/vhosts/tecono` |
| Compose | `docker-compose.prod.yml` |
| デプロイ | `sh/deploy.sh` |
| コンテナ公開ポート | `8081`（PlaPro `3000` / Redmine `3001` と競合しない番号） |
| DB 接続先 | ホスト MariaDB（コンテナから `db-host:3306`） |

### 1. ホスト DB の準備

```sql
CREATE DATABASE wordpress CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'wordpress'@'%' IDENTIFIED BY '（本番パスワード）';
GRANT ALL PRIVILEGES ON wordpress.* TO 'wordpress'@'%';
FLUSH PRIVILEGES;
```

既存ダンプがある場合:

```bash
mysql -h 127.0.0.1 -uwordpress -p wordpress < db/backup.sql
```

`172.17.0.1` から接続できない場合は、MariaDB の `bind-address` やユーザー Host（`'wordpress'@'172.%'` 等）を確認してください。

### 2. サーバー上の初回セットアップ

```bash
sudo mkdir -p /var/www/vhosts/tecono
sudo chown $USER:$USER /var/www/vhosts/tecono

git clone <このリポジトリ> /var/www/vhosts/tecono
cd /var/www/vhosts/tecono

cp .env.example .env
# 本番向けに編集:
#   WORDPRESS_DB_HOST=db-host:3306
#   MYSQL_* （ホスト DB）
#   WORDPRESS_HOST_PORT=8081
#   WORDPRESS_HOME=https://example.tecono.jp
#   WORDPRESS_SITEURL=https://example.tecono.jp

mkdir -p html
# 既存サイトを移行する場合は html/ にファイルを配置

chmod +x sh/deploy.sh
./sh/deploy.sh
```

### 3. 以降のデプロイ

```bash
/var/www/vhosts/tecono/sh/deploy.sh
```

### 4. httpd（Apache）設定

`config/httpd/` 内の `example.tecono.jp` とポート `8081` を実ドメイン・`.env` の `WORDPRESS_HOST_PORT` に合わせて編集してから配置します。

```bash
cd /var/www/vhosts/tecono

sudo cp config/httpd/wordpress.conf /etc/httpd/conf.d/wordpress.conf
sudo httpd -t && sudo systemctl reload httpd

sudo certbot certonly --apache -d example.tecono.jp

sudo cp config/httpd/wordpress-le-ssl.conf /etc/httpd/conf.d/wordpress-le-ssl.conf
sudo httpd -t && sudo systemctl reload httpd
```

Basic認証を有効にする場合（`wordpress.conf` / `wordpress-le-ssl.conf` で設定済み）:

```bash
sudo htpasswd -c /var/www/vhosts/tecono/.htpasswd tecono_admin
# 2人目以降は -c を外す
# sudo htpasswd /var/www/vhosts/tecono/.htpasswd another_user

sudo httpd -t && sudo systemctl reload httpd
```

管理画面の **設定 → 一般** でもサイト URL が HTTPS になっているか確認してください（`.env` の `WORDPRESS_HOME` / `WORDPRESS_SITEURL` と一致させると安全です）。

### 5. 503 / 接続できない場合

```bash
curl -I http://127.0.0.1:8081/
docker compose -f docker-compose.prod.yml ps
docker compose -f docker-compose.prod.yml logs wordpress --tail 80
sudo tail -30 /var/log/httpd/wordpress_error.log
```

DB 接続エラー時はコンテナ内からホスト DB へ到達できるか確認します。

```bash
docker compose -f docker-compose.prod.yml exec wordpress bash -c \
  'apt-get update -qq && apt-get install -y -qq default-mysql-client >/dev/null && mysql -hdb-host -u"$WORDPRESS_DB_USER" -p"$WORDPRESS_DB_PASSWORD" -e "SELECT 1"'
```

---

## ディレクトリ構成

```text
tecono/
  ├─ docker-compose.yml          # ローカル（DB + WordPress + MailCatcher）
  ├─ docker-compose.prod.yml     # 本番（WordPress のみ、ホスト DB）
  ├─ config/httpd/              # Apache リバースプロキシ例
  ├─ docker/php/                # アップロード上限など
  ├─ docker/apache/
  ├─ html/                      # WordPress 本体（Git 管理・本番もこのディレクトリをマウント）
  ├─ db/                        # SQL ダンプ置き場
  └─ sh/deploy.sh
```

---

## 既存 WordPress リポジトリからの移行

`/Users/jun.yamaguchi/work/wordpress` など既に `html/` にサイトがある場合:

1. `html/` をこのリポジトリへコピー（または `WORDPRESS_HTML_PATH` でパス指定）
2. 本番用 `.env` で `WORDPRESS_DB_HOST=db-host:3306` と DB 認証情報を設定
3. `wp-config.php` の `DB_HOST` はコンテナ起動時に環境変数で上書きされるため、通常は `docker-compose.prod.yml` の `WORDPRESS_DB_*` で足ります
4. URL が変わる場合は DB の `wp_options`（`siteurl` / `home`）か `WORDPRESS_HOME` / `WORDPRESS_SITEURL` を調整

大容量 `.wpress` インポート時は `docker-compose.override.example.yml` を参照してください。
