<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231102132137 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        // $this->addSql('ALTER TABLE news ADD CONSTRAINT FK_1DD399506563BFA9 FOREIGN KEY (chocolate_shop_id) REFERENCES chocolate_shop (id)');
        // $this->addSql('CREATE INDEX IDX_1DD399506563BFA9 ON news (chocolate_shop_id)');
        // $this->addSql('ALTER TABLE user CHANGE chocolate_shop_id chocolate_shop_id INT DEFAULT NULL, CHANGE token_registration_life_time token_registration_life_time DATETIME NOT NULL');
        // $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649989D9B62 ON user (slug)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE news DROP FOREIGN KEY FK_1DD399506563BFA9');
        $this->addSql('DROP INDEX IDX_1DD399506563BFA9 ON news');
        $this->addSql('DROP INDEX UNIQ_8D93D649989D9B62 ON user');
        $this->addSql('ALTER TABLE user CHANGE chocolate_shop_id chocolate_shop_id INT NOT NULL, CHANGE token_registration_life_time token_registration_life_time DATETIME DEFAULT NULL');
    }
}
