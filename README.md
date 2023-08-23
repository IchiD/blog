ローカル環境でアプリを立ち上げる方法  

DockerDesktop, Composer, Node.js, npmがインストールされていることが前提です。  
コマンドを順番に入力します。登録・ログインするにはメール設定しないとエラーが出ます。

mkdir clone-blog

git clone https://github.com/IchiD/blog.git clone-blog

cd clone-blog

cp .env.example .env

composer install

php artisan key:generate

npm install

npm run dev

ターミナル新規ウィンドウを開いてclone-blogのディレクトリに移動

./vendor/bin/sail up

ターミナル新規ウィンドウを開いてclone-blogのディレクトリに移動

./vendor/bin/sail artisan migrate

mkdir -p storage/app/public/images

mkdir -p storage/app/public/thumbnails

vendor/bin/sail artisan storage:link

./vendor/bin/sail artisan db:seed

.envのメール設定をしてください

アプリケーション：http://localhost  
phpMyAdmin：http://localhost:8888