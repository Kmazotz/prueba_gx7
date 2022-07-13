<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220713071440 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE empleado_rol (id INT AUTO_INCREMENT NOT NULL, empleado_id_id INT DEFAULT NULL, rol_id_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_43CD4CEFFFA9BC5E (empleado_id_id), INDEX IDX_43CD4CEF393FB813 (rol_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE empleado_rol ADD CONSTRAINT FK_43CD4CEFFFA9BC5E FOREIGN KEY (empleado_id_id) REFERENCES empleado (id)');
        $this->addSql('ALTER TABLE empleado_rol ADD CONSTRAINT FK_43CD4CEF393FB813 FOREIGN KEY (rol_id_id) REFERENCES roles (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE empleado_rol');
    }
}
