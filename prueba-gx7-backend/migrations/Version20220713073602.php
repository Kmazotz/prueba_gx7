<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220713073602 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE empleado_rol (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE empleado ADD empleado_rol_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE empleado ADD CONSTRAINT FK_D9D9BF527DE6ECEA FOREIGN KEY (empleado_rol_id) REFERENCES empleado_rol (id)');
        $this->addSql('CREATE INDEX IDX_D9D9BF527DE6ECEA ON empleado (empleado_rol_id)');
        $this->addSql('ALTER TABLE roles ADD empleado_rol_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE roles ADD CONSTRAINT FK_B63E2EC77DE6ECEA FOREIGN KEY (empleado_rol_id) REFERENCES empleado_rol (id)');
        $this->addSql('CREATE INDEX IDX_B63E2EC77DE6ECEA ON roles (empleado_rol_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE empleado DROP FOREIGN KEY FK_D9D9BF527DE6ECEA');
        $this->addSql('ALTER TABLE roles DROP FOREIGN KEY FK_B63E2EC77DE6ECEA');
        $this->addSql('DROP TABLE empleado_rol');
        $this->addSql('DROP INDEX IDX_D9D9BF527DE6ECEA ON empleado');
        $this->addSql('ALTER TABLE empleado DROP empleado_rol_id');
        $this->addSql('DROP INDEX IDX_B63E2EC77DE6ECEA ON roles');
        $this->addSql('ALTER TABLE roles DROP empleado_rol_id');
    }
}
