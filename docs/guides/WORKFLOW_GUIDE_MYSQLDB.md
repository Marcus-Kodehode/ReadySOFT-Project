Sånn du bør jobbe NÅ (2 maskiner, lokal utvikling)

Sånn du bør gjøre det når du får sky-database / prod

1️⃣ Optimal workflow nå: 2 PC-er + Laravel + MySQL lokalt

Målet:
👉 Aldri mer dumpe/importere bare for å få ting til å funke.
👉 Kun kopiere struktur (migrations) + ev. testdata (seeders).

A. Regler du bør følge

Ikke rør tabell-oppsett i Workbench lenger.

Ingen ALTER TABLE manuelt.

Ingen “add column” via GUI.

Alt slikt skal skje via migrations.

All struktur-endring = ny migration

Ny tabell? → migration.

Ny kolonne? → migration.

Endre type / rename? → migration.

Testdata = seeders

Ikke sitt og manuelt putt testdata i Workbench.

Lag seeders som fyller inn demo-brukere, bookings, etc.

B. Konkrett: hvordan du jobber med MIGRATIONS

Si du vil legge til en kolonne phone på users:

php artisan make:migration add_phone_to_users_table --table=users

I migration-filen:

public function up(): void
{
Schema::table('users', function (Blueprint $table) {
$table->string('phone')->nullable();
});
}

public function down(): void
{
Schema::table('users', function (Blueprint $table) {
$table->dropColumn('phone');
});
}

Så:

php artisan migrate

På andre PC-en:

git pull

php artisan migrate

Ferdig. Ingen dump, ingen Workbench-triks. Begge DB-er er like i struktur.

C. Konkrett: hvordan du jobber med SEEDERS

Eksempel: du vil ha noen demo-brukere og demo-bookings.

Lag seeder:

php artisan make:seeder DemoDataSeeder

database/seeders/DemoDataSeeder.php:

public function run(): void
{
\App\Models\User::factory()->create([
'name' => 'Admin ReadySoft',
'email' => 'admin@example.com',
]);

    // legg til mer testdata her ...

}

Legg den inn i DatabaseSeeder:

public function run(): void
{
$this->call([
DemoDataSeeder::class,
]);
}

Kjør seeding:

php artisan db:seed

Eller for helt fresh DB (ny maskin, eller rydd opp):

php artisan migrate:fresh --seed

Da gjør Laravel:

dropper alle tabeller

kjører ALLE migrations

kjører ALLE seeders

På andre PC-en:
Når du har satt opp .env riktig og har tom DB:

php artisan migrate:fresh --seed

→ ferdig, samme struktur + samme testdata.

D. Oppsummert workflow mellom stasjonær og laptop

Når du endrer noe på stasjonær:

Lag migration (og ev. seeder)

Kjør php artisan migrate (og db:seed hvis nødvendig)

Commit + push til Git

På laptopen:

git pull

php artisan migrate
(eller migrate:fresh --seed hvis du vil resette alt)

Kjør prosjektet: php artisan serve

Ingen flere mysql-dumper hver gang.
Dump/import kan du bruke av og til når du vil kopiere “ekte” data, men ikke som hverdags-løsning.

2️⃣ Når du får sky-database / prod (etter Vercel/hosting)

Når du en dag:

hoster backend-et et sted (f.eks. Railway, Laravel Forge-server, etc.)

får en remote MySQL-instans

Da endrer spillet seg:

Både stasjonær, laptop og produksjonsserver kan bruke samme DB.

Ingen synking, ingen dump/import, alt peker mot samme host.

Typisk oppsett:

I sky-databasen får du noe sånt:

HOST: containers-us-west-123.railway.app

PORT: 12345

USER: root eller noe generert

PASS: langt random passord

DB: readysoft_project

I .env.production (eller på serverens miljøvariabler):

DB_CONNECTION=mysql
DB_HOST=containers-us-west-123.railway.app
DB_PORT=12345
DB_DATABASE=readysoft_project
DB_USERNAME=whatever
DB_PASSWORD=superhemmelig

På dev-maskinene kan du:

Enten fortsatt bruke lokal MySQL (trygt, raskt, funker offline)

Eller peke begge mot samme sky-DB for dev også

Jeg ville gjort:

Lokal MySQL for utvikling

Sky-MySQL for staging/prod

Brukt migrasjoner til å holde struktur lik

Brukt seeders til dev-data, og ikke til prod-data

3️⃣ Konkrete “regler” for deg framover

For å gjøre det superkonkret for deg:

Slutt å endre tabeller i Workbench.
Alltid php artisan make:migration.

Når du bytter PC:

git pull

php artisan migrate

hvis tom DB: php artisan migrate:fresh --seed

Bare bruk dump/import når:

du vil kopiere ekte data (for eksempel hvis du har lagt masse reelle bookings, brukere osv. du vil ha med deg)

På sikt:

Få deg en sky-DB (Railway el.l.)

Koble prod/staging mot den

La lokal utvikling styres av migrations/seeders
