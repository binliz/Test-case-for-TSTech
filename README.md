## Test case for TSTech

Требования:
 
        The Laravel Framework
        php >= 7.2.5
        composer
        mysql or mariaDB
        
    
Необходимо склонировать проект, обновить зависимости

        composer install
        
Скоприровать файл **.env.example** в **.env** 
настроить базу данных:

        DB_CONNECTION=mysql
        DB_HOST=127.0.0.1
        DB_PORT=3306
        DB_DATABASE=ваша база
        DB_USERNAME=логин
        DB_PASSWORD=пароль
        
Предварительно я написал пару фабрик для заполнения таблиц данными

Для запуска миграций без заполнения таблиц данными надо выполнить комманду

        php artisan migrate

Если нужны фейковые данные:

        php artisan migrate --seed
        
Или если перегененрировать данные:

        php artisan migrate:fresh --seed
        
Вся "бизнес логика" вынесена в папку "/app/services" 

Запуск репортов:

        php artisan report:groups
        php artisan report:bank
        
Крон задача вынесена в комманду:

        php artisan cron:transactions
        
Коммадна реализована на запуск одноразово каждый день.



