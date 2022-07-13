<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220713054051 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE empleado (id INT AUTO_INCREMENT NOT NULL, area_id_id INT NOT NULL, nombre VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, sexo VARCHAR(1) NOT NULL, boletin INT NOT NULL, descripcion LONGTEXT NOT NULL, INDEX IDX_D9D9BF52F28ED68D (area_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE empleado ADD CONSTRAINT FK_D9D9BF52F28ED68D FOREIGN KEY (area_id_id) REFERENCES areas (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE empleado');
    }
}
