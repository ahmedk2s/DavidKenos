<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240102142234 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD profile_picture_filename VARCHAR(255) DEFAULT NULL, ADD cover_picture_filename VARCHAR(255) DEFAULT NULL, DROP cover_picture, DROP profile_picture, DROP image_file_name, DROP images_file_name');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD cover_picture VARCHAR(255) NOT NULL, ADD profile_picture VARCHAR(255) NOT NULL, ADD image_file_name VARCHAR(255) NOT NULL, ADD images_file_name VARCHAR(255) NOT NULL, DROP profile_picture_filename, DROP cover_picture_filename');
    }
}
