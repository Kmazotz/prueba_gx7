<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220713073142 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE empleado_rol_empleado (empleado_rol_id INT NOT NULL, empleado_id INT NOT NULL, INDEX IDX_48A2E5237DE6ECEA (empleado_rol_id), INDEX IDX_48A2E523952BE730 (empleado_id), PRIMARY KEY(empleado_rol_id, empleado_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE empleado_rol_roles (empleado_rol_id INT NOT NULL, roles_id INT NOT NULL, INDEX IDX_B0ECDB067DE6ECEA (empleado_rol_id), INDEX IDX_B0ECDB0638C751C4 (roles_id), PRIMARY KEY(empleado_rol_id, roles_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE empleado_rol_empleado ADD CONSTRAINT FK_48A2E5237DE6ECEA FOREIGN KEY (empleado_rol_id) REFERENCES empleado_rol (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE empleado_rol_empleado ADD CONSTRAINT FK_48A2E523952BE730 FOREIGN KEY (empleado_id) REFERENCES empleado (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE empleado_rol_roles ADD CONSTRAINT FK_B0ECDB067DE6ECEA FOREIGN KEY (empleado_rol_id) REFERENCES empleado_rol (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE empleado_rol_roles ADD CONSTRAINT FK_B0ECDB0638C751C4 FOREIGN KEY (roles_id) REFERENCES roles (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE empleado_rol DROP FOREIGN KEY FK_43CD4CEFFFA9BC5E');
        $this->addSql('ALTER TABLE empleado_rol DROP FOREIGN KEY FK_43CD4CEF393FB813');
        $this->addSql('DROP INDEX IDX_43CD4CEF393FB813 ON empleado_rol');
        $this->addSql('DROP INDEX UNIQ_43CD4CEFFFA9BC5E ON empleado_rol');
        $this->addSql('ALTER TABLE empleado_rol DROP empleado_id_id, DROP rol_id_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE empleado_rol_empleado');
        $this->addSql('DROP TABLE empleado_rol_roles');
        $this->addSql('ALTER TABLE empleado_rol ADD empleado_id_id INT DEFAULT NULL, ADD rol_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE empleado_rol ADD CONSTRAINT FK_43CD4CEFFFA9BC5E FOREIGN KEY (empleado_id_id) REFERENCES empleado (id)');
        $this->addSql('ALTER TABLE empleado_rol ADD CONSTRAINT FK_43CD4CEF393FB813 FOREIGN KEY (rol_id_id) REFERENCES roles (id)');
        $this->addSql('CREATE INDEX IDX_43CD4CEF393FB813 ON empleado_rol (rol_id_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_43CD4CEFFFA9BC5E ON empleado_rol (empleado_id_id)');
    }
}
