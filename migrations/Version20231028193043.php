<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231028190629 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Supprime l'index existant s'il existe
        $this->addSql('DROP INDEX UNIQ_CHOCOLATE_SHOP_SLUG ON chocolate_shop');

        // Crée le nouvel index unique sur chocolate_shop.slug
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CHOCOLATE_SHOP_SLUG_NEW ON chocolate_shop (slug)');

        // ... autres opérations de migration
    }

    public function down(Schema $schema): void
    {
        // Supprime le nouvel index
        $this->addSql('DROP INDEX UNIQ_CHOCOLATE_SHOP_SLUG_NEW ON chocolate_shop');

        // Récrée l'ancien index s'il était nécessaire
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CHOCOLATE_SHOP_SLUG ON chocolate_shop (slug)');

        // ... autres opérations de migration pour la réversion
    }
}
