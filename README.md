# CMS Post Relations

Laravel package for linking CMS posts to other CMS posts.



## Seeder regisztrálása

A jogosultságok kezdeti beállításához regisztráld a seedert a `database/seeders/DatabaseSeeder.php` fájlban:

```php
use Molitor\CmsPostRelations\Database\Seeders\CmsPostRelationsSeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CmsPostRelationsSeeder::class,
        ]);
    }
}
```
